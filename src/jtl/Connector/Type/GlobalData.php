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
			new PropertyInfo('companies', '\jtl\Connector\Model\Company', null, false, false, true),
			new PropertyInfo('configGroups', '\jtl\Connector\Model\ConfigGroup', null, false, false, true),
			new PropertyInfo('configItems', '\jtl\Connector\Model\ConfigItem', null, false, false, true),
			new PropertyInfo('crossSellingGroups', '\jtl\Connector\Model\CrossSellingGroup', null, false, false, true),
			new PropertyInfo('crossSellings', '\jtl\Connector\Model\CrossSelling', null, false, false, true),
			new PropertyInfo('currencies', '\jtl\Connector\Model\Currency', null, false, false, true),
			new PropertyInfo('customerGroups', '\jtl\Connector\Model\CustomerGroup', null, false, false, true),
			new PropertyInfo('languages', '\jtl\Connector\Model\Language', null, false, false, true),
			new PropertyInfo('setArticles', '\jtl\Connector\Model\SetArticle', null, false, false, true),
			new PropertyInfo('shipments', '\jtl\Connector\Model\Shipment', null, false, false, true),
			new PropertyInfo('shippingClasss', '\jtl\Connector\Model\ShippingClass', null, false, false, true),
			new PropertyInfo('specialPrices', '\jtl\Connector\Model\SpecialPrice', null, false, false, true),
			new PropertyInfo('taxClasss', '\jtl\Connector\Model\TaxClass', null, false, false, true),
			new PropertyInfo('taxRates', '\jtl\Connector\Model\Company', null, false, false, true),
			new PropertyInfo('taxZones', '\jtl\Connector\Model\TaxZone', null, false, false, true),
			new PropertyInfo('units', '\jtl\Connector\Model\Unit', null, false, false, true),
			new PropertyInfo('warehouses', '\jtl\Connector\Model\Unit', null, false, false, true),
        );
    }
}
