<?php

namespace PHP2WSDL\Test\Fixtures;

/**
 * Example class without @soap annotation.
 */
class TestSimpleClassWithoutSoapTagAnnotations
{

    public $param1 = array();
    public $param2;

    /**
     * Constructor.
     *
     * @param string $param2
     */
    function __construct($param2 = "")
    {
        $this->param2 = $param2;
    }


    /**
     * Adds two numbers.
     *
     * @soap
     * @soapParam float $p1
     * @soapParam float $p2
     * @soapReturn float
     */
    protected function add($p1, $p2)
    {
        return ($p1 + $p2);
    }

    /**
     * Make array.
     *
     * @soap
     * @soapParam mixed $el1
     * @soapParam mixed $el2
     * @soapReturn array
     */
    public function makeArray($el1, $el2)
    {
        return array($el1, $el2);
    }
}
