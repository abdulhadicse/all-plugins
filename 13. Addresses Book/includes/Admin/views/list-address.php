<div class="wrap">
    <h1 class="wp-heading-inline"> <?php _e( 'Address Book', 'address-book'); ?> </h1>
    <a class="page-title-action" href="<?php echo admin_url('admin.php?page=address-books&action=new') ?>"><?php _e('Add New', 'address-book'); ?></a>

    <form action="" method="post">
        <?php
        $table = new AddressBook\Admin\Address_List();
        $table->prepare_items();
        // $table->search_box( 'search', 'search_id' );
        $table->display();
        ?>
    </form>
</div>

