<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bahasa extends CI_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 3;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(2, 3);       // untuk keperluan item menu dengan class 'open', 'selected'

    // private $data_main = array();

    public function __construct()
    {       
        parent::__construct();
        
        $this->load->model('master/bahasa_m');
        $this->load->model('master/user_m');
        $this->load->model('master/user_level_menu_m');
        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        
       
    }
    
    public function index()
    {
       
    }

    public function ganti_bahasa($id,$url)
    {
        //$link = str_replace("7", "", urldecode(base64_decode($url)));
        $link = base64_decode(urldecode($url));
        $language = $this->bahasa_m->get($id);

        $array_session = array(
            "language"          =>  $language->kode,
        );
        $this->session->set_userdata($array_session);

        $user_id = $this->session->userdata("user_id");
        $data['language'] = $language->kode;
        $this->user_m->save($data,$user_id);        

        $this->user_level_menu_m->delete_menu_file();
        // echo current_url();
        redirect($link);
    }



}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */