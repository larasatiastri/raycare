<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_dokumen_klaim extends MY_Controller {

    protected $menu_id = '75ae990cee312fc7326c01ba5576f0ff';                  // untuk check bit_access
    
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

        $this->load->model('klinik_hd/tindakan_hd_history_m');
        $this->load->model('klinik_hd/outstanding_upload_dokumen_klaim_m');
        $this->load->model('master/pasien_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_history_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('master/pasien_penjamin_m');
 
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/upload_dokumen_klaim/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Upload Dokumen Klaim', $this->session->userdata('language')), 
            'header'         => translate('Upload Dokumen Klaim', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/upload_dokumen_klaim/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 


    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        

        $result = $this->outstanding_upload_dokumen_klaim_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=1;


        foreach($records->result_array() as $row)
        {   

            $action = '<a title="'.translate('Upload Dokumen', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_upload" name="upload" href="'.base_url().'klinik_hd/upload_dokumen_klaim/upload_dokumen/'.$row['id'].'" class="btn btn-primary upload"><i class="fa fa-upload"></i></a>';
                        
       
            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

    
    public function upload_dokumen($id)
    {
        $data_os_upload = $this->outstanding_upload_dokumen_klaim_m->get_by(array('id' => $id), true);
        $id_tindakan = $data_os_upload->tindakan_hd_history_id;
        $pasien_id = $data_os_upload->pasien_id;
        $data_pasien = $this->pasien_m->get_by(array('id' => $pasien_id), true);
        $data_tindakan = $this->tindakan_hd_history_m->get_by(array('id' => $id_tindakan), true);

        $data = array(
            'data_os_upload'  => object_to_array($data_os_upload),
            'data_tindakan'  => object_to_array($data_tindakan),
            'data_pasien'  => object_to_array($data_pasien),
            'id_tindakan'  => $id_tindakan,
        );

        $this->load->view('klinik_hd/upload_dokumen_klaim/upload_dokumen', $data);
    }

    public function generate_invoice()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();
            $this->load->library('mpdf/mpdf.php');

            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/docs/'.$array_input['no_transaksi'];
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

            $response = new stdClass;
            $response->success = false;
            $response->msg = 'Invoice tindakan ini tidak dapat ditemukan';

            $invoice = $this->invoice_m->get_by(array(
                'pasien_id' => $array_input['pasien_id'], 
                'date(created_date)' => date('Y-m-d', strtotime($array_input['tanggal'])),
                 'penjamin_id' => 2
                ), true);


            if(count($invoice) != 0){

                $id_invoice = $invoice->id;

                $invoice->created_date = date('d-M-Y', strtotime($invoice->created_date));
                $pasien = $this->pasien_m->get_by(array('id' => $invoice->pasien_id), true); 
                
                $invoice_detail_tindakan = $this->invoice_detail_m->get_by("invoice_id = '$invoice->id' AND tipe_item = 1 OR invoice_id = '$invoice->id' AND tipe_item = 2");
                $invoice_detail_tindakan = object_to_array($invoice_detail_tindakan);

                $invoice_detail_item = $this->invoice_detail_m->get_by("invoice_id = '$invoice->id' AND tipe_item = 3");
                $invoice_detail_item = object_to_array($invoice_detail_item);

                $data_pendaftaran = $this->pendaftaran_tindakan_history_m->get_by(array('pasien_id' => $invoice->pasien_id, 'date(created_date)' => date('Y-m-d', strtotime($invoice->created_date))), true);
                $penjamin_id = 2;
                if(count($data_pendaftaran))
                {
                    $penjamin_id = $data_pendaftaran->penjamin_id;
                }

                $data_edit = array(
                    'is_single_inv' => 1,
                    'no_invoice_bpjs' => str_replace(' ', '_', $invoice->no_invoice).'.pdf',
                );

                $edit_tindakan_history = $this->tindakan_hd_history_m->edit_data($data_edit, $array_input['tindakan_hd_id']);

                $data = array(
                    'invoice' => object_to_array($invoice),
                    'pasien' => object_to_array($pasien),
                    'invoice_detail_tindakan' => object_to_array($invoice_detail_tindakan),
                    'invoice_detail_item' => object_to_array($invoice_detail_item),
                    'penjamin_id' => $penjamin_id
                );

                $mpdf = new mPDF('','A4', 0, 'dejavusans', 0, 0, 0, 0, 0, 0);
                $mpdf->writeHTML($this->load->view('reservasi/pembayaran/print_invoice', $data, true));
                
                $mpdf->Output('../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/docs/'.$array_input['no_transaksi'].'/Invoice_'.str_replace(' ', '_', $invoice->no_invoice).'.pdf', 'F');

                $response->success = true;
                $response->msg = 'Berhasil';
                $filename = str_replace(' ', '_', $invoice->no_invoice).'.pdf';

                $response->filename = $array_input['no_transaksi'].'/Invoice_'.$filename;

            }
            
            die(json_encode($response));      
        }
    }

    public function generate_dokumen_pasien()
    {
        if($this->input->is_ajax_request()){

            $this->load->model('master/penjamin_dokumen_m');
            $this->load->model('master/pasien_dokumen_m');
            $this->load->model('master/pasien_dokumen_detail_m');

            $array_input = $this->input->post();
            $this->load->library('mpdf/mpdf.php');

            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/docs/'.$array_input['no_transaksi'];
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

            $response = new stdClass;
            $response->success = false;
            $response->msg = 'Dokumen pasien ini tidak dapat ditemukan';

            $this->load->library('mpdf/mpdf.php');

            $pasien_id = $array_input['pasien_id'];
            $penjamin_id = $array_input['penjamin_id'];

            $data_pasien = $this->pasien_m->get_by(array('id' => $pasien_id), true);
            $data_pasien = object_to_array($data_pasien);

            $ktp = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 1), true);
            $ktp_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 1), true, 'id');
            $ktp_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $ktp_pasien->id, 'tipe' => 9), true);
            
            //$ktp_detail_value = $ktp_detail;
            $ktp_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id,1,9)->row(0);
           // die(dump($this->db->last_query()));

            $kartu_penjamin = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 2), true);

            $kartu_penjamin_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => $kartu_penjamin->dokumen_id), true, 'id');
            $kartu_penjamin_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $kartu_penjamin_pasien->id, 'tipe' => 9), true);
            $kartu_penjamin_detail_value = $kartu_penjamin_detail;

            $kartu_penjamin_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, $kartu_penjamin->dokumen_id,9)->row(0);


            $kartu_keluarga = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 3), true);
            $kartu_keluarga_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 3), true, 'id');
            $kartu_keluarga_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $kartu_keluarga_pasien->id, 'tipe' => 9), true);
            $kartu_keluarga_detail_value = $kartu_keluarga_detail;

            $kartu_keluarga_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 3,9)->row(0);

            $rujukan = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 4), true);
            $rujukan_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 7), true, 'id');
            $rujukan_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien->id, 'tipe' => 9), true);
            $rujukan_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 7,9)->row(0);
            $rujukan_detail_nilai = object_to_array($rujukan_detail_value);

            $rujukan_memo = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 23), true, 'id');
            $rujukan_detail_memo = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_memo->id, 'tipe' => 9), true);
            $rujukan_detail_memo_value = $rujukan_detail_memo;
            $rujukan_detail_memo_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 23,9)->row(0);

            $rujukan_detail_memo_nilai = object_to_array($rujukan_detail_memo);

            $rujukan_pasien_rs = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 22), true, 'id');
            $rujukan_detail_rs = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien_rs->id, 'tipe' => 9), true);
            $rujukan_detail_rs_value = $rujukan_detail_rs;
            $rujukan_detail_rs_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 22,9)->row(0);
            $rujukan_detail_rs_nilai = object_to_array($rujukan_detail_rs);

            $rujukan_pasien_luar = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 13), true, 'id');
            $rujukan_detail_luar = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien_luar->id, 'tipe' => 9), true);
            $rujukan_detail_luar_value = $rujukan_detail_luar;
            $rujukan_detail_luar_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 13,9)->row(0);
            $rujukan_detail_luar_nilai = object_to_array($rujukan_detail_luar);

            $sppd = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_sppd')), true, 'id');
            $sppd_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $sppd->id, 'tipe' => 9), true);
            $sppd_detail_value = $sppd_detail;
            $sppd_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, config_item('id_surat_sppd'),9)->row(0);
            $sppd_detail_nilai = object_to_array($sppd_detail);

            $sppd_tiga = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_tiga_kali')), true, 'id');

            $sppd_tiga_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $sppd_tiga->id, 'tipe' => 9), true);
            $sppd_tiga_detail_value = $sppd_tiga_detail;
            $sppd_tiga_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, config_item('id_surat_tiga_kali'),9)->row(0);
            $sppd_tiga_detail_nilai = object_to_array($sppd_tiga_detail);

            $traveling = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 4), true, 'id');
            $traveling_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $traveling->id, 'tipe' => 9), true);
            $traveling_detail_value = $traveling_detail;
            $traveling_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 4,9)->row(0);
            $traveling_detail_nilai = object_to_array($traveling_detail_value);
    //die(dump(count($rujukan_memo)));

            $data_edit = array(
                'is_dok_pasien' => 1,
                'dok_pasien_file' => $data_pasien['no_member'].'.pdf',
            );

            $edit_tindakan_history = $this->tindakan_hd_history_m->edit_data($data_edit, $array_input['tindakan_hd_id']);

            $body = array(
                'data_pasien'           => object_to_array($data_pasien),
                'ktp_pasien'            => object_to_array($ktp_detail_value),
                'kartu_penjamin_pasien' => object_to_array($kartu_penjamin_detail_value),
                'kartu_keluarga_pasien' => object_to_array($kartu_keluarga_detail_value),
                'rujukan_pasien'        => object_to_array($rujukan_detail_value),
                'rujukan_pasien_rs'     => object_to_array($rujukan_detail_rs_value),
                'rujukan_pasien_luar'   => object_to_array($rujukan_detail_luar_value),
                'sppd'                  => object_to_array($sppd_detail_value),
                'sppd_tiga'             => object_to_array($sppd_tiga_detail_value),
                'traveling'             => object_to_array($traveling_detail_value),
                'memo'             => object_to_array($rujukan_detail_memo_value),
            );



            $mpdf = new mPDF('utf-8','A4', 0, '', 8, 5, 15, 5, 0, 0);
            $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_ktp', $body,true));

            $file_rujukan = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$rujukan_detail_nilai['value'];
            $result_rujukan = @get_headers($file_rujukan);

            if($result_rujukan[0] == 'HTTP/1.1 200 OK' && isset($result_rujukan[6])){
                $mpdf->AddPage();
                $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_rujukan_puskesmas', $body,true));
            }

            
            $file_rujukan_memo = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$rujukan_detail_memo_nilai['value'];
            $result_rujukan_memo = @get_headers($file_rujukan_memo);

            if($result_rujukan_memo[0] == 'HTTP/1.1 200 OK' && isset($result_rujukan_memo[6])){
                $mpdf->AddPage();
                $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_rujukan_memo', $body,true));
            }

            $file_rujukan_rs = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$rujukan_detail_rs_nilai['value'];
            $result_rujukan_rs = @get_headers($file_rujukan_rs);

            if($result_rujukan_rs[0] == 'HTTP/1.1 200 OK' && isset($result_rujukan_rs[6])){
                $mpdf->AddPage();
                $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_rujukan_rs', $body,true));
            }

            
            if(count($rujukan_pasien_luar) != 0)
            {
                $mpdf->AddPage();
                $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_rujukan_luar', $body,true));
            }

            $file_sppd = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/rekam_medis/'.$sppd_detail_nilai['value'];
            $result_sppd = @get_headers($file_sppd);

            if($result_sppd[0] == 'HTTP/1.1 200 OK' && isset($result_sppd[6])){
                $mpdf->AddPage();
                $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_sppd', $body,true));
            }
             
            
            
            $file_sppd_tiga = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$sppd_tiga_detail_nilai['value'];
            $result_sppd_tiga = @get_headers($file_sppd_tiga);

            if($result_sppd_tiga[0] == 'HTTP/1.1 200 OK' && isset($result_sppd_tiga[6])){
                $mpdf->AddPage();
                $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_sppd_tiga_kali', $body,true));
            }

            $file_traveling = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$traveling_detail_nilai['value'];
            $result_traveling = @get_headers($file_traveling);

            if($result_traveling[0] == 'HTTP/1.1 200 OK' && isset($result_traveling[6])){
                $mpdf->AddPage();
                $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_traveling', $body,true));
            }
            
            
            $mpdf->Output('../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/docs/'.$array_input['no_transaksi'].'/Dokumen_'.$data_pasien['no_member'].'.pdf', 'F');

            $response->success = true;
            $response->msg = 'Berhasil';
            $response->filename = $array_input['no_transaksi'].'/Dokumen_'.$data_pasien['no_member'].'.pdf';
        

            
            
            die(json_encode($response));      
        }
    }

    public function generate_assesment()
    {
        if($this->input->is_ajax_request()){

            $this->load->library('mpdf/mpdf.php');
            $this->load->model('master/cabang_m');
            $this->load->model('master/cabang_alamat_m');
            $this->load->model('master/cabang_telepon_m');
            $this->load->model('master/cabang_sosmed_m');
            $this->load->model('klinik_hd/tindakan_hd_penaksiran_history_m');
            $this->load->model('klinik_hd/observasi_history_m');
            $this->load->model('klinik_hd/tindakan_hd_item_history_m');
            $this->load->model('klinik_hd/pasien_problem_history_m');
            $this->load->model('klinik_hd/pasien_komplikasi_history_m');

            $array_input = $this->input->post();

            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/docs/'.$array_input['no_transaksi'];
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

            $response = new stdClass;
            $response->success = false;
            $response->msg = 'Assesment tindakan ini tidak dapat ditemukan';

            $tindakan_hd_id = $array_input['tindakan_hd_id'];
            $pasien_id = $array_input['pasien_id'];


            $form_tindakan_hd            = $this->tindakan_hd_history_m->get_by(array('id' => $tindakan_hd_id), true);
            $form_pasien                 = $this->pasien_m->get_by(array('id' => $pasien_id), true);
            $form_tindakan_hd_penaksiran = $this->tindakan_hd_penaksiran_history_m->get_by(array('tindakan_hd_id' => $form_tindakan_hd->id));
            // die(dump($this->db->last_query()));
            if(count($form_tindakan_hd_penaksiran) != 0){

                $pasien_problem              = $this->pasien_problem_history_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
                $pasien_komplikasi           = $this->pasien_komplikasi_history_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
               // $form_hemodialisis_history   = $this->tindakan_hd_history_m->get_hemodialisis_history($pasien_id, $form_tindakan_hd->tanggal)->result();
                $form_observasi              = $this->observasi_history_m->get_by_trans_id($tindakan_hd_id)->result_array();
                $form_medicine               = $this->tindakan_hd_item_history_m->get_is_assessment($tindakan_hd_id)->result();
                $form_cabang                 = $this->cabang_m->get($form_tindakan_hd->cabang_id);
                $cabang_alamat               = $this->cabang_alamat_m->get_by(array('cabang_id' => $form_tindakan_hd->cabang_id, 'is_primary' => 1, 'is_active' => 1));
                $cabang_alamat               = object_to_array($cabang_alamat);
                $cabang_telepon              = $this->cabang_telepon_m->get_by(array('cabang_id' => $form_tindakan_hd->cabang_id,'is_active' => 1, 'subjek_id' => 8));
                $cabang_telepon              = object_to_array($cabang_telepon);
                $cabang_fax                  = $this->cabang_telepon_m->get_by(array('cabang_id' => $form_tindakan_hd->cabang_id,'is_active' => 1, 'subjek_id' => 9));
                $cabang_fax                  = object_to_array($cabang_fax);
                $cabang_email                = $this->cabang_sosmed_m->get_by(array('tipe' => 1,'cabang_id' => $form_tindakan_hd->cabang_id,'is_active' => 1));
                $cabang_email                = object_to_array($cabang_email);
                $cabang_fb                   = $this->cabang_sosmed_m->get_by(array('tipe' => 3,'cabang_id' => $form_tindakan_hd->cabang_id,'is_active' => 1));
                $cabang_fb                   = object_to_array($cabang_fb);
                $cabang_twitter              = $this->cabang_sosmed_m->get_by(array('tipe' => 4,'cabang_id' => $form_tindakan_hd->cabang_id,'is_active' => 1));
                $cabang_twitter              = object_to_array($cabang_twitter);
                $cabang_website              = $this->cabang_sosmed_m->get_by(array('tipe' => 2,'cabang_id' => $form_tindakan_hd->cabang_id,'is_active' => 1));
                $cabang_website              = object_to_array($cabang_website);
                
                $data_email = '';
                foreach ($cabang_email as $email) 
                {
                    $data_email .= $email['url'].', ';
                }

                $data_edit = array(
                    'is_assesment' => 1,
                    'assesment_file' => $form_pasien->no_member.'.pdf',
                );

                $edit_tindakan_history = $this->tindakan_hd_history_m->edit_data($data_edit, $array_input['tindakan_hd_id']);

                $body = array(
                    "tindakan_hd_id"              => $tindakan_hd_id, 
                    "pasien_id"                   => $pasien_id, 
                    "form_tindakan_hd"            => object_to_array($form_tindakan_hd), 
                    "form_pasien"                 => object_to_array($form_pasien), 
                    "form_tindakan_hd_penaksiran" => object_to_array($form_tindakan_hd_penaksiran[0]), 
                    'pasien_problem'              => object_to_array($pasien_problem),
                    'pasien_komplikasi'           => object_to_array($pasien_komplikasi),
                    'form_observasi'              => object_to_array($form_observasi),
                    'form_medicine'               => (count($form_medicine))?object_to_array($form_medicine):'0',
                    'form_cabang'                 => $form_cabang,
                    'cabang_alamat'               => $cabang_alamat,
                    'cabang_telepon'              => $cabang_telepon,
                    'cabang_fax'                  => $cabang_fax,
                    'cabang_email'                => $cabang_email,
                    'cabang_fb'                   => $cabang_fb,
                    'cabang_twitter'              => $cabang_twitter,
                    'cabang_website'              => $cabang_website,
                    'data_email'                  => $data_email

                );
        
                $mpdf = new mPDF('utf-8','A4', 0, '', 8, 5, 5, 5, 0, 0);
                $mpdf->writeHTML($this->load->view('klinik_hd/transaksi_dokter/print_assesment_new', $body, true));
                

                // $mpdf->Output('Assessment_'.$form_pasien->no_member.'.pdf', 'F'); 
                $mpdf->Output('../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/docs/'.$array_input['no_transaksi'].'/Assessment_'.$form_pasien->no_member.'.pdf', 'F');

                $response->success = true;
                $response->msg = 'Berhasil';
                $response->filename = $array_input['no_transaksi'].'/Assessment_'.$form_pasien->no_member.'.pdf';
            }

            
            die(json_encode($response));      
        }
    }

    public function save_upload_sep()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('SEP untuk tindakan ini gagal ditambahkan', $this->session->userdata('language'));

            $array_input = $this->input->post();
            $tindakan_hd_history = $this->tindakan_hd_history_m->get_by(array('id' => $array_input['tindakan_hd_id']), true);

            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/images/'.$array_input['no_transaksi'];
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

            $temp_filename = $array_input['url_sep'];

            $convtofile = new SplFileInfo($temp_filename);
            $extenstion = ".".$convtofile->getExtension();

            $new_filename = $array_input['url_sep'];
            $real_file = $array_input['no_transaksi'].'/'.$new_filename;

            copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice_doc').$real_file);

            $temp_filename_ina = $array_input['url_inacbg'];

            $convtofile = new SplFileInfo($temp_filename_ina);
            $extenstion = ".".$convtofile->getExtension();

            $new_filename_ina = $array_input['url_inacbg'];
            $real_file_ina = $array_input['no_transaksi'].'/'.$new_filename_ina;

            copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename_ina, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice_doc').$real_file_ina);
            
           

            $file_pdf_names = array(
                '0' => $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice_doc').$array_input['no_transaksi'].'/'.$new_filename,
                '1' => $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice_doc').$array_input['no_transaksi'].'/'.$new_filename_ina,
                '2' => $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice_doc').$array_input['no_transaksi'].'/Invoice_'.$tindakan_hd_history->no_invoice_bpjs,
                '3' => $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice_doc').$array_input['no_transaksi'].'/Dokumen_'.$tindakan_hd_history->dok_pasien_file,
                '4' => $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice_doc').$array_input['no_transaksi'].'/Assessment_'.$tindakan_hd_history->assesment_file,
            );

            $outputFile = $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice_doc').$array_input['no_transaksi'].'/'.$array_input['no_sep'].'.pdf';
            $mergePdf = $this->mergePDFFiles($file_pdf_names, $outputFile,'','','');

            if($mergePdf){

                 $data = array(
                    'is_sep'  => 1,
                    'no_sep'  => $array_input['no_sep'],
                    'url_sep' => $new_filename,
                    'is_inacbg' => 1,
                    'url_inacbg' => $new_filename_ina,
                );

                $sep_id = $this->tindakan_hd_history_m->edit_data($data,$array_input['tindakan_hd_id']);

                $delete_os_upload = $this->outstanding_upload_dokumen_klaim_m->delete_by(array('tindakan_hd_history_id' => $array_input['tindakan_hd_id']));

                $response->success = true;
                $response->msg = translate('SEP untuk tindakan ini berhasil ditambahkan',$this->session->userdata('language'));
            }
        

            die(json_encode($response));

        }
    }

    public function mergePDFFiles(Array $filenames, $outFile, $title='', $author = '', $subject = '') {
        
        $this->load->library('mpdf/mpdf.php');

        $mpdf=new mPDF('c');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($author);
        $mpdf->SetSubject($subject);
        if ($filenames) {
            $filesTotal = sizeof($filenames);
            $mpdf->SetImportUse();        
            for ($i = 0; $i<count($filenames);$i++) {
                $curFile = $filenames[$i];
                if (file_exists($curFile)){
                    $pageCount = $mpdf->SetSourceFile($curFile);
                    for ($p = 1; $p <= $pageCount; $p++) {
                        $tplId = $mpdf->ImportPage($p);
                        $wh = $mpdf->getTemplateSize($tplId);                
                        if (($p==1)){
                            $mpdf->state = 0;
                            $mpdf->UseTemplate ($tplId);
                        }
                        else {
                            $mpdf->state = 1;
                            $mpdf->AddPage($wh['w']>$wh['h']?'L':'P');
                            $mpdf->UseTemplate($tplId);    
                        }
                    }
                }                    
            }                
        }
        $mpdf->Output($outFile, 'F');
        unset($mpdf);
        return true;
    }

}

/* End of file upload_dokumen_klaim.php */
/* Location: ./application/controllers/klinik_hd/upload_dokumen_klaim.php */