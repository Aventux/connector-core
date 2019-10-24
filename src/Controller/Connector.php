<?php
/**
 *
 * @copyright 2010-2013 JTL-Software GmbH
 * @package Jtl\Connector\Core\Application
 */

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Authentication\ITokenValidator;
use Jtl\Connector\Core\IO\Path;
use Jtl\Connector\Core\System\Check;
use Jtl\Connector\Core\Exception\JsonException;
use Jtl\Connector\Core\Result\Action;
use Jtl\Connector\Core\Rpc\Error;
use Jtl\Connector\Core\Linker\IdentityLinker;
use Jtl\Connector\Core\Serializer\JMS\SerializerBuilder;
use Jtl\Connector\Core\Logger\Logger;
use Jtl\Connector\Core\Linker\ChecksumLinker;
use Jtl\Connector\Core\Checksum\IChecksum;
use Jtl\Connector\Core\Formatter\ExceptionFormatter;

/**
 * Base Config Controller
 *
 * @access public
 */
class Connector extends AbstractController
{    
    /**
     * Initialize the connector.
     *
     * @param mixed $params Can be empty or not defined and a string.
     */
    public function init($params = null)
    {
        $ret = new Action();
        $ret->setHandled(true);

        try {
            Check::run();

            $ret->setResult(true);
        } catch (\Exception $e) {
            $err = new Error();
            $err->setCode($e->getCode());
            $err->setMessage($e->getMessage());
            $ret->setError($err);
        }

        return $ret;
    }
    
    /**
     * Returns the connector features.
     *
     * @param mixed $params Can be empty or not defined and a string.
     */
    public function features($params = null)
    {
        $ret = new Action();
        try {
            $featureData = file_get_contents(CONNECTOR_DIR . '/config/features.json');
            $features = json_decode($featureData);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw JsonException::decoding(json_last_error_msg(), $featureData);
            }

            $ret->setResult($features);
            $ret->setHandled(true);
        } catch (\Exception $e) {
            $err = new Error();
            $err->setCode($e->getCode());
            $err->setMessage($e->getMessage());
            $ret->setError($err);
        }

        return $ret;
    }

    /**
     * Ack Identity Mappings
     *
     * @param mixed $params empty or ack json string.
     */
    public function ack($params = null)
    {
        $ret = new Action();
        $ret->setHandled(true);

        try {
            $serializer = SerializerBuilder::create();

            $ack = $serializer->deserialize($params, "Jtl\Connector\Core\Model\Ack", 'json');

            $identityLinker = IdentityLinker::getInstance();

            foreach ($ack->getIdentities() as $modelName => $identities) {
                if(!$identityLinker->isType($modelName)) {
                    Logger::write(sprintf('ACK: Unknown core entity (%s)! Skipping related ack\'s...',
                        $modelName
                    ), Logger::WARNING, 'global');
                    continue;
                }

                foreach ($identities as $identity) {
                    $identityLinker->save($identity->getEndpoint(), $identity->getHost(), $modelName);
                }
            }

            // Checksum linking
            foreach ($ack->getChecksums() as $checksum) {
                if ($checksum instanceof IChecksum) {
                    if (!ChecksumLinker::save($checksum)) {
                        Logger::write(sprintf('Could not save checksum for endpoint (%s), host (%s) and type (%s)',
                            $checksum->getForeignKey()->getEndpoint(), 
                            $checksum->getForeignKey()->getHost(),
                            $checksum->getType()
                        ), Logger::WARNING, 'checksum');
                    }
                }
            }

            $ret->setResult(true);            
        } catch (\Exception $e) {
            $err = new Error();
            $err->setCode($e->getCode());
            $err->setMessage($e->getMessage());
            $ret->setError($err);
        }

        return $ret;
    }

    /**
     * Returns the connector auth action
     *
     * @param mixed $params
     * @return \Jtl\Connector\Core\Result\Action
     */
    public function auth($params)
    {
        $action = new Action();
        $action->setHandled(true);
        $authRequest = null;
        $token = '';

        try {
            $serializer = SerializerBuilder::create();

            $authRequest = $serializer->deserialize($params, "Jtl\Connector\Core\Model\AuthRequest", 'json');
        } catch (\Exception $e) {
            $err = new Error();
            $err->setCode($e->getCode());
            $err->setMessage($e->getMessage());
            $action->setError($err);

            return $action;
        }

        // EP checks validation?
        $useEpValidation = false;
        $isValid = false;
        if (is_callable([Application()->getConnector(), 'getTokenValidator'])) {
            $tokenValidator = Application()->getConnector()->getTokenValidator();
            if ($tokenValidator instanceof ITokenValidator) {
                $useEpValidation = true;
                $isValid = $tokenValidator->validate($authRequest);
            }
        }

        if (!$useEpValidation) {
            try {
                $token = Application()->getConnector()->getTokenLoader()->load();
            } catch (\Exception $e) {
                Logger::write(ExceptionFormatter::format($e), Logger::ERROR, 'security');
                $token = '';
            }
        }

        // If credentials are not valid, return appropriate response
        if (($useEpValidation && !$isValid) || (!$useEpValidation && $token !== $authRequest->getToken())) {
            sleep(2);

            $error = new Error();
            $error->setCode(790);
            $error->setMessage("Could not authenticate access to the connector");
            $action->setError($error);

            Logger::write(sprintf("Unauthorized access with token (%s) from ip (%s)", $authRequest->getToken(), $_SERVER['REMOTE_ADDR']), Logger::INFO, 'security');

            return $action;
        }

        if (Application()->getSessionHandler() !== null) {
            $session = new \stdClass();
            $session->sessionId = session_id();
            $session->lifetime = (int) ini_get('session.gc_maxlifetime');
            
            $action->setResult($session);
        } else {
            $error = new Error();
            $error->setCode(789)
                ->setMessage("Could not get any Session");
            $action->setError($error);
        }
        
        return $action;
    }
    
    /**
     * @return Action
     */
    public function debug()
    {
        $action = new Action();
        $action->setHandled(true);
        
        try {
            $path = Path::combine(CONNECTOR_DIR, 'config', 'config.json');
            $configData = file_get_contents($path);
            if ($configData === false) {
                throw new \RuntimeException(sprintf('Cannot read config file %s', $path));
            }
            
            $config = json_decode($configData);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw JsonException::decoding(json_last_error_msg());
            }
    
            $status = false;
            if (!isset($config->developer_logging) || !$config->developer_logging) {
                $status = true;
            }
            
            $config->developer_logging = $status;

            $json = json_encode($config, JSON_PRETTY_PRINT);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw JsonException::encoding(json_last_error_msg());
            }

            file_put_contents($path, $json);
            
            $action->setResult($config);
        } catch (\Exception $e) {
            $error = new Error();
            $error->setCode($e->getCode());
            $error->setMessage($e->getMessage());
            $action->setError($error);
        }
        
        return $action;
    }
    
    /**
     * @return Action
     */
    public function logs()
    {
        $action = new Action();
        $action->setHandled(true);
        
        try {
            $log = [];
            foreach (glob(Path::combine(CONNECTOR_DIR, 'logs', '*.log')) as $file) {
                if (!preg_match('/(global|database){1}.+\.log/', $file)) {
                    continue;
                }
                
                $lines = array_filter(explode(PHP_EOL, file_get_contents($file)), function ($elem) {
                    return  !empty(trim($elem));
                });
                
                $log = array_merge($log, $lines);
            }
            
            $action->setResult($log);
        } catch (\Exception $e) {
            $error = new Error();
            $error->setCode($e->getCode());
            $error->setMessage($e->getMessage());
            $action->setError($error);
        }
    
        return $action;
    }
}
