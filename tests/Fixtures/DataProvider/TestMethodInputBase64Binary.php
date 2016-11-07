<?php

namespace PHP2WSDL\Test\Fixtures\DataProvider;

/**
 * Class TestMethodInputBase64Binary
 * @package PHP2WSDL\Test\Fixtures\DataProvider
 *
 * @soap
 */
class TestMethodInputBase64Binary {

    /**
     * @soap
     * @soapParam base64Binary base64binary Input.
     */
    public function inputBase64Binary($base64binary)
	{
    }
	
	/**
     * @soap
     * @soapParam base64Binary[] base64binaries Input.
     */
    public function inputBase64BinaryArray(array $base64binaries)
	{
    }
}
