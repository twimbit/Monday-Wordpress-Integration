<?php
/*
Plugin Name: Monday Wordpress Integration
Plugin URI: https://github.com/twimbit/monday-wordpress-integration
Description: Automated WordPress by integrating with Monday.
Version: 0.1
Author: twimbit
Author URI: https://twimbit.com
License: MIT
Text Domain: twimbit
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Now Allowed' );
}

/** Monday token
 * @var string authentication token
 */
$token = '';
if ( ! empty( get_option( 'accessToken' ) ) ) {
	$token = get_option( 'accessToken' );
}

/* monday settings page */
include_once 'view/monday-settings-page.php';

/* monday graphql functions */
include_once 'inc/graphql-api/graphql-get.php';
include_once 'inc/graphql-api/graphql-post.php';

// post synch module
include_once 'utils/post-synch.php';

//database table api
include_once 'utils/db_table-api.php';

// useful functions
include_once 'utils/monday-functions.php';

/* Creating monday tables */
global $monday_db_version;
$monday_db_version = '1.0';
function monday_install() {
	global $wpdb;
	global $monday_db_version;

	$table_name = array(
		$wpdb->prefix . 'monday_action',
		$wpdb->prefix . 'monday_post',
		$wpdb->prefix . 'monday_comments',
		$wpdb->prefix . 'monday_users',
		$wpdb->prefix . 'monday_taxonomy',
		$wpdb->prefix . 'monday_authorize'
	);

	$charset_collate = $wpdb->get_charset_collate();

	$table_sql = array(
		"CREATE TABLE $table_name[0] (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		clientId bigint(20) NOT NULL,
		subscription_id bigint(20) NOT NULL,
		boardId int(11) NOT NULL,
		action text,
		PRIMARY KEY  (id)
	) $charset_collate;",
		"CREATE TABLE $table_name[1] (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		subscription_id bigint(20) NOT NULL,
		itemId int(11) NOT NULL,
		postId int(11) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;",
		"CREATE TABLE $table_name[2] (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		subscription_id bigint(20) NOT NULL,
		mondayId int(11) NOT NULL,
		wpId int(11) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;",
		"CREATE TABLE $table_name[3] (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		subscription_id bigint(20) NOT NULL,
		mondayId int(11) NOT NULL,
		wpId int(11) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;",
		"CREATE TABLE $table_name[4] (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		subscription_id bigint(20) NOT NULL,
		mondayId int(11) NOT NULL,
		termId int(11) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;",
		"CREATE TABLE $table_name[5] (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		clientId bigint(20) NOT NULL,
		scopes text,
		expDate int(11),
		accessToken text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;"
	);

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	foreach ( $table_sql as $sql ) {
		dbDelta( $sql );
	}


	add_option( 'monday_db_version', $monday_db_version );
}

register_activation_hook( __FILE__, 'monday_install' );

add_action( 'rest_api_init', 'register_monday_rest_points' );

// API custom endpoints for WP-REST API
function register_monday_rest_points() {

	register_rest_route(
		'monday', '/subscribe/create_post',
		array(
			'methods'  => array( 'POST' ),
			'callback' => 'subscribe_post',
		)
	);

	register_rest_route(
		'monday', '/authorize',
		array(
			'methods'  => array( 'POST' ),
			'callback' => 'authorize',
		)
	);

	register_rest_route(
		'monday', '/unsubscribe',
		array(
			'methods'  => array( 'POST' ),
			'callback' => 'unsubscribe',
		)
	);

	register_rest_route(
		'monday', '/run/create_post',
		array(
			'methods'  => array( 'POST' ),
			'callback' => 'create_post',
		)
	);

	register_rest_route(
		'monday', '/run/change_status',
		array(
			'methods'  => array( 'POST' ),
			'callback' => 'change_post_status',
		)
	);

	register_rest_route(
		'monday', '/validate',
		array(
			'methods'  => array( 'POST' ),
			'callback' => 'wp_validate',
		)
	);

}

//validate calls
function wp_validate( $req ) {
	$app_key    = $req['APIKey'];
	$app_secret = $req['APISecret'];


	$check = check_auth( $app_key, $app_secret );

	if ( $check ) {
		wp_send_json( array( 'success' => true ) );
	}

	wp_send_json( array( 'success' => false ) );
}

//creating post by monday
function create_post( $req ) {
	$app_key    = $req['APIKey'];
	$app_secret = $req['APISecret'];

	$post_title = $req['title'];
	$user_id    = $req['user_id'];
	$item_id    = $req['itemId'];
	$sub_id     = $req['subscriptionId'];

	error_log( print_r( $req, true ) );

	$check = check_auth( $app_key, $app_secret );

	if ( $check ) {
		$wp_user_id = get_wp_user_id( $user_id );
		if ( empty( $user_id ) ) {
			$wp_user_id = 1;
		}
		$post_id = wp_insert_post( array( 'post_title' => $post_title, 'post_author' => $wp_user_id ) );
		create_monday_post( $sub_id, $item_id, $post_id );

		wp_send_json( array( 'success' => true ) );
	}

	wp_send_json( array( 'success' => false ) );

}

//changing post status by monday
function change_post_status( $req ) {
	$app_key    = $req['APIKey'];
	$app_secret = $req['APISecret'];

	$post_status  = $req['status'];
	$post_item_id = $req['item_id'];

	error_log( print_r( $req, true ) );

	$check = check_auth( $app_key, $app_secret );

	if ( $check ) {
		$post_id = get_item_post_id( $post_item_id );

		if ( $post_status == 1 ) {
			$wp_post_status = 'publish';
		} else {
			$wp_post_status = 'draft';
		}

		if ( empty( $post_id ) ) {
			wp_send_json( array( 'success' => false, 'error' => 'wp post does not exist' ) );
		}

		wp_insert_post( array(
			'post_status' => $wp_post_status,
			'ID'          => $post_id
		) );


		wp_send_json( array( 'success' => true ) );
	}

	wp_send_json( array( 'success' => false ) );
}

//call back unsubscribe route function
function unsubscribe( $req ) {

	//$clientId = $req['ClientId'];
	$action  = $req['action'];
	$sub_id  = $req['subscriptionId'];
	$boardId = $req['boardId'];

	//for request validation
	$apiKey    = $req['APIKey'];
	$apiSecret = $req['APISecret'];

	$result = check_auth( $apiKey, $apiSecret );

	if ( $result ) {
		delete_subscription( $sub_id );
		delete_subscription_post( $sub_id );
		wp_send_json( array( 'success' => true ) );
	}
	wp_send_json( array( 'success' => false ) );
}

//call back authorize route function
function authorize( $req ) {

	// for validating request
	$app_key    = $req['APIKey'];
	$app_secret = $req['APISecret'];

	//data to push in database table
	$accessToken = $req['accessToken'];
	$clientId    = $req['clientId'];
	$scopes      = $req['scopes'];
	$expDate     = $req['expDate'];


	$result = check_auth( $app_key, $app_secret );

	if ( $result ) {
		update_option( 'accessToken', $accessToken );
		$return = monday_add_authorize_data( $clientId, $scopes, $expDate, $accessToken );
		if ( $return['error'] != '' ) {
			wp_send_json( $return );
		}
		wp_send_json( array( 'success' => true ) );
	}

	wp_send_json( array( 'success' => false ) );
}

//call back subscribe route function
function subscribe_post( $req ) {

	//	request data
	$clientId = $req['ClientId'];
	$action   = $req['action'];
	$sub_id   = $req['subscriptionId'];
	$boardId  = $req['boardId'];

	//for request validation
	$apiKey    = $req['APIKey'];
	$apiSecret = $req['APISecret'];

	$result = check_auth( $apiKey, $apiSecret );

	if ( $result ) {

		$result = monday_install_data( $clientId, $action, $sub_id, $boardId );
		if ( $result['error'] != '' ) {
			wp_send_json( $result );
		}
		wp_send_json( array( 'success' => true ) );
	}

	wp_send_json( array( 'success' => false ) );
	//error_log( print_r( $req, true ) );

}

//save monday info for first time when plugin activates
function save_monday_info() {

	$monday_key    = generate_hash( get_current_user_id() . uniqid() );
	$monday_secret = generate_hash( get_current_user_id() . uniqid() );
	$description   = 'this is monday key';

	//options update
	if ( empty( get_option( 'authorization' ) ) ) {
		update_option( 'authorization', array(
			'monday_key'    => $monday_key,
			'monday_secret' => $monday_secret,
			'description'   => $description
		) );
	}
}

register_activation_hook( __FILE__, 'save_monday_info' );

//creating monday tags and syncing with wordpress terms
function monday_create_term( $term_id, $tt_id, $taxonomy ) {
	global $monday_mutation;
	if ( $taxonomy == 'post_tag' ) {
		$tag = get_tag( $term_id );
		foreach ( get_board_ids() as $board_id ) {
			$monday_tag_id = $monday_mutation->create_tag( $board_id->boardId, $tag->name )['data']['create_or_get_tag']['id'];
			update_monday_terms( '', $monday_tag_id, $tag->term_id );

		}
	} else if ( $taxonomy == 'category' ) {
		$category = get_category( $term_id );
		foreach ( get_board_ids() as $board_id ) {
			$monday_category_id = $monday_mutation->create_tag( $board_id->boardId, $category->name )['data']['create_or_get_tag']['id'];
			update_monday_terms( '', $monday_category_id, $category->term_id );

		}
	}
}

add_action( 'create_term', 'monday_create_term', 10, 3 );

// saving post and sending data back to app server
add_action( 'save_post_post', 'update_or_create' );
//saving page
add_action( 'save_post_page', 'update_or_create' );

function update_or_create( $post_id ) {

	$post = get_post( $post_id );

	$post_date   = strtotime( $post->post_date );
	$post_update = strtotime( $post->post_modified );



	//for post create
	if ( $post_date == $post_update && $post->post_status != 'auto-draft' ) {
		create_monday_post_item( $post_id, 'create' );
	} //for post update
	else if ( $post->post_status != 'auto-draft' && $post->post_status != 'trash' ) {
		create_monday_post_item( $post_id, 'update' );
	} else if ( $post->post_status == 'trash' ) {
		global $monday_mutation;
		foreach ( get_post_item_id( $post_id ) as $item_id ) {
			$monday_mutation->delete_item( $item_id->itemId );
		}
	}
}

//when a new user registers
//add_action( 'user_register', 'monday_create_user_item', 10, 1 );
function monday_create_user_item( $userId ) {
	global $monday_mutation;
	foreach ( get_board_ids() as $board_id ) {
		$itemId = $monday_mutation->create_item( $board_id->boardId, array(), get_author_username( $userId ) );
	}
	synch_monday_authors();
}

//monday create comment item
//add_action( 'comment_post', 'monday_create_comment_item', 10, 3 );
function monday_create_comment_item( $commentId, $status, $data ) {
	global $monday_mutation;
	foreach ( get_board_ids() as $board_id ) {
		$itemId = $monday_mutation->create_item( $board_id->boardId, array(), $data['comment_content'] );
		add_comment_meta( $commentId, 'comment_item_id', $itemId['data']['create_item']['id'] );
	}
}
