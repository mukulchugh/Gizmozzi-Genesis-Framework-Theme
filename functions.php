<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'gizmozzi', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'gizmozzi' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Gizmozzi Theme', 'gizmozzi' ) );
define( 'CHILD_THEME_URL', 'http://www.gizmozzi.com/' );
define( 'CHILD_THEME_VERSION', '1.2' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'gizmozzi_enqueue_scripts_styles' );
function gizmozzi_enqueue_scripts_styles() {

	wp_enqueue_script( 'gizmozzi-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700|Raleway:400,500', array(), CHILD_THEME_VERSION );

}

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'default-text-color'     => '000000',
	'header-selector'        => '.site-title a',
	'header-text'            => false,
	'height'                 => 120,
	'width'                  => 320,
) );

//* Add support for custom background
add_theme_support( 'custom-background', array(
	'default-color'         => 'ffffff',
	'default-image'         => get_stylesheet_directory_uri() . '/images/header-banner.png',
	'wp-head-callback'      => 'gizmozzi_background_callback',
) );

//* Add custom background callback 
function gizmozzi_background_callback() { 

	$background = get_background_image();  
	$color = get_background_color();

	if ( ! $background && ! $color )  
		return; 

	echo trim( sprintf( 
		"<style type='text/css'>.custom-background .site-header-banner { background: %s %s %s %s %s; } </style>",
		$background ? 'url('. $background .')' : '',
		$color ? '#'. $color : 'transparent', 
		get_theme_mod( 'background_repeat', 'repeat' ), 
		get_theme_mod( 'background_position_x', 'left' ), 
		get_theme_mod( 'background_attachment', 'scroll' ) 
	) );
} 

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Unregister secondary sidebar 
add_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );

//* Add custom body class to the head
add_filter( 'body_class', 'gizmozzi_custom_body_class' );
function gizmozzi_custom_body_class( $classes ) {

	$classes[] = 'gizmozzi';
	return $classes;

}

//* Hook before header widget area above header
add_action( 'genesis_before_header', 'gizmozzi_before_header' );
function gizmozzi_before_header() {

	genesis_widget_area( 'before-header', array(
		'before' => '<div class="before-header" class="widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

//* Hook site header banner after header
add_action( 'genesis_after_header', 'gizmozzi_site_header_banner' );
function gizmozzi_site_header_banner() {

	if ( ! get_background_image() )
		return;

	echo '<div class="site-header-banner"></div>';

}

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_after_header', 'genesis_do_subnav', 15 );


//* Hook welcome message widget area before content
add_action( 'genesis_before_loop', 'gizmozzi_welcome_message' );
function gizmozzi_welcome_message() {

	if ( ! is_front_page() || get_query_var( 'paged' ) >= 2 )
		return;

	genesis_widget_area( 'welcome-message', array(
		'before' => '<div class="welcome-message" class="widget-area">',
		'after'  => '</div>',
	) );

}

//* Modify the WordPress read more link
add_filter( 'the_content_more_link', 'gizmozzi_read_more' );
function gizmozzi_read_more() {

	return '<a class="more-link" href="' . get_permalink() . '">' . __( 'Continue Reading', 'gizmozzi' ) . '</a>';

}

//* Modify the content limit read more link
add_action( 'genesis_before_loop', 'gizmozzi_more' );
function gizmozzi_more() {

	add_filter( 'get_the_content_more_link', 'gizmozzi_read_more' );

}

add_action( 'genesis_after_loop', 'gizmozzi_remove_more' );
function gizmozzi_remove_more() {

	remove_filter( 'get_the_content_more_link', 'gizmozzi_read_more' );

}

//* Remove entry meta in entry footer
add_action( 'genesis_before_entry', 'gizmozzi_remove_entry_meta' );
function gizmozzi_remove_entry_meta() {
	
	//* Remove if not single post
	if ( ! is_single() ) {
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
	}

}

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'gizmozzi_author_box_gravatar' );
function gizmozzi_author_box_gravatar( $size ) {

	return 180;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'gizmozzi_comments_gravatar' );
function gizmozzi_comments_gravatar( $args ) {

	$args['avatar_size'] = 100;
	return $args;

}

//* Hook split sidebar and bottom sidebar widget areas below primary sidebar
add_action( 'genesis_after_sidebar_widget_area', 'gizmozzi_extra_sidebars' );
function gizmozzi_extra_sidebars() {

	if ( is_active_sidebar( 'split-sidebar-left' ) || is_active_sidebar( 'split-sidebar-right' ) ) {

		echo '<div class="split-sidebars">';

			genesis_widget_area( 'split-sidebar-left', array(
				'before' => '<div class="split-sidebar-left" class="widget-area">',
				'after'  => '</div>',
			) );
			genesis_widget_area( 'split-sidebar-right', array(
				'before' => '<div class="split-sidebar-right" class="widget-area">',
				'after'  => '</div>',
			) );

		echo '</div>';

	}

	genesis_widget_area( 'bottom-sidebar', array(
		'before' => '<div class="bottom-sidebar" class="widget-area">',
		'after'  => '</div>',
	) );

}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'gizmozzi_remove_comment_form_allowed_tags' );
function gizmozzi_remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_notes_after'] = '';
	return $defaults;

}
//* Add the sub footer section
add_action( 'genesis_before_footer', 'gizmozzi_sub_footer', 5 );
function gizmozzi_sub_footer() {

	if ( is_active_sidebar( 'sub-footer' ) ) {
		echo '<div class="sub-footer"><div class="wrap">';
		
		   genesis_widget_area( 'sub-footer', array(
		       'before' => '<div class="sub-footer">',
		       'after'  => '</div>',
		   ) );
	
		
		echo '</div><!-- end .wrap --></div><!-- end .sub-footer -->';	
	}
	
}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'before-header',
	'name'        => __( 'Before Header', 'gizmozzi' ),
	'description' => __( 'This is the before header widget area.', 'gizmozzi' ),
) );
genesis_register_sidebar( array(
	'id'          => 'welcome-message',
	'name'        => __( 'Welcome Message', 'gizmozzi' ),
	'description' => __( 'This is the welcome message widget area.', 'gizmozzi' ),
) );
genesis_register_sidebar( array(
	'id'          => 'split-sidebar-left',
	'name'        => __( 'Split Sidebar Left', 'gizmozzi' ),
	'description' => __( 'This is the left split sidebar widget area.', 'gizmozzi' ),
) );
genesis_register_sidebar( array(
	'id'          => 'split-sidebar-right',
	'name'        => __( 'Split Sidebar Right', 'gizmozzi' ),
	'description' => __( 'This is the right split sidebar widget area.', 'gizmozzi' ),
) );
genesis_register_sidebar( array(
	'id'          => 'bottom-sidebar',
	'name'        => __( 'Bottom Sidebar', 'gizmozzi' ),
	'description' => __( 'This is the bottom sidebar widget area.', 'gizmozzi' ),
) );
genesis_register_sidebar( array(
	'id'          => 'sub-footer',
	'name'        => __( 'Sub Footer', 'gizmozzi' ),
	'description' => __( 'This is the section of the sub footer.', 'gizmozzi' ),
) );