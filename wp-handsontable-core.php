<?php 
/**
 * Plugin Name: WP HandsOnTable Core
 * Plugin URI: http://in-soft.pro/plugins/wc-custom-checkout/
 * Description: Поддержка функций таблиц HandsOnTable в Wordpress. Этот плагин является сервисным и напрямую не вызывается, а лишь добавляет классы, необходимые другим плагинам.
 * Version: 0.1
 * Author: Иван Никитин и партнеры
 * Author URI: http://ivannikitin.com
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
define( 'HOT_TEXT_DOMAIN', 'wp-handsontable-core' );		// Текстовый домен
define( 'HOT_CORE_PATH', plugin_dir_path( __FILE__ ) );		// Путь к папке плагина
define( 'HOT_CORE_URL', plugin_dir_url( __FILE__ ) );		// URL к папке плагина

/**
 * Подключение классов
 */
include( HOT_CORE_PATH . 'classes/handsontable.php');