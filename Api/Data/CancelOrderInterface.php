<?php declare(strict_types = 1);

namespace DeveloperHub\CancelOrder\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;
interface CancelOrderInterface extends ExtensibleDataInterface
{
    const ID = 'id';
    const ORDER_ID = 'order_id';
    const REASON = 'order_reason';

    const STATUS = 'order_status';

    const CUSTOMER_MAIL = 'email';

    const CUSTOMER_NAME = 'name';

    const ITEM_STATUS = 'item_status';

    const SKU = 'sku';

    const ITEM_ID = 'item_id';

    /**
     * @return string
     */
    public function getId();

    /**
     * @param $id
     * @return int
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getOrderId();

    /**
     * @param $order_id
     * @return mixed
     */
    public function setOrderId($order_id);

    /**
     * @return string
     */
    public function getReason();

    /**
     * @param $reason
     * @return mixed
     */
    public function setReason($reason);

    /**
     * @return mixed
     */
    public function getOrderSatatus();

    /**
     * @param $status
     * @return mixed
     */
    public function setOrderStatus($status);

    /**
     * @return mixed
     */
    public function getemail();

    /**
     * @param $email
     * @return mixed
     */
    public function setemail($email);

    /**
     * @return mixed
     */
    public function getname();

    /**
     * @param $name
     * @return mixed
     */
    public function setname($name);


    /**
     * @return mixed
     */
    public function getItemStatus();

    /**
     * @param $itemStatus
     * @return mixed
     */
    public function setItemStatus($itemStatus);

    /**
     * @return mixed
     */
    public function getItemSku();

    /**
     * @param $itemSku
     * @return mixed
     */
    public function setItemSku($itemSku);

    /**
     * @return mixed
     */
    public function getItemId();

    /**
     * @param $itemId
     * @return mixed
     */
    public function setItemId($itemId);
}
