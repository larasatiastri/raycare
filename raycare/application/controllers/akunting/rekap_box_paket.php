<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_box_paket extends MY_Controller {

    // protected $menu_id = '7935f149dd9c1c8d88ce7296c2fdcd4b';                  // untuk check bit_access
    protected $menu_id = 'b28cce2b284b73c6fbe162cab011a4bf';                  // untuk check bit_access
    
    private $menus     = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level  = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('apotik/box_paket/t_box_paket_m');
        $this->load->model('apotik/box_paket/t_box_paket_detail_m');
        $this->load->model('apotik/box_paket/t_box_paket_history_m');
        $this->load->model('apotik/box_paket/t_box_paket_detail_history_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/item/item_m');
       
    }
    
    public function index()
    {
        
        $assets = array();
        $config = 'assets/akunting/rekap_box_paket/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $tgl_awal = date('Y-m-1');
        $tgl_akhir = date('Y-m-t');
        
        $data_laporan = $this->t_box_paket_m->get_data_laporan($tgl_awal,$tgl_akhir,0,0)->result_array();
        $data_laporan_group = $this->t_box_paket_m->get_data_laporan_group($tgl_awal,$tgl_akhir,0,0)->result_array();

        // die(dump($this->db->last_query()));
        
        $isian_item = array();
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Rekap Box Paket', $this->session->userdata('language')), 
            'header'         => translate('Rekap Box Paket', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'tgl_awal'       => $tgl_awal,
            'tgl_akhir'       => $tgl_akhir,
            'isian_item'     => $isian_item,
            'status'      => 0,
            'data_laporan'   => $data_laporan,
            'data_laporan_group'   => $data_laporan_group,
            'content_view'   => 'akunting/rekap_box_paket/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function search($tgl_awal,$tgl_akhir,$item_id,$status)
    {
        $assets = array();
        $config = 'assets/akunting/rekap_box_paket/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $tgl_awal = date('Y-m-d', strtotime($tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));

        $data_laporan = $this->t_box_paket_m->get_data_laporan($tgl_awal,$tgl_akhir,$item_id,$status)->result_array();
        $data_laporan_group = $this->t_box_paket_m->get_data_laporan_group($tgl_awal,$tgl_akhir,$item_id,$status)->result_array();

        $isian_item = explode('-', $item_id);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Rekap Box Paket', $this->session->userdata('language')), 
            'header'         => translate('Rekap Box Paket', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'tgl_awal'       => $tgl_awal,
            'tgl_akhir'       => $tgl_akhir,
            'status'      => $status,
            'isian_item'      => $isian_item,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_laporan'   => $data_laporan,
            'data_laporan_group'   => $data_laporan_group,
            'content_view'   => 'akunting/rekap_box_paket/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    

}

/* End of file antrian_tensi_bb.php */
/* Location: ./application/controllers/akunting/rekap_box_paket.php */