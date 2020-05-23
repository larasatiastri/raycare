<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pemeriksaan_lab extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'f881b082ec20538a151c6cb6dd77ed51';                  // untuk check bit_access

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

        $this->load->model('master/kategori_pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_m');
        $this->load->model('master/tindakan_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/pemeriksaan_lab/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master Pemeriksaan Lab', $this->session->userdata('language')), 
            'header'         => translate('Master Pemeriksaan Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pemeriksaan_lab/index',
            'flag'           => 2,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }


    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/pemeriksaan_lab/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Tambah Tindakan", $this->session->userdata("language")), 
            'header'         => translate("Tambah Tindakan", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            
            'content_view'   => 'master/pemeriksaan_lab/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        
        $assets = array();
        $config = 'assets/master/pemeriksaan_lab/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
               
        $form_data=$this->pemeriksaan_lab_m->get_by(array('id'=>$id,'is_active'=>1), true);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Tindakan", $this->session->userdata("language")), 
            'header'         => translate("Edit Tindakan", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pemeriksaan_lab/edit',
            'form_data'      => object_to_array($form_data),
            'pk_value'             => $id,                    //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function listing()
    {        
        $result = $this->pemeriksaan_lab_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die(dump($records));

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
                
            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/pemeriksaan_lab/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                        <a title="'.translate('Hapus', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data pemeriksaan lab ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
       
            $output['data'][] = array(
                '<div class="text-left">'.$row['tipe'].'/'.$row['nama_kategori'].'</div>',
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga']).'</div>',
                '<div class="text-left">'.$row['satuan'].'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }


    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        
        $hapus_pemeriksaan = $this->pemeriksaan_lab_m->edit_data($data,$id);

        $where_tindakan = array(
            'pemeriksaan_lab_id' => $id_pemeriksaan,
            'poliklinik_id' => 3
        );

        $edit_tindakan = $this->tindakan_m->update_by($this->session->userdata('user_id'),$data, $where_tindakan);
        

        $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Pemeriksaan Lab dihapus", $this->session->userdata("language")),
            "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
        
        redirect("master/pemeriksaan_lab");
    }

    

    public function save()
    {
        
        $array_input = $this->input->post();

        if($array_input['command'] === 'add'){

            $last_id_pemeriksaan   = $this->pemeriksaan_lab_m->get_max_id()->result_array();
            $last_id_pemeriksaan   = intval($last_id_pemeriksaan[0]['max_id'])+1;
            $format        = 'PL-'.date('mY').'-%04d';
            $id_pemeriksaan     = sprintf($format, $last_id_pemeriksaan, 4);

            $data = array(
                'id'    => $id_pemeriksaan,
                'kategori_pemeriksaan_id' => $array_input['kategori_id'],
                'kode' => $array_input['kode'],
                'nama' => $array_input['nama'],
                'satuan' => $array_input['satuan'],
                'harga' => $array_input['harga'],
                'is_active' => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_pemeriksaan = $this->pemeriksaan_lab_m->add_data($data);

            $data_tindakan = array(
                'poliklinik_id' => 3,
                'pemeriksaan_lab_id' => $id_pemeriksaan,
                'kode' => $array_input['kode'],
                'nama' => $array_input['nama'],
                'harga' => $array_input['harga'],
                'tanggal' => date('Y-m-d'),
                'keterangan' => $array_input['satuan'],
                'is_active' => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s')
            );
            $save_tindakan = $this->tindakan_m->add_data($data_tindakan);
        }

        if($array_input['command'] === 'edit'){

            
            $id_pemeriksaan     = $array_input['id'];

            $data = array(
                'kategori_pemeriksaan_id' => $array_input['kategori_id'],
                'kode' => $array_input['kode'],
                'nama' => $array_input['nama'],
                'satuan' => $array_input['satuan'],
                'harga' => $array_input['harga'],
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            $edit_pemeriksaan = $this->pemeriksaan_lab_m->edit_data($data, $id_pemeriksaan);

            $data_tindakan = array(
                'kode' => $array_input['kode'],
                'nama' => $array_input['nama'],
                'harga' => $array_input['harga'],
                'tanggal' => date('Y-m-d'),
                'keterangan' => $array_input['satuan'],
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            $where_tindakan = array(
                'pemeriksaan_lab_id' => $id_pemeriksaan,
                'poliklinik_id' => 3
            );

            $edit_tindakan = $this->tindakan_m->update_by($this->session->userdata('user_id'),$data_tindakan, $where_tindakan);
        }
       
        if ($edit_pemeriksaan) 
        {
            $flashdata = array(
                "success",
                translate("Pemeriksaan lab berhasil telah diubah", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language")),
            );
           $this->session->set_flashdata($flashdata);
        }
        redirect("master/pemeriksaan_lab");
    }

  
}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */