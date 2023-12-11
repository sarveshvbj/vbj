define([
    'jquery',
    'uiComponent',
    'mage/storage',
    'ko'
], function ($, Component, storage, ko) {
    return Component.extend({
        initialize: function () {
            this._super();
           $('#custom_price').val(window.VBJ.finalPrice);
           $('#spl_price').val(window.VBJ.specialPrice);
           $('#product_sku').val(window.VBJ.sku);
            $('#price_flag').val(0);
            $("#product-addtocart-button").addClass("disabled-loader");
           this.printAll(false);
            setTimeout(function() {
                          var swatchLength = $('.swatch-attribute').length;
            if(swatchLength >= 1){
                if($('.swatch-attribute').hasClass("metal_color")){
                    $('.swatch-option').first().trigger('click');
                     $("#product-addtocart-button").removeClass("disabled-loader");
                } else $("#product-addtocart-button").removeClass("disabled-loader");
            } else {
              $("#product-addtocart-button").removeClass("disabled-loader");
            }
                    }, 2500);
         
        },
        numberOfClicks: ko.observable(0),
        vbjOffer: ko.observable(false),
        ring_sizes: ko.observable(window.VBJ.default_ringsize),
        purity: ko.observable(window.VBJ.default_purity),
        diamond_quality: ko.observable(window.VBJ.default_diamond),
        purityClick: function (d, event) {
            var p_element = event.target;
            var p_value = $(p_element).attr('data-value');
            this.printAll(true);
            return true;
        },
        diamond_qualityClick: function (d, event) {
            var d_element = event.target;
            var d_value = $(d_element).attr('data-value');
            this.printAll(true);
            return true;
        },
        incrementClickCounter: function () {
            var previousCount = this.numberOfClicks();
            this.numberOfClicks(previousCount + 1);
            $('.need-help').text(this.purity() + '+' + this.diamond_quality());
        },
        GetSelected: function (d, event) {
            var element = event.target;
            var dataAttributeValue = $(element).find(':selected').attr('data-value');
            console.log(dataAttributeValue);
            this.printAll(true);
        },
        printAll: function (ajaxcall) {
            var product_sku = $('#product_sku').val();
            var purity_selected = $('.purity .field.choice').find(':checked').attr('data-value');
            var quality_selected = $('.diamond-quality .field.choice').find(':checked').attr('data-value');
            var ring_selected = $('.ring-sizes select').find(':selected').attr('data-value');

            var purity_selected_id = $('.purity .field.choice').find(':checked').val();
            var quality_selected_id = $('.diamond-quality .field.choice').find(':checked').val();
            var ring_selected_id = $('.ring-sizes select').find(':selected').val();

            var purity_value = (purity_selected != undefined && purity_selected != null) ? purity_selected : '0';
            var quality_value = (quality_selected != undefined && quality_selected != null) ? quality_selected : '0';
            var ring_value = (ring_selected != undefined && ring_selected != null) ? ring_selected : '0';

            console.log('purity: ' + purity_value + ' ringsize: ' + ring_value + ' quality: ' + quality_value);
            var obj = {
                "id": "12",
                "sku": product_sku,
                "ringsize": ring_value,
                "purity": purity_value,
                "diamond_quality": quality_value
            };
            if(ajaxcall) {
                this.myAjaxCall(obj);
            } else {
                var pasv= {
                    "ring_selected":ring_value,
                    "diamond_selected":quality_value,
                    "purity_selected":purity_value,
                    "purity_id":purity_selected_id,
                    "diamond_id":quality_selected_id,
                    "ring_id":ring_selected_id,
                    "product_price":window.VBJ.finalPrice
                }

                this.addValuesToCartFrom(pasv);

            }
            
        },
        addValuesToCartFrom: function(values) {
            if(values) {
             $('#ring_selected').val(values.ring_selected);
             $('#diamond_selected').val(values.diamond_selected);
             $('#purity_selected').val(values.purity_selected);
              $('#ring_selected').attr('data-value',values.ring_id);
             $('#diamond_selected').attr('data-value',values.diamond_id);
             $('#purity_selected').attr('data-value',values.purity_id);
          }     
          //this.printAll(false);
        },
          setPreValues: function() {
            var quality_id =  $('#diamond_selected').attr('data-value');
            var ring_id =  $('#ring_selected').attr('data-value');
            var purity_id = $('#purity_selected').attr('data-value');
             if(quality_id != undefined || quality_id != null) {
                this.diamond_quality(quality_id);
             }      
               if(ring_id != undefined || ring_id != null) {
                this.ring_sizes(ring_id);
             }      
               if(purity_id != undefined || purity_id != null) {
                this.purity(purity_id);
             }      
          //this.printAll(false);
        },
        myAjaxCall: function (dataToPass) {
            var self = this;
            var base_url = window.VBJ.baseUrl;
            var api_url = base_url + 'rest/V1/configproducts/getcustomprice/';
            $('body').trigger('processStart');
            storage.post(
                api_url,
                JSON.stringify(dataToPass),
                true
            ).done(
                function (response) {
                    /** Do your code here */
                    console.log(response[0]['status']);
                    if (response[0]['status'] == 'error') {
                         self.setPreValues();
                         var x = document.getElementById("snackbar");
                             x.className = "show";
                        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                    } else {
                        var res = response[0]['response'];
                        self.frontChange(res);
                    }
                    $('body').trigger('processStop');
                }
            ).fail(
                function (response) {
                    console.log(response);
                    $('body').trigger('processStop');
                }
            );
        },
        frontChange: function (response) {
            var smartbuy = 0;
            if($("#smartbuy_product").prop("checked") == true){
              $('#smartbuy_product').trigger('click');
              smartbuy = 1;
            }
            console.log(response);
              var final_price = this.formatNumber(this.convertPrice(response.final_price));
            var special_price = this.formatNumber(this.convertPrice(response.special_price));
            var metal_price = this.convertPrice(response.metal_price);
            var diamond_price = this.convertPrice(response.diamond_price);
            var stone_price = this.convertPrice(response.stone_price);
            var gst = this.formatNumber(this.convertPrice(response.tax));
            var dis_metal_price = Math.round(response.dis_metal_price);
            var dis_making_price = Math.round(response.dis_making_price);
            var dis_wastage_price = Math.round(response.dis_wastage_price);
            var dis_diamond_price = Math.round(response.dis_diamond_price);
            var after_dis_metal_price = this.convertPrice(response.after_dis_metal_price);
            var after_dis_making_price = this.convertPrice(response.after_dis_making_price);
            var after_dis_wastage_price = this.convertPrice(response.after_dis_wastage_price);
            var after_dis_diamond_price = this.convertPrice(response.after_dis_diamond_price);
            var wastage_price = this.convertPrice(response.wastage_price);
            var making_price = this.convertPrice(response.making_price);
          

            var offer_flag=0;
            var offer_percant=0;
            var offer_text="";
            this.printAll(false);
         //price breakup

            if(dis_metal_price != 0  && after_dis_metal_price != 0) {
               $('.pb-metal').html(window.VBJ.currency+this.formatNumber(metal_price));
               $('.pb-metal-offer').html(window.VBJ.currency+this.formatNumber(after_dis_metal_price));
               $('.pb-metal-offer').addClass('showoffer'); 
               $('.pb-metal').addClass('strike');
               $('.pb-discount').hide();
              offer_flag = 1;
              offer_text ='<span>('+dis_metal_price+'% savings on metal charges)</span>';

            } else {
              $('.pb-metal-offer').removeClass('showoffer'); 
               $('.pb-metal-offer').text(''); 
               $('.pb-metal').removeClass('strike');
                $('.pb-metal').html(window.VBJ.currency+this.formatNumber(metal_price));
            }

             if((dis_making_price != 0 || dis_wastage_price != 0) && (after_dis_making_price+after_dis_wastage_price) != 0) {
               $('.pb-making').html(window.VBJ.currency+this.formatNumber(making_price + wastage_price));
               $('.pb-making-offer').html(window.VBJ.currency+this.formatNumber(after_dis_making_price+after_dis_wastage_price));
                $('.pb-making-offer').addClass('showoffer'); 
               $('.pb-making').addClass('strike'); 
                offer_flag = 1;
                if(dis_making_price != 0) {
                     offer_text +='<span>('+dis_making_price+'% savings on making charges)</span>';
                      $('.pb-discount').hide();
            } else if(dis_wastage_price != 0) {
                 offer_text +='<span>('+dis_wastage_price+'% savings on wastage charges)</span>';
                  $('.pb-discount').hide();
                }
            } else {
               $('.pb-making-offer').removeClass('showoffer'); 
               $('.pb-making-offer').text(''); 
               $('.pb-making').removeClass('strike');
                 $('.pb-making').html(window.VBJ.currency+this.formatNumber(making_price + wastage_price));
            }
              if(dis_diamond_price != 0 && (after_dis_diamond_price+stone_price) !=0) {
               $('.pb-diamond').html(window.VBJ.currency+this.formatNumber(diamond_price+stone_price));
               $('.pb-diamond-offer').html(window.VBJ.currency+this.formatNumber(after_dis_diamond_price+stone_price));
               $('.pb-diamond-offer').addClass('showoffer'); 
               $('.pb-diamond').addClass('strike'); 
                $('.pb-discount').hide();
               offer_flag = 1; 
               offer_text +='<span>('+dis_diamond_price+'% savings on diamond charges)</span>';
    
            } else {
                  $('.pb-diamond-offer').removeClass('showoffer'); 
                   $('.pb-diamond-offer').text(''); 
                  $('.pb-diamond').removeClass('strike');
                 $('.pb-diamond').html(window.VBJ.currency+this.formatNumber(diamond_price+stone_price));
            }
             $('.pb-gst').html(window.VBJ.currency+gst);

        //Offer Text

        if(offer_flag == 1) {
            this.vbjOffer(true);
            $('#product-price-'+window.VBJ.product_id+' .price').css('text-decoration','line-through');
           $('.vbjoffer .offer-head').text('Offer Price :')
           $('.offer-price.text-orange').html('<span id="vbjoffer-price" class="dis_make_charge_actual">'+window.VBJ.currency+special_price+'</span> '+offer_text);
           $('.default-offer.pdp-offer').hide();
            $('#spl_price').val(this.convertPrice(response.special_price));
        } else {
          this.vbjOffer(false);
           $('#product-price-'+window.VBJ.product_id+' .price').css('text-decoration','none');
           $('#spl_price').val(this.convertPrice(response.special_price));
        }
 
        //Final price
            $('#product-price-'+window.VBJ.product_id+' .price').html(window.VBJ.currency + final_price);
            $('#old-price-'+window.VBJ.product_id+' .price').html(window.VBJ.currency + final_price);
            $('#custom_price').val(this.convertPrice(response.final_price));
            $('#price_flag').val(1);

        //Product Details
          //$('.vbj_purity').html(response.purity);
          $('.vbj_diamond_color') .html(response.diamond_color);  
          $('.vbj_diamond_clarity').html(response.diamond_quality);
          $('.vbj_net_weight').html(response.net_weight); 
          $('.vbj_diamond_carat').html(response.diamond_caret); 
          $('.vbj_gross_weight').html(response.gross_weight);

          if(smartbuy == 1) {
              $('#smartbuy_product').trigger('click');
          }

        },
        formatNumber: function (nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        },
        convertPrice : function (value) {
           var currencyRate = parseFloat(window.VBJ.currencyRate);
           var amount = parseFloat(value);
           var price =1;
           if(window.VBJ.currencyCode == 'USD')
            price = parseFloat((amount/currencyRate).toFixed(2));
           else
            price = Math.round(amount);

          return price;
        }
    });
});