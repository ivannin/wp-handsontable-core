/**
 * Поиск
 */

// Таймеp
var timerId = 'keyTimer_' + containerId;   // containerId определяется в основном скрипте

$('#' + containerId + ' .search').on('keyup', function()
{
    
    // Сохраненный датасет
    var dataId = 'data_' + containerId;
    if (typeof window[dataId] === undefined)
    {
        //console.warn('Датасет неопределен ', dataId);
        return;
    }    
    var data = window[dataId];
    
    var searchValue = this.value.trim().toLowerCase();
    if (searchValue.length === 0)
    {
        currentTable.handsontable('loadData', data);
        currentTable.handsontable('render');
        return;
    }
    
    // Если есть таймер, сбрасываем его
    if (typeof window[timerId] !== undefined)
        clearTimeout(window[timerId]);
    
    window[timerId] = setTimeout(function()
    {
        //console.log('Filtering data source');
        
        //Пробуем создать регулярное выражение
        var reMode = false;
        try
        {
            var re = RegExp(searchValue, 'i');
            reMode = true;
            console.log(re);
        }
        catch (e)
        {
            //console.log('Режим текстового поиска');
        }

        // Формируем отфильтрованный датасет
        //console.log('Исходные данные ', data);
        var tmpData = [];        
        for (var i=0; i<data.length; i++)
        {
            var dataRow = data[i];
            var rowFound = false;
            $.each(dataRow, function(key, value)
            {
                if (reMode)
                {
                   if (re.test(value))
                   {
                       rowFound = true;
                       return false; // break .each function
                   }
                }
                else
                {
                   if (typeof value == 'string' && value.indexOf(searchValue) >= 0)
                   {
                       rowFound = true;
                       return false; // break .each function
                   }
                }                
            });
            
            if (rowFound) 
                tmpData.push(dataRow);
            
        }
        //console.log('Отфильтрованные данные ', tmpData);
        currentTable.handsontable('loadData', tmpData);
        currentTable.handsontable('render');
        
    }, 500);
 
});
