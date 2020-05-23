<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_pembelian extends MY_Controller {

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

        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('pembelian/item_m');
        $this->load->model('gudang/daftar_pembelian/pmb_po_detail_m');
        $this->load->model('master/item/item_m');
       
        $this->load->model('master/supplier/supplier_m');
        $this->load->model('pembelian/supplier_tipe_pembayaran_m');
       
    }
    
    public function index()
    {
        
        $assets = array();
        $config = 'assets/akunting/rekap_pembelian/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $tgl_awal = date('Y-m-1');
        $tgl_akhir = date('Y-m-t');

        $data_laporan = $this->pmb_po_detail_m->get_data_laporan($tgl_awal,$tgl_akhir,0,0)->result_array();

        // die(dump($this->db->last_query()));
        
        $isian_item = array();
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Rekap Pembelian', $this->session->userdata('language')), 
            'header'         => translate('Rekap Pembelian', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'tgl_awal'       => $tgl_awal,
            'tgl_akhir'       => $tgl_akhir,
            'isian_item'     => $isian_item,
            'supplier_id'      => 0,
            'data_laporan'   => $data_laporan,
            'content_view'   => 'akunting/rekap_pembelian/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function search($tgl_awal,$tgl_akhir,$supplier_id,$item_id)
    {
        $assets = array();
        $config = 'assets/akunting/rekap_pembelian/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $tgl_awal = date('Y-m-d', strtotime($tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));

        $data_laporan = $this->pmb_po_detail_m->get_data_laporan($tgl_awal,$tgl_akhir,$supplier_id,$item_id)->result_array();

        $isian_item = explode('-', $item_id);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Rekap Pembelian', $this->session->userdata('language')), 
            'header'         => translate('Rekap Pembelian', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'tgl_awal'       => $tgl_awal,
            'tgl_akhir'       => $tgl_akhir,
            'supplier_id'      => $supplier_id,
            'isian_item'      => $isian_item,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_laporan'   => $data_laporan,
            'content_view'   => 'akunting/rekap_pembelian/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function cetak($tgl_awal,$tgl_akhir,$supplier_id,$item_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $tgl_awal = date('Y-m-d', strtotime($tgl_awal));
        $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));

        $data_laporan = $this->pmb_po_detail_m->get_data_laporan($tgl_awal,$tgl_akhir,$supplier_id,$item_id)->result_array();

        $isian_item = explode('-', $item_id);
        
        $data_cetak = array(
            'tgl_awal'       => $tgl_awal,
            'tgl_akhir'       => $tgl_akhir,
            'data_laporan'   => $data_laporan,
        );
        
        $mpdf = new mPDF('utf-8','A4', 0, '', 5, 5, 5, 8, 0, 0);
        $mpdf->SetHTMLFooter($this->load->view('akunting/rekap_pembelian/footer', $body, true));
        $mpdf->AddPage('L');
        $mpdf->writeHTML($this->load->view('akunting/rekap_pembelian/cetak_laporan', $data_cetak, true));

        $mpdf->Output('Laporan Penjualan.pdf', 'I');
    } 

    

}

/* End of file antrian_tensi_bb.php */
/* Location: ./application/controllers/akunting/rekap_pembelian.php */