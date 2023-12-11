require([
    'jquery'
], function($){
	var priceManagerData = '';
	var StoneMasterData = '';
	var StoneSelectData  = '';
	var add_stone = 0;
	var stonePrice = 0;
	var stoneBusinessPrice = 0;
	var stoneSelect = 0;
	var ajaxcall = 0;
	var getStoneInfo = '';
	
	$(document).ready(function(){
		
		 //http://192.168.1.27/mg222/pricemanger
		 var linkUrl = document.location.origin+'/pricemanger';
		 $.ajax({
				  url: linkUrl,
				  type: 'POST',
				  complete: function (data) {
					 //alert(data.responseText);
					  priceManagerData = JSON.parse(data.responseText);
					  //alert(priceManagerData['24K']);
				  },
	 	  }); 
		 
		 var linkUrl = document.location.origin+'/pricemanger/index/stone';
		 $.ajax({
				  url: linkUrl,
				  type: 'POST',
				  complete: function (data) {
					  //alert(data.responseText);
					  StoneMasterData = JSON.parse(data.responseText);
					  StoneSelectData = '<option value="">Select Stone</option>';
					  $.each(StoneMasterData, function(i, item) {
						 
						  StoneSelectData+="<option rel='"+JSON.stringify(item)+"' price='"+item.price+"' business_price='"+item.business_price+"' value='"+item.itemkey+"'>"+item.name+" ("+item.price+")</option>";
					  });
					  
					  //alert(priceManagerData['24K']);
				  },
	 	  });
		
	})
	
	$( document ).ajaxComplete(function() {
	  if(ajaxcall == 0){
		/*jQuery(".admin__fieldset .admin__field._required .admin__field-small._required .admin__field-control .admin__control-addon input.admin__control-text").each(function(){
			if(jQuery(this).attr('name') == 'product[price]'){
				jQuery(this).addClass('mg-mts-price');
				jQuery(this).attr('readonly', 'readonly');
			}
		});*/
		
		/*======== Stone Section =========*/

		jQuery("textarea[name='product[stone_information]']" ).attr('readonly', 'readonly');
		jQuery("input[name='product[metal_discount_in]']" ).attr('readonly', 'readonly');
		jQuery("input[name='product[discount_diamond_in]']" ).attr('readonly',  'readonly');
		jQuery("input[name='product[discount_making_charge_in]']" ).attr('readonly',  'readonly');
		jQuery("textarea[name='product[stone_information]']" ).hide();
		
		getStoneInfo = jQuery("textarea[name='product[stone_information]']" ).val();
		
		stoneSelect = '<div class="stone_select_section"><select class="admin__control-select addional_stone_selection">'+StoneSelectData+'</select></div>';
		
		var stoneSelectSave ='<div class="stone_buttons"><button class="add_stone primary"><span>Add More Stone</span></button><button class="submit_save_stone primary"><span>Submit Stone Information</span></button></div>';
		
		if(typeof getStoneInfo != 'undefined'){
			if(getStoneInfo !='' && getStoneInfo !='[]'){
				var stoneData= JSON.parse(getStoneInfo);
				var stoneHtml = '';
				jQuery.each(stoneData, function(i, itemselect) {
					//console.log('stone_id=' + itemselect[0]); 
					
					var stoneWeight = itemselect[2];

					var StoneSelectedData = '';
					var stoneAllInfo = ''; 
					var removeButton = '<a class="add-remove-stone"></a>'; 
					$.each(StoneMasterData, function(i, item) {
						 var selectedOPtion = '';
						  if(item.itemkey == itemselect[0]){
							  selectedOPtion = 'selected';
							jQuery.each(item, function(si, stoneitem) {
								//console.log(item);
								if(stoneWeight >= item.startcent && stoneWeight <= item.endcent ){
									 if(si != 'created_at' && si!= 'status' && si!= 'id'){
									  stoneAllInfo+="<div class='stone-info'><b>"+si+": </b>"+stoneitem+"</div>";
									 }
								}
							}); 
						  }
						 StoneSelectedData+="<option rel='"+JSON.stringify(item)+"' price='"+item.price+"' business_price='"+item.business_price+"' "+selectedOPtion+" value='"+item.itemkey+"'>"+item.name+" ("+item.price+")</option>";
					}); 
					 
					stoneAllInfo+="<div class='clear'></div>"
					
					var stoneSelectedDataOptions = '<div class="stone_select_section"><select class="admin__control-select addional_stone_selection">'+StoneSelectedData+'</select><span class="stone-text-box"><input type="text" class="no-peace stone-text" value="'+itemselect[1]+'"  placeholder="No. of Peace" /> <input type="text" class="weight stone-text" value="'+itemselect[2]+'"  placeholder="Weight" /><em class="gm-place">gm</em>'+removeButton+'</span><div class="stone_information_html">'+stoneAllInfo+'</div></div>';
					//stoneHtml+=stoneSelect; 
					
					jQuery("textarea[name='product[stone_information]']" ).parent().append(stoneSelectedDataOptions);

				});
				jQuery("textarea[name='product[stone_information]']" ).parent().append(stoneSelectSave);
				ajaxcall = 1;
			}else{
				jQuery("textarea[name='product[stone_information]']" ).parent().append(stoneSelect+stoneSelectSave);
			}
			ajaxcall = 1;
		}
	
		
		
		jQuery("body").on("change",".addional_stone_selection",function(){
		  jQuery(this).parent(".stone_select_section").find(".stone-text-box").remove();
		  jQuery(this).parent(".stone_select_section").find(".stone_information_html").remove();
		  if(jQuery(this).val()!=''){
			var StoneInfo = JSON.parse(jQuery(this ).find("option:selected").attr('rel'));
			jQuery(this).css({"background-color": ""});
			var stoneAllInfo = '';  
			 jQuery.each(StoneInfo, function(i, item) {
				 if(i != 'created_at' && i!= 'status' && i!= 'id'){
				  stoneAllInfo+="<div class='stone-info'><b>"+i+": </b>"+item+"</div>";
				 }
				 
			 });
			  stoneAllInfo+="<div class='clear'></div>"
			  /*if(add_stone==0){
				var removeButton = '';  
			  }else{
				var removeButton = '<a class="add-remove-stone"></a>';  
			  }*/
			 var removeButton = '<a class="add-remove-stone"></a>';  
		  	jQuery(this).parent(".stone_select_section").append('<span class="stone-text-box"><input type="text" class="no-peace stone-text"  placeholder="No. of Peace" /> <input type="text" class="weight stone-text"  placeholder="Weight" /><em class="gm-place">gm</em>'+removeButton+'</span><div class="stone_information_html">'+stoneAllInfo+'</div>');  
		  }
		});
		
		//add_stone = 1;
		jQuery("body").on("click",".add_stone",function(event){
			
			var isValid = true;
			 jQuery('.stone_select_section input[type="text"],.stone_select_section select').each(function() {
				if ($.trim($(this).val()) == '') {
					isValid = false;
					jQuery(this).css({	
						"background-color": "#FFCECE",
						"border": "1px solid #878787"
					});
				}
				else {
					jQuery(this).css({
						"background-color": ""
					});
				}
			});				
			if (isValid == false){
				event.preventDefault();
				event.stopImmediatePropagation();
			}else{ 
				add_stone++;
				jQuery( stoneSelect ).insertBefore( $( ".stone_buttons" ) );
				event.stopImmediatePropagation();
			}
		});
		
		
		jQuery('body').on("click",".add-remove-stone", function(){
			var obj =  jQuery(this).parents(".stone_select_section");
			obj.css({"background-color": "#FFCECE"});
		   jQuery(this).parents(".stone_select_section").fadeOut( "slow", function() {
			obj.remove();
		  });	
		   event.stopImmediatePropagation();
		});
		
		
		jQuery("body").on("click",".submit_save_stone",function(event){
		   var isValid = true;
			jQuery('.stone_select_section input[type="text"],.stone_select_section select').each(function() {
				if ($.trim($(this).val()) == '') {
					isValid = false;
					jQuery(this).css({	
						"background-color": "#FFCECE",
						"border": "1px solid #878787"
					});
				}else{
					jQuery(this).css({
						"background-color": ""
					});
				}
			});	
		    if (isValid == false){
				event.preventDefault();
				
			}else{ 
			    var stoneCutPrice = 0;
				var stoneCutBusinessPrice = 0;
				var mainArray = []; 
				jQuery('.stone_select_section').each(function() {
					var tempArray = [];
					var stoneActulePrice =  parseFloat(jQuery(this).find("select.addional_stone_selection option:selected").attr("price"));
					var stoneActuleBusinessPrice = parseFloat(jQuery(this).find("select.addional_stone_selection option:selected").attr("business_price"));
					var stoneSelected = jQuery(this).find("select.addional_stone_selection").val();
					var stoneWeight = parseFloat(jQuery(this).find(".stone-text-box input.weight").val());
					var stoneNo = jQuery(this).find(".stone-text-box input.no-peace").val(); 					
					stoneCutPrice+= stoneActulePrice*stoneWeight;
					stoneCutBusinessPrice+= stoneActuleBusinessPrice*stoneWeight;
					 tempArray.push(stoneSelected);
					 tempArray.push(stoneNo);
					 tempArray.push(stoneWeight);
					 tempArray.push(stoneActulePrice*stoneWeight);
					 tempArray.push(stoneActulePrice);
					 mainArray.push(tempArray);
					 
				});
				var stoneInfomation = JSON.stringify(mainArray);
				ajaxcall = 1;
				var linkUrl = document.location.origin+'/admin/pricemanger/index/stonesave';
				
				 $.ajax({
						  url: linkUrl,
						  type: 'POST',
						  data: {'stoneinfo': stoneInfomation , 'stonePrice':stoneCutPrice, 'stoneBusinessPrice': stoneCutBusinessPrice},
						  complete: function (data) {
							  //console.log('stone information set in session');
						  },
				  });
				stonePrice = stoneCutPrice;
				stoneBusinessPrice = stoneCutBusinessPrice;
				priceCalculation();
			}
			event.stopImmediatePropagation();
		});
		
	     jQuery("input[name='product[fixed_price]']").change(function(){
			if(jQuery(this).val() == 1)
				{
					jQuery("input[name='product[price]']" ).attr('readonly', false);
					//alert(jQuery(this).val());
				}
			else
				{
					jQuery("input[name='product[price]']" ).attr('readonly', 'readonly');
				}
		 });
		  
		 var fixprice = jQuery("input[name='product[fixed_price]']").val();
		 if(fixprice == 1){
			 jQuery("input[name='product[price]']" ).attr('readonly', false);
		 }else{
		 	jQuery("input[name='product[price]']" ).attr('readonly', 'readonly');
		 }
		
		jQuery("input[name='product[tax_amount]']" ).attr('readonly', 'readonly');
		jQuery("input[name='product[tax_amount_business]']" ).attr('readonly', 'readonly');
		jQuery("select[name='product[purity]'] option").hide();
        jQuery("select[name='product[purity]'] option:first").text('Select Purity').show();
		
		jQuery("input[name='product[weight]'] , input[name='product[wastage]'], input[name='product[making_charge]'], input[name='product[business_wastage]']" ).keyup(function(){
		  priceCalculation();
		});	
		
		/* ================== Select Tax Id ===========*/
		
		jQuery("select[name='product[tax_class_id]']").change(function(){
		    priceCalculation();
		});
		
		
		/*================ Select Metal =========*/
		
		jQuery("select[name='product[metal]']").change(function(){
            /* code to show/hide purity for selected metals */	
        	var goldPurity=new Array('24K','22K','18K','14K');
        	var silverPurity=new Array('100%','92.5%','91.6%');
        	var platinumPurity=new Array('100%','99.9%','95.0%');		
			
          	metal = jQuery.trim(jQuery("select[name='product[metal]'] option:selected").text());
			
		  if(jQuery.trim(metal)== ''){
			   
                jQuery("select[name='product[purity]'] option").hide();
                jQuery("select[name='product[purity]'] option:first").text('Select Purity').show();  
            }else{
                jQuery("select[name='product[purity]'] option").removeAttr('selected').hide();   
                jQuery("select[name='product[purity]'] option:first").text('Select Purity').show(); 
                var metalPurity=new Array();
                if(metal=='Gold' || metal=='Non Metal')
                    metalPurity=goldPurity;
                else if(metal=='Platinum')
                    metalPurity=platinumPurity;
                else if(metal=='Silver')
                    metalPurity=silverPurity;

                jQuery("select[name='product[purity]'] option").each(function(){
                    returnValue=jQuery.inArray(jQuery(this).text(),metalPurity);
                    if(returnValue!=-1){
                      jQuery(this).show();
                    }
                });
            }
        });
		
		/* =============== Select Purity =========*/

		jQuery("select[name='product[purity]']").change(function(){
           priceCalculation(); // Fire price calculation
        });
		
	  }
	});
	
	
	function getMetalRate(){
		
		metal = jQuery.trim(jQuery("select[name='product[metal]'] option:selected" ).text());
        purity = jQuery.trim(jQuery("select[name='product[purity]'] option:selected" ).text());
			
        index = purity.indexOf('.');
		metalRate = 0;
		metalValue = 0;
        if(metal!='' && purity!=''){     
			if(index!=-1){
				metalCode = purity.substring(0,(index));
			}else{
				metalCode=purity.replace('%','');
			}
			
			if(metal=='Gold' || metal=='Non Metal'){
				//console.log(priceManagerData);
				//console.log(priceManagerData[metalCode]);
				metalValue = priceManagerData[metalCode];
			}else if(metal=='Platinum'){
				if(metalCode==100){
					metalValue = priceManagerData['p_hundred'];
				}else if(metalCode==99){
					metalValue = priceManagerData['ninety_nine'];
				}else if(metalCode==95){
					metalValue = priceManagerData['ninety_five'];
				}
			}else if(metal=='Silver'){
				if(metalCode==100){
					metalValue = priceManagerData['hundred'];
				}else if(metalCode==92){
					metalValue = priceManagerData['ninety_two'];
				}else if(metalCode==91){
					metalValue = priceManagerData['ninety_one'];
				}
			}
			
			return metalValue;
		   }
	}
	
	
	function priceCalculation() {
		
        weight = jQuery.trim(jQuery("input[name='product[weight]']").val()) ? parseFloat(jQuery("input[name='product[weight]']").val()) : 0;		
        wastage = jQuery.trim(jQuery("input[name='product[wastage]']").val()) ? parseFloat(jQuery("input[name='product[wastage]']").val()) : 0;
	    business_wastage = jQuery.trim(jQuery("input[name='product[business_wastage]']").val()) ? parseFloat(jQuery("input[name='product[business_wastage]']").val()) : 0;
        making_charge = jQuery.trim(jQuery("input[name='product[making_charge]']").val()) ? parseFloat(jQuery("input[name='product[making_charge]']").val()) : 0;
		
		
		//console.log(making_charge);
        metal = jQuery.trim(jQuery("select[name='product[metal]'] option:selected" ).text()) ? jQuery.trim(jQuery("select[name='product[metal]'] option:selected" ).text()) : '';
        purity = jQuery.trim(jQuery("select[name='product[purity]'] option:selected" ).text()) ? jQuery.trim(jQuery("select[name='product[purity]'] option:selected" ).text()) : '';
		
        if(metal!='' && weight!=0){
			metalRate    = getMetalRate();
        }
		console.log("metalRate="+metalRate);
        
		wastageweight = wastage*(weight/100);
		
		console.log("wastageweight="+wastageweight);
		
		wastageamnt = wastageweight*metalRate;
		//console.log("metalRate="+metalRate);
        businesswastageweight = business_wastage*(weight/100);
		//console.log("wastageweight="+wastageweight);
		businesswastageamnt = businesswastageweight*metalRate;
		//console.log("wastageamnt="+wastageamnt);
		makingcharges = weight*making_charge;
		//console.log("makingcharges="+makingcharges);
		metalamnt = weight*metalRate;
		//console.log("metalamnt="+metalamnt);
		 
        var stoneRate = stonePrice;
        var stoneBusinessRate = stoneBusinessPrice;
		
		/*jQuery('#tier_price_container input.stonetotal').each(function(){
			if(jQuery(this).is(':visible'))
				stoneRate+=parseFloat(jQuery(this).val());
		});
		jQuery('#tier_price_container input.stonebusinesstotal').each(function(){
			if(jQuery(this).is(':visible'))
				stoneBusinessRate+=parseFloat(jQuery(this).val());
		});*/
					//console.log(stoneRate);
                 
        price = wastageamnt + makingcharges + metalamnt + stoneRate;
		//console.log(price);
        businessprice = businesswastageamnt + makingcharges + metalamnt + stoneBusinessRate;
		
		taxamount_customer = 0;
		taxamount_business =0;
		
		/*======== Tax Function remove from price manager =======*/
		
        /*taxamount_customer_option = priceManagerData['tax_customer'];
        taxamount_business_option = priceManagerData['tax_business'];
		
		
		if(taxamount_customer_option !=0 && taxamount_customer_option!="")
		{
			taxamount_customer = price / taxamount_customer_option;
		}
		else{
			taxamount_customer = 0;
		}
		if(taxamount_business_option !=0 && taxamount_business_option!="")
		{
			taxamount_business = businessprice / taxamount_business_option;
		}
		else
		{
			taxamount_business =0;
		}
		
		/*======== End Tax Function remove from price manager =======*/
		
		
		var taxClassselted = jQuery("select[name='product[tax_class_id]']").val();
		/*if(taxClassselted == 4){
			taxamount_customer = (price * 10)/100;
			taxamount_business = (businessprice * 10)/100;
		}else if(taxClassselted == 5){
			taxamount_customer = (price * 5)/100;
			taxamount_business = (businessprice * 10)/100;
		}*/
		
		
		if(taxClassselted == 9){
			taxamount_customer = (price * 3)/100;
			taxamount_business = (businessprice * 3)/100;
		}else if(taxClassselted == 10){
			taxamount_customer = (price * 0.25)/100;
			taxamount_business = (businessprice * 0.25)/100;
		}else if(taxClassselted == 11){
			taxamount_customer = (price * 12)/100;
			taxamount_business = (businessprice * 12)/100;
		}else if(taxClassselted == 12){
			taxamount_customer = (price * 28)/100;
			taxamount_business = (businessprice * 28)/100;
		}
		
		
		jQuery("input[name='product[tax_amount]']").val(taxamount_customer);
		jQuery("input[name='product[tax_amount_business]']").val(taxamount_business);
        totalprices = price;
        totalbusiness = businessprice; 
        jQuery("input[name='product[price]']").val(totalprices);
        jQuery("input[name='product[business_price]']").val(totalbusiness);
    }
	
});
