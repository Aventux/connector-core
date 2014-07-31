<?php
/**
 * @copyright 2010-2014 JTL-Software GmbH
 * @package jtl\Connector\Type
 */

namespace jtl\Connector\Type;

use jtl\Connector\Type\PropertyInfo;

/**
 * @access public
 * @package jtl\Connector\Type
 */
class Product extends DataModel
{
    protected function loadProperties()
    {
        return array(
			new PropertyInfo('productId', '\jtl\Connector\Model\IdentityKeyPair', null, true, true, false),
			new PropertyInfo('description', 'string', '', false, false, false),
			new PropertyInfo('localeName', 'string', '', true, false, false),
			new PropertyInfo('metaDescription', 'string', '', false, false, false),
			new PropertyInfo('metaKeywords', 'string', '', false, false, false),
			new PropertyInfo('name', 'string', '', false, false, false),
			new PropertyInfo('shortDescription', 'string', '', false, false, false),
			new PropertyInfo('titleTag', 'string', '', false, false, false),
			new PropertyInfo('urlPath', 'string', '', false, false, false),
        );
    }
}
