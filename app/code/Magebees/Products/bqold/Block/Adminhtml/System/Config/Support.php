<?php
namespace Magebees\Products\Block\Adminhtml\System\Config;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
class Support extends \Magento\Config\Block\System\Config\Form\Field
{	
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
       $html = '';
		$html .= '<div style="float: left;">
<a href="" target="_blank"><img src="" style="float:left; padding-right: 35px; margin-top: 30px;" /></a></div>
<div style="float:left">
<h2><b>MageBees Import Export Products Extension</b></h2>
<p>
<b>Installed Version: v1.4.3</b><br>
Website: <a target="_blank" href=""></a><br>
Like, share and follow us on 
<a target="_blank" href="">Facebook</a>, 
<a target="_blank" href="">Google+</a> and
<a target="_blank" href="">Twitter</a>.<br>
Do you need Extension Support? Please create support ticket from <a href="" target="_blank">here</a> or <br> please contact us on for quick reply.
</p>
</div>';	
        return $html;
    }
}