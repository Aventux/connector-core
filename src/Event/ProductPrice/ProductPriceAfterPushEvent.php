<?php
namespace Jtl\Connector\Core\Event\ProductPrice;

use Symfony\Contracts\EventDispatcher\Event;
use Jtl\Connector\Core\Model\ProductPrice;

class ProductPriceAfterPushEvent extends Event
{
    const EVENT_NAME = 'productprice.after.push';

    protected $productPrice;

    public function __construct(ProductPrice &$productPrice)
    {
        $this->productPrice = $productPrice;
    }

    public function getProductPrice()
    {
        return $this->productPrice;
    }
}
