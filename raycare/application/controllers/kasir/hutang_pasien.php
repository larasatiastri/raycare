<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hutang_pasien extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '8f5761133fa833f8810cbf577d6301af';                  // untuk check bit_access

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

       
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/kasir/hutang_pasien/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Hutang Pasien', $this->session->userdata('language')), 
            'header'         => translate('Hutang Pasien', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'kasir/hutang_pasien/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing($tgl_awal=null,$tgl_akhir=null,$shift=null)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $result = $this->invoice_m->get_datatable_hutang($tgl_awal,$tgl_akhir,$shift);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        //die_dump($records);

        $total_invoice=0;
        $total_invoice_bayar=0;
        $total_invoice_hutang=0;
        $i=0;
        $count = count($records->result_array());
        $input_total = '';
        $input_total_bayar = '';
        $input_total_hutang = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            $total_invoice=$total_invoice+$row['harga'];
            $total_invoice_bayar=$total_invoice_bayar+($row['harga'] - $row['sisa_bayar']);
            $total_invoice_hutang=$total_invoice_hutang+$row['sisa_bayar'];

            if($i == $count){
                $input_total='<input id="input_total" type="hidden" value='.$total_invoice.'>';
                $input_total_bayar='<input id="input_total_bayar" type="hidden" value='.$total_invoice_bayar.'>';
                $input_total_hutang='<input id="input_total_hutang" type="hidden" value='.$total_invoice_hutang.'>';
            }
            $data_paket = $this->invoice_detail_m->get_data_paket($row['id'])->result_array();
            $data_items = $this->invoice_detail_m->get_data_items($row['id'])->result_array();
            $data_tindakan = $this->invoice_detail_m->get_data_tindakan($row['id'])->result_array();

            $data_paket_hutang = $this->invoice_detail_m->get_data_paket_bayar($row['id'])->result_array();
            $data_items_hutang = $this->invoice_detail_m->get_data_items_bayar($row['id'])->result_array();
            $data_tindakan_hutang = $this->invoice_detail_m->get_data_tindakan_bayar($row['id'])->result_array();

            $data_paket_bayar = $this->invoice_detail_m->get_data_paket_sudah_bayar($row['id'])->result_array();
            $data_items_bayar = $this->invoice_detail_m->get_data_items_sudah_bayar($row['id'])->result_array();
            $data_tindakan_bayar = $this->invoice_detail_m->get_data_tindakan_sudah_bayar($row['id'])->result_array();
            
            $output['aaData'][] = array(
                '<div class="text-center">'.date('d-M-Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['waktu'].'</div>',
                '<div class="text-center">'.substr($row['no_invoice'],12).'</div>',
                '<div class="text-left">'.$row['nama_resepsionis'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right"><a class="detail_item" data-paket="'.htmlentities(json_encode($data_paket)).'" data-item="'.htmlentities(json_encode($data_items)).'" data-tindakan="'.htmlentities(json_encode($data_tindakan)).'">'.formatrupiah($row['harga']).'</a>'.$input_total.'</div>',
                '<div class="text-right"><a class="detail_item_bayar" data-paket="'.htmlentities(json_encode($data_paket_bayar)).'" data-item="'.htmlentities(json_encode($data_items_bayar)).'" data-tindakan="'.htmlentities(json_encode($data_tindakan_bayar)).'">'.formatrupiah($row['harga'] - $row['sisa_bayar']).'</a>'.$input_total_bayar.'</div>',
                '<div class="text-right"><a class="detail_item_hutang" data-paket="'.htmlentities(json_encode($data_paket_hutang)).'" data-item="'.htmlentities(json_encode($data_items_hutang)).'" data-tindakan="'.htmlentities(json_encode($data_tindakan_hutang)).'">'.formatrupiah($row['sisa_bayar']).'</a>'.$input_total_hutang.'</div>',

            );
        }

        echo json_encode($output);

    }



    /**
     * [list description]
     * @return [type] [description]
     */


}

/* End of file hutang_pasien.php */
/* Location: ./application/controllers/keuangan/hutang_pasien.php */