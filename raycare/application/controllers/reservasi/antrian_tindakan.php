<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Antrian_tindakan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'dbf69e347764bc15a1e85f6df1c4c0d0';                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    // private $data_main = array();

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('klinik_hd/tindakan_hd_m');  
    }
    
    

    public function index()
    {
        $assets = array();
        $config = 'assets/reservasi/antrian_tindakan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_antrian = $this->tindakan_hd_m->get_data_antrian(1)->result_array();

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Antrian Tindakan', $this->session->userdata('language')), 
            'header'         => translate('Antrian Tindakan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/antrian_tindakan/index',
            'data_antrian'   => $data_antrian,
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    
}

/* End of file pendaftaran_tindakan.php */
/* Location: ./application/controllers/reservasi/pendaftaran_tindakan.php */