<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_omzet_invoice extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '68b292969a807058c32c7aae7a74bd2f';                  // untuk check bit_access

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
        $this->load->model('master/tindakan_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/tarif_ina_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('reservasi/pembayaran/pembayaran_pasien_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_detail_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_detail_item_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_m');
        $this->load->model('reservasi/pembayaran/pembayaran_tipe_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/laporan_omzet_invoice/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Laporan Omzet Invoice', $this->session->userdata('language')), 
            'header'         => translate('Laporan Omzet Invoice', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/laporan_omzet_invoice/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function get_data_omzet()
    {
        if($this->input->is_ajax_request()){

            $array_input = $this->input->post();
            $tanggal = date('Y-m-d', strtotime($array_input['tanggal']));
            $shift = $array_input['shift'];
            $penjamin_id = $array_input['penjamin_id'];


            $total_omzet = $this->invoice_m->get_total($penjamin_id, $shift, $tanggal)->result_array();
            // die(dump($this->db->last_query()));

            $total_hutang = $this->invoice_m->get_hutang($shift, $tanggal)->result_array();
            $total_edc = $this->pembayaran_tipe_m->get_total_bayar(2,$penjamin_id ,$shift, $tanggal)->result_array();
            $total_tunai = $this->pembayaran_tipe_m->get_total_bayar(1,$penjamin_id ,$shift, $tanggal)->result_array();

            $response = new stdClass;
            $response->success = true;
            $response->omzet = 0;
            $response->edc = 0;
            $response->tunai = 0;
            $response->hutang = 0;

            if($total_omzet[0]['total'] != NULL){
                $response->omzet = $total_omzet[0]['total'];
            }if($total_edc[0]['total_bayar'] != NULL){
                $response->edc = $total_edc[0]['total_bayar'];
            }if($total_tunai[0]['total_bayar'] != NULL){
                $response->tunai = $total_tunai[0]['total_bayar'];
            }if($total_hutang[0]['total'] != NULL){
                $response->hutang = $total_hutang[0]['total'];
            }

            die(json_encode($response));

        }
    }

    public function view_detail($tipe, $penjamin_id, $shift, $tanggal)
    {
        $tanggal = str_replace('%20', ' ', $tanggal);
        $tanggal = date('Y-m-d', strtotime($tanggal));

        $data = array(
            'tipe'    => $tipe,
            'penjamin_id'    => $penjamin_id,
            'shift'    => $shift,
            'tanggal' => $tanggal,
        );

        $this->load->view('keuangan/laporan_omzet_invoice/modal_detail',$data);

    }

    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing_invoice($tanggal=null,$shift=null,$penjamin_id=null)
    {        
        $tanggal = str_replace('%20', ' ', $tanggal);
        $tanggal = date('Y-m-d', strtotime($tanggal));
        $result = $this->invoice_m->get_datatable_laporan($tanggal,$tanggal,0,$shift,$penjamin_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);

        $i=0;
        $total = 0;
        $count = count($records->result_array());
        $input_total = '';
        foreach($records->result_array() as $row)
        {
            $i++;

            $invoice_detail = $this->invoice_detail_m->get_by(array('invoice_id' => $row['id'], 'is_active' => 1));
            $invoice_detail = object_to_array($invoice_detail);

            $total_invoice = 0;
            foreach ($invoice_detail as $inv_detail) {
                $total_invoice = $total_invoice + (($inv_detail['harga']*$inv_detail['qty']) - $inv_detail['diskon_nominal']);
            }

            $total_invoice = $total_invoice + $row['akomodasi'] - $row['diskon_nominal'] ;

            $status = '';
            $action = '';

            $jenis = '';

            if($row['jenis_invoice']==1){
                $jenis = 'Internal';
            }if($row['jenis_invoice']==2){
                $jenis = 'External';
            }

            $total = $total + $total_invoice;
            if($i == $count){
                $input_total='<input id="input_total" type="hidden" value='.$total.'>';
            }

            $output['data'][] = array(                
                '<div class="text-center">'.$row['no_invoice'].'</div>',            
                '<div class="text-center">'.$jenis.'</div>',            
                '<div class="text-left">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($total_invoice).$input_total.'</div>',
            );
        }

        echo json_encode($output);
    }

    public function listing_hutang($tanggal=null,$shift=null)
    {
        $tanggal = str_replace('%20', ' ', $tanggal);
        $tanggal = date('Y-m-d', strtotime($tanggal));

        $result = $this->invoice_m->get_datatable_hutang($tanggal,$tanggal,$shift);
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

        $i=0;
        $total = 0;
        $count = count($records->result_array());
        $input_total = '';     
        foreach($records->result_array() as $row)
        {
            $i++;
            $jenis = '';

            if($row['jenis_invoice']==1){
                $jenis = 'Internal';
            }if($row['jenis_invoice']==2){
                $jenis = 'External';
            }

            $total = $total + $row['harga'];
            if($i == $count){
                $input_total='<input id="input_total" type="hidden" value='.$total.'>';
            }
            
            $output['aaData'][] = array(
                
                '<div class="text-center">'.$row['no_invoice'].'</div>',            
                '<div class="text-center">'.$jenis.'</div>',            
                '<div class="text-left">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga']).$input_total.'</div>',

            );
        }

        echo json_encode($output);

    }

    public function listing_bayar($tipe, $penjamin_id, $shift, $tanggal)
    {
        $tanggal = str_replace('%20', ' ', $tanggal);
        $tanggal = date('Y-m-d', strtotime($tanggal));

        $result = $this->pembayaran_tipe_m->get_datatable_invoice_pembayaran($tipe, $penjamin_id, $shift, $tanggal);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);

        $i=0;
        $total = 0;
        $count = count($records->result_array());
        $input_total = '';       
        foreach($records->result_array() as $row)
        {
            $i++;
            $jenis = '';

            if($row['jenis_invoice']==1){
                $jenis = 'Internal';
            }if($row['jenis_invoice']==2){
                $jenis = 'External';
            }

            $total = $total + $row['rupiah'];
            if($i == $count){
                $input_total='<input id="input_total" type="hidden" value='.$total.'>';
            }

            
            $output['aaData'][] = array(
                
                '<div class="text-center">'.$row['no_invoice'].'</div>',            
                '<div class="text-center">'.$jenis.'</div>',            
                '<div class="text-left">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($row['rupiah']).$input_total.'</div>',

            );
        }

        echo json_encode($output);

    }

    public function listing_bayar_lain($tipe, $penjamin_id, $shift, $tanggal)
    {
        $tanggal = str_replace('%20', ' ', $tanggal);
        $tanggal = date('Y-m-d', strtotime($tanggal));

        $result = $this->pembayaran_tipe_m->get_datatable_invoice_pembayaran($tipe, $penjamin_id, $shift, $tanggal);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);

        $i=0; 
        $input_total = '';
        $total_invoice=0;
        $count = count($records->result_array());      
        foreach($records->result_array() as $row)
        {
            $jenis = '';
            $jenis_bayar = '';

            if($row['jenis_invoice']==1){
                $jenis = 'Internal';
            }if($row['jenis_invoice']==2){
                $jenis = 'External';
            }
            if($row['tipe_bayar']==1){
                $jenis_bayar = 'Tunai';
            }if($row['tipe_bayar']==2){
                $jenis_bayar = 'Mesin EDC';
            }

            $i++;
            $total_invoice=$total_invoice+$row['rupiah'];
            if($i == $count){
                $input_total='<input id="input_total" type="hidden" value='.$total_invoice.'>';
            }

            
            $output['aaData'][] = array(
                
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',            
                '<div class="text-center">'.$row['no_invoice'].'</div>',            
                '<div class="text-center">'.$jenis.'</div>',            
                '<div class="text-left">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$jenis_bayar.'</div>',
                '<div class="text-right">'.formatrupiah($row['rupiah']).$input_total.'</div>',

            );
        }

        echo json_encode($output);

    }

     
}

/* End of file buat_invoice.php */
/* Location: ./application/controllers/reservasi/buat_invoice.php */