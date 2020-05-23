<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biaya extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'e9040257ed0fd0f0cfab75db6eaa20a5';                  // untuk check bit_access
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

        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_m');
    }
    public function index()
    {
        $assets = array();
        $config = 'assets/laporan/keuangan/biaya/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Grafik Laporan Biaya', $this->session->userdata('language')), 
            'header'         => translate('Grafik Laporan Biaya', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laporan/keuangan/biaya/index',
        );
    
        // Load the view
        $this->load->view('_layout', $data);
    }

    function get_all_divisi($start,$tipe)
    {
        $start = str_replace('%20', '-' , $start);

        if($tipe == 1){
            $param_year  = date('Y', strtotime($start));
            $param_month = date('m', strtotime($start)); 
        }elseif($tipe == 2){
            $param_year  = date('Y', strtotime($start." -1 months"));
            $param_month = date('m', strtotime($start." -1 months"));
        }
        
        $response = new stdClass;
        $response->success = false;

        $sql = "SELECT divisi.kode, divisi.nama, sum(permintaan_biaya.nominal_setujui) as total FROM `permintaan_biaya` 
                JOIN user ON permintaan_biaya.diminta_oleh_id = `user`.id
                JOIN user_level ON `user`.user_level_id = user_level.id 
                LEFT JOIN divisi ON user_level.divisi_id = divisi.id
                WHERE month(permintaan_biaya.tanggal) = ? 
                    AND year(permintaan_biaya.tanggal) = ? 
                    AND permintaan_biaya.`status` = 5 
                GROUP BY divisi.id";

        $query = $this->db->query(
            $sql,
            array( $param_month,$param_year)
        );
        
        $response->success = true;
        $response->rows = $query->result();
        

        die(json_encode($response));
    }
}

/* End of file spesialis.php */
/* Location: ./application/controllers/spesialis/spesialis.php */