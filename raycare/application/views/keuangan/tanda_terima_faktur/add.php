<?php

    $form_attr = array(
        "id"            => "form_tanda_terima_faktur", 
        "name"          => "form_tanda_terima_faktur", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."keuangan/tanda_terima_faktur/save", $form_attr, $hidden); 

    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');


    $btn_del_berkas = '<div class="text-center"><button class="btn red-intense del-this-berkas" title="Hapus Berkas"><i class="fa fa-times"></i></button></div>';

    $item_cols_berkas = array(
        'berkas_nomor' => '<input id="berkas_nomor_{0}" name="berkas[{0}][nomor]" class="form-control">',
        'keterangan'   => '<input id="keterangan_{0}" name="berkas[{0}][keterangan]" class="form-control">',
        'nilai'        => '<div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input id="nilai_{0}" name="berkas[{0}][nilai]" class="form-control text-right"></div>',
        'action'       => $btn_del_berkas,
    );

    $item_row_template_berkas =  '<tr id="item_row_berkas_{0}" ><td>' . implode('</td><td>', $item_cols_berkas) . '</td></tr>';




?>  
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-plus font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tanda Terima Faktur", $this->session->userdata("language"))?></span>
        </div>
        
    </div>
    <div class="portlet-body form">
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <?=$form_alert_danger?>
        </div>
        <div class="alert alert-success display-hide">
            <button class="close" data-close="alert"></button>
            <?=$form_alert_success?>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="portlet box blue-sharp">
                    <div class="portlet-title" style="margin-bottom: 0px !important;">
                        <div class="caption">
                            <?=translate("Informasi PO", $this->session->userdata("language"))?>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <?php
                                                    $nama_supplier = array(
                                                        "id"        => "nama_supplier",
                                                        "name"      => "nama_supplier",
                                                        "autofocus" => true,
                                                        "class"     => "form-control", 
                                                        "readonly"  => "readonly"
                                                    );

                                                    $id_supplier = array(
                                                        "id"          => "id_supplier",
                                                        "name"        => "id_supplier",
                                                        "autofocus"   => true,
                                                        "class"       => "form-control hidden",
                                                        "placeholder" => translate("Pasien", $this->session->userdata("language"))
                                                    );

                                                    $id_po = array(
                                                        "id"          => "id_po",
                                                        "name"        => "id_po",
                                                        "autofocus"   => true,
                                                        "class"       => "form-control hidden",
                                                        "placeholder" => translate("PO", $this->session->userdata("language"))
                                                    );
                                                    echo form_input($nama_supplier).form_input($id_supplier).form_input($id_po);
                                                ?>
                                                <span class="input-group-btn">
                                                    <a class="btn btn-primary pilih-supplier" title="<?=translate('Pilih Supplier', $this->session->userdata('language'))?>">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12" id="nama_supplier"></label>
                                        <input class="form-control" type="hidden" id="supplier_id" name="supplier_id" ></input>
                                        <input class="form-control" type="hidden" id="is_pkp" name="is_pkp" ></input>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12" id="kontak_supplier"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Email", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12" id="email_supplier"></label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12" id="alamat_supplier"> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12" id="tanggal_pesan"></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Garansi", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12" id="tanggal_garansi"></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> :</label>
                                        <label class="col-md-12" id="tipe_bayar"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
                                <label class="col-md-12" id="keterangan"></label>
                            </div>
                        </div>
                    </div>
                        <div class="portlet-title" style="margin-bottom: 0px !important;">
                            <div class="caption">
                                <span class="caption-subject"><?=translate("Order Deskripsi", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-scrollable">
                            <table class="table table-striped table-hover" id="table_detail_pembelian">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" ><?=translate("Nama", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"style="width : 120px !important;"><?=translate("Harga", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="3%"><?=translate("Disc", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="5%"><?=translate("Jml PO", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="10%"><?=translate("Jml Inv", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="9%"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="10%"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
                                    </tr>
                                </thead>
                                <tbody id="detail_po">
                                    
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                        <td colspan="10" class="text-right bold bg-grey" style="height:25px;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right bold">Total</td>
                                        <td><div class="text-right bold" id="total"></div></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right bold" style="background-color:#f9f9f9">Diskon(%)</td>
                                        <td class="text-right" id="diskon_persen" style="background-color:#f9f9f9"></td>
                                        <td class="text-right" id="diskon_nominal" style="background-color:#f9f9f9"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="7" class="text-right bold">TAD</td>
                                        <td class="text-right bold" id="grand_tot"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right bold" style="background-color:#f9f9f9">PPN(%)</td>
                                        <td class="text-right" id="ppn_persen" style="background-color:#f9f9f9"></td>
                                        <td class="text-right" id="ppn_nominal" style="background-color:#f9f9f9"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right bold">TAT</td>
                                        <td class="text-right bold" id="grand_tot_tax"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right bold" style="background-color:#f9f9f9">DP(%)</td>
                                        <td class="text-right" id="dp_persen" style="background-color:#f9f9f9"></td>
                                        <td class="text-right" id="dp_nominal" style="background-color:#f9f9f9"></td>
                                    </tr>
                                    <tr class="hidden">
                                        <td colspan="7" class="text-right bold">Sisa Bayar</td>
                                        <td class="text-right bold"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right bold">Biaya Tambahan</td>
                                        
                                        
                                        <td class="text-right" id="biaya_tambahan_po"></a></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right bold" style="background-color:#f9f9f9">Grand Total Setelah Biaya</td>
                                        <td class="text-right bold" id="grand_tot_biaya" style="background-color:#f9f9f9"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right bold">TOTAL INVOICE</td>
                                        <td colspan="2" class="text-right bold" id="label_total_invoice"></td>
                                    </tr>
                                    <tr class="hidden">
                                        <td colspan="6" class="text-right bold" style="background-color:#f9f9f9">TOTAL DATANG</td>
                                        <td colspan="2" class="text-right bold" id="label_total_datang" style="background-color:#f9f9f9"></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <input class="form-control hidden" readonly value="" id="tot_hidden" name="tot_hidden">
                            <input class="form-control hidden" id="ppn_hidden" name="ppn_hidden" value="">
                            <input class="form-control text-right hidden" id="disk_hidden" name="disk_hidden" value="">
                            
                            <input class="form-control hidden" id="biaya_tambah_hidden" name="biaya_tambah_hidden" value="">

                            <input class="form-control hidden" id="grand_tot_hidden" name="grand_tot_hidden" value="">
                            <input class="form-control hidden" id="grand_tot_biaya_hidden" name="grand_tot_biaya_hidden" value="">
                            <input class="form-control hidden" id="depe" name="depe" value="">
                            <input class="form-control hidden" id="sisa_nya" name="sisa_nya" value="">
                            <input class="form-control hidden" id="total_invoice" name="total_invoice" value="">
                            <input class="form-control hidden" id="total_datang" name="total_datang" value="">
                        </div>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="portlet box red" id="section-biaya">
                                    <div class="portlet-title" style="margin-bottom: 0px !important;">
                                        <div class="caption">
                                            <span class="caption-subject"><?=translate("Biaya", $this->session->userdata("language"))?></span>
                                        </div>
                                        <div class="actions">
                                            <a class="btn btn-icon-only btn-default btn-circle add-biaya">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <?php
                                        $biaya_option = array(
                                            ''  => translate('Pilih', $this->session->userdata('language')).'...'
                                        );

                                        $biaya = $this->biaya_m->get_by(array('is_active' => 1));

                                        foreach ($biaya as $row) {
                                            $biaya_option[$row->id] = $row->nama;
                                        }

                                        $form_biaya = '
                                            <div class="form-group hidden">
                                                <label class="control-label col-md-4">'.translate("ID", $this->session->userdata("language")).' :</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="id_biaya{0}" name="biaya[{0}][id]">
                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <label class="control-label col-md-4">'.translate("Active", $this->session->userdata("language")).' :</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="is_active_biaya{0}" name="biaya[{0}][is_active]">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12 bold">'.translate("Jenis Biaya", $this->session->userdata("language")).' :</label>
                                                <div class="col-md-12">
                                                    <div class="input-group">';
                                        $form_biaya .= form_dropdown('biaya[{0}][biaya_id]', $biaya_option, '','id="jenis_biaya_{0}" class="form-control"');             
                                        $form_biaya .= '<span class="input-group-btn">
                                                            <a class="btn red-intense del-this-biaya" id="btn_delete_biaya_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12 bold">'.translate("Jumlah Biaya", $this->session->userdata("language")).' :</label>
                                                <div class="col-md-12">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                Rp.
                                                            </span>
                                                            <input class="form-control jumlah" id="jumlah_biaya_{0}" name="biaya[{0}][nominal]" placeholder="Jumlah Biaya">
                                                        </div>
                                                        <span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12 bold">'.translate("Upload Bukti Biaya", $this->session->userdata("language")).' :</label>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="biaya[{0}][url]" id="biaya_url_{0}">
                                                    <div id="upload_biaya_{0}">
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>        
                                                            <input type="file" class="upl_biaya" name="upl" id="upl_biaya_{0}" data-url="'.base_url().'upload_new/upload_photo" multiple />
                                                        </span>

                                                    <ul class="ul-img">
                                                    </ul>

                                                    </div>
                                                </div>
                                            </div>';

                                        ?>
                                        <input type="hidden" id="tpl-form-biaya" value="<?=htmlentities($form_biaya)?>">
                                        <div class="form-body">
                                            <ul class="list-unstyled" id="biayaList">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                </div>
            </div>
        </div>
        <div class="col-md-5">
                <div class="portlet box blue-madison" id="section-bon">
                    <div class="portlet-title" style="margin-bottom: 0px !important;">
                        <div class="caption">
                            <span class="caption-subject"><?=translate("Form Input", $this->session->userdata("language"))?></span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
                                        <div class="col-md-12">
                                            <div class="input-group date">
                                                <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?=date('d M Y')?>" readonly >
                                                <span class="input-group-btn">
                                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Tanggal Bayar", $this->session->userdata("language"))?> :</label>              
                                        <div class="col-md-12">
                                            <div class="input-group date">
                                                <input type="text" class="form-control" id="tanggal_faktur" name="tanggal_faktur" placeholder="Tanggal Faktur" value="<?=date('d M Y')?>"readonly required>
                                                <span class="input-group-btn">
                                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Diserahkan oleh", $this->session->userdata("language"))?> :</label>
                                        <div class="col-md-12">
                                            <?php
                                                $diserahkan = array(
                                                    "id"            => "diserahkan",
                                                    "name"          => "diserahkan",
                                                    "class"         => "form-control",
                                                    "placeholder"   => translate("Diserahkan Oleh", $this->session->userdata("language")),
                                                    "required"      => "required"
                                                );
                                                echo form_input($diserahkan);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("No. Telepon", $this->session->userdata("language"))?> :</label>
                                        <div class="col-md-12">
                                            <?php
                                                $no_telepon = array(
                                                    "id"            => "no_telepon",
                                                    "name"          => "no_telepon",
                                                    "class"         => "form-control",
                                                    "placeholder"   => translate("No. Telepon", $this->session->userdata("language")),
                                                    "required"      => "required"
                                                );
                                                echo form_input($no_telepon);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
                                <div class="col-md-12">
                                    <?php
                                        $keterangan = array(
                                            "id"            => "keterangan",
                                            "name"          => "keterangan",
                                            "autofocus"     => true,
                                            "rows"          => 4,
                                            "class"         => "form-control",
                                            "style"         => "resize: none;",
                                            "placeholder"   => translate("Keterangan", $this->session->userdata("language"))
                                        );
                                        echo form_textarea($keterangan);
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet box blue-madison">
                                    <div class="portlet-title" style="margin-bottom: 0px !important;">
                                        <div class="caption">
                                            <span class="caption-subject"><?=translate("Upload Invoice", $this->session->userdata("language"))?></span>
                                        </div>
                                        <div class="actions">
                                            <a class="btn btn-icon-only btn-default btn-circle add-upload">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="portlet-body">
                                        <?php
                                        $form_upload_bon = '
                                            <div class="form-group hidden">
                                                <label class="col-md-12">'.translate("ID", $this->session->userdata("language")).' :</label>
                                                <div class="col-md-12">
                                                    <input class="form-control" id="id_bon{0}" name="bon[{0}][id]">
                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <label class="col-md-12">'.translate("Active", $this->session->userdata("language")).' :</label>
                                                <div class="col-md-12">
                                                    <input class="form-control" id="is_active_bon{0}" name="bon[{0}][is_active]">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12 bold">'.translate("No. Invoice", $this->session->userdata("language")).' :</label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input class="form-control" id="no_bon_{0}" name="bon[{0}][no_bon]" placeholder="No. Invoice">
                                                        <span class="input-group-btn">
                                                            <a class="btn red-intense del-this" id="btn_delete_upload_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-12 bold">'.translate("Total Invoice", $this->session->userdata("language")).' :</label>
                                                        <div class="col-md-12">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        Rp.
                                                                    </span>
                                                                    <input class="form-control" required id="total_bon_{0}" name="bon[{0}][total_bon]" placeholder="Total Invoice">
                                                                </div>
                                                                <span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-12 bold">'.translate("Tanggal", $this->session->userdata("language")).' :<span class="required">*</span></label>
                                                        <div class="col-md-12">
                                                            <div class="input-group date">
                                                                <input type="text" class="form-control" id="bon_tanggal_{0}" name="bon[{0}][tanggal]" placeholder="Tanggal" value="'.date('d M Y').'"readonly >
                                                                <span class="input-group-btn">
                                                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-12 bold">'.translate("Keterangan", $this->session->userdata("language")).' :</label>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control" id="keterangan_{0}" name="bon[{0}][keterangan]" cols="8" rows="4" ></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-md-12 bold">'.translate("Upload Invoice", $this->session->userdata("language")).' :<span class="required">*</span></label>
                                                        <div class="col-md-12">
                                                            <input type="hidden" required name="bon[{0}][url]" id="bon_url_{0}">
                                                            <div id="upload_{0}">
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>        
                                                                    <input type="file" class="upl_invoice" name="upl" id="upl_{0}" data-url="'.base_url().'upload_new/upload_photo" multiple />
                                                                </span>

                                                            <ul class="ul-img">
                                                            </ul>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="form-group upload_pkp hidden">
                                                <label class="col-md-12">'.translate("Upload Faktur Pajak", $this->session->userdata("language")).' :<span class="required">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="bon[{0}][url_pajak]" id="bon_url_pajak_{0}">
                                                    <div id="upload_pajak_{0}">
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>        
                                                            <input type="file" class="upl_invoice" name="upl" id="upl_pajak_{0}" data-url="'.base_url().'upload_new/upload_photo" multiple />
                                                        </span>

                                                    <ul class="ul-img-pajak">
                                                    </ul>

                                                    </div>
                                                </div>
                                            </div>
                                            
                                            ';
                                        ?>

                                        <input type="hidden" id="tpl-form-upload" value="<?=htmlentities($form_upload_bon)?>">
                                        <div class="form-body" >
                                            <ul class="list-unstyled" id="invoiceList">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <div class="form-actions right">
            <?php

                $back_text      = translate('Kembali', $this->session->userdata('language'));
                $confirm_save   = translate('Apa Kamu Yakin Akan Membuat Tanda Terima Faktur Ini ?',$this->session->userdata('language'));
                $submit_text    = translate('Simpan', $this->session->userdata('language'));
                $reset_text     = translate('Reset', $this->session->userdata('language'));
            ?>
                
            <a class="btn btn-circle btn-default hidden" id="show_modal" href="<?=base_url()?>keuangan/tanda_terima_faktur/modal_po/1" data-toggle="modal" data-target="#modal_po">
                <i class="fa fa-users"></i>
            Supplier
            </a>
            <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
                <i class="fa fa-chevron-left"></i>
            <?=$back_text?>
            </a>
            <button type="submit" id="save" class="btn btn-primary hidden" >
            <?=$submit_text?>
            </button> 
            <a id="confirm_save" class="btn btn-circle btn-primary" data-confirm="<?=$confirm_save?>">
                <i class="glyphicon glyphicon-floppy-disk"></i>
            <?=$submit_text?>
            </a> 
        </div>
</div>


                            
<?=form_close();?>

<div class="modal fade bs-modal-lg" id="modal_po" role="basic" aria-hidden="true" >
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>

<div id="popover_supplier_content" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_supplier">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Rating', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

