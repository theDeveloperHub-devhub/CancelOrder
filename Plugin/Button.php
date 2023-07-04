<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Plugin;

use DeveloperHub\CancelOrder\Model\ResourceModel\Order\CollectionFactory;
use Magento\Backend\Block\Widget\Button\ButtonList;
use Magento\Backend\Block\Widget\Button\Toolbar\Interceptor;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Sales\Model\Order;

class Button
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param Registry $registry
     * @param CollectionFactory $collectionFactory
     * @param UrlInterface $urlBuilder
     * @param RequestInterface $request
     */
    public function __construct(
        Registry $registry,
        CollectionFactory $collectionFactory,
        UrlInterface $urlBuilder,
        RequestInterface $request
    ) {
        $this->_coreRegistry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
    }

    /**
     * @param Interceptor $subject
     * @param AbstractBlock $context
     * @param ButtonList $buttonList
     * @return array|void|null
     */
    public function beforePushButtons(
        Interceptor $subject,
        AbstractBlock $context,
        ButtonList $buttonList
    ) {
        $this->request = $context->getRequest();
        $order = $this->getOrder();
        $orderId = $order->getIncrementId();
        $collection = $this->collectionFactory->create();
        $data = $collection->getData();
        if ($data) {
            foreach ($data as $item) {
                $collectionData[] = $item['order_id'];
                $collectionStatus[] = ['status'=> $item['order_status'],
                                        'id' => $item['order_id']
                ];
            }
            if (in_array($orderId, $collectionData)) {
                foreach ($collectionStatus as $item) {
                    if ($item['id'] == $orderId) {
                        if ($item['status'] >= 2) {
                            return $buttonList->remove('mybutton');
                        }
                        if ($item['status'] >= 1) {
                            $buttonList->add(
                                'mybutton',
                                ['label' => __('Cancel Order Request'),
                                    'onclick' => "setLocation('{$this->urlBuilder->getUrl('developerhub/index/index', ['my_param' => $orderId])}')",
                                    'class' => 'mybutton',
                                ],
                                -1
                            );
                        }
                    }
                }
            }
        } else {
            return [$context, $buttonList];
        }
    }

    /**
     * Retrieve order model object
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('sales_order');
    }
}
