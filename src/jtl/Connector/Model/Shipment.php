<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\Model
 * @subpackage DeliveryNote
 */

namespace jtl\Connector\Model;

use \jtl\Core\Model\DataModel;

/**
 * Shipment Model with reference to a deliveryNote
 *
 * @access public
 * @package jtl\Connector\Model
 * @subpackage DeliveryNote
 */
class Shipment extends DataModel
{
    /**
     * @var string Unique shipment id
     */
    protected $_id = '';             
    
    /**
     * @var string Reference to deliveryNote
     */
    protected $_deliveryNoteId = '';             
    
    /**
     * @var string Carrier name
     */
    protected $_carrierName = '';             
    
    /**
     * @var string Optional Tracking URL
     */
    protected $_trackingURL = '';             
    
    /**
     * @var string Optional Identcode
     */
    protected $_identCode = '';             
    
    /**
     * @var string Creation date
     */
    protected $_created = null;             
    
    /**
     * @var string Optional shipment note
     */
    protected $_note = '';             
    
    /**
     * Shipment Setter
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            if ($value === null) {
                $this->$name = null;
                return;
            }
        
            switch ($name) {
                case "_id":
                case "_deliveryNoteId":
                case "_carrierName":
                case "_trackingURL":
                case "_identCode":
                case "_created":
                case "_note":
                
                    $this->$name = (string)$value;
                    break;
            
            }
        }
    }
    
    /**
     * @param string $id Unique shipment id
     * @return \jtl\Connector\Model\Shipment
     */
    public function setId($id)
    {
        $this->_id = (string)$id;
        return $this;
    }
    
    /**
     * @return string Unique shipment id
     */
    public function getId()
    {
        return $this->_id;
    }
    /**
     * @param string $deliveryNoteId Reference to deliveryNote
     * @return \jtl\Connector\Model\Shipment
     */
    public function setDeliveryNoteId($deliveryNoteId)
    {
        $this->_deliveryNoteId = (string)$deliveryNoteId;
        return $this;
    }
    
    /**
     * @return string Reference to deliveryNote
     */
    public function getDeliveryNoteId()
    {
        return $this->_deliveryNoteId;
    }
    /**
     * @param string $carrierName Carrier name
     * @return \jtl\Connector\Model\Shipment
     */
    public function setCarrierName($carrierName)
    {
        $this->_carrierName = (string)$carrierName;
        return $this;
    }
    
    /**
     * @return string Carrier name
     */
    public function getCarrierName()
    {
        return $this->_carrierName;
    }
    /**
     * @param string $trackingURL Optional Tracking URL
     * @return \jtl\Connector\Model\Shipment
     */
    public function setTrackingURL($trackingURL)
    {
        $this->_trackingURL = (string)$trackingURL;
        return $this;
    }
    
    /**
     * @return string Optional Tracking URL
     */
    public function getTrackingURL()
    {
        return $this->_trackingURL;
    }
    /**
     * @param string $identCode Optional Identcode
     * @return \jtl\Connector\Model\Shipment
     */
    public function setIdentCode($identCode)
    {
        $this->_identCode = (string)$identCode;
        return $this;
    }
    
    /**
     * @return string Optional Identcode
     */
    public function getIdentCode()
    {
        return $this->_identCode;
    }
    /**
     * @param string $created Creation date
     * @return \jtl\Connector\Model\Shipment
     */
    public function setCreated($created)
    {
        $this->_created = (string)$created;
        return $this;
    }
    
    /**
     * @return string Creation date
     */
    public function getCreated()
    {
        return $this->_created;
    }
    /**
     * @param string $note Optional shipment note
     * @return \jtl\Connector\Model\Shipment
     */
    public function setNote($note)
    {
        $this->_note = (string)$note;
        return $this;
    }
    
    /**
     * @return string Optional shipment note
     */
    public function getNote()
    {
        return $this->_note;
    }
}