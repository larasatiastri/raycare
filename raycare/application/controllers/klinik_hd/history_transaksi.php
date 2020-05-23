<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class History_transaksi extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '97b873720821adf08294e2b755f71f27';                  // untuk check bit_access

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

      
        $this->load->model('apotik/item_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/poliklinik_tindakan_m');
        $this->load->model('master/poliklinik_harga_tindakan_m');
        $this->load->model('master/transaksi_dokter_m');
        $this->load->model('master/transaksi_dokter2_m');
        $this->load->model('master/transaksi_dokter3_m');
        $this->load->model('master/transaksi_dokter4_m');
        $this->load->model('master/user_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/dokumen_m');
        $this->load->model('master/pasien_dokumen_m');
        $this->load->model('master/pasien_dokumen_detail_m');
        $this->load->model('master/pasien_dokumen_detail_tipe_m');
        $this->load->model('master/penjamin_dokumen_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('klinik_hd/obat_m');
        $this->load->model('klinik_hd/resep_racikan_obat_m');
        $this->load->model('klinik_hd/klaim_m');
        $this->load->model('klinik_hd/rujukan_m');
        $this->load->model('klinik_hd/paket_m');
        $this->load->model('klinik_hd/sejarah_item_m');
        $this->load->model('klinik_hd/observasi_m');
        $this->load->model('klinik_hd/observasi_history_m');
        $this->load->model('klinik_hd/item_tersimpan_m');
        $this->load->model('klinik_hd/item_digunakan_m');
        $this->load->model('klinik_hd/tagihan_paket_m');
        $this->load->model('klinik_hd/view_tagihan_paket_m');
        $this->load->model('klinik_hd/tindakan_hd_m');
        $this->load->model('klinik_hd/tindakan_hd_history_m');
        $this->load->model('klinik_hd/tindakan_hd_invoice_m');
        $this->load->model('klinik_hd/pasien_klaim_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_history_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual2_m');
        $this->load->model('klinik_hd/bed_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_history_m');
        $this->load->model('klinik_hd/tindakan_hd_diagnosa_m');
        $this->load->model('klinik_hd/pendaftaran_tindakan_m');
        $this->load->model('klinik_hd/transaksi_diproses_m');

        $this->load->model('klinik_hd/resep_obat_racikan_m');
        $this->load->model('klinik_hd/resep_obat_racikan_detail_m');
        $this->load->model('klinik_hd/resep_obat_racikan_detail_manual_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/region_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('klinik_hd/pasien_problem_m');
        $this->load->model('klinik_hd/pasien_problem_history_m');
        $this->load->model('klinik_hd/pasien_problem2_m');
        $this->load->model('klinik_hd/pasien_komplikasi_m');
        $this->load->model('klinik_hd/pasien_komplikasi_history_m');
        $this->load->model('klinik_hd/tindakan_hd_paket_m');
        $this->load->model('klinik_hd/tindakan_hd_visit_m');

        $this->load->model('klinik_hd/tindakan_hd_tindakan_m');
        $this->load->model('klinik_hd/paket_item_m');
        $this->load->model('klinik_hd/paket_tindakan_m');
        $this->load->model('master/paket_batch2_m');

        // ITEM
        $this->load->model('klinik_hd/inventory_klinik_m');
        $this->load->model('klinik_hd/tindakan_hd_item_m');
        $this->load->model('klinik_hd/tindakan_hd_item_history_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('apotik/pemisahan_item/inventory_identitas_m');

        // INVENTORY/SIMPAN_ITEM
        $this->load->model('klinik_hd/simpan_item_identitas_m');
        $this->load->model('klinik_hd/simpan_item_identitas_detail_m');
        $this->load->model('klinik_hd/simpan_item_history_m');
        $this->load->model('klinik_hd/simpan_item_history_detail_m');
        $this->load->model('klinik_hd/simpan_item_history_identitas_m');
        $this->load->model('klinik_hd/tindakan_hd_item_identitas_m');
        $this->load->model('klinik_hd/simpan_item_history_identitas_detail_m');
        $this->load->model('klinik_hd/tindakan_hd_item_identitas_detail_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_history_m');


        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('apotik/inventory_m');
        // $this->load->model('apotik/inventory_identitas_m');
        $this->load->model('apotik/inventory_identitas_detail_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');  

        $this->load->library('mpdf/mpdf.php');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/history_transaksi/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
       
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Histori Transaksi', $this->session->userdata('language')), 
            'header'         => translate('Histori Transaksi', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/history_transaksi/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_histori($id=null)
    {        
        $user_level_id = $this->session->userdata('level_id');
        $result = $this->transaksi_diproses_m->get_datatable2(array('3'));
    //die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die_dump($records);
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            
            
            $data_asses_new = '<a title="'.translate('Print Assesment New', $this->session->userdata('language')).'" target="_blank" name="print" href="'.base_url().'klinik_hd/history_transaksi/print_assesment_new/'.$row['id'].'/'.$row['pasienid'].'" class="btn default search-item"><i class="fa fa-print"></i></a>';
            $data_doc = '<a title="'.translate('Print Dokumen Pasien', $this->session->userdata('language')).'" target="_blank" name="print_dokumen" href="'.base_url().'klinik_hd/history_transaksi/modal_dokumen/'.$row['pasienid'].'/'.$row['penjamin_id'].'" class="btn btn-primary" data-toggle="modal" data-target="#modal_dokumen_pasien" ><i class="fa fa-print"></i></a>';
            $data_persetujuan = '<a title="'.translate('Print Persetujuan', $this->session->userdata('language')).'" target="_blank" name="print_perstujuan" href="'.base_url().'klinik_hd/history_transaksi/print_persetujuan/'.$row['id'].'/'.$row['pasienid'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
            $data_detail = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/history_transaksi/detail_history/'.$row['id'].'/'.$row['pasienid'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
            $data_sep = '<a title="'.translate('Print SEP', $this->session->userdata('language')).'" target="_blank" name="print" href="'.base_url().'klinik_hd/history_transaksi/print_sep/'.$row['id'].'/'.$row['pasienid'].'" class="btn default search-item"><i class="fa fa-envelope"></i></a>';
            $data_inv = '<a title="'.translate('Print Invoice', $this->session->userdata('language')).'" target="_blank" name="print" href="'.base_url().'klinik_hd/history_transaksi/print_invoice/'.$row['id'].'/'.$row['pasienid'].'" class="btn btn-primary search-item hidden"><i class="fa fa-money"></i></a>';
            $data_upload = '<a title="'.translate('Upload Invoice', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_upload" name="upload" href="'.base_url().'klinik_hd/history_transaksi/upload_invoice/'.$row['id'].'" class="btn btn-primary upload"><i class="fa fa-upload"></i></a>';
            $data_upload_setuju = '<a title="'.translate('Upload Persetujuan', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_upload_setuju" name="upload" href="'.base_url().'klinik_hd/history_transaksi/upload_persetujuan/'.$row['id'].'" class="btn btn-danger upload"><i class="fa fa-upload"></i></a>';
            $data_upload_sep = '<a title="'.translate('Upload SEP', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_upload_sep" name="upload" href="'.base_url().'klinik_hd/history_transaksi/upload_sep/'.$row['id'].'" class="btn btn-success upload"><i class="fa fa-upload"></i></a>';
                //tambahkan data ke tabel fitur_tombol. Field page="klinik_hd_history_transaksi", button="print_asses"
                //tambahkan data ke tabel fitur_tombol. Field page="klinik_hd_history_transaksi", button="print_doc"
                //tambahkan data ke tabel fitur_tombol. Field page="klinik_hd_history_transaksi", button="detail"
            $action = restriction_button($data_asses_new,$user_level_id,'klinik_hd_history_transaksi','print_asses').restriction_button($data_doc,$user_level_id,'klinik_hd_history_transaksi','print_doc').restriction_button($data_persetujuan,$user_level_id,'klinik_hd_history_transaksi','print_persetujuan').restriction_button($data_upload_setuju,$user_level_id,'klinik_hd_history_transaksi','upload_persetujuan').restriction_button($data_upload_sep,$user_level_id,'klinik_hd_history_transaksi','upload_sep').restriction_button($data_upload,$user_level_id,'klinik_hd_history_transaksi','upload_invoice');
               


            // $action='<a title="'.translate('Print Assesment', $this->session->userdata('language')).'" target="_blank" name="print" href="'.base_url().'klinik_hd/history_transaksi/print_assesment/'.$row['id'].'/'.$row['pasienid'].'" class="btn default search-item"><i class="fa fa-print"></i></a><a title="'.translate('Print Dokumen Pasien', $this->session->userdata('language')).'" target="_blank" name="print_dokumen" href="'.base_url().'klinik_hd/history_transaksi/print_dokumen/'.$row['id'].'/'.$row['pasienid'].'" class="btn btn-primary"><i class="fa fa-print"></i></a><a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/history_transaksi/detail_history/'.$row['id'].'/'.$row['pasienid'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
                         
       
            $output['data'][] = array(
                $row['id'].'/'.$row['pasienid'],
                 '<div class="text-left inline-button-table">'.$row['no_transaksi'].'</div>',
                 '<div class="text-center inline-button-table">'.date('d M Y',strtotime($row['tanggal'])).'</div>',
                 '<div class="text-center">'.$row['no_member'].'</div>',
                 '<div class="text-left inline-button-table">'.$row['nama'].'</div>',
                 '<div class="text-left inline-button-table">'.$row['nama1'].'</div>',
                 '<div class="text-left">'.$row['nama3'].'</div>',
                 '<div class="text-center">'.$row['berat_awal'].' Kg </div>',
                 '<div class="text-center">'.$row['berat_akhir'].' Kg </div>',
                 '<div class="text-center inline-button-table">'.$action.'</div>' ,
               
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function print_assesment_new($tindakan_hd_id, $pasien_id)
    {
        if(restriction_function($this->session->userdata('level_id'),'klinik_hd_history_transaksi','print_asses'))
        {
            $this->load->library('mpdf/mpdf.php');

            $form_tindakan_hd            = $this->tindakan_hd_history_m->get($tindakan_hd_id);
            $form_pasien                 = $this->pasien_m->get($pasien_id);
            $form_tindakan_hd_penaksiran = $this->tindakan_hd_penaksiran_history_m->get_by(array('tindakan_hd_id' => $form_tindakan_hd->id));
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


            $body = array(

                "tindakan_hd_id"              => $tindakan_hd_id, 
                "pasien_id"                   => $pasien_id, 
                "form_tindakan_hd"            => object_to_array($form_tindakan_hd), 
                "form_pasien"                 => object_to_array($form_pasien), 
                "form_tindakan_hd_penaksiran" => object_to_array($form_tindakan_hd_penaksiran[0]), 
                'pasien_problem'              => object_to_array($pasien_problem),
                'pasien_komplikasi'           => object_to_array($pasien_komplikasi),
               // 'form_hemodialisis_history'   => object_to_array($form_hemodialisis_history),
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
            

            $mpdf->Output('Assessment '.$form_pasien->no_member.'.pdf', 'I'); 
        }
    }

    public function print_assesment($tindakan_hd_id, $pasien_id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'klinik_hd_history_transaksi','print_asses'))
        {

            $this->load->library('mpdf/mpdf.php');

            $form_tindakan_hd            = $this->tindakan_hd_history_m->get($tindakan_hd_id);
            $form_pasien                 = $this->pasien_m->get($pasien_id);
            $form_tindakan_hd_penaksiran = $this->tindakan_hd_penaksiran_history_m->get_by(array('tindakan_hd_id' => $form_tindakan_hd->id));
            $pasien_problem              = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
            $pasien_komplikasi           = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
            $form_hemodialisis_history   = $this->tindakan_hd_history_m->get_hemodialisis_history($pasien_id, $form_tindakan_hd->tanggal)->result();
            $form_observasi              = $this->observasi_history_m->get_by(array('transaksi_hd_id' => $tindakan_hd_id,'is_active' => 1));
            $form_medicine               = $this->tindakan_hd_item_m->get_is_assessment($tindakan_hd_id)->result();
            $form_cabang                 = $this->cabang_m->get($form_tindakan_hd->cabang_id);
            $form_cabang_alamat          = $this->cabang_alamat_m->get_by(array('cabang_id' => $form_tindakan_hd->cabang_id, 'is_active' => 1, 'is_primary' => 1), true);
            $form_cabang_telepon         = $this->cabang_telepon_m->get_by(array('cabang_id' => $form_tindakan_hd->cabang_id, 'is_active' => 1));
            $cabang_sosmed               = $this->cabang_sosmed_m->get_by(array('is_active' => 1, 'cabang_id' => $form_tindakan_hd->cabang_id));

            $kelurahan_cabang = $this->region_m->get_by(array('id' => $form_cabang_alamat->kelurahan_id), true)->nama;
            $kecamatan_cabang = $this->region_m->get_by(array('id' => $form_cabang_alamat->kecamatan_id), true)->nama;
            $kota_cabang      = $this->region_m->get_by(array('id' => $form_cabang_alamat->kota_id), true)->nama;
            $alamat_subject   = $this->subjek_m->get($form_cabang_alamat->subjek_id);


            // die_dump($cabang_sosmed);

            $body = array(

                "tindakan_hd_id"              => $tindakan_hd_id, 
                "pasien_id"                   => $pasien_id, 
                "form_tindakan_hd"            => object_to_array($form_tindakan_hd), 
                "form_pasien"                 => object_to_array($form_pasien), 
                "form_tindakan_hd_penaksiran" => object_to_array($form_tindakan_hd_penaksiran[0]), 
                'pasien_problem'              => object_to_array($pasien_problem),
                'pasien_komplikasi'           => object_to_array($pasien_komplikasi),
                'form_hemodialisis_history'   => object_to_array($form_hemodialisis_history),
                'form_observasi'              => object_to_array($form_observasi),
                'form_medicine'               => (count($form_medicine))?object_to_array($form_medicine):'0',
                
                'form_cabang'         => object_to_array($form_cabang),
                'form_cabang_alamat'  => object_to_array($form_cabang_alamat),
                'alamat_subject'      => object_to_array($alamat_subject),
                'kelurahan_cabang'    => object_to_array($kelurahan_cabang),
                'kecamatan_cabang'    => object_to_array($kecamatan_cabang),
                'kota_cabang'         => object_to_array($kota_cabang),
                'form_cabang_telepon' => object_to_array($form_cabang_telepon),
                'cabang_sosmed'       => object_to_array($cabang_sosmed),

            );

            $mpdf = new mPDF('utf-8','A4', 0, '', 2, 2, 2, 0, 0, 0);
            $mpdf->writeHTML($this->load->view('klinik_hd/transaksi_dokter/print_assessment', $body, true));
            $mpdf->AddPage();
            $mpdf->writeHTML($this->load->view('klinik_hd/transaksi_dokter/print_assessment2', $body, true));

            $mpdf->Output('Assessment '.$form_pasien->no_member.'.pdf', 'I'); 
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('klinik_hd/history_transaksi');
        }

    }

    public function print_persetujuan($tindakan_hd_id, $pasien_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/pasien_hubungan_m');
        $this->load->model('master/pasien_hubungan_alamat_m');
        $this->load->model('master/pasien_hubungan_telepon_m');

        $tindakan_hd     = $this->tindakan_hd_history_m->get($tindakan_hd_id);
        $assesment       = $this->tindakan_hd_penaksiran_history_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id), true);
        $pendaftaran     = $this->pendaftaran_tindakan_history_m->get($tindakan_hd->pendaftaran_tindakan_id);

        $pasien_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $pasien_id, 'penjamin_id' => $tindakan_hd->penjamin_id), true);
        
        $penanggungjawab = $pendaftaran->penanggung_jawab_id;
        
        $pasien          = $this->pasien_m->get($pasien_id);
        $pasien          = object_to_array($pasien);
        
        $pj             = $pasien;
        $pj_alamat      = $this->pasien_alamat_m->get_data($pasien['id'])->result_array();
        $pj_data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $pj_alamat[0]['kode_lokasi']),true);
        $pj_telepon     = $this->pasien_telepon_m->get_data($pasien['id'])->result_array();
        $status         = translate('Pasien', $this->session->userdata('language'));

        if($penanggungjawab != 0)
        {
            $pj             = $this->pasien_hubungan_m->get($penanggungjawab);
            $pj             = object_to_array($pj);
            $pj_alamat      = $this->pasien_hubungan_alamat_m->get_data($pj['id'])->result_array();
            $pj_data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $pj_alamat[0]['kode_lokasi']),true);
            $pj_telepon     = $this->pasien_hubungan_telepon_m->get_data($pj['id'])->result_array();

            switch ($pj['tipe_pj']) {
                case 2:
                    $status = translate('Suami Pasien', $this->session->userdata('language'));
                    break;
                case 3:
                    $status = translate('Istri Pasien', $this->session->userdata('language'));
                    break;
                case 4:
                    $status = translate('Anak Pasien', $this->session->userdata('language'));
                    break;
                case 5:
                    $status = translate('Ayah Pasien', $this->session->userdata('language'));
                    break;
                case 6:
                    $status = translate('Ibu Pasien', $this->session->userdata('language'));
                    break;
                case 7:
                    $status = $pj['alias_pj'];
                    break;
            }
        }

        $form_kel_alamat_pj  = '';
        $form_kec_alamat_pj  = '';
        $form_kota_alamat_pj = '';

        if(count($pj_data_lokasi))
        {
            $form_kel_alamat_pj  = $pj_data_lokasi->nama_kelurahan;
            $form_kec_alamat_pj  = $pj_data_lokasi->nama_kecamatan;
            $form_kota_alamat_pj = $pj_data_lokasi->nama_kabupatenkota;            
        }

        $umur_pasien = date_diff(date_create($pasien['tanggal_lahir']), date_create('today'))->y.' Tahun ';

        if ($umur_pasien < 1) {
            $umur_pasien = translate('Dibawah 1 Tahun ', $this->session->userdata('language'));
        }

        $form_pekerjaan = $this->info_umum_m->get($pasien['pekerjaan_id']);
        $nama_pekerjaan = (count($form_pekerjaan)!=0)?$form_pekerjaan->nama:'-';
        
        $alamat_pasien  = $this->pasien_alamat_m->get_data($pasien['id'])->result_array();
        $data_lokasi    = $this->info_alamat_m->get_by(array('lokasi_kode' => $alamat_pasien[0]['kode_lokasi']),true);
        
        $telepon_pasien = $this->pasien_telepon_m->get_data($pasien['id'])->result_array();

        $form_kel_alamat  = '';
        $form_kec_alamat  = '';
        $form_kota_alamat = '';

        if(count($data_lokasi))
        {
            $form_kel_alamat  = $data_lokasi->nama_kelurahan;
            $form_kec_alamat  = $data_lokasi->nama_kecamatan;
            $form_kota_alamat = $data_lokasi->nama_kabupatenkota;            
        }

        $dokter = $this->user_m->get($tindakan_hd->dokter_id);
        $dokter = object_to_array($dokter);

        $body = array(
            'tindakan_hd'         => object_to_array($tindakan_hd),
            'assesment'           => object_to_array($assesment),
            'pasien'              => $pasien,
            'dokter'              => $dokter,
            'nama_pekerjaan'      => $nama_pekerjaan,
            'umur_pasien'         => $umur_pasien,
            'alamat_pasien'       => (count($alamat_pasien))?$alamat_pasien[0]:'',
            'telepon_pasien'      => (count($telepon_pasien))?$telepon_pasien[0]:'',
            'form_kel_alamat'     => $form_kel_alamat,
            'form_kec_alamat'     => $form_kec_alamat,
            'form_kota_alamat'    => $form_kota_alamat,
            'pj'                  => $pj,
            'pj_alamat'           => (count($pj_alamat))?$pj_alamat[0]:'',
            'pj_telepon'          => (count($pj_telepon))?$pj_telepon[0]:'',
            'status'              => $status,
            'pasien_penjamin'     => object_to_array($pasien_penjamin),
            'form_kel_alamat_pj'  => $form_kel_alamat_pj,
            'form_kec_alamat_pj'  => $form_kec_alamat_pj,
            'form_kota_alamat_pj' => $form_kota_alamat_pj,
            'penanggungjawab'     => $penanggungjawab
        );

        $mpdf = new mPDF('utf-8','A4', 0, '', 10, 10, 8, 0, 0, 0);
        $mpdf->writeHTML($this->load->view('klinik_hd/transaksi_dokter/print_persetujuan', $body, true));

        $mpdf->Output('surat_persetujuan_'.$tindakan_hd->no_transaksi.'.pdf', 'I'); 
    }

    public function detail_history($id=null,$pasien_id=null)
    {
        if(restriction_function($this->session->userdata('level_id'), 'klinik_hd_history_transaksi','detail'))
        {
            $assets = array();
            $config = 'assets/klinik_hd/history_transaksi/detail_histori';
            $this->config->load($config, true);
            $assets = $this->config->item('assets', $config);
            
            // die(dump( $assets['css'] ));
            $form_data_kiri=$this->tindakan_hd_history_m->detail_histori_kiri($id,$pasien_id)->result_array();
            // die(dump($this->db->last_query()));
            $form_data_kanan=$this->tindakan_hd_history_m->detail_histori_kanan($id)->result_array();

            $form_pasien = $this->pasien_m->get($pasien_id);

            $data = array(
                'title'                 =>  config_item('site_name').' | '.translate('Detail History', $this->session->userdata('language')), 
                'header'                =>  translate('Detail History', $this->session->userdata('language')), 
                'header_info'           =>  config_item('site_name'), 
                'breadcrumb'            =>  true,
                'menus'                 =>  $this->menus,
                'menu_tree'             =>  $this->menu_tree,
                'css_files'             =>  $assets['css'],
                'js_files'              =>  $assets['js'],
                'content_view'          =>  'klinik_hd/history_transaksi/detail_history',
                'form_data_kiri'        =>  $form_data_kiri,
                'form_data_kanan'       =>  $form_data_kanan,
                'form_pasien'           =>  object_to_array($form_pasien),
                'pasien_id'             =>  $pasien_id,
                'id'                    =>  $id
            );
            
            // Load the view
            $this->load->view('_layout', $data);
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('klinik_hd/history_transaksi');
        }

    }

    public function print_dokumen($tindakan_hd_id, $pasien_id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'klinik_hd_history_transaksi','print_doc'))
        {
            $this->load->model('master/penjamin_dokumen_m');
            $this->load->model('master/pasien_dokumen_m');
            $this->load->model('master/pasien_dokumen_detail_m');
            $this->load->model('master/pasien_dokumen_detail_tipe_m');
            $this->load->model('master/dokumen_m');

            $this->raycare_dokumen_pasien->RAYCARE_DOKUMEN_PASIEN("L", "mm", "A4");
            $this->raycare_dokumen_pasien->Header(1);
            $this->raycare_dokumen_pasien->Open();
            $this->raycare_dokumen_pasien->SetTitle("Judul Hardcode");
            $this->raycare_dokumen_pasien->SetAuthor("Author Hardcode");
            $this->raycare_dokumen_pasien->AliasNbPages();
            $this->raycare_dokumen_pasien->AddPage();
            $this->raycare_dokumen_pasien->SetAutoPageBreak(TRUE, 10);
            $this->raycare_dokumen_pasien->SetSubject("Cetak Dokumen Pasien");


            $tindakan = $this->tindakan_hd_m->get($tindakan_hd_id);
            $pasien = $this->pasien_m->get($pasien_id);

            $penjamin_dokumen = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $tindakan->penjamin_id));
            if(count($penjamin_dokumen)) 
            {
                $penjamin_dokumen = object_to_array($penjamin_dokumen);

                foreach ($penjamin_dokumen as $penj_dok) 
                {
                    $pasien_dokumen[] = $this->pasien_dokumen_m->get_by_data_tipe(array('pasien_id' => $pasien_id, 'dokumen_id' => $penj_dok['dokumen_id'], 'tipe' => 9))->result_array();
                }
                    // die(dump($pasien_dokumen));
                    $count = count($pasien_dokumen);
                    
                    $i=0;
                    $h = 0;
                    $w = 5;

                    for($x=0;$x<$count;$x++)
                    {
                        $width1 = 0;
                        $height1 = 0;
                        if(isset($pasien_dokumen[$x][0]))
                        {      
                        if($pasien_dokumen[$x][0]['value'] != 'doc_global/document.png')
                            {      
                            if(check_url(config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/pelengkap/'.$pasien_dokumen[$x][0]['value']) == 'HTTP/1.1 200 OK')
                            {

                                $data_dokumen = $this->dokumen_m->get($pasien_dokumen[$x][0]['dokumen_id']);
                                if($data_dokumen->tipe == 1)
                                {
                                    $image_header = config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/pelengkap/'.$pasien_dokumen[$x][0]['value'];
                                    list($width, $height, $type, $attr) = getimagesize(config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/pelengkap/'.$pasien_dokumen[$x][0]['value']);
                                    if($x < $count - 1)
                                    {
                                        if(isset($pasien_dokumen[$x+1][0]))
                                        { 
                                            if($pasien_dokumen[$x+1][0]['value'] != 'doc_global/document.png')
                                            {
                                                $data_dokumen2 = $this->dokumen_m->get($pasien_dokumen[$x+1][0]['dokumen_id']);
                                                if($data_dokumen2->tipe == 1)
                                                {
                                                    list($width1, $height1, $type1, $attr1) = getimagesize(config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/pelengkap/'.$pasien_dokumen[$x+1][0]['value']);                    
                                                }
                                                else
                                                {
                                                    list($width1, $height1, $type1, $attr1) = getimagesize(config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/rekam_medis/'.$pasien_dokumen[$x+1][0]['value']);
                                                }
                                            }
                                        }
                                    }
                                }
                                elseif($data_dokumen->tipe == 2)
                                {
                                    $image_header = config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/rekam_medis/'.$pasien_dokumen[$x][0]['value'];
                                    list($width, $height, $type, $attr) = getimagesize(config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/rekam_medis/'.$pasien_dokumen[$x][0]['value']);
                                    if($x < $count - 1)
                                    {
                                        if(isset($pasien_dokumen[$x+1][0]))
                                        { 
                                            if($pasien_dokumen[$x+1][0]['value'] != 'doc_global/document.png')
                                            {
                                                $data_dokumen2 = $this->dokumen_m->get($pasien_dokumen[$x+1][0]['dokumen_id']);
                                                if($data_dokumen2->tipe == 1)
                                                {
                                                    list($width1, $height1, $type1, $attr1) = getimagesize(config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/pelengkap/'.$pasien_dokumen[$x+1][0]['value']);                    
                                                }
                                                else
                                                {
                                                    list($width1, $height1, $type1, $attr1) = getimagesize(config_item('url_core').'assets/mb/pages/master/pasien/images/'.$pasien->no_member.'/dokumen/rekam_medis/'.$pasien_dokumen[$x+1][0]['value']);
                                                }
                                            }
                                        }
                                    }
                                }


                                $h = ($height>120)? $height : 120;
                                $h1 = ($height1>120)? $height1 : 120;   

                                if ($width > $height) 
                                {
                                    if($width > 900)
                                    {
                                        $this->raycare_dokumen_pasien->centreImageWithoutRotate($image_header);
                                    }           
                                    else
                                    {
                                        $this->raycare_dokumen_pasien->Image($image_header,5, $w);
                                    }

                                    $w = ($height > 145)?$w + ($h - 140):$w + ($h - 80);

                                    if((($w + $width1 ) > 520) && $x != $count-1)
                                    {       
                                        $w = 5;
                                        $this->raycare_dokumen_pasien->AddPage();
                                    }
                                    elseif((($w + $width1 ) < 500) && $x == $count-1)
                                    {       
                                        $w = 5;                                           
                                    }
                                } 
                                elseif($width < $height)
                                {
                                    if($height > 900)
                                    {
                                        $this->raycare_dokumen_pasien->centreImage($image_header);
                                    }                                           
                                    else
                                    {
                                        $this->raycare_dokumen_pasien->Rotate(90,55,150);       
                                        $this->raycare_dokumen_pasien->Image($image_header,5, 100);
                                        $this->raycare_dokumen_pasien->Rotate(0);
                                    }

                                    $w = $w + ($h - 80);

                                    if((($h1 + $w ) > 250) && $x != $count-1)
                                    {       
                                        $w = 5;
                                        $this->raycare_dokumen_pasien->AddPage();
                                    }
                                    elseif((($h1 + $w ) < 250) && $x == $count-1)
                                    {
                                        $w = 5;
                                    }
                                }       
                            }
                            else
                            {
                                $this->raycare_dokumen_pasien->SetXY($w,40);
                                $this->raycare_dokumen_pasien->SetFont("Arial", "", 12);
                                $this->raycare_dokumen_pasien->Cell(16, 2, "Tidak ada dokumen yang dapat dicetak, silahkan upload kembali dokumen yang akan dicetak.", '', 0, 'L');
                                $this->raycare_dokumen_pasien->AddPage();
                            }
                        }
                        }
                    }

                    // $this->raycare_dokumen_pasien->AddPage(); 
                    $this->raycare_dokumen_pasien->Output(date('Y-M-d')."-".str_replace(' ', '_', $pasien->nama)."-".$tindakan->no_transaksi.".pdf", "I");
                   
                
            }
        }
    }

    public function getsejarah2()
    {
        
        
        $tindakan_id=$this->input->post('id');
        
       
        $rows_assesment = $this->tindakan_hd_history_m->get_by_transaction_id2($tindakan_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);

        
      
       
        echo json_encode($this->hemodialisis($rows_assesment));
    }

    public function hemodialisis($rows_assesment){
        $assesment = array(
                    'date_value'                   => date('d F Y',strtotime($rows_assesment[0]['tanggal'])),
                    'time_value'                   => $rows_assesment[0]['waktu'],
                    'alergic_medicine'             => $rows_assesment[0]['alergic_medicine'],    
                    'alergic_food'                 => $rows_assesment[0]['alergic_food'],    
                    'assessment_cgs_value'         => $rows_assesment[0]['assessment_cgs'],
                    'medical_diagnose_value'       => $rows_assesment[0]['medical_diagnose'],
                    'time_of_dialysis_value'       => $rows_assesment[0]['time_of_dialysis'],
                    'quick_of_blood_value'         => $rows_assesment[0]['quick_of_blood'],
                    'quick_of_dialysis_value'      => $rows_assesment[0]['quick_of_dialysis'],
                    'uf_goal_value'                => $rows_assesment[0]['uf_goal'],
                    'heparin_reguler_value'        => $rows_assesment[0]['heparin_reguler'],
                    'heparin_minimal_value'        => $rows_assesment[0]['heparin_minimal'],
                    'heparin_free_value'           => $rows_assesment[0]['heparin_free'],
                    'dose_value'                   => $rows_assesment[0]['dose'],
                    'first_value'                  => $rows_assesment[0]['first'],
                    'maintenance_value'            => $rows_assesment[0]['maintenance'],
                    'hours_value'                  => $rows_assesment[0]['hours'],
                    'machine_no_value'             => $rows_assesment[0]['machine_no'],
                    'dialyzer_new_value'           => $rows_assesment[0]['dialyzer_new'],
                    'dialyzer_reuse_value'         => $rows_assesment[0]['dialyzer_reuse'],
                    'dialyzer_value'               => $rows_assesment[0]['dialyzer'],
                    'bn_dialyzer_value'               => $rows_assesment[0]['bn_dialyzer'],
                    'ba_avshunt_value'             => $rows_assesment[0]['ba_avshunt'],
                    'ba_femoral_value'             => $rows_assesment[0]['ba_femoral'],
                    'ba_catheter_value'            => $rows_assesment[0]['ba_catheter'],
                    'dialyzer_type_value'          => $rows_assesment[0]['dialyzer_type'],
                    'remaining_of_priming_value'   => $rows_assesment[0]['remaining_of_priming'],
                    'wash_out_value'               => $rows_assesment[0]['wash_out'],
                    'drip_of_fluid_value'          => $rows_assesment[0]['drip_of_fluid'],
                    'blood_value'                  => $rows_assesment[0]['blood'],
                    'drink_value'                  => $rows_assesment[0]['drink'],
                    'vomiting_value'               => $rows_assesment[0]['vomiting'],
                    'urinate_value'                => $rows_assesment[0]['urinate'],
                    'transfusion_type_value'       => $rows_assesment[0]['transfusion_type'],
                    'transfusion_qty_value'        => $rows_assesment[0]['transfusion_qty'],
                    'transfusion_blood_type_value' => $rows_assesment[0]['transfusion_blood_type'],
                    'serial_number_value'          => $rows_assesment[0]['serial_number'],
                    'laboratory_value'             => $rows_assesment[0]['laboratory'],
                    'ecg_value'                    => $rows_assesment[0]['ecg'],
                    'priming_value'                => $rows_assesment[0]['priming'],
                    'initiation_value'             => $rows_assesment[0]['initiation'],
                    'termination_value'            => $rows_assesment[0]['termination'],
                     'tindakan_hd_id'            => $rows_assesment[0]['tindakan_hd_id']
                    
                );
            return $assesment;
    }  

    public function listing_observasi($id=null)
    {        
       
        $result = $this->observasi_history_m->get_datatable($id);

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
           $action='';
           if($row['is_active']==1){
             $action='<a title="'.translate('Edit', $this->session->userdata('language')).'"   data-id="'.$row['id'].'" name="viewobservasi[]"  data-target="#ajax_notes4" data-toggle="modal" class="btn grey-cascade"><i class="fa fa-edit"></i></a>
             <a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="deleted[]"  data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i></a>';
         }else{
             $action='<a title="'.translate('Restore', $this->session->userdata('language')).'"  name="restore1[]"  data-id="'.$row['id'].'" class="btn yellow"><i class="fa fa-check"></i></a>';
         }
            
       
            $output['data'][] = array(
                 
                '<div class="text-center">'.date("H:i:s", strtotime($row['waktu_pencatatan'])).'</div>',
               '<div class="text-center">'.$row['tekanan_darah_1'].'/'.$row['tekanan_darah_2'].'</div>',
                '<div class="text-center">'.$row['ufg'].'</div>',
                '<div class="text-center">'.$row['ufr'].'</div>',
                '<div class="text-center">'.$row['ufv'].'</div>',
                '<div class="text-center">'.$row['qb'].'</div>',
                '<div class="text-center">'.$row['nama'].'</div>',
                 '<div class="text-center">'.$row['keterangan'].'</div>',
                 '<div class="text-center inline-button-table">'.$action.'</div>',
                  '<div class="text-center">'.$row['is_active'].'</div>'
                               
                
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_histori_detail_resep($id=null)
    {        
         
        $result = $this->tindakan_resep_obat_history_m->get_datatable2($id);

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
            
            $output['data'][] = array(
                    '<div class="text-left">'.$row['nama'].'</div>',
                    '<div class="text-center">'.$row['tipe_item'].'</div>',
                    '<div class="text-left">'.$row['jumlah'].' '.$row['nama_satuan'].'</div>',
                    
                    '<div class="text-left">'.$row['dosis'].'</div>',
               
              
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_histori_detail_resep_racikan($id=null)
    {        
         
        $result = $this->tindakan_resep_obat_manual2_m->get_datatable($id);

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
            $impWord2='';
            $notes = $row['keterangan'];
          
            $words = explode(' ', $notes);
          
            $impWords = implode(' ', array_splice($words, 0, 2));
            
            if(count($words) <= 2 ){
                $impWord2='';
            }else{
                $impWord2=' ... <a class="show-notes"   data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a>';
            }
            //$preNotes =  '<p>'.$impWords.'</p>';

            //  $notes = $row['keterangan'];
          
            // $words = explode(' ', $notes);
          
            // $impWords = implode(' ', array_splice($words, 0, 2));
            
            // $preNotes =  '<p>'.$impWords.' ... <a class="show-notes"   data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            
            
            $output['data'][] = array(
                    $impWords.$impWord2 

            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_tersimpan($id=null)
    {        

       
        $result = $this->item_tersimpan_m->get_datatable($id);

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
           
           $action='';
           if($row['status']==1)
           {
                $action=' 
             <a title="'.translate('Pakai Item', $this->session->userdata('language')).'"  data-confirm="'.translate('Pakai Item ini?', $this->session->userdata('language')).'" name="pakai[]"  data-id="'.$row['simpan_item_id'].'" class="btn green"><i class="fa fa-plus"></i></a>';
       
           }else  if($row['status']==2){
                $action=' 
             <a title="'.translate('Batal Menggunakan Item', $this->session->userdata('language')).'"  data-confirm="'.translate('Batalkan Penggunaan Item ini?', $this->session->userdata('language')).'" name="batal[]"  data-id="'.$row['simpan_item_id'].'"  class="btn red"><i class="fa fa-times"></i></a>';
       
           }
             
            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['nama'].'</div>',
               
                '<div class="text-center">'.$action.'</div>',
                               
                
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_digunakan($id=null)
    {        

       $result = $this->tindakan_hd_item_history_m->get_datatable($id);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
    // die_dump($records);
        foreach($records->result_array() as $row)
        {       
            // SELECT * FROM tindakan_hd_item where waktu = '2015-03-26 13:00:00' and item_id = 1 and tipe_pemberian = 1 and item_satuan_id = 4 and created_by = 6
            $id = '';
            if ($row['tipe_pemberian'] == 1) 
            {
                // Join dengan paket
                $tindakan_paket = $this->tindakan_hd_paket_m->get($row['transaksi_pemberian_id']);
                $paket = $this->paket_m->get($tindakan_paket->paket_id);
                $tipe = $paket->nama.' ['.$paket->kode.']';
            } 
            elseif ($row['tipe_pemberian'] == 2) 
            {
                $tipe = "Simpan Item History";
            } 
            else 
            {
                $tipe = "Diluar Paket";
            }

            if($row['is_identitas'] == 1)
            {
                $tindakan_hd = $this->tindakan_hd_item_history_m->get_by(array('waktu' => $row['waktu'], 'item_id' => $row['item_id'], 'item_satuan_id' => $row['item_satuan_id'], 'created_by' => $row['created_by']));
                if(count($tindakan_hd))
                {
                    foreach ($tindakan_hd as $hd) 
                    {
                        $id .= $hd->id.',';
                    }
                    $id = urlencode(base64_encode($id));
                }
                $col_jumlah = '<div class="col-md-12">
                            <div class="input-group">
                                <input class="form-control text-center" style="background: transparent; border: 0px;" value="'.$row['jumlah'].' '.$row['item_satuan_nama'].'">
                                <span class="input-group-btn">
                                    <a class="btn btn-primary" href="'.base_url().'klinik_hd/edit_transaksi/modal_view_item/'.$id.'" data-id="'.$row['id'].'" data-target="#modal_view_item" data-toggle="modal">
                                        <i class="fa fa-info"></i>
                                    </a>
                                </span>
                            </div>                
                        </div>';
            }
            else
            {
                $col_jumlah = '<div class="col-md-12">
                                <input class="form-control text-center" style="background: transparent; border: 0px;" value="'.$row['jumlah'].' '.$row['item_satuan_nama'].'">
                                                
                        </div>';
            }

                            
            $action = '<a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="delete[]"  data-id="'.$row['id'].'" class="btn red-intense"><i class="fa fa-times"></i></a>';
            
            $output['data'][] = array(
                 
                '<div class="text-center">'.date('H:i', strtotime($row['waktu'])).'</div>',
                '<div class="text-center">'.$row['item_nama'].'</div>',
                '<div class="text-center">'.$tipe.'</div>',
                '<div class="text-center">'.$col_jumlah.'</div>',
                '<div class="text-center">'.$row['user_nama'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );
        }

        echo json_encode($output);
    }

     public function listing_paket_tagihan($id=null)
    {        

       

        $result = $this->tagihan_paket_m->get_datatable($id);

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
           
           $action=' 
             <a title="'.translate('Lihat', $this->session->userdata('language')).'"   name="viewtagihanpaket[]" data-id="'.$row['paket_id'].'" data-transaksiid="'.$id.'" data-paketname="'.$row['nama'].'"  class="btn btn-primary"><i class="fa fa-search"></i></a>';
       
             $tipe='';
             if($row['tipe']==1)
             {
                $tipe='Obat';
             }else{
                 $tipe='Alat Kesehatan';
             }
            $output['data'][] = array(
                 
                
                 '<div class="text-center">'.$tipe.'</div>',
                 
                '<div class="text-center">'.$row['nama'].'</div>',
               
                '<div class="text-center">'.$action.'</div>'
                               
                
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_view_tagihan_paket($transaksiid=null,$paketid=null)
    {        

       
        $result = $this->view_tagihan_paket_m->get_datatable($transaksiid,$paketid);

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
            $i++;
            $output['data'][] = array(
                 
                '<div class="text-center">'.$i.'</div>',
               '<div class="text-center">'.$row['nama'].'</div>',
               '<div class="text-center">'.$row['jumlah'].'</div>',
               '<div class="text-center">'.$row['digunakan'].'</div>',
               
               '<div class="text-center">'.($row['jumlah']-$row['digunakan']).'</div>',
               
                 
            );
            
        }

        echo json_encode($output);
    }

    public function getproblem()
    {
        
        
        $tindakan_id=$this->input->post('tindakan_id');
        $pasien_id=$this->input->post('pasien_id');
       
         
        $rows_assesment = $this->tindakan_hd_history_m->getproblem($tindakan_id,$pasien_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);

        
      
       
        echo json_encode($rows_assesment);
    }

     public function getkomplikasi()
    {
        
        
        $tindakan_id=$this->input->post('tindakan_id');
        $pasien_id=$this->input->post('pasien_id');
       
         
        $rows_assesment = $this->tindakan_hd_history_m->getkomplikasi($tindakan_id,$pasien_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);

        
      
       
        echo json_encode($rows_assesment);
    }

    public function print_sep($tindakan_hd_id,$pasien_id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'klinik_hd_history_transaksi','print_sep'))
        {
            $mpdf = new mPDF('arial',array(210,93), 1, '', 0, 0, 7, 2, 0, 0);
            
            $data = array(
                'tindakan_hd_id' => $tindakan_hd_id,
                'pasien_id' => $pasien_id,
            );


            $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/pdf/print_sep', $data,true));

            $mpdf->Output('sep_'.date('Y-m-d H:i:s').'.pdf', 'I');  
        } 
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('klinik_hd/history_transaksi');
        } 
    }

    public function print_invoice($tindakan_hd_id,$pasien_id)
    {
        
        $mpdf = new mPDF('arial',array(95,230), 1, '', 0, 0, 7, 2, 0, 0);
        
        $data = array(
            'tindakan_hd_id' => $tindakan_hd_id,
            'pasien_id' => $pasien_id,
        );


        $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/pdf/print_invoice', $data,true));

        $mpdf->Output('sep_'.date('Y-m-d H:i:s').'.pdf', 'I');    
    }

    public function upload_invoice($tindakan_hd_id)
    {
        $data_tindakan = $this->tindakan_hd_history_m->get($tindakan_hd_id);
        $data_pasien = $this->pasien_m->get($data_tindakan->pasien_id);
        $data_invoice = $this->tindakan_hd_invoice_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));

        $body = array(
            'data_invoice'  => $data_invoice,
            'data_tindakan' => object_to_array($data_tindakan),
            'data_pasien'   => object_to_array($data_pasien)
        );

        $this->load->view('klinik_hd/history_transaksi/upload_invoice', $body);
    }

    public function upload_persetujuan($tindakan_hd_id)
    {
        $data_tindakan = $this->tindakan_hd_history_m->get($tindakan_hd_id);
        $data_pasien = $this->pasien_m->get($data_tindakan->pasien_id);

        $body = array(
            
            'data_tindakan' => object_to_array($data_tindakan),
            'data_pasien'   => object_to_array($data_pasien)
        );

        $this->load->view('klinik_hd/history_transaksi/upload_persetujuan', $body);
    }

    public function upload_sep($tindakan_hd_id)
    {
        $data_tindakan = $this->tindakan_hd_history_m->get($tindakan_hd_id);
        $data_pasien = $this->pasien_m->get($data_tindakan->pasien_id);

        $body = array(
            
            'data_tindakan' => object_to_array($data_tindakan),
            'data_pasien'   => object_to_array($data_pasien)
        );

        $this->load->view('klinik_hd/history_transaksi/upload_sep', $body);
    }

    public function get_invoice()
    {
        if($this->input->is_ajax_request())
        {
            $tindakan_hd_id = $this->input->post('tindakan_hd_id');

            $response = new stdClass;
            $response->success = false;

            $data_invoice = $this->tindakan_hd_invoice_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id, 'is_active' => 1));
            if(count($data_invoice))
            {
                $response->success = true;
                $response->rows = $data_invoice;
            }

            die(json_encode($response));
        }
    }

    public function save_upload()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Invoice untuk tindakan ini gagal ditambahkan', $this->session->userdata('language'));

            $array_input = $this->input->post();

            $upload = $array_input['upload'];
            foreach ($upload as $row) 
            {
                if($row['id'] == '')
                {
                    $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/images/'.$array_input['no_transaksi'];
                    if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                    $temp_filename = $row['url'];

                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = str_replace(' ', '_', $row['no_invoice']).$extenstion;
                    $real_file = $array_input['no_transaksi'].'/'.$new_filename;

                    // if (file_exists(FCPATH.config_item('user_img_temp_dir_new').$temp_filename) && is_file(FCPATH.config_item('user_img_temp_dir_new').$temp_filename)){
                         copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice').$real_file);

                    // }
                    
                    $data = array(
                        'tindakan_hd_id' => $array_input['tindakan_hd_id'],
                        'no_invoice'     => $row['no_invoice'],
                        'url'            => $real_file,
                        'is_active'      => 1
                    );

                    $tindakan_invoice_id = $this->tindakan_hd_invoice_m->save($data);
                    if($tindakan_invoice_id)
                    {
                        $response->success = true;
                        $response->msg = translate('Invoice untuk tindakan ini berhasil ditambahkan',$this->session->userdata('language'));
                    }
                }
                elseif($row['id'] != '')
                {
                    if($row['is_active'] == 1)
                    {
                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/images/'.$array_input['no_transaksi'];
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $row['url'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = str_replace(' ', '_', $row['no_invoice']).$extenstion;
                        $real_file = $array_input['no_transaksi'].'/'.$new_filename;

                        // if (file_exists(FCPATH.config_item('user_img_temp_dir_new').$temp_filename) && is_file(FCPATH.config_item('user_img_temp_dir_new').$temp_filename)){

                            copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice').$real_file);
                        // }
                        $data = array(
                            'tindakan_hd_id' => $array_input['tindakan_hd_id'],
                            'no_invoice'     => $row['no_invoice'],
                            'url'            => $real_file,
                            'is_active'      => 1
                        );

                        $tindakan_invoice_id = $this->tindakan_hd_invoice_m->save($data, $row['id']);
                        if($tindakan_invoice_id)
                        {
                            $response->success = true;
                            $response->msg = translate('Invoice untuk tindakan ini berhasil diubah',$this->session->userdata('language'));
                        }
                    }
                    elseif($row['is_active'] == 0)
                    {
                        $data = array(
                            'is_active'      => 0
                        );

                        $tindakan_invoice_id = $this->tindakan_hd_invoice_m->save($data, $row['id']);
                        if($tindakan_invoice_id)
                        {
                            $response->success = true;
                            $response->msg = translate('Invoice untuk tindakan ini berhasil dihapus',$this->session->userdata('language'));
                        }
                    }
                }
            }

            die(json_encode($response));

        }
    }

    public function save_upload_persetujuan()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Persetujuan untuk tindakan ini gagal ditambahkan', $this->session->userdata('language'));

            $array_input = $this->input->post();

            $upload = $array_input['upload'];
            foreach ($upload as $row) 
            { 
                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/images/'.$array_input['no_transaksi'];
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $row['url'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $real_file = $array_input['no_transaksi'].'/'.$row['url'];

                // if (file_exists(FCPATH.config_item('user_img_temp_dir_new').$temp_filename) && is_file(FCPATH.config_item('user_img_temp_dir_new').$temp_filename)){
                     copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice').$real_file);

                // }
                
                $data = array(
                    'url_persetujuan' => $row['url']
                );

                $persetujuan_id = $this->tindakan_hd_history_m->save($data,$array_input['tindakan_hd_id']);
                if($persetujuan_id)
                {
                    $response->success = true;
                    $response->msg = translate('Persetujuan untuk tindakan ini berhasil ditambahkan',$this->session->userdata('language'));
                }
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

            $upload = $array_input['upload_sep'];
            foreach ($upload as $row) 
            { 
                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/history_transaksi/images/'.$array_input['no_transaksi'];
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $row['url'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = str_replace(' ', '_', $row['no_sep']).$extenstion;
                $real_file = $array_input['no_transaksi'].'/'.$new_filename;

                // if (file_exists(FCPATH.config_item('user_img_temp_dir_new').$temp_filename) && is_file(FCPATH.config_item('user_img_temp_dir_new').$temp_filename)){
                     copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_invoice').$real_file);

                // }
                
                $data = array(
                    'no_sep'  => $row['no_sep'],
                    'url_sep' => $new_filename
                );

                $sep_id = $this->tindakan_hd_history_m->save($data,$array_input['tindakan_hd_id']);
                if($sep_id)
                {
                    $response->success = true;
                    $response->msg = translate('SEP untuk tindakan ini berhasil ditambahkan',$this->session->userdata('language'));
                }
            }

            die(json_encode($response));

        }
    }

    public function modal_dokumen($pasien_id, $penjamin_id)
    {
        $data_pasien = $this->pasien_m->get($pasien_id);
        $data_pasien = object_to_array($data_pasien);

        $ktp = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 1), true);
        
        $ktp_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 1), true, 'id');
        $ktp_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $ktp_pasien->id, 'tipe' => 9), true);
        $ktp_detail_value = $ktp_detail;
        $ktp_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 1,9)->row(0);

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
        $rujukan_detail_value = $rujukan_detail;
        $rujukan_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 7,9)->row(0);

        $rujukan_memo = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 23), true, 'id');
        $rujukan_detail_memo = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_memo->id, 'tipe' => 9), true);
        $rujukan_detail_memo_value = $rujukan_detail_memo;
        $rujukan_detail_memo_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 23,9)->row(0);

        $rujukan_pasien_rs = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 22), true, 'id');
        $rujukan_detail_rs = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien_rs->id, 'tipe' => 9), true);
        $rujukan_detail_rs_value = $rujukan_detail_rs;
        $rujukan_detail_rs_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 22,9)->row(0);

        $rujukan_pasien_luar = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 13), true, 'id');
        $rujukan_detail_luar = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien_luar->id, 'tipe' => 9), true);
        $rujukan_detail_luar_value = $rujukan_detail_luar;
        $rujukan_detail_luar_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 13,9)->row(0);

        $sppd = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_sppd')), true, 'id');
        $sppd_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $sppd->id, 'tipe' => 9), true);
        $sppd_detail_value = $sppd_detail;
        $sppd_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, config_item('id_surat_sppd'),9)->row(0);

        $sppd_tiga = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_tiga_kali')), true, 'id');
        $sppd_tiga_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $sppd_tiga->id, 'tipe' => 9), true);
        // $sppd_tiga_detail_value = $sppd_tiga_detail;
        $sppd_tiga_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, config_item('id_surat_tiga_kali'),9)->row(0);
        
        $traveling = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 4), true, 'id');
        $traveling_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $traveling->id, 'tipe' => 9), true);
        $traveling_detail_value = $traveling_detail;
        $traveling_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 4,9)->row(0);
        $body = array(
            'data_pasien'           => object_to_array($data_pasien),
            'penjamin_id'           => $penjamin_id,
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

        $this->load->view('klinik_hd/history_transaksi/modal_dokumen_pasien', $body);


    }

    public function cetak_dokumen($pasien_id, $penjamin_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $data_pasien = $this->pasien_m->get($pasien_id);
        $data_pasien = object_to_array($data_pasien);

        $ktp = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 1), true);
        $ktp_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 1), true, 'id');
        //die(dump($this->db->last_query()));
        $ktp_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $ktp_pasien->id, 'tipe' => 9), true);
        //$ktp_detail_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $ktp_detail->id), true);
        
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
        $rujukan_detail_rs_nilai = object_to_array($rujukan_detail_rs_value);

        $rujukan_pasien_luar = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 13), true, 'id');
        $rujukan_detail_luar = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien_luar->id, 'tipe' => 9), true);
        $rujukan_detail_luar_value = $rujukan_detail_luar;
        $rujukan_detail_luar_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 13,9)->row(0);
        $rujukan_detail_luar_nilai = object_to_array($rujukan_detail_luar);

        $sppd = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_sppd')), true, 'id');
        $sppd_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $sppd->id, 'tipe' => 9), true);
        $sppd_detail_value = $sppd_detail;
        $sppd_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, config_item('id_surat_sppd'),9)->row(0);
        $sppd_detail_nilai = object_to_array($sppd_detail_value);

        $sppd_tiga = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_tiga_kali')), true, 'id');

        $sppd_tiga_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $sppd_tiga->id, 'tipe' => 9), true);
        $sppd_tiga_detail_value = $sppd_tiga_detail;
        $sppd_tiga_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, config_item('id_surat_tiga_kali'),9)->row(0);
        $sppd_tiga_detail_nilai = object_to_array($sppd_tiga_detail_value);

        $traveling = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 4), true, 'id');
        $traveling_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $traveling->id, 'tipe' => 9), true);
        $traveling_detail_value = $traveling_detail;
        $traveling_detail_value = $this->pasien_dokumen_detail_m->get_dokumen_pasien($pasien_id, 4,9)->row(0);
        $traveling_detail_nilai = object_to_array($traveling_detail_value);
//die(dump(count($rujukan_memo)));

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
        
        
        
        $mpdf->Output($data_pasien['no_member'].'.pdf', 'I'); 

    }

}

