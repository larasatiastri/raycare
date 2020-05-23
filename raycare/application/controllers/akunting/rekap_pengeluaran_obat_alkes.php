<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_pengeluaran_obat_alkes extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '26b0805300b71a2a08b6e345e3e22a00';                  // untuk check bit_access

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

        $this->load->model('master/pasien_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/paket_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/tarif_ina_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('klinik_hd/tindakan_hd_history_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/akunting/rekap_pengeluaran_obat_alkes/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Laporan Invoice', $this->session->userdata('language')), 
            'header'         => translate(' Laporan Invoice', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/rekap_pengeluaran_obat_alkes/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
     
    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing($tgl_awal = null, $tgl_akhir = null,  $shift = null, $penjamin = null)
    {   
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }
        
        $result = $this->invoice_m->get_datatable_laporan_rekap($tgl_awal,$tgl_akhir,$shift,$penjamin);

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
            $i++;
            

            $invoice_detail = $this->invoice_detail_m->get_by(array('invoice_id' => $row['id'], 'is_active' => 1));
            $invoice_detail = object_to_array($invoice_detail);

            $total_invoice_tindakan = 0;
            $grand_total = 0;
            $detail_invoice = '';
            foreach ($invoice_detail as $inv_detail) {
                $total_invoice_tindakan = $total_invoice_tindakan + (($inv_detail['harga']*$inv_detail['qty']) - $inv_detail['diskon_nominal']);
                $detail_invoice .= $inv_detail['nama_tindakan'].' ('.$inv_detail['qty'].') </br>';
            }

            $grand_total = $total_invoice_tindakan + $row['akomodasi'];
            $total_invoice=$total_invoice+$total_invoice_tindakan;
            

            $data_items = $invoice_detail;


            $output['data'][] = array(

                '<div class="text-center">'.$i.'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.substr($row['no_invoice'], 12).'</div>',
                '<div class="text-left">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($grand_total).'</div>',
                '<div class="text-left">'.rtrim($detail_invoice,', ').'</div>',


            );

        }

        echo json_encode($output);
    }

    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing_pasien($tgl_awal = null, $tgl_akhir = null,  $shift = null, $penjamin = null)
    {   
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }
        
        $result = $this->tindakan_hd_history_m->get_datatable_laporan_rekap($tgl_awal,$tgl_akhir,$shift,$penjamin);
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
            $i++;

            $penjamin = ($row['penjamin_id'] == 1)?'Swasta':'BPJS';


            $output['data'][] = array(

                '<div class="text-center">'.$i.'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$penjamin.'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',


            );

        }

        echo json_encode($output);
    }

    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing_obat_alkes($tgl_awal = null, $tgl_akhir = null,  $shift = null, $penjamin = null)
    {   
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $invoice_id = array();

        $data_invoice = $this->invoice_m->get_data_invoice_tgl($tgl_awal,$tgl_akhir,$shift,$penjamin)->result_array();
        foreach ($data_invoice as $inv) {
            $invoice_id[] = $inv['id'];
        }

        $result = $this->invoice_detail_m->get_datatable($invoice_id);

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
            $i++;

            $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1), true);

            $satuan = ($row['tipe_item'] == 1)?'Box':$data_satuan->nama;

            $grand_total = 0;
            $grand_total = ($row['total_harga'] - $row['total_diskon']);

            $output['data'][] = array(

                '<div class="text-center">'.$i.'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['nama_tindakan'].'</div>',
                '<div class="text-left">'.$row['qty'].'</div>',
                '<div class="text-left">'.$satuan.'</div>',
                '<div class="text-right">'.formatrupiah($row['total_harga']).'</div>',
                '<div class="text-right">'.formatrupiah($row['total_diskon']).'</div>',
                '<div class="text-right">'.formatrupiah($grand_total).'</div>',

            );

        }

        echo json_encode($output);
    }

    
}

/* End of file buat_invoice.php */
/* Location: ./application/controllers/reservasi/buat_invoice.php */