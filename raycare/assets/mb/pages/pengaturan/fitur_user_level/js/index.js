mb.app.fitur = mb.app.fitur || {};

// mb.app.users.add namespace
(function(o){

    var baseAppUrl = '';
        $tableFitur = $('#table_fitur');
    //untuk dropdown pemilihan bahasa agar muncul bendera setiap negara
	function formatFitur(state) {
	    if (!state.id) return state.text; // optgroup
	    return state.text;
	}

    var handleDatatable = function()
    {
        oTable = $tableFitur.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
    }

    var handleDropdownFitur = function()
    {
        $('#fitur_tombol_id').select2({
            placeholder: 'Pilih Fitur',
            allowClear: true,
            formatResult: formatFitur,
            formatSelection: formatFitur,
            escapeMarkup: function (m) {
                return m;
            }
        });

        $('#fitur_tombol_id').on('change', function(){
            var page = $(this).val();
            // alert(page);
            oTable.api().ajax.url(baseAppUrl + 'listing/'+ page).load();
        });
    }


	

    o.init = function(){
        baseAppUrl = mb.baseUrl()+'pengaturan/fitur_user_level/';
        handleDatatable();
        handleDropdownFitur();    
    };

}(mb.app.fitur));


// initialize  mb.app.users.add
$(function(){
	mb.app.fitur.init();
});