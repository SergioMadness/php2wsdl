<?php

namespace PHP2WSDL\Test\Fixtures;

/**
 * Fixture class.
 *
 * @soap
 */
class TestGenerateWSDLForURIWithAllComponents
{
    /**
     * Adds two numbers.
     *
     * @soap
     * @soapParam float p1
     * @soapParam float p2
     * @soapReturn float
     */
    protected function add($p1, $p2)
    {
        return ($p1 + $p2);
    }
}
