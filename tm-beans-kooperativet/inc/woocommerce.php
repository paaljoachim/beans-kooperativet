<?php

/* ------------------ WooCommerce -------------------*/   
  
    
 /* --------- WooCommerce - Single Product page -----------*/
 
 // Removes price. Adds price below short description.
 remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
 add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 20);             
 
 // Remove product title. Adds product title above thumbnail.
 remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
 add_action('woocommerce_before_single_product', 'woocommerce_template_single_title', 10); 
 
 // Shop 
 // Remove category title. Add category title above thumbnail.  ????
 remove_action('woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10);
 add_action('woocommerce_before_subcategory', 'woocommerce_template_loop_category_title', 10); 
 
 // Hide sku: https://www.skyverge.com/blog/how-to-hide-sku-woocommerce-product-pages/ 
// add_filter( 'wc_product_sku_enabled', '__return_false' );
 
 // Hide meta (skue, category and tags): https://stackoverflow.com/questions/38404187/remove-tags-from-product-page-in-woocommerce
 add_action( 'after_setup_theme', 'my_after_setup_theme' );
 function my_after_setup_theme() {
     remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
 }
 
 
 // Attribute/Variation drop down. Change drop down phrase.
 // https://stackoverflow.com/questions/32170575/how-do-i-change-button-text-from-choose-an-option-in-woocommerce
  function my_dropdown_variation_attribute_options_html($html, $args){
      $html = str_replace('Velg en', 'Velg', $html);
      return $html;
  }
  add_filter('woocommerce_dropdown_variation_attribute_options_html', 'my_dropdown_variation_attribute_options_html', 10, 2);
  
  // END - Virker når det er på Norsk...
 
 
 /*
 // Show Units sold text. Single product.
 // https://businessbloomer.com/woocommerce-show-number-products-sold-product-page/
 add_action( 'woocommerce_single_product_summary', 'bbloomer_product_sold_count', 11 );
  
 function bbloomer_product_sold_count() {
 global $product;
 $units_sold = get_post_meta( $product->get_id(), 'total_sales', true );
 if ( $units_sold ) echo '<p>' . sprintf( __( 'Units Sold: %s', 'woocommerce' ), $units_sold ) . '</p>';
 }
 */
 // END
 
 /*
 // Extra text below the price. Single product
 // https://businessbloomer.com/woocommerce-add-text-add-cart-single-product-page/
 add_action( 'woocommerce_single_product_summary', 'return_policy', 20 );
  
 function return_policy() {
     echo '<p id="rtrn">Produkt hentes på hentedagen mellom 16.00 - 19.00.</p>';
 }
 */
 // END
  
/*
// Add to cart message. Showing product title and the buttons Show Cart and Go to checkout.
// https://stackoverflow.com/questions/25880460/woocommerce-how-to-edit-the-added-to-cart-message also see https://businessbloomer.com/woocommerce-remove-product-successfully-added-cart-message/ 
add_filter ( 'wc_add_to_cart_message', 'wc_add_to_cart_message_filter', 10, 2 );
function wc_add_to_cart_message_filter($message, $product_id = null) {
$titles[] = get_the_title( $product_id );

$titles = array_filter( $titles );
$added_text = sprintf( _n( '%s has been added to your cart.', '%s have been added to your cart.', sizeof( $titles ), 'woocommerce' ), wc_format_list_of_items( $titles ) );

$message = sprintf( '%s <a href="%s" class="button">%s</a>&nbsp;<a href="%s" class="button">%s</a>',
                esc_html( $added_text ),
                esc_url( wc_get_page_permalink( 'checkout' ) ),
                esc_html__( 'Checkout', 'woocommerce' ),
                esc_url( wc_get_page_permalink( 'cart' ) ),
                esc_html__( 'View Cart', 'woocommerce' ));

return $message;}
*/ 
 
 
 
 
 /* Add go to shop button below purchase button on single product page.
  // https://businessbloomer.com/woocommerce-continue-shopping-button-single-product-page/#more-72772
  // Code added from comments.
  add_action( 'woocommerce_single_product_summary', 'bbloomer_continue_shopping_button', 31 );
  function bbloomer_continue_shopping_button() {
    if ( wp_get_referer() ) echo '<a class="button continue" href="./shop">Gå til butikken</a>';
  } 
  // END
  */
 
 
 
 
 

// Change year to år - in HF WooCommerce Subscriptions
// https://wordpress.org/support/topic/adjusting-phrases/#post-9819212 
add_filter('hf_subscription_product_price_string', 'mark_change_price_string', 10, 3);
function mark_change_price_string($subscription_string, $product, $include){
return str_replace("year", "år", $subscription_string);
}
 
 
 /* ---------   WooCommerce - Shop page --------*/
 
 // Remove sorting result.
 remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
 remove_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);
 
 // Remove sorting drop down.
 remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
// remove_action('woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 30);
 
 // Or  use the CSS: .storefront-sorting class with a display none
 add_action('woocommerce_single_product_summary', 'woocommerce_taxonomy_archive_description', 10);  
 
 
 /* https://docs.woocommerce.com/document/customise-the-woocommerce-breadcrumb/ */
 add_action( 'init', 'jk_remove_wc_breadcrumbs' );
 function jk_remove_wc_breadcrumbs() {
     remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
 }  
 
 /* ON login all products visible except members product. On log out only members product visible. */
 // https://businessbloomer.com/woocommerce-remove-specific-category-shop-loop/
 // https://stackoverflow.com/questions/34684881/hide-products-from-users-who-are-not-logged-in-using-tags/34689768#34689768 
 add_action( 'woocommerce_product_query', 'show_hide_products_category_shop' );
 function show_hide_products_category_shop( $q ) {
     $tax_query = (array) $q->get( 'tax_query' );
     
     if ( is_user_logged_in() ) {
         
         $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => array( 'bli-medlem'), // Category slug here
                'operator' => 'NOT IN'
         );
  
     } else {
         $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => array( 'ukens pose', 'vakt' ), // Category slug here. ukens pose and vakt will not be seen when user is not logged in.
                'operator' => 'NOT IN'
         );
  
     }
     $q->set( 'tax_query', $tax_query );
  
 }

 
 // END
 
 
 // Click kjøp and go straight to checkout page.
 //https://wpbeaches.com/skip-cart-go-straight-to-checkout-page-in-woocommerce/
 // NB! I have added the if and else statement. So only the not logged in user can add medlemskap and go straight to checkout.
 add_filter('woocommerce_add_to_cart_redirect', 'themeprefix_add_to_cart_redirect');
 function themeprefix_add_to_cart_redirect() {
  	if ( !is_user_logged_in() ) {
  		global $woocommerce;
  		$checkout_url = wc_get_checkout_url();
  		return $checkout_url;
  	}
  	else {
  	}
  		
 }

// For membership product.
// In single membershop product page. Add to cart redirects page to checkout. 
// https://stackoverflow.com/questions/15592633/woocommerce-add-to-cart-button-redirect-to-checkout 
function redirect_to_checkout() {
	if ( !is_user_logged_in() ) {
    	return WC()->cart->get_checkout_url();
    }
    else {
    }	
}
 
 
 
 /* REMOVE buy button on specific category products.
 // https://stackoverflow.com/questions/41660835/remove-add-cart-button-in-woocommerce-for-a-specific-product-category
 function western_custom_buy_buttons(){
    $product = get_product();
    if ( has_term( 'forhandsvisning', 'product_cat') ){
    // removing the purchase buttons
                             remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
    remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
    remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
    remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
    }
 }
 
 add_action( 'wp', 'western_custom_buy_buttons' );
 */
 
 
 
 /*------- WooCommerce - only registered users can order products 
  https://businessbloomer.com/woocommerce-hide-price-add-cart-logged-users/  ------- 
  
  add_action('init', 'bbloomer_hide_price_add_cart_not_logged_in');
   
  function bbloomer_hide_price_add_cart_not_logged_in() { 
  if ( !is_user_logged_in()   ) {       
  remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
   remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
  // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
   remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );  
   add_action( 'woocommerce_single_product_summary', 'bbloomer_print_login_to_see', 31 );
   //add_action( 'woocommerce_after_shop_loop_item', 'bbloomer_print_login_to_see', 11 );
  }
  }
  
  // Text and link on the shop page.
  function bbloomer_print_login_to_see() {
  echo '<a href="' . get_permalink(wc_get_page_id('myaccount')) . 'https://designkurs.com/myaccount/">' . __('Register and become a member to purchase our organic food. ', 'theme_name') . '</a>';
  }
 */

 
 
 // Change the text on the shop purchase product button.
 // https://stackoverflow.com/questions/25880460/woocommerce-how-to-edit-the-added-to-cart-message and
 // http://businessbloomer.com/woocommerce-edit-add-to-cart-text-by-product-category/?ck_subscriber_id=140754468
 add_filter( 'woocommerce_product_add_to_cart_text', 'bbloomer_archive_custom_cart_button_text' );
   
 function bbloomer_archive_custom_cart_button_text() {
 global $product;
  
 $terms = get_the_terms( $product->ID, 'product_cat' );
  foreach ($terms as $term) {
             $product_cat = $term->name;
             break;
 }
  
 switch($product_cat)
 {
     case 'Ukens pose';
         return 'Kjøp'; break;
     case 'Årsmedlemskap';
         return 'Kjøp Årsmedlemskap'; break;
     case 'Vakt';
         return 'Ta en vakt'; break;    
 // case 'category3'; etc...
 // return 'Category 3 button text'; break;
  
     default;
         return 'Default button text when no match found'; break;
 }
 }
 
 // END 
 
 
 //https://businessbloomer.com/woocommerce-add-nextprevious-single-product-page/ 
 // add_action( 'woocommerce_before_single_product', 'bbloomer_prev_next_product' );
   
  // and if you also want them at the bottom...
  add_action( 'woocommerce_after_single_product', 'bbloomer_prev_next_product' );
   
  function bbloomer_prev_next_product(){
   
  echo '<div class="prev_next_buttons">';
   
      // 'product_cat' will make sure to return next/prev from current category
         $previous = next_post_link('%link', '☜ Produkt: %title', TRUE, ' ', 'product_cat'); 
         // Arrow: '&larr;  Changed Previous to forrige. Changed TRUE to FALSE. Added %title.
      	$next = previous_post_link('%link', 'Produkt: %title  ☞', TRUE, ' ', 'product_cat'); 
      	// Arrow: &rarr; Changed Next to neste. Changed TRUE to FALSE
 
      echo $previous;
      echo $next;
       
  echo '</div>';
           
  }
  
  // END
 
 
 
 /* ------------ WooCommerce - Cart ----------- */
 
 
 // Remove trash icon and then add a new. I have added an fontawesome icon.
 // Kathy - Helga the Viking helped me with the code.
 function kia_cart_item_remove_link( $link, $cart_item_key ) {
     return str_replace( '&times;', '<span class="cart-remove-icon"><i class="fa fa-trash" aria-hidden="true"></i></span>', $link );
 }
 add_filter( 'woocommerce_cart_item_remove_link', 'kia_cart_item_remove_link', 10, 2 );
 
 
 /* ------------ WooCommerce - Checkout ----------- */


 // Remove what is PayPal text link. 
 // https://businessbloomer.com/woocommerce-remove-paypal-checkout/ 
 add_filter( 'woocommerce_gateway_icon', 'bbloomer_remove_what_is_paypal', 10, 2 );
 function bbloomer_remove_what_is_paypal( $icon_html, $gateway_id ) { 
 if( 'paypal' == $gateway_id ) { 
 $icon_html = '<img src="/wp-content/plugins/woocommerce/includes/gateways/paypal/assets/images/paypal.png" alt="PayPal Acceptance Mark">';
 }
  
 return $icon_html;
 }
 
 //END
 
 
 // define the wc_add_to_cart_message 
 /* https://stackoverflow.com/questions/37126658/hide-added-to-cart-message-on-checkout-page-in-woocommerce
 function empty_wc_add_to_cart_message( $message, $product_id ) { 
 	if ( is_user_logged_in() ) {
    	 // add the filter 
    	 add_filter( 'wc_add_to_cart_message', 'empty_wc_add_to_cart_message', 10, 2 ); 
    }
    else {
    }
    	 
 }; 
   */       

   
   
  
  
/* --------- My Account page and checkout fields .....  -----*/

// My account fields.
// Adjusting the visible sections.
/* https://docs.woocommerce.com/document/woocommerce-endpoints-2-1/ 
*
 * Change the order of the endpoints that appear in My Account Page - WooCommerce 2.6
 * The first item in the array is the custom endpoint URL - ie http://mydomain.com/my-account/my-custom-endpoint
 * Alongside it are the names of the list item Menu name that corresponds to the URL, change these to suit
 */
function wpb_woo_my_account_order() {
 $myorder = array(
 'dashboard' => __( 'Dashboard', 'woocommerce' ),
 'orders' => __( 'Orders', 'woocommerce' ),
// 'downloads' => __( 'Downloads', 'woocommerce' ),
// 'edit-address' => __( 'Addresses', 'woocommerce' ),
 'payment-methods' => __( 'Payment Methods', 'woocommerce' ),
 'customer-logout' => __( 'Logout', 'woocommerce' ),
 );
 return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order' );


// Password strength meter
/**  https://www.snip2code.com/Snippet/1107676/Reduce-or-remove-WooCommerce-2-5-minimum
 *Reduce the strength requirement on the woocommerce password.
 * 
 * Strength Settings
 * 3 = Strong (default)
 * 2 = Medium
 * 1 = Weak
 * 0 = Very Weak / Anything
 */
function reduce_woocommerce_min_strength_requirement( $strength ) {
    return 1;
}
add_filter( 'woocommerce_min_password_strength', 'reduce_woocommerce_min_strength_requirement' );
  
  
 
 /* -------- WooCommerce - checkout fields ----------   
 
/* From Helga the Viking - Kathy.  */
function kia_modify_default_address_fields( $fields ){
    if( isset( $fields['company'] ) ) unset( $fields['company'] );
    if( isset( $fields['country'] ) ) unset( $fields['country'] );
    if( isset( $fields['address_1'] ) ) unset( $fields['address_1'] );
    if( isset( $fields['address_2'] ) ) unset( $fields['address_2'] );
    if( isset( $fields['city'] ) ) unset( $fields['city'] );
    if( isset( $fields['state'] ) ) unset( $fields['state'] );
    if( isset( $fields['postcode'] ) ) unset( $fields['postcode'] );
    return $fields; 
}
add_filter( 'woocommerce_default_address_fields', 'kia_modify_default_address_fields' );

function kia_remove_billing_phone_fields( $fields ){
    // if( isset( $fields['billing_phone'] ) ) unset( $fields['billing_phone'] );
    // if( isset( $fields['billing_email'] ) ) $fields['billing_email']['class'] = array( 'form-row-wide' );
    return $fields;

}
add_filter( 'woocommerce_billing_fields', 'kia_remove_billing_phone_fields' );

// https://businessbloomer.com/woocommerce-remove-order-notes-checkout-page/
// Removes the Additional Information box.
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );


// Endring av Billing details = Norsk er det Faktureringsdetaljer. Endret til Konto informasjon.
// https://stackoverflow.com/questions/44419189/woocommerce-3-0-change-billing-details-to-say-shipping-details-on-checkout
function wc_billing_field_strings( $translated_text, $text, $domain ) {
    switch ( $translated_text ) {
        case 'Faktureringsdetaljer' :
            $translated_text = __( 'Konto informasjon', 'woocommerce' );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'wc_billing_field_strings', 20, 3 );



//https://stackoverflow.com/questions/34665347/woocommerce-with-wordpress-change-text-for-creating-password-on-checkout-page
//Change the Create Account checkout text
function wc_create_account_field_strings( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
case 'Registrert som kunde?' :
$translated_text = __( 'Hvis du er allerede er registert som medlem så kan du logge deg inn.', 'woocommerce' );
break;
}
return $translated_text;
}
add_filter( 'gettext', 'wc_create_account_field_strings', 20, 3 );




// Only one product in cart
// https://businessbloomer.com/woocommerce-allow-1-product-cart/ 
add_filter( 'woocommerce_add_to_cart_validation', 'bbloomer_only_one_in_cart', 10, 2 );
  
function bbloomer_only_one_in_cart( $cart_item_data, $product_id ) {
global $woocommerce;
// if adding product ID, empty cart
 
if ( $product_id == 3204 ) {
$woocommerce->cart->empty_cart();
} else {
 
//  // if adding other products, check ID is not @ cart already
//  foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
//  $_product = $values['data'];
//  if( $_product->id == 3204 ) {
//        wc_add_notice('That product is already in the cart!', 'error' );
//    return false;
//  }
// }
}
return $cart_item_data;
}