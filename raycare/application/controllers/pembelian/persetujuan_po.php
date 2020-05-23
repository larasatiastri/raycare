<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_po extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'c869644fc39d9b0d25b907f0689ab829';                  // untuk check bit_access

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
        $this->load->model('pembelian/item_m');
        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('pembelian/pembelian_detail_tanggal_kirim_m');
        $this->load->model('pembelian/supplier_harga_item_m');
        $this->load->model('pembelian/pembelian_penawaran_m');
        $this->load->model('pembelian/pembelian_kredit_m');
        $this->load->model('pembelian/o_s_pembayaran_pembelian_m');
        $this->load->model('pembelian/supplier_tipe_pembayaran_m');
        $this->load->model('pembelian/supplier_m');
        $this->load->model('master/supplier/supplier_email_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_telepon_m');
        $this->load->model('master/master_tipe_bayar_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('pembelian/penerima_cabang_m');
        $this->load->model('pembelian/penerima_customer_m');
        $this->load->model('master/info_alamat_m');

        $this->load->model('pembelian/draft_po_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_po_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_po_history_m');
        $this->load->model('master/user_level_m');
        $this->load->model('master/user_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('gudang/inventory_m');
        $this->load->model('others/kotak_sampah_m');

        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');
        $this->load->model('pembelian/o_s_pmsn_m');
        $this->load->model('pembelian/o_s_pmsn_po_det_m');
        $this->load->model('pembelian/persetujuan_po/order_permintaan_barang_m');
        $this->load->model('pembelian/persetujuan_po/order_permintaan_barang_detail_m');
        $this->load->model('pembelian/persetujuan_po/order_permintaan_barang_detail_other_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_detail_m');

       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/pembelian/persetujuan_po/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Persetujuan PO', $this->session->userdata('language')), 
            'header'         => translate('Persetujuan PO', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/persetujuan_po/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add_persetujuan($id, $order)
    {
        $assets = array();
        $config = 'assets/pembelian/persetujuan_po/add';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
        // $user = $this->session->userdata('user_id');
        // $user = $this->session->userdata('user_id');
        $user_id = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');
        $date = date('Y-m-d H:i:s');

        $get_dibaca = $this->persetujuan_po_m->get_by(array('pembelian_id' => $id, 'user_level_id' => $this->session->userdata['level_id']));
        $dibaca = object_to_array($get_dibaca);

        // die_dump($dibaca);
        if($dibaca[0]['status'] < 2 && $dibaca[0]['status'] != 4)
        {
            $update = $this->persetujuan_po_m->update_persetujuan($id);
        }

        $data_pembelian['status'] = 2;
        $update_pembelian = $this->pembelian_m->edit_data($data_pembelian, $id);

        $data_status = array(
            'status'        => 2,
            'modified_by'   => $user_id,
            'modified_date' => $date
        );      

        $wheres_status = array(
            'transaksi_id'   => $id,
            'tipe_transaksi' => 1
        );    

        $update_status = $this->pembayaran_status_m->update_by($user_id,$data_status,$wheres_status);  

        $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

        $wheres_bayar_detail = array(
            'transaksi_id'   => $id,
            'tipe_transaksi' => 1,
            'tipe_pengajuan' => 0,
            'tipe'           => 1,
            'user_level_id'  => $level_id
        );
        $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

        $wheres_bayar_detail_before = array(
            'transaksi_id'   => $id,
            'tipe_transaksi' => 1,
            'tipe_pengajuan' => 0,
            'tipe'           => 1,
            '`order`'        => $pembayaran_status_detail_id->order - 1
        );

        $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

        if(count($pembayaran_status_detail_before) != 0){
            $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
        }else{
            $waktu_proses = $pembayaran_status_id->created_date;
        }

        $datetime1 = new DateTime();
        $datetime2 = new DateTime($waktu_proses);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%a d %h h %i m %S s');

        $data_pembayaran_status_detail = array(
            'status'         => 1,
            'tanggal_proses' => date('Y-m-d H:i:s'),
            'user_proses'    => $user_id,
            'waktu_tunggu'   => $elapsed,
            'modified_by'    => $user_id,
            'modifed_date'   => date('Y-m-d H:i:s')
        );

        $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);
        // die_dump($this->db->last_query());

        $form_data = $this->pembelian_m->get_by(array('id' => $id), true);
        $form_data_supplier = $this->pembelian_m->get_data_supplier($id)->result();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data->tipe_pembayaran)->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);


        $data = array(
            'title'              => config_item('site_name').' | '. translate("Persetujuan Pembelian", $this->session->userdata("language")), 
            'header'             => translate("Persetujuan Pembelian", $this->session->userdata("language")), 
            'header_info'        => config_item('site_name'), 
            'breadcrumb'         => TRUE,
            'menus'              => $this->menus,
            'menu_tree'          => $this->menu_tree,
            'css_files'          => $assets['css'],
            'js_files'           => $assets['js'],
            'content_view'       => 'pembelian/persetujuan_po/add',
            'form_data'          => object_to_array($form_data),
            'form_data_supplier' => object_to_array($form_data_supplier),
            'tipe_bayar'         => object_to_array($tipe_bayar),
            'order'              => $order,
            'pk_value'           => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id, $order)
    {

        $assets = array();
        $config = 'assets/pembelian/persetujuan_po/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
        $get_dibaca = $this->persetujuan_po_m->get_by(array('pembelian_id' => $id, 'user_level_id' => $this->session->userdata['level_id']));
        $dibaca = object_to_array($get_dibaca);

        if($dibaca[0]['status'] < 2 && $dibaca[0]['status'] != 4)
        {
            $update = $this->persetujuan_po_m->update_persetujuan($id);
        // die_dump($this->db->last_query());
        }

        //$data_pembelian['status'] = 2;
        //$update_pembelian = $this->pembelian_m->edit_data($data_pembelian, $id);
       
        $form_data = $this->pembelian_m->get_by(array('id' => $id), true);
        $form_data_supplier = $this->pembelian_m->get_data_supplier($id)->result();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data->tipe_pembayaran)->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);


        $data = array(
            'title'              => config_item('site_name').' | '. translate("Persetujuan Pembelian", $this->session->userdata("language")), 
            'header'             => translate("Persetujuan Pembelian", $this->session->userdata("language")), 
            'header_info'        => config_item('site_name'), 
            'breadcrumb'         => TRUE,
            'menus'              => $this->menus,
            'menu_tree'          => $this->menu_tree,
            'css_files'          => $assets['css'],
            'js_files'           => $assets['js'],
            'content_view'       => 'pembelian/persetujuan_po/view',
            'form_data'          => object_to_array($form_data),
            'form_data_supplier' => object_to_array($form_data_supplier),
            'tipe_bayar'         => object_to_array($tipe_bayar),
            'order'              => $order,
            'pk_value'           => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_history($id, $order)
    {

        $assets = array();
        $config = 'assets/pembelian/persetujuan_po/view_history';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
        
       
        $form_data = $this->pembelian_m->get_by(array('id' => $id), true);
        $form_data_supplier = $this->pembelian_m->get_data_supplier($id)->result();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data->tipe_pembayaran)->result_array();


        $data = array(
            'title'              => config_item('site_name').' | '. translate("Persetujuan Pembelian", $this->session->userdata("language")), 
            'header'             => translate("Persetujuan Pembelian", $this->session->userdata("language")), 
            'header_info'        => config_item('site_name'), 
            'breadcrumb'         => TRUE,
            'menus'              => $this->menus,
            'menu_tree'          => $this->menu_tree,
            'css_files'          => $assets['css'],
            'js_files'           => $assets['js'],
            'content_view'       => 'pembelian/persetujuan_po/view_history',
            'form_data'          => object_to_array($form_data),
            'tipe_bayar'         => object_to_array($tipe_bayar),
            'form_data_supplier' => object_to_array($form_data_supplier),
            'order'              => $order,
            'pk_value'           => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */

    public function listing_pembelian()
    {   

        $result = $this->pembelian_m->get_datatable_persetujuan_pembelian();
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
            $get_data_persetujuan = $this->persetujuan_po_m->get_by(array('pembelian_id' => $row['id'], 'user_level_id' => $this->session->userdata('level_id')));
            $data_persetujuan = object_to_array($get_data_persetujuan);
            $action= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_po/view/'.$row['id'].'/'.$data_persetujuan[0]['order'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';

            $posisi = '';

            if($data_persetujuan[0]['order'] != 1){
                $data_before = $this->persetujuan_po_m->get_order_before($row['id'], $data_persetujuan[0]['order'])->result_array();
                $data_tolak = $this->pembelian_detail_m->get_by(array('pembelian_id' => $row['id'], 'is_active' => 1));
                $tolak = object_to_array($data_tolak);

                $data_after = $this->persetujuan_po_m->get_order_after($row['id'], $data_persetujuan[0]['order'])->result_array();
                if(count($data_after) != 0 && $data_after[0]['stat'] <= 2){
                    $posisi_user_level = $this->user_level_m->get_by(array('id' => $data_after[0]['user_level_id']), true);
                    $posisi = $posisi_user_level->nama;
                }

                $data_disetujui_oleh = $this->persetujuan_po_m->get_by(array('pembelian_id' => $row['id'], 'user_level_id' => $this->session->userdata('level_id'), 'disetujui_oleh' => null));
                $disetujui_oleh = object_to_array($data_disetujui_oleh);

                // die_dump($disetujui_oleh);
                if($data_before[0]['stat'] >= 3 && !empty($tolak) && !empty($disetujui_oleh))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_po/view/'.$row['id'].'/'.$data_persetujuan[0]['order'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Finish Process', $this->session->userdata('language')).'"  href="'.base_url().'pembelian/persetujuan_po/add_persetujuan/'.$row['id'].'/'.$data_persetujuan[0]['order'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>'; 

                    $posisi_user_level = $this->user_level_m->get_by(array('id' => $this->session->userdata('level_id')), true);
                    $posisi = $posisi_user_level->nama;  
                }elseif($data_before[0]['stat'] <= 2 )
                {
                    $posisi_user_level = $this->user_level_m->get_by(array('id' => $data_before[0]['user_level_id']), true);
                    $posisi = $posisi_user_level->nama;
                }
            }
            elseif($data_persetujuan[0]['order'] == 1)
            {
                if($data_persetujuan[0]['status'] <= 2){
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_po/view/'.$row['id'].'/'.$data_persetujuan[0]['order'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Finish Process', $this->session->userdata('language')).'"  href="'.base_url().'pembelian/persetujuan_po/add_persetujuan/'.$row['id'].'/'.$data_persetujuan[0]['order'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>'; 

                    $posisi_user_level = $this->user_level_m->get_by(array('id' => $this->session->userdata('level_id')), true);
                    $posisi = $posisi_user_level->nama;    
                }if($data_persetujuan[0]['status'] > 2){
                    $data_after = $this->persetujuan_po_m->get_order_after($row['id'], $data_persetujuan[0]['order'])->result_array();
                    if(count($data_after) != 0){
                        $posisi_user_level = $this->user_level_m->get_by(array('id' => $data_after[0]['user_level_id']), true);
                        $posisi = $posisi_user_level->nama;
                    }
                }
            }
            
            // die_dump($data_before);
            
            $info = '';
            $sekarang = date('Y-m-d');


            $output['data'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left inline-button-table">'.$row['no_po'].'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_sup'].' ['.$row['kode_sup'].']</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_pesan'])).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_kadaluarsa'])).'</div>',
                $row['keterangan'],
                $row['user'],
                '<div class="text-left inline-button-table">'.$posisi.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>'  
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pembelian_history()
    {   

        $result = $this->pembelian_m->get_datatable_persetujuan_pembelian_history();
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
            $get_data_persetujuan = $this->persetujuan_po_history_m->get_by(array('pembelian_id' => $row['id'], 'user_level_id' => $this->session->userdata('level_id')));
            $data_persetujuan = object_to_array($get_data_persetujuan);
            $action= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_po/view_history/'.$row['id'].'/'.$data_persetujuan[0]['order'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';

            
            // die_dump($data_before);
            
            $info = '';
            $sekarang = date('Y-m-d');


            $output['data'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-center">'.$row['no_po'].'</div>',
                $row['nama_sup'].' ['.$row['kode_sup'].']',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_pesan'])).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_kadaluarsa'])).'</div>',
                $row['keterangan'],
                '<div class="text-center inline-button-table">'.$action.'</div>'  
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_persetujuan($id)
    {        
        $result = $this->pmb_detail_m->get_datatable($id);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $count = count($records->result_array());

        $i=0;
        $harga ='';
        $input = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            
            $harga = $harga + ($row['item_jumlah']*$row['item_harga']);
            // die_dump($count);
            if ($i == $count) {
                $input = '<input type="hidden" id="sub_total" name="sub_total" value="'.$harga.'""></div>';
            }

            $action = '';

            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.$row['item_kode'].'</div>',
                '<div class="text-center">'.$row['item_nama'].'</div>',
                '<input type="text" value="'.$row['item_jumlah'].' '.$row['item_satuan'].'" id="jumlah_'.$row['id'].'" readonly style="background-color: transparent;border: 0px solid;">',
                '<div class="text-right">Rp. ' . number_format($row['item_harga'],0,'','.').',-</div>',
                '<div class="text-right">Rp. ' . number_format(($row['item_harga']*$row['item_jumlah']),0,'','.').',-'.$input.'</div>' 
            );
        }

        echo json_encode($output);
    }

    public function listing_data_persetujuan($id=null)
    {        
        $result = $this->persetujuan_po_m->get_datatable($id);
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
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/history_persetujuan_po/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
            $status = '';
            
            if($row['status'] == 1)
            {                
                $status = '<div class="text-center"><span class="label label-md label-warning">Belum Dibaca</span></div>';
            }
            if($row['status'] == 2)
            {                
                $status = '<div class="text-center"><span class="label label-md label-success">Dibaca</span></div>';
            }
            if($row['status'] == 3)
            {                
                $status = '<div class="text-center"><span class="label label-md label-info">Disetujui</span></div>';
            }
            else if($row['status'] == 4)
            {
                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            }
            

            $sekarang = date('Y M d');
            $tgl_baca = ($row['tanggal_baca'] != NULL && $row['tanggal_baca'] !='')?date('d-m-Y', strtotime($row['tanggal_baca'])):'';
            $tgl_setuju = ($row['tanggal_persetujuan'] != NULL && $row['tanggal_persetujuan'] != '')?date('d-m-Y', strtotime($row['tanggal_persetujuan'])):'';


            $output['data'][] = array(
                '<div class="text-left">'.$row['user_level'].'</div>',
                '<div class="text-center">'.$row['order'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tgl_baca.'</div>',
                '<div class="text-left">'.$row['dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tgl_setuju.'</div>',
                '<div class="text-left">'.$row['disetujui_oleh'].'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>'
            );

            if($row['status'] == 4)
            {
                break;
            }
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_data_persetujuan_history($id=null)
    {        
        $result = $this->persetujuan_po_history_m->get_datatable($id);
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
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/history_persetujuan_po/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
            $status = '';
            
            if($row['status'] == 1)
            {                
                $status = '<div class="text-center"><span class="label label-md label-warning">Belum Dibaca</span></div>';
            }
            if($row['status'] == 2)
            {                
                $status = '<div class="text-center"><span class="label label-md label-success">Dibaca</span></div>';
            }
            if($row['status'] == 3)
            {                
                $status = '<div class="text-center"><span class="label label-md label-info">Disetujui</span></div>';
            }
            else if($row['status'] == 4)
            {
                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            }
            

            $sekarang = date('Y-m-d');
            $tgl_baca = ($row['tanggal_baca'] != NULL && $row['tanggal_baca'] !='')?date('d-m-Y', strtotime($row['tanggal_baca'])):'';
            $tgl_setuju = ($row['tanggal_persetujuan'] != NULL && $row['tanggal_persetujuan'] != '')?date('d-m-Y', strtotime($row['tanggal_persetujuan'])):'';


            $output['data'][] = array(
                '<div class="text-left">'.$row['user_level'].'</div>',
                '<div class="text-center">'.$row['order'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tgl_baca.'</div>',
                '<div class="text-left">'.$row['dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tgl_setuju.'</div>',
                '<div class="text-left">'.$row['disetujui_oleh'].'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>'
            );

            if($row['status'] == 4)
            {
                break;
            }
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        // $command = $this->input->post('command');
        $array_input = $this->input->post();

        // die(dump($array_input));
        $user = $this->session->userdata('level_id');
        $user_id = $this->session->userdata('user_id');
        $found_last = false;
        $found_tolak = false;
        $status = 3;
        $keterangan = 'OK';

        $data_pembelian = $this->pembelian_m->get_by(array('id' => $array_input['pembelian_id']), true);
        $supplier = $this->supplier_m->get_by(array('id' => $data_pembelian->supplier_id), true);

        foreach ($array_input['items'] as $data) 
        {
            
            $get_last_order = $this->persetujuan_po_m->get_last_order($array_input['pembelian_id'], $data['id_detail'])->row(0);

            if(isset($data['jumlah']))
            {
                if (isset($data['checkbox']))
                {
                    $update_pembelian_detail = array(
                        '`status`'              => 3,
                        'jumlah_disetujui'      => 0,
                        'jumlah_belum_diterima' => 0,
                        'keterangan'            => $data['keterangan'],
                        'disetujui_oleh'        => $this->session->userdata('user_id'),
                        'modified_by'           => $this->session->userdata('user_id'),
                        'modified_date'         => date('Y-m-d H:i:s')
                    );
                    $update = $this->pembelian_detail_m->edit_data($update_pembelian_detail, $data['id_detail']);

                    $data_persetujuan = array(
                        '`status`'            => 4,
                        'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                        'disetujui_oleh'      => $this->session->userdata('user_id'),
                        'jumlah_persetujuan'  => 0,
                        'satuan_id'           => $data['satuan_nama'],
                        'keterangan'          => $data['keterangan']
                    );  

                    $wheres = array(
                        'pembelian_id'        => $array_input['pembelian_id'],
                        'pembelian_detail_id' => $data['id_detail'],
                    );
                    
                    $update_persetujuan = $this->persetujuan_po_m->update_by($user_id, $data_persetujuan, $wheres);
                    // die_dump($this->db->last_query());

                    if($array_input['order_persetujuan'] == $get_last_order->max_order)
                    {
                        $found_last = true;
                        $found_tolak = true;
                        
                    }

                }
                else
                {
                    $nilai_konversi         = $this->item_m->get_nilai_konversi($data['satuan_nama']);

                    $update_pembelian_detail = array(
                        '`status`'              => 2,
                        'jumlah_disetujui'      => $data['jumlah'],
                        'jumlah_belum_diterima' => ($data['jumlah']*$nilai_konversi),
                        'keterangan'            => $data['keterangan'],
                        'disetujui_oleh'        => $this->session->userdata('user_id'),
                        'modified_by'           => $this->session->userdata('user_id'),
                        'modified_date'         => date('Y-m-d H:i:s')
                    );
                    $update = $this->pembelian_detail_m->edit_data($update_pembelian_detail, $data['id_detail']);

                    $data_persetujuan = array(
                        '`status`'            => 3,
                        'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                        'keterangan'          => $data['keterangan'],
                        'disetujui_oleh'      => $this->session->userdata('user_id'),
                        'jumlah_persetujuan'  => $data['jumlah'],
                        'satuan_id'           => $data['satuan_nama']
                    );  

                    $wheres = array(
                        'pembelian_id'        => $array_input['pembelian_id'],
                        'pembelian_detail_id' => $data['id_detail'],
                        '`order`'             => $array_input['order_persetujuan']
                    );
                    
                    $update_persetujuan = $this->persetujuan_po_m->update_by($user_id, $data_persetujuan, $wheres);
                    // die_dump($this->db->last_query());

                    if($array_input['order_persetujuan'] == $get_last_order->max_order)
                    {
                        $found_last = true;
                    }
                }

                $data_os_pesan = $this->o_s_pmsn_po_det_m->get_by(array('pembelian_id' => $array_input['pembelian_id'], 'pembelian_detail_id' => $data['id_detail']));
                $data_os_pesan = object_to_array($data_os_pesan);

                foreach ($data_os_pesan as $row_os_pesan) {
                            
                    $level_id = $this->session->userdata('level_id');
                    $user_id = $this->session->userdata('user_id');

                    $data_permintaan = array(
                        'status' => 3
                    );
                    $wheres = array(
                        'id'    => $row_os_pesan['pemesanan_detail_id']
                    );

                    $update_permintaan = $this->order_permintaan_barang_m->update_by($user_id, $data_permintaan, $wheres);

                    
                    $wheres_status = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1
                    );    

                    $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);

                    $wheres_bayar_detail = array(
                        'transaksi_id'   => $row_os_pesan['pemesanan_detail_id'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        'tipe'           => 1,
                        'user_level_id'  => $level_id
                    );
                    $permintaan_status_detail = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

                    $wheres_bayar_detail_before = array(
                        'transaksi_id'   => $row_os_pesan['pemesanan_detail_id'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        'tipe'           => 1,
                        '`order`'        => $permintaan_status_detail->order - 1
                    );

                    $pembayaran_status_detail_before = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_before, true);

                    if(count($pembayaran_status_detail_before) != 0){
                        $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                    }else{
                        $waktu_proses = $permintaan_status_id->created_date;
                    }

                    $datetime1 = new DateTime();
                    $datetime2 = new DateTime($waktu_proses);
                    $interval = $datetime1->diff($datetime2);
                    $elapsed = $interval->format('%a d %h h %i m %S s');

                    $data_pembayaran_status_detail = array(
                        'status'         => 2,
                        'tanggal_proses' => date('Y-m-d H:i:s'),
                        'user_proses'    => $user_id,
                        'waktu_tunggu'   => $elapsed,
                        'keterangan'     => $row['keterangan'],
                        'modified_by'    => $user_id,
                        'modifed_date'   => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->permintaan_status_detail_m->edit_data($data_pembayaran_status_detail, $permintaan_status_detail->id); 
                    
                    $wheres_bayar_detail_after = array(
                        'transaksi_id'   => $row_os_pesan['pemesanan_detail_id'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        '`order`'        => $permintaan_status_detail->order + 1
                    );

                    $pembayaran_status_detail_after = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_after, true);

                    $data_status_after = array(
                        'status' => 3,
                        'tipe_persetujuan' => 1,
                        'modified_by'   => $user_id,
                        'modified_date' => date('Y-m-d H:i:s')
                    );   

                    if($array_input['tipe'] == 3){
                        if($array_input['order_setuju'] == $maksimum->max_order ){
                            $data_status_after['status'] = 3;
                            $data_status_after['tipe_persetujuan'] = 2;
                        }
                    }

                    if(count($pembayaran_status_detail_after) != 0){
                        $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                        $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
                    }

                    $wheres_status = array(
                        'transaksi_id'   => $row_os_pesan['pemesanan_detail_id'],
                        'tipe_transaksi' => 1
                    );    

                    $update_status = $this->permintaan_status_m->update_by($user_id,$data_status_after,$wheres_status);
                }

                $wheres_status = array(
                    'transaksi_id'   => $array_input['pembelian_id'],
                    'tipe_transaksi' => 1
                );    

                $permintaan_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

                $wheres_bayar_detail = array(
                    'transaksi_id'   => $array_input['pembelian_id'],
                    'tipe_transaksi' => 1,
                    'tipe_pengajuan' => 0,
                    'tipe'           => 1,
                    'user_level_id'  => $level_id
                );
                $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

                $wheres_bayar_detail_before = array(
                    'transaksi_id'   => $array_input['pembelian_id'],
                    'tipe_transaksi' => 1,
                    'tipe_pengajuan' => 0,
                    'tipe'           => 1,
                    '`order`'        => $pembayaran_status_detail_id->order - 1
                );

                $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

                if(count($pembayaran_status_detail_before) != 0){
                    $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                }else{
                    $waktu_proses = $permintaan_status_id->created_date;
                }

                $datetime1 = new DateTime();
                $datetime2 = new DateTime($waktu_proses);
                $interval = $datetime1->diff($datetime2);
                $elapsed = $interval->format('%a d %h h %i m %S s');

                $data_pembayaran_status_detail = array(
                    'status'         => 3,
                    'tanggal_proses' => date('Y-m-d H:i:s'),
                    'user_proses'    => $user_id,
                    'waktu_tunggu'   => $elapsed,
                    'modified_by'    => $user_id,
                    'modifed_date'   => date('Y-m-d H:i:s')
                );

                $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

                $wheres_bayar_detail_after = array(
                    'transaksi_id'   => $array_input['pembelian_id'],
                    'tipe_transaksi' => 1,
                    'tipe_pengajuan' => 0,
                    '`order`'        => $pembayaran_status_detail_id->order + 1
                );

                $pembayaran_status_detail_after = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_after, true);

                $data_status_after = array(
                    'status'        => 3,
                    'modified_by'   => $user_id,
                    'modified_date' => date('Y-m-d H:i:s')
                );      

                if(count($pembayaran_status_detail_after) != 0){
                    $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                    $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
                }

                $wheres_status = array(
                    'transaksi_id'   => $array_input['pembelian_id'],
                    'tipe_transaksi' => 1
                );    

                $update_status = $this->pembayaran_status_m->update_by($user_id,$data_status_after,$wheres_status); 
            }
        }

       // if($found_last == false)
        //{
       // }
        if($found_last == true)
        {
           // $this->kirim_email_po($array_input['pembelian_id']);
            if($found_tolak == true)
            {
                $status = 6;
                $keterangan = 'Ditolak';
                $data_pembelian = array(
                    '`status`' =>$status,
                    'modified_by'      => $this->session->userdata('user_id'),
                    'modified_date'    => date('Y-m-d H:i:s'),
                );

                $update_pembelian = $this->pembelian_m->edit_data($data_pembelian,$array_input['pembelian_id']);
            }
            else
            {
                $status = 4;
                $keterangan = 'OK';
                $data_pembelian = array(
                    '`status`' => $status,
                    'modified_by'      => $this->session->userdata('user_id'),
                    'modified_date'    => date('Y-m-d H:i:s'),
                );

                $update_pembelian = $this->pembelian_m->edit_data($data_pembelian,$array_input['pembelian_id']);

                $pembelian = $this->pembelian_m->get_by(array('id' => $array_input['pembelian_id']), true);
                $pembelian_kredit = $this->pembelian_kredit_m->get_by(array('pembelian_id' => $array_input['pembelian_id']));

                $tempo = $this->supplier_tipe_pembayaran_m->get($pembelian->tipe_pembayaran);
                if($tempo->tipe_bayar_id == 1 || $tempo->tipe_bayar_id == 2){
                    if(count($pembelian_kredit) == 0){
                        if($pembelian->dp != 0){

                            $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                            $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                            $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                            $data_o_s = array(
                                'id'           => $id_os_po,
                                'pembelian_id' => $array_input['pembelian_id'],
                                'tanggal'      => date('Y-m-d', strtotime($pembelian->tanggal_pesan)),
                                'nominal'      => ($pembelian->dp/100)*$pembelian->grand_total,
                                'status'       => 1,
                                'created_by'   => $pembelian->created_by,
                                'created_date' => date('Y-m-d H:i:s')
                            );
                            $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                        }if($pembelian->dp == 0){

                            $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                            $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                            $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                            $data_o_s = array(
                                'id'           => $id_os_po,
                                'pembelian_id' => $array_input['pembelian_id'],
                                'tanggal'      => date('Y-m-d', strtotime($pembelian->tanggal_pesan)),
                                'nominal'      => $pembelian->grand_total,
                                'status'       => 1,
                                'created_by'   => $pembelian->created_by,
                                'created_date' => date('Y-m-d H:i:s')
                            );
                            $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                        }
                    }elseif(count($pembelian_kredit) != 0){
                        $pembelian_kredit = object_to_array($pembelian_kredit);
                        if($pembelian->dp != 0){

                            $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                            $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                            $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                            $data_o_s = array(
                                'id'           => $id_os_po,
                                'pembelian_id' => $array_input['pembelian_id'],
                                'tanggal'      => date('Y-m-d', strtotime($pembelian->tanggal_pesan)),
                                'nominal'      => ($pembelian->dp/100)*$pembelian->grand_total,
                                'status'       => 1,
                                'created_by'   => $pembelian->created_by,
                                'created_date' => date('Y-m-d H:i:s')
                            );
                            $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                        }
                        foreach ($pembelian_kredit as $beli_kredit) {
                           
                            $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                            $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                            $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                            
                            $data_o_s = array(
                                'id'           => $id_os_po,
                                'pembelian_id' => $array_input['pembelian_id'],
                                'tanggal'      => date('Y-m-d', strtotime($beli_kredit['tanggal'])),
                                'nominal'      => $beli_kredit['harga_setoran'],
                                'status'       => 1,
                                'created_by'   => $beli_kredit['created_by'],
                                'created_date' => date('Y-m-d H:i:s')
                            );
                            $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                        }
                    }
                }if($tempo->tipe_bayar_id == 3){
                    if(count($pembelian_kredit) == 0){
                        if($pembelian->dp != 0){

                            $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                            $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                            $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                            $data_o_s = array(
                                'id'           => $id_os_po,
                                'pembelian_id' => $array_input['pembelian_id'],
                                'tanggal'      => date('Y-m-d', strtotime($pembelian->tanggal_pesan)),
                                'nominal'      => ($pembelian->dp/100)*$pembelian->grand_total,
                                'status'       => 1,
                                'created_by'   => $pembelian->created_by,
                                'created_date' => date('Y-m-d H:i:s')
                            );
                            $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                        }
                    }elseif(count($pembelian_kredit) != 0){
                        if($pembelian->dp != 0){

                            $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                            $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                            $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                            $data_o_s = array(
                                'id'           => $id_os_po,
                                'pembelian_id' => $array_input['pembelian_id'],
                                'tanggal'      => date('Y-m-d', strtotime($pembelian->tanggal_pesan)),
                                'nominal'      => ($pembelian->dp/100)*$pembelian->grand_total,
                                'status'       => 1,
                                'created_by'   => $pembelian->created_by,
                                'created_date' => date('Y-m-d H:i:s')
                            );
                            $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                        }

                        $pembelian_kredit = object_to_array($pembelian_kredit);
                        foreach ($pembelian_kredit as $beli_kredit) {
                           
                            $last_id_o_s = $this->o_s_pembayaran_pembelian_m->get_max_id_os_po()->result_array();
                            $last_id_o_s = intval($last_id_o_s[0]['max_id'])+1;

                            $format_id_o_s   = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                            $id_os_po      = sprintf($format_id_o_s, $last_id_o_s, 4);

                            $data_o_s = array(
                                'id'           => $id_os_po,
                                'pembelian_id' => $array_input['pembelian_id'],
                                'tanggal'      => date('Y-m-d', strtotime($beli_kredit['tanggal'])),
                                'nominal'      => $beli_kredit['harga_setoran'],
                                'status'       => 1,
                                'created_by'   => $beli_kredit['created_by'],
                                'created_date' => date('Y-m-d H:i:s')
                            );
                            $os_po = $this->o_s_pembayaran_pembelian_m->add_data($data_o_s);
                        }
                    }
                }

            }
            $get_persetujuan_detail = $this->persetujuan_po_m->get_by(array('pembelian_id' => $array_input['pembelian_id']));
            $persetujuan_detail = object_to_array($get_persetujuan_detail);

            // die_dump($persetujuan_detail);
            foreach ($persetujuan_detail as $persetujuan_detail_history) {
                
                $data_history = array(
                    'pembelian_id'        => $persetujuan_detail_history['pembelian_id'],
                    'pembelian_detail_id' => $persetujuan_detail_history['pembelian_detail_id'],
                    'user_level_id'       => $persetujuan_detail_history['user_level_id'],
                    '`order`'             => $persetujuan_detail_history['order'],
                    '`status`'            => $persetujuan_detail_history['status'],
                    'tanggal_baca'        => date('Y-m-d H:i:s', strtotime($persetujuan_detail_history['tanggal_baca'])),
                    'dibaca_oleh'         => $persetujuan_detail_history['dibaca_oleh'],
                    'tanggal_persetujuan' => date('Y-m-d H:i:s', strtotime($persetujuan_detail_history['tanggal_persetujuan'])),
                    'disetujui_oleh'      => $persetujuan_detail_history['disetujui_oleh'],
                    'jumlah_persetujuan'  => $persetujuan_detail_history['jumlah_persetujuan'],
                    'satuan_id'           => $persetujuan_detail_history['satuan_id'],
                    'keterangan'          => $persetujuan_detail_history['keterangan'],
                    'is_active'           => $persetujuan_detail_history['is_active']
                );

                $persetujuan_history_id = $this->persetujuan_po_history_m->save($data_history);

                $wheres_setuju = array(
                    'persetujuan_pembelian_id' => $persetujuan_detail_history['persetujuan_pembelian_id']
                );
                $delete_persetujuan = $this->persetujuan_po_m->delete_by($wheres_setuju);
                // die_dump($this->db->last_query());
            }
        }elseif($found_last == false){
            $nama_supplier = str_replace(' ', '_', $supplier->nama);
            sent_notification_po($data_pembelian->jenis_pembelian,$nama_supplier, 2);
        }

        $level_id = $this->session->userdata('level_id');
        $user_id = $this->session->userdata('user_id');
        $user_level_next = ($data_pembelian->jenis_pembelian == 1)?32:9;
        $user_keuangan = $this->user_level_m->get($user_level_next);
        $data_bayar = array(
            'status'          => $status,
            'user_level_id'   => $user_level_next,
            'divisi'          => $user_keuangan->divisi_id,
        );

        $wheres_bayar = array(
            'transaksi_id'   => $array_input['pembelian_id'],
            'tipe_transaksi' => 1
        );
        $pembayaran_status = $this->pembayaran_status_m->update_by($user_id,$data_bayar,$wheres_bayar);

        $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_bayar, true);

        $wheres_bayar_detail = array(
            'pembayaran_status_id' => $pembayaran_status_id->id,
            'tipe_pengajuan'       => 0,
            'tipe'                 => 1,
            'user_level_id'        => $level_id
        );
        $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

        $wheres_bayar_detail_before = array(
            'pembayaran_status_id' => $pembayaran_status_id->id,
            'tipe_pengajuan'       => 0,
            'tipe'                 => 1,
            '`order`'              => $pembayaran_status_detail_id->order - 1
        );

        $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

        if(count($pembayaran_status_detail_before) != 0){
            $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
        }else{
            $waktu_proses = $pembayaran_status_id->created_date;
        }

        $datetime1 = new DateTime();
        $datetime2 = new DateTime($waktu_proses);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%a d %h h %i m %S s');

        $data_pembayaran_status_detail = array(
            'status'         => 2,
            'tanggal_proses' => date('Y-m-d H:i:s'),
            'user_proses'    => $user_id,
            'waktu_tunggu'   => $elapsed,
            'keterangan'     => $keterangan,
            'modified_by'    => $user_id,
            'modifed_date'   => date('Y-m-d H:i:s')
        );

        $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);
       
        redirect("pembelian/persetujuan_po");
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

    public function history()
    {
        $assets = array();
        $config = 'assets/pembelian/persetujuan_po/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Persetujuan PO', $this->session->userdata('language')), 
            'header'         => translate('History Persetujuan PO', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/persetujuan_po/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function kirim_email_po($id)
    {
        $this->load->library('mpdf/mpdf.php');

        $pembelian = $this->pembelian_m->get_by(array('id' => $id, 'is_active' => 1), true);
        $pembelian_detail = $this->pembelian_detail_m->get_data_detail_invoice($id);

        $supplier = $this->supplier_m->get($pembelian->supplier_id);
        $supplier_email = $this->supplier_email_m->get_by(array('supplier_id' => $supplier->id, 'is_active' => 1));
        $supplier_email = object_to_array($supplier_email);

        $mpdf = new mPDF('utf-8','A4', 1, '', 10, 10, 28, 28, 0, 5);
        
        $body = array(
            'no_cetak' => $no_cetak,
            'pembelian'  => object_to_array($pembelian),
            'pembelian_detail'  => object_to_array($pembelian_detail)
        );

        $stylesheets = file_get_contents(base_url().'assets/metronic/global/plugins/bootstrap/css/bootstrap.css');
        $filename = $pembelian->no_pembelian.'.pdf';
        $filename = str_replace('/', '_', $filename);
        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/pembelian/pembelian_obat/doc/PO/'.$pembelian->id;
        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
        $mpdf->writeHTML($stylesheets, 1);
       
        $mpdf->SetHTMLHeader($this->load->view('pembelian/pembelian_obat/cetak/header', $body, true));
        $mpdf->SetHTMLFooter($this->load->view('pembelian/pembelian_obat/cetak/footer', $body, true));
        $mpdf->writeHTML($this->load->view('pembelian/pembelian_obat/cetak/print_po', $body, true));
 
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
        $this->email->from("Puchasing PT. Raycare Health Solution");
        foreach ($supplier_email as $email) {
            $this->email->to($email['email']); 
        }
        $this->email->cc('purc_rhs@yahoo.com');    
        $this->email->reply_to('purc_rhs@yahoo.com', 'Purchasing PT. Raycare Health Solution');                 
        $this->email->subject('Pembelian '.$pembelian->no_pembelian);
        $this->email->message("Kepada ".$supplier->nama.",\nBerikut kami lampirkan PO PT. Raycare Health Solution (Please See Attach).\n\n\n\n\nThank and Regards,\n\n".$this->session->userdata('nama_lengkap'));
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

    public function tolak_po($pembelian_id)
    {
        $data_po = $this->pembelian_m->get_by(array('id' => $pembelian_id), true);
        $data = array( 
            'pembelian_id'             => $pembelian_id,
            'tipe_transaksi'           => 1,
            'jenis_pembelian'          => $data_po->jenis_pembelian
        );

        $this->load->view('pembelian/persetujuan_po/modal_tolak_po', $data);
    }

    public function reject_po()
    {
        $array_input = $this->input->post();

        $user_id = $this->session->userdata('user_id');

        $data_po = array(
            'status' => 6,
            'keterangan' => $array_input['keterangan_tolak'],
            'modified_by' => $this->session->userdata('user_id'),
            'modified_date'         => date('Y-m-d H:i:s')
        );

        $update_po = $this->pembelian_m->edit_data($data_po, $array_input['pembelian_id']);


        $update_pembelian_detail = array(
            '`status`'              => 3,
            'jumlah_disetujui'      => 0,
            'jumlah_belum_diterima' => 0,
            'keterangan'            => $array_input['keterangan_tolak'],
            'disetujui_oleh'        => $this->session->userdata('user_id'),
            'modified_by'           => $this->session->userdata('user_id'),
            'modified_date'         => date('Y-m-d H:i:s')
        );

        $where_po_detail = array(
            'pembelian_id'        => $array_input['pembelian_id']
        );
        $update = $this->pembelian_detail_m->update_by($user_id,$update_pembelian_detail, $where_po_detail);

        $data_persetujuan = array(
            '`status`'            => 4,
            'tanggal_persetujuan' => date('Y-m-d H:i:s'),
            'disetujui_oleh'      => $this->session->userdata('user_id'),
            'jumlah_persetujuan'  => 0,
            'satuan_id'           => $array_input['satuan_nama'],
            'keterangan'          => $array_input['keterangan_tolak']
        );  

        $wheres = array(
            'pembelian_id'        => $array_input['pembelian_id']
        );
        
        $update_persetujuan = $this->persetujuan_po_m->update_by($user_id, $data_persetujuan, $wheres);

        $get_persetujuan_detail = $this->persetujuan_po_m->get_by(array('pembelian_id' => $array_input['pembelian_id']));
        $persetujuan_detail = object_to_array($get_persetujuan_detail);

        // die_dump($persetujuan_detail);
        foreach ($persetujuan_detail as $persetujuan_detail_history) {
            
            $data_history = array(
                'pembelian_id'        => $persetujuan_detail_history['pembelian_id'],
                'pembelian_detail_id' => $persetujuan_detail_history['pembelian_detail_id'],
                'user_level_id'       => $persetujuan_detail_history['user_level_id'],
                '`order`'             => $persetujuan_detail_history['order'],
                '`status`'            => $persetujuan_detail_history['status'],
                'tanggal_baca'        => date('Y-m-d H:i:s', strtotime($persetujuan_detail_history['tanggal_baca'])),
                'dibaca_oleh'         => $persetujuan_detail_history['dibaca_oleh'],
                'tanggal_persetujuan' => date('Y-m-d H:i:s', strtotime($persetujuan_detail_history['tanggal_persetujuan'])),
                'disetujui_oleh'      => $persetujuan_detail_history['disetujui_oleh'],
                'jumlah_persetujuan'  => $persetujuan_detail_history['jumlah_persetujuan'],
                'satuan_id'           => $persetujuan_detail_history['satuan_id'],
                'keterangan'          => $persetujuan_detail_history['keterangan'],
                'is_active'           => $persetujuan_detail_history['is_active']
            );

            $persetujuan_history_id = $this->persetujuan_po_history_m->save($data_history);

            $wheres_setuju = array(
                'persetujuan_pembelian_id' => $persetujuan_detail_history['persetujuan_pembelian_id']
            );
            $delete_persetujuan = $this->persetujuan_po_m->delete_by($wheres_setuju);
            // die_dump($this->db->last_query());
        }

        $flashdata = array(
            "type"     => "error",
            "msg"      => translate("PO Ditolak", $this->session->userdata("language")),
            "msgTitle" => translate("Berhasil", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);

        redirect('pembelian/persetujuan_po');
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */