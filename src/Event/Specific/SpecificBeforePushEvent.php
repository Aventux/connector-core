<?php
namespace Jtl\Connector\Core\Event\Specific;

use Symfony\Contracts\EventDispatcher\Event;
use Jtl\Connector\Core\Model\Specific;

class SpecificBeforePushEvent extends Event
{
    const EVENT_NAME = 'specific.before.push';

    protected $specific;

    public function __construct(Specific &$specific)
    {
        $this->specific = $specific;
    }

    public function getSpecific()
    {
        return $this->specific;
    }
}
