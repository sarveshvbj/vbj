/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_StoreOptimization
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
define([
    'jquery',
    'Webkul_StoreOptimization/js/jquery.lazyload'
], function ($) {

    return function (options) {
        $(function () {
            $("img.wk_lazy.new-lazy").lazyload();

            $("img.wk_lazy").one("appear", function () {
                $(this).removeClass('new-lazy')
            });
        });
    };
});