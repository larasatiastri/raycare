//mb namespace - common function
var mb = mb || {};

// mb page specific namespace
mb.app = mb.app || {};

// mb common function
(function(o){

    // add class 'active' to active sidebar menu
    // note: auto active saat generate menu di helper generate_user_menus
    /*  
    var activeSbMenu = function (menusId) {
        for (var i=0; i<menusId.length; i++){
            $('#menu_id_'+menusId[i]).addClass('active');
        }
    };
    */

    var timestamp = 0,
        noerror       = true,
        _baseUrl      = null,
        _baseDir      = null,
        _loginUrl      = null,
        _timeout      = 0,

    // untuk menampilkan notif8 di semua page master

    showMessage = function (type,msg,title){

        if (type === '' || msg === '' || title ==='') return;

        var 
        msgColor = {'success': 'lime', 'error': 'ruby', 'warning': 'lemon'},
        settings = {            
            theme          : msgColor[type],
            sticky         : false,
            horizontalEdge : 'bottom',
            verticalEdge   : 'right'
        };

        $button = $(this);
        
        if ($.trim(title) != '') settings.heading = title;
        
        if (!settings.sticky) settings.life = 10000;

        $.notific8('zindex', 11500);
        $.notific8($.trim(msg), settings);
        
        $button.attr('disabled', 'disabled');
        
        setTimeout(function() {
            $button.removeAttr('disabled');
        }, 1000);

    },

    showToast = function (type, msg, title) {
        if (type === '' || msg === '' || title ==='') return;

        var 
            msgColor = {'success': 'success', 'error': 'error', 'warning': 'warning'};

        var color = msgColor[type];

        toastr.options = {
            closeButton: false,
            debug: true,
            positionClass: 'toast-top-left',
            onclick: null,
            showDuration: 1000,
            hideDuration: 1000,
            timeOut: 10000,
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut',
        };

        toastr[color](msg, title);
    },

    // set and get DataTable language
    _DTLang = {},
    DTLanguage = function (langData) {
        if (langData !== undefined)  {
            _DTLang = langData;
        } else {
            return _DTLang;
        }
    },
    
    // set and get baseUrl
    _baseUrl = null,
    baseUrl = function (url) {
        if (url !== undefined)  {
            _baseUrl = url;
        } else {
            return _baseUrl;
        }
    },

    _baseDir = null,
    baseDir = function (url) {
        if (url !== undefined)  {
            _baseDir = url;
        } else {
            return _baseDir;
        }
    },

    _loginUrl = null,
    loginUrl = function (url) {
        if (url !== undefined)  {
            _loginUrl = url;
        } else {
            return _loginUrl;
        }
    },

    _timeout = 0,
    timeout = function(time){
        if (time !== undefined)  {
            _timeout = parseInt(time);
        } else {
            return _timeout;
        }
    },

    handleCommet = function() {
        
        $.ajax({
            type     : 'POST',
            url      : _baseUrl+'home/commet',
            data     : { timestamp : timestamp },
            // dataType : 'json',
            success  : function( transport ) {
                var response = $.parseJSON(transport);
                timestamp = response.timestamp;
                handleResponse(response);
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

    },

    handleResponse = function(response){
        if(response.msg != '')
        {
            $.ajax({
                type     : 'POST',
                url      : _baseUrl+'home/get_notif',
               
                // dataType : 'json',
                success  : function( results ) {
                    var response = $.parseJSON(results);
                    if(response.success === true)
                    {
                        $('li#header_notification_bar').removeClass('hidden');
                        $('span#notif_count').addClass('badge badge-default') ;   
                        $('span#notif_count').text(response.count) ; 
                        $('h3#notif_title').text(response.title)  ;

                        var rows = response.rows;
                        
                        addFieldset(response.count,rows);
                    }
                    else
                    {
                        deleteFieldset();
                    }
                    
                         
                },
                complete : function (results) {
                   
                }
            });
        }
    },

    addFieldset = function(count,data){
        // alert(count);
        var 
            $fieldsetContainer = $('ul#notif_content'),            
            html ='';

        if(Object.keys(data).length>0){
            for (var i=0; i<count; i++){
                html +='<li class="list_content" data-id="'+data[i]['notifikasi_id']+'"><a class="link_content" data-href="'+data[i]['notifikasi_url']+'" ><span class="time">'+data[i]['created_date']+'</span><span class="details"> <span class="label label-sm label-icon label-success"> <i class="fa fa-plus"></i> </span> '+data[i]['notifikasi_text']+'</span></a></li>';
            }

           $fieldsetContainer.html(html);
            // alert(Object.keys(data).length);
        }
        playSound('hangouts_message');

        handleSelectNotif();
    },

    playSound = function(filename){   
        document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' +_baseUrl+'assets/mb/sound/'+ filename + '.ogg" type="audio/ogg" /><source src="' +_baseUrl+'assets/mb/sound/'+ filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="'+_baseUrl+'assets/mb/sound/'+ filename +'.ogg" /></audio>';
    },

    deleteFieldset = function()
    {
        var $fieldsetContainer = $('ul#notif_content');

        $fieldsetContainer.html('');
    },

    handleSelectNotif = function(){
        var $fieldsetContainer = $('ul#notif_content');

        $('li.list_content',$fieldsetContainer).click(function(){
            var id = $(this).data('id');
            var link = $('a.link_content', $fieldsetContainer).data('href');

            $.ajax({
                type     : 'POST',
                url      : _baseUrl+'home/delete_notif',
                data     : {id:id},
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success  : function( results ) {
                    var response = $.parseJSON(results);
                    if(response.success === true)
                    {
                        Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                        location.href = link;
                    }
                    
                         
                },
                complete : function (results) {
                    Metronic.unblockUI();
                }
            });
        });
    },

    handleSession = function() {
        
        $.ajax({
            url      : _baseUrl+'home/getSessionTimeLeft',
            dataType : 'json',
            success  : function( result ) {

               if(result == 0)
               {
                    $('a#session').click();
               }
                       
            }
        });

    },

    handleAssetPath = function() {
        Metronic.setAssetsPath(_baseUrl+'assets/metronic/');
    },

    showClock = function()
    {
        setInterval(function(){
            handleSession();
        },1000);
    },


   formatRp = function(num){
        return 'Rp. ' + num.format(2, 3, '.', ',') + ',-';
    },

    formatTanpaRp = function(num){
        return num.format(2, 3, '.', ',');
    };


    o.init = function(){
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        Demo.init(); // init current layout
        /**
         * http://stackoverflow.com/questions/149055/how-can-i-format-numbers-as-money-in-javascript
         * Number.prototype.format(n, x, s, c)
         * 
         * @param integer n: length of decimal
         * @param integer x: length of whole part
         * @param mixed   s: sections delimiter
         * @param mixed   c: decimal delimiter
         * example :
         * 12345678.9.format(2, 3, '.', ',');  // "12.345.678,90"
         * 123456.789.format(4, 4, ' ', ':');  // "12 3456:7890"
         * 12345678.9.format(0, 3, '-');       // "12-345-679"
         */
        Number.prototype.format = function(n, x, s, c) {
            var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
                num = this.toFixed(Math.max(0, ~~n));

            return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
        };          
    }
    o.baseUrl         = baseUrl;
    o.baseDir         = baseDir;
    o.loginUrl        = loginUrl;
    o.timeout         = timeout;
    o.showMessage     = showMessage;
    o.showToast       = showToast;
    o.DTLanguage      = DTLanguage;
    o.handleCommet    = handleCommet;
    o.handleResponse  = handleResponse;
    o.handleSession   = handleSession;
    o.handleAssetPath = handleAssetPath;
    o.showClock       = showClock;
    o.formatRp        = formatRp;
    o.formatTanpaRp   = formatTanpaRp;

    
}(mb));

// initialize  mb
$(function(){
    mb.init();
});