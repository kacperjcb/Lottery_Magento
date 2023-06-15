<?php
namespace dsw\Lottery\Observer;

use dsw\Lottery\Model\LotteryConfig;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Lottery implements ObserverInterface
{
    protected $_messageManager;
    protected $_resource;
    protected $_connection;
    protected LotteryConfig $lotteryConfig;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        ResourceConnection $resource,
        LotteryConfig $lotteryConfig
    ) {
        $this->_messageManager = $messageManager;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection();
        $this->lotteryConfig = $lotteryConfig;
    }

    public function execute(Observer $observer): void
    {
        $order = $observer->getEvent()->getOrder();
        $total = $order->getGrandTotal();

        if ($this->lotteryConfig->isEnabled() && $total > $this->lotteryConfig->getMinimumAmount()) {
            if (rand(1, 100) <= $this->lotteryConfig->getWinningChance()) {
                $customerName = $order->getCustomerFirstname();
                $customerLastname = $order->getCustomerLastname();
                $message = "Congratulations $customerName $customerLastname! You won the lottery!";
                $this->_messageManager->addSuccessMessage($message);

                // Get the product details
                $products = $order->getAllVisibleItems();
                $productDetails = [];

                foreach ($products as $product) {
                    $productId = $product->getProductId();
                    $productName = $product->getName();
                    $productPrice = $product->getPrice();
                    $productDetails[] = [
                        'product_id' => $productId,
                        'product_name' => $productName,
                        'product_price' => $productPrice
                    ];
                }

                // Save the customer name, lastname, and product details to the database
                $tableName = $this->_resource->getTableName('project_lottery');
                $data = [
                    'customer_name' => $customerName,
                    'customer_lastname' => $customerLastname,
                    'winning_chance'=>$this->lotteryConfig->getWinningChance(),
                    'minimum_amount'=>$this->lotteryConfig->getMinimumAmount(),
                    'product_details' => json_encode($productDetails)
                ];
                $this->_connection->insert($tableName, $data);
                
            } else {
                $message = "Sorry, you didn't win the lottery this time.";
                $this->_messageManager->addNoticeMessage($message);
            }
        }
    }
}
