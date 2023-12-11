<?php
namespace Iksula\Complaint\Observer;


    use Magento\Framework\DataObject;
    use Magento\Framework\Encryption\EncryptorInterface;
    use Magento\Framework\Event\Observer;
    use Magento\Framework\Exception\LocalizedException;
    use Magento\Payment\Observer\AbstractDataAssignObserver;
    use Magento\Quote\Api\Data\PaymentInterface;
    use Magento\Payment\Model\InfoInterface;

    class DataAssignObserver extends AbstractDataAssignObserver
    {
        /**
         * @param Observer $observer
         * @throws LocalizedException
         */
        public function execute(Observer $observer)
        {
            echo "DataAssignObserver start from here";

            $data = $this->readDataArgument($observer);

            echo $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
            exit;
            if (!is_array($additionalData)) {
                return;
            }

            $paymentModel = $this->readPaymentModelArgument($observer);

            $paymentModel->setAdditionalInformation(
                $additionalData
            );

        }
    }