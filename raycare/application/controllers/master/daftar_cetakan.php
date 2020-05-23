<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_cetakan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '7973cd1d2f84dffdaf1ab115a9e9d37e';                  // untuk check bit_access

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

        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');  
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/daftar_cetakan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').'| '.translate('Daftar Cetakan', $this->session->userdata('language')), 
            'header'         => translate('Daftar Cetakan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/daftar_cetakan/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */