<?php

//* gizmozzi Theme Setting Defaults
add_filter( 'genesis_theme_settings_defaults', 'gizmozzi_theme_defaults' );
function gizmozzi_theme_defaults( $defaults ) {

	$defaults['blog_cat_num']              = 4;
	$defaults['content_archive']           = 'full';
	$defaults['content_archive_limit']     = 0;
	$defaults['content_archive_thumbnail'] = 0;
	$defaults['image_alignment']           = 'alignleft';
	$defaults['posts_nav']                 = 'prev-next';
	$defaults['site_layout']               = 'content-sidebar';

	return $defaults;

}

//* gizmozzi Theme Setup
add_action( 'after_switch_theme', 'gizmozzi_theme_setting_defaults' );
function gizmozzi_theme_setting_defaults() {

	if( function_exists( 'genesis_update_settings' ) ) {

		genesis_update_settings( array(
			'blog_cat_num'              => 4,	
			'content_archive'           => 'full',
			'content_archive_limit'     => 0,
			'content_archive_thumbnail' => 0,
			'image_alignment'           => 'alignleft',
			'posts_nav'                 => 'prev-next',
			'site_layout'               => 'content-sidebar',
		) );
		
	} else {
		
		_genesis_update_settings( array(
			'blog_cat_num'              => 4,	
			'content_archive'           => 'full',
			'content_archive_limit'     => 0,
			'content_archive_thumbnail' => 0,
			'image_alignment'           => 'alignleft',
			'posts_nav'                 => 'prev-next',
			'site_layout'               => 'content-sidebar',
		) );
		
	}

	update_option( 'posts_per_page', 4 );

}

//* Simple Social Icon Defaults
add_filter( 'simple_social_default_styles', 'gizmozzi_social_default_styles' );
function gizmozzi_social_default_styles( $defaults ) {

	$args = array(
		'alignment'              => 'aligncenter',
		'background_color'       => '#eeeeee',
		'background_color_hover' => '#e5554e',
		'border_radius'          => 3,
		'icon_color'             => '#333333',
		'icon_color_hover'       => '#ffffff',
		'size'                   => 36,
	);
		
	$args = wp_parse_args( $args, $defaults );
	
	return $args;
	
}