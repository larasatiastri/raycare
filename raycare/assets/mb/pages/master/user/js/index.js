mb.app.user = mb.app.user || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableUser = $('#table_user');

    var handleDataTable = function() 
    {
    	$tableUser.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'				: true,
			'pagingType'			: 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[2, 'asc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		],
        	'aoColumnDefs': [{
				'aTargets': [5],
				'mRender': function (data, type, full) {
					var user_id  = full[0],
						active   = data,
						checked  = active==1 ? 'checked' : '',
						checkbox = '<center><input type="checkbox" name="status[]" ' + checked + ' data-user_id="' + user_id+ '"'+ ' value="' + active + '"></center>';
					
					return checkbox;					
				}


			 }]
        });
        $tableUser.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('input[name="status[]"]', this).click(function(){
				var $checkbox = $(this),
				    userId    = $checkbox.data('user_id');

				setUserStatus(userId, $checkbox);
			});	

			$('a[data-action="passreset"]', this).click(function(){
				var userId = $(this).data('user_id');
				passReset(userId);
				//cegah hiperlink default action 
				event.preventDefault();
			});

			$('input[type=checkbox]',this).uniform();

		} );
    }

    var setUserStatus = function(userId, $checkbox) {

		var checked = $checkbox.prop('checked'),
			status  = checked ? 1 : 0,
			msg     = checked ? 'Do you want to enable this user?' :
			          'Do you want to disable this user?';
		    
		bootbox.confirm(msg, function(result) {
			if(result==true) {
				$.blockUI();
				$.post(baseAppUrl + 'setStatus', {id: userId, status: status}, function(data){
					mb.showMessage('success', data, 'User Status');
				});
				$.unblockUI();
			} else {
				// bootbox dicancel, kembalikan checkbox checked ke keadaan sebelum di klik
				$checkbox.prop('checked', !checked);
			}
		});
	};

	var passReset = function(userId){
		// console.log('what\'s up doc...??');
		bootbox.confirm('Are you sure you want to reset this user password?', function(result) {
			if (result==true) {
				$.blockUI();
				$.post(baseAppUrl + 'passreset', {id: userId}, function(data){
					bootbox.alert(data);
				});
				$.unblockUI();
			}
		});
	};

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/user/';
        handleDataTable();
    };
 }(mb.app.user));


// initialize  mb.app.home.table
$(function(){
    mb.app.user.init();
});