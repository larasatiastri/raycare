<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resep_obat extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '3cc510cc30508664317424e29b34a162';                  // untuk check bit_access

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

        $this->load->model('others/kotak_sampah_m');
        $this->load->model('klinik_hd/tindakan_hd_m');
        $this->load->model('klinik_hd/tindakan_hd_item_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_detail_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_detail_identitas_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual_m');
        $this->load->model('klinik_hd/bed_m');
        $this->load->model('apotik/item_m');
        $this->load->model('apotik/item_satuan_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('apotik/transfer_item/inventory_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_detail_m');
        $this->load->model('apotik/permintaan_box_paket_m');
        $this->load->model('apotik/box_paket/t_box_paket_m');
        $this->load->model('apotik/box_paket/t_box_paket_detail_m');
        $this->load->model('reservasi/invoice/draf_invoice_m');   
        $this->load->model('reservasi/invoice/draf_invoice_detail_m'); 
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/resep_obat/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Resep Obat', $this->session->userdata('language')), 
            'header'         => translate('Resep Obat', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/resep_obat/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/apotik/resep_obat/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Resep Obat', $this->session->userdata('language')), 
            'header'         => translate('History Resep Obat', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/resep_obat/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/apotik/resep_obat/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->tindakan_resep_obat_m->get($id);
        $form_data_item = $this->tindakan_resep_obat_detail_m->get_data_item($id,0)->result_array();
        $form_data_item_pulang = $this->tindakan_resep_obat_detail_m->get_data_item($id,1)->result_array();

        $data = array(
            'title'                    => config_item('site_name').' | '.translate('View Resep Obat', $this->session->userdata('language')), 
            'header'                   => translate('View Resep Obat', $this->session->userdata('language')), 
            'header_info'              => 'RayCare', 
            'breadcrumb'               => true,
            'menus'                    => $this->menus,
            'menu_tree'                => $this->menu_tree,
            'css_files'                => $assets['css'],
            'js_files'                 => $assets['js'],
            'form_data'                => object_to_array($form_data),
            'form_data_item'           => object_to_array($form_data_item),
            'form_data_item_pulang'    => object_to_array($form_data_item_pulang),
            'content_view'             => 'apotik/resep_obat/view_resep_history',
            'pk_value'                 => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function view_manual($id)
    {
        $assets = array();
        $config = 'assets/apotik/resep_obat/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->tindakan_resep_obat_m->get($id);
        $form_data_item = $this->tindakan_resep_obat_manual_m->get_by(array('tindakan_resep_obat_id' => $id, 'is_active' => 1));

        $data = array(
            'title'                    => config_item('site_name').' | '.translate('View Resep Obat', $this->session->userdata('language')), 
            'header'                   => translate('View Resep Obat', $this->session->userdata('language')), 
            'header_info'              => 'RayCare', 
            'breadcrumb'               => true,
            'menus'                    => $this->menus,
            'menu_tree'                => $this->menu_tree,
            'css_files'                => $assets['css'],
            'js_files'                 => $assets['js'],
            'form_data'                => object_to_array($form_data),
            'form_data_item'           => object_to_array($form_data_item),
            'content_view'             => 'apotik/resep_obat/view_resep_manual_history',
            'pk_value'                 => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function proses($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/resep_obat/proses';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
               
        $form_data = $this->tindakan_resep_obat_m->get($id);

        $form_data_item = $this->tindakan_resep_obat_detail_m->get_data_item($id,0)->result_array();
        // die(dump($this->db->last_query()));
        $form_data_item_pulang = $this->tindakan_resep_obat_detail_m->get_data_item($id,1)->result_array();

        $cabang = $this->cabang_m->get($form_data->cabang_id);

        $path_model = 'klinik_hd/tindakan_hd_m';
        $wheres = array(
            'id' => $form_data->tindakan_id
        );
        $data_tindakan = get_data_api($cabang->url,$path_model,$wheres);
        $data_tindakan = object_to_array(json_decode($data_tindakan));

        $path_model = 'klinik_hd/bed_m';
        $wheres_bed = array(
            'id' => $data_tindakan[0]['bed_id']
        );
        $data_bed = get_data_api($cabang->url,$path_model,$wheres_bed);
        $data_bed = object_to_array(json_decode($data_bed));



        // die(dump($this->db->last_query()));
        $data = array(
            'title'            => config_item('site_name').' | '.translate("Proses Resep Obat", $this->session->userdata("language")), 
            'header'           => translate("Proses Resep Obat", $this->session->userdata("language")), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => TRUE,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'apotik/resep_obat/proses',
            'form_data'        => object_to_array($form_data),
            'form_data_item'   => object_to_array($form_data_item),
            'form_data_item_pulang'   => object_to_array($form_data_item_pulang),
            'data_bed'         => $data_bed[0],
            'pk_value'         => $id,                         //table primary key value
            'flag'             => 'proses',                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses_manual($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/resep_obat/proses_manual';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
               
        $form_data = $this->tindakan_resep_obat_m->get($id);

        $form_data_item = $this->tindakan_resep_obat_manual_m->get_by(array('tindakan_resep_obat_id' => $id));

        $cabang = $this->cabang_m->get($form_data->cabang_id);

        $path_model = 'klinik_hd/tindakan_hd_m';
        $wheres = array(
            'id' => $form_data->tindakan_id
        );
        $data_tindakan = get_data_api($cabang->url,$path_model,$wheres);
        $data_tindakan = object_to_array(json_decode($data_tindakan));


        $path_model = 'klinik_hd/bed_m';
        $wheres_bed = array(
            'id' => $data_tindakan[0]['bed_id']
        );
        $data_bed = get_data_api($cabang->url,$path_model,$wheres_bed);
        $data_bed = object_to_array(json_decode($data_bed));

        $data = array(
            'title'            => config_item('site_name').' | '.translate("Proses Resep", $this->session->userdata("language")), 
            'header'           => translate("Proses Resep", $this->session->userdata("language")), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => TRUE,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'apotik/resep_obat/proses_manual',
            'form_data'        => object_to_array($form_data),
            'form_data_item'   => object_to_array($form_data_item),
            'data_bed'   => $data_bed,
            'pk_value'         => $id,                         //table primary key value
            'flag'             => 'proses',                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses_box_paket($id)
    {
        $permintaan = $this->permintaan_box_paket_m->get_by(array('id' => $id), true);
        $pasien = $this->pasien_m->get_by(array('id' => $permintaan->pasien_id), true);
        $pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $permintaan->pasien_id, 'is_primary' => 1, 'is_active' => 1), true);
        $cabang = $this->cabang_m->get_by(array('id' => $permintaan->cabang_id), true);

        $data_tindakan = $this->tindakan_hd_m->get_by(array('id' => $permintaan->tindakan_id), true);
        $data_tindakan = object_to_array($data_tindakan);

        $data_bed = $this->bed_m->get_by(array('id' => $data_tindakan['bed_id']), true);
        $data_bed = object_to_array($data_bed);

        $data = array(
            'id'            => $id,
            'permintaan'    => object_to_array($permintaan),
            'pasien'        => object_to_array($pasien),
            'pasien_alamat' => object_to_array($pasien_alamat),
            'tindakan'      => $data_tindakan,
            'bed'           => $data_bed
        );
        $this->load->view('apotik/resep_obat/modal_proses_box_paket',$data);
    }

   
    public function listing($status)
    {        
        $result = $this->tindakan_resep_obat_m->get_datatable($status);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {

            if($row['tipe_resep'] == 1){
                $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-cogs"></i></a>
                <a title="'.translate('Batalkan', $this->session->userdata('language')).'" data-id="'.$row['id'].'"
            data-confirm="'.translate('Anda yakin untuk membatalkan resep ini?', $this->session->userdata('language')).'" name="delete_resep[]" class="btn btn-danger"><i class="fa fa-times"></i></a>';
           
                $tindakan_resep_detail = $this->tindakan_resep_obat_detail_m->get_by(array('tindakan_resep_obat_id' => $row['id'], 'is_active' => 1));
                $data_array = object_to_array($tindakan_resep_detail);

                $item = $this->item_m->get_item_tindakan_resep_detail($data_array[0]['tindakan_resep_obat_id'])->result_array();
                $jumlah = count($data_array);
                
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';  
            }if($row['tipe_resep'] == 0){
                $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/proses_manual/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-cogs"></i></a>
                <a title="'.translate('Batalkan', $this->session->userdata('language')).'" data-id="'.$row['id'].'"
            data-confirm="'.translate('Anda yakin untuk membatalkan resep ini?', $this->session->userdata('language')).'" name="delete_resep[]" class="btn btn-danger"><i class="fa fa-times"></i></a>';
           
                $tindakan_resep_detail = $this->tindakan_resep_obat_manual_m->get_by(array('tindakan_resep_obat_id' => $row['id'], 'is_active' => 1));
                $data_array = object_to_array($tindakan_resep_detail);

                $jumlah = count($data_array);
                
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($tindakan_resep_detail)).'" class="pilih-item-manual" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';
            }
            

            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['nomor_resep'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$row['nama_dokter'].'</div>',
                $info,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_box_paket($status)
    {        
        $result = $this->permintaan_box_paket_m->get_datatable($status);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {

                $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/proses_box_paket/'.$row['id'].'" class="btn btn-primary" data-toggle="modal" data-target="#popup_modal_box_paket"><i class="fa fa-check"></i></a>
                <a title="'.translate('Batalkan', $this->session->userdata('language')).'" data-id="'.$row['id'].'"
            data-confirm="'.translate('Anda yakin untuk membatalkan permintaan ini?', $this->session->userdata('language')).'" name="delete_box_paket[]" class="btn btn-danger"><i class="fa fa-times"></i></a>';

            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$row['kode_bed'].'</div>',
                '<div class="text-left">'.$row['nama_dokter'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_box_paket_history()
    {        
        $result = $this->permintaan_box_paket_m->get_datatable_history();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {


            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['no_permintaan'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$row['kode_bed'].'</div>',
                '<div class="text-left">'.$row['nama_dokter'].'</div>',
                '<div class="text-left">'.$row['kode_box_paket'].'</div>',
                '<div class="text-left">'.$row['nama_apoteker'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    } 

    public function listing_history($status)
    {        
        $result = $this->tindakan_resep_obat_m->get_datatable($status);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {

            if($row['tipe_resep'] == 1){
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
           
                $tindakan_resep_detail = $this->tindakan_resep_obat_detail_m->get_by(array('tindakan_resep_obat_id' => $row['id'], 'is_active' => 1));
                $data_array = object_to_array($tindakan_resep_detail);

                $item = $this->item_m->get_item_tindakan_resep_detail($data_array[0]['tindakan_resep_obat_id'])->result_array();
                $jumlah = count($data_array);
                
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';  
            }if($row['tipe_resep'] == 0){
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/view_manual/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
           
                $tindakan_resep_detail = $this->tindakan_resep_obat_manual_m->get_by(array('tindakan_resep_obat_id' => $row['id'], 'is_active' => 1));
                $data_array = object_to_array($tindakan_resep_detail);

                $jumlah = count($data_array);
                
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($tindakan_resep_detail)).'" class="pilih-item-manual" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';
            }
            

            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['nomor_resep'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].' ('.$row['kode_bed'].')</div>',
                '<div class="text-left">'.$row['nama_dokter'].'</div>',
                $info,
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listing_proses($status)
    {        
        $result = $this->tindakan_resep_obat_m->get_datatable($status);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {

            if($row['tipe_resep'] == 1){
                $action = '<a title="'.translate('Finish', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/finish_resep/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
           
                $tindakan_resep_detail = $this->tindakan_resep_obat_detail_m->get_by(array('tindakan_resep_obat_id' => $row['id'], 'is_active' => 1));
                $data_array = object_to_array($tindakan_resep_detail);

                $item = $this->item_m->get_item_tindakan_resep_detail($data_array[0]['tindakan_resep_obat_id'])->result_array();
                $jumlah = count($data_array);
                
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';  
            }if($row['tipe_resep'] == 0){
                $action = '<a title="'.translate('Finish', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/finish_manual/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
           
                $tindakan_resep_detail = $this->tindakan_resep_obat_manual_m->get_by(array('tindakan_resep_obat_id' => $row['id'], 'is_active' => 1));
                $data_array = object_to_array($tindakan_resep_detail);

                $jumlah = count($data_array);
                
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($tindakan_resep_detail)).'" class="pilih-item-manual" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';
            }
            

            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['nama_cabang'].'</div>',
                '<div class="text-left">'.$row['nomor_resep'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].' ('.$row['kode_bed'].')</div>',
                '<div class="text-left">'.$row['nama_dokter'].'</div>',
                $info,
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_resep_manual($status)
    {
        // $id = '1';
        $result = $this->tindakan_resep_obat_m->get_datatable(0);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {

            $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/proses_manual/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-cogs"></i></a>';
           
            $tindakan_resep_detail = $this->tindakan_resep_obat_manual_m->get_by(array('tindakan_resep_obat_id' => $row['id'], 'is_active' => 1));
            $data_array = object_to_array($tindakan_resep_detail);

            $jumlah = count($data_array);
            
            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($tindakan_resep_detail)).'" class="pilih-item" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';

            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['nama_cabang'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$row['nama_dokter'].'</div>',
                $info,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_resep_history($status)
    {
        // $id = '1';
        $result = $this->tindakan_resep_obat_manual_m->get_datatable($status);
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
        $dokter = '';
        foreach($records->result_array() as $row)
        {
            $user = $this->user_m->get($row['created_by']);
            $user_array = object_to_array($user);
            $dokter = $user_array['nama'];

            $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'apotik/resep_obat/resep_history/'.$row['id'].'/'.$row['resep_racik_obat_id'].'" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a>';

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_cabang'].'</div>',
                '<div class="text-left">'.$dokter.'</div>',
                '<div class="text-center">'.date('d F Y', strtotime($row['created_date'])).'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-center">'.$action.'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_item($cabang_id)
    {
        $result = $this->inventory_m->get_datatable_item($cabang_id);
  
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;

        $i = 0;
        foreach($records->result_array() as $row)
        {

            $satuan = $this->item_satuan_m->get_by(array('item_id' => $row['item_id']));
            $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1),true);
            $satuan = object_to_array($satuan);
            // $satuan_primary = object_to_array($satuan_primary);

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($satuan)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
             
             $output['aaData'][] = array(
                '<div class="text-center">'.$row['item_id'].'</div>',
                '<div class="text-left">'.$row['nama_gudang'].'</div>',
                '<div class="text-left">'.$row['item_kode'].'</div>',
                '<div class="text-left">'.$row['item_nama'].'</div>',
                '<div class="text-left">'.$row['satuan'].'</div>',
                '<div class="text-left">'.$row['bn'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['ed'])).'</div>',
                '<div class="text-center">'.$action.'</div>'
               );             
         $i++;
        }

       // die(dump($this->db->last_query()));


      echo json_encode($output);

    }

    public function listing_item_batal()
    {
        $result = $this->tindakan_resep_obat_detail_identitas_m->get_datatable_batal();
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i = 0;
        foreach($records->result_array() as $row)
        {   

            $item_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1), true);
            $action = '<a title="'.translate('Terima', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_verif" href="'.base_url().'apotik/resep_obat/modal_verif/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a><a title="'.translate('Tidak Terima', $this->session->userdata('language')).'" data-id="'.$row['id'].'"
            data-confirm="'.translate('Anda yakin untuk menyatakan item ini hilang dan membuat invoice untuk perawat yang membatalkan item ini?', $this->session->userdata('language')).'" name="delete[]" class="btn btn-danger"><i class="fa fa-times"></i></a>';
            
            $output['data'][] = array(
                
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][item_id]" id="item_'.$id.'_item_id" value="'.$row['item_id'].'" class="form-control">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][bn_sn_lot]" id="item_'.$id.'_bn_sn_lot" value="'.$row['bn_sn_lot'].'" class="form-control"><input type="hidden" name="item['.$i.'][jumlah]" id="item_'.$id.'_jumlah" value="'.$row['jumlah'].'" class="form-control">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][expire_date]" id="item_'.$id.'_expire_date" value="'.$row['expire_date'].'" class="form-control">'.date('d M Y', strtotime($row['expire_date'])).'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {   
        $user_id = $this->session->userdata('user_id');
        $array_input = $this->input->post();
         //die(dump($array_input));

        if ($array_input['command'] == "add") {
            
            $data_resep_obat = array(
                'nama'       => $array_input['nama'],
                'keterangan' => $array_input['keterangan'],
                'user_id'    => $user_id,
                'is_active'  => '1',
            );

            $id_resep_obat = $this->resep_racik_obat_m->save($data_resep_obat);


            foreach ($array_input['items'] as $item) 
            {
                if($item['item_id'] != "" && $item['item_kode'] != "" && $item['item_nama'] != "" && $item['satuan'] != ""  )
                {
                    $data_item = array(
                        'resep_racik_obat_id' => $id_resep_obat,
                        'item_id'             => $item['item_id'],
                        'item_satuan_id'      => $item['satuan'],
                        'jumlah'              => $item['jumlah'],

                    );
                    
                    $save_resep_obat_detail = $this->resep_racik_obat_detail_m->save($data_item); 
                }        
            }

            if ($id_resep_obat) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data resep obat berhasil ditambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
                $this->session->set_flashdata($flashdata);
            }
        }if ($array_input['command'] == "proses") {
            
            $resep_id = $array_input['tindakan_resep_obat_id'];
            $pasien_id = $array_input['pasien_id'];
            $cabang_id = $array_input['cabang_id'];

            $data_cabang = $this->cabang_m->get_by(array('id' => $cabang_id), true);
            $data_cabang = object_to_array($data_cabang);

            $data_history = array(
                'transaksi_id' => $resep_id,
                'transaksi_tipe' => 14
            );

            $inventory_history_id = $this->inventory_history_m->save($data_history);

            // $path_model = 'apotik/inventory_api_history_m';
            // $inv_api_history_api = insert_data_api($data_history,$data_cabang['url'],$path_model);
            // $inv_api_history_api = str_replace('"', '', $inv_api_history_api);

            foreach ($array_input['items'] as $key => $item) 
            {
                if($item['jumlah_kirim'] != "" && $item['jumlah_kirim'] != "0")
                {
                    if(isset($array_input['identitas_'.$item['item_id'].'_'.$key])){

                        foreach ($array_input['identitas_'.$item['item_id'].'_'.$key] as $key_id => $identitas) {
                                // die(dump($identitas));
                            if($identitas['jumlah_identitas'] != 0){
                                
                                $parameter = array(
                                    'item_id'        => $item['item_id'], 
                                    'item_satuan_id' => $identitas['item_satuan'], 
                                    'bn_sn_lot'      => $identitas['bn_sn_lot'], 
                                    'expire_date'    => date('Y-m-d', strtotime($identitas['expire_date'])), 
                                    'gudang_id'      => $identitas['gudang_id']
                                );

                                $data_inventory = $this->inventory_m->get_by($parameter);
                                $data_inventory = object_to_array($data_inventory);

                                $parameter_api = array(
                                    'item_id'              => $item['item_id'], 
                                    'item_satuan_id'       => $identitas['item_satuan'], 
                                    'bn_sn_lot'            => $identitas['bn_sn_lot'], 
                                    'expire_date'          => date('Y-m-d', strtotime($identitas['expire_date'])), 
                                    'gudang_id'            => $identitas['gudang_id'],
                                    'jumlah_identitas'     => $identitas['jumlah_identitas'],
                                    'resep_id'             => $resep_id,
                                    'transaksi_tipe'       => 14,
                                    'inventory_history_id' => $inv_api_history_api,
                                );
                                $paramdescripted = $parameter_api;

                                // $prosesinvapi = proses_inventory($paramdescripted,$data_cabang['url']);
                                // die(dump($prosesinvapi));

                                $x = 1;
                                $sisa = 0;
                                foreach ($data_inventory as $row_inv) {
                                    
                                    if($x == 1 && $identitas['jumlah_identitas'] >= $row_inv['jumlah']){

                                        $data_identitas = array(
                                            'cabang_id'                     => $cabang_id,
                                            'tindakan_resep_obat_id'        => $resep_id,
                                            'tindakan_resep_obat_detail_id' => $item['tindakan_resep_obat_detail_id'],
                                            'gudang_id'                     => $row_inv['gudang_id'],
                                            'pmb_id'                        => $row_inv['pmb_id'],
                                            'pembelian_detail_id'           => $row_inv['pembelian_detail_id'],
                                            'harga_beli'                    => $row_inv['harga_beli'],
                                            'tanggal_datang'                => date('Y-m-d', strtotime($row_inv['tanggal_datang'])),
                                            'item_id'                       => $item['item_id'],
                                            'item_satuan_id'                => $identitas['item_satuan'],
                                            'jumlah'                        => $row_inv['jumlah'],
                                            'bn_sn_lot'                     => $identitas['bn_sn_lot'],
                                            'expire_date'                   => date('Y-m-d', strtotime($identitas['expire_date'])),
                                            'status'                        => 1
                                        );

                                        $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                                        // $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                                        // $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                        // $identitas_resep_id = str_replace('"', '', $identitas_resep_id);


                                        $sisa = $identitas['jumlah_identitas'] - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));


                                    }
                                    if($x == 1 && $identitas['jumlah_identitas'] < $row_inv['jumlah']){

                                        $data_identitas = array(
                                            'cabang_id'                     => $cabang_id,
                                            'tindakan_resep_obat_id'        => $resep_id,
                                            'tindakan_resep_obat_detail_id' => $item['tindakan_resep_obat_detail_id'],
                                            'gudang_id'                     => $row_inv['gudang_id'],
                                            'pmb_id'                        => $row_inv['pmb_id'],
                                            'pembelian_detail_id'           => $row_inv['pembelian_detail_id'],
                                            'harga_beli'                    => $row_inv['harga_beli'],
                                            'tanggal_datang'                => date('Y-m-d', strtotime($row_inv['tanggal_datang'])),
                                            'item_id'                       => $item['item_id'],
                                            'item_satuan_id'                => $identitas['item_satuan'],
                                            'jumlah'                        => $identitas['jumlah_identitas'],
                                            'bn_sn_lot'                     => $identitas['bn_sn_lot'],
                                            'expire_date'                   => date('Y-m-d', strtotime($identitas['expire_date'])),
                                            'status'                        => 1
                                        );

                                        $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                                        // $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                                        // $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                        // $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                        $sisa = 0;
                                        $sisa_inv = $row_inv['jumlah'] - $identitas['jumlah_identitas'];

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($identitas['jumlah_identitas'] * (-1)),
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $identitas['jumlah_identitas'] * $row_inv['harga_beli'],
                                            'final_stock'          => $sisa_inv,
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                                        $data_identitas = array(
                                            'cabang_id'                     => $cabang_id,
                                            'tindakan_resep_obat_id'        => $resep_id,
                                            'tindakan_resep_obat_detail_id' => $item['tindakan_resep_obat_detail_id'],
                                            'gudang_id'                     => $row_inv['gudang_id'],
                                            'pmb_id'                        => $row_inv['pmb_id'],
                                            'pembelian_detail_id'           => $row_inv['pembelian_detail_id'],
                                            'harga_beli'                    => $row_inv['harga_beli'],
                                            'tanggal_datang'                => date('Y-m-d', strtotime($row_inv['tanggal_datang'])),
                                            'item_id'                       => $item['item_id'],
                                            'item_satuan_id'                => $identitas['item_satuan'],
                                            'jumlah'                        => $row_inv['jumlah'],
                                            'bn_sn_lot'                     => $identitas['bn_sn_lot'],
                                            'expire_date'                   => date('Y-m-d', strtotime($identitas['expire_date'])),
                                            'status'                        => 1
                                        );

                                        $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);
                                        // $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                                        // $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                        // $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                        $sisa = $sisa - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                                    }
                                    if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){

                                        $data_identitas = array(
                                            'cabang_id'                     => $cabang_id,
                                            'tindakan_resep_obat_id'        => $resep_id,
                                            'tindakan_resep_obat_detail_id' => $item['tindakan_resep_obat_detail_id'],
                                            'gudang_id'                     => $row_inv['gudang_id'],
                                            'pmb_id'                        => $row_inv['pmb_id'],
                                            'pembelian_detail_id'           => $row_inv['pembelian_detail_id'],
                                            'harga_beli'                    => $row_inv['harga_beli'],
                                            'tanggal_datang'                => date('Y-m-d', strtotime($row_inv['tanggal_datang'])),
                                            'item_id'                       => $item['item_id'],
                                            'item_satuan_id'                => $identitas['item_satuan'],
                                            'jumlah'                        => $sisa,
                                            'bn_sn_lot'                     => $identitas['bn_sn_lot'],
                                            'expire_date'                   => date('Y-m-d', strtotime($identitas['expire_date'])),
                                            'status'                        => 1
                                        );

                                        $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                                        // $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                                        // $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                        // $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                        $sisa_inv = $row_inv['jumlah'] - $sisa;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($sisa * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $sisa * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $sisa = 0;
                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    $x++;        
                                }
                            }
                            
                        }    
                    }else{

                        $parameter_api = array(
                            'item_id'              => $item['item_id'], 
                            'item_satuan_id'       => $identitas['item_satuan'], 
                            'gudang_id'            => $identitas['gudang_id'],
                            'jumlah_identitas'     => $identitas['jumlah_identitas'],
                            'resep_id'             => $identitas['resep_id'],
                            'transaksi_tipe'       => 14,
                            'inventory_history_id' => $inv_api_history_api,
                        );
                        $paramdescripted = serialize($parameter_api);

                        $data_inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['item_satuan_id'],'gudang_id' => $identitas['gudang_id']));
                
                        $data_inventory = object_to_array($data_inventory);

                        $x = 1;
                        $sisa = 0;
                        foreach ($data_inventory as $row_inv) {
                            
                            if($x == 1 && $item['jumlah_kirim'] >= $row_inv['jumlah']){

                                
                                $data_identitas = array(
                                    'cabang_id'                     => $cabang_id,
                                    'tindakan_resep_obat_id'        => $resep_id,
                                    'tindakan_resep_obat_detail_id' => $item['tindakan_resep_obat_detail_id'],
                                    'item_id'                       => $item['item_id'],
                                    'item_satuan_id'                => $item['item_satuan_id'],
                                    'jumlah'                        => $row_inv['jumlah'],
                                    'status'                        => 1
                                );

                                $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                                // $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                                // $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                // $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                $sisa = $item['jumlah_kirim'] - $row_inv['jumlah'];
                                $sisa_inv = 0;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $row_inv['gudang_id'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                    'final_stock'          => $sisa_inv,
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));


                            }
                            if($x == 1 && $item['jumlah_kirim'] < $row_inv['jumlah']){

                                $data_identitas = array(
                                    'cabang_id'                     => $cabang_id,
                                    'tindakan_resep_obat_id'        => $resep_id,
                                    'tindakan_resep_obat_detail_id' => $item['tindakan_resep_obat_detail_id'],
                                    'item_id'                       => $item['item_id'],
                                    'item_satuan_id'                => $item['item_satuan_id'],
                                    'jumlah'                        => $item['jumlah_kirim'],
                                    'status'                        => 1
                                );

                                $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                                // $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                                // $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                // $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                $sisa = 0;
                                $sisa_inv = $row_inv['jumlah'] - $item['jumlah_kirim'];

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $row_inv['gudang_id'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($item['jumlah_kirim'] * (-1)),
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'          => $item['jumlah_kirim'] * $row_inv['harga_beli'],
                                    'final_stock'          => $sisa_inv,
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                            }

                            if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                                $data_identitas = array(
                                    'cabang_id'                     => $cabang_id,
                                    'tindakan_resep_obat_id'        => $resep_id,
                                    'tindakan_resep_obat_detail_id' => $item['tindakan_resep_obat_detail_id'],
                                    'item_id'                       => $item['item_id'],
                                    'item_satuan_id'                => $item['item_satuan_id'],
                                    'jumlah'                        => $row_inv['jumlah'],
                                    'status'                        => 1
                                );

                                $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                                // $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                                // $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                // $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                $sisa = $sisa - $row_inv['jumlah'];
                                $sisa_inv = 0;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $row_inv['gudang_id'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                    'final_stock'          => $sisa_inv,
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                            }
                            if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){

                                $data_identitas = array(
                                    'cabang_id'                     => $cabang_id,
                                    'tindakan_resep_obat_id'        => $resep_id,
                                    'tindakan_resep_obat_detail_id' => $item['tindakan_resep_obat_detail_id'],
                                    'item_id'                       => $item['item_id'],
                                    'item_satuan_id'                => $item['item_satuan_id'],
                                    'jumlah'                        => $sisa,
                                    'status'                        => 1
                                );

                                $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                                // $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                                // $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                // $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                $sisa_inv = $row_inv['jumlah'] - $sisa;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $row_inv['gudang_id'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($sisa * (-1)),
                                    'final_stock'          => $sisa_inv,
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'          => $sisa * $row_inv['harga_beli'],
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $sisa = 0;
                                $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                            }

                            $x++;        
                        }
                    }
                }  
            }
            
            foreach ($array_input['items_pulang'] as $keys => $item_pulang) {
                if($item_pulang['jumlah_kirim'] != "" && $item_pulang['jumlah_kirim'] != "0")
                {

                    $data_jual = $this->penjualan_obat_m->get_by(array('tipe_jual' => 1, 'id_resep' => $resep_id), true);

                    if(count($data_jual) == 0){
                        $biaya_total = 0;
                        foreach ($array_input['items_pulang'] as $items_plg) {
                            if($items_plg['item_id'] != '')
                            {
                                $biaya_total = $biaya_total + intval($items_plg['item_harga'] * $items_plg['jumlah_identitas']);
                            }
                        }
                        
                        $tipe_transaksi = 1;
                        $biaya_tunai = $biaya_total;
                        $biaya_klaim = 0;

                        $draf_invoice_swasta = $this->draf_invoice_m->get_by(array('pasien_id' => $array_input['pasien_id'], 'jenis_invoice' => 1), true);
                        if(count($draf_invoice_swasta) == 0){
                            $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                            $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                            
                            $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                            $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                            $data_draft_tindakan = array(
                                'id'    => $id_draft,
                                'pasien_id'    => $array_input['pasien_id'],
                                'tipe'  => 1,
                                'cabang_id'  => $this->session->userdata('cabang_id'),
                                'tipe_pasien'  => 1,
                                'nama_pasien'  => $array_input['pasien_nama'],
                                'user_level_id'  => $this->session->userdata('level_id'),
                                'jenis_invoice' => 1,
                                'status'    => 1,
                                'is_active'    => 1,
                                'akomodasi'    => 0,
                                'created_by'    => $this->session->userdata('user_id'),
                                'created_date'    => date('Y-m-d H:i:s')
                            );

                            $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);
                        }elseif(count($draf_invoice_swasta) != 0){
                            $id_draft = $draf_invoice_swasta->id;
                        }

                        $last_id       = $this->penjualan_obat_m->get_max_id_penjualan()->result_array();
                        $last_id       = intval($last_id[0]['max_id'])+1;
                        
                        $format_id     = 'PJO-'.date('m').'-'.date('Y').'-%04d';
                        $id_jual         = sprintf($format_id, $last_id, 4);
                        
                        
                        $last_number   = $this->penjualan_obat_m->get_no_penjualan()->result_array();
                        $last_number   = intval($last_number[0]['max_no_penjualan'])+1;
                        
                        
                        $format        = '#PJO#%03d/RHS/'.romanic_number(date('m'), true).'/'.date('Y');
                        $no_jual     = sprintf($format, $last_number, 3);

                        $pasien_id = ($array_input['pasien_id'] != '')?$array_input['pasien_id']:0;
                        $data_penjualan = array(
                            'id'           => $id_jual,
                            'pasien_id'    => $array_input['pasien_id'],
                            'nama_pasien'  => $array_input['pasien_nama'],
                            'tipe_pasien'  => 1,
                            'tipe_jual'  => 1,
                            'id_resep'  => $resep_id,
                            'alamat_pasien' => $array_input['pasien_alamat'],
                            'no_penjualan' => $no_jual,
                            'invoice_id'   => $invoice_id,
                            'no_invoice'   => $no_invoice,
                            'tanggal'      => date('Y-m-d'),
                            'status'       => 1,
                            'grand_total'  => $biaya_total,
                            'is_active'    => 1,
                            'created_by'   => $this->session->userdata('user_id'),
                            'created_date' => date('Y-m-d H:i:s'),
                        );

                        $penjualan_obat = $this->penjualan_obat_m->add_data($data_penjualan);                    
                    }else{
                        $id_jual = $data_jual->id;
                        $invoice_id = $data_jual->invoice_id;
                    }

                    if(isset($array_input['identitas_pulang_'.$item_pulang['item_id'].'_'.$keys])){

                        foreach ($array_input['identitas_pulang_'.$item_pulang['item_id'].'_'.$keys] as $key_plg => $identitas_pulang) {
                                // die(dump($identitas_pulang));
                            if($identitas_pulang['jumlah_identitas'] != 0){
                                
                                $parameter = array(
                                    'item_id'        => $item_pulang['item_id'], 
                                    'item_satuan_id' => $identitas_pulang['item_satuan'], 
                                    'bn_sn_lot'      => $identitas_pulang['bn_sn_lot'], 
                                    'expire_date'    => date('Y-m-d', strtotime($identitas_pulang['expire_date'])), 
                                    'gudang_id'      => $identitas_pulang['gudang_id']
                                );

                                $data_inventory = $this->inventory_m->get_by($parameter);
                                $data_inventory = object_to_array($data_inventory);
                                $data_item = $this->item_m->get_by(array('id' => $item_pulang['item_id']), true);

                                // $parameter_api = array(
                                //     'item_id'              => $item_pulang['item_id'], 
                                //     'item_satuan_id'       => $identitas_pulang['item_satuan'], 
                                //     'bn_sn_lot'            => $identitas_pulang['bn_sn_lot'], 
                                //     'expire_date'          => date('Y-m-d', strtotime($identitas_pulang['expire_date'])), 
                                //     'gudang_id'            => $identitas_pulang['gudang_id'],
                                //     'jumlah_identitas'     => $identitas_pulang['jumlah_identitas'],
                                //     'resep_id'             => $resep_id,
                                //     'transaksi_tipe'       => 14,
                                //     'inventory_history_id' => $inv_api_history_api,
                                // );
                                // $paramdescripted = $parameter_api;

                                // $prosesinvapi = proses_inventory($paramdescripted,$data_cabang['url']);
                                // die(dump($prosesinvapi));

                                $x = 1;
                                $sisa = 0;
                                foreach ($data_inventory as $row_inv) {
                                    
                                    if($x == 1 && $identitas_pulang['jumlah_identitas'] >= $row_inv['jumlah']){

                                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                        
                                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                        $data_penjualan_detail = array(
                                            'id'                => $id_jual_detail,
                                            'penjualan_obat_id' => $id_jual,
                                            'item_id'           => $row_inv['item_id'],
                                            'item_satuan_id'    => $row_inv['item_satuan_id'],
                                            'tipe_obat'         => 2,
                                            'jumlah'            => $row_inv['jumlah'],
                                            'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                            'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_jual'        => $identitas_pulang['item_harga'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);


                                        $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                                        $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                                        $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                                        $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                                        $data_draft_tindakan_detail = array(
                                            'id'    => $id_draft_detail,
                                            'draf_invoice_id'    => $id_draft,
                                            'tipe_item' => 3,
                                            'item_id'   => $row_inv['item_id'],
                                            'jumlah'                 => $row_inv['jumlah'],
                                            'nama_tindakan' => $data_item->nama,
                                            'harga_jual'             => $identitas_pulang['item_harga'],
                                            'status' => 1,
                                            'is_active'    => 1,
                                            'created_by'    => $this->session->userdata('user_id'),
                                            'created_date'    => date('Y-m-d H:i:s')
                                        );

                                        $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);

                                        $sisa = $identitas_pulang['jumlah_identitas'] - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));


                                    }
                                    if($x == 1 && $identitas_pulang['jumlah_identitas'] < $row_inv['jumlah']){

                                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                        
                                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                        $data_penjualan_detail = array(
                                            'id'                => $id_jual_detail,
                                            'penjualan_obat_id' => $id_jual,
                                            'item_id'           => $row_inv['item_id'],
                                            'item_satuan_id'    => $row_inv['item_satuan_id'],
                                            'tipe_obat'         => 2,
                                            'jumlah'            => $identitas_pulang['jumlah_identitas'],
                                            'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                            'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_jual'        => $identitas_pulang['item_harga'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                        $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                                        $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                                        $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                                        $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                                        $data_draft_tindakan_detail = array(
                                            'id'    => $id_draft_detail,
                                            'draf_invoice_id'    => $id_draft,
                                            'tipe_item' => 3,
                                            'item_id'   => $row_inv['item_id'],
                                            'jumlah'                 => $identitas_pulang['jumlah_identitas'],
                                            'nama_tindakan' => $data_item->nama,
                                            'harga_jual'             => $identitas_pulang['item_harga'],
                                            'status' => 1,
                                            'is_active'    => 1,
                                            'created_by'    => $this->session->userdata('user_id'),
                                            'created_date'    => date('Y-m-d H:i:s')
                                        );

                                        $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);

                                        $sisa = 0;
                                        $sisa_inv = $row_inv['jumlah'] - $identitas_pulang['jumlah_identitas'];

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($identitas_pulang['jumlah_identitas'] * (-1)),
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $identitas_pulang['jumlah_identitas'] * $row_inv['harga_beli'],
                                            'final_stock'          => $sisa_inv,
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                                        
                                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                        
                                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                        $data_penjualan_detail = array(
                                            'id'                => $id_jual_detail,
                                            'penjualan_obat_id' => $id_jual,
                                            'item_id'           => $row_inv['item_id'],
                                            'item_satuan_id'    => $row_inv['item_satuan_id'],
                                            'tipe_obat'         => 2,
                                            'jumlah'            => $row_inv['jumlah'],
                                            'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                            'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_jual'        => $identitas_pulang['item_harga'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                        $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                                        $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                                        $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                                        $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                                        $data_draft_tindakan_detail = array(
                                            'id'    => $id_draft_detail,
                                            'draf_invoice_id'    => $id_draft,
                                            'tipe_item' => 3,
                                            'item_id'   => $row_inv['item_id'],
                                            'jumlah'                 => $row_inv['jumlah'],
                                            'nama_tindakan' => $data_item->nama,
                                            'harga_jual'             => $identitas_pulang['item_harga'],
                                            'status' => 1,
                                            'is_active'    => 1,
                                            'created_by'    => $this->session->userdata('user_id'),
                                            'created_date'    => date('Y-m-d H:i:s')
                                        );

                                        $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                                        $sisa = $sisa - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                                    }
                                    if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){

                                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                        
                                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                        $data_penjualan_detail = array(
                                            'id'                => $id_jual_detail,
                                            'penjualan_obat_id' => $id_jual,
                                            'item_id'           => $row_inv['item_id'],
                                            'item_satuan_id'    => $row_inv['item_satuan_id'],
                                            'tipe_obat'         => 2,
                                            'jumlah'            => $sisa,
                                            'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                            'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_jual'        => $identitas_pulang['item_harga'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                        $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                                        $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                                        $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                                        $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                                        $data_draft_tindakan_detail = array(
                                            'id'    => $id_draft_detail,
                                            'draf_invoice_id'    => $id_draft,
                                            'tipe_item' => 3,
                                            'item_id'   => $row_inv['item_id'],
                                            'jumlah'                 => $sisa,
                                            'nama_tindakan' => $data_item->nama,
                                            'harga_jual'             => $identitas_pulang['item_harga'],
                                            'status' => 1,
                                            'is_active'    => 1,
                                            'created_by'    => $this->session->userdata('user_id'),
                                            'created_date'    => date('Y-m-d H:i:s')
                                        );

                                        $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);


                                        $sisa_inv = $row_inv['jumlah'] - $sisa;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($sisa * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $sisa * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $sisa = 0;
                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    $x++;        
                                }
                            }
                            
                        }    
                    }


                }
            }       
            

            $data_resep = array(
                'status' => 2
            );

            $edit_resep = $this->tindakan_resep_obat_m->save($data_resep,$resep_id);

            if ($edit_resep) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data resep obat berhasil diproses", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

        } if ($array_input['command'] == "proses_manual") {
            
            $resep_id = $array_input['tindakan_resep_obat_id'];
            $pasien_id = $array_input['pasien_id'];
            $cabang_id = $array_input['cabang_id'];

            $data_cabang = $this->cabang_m->get_by(array('id' => $cabang_id), true);
            $data_cabang = object_to_array($data_cabang);

            $tindakan_resep = $this->tindakan_resep_obat_m->get_by(array('id' => $resep_id), true);

            $data_history = array(
                'transaksi_id' => $resep_id,
                'transaksi_tipe' => 14
            );

            $inventory_history_id = $this->inventory_history_m->save($data_history);

            $path_model = 'apotik/inventory_api_history_m';
            $inv_api_history_api = insert_data_api($data_history,$data_cabang['url'],$path_model);
            $inv_api_history_api = str_replace('"', '', $inv_api_history_api);

            foreach ($array_input['items'] as $key => $item) 
            {
                if($item['jumlah_kirim'] != '' && $item['jumlah_kirim'] != 0)
                {
                    $data_item = array(
                        'tindakan_id'                   => $tindakan_resep->tindakan_id,
                        'tipe_tindakan'                 => 1,
                        'tindakan_resep_obat_id'        => $resep_id,
                        'tindakan_resep_obat_manual_id' => $item['resep_id'],
                        'item_id'                       => $item['item_id'],
                        'tipe_item'                     => 1,
                        'jumlah'                        => $item['jumlah_kirim'],
                        'satuan_id'                     => $item['id_satuan'],
                        'dosis'                         => $item['dosis'],
                        'is_active'                     => 1
                    );

                    $save_item = $this->tindakan_resep_obat_detail_m->save($data_item);
                    
                    $path_model = 'klinik_hd/tindakan_resep_obat_detail_m';
                    $resep_detail_id = insert_data_api($data_item,$data_cabang['url'],$path_model);
                    $resep_detail_id = str_replace('"', '', $resep_detail_id);

                    $data_inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['id_satuan'], 'bn_sn_lot' => $item['bn'], 'expire_date' => date('Y-m-d', strtotime($item['ed'])), 'gudang_id' => $item['gudang_id']));
                        
                    $data_inventory = object_to_array($data_inventory);    
                    // die(dump($this->db->last_query()));

                    $parameter_api = array(
                        'item_id'              => $item['item_id'], 
                        'item_satuan_id'       => $item['id_satuan'], 
                        'bn_sn_lot'            => $item['bn'], 
                        'expire_date'          => date('Y-m-d', strtotime($item['ed'])), 
                        'gudang_id'            => $item['gudang_id'],
                        'jumlah_identitas'     => $item['jumlah_kirim'],
                        'resep_id'             => $resep_id,
                        'transaksi_tipe'       => 14,
                        'inventory_history_id' => $inv_api_history_api,
                    );
                    $paramdescripted = $parameter_api;

                    $prosesinvapi = proses_inventory($paramdescripted,$data_cabang['url']);

                    $x = 1;
                    $sisa = 0;
                    foreach ($data_inventory as $row_inv) {
                        
                        if($x == 1 && $item['jumlah_kirim'] >= $row_inv['jumlah']){

                            $data_identitas = array(
                                'tindakan_resep_obat_id'        => $resep_id,
                                'tindakan_resep_obat_detail_id' => $save_item,
                                'gudang_id'                     => $row_inv['gudang_id'],
                                'pmb_id'                        => $row_inv['pmb_id'],
                                'pembelian_detail_id'           => $row_inv['pembelian_detail_id'],
                                'harga_beli'                    => $row_inv['harga_beli'],
                                'tanggal_datang'                => date('Y-m-d', strtotime($row_inv['tanggal_datang'])),
                                'item_id'                       => $item['item_id'],
                                'item_satuan_id'                => $item['id_satuan'],
                                'jumlah'                        => $row_inv['jumlah'],
                                'bn_sn_lot'                     => $item['bn'],
                                'expire_date'                   => date('Y-m-d', strtotime($item['ed'])),
                                'status'                        => 1
                            );

                            $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                            $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                            $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                            $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                            $sisa = $item['jumlah_kirim'] - $row_inv['jumlah'];
                            $sisa_inv = 0;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                'final_stock'          => $sisa_inv,
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                'expire_date'          => $row_inv['expire_date'],
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));


                        }
                        if($x == 1 && $item['jumlah_kirim'] < $row_inv['jumlah']){

                            $data_identitas = array(
                                'tindakan_resep_obat_id'        => $resep_id,
                                'tindakan_resep_obat_detail_id' => $save_item,
                                'gudang_id'                     => $row_inv['gudang_id'],
                                'pmb_id'                        => $row_inv['pmb_id'],
                                'pembelian_detail_id'           => $row_inv['pembelian_detail_id'],
                                'harga_beli'                    => $row_inv['harga_beli'],
                                'tanggal_datang'                => date('Y-m-d', strtotime($row_inv['tanggal_datang'])),
                                'item_id'                       => $item['item_id'],
                                'item_satuan_id'                => $item['id_satuan'],
                                'jumlah'                        => $item['jumlah_kirim'],
                                'bn_sn_lot'                     => $item['bn'],
                                'expire_date'                   => date('Y-m-d', strtotime($item['ed'])),
                                'status'                        => 1
                            );

                            $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                            $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                            $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                            $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                            $sisa = 0;
                            $sisa_inv = $row_inv['jumlah'] - $item['jumlah_kirim'];

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($item['jumlah_kirim'] * (-1)),
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'          => $identitas['jumlah_identitas'] * $row_inv['harga_beli'],
                                'final_stock'          => $sisa_inv,
                                'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                'expire_date'          => $row_inv['expire_date'],
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                        }

                        if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                            $data_identitas = array(
                                'tindakan_resep_obat_id'        => $resep_id,
                                'tindakan_resep_obat_detail_id' => $save_item,
                                'gudang_id'                     => $row_inv['gudang_id'],
                                'pmb_id'                        => $row_inv['pmb_id'],
                                'pembelian_detail_id'           => $row_inv['pembelian_detail_id'],
                                'harga_beli'                    => $row_inv['harga_beli'],
                                'tanggal_datang'                => date('Y-m-d', strtotime($row_inv['tanggal_datang'])),
                                'item_id'                       => $item['item_id'],
                                'item_satuan_id'                => $item['id_satuan'],
                                'jumlah'                        => $row_inv['jumlah'],
                                'bn_sn_lot'                     => $item['bn'],
                                'expire_date'                   => date('Y-m-d', strtotime($item['ed'])),
                                'status'                        => 1
                            );

                            $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                            $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                            $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                            $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                            $sisa = $sisa - $row_inv['jumlah'];
                            $sisa_inv = 0;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                'final_stock'          => $sisa_inv,
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                'expire_date'          => $row_inv['expire_date'],
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                        }
                        if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){

                            $data_identitas = array(
                                'tindakan_resep_obat_id'        => $resep_id,
                                'tindakan_resep_obat_detail_id' => $save_item,
                                'gudang_id'                     => $row_inv['gudang_id'],
                                'pmb_id'                        => $row_inv['pmb_id'],
                                'pembelian_detail_id'           => $row_inv['pembelian_detail_id'],
                                'harga_beli'                    => $row_inv['harga_beli'],
                                'tanggal_datang'                => date('Y-m-d', strtotime($row_inv['tanggal_datang'])),
                                'item_id'                       => $item['item_id'],
                                'item_satuan_id'                => $item['id_satuan'],
                                'jumlah'                        => $sisa,
                                'bn_sn_lot'                     => $item['bn'],
                                'expire_date'                   => date('Y-m-d', strtotime($item['ed'])),
                                'status'                        => 1
                            );

                            $save_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($data_identitas);

                            $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                            $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                            $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                            $sisa_inv = $row_inv['jumlah'] - $sisa;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($sisa * (-1)),
                                'final_stock'          => $sisa_inv,
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'          => $sisa * $row_inv['harga_beli'],
                                'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                'expire_date'          => $row_inv['expire_date'],
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            $sisa = 0;
                            $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                        }

                        $x++;        
                    }
                }

            }

            $data_resep = array(
                'status' => 2
            );

            $edit_resep = $this->tindakan_resep_obat_m->save($data_resep,$resep_id);

            $path_model = 'klinik_hd/tindakan_resep_obat_m';
            $edit_resep_api = insert_data_api($data_resep,$data_cabang['url'],$path_model,$resep_id);
            $edit_resep_api = str_replace('"', '', $edit_resep_api);

            if ($edit_resep) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data resep obat berhasil diproses", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

        }
        if ($array_input['command'] == "finish") {
            $resep_id = $array_input['tindakan_resep_obat_id'];
            $pasien_id = $array_input['pasien_id'];
            $tindakan_id = $array_input['tindakan_id'];

            $cabang_id = $array_input['cabang_id'];

            $data_cabang = $this->cabang_m->get_by(array('id' => $cabang_id), true);
            $data_cabang = object_to_array($data_cabang);

            $data_pasien = $this->pasien_m->get($pasien_id);
            $data_tindakan = $this->tindakan_hd_m->get($tindakan_id);
            $items = $array_input['item'];

            foreach ($items as $key => $item) {
                
                $item_identitas = $array_input['identitas'][$key]; 

                $harga_inv = 0;
                $harga_inv_plg = 0;
                foreach ($item_identitas as $idx => $identitas) {

                    $identitas['tindakan_id'] = $tindakan_id;
                    $identitas['pasien_id']   = $pasien_id;
                    $identitas['resep_id']    = $resep_id;
                    $identitas['user_id']     = $this->session->userdata('user_id');
                    $identitas['level_id']    = $this->session->userdata('level_id');
                    $identitas['cabang_id']   = $cabang_id;
                    
                    if(isset($identitas['aksi'])){
                        if($identitas['aksi'] == 2){
                            $harga_inv = $harga_inv + $identitas['harga'];

                            $identitas['harga_inv'] = $harga_inv;

                            proses_invoice_finish($identitas, $data_cabang['url']);

                        }
                        if($identitas['aksi'] == 3){
                            proses_invoice_finish3($identitas, $data_cabang['url']);

                            $data_resep_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_by(array('id' => $identitas['id']));
                            $data_resep_identitas = object_to_array($data_resep_identitas);

                            foreach ($data_resep_identitas as $resep_identitas) {
                                $last_inv_id = $this->inventory_m->get_last_id()->result_array();
                                $last_inv_id = intval($last_inv_id[0]['inventory_id']) + 1;

                                $data_inventory = array(
                                    'inventory_id'        => $last_inv_id,
                                    'gudang_id'           => $resep_identitas['gudang_id'],
                                    'pmb_id'              => $resep_identitas['pmb_id'],
                                    'pembelian_detail_id' => $resep_identitas['pembelian_detail_id'],
                                    'box_paket_id'        => NULL,
                                    'kode_box_paket'      => NULL,
                                    'item_id'             => $resep_identitas['item_id'],
                                    'item_satuan_id'      => $resep_identitas['item_satuan_id'],
                                    'jumlah'              => $resep_identitas['jumlah'],
                                    'tanggal_datang'      => date('Y-m-d', strtotime($resep_identitas['tanggal_datang'])),
                                    'harga_beli'          => $resep_identitas['harga_beli'],
                                    'bn_sn_lot'           => $resep_identitas['bn_sn_lot'],
                                    'expire_date'         => date('Y-m-d', strtotime($resep_identitas['expire_date'])),
                                    'created_by'          => $this->session->userdata('user_id'),
                                    'created_date'        => date('Y-m-d H:i:s')
                                );

                                $inv_tujuan = $this->inventory_m->add_data($data_inventory);


                                $data_inventory_api = array(
                                    'inventory_api_id'        => $last_inv_id,
                                    'gudang_api_id'           => $resep_identitas['gudang_id'],
                                    'pmb_id'              => $resep_identitas['pmb_id'],
                                    'pembelian_detail_id' => $resep_identitas['pembelian_detail_id'],
                                    'box_paket_id'        => NULL,
                                    'kode_box_paket'      => NULL,
                                    'item_id'             => $resep_identitas['item_id'],
                                    'item_satuan_id'      => $resep_identitas['item_satuan_id'],
                                    'jumlah'              => $resep_identitas['jumlah'],
                                    'tanggal_datang'      => date('Y-m-d', strtotime($resep_identitas['tanggal_datang'])),
                                    'harga_beli'          => $resep_identitas['harga_beli'],
                                    'bn_sn_lot'           => $resep_identitas['bn_sn_lot'],
                                    'expire_date'         => date('Y-m-d', strtotime($resep_identitas['expire_date'])),
                                    'created_by'          => $this->session->userdata('user_id'),
                                    'created_date'        => date('Y-m-d H:i:s')
                                );
                                $path_model = 'apotik/inventory_api_m';
                                insert_data_api_id($data_inventory_api,$data_cabang['url'],$path_model);
                            }
                        }
                        if($identitas['aksi'] == 4){
                            $harga_inv_plg = $harga_inv_plg + $identitas['harga'];
                            $identitas['harga_inv_plg'] = $harga_inv_plg;

                            $cek_invoice = $this->invoice_m->get_by(array('pasien_id' => $pasien_id, 'penjamin_id' => 1, 'is_active' => 1, 'is_take_home' => 1, 'date(created_date)' => date('Y-m-d')), true);

                            if(count($cek_invoice) == 0){
                                $last_number_invoice  = $this->invoice_m->get_nomor_invoice()->result_array();
                                if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
                                {
                                    $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
                                }
                                else
                                {
                                    $last_number_invoice = intval(13143);
                                }

                                $format_invoice = date('Ymd').' - '.'%06d';
                                $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);
                                
                                $nama_penjamin = $data_pasien->nama;

                                $data_pembayaran_detail = array(
                                    'no_invoice'           => $no_invoice,
                                    'waktu_tindakan'       => '',
                                    'cabang_id'            => $this->session->userdata('cabang_id'),
                                    'tipe_pasien'          => 0,
                                    'pasien_id'            => $pasien_id,
                                    'nama_pasien'          => $nama_penjamin,
                                    'tipe'                 => 1,
                                    'penjamin_id'          => 1,
                                    'nama_penjamin'        => $nama_penjamin,
                                    'is_claim'             => 0,
                                    'poliklinik_id'        => 1,
                                    'nama_poliklinik'      => 'Poli HD',
                                    'status'               => 1,
                                    'shift'                => $data_tindakan->shift,
                                    'jenis_invoice'        => 1,
                                    'harga'                => $harga_inv_plg,
                                    'akomodasi'            => 0,
                                    'diskon'               => 0,
                                    'harga_setelah_diskon' => $harga_inv_plg,
                                    'sisa_bayar'           => $harga_inv_plg,
                                    'user_level_id'        => $this->session->userdata('level_id'),
                                    'is_take_home'         => 1,
                                    'is_active'            => 1
                                );
                                
                                $invoice_plg_id = $this->invoice_m->save($data_pembayaran_detail);
                            }else{
                                $invoice_plg_id = $cek_invoice->id;
                            }

                            $data_detail_item = array(
                               'invoice_id'           => $invoice_plg_id,
                               'item_id'              => $identitas['item_id'],
                               'qty'                  => $identitas['jumlah'],
                               'harga'                => $identitas['harga'],
                               'tipe'                 => 2,
                               'tipe_item'            => 2,
                               'status'               => 1,
                               'is_active'            => 1
                            );

                            $invoice_detail_plg_id = $this->invoice_detail_m->save($data_detail_item);

                        }
                    }
                }
            }

            $data_resep = array(
                'status' => 3
            );

            $edit_resep = $this->tindakan_resep_obat_m->save($data_resep,$resep_id);

            $path_model = 'klinik_hd/tindakan_resep_obat_m';
            $edit_resep_api = insert_data_api($data_resep,$data_cabang['url'],$path_model,$resep_id);
            $edit_resep_api = str_replace('"', '', $edit_resep_api);

        }
        
        redirect("apotik/resep_obat");
    }

    public function save_proses()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate("Data permintaan box paket gagal diproses", $this->session->userdata("language"));


            $array_input = $this->input->post();

            $data_t_box = $this->t_box_paket_m->get_by(array('id' => $array_input['t_box_paket_id']), true);

            $data_permintaan = array(
                'status' => 2,
                'box_paket_id' => $data_t_box->box_paket_id,
                'kode_box_paket' => $data_t_box->kode_box_paket,
                'apoteker_pengirim' => $this->session->userdata('user_id'),
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            $edit_permintaan = $this->permintaan_box_paket_m->edit_data($data_permintaan, $array_input['id']);

            $data_edit_tbox = array(
                'pasien_id' => $array_input['pasien_id'],
                'dokter_id' => $array_input['dokter_id'],
                'tindakan_id' => $array_input['tindakan_id'],
                'tanggal_tindakan' => date('Y-m-d'),
                'status' => 2,
                'tipe_tindakan' => 1,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            $edit_tbox = $this->t_box_paket_m->edit_data($data_edit_tbox, $array_input['t_box_paket_id']);
            
            $response->success = true;
            $response->msg = translate("Data permintaan box paket berhasil diproses", $this->session->userdata("language"));

            die(json_encode($response));

        }
    }


    public function delete_box_paket($id)
    {
        $data = array(
            'is_active'    => 0
        );
        // save data
        $edit_box_paket = $this->permintaan_box_paket_m->edit_data($data, $id);  

        $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Permintaan Box Paket Dihapus", $this->session->userdata("language")),
            "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
        
        redirect("apotik/resep_obat");

    }

    

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $resep_racik_obat_id = $this->tindakan_resep_obat_m->save($data, $id);

        // $update_status = $this->tindakan_resep_obat_manual_m->update_status($id);

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
            'tipe'            => 5,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);
        // die_dump($this->db->last_query());

        if ($resep_racik_obat_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Resep Telah Di Delete", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("apotik/resep_obat");
    }

    public function get_item_satuan(){

       $item_id = $this->input->post('item_id');
       //die_dump($id_negara);
       //$this->region_m->set_columns(array('id','nama'));
       $item_satuan       = $this->item_satuan_m->get_by(array('item_id' => $item_id));
       //die_dump($this->db->last_query());        
       $data_item_satuan = object_to_array($item_satuan);

       // die_dump($data_item_satuan);

       //die_dump($this->db->last_query());
       echo json_encode($data_item_satuan);
   }

    public function add_identitas($row_id,$item_id,$cabang_id)
    {
        $data_item = $this->item_m->get_by(array('id' =>  $item_id), true);
        $data_item_identitas = str_split($data_item->identitas_byte);

        $data_inventory = $this->inventory_m->get_data_inventory($item_id,$cabang_id)->result_array();

        $data_inventory = (count($data_inventory) != 0)?object_to_array($data_inventory):'';
        $index = explode('_', $row_id);

        $body = array(
            'row_id'              => $row_id,
            'item_id'             => $item_id,
            'data_item'           => object_to_array($data_item),
            'data_inventory'      => $data_inventory,
            'data_item_identitas' => $data_item_identitas,
            'index'               => $index[2]
        );

        $this->load->view('apotik/resep_obat/modal_identitas', $body);
    }

    public function add_identitas_pulang($row_id,$item_id,$cabang_id)
    {
        $data_item = $this->item_m->get_by(array('id' =>  $item_id), true);
        $data_item_identitas = str_split($data_item->identitas_byte);

        $data_inventory = $this->inventory_m->get_data_inventory($item_id,$cabang_id)->result_array();

        $data_inventory = (count($data_inventory) != 0)?object_to_array($data_inventory):'';
        $index = explode('_', $row_id);

        $body = array(
            'row_id'              => $row_id,
            'item_id'             => $item_id,
            'data_item'           => object_to_array($data_item),
            'data_inventory'      => $data_inventory,
            'data_item_identitas' => $data_item_identitas,
            'index'               => $index[2]
        );

        $this->load->view('apotik/resep_obat/modal_identitas_pulang', $body);
    }

    public function finish_resep($id)
    {
        $assets = array();
        $config = 'assets/apotik/resep_obat/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->tindakan_resep_obat_m->get($id);
        $form_data_item = $this->tindakan_resep_obat_detail_m->get_data_item($id,0)->result_array();

        $data = array(
            'title'                    => config_item('site_name').' | '.translate('Finish Resep Obat', $this->session->userdata('language')), 
            'header'                   => translate('Finish Resep Obat', $this->session->userdata('language')), 
            'header_info'              => 'RayCare', 
            'breadcrumb'               => true,
            'menus'                    => $this->menus,
            'menu_tree'                => $this->menu_tree,
            'css_files'                => $assets['css'],
            'js_files'                 => $assets['js'],
            'form_data'                => object_to_array($form_data),
            'form_data_item'           => object_to_array($form_data_item),
            'content_view'             => 'apotik/resep_obat/finish_resep',
            'pk_value'                 => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function finish_manual($id)
    {
        $assets = array();
        $config = 'assets/apotik/resep_obat/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->tindakan_resep_obat_m->get($id);
        $form_data_item = $this->tindakan_resep_obat_detail_m->get_data_item($id)->result_array();

        $data = array(
            'title'                    => config_item('site_name').' | '.translate('Finish Resep Obat', $this->session->userdata('language')), 
            'header'                   => translate('Finish Resep Obat', $this->session->userdata('language')), 
            'header_info'              => 'RayCare', 
            'breadcrumb'               => true,
            'menus'                    => $this->menus,
            'menu_tree'                => $this->menu_tree,
            'css_files'                => $assets['css'],
            'js_files'                 => $assets['js'],
            'form_data'                => object_to_array($form_data),
            'form_data_item'           => object_to_array($form_data_item),
            'content_view'             => 'apotik/resep_obat/finish_resep',
            'pk_value'                 => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function modal_verif($id)
    {

        $data = array(
            'id' => $id
        );
        $this->load->view('apotik/resep_obat/modal_terima_item',$data);
    }

    public function verifikasi()
    {
        $array_input = $this->input->post();
        // die_dump($array_input);

        $password    = $this->user_m->hash($array_input['password']);
        $username    = $array_input['username'];
        $user        = $this->user_m->get_by(array('username' => $username, 'password' => $password), true);
        

        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Data gagal disimpan', $this->session->userdata('language'));

        if ($array_input['command'] === 'add')
        {
            if(count($user)) 
            {
                $data_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_by(array('id' => $array_input['id_detail_identitas']), true);
                $data_resep = $this->tindakan_resep_obat_m->get_by(array('id' => $data_identitas->tindakan_resep_obat_id), true);
                $cabang_id = $data_resep->cabang_id;

                $data_cabang = $this->cabang_m->get_by(array('id' => $cabang_id), true);
                $data_cabang = object_to_array($data_cabang);

                $data_item_identitas = array(
                    'gived_by' => $user->id,
                    'status' => 4,
                );
            
                $update_terima_item = $this->tindakan_resep_obat_detail_identitas_m->save($data_item_identitas, $array_input['id_detail_identitas']);

                $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                $identitas_resep_id = insert_data_api($data_item_identitas,$data_cabang['url'],$path_model,$array_input['id_detail_identitas']);
                $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                $data_resep_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_by(array('id' => $array_input['id_detail_identitas']));
                $data_resep_identitas = object_to_array($data_resep_identitas);

                foreach ($data_resep_identitas as $resep_identitas) {
                    $last_inv_id = $this->inventory_m->get_last_id()->result_array();
                    $last_inv_id = intval($last_inv_id[0]['inventory_id']) + 1;

                    $data_inventory = array(
                        'inventory_id'        => $last_inv_id,
                        'gudang_id'           => $resep_identitas['gudang_id'],
                        'pmb_id'              => $resep_identitas['pmb_id'],
                        'pembelian_detail_id' => $resep_identitas['pembelian_detail_id'],
                        'box_paket_id'        => NULL,
                        'kode_box_paket'      => NULL,
                        'item_id'             => $resep_identitas['item_id'],
                        'item_satuan_id'      => $resep_identitas['item_satuan_id'],
                        'jumlah'              => $resep_identitas['jumlah'],
                        'tanggal_datang'      => date('Y-m-d', strtotime($resep_identitas['tanggal_datang'])),
                        'harga_beli'          => $resep_identitas['harga_beli'],
                        'bn_sn_lot'           => $resep_identitas['bn_sn_lot'],
                        'expire_date'         => date('Y-m-d', strtotime($resep_identitas['expire_date'])),
                        'created_by'          => $this->session->userdata('user_id'),
                        'created_date'        => date('Y-m-d H:i:s')
                    );

                    $inv_tujuan = $this->inventory_m->add_data($data_inventory);


                    $data_inventory_api = array(
                        'inventory_api_id'        => $last_inv_id,
                        'gudang_api_id'           => $resep_identitas['gudang_id'],
                        'pmb_id'              => $resep_identitas['pmb_id'],
                        'pembelian_detail_id' => $resep_identitas['pembelian_detail_id'],
                        'box_paket_id'        => NULL,
                        'kode_box_paket'      => NULL,
                        'item_id'             => $resep_identitas['item_id'],
                        'item_satuan_id'      => $resep_identitas['item_satuan_id'],
                        'jumlah'              => $resep_identitas['jumlah'],
                        'tanggal_datang'      => date('Y-m-d', strtotime($resep_identitas['tanggal_datang'])),
                        'harga_beli'          => $resep_identitas['harga_beli'],
                        'bn_sn_lot'           => $resep_identitas['bn_sn_lot'],
                        'expire_date'         => date('Y-m-d', strtotime($resep_identitas['expire_date'])),
                        'created_by'          => $this->session->userdata('user_id'),
                        'created_date'        => date('Y-m-d H:i:s')
                    );
                    $path_model = 'apotik/inventory_api_m';
                    insert_data_api_id($data_inventory_api,$data_cabang['url'],$path_model);
                }
                
                if ($update_terima_item) 
                {
                    $response->success = true;
                    $response->msg = translate('Item berhasil dikembalikan', $this->session->userdata('language'));
                }
            } 

            else
            {
                $response->success = false;
                $response->msg = translate('Username dan Password tidak valid', $this->session->userdata('language'));
            }

            die(json_encode($response));

        }
    }

    public function delete_item_batal($id)
    {
        $data_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_by(array('id' => $id), true);
        $data_resep = $this->tindakan_resep_obat_m->get_by(array('id' => $data_identitas->tindakan_resep_obat_id), true);
        $cabang_id = $data_resep->cabang_id;

        $data_cabang = $this->cabang_m->get_by(array('id' => $cabang_id), true);
        $data_cabang = object_to_array($data_cabang);

        $data_item_identitas = array(
            'status' => 5,
            'is_active' => 0,
        );
    
        $update_terima_item = $this->tindakan_resep_obat_detail_identitas_m->save($data_item_identitas, $id);

        $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
        $identitas_resep_id = insert_data_api($data_item_identitas,$data_cabang['url'],$path_model,$id);
        $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

        $user_canceled = $this->user_m->get($data_identitas->canceled_by);

        $last_number_invoice  = $this->invoice_m->get_nomor_invoice()->result_array();
        if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
        {
            $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
        }
        else
        {
            $last_number_invoice = intval(13143);
        }

        $format_invoice = date('Ymd').' - '.'%06d';
        $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);
        
        $nama_penjamin = $user_canceled->nama;

        $item_harga = $this->item_harga_m->get_harga_item_satuan($data_identitas->item_id,$data_identitas->item_satuan_id, $cabang_id)->row(0);

        $data_pembayaran_detail = array(
            'no_invoice'           => $no_invoice,
            'waktu_tindakan'       => '',
            'cabang_id'            => $this->session->userdata('cabang_id'),
            'tipe_pasien'          => 2,
            'pasien_id'            => 0,
            'nama_pasien'          => $nama_penjamin,
            'tipe'                 => 1,
            'penjamin_id'          => 1,
            'nama_penjamin'        => $nama_penjamin,
            'is_claim'             => 0,
            'poliklinik_id'        => 1,
            'nama_poliklinik'      => 'Poli HD',
            'status'               => 1,
            'shift'                => 1,
            'jenis_invoice'        => 1,
            'harga'                => $harga_item->harga,
            'akomodasi'            => 0,
            'diskon'               => 0,
            'harga_setelah_diskon' => $harga_item->harga,
            'sisa_bayar'           => $harga_item->harga,
            'user_level_id'        => $this->session->userdata('level_id'),
            'is_take_home'         => 1,
            'is_active'            => 1
        );
        
        $invoice_plg_id = $this->invoice_m->save($data_pembayaran_detail);
                            

        $data_detail_item = array(
           'invoice_id'           => $invoice_plg_id,
           'item_id'              => $data_identitas->item_id,
           'satuan_id'              => $data_identitas->item_satuan_id,
           'qty'                  => $data_identitas->jumlah,
           'harga'                => $harga_item->harga,
           'tipe'                 => 2,
           'tipe_item'            => 2,
           'status'               => 1,
           'is_active'            => 1
        );

        $invoice_detail_plg_id = $this->invoice_detail_m->save($data_detail_item);


        if ($update_terima_item) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Item dinyatakan hilang", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("apotik/resep_obat");
    }
}

/* End of file resep_obat.php */
/* Location: ./application/controllers/resep_obat/resep_obat.php */