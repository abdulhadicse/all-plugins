<p>
    <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Display Title', 'latest-post' ); ?></label>
    <input 
        class="widefat" 
        id="<?php echo $this->get_field_id( 'title' ); ?>" 
        name="<?php echo $this->get_field_name( 'title' ); ?>" 
        type="text" 
        value="<?php echo esc_attr( $title ); ?>" 
    />
</p>

<p>
    <label for="<?php echo $this->get_field_name( 'no-of-posts' ); ?>"><?php _e( 'Display Number of Posts', 'latest-post' ); ?></label>
    <input 
        class="widefat" 
        id="<?php echo $this->get_field_id( 'no-of-posts' ); ?>" 
        name="<?php echo $this->get_field_name( 'no-of-posts' ); ?>" 
        type="text" 
        value="<?php echo esc_attr( $no_of_posts ); ?>" 
    />
</p>

<p>
    <label for="<?php echo $this->get_field_name( 'post-order' ) ?>"><?php _e( 'Select Post Order', 'latest-post' ); ?></label>
    <select class="widefat" name="<?php echo $this->get_field_name('post-order'); ?>" id="<?php echo $this->get_field_id('post-order') ?>">
        <option value="ASC" <?php echo ( $order == 'ASC' ) ? 'selected' : '' ?> >ASC</option>
        <option value="DESC" <?php echo ( $order == 'DESC' ) ? 'selected' : '' ?> >DESC</option>
    </select>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'category-list' ) ?>"><?php _e( 'Select Post Category', 'latest-post' ); ?></label>
    
    <?php 
        echo '</br>';
        foreach ($category_list as $key => $val) : ?>
    <input 
        class="checkbox" 
        id="<?php echo $this->get_field_id("category-list") . $val; ?>" 
        name="<?php echo $this->get_field_name("category-list"); ?>[]" 
        type="checkbox" value="<?php echo $val; ?>" <?php checked(in_array($val, $category)); ?> 
    />
    <?php 
    echo $val . '</br>';
    endforeach; ?>
</p>
