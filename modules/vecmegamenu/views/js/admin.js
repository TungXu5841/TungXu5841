var vecMegamenu = {
	_getFormData: function(){
        var params = [];
        $('#submenu-item-form [data-serializable="true"]').each(function(){
            params.push({
                name: $(this).attr('name'),
                value: $(this).val()
            });
        });
        return params;
    },
    add: function(id_column){
        $('#submenu-item-modal').modal('show');
        vecMegamenu.reloadmce();
        $('#itemform_id_vecmegamenu_submenu_column').val(id_column);
    },
    populateForm: function(data){
        $.each(data, function(i){
            var fieldId = '#itemform_' + i;
            if (typeof data[i] == 'object'){
                if (data[i] != null){
                    $.each(data[i], function(id_lang){
                        if(i == 'image'){
                            $('#'+i + '_' + id_lang).val(data[i][id_lang]);
                        }else if(i == 'htmlcontent'){
                            $(fieldId + '_' + id_lang).html(data[i][id_lang]);
                        }else{
                            $(fieldId + '_' + id_lang).val(data[i][id_lang]);
                        }
                    });    
                }
            }else{
                $(fieldId).val(data[i]);  
                $('#itemform_search_product').val(data[i]);
            }
        });
        SubmenuItemChangeType();
    },
    toggle: function(id){
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'switch',
                id: id,
                ajax : true
            },
            success: function(data)
            {
                vecMegamenu.reload();
                showSuccessMessage(vecMegamenu.successChangeMessage);
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
	edit: function(id){
        vecMegamenu.blockUI('#vecmegamenu-submenu');
        $.ajax({
            type: 'GET',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'edit',
                ajax : true,
                id: id
            },
            success: function(data)
            {   
                console.log(data);
                vecMegamenu.unblockUI('#vecmegamenu-submenu');
                $('#submenu-item-modal').modal();
                vecMegamenu.populateForm(data);
                vecMegamenu.reloadmce();
                
            }
        }).fail(function(){
            vecMegamenu.unblockUI('#vecmegamenu-submenu');
            (vecMegamenu.errorMessage);
        });
    },
    save: function(){
        var params = vecMegamenu._getFormData();
        console.log(params);
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'save',
                ajax : true,
                data: params,
                id: $('#itemform_id').val(),
                id_column: $('#itemform_id_vecmegamenu_submenu_column').val(),
            },
            success: function(data)
            {      
                $('#submenu-item-modal').modal('hide');
                showSuccessMessage(vecMegamenu.successSaveMessage);
                vecMegamenu.reload();
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
    remove: function(id){
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'delete',
                ajax : true,
                id: id,
            },
            success: function(data)
            {      
                showSuccessMessage(vecMegamenu.successDeleteMessage);
                vecMegamenu.reload();
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
    reload: function(){
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'reload',
                ajax : true,
                id_vecmegamenu_item: $('#id_vecmegamenu_item').val()
            },
            success: function(data)
            {
                $('#vecmegamenu-submenu').replaceWith(data.content);
                vecMegamenu.autocompleteProduct();
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
    reloadmce: function(){
        tinySetup({
            editor_selector :"autoload_rte1",
            setup : function(ed) {
                ed.on('change', function(ed, e) {
                    tinyMCE.triggerSave();
                });
                ed.on('blur', function(ed) {
                    tinyMCE.triggerSave();
                });
            }
        });

        
    },
    _getFormDataColumn: function(){
        var params = [];
        $('#submenu-column-form [data-serializable="true"]').each(function(){
            params.push({
                name: $(this).attr('name'),
                value: $(this).val()
            });
        });
        return params;
    },
    populateFormColumn: function(data){
        $.each(data, function(i){
            if (typeof data[i] == 'object'){
                if (data[i] != null){
                    $.each(data[i], function(id_lang){
                       $('#column_'+i+ '_' + id_lang).val(data[i][id_lang]);
                    });    
                }
            }else{
                $('#column_'+i).val(data[i]);
            }
        });
        ColumnChangeTypeLink();
    },
    addColumn: function(id_row){
        $('#submenu-column-modal').modal('show');
        $('#column_id_row').val(id_row);
    },
    editColumn: function(id){
        vecMegamenu.blockUI('#vecmegamenu-submenu');
        $.ajax({
            type: 'GET',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'editColumn',
                ajax : true,
                id: id
            },
            success: function(data)
            {   console.log(data);
                $('#submenu-column-modal').modal(); 
                vecMegamenu.populateFormColumn(data);              
                vecMegamenu.unblockUI('#vecmegamenu-submenu');
            }
        }).fail(function(){
            vecMegamenu.unblockUI('#vecmegamenu-submenu');;
            (vecMegamenu.errorMessage);
        });
    },
    saveColumn: function(){
        var params = vecMegamenu._getFormDataColumn();
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'saveColumn',
                ajax : true,
                data: params,
                id: $('#column_id').val(),
                id_row: $('#column_id_row').val(),
            },
            success: function(data)
            {      
                $('#submenu-column-modal').modal('hide');
                showSuccessMessage(vecMegamenu.successSaveMessage);
                vecMegamenu.reload();
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
    removeColumn: function(id_column){
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'deleteColumn',
                ajax : true,
                id: id_column,
            },
            success: function(data)
            {      
                showSuccessMessage(vecMegamenu.successDeleteMessage);
                vecMegamenu.reload();
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
//functions for rows
    _getFormDataRow: function(){
        var params = [];
        $('#submenu-row-form [data-serializable="true"]').each(function(){
            params.push({
                name: $(this).attr('name'),
                value: $(this).val()
            });
        });
        return params;
    },
    populateFormRow: function(data){
        $.each(data, function(i){
            if(i =='id'){
                $('#row_'+i+'_row').val(data[i]);
            }else{
                $('#row_'+i).val(data[i]);
            }
            
        });
    },
    addRow: function(id){
        $('#submenu-row-modal').modal('show');
        $('#row_id_vecmegamenu_item').val(id);
    },
    editRow: function(id){
        vecMegamenu.blockUI('#vecmegamenu-submenu');
        $.ajax({
            type: 'GET',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'editRow',
                ajax : true,
                id: id
            },
            success: function(data)
            {   console.log(data);
                $('#submenu-row-modal').modal(); 
                vecMegamenu.populateFormRow(data);              
                vecMegamenu.unblockUI('#vecmegamenu-submenu');
            }
        }).fail(function(){
            vecMegamenu.unblockUI('#vecmegamenu-submenu');
            (vecMegamenu.errorMessage);
        });
    },
    toggleRow: function(id){
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'switchRow',
                id: id,
                ajax : true
            },
            success: function(data)
            {
                vecMegamenu.reload();
                showSuccessMessage(vecMegamenu.successChangeMessage);
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
    saveRow: function(){
        var params = vecMegamenu._getFormDataColumn();
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'saveRow',
                ajax : true,
                data: params,
                id: $('#row_id_row').val(),
                id_vecmegamenu_item: $('#row_id_vecmegamenu_item').val(),
            },
            success: function(data)
            {      
                $('#submenu-row-modal').modal('hide');
                showSuccessMessage(vecMegamenu.successSaveMessage);
                vecMegamenu.reload();
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
    removeRow: function(id_column){
        $.ajax({
            type: 'POST',
            url: vecMegamenu.ajaxUrl,
            dataType: 'json',
            data: {
                controller : 'AdminVecMegamenuSubmenu',
                action : 'deleteRow',
                ajax : true,
                id: id_column,
            },
            success: function(data)
            {      
                showSuccessMessage(vecMegamenu.successDeleteMessage);
                vecMegamenu.reload();
            }
        }).fail(function(){
            showErrorMessage(vecMegamenu.errorMessage);
        });
    },
    blockUI: function(selector){
        $(selector).addClass('ar-blocked');
        $(selector).find('.ar-loading').remove();
        $(selector).append('<div class="ar-loading"><div class="ar-loading-inner"></div></div>');
    },
    unblockUI: function(selector){
        $(selector).find('.ar-loading').remove();
        $(selector).removeClass('ar-blocked');
    },
    autocompleteProduct: function(){
        $('#itemform_search_product').autocomplete('ajax_products_list.php?excludeIds=', {
            minChars: 1,
            autoFill: true,
            max:20,
            matchContains: true,
            mustMatch:true,
            scroll:false,
            cacheLength:0,
            extraParams:{ excludeIds:getMenuProductsIds()},
            formatItem: function(item) {
                if (item.length == 2) {
                  return item[1]+' - '+item[0];  
                } else {
                    return '--';
                }
            }
        }).result(function(event, data, formatted) {
            if (data == null || data.length != 2)
                return false;
            var productId = data[1];
            var productName = data[0];

            var divProductName = $('#itemform_id_product');
            divProductName.val(productId);

            $('#itemform_search_product').setOptions({
                extraParams: {excludeIds : getMenuProductsIds()}
            });
        });
    }
};
$(document).ready(function(){
    vecMegamenu.autocompleteProduct();
})
var getMenuProductsIds = function()
{
    if (!$('#inputMenuProducts').val())
        return '-1';
    return $('#inputMenuProducts').val().replace(/\-/g,',');
}