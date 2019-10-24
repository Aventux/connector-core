<?php
namespace Jtl\Connector\Core\Event\Manufacturer;

use Symfony\Component\EventDispatcher\Event;
use Jtl\Connector\Core\Model\QueryFilter;


class ManufacturerBeforeStatisticEvent extends Event
{
    const EVENT_NAME = 'manufacturer.before.statistic';

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
