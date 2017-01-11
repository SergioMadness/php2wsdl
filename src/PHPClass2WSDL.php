<?php

namespace PHP2WSDL;

use DOMElement;
use InvalidArgumentException;
use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\Reflection\ReflectionMethod;

/**
 * Generate a WSDL from a PHP class.
 */
class PHPClass2WSDL
{

    /**
     * Classes to transform.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * The URL of the web service.
     *
     * @var string
     */
    protected $uri;

    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * The URI to the stylesheet file.
     *
     * @var string
     */
    protected $xslUri;

    /**
     * The WSDL document.
     *
     * @var \PHP2WSDL\WSDL
     */
    protected $wsdl;

    /**
     * The soap:operation style.
     *
     * @var array
     */
    protected $bindingStyle = [
        'style'     => 'rpc',
        'transport' => 'http://schemas.xmlsoap.org/soap/http',
    ];

    /**
     * The soap:body operation style options.
     *
     * @var array
     */
    protected $operationBodyStyle = [
        'use'           => 'encoded',
        'encodingStyle' => 'http://schemas.xmlsoap.org/soap/encoding/',
    ];

    /**
     * Constructor.
     *
     * @param mixed  $class The class name from which to generate the WSDL.
     * @param string $uri   The web service URL.
     *
     * @param string $name
     */
    public function __construct($class, $uri, $name)
    {
        if ($class != '') {
            $this->addClass($class);
        }

        $this->uri = $uri;
        $this->name = $name;
    }

    /**
     * Add class
     *
     * @param $class
     *
     * @return $this
     */
    public function addClass($class)
    {
        if (is_string($class) && class_exists($class)) {
            $this->classes[] = $class;
        } elseif (is_object($class)) {
            $this->classes[] = get_class($class);
        } else {
            throw new InvalidArgumentException('Invalid class name or object to generate the WSDL for.');
        }

        return $this;
    }

    /**
     * Set the stylesheet for the WSDL.
     *
     * @param string $xslUri The URI to the stylesheet.
     *
     * @return PHPClass2WSDL
     */
    public function setStylesheet($xslUri)
    {
        $this->xslUri = $xslUri;

        return $this;
    }

    /**
     * Set the binding style.
     *
     * @param string $style     The style (rpc or document).
     * @param string $transport The transport.
     *
     * @return PHPClass2WSDL
     */
    public function setBindingStyle($style, $transport)
    {
        $this->bindingStyle['style'] = $style;
        $this->bindingStyle['transport'] = $transport;

        return $this;
    }

    /**
     * Generate the WSDL DOMDocument.
     *
     * @param boolean $withAnnotation Flag if only the methods with '@soap' annotation should be added.
     *
     * @return $this
     */
    public function generateWSDL()
    {
        $this->wsdl = new WSDL($this->name, $this->uri, $this->xslUri);
        foreach ($this->classes as $class) {
            $this->generateWSDLClass($class);
        }

        return $this;
    }

    /**
     * Generate for single class
     *
     * @param      $class
     *
     * @return $this
     */
    public function generateWSDLClass($class)
    {
        $ref = new ReflectionClass($class);
        $docs = $ref->getReflectionDocComment()->getAnnotationsCollection();
        if ($docs->hasAnnotationTag('soap')) {
            foreach ($ref->getMethods() as $method) {
                $annotations = $method->getReflectionDocComment()->getAnnotationsCollection();
                if ($annotations->hasAnnotationTag('soap')) {
                    $serviceName = $annotations->hasAnnotationTag('soapName') ? $annotations->getAnnotation('soapName')[0]->getDescription() : WSDL::typeToQName($method->getName());

                    $port = $this->wsdl->addPortType($serviceName . 'Port');
                    $binding = $this->wsdl->addBinding($serviceName . 'Binding', 'tns:' . $serviceName . 'Port');

                    $this->wsdl->addSoapBinding($binding, $this->bindingStyle['style'], $this->bindingStyle['transport']);

                    $uri = $annotations->hasAnnotationTag('soapUri') ? $annotations->getAnnotation('soapUri')[0]->getDescription() : $this->uri;
                    $this->wsdl->addService(
                        $serviceName . 'Service', $serviceName . 'Port',
                        'tns:' . $serviceName . 'Binding',
                        $uri
                    );
                    $this->addMethodToWsdl($method, $port, $binding);
                }
            }
        }

        return $this;
    }

    /**
     * Add a method to the WSDL.
     *
     * @param ReflectionMethod $method  The reflection of the method to add.
     * @param DOMElement       $port    The portType element.
     * @param DOMElement       $binding The binding element.
     */
    protected function addMethodToWsdl(ReflectionMethod $method, DOMElement $port, DOMElement $binding)
    {
        $args = [];
        $annotations = [];
        $sequence = [];
        $methodAnnotationsCollection = $method->getReflectionDocComment()->getAnnotationsCollection();

        $qNameMethodName = $methodAnnotationsCollection->hasAnnotationTag('soapName') ? $methodAnnotationsCollection->getAnnotation('soapName')[0]->getDescription() : WSDL::typeToQName($method->getName());

        if ($methodAnnotationsCollection->hasAnnotationTag('soapParam')) {
            /** @var \Wingu\OctopusCore\Reflection\Annotation\Tags\BaseTag $param */
            foreach ($methodAnnotationsCollection->getAnnotation('soapParam') as $param) {
                $paramInfo = $this->parseSoapParam($param->getDescription());
                $annotations[$paramInfo['name']] = $paramInfo['type'];

                if ($this->bindingStyle['style'] === 'document') {
                    $sequenceElement = ['name' => $paramInfo['name'], 'type' => $this->wsdl->getXSDType($paramInfo['type'])];
                    if ($paramInfo['isOptional']) {
                        $sequenceElement['nillable'] = 'true';
                    }

                    $sequence[] = $sequenceElement;
                } else {
                    $args[$paramInfo['name']] = ['type' => $this->wsdl->getXSDType($paramInfo['type'])];
                }
            }
            if ($this->bindingStyle['style'] === 'document') {
                $element = ['name' => $qNameMethodName, 'sequence' => $sequence];
                $args['parameters'] = ['element' => $this->wsdl->addElement($element)];
            }
        }
        $this->wsdl->addMessage($qNameMethodName . 'In', $args);

        $returnType = null;
        if ($methodAnnotationsCollection->hasAnnotationTag('soapReturn') === true) {
            $annotation = $methodAnnotationsCollection->getAnnotation('soapReturn');
            $annotation = reset($annotation);
            $returnType = $annotation->getDescription();
        }

        $isOneWayMessage = ($returnType === null);

        if ($isOneWayMessage === false) {
            $args = [];
            if ($this->bindingStyle['style'] === 'document') {
                $sequence = [];
                if ($returnType !== null) {
                    $sequence[] = [
                        'name' => $qNameMethodName . 'Result',
                        'type' => $this->wsdl->getXSDType($returnType),
                    ];
                }

                $element = ['name' => $qNameMethodName . 'Response', 'sequence' => $sequence];
                $args['parameters'] = ['element' => $this->wsdl->addElement($element)];
            } elseif ($returnType !== null) {
                $args['return'] = ['type' => $this->wsdl->getXSDType($returnType)];
            }

            $this->wsdl->addMessage($qNameMethodName . 'Out', $args);
        }

        // Add the portType operation.
        if ($isOneWayMessage === false) {
            $portOperation = $this->wsdl->addPortOperation(
                $port,
                $qNameMethodName,
                'tns:' . $qNameMethodName . 'In',
                'tns:' . $qNameMethodName . 'Out'
            );
        } else {
            $portOperation = $this->wsdl->addPortOperation($port, $qNameMethodName, 'tns:' . $qNameMethodName . 'In');
        }

        // Add any documentation for the operation.
        $description = $method->getReflectionDocComment()->getFullDescription();
        if (strlen($description) > 0) {
            $this->wsdl->addDocumentation($portOperation, $description);
        }

        // When using the RPC style, make sure the operation style includes a 'namespace' attribute (WS-I Basic Profile 1.1 R2717).
        if ($this->bindingStyle['style'] === 'rpc' && isset($this->operationBodyStyle['namespace']) === false) {
            $this->operationBodyStyle['namespace'] = '' . htmlspecialchars($this->uri);
        }

        // Add the binding operation.
        $operation = $this->wsdl->addBindingOperation(
            $binding,
            $qNameMethodName,
            $this->operationBodyStyle,
            $this->operationBodyStyle
        );
        $this->wsdl->addSoapOperation($operation, $this->uri . '#' . $qNameMethodName);
    }

    /**
     * Dump the WSDL as XML string.
     *
     * @return string
     */
    public function dump()
    {
        return $this->wsdl->dump();
    }

    /**
     * Parse SoapParam description
     *
     * @param string $description
     *
     * @return array
     */
    public function parseSoapParam($description)
    {
        $parts = explode(' ', trim(preg_replace('/\s{2,}/', ' ', $description)));
        $result = [
            'type'       => trim($parts[0]),
            'name'       => trim($parts[1]),
            'isOptional' => trim($parts[count($parts) - 1]) === 'optional',
        ];

        return $result;
    }
}
