# wp-handsontable-core
## Функционал HandsOnTable в других проектах WP
Данный плагин добавляет код класса, формирующего вывод таблицы HandsOnTable



## Пример кода проверки активации wp-handsontable-core в вашем плагине
```php
if ( ! defined( 'WP_HOT_CORE_VERSION' )) 
{
	add_action( 'admin_notices', 'my_wp_hot_core_missing' );
	deactivate_plugins( plugin_basename( __FILE__ ) );
}
	

function my_wp_hot_core_missing() 
{
	$class = 'notice notice-error';
	$message = __( 'Для работы этого плагина третуется установка и активация wp-handsontable-core!<br/>'
				'https://github.com/ivannin/wp-handsontable-core', 'sample-text-domain' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}
```