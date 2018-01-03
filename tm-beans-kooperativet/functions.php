<?php

// Include Beans. Do not remove the line below.
require_once( get_template_directory() . '/lib/init.php' );

/*
 * Remove this action and callback function if you do not whish to use LESS to style your site or overwrite UIkit variables.
 * If you are using LESS, make sure to enable development mode via the Admin->Appearance->Settings option. LESS will then be processed on the fly.
 */
/*add_action( 'beans_uikit_enqueue_scripts', 'beans_child_enqueue_uikit_assets' );

function beans_child_enqueue_uikit_assets() {

	beans_compiler_add_fragment( 'uikit', get_stylesheet_directory_uri() . '/style.less', 'less' );

}*/

// Remove this action and callback function if you are not adding CSS in the style.css file.
add_action( 'wp_enqueue_scripts', 'beans_child_enqueue_assets' );
function beans_child_enqueue_assets() {	
	wp_enqueue_style( 'child-styles', get_stylesheet_directory_uri() . '/style.css', array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
}





/*------ CUSTOM CODE for Beans Kooperativet theme -----*/

//Include WooCommerce code snippet file.
require_once( get_stylesheet_directory() . '/inc/woocommerce.php' );

// Add WooCommerce support.
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// Load Font Awesome
add_action( 'wp_enqueue_scripts', 'enqueue_font_awesome' );
function enqueue_font_awesome() {
 wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
}



// Remove site description
beans_remove_action( 'beans_site_title_tag' );

/* Remove the page title from all pages  
// https://community.getbeans.io/discussion/remov-title-and-change-archive-h1/ ----
add_action( 'wp', 'setup_document_remove_pagetitle' );
function setup_document_remove_pagetitle() {
    if ( false === is_single() and !is_home()  ) { 
        beans_remove_action( 'beans_post_title' );
    }
}
*/

// Disable comments on posts.
// http://www.getbeans.io/code-snippets/disable-comments-for-specific-post-types/
add_action( 'init', 'remove_post_comments' );
function remove_post_comments() {
  remove_post_type_support( 'post', 'comments' );
}


// Remove breadcrumbs.
beans_remove_action( 'beans_breadcrumb' );


// Removes featured image from all pages (posts and pages) except the blog page.
add_action( 'wp', 'beans_child_setup_document' );
function beans_child_setup_document() {
   if ( is_single() or is_page() ) {					
        beans_remove_action( 'beans_post_image' );
    }
}

/* --- Resize featured images seen on the blog page: 
https://community.getbeans.io/discussion/default-featured-image-size/ --- */
add_filter( 'beans_edit_post_image_args', 'example_post_image_edit_args' );
function example_post_image_edit_args( $args ) {
    return array_merge( $args, array(
        'resize' => array( 300, true ),
    ) );
} 


/* ------- Force LEFT SIDEBAR layout and remove options to change it. -----*/
add_filter( 'beans_layout', 'example_force_layout' );
function example_force_layout() {
    return 'sp_c';
}

// https://community.getbeans.io/discussion/hide-post-options-in-dashboard/
// Remove layout options from pages and posts.
beans_remove_action(  'beans_do_register_post_meta' ); // will remove the options from pages
beans_remove_action(  'beans_do_register_term_meta' ); // will remove the options from posts

// Remove option from the customizer.
/* http://kb.wpbeaverbuilder.com/article/357-remove-a-customizer-panel and https://wordpress.stackexchange.com/questions/58932/how-do-i-remove-a-pre-existing-customizer-setting and https://css-tricks.com/ */
function default_layout_remove() {     
global $wp_customize;
    $wp_customize->remove_section( 'beans_layout' );  // Section name of the default layout.
} 

add_action( 'customize_register', 'default_layout_remove', 11 );

// END



// Blog page Excerpt 
// https://community.getbeans.io/discussion/how-to-show-post-excerpts/

add_filter( 'the_content', 'beans_child_modify_post_content' );
function beans_child_modify_post_content( $content ) {
 
  // Stop here if we are on a single view.
 if ( is_singular() )
 return $content;
 
 // Return the excerpt() if it exists other truncate.
 if ( has_excerpt() )
 $content = '<p>' . get_the_excerpt() . '</p>';
 else
 $content = '<p>' . wp_trim_words( get_the_content(), 40, '...' ) . '</p>';
 
 // Return content and readmore.
 return $content . '<p>' . beans_post_more_link() . '</p>';
}



/* Modify Continue reading text link to Read more.
https://community.getbeans.io/discussion/modify-wordpress-language/ */
add_filter( 'beans_post_more_link_text_output', 'example_modify_read_more' );
function example_modify_read_more() {
 return 'Les videre';
}


// An alternative example code to the above code to remove or add fields.
add_filter( 'beans_post_meta_items', 'beans_child_remove_post_meta_items' );
function beans_child_remove_post_meta_items( $items ) {

    // Remove
    unset( $items['author'] );
    unset( $items['comments'] );

   // Add 
   $items['categories'] = 20;
   $items['tags'] = 20;
    
   return $items;
}

// Remove the post meta categories below the content.
beans_remove_action( 'beans_post_meta_categories' );

// Remove the post meta tags below the content.
beans_remove_action( 'beans_post_meta_tags' );

// Removing prefixes for date, author, categories and tags.
beans_remove_output( 'beans_post_meta_date_prefix' );
beans_remove_output( 'beans_post_meta_author_prefix' );
beans_remove_output( 'beans_post_meta_categories_prefix' );
beans_remove_output( 'beans_post_meta_tags_prefix' );



// Display posts in a responsive grid.
/* http://www.getbeans.io/code-snippets/display-posts-in-a-responsive-grid/ and https://getuikit.com/v2/docs/grid.html */
add_action( 'wp', 'the_posts_grid' );

function the_posts_grid() {
	// Only apply to non singular view.
	if ( !is_singular() ) {

		// Add grid.
		beans_wrap_inner_markup( 'beans_content', 'beans_child_posts_grid', 'div', array(
			'class' => 'uk-grid uk-grid-match',
			'data-uk-grid-margin' => ''
		) );
		beans_wrap_markup( 'beans_post', 'beans_child_post_grid_column', 'div', array(
			'class' => 'uk-width-large-1-2 uk-width-medium-1-2'
		) );

		// Move the posts pagination after the new grid markup.
		beans_modify_action_hook( 'beans_posts_pagination', 'beans_child_posts_grid_after_markup' );		
	}
}


// Add a archive title CSS class so that I can style it.
beans_add_attribute( 'beans_archive_title', 'class', 'archive-title' );



/* -------- BLOG CODE END ------ */


// SHOW category description. https://codex.wordpress.org/Function_Reference/category_description 
// and http://www.wpbeginner.com/wp-tutorials/how-to-display-category-descriptions-in-wordpress/
echo category_description();



//Removes uk-block from main area.
beans_remove_attribute( 'beans_post', 'class', 'uk-panel-box' );




/* ------------- Adding additional menus ---------------*/


// Move menu below header.
beans_modify_action_hook( 'beans_primary_menu', 'beans_header_after_markup' );

// Remove float and add container.
beans_remove_attribute( 'beans_primary_menu', 'class', 'uk-float-right' );
//beans_wrap_markup( 'beans_primary_menu', 'data-mark_primary_menu', 'div', array( 'class' => 'uk-container uk-container-center' ) );


beans_add_attribute( 'beans_primary_menu', 'class', 'tm-primary-menu-bar' );



// Register new Menus
function register_multiple_menus() {
  register_nav_menus(
    array(
      'footer-menu' => __( 'Footer Menu' ),
    )
 );
}
add_action( 'init', 'register_multiple_menus' );


// Add the new footer menu in the correct location.
beans_add_smart_action( 'beans_fixed_wrap[_footer]_prepend_markup', 'fast_monkey_footer_menu' );

// Add the footer menu
function fast_monkey_footer_menu() {
		 if ( has_nav_menu( 'footer-menu' ) ) { // only show if the  location precontent-menu has a menu assigned
	wp_nav_menu( array( 'theme_location' => 'footer-menu',
						'container' => 'nav',
	 					'container_class' => 'tm-footer-menu uk-margin-bottom', // Added uk-margin-bottom from kkthemes code.
						'menu_class' => 'uk-navbar-nav',
											
						'depth' => 1, // For drop down menus change to 0
					));
				}	
}



// Conditional menu for logged in users.
/* http://genesisdeveloper.me/different-primary-menu-on-pages-using-altitude-pro-theme/ and http://victorfont.com/conditional-secondary-menus-genesis-themes/ */
function gd_nav_menu_args( $args ){
 if( ( 'primary' == $args['theme_location'] ) && is_user_logged_in() ) {
 $args['menu'] = 'Topp meny - Logged-in'; // Add your menu name here. My case it is "Logged-in"
 }
 return $args;
}
add_filter( 'wp_nav_menu_args', 'gd_nav_menu_args' );





/*----- COPYRIGHT INFO BOTTOM ----*/

// Overwrite the footer content.
beans_modify_action_callback( 'beans_footer_content', 'beans_child_footer_content' );

//Removes uk-container from footer area.
beans_remove_attribute( 'beans_fixed_wrap[_footer]', 'class', 'uk-container' );

// COPYRIGHT area
function beans_child_footer_content() {
?>
<div class="tm-sub-footer uk-text-center">
 <p>© <?php echo date('Y'); ?>
 Laget av 
<a href="http://www.easywebdesigntutorials.com" target="_blank" title="Kooperativet WordPress tema"> Paal Joachim.</a> 
Med <a href="http://www.getbeans.io/" title="Beans Framework for WordPress" target="_blank">Beans WordPress Framework</a>. 
 - <a href="<?php echo admin_url();?>" title="Gå til baksiden i WordPress" />Login</a></p>
 </div>
 <?php
}

beans_add_attribute( 'beans_fixed_wrap[_footer]', 'class', 'copyright-footer-full' );





/* --------- Widget areas ----- */


/*---- Hero Widget ----*/
 add_action( 'widgets_init', 'beans_child_widgets_init' );
 function beans_child_widgets_init() {
 beans_register_widget_area( array(
 'name' => 'Hero',
 'id' => 'hero',
 'description' => 'Widgets in this area will be shown in the hero section as a grid.',
 'beans_type' => 'grid'
 ) );
 }

// Display hero widget area in the front end 
add_action( 'beans_main_grid_before_markup', 'beans_child_hero_widget_area' );
function beans_child_hero_widget_area() {

// Stop here if no widget
 if( !beans_is_active_widget_area( 'hero' ) )
 return;
 ?>
 	<div class="widget-hero uk-block">
 	<div class="uk-container uk-container-center">
 	<?php echo beans_widget_area( 'hero' ); ?>
 	</div>
 	</div>
 <?php
}



// Register a widget area below blogroll.
add_action( 'widgets_init', 'flipster_below_blogroll_widget_area' );

function flipster_below_blogroll_widget_area() {

    beans_register_widget_area( array(
        'name' => 'Below Blogroll',
        'id' => 'below-blogroll',
        'beans_type' => 'stack'
    ) );
}

beans_add_smart_action('beans_posts_pagination_before_markup', 'flipster_below_blogroll_widget_output');
//Display the Widget area

function flipster_below_blogroll_widget_output() {
	?>
	<div class="tm-below-blogroll-widget-area">
			<?php echo beans_widget_area( 'below-blogroll' ); ?>
	</div>
	<?php
}

// Register a widget area below post content.
add_action( 'widgets_init', 'flipster_below_post_widget_area' );

function flipster_below_post_widget_area() {

    beans_register_widget_area( array(
        'name' => 'Below Post',
        'id' => 'below-post',
        'beans_type' => 'stack'
    ) );
}

//Display the Widget area
function flipster_widget_after_post_content( $content ) {
	$output =  $content;
	$output .=  '<div class="tm-below-post-widget-area">';
	$output .=   beans_widget_area( 'below-post' );
	$output .=  '</div>';
	return $output;
}



// Register a footer widget area.
add_action( 'widgets_init', 'example_widget_area' );
function example_widget_area() {
    beans_register_widget_area( array(
        'name' => 'Footer',
        'id' => 'footer',
        'beans_type' => 'grid'
    ) );
}

// Display the footer widget area in the front end.
add_action( 'beans_footer_before_markup', 'example_footer_widget_area' );
function example_footer_widget_area() {
	?>
	<div class="footer-widget uk-block">
		<div class="uk-container uk-container-center">
			<?php echo beans_widget_area( 'footer' ); ?>
		</div>
	</div>
	<?php

}


// https://community.getbeans.io/discussion/order-of-sidebar-when-responsive/
add_action( 'wp', 'example_modify_layout' );
/**
 * Modify mobile layout.
 */
function example_modify_layout() {

    // Display left sidebar before content on mobile.
    if ( 'sp_c' === beans_get_layout() ) {
        // Move the sidebar HTML before primary content.
        beans_modify_action_hook( 'beans_sidebar_primary_template', 'beans_primary_before_markup' );

        // Remove push pull classes.
        beans_remove_attribute( 'beans_primary', 'class', 'uk-push-1-4' );
        beans_remove_attribute( 'beans_sidebar_primary', 'class', 'uk-pull-3-4' );
    }

}


/* ---------- Media Queries ------*/
//beans_add_attribute( 'beans_widget_content[_sidebar_primary][_media_image][_media_image-5]', 'class', 'tm-widget-image' );

//Removes uk-container from footer area.
//beans_remove_attribute( 'beans_primary', 'class', ' uk-width-medium-3-4' );






/*----------- Other custom modifications ------------*/


/* Back To Top button */

add_action( 'wp_footer', 'back_to_top' );
 function back_to_top() {
 echo '<a id="totop" href="#" data-btn-alt="Topp">⬆︎</a>';
 }

add_action( 'wp_head', 'back_to_top_style' );
 function back_to_top_style() {
 echo '<style type="text/css">
 #totop {
 position: fixed;
 right: 30px;
 bottom: 30px;
 display: none;
 outline: none;
 text-decoration: none;
 font-size: 26px;
 background: rgba(42, 64, 67, 0.2); 
 padding: 10px 20px 5px 20px; 
 border-radius: 5px;
 border: 1px solid #ccc;
 box-shadow: 0 0 1px #000;
 color: #fff;
 z-index: 100;
 }
 
 #totop:hover {
 background: rgba(42, 64, 67, 1);
 }
 
 #totop:hover:after{
 content: attr(data-btn-alt);
 font-size: 16px;
 color: #fff;
 padding-left: 5px;
 }
 </style>';
 
 }

add_action( 'wp_footer', 'back_to_top_script' );
 function back_to_top_script() {
 echo '<script type="text/javascript">
 jQuery(document).ready(function($){
 $(window).scroll(function () {
 if ( $(this).scrollTop() > 1500 ) 
 $("#totop").fadeIn();
 else
 $("#totop").fadeOut();
 });

$("#totop").click(function () {
 $("body,html").animate({ scrollTop: 0 }, 1400 );
 return false;
 });
 });
 </script>';
 }




/* Bigger embed size http://cantonbecker.com/work/musings/2011/how-to-change-automatic-wordpress-youtube-embed-size-width/ */
add_filter( 'embed_defaults', 'bigger_embed_size' );
function bigger_embed_size()
{ 
 return array( 'width' => 910, 'height' => 590 );
}


// Add support for editor stylesheet - using twenty Sixteens editor stylesheet.
add_editor_style( 'assets/css/editor-style.css' );


/* --------- Bottom of backend Admin screen -  Custom admin footer credits https://github.com/gregreindel/greg_html5_starter -----*/

add_filter( 'admin_footer_text', create_function( '$a', 'return \'<span id="footer-thankyou">Site managed by <a href="http://www.easywebdesigntutorials.com" target="_blank">Paal Joachim Romdahl </a><span> | Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>\';' ) );



// Modify the WordPress login screen.
//
// https://github.com/JiveDig/baseline/blob/master/functions.php
/**
 * Change login logo
 * Max image width should be 320px
 * @link http://andrew.hedges.name/experiments/aspect_ratio/
 */
add_action('login_head',  'tsm_custom_dashboard_logo');
function tsm_custom_dashboard_logo() {
	echo '<style  type="text/css">
		body.login {
		   background-image:url(' . get_stylesheet_directory_uri() . '/images/Kooperativet-ukens-pose.jpg)  !important;
		}
		
		#login {
		  margin: 0 auto;
		  padding: 25px;
		}
		
		.login h1 a {
			background-image:url(' . get_stylesheet_directory_uri() . '/images/Kooperativet-logo-liten.jpg)  !important;
			background-size: 110px auto !important;
			width: 100% !important;
			height: 150px !important;
			
		}
		
		#login form { 
		 box-shadow:0 2px 3px #444 !important;
		 border-radius: 7px;
		 background: #eeecec;
		 font-size: 18px;
		}
		
		.login #nav {
		 font-size: 20px;	 
		 text-decoration: underline;
		}
		
		.login #backtoblog, .login #nav {
		    font-size: 20px;
		    text-decoration: underline;
		}
		
	</style>';
}

// Change login link
add_filter('login_headerurl','tsm_loginpage_custom_link');
function tsm_loginpage_custom_link() {
	return get_site_url();
}


// Add UIkit social media icons to the right of the Primary menu. */
/* https://community.getbeans.io/discussion/social-media-menu-alignment/ and https://getuikit.com/v2/docs/icon.html 
function myprefix_add_menu_items() {
    ?>
    <div class="uk-float-right uk-margin-left">
        <a href="#" class="uk-icon-twitter-square uk-icon-small uk-icon uk-margin-small-left"></a>
        <a href="https://www.instagram.com/oslokooperativ/" target="_blank" class="uk-icon-instagram uk-icon-small uk-icon uk-margin-small-left"></a>
        <a href="https://www.facebook.com/OsloKooperativ" target="_blank" class="uk-icon-facebook-square uk-icon-small uk-icon uk-margin-small-left"></a>
    </div>
    <?php
}

add_action( 'beans_primary_menu_append_markup', 'myprefix_add_menu_items' );
*/
