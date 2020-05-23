<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_alkes extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'a06518a55052e78099810015fab51967';                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->library('mpdf/mpdf.php');

        $this->load->model('pembelian/o_s_pmsn_m');
        $this->load->model('pembelian/daftar_permintaan_po_m');
        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_cetak_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_po_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_po_history_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('pembelian/pembelian_detail_tanggal_kirim_m');
        $this->load->model('pembelian/pembelian_penawaran_m');
        $this->load->model('pembelian/pembelian_kredit_m');
        $this->load->model('pembelian/pembelian_biaya_m');
        $this->load->model('pembelian/supplier_m');
        $this->load->model('pembelian/o_s_pembayaran_pembelian_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_telepon_m');
        $this->load->model('master/supplier/supplier_email_m');
        $this->load->model('master/user_level_persetujuan_m');
        $this->load->model('pembelian/penerima_cabang_m');
        $this->load->model('pembelian/penerima_customer_m');
        $this->load->model('pembelian/link_permintaan_pembelian_m');
        $this->load->model('pembelian/draft_po_m');
        $this->load->model('pembelian/draf_po_detail_m');
        $this->load->model('pembelian/item_m');
        $this->load->model('pembelian/supplier_tipe_pembayaran_m');
        $this->load->model('pembelian/supplier_harga_item_m');
        $this->load->model('pembelian/supplier_item_m');
        $this->load->model('pembelian/item_sub_kategori_pembelian_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/master_tipe_bayar_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('penjualan/penjualan_detail_m');
        $this->load->model('penjualan/inventory_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');
        $this->load->model('master/biaya_m');
        $this->load->model('gudang/barang_datang/pmb_m');
        $this->load->model('gudang/barang_datang/pmb_po_detail_m');
        $this->load->model('pegawai/pegawai_user_m');
        $this->load->model('pegawai/pegawai_m');
        $this->load->model('keuangan/perubahan_modal/o_s_hutang_m');
        $this->load->model('pembelian/setting_persetujuan_po_m');

   
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/pembelian/pembelian_alkes/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pembelian', $this->session->userdata('language')), 
            'header'         => translate('Pembelian', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/pembelian_alkes/index',
            //'content_view'   => 'under_maintenance',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 
    public function history()
    {
        $assets = array();
        $config = 'assets/pembelian/pembelian_alkes/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pembelian', $this->session->userdata('language')), 
            'header'         => translate('Pembelian', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/pembelian_alkes/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function modal_jumlah_terima($detail_id, $item_id, $item_satuan_id)
    {
        $data = array(
            'po_detail_id' => $detail_id,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        $this->load->view('gudang/barang_datang/modal/modal_jumlah_terima.php', $data);
    
    }

    public function add($supplier_id = NULL, $param_item = NULL, $param_item_satuan = NULL, $param_jumlah = NULL)
    {
        $assets = array();
        $assets_config = 'assets/pembelian/pembelian_alkes/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data_supplier = array();
        $data_item = array();
        $data_item_satuan = array();
        $data_jumlah = array();
        $tipe_pembayaran = array();
        $email_supplier = array();

        if($supplier_id != NULL){
            $data_supplier = $this->supplier_m->get_data($supplier_id)->result_array();
            $tipe_pembayaran = $this->supplier_tipe_pembayaran_m->get_pembayaran($supplier_id)->result_array();
            $email_supplier = $this->supplier_email_m->get_by(array('supplier_id' => $supplier_id, 'is_active' => 1, 'is_primary' => 1), true);
            $email_supplier = object_to_array($email_supplier);

            $data_item = unserialize(base64_decode(urldecode($param_item)));
            $data_item_satuan = unserialize(base64_decode(urldecode($param_item_satuan)));
            $data_jumlah = unserialize(base64_decode(urldecode($param_jumlah)));

            //die_dump($data_jumlah);
        }

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Tambah Pembelian", $this->session->userdata("language")), 
            'header'         => translate("Tambah Pembelian", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_supplier'       => $data_supplier,
            'tipe_pembayaran'       => $tipe_pembayaran,
            'email_supplier'       => $email_supplier,
            'data_item'       => $data_item,
            'data_item_satuan'       => $data_item_satuan,
            'data_jumlah'       => $data_jumlah,
            'content_view'   => 'pembelian/pembelian_alkes/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit_draft_po($id, $id_sup)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $id_sup = intval($id_sup);
        $id_sup || redirect(base_Url());

        $assets = array();
        $config = 'assets/pembelian/pembelian_alkes/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->draft_po_m->get_data($id)->result_array();
        // die_dump($this->db->last_query());

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Draft PO", $this->session->userdata("language")), 
            'header'         => translate("Edit Draft PO", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'], 
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/pembelian_alkes/edit_draft_po',
            'form_data'      => object_to_array($form_data),
            'form_data_item' => $this->draft_po_m->get_data_item($id, $id_sup),
            'pk_value'       => $id                         //table primary key value
        );

         $this->load->view('_layout', $data);
    }

    public function edit_pembelian($id)
    {
        $assets = array();
        $config = 'assets/pembelian/pembelian_alkes/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->pembelian_m->get_data($id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);


        $data = array(
            'title'               => config_item('site_name').' | '. translate("Edit Pembelian", $this->session->userdata("language")), 
            'header'              => translate("Edit Pembelian", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'], 
            'js_files'            => $assets['js'],
            'content_view'        => 'pembelian/pembelian_alkes/edit_pembelian',
            'form_data'           => object_to_array($form_data),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'form_data_detail'    => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'pk_value'            => $id                         //table primary key value
        );

         $this->load->view('_layout', $data);
    }
    public function edit_pembelian_tolak($id)
    {
        $assets = array();
        $config = 'assets/pembelian/pembelian_alkes/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->pembelian_m->get_data($id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);
        // die(dump($this->db->last_query()));


        $data = array(
            'title'               => config_item('site_name').' | '. translate("Edit Pembelian", $this->session->userdata("language")), 
            'header'              => translate("Edit Pembelian", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'], 
            'js_files'            => $assets['js'],
            'content_view'        => 'pembelian/pembelian_alkes/edit_pembelian_tolak',
            'form_data'           => object_to_array($form_data),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'form_data_detail'    => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'pk_value'            => $id                         //table primary key value
        );

         $this->load->view('_layout', $data);
    }

    public function view($id)
    {

        $assets = array();
        $config = 'assets/pembelian/pembelian_alkes/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_data = $this->pembelian_m->get_data($id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("View Pembelian", $this->session->userdata("language")), 
            'header'              => translate("View Pembelian", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'pembelian/pembelian_alkes/view',
            'form_data'           => object_to_array($form_data),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'tipe_bayar'          => $tipe_bayar,
            'form_data_detail'    => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_proses($id)
    {

        $assets = array();
        $config = 'assets/pembelian/pembelian_alkes/view_proses';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_data = $this->pembelian_m->get_data($id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("View Pembelian", $this->session->userdata("language")), 
            'header'              => translate("View Pembelian", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'pembelian/pembelian_alkes/view_proses',
            'form_data'           => object_to_array($form_data),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'tipe_bayar'          => $tipe_bayar,
            'form_data_detail'    => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($tipe=null, $stat=null)
    {        
        $result = $this->pembelian_m->get_datatable($tipe, $stat, 2);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

    // die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $info = '';

            if($row['status'] == 1)
            {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/pembelian_alkes/view/'.$row['id'].'"><i class="fa fa-search"></i></a>
                <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'pembelian/pembelian_alkes/edit_pembelian/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data pembelian ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
                
                $status = '<div class="text-left"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
            }
            else if($row['status'] == 2)
            {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/pembelian_alkes/view/'.$row['id'].'"><i class="fa fa-search"></i></a>';
                
                $status = '<div class="text-left"><span class="label label-md label-primary">Proses Persetujuan</span></div>';
            }
            else if($row['status'] == 3)
            {
                $action = '<a title="'.translate('Verifikasi Pembelian', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'pembelian/pembelian_alkes/proses_setuju/'.$row['id'].'"><i class="fa fa-check"></i></a>';
                
                $status = '<div class="text-left"><span class="label label-md label-info">Disetujui</span></div>';
            }
            else if($row['status'] == 6)
            {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/pembelian_alkes/view/'.$row['id'].'"><i class="fa fa-search"></i></a>
                <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'pembelian/pembelian_alkes/edit_pembelian_tolak/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data cabang ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
                
                $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';
            }

            $posisi = $this->persetujuan_po_m->get_posisi_persetujuan($row['id'], $row['status'])->result_array();
            
            $output['data'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left inline-button-table">'.$row['no_po'].'</div>',
                $row['nama_sup'].' <strong>['.$row['kode_sup'].']</strong>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_pesan'])).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_kadaluarsa'])).'</div>',
                $row['keterangan'],
                $status,
                $posisi[0]['nama'],
                '<div class="text-left inline-button-table">'.$action.'</div>'  
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_proses($tipe=null)
    {      
        $result = $this->pembelian_m->get_datatable_proses($tipe, 2);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $info = '';
            $no_cetak = '';
            $supplier = '';
            $tgl_pesan = '';
            $tgl_kadaluarsa = '';

            $get_data_cetak = $this->pembelian_cetak_m->get_pembelian_cetak($row['id']);
            // die_dump($get_data_cetak);
            $cetakan = '';
           
            if($get_data_cetak[0]['no_cetak'] != null)
            {
                $info = '<a class="btn btn-primary no_cetak" data-cetak="'.htmlentities(json_encode($get_data_cetak)).'"><i class="fa fa-info"></i></a>';
                $no_cetak = $get_data_cetak[0]['no_cetak'];
                $cetakan = '<div class="input-group" style="width:120px;">
                    <input class="form-control text-left" value="'.$no_cetak.'" id="jumlah_'.$row['id'].'" readonly>
                    <span class="input-group-btn">'.$info.'</span>
                </div>';
            }

            if($row['nama_sup'] != null && $row['kode_sup'] != null && $row['tanggal_pesan'] != null && $row['tanggal_kadaluarsa'] != null)
            {
                $supplier = $row['nama_sup'].' <strong>['.$row['kode_sup'].']</strong>';
                $tgl_pesan = date('d M Y', strtotime($row['tanggal_pesan']));
                $tgl_kadaluarsa = date('d M Y', strtotime($row['tanggal_kadaluarsa']));

                 $action = '<a title="'.translate('Send PO', $this->session->userdata('language')).'" data-target="#modal_send_po" data-toggle="modal" href="'.base_url().'pembelian/pembelian_alkes/send_po/'.$row['id'].'" class="btn btn-success"><i class="fa fa-send"></i></a>
                 <a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/pembelian_alkes/view_proses/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>
                <a title="'.translate('Finish PO', $this->session->userdata('language')).'" data-target="#modal_finish_po" data-toggle="modal" href="'.base_url().'pembelian/pembelian_alkes/selesaikan_po/'.$row['id'].'" class="btn btn-primary perpanjang"><i class="fa fa-check"></i></a>
                <a title="'.translate('Cancel PO', $this->session->userdata('language')).'" data-target="#modal_cancel_po" data-toggle="modal" href="'.base_url().'pembelian/pembelian_alkes/batalkan_po/'.$row['id'].'" class="btn red cancel"><i class="fa fa-undo"></i></a>
                <a title="'.translate('Print', $this->session->userdata('language')).'" href="'.base_url().'pembelian/pembelian_alkes/cetak_po/'.$row['id'].'" target="_blank" class="btn default"><i class="fa fa-print"></i></a>';

            }
            
            $output['data'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left inline-button-table">'.$row['no_po'].'</div>',
                $supplier,
                '<div class="text-center">'.$tgl_pesan.'</div>',
                '<div class="text-center">'.$tgl_kadaluarsa.'</div>',
                $row['keterangan'],
                $cetakan,
                '<div class="text-center inline-button-table">'.$action.'</div>'  
            );
         $i++;
        }

        echo json_encode($output);
    }
    
    public function listing_history($tipe=null)
    {      
        $result = $this->pembelian_m->get_datatable_history($tipe, 2);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $info = '';
            $no_cetak = '';
            $supplier = '';
            $tgl_pesan = '';
            $tgl_kadaluarsa = '';

            $get_data_cetak = $this->pembelian_cetak_m->get_pembelian_cetak($row['id']);
            // die_dump($get_data_cetak);
            $cetakan = '';
           
            if($get_data_cetak[0]['no_cetak'] != null)
            {
                $info = '<a class="btn btn-primary no_cetak" data-cetak="'.htmlentities(json_encode($get_data_cetak)).'"><i class="fa fa-info"></i></a>';
                $no_cetak = $get_data_cetak[0]['no_cetak'];
                $cetakan = '<div class="input-group" style="width:120px;">
                    <input class="form-control text-center" value="'.$no_cetak.'" id="jumlah_'.$row['id'].'" readonly>
                    <span class="input-group-btn">'.$info.'</span>
                </div>';
            }

            if($row['nama_sup'] != null && $row['kode_sup'] != null && $row['tanggal_pesan'] != null && $row['tanggal_kadaluarsa'] != null)
            {
                $supplier = $row['nama_sup'].' <strong>['.$row['kode_sup'].']<strong>';
                $tgl_pesan = date('d M Y', strtotime($row['tanggal_pesan']));
                $tgl_kadaluarsa = date('d M Y', strtotime($row['tanggal_kadaluarsa']));

                 $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/pembelian_alkes/view_proses/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>
                 <a title="'.translate('Send PO', $this->session->userdata('language')).'" data-target="#modal_send_po" data-toggle="modal" href="'.base_url().'pembelian/pembelian_alkes/send_po/'.$row['id'].'" class="btn btn-success"><i class="fa fa-send"></i></a>';

            }
            
            $status = '';
            $keterangan = $row['keterangan'];
            if($row['status'] == 5 && $row['status_cancel'] == 0)
            {
                $status = '<div class="text-left"><span class="label label-md label-success">PO Selesai</span></div>';
            }if($row['status'] == 8 && $row['status_cancel'] == 0)
            {
                $status = '<div class="text-left"><span class="label label-md label-danger">PO Ditolak</span></div>';
            }if($row['status'] == 9 && $row['status_cancel'] == 0)
            {
                $status = '<div class="text-left"><span class="label label-md label-danger">PO Dihapus</span></div>';
            }if($row['status_cancel'] == 1)
            {
                $status = '<div class="text-left"><span class="label label-md label-warning">PO Cancel</span></div>';
                $keterangan = $row['keterangan_batal'];
            }
            
            $output['data'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left inline-button-table">'.$row['no_po'].'</div>',
                $supplier,
                '<div class="text-center">'.$tgl_pesan.'</div>',
                '<div class="text-center">'.$tgl_kadaluarsa.'</div>',
                $keterangan,
                $status,
                $cetakan,
                '<div class="text-center inline-button-table">'.$action.'</div>'  
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_draft($tipe=null)
    {
        
        $result = $this->draft_po_m->get_datatable_pembelian($tipe);
        // die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            

            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'pembelian/pembelian_alkes/edit_draft_po/'.$row['id'].'/'.$row['id_sup'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
 
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                $row['nama'].' ['.$row['kode'].']',
                '<div class="text-center">'.date('d/m/Y', strtotime($row['tanggal_pesan'])).'</div>',
                '<div class="text-center">'.date('d/m/Y', strtotime($row['tanggal_kadaluarsa'])).'</div>',
                $row['keterangan'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_supplier($tipe=null)
    {
        
        $result = $this->supplier_m->get_datatable($tipe, 2);
        // die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $tipe_pembayaran = $this->supplier_tipe_pembayaran_m->get_pembayaran($row['id'])->result_array();
            $email_supplier = $this->supplier_email_m->get_by(array('supplier_id' => $row['id'], 'is_active' => 1, 'is_primary' => 1), true);

            $negara= "Indonesia";
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-pembayaran="'.htmlentities(json_encode($tipe_pembayaran)).'"  data-item="'.htmlentities(json_encode($row)).'" data-email="'.htmlentities(json_encode($email_supplier)).'" data-negara="'.$negara.'" class="btn btn-primary select-supplier"><i class="fa fa-check"></i></a>';

            if($row['rating'] == 0.0)
            {
                $rate = '<i class="fa fa-star-o"></i>';
            }

            else if($row['rating'] > 0.0)
            {
                for($x=1;$x<=$row['rating']/2;$x++)
                 {
                     $rate .= '<i class="fa fa-star"></i>';
                 }
            }

 
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                $row['kode'],
                $row['nama'],
                '<div class="text-left">'.$row['kontak_person'].' ('.$row['no_telp'].')'.'</div>',
                '<div class="text-left">'.$row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['propinsi'].', '.$negara.'</div>',
                '<div class="text-center">'.$rate.'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
        $rate = '';
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pembelian_cetak()
    {
        
        $result = $this->pembelian_m->get_datatable_cetak();
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-center">'.$row['no_cetak'].'</div>',
                '<div class="text-left">'.$row['user'].'</div>',
                '<div class="text-center">'.date('d/m/Y', strtotime($row['tanggal_cetak'])).'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_penerima_cabang()
    {
        
        $result = $this->penerima_cabang_m->get_datatable();
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $negara= "Indonesia";
            $kelurahan = '';
            if($row['kelurahan'] != '')
            {
                $kelurahan = $row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['propinsi'].', ';
            }
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-negara="'.$negara.'" class="btn btn-primary select-cabang"><i class="fa fa-check"></i></a>';

            $output['aaData'][] = array(
                $row['id'],
                $row['nama_cabang'],
                '<div class="text-left">'.$row['penanggung_jawab'].'</div>',
                '<div class="text-left">'.$row['alamat'].', '.$kelurahan.$negara.'</div>',
                '<div class="text-center">'.$action.'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_penerima_customer()
    {
        
        $result = $this->penerima_customer_m->get_datatable();
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-tipe="1" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select-customer"><i class="fa fa-check"></i></a>';

            $output['aaData'][] = array(
                $row['id'],
                $row['kode_customer'],
                $row['nama_customer'],
                '<div class="text-left">'.$row['orang_bersangkutan'].' ('.$row['no_telp'].')'.'</div>',
                '<div class="text-center">'.$row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['propinsi'].', '.$row['negara'].'</div>',
                '<div class="text-center">'.$action.'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_search_item($id_supplier=null)
    {
        // $id = '1';
        $result = $this->item_m->get_datatable_item($id_supplier);
        // die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
       // die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            if($row['is_active']==1)
            {

                $get_data_info = $this->item_m->get_data_item($row['id'])->result_array();
                $sub_data_info = object_to_array($get_data_info);
                $info_stok = '';


                foreach ($sub_data_info as $data_info) {
                    $info_stok .= $data_info['stok'].' '.$data_info['nama'].' ';

                }
                if(count($get_data_info) == 0)
                {
                    $sub_data_info[0]['stok'] = 0;
                    $sub_data_info[0]['nama'] = $row['satuan'];
                    $info_stok = '0 '.$row['satuan'];

                }
                $supplier_item = $this->supplier_item_m->get_by(array('supplier_id' => $id_supplier, 'item_id' => $row['id'], 'item_satuan_id' => $row['item_satuan_id']), true);
                // die(sump($this->db->last_query()));
                $harga_item = $this->supplier_harga_item_m->get_harga($supplier_item->id)->result_array();
                
                $row['harga'] = $harga_item[0]['harga'];

                $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1),true);
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-info="'.htmlentities(json_encode($sub_data_info)).'" data-item="'.htmlentities(json_encode($row)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';

                



                $info = '<a title="'.$info_stok.'" name="info[]" class="btn btn-primary pilih-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';

                $output['aaData'][] = array(
                    '<div class="text-center">'.$row['id'].'</div>',
                    '<div class="text-center">'.$row['item_kode'].'</div>',
                    $row['item_nama'],
                    '<div class="text-center">'.$info.'</div>',
                    ($row['jumlah'] == NULL)?'<div class="text-center">0</div>':'<div class="text-center">'.$row['jumlah'].'</div>',
                    '<div class="text-center">'.$row['satuan'].'</div>',
                    '<div class="text-center">'.$action.'</div>',
                    $row['min_order'],
                    $row['max_order'],
                    $row['harga'] 
                );
            }

            
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_permintaan_terdaftar($item_id=null, $row_id=null, $satuan_id=null)
    {
        $result = $this->daftar_permintaan_po_m->get_datatable_link_permintaan($item_id);
        // die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            if($row['is_active']==1)
            {
                $tanggal = date('d M Y', strtotime($row['tanggal']));
                $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1),true);
                $action = '<a href="'.base_url().'pembelian/pembelian_alkes/data_daftar_link/'.$item_id.'/'.$row['id'].'/'.$row_id.'/'.$satuan_id.'/'.$row['id_detail'].'/'.$row['id'].'/'.$i.'" data-target="#ajax_notes2" data-toggle="modal" data-tipe="1" data-tipe_pembelian="2" title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';                
                
                $output['aaData'][] = array(
                    '<div class="text-center">'.$row['id'].$row['id_detail'].'</div>',
                    '<div class="text-center">'.$tanggal.'</div>',
                    $row['user'].' ('.$row['user_level'].')',
                    $row['subjek'],
                    $row['kode'],
                    $row['nama_item'],
                    $row['nama_satuan'],
                    $row['jumlah_item'],
                    '<div class="text-center">'.$action.'</div>'

                );
            }

         $i++;
        }

        echo json_encode($output);
    }

    public function modal_perpanjang($id)
    {
        $data = array(
            'id'    => $id
        );
        $this->load->view('pembelian/pembelian_alkes/modals/perpanjang_tanggal.php', $data);
    }

    public function daftar_link($id, $rowId, $satuan_id)
    {
        $data = array(
            'id'    => $id,
            'rowId' => $rowId,
            'satuanId' => $satuan_id
        );
        $this->load->view('pembelian/pembelian_alkes/modals/daftar_link.php', $data);
    }

    public function data_daftar_link($item_id, $row, $row_id, $satuan_id, $id_detail, $id, $i)
    {
        $hasil = $this->daftar_permintaan_po_m->get_data_order_permintaan($item_id, $id)->result();
        $satuan = $this->item_satuan_m->get_by(array('id' => $satuan_id),true);
        // die_dump($hasil);
        $data = array(
            'item_satuan'       => object_to_array($satuan),
            'link_permintaan'    => object_to_array($hasil),
            'row'               => $row,
            'rowId'             => $row_id,
            'id_detail'         => $id_detail,
            'satuan_id'         => $satuan_id,
            'rowPermintaan'     => $i
        );
        // die_dump($data);
        $this->load->view('pembelian/pembelian_alkes/modals/data_daftar_link.php', $data);
    }

    public function save()
    {

        $array_input       = $this->input->post();

        $command           = $array_input['command'];
        $idSupplier        = $array_input['id_supplier'];
        $tanggalPesan      = $array_input['tanggal_pesan'];
        $tipe_bayar        = $array_input['tipe_pembayaran'];
        $tanggalKadaluarsa = $array_input['tanggal_kadaluarsa'];
        $tanggalGaransi    = $array_input['tanggal_garansi'];
        $penerima          = $array_input['id_penerima'];
        $keterangan        = $array_input['keterangan'];
        $tipePenerima      = $array_input['tipe_penerima'];
        $beliItem          = $array_input['items'];
        $saveDraft         = $array_input['save_draft'];
        $penawaran         = $array_input['penawaran'];
        $biaya             = $array_input['biaya'];

        // die(dump($array_input));

        if($command == 'add')
        {
            if($saveDraft == "")
            {
                $supplier      = $this->supplier_m->get($idSupplier);
                $nama_supplier = str_replace(' ', '_', $supplier->nama);

                $tempo = $this->supplier_tipe_pembayaran_m->get($tipe_bayar);
                // die_dump("po");
                
                $last_id       = $this->pembelian_m->get_max_id_pembelian()->result_array();
                $last_id       = intval($last_id[0]['max_id'])+1;
                
                $format_id     = 'PO-'.date('m').'-'.date('Y').'-%04d';
                $id_po         = sprintf($format_id, $last_id, 4);
                
                
                $last_number   = $this->pembelian_m->get_no_pembelian()->result_array();
                $last_number   = intval($last_number[0]['max_no_pembelian'])+1;
                
                
                $format        = '#PO#%03d/RHS-'.strtoupper($supplier->kode).'/'.romanic_number(date('m'), true).'/'.date('Y');
                $no_member     = sprintf($format, $last_number, 3);

                // strtotime(date('Y-m-d H:i:s', strtotime($array_input['tanggal'].' '.$waktu)). " +".$i." week");
                // die_dump($no_member);

                $user_level_login = $this->session->userdata('level_id');
                $data_user_level_persetujuan = $this->setting_persetujuan_po_m->get_by(array('tipe_po' => 2, 'is_active' => 1));
                $user_level_persetujuan = object_to_array($data_user_level_persetujuan);
          
                $data = array(
                    'id'                        => $id_po,
                    'no_pembelian'              => $no_member,
                    'jenis_pembelian'              => 2,
                    'supplier_id'               => $idSupplier,
                    'customer_id'               => $penerima,
                    'tanggal_pesan'             => date('Y-m-d', strtotime($tanggalPesan)),
                    'tanggal_kadaluarsa'        => date('Y-m-d', strtotime($tanggalKadaluarsa)),
                    'tanggal_garansi'           => date('Y-m-d', strtotime($tanggalGaransi)),
                    'is_single_kirim'           => $array_input['is_single'],
                    'keterangan'                => $keterangan,
                    'tipe_customer'             => $tipePenerima,
                    'master_tipe_pembayaran_id' => $tempo->tipe_bayar_id,
                    'tipe_pembayaran'           => $tipe_bayar,
                    'status_keuangan'           => 1,
                    'diskon'                    => $array_input['diskon'],
                    'pph'                       => $array_input['pph'],
                    'ket_tambahan'              => $array_input['ket_tambahan'],
                    'biaya_tambahan'            => $array_input['biaya_tambahan'],
                    'pembulatan'                => $array_input['pembulatan'],
                    'grand_total_po'            => $array_input['grand_total_hidden'],
                    'dp'                        => $array_input['dp'],
                    'sisa_bayar'                => $array_input['sisa_bayar_hidden'],
                    'grand_total'               => $array_input['grand_total_biaya_hidden'],
                    'status_cancel'             => 0,
                    'is_active'                 => 1,
                    'created_by'                => $this->session->userdata('user_id'),
                    'created_date'              => date('Y-m-d H:i:s')
                );

                if($array_input['is_single'] == 1){
                    $data['tanggal_kirim'] = date('Y-m-d', strtotime($array_input['tanggal_kirim']));
                }

                if(count($user_level_persetujuan) != 0){
                    $data['status'] = 1;
                }else{
                    $data['status'] = 3;
                }

                $pembelian_id = $this->pembelian_m->add_data($data);

                $last_id_status       = $this->pembayaran_status_m->get_max_id_pembayaran()->result_array();
                $last_id_status       = intval($last_id_status[0]['max_id'])+1;
                
                $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
                $id_status         = sprintf($format_id_status, $last_id_status, 4);

                $data_status = array(
                    'id'             => $id_status,
                    'transaksi_id'   => $id_po,
                    'transaksi_nomor'   => $no_member,
                    'tipe_transaksi' => 1,
                    'status'         => $data['status'],
                    'is_active'      => 1,
                    'created_by'     => $this->session->userdata('user_id'),
                    'created_date'   => date('Y-m-d H:i:s')
                );

                if($tempo->tipe_bayar_id == 1 || $tempo->tipe_bayar_id == 2){
                    $data_status['nominal'] = $array_input['sisa_bayar_hidden'];

                    if(count($user_level_persetujuan) != 0){

                        $posisi = $user_level_persetujuan[0]['user_level_menyetujui_id'];
                        $divisi_posisi = $this->user_level_m->get($posisi);

                        $data_status['user_level_id'] = $posisi;
                        $data_status['divisi']        = $divisi_posisi->divisi_id;
                        $data_status['nominal'] = $array_input['sisa_bayar_hidden'];

                        $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);

                        foreach ($user_level_persetujuan as $kategori) {
                            $user_level = $this->user_level_m->get($kategori['user_level_menyetujui_id']);

                            $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                            $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                            
                            $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                            $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                            $data_status_detail = array(
                                'id'                   => $id_status_detail,
                                'pembayaran_status_id' => $id_status,
                                'transaksi_id'   => $id_po,
                                'transaksi_nomor'   => $no_member,
                                'tipe_transaksi' => 1,
                                '`order`'              => $kategori['level_order'],
                                'divisi'               => $user_level->divisi_id,
                                'user_level_id'        => $kategori['user_level_menyetujui_id'],
                                'tipe'                 => 1,
                                'tipe_pengajuan'       => 0,
                                'is_active'            => 1,
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                        }
                    }else{
                        $posisi = 21;
                        $divisi_posisi = $this->user_level_m->get(21);

                        $data_status['user_level_id'] = $posisi;
                        $data_status['divisi']        = $divisi_posisi->divisi_id;
                        $data_status['nominal'] = $array_input['sisa_bayar_hidden'];

                        $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);
                    }

                    $order_status = count($user_level_persetujuan);

                    for($i=0; $i<3; $i++){
                        $order_status = $order_status + 1;

                        $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                        $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                        
                        $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                        $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                        if($i == 0 || $i == 2){
                            $user_level = $this->user_level_m->get(21);

                            $divisi_id = $user_level->divisi_id;
                            $user_level_id = 21;
                        }if($i == 1){
                            $user_level = $this->user_level_m->get(5);

                            $divisi_id = $user_level->divisi_id;
                            $user_level_id = 5;
                        }

                        $data_status_detail = array(
                            'id'                   => $id_status_detail,
                            'pembayaran_status_id' => $id_status,
                            'transaksi_id'   => $id_po,
                            'tipe_transaksi' => 1,
                            '`order`'              => $order_status,
                            'divisi'               => $divisi_id,
                            'user_level_id'        => $user_level_id,
                            'tipe'                 => 2,
                            'tipe_pengajuan'       => 0,
                            'is_active'            => 1,
                            'created_by'           => $this->session->userdata('user_id'),
                            'created_date'         => date('Y-m-d H:i:s')
                        );

                        $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                    }
                }elseif($tempo->tipe_bayar_id == 3 && $array_input['dp_nominal'] != 0){
                    $data_status['nominal'] = ($array_input['dp'] / 100) * $array_input['grand_total_hidden'];

                    $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);
                    if(count($user_level_persetujuan) != 0){

                        foreach ($user_level_persetujuan as $kategori) {
                            $user_level = $this->user_level_m->get($kategori['user_level_menyetujui_id']);

                            $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                            $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                            
                            $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                            $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                            $data_status_detail = array(
                                'id'                   => $id_status_detail,
                                'pembayaran_status_id' => $id_status,
                                'transaksi_id'   => $id_po,
                                'tipe_transaksi' => 1,
                                '`order`'              => $kategori['level_order'],
                                'divisi'               => $user_level->divisi_id,
                                'user_level_id'        => $kategori['user_level_menyetujui_id'],
                                'tipe'                 => 1,
                                'tipe_pengajuan'       => 0,
                                'is_active'            => 1,
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                        }
                    }

                    $order_status = count($user_level_persetujuan);

                    for($i=0; $i<3; $i++){
                        $order_status = $order_status + 1;

                        $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                        $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                        
                        $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                        $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                        if($i == 0 || $i == 2){
                            $user_level = $this->user_level_m->get(21);

                            $divisi_id = $user_level->divisi_id;
                            $user_level_id = 21;
                        }if($i == 1){
                            $user_level = $this->user_level_m->get(5);

                            $divisi_id = $user_level->divisi_id;
                            $user_level_id = 5;
                        }

                        $data_status_detail = array(
                            'id'                   => $id_status_detail,
                            'pembayaran_status_id' => $id_status,
                            '`order`'              => $order_status,
                            'divisi'               => $divisi_id,
                            'user_level_id'        => $user_level_id,
                            'tipe'                 => 2,
                            'tipe_pengajuan'       => 0,
                            'is_active'            => 1,
                            'created_by'           => $this->session->userdata('user_id'),
                            'created_date'         => date('Y-m-d H:i:s')
                        );

                        $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                    }
                }

                if($biaya){
                   foreach ($biaya as $row_biaya) {
                        if($row_biaya['jumlah_biaya'] != 0){
                            $last_id_biaya = $this->pembelian_biaya_m->get_max_id_biaya_pembelian()->result_array();
                            $last_id_biaya = intval($last_id_biaya[0]['max_id'])+1;

                            $format_id_biaya   = 'POB-'.date('m').'-'.date('Y').'-%04d';
                            $id_po_biaya       = sprintf($format_id_biaya, $last_id_biaya, 4);

                            $data_biaya = array(
                                'id'           => $id_po_biaya,
                                'pembelian_id' => $id_po,
                                'biaya_id'     => $row_biaya['jenis_biaya'],
                                'nominal'      => $row_biaya['jumlah_biaya'],
                                'is_active'    => 1,
                                'created_by'   => $this->session->userdata('user_id'),
                                'created_date' => date('Y-m-d H:i:s')
                            );

                            $pembelian_biaya = $this->pembelian_biaya_m->add_data($data_biaya); 

                        }
                    } 
                }
                

                $jml_item = 0;
                foreach ($beliItem as $item) 
                {
                    if($item['item_id'] != ""  && $item['item_nama'] != "" && $item['satuan_id'] != ""  )
                    {
                        $jml_item++;
                    }
                }
                $i = 0;
                $y = 1;
                $found = false;
                $z = 0;
                foreach ($beliItem as $key => $item) 
                {
                    if($item['item_id'] != ""  && $item['item_nama'] != "" && $item['satuan_id'] != ""  )
                    {
                        $get_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $item['item_id'], 'is_primary' => 1));
                        $satuan_primary = object_to_array($get_satuan_primary);
                        // die_dump($get_satuan_primary);
                        $nilai_konversi         = $this->item_m->get_nilai_konversi($item['satuan_id']);
                        
                        $harga_satuan = $item['item_sub_total']/(intval($item['jumlah']*$nilai_konversi));

                        $diskon_tambahan = 0;
                        if($array_input['diskon_nominal'] != 0){
                            $diskon_tambahan = (intval($array_input['diskon_nominal'])/$jml_item) / (intval($item['jumlah']*$nilai_konversi));
                        }

                        $tad_tambahan = $harga_satuan - $diskon_tambahan;

                        $ppn_tambahan = 0;
                        if($array_input['pph'] != 0){
                            $ppn_tambahan = (intval($array_input['pph'])/100) * $tad_tambahan;
                        }

                        $tax_tambahan = $tad_tambahan + $ppn_tambahan;

                        $pembulatan = 0;
                        if($array_input['pembulatan'] != 0){
                            $pembulatan = (intval($array_input['pembulatan'])/$jml_item) / (intval($item['jumlah']*$nilai_konversi));
                        }


                        $harga_satuan = $tax_tambahan - $pembulatan;

                        $last_id_detail = $this->pembelian_detail_m->get_max_id_detail_pembelian()->result_array();
                        $last_id_detail = intval($last_id_detail[0]['max_id'])+1;

                        $format_id_detail   = 'POD-'.date('m').'-'.date('Y').'-%04d';
                        $id_po_detail       = sprintf($format_id_detail, $last_id_detail, 4);

                        $data_item = array(
                            'id'                          => $id_po_detail,
                            'pembelian_id'                => $id_po,
                            'item_id'                     => $item['item_id'],
                            'item_satuan_id'              => $item['satuan_id'],
                            'item_satuan_id_primary'      => $satuan_primary[0]['id'],
                            'jumlah_pesan'                => $item['jumlah'],
                            'jumlah_diterima'             => 0,
                            'jumlah_belum_diterima'       => intval($item['jumlah']*$nilai_konversi),
                            'diskon'                      => $item['item_diskon'],
                            'diskon_tambahan_primary'     => $diskon_tambahan,
                            'ppn_tambahan_primary'        => $ppn_tambahan,
                            'pembulatan_tambahan_primary' => $pembulatan,
                            'harga_beli_primary'          => $harga_satuan,
                            'tanggal_kirim'               => date('Y-m-d', strtotime($item['item_tanggal_kirim'])),
                            'harga_beli'                  => $item['item_harga'],
                            'urutan'                      => $y,
                            'is_active'                   => 1,
                            'created_by'                  => $this->session->userdata('user_id'),
                            'created_date'                => date('Y-m-d H:i:s')

                        );
                        
                        if(count($user_level_persetujuan) != 0){
                            $data_item['status'] = 1;

                            foreach ($user_level_persetujuan as $kategori) 
                            {
                               
                                $last_id = $this->persetujuan_po_m->get_last_id()->result_array();

                                $data_persetujuan = array(
                                    'persetujuan_pembelian_id' => (count($last_id) == 0)?'1':intval($last_id[0]['last_id'])+1,
                                    'pembelian_id'             => $id_po,
                                    'pembelian_detail_id'      => $id_po_detail,
                                    'user_level_id'            => $kategori['user_level_menyetujui_id'],
                                    '`order`'                  => $kategori['level_order'],
                                    '`status`'                 => 1,
                                    'is_active'                => 1,
                                    'created_by'               => $this->session->userdata('user_id'),
                                    'created_date'             => date('Y-m-d H:i:s')
                                );

                                $save_persetujuan = $this->persetujuan_po_m->add_data($data_persetujuan);


                            }

                        }else{
                            $data_item['status'] = 2;
                            $data_item['jumlah_disetujui'] = $item['jumlah'];
                            $data_item['disetujui_oleh']   = 0;
                        }
                        
                        $pembelian_detail = $this->pembelian_detail_m->add_data($data_item);

                        $data_os_pesan = $this->o_s_pmsn_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['satuan_id'], 'status' => 1));
                        $data_os_pesan = object_to_array($data_os_pesan);

                        $x = 1;
                        $sisa = 0;
                        foreach ($data_os_pesan as $row_os_pesan) {
                            if($x == 1 && $item['jumlah'] >= $row_os_pesan['jumlah']){

                                $sisa = $item['jumlah'] - $row_os_pesan['jumlah'];
                                $sisa_os = 0;

                                $array_os = array(
                                    'status' => 2,
                                );

                                $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);
                            }
                            if($x == 1 && $item['jumlah'] < $row_os_pesan['jumlah']){

                                $sisa = 0;
                                $sisa_os = $row_os_pesan['jumlah'] - $item['jumlah'];

                                $array_os = array(
                                    'jumlah' => $sisa_os,
                                    'status' => 1,
                                );

                                $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);
                            }
                            if($x != 1 && $sisa > 0 && $sisa >= $row_os_pesan['jumlah']){
                                
                                $sisa = $sisa - $row_os_pesan['jumlah'];
                                $sisa_os = 0;

                                $array_os = array(
                                    'status' => 2,
                                );

                                $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);
                            }

                            if($x != 1 && $sisa > 0 && $sisa < $row_os_pesan['jumlah']){

                                $sisa_os = $row_os_pesan['jumlah'] - $sisa;
                                $sisa = 0;

                                $array_os = array(
                                    'jumlah' => $sisa_os,
                                    'status' => 1,
                                );

                                $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);
                            }
                            $x++;
                        }

                        $item_tgl_kirim = $array_input['input_jumlah_'.$key]; 

                        if($array_input['is_single'] == 1){

                            $last_id_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->get_max_id_detail_kirim()->result_array();
                            $last_id_detail_kirim = intval($last_id_detail_kirim[0]['max_id'])+1;

                            $format_id_detail_kirim   = 'PODT-'.date('m').'-'.date('Y').'-%04d';
                            $id_po_detail_kirim       = sprintf($format_id_detail_kirim, $last_id_detail_kirim, 4);

                            $data_tanggal_kirim = array(
                                'id'                  => $id_po_detail_kirim, 
                                'pembelian_id'        => $id_po, 
                                'pembelian_detail_id' => $id_po_detail, 
                                'jumlah_kirim'        => $item['jumlah'], 
                                'tanggal_kirim'       => date('Y-m-d', strtotime($array_input['tanggal_kirim'])),
                                'created_by'               => $this->session->userdata('user_id'),
                                'created_date'             => date('Y-m-d H:i:s')
                            );

                            $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->add_data($data_tanggal_kirim); 
                        }

                        if($array_input['is_single'] == 0){

                            foreach ($item_tgl_kirim as $keys => $tgl_kirim) {
                                $last_id_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->get_max_id_detail_kirim()->result_array();
                                $last_id_detail_kirim = intval($last_id_detail_kirim[0]['max_id'])+1;

                                $format_id_detail_kirim   = 'PODT-'.date('m').'-'.date('Y').'-%04d';
                                $id_po_detail_kirim       = sprintf($format_id_detail_kirim, $last_id_detail_kirim, 4);

                                $data_tanggal_kirim = array(
                                    'id'                  => $id_po_detail_kirim, 
                                    'pembelian_id'        => $id_po, 
                                    'pembelian_detail_id' => $id_po_detail, 
                                    'jumlah_kirim'        => $tgl_kirim['jumlah_kirim'], 
                                    'tanggal_kirim'       => date('Y-m-d', strtotime($tgl_kirim['tanggal_kirim'])),
                                    'created_by'          => $this->session->userdata('user_id'),
                                    'created_date'        => date('Y-m-d H:i:s')
                                );

                                $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->add_data($data_tanggal_kirim); 
  
                            }
                        }
                    
                    }

                    //proses input persetujuan jika diambil per sub kategori
                    // $get_data = $this->item_m->get($item['item_id']);
                    // // die_dump($get_data);

                    // $get_sub_kategori = $this->item_sub_kategori_pembelian_m->get_by(array('item_sub_kategori_id' => $get_data->item_sub_kategori));
                    // $sub_kategori = object_to_array($get_sub_kategori);
                    
                    // // die_dump(count($get_sub_kategori));

                    // if(count($get_sub_kategori) === 0)
                    // {
                    //     $z++;
                    //     $data_item['status']           = 2;
                    //     $data_item['jumlah_disetujui'] = $item['jumlah'];
                    //     $data_item['disetujui_oleh']   = 0;

                    // }
                    // elseif(count($get_sub_kategori) != 0)
                    // {
                    //     $data_item['status']           = 1;

                    //     $upd_stat = array(
                    //         'status' => 1
                    //     );
                    //     $update = $this->pembelian_m->edit_data($upd_stat, $id_po);

                    //     foreach ($sub_kategori as $kategori) 
                    //     {
                           
                    //         $last_id = $this->persetujuan_po_m->get_last_id()->result_array();

                    //         $data_persetujuan = array(
                    //             'persetujuan_pembelian_id' => (count($last_id) == 0)?'1':intval($last_id[0]['last_id'])+1,
                    //             'pembelian_id'             => $id_po,
                    //             'pembelian_detail_id'      => $id_po_detail,
                    //             'user_level_id'            => $kategori['user_level_menyetujui_id'],
                    //             '`order`'                  => $kategori['level_order'],
                    //             '`status`'                 => 1,
                    //             'is_active'                 => 1,
                    //             'created_by'               => $this->session->userdata('user_id'),
                    //             'created_date'             => date('Y-m-d H:i:s')
                    //         );

                    //         $save_persetujuan = $this->persetujuan_po_m->add_data($data_persetujuan);
                    //         // die(dump($this->db->last_query()));
                    //     }
                    // }

                    // $pembelian_detail = $this->pembelian_detail_m->add_data($data_item); 



                    if($array_input['link_'.$i])
                    {
                        foreach ($array_input['link_'.$i] as $data) {
                            $jumlah_item_link = explode(' ', $data['jumlah_item']); 
                            $jumlah_item = $jumlah_item_link[0];

                            $data_save_link = array(
                                'pembelian_detail_id'                  => $id_po_detail,
                                'order_permintaan_pembelian_detail_id' => $data['id_detail'],
                                'tipe_pembelian'                       => 1,
                                'tipe_permintaan'                      => $data['tipe_permintaan'],
                                'jumlah'                               => $jumlah_item,
                                'satuan_id'                            => $item['satuan_id'],
                                'is_active'                            => 1
                            );

                            $link_permintaan_pembelian = $this->link_permintaan_pembelian_m->save($data_save_link);
                            // die_dump($data);
                        }
                    }

                    $get_harga_supplier = $this->supplier_item_m->get_data_harga($idSupplier,$item['item_id'],$item['satuan'],date('Y-m-d'))->result_array();
                   
                    if($get_harga_supplier[0]['tanggal_efektif'] != date('Y-m-d')){
                        $data_harga_baru = array(
                            'supplier_item_id' => $get_harga_supplier[0]['id'],
                            'harga'            => $item['item_harga'],
                            'tanggal_efektif'  => date('Y-m-d')
                        );

                        $harga_baru = $this->supplier_harga_item_m->save($data_harga_baru);
                    }
                    elseif($get_harga_supplier[0]['tanggal_efektif'] == date('Y-m-d')){
                        $data_harga_baru = array(
                            'harga'            => $item['item_harga'],
                        );

                        $harga_baru = $this->supplier_harga_item_m->save($data_harga_baru, $get_harga_supplier[0]['id_harga']);
                    }
                    
                    
                    
                    
                    $y++;
                    $i++;
                    // if($z === $i)
                    // {
                    //     $upd_stat = array(
                    //         'status' => 3 //langsung disetujui jika tidak ada item yang harus melewati persetujuan
                    //     );
                    //     $update = $this->pembelian_m->edit_data($upd_stat, $id_po);
                    // }
                }

                $data_po = $this->pembelian_m->get_by(array('id' => $id_po), true);

                if($data_po->status == 3){
                    if($tempo->tipe_bayar_id == 1 || $tempo->tipe_bayar_id == 2){
                        if($array_input['setoran'] == '' || $array_input['setoran'] == 0){
                            if($array_input['dp'] != 0){

                                $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                $data_o_s = array(
                                    'id'           => $id_os_po,
                                    'pembelian_id' => $id_po,
                                    'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                    'nominal'      => $array_input['dp_nominal'],
                                    'status'       => 1,
                                    'created_by'         => $this->session->userdata('user_id'),
                                    'created_date'       => date('Y-m-d H:i:s')
                                );
                                $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                            }if($array_input['dp'] == 0){

                                $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                $data_o_s = array(
                                    'id'           => $id_os_po,
                                    'pembelian_id' => $id_po,
                                    'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                    'nominal'      => $array_input['sisa_bayar_hidden'],
                                    'status'       => 1,
                                    'created_by'         => $this->session->userdata('user_id'),
                                    'created_date'       => date('Y-m-d H:i:s')
                                );
                                $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                            }
                        }elseif($array_input['setoran'] != '' && $array_input['setoran'] != 0){
                            if($array_input['dp'] != 0){

                                $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                $data_o_s = array(
                                    'id'           => $id_os_po,
                                    'pembelian_id' => $id_po,
                                    'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                    'nominal'      => $array_input['dp_nominal'],
                                    'status'       => 1,
                                    'created_by'         => $this->session->userdata('user_id'),
                                    'created_date'       => date('Y-m-d H:i:s')
                                );
                                $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                            }
                            for($x=1;$x<=$array_input['kelipatan'];$x++){
                                $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                $tipe_time = ' days';
                                if($array_input['jenis_bayar'] == 1){
                                    $tipe_time = ' days';
                                }if($array_input['jenis_bayar'] == 2){
                                    $tipe_time = ' month';
                                }if($array_input['jenis_bayar'] == 3){
                                    $tipe_time = ' year';
                                }
                                $data_o_s = array(
                                    'id'           => $id_os_po,
                                    'pembelian_id' => $id_po,
                                    'tanggal'      => date('Y-m-d', strtotime($tanggalPesan.' +'.$x.$tipe_time)),
                                    'nominal'      => $array_input['setoran'],
                                    'status'       => 1,
                                    'created_by'         => $this->session->userdata('user_id'),
                                    'created_date'       => date('Y-m-d H:i:s')
                                );
                                $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                            }
                        }
                    }if($tempo->tipe_bayar_id == 3){
                        if($array_input['setoran'] == '' || $array_input['setoran'] == 0){
                            if($array_input['dp'] != 0){

                                $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                $data_o_s = array(
                                    'id'           => $id_os_po,
                                    'pembelian_id' => $id_po,
                                    'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                    'nominal'      => $array_input['dp_nominal'],
                                    'status'       => 1,
                                    'created_by'         => $this->session->userdata('user_id'),
                                    'created_date'       => date('Y-m-d H:i:s')
                                );
                                $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                            }
                        }elseif($array_input['setoran'] != '' && $array_input['setoran'] != 0){
                            if($array_input['dp'] != 0){

                                $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                $data_o_s = array(
                                    'id'           => $id_os_po,
                                    'pembelian_id' => $id_po,
                                    'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                    'nominal'      => $array_input['dp_nominal'],
                                    'status'       => 1,
                                    'created_by'         => $this->session->userdata('user_id'),
                                    'created_date'       => date('Y-m-d H:i:s')
                                );
                                $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                            }
                            for($x=1;$x<=$array_input['kelipatan'];$x++){
                                $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                $tipe_time = ' days';
                                if($array_input['jenis_bayar'] == 1){
                                    $tipe_time = ' days';
                                }if($array_input['jenis_bayar'] == 2){
                                    $tipe_time = ' month';
                                }if($array_input['jenis_bayar'] == 3){
                                    $tipe_time = ' year';
                                }
                                $data_o_s = array(
                                    'id'           => $id_os_po,
                                    'pembelian_id' => $id_po,
                                    'tanggal'      => date('Y-m-d', strtotime($tanggalPesan.' +'.$x.$tipe_time)),
                                    'nominal'      => $array_input['setoran'],
                                    'status'       => 1,
                                    'created_by'         => $this->session->userdata('user_id'),
                                    'created_date'       => date('Y-m-d H:i:s')
                                );
                                $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                            }
                        }
                    }
                }

                if($penawaran)
                {
                    foreach ($penawaran as $row) 
                    {
                        if($row['nomor'] != '' || $row['url'] != '')
                        {
                            $last_id_penawaran = $this->pembelian_penawaran_m->get_max_id_pembelian_penawaran()->result_array();
                            $last_id_penawaran = intval($last_id_penawaran[0]['max_id'])+1;

                            $format_id_penawaran   = 'POQ-'.date('m').'-'.date('Y').'-%04d';
                            $id_po_penawaran      = sprintf($format_id_penawaran, $last_id_penawaran, 4);

                            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/pembelian/pembelian_alkes/doc/penawaran/'.str_replace(' ', '_', $id_po).'/'.str_replace(' ', '_', $id_po_penawaran);
                            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                            $temp_filename = $row['url'];

                            $convtofile = new SplFileInfo($temp_filename);
                            $extenstion = ".".$convtofile->getExtension();

                            $new_filename = $row['url'];
                            $real_file = str_replace(' ', '_', $id_po).'/'.str_replace(' ', '_', $id_po_penawaran).'/'.$new_filename;

                            copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_doc_penawaran').$real_file);     
                            
                            $data_penawaran = array(
                                'id'                    => $id_po_penawaran,
                                'pembelian_id'          => $id_po,
                                'supplier_penawaran_id' => '',
                                'nomor_penawaran'       => $row['nomor'],
                                'url'                   => $row['url'],
                                'status'                => 1,
                                'is_active'             => 1,
                                'created_by'             => $this->session->userdata('user_id'),
                                'created_date'           => date('Y-m-d H:i:s')
                            );

                            $penawaran = $this->pembelian_penawaran_m->add_data($data_penawaran);

                        }
                    }
                }

                if($array_input['setoran'] != '' && $array_input['setoran'] != 0){
                    $last_id_kredit = $this->pembelian_kredit_m->get_max_id_pembelian_kredit()->result_array();
                    $last_id_kredit = intval($last_id_kredit[0]['max_id'])+1;

                    $format_id_kredit   = 'POK-'.date('m').'-'.date('Y').'-%04d';
                    $id_po_kredit      = sprintf($format_id_kredit, $last_id_kredit, 4);

                    $data_kredit = array(
                        'pembelian_kredit_id' => $id_po_kredit,
                        'pembelian_id'        => $id_po,
                        'tenor'               => $array_input['lama_tenor'],
                        'jenis_tenor'         => $array_input['jenis_tenor'],
                        'jenis_bayar'         => $array_input['jenis_bayar'],
                        'kelipatan'           => $array_input['kelipatan'],
                        'bunga'               => $array_input['bunga_persen'],
                        'harga_setoran'       => $array_input['setoran']
                    );

                    $po_kredit = $this->pembelian_kredit_m->add_data($data_kredit);
                }

                sent_notification_po(2,$nama_supplier, 1);

                $filename = urlencode(base64_encode($this->session->userdata('url_login')));

                $all_cabang = $this->cabang_m->get();

                foreach ($all_cabang as $cabang) 
                {
                    if($cabang->url != NULL || $cabang->url != '')
                    {
                        change_file_notif($cabang->url,$filename);                       
                    }
                }
            }
            else
            {
                // die_dump("draft_po");

                $data_draft = array(
                    'supplier_id'        => $idSupplier,
                    'customer_id'        => $penerima,
                    'tipe_customer'      => $tipePenerima,
                    'tanggal_pesan'      => date('Y-m-d', strtotime($tanggalPesan)),
                    'tanggal_kadaluarsa' => date('Y-m-d', strtotime($tanggalKadaluarsa)),
                    'tanggal_garansi'    => date('Y-m-d', strtotime($tanggalGaransi)),
                    'keterangan'         => $keterangan,
                    'tipe_customer'      => $tipePenerima,
                    'tipe_pembayaran'    => $tipe_bayar,
                    'is_po'              => 0,
                    'is_active'          => 1,
                );
                // die_dump($data_draft);
                $draft_pembelian_id = $this->draft_po_m->save($data_draft);
                
                $i = 0;
                $y = 1;
                foreach ($beliItem as $item) 
                {
                    if($item['item_id'] != "" && $item['item_kode'] != "" && $item['item_nama'] != "" && $item['satuan_id'] != ""  )
                    {
                        $data_item = array(
                            'draf_pembelian_id' => $draft_pembelian_id,
                            'item_id'                => $item['item_id'],
                            'item_satuan_id'         => $item['satuan_id'],
                            'item_satuan_id_primary' => $satuan_primary[0]['id'],
                            'jumlah_pesan'           => $item['jumlah'],
                            'diskon'                 => $item['item_diskon'],
                            'tanggal_kirim'          => date('Y-m-d', strtotime($item['item_tanggal_kirim'])),
                            'harga_beli'             => $item['item_harga'],
                            'urutan'                 => $y,
                            'is_active'              => 1
                        );
                        // die_dump($data_item);
                        // die_dump($item);
                        $draft_pembelian_detail = $this->draf_po_detail_m->save($data_item); 
                    }    

                    if($array_input['link_'.$i])
                    {
                        foreach ($array_input['link_'.$i] as $data) {
                            $jumlah_item_link = explode(' ', $data['jumlah_item']); 
                            $jumlah_item = $jumlah_item_link[0];
                            // die_dump($jumlah_item_link);
                             $data_save_link = array(
                                'pembelian_detail_id'                  => $draft_pembelian_detail,
                                'order_permintaan_pembelian_detail_id' => $data['id_detail'],
                                'tipe_pembelian'                       => 2,
                                'tipe_permintaan'                      => $data['tipe_permintaan'],
                                'jumlah'                               => $jumlah_item,
                                'satuan_id'                            => $item['satuan_id'],
                                'is_active'                            => 1
                            );
                             // die_dump($data_save_link);

                             $save_draf_link = $this->link_permintaan_pembelian_m->save($data_save_link);
                             // die_dump($this->db->last_query());
                        }
                    }
                $y++;                                    
                $i++;                                    
                }
            }
        }
        else if($command == 'edit')
        {
            if($saveDraft == "")
            {
                $pembelian_id = $array_input['pembelian_id'];
          
                $user_level_login = $this->session->userdata('level_id');
                $data_user_level_persetujuan = $this->setting_persetujuan_po_m->get_by(array('tipe_po' => 2, 'is_active' => 1));
                $user_level_persetujuan = object_to_array($data_user_level_persetujuan);

                $tempo = $this->supplier_tipe_pembayaran_m->get($tipe_bayar);
          
                $data = array(
                    'supplier_id'               => $idSupplier,
                    'customer_id'               => $penerima,
                    'jenis_pembelian'           => 2,
                    'tanggal_pesan'             => date('Y-m-d', strtotime($tanggalPesan)),
                    'tanggal_kadaluarsa'        => date('Y-m-d', strtotime($tanggalKadaluarsa)),
                    'tanggal_garansi'           => date('Y-m-d', strtotime($tanggalGaransi)),
                    'is_single_kirim'           => $array_input['is_single'],
                    'keterangan'                => $keterangan,
                    'tipe_customer'             => $tipePenerima,
                    'master_tipe_pembayaran_id' => $tempo->tipe_bayar_id,
                    'tipe_pembayaran'           => $tipe_bayar,
                    'status_keuangan'           => 1,
                    'diskon'                    => $array_input['diskon'],
                    'pph'                       => $array_input['pph'],
                    'ket_tambahan'              => $array_input['ket_tambahan'],
                    'biaya_tambahan'            => $array_input['biaya_tambahan'],
                    'pembulatan'                => $array_input['pembulatan'],
                    'grand_total_po'            => $array_input['grand_total_hidden'],
                    'dp'                        => $array_input['dp'],
                    'sisa_bayar'                => $array_input['sisa_bayar_hidden'],
                    'grand_total'               => $array_input['grand_total_biaya_hidden'],
                    'status_cancel'             => 0,
                    'is_active'                 => 1,
                    'created_by'                => $this->session->userdata('user_id'),
                    'created_date'              => date('Y-m-d H:i:s')
                );

                if($array_input['is_single'] == 1){
                    $data['tanggal_kirim'] = date('Y-m-d', strtotime($array_input['tanggal_kirim']));
                }

                if(count($user_level_persetujuan) != 0){
                    $data['status'] = 1;
                }else{
                    $data['status'] = 3;
                }
                $edit_pembelian = $this->pembelian_m->edit_data($data, $pembelian_id);

                $jml_item = 0;
                foreach ($beliItem as $item) 
                {
                    if($item['item_id'] != ""  && $item['item_nama'] != "" && $item['satuan_id'] != ""  )
                    {
                        $jml_item++;
                    }
                }

                $i = 0;
                $y = 1;
                $found = false;
                $z = 0;
                foreach ($beliItem as $key => $item) 
                {
                    if($item['item_id'] != "" && $item['item_kode'] != "" && $item['item_nama'] != "")
                    {
                        if($item['id'] == '' && $item['is_active'] == 1)
                        {
                            $get_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $item['item_id'], 'is_primary' => 1));
                            $satuan_primary = object_to_array($get_satuan_primary);
                            // die_dump($get_satuan_primary);
                            $nilai_konversi         = $this->item_m->get_nilai_konversi($item['satuan_id']);

                            $harga_satuan = $item['item_sub_total']/(intval($item['jumlah']*$nilai_konversi));

                            $diskon_tambahan = 0;
                            if($array_input['diskon_nominal'] != 0){
                                $diskon_tambahan = (intval($array_input['diskon_nominal'])/$jml_item) / (intval($item['jumlah']*$nilai_konversi));
                            }

                            $tad_tambahan = $harga_satuan - $diskon_tambahan;

                            $ppn_tambahan = 0;
                            if($array_input['pph'] != 0){
                                $ppn_tambahan = (intval($array_input['pph'])/100) * $tad_tambahan;
                            }

                            $tax_tambahan = $tad_tambahan + $ppn_tambahan;

                            $pembulatan = 0;
                            if($array_input['pembulatan'] != 0){
                                $pembulatan = (intval($array_input['pembulatan'])/$jml_item) / (intval($item['jumlah']*$nilai_konversi));
                            }


                            $harga_satuan = $tax_tambahan - $pembulatan;
                            


                            $last_id_detail = $this->pembelian_detail_m->get_max_id_detail_pembelian()->result_array();
                            $last_id_detail = intval($last_id_detail[0]['max_id'])+1;

                            $format_id_detail   = 'POD-'.date('m').'-'.date('Y').'-%04d';
                            $id_po_detail       = sprintf($format_id_detail, $last_id_detail, 4);

                            $data_item = array(
                                'id'                      => $id_po_detail,
                                'pembelian_id'            => $pembelian_id,
                                'item_id'                 => $item['item_id'],
                                'item_satuan_id'          => $item['satuan_id'],
                                'item_satuan_id_primary'  => $satuan_primary[0]['id'],
                                'jumlah_pesan'            => $item['jumlah'],
                                'jumlah_diterima'         => 0,
                                'jumlah_belum_diterima'   => intval($item['jumlah']*$nilai_konversi),
                                'diskon'                  => $item['item_diskon'],
                                'diskon_tambahan_primary' => $diskon_tambahan,
                                'ppn_tambahan_primary'    => $ppn_tambahan,
                                'pembulatan_tambahan_primary'    => $pembulatan,
                                'harga_beli_primary'      => $harga_satuan,
                                'tanggal_kirim'           => date('Y-m-d', strtotime($item['item_tanggal_kirim'])),
                                'harga_beli'              => $item['item_harga'],
                                'urutan'                  => $y,
                                'is_active'               => 1,
                                'created_by'              => $this->session->userdata('user_id'),
                                'created_date'            => date('Y-m-d H:i:s')

                            );

                            if(count($user_level_persetujuan) != 0){
                                $data_item['status'] = 1;

                                foreach ($user_level_persetujuan as $kategori) 
                                {
                                   
                                    $last_id = $this->persetujuan_po_m->get_last_id()->result_array();

                                    $data_persetujuan = array(
                                        'persetujuan_pembelian_id' => (count($last_id) == 0)?'1':intval($last_id[0]['last_id'])+1,
                                        'pembelian_id'             => $id_po,
                                        'pembelian_detail_id'      => $id_po_detail,
                                        'user_level_id'            => $kategori['user_level_menyetujui_id'],
                                        '`order`'                  => $kategori['level_order'],
                                        '`status`'                 => 1,
                                        'is_active'                => 1,
                                        'created_by'               => $this->session->userdata('user_id'),
                                        'created_date'             => date('Y-m-d H:i:s')
                                    );

                                    $save_persetujuan = $this->persetujuan_po_m->add_data($data_persetujuan);


                                }

                            }else{
                                $data_item['status'] = 2;
                                $data_item['jumlah_disetujui'] = $item['jumlah'];
                                $data_item['disetujui_oleh']   = 0;
                            }
                            
                            $pembelian_detail = $this->pembelian_detail_m->add_data($data_item); 

                            $item_tgl_kirim = $array_input['input_jumlah_'.$key];
                    
                            if($array_input['is_single'] == 1 && $item['id'] == '' && $item['is_active'] == 1){

                                $last_id_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->get_max_id_detail_kirim()->result_array();
                                $last_id_detail_kirim = intval($last_id_detail_kirim[0]['max_id'])+1;

                                $format_id_detail_kirim   = 'PODT-'.date('m').'-'.date('Y').'-%04d';
                                $id_po_detail_kirim       = sprintf($format_id_detail_kirim, $last_id_detail_kirim, 4);

                                $data_tanggal_kirim = array(
                                    'id'                  => $id_po_detail_kirim, 
                                    'pembelian_id'        => $pembelian_id, 
                                    'pembelian_detail_id' => $id_po_detail, 
                                    'jumlah_kirim'        => $item['jumlah'], 
                                    'tanggal_kirim'       => date('Y-m-d', strtotime($array_input['tanggal_kirim'])),
                                    'created_by'               => $this->session->userdata('user_id'),
                                    'created_date'             => date('Y-m-d H:i:s')
                                );

                                $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->add_data($data_tanggal_kirim); 
                            }

                            if($array_input['is_single'] == 0 && $item['id'] == '' && $item['is_active'] == 1){

                                foreach ($item_tgl_kirim as $keys => $tgl_kirim) {
                                    $last_id_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->get_max_id_detail_kirim()->result_array();
                                    $last_id_detail_kirim = intval($last_id_detail_kirim[0]['max_id'])+1;

                                    $format_id_detail_kirim   = 'PODT-'.date('m').'-'.date('Y').'-%04d';
                                    $id_po_detail_kirim       = sprintf($format_id_detail_kirim, $last_id_detail_kirim, 4);

                                    $data_tanggal_kirim = array(
                                        'id'                  => $id_po_detail_kirim, 
                                        'pembelian_id'        => $pembelian_id, 
                                        'pembelian_detail_id' => $id_po_detail, 
                                        'jumlah_kirim'        => $tgl_kirim['jumlah_kirim'], 
                                        'tanggal_kirim'       => date('Y-m-d', strtotime($tgl_kirim['tanggal_kirim'])),
                                        'created_by'          => $this->session->userdata('user_id'),
                                        'created_date'        => date('Y-m-d H:i:s')
                                    );

                                    $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->add_data($data_tanggal_kirim); 
      
                                }
                            }
                                                    
                        }
                        else if($item['id'] != '' && $item['is_active'] == 0)
                        {
                            $data_item = array(
                                'is_active'    => 0,
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_date' => date('Y-m-d H:i:s')
                            );
                            $pembelian_detail = $this->pembelian_detail_m->edit_data($data_item, $item['id']);

                            $where_setuju = array(
                                'pembelian_detail_id' => $item['id']
                            );

                            $this->persetujuan_po_m->delete_by($where_setuju);

                            $wheres_id_detail['pembelian_detail_id'] = $item['id'];
                            $delete_kirim_item = $this->pembelian_detail_tanggal_kirim_m->delete_by($wheres_id_detail);
                        }
                        else if($item['id'] != '' && $item['is_active'] == 1)
                        {
                            $data_item = array(
                                'item_id'                => $item['item_id'],
                                'item_satuan_id'         => $item['satuan_id'],
                                'item_satuan_id_primary' => $satuan_primary[0]['id'],
                                'jumlah_pesan'           => $item['jumlah'],
                                'jumlah_diterima'        => 0,
                                'jumlah_belum_diterima'  => intval($item['jumlah']*$nilai_konversi),
                                'diskon'                 => $item['item_diskon'],
                                'tanggal_kirim'          => date('Y-m-d', strtotime($item['item_tanggal_kirim'])),
                                'harga_beli'             => $item['item_harga'],
                                'urutan'                 => $y,
                                'is_active'              => 1,
                                'modified_by'            => $this->session->userdata('user_id'),
                                'modified_date'          => date('Y-m-d H:i:s')

                            );
                            
                            if(count($user_level_persetujuan) != 0){
                                $data_persetujuan = $this->persetujuan_po_m->get_by(array('pembelian_detail_id' => $item['id']));
                                if(count($data_persetujuan) == 0){
                                    $data_item['status'] = 1;

                                    foreach ($user_level_persetujuan as $kategori) 
                                    {
                                       
                                        $last_id = $this->persetujuan_po_m->get_last_id()->result_array();

                                        $data_persetujuan = array(
                                            'persetujuan_pembelian_id' => (count($last_id) == 0)?'1':intval($last_id[0]['last_id'])+1,
                                            'pembelian_id'             => $id_po,
                                            'pembelian_detail_id'      => $id_po_detail,
                                            'user_level_id'            => $kategori['user_level_menyetujui_id'],
                                            '`order`'                  => $kategori['level_order'],
                                            '`status`'                 => 1,
                                            'is_active'                => 1,
                                            'created_by'               => $this->session->userdata('user_id'),
                                            'created_date'             => date('Y-m-d H:i:s')
                                        );

                                        $save_persetujuan = $this->persetujuan_po_m->add_data($data_persetujuan);
                                    }
                                }
                            }
                            
                            
                            $pembelian_detail = $this->pembelian_detail_m->edit_data($data_item, $item['id']); 

                            $item_tgl_kirim = $array_input['input_jumlah_'.$key];
                    
                            if($array_input['is_single'] == 1 && $item['id'] != '' && $item['is_active'] == 1){

                                $cek_data_kirim = $this->pembelian_detail_tanggal_kirim_m->get_by(array('pembelian_detail_id' => $item['id']));
                                // die(dump(count($cek_data_kirim)));
                                
                                if(count($cek_data_kirim) == 0){
                                    $last_id_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->get_max_id_detail_kirim()->result_array();
                                    $last_id_detail_kirim = intval($last_id_detail_kirim[0]['max_id'])+1;

                                    $format_id_detail_kirim   = 'PODT-'.date('m').'-'.date('Y').'-%04d';
                                    $id_po_detail_kirim       = sprintf($format_id_detail_kirim, $last_id_detail_kirim, 4);

                                    $data_tanggal_kirim = array(
                                        'id'                  => $id_po_detail_kirim, 
                                        'pembelian_id'        => $pembelian_id, 
                                        'pembelian_detail_id' => $item['id'], 
                                        'jumlah_kirim'        => $item['jumlah'], 
                                        'tanggal_kirim'       => date('Y-m-d', strtotime($array_input['tanggal_kirim'])),
                                        'created_by'               => $this->session->userdata('user_id'),
                                        'created_date'             => date('Y-m-d H:i:s')
                                    );

                                    $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->add_data($data_tanggal_kirim); 
                                }elseif(count($cek_data_kirim) != 0){

                                    foreach ($cek_data_kirim as $dt_kirim) {
                                        $data_tanggal_kirim = array(
                                            'jumlah_kirim'  => $item['jumlah'], 
                                            'tanggal_kirim' => date('Y-m-d', strtotime($array_input['tanggal_kirim'])),
                                            'modified_by'    => $this->session->userdata('user_id'),
                                            'modified_date'  => date('Y-m-d H:i:s')
                                        );

                                        $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->edit_data($data_tanggal_kirim, $dt_kirim->id);
                                    }
                                }
                                
                            }

                            if($array_input['is_single'] == 0){

                                foreach ($item_tgl_kirim as $keys => $tgl_kirim) {

                                    if($tgl_kirim['id_kirim'] == '' && $tgl_kirim['is_active'] == 1){

                                        $last_id_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->get_max_id_detail_kirim()->result_array();
                                        $last_id_detail_kirim = intval($last_id_detail_kirim[0]['max_id'])+1;

                                        $format_id_detail_kirim   = 'PODT-'.date('m').'-'.date('Y').'-%04d';
                                        $id_po_detail_kirim       = sprintf($format_id_detail_kirim, $last_id_detail_kirim, 4);

                                        $data_tanggal_kirim = array(
                                            'id'                  => $id_po_detail_kirim, 
                                            'pembelian_id'        => $pembelian_id, 
                                            'pembelian_detail_id' => $item['id'], 
                                            'jumlah_kirim'        => $tgl_kirim['jumlah_kirim'], 
                                            'tanggal_kirim'       => date('Y-m-d', strtotime($tgl_kirim['tanggal_kirim'])),
                                            'created_by'          => $this->session->userdata('user_id'),
                                            'created_date'        => date('Y-m-d H:i:s')
                                        );

                                        $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->add_data($data_tanggal_kirim); 

                                    }if($tgl_kirim['id_kirim'] != '' && $tgl_kirim['is_active'] == 1){

                                        $data_tanggal_kirim = array(
                                            'jumlah_kirim'        => $tgl_kirim['jumlah_kirim'], 
                                            'tanggal_kirim'       => date('Y-m-d', strtotime($tgl_kirim['tanggal_kirim'])),
                                            'modified_by'          => $this->session->userdata('user_id'),
                                            'modified_date'        => date('Y-m-d H:i:s')
                                        );

                                        $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->edit_data($data_tanggal_kirim, $tgl_kirim['id_kirim']); 
                                    }if($tgl_kirim['id_kirim'] != '' && $tgl_kirim['is_active'] == 0){

                                        $data_tanggal_kirim = array(
                                            'id'        => $tgl_kirim['id_kirim']
                                        );

                                        $pembelian_detail_kirim = $this->pembelian_detail_tanggal_kirim_m->delete_by($data_tanggal_kirim); 
                                    }
      
                                }
                            }
                        }

                        foreach ($linkData as $data) {
                            $jumlah_item_link = explode(' ', $data['jumlah_item']); 
                            $jumlah_item = $jumlah_item_link[0];

                            $data_save_link = array(
                                'pembelian_detail_id'                  => $item['id'],
                                'order_permintaan_pembelian_detail_id' => $data['id_detail'],
                                'tipe_pembelian'                       => 1,
                                'tipe_permintaan'                      => $data['tipe_permintaan'],
                                'jumlah'                               => $jumlah_item,
                                'satuan_id'                            => $item['satuan'],
                                'is_active'                            => 1
                            );
                            $save_draf_link = $this->link_permintaan_pembelian_m->save($data_save_link);

                        } 
                    } 

                    $get_harga_supplier = $this->supplier_item_m->get_data_harga($idSupplier,$item['item_id'],$item['satuan'],date('Y-m-d'))->result_array();
                   
                    if($get_harga_supplier[0]['tanggal_efektif'] != date('Y-m-d')){
                        $data_harga_baru = array(
                            'supplier_item_id' => $get_harga_supplier[0]['id'],
                            'harga'            => $item['item_harga'],
                            'tanggal_efektif'  => date('Y-m-d')
                        );

                        $harga_baru = $this->supplier_harga_item_m->save($data_harga_baru);
                    }
                    elseif($get_harga_supplier[0]['tanggal_efektif'] == date('Y-m-d')){
                        $data_harga_baru = array(
                            'harga'            => $item['item_harga'],
                        );

                        $harga_baru = $this->supplier_harga_item_m->save($data_harga_baru, $get_harga_supplier[0]['id_harga']);
                    }

                       
                $y++;  
                $i++;  
                                      
                }

                if($biaya){
                   foreach ($biaya as $row_biaya) {
                        if($row_biaya['id'] == '' && $row_biaya['biaya_id'] != '' && $row_biaya['nominal'] != 0){
                            $last_id_biaya = $this->pembelian_biaya_m->get_max_id_biaya_pembelian()->result_array();
                            $last_id_biaya = intval($last_id_biaya[0]['max_id'])+1;

                            $format_id_biaya   = 'POB-'.date('m').'-'.date('Y').'-%04d';
                            $id_po_biaya       = sprintf($format_id_biaya, $last_id_biaya, 4);

                            $data_biaya = array(
                                'id'           => $id_po_biaya,
                                'pembelian_id' => $pembelian_id,
                                'biaya_id'     => $row_biaya['biaya_id'],
                                'nominal'      => $row_biaya['nominal'],
                                'is_active'    => 1,
                                'created_by'   => $this->session->userdata('user_id'),
                                'created_date' => date('Y-m-d H:i:s')
                            );

                            $pembelian_biaya = $this->pembelian_biaya_m->add_data($data_biaya); 

                        }if($row_biaya['id'] != '' && $row_biaya['biaya_id'] != '' && $row_biaya['nominal'] != 0 && $row_biaya['is_active'] == 1){
                           
                            $data_biaya = array(
                                'biaya_id'     => $row_biaya['biaya_id'],
                                'nominal'      => $row_biaya['nominal'],
                                'is_active'    => 1,
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_date' => date('Y-m-d H:i:s')
                            );

                            $pembelian_biaya = $this->pembelian_biaya_m->edit_data($data_biaya,$row_biaya['id']); 

                        }if($row_biaya['id'] != '' && $row_biaya['is_active'] == 0){
                           
                            $where_biaya = array(
                                'id'     => $row_biaya['id']
                            );

                            $pembelian_biaya = $this->pembelian_biaya_m->delete_by($where_biaya); 

                        }
                    } 
                }

                $data_po_edit = $this->pembelian_m->get_by(array('pembelian_id' => $pembelian_id), true);
                if($data_po_edit->status == 3){
                    $data_os_po = $this->o_s_pembayaran_pembelian_m->get_by(array('pembelian_id' => $pembelian_id));
                    if($tempo->tipe_bayar_id == 1 || $tempo->tipe_bayar_id == 2){
                        if(count($data_os_po) == 0){
                            if($array_input['setoran'] == '' || $array_input['setoran'] == 0){
                                if($array_input['dp'] != 0){

                                    $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                    $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                    $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                    $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                    $data_o_s = array(
                                        'id'           => $id_os_po,
                                        'pembelian_id' => $pembelian_id,
                                        'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                        'nominal'      => $array_input['dp_nominal'],
                                        'status'       => 1
                                    );
                                    $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                                }if($array_input['dp'] == 0){

                                    $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                    $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                    $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                    $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                    $data_o_s = array(
                                        'id'           => $id_os_po,
                                        'pembelian_id' => $pembelian_id,
                                        'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                        'nominal'      => $array_input['sisa_bayar_hidden'],
                                        'status'       => 1
                                    );
                                    $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                                }
                            }elseif($array_input['setoran'] != '' && $array_input['setoran'] != 0){
                                for($x=1;$x<=$array_input['kelipatan'];$x++){
                                    $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                    $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                    $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                    $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                    $tipe_time = ' days';
                                    if($array_input['jenis_bayar'] == 1){
                                        $tipe_time = ' days';
                                    }if($array_input['jenis_bayar'] == 2){
                                        $tipe_time = ' month';
                                    }if($array_input['jenis_bayar'] == 3){
                                        $tipe_time = ' year';
                                    }
                                    $data_o_s = array(
                                        'id'           => $id_os_po,
                                        'pembelian_id' => $pembelian_id,
                                        'tanggal'      => date('Y-m-d', strtotime($tanggalPesan.' +'.$x.$tipe_time)),
                                        'nominal'      => $array_input['dp_nominal'],
                                        'status'       => 1
                                    );
                                    $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                                }
                            }
                        }elseif(count($data_os_po) != 0){
                            if($array_input['setoran'] == '' || $array_input['setoran'] == 0){
                                if($array_input['dp'] != 0){
                                    foreach ($data_os_po as $os_po) {
                                        $data_o_s_edit = array(
                                            'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                            'nominal'      => $array_input['dp_nominal'],
                                            'status'       => 1
                                        );
                                        $os_po_edit = $this->o_s_pembayaran_pembelian_m->edit_data($data_o_s_edit, $os_po->id);
                                    }
                                }if($array_input['dp'] == 0){

                                    foreach ($data_os_po as $os_po) {
                                        $data_o_s_edit = array(
                                            'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                            'nominal'      => $array_input['sisa_bayar_hidden'],
                                            'status'       => 1
                                        );
                                        $os_po_edit = $this->o_s_pembayaran_pembelian_m->edit_data($data_o_s_edit, $os_po->id);
                                    }
                                }
                            }elseif($array_input['setoran'] != '' && $array_input['setoran'] != 0){

                                $this->o_s_pembayaran_pembelian_m->delete_by(array('pembelian_id' => $pembelian_id));

                                for($x=1;$x<=$array_input['kelipatan'];$x++){
                                    $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                    $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                    $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                    $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                    $tipe_time = ' days';
                                    if($array_input['jenis_bayar'] == 1){
                                        $tipe_time = ' days';
                                    }if($array_input['jenis_bayar'] == 2){
                                        $tipe_time = ' month';
                                    }if($array_input['jenis_bayar'] == 3){
                                        $tipe_time = ' year';
                                    }
                                    $data_o_s = array(
                                        'id'           => $id_os_po,
                                        'pembelian_id' => $pembelian_id,
                                        'tanggal'      => date('Y-m-d', strtotime($tanggalPesan.' +'.$x.$tipe_time)),
                                        'nominal'      => $array_input['setoran'],
                                        'status'       => 1
                                    );
                                    $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                                }
                            }
                        }
                    }if($tempo->tipe_bayar_id == 3){
                        if(count($data_os_po) == 0){
                            if($array_input['setoran'] == '' || $array_input['setoran'] == 0){
                                if($array_input['dp'] != 0){

                                    $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                    $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                    $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                    $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                    $data_o_s = array(
                                        'id'           => $id_os_po,
                                        'pembelian_id' => $pembelian_id,
                                        'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                        'nominal'      => $array_input['dp_nominal'],
                                        'status'       => 1
                                    );
                                    $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                                }
                            }elseif($array_input['setoran'] != '' && $array_input['setoran'] != 0){
                                for($x=1;$x<=$array_input['kelipatan'];$x++){
                                    $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                    $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                    $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                    $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                    $tipe_time = ' days';
                                    if($array_input['jenis_bayar'] == 1){
                                        $tipe_time = ' days';
                                    }if($array_input['jenis_bayar'] == 2){
                                        $tipe_time = ' month';
                                    }if($array_input['jenis_bayar'] == 3){
                                        $tipe_time = ' year';
                                    }
                                    $data_o_s = array(
                                        'id'           => $id_os_po,
                                        'pembelian_id' => $pembelian_id,
                                        'tanggal'      => date('Y-m-d', strtotime($tanggalPesan.' +'.$x.$tipe_time)),
                                        'nominal'      => $array_input['dp_nominal'],
                                        'status'       => 1
                                    );
                                    $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                                }
                            }
                        }if(count($data_os_po) != 0){
                            if($array_input['setoran'] == '' || $array_input['setoran'] == 0){
                                if($array_input['dp'] != 0){

                                    foreach ($data_os_po as $os_po) {
                                        $data_o_s_edit = array(
                                            'tanggal'      => date('Y-m-d', strtotime($tanggalPesan)),
                                            'nominal'      => $array_input['dp_nominal'],
                                            'status'       => 1
                                        );
                                        $os_po_edit = $this->o_s_pembayaran_pembelian_m->edit_data($data_o_s_edit, $os_po->id);
                                    }
                                }
                            }elseif($array_input['setoran'] != '' && $array_input['setoran'] != 0){
                                $this->o_s_pembayaran_pembelian_m->delete_by(array('pembelian_id' => $pembelian_id));

                                for($x=1;$x<=$array_input['kelipatan'];$x++){
                                    $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                                    $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                                    $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                                    $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                                    $tipe_time = ' days';
                                    if($array_input['jenis_bayar'] == 1){
                                        $tipe_time = ' days';
                                    }if($array_input['jenis_bayar'] == 2){
                                        $tipe_time = ' month';
                                    }if($array_input['jenis_bayar'] == 3){
                                        $tipe_time = ' year';
                                    }
                                    $data_o_s = array(
                                        'id'           => $id_os_po,
                                        'pembelian_id' => $pembelian_id,
                                        'tanggal'      => date('Y-m-d', strtotime($tanggalPesan.' +'.$x.$tipe_time)),
                                        'nominal'      => $array_input['dp_nominal'],
                                        'status'       => 1
                                    );
                                    $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                                }
                            }
                        }
                    }
                }  
                if($penawaran)
                {
                    foreach ($penawaran as $row) 
                    {
                        if($row['id'] == '' && ($row['nomor'] != '' || $row['url'] != ''))
                        {
                            $last_id_penawaran = $this->pembelian_penawaran_m->get_max_id_pembelian_penawaran()->result_array();
                            $last_id_penawaran = intval($last_id_penawaran[0]['max_id'])+1;

                            $format_id_penawaran   = 'POQ-'.date('m').'-'.date('Y').'-%04d';
                            $id_po_penawaran      = sprintf($format_id_penawaran, $last_id_penawaran, 4);

                            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/pembelian/pembelian_alkes/doc/penawaran/'.str_replace(' ', '_', $pembelian_id).'/'.str_replace(' ', '_', $id_po_penawaran);
                            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                            $temp_filename = $row['url'];

                            $convtofile = new SplFileInfo($temp_filename);
                            $extenstion = ".".$convtofile->getExtension();

                            $new_filename = $row['url'];
                            $real_file = str_replace(' ', '_', $pembelian_id).'/'.str_replace(' ', '_', $id_po_penawaran).'/'.$new_filename;

                            copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_doc_penawaran').$real_file);     
                            
                            $data_penawaran = array(
                                'id'                    => $id_po_penawaran,
                                'pembelian_id'          => $pembelian_id,
                                'supplier_penawaran_id' => '',
                                'nomor_penawaran'       => $row['nomor'],
                                'url'                   => $row['url'],
                                'status'                => 1,
                                'is_active'             => 1,
                                'created_by'             => $this->session->userdata('user_id'),
                                'created_date'           => date('Y-m-d H:i:s')
                            );

                            $penawaran = $this->pembelian_penawaran_m->add_data($data_penawaran);

                        }
                        elseif($row['id'] != '')
                        {
                            if($row['is_active'] == 0)
                            {
                                $data_penawaran = array(
                                    'is_active'    => 0,
                                    'modified_by'   => $this->session->userdata('user_id'),
                                    'modified_date' => date('Y-m-d H:i:s')
                                );
                                $penawaran = $this->pembelian_penawaran_m->edit_data($data_penawaran, $row['id']);
                            }
                            else
                            {
                                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/pembelian/pembelian_alkes/doc/penawaran/'.str_replace(' ', '_', $pembelian_id).'/'.str_replace(' ', '_', $row['id']);
                                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                                $temp_filename = $row['url'];

                                $convtofile = new SplFileInfo($temp_filename);
                                $extenstion = ".".$convtofile->getExtension();

                                $new_filename = $row['url'];
                                $real_file = str_replace(' ', '_', $pembelian_id).'/'.str_replace(' ', '_', $row['id']).'/'.$new_filename;

                                copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_doc_penawaran').$real_file);

                                $data_penawaran = array(
                                    'nomor_penawaran' => $row['nomor'],
                                    'url'             => $row['url'],
                                    'status'          => 1,
                                    'is_active'       => 1,
                                    'modified_by'     => $this->session->userdata('user_id'),
                                    'modified_date'   => date('Y-m-d H:i:s')
                                );
                                $penawaran = $this->pembelian_penawaran_m->edit_data($data_penawaran, $row['id']);
                            }
                        }
                    }
                }
                if($array_input['setoran'] != '' && $array_input['setoran'] != 0){

                    $data_kredit = $this->pembelian_kredit_m->get_by(array('pembelian_id' => $pembelian_id), true);
                    if(count($data_kredit) == 0){
                        $last_id_kredit = $this->pembelian_kredit_m->get_max_id_pembelian_kredit()->result_array();
                        $last_id_kredit = intval($last_id_kredit[0]['max_id'])+1;

                        $format_id_kredit   = 'POK-'.date('m').'-'.date('Y').'-%04d';
                        $id_po_kredit      = sprintf($format_id_kredit, $last_id_kredit, 4);

                        $data_kredit = array(
                            'pembelian_kredit_id' => $id_po_kredit,
                            'pembelian_id'        => $pembelian_id,
                            'tenor'               => $array_input['lama_tenor'],
                            'jenis_tenor'         => $array_input['jenis_tenor'],
                            'jenis_bayar'         => $array_input['jenis_bayar'],
                            'kelipatan'           => $array_input['kelipatan'],
                            'bunga'               => $array_input['bunga_persen'],
                            'harga_setoran'       => $array_input['setoran']
                        );

                        $po_kredit = $this->pembelian_kredit_m->add_data($data_kredit);
                    }elseif(count($data_kredit) != 0){
                        $data_kredit_edit = array(
                            'tenor'               => $array_input['lama_tenor'],
                            'jenis_tenor'         => $array_input['jenis_tenor'],
                            'jenis_bayar'         => $array_input['jenis_bayar'],
                            'kelipatan'           => $array_input['kelipatan'],
                            'bunga'               => $array_input['bunga_persen'],
                            'harga_setoran'       => $array_input['setoran']
                        );

                        $po_kredit_edit = $this->pembelian_kredit_m->edit_data($data_kredit_edit, $data_kredit->pembelian_kredit_id);
                    }
                }
            }
            else
            {
                $data_draft = array(
                    'tanggal_pesan'      => date('Y-m-d', strtotime($tanggalPesan)),
                    'tanggal_kadaluarsa' => date('Y-m-d', strtotime($tanggalKadaluarsa)),
                    'keterangan'         => $keterangan,
                );
                $draft_pembelian_id = $this->draft_po_m->save($data_draft, $array_input['pk_value']);
                $y = 1;
                foreach ($beliItem as $item) {
                    
                    if($item['id_detail'] == null && $item['is_deleted'] == null && $item['action'] == 'add' && $item['id'] != null)
                    {
                        $data_item = array(
                            'draf_pembelian_id' => $draft_pembelian_id,
                            'item_id'           => $item['item_id'],
                            'item_satuan_id'    => $item['satuan'],
                            'jumlah_pesan'      => $item['jumlah'],
                            'diskon'            => $item['item_diskon'],
                        );
                        $pembelian_detail = $this->draf_po_detail_m->save($data_item);
                    }
                    else if($item['id_detail'] != null && $item['is_deleted'] == null && $item['action'] == 'edit')
                    {
                        $data_item = array(
                            'jumlah_pesan'  => $item['jumlah'],
                            'diskon'        => $item['item_diskon']
                        );
                        $pembelian_detail = $this->draf_po_detail_m->save($data_item, $item['id_detail']);
                    }
                    else if($item['id_detail'] != null && $item['is_deleted'] != null && $item['action'] == 'edit')
                    {
                        $pembelian_detail = $this->draf_po_detail_m->delete($item['id_detail']);
                    }

                    if(!empty($linkData))
                    {
                        foreach ($linkData as $data) {
                            $jumlah_item_link = explode(' ', $data['jumlah_item']); 
                            $jumlah_item = $jumlah_item_link[0];

                            $data_save_link = array(
                                'pembelian_detail_id'                  => $draft_pembelian_id,
                                'order_permintaan_pembelian_detail_id' =>  $data['id_detail'],
                                'tipe_pembelian'                       => 2,
                                'tipe_permintaan'                      => $data['tipe_permintaan'],
                                'jumlah'                               => $jumlah_item,
                                'satuan_id'                            => $item['satuan'],
                                'is_active'                            => 1
                            );
                            $save_draf_link = $this->link_permintaan_pembelian_m->save($data_save_link);
                        }
                    } 
                    $y++;
                }
            }
        }
        else if($command == 'proses')
        {
            $id_beli = $array_input['pembelian_id'];
            $data_beli = $this->pembelian_m->get_by(array('id' => $id_beli), true);

            $data = array(
                '`status`' => 4,
                'diskon'                    => $array_input['disk_angka'],
                'pph'                       => $array_input['ppn_hidden'],
                'biaya_tambahan'            => $array_input['biaya_tambah_hidden'],
                'grand_total'               => $array_input['grand_tot_hidden'],
                'dp'                        => $array_input['depe'],
                'sisa_bayar'                => $array_input['sisa_nya']
            );

            $pembelian_id = $this->pembelian_m->edit_data($data, $id_beli);

            if($array_input['master_tipe_bayar'] == 1 || $array_input['master_tipe_bayar'] == 2){
                if($array_input['grand_total_biaya_hidden'] != 0){
                    $last_id_os_hutang   = $this->o_s_hutang_m->get_max_id_o_s_hutang()->result_array();
                    $last_id_os_hutang   = intval($last_id_os_hutang[0]['max_id'])+1;
                    
                    $format_id = 'OSH-'.date('mY').'-%04d';
                    $id_os_hutang  = sprintf($format_id, $last_id_os_hutang, 4);

                    $supplier = $this->supplier_m->get_by(array('id' => $array_input['supplier_id']), true);

                    $insert_os_hutang = array(
                        'id'                    => $id_os_hutang,
                        'tanggal'               => date('Y-m-d'),
                        'transaksi_id'          => $id_beli,
                        'tipe_transaksi'        => 1,
                        'nomor_transaksi'        => $data_beli->no_pembelian,
                        'pemberi_hutang_id'     => $array_input['supplier_id'],
                        'nama_pemberi_hutang'   => $supplier->nama,
                        'tipe_pemberi_hutang'   => 1,
                        'jumlah'                => $array_input['grand_total_biaya_hidden'],
                        'created_by'            => $this->session->userdata('user_id'),
                        'created_date'          => date('Y-m-d H:i:s'),
                    );

                    $save_os_hutang = $this->o_s_hutang_m->add_data($insert_os_hutang);
                }   
            }

            $jml_item = $array_input['jml_baris'];
            foreach ($beliItem as $item) 
            {
                if($item['item_id'] != "" && $item['item_satuan'] != ""  )
                {
                    $get_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $item['item_id'], 'is_primary' => 1));
                    $satuan_primary = object_to_array($get_satuan_primary);
                    // die_dump($get_satuan_primary);
                    $nilai_konversi         = $this->item_m->get_nilai_konversi($item['item_satuan']);
                    
                    $harga_satuan = $item['item_total']/(intval($item['jumlah_setujui']*$nilai_konversi));

                    $diskon_tambahan = 0;
                    if($array_input['disk_hidden'] != 0){
                        $diskon_tambahan = (intval($array_input['disk_hidden'])/$jml_item) / (intval($item['jumlah_setujui']*$nilai_konversi));
                    }

                    $tad_tambahan = $harga_satuan - $diskon_tambahan;

                    $ppn_tambahan = 0;
                    if($array_input['ppn_hidden'] != 0){
                        $ppn_tambahan = (intval($array_input['ppn_hidden'])/100) * $tad_tambahan;
                    }

                    $tax_tambahan = $tad_tambahan + $ppn_tambahan;

                    $pembulatan = 0;

                    $harga_satuan = $tax_tambahan - $pembulatan;
                    $data_item = array(
                        'jumlah_belum_diterima'       => intval($item['jumlah_setujui']*$nilai_konversi),
                        'diskon'                      => $item['item_diskon'],
                        'diskon_tambahan_primary'     => $diskon_tambahan,
                        'ppn_tambahan_primary'        => $ppn_tambahan,
                        'pembulatan_tambahan_primary' => $pembulatan,
                        'harga_beli_primary'          => $harga_satuan,
                        'harga_beli'                  => $item['item_harga']

                    );
                    
                    $pembelian_detail = $this->pembelian_detail_m->edit_data($data_item, $item['id']); 

                    $data_os_pesan = $this->o_s_pmsn_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['item_satuan'], 'status' => 2));
                    $data_os_pesan = object_to_array($data_os_pesan);

                    $x = 1;
                    $sisa = 0;
                    foreach ($data_os_pesan as $row_os_pesan) {
                        if($x == 1 && $item['jumlah_setujui'] >= $row_os_pesan['jumlah']){

                            $sisa = $item['jumlah_setujui'] - $row_os_pesan['jumlah'];
                            $sisa_os = 0;

                            $array_os = array(
                                'status' => 2,
                            );

                            $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);
                        }
                        if($x == 1 && $item['jumlah_setujui'] < $row_os_pesan['jumlah']){

                            $sisa = 0;
                            $sisa_os = $row_os_pesan['jumlah'] - $item['jumlah_setujui'];

                            $array_os = array(
                                'jumlah' => $item['jumlah_setujui'],
                                'status' => 2,
                            );

                            $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);

                            $last_id_osp       = $this->o_s_pmsn_m->get_max_id_os_pesan()->result_array();
                            $last_id_osp       = intval($last_id_osp[0]['max_id'])+1;
                            
                            $format_id_osp     = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_osp            = sprintf($format_id_osp, $last_id_osp, 4);

                            $data_item_os = array(
                                'id'                     => $id_osp,
                                'tanggal'                => date('Y-m-d'),
                                'pemesanan_detail_id'    => 0,
                                'item_id'                => $item['item_id'],
                                'item_satuan_id'         => $item['item_satuan'],
                                'jumlah'                 => $sisa_os,
                                'status'                 => 1,
                                'created_by'             => $this->session->userdata('user_id'),
                                'created_date'           => date('Y-m-d H:i:s')
                            );
                            
                            $save_os_pesan = $this->o_s_pmsn_m->add_data($data_item_os); 
                        }
                        if($x != 1 && $sisa > 0 && $sisa >= $row_os_pesan['jumlah']){
                            
                            $sisa = $sisa - $row_os_pesan['jumlah'];
                            $sisa_os = 0;

                            $array_os = array(
                                'status' => 2,
                            );

                            $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);
                        }

                        if($x != 1 && $sisa > 0 && $sisa < $row_os_pesan['jumlah']){

                            $sisa_os = $row_os_pesan['jumlah'] - $sisa;

                            $array_os = array(
                                'jumlah' => $sisa,
                                'status' => 2,
                            );

                            $last_id_osp       = $this->o_s_pmsn_m->get_max_id_os_pesan()->result_array();
                            $last_id_osp       = intval($last_id_osp[0]['max_id'])+1;
                            
                            $format_id_osp     = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_osp            = sprintf($format_id_osp, $last_id_osp, 4);

                            $data_item_os = array(
                                'id'                     => $id_osp,
                                'tanggal'                => date('Y-m-d'),
                                'pemesanan_detail_id'    => 0,
                                'item_id'                => $item['item_id'],
                                'item_satuan_id'         => $item['item_satuan'],
                                'jumlah'                 => $sisa_os,
                                'status'                 => 1,
                                'created_by'             => $this->session->userdata('user_id'),
                                'created_date'           => date('Y-m-d H:i:s')
                            );
                            
                            $save_os_pesan = $this->o_s_pmsn_m->add_data($data_item_os); 

                            $sisa = 0;

                            $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);
                        }
                        $x++;
                    }
                    // die_dump($pembelian_detail);
                }
            }

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data PO berhasil diproses.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect("pembelian/pembelian_alkes");
        }
        if ($draft_pembelian_id) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data PO berhasil ditambahkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
            

        redirect("pembelian/pembelian_alkes");
    }

    public function delete_draft($id)
    {
           
        // die_dump($id);
        $this->draft_po_m->delete($id);

        $delete_data = array('draf_pembelian_id' => $id);
        $this->draf_po_detail_m->delete_all_by($delete_data);

        // die_dump($this->db->last_query());
        
        if ($delete_data) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate(" Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("pembelian/pembelian");
    }

    public function delete($id)
    {
        $user_id = $this->session->userdata('user_id');
        $data = array(
            'is_active'    => 0
        );
    
        // save data
        $pembelian_id = $this->pembelian_m->edit_data($data, $id);

       
        $where_setuju['pembelian_id'] = $id;

        $persetujuan = $this->persetujuan_po_m->delete_by($where_setuju);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id'   => $trash_id,
            'tipe'              => 2,
            'data_id'           => $id,
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);
        if ($pembelian_id) {
            $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Pembelian sudah dihapus", $this->session->userdata("language")),
            "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
        }
       redirect('pembelian/pembelian_alkes/');
    }

    public function proses_setuju($id)
    {
        $assets = array();
        $config = 'assets/pembelian/pembelian_alkes/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_data = $this->pembelian_m->get_data($id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("Proses Pembelian", $this->session->userdata("language")), 
            'header'              => translate("Proses Pembelian", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'pembelian/pembelian_alkes/proses_setuju',
            'form_data'           => object_to_array($form_data),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'tipe_bayar'          => $tipe_bayar,
            'form_data_detail'    => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    public function proses($id)
    {

        $update_data = array(
            '`status`' => 4
        );

        $update = $this->pembelian_m->edit_data($update_data, $id);

        redirect("pembelian/pembelian");
    }

    public function save_tanggal()
    {
        if($this->input->is_ajax_request())
        {
            $array_input = $this->input->post();
            // die_dump($array_input);
            $id = $array_input['id_perpanjang'];
            $data = array(
                'tanggal_kadaluarsa' => date('Y-m-d', strtotime($array_input['tanggal_perpanjang']))
            );
            

            $perpanjang = $this->pembelian_m->save($data, $id);
            // die_dump($this->db->last_query());
        }
    }

    public function get_item_satuan()
    {
        $item_id           = $this->input->post('item_id');
        $supplier_id       = $this->input->post('supplier_id');
        
        $get_data_sup_item = $this->supplier_item_m->get_data($item_id,$supplier_id)->result_array();
        $sup_item          = object_to_array($get_data_sup_item);
        
        $satuan_terkecil   = $this->item_satuan_m->get_satuan_terkecil($item_id)->result_array();
        $data_konversi     = $this->item_satuan_m->get_data_konversi($item_id, $satuan_terkecil[0]['id']);

        $i = 0;
        $y = count($data_konversi);
        foreach ($data_konversi as $data) 
        {
            $harga = $this->supplier_harga_item_m->get_harga($sup_item[$i]['id'])->result_array();
            // $data_konversi[$i]['sql'] = $harga;
            // $data_konversi[$i]['supplier_item_id'] = $sup_item[$i]['id'];
            $data_konversi[$i]['harga'] = intval($harga[0]['harga']);
            $data_konversi[$i]['minimum_order'] = intval($sup_item[$i]['minimum_order']);
            $data_konversi[$i]['kelipatan_order'] = intval($sup_item[$i]['kelipatan_order']);
            $i++;
            $y--;
        }

        echo json_encode($data_konversi);
    }

   public function get_data_link()
   {
        $item_id = $this->input->post('id_item');

        $id_link = $this->daftar_permintaan_po_m->get_data_link_permintaan($item_id)->result();
        $id_link_permintaan = object_to_array($id_link);

        echo json_encode($id_link_permintaan);
   }

   public function cetak_po($id)
   {

        $last_number    = $this->pembelian_cetak_m->get_no_cetak($id)->result_array();
        $last_number    = intval($last_number[0]['max_no_cetak'])+1;


        $format         = 'PRN-'.date('y').date('m').'-'.'%03d';
        $no_cetak       = sprintf($format, $last_number, 3);

        $data_cetak = array(
            'pembelian_id' => $id,
            'no_cetak' => $no_cetak
        );

        $cetak_id = $this->pembelian_cetak_m->save($data_cetak);

        $pembelian = $this->pembelian_m->get_by(array('id' => $id, 'is_active' => 1), true);
        $pembelian_detail = $this->pembelian_detail_m->get_data_detail_invoice($id);
        $pembelian_penawaran = $this->pembelian_penawaran_m->get_by(array('pembelian_id' => $id, 'is_active' => 1));


        $mpdf = new mPDF('utf-8','A4', 1, '', 10, 10, 28, 28, 0, 5);
        
        $body = array(
            'no_cetak' => $no_cetak,
            'pembelian'  => object_to_array($pembelian),
            'pembelian_detail'  => object_to_array($pembelian_detail),
            'pembelian_penawaran'  => object_to_array($pembelian_penawaran),
        );

        $stylesheets = file_get_contents(base_url().'assets/metronic/global/plugins/bootstrap/css/bootstrap.css');
        $mpdf->writeHTML($stylesheets, 1);
        $filename = $pembelian->no_pembelian.'.pdf';
        $filename = str_replace('/', '_', $filename);
        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/pembelian/pembelian_alkes/doc/PO/'.$pembelian->id;
        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
       
        // $html = str_replace('{PAGECNT}', $mpdf->getPageCount(), $this->load->view('pembelian/pembelian_alkes/cetak/header', $body, true));
        $mpdf->SetHTMLHeader($this->load->view('pembelian/pembelian_alkes/cetak/header', $body, true));
        $mpdf->SetHTMLFooter($this->load->view('pembelian/pembelian_alkes/cetak/footer', $body, true));
        $mpdf->writeHTML($this->load->view('pembelian/pembelian_alkes/cetak/print_po', $body, true));

        $mpdf->Output('invoice_'.date('Y-m-d H:i:s').'.pdf', 'I');
        $mpdf->Output($path_dokumen.'/'.$filename, 'F');
   }

    public function finish_po()
    {
        $po_id = $this->input->post('id_po');

        $response = new stdClass;
        $response->success = false;

        $data = array(
            'status'           => 5,
            'keterangan_batal'     => $this->input->post('keterangan')
        );
    
        // save data
        $pembelian_id = $this->pembelian_m->edit_data($data, $po_id);

        
        $response->success = true;
        $response->msg = translate('PO berhasil diselesaikan', $this->session->userdata('language'));
        
        die(json_encode($response));
    }

    public function cancel_po()
    {
        $po_id = $this->input->post('id_po');

        $response = new stdClass;
        $response->success = false;

        $data = array(
            'status_cancel'    => 1,
            'keterangan_batal'     => $this->input->post('keterangan')
        );
    
        // save data
        $pembelian_id = $this->pembelian_m->edit_data($data, $po_id);

        $response->success = true;
        $response->msg = translate('PO berhasil dibatalkan', $this->session->userdata('language'));
        
        die(json_encode($response));
    }

    public function add_biaya()
    {
       $this->load->view('pembelian/pembelian_alkes/add_biaya');
    }

    public function edit_biaya($id_po)
    {
        $data = array(
            'id_po'      => $id_po,
        );
        $this->load->view('pembelian/pembelian_alkes/edit_biaya', $data);
    }

    public function get_biaya_tambahan()
    {
        if($this->input->is_ajax_request()){
            $id_po = $this->input->post('id_po');

            $response = new stdClass;
            $response->success = false;

            $data_biaya = $this->pembelian_biaya_m->get_by(array('pembelian_id' => $id_po, 'is_active' => 1));

            if(count($data_biaya) != 0){
                $response->success = true;
                $response->rows = object_to_array($data_biaya);
            }

            die(json_encode($response));    
        }
    }

    public function send_po($po_id)
    {
        $data_po = $this->pembelian_m->get_by(array('id' => $po_id), true);
        $supplier = $this->supplier_m->get_by(array('id' => $data_po->supplier_id), true);
        $supplier_email = $this->supplier_email_m->get_by(array('supplier_id' => $supplier->id));

        $data = array(
            'po_id'          => $po_id,
            'data_po'        => object_to_array($data_po),
            'supplier'       => object_to_array($supplier),
            'supplier_email' => object_to_array($supplier_email)
        );
        $this->load->view('pembelian/pembelian_alkes/modals/send_po', $data);
        
    }

    public function selesaikan_po($po_id)
    {
        $data_po = $this->pembelian_m->get_by(array('id' => $po_id), true);
        

        $data = array(
            'po_id'          => $po_id,
            'data_po'        => object_to_array($data_po)
        );
        $this->load->view('pembelian/pembelian_alkes/modals/finish_po', $data);
        
    }

    public function batalkan_po($po_id)
    {
        $data_po = $this->pembelian_m->get_by(array('id' => $po_id), true);
        

        $data = array(
            'po_id'          => $po_id,
            'data_po'        => object_to_array($data_po)
        );
        $this->load->view('pembelian/pembelian_alkes/modals/cancel_po', $data);
        
    }

    public function kirim_email_po()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();

            // die(dump($array_input));

            $id = $array_input['id_po'];
            $email = $array_input['supplier_email'];


            $pembelian = $this->pembelian_m->get_by(array('id' => $id, 'is_active' => 1), true);
            $pembelian_detail = $this->pembelian_detail_m->get_data_detail_invoice($id);

            $supplier = $this->supplier_m->get($pembelian->supplier_id);


            $mpdf = new mPDF('utf-8','A4', 1, '', 10, 10, 28, 28, 0, 5);
            
            $body = array(
                'no_cetak' => $no_cetak,
                'pembelian'  => object_to_array($pembelian),
                'pembelian_detail'  => object_to_array($pembelian_detail)
            );

            $stylesheets = file_get_contents(base_url().'assets/metronic/global/plugins/bootstrap/css/bootstrap.css');
            $filename = $pembelian->no_pembelian.'.pdf';
            $filename = str_replace('/', '_', $filename);
            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/pembelian/pembelian_alkes/doc/PO/'.$pembelian->id;
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

            $mpdf->writeHTML($stylesheets, 1);
           
            // $html = str_replace('{PAGECNT}', $mpdf->getPageCount(), $this->load->view('pembelian/pembelian_alkes/cetak/header', $body, true));
            $mpdf->SetHTMLHeader($this->load->view('pembelian/pembelian_alkes/cetak/header', $body, true));
            $mpdf->SetHTMLFooter($this->load->view('pembelian/pembelian_alkes/cetak/footer', $body, true));
            $mpdf->writeHTML($this->load->view('pembelian/pembelian_alkes/cetak/print_po', $body, true));

            $mpdf->Output($path_dokumen.'/'.$filename, 'F');

            $this->load->library('email');
            $config = array(
              'protocol'  => config_item('email_protocol'),
              'smtp_host' => config_item('email_smtp_host'),
              'smtp_port' => config_item('email_smtp_port'),
              'smtp_user' => config_item('email_smtp_user'), // change it to yours
              'smtp_pass' => config_item('email_smtp_pass'), // change it to yours
            );

            $response = new stdClass;
            $response->success = false;

            $this->email->initialize($config);
            $this->email->set_newline("\r\n");  
            $this->email->from("Puchasing PT. Ravena Indonesia");
            $this->email->to($email); 
            //$this->email->to('larastriw.astri@gmail.com'); 
            $this->email->cc('purc_rhs@yahoo.com');    
            $this->email->reply_to('purc_rhs@yahoo.com', 'Purchasing PT. Ravena Indonesia');                 
            $this->email->subject('Pembelian '.$pembelian->no_pembelian);
            $this->email->message("Kepada ".$supplier->nama.",\nBerikut kami lampirkan PO PT. Ravena Indonesia (Please See Attach).\n\n\n\n\nThank and Regards,\n\n".$this->session->userdata('nama_lengkap'));
            $this->email->attach($path_dokumen.'/'.$filename);
            if($this->email->send())
            {
                $response->success = true;
                $response->msg = translate('Email PO berhasil dikirim', $this->session->userdata('language'));
            }else{
                $response->success = false;
                $response->msg = translate('Email PO gagal dikirim', $this->session->userdata('language'));
                $response->error = $this->email->print_debugger();
            }
            die(json_encode($response));


        }
    }

    public function listing_jumlah_terima($po_detail_id=null, $item_id=null, $item_satuan_id=null){
        $result = $this->pmb_po_detail_m->get_datatable($po_detail_id);
       

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
        
        // die_dump($this->db->last_query());
        
        $records = $result->records;
        // die(dump($records));

        $i=0;
        foreach($records->result_array() as $row)
        {
            $output['data'][] = array(
                '<div class="text-center">'.$row['no_pmb'].'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.$row['jumlah'].' '.$row['satuan_nama'].'</div>',
                '<div class="text-center">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-center">'.date('d F Y', strtotime($row['expire_date'])).'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function add_jumlah($id_row, $item_id, $satuan_id)
    {
        $index = explode('_', $id_row);
        $data_item = $this->item_m->get_by(array('id' => $item_id), true);
        $data_item_satuan = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);
        
        $data = array(
            'id_row'    => $id_row,
            'index_row' => $index[2],
            'data_item' => $data_item,
            'data_item_satuan' => $data_item_satuan,
        );

        $this->load->view('pembelian/pembelian_alkes/modals/add_jumlah', $data);
    }

    public function edit_jumlah_edit($id_row, $pembelian_detail_id)
    {
        $index            = explode('_', $id_row);
        $data_detail      = $this->pembelian_detail_m->get_by(array('id' => $pembelian_detail_id), true);
        $data_kirim       = $this->pembelian_detail_tanggal_kirim_m->get_by(array('pembelian_detail_id' => $pembelian_detail_id));
        
        $data_item        = $this->item_m->get_by(array('id' => $data_detail->item_id), true);
        $data_item_satuan = $this->item_satuan_m->get_by(array('id' => $data_detail->item_satuan_id), true);
        
        $data = array(
            'id_row'           => $id_row,
            'index_row'        => $index[2],
            'data_detail'      => $data_detail,
            'data_kirim'       => object_to_array($data_kirim),
            'data_item'        => $data_item,
            'data_item_satuan' => $data_item_satuan,
        );

        $this->load->view('pembelian/pembelian_alkes/modals/edit_jumlah', $data);
    }
}

/* End of file branch.php */
/* Location: ./application/controllers/pembelian/pembelian.php */