<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Titip_terima_uang extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '1dc9273f5f033d83ec4edc4119b8e12e';                  // untuk check bit_access

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

        $this->load->model('kasir/titip_terima_uang/kasir_titip_uang_m');
        $this->load->model('kasir/titip_terima_uang/kasir_terima_uang_m');
        $this->load->model('kasir/titip_terima_uang/kasir_biaya_m');
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
        $config = 'assets/kasir/titip_terima_uang/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Reservasi Titip & Terima Uang', $this->session->userdata('language')), 
            'header'         => translate('Reservasi Titip & Terima Uang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'kasir/titip_terima_uang/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/kasir/titip_terima_uang/add';
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
            'content_view'   => 'kasir/titip_terima_uang/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add_terima_uang()
    {
        $assets = array();
        $assets_config = 'assets/kasir/titip_terima_uang/add_terima_uang';
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
            'content_view'   => 'kasir/titip_terima_uang/add_terima_uang',
            'flag'           => 'add',
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
        // die_dump($records);
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

        if ($array_input['command'] === 'add')
        {  
            if($array_input['id_ref_pasien'] != "")
            {
                $data_kasir_titip_uang = array(
                    'tanggal'               => date("Y-m-d", strtotime($array_input['tanggal'])),
                    'user_id'               => $array_input['id_ref_pasien'],
                    'tipe_user'             => $array_input['tipe_user'],
                    'rupiah'                => $array_input['rupiah'],
                    'rupiah_terima'         => 0,
                    'rupiah_sisa'           => $array_input['rupiah'],
                    'subjek'                => $array_input['subjek'],
                    'keterangan'            => $array_input['keterangan'],
                    'is_lunas'              => 0,
                );
                
                $save_kasir_titip_uang = $this->kasir_titip_uang_m->save($data_kasir_titip_uang);
                // die_dump($this->db->last_query());    
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

        redirect("kasir/titip_terima_uang");

    }

    public function save_terima_uang()
    {
        $array_input = $this->input->post();
        $id          = $array_input['kasir_titip_uang_id'];
        $user_id     = $array_input['id_ref_pasien'];
        $tipe_user   = $array_input['tipe_user'];
        die_dump($array_input);   
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

        redirect("kasir/titip_terima_uang");
        
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
}

/* End of file titip_terima_uang.php */
/* Location: ./application/controllers/kasir/titip_terima_uang.php */