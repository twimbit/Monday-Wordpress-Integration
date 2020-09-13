<?php

function sync_post() {

	/* create tags and category in monday and set relation */
	create_monday_columns();
	create_category_monday();
	create_tags_monday();
	synch_monday_authors();


	//for create post subscription
	foreach ( get_board_ids( 'create_post' ) as $board_id ) {

		process_post_or_page( 'post', $board_id );

	}

	//for create page subscription
	foreach ( get_board_ids( 'create_page' ) as $board_id ) {

		process_post_or_page( 'page', $board_id );

	}

	die();
}

//add_action( 'wp_ajax_nopriv_synch_post', 'synch_post' );
add_action( 'wp_ajax_sync_post', 'sync_post' );


