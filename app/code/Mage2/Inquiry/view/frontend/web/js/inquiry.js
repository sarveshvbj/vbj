 require([
    'jquery','mage/translate',
    'jquery/ui'
    ], function($, $t){
    jQuery(document).ready(function() { 

    	jQuery(".question-listing").hide();
    	jQuery(".view-question label").click(function(){
            jQuery(".question-listing").slideToggle("slow");
        });
  	});
})