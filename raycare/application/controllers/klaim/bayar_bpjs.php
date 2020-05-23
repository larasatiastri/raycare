<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bayar_bpjs extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'a9a9f6760fd468b2dc336a0b0b2e8bb1';                  // untuk check bit_access

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

    
        $this->load->model('master/pasien_penjamin_m');        
        $this->load->model('master/cabang_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('others/jenis_peserta_bpjs_m');
        $this->load->model('kasir/inquiry_order_m');
        $this->load->model('kasir/bayar_iuran_bpjs_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('master/mesin_edc_m');
         $this->load->model('reservasi/pendaftaran/pembayaran_detail_m');
        $this->load->model('reservasi/pembayaran/pembayaran_pasien_m');
        $this->load->model('reservasi/pembayaran/pembayaran_tipe_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klaim/bayar_bpjs/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Bayar BPJS', $this->session->userdata('language')), 
            'header'         => translate('Bayar BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/bayar_bpjs/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function inquiry()
    {
        $array_input = $this->input->post();

        $result = new stdClass();
        $result->success = false;

        // $merchant_id = '327';
        // $sharedkey = 'sta911n9reickar3';
        // $url = 'http://103.252.51.158:2124/biller/inquiry';

        $merchant_id = '110';
        $sharedkey = 'r4iK4123';
        $url = 'http://inqv2.mobeli.co.id';

        $hash = SHA1($merchant_id.$sharedkey.$array_input['no_va']);

        $last_id = $this->inquiry_order_m->get_max_id()->result_array();
        $last_id = intval($last_id[0]['max_id'])+1;

        $format_id   = 'INQ-KLD-'.date('my').'-%04d';
        $id_order    = sprintf($format_id, $last_id, 4);

        $data_order = array(
            'id'    => $id_order,
            'status'    => 1,
            'created_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d H:i:s')
        );

        $save_order = $this->inquiry_order_m->add_data($data_order);

        $parameter = array(
            "MERCHANT_ID"  => $merchant_id,
            "ORDER_ID"     => $id_order,
            "CHECKSUMHASH" => $hash,
            "PRODUCT_CODE" => "BPJSKS",
            "SUBSCRIBER_NUMBER" => $array_input['no_va'],
            "PERIOD" => $array_input['periode_bayar'],
            "MOBILE_NO" => ""
        );

        $param = json_encode($parameter);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $server_output = curl_exec($ch);

        $response = json_decode($server_output);
        $response = object_to_array($response);
        // die(dump($response));

        $nama_pelanggan = explode(',', $response['INQUIRY_DETAILS']['subscriber_name']);

        if($response['INQUIRY_STATUS'] == '1001'){
            $result->success = true;
            $result->order_id = $id_order;
            $result->inquiry_id = $response['INQUIRY_ID'];
            $result->trx_id = $response['TRX_ID'];
            $result->nama_pelanggan = $nama_pelanggan[0];
            $result->jumlah_peserta = $response['BILL_INFO']['jumlah_anggota_keluarga'];
            $result->no_va_keluarga = $response['BILL_INFO']['no_va_keluarga'];
            $result->no_va_kepala_keluarga = $response['BILL_INFO']['no_va_kepala_keluarga'];
            $result->periode_bayar = $response['BILL_INFO']['jumlah_bulan'];
            $result->jumlah_tagihan = $response['INQUIRY_DETAILS']['transaction_amount'];
            $result->biaya_admin = $response['INQUIRY_DETAILS']['admin_fee'];
            $result->total_bayar = $response['TOTAL_TRX_AMOUNT'];
            $result->refnum = $response['BILL_INFO']['JPA_refnum'];
        }else{
            $result->msg = $response['INQUIRY_STATUS'].' - '.$response['INQUIRY_STATUS_DESC'];
        }

        
        die(json_encode($result));
       
    }

    public function payment()
    {
        $array_input = $this->input->post();
        $payment = $array_input['payment'];

        $shift_bayar = 1;
        if(date('H:i:s') > '03:00:01' &&  date('H:i:s') <= '12:00:00'){
            $shift_bayar = 1;
        }if(date('H:i:s') > '12:00:01' &&  date('H:i:s') <= '18:30:00'){
            $shift_bayar = 2;
        }if(date('H:i:s') > '18:30:01' &&  date('H:i:s') <= '23:59:59'){
            $shift_bayar = 3;
        }if(date('H:i:s') > '00:00:01' &&  date('H:i:s') <= '03:00:00'){
            $shift_bayar = 3;
        }

        // die(dump($array_input));

        $result = new stdClass();
        $result->success = false;

        // $merchant_id = '327';
        // $sharedkey = 'sta911n9reickar3';
        // $url = 'http://103.252.51.158:2122/biller/purchase';

        $merchant_id = '110';
        $sharedkey = 'r4iK4123';
        $url = 'http://purchasev2.mobeli.co.id';

        // $array_input['order_id'] = "INQ-RHS-1118-0011";
        $hash = SHA1($merchant_id.$sharedkey.$array_input['order_id']);

        $last_no = $this->bayar_iuran_bpjs_m->get_max_nomor()->result_array();
        $last_no = intval($last_no[0]['max_nomor'])+1;

        $format_id   = '#TRX-KLD-'.date('my').'-%04d';
        $no_bayar    = sprintf($format_id, $last_no, 4);

        $data_bayar = array(
            'nomor_invoice' => $no_bayar,
            'tanggal' => date('Y-m-d'),
            'jpa_ref' => $array_input['jpa_ref'],
            'no_va_keluarga' => $array_input['no_va_keluarga'],
            'no_va_kepala_keluarga' => $array_input['no_va_kepala_keluarga'],
            'no_va_kepala_keluarga' => $array_input['no_va_kepala_keluarga'],
            'nama_pelanggan' => $array_input['nama_pelanggan'],
            'jumlah_peserta' => $array_input['jumlah_peserta'],
            'periode' => $array_input['periode'],
            'jumlah_tagihan' => $array_input['jumlah_tagihan'],
            'biaya_admin' => $array_input['biaya_admin'],
            'total_tagihan' => $array_input['total_bayar_trx']
        );

        // $output = array('x' => 'y', 'price' => '0.00');
        // $json = json_encode($output);
        // $json = str_replace('"price":"'.$output['price'].'"', '"price":'.$output['price'].'', $json);
        // echo $json;
        // exit();

        $parameter = array(
            "CHECKSUMHASH" => $hash,
            "EMAIL" => "",
            "INQUIRY_ID" => $array_input['inquiry_id'],
            "MERCHANT_ID" => $merchant_id,
            "MOBILE_NO" => "",
            "ORDER_ID" => $array_input['order_id'],
            "PAYMENT_ID" => "",
            "PERIOD" => $array_input['periode'],
            "PRODUCT_CODE" => "BPJSKS",
            "PROMO_CAMP_CODE" => "",
            "SUBSCRIBER_NUMBER" => $array_input['no_va'],
            "TOTAL_TRX_AMOUNT" => $array_input['total_bayar_trx']
        );

        $param = json_encode($parameter);
        $param = str_replace('"TOTAL_TRX_AMOUNT":"'.$parameter['TOTAL_TRX_AMOUNT'].'"', '"TOTAL_TRX_AMOUNT":'.$parameter['TOTAL_TRX_AMOUNT'].'', $param);
       
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $server_output = curl_exec($ch);

        $response = json_decode($server_output);
        $response = object_to_array($response);

        if($response['PURCHASE_STATUS'] == '2001'){
            $save_bayar = $this->bayar_iuran_bpjs_m->save($data_bayar);

            $last_id_invoice       = $this->invoice_m->get_id_invoice()->result_array();
            $last_id_invoice       = intval($last_id_invoice[0]['max_id'])+1;
            
            $format_id_invoice     = 'IV-'.date('m').'-'.date('Y').'-%04d';
            $id_invoice = sprintf($format_id_invoice, $last_id_invoice, 4);

            $last_number_invoice  = $this->invoice_m->get_nomor_invoice()->result_array();
            if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
            {
                $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
            }
            else
            {
                $last_number_invoice = intval(1);
            }

            $format_invoice = date('Ymd').' - '.'%06d';
            $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);

            $data_invoice_real = array(
                'id'    => $id_invoice,
                'no_invoice'           => $no_invoice,
                'waktu_tindakan'       => date('H:i'),
                'cabang_id'            => $this->session->userdata('cabang_id'),
                'tipe_pasien'            => 2,
                'pasien_id'            => 0,
                'nama_pasien'          => $array_input['nama_pelanggan'],
                'tipe'                 => 1,
                'penjamin_id'          => 1,
                'nama_penjamin'        => $array_input['nama_pelanggan'],
                'is_claim'             => 0, 
                'poliklinik_id'        => 1,
                'nama_poliklinik'      => 'Poli HD',
                'status'               => 2,
                'shift'                => $shift_bayar,
                'jenis_invoice'                => 1,
                'harga'                => $array_input['jumlah_tagihan'],
                'akomodasi'            => $array_input['biaya_admin'],
                'diskon'               => 0,
                'harga_setelah_diskon' => $array_input['total_bayar_trx'],
                'sisa_bayar' => $array_input['total_bayar_trx'],
                'user_level_id' => $this->session->userdata('level_id'),
                'is_active'            => 1,
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s')
            );

            $save_invoice = $this->invoice_m->add_data($data_invoice_real);

            $last_id_invoice_detail       = $this->invoice_detail_m->get_id_invoice_detail()->result_array();
            $last_id_invoice_detail       = intval($last_id_invoice_detail[0]['max_id'])+1;
            
            $format_id_invoice_detail     = 'IVD-'.date('m').'-'.date('Y').'-%04d';
            $id_invoice_detail = sprintf($format_id_invoice_detail, $last_id_invoice_detail, 4);


            $data_invoice_real = array(
                'id'    => $id_invoice_detail,
                'invoice_id'           => $id_invoice,
                'item_id'   => '0',
                'nama_tindakan'   => 'Bayar Iuran BPJS '.$array_input['periode'].' Periode',
                'tipe_item'   => 3,
                'qty'   => 1,
                'hpp'   => $array_input['jumlah_tagihan'],
                'harga'   => $array_input['jumlah_tagihan'],
                'status'            => 2,
                'is_active'            => 1,
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s')
            );

            $save_invoice_detail = $this->invoice_detail_m->add_data($data_invoice_real);

            $last_number  = $this->pembayaran_pasien_m->get_nomor_faktur()->result_array();
            $last_number  = intval($last_number[0]['max_nomor_faktur'])+1;
            // die_dump($last_number);

            $format       = 'PYC-'.date('ym').'-%03d';
            $no_faktur    = sprintf($format, $last_number, 3);


            $data = array(
                'cabang_id'      => $this->session->userdata('cabang_id'),
                'no_pembayaran'  => $no_faktur,
                'pasien_id'      => 0,
                'nama_pasien'    => $array_input['nama_pelanggan'],
                'tipe_pasien'    => 1,
                'kasir_id'       => $array_input['user_id'],
                'shift'          => $shift_bayar,
                'tanggal'        => date('Y-m-d'),
                'tipe_transaksi' => 1,
                'status'         => 1,   
                'is_active'      => 1,   
            );
            // die_dump($data);

            $pembayaran_pasien_id = $this->pembayaran_pasien_m->save($data);
            // die(dump($this->db->last_query()));

            $data_detail = array(
                'pembayaran_id' => $pembayaran_pasien_id,
                'invoice_id'    => $id_invoice,
                'total_bayar'   => $array_input['total_bayar_trx'],
                'status'        => 1
            );

            $pembayaran_detail_id = $this->pembayaran_detail_m->save($data_detail);

            if($payment)
            {
                foreach ($payment as $pay) 
                {
                    
                    $data_tipe_bayar = array(
                        'pembayaran_id' => $pembayaran_pasien_id,
                        'tipe_bayar'    => $pay['payment_type'],
                        'mesin_edc_id'  => ($pay['payment_type'] == 1)?'':$pay['mesin_edc_id'],
                        'jumlah_bayar'        => $pay['jumlah_bayar'],
                        'rupiah'        => $pay['nominal'],
                        'no_kartu'      => ($pay['payment_type'] == 1)?'':$pay['no_kartu'],
                        'jenis_kartu'   => ($pay['payment_type'] == 1)?0:$pay['jenis_kartu'],
                        'status'        => 1,
                    );
                    $pembayaran_tipe = $this->pembayaran_tipe_m->save($data_tipe_bayar);

                }
            }


            $result->response = $response;
            $result->success = true;
            $result->id_bayar = $save_bayar;
        }else{
            $result->response = $response;
            $result->success = false;
            $result->msg = $response['PURCHASE_STATUS_DESC'];
        }
        
        die(json_encode($result));

    }

    public function advice()
    {
        $array_input = $this->input->post();

        $result = new stdClass();
        $result->success = false;

        // $merchant_id = '327';
        // $sharedkey = 'sta911n9reickar3';
        // $url = 'http://103.252.51.158:2123/biller/advice';

        $merchant_id = '110';
        $sharedkey = 'r4iK4123';
        $url = 'http://advicev2.mobeli.co.id';

        // $array_input['order_id'] = "INQ-RHS-1118-0011";
        $hash = SHA1($merchant_id.$sharedkey.$array_input['order_id'].$array_input['trx_id']);
        $total = 162500;

        // $output = array('x' => 'y', 'price' => '0.00');
        // $json = json_encode($output);
        // $json = str_replace('"price":"'.$output['price'].'"', '"price":'.$output['price'].'', $json);
        // echo $json;
        // exit();

        $parameter = array(
            "MERCHANT_ID" => $merchant_id,
            "ORDER_ID" => $array_input['order_id'],
            "TRX_ID" => $array_input['trx_id'],
            "PAYMENT_ID" => "",
            "PURCHASE_ID" => "",
            "CHECKSUMHASH" => $hash
        );

        $param = json_encode($parameter);
        // print_r($param);
        // exit();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $server_output = curl_exec($ch);

        $response = json_decode($server_output);
        $response = object_to_array($response);


        
        die(dump($response));

    }


    public function print_invoice_dot($id_bayar)
    {

        $bayar_bpjs = $this->bayar_iuran_bpjs_m->get_by(array('id' => $id_bayar),true);
        $data = array(
            'bayar_bpjs' => object_to_array($bayar_bpjs),
        );

        $this->load->view('reservasi/pembayaran/print_invoice_dot_bpjs', $data);


    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */