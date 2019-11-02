<?php
namespace Jtl\Connector\Core\Event\Customer;

use Symfony\Contracts\EventDispatcher\Event;
use Jtl\Connector\Core\Model\QueryFilter;

class CustomerBeforeStatisticEvent extends Event
{
    const EVENT_NAME = 'customer.before.statistic';

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
