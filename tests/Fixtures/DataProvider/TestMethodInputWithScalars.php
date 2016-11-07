<?php

namespace PHP2WSDL\Test\Fixtures\DataProvider;

/**
 * Class TestMethodInputWithScalars
 * @package PHP2WSDL\Test\Fixtures\DataProvider
 *
 * @soap
 */
class TestMethodInputWithScalars
{
    /**
     * @soap
     * @soapParam string input Input.
     */
    public function inputString($input)
    {
    }

    /**
     * @soap
     * @soapParam bool input1 Input 1.
     * @soapParam boolean input2 Input 2.
     */
    public function inputBoolean($input1, $input2)
    {
    }

    /**
     * @soap
     * @soapParam int input1 Input 1.
     * @soapParam integer input2 Input 2.
     */
    public function inputInteger($input1, $input2)
    {
    }

    /**
     * @soap
     * @soapParam double input Input.
     */
    public function inputDouble($input)
    {
    }

    /**
     * @soap
     * @soapParam float input Input.
     */
    public function inputFloat($input)
    {
    }

    /**
     * @soap
     * @soapParam decimal input Input.
     */
    public function inputDecimal($input)
    {
    }

    /**
     * @soap
     * @soapParam time input1 Input 1.
     * @soapParam date input2 Input 2.
     * @soapParam \DateTime input3 Input 3.
     */
    public function inputTime($input1, $input2, $input3)
    {
    }
}
