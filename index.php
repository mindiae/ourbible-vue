<?php
/**
 * Plugin Name: Vue App Dev
 * Description: Vuejs app for Bible, Commentaries and more.
 */
function load_vuescripts() {
    wp_register_script(
        'vue_app',
        'http://localhost:5173/src/main.js'
        // plugin_dir_url( __FILE__ ) . 'dist/assets/index.6c51e975.js'
    );
}
function load_vuestyles() {
    wp_enqueue_style(
        'vue_css',
        plugin_dir_url( __FILE__ ) . 'dist/assets/index.60ed5573.css'
    );
}
add_action('wp_enqueue_scripts', 'load_vuescripts');
add_action('wp_enqueue_scripts', 'load_vuestyles');
function add_type_attribute($tag, $handle, $src) {
    // if not your script, do nothing and return original $tag
    if ( 'vue_app' !== $handle ) {
        return $tag;
    }
    // change the script tag by adding type="module" and return it.
    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    return $tag;
}


function handle_shortcode(){
    wp_enqueue_script('vue_app');
    add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);
    return "<div id='app'>"
       ."Message from Vue: "
       ."</div>";
}

add_shortcode( 'VueApp', 'handle_shortcode' );
