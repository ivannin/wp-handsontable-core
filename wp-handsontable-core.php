<?php 
/**
 * Plugin Name: WP HandsOnTable Core
 * Plugin URI: http://in-soft.pro/plugins/wp-handsontable-core/
 * Description: Support functions HandsOnTable in Wordpress. This plugin is a service and not directly caused, but only adds classes required other plugins.
 * Version: 0.1
 * Author: Ivan Nikitin and partners
 * Author URI: http://ivannikitin.com
 * Text domain: wp-handsontable-core
 *
 * Copyright 2016 Ivan Nikitin  (email: info@ivannikitin.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Напрямую не вызываем!
if ( ! defined( 'ABSPATH' ) ) 
	die( '-1' );

/**
 * Определения плагина
 */
define( 'WP_HOT_CORE_VERSION', '0.1' );							// Версия плагина
define( 'WP_HOT_CORE_TEXT_DOMAIN', 'wp-handsontable-core' );	// Текстовый домен
define( 'WP_HOT_CORE_PATH', plugin_dir_path( __FILE__ ) );		// Путь к папке плагина
define( 'WP_HOT_CORE_URL', plugin_dir_url( __FILE__ ) );		// URL к папке плагина

/**
 * Подключение классов
 */
include( WP_HOT_CORE_PATH . 'classes/wphotcore.php');

/**
 * Попытка загрузить этот плагин раньше других
 * http://stv.whtly.com/2011/09/03/forcing-a-wordpress-plugin-to-be-loaded-before-all-other-plugins/
 */
add_action( 'activated_plugin', 'wp_hot_core_load_first' );
function wp_hot_core_load_first()
{
    $path = str_replace( WP_PLUGIN_DIR . '/', '', __FILE__ );
    if ( $plugins = get_option( 'active_plugins' ) ) 
	{
        if ( $key = array_search( $path, $plugins ) ) 
		{
            array_splice( $plugins, $key, 1 );
            array_unshift( $plugins, $path );
            update_option( 'active_plugins', $plugins );
        }
    }
}

/**
 * Локализация плагина
 */
add_action( 'init', 'wp_hot_core_load_textdomain' );
function wp_hot_core_load_textdomain() 
{
	load_plugin_textdomain( WP_HOT_CORE_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' ); 
}