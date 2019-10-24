<?php
namespace Jtl\Connector\Core\Event\ProductStockLevel;

use Symfony\Component\EventDispatcher\Event;
use Jtl\Connector\Core\Model\ProductStockLevel;


class ProductStockLevelAfterPullEvent extends Event
{
    const EVENT_NAME = 'productstocklevel.after.pull';

	protected $productStockLevel;

    public function __construct(ProductStockLevel &$productStockLevel)
    {
		$this->productStockLevel = $productStockLevel;
    }

    public function getProductStockLevel()
    {
        return $this->productStockLevel;
	}
	
}
