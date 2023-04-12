<?php

function create_block_test_block_block_init() {
	register_block_type( get_template_directory_uri() . '/dist/blocks/test/' );
}
add_action( 'init', 'create_block_test_block_block_init' );