<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '1fe681464f052ea5c4ed1581edce9865';                  // untuk check bit_access

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

        $this->load->model('master/user_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/user_level_m');
        $this->load->model('master/user_level_menu_m');
        $this->load->model('master/user_level_persetujuan_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/user/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master User', $this->session->userdata('language')), 
            'header'         => translate('Master User', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/user/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/user/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah User", $this->session->userdata("language")), 
            'header'         => translate("Tambah User", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/user/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $this->load->model('master/user_level_m');
        $this->load->model('master/cabang_m');

        $assets = array();
        $config = 'assets/master/user/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $this->user_m->set_columns($this->user_m->fillable_edit());

        $flash_form_data = $this->session->flashdata('form_data');
        $flash_form_error = $this->session->flashdata('form_error');

        $form_data = ($flash_form_data === false)? $this->user_m->get($id): $flash_form_data;
            
        // die(dump($form_data));

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Edit User", $this->session->userdata("language")), 
            'header'         => translate("Edit User", $this->session->userdata("language")),
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            // 'menu_id'        => $this->menu_id,
            // 'menu_parent_id' => $this->menu_parent_id,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/user/edit',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id                         //table primary key value
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
        $result = $this->user_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;

        $action = '';
        

        foreach($records->result_array() as $row)
        {
            
            $action_passreset = '<a class="btn btn-xs btn-primary" data-action="passreset" data-user_id="' . $row['id'] . '" title="'.translate("Password Reset", $this->session->userdata("language")).'">' . translate("Password Reset", $this->session->userdata("language")) . '</a>';
            $action_edit      = '<a class="btn btn-xs blue-chambray" title="'.translate("Edit", $this->session->userdata("language")).'" href="'.base_url().'master/user/edit/'.$row['id'].'" ><i class="fa fa-edit"></i></a>';

            $url = array();
            if($row['url'] != '')
            {
                $url = explode('/', $row['url']); 
                // die(dump(file_exists(FCPATH.config_item('site_user_img_dir').$row['username'].'/small/'.$url[1])));
                if (file_exists(FCPATH.config_item('site_user_img_dir').$row['username'].'/small/'.$url[1]) && is_file(FCPATH.config_item('site_user_img_dir').$row['username'].'/small/'.$url[1])) 
                {
                    $image_user = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">';
                }
                else
                {
                    $image_user = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').'global/small/global.png">';
                }
            }
            else
            {
                $image_user = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').'global/small/global.png">';
            }


            $output['data'][] = array(
                $row['id'],
                $image_user.'&nbsp;'.$row['nama_lengkap'],
                $row['username'],
                $row['nama_level'],
                $row['nama_cabang'],
                $row['active'], 
                '<div class="text-center inline-button-table">'. $action_passreset . $action_edit . '</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $command = $this->input->post('command');
        $data_cabang = $this->cabang_m->get();
        if ($command === 'add')
        {
            // validasi form
            $rules = $this->user_m->rules;
            $this->form_validation->set_rules($rules);
            $this->form_validation->set_error_delimiters('', '');
            // process form
            if ($this->form_validation->run() == TRUE) 
            {
                $data = $this->user_m->array_from_post( $this->user_m->fillable_add() );
                $data['password']   = $this->user_m->hash($data['password']);
                $data['is_active']   = 1;

                $data_path = array(
                    'username'            => $data['username'],
                    'path_dokumen'        => './assets/mb/pages/master/user/images/'.$data['username'],
                    'path_dokumen_thumb'  => './assets/mb/pages/master/user/images/'.$data['username'].'/medium',
                    'path_dokumen_thumb2' => './assets/mb/pages/master/user/images/'.$data['username'].'/small',
                    'path_temporary'      => './assets/mb/var/temp',
                    'temp_filename'       => $data['url'],
                    'path_temp'           => base_url().config_item('user_img_temp_dir'),
                    'path_temp_thumbs'    => base_url().config_item('user_img_temp_thumb_dir'),
                    'path_temp_thumbs2'   => base_url().config_item('user_img_temp_thumb_small_dir'),
                );

                $data_path_sign = array(
                    'username'            => $data['username'],
                    'path_dokumen'        => './assets/mb/pages/master/user/images/'.$data['username'],
                    'path_temporary'      => './assets/mb/var/temp',
                    'temp_filename'       => $data['url_sign'],
                    'path_temp'           => base_url().config_item('user_img_temp_dir')
                );

                $data_api = serialize($data_path);
                $data_api_sign = serialize($data_path_sign);

                $url = base_url();
                if($data['url'] == '')
                {
                    $data['url'] = 'global/global.png';
                }
                else
                {
                    $data['url'] = move_user_photo($url,$data_api);
                }

                if($data['url_sign'] == '')
                {
                    $data['url_sign'] = 'global/global.png';
                }
                else
                {
                    $data['url_sign'] = move_user_sign_photo($url,$data_api_sign);
                }

                $user_id = insert_user_api($data,$url);

                $inserted_id = $user_id;

                //mulai insert ke tabel user cabang lain
                //ini dilakukan jika data yang diinsert harus diinsert ke tabel di cabang lain
                foreach ($data_cabang as $row_cabang) 
                {
                    if($row_cabang->url != '')
                    {
                        $url = $row_cabang->url;
                        move_user_photo($url,$data_api);
                        move_user_sign_photo($url,$data_api_sign);
                        $user_id = insert_user_api($data,$url,$inserted_id);                        
                    }
                }
                //akhir insert ke tabel user cabang lain
                if ($user_id) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("User Berhasil Ditambahkan", $this->session->userdata("language")),
                        "msgTitle" => translate("Success", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }
            }
            else
            {
                // collect form error dan form data dari form_validation
                $flashdata = $this->form_validation->get_flashdata();
                $this->session->set_flashdata($flashdata);

                redirect('master/user/add');
            }
        }
        elseif ($command === 'edit')
        {
            $id = $this->input->post('id');
            $data_cabang = $this->cabang_m->get();
            $array_input = $this->input->post();
            // validasi form
            $rules = $this->user_m->rules;
            unset($rules['password'], $rules['confirm-password']);

            $this->form_validation->set_rules($rules);
            $this->form_validation->set_error_delimiters('', '');
            
            // process form
            if ($this->form_validation->run() == TRUE) 
            {
                //$data = $this->user_m->array_from_post( $this->user_m->fillable_edit() );

                $data_path = array(
                    'username'            => $array_input['username'],
                    'path_dokumen'        => './assets/mb/pages/master/user/images/'.$array_input['username'],
                    'path_dokumen_thumb'  => './assets/mb/pages/master/user/images/'.$array_input['username'].'/medium',
                    'path_dokumen_thumb2' => './assets/mb/pages/master/user/images/'.$array_input['username'].'/small',
                    'path_temporary'      => './assets/mb/var/temp',
                    'temp_filename'       => $array_input['url'],
                    'path_temp'           => base_url().config_item('user_img_temp_dir'),
                    'path_temp_thumbs'    => base_url().config_item('user_img_temp_thumb_dir'),
                    'path_temp_thumbs2'   => base_url().config_item('user_img_temp_thumb_small_dir'),
                );

                $data_path_sign = array(
                    'username'            => $array_input['username'],
                    'path_dokumen'        => './assets/mb/pages/master/user/images/'.$array_input['username'],
                    'path_temporary'      => './assets/mb/var/temp',
                    'temp_filename'       => $array_input['url_sign'],
                    'path_temp'           => base_url().config_item('user_img_temp_dir')
                );

                $data_api = serialize($data_path);
                $data_api_sign = serialize($data_path_sign);

                $data = array(
                    'user_level_id' => $array_input['user_level_id'],
                    'username'      => $array_input['username'],
                    'inisial'      => $array_input['inisial'],
                    'nama'          => $array_input['nama'],
                    'cabang_id'     => $array_input['cabang_id'],
                    'bahasa'        => $array_input['bahasa']
                );

                $url = base_url();
                if($array_input['url'] == '')
                {
                    $data['url'] = 'global/global.png';
                }
                else
                {
                    $data['url'] = move_user_photo($url,$data_api);
                }


                if($array_input['url_sign'] == '')
                {
                    $data['url_sign'] = 'global/global.png';
                }
                else
                {
                    $data['url_sign'] = move_user_sign_photo($url,$data_api_sign);
                }

                $path_model = 'master/user_m';
                $user_id = insert_data_api($data,$url,$path_model,$id);
                $user_id = $user_id;
          
                foreach ($data_cabang as $row_cabang) 
                {
                    if($row_cabang->url != '')
                    {
                        $url = $row_cabang->url;
                        move_user_photo($url,$data_api);
                        move_user_sign_photo($url,$data_api_sign);
                        $path_model = 'master/user_m';
                        $user_id = insert_data_api($data,$url,$path_model,$id);
                    }
                }
                //$user_id = $this->user_m->save($data, $id);
                $user_id = str_replace('"', '', $user_id);
                if ($user_id) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("User Edited", $this->session->userdata("language")),
                        "msgTitle" => translate("Success", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }
            }
            else
            {
                // collect form error dan form data dari form_validation
                $flashdata = $this->form_validation->get_flashdata();
                $this->session->set_flashdata($flashdata);

                redirect('master/user/edit/' . $id );
            }
        }
        
        redirect("master/user");
    }

    /**
     * setStatus - Change User Active Status
     */
    public function setStatus()
    {
        if( !$this->input->is_ajax_request()) redirect(base_url());

        $data_cabang = $this->cabang_m->get();
        $user_id = intval($this->input->post('id'));
        $active  = intval($this->input->post('status'));
        $msg     = ($active == 1) ? 
                   translate("This user has been enabled", $this->session->userdata("language")) : 
                   translate("This user has been disabled", $this->session->userdata("language"));

        $data = array('is_active' => $active);

        $url = base_url();
        $user_id = edit_status_api($data,$url,$user_id);

        foreach ($data_cabang as $row_cabang) 
        {
            if($row_cabang->url != '')
            {
                $url = $row_cabang->url;
                $user_id = edit_status_api($data,$url,$user_id);
            }
        }

        // $this->user_m->save($data, $user_id);

        echo $msg;
    }   

    /**
     * [passReset description]
     * @return [type] [description]
     */
    public function passReset()
    {
        if( !$this->input->is_ajax_request()) redirect(base_url());

        $data_cabang = $this->cabang_m->get();
        $user_id      = intval($this->input->post('id'));
        $new_password = config_item('new_password_reset');

        $data = array('password' => $this->user_m->hash($new_password));

        $url = base_url();
        $user_edit_id = edit_password_api($data,$url,$user_id);

        foreach ($data_cabang as $row_cabang) 
        {
            if($row_cabang->url != '')
            {
                $url = $row_cabang->url;
                $user_edit_id = edit_password_api($data,$url,$user_id);
            }
        }

        // $this->user_m->save($data, $user_id);
        
        // get username
        $this->user_m->set_columns(array('username'));
        $user = $this->user_m->get($user_id);

        echo 'The new password for username <strong>' . $user->username . '</strong> is <strong>' . $new_password . 
            '</strong>.<br>Please change this password afterwards.';
    }

    /**
     * _unique_username - check username sudah terdaftar apa belum
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    public function _unique_username ($str)
    {
        $id = $this->input->post('id');

        $where =  array(
            'username' => $this->input->post('username')
        );
        // on edit
        // jangan validasi username terhadap current user username
        !$id || $where['id !='] = $id;

        $this->user_m->set_columns(array('username'));
        $user = $this->user_m->get_by($where);

        if (count($user)) 
        {
            $this->form_validation->set_message('_unique_username', '%s should be unique');
            return FALSE;
        }
        
        return TRUE;
    }

        /**
     * _unique_email - check email sudah terdaftar apa belum
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    public function _unique_email ($str)
    {
        $id = $this->input->post('id');

        $where =  array(
            'email' => $this->input->post('email')
        );
        // on edit
        // jangan validasi username terhadap current user username
        !$id || $where['id !='] = $id;

        $this->user_m->set_columns(array('email'));
        $user = $this->user_m->get_by($where);

        if (count($user)) 
        {
            $this->form_validation->set_message('_unique_email', '%s should be unique');
            return FALSE;
        }
        
        return TRUE;
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

            $users = $this->user_m->get($id);

            if($users->modified_date == $modified_date)
            {
                $response->success = true;
                $response->msg = '';
            }

            echo json_encode($response);

        }
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */