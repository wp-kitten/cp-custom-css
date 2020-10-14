<?php

/**
 * Stores the name of the plugin's directory
 * @var string
 */

use App\Helpers\ScriptsManager;

define( 'CPCS_PLUGIN_DIR_NAME', basename( dirname( __FILE__ ) ) );
/**
 * Stores the system path to the plugin's directory
 * @var string
 */
define( 'CPCS_PLUGIN_DIR_PATH', trailingslashit( wp_normalize_path( dirname( __FILE__ ) ) ) );

function cpcsGetStylesheetUrl()
{
    $uploadsDir = cp_get_uploads_dir();
    return path_combine( $uploadsDir[ 'url' ], 'custom-styles.min.css' );
}

if ( cp_is_admin() ) {
    require_once( CPCS_PLUGIN_DIR_PATH . 'admin/hooks.php' );
    require_once( CPCS_PLUGIN_DIR_PATH . 'admin/routes.php' );
}

/**
 * Enqueue custom styles
 */
add_action( 'contentpress/site/head', function () {
    ScriptsManager::enqueueStylesheet( 'contentpress-custom-styles', cpcsGetStylesheetUrl() );
} );
