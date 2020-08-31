<?php

/*<================== Database get operations =======================>*/

//get board ids
function get_board_ids( $action = '' ) {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_action';
	if ( empty( $action ) ) {

		$results = $wpdb->get_results( "SELECT boardId FROM $wp_table_name" );
	} else {

		$results = $wpdb->get_results( "SELECT boardId FROM $wp_table_name WHERE action='$action'" );
	}


	if ( empty( $results ) ) {
		return '';
	}

	return $results;
}

//get subscription id
function get_subscription_id( $boardId ) {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_action';
	$results       = $wpdb->get_results( "SELECT subscription_id FROM $wp_table_name WHERE boardId=$boardId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->subscription_id;
}

//get subscription ids
function get_subscription_ids() {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_action';

	$results = $wpdb->get_results( "SELECT subscription_id FROM $wp_table_name" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results;
}

//get post item id from post id
function get_post_item_id( $postId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';

	$results = $wpdb->get_results( "SELECT itemId FROM $wp_table_name WHERE postId=$postId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results;
}

//get board post item ids
function get_board_post_item_ids( $boardId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';

	$results = $wpdb->get_results( "SELECT itemId FROM $wp_table_name WHERE boardId=$boardId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results;
}

//get monday user id
function get_monday_user_id( $wpId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_users';

	$results = $wpdb->get_results( "SELECT mondayId FROM $wp_table_name WHERE wpId=$wpId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->mondayId;
}

//get wp user id from monday user id
function get_wp_user_id( $mondayId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_users';

	$results = $wpdb->get_results( "SELECT wpId FROM $wp_table_name WHERE mondayId=$mondayId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->wpId;
}

//get monday tag id
function get_monday_term_id( $termId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_taxonomy';

	$results = $wpdb->get_results( "SELECT mondayId FROM $wp_table_name WHERE termId=$termId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->mondayId;
}

//get post subscription id from post id
function get_post_sub_id( $postId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';
	$results       = $wpdb->get_results( "SELECT subscription_id FROM $wp_table_name WHERE postId=$postId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->subscription_id;
}

//get post item id from post id
function get_item_post_id( $itemId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';

	$results = $wpdb->get_results( "SELECT postId FROM $wp_table_name WHERE itemId=$itemId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->postId;
}

//get board id from post item id
function get_post_item_board_id( $itemId ) {
	global $wpdb;
	$wp_table_name_post   = $wpdb->prefix . 'monday_post';
	$wp_table_name_action = $wpdb->prefix . 'monday_action';

	$subscription_id = $wpdb->get_results( "SELECT subscription_id FROM $wp_table_name_post WHERE itemId=$itemId" )[0]->subscription_id;
	$results         = $wpdb->get_results( "SELECT boardId FROM $wp_table_name_action WHERE subscription_id=$subscription_id" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->boardId;
}

//get subscription authorization token
function get_subs_auth_token( $boardId ) {
	global $wpdb;
	$wp_table_name_action = $wpdb->prefix . 'monday_action';
	$wp_table_name_auth   = $wp_table_name = $wpdb->prefix . 'monday_authorize';
	$clientId             = $wpdb->get_results( "SELECT clientId FROM $wp_table_name_action WHERE boardId=$boardId" )[0]->clientId;
	$results              = $wpdb->get_results( "SELECT accessToken FROM $wp_table_name_auth WHERE clientId=$clientId" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->accessToken;
}

//get actions from monday action table
function get_sub_actions( $action ) {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_action';

	$results = $wpdb->get_var( "SELECT action FROM $wp_table_name WHERE action='$action'" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results;
}

//get board action
function get_board_action( $boardId, $action ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_action';
	$results       = $wpdb->get_results( "SELECT action FROM $wp_table_name WHERE boardId=$boardId" );

	if ( empty( $results ) ) {
		return '';
	}

	foreach ( $results as $result ) {
		if ( $result->action == $action ) {
			return $action;
		}
	}

	return '';
}

//check post item Id exist
function get_check_item_id( $itemId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';
	$results       = $wpdb->get_results( "SELECT itemId FROM $wp_table_name WHERE itemId='$itemId'" );

	if ( empty( $results ) ) {
		return '';
	}

	return $results[0]->itemId;
}

/*<================== Database write operations =======================>*/

//insert data in monday table
function monday_install_data( $clientId, $action, $sub_id, $boardId ) {

	global $wpdb;

	$table_name = $wpdb->prefix . 'monday_action';

	$wpdb->insert(
		$table_name,
		array(
			'time'            => current_time( 'mysql' ),
			'clientId'        => $clientId,
			'subscription_id' => $sub_id,
			'boardId'         => $boardId,
			'action'          => $action
		)
	);

	if ( $wpdb->last_error != '' ) {
		return array( 'error' => $wpdb->last_error );
	}

	return array( 'error' => '' );
}

//create monday post item id
function create_monday_post( $subId, $itemId, $postId, $boardId ) {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';
	$wpdb->insert( $wp_table_name, array(
		'time'            => current_time( 'mysql' ),
		"subscription_id" => $subId,
		"itemId"          => $itemId,
		"postId"          => $postId,
		"boardId"         => $boardId
	) );
}

//update monday post item id
function update_monday_post( $itemId, $postId ) {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';
	$wpdb->update( $wp_table_name,
		array( "itemId" => $itemId, ), array( 'postId' => $postId ), array( "%d" ), array( "%d" ) );
}

//update monday users
function create_monday_users( $subId, $mondayId, $wpId ) {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_users';
	$wpdb->insert( $wp_table_name, array(
		'time'            => current_time( 'mysql' ),
		"subscription_id" => $subId,
		"mondayId"        => $mondayId,
		"wpId"            => $wpId
	) );
}

//update monday users
function update_monday_users( $subId, $mondayId, $wpId ) {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_users';
	$wpdb->update( $wp_table_name,
		array( "mondayId" => $mondayId ), array( 'wpId' => $wpId ), array( "%d" ), array( "%d" ) );
}

//update monday categories and tags
function update_monday_terms( $subId, $mondayId, $termId ) {

	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_taxonomy';
	$wpdb->insert( $wp_table_name, array(
		'time'            => current_time( 'mysql' ),
		"subscription_id" => $subId,
		"mondayId"        => $mondayId,
		"termId"          => $termId
	) );
}

//delete subscription from monday action table
function delete_subscription( $sub_id ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_action';
	$wpdb->delete( $wp_table_name, array( 'subscription_id' => $sub_id ) );
}

//delete post subscriptions
function delete_subscription_post( $sub_id ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';
	$wpdb->delete( $wp_table_name, array( 'subscription_id' => $sub_id ) );
}

//delete post item id
function delete_post_item_id( $itemId ) {
	global $wpdb;
	$wp_table_name = $wpdb->prefix . 'monday_post';
	$wpdb->delete( $wp_table_name, array( 'itemId' => $itemId ) );
}

//insert authorize request in database
function monday_add_authorize_data( $clientId, $scopes, $expDate, $accessToken ) {

	global $wpdb;

	$table_name = $wpdb->prefix . 'monday_authorize';

	$wpdb->insert(
		$table_name,
		array(
			'time'        => current_time( 'mysql' ),
			'clientId'    => $clientId,
			'scopes'      => $scopes,
			'expDate'     => $expDate,
			'accessToken' => $accessToken
		)
	);

	if ( $wpdb->last_error != '' ) {
		return array( 'error' => $wpdb->last_error );
	}

	return array( 'error' => '' );
}
