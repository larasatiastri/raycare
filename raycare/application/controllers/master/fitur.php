<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fitur extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '182ceab8ccfa0c23b776dada4be2f03e';                  // untuk check bit_access

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

        $this->load->model('master/m_fitur');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/fitur/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Fitur', $this->session->userdata('language')), 
            'header'         => translate('Master Fitur', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/fitur/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/fitur/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). ' &gt;'.translate("Tambah Fitur", $this->session->userdata("language")), 
            'header'         => translate("Tambah Fitur", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/fitur/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/fitur/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        // $form_data = $this->cabang_m->get($id);
        $form_data=$this->m_fitur->get_by(array('id'=>$id));
      //  $form_data2=$this->poliklinik_tindakan_m->getdata($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Fitur", $this->session->userdata("language")), 
            'header'         => translate("Edit Fitur", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/fitur/edit',
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
        $result = $this->m_fitur->get_datatable();
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
    
            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/fitur/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus fitur ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
               
                
           
            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                $row['path'],
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
              $data['nama'] = $this->input->post('nama');
            $data['path'] = $this->input->post('path');
           
            $fitur_id = $this->m_fitur->save($data);

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
            if ($fitur_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data fitur berhasil ditambahkan.", $this->session->userdata("language")),
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
                'path'              => $array_input['path'],

            );  
            $save_fitur_id = $this->m_fitur->save($data, $id);

            // $user_level_menu = $this->menu_user_m->get_data_is_active()->result_array();
            // die_dump($user_level_menu);
//die($this->db->last_query());


            if ($save_fitur_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data fitur berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/fitur");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0,
            'modified_by'       => $this->session->userdata('user_id'),
                'modified_date'     => date('Y-m-d')
        );
        // save data
        $user_id = $this->m_fitur->edit_data($data, $id);

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
                "msg"      => translate("Mesin EDC telah dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/fitur");
    }


    public function delete2($id){
        $hapus = $this->m_fitur->hapusData('fitur',$id);
        if($hapus > 0){

         $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data Fitur Ini Telah Dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('master/fitur');
        }else{
            echo 'Gagal disimpan';
        }
    }
}

/* End of file mesin_edc.php */
/* Location: ./application/controllers/mesin_edc/mesin_edc.php */