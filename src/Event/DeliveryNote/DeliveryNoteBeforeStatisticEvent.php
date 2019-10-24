<?php
namespace Jtl\Connector\Core\Event\DeliveryNote;

use Symfony\Component\EventDispatcher\Event;
use Jtl\Connector\Core\Model\QueryFilter;


class DeliveryNoteBeforeStatisticEvent extends Event
{
    const EVENT_NAME = 'deliverynote.before.statistic';

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
