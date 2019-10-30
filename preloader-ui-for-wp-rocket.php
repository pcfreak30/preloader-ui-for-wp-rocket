<?php

/*
 * Plugin Name: WP Rocket Preloader UI
 * Author: Derrick Hammer
 * Version: 0.1.0
 */


/**
 * @param null $container
 *
 * @return \League\Container\ContainerInterface
 */
function preloader_ui_rocket_container( $item, $value = null ) {
	static $container = [];

	if ( null !== $value ) {
		$container[ $item ] = $value;
	}

	return $container[ $item ];
}

function preloader_ui_rocket_admin_menu() {
	require_once __DIR__ . '/classes/class-preloader-ui-rocket-table.php';
	if ( function_exists( 'get_rocket_option' ) && 0 < get_rocket_option( 'manual_preload' ) ) {
		add_management_page( 'Preload Queue', 'WP Rocket Preload Queue', 'manage_options', 'wp-rocket-preload-ui-full', 'preloader_ui_rocket_page_full' );
		add_management_page( 'Post Preload Queue', 'WP Rocket Post Preload Queue', 'manage_options', 'wp-rocket-preload-ui-partial', 'preloader_ui_rocket_page_partial' );
		require_once __DIR__ . '/classes/class-preloader-ui-rocket-table-web.php';
		require_once __DIR__ . '/classes/class-preloader-ui-rocket-table-partial.php';
		preloader_ui_rocket_container( 'web', new Preloader_UI_Rocket_Table_Web() );
		preloader_ui_rocket_container( 'partial', new Preloader_UI_Rocket_Table_Partial() );
	}
}

function preloader_ui_rocket_page_full() {
	/** @var \Preloader_UI_Rocket_Table_Web $table */
	$table = preloader_ui_rocket_container( 'web' );
	$table->prepare_items();
	$table->display();
}

function preloader_ui_rocket_page_partial() {

	/** @var \Preloader_UI_Rocket_Table_Partial $table */
	$table = preloader_ui_rocket_container( 'partial' );
	$table->prepare_items();
	$table->display();
}


add_action( 'rocket_container', 'preloader_ui_rocket_container' );
add_action( 'admin_menu', 'preloader_ui_rocket_admin_menu' );
