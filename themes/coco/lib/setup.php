<?php

require_once( 'theme-options.php' );
	
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

function theme_scripts() {
    wp_deregister_script( 'jquery' );

    wp_register_script( 'jquery', get_template_directory_uri() . '/_/js/jquery.js');
    wp_register_script( 'less', get_template_directory_uri() . '/_/js/less.js');
    wp_register_script( 'bootstrap', get_template_directory_uri() . '/_/js/bootstrap.js');
    wp_register_script( 'flexslider', get_template_directory_uri() . '/_/js/flexslider.js');
    wp_register_script( 'elevate-zoom', get_template_directory_uri() . '/_/js/jquery.elevateZoom-3.0.8.min.js');
    wp_register_script( 'functions', get_template_directory_uri() . '/_/js/functions.js');
    wp_register_script( 'filter', get_template_directory_uri() . '/_/js/filter.js');
    wp_register_script( 'cc-ajax-make-reservation', get_template_directory_uri() . '/_/js/ajax/make-reservation.js' );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'less' );    
    wp_enqueue_script( 'bootstrap' );
    wp_enqueue_script( 'flexslider' ); 
    wp_enqueue_script('elevate-zoom');
    wp_enqueue_script( 'functions' );
    wp_enqueue_script( 'filter' );
}
add_action('wp_enqueue_scripts', 'theme_scripts');


function theme_styles() { 

  wp_register_style( 'bootstrap', get_template_directory_uri() . '/_/css/bootstrap/bootstrap.css');   
 
  if ( file_exists( dirname( __FILE__ ) . '/env_prod' ) ) { wp_register_style( 'style-css', get_template_directory_uri() . '/_/css/style.css'); } 
  else { wp_register_style( 'style-less', get_template_directory_uri() . '/_/css/style.less'); }
  
  wp_register_style( 'fonts', get_template_directory_uri() . '/_/fonts/fonts.css');  
   
  wp_enqueue_style( 'bootstrap' );   

  if ( file_exists( dirname( __FILE__ ) . '/env_prod' ) )  {  wp_enqueue_style( 'style-css' ); }
  else { wp_enqueue_style( 'style-less' ); }

  wp_enqueue_style( 'fonts' );  


  
}
add_action('wp_enqueue_scripts', 'theme_styles');

function enqueue_less_styles($tag, $handle) {
    global $wp_styles;
    $match_pattern = '/\.less$/U';
    if ( preg_match( $match_pattern, $wp_styles->registered[$handle]->src ) ) {
        $handle = $wp_styles->registered[$handle]->handle;
        $media = $wp_styles->registered[$handle]->args;
        $href = $wp_styles->registered[$handle]->src . '?ver=' . $wp_styles->registered[$handle]->ver;
        $rel = isset($wp_styles->registered[$handle]->extra['alt']) && $wp_styles->registered[$handle]->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
        $title = isset($wp_styles->registered[$handle]->extra['title']) ? "title='" . esc_attr( $wp_styles->registered[$handle]->extra['title'] ) . "'" : '';

        $tag = "<link rel='stylesheet' id='$handle' $title href='$href' type='text/less' media='$media' />";
    }
    return $tag;
}
add_filter( 'style_loader_tag', 'enqueue_less_styles', 5, 2);


if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 223, 372, true ); 
}
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'square', 500, 500, true );  	
	add_image_size( 'full', 1440, 878, true );  
	add_image_size( 'dress-full', 1440, 2415, true );  
	add_image_size( 'gallery', 446, 748, true );  
	
}

// Add menus to theme
function register_menus() {
  register_nav_menus(
    array(
      'footer-menu' => __( 'Footer Menu' ),
      'top-left-menu' => __( 'Top Left Menu' ),
      'top-right-menu' => __( 'Top Right Menu' )
    )
  );
}
add_action('init', 'register_menus');

// Remove post formats support
add_action('after_setup_theme', 'remove_post_formats', 11);
function remove_post_formats() {
    remove_theme_support('post-formats');
}	
	

function add_shop_manager_roles() {
  $role = get_role('shop_manager');

  if ( $role->capabilities['add_users'] != 1 
   || $role->capabilities['create_users'] != 1
   || $role->capabilities['edit_user'] != 1
   || $role->capabilities['promote_users'] != 1
    ) {

    $role->add_cap('add_users');
    $role->add_cap('edit_user');
    $role->add_cap('create_users');
    $role->add_cap('delete_users');
    $role->add_cap('remove_users');
    $role->add_cap('promote_users');

  }
}
add_action('admin_init','add_shop_manager_roles');
	
function login_css() {
	wp_enqueue_style( 'login_css', get_template_directory_uri() . '/_/css/login.css' );
}
add_action('login_head', 'login_css');

function customAdmin() {
	wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/_/css/admin.css' );}
add_action('admin_head', 'customAdmin');


function remove_menus () {
global $menu, $current_user;
	$restricted = array( __('Comments'),__('Posts'), __('Pages'));
   $whitelist = array('dev','greg','nic');
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted) && !in_array($current_user->user_login, $whitelist)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');


function remove_acf_menu(){
    // provide a list of usernames who can edit custom field definitions here
    $admins = array( 
        'dev','greg','nic'
    );
 
    // get the current user
    $current_user = wp_get_current_user();
 
    // match and remove if needed
    if( !in_array( $current_user->user_login, $admins ) )
    {
        remove_menu_page('edit.php?post_type=acf');
    }
 
}
add_action( 'admin_menu', 'remove_acf_menu' );


show_admin_bar(false);


function cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

	
function get_template_parts( $parts = array() ) {
	foreach( $parts as $part ) {
		get_template_part( $part );
	};
}	

add_filter( 'woocommerce_enqueue_styles', '__return_false' );


/**
 * This routine hot-plugs the wp_new_user_notification function in pluggable.php
 * It sends an email to the site-admin notifying them of the change, but not to anyone else.
 */
if ( !function_exists( 'wp_new_user_notification' ) ) {
  function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
    $user = new WP_User( $user_id );

    $user_login = stripslashes( $user->user_login );
    $user_email = stripslashes( $user->user_email );

    $message = sprintf( __("We received a New User Application on %s:"), get_option('blogname')) . "\r\n\r\n";
    $message .= sprintf( __("Username: %s"), $user_login ) . '\r\n\r\n';
    $message .= sprintf( __("Email: %s"), $user_email ) . "\r\n";

    @wp_mail(
        get_option( 'admin_email' ),
        sprintf(__("[%s] New User Application"), get_option('blogname')),
        $message
     );
  }
}

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
    .bookings { 
      height:350px !important;
    } 
  </style>';
}







