<?php

namespace Vvc\Task\Observer;
use Magento\Framework\App\RequestInterface;

class SendMailTask implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    protected $helper;

    protected $logger;

    protected $authSession;

    public function __construct(
       	 \Magento\Framework\App\Request\Http $request
        , \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
        , \Magento\Store\Model\StoreManagerInterface $storeManager
        , \Vvc\Task\Helper\Data $helper
        , \Psr\Log\LoggerInterface $logger
        , \Magento\Backend\Model\Auth\Session $authSession
    )
    {
        $this->request = $request;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        $this->logger = $logger;
        $this->authSession = $authSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $taskInfo = $observer->getData('taskInfo');
        $dataCustomer = $this->helper->getEmailByIdCustomer($taskInfo['assign_to']);
        $store = $this->storeManager->getStore()->getId();
        $taskDetailUrl = $this->storeManager->getStore()->getBaseUrl();
        $taskInfo['taskDetailUrl'] = $taskDetailUrl . 'task/index/updatetask/id/' . $taskInfo['task_id'];
        $firstNameSender = $this->authSession->getUser()->getFirstname();
        $lastNameSender = $this->authSession->getUser()->getLastname();
        $emailSender = $this->authSession->getUser()->getEmail();
        $sender = [
            'name' => $firstNameSender . ' ' . $lastNameSender,
            'email' => $emailSender
        ];
        $receiver = [
            'name' => $dataCustomer['firstname'] . ' ' . $dataCustomer['lastname'],
            'email' => $dataCustomer['email']
        ];

        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('task_email_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store])
                ->setTemplateVars($taskInfo)
                ->setFrom($sender)
                // you can config general email address in Store -> Configuration -> General -> Store Email Addresses
                ->addTo($receiver)
                ->getTransport();

            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->logger->log('ERROR', $e->getMessage(), $taskInfo);
        }

        return $this;
    }
}
?>