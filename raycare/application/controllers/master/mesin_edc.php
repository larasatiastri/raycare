<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mesin_edc extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '28819b83476555e25dcdbc3675f7a55f';                  // untuk check bit_access

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

        $this->load->model('master/mesin_edc_m');
        $this->load->model('master/bank_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/mesin_edc/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master Mesin EDC', $this->session->userdata('language')), 
            'header'         => translate('Master Mesin EDC', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/mesin_edc/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/mesin_edc/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). ' | '.translate("Tambah Mesin EDC", $this->session->userdata("language")), 
            'header'         => translate("Tambah Mesin EDC", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/mesin_edc/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {

        $assets = array();
        $config = 'assets/master/mesin_edc/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->mesin_edc_m->get_by(array('id' => $id), true);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Mesin EDC", $this->session->userdata("language")), 
            'header'         => translate("Edit Mesin EDC", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/mesin_edc/edit',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id,
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
        $result = $this->mesin_edc_m->get_datatable();

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
    
            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/mesin_edc/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data mesin edc ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
               
                
           
            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                $row['nob'].' a/n '.$row['acc_name'].' Norek. '.$row['acc_number'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
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
                        
            $last_id       = $this->mesin_edc_m->get_max_id()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'EDC-'.date('m').'-'.date('y').'-%04d';
            $id_mesin         = sprintf($format_id, $last_id, 4);

            $data = array(
                'id'               => $id_mesin,
                'nama'             => $array_input['nama'],
                'bank_id'          => $array_input['bank_id'],
                'is_active'        => '1',
                'created_by'       => $this->session->userdata('user_id'),
                'created_date'     => date('Y-m-d')
            );
            $save_mesin_edc_id = $this->mesin_edc_m->add_data($data);

            if ($save_mesin_edc_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data mesin edc berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }


            
        }
        
        elseif ($array_input['command'] === 'edit')
        {
            $data = array(
                'nama'              => $array_input['nama'],
                'bank_id'     => $array_input['bank_id'],
                'is_active'         => '1',
                'modified_by'       => $this->session->userdata('user_id'),
                'modified_date'     => date('Y-m-d')
            );  
            $save_mesin_edc_id = $this->mesin_edc_m->save($data, $array_input['id']);

            // $user_level_menu = $this->menu_user_m->get_data_is_active()->result_array();
            // die_dump($user_level_menu);



            if ($save_mesin_edc_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data mesin edc berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/mesin_edc");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0,
            'modified_by'       => $this->session->userdata('user_id'),
                'modified_date'     => date('Y-m-d')
        );
        // save data
        $user_id = $this->mesin_edc_m->edit_data($data, $id);

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
        redirect("master/mesin_edc");
    }
}

/* End of file mesin_edc.php */
/* Location: ./application/controllers/mesin_edc/mesin_edc.php */