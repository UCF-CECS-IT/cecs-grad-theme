<?php

/**
 * Undocumented function
 *
 * @return void
 */
function register_custom_types() {

	$labels = array(
		'name'				=> _x( 'Research Positions', 'Post Type General Name', 'text_domain'  ),
		'singular_name'		=> _x( 'Research Position','Post Type Singular Name', 'text_domain' ),
		'menu_name'			=> __( 'Research Positions', 'text_domain'  ),
		'name_admin_bar'	=> __( 'Research Position', 'text_domain' ),
		'add_new_item' 		=> __( 'Add Research Position' ),
		'add_new'			=> __( 'Add Research Position' ),
		'edit_item'			=> __( 'Edit Research Position' ),
	);

	$args = array(
		'label'				=> __( 'Research Position', 'text_domain' ),
		'labels'			=> $labels,
		'menu_icon'			=> 'dashicons-clipboard',
		'menu_position'		=> 3,
		'public'			=> true,
		'has_archive'		=> true,
		'supports'			=> array('title', 'thumbnail', 'revisions')
	);

	register_post_type( 'research_position', $args );
}

add_action( 'init', 'register_custom_types', 0 );

/**
 * Undocumented function
 *
 * @param array $columns
 * @return void
 */
function add_acf_columns ( $columns ) {
	return array_merge ( $columns, array (
	  'start_date' => __ ( 'Starts' ),
	  'end_date'   => __ ( 'Ends' )
	) );
}
add_filter ( 'manage_research_position_posts_columns', 'add_acf_columns' );

/*
 * Add columns to Hosting CPT
 */
function research_position_custom_column ( $column, $post_id ) {
	switch ( $column ) {
	  case 'start_date':
		echo get_post_meta ( $post_id, 'hosting_start_date', true );
		break;
	  case 'end_date':
		echo get_post_meta ( $post_id, 'hosting_end_date', true );
		break;
	}
}
add_action ( 'manage_research_position_posts_custom_column', 'research_position_custom_column', 10, 2 );

/*
 * Add Sortable columns
 */
function my_column_register_sortable( $columns ) {
	$columns['start_date'] = 'start_date';
	$columns['end_date'] = 'start_date';
	return $columns;
}
add_filter('manage_edit-hosting_sortable_columns', 'my_column_register_sortable' );
