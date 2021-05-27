<div class="container">
        <form action="">
            <label for="fname"><?php echo __( 'Book Author', 'br_book_review' ) ?></label>
            <input style="margin-bottom:5px" type="text" class="widefat" id="fname" name="book_author" value="<?php echo esc_html( $book_author ) ?>" placeholder="Enter Book Author Name">
            <br/>
            <label for="lname"><?php echo __( 'Book Publisher Date', 'br_book_review' ) ?></label>
            <input type="date" style="margin-bottom:5px" class="widefat" id="lname" name="book_publisher" value="<?php echo esc_html( $book_publisher ) ?>" placeholder="Enter Book Publisher Date">
            <br/>
            <label for="subject"><?php echo __( 'ISBN for Book', 'br_book_review' ) ?></label>
            <input type="text" style="margin-bottom:5px" class="widefat" id="lname" name="book_isbn" value="<?php echo esc_html( $book_isbn ) ?>" placeholder="Enter Book ISBN Number">
            <br/>
            <label for="subject"><?php echo __( 'Book Price', 'br_book_review' ) ?></label>
            <input type="text"  style="margin-bottom:5px" class="widefat" id="lname" name="book_price" value="<?php echo esc_html( $book_price ) ?>"  placeholder="Enter Book Price">
            <br/>
            <label for="book_text"><?php echo __( 'Personal Note', 'br_book_review' ) ?></label>
            <textarea id="book_text" name="book_text" rows="4" cols="124"><?php echo esc_html( $book_text ); ?></textarea>
        </form>
    </div>