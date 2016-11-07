<?php

namespace PHP2WSDL\Test\Fixtures\DataProvider;

/**
 * Class TestElementWithMinOccurrences
 * @package PHP2WSDL\Test\Fixtures\DataProvider
 *
 * @soap
 */
class TestElementWithMinOccurrences {

    /**
     * @soap
     * @soapParam \PHP2WSDL\Test\Stub\MinOccurrences object Input.
     */
    public function inputObject($object) {

    }
}
