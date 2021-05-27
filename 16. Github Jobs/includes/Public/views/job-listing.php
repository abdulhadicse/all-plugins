<div id="container">
    
    <div id="g_search">
        <input type="text" id="myInput" placeholder="Search for names.." title="Type in a name" required>
        <input type="submit" id="mySubmit" value="Search">
    </div>
    
    <h6 id="g_search_text1"></h6>
    <p id="g_search_text"></p>

    <table id="myTable">
        <tr class="header">
            <th style="width:60%;"> <?php _e( 'Job Title', 'search-jobs') ?></th>
            <th style="width:40%;"> <?php _e( 'Description', 'search-jobs') ?></th>
        </tr>
    <?php 
    
    if( is_array( $data ) ) {
        foreach( $data as $key => $value ) {
            ?>
            <tr>
                <td> <?php echo esc_html( $value['title'] );  ?> </td>
                <td> <button id="<?php echo $value['id'];  ?>" class='pagerlink' > View Details </button></td>
            </tr>
        <?php
        }
    }
    
    else { 
        echo "<tr> <td> No jobs found. </td> </tr>";
    }
    ?>
    
    </table> 

    <table id="mySearchTable">
        <tr class="header">
            <th style="width:60%;">Job Title</th>
            <th style="width:40%;">Description</th>
        </tr>
    </table> 

    <div id= "job_details">

    </div>
</div>