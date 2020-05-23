<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_apoteker extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '3bf263cf8d8212c68b39a5c7daf6337e';                  // untuk check bit_access

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

        $this->load->model('master/cabang_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/item/item_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('apotik/buffer_stock_m');
        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_detail_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/dashboard_apoteker/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_expired = $this->buffer_stock_m->get_data_expired()->result_array();
        $data_buffer = $this->buffer_stock_m->get_data_buffer()->result_array();
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Dashboard', $this->session->userdata('language')), 
            'header'         => translate('Dashboard', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_expired'   => $data_expired,
            'data_buffer'    => $data_buffer,
            'content_view'   => 'apotik/dashboard_apoteker/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
    
    public function listing_proses($tipe=null)
    {      
        $result = $this->pembelian_m->get_datatable_proses($tipe, 1);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $info = '';
            $no_cetak = '';
            $supplier = '';
            $tgl_pesan = '';
            $tgl_kadaluarsa = '';


            if($row['nama_sup'] != null && $row['kode_sup'] != null && $row['tanggal_pesan'] != null && $row['tanggal_kadaluarsa'] != null)
            {
                $supplier = '<div class="text-left inline-button-table">'.$row['nama_sup'].' <strong>['.$row['kode_sup'].']</strong></div>';
                $tgl_pesan = date('d M Y', strtotime($row['tanggal_pesan']));
                $tgl_kadaluarsa = date('d M Y', strtotime($row['tanggal_kadaluarsa']));

                
            }
            
            $output['data'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left inline-button-table">'.$row['no_po'].'</div>',
                $supplier,
                '<div class="text-center">'.$tgl_pesan.'</div>',
                '<div class="text-center">'.$tgl_kadaluarsa.'</div>',
                $row['keterangan']
            );
         $i++;
        }

        echo json_encode($output);
    }

  
    
    
    
}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */