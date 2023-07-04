<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Model;

use DeveloperHub\CancelOrder\Api\Data\CancelOrderInterface;
use DeveloperHub\CancelOrder\Model\ResourceModel\Order as CustomTabsResourceModel;
use Magento\Framework\Model\AbstractExtensibleModel;

class Order extends AbstractExtensibleModel implements CancelOrderInterface
{

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(CustomTabsResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return parent::getData(self::ID);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }
    /**
     * @return string
     */
    public function getOrderId()
    {
        return parent::getData(self::ORDER_ID);
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function setOrderId($order_id)
    {
        return $this->setData(self::ORDER_ID, $order_id);
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return parent::getData(self::REASON);
    }

    /**
     * @param $reason
     * @return mixed
     */
    public function setReason($reason)
    {
        return $this->setData(self::REASON, $reason);
    }

    /**
     * @return mixed|null
     */
    public function getOrderSatatus()
    {
        return parent::getData(self::STATUS);
    }

    /**
     * @param $status
     * @return mixed
     */
    public function setOrderStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @return mixed|null
     */
    public function getemail()
    {
        return parent::getData(self::CUSTOMER_MAIL);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function setemail($email)
    {
        return $this->setData(self::CUSTOMER_MAIL, $email);
    }

    /**
     * @return mixed|null
     */
    public function getname()
    {
        return parent::getData(self::CUSTOMER_NAME);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function setname($name)
    {
        return $this->setData(self::CUSTOMER_NAME, $name);
    }

    /**
     * @return mixed|null
     */
    public function getItemStatus()
    {
        return parent::getData(self::ITEM_STATUS);
    }

    /**
     * @param $itemStatus
     * @return mixed
     */
    public function setItemStatus($itemStatus)
    {
        return $this->setData(self::ITEM_STATUS, $itemStatus);
    }

    /**
     * @return mixed|null
     */
    public function getItemSku()
    {
        return parent::getData(self::SKU);
    }

    /**
     * @param $itemSku
     * @return mixed
     */

    public function setItemSku($itemSku)
    {
        return $this->setData(self::SKU, $itemSku);
    }

    /**
     * @return mixed|null
     */
    public function getItemId()
    {
        return parent::getData(self::ITEM_ID);
    }

    /**
     * @param $itemId
     * @return mixed
     */
    public function setItemId($itemId)
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }

}
