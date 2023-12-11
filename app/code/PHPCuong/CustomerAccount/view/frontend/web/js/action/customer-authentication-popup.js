/**
 * GiaPhuGroup Co., Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GiaPhuGroup.com license that is
 * available through the world-wide-web at this URL:
 * https://www.giaphugroup.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    PHPCuong
 * @package     PHPCuong_CustomerAccount
 * @copyright   Copyright (c) 2018-2019 GiaPhuGroup Co., Ltd. All rights reserved. (http://www.giaphugroup.com/)
 * @license     https://www.giaphugroup.com/LICENSE.txt
 */
define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Magento_Customer/js/customer-data',
    'mage/storage',
    'mage/translate',
    'mage/mage',
    'jquery/ui'
], function ($, modal, customerData, storage, $t) {
    'use strict';

    $.widget('phpcuong.customerAuthenticationPopup', {
        options: {
            login: '#customer-popup-login',
            nextRegister: '#customer-popup-registration',
            register: '#customer-popup-register',
            prevLogin: '#customer-popup-sign-in'
        },

        /**
         *
         * @private
         */
        _create: function () {
            var self = this,
                authentication_options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: this.options.popupTitle,
                    buttons: false,
                    modalClass : 'customer-popup'
                };

            modal(authentication_options, this.element);

            // Show the login form in a popup when clicking on the sign in text
            $('body').on('click', '.customer-login-link, '+self.options.prevLogin, function() {
                $(self.options.register).modal('closeModal');
                $(self.options.login).modal('openModal');
                self._setStyleCss();
                return false;
            });

            // Show the registration form in a popup when clicking on the create an account text
            $('body').on('click', '.customer-register-link, '+self.options.nextRegister, function() {
                $(self.options.login).modal('closeModal');
                $(self.options.register).modal('openModal');
                self._setStyleCss(self.options.innerWidth);
                return false;
            });

            this._ajaxSubmit();
            this._resetStyleCss();
        },

        /**
         * Set width of the popup
         * @private
         */
        _setStyleCss: function(width) {
            width = width || 400;
            if (window.innerWidth > 786) {
                this.element.parent().parent('.modal-inner-wrap').css({'max-width': width+'px'});
            }
        },

        /**
         * Reset width of the popup
         * @private
         */
        _resetStyleCss: function() {
            var self = this;
            $( window ).resize(function() {
                if (window.innerWidth <= 786) {
                    self.element.parent().parent('.modal-inner-wrap').css({'max-width': 'initial'});
                } else {
                    self._setStyleCss(self.options.innerWidth);
                }
            });
        },

        /**
         * Submit data by Ajax
         * @private
         */
        _ajaxSubmit: function() {
            var self = this,
                form = this.element.find('form'),
                inputElement = form.find('input');

            inputElement.keyup(function (e) {
                self.element.find('.messages').html('');
            });

            form.submit(function (e) {
                if (form.validation('isValid')) {
                    if((self.element.find('#g-recaptcha-response').val()) !== '') {
                        self.element.find('#vgcaptcha').val(self.element.find('#g-recaptcha-response').val());
                    if (form.hasClass('form-create-account')) {
                        $.ajax({
                            url: $(e.target).attr('action'),
                            data: $(e.target).serializeArray(),
                            showLoader: true,
                            type: 'POST',
                            dataType: 'json',
                            success: function (response) {
                                self._showResponse(response, form.find('input[name="redirect_url"]').val());
                            },
                            error: function() {
                                self._showFailingMessage();
                            }
                        });
                    } else {
                        var submitData = {},
                            formDataArray = $(e.target).serializeArray();
                        formDataArray.forEach(function (entry) {
                            submitData[entry.name] = entry.value;
                        });
                        $('body').loader().loader('show');
                        storage.post(
                            $(e.target).attr('action'),
                            JSON.stringify(submitData)
                        ).done(function (response) {
                            $('body').loader().loader('hide');
                            self._showResponse(response, form.find('input[name="redirect_url"]').val());
                        }).fail(function () {
                            $('body').loader().loader('hide');
                            self._showFailingMessage();
                        });
                    }
                } else {
                   self._showCaptchaMessage();
                }
                }
                return false;
            });
        },

        /**
         * Display messages on the screen
         * @private
         */
        _displayMessages: function(className, message) {
            $('<div class="message '+className+'"><div>'+message+'</div></div>').appendTo(this.element.find('.messages'));
        },

        /**
         * Showing response results
         * @private
         * @param {Object} response
         * @param {String} locationHref
         */
        _showResponse: function(response, locationHref) {
            var self = this,
                timeout = 800;
            this.element.find('.messages').html('');
            if (response.errors) {
                this._displayMessages('message-error error', response.message);
            } else {
                this._displayMessages('message-success success', response.message);
            }
            this.element.find('.messages .message').show();
            var form = $('#product_addtocart_form');
            if(form.valid()) {
            var submitData = form.serialize();
               setTimeout(function() {
                if (!response.errors) {
            var addToCartUrl = $('#add-cart-detail').val();
            $.ajax({                
                  url : addToCartUrl,
                  type:'POST',
                  dataType: 'text',
                  data: submitData                 
                 })
                .done(function(data){
                if(data){ 
                var str1 = data;
                var str2 ='-success';
                if(str1.indexOf(str2) != -1){
                    self.element.modal('closeModal');
                    window.location.href = locationHref;
                } else {
                     self.element.find('.messages').html('');
                     self._displayMessages('message-error error', 'requested qty is not available');
                     self.element.find('.messages .message').show();
                }                                                                                                                 
                    }else{
                         self._showFailingMessage();
                        }
                    })
                .fail(function(textStatus){
                alert(textStatus)
                });  
                }
            }, timeout);
            } else {
              this._displayMessages('message-error error', "Please Select required fields for add to cart");
      }
                   },

        /**
         * Show the failing message
         * @private
         */
        _showFailingMessage: function() {
            this.element.find('.messages').html('');
            this._displayMessages('message-error error', $t('An error occurred, please try again later.'));
            this.element.find('.messages .message').show();
        },
         /**
         * Show the Captcha failing message
         * @private
         */
        _showCaptchaMessage: function() {
            this.element.find('.messages').html('');
            this._displayMessages('message-error error', $t('Please Enter Captcha.'));
            this.element.find('.messages .message').show();
        }
    });

        
         
     
    

    return $.phpcuong.customerAuthenticationPopup;
});

