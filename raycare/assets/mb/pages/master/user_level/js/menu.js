mb.app.user_level = mb.app.user_level || {};
mb.app.user_level.menu = mb.app.user_level.menu || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form                   = $('#form_menu_user_level'),
        tplFormParent            = '<li class="fieldset">' + $('#tpl-form-parent', $form).val() + '<hr></li>',
        regExpTplPhone          = new RegExp('menu_parent[0]', 'g'),   // 'g' perform global, case-insensitive
        parentCounter            = 1,
       
        formsParent = 
        {
            'menu_parent' : 
            {            
                section  : $('#section-parent', $form),
                urlData  : function(){ return baseAppUrl + 'get_menu'; },
                template : $.validator.format( tplFormParent.replace(regExpTplPhone, '_id_{0}') ), //ubah ke format template jquery validator
                counter  : function(){ parentCounter++; return parentCounter-1; },
                fields   : ['id','nama', 'cabang_id','url','icon_class'],
                fieldPrefix : 'menu_parent'
            }   
        };

    var initForm = function(){

        var cabang_id = $('select#cabang_id').val();       
        var level_id = $('input[name="id"]').val();
        var $selectParent = $('select#menu_parent_id');

        $.each(formsParent, function(idx, form){
            var $section           = form.section,
                $fieldsetContainer = $('ul', $section);

            $.ajax({
                type     : 'POST',
                url      : form.urlData(),
                data     : {cabang_id: cabang_id, parent_id: 0,level_id:level_id},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    if (results.success === true) {
                        var rows = results.rows;
                        $selectParent.empty();

                        $selectParent.html($("<option></option>").attr("value", '0').text('Utama'));
                        
                        $.each(rows, function(idx, data){
                            addFieldsetParent(form, data);
                            $selectParent.append($("<option></option>").attr("value", data.id).text(data.nama));
                        });
                    }
                    else
                    {
                        $selectParent.empty();
                        $selectParent.html($("<option></option>").attr("value", '0').text('Utama'));

                        $fieldsetContainer.html('');
                        addFieldsetParent(form,{});
                    }

                },
                complete : function(){
                    handleTableFitur();
                    Metronic.unblockUI();
                }
            });

            // handle button add
            $('a.add-parent', form.section).on('click', function(){
                addFieldsetParent(form,{});
            });
             
            // // beri satu fieldset kosong
            // addFieldsetParent(form,{});
        });  

        
    };

    var handleTableFitur = function() {
        var fitur_cabang_id = $('select#fitur_cabang_id').val();
        var cabang_id = $('select#cabang_id').val(); 
        var level_id = $('input[name="id"]').val();
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'listing_fitur',
            data     : {cabang_id:cabang_id,fitur_cabang_id: fitur_cabang_id,level_id:level_id},
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success  : function( results ) {
                $('#fitur_content').html(results);

            },
            complete : function(){
                Metronic.unblockUI();
            }        
        });  
    }
  
    var addFieldsetParent = function(form,data)
    {
        var 
            $section           = form.section,
            $fieldsetContainer = $('ul', $section),
            counter            = form.counter(),
            $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer),
            fields             = form.fields,
            prefix             = form.fieldPrefix
        ;

        if(Object.keys(data).length>0){
            for (var i=0; i<fields.length; i++){
                // format: name="emails[_ID_1][subject]"
                $('*[name="' + prefix + '[' + counter + '][' + fields[i] + ']"]', $newFieldset).val( data[fields[i]] );
                $('a.del-this', $newFieldset).attr('data-id',data[fields[0]]);
                
            }       
        }

        $('a.del-this', $newFieldset).on('click', function(){
            var id = $(this).data('id');
        
            handleDeleteFieldset($(this).parents('.fieldset').eq(0), id);
        });

        //jelasin warna hr pemisah antar fieldset
        $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
    };

    var handleDeleteFieldset = function($fieldset, id)
    {        
        var 
            $parentUl     = $fieldset.parent(),
            fieldsetCount = $('.fieldset', $parentUl).length,
            hasId         = false,
            cabang_id = $('select#cabang_id').val()//punya id tidak, jika tidak bearti data baru,           
            ; 

        if(id != undefined)
        {
            var i = 0;
            bootbox.confirm('Anda yakin akan menghapus menu utama ini?', function(result) {
                if (result==true) {
                    i = parseInt(i) + 1;
                    if(i == 1)
                    {
                        $.ajax({
                            type     : 'POST',
                            url      : baseAppUrl + 'delete_menu',
                            data     : {id:id, cabang_id:cabang_id},
                            dataType : 'json',
                            beforeSend : function(){
                                Metronic.blockUI({boxed: true });
                            },
                            success  : function( results ) {
                                if(results.success == true)
                                {
                                    $fieldset.remove();                                     
                                }
                            },
                            complete : function(){
                                Metronic.unblockUI();
                            }
                        });        
                    }
                }
            });
        }
        else
        {
            if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
            $fieldset.remove();            
        }

    };



    var handleConfirmSave = function() {
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var i = 0;
            var msg = $(this).data('confirm');
            var proses = $(this).data('proses');
            bootbox.confirm(msg, function(result) {
                Metronic.blockUI({boxed: true, message: proses});
                if (result==true) {
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                        $.ajax({
                            type     : 'POST',
                            url      : baseAppUrl + 'save_menu_parent',
                            data     : $form.serialize(),
                            dataType : 'json',
                            beforeSend : function(){
                                Metronic.blockUI({boxed: true });
                            },
                            success  : function( results ) {
                               if(results.success === true)
                               {
                                    mb.showMessage('success',results.msg,'Sukses');
                                    $('a#confirm_save', $form).removeAttr('disabled');
                               }
                               else
                               {
                                    mb.showMessage('error',results.msg,'Gagal');
                               }
                            },
                            complete : function(){
                                Metronic.unblockUI();
                            }
                        });               
                    }
                }
            });
        });
    };

    var handleChangeCabang = function()
    {
        $('select#cabang_id').on('change', function(){
            var cabang_id = $(this).val();
            var level_id = $('input[name="id"]').val();
            var $selectParent = $('select#menu_parent_id');

            $.each(formsParent, function(idx, form){
                var $section           = form.section,
                    $fieldsetContainer = $('ul', $section);

                $.ajax({
                    type     : 'POST',
                    url      : form.urlData(),
                    data     : {cabang_id: cabang_id, parent_id: 0, level_id:level_id},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        if (results.success === true) {
                            var rows = results.rows;

                            $fieldsetContainer.html('');
                            $selectParent.empty();

                            $selectParent.html($("<option></option>").attr("value", '0').text('Utama'));
                            
                            $.each(rows, function(idx, data){
                                addFieldsetParent(form, data);
                                $selectParent.append($("<option></option>").attr("value", data.id).text(data.nama));
                            });
                        }
                        else
                        {
                            $selectParent.empty();
                            $selectParent.html($("<option></option>").attr("value", '0').text('Utama'));

                            $fieldsetContainer.html('');
                            addFieldsetParent(form,{});
                        }
                    },
                    complete : function(){
                        handleTableFitur();
                        handleTabelMenu();
                        Metronic.unblockUI();
                    }
                });

                // handle button add
                $('a.add-parent', form.section).on('click', function(){
                    addFieldsetParent(form,{});
                });
        
            });
            $('a#confirm_save', $form).removeAttr('disabled');   
        });
    };

    var handleButtonRefresh = function()
    {
        $('a#refresh').click(function(){
            var cabang_id = $('select#cabang_id').val();       
            var level_id = $('input[name="id"]').val();
            var $selectParent = $('select#menu_parent_id');

            $.each(formsParent, function(idx, form){
                var $section           = form.section,
                    $fieldsetContainer = $('ul', $section);

                $.ajax({
                    type     : 'POST',
                    url      : form.urlData(),
                    data     : {cabang_id: cabang_id, parent_id: 0,level_id:level_id},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        if (results.success === true) {
                            var rows = results.rows;
                            $fieldsetContainer.html('');
                            $selectParent.empty();

                            $selectParent.html($("<option></option>").attr("value", '0').text('Utama'));
                            
                            $.each(rows, function(idx, data){
                                addFieldsetParent(form, data);
                                $selectParent.append($("<option></option>").attr("value", data.id).text(data.nama));
                            });
                        }
                        else
                        {
                            $selectParent.empty();
                            $selectParent.html($("<option></option>").attr("value", '0').text('Utama'));

                            $fieldsetContainer.html('');
                            addFieldsetParent(form,{});
                        }

                    },
                    complete : function(){
                        handleTableFitur();
                        handleTabelMenu();
                        Metronic.unblockUI();
                    }
                });

                // handle button add
                $('a.add-parent', form.section).on('click', function(){
                    addFieldsetParent(form,{});
                });
                 
            });  
        });
    }

    var handleChangeCabangFitur = function()
    {
        $('select#fitur_cabang_id').on('change', function(){
            handleTableFitur();
        });
    }

    var handleTabelMenu = function()
    {
        var menu_parent_id = $('select#menu_parent_id').val();
        var cabang_id = $('select#cabang_id').val(); 
        var level_id = $('input[name="id"]').val();
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'listing_menu',
            data     : {cabang_id:cabang_id,menu_parent_id: menu_parent_id,level_id:level_id},
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success  : function( results ) {
                $('#menu_content').html(results);
                handleButtonMove();

            },
            complete : function(){
                Metronic.unblockUI();
            }        
        });  
    }

    var handleChangeParent = function()
    {
        $('select#menu_parent_id').on('change', function(){
            var menu_parent_id = $(this).val();
            var cabang_id = $('select#cabang_id').val(); 
            var level_id = $('input[name="id"]').val();
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'listing_menu',
                data     : {cabang_id:cabang_id,menu_parent_id: menu_parent_id,level_id:level_id},
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    $('#menu_content').html(results);
                    handleButtonMove();

                },
                complete : function(){
                    Metronic.unblockUI();
                }        
            });  

        });
    };

    var handleButtonMove = function()
    {
        $table = $('#menu_content');
        $('a[data-action="move_order"]', $table).click(function(){
            var parent_id = $(this).data('parent_id');
            var id = $(this).data('id');
            var command = $(this).data('command');
            var order = $(this).data('order');
            
            changeOrder(parent_id,id,order,command);
            //cegah hiperlink default action 
            event.preventDefault();
        });

        $('a[name="delete[]"]', $table).click(function(){
            var parent_id = $(this).data('parent_id');
            var id = $(this).data('id');
            var confirm = $(this).data('confirm');
            
            deleteMenu(id,parent_id,confirm);
            //cegah hiperlink default action 
            event.preventDefault();
        });
    }

    var changeOrder = function(parent_id,id,order,command){
        var cabang_id = $('select#cabang_id').val(); 
        var level_id = $('input[name="id"]').val();

        $.ajax({
            url: baseAppUrl + "change_order",
            type : 'POST',
            data : {cabang_id:cabang_id,level_id:level_id,parent_id:parent_id,id:id,order:order,command:command},

            success  : function( results ) {
                handleTabelMenu();
            } 
        });     
    };

    var deleteMenu = function(id,parent_id,confirm){

        var i=0;
        var cabang_id = $('select#cabang_id').val();

        bootbox.confirm(confirm, function(result) {
            if (result==true) {
                i = parseInt(i) + 1;
                if(i == 1)
                {
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'delete_menu',
                        data     : {id:id, cabang_id:cabang_id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            if(results.success == true)
                            {
                                 handleTabelMenu();  
                                 $('a#refresh').click();  

                            }
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });        
                }
            }
        });
    }
  
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/user_level/';
        initForm();
        handleConfirmSave();
        handleChangeCabang();
        handleChangeCabangFitur();
        handleButtonRefresh();
        handleTabelMenu();
        handleChangeParent();
    };

 }(mb.app.user_level.menu));


// initialize  mb.app.home.table
$(function(){
    mb.app.user_level.menu.init();
});