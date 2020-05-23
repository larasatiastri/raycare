<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabang extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'f983b4b80c00be37fb57f21bc007c957';                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    // private $data_main = array();

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('master/cabang_m');
        $this->load->model('master/alamat_m');
        $this->load->model('master/telepon_m');
        $this->load->model('master/region_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/user_m');
        $this->load->model('master/cabang_poliklinik_m');
        $this->load->model('master/cabang_poliklinik_dokter_m');
        $this->load->model('master/cabang_poliklinik_perawat_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/cabang/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Cabang', $this->session->userdata('language')), 
            'header'         => translate('Master Cabang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/cabang/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/cabang/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate("Tambah Cabang", $this->session->userdata("language")), 
            'header'         => translate("Tambah Cabang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/cabang/add',
        );
         $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $assets_config = 'assets/master/cabang/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->cabang_m->get($id);

        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate("Tambah Cabang", $this->session->userdata("language")), 
            'header'         => translate("Tambah Cabang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/cabang/view',
            'form_data'            => object_to_array($form_data),
            'form_data_alamat'     => $this->alamat_m->get_by(array('cabang_id' =>$id)),
            'customer_phone'       => $this->telepon_m->get_data($id),
            'form_data_poliklinik' => $this->cabang_poliklinik_m->get_by(array('cabang_id' => $id)),
            'pk_value'             => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $this->load->model('master/info_alamat_m');

        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/cabang/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        $form_data = $this->cabang_m->get($id);
        // die_dump($form_alamat);
        $data = array(
            'title'                => config_item('site_name').' &gt;'. translate("Edit Cabang", $this->session->userdata("language")), 
            'header'               => translate("Edit Cabang", $this->session->userdata("language")), 
            'header_info'          => config_item('site_name'), 
            'breadcrumb'           => TRUE,
            'menus'             => $this->menus,
            'menu_tree'         => $this->menu_tree,
            'css_files'            => $assets['css'],
            'js_files'             => $assets['js'],
            'content_view'         => 'master/cabang/edit',
            'form_data'            => object_to_array($form_data),
            'form_data_alamat'     => $this->alamat_m->get_by(array('cabang_id' =>$id)),
            'customer_phone'       => $this->telepon_m->get_data($id),
            'form_data_poliklinik' => $this->cabang_poliklinik_m->get_by(array('cabang_id' => $id, 'is_active' => 1)),
            'pk_value'             => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        
        $result = $this->cabang_m->get_datatable();
        //die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/cabang/view/'.$row['id'].'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>
            <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/cabang/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
            <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data cabang ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
           

            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.$row['kode'].'</div>',
                $row['nama'],
                '<div class="text-center">'.$row['nomor'].'</div>',
                $row['alamat'],
                $row['keterangan'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function search_kelurahan()
    {
       $data = array(
        
        );
       $this->load->view('master/cabang/modal/search_alamat', $data);
    }

    public function listing_alamat()
    {
        $this->load->model('master/info_alamat_m');
        $result = $this->info_alamat_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
      

        foreach($records->result_array() as $row)
        {             
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-confirm="'.translate('Pilih alamat ini?', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select_alamat"><i class="fa fa-check"></i> </a>';
            
            $output['data'][] = array(
                '<div class="text-left">'.$row['kelurahan'].'</div>' ,
                '<div class="text-left">'.$row['kecamatan'].'</div>' ,
                '<div class="text-left">'.$row['kotkab'].'</div>',
                '<div class="text-left">'.$row['propinsi'].'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
        }

        echo json_encode($output);
    }

    public function save()
    {
        $command        = $this->input->post('command');
        $tipe           = $this->input->post('tipe');
        $kode           = $this->input->post('kode');
        $nama           = $this->input->post('nama');
        $url            = $this->input->post('url');
        $ket            = $this->input->post('keterangan');
        $sub_alamat     = $this->input->post('sub_alamat');
        $alamat         = $this->input->post('alamat');
        $rt             = $this->input->post('rt');
        $rw             = $this->input->post('rw');
        $kode_lokasi    = $this->input->post('input_kode');
        $kode_pos       = $this->input->post('kode_pos');
        $telepon        = $this->input->post('phone');
        $poliklinik     = $this->input->post('poliklinik');

        $data_cabang = $this->cabang_m->get();
        //$tanggal = $this->input->post('defaultrange');
        //die_dump($poliklinik);
        
        $data_rt_rw = array(
            "rt" => $rt,
            "rw" => $rw);
        $rt_rw = implode("/", $data_rt_rw);
        //die_dump($rt_rw);
        if ($command === 'add')
        {  
            $data = array(
                "tipe"       => $tipe,
                "kode"       => $kode,
                "nama"       => $nama,
                "url"        => $url,
                "keterangan" => $ket,
                "is_active"  => 1
            );
            // $cabang_id = $this->cabang_m->save($data);
            $path_model = 'master/cabang_m';
            $cabang_id = insert_data_api($data,base_url(),$path_model);
            $inserted_cabang_id = $cabang_id;

            foreach ($data_cabang as $cabang_data) 
            {
                if($cabang_data->url != '' || $cabang_data->url != NULL)
                {
                    $cabang_id = insert_data_api($data,$cabang_data->url,$path_model, $inserted_cabang_id);
                }
            }

            $inserted_cabang_id = str_replace('"', '', $inserted_cabang_id);

            //die_dump($this->db->last_query());

            $data_alamat = array(
                "cabang_id"    => $inserted_cabang_id,
                "subjek_id"    => $sub_alamat,
                "alamat"       => $alamat,
                "rt_rw"        => $rt_rw,
                "kode_pos"     => $kode_pos,
                "kode_lokasi"    => $kode_lokasi,
                "is_primary"   => 1,
                "is_active"    => 1
            );
            //die_dump($data_alamat);
            // $alamat_id = $this->alamat_m->save($data_alamat);
            $path_model = 'master/alamat_m';
            $alamat_id = insert_data_api($data_alamat,base_url(),$path_model);
            $inserted_cabang_alm_id = $alamat_id;

            foreach ($data_cabang as $cabang_data) 
            {
                if($cabang_data->url != '' || $cabang_data->url != NULL)
                {
                    $alamat_id = insert_data_api($data_alamat,$cabang_data->url,$path_model, $inserted_cabang_alm_id);
                }
            }
            //die_dump($this->db->last_query());

            foreach ($telepon as $input) 
            {
                if(isset($input['is_primary']) && $input['is_primary'] !== '')
                {
                    $stat_primary = 1;
                }
                else
                {
                    $stat_primary = 0;
                }

                $data_telepon = array(
                    "cabang_id"  => $inserted_cabang_id,
                    "subjek_id"  => $input['subjek'],
                    "nomor"      => $input['number'],
                    "is_primary" => ($input['is_primary'] != '')? $input['is_primary']:0,
                    "is_active"  => 1
                );

                // $telepon_id = $this->telepon_m->save($data_telepon);
                $path_model = 'master/telepon_m';
                $telepon_id = insert_data_api($data_telepon,base_url(),$path_model);
                $inserted_cabang_tlp_id = $telepon_id;

                foreach ($data_cabang as $cabang_data) 
                {
                    if($cabang_data->url != '' || $cabang_data->url != NULL)
                    {
                        $telepon_id = insert_data_api($data_telepon,$cabang_data->url,$path_model, $inserted_cabang_tlp_id);
                    }
                }
            }

            if($poliklinik)
            {                
                foreach ($poliklinik as $cabang) 
                {

                    $data_poliklinik = array(
                        "cabang_id"     => $inserted_cabang_id,
                        "poliklinik_id" => $cabang['subjek'],
                        "jam_buka"      => date('H:i:s', strtotime($cabang['jam_buka'])),
                        "jam_tutup"     => date('H:i:s', strtotime($cabang['jam_tutup'])),
                        "is_active"     => 1
                    );

                    // $poliklinik_id = $this->cabang_poliklinik_m->save($data_poliklinik);

                    $path_model = 'master/cabang_poliklinik_m';
                    $poliklinik_id = insert_data_api($data_poliklinik,base_url(),$path_model);
                    $inserted_cabang_poli_id = $poliklinik_id;

                    foreach ($data_cabang as $cabang_data) 
                    {
                        if($cabang_data->url != '' || $cabang_data->url != NULL)
                        {
                            $poliklinik_id = insert_data_api($data_poliklinik,$cabang_data->url,$path_model, $inserted_cabang_poli_id);
                        }
                    }

                    $inserted_cabang_poli_id = str_replace('"', '', $inserted_cabang_poli_id);

                    foreach ($cabang['dokter'] as $dokter) 
                    {
                        $data_dokter = array(
                            "cabang_poliklinik_id" => $inserted_cabang_poli_id,
                            "dokter_id" => $dokter
                        );
                        
                        // $cabang_dokter = $this->cabang_poliklinik_dokter_m->save($data_dokter);

                        $path_model = 'master/cabang_poliklinik_dokter_m';
                        $cabang_dokter = insert_data_api($data_dokter,base_url(),$path_model);
                        $inserted_cabang_poli_dr_id = $cabang_dokter;

                        foreach ($data_cabang as $cabang_data) 
                        {
                            if($cabang_data->url != '' || $cabang_data->url != NULL)
                            {
                                $cabang_dokter = insert_data_api($data_dokter,$cabang_data->url,$path_model, $inserted_cabang_poli_dr_id);
                            }
                        }
                        //die_dump($this->db->last_query());
                    }

                    foreach ($cabang['perawat'] as $perawat) 
                    {
                        $data_perawat = array(
                            "cabang_poliklinik_id" => $inserted_cabang_poli_id,
                            "perawat_id" => $perawat
                        );
                        // die_dump($data_perawat);
                        // $cabang_dokter = $this->cabang_poliklinik_perawat_m->save($data_perawat);

                        $path_model = 'master/cabang_poliklinik_perawat_m';
                        $cabang_perawat = insert_data_api($data_perawat,base_url(),$path_model);
                        $inserted_cabang_poli_prwt_id = $cabang_perawat;

                        foreach ($data_cabang as $cabang_data) 
                        {
                            if($cabang_data->url != '' || $cabang_data->url != NULL)
                            {
                                $cabang_perawat = insert_data_api($data_perawat,$cabang_data->url,$path_model, $inserted_cabang_poli_prwt_id);
                            }
                        }

                        //die_dump($this->db->last_query());
                    }
                }
            }

           

            if ($cabang_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data cabang berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }
        elseif ($command === 'edit')
        {
            $id = $this->input->post('id');
            $id_alamat = $this->input->post('id_alamat');

            $array_input = $this->input->post();
            // die(dump($array_input));
            $data = array(
                "tipe"       => $tipe,
                "kode"       => $kode,
                "nama"       => $nama,
                "url"        => $url,
                "keterangan" => $ket,
                "is_active"  => 1
            );
            // $cabang_id = $this->cabang_m->save($data, $id);

            $path_model = 'master/cabang_m';
            $cabang_id = insert_data_api($data,base_url(),$path_model,$id);
            $inserted_cabang_id = $cabang_id;

            foreach ($data_cabang as $cabang_data) 
            {
                if($cabang_data->url != '' || $cabang_data->url != NULL)
                {
                    $cabang_id = insert_data_api($data,$cabang_data->url,$path_model,$id);
                }
            }
            $inserted_cabang_id = str_replace('"', '', $inserted_cabang_id);
            //die_dump($this->db->last_query());

            $data_alamat = array(
                "cabang_id"    => $inserted_cabang_id,
                "subjek_id"    => $sub_alamat,
                "alamat"       => $alamat,
                "rt_rw"        => $rt_rw,
                "kode_pos"     => $kode_pos,
                "kode_lokasi"    => $kode_lokasi,
                "is_primary"   => 1,
                "is_active"    => 1
            );
            //die_dump($data_alamat);
            // $alamat_id = $this->alamat_m->save($data_alamat, $id_alamat);

            $path_model = 'master/alamat_m';
            $alamat_id = insert_data_api($data_alamat,base_url(),$path_model,$id_alamat);
            $inserted_alamat_id = $alamat_id;

            foreach ($data_cabang as $cabang_data) 
            {
                if($cabang_data->url != '' || $cabang_data->url != NULL)
                {
                    $alamat_id = insert_data_api($data_alamat,$cabang_data->url,$path_model,$id_alamat);
                }
            }
            //die_dump($this->db->last_query());

            foreach ($telepon as $input) 
            {
                if(isset($input['is_primary']) && $input['is_primary'] !== '')
                {
                    $stat_primary = 1;
                }
                else
                {
                    $stat_primary = 0;
                }

                if ($input['id'] != "" && $input['is_delete'] == "") 
                {
                    $data_telepon = array(
                        "cabang_id"  => $inserted_cabang_id,
                        "subjek_id"  => $input['subjek'],
                        "nomor"      => $input['number'],
                        "is_primary" => ($input['is_primary'] != '')? $input['is_primary']:0,
                        "is_active"  => 1
                    );
                    // $telepon_id = $this->telepon_m->save($data_telepon, $input['id']);

                    $path_model = 'master/telepon_m';
                    $telepon_id = insert_data_api($data_telepon,base_url(),$path_model,$input['id']);
                    $inserted_telepon_id = $telepon_id;

                    foreach ($data_cabang as $cabang_data) 
                    {
                        if($cabang_data->url != '' || $cabang_data->url != NULL)
                        {
                            $telepon_id = insert_data_api($data_telepon,$cabang_data->url,$path_model,$input['id']);
                        }
                    }
                    // $save_phone = $this->pasien_telepon_m->save($data_phone, $phone['id']); 
                }

                if ($input['id'] != "" && $input['is_delete'] == "1") 
                {                       
                    $data_telepon = array(
                        "is_active"  => 0
                    );
                    // $telepon_id = $this->telepon_m->save($data_telepon, $input['id']);

                    $path_model = 'master/telepon_m';
                    $telepon_id = insert_data_api($data_telepon,base_url(),$path_model,$input['id']);
                    $inserted_telepon_id = $telepon_id;

                    foreach ($data_cabang as $cabang_data) 
                    {
                        if($cabang_data->url != '' || $cabang_data->url != NULL)
                        {
                            $telepon_id = insert_data_api($data_telepon,$cabang_data->url,$path_model,$input['id']);
                        }
                    }
                }
                
                if ($input['id'] == "" && $input['is_delete'] == "" && $input['number'] != "") 
                {
                    $data_telepon = array(
                        "cabang_id"  => $inserted_cabang_id,
                        "subjek_id"  => $input['subjek'],
                        "nomor"      => $input['number'],
                        "is_primary" => ($input['is_primary'] != '')? $input['is_primary']:0,
                        "is_active"  => 1
                    );
                    
                    // $save_phone = $this->telepon_m->save($data_telepon); 
                    $path_model = 'master/telepon_m';
                    $telepon_id = insert_data_api($data_telepon,base_url(),$path_model);
                    $inserted_telepon_id = $telepon_id;

                    foreach ($data_cabang as $cabang_data) 
                    {
                        if($cabang_data->url != '' || $cabang_data->url != NULL)
                        {
                            $telepon_id = insert_data_api($data_telepon,$cabang_data->url,$path_model,$inserted_telepon_id);
                        }
                    }
                }
                
            }

            if($poliklinik)
            {
                foreach ($poliklinik as $cabang) 
                {
                    //die_dump($cabang);
                    if ($cabang['id'] != "" && $cabang['is_deleted'] == "")
                    {
                        $data_poliklinik = array(
                            "cabang_id"     => $inserted_cabang_id,
                            "poliklinik_id" => $cabang['subjek'],
                            "jam_buka"      => date('H:i:s', strtotime($cabang['jam_buka'])),
                            "jam_tutup"     => date('H:i:s', strtotime($cabang['jam_tutup'])),
                            "is_active"     => 1
                        );
                         //die_dump($data_poliklinik);
                        // $poliklinik_id = $this->cabang_poliklinik_m->save($data_poliklinik, $cabang['id']);
                        $path_model = 'master/cabang_poliklinik_m';
                        $poliklinik_id = insert_data_api($data_poliklinik,base_url(),$path_model,$cabang['id']);
                        $inserted_cabang_poli_id = $poliklinik_id;

                        foreach ($data_cabang as $cabang_data) 
                        {
                            if($cabang_data->url != '' || $cabang_data->url != NULL)
                            {
                                $poliklinik_id = insert_data_api($data_poliklinik,$cabang_data->url,$path_model, $cabang['id']);
                            }
                        }

                        $inserted_cabang_poli_id = str_replace('"', '', $inserted_cabang_poli_id);

                        foreach ($cabang['dokter'] as $dokter) 
                        {
                            $dokter_poli = $this->cabang_poliklinik_dokter_m->get_by(array('cabang_poliklinik_id' => $inserted_cabang_poli_id, 'dokter_id' => $dokter));
                            if(count($dokter_poli) == 0)
                            {
                                $data_dokter = array(
                                    "cabang_poliklinik_id" => $inserted_cabang_poli_id,
                                    "dokter_id" => $dokter
                                );
                                
                                // $cabang_dokter = $this->cabang_poliklinik_dokter_m->save($data_dokter);

                                $path_model = 'master/cabang_poliklinik_dokter_m';
                                $cabang_dokter = insert_data_api($data_dokter,base_url(),$path_model);
                                $inserted_cabang_poli_dr_id = $cabang_dokter;

                                foreach ($data_cabang as $cabang_data) 
                                {
                                    if($cabang_data->url != '' || $cabang_data->url != NULL)
                                    {
                                        $cabang_dokter = insert_data_api($data_dokter,$cabang_data->url,$path_model, $inserted_cabang_poli_dr_id);
                                    }
                                }                                
                            }
                            //die_dump($this->db->last_query());
                        }

                        foreach ($cabang['perawat'] as $perawat) 
                        {
                            $perawat_poli = $this->cabang_poliklinik_perawat_m->get_by(array('cabang_poliklinik_id' => $inserted_cabang_poli_id, 'perawat_id' => $perawat));
                            if(count($perawat_poli) == 0)
                            {
                                $data_perawat = array(
                                    "cabang_poliklinik_id" => $inserted_cabang_poli_id,
                                    "perawat_id" => $perawat
                                );
                                // die_dump($data_perawat);
                                // $cabang_dokter = $this->cabang_poliklinik_perawat_m->save($data_perawat);

                                $path_model = 'master/cabang_poliklinik_perawat_m';
                                $cabang_perawat = insert_data_api($data_perawat,base_url(),$path_model);
                                $inserted_cabang_poli_prwt_id = $cabang_perawat;

                                foreach ($data_cabang as $cabang_data) 
                                {
                                    if($cabang_data->url != '' || $cabang_data->url != NULL)
                                    {
                                        $cabang_perawat = insert_data_api($data_perawat,$cabang_data->url,$path_model, $inserted_cabang_poli_prwt_id);
                                    }
                                }                                
                            }
                            //die_dump($this->db->last_query());
                        }

                        // die_dump($this->db->last_query());
                       
                    }
                    if($cabang['id'] != "" && $cabang['is_deleted'] == "1")
                    {
                        $data_poliklinik['is_active'] = 0;
                        // $delete_poliklinik = $this->cabang_poliklinik_m->delete($cabang['id']);
                        $path_model = 'master/cabang_poliklinik_m';
                        $poliklinik_id = insert_data_api($data_poliklinik,base_url(),$path_model,$cabang['id']);
                        $inserted_cabang_poli_id = $poliklinik_id;

                        foreach ($data_cabang as $cabang_data) 
                        {
                            if($cabang_data->url != '' || $cabang_data->url != NULL)
                            {
                                $poliklinik_id = insert_data_api($data_poliklinik,$cabang_data->url,$path_model, $cabang['id']);
                            }
                        }
                    }
                    if ($cabang['id'] == "" && $cabang['is_deleted'] == "")
                    {
                        if($cabang['subjek'] != '')
                        {
                            $data_poliklinik = array(
                                "cabang_id"     => $inserted_cabang_id,
                                "poliklinik_id" => $cabang['subjek'],
                                "jam_buka"      => date('H:i:s', strtotime($cabang['jam_buka'])),
                                "jam_tutup"     => date('H:i:s', strtotime($cabang['jam_tutup'])),
                                "is_active"     => 1
                            );
                             //die_dump($data_poliklinik);
                            // $poliklinik_id = $this->cabang_poliklinik_m->save($data_poliklinik);

                            $path_model = 'master/cabang_poliklinik_m';
                            $poliklinik_id = insert_data_api($data_poliklinik,base_url(),$path_model);
                            $inserted_cabang_poli_id = $poliklinik_id;

                            foreach ($data_cabang as $cabang_data) 
                            {
                                if($cabang_data->url != '' || $cabang_data->url != NULL)
                                {
                                    $poliklinik_id = insert_data_api($data_poliklinik,$cabang_data->url,$path_model, $inserted_cabang_poli_id);
                                }
                            }

                            $inserted_cabang_poli_id = str_replace('"', '', $inserted_cabang_poli_id);

                            foreach ($cabang['dokter'] as $dokter) 
                            {
                                $data_dokter = array(
                                    "cabang_poliklinik_id" => $inserted_cabang_poli_id,
                                    "dokter_id" => $dokter
                                );
                                
                                // $cabang_dokter = $this->cabang_poliklinik_dokter_m->save($data_dokter);

                                $path_model = 'master/cabang_poliklinik_dokter_m';
                                $cabang_dokter = insert_data_api($data_dokter,base_url(),$path_model);
                                $inserted_cabang_poli_dr_id = $cabang_dokter;

                                foreach ($data_cabang as $cabang_data) 
                                {
                                    if($cabang_data->url != '' || $cabang_data->url != NULL)
                                    {
                                        $cabang_dokter = insert_data_api($data_dokter,$cabang_data->url,$path_model, $inserted_cabang_poli_dr_id);
                                    }
                                }
                                //die_dump($this->db->last_query());
                            }

                            foreach ($cabang['perawat'] as $perawat) 
                            {
                                $data_perawat = array(
                                    "cabang_poliklinik_id" => $inserted_cabang_poli_id,
                                    "perawat_id" => $perawat
                                );
                                // die_dump($data_perawat);
                                // $cabang_dokter = $this->cabang_poliklinik_perawat_m->save($data_perawat);

                                $path_model = 'master/cabang_poliklinik_perawat_m';
                                $cabang_perawat = insert_data_api($data_perawat,base_url(),$path_model);
                                $inserted_cabang_poli_prwt_id = $cabang_perawat;

                                foreach ($data_cabang as $cabang_data) 
                                {
                                    if($cabang_data->url != '' || $cabang_data->url != NULL)
                                    {
                                        $cabang_perawat = insert_data_api($data_perawat,$cabang_data->url,$path_model, $inserted_cabang_poli_prwt_id);
                                    }
                                }
                            }
                        }
                    }               
                }
            }



            if ($cabang_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data cabang berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/cabang");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        // $user_id = $this->cabang_m->save($data, $id);

        $path_model = 'master/cabang_m';
        $cabang_id = insert_data_api($data,base_url(),$path_model);
        $inserted_cabang_id = $cabang_id;

        foreach ($data_cabang as $cabang_data) 
        {
            if($cabang_data->url != '' || $cabang_data->url != NULL)
            {
                $cabang_id = insert_data_api($data,$cabang_data->url,$path_model, $inserted_cabang_id);
            }
        }

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->max_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->max_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'  => 1,
            'data_id'    => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Branch Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/cabang");
    }

    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        // $user_id = $this->cabang_m->save($data, $id);
        $path_model = 'master/cabang_m';
        $cabang_id = insert_data_api($data,base_url(),$path_model);
        $inserted_cabang_id = $cabang_id;

        foreach ($data_cabang as $cabang_data) 
        {
            if($cabang_data->url != '' || $cabang_data->url != NULL)
            {
                $cabang_id = insert_data_api($data,$cabang_data->url,$path_model, $inserted_cabang_id);
            }
        }
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Branch Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/cabang");
    }


    public function save_subjek(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_subjek = array(
            "tipe"     => $tipe,
            "nama"   => $nama,
        );

        // $save_data = $this->subjek_m->save($data_subjek);
        $data_cabang = $this->cabang_m->get();

        $path_model = 'master/subjek_m';
        $save_data = insert_data_api($data_subjek,base_url(),$path_model);
        $inserted_save_data = $save_data;

        foreach ($data_cabang as $cabang_data) 
        {
            if($cabang_data->url != '' || $cabang_data->url != NULL)
            {
                $save_data = insert_data_api($data_subjek,$cabang_data->url,$path_model, $inserted_save_data);
            }
        }

        $inserted_save_data = str_replace('"', '', $inserted_save_data);


        $hasil_data_subjek = new stdClass;

        $data_subjek = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek->id = $inserted_save_data;
        $hasil_data_subjek->data = object_to_array($data_subjek);

        echo json_encode($hasil_data_subjek);

    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */