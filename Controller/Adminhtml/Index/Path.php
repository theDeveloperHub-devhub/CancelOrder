<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Controller\Adminhtml\Index;

use DeveloperHub\CancelOrder\Model\OrderFactory;
use DeveloperHub\CancelOrder\Model\OrderRepository;
use DeveloperHub\CancelOrder\Model\ResourceModel\OrderFactory as ResourceOrderFactory;
use DeveloperHub\CancelOrder\Model\ResourceModel\Order\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Session\SessionManagerInterface;

class Path extends Action
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var SessionManagerInterface
     */
    protected $session;


    /**
     * @param Context $context
     * @param Index $index
     * @param OrderFactory $orderFactory
     * @param ResourceOrderFactory $order
     * @param JsonFactory $jsonFactory
     * @param OrderRepository $orderRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context              $context,
        Index                $index,
        OrderFactory         $orderFactory,
        ResourceOrderFactory $order,
        JsonFactory          $jsonFactory,
        OrderRepository      $orderRepository,
        CollectionFactory    $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->orderFactory = $orderFactory;
        $this->index = $index;
        $this->order = $order;
        $this->jsonFactory = $jsonFactory;
        $this->orderRepository = $orderRepository;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost();
        $id = $data['form_data'][0]['value'];
        $collection = $this->collectionFactory->create()->addFieldToFilter('item_id', $id);
        $data = $collection->getData();
        $collectionId = $data[0]['id'];
        $order = $this->orderFactory->create();
        $orderCollection = $this->order->create()->load($order, $collectionId);
        $orderData = $order->setData('item_status', '2');
        $this->orderRepository->save($orderData);
        $email = $data[0]['email'];
        $name = $data[0]['name'];
        $this->index->sendmyEmail($id, $name, $email);
        $result = $this->jsonFactory->create();
        return $result->setData([
            'row'=> $orderData['item_status']
        ]);
    }
}
