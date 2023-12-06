require(['jquery'], function($) {

    $(document).ready(function() {
        /* Category Page */

        //  $(document).on("click", ".checkmark" , function() {	

        //     $(this).closest('input[type=checkbox]').prop('checked', true);

        //     var ahref = $(this).parents('a').attr("href");

        //     window.location.href = ahref;
        // });

        $(document).on("click", ".tab-content .ais-RefinementList-checkbox" , function() {

            console.log('filter clicked');
            
            var ahref = $(this).parent('a').attr("href");

            window.location.href = ahref;
        });

    });
});