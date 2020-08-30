<?php
//generate hash
function generate_hash( $data ) {

	return hash_hmac( 'sha256',
		$data,
		AUTH_SALT
	);
}

//get author full name by id
function get_author_full_name( $author_id ) {
	$fname     = get_the_author_meta( 'first_name', $author_id );
	$lname     = get_the_author_meta( 'last_name', $author_id );
	$full_name = '';

	if ( empty( $fname ) ) {
		$full_name = $lname;
	} elseif ( empty( $lname ) ) {
		$full_name = $fname;
	} else {
		//both first name and last name are present
		$full_name = "{$fname} {$lname}";
	}

	return $full_name;
}

//get wp user username
function get_author_username( $author_id ) {
	return get_the_author_meta( 'display_name', $author_id );
}

//sending data to endpoint
function send_data_to_app( $post, $action, $sub_id ) {
	$host = 'MONDAY_APP_URL';

	$data = array(
		'post'           => wp_json_encode( $post ),
		'action'         => $action,
		'subscriptionId' => $sub_id
	);

	$ch   = curl_init( $host );
	$data = json_encode( $data );
	//curl_setopt( $ch, CURLOPT_USERPWD, $username . ":" . $password );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json' ) );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	$response = curl_exec( $ch );
}

//create monday columns and check conditions
function create_monday_columns() {

	global $monday_mutation, $monday_query;

	foreach ( get_board_ids() as $board_id ) {

		$column_array = array(
			array( 'column_name' => 'Author', 'column_type' => 'people', 'column_id' => '' ),
			array( 'column_name' => 'Tags', 'column_type' => 'tags', 'column_id' => '' ),
			array( 'column_name' => 'Category', 'column_type' => 'tags', 'column_id' => '' ),
			array( 'column_name' => 'Status', 'column_type' => 'status', 'column_id' => '' ),
			array( 'column_name' => 'Date', 'column_type' => 'date', 'column_id' => '' ),
			array( 'column_name' => 'Post Link', 'column_type' => 'link', 'column_id' => '' ),
		);

		$monday_columns = $monday_query->get_board_columns( $board_id->boardId );
		//print_r( $monday_columns );
		foreach ( $monday_columns as $monday_column ) {
			foreach ( $column_array as $key => $column ) {
				if ( $monday_column['title'] != 'Name' ) {
					if ( $monday_column['title'] == $column['column_name'] ) {
						$column_array[ $key ]['column_id'] = $monday_column['id'];
						//array_push( $column_array, $column );
					}
				}
			}
		}

		foreach ( $column_array as $key => $column ) {
			if ( $column_array[ $key ]['column_id'] == '' ) {

				$monday_id = $monday_mutation->create_column( $board_id->boardId, $column['column_name'], $column['column_type'] )['data']['create_column']['id'];

				$column_array[ $key ]['column_id'] = $monday_id;
				//array_push( $update_array, $column );
			}
		}

		if ( ! empty( $column_array ) ) {
			update_option( 'monday_' . $board_id->boardId, $column_array );
		}
	}
}

//create tags on monday and synch with wordpress
function create_tags_monday() {

	global $monday_mutation;
	$args = array(
		'taxonomy'   => 'post_tag',
		'hide_empty' => false,
	);

	foreach ( get_board_ids() as $board_id ) {

		//$sub_id = get_subscription_id( $board_id->boardId );
		foreach ( get_tags( $args ) as $tag ) {
			if ( empty( get_monday_term_id( $tag->term_id ) ) ) {
				$monday_tag_id = $monday_mutation->create_tag( $board_id->boardId, $tag->name )['data']['create_or_get_tag']['id'];
				update_monday_terms( '', $monday_tag_id, $tag->term_id );
			}

		}

	}
}

//create tags on monday and synch with wordpress
function create_category_monday() {

	global $monday_mutation;
	$args = array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
		'exclude'    => 1
	);

	foreach ( get_board_ids() as $board_id ) {
		foreach ( get_categories( $args ) as $category ) {
			if ( empty( get_monday_term_id( $category->term_id ) ) ) {
				$monday_category_id = $monday_mutation->create_tag( $board_id->boardId, $category->name )['data']['create_or_get_tag']['id'];
				update_monday_terms( '', $monday_category_id, $category->term_id );
			}
		}
	}
}

//for monday post create
function create_monday_post_item( $postId, $post_status ) {

	$post = get_post( $postId );

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


	foreach ( get_board_ids() as $board_id ) {
		if ( get_board_action( $board_id->boardId ) == 'create_post' && $post->post_type == 'post' ) {
			update_or_create_content( $board_id, $post_array, $post_status, $post );
		} else if ( get_board_action( $board_id->boardId ) == 'create_page' && $post->post_type == 'page' ) {
			update_or_create_content( $board_id, $post_array, $post_status, $post );
		}
	}
}

// synch monday authors with wordpress authors
function synch_monday_authors() {
	global $monday_query;

	$wp_users     = get_users();
	$monday_users = $monday_query->get_monday_user();

	foreach ( $wp_users as $wp_user ) {
		foreach ( $monday_users as $monday_user ) {
			if ( empty( get_monday_user_id( $wp_user->data->ID ) ) ) {
				if ( $wp_user->data->user_email == $monday_user['email'] ) {
					create_monday_users( '', $monday_user['id'], $wp_user->data->ID );
				} else {
					update_monday_users( '', $monday_user['id'], $wp_user->data->ID );
				}
			}
		}
	}
}

//check auth
function check_auth( $appKey, $appSecret ) {

	$wp_app_key    = get_option( 'authorization' )['monday_key'];
	$wp_app_secret = get_option( 'authorization' )['monday_secret'];

	if ( $wp_app_key == $appKey && $wp_app_secret == $appSecret ) {
		return true;
	}

	return false;
}

//sync post comments
function sync_post_comments( $postId ) {

	global $monday_mutation;
	$comments = get_comments( array(
		'post_id' => $postId,
	) );

	$item_id = get_post_item_id( $postId );

	foreach ( $comments as $comment ) {
		if ( empty( get_comment_meta( $comment->comment_ID, 'update_id' ) ) ) {
			$update_id = $monday_mutation->create_update( $item_id, $comment->comment_content );
			add_comment_meta( $comment->comment_ID, 'update_id', $update_id['data']['create_update']['id'] );
		}
	}

}

//create monday post
function create_monday_post_item_function( $boardId, $post_post_array, $post_title, $sub_id, $postId ) {
	global $monday_mutation;
	$item_id_create = $monday_mutation->create_item( $boardId, $post_post_array, $post_title )['data']['create_item']['id'];
	create_monday_post( $sub_id, $item_id_create, $postId );
}

//get post tags
function get_post_tags_array( $postId ) {
	$tags = array();
	if ( get_the_tags( $postId ) ) {
		foreach ( get_the_tags( $postId ) as $tag ) {
			$monday_tag_id = get_monday_term_id( $tag->term_id );
			array_push( $tags, $monday_tag_id );
		}
	}

	return $tags;
}

//get post categories array
function get_post_categories_array( $postId ) {
	$categories = array();
	if ( get_the_category( $postId ) ) {
		foreach ( get_the_category( $postId ) as $category ) {
			if ( $category->term_id != 1 ) {
				$monday_category_id = get_monday_term_id( $category->term_id );
				array_push( $categories, $monday_category_id );
			}
		}
	}

	return $categories;
}

//update or create post and page
function update_or_create_content( $board_id, $post_array, $post_status, $post ) {
	$post_post_array = array();


	global $monday_mutation;

	$sub_id = get_subscription_id( $board_id->boardId );

	//monday board columns
	$board_columns     = get_option( 'monday_' . $board_id->boardId );
	$post_array_no_key = array_values( $post_array );
	foreach ( $board_columns as $key => $board_column ) {
		if ( $key == 0 ) {
			$post_post_array['name']                       = $post_array_no_key[ $key ];
			$post_post_array[ $board_column['column_id'] ] = $post_array_no_key[ $key + 1 ];
		} else {
			$post_post_array[ $board_column['column_id'] ] = $post_array_no_key[ $key + 1 ];
		}

	}

	if ( $post_status == 'create' ) {
		$item_id = $monday_mutation->create_item( $board_id->boardId, $post_post_array, $post->post_title )['data']['create_item']['id'];
		create_monday_post( $sub_id, $item_id, $post->ID );
		//update_post_meta( $post->ID, 'monday_item_id', $item_id );
	} else if ( $post_status == 'update' ) {
		$item_id = get_post_item_id( $post->ID );
		if ( ! empty( $item_id ) ) {
			foreach ( $item_id as $item ) {
				$status = $monday_mutation->change_multiple_column_values( $board_id->boardId, $post_post_array, $item->itemId );
				//error_log( print_r( $status, true ) );
				if ( $status['data']['change_multiple_column_values']['state'] == 'deleted' ) {
					delete_post_item_id( $item->itemId );
					if ( empty( get_item_post_id( $item->itemId ) ) ) {
						create_monday_post_item_function( $board_id->boardId, $post_post_array, $post->post_title, $sub_id, $post->ID );
					}
				}
			}
		} else {
			create_monday_post_item_function( $board_id->boardId, $post_post_array, $post->post_title, $sub_id, $post->ID );
		}

	}
}

//update or create post and page auto sync
function update_or_create_content_auto_synch( $board_id, $post_array, $post ) {
	global $monday_mutation;

	$post_post_array = array();

	$sub_id = get_subscription_id( $board_id->boardId );

	//monday board columns
	$board_columns     = get_option( 'monday_' . $board_id->boardId );
	$post_array_no_key = array_values( $post_array );
	foreach ( $board_columns as $key => $board_column ) {
		if ( $key == 0 ) {
			$post_post_array['name']                       = $post_array_no_key[ $key ];
			$post_post_array[ $board_column['column_id'] ] = $post_array_no_key[ $key + 1 ];
		} else {
			$post_post_array[ $board_column['column_id'] ] = $post_array_no_key[ $key + 1 ];
		}
	}

	$item_id = get_post_item_id( $post->ID );

	if ( ! empty( $item_id ) ) {
		foreach ( $item_id as $item ) {
			if ( get_post_item_board_id( $item->itemId ) == $board_id->boardId ) {
				if ( empty( get_item_post_id( $item->itemId ) ) ) {
					create_monday_post_item_function( $board_id->boardId, $post_post_array, $post->post_title, $sub_id, $post->ID );
				} else {

					$status = $monday_mutation->change_multiple_column_values( $board_id->boardId, $post_post_array, $item->itemId );
					if ( $status['data']['change_multiple_column_values']['state'] == 'deleted' ) {
						delete_post_item_id( $item->itemId );
						if ( empty( get_item_post_id( $item->itemId ) ) ) {
							create_monday_post_item_function( $board_id->boardId, $post_post_array, $post->post_title, $sub_id, $post->ID );
						}
					}
				}
			} else {
				create_monday_post_item_function( $board_id->boardId, $post_post_array, $post->post_title, $sub_id, $post->ID );
			}
		}
	} else {
		create_monday_post_item_function( $board_id->boardId, $post_post_array, $post->post_title, $sub_id, $post->ID );
	}
}

//monday key refresh login
function keys_refresh() {

	$monday_key    = generate_hash( get_current_user_id() . uniqid() );
	$monday_secret = generate_hash( get_current_user_id() . uniqid() );

	//options update
	update_option( 'authorization', array(
		'monday_key'    => $monday_key,
		'monday_secret' => $monday_secret,
	) );


	$monday_key    = get_option( 'authorization' )['monday_key'];
	$monday_secret = get_option( 'authorization' )['monday_secret'];

	echo json_encode( array( 'monday_key' => $monday_key, 'monday_secret' => $monday_secret ) );
	die();
}

add_action( 'wp_ajax_keys_refresh', 'keys_refresh' );
