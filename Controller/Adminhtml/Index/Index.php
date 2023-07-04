<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Controller\Adminhtml\Index;

use DeveloperHub\CancelOrder\Model\OrderFactory;
use DeveloperHub\CancelOrder\Model\OrderRepository;
use DeveloperHub\CancelOrder\Model\ResourceModel\OrderFactory as ResourceOrderFactory;
use DeveloperHub\CancelOrder\Model\ResourceModel\Order\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Area;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\Store;

class Index extends Action
{
    const EMAIL_TEMPLATE = 'cancel_request';

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param OrderFactory $orderFactory
     * @param ResourceOrderFactory $order
     * @param OrderRepository $orderRepository
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context              $context,
        PageFactory          $resultPageFactory,
        OrderFactory         $orderFactory,
        ResourceOrderFactory $order,
        OrderRepository      $orderRepository,
        StateInterface       $inlineTranslation,
        Escaper              $escaper,
        TransportBuilder     $transportBuilder,
        CollectionFactory    $collectionFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->orderFactory = $orderFactory;
        $this->order = $order;
        $this->orderRepository = $orderRepository;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $orderId = $this->getRequest()->getParam('my_param');
        $collection = $this->collectionFactory->create()->addFieldToFilter('order_id', $orderId);
        $data = $collection->getData();
        $id = $data[0]['id'];
        $order = $this->orderFactory->create();
        $orderCollection = $this->order->create()->load($order, $id);
        $orderData = $order->setData('order_status', '2');
        $this->orderRepository->save($orderData);
        $email = $data[0]['email'];
        $name = $data[0]['name'];
        $this->sendmyEmail($orderId, $name, $email);
        $this->getResponse()->setRedirect($this->_redirect->getRefererUrl());
        return $this->resultPageFactory->create();
    }
    public function sendmyEmail($orderId, $name, $email)
    {
        $emailSender = 'support@magento.com';
        $finalData = [
            'id' => $orderId,
            'recipient_name' => $name,
        ];
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml($emailSender),
                'email' => $this->escaper->escapeHtml($emailSender),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier(self::EMAIL_TEMPLATE)
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $finalData])
                ->setFromByScope($sender)
                ->addTo($email)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __($e->getMessage())
            );
        }
    }
}
