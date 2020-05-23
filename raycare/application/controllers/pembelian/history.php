<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class History extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd8b84850148a49d0c9fd60ba624315db';                  // untuk check bit_access

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

        $this->load->model('pembelian/daftar_permintaan_po_m');
        $this->load->model('pembelian/pembelian_cetak_m');
        $this->load->model('pembelian/pembelian_detail_tanggal_kirim_m');
        $this->load->model('pembelian/item_m');
        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('pembelian/draft_po_m');        
        $this->load->model('pembelian/supplier_harga_item_m');
        $this->load->model('pembelian/penerima_cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('pembelian/pembelian_penawaran_m');
        $this->load->model('pembelian/supplier_item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('pembelian/supplier_m');
        $this->load->model('pembelian/supplier_tipe_pembayaran_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_po_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_po_history_m');
        $this->load->model('pembelian/item_sub_kategori_pembelian_m');
        $this->load->model('penjualan/penjualan_detail_m');
        $this->load->model('penjualan/inventory_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_telepon_m');
        $this->load->model('master/supplier/supplier_email_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('pembelian/pembelian_invoice_m');
        $this->load->model('pembelian/penerima_customer_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/master_tipe_bayar_m');
        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_m');
        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_detail_m');
        $this->load->model('gudang/barang_datang/pmb_po_detail_m');
      
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/pembelian/history/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History', $this->session->userdata('language')), 
            'header'         => translate('History', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/history/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/cabang_/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). translate("Tambah Cabang", $this->session->userdata("language")), 
            'header'         => translate("Tambah Cabang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            // 'menus'          => $this->menus,
            // 'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/cabang_/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function laporan_pembelian()
    { 
        $assets = array();
        $config = 'assets/pembelian/history/laporan';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Laporan Pembelian', $this->session->userdata('language')), 
            'header'         => translate('Laporan Pembelian', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/history/laporan',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function export_pembelian($bulan, $tahun)
    {
        $result = $this->pembelian_m->get_laporan_pembelian($bulan, $tahun);
       // die(dump(($bulan));
    }



   public function proses_pembelian($id, $id_sup)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $id_sup = intval($id_sup);
        $id_sup || redirect(base_Url());

        $assets = array();
        $config = 'assets/pembelian/history/proses';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->pembelian_m->get_data($id)->result_array();
        // die_dump($this->db->last_query());

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("Buat PO Berdasarkan History", $this->session->userdata("language")), 
            'header'         => translate("Buat PO Berdasarkan History", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'], 
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/history/proses',
            'form_data'      => object_to_array($form_data),
            'form_data_item'      => $this->pembelian_m->get_data_item($id, $id_sup),
            'pk_value'       => $id                         //table primary key value
        );

         $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing_permintaan($status=null)
    {        
        $result = $this->daftar_permintaan_po_m->get_datatable_proses($status);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die_dump($records->result_array());

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $info = '';
            
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info[]" class="btn btn-primary pilih-item hidden" data-id="'.$row['id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info "></i></a>';
                
               if($row['status_terakhir'] == 4)
               {
                    $action = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
               }
               else
               {
                    $action = '<div class="text-center"><span class="label label-md label-warning">Dalam Proses</span></div>';
               }
            

            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.date('d/m/Y', strtotime($row['tanggal'])).'</div>',
                $row['user'].'('.$row['user_level'].')',
                $row['subjek'],
                '<div class="row" style="margin-left : 0px !important; margin-right : 0px !important">
                    <div class="col-xs-8" style="text-align:left; padding-left : 0px !important; padding-right : 0px !important; ">
                        <input type="text" value="'.($row['jumlah_terdaftar']+$row['jumlah_tidak_terdaftar']).' items" id="jumlah_'.$row['id'].'" readonly style="background-color: transparent;border: 0px solid;">
                    </div>
                    <div class="col-xs-4" style="text-align:right; padding-left : 0px !important; padding-right : 0px !important;">
                        <span class="input-group-button">'.$info.'</span>
                    </div>
                </div>',
                $row['keterangan'],
                '<div class="text-center">'.$action.'</div>' 
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
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            if($row['is_active']==1)
            {

                $get_data_info = $this->item_m->get_data_item($row['id'])->result_array();
                $sub_data_info = object_to_array($get_data_info);
                // die_dump($get_data_info);
                $info_stok = '';

                foreach ($sub_data_info as $data_info) {
                    $info_stok .= $data_info['stok'].' '.$data_info['nama'].' ';
                }
                
                $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1),true);
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-info="'.htmlentities(json_encode($sub_data_info)).'" data-item="'.htmlentities(json_encode($row)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-xs btn-primary select"><i class="fa fa-check"></i></a>';

                // die_dump($info_stok);

                $info = '<a title="'.$info_stok.'" name="info[]" class="btn btn-xs btn-primary pilih-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';

                $output['aaData'][] = array(
                    '<div class="text-center">'.$row['id'].'</div>',
                    '<div class="text-center">'.$row['item_kode'].'</div>',
                    $row['item_nama'],
                    '<div class="text-center">'.$info.'</div>',
                    '<div class="text-center">'.$row['jumlah'].'</div>',
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

    public function listing_supplier($tipe=null)
    {
        
        $result = $this->supplier_m->get_datatable($tipe);
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

            $negara= "Indonesia";
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-pembayaran="'.htmlentities(json_encode($tipe_pembayaran)).'"  data-item="'.htmlentities(json_encode($row)).'" data-negara="'.$negara.'" class="btn btn-xs btn-primary select-supplier"><i class="fa fa-check"></i></a>';
 
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                $row['kode'],
                $row['nama'],
                '<div class="text-left">'.$row['kontak_person'].' ('.$row['no_telp'].')'.'</div>',
                '<div class="text-center">'.$row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['propinsi'].', '.$negara.'</div>',
                $row['email'],
                $row['rating'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item($id=null)
    {
        
        $result = $this->item_m->get_datatable($id);
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
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_tidak_terdaftar($id=null)
    {
        
        $result = $this->daftar_permintaan_po_m->get_datatable_item($id);
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
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pembelian($stat=null)
    {        
        $result = $this->pembelian_m->get_datatable_pembelian($stat);
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
            $sekarang = date('Y-m-d');
            $status = '';

            if($row['status'] == 1)
            {
                $status = '<div class="text-left"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
            }
            else if($row['status'] == 2)
            {
                $status = '<div class="text-left"><span class="label label-md label-primary">Proses Persetujuan</span></div>';
            }
            else if($row['status'] == 3 || $row['status'] == 4 || $row['status'] == 5)
            {
                $status = '<div class="text-left"><span class="label label-md label-info">Disetujui</span></div>';

                if($row['status_cancel'] == 1){
                    $status = '<div class="text-left"><span class="label label-md label-danger">Batal</span></div>';
                }

            }
            else if($row['status'] == 6 || $row['status'] == 7)
            {      
                $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';
            }
            else{
                $status = '<div class="text-left"><span class="label label-md label-danger">Kadaluarsa</span></div>';
            }

            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['id'].'"><i class="fa fa-search"></i></a>';
            $action .= '<a title="'.translate('Cetak', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'pembelian/history/cetak_po/'.$row['id'].'"><i class="fa fa-print"></i></a>';
            $action .= '<a title="'.translate('Cetak Exel', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'pembelian/history/cetak_ex/'.$row['id'].'"><i class="fa fa-file"></i></a>';


            $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($row['tipe_pembayaran'])->result_array();
            $form_data_detail = $this->pembelian_detail_m->get_data_detail($row['id']);

            $grand_total = 0;
            $tad = 0;
            $tat = 0;

            foreach ($form_data_detail as $key => $detail) {
                
                $sub_total = $detail['harga_beli'] * $detail['jumlah_disetujui'];
                $tad_detail = $sub_total - (($detail['diskon']/100) * $sub_total);

                $grand_total = $grand_total + $tad_detail;
            }

            $tad = $grand_total - (($row['diskon']/100) * $grand_total);
            $tat = $tad + (($row['pph']/100)* $tad);
            $tat = $tat - $row['pembulatan'];

            $output['data'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_po'].'</div>',
                $row['nama_sup'].' ['.$row['kode_sup'].']',
                '<div class="text-left">'.date('d M Y', strtotime($row['tanggal_pesan'])).'</div>',
                '<div class="text-right">'.formatrupiah($tat).'</div>',
                '<b>['.$tipe_bayar[0]['nama'].' '.$tipe_bayar[0]['lama_tempo'].']</b> '.$row['keterangan'],
                $row['user'],
                '<div class="text-left inline-button-table">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>'  
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function view($id)
    {

        $assets = array();
        $config = 'assets/pembelian/pembelian/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_data = $this->pembelian_m->get_data($id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);

        $data_bayar = $this->pembayaran_status_m->get_by(array('transaksi_id' => $id), true);

        if(count($data_bayar) == 0){
            $tanda_terima_faktur = $this->tanda_terima_faktur_m->get_by(array('pembelian_id' => $id), true);
            $data_bayar = $this->pembayaran_status_m->get_by(array('transaksi_id' => $tanda_terima_faktur->id), true);
        }

        $data_invoice = $this->pembelian_invoice_m->get_by(array('pembelian_id' => $id));
        // die(dump($data_invoice));

        $data = array(
            'title'               => config_item('site_name').' | '. translate("View Pembelian", $this->session->userdata("language")), 
            'header'              => translate("View Pembelian", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'pembelian/pembelian/view',
            'form_data'           => object_to_array($form_data),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'tipe_bayar'          => $tipe_bayar,
            'data_bayar'          => $data_bayar,
            'data_invoice'          => $data_invoice,
            'form_data_detail'    => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_ttf($id)
    {

        $assets = array();
        $config = 'assets/pembelian/pembelian/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_data = $this->pembelian_m->get_data($id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);

        $data_bayar = $this->pembayaran_status_m->get_by(array('transaksi_id' => $id), true);
        $data_invoice = $this->pembelian_invoice_m->get_by(array('pembelian_id' => $id));
        $data_tukar_faktur = $this->tanda_terima_faktur_m->get_by(array('pembelian_id' => $id), true);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("View Pembelian", $this->session->userdata("language")), 
            'header'              => translate("View Pembelian", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'pembelian/pembelian/view_ttf',
            'form_data'           => object_to_array($form_data),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'tipe_bayar'          => $tipe_bayar,
            'data_bayar'          => $data_bayar,
            'data_invoice'          => $data_invoice,
            'data_tukar_faktur'          => object_to_array($data_tukar_faktur),
            'form_data_detail'    => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function modal_perpanjang($id)
    {
        $data = array(
            'id'    => $id
        );
        $this->load->view('pembelian/history/modals/perpanjang_tanggal.php', $data);
    }

    public function modal_jumlah_terima($detail_id, $item_id, $item_satuan_id)
    {
        $data = array(
            'po_detail_id' => $detail_id,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        // die(dump($data));

        $this->load->view('pembelian/history/modals/modal_jumlah_terima_po.php', $data);
    
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

     public function daftar_link($id, $rowId, $satuan_id)
    {
        $data = array(
            'id'    => $id,
            'rowId' => $rowId,
            'satuanId' => $satuan_id
        );
        $this->load->view('pembelian/pembelian/modals/daftar_link.php', $data);
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
        $this->load->view('pembelian/pembelian/modals/data_daftar_link.php', $data);
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

    public function save()
    {
        $command = $this->input->post('command');
        $idSupplier = $this->input->post('id_supplier');
        $tanggalPesan = $this->input->post('tanggal_pesan');
        $tipe_bayar = $this->input->post('tipe_pembayaran');
        $tanggalKadaluarsa = $this->input->post('tanggal_kadaluarsa');
        $penerima = $this->input->post('id_penerima');
        $keterangan = $this->input->post('keterangan');
        $tipePenerima = $this->input->post('tipe');
        $beliItem = $this->input->post('items');
        // $linkData = $this->input->post('link');
        // $saveDraft = $this->input->post('save_draft');
        $array_input = $this->input->post();

        // die_dump($beliItem);
        if ($command === 'proses')
        {   
            $last_number    = $this->pembelian_m->get_no_pembelian()->result_array();
                // die_dump($last_number);
            $last_number    = intval($last_number[0]['max_no_pembelian'])+1;


            $format         = 'PRC-'.date('y').date('m').'-'.'%03d';
            $no_member       = sprintf($format, $last_number, 3);

            // die_dump($no_member);
      
            $data = array(
                'no_pembelian'       => $no_member,
                'supplier_id'        => $idSupplier,
                'customer_id'        => $penerima,
                'tanggal_pesan'      => date('Y-m-d', strtotime($tanggalPesan)),
                'tanggal_kadaluarsa' => date('Y-m-d', strtotime($tanggalKadaluarsa)),
                'keterangan'         => $keterangan,
                'tipe_customer'      => $tipePenerima,
                'tipe_bayar'         => $tipe_bayar,
                'status'             => 0,
                'status_keuangan'    => 0,
                'diskon'             => $array_input['diskon'],
                'pph'                => $array_input['pph'],
                'biaya_tambahan'     => $array_input['biaya_tambahan'],
                'is_active'          => 1,
            );
            $pembelian_id = $this->pembelian_m->save($data);
            // die_dump($this->db->last_query());
            $i = 0;
            foreach ($beliItem as $item) 
            {
                if($item['item_id'] != "" && $item['item_kode'] != "" && $item['item_nama'] != "" && $item['satuan_nama'] != ""  && $item['is_deleted'] == null)
                {
                    $get_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $item['item_id'], 'is_primary' => 1));
                    $satuan_primary = object_to_array($get_satuan_primary);
                    // die_dump($get_satuan_primary);
                    $nilai_konversi         = $this->item_m->get_nilai_konversi($item['satuan_nama']);
                    // die_dump($nilai_konversi);

                    $data_item = array(
                        'pembelian_id'           => $pembelian_id,
                        'item_id'                => $item['item_id'],
                        'item_satuan_id'         => $item['satuan_nama'],
                        'item_satuan_id_primary' => $satuan_primary[0]['id'],
                        'jumlah_pesan'           => $item['jumlah'],
                        'jumlah_diterima'        => 0,
                        'jumlah_belum_diterima'  => intval($item['jumlah']*$nilai_konversi),
                        'diskon'                 => $item['item_diskon'],
                        'tanggal_kirim'          => date('Y-m-d', strtotime($item['item_tanggal_kirim'])),
                        'harga_beli'             => $item['item_harga'],
                        'is_active'              => 1
                    );
                    
                    $pembelian_detail = $this->pembelian_detail_m->save($data_item); 
                    // die_dump($this->db->last_query());
                }

                if($array_input['link_'.$i])
                    {
                        foreach ($array_input['link_'.$i] as $data) {
                            $jumlah_item_link = explode(' ', $data['jumlah_item']); 
                            $jumlah_item = $jumlah_item_link[0];

                            $data_save_link = array(
                                'pembelian_detail_id'                  => $pembelian_detail,
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

                $get_harga_supplier = $this->supplier_item_m->get_by(array('supplier_id' => $idSupplier, 'item_id' => $item['item_id'], 'item_satuan_id' => $item['satuan']));
                $harga_supplier = object_to_array($get_harga_supplier);

                if($item['item_harga'] > $item['item_harga_lama'] || $item['item_harga'] < $item['item_harga_lama'])
                {
                    $data_harga_baru = array(
                        'supplier_item_id' => $harga_supplier[0]['id'],
                        'harga'            => $item['item_harga'],
                        'tanggal_efektif'  => date('Y-m-d')
                    );

                    $harga_baru = $this->supplier_harga_item_m->save($data_harga_baru);
                }
                
                $get_data = $this->item_m->get($item['item_id']);
                // die_dump($get_data);

                $get_sub_kategori = $this->item_sub_kategori_pembelian_m->get_by(array('item_sub_kategori_id' => $get_data->item_sub_kategori));
                $sub_kategori = object_to_array($get_sub_kategori);
                // die_dump($sub_kategori);

                if(empty($sub_kategori))
                {
                    $upd_stat = array(
                        'status' => 2
                    );
                    $update = $this->pembelian_m->save($upd_stat, $pembelian_id);
                    // die_dump($update);
                }
                else
                {
                    foreach ($variable as $key => $value) {
                       
                        $last_id = $this->persetujuan_po_m->get_last_id();

                        $data_persetujuan = array(
                            'persetujuan_pembelian_id'  => $last_id[0]['last_id']+1,
                            'pembelian_id'              => $pembelian_id,
                            'pembelian_detail'          => $pembelian_detail,
                            'user_level_id'             => $sub_kategori['user_level_id'],
                            'order'                     => $sub_kategori['level_order'],
                            'status'                    => 1
                        );

                        $save_persetujuan = $this->persetujuan_po_m->save($data_persetujuan);
                    }
                }
            $i++;
            }

            if ($pembelian_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data PO berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }

        redirect("pembelian/history");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->cabang__m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        // die_dump($max_id);
        
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // die_dump($trash_id);

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 1,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);
        // die_dump($this->db->last_query());

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Branch Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/cabang_");
    }

    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->cabang__m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Branch Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/cabang_");
    }

    public function get_item_satuan()
    {
        $item_id = $this->input->post('item_id');
        $supplier_id = $this->input->post('supplier_id');
        
        $get_data_sup_item = $this->supplier_item_m->get_by(array('item_id' => $item_id, 'supplier_id' => $supplier_id));
        $sup_item = object_to_array($get_data_sup_item);

        // die_dump($sup_item);
        $satuan_terkecil = $this->item_satuan_m->get_satuan_terkecil($item_id)->result_array();

        $data_konversi = $this->item_satuan_m->get_data_konversi($item_id, $satuan_terkecil[0]['id']);

        $i = 0;
        foreach ($data_konversi as $data) 
        {
            $harga = $this->supplier_harga_item_m->get_harga($sup_item[$i]['id'])->result_array();
            $data_konversi[$i]['harga'] = intval($harga[0]['harga']);
            $data_konversi[$i]['minimum_order'] = intval($sup_item[$i]['minimum_order']);
            $data_konversi[$i]['kelipatan_order'] = intval($sup_item[$i]['kelipatan_order']);
            // die_dump($data_konversi);
            $i++;
        }

        // die_dump($data_konversi);
        echo json_encode($data_konversi);
    }

    public function cetak_po($id)
    {

        $this->load->library('mpdf/mpdf.php');


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
        $path_dokumen = './assets/mb/pages/pembelian/pembelian/doc/PO/'.$pembelian->id;
        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
       
        // $html = str_replace('{PAGECNT}', $mpdf->getPageCount(), $this->load->view('pembelian/pembelian/cetak/header', $body, true));
        $mpdf->SetHTMLHeader($this->load->view('pembelian/pembelian/cetak/header', $body, true));
        $mpdf->SetHTMLFooter($this->load->view('pembelian/pembelian/cetak/footer', $body, true));
        $mpdf->writeHTML($this->load->view('pembelian/pembelian/cetak/print_po', $body, true));

        $mpdf->Output('invoice_'.date('Y-m-d H:i:s').'.pdf', 'I');
        $mpdf->Output($path_dokumen.'/'.$filename, 'F');
   }


   public function cetak_ex($id)
    {

        // $assets = array();
        // $config = 'assets/pembelian/pembelian/view';
        // $this->config->load($config, true);

        // $assets = $this->config->item('assets', $config);

        // $form_data = $this->pembelian_m->get_data($id)->result_array();
        // $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        // $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        // $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);

        // $data_bayar = $this->pembayaran_status_m->get_by(array('transaksi_id' => $id), true);

        // if(count($data_bayar) == 0){
        //     $tanda_terima_faktur = $this->tanda_terima_faktur_m->get_by(array('pembelian_id' => $id), true);
        //     $data_bayar = $this->pembayaran_status_m->get_by(array('transaksi_id' => $tanda_terima_faktur->id), true);
        // }

        // $data_invoice = $this->pembelian_invoice_m->get_by(array('pembelian_id' => $id));
        // // die(dump($data_invoice));

        // Load the view
        $this->load->view('pembelian/pembelian/cetak/header_ex');
   }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */