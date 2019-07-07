<?php


abstract class Preloader_UI_Rocket_Table extends WP_List_Table {
	protected $identifier;
	protected $action = '';

	public function __construct( $args = array() ) {
		$this->identifier = "rocket_{$this->action}";
		parent::__construct( $args );
	}

	public function prepare_items() {
		global $wpdb;

		$table        = $wpdb->options;
		$column       = 'option_name';
		$key_column   = 'option_id';
		$value_column = 'option_value';

		$key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

		$query = $wpdb->get_results( $wpdb->prepare( "
		SELECT *
		FROM {$table}
		WHERE {$column} LIKE %s
		ORDER BY {$key_column} ASC
	", $key ) );

		$items = [];

		foreach ( $query as $item ) {
			$items = [ maybe_unserialize( $item->$value_column ) ];
		}
		$this->items = call_user_func_array( 'array_merge', $items );

		$this->set_pagination_args( [
			'total_items' => count( $this->items ),
			'per_page'    => count( $this->items ),
		] );
	}

	public function get_columns() {
		return [ 'url'  => 'URL'];
	}

	public function column_url( $item ) {
		return $item;
	}
}
