<?php

function sync_post() {

	/* create tags and category in monday and set relation */
	create_monday_columns();
	create_category_monday();
	create_tags_monday();
	synch_monday_authors();


	$args = array(
		'numberposts' => - 1,
		'post_type'   => array( 'post', 'page' ),
		'orderby'     => 'title',
		'order'       => 'ASC',
		'post_status' => array( 'pending', 'draft', 'future', 'publish' )
	);

	$posts_array = get_posts( $args );

	foreach ( $posts_array as $post ) {

		//sync post comments
		sync_post_comments( $post->ID );

		// get post tags
		$tags = get_post_tags_array( $post->ID );

		// get post categories
		$categories = get_post_categories_array( $post->ID );

		if ( $post->post_status == 'publish' ) {
			$status = 1;
		} else {
			$status = 0;
		}

		if ( empty( get_monday_user_id( $post->post_author ) ) ) {
			$authors = array();
		} else {
			$authors = array(
				array(
					'id'   => get_monday_user_id( $post->post_author ),
					'kind' => 'person'
				)
			);
		}

		$post_array = array(
			'name'      => $post->post_title,
			'author'    => array(
				'personsAndTeams' => $authors
			),
			'tags'      => array( 'tag_ids' => $tags ),
			'category'  => array( 'tag_ids' => $categories ),
			'status'    => array( 'index' => $status ),
			'date'      => array(
				'date' => get_the_date( 'Y-m-d', $post->ID ),
				'time' => get_the_time( 'G:i:s', $post->ID )
			),
			'post_link' => array( 'url' => get_post_permalink( $post->ID ), 'text' => $post->post_title ),
		);

		if ( $post->post_type == 'post' ) {
			foreach ( get_board_ids( 'create_post' ) as $board_id ) {
				update_or_create_content_auto_synch( $board_id, $post_array, $post );
			}
		}

		if ( $post->post_type == 'page' ) {
			foreach ( get_board_ids( 'create_page' ) as $board_id ) {
				update_or_create_content_auto_synch( $board_id, $post_array, $post );
			}
		}
	}
	die();
}

//add_action( 'wp_ajax_nopriv_synch_post', 'synch_post' );
add_action( 'wp_ajax_sync_post', 'sync_post' );


