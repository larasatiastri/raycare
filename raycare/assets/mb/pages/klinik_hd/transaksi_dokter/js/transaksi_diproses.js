mb.app.cabang = mb.app.cabang || {};
(function(o){

    var 
      baseAppUrl          = '',
      timestamp           = 0,
      noerror             = true;
 

      var handleTransaksiDiproses = function(){
     
        oTableTransaksiDiproses=$("#table_cabang2").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            "scrollX"               : "100%",
            "scrollCollapse"        : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_transaksi_diproses',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'stateSave'             :true,
            'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.no_transaksi no_transaksi',' visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.tanggal_lahir tanggal_lahir','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien_alamat.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'user.nama nama_dokter','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd.status status','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 
                ],       
      });
      new $.fn.dataTable.FixedColumns( oTableTransaksiDiproses );

      $("#table_cabang2").on('draw.dt', function (){
          $('.search-item', this).tooltip();
            $('a[name="return[]"]', this).click(function(e){
                var id = $(this).data('id'),
                    confirm = $(this).data('confirm');

                    handleDeleteTransaksi(id,confirm);

             });      
        });
         
     
    };

    var handleVisit = function(){
     
      oTableVisit=$("#table_visit").dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_visit',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
             'stateSave'             :true,
              'pagingType'            :'full_numbers',
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name':'tindakan_hd.no_transaksi no_transaksi',' visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'user.nama nama_dokter','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd_visit.start_visit start_visit','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd_visit.end_visit end_visit','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_hd_visit.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                 
                ],
             
        });

      $("#table_visit").on('draw.dt', function (){
          
        });
         
     
    };

  var handleDeleteRow = function(id,msg,type){

		  bootbox.confirm(msg, function(result) {
			if(result==true) {
				//location.href = baseAppUrl + 'delete/' +id;

				$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "deleteajax",  
                                data:  {id:id,type:type},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     // $('#accept', $form).click();

                                   mb.showMessage(data[0],data[1],data[2]);
                                   oTable.api().ajax.reload();
                                // oTable.ajax.url(baseAppUrl + 'listing').load();
                                 //$tableCabang.dataTable.ajax.reload();
        						 //  oTable.fnReloadAjax();
                                    //location.href = mb.baseUrl() + 'approval/finance2/';
                                }
                   
                       });
                        // oTable.ajax.url(baseAppUrl + 'listing').load();
                        
			} 
		});
	
	};

  var handleDeleteRowPendaftaran = function(id,msg){

      bootbox.confirm(msg, function(result) {
        if(result==true) {
          location.href = baseAppUrl +'cancel_tindakan/'+id;
        }
      });  
  };

  var handleDeleteTransaksi = function(id,msg){

      bootbox.confirm(msg, function(result) {
        if(result==true) {
          location.href = baseAppUrl +'kembalikan_tindakan/'+id;
        }
      });  
  };


    // Kebutuan Untuk Denah | Created By Abu
     
    var handleClickReload = function(){
        $('.btn').tooltip();

        $('a#refresh').click(function(){
            handleLoadDenah();
        });

        $('a.btn-reload-antrian').click(function(){
           oTable.api().ajax.reload();
           oTableTransaksiDiproses.api().ajax.reload();
        });

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
            url: baseAppUrl + "show_denah_lantai_html",  
            data:  {lantai: 1},  
            dataType : "text",
            beforeSend : function(){
                Metronic.blockUI({boxed: true,target:"#lantai1"});
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_1").html(data);
            },
            complete : function() {
                Metronic.unblockUI("#lantai1");
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
                Metronic.blockUI({boxed: true, target:"#lantai2"});
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_2").html(data);
            },
            complete : function() {
                Metronic.unblockUI("#lantai2");
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
                Metronic.blockUI({boxed: true, target:"#lantai3"});
            },
            success:function(data)         
            { 
                $("div.svg_file_lantai_3").html(data);
            },
            complete : function() {
                Metronic.unblockUI("#lantai3");
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
             // handleLoadDenah();
              $('a.btn-reload-antrian').click();
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

   
    o.init = function(){
    	 
        baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
        handleTransaksiDiproses();
       // handleCommet();
        handleVisit();

        // Kebutuhan Denah | Created by Abu
        handleLoadDenah();
        handleClickReload();

    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});