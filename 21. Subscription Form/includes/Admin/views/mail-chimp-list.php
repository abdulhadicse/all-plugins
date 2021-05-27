<p>
    <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Display Subscription Title', 'wsp-form' ); ?></label>
    <input 
        class="widefat" 
        id="<?php echo $this->get_field_id( 'title' ); ?>" 
        name="<?php echo $this->get_field_name( 'title' ); ?>" 
        type="text" 
        value="<?php echo esc_attr( $title ); ?>" 
    />
</p>

<p>
    <label for="<?php echo $this->get_field_name( 'mailchimp-list' ) ?>"><?php _e( 'Select Mail Chimp List', 'wps-form' ); ?></label>
    <select 
        class="widefat" name="<?php echo $this->get_field_name('mailchimp-list'); ?>" 
        id="<?php echo $this->get_field_id('mailchimp-list') ?>"
    >
    <?php foreach ( $data['lists'] as $key => $value ) { ?>
        <option value="<?php echo esc_attr( $value['id'] ) ; ?>" ><?php echo esc_html( $value['name'] ) ?></option>
    <?php } ?>
    </select>
</p>