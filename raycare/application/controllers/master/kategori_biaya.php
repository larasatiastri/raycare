    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kategori_biaya extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '46b1596ab4c390fe1d5ad440939dc1a0';                  // untuk check bit_access

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

        $this->load->model('master/m_kategori_biaya');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/kategori_biaya/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Kategori Biaya', $this->session->userdata('language')), 
            'header'         => translate('Master Kategori Biaya', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_biaya/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/kategori_biaya/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). ' &gt;'.translate("Tambah Kategori Biaya", $this->session->userdata("language")), 
            'header'         => translate("Tambah Kategori Biaya", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_biaya/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function edit($id)
    {
         
        $assets = array();
        $config = 'assets/master/kategori_biaya/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        // $form_data = $this->cabang_m->get($id);
        $form_data=$this->m_kategori_biaya->get_by(array('id'=>$id));
        //die_dump($form_data);
      //  $form_data2=$this->poliklinik_tindakan_m->getdata($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Kategori Biaya", $this->session->userdata("language")), 
            'header'         => translate("Edit Kategori Biaya", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_biaya/edit',
            'form_data'      => $form_data,
           // 'form_data2'     => $form_data2,
            'pk'             => $id,                    //table primary key value
            'flag'           => 'edit'
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    public function listing()
    {        
        $result = $this->m_kategori_biaya->get_datatable();
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
        $tipe = "1";
        if($row['tipe'] == "1"){ 
            $tipe = 'Kasbon';
        }elseif ($row['tipe'] == "2"){ 
            $tipe = 'Biasa';
        }
       
    
            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/kategori_biaya/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus kategori biaya ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
               
                
           
            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                $tipe,
                '<div class="text-left inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
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
            $last_id       = $this->m_kategori_biaya->get_max_id()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
////die_dump($last_id);
            
            $format_id     = 'KTB-'.date('m').'-'.date('Y').'-%03d';
            $id_mesin         = sprintf($format_id, $last_id, 3);
//die_dump($id_mesin);
            $data['id'] = $id_mesin;
              $data['nama'] = $this->input->post('nama');
              $data['tipe'] = $this->input->post('tipe');
              $data['created_by'] = $this->session->userdata('user_id');
              $data['created_date'] = date('Y-m-d H:i:s' );
              $data['is_active'] = '1';
              $kategori_biaya_id = $this->m_kategori_biaya->add_data($data);
             // die($this->db->last_query());

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
//xattr_get(filename, name)die($this->db->last_query());
            if ($kategori_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Kategori biaya berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }


            
        }
        
        elseif ($array_input['command'] === 'edit')
        {
           
            $id = $this->input->post('id');

            $data = array(

                "tipe" => $array_input['tipe'],
                "nama"  => $array_input['nama'],
               'modified_by'       => $this->session->userdata('user_id'),
                'modified_date'     => date('Y-m-d H:i:s'),
            
            );  
            $save_kategori_biaya_id = $this->m_kategori_biaya->edit_data($data, $id);

            // $user_level_menu = $this->menu_user_m->get_data_is_active()->result_array();
            // die_dump($user_level_menu);
//die($this->db->last_query());


            if ($save_kategori_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data kategori biaya berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/kategori_biaya");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0,
            'modified_by'       => $this->session->userdata('user_id'),
                'modified_date'     => date("Y-m-d H:i:s")
        );
        // save data
        $user_id = $this->m_kategori_biaya->edit_data($data, $id);



        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Kategori Biaya telah dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/kategori_biaya");
    }


    public function delete2($id){
        $hapus = $this->m_kategori_biaya->hapusData('kategori_biaya',$id);
        if($hapus > 0){

         $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data Kategori biaya Ini Telah Dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('master/kategori_biaya');
        }else{
            echo 'Gagal disimpan';
        }
    }
}

/* End of file mesin_edc.php */
/* Location: ./application/controllers/mesin_edc/mesin_edc.php */