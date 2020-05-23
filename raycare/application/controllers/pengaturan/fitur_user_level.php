<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fitur_user_level extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '4bfd5cea1a28be11eeb113d01b4fec3d';                  // untuk check bit_access

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

        $this->load->model('others/fitur_tombol_m');
        $this->load->model('others/fitur_tombol_user_level_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/pengaturan/fitur_user_level/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Fitur User Level', $this->session->userdata('language')), 
            'header'         => translate('Fitur User Level', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pengaturan/fitur_user_level/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function set_user_level($page,$button)
    {
        
        $assets = array();
        $config = 'assets/pengaturan/fitur_user_level/set_user_level';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $data = array(
            'title'        => config_item('site_name').' &gt;'.translate("Set Fitur User Level", $this->session->userdata("language")), 
            'header'       => translate("Set Fitur User Level", $this->session->userdata("language")),
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'pengaturan/fitur_user_level/set_user_level',
            'page'         => $page,
            'button'       => $button,
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($page = null)
    {        
        $page = urldecode($page);
        $result = $this->fitur_tombol_m->get_datatable($page);

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
            $batasan = '';
            $data_edit = '<a title="'.translate('Set User Level', $this->session->userdata('language')).'" href="'.base_url().'pengaturan/fitur_user_level/set_user_level/'.$row['page'].'/'.$row['button'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>';

            $action =  restriction_button($data_edit,$user_level_id,'pengaturan_fitur_user_level','set_user_level');

            $restriction = $this->fitur_tombol_user_level_m->get_by(array('page' => $row['page'], 'button' => $row['button']));
            if(count($restriction))
            {
                foreach ($restriction as $rest) 
                {
                    $user_level = $this->user_level_m->get($rest->user_level_id);

                    $batasan .= $user_level->nama.', ';
                }
            }

            $output['data'][] = array(
                $row['button'],
                rtrim($batasan,", "),
                '<div class="text-center">'. $action. '</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();

        if($array_input['command'] == 'add')
        {
            
            $page = $array_input['page'];
            $button = $array_input['button'];

            $this->fitur_tombol_user_level_m->delete_by_page($page, $button);

            if(isset($array_input['user_level_id']))
            {
                $user_levels = $array_input['user_level_id'];
                foreach ($user_levels as $row)
                {
                    $max_id = $this->fitur_tombol_user_level_m->max();
                    $max_id = $max_id->fitur_tombol_user_level_id;
                    $max_id = $max_id + 1;

                    if($max_id == NULL)
                    {
                        $max_id = 1;
                    }


                    $data = array(
                        'fitur_tombol_user_level_id' => $max_id,
                        'page'                       => $page,
                        'button'                     => $button,
                        'user_level_id'              => $row,
                        'created_by'                 => $this->session->userdata('user_id'),
                        'created_date'               => date('Y-m-d H:i:s')
                    );

                    $fitur_id = $this->fitur_tombol_user_level_m->add_data($data);
                }                
            }

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Fitur User Level berhasil ditambahkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        redirect('pengaturan/fitur_user_level');
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */