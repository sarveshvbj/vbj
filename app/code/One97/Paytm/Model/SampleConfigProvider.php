<?php

   namespace One97\Paytm\Model;

   use Magento\Checkout\Model\ConfigProviderInterface;

   /**
     * Class SampleConfigProvider
    */
   class SampleConfigProvider implements ConfigProviderInterface
   {

    public function getStoredCards(){
      $result = array();
      $result['pavanpalace'] = "Pavan Palace - Visakhapatnam";
      $result['vsquare'] = "V Square - Visakhapatnam";
      $result['kakinada'] = "Kakinada";
      $result['gajuwaka'] = "Gajuwaka";
      $result['parvathipuram'] = "Parvathipuram";
      $result['bobbili'] = "Bobbili";
      $result['rajahmundry'] = "Rajahmundry";
      $result['anakapalli'] = "Anakapalli";
      $result['dilsukhnagar'] = "Dilsukh Nagar- Hyderabad";
      $result['asrao'] = "A.S Rao Nagar - Hyderabad";
       $result['gopalapatnam'] = "Gopalapatnam";
      return $result;
    }

    public function getConfig()
    {

   /* $config = array_merge_recursive($config, [
        'payment' => [
            \One97\Paytm\Model\Payment::CODE => [
                'storedCards' => $this->getStoredCards(),
            ],
        ],
    ]);
    return $config;*/
    return [
            'payment' => [
                'storedCards' => $this->getStoredCards(),
            ],
        ];
   }
}