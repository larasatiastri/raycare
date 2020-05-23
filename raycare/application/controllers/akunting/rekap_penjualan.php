<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_penjualan extends MY_Controller {

    // protected $menu_id = '7935f149dd9c1c8d88ce7296c2fdcd4b';                  // untuk check bit_access
    protected $menu_id = '3ea89c28017d43bbab0b590c24a8af32';                  // untuk check bit_access
    
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

        $this->load->model('apotik/penjualan_obat/penjualan_obat_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_detail_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_detail_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('master/item/item_m');

       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/akunting/rekap_penjualan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $tgl_awal = date('Y-m-d');
        $tgl_akhir = date('Y-m-d');

        $data_laporan = $this->penjualan_obat_detail_m->get_laporan_penjualan($tgl_awal,$tgl_akhir,'-','-',0)->result_array();
        
        $isian_item = array();
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Rekap Penjualan', $this->session->userdata('language')), 
            'header'         => translate('Rekap Penjualan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'tgl_awal'       => $tgl_awal,
            'tgl_akhir'       => $tgl_akhir,
            'isian_item'     => $isian_item,
            'pasien_id'      => '-',
            'data_laporan'   => $data_laporan,
            'content_view'   => 'akunting/rekap_penjualan/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function search($tgl_awal,$tgl_akhir,$pasien,$item_id)
    {
        $assets = array();
        $config = 'assets/akunting/rekap_penjualan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $tgl_awal = date('Y-m-d', strtotime($tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));

        $pasien_id = '-';
        $nama_pasien = '-';

        $select_pasien = '-';
        
        if($pasien != 0){
            $select_pasien = $pasien;
            $pasien = explode('-', $pasien);
            $pasien_id = $pasien[0];
            $nama_pasien = $pasien[1];
        }
        

        $data_laporan = $this->penjualan_obat_detail_m->get_laporan_penjualan($tgl_awal,$tgl_akhir,$pasien_id,$nama_pasien,$item_id)->result_array();

        // die(dump($this->db->last_query()));

        $isian_item = explode('-', $item_id);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Rekap Penjualan', $this->session->userdata('language')), 
            'header'         => translate('Rekap Penjualan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'tgl_awal'       => $tgl_awal,
            'tgl_akhir'       => $tgl_akhir,
            'pasien_id'      => $select_pasien,
            'isian_item'      => $isian_item,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_laporan'   => $data_laporan,
            'content_view'   => 'akunting/rekap_penjualan/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function cetak($tgl_awal,$tgl_akhir,$pasien,$item_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $tgl_awal = date('Y-m-d', strtotime($tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));

        $pasien_id = '-';
        $nama_pasien = '-';

        $select_pasien = '-';
        
        if($pasien != 0){
            $select_pasien = $pasien;
            $pasien = explode('-', $pasien);
            $pasien_id = $pasien[0];
            $nama_pasien = $pasien[1];
        }

        $data_laporan = $this->penjualan_obat_detail_m->get_laporan_penjualan($tgl_awal,$tgl_akhir,$pasien_id,$nama_pasien,$item_id)->result_array();
        $isian_item = explode('-', $item_id);
        
        $data_cetak = array(
            'tgl_awal'       => $tgl_awal,
            'tgl_akhir'       => $tgl_akhir,
            'data_laporan'   => $data_laporan,
        );
        
        $mpdf = new mPDF('utf-8','A4', 0, '', 5, 5, 5, 8, 0, 0);
        $mpdf->SetHTMLFooter($this->load->view('akunting/rekap_penjualan/footer', $body, true));
        $mpdf->writeHTML($this->load->view('akunting/rekap_penjualan/cetak_laporan', $data_cetak, true));

        $mpdf->Output('Laporan Penjualan.pdf', 'I');
    } 

    

}

/* End of file antrian_tensi_bb.php */
/* Location: ./application/controllers/akunting/rekap_penjualan.php */