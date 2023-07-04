<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Model;

use DeveloperHub\CancelOrder\Api\Data\CancelOrderInterface;
use DeveloperHub\CancelOrder\Model\ResourceModel\Order;
use DeveloperHub\CancelOrder\Api\CancelOrderRepositoryInterface;

class OrderRepository implements CancelOrderRepositoryInterface
{

    /**
     * @var Order
     */
    private $orderResourceModel;

    /**
     * @param CancelOrderInterface $orderResource
     * @param Order $orderResourceModel
     */
    public function __construct(
        CancelOrderInterface $orderResource,
        Order $orderResourceModel
    ) {
        $this->orderResource = $orderResource;
        $this->orderResourceModel = $orderResourceModel;
    }

    /**
     * @param CancelOrderInterface $cancelOrder
     * @return CancelOrderInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(CancelOrderInterface $cancelOrder)
    {
        $this->orderResourceModel->save($cancelOrder);
        return $cancelOrder;
    }
}
