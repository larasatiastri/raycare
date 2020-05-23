<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pabrik extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '60bb6e70808d0dbd1a330b016332b724';                  // untuk check bit_access

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


        $this->load->model('master/pabrik_m');
        $this->load->model('master/pabrik_contact_person_m');
        $this->load->model('master/pabrik_alamat_m');
        $this->load->model('master/pabrik_telepon_m');

        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/region_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/penyakit_m');
        $this->load->model('master/penjamin_m');
        $this->load->model('master/faskes_m');
        $this->load->model('master/pasien_penjamin_m');
        // $this->load->model('master/pasien_syarat_penjamin_m');
        // $this->load->model('master/pasien_syarat_penjamin_detail_m');
        // $this->load->model('master/pasien_isi_syarat_penjamin_m');
        // $this->load->model('master/penjamin_syarat_m');
        $this->load->model('master/penjamin_kelompok_m');
       
    }
    
    public function index()
    {
        if(restriction_function($this->session->userdata('level_id'), 'pabrik','add'))
        {
        
        $assets = array();
        $config = 'assets/master/pabrik/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master Pabrik', $this->session->userdata('language')), 
            'header'         => translate('Master Pabrik', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pabrik/index',
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

    public function add()
    {
        if(restriction_function($this->session->userdata('level_id'), 'pabrik','add'))
        {
            $assets = array();
            $assets_config = 'assets/master/pabrik/add';
            $this->config->load($assets_config, true);

            $assets = $this->config->item('assets', $assets_config);

            $data = array(
                'title'          => config_item('site_name'). ' | '.translate("Tambah Pabrik", $this->session->userdata("language")), 
                'header'         => translate("Tambah Pabrik", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/pabrik/add',
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
        if(restriction_function($this->session->userdata('level_id'), 'pabrik','edit'))
        {
            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/master/pabrik/edit';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
            //die_dump($this->pabrik_m->get_data($id));
            $form_data = $this->pabrik_m->get($id);
            // die_dump($form_data);

            $data = array(
                'title'                 => config_item('site_name').' | '. translate("Edit Data Pabrik", $this->session->userdata("language")), 
                'header'                => translate("Edit Data Pabrik", $this->session->userdata("language")), 
                'header_info'           => config_item('site_name'), 
                'breadcrumb'            => TRUE,
                'menus'                 => $this->menus,
                'menu_tree'             => $this->menu_tree,
                'css_files'             => $assets['css'],
                'js_files'              => $assets['js'],
                'content_view'          => 'master/pabrik/edit',
                'form_data'             => object_to_array($form_data),
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

    public function view($id)
    {

        if(restriction_function($this->session->userdata('level_id'), 'pabrik','view'))
        {

            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/master/pabrik/view';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
            //die_dump($this->user_level_m->get_data($id));
            $form_data = $this->pabrik_m->get($id);
            // die_dump($form_data);
            

            $data = array(
                'title'          => config_item('site_name'). ' | ' .translate("View Data Pabrik", $this->session->userdata("language")), 
                'header'         => translate("View Data Pabrik", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/pabrik/view',
                'form_data'      => object_to_array($form_data),
                'pk_value'       => $id,
                'flag'           => 'view'                         //table primary key value
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
    public function listing()
    {        
        $result = $this->pabrik_m->get_datatable();
        // die_dump($result);


        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $info = '';
        $user_level_id = $this->session->userdata('level_id');

        foreach($records->result_array() as $row)
        {
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info[]" class="btn btn-primary pilih-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';
                
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/pabrik/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
                $data_view = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/pabrik/view/'.$row['id'].'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_view,$user_level_id,'pabrik','view').restriction_button($data_edit,$user_level_id,'pabrik','edit').restriction_button($data_delete,$user_level_id,'pabrik','delete');



                $contact_person         = $this->pabrik_contact_person_m->get_by(array('pabrik_id' => $row['id']));
                // $contact_person         = $this->pabrik_contact_person_m->get_datatable();
                // die_dump($this->db->last_query());
                // die_dump($contact_person);
                $contact_person         = object_to_array($contact_person);
                // die_dump($cp);
                // die_dump(count($contact_person));
                
                $nama_cp = '';

                $countCP = count($contact_person);
                $iCP = 1;
                foreach ($contact_person as $cp) 
                {
                
                    if ($iCP < $countCP)
                    {
                        $nama_cp .= $cp['nama'] . ', ';
                        
                    } elseif ($iCP == $countCP) {
                       
                        $nama_cp .= $cp['nama'].'.';

                    }

                    $iCP++;
                }

                // die_dump($nama_cp);
                
                $notes = $row['alamat_pabrik'];

                if ($notes == "")
                {
                    $notes = "";
                
                    $words = explode(' ', $notes);

                    $impWords = implode(' ', array_splice($words, 0, 4));

                    $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';

                }
                else
                {
                    $notes = $row['alamat_pabrik'].' RT/RW '.$row['rt_rw']. ' Kode Pos '.$row['kode_pos'];

                    $words = explode(' ', $notes);

                    $impWords = implode(' ', array_splice($words, 0, 4));

                    $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
                }
                

                
            $output['data'][] = array(
                $row['id'],
                $row['kode'],
                $row['nama_pabrik'],
                '<div class="row" style="margin-left : 0px !important; margin-right : 0px !important">
                                                <div class="col-xs-8" style="text-align:left; padding-left : 0px !important; padding-right : 0px !important; ">
                                                    <input type="text" value="'.$nama_cp.'" id="cp_'.$row['id'].'" readonly style="background-color: transparent;border: 0px solid;">
                                                </div>
                                                <div class="col-xs-4" style="text-align:right; padding-left : 0px !important; padding-right : 0px !important;">
                                                    <span class="input-group-button">'.$info.'</span>
                                                </div>',
                $preNotes,
                $row['nomor_tlp_pabrik'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item($id=null)
    {
        
        $result = $this->pabrik_m->get_datatable_cp($id);
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
                '<div class="text-left">'.$row['nama_contact_person'].'</div>',
                '<div class="text-left">'.$row['nomor_contact_person'].'</div>',
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
                $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select-p-item" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></a>';
            }
            else
            {
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_edit,$user_level_id,'user_level','edit').restriction_button($data_delete,$user_level_id,'user_level','delete');
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
                $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select-p-supplier" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></a>';
            }
            else
            {
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_edit,$user_level_id,'user_level','edit').restriction_button($data_delete,$user_level_id,'user_level','delete');
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
                $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select-p-customer" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></a>';
            }
            else
            {
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/user_level/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data user level ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';

                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_edit,$user_level_id,'user_level','edit').restriction_button($data_delete,$user_level_id,'user_level','delete');
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

    public function save()
    {
        $array_input = $this->input->post();
        // die_dump($array_input);

        if ($array_input['command'] === 'add')
        {  

            if ($array_input['kode'] != "")
            {
                $data = array(

                    'kode'      => $array_input['kode'],
                    'nama'      => $array_input['nama_lengkap'],
                    'is_active' => 1,

                );

                $save_pabrik = $this->pabrik_m->save($data);
            }

            foreach ($array_input['cp'] as $contact_person) 
            {

                if($contact_person['nama_cp'] != "")
                {

                    $data_cp = array(

                        'pabrik_id'     => $save_pabrik,
                        'nama'          => $contact_person['nama_cp'],
                        'nomor'         => $contact_person['number'],
                        'is_active'     => 1,

                    );

                    $save_contact_person = $this->pabrik_contact_person_m->save($data_cp);
                }

            }

            foreach ($array_input['alamat'] as $alamat) 
            {

                if($alamat['subjek'] != "")
                {

                    $data_alamat = array(

                        'pabrik_id'     => $save_pabrik,
                        'subjek_id'     => $alamat['subjek'],
                        'alamat'        => $alamat['alamat'],
                        'rt_rw'         => $alamat['rt'].'/'.$alamat['rw'],
                        'kode_pos'      => $alamat['kode_pos'],
                        'negara_id'     => $alamat['negara'],
                        'propinsi_id'   => $alamat['provinsi'],
                        'kota_id'       => $alamat['kota'],
                        'kecamatan_id'  => $alamat['kecamatan'],
                        'kelurahan_id'  => $alamat['kelurahan'],
                        'is_active'     => 1,
                    );

                    $save_pabrik_alamat = $this->pabrik_alamat_m->save($data_alamat);
                }

            }

            foreach ($array_input['phone'] as $phone) 
            {

                if($phone['subjek'] != "")
                {

                    $data_phone = array(

                        'pabrik_id'     => $save_pabrik,
                        'subjek_id'     => $phone['subjek'],
                        'nomor'         => $phone['number'],
                        'is_active'     => 1,

                    );


                    $save_pabrik_phone = $this->pabrik_telepon_m->save($data_phone);

                }

            }

            if ($save_pabrik && $save_pabrik_alamat && $save_contact_person && $save_pabrik_phone) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Pabrik berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

        }
        
        elseif ($array_input['command'] === 'edit')
        {

            // die_dump($array_input);

            $id_pabrik = $array_input['id'];

            $data_pabrik = array(

                'kode'      => $array_input['kode'],
                'nama'      => $array_input['nama_lengkap'],
                'is_active' => 1,

            );

            $save_pabrik = $this->pabrik_m->save($data_pabrik, $id_pabrik); 

            foreach ($array_input['cp'] as $contact_person) 
            {

                if($contact_person['id'] != "" && $contact_person['is_delete_cp'] == "")
                {

                    $update_cp = array(

                        'pabrik_id'     => $id_pabrik,
                        'nama'          => $contact_person['nama_cp'],
                        'nomor'         => $contact_person['number'],
                        'is_active'     => 1,
                    );

                    $update = $this->pabrik_contact_person_m->save($update_cp, $contact_person['id']);
                }

                if ($contact_person['id'] != "" && $contact_person['is_delete_cp'] == "1") 
                {                       
                    $delete = $this->pabrik_contact_person_m->delete($contact_person['id']); 
                }

                if ($contact_person['id'] == "" && $contact_person['is_delete_cp'] == "" && $contact_person['number'] != "") 
                {
                    $data_contact_person = array(
                        'pabrik_id'  => $id_pabrik,
                        'nama'       => $contact_person['nama_cp'],
                        'nomor'      => $contact_person['number'],
                        'is_active'  => '1',
                    );
                    
                    $save_contact_person = $this->pabrik_contact_person_m->save($data_contact_person); 
                }

            }

            foreach ($array_input['alamat'] as $alamat) 
            {

                if($alamat['id'] != "" && $alamat['is_delete_alamat'] == "")
                {

                    $update_alamat = array(

                        'pabrik_id'    => $id_pabrik,
                        'subjek_id'    => $alamat['subjek'],
                        'alamat'       => $alamat['alamat'],
                        'rt_rw'        => $alamat['rt'].'/'.$alamat['rw'],
                        'kode_pos'     => $alamat['kode_pos'],
                        'negara_id'    => $alamat['negara'],
                        'propinsi_id'  => $alamat['provinsi'],
                        'kota_id'      => $alamat['kota'],
                        'kecamatan_id' => $alamat['kecamatan'],
                        'kelurahan_id' => $alamat['kelurahan'],
                        'is_active'    => '1',
                    );

                    $update = $this->pabrik_alamat_m->save($update_alamat, $alamat['id']);
                }

                if($alamat['id'] != "" && $alamat['is_delete_alamat'] == "1")
                {

                    $delete = $this->pabrik_alamat_m->delete($alamat['id']);

                }

                if($alamat['id'] == "" && $alamat['is_delete_alamat'] == "" && $alamat['alamat'] != "")
                {

                    $data_alamat = array(

                        'pabrik_id'     => $id_pabrik,
                        'subjek_id'     => $alamat['subjek'],
                        'alamat'        => $alamat['alamat'],
                        'rt_rw'         => $alamat['rt'].'/'.$alamat['rw'],
                        'kode_pos'      => $alamat['kode_pos'],
                        'negara_id'     => $alamat['negara'],
                        'propinsi_id'   => $alamat['provinsi'],
                        'kota_id'       => $alamat['kota'],
                        'kecamat`an_id' => $alamat['kecamatan'],
                        'kelurahan_id'  => $alamat['kelurahan'],
                        'is_active'     => '1',

                    );

                    $save_alamat = $this->pabrik_alamat_m->save($data_alamat);

                }

            }

            foreach ($array_input['phone'] as $phone) 
            {

                if($phone['id'] != "" && $phone['is_delete_phone'] == "")
                {

                    $update_phone = array(

                        'pabrik_id' => $id_pabrik,
                        'subjek_id' => $phone['subjek'],
                        'nomor'     => $phone['number'],
                        'is_active' => 1,
                    );

                    $update = $this->pabrik_telepon_m->save($update_phone, $phone['id']);

                }

                if ($phone['id'] != "" && $phone['is_delete_phone'] == "1") 
                {                       
                    $delete = $this->pabrik_telepon_m->delete($phone['id']); 
                }

            }



            if ($update || $delete ||  $save_pabrik || $save_alamat || $save_contact_person) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Pabrik berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/pabrik");
    }

    public function delete($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'user_level','delete'))
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


    public function get_negara(){

        //$id_negara = $this->input->post('id_negara');
        //die_dump($id_negara);
        //$this->region_m->set_columns(array('id','nama'));
        $negara       = $this->region_m->get_by(array('parent' => Null));
        //die_dump($this->db->last_query());        
        $hasil_negara = object_to_array($negara);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_negara);
    }

    public function get_provinsi(){

        $id_negara      = $this->input->post('id_negara');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $provinsi       = $this->region_m->get_data_region($id_negara)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_provinsi = object_to_array($provinsi);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_provinsi);
    }

    public function get_kota(){

        $id_provinsi = $this->input->post('id_provinsi');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kota        = $this->region_m->get_data_region($id_provinsi)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kota  = object_to_array($kota);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kota);
    }

    public function get_kecamatan(){

        $id_kota         = $this->input->post('id_kota');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kecamatan       = $this->region_m->get_data_region($id_kota)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kecamatan = object_to_array($kecamatan);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kecamatan);
    }

    public function get_kelurahan(){

        $id_kecamatan    = $this->input->post('id_kecamatan');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kelurahan       = $this->region_m->get_data_region($id_kecamatan)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kelurahan = object_to_array($kelurahan);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kelurahan);
    }

    public function get_kode_cabang(){

        $id_negara    = $this->input->post('id_cabang');
        //die_dump($id_negara);
        $this->cabang_m->set_columns(array('id','kode'));
        $cabang       = $this->cabang_m->get_by(array('id' => $id_negara));
        //die_dump($this->db->last_query());        
        $hasil_cabang = object_to_array($cabang);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_cabang);
    }

    public function get_subjek(){

        $tipe              = $this->input->post('tipe');
        $data_subjek       = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek = object_to_array($data_subjek);

        echo json_encode($hasil_data_subjek);

    }

    public function save_subjek(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_subjek = array(
            "tipe" => $tipe,
            "nama" => $nama,
        );

        
        $save_data         = $this->subjek_m->save($data_subjek);
        
        $data_subjek       = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek = object_to_array($data_subjek);
        
        echo json_encode($hasil_data_subjek);

    }

    public function save_negara(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_negara = array(
            "tipe" => $tipe,
            "nama" => $nama,
        );

        $save_data         = $this->region_m->save($data_negara);
        
        $data_negara       = $this->region_m->get_by(array('parent' => null));
        $hasil_data_negara = object_to_array($data_negara);

        echo json_encode($hasil_data_negara);

    }

    public function save_region(){

        $parent = $this->input->post('parent');
        $tipe   = $this->input->post('tipe');
        $nama   = $this->input->post('nama');

        $data_region = array(
            "parent" => $parent,
            "tipe"   => $tipe,
            "nama"   => $nama,
        );

        
        $save_data         = $this->region_m->save($data_region);
        
        $data_region       = $this->region_m->get_by(array('parent' => $parent));
        $hasil_data_region = object_to_array($data_region);

        echo json_encode($hasil_data_region);

    }



}

/* End of file pabrik.php */
/* Location: ./application/controllers/pabrik/pabrik.php */