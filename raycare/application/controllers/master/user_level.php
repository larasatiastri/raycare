<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_level extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '40e769b6972c42e45f5218cb88db7348';                  // untuk check bit_access

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

        $this->load->model('master/menu_user_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/user_level_m');
        $this->load->model('master/user_level_menu_m');
        $this->load->model('master/user_level_persetujuan_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        // die(dump($this->session->userdata('session')));
        // $test = urlencode(base64_encode('http://simrhs.com/ravena/pembelian/persetujuan_permintaan_po'));
        // die(dump($test));
        $assets = array();
        $config = 'assets/master/user_level/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master User Level', $this->session->userdata('language')), 
            'header'         => translate('Master User Level', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/user_level/index',
            );
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_user_level','add'))
        {
            $assets = array();
            $assets_config = 'assets/master/user_level/add';
            $this->config->load($assets_config, true);

            $assets = $this->config->item('assets', $assets_config);

            $data = array(
                'title'          => config_item('site_name'). ' | '.translate("Tambah User Level", $this->session->userdata("language")), 
                'header'         => translate("Tambah User Level", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/user_level/add',
                'flag'           => 'add',
            );

            // Load the view
            $this->load->view('_layout', $data);            
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function edit($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_user_level','edit'))
        {
            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/master/user_level/edit';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
            //die_dump($this->user_level_m->get_data($id));
            $form_data = $this->user_level_m->get($id);
            // die_dump($form_data);

            $data = array(
                'title'                 => config_item('site_name').' | '. translate("Edit User Level", $this->session->userdata("language")), 
                'header'                => translate("Edit User Level", $this->session->userdata("language")), 
                'header_info'           => config_item('site_name'), 
                'breadcrumb'            => TRUE,
                'menus'                 => $this->menus,
                'menu_tree'             => $this->menu_tree,
                'css_files'             => $assets['css'],
                'js_files'              => $assets['js'],
                'content_view'          => 'master/user_level/edit',
                'form_data'             => object_to_array($form_data),
                'form_data_persetujuan'          => $this->user_level_m->get_data($id, 1),
                'form_data_persetujuan_item'     => $this->user_level_m->get_data($id, 2),
                'form_data_persetujuan_supplier' => $this->user_level_m->get_data($id, 3),
                'form_data_persetujuan_customer' => $this->user_level_m->get_data($id, 4),
                'form_data_persetujuan_biaya' => $this->user_level_m->get_data($id, 5),
                'pk_value'              => $id,
                'flag'                  => 'edit'                         //table primary key value
            );

            // Load the view
            $this->load->view('_layout', $data);
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function menu($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_user_level','menu'))
        {
            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/master/user_level/menu';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
            //die_dump($this->user_level_m->get_data($id));
            $form_data = $this->user_level_m->get($id);
            // die_dump($form_data);

            $data = array(
                'title'                 => config_item('site_name').' | '. translate("Edit User Level", $this->session->userdata("language")), 
                'header'                => translate("Edit User Level", $this->session->userdata("language")), 
                'header_info'           => config_item('site_name'), 
                'breadcrumb'            => TRUE,
                'menus'                 => $this->menus,
                'menu_tree'             => $this->menu_tree,
                'css_files'             => $assets['css'],
                'js_files'              => $assets['js'],
                'content_view'          => 'master/user_level/menu',
                'form_data'             => object_to_array($form_data),
                'pk_value'              => $id,
            );

            // Load the view
            $this->load->view('_layout', $data);
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($bagian)
    {        
        $result = $this->user_level_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            if ($bagian == 'add') 
            {
                $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
            }
            else
            {
                $data_menu = '<a title="'.translate('Menu', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/menu/'.$row['id'].'" class="btn green-haze"><i class="fa fa-list"></i></a>';
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action = restriction_button($data_menu,$user_level_id,'master_user_level','menu'). restriction_button($data_edit,$user_level_id,'master_user_level','edit').restriction_button($data_delete,$user_level_id,'master_user_level','delete');
            }

            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_p_item($bagian)
    {        
        $result = $this->user_level_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            if ($bagian == 'add') 
            {
                $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select-p-item" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
            }
            else
            {
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_edit,$user_level_id,'master_user_level','edit').restriction_button($data_delete,$user_level_id,'master_user_level','delete');
            }

            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_p_biaya($bagian)
    {        
        $result = $this->user_level_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            if ($bagian == 'add') 
            {
                $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select-p-biaya" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
            }
            else
            {
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_edit,$user_level_id,'master_user_level','edit').restriction_button($data_delete,$user_level_id,'master_user_level','delete');
            }

            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_p_supplier($bagian)
    {        
        $result = $this->user_level_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            if ($bagian == 'add') 
            {
                $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select-p-supplier" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
            }
            else
            {
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_edit,$user_level_id,'master_user_level','edit').restriction_button($data_delete,$user_level_id,'master_user_level','delete');
            }

            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_p_customer($bagian)
    {        
        $result = $this->user_level_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            if ($bagian == 'add') 
            {
                $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select-p-customer" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
            }
            else
            {
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_edit,$user_level_id,'master_user_level','edit').restriction_button($data_delete,$user_level_id,'master_user_level','delete');
            }

            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function search_user_level()
    {
        $data = array(
        
        );
       $this->load->view('master/user_level/modal/modal_add_user_level_p', $data);
    }

    public function search_user_level_biaya()
    {
        $data = array(
        
        );
       $this->load->view('master/user_level/modal/modal_add_user_level_p_biaya', $data);
    }

    public function search_user_level_item()
    {
        $data = array(
        
        );
       $this->load->view('master/user_level/modal/modal_add_user_level_p_item', $data);
    }

    public function search_user_level_supplier()
    {
        $data = array(
        
        );
       $this->load->view('master/user_level/modal/modal_add_user_level_p_supplier', $data);
    }

    public function search_user_level_customer()
    {
        $data = array(
        
        );
       $this->load->view('master/user_level/modal/modal_add_user_level_p_customer', $data);
    }

    public function save()
    {
        $array_input = $this->input->post();
        $data_cabang = $this->cabang_m->get();
        $user_level_persetujuan          = $this->input->post('user_level');
        $user_level_persetujuan_item     = $this->input->post('user_level_persetujuan_item');
        $user_level_persetujuan_supplier = $this->input->post('user_level_persetujuan_supplier');
        $user_level_persetujuan_customer = $this->input->post('user_level_persetujuan_customer');
        // die_dump($array_input);

        // foreach ($user_level_persetujuan as $row_persetujuan) 

        // {
            
        //         die_dump($row_persetujuan);

        // }



        if ($array_input['command'] === 'add')
        {  
            // $data = array(
            //     'nama'          => $array_input['nama_user'],
            //     'dashboard_url' => null,
            //     'is_active'     => '1',
            //     'persetujuan'   => serialize($array_input['user_level'])
                
            // );
            // $url = base_url();
            // $save_user_level_id = insert_user_level_api($data,$url);
            
            // $inserted_id = $save_user_level_id;
            //     // die(dump($inserted_id));

            // foreach ($data_cabang as $row_cabang) 
            // {
            //     $url = $row_cabang->url;
            //     $save_user_level_id = insert_user_level_api($data,$url,$inserted_id);
     
            // }
            
            
            $data = array(

                'nama'          => $array_input['nama_user'],
                'cabang_id'     => $array_input['cabang_id'],
                'dashboard_url' => $array_input['dashboard_url'],
                'is_active'     => 1,

            );

            // $save_user_level = $this->user_level_m->save($data);
            $path_model = 'master/user_level_m';
            $save_user_level = insert_data_api($data,base_url(),$path_model);
            $inserted_save_user_level = $save_user_level;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active != 0)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_user_level = insert_data_api($data,$cabang->url,$path_model,$inserted_save_user_level);
                    }                    
                }
            }
            $inserted_save_user_level = str_replace('"', '', $inserted_save_user_level);

            if($save_user_level != '')
            {

                foreach ($user_level_persetujuan as $row_persetujuan) 
                {
                    if($row_persetujuan['id'] != '')
                    {
                        $data = array(

                            'user_level_id'             => $inserted_save_user_level,
                            'user_level_menyetujui_id'  => $row_persetujuan['id'],
                            'tipe_persetujuan'          => 1,
                            'level_order'               => $row_persetujuan['order'],
                            'is_active'                 => 1,

                        );
                        $path_model = 'master/user_level_persetujuan_m';
                        $save_persetujuan_item = insert_data_api($data,base_url(),$path_model);
                        $inserted_save_persetujuan_item = $save_persetujuan_item;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active != 0)
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_persetujuan_item = insert_data_api($data,$cabang->url,$path_model,$inserted_save_persetujuan_item);
                                }                    
                            }
                        }
                        $inserted_save_persetujuan_item = str_replace('"', '', $inserted_save_persetujuan_item);

                    }
                }


                foreach ($user_level_persetujuan_item as $row_persetujuan_item) 
                {
                    if($row_persetujuan_item['id'] != '')
                    {
                        $data = array(

                            'user_level_id'             => $save_user_level,
                            'user_level_menyetujui_id'  => $row_persetujuan_item['id'],
                            'tipe_persetujuan'          => 2,
                            'level_order'               => $row_persetujuan_item['order'],
                            'is_active'                 => 1,

                        );

                        $path_model = 'master/user_level_persetujuan_m';
                        $save_persetujuan_item = insert_data_api($data,base_url(),$path_model);
                        $inserted_save_persetujuan_item = $save_persetujuan_item;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active != 0)
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_persetujuan_item = insert_data_api($data,$cabang->url,$path_model,$inserted_save_persetujuan_item);
                                }                    
                            }
                        }
                        $inserted_save_persetujuan_item = str_replace('"', '', $inserted_save_persetujuan_item);
                    }
                }

                foreach ($user_level_persetujuan_supplier as $row_persetujuan_supplier) 
                {
                    if($row_persetujuan_supplier['id'] != '')
                    {
                        $data = array(

                            'user_level_id'             => $save_user_level,
                            'user_level_menyetujui_id'  => $row_persetujuan_supplier['id'],
                            'tipe_persetujuan'          => 3,
                            'level_order'               => $row_persetujuan_supplier['order'],
                            'is_active'                 => 1,

                        );

                        $path_model = 'master/user_level_persetujuan_m';
                        $save_persetujuan_item = insert_data_api($data,base_url(),$path_model);
                        $inserted_save_persetujuan_item = $save_persetujuan_item;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active != 0)
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_persetujuan_item = insert_data_api($data,$cabang->url,$path_model,$inserted_save_persetujuan_item);
                                }                    
                            }
                        }
                        $inserted_save_persetujuan_item = str_replace('"', '', $inserted_save_persetujuan_item);
                    }
                }

                foreach ($user_level_persetujuan_customer as $row_persetujuan_customer) 
                {
                    if($row_persetujuan_customer['id'] != '')
                    {
                        $data = array(

                            'user_level_id'             => $save_user_level,
                            'user_level_menyetujui_id'  => $row_persetujuan_customer['id'],
                            'tipe_persetujuan'          => 4,
                            'level_order'               => $row_persetujuan_customer['order'],
                            'is_active'                 => 1,

                        );

                        $path_model = 'master/user_level_persetujuan_m';
                        $save_persetujuan_item = insert_data_api($data,base_url(),$path_model);
                        $inserted_save_persetujuan_item = $save_persetujuan_item;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active != 0)
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_persetujuan_item = insert_data_api($data,$cabang->url,$path_model,$inserted_save_persetujuan_item);
                                }                    
                            }
                        }
                        $inserted_save_persetujuan_item = str_replace('"', '', $inserted_save_persetujuan_item);
                    }
                }
            }


            if ($save_user_level && $save_persetujuan_customer) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data User Level berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }


            
        }
        
        elseif ($array_input['command'] === 'edit')
        {
            // die_dump($array_input);
            $data = array(
                'nama'              => $array_input['nama_user'],
                'cabang_id'         => $array_input['cabang_id'],
                'dashboard_url'     => $array_input['dashboard_url'],
                'is_active'         => '1',
            );  
            $save_user_level_id = $this->user_level_m->save($data, $array_input['id']);

            $path_model = 'master/user_level_m';
            $save_user_level = insert_data_api($data,base_url(),$path_model, $array_input['id']);
            $inserted_save_user_level = $save_user_level;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active != 0)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_user_level = insert_data_api($data,$cabang->url,$path_model, $array_input['id']);
                    }                    
                }
            }
            $inserted_save_user_level = str_replace('"', '', $inserted_save_user_level);

          
            ///////////////////////////tab persetujuan pembelian/////////////////////////////////////          
            foreach ($array_input['user_level'] as $user_level) 
            {     
                ///////////////////////update row yang sudah ada///////////////////////////
                if ($user_level['id'] != "" && $user_level['action'] == "edit" && $user_level['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'level_order'       => $user_level['order'],
                        'email_notif'       => $user_level['email'],
                        'tipe_persetujuan'  => '1',
                        'is_active'         => '1',
                    );

                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan, $user_level['persetujuan_id']); 
                
                }
                ///////////////////////////////////////////////////////////////////////////

                ///////////////////////add row yang baru///////////////////////////////////
                if ($user_level['id'] != "" && $user_level['action'] == "add" && $user_level['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'user_level_id'            => $array_input['id'],
                        'user_level_menyetujui_id' => $user_level['id'],
                        'level_order'              => $user_level['order'],
                        'email_notif'       => $user_level['email'],
                        'tipe_persetujuan'         => '1',
                        'is_active'                => '1',

                    );
                
                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan); 
                    
                }
                /////////////////////////////////////////////////////////////////////////////

                //////////////////////delete row yang ada di db///////////////////////////////
                if ($user_level['action'] == "edit" && $user_level['delete'] == '1') 
                {
                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->delete($user_level['persetujuan_id']);
                }
                //////////////////////////////////////////////////////////////////////////////
            }

            foreach ($array_input['user_level_biaya'] as $user_level_biaya) 
            {     
                ///////////////////////update row yang sudah ada///////////////////////////
                if ($user_level_biaya['id'] != "" && $user_level_biaya['action'] == "edit" && $user_level_biaya['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'level_order'       => $user_level_biaya['order'],
                        'email_notif'       => $user_level_biaya['email'],
                        'tipe_persetujuan'  => '1',
                        'is_active'         => '1',
                    );

                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan, $user_level_biaya['persetujuan_id']); 
                
                }
                ///////////////////////////////////////////////////////////////////////////

                ///////////////////////add row yang baru///////////////////////////////////
                if ($user_level_biaya['id'] != "" && $user_level_biaya['action'] == "add" && $user_level_biaya['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'user_level_id'            => $array_input['id'],
                        'user_level_menyetujui_id' => $user_level_biaya['id'],
                        'level_order'              => $user_level_biaya['order'],
                        'email_notif'       => $user_level_biaya['email'],
                        'tipe_persetujuan'         => '1',
                        'is_active'                => '1',

                    );
                
                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan); 
                    
                }
                /////////////////////////////////////////////////////////////////////////////

                //////////////////////delete row yang ada di db///////////////////////////////
                if ($user_level_biaya['action'] == "edit" && $user_level_biaya['delete'] == '1') 
                {
                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->delete($user_level_biaya['persetujuan_id']);
                }
                //////////////////////////////////////////////////////////////////////////////
            }

            //////////////////////////////////tab persertujuan item//////////////////////////////////////////

            foreach ($array_input['user_level_persetujuan_item'] as $user_level_persetujuan_item) 
            {
             
                //////////////update row yang sudah ada//////////////////////////               
                if ($user_level_persetujuan_item['id'] != "" && $user_level_persetujuan_item['action'] == "edit" && $user_level_persetujuan_item['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'level_order' => $user_level_persetujuan_item['order'],
                        'email_notif'       => $user_level_persetujuan_item['email'],
                        'tipe_persetujuan'         => '2',
                        'is_active'   => '1',
                    );

                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan, $user_level_persetujuan_item['persetujuan_id']); 
                
                }

                //////////////add row yang baru//////////////////////////////////                
                if ($user_level_persetujuan_item['id'] != "" && $user_level_persetujuan_item['action'] == "add" && $user_level_persetujuan_item['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'user_level_id'            => $array_input['id'],
                        'user_level_menyetujui_id' => $user_level_persetujuan_item['id'],
                        'email_notif'       => $user_level_persetujuan_item['email'],
                        'level_order'              => $user_level_persetujuan_item['order'],
                        'tipe_persetujuan'         => '2',
                        'is_active'                => '1',
                    );
                
                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan); 
                    
                }

                //////////////delete row yg ada di db///////////////////////////
                if ($user_level_persetujuan_item['action'] == "edit" && $user_level_persetujuan_item['delete'] == '1') 
                {
                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->delete($user_level_persetujuan_item['persetujuan_id']);
                }
            }

            ///////////////////////////////////tab persetujuan Supplier////////////////////////////////////////////

            foreach ($array_input['user_level_persetujuan_supplier'] as $user_level_persetujuan_supplier) 
            {
                ///////////update//////////////
                if ($user_level_persetujuan_supplier['id'] != "" && $user_level_persetujuan_supplier['action'] == "edit" && $user_level_persetujuan_supplier['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'level_order' => $user_level_persetujuan_supplier['order'],
                        'email_notif'       => $user_level_persetujuan_supplier['email'],
                        'tipe_persetujuan'         => '3',
                        'is_active'   => '1',
                    );

                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan, $user_level_persetujuan_supplier['persetujuan_id']); 
                
                }

                ////////////add row baru//////////////
                if ($user_level_persetujuan_supplier['id'] != "" && $user_level_persetujuan_supplier['action'] == "add" && $user_level_persetujuan_supplier['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'user_level_id'            => $array_input['id'],
                        'user_level_menyetujui_id' => $user_level_persetujuan_supplier['id'],
                        'level_order'              => $user_level_persetujuan_supplier['order'],
                        'email_notif'       => $user_level_persetujuan_supplier['email'],
                        'tipe_persetujuan'         => '3',
                        'is_active'                => '1',
                    );
                
                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan); 
                    
                }

                /////////////////delete row di db//////////////
                if ($user_level_persetujuan_supplier['action'] == "edit" && $user_level_persetujuan_supplier['delete'] == '1') 
                {

                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->delete($user_level_persetujuan_supplier['persetujuan_id']);
                
                }
            }

            foreach ($array_input['user_level_persetujuan_customer'] as $user_level_persetujuan_customer) 
            {
                ///////////update//////////////
                if ($user_level_persetujuan_customer['id'] != "" && $user_level_persetujuan_customer['action'] == "edit" && $user_level_persetujuan_customer['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'level_order' => $user_level_persetujuan_customer['order'],
                        'email_notif'       => $user_level_persetujuan_customer['email'],
                        'tipe_persetujuan'         => '4',
                        'is_active'   => '1',
                    );

                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan, $user_level_persetujuan_customer['persetujuan_id']); 
                
                }

                ////////////add row baru//////////////
                if ($user_level_persetujuan_customer['id'] != "" && $user_level_persetujuan_customer['action'] == "add" && $user_level_persetujuan_customer['delete'] == "") 
                {
                    $data_persetujuan = array(
                        'user_level_id'            => $array_input['id'],
                        'user_level_menyetujui_id' => $user_level_persetujuan_customer['id'],
                        'level_order'              => $user_level_persetujuan_customer['order'],
                        'email_notif'       => $user_level_persetujuan_customer['email'],
                        'tipe_persetujuan'         => '4    ',
                        'is_active'                => '1',
                    );
                
                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan); 
                    
                }

                /////////////////delete row di db//////////////
                if ($user_level_persetujuan_customer['action'] == "edit" && $user_level_persetujuan_customer['delete'] == '1') 
                {

                    $save_user_level_menyetujui = $this->user_level_persetujuan_m->delete($user_level_persetujuan_customer['persetujuan_id']);
                
                }
            }

            if ($save_user_level_id || $save_data || $save_user_level_menyetujui) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data User Level berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/user_level");
    }

    public function delete($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_user_level','delete'))
        {
            $data = array(
                'is_active'    => 0
            );
            // save data
            $user_id = $this->user_level_m->save($data, $id);

            $max_id = $this->kotak_sampah_m->max();
            if ($max_id->kotak_sampah_id==null){
                $trash_id = 1;
            } else {
                $trash_id = $max_id->kotak_sampah_id+1;
            }

            $data_trash = array(
                'kotak_sampah_id' => $trash_id,
                'tipe'            => 4,
                'data_id'         => $id,
                'created_by'      => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s')
            );

            $trash = $this->kotak_sampah_m->save($data_trash);

            if ($user_id) 
            {
                $flashdata = array(
                    "type"     => "error",
                    "msg"      => translate("User level telah dihapus", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            redirect("master/user_level");
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

     public function check_modified()
    {
        if($this->input->is_ajax_request())
        {
            $response = new StdClass;
            $response->success = false;
            $response->msg = translate('Data yang akan anda ubah telah diubah oleh user lain. Apakah anda ingin melihat perubahannya?', $this->session->userdata('language'));

            $id = $this->input->post('id');
            $modified_date = $this->input->post('modified_date');

            $users = $this->user_level_m->get($id);

            if($users->modified_date == $modified_date)
            {
                $response->success = true;
                $response->msg = '';
            }

            echo json_encode($response);

        }
    }

    public function get_menu()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;

            $cabang_id = $this->input->post('cabang_id');
            $parent_id = $this->input->post('parent_id');
            $level_id = $this->input->post('level_id');

            $cabang = $this->cabang_m->get($cabang_id);
            
            if($cabang->url != '')
            {
                $menus = get_parent_menu($cabang->url,$level_id);

                if (count($menus)){
                    $response->success = true;
                    $response->rows = $menus;
                }                
            }

            die(json_encode($response));
        }
    }

    public function save_menu_parent()
    {
        if($this->input->is_ajax_request())
        {
            $response = new StdClass;
            $response->success = false;
            $response->msg = translate('Terjadi kegagalan koneksi, Menu Utama tidak dapat disimpan.',$this->session->userdata('language'));

            $array_input = $this->input->post();

            $cabang = $this->cabang_m->get($array_input['cabang_id']);

            $param = array(
                'user_level_id' => $array_input['id'],
                'menu_parent'   => serialize($array_input['menu_parent']),
                'parent_id'     => 0
            );


            if(insert_user_level_menu($cabang->url,$param))
            {
                $response->success = true;
                $response->msg = translate('Menu Utama berhasil disimpan.',$this->session->userdata('language'));
            }

            die(json_encode($response));

        }
    }

    public function save_sub_menu()
    {
        if($this->input->is_ajax_request())
        {
            $response = new StdClass;
            $response->success = false;
            $response->msg = translate('Terjadi kegagalan koneksi, Sub Menu tidak dapat disimpan.',$this->session->userdata('language'));

            $array_input = $this->input->post();

            // die(dump($array_input));

            $cabang = $this->cabang_m->get($array_input['cabang_id']);

            $param = array(
                'user_level_id' => $array_input['id'],
                'menu_parent'   => serialize($array_input['sub_menu']),
                'parent_id'     => $array_input['parent_id']
            );


            if(insert_user_level_menu($cabang->url,$param))
            {
                $response->success = true;
                $response->msg = translate('Sub Menu berhasil disimpan.',$this->session->userdata('language'));
            }

            die(json_encode($response));

        }
    }

    public function edit_menu_parent()
    {
        if($this->input->is_ajax_request())
        {
            $response = new StdClass;
            $response->success = false;
            $response->msg = translate('Terjadi kegagalan koneksi, Menu Utama tidak dapat diubah.',$this->session->userdata('language'));

            $array_input = $this->input->post();

            // die(dump($array_input));

            $cabang = $this->cabang_m->get($array_input['cabang_id']);

            $param = array(
                'id'             => $array_input['id_menu'],
                'user_level_id'  => $array_input['user_level_id'],
                'parent_id_awal' => $array_input['parent_id_awal'],
                'parent_id'      => $array_input['parent_id'],
                'm_order'        => $array_input['m_order'],
            );


            if(edit_user_level_menu($cabang->url,$param))
            {
                $response->success = true;
                $response->msg = translate('Menu Utama berhasil diubah.',$this->session->userdata('language'));
            }

            die(json_encode($response));

        }
    }

    public function delete_menu()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;

            $cabang_id = $this->input->post('cabang_id');
            $id = $this->input->post('id');

            $cabang = $this->cabang_m->get($cabang_id);

            if($cabang->url != '')
            {
                $menus = delete_menu_parent($cabang->url,$id);

                if ($menus){
                    $response->success = true;
                }                
            }

            die(json_encode($response));
        }
    }

    public function listing_fitur()
    {        
        if($this->input->is_ajax_request())
        {
            $fitur_cabang_id = $this->input->post('fitur_cabang_id');
            $cabang_id = $this->input->post('cabang_id');
            $level_id = $this->input->post('level_id');

            $fitur_cabang = $this->cabang_m->get($fitur_cabang_id);
            $cabang = $this->cabang_m->get($cabang_id);

            $data = array(
                'id'            => '', 
                'nama'          => '', 
                'path'          => '', 
                'url_api'       => $cabang->url,
                'base_url'      => '',
                'user_level_id' => $level_id,
                'cabang_id'     => $fitur_cabang_id
            );

            $url = urlencode(base64_encode(serialize($data)));

            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_sub_menu" id="select" name="select" href="'.base_url().'master/user_level/sub_menu/'.$url.'" class="btn btn-primary"><i class="fa fa-check"></i></a>';


            $html = '<tr><td>Sub Menu Custom</td><td>-</td><td><div class="text-center inline-button-table">'.$action.'</div></td></tr>';

            if($fitur_cabang->url != '')
            {
                $fitur = get_fitur($fitur_cabang->url);

                if (count($fitur))
                {

                    foreach ($fitur as $fitur) 
                    {
                        $data = array(
                            'id'            => $fitur['id'], 
                            'nama'          => $fitur['nama'], 
                            'path'          => $fitur['path'], 
                            'url_api'       => $cabang->url,
                            'base_url'      => $fitur_cabang->url,
                            'user_level_id' => $level_id,
                            'cabang_id'     => $fitur_cabang_id
                        );

                        $url = urlencode(base64_encode(serialize($data)));

                        $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_sub_menu" id="select" name="select" href="'.base_url().'master/user_level/sub_menu/'.$url.'" class="btn btn-primary"><i class="fa fa-check"></i></a>';

                        $html .= '<tr><td>'.$fitur['nama'].'</td><td>'.$fitur['path'].'</td><td><div class="text-center">'.$action.'</div></td></tr>';
                    }
                } 
                else
                {
                    $html .= '<td colspan="3" style="text-align:center;"><span>'.translate('Tidak ada fitur di cabang yang dipilih', $this->session->userdata('language')).'</span></td>';               
                    
                }
            }
            else
            {
                $html .= '<td colspan="3" style="text-align:center;"><span>'.translate('Tidak ada fitur di cabang yang dipilih', $this->session->userdata('language')).'</span></td>';
            }

            echo $html;
        }
    }

    public function sub_menu($url)
    {
        $data = base64_decode(urldecode($url));
        $data = unserialize($data);

        $this->load->view('master/user_level/modal/modal_sub_menu', $data);
        
    }

    public function edit_sub_menu($url)
    {
        $data = base64_decode(urldecode($url));
        $data = unserialize($data);

        $this->load->view('master/user_level/modal/modal_edit_sub_menu', $data);
        
    }
    public function edit_sub_menu_parent($url)
    {
        $data = base64_decode(urldecode($url));
        $data = unserialize($data);

        $this->load->view('master/user_level/modal/modal_edit_sub_menu_parent', $data);
        
    }

    public function listing_menu($value='')
    {
        if($this->input->is_ajax_request())
        {
            $cabang_id = $this->input->post('cabang_id');
            $menu_parent_id = $this->input->post('menu_parent_id');
            $level_id = $this->input->post('level_id');

            $cabang = $this->cabang_m->get($cabang_id);

            $html = '';
            if($cabang->url != '')
            {
                $menu = get_user_level_menu_order($cabang->url,$level_id,$menu_parent_id);
                $menu = object_to_array($menu);

                if (count($menu))
                {
                    $i=0;
                    $count = count($menu);
                    foreach ($menu as $menu) 
                    {
                        $data = array(
                            'id'            => $menu['id'], 
                            'parent_id'     => $menu['parent_id'],
                            'user_level_id' => $menu['user_level_id'],
                            'nama'          => $menu['nama'], 
                            'base_url'      => $menu['base_url'],
                            'url'           => $menu['url'], 
                            'icon_class'    => $menu['icon_class'],
                            'unik_id'       => $menu['unik_id'],
                            'm_order'       => $menu['m_order'],
                            'url_api'       => $cabang->url,
                            'cabang_id'     => $cabang_id
                        );

                        $url = urlencode(base64_encode(serialize($data)));

                        $order = '';
                        if($i == 0) //first row
                            $order = '<a title="" data-action="move_order" data-id="'.$menu['id'].'" data-parent_id="'.$menu['parent_id'].'" data-command="down" data-order="'.$menu['m_order'].'" class="btn default"><i class="fa fa-caret-down"></i></a>';
                        elseif($i == $count-1)  //last row
                            $order = '<a data-action="move_order" data-id="'.$menu['id'].'" data-parent_id="'.$menu['parent_id'].'" data-command="up" data-order="'.$menu['m_order'].'" class="btn default"><i class="fa fa-caret-up"></i></a>';
                        else    //middle row
                            $order = '<a data-action="move_order" data-id="'.$menu['id'].'" data-parent_id="'.$menu['parent_id'].'" data-command="up" data-order="'.$menu['m_order'].'" class="btn default"><i class="fa fa-caret-up"></i></a><a data-action="move_order" data-id="'.$menu['id'].'" data-parent_id="'.$menu['parent_id'].'" data-command="down" data-order="'.$menu['m_order'].'" class="btn default"><i class="fa fa-caret-down"></i></a>';              

                        
                        $action = '<a title="'.translate('Pindah Menu Utama', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_edit_parent" name="edit[]" href="'.base_url().'master/user_level/edit_sub_menu_parent/'.$url.'" class="btn blue"><i class="fa fa-wrench"></i></a><a title="'.translate('Edit', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_edit_menu" name="edit[]" href="'.base_url().'master/user_level/edit_sub_menu/'.$url.'" class="btn blue-chambray"><i class="fa fa-edit"></i></a><a title="'.translate('Delete', $this->session->userdata('language')).'"  name="delete[]" class="btn red" data-id="'.$menu['id'].'" data-parent_id="'.$menu['parent_id'].'" data-confirm="'.translate('Anda yakin akan menghapus menu ini?',$this->session->userdata('language')).'"><i class="fa fa-times"></i></a>';

                        $html .= '<tr><td>'.$menu['nama'].'</td><td>'.$menu['base_url'].'</td><td>'.$menu['url'].'</td><td>'.$menu['icon_class'].'</td><td>'.$menu['unik_id'].'</td><td><div class="text-center inline-button-table">'.$order.'</div></td><td><div class="text-center inline-button-table">'.$action.'</div></td></tr>';

                        $i++;
                    }
                } 
                else
                {
                    $html = '<td colspan="7" style="text-align:center;"><span>'.translate('Tidak ada sub menu dalam menu utama yang dipilih', $this->session->userdata('language')).'</span></td>';               
                    
                }
            }
            else
            {
                $html = '<td colspan="7" style="text-align:center;"><span>'.translate('Tidak ada sub menu dalam menu utama yang dipilih', $this->session->userdata('language')).'</span></td>';
            }

            echo $html;
           
        }
    }

    public function change_order()
    {
       if(! $this->input->is_ajax_request()) redirect(base_url());

        $parent_id    = $this->input->post('parent_id');
        $cabang_id    = $this->input->post('cabang_id');
        $level_id    = $this->input->post('level_id');

        $id = $this->input->post('id');
        $order  = intval($this->input->post('order'));
        $command = $this->input->post('command');

        $cabang = $this->cabang_m->get($cabang_id);

        $data = array(
            'parent_id' => $parent_id,
            'level_id'  => $level_id,
            'id'        => $id,
            'order'     => $order,
        );

        $data = serialize($data);

        if($command == 'up')
        {
            if($cabang->url != "")
            {
                up_order($cabang->url,$data);
            }
        }             
        else
        {
            if($cabang->url != "")
            {
                down_order($cabang->url,$data);                
            }
        }
    }
}

/* End of file user_level.php */
/* Location: ./application/controllers/master/user_level.php */