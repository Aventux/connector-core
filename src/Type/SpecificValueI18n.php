<?php
/**
 * @copyright 2010-2014 JTL-Software GmbH
 * @package Jtl\Connector\Core\Type
 */

namespace Jtl\Connector\Core\Type;

use \Jtl\Connector\Core\Type\PropertyInfo;

/**
 * @access public
 * @package Jtl\Connector\Core\Type
 */
class SpecificValueI18n extends DataType
{
    protected function loadProperties()
    {
        return [
            new PropertyInfo('description', 'string', '', false, false, false),
            new PropertyInfo('metaDescription', 'string', '', false, false, false),
            new PropertyInfo('metaKeywords', 'string', '', false, false, false),
            new PropertyInfo('titleTag', 'string', '', false, false, false),
            new PropertyInfo('urlPath', 'string', '', false, false, false),
            new PropertyInfo('value', 'string', '', false, false, false),
            new PropertyInfo('languageISO', 'string', '', false, false, false)
        ];
    }

    public function isMain()
    {
        return false;
    }
}
