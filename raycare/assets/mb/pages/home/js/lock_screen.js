mb.app.home = mb.app.home || {};
mb.app.home.lock_screen = mb.app.home.lock_screen || {};

// mb.app.home.lock_screen namespace
(function(o){

    var 
        baseAppUrl              = '';
      

    var handleBackGround = function(){

        $.backstretch([
                mb.baseUrl()+"assets/metronic/admin/pages/media/bg/1.jpg",
                mb.baseUrl()+"assets/metronic/admin/pages/media/bg/2.jpg",
                mb.baseUrl()+"assets/metronic/admin/pages/media/bg/3.jpg",
                mb.baseUrl()+"assets/metronic/admin/pages/media/bg/4.jpg"
                ], {
                  fade: 1000,
                  duration: 8000
              });

    };  

    // mb.app.home.lock_screen properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/home/';
        handleBackGround();
        // handleDocument();
    };

}(mb.app.home.lock_screen));


// initialize  mb.app.home.lock_screen
$(function(){
    mb.app.home.lock_screen.init();
});