<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biaya extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'c4804207e636d0f022652c802aedc6f4';                  // untuk check bit_access

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

        $this->load->model('master/m_biaya');
        $this->load->model('master/m_kategori_biaya');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/biaya/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Biaya', $this->session->userdata('language')), 
            'header'         => translate('Master Biaya', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/biaya/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function tambah_biaya()
    {

        $var_biaya = $this->m_biaya->get();
        $var_kategori_biaya = $this->m_kategori_biaya->get();
        $data = array('var_biaya' => $var_biaya,'var_kategori_biaya' => $var_kategori_biaya);
        $this->load->view('v_kategori_biaya',$data);
        
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/biaya/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $var_biaya = $this->m_biaya->get_data();
        $var_kategori_biaya = $this->m_kategori_biaya->get_data();

        $data = array(
            'title'          => config_item('site_name'). ' &gt;'.translate("Tambah Biaya", $this->session->userdata("language")), 
            'header'         => translate("Tambah Biaya", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/biaya/add',
            'var_biaya' => $var_biaya,
            'var_kategori_biaya' => $var_kategori_biaya,
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function edit($id)
    {

        $assets = array();
        $config = 'assets/master/biaya/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->m_biaya->get_by(array('id' => $id), true);

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("Edit Biaya", $this->session->userdata("language")), 
            'header'         => translate("Edit Biaya", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/biaya/edit',
            'form_data'      => object_to_array($form_data),
            'pk'             => $id,
            'flag'           => 'edit'                         //table primary key value
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
        $result = $this->m_biaya->get_datatable();
        // die($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i=0;

        $action = '';
        

        foreach($records->result_array() as $row)
        {    
         $tipe = "";
        if($row['tipe_kategori_biaya'] == "1"){
            $tipe = 'Kasbon';
        }elseif ($row['tipe_kategori_biaya'] == "2") {
            $tipe = 'Biasa';
        }
     
    
            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/biaya/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus biaya ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
               
                
           
            $output['data'][] = array(
                $row['id'],
                $row['nama_biaya'],
                $row['kategori_biaya'],
                $tipe,
                '<div class="text-left inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

        public function post_multiple_table() 
    { 
        // $this->load->model = ('M_pasien', 'model_pasien', TRUE);
        $biaya_input_data = array();
        $biaya_input_data = array();
        $biaya_input_data['kategori_biaya_id'] = $this->input->post('kategori_biaya_id');
        $biaya_input_data['nama'] = $this->input->post('nama');
        $biaya_input_data['parent_id'] = $this->input->post('parent_id');
        $biaya_input_data['is_parent'] = $this->input->post('is_parent');
        $biaya_input_data['is_active'] = $this->input->post('is_active');
        $biaya_input_data['created_by'] = $this->input->post('created_by');
        $biaya_input_data['created_date'] = $this->input->post('created_date');
        $biaya_input_data['modified_by'] = $this->input->post('modified_by');
        $biaya_input_data['modified_date'] = $this->input->post('modified_date');
        //pasien_tindakan_input_data
        $kategori_biaya_input_data['nama'] = $this->input->post('nama');
        $kategori_biaya_input_data['is_active'] = $this->input->post('is_active');
        $kategori_biaya_input_data['created_by'] = $this->input->post('created_by');
        $kategori_biaya_input_data['created_date'] = $this->input->post('created_date');
        $kategori_biaya_input_data['modified_by'] = $this->input->post('modified_by');
        $kategori_biaya_input_data['modified_date'] = $this->input->post('modified_date');
        $checking_insert = $this->m_biaya->create_multiple_table($biaya_input_data, $kategori_biaya_input_data);
        //die($this->db->last_query());
         if($checking_insert)
         {

            // if you want to set succes alert message on view
            redirect(base_url('master/biaya/index'));
         }
         else
         {
            // if you want to set succes alert message on view
            redirect(base_url('master/biaya/index'));
         }


    }

   
    public function save()
    {
         $command     = $this->input->post('command');
        $array_input = $this->input->post();
         //die_dump($array_input);

        if ($array_input['command'] === 'add')
        {  

            // $data = array(
            //     'nama'             => $array_input['nama'],
            //     'path'          => $array_input['path'],
 
            // );
            $last_id       = $this->m_biaya->get_max_id()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;

            
            $format_id     = 'COST-'.date('m').'-'.date('Y').'-%04d';
            $id_mesin         = sprintf($format_id, $last_id, 4);
//die_dump($id_mesin);
            $data['id'] = $id_mesin;
            $data['nama'] = $this->input->post('nama_biaya');
            $data['kategori_biaya_id'] = $this->input->post('kategori_biaya');
            $data['is_active'] = 1;
            $data['created_by'] = $this->session->userdata('user_id');
            $data['created_date'] = date('Y-m-d H:i:s');
    //die($this->db->last_query());       
            $biaya_id = $this->m_biaya->add_data($data);

            // $tindakan=$this->input->post('tindakan');
            // foreach ($tindakan as $row) 
            // {
            //     if($row['tindakan_id']!='')
            //     {
            //         $data1['poliklinik_id']=$poliklinik_id;
            //         $data1['tindakan_id']=$row['tindakan_id'];
            //         $data1['is_active'] = 1;
            //         $this->poliklinik_tindakan_m->save($data1);
            //     }
            //  }   
//die($this->db->last_query());
            if ($biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Biaya berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }


            
        }
        
        elseif ($array_input['command'] === 'edit')
        {
            $id = $this->input->post('id');

            $data = array(
                    'nama'              => $array_input['nama'],
                    'kategori_biaya_id' => $array_input['kategori_biaya'],
                    'modified_by'           => $this->session->userdata('user_id'),
                    'modified_date' => date('Y-m-d H:i:s'),
                   
            );  
            $save_biaya_id = $this->m_biaya->edit_data($data, $id);

            // $user_level_menu = $this->menu_user_m->get_data_is_active()->result_array();
            // die_dump($user_level_menu);
  //die($this->db->last_query());


            if ($save_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data biaya berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/biaya");
    }


    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0,
            'modified_by'       => $this->session->userdata('user_id'),
                'modified_date'     => date("d-m-Y h:i:s")
        );
        // save data
        $user_id = $this->m_biaya->edit_data($data, $id);



        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Biaya telah dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/biaya");
    }


    public function delete2($id){
        $hapus = $this->m_biaya->hapusData('biaya',$id);
        if($hapus > 0){

         $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data biaya Ini Telah Dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('master/biaya');
        }else{
            echo 'Gagal disimpan';
        }
    }
}

/* End of file mesin_edc.php */
/* Location: ./application/controllers/mesin_edc/mesin_edc.php */