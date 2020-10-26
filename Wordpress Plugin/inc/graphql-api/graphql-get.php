<?php

class MONDAY_GRAPHQL_QUERY {

	private $token;

	//setting monday token
	function __construct( $token ) {
		$this->token = $token;
	}

	// request monday
	public function request_monday( $query ) {
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

	// get Boards
	public function get_board( $boardId ) {

		$query = 'query MyQuery {
  boards(ids: ' . $boardId . ') {
    name
  }
}';

		$result = $this->request_monday( $query );

		return $result['data']['boards'];
	}

	//get board columns meta
	public function get_board_columns( $boardId ) {

		$query  = 'query MyQuery {
  boards(ids: ' . $boardId . ') {
     columns {
      title
      id
      type
    }
  }
}
';
		$result = $this->request_monday( $query );

		return $result['data']['boards'][0]['columns'];
	}

	//get monday users
	public function get_monday_user() {
		$query  = 'query MyQuery {
  users {
    email
    id
  }
}';
		$result = $this->request_monday( $query );

		return $result['data']['users'];
	}

	//get monday item
	public function get_monday_item( $itemId ) {
		$query  = 'query MyQuery {
  items(ids: ' . $itemId . ') {
    name
    creator {
      id
    }
    board {
      id
    }
  }
}';
		$result = $this->request_monday( $query );

		return $result['data']['items'][0];
	}

	//get monday item specific column row value
	public function get_monday_item_column_value( $itemId, $columnId ) {
		$query  = 'query MyQuery {
  items(ids:' . $itemId . ') {
    column_values(ids: "' . $columnId . '") {
      value
    }
  }
}';
		$result = $this->request_monday( $query )['data']['items'][0]['column_values'][0]['value'];;

		return json_decode( $result )->personsAndTeams[0]->id;
	}

}

global $token;
$monday_query = new MONDAY_GRAPHQL_QUERY( $token );




