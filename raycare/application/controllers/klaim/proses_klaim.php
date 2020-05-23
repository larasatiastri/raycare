<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses_klaim extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'a7530bdb1a8fe270d95ff9e2623a9444';                  // untuk check bit_access

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

        $this->load->model('klaim/proses_klaim/proses_klaim_m');        
        $this->load->model('klaim/proses_klaim/proses_klaim_kwitansi_m');        
        $this->load->model('klinik_hd/tindakan_hd_history_m');        
        $this->load->model('master/petugas_bpjs_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/bank_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klaim/proses_klaim/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/proses_klaim/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/klaim/proses_klaim/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/proses_klaim/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/klaim/proses_klaim/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tambah Proses Klaim', $this->session->userdata('language')), 
            'header'         => translate('Tambah Proses Klaim', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/proses_klaim/add',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($flag)
    {        
        if($flag == 1)
        {
            $status = array('1','2','3');
        }
        if($flag == 2)
        {
            $status = array('4');
        }
        $result = $this->proses_klaim_m->get_datatable($status);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';

            if($row['status'] == 1 || $row['status'] == 2)
            {
                $action = '<a title="'.translate('Print Dokumen', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klaim/proses_klaim/cetak_dokumen/'.$row['id'].'" class="btn default"><i class="fa fa-print"></i></a>';
            }
            if($row['status'] == 3)
            {
                $action = '<a title="'.translate('Print Dokumen', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klaim/proses_klaim/cetak_dokumen/'.$row['id'].'" class="btn default"><i class="fa fa-print"></i></a><a title="'.translate('Buat Kwitansi', $this->session->userdata('language')).'" href="'.base_url().'klaim/proses_klaim/buat_kwitansi/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-dollar"></i></a>';
            }
            if($row['status'] == 4)
            {

                $action = '<a title="'.translate('Print Dokumen BPJS', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klaim/verifikasi_klaim_bpjs/cetak_dokumen/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a><a title="'.translate('Print Dokumen', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klaim/proses_klaim/cetak_dokumen/'.$row['id'].'" class="btn default"><i class="fa fa-print"></i></a><a title="'.translate('Print Kwitansi', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klaim/proses_klaim/cetak_kwitansi/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
            }

            $output['data'][] = array(
                '<div class="text-left">'.date('F Y', strtotime($row['periode_tindakan'])).'</div>',
                '<div class="text-center">'.$row['no_surat'].'</div>',
                '<div class="text-left">'.$row['jumlah_tindakan'].'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_tarif_riil']).'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_tarif_ina']).'</div>',
                '<div class="text-left">'.$row['jumlah_tindakan_verif'].'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_tarif_ina_verif']).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['status'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function get_jumlah_tindakan()
    {
        $bulan = $this->input->post('bulan');

        $bulan = str_replace('%20', ' ', $bulan);
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));

        $response = new stdClass;
        $response->success = false;
        $response->count = 0;
        $response->tarif_rs = 0;
        $response->tarif_ina = 0;

        $jumlah_tindakan = $this->tindakan_hd_history_m->get_jumlah_tindakan($month,$year);

        if(count($jumlah_tindakan))
        {
            $response->success = true;
            $response->count   = $jumlah_tindakan[0]['jumlah_tindakan'];
            $response->tarif_rs = config_item('tarif_rs') * $jumlah_tindakan[0]['jumlah_tindakan'];
            $response->tarif_ina = config_item('tarif_ina') * $jumlah_tindakan[0]['jumlah_tindakan'];
        }
        die(json_encode($response));
    }

    public function get_jumlah_tindakan_manual()
    {
        $jumlah = $this->input->post('jumlah');

        $response = new stdClass;
        
        $response->success = true;
        $response->count   = $jumlah;
        $response->tarif_rs = config_item('tarif_rs') * $jumlah;
        $response->tarif_ina = config_item('tarif_ina') * $jumlah;
        
        die(json_encode($response));
    }

    public function save()
    {
        $array_input = $this->input->post();
        // die(dump($array_input));
        if($array_input['command'] == 'add')
        {
            $data_klaim = array(
                'cabang_id'              => $this->session->userdata('cabang_id'),
                'tanggal'                => date('Y-m-d H:i:s', strtotime($array_input['tanggal_surat'])),
                'no_surat'               => $array_input['no_surat'],
                'no_surat_perjanjian'    => $array_input['no_surat_perjanjian'],
                'periode_tindakan'       => date('Y-m-d', strtotime($array_input['periode_tindakan'])),
                'jumlah_tindakan'        => $array_input['jumlah_tindakan'],
                'jumlah_tarif_riil'      => $array_input['jumlah_tarif_riil'],
                'jumlah_tarif_ina'       => $array_input['jumlah_tarif_ina'],
                'amhp'                   => 0,
                'biaya_lain'             => 0,
                'jumlah_tindakan_verif'  => 0,
                'jumlah_tarif_ina_verif' => 0,
                'amhp_verif'             => 0,
                'biaya_lain_verif'       => 0,
                'user_id'                => $array_input['diserahkan_oleh'],
                'penerima_id'            => $array_input['penerima_id'],
                'verif_id'               => $array_input['verif_id'],
                'status'                 => 1
            );

            $proses_klaim_id = $this->proses_klaim_m->save($data_klaim);

            if($proses_klaim_id)
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data klaim tindakan berhasil diproses.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }
        if($array_input['command'] == 'buat_kwitansi')
        {
            $data_kwitansi = array(
                'proses_klaim_id'   => $array_input['proses_id'],
                'no_kwitansi'       => $array_input['no_kwitansi'],
                'jumlah_terima'     => $array_input['jumlah_terima'],
                'tipe_bayar'        => $array_input['tipe_bayar'],
                'no_check_transfer' => $array_input['no_check_transfer'],
                'diterima_dari'     => $array_input['diterima_dari'],
                'deskripsi'         => $array_input['deskripsi'],
                'dibayar_ke'        => $array_input['dibayar_ke'],
                'bank_id'           => $array_input['bank_id'],
                'status'           => 1
            );

            $proses_klaim_kwitansi_id = $this->proses_klaim_kwitansi_m->save($data_kwitansi);

            $data_klaim['status'] = 4;
            $proses_klaim_id = $this->proses_klaim_m->save($data_klaim,$array_input['proses_id'] );

            if($proses_klaim_kwitansi_id)
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Kwitansi Telah Dibuat", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

        }
        redirect("klaim/proses_klaim");
    }

    public function buat_kwitansi($id)
    {
        $assets = array();
        $config = 'assets/klaim/proses_klaim/buat_kwitansi';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_klaim  = $this->proses_klaim_m->get($id);
        $data_klaim = object_to_array($data_klaim);
        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Buat Kwitansi', $this->session->userdata('language')), 
            'header'         => translate('Buat Kwitansi', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_klaim'     => $data_klaim,
            'content_view'   => 'klaim/proses_klaim/buat_kwitansi',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function cetak_dokumen($id)
    {
        $this->load->library('mpdf/mpdf.php');

        $data_proses    = $this->proses_klaim_m->get($id);
        $form_cabang    = $this->cabang_m->get($data_proses->cabang_id);
        $cabang_alamat  = $this->cabang_alamat_m->get_by(array('cabang_id' => $data_proses->cabang_id, 'is_primary' => 1, 'is_active' => 1));
        $cabang_alamat  = object_to_array($cabang_alamat);
        $cabang_telepon = $this->cabang_telepon_m->get_by(array('cabang_id' => $data_proses->cabang_id,'is_active' => 1, 'subjek_id' => 8));
        $cabang_telepon = object_to_array($cabang_telepon);
        $cabang_fax     = $this->cabang_telepon_m->get_by(array('cabang_id' => $data_proses->cabang_id,'is_active' => 1, 'subjek_id' => 9));
        $cabang_fax     = object_to_array($cabang_fax);
        $cabang_email   = $this->cabang_sosmed_m->get_by(array('tipe' => 1,'cabang_id' => $data_proses->cabang_id,'is_active' => 1));
        $cabang_email   = object_to_array($cabang_email);
        $cabang_fb      = $this->cabang_sosmed_m->get_by(array('tipe' => 3,'cabang_id' => $data_proses->cabang_id,'is_active' => 1));
        $cabang_fb      = object_to_array($cabang_fb);
        $cabang_twitter = $this->cabang_sosmed_m->get_by(array('tipe' => 4,'cabang_id' => $data_proses->cabang_id,'is_active' => 1));
        $cabang_twitter = object_to_array($cabang_twitter);
        $cabang_website = $this->cabang_sosmed_m->get_by(array('tipe' => 2,'cabang_id' => $data_proses->cabang_id,'is_active' => 1));
        $cabang_website = object_to_array($cabang_website);

        $data_email = '';
        foreach ($cabang_email as $email) 
        {
            $data_email .= $email['url'].', ';
        }

        $body = array(
            'data_proses'    => object_to_array($data_proses),
            'form_cabang'    => $form_cabang,
            'cabang_alamat'  => $cabang_alamat,
            'cabang_telepon' => $cabang_telepon,
            'cabang_fax'     => $cabang_fax,
            'cabang_email'   => $cabang_email,
            'cabang_fb'      => $cabang_fb,
            'cabang_twitter' => $cabang_twitter,
            'cabang_website' => $cabang_website,
            'data_email'     => $data_email
        );

        $mpdf = new mPDF('utf-8','A4', 0, '', 10, 10, 15, 10, 0, 0);

        $mpdf->writeHTML($this->load->view('klaim/proses_klaim/print_pengajuan', $body, true));
        $mpdf->AddPage();
        $mpdf->writeHTML($this->load->view('klaim/proses_klaim/print_berita_acara', $body, true));

        $mpdf->Output('Dokumen_'.$data_proses->no_surat.'.pdf', 'I');
    }

    public function cetak_kwitansi($id)
    {
        $this->load->library('mpdf/mpdf.php');

        $data_proses    = $this->proses_klaim_m->get($id);
        $data_kwitansi  = $this->proses_klaim_kwitansi_m->get_by(array('proses_klaim_id' => $id), true);
        $form_cabang    = $this->cabang_m->get($data_proses->cabang_id);
        $cabang_alamat  = $this->cabang_alamat_m->get_by(array('cabang_id' => $data_proses->cabang_id, 'is_primary' => 1, 'is_active' => 1));
        $cabang_alamat  = object_to_array($cabang_alamat);
        $cabang_telepon = $this->cabang_telepon_m->get_by(array('cabang_id' => $data_proses->cabang_id,'is_active' => 1, 'subjek_id' => 8));
        $cabang_telepon = object_to_array($cabang_telepon);
        $cabang_fax     = $this->cabang_telepon_m->get_by(array('cabang_id' => $data_proses->cabang_id,'is_active' => 1, 'subjek_id' => 9));
        $cabang_fax     = object_to_array($cabang_fax);
        $cabang_email   = $this->cabang_sosmed_m->get_by(array('tipe' => 1,'cabang_id' => $data_proses->cabang_id,'is_active' => 1));
        $cabang_email   = object_to_array($cabang_email);
        $cabang_fb      = $this->cabang_sosmed_m->get_by(array('tipe' => 3,'cabang_id' => $data_proses->cabang_id,'is_active' => 1));
        $cabang_fb      = object_to_array($cabang_fb);
        $cabang_twitter = $this->cabang_sosmed_m->get_by(array('tipe' => 4,'cabang_id' => $data_proses->cabang_id,'is_active' => 1));
        $cabang_twitter = object_to_array($cabang_twitter);
        $cabang_website = $this->cabang_sosmed_m->get_by(array('tipe' => 2,'cabang_id' => $data_proses->cabang_id,'is_active' => 1));
        $cabang_website = object_to_array($cabang_website);

        $data_email = '';
        foreach ($cabang_email as $email) 
        {
            $data_email .= $email['url'].', ';
        }

        $body = array(
            'data_proses'    => object_to_array($data_proses),
            'data_kwitansi'  => object_to_array($data_kwitansi),
            'form_cabang'    => $form_cabang,
            'cabang_alamat'  => $cabang_alamat,
            'cabang_telepon' => $cabang_telepon,
            'cabang_fax'     => $cabang_fax,
            'cabang_email'   => $cabang_email,
            'cabang_fb'      => $cabang_fb,
            'cabang_twitter' => $cabang_twitter,
            'cabang_website' => $cabang_website,
            'data_email'     => $data_email
        );

        $mpdf = new mPDF('utf-8','A4', 0, '', 10, 10, 15, 10, 0, 0);

        $mpdf->writeHTML($this->load->view('klaim/proses_klaim/print_pernyataan', $body, true));
        $mpdf->AddPage();
        $mpdf->writeHTML($this->load->view('klaim/proses_klaim/print_kwitansi', $body, true));
        

        $mpdf->Output('Kwitansi_'.$data_kwitansi->no_kwitansi.'.pdf', 'I'); 
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */