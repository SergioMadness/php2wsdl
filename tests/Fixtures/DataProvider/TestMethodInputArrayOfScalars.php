<?php

namespace PHP2WSDL\Test\Fixtures\DataProvider;

/**
 * Class TestMethodInputArrayOfScalars
 * @package PHP2WSDL\Test\Fixtures\DataProvider
 *
 * @soap
 */
class TestMethodInputArrayOfScalars
{
    /**
     * @soap
     *
     * @soapParam array array Simple array.
     */
    public function inputArraySimple(array $array)
    {
    }

    /**
     * @soap
     * @soapParam string[] strings Array of strings.
     */
    public function inputArrayStrings(array $strings)
    {
    }

    /**
     * @soap
     * @soapParam int[] ints Array of integers.
     */
    public function inputArrayInt(array $ints)
    {
    }

    /**
     * @soap
     * @soapParam integer[] integers Array of integers.
     */
    public function inputArrayInteger(array $integers)
    {
    }

    /**
     * @soap
     * @soapParam decimal[] decimals Array of decimals.
     */
    public function inputArrayDecimal(array $decimals)
    {
    }

    /**
     * @soap
     * @soapParam float[] floats Array of floats.
     */
    public function inputArrayFloat(array $floats)
    {
    }
}
