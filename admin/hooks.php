<?php

use App\Helpers\MenuHelper;
use App\Helpers\ScriptsManager;
use Illuminate\Support\Facades\File;

if ( !defined( 'CPCS_PLUGIN_DIR_NAME' ) ) {
    exit;
}

//#! Register the views path
add_filter( 'valpress/register_view_paths', 'cpcs_register_view_paths', 20 );
function cpcs_register_view_paths( $paths = [] )
{
    $viewPath = path_combine( public_path( 'plugins' ), CPCS_PLUGIN_DIR_NAME, 'views' );
    if ( !in_array( $viewPath, $paths ) ) {
        array_push( $paths, $viewPath );
    }
    return $paths;
}

//#! Add the sidebar menu entry
add_action( 'valpress/admin/sidebar/menu', function () {
    if ( vp_current_user_can( 'manage_options' ) ) {
        ?>
        <li class="treeview <?php MenuHelper::activateMenuItem( 'admin.vp_custom_css' ); ?>">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-code"></i>
                <span class="app-menu__label"><?php esc_html_e( __( 'cpcs::m.Custom CSS' ) ); ?></span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item <?php MenuHelper::activateSubmenuItem( 'admin.vp_custom_css' ); ?>" href="<?php esc_attr_e( route( 'admin.vp_custom_css' ) ); ?>">
                        <?php esc_html_e( __( 'cpcs::m.Custom CSS' ) ); ?>
                    </a>
                </li>
            </ul>
        </li>
        <?php
    }
} );

/**
 * Register the path to the translation file that will be used depending on the current locale
 */
add_action( 'valpress/app/loaded', function () {
    vp_register_language_file( 'cpcs', path_combine( public_path( 'plugins' ), CPCS_PLUGIN_DIR_NAME, 'lang' ) );
} );

add_action( 'valpress/admin/head', function () {
    //#! Make sure we're only loading in our page
    if ( request()->is( 'admin/cp-custom-css' ) ) {
        ScriptsManager::enqueueStylesheet( 'cpcs-plugin-styles', vp_plugin_url( CPCS_PLUGIN_DIR_NAME, 'assets/styles.css' ) );

        ScriptsManager::enqueueHeadScript( 'ace-editor.js', '//cdnjs.cloudflare.com/ajax/libs/ace/1.4.11/ace.js' );
        ScriptsManager::enqueueHeadScript( 'ace-mode-css.js', '//cdnjs.cloudflare.com/ajax/libs/ace/1.4.11/mode-css.min.js' );
        ScriptsManager::enqueueHeadScript( 'ace-theme-monokai.js', '//cdnjs.cloudflare.com/ajax/libs/ace/1.4.11/theme-monokai.min.js' );

        ScriptsManager::enqueueFooterScript( 'cpcs-ace-editor-init.js', vp_plugin_url( CPCS_PLUGIN_DIR_NAME, 'assets/ace-editor-init.js' ) );
    }
}, 80 );

add_action( 'valpress/site/head', function () {
    $uploadsDir = vp_get_uploads_dir();
    $stylesheetMinFilePath = path_combine( $uploadsDir[ 'dir' ], 'custom-styles.min.css' );
    if ( File::isFile( $stylesheetMinFilePath ) ) {
        ScriptsManager::enqueueStylesheet( 'cpcs-custom-styles.css', path_combine( $uploadsDir[ 'url' ], 'custom-styles.min.css' ) );
    }
}, 500 );
