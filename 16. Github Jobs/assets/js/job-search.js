;(function($){
    
    $('#mySearchTable').hide();
    $('#job_details').hide();

    $("#mySubmit").on( 'click', function() {

        let value = $('#myInput').val();
        
        if ( value !== ''){
            var v_url = 'https://jobs.github.com/positions.json?search='+value;

            let data = {
                "action" : "gj_search",
                "url" : v_url
            }
    
            $('#myInput').val('');
            $('#g_search_text1').text('Search Loading .....');
    
            $.post(gj.url, data, function( response){
                if( response.success == true){
    
                    $('#g_search_text').text('Search data found .....');
                    $('#myTable').hide()
                    $('#g_search_text1').hide();
                    $('#mySearchTable').show();
                    $('#mySearchTable').append( response.data.data );
    
                    clickedButton();
                }
            })
        }
        else{
            $('#g_search_text').text('No data found .....');
        }
       
        
    });

    function clickedButton() {
        $('button.pagerlink').click( function() { 
            var sid = $(this).attr('id');  
            let v_url = 'https://jobs.github.com/positions/'+sid+'.json';
            let data = {
                "action" : "single_search",
                "url" : v_url
            }

            $.post(gj.url, data, function( response){
                if( response.success == true){
                    $('#myTable').hide()
                    $('#mySearchTable').hide();
                    $('#job_details').append( response.data.data );
                    $('#job_details').show();
                }
            })
    
        });
    }

    clickedButton();
    
})(jQuery);