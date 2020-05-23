<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Titip_terima_uang extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 6;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(15, 6);       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('reservasi/titip_terima_uang/kasir_titip_uang_m');
        $this->load->model('reservasi/titip_terima_uang/kasir_terima_uang_m');
        $this->load->model('reservasi/titip_terima_uang/kasir_biaya_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('master/pasien_dokumen_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/region_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/penyakit_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/reservasi/titip_terima_uang/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reservasi Titip & Terima Uang', $this->session->userdata('language')), 
            'header'         => translate('Reservasi Titip & Terima Uang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/titip_terima_uang/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/reservasi/rujukan/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reservasi History Rujukan', $this->session->userdata('language')), 
            'header'         => translate('Reservasi History Rujukan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/rujukan/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/reservasi/titip_terima_uang/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). translate("Tambah Titip Uang", $this->session->userdata("language")), 
            'header'         => translate("Tambah Titip Uang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/titip_terima_uang/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add_terima_uang()
    {
        $assets = array();
        $assets_config = 'assets/reservasi/titip_terima_uang/add_terima_uang';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). translate("Tambah Terima Uang", $this->session->userdata("language")), 
            'header'         => translate("Tambah Terima Uang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/titip_terima_uang/add_terima_uang',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/reservasi/rujukan/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->rujukan_m->get($id);
        $data_pasien = $this->pasien_m->get_by(array('id' => $form_data->pasien_id));
        $data_poliklinik = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_tujuan_id));

        $data = array(
            'title'           => config_item('site_name'). translate("Edit Pasien", $this->session->userdata("language")), 
            'header'          => translate("Edit Pasien", $this->session->userdata("language")), 
            'header_info'     => config_item('site_name'), 
            'breadcrumb'      => TRUE,
            'menus'           => $this->menus,
            'menu_tree'       => $this->menu_tree,
            'css_files'       => $assets['css'],
            'js_files'        => $assets['js'],
            'content_view'    => 'reservasi/rujukan/edit',
            'form_data'       => object_to_array($form_data),
            'data_pasien'     => object_to_array($data_pasien),
            'data_poliklinik' => object_to_array($data_poliklinik),
            'pk_value'        => $id,
            'flag'            => 'edit'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/reservasi/rujukan/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data            = $this->rujukan_m->get($id);
        $data_pasien          = $this->pasien_m->get_by(array('id' => $form_data->pasien_id));
        $data_poliklinik      = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_tujuan_id));
        $data_poliklinik_asal = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_asal_id));
        // die(dump($this->db->last_query()));
        // die(dump($data_poliklinik));

        $data = array(
            'title'                => config_item('site_name'). translate("View Rujukan", $this->session->userdata("language")), 
            'header'               => translate("View Rujukan", $this->session->userdata("language")), 
            'header_info'          => config_item('site_name'), 
            'breadcrumb'           => TRUE,
            'menus'                => $this->menus,
            'menu_tree'            => $this->menu_tree,
            'css_files'            => $assets['css'],
            'js_files'             => $assets['js'],
            'content_view'         => 'reservasi/rujukan/view',
            'form_data'            => object_to_array($form_data),
            'data_pasien'          => object_to_array($data_pasien),
            'data_poliklinik'      => object_to_array($data_poliklinik),
            'data_poliklinik_asal' => object_to_array($data_poliklinik_asal),
            'pk_value'             => $id,
            'flag'                 => 'view'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

     public function view_history_rujukan($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/reservasi/rujukan/view_history_rujukan';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->rujukan_m->get($id);
        $data_pasien = $this->pasien_m->get_by(array('id' => $form_data->pasien_id));
        $data_poliklinik = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_tujuan_id));
        $data_poliklinik_asal = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_asal_id));
        // die(dump($this->db->last_query()));
        // die(dump($data_poliklinik));

        $data = array(
            'title'                => config_item('site_name'). translate("View History Rujukan", $this->session->userdata("language")), 
            'header'               => translate("View History Rujukan", $this->session->userdata("language")), 
            'header_info'          => config_item('site_name'), 
            'breadcrumb'           => TRUE,
            'menus'                => $this->menus,
            'menu_tree'            => $this->menu_tree,
            'css_files'            => $assets['css'],
            'js_files'             => $assets['js'],
            'content_view'         => 'reservasi/rujukan/view_history_rujukan',
            'form_data'            => object_to_array($form_data),
            'data_pasien'          => object_to_array($data_pasien),
            'data_poliklinik'      => object_to_array($data_poliklinik),
            'data_poliklinik_asal' => object_to_array($data_poliklinik_asal),
            'pk_value'             => $id,
            'flag'                 => 'view'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    
    public function listing_titip_uang($filter_date){

        $replace = str_replace('%20', ' ', $filter_date);
        $bulan = date('Y-m', strtotime($replace));
        $date = date('Y-m', strtotime($replace));


        $result = $this->kasir_titip_uang_m->get_datatable($date);
        // die_dump($this->db->last_query());  
        // die_dump($result);

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        $rupiah = 0;
        $rupiah_terima = 0;
        $nama = '';
        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd');
            $bulan = explode('-', $row['tanggal']);

            $rp = $row['rupiah'] - $row['rupiah_terima'];

            if ($row['tipe_user'] == 1) 
            {
                $nama = $row['nama_user'];
            
            } elseif ($row['tipe_user'] == 2) 

            {

                $nama = $row['nama_user_gudang'];
            }

            $output['data'][] = array(
                
                '<div class="text-center">'.$effective_date.'</div>',
                '<div class="text-left">'.$row['nama_user_created'].'</div>',
                '<div class="text-left">'.$nama.'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-right">Rp. '.number_format($row['rupiah_sisa'], 0,'','.').',-</div>',
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function listing_terima_uang($filter_date){

        $replace = str_replace('%20', ' ', $filter_date);
        $bulan = date('Y-m', strtotime($replace));
        $date = date('Y-m', strtotime($replace));


        $result = $this->kasir_terima_uang_m->get_datatable($date);
        // die_dump($this->db->last_query());
        // die_dump($result);  

        // Output
         $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $nama = '';
        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd');
            $bulan = explode('-', $row['tanggal']);

            if ($row['tipe_user'] == 1) 
            {
                $nama = $row['nama_user'];
            
            } elseif ($row['tipe_user'] == 2) 

            {

                $nama = $row['nama_user_gudang'];
            }

            $output['data'][] = array(
                
                '<div class="text-center">'.$effective_date.'</div>',
                '<div class="text-left">'.$nama.'</div>',
                '<div class="text-left">'.$row['nama_user_created'].'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-right">Rp. '.number_format($row['rupiah'], 0,'','.').',-</div>',
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function listing_history(){

        $result = $this->kasir_titip_uang_m->get_datatable_history();
        // die_dump($this->db->last_query());
        // die_dump($result);

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        $rupiah = 0;
        $rupiah_terima = 0;
        $nama = '';
        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd');
            $bulan = explode('-', $row['tanggal']);

            $rp = $row['rupiah'] - $row['rupiah_terima'];

            if ($row['tipe_user'] == 1) 
            {
                $nama = $row['nama_user'];
            
            } elseif ($row['tipe_user'] == 2) 

            {

                $nama = $row['nama_user_gudang'];
            }


            $output['data'][] = array(
                
                '<div class="text-center">'.$effective_date.'</div>',
                '<div class="text-left">'.$row['nama_user_created'].'</div>',
                '<div class="text-left">'.$nama.'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-right">Rp. '.number_format($row['rupiah_terima'], 0,'','.').',-</div>',
            );

            $i++;
        }

        echo json_encode($output);
    }


    public function listing_pilih_user()
    {

        $cabang_id = $this->session->userdata('cabang_id');
                
        $result = $this->user_m->get_datatable_pilih_user($cabang_id);
        //die_dump($this->db->last_query());
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
            
            $action = '';
            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-tab="1" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['nama_user_level'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_gudang_orang()
    {

        $cabang_id = $this->session->userdata('cabang_id');
                
        $result = $this->user_m->get_datatable_pilih_gudang_orang($cabang_id);
        //die_dump($this->db->last_query());
        // die(dump($result));
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
            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-tab="2" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary select-gudang-orang"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_gudang_orang'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_user_terima_uang()
    {

        // $cabang_id = $this->session->userdata('cabang_id');
        // die_dump($this->db->last_query());
                // die_dump($cabang_id);
        $result = $this->kasir_titip_uang_m->get_datatable_pilih_user();
        // die_dump($this->db->last_query());
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
            
            $action = '';
            if($row['is_active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['kasir_titip_uang_id'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center">'.$row['nama_user_level'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_gudang_orang_terima_uang()
    {

        $result = $this->kasir_titip_uang_m->get_datatable_pilih_gudang_orang();
        // die_dump($this->db->last_query());
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
            
            $action = '';
            if($row['is_active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary select-gudang-orang"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['user_id'].'</div>',
                '<div class="text-left">'.$row['nama_gudang_orang'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();
        // die_dump($array_input);
        // die_dump($tanggal_lahir);
        if ($array_input['command'] === 'add')
        {  
            foreach ($array_input['phone'] as $titip_uang) 
            {

                if($array_input['id_ref_pasien'] != "")
                {
                    $data_kasir_titip_uang = array(
                        'tanggal'               => date("Y-m-d", strtotime($array_input['tanggal'])),
                        'user_id'               => $array_input['id_ref_pasien'],
                        'tipe_user'             => $array_input['tipe_user'],
                        'rupiah'                => $titip_uang['rupiah'],
                        'rupiah_terima'         => 0,
                        'rupiah_sisa'           => $titip_uang['rupiah'],
                        'subjek'                => $titip_uang['subjek'],
                        'keterangan'            => $titip_uang['keterangan'],
                        'is_lunas'              => 0,
                    );
                    
                    $save_kasir_titip_uang = $this->kasir_titip_uang_m->save($data_kasir_titip_uang);
                    // die_dump($this->db->last_query());
                    
                }
                    // die_dump($this->db->last_query());
                    //die_dump($save_rujukan);
            }            

            if ($save_kasir_titip_uang) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Titip Uang berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }

        redirect("reservasi/titip_terima_uang");

    }

    public function save_terima_uang()
    {
        $array_input = $this->input->post();
        $id          = $array_input['kasir_titip_uang_id'];
        $user_id     = $array_input['id_ref_pasien'];
        $tipe_user   = $array_input['tipe_user'];
        // die_dump($array_input);   
        // die_dump($tanggal_lahir);
        
        if ($array_input['command'] === 'add')
        {  

            if($array_input['id_ref_pasien'] != "")
            {
                $data_kasir_terima_uang = array(
                    'tanggal'               => date("Y-m-d", strtotime($array_input['tanggal'])),
                    'user_id'               => $array_input['id_ref_pasien'],
                    'tipe_user'             => $array_input['tipe_user'],
                    'rupiah'                => $array_input['rupiah'],
                    'subjek'                => $array_input['subjek'],
                    'keterangan'            => $array_input['keterangan'],
                );

                $save_kasir_terima_uang = $this->kasir_terima_uang_m->save($data_kasir_terima_uang);

            }

            foreach ($array_input['account'] as $biaya) 
            {

                if($biaya['keterangan'] != "")

                {
                    $data_biaya = array(

                        'kasir_terima_uang_id'  => $save_kasir_terima_uang,
                        'tanggal'               => date("Y-m-d", strtotime($biaya['tanggal_kasir_biaya'])),
                        'rupiah'                => $biaya['rupiah'],
                        'keterangan'            => $biaya['keterangan'],
                        'is_done'               => 0,
                        );

                    $save_kasir_biaya = $this->kasir_biaya_m->save($data_biaya);
                    // die_dump($this->db->last_query());
                    //die_dump($save_rujukan);
                }
            }   

            $data_sisa = $this->kasir_titip_uang_m->get_sisa($user_id, $tipe_user)->result();
            $data_titip_uang = object_to_array($data_sisa);
            // die_dump($this->db->last_query());
            // die_dump($data_titip_uang);
            $sisa = 0;
            $x = 0;

            foreach ($data_titip_uang as $row) 
            {

                $x++;
                
                if($x == 1)
                {
                    if($array_input['rupiah'] >= $row['rupiah_sisa'])
                    {

                        $sisa = $array_input['rupiah'] - $row['rupiah_sisa'];
                        // die_dump($sisa);
                        
                        $data = array(
                        
                        'rupiah_terima' => $row['rupiah_sisa'],
                        'rupiah_sisa'   => 0,
                        'is_lunas'      => 1,

                        );

                        $update = $this->kasir_titip_uang_m->save($data, $row['id']);
                        // die(dump($update));
                        // die_dump($this->db->last_query());
                    
                    } elseif ($array_input['rupiah'] < $row['rupiah_sisa']) 

                    {


                        $data = array(

                            'rupiah_terima' => $array_input['rupiah'],
                            'rupiah_sisa'   => $row['rupiah_sisa'] - ($array_input['rupiah']),
                            'is_lunas'      => 0,

                            );

                        $update = $this->kasir_titip_uang_m->save($data, $row['id']);
                        $sisa = 0;
                        // die_dump($this->db->last_query());

                    }
                    
                } else 

                {
                    if ($sisa != 0)
                    {
                        if($sisa >= $row['rupiah_sisa'])
                        {
                            $sisa = $sisa - $row['rupiah_sisa'];
                            // die_dump($sisa);
                            $data = array(

                                'rupiah_terima' => $row['rupiah_sisa'],
                                'rupiah_sisa'   => 0,
                                'is_lunas'      => 1,

                                );

                            $update = $this->kasir_titip_uang_m->save($data, $row['id']);
                            // die_dump($this->db->last_query());

                        } elseif ($sisa < $row['rupiah_sisa']) 

                        {

                            $data = array(

                                'rupiah_terima' => $sisa,
                                'rupiah_sisa'   => $row['rupiah_sisa'] - $sisa,
                                'is_lunas'      => 0,

                                );

                            $update = $this->kasir_titip_uang_m->save($data, $row['id']);
                            $sisa = 0;
                            // die_dump($this->db->last_query());
                        }
                    }
                }    
            }

            if ($save_kasir_terima_uang && $save_kasir_biaya && $update) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Terima Uang berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }

        redirect("reservasi/titip_terima_uang");
        
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $rujukan_id = $this->rujukan_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        // die_dump($max_id);
        
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 8,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->save($data_trash);
        // die_dump($this->db->last_query());
        
        if ($trash || $rujukan_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("reservasi/rujukan");
    }

    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->cabang_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Branch Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("reservasi/rujukan");
    }

    public function get_negara(){

        //$id_negara = $this->input->post('id_negara');
        //die_dump($id_negara);
        //$this->region_m->set_columns(array('id','nama'));
        $negara = $this->region_m->get_by(array('parent' => Null));
        //die_dump($this->db->last_query());        
        $hasil_negara = object_to_array($negara);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_negara);
    }

    public function get_provinsi(){

        $id_negara = $this->input->post('id_negara');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $provinsi = $this->region_m->get_data_region($id_negara)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_provinsi = object_to_array($provinsi);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_provinsi);
    }

    public function get_kota(){

        $id_provinsi = $this->input->post('id_provinsi');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kota = $this->region_m->get_data_region($id_provinsi)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kota = object_to_array($kota);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kota);
    }

    public function get_kecamatan(){

        $id_kota = $this->input->post('id_kota');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kecamatan = $this->region_m->get_data_region($id_kota)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kecamatan = object_to_array($kecamatan);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kecamatan);
    }

    public function get_kelurahan(){

        $id_kecamatan = $this->input->post('id_kecamatan');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kelurahan = $this->region_m->get_data_region($id_kecamatan)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kelurahan = object_to_array($kelurahan);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kelurahan);
    }

    public function get_kode_cabang(){

        $id_negara = $this->input->post('id_cabang');
        //die_dump($id_negara);
        $this->cabang_m->set_columns(array('id','kode'));
        $cabang = $this->cabang_m->get_by(array('id' => $id_negara));
        //die_dump($this->db->last_query());        
        $hasil_cabang = object_to_array($cabang);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_cabang);
    }

    public function get_subjek(){

        $tipe = $this->input->post('tipe');
        $data_subjek = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek = object_to_array($data_subjek);

        echo json_encode($hasil_data_subjek);

    }

    public function save_subjek(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_subjek = array(
            "tipe"     => $tipe,
            "nama"   => $nama,
        );

        
        $save_data = $this->subjek_m->save($data_subjek);

        $data_subjek = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek = object_to_array($data_subjek);

        echo json_encode($hasil_data_subjek);

    }

    public function save_negara(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_negara = array(
            "tipe"     => $tipe,
            "nama"   => $nama,
        );

        $save_data = $this->region_m->save($data_negara);

        $data_negara = $this->region_m->get_by(array('parent' => null));
        $hasil_data_negara = object_to_array($data_negara);

        echo json_encode($hasil_data_negara);

    }

    public function save_region(){

        $parent = $this->input->post('parent');
        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_region = array(
            "parent"     => $parent,
            "tipe"     => $tipe,
            "nama"   => $nama,
        );

        
        $save_data = $this->region_m->save($data_region);

        $data_region = $this->region_m->get_by(array('parent' => $parent));
        $hasil_data_region = object_to_array($data_region);

        echo json_encode($hasil_data_region);

    }

    public function get_sisa($user_id, $tipe)
    {
        
        $data_rupiah_sisa = $this->kasir_titip_uang_m->get_sisa($user_id, $tipe);
        die_dump($data_rupiah_sisa);
    }

    function cetak_rujukan($id) 
    {


        // $data_bayar    = $this->pembayaran_pasien_m->get($id);
        // $data_bayar    = object_to_array($data_bayar);

        $data_rujukan  = $this->rujukan_m->get($id);
        $data_rujukan  = object_to_array($data_rujukan);

        // die(dump($data_rujukan));

        $data_pasien   = $this->rujukan_m->get_by(array('id' => $id, 'is_active' => 1));
        $data_pasien   = object_to_array($data_pasien);
        $nama_pasien   = $this->pasien_m->get($data_pasien[0]['pasien_id']);
        $nama_pasien   = object_to_array($nama_pasien);

        // die(dump($nama_pasien));    
        
        $nama_poli_asal = $this->poliklinik_m->get($data_pasien[0]['poliklinik_asal_id']);
        $nama_poli_asal = object_to_array($nama_poli_asal);
        // die(dump($nama_poli_asal));    

        $nama_poli_tujuan = $this->poliklinik_m->get($data_pasien[0]['poliklinik_tujuan_id']);
        $nama_poli_tujuan = object_to_array($nama_poli_tujuan);
        // die(dump($nama_poli_tujuan));    

        // $data_tindakan = $this->pembayaran_tindakan_pasien_m->get_by(array('pembayaran_pasien_id' => $id, 'is_active' => 1));
        // $data_tindakan = object_to_array($data_tindakan);
        
        // $data_obat     = $this->pembayaran_obat_pasien_m->get_by(array('pembayaran_pasien_id' => $id, 'is_active' => 1));
        // $data_obat     = object_to_array($data_obat);

        // $result_tindakan = array();
        // $result_obat = array();
        // $result_pasien =array();

        // foreach ($data_tindakan as $tindakan) 
        // {
        //     $result_tindakan[] = $this->pembayaran_tindakan_pasien_m->get_by_trans_tipe($tindakan['pembayaran_pasien_id'], $tindakan['tipe'])->result_array();
        // }

        // foreach ($data_obat as $obat) 
        // {
        //     $result_obat[] = $this->pembayaran_obat_pasien_m->get_by_data_obat($obat['pembayaran_pasien_id'])->result_array();
        // }

        // $result_obat = $this->pembayaran_obat_pasien_m->get_by_data_obat->result_array();


        // die(dump($this->db->last_query()));
        // die(dump($result_obat));    

        ///////////////////////////////////

        $data_header = array(

            "id_rujukan"    => $data_rujukan['id'],
            "pasien_id"     => $data_rujukan['pasien_id'],
            // "nama_pasien"   => $data_bayar['GenderPasien'].". ".$transaksi['NamaPasien'],
            // "no_pasien"     => $data_bayar['NomorMemberPasien'],
            // "nama_jaminan"  => $nama_jaminan,
            // "start"         => $start,
            // "end"           => $end 

        );


        //default setting

        $this->raycare_rujukan_fpdf->RAYCARE_RUJUKAN_FPDF("P", "mm", array(95,230));
        $this->raycare_rujukan_fpdf->Header(base64_encode(serialize($data_header)));
        $this->raycare_rujukan_fpdf->Open();
        $this->raycare_rujukan_fpdf->SetTitle("TREATMENT");
        $this->raycare_rujukan_fpdf->SetAuthor("Author Hardcode");
        $this->raycare_rujukan_fpdf->AliasNbPages();
        $this->raycare_rujukan_fpdf->AddPage();
        $this->raycare_rujukan_fpdf->SetAutoPageBreak(TRUE, 10);
        $this->raycare_rujukan_fpdf->SetSubject("Rujukan");

        //baris pertama

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->SetXY(5,35);
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $this->raycare_rujukan_fpdf->Cell(16, 4, "Nomor Pasien", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->MultiCell(35, 4, $nama_pasien['no_member'],0);

        $this->raycare_rujukan_fpdf->SetXY(50,35);
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $this->raycare_rujukan_fpdf->Cell(16, 4, "Poliklinik Asal", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->MultiCell(30, 4, $nama_poli_asal['nama'], 0,"L");

        $this->raycare_rujukan_fpdf->Ln(6);

        //baris kedua

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();

        $this->raycare_rujukan_fpdf->SetXY(5,40);
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $this->raycare_rujukan_fpdf->Cell(16, 4, "Nama Pasien", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->MultiCell(30, 4, $nama_pasien['nama'], 0,"L");

        $this->raycare_rujukan_fpdf->SetXY(50,40);
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $this->raycare_rujukan_fpdf->Cell(16, 4, "Poliklinik Tujuan", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->MultiCell(30, 4, $nama_poli_tujuan['nama'], 0,"L");

        $this->raycare_rujukan_fpdf->Ln(6);

        //baris ketiga

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->SetXY(5,45);
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $this->raycare_rujukan_fpdf->Cell(16, 4, "Tanggal Di Rujuk", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->MultiCell(27, 4, date('d F Y', strtotime($data_rujukan['tanggal_dirujuk'])),0);
        $this->raycare_rujukan_fpdf->Ln(6);

        
        //baris keEmpat

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        
        $this->raycare_rujukan_fpdf->SetXY(5,50);
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $this->raycare_rujukan_fpdf->Cell(16, 4, "Tanggal Rujukan", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->MultiCell(27, 4, date('d F Y', strtotime($data_rujukan['tanggal_rujukan'])),0);
        $this->raycare_rujukan_fpdf->Ln(6);


        //baris keLima


        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        
        $this->raycare_rujukan_fpdf->SetXY(5,55);
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $this->raycare_rujukan_fpdf->Cell(16, 4, "Perihal", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->MultiCell(70, 4, $data_rujukan['subjek'],0);
        $this->raycare_rujukan_fpdf->Ln(6);

        //////////////////Line//////////////////////////////////
        // $posisi_y = $this->raycare_rujukan_fpdf->GetY();
        // $this->raycare_rujukan_fpdf->Line(0, $posisi_y-2, 95, $posisi_y-2);
        // $this->raycare_rujukan_fpdf->Line(0, $posisi_y-1, 95, $posisi_y-1);

        ////////////////////////end Line///////////////////////////////
        


        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        
        $posisi_y = $this->raycare_rujukan_fpdf->GetY();
        $posisi_x = $this->raycare_rujukan_fpdf->GetX();

        $this->raycare_rujukan_fpdf->SetXY(5, 65);
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $this->raycare_rujukan_fpdf->Cell(16, 4, "Keterangan", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Ln(2);

        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->SetXY(5, 70);
            $this->raycare_rujukan_fpdf->MultiCell(85, 4, $data_rujukan['keterangan'], 0,"J");
        $this->raycare_rujukan_fpdf->Ln(6);

        $posisiY_garis = $this->raycare_rujukan_fpdf->GetY();
        $posisiX_garis = $this->raycare_rujukan_fpdf->GetX();

        // $this->raycare_rujukan_fpdf->Line(0, $posisiY_garis, 95, $posisiY_garis);
        // $this->raycare_rujukan_fpdf->Line(0, $posisiY_garis+1, 95, $posisiY_garis+1); 
        // $this->raycare_rujukan_fpdf->Ln();

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $posisi_x = $this->raycare_rujukan_fpdf->GetX();
        $posisi_y = $this->raycare_rujukan_fpdf->GetY();
        $lineY_begin = $this->raycare_rujukan_fpdf->GetY();
        $this->raycare_rujukan_fpdf->SetFont("Arial", "", 5);
        $posisi_y = $this->raycare_rujukan_fpdf->GetY();
        $posisi_x = $this->raycare_rujukan_fpdf->GetX();
        // $this->raycare_rujukan_fpdf->SetXY(50,82);
        $this->raycare_rujukan_fpdf->Cell(10, 4, "", '', 0, 'L');
        // $this->raycare_rujukan_fpdf->SetXY(50,82);
        $this->raycare_rujukan_fpdf->SetX(70);
        $this->raycare_rujukan_fpdf->Cell(50, 4, "Jakarta, ".date("d M Y"), 0, "J");
        $this->raycare_rujukan_fpdf->Ln(3);

        // $this->raycare_rujukan_fpdf->SetXY(50,35);

        $posisi_y = $this->raycare_rujukan_fpdf->GetY();
        $posisi_x = $this->raycare_rujukan_fpdf->GetX();
        // $this->raycare_rujukan_fpdf->SetXY(50,85);
        $this->raycare_rujukan_fpdf->SetX(57);
        $this->raycare_rujukan_fpdf->Cell(20, 4, "", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(35, 4, "Dokter", '', 0, 'L');
        // $this->raycare_rujukan_fpdf->Cell(30, 4, "Pasien", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Ln(15);

        $user_id = $this->session->userdata("user_id");
        $user_login = $this->user_m->get_by(array('id' => $user_id), true);
        // die_dump($this->db->last_query());
        // die_dump($user_login);
        // 
        $posisi_y = $this->raycare_rujukan_fpdf->GetY();
        // $this->raycare_rujukan_fpdf->SetXY(50,100);
        $this->raycare_rujukan_fpdf->SetX(57);
        $this->raycare_rujukan_fpdf->Cell(20, 4, "", '', 0, 'L');
        $this->raycare_rujukan_fpdf->Cell(35, 4, $user_login->nama, '', 0, 'L');
        // $this->raycare_rujukan_fpdf->Cell(30, 4, $nama_pasien['nama'], '', 0, 'L');
        $this->raycare_rujukan_fpdf->Ln();
        

        //Save PDF

        $this->raycare_rujukan_fpdf->Output($data_rujukan['id'].".pdf", "I");

        $flashdata = array(

            "information"   => "Berhasil membuat invoice"

        );

        $this->session->set_flashdata($flashdata);
    }


}

/* End of file titip_terima_uang.php */
/* Location: ./application/controllers/reservasi/titip_terima_uang.php */