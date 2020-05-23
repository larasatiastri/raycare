<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabang_ extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 3;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(2, 3);       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
            // $this->menus = $this->menu_m->get_nested($user_level);
        }

        $this->load->model('master/cabang__m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/cabang_/index';
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
            'content_view'   => 'master/cabang_/index',
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
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/cabang_/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/cabang_/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
       
        $form_data = $this->cabang__m->get($id);

        $data = array(
            'title'          => config_item('site_name'). translate("Edit Cabang", $this->session->userdata("language")), 
            'header'         => translate("Edit Cabang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/cabang_/edit',
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
        $result = $this->cabang__m->get_datatable();

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
            
            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/cabang_/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
            <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data cabang ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
           

            $output['data'][] = array(
                $row['id'],
                $row['kode'],
                $row['nama'],
                $row['alamat'],
                $row['nomor_telepon1'],
                $row['nomor_telepon2'],
                $row['nomor_fax'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $command = $this->input->post('command');

        if ($command === 'add')
        {  
            $data = $this->cabang__m->array_from_post( $this->cabang__m->fillable_add());
            $data['is_active'] = 1;
            $cabang_id = $this->cabang__m->save($data);
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
           
            $data = $this->cabang__m->array_from_post( $this->cabang__m->fillable_edit());
            // save data
            $cabang_id = $this->cabang__m->save($data, $id);
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

        redirect("master/cabang_");
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
            'tipe'  => 1,
            'data_id'    => $id,
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


}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */