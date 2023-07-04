<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Controller\Path;

use DeveloperHub\CancelOrder\Model\OrderFactory;
use DeveloperHub\CancelOrder\Model\OrderRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\Store;

class Path implements ActionInterface
{
    const EMAIL_TEMPLATE = 'cancelorder_emailoptions_send';

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    private $_customerSession;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    protected $session;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @param RequestInterface $request
     * @param PageFactory $pageFactory
     * @param OrderFactory $orderFactory
     * @param Session $customerSession
     * @param StateInterface $inlineTranslation
     * @param SessionManagerInterface $session
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param JsonFactory $jsonFactory
     * @param OrderRepository $orderRepository
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface        $request,
        PageFactory             $pageFactory,
        OrderFactory            $orderFactory,
        Session                 $customerSession,
        StateInterface          $inlineTranslation,
        SessionManagerInterface $session,
        Escaper                 $escaper,
        TransportBuilder        $transportBuilder,
        JsonFactory             $jsonFactory,
        OrderRepository         $orderRepository,
        ManagerInterface        $messageManager
    ) {
        $this->request = $request;
        $this->pageFactory = $pageFactory;
        $this->orderFactory = $orderFactory;
        $this->inlineTranslation = $inlineTranslation;
        $this->session = $session;
        $this->escaper = $escaper;
        $this->_customerSession = $customerSession;
        $this->transportBuilder = $transportBuilder;
        $this->jsonFactory = $jsonFactory;
        $this->orderRepository = $orderRepository;
        $this->messageManager = $messageManager;
    }
    public function execute()
    {
        $data = $this->request->getPost();
        $orderInfo = $this->orderFactory->create();
        $sku = $data['sku'];
        if (isset($sku)) {
            $customData = $this->session->getData('custom_data');
            $customerData = $this->_customerSession->getCustomer();
            $email = $customerData->getEmail();
            $name = $customerData->getName();
            $orderInfo->setemail($email);
            $orderInfo->setname($name);
            $orderInfo->setOrderId($customData);
            $orderInfo->setItemSku($data['sku']);
            $orderInfo->setReason($data['devhub-cancel-order-reason']);
            $orderInfo->setItemStatus(1);
            $orderInfo->setItemId($data['order-cancellation']);
            $this->orderRepository->save($orderInfo);
            $this->sendEmail($orderInfo);
            $result = $this->jsonFactory->create();
            return $result->setData([
                'value'=> $orderInfo['order_reason'],
                'row'=> $orderInfo['order_id'],
                'item_status' => $orderInfo['item_status'],
                'item_id' => $data['order-cancellation']
            ]);
        } else {
            $customerData = $this->_customerSession->getCustomer();
            $email = $customerData->getEmail();
            $name = $customerData->getName();
            $orderInfo->setemail($email);
            $orderInfo->setname($name);
            $orderInfo->setOrderId($data['order-cancellation-id']);
            $orderInfo->setReason($data['devhub-cancel-order-reason']);
            $orderInfo->setOrderStatus(1);
            $this->orderRepository->save($orderInfo);
            $this->sendEmail($orderInfo);
            $result = $this->jsonFactory->create();
            return $result->setData([
                'value'=> $orderInfo['order_reason'],
                'row'=> $orderInfo['order_id']
            ]);
        }
    }
    public function sendEmail($orderInfo)
    {
        $data = $orderInfo->getData();
        $customerData = $this->_customerSession->getCustomer();
        $id = $data['order_id'];
        $reason = $data['order_reason'];
        $email = $customerData->getEmail();
        $sendEmailTo = 'support@magento.com';
        $emailSender = $customerData->getName();
        $finalData = [
            'id' => $id,
            'reason' => $reason,
            'recipient_name' => $sendEmailTo,
            'name' => $emailSender
        ];
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml($emailSender),
                'email' => $this->escaper->escapeHtml($email),
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
                ->addTo($sendEmailTo)
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
