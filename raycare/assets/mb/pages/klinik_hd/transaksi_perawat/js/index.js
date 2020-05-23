mb.app.spesialis = mb.app.spesialis || {};
(function(o){

    var 
        baseAppUrl      = '',
        $lastPopoverBed = null,
        timestamp       = 0,
        noerror         = true;


    var initForm = function(){

        handleLantai2();
        handleLantai3();
        handleLantai4();
    }

    var handleLantai2 = function()
    {
        // Load pertamakali tampilan pertama Lantai 2
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html",  
            data:  {lantai: 1},  
            dataType : "text",
            beforeSend : function(){
                Metronic.blockUI({boxed: true, target: '#lantai1'});
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_1").html(data);
            },
            complete : function() {
                Metronic.unblockUI('#lantai1');
            }
        });
    }

    var handleLantai3 = function()
    {
        // Load pertamakali tampilan pertama Lantai 3
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html",  
            data:  {lantai: 2},  
            dataType : "text",
            beforeSend : function(){
                Metronic.blockUI({boxed: true, target : '#lantai2'});
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_2").html(data);
            },
            complete : function() {
                Metronic.unblockUI('#lantai2');
            }
        });
    }

    var handleLantai4 = function()
    {
        // Load pertamakali tampilan pertama Lantai 3
        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "show_denah_lantai_html",  
            data:  {lantai: 3},  
            dataType : "text",
            beforeSend : function(){
                Metronic.blockUI({boxed: true, target : '#lantai3'});
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_3").html(data);
            },
            complete : function() {
                Metronic.unblockUI('#lantai3');
            }
        });
    }


    var handleKlikBed = function(){
    	
		var $bed = $('polygon');

        $.each($bed, function(idx, colBed){
            var
                $colBed = $(colBed);

            // console.log($colBed);
            $colBed.popover({
                html : true,
                container : 'body',
                placement : 'top',
                content: function(){
                	// return $('#popover-content').html();

                	// return '<script> $(document).ready(function() {$("a#proses").click(function() {alert("a"); }); });</script>';
                    // return '<a style="margin-bottom:5px;"  id="proses" class="btn btn-primary"><i class="fa fa-gears"></i> Proses</a>'
	                   //    + '<br><a href="" style="margin-bottom:5px;" class="btn btn-primary"><i class="fa fa-arrows"></i> Pindah Bed</a>'
	                   //    + '<br><a href="" class="btn btn-primary"><i class="fa fa-times"></i> Tolak</a>'
	                   //    + '<script> $(document).ready(function() {$("a#proses").click(function() {alert("a"); }); });</script>';
                }
            }).on("show.bs.popover", function(){
                $(this).data("bs.popover").tip().addClass('popover_menu');
                $(this).data("bs.popover").tip().css({minWidth: '100px', maxWidth: '300px', margin: '26px 0 0 40px', left : '85.808815px'});
                if ($lastPopoverBed !== null) $lastPopoverBed.popover('hide');
                $lastPopoverBed = $colBed;
            }).on('hide.bs.popover', function(){
                $lastPopoverBed = null;
            }).on('click', function(e){

            });
        });
    }

    var handleTabKlik = function(){
        $('li.tab').each(function( index ) {
            var i = index+1;
            $('a#lantai_'+ i).on('click', function(){
                // alert('hei'+i);
                
            });
        });
    }

    var handleModalTolak = function(){
        $('#tolak_ok').on('click', function(){

            var id = $('#id_bed').val(),
                ket = $('#keterangan').val();

            $.ajax ({ 
                type: "POST",
                url: "transaksi_perawat/tolak_bed",  
                data:  {id: id, keterangan: ket},  
                dataType : "json",
                success:function(data)         
                { 
                    window.location.reload();
                    // mb.showMessage(data[0],data[1],data[2]);
                     // $(".svg_file").html(data);
                }

            });            
        });
    }

    var handleModalPindah = function(){
        $('#pindah_ok').on('click', function(){

            var bed_asal = $('#id_bed_pindah').val(),
                bed_tujuan = $('#bed_tujuan').val();

            $.ajax ({ 
                type: "POST",
                url: "transaksi_perawat/pindah_bed",  
                data:  {bed_asal: bed_asal, bed_tujuan: bed_tujuan},  
                dataType : "json",
                success:function(data)         
                { 
                    window.location.reload();
                    // mb.showMessage(data[0],data[1],data[2]);

                     // $(".svg_file").html(data);
                }

            });            
        });
    }

    var handleLantai = function(){
        $('#lantai').on('change', function(){
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_lantai',
                data     : {lantai: $(this).val()}, 
                dataType : 'json',
                success  : function( results ) {
                    
                    $('#bed_tujuan').empty();
                    $('#bed_tujuan').append($("<option></option>").attr("value", "").text("Pilih Bed.."));

                    $.each(results, function(key, value) {
                        $('#bed_tujuan').append($("<option></option>").attr("value", value.id).text('['+ value.kode +'] '+ value.nama));
                        $('#bed_tujuan').val('');

                    });                    
                }
            });  
        });
    }

    var handleRefresh = function(){
        $('a#refresh').on('click', function(){
            initForm();
        })
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
                handleResponse();
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

    var handleResponse = function(){
    
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl+'get_notif_bed',
           
            // dataType : 'json',
            success  : function( results ) {
                var response = $.parseJSON(results);
                if(response.success === true)
                {
                    // var lantai = '';
                    $('span#notifbed', $('a#lantai_1')).addClass('hidden');
                    $('span#notifbed', $('a#lantai_2')).addClass('hidden');
                    $('span#notifbed', $('a#lantai_3')).addClass('hidden');
                    $.each(response.rows, function(idx,value){
                        
                        $('span#notifbed', $('a#lantai_'+value.lantai_id)).removeClass('hidden'); 
                    
                    });
                }
                else
                {
                    $('span#notifbed', $('a#lantai_1')).addClass('hidden');
                    $('span#notifbed', $('a#lantai_2')).addClass('hidden');
                    $('span#notifbed', $('a#lantai_3')).addClass('hidden');
                }
                
                     
            },
            complete : function (results) {
                initForm();

            }
        });
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';
        initForm();
        handleRefresh();
        handleCommet();
        handleResponse();
        // handleKlikBed();
        // handleModalTolak();
        // handleLantai();
        // handleModalPindah();
        // handleTabKlik();
    };
 }(mb.app.spesialis));


// initialize  mb.app.home.table
$(function(){
    mb.app.spesialis.init();
});