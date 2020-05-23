<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_dokter extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '079720ba44f1c50f27533ddfc46143c0';                  // untuk check bit_access

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
        $this->load->model('klinik_hd/pasien_klaim_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_detail_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_detail_identitas_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual2_m');
        $this->load->model('klinik_hd/bed_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_history_m');
        $this->load->model('klinik_hd/tindakan_hd_diagnosa_m');
        $this->load->model('klinik_hd/pendaftaran_tindakan_m');
        $this->load->model('klinik_hd/pendaftaran_tindakan_history_m');
        $this->load->model('klinik_hd/transaksi_diproses_m');

        $this->load->model('klinik_hd/resep_obat_racikan_m');
        $this->load->model('klinik_hd/resep_obat_racikan_detail_m');
        $this->load->model('klinik_hd/resep_obat_racikan_detail_manual_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/penjamin_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/region_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('master/penyakit_m');
        $this->load->model('klinik_hd/pasien_problem_m');
        $this->load->model('klinik_hd/pasien_problem_history_m');
        $this->load->model('klinik_hd/pasien_problem2_m');
        $this->load->model('klinik_hd/pasien_komplikasi_m');
        $this->load->model('klinik_hd/pasien_komplikasi_history_m');
        $this->load->model('klinik_hd/tindakan_hd_paket_m');
        $this->load->model('klinik_hd/tindakan_hd_visit_m');

        $this->load->model('klinik_hd/tindakan_hd_tindakan_m');
        $this->load->model('klinik_hd/tindakan_hd_tindakan_lain_m');
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

        $this->load->model('klinik_umum/tindakan_umum_m');
        $this->load->model('klinik_umum/tindakan_umum_tindakan_m');
        $this->load->model('klinik_umum/tindakan_umum_diagnosa_m');

        $this->load->model('klinik_umum/pasien_tindakan_m');
        $this->load->model('klinik_umum/tindakan_transfusi_m');
        $this->load->model('klinik_umum/tindakan_transfusi_item_m');
        $this->load->model('klinik_umum/tindakan_transfusi_history_m');
        $this->load->model('klinik_umum/tindakan_transfusi_item_history_m');


        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('apotik/inventory_m');
        // $this->load->model('apotik/inventory_identitas_m');
        $this->load->model('apotik/inventory_identitas_detail_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');
        $this->load->model('klinik_hd/surat_traveling_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');

        $this->load->model('master/laboratorium_klinik_m');
        $this->load->model('master/kategori_pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_detail_m');
        $this->load->model('tindakan/hasil_lab_m');
        $this->load->model('tindakan/hasil_lab_detail_m');
        $this->load->model('tindakan/hasil_lab_dokumen_m');
        $this->load->model('apotik/permintaan_box_paket_m');
        $this->load->model('reservasi/antrian/antrian_pasien_m');  
        $this->load->model('apotik/permintaan_dialyzer_baru_m');  

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/transaksi_dokter/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
 
        $data_antrian = $this->antrian_pasien_m->get_data_loket_panggil(3)->row(0);

        $list_antrian = $this->antrian_pasien_m->get_data_loket(3)->result_array();
       
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transaksi Dokter', $this->session->userdata('language')), 
            'header'         => translate('Transaksi Dokter', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/transaksi_dokter/index',
            'data_antrian'   => object_to_array($data_antrian),
            'list_antrian'   => object_to_array($list_antrian),

            'flag'           => 2,
            'tanda'         =>1,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/klinik_hd/transaksi_dokter/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Transaksi Dokter', $this->session->userdata('language')), 
            'header'         => translate('History Transaksi Dokter', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/transaksi_dokter/history',
            'flag'           => 2,
            'tanda'          => 2,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function transaksi_diproses()
    {
        $assets = array();
        $config = 'assets/klinik_hd/transaksi_dokter/transaksi_diproses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transaksi Sedang Diproses', $this->session->userdata('language')), 
            'header'         => translate('Transaksi Sedang Diproses', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/transaksi_dokter/transaksi_diproses',
            'flag'           => 2,
            'tanda'          => 2,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
    public function detail_history($id=null,$pasien_id=null)
    {
        if(restriction_function($this->session->userdata('level_id'), 'klinik_hd_transaksi_dokter','detail'))
        {
            $assets = array();
            $config = 'assets/klinik_hd/transaksi_dokter/detail_histori';
            $this->config->load($config, true);
            $assets = $this->config->item('assets', $config);
            
            // die(dump( $assets['css'] ));
            $form_data_kiri=$this->tindakan_hd_m->detail_histori_kiri($id,$pasien_id)->result_array();
            // die(dump($this->db->last_query()));
            $form_data_kanan=$this->tindakan_hd_m->detail_histori_kanan($id)->result_array();

            $form_pasien = $this->pasien_m->get($pasien_id);
            
            // die_dump($form_pasien);
            $data = array(
                'title'                 =>  config_item('site_name').' | '.translate('Detail History', $this->session->userdata('language')), 
                'header'                =>  translate('Detail History', $this->session->userdata('language')), 
                'header_info'           =>  config_item('site_name'), 
                'breadcrumb'            =>  true,
                'menus'                 =>  $this->menus,
                'menu_tree'             =>  $this->menu_tree,
                'css_files'             =>  $assets['css'],
                'js_files'              =>  $assets['js'],
                'content_view'          =>  'klinik_hd/transaksi_dokter/detail_history',
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
            redirect('klinik_hd/transaksi_dokter/history');
        }
    }

    public function visit2($id=null,$pasien_id=null)
    {
        $assets = array();
        $config = 'assets/klinik_hd/transaksi_dokter/detail_histori';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $form_data_kiri=$this->tindakan_hd_m->detail_histori_kiri($id,$pasien_id)->result_array();
        $form_data_kanan=$this->tindakan_hd_m->detail_histori_kanan($id)->result_array();
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transaksi Dokter', $this->session->userdata('language')), 
            'header'         => translate('Transaksi Dokter', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/transaksi_dokter/visit',
            'form_data_kiri'           => $form_data_kiri,
            'form_data_kanan'           => $form_data_kanan,
            'pasien_id'=>$pasien_id,
            'id'=>$id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function visit($type, $id_tindakan, $pasien_id=null, $bed_id, $id_tindakan_visit)
    {
        // $id = intval($id_tindakan);
        // $id || redirect(base_Url());
        $bed = $this->bed_m->get($bed_id);
        if($bed->user_edit_id != NULL && $bed->user_edit_id != $this->session->userdata('user_id'))
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Transaksi sedang dibuka oleh dokter lain.", $this->session->userdata("language")),
                "msgTitle" => translate("Informasi", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }
        if($bed->user_edit_id == NULL)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Bed yang anda pilih salah.", $this->session->userdata("language")),
                "msgTitle" => translate("Informasi", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }
        
        $assets = array();
        $config = 'assets/klinik_hd/transaksi_dokter/observasi_dialisis';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $cabang = $this->cabang_m->get(1);
        $form_tindakan = $this->tindakan_hd_m->get($id_tindakan);
        $result = get_data_sejarah_api($form_tindakan->pasien_id,$cabang->url);
        
        // TAB DATA PASIEN
        $form_pasien      = $this->pasien_m->get($form_tindakan->pasien_id);
        $form_agama       = $this->info_umum_m->get($form_pasien->agama_id);
        $form_goldar      = $this->info_umum_m->get($form_pasien->golongan_darah_id);
        $form_tlp         = $this->pasien_telepon_m->get_by(array('pasien_id' => $form_pasien->id, 'is_primary' => 1));
        if(count($form_tlp))
        {
            $form_sbjk_tlp    = $this->subjek_m->get($form_tlp[0]->subjek_id);
            
        }
        else
        {
            $form_sbjk_tlp    = $this->subjek_m->get(1);
            
        }
        $form_alamat      = $this->pasien_alamat_m->get_by(array('pasien_id' => $form_pasien->id, 'is_primary' => 1));
        $form_sbjk_alamat = $this->subjek_m->get($form_alamat[0]->subjek_id);
        $data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $form_alamat[0]->kode_lokasi),true);

        $form_kel_alamat  = '';
        $form_kec_alamat  = '';
        $form_kota_alamat = '';

        if(count($data_lokasi))
        {
            $form_kel_alamat  = $data_lokasi->nama_kelurahan;
            $form_kec_alamat  = $data_lokasi->nama_kecamatan;
            $form_kota_alamat = $data_lokasi->nama_kabupatenkota;            
        }

        $form_penyakit    = $this->pasien_penyakit_m->get_penyakit($form_pasien->id)->result();

        
        
        // TAB ASSESMENT
        $form_assesment   = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $id_tindakan));
        $form_problem     = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $id_tindakan));
        $form_komplikasi = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $id_tindakan));

        // die_dump($this->session);
        $form_data_kiri=$this->tindakan_hd_m->detail_histori_kiri($id_tindakan,$pasien_id)->result_array();
        $form_data_kanan=$this->tindakan_hd_m->detail_histori_kanan($id_tindakan)->result_array();

        $data = array(
            'title'            => config_item('site_name').' | '. translate("Visit Pasien", $this->session->userdata("language")), 
            'header'           => translate("Visit Pasien", $this->session->userdata("language")), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => TRUE,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'klinik_hd/transaksi_dokter/visit2',
            // 'pk_value'         => $bed_id,
            'flag'             => $type,
            'form_tindakan'    => object_to_array($form_tindakan),
            'form_pasien'      => object_to_array($form_pasien),
            'form_agama'       => object_to_array($form_agama),
            'form_goldar'      => (count($form_goldar) != 0)?object_to_array($form_goldar):'',
            'form_tlp'         => object_to_array($form_tlp),
            'form_sbjk_tlp'    => object_to_array($form_sbjk_tlp),
            'form_alamat'      => object_to_array($form_alamat),
            'form_sbjk_alamat' => object_to_array($form_sbjk_alamat),
            'form_kel_alamat'  => $form_kel_alamat,
            'form_kec_alamat'  => $form_kec_alamat,
            'form_kota_alamat' => $form_kota_alamat,
            'form_penyakit'    => object_to_array($form_penyakit),
            'form_assesment'   => object_to_array($form_assesment),
            'form_problem'     => object_to_array($form_problem),
            'form_komplikasi'   => object_to_array($form_komplikasi),
            'form_data_kiri'    => $form_data_kiri,
            'form_data_kanan'   => $form_data_kanan,
            'data_sejarah'      => $result,
            'bed_id'            => $bed_id,
            'id_tindakan_visit' => $id_tindakan_visit,
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit_observasi_dialisis($tindakan_id,$observasi_id)
    {
        $assets = array();
        $config = 'assets/klinik_hd/transaksi_dokter/create_tindakan';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $rows = $this->observasi_m->get_data_by_id($tindakan_id)->result_array();
        $body = array(
            "observasi_id_value"        => $rows[0]['id'],
            "transaksi_id_value"        => $rows[0]['transaksi_hd_id'], 
            "user_id_value"             => $rows[0]['user_id'],
            "waktu_pencatatan_value"    => $rows[0]['waktu_pencatatan'],
            "tda_value"                 => $rows[0]['tekanan_darah_1'],
            "tdb_value"                 => $rows[0]['tekanan_darah_2'],
            "ufg_value"                 => $rows[0]['ufg'],
            "ufr_value"                 => $rows[0]['ufr'],
            "ufv_value"                 => $rows[0]['ufv'],
            "qb_value"                  => $rows[0]['qb'],
            "keterangan_value"          => $rows[0]['keterangan'],
           
         
        );
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Edit Observasi Dialisis', $this->session->userdata('language')), 
            'header'         => translate('Edit Observasi Dialisis', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'pk'    =>$tindakan_id,
            'id'    =>$observasi_id,
            'content_view'   => 'klinik_hd/transaksi_dokter/edit_observasi_dialisis',
            
        );

        $data=array_merge($body,$data);
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function poliklinik_tindakan($id)
    {
        $assets = array();
        $config = 'assets/klinik_hd/poliklinik/tindakan';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data  = $this->poliklinik_m->get($id);
        $form_data2 = $this->poliklinik_tindakan_m->getdata($id)->result_array();
        $form_data3 = $this->poliklinik_tindakan_m->getdata3($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Poliklinik Tindakan', $this->session->userdata('language')), 
            'header'         => translate('Poliklinik Tindakan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'pk'             => $id,
            'form_data'      => $form_data,
            'form_data2'     => $form_data2,
            'form_data3'     => $form_data3,
            'flag'           => 1,
            'content_view'   => 'klinik_hd/poliklinik/tindakan',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    // *abu
    public function pilih_bed()
    {
        if($this->input->is_ajax_request())
        {
            $id = $this->input->post('id');

            // MENGUBAH STATUS BED
            $data['status'] = 2;
            $data['user_edit_id'] = $this->session->userdata('user_id');
            $bed_id = $this->bed_m->save($data, $id);

            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);

            if ($bed_id) 
            {
                $flashdata = array(
                    "success",
                    translate("Bed Telah di Set", $this->session->userdata("language")),
                    translate("Sukses", $this->session->userdata("language"))
                );
                
                echo json_encode($flashdata);
            }

        }
        
    }

    public function batal_bed()
    {
        if($this->input->is_ajax_request())
        {
            $id = $this->input->post('id');

            // MENGUBAH STATUS BED
            $data['status'] = 1;
            $bed_id = $this->bed_m->save($data, $id);

            $id_bed = '';

            $bed_booking = $this->bed_m->get_bed_booking()->row(0);
            if($bed_booking) $id_bed = $bed_booking->id;

            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);

            if ($bed_id) 
            {
                $flashdata = array(
                    "success",
                    translate("Penggunaan Bed Dibatalkan", $this->session->userdata("language")),
                    translate("Sukses", $this->session->userdata("language")),
                    $id_bed
                );
                
                echo json_encode($flashdata);
            }

        }
        
    }

    public function create_tindakan($id)
    {
        $this->load->model('global/rm_transaksi_pasien_m');
        
        $assets = array();
        $assets_config = 'assets/klinik_hd/transaksi_dokter/create_tindakan';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $pendaftaran = $this->pendaftaran_tindakan_m->get_by(array('id' => $id), true);

        if($pendaftaran->status == 2)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data tindakan sudah diproses.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }
        if($pendaftaran->status == 3)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data tindakan sudah selesai.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }
        if($pendaftaran->status == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data tindakan sudah dibatalkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }

        if($pendaftaran->status == 1 &&  $pendaftaran->waktu_proses !=NULL && $pendaftaran->modified_by !=NULL && $pendaftaran->modified_by != $this->session->userdata('user_id'))
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data tindakan sedang diproses.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }

        $form_data=$this->transaksi_dokter_m->getdatatindakan($id)->result_array();
        $form_data_daftar=$this->transaksi_dokter_m->get_by(array('id' => $id), true);
        $form_data_daftar = object_to_array($form_data_daftar);

        $cabang = $this->cabang_m->get(1);
        $result = get_data_sejarah_api($form_data[0]['pasien_id'],$cabang->url);
        // die(dump($result));
        // $form_data=get_data_tindakan_dokter($id);
 
        $startdate='';
        $enddate='';
        $date1='';
        $date2='';
        $now=date("Y-m-d");
        if(date("l")=='Monday' || date("l")=='Senin'){
            $startdate=$now;
            $date1=date_create($startdate);
            date_add($date1,date_interval_create_from_date_string("6 day"));
            $enddate=date_format($date1,"Y-m-d");
        }elseif (date("l")=='Tuesday' || date("l")=='Selasa') {
            $date1=date_create($now);
            date_add($date1,date_interval_create_from_date_string("-1 day"));
            $startdate=date_format($date1,"Y-m-d");

            $date2=date_create($startdate);
            date_add($date2,date_interval_create_from_date_string("6 day"));
            $enddate=date_format($date2,"Y-m-d");
        }elseif (date("l")=='Wednesday' || date("l")=='Rabu') {
            $date1=date_create($now);
            date_add($date1,date_interval_create_from_date_string("-2 day"));
            $startdate=date_format($date1,"Y-m-d");

            $date2=date_create($startdate);
            date_add($date2,date_interval_create_from_date_string("6 day"));
            $enddate=date_format($date2,"Y-m-d");
        }elseif (date("l")=='Thursday' || date("l")=='Kamis') {
            $date1=date_create($now);
            date_add($date1,date_interval_create_from_date_string("-3 day"));
            $startdate=date_format($date1,"Y-m-d");

            $date2=date_create($startdate);
            date_add($date2,date_interval_create_from_date_string("6 day"));
            $enddate=date_format($date2,"Y-m-d");
        }elseif (date("l")=='Friday' || date("l")=='Jumat') {
            $date1=date_create($now);
            date_add($date1,date_interval_create_from_date_string("-4 day"));
            $startdate=date_format($date1,"Y-m-d");

            $date2=date_create($startdate);
            date_add($date2,date_interval_create_from_date_string("6 day"));
            $enddate=date_format($date2,"Y-m-d");
        }elseif (date("l")=='Saturday' || date("l")=='Sabtu') {
            $date1=date_create($now);
            date_add($date1,date_interval_create_from_date_string("-5 day"));
            $startdate=date_format($date1,"Y-m-d");

            $date2=date_create($startdate);
            date_add($date2,date_interval_create_from_date_string("6 day"));
            $enddate=date_format($date2,"Y-m-d");
        }else{
            $date1=date_create($now);
            date_add($date1,date_interval_create_from_date_string("-6 day"));
            $startdate=date_format($date1,"Y-m-d");

            $date2=date_create($startdate);
            date_add($date2,date_interval_create_from_date_string("6 day"));
            $enddate=date_format($date2,"Y-m-d");
        }

        $form_data2=$this->transaksi_diproses_m->getdatatindakanfrekuensi($startdate,$enddate,$form_data[0]['pasien_id'])->result_array();
        $form_data3=$this->transaksi_diproses_m->getdatapasien($id)->result_array();
        $form_data4=$this->transaksi_diproses_m->getdatapasienphone($id)->result_array();
        $form_data5=$this->transaksi_diproses_m->getdatapasien2($id)->result_array();
        $form_data6=$this->transaksi_diproses_m->getdatapasienphone2($id)->result_array();
        $form_data7=$this->transaksi_diproses_m->getdatapasienalamat($id)->result_array();
        $form_data8=$this->transaksi_diproses_m->getdatapasienalamat2($id)->result_array();
        // die(dump($this->db->last_query()));
        $form_data9=$this->transaksi_diproses_m->getdatapasienpenyakit($id)->result_array();
        $form_data10 = $this->transaksi_diproses_m->getlasttransaksi($form_data[0]['pasien_id'])->row(0);
        // die(dump($this->db->last_query()));


        $data = array(
            'title'        => config_item('site_name').' | '. translate("Tindakan HD", $this->session->userdata("language")), 
            'header'       => translate("Tindakan HD", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'form_data'    => $form_data,
            'form_data_daftar'    => $form_data_daftar,
            'form_data2'   => $form_data2,
            'form_data3'   => $form_data3,
            'form_data4'   => $form_data4,
            'form_data5'   => $form_data5,
            'form_data6'   => $form_data6,
            'form_data7'   => $form_data7,
            'form_data8'   => $form_data8,
            'form_data9'   => $form_data9,
            'form_data10'   => (count($form_data10)!=0)?$form_data10:'',
            'data_sejarah'  => $result,
            'pk'           => $id,
            'shift'        => $pendaftaran->shift,
            'pasien_id'    => $form_data[0]['pasien_id'],
            'content_view' => 'klinik_hd/transaksi_dokter/create_tindakan',
            'flagg'        =>1
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function create_tindakan_umum($id)
    {
        $this->load->model('global/rm_transaksi_pasien_m');
        
        $assets = array();
        $assets_config = 'assets/klinik_hd/transaksi_dokter/create_tindakan_umum';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $pendaftaran = $this->pendaftaran_tindakan_m->get_by(array('id' => $id),true);

        if($pendaftaran->status == 2)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data tindakan sudah diproses.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }
        if($pendaftaran->status == 3)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data tindakan sudah selesai.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }
        if($pendaftaran->status == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data tindakan sudah dibatalkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }

        if($pendaftaran->status == 1 &&  $pendaftaran->waktu_proses !=NULL && $pendaftaran->modified_by !=NULL && $pendaftaran->modified_by != $this->session->userdata('user_id'))
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data tindakan sedang diproses.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_dokter');
        }

        $form_data=$this->transaksi_dokter_m->getdatatindakan($id)->result_array();
        $form_data_daftar=$this->transaksi_dokter_m->get_by(array('id' => $id), true);
        $form_data_daftar = object_to_array($form_data_daftar);

        $cabang = $this->cabang_m->get(1);
        $result = get_data_sejarah_api($form_data[0]['pasien_id'],$cabang->url);
        
        $form_data3=$this->transaksi_dokter_m->getdatapasien($id)->result_array();
        $form_data4=$this->transaksi_dokter_m->getdatapasienphone($id)->result_array();
        $form_data5=$this->transaksi_dokter_m->getdatapasien2($id)->result_array();
        $form_data6=$this->transaksi_dokter_m->getdatapasienphone2($id)->result_array();
        $form_data7=$this->transaksi_dokter_m->getdatapasienalamat($id)->result_array();
        $form_data8=$this->transaksi_dokter_m->getdatapasienalamat2($id)->result_array();
        // die(dump($this->db->last_query()));
        $form_data10 = $this->transaksi_dokter_m->getlasttransaksi($form_data[0]['pasien_id'])->row(0);
        // die(dump($this->db->last_query()));


        $data = array(
            'title'        => config_item('site_name').' | '. translate("Tindakan HD", $this->session->userdata("language")), 
            'header'       => translate("Tindakan HD", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'form_data'    => $form_data,
            'form_data_daftar'    => $form_data_daftar,
            'form_data3'   => $form_data3,
            'form_data4'   => $form_data4,
            'form_data5'   => $form_data5,
            'form_data6'   => $form_data6,
            'form_data7'   => $form_data7,
            'form_data8'   => $form_data8,
            'form_data10'   => (count($form_data10)!=0)?$form_data10:'',
            'data_sejarah'  => $result,
            'pk'           => $id,
            'shift'        => $pendaftaran->shift,
            'pasien_id'    => $form_data[0]['pasien_id'],
            'content_view' => 'klinik_hd/transaksi_dokter/create_tindakan_umum',
            'flagg'        =>1
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit_tindakan($id)
    {
        $assets = array();
        $assets_config = 'assets/klinik_hd/transaksi_dokter/edit_tindakan';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data=$this->transaksi_dokter_m->getdatatindakan2($id)->result_array();
        $cabang = $this->cabang_m->get(1);
        $result = get_data_sejarah_api($form_data[0]['pasien_id'],$cabang->url);
 
            $startdate='';
            $enddate='';
            $date1='';
            $date2='';
            $now=date("Y-m-d");
            if(date("l")=='Monday' || date("l")=='Senin'){
                $startdate=$now;
                $date1=date_create($startdate);
                date_add($date1,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date1,"Y-m-d");
            }elseif (date("l")=='Tuesday' || date("l")=='Selasa') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-1 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }elseif (date("l")=='Wednesday' || date("l")=='Rabu') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-2 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }elseif (date("l")=='Thursday' || date("l")=='Kamis') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-3 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }elseif (date("l")=='Friday' || date("l")=='Jumat') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-4 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }elseif (date("l")=='Saturday' || date("l")=='Sabtu') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-5 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }else{
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-6 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }

        $form_data2=$this->transaksi_dokter_m->getdatatindakanfrekuensi2($startdate,$enddate,$form_data[0]['pasien_id'])->result_array();
        $form_data3=$this->transaksi_dokter_m->getdatapasien21($id)->result_array();
        $form_data4=$this->transaksi_dokter_m->getdatapasienphone21($id)->result_array();
        $form_data5=$this->transaksi_dokter_m->getdatapasien22($id)->result_array();
        $form_data6=$this->transaksi_dokter_m->getdatapasienphone22($id)->result_array();
        $form_data7=$this->transaksi_dokter_m->getdatapasienalamat21($id)->result_array();
        $form_data8=$this->transaksi_dokter_m->getdatapasienalamat22($id)->result_array();
        $form_data9=$this->transaksi_dokter_m->getdatapasienpenyakit2($id)->result_array();
        $form_data10=$this->transaksi_dokter_m->getdatapenaksiran($id)->result_array();

        $data = array(
            'title'        => config_item('site_name').' | '. translate("Tindakan HD", $this->session->userdata("language")), 
            'header'       => translate("Tindakan HD", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'form_data'    => $form_data,
            'form_data2'   => $form_data2,
            'form_data3'   => $form_data3,
            'form_data4'   => $form_data4,
            'form_data5'   => $form_data5,
            'form_data6'   => $form_data6,
            'form_data7'   => $form_data7,
            'form_data8'   => $form_data8,
            'form_data9'   => $form_data9,
            'form_data10'  => $form_data10,
            'data_sejarah' => $result,
            'pk'           =>$id,
            'pasien_id'    =>$form_data[0]['pasien_id'],
            'content_view' => 'klinik_hd/transaksi_dokter/create_tindakan',
            'flagg'        =>2
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/klinik_hd/poliklinik/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        // $form_data = $this->cabang_m->get($id);
        $form_data=$this->poliklinik_m->get($id);
        $form_data2=$this->poliklinik_tindakan_m->getdata($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Poliklinik", $this->session->userdata("language")), 
            'header'         => translate("Edit Poliklinik", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/poliklinik/edit',
            'form_data'      => $form_data,
            'form_data2'     => $form_data2,
            'pk'             => $id,                    //table primary key value
            'flag'           => 2
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/poliklinik/tindakan';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        // $form_data = $this->cabang_m->get($id);
        $form_data=$this->poliklinik_m->get($id);
        $data = array(
            'title'          => config_item('site_name'). translate("Lihat Poliklinik", $this->session->userdata("language")), 
            'header'         => translate("Lihat Poliklinik", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'flag'          =>2,
            'pk'             => $id,
            'content_view'   => 'master/poliklinik/tindakan',
            'form_data'      => $form_data,
            'pk_value'       => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function lihat_detail_denah($bed_id)
    {

        $data_detail = $this->bed_m->get_bed_pasien($bed_id);
        if ($data_detail) 
        {
            $form_data = object_to_array($data_detail);
        } 
        else
        {
            $form_data = array
            (
                array
                (
                    "bed_id"       => '',
                    "tindakan_id"  => '',
                    "no_transaksi" => '',
                    "bed_kode"     => '',
                    "bed_nama"     => '',
                    "lantai"       => '',
                    "pasien"       => '',
                    "jam_mulai"    => '',
                    "dokter"       => '',
                    "perawat"      => ''
                ),
            );
        }

        $data = array(
            'form_data' => $form_data,
            'bed_id'    => $bed_id,
        );
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_dokter/tab_transaksi_dokter/modal_lihat_detail', $data);

    }
   

    public function listing()
    {        
        $cabang_id=$this->session->userdata('cabang_id');
        $dokter_id = $this->session->userdata('user_id');
        $params = $this->transaksi_dokter_m->get_params();
        $post = $this->input->post();
        $param = array_merge($params,$post);


        // $result = json_decode(get_datatable_pendaftaran_tindakan($param,$cabang_id,$dokter_id));
        $result = $this->transaksi_dokter_m->get_datatable($cabang_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        // $records = $records->result_id;

        // $SQL = $records->queryString;
        // $records = $this->db->query($SQL);
        // die(dump($records->queryString));

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $shift = '';

            $user_level_id = $this->session->userdata('level_id');
            
            $data = '<a title="'.translate('Proses', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/transaksi_dokter/create_tindakan/'.$row['id'].'" class="btn btn-primary search-item"><i class="fa fa-check"></i></a>';
            $data_umum = '<a title="'.translate('Proses', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/transaksi_dokter/create_tindakan_umum/'.$row['id'].'" class="btn btn-primary search-item"><i class="fa fa-check"></i></a>';
            $data2 = '<a title="'.translate('Batalkan', $this->session->userdata('language')).'"  name="del[]" data-confirm="'.translate('Batalkan tindakan untuk pasien ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn red search-item"><i class="fa fa-times"></i></a>';
            
            if($row['poliklinik_id'] == 1){
                $action =  restriction_button($data,$user_level_id,'klinik_hd_transaksi_dokter','view1').restriction_button($data2,$user_level_id,'klinik_hd_transaksi_dokter','delete1');
            }if($row['poliklinik_id'] == 2){
                $action =  restriction_button($data_umum,$user_level_id,'klinik_hd_transaksi_dokter','view1').restriction_button($data2,$user_level_id,'klinik_hd_transaksi_dokter','delete1');
            }
            
            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
            }
            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }

       
            $output['data'][] = array(
                '<div class="text-left">'.$img_url.$shift.' '.$row['nama'].'</div>',
                '<div class="text-left">'.$row['nama_poli'].'</div>',
                '<div class="text-left">'.$row['tempat_lahir'].', '.date('d M Y',strtotime($row['tanggal_lahir'])).'</div>',
                '<div class="text-left">'.ucwords(strtolower($row['alamat'])).', '.ucwords(strtolower($row['kelurahan'])).', '.ucwords(strtolower($row['kecamatan'])).', '.ucwords(strtolower($row['kota'])).'</div>',
                '<div class="text-center">'.date('d M Y H:i:s', strtotime($row['created_date'])).'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_ditolak()
    {        
        $cabang_id=$this->session->userdata('cabang_id');

        // $result = json_decode(get_datatable_pendaftaran_tindakan($param,$cabang_id,$dokter_id));
        $result = $this->transaksi_dokter_m->get_datatable_ditolak($cabang_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        // $records = $records->result_id;

        // $SQL = $records->queryString;
        // $records = $this->db->query($SQL);
        // die(dump($records->queryString));

        $i=0;
        foreach($records->result_array() as $row)
        {
            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('base_dir').config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
            }

       
            $output['data'][] = array(
                '<div class="text-left">'.$img_url.$row['nama'].'</div>',
                '<div class="text-left">'.$row['tempat_lahir'].', '.date('d M Y',strtotime($row['tanggal_lahir'])).'</div>',
                '<div class="text-center">'.date('d M Y H:i:s', strtotime($row['created_date'])).'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing2($id)
    {        
        $result = $this->transaksi_dokter2_m->get_datatable($id);

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
                
            
       
            $output['data'][] = array(
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['jumlah'].' ('.$row['idx'].' kali)</div>',
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing3($id=null)
    {        
        $result = $this->transaksi_dokter3_m->get_datatable($id);

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
            $tipe='';    
            $jenis='';
            $status='';
            $status2='';
            $tipe1='';

            if($row['tipe']==1){
                $jenis='Dokumen Pelengkap';
            }else{
                $jenis='Dokumen Rekam Medis';
            }

            $action = '<a title="'.translate('Update', $this->session->userdata('language')).'"  name="update" href="'.base_url().'klinik_hd/transaksi_dokter/edit_dokumen/'.$row['id'].'" data-target="#ajax_notes3" data-toggle="modal"  class="btn btn-xs blue-chambray search-item"><i class="fa fa-edit"></i></a>
                         ';
            
            $notif = $row['notif_hari'].' day';       

            if($row['is_kadaluarsa'] == 1)
            {
                $date1=date_create(date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])));
                date_sub($date1,date_interval_create_from_date_string($notif));
                $startdate=date_format($date1,"Y-m-d");
                
                $tanggal_kadaluarsa = date('d M Y',strtotime($row['tanggal_kadaluarsa']));
            

                if(date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])) < date('Y-m-d') )
                {
                    $status='<span class="label label-danger">Kadaluarsa</span>';
                    $status2='Kadaluarsa';                
                }
                else if(date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])) >= date('Y-m-d') )
                {
                    if($startdate <= date('Y-m-d') )
                    {
                        $status='<span class="label label-warning">Peringatan</span></div>';
                        $status2='Peringatan';
                    }
                    else
                    {
                        $status='<span class="label label-success">Aktif</span></div>';
                        $status2='Aktif';                    
                    }
                }                 
            }
            else
            {
                $tanggal_kadaluarsa = '-';
                $status='<span class="label label-success">Aktif</span></div>';
                $status2='Aktif';
            }
       
            $output['data'][] = array(
                 '<div class="text-left">'.$row['nama'].'</div>',
                 '<div class="text-center">'.$tanggal_kadaluarsa.'</div>',
                 '<div class="text-center">'.$jenis.'</div>',
                 '<div class="text-center">'.$status.'</div>',
                 '<div class="text-center">'.$action.'</div>',
                $status2
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

     public function listing4($pasienid=null)
    {        

        $result = $this->transaksi_dokter4_m->get_datatable($pasienid);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=1;
        $pasien = $this->pasien_m->get_by(array('id' => $pasienid), true);
        // die(dump($pasien));
        foreach($records->result_array() as $row)
        {
            $action = '';

            $detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $row['id'], 'tipe' => 9), true);

            $detail_tipe = $this->pasien_dokumen_detail_tipe_m->get_by(array('pasien_dokumen_detail_id' => $detail->id), true);
                   
            $output['data'][] = array(
                '<div class="text-left">'.$i.'</div>',
                '<div class="text-left thumbnail"><a class="fancybox-button" title="'.$detail_tipe->value.'" href="'.config_item('url_core').config_item('site_img_pasien').$pasien->no_member.'/dokumen/rekam_medis/'.$detail_tipe->value.'" data-rel="fancybox-button"><img max-width="10%" src="'.config_item('url_core').config_item('site_img_pasien').$pasien->no_member.'/dokumen/rekam_medis/'.$detail_tipe->value.'" alt="Smiley face" class="img-responsive" ></a></div>'      
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_item()
    {        
        $result = $this->tindakan_m->get_datatable();

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
            
            $action = '<div class="text-center"><a class="btn btn-primary select" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-check"></i></a></div>';

            $output['data'][] = array(
               
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-right">'.$this->formatrupiah($row['harga']).'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_tindakan($id=null,$type=null)
    {        
        $result = $this->poliklinik_tindakan_m->get_datatable($id);

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
            if($type==2){
                $modal=$this->formatrupiah($row['harga']).' <a  href="#" id="select2[]" name="select[]2" data-id="'.$row['id'].'"  data-target="#ajax_notes" data-toggle="modal" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
            }else{
                $modal=$this->formatrupiah($row['harga']);
            }
            

            $action = '<div class="text-center"><a class="btn btn-primary select" id="select[]" name="select[]" data-id="'.$row['id'].'"  data-target="#ajax_notes" data-toggle="modal"><i class="fa fa-dollar"></i></a></div>';
           

            $output['data'][] = array(
               
                '<div class="text-center">'.$row['kode'].'</div>' ,
                $row['nama'],
                '<div class="text-right">'.$modal.'</div>' ,
              
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

     public function listing_tindakan_2($id=null)
    {        
        $result = $this->poliklinik_harga_tindakan_m->get_datatable($id);

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
                '<div class="text-center">'.date('d F Y',strtotime($row['tanggal'])).'</div>',
                '<div class="text-right">'.$this->formatrupiah($row['harga']).'</div>',
               $row['harga'],
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save_umum()
    {
        $array_input = $this->input->post();

        $pasien = $this->pasien_m->get_by(array('id' => $array_input['pasienid']), true);

        $last_number = $this->tindakan_umum_m->get_max_id()->result_array();
        $last_number = intval($last_number[0]['max_id'])+1;
        
        $format         = 'UM-'.date('my').'-%04d';
        $format_nomor   = 'UM#'.date('my').'#%04d';
        $tindakan_id    = sprintf($format, $last_number, 4);
        $tindakan_nomor = sprintf($format_nomor, $last_number, 4);

        $data_umum = array(
            'id'             => $tindakan_id,
            'nomor_tindakan' => $tindakan_nomor,
            'tanggal'        => date('Y-m-d'),
            'shift'          => $array_input['shift'],
            'cabang_id'      => $this->session->userdata('cabang_id'),
            'pasien_id'      => $array_input['pasienid'],
            'dokter_id'      => $array_input['userid1'],
            'bb'             => $array_input['berat'],
            'td'             => $array_input['tdatas'].'_'.$array_input['tdbawah'],
            'nadi'           => $array_input['nadi'],
            'respirasi'      => $array_input['respirasi'],
            'suhu'           => $array_input['suhu'],
            'keluhan'        => $array_input['keluhan'],
            'status'         => 3,
            'is_active'      => 1,
            'created_by'     => $this->session->userdata('user_id'),
            'created_date'   => date('Y-m-d')
        );

        $save_tindakan_umum = $this->tindakan_umum_m->add_data($data_umum);

        foreach ($array_input['diagnosa_awal_umum'] as $diagnosa) {
           
           if($diagnosa['name'] != ''){

                $last_id_diag = $this->tindakan_umum_diagnosa_m->get_max_id()->result_array();
                $last_id_diag = intval($last_id_diag[0]['max_id'])+1;
                
                $format         = 'UMD-'.date('my').'-%04d';
                $diagnosa_id    = sprintf($format, $last_id_diag, 4);

                $data_diagnosa = array(
                    'id'                => $diagnosa_id,
                    'tindakan_umum_id'  => $tindakan_id,
                    'diagnosa'          => $diagnosa['name'],
                    'is_active'         => 1,
                    'created_by'        => $this->session->userdata('user_id'),
                    'created_date'      => date('Y-m-d')
                );
                $save_tindakan_diagnosa = $this->tindakan_umum_diagnosa_m->add_data($data_diagnosa);

           }
        }

        $total_invoice = 0;
        foreach ($array_input['tindakan_umum'] as $tindakan) {
           
           if($tindakan['id'] != ''){

                $invoice_tindakan = $this->invoice_m->get_by(array('tindakan_id' => $tindakan_id, 'is_active' => 1), true);

                $cabang_id = $this->session->userdata('cabang_id');
                $cabang = $this->cabang_m->get($cabang_id);

                if(count($invoice_tindakan) == 0){

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

                    $data_invoice = array(
                        'no_invoice'        => $no_invoice,
                        'tindakan_id'       => $tindakan_id,
                        'no_tindakan'       => $tindakan_nomor,
                        'cabang_id'         => $cabang_id,
                        'nama_cabang'       => $cabang->nama,
                        'tipe_pasien'       => 0,
                        'pasien_id'         => $array_input['pasienid'],
                        'nama_pasien'       => $pasien->nama,
                        'tipe'              => 2,
                        'penjamin_id'       => 1,
                        'nama_penjamin'     => $pasien->nama,
                        'is_claim'          => 0,
                        'poliklinik_id'     => 2,
                        'nama_poliklinik'   => 'Umum',
                        'jenis_invoice'     => 1,
                        'shift'             => $array_input['shift'],
                        'status'            => 1,
                        'harga'             => 0,
                        'diskon'            => 0,
                        'is_active'         => 1,
                    );
                    
                    $invoice_id = $this->invoice_m->save($data_invoice);
                }else{
                    $invoice_id = $invoice_tindakan->id;
                }


                $last_id_diag = $this->tindakan_umum_tindakan_m->get_max_id()->result_array();
                $last_id_diag = intval($last_id_diag[0]['max_id'])+1;
                
                $format         = 'UMT-'.date('my').'-%04d';
                $tindakan_harga_id    = sprintf($format, $last_id_diag, 4);

                $data_tindakan_harga = array(
                    'id'                => $tindakan_harga_id,
                    'tindakan_umum_id'  => $tindakan_id,
                    'tindakan_id'          => $tindakan['id'],
                    'harga'          => $tindakan['harga'],
                    'is_active'         => 1,
                    'created_by'        => $this->session->userdata('user_id'),
                    'created_date'      => date('Y-m-d')
                );

                $total_invoice = $total_invoice + $tindakan['harga'];

                $save_tindakan_diagnosa = $this->tindakan_umum_tindakan_m->add_data($data_tindakan_harga);

                $data_invoice_detail = array(
                    'invoice_id' => $invoice_id,
                    'item_id' => $tindakan['id'],
                    'tipe_item' => 3,
                    'qty' => 1,
                    'harga' => $tindakan['harga'],
                    'tipe' => 3,
                    'status' => 1,
                    'is_active' => 1
                );

                $invoice_detail_id = $this->invoice_detail_m->save($data_invoice_detail);

                $invoice_edit = array(
                    'harga' => $total_invoice,
                    'harga_setelah_diskon' => $total_invoice,
                    'sisa_bayar' => $total_invoice,
                );
                $edit_invoice = $this->invoice_m->save($invoice_edit, $invoice_id);

           }
        }
        

        $resep=$this->input->post('resep');
        if(count($resep) != 0){
            if($resep[1]['tindakan_id']!= null){
                $invoice_tindakan = $this->invoice_m->get_by(array('tindakan_id' => $tindakan_id, 'is_active' => 1), true);

                $cabang_id = $this->session->userdata('cabang_id');
                $cabang = $this->cabang_m->get($cabang_id);

                if(count($invoice_tindakan) == 0){

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

                    $data_invoice = array(
                        'no_invoice'        => $no_invoice,
                        'tindakan_id'       => $tindakan_id,
                        'no_tindakan'       => $tindakan_nomor,
                        'cabang_id'         => $cabang_id,
                        'nama_cabang'       => $cabang->nama,
                        'tipe_pasien'       => 0,
                        'pasien_id'         => $array_input['pasienid'],
                        'nama_pasien'       => $pasien->nama,
                        'tipe'              => 2,
                        'penjamin_id'       => 1,
                        'nama_penjamin'     => $pasien->nama,
                        'is_claim'          => 0,
                        'poliklinik_id'     => 2,
                        'nama_poliklinik'   => 'Umum',
                        'jenis_invoice'     => 1,
                        'shift'             => $array_input['shift'],
                        'status'            => 1,
                        'harga'             => 0,
                        'diskon'            => 0,
                        'is_active'         => 1,
                    );
                    
                    $invoice_id = $this->invoice_m->save($data_invoice);
                }else{
                    $invoice_id = $invoice_tindakan->id;
                }
                $roman_month = romanic_number(date('m'), true);
                $max_no = $this->tindakan_resep_obat_m->get_max_no($roman_month)->result_array();

                if(count($max_no))
                {
                    $last_nosurat = intval($max_no[0]['max_no']) + 1;
                }
                else
                {
                    $last_nosurat = 1;
                }

                $format       = '#RSP#%03d';
                $no_resep    = sprintf($format, $last_nosurat, 3);
                $no_resep_new = $no_resep.'#RHS#'.$roman_month.'#'.date('Y');

                $data_resep = array(
                    'cabang_id'     => $this->session->userdata('cabang_id'),
                    'nomor_resep'   => $no_resep_new,
                    'tindakan_id'   => $tindakan_id,
                    'tipe_tindakan' => 2,
                    'tipe_resep' => 1,
                    'pasien_id'     => $this->input->post('pasienid'),
                    'dokter_id'     => $this->session->userdata('user_id'),
                    'status'        => 1,
                    'is_active'     => 1
                );
                $resep_id = $this->tindakan_resep_obat_m->save($data_resep);
            }
            
        }

        foreach($resep as $row){
            if($row['tindakan_id']!= null){
                $bawa_pulang = (isset($row['item_bawa']))?$row['item_bawa']:0;
                if($row['tipe_obat'] == 'obat')
                {
                    $data6 = array(
                        'tindakan_id'            => $tindakan_id,
                        'tipe_tindakan'          => 2,
                        'tindakan_resep_obat_id' => $resep_id,
                        'tipe_item'              => 1,
                        'item_id'                => $row['tindakan_id'],
                        'jumlah'                 => $row['jumlah'],
                        'satuan_id'              => $row['satuan'],
                        'dosis'                  => $row['item_dosis'],
                        'aturan'                 => $row['item_aturan'],
                        'bawa_pulang'            => $bawa_pulang,
                        'is_active'              => 1
                    );

                    $resep_detail_id = $this->tindakan_resep_obat_detail_m->save($data6);

                    $harga_jual = $this->item_harga_m->get_harga_item_satuan($row['tindakan_id'], $row['satuan'])->result_array();

                    $harga_item = (count($harga_jual))?$harga_jual[0]['harga']:0;

                    $total_invoice = $total_invoice + $harga_item;
                    $data_invoice_detail = array(
                        'invoice_id' => $invoice_id,
                        'item_id' => $row['tindakan_id'],
                        'satuan_id' => $row['satuan'],
                        'tipe_item' => 3,
                        'qty' => $row['jumlah'],
                        'harga' =>  $harga_item,
                        'tipe' => 3,
                        'status' => 1,
                        'is_active' => 1
                    );

                    $invoice_detail_id = $this->invoice_detail_m->save($data_invoice_detail);

                    $invoice_edit = array(
                        'harga' => $total_invoice,
                        'harga_setelah_diskon' => $total_invoice,
                        'sisa_bayar' => $total_invoice,
                    );
                    $edit_invoice = $this->invoice_m->save($invoice_edit, $invoice_id);
                }    
            }    
        }

        $data_daftar = $this->pendaftaran_tindakan_m->get_by(array('id' => $array_input['pk']), true);

        $array_daftar = array(
            'cabang_id' => $data_daftar->cabang_id,
            'poliklinik_id' => $data_daftar->poliklinik_id,
            'dokter_id' => $data_daftar->dokter_id,
            'pasien_id' => $data_daftar->pasien_id,
            'penjamin_id' => $data_daftar->penjamin_id,
            'penanggung_jawab_id' => $data_daftar->penanggung_jawab_id,
            'antrian' => $data_daftar->antrian,
            'antrian_dokter' => $data_daftar->antrian_dokter,
            'status' => 3,
            'shift' => $data_daftar->shift,
            'user_verif_id' => $data_daftar->user_verif_id,
            'status_verif' => $data_daftar->status_verif,
            'tanggal_verif' => $data_daftar->tanggal_verif,
            'keterangan' => $data_daftar->keterangan,
            'waktu_proses' => $data_daftar->waktu_proses,
            'berat_badan' => $data_daftar->berat_badan,
            'tekanan_darah' => $data_daftar->tekanan_darah,
            'nadi' => $data_daftar->nadi,
            'respirasi' => $data_daftar->respirasi,
            'suhu' => $data_daftar->suhu,
            'jenis_peserta' => $data_daftar->jenis_peserta,
            'is_active' => $data_daftar->is_active,
            'created_by' => $data_daftar->created_by,
            'created_date' => $data_daftar->created_date,
        );

        $save_history_daftar_id = $this->pendaftaran_tindakan_history_m->save($array_daftar);

        $delete_daftar = $this->pendaftaran_tindakan_m->delete($array_input['pk']);


        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Data Tindakan Gagal Disimpan', $this->session->userdata('language'));

        if($tindakan_id){
            $response->success = true;
        }

        die(json_encode($response)); 
    }

    public function save()
    {
        if($this->input->is_ajax_request()){
            $command2 = $this->input->post('command2');

            $pasien = $this->pasien_m->get($this->input->post('pasienid'));
            $array_input = $this->input->post();

            //die_dump($array_input);

            $id_daftar = $this->input->post('pk');

            $tindakan_berjalan = $this->tindakan_hd_m->get_by(array('pendaftaran_tindakan_id' => $id_daftar), true);
            $pasien_id = $array_input['pasienid'];
            $data_dialyzer_reuse = $this->item_tersimpan_m->get_by("pasien_id = $pasien_id AND status_reuse in (1,2,4,5)");
            $data_dialyzer_reuse_siap = $this->item_tersimpan_m->get_by("pasien_id = $pasien_id AND status_reuse in (3)");
            $data_dialyzer_pasien = $this->item_tersimpan_m->get_by("pasien_id = $pasien_id");
            $dialyzer_new = 0;
            $dialyzer_reuse = 1;
            $reason = 0;

            if(count($tindakan_berjalan) == 0){
                if ($command2 === 'add')
                {  
                    $klaim       =$this->input->post('pilihklaim');
                    $cabang_id   =$this->user_m->get($this->session->userdata("user_id"));
                    $cabang_id   =object_to_array($cabang_id);
                    $cabang      = $this->cabang_m->get($this->session->userdata('cabang_id'));


                    $data_daftar = $this->pendaftaran_tindakan_m->get_by(array('id' => $id_daftar), true);

                    $array_daftar = array(
                        'cabang_id' => $data_daftar->cabang_id,
                        'cabang_pasien_id' => $data_daftar->cabang_pasien_id,
                        'poliklinik_id' => $data_daftar->poliklinik_id,
                        'dokter_id' => $data_daftar->dokter_id,
                        'pasien_id' => $data_daftar->pasien_id,
                        'penjamin_id' => $data_daftar->penjamin_id,
                        'penanggung_jawab_id' => $data_daftar->penanggung_jawab_id,
                        'antrian' => $data_daftar->antrian,
                        'antrian_dokter' => $data_daftar->antrian_dokter,
                        'status' => 2,
                        'shift' => $data_daftar->shift,
                        'user_verif_id' => $data_daftar->user_verif_id,
                        'status_verif' => $data_daftar->status_verif,
                        'tanggal_verif' => $data_daftar->tanggal_verif,
                        'keterangan' => $data_daftar->keterangan,
                        'waktu_proses' => $data_daftar->waktu_proses,
                        'berat_badan' => $data_daftar->berat_badan,
                        'tekanan_darah' => $data_daftar->tekanan_darah,
                        // 'nadi' => $data_daftar->nadi,
                        // 'respirasi' => $data_daftar->respirasi,
                        // 'suhu' => $data_daftar->suhu,
                        'jenis_peserta' => $data_daftar->jenis_peserta,
                        'is_active' => $data_daftar->is_active,
                        'created_by' => $data_daftar->created_by,
                        'created_date' => $data_daftar->created_date,
                    );

                    $save_history_daftar_id = $this->pendaftaran_tindakan_history_m->save($array_daftar);

                    
                    $last_number = $this->tindakan_hd_m->get_nomor_tindakan($cabang->kode)->result_array();
                    // die(dump($this->db->last_query()));
                    $last_number = intval($last_number[0]['max_nomor_po'])+1;
                    
                    $format      = 'HD'.$cabang->kode.'-'.date('ym').'-%04d';
                    $po_number   = sprintf($format, $last_number, 4);



                    $data['no_transaksi']            =$po_number;
                    $data['pendaftaran_tindakan_id'] =$save_history_daftar_id;
                    $data['pasien_id']               =$this->input->post('pasienid');
                    $data['dokter_id']               =$this->session->userdata("user_id");
                    $data['tanggal']                 =date('Y-m-d H:i:s');
                    $data['shift']               = $array_input['shift'];

                    // $cabang_id=$this->user_m->get($this->session->userdata("user_id"));

                    $pendaftaran_id = $this->input->post('pk');
                    $pendaftaran = $this->pendaftaran_tindakan_m->get_by(array('id' => $pendaftaran_id), true);

                    $data['cabang_id']=$cabang_id['cabang_id'];
                    $data['cabang_pasien_id']=$pasien->cabang_id;

                    $data['penjamin_id'] = $pendaftaran->penjamin_id;
                   
                    $data['status']       =1;
                    $data['jangka_waktu'] =$this->input->post('freq');
                    $data['berat_awal']   =$this->input->post('berat');
                    $data['bed_id']       = $this->input->post('bed_id');      // *abu
                    $data['rupiah']       =0;
                    $data['is_active']    =1;
                    $data['is_sep']    =0;
                    $data['jenis_peserta'] = $data_daftar->jenis_peserta;

                    $tindakan_id=$this->tindakan_hd_m->save($data);

                    // sent_notification(6,$po_number, $tindakan_id);

                    // $filename = urlencode(base64_encode($this->session->userdata('url_login')));

                    // $all_cabang = $this->cabang_m->get_by(array('is_active' => 1));

                    // foreach ($all_cabang as $cabang) 
                    // {
                    //     if($cabang->url != NULL || $cabang->url != '')
                    //     {
                    //         change_file_notif($cabang->url,$filename);                       
                    //     }
                    // }

                    if(count($data_dialyzer_reuse) != 0 && count($data_dialyzer_reuse_siap) == 0){
                        $last_id       = $this->permintaan_dialyzer_baru_m->get_max_id_permintaan()->result_array();
                        $last_id       = intval($last_id[0]['max_id'])+1;
                        
                        $format_id     = 'DB-'.date('m').'-'.date('Y').'-%04d';
                        $id_permintaan = sprintf($format_id, $last_id, 4);
                        
                        $format        = '#DB#%04d/RHS/'.romanic_number(date('m'), true).'/'.date('Y');
                        $no_permintaan = sprintf($format, $last_id, 4);

                        $reason = 1;

                        $array_permintaan = array(
                            'id'            => $id_permintaan,
                            'cabang_id'     => $this->session->userdata('cabang_id'),
                            'no_permintaan' => $no_permintaan,
                            'pasien_id'     => $pasien_id,
                            'tindakan_id'   => $tindakan_id,
                            'reason'        => 1,
                            'status'        => 1,
                            'is_active'     => 1,
                            'created_by'    => $this->session->userdata('user_id'),
                            'created_date'  => date('Y-m-d H:i:s')
                        );

                        //$buat_permintaan = $this->permintaan_dialyzer_baru_m->add_data($array_permintaan);
                        $dialyzer_new = 1;
                        $dialyzer_reuse = 0;
                    }
                    if(count($data_dialyzer_pasien) == 0){
                        $last_id       = $this->permintaan_dialyzer_baru_m->get_max_id_permintaan()->result_array();
                        $last_id       = intval($last_id[0]['max_id'])+1;
                        
                        $format_id     = 'DB-'.date('m').'-'.date('Y').'-%04d';
                        $id_permintaan = sprintf($format_id, $last_id, 4);
                        
                        $format        = '#DB#%04d/RHS/'.romanic_number(date('m'), true).'/'.date('Y');
                        $no_permintaan = sprintf($format, $last_id, 4);

                        $reason = 2;

                        $array_permintaan = array(
                            'id'            => $id_permintaan,
                            'cabang_id'     => $this->session->userdata('cabang_id'),
                            'no_permintaan' => $no_permintaan,
                            'pasien_id'     => $pasien_id,
                            'tindakan_id'   => $tindakan_id,
                            'reason'        => 2,
                            'status'        => 1,
                            'is_active'     => 1,
                            'created_by'    => $this->session->userdata('user_id'),
                            'created_date'  => date('Y-m-d H:i:s')
                        );

                        //$buat_permintaan = $this->permintaan_dialyzer_baru_m->add_data($array_permintaan);
                        $dialyzer_new = 1;
                        $dialyzer_reuse = 0;
                    }
                   

                    $diagnosa = $this->input->post('diagnosa_awal');
                    foreach ($diagnosa as $diag) 
                    {
                        if($diag['name'] != '' && $diag['code_ast'] != '')
                        {
                            if($diag['is_deleted'] == '')
                            {
                                $data_diagnosa['tindakan_hd_id'] = $tindakan_id;
                                $data_diagnosa['kode_diagnosis'] = $diag['code_ast'];
                                $data_diagnosa['tipe']           = 1;
                                
                                $tindakan_hd_diagnosa_id = $this->tindakan_hd_diagnosa_m->save($data_diagnosa);
                            }
                        }

                    }

                    $last_id_ps_tindakan       = $this->pasien_tindakan_m->get_max_id()->result_array();
                    $last_id_ps_tindakan       = intval($last_id_ps_tindakan[0]['max_id'])+1;
                    
                    $format_id          = 'PT-'.date('my').'-%04d';
                    $id_tindakan_lain   = sprintf($format_id, $last_id_ps_tindakan, 4);

                    $data_pasien_tindakan = array(
                        'id'    => $id_tindakan_lain,
                        'pasien_id'    => $this->input->post('pasienid'),
                        'tindakan_id'    => $tindakan_id,
                        'tipe_tindakan'  => 1,
                        'status' => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_ps_tindakan_transfusi = $this->pasien_tindakan_m->add_data($data_pasien_tindakan);

                    if(isset($array_input['is_transfusi'])){

                        $last_id_transfusi       = $this->tindakan_transfusi_m->get_max_id()->result_array();
                        $last_id_transfusi       = intval($last_id_transfusi[0]['max_id'])+1;
                        
                        $format_id_transfusi     = 'TF-'.date('my').'-%04d';
                        $id_transfusi            = sprintf($format_id_transfusi, $last_id_transfusi, 4);

                        $data_tindakan_transfusi = array(
                            'id' => $id_transfusi,
                            'cabang_id' => $this->session->userdata('cabang_id'),
                            'pasien_id' => $this->input->post('pasienid'),
                            'tanggal'   => date('Y-m-d'),
                            'shift' => $array_input['shift'],
                            'jumlah_kantong_darah' => $array_input['jumlah_kantong_darah'],
                            'dokter_id' => $this->session->userdata('user_id'),
                            'status'    => 1,
                            'created_by'        => $this->session->userdata('user_id'),
                            'created_date'      => date('Y-m-d')
                        );

                        $save_tindakan_transfusi = $this->tindakan_transfusi_m->add_data($data_tindakan_transfusi);


                        $last_id_ps_tindakan       = $this->pasien_tindakan_m->get_max_id()->result_array();
                        $last_id_ps_tindakan       = intval($last_id_ps_tindakan[0]['max_id'])+1;
                        
                        $format_id          = 'PT-'.date('my').'-%04d';
                        $id_tindakan_lain   = sprintf($format_id, $last_id_ps_tindakan, 4);

                        $data_pasien_tindakan = array(
                            'id'    => $id_tindakan_lain,
                            'pasien_id'    => $this->input->post('pasienid'),
                            'tindakan_id'    => $id_transfusi,
                            'tipe_tindakan'  => 2,
                            'status' => 1
                        );

                        $save_ps_tindakan_transfusi = $this->pasien_tindakan_m->add_data($data_pasien_tindakan);

                        $tindakan_lain = $array_input['resep_transfusi'];

                        if(count($tindakan_lain) != 0){
                            $roman_month = romanic_number(date('m'), true);
                            $max_no = $this->tindakan_resep_obat_m->get_max_no($roman_month)->result_array();

                            if(count($max_no))
                            {
                                $last_nosurat = intval($max_no[0]['max_no']) + 1;
                            }
                            else
                            {
                                $last_nosurat = 1;
                            }

                            $format       = '#RSP#%03d';
                            $no_resep    = sprintf($format, $last_nosurat, 3);
                            $no_resep_new = $no_resep.'#RHS#'.$roman_month.'#'.date('Y');

                            $data_resep = array(
                                'cabang_id'     => $this->session->userdata('cabang_id'),
                                'nomor_resep'   => $no_resep_new,
                                'tindakan_id'   => $id_transfusi,
                                'tipe_tindakan' => 2,
                                'tipe_resep' => 1,
                                'pasien_id'     => $this->input->post('pasienid'),
                                'dokter_id'     => $this->session->userdata('user_id'),
                                'status'        => 1,
                                'is_active'     => 1
                            );
                            $resep_transfusi_id = $this->tindakan_resep_obat_m->save($data_resep);
                            
                        }


                        foreach ($tindakan_lain as $row_transfusi) {
                            
                            if($row_transfusi['tipe_obat'] == 'obat')
                            {
                                $data6 = array(
                                    'tindakan_id'            => $id_transfusi,
                                    'tipe_tindakan'          => 2,
                                    'tindakan_resep_obat_id' => $resep_transfusi_id,
                                    'tipe_item'              => 1,
                                    'item_id'                => $row_transfusi['tindakan_id'],
                                    'jumlah'                 => $row_transfusi['jumlah'],
                                    'satuan_id'              => $row_transfusi['satuan'],
                                    'dosis'                  => $row_transfusi['item_dosis'],
                                    'aturan'                 => $row_transfusi['item_aturan'],
                                    'bawa_pulang'            => 0,
                                    'is_active'              => 1
                                );

                                $resep_transfusi_detail_id = $this->tindakan_resep_obat_detail_m->save($data6);
                            }    
                        }
                    }

                    $id_bed = $this->input->post('bed_id');
                    $data_bed = $this->bed_m->get($id_bed);
                    
                    if($data_bed->status == 1 && $data_bed->status_antrian == 0){
                        $data88['status']=2;
                        $data88['user_edit_id']=NULL;
                        $data88['shift']=$array_input['shift'];
                    }if($data_bed->status != 1 && $data_bed->status_antrian == 0){
                        $data88['status_antrian']=1;
                    }
                    //update bed--->tunggu parameter
                    $bed_id=$this->bed_m->save($data88,$this->input->post('bed_id'));

                    $id_bed = $this->input->post('bed_id');
                    $data_bed = $this->bed_m->get($id_bed);

                    $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
                    $date = getDate();
                    $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                    file_put_contents($file,$jam);

                    //========input tindakan hd penaksiran====
                    $now=date("H:i:s");
                    $startdate=$now;
                    $date1=date_create($startdate);
                    date_add($date1,date_interval_create_from_date_string("4 hours"));
                    $enddate=date_format($date1,"H:i:s");

                    $data_penaksiran['blood_preasure']=$this->input->post('tdatas').'_'.$this->input->post('tdbawah');
                    $data_penaksiran['time_of_dialysis']=$this->input->post('time_dialisis');
                    $data_penaksiran['quick_of_blood']=$this->input->post('qb');
                    $data_penaksiran['quick_of_dialysis']=$this->input->post('qd');
                    $data_penaksiran['uf_goal']=$this->input->post('ufg');
                    if($this->input->post('keluhan')==null)
                    {
                        $data_penaksiran['assessment_cgs']="GCS:15\nKel(-)";
                    }else{
                        $data_penaksiran['assessment_cgs']="GCS:15\nKel(".$this->input->post('keluhan').")";
                    }
                    
                    $data_penaksiran['tindakan_hd_id']=$tindakan_id;
                    $data_penaksiran['pasien_id']=$this->input->post('pasienid');
                    $data_penaksiran['waktu']=$startdate.' - '.$enddate;
                    $data_penaksiran['tanggal']=date('Y-m-d');
                    // $data_penaksiran['machine_no']=$this->input->post('bed_id');
                    $data_penaksiran['machine_no']=$data_bed->kode;
                    $data_penaksiran['medical_diagnose']='CKD on HD';
                    $data_penaksiran['dialyzer_new']= $dialyzer_new;
                    $data_penaksiran['dialyzer_reuse']= $dialyzer_reuse;
                    $data_penaksiran['reason']= $reason;

                    $penaksiran = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $tindakan_id));
                    if(count($penaksiran) == 0)
                    {
                        $tindakan_hd_penaksiran_id=$this->tindakan_hd_penaksiran_m->save($data_penaksiran);                
                    }

                    $problem_pasien = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $tindakan_id));
                    if(count($problem_pasien) == 0)
                    {
                        // MENGISI DATA KE TABEL PASIEN_PROBLEM
                        for ($i=1; $i <= 6; $i++) 
                        { 
                            $problem = array(
                                'tindakan_hd_id' => $tindakan_id,
                                'pasien_id'      => $this->input->post('pasienid'),
                                'problem_id'     => $i,
                                'nilai'          => 0
                            );
                            $this->pasien_problem_m->save($problem);
                        }                
                    }
                    
                    $komplikasi_pasien = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $tindakan_id));
                    if(count($komplikasi_pasien) == 0)
                    {
                        // MENGISI DATA KE TABEL PASIEN_KOMPLIKASI
                        for ($i=1; $i <= 9; $i++) 
                        { 
                            $komplikasi = array(
                                'tindakan_hd_id' => $tindakan_id,
                                'pasien_id'      => $this->input->post('pasienid'),
                                'komplikasi_id'  => $i,
                                'nilai'          => 0
                            );
                            $this->pasien_komplikasi_m->save($komplikasi);
                        }

                    }
                 //   die(dump($this->db->last_query())); 

                    // if(in_array($pendaftaran->penjamin_id, config_item('penjamin_id')))
                    // {

                    //     $data_penjamin = $this->penjamin_m->get($penjamin_id);
                    //     $no_kartu = '';
                    //     $pas_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $this->input->post('pasienid'), 'penjamin_id' => $pendaftaran->penjamin_id, 'is_active' =>1), true);
                    //     if(count($pas_penjamin))
                    //     {
                    //         $no_kartu = $pas_penjamin->no_kartu;
                    //     }

                    //     $param = array(
                    //         'noKartu'      => $no_kartu, 
                    //         'tglSep'       => date('Y-m-d H:i:s'),
                    //         'tglRujukan'   => date('Y-m-d H:i:s', strtotime($pasien->ref_tanggal_rujukan)),
                    //         'noRujukan'    => $pasien->ref_nomor_rujukan, 
                    //         'ppkRujukan'   => $pasien->kode_faskes, 
                    //         'ppkPelayanan' => '0115R027', 
                    //         'jnsPelayanan' => '2', 
                    //         'catatan'      => 'HD '.date('d/m/Y'), 
                    //         'diagAwal'     => 'N18.9', 
                    //         'poliTujuan'   => 'HDL', 
                    //         'klsRawat'     => '3', 
                    //         'user'         => 'bpjskesehatan', 
                    //         'noMr'         => $pasien->no_member, 
                    //     );

                    //     create_sep($param);
                    // }

                    
                   

                    $data_pasien['berat_badan_kering']=$this->input->post('berat_kering');
                    $edit_pasien = $this->pasien_m->save($data_pasien,$this->input->post('pasienid'));

                    // $cabang = $this->cabang_m->get_by(array('tipe' => 0, 'is_active' => 1),true);
                    // $save_pasien = insert_pasien($data_pasien,$cabang->url,$this->input->post('pasienid'));
                    // $inserted_pasien_id = $save_pasien;
                    // $data_cabang = $this->cabang_m->get_by(array('tipe' => 1, 'is_active' => 1));
                    // foreach ($data_cabang as $cabang) 
                    // {
                    //     if($cabang->url != '' || $cabang->url != NULL)
                    //     {
                    //         $save_pasien = insert_pasien($data_pasien,$cabang->url,$this->input->post('pasienid'));
                    //     }
                    // }

                    if(isset($array_input['penyakit_bawaan']))
                    {
                        $data_cabang = $this->cabang_m->get_by('tipe in (1,0)');
                        $penyakit_bawaan = $array_input['penyakit_bawaan'];
                        $penyakit_penyebab = $array_input['penyakit_penyebab'];

                        foreach ($penyakit_bawaan as $penyakit_bawaan) 
                        {
                            $data_penyakit_bawaan = array(
                                'pasien_id'   => $this->input->post('pasienid'),
                                'penyakit_id' => $penyakit_bawaan,
                                'tipe'        => 1,
                                'is_active'   => '1',
                            );

                            $save_penyakit_bawaan = $this->pasien_penyakit_m->save($data_penyakit_bawaan); 
                            // $model = 'pasien_penyakit_m';
                            // $save_penyakit_bawaan = insert_pasien_hub_tlp_alm($data_penyakit_bawaan,base_url(),$model);
                            // $inserted_penyakit_bawan = $save_penyakit_bawaan;

                            // foreach ($data_cabang as $cabang) 
                            // {
                            //     if($cabang->is_active == 1)
                            //     {
                            //         if($cabang->url != '' || $cabang->url != NULL)
                            //         {
                            //             $save_penyakit_bawaan = insert_pasien_hub_tlp_alm($data_penyakit_bawaan,$cabang->url,$model,$inserted_penyakit_bawan);
                            //         }
                            //     }
                            // }
                                
                        }

                        foreach ($penyakit_penyebab as $penyakit_penyebab) 
                        {
                            $data_penyakit_penyebab = array(
                                'pasien_id'   => $this->input->post('pasienid'),
                                'penyakit_id' => $penyakit_penyebab,
                                'tipe'        => 2,
                                'is_active'   => '1',
                            );

                            $save_penyakit_penyebab = $this->pasien_penyakit_m->save($data_penyakit_penyebab); 
                            // $model = 'pasien_penyakit_m';
                            // $save_penyakit_penyebab = insert_pasien_hub_tlp_alm($data_penyakit_penyebab,base_url(),$model);
                            // $inserted_penyakit_pyb = $save_penyakit_penyebab;

                            // foreach ($data_cabang as $cabang) 
                            // {
                            //     if($cabang->url != '' || $cabang->url != NULL)
                            //     {
                            //         $save_penyakit_penyebab = insert_pasien_hub_tlp_alm($data_penyakit_penyebab,$cabang->url,$model,$inserted_penyakit_pyb);
                            //     }
                            // }
                                
                        }
                    }
                    // $get_pasien_id=$this->pasien_m->save($data_pasien,$this->input->post('pasienid'));


                    $cabang = $this->cabang_m->get(1);
                    $delete_daftar = $this->pendaftaran_tindakan_m->delete_by(array('id' => $array_input['pk']));

                    // $data_status_pendaftaran_tindakan['status']=2;
                    // // $get_data_status_pendaftaran_tindakan=$this->pendaftaran_tindakan_m->save($data_status_pendaftaran_tindakan,$this->input->post('pk'));
                    // update_pendaftaran($data_status_pendaftaran_tindakan,base_url(),$this->input->post('pk'));
                    // // update_pendaftaran($data_status_pendaftaran_tindakan,$cabang->url,$this->input->post('pk'));
                //========================================
                    $pasien_id = $this->input->post('pasienid');
                    $data_traveling = $this->surat_traveling_m->get_last_date($pasien_id)->result_array();

                    foreach ($data_traveling as $traveling) {
                        $trv = array(
                            'status' => 1
                        );

                        $update_traveling = $this->surat_traveling_m->save($trv,$traveling['id']);
                    }

                     foreach ($klaim as $key=>$value) {
                        if(isset($value)){
                            
                            $data4['transaksi_id']=$tindakan_id;
                            $data4['penjamin_id']=$value;
                            $data4['pasien_id']=$this->input->post('pasienid');
                            $data4['tipe']=1;
                            $data4['is_active']=1;
                            $pasien_klaim_id=$this->pasien_klaim_m->save($data4);
                        }
                    }

                   
                        $resep=$this->input->post('resep');
                        $cabang_apotik = $this->cabang_m->get_by(array('id' => config_item('apotik_id')), true);
                        if(count($resep) != 0){
                            if($resep[1]['tindakan_id']!= null){
                                $roman_month = romanic_number(date('m'), true);
                                $max_no = $this->tindakan_resep_obat_m->get_max_no($roman_month)->result_array();

                                if(count($max_no))
                                {
                                    $last_nosurat = intval($max_no[0]['max_no']) + 1;
                                }
                                else
                                {
                                    $last_nosurat = 1;
                                }

                                $format       = '#RSP#%03d';
                                $no_resep    = sprintf($format, $last_nosurat, 3);
                                $no_resep_new = $no_resep.'#RHS#'.$roman_month.'#'.date('Y');

                                $data_resep = array(
                                    'cabang_id'     => $this->session->userdata('cabang_id'),
                                    'nomor_resep'   => $no_resep_new,
                                    'tindakan_id'   => $tindakan_id,
                                    'tipe_tindakan' => 1,
                                    'tipe_resep' => 1,
                                    'pasien_id'     => $this->input->post('pasienid'),
                                    'dokter_id'     => $this->session->userdata('user_id'),
                                    'status'        => 1,
                                    'is_active'     => 1
                                );
                                $resep_id = $this->tindakan_resep_obat_m->save($data_resep);

                                // $path_model = 'klinik_hd/tindakan_resep_obat_m';
                                // $resep_id = insert_data_api($data_resep,$cabang_apotik->url,$path_model);
                                // $resep_id = str_replace('"', '', $resep_id);

                            }
                            
                        }

                        foreach($resep as $row){
                            if($row['tindakan_id']!= null){
                                $bawa_pulang = (isset($row['item_bawa']))?$row['item_bawa']:0;
                                if($row['tipe_obat'] == 'obat')
                                {
                                    $data6 = array(
                                        'tindakan_id'            => $tindakan_id,
                                        'tipe_tindakan'          => 1,
                                        'tindakan_resep_obat_id' => $resep_id,
                                        'tipe_item'              => 1,
                                        'item_id'                => $row['tindakan_id'],
                                        'jumlah'                 => $row['jumlah'],
                                        'satuan_id'              => $row['satuan'],
                                        'dosis'                  => $row['item_dosis'],
                                        'aturan'                 => $row['item_aturan'],
                                        'bawa_pulang'            => $bawa_pulang,
                                        'is_active'              => 1
                                    );

                                    $resep_detail_id = $this->tindakan_resep_obat_detail_m->save($data6);
                                    // $path_model = 'klinik_hd/tindakan_resep_obat_detail_m';
                                    // $resep_detail_id = insert_data_api($data6,$cabang_apotik->url,$path_model);
                                    // $resep_detail_id = str_replace('"', '', $resep_detail_id);
                                }    
                            }    
                        }
                       
                        $resepmanual=$this->input->post('resepmanual');
                        if(count($resepmanual) != 0){
                            if($resepmanual[1]['keterangan']!= ''){
                                $roman_month = romanic_number(date('m'), true);
                                $max_no = $this->tindakan_resep_obat_m->get_max_no($roman_month)->result_array();

                                if(count($max_no))
                                {
                                    $last_nosurat = intval($max_no[0]['max_no']) + 1;
                                }
                                else
                                {
                                    $last_nosurat = 1;
                                }

                                $format       = '#RSP#%03d';
                                $no_resep    = sprintf($format, $last_nosurat, 3);
                                $no_resep_new = $no_resep.'#RHS#'.$roman_month.'#'.date('Y');

                                $data_resep_manual = array(
                                    'cabang_id'   => $this->session->userdata('cabang_id'),
                                    'nomor_resep'   => $no_resep_new,
                                    'tindakan_id'   => $tindakan_id,
                                    'tipe_tindakan' => 1,
                                    'tipe_resep'    => 0,
                                    'pasien_id'     => $this->input->post('pasienid'),
                                    'dokter_id'     => $this->session->userdata('user_id'),
                                    'status'        => 1,
                                    'is_active'     => 1
                                );
                                $resep_manual_id = $this->tindakan_resep_obat_m->save($data_resep_manual);

                                // $path_model = 'klinik_hd/tindakan_resep_obat_m';
                                // $resep_manual_id = insert_data_api($data_resep_manual,$cabang_apotik->url,$path_model);
                                // $resep_manual_id = str_replace('"', '', $resep_manual_id);
                            }
                        }
                        foreach($resepmanual as $row){
                            if($row['keterangan']!= ''){
                                $data7 = array(
                                    'cabang_id'   => $this->session->userdata('cabang_id'),
                                    'tindakan_id'            => $tindakan_id,
                                    'tipe_tindakan'          => 1,
                                    'tindakan_resep_obat_id' => $resep_manual_id,
                                    'keterangan'             => $row['keterangan'],
                                    'is_active'              => 1
                                );
                                $resep_detail_id = $this->tindakan_resep_obat_manual_m->save($data7);

                                // $path_model = 'klinik_hd/tindakan_resep_obat_manual_m';
                                // $resep_detail_id = insert_data_api($data7,$cabang_apotik->url,$path_model);
                                // $resep_detail_id = str_replace('"', '', $resep_detail_id);   
                            }
                        }

                    $last_id       = $this->permintaan_box_paket_m->get_max_id_permintaan()->result_array();
                    $last_id       = intval($last_id[0]['max_id'])+1;
                    
                    $format_id     = 'BX-'.date('m').'-'.date('Y').'-%04d';
                    $id_permintaan = sprintf($format_id, $last_id, 4);
                    
                    $format        = '#BX#%04d/RHS/'.romanic_number(date('m'), true).'/'.date('Y');
                    $no_permintaan = sprintf($format, $last_id, 4);

                    $array_permintaan = array(
                        'id'            => $id_permintaan,
                        'no_permintaan' => $no_permintaan,
                        'tanggal'       => date('Y-m-d'),
                        'pasien_id'     => $this->input->post('pasienid'),
                        'tindakan_id'   => $tindakan_id,
                        'status'        => 1,
                        'is_active'     => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'  => date('Y-m-d H:i:s')
                    );

                    $insert_permintaan_box = $this->permintaan_box_paket_m->add_data($array_permintaan);

                    $inserted_id = $tindakan_id;
                    $nama_pasien = str_replace(' ', '_', $pasien->nama);
                    sent_notification(2,$nama_pasien,$inserted_id);

                    //$data_antrian = $this->antrian_pasien_m->get_by(array('pasien_id' => $this->input->post('pasienid'), 'posisi_loket' => 3, 'status' => 1), true);
                    $data_antrian = $this->antrian_pasien_m->get_by(array('pasien_id' => $this->input->post('pasienid'), 'posisi_loket' => 3, 'date(created_date)' => date('Y-m-d')), true);

                    $last_id       = $this->antrian_pasien_m->get_max_id()->result_array();
                    $last_id       = intval($last_id[0]['max_id'])+1;
                    
                    $format_id     = 'ANT-'.date('m').'-'.date('Y').'-%04d';
                    $id_antrian    = sprintf($format_id, $last_id, 4);

                    $no_urut = $this->antrian_pasien_m->get_max_no_urut_dokter(5,$this->session->userdata('user_id'))->result_array();
                    $no_urut = intval($no_urut[0]['max_no_urut'])+1;



                    $data_simpan = array(
                        'id'    => $id_antrian,
                        "dokter_id"           => $this->session->userdata('user_id'),
                        "pasien_id"           => $data_antrian->pasien_id,
                        'nama_pasien' => $data_antrian->nama_pasien,
                        'no_telp' => $data_antrian->no_telp,
                        'posisi_loket' => 5,
                        'status' => 0,
                        'no_urut' => $no_urut,
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_antrian = $this->antrian_pasien_m->add_data($data_simpan);

                    $delete_antrian_prev = $this->antrian_pasien_m->delete_by(array('id' => $data_antrian->id));

                    $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
                    $date = getDate();
                    $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                    file_put_contents($file,$jam);

                    $response = new stdClass;
                    $response->success = false;
                    $response->msg = translate('Data Tindakan Gagal Disimpan', $this->session->userdata('language'));

                    if($tindakan_id){
                        $response->success = true;
                        $response->msg = translate('Data Tindakan Berhasil Disimpan. Lanjutkan untuk mencetak surat persetujuan?', $this->session->userdata('language'));
                        $response->tindakan_id = $tindakan_id;
                        $response->pasien_id = $this->input->post('pasienid');
                    }

                    die(json_encode($response)); 
                    if(in_array($pendaftaran->penjamin_id, config_item('penjamin_id')))
                    {

                        $data_penjamin = $this->penjamin_m->get($penjamin_id);
                        $no_kartu = '';
                        $pas_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $this->input->post('pasienid'), 'penjamin_id' => $pendaftaran->penjamin_id, 'is_active' =>1), true);
                        if(count($pas_penjamin))
                        {
                            $no_kartu = $pas_penjamin->no_kartu;
                        }

                        $param = array(
                            'noKartu'      => $no_kartu, 
                            'tglSep'       => date('Y-m-d H:i:s'),
                            'tglRujukan'   => date('Y-m-d H:i:s', strtotime($pasien->ref_tanggal_rujukan)),
                            'noRujukan'    => $pasien->ref_nomor_rujukan, 
                            'ppkRujukan'   => $pasien->kode_faskes, 
                            'ppkPelayanan' => '0115R027', 
                            'jnsPelayanan' => '2', 
                            'catatan'      => 'HD '.date('d/m/Y'), 
                            'diagAwal'     => 'N18.9', 
                            'poliTujuan'   => 'HDL', 
                            'klsRawat'     => '3', 
                            'user'         => 'bpjskesehatan', 
                            'noMr'         => $pasien->no_member, 
                        );

                        $data_sep = '<?xml version="1.0" encoding="UTF-8"?>
                        <request>
                             <data>
                              <t_sep>
                               <noKartu>'.$no_kartu.'</noKartu>
                               <tglSep>'.date('Y-m-d H:i:s').'</tglSep>
                               <tglRujukan>'.date('Y-m-d H:i:s', strtotime($pasien->ref_tanggal_rujukan)).'</tglRujukan>
                               <noRujukan>'.$pasien->ref_nomor_rujukan.'</noRujukan>
                               <ppkRujukan>'.$pasien->kode_faskes.'</ppkRujukan>
                               <ppkPelayanan>0115R027</ppkPelayanan>
                               <jnsPelayanan>2</jnsPelayanan>
                               <catatan>HD '.date('d/m/Y').'</catatan>
                               <diagAwal>N18.9</diagAwal>
                               <poliTujuan>HDL</poliTujuan>
                               <klsRawat>3</klsRawat>
                               <user>bpjskesehatan</user>
                               <noMr>'.$pasien->no_member.'</noMr>
                              </t_sep>
                             </data>
                            </request>';

                        // die(dump(create_sep($data_sep)));
                    }
                }
            }
        }
 
        

        elseif ($command2 === 'edit_observasi')
        {
            $data['user_id'] = $this->input->post('user_id3');
            $data['waktu_pencatatan'] = date("H:i", strtotime($this->input->post('jam')));
            $data['tekanan_darah_1'] = $this->input->post('tda3');
            $data['tekanan_darah_2'] = $this->input->post('tdb3');
            $data['ufg'] = $this->input->post('ufg');
            $data['ufr'] = $this->input->post('ufr');
            $data['ufv'] = $this->input->post('ufv');
            $data['qb'] = $this->input->post('qb');
            $data['keterangan'] = $this->input->post('keterangan');
            
            $observasi_id = $this->observasi_m->save($data, $this->input->post('observasi_id'));

           
            if ($observasi_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Observasi Dialisis berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        elseif ($command2 === 'edit')
        {  
          
           $klaim=$this->input->post('pilihklaim3');
          //   // $data = $this->purchase_order_m->array_from_post( $this->purchase_order_m->fillable());
            $cabang_id=$this->user_m->get($this->session->userdata("user_id"));
            $cabang_id=object_to_array($cabang_id);

          //   $last_number    = $this->tindakan_hd_m->get_nomor_po($cabang_id['cabang_id'])->result_array();
          // //  die(dump($this->db->last_query()));
          //   $last_number    = intval($last_number[0]['max_nomor_po'])+1;

          //   $format         = 'R'.$cabang_id['cabang_id'].'HD-'.date('ym').'-%04d';
          //   $po_number       = sprintf($format, $last_number, 4);



           // $data['no_transaksi']=$po_number;
          //  $data['pasien_id']=$this->input->post('pasienid');
            $data['dokter_id']=$this->session->userdata("user_id");
            //$data['tanggal']=date('Y-m-d H:i:s');

           // $cabang_id=$this->user_m->get($this->session->userdata("user_id"));
            
            $data['cabang_id']=$cabang_id['cabang_id'];

            foreach ($klaim as $key=>$value) {
                if(isset($value)){
                    $data['penjamin_id']=$value;
                    if($value==1)
                    {
                        $data['is_claimed']=0;
                    }else{
                       $data['is_claimed']=1;    
                    }


                }
                 
            }
           
          //  $data['status']=1;
           // $data['jangka_waktu']=$this->input->post('freq');
            $data['berat_awal']=$this->input->post('berat');
            //insert bed--->tunggu parameter
            $data['bed_id']=1;
           // $data['rupiah']=1;
          //  $data['is_active']=1;

            $tindakan_id=$this->tindakan_hd_m->save($data,$this->input->post('transid'));

            $paket=$this->input->post('paket2');
            $jumlah='';
            foreach($paket as $row)
            {
                if($row['idpaket']!=null){
                    if($row['flag']==0 && $row['flag']!=null)
                    {
                        $this->tagihan_paket_m->delete($row['id']);
                    }else if($row['flag']==null){
                        $data2['tindakan_hd_id']=$tindakan_id;
                        $data2['paket_id']=$row['idpaket'];
                        $data2['is_use']=1;
                        $tindakan_hd_paket_id=$this->tagihan_paket_m->save($data2);
                        // die(dump($this->db->last_query()));
                        //echo 'satu';
                        $jumlah+=$row['harga'];
                    }else{
                        $jumlah+=$row['harga'];
                    }
                   
                    
                }
               
            }

            // $data8['status']=2;
            // //update bed--->tunggu parameter
            // $bed_id=$this->bed_m->save($data8,1);

            $now=date("H:i:s");
            $startdate=$now;
            $date1=date_create($startdate);
            date_add($date1,date_interval_create_from_date_string("4 hours"));
            $enddate=date_format($date1,"Y-m-d H:i:s");

            $data_penaksiran['blood_preasure']=$this->input->post('tdatas').'_'.$this->input->post('tdbawah');
            $data_penaksiran['time_of_dialysis']=$this->input->post('time_dialisis');
            $data_penaksiran['quick_of_blood']=$this->input->post('qb');
            $data_penaksiran['quick_of_dialysis']=$this->input->post('qd');
            $data_penaksiran['uf_goal']=$this->input->post('ufg');
            if($this->input->post('keluhan')==null)
            {
                $data_penaksiran['assessment_cgs']="GCS:15\nKel(-)";
            }else{
                $data_penaksiran['assessment_cgs']=$this->input->post('keluhan');
            }
            
            $id_bed = $this->input->post('bed_id');
            $data_bed = $this->bed_m->get($id_bed);

            $data_penaksiran['waktu']=$startdate.' - '.$enddate;
            $data_penaksiran['tanggal']=date('Y-m-d');
            $data_penaksiran['machine_no']=$data_bed->kode;
         
            $get_id_penaksiran=$this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' =>$this->input->post('transid')));
            foreach($get_id_penaksiran as $row)
            {
                 $tindakan_hd_penaksiran_id=$this->tindakan_hd_penaksiran_m->save($data_penaksiran,$row->id);
            }
           
         //   die(dump($this->db->last_query())); 

            $data_pasien['berat_badan_kering']=$this->input->post('berat_kering');
            $get_pasien_id=$this->pasien_m->save($data_pasien,$this->input->post('pasienid'));


             foreach ($klaim as $key=>$value) {
                if(isset($value)){
                    
                   // $data4['transaksi_id']=$tindakan_id;
                    $data4['penjamin_id']=$value;
                   // $data4['pasien_id']=$this->input->post('pasienid');
                   // $data4['tipe']=1;
                   // $data4['is_active']=1;
                    $getidklaim=$this->pasien_klaim_m->get_by(array('transaksi_id'=>$tindakan_id));
                    foreach($getidklaim as $row)
                    {
                             $pasien_klaim_id=$this->pasien_klaim_m->save($data4,$row->id);
                    }
                   


                    
                }
                 
            }

            $data5['rupiah']=$jumlah;
            $this->tindakan_hd_m->save($data5,$tindakan_id);

           
          // $getidresep=$this->tindakan_resep_obat_m->get_by(array('tindakan_id'=>$tindakan_id));
           // foreach($getidresep as $row)
           // {
           //       $this->tindakan_resep_obat_m->delete($row->id);
           // }

           $resep=$this->input->post('resep');
           foreach($resep as $row){
            if($row['tindakan_id']!=null || $row['tindakan_id2']!=null){
                if($row['flag1']==0)
                {
                    $data6['is_active']=0;
                    $this->tindakan_resep_obat_m->save($data6,$row['id1']);

                    if($row['tipe_obat']=='racikan')
                    {
                        $this->resep_obat_racikan_m->save($data6,$row['tindakan_id']);

                        $getidracikanmanual=$this->resep_obat_racikan_detail_manual_m->get_by(array('resep_obat_racikan_detail_id'=>$row['tindakan_id']));
                        foreach($getidracikanmanual as $row)
                        {
                             $this->resep_obat_racikan_detail_manual_m->save($data6,$row->id);
                        }
                       
                    } 
                }else if($row['flag1']==2)
                {
                    if($row['tipe_obat']=='obat')
                    {
                      //  echo 'hahahah';
                        $data68['tindakan_id']=$tindakan_id;
                        $data68['tipe_tindakan']=1;
                        $data68['pasien_id']=$this->input->post('pasienid');
                
                        $data68['tipe_item']=1;
                        $data68['item_id']=$row['tindakan_id'];
                     
                        $data68['jumlah']=$row['jumlah'];
                        $data68['satuan_id']=$row['satuan'];
                        $data68['dosis']=$row['item_dosis'];
                        $data68['is_active']=1;

                        $resep_id=$this->tindakan_resep_obat_m->save($data68);
                    }else{
                        // echo 'hihihihi';
                        $data11['nama']=$row['nama'];
                        $data11['keterangan']=$row['keteranganmodal'];
                        $data11['status']=1;
                        $data11['is_active']=1;
                        $resep_obat_racikan_id=$this->resep_obat_racikan_m->save($data11);

                        $resep1=$this->input->post('1resep');
                        foreach($resep1 as $row2)
                        {
                            if($row2['itemrow2']!=null)
                            {
                                 if($row2['itemrow2']==$row['itemrow'])
                                 {
                                    $data9['resep_racik_obat_id']=$resep_obat_racikan_id;
                                    $data9['item_id']=$row2['tindakan_id1'];
                                    $data9['item_satuan_id']=$row2['satuan1'];
                                    $data9['jumlah']=$row2['jumlah1'];
                                    $resep_obat_racikan_detail_id=$this->resep_obat_racikan_detail_m->save($data9);
                                 }
                            }
                           
                      
                        }

                        $resep2=$this->input->post('resepmanual3');
                        foreach($resep2 as $row3)
                        {
                             if($row3['itemrow3']!=null)
                                 {
                                         if($row3['itemrow3']==$row['itemrow'])
                                        {
                                            $data10['resep_obat_racikan_id']=$resep_obat_racikan_id;
                                            $data10['keterangan']=$row3['keterangan11'];
                                            $data10['is_active']=1;
                                            $resep_obat_racikan_detail_manual_id=$this->resep_obat_racikan_detail_manual_m->save($data10);
                                        }
                                 }
                           
                       
                        }

                            $data69['tindakan_id']=$tindakan_id;
                            $data69['tipe_tindakan']=1;
                            $data69['pasien_id']=$this->input->post('pasienid');
                
                            $data69['tipe_item']=2;
                            $data69['item_id']=$resep_obat_racikan_id;
                     
                            $data69['jumlah']=$row['jumlah'];
                       // $data69['satuan_id']=$row['satuan'];
                      //  $data69['dosis']=$row['item_dosis'];
                            $data69['is_active']=1;
                            $resep_id2=$this->tindakan_resep_obat_m->save($data69);

                        // $data6['tindakan_id']=$tindakan_id;
                        // $data6['tipe_tindakan']=1;
                        // $data6['pasien_id']=$this->input->post('pasienid');
                    
                        // $data6['tipe_item']=2;
                        // $data6['item_id']=$resep_obat_racikan_id;
                    
               
                        // $data6['jumlah']=$row['jumlah'];
                        // $data6['satuan_id']=$row['satuan'];
                        // $data6['dosis']=$row['item_dosis'];
                        // $data6['is_active']=1;

                        // $resep_id=$this->tindakan_resep_obat_m->save($data6);
                    }
                }else{
                    if($row['tipe_obat']=='obat')
                    {
                        // echo 'heheheh';
                      //  $data6['tindakan_id']=$tindakan_id;
                     //   $data6['tipe_tindakan']=1;
                      //  $data6['pasien_id']=$this->input->post('pasienid');
                
                      //  $data6['tipe_item']=1;
                        $data67['item_id']=$row['tindakan_id'];
                     
                        $data67['jumlah']=$row['jumlah'];
                        $data67['satuan_id']=$row['satuan'];
                        $data67['dosis']=$row['item_dosis'];
                       // $data6['is_active']=1;

                        $resep_id=$this->tindakan_resep_obat_m->save($data67,$row['id1']);
                    }
                }
                
            }
                
           }
           
           // $getidresepmanual=$this->tindakan_resep_obat_manual_m->get_by(array('tindakan_id'=>$tindakan_id));
           // foreach($getidresepmanual as $row)
           // {
           //       $this->tindakan_resep_obat_manual_m->delete($row->id);
           // }

            $resepmanual=$this->input->post('resepmanual');
            foreach($resepmanual as $row){
              if($row['keterangan']!=null ){
                    if($row['flag4']==0)
                    {
                        $data7['is_active']=0;
                        $this->tindakan_resep_obat_manual_m->save($data7,$row['id2']);
                    }else if($row['flag4']=='2')
                    {
                      
                        $data77['tindakan_id']=$tindakan_id;
                        $data77['tipe_tindakan']=1;
                        $data77['pasien_id']=$this->input->post('pasienid');
                        $data77['keterangan']=$row['keterangan'];
                        $data77['is_active']=1;

                        $resep_manual_id1=$this->tindakan_resep_obat_manual_m->save($data77);   
                    }else{
                        
                        $data78['keterangan']=$row['keterangan'];
                        $resep_manual_id2=$this->tindakan_resep_obat_manual_m->save($data78,$row['id2']);   
                    }
                    
                }
             }

            
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data poliklinik berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            
            
        }

        redirect("klinik_hd/transaksi_dokter/");
        // redirect('print_persetujuan/'.$tindakan_id.'/'.$this->input->post('pasienid'));

    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->cabang_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id'   => $trash_id,
            'tipe'              => 2,
            'data_id'           => $id,
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Branch Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/cabang");
    }

    public function deleteajax()
    {
        
        $id     = $this->input->post('id');
        
       
            $data = array(
                'is_active'    => 0
            );
            $msg = "Dokumen sudah dihapus";

         
        // save data
        $user_id = $this->transaksi_dokter3_m->save($data, $id);

        $trash_id='';
        $max_id = $this->kotak_sampah_m->max2();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // Poliklinik
        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 2,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->save($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "error",
                translate($msg, $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
       // redirect("master/cabang");
    }

     public function deleteajax2()
    {
        
        $id     = $this->input->post('id');
        
       
            $data = array(
                'is_active'    => 0
            );
         

         $msg = "Observasi sudah dihapus";
        // save data

        $user_id = $this->observasi_m->save($data, $id);

     
      

        if ($user_id) 
        {
            $flashdata = array(
                "error",
                translate($msg, $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
       // redirect("master/cabang");
    }

    public function restoreajax()
    {
        
        $id     = $this->input->post('id');
        
       
            $data = array(
                'is_active'    => 1
            );
         

         $msg = "Observasi sudah direstore";
        // save data

        $user_id = $this->observasi_m->save($data, $id);

     
      

        if ($user_id) 
        {
            $flashdata = array(
                "error",
                translate($msg, $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
       // redirect("master/cabang");
    }


    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->cabang_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Branch Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/cabang");
    }

    public function formatrupiah($val) {
        $hasil ='Rp. ' . number_format($val, 0 , '' , '.' ) . ',-';
        return $hasil;
    }

    public function insertharga()
    {
        
        $id=$this->input->post('id');
        $tggl=$this->input->post('tggl');
        $harga=$this->input->post('harga');
 
        // save data
        $data['poliklinik_tindakan_id']=$id;
        $data['tanggal']=date('Y-m-d',strtotime($tggl));
        $data['harga']=$harga;
        $data['is_active']=1;
        $user_id = $this->poliklinik_harga_tindakan_m->save($data);

       
        if ($user_id) 
        {
            $flashdata = array("success",translate("Harga telah ditambahkan", $this->session->userdata("language")),translate("Sukses", $this->session->userdata("language")));
            echo json_encode($flashdata);
          //  $this->session->set_flashdata($flashdata);
        }
       // redirect("master/cabang");
    }

    public function getdata()
    {
        
        $id=$this->input->post('id');
        
        $form_data=$this->poliklinik_tindakan_m->getdata($id)->result_array();
        echo json_encode($form_data);
           
         
       // redirect("master/cabang");
    }

    public function getdata2()
    {
        
        $id=$this->input->post('id');
        
        $form_data=$this->poliklinik_m->getdata($id)->result_array();
      //  $form_data=object_to_array($form_data);
        echo json_encode($form_data);
           
         
       // redirect("master/cabang");
    }

     public function saveajax()
    {
        
        $tindakan_id=$this->input->post('tindakan_id');
        $pk=$this->input->post('pk');
        
        // save data
       if($tindakan_id!='')
        {
            $data1['poliklinik_id']=$pk;
            $data1['tindakan_id']=$tindakan_id;
            $data1['is_active'] = 1;
            $result=$this->poliklinik_tindakan_m->save($data1);
         }

       
        if ($result) 
        {
            $flashdata = array(
                "success",
                translate("Tindakan telah ditambahkan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language")),
                $result
            );
            echo json_encode($flashdata);
          //  $this->session->set_flashdata($flashdata);
        }
       // redirect("master/cabang");
    }

     public function getdataajax()
    {
        
        
        $pk=$this->input->post('pk');
        
        // save data
        $result=$this->poliklinik_tindakan_m->getdata2($pk)->result_array();
       
        echo json_encode($result);
    }

    public function deletetindakanajax()
    {
        
        $id=$this->input->post('id');
        $pk=$this->input->post('pk');

        $data = array(
            'is_active'    => 0
        );

        $user_id = $this->poliklinik_tindakan_m->save($data, $id);
        // die_dump($user_id);

        $trash_id='';
        $max_id = $this->kotak_sampah_m->max2();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // 3 Poliklinik Tindakan
        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 3,
            'data_id'         => $pk,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

         $trash = $this->kotak_sampah_m->simpan($data_trash);

       
        if ($user_id) 
        {
            $flashdata = array(
                "error",
                translate("Poliklinik tindakan dihapus", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );
            echo json_encode($flashdata);
         }
        
        // save data
       // $result=$this->poliklinik_tindakan_m->getdata2($pk)->result_array();
       
         
    }

    public function cancel_tindakan($id)
    {
        $cabang = $this->cabang_m->get(11);

        $daftar = $this->pendaftaran_tindakan_m->get_by(array('id' => $id), true);
        $pasien = $this->pasien_m->get($daftar->pasien_id);

        $data_status_pendaftaran_tindakan['status']=0;

        $this->pendaftaran_tindakan_m->edit_data($data_status_pendaftaran_tindakan, $id);


        // update_pendaftaran($data_status_pendaftaran_tindakan,base_url(),$id);
        // update_pendaftaran($data_status_pendaftaran_tindakan,$cabang->url,$id);

        $nama_pasien = str_replace(' ', '_', $pasien->nama);

        // sent_notification(5,$nama_pasien,$id); 
        // $filename = urlencode(base64_encode($this->session->userdata('url_login')));
        // $all_cabang = $this->cabang_m->get();
        // foreach ($all_cabang as $cabang) 
        // {
        //     change_file_notif($cabang->url,$filename);                    
        // }
        
        $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Tindakan pasien dibatalkan.", $this->session->userdata("language")),
            "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
      
        redirect('klinik_hd/transaksi_dokter');
    }

    public function kembalikan_tindakan($id)
    {
        $data_tindakan['status'] = 1;
        $data_tindakan['is_active'] = 1;

        $this->tindakan_hd_m->edit_data($data_tindakan,$id);

         $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
        $date = getDate();
        $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
        file_put_contents($file,$jam);

        $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Tindakan pasien dikembalikan.", $this->session->userdata("language")),
            "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
      
        redirect('klinik_hd/transaksi_dokter');
    }

    public function hapus_tindakan()
    {
        $array_input = $this->input->post();
        $response = new stdClass;

        $id = $array_input['tindakan_hd_id'];
        $tindakan = $this->tindakan_hd_m->get($id);

        $bed_id = $tindakan->bed_id;
        $pendaftaran_id = $tindakan->pendaftaran_tindakan_id;

        $cabang = $this->cabang_m->get(1);
        $data_status_pendaftaran_tindakan['status']=0;

        $batal_daftar = $this->pendaftaran_tindakan_history_m->save($data_status_pendaftaran_tindakan, $pendaftaran_id);
        // update_pendaftaran($data_status_pendaftaran_tindakan,base_url(),$pendaftaran_id);
        // update_pendaftaran($data_status_pendaftaran_tindakan,$cabang->url,$pendaftaran_id);

        $data_tindakan['status'] = 5;
        $data_tindakan['is_active'] = 0;
        $data_tindakan['keterangan_tolak'] = ($tindakan->keterangan_tolak != '' || $tindakan->keterangan_tolak != NULL)?$tindakan->keterangan_tolak."\n".$array_input['keterangan_tolak']:$array_input['keterangan_tolak'];

        $data_bed = array(
            'status'    => 1,
            'user_edit_id'    => NULL,
        );
        $update_bed_id = $this->bed_m->save($data_bed, $bed_id);

        $this->tindakan_hd_m->save($data_tindakan,$id);

        $response->success = true;

        $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
        $date = getDate();
        $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
        file_put_contents($file,$jam);

        die(json_encode($response));

    }

    public function keterangan_batal($id)
    {
        $tindakan = $this->tindakan_hd_m->get($id);

        $data = array(
            'id'    => $id,
            'keterangan_tolak'  => $tindakan->keterangan_tolak
        );
        $this->load->view('klinik_hd/transaksi_dokter/modal/keterangan_tolak', $data);
    }

    public function add_dokumen($pasien_id)
    {
        $data = array(
            'pasien' => object_to_array($this->pasien_m->get($pasien_id))
        );
        $this->load->view('klinik_hd/transaksi_dokter/modal/tambah_dokumen_pasien', $data);
    }

    public function edit_dokumen($pasien_dok_id)
    {
        $this->load->model('master/dokumen_m');
        $this->load->model('master/pasien_dokumen_m');
        $data = array(
            'pasien_dokumen' => object_to_array($this->pasien_dokumen_m->get($pasien_dok_id))
        );
        $this->load->view('klinik_hd/transaksi_dokter/modal/edit_dokumen_pasien', $data);
    }

    public function save_dokumen()
    {
        $array_input = $this->input->post();

        $data_cabang = $this->cabang_m->get_by(array('tipe' => 0, 'is_active' => 1));

        $pasien_id = $array_input['pasien_id'];
        $dokumen_id = $array_input['dokumen_id'];
        $penjamin_dokumen = $array_input['penjamin_dokumen'];
        $data_pasien = $this->pasien_m->get($pasien_id);

        $response = new stdClass;
        $response->success = false;

        if($array_input['command'] == "add") 
        {
            foreach ($penjamin_dokumen as $penj_dok) 
            {
                $data_penj_dok = array(
                    'pasien_id'          => $pasien_id,
                    'dokumen_id'         => $penj_dok['dokumen_id'],
                    'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                    'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                    'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                    'is_active'         => 1
                );

                $path_model = 'master/pasien_dokumen_m';
                $pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model);
                $inserted_pasien_dokumen_id = $pasien_dokumen_id;
                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model,$inserted_pasien_dokumen_id);
                    }
                }

                $inserted_pasien_dokumen_id = str_replace('"', '', $inserted_pasien_dokumen_id);

                $response->success = true;

                $data_dok_history = array(
                    'pasien_id'          => $pasien_id,
                    'dokumen_id'         => $penj_dok['dokumen_id'],
                    'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                    'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                    'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                    'is_active'          => 1
                );

                $path_model = 'master/pasien_dok_history_m';
                $pasien_dok_history_id = insert_data_api($data_dok_history,base_url(),$path_model);
                $inserted_dok_history_id = $pasien_dok_history_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $pasien_dok_history_id = insert_data_api($data_dok_history,$cabang->url,$path_model,$inserted_dok_history_id);
                    }
                }
                $inserted_dok_history_id = str_replace('"', '', $inserted_dok_history_id);

                $penjamin_dokumen_detail = $array_input['penjamin_dokumen_detail_'.$penj_dok['dokumen_id']];
                if(isset($penjamin_dokumen_detail))
                {
                    foreach ($penjamin_dokumen_detail as $penj_dok_det) 
                    {
                        $data_penj_dok_det = array(
                            'pasien_dokumen_id' => $inserted_pasien_dokumen_id,
                            'judul'             => $penj_dok_det['judul'],
                            'tipe'              => $penj_dok_det['tipe'],
                            'is_active'         => 1
                        );

                        $path_model = 'master/pasien_dokumen_detail_m';
                        $pasien_dok_det_id = insert_data_api($data_penj_dok_det,base_url(),$path_model);
                        $inserted_pas_dok_det_id = $pasien_dok_det_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok_det_id = insert_data_api($data_penj_dok_det,$cabang->url,$path_model,$inserted_pas_dok_det_id);
                            }
                        }
                        $inserted_pas_dok_det_id = str_replace('"', '', $inserted_pas_dok_det_id);    

                        $data_dok_history_det = array(
                            'pasien_dok_history_id' => $inserted_dok_history_id,
                            'pasien_id'             => $pasien_id,
                            'dokumen_id'            => $penj_dok_det['dokumen_id'],
                            'judul'                 => $penj_dok_det['judul'],
                            'tipe'                  => $penj_dok_det['tipe'],
                            'is_active'             => 1
                        );

                        $path_model = 'master/pasien_dok_history_detail_m';
                        $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,base_url(),$path_model);
                        $inserted_dok_history_det_id = $pasien_dok_history_det_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,$cabang->url,$path_model,$inserted_dok_history_det_id);
                            }
                        }
                        $inserted_dok_history_det_id = str_replace('"', '', $inserted_dok_history_det_id);   

                        $penjamin_pasien_dokumen_tipe = $array_input['penjamin_dokumen_detail_tipe_'.$penj_dok_det['id']];
                        if(isset($penjamin_pasien_dokumen_tipe))
                        {
                            $index = 1;
                            foreach ($penjamin_pasien_dokumen_tipe as $penj_pasien_dokumen_tipe) 
                            {
                                if($penj_dok_det['tipe'] != 9 && $penj_dok_det['tipe'] != 7)
                                {
                                    $data_detail_tipe = array(
                                        'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                        'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                     => $penj_pasien_dokumen_tipe['text'],
                                        'value'                    => $penj_pasien_dokumen_tipe['value']
                                    );

                                    $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                    $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                        }
                                    }
                                    $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                    
                                    

                                    $data_dok_history_det_tipe = array(
                                        'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                        'pasien_id'                    => $pasien_id,
                                        'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                         => $penj_pasien_dokumen_tipe['text'],
                                        'value'                        => $penj_pasien_dokumen_tipe['value']
                                    );

                                    $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                    $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                    $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                        }
                                    }
                                    $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                }
                                if($penj_dok_det['tipe'] == 7)
                                {
                                    $value = $penj_pasien_dokumen_tipe['value'];

                                    foreach ($value as $val) 
                                    {  
                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                            'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                          => $penj_pasien_dokumen_tipe['text'],
                                            'value'                         => $val
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);
                                        
                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $pasien_id,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $val
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                }
                                if($penj_dok_det['tipe'] == 9)
                                {
                                    if($penj_pasien_dokumen_tipe['value'] != '')
                                    {
                                        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.'assets/mb/var/temp/'.$penj_pasien_dokumen_tipe['value']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.'assets/mb/var/temp/'.$penj_pasien_dokumen_tipe['value'])) 
                                        {
                                            $data_path = array(
                                                'pasien_id'      => $pasien_id,
                                                'no_pasien'      => $data_pasien->no_member,
                                                'dokumen_id'     => $penj_dok['dokumen_id'],
                                                'index'          => $index,
                                                'nama_dokumen'   => str_replace(' ', '_', $penj_dok['nama']),
                                                'path_dokumen'   => './assets/mb/pages/master/pasien/images/'.$data_pasien->no_member.'/dokumen',
                                                'tipe_dokumen'   => $penj_dok['tipe_dokumen'],
                                                'path_temporary' => './assets/mb/var/temp',
                                                'temp_filename'  => $penj_pasien_dokumen_tipe['value'],
                                                'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                                            );

                                            $data_api = serialize($data_path);

                                            $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->is_active == 1)
                                                {
                                                    if($cabang->url != NULL && $cabang->url != '')
                                                    {
                                                       $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);                    
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $file_detail_tipe = $penj_pasien_dokumen_tipe['value'];
                                        }
                                        
                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                            'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                     => $penj_pasien_dokumen_tipe['text'],
                                            'value'                    => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                        
                                        

                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $pasien_id,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                    elseif($penj_pasien_dokumen_tipe['value'] == '')
                                    {
                                        $file_detail_tipe = 'doc_global/document.png';

                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                            'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                     => $penj_pasien_dokumen_tipe['text'],
                                            'value'                    => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                        
                                        

                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $pasien_id,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                }
                            $index++;
                            }                    
                        }
                    }                    
                }
            }
        }
        if($array_input['command'] == "edit") 
        {
            foreach ($penjamin_dokumen as $penj_dok) 
            {
                $data_penj_dok = array(
                    'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                    'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                    'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                    'is_active'         => 1
                );

                $path_model = 'master/pasien_dokumen_m';
                $pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model, $array_input['pasien_dokumen_id']);
                $inserted_pasien_dokumen_id = $pasien_dokumen_id;
                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model,$array_input['pasien_dokumen_id']);
                    }
                }

                $inserted_pasien_dokumen_id = str_replace('"', '', $inserted_pasien_dokumen_id);

                $response->success = true;

                $data_dok_history = array(
                    'pasien_id'          => $pasien_id,
                    'dokumen_id'         => $penj_dok['dokumen_id'],
                    'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                    'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                    'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                    'is_active'          => 1
                );

                $path_model = 'master/pasien_dok_history_m';
                $pasien_dok_history_id = insert_data_api($data_dok_history,base_url(),$path_model);
                $inserted_dok_history_id = $pasien_dok_history_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $pasien_dok_history_id = insert_data_api($data_dok_history,$cabang->url,$path_model,$inserted_dok_history_id);
                    }
                }
                $inserted_dok_history_id = str_replace('"', '', $inserted_dok_history_id);

                $penjamin_dokumen_detail = $array_input['penjamin_dokumen_detail_'.$penj_dok['dokumen_id']];
                if(isset($penjamin_dokumen_detail))
                {
                    foreach ($penjamin_dokumen_detail as $penj_dok_det) 
                    {   

                        $data_dok_history_det = array(
                            'pasien_dok_history_id' => $inserted_dok_history_id,
                            'pasien_id'             => $pasien_id,
                            'dokumen_id'            => $penj_dok_det['dokumen_id'],
                            'judul'                 => $penj_dok_det['judul'],
                            'tipe'                  => $penj_dok_det['tipe'],
                            'is_active'             => 1
                        );

                        $path_model = 'master/pasien_dok_history_detail_m';
                        $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,base_url(),$path_model);
                        $inserted_dok_history_det_id = $pasien_dok_history_det_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,$cabang->url,$path_model,$inserted_dok_history_det_id);
                            }
                        }
                        $inserted_dok_history_det_id = str_replace('"', '', $inserted_dok_history_det_id);   

                        $penjamin_pasien_dokumen_tipe = $array_input['penjamin_dokumen_detail_tipe_'.$penj_dok_det['id']];
                        if(isset($penjamin_pasien_dokumen_tipe))
                        {
                            $index = 1;
                            foreach ($penjamin_pasien_dokumen_tipe as $penj_pasien_dokumen_tipe) 
                            {
                                if($penj_dok_det['tipe'] != 9 && $penj_dok_det['tipe'] != 7)
                                {
                                    $data_detail_tipe = array(
                                        'value'                    => $penj_pasien_dokumen_tipe['value']
                                    );

                                    $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$penj_pasien_dokumen_tipe['pas_dok_det_id']);
                                    $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$penj_pasien_dokumen_tipe['pas_dok_det_id']);
                                        }
                                    }
                                    $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                    
                                    

                                    $data_dok_history_det_tipe = array(
                                        'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                        'pasien_id'                    => $pasien_id,
                                        'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                         => $penj_pasien_dokumen_tipe['text'],
                                        'value'                        => $penj_pasien_dokumen_tipe['value']
                                    );

                                    $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                    $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                    $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                        }
                                    }
                                    $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                }
                                if($penj_dok_det['tipe'] == 7)
                                {
                                    $value = $penj_pasien_dokumen_tipe['value'];

                                    foreach ($value as $val) 
                                    {  
                                        $data_detail_tipe = array(
                                            'value'                         => $val
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$penj_pasien_dokumen_tipe['pas_dok_det_id']);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$penj_pasien_dokumen_tipe['pas_dok_det_id']);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);
                                        
                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $pasien_id,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $val
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                }
                                if($penj_dok_det['tipe'] == 9)
                                {
                                    if($penj_pasien_dokumen_tipe['value'] != '')
                                    {
                                        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.'assets/mb/var/temp/'.$penj_pasien_dokumen_tipe['value']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.'assets/mb/var/temp/'.$penj_pasien_dokumen_tipe['value'])) 
                                        {
                                            $data_path = array(
                                                'pasien_id'      => $pasien_id,
                                                'no_pasien'      => $data_pasien->no_member,
                                                'dokumen_id'     => $penj_dok['dokumen_id'],
                                                'index'          => $index,
                                                'nama_dokumen'   => str_replace(' ', '_', $penj_dok['nama']),
                                                'path_dokumen'   => './assets/mb/pages/master/pasien/images/'.$data_pasien->no_member.'/dokumen',
                                                'tipe_dokumen'   => $penj_dok['tipe_dokumen'],
                                                'path_temporary' => './assets/mb/var/temp',
                                                'temp_filename'  => $penj_pasien_dokumen_tipe['value'],
                                                'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                                            );

                                            $data_api = serialize($data_path);

                                            $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->is_active == 1)
                                                {
                                                    if($cabang->url != NULL && $cabang->url != '')
                                                    {
                                                       $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);                    
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $file_detail_tipe = $penj_pasien_dokumen_tipe['value'];
                                        }
                                        
                                        $data_detail_tipe = array(
                                            'value'                    => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$penj_pasien_dokumen_tipe['pas_dok_det_id']);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$penj_pasien_dokumen_tipe['pas_dok_det_id']);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                        
                                        

                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $pasien_id,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                    elseif($penj_pasien_dokumen_tipe['value'] == '')
                                    {
                                        $file_detail_tipe = 'doc_global/document.png';

                                        $data_detail_tipe = array(
                                            'value'                    => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$penj_pasien_dokumen_tipe['pas_dok_det_id']);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$penj_pasien_dokumen_tipe['pas_dok_det_id']);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                        
                                        

                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $pasien_id,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                }
                            $index++;
                            }                    
                        }
                    }                    
                }
            }
        }
        die(json_encode($response));
    }

    public function listing_obat($id=null)
    {        

       
        $result = $this->obat_m->get_datatable($id);

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
          
            $action='<a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="view"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
          
       
            $output['data'][] = array(
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-center">'.$row['nama'].'</div>',
               '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center">'.$action.'</div>',
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_obat_transfusi($id=null)
    {        

       
        $result = $this->obat_m->get_datatable($id);

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
          
                $action='<a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="view"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
          

           

           
       
            $output['data'][] = array(
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-center">'.$row['nama'].'</div>',
               '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center">'.$action.'</div>',
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_racikan()
    {        

       
        $result = $this->resep_racikan_obat_m->get_datatable();

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
          
            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="view2" data-item="'.htmlentities(json_encode($row)).'"  class="btn btn-primary select2"><i class="fa fa-check"></i></a>';
        
       
            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['nama'].'</div>',
               '<div class="text-center"><textarea class="form-control" rows="3">'.$row['keterangan'].'</textarea></div>',
                '<div class="text-center">'.$action.'</div>',
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_klaim($id,$trx_id)
    {        
        $result = $this->klaim_m->get_datatable($id);
        $pendaftaran = $this->pendaftaran_tindakan_m->get($trx_id);

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
            $penggunaan='';
            $penggunaan2='';
            $nokartu='';
            $aktif='';
            $pilih='';
            $check = '';

            if($pendaftaran->penjamin_id == $row['id'])
            {
                $check = 'checked="checked"';                
            }



            if($row['id']!=1)
            {
                // if($row['id'] == 2 || $row['id'] == 3 || $row['id'] == 5 || $row['id'] == 6 || $row['id'] == 7 || $row['id'] == 8 || $row['id'] == 9)
                if(in_array($row['id'], config_item('penjamin_id')))
                {
                    if($row['id_kelompok'] != 0)
                    {
                        $url         = $row['url_kelompok'];
                        $nokartu     = $row['no_kartu'];
                        $hiddenval   ='<input type="text" id="pilihklaim2_'.$i.'" name="pilihklaim2['.$i.'][claimed2]" value="'.$row['id'].'">';

                        $cek_bpjs  = check_bpjs($url.$nokartu);

                        if($cek_bpjs != false && $cek_bpjs != 'not_responding')
                        {
                            $aktif       ='<span class="label label-success">Aktif</span>';

                            if(($row['tggl']!=null) && ($row['tipe']!=null))
                            {
                                $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                                $penggunaan2 = 'Tidak Tersedia';
                                $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled>';
                            }
                            else
                            {                            
                            
                                $penggunaan  = '<span class="label label-success">Tersedia</span>';
                                $penggunaan2 = 'Tersedia';
                                $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" '.$check.'>';                            
                            }
                        }
                        elseif($cek_bpjs == false && $cek_bpjs != 'not_responding')
                        {
                            $aktif       ='<span class="label label-danger">Tidak Aktif</span>';
                            $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled class="code">';
                            $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                            $penggunaan2 = 'Tidak Tersedia';
                        }
                        elseif($cek_bpjs == 'not_responding')
                        {
                            $aktif       ='<span class="label label-warning">Not Responding</span>';
                            if(($row['tggl']!=null) && ($row['tipe']!=null))
                            {
                                $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                                $penggunaan2 = 'Tidak Tersedia';
                                $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled>';
                            }
                            else
                            {                          
                                $penggunaan  = '<span class="label label-success">Tersedia</span>';
                                $penggunaan2 = 'Tersedia';
                                $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" '.$check.'>';                            
                            }
                        }

                    }
                    else
                    {
                        $url         = $row['url'];
                        $nokartu     = $row['no_kartu'];
                        $aktif       ='<span class="label label-danger">Tidak Aktif</span>';
                        $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" class="code">';
                        $hiddenval   ='<input type="text" id="pilihklaim2_'.$i.'" name="pilihklaim2['.$i.'][claimed2]" value="'.$row['id'].'">';
                        $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                        $penggunaan2 = 'Tidak Tersedia';

                        $cek_bpjs  = check_bpjs($url.$nokartu);

                        if($cek_bpjs != false && $cek_bpjs != 'not_responding')
                        {
                            $aktif       ='<span class="label label-success">Aktif</span>';

                            if(($row['tggl']!=null) && ($row['tipe']!=null))
                            {
                                $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                                $penggunaan2 = 'Tidak Tersedia';
                                $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled>';
                            }
                            else
                            {                            
                            
                                $penggunaan  = '<span class="label label-success">Tersedia</span>';
                                $penggunaan2 = 'Tersedia';
                                $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" '.$check.'>';                            
                            }
                        }
                        elseif($cek_bpjs == false && $cek_bpjs != 'not_responding')
                        {
                            $aktif       ='<span class="label label-danger">Tidak Aktif</span>';
                            $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled class="code">';
                            $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                            $penggunaan2 = 'Tidak Tersedia';
                        }
                        elseif($cek_bpjs == 'not_responding')
                        {
                            $aktif       ='<span class="label label-warning">Not Responding</span>';
                            if(($row['tggl']!=null) && ($row['tipe']!=null))
                            {
                                $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                                $penggunaan2 = 'Tidak Tersedia';
                                $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled>';
                            }
                            else
                            {                          
                                $penggunaan  = '<span class="label label-success">Tersedia</span>';
                                $penggunaan2 = 'Tersedia';
                                $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" '.$check.'>';                            
                            }
                        }
                    }
                }
                else
                {                              
                     $nokartu     = $row['no_kartu'];
                     $aktif       ='<span class="label label-success">Aktif</span>';
                     $penggunaan  ='<span class="label label-success">Tersedia</span>';
                     $penggunaan2 ='Tersedia';
                     $pilih       ='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" class="code" '.$check.'>';
                     $hiddenval   ='<input type="text" id="pilihklaim2_'.$i.'" name="pilihklaim2['.$i.'][claimed2]" value="'.$row['id'].'">';
                }
            }
            else
            {
                  $penggunaan  ='-';
                  $penggunaan2 ='-';
                  $nokartu     ='-';
                  $aktif       ='-';
                  $hiddenval   ='<input type="text" id="pilihklaim2_'.$i.'" name="pilihklaim2['.$i.'][claimed2]" value="'.$row['id'].'">';
                  $pilih       ='<input type="radio" id="pilihklaim2_'.$i.'" name="pilihklaim[]" value="'.$row['id'].'" '.$check.'>';
            }

            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">'.$nokartu.'</div>',
                '<div class="text-center">'.$aktif.'</div>',
                '<div class="text-center">'.$penggunaan.'</div>',
                '<div class="text-center">'.$pilih.'</div>',
                 
            );
            
        }

        echo json_encode($output);
    }

    public function listing_klaim2($id,$transid)
    {        

        $result = $this->klaim_m->get_datatable($id);

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
            $penggunaan='';
            $nokartu='';
            $aktif='';
            $pilih='';
            $klaim2='';
            $check='';

           $klaim_id=$this->pasien_klaim_m->get_by(array('transaksi_id'=>$transid));
           // $klaim_id=object_to_array($klaim_id);
           foreach ($klaim_id as $row2) {
                  $klaim2=$row2->penjamin_id;
           };
         
           // $pilih='<input type="radio" id="pilihklaim3'.$i.'" name="pilihklaim3[]"  value="'.$row['id'].'" class="kod">';
            $pilih='<div name="place[]"><input type="radio" id="pilihklaim3'.$i.'" name="pilihklaim3[]"  value="'.$row['id'].'" class="kod"  /></div>
                 ';
             if($row['id']!=1)
             {
                if(($row['tggl']!=null) && ($row['tipe']!=null))
                {
                   
                    $penggunaan='<span class="label label-danger">Tidak Tersedia</span>';
                    $nokartu=$row['no_kartu'];
                    $aktif='<span class="label label-success">Aktif</span>';
                    
                  //  $pilih='<input type="radio"  id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'">';
                   
                   
                }else{
                    
                    $nokartu=$row['no_kartu'];
                    $aktif='<span class="label label-success">Aktif</span>';
                    $penggunaan='<span class="label label-danger">Tersedia</span>';
                   
                   // $pilih='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'">';
                    
                }
            }else{
                 $penggunaan='-';
                 $nokartu='-';
                 $aktif='-';
                
               //  $pilih='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]"  value="'.$row['id'].'">';
                    
            }

            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['nama'].'<input type="hidden" class="kod2" value="'.$klaim2.'"></div>',
                '<div class="text-center">'.$nokartu.'</div>',
                '<div class="text-center">'.$aktif.'</div>',
                '<div class="text-center">'.$penggunaan.'</div>',
                '<div class="text-center">'.$pilih.'</div>',
                $row['id']
                 
            );
            
        }

        echo json_encode($output);
    }

     public function listing_rujukan($id)
    {        

       
        $result = $this->rujukan_m->get_datatable($id);

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
                 
                '<div class="text-center">'.$row['asal'].'<input type="hidden" id="poli_id" name="poliid[]" value="'.$row['id'].'"></div>',
               '<div class="text-center">'.$row['tujuan'].'</div>',
               '<div class="text-center">'.date('d F Y',strtotime($row['tggldirujuk'])).'</div>',
               '<div class="text-center">'.date('d F Y',strtotime($row['tgglrujukan'])).'</div>',
                
                 
            );
            $i++;
        }

        echo json_encode($output);
    }
     public function listing_paket_popover()
    {        

       
        $result = $this->paket_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));

        $i=0;
        foreach($records->result_array() as $row)
        {
           $tipe='';
            $action = '';

            if($row['tipe']==1)
            {
                $tipe='Obat';
            }else{
                $tipe='Alat Kesehatan';
            }
          
                $action='<a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="viewpaket3[]" data-item="'.htmlentities(json_encode($row)).'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
       
            $output['data'][] = array(
                
                '<div class="text-center">'.$tipe.'</div>',
               '<div class="text-center">'.$row['nama'].'</div>',
               '<div class="text-center">'.$this->formatrupiah($row['harga']).'</div>',
               '<div class="text-center">'.$row['keterangan'].'</div>',
               '<div class="text-center">'.$action.'</div>',
           
                
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_paket_popover2()
    {        

       
        $result = $this->paket_m->get_datatable();

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
           $tipe='';
            $action = '';

            if($row['tipe']==1)
            {
                $tipe='Obat';
            }else{
                $tipe='Alat Kesehatan';
            }
          
                $action='<a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="viewpaket55[]" data-item="'.htmlentities(json_encode($row)).'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
       
            $output['data'][] = array(
                
                '<div class="text-center">'.$tipe.'</div>',
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">'.$this->formatrupiah($row['harga']).'</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center">'.$action.'</div>',
           
                
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_sejarah_transaksi($pasien_id)
    {        
        $this->load->model('global/rm_transaksi_pasien_m');
        $params = $this->rm_transaksi_pasien_m->get_params();
        $post = $this->input->post();
        $param = array_merge($params,$post);

        $cabang = $this->cabang_m->get(1);

        $result = json_decode(get_datatable_api($param,$pasien_id,$cabang->url));

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = object_to_array($result->records);
        
        $i=1;
        foreach($records as $row)
        {
           
            $action='<a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="viewsejarah[]" href="#page" data-toggle="tab" data-id="'.$row['transaksi_id'].'" data-tipe="'.$row['tipe'].'" data-cabang_id="'.$row['cabang_id'].'" data-index="'.$i.'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';
       
            $output['data'][] = array(                 
                 '<div class="text-center">'.date('d F Y',strtotime($row['tanggal'])).'</div>',
                 '<div class="text-center">'.$row['nama_cabang'].'</div>',
                 '<div class="text-center">'.$row['nama_poliklinik'].'</div>',
                 '<div class="text-center">'.$row['no_transaksi'].'</div>',
                 '<div class="text-center">'.$row['nama_dokter'].'</div>',
                 '<div class="text-center">'.$row['keterangan'].'</div>',
                 '<div class="text-center inline-button-table">'.$action.'</div>'        
            );
            $i++;
        }

        echo json_encode($output);
    }

      public function listing_sejarah_item($id)
    {        

       
        $result = $this->sejarah_item_m->get_datatable($id);

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
           
            $action='<a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="viewitem[]"  href="#page" data-toggle="tab" data-id="'.$row['id'].'" data-tggl="'.$row['tanggal'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
       
            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['kode'].'</div>',
               '<div class="text-center">'.$row['nama'].'</div>',
               '<div class="text-center">'.$row['jumlah'].' '.$row['satuan'].'</div>',
               '<div class="text-center">'.$row['jumlah'].'</div>',
               '<div class="text-center">'.date('d F Y',strtotime($row['tanggal_resep'])).'</div>',
               '<div class="text-center">'.date('d F Y',strtotime($row['tanggal_habis'])).'</div>',
                '<div class="text-center">'.$action.'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_observasi($id=null)
    {        
       
        $result = $this->observasi_m->get_datatable($id);

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

    public function listing_chapter()
    {        
        $this->load->model('master/icd/icd_chapter_m');
        $result = $this->icd_chapter_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        foreach($records->result_array() as $row)
        {
           $action='';
       
            $output['data'][] = array(

                '<div class="text-center">'.$row['first_code'].'-'.$row['last_code'].'</div>',
                '<div class="text-left"><a class="chapter_name" data-code="'.$row['code'].'">'.$row['name'].'</a></div>'            
            );
        }

        echo json_encode($output);
    }
    public function listing_block($chapter_code)
    {        
        $this->load->model('master/icd/icd_block_m');
        $result = $this->icd_block_m->get_datatable($chapter_code);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        foreach($records->result_array() as $row)
        {
           $action='';
       
            $output['data'][] = array(

                '<div class="text-center">'.$row['first_code'].'-'.$row['last_code'].'</div>',
                '<div class="text-left"><a class="block_name" data-first_code="'.$row['first_code'].'" data-last_code="'.$row['last_code'].'" >'.$row['name'].'</a></div>'            
            );
        }

        echo json_encode($output);
    }

    public function listing_icd_code($first_code=null,$last_code=null)
    {        
        $this->load->model('master/icd/icd_code_m');
        $result = $this->icd_code_m->get_datatable($first_code,$last_code);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        foreach($records->result_array() as $row)
        {
           $action='';
       
            $output['data'][] = array(

                '<div class="text-center">'.$row['code_ast'].'</div>',
                '<div class="text-left"><a class="icd_name" data-code="'.$row['code_ast'].'" data-name="'.$row['name'].'">'.$row['name'].'</a></div>'            
            );
        }

        echo json_encode($output);
    }

    public function listing_observasi2($id=null)
    {        

       
        $result = $this->observasi_m->get_datatable($id);

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
           
             $action='<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/transaksi_dokter/edit_observasi_dialisis/'.$row['id'].'"  name="viewobservasi"   class="btn grey-cascade"><i class="fa fa-edit"></i></a>
             <a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="deleted[]"   class="btn red"><i class="fa fa-times"></i></a>';
       
            $output['data'][] = array(
                 
                '<div class="text-center">'.date("H:i:s", strtotime($row['waktu_pencatatan'])).'</div>',
                '<div class="text-center">'.$row['tekanan_darah_1'].'/'.$row['tekanan_darah_2'].'</div>',
                '<div class="text-center">'.$row['ufg'].'</div>',
                '<div class="text-center">'.$row['ufr'].'</div>',
                '<div class="text-center">'.$row['ufv'].'</div>',
                '<div class="text-center">'.$row['qb'].'</div>',
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>'
                               
                
                 
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

       

        $result = $this->item_digunakan_m->get_datatable($id);

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
           
           $ispaket='';
           if($row['is_paket']==1)
           {
            $ispaket=$row['namapaket'];
           }else{
            $ispaket='-';
           }

            $output['data'][] = array(
                 
                '<div class="text-center">'.date('d M Y',strtotime($row['tanggal_resep'])).'</div>',
                 '<div class="text-center">'.$row['namaitem'].'</div>',
                '<div class="text-center">'.$ispaket.'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
               
                '<div class="text-center">'.$row['nurse'].'</div>',
                '<div class="text-center">'.$row['nurse'].'</div>'
                               
                
                 
            );
            $i++;
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

    public function listing_paket_tagihan2($id=null)
    {        

       

        $result = $this->tagihan_paket_m->get_datatable2($id);

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
             <a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="viewtagihanpaket5[]" data-id="'.$row['paket_id'].'" data-transaksiid="'.$id.'" data-paketname="'.$row['nama'].'" data-tindakanhdpaket="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-search"></i></a>
             <a title="'.translate('Ubah', $this->session->userdata('language')).'"  name="viewtagihanpaket6[]" data-id="'.$row['paket_id'].'" data-transaksiid="'.$id.'" data-paketname="'.$row['nama'].'" data-tindakanhdpaket="'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-edit"></i></a>';
       
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

     public function listing_view_tagihan_paket2($transaksiid=null,$paketid=null,$transaksihdpaketid=null)
    {        

       
        $result = $this->view_tagihan_paket_m->get_datatable2($transaksiid,$paketid,$transaksihdpaketid);

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
                 
                '<div class="text-center">'.$row['kode'].'</div>',
               '<div class="text-center">'.$row['nama'].'</div>',
               '<div class="text-center">'.$row['jumlah'].'</div>',
               '<div class="text-center">'.$row['digunakan'].'</div>',
               
               '<div class="text-center">'.($row['jumlah']-$row['digunakan']).'</div>',
               
                 
            );
            
        }

        echo json_encode($output);
    }
      public function getsatuanobat()
    {
        
        
        $id=$this->input->post('id');
        
        // save data
        $result=$this->resep_racikan_obat_m->getsatuanobat($id)->result_array();
       
        echo json_encode($result);
    }

    public function getsejarah()
    {
        $tindakan_id=$this->input->post('tindakan_id');
        $pasien_id=$this->input->post('pasien_id');
        $cabang_id=$this->input->post('cabang_id');
        $tipe=$this->input->post('tipe');
        $flag=$this->input->post('flag');

        $cabang = $this->cabang_m->get($cabang_id);

        if($tipe==1)
        {
            $rows_assesment = get_data_sejarah_assesment($tindakan_id, $pasien_id, $cabang->url);
            $data_tindakan = $this->tindakan_hd_m->get($tindakan_id);
        }


        $response = new stdClass;
        $response->nama_cabang = $cabang->nama;
        $response->assesment = $this->hemodialisis($rows_assesment[0]);
        $response->problem = $rows_assesment[1];
        $response->komplikasi = $rows_assesment[2];
        $response->tindakan = $data_tindakan;
         
        // $rows_assesment=object_to_array($rows_assesment);
        die(json_encode($response));
    }

    public function getsejarah2()
    {
        
        
        $tindakan_id=$this->input->post('id');
        
       
        $rows_assesment = $this->tindakan_hd_m->get_by_transaction_id2($tindakan_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);

        
      
       
        echo json_encode($this->hemodialisis($rows_assesment));
    }

       public function getpage($flag)
    {
        
        
        $pasien_id=$this->input->post('pasien_id');
        $tanggal=date('Y-m-d',strtotime($this->input->post('tanggal')));
        
        // save data
        $result='';
        if($flag=='prev')
        {
             $rows_assesment=$this->tindakan_hd_m->getpageprev($pasien_id,$tanggal)->result_array();

        }else if($flag=='next')
        {
             $rows_assesment=$this->tindakan_hd_m->getpagenext($pasien_id,$tanggal)->result_array();
        }else if($flag=='first')
        {
             $rows_assesment=$this->tindakan_hd_m->getpagefirst($pasien_id,$tanggal)->result_array();
        }else{
            $rows_assesment=$this->tindakan_hd_m->getpagelast($pasien_id,$tanggal)->result_array();
        }
       
         
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

     public function getproblem()
    {
        
        
        $tindakan_id=$this->input->post('tindakan_id');
        $pasien_id=$this->input->post('pasien_id');
       
         
        $rows_assesment = $this->tindakan_hd_m->getproblem($tindakan_id,$pasien_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);

        
      
       
        echo json_encode($rows_assesment);
    }

     public function getkomplikasi()
    {
        
        
        $tindakan_id=$this->input->post('tindakan_id');
        $pasien_id=$this->input->post('pasien_id');
       
         
        $rows_assesment = $this->tindakan_hd_m->getkomplikasi($tindakan_id,$pasien_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);

        
      
       
        echo json_encode($rows_assesment);
    }

    public function editdataobservasi($id)
    {
        
        // $id=$this->input->post('id');

         
        $rows = $this->observasi_m->get_data_by_id($id)->result_array();
        $user = $this->user_m->get($rows[0]['user_id']);
        $body = array(

            "observasi_id_value"     => $rows[0]['id'],
            "transaksi_id_value"     => $rows[0]['transaksi_hd_id'], 
            "user_name"              => $user->nama,
            "waktu_pencatatan_value" => date('H:i',strtotime($rows[0]['waktu_pencatatan'])),
            "tda_value"              => $rows[0]['tekanan_darah_1'],
            "tdb_value"              => $rows[0]['tekanan_darah_2'],
            "ufg_value"              => $rows[0]['ufg'],
            "ufr_value"              => $rows[0]['ufr'],
            "ufv_value"              => $rows[0]['ufv'],
            "qb_value"               => $rows[0]['qb'],
            "keterangan_value"       => $rows[0]['keterangan'],

        );
        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/edit_observasi_dialisis', $body);
        
        // $rows_assesment=object_to_array($rows_assesment);

        // echo json_encode($body);
    }

    public function updateobservasi()
    {
        $data['user_id'] = $this->input->post('userid');
        $data['waktu_pencatatan'] =date("Y-m-d H:i:s", strtotime($this->input->post('jam')));
        $data['tekanan_darah_1'] = $this->input->post('tda');
        $data['tekanan_darah_2'] = $this->input->post('tdb');
        $data['ufg'] = $this->input->post('ufg');
        $data['ufr'] = $this->input->post('ufr');
        $data['ufv'] = $this->input->post('ufv');
        $data['qb'] = $this->input->post('qb');
        $data['keterangan'] = $this->input->post('keterangan');
        
        $observasi_id = $this->observasi_m->save($data, $this->input->post('id_observasi'));

        $flashdata = array(
            "success",
            translate("Observasi sudah diupdate", $this->session->userdata("language")),
            translate("Sukses", $this->session->userdata("language"))
        );

        echo json_encode($flashdata);
             
    }

    public function getnomortransaksi()
    {
            $transaksiid = $this->input->post('transaksiid');
           
            $id = $this->tindakan_hd_m->get($transaksiid);

            $body=array('id' => $id->no_transaksi);
         
            echo json_encode($body);
             
    }

     public function updatestatus($flag)
    {
            $id = $this->input->post('id');

            if($flag==1){
                $data['status']=2;
            }else{
                $data['status']=1;
            }
            
            $id = $this->item_tersimpan_m->save($data,$id);

          
         
            echo json_encode('sukses');
             
    }
 
    public function listing_transaksi_diproses()
    {        
        
        $id=array('1','2','4');
        
        $result = $this->tindakan_hd_m->get_datatable($id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $status='';
            $shift='';
            if($row['status']=='1')
            {
                $status='<span class="label label-warning">Menunggu Ditindak</span>';
                 $action = '
                       <a title="'.translate('Print', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klinik_hd/transaksi_dokter/print_persetujuan/'.$row['id'].'/'.$row['pasienid'].'" class="btn grey"><i class="fa fa-print"></i></a>
                        <a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="del[]" href="'.base_url().'klinik_hd/transaksi_dokter/detail_history/'.$row['id'].'/'.$row['pasienid'].'" data-action="view" data-id="'.$row['id'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>
                        <a title="'.translate('Edit', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/transaksi_dokter/edit_tindakan/'.$row['id'].'" class="btn blue-chambray search-item hidden"><i class="fa fa-edit"></i></a>
                        <a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="del[]"  href="'.base_url().'klinik_hd/transaksi_dokter/keterangan_batal/'.$row['id'].'" data-toggle="modal" data-target="#modal_ketarangan_hapus" data-id="'.$row['id'].'" class="btn red search-item"><i class="fa fa-times"></i></a>';
                         
            }else if($row['status']=='2'){
                $status='<span class="label label-primary">Sedang Ditindak</span>';
                 $action = '<a title="'.translate('Print', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klinik_hd/transaksi_dokter/print_persetujuan/'.$row['id'].'/'.$row['pasienid'].'" class="btn grey"><i class="fa fa-print"></i></a>
                       <a title="'.translate('Lihat', $this->session->userdata('language')).'"  href="'.base_url().'klinik_hd/transaksi_dokter/detail_history/'.$row['id'].'/'.$row['pasienid'].'" name="del[]" data-action="view" data-id="'.$row['id'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
                         
            }else if($row['status']=='3'){
                      $status='<span class="label label-info">Selesai</span>';
                    $action = '<a title="'.translate('Print', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/transaksi_dokter/edit_tindakan/'.$row['id'].'" class="btn grey search-item"><i class="fa fa-print"></i></a>
                       <a title="'.translate('Lihat', $this->session->userdata('language')).'"  href="'.base_url().'klinik_hd/transaksi_dokter/detail_history/'.$row['id'].'/'.$row['pasienid'].'" name="del[]" data-action="view" data-id="'.$row['id'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
                         
            }else{
                   $status='<span class="label label-danger">Ditolak Perawat</span>';
                 $action = '
                       <a title="'.translate('Edit', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/transaksi_dokter/edit_tindakan/'.$row['id'].'" class="btn blue-chambray search-item hidden"><i class="fa fa-check"></i></a>
                       <a title="'.translate('View', $this->session->userdata('language')).'"  href="'.base_url().'klinik_hd/transaksi_dokter/detail_history/'.$row['id'].'/'.$row['pasienid'].'" name="del[]" data-action="view" data-id="'.$row['id'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>
                       <a title="'.translate('Kembalikan', $this->session->userdata('language')).'" name="return[]" data-confirm="'.translate('Kembalikan tindakan ini ke perawat?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn btn-primary search-item"><i class="fa fa-undo"></i></a>
                        <a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="del[]"  href="'.base_url().'klinik_hd/transaksi_dokter/keterangan_batal/'.$row['id'].'" data-toggle="modal" data-target="#modal_ketarangan_hapus" data-id="'.$row['id'].'" class="btn red search-item"><i class="fa fa-times"></i></a>';
                     
            }

            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
            }

            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }

           
       
            $output['data'][] = array(
              '<div class="text-left inline-button-table">'.$img_url.$row['nama'].'</div>',
              '<div class="text-left inline-button-table">'.$shift.' '.$row['no_transaksi'].'</div>',
              '<div class="text-left">'.$row['tempat_lahir'].', '.date('d M Y',strtotime($row['tanggal_lahir'])).'</div>',
             '<div class="text-left">'.ucwords(strtolower($row['alamat'])).', '.ucwords(strtolower($row['kelurahan'])).', '.ucwords(strtolower($row['kecamatan'])).', '.ucwords(strtolower($row['kota'])).'</div>',
              '<div class="text-left">'.$row['nama_dokter'].'</div>',
              '<div class="text-left">'.$status.'</div>',
              '<div class="text-left inline-button-table">'.$action.'</div>' ,
               
                
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_visit()
    {        
                
        $result = $this->tindakan_hd_visit_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="del[]" href="'.base_url().'klinik_hd/transaksi_dokter/detail_history/'.$row['tindakan_hd_id'].'/'.$row['pasienid'].'" data-action="view" data-id="'.$row['tindakan_hd_id'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
            
            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
            }
           
       
            $output['data'][] = array(
              '<div class="text-left">'.$row['no_transaksi'].'</div>',
              '<div class="text-left">'.$img_url.$row['nama'].'</div>',
              '<div class="text-left">'.$row['nama_dokter'].'</div>',
              '<div class="text-center">'.date('d-M-Y H:i:s', strtotime($row['start_visit'])).'</div>',
              '<div class="text-center">'.date('d-M-Y H:i:s', strtotime($row['end_visit'])).'</div>',
              '<div class="text-left">'.$row['keterangan'].'</div>',
              '<div class="text-center inline-button-table">'.$action.'</div>' ,
               
                
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_histori($id=null)
    {        
        $user_level_id = $this->session->userdata('level_id');
        // if($id==5)
        // {
        //     $id=array('1','2','3','4');
        // }else{
        //     $id=$id;
        // }
        $result = $this->transaksi_diproses_m->get_datatable2(array('3'));
        // die_dump($this->db->last_query());
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
            
            $url = array();//URL PHOTO PASIEN
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
            }

            $url1 = array();//URL PHOTO DOKTER
            if ($row['url_photo1'] != '') 
            {
                $url1 = explode('/', $row['url_photo1']);
                // die(dump($row['url_photo']));
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1]) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1])) 
                {
                    $img_url1 = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1].'">';
                }
                else
                {
                    $img_url1 = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_user_img_dir').'global/small/global.png">';
                }
            } else {

                $img_url1 = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_user_img_dir').'global/small/global.png">';
            }

            $data_asses = '<a title="'.translate('Print Assesment', $this->session->userdata('language')).'" target="_blank" name="print" href="'.base_url().'klinik_hd/transaksi_dokter/print_assesment/'.$row['id'].'/'.$row['pasienid'].'" class="btn default search-item"><i class="fa fa-print"></i></a>';
            $data_doc = '<a title="'.translate('Print Dokumen Pasien', $this->session->userdata('language')).'" target="_blank" name="print_dokumen" href="'.base_url().'klinik_hd/transaksi_dokter/print_dokumen/'.$row['id'].'/'.$row['pasienid'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
            $data_detail = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/transaksi_dokter/detail_history/'.$row['id'].'/'.$row['pasienid'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';

            //tambahkan data ke tabel fitur_tombol. Field page="klinik_hd_transaksi_dokter", button="print_asses"
            //tambahkan data ke tabel fitur_tombol. Field page="klinik_hd_transaksi_dokter", button="print_doc"
            //tambahkan data ke tabel fitur_tombol. Field page="klinik_hd_transaksi_dokter", button="detail"
            $action = restriction_button($data_asses,$user_level_id,'klinik_hd_transaksi_dokter','print_asses'). restriction_button($data_doc,$user_level_id,'klinik_hd_transaksi_dokter','print_doc').restriction_button($data_detail,$user_level_id,'klinik_hd_transaksi_dokter','detail');
            // $action='<a title="'.translate('Print Assesment', $this->session->userdata('language')).'" target="_blank" name="print" href="'.base_url().'klinik_hd/transaksi_dokter/print_assesment/'.$row['id'].'/'.$row['pasienid'].'" class="btn default search-item"><i class="fa fa-print"></i></a><a title="'.translate('Print Dokumen Pasien', $this->session->userdata('language')).'" target="_blank" name="print_dokumen" href="'.base_url().'klinik_hd/transaksi_dokter/print_dokumen/'.$row['id'].'/'.$row['pasienid'].'" class="btn btn-primary"><i class="fa fa-print"></i></a><a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/transaksi_dokter/detail_history/'.$row['id'].'/'.$row['pasienid'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
                         
       
            $output['data'][] = array(
                $row['id'].'/'.$row['pasienid'],
                 '<div class="text-center">'.$row['no_transaksi'].'</div>',
                 '<div class="text-center">'.date('d M Y',strtotime($row['tanggal'])).'</div>',
                 '<div class="text-center">'.$row['no_member'].'</div>',
                 '<div class="text-left">'.$img_url.$row['nama'].'</div>',
                 '<div class="text-left">'.$img_url1.$row['nama1'].'</div>',
                 '<div class="text-center">'.$row['berat_awal'].' Kg </div>',
                 '<div class="text-center">'.$row['berat_akhir'].' Kg </div>',
                 '<div class="text-center inline-button-table">'.$action.'</div>' ,
               
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_histori_umum()
    {
        $user_level_id = $this->session->userdata('level_id');
        
        $status = array('3');
        $result = $this->tindakan_umum_m->get_datatable($status);

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
            
            $url = array();//URL PHOTO PASIEN
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
            }

            $url1 = array();//URL PHOTO DOKTER
            if ($row['url_photo1'] != '') 
            {
                $url1 = explode('/', $row['url_photo1']);
                // die(dump($row['url_photo']));
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1]) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1])) 
                {
                    $img_url1 = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1].'">';
                }
                else
                {
                    $img_url1 = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_user_img_dir').'global/small/global.png">';
                }
            } else {

                $img_url1 = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.config_item('base_dir').config_item('site_user_img_dir').'global/small/global.png">';
            }

            $data_asses = '<a title="'.translate('Print Rekmed', $this->session->userdata('language')).'" target="_blank" name="print" href="'.base_url().'klinik_hd/transaksi_dokter/print_rekmed/'.$row['id'].'/'.$row['pasienid'].'" class="btn btn-primary search-item"><i class="fa fa-print"></i></a>';

            $action = restriction_button($data_asses,$user_level_id,'klinik_hd_transaksi_dokter','print_rekmed');
                         
       
            $output['data'][] = array(
                $row['id'],
                 '<div class="text-center">'.$row['nomor_tindakan'].'</div>',
                 '<div class="text-center">'.date('d M Y',strtotime($row['tanggal'])).'</div>',
                 '<div class="text-center">'.$row['no_member'].'</div>',
                 '<div class="text-left">'.$img_url.$row['nama'].'</div>',
                 '<div class="text-left">'.$img_url1.$row['nama1'].'</div>',
                 '<div class="text-center inline-button-table">'.$action.'</div>' ,
               
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_histori_detail_resep($tindakan_hd_id,$pasien_id)
    {        
         
        $result = $this->tindakan_resep_obat_detail_m->get_datatable_history($tindakan_hd_id,$pasien_id);

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
            $aturan = '';
            if($row['aturan'] == 1){
                $aturan = 'PC';
            }if($row['aturan'] == 2){
                $aturan = 'AC';
            }
            $output['data'][] = array(
                '<div class="text-left">'.$row['nama_item'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['nama_satuan'].'</div>',
                '<div class="text-left">'.$row['dosis'].' '.$aturan.'</div>',
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

      public function loadpaket()
    {
        
        
        $id=$this->input->post('id');
        
        // save data
        $result=$this->transaksi_dokter_m->loadpaket($id)->result_array();
       
        echo json_encode($result);
    }

      public function loadresep()
    {
        
        
        $id=$this->input->post('id');
        $pasienid=$this->input->post('pasien_id');
        
        // save data
        $result=$this->transaksi_dokter_m->loadresep($id,$pasienid)->result_array();
       
        echo json_encode($result);
    }

      public function loadresepmanual()
    {
        
        
        $id=$this->input->post('id');
        $pasienid=$this->input->post('pasien_id');
        
        // save data
        $result=$this->transaksi_dokter_m->loadresepmanual($id,$pasienid)->result_array();
       
        echo json_encode($result);
    }

      public function loadklaim()
    {
        
        
        $id=$this->input->post('id');
        $pasienid=$this->input->post('pasien_id');
        
        // save data
        $result=$this->transaksi_dokter_m->loadklaim($id,$pasienid)->result_array();
       
        echo json_encode($result);
    }

      public function loadresepobat()
    {
        
        
        $item_id=$this->input->post('item_id');
        $satuan_id=$this->input->post('satuan_id');
        
        // save data
        $result=$this->transaksi_dokter_m->loadresepobat($item_id,$satuan_id)->result_array();
       
        echo json_encode($result);
    }

      public function loadresepracikan()
    {
        
        
        $item_id=$this->input->post('item_id');
       
        // save data
        $result=$this->transaksi_dokter_m->loadresepracikan($item_id)->result_array();
       
        echo json_encode($result);
    }

    public function modalracikan()
    {
      
        // $assets = array(); 
        // $config = 'assets/klinik_hd/transaksi_dokter/create_tindakan';
        // $this->config->load($config, true);

        // $assets = $this->config->item('assets', $config);
        
        // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        // $form_data = $this->cabang_m->get($id);
        // $form_data=$this->poliklinik_m->get($id);
        // $data = array(
        //     'title'          => config_item('site_name'). translate("Lihat Poliklinik", $this->session->userdata("language")), 
        //     'header'         => translate("Lihat Poliklinik", $this->session->userdata("language")), 
        //     'header_info'    => config_item('site_name'), 
        //     'breadcrumb'     => TRUE,
        //     'menus'          => $this->menus,
        //     'menu_tree'      => $this->menu_tree,
        //     'css_files'      => $assets['css'],
        //     'js_files'       => $assets['js'],
        //     'flag'          =>2,
        //     'pk'             => $id,
        //     'content_view'   => 'klinik_hd/transaksi_dokter/modalracikan',
                    
        // );

        // // Load the view
        //   $this->load->view('klinik_hd/transaksi_dokter/modalracikan', $data);
          $this->load->view('klinik_hd/transaksi_dokter/tab_transaksi_dokter/modalracikan');
    }

        public function getreseptemp()
    {
        
        
        $id=$this->input->post('id');
        
        // save data
        $result=$this->transaksi_dokter_m->getreseptemp($id)->result_array();
       
        echo json_encode($result);
    }

    public function getresepmanualtemp()
    {
        
        
        $id=$this->input->post('id');
        
        // save data
        $result=$this->transaksi_dokter_m->getresepmanualtemp($id)->result_array();
       
        echo json_encode($result);
    }

    public function update_waktu_proses()
    {
        
       $id=$this->input->post('id');
        
       $get_id=$this->pendaftaran_tindakan_m->get($id);
       $cabang = $this->cabang_m->get(1);

       if($get_id->waktu_proses==null || $get_id->waktu_proses=='')
       {
            $data['waktu_proses']=date('Y-m-d h:i:s');
            update_pendaftaran($data,base_url(),$id);
            update_pendaftaran($data,$cabang->url,$id);
            // $this->pendaftaran_tindakan_m->save($data,$id);
       }
       
       
        echo json_encode('sukses');
    }


    public function modal_pindah($bed_id)
    {
        $data = array(
            'bed_id'    => $bed_id,
        );
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/modal_pindah', $data);
    }

    public function modal_tolak($bed_id)
    {
        $data = array(
            'bed_id'    => $bed_id,
        );
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/modal_tolak', $data);
    }

     public function listing_monitoring($id_tindakan_hd = null)
    {        

        $result = $this->observasi_m->get_datatable($id_tindakan_hd);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        foreach($records->result_array() as $row)
        {
            $action = '';
            if($row['is_active']==1)
            {
                $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'"   data-id="'.$row['id'].'" name="editobservasi[]" data-toggle="modal" data-target="#modal_dialisis" href="'.base_url().'klinik_hd/transaksi_dokter/editdataobservasi/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                            <a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="deleted[]"  data-msg="'.translate('Anda yakin akan menghapus monitoring dialisis ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i></a>';
            }else
            {
                $action = '<a title="'.translate('Restore', $this->session->userdata('language')).'"  name="restore1[]"  data-msg="'.translate('Anda yakin akan menyimpan monitoring dialisis ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn yellow"><i class="fa fa-check"></i></a>';
            }
            
            $output['data'][] = array(
                 
                '<div class="text-center">'.date("H:i:s", strtotime($row['waktu_pencatatan'])).'</div>',
                '<div class="text-center">'.$row['tekanan_darah_1'].'/'.$row['tekanan_darah_2'].'</div>',
                '<div class="text-center">'.$row['ufg'].'</div>',
                '<div class="text-center">'.$row['ufr'].'</div>',
                '<div class="text-center">'.$row['ufv'].'</div>',
                '<div class="text-center">'.$row['qb'].'</div>',
                '<div class="text-center">'.$row['tmp'].'</div>',
                '<div class="text-center">'.$row['vp'].'</div>',
                '<div class="text-center">'.$row['ap'].'</div>',
                '<div class="text-center">'.$row['cond'].'</div>',
                '<div class="text-center">'.$row['temperature'].'</div>',
                '<div class="text-center">'.$row['nama'].' [ '.$row['user_level'].' ]</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                '<div class="text-center">'.$row['is_active'].'</div>'
                 
            );
        }

        echo json_encode($output);
    }

    public function simpan_assesment()
    {
        $id_tindakan_penaksiran = $this->input->post('id_tindakan_penaksiran');
        
        $tanggal                = $this->input->post('tanggal');
        $waktu                  = $this->input->post('waktu');
        
        $alergic_medicine       = $this->input->post('alergic_medicine');
        $alergic_food           = $this->input->post('alergic_food');
        $assessment_cgs         = $this->input->post('assessment_cgs');
        $medical_diagnose       = $this->input->post('medical_diagnose');
        
        $time_of_dialysis       = $this->input->post('time_of_dialysis');
        $quick_of_blood         = $this->input->post('quick_of_blood');
        $quick_of_dialysate     = $this->input->post('quick_of_dialysate');
        $uf_goal                = $this->input->post('uf_goal');
        $regular                = $this->input->post('regular');
        $minimal                = $this->input->post('minimal');
        $free                   = $this->input->post('free');
        $dose                   = $this->input->post('dose');
        $first                  = $this->input->post('first');
        $maintenance            = $this->input->post('maintenance');
        $hour                   = $this->input->post('hour');
        
        $machine_no             = $this->input->post('machine_no');
        $new                    = $this->input->post('new_');
        $reuse                  = $this->input->post('reuse');
        $dialyzer               = $this->input->post('dialyzer');
        $av_shunt               = $this->input->post('av_shunt');
        $femoral                = $this->input->post('femoral');
        $double_lument          = $this->input->post('double_lument');
        $bicarbonate            = $this->input->post('bicarbonate');


        $data_assesment = array(
            'waktu'             => $waktu,
            'alergic_medicine'  => $alergic_medicine,
            'alergic_food'      => $alergic_food,
            'assessment_cgs'    => $assessment_cgs,
            'medical_diagnose'  => $medical_diagnose,      
            'time_of_dialysis'  => $time_of_dialysis,
            'quick_of_blood'    => $quick_of_blood,
            'quick_of_dialysis' => $quick_of_dialysate,
            'uf_goal'           => $uf_goal,
            'heparin_reguler'   => $regular,
            'heparin_minimal'   => $minimal,
            'heparin_free'      => $free,
            'dose'              => $dose,
            'first'             => $first,
            'maintenance'       => $maintenance,
            'hours'             => $hour,
            'machine_no'        => $machine_no,
            'ba_avshunt'        => $av_shunt,
            'ba_femoral'        => $femoral,
            'ba_catheter'       => $double_lument,
            'dialyzer_type'     => $bicarbonate,

        );

        $assesment = $this->tindakan_hd_penaksiran_m->save($data_assesment, $id_tindakan_penaksiran);

        if ($assesment) 
        {
            $flashdata = array(
                "success",
                translate("Assesment telah disimpan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );
        }
        else
        {
            $flashdata = array(
                "error"
            );
        }
        echo json_encode($flashdata);   

    }

    public function simpan_supervising(){

        $id_tindakan_penaksiran = $this->input->post('id_tindakan_penaksiran');

        $remaining_of_priming   = $this->input->post('remaining_of_priming');
        $wash_out               = $this->input->post('wash_out');
        $drip_of_fluid          = $this->input->post('drip_of_fluid');
        $blood                  = $this->input->post('blood');
        $drink                  = $this->input->post('drink');
        $vomiting               = $this->input->post('vomiting');
        $urinate                = $this->input->post('urinate');
        $transfusion_type       = $this->input->post('transfusion_type');
        $transfusion_qty        = $this->input->post('transfusion_qty');
        $transfusion_blood_type = $this->input->post('transfusion_blood_type');
        $serial_number          = $this->input->post('serial_number');
        
        $data_assesment = array(
            
            'remaining_of_priming'   => $remaining_of_priming,
            'wash_out'               => $wash_out,
            'drip_of_fluid'          => $drip_of_fluid,
            'blood'                  => $blood,
            'drink'                  => $drink,
            'vomiting'               => $vomiting,
            'urinate'                => $urinate,
            'transfusion_type'       => $transfusion_type,
            'transfusion_qty'        => $transfusion_qty,
            'transfusion_blood_type' => $transfusion_blood_type,
            'serial_number'          => $serial_number,

        );

        $assesment = $this->tindakan_hd_penaksiran_m->save($data_assesment, $id_tindakan_penaksiran);

        if ($assesment) 
        {
            $flashdata = array(
                "success",
                translate("Supervising telah disimpan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );
        }
        else
        {
            $flashdata = array(
                "error"
            );
        }
        echo json_encode($flashdata);   
    }


    public function simpan_examination(){

        $id_tindakan_penaksiran = $this->input->post('id_tindakan_penaksiran');

        $laboratory  = $this->input->post('laboratory');
        $ecg         = $this->input->post('ecg');
        $priming     = $this->input->post('priming');
        $initiation  = $this->input->post('initiation');
        $termination = $this->input->post('termination');
        
        $data_assesment = array(
            
            'laboratory'  => $laboratory,
            'ecg'         => $ecg,
            'priming'     => $priming,
            'initiation'  => $initiation,
            'termination' => $termination,

        );

        $assesment = $this->tindakan_hd_penaksiran_m->save($data_assesment, $id_tindakan_penaksiran);

        if ($assesment) 
        {
            $flashdata = array(
                "success",
                translate("Examination support telah disimpan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );
        }
        else
        {
            $flashdata = array(
                "error"
            );
        }
        echo json_encode($flashdata);   
    }

    // utk mengeset checkbox kemudian di beri nilai 1
    public function simpan_pasien_problem(){
        
        $tindakan_hd_id = $this->input->post('tindakan_hd_id');
        $problem_id     = $this->input->post('problem_id');

        $pasien_problem_id = $this->pasien_problem_m->get_by(array('problem_id' => $problem_id, 'tindakan_hd_id' => $tindakan_hd_id));
        
        // $update_nol = $this->pasien_problem_m->set_nilai_nol($tindakan_hd_id);

        $data_pasien_problem['nilai'] = 1;
        $pasien_problem = $this->pasien_problem_m->save($data_pasien_problem, $pasien_problem_id[0]->id);

    }

    // utk mngupdate checkbox yg di unchek kemudian di beri nilai 0
    public function update_pasien_problem(){
        
        $tindakan_hd_id = $this->input->post('tindakan_hd_id');
        $problem_id     = $this->input->post('problem_id');

        $pasien_problem_id = $this->pasien_problem_m->get_by(array('problem_id' => $problem_id, 'tindakan_hd_id' => $tindakan_hd_id));
        
        $data_pasien_problem['nilai'] = 0;
        $pasien_problem = $this->pasien_problem_m->save($data_pasien_problem, $pasien_problem_id[0]->id);

    }

    // utk mengeset checkbox kemudian di beri nilai 1
    public function simpan_pasien_komplikasi(){
        
        $tindakan_hd_id = $this->input->post('tindakan_hd_id');
        $komplikasi_id  = $this->input->post('komplikasi_id');

        $pasien_komplikasi_id = $this->pasien_komplikasi_m->get_by(array('komplikasi_id' => $komplikasi_id, 'tindakan_hd_id' => $tindakan_hd_id));
        $data_pasien_komplikasi['nilai'] = 1;
        $pasien_komplikasi = $this->pasien_komplikasi_m->save($data_pasien_komplikasi, $pasien_komplikasi_id[0]->id);

    }

    // utk mngupdate checkbox yg di unchek kemudian di beri nilai 0
    public function update_pasien_komplikasi(){
        
        $tindakan_hd_id = $this->input->post('tindakan_hd_id');
        $komplikasi_id  = $this->input->post('komplikasi_id');

        $pasien_komplikasi_id = $this->pasien_komplikasi_m->get_by(array('komplikasi_id' => $komplikasi_id, 'tindakan_hd_id' => $tindakan_hd_id));
        
        $data_pasien_komplikasi['nilai'] = 0;
        $pasien_komplikasi = $this->pasien_komplikasi_m->save($data_pasien_komplikasi, $pasien_komplikasi_id[0]->id);

    }

    public function add_monitoring($trans_id)
    {
        $rows = $this->observasi_m->get_data_last($trans_id)->result_array();
        if(count($rows)>0){
              $body = array(

            "observasi_id_value"     => $rows[0]['id'],
            "transaksi_id_value"     => $rows[0]['transaksi_hd_id'], 
            "user_id_value"          => $rows[0]['user_id'],
            "waktu_pencatatan_value" => date('H:i',strtotime($rows[0]['waktu_pencatatan'])),
            "tda_value"              => $rows[0]['tekanan_darah_1'],
            "tdb_value"              => $rows[0]['tekanan_darah_2'],
            "ufg_value"              => $rows[0]['ufg'],
            "ufr_value"              => $rows[0]['ufr'],
            "ufv_value"              => $rows[0]['ufv'],
            "qb_value"               => $rows[0]['qb'],
            "keterangan_value"       => $rows[0]['keterangan'],
        );
        }else{
            $assesment = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id'=> $trans_id),true);
            $td_awal = '';
            $td_akhir = '';
            if($assesment)
            {
                $td = explode('_', $assesment->blood_preasure);
                $td_awal = $td[0];
                $td_akhir = $td[1];
            }
            $body = array(

            "observasi_id_value"     => '',
            "transaksi_id_value"     => '', 
            "user_id_value"          => '',
            "waktu_pencatatan_value" => '',
            "tda_value"              => $td_awal,
            "tdb_value"              => $td_akhir,
            "ufg_value"              => '',
            "ufr_value"              => '',
            "ufv_value"              => '',
            "qb_value"               => '',
            "keterangan_value"       => '',
        );
          }
      
        
        $this->load->view('klinik_hd/transaksi_dokter/monitoring_dialisis', $body);
    }

    public function simpanobservasi()
    {   

        $data['transaksi_hd_id']  = $this->input->post('transaksiid');
        $data['user_id']          = $this->input->post('userid');
        $data['waktu_pencatatan'] = date("Y-m-d H:i:s", strtotime($this->input->post('jam')));
        $data['tekanan_darah_1']  = $this->input->post('tda');
        $data['tekanan_darah_2']  = $this->input->post('tdb');
        $data['ufg']              = $this->input->post('ufg');
        $data['ufr']              = $this->input->post('ufr');
        $data['ufv']              = $this->input->post('ufv');
        $data['qb']               = $this->input->post('qb');
        $data['keterangan']       = $this->input->post('keterangan');
        $data['is_active']        = 1;
        
        $observasi_id = $this->observasi_m->save($data);

        // UNTUK EXAMINATION SUPPORT PRIMING, INITIATION, TERMINATION
        // $tindakan_penaksiran_id = $this->input->post('tindakan_penaksiran_id');

        $data_taksir = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $this->input->post('transaksiid')), true);

        $priming = '';
        if ($data_taksir->priming == NULL && $data_taksir->initiation == NULL || $data_taksir->priming == '' && $data_taksir->initiation == '') 
        {   
            $data_save['priming']       = $this->session->userdata('nama_lengkap');
            $data_save['initiation']    = $this->session->userdata('nama_lengkap');

            $save_taksir = $this->tindakan_hd_penaksiran_m->save($data_save, $data_taksir->id);

            $priming = $this->session->userdata('nama_lengkap');
        }
        
        $data_save['termination'] = $this->session->userdata('nama_lengkap');
        $save_taksir = $this->tindakan_hd_penaksiran_m->save($data_save, $data_taksir->id);
        $termination = $this->session->userdata('nama_lengkap');

        if ($observasi_id) 
        {
            $flashdata = array(
                "success",
                translate("Observasi sudah disimpan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language")),
                $priming,
                $termination
            );
            
            echo json_encode($flashdata);
        }
        
    }

    public function listing_dokumen_pasien($id_pasien=null, $jenis, $flag)
    {        

        $where='';
        if($flag==1)
        {
            $where='tanggal_kadaluarsa > now()';
        }else
        {
            $where='tanggal_kadaluarsa < now()';
        }

        $result = $this->transaksi_dokter4_m->get_datatable2($id_pasien, $where, $jenis);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $tipe   = '';    
            $jenis  = '';
            $status = '';
            $tipe1  = '';
          
            if($row['tipe']==1)
            {
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="view" href="'.base_url().$row['url_file'].'" target="_blank" class="btn blue-chambray search-item"><i class="fa fa-file-pdf-o"></i></a>';
            }
            else
            {
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="viewpic[]" data-id="'.$row['url_file'].'" data-target="#ajax_notes2" data-toggle="modal"  class="btn btn-primary search-item"><i class="fa fa-picture-o"></i></a>';
            }

            $output['data'][] = array(
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-center">'.$row['no_dokumen'].'</div>',
                '<div class="text-center">'.date('d M Y',strtotime($row['tanggal_kadaluarsa'])).'</div>',
                '<div class="text-center">'.$row['no_dokumen'].'</div>',
                '<div class="text-center">'.$tipe1.'</div>',
            );
        }

        echo json_encode($output);
    }

     public function modal_paket($id=null,$paket_id=null,$nama_paket=null,$flag=null,$tindakanhdpaket=null)
    {

        $assets = array();
        $config = 'assets/klinik_hd/transaksi_dokter/flagjs';
        $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            $body=array(
                    'id'              =>$id,
                    'paket_id'        =>$paket_id,
                    'nama_paket'      =>str_replace('%20',' ', $nama_paket),
                    'js_files'        => $assets['js'],
                    'flag'            => $flag,
                    'tindakanhdpaket' => $tindakanhdpaket
                );
         
          $this->load->view('klinik_hd/transaksi_dokter/tab_transaksi_dokter/modal_paket',$body);

    }

    public function modal_view_paket($id,$paket_id,$nama_paket)
    {

       $assets = array();
        $config = 'assets/klinik_hd/transaksi_dokter/flagjs';
        $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
         $body=array(
                'id'=>$id,
                'paket_id'=>$paket_id,
                'nama_paket'=>$nama_paket,
                'js_files'     => $assets['js'],
                'flag'     => 2
            );
          $this->load->view('klinik_hd/transaksi_dokter/tab_transaksi_dokter/modal_view_paket',$body);
    }

    public function modal_view_item($tindakan_hd_item_id)
    {
        $data = array(
            'tindakan_hd_item_id' => $tindakan_hd_item_id
        );

        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/modal_view_item', $data);
    }

     public function inserttindakanhdpaket()
    {
        
        
        $paket_id=$this->input->post('paket_id');
        $tindakan_id=$this->input->post('tindakan_id');

        foreach($paket_id as $row){
            if($row!=null){
                $get_count=$this->tindakan_hd_paket_m->get_by(array('tindakan_hd_id' =>  $tindakan_id,'paket_id'=>$row));
                $paket = $this->paket_m->get($row);

                $data['tindakan_hd_id']=$tindakan_id;
                $data['paket_id']=$row;
                $data['is_use']=1;
                $data['rupiah']=$paket->harga_total;
                $tindakan_hd_paket_id=$this->tindakan_hd_paket_m->save($data);


                $get_id_paket_item=$this->paket_item_m->get_by(array('paket_id'=>$row,'is_active'=>1));

                foreach ($get_id_paket_item as $row1) 
                {
                    $data_item['waktu']=date('Y-m-d H:i:s');
                    $data_item['user_id']=$this->session->userdata("user_id");
                    $data_item['tindakan_hd_id']=$tindakan_id;
                    $data_item['item_id']=$row1->item_id;
                    $data_item['jumlah']=0;
                    $data_item['item_satuan_id']=$row1->item_satuan_id;
                    $data_item['transaksi_pemberian_id']=$tindakan_hd_paket_id;
                    $data_item['tipe_pemberian']=1;
                    $data_item['is_active']=1;
                    //$get_id_tindakan_hd_item=$this->tindakan_hd_item_m->save($data_item);
                }

                $get_id_paket_tindakan=$this->paket_tindakan_m->get_by(array('paket_id'=>$row,'is_active'=>1));

                foreach ($get_id_paket_tindakan as $row2) 
                {
                    $data_tindakan['waktu']=date('Y-m-d H:i:s');
                    $data_tindakan['user_id']=$this->session->userdata("user_id");
                    $data_tindakan['tindakan_hd_id']=$tindakan_id;
                    $data_tindakan['tindakan_id']=$row2->tindakan_id;
                    $data_tindakan['jumlah']=0;
                    $data_tindakan['tindakan_hd_paket_id']=$tindakan_hd_paket_id;
                    $data_tindakan['is_paket']=1;
                    $data_tindakan['is_active']=1;
                    //$get_id_tindakan_hd_tindakan=$this->tindakan_hd_tindakan_m->save($data_tindakan);
                }
                

                
               
            }
          
        }

        
        echo json_encode('sukses');
    }

    public function get_paket_item()
    {
        $this->load->model('master/item/item_m');   
        $x=0;
        $paket_id=$this->input->post('paket_id');
        $tindakan_id=$this->input->post('transaksiid');
        $tindakanhdpaketid=$this->input->post('tindakanhdpaketid');

        $result=$this->view_tagihan_paket_m->get_paket_item($tindakan_id,$paket_id,$tindakanhdpaketid)->result_array();
        if(count($result)>0)
        {      
            $table='';
            foreach($result as $row)
            {
                $x++;
                $table.='<tr id="item_row_'.$x.'"><td align="center" width="100px"><div id="inventory_identitas_detail" class="hidden"> </div><input type="hidden" name="item['.$x.'][itemid]" value="'.$row['id'].'"><input type="hidden" name="item['.$x.'][identitas]" value="'.$row['is_identitas'].'"><input type="hidden" name="item['.$x.'][satuanid]" value="'.$row['item_satuan_id'].'">'.$row['kode'].'</td><td>'.$row['nama'].'</td><td align="center" width="100px">'.$row['jumlah'].' '.$row['nama_satuan'].'<input type="hidden" id="item['.$x.'][jatah]" name="item['.$x.'][jatah]" value="'.$row['jumlah'].'"></td><td align="center" width="100px">'.$row['digunakan'].' '.$row['nama_satuan'].'</td><td align="center" width="100px"><div name="item['.$x.'][sisa]">'.($row['jumlah']-$row['digunakan']).'</div><input type="hidden" name="item['.$x.'][sisa2]" value="'.($row['jumlah']-$row['digunakan']).'"></td><td align="center" width="100px">';
                $item = $this->item_m->get($row['id']);
                if($item->is_identitas == 1)
                {
                    $table .= '<div class="input-group">
                        <input type="number" class="form-control" readonly="" id="use_" name="item['.$x.'][user]" value="0" max="'.($row['jumlah']-$row['digunakan']).'" min="0" size="5" required="required">
                        <span class="input-group-btn">
                            <a class="btn btn-primary btn-modal-identitas" data-sisa="'.($row['jumlah']-$row['digunakan']).'" data-row="item_row_'.$x.'" data-item_id="'.$row['id'].'" data-satuan="'.$row['item_satuan_id'].'" href="'.base_url().'klinik_hd/transaksi_dokter/modal_inventori_identitas/&gudang_id&/'.$row['id'].'/'.$row['item_satuan_id'].'" data-toggle="modal" data-target="#modal_identitas_paket" disabled="disabled"><i class="fa fa-info"></i></a>
                        </span>
                    </div>';                
                }
                else
                {
                    $table .= '<div class="col-md-12"><input type="number" class="form-control" id="use_" name="item['.$x.'][user]" value="0" max="'.($row['jumlah']-$row['digunakan']).'" min="0" size="5" required="required"></div>';  
                }

                $table .= '</td></tr>';
            }
            $table .= '<tr class="hidden"><td align="center" colspan="6"><input type="hidden" id="jml_item" name="jml_item" value="'.$x.'"></td></tr>';
        }
        else
        {
             $table ='<tr><td align="center" colspan="6">No data available in table</td></tr>';
                 // $table .= '<input type="hidden" id="jml_item" name="jml_item" value="'.$x.'">';
        }

        echo json_encode($table);
    }

    public function get_paket_item2()
    {
        $this->load->model('master/item/item_m');   
        $x=0;
        $id=$this->input->post('paket_id');
        $tindakan_id=$this->input->post('transaksiid');
        $tindakanhdpaketid=$this->input->post('tindakanhdpaketid');

        $result=$this->view_tagihan_paket_m->get_paket_item2($tindakan_id,$id,$tindakanhdpaketid)->result_array();
        
        if(count($result)>0)
        {
            $table='';
            foreach($result as $row)
            {
                $x++;
                $table.='<tr id="item_row_'.$x.'"><td align="center" width="100px"><div id="inventory_identitas_detail" class="hidden"> </div><input type="hidden" name="item['.$x.'][itemid]" value="'.$row['id'].'"><input type="hidden" name="item['.$x.'][identitas]" value="'.$row['is_identitas'].'"><input type="hidden" name="item['.$x.'][satuanid]" value="'.$row['item_satuan_id'].'">'.$row['kode'].'</td><td>'.$row['nama'].'</td><td align="center" width="100px">'.$row['jumlah'].' '.$row['nama_satuan'].'<input type="hidden" id="item['.$x.'][jatah2]" name="item['.$x.'][jatah2]" value="'.$row['jumlah'].'"></td><td align="center" width="100px">'.$row['digunakan'].' '.$row['nama_satuan'].'</td><td align="center" width="100px"><div name="item['.$x.'][sisa2]">'.($row['jumlah']-$row['digunakan']).'</div><input type="hidden" name="item['.$x.'][sisa3]" value="'.($row['jumlah']-$row['digunakan']).'"></td><td align="center" width="100px">';
                $item = $this->item_m->get($row['id']);
                if($item->is_identitas == 1)
                {
                    $table .= '<div class="input-group">
                        <input type="number" class="form-control" readonly="" id="use_" name="item['.$x.'][user]" value="0" max="'.($row['jumlah']-$row['digunakan']).'" min="0" size="5" required="required">
                        <span class="input-group-btn">
                            <a class="btn btn-primary btn-modal-identitas" data-sisa="'.($row['jumlah']-$row['digunakan']).'" data-row="item_row_'.$x.'" data-item_id="'.$row['id'].'" data-satuan="'.$row['item_satuan_id'].'" href="'.base_url().'klinik_hd/transaksi_dokter/modal_inventori_identitas/&gudang_id&/'.$row['id'].'/'.$row['item_satuan_id'].'" data-toggle="modal" data-target="#modal_identitas_paket" disabled="disabled"><i class="fa fa-info"></i></a>
                        </span>
                    </div>';                
                }
                else
                {
                    $table .= '<div class="col-md-12"><input type="number" class="form-control" id="use_" name="item['.$x.'][user]" value="0" max="'.($row['jumlah']-$row['digunakan']).'" min="0" size="5" required="required"></div>';  
                }
                $table .= '</td></tr>';
            }
            $table .= '<tr class="hidden"><td align="center" colspan="6"><input type="hidden" id="jml_item" name="jml_item" value="'.$x.'"></td></tr>';
        }
        else
        {
             $table ='<tr><td align="center" colspan="6">No data available in table</td></tr>';
                 // $table .= '<input type="hidden" id="jml_item" name="jml_item" value="'.$x.'">';
        }
       
        
        echo json_encode($table);
    }

     public function get_paket_tindakan()
    {
        
        $table='';
        $x=0;
        $paket_id=$this->input->post('paket_id');
        $tindakan_id=$this->input->post('transaksiid');
        $tindakanhdpaketid=$this->input->post('tindakanhdpaketid');

        $result=$this->view_tagihan_paket_m->get_paket_tindakan($tindakan_id,$paket_id,$tindakanhdpaketid)->result_array();
        if(count($result)>0)
        { 
        foreach($result as $row)
        {
            $x++;
            $table.='<tr><td align="center" width="100px"><input type="hidden" name="item_tindakan['.$x.'][tindakan_id]" value="'.$row['id'].'">'.$row['kode'].'</td><td>'.$row['nama'].'</td><td align="center" width="100px">'.$row['jumlah'].'<input type="hidden" id="item['.$x.'][jatah4]" name="item_tindakan['.$x.'][jatah4]" value="'.$row['jumlah'].'"></td><td align="center" width="100px">'.$row['digunakan'].'</td><td align="center" width="100px"><div name="item_tindakan['.$x.'][sisa4]">'.($row['jumlah']-$row['digunakan']).'</div><input type="hidden" name="item_tindakan['.$x.'][sisa5]" value="'.($row['jumlah']-$row['digunakan']).'"></td><td align="center" width="100px"><input type="number" id="use_" name="item_tindakan['.$x.'][user]" value="0" max="'.($row['jumlah']-$row['digunakan']).'" min="0" size="5" required="required"></td></tr>';
        }
         }else{
             $table.='<tr><td align="center" colspan="6">No data available in table</td></tr>';
        }
        echo json_encode($table);
    }

     public function get_paket_tindakan2()
    {
        
        $table='';
        $x=0;
        $id=$this->input->post('paket_id');
        $tindakan_id=$this->input->post('transaksiid');
        $tindakanhdpaketid=$this->input->post('tindakanhdpaketid');

        $result=$this->view_tagihan_paket_m->get_paket_tindakan2($tindakan_id,$id,$tindakanhdpaketid)->result_array();
       if(count($result)>0)
        {   
        foreach($result as $row)
        {
            $x++;
            $table.='<tr><td align="center" width="100px"><input type="hidden" name="item_tindakan['.$x.'][tindakan_id]" value="'.$row['id'].'">'.$row['kode'].'</td><td>'.$row['nama'].'</td><td align="center" width="100px">'.$row['jumlah'].'<input type="hidden" id="item['.$x.'][jatah5]" name="item_tindakan['.$x.'][jatah5]" value="'.$row['jumlah'].'"></td><td align="center" width="100px">'.$row['digunakan'].'</td><td align="center" width="100px"><div name="item_tindakan['.$x.'][sisa5]">'.($row['jumlah']-$row['digunakan']).'</div><input type="hidden" name="item_tindakan['.$x.'][sisa6]" value="'.($row['jumlah']-$row['digunakan']).'"></td><td align="center" width="100px"><input type="number" id="use_" name="item_tindakan['.$x.'][user]" value="0" max="'.($row['jumlah']-$row['digunakan']).'" min="0" size="5" required="required"></td></tr>';
        }
         }else{
             $table.='<tr><td align="center" colspan="6">No data available in table</td></tr>';
        }
        echo json_encode($table);
    }

    public function simpatindakanhditem()
    {
      
        $id=$this->input->post('idtindakanpaketid');
        $tindakan_id=$this->input->post('idtindakanpaket');
        $tindakanhdpaketid=$this->input->post('tindakanhdpaketid');
        $data=$this->input->post('item');
        $data2=$this->input->post('item_tindakan');
        $jml_item = $this->input->post('jml_item');

        $tggl_tindakan=$this->input->post('tggl_tindakan');
        $keterangan_tindakan=$this->input->post('keterangan_tindakan');


        //UPDATE TINDAKAN HD PAKET is_use = 1
        $paket['is_use'] = 1;
        $tindakan_hd_paket = $this->tindakan_hd_paket_m->save($paket, $tindakanhdpaketid);
        
        // SAVE inventory_history
        $data_inventory_history = array(
            'transaksi_id'   => $tindakan_id,
            'transaksi_tipe' => 5
        );
        $inventory_history_id = $this->inventory_history_m->save($data_inventory_history);
       
            if(count($data) > 0)
            {    
                foreach($data as $row)
                {
                    if($row['user'] > 0)
                    {

                        $inventory_identitas = $this->input->post('inventory_identitas_'.$row['itemid']);

                        if($inventory_identitas) 
                        {
                                    
                            $i = 1;
                            foreach ($inventory_identitas as $data_inventory_identitas) 
                            {

                                if ($data_inventory_identitas['jumlah'] != 0) 
                                {

                                    $harga_jual = $this->item_harga_m->get_harga_item_satuan($row['itemid'], $row['satuanid'])->result_array();

                                    // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG
                                    $data_tindakan_hd_item = array(
                                        'waktu'                  => date('Y-m-d H:i:s', strtotime($tggl_tindakan)),
                                        'user_id'                => $this->session->userdata('user_id'),
                                        'tindakan_hd_id'         => $tindakan_id,
                                        'item_id'                => $row['itemid'],
                                        'jumlah'                 => $data_inventory_identitas['jumlah'],
                                        '`index`'                  => 1,
                                        'item_satuan_id'         => $row['satuanid'],
                                        'transaksi_pemberian_id' => $tindakanhdpaketid,
                                        'harga_beli'             => $data_inventory_identitas['harga_beli'],
                                        'harga_jual'             => (count($harga_jual))?$harga_jual[0]['harga']:'-',     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                                        'keterangan'             => $keterangan_tindakan,
                                        'tipe_pemberian'         => 1,
                                        'is_active'              => 1
                                    );
                                    $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);
                                    


                                    // // DELETE BERDASARKAN ITEM_ID, ITEM_SATUAN_ID, HARGA_BELI YANG SAMA
                                    // $get_data_group_delete = $this->tindakan_hd_item_m->get_by(array('id !=' => $get_data_group[0]->id, 'tindakan_hd_id' => $tindakan_hd_id, 'item_id' => $item_id, 'item_satuan_id' => $satuan_id, 'harga_beli' => $data_inventory_identitas['harga_beli']));

                                    // foreach ($get_data_group_delete as $data_deleted) 
                                    // {
                                    //     $this->tindakan_hd_item_m->delete($data_deleted->id);
                                    // }

                                    // SAVE INVENTORY_HISTORY_DETAIL
                                    $data_inventory_history_detail = array
                                    (
                                        'inventory_history_id' => $inventory_history_id,
                                        'gudang_id'            => $data_inventory_identitas['gudang_id'],
                                        'pmb_id'               => $data_inventory_identitas['pmb_id'],
                                        'item_id'              => $row['itemid'],
                                        'item_satuan_id'       => $row['satuanid'],
                                        'initial_stock'        => $data_inventory_identitas['stock'],
                                        'change_stock'         => '-'.$data_inventory_identitas['jumlah'],
                                        'final_stock'          => (intval($data_inventory_identitas['stock']) - intval($data_inventory_identitas['jumlah'])),
                                        'harga_beli'           => $data_inventory_identitas['harga_beli'],
                                        'total_harga'          => (intval($data_inventory_identitas['jumlah'])*intval($data_inventory_identitas['harga_beli'])),
                                    );
                                    $inventory_history_detail_id = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                    // UNTUK MEGUPDATE STOCK INVENTORY_IDENTITAS
                                    $data_inventory_idt = array(
                                        'jumlah'        => (intval($data_inventory_identitas['stock']) - intval($data_inventory_identitas['jumlah'])),
                                        'modified_by'   => $this->session->userdata('user_id'),
                                        'modified_date' => date('Y-m-d H:i:s')
                                    );
                                    $inventory_identitas_id = $this->inventory_identitas_m->save_inventory_identitas($data_inventory_idt, $data_inventory_identitas['inventory_identitas_id']);

                                    // UNTUK MENGUPDATE JUMLAH INVENTORY
                                    $jumlah_inventory = $this->inventory_m->get_by(array('inventory_id' => $data_inventory_identitas['inventory_id']));
                                    $jumlah_inventory = intval($jumlah_inventory[0]->jumlah) - intval($data_inventory_identitas['jumlah']);
                                    $data_inventory = array(
                                        'jumlah'        => $jumlah_inventory,
                                        'modified_by'   => $this->session->userdata('user_id'),
                                        'modified_date' => date('Y-m-d H:i:s')
                                    );
                                    $data_inventory_id = $this->inventory_m->save_inventory($data_inventory, $data_inventory_identitas['inventory_id']);

                                    // SAVE INVENTORY_HISTORY_IDENTITAS
                                    $data_inventory_history_identitas = array
                                    (
                                        'inventory_history_detail_id' => $inventory_history_detail_id,
                                        'jumlah'                      => $data_inventory_identitas['jumlah']
                                    );
                                    $inventory_history_identitas_id = $this->inventory_history_identitas_m->save($data_inventory_history_identitas);

                                    // SAVE TINDAKAN_ITEM_IDENTITAS
                                    $data_tindakan_hd_item_identitas = array
                                    (
                                        'tindakan_hd_item_id' => $tindakan_hd_item_id,
                                        'jumlah'              => $data_inventory_identitas['jumlah']
                                    );
                                    $tindakan_hd_item_identitas_id = $this->tindakan_hd_item_identitas_m->save($data_tindakan_hd_item_identitas);


                                    $inventory_identitas_detail = $this->input->post('inventory_identitas_detail_'.$row['itemid'].'_'.$i);
                                    foreach ($inventory_identitas_detail as $data_inventory_identitas_detail) 
                                    {
                                        // SAVE INVENTORY_HISTORY_IDENTITAS_DETAIL
                                        $data_inventory_history_identitas_detail = array
                                        (
                                            'inventory_history_identitas_id' => $inventory_history_identitas_id,
                                            'identitas_id'                   => $data_inventory_identitas_detail['identitas_id'],
                                            'judul'                          => $data_inventory_identitas_detail['judul'],
                                            'value'                          => $data_inventory_identitas_detail['value']
                                        );
                                        $inventory_history_identitas_detail_id = $this->inventory_history_identitas_detail_m->save($data_inventory_history_identitas_detail);

                                        // SAVE TINDAKAN_HD_ITEM_IDENTITAS_DETAIL
                                        $data_tindakan_hd_item_identitas_detail = array
                                        (
                                            'tindakan_hd_item_identitas_id' => $tindakan_hd_item_identitas_id,
                                            'identitas_id'                  => $data_inventory_identitas_detail['identitas_id'],
                                            'judul'                         => $data_inventory_identitas_detail['judul'],
                                            'value'                         => $data_inventory_identitas_detail['value']
                                        );
                                        $tindakan_hd_item_identitas_detail_id = $this->tindakan_hd_item_identitas_detail_m->save($data_tindakan_hd_item_identitas_detail);
                                    }

                                }  

                                 // DELETE INVENTORY JIKA ADA ITEM YG JUMLAHNYA = 0
                                $get_data_inventory_kosong = $this->inventory_m->get_by(array('jumlah' => 0));
                                foreach ($get_data_inventory_kosong as $data_kosong) 
                                {
                                    $this->inventory_m->delete_inventory_kosong($data_kosong->inventory_id);
                                }

                                // DELETE INVENTORY_IDENTITAS YG JUMLAHNYA = 0
                                $get_data_inventory_identias_kosong = $this->inventory_identitas_m->get_by(array('jumlah' => 0));
                                foreach ($get_data_inventory_identias_kosong as $data_identitas_kosong) 
                                {
                                    $this->inventory_identitas_m->delete_inventory_identitas_kosong($data_identitas_kosong->inventory_identitas_id);
                                    $this->inventory_identitas_detail_m->delete_inventory_identitas_detail_kosong($data_identitas_kosong->inventory_identitas_id);
                                }

                                $i++;  
                            } 
                        }
                        else
                        {
                            $harga_jual = $this->item_harga_m->get_harga_item_satuan($row['itemid'], $row['satuanid'])->result_array();
                            $inventory_item = $this->inventory_m->get_by(array('item_id' => $row['itemid']), true);
                            // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG
                            $data_tindakan_hd_item = array(
                                'waktu'                  => date('Y-m-d H:i:s', strtotime($tggl_tindakan)),
                                'user_id'                => $this->session->userdata('user_id'),
                                'tindakan_hd_id'         => $tindakan_id,
                                'item_id'                => $row['itemid'],
                                'jumlah'                 => $row['user'],
                                '`index`'                  => 1,
                                'item_satuan_id'         => $row['satuanid'],
                                'transaksi_pemberian_id' => $tindakanhdpaketid,
                                'harga_beli'             => $inventory_item->harga_beli,
                                'harga_jual'             => (count($harga_jual))?$harga_jual[0]['harga']:'-',     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                                'keterangan'             => $keterangan_tindakan,
                                'tipe_pemberian'         => 1,
                                'is_active'              => 1
                            );
                            $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);

                            $data_inventory_history_detail = array
                            (
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $inventory_item->gudang_id,
                                'pmb_id'               => $inventory_item->pmb_id,
                                'item_id'              => $row['itemid'],
                                'item_satuan_id'       => $row['satuanid'],
                                'initial_stock'        => $inventory_item->jumlah,
                                'change_stock'         => '-'.$row['user'],
                                'final_stock'          => (intval($inventory_item->jumlah) - intval($row['user'])),
                                'harga_beli'           => $inventory_item->harga_beli,
                                'total_harga'          => (intval($row['user'])*intval($inventory_item->harga_beli)),
                            );
                            $inventory_history_detail_id = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            // UNTUK MENGUPDATE JUMLAH INVENTORY
                            $jumlah_inventory = $this->inventory_m->get_by(array('inventory_id' => $inventory_item->inventory_id));
                            $jumlah_inventory = intval($jumlah_inventory[0]->jumlah) - intval($row['user']);
                            $data_inventory = array(
                                'jumlah'        => $jumlah_inventory,
                                'modified_by'   => $this->session->userdata('user_id'),
                                'modified_date' => date('Y-m-d H:i:s')
                            );
                            $data_inventory_id = $this->inventory_m->save_inventory($data_inventory, $inventory_item->inventory_id);
                        }
                    }
                }
                
            }
        

        if(count($data2) > 0)
        {
            foreach($data2 as $row)
            {
                if($row['user'] > 0)
                {
                    $data_tindakan_hd_tindakan['waktu']=date('Y-m-d H:i',strtotime($tggl_tindakan));
                    $data_tindakan_hd_tindakan['user_id']=$this->session->userdata("user_id");
                    $data_tindakan_hd_tindakan['tindakan_hd_id']=$tindakan_id;
                    $data_tindakan_hd_tindakan['tindakan_id']=$row['tindakan_id'];
                    $data_tindakan_hd_tindakan['jumlah']=$row['user'];
                   
                    $data_tindakan_hd_tindakan['tindakan_hd_paket_id']=$tindakanhdpaketid;
                    $data_tindakan_hd_tindakan['is_paket']=1;
                    $data_tindakan_hd_tindakan['is_active']=1;
                    $data_tindakan_hd_tindakan['keterangan']=$keterangan_tindakan;
                    $this->tindakan_hd_tindakan_m->save($data_tindakan_hd_tindakan);
                }
            
            }
        }
        
        
        echo json_encode('sukses');
    }

    public function modal_detail($lantai, $bed_id, $shift) 
    {

        $data_detail = $this->bed_m->get_bed_pasien($bed_id, $shift);

        if ($data_detail) 
        {
            $form_data = object_to_array($data_detail);
        } 
        else
        {
            $form_data = array
            (
                array
                (
                    "bed_id"             => '',
                    "tindakan_id"        => '',
                    "no_transaksi"       => '',
                    "bed_kode"           => '',
                    "bed_nama"           => '',
                    "lantai"             => '',
                    "pasien"             => '',
                    "jam_mulai"          => '',
                    "dokter"             => '',
                    "perawat"            => '',
                    "url_photo"          => '',
                    "tanggal_registrasi" => '',
                    "tanggal_lahir"      => '',
                ),
            );
        }

        $data = array(
            'form_data' => $form_data,
            'bed_id'    => $bed_id,
            'lantai'    => $lantai,
        );
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/modal_detail', $data);
    }



    // Kebutuhan Denah | Created by Abu
    public function show_denah_lantai_html()
    {   
        $lantai = $this->input->post('lantai');
        // die_dump($lantai);

        $bed = $this->bed_m->get_by(array('is_active' => 1, 'lantai_id' => $lantai));
        $bed = object_to_array($bed);

        $denah_html = '';
        $denah_html .= "<style>
                            svg {
                                background: url('".base_url()."assets/mb/global/image/Denah RayCare.jpg') no-repeat; 
                                background-size: 100%;
                            }
                        </style>
                        <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Layer_1_".$lantai."' x='0px' y='0px' width='100%' height='100%' viewBox='0 0 1907.08 715.16' style='enable-background:new 0 0 1907.08 715.16;' xml:space='preserve'>";

                        $i=0;
                        foreach ($bed as $row) {

                            $is_user = $this->bed_m->get_by(array('user_edit_id' => $this->session->userdata('user_id')));

                            // GET ID TINDAKAN 
                            $tindakan_id     = $this->bed_m->get_bed_pasien($row['id'],$row['shift']);
                        
                            $pasien_id       = 'null';
                            $tindakan_row_id = 'null';
                            // foreach ($tindakan as $tindakan_row) {
                            if(count($tindakan_id))
                            {
                                $tindakan_row_id = $tindakan_id[0]['tindakan_id'];
                                $pasien_id = $tindakan_id[0]['pasien_id'];
                                
                            }

                            $get_visit = $this->tindakan_hd_visit_m->get_by(array('tindakan_hd_id' => $tindakan_row_id, 'pasien_id' => $pasien_id, 'dokter_id' => $this->session->userdata('user_id')));
                            $get_visited = $this->tindakan_hd_visit_m->get_by(array('tindakan_hd_id' => $tindakan_row_id, 'pasien_id' => $pasien_id));

                            $id_tindakan_visit = '';
                            if ($get_visit) 
                            {
                                $last_record       = count($get_visit);
                                $id_tindakan_visit = $get_visit[intval($last_record)-1]->id;
                            }

                            $data_tindakan_hd = $this->tindakan_hd_m->get_bed_pasien_isi($row['id'],$row['shift']);
                            $nama_pasien = $row['nama'];
                            
                            // die_dump($id_tindakan_visit);

                            $point_box = $row['point_box'];
                            $split_str = explode(',', $point_box);

                            $status = '';
                            $color = '#fff';
                            if ($row['status'] == 1) 
                            {
                                $status = '#44b6ae';
                                $data_content = 'Bed Kosong';

                            } elseif ($row['status'] == 2) 
                            {
                                $transaksi = $this->tindakan_hd_m->get_data_id($row['id'],$row['shift']);
                                if(count($transaksi))
                                {
                                    $nama_pasien = $transaksi->nama_pasien;
                                }
                                $status = '#f3c200';
                                $data_content = 'Bed Dipesan';

                            } elseif ($row['status'] == 3) 
                            {
                                $status = '#d91e18';
                                if(count($data_tindakan_hd))
                                {
                                    $nama_pasien = $data_tindakan_hd->nama_pasien;
                                }
                                if(count($get_visited))
                                {
                                    $status = 'purple';
                                }
                                
                                
                                $data_content = '<a id="start_visit" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary"><i class="fa fa-gear"></i>'.translate(' Start Visit', $this->session->userdata('language')).'</a><br>
                                                 <a id="detail" data-toggle="modal" data-target="#modal_detail" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'"  class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>
                                                ';                                    
                               
                                if ($is_user) 
                                {
                                    $data_content = '<a id="detail" data-toggle="modal" data-target="#modal_detail" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'"  class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }

                            } elseif ($row['status'] == 4)
                            {
                                $status = '#95a5a6';
                                $data_content = 'Maintenance';

                            } elseif ($row['status'] == 5)
                            {   
                                if(count($data_tindakan_hd))
                                {
                                    $nama_pasien = $data_tindakan_hd->nama_pasien;
                                }
                                $tutup_dialisis = '';
                                if ($row['user_edit_id'] == $this->session->userdata('user_id')) 
                                {
                                    $tutup_dialisis = '<a id="end_visit" data-toggle="modal" data-target="#modal_end_visit" href="'.base_url().'klinik_hd/transaksi_dokter/modal_end_visit/'.$row['id'].'/'.$id_tindakan_visit.'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn red-intense detail"><i class="fa fa-times"></i>'.translate(' End Visit', $this->session->userdata('language')).'</a><br>';
                                }
                                $color = '#fff';
                                $status = '#2c3e50';//Hitam
                                $data_content = $tutup_dialisis.'<a id="detail" data-toggle="modal" data-target="#modal_detail" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'"  class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                            }

                            $data_content .= '<script> 
                                                $(document).ready(function() 
                                                {
                                                    baseAppUrl = mb.baseUrl() + "klinik_hd/transaksi_dokter/";
                                                    
                                                    $("a#start_visit").click(function() {
                                                        var id = $(this).data("id");

                                                        $.ajax ({ 
                                                            type: "POST",
                                                            url: baseAppUrl + "set_visit",  
                                                            data:  {
                                                                id:id, 
                                                                type:0,
                                                                id_tindakan:'.$tindakan_row_id.',
                                                                pasien_id:'.$pasien_id.'
                                                            },  
                                                            dataType : "json",
                                                            beforeSend : function(){
                                                                Metronic.blockUI({boxed: true });
                                                            },
                                                            success:function(data)         
                                                            { 
                                                                location.href = "'.base_url().'klinik_hd/transaksi_dokter/visit/0/'.$tindakan_row_id.'/'.$pasien_id.'/'.$row['id'].'/" + data[3];
                                                            },
                                                            complete : function() {
                                                                Metronic.unblockUI();
                                                            }
                                                        });

                                                        $(".popover_menu").popover("hide");
                                                    }); 

                                                    $("a#end_visit").click(function() {
                                                        var id = $(this).data("id");

                                                        // $.ajax ({ 
                                                        //     type: "POST",
                                                        //     url: baseAppUrl + "show_denah_lantai_html",  
                                                        //     data: {lantai: "'.$lantai.'"}, 
                                                        //     dataType : "text",
                                                        //     beforeSend : function(){
                                                        //         Metronic.blockUI({boxed: true });
                                                        //     },
                                                        //     success:function(data2)         
                                                        //     { 
                                                        //         $("div.svg_file_lantai_'.$lantai.'").html(data2);
                                                        //         // mb.showMessage(data[0],data[1],data[2]);
                                                        //     },
                                                        //     complete : function() {
                                                        //         Metronic.unblockUI();
                                                        //     }
                                                        // });


                                                        $(".popover_menu").popover("hide");
                                                    }); 


                                                    $("a#detail").click(function() 
                                                    {
                                                        $(".popover_menu").popover("hide");

                                                    });
                                                    

                                                });
                                            </script>';

                            $denah_html .= "<polygon id='bed_".$i."' class='popover_bed' points='".$row['point']."' data-id='".($i+1)."' data-content='".$data_content."' style='fill:white; opacity:0.1; cursor: hand;'/>";
                            $denah_html .= "<rect id='box_bed_".$i."' class='rectangle' x='".$split_str[0]."' y='".$split_str[1]."' rx='10' ry='10' width='150' height='20' style='fill:".$status.";' />";
                            $multiply = 15 + (10 * strlen($row['kode']));
                           
                            $denah_html .= "<text x='".($split_str[0]+10)."' y='".($split_str[1]+16)."' font-family='arial' font-size='16' font-weight='bold' fill='".$color."' > ".$row['kode']."</text>";
                            $denah_html .= "<text x='".($split_str[0]+$multiply)."' y='".($split_str[1]+16)."' font-family='arial' font-size='12' font-weight='' fill='".$color."' > ".$nama_pasien."</text>";
                            $i++;
                        };

        $denah_html .= "</svg>";

        $denah_html .= "<script>
                            $(document).ready(function() 
                            {
                                KlikBed();
                            });

                            function KlikBed()
                            {
                                var lastPopoverBed = null;
                                var bed = $('polygon');

                                $.each(bed, function(idx, colBed){
                                    
                                    var colBed = $(colBed);

                                    colBed.popover({
                                        html : true,
                                        container : 'body',
                                        placement : 'top',
                                        content: function(){
                                           
                                        }
                                    }).on('show.bs.popover', function(){
                                        $(this).data('bs.popover').tip().addClass('popover_menu');
                                        $(this).data('bs.popover').tip().css({minWidth: '100px', maxWidth: '300px', margin: '26px 0 0 40px', left : '85.808815px'});
                                        if (lastPopoverBed !== null) lastPopoverBed.popover('hide');
                                        lastPopoverBed = colBed;
                                    }).on('hide.bs.popover', function(){
                                        lastPopoverBed = null;
                                    }).on('click', function(e){

                                    });
                                });
                            }

                        </script>";
            

        echo $denah_html;
    }

    public function modal_end_visit($bed_id, $id_tindakan_visit){

        $data = array(
            'bed_id'            => $bed_id,
            'id_tindakan_visit' => $id_tindakan_visit,
        );
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_dokter/modal_end_visit', $data);

    }

    public function set_visit()
    {
        $bed_id            = $this->input->post('id');
        $type              = $this->input->post('type');
        $id_tindakan       = $this->input->post('id_tindakan');
        $pasien_id         = $this->input->post('pasien_id');
        $id_tindakan_visit = $this->input->post('id_tindakan_visit');

        $data_bed = array();
        $tindakan_hd_visit_id = '';
        if ($type) 
        {
            $data_bed['user_edit_id'] = null;
            $data_bed['status']       = 3;

            $keterangan = $this->input->post('keterangan');

            $data_tindakan_hd_visit =  array(
                'end_visit'    => date('Y-m-d H:i:s'),
                'keterangan'   => $keterangan
            );
            $tindakan_hd_visit_id = $this->tindakan_hd_visit_m->save($data_tindakan_hd_visit, $id_tindakan_visit);
        } 
        else {

            $data_bed['user_edit_id'] = $this->session->userdata('user_id');
            $data_bed['status']       = 5;

            // ketika visit insert ke tabel tindakan_hd_visit
            $data_tindakan_hd_visit =  array(
                'tindakan_hd_id' => $id_tindakan,
                'pasien_id'      => $pasien_id,
                'dokter_id'      => $this->session->userdata('user_id'),
                'start_visit'    => date('Y-m-d H:i:s')
            );
            $tindakan_hd_visit_id = $this->tindakan_hd_visit_m->save($data_tindakan_hd_visit);
        }
        
        $bed = $this->bed_m->save($data_bed, $bed_id);

        $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
        $date = getDate();
        $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
        file_put_contents($file,$jam);

        if ($bed) 
        {
            $flashdata = array(
                "success",
                translate("Kunjungan dialisis telah diakhiri", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language")),
                $tindakan_hd_visit_id,
            );
            
            echo json_encode($flashdata);
        }
    }


    // Kebutuhan Denah Create Tindakan | Created by Abu
    public function show_denah_lantai_html_create()
    {   
        $lantai = $this->input->post('lantai');
        $shift = $this->input->post('shift');
        $shift_prev = intval($this->input->post('shift')) - 1;
        $shift_next = intval($this->input->post('shift')) + 1;
        // die_dump($lantai);

        $bed = $this->bed_m->get_by(array('is_active' => 1, 'lantai_id' => $lantai));
        $bed = object_to_array($bed);

        
        // die(dump($bed_booking->id));

        $denah_html = '';
        $denah_html .= "<style>
                            svg {
                                background: url('".base_url()."assets/mb/global/image/Denah RayCare.jpg') no-repeat; 
                                background-size: 100%;
                            }
                        </style>
                        <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Layer_1_".$lantai."' x='0px' y='0px' width='100%' height='100%' viewBox='0 0 1907.08 715.16' style='enable-background:new 0 0 1907.08 715.16;' xml:space='preserve'>";

                        $i=0;
                        foreach ($bed as $row) {
                            $is_user = $this->bed_m->get_by(array('user_edit_id' => $this->session->userdata('user_id')));
                            // GET ID TINDAKAN 

                            $tindakan_id     = $this->bed_m->get_bed_pasien($row['id'],$shift);
                            $tindakan_row_id = '';
                            // foreach ($tindakan as $tindakan_row) {
                            if(count($tindakan_id))
                            {
                                $tindakan_row_id = $tindakan_id[0]['tindakan_id'];
                                
                            }

                            $data_tindakan_hd_prev = $this->tindakan_hd_m->get_bed_pasien_isi($row['id'],$shift_prev);
                            $data_tindakan_hd = $this->tindakan_hd_m->get_bed_pasien_isi($row['id'],$shift);
                            $data_tindakan_hd_next = $this->tindakan_hd_m->get_bed_pasien_isi($row['id'],$shift_next);
                            $nama_pasien = $row['nama'];
                            $nama_pasien_next = '';

                            //cek apakah ada transaksi yang menggunakan bed ini tetapi transaksinya belum diproses
                            $cek_tindakan_prev = $this->tindakan_hd_m->get_data_id($row['id'],$shift_prev);
                            $cek_tindakan = $this->tindakan_hd_m->get_data_id($row['id'],$shift);
                            $cek_tindakan_next = $this->tindakan_hd_m->get_data_id($row['id'],$shift_next);

                            $point_box = $row['point_box'];
                            $split_str = explode(',', $point_box);

                            $estimate_time = '';
                            $estimate_time_text = '';
                            $status = '';
                            $height = 20;
                            $color = '#fff';
                            if ($row['status'] == 1 && $row['status_antrian'] == 0) 
                            {
                                $status = '#44b6ae';//Hijau
                                $data_content = '<a style="margin-bottom:5px;" id="pilih_bed" data-id="'.$row['id'].'" data-kode="'.$row['kode'].'" class="btn btn-primary"><i class="fa fa-check"></i> Pilih Bed</a>';

                            } elseif ($row['status'] == 2) 
                            {
                                $status = '#f3c200'; //KUNING
                                if($row['status_antrian'] == 0 && $row['shift'] == $shift){
                                    if(count($cek_tindakan_prev))
                                    {
                                        $nama_pasien = $cek_tindakan_prev->nama_pasien;
                                    }
                                    if(count($cek_tindakan))
                                    {
                                        $nama_pasien = $cek_tindakan->nama_pasien;
                                    }
                                    $data_content = translate('Pasien di bed ini belum diproses.',$this->session->userdata('language'));
                                }if($row['status_antrian'] == 0 && $row['shift'] == ($shift-1)){
                                    if(count($cek_tindakan_prev))
                                    {
                                        $nama_pasien = $cek_tindakan_prev->nama_pasien;
                                    }
                                    if(count($cek_tindakan))
                                    {
                                        $nama_pasien = $cek_tindakan->nama_pasien;
                                    }
                                    $data_content = '<a style="margin-bottom:5px;" id="pilih_bed" data-id="'.$row['id'].'" data-kode="'.$row['kode'].'" class="btn btn-primary"><i class="fa fa-check"></i> Pilih Bed</a>';
                                }if($row['status_antrian'] == 1 && $row['shift'] != $shift){
                                    $height = 40;
                                    if(count($cek_tindakan))
                                    {
                                        $nama_pasien = $cek_tindakan->nama_pasien;
                                    }
                                    if(count($cek_tindakan_next))
                                    {
                                        $nama_pasien_next = $cek_tindakan_next->nama_pasien;
                                    }
                                    $data_content = translate('Pasien di bed ini belum diproses.',$this->session->userdata('language'));
                                }

                            } elseif ($row['status'] == 3) 
                            {
                                $status = '#d91e18';//MERAH
                                $estimate_time = "<rect id='box_time_".$i."' class='rectangle' x='".($split_str[0])."' y='".($split_str[1]-25)."' rx='10' ry='10' width='100' height='20' style='fill:#000;' />";
                                
                                if($row['status_antrian'] == 0 && $row['shift'] == $shift){
                                    if(count($data_tindakan_hd_prev))
                                    {
                                        $nama_pasien = $data_tindakan_hd_prev->nama_pasien;
                                        $start = substr($data_tindakan_hd_prev->waktu, 0,5);
                                        $time = $data_tindakan_hd_prev->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));
                                    }if(count($data_tindakan_hd))
                                    {
                                        $nama_pasien = $data_tindakan_hd->nama_pasien;
                                        $start = substr($data_tindakan_hd->waktu, 0,5);
                                        $time = $data_tindakan_hd->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);

                                    if($start_time < 0.00){
                                        $start =  'Waiting...';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    $data_content = '<a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }
                                if($row['status_antrian'] == 0 && $row['shift'] == ($shift-1)){
                                    if(count($data_tindakan_hd_prev))
                                    {
                                        $nama_pasien = $data_tindakan_hd_prev->nama_pasien;
                                        $start = substr($data_tindakan_hd_prev->waktu, 0,5);
                                        $time = $data_tindakan_hd_prev->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    }if(count($data_tindakan_hd))
                                    {
                                        $nama_pasien = $data_tindakan_hd->nama_pasien;
                                        $start = substr($data_tindakan_hd->waktu, 0,5);
                                        $time = $data_tindakan_hd->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);
                                    // die(dump($start));

                                    if($start_time < 0.00){
                                        $start =  'Waiting...';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    $data_content = '<a style="margin-bottom:5px;" id="pilih_bed" data-id="'.$row['id'].'" data-kode="'.$row['kode'].'" class="btn btn-primary"><i class="fa fa-check"></i> Pilih Bed</a>
                                    <br><a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }
                                if($row['status_antrian'] == 1 && $row['shift'] != $shift){
                                    $height = 40;
                                    if(count($data_tindakan_hd_prev))
                                    {
                                        $nama_pasien = $data_tindakan_hd_prev->nama_pasien;
                                        $start = substr($data_tindakan_hd_prev->waktu, 0,5);
                                        $time = $data_tindakan_hd_prev->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    }
                                    if(count($cek_tindakan))
                                    {
                                        $nama_pasien_next = $cek_tindakan->nama_pasien;
                                        

                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);
                                    // die(dump($start));

                                    if($start_time < 0.00){
                                        $start =  'Waiting...';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    $data_content = '<a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }

                            } elseif ($row['status'] == 4)
                            {
                                $status = '#95a5a6';//ABU-ABU
                                $data_content = translate('Maintenance',$this->session->userdata('language'));

                            } elseif ($row['status'] == 5)
                            {  
                                $color = '#fff';
                                $status = '#2c3e50';//Hitam
                                $estimate_time = "<rect id='box_time_".$i."' class='rectangle' x='".($split_str[0])."' y='".($split_str[1]-25)."' rx='10' ry='10' width='100' height='20' style='fill:#000;' />";
                                if($row['status_antrian'] == 0 && $row['shift'] == $shift){
                                    if(count($data_tindakan_hd_prev))
                                    {
                                        $nama_pasien = $data_tindakan_hd_prev->nama_pasien;
                                        $start = substr($data_tindakan_hd_prev->waktu, 0,5);
                                        $time = $data_tindakan_hd_prev->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));
                                    }if(count($data_tindakan_hd))
                                    {
                                        $nama_pasien = $data_tindakan_hd->nama_pasien;
                                        $start = substr($data_tindakan_hd->waktu, 0,5);
                                        $time = $data_tindakan_hd->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    } 
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);

                                    if($start_time < 0.00){
                                        $start =  'Waiting...';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    $data_content = '<a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';

                                }
                                if($row['status_antrian'] == 0 && $row['shift'] == ($shift-1)){
                                    if(count($data_tindakan_hd_prev))
                                    {
                                        $nama_pasien = $data_tindakan_hd_prev->nama_pasien;
                                        $start = substr($data_tindakan_hd_prev->waktu, 0,5);
                                        $time = $data_tindakan_hd_prev->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));
                                    }if(count($data_tindakan_hd))
                                    {
                                        $nama_pasien = $data_tindakan_hd->nama_pasien;
                                        $start = substr($data_tindakan_hd->waktu, 0,5);
                                        $time = $data_tindakan_hd->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));
                                    }
                                    // die(dump($start));

                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);
                                    // die(dump($start));

                                    if($start_time < 0.00){
                                        $start =  'Waiting...';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    $data_content = '<a style="margin-bottom:5px;" id="pilih_bed" data-id="'.$row['id'].'" data-kode="'.$row['kode'].'" class="btn btn-primary"><i class="fa fa-check"></i> Pilih Bed</a>
                                    <br><a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }
                                if($row['status_antrian'] == 1 && $row['shift'] == $shift){
                                    $height = 40;
                                    if(count($data_tindakan_hd))
                                    {
                                        $nama_pasien = $data_tindakan_hd->nama_pasien;
                                        $start = substr($data_tindakan_hd->waktu, 0,5);
                                        $time = $data_tindakan_hd->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));
                                    }
                                    if(count($cek_tindakan_next))
                                    {
                                        $nama_pasien_next = $cek_tindakan_next->nama_pasien;
                                        
                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);
                                    // die(dump($start));

                                    if($start_time < 0.00){
                                        $start =  'Waiting...';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    $data_content = '<a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }
                                if($row['status_antrian'] == 1 && $row['shift'] != $shift){
                                    $height = 40;
                                    if(count($data_tindakan_hd_prev))
                                    {
                                        $nama_pasien = $data_tindakan_hd_prev->nama_pasien;
                                        $start = substr($data_tindakan_hd_prev->waktu, 0,5);
                                        $time = $data_tindakan_hd_prev->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));
                                    }
                                    if(count($cek_tindakan))
                                    {
                                        $nama_pasien_next = $cek_tindakan->nama_pasien;
                                        
                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);
                                    // die(dump($start));

                                    if($start_time < 0.00){
                                        $start =  'Waiting...';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    $data_content = '<a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/transaksi_dokter/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }

                            }

                            $data_content .= '<script> 
                                $(document).ready(function() {
                                    
                                    baseAppUrl = mb.baseUrl() + "klinik_hd/transaksi_dokter/";

                                    $("a#pilih_bed").click(function() {
                                        var id = $(this).data("id");
                                        var kode = $(this).data("kode");
                                        $("input#bed_id").val(id);

                                        $("div#bed_terpilih").text(" Pilih Bed [ "+kode+" ]");
                                        $("div#bed_terpilih").addClass("bold");
                                        $("div#bed_terpilih").attr("style","color:red;");

                                        $(".popover_menu").popover("hide");
                                    }); 

                                    $("a#batal").click(function() {
                                        var id = $(this).data("id");
                                        var bed_id = $("input#bed_id").val();


                                        bootbox.confirm("'.translate('Batalkan penggunaan bed ini?', $this->session->userdata('language')).'", function(result){
                                            if(result === true)
                                            {

                                                if(bed_id == id)
                                                {
                                                    $("input#bed_id").val("");                                            
                                                }

                                                $.ajax ({ 
                                                    type: "POST",
                                                    url: "'.base_url().'" + "klinik_hd/transaksi_dokter/batal_bed",  
                                                    data:  {id:id},  
                                                    dataType : "json",
                                                    beforeSend : function(){
                                                        Metronic.blockUI({boxed: true });
                                                    },
                                                    success:function(data)         
                                                    { 
                                                        $.ajax ({ 
                                                            type: "POST",
                                                            url: baseAppUrl + "show_denah_lantai_html_create",  
                                                            data: {lantai: "'.$lantai.'"}, 
                                                            dataType : "text",
                                                            success:function(data2)         
                                                            { 
                                                                $("input#bed_id").val(data[3]);
                                                                $("div.svg_file_lantai_'.$lantai.'").html(data2);
                                                                mb.showMessage(data[0],data[1],data[2]);
                                                            },
                                                        });

                                                    },
                                                    complete : function() {
                                                        Metronic.unblockUI();
                                                    }
                                                });

                                                $(".popover_menu").popover("hide");
                                            }
                                        });
                                    });

                                    $("a#detail").click(function() 
                                    {
                                        $(".popover_menu").popover("hide");
                                    }); 


                                });
                            </script>';

                            $denah_html .= "<polygon id='bed_".$i."' class='popover_bed' points='".$row['point']."' data-id='".($i+1)."' data-content='".$data_content."' style='fill:white; opacity:0.1; cursor: hand;'/>";
                            $denah_html .= $estimate_time;
                            $denah_html .= "<rect id='box_bed_".$i."' class='rectangle' x='".$split_str[0]."' y='".$split_str[1]."' rx='10' ry='10' width='150' height='".$height."' style='fill:".$status.";' />";
                            $multiply = 15 + (10 * strlen($row['kode']));
                           
                            $denah_html .= $estimate_time_text;
                            $denah_html .= "<text x='".($split_str[0]+10)."' y='".($split_str[1]+16)."' font-family='arial' font-size='16' font-weight='bold' fill='".$color."' > ".$row['kode']."</text>";
                            $denah_html .= "<text x='".($split_str[0]+$multiply)."' y='".($split_str[1]+16)."' font-family='arial' font-size='12' font-weight='' fill='".$color."' > ".$nama_pasien."</text>";
                            $denah_html .= "<text x='".($split_str[0]+$multiply)."' y='".($split_str[1]+30)."' font-family='arial' font-size='12' font-weight='' fill='".$color."' > ".$nama_pasien_next."</text>";
                            $i++;
                        };

        $denah_html .= "</svg>";

        $denah_html .= "<script>
                            $(document).ready(function() 
                            {
                                KlikBed();
                            });

                            function KlikBed()
                            {
                                var lastPopoverBed = null;
                                var bed = $('polygon');

                                $.each(bed, function(idx, colBed){
                                    
                                    var colBed = $(colBed);

                                    colBed.popover({
                                        html : true,
                                        container : 'body',
                                        placement : 'top',
                                        content: function(){
                                           
                                        }
                                    }).on('show.bs.popover', function(){
                                        $(this).data('bs.popover').tip().addClass('popover_menu');
                                        $(this).data('bs.popover').tip().css({minWidth: '100px', maxWidth: '300px', margin: '26px 0 0 40px', left : '85.808815px'});
                                        if (lastPopoverBed !== null) lastPopoverBed.popover('hide');
                                        lastPopoverBed = colBed;
                                    }).on('hide.bs.popover', function(){
                                        lastPopoverBed = null;
                                    }).on('click', function(e){

                                    });
                                });
                            }

                        </script>";
            

        echo $denah_html;
    }

    public function commet_bed()
    {
        $file = urlencode(base64_encode($this->session->userdata('url_login')));

        $filename  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
        // die(dump($filename));
        // infinite loop until the data file is not modified
        $lastmodif    = isset($_POST['timestamp']) ? $_POST['timestamp'] : 0;
        $currentmodif = filemtime($filename);
          // die(dump($lastmodif));
        while ($currentmodif <= $lastmodif) // check if the data file has been modified
        {
          usleep(10000); // sleep 10ms to unload the CPU
          clearstatcache();
          $currentmodif = filemtime($filename);
        }

        // return a json array
        $response = array();
        $response['msg']       = file_get_contents($filename);
        $response['timestamp'] = $currentmodif;
        echo json_encode($response);
        flush();
    }

    public function modal_item_diluar_paket($tindakan_hd_id)
    {
        $this->load->model('apotik/gudang_m');
        $data = array(
            'tindakan_hd_id' => $tindakan_hd_id
        );

        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/modal_item_diluar_paket', $data);
    }

    public function listing_item_telah_digunakan($tindakan_id=null)
    {
        $result = $this->tindakan_hd_item_m->get_datatable($tindakan_id);
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
            $tindakan_hd = $this->tindakan_hd_item_m->get_by(array('waktu' => $row['waktu'], 'item_id' => $row['item_id'], 'item_satuan_id' => $row['item_satuan_id'], 'created_by' => $row['created_by']));
            if(count($tindakan_hd))
            {
                foreach ($tindakan_hd as $hd) 
                {
                    $id .= $hd->id.',';
                }
                $id = urlencode(base64_encode($id));
            }
            if ($row['tipe_pemberian'] == 1) 
            {
                // Join dengan paket
                $tindakan_paket = $this->tindakan_hd_paket_m->get($row['transaksi_pemberian_id']);
                $paket = $this->paket_m->get($tindakan_paket->paket_id);
                $tipe = $paket->nama.' ['.$paket->kode.']';
            } elseif ($row['tipe_pemberian'] == 2) 
            {
                $tipe = "Simpan Item History";
            } else 
            {
                $tipe = "Diluar Paket";
            }

            if($row['is_identitas'] == 1)
            {
            $col_jumlah = '<div class="col-md-12">
                            <div class="input-group">
                                <input class="form-control text-center" style="background: transparent; border: 0px;" value="'.$row['jumlah'].' '.$row['item_satuan_nama'].'">
                                <span class="input-group-btn">
                                    <a class="btn btn-primary" href="'.base_url().'klinik_hd/transaksi_dokter/modal_view_item/'.$id.'" data-id="'.$row['id'].'" data-target="#modal_view_item" data-toggle="modal">
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
                '<div class="text-center">'.$row['jumlah'].' '.$row['item_satuan_nama'].'</div>',
                '<div class="text-center">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-center">'.$row['expire_date'].'</div>',
                '<div class="text-center">'.$row['user_nama'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );
        }

        echo json_encode($output);
    }


    public function listing_item_diluar_paket($gudang_id = null, $kategori = null)
    {
        $result = $this->inventory_klinik_m->get_datatable_diluar_paket($gudang_id, $kategori);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        foreach($records->result_array() as $row)
        {   
            $satuan_primary = $this->inventory_klinik_m->get_satuan_inventori($row['gudang_id'], $row['item_id'])->result_array();
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan ="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
            
            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['item_kategori'].'</div>',
                '<div class="text-center">'.$row['nama_gudang'].'</div>',
                '<div class="text-left">'.$row['item_keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );
        }

        echo json_encode($output);
    }

    public function modal_inventori_identitas($gudang_id, $item_id, $item_satuan_id, $row=null)
    {

        $data = array(
            'gudang_id'         => $gudang_id,
            'item_id'           => $item_id,
            'item_satuan_id'    => $item_satuan_id
        );
        if($row != null)
        {
            $data['row']    = $row;
        }
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/modal_inventori_identitas', $data);

    }

    public function save_item_diluar_paket()
    {
        // die_dump($this->input->post());

        $waktu               = $this->input->post('waktu_diluar_paket');
        $tindakan_hd_id      = $this->input->post('tindakan_hd_id');
        $item_id             = $this->input->post('item_id');
        $jumlah              = $this->input->post('jumlah_inventory');
        $satuan_id           = $this->input->post('satuan');
        $inventory_id        = $this->input->post('inventory_id');
        $stock               = $this->input->post('stock');
        $harga_beli          = $this->input->post('harga_beli');
        $keterangan          = $this->input->post('keterangan_diluar_paket');
        $inventory_identitas = $this->input->post('inventory_identitas');
        $gudang_id = $this->input->post('gudang_id');
        $pmb_id = $this->input->post('pmb_id');
        // die(dump($inventory_identitas));

        $data_item = $this->item_m->get($item_id);

        
        if($inventory_identitas) 
        {
            // SAVE SIMPAN_ITEM_HISTORY
            $data_inventory_history = array(
                'transaksi_id'   => $tindakan_hd_id,
                'transaksi_tipe' => 5
            );
            $inventory_history_id = $this->inventory_history_m->save($data_inventory_history);
            
            $i = 1;
            foreach ($inventory_identitas as $data_inventory_identitas) 
            {

                if ($data_inventory_identitas['jumlah'] != 0) 
                {

                    $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();

                    // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG
                    $data_tindakan_hd_item = array(
                        'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                        'user_id'                => $this->session->userdata('user_id'),
                        'tindakan_hd_id'         => $tindakan_hd_id,
                        'item_id'                => $item_id,
                        'jumlah'                 => $data_inventory_identitas['jumlah'],
                        '`index`'                  => 1,
                        'item_satuan_id'         => $satuan_id,
                        'harga_beli'             => $data_inventory_identitas['harga_beli'],
                        'harga_jual'             => $harga_jual[0]['harga'],     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                        'keterangan'             => $keterangan,
                        'tipe_pemberian'         => 3,
                        'is_active'              => 1
                    );
                    $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);
                    

                    // SAVE INVENTORY_HISTORY_DETAIL
                    $data_inventory_history_detail = array
                    (
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $data_inventory_identitas['gudang_id'],
                        'pmb_id'               => $data_inventory_identitas['pmb_id'],
                        'item_id'              => $item_id,
                        'item_satuan_id'       => $satuan_id,
                        'initial_stock'        => $data_inventory_identitas['stock'],
                        'change_stock'         => '-'.$data_inventory_identitas['jumlah'],
                        'final_stock'          => (intval($data_inventory_identitas['stock']) - intval($data_inventory_identitas['jumlah'])),
                        'harga_beli'           => $data_inventory_identitas['harga_beli'],
                        'total_harga'          => (intval($data_inventory_identitas['jumlah'])*intval($data_inventory_identitas['harga_beli'])),
                    );
                    $inventory_history_detail_id = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                    // UNTUK MEGUPDATE STOCK INVENTORY_IDENTITAS
                    $data_inventory_idt = array(
                        'jumlah'        => (intval($data_inventory_identitas['stock']) - intval($data_inventory_identitas['jumlah'])),
                        'modified_by'   => $this->session->userdata('user_id'),
                        'modified_date' => date('Y-m-d H:i:s')
                    );
                    $inventory_identitas_id = $this->inventory_identitas_m->save_inventory_identitas($data_inventory_idt, $data_inventory_identitas['inventory_identitas_id']);

                    // UNTUK MENGUPDATE JUMLAH INVENTORY
                    $jumlah_inventory = $this->inventory_m->get_by(array('inventory_id' => $data_inventory_identitas['inventory_id']));
                    $jumlah_inventory = intval($jumlah_inventory[0]->jumlah) - intval($data_inventory_identitas['jumlah']);
                    $data_inventory = array(
                        'jumlah'        => $jumlah_inventory,
                        'modified_by'   => $this->session->userdata('user_id'),
                        'modified_date' => date('Y-m-d H:i:s')
                    );
                    $data_inventory_id = $this->inventory_m->save_inventory($data_inventory, $data_inventory_identitas['inventory_id']);

                    // SAVE INVENTORY_HISTORY_IDENTITAS
                    $data_inventory_history_identitas = array
                    (
                        'inventory_history_detail_id' => $inventory_history_detail_id,
                        'jumlah'                      => $data_inventory_identitas['jumlah']
                    );
                    $inventory_history_identitas_id = $this->inventory_history_identitas_m->save($data_inventory_history_identitas);

                    // SAVE TINDAKAN_ITEM_IDENTITAS
                    $data_tindakan_hd_item_identitas = array
                    (
                        'tindakan_hd_item_id' => $tindakan_hd_item_id,
                        'jumlah'              => $data_inventory_identitas['jumlah']
                    );
                    $tindakan_hd_item_identitas_id = $this->tindakan_hd_item_identitas_m->save($data_tindakan_hd_item_identitas);


                    $inventory_identitas_detail = $this->input->post('inventory_identitas_detail_'.$i);
                    foreach ($inventory_identitas_detail as $data_inventory_identitas_detail) 
                    {
                        // SAVE INVENTORY_HISTORY_IDENTITAS_DETAIL
                        $data_inventory_history_identitas_detail = array
                        (
                            'inventory_history_identitas_id' => $inventory_history_identitas_id,
                            'identitas_id'                   => $data_inventory_identitas_detail['identitas_id'],
                            'judul'                          => $data_inventory_identitas_detail['judul'],
                            'value'                          => $data_inventory_identitas_detail['value']
                        );
                        $inventory_history_identitas_detail_id = $this->inventory_history_identitas_detail_m->save($data_inventory_history_identitas_detail);

                        // SAVE TINDAKAN_HD_ITEM_IDENTITAS_DETAIL
                        $data_tindakan_hd_item_identitas_detail = array
                        (
                            'tindakan_hd_item_identitas_id' => $tindakan_hd_item_identitas_id,
                            'identitas_id'                  => $data_inventory_identitas_detail['identitas_id'],
                            'judul'                         => $data_inventory_identitas_detail['judul'],
                            'value'                         => $data_inventory_identitas_detail['value']
                        );
                        $tindakan_hd_item_identitas_detail_id = $this->tindakan_hd_item_identitas_detail_m->save($data_tindakan_hd_item_identitas_detail);
                    }

                }  

                 // DELETE INVENTORY JIKA ADA ITEM YG JUMLAHNYA = 0
                $get_data_inventory_kosong = $this->inventory_m->get_by(array('jumlah' => 0));
                foreach ($get_data_inventory_kosong as $data_kosong) 
                {
                    $this->inventory_m->delete_inventory_kosong($data_kosong->inventory_id);
                }

                // DELETE INVENTORY_IDENTITAS YG JUMLAHNYA = 0
                $get_data_inventory_identias_kosong = $this->inventory_identitas_m->get_by(array('jumlah' => 0));
                foreach ($get_data_inventory_identias_kosong as $data_identitas_kosong) 
                {
                    $this->inventory_identitas_m->delete_inventory_identitas_kosong($data_identitas_kosong->inventory_identitas_id);
                    $this->inventory_identitas_detail_m->delete_inventory_identitas_detail_kosong($data_identitas_kosong->inventory_identitas_id);
                }

                $i++;

                
            } 
        }
        else
        {
            if($jumlah != 0)
            {
                // SAVE SIMPAN_ITEM_HISTORY
                $data_inventory_history = array(
                    'transaksi_id'   => $tindakan_hd_id,
                    'transaksi_tipe' => 5
                );
                $inventory_history_id = $this->inventory_history_m->save($data_inventory_history);

                $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();

                // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG
                $data_tindakan_hd_item = array(
                    'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                    'user_id'                => $this->session->userdata('user_id'),
                    'tindakan_hd_id'         => $tindakan_hd_id,
                    'item_id'                => $item_id,
                    'jumlah'                 => $jumlah,
                    '`index`'                  => 1,
                    'item_satuan_id'         => $satuan_id,
                    'harga_beli'             => $harga_beli,
                    'harga_jual'             => $harga_jual[0]['harga'],     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                    'keterangan'             => $keterangan,
                    'tipe_pemberian'         => 3,
                    'is_active'              => 1
                );
                $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);
                

                // SAVE INVENTORY_HISTORY_DETAIL
                $data_inventory_history_detail = array
                (
                    'inventory_history_id' => $inventory_history_id,
                    'gudang_id'            => $gudang_id,
                    'pmb_id'               => $pmb_id,
                    'item_id'              => $item_id,
                    'item_satuan_id'       => $satuan_id,
                    'initial_stock'        => $stock,
                    'change_stock'         => '-'.$jumlah,
                    'final_stock'          => (intval($stock) - intval($jumlah)),
                    'harga_beli'           => $harga_beli,
                    'total_harga'          => (intval($jumlah)*intval($harga_beli)),
                );
                $inventory_history_detail_id = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                // UNTUK MENGUPDATE JUMLAH INVENTORY
                $jumlah_inventory = $this->inventory_m->get_by(array('inventory_id' => $inventory_id));
                $jumlah_inventory = intval($jumlah_inventory[0]->jumlah) - intval($jumlah);
                $data_inventory = array(
                    'jumlah'        => $jumlah_inventory,
                    'modified_by'   => $this->session->userdata('user_id'),
                    'modified_date' => date('Y-m-d H:i:s')
                );
                $data_inventory_id = $this->inventory_m->save_inventory($data_inventory, $inventory_id);


                // DELETE INVENTORY JIKA ADA ITEM YG JUMLAHNYA = 0
                $get_data_inventory_kosong = $this->inventory_m->get_by(array('jumlah' => 0));
                foreach ($get_data_inventory_kosong as $data_kosong) 
                {
                    $this->inventory_m->delete_inventory_kosong($data_kosong->inventory_id);
                }
                
            }

        }
        
        if ($inventory_history_id) 
        {
            $flashdata = array(
                "success",
                translate("Item diluar paket berhasil ditambahkan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
    }

    public function listing_observasi_item_tersimpan($pasien_id = null, $tindakan_hd_id)
    {        
       
        $result = $this->item_tersimpan_m->get_datatable_simpan_item($pasien_id,$tindakan_hd_id);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        foreach($records->result_array() as $row)
        {
           
            $action='<a data-target="#modal_item_tersimpan" data-toggle="modal" href="'.base_url().'klinik_hd/transaksi_dokter/modal_item_tersimpan/'.$row['id'].'/'.$row['item_id'].'/'.$row['item_satuan_id'].'/'.str_replace(' ','_',$row['item_satuan']).'/'.$tindakan_hd_id.'/'.$pasien_id.'" title="'.translate('Gunakan Item', $this->session->userdata('language')).'" name="pakai[]"  data-id="'.$row['id'].'" class="btn btn-primary" style="margin-left: 10px;"><i class="fa fa-check"></i></a>';
             
            $output['data'][] = array(
                 
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['jumlah'].' '.$row['item_satuan'].$action.'</div>',
                '<div class="text-center">0</div>',
            );
        }

        echo json_encode($output);
    }

    public function modal_item_tersimpan($simpan_item_id, $item_id, $satuan_id, $satuan, $tindakan_hd_id, $pasien_id)
    {   
        $data = array(
            'pasien_id'      => $pasien_id,
            'tindakan_hd_id' => $tindakan_hd_id,
            'simpan_item_id' => $simpan_item_id,
            'item_id'        => $item_id,
            'item_satuan_id' => $satuan_id,
            'satuan'         => $satuan
        );

        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/modal_item_tersimpan', $data);
    }

    public function modal_simpan_identitas($pasien_id, $item_id, $satuan_id)
    {
        $data = array(
            'pasien_id'      => $pasien_id,
            'item_id'        => $item_id,
            'item_satuan_id' => $satuan_id
        );

        $this->load->view('klinik_hd/transaksi_dokter/tab_perawat/modal_simpan_identitas', $data);
    }

    public function save_simpan_item()
    {
        $array_input      = $this->input->post();
        $waktu            = $array_input['waktu_item_tersimpan'];
        $tindakan_hd_id   = $array_input['tindakan_hd_id'];
        $simpan_item_id   = $array_input['simpan_item_id'];
        $stock            = $array_input['stock'];
        $item_id          = $array_input['item_id_simpan'];
        $pasien_id        = $array_input['pasien_id'];
        $satuan_id        = $array_input['satuan_id_simpan'];
        $jumlah           = $array_input['jumlah_item_tersimpan'];
        $keterangan       = $array_input['keterangan_item_tersimpan'];

        $data_simpan = $this->item_tersimpan_m->get_by(array('simpan_item_id' => $simpan_item_id), true);
        
        // SAVE SIMPAN_ITEM_HISTORY
        $data_simpan_item_history = array(
            'transaksi_id'   => $tindakan_hd_id,
            'transaksi_tipe' => 1
        );
        $simpan_item_history_id = $this->simpan_item_history_m->save($data_simpan_item_history);

        if (isset($array_input['simpan_identitas'])) 
        {
            $simpan_identitas = $array_input['simpan_identitas'];

            $i = 1;
            foreach ($simpan_identitas as $data_simpan_identitas) 
            {

                if ($data_simpan_identitas['jumlah'] != 0) 
                {
                    // SAVE TINDAKAN_HD_ITEM JIkA DATA BELUM ADA
                    $data_tindakan_hd_item = array(
                        'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                        'user_id'                => $this->session->userdata('user_id'),
                        'tindakan_hd_id'         => $tindakan_hd_id,
                        'item_id'                => $item_id,
                        'jumlah'                 => $data_simpan_identitas['jumlah'],
                        '`index`'                  => intval($data_simpan->index) + 1,
                        'item_satuan_id'         => $satuan_id,
                        'harga_beli'             => $data_simpan_identitas['harga_beli'],
                        'harga_jual'             => $data_simpan_identitas['harga_jual'],
                        'keterangan'             => $keterangan,
                        'transaksi_pemberian_id' => $simpan_item_history_id,
                        'tipe_pemberian'         => 2,
                        'is_active'              => 1
                    );
                    $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);

                    // SAVE SIMPAN_ITEM_HISTORY_DETAIL
                    $data_simpan_item_history_detail = array
                    (
                        'simpan_item_history_id' => $simpan_item_history_id,
                        'pasien_id'              => $pasien_id,
                        'item_id'                => $item_id,
                        'item_satuan_id'         => $satuan_id,
                        'initial_stock'          => $data_simpan_identitas['stock'],
                        'change_stock'           => '-'.$data_simpan_identitas['jumlah'],
                        'final_stock'            => (intval($data_simpan_identitas['stock']) - intval($data_simpan_identitas['jumlah'])),
                        'harga_beli'             => $data_simpan_identitas['harga_beli'],
                        'harga_jual'             => $data_simpan_identitas['harga_jual'],
                        'total_harga'            => (intval($data_simpan_identitas['jumlah'])*intval($data_simpan_identitas['harga_beli'])),
                    );
                    $simpan_item_history_detail_id = $this->simpan_item_history_detail_m->save($data_simpan_item_history_detail);

                    // UNTUK MEGUPDATE STOCK SIMPAN_ITEM_IDENTITAS
                    $data_simpan_item_identitas = array(
                        'jumlah'    => (intval($data_simpan_identitas['stock']) - intval($data_simpan_identitas['jumlah']))
                    );
                    $simpan_item_identitas_id = $this->simpan_item_identitas_m->save_simpan_item_identitas($data_simpan_item_identitas, $data_simpan_identitas['simpan_item_identitas_id']);

                    // UNTUK MENGUPDATE JUMLAH SIMPAN_ITEM
                    $jumlah_simpan_item = $this->item_tersimpan_m->get_by(array('simpan_item_id' => $data_simpan_identitas['simpan_item_id']));
                    $jumlah_simpan = intval($jumlah_simpan_item[0]->jumlah) - intval($data_simpan_identitas['jumlah']);
                    $data_simpan_item = array(
                        'jumlah'    => $jumlah_simpan,
                    );
                    $data_simpan_item_id = $this->item_tersimpan_m->save_simpan_item($data_simpan_item, $data_simpan_identitas['simpan_item_id']);

                    // SAVE SIMPAN_ITEM_HISTORY_IDENTITAS
                    $data_simpan_item_history_identitas = array
                    (
                        'simpan_item_history_detail_id' => $simpan_item_history_detail_id,
                        'jumlah'                        => $data_simpan_identitas['jumlah']
                    );
                    $simpan_item_history_identitas_id = $this->simpan_item_history_identitas_m->save($data_simpan_item_history_identitas);

                    // SAVE TINDAKAN_ITEM_IDENTITAS
                    $data_tindakan_hd_item_identitas = array
                    (
                        'tindakan_hd_item_id' => $tindakan_hd_item_id,
                        'jumlah'              => $data_simpan_identitas['jumlah']
                    );
                    $tindakan_hd_item_identitas_id = $this->tindakan_hd_item_identitas_m->save($data_tindakan_hd_item_identitas);


                    $simpan_identitas_detail = $array_input['simpan_identitas_detail_'.$i];
                    foreach ($simpan_identitas_detail as $data_simpan_identitas_detail) 
                    {
                        // SAVE SIMPAN_ITEM_HISTORY_IDENTITAS_DETAIL
                        $data_simpan_item_history_identitas_detail = array
                        (
                            'simpan_item_history_identitas_id' => $simpan_item_history_identitas_id,
                            'identitas_id'                     => $data_simpan_identitas_detail['identitas_id'],
                            'judul'                            => $data_simpan_identitas_detail['judul'],
                            'value'                            => $data_simpan_identitas_detail['value']
                        );
                        $simpan_item_history_identitas_detail_id = $this->simpan_item_history_identitas_detail_m->save($data_simpan_item_history_identitas_detail);

                        // SAVE TINDAKAN_HD_ITEM_IDENTITAS_DETAIL
                        $data_tindakan_hd_item_identitas_detail = array
                        (
                            'tindakan_hd_item_identitas_id' => $tindakan_hd_item_identitas_id,
                            'identitas_id'                  => $data_simpan_identitas_detail['identitas_id'],
                            'judul'                         => $data_simpan_identitas_detail['judul'],
                            'value'                         => $data_simpan_identitas_detail['value']
                        );
                        $tindakan_hd_item_identitas_detail_id = $this->tindakan_hd_item_identitas_detail_m->save($data_tindakan_hd_item_identitas_detail);
                    }

                }  

                 // DELETE SIMPAN_ITEM JIKA ADA ITEM YG JUMLAHNYA = 0
                $get_data_simpan_item_kosong = $this->item_tersimpan_m->get_by(array('jumlah' => 0));
                foreach ($get_data_simpan_item_kosong as $data_kosong) 
                {
                    $this->item_tersimpan_m->delete_simpan_item_kosong($data_kosong->simpan_item_id);
                }

                // DELETE SIMPAN_ITEM_IDENTITAS YG JUMLAHNYA = 0
                $get_data_simpan_item_identias_kosong = $this->simpan_item_identitas_m->get_by(array('jumlah' => 0));
                foreach ($get_data_simpan_item_identias_kosong as $data_identitas_kosong) 
                {
                    $this->simpan_item_identitas_m->delete_simpan_item_identitas_kosong($data_identitas_kosong->simpan_item_identitas_id);
                    $this->simpan_item_identitas_detail_m->delete_simpan_item_identitas_detail_kosong($data_identitas_kosong->simpan_item_identitas_id);
                }

                $i++;
            } 
        }
        else
        {
            $data_tindakan_hd_item = array(
                'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                'user_id'                => $this->session->userdata('user_id'),
                'tindakan_hd_id'         => $tindakan_hd_id,
                'item_id'                => $item_id,
                'jumlah'                 => $jumlah,
                '`index`'                  => intval($data_simpan->index) + 1,
                'item_satuan_id'         => $satuan_id,
                'harga_beli'             => 0,
                'harga_jual'             => 0,
                'keterangan'             => $keterangan,
                'transaksi_pemberian_id' => $simpan_item_history_id,
                'tipe_pemberian'         => 2,
                'is_active'              => 1
            );
            $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);

            $data_simpan_item_history_detail = array
            (
                'simpan_item_history_id' => $simpan_item_history_id,
                'pasien_id'              => $pasien_id,
                'item_id'                => $item_id,
                'item_satuan_id'         => $satuan_id,
                'initial_stock'          => $stock,
                'change_stock'           => '-'.$jumlah,
                'final_stock'            => (intval($stock) - intval($jumlah)),
                'harga_beli'             => 0,
                'harga_jual'             => 0,
                'total_harga'            => 0,
            );
            $simpan_item_history_detail_id = $this->simpan_item_history_detail_m->save($data_simpan_item_history_detail);

            // UNTUK MENGUPDATE JUMLAH SIMPAN_ITEM
            $jumlah_simpan_item = $this->item_tersimpan_m->get_by(array('simpan_item_id' => $simpan_item_id));
            $jumlah_simpan = intval($jumlah_simpan_item[0]->jumlah) - intval($jumlah);
            $data_simpan_item = array(
                'jumlah'    => $jumlah_simpan,
            );
            $data_simpan_item_id = $this->item_tersimpan_m->save_simpan_item($data_simpan_item, $simpan_item_id);

             // DELETE SIMPAN_ITEM JIKA ADA ITEM YG JUMLAHNYA = 0
            $get_data_simpan_item_kosong = $this->item_tersimpan_m->get_by(array('jumlah' => 0));
            foreach ($get_data_simpan_item_kosong as $data_kosong) 
            {
                $this->item_tersimpan_m->delete_simpan_item_kosong($data_kosong->simpan_item_id);
            }

        }
        if ($simpan_item_history_id) 
        {
            $flashdata = array(
                "success",
                translate("Item telah tersimpan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }

    }

    public function get_last_diagnose()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->model('klinik_hd/tindakan_hd_diagnosa_history_m');

            $response = new stdClass;
            $response->success = false;

            $pasien_id = $this->input->post('pasien_id');
            $last_hd = $this->tindakan_hd_history_m->get_last_data($pasien_id);

            if(count($last_hd))
            {
                $last_diagnose = $this->tindakan_hd_diagnosa_history_m->get_last_diagnose($last_hd->id);
                if(count($last_diagnose))
                {
                    $response->success = true;
                    $response->rows = $last_diagnose;
                }
            }

            die(json_encode($response));


        }
    }

    public function print_assesment($tindakan_hd_id, $pasien_id)
    {
        if(restriction_function($this->session->userdata('level_id'),'klinik_hd_history_transaksi','print_asses'))
        {
            $this->load->library('mpdf/mpdf.php');

            $form_tindakan_hd            = $this->tindakan_hd_history_m->get($tindakan_hd_id);
            $form_pasien                 = $this->pasien_m->get($pasien_id);
            $form_tindakan_hd_penaksiran = $this->tindakan_hd_penaksiran_history_m->get_by(array('tindakan_hd_id' => $form_tindakan_hd->id));
            $pasien_problem              = $this->pasien_problem_history_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
            $pasien_komplikasi           = $this->pasien_komplikasi_history_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
            $form_hemodialisis_history   = $this->tindakan_hd_history_m->get_hemodialisis_history($pasien_id, $form_tindakan_hd->tanggal)->result();
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
                'form_hemodialisis_history'   => object_to_array($form_hemodialisis_history),
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

    public function show_dokumen_detail()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->model('master/dokumen_m');
            $this->load->model('master/dokumen_detail_m');
            $this->load->model('master/dokumen_detail_tipe_m');

            $dokumen_id = $this->input->post('dok_id');
            $data_dokumen = $this->dokumen_m->get($dokumen_id);
            $data_dokumen_detail = $this->dokumen_detail_m->get_by(array('dokumen_id' =>$dokumen_id));
            $data_dokumen_detail = object_to_array($data_dokumen_detail);


            $show_penjamin = '';
            if($data_dokumen->is_kadaluarsa == 1)
            {
                $expire = '<div class="form-group">
                        <label class="control-label col-md-4">Tanggal Kadaluarsa :';

                if($data_dokumen->is_required == 1)
                {
                    $expire .= '<span class="required" aria-required="true">*</span>';  
                }
                $expire .= '</label>
                            <div class="col-md-8">
                            <div class="input-group date" id="penjamin_dokumen[1][tanggal_kadaluarsa]">
                                <input type="text" class="form-control" id="penjamin_dokumen[1][tanggal_kadaluarsa]" name="penjamin_dokumen[1][tanggal_kadaluarsa]" value=""'; 
                if($data_dokumen->is_required == 1)
                {
                    $expire .= ' required="required" '; 
                }
                $expire .='readonly="" aria-required="true">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>';                 
            }
            else
            {
                $expire = '<input type="hidden" class="form-control" id="penjamin_dokumen[1][tanggal_kadaluarsa]" name="penjamin_dokumen[1][tanggal_kadaluarsa]">';
            }
            $expire .= '<input type="hidden" id="penjamin_dokumen[1][dokumen_id]" name="penjamin_dokumen[1][dokumen_id]" value="'.$data_dokumen->id.'"><input type="hidden" id="penjamin_dokumen[1][is_kadaluarsa]" name="penjamin_dokumen[1][is_kadaluarsa]" value="'.$data_dokumen->is_kadaluarsa.'"><input type="hidden" id="penjamin_dokumen[1][is_required]" name="penjamin_dokumen[1][is_required]" value="'.$data_dokumen->is_required.'"><input type="hidden" id="penjamin_dokumen[1][nama]" name="penjamin_dokumen[1][nama]" value="'.$data_dokumen->nama.'"><input type="hidden" id="penjamin_dokumen[1][tipe_dokumen]" name="penjamin_dokumen[1][tipe_dokumen]" value="'.$data_dokumen->tipe.'">';

            $show_penjamin .= $expire;
            if(count($data_dokumen_detail))
            {
                $detail = '';
                $i = 1;
                $ii = 1;
                $z = 0;
                foreach ($data_dokumen_detail as $data_detail) 
                {
                    $detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][dokumen_id]" value="'.$data_dokumen->id.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][nama_dok]" value="'.$data_dokumen->nama.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'">';

                    if ($data_detail['tipe'] == 1)
                    {
                        $required = '';
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="'.$data_detail['maksimal_karakter'].'" value="">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 2)
                    {
                        $required = '';
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';

                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .= '</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="'.$data_detail['maksimal_karakter'].'" value=""></textarea>

                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 3) 
                    {
                        $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .='</label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="'.$data_detail['maksimal_karakter'].'" value="">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 4) 
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );

                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                        }

                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]', $detail_tipe_option,'', 'class="form-control" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]"')
                                        .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 5)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .= '</label>
                                    <div class="col-md-8"><div class="radio-list">';

                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 6)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8"><div class="checkbox-list">';
                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 7) 
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );

                        $selected = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                        }

                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" multiple="multiple" '.$required.' '.$selected.' ')
                                        .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 8) 
                    {
                        $date = '';
                        
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">
                                    <div class="input-group date" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]">
                                        <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" required="required" readonly="" aria-required="true" '.$required.' value="'.$date.'">
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                        
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 9) 
                    {
                        
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                  <div class="col-md-8">
                                    <div id="upload_dokumen_'.$z.'">
                                        <input type="hidden" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="" '.$required.' />
                                        <span class="btn default btn-file">
                                        <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>    
                                            <input type="file" name="upl" id="upload_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
                                        </span>
                                        <ul class="ul-img">
                                            
                                        </ul>
                                    </div>
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">
                                  </div>';
                        $z++;
                    }
                    
                    $show_penjamin .= '<div class="form-group">'.$detail.'</div><div class="form-group">'.$input.'</div>';
                    $i++;
                    $ii++;
                }
                $show_penjamin .= '</div></div>';
    
            }

            echo $show_penjamin;

        }
    }

    public function show_dokumen_detail_edit()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->model('master/dokumen_m');
            $this->load->model('master/pasien_dokumen_m');
            $this->load->model('master/dokumen_detail_m');
            $this->load->model('master/dokumen_detail_tipe_m');

            $dokumen_id = $this->input->post('dok_id');
            $pasien_id = $this->input->post('pasien_id');
            $pasien_dokumen_id = $this->input->post('pasien_dok_id');
            $data_dokumen = $this->dokumen_m->get($dokumen_id);
            $data_pasien = $this->pasien_m->get($pasien_id);
            $data_pasien_dokumen = $this->pasien_dokumen_m->get($pasien_dokumen_id);
            $data_dokumen_detail = $this->dokumen_detail_m->get_by(array('dokumen_id' =>$dokumen_id));
            $data_dokumen_detail = object_to_array($data_dokumen_detail);

            $tanggal_kadaluarsa = '';

            if(count($data_pasien_dokumen))
            {
                $tanggal_kadaluarsa = date('d-M-Y', strtotime($data_pasien_dokumen->tanggal_kadaluarsa));
            }
            $show_penjamin = '';
            if($data_dokumen->is_kadaluarsa == 1)
            {
                $expire = '<div class="form-group">
                        <label class="control-label col-md-4">Tanggal Kadaluarsa :';

                if($data_dokumen->is_required == 1)
                {
                    $expire .= '<span class="required" aria-required="true">*</span>';  
                }
                $expire .= '</label>
                            <div class="col-md-8">
                            <div class="input-group date" id="penjamin_dokumen[1][tanggal_kadaluarsa]">
                                <input type="text" class="form-control" id="penjamin_dokumen[1][tanggal_kadaluarsa]" name="penjamin_dokumen[1][tanggal_kadaluarsa]" value="'.$tanggal_kadaluarsa.'"'; 
                if($data_dokumen->is_required == 1)
                {
                    $expire .= ' required="required" '; 
                }
                $expire .='readonly="" aria-required="true">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>';                 
            }
            else
            {
                $expire = '<input type="hidden" class="form-control" id="penjamin_dokumen[1][tanggal_kadaluarsa]" name="penjamin_dokumen[1][tanggal_kadaluarsa]">';
            }
            $expire .= '<input type="hidden" id="penjamin_dokumen[1][pasien_dokumen_id]" name="penjamin_dokumen[1][pasien_dokumen_id]" value="'.$pasien_dokumen_id.'"><input type="hidden" id="penjamin_dokumen[1][dokumen_id]" name="penjamin_dokumen[1][dokumen_id]" value="'.$data_dokumen->id.'"><input type="hidden" id="penjamin_dokumen[1][is_kadaluarsa]" name="penjamin_dokumen[1][is_kadaluarsa]" value="'.$data_dokumen->is_kadaluarsa.'"><input type="hidden" id="penjamin_dokumen[1][is_required]" name="penjamin_dokumen[1][is_required]" value="'.$data_dokumen->is_required.'"><input type="hidden" id="penjamin_dokumen[1][nama]" name="penjamin_dokumen[1][nama]" value="'.$data_dokumen->nama.'"><input type="hidden" id="penjamin_dokumen[1][tipe_dokumen]" name="penjamin_dokumen[1][tipe_dokumen]" value="'.$data_dokumen->tipe.'">';

            $show_penjamin .= $expire;
            if(count($data_dokumen_detail))
            {
                $detail = '';
                $i = 1;
                $ii = 1;
                $z = 0;
                foreach ($data_dokumen_detail as $data_detail) 
                {
                    $value = '';
                    $detail_tipe_id = '';
                    $detail_tipe_dokumen = $this->pasien_dokumen_m->get_by_data(array('dokumen_id' => $dokumen_id,'pasien_id' => $pasien_id, 'dokumen_detail_id' => $data_detail['id']));
                    // die(dump($detail_tipe_dokumen));

                    
                    if(count($detail_tipe_dokumen))
                    {
                        $detail_tipe_id = $detail_tipe_dokumen->id;
                        $value = $detail_tipe_dokumen->value;
                    }

                    $detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][dokumen_id]" value="'.$data_dokumen->id.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][nama_dok]" value="'.$data_dokumen->nama.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'">';

                    if ($data_detail['tipe'] == 1)
                    {
                        $required = '';
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 2)
                    {
                        $required = '';
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';

                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .= '</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'"></textarea>

                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 3) 
                    {
                        $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .='</label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 4) 
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );

                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                        }

                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]', $detail_tipe_option,$value, 'class="form-control" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]"')
                                        .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 5)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .= '</label>
                                    <div class="col-md-8"><div class="radio-list">';

                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            if($detail_tipe->value == $value)
                            {
                                $checked = 'checked="checked"';
                            }
                            $input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 6)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8"><div class="checkbox-list">';
                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            if($detail_tipe->value == $value)
                            {
                                $checked = 'checked="checked"';
                            }
                            $input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 7) 
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );

                        $selected = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            if($detail_tipe->value == $value)
                            {
                                $selected = 'selected="selected"';
                            }
                            $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                        }

                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" multiple="multiple" '.$required.' '.$selected.' ')
                                        .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 8) 
                    {
                        $date = '';
                        if($value != '')
                        {
                            $date = date('d-M-Y', strtotime($value));
                        }
                        
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">
                                    <div class="input-group date" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]">
                                        <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" required="required" readonly="" aria-required="true" '.$required.' value="'.$date.'">
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                        
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 9) 
                    {
                        
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $src = '';
                        $image = '';
                        if($value != '')
                        {
                            if($value != 'doc_global/document.png')
                            {
                                $value = $value;
                                $tipe  = ($data_dokumen->tipe == 1)?'pelengkap':'rekam_medis';
                                $src   = $data_pasien->no_member.'/dokumen/'.$tipe.'/'.$value;
                            }

                            $image = '<li class="working">
                                        <div class="thumbnail">
                                            <a class="fancybox-button" title="'.$value.'" href="'.config_item('base_dir').config_item('site_img_pasien').$src.'" data-rel="fancybox-button">
                                                <img src="'.config_item('base_dir').config_item('site_img_pasien').$src.'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                            </a>
                                        </div>
                                    </li>';
                        }

                        $input .= '</label>
                                  <div class="col-md-8">
                                    <div id="upload_dokumen_'.$z.'">
                                        <input type="hidden" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$value.'" '.$required.' />
                                        <span class="btn default btn-file">
                                        <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>    
                                            <input type="file" name="upl" id="upload_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
                                        </span>
                                        <ul class="ul-img">
                                            '.$image.'
                                        </ul>
                                    </div>
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">
                                  </div>';
                        $z++;
                    }
                    
                    $show_penjamin .= '<div class="form-group">'.$detail.'</div><div class="form-group">'.$input.'</div>';
                    $i++;
                    $ii++;
                }
                $show_penjamin .= '</div></div>';
    
            }

            echo $show_penjamin;

        }
    }

    public function check_bed()
    {
        if($this->input->is_ajax_request())
        {
            $bed_id = $this->input->post('bed_id');

            $response = new stdClass;
            $response->success = false;

            $data_bed = $this->bed_m->get($bed_id);
            if($data_bed->status == 1 && $data_bed->status_antrian == 0)
            {
                $response->success = true;
            }elseif($data_bed->status != 1 && $data_bed->status_antrian == 0)
            {
                $response->success = true;
            }
            elseif($data_bed->status != 1 && $data_bed->status_antrian != 0)
            {
                $response->success = false;
                $response->msg = translate('Bed yang dipilih sudah diisi oleh pasien lain', $this->session->userdata('language'));
                $response->title = translate('Informasi', $this->session->userdata('language'));
            }

            die(json_encode($response));
        }
    }

    public function print_dokumen($tindakan_hd_id, $pasien_id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'klinik_hd_transaksi_dokter','print_doc'))
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
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('klinik_hd/Transaksi_dokter/history');
        }
    }

    public function print_persetujuan($tindakan_hd_id, $pasien_id)
    {
        //$this->load->library('mpdf/mpdf.php');

        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/pasien_hubungan_m');
        $this->load->model('master/pasien_hubungan_alamat_m');
        $this->load->model('master/pasien_hubungan_telepon_m');

        $tindakan_hd     = $this->tindakan_hd_m->get($tindakan_hd_id);
        $assesment       = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id), true);
        $pendaftaran     = $this->pendaftaran_tindakan_m->get($tindakan_hd->pendaftaran_tindakan_id);

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

        $this->load->view('klinik_hd/transaksi_dokter/print_persetujuan', $body);
    }

    public function print_persetujuan_real($tindakan_hd_id, $pasien_id)
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

        $tindakan_hd     = $this->tindakan_hd_m->get($tindakan_hd_id);
        $assesment       = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id), true);
        $pendaftaran     = $this->pendaftaran_tindakan_m->get($tindakan_hd->pendaftaran_tindakan_id);

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
        $mpdf->SetJS("<script>
$(document).ready(function(){

    $('#sig_setuju').signature();
    $('#sig_setuju').signature('draw', $('textarea#signature_setuju').val()); 
});
</script>");

        $mpdf->Output('surat_persetujuan_'.$tindakan_hd->no_transaksi.'.pdf', 'I'); 
    }

    public function print_rekmed($id, $pasien_id)
    {

        $this->load->library('mpdf/mpdf.php');

        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');

        $data_tindakan = $this->tindakan_umum_m->get_by(array('id' => $id), true);
        $data_tindakan_diagnosa = $this->tindakan_umum_diagnosa_m->get_by(array('tindakan_umum_id' => $id));
        $data_tindakan_tindakan = $this->tindakan_umum_tindakan_m->get_by(array('tindakan_umum_id' => $id));
        $data_tindakan_resep = $this->tindakan_resep_obat_m->get_by(array('tindakan_id' => $id, 'tipe_tindakan' => 2));
        $data_tindakan_resep_detail = $this->tindakan_resep_obat_detail_m->get_by(array('tindakan_id' => $id, 'tipe_tindakan' => 2));

        $data_pasien = $this->pasien_m->get_by(array('id' => $data_tindakan->pasien_id), true);

        $umur_pasien = date_diff(date_create($data_pasien->tanggal_lahir), date_create($data_tindakan->tanggal))->y.' Tahun ';

        if ($umur_pasien < 1) {
            $umur_pasien = translate('Dibawah 1 Tahun ', $this->session->userdata('language'));
        }

        $body = array(
            'data_tindakan'                 => object_to_array($data_tindakan),
            'data_tindakan_diagnosa'        => object_to_array($data_tindakan_diagnosa),
            'data_tindakan_tindakan'        => object_to_array($data_tindakan_tindakan),
            'data_tindakan_resep_detail'    => object_to_array($data_tindakan_resep_detail),
            'data_pasien'                   => object_to_array($data_pasien),
            'umur_pasien'                   => $umur_pasien,
           
        );

        $mpdf = new mPDF('utf-8','A4', 0, '', 10, 10, 8, 0, 0, 0);
        $mpdf->writeHTML($this->load->view('klinik_hd/transaksi_dokter/print_rekmed', $body, true));

        $mpdf->Output('surat_persetujuan_'.$data_tindakan->nomor_tindakan.'.pdf', 'I'); 
    }

    public function save_resep()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Resep gagal ditambahkan', $this->session->userdata('language'));

            // die(dump($this->input->post()));

            $tindakan_id = $this->input->post('tindakan_hd_id');
            $resep=$this->input->post('resep');
            $cabang_apotik = $this->cabang_m->get_by(array('id' => config_item('apotik_id')), true);
            if(count($resep) != 0){
                if($resep[1]['tindakan_id'] != null){
                    $roman_month = romanic_number(date('m'), true);
                    $max_no = $this->tindakan_resep_obat_m->get_max_no($roman_month)->result_array();

                    if(count($max_no))
                    {
                        $last_nosurat = intval($max_no[0]['max_no']) + 1;
                    }
                    else
                    {
                        $last_nosurat = 1;
                    }

                    $format       = '#RSP#%03d';
                    $no_resep    = sprintf($format, $last_nosurat, 3);
                    $no_resep_new = $no_resep.'#RHS#'.$roman_month.'#'.date('Y');

                    $data_resep = array(
                        'cabang_id'     => $this->session->userdata('cabang_id'),
                        'nomor_resep'   => $no_resep_new,
                        'tindakan_id'   => $tindakan_id,
                        'tipe_tindakan' => 1,
                        'tipe_resep' => 1,
                        'pasien_id'     => $this->input->post('pasienid'),
                        'dokter_id'     => $this->session->userdata('user_id'),
                        'status'        => 1,
                        'is_active'     => 1
                    );
                    $resep_id_lokal = $this->tindakan_resep_obat_m->save($data_resep);

                    // $path_model = 'klinik_hd/tindakan_resep_obat_m';
                    // $resep_id = insert_data_api($data_resep,$cabang_apotik->url,$path_model);
                    // $resep_id = str_replace('"', '', $resep_id);

                }

                foreach($resep as $row){
                    if($row['tindakan_id'] != null){
                        $bawa_pulang = (isset($row['item_bawa']))?$row['item_bawa']:0;

                    
                        $data6 = array(
                            'tindakan_id'            => $tindakan_id,
                            'tipe_tindakan'          => 1,
                            'tindakan_resep_obat_id' => $resep_id_lokal,
                            'tipe_item'              => 1,
                            'item_id'                => $row['tindakan_id'],
                            'jumlah'                 => $row['jumlah'],
                            'satuan_id'              => $row['satuan'],
                            'dosis'                  => $row['item_dosis'],
                            'aturan'                 => $row['item_aturan'],
                            'bawa_pulang'            => $bawa_pulang,
                            'is_active'              => 1
                        );

                        $resep_detail_lokal_id = $this->tindakan_resep_obat_detail_m->save($data6);
                             
                    }    
                }
                
            }

            
           
            $resepmanual=$this->input->post('resepmanual');
            if(count($resepmanual) != 0){
                if($resepmanual[1]['keterangan']!= ''){
                    $roman_month = romanic_number(date('m'), true);
                    $max_no = $this->tindakan_resep_obat_m->get_max_no($roman_month)->result_array();

                    if(count($max_no))
                    {
                        $last_nosurat = intval($max_no[0]['max_no']) + 1;
                    }
                    else
                    {
                        $last_nosurat = 1;
                    }

                    $format       = '#RSP#%03d';
                    $no_resep    = sprintf($format, $last_nosurat, 3);
                    $no_resep_new = $no_resep.'#RHS#'.$roman_month.'#'.date('Y');

                    $data_resep_manual = array(
                        'cabang_id'   => $this->session->userdata('cabang_id'),
                        'nomor_resep'   => $no_resep_new,
                        'tindakan_id'   => $tindakan_id,
                        'tipe_tindakan' => 1,
                        'tipe_resep'    => 0,
                        'pasien_id'     => $this->input->post('pasienid'),
                        'dokter_id'     => $this->session->userdata('user_id'),
                        'status'        => 1,
                        'is_active'     => 1
                    );
                    $resep_manual_lokal_id = $this->tindakan_resep_obat_m->save($data_resep_manual);
                    
                    // $path_model = 'klinik_hd/tindakan_resep_obat_m';
                    // $resep_manual_id = insert_data_api($data_resep_manual,$cabang_apotik->url,$path_model);
                    // $resep_manual_id = str_replace('"', '', $resep_manual_id);
                }
            }
            foreach($resepmanual as $row){
                if($row['keterangan']!= ''){
                    $data7 = array(
                        'cabang_id'   => $this->session->userdata('cabang_id'),
                        'tindakan_id'            => $tindakan_id,
                        'tipe_tindakan'          => 1,
                        'tindakan_resep_obat_id' => $resep_manual_id,
                        'keterangan'             => $row['keterangan'],
                        'is_active'              => 1
                    );
                    $resep_detail_lokal_id = $this->tindakan_resep_obat_manual_m->save($data7);

                    // $path_model = 'klinik_hd/tindakan_resep_obat_manual_m';
                    // $resep_detail_id = insert_data_api($data7,$cabang_apotik->url,$path_model);
                    // $resep_detail_id = str_replace('"', '', $resep_detail_id);   
                }
            }

            if($resep_id_lokal || $resep_manual_lokal_id){
                $response->success = true;
                $response->msg = translate('Resep berhasil ditambahkan', $this->session->userdata('language'));
            }

            die(json_encode($response)); 
        }
    }

    public function get_data_lab()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();

            $response = new stdClass;
            $response->success = false;

            if($array_input['tanggal'] == ''){
                $tanggal = date('Y-m-d');
                $data_hasil_lab = $this->hasil_lab_m->get_last_hasil($tanggal, $array_input['pasien_id'])->row(0);
                $data_hasil_lab = object_to_array($data_hasil_lab);

                $data_hasil_lab_prev = array('tanggal' => '');
                $data_hasil_lab_next = $this->hasil_lab_m->get_last_hasil_next($tanggal, $array_input['pasien_id'])->row(0);
                $data_hasil_lab_next = object_to_array($data_hasil_lab_next);

            }elseif($array_input['tanggal'] != ''){
                $data_hasil_lab = $this->hasil_lab_m->get_by(array('date(tanggal)' => $array_input['tanggal'], $array_input['pasien_id']), true);
                $data_hasil_lab = object_to_array($data_hasil_lab);

                $data_hasil_lab_prev = $this->hasil_lab_m->get_last_hasil($array_input['tanggal'], $array_input['pasien_id'])->row(0);
                $data_hasil_lab_prev = object_to_array($data_hasil_lab_prev);

                $data_hasil_lab_next = $this->hasil_lab_m->get_last_hasil_next($array_input['tanggal'], $array_input['pasien_id'])->row(0);
                $data_hasil_lab_next = object_to_array($data_hasil_lab_next);
            }

            $data_hasil_lab_detail = $this->hasil_lab_detail_m->get_data_detail($data_hasil_lab['id'])->result_array();
            $data_hasil_lab_dokumen = $this->hasil_lab_dokumen_m->get_by(array('hasil_lab_id' => $data_hasil_lab['id']));

            $pasien = $this->pasien_m->get_by(array('id' => $array_input['pasien_id']), true);
            $pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $array_input['pasien_id'], 'is_primary' => 1, 'is_active' => 1), true);
            $pasien_telepon = $this->pasien_telepon_m->get_by(array('pasien_id' => $array_input['pasien_id'], 'is_primary' => 1, 'is_active' => 1), true);

            $response->success = true;
            $response->data_hasil_lab = $data_hasil_lab;
            $response->data_hasil_lab_detail = $data_hasil_lab_detail;
            $response->data_hasil_lab_dokumen = $data_hasil_lab_dokumen;
            $response->pasien = $pasien;
            $response->pasien_alamat = $pasien_alamat;
            $response->pasien_telepon = $pasien_telepon;
            $response->data_hasil_lab_prev = $data_hasil_lab_prev;
            $response->data_hasil_lab_next = $data_hasil_lab_next;

            die(json_encode($response));
        }
    }

    public function get_hasil_lab()
    {
        $array_input = $this->input->post();

        $response = new stdClass;
        $response->success = false;

        $command = $array_input['command'];
        $pasien_id = $array_input['pasien_id'];
        $tanggal = date('Y-m-d', strtotime($array_input['tanggal']));

        if($command == 'now'){
            $hasil_lab = $this->hasil_lab_m->get_last_hasil($tanggal, $pasien_id)->result_array();
        }if($command == 'next'){
            $hasil_lab = $this->hasil_lab_m->get_last_hasil_next($tanggal, $pasien_id)->result_array();
        }if($command == 'prev'){
            $hasil_lab = $this->hasil_lab_m->get_last_hasil_prev($tanggal, $pasien_id)->result_array();
        }


        if(count($hasil_lab) != 0){
            if($hasil_lab[0]['jenis'] == 2){
                $data_hasil_lab_detail = $this->hasil_lab_detail_m->get_data_detail($hasil_lab[0]['id'])->result_array();
            }if($hasil_lab[0]['jenis'] == 1){
                $data_hasil_lab_detail = $this->hasil_lab_detail_m->get_by(array('hasil_lab_id' => $hasil_lab[0]['id']));
            }

            $data_hasil_lab_dokumen = $this->hasil_lab_dokumen_m->get_by(array('hasil_lab_id' => $hasil_lab[0]['id']));

            $response->success = true;
            $response->data_hasil_lab = object_to_array($hasil_lab[0]);
            $response->data_hasil_lab_detail = object_to_array($data_hasil_lab_detail);
            $response->data_hasil_lab_dokumen = object_to_array($data_hasil_lab_dokumen);

        }

        die(json_encode($response));
    }

    public function save_tindakan_lain()
    {
        if($this->input->is_ajax_request()){

            $array_input = $this->input->post();

            //die_dump($array_input);

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Tindakan Lain gagal ditambahkan', $this->session->userdata('language'));

            if(isset($array_input['is_transfusi'])){

                $last_id_transfusi       = $this->tindakan_transfusi_m->get_max_id()->result_array();
                $last_id_transfusi       = intval($last_id_transfusi[0]['max_id'])+1;
                
                $format_id_transfusi     = 'TF-'.date('my').'-%04d';
                $id_transfusi            = sprintf($format_id_transfusi, $last_id_transfusi, 4);

                $data_tindakan_transfusi = array(
                    'id' => $id_transfusi,
                    'cabang_id' => $this->session->userdata('cabang_id'),
                    'pasien_id' => $this->input->post('pasienid'),
                    'tanggal'   => date('Y-m-d'),
                    'shift' => $array_input['shift'],
                    'jumlah_kantong_darah' => $array_input['jumlah_kantong_darah'],
                    'dokter_id' => $this->session->userdata('user_id'),
                    'status'    => 1,
                    'created_by'        => $this->session->userdata('user_id'),
                    'created_date'      => date('Y-m-d')
                );

                $save_tindakan_transfusi = $this->tindakan_transfusi_m->add_data($data_tindakan_transfusi);


                $last_id_ps_tindakan       = $this->pasien_tindakan_m->get_max_id()->result_array();
                $last_id_ps_tindakan       = intval($last_id_ps_tindakan[0]['max_id'])+1;
                
                $format_id          = 'PT-'.date('my').'-%04d';
                $id_tindakan_lain   = sprintf($format_id, $last_id_ps_tindakan, 4);

                $data_pasien_tindakan = array(
                    'id'    => $id_tindakan_lain,
                    'pasien_id'    => $this->input->post('pasienid'),
                    'tindakan_id'    => $id_transfusi,
                    'tipe_tindakan'  => 2,
                    'status' => 1
                );

                $save_ps_tindakan_transfusi = $this->pasien_tindakan_m->add_data($data_pasien_tindakan);

                $tindakan_lain = $array_input['resep_transfusi'];

                if(count($tindakan_lain) != 0){
                    $roman_month = romanic_number(date('m'), true);
                    $max_no = $this->tindakan_resep_obat_m->get_max_no($roman_month)->result_array();

                    if(count($max_no))
                    {
                        $last_nosurat = intval($max_no[0]['max_no']) + 1;
                    }
                    else
                    {
                        $last_nosurat = 1;
                    }

                    $format       = '#RSP#%03d';
                    $no_resep    = sprintf($format, $last_nosurat, 3);
                    $no_resep_new = $no_resep.'#RHS#'.$roman_month.'#'.date('Y');

                    $data_resep = array(
                        'cabang_id'     => $this->session->userdata('cabang_id'),
                        'nomor_resep'   => $no_resep_new,
                        'tindakan_id'   => $id_transfusi,
                        'tipe_tindakan' => 2,
                        'tipe_resep' => 1,
                        'pasien_id'     => $this->input->post('pasienid'),
                        'dokter_id'     => $this->session->userdata('user_id'),
                        'status'        => 1,
                        'is_active'     => 1
                    );
                    $resep_transfusi_id = $this->tindakan_resep_obat_m->save($data_resep);
                    
                }


                foreach ($tindakan_lain as $row_transfusi) {
                    
                    if($row_transfusi['tipe_obat'] == 'obat')
                    {
                        $data6 = array(
                            'tindakan_id'            => $id_transfusi,
                            'tipe_tindakan'          => 2,
                            'tindakan_resep_obat_id' => $resep_transfusi_id,
                            'tipe_item'              => 1,
                            'item_id'                => $row_transfusi['tindakan_id'],
                            'jumlah'                 => $row_transfusi['jumlah'],
                            'satuan_id'              => $row_transfusi['satuan'],
                            'dosis'                  => $row_transfusi['item_dosis'],
                            'aturan'                 => $row_transfusi['item_aturan'],
                            'bawa_pulang'            => 0,
                            'is_active'              => 1
                        );

                        $resep_transfusi_detail_id = $this->tindakan_resep_obat_detail_m->save($data6);
                    }    
                }
            }

            $response->success = true;
            $response->msg = translate('Tindakan Lain berhasil ditambahkan', $this->session->userdata('language'));
            

            die(json_encode($response));
        }
    }

    public function get_antrian()
    {
        if($this->input->is_ajax_request()){


            $id_antrian = $this->input->post('antrian_id');
            $counter = $this->input->post('counter');

            $data_antrian = $this->antrian_pasien_m->get_by(array('id' => $id_antrian), true);

            if($data_antrian->posisi_loket != 3 && $data_antrian->status != 0){
                $data_antrian = $this->antrian_pasien_m->get_by(array('posisi_loket' => 3, 'status' => 0),true);
            }

            $data = array(
                'is_panggil' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            );

            $edit_antrian = $this->antrian_pasien_m->edit_data($data, $data_antrian->id);

            $last_id_panggil       = $this->antrian_pasien_m->get_max_id_panggilan()->result_array();
            $last_id_panggil       = intval($last_id_panggil[0]['max_id'])+1;
            
            $format_id_panggil     = 'PGL-'.date('m').'-'.date('Y').'-%04d';
            $id_antrian_panggil    = sprintf($format_id_panggil, $last_id_panggil, 4);

            $no_urut = $this->antrian_pasien_m->get_max_no_urut_panggil()->result_array();
            $no_urut = intval($no_urut[0]['max_no_urut'])+1;

            $data_antrian_panggil = array(
                'id'    => $id_antrian_panggil,
                'antrian_id'    => $id_antrian,
                'panggilan'    => 'Panggilan untuk pasien '.$data_antrian->nama_pasien.', Ke Ruang Dokter',
                'urutan' => $no_urut
            );

            $save_panggilan = $this->antrian_pasien_m->add_data_panggilan($data_antrian_panggil);


            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);

            $response->success = true;
            $response->file = file_put_contents($file,$jam);

            die(json_encode($response));

        }
    }

    public function tindak_antrian()
    {
        if($this->input->is_ajax_request()){


            $id_antrian = $this->input->post('antrian_id');

            $data_antrian = $this->antrian_pasien_m->get_by(array('id' => $id_antrian), true);

            if($data_antrian->is_panggil == 1){
                $data = array(
                    'status' => 1,
                    'modified_date' => date('Y-m-d H:i:s'),
                );

                $edit_antrian = $this->antrian_pasien_m->edit_data($data, $id_antrian);
                $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
                $date = getDate();
                $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                file_put_contents($file,$jam);

                $response->success = true;
                $response->file = file_put_contents($file,$jam);
            }else{
                $response->success = false;
            }

            die(json_encode($response));

        }
    }

    public function lewati_antrian()
    {
        if($this->input->is_ajax_request()){


            $id_antrian = $this->input->post('antrian_id');

            $data_antrian = $this->antrian_pasien_m->get_by(array('id' => $id_antrian), true);

            $antrian_plus_tiga = $this->antrian_pasien_m->get_data_loket_panggil_tiga(3,($data_antrian->no_urut+4))->result_array();
           // $antrian_plus_tiga = object_to_array($antrian_plus_tiga);

            foreach ($antrian_plus_tiga as $plus_tiga) {
                $update = array(
                    'no_urut' => ($plus_tiga['no_urut'] + 1)
                );

                $edit_antrian_tiga = $this->antrian_pasien_m->edit_data($update, $plus_tiga['id']);

            }

            if($data_antrian->is_panggil == 1){
                $data = array(
                    'status' => 0,
                    'is_panggil' => NULL,
                    'no_urut' => ($data_antrian->no_urut+3),
                    'modified_date' => date('Y-m-d H:i:s'),
                );

                $edit_antrian = $this->antrian_pasien_m->edit_data($data, $id_antrian);

                $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
                $date = getDate();
                $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                file_put_contents($file,$jam);

                $response->success = true;
                $response->file = file_put_contents($file,$jam);
            }else{
                $response->success = false;
            }

            die(json_encode($response));

        }
    }

    public function modal_persetujuan($tindakan_hd_id, $pasien_id)
    {

        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/pasien_hubungan_m');
        $this->load->model('master/pasien_hubungan_alamat_m');
        $this->load->model('master/pasien_hubungan_telepon_m');

        $tindakan_hd     = $this->tindakan_hd_m->get($tindakan_hd_id);
        $assesment       = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id), true);
        $pendaftaran     = $this->pendaftaran_tindakan_m->get($tindakan_hd->pendaftaran_tindakan_id);

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
            'penanggungjawab'     => $penanggungjawab,
        );

         $this->load->view('klinik_hd/transaksi_dokter/tab_transaksi_dokter/signature', $body);
    }

    public function save_persetujuan()
    {
        $array_input = $this->input->post();

        $data_tindakan = array(
            'ttd_setuju'    => $array_input['signature_setuju'],
            'ttd_saksi'    => $array_input['signature_saksi'],
            'ttd_dokter_medis'    => $array_input['signature_dokter_medis'],
            'ttd_saksi2'    => $array_input['signature_saksi2'],
            'ttd_dokter_anastesi'    => $array_input['signature_dokter_anastesi']
        );

        $edit_tindakan = $this->tindakan_hd_m->edit_data($data_tindakan, $array_input['tindakan_hd_id']);

        redirect('klinik_hd/transaksi_dokter');
    }

}

/* End of file transaksi_dokter.php */
/* Location: ./application/controllers/klinik_hd/transaksi_dokter.php */