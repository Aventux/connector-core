<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package Jtl\Connector\Core\Base
 */
namespace Jtl\Connector\Core\Connector;

use Jtl\Connector\Core\Application\Application;
use Jtl\Connector\Core\Authentication\ITokenValidator;
use Jtl\Connector\Core\Controller\IController;
use Jtl\Connector\Core\Rpc\RequestPacket;
use Jtl\Connector\Core\Utilities\RpcMethod;
use Jtl\Connector\Core\Rpc\Method;
use Jtl\Connector\Core\Mapper\IPrimaryKeyMapper;
use Jtl\Connector\Core\Result\Action;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Base Connector
 *
 * @access public
 * @author Daniel Böhmer <daniel.boehmer@jtl-software.de>
 */
class BaseConnector implements ConnectorInterface
{
    /**
     * @var IController
     */
    protected $controller;

    /**
     * @var IPrimaryKeyMapper
     */
    protected $primaryKeyMapper;

    /**
     * @var ITokenValidator
     */
    protected $tokenValidator;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var Method
     */
    protected $method;

    /**
     * @var bool
     */
    protected $useSuperGlobals = true;

    /**
     * @var string
     */
    protected $controllerNamespace = 'Jtl\Connector\Core\Controller';

    /**
     * Connector constructor.
     * @param IPrimaryKeyMapper $primar
     * @param ITokenValidator $tokenValidator
     */
    public function __construct(IPrimaryKeyMapper $primaryKeyMapper, ITokenValidator $tokenValidator)
    {
        $this->primaryKeyMapper = $primaryKeyMapper;
        $this->tokenValidator = $tokenValidator;
    }


    public function initialize()
    {
    }

    /**
     * Returns primary key mapper
     *
     * @return IPrimaryKeyMapper
     */
    public function getPrimaryKeyMapper(): IPrimaryKeyMapper
    {
        return $this->primaryKeyMapper;
    }

    /**
     * @return ITokenValidator
     */
    public function getTokenValidator(): ITokenValidator
    {
        return $this->tokenValidator;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     * @return ConnectorInterface
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher): ConnectorInterface
    {
        $this->eventDispatcher = $eventDispatcher;
        
        return $this;
    }
    
    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher(): EventDispatcher
    {
        return $this->eventDispatcher;
    }
    
    /**
     * Method Setter
     *
     * @param \Jtl\Connector\Core\Rpc\Method $method
     * @return BaseConnector
     */
    public function setMethod(Method $method): ConnectorInterface
    {
        $this->method = $method;
        
        return $this;
    }
    
    /**
     * Method Getter
     *
     * @return \Jtl\Connector\Core\Rpc\Method
     */
    public function getMethod(): Method
    {
        return $this->method;
    }
    
    /**
     * @return boolean
     */
    public function getUseSuperGlobals(): bool
    {
        return $this->useSuperGlobals;
    }

    /**
     * @return string
     */
    public function getControllerNamespace(): string
    {
        return $this->controllerNamespace;
    }

    /**
     * (non-PHPdoc)
     * @see \Jtl\Connector\Core\Application\ConnectorInterface::canHandle()
     */
    public function canHandle(Application $application): bool
    {
        $controller = RpcMethod::buildController($this->getMethod()->getController());
        $class = sprintf('%s\%s', $this->getControllerNamespace(), $controller);
        if (class_exists($class)) {
            $this->controller = new $class($application);
            $this->action = $this->getMethod()->getAction();
            
            return method_exists($this->controller, $this->action);
        }
        
        return false;
    }
    
    /**
     * @param RequestPacket $requestpacket
     * @return Action
     * @see \Jtl\Connector\Core\Application\ConnectorInterface::handle()
     */
    public function handle(RequestPacket $requestpacket): Action
    {
        $this->controller->setMethod($this->getMethod());
        
        return $this->controller->{$this->action}($requestpacket->getParams());
    }
    
    /**
     * @see \Jtl\Connector\Core\Application\ConnectorInterface::getController()
     */
    public function getController(): IController
    {
        return $this->controller;
    }
}
