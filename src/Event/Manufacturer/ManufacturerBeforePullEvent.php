<?php
namespace Jtl\Connector\Core\Event\Manufacturer;

use Symfony\Contracts\EventDispatcher\Event;
use Jtl\Connector\Core\Model\QueryFilter;

class ManufacturerBeforePullEvent extends Event
{
    const EVENT_NAME = 'manufacturer.before.pull';

    protected $filter;

    public function __construct(QueryFilter &$filter)
    {
        $this->filter = $filter;
    }

    public function getFilter()
    {
        return $this->filter;
    }
}
