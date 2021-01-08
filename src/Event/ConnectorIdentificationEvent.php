<?php

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\ConnectorIdentification;
use Symfony\Contracts\EventDispatcher\Event;

class ConnectorIdentificationEvent extends Event
{
    /**
     * @var ConnectorIdentification
     */
    protected $connectorIdentification;

    /**
     * ConnectorIdentificationEvent constructor.
     * @param ConnectorIdentification $connectorIdentification
     */
    public function __construct(ConnectorIdentification $connectorIdentification)
    {
        $this->connectorIdentification = $connectorIdentification;
    }

    /**
     * @return ConnectorIdentification
     */
    public function getConnectorIdentification(): ConnectorIdentification
    {
        return $this->connectorIdentification;
    }
}
