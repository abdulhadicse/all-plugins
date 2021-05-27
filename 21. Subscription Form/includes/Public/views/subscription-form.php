<div id ="subscription">
    <form action="" id="wps_form_submit">
        <div class="wps">
            <h2><?php echo esc_html( $title ) ; ?></h2>
        </div>

        <div class="wps" style="background-color:white">
            <input type="text" id="email" placeholder="Email address" name="mail" value="" >
            <p id="error_notice"></p>
        </div>
        
        <input type="hidden" name="action" value="wps_form_submission" value="Subscribe">
        <?php wp_nonce_field('wps_form_action'); ?>
        
        <div class="wps">
            <input type="submit" value="Subscribe">  
        </div>
    </form>
</div>