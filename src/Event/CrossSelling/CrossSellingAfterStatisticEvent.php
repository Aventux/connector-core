<?php
namespace Jtl\Connector\Core\Event\CrossSelling;

use Symfony\Component\EventDispatcher\Event;
use Jtl\Connector\Core\Model\Statistic;


class CrossSellingAfterStatisticEvent extends Event
{
    const EVENT_NAME = 'crossselling.after.statistic';

	protected $statistic;

    public function __construct(Statistic &$statistic)
    {
		$this->statistic = $statistic;
    }

    public function getStatistic()
    {
        return $this->statistic;
	}
	
}
