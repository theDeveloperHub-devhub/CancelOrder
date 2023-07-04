<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Block;

use DeveloperHub\CancelOrder\Model\ResourceModel\Order\CollectionFactory;
use Magento\Framework\View\Element\Template;

class Index extends Template
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Template\Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**w
     * @return array
     */
    public function getCollection()
    {
        $collection = $this->collectionFactory->create();
        $data = $collection->getData();
        foreach ($data as $item) {
            $collectiondata[] = [
               'id' => $item['order_id'],
               'status' => $item['order_status'],
                'reason' => $item['order_reason'],
                'item_status' => $item['item_status'],
                'item_sku' => $item['sku'],
                'item_id' => $item['item_id']
           ];
        }
        if (isset($collectiondata)) {
            return $collectiondata;
        }
        return null;
    }
}
