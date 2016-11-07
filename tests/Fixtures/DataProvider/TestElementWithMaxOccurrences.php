<?php

namespace PHP2WSDL\Test\Fixtures\DataProvider;

/**
 * Class TestElementWithMaxOccurrences
 * @package PHP2WSDL\Test\Fixtures\DataProvider
 *
 * @soap
 */
class TestElementWithMaxOccurrences {

    /**
     * @soap
     *
     * @soapParam \PHP2WSDL\Test\Stub\MaxOccurrences object Input.
     */
    public function inputObject($object) {

    }
}
