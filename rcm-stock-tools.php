<?php
/*
 * Plugin Name: RCM Stock Tools
 * Plugin URI: https://piglet.me/
 * Description: RCM Stock Tools
 * Version: 0.1.0
 * Author: heiblack
 * Author URI: https://piglet.me
 * License:  GPL 3.0
 * Domain Path: /languages
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
*/

add_filter( 'woocommerce_order_item_quantity', function ( $quantity, $order, $item ){
    $order_id = $item['variation_id'];
    if($order_id > 0){
        $volume = get_post_meta( $order_id, '_rcm-stock-tools', true );
        $volume = $volume ? $volume : 1;
        $new_quantity =  (int)($quantity * $volume);
        return $new_quantity;
    }
    return $quantity;
}, 10, 3 );

add_action('woocommerce_product_after_variable_attributes', function ($loop, $variation_data, $variation) {
    echo '<div class="Woo_HideShipping_Volume" style="border-top: 1px solid #eee;border-bottom: 1px solid #eee;padding-bottom: 10px">';
    woocommerce_wp_text_input(
        array(
            'id'                => "_rcm-stock-tools{$loop}",
            'name'              => "_rcm-stock-tools[{$loop}]",
            'value'             => get_post_meta( $variation->ID, '_rcm-stock-tools', true ),
            'label'             => 'number',
            'type'              => 'number',
            'desc_tip'          => true,
            'custom_attributes' => array('min' => '1')
        )
    );
    echo '</div>';
},10,3);

add_action( 'woocommerce_save_product_variation', function ( $id, $loop ){
    $text_field = sanitize_text_field($_POST['_rcm-stock-tools'][ $loop ]);
    update_post_meta( $id, '_rcm-stock-tools', esc_attr( $text_field ));
}, 10, 2 );


