<form method="get" class="search-form">
	<label for="search-form-1"><?php esc_html_e( __("Search..."), 'search_meta' )?></label>
	<input type="search" id="search-form-1" class="search-field" value="" name="sm_search_meta" placeholder="<?php esc_html_e( __("Type author name, ISBN, Book name etc..."), 'search_meta' ) ?>">
	<input type="submit" class="search-submit" name="search" value="Search">
</form>