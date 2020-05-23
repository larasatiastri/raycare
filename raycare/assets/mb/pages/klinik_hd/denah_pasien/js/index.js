mb.app.denah_tindakan = mb.app.denah_tindakan || {};

(function(o){

    var 
        baseAppUrl               = '',
        timestamp       = 0,
        noerror         = true;


    var handleClickReload = function(){
        $('.btn').tooltip();

        $('a#refresh').click(function(){
            handleLoadDenah();
        })
    }

    var handleLoadDenah = function() 
    {
        handleLantai2();
        handleLantai3();
        handleLantai4();
    }
    
    var handleLantai2 = function()
    {
        // Load pertamakali tampilan pertama Lantai 2
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html_create",  
            data:  {lantai: 2, shift:$('input#shift').val()},  
            dataType : "text",
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_2").html(data);
            },
            complete : function() {
                Metronic.unblockUI();
            }
        });
    }

    var handleLantai3 = function()
    {
        // Load pertamakali tampilan pertama Lantai 3
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html_create",  
            data:  {lantai: 3, shift:$('input#shift').val()},  
            dataType : "text",
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_3").html(data);
            },
            complete : function() {
                Metronic.unblockUI();
            }
        });
    }

    var handleLantai4 = function()
    {
        // Load pertamakali tampilan pertama Lantai 3
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html_create",  
            data:  {lantai: 4, shift:$('input#shift').val()},  
            dataType : "text",
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_4").html(data);
            },
            complete : function() {
                Metronic.unblockUI();
            }
        });
    }

    var handleCommet = function() {
        
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl +'commet_bed',
            data     : { timestamp : timestamp },
            // dataType : 'json',
            success  : function( transport ) {
                var response = $.parseJSON(transport);
                timestamp = response.timestamp;
                handleLoadDenah();
                noerror = true;
                       
            },
            complete : function (transport) {
                if (!noerror)
                {
                  // if a connection problem occurs, try to reconnect each 5 seconds
                  setTimeout(function(){ handleCommet() }, 5000); 
                }
                else
                  handleCommet();
                
                noerror = false;
            }
        });

    }


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/denah_tindakan/';
        handleLoadDenah();
        handleClickReload();
        handleCommet();
    };
 }(mb.app.denah_tindakan));


// initialize  mb.app.home.table
$(function(){
    mb.app.denah_tindakan.init();
});