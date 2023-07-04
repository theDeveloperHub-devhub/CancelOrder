<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Model\ResourceModel\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use DeveloperHub\CancelOrder\Model\ResourceModel\Price;

class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \DeveloperHub\CancelOrder\Model\Price::class,
            Price::class
        );
    }
}

