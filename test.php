<?php
require_once 'vendor/autoload.php';

/**
 * @soap
 * @soapName testIt
 */
class TestClass
{
    /**
     * @soap
     * @soapName testMethod
     *
     * @param integer $param1
     * @param string  $param2
     *
     * @return bool
     */
    public function testMethod($param1, $param2)
    {
        return false;
    }

    /**
     * @soap
     * @soapUri http://www.api.ru/v2/test
     */
    public function secondMethod()
    {

    }
}

$wsdl = new \PHP2WSDL\PHPClass2WSDL(TestClass::class, 'http://www.www.ru/', 'testwsdl');
$wsdl->generateWSDL();
echo $wsdl->dump();