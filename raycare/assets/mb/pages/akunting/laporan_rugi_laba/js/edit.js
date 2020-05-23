mb.app.laporan_rugi_laba = mb.app.laporan_rugi_laba || {};
mb.app.laporan_rugi_laba.edit = mb.app.laporan_rugi_laba.edit || {};


(function(o){
    
     var 
        baseAppUrl          = '',
        $form               = $('#form_edit_laporan_rugi_laba');

    var initForm = function(){ 
        handleValidation();
        handleDatePickers();
        handleConfirmSave();

        $('input[name$="[nominal_pendapatan]"]').on('change keyup', function(){

            handleCountPendapatan();
            handleCountHPP();
            handleCountBeban();
            handleCountPendapatanLain();
            handleCountBebanLain();
        });

        $('input[name$="[nominal_hpp]"]').on('change keyup', function(){

            handleCountHPP();
        });

        $('input[name$="[nominal_beban]"]').on('change keyup', function(){

            handleCountBeban();
        });

        $('input[name$="[nominal_pendapatan_lain]"]').on('change keyup', function(){

            handleCountPendapatanLain();
        });

        $('input[name$="[nominal_beban_lain]"]').on('change keyup', function(){

            handleCountBebanLain();
        });

    };

    var handleCountPendapatan = function(){

        var $rows = $('input.akun_pendapatan', $form),
            total_pendapatan = 0;

        $.each($rows, function(idx, pendapatan){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index');

            total_pendapatan = total_pendapatan + nominal;

            $('input#total_pendapatan').val(total_pendapatan);
            $('input#total_pendapatan').attr('value',total_pendapatan);
            $('span#total_pendapatan').text(mb.formatRp(total_pendapatan));

            

        });

        $.each($rows, function(idx, pendapatan){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index'),
                prosentase = 0;

            prosentase = (nominal / total_pendapatan) * 100;
            // console.log(nominal);
            $('input#akun_pendapatan_prosentase_'+baris).val(prosentase);
            $('span#akun_pendapatan_prosentase_'+baris).text(formatKoma(prosentase));

        });

        var pendapatan_total = parseFloat($('input#total_pendapatan').val()),
            hpp_total = parseFloat($('input#total_hpp').val());

        prosetase_total = (total_pendapatan / pendapatan_total) * 100;

        $('input#prosentase_total_pendapatan').val(prosetase_total);
        $('input#prosentase_total_pendapatan').attr('value',prosetase_total);
        $('span#prosentase_total_pendapatan').text(formatKoma(prosetase_total));

        var laba_kotor = pendapatan_total - hpp_total;
        $('input#laba_kotor').val(laba_kotor);
        $('input#laba_kotor').attr('value',laba_kotor);
        $('span#laba_kotor').text(mb.formatRp(laba_kotor));

        prosentase_labkot = (laba_kotor / pendapatan_total) * 100;

        $('input#prosentase_laba_kotor').val(prosentase_labkot);
        $('input#prosentase_laba_kotor').attr('value',prosentase_labkot);
        $('span#prosentase_laba_kotor').text(formatKoma(prosentase_labkot));

    }

    var handleCountHPP = function(){

        var $rowhpp = $('input.akun_hpp', $form),
            total_hpp = 0;

        $.each($rowhpp, function(idx, hpp){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index');

            total_hpp = total_hpp + nominal;

            $('input#total_hpp').val(total_hpp);
            $('input#total_hpp').attr('value',total_hpp);
            $('span#total_hpp').text(mb.formatRp(total_hpp));

        });

        $.each($rowhpp, function(idx, hpp){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index'),
                prosentase = 0,
                pendapatan_total = parseFloat($('input#total_pendapatan').val());

            prosentase = (nominal / pendapatan_total) * 100;
            // console.log(nominal);
            $('input#akun_hpp_prosentase_'+baris).val(prosentase);
            $('span#akun_hpp_prosentase_'+baris).text(formatKoma(prosentase));

        });

        var pendapatan_total = parseFloat($('input#total_pendapatan').val()),
            hpp_total = parseFloat($('input#total_hpp').val());

        prosetase_total = (total_hpp / pendapatan_total) * 100;

        $('input#prosentase_total_hpp').val(prosetase_total);
        $('input#prosentase_total_hpp').attr('value',prosetase_total);
        $('span#prosentase_total_hpp').text(formatKoma(prosetase_total));

        var laba_kotor = pendapatan_total - hpp_total;
        $('input#laba_kotor').val(laba_kotor);
        $('input#laba_kotor').attr('value',laba_kotor);
        $('span#laba_kotor').text(mb.formatRp(laba_kotor));

        prosentase_labkot = (laba_kotor / pendapatan_total) * 100;

        $('input#prosentase_laba_kotor').val(prosentase_labkot);
        $('input#prosentase_laba_kotor').attr('value',prosentase_labkot);
        $('span#prosentase_laba_kotor').text(formatKoma(prosentase_labkot));

    }
   

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            // alert('klik');
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save', $form).click();
                }
            });
        });
    };

 
    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleCountBeban = function(){

        var $rowBeban = $('input.akun_beban', $form),
            total_beban_operasional = 0;

        $.each($rowBeban, function(idx, pendapatan){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index');

            total_beban_operasional = total_beban_operasional + nominal;

            $('input#total_beban_operasional').val(total_beban_operasional);
            $('input#total_beban_operasional').attr('value',total_beban_operasional);
            $('span#total_beban_operasional').text(mb.formatRp(total_beban_operasional));
        });

        $.each($rowBeban, function(idx, pendapatan){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index'),
                prosentase = 0,
                pendapatan_total = parseFloat($('input#total_pendapatan').val());

            prosentase = (nominal / pendapatan_total) * 100;
            // console.log(nominal);
            $('input#akun_beban_prosentase_'+baris).val(prosentase);
            $('span#akun_beban_prosentase_'+baris).text(formatKoma(prosentase));

        });

        var pendapatan_total = parseFloat($('input#total_pendapatan').val()),
            total_beban_operasional = parseFloat($('input#total_beban_operasional').val());

        prosetase_total = (total_beban_operasional / pendapatan_total) * 100;

        $('input#prosentase_total_beban_operasional').val(prosetase_total);
        $('input#prosentase_total_beban_operasional').attr('value',prosetase_total);
        $('span#prosentase_total_beban_operasional').text(formatKoma(prosetase_total));

        var pendapatan_total = parseFloat($('input#total_pendapatan').val()),
            laba_kotor =  parseFloat($('input#laba_kotor').val()),
            total_beban_operasional = parseFloat($('input#total_beban_operasional').val()),
            total_pendapatan_beban_lain = parseFloat($('input#total_pendapatan_beban_lain').val());

        laba_bersih_belum_pajak = laba_kotor - total_beban_operasional + total_pendapatan_beban_lain;

        $('input#labrug_sebelum_pajak').val(laba_bersih_belum_pajak);
        $('input#labrug_sebelum_pajak').attr('value',laba_bersih_belum_pajak);
        $('span#labrug_sebelum_pajak').text(mb.formatRp(laba_bersih_belum_pajak));

        prosetase_labrug_sblm_pajak = (laba_bersih_belum_pajak / pendapatan_total) * 100;

        $('input#prosentase_labrug_sebelum_pajak').val(prosetase_labrug_sblm_pajak);
        $('input#prosentase_labrug_sebelum_pajak').attr('value',prosetase_labrug_sblm_pajak);
        $('span#prosentase_labrug_sebelum_pajak').text(formatKoma(prosetase_labrug_sblm_pajak));
        
        pajak_penghasilan_badan = (0.5 / 100) * laba_bersih_belum_pajak;

        $('input#pajak_penghasilan_badan').val(pajak_penghasilan_badan);
        $('input#pajak_penghasilan_badan').attr('value',pajak_penghasilan_badan);
        $('span#pajak_penghasilan_badan').text(mb.formatRp(pajak_penghasilan_badan));

        prosetase_pajak_badan = (pajak_penghasilan_badan / pendapatan_total) * 100;

        $('input#prosentase_pajak_penghasilan_badan').val(prosetase_pajak_badan);
        $('input#prosentase_pajak_penghasilan_badan').attr('value',prosetase_pajak_badan);
        $('span#prosentase_pajak_penghasilan_badan').text(formatKoma(prosetase_pajak_badan));


        laba_bersih_setelah_pajak = laba_bersih_belum_pajak - pajak_penghasilan_badan;

        $('input#labrug_setelah_pajak').val(laba_bersih_setelah_pajak);
        $('input#labrug_setelah_pajak').attr('value',laba_bersih_setelah_pajak);
        $('span#labrug_setelah_pajak').text(mb.formatRp(laba_bersih_setelah_pajak));

        prosetase_labrug_setelah_pajak = (laba_bersih_setelah_pajak / pendapatan_total) * 100;

        $('input#prosentase_labrug_setelah_pajak').val(prosetase_labrug_setelah_pajak);
        $('input#prosentase_labrug_setelah_pajak').attr('value',prosetase_labrug_setelah_pajak);
        $('span#prosentase_labrug_setelah_pajak').text(formatKoma(prosetase_labrug_setelah_pajak));

    }

    var handleCountPendapatanLain = function(){

        var $rowPenLain = $('input.akun_pendapatan_lain', $form),
            total_pendapatan_lain = 0;

        $.each($rowPenLain, function(idx, pendapatan){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index');

            total_pendapatan_lain = total_pendapatan_lain + nominal;

            $('input#total_pendapatan_lain').val(total_pendapatan_lain);
            $('input#total_pendapatan_lain').attr('value',total_pendapatan_lain);
            $('span#total_pendapatan_lain').text(mb.formatRp(total_pendapatan_lain));
        });

        $.each($rowPenLain, function(idx, pendapatan){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index'),
                prosentase = 0,
                pendapatan_total = parseFloat($('input#total_pendapatan').val());

            prosentase = (nominal / pendapatan_total) * 100;
            // console.log(nominal);
            $('input#akun_pendapatan_lain_prosentase_'+baris).val(prosentase);
            $('span#akun_pendapatan_lain_prosentase_'+baris).text(formatKoma(prosentase));

        });

        var pendapatan_total = parseFloat($('input#total_pendapatan').val()),
            total_pendapatan_lain = parseFloat($('input#total_pendapatan_lain').val()),
            total_beban_lain = parseFloat($('input#total_beban_lain').val());

        prosetase_total = (total_pendapatan_lain / pendapatan_total) * 100;

        $('input#prosentase_total_pendapatan_lain').val(prosetase_total);
        $('input#prosentase_total_pendapatan_lain').attr('value',prosetase_total);
        $('span#prosentase_total_pendapatan_lain').text(formatKoma(prosetase_total));

        total_pendapatan_beban_lain = total_pendapatan_lain + total_beban_lain;

        $('input#total_pendapatan_beban_lain').val(total_pendapatan_beban_lain);
        $('input#total_pendapatan_beban_lain').attr('value',total_pendapatan_beban_lain);
        $('span#total_pendapatan_beban_lain').text(mb.formatRp(total_pendapatan_beban_lain));

        prosetase_total_lain = (total_pendapatan_beban_lain / pendapatan_total) * 100;

        $('input#prosentase_total_pendapatan_beban_lain').val(prosetase_total_lain);
        $('input#prosentase_total_pendapatan_beban_lain').attr('value',prosetase_total_lain);
        $('span#prosentase_total_pendapatan_beban_lain').text(formatKoma(prosetase_total_lain));

         
        var pendapatan_total = parseFloat($('input#total_pendapatan').val()),
            laba_kotor =  parseFloat($('input#laba_kotor').val()),
            total_beban_operasional = parseFloat($('input#total_beban_operasional').val()),
            total_pendapatan_beban_lain = parseFloat($('input#total_pendapatan_beban_lain').val());

        laba_bersih_belum_pajak = laba_kotor - total_beban_operasional + total_pendapatan_beban_lain;

        $('input#labrug_sebelum_pajak').val(laba_bersih_belum_pajak);
        $('input#labrug_sebelum_pajak').attr('value',laba_bersih_belum_pajak);
        $('span#labrug_sebelum_pajak').text(mb.formatRp(laba_bersih_belum_pajak));

        prosetase_labrug_sblm_pajak = (laba_bersih_belum_pajak / pendapatan_total) * 100;

        $('input#prosentase_labrug_sebelum_pajak').val(prosetase_labrug_sblm_pajak);
        $('input#prosentase_labrug_sebelum_pajak').attr('value',prosetase_labrug_sblm_pajak);
        $('span#prosentase_labrug_sebelum_pajak').text(formatKoma(prosetase_labrug_sblm_pajak));
        
        pajak_penghasilan_badan = (0.5 / 100) * laba_bersih_belum_pajak;

        $('input#pajak_penghasilan_badan').val(pajak_penghasilan_badan);
        $('input#pajak_penghasilan_badan').attr('value',pajak_penghasilan_badan);
        $('span#pajak_penghasilan_badan').text(mb.formatRp(pajak_penghasilan_badan));

        prosetase_pajak_badan = (pajak_penghasilan_badan / pendapatan_total) * 100;

        $('input#prosentase_pajak_penghasilan_badan').val(prosetase_pajak_badan);
        $('input#prosentase_pajak_penghasilan_badan').attr('value',prosetase_pajak_badan);
        $('span#prosentase_pajak_penghasilan_badan').text(formatKoma(prosetase_pajak_badan));


        laba_bersih_setelah_pajak = laba_bersih_belum_pajak - pajak_penghasilan_badan;

        $('input#labrug_setelah_pajak').val(laba_bersih_setelah_pajak);
        $('input#labrug_setelah_pajak').attr('value',laba_bersih_setelah_pajak);
        $('span#labrug_setelah_pajak').text(mb.formatRp(laba_bersih_setelah_pajak));

        prosetase_labrug_setelah_pajak = (laba_bersih_setelah_pajak / pendapatan_total) * 100;

        $('input#prosentase_labrug_setelah_pajak').val(prosetase_labrug_setelah_pajak);
        $('input#prosentase_labrug_setelah_pajak').attr('value',prosetase_labrug_setelah_pajak);
        $('span#prosentase_labrug_setelah_pajak').text(formatKoma(prosetase_labrug_setelah_pajak));

        
    }

    var handleCountBebanLain = function(){

        var $rowBebanLain = $('input.akun_beban_lain', $form),
            total_beban_lain = 0;

        $.each($rowBebanLain, function(idx, bebanlain){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index');

            total_beban_lain = total_beban_lain + nominal;

            $('input#total_beban_lain').val(total_beban_lain);
            $('input#total_beban_lain').attr('value',total_beban_lain);
            $('span#total_beban_lain').text(mb.formatRp(total_beban_lain));
        });

        $.each($rowBebanLain, function(idx, bebanlain){

            var nominal = parseFloat($(this).val()),
                baris = $(this).data('index'),
                prosentase = 0,
                pendapatan_total = parseFloat($('input#total_pendapatan').val());

            prosentase = (nominal / pendapatan_total) * 100;
            // console.log(nominal);
            $('input#akun_beban_lain_prosentase_'+baris).val(prosentase);
            $('span#akun_beban_lain_prosentase_'+baris).text(formatKoma(prosentase));

        });

        var pendapatan_total = parseFloat($('input#total_pendapatan').val()),
            total_pendapatan_lain = parseFloat($('input#total_pendapatan_lain').val()),
            total_beban_lain = parseFloat($('input#total_beban_lain').val());

        prosetase_total = (total_beban_lain / pendapatan_total) * 100;

        $('input#prosentase_total_beban_lain').val(prosetase_total);
        $('input#prosentase_total_beban_lain').attr('value',prosetase_total);
        $('span#prosentase_total_beban_lain').text(formatKoma(prosetase_total));

        total_pendapatan_beban_lain = total_pendapatan_lain + total_beban_lain;

        $('input#total_pendapatan_beban_lain').val(total_pendapatan_beban_lain);
        $('input#total_pendapatan_beban_lain').attr('value',total_pendapatan_beban_lain);
        $('span#total_pendapatan_beban_lain').text(mb.formatRp(total_pendapatan_beban_lain));

        prosetase_total_lain = (total_pendapatan_beban_lain / pendapatan_total) * 100;

        $('input#prosentase_total_pendapatan_beban_lain').val(prosetase_total_lain);
        $('input#prosentase_total_pendapatan_beban_lain').attr('value',prosetase_total_lain);
        $('span#prosentase_total_pendapatan_beban_lain').text(formatKoma(prosetase_total_lain));

        var pendapatan_total = parseFloat($('input#total_pendapatan').val()),
            laba_kotor =  parseFloat($('input#laba_kotor').val()),
            total_beban_operasional = parseFloat($('input#total_beban_operasional').val()),
            total_pendapatan_beban_lain = parseFloat($('input#total_pendapatan_beban_lain').val());

        laba_bersih_belum_pajak = laba_kotor - total_beban_operasional + total_pendapatan_beban_lain;

        $('input#labrug_sebelum_pajak').val(laba_bersih_belum_pajak);
        $('input#labrug_sebelum_pajak').attr('value',laba_bersih_belum_pajak);
        $('span#labrug_sebelum_pajak').text(mb.formatRp(laba_bersih_belum_pajak));

        prosetase_labrug_sblm_pajak = (laba_bersih_belum_pajak / pendapatan_total) * 100;

        $('input#prosentase_labrug_sebelum_pajak').val(prosetase_labrug_sblm_pajak);
        $('input#prosentase_labrug_sebelum_pajak').attr('value',prosetase_labrug_sblm_pajak);
        $('span#prosentase_labrug_sebelum_pajak').text(formatKoma(prosetase_labrug_sblm_pajak));
        
        pajak_penghasilan_badan = (0.5 / 100) * laba_bersih_belum_pajak;

        $('input#pajak_penghasilan_badan').val(pajak_penghasilan_badan);
        $('input#pajak_penghasilan_badan').attr('value',pajak_penghasilan_badan);
        $('span#pajak_penghasilan_badan').text(mb.formatRp(pajak_penghasilan_badan));

        prosetase_pajak_badan = (pajak_penghasilan_badan / pendapatan_total) * 100;

        $('input#prosentase_pajak_penghasilan_badan').val(prosetase_pajak_badan);
        $('input#prosentase_pajak_penghasilan_badan').attr('value',prosetase_pajak_badan);
        $('span#prosentase_pajak_penghasilan_badan').text(formatKoma(prosetase_pajak_badan));


        laba_bersih_setelah_pajak = laba_bersih_belum_pajak - pajak_penghasilan_badan;

        $('input#labrug_setelah_pajak').val(laba_bersih_setelah_pajak);
        $('input#labrug_setelah_pajak').attr('value',laba_bersih_setelah_pajak);
        $('span#labrug_setelah_pajak').text(mb.formatRp(laba_bersih_setelah_pajak));

        prosetase_labrug_setelah_pajak = (laba_bersih_setelah_pajak / pendapatan_total) * 100;

        $('input#prosentase_labrug_setelah_pajak').val(prosetase_labrug_setelah_pajak);
        $('input#prosentase_labrug_setelah_pajak').attr('value',prosetase_labrug_setelah_pajak);
        $('span#prosentase_labrug_setelah_pajak').text(formatKoma(prosetase_labrug_setelah_pajak));

    }

    var handleValidation = function() {
        var error1   = $('.alert-danger', $form);
        var success1 = $('.alert-success', $form);

        $form.validate({
            // class has-error disisipkan di form element dengan class col-*
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('[class^="col"]'));
            },
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            // rules: {
            // buat rulenya di input tag
            // },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                Metronic.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('[class^="col"]').addClass('has-error');
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control group
            } 
        });    
    }

    formatKoma = function(num){
        return num.format(3, 3, '.') + ' %';
    };


    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/laporan_rugi_laba/';
        initForm();


    
        // handleDropdownTypeChange();
 
    };

}(mb.app.laporan_rugi_laba.edit));

$(function(){    
    mb.app.laporan_rugi_laba.edit.init();
});