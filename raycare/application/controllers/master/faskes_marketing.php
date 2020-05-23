<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faskes_marketing extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '96358a88a50a09f4e76b813fdc47649d';                  // untuk check bit_access

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

        $this->load->model('master/cabang_m');
        $this->load->model('master/master_faskes_m');
        $this->load->model('master/faskes_marketing_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/faskes_marketing/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Faskes Marketing', $this->session->userdata('language')), 
            'header'         => translate('Faskes Marketing', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/faskes_marketing/index',
            'flag'           => 2,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_faskes_marketing','add'))
        {
            $assets = array();
            $assets_config = 'assets/master/faskes_marketing/add';
            $this->config->load($assets_config, true);

            $assets = $this->config->item('assets', $assets_config);

            $data = array(
                'title'          => config_item('site_name').' &gt;'. translate("Tambah Faskes Marketing", $this->session->userdata("language")), 
                'header'         => translate("Tambah Faskes Marketing", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],                
                'content_view'   => 'master/faskes_marketing/add',
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
        if(restriction_function($this->session->userdata('level_id'), 'master_faskes_marketing','edit'))
        {
            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/master/faskes_marketing/edit';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
           
            // $form_data = $this->cabang_m->get($id);
            $form_data=$this->faskes_marketing_m->get($id);
          //  $form_data2=$this->poliklinik_tindakan_m->getdata($id)->result_array();

            $data = array(
                'title'          => config_item('site_name').' &gt;'. translate("Edit Penjamin", $this->session->userdata("language")), 
                'header'         => translate("Edit Penjamin", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/faskes_marketing/edit',
                'form_data'      => $form_data,
                'pk'             => $id,                    //table primary key value
                'flag'           => 2
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
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/poliklinik/tindakan';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        // $form_data = $this->cabang_m->get($id);
        $form_data=$this->poliklinik_m->get($id);
        $data = array(
            'title'          => config_item('site_name'). translate("Lihat Penjamin", $this->session->userdata("language")), 
            'header'         => translate("Lihat Penjamin", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            // 'menus'          => $this->menus,
            // 'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'flag'          =>2,
            'pk'             => $id,
            'content_view'   => 'master/poliklinik/tindakan',
            'form_data'      => $form_data,
            'pk_value'       => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
   

    public function listing()
    {        
        $result = $this->faskes_marketing_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $action = '';
            $status = '';
            
            $data_delete = '<a title="'.translate('Hapus', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus faskes untuk marketing ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['kode_faskes'].'" class="btn red delete"><i class="fa fa-times"></i> </a>';
            

            $action =  restriction_button($data_delete,$user_level_id,'master_faskes_marketing','delete');

            $output['data'][] = array(
                '<div class="text-left">'.$row['kode_faskes'].'</div>',
                '<div class="text-left">'.$row['nama_faskes'].'</div>',
                '<div class="text-left">'.$row['nama_reg'].'</div>',
                '<div class="text-left">'.$row['nama_marketing'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_faskes()
    {
        $result = $this->master_faskes_m->get_datatable_search();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        foreach($records->result_array() as $row)
        {             
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-marketing="'.htmlentities(json_encode(object_to_array($marketing))).'" class="btn btn-primary select_faskes"><i class="fa fa-check"></i> </a>';
            
            $output['data'][] = array(
                '<div class="text-left">'.$row['jenis'].'</div>' ,
                '<div class="text-left">'.$row['kode_faskes'].'</div>' ,
                '<div class="text-left">'.$row['nama_faskes'].'</div>',
                '<div class="text-left">'.$row['alamat'].'</div>' ,
                '<div class="text-left">'.$row['nama_reg'].'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
        }

        echo json_encode($output);
    }

    public function get_faskes()
    {
        if($this->input->is_ajax_request()){
            $marketing_id = $this->input->post('marketing_id');

            $response = new stdClass;
            $response->success = false;

            $data = $this->faskes_marketing_m->get_faskes_marketing($marketing_id)->result_array();

            if(count($data) != 0){
                $response->success = true;
                $response->rows = $data;
            }

            die(json_encode($response));
        }
    }

    public function delete_faskes_marketing()
    {
        if($this->input->is_ajax_request()){
            $kode_faskes = $this->input->post('kode_faskes');

            $data_cabang = $this->cabang_m->get_by("tipe in (1, 0) and is_active = 1 and url != ''");
            $data_cabang = object_to_array($data_cabang);
            
            $response = new stdClass;
            $response->success = false;
            
            $path_model = 'master/faskes_marketing_m';
            $wheres['kode_faskes'] = $kode_faskes;

            foreach ($data_cabang as $cabang) {
                $faskes_marketing = delete_data_api($wheres,$cabang['url'],$path_model);
            }

            // $delete = $this->faskes_marketing_m->delete_by($wheres);

            $response->success = true;
            
        
            die(json_encode($response));
        }
    }

    public function save_faskes_marketing()
    {
        if($this->input->is_ajax_request()){
            $faskes_id = $this->input->post('faskes_id');
            $kode_faskes = $this->input->post('kode_faskes');
            $marketing_id = $this->input->post('marketing_id');

            $data_cabang = $this->cabang_m->get_by("tipe in (1, 0) and is_active = 1 and url != ''");
            $data_cabang = object_to_array($data_cabang);

            $response = new stdClass;
            $response->success = false;
            
            $path_model = 'master/faskes_marketing_m';

            $data = array(
                'cabang_id'    => $this->session->userdata('cabang_id'),
                'faskes_id'    => $faskes_id,
                'kode_faskes'  => $kode_faskes,
                'marketing_id' => $marketing_id,
                'is_active'    => 1
            );

            foreach ($data_cabang as $cabang) {
                $faskes_marketing = insert_data_api($data,$cabang['url'],$path_model);
            }

            $response->success = true;
            $response->msg = translate('Faskes berhasil ditambahkan ke data marketing', $this->session->userdata('language'));
            
        
            die(json_encode($response));
        }
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */