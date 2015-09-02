<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\Application
 */

namespace jtl\Connector\Application\Error;

use jtl\Connector\Core\Http\Response;
use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Core\Rpc\Error;
use jtl\Connector\Core\Rpc\RequestPacket;
use jtl\Connector\Core\Rpc\ResponsePacket;
use jtl\Connector\Core\Utilities\RpcMethod;
use jtl\Connector\Event\EventHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ErrorHandler implements IErrorHandler
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Exception
        $exceptionFunc = function(\Exception $e) {
            $trace = $e->getTrace();
            if (isset($trace[0]['args'][0])) {
                $requestpacket = $trace[0]['args'][0];
            }

            $error = new Error();
            $error->setCode($e->getCode())
                ->setData("Exception: " . substr(strrchr(get_class($e), "\\"), 1) . " - File: {$e->getFile()} - Line: {$e->getLine()}")
                ->setMessage($e->getMessage());

            Logger::write($error->getData(), Logger::ERROR, 'global');

            $responsepacket = new ResponsePacket();
            $responsepacket->setError($error)
                ->setId('unknown')
                ->setJtlrpc('2.0');

            $method = 'unknown.unknown';
            if (isset($requestpacket) && $requestpacket !== null && is_object($requestpacket) && $requestpacket instanceof RequestPacket) {
                $responsepacket->setId($requestpacket->getId());
                $method = $requestpacket->getMethod();
            }

            $this->triggerRpcAfterEvent($responsepacket->getPublic(), $method);
            Response::send($responsepacket);
        };

        $this->setExceptionHandler($exceptionFunc);

        // Error
        $errorFunc = function($errno, $errstr, $errfile, $errline, $errcontext) {
            $types = array(
                E_ERROR => array(Logger::ERROR, 'E_ERROR'),
                E_WARNING => array(Logger::WARNING, 'E_WARNING'),
                E_PARSE => array(Logger::WARNING, 'E_PARSE'),
                E_NOTICE => array(Logger::NOTICE, 'E_NOTICE'),
                E_CORE_ERROR => array(Logger::ERROR, 'E_CORE_ERROR'),
                E_CORE_WARNING => array(Logger::WARNING, 'E_CORE_WARNING'),
                E_CORE_ERROR => array(Logger::ERROR, 'E_COMPILE_ERROR'),
                E_CORE_WARNING => array(Logger::WARNING, 'E_COMPILE_WARNING'),
                E_USER_ERROR => array(Logger::ERROR, 'E_USER_ERROR'),
                E_USER_WARNING => array(Logger::WARNING, 'E_USER_WARNING'),
                E_USER_NOTICE => array(Logger::NOTICE, 'E_USER_NOTICE'),
                E_STRICT => array(Logger::NOTICE, 'E_STRICT'),
                E_RECOVERABLE_ERROR => array(Logger::ERROR, 'E_RECOVERABLE_ERROR'),
                E_DEPRECATED => array(Logger::INFO, 'E_DEPRECATED'),
                E_USER_DEPRECATED => array(Logger::INFO, 'E_USER_DEPRECATED')
            );

            if (isset($types[$errno])) {
                $err = "(" . $types[$errno][1] . ") File ({$errfile}, {$errline}): {$errstr}";
                Logger::write($err, $types[$errno][0], 'global');
            } else {
                Logger::write("File ({$errfile}, {$errline}): {$errstr}", Logger::ERROR, 'global');
            }
        };

        $this->setErrorHandler($errorFunc);

        // Shutdown
        $shutdownFunc = function() {
            if (($err = error_get_last())) {
                ob_clean();

                $error = new Error();
                $error->setCode($err['type'])
                    ->setData('Shutdown! File: ' . $err['file'] . ' - Line: ' . $err['line'])
                    ->setMessage($err['message']);

                $responsepacket = new ResponsePacket();
                $responsepacket->setError($error)
                    ->setId('unknown')
                    ->setJtlrpc('2.0');

                $this->triggerRpcAfterEvent($responsepacket->getPublic(), 'unknown.unknown');

                Response::send($responsepacket);
            }
        };

        $this->setShutdownHandler($shutdownFunc);
    }

    protected function triggerRpcAfterEvent($data, $method)
    {
        if ($this->eventDispatcher !== null) {
            $method = RpcMethod::splitMethod($method);
            EventHandler::dispatchRpc($data, $this->eventDispatcher, $method->getController(), $method->getAction(), EventHandler::AFTER);
        }
    }

    public function setExceptionHandler(callable $func)
    {
        set_exception_handler($func);
    }

    public function setErrorHandler(callable $func)
    {
        set_error_handler($func, E_ALL);
    }

    public function setShutdownHandler(callable $func)
    {
        register_shutdown_function($func);
    }

    public function setEventDispatcher(EventDispatcher $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
        return $this;
    }
}