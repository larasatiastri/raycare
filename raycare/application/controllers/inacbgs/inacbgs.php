<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inacbgs extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '2bf43ddbd2bf71ddd5f369c81e5a95fa';                  // untuk check bit_access

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
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/inacbgs/inacbgs/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Inacgbs', $this->session->userdata('language')), 
            'header'         => translate('Inacbgs', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'inacbgs/inacbgs/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

}

/* End of file inacbgs.php */
/* Location: ./application/controllers/inacbgs/inacbgs.php */