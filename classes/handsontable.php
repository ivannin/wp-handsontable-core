<?php
/**
 * Класс реализует работу с таблицей Handontalbe
 */
class HOT_Table
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
            'id'            =>  'hot_' . md5(microtime()),         // ID таблицы
            'loadHandler'   =>  '',     // Имя функции обработчика загружки данных
            'saveHandler'   =>  '',     // Имя функции обработчика сохранения данных
            
        );        
        // Переопределение параметров
        foreach( $params as $key => $value )
            $this->_params[$key] = $value;
        
        // Загрузка стилей
        wp_enqueue_style("wp-jquery-ui-dialog");
        wp_enqueue_style('handsontable', HOT_CORE_URL . 'js/handsontable-0.20.0/handsontable.full.min.css', false, '0.20.0', 'all');
        
		// Регистрация скриптов
        wp_register_script('handsontable', HOT_CORE_URL . 'js/handsontable-0.20.0/handsontable.full.min.js', array('jquery-ui-dialog'), '0.20.0', true);
        wp_register_script('numeral-ru', HOT_CORE_URL . 'js/numeral/languages/ru.min.js', array('handsontable'), '1.5.3', true);

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
     * Метод возвращает HTML код кнопки [Обновить]
     *
     * @return string   Сформированный HTML код кнопки
     */        
    protected function getHtmlLoadButton()
    {   
        return '<button class="button loadButton">' . __( 'Обновить', HOT_TEXT_DOMAIN ) . '</button>';
    }    
    
    
    /**
     * Метод возвращает HTML код кнопки [Сохранить]
     *
     * @return string   Сформированный HTML код кнопки
     */        
    protected function getHtmlSaveButton()
    {   
        return '<button class="button saveButton">' . __( 'Сохранить', HOT_TEXT_DOMAIN ) . '</button>';
    } 
    
    /**
     * Метод возвращает HTML код блока Поиск
     *
     * @return string   Сформированный HTML код кнопки
     */        
    protected function getHtmlSearch()
    {   
        return '<input type="text" class="search" placeholder="' . __( 'Поиск', HOT_TEXT_DOMAIN ) . '" />';
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
        return '';
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
        $jsHandlers     = $this->getJsHandlers();
        $progressImage  = IN_URL . 'img/ProcessAnimationSmall2_v3.gif';
        
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
        var currentTable = $('#{$this->id} .hot');
        currentTable.handsontable(options);
        // Сохраним данные для манипуляции
        var dataId = 'data_' + containerId;
        window[dataId] = options.data
        // Для отладки
        window.myHOT = currentTable;
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