;(function($){

    $(document).ready( function() {

        $( '#namecheck' ).hide();
        $( '#emailcheck' ).hide();

        $( '#contact' ).on( 'submit', function(e) {
            e.preventDefault();
            
            let name    = $( '#name' ).val();
            let email   = $( '#email' ).val();
            var data    = $(this).serialize();

            if ( validateForm( name )  && validateForm ( email) ) {
                $.post( mcf.url, data, function(response){
                    //console.log('data inserted');
                    $( '#contact' ).hide();
                    $( '#form_notice' ).text( 'Thank You for submitting.' );
                } )
                .fail( function(){
                    //console.log('data inserted fialed');
                } );     
            }

            else {
                showError( name , '#namecheck' );
                showError( email , '#emailcheck');  
            }
        })
    });

    function validateForm( field ) {
        if( field.length > 0 ) {
            return true;
        }
    }

    function showError( value, selector) {
        if(  value.length < 1 ) {
            $( selector ).show();
        }
    }

})(jQuery);