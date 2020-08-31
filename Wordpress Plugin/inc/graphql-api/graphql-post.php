<?php

class MONDAY_GRAPHQL_MUTATION {

	private $token;

	//setting monday token
	function __construct( $token ) {
		$this->token = $token;
	}

	// request monday
	public function send_monday( $query ) {
		$request = wp_remote_post( 'https://api.monday.com/v2', [
			'headers' => [
				'Content-Type'  => 'application/json',
				'authorization' => $this->token
			],
			'body'    => wp_json_encode( [
				'query' => $query
			] )
		] );

		return json_decode( $request['body'], true );
	}

	/**
	 * Creates new column in a board
	 *
	 * @param integer $boardId this is monday board id
	 * @param string $title give a meaningful name to monday column
	 * @param string $column_type a predefined type for a column e.g. auto_number, checkbox, country, color_picker, creation_log, date, dropdown, email, hour, item_id, last_updated, link, location, long_text, numbers, people, phone, progress, rating, status, team, tags, text, timeline, time_tracking, vote, week, world_clock, integration,
	 *
	 * @return array Array ( [data] => Array ( [create_column] => Array ( [id] => testing9 ) ) [account_id] => 5764226 )
	 */
	public function create_column( $boardId, $title, $column_type ) {
		$query = 'mutation {
create_column (board_id: ' . $boardId . ', title:"' . $title . '", column_type: ' . $column_type . ') {
id
}
}';

		return $this->send_monday( $query );

	}

	/**
	 * Creates an item in a board
	 *
	 * @param integer $boardId this is monday board id
	 * @param array $column_values_array pass an array
	 * @param string $item_name give a name to item
	 *
	 * @return array Array ( [data] => Array ( [create_item] => Array ( [id] => 704866515 ) ) [account_id] => 5764226 )
	 */
	public function create_item( $boardId, $column_values_array, $item_name ) {

		$column_values_array_scaped = wp_json_encode( json_encode( $column_values_array, JSON_NUMERIC_CHECK ) );

		$query = 'mutation {
create_item (board_id: ' . $boardId . ', column_values: ' . $column_values_array_scaped . ',item_name:"' . $item_name . '") {
id
}
}';

		return $this->send_monday( $query );
	}

	/**
	 * Creates a tag for a board
	 *
	 * @param integer $boardId this is monday board id
	 * @param string $tag_name give a name to tag
	 *
	 * @return array Array ( [data] => Array ( [create_or_get_tag] => Array ( [id] => 6589377 ) ) [account_id] => 5764226 )
	 *
	 */
	public function create_tag( $boardId, $tag_name ) {

		$query = 'mutation {
create_or_get_tag (tag_name: "' . $tag_name . '", board_id:' . $boardId . ') {
id
}
}';

		return $this->send_monday( $query );
	}

	/**
	 * Creates an item in a board
	 *
	 * @param integer $boardId this is monday board id
	 * @param array $column_values_array pass an array
	 * @param integer $itemId provide an item id
	 *
	 * @return array Array ( [data] => Array ( [change_multiple_column_values] => Array ( [id] => 704509316 ) ) [account_id] => 5764226 )
	 */
	public function change_multiple_column_values( $boardId, $column_values_array, $itemId ) {

		$column_values_array_scaped = wp_json_encode( json_encode( $column_values_array, JSON_NUMERIC_CHECK ) );

		$query = 'mutation {
change_multiple_column_values (board_id: ' . $boardId . ', column_values: ' . $column_values_array_scaped . ',item_id:' . $itemId . ') {
id
state
}
}';

		return $this->send_monday( $query );
	}

	/**
	 * Creates an update to an item
	 *
	 * @param integer $itemId monday board item id
	 * @param string $itemBody updates to send in an item
	 *
	 * @return array Array ( [data] => Array ( [create_update] => Array ( [id] => 775967330 ) ) [account_id] => 5764226 )
	 *
	 */
	public function create_update( $itemId, $itemBody ) {

		$query = 'mutation {
create_update (item_id: ' . $itemId . ', body: "' . $itemBody . '") {
id
}
}';

		return $this->send_monday( $query );
	}

	/**
	 * Delete an item
	 *
	 * @param integer $itemId monday board item id
	 * @return array Array ( [data] => Array ( [delete_item] => Array ( [id] => 775967330 ) ) [account_id] => 5764226 )
	 */
	public function delete_item( $itemId ) {

		$query = 'mutation {
delete_item (item_id: ' . $itemId . ') {
id
}
}';

		return $this->send_monday( $query );
	}
}

global $token;
$monday_mutation = new MONDAY_GRAPHQL_MUTATION( $token );

