<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventaris extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '6e5a3344578907e2a790c6fa15adb754';                  // untuk check bit_access

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

        $this->load->model('inventaris/inventaris_m');
        $this->load->model('master/divisi_m');
        $this->load->model('master/bank_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/inventaris/inventaris/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Daftar Inventaris', $this->session->userdata('language')), 
            'header'         => translate('Daftar Inventaris', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'inventaris/inventaris/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/inventaris/inventaris/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). ' | '.translate("Tambah Inventaris", $this->session->userdata("language")), 
            'header'         => translate("Tambah Inventaris", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'inventaris/inventaris/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {

        $assets = array();
        $config = 'assets/inventaris/inventaris/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->inventaris_m->get_by(array("id"=>$id),true);
        if(count($form_data) == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data inventaris tidak ditemukan.", $this->session->userdata("language")),
                "msgTitle" => translate("Info", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('inventaris/inventaris');
        }

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Inventaris", $this->session->userdata("language")), 
            'header'         => translate("Edit Inventaris", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'inventaris/inventaris/edit',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id,
            'flag'           => 'edit'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {

        $assets = array();
        $config = 'assets/inventaris/inventaris/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->inventaris_m->get_by(array("id"=>$id),true);
        if(count($form_data) == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data inventaris tidak ditemukan.", $this->session->userdata("language")),
                "msgTitle" => translate("Info", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('inventaris/inventaris');
        }


        $data = array(
            'title'          => config_item('site_name').' | '. translate("View Inventaris", $this->session->userdata("language")), 
            'header'         => translate("View Inventaris", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'inventaris/inventaris/view',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id,
            'flag'           => 'view'                         //table primary key value
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
        $result = $this->inventaris_m->get_datatable();

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
    
            $action = '<a title="'.translate('view', $this->session->userdata('language')).'" href="'.base_url().'inventaris/inventaris/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>
                       <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'inventaris/inventaris/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data inventaris ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
               
                
           
            $output['data'][] = array(
                $row['id'],
                $row['no_inventaris'],
                $row['merk'],
                $row['tipe'],
                $row['serial_number'],
                date('d M Y',strtotime($row['tanggal_pembelian'])),
                $row['garansi'],
                date('d M Y',strtotime($row['jadwal_maintenance'])),
                $row['nama_pengguna'],
                $row['nama_penanggung_jawab'],
                
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
            $user_level = $this->user_level_m->get($this->session->userdata('level_id'));

            $divisi = $this->divisi_m->get_by(array('id' => $user_level->divisi_id), true);

            $last_id       = $this->inventaris_m->get_max_id()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'AST-'.date('m').'-'.date('y').'-%04d';
            $id_inventaris         = sprintf($format_id, $last_id, 4);

            $last_number   = $this->inventaris_m->get_no_inventaris()->result_array();
            $last_number   = intval($last_number[0]['max_no_inventaris'])+1;

            $format_no        = '#AST#%03d/RHS-'.$divisi->kode.'/'.romanic_number(date('m'), true).'/'.date('Y');
            $no_inventaris     = sprintf($format_no, $last_number, 3);


            if($array_input['url'] != ''){

                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/inventaris/inventaris/images/'.$id_inventaris;
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $array_input['url'];
                $real_file = $id_inventaris.'/'.$new_filename;

                copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_inventaris').$real_file);
            }


            $data = array(
                'id'               => $id_inventaris,
                'no_inventaris'               => $no_inventaris,
                'merk'             => $array_input['merk'],
                'tipe'             => $array_input['tipe'],
                'url_foto'             => $array_input['url'],
                'serial_number'             => $array_input['serial_number'],
                'tanggal_pembelian'             => date('Y-m-d',strtotime($array_input['tanggal_pembelian'])),
                'pembeli'             => $array_input['pembeli_id'],
                'garansi'             => $array_input['garansi'],
                'ip_address'             => $array_input['ip_address'],
                'tanggal_serah_terima'             => date('Y-m-d',strtotime($array_input['tanggal_serah_terima'])),
                'tanggal_kembali'             => date('Y-m-d',strtotime($array_input['tanggal_kembali'])),
                'pengguna'             => $array_input['user_id'],
                'jadwal_maintenance'             => date('Y-m-d',strtotime($array_input['jadwal_maintenance'])),
                'yang_menyerahkan'       => $this->session->userdata('user_id'),
                'keterangan'             => $array_input['keterangan'],
                'is_active'        => '1',
                'divisi_id'        => $user_level->divisi_id,
                'created_by'       => $this->session->userdata('user_id'),
                'created_date'     => date('Y-m-d H:i:s')
            );
            $save_inventaris_id = $this->inventaris_m->add_data($data);
            // die_dump($this->db->last_query());
            if ($save_inventaris_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data inventaris berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }
        
        elseif ($array_input['command'] === 'edit')
        {
            $id_inventaris = $array_input['id'];

            if($array_input['url'] != ''){

                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/inventaris/inventaris/images/'.$id_inventaris;
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $array_input['url'];
                $real_file = $id_inventaris.'/'.$new_filename;

                copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_inventaris').$real_file);
            }


            $user = $this->user_m->get($array_input['created_by']);
            $user_level = $this->user_level_m->get($user->user_level_id);


            $data = array(
                'merk'                  => $array_input['merk'],
                'tipe'                  => $array_input['tipe'],
                'url_foto'              => $array_input['url'],
                'serial_number'         => $array_input['serial_number'],
                'tanggal_pembelian'     => date('Y-m-d',strtotime($array_input['tanggal_pembelian'])),
                'pembeli'               => $array_input['pembeli_id'],
                'garansi'               => $array_input['garansi'],
                'ip_address'            => $array_input['ip_address'],
                'tanggal_serah_terima'  => date('Y-m-d',strtotime($array_input['tanggal_serah_terima'])),
                'tanggal_kembali'       => date('Y-m-d',strtotime($array_input['tanggal_kembali'])),
                'pengguna'              => $array_input['user_id'],
                'jadwal_maintenance'    => date('Y-m-d',strtotime($array_input['jadwal_maintenance'])),
                'keterangan'            => $array_input['keterangan'],
                'is_active'             => '1',
                'divisi_id'             => $user_level->divisi_id,
                'modified_by'            => $this->session->userdata('user_id'),
                'modified_date'          => date('Y-m-d H:i:s')
            );
            $save_inventaris_id = $this->inventaris_m->edit_data($data,$id_inventaris);

            if ($save_inventaris_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data inventaris berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("inventaris/inventaris");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0,
            'modified_by'       => $this->session->userdata('user_id'),
                'modified_date'     => date('Y-m-d')
        );
        // save data
        $user_id = $this->inventaris_m->edit_data($data, $id);

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
                "msg"      => translate("Inventaris telah dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("inventaris/inventaris");
    }
}

/* End of file inventaris.php */
/* Location: ./application/controllers/inventaris/inventaris.php */