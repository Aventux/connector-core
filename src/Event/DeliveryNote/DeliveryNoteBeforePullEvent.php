<?php
namespace Jtl\Connector\Core\Event\DeliveryNote;

use Symfony\Contracts\EventDispatcher\Event;
use Jtl\Connector\Core\Model\QueryFilter;

class DeliveryNoteBeforePullEvent extends Event
{
    const EVENT_NAME = 'deliverynote.before.pull';

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
