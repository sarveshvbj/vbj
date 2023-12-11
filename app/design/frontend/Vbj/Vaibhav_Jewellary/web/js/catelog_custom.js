require(['jquery'], function($) {

    $(document).ready(function() {
        /* Category Page */

                if(!($(".category-banner").length)){

           $('.sidebar').css('margin-top','27px');
        }

                if($("#myDiv").length){

            alert("The element you're testing is present.");

        }


         $(document).on("click", ".checkmark" , function() {	

            $(this).closest('input[type=checkbox]').prop('checked', true);

            var ahref = $(this).parents('a').attr("href");

            window.location.href = ahref;
        });



         $(document).on("click", ".tick_mark" , function() { 

            var ahref = $(this).parents('a').attr("href");

            window.location.href = ahref;
        });

        $(document).on("click", "#filters .filter-check" , function() {

            console.log('filter clicked');
            
            var ahref = $(this).parent('a').attr("href");

            window.location.href = ahref;
        });

    });
});