<div class="wrap">
    <h1><?php _e( 'New Address', 'address-book' ); ?></h1>
    <?php var_dump($this->errors)?>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr class="row <?php echo $this->has_error('name') ? 'form-invalid' : ''; ?>">
                    <th scope="row">
                        <label for="name"><?php _e( 'Name', 'address-book' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" value="">
                        
                        <?php if ( $this->has_error( 'name' ) ) { ?>
                            <p class="description error"><?php echo $this->get_error( 'name' ); ?></p>
                        <?php } ?>

                    </td>
                </tr>
                <tr class="row <?php echo $this->has_error( 'name' ) ? 'form-invalid' : '' ;?>">
                    <th scope="row">
                        <label for="email"><?php _e( 'Email', 'address-book' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="email" id="email" class="regular-text" value="">
                        
                        <?php if ( $this->has_error( 'email' ) ) { ?>
                            <p class="description error"><?php echo $this->get_error( 'email' ); ?></p>
                        <?php } ?>

                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="address"><?php _e( 'Address', 'address-book' ); ?></label>
                    </th>
                    <td>
                        <textarea class="regular-text" name="address" id="address"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field( 'new-address' ); ?>
        <?php submit_button( __( 'Add Address', 'address-book' ), 'primary', 'submit_address' ); ?>
    </form>
</div>