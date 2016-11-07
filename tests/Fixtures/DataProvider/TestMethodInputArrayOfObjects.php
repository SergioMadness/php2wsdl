<?php

namespace PHP2WSDL\Test\Fixtures\DataProvider;

/**
 * Class TestMethodInputArrayOfObjects
 * @package PHP2WSDL\Test\Fixtures\DataProvider
 *
 * @soap
 */
class TestMethodInputArrayOfObjects
{
    /**
     * @soap
     *
     * @soapParam \stdClass[] objects Input.
     */
    public function inputArrayOfObjects(array $objects)
    {
    }
}
