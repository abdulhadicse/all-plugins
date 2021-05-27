;(function($){

    $(document).ready( function() {

        $( '#wps_form_submit' ).on( 'submit', function(e) {
            e.preventDefault();
            
            var email = $( '#email' ).val();
            var data    = $(this).serialize();

            if( email != '' && isValidateEmail( email ) ) {
                $.post(wps.url, data, function(res) {
                    $( '#error_notice' ).text( res.data );
                    $( '#email' ).hide();
                });
            }
            else {
                $( '#error_notice' ).text( 'Enter a valid email address' );
            }
        });
    });

    function isValidateEmail( email ) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( email );
    }

   

})(jQuery);