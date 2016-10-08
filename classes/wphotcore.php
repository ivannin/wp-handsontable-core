<?php
/**
 * Класс реализует работу с таблицей Handontalbe
 */
class WP_HOT_Core
{
    /**
     * Параметры объекта
     */
    protected $_params;
    
    /**
     * Конструктор класса
     *
     * @param mixed $params    Идентификатор таблицы
     */
    public function __construct( $params=array() )
    {
        // Инициализация параметров
        $this->_params = array(
            'id'            =>  'hot_' . md5(microtime()),	// ID таблицы
            'loadHandler'   =>  '',     					// Имя функции обработчика загрузки данных
            'saveHandler'   =>  '',							// Имя функции обработчика сохранения данных
            'ajaxAction'    =>  get_class($this),			// Имя действия для Ajax вызовов
            
        );        
        // Переопределение параметров
        foreach( $params as $key => $value )
            $this->_params[$key] = $value;
        
        // Загрузка стилей
        wp_enqueue_style("wp-jquery-ui-dialog");
        wp_enqueue_style('handsontable', WP_HOT_CORE_URL . 'js/handsontable-0.20.0/handsontable.full.min.css', false, '0.20.0', 'all');
        
		// Регистрация скриптов
        wp_register_script('handsontable', WP_HOT_CORE_URL . 'js/handsontable-0.20.0/handsontable.full.min.js', array('jquery-ui-dialog'), '0.20.0', true);
        wp_register_script('numeral-ru', WP_HOT_CORE_URL . 'js/numeral/languages/ru.min.js', array('handsontable'), '1.5.3', true);

        // Загрузка скриптов
		wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('handsontable');
        wp_enqueue_script('numeral-ru');
    }
    
    
    /**
     * Метод формирует HTML код элементов управления перед таблицей
     *
     * @return string   Сформированный HTML код элементов управления перед таблицей
     */        
    protected function getHtmlControls()
    {   
        return
            $this->getHtmlSearch()      . ' | ' . 
            $this->getHtmlLoadButton()  . ' ' . 
            $this->getHtmlSaveButton();
    }
    
    /**
     * Метод возвращает HTML код кнопки [Load]
     *
     * @return string   Сформированный HTML код кнопки
     */        
    protected function getHtmlLoadButton()
    {   
        return '<button class="button loadButton">' . __( 'Load', WP_HOT_CORE_TEXT_DOMAIN ) . '</button>';
    }    
    
    
    /**
     * Метод возвращает HTML код кнопки [Save]
     *
     * @return string   Сформированный HTML код кнопки
     */        
    protected function getHtmlSaveButton()
    {   
        return '<button class="button saveButton">' . __( 'Save', WP_HOT_CORE_TEXT_DOMAIN ) . '</button>';
    } 
    
    /**
     * Метод возвращает HTML код блока Поиск
     *
     * @return string   Сформированный HTML код кнопки
     */        
    protected function getHtmlSearch()
    {   
        return '<input type="text" class="search" placeholder="' . __( 'Search', WP_HOT_CORE_TEXT_DOMAIN ) . '" />';
    }    
    
    
    /**
     * Метод формирует JSON код настроек таблицы
     *
     * @return string   Сформированный JSON код
     */        
    protected function getTableOptions()
    {   
        return '{minSpareRows:1, rowHeaders:true, colHeaders:true, contextMenu:true}';
    } 
    
    
    /**
     * Метод формирует JSON код предварительно загружаемых данных
     *
     * @return string   Сформированный JSON код
     */        
    protected function getPreloadedData()
    {   
        // Если указан обработчик, вызываем его и получаем данные
        $loadFunction = $this->loadHandler;
        $data = ( !empty( $loadFunction ) ) ? json_encode( call_user_func($loadFunction) ) : '[]';            
        return $data;
    }     
    
    
    /**
     * Метод формирует JavaScript код обработчиков который вставляется после инициализации таблицы
     *
     * @return string   Сформированный JSON код
     */        
    protected function getJsHandlers()
    {           
        // Штатные обработчики
		$handlers = array(
			'search.js',		// Поиск
		);
		
		$js = '';
		foreach ($handlers as $jsFile)
		{
			if ( WP_DEBUG )
				$js .= '/* ' . WP_HOT_CORE_PATH . 'js/' . $jsFile . ' */' . PHP_EOL;
 			$jsMinFile = str_replace('.js', '.min.js', $jsFile);
			if ( file_exists( WP_HOT_CORE_PATH . 'js/' . $jsMinFile ) && ! WP_DEBUG )
			{
				$js .= file_get_contents( WP_HOT_CORE_PATH . 'js/' . $jsMinFile ) . PHP_EOL;
			}
			elseif ( file_exists( WP_HOT_CORE_PATH . 'js/' . $jsFile ) )
			{
				$js .= file_get_contents( WP_HOT_CORE_PATH . 'js/' . $jsFile ) . PHP_EOL;
			}
		}
		return $js;
    }    
      
    /**
     * Вывод HTML таблицы
     *
     * @return string   Сформированный HTML код
     */        
    public function __toString()
    {
        // Вызываем методы подготовки
        $htmlControls   = $this->getHtmlControls();
        $tableOptions   = $this->getTableOptions();
        $preloadedData  = $this->getPreloadedData();
        $loadAction     = 'load_' . $this->ajaxAction;
        $saveAction    =  'save_' . $this->ajaxAction;
		$jsHandlers     = $this->getJsHandlers();
        $progressImage  = WP_HOT_CORE_URL . 'img/process-animation.gif';
        
        $html = <<<EOD
<div id="{$this->id}" class="handsontable-container">
    <div class="fieldGroup">{$htmlControls}</div><br style="clear:both" />
    <div class="hot" style="width:90%;margin-top:20px;"></div><br style="clear:both" />
    <div class="animationProcess" style="position:fixed;top:50%;left:50%;margin-top:0;margin-left:0;z-index:999;display:none"><img src="{$progressImage}" alt="Пожалуйста, ждите..." /></div>
    <script>
    jQuery(function($){
        var containerId = '{$this->id}';
        var options = {$tableOptions};
        options.data = {$preloadedData};
        var dataId = 'data_' + containerId;
        window[dataId] = options.data;
		var loadAction = '{$loadAction}';
		var saveAction = '{$saveAction}';
        var currentTable = $('#{$this->id} .hot');
        currentTable.handsontable(options);
        {$jsHandlers}
    });
    </script>    
</div>
EOD;
       
        return $html;
    }        
        
    /**
     * Установка свойства объекта
     *
     * @param string $key   Имя свойства
     * @param mixed $value  Значение свойства
     */  
    public function __set($key, $value) 
    {
        $this->_params[$key] = $value;
    }
        
    /**
     * Чтение свойства объекта
     *
     * @return mixed   Значение свойства
     */  
    public function __get($key) 
    {
        return array_key_exists( $key, $this->_params ) ? $this->_params[$key] : NULL;
    }   
}
?>