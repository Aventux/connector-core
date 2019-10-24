<?php
namespace Jtl\Connector\Core\Event\DeliveryNote;

use Symfony\Component\EventDispatcher\Event;
use Jtl\Connector\Core\Model\DeliveryNote;


class DeliveryNoteAfterPullEvent extends Event
{
    const EVENT_NAME = 'deliverynote.after.pull';

	protected $deliveryNote;

    public function __construct(DeliveryNote &$deliveryNote)
    {
		$this->deliveryNote = $deliveryNote;
    }

    public function getDeliveryNote()
    {
        return $this->deliveryNote;
	}
	
}
