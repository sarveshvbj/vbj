<?php
namespace Retailinsights\ConfigProducts\Api;

interface ConfigProductInterface
{
    
     /**
	 * POST returnOrder 
	 * @param string $id
	 * @param string $sku
	 * @param string $ringsize
	 * @param string $purity
	 * @param string $diamond_quality
	 * @return string
	 */
     
    public function getcustomprice($id,$sku,$ringsize,$purity,$diamond_quality);
   

}