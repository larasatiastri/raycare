<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_perawat extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '091ba28b4ddd9f8ae8015619c57a6aa3';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/pembayaran_transaksi_m');
        $this->load->model('klinik_hd/tindakan_hd_survey_m');
        $this->load->model('klinik_hd/tindakan_hd_survey_history_m');
        $this->load->model('klinik_hd/tindakan_hd_note_m');
        $this->load->model('klinik_hd/pertanyaan_surey_m');
        $this->load->model('klinik_hd/transaksi_perawat_m');
        $this->load->model('master/transaksi_dokter_m');
        $this->load->model('master/transaksi_dokter4_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/region_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('master/pasien_dok_history_m');
        $this->load->model('klinik_hd/pendaftaran_tindakan_history_m');
        $this->load->model('klinik_hd/tindakan_hd_m');
        $this->load->model('klinik_hd/tindakan_hd_history_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_history_m');
        $this->load->model('klinik_hd/tindakan_hd_diagnosa_m');
        $this->load->model('klinik_hd/tindakan_hd_diagnosa_history_m');
        $this->load->model('klinik_hd/tindakan_hd_visit_m');
        $this->load->model('klinik_hd/tindakan_hd_visit_history_m');
        $this->load->model('klinik_hd/pasien_problem_m');
        $this->load->model('klinik_hd/pasien_problem_history_m');
        $this->load->model('klinik_hd/pasien_komplikasi_m');
        $this->load->model('klinik_hd/pasien_komplikasi_history_m');
        $this->load->model('klinik_hd/bed_m');
        $this->load->model('klinik_hd/sejarah_transaksi_m');
        $this->load->model('klinik_hd/sejarah_item_m');
        $this->load->model('klinik_hd/observasi_m');
        $this->load->model('klinik_hd/observasi_history_m');
        $this->load->model('klinik_hd/item_tersimpan_m');
        $this->load->model('klinik_hd/item_digunakan_m');
        $this->load->model('klinik_hd/tagihan_paket_m');
        $this->load->model('klinik_hd/view_tagihan_paket_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/penjamin_m');
        $this->load->model('klinik_hd/pasien_klaim_m');
        $this->load->model('klinik_hd/paket_m');
        $this->load->model('klinik_hd/obat_m');

        // ITEM
        $this->load->model('klinik_hd/inventory_klinik_m');
        $this->load->model('klinik_hd/tindakan_hd_item_m');
        $this->load->model('klinik_hd/tindakan_hd_item_history_m');
        $this->load->model('klinik_hd/tindakan_hd_paket_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('apotik/pemisahan_item/inventory_identitas_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_history_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_detail_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_detail_history_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_detail_identitas_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_detail_identitas_history_m');

        // INVENTORY/SIMPAN_ITEM
        $this->load->model('klinik_hd/simpan_item_identitas_m');
        $this->load->model('klinik_hd/simpan_item_identitas_detail_m');
        $this->load->model('klinik_hd/simpan_item_history_m');
        $this->load->model('klinik_hd/simpan_item_history_detail_m');
        $this->load->model('klinik_hd/simpan_item_history_identitas_m');
        $this->load->model('klinik_hd/tindakan_hd_item_identitas_m');
        $this->load->model('klinik_hd/simpan_item_history_identitas_detail_m');
        $this->load->model('klinik_hd/tindakan_hd_item_identitas_detail_m');
        $this->load->model('apotik/inventory_api_m');
        $this->load->model('apotik/inventory_m');


        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/item/item_identitas_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_klaim_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('apotik/inventory_m');
        $this->load->model('apotik/permintaan_dialyzer_baru_m');
        // $this->load->model('apotik/inventory_identitas_m');
        $this->load->model('apotik/inventory_identitas_detail_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');

        $this->load->model('klinik_hd/tindakan_hd_tindakan_m');
        $this->load->model('klinik_hd/tindakan_hd_tindakan_lain_m');
        $this->load->model('klinik_hd/paket_item_m');
        $this->load->model('klinik_hd/paket_tindakan_m');
        $this->load->model('master/paket_batch2_m');
        $this->load->model('master/transaksi_dokter3_m');

        $this->load->model('master/laboratorium_klinik_m');
        $this->load->model('master/kategori_pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_detail_m');
        $this->load->model('tindakan/hasil_lab_m');
        $this->load->model('tindakan/hasil_lab_detail_m');
        $this->load->model('tindakan/hasil_lab_dokumen_m');
        $this->load->model('apotik/box_paket/t_box_paket_m');
        $this->load->model('apotik/box_paket/t_box_paket_detail_m'); 

        $this->load->model('reservasi/invoice/draf_invoice_m');   
        $this->load->model('reservasi/invoice/draf_invoice_detail_m');  

        $this->load->model('klinik_umum/pasien_tindakan_m');
        $this->load->model('klinik_umum/tindakan_transfusi_m');
        $this->load->model('klinik_umum/tindakan_transfusi_item_m');
        $this->load->model('klinik_umum/tindakan_transfusi_history_m');
        $this->load->model('klinik_umum/tindakan_transfusi_item_history_m'); 
        $this->load->model('reservasi/antrian/antrian_pasien_m');  
        $this->load->model('klinik_hd/outstanding_upload_dokumen_klaim_m');


    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/transaksi_perawat/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transaksi Perawat', $this->session->userdata('language')), 
            'header'         => translate('Transaksi Perawat', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/transaksi_perawat/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function observasi_dialisis($bed_id, $type, $id_tindakan = null)
    {
        $this->load->model('master/info_alamat_m');
        $id = intval($bed_id);
        $id || redirect(base_Url());


        $bed = $this->bed_m->get($bed_id);
        if($bed->user_edit_id != NULL && $bed->user_edit_id != $this->session->userdata('user_id'))
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Transaksi sedang dibuka oleh perawat lain.", $this->session->userdata("language")),
                "msgTitle" => translate("Informasi", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_perawat');
        }
        $bed_isi = $this->bed_m->get_by(array('user_edit_id' => $this->session->userdata('user_id'), 'id !=' => $bed_id ));
        if(count($bed_isi))
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda belum menyelesaikan observasi dialisis sebelumnya.", $this->session->userdata("language")),
                "msgTitle" => translate("Informasi", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_perawat');
        }
        else
        {
            $assets = array();
            $config = 'assets/klinik_hd/transaksi_perawat/observasi_dialisis';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);

            $form_tindakan = $this->tindakan_hd_m->get($id_tindakan);
            $data_item_resep = $this->tindakan_resep_obat_detail_identitas_m->get_current_data($id_tindakan)->result_array();
            //echo $this->db->last_query();

            $data_bed = array(
                'user_edit_id' => $this->session->userdata('user_id'),
                'status' => 5
            );

            $bed_edit = $this->bed_m->save($data_bed, $bed_id);

            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);
            
            // TAB DATA PASIEN
            $form_pasien      = $this->pasien_m->get($form_tindakan->pasien_id);
            $form_agama       = $this->info_umum_m->get($form_pasien->agama_id);
            $form_goldar      = $this->info_umum_m->get($form_pasien->golongan_darah_id);
            $form_pendidikan  = $this->info_umum_m->get($form_pasien->pendidikan_id);
            $form_pekerjaan   = $this->info_umum_m->get($form_pasien->pekerjaan_id);
            $form_cara_masuk  = $this->info_umum_m->get($form_pasien->cara_masuk_id);
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
            $penyakit_hbsag = $this->pasien_penyakit_m->get_by(array('pasien_id' => $form_pasien->id, 'penyakit_id' => config_item('hbsag_id'), 'is_active' => 1));


            $hbsag = 0;
            if(count($penyakit_hbsag) != 0){
                $hbsag = 1;
            }
            
            // TAB ASSESMENT
            $form_assesment   = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $form_problem     = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $form_komplikasi  = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $id_tindakan));

            $cabang = $this->cabang_m->get(1);
            $result = get_data_sejarah_api($form_pasien->id,$cabang->url);

            $form_tindakan_hd_view            = $this->tindakan_hd_m->get($id_tindakan);
            $form_pasien_view                = $this->pasien_m->get($form_pasien->id);
            $form_tindakan_hd_penaksiran_view = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $pasien_problem_view           = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $pasien_komplikasi_view         = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $form_observasi_view             = $this->observasi_m->get_by_trans_id($id_tindakan)->result_array();
            $form_medicine_view               = $this->tindakan_hd_item_m->get_is_assessment($id_tindakan)->result();

            $pasien_tindakan_transfusi = $this->pasien_tindakan_m->get_by(array('pasien_id' => $form_tindakan->pasien_id, 'tipe_tindakan' => 2), true);
            $pasien_tindakan_cek_lab = $this->pasien_tindakan_m->get_by(array('pasien_id' => $form_tindakan->pasien_id, 'tipe_tindakan' => 3), true);
            // die_dump($this->session);

            $data = array(
                'title'            => config_item('site_name').' | '. translate("Observasi Dialisis", $this->session->userdata("language")), 
                'header'           => translate("Observasi Dialisis", $this->session->userdata("language")), 
                'header_info'      => config_item('site_name'), 
                'breadcrumb'       => TRUE,
                'menus'            => $this->menus,
                'menu_tree'        => $this->menu_tree,
                'css_files'        => $assets['css'],
                'js_files'         => $assets['js'],
                'content_view'     => 'klinik_hd/transaksi_perawat/observasi_dialisis',
                'pk_value'         => $bed_id,
                'flag'             => $type,
                'form_tindakan'    => object_to_array($form_tindakan),
                'data_item_resep'  => count($data_item_resep),
                'form_pasien'      => object_to_array($form_pasien),
                'form_agama'       => object_to_array($form_agama),
                'form_goldar'      => (count($form_goldar) != 0)?object_to_array($form_goldar):'',
                'form_pendidikan'  => object_to_array($form_pendidikan),
                'form_pekerjaan'   => object_to_array($form_pekerjaan),
                'form_cara_masuk'  => object_to_array($form_cara_masuk),
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
                'form_komplikasi'  => object_to_array($form_komplikasi),
                'form_tindakan_hd_view'  => object_to_array($form_tindakan_hd_view),
                'form_pasien_view'  => object_to_array($form_pasien_view),
                'form_tindakan_hd_penaksiran_view'  => object_to_array($form_tindakan_hd_penaksiran_view[0]),
                'pasien_problem_view'  => object_to_array($pasien_problem_view),
                'pasien_komplikasi_view'  => object_to_array($pasien_komplikasi_view),
                'form_observasi_view'  => object_to_array($form_observasi_view),
                'form_medicine_view'  => object_to_array($form_medicine_view),
                'pasien_tindakan_transfusi'  => object_to_array($pasien_tindakan_transfusi),
                'pasien_tindakan_cek_lab'  => object_to_array($pasien_tindakan_cek_lab),
                'data_sejarah'      => $result,
                'hbsag'             => $hbsag

            );

            // Load the view
            $this->load->view('_layout', $data);
            
        }

    }

    public function selesaikan_observasi($bed_id, $type, $id_tindakan = null)
    {
        $this->load->model('master/info_alamat_m');
        $id = intval($bed_id);
        $id || redirect(base_Url());


        $bed = $this->bed_m->get($bed_id);
        if($bed->user_edit_id != NULL && $bed->user_edit_id != $this->session->userdata('user_id'))
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Transaksi sedang dibuka oleh perawat lain.", $this->session->userdata("language")),
                "msgTitle" => translate("Informasi", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_perawat');
        }
        $bed_isi = $this->bed_m->get_by(array('user_edit_id' => $this->session->userdata('user_id'), 'id !=' => $bed_id ));
        if(count($bed_isi))
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda belum menyelesaikan observasi dialisis sebelumnya.", $this->session->userdata("language")),
                "msgTitle" => translate("Informasi", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('klinik_hd/transaksi_perawat');
        }
        else
        {
            $assets = array();
            $config = 'assets/klinik_hd/transaksi_perawat/observasi_dialisis';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);

            $form_tindakan = $this->tindakan_hd_m->get($id_tindakan);
            //echo $this->db->last_query();

            $data_bed = array(
                'user_edit_id' => $this->session->userdata('user_id'),
                'status' => 5
            );

            $bed_edit = $this->bed_m->save($data_bed, $bed_id);

            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);
            
            // TAB DATA PASIEN
            $form_pasien      = $this->pasien_m->get($form_tindakan->pasien_id);
            // TAB ASSESMENT
            $form_assesment   = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $form_pasien_view                = $this->pasien_m->get($form_pasien->id);
            $form_tindakan_hd_penaksiran_view = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $pasien_problem_view           = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $pasien_komplikasi_view         = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $id_tindakan));
            $form_observasi_view             = $this->observasi_m->get_by_trans_id($id_tindakan)->result_array();
            $form_medicine_view               = $this->tindakan_hd_item_m->get_is_assessment($id_tindakan)->result();
            // die_dump($this->session);
            $data_item_resep = $this->tindakan_resep_obat_detail_identitas_m->get_current_data($id_tindakan)->result_array();

            $data = array(
                'title'            => config_item('site_name').' | '. translate("Selesaikan Tindakan", $this->session->userdata("language")), 
                'header'           => translate("Selesaikan Tindakan", $this->session->userdata("language")), 
                'header_info'      => config_item('site_name'), 
                'breadcrumb'       => TRUE,
                'menus'            => $this->menus,
                'menu_tree'        => $this->menu_tree,
                'css_files'        => $assets['css'],
                'js_files'         => $assets['js'],
                'content_view'     => 'klinik_hd/transaksi_perawat/selesaikan_observasi',
                'pk_value'         => $bed_id,
                'flag'             => $type,
                'form_tindakan'    => object_to_array($form_tindakan),
                'form_pasien'      => object_to_array($form_pasien),
                'form_assesment'   => object_to_array($form_assesment),
                'form_pasien_view'  => object_to_array($form_pasien_view),
                'form_tindakan_hd_penaksiran_view'  => object_to_array($form_tindakan_hd_penaksiran_view[0]),
                'pasien_problem_view'  => object_to_array($pasien_problem_view),
                'pasien_komplikasi_view'  => object_to_array($pasien_komplikasi_view),
                'form_observasi_view'  => object_to_array($form_observasi_view),
                'form_medicine_view'  => object_to_array($form_medicine_view),
                'data_item_resep'  => count($data_item_resep),
            );

            // Load the view
            $this->load->view('_layout', $data);
            
        }

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
        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_detail', $data);
    }

    public function modal_pindah($lantai, $bed_id, $shift)
    {
        $data = array(
            'bed_id'    => $bed_id,
            'lantai'    => $lantai,
            'shift'    => $shift,
        );
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_pindah', $data);
    }

    public function modal_switch($lantai, $bed_id, $shift, $id_tindakan)
    {
        $data_tindakan_awal = $this->tindakan_hd_m->get_unprocessed_id($id_tindakan);
        $data_tindakan_tujuan = $this->tindakan_hd_m->get_unprocessed($shift, $id_tindakan);

        $data = array(
            'bed_id'    => $bed_id,
            'lantai'    => $lantai,
            'shift'    => $shift,
            'id_tindakan' => $id_tindakan,
            'tindakan_awal' => $data_tindakan_awal,
            'tindakan_tujuan' => $data_tindakan_tujuan
        );
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_switch', $data);
    }


    public function modal_tolak($lantai, $bed_id, $shift)
    {
        $data = array(
            'bed_id'    => $bed_id,
            'lantai'    => $lantai,
            'shift'    => $shift,
        );
        // $this->load->view('_layout', $data);
        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_tolak', $data);
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
                 '<div class="text-center">'.$action.'</div>'        
            );
            $i++;
        }

        echo json_encode($output);
    }


    public function listing_sejarah_item($pasien_id)
    {        
       
        $result = $this->sejarah_item_m->get_datatable($pasien_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
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
                '<div class="text-center inline-button-table">'.$action.'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
            );
        }

        echo json_encode($output);
    }

    public function listing_observasi($id = null)
    {        

        $result = $this->observasi_m->get_datatable($id);

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
                $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'"   data-id="'.$row['id'].'" name="viewobservasi[]"   class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                            <a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="deleted[]"  data-msg="'.translate('Anda yakin akan menghapus monitoring dialisis ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i></a>';
            }else
            {
                $action = '<a title="'.translate('Restore', $this->session->userdata('language')).'"  name="restore1[]"  data-msg="'.translate('Anda yakin akan menyimpan kembali monitoring dialisis ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn yellow"><i class="fa fa-check"></i></a>';
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
        foreach($records->result_array() as $row)
        {
           
            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/transaksi_dokter/edit_observasi_dialisis/'.$row['id'].'"  name="viewobservasi"   class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                        <a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="deleted[]"   class="btn red"><i class="fa fa-times"></i></a>';
       
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
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>'
            );
        }

        echo json_encode($output);
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
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $action = '';
            if($row['is_active']==1)
            {
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'"   data-id="'.$row['id'].'" name="editobservasi[]" data-toggle="modal" data-target="#modal_dialisis" href="'.base_url().'klinik_hd/transaksi_perawat/editdataobservasi/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_hapus = '<a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="deleted[]"  data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i></a>';
                
                $action = restriction_button($data_edit, $user_level_id, 'klinik_hd_transaksi_perawat', 'edit_dialisis').restriction_button($data_hapus, $user_level_id, 'klinik_hd_transaksi_perawat', 'delete_dialisis');

            }else
            {
                $action = '<a title="'.translate('Restore', $this->session->userdata('language')).'"  name="restore1[]"  data-id="'.$row['id'].'" class="btn yellow"><i class="fa fa-check"></i></a>';
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
            "tmp_value"              => $rows[0]['tmp'],
            "vp_value"               => $rows[0]['vp'],
            "ap_value"               => $rows[0]['ap'],
            "cond_value"             => $rows[0]['cond'],
            "temperature_value"      => $rows[0]['temperature'],
            "keterangan_value"       => $rows[0]['keterangan'],

        );
        $this->load->view('klinik_hd/transaksi_perawat/edit_observasi_dialisis', $body);
        
        // $rows_assesment=object_to_array($rows_assesment);

        // echo json_encode($body);
    }

    public function updateobservasi()
    {
        $data_observasi = $this->observasi_m->get($this->input->post('id_observasi'));

        $data['user_id'] = $this->input->post('userid');
        $data['waktu_pencatatan'] =date("Y-m-d H:i:s", strtotime($this->input->post('jam')));
        $data['tekanan_darah_1'] = $this->input->post('tda');
        $data['tekanan_darah_2'] = $this->input->post('tdb');
        $data['ufg'] = $this->input->post('ufg');
        $data['ufr'] = $this->input->post('ufr');
        $data['ufv'] = $this->input->post('ufv');
        $data['qb'] = $this->input->post('qb');
        $data['tmp'] = $this->input->post('tmp');
        $data['vp'] = $this->input->post('vp');
        $data['ap'] = $this->input->post('ap');
        $data['cond'] = $this->input->post('cond');
        $data['temperature'] = $this->input->post('temperature');
        $data['keterangan'] = $this->input->post('keterangan');
        
        $observasi_id = $this->observasi_m->save($data, $this->input->post('id_observasi'));

        $waktu_awal = $this->observasi_m->get_data_first_time($data_observasi->transaksi_hd_id)->row(0);
        $waktu_awal = date('H:i', strtotime($waktu_awal->waktu_pencatatan));
        $waktu_akhir = $this->observasi_m->get_data_last_time($data_observasi->transaksi_hd_id)->row(0);
        $waktu_akhir = date('H:i', strtotime($waktu_akhir->waktu_pencatatan));

        $data_taksir = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $data_observasi->transaksi_hd_id), true);

        $data_save_penaksiran['waktu'] = $waktu_awal.' - '.$waktu_akhir;

        $waktu = $data_save_penaksiran['waktu'];

        $save_taksir = $this->tindakan_hd_penaksiran_m->save($data_save_penaksiran, $data_taksir->id);

        $flashdata = array(
            "success",
            translate("Observasi sudah diupdate", $this->session->userdata("language")),
            translate("Sukses", $this->session->userdata("language")),
            $waktu
        );

        echo json_encode($flashdata);
    }


    public function simpanobservasi()
    {   
        $observasi = $this->observasi_m->get_by(array('transaksi_hd_id' => $this->input->post('transaksiid')));
        // UNTUK EXAMINATION SUPPORT PRIMING, INITIATION, TERMINATION
        $tindakan_penaksiran_id = $this->input->post('tindakan_penaksiran_id');

        $data_taksir = $this->tindakan_hd_penaksiran_m->get($tindakan_penaksiran_id);

        if(count($observasi) == 0){
            $data_save['waktu'] =  date("H:i", strtotime($this->input->post('jam'))).' - '.date("H:i", strtotime($this->input->post('jam')));
        }else{

            $waktu_assesment = explode('-', $data_taksir->waktu);
            $data_save['waktu'] =  $waktu_assesment[0].'- '.date("H:i", strtotime($this->input->post('jam')));
        }

        $waktu =  $data_save['waktu'];

        $data['transaksi_hd_id']  = $this->input->post('transaksiid');
        $data['user_id']          = $this->input->post('userid');
        $data['waktu_pencatatan'] = date("Y-m-d H:i:s", strtotime($this->input->post('jam')));
        $data['tekanan_darah_1']  = $this->input->post('tda');
        $data['tekanan_darah_2']  = $this->input->post('tdb');
        $data['ufg']              = $this->input->post('ufg');
        $data['ufr']              = $this->input->post('ufr');
        $data['ufv']              = $this->input->post('ufv');
        $data['qb']               = $this->input->post('qb');
        $data['tmp']              = $this->input->post('tmp');
        $data['vp']               = $this->input->post('vp');
        $data['ap']               = $this->input->post('ap');
        $data['cond']             = $this->input->post('cond');
        $data['temperature']      = $this->input->post('temp');
        $data['keterangan']       = $this->input->post('keterangan');
        $data['is_active']        = 1;
        
        $observasi_id = $this->observasi_m->save($data);

        // UNTUK EXAMINATION SUPPORT PRIMING, INITIATION, TERMINATION
        $tindakan_penaksiran_id = $this->input->post('tindakan_penaksiran_id');

        $data_taksir = $this->tindakan_hd_penaksiran_m->get($tindakan_penaksiran_id);

        $priming = '';
        if ($data_taksir->priming == NULL && $data_taksir->initiation == NULL || $data_taksir->priming == '' && $data_taksir->initiation == '') 
        {   
            $data_save['priming']       = $this->session->userdata('nama_lengkap');
            $data_save['initiation']    = $this->session->userdata('nama_lengkap');

            $save_taksir = $this->tindakan_hd_penaksiran_m->save($data_save, $tindakan_penaksiran_id);

            $priming = $this->session->userdata('nama_lengkap');
        }
        
        $data_save['termination'] = $this->session->userdata('nama_lengkap');
        $save_taksir = $this->tindakan_hd_penaksiran_m->save($data_save, $tindakan_penaksiran_id);
        $termination = $this->session->userdata('nama_lengkap');

        if ($observasi_id) 
        {
            $flashdata = array(
                "success",
                translate("Observasi sudah disimpan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language")),
                $priming,
                $termination,
                $waktu
            );
            
            echo json_encode($flashdata);
        }
        
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
                "success",
                translate($msg, $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
    }

    public function listing_item_tersimpan($id = null)
    {        
       
        $result = $this->item_tersimpan_m->get_datatable($id);

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
            if($row['status'] == 1)
            {
                $action='<a title="'.translate('Pakai Item', $this->session->userdata('language')).'"  data-confirm="'.translate('Pakai Item ini?', $this->session->userdata('language')).'" name="pakai[]"  data-id="'.$row['simpan_item_id'].'" class="btn btn-primary"><i class="fa fa-plus"></i></a>';
            }
            elseif($row['status'] == 2)
            {
                $action='<a title="'.translate('Batal Menggunakan Item', $this->session->userdata('language')).'"  data-confirm="'.translate('Batalkan Penggunaan Item ini?', $this->session->userdata('language')).'" name="batal[]"  data-id="'.$row['simpan_item_id'].'"  class="btn red"><i class="fa fa-times"></i></a>';
            }
             
            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
            );
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
        foreach($records->result_array() as $row)
        {
           
            $ispaket = '';
            if($row['is_paket'] == 1)
            {
                $ispaket = $row['namapaket'];
            }else
            {
                $ispaket = '-';
            }

            $output['data'][] = array(
                 
                '<div class="text-center">'.date('d M Y',strtotime($row['tanggal_resep'])).'</div>',
                '<div class="text-center">'.$row['namaitem'].'</div>',
                '<div class="text-center">'.$ispaket.'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
                '<div class="text-center">'.$row['nurse'].'</div>',
                '<div class="text-center">'.$row['nurse'].'</div>'
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
        foreach($records->result_array() as $row)
        {
           
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'"  name="viewtagihanpaket[]" data-id="'.$row['paket_id'].'" data-transaksiid="'.$id.'" data-paketname="'.$row['nama'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';
       
            $tipe = '';
            if($row['tipe'] == 1)
            {
                $tipe = 'Obat';
            } else{
                $tipe = 'Alat Kesehatan';
            }

            $output['data'][] = array(
                
                '<div class="text-center">'.$tipe.'</div>',
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>'
            );
        }

        echo json_encode($output);
    }

    public function listing_view_tagihan_paket($transaksiid=null, $paketid=null)
    {        

        $result = $this->view_tagihan_paket_m->get_datatable($transaksiid, $paketid);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $i=0;
        $records = $result->records;
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


    public function listing_dokumen_pasien($id_pasien=null, $jenis,$flag)
    {       
        if($jenis == 1)
        {
            $tipe_array = array('1');
        }
        elseif($jenis == 2)
        {
            $tipe_array = array('2','3');
        }
        if($flag == 1)
        { 
            $result = $this->transaksi_dokter3_m->get_datatable2($id_pasien, $tipe_array);
        }
        elseif($flag == 2)
        {
            $result = $this->pasien_dok_history_m->get_datatable($id_pasien, $tipe_array);
        }
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        foreach($records->result_array() as $row)
        {
            $action = '';

            $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="update" href="'.base_url().'klinik_hd/transaksi_perawat/lihat_dokumen/'.$row['id'].'" data-target="#ajax_notes2" data-toggle="modal"  class="btn default search-item"><i class="fa fa-search"></i></a>
                         ';
            
            if($row['is_kadaluarsa'] == 1)
            {            
                $tanggal_kadaluarsa = date('d M Y',strtotime($row['tanggal_kadaluarsa']));
            }
            else
            {
                $tanggal_kadaluarsa = '-';
            }
       
            $output['data'][] = array(
                 '<div class="text-left">'.$row['nama'].'</div>',
                 '<div class="text-center">'.$tanggal_kadaluarsa.'</div>',
                 '<div class="text-center">'.$action.'</div>',
                 
            );
        }

        echo json_encode($output);
    }

    public function lihat_dokumen($pasien_dok_id)
    {
        $this->load->model('master/dokumen_m');
        $this->load->model('master/pasien_dokumen_m');
        $data = array(
            'pasien_dokumen' => object_to_array($this->pasien_dokumen_m->get($pasien_dok_id))
        );
        $this->load->view('klinik_hd/transaksi_perawat/lihat_dokumen_pasien', $data);
    }

    public function save()
    {
        $array_input = $this->input->post();
        // die_dump($array_input);

        if ($array_input['command'] === 'add')
        {  
                        
            $data = array(
                'nama'                => $array_input['nama'],
                'keterangan'          => $array_input['keterangan'],
                'is_active'           => '1',
            );
            $save_spesialis_id = $this->spesialis_m->save($data);

            if ($save_spesialis_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Spesialis berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }


            
        }
        
        elseif ($array_input['command'] === 'edit')
        {
            $data = array(
                'nama'              => $array_input['nama'],
                'keterangan'     => $array_input['keterangan'],
                'is_active'         => '1',
            );  
            $save_spesialis_id = $this->spesialis_m->save($data, $array_input['id']);

            // $user_level_menu = $this->menu_user_m->get_data_is_active()->result_array();
            // die_dump($user_level_menu);



            if ($save_spesialis_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data User Level berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/spesialis");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->spesialis_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 4,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->save($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Spesialis telah dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/spesialis");
    }

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
                            $tindakan_id = $this->bed_m->get_bed_pasien($row['id'], $row['shift']);
                            // $tindakan = object_to_array($tindakan_id);

                            $tindakan_row_id = '';
                            // foreach ($tindakan as $tindakan_row) {
                            if(count($tindakan_id))
                            {
                                $tindakan_row_id = $tindakan_id[0]['tindakan_id'];
                                
                            }
                            

                            $data_tindakan_hd = $this->tindakan_hd_m->get_bed_pasien_isi($row['id'], $row['shift']);
                            $nama_pasien = $row['nama'];

                            $point_box = $row['point_box'];
                            $split_str = explode(',', $point_box);

                            $status = '';
                            $color = '#fff';
                            if ($row['status'] == 1) 
                            {
                                $logo = '';
                                $color_note = '';
                                $status = '#44b6ae';//Hijau
                                $data_content = '<div class="kosong">'
                                                    .translate('Belum Digunakan', $this->session->userdata('language')).'
                                                </div>
                                                <a id="pilih" data-id="'.$row['id'].'" class="btn btn-primary" style="display:none;"><i class="fa fa-check"></i> Pilih Bed</a>';

                            } 
                            elseif ($row['status'] == 2) 
                            {
                                $transaksi = $this->tindakan_hd_m->get_data_id($row['id'], $row['shift']);
                                $status = '#f3c200'; //KUNING
                                $logo = '';
                                $color_note = '';
                                if(count($transaksi))
                                {
                                    $nama_pasien = $transaksi->nama_pasien;
                                    $id_tindakan = $transaksi->id;
                                    $pasien_id = $transaksi->id_pasien;

                                    $data_content = '<div class="dipesan">
                                                        <a style="margin-bottom:5px;" id="panggil" data-id="'.$pasien_id.'" data-shift="'.$row['shift'].'" data-lantai="'.$lantai.'" class="btn green"><i class="fa fa-volume-up"></i> Panggil</a>
                                                        <br><a style="margin-bottom:5px;" id="proses" data-id="'.$row['id'].'" data-shift="'.$row['shift'].'" data-lantai="'.$lantai.'" class="btn btn-primary"><i class="fa fa-gears"></i> Proses</a>
                                                        <br><a id="pindah" data-toggle="modal" data-target="#modal_pindah" href="'.base_url().'klinik_hd/transaksi_perawat/modal_pindah/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary"><i class="fa fa-arrows"></i> Pindah Bed</a>
                                                        <br><a id="switch" data-toggle="modal" data-target="#modal_switch" href="'.base_url().'klinik_hd/transaksi_perawat/modal_switch/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'/'.$id_tindakan.'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary"><i class="fa fa-exchange"></i> Tukar Bed</a>
                                                        <br><a id="tolak" data-toggle="modal" data-target="#modal_tolak" href="'.base_url().'klinik_hd/transaksi_perawat/modal_tolak/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-times"></i> Tolak</a>
                                                    </div>
                                                    <div class="pilih_bed_tujuan" style="display:none;">
                                                        Silahkan Pilih Bed Tujuan
                                                    </div>';                                    
                                }
                                else
                                {
                                    $nama_pasien = $row['nama'];
                                    $data_content = '<div class="kosong">'
                                                    .translate('Bed Dibooking', $this->session->userdata('language')).'                                                   
                                                </div>';
                                }

                            } 
                            elseif ($row['status'] == 3) 
                            {
                                $logo = '';
                                $color_note = '';
                                if(count($data_tindakan_hd))
                                {
                                    $nama_pasien = $data_tindakan_hd->nama_pasien;
                                }
                                $data_note = $this->tindakan_hd_note_m->get_by(array('tindakan_hd_id' => $tindakan_row_id, 'status' => 0));
                                $status = '#d91e18';//MERAH
                                $data_content = '<div class="digunakan">';
                                if(count($data_note) != 0)
                                {
                                    $logo = '&#xf071';
                                    $color_note = '#f3c200';
                                    $data_content .= '<a style="margin-bottom:5px;" data-toggle="modal" data-target="#modal_notes_proses" href="'.base_url().'klinik_hd/transaksi_perawat/proses_notes/'.$tindakan_row_id.'/'.$row['id'].'" id="proses_notes" data-id="'.$row['id'].'" class="btn btn-danger"><i class="fa fa-edit"></i> Proses Note</a></br>';
                                }

                                $data_content .= '<a style="margin-bottom:5px;" href="'.base_url().'klinik_hd/transaksi_perawat/observasi_dialisis/'.$row['id'].'/0/'.$tindakan_row_id.'" id="observasi" data-id="'.$row['id'].'" class="btn green"><i class="fa fa-plus-square"></i> Observasi Dialisis</a>
                                                    <br><a id="notes" data-toggle="modal" data-target="#modal_notes" href="'.base_url().'klinik_hd/transaksi_perawat/modal_add_notes/'.$tindakan_row_id.'/'.$row['id'].'" data-tindakan_id="'.$tindakan_row_id.'"data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-info notes"><i class="fa fa-file-text-o"></i> Tambah Note Ganti Shift</a>
                                                    <br><a id="detail" data-toggle="modal" data-target="#modal_detail" href="'.base_url().'klinik_hd/transaksi_perawat/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn default detail"><i class="fa fa-search"></i> Lihat Detail</a>
                                                    <br><a id="selesai" href="'.base_url().'klinik_hd/transaksi_perawat/selesaikan_observasi/'.$row['id'].'/1/'.$tindakan_row_id.'" data-id="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-power-off"></i> Selesaikan Tindakan</a>
                                                </div>
                                                <div class="pilih_bed_tujuan" style="display:none;">    
                                                    Silahkan Pilih Bed Tujuan
                                                </div>';
                                if ($is_user) 
                                {
                                    $data_content = '<a id="detail" data-toggle="modal" data-target="#modal_detail" href="'.base_url().'klinik_hd/transaksi_perawat/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" class="btn default detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }

                            } elseif ($row['status'] == 4)
                            {
                                $logo = '';
                                $color_note = '';
                                $status = '#95a5a6';//ABU-ABU
                                $data_content = 'Maintenance';

                            } elseif ($row['status'] == 5)
                            {   
                                $logo = '';
                                $color_note = '';
                                if(count($data_tindakan_hd))
                                {
                                    $nama_pasien = $data_tindakan_hd->nama_pasien;
                                }
                                $tutup_dialisis = '';
                                if ($row['user_edit_id'] == $this->session->userdata('user_id')) 
                                {
                                    $tutup_dialisis = '<a style="margin-bottom:5px;" href="'.base_url().'klinik_hd/transaksi_perawat/observasi_dialisis/'.$row['id'].'/0/'.$tindakan_row_id.'" id="observasi" data-id="'.$row['id'].'" class="btn btn-primary"><i class="fa fa-plus-square"></i> Lanjutkan Observasi Dialisis</a><br>
                                        <a id="tutup_dialisis" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn red-intense detail"><i class="fa fa-times"></i> Tutup Dialisis</a><br>';
                                }
                                $color = '#fff';
                                $status = '#2c3e50';//Hitam
                                $data_content = $tutup_dialisis.'<a id="detail" data-toggle="modal" data-target="#modal_detail" href="'.base_url().'klinik_hd/transaksi_perawat/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" class="btn default detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                            }

                            $data_content .= '<script> 
                                                $(document).ready(function() 
                                                {
                                                    baseAppUrl = mb.baseUrl() + "klinik_hd/transaksi_perawat/";

                                                    $("a#panggil").click(function() {
                                                        var pasien_id = $(this).data("id");

                                                        $.ajax ({ 
                                                            type: "POST",
                                                            url: baseAppUrl + "get_antrian",  
                                                            data:  {pasien_id:pasien_id},  
                                                            dataType : "json",
                                                            beforeSend : function(){
                                                                Metronic.blockUI({boxed: true });
                                                            },
                                                            success:function(data)         
                                                            { 
                                                                
                                                            },
                                                            complete : function() {
                                                                Metronic.unblockUI();
                                                            }
                                                        });

                                                        $(".popover_menu").popover("hide");
                                                    }); 
                                                    
                                                    $("a#proses").click(function() {
                                                        var id = $(this).data("id");
                                                        var shift = $(this).data("shift");
                                                        var lantai = $(this).data("lantai");

                                                        $.ajax ({ 
                                                            type: "POST",
                                                            url: baseAppUrl + "proses_bed",  
                                                            data:  {id:id,shift:shift },  
                                                            dataType : "json",
                                                            beforeSend : function(){
                                                                Metronic.blockUI({boxed: true });
                                                            },
                                                            success:function(data)         
                                                            { 
                                                                $.ajax ({ 
                                                                    type: "POST",
                                                                    url: baseAppUrl + "show_denah_lantai_html",  
                                                                    data: {lantai: "'.$lantai.'"}, 
                                                                    dataType : "text",
                                                                    success:function(data2)         
                                                                    { 
                                                                        $("div.svg_file_lantai_'.$lantai.'").html(data2);
                                                                        mb.showToast(data[0],data[1],data[2]);
                                                                    },
                                                                });
                                                            },
                                                            complete : function() {
                                                                Metronic.unblockUI();
                                                            }
                                                        });

                                                        $(".popover_menu").popover("hide");
                                                    }); 

                                                    
                                                    $("a#tutup_dialisis").click(function() {
                                                        var id = $(this).data("id");

                                                        $.ajax ({ 
                                                            type: "POST",
                                                            url: baseAppUrl + "set_observasi",  
                                                            data:  {id:id, type:1},  
                                                            dataType : "json",
                                                            beforeSend : function(){
                                                                Metronic.blockUI({boxed: true });
                                                            },
                                                            success:function(data)         
                                                            { 
                                                                $.ajax ({ 
                                                                    type: "POST",
                                                                    url: baseAppUrl + "show_denah_lantai_html",  
                                                                    data: {lantai: "'.$lantai.'"}, 
                                                                    dataType : "text",
                                                                    success:function(data2)         
                                                                    { 
                                                                        $("div.svg_file_lantai_'.$lantai.'").html(data2);
                                                                        mb.showToast(data[0],data[1],data[2]);

                                                                        $("span#status_open").removeClass("font-red bold");
                                                                        $("span#status_open").html("<label>'.date('d M Y').'</label>");
                                                                    },
                                                                });
                                                            },
                                                            complete : function() {
                                                                Metronic.unblockUI();
                                                            }
                                                        });

                                                        $(".popover_menu").popover("hide");
                                                    }); 

                                                    $("a#tolak").click(function() 
                                                    {
                                                        $(".popover_menu").popover("hide");
                                                    
                                                    }); 

                                                    $("a#pindah").click(function() 
                                                    {
                                                        $(".popover_menu").popover("hide");

                                                    });

                                                    $("a#switch").click(function() 
                                                    {
                                                        $(".popover_menu").popover("hide");

                                                    }); 

                                                    $("a#detail").click(function() 
                                                    {
                                                        $(".popover_menu").popover("hide");

                                                    });

                                                    $("a#notes").click(function() 
                                                    {
                                                        $(".popover_menu").popover("hide");

                                                    });

                                                    $("a#proses_notes").click(function() 
                                                    {
                                                        $(".popover_menu").popover("hide");

                                                    });
                                                    

                                                });
                                            </script>';

                            $denah_html .= "<polygon id='bed_".$i."' class='popover_bed' points='".$row['point']."' data-id='".($i+1)."' data-content='".$data_content."' style='fill:white; opacity:0.1; cursor: hand;'/>";
                            $denah_html .= "<rect id='box_bed_".$i."' class='rectangle' x='".$split_str[0]."' y='".$split_str[1]."' rx='10' ry='10' width='150' height='20' style='fill:".$status.";' />";
                            $multiply = 15 + (10 * strlen($row['kode']));
                           
                            $denah_html .= "<text x='".($split_str[0]+10)."' y='".($split_str[1]+16)."' font-family='arial' font-size='16' font-weight='bold' fill='".$color."' > ".$row['kode']."</text>";
                            $denah_html .= "<text x='".($split_str[0]+$multiply)."' y='".($split_str[1]+16)."' font-family='arial' font-size='12' font-weight='' fill='".$color."' > ". $nama_pasien."</text>";
                            $denah_html .= "<g><text x='".($split_str[0]+$multiply+95)."' y='".($split_str[1]+16)."' font-family='FontAwesome' fill='".$color_note."'>".$logo."</text></g>";
                            
                           

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


    public function proses_bed()
    {
        if($this->input->is_ajax_request())
        {
            $id = $this->input->post('id');
            $shift = $this->input->post('shift');

            // MENGUBAH STATUS BED
    
            // MENGESET PERAWAT ID DAN JAM MULAI JUGA MENGUBAH STATUS
            $id_tindakan = $this->tindakan_hd_m->get_data_id($id,$shift);

            $data_antrian = $this->antrian_pasien_m->get_by(array('pasien_id' => $id_tindakan->pasien_id, 'posisi_loket' => 5, 'date(created_date)' => date('Y-m-d')), true);


            $data_antri_edit = array(
                'status' => 1,
                // 'is_panggil' => 1,
                'modified_date' => date('Y-m-d H:i:s'),
            );

            $edit_antrian = $this->antrian_pasien_m->edit_data($data_antri_edit, $data_antrian->id);

            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);

            $tindakan_lain = $this->tindakan_hd_tindakan_lain_m->get_by(array('tindakan_hd' => $id_tindakan->id));
            $tindakan_lain = object_to_array($tindakan_lain);

            $pasien = $this->pasien_m->get_by(array('id' => $id_tindakan->pasien_id), true);

            if(count($id_tindakan) != 0) 
            {
                if($id_tindakan->perawat_id == NULL || $id_tindakan->perawat_id == '')
                {
                    $data['status'] = 3;
                    $bed_id = $this->bed_m->save($data, $id);

                    $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
                    $date = getDate();
                    $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                    file_put_contents($file,$jam);

                    $data_tindakan['status']     = 2;
                    $data_tindakan['perawat_id'] = $this->session->userdata('user_id');
                    $data_tindakan['jam_mulai']  = date('Y-m-d H:i:s');
                    $tindakan_id = $this->tindakan_hd_m->save($data_tindakan, $id_tindakan->id);
                    

                    $data_penaksiran['priming'] = $this->session->userdata('nama_lengkap');
                    $data_penaksiran['initiation'] = $this->session->userdata('nama_lengkap');
                    $penaksiran = $this->tindakan_hd_penaksiran_m->edit_by_trx_id($data_penaksiran,$id_tindakan->id);

                    $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                    $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                    
                    $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                    $data_draft_tindakan = array(
                        'id'    => $id_draft,
                        'pasien_id'    => $id_tindakan->pasien_id,
                        'tipe'  => 1,
                        'cabang_id'  => $this->session->userdata('cabang_id'),
                        'tipe_pasien'  => 1,
                        'nama_pasien'  => $pasien->nama,
                        'shift'  => $id_tindakan->shift,
                        'user_level_id'  => $this->session->userdata('level_id'),
                        'jenis_invoice' => ($id_tindakan->penjamin_id == 1)?1:2,
                        'status'    => 1,
                        'akomodasi'    => 0,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);


                    $data_pas_tdk = array(
                        'status' => 2
                    );
                    $wheres_pas_tdk = array(
                        'tindakan_id' => $id_tindakan->id,
                        'tipe_tindakan' => 1
                    );

                    $edit_pasien_tindakan = $this->pasien_tindakan_m->update_by($this->session->userdata('user_id'), $data_pas_tdk, $wheres_pas_tdk);

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $id_draft,
                        'tipe_item' => 1,
                        'item_id'   => $id,
                        'nama_tindakan' => 'Paket Hemodialisa',
                        'hpp'   => ($id_tindakan->penjamin_id == 1)?900000:900000,
                        'harga_jual'   => ($id_tindakan->penjamin_id == 1)?900000:900000,
                        'status' => 1,
                        'jumlah' => 1,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);


                    if(count($tindakan_lain) != 0 && $id_tindakan->penjamin_id == 1){

                        foreach($tindakan_lain as $tind_lain) {

                            $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                            $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                            
                            $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                            $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);


                            $data_draft_tindakan_detail = array(
                                'id'    => $id_draft_detail,
                                'draf_invoice_id'    => $id_draft,
                                'tipe_item' => 2,
                                'item_id'   => $tind_lain['tindakan_id'],
                                'nama_tindakan' => $tind_lain['nama_tindakan'],
                                'hpp' => $tind_lain['hpp'],
                                'harga_jual' => $tind_lain['harga_jual'],
                                'status' => 1,
                                'jumlah' => 1,
                                'is_active'    => 0,
                                'created_by'    => $this->session->userdata('user_id'),
                                'created_date'    => date('Y-m-d')
                            );

                            $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                        }
                    }



                    if(count($tindakan_lain) != 0 && $id_tindakan->penjamin_id != 1){
                        $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                        $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                        
                        $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                        $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                        $data_draft_tindakan = array(
                            'id'    => $id_draft,
                            'pasien_id'    => $id_tindakan->pasien_id,
                            'tipe'  => 2,
                            'cabang_id'  => $this->session->userdata('cabang_id'),
                            'tipe_pasien'  => 1,
                            'nama_pasien'  => $pasien->nama,
                            'shift'  => $id_tindakan->shift,
                            'user_level_id'  => $this->session->userdata('level_id'),
                            'jenis_invoice' => 1,
                            'status'    => 1,
                            'akomodasi' => 0,
                            'is_active'    => 1,
                            'created_by'    => $this->session->userdata('user_id'),
                            'created_date'    => date('Y-m-d')
                        );

                        $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);

                        foreach($tindakan_lain as $tind_lain) {

                            $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                            $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                            
                            $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                            $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);


                            $data_draft_tindakan_detail = array(
                                'id'    => $id_draft_detail,
                                'draf_invoice_id'    => $id_draft,
                                'tipe_item' => 2,
                                'item_id'   => $tind_lain['tindakan_id'],
                                'nama_tindakan' => $tind_lain['nama_tindakan'],
                                'hpp' => $tind_lain['hpp'],
                                'harga_jual' => $tind_lain['harga_jual'],
                                'status' => 1,
                                'jumlah' => 1,
                                'is_active'    => 0,
                                'created_by'    => $this->session->userdata('user_id'),
                                'created_date'    => date('Y-m-d')
                            );

                            $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                        }
                    }



                  
                    $flashdata = array(
                        "success",
                        translate("Bed Telah di Set", $this->session->userdata("language")),
                        translate("Sukses", $this->session->userdata("language"))
                    );
                    
                    echo json_encode($flashdata);
                    


                }
                else
                {
                    $flashdata = array(
                        "error",
                        translate("Transaksi sudah diproses oleh perawat lain", $this->session->userdata("language")),
                        translate("Informasi", $this->session->userdata("language"))
                    );
                    
                    echo json_encode($flashdata);
                }
            }
            elseif(count($id_tindakan) == 0) 
            {
                $flashdata = array(
                    "error",
                    translate("Dokter belum menyelesaikan transaksi", $this->session->userdata("language")),
                    translate("Informasi", $this->session->userdata("language"))
                );
                
                echo json_encode($flashdata);
            }



            // utk sementara operator yg asli &&

        }
        
    }

    public function tolak_bed(){

        if($this->input->is_ajax_request())
        {
            $id_bed     = $this->input->post('id');
            $keterangan = $this->input->post('keterangan');
            $lantai     = $this->input->post('lantai');

            
            // MERUBAH STATUS TINDAKAN_HD MENJADI DITOLAK
            $id_tindakan = $this->tindakan_hd_m->get_by(array('bed_id' => $id_bed, 'status' => 1), true);
            if ($id_tindakan) 
            {
                $data_tindakan['status'] = 4;
                $data_tindakan['keterangan_tolak'] = $keterangan;
                $tindakan_id = $this->tindakan_hd_m->save($data_tindakan, $id_tindakan->id);

                $data_bed = array(
                    'status' => 1
                );
                $edit_bed = $this->bed_m->save($data_bed, $id_bed);
            }
            
            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);

            // utk sementara operator yg asli &&
            if ($tindakan_id) 
            {
                $flashdata = array(
                    "success",
                    translate("Tindakan Ditolak", $this->session->userdata("language")),
                    translate("Sukses", $this->session->userdata("language")),
                    $lantai
                );
                
                echo json_encode($flashdata);
            }
        }
    }

    public function pindah_bed(){

        $bed_asal = $this->input->post('bed_asal');
        $bed_tujuan = $this->input->post('bed_tujuan');
        $lantai = $this->input->post('lantai');
        $shift = $this->input->post('shift');

        $data_bed_asal = $this->bed_m->get_by(array('id' => $bed_asal), true);
        $data_bed_tujuan = $this->bed_m->get_by(array('id' => $bed_tujuan), true);

        if($data_bed_asal->status != 3 && $data_bed_tujuan->status != 3){
            // MERUBAH STATUS BED ID ASAL DAN TUJUAN
            $data_asal['status'] = 1;
            $data_asal['shift'] = $shift;
            
            $bed_asal_id = $this->bed_m->save($data_asal, $bed_asal);

            $data_tujuan['status'] = 2;
            $data_tujuan['shift'] = $shift;
            $bed_tujuan_id = $this->bed_m->save($data_tujuan, $bed_tujuan);
            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);

            // MERUBAH BED ID TINDAKAN_HD MENJADI BED ID TUJUAN
            $id_tindakan = $this->tindakan_hd_m->get_data_id($bed_asal, $shift);
            if ($id_tindakan) 
            {
                $data_tindakan['bed_id'] = $bed_tujuan;
                $tindakan_id = $this->tindakan_hd_m->save($data_tindakan, $id_tindakan->id);

                $assesment = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $id_tindakan->id), true);
                if($assesment)
                {
                    $data_bed = $this->bed_m->get($bed_tujuan);
                    $data_assesment['machine_no'] = $data_bed->kode;
                    $assesment_id = $this->tindakan_hd_penaksiran_m->save($data_assesment, $assesment->id);
                }
            }

            if ($bed_asal_id || $bed_tujuan_id || $tindakan_id) 
            {
                $flashdata = array(
                    "success",
                    translate("Bed Telah Dipindahkan", $this->session->userdata("language")),
                    translate("Sukses", $this->session->userdata("language")),
                    $lantai
                );
                
            }

        }elseif($data_bed_asal->status == 3 || $data_bed_tujuan->status == 3){
            $flashdata = array(
                "error",
                translate("Bed sudah diproses oleh perawat lain", $this->session->userdata("language")),
                translate("Error", $this->session->userdata("language")),
                $lantai
            );
        }

        echo json_encode($flashdata);

    }

    public function tukar_bed(){

        $user_id = $this->session->userdata('user_id');
        $id_tindakan_awal = $this->input->post('id_asal');
        $id_bed_awal = $this->input->post('id_bed_awal');
        $bed_awal = $this->bed_m->get($id_bed_awal);

        $id_tujuan = $this->input->post('id_tujuan');
        $id_tujuan = explode('-', $id_tujuan);
        $id_tindakan_tuju = $id_tujuan[0];
        $id_bed_tuju = $id_tujuan[1];
        $bed_tuju = $this->bed_m->get($id_bed_tuju);

        $lantai = $this->input->post('lantai');
        $shift = $this->input->post('shift');

        if($bed_awal->status != 3 && $bed_tuju->status != 3){
            // MERUBAH STATUS BED ID ASAL DAN TUJUAN
            $data_asal['bed_id'] = $id_bed_tuju;
            $tindakan_asal_id = $this->tindakan_hd_m->save($data_asal, $id_tindakan_awal);

            $data_assesment_asal['machine_no'] = $bed_tuju->kode;
            $wheres_asses_awal['tindakan_hd_id'] = $id_tindakan_awal;
            $update_awal = $this->tindakan_hd_penaksiran_m->update_by($user_id,$data_assesment_asal,$wheres_asses_awal);


            $data_tujuan['bed_id'] = $id_bed_awal;
            $tindakan_tujuan_id = $this->tindakan_hd_m->save($data_tujuan, $id_tindakan_tuju);

            $data_assesment_tuju['machine_no'] = $bed_awal->kode;
            $wheres_asses_tuju['tindakan_hd_id'] = $id_tindakan_tuju;
            $update_tuju = $this->tindakan_hd_penaksiran_m->update_by($user_id,$data_assesment_tuju,$wheres_asses_tuju);

            $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
            $date = getDate();
            $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
            file_put_contents($file,$jam);


            // utk sementara operator yg asli &&
            if ($tindakan_asal_id || $tindakan_tujuan_id) 
            {
                $flashdata = array(
                    "success",
                    translate("Bed Telah Ditukar", $this->session->userdata("language")),
                    translate("Sukses", $this->session->userdata("language")),
                    $lantai
                );
            }
        }if($bed_awal->status != 3 || $bed_tuju->status != 3){
            
            $flashdata = array(
                "error",
                translate("Bed sudah diproses oleh perawat lain", $this->session->userdata("language")),
                translate("Error", $this->session->userdata("language")),
                $lantai
            );
        }

        echo json_encode($flashdata);
    }
    public function set_observasi()
    {
        $bed_id = $this->input->post('id');
        $type = $this->input->post('type');

        $table_bed = $this->bed_m->get_by(array('id' => $bed_id), true);
        $status_bed = $table_bed->status;

        if($status_bed != 1){
            if ($type) 
            {
                $data_bed['user_edit_id'] = null;
                $data_bed['status']       = 3;
            } 
            else {

                $data_bed['user_edit_id'] = $this->session->userdata('user_id');
                $data_bed['status']       = 5;
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
                    translate("Observasi dialisis telah ditutup", $this->session->userdata("language")),
                    translate("Sukses", $this->session->userdata("language")),
                );
                
                echo json_encode($flashdata);
            }
        }elseif($status_bed == 1){
            $flashdata = array(
                "success",
                translate("Observasi dialisis telah ditutup", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language")),
            );
            
            echo json_encode($flashdata);
        }
        
    }

    public function get_lantai(){

        $lantai = $this->input->post('lantai');

        $bed = $this->bed_m->get_by(array('lantai_id' => $lantai, 'status' => 1));
        $bed = object_to_array($bed);

        echo json_encode($bed);
    }

    public function detail_bed(){

        $bed_id = $this->input->post('id');

        $bed = $this->bed_m->get_bed_pasien($bed_id);
        $bed = object_to_array($bed);

        // die_dump($this->db->last_query());

        echo json_encode($bed);
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
            // die(dump($rows_assesment));
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

    public function simpan_assesment()
    {
        $this->load->model('apotik/inventory_api_m');
        $this->load->model('apotik/inventory_api_history_m');
        $this->load->model('apotik/inventory_api_history_detail_m');
        $id_tindakan_penaksiran = $this->input->post('id_tindakan_penaksiran');
        
        $assesment = $this->tindakan_hd_penaksiran_m->get($id_tindakan_penaksiran);

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
        $bn_heparin_1           = $this->input->post('bn_heparin_1');        
        $bn_heparin_2           = $this->input->post('bn_heparin_2');        
        $dose                   = $this->input->post('dose');
        $first                  = $this->input->post('first');
        $maintenance            = $this->input->post('maintenance');
        $hour                   = $this->input->post('hour');
        
        $machine_no             = $this->input->post('machine_no');
        $new                    = $this->input->post('new_');
        $reuse                  = $this->input->post('reuse');
        $dialyzer               = $this->input->post('dialyzer');
        $dialyzer_id            = $this->input->post('id_dializer');
        $av_shunt               = $this->input->post('av_shunt');
        $femoral                = $this->input->post('femoral');
        $double_lument          = $this->input->post('double_lument');
        $bicarbonate            = $this->input->post('bicarbonate');
        $reason            = $this->input->post('reason');

        $item_id        = $this->input->post('id_dializer');
        $tindakan_hd_id = $assesment->tindakan_hd_id;//ambil dari assesment
        $pasien_id      = $assesment->pasien_id;//ambil dari assesment
        $tipe           = ($new == 1)?1:2;
        $value          = $this->input->post('value');
        $kode_dialyzer          = $this->input->post('kode_dialyzer');
        $bed_id          = $this->input->post('bed_id');

        $user_id = $this->session->userdata('user_id');

        $data_bed = $this->bed_m->get($bed_id);
        // $gudang_id = ($data_bed->lantai_id) + 1;
        $gudang_id = 'WH-05-2016-00'.($data_bed->lantai_id + 2);

        $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $item_id, 'is_primary' => 1), true);

        // $harga_item = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_primary->id)->result_array();
        $harga_item = $this->item_satuan_m->get_by(array('id' => $satuan_primary->id), true);

        $cabang_klinik = $this->cabang_m->get_by(array('id' => $this->session->userdata('cabang_id')));
        $cabang_apotik = $this->cabang_m->get_by(array('tipe' => 4, 'is_active' => 1));
        // die(dump($harga_item->harga));
        if($tipe == 1){
            
            $data_permintaan = $this->permintaan_dialyzer_baru_m->get_by(array('tindakan_id' => $tindakan_hd_id, 'pasien_id' => $pasien_id, 'date(created_date)' => date('Y-m-d')));
            if(count($data_permintaan) == 0){
                $last_id       = $this->permintaan_dialyzer_baru_m->get_max_id_permintaan()->result_array();
                $last_id       = intval($last_id[0]['max_id'])+1;
                
                $format_id     = 'DB-'.date('m').'-'.date('Y').'-%04d';
                $id_permintaan = sprintf($format_id, $last_id, 4);
                
                $format        = '#DB#%04d/RHS/'.romanic_number(date('m'), true).'/'.date('Y');
                $no_permintaan = sprintf($format, $last_id, 4);

                $array_permintaan = array(
                    'id'            => $id_permintaan,
                    'cabang_id'     => $this->session->userdata('cabang_id'),
                    'no_permintaan' => $no_permintaan,
                    'pasien_id'     => $pasien_id,
                    'tindakan_id'   => $tindakan_hd_id,
                    'reason'        => $reason,
                    'status'        => 1,
                    'is_active'     => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s')
                );
                
                $save_permintaan = $this->permintaan_dialyzer_baru_m->add_data($array_permintaan);

                // $path_model = 'apotik/permintaan_dialyzer_baru_m';
                // foreach ($cabang_klinik as $klinik) {
                //     $save_permintaan = insert_data_api_id($array_permintaan, $klinik->url, $path_model);
                // }
                // foreach ($cabang_apotik as $apotik) {
                //     $save_permintaan = insert_data_api_id($array_permintaan, $apotik->url, $path_model);
                // }

            }

        }
        if($tipe == 2){
            $item_tersimpan = $this->item_tersimpan_m->get_by(array('pasien_id' => $pasien_id, 'item_id' => $item_id, 'kode_dialyzer' => $kode_dialyzer),true);
            $item_tersimpan = object_to_array($item_tersimpan);

            $data_tindakan_item = $this->tindakan_hd_item_m->get_by(array('item_id'=> $item_id, 'tindakan_hd_id' => $tindakan_hd_id, 'kode_dialyzer' => $kode_dialyzer, 'tipe_pemberian' => 2));

            $data_tindakan_item_new = $this->tindakan_hd_item_m->get_item_dialyzer_new($tindakan_hd_id);
            if(count($data_tindakan_item_new) != 0){
                $data_new['is_active'] = 0;
                $this->tindakan_hd_item_m->save($data_new, $data_tindakan_item_new[0]['id']);
            }

            // SAVE SIMPAN_ITEM_HISTORY
            $data_simpan_item_history = array(
                'transaksi_id'   => $tindakan_hd_id,
                'transaksi_tipe' => 1
            );
            $simpan_item_history_id = $this->simpan_item_history_m->save($data_simpan_item_history);

            if(count($data_tindakan_item) == 0){
                $data_hd_item = array(
                    'waktu'                  => date('Y-m-d H:i:s'),
                    'user_id'                => $this->session->userdata('user_id'),
                    'tindakan_hd_id'         => $tindakan_hd_id,
                    'item_id'                => $item_id,
                    'jumlah'                 => 1,
                    'item_satuan_id'         => $satuan_primary->id,
                    'kode_dialyzer'          => $kode_dialyzer,
                    'bn_sn_lot'              => $value,
                    'expire_date'            => date('Y-m-d',strtotime($item_tersimpan['expire_date'])),
                    '`index`'                => $simpan_item->index + 1,
                    'harga_beli'             => $simpan_item->harga_beli,
                    'harga_jual'             => $harga_item->harga,
                    'tipe_pemberian'         => 2,
                    'transaksi_pemberian_id' => $identitas['simpan_item_id'],
                    'is_active'              => 1
                );

                $tindakan_hd_item = $this->tindakan_hd_item_m->save($data_hd_item);

                // SAVE SIMPAN_ITEM_HISTORY_DETAIL
                $data_simpan_item_history_detail = array
                (
                    'simpan_item_history_id' => $simpan_item_history_id,
                    'pasien_id'              => $pasien_id,
                    'item_id'                => $item_id,
                    'item_satuan_id'         => $satuan_primary->id,
                    'bn_sn_lot'              => $value,
                    'expire_date'            => date('Y-m-d',strtotime($item_tersimpan['expire_date'])),
                    'initial_stock'          => $simpan_item->jumlah,
                    'change_stock'           => 1,
                    'final_stock'            => $simpan_item->jumlah,
                    'harga_beli'             => $simpan_item->harga_beli,
                    'harga_jual'             => $simpan_item->harga_jual,
                    'total_harga'            => $simpan_item->harga_jual,
                );
                $simpan_item_history_detail_id = $this->simpan_item_history_detail_m->save($data_simpan_item_history_detail);  
            }
        }


        $data_assesment = array(
            'tanggal'           => $tanggal,
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
            'dialyzer_new'      => $new,
            'dialyzer_reuse'    => $reuse,
            'dialyzer'          => $dialyzer,
            'ba_avshunt'        => $av_shunt,
            'ba_femoral'        => $femoral,
            'ba_catheter'       => $double_lument,
            'dialyzer_type'     => $bicarbonate,
            'dialyzer_id'       => $dialyzer_id,
            'bn_dialyzer'       => $value,
            'kode_dialyzer'     => $kode_dialyzer,
            'reason'            => $reason,
            'bn_heparin_1'      => $bn_heparin_1,
            'bn_heparin_2'      => $bn_heparin_2,

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

        // $get_assesment = $this->tindakan_hd_penaksiran_m->get($id_tindakan_penaksiran);
        // $get_assesment = object_to_array($get_assesment);
        // // die_dump($get_assesment);

        // echo json_encode($get_assesment);

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

    public function getpage($flag)
    {
        
        $pasien_id = $this->input->post('pasien_id');
        $tanggal   = date('Y-m-d',strtotime($this->input->post('tanggal')));
        
        // save data
        $result='';
        if($flag=='prev')
        {
            $rows_assesment=$this->sejarah_transaksi_m->getpageprev($pasien_id,$tanggal)->result_array();
        }
        else if($flag=='next')
        {
             $rows_assesment=$this->sejarah_transaksi_m->getpagenext($pasien_id,$tanggal)->result_array();
        }
        else if($flag=='first')
        {
             $rows_assesment=$this->sejarah_transaksi_m->getpagefirst($pasien_id,$tanggal)->result_array();
        }
        else
        {
            $rows_assesment=$this->sejarah_transaksi_m->getpagelast($pasien_id,$tanggal)->result_array();
        }
       
        echo json_encode($this->hemodialisis($rows_assesment));
    }

    public function getproblem()
    {
        $tindakan_id = $this->input->post('tindakan_id');
        $pasien_id   = $this->input->post('pasien_id');
         
        $rows_assesment = $this->sejarah_transaksi_m->getproblem($tindakan_id,$pasien_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);
       
        echo json_encode($rows_assesment);
    }

    public function getkomplikasi()
    {
        
        $tindakan_id = $this->input->post('tindakan_id');
        $pasien_id   = $this->input->post('pasien_id');
       
        $rows_assesment = $this->sejarah_transaksi_m->getkomplikasi($tindakan_id,$pasien_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);

        echo json_encode($rows_assesment);
    }

    public function getnomortransaksi()
    {
        $transaksiid = $this->input->post('transaksiid');
       
        $id = $this->tindakan_hd_m->get($transaksiid);

        $body=array('id' => $id->no_transaksi);
     
        echo json_encode($body);
             
    }

    

    public function hemodialisis($rows_assesment)
    {
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
            'tindakan_hd_id'               => $rows_assesment[0]['tindakan_hd_id']
        );

        return $assesment;
    }  

    public function updatestatus($flag)
    {
        $id = $this->input->post('id');

        if($flag==1)
        {
            $data['status'] = 2;
        }else
        {
            $data['status'] = 1;
        }
        
        $id = $this->item_tersimpan_m->save($data,$id);

        echo json_encode('sukses');
    }

    public function add_monitoring($trans_id, $tindakan_penaksiran_id)
    {
        $rows = $this->observasi_m->get_data_last($trans_id)->result_array();

        if ($rows) 
        {
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
                "tmp_value"              => $rows[0]['tmp'],
                "vp_value"               => $rows[0]['vp'],
                "ap_value"               => $rows[0]['ap'],
                "cond_value"             => $rows[0]['cond'],
                "temperature_value"      => $rows[0]['temperature'],
                "keterangan_value"       => $rows[0]['keterangan'],
                "tindakan_penaksiran_id" => $tindakan_penaksiran_id,
            );
        } 
        else 
        {
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
                "tmp_value"              => '',
                "vp_value"               => '',
                "ap_value"               => '',
                "cond_value"             => '',
                "temperature_value"             => '',
                "keterangan_value"       => '',
                "tindakan_penaksiran_id" => $tindakan_penaksiran_id,
            );
        }

        $this->load->view('klinik_hd/transaksi_perawat/monitoring_dialisis', $body);
    }

    public function setnamapriming()
    {
        $taksir_id = $this->input->post('tindakan_penaksiran_id');

        $data_save['priming'] = $this->session->userdata('nama_lengkap');
        $save_taksir = $this->tindakan_hd_penaksiran_m->save($data_save, $taksir_id);

        if ($save_taksir) 
        {
            $flashdata = array(
                "success",
                translate("Priming telah dirubah", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language")),
                $this->session->userdata('nama_lengkap')
            );
            
            echo json_encode($flashdata);
        }

    }

    public function modal_item_diluar_paket($tindakan_hd_id,$resep_identitas_id)
    {
        $data_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_by(array('id' => $resep_identitas_id), true);
        $data_identitas = object_to_array($data_identitas);
        
        $data = array(
            'data_identitas' => $data_identitas,
            'tindakan_hd_id' => $tindakan_hd_id,
            'resep_identitas_id' => $resep_identitas_id
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_item_diluar_paket2', $data);
    }

    public function modal_item_transfusi($tindakan_transfusi_id,$resep_identitas_id)
    {
        $data_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_by(array('id' => $resep_identitas_id), true);
        $data_identitas = object_to_array($data_identitas);
        
        $data = array(
            'data_identitas' => $data_identitas,
            'tindakan_transfusi_id' => $tindakan_transfusi_id,
            'resep_identitas_id' => $resep_identitas_id
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_item_transfusi', $data);
    }

    public function modal_item_dalam_box($tindakan_hd_id,$t_box_paket_detail_id)
    {
        $data_t_box_paket_detail = $this->t_box_paket_detail_m->get_by(array('id' => $t_box_paket_detail_id), true);
        $data_t_box_paket_detail = object_to_array($data_t_box_paket_detail);
        
        $data = array(
            'data_t_box_paket_detail' => $data_t_box_paket_detail,
            'tindakan_hd_id' => $tindakan_hd_id,
            't_box_paket_detail_id' => $t_box_paket_detail_id
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_item_dalam_box', $data);
    }

    public function modal_tindakan_lain($tindakan_transfusi_id,$tindakan_lain_id)
    {
        $data_tindakan_lain = $this->tindakan_hd_tindakan_lain_m->get_by(array('id' => $tindakan_lain_id), true);
        $data_tindakan_lain = object_to_array($data_tindakan_lain);

        $data_tindakan_hd = $this->tindakan_hd_m->get_by(array('id' => $tindakan_hd_id), true);
        $data_tindakan_hd = object_to_array($data_tindakan_hd);

        $data = array(
            'data_tindakan_lain' => $data_tindakan_lain,
            'tindakan_hd_id' => $tindakan_hd_id,
            'tindakan_lain_id' => $tindakan_lain_id,
            'data_tindakan_hd' => $data_tindakan_hd,
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_tindakan_lain', $data);
    }

    public function save_tindakan_lain()
    {
        $array_input = $this->input->post();

        $data_tindakan_lain = array(
            'status'            => 2,
            'perawat_tindak_id' => $this->session->userdata('user_id'),
            'waktu_tindakan'    => date('Y-m-d H:i:s', strtotime($array_input['waktu_tindakan_lain'])),
            'keterangan'        => $array_input['keterangan_tindakan_lain'],
            'modified_by'       => $this->session->userdata('user_id'),
            'modified_date'     => date('Y-m-d H:i:s')
        );

        $simpan_tindakan_lain = $this->tindakan_hd_tindakan_lain_m->edit_data($data_tindakan_lain, $array_input['tindakan_lain_id']);

        $draf_invoice_swasta = $this->draf_invoice_m->get_by(array('pasien_id' => $array_input['pasien_id'], 'jenis_invoice' => 1), true);
        $draf_invoice_detail_swasta = $this->draf_invoice_detail_m->get_by(array('draf_invoice_id' => $draf_invoice_swasta->id, 'tipe_item' => 2, 'item_id' => $array_input['tindakan_lain_item_id'], 'is_active' => 0), true);
      //  die(dump($this->db->last_query()));

        $data_invoice_swasta = array(
            'is_active'    => 1
        );

        $edit_draf_invoice_detail = $this->draf_invoice_detail_m->edit_data($data_invoice_swasta, $draf_invoice_detail_swasta->id);




        $flashdata = array(
            "success",
            translate("Item diluar paket berhasil ditambahkan", $this->session->userdata("language")),
            translate("Sukses", $this->session->userdata("language"))
        );

        echo json_encode($flashdata);

    }

    public function modal_minta_item($pasien_id,$tindakan_hd_id)
    {
  
        $data = array(
            'pasien_id' => $pasien_id,
            'tindakan_hd_id' => $tindakan_hd_id,
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_minta_item', $data);
    }

    public function listing_item_telah_digunakan($tindakan_id)
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
            if ($row['tipe_pemberian'] == 1) 
            {
                $tipe = 'Paket Hemodialisa';
            } elseif ($row['tipe_pemberian'] == 2) 
            {
                $tipe = "Simpan Item History";
            } else 
            {
                $tipe = "Diluar Paket";
            }
                            
            $action = '<a title="'.translate('Hapus', $this->session->userdata('language')).'"  name="delete[]" data-id="'.$row['id'].'" data-tindakan_id="'.$tindakan_id.'" data-bn="'.$row['bn_sn_lot'].'" data-item_id="'.$row['item_id'].'" class="btn red-intense" ><i class="fa fa-times"></i></a>';
            
            $output['data'][] = array(
                 
                '<div class="text-left">'.date('H:i', strtotime($row['waktu'])).'</div>',
                '<div class="text-left">'.$row['item_nama'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-left">'.$row['jumlah'].'</div>',
                '<div class="text-left">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-left">'.$row['expire_date'].'</div>',
                '<div class="text-left">'.$row['user_nama'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
        }

        echo json_encode($output);
    }


    public function listing_item_diluar_paket2($gudang_id, $kategori = null)
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
        //die(dump($records));
        foreach($records->result_array() as $row)
        {   
            $satuan_primary = $this->inventory_klinik_m->get_satuan_inventori($row['gudang_id'], $row['item_id'])->result_array();

            $item_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1), true);
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan ="'.htmlentities(json_encode($satuan_primary)).'" data-satuan_primary="'.htmlentities(json_encode($item_satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
            
            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['satuan'].'</div>',
                '<div class="text-center">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['expire_date'])).'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );
        }

        echo json_encode($output);
    }

    public function listing_item_diluar_paket($tindakan_hd_id = null, $kategori = null)
    {
        $result = $this->tindakan_resep_obat_detail_identitas_m->get_datatable($tindakan_hd_id, 1);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i = 0;
        foreach($records->result_array() as $row)
        {   

            $item_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1), true);
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" data-satuan_primary="'.htmlentities(json_encode($item_satuan_primary)).'" class="btn btn-primary select" href="'.base_url().'klinik_hd/transaksi_perawat/modal_item_diluar_paket/'.$tindakan_hd_id.'/'.$row['id'].'" data-toggle="modal" data-target="#modal_item_diluar"><i class="fa fa-check"></i></a>
            <a title="'.translate('Batalkan', $this->session->userdata('language')).'"  name="cancel[]"  data-msg="'.translate('Anda yakin akan membatalkan item resep ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn red cancel"><i class="fa fa-times"></i></a>';
            
            $output['data'][] = array(
                 
                '<div class="text-center"><input type="hidden" name="item['.$i.'][item_id]" id="item_'.$id.'_item_id" value="'.$row['item_id'].'" class="form-control">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][bn_sn_lot]" id="item_'.$id.'_bn_sn_lot" value="'.$row['bn_sn_lot'].'" class="form-control"><input type="hidden" name="item['.$i.'][jumlah]" id="item_'.$id.'_jumlah" value="'.$row['jumlah'].'" class="form-control">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][expire_date]" id="item_'.$id.'_expire_date" value="'.$row['expire_date'].'" class="form-control">'.date('d M Y', strtotime($row['expire_date'])).'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );

            $i++;
        }

        echo json_encode($output);
    }
    
    public function listing_item_dalam_box_paket($tindakan_hd_id = null)
    {
        $date = date('Y-m-d');
        $t_box_paket = $this->t_box_paket_m->get_by("tindakan_id = $tindakan_hd_id AND tipe_tindakan = 1 AND status IN (2,3) AND date(tanggal_tindakan) = '$date'");
        $t_box_paket = object_to_array($t_box_paket[0]);

        $result = $this->t_box_paket_detail_m->get_datatable($t_box_paket['id'],date('Y-m-d', strtotime($t_box_paket['created_date'])));
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i = 0;
        foreach($records->result_array() as $row)
        {   
            $action = '';
            $status = '';
            $item_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1), true);
            
            if($row['status'] == NULL){
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" data-satuan_primary="'.htmlentities(json_encode($item_satuan_primary)).'" class="btn btn-primary select" href="'.base_url().'klinik_hd/transaksi_perawat/modal_item_dalam_box/'.$tindakan_hd_id.'/'.$row['id'].'" data-toggle="modal" data-target="#modal_item_box"><i class="fa fa-check"></i></a>';
            
            }

            if($row['status'] == 3){
                $status = '<div class="text-center"><span class="label label-md label-success">Digunakan Pasien Jam '.date('H:i', strtotime($row['tanggal_pakai'])).'</span></div>';
            }else{
                $status = '<div class="text-center"><span class="label label-md label-warning">Belum Digunakan</span></div>';

            }

           
            $output['data'][] = array(
                 
                '<div class="text-center"><input type="hidden" name="item['.$i.'][item_id]" id="item_'.$id.'_item_id" value="'.$row['item_id'].'" class="form-control">'.$row['kode_box_paket'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][item_id]" id="item_'.$id.'_item_id" value="'.$row['item_id'].'" class="form-control">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
                '<div class="text-left"><input type="hidden" name="item['.$i.'][bn_sn_lot]" id="item_'.$id.'_bn_sn_lot" value="'.$row['bn_sn_lot'].'" class="form-control"><input type="hidden" name="item['.$i.'][jumlah]" id="item_'.$id.'_jumlah" value="'.$row['jumlah'].'" class="form-control">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][expire_date]" id="item_'.$id.'_expire_date" value="'.$row['expire_date'].'" class="form-control">'.date('d M Y', strtotime($row['expire_date'])).'</div>',
                $status,
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function listing_tindakan_lain($tindakan_hd_id = null)
    {

        $result = $this->tindakan_hd_tindakan_lain_m->get_datatable($tindakan_hd_id);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i = 0;
        foreach($records->result_array() as $row)
        {   
            $action = '';
            $status = '';
            
            if($row['status'] == 1){
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select" href="'.base_url().'klinik_hd/transaksi_perawat/modal_tindakan_lain/'.$tindakan_hd_id.'/'.$row['id'].'" data-toggle="modal" data-target="#modal_tindakan_lain"><i class="fa fa-check"></i></a>';
                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Ditindak</span></div>';
            }

            if($row['status'] == 2){
                $status = '<div class="text-center"><span class="label label-md label-success">Sudah ditindak Jam '.date('H:i', strtotime($row['waktu_tindakan'])).'</span></div>';
            }
           
            $output['data'][] = array(
                 
                '<div class="text-left">'.$row['nama_tindakan'].'</div>',
                $status,
                '<div class="text-left">'.$row['nama_perawat'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_transfusi_telah_digunakan($tindakan_id)
    {
        $result = $this->tindakan_transfusi_item_m->get_datatable($tindakan_id);
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
            
            $output['data'][] = array(
                 
                '<div class="text-left">'.date('H:i', strtotime($row['waktu'])).'</div>',
                '<div class="text-left">'.$row['item_nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].'</div>',
                '<div class="text-left">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-left">'.$row['expire_date'].'</div>',
                '<div class="text-left">'.$row['user_nama'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
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
        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_inventori_identitas', $data);

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
        $bn_sn_lot = $this->input->post('bn_sn_lot');
        $expire_date = $this->input->post('expire_date');
        // die(dump($inventory_i0pentitas));

        $data_item = $this->item_m->get($item_id);

        
        if($bn_sn_lot != '' && $jumlah != 0 && $jumlah != '') 
        {
            $data_inventory = $this->inventory_m->get_by(array('gudang_id' => $gudang_id, 'item_id' => $item_id,'bn_sn_lot' => $bn_sn_lot, 'expire_date' => date('Y-m-d', strtotime($expire_date)) ));
            $data_inventory = object_to_array($data_inventory);
            // SAVE SIMPAN_ITEM_HISTORY
            $data_inventory_history = array(
                'transaksi_id'   => $tindakan_hd_id,
                'transaksi_tipe' => 5
            );
            $inventory_history_id = $this->inventory_history_m->save($data_inventory_history);

            // $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();
            $harga_jual = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);

            // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG
            $data_tindakan_hd_item = array(
                'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                'user_id'                => $this->session->userdata('user_id'),
                'tindakan_hd_id'         => $tindakan_hd_id,
                'item_id'                => $item_id,
                'jumlah'                 => $jumlah,
                'bn_sn_lot'              => $bn_sn_lot,
                'expire_date'            => date('Y-m-d', strtotime($expire_date)),
                '`index`'                => 1,
                'item_satuan_id'         => $satuan_id,
                'harga_beli'             => $data_inventory['harga_beli'],
                'harga_jual'             => $harga_jual->harga,     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                'keterangan'             => $keterangan,
                'tipe_pemberian'         => 3,
                'is_active'              => 1
            );
            $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);

            $x = 1;
            $sisa = 0;
            foreach ($data_inventory as $row_inv) {
                if($x == 1 && $jumlah >= $row_inv['jumlah']){

                    $sisa = $jumlah - $row_inv['jumlah'];
                    $sisa_inv = 0;

                    // SAVE INVENTORY_HISTORY_DETAIL
                    $data_inventory_history_detail = array
                    (
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $gudang_id,
                        'pmb_id'               => $row_inv['pmb_id'],
                        'item_id'              => $item_id,
                        'item_satuan_id'       => $satuan_id,
                        'initial_stock'        => $row_inv['jumlah'],
                        'change_stock'         => ($row_inv['jumlah'] * (-1)),
                        'final_stock'          => $sisa_inv,
                        'bn_sn_lot'            => $bn_sn_lot,
                        'expire_date'          => date('Y-m-d', strtotime($expire_date)),
                        'harga_beli'           => $row_inv['harga_beli'],
                        'total_harga'          => (intval($row_inv['jumlah'])*intval($row_inv['harga_beli'])),
                    );
                    $inventory_history_detail_id = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                    $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                    
                }
                if($x == 1 && $jumlah < $row_inv['jumlah']){

                    $sisa = 0;
                    $sisa_inv = $row_inv['jumlah'] - $jumlah;

                    $data_inventory_history_detail = array(
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $gudang_id,
                        'pmb_id'               => $row_inv['pmb_id'],
                        'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                        'box_paket_id'         => NULL,
                        'kode_box_paket'       => NULL,
                        'item_id'              => $item_id,
                        'item_satuan_id'       => $satuan_id,
                        'initial_stock'        => $row_inv['jumlah'],
                        'change_stock'         => ($jumlah * (-1)),
                        'harga_beli'           => $row_inv['harga_beli'],
                        'total_harga'          => $jumlah * $row_inv['harga_beli'],
                        'final_stock'          => $sisa_inv,
                        'bn_sn_lot'            => $bn_sn_lot,
                        'expire_date'          => date('Y-m-d', strtotime($expire_date)),
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                    $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                }
                if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                    $sisa = $sisa - $row_inv['jumlah'];
                    $sisa_inv = 0;

                    $data_inventory_history_detail = array(
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $array_input['gudang_ke'],
                        'pmb_id'               => $row_inv['pmb_id'],
                        'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                        'box_paket_id'         => NULL,
                        'kode_box_paket'       => NULL,
                        'item_id'              => $row_inv['item_id'],
                        'item_satuan_id'       => $row_inv['item_satuan_id'],
                        'initial_stock'        => $row_inv['jumlah'],
                        'change_stock'         => ($row_inv['jumlah'] * (-1)),
                        'final_stock'          => $sisa_inv,
                        'harga_beli'           => $row_inv['harga_beli'],
                        'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                        'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                        'expire_date'          => $row_inv['expire_date'],
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                    $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                }

                if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){
                    $sisa_inv = $row_inv['jumlah'] - $sisa;

                    $data_inventory_history_detail = array(
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $array_input['gudang_ke'],
                        'pmb_id'               => $row_inv['pmb_id'],
                        'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                        'box_paket_id'         => NULL,
                        'kode_box_paket'       => NULL,
                        'item_id'              => $row_inv['item_id'],
                        'item_satuan_id'       => $row_inv['item_satuan_id'],
                        'initial_stock'        => $row_inv['jumlah'],
                        'change_stock'         => ($sisa * (-1)),
                        'final_stock'          => $sisa_inv,
                        'harga_beli'           => $row_inv['harga_beli'],
                        'total_harga'          => $sisa * $row_inv['harga_beli'],
                        'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                        'expire_date'          => $row_inv['expire_date'],
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                    $sisa = 0;



                    $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                }

                $x++;
            }
           
            // $data_update_inv = array(
            //     'jumlah' => (intval($data_inventory['jumlah']) - intval($jumlah))
            // );
            // $wheres_inv = array(
            //     'inventory_id' => $data_inventory['inventory_id'] 
            // );

            // $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),$data_update_inv,$wheres_inv);

            // if((intval($data_inventory['jumlah']) - intval($jumlah)) <= 0){
            //     $delete_inv = $this->inventory_m->delete_by($wheres_inv);
            // }
                
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
                    'harga_jual'             => (count($harga_jual) != 0)?$harga_jual->harga:0,     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                    'keterangan'             => $keterangan,
                    'tipe_pemberian'         => 3,
                    'is_active'              => 1
                );
                $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);

                $data_inventory = $this->inventory_m->get_by(array('gudang_id' => $gudang_id, 'item_id' => $item_id, 'item_satuan_id' => $satuan_id));
                $data_inventory = object_to_array($data_inventory);
                
                $x = 1;
                $sisa = 0;
                foreach ($data_inventory as $row_inv) {
                    if($x == 1 && $jumlah >= $row_inv['jumlah']){

                        $sisa = $jumlah - $row_inv['jumlah'];
                        $sisa_inv = 0;

                        // SAVE INVENTORY_HISTORY_DETAIL
                        $data_inventory_history_detail = array
                        (
                            'inventory_history_id' => $inventory_history_id,
                            'gudang_id'            => $gudang_id,
                            'pmb_id'               => $row_inv['pmb_id'],
                            'item_id'              => $item_id,
                            'item_satuan_id'       => $satuan_id,
                            'initial_stock'        => $row_inv['jumlah'],
                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                            'final_stock'          => $sisa_inv,
                            'bn_sn_lot'            => $bn_sn_lot,
                            'expire_date'          => date('Y-m-d', strtotime($expire_date)),
                            'harga_beli'           => $row_inv['harga_beli'],
                            'total_harga'          => (intval($row_inv['jumlah'])*intval($row_inv['harga_beli'])),
                        );
                        $inventory_history_detail_id = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                        
                    }
                    if($x == 1 && $jumlah < $row_inv['jumlah']){

                        $sisa = 0;
                        $sisa_inv = $row_inv['jumlah'] - $jumlah;

                        $data_inventory_history_detail = array(
                            'inventory_history_id' => $inventory_history_id,
                            'gudang_id'            => $gudang_id,
                            'pmb_id'               => $row_inv['pmb_id'],
                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                            'box_paket_id'         => NULL,
                            'kode_box_paket'       => NULL,
                            'item_id'              => $item_id,
                            'item_satuan_id'       => $satuan_id,
                            'initial_stock'        => $row_inv['jumlah'],
                            'change_stock'         => ($jumlah * (-1)),
                            'harga_beli'           => $row_inv['harga_beli'],
                            'total_harga'          => $jumlah * $row_inv['harga_beli'],
                            'final_stock'          => $sisa_inv,
                            'bn_sn_lot'            => $bn_sn_lot,
                            'expire_date'          => date('Y-m-d', strtotime($expire_date)),
                            'created_by'           => $this->session->userdata('user_id'),
                            'created_date'         => date('Y-m-d H:i:s')
                        );

                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                    }
                    if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                        $sisa = $sisa - $row_inv['jumlah'];
                        $sisa_inv = 0;

                        $data_inventory_history_detail = array(
                            'inventory_history_id' => $inventory_history_id,
                            'gudang_id'            => $array_input['gudang_ke'],
                            'pmb_id'               => $row_inv['pmb_id'],
                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                            'box_paket_id'         => NULL,
                            'kode_box_paket'       => NULL,
                            'item_id'              => $row_inv['item_id'],
                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                            'initial_stock'        => $row_inv['jumlah'],
                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                            'final_stock'          => $sisa_inv,
                            'harga_beli'           => $row_inv['harga_beli'],
                            'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                            'expire_date'          => $row_inv['expire_date'],
                            'created_by'           => $this->session->userdata('user_id'),
                            'created_date'         => date('Y-m-d H:i:s')
                        );

                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                    }

                    if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){
                        $sisa_inv = $row_inv['jumlah'] - $sisa;

                        $data_inventory_history_detail = array(
                            'inventory_history_id' => $inventory_history_id,
                            'gudang_id'            => $array_input['gudang_ke'],
                            'pmb_id'               => $row_inv['pmb_id'],
                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                            'box_paket_id'         => NULL,
                            'kode_box_paket'       => NULL,
                            'item_id'              => $row_inv['item_id'],
                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                            'initial_stock'        => $row_inv['jumlah'],
                            'change_stock'         => ($sisa * (-1)),
                            'final_stock'          => $sisa_inv,
                            'harga_beli'           => $row_inv['harga_beli'],
                            'total_harga'          => $sisa * $row_inv['harga_beli'],
                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                            'expire_date'          => $row_inv['expire_date'],
                            'created_by'           => $this->session->userdata('user_id'),
                            'created_date'         => date('Y-m-d H:i:s')
                        );

                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                        $sisa = 0;



                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                    }

                    $x++;
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

    public function save_item_diluar_paket2()
    {
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
        $bn_sn_lot = $this->input->post('bn_sn_lot');
        $expire_date = $this->input->post('expire_date');
        $id_resep_detail_identitas = $this->input->post('id_resep_detail');

        $apotik = $this->cabang_m->get(4);
        // die(dump($inventory_identitas));

        $data_item = $this->item_m->get_by(array('id' => $item_id), true);

        $data_tindakan_hd = $this->tindakan_hd_m->get_by(array('id' => $tindakan_hd_id), true);

        $draf_invoice_swasta = $this->draf_invoice_m->get_by(array('pasien_id' => $data_tindakan_hd->pasien_id, 'jenis_invoice' => 1), true);
        $draf_invoice_bpjs = $this->draf_invoice_m->get_by(array('pasien_id' => $data_tindakan_hd->pasien_id, 'jenis_invoice' => 2), true);
        
        // $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();
        $harga_jual = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);

        $pasien = $this->pasien_m->get_by(array('id' => $data_tindakan_hd->pasien_id),true);

        if($data_tindakan_hd->penjamin_id != 1){

            $data_item_klaim = $this->item_klaim_m->get_by(array('item_id' => $item_id));

            if(count($data_item_klaim)){
                $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                
                $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                $data_draft_tindakan_detail = array(
                    'id'    => $id_draft_detail,
                    'draf_invoice_id'    => $draf_invoice_bpjs->id,
                    'tipe_item' => 3,
                    'item_id'   => $item_id,
                    'jumlah'                 => $jumlah,
                    'nama_tindakan' => $data_item->nama,
                    'harga_jual'             => $harga_jual->harga,
                    'status' => 1,
                    'is_active'    => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')
                );

                $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
            }
            elseif(count($data_item_klaim) == 0){
                if(count($draf_invoice_swasta) == 0){
                    $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                    $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                    
                    $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                    $data_draft_tindakan = array(
                        'id'    => $id_draft,
                        'pasien_id'    => $data_tindakan_hd->pasien_id,
                        'tipe'  => 1,
                        'cabang_id'  => $this->session->userdata('cabang_id'),
                        'tipe_pasien'  => 1,
                        'nama_pasien'  => $pasien->nama,
                        'shift'  => $data_tindakan_hd->shift,
                        'user_level_id'  => $this->session->userdata('level_id'),
                        'jenis_invoice' => 1,
                        'status'    => 1,
                        'is_active'    => 1,
                        'akomodasi'    => 0,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);
                }elseif(count($draf_invoice_swasta) != 0){
                    $id_draft = $draf_invoice_swasta->id;
                }

                $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                
                $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                $data_draft_tindakan_detail = array(
                    'id'    => $id_draft_detail,
                    'draf_invoice_id'    => $id_draft,
                    'tipe_item' => 3,
                    'item_id'   => $item_id,
                    'jumlah'                 => $jumlah,
                    'nama_tindakan' => $data_item->nama,
                    'harga_jual'             => $harga_jual->harga,
                    'status' => 1,
                    'jumlah' => $jumlah,
                    'is_active'    => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')
                );

                $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
            }
        }elseif ($data_tindakan_hd->penjamin_id == 1) {
            if(count($draf_invoice_swasta) == 0){
                $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                
                $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                $data_draft_tindakan = array(
                    'id'    => $id_draft,
                    'pasien_id'    => $data_tindakan_hd->pasien_id,
                    'tipe'  => 1,
                    'cabang_id'  => $this->session->userdata('cabang_id'),
                    'tipe_pasien'  => 1,
                    'nama_pasien'  => $pasien->nama,
                    'shift'  => $data_tindakan_hd->shift,
                    'user_level_id'  => $this->session->userdata('level_id'),
                    'jenis_invoice' => 1,
                    'status'    => 1,
                    'is_active'    => 1,
                    'akomodasi'    => 0,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')
                );

                $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);
            }elseif(count($draf_invoice_swasta) != 0){
                $id_draft = $draf_invoice_swasta->id;
            }

            $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
            $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
            
            $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
            $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

            $data_draft_tindakan_detail = array(
                'id'    => $id_draft_detail,
                'draf_invoice_id'    => $id_draft,
                'tipe_item' => 3,
                'item_id'   => $item_id,
                'jumlah'                 => $jumlah,
                'nama_tindakan' => $data_item->nama,
                'harga_jual'             => $harga_jual->harga,
                'status' => 1,
                'is_active'    => 1,
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s')
            );

            $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
        }

        if($bn_sn_lot != '' && $jumlah != 0 && $jumlah != '') 
        {
            $harga_jual = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);
            // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG
            $data_tindakan_hd_item = array(
                'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                'user_id'                => $this->session->userdata('user_id'),
                'tindakan_hd_id'         => $tindakan_hd_id,
                'item_id'                => $item_id,
                'jumlah'                 => $jumlah,
                'bn_sn_lot'              => $bn_sn_lot,
                'expire_date'            => date('Y-m-d', strtotime($expire_date)),
                '`index`'                => 1,
                'item_satuan_id'         => $satuan_id,
                'harga_beli'             => $data_inventory['harga_beli'],
                'harga_jual'             => $harga_jual->harga,     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                'keterangan'             => $keterangan,
                'tipe_pemberian'         => 3,
                'is_active'              => 1
            );
            $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);  

            $status_resep['status'] = 2;
            $edit_resep_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($status_resep, $id_resep_detail_identitas);

            $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
            $edit_resep_identitas = insert_data_api($status_resep, $apotik->url, $path_model, $id_resep_detail_identitas);

        }
        else
        {
            if($jumlah != 0)
            {
                // $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();
                $harga_jual = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);
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
                    'harga_jual'             => (count($harga_jual) != 0)?$harga_jual->harga:0,     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                    'keterangan'             => $keterangan,
                    'tipe_pemberian'         => 3,
                    'is_active'              => 1
                );
                $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);     

                $status_resep['status'] = 2;
                $edit_resep_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($status_resep, $id_resep_detail_identitas); 

                $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                $edit_resep_identitas = insert_data_api($status_resep, $apotik->url, $path_model, $id_resep_detail_identitas);
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
    
    public function save_item_transfusi()
    {
        $waktu               = $this->input->post('waktu_diluar_paket');
        $tindakan_transfusi_id      = $this->input->post('tindakan_transfusi_id');
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
        $bn_sn_lot = $this->input->post('bn_sn_lot');
        $expire_date = $this->input->post('expire_date');
        $id_resep_detail_identitas = $this->input->post('id_resep_detail');

        $apotik = $this->cabang_m->get(4);
        // die(dump($inventory_identitas));

        $data_tindakan_transfusi = $this->tindakan_transfusi_m->get_by(array('id' => $tindakan_transfusi_id), true);

        $data_item = $this->item_m->get($item_id);

        // $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();
        $harga_jual = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);

        $pasien = $this->pasien_m->get_by(array('id' => $data_tindakan_transfusi->pasien_id), true);
        
        if($bn_sn_lot != '' && $jumlah != 0 && $jumlah != '') 
        {
            // $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();
            // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG

            $last_id_transfusi_item       = $this->tindakan_transfusi_item_m->get_max_id()->result_array();
            $last_id_transfusi_item       = intval($last_id_transfusi_item[0]['max_id'])+1;
            
            $format_id_transfusi_transfusi     = 'TFI-'.date('my').'-%04d';
            $id_transfusi_item = sprintf($format_id_transfusi_transfusi, $last_id_transfusi_item, 4);

            $data_tindakan_transfusi_item = array(
                'id'                     => $id_transfusi_item,
                'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                'user_id'                => $this->session->userdata('user_id'),
                'tindakan_transfusi_id'         => $tindakan_transfusi_id,
                'item_id'                => $item_id,
                'item_satuan_id'         => $satuan_id,
                'jumlah'                 => $jumlah,
                'harga_beli'             => $data_inventory['harga_beli'],
                'harga_jual'             => $harga_jual->harga,     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                'bn_sn_lot'              => $bn_sn_lot,
                'expire_date'            => date('Y-m-d', strtotime($expire_date)),
                'keterangan'             => $keterangan,
                'is_active'              => 1,
                'created_by'             => $this->session->userdata('user_id'),
                'created_date'           => date('Y-m-d H:i:s')
            );

            $tindakan_transfusi_item = $this->tindakan_transfusi_item_m->add_data($data_tindakan_transfusi_item);  

            $status_resep['status'] = 2;
            $edit_resep_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($status_resep, $id_resep_detail_identitas);

            $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
            $edit_resep_identitas = insert_data_api($status_resep, $apotik->url, $path_model, $id_resep_detail_identitas);

        }
        else
        {
            if($jumlah != 0)
            {
                // $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();
                $harga_jual = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);
               // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG
                $last_id_transfusi_item       = $this->tindakan_transfusi_item_m->get_max_id()->result_array();
                $last_id_transfusi_item       = intval($last_id_transfusi_item[0]['max_id'])+1;
                
                $format_id_transfusi_transfusi     = 'TFI-'.date('my').'-%04d';
                $id_transfusi_item = sprintf($format_id_transfusi_transfusi, $last_id_transfusi_item, 4);

                $data_tindakan_transfusi_item = array(
                    'id'                     => $id_transfusi_item,
                    'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                    'user_id'                => $this->session->userdata('user_id'),
                    'tindakan_transfusi_id'         => $tindakan_transfusi_id,
                    'item_id'                => $item_id,
                    'item_satuan_id'         => $satuan_id,
                    'jumlah'                 => $jumlah,
                    'harga_beli'             => $data_inventory['harga_beli'],
                    'harga_jual'             => $harga_jual->harga,     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                    'keterangan'             => $keterangan,
                    'is_active'              => 1,
                    'created_by'             => $this->session->userdata('user_id'),
                    'created_date'           => date('Y-m-d H:i:s')
                );

                $tindakan_transfusi_item = $this->tindakan_transfusi_item_m->add_data($data_tindakan_transfusi_item);     

                $status_resep['status'] = 2;
                $edit_resep_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($status_resep, $id_resep_detail_identitas); 

                $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                $edit_resep_identitas = insert_data_api($status_resep, $apotik->url, $path_model, $id_resep_detail_identitas);
            }

        }

    $draf_invoice_swasta = $this->draf_invoice_m->get_by(array('pasien_id' => $pasien->id, 'jenis_invoice' => 1), true);

    if(count($draf_invoice_swasta) == 0){
        $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
        $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
        
        $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
        $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

        $data_draft_tindakan = array(
            'id'    => $id_draft,
            'pasien_id'    => $pasien->id,
            'tipe'  => 1,
            'cabang_id'  => $this->session->userdata('cabang_id'),
            'tipe_pasien'  => 1,
            'nama_pasien'  => $pasien->nama,
            'shift'  => $data_tindakan_hd->shift,
            'user_level_id'  => $this->session->userdata('level_id'),
            'jenis_invoice' => 1,
            'status'    => 1,
            'is_active'    => 1,
            'akomodasi'    => 25000,
            'created_by'    => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);
    }elseif(count($draf_invoice_swasta) != 0){
        $id_draft = $draf_invoice_swasta->id;
        $data_draf_inv_swasta['akomodasi'] = 25000;
        $edit_inv_swasta = $this->draf_invoice_m->edit_data($data_draf_inv_swasta, $id_draft);
                    
    }

    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
    
    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

    $data_draft_tindakan_detail = array(
        'id'    => $id_draft_detail,
        'draf_invoice_id'    => $id_draft,
        'tipe_item' => 3,
        'item_id'   => $item_id,
        'jumlah'                 => $jumlah,
        'nama_tindakan' => $data_item->nama,
        'tipe_tindakan' => 2,
        'harga_jual'             => $harga_jual->harga,
        'status' => 1,
        'jumlah' => $jumlah,
        'is_active'    => 1,
        'created_by'    => $this->session->userdata('user_id'),
        'created_date'    => date('Y-m-d H:i:s')
    );

    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);


        
        if ($inventory_history_id) 
        {
            $flashdata = array(
                "success",
                translate("Item Transfusi Berhasil ditambahkan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
    }

    public function save_item_dalam_box_paket()
    {
        $waktu               = $this->input->post('waktu_dalam_box');
        $tindakan_hd_id      = $this->input->post('tindakan_hd_id');
        $item_id             = $this->input->post('item_id');
        $jumlah              = $this->input->post('jumlah_inventory');
        $satuan_id           = $this->input->post('satuan');
        $inventory_id        = $this->input->post('inventory_id');
        $stock               = $this->input->post('stock');
        $harga_beli          = $this->input->post('harga_beli');
        $keterangan          = $this->input->post('keterangan_dalam_box');
        $inventory_identitas = $this->input->post('inventory_identitas');
        $gudang_id = $this->input->post('gudang_id');
        $pmb_id = $this->input->post('pmb_id');
        $bn_sn_lot = $this->input->post('bn_sn_lot');
        $expire_date = $this->input->post('expire_date');
        $t_box_paket_detail_id = $this->input->post('t_box_paket_detail_id');
        $t_box_paket_id = $this->input->post('t_box_paket_id');

        $apotik = $this->cabang_m->get(4);
        // die(dump($inventory_identitas));

        $data_item = $this->item_m->get($item_id);

        
        if($bn_sn_lot != '' && $jumlah != 0 && $jumlah != '') 
        {
            // $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();
            $harga_jual = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);
           // SAVE TINDAKAN_HD_ITEM JIKA DATA KOSONG
            $data_tindakan_hd_item = array(
                'waktu'                  => date('Y-m-d H:i:s', strtotime($waktu)),
                'user_id'                => $this->session->userdata('user_id'),
                'tindakan_hd_id'         => $tindakan_hd_id,
                'item_id'                => $item_id,
                'jumlah'                 => $jumlah,
                'bn_sn_lot'              => $bn_sn_lot,
                'expire_date'            => date('Y-m-d', strtotime($expire_date)),
                '`index`'                => 1,
                'item_satuan_id'         => $satuan_id,
                'harga_beli'             => $data_inventory['harga_beli'],
                'harga_jual'             => $harga_jual->harga,     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                'keterangan'             => $keterangan,
                'tipe_pemberian'         => 1,
                'is_active'              => 1
            );
            $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);  

            $status_t_box['status'] = 3;
            $edit_t_box_paket = $this->t_box_paket_m->edit_data($status_t_box, $t_box_paket_id);

            $status_t_box_detail['status'] = 3;
            $status_t_box_detail['tanggal_pakai'] = date('Y-m-d H:i:s', strtotime($waktu));
            $edit_t_box_paket_detail = $this->t_box_paket_detail_m->edit_data($status_t_box_detail, $t_box_paket_detail_id);


        }
        else
        {
            if($jumlah != 0)
            {
                // $harga_jual = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_id)->result_array();
                $harga_jual = $this->item_satuan_m->get_by(array('id' => $satuan_id), true);
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
                    'harga_jual'             => (count($harga_jual) != 0)?$harga_jual->harga:0,     // didapatkan dari item_harga sesuai effective date nya <= hari ini
                    'keterangan'             => $keterangan,
                    'tipe_pemberian'         => 3,
                    'is_active'              => 1
                );
                $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item);     

                $status_resep['status'] = 2;
                $edit_resep_identitas = $this->tindakan_resep_obat_detail_identitas_m->save($status_resep, $id_resep_detail_identitas); 

                $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
                $edit_resep_identitas = insert_data_api($status_resep, $apotik->url, $path_model, $id_resep_detail_identitas);
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
       
        $result = $this->item_tersimpan_m->get_datatable_simpan_item($pasien_id);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        foreach($records->result_array() as $row)
        {
           
            $action='<a data-target="#modal_item_tersimpan" data-toggle="modal" href="'.base_url().'klinik_hd/transaksi_perawat/modal_item_tersimpan/'.$row['id'].'/'.$row['item_id'].'/'.$row['item_satuan_id'].'/'.str_replace(' ','_',$row['item_satuan']).'/'.$tindakan_hd_id.'/'.$pasien_id.'" title="'.translate('Gunakan Item', $this->session->userdata('language')).'" name="pakai[]"  data-id="'.$row['id'].'" class="btn btn-primary" style="margin-left: 10px;"><i class="fa fa-check"></i></a>';
             
            $output['data'][] = array(
                 
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['jumlah'].' '.$row['item_satuan'].' ('.$row['idx'].' kali)'.$action.'</div>'
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

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_item_tersimpan', $data);
    }

    public function modal_simpan_identitas($pasien_id, $item_id, $satuan_id)
    {
        $data = array(
            'pasien_id'      => $pasien_id,
            'item_id'        => $item_id,
            'item_satuan_id' => $satuan_id
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_simpan_identitas', $data);
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

    public function save_selesaikan()
    {
        // die_dump($this->input->post());
        $berat_akhir               = $this->input->post('berat_akhir');
        $tindakan_hd_id            = $this->input->post('tindakan_hd_id');
        $tindakan_hd_penaksiran_id = $this->input->post('tindakan_hd_penaksiran_id');
        $keluhan_selesai           = $this->input->post('keluhan_selesai');
        $assessment_cgs_selesai    = $this->input->post('assessment_cgs_selesai');
        $bed_id                    = $this->input->post('bed_id');
        $pertanyaan                = $this->input->post('pertanyaan');
        $reuse_dializer            = $this->input->post('reuse_dializer');


        $get_data_tindakan_hd  = $this->tindakan_hd_m->get($tindakan_hd_id);
        $url_core = config_item('url_core');
        $max_id = get_max_id_pembayaran($url_core);
        // die(dump($max_id));

        $data_pasien = $this->pasien_m->get($get_data_tindakan_hd->pasien_id);
        $data_cabang = $this->cabang_m->get($get_data_tindakan_hd->cabang_id);
        $data_poliklinik = $this->poliklinik_m->get(1);
        $data_dokter = $this->user_m->get($get_data_tindakan_hd->dokter_id);
        $data_penjamin = $this->penjamin_m->get($get_data_tindakan_hd->penjamin_id);

        $total_rupiah = 0;

        $status_bayar = 0;
        if($get_data_tindakan_hd->penjamin_id == 1)
        {
            $status_bayar = 0;
        }
        elseif($get_data_tindakan_hd->penjamin_id != 1)
        {
            if($get_data_tindakan_hd->is_sep == 0)
            {
                $status_bayar = 1;
            }
            else
            {
                $status_bayar = 2;
            }
        }

        // INSERT TABEL PASIEN_KLAIM
        $data_pasien_klaim = array(
            'id'           => NULL,
            'cabang_id'    => $get_data_tindakan_hd->cabang_id,
            'transaksi_id' => $get_data_tindakan_hd->id,
            'penjamin_id'  => $get_data_tindakan_hd->penjamin_id,
            'pasien_id'    => $get_data_tindakan_hd->pasien_id,
            'tipe'         => 1,
            'is_active'    => 1,
            'created_by'   => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d H:i:s', strtotime($get_data_tindakan_hd->tanggal))
        );
        // $pasien_klaim_id = $this->pasien_klaim_m->save($data_pasien_klaim);
        $path_model = 'klinik_hd/pasien_klaim_m';
        $cabang = $this->cabang_m->get_by(array('id' => $get_data_tindakan_hd->cabang_id));
        foreach ($cabang as $row_cabang) 
        {
            if($row_cabang->is_active == 1)
            {
                if($row_cabang->url != '' || $row_cabang->url != NULL)
                {
                    $pasien_klaim_id = insert_data_api_id($data_pasien_klaim,$row_cabang->url,$path_model);                    
                }
            }
        }


        //INSERT KE TABLE rm_transaksi_pasien di core
        $data_rm_tindakan = array(
            'pasien_id'       => $get_data_tindakan_hd->pasien_id,
            'nama_pasien'     => $data_pasien->nama,
            'cabang_id'       => $get_data_tindakan_hd->cabang_id,
            'nama_cabang'     => $data_cabang->nama,
            'poliklinik_id'   => 1,
            'nama_poliklinik' => $data_poliklinik->nama,
            'transaksi_id'    => $tindakan_hd_id,
            'no_transaksi'    => $get_data_tindakan_hd->no_transaksi,
            'tipe'            => 1,
            'tanggal'         => date('Y-m-d H:i:s', strtotime($get_data_tindakan_hd->tanggal)),
            'dokter_id'       => $get_data_tindakan_hd->dokter_id,
            'nama_dokter'     => $data_dokter->nama,
            'keterangan'      => $get_data_tindakan_hd->keterangan,
            'is_active'       => 1    
        );

        $path_model = 'global/rm_transaksi_pasien_m';
        $core = $this->cabang_m->get(1);
        $rm_tindakan_pasien = insert_data_api($data_rm_tindakan,$core->url,$path_model);


        $assesment_data = $this->tindakan_hd_penaksiran_m->get($tindakan_hd_penaksiran_id);

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){
            $path_model = 'global/rm_transaksi_pasien_m';
            $cabang_pasien = $this->cabang_m->get($get_data_tindakan_hd->cabang_pasien_id);
            
            $rm_tindakan_pasien = insert_data_api($data_rm_tindakan,$cabang_pasien->url,$path_model);

        }

        $data_pas_tdk = array(
            'status' => 3
        );
        $wheres_pas_tdk = array(
            'tindakan_id' => $id_tindakan->id,
            'tipe_tindakan' => 1
        );

        $edit_pasien_tindakan = $this->pasien_tindakan_m->update_by($this->session->userdata('user_id'), $data_pas_tdk, $wheres_pas_tdk);

        $data_antrian = $this->antrian_pasien_m->get_by(array('pasien_id' => $get_data_tindakan_hd->pasien_id, 'posisi_loket' => 5, 'status' => 1), true);

        $last_id       = $this->antrian_pasien_m->get_max_id()->result_array();
        $last_id       = intval($last_id[0]['max_id'])+1;
        
        $format_id     = 'ANT-'.date('m').'-'.date('Y').'-%04d';
        $id_antrian    = sprintf($format_id, $last_id, 4);

        $no_urut = $this->antrian_pasien_m->get_max_no_urut_dokter(6,$get_data_tindakan_hd->dokter_id)->result_array();
        $no_urut = intval($no_urut[0]['max_no_urut'])+1;

        $data_simpan = array(
            'id'    => $id_antrian,
            "dokter_id"           => $get_data_tindakan_hd->dokter_id,
            "pasien_id"           => $data_antrian->pasien_id,
            'nama_pasien' => $data_antrian->nama_pasien,
            'no_telp' => $data_antrian->no_telp,
            'posisi_loket' => 6,
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

        $bed_id = $get_data_tindakan_hd->bed_id;

        $bed_db = $this->bed_m->get($bed_id);

        // UPDATE BED
        $data_bed = array(
            'user_edit_id'    => NULL
        );
        if($bed_db->shift < 3){
            $data_bed['shift'] = intval($bed_db->shift) + 1;
            if($bed_db->status_antrian == 0){
                $data_bed['status'] = 1;
                $data_bed['status_antrian'] = 0;
            }if($bed_db->status_antrian == 1){
                $data_bed['status'] = 2;
                $data_bed['status_antrian'] = 0;
            }
        }if($bed_db->shift == 3){
            $data_bed['shift'] = 1;
            if($bed_db->status_antrian == 0){
                $data_bed['status'] = 1;
                $data_bed['status_antrian'] = 0;
            }if($bed_db->status_antrian == 1){
                $data_bed['status'] = 2;
                $data_bed['status_antrian'] = 0;
            }
        }
        $update_bed_id = $this->bed_m->save($data_bed, $bed_id);

        $bed_db_kosong = $this->bed_m->get_by(array('status' => 1));

        if(count($bed_db_kosong) != 0){
            $bed_db_kosong = object_to_array($bed_db_kosong);

            foreach ($bed_db_kosong as $key => $row_bed) {
                $shift_bed['shift'] = $data_bed['shift'];
                $shift_bed['status_antrian'] = 0;

                $update_bed_kosong = $this->bed_m->save($shift_bed, $row_bed['id']);
            }
        }
        $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
        $date = getDate();
        $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
        file_put_contents($file,$jam);

        // UPDATE TINDAKAN_HD
        $data_update_berat_akhir = array(
            'berat_akhir' => $berat_akhir,
            'status'      => 3,
        );
        $update_berat_akhir = $this->tindakan_hd_m->save($data_update_berat_akhir, $tindakan_hd_id);

        if($keluhan_selesai != ''){
            $data_update_assessment_cgs = array(
                'assessment_cgs'    => $assessment_cgs_selesai."\nKeluhan Akhir: ".$keluhan_selesai
            );
        }else{
            $data_update_assessment_cgs = array(
                'assessment_cgs'    => $assessment_cgs_selesai
            );
        }
        // UPDATE TINDAKAN_HD_PENAKSIRAN
        $update_assessment_cgs = $this->tindakan_hd_penaksiran_m->save($data_update_assessment_cgs, $tindakan_hd_penaksiran_id);

        $cabang_id = $this->session->userdata('cabang_id');
        $cabang_aktif = $this->cabang_m->get_by(array('id' => $cabang_id), true);

        if($reuse_dializer == 1)
        {
            $item_digunakan = $this->tindakan_hd_item_m->get_sum_item($tindakan_hd_id)->result_array();
            if($assesment_data->dialyzer_new == 1 && $assesment_data->dialyzer_reuse == 0){
                if(count($item_digunakan))
                {
                    foreach ($item_digunakan as $gunakan) 
                    {
                        $max_id = $this->item_tersimpan_m->get_max_id()->result_array();
                        if($max_id == null)
                        {
                            $max_id = 1;
                        }
                        else
                        {
                            $max_id = $max_id[0]['max_id'] + 1;
                        }

                        $data_simpan_item = array(
                            'simpan_item_id'      => $max_id,
                            'pasien_id'           => $get_data_tindakan_hd->pasien_id,
                            'item_id'             => $gunakan['item_id'],
                            'item_satuan_id'      => $gunakan['item_satuan_id'],
                            'transaksi_simpan_id' => $save_tindakan_hd_history,
                            'tipe_transaksi'      => 1,
                            'jumlah'              => $gunakan['jumlah'],
                            '`index`'             => intval($gunakan['index']),
                            '`counter`'           => 0,
                            'kode_dialyzer'       => $gunakan['kode_dialyzer'],
                            'bn_sn_lot'       => $gunakan['bn_sn_lot'],
                            'expire_date'       => date('Y-m-d', strtotime($gunakan['expire_date'])),
                            'harga_beli'          => $gunakan['harga_beli'],
                            'harga_jual'          => $gunakan['harga_jual'],
                            'status'              => 1,
                            'status_reuse'        => 1,
                            'is_active'           => 1,
                            'created_by'          => $this->session->userdata('user_id'),
                            'created_date'        => date('Y-m-d H:i:s')
                        );
                        $simpan_item_id = $this->item_tersimpan_m->add_data($data_simpan_item);
                    }
                }
            }

            if($assesment_data->dialyzer_new == 0 && $assesment_data->dialyzer_reuse == 1){
                if(count($item_digunakan))
                {
                    foreach ($item_digunakan as $gunakan) {
                        $id_simpan_item =  0;
                        if($gunakan['tipe_pemberian'] == 2){
                            $id_simpan_item = $gunakan['transaksi_pemberian_id'];

                            $simpan_item = $this->item_tersimpan_m->get_by(array('simpan_item_id' => $id_simpan_item), true);
                            $data_simpan_item = array(
                                '`index`'       => intval($simpan_item->index) + 1,
                                '`counter`'     => intval($simpan_item->counter)
                            );

                            $wheres_simpan_item['simpan_item_id'] = $id_simpan_item;
                            $simpan_item_id = $this->item_tersimpan_m->update_by($this->session->userdata('user_id'), $data_simpan_item, $wheres_simpan_item);
                        }
                    }
                }
            }
        }

        $user_id = $this->session->userdata('user_id');
        $t_box_paket_tindakan = $this->t_box_paket_m->get_by(array('tindakan_id' => $tindakan_hd_id), true);

        $wheres['status'] = 3;
        $id_edit['t_box_paket_id'] = $t_box_paket_tindakan->id;

        $edit_t_box_paket = $this->t_box_paket_m->edit_data($wheres, $t_box_paket_tindakan->id);
        $edit_t_box_paket_detail = $this->t_box_paket_detail_m->update_by($user_id,$wheres, $id_edit);

        $cabang_id = $this->session->userdata('cabang_id');
        $cabang_aktif = $this->cabang_m->get_by(array('id' => $cabang_id), true);

        $last_number = $this->tindakan_hd_history_m->get_nomor_tindakan($cabang_aktif->kode)->result_array();
        $last_number = intval($last_number[0]['max_nomor_po'])+1;
                    
        $format      = 'HD'.$cabang_aktif->kode.'-'.date('ym').'-%04d';
        $tindakan_number   = sprintf($format, $last_number, 4);

        $tindakan_hd_sementara = $this->tindakan_hd_m->get_by(array('id' => $tindakan_hd_id), true);
        $tindakan_hd_sementara = object_to_array($tindakan_hd_sementara);
        $tindakan_hd_sementara['no_transaksi'] = $tindakan_number;
        $tindakan_hd_sementara['status'] = 3;
        unset($tindakan_hd_sementara['id']);
        $save_tindakan_hd_history = $this->tindakan_hd_history_m->save($tindakan_hd_sementara);

        $last_number_id_os_upload = $this->outstanding_upload_dokumen_klaim_m->get_max_id()->result_array();
        $last_number_id_os_upload = intval($last_number_id_os_upload[0]['max_id'])+1;

        $data_os_upload = array(
            'id'    => $last_number_id_os_upload,
            'tindakan_hd_history_id'    => $save_tindakan_hd_history,
            'pasien_id' => $get_data_tindakan_hd->pasien_id,
            'status'    => 1,
            'created_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d H:i:s')
        );

        $add_os_upload = $this->outstanding_upload_dokumen_klaim_m->add_data($data_os_upload);

        
        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            unset($tindakan_hd_sementara['created_by']);
            unset($tindakan_hd_sementara['created_date']);
            unset($tindakan_hd_sementara['modified_by']);
            unset($tindakan_hd_sementara['modified_date']);

        $tindakan_hd_sementara['cabang_id'] = $get_data_tindakan_hd->cabang_pasien_id;

            $path_model_tindakan = 'klinik_hd/tindakan_hd_history_m';
            $cabang_pasien = $this->cabang_m->get_by(array('id' => $get_data_tindakan_hd->cabang_pasien_id), true);
            $save_tindakan_hd_history_cabang = insert_data_api($tindakan_hd_sementara,$cabang_pasien->url,$path_model_tindakan);
            //die(dump($save_tindakan_hd_history_cabang));

        }
        $save_tindakan_hd_history_cabang = str_replace('"', '', $save_tindakan_hd_history_cabang);


        $assesment_data = $this->tindakan_hd_penaksiran_m->get($tindakan_hd_penaksiran_id);
        $assesment_data_sementara = object_to_array($assesment_data);
        $assesment_data_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
        unset($assesment_data_sementara['id']);
        $save_tindakan_hd_penaksiran_history = $this->tindakan_hd_penaksiran_history_m->save($assesment_data_sementara);

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            unset($assesment_data_sementara['created_by']);
            unset($assesment_data_sementara['created_date']);
            unset($assesment_data_sementara['modified_by']);
            unset($assesment_data_sementara['modified_date']);

            $path_model_tindakan_penaksiran = 'klinik_hd/tindakan_hd_penaksiran_history_m';

            $assesment_data_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
            unset($assesment_data_sementara['id']);
            $cabang_pasien = $this->cabang_m->get($get_data_tindakan_hd->cabang_pasien_id);
            $save_tindakan_hd_assesment_history_cabang = insert_data_api($assesment_data_sementara,$cabang_pasien->url,$path_model_tindakan_penaksiran);
        }

        $observasi_dialisis_sementara = $this->observasi_m->get_by(array('transaksi_hd_id' => $tindakan_hd_id));
        $observasi_dialisis_sementara = object_to_array($observasi_dialisis_sementara);

        foreach ($observasi_dialisis_sementara as $key => $obs_dialisis_sementara) {
            $obs_dialisis_sementara['transaksi_hd_id'] = $save_tindakan_hd_history;
            unset($obs_dialisis_sementara['id']);
            $save_observasi_history = $this->observasi_history_m->save($obs_dialisis_sementara);
        }
        
        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){


            foreach ($observasi_dialisis_sementara as $key => $obs_dialisis_sementara) {
                $obs_dialisis_sementara['transaksi_hd_id'] = $save_tindakan_hd_history_cabang;
                

            $path_model_observasi = 'klinik_hd/observasi_history_m';

                unset($obs_dialisis_sementara['id']);
                unset($obs_dialisis_sementara['created_by']);
                unset($obs_dialisis_sementara['created_date']);
                unset($obs_dialisis_sementara['modified_by']);
                unset($obs_dialisis_sementara['modified_date']);

                $cabang_pasien = $this->cabang_m->get($get_data_tindakan_hd->cabang_pasien_id);
                $save_observasi_cabang = insert_data_api($obs_dialisis_sementara,$cabang_pasien->url,$path_model_observasi);
            }
           
        }


        $komplikasi_sementara = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $komplikasi_sementara = object_to_array($komplikasi_sementara);

        foreach ($komplikasi_sementara as $key => $komp_sementara) {
            $komp_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($komp_sementara['id']);
            $save_komplikasi_history = $this->pasien_komplikasi_history_m->save($komp_sementara);
        }

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            $path_model_komplikasi = 'klinik_hd/pasien_komplikasi_history_m';

            foreach ($komplikasi_sementara as $key => $komp_sementara) {
                $komp_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
                unset($komp_sementara['id']);
                unset($komp_sementara['created_by']);
            unset($komp_sementara['created_date']);
            unset($komp_sementara['modified_by']);
            unset($komp_sementara['modified_date']);
                $save_komplikasi_cabang = insert_data_api($komp_sementara,$cabang_pasien->url,$path_model_komplikasi);
            }
           
        }
        

        $problem_sementara = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $problem_sementara = object_to_array($problem_sementara);

        foreach ($problem_sementara as $key => $prob_sementara) {
            $prob_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($prob_sementara['id']);
            $save_komplikasi_history = $this->pasien_problem_history_m->save($prob_sementara);
        }

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            $path_model_problem = 'klinik_hd/pasien_problem_history_m';

            foreach ($problem_sementara as $key => $prob_sementara) {
                $prob_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
                unset($prob_sementara['id']);
                unset($prob_sementara['created_by']);
            unset($prob_sementara['created_date']);
            unset($prob_sementara['modified_by']);
            unset($prob_sementara['modified_date']);
                $save_problem_cabang = insert_data_api($prob_sementara,$cabang_pasien->url,$path_model_problem);
            }
           
        }
        

        $tindakan_item_sementara = $this->tindakan_hd_item_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $tindakan_item_sementara = object_to_array($tindakan_item_sementara);

        foreach ($tindakan_item_sementara as $key => $tind_item_sementara) {
            $tind_item_sementara['`index`'] = $tind_item_sementara['index'];
            $tind_item_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($tind_item_sementara['id']);
            unset($tind_item_sementara['index']);
            $save_tindakan_item_history = $this->tindakan_hd_item_history_m->save($tind_item_sementara);
        }

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            $path_model_item = 'klinik_hd/tindakan_hd_item_m';

            foreach ($tindakan_item_sementara as $key => $tind_item_sementara) {
                $tind_item_sementara['`index`'] = $tind_item_sementara['index'];
                $tind_item_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
                unset($tind_item_sementara['id']);
                unset($tind_item_sementara['index']);
                unset($tind_item_sementara['created_date']);
                unset($tind_item_sementara['modified_by']);
                unset($tind_item_sementara['modified_date']);
                unset($tind_item_sementara['created_by']);

                $save_item_sementara = insert_data_api($tind_item_sementara,$cabang_pasien->url,$path_model_item);
            }
           
        }
        

        $tindakan_resep_sementara = $this->tindakan_resep_obat_m->get_by(array('tindakan_id' => $tindakan_hd_id));
        $tindakan_resep_sementara = object_to_array($tindakan_resep_sementara);

        foreach ($tindakan_resep_sementara as $key => $tind_resep_sementara) {
            $tind_resep_sementara['tindakan_id'] = $save_tindakan_hd_history;
            unset($tind_resep_sementara['id']);

            $save_tindakan_resep_history = $this->tindakan_resep_obat_history_m->save($tind_resep_sementara);
        }
        

        $tindakan_resep_detail_sementara = $this->tindakan_resep_obat_detail_m->get_by(array('tindakan_id' => $tindakan_hd_id));
        $tindakan_resep_detail_sementara = object_to_array($tindakan_resep_detail_sementara);
        foreach ($tindakan_resep_detail_sementara as $key => $tind_resep_detail_sementara) {
            $tind_resep_detail_sementara['tindakan_resep_obat_id'] = $save_tindakan_resep_history;
            $tind_resep_detail_sementara['tindakan_id'] = $save_tindakan_hd_history;
            unset($tind_resep_detail_sementara['id']);
            $save_tindakan_resep_history = $this->tindakan_resep_obat_detail_history_m->save($tind_resep_detail_sementara);
        }

        $diagnosa_sementara = $this->tindakan_hd_diagnosa_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $diagnosa_sementara = object_to_array($diagnosa_sementara);
        foreach ($diagnosa_sementara as $key => $diag_sementara) {
            $diag_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($diag_sementara['id']);
            $save_tindakan_diagnosa_history = $this->tindakan_hd_diagnosa_history_m->save($diag_sementara);
        }

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            $path_model_diagnosa = 'klinik_hd/tindakan_hd_item_m';

            foreach ($diagnosa_sementara as $key => $diag_sementara) {
                
                $diag_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
                unset($diag_sementara['id']);
                unset($diag_sementara['created_date']);
                unset($diag_sementara['modified_by']);
                unset($diag_sementara['modified_date']);
                unset($diag_sementara['created_by']);
                $save_diagnosa_sementara = insert_data_api($diag_sementara,$cabang_pasien->url,$path_model_diagnosa);
            }
           
        }
        
    
        $visit_sementara = $this->tindakan_hd_visit_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $visit_sementara = object_to_array($visit_sementara);
        foreach ($visit_sementara as $key => $vst_sementara) {
            $vst_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($vst_sementara['id']);
            $save_tindakan_visit_history = $this->tindakan_hd_visit_history_m->save($vst_sementara);
        }

        
        
        // INSERT TINDAKAN_HD_SURVEY
        foreach ($pertanyaan as $data_pertanyaan) 
        {
            $data_tindakan_hd_survey = array(
                'tindakan_hd_id'       => $save_tindakan_hd_history,
                'pertanyaan_survey_id' => $data_pertanyaan['survey_id'],
                'nilai'                => $data_pertanyaan['nilai'],
            );            
            $tindakan_hd_survey_id = $this->tindakan_hd_survey_history_m->save($data_tindakan_hd_survey);
        }   

        $cabang = $this->cabang_m->get(1);
        $data_status_pendaftaran_tindakan['status']=3;

        $edit_daftar = $this->pendaftaran_tindakan_history_m->save($data_status_pendaftaran_tindakan, $tindakan_hd_sementara['pendaftaran_tindakan_id']);

       

        $delete_observasi = $this->observasi_m->delete_by(array('transaksi_hd_id' => $tindakan_hd_id));
        $delete_komplikasi = $this->pasien_komplikasi_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_problem = $this->pasien_problem_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_diagnosa = $this->tindakan_hd_diagnosa_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_item = $this->tindakan_hd_item_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_note = $this->tindakan_hd_note_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_penaksiran = $this->tindakan_hd_penaksiran_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_visit = $this->tindakan_hd_visit_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_tindakan = $this->tindakan_hd_m->delete_by(array('id' => $tindakan_hd_id));


        if ($update_berat_akhir && $update_assessment_cgs && $tindakan_hd_survey_id) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data selesai tindakan telah ditambahkan", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
        }

        $lantai = $this->bed_m->get($bed_id);
        redirect("klinik_hd/transaksi_perawat#lantai".$lantai->lantai_id);

    }

    public function save_selesaikan_copy()
    {
        // die_dump($this->input->post());
        $berat_akhir               = $this->input->post('berat_akhir');
        $tindakan_hd_id            = $this->input->post('tindakan_hd_id');
        $tindakan_hd_penaksiran_id = $this->input->post('tindakan_hd_penaksiran_id');
        $keluhan_selesai           = $this->input->post('keluhan_selesai');
        $assessment_cgs_selesai    = $this->input->post('assessment_cgs_selesai');
        $bed_id                    = $this->input->post('bed_id');
        $pertanyaan                = $this->input->post('pertanyaan');
        $reuse_dializer            = $this->input->post('reuse_dializer');


        $get_data_tindakan_hd  = $this->tindakan_hd_m->get($tindakan_hd_id);
        $url_core = config_item('url_core');
        $max_id = get_max_id_pembayaran($url_core);
        // die(dump($max_id));

        $data_pasien = $this->pasien_m->get($get_data_tindakan_hd->pasien_id);
        $data_cabang = $this->cabang_m->get($get_data_tindakan_hd->cabang_id);
        $data_poliklinik = $this->poliklinik_m->get(1);
        $data_dokter = $this->user_m->get($get_data_tindakan_hd->dokter_id);
        $data_penjamin = $this->penjamin_m->get($get_data_tindakan_hd->penjamin_id);

        $total_rupiah = 0;

        $status_bayar = 0;
        if($get_data_tindakan_hd->penjamin_id == 1)
        {
            $status_bayar = 0;
        }
        elseif($get_data_tindakan_hd->penjamin_id != 1)
        {
            if($get_data_tindakan_hd->is_sep == 0)
            {
                $status_bayar = 1;
            }
            else
            {
                $status_bayar = 2;
            }
        }

        // INSERT TABEL PASIEN_KLAIM
        $data_pasien_klaim = array(
            'id'           => NULL,
            'cabang_id'    => $get_data_tindakan_hd->cabang_id,
            'transaksi_id' => $get_data_tindakan_hd->id,
            'penjamin_id'  => $get_data_tindakan_hd->penjamin_id,
            'pasien_id'    => $get_data_tindakan_hd->pasien_id,
            'tipe'         => 1,
            'is_active'    => 1,
            'created_by'   => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d H:i:s', strtotime($get_data_tindakan_hd->tanggal))
        );
        // $pasien_klaim_id = $this->pasien_klaim_m->save($data_pasien_klaim);
        $path_model = 'klinik_hd/pasien_klaim_m';
        $cabang = $this->cabang_m->get_by(array('id' => $get_data_tindakan_hd->cabang_id));
        foreach ($cabang as $row_cabang) 
        {
            if($row_cabang->is_active == 1)
            {
                if($row_cabang->url != '' || $row_cabang->url != NULL)
                {
                    $pasien_klaim_id = insert_data_api_id($data_pasien_klaim,$row_cabang->url,$path_model);                    
                }
            }
        }


        //INSERT KE TABLE rm_transaksi_pasien di core
        $data_rm_tindakan = array(
            'pasien_id'       => $get_data_tindakan_hd->pasien_id,
            'nama_pasien'     => $data_pasien->nama,
            'cabang_id'       => $get_data_tindakan_hd->cabang_id,
            'nama_cabang'     => $data_cabang->nama,
            'poliklinik_id'   => 1,
            'nama_poliklinik' => $data_poliklinik->nama,
            'transaksi_id'    => $tindakan_hd_id,
            'no_transaksi'    => $get_data_tindakan_hd->no_transaksi,
            'tipe'            => 1,
            'tanggal'         => date('Y-m-d H:i:s', strtotime($get_data_tindakan_hd->tanggal)),
            'dokter_id'       => $get_data_tindakan_hd->dokter_id,
            'nama_dokter'     => $data_dokter->nama,
            'keterangan'      => $get_data_tindakan_hd->keterangan,
            'is_active'       => 1    
        );

        $path_model = 'global/rm_transaksi_pasien_m';
        $core = $this->cabang_m->get(1);
        $rm_tindakan_pasien = insert_data_api($data_rm_tindakan,$core->url,$path_model);



        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){
            $path_model = 'global/rm_transaksi_pasien_m';
            $cabang_pasien = $this->cabang_m->get($get_data_tindakan_hd->cabang_pasien_id);
            
            $rm_tindakan_pasien = insert_data_api($data_rm_tindakan,$cabang_pasien->url,$path_model);

        }

        $bed_id = $get_data_tindakan_hd->bed_id;

        $bed_db = $this->bed_m->get($bed_id);

        // UPDATE BED
        $data_bed = array(
            'user_edit_id'    => NULL
        );
        if($bed_db->shift < 3){
            $data_bed['shift'] = intval($bed_db->shift) + 1;
            if($bed_db->status_antrian == 0){
                $data_bed['status'] = 1;
                $data_bed['status_antrian'] = 0;
            }if($bed_db->status_antrian == 1){
                $data_bed['status'] = 2;
                $data_bed['status_antrian'] = 0;
            }
        }if($bed_db->shift == 3){
            $data_bed['shift'] = 1;
            if($bed_db->status_antrian == 0){
                $data_bed['status'] = 1;
                $data_bed['status_antrian'] = 0;
            }if($bed_db->status_antrian == 1){
                $data_bed['status'] = 2;
                $data_bed['status_antrian'] = 0;
            }
        }
        $update_bed_id = $this->bed_m->save($data_bed, $bed_id);

        $bed_db_kosong = $this->bed_m->get_by(array('status' => 1));

        if(count($bed_db_kosong) != 0){
            $bed_db_kosong = object_to_array($bed_db_kosong);

            foreach ($bed_db_kosong as $key => $row_bed) {
                $shift_bed['shift'] = $data_bed['shift'];
                $shift_bed['status_antrian'] = 0;

                $update_bed_kosong = $this->bed_m->save($shift_bed, $row_bed['id']);
            }
        }
        $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
        $date = getDate();
        $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
        file_put_contents($file,$jam);

        // UPDATE TINDAKAN_HD
        $data_update_berat_akhir = array(
            'berat_akhir' => $berat_akhir,
            'status'      => 3,
        );
        $update_berat_akhir = $this->tindakan_hd_m->save($data_update_berat_akhir, $tindakan_hd_id);

        if($keluhan_selesai != ''){
            $data_update_assessment_cgs = array(
                'assessment_cgs'    => $assessment_cgs_selesai."\nKeluhan Akhir: ".$keluhan_selesai
            );
        }else{
            $data_update_assessment_cgs = array(
                'assessment_cgs'    => $assessment_cgs_selesai
            );
        }
        // UPDATE TINDAKAN_HD_PENAKSIRAN
        $update_assessment_cgs = $this->tindakan_hd_penaksiran_m->save($data_update_assessment_cgs, $tindakan_hd_penaksiran_id);

        $cabang_id = $this->session->userdata('cabang_id');
        $cabang_aktif = $this->cabang_m->get_by(array('id' => $cabang_id), true);

        $last_number = $this->tindakan_hd_history_m->get_nomor_tindakan($cabang_aktif->kode)->result_array();
        $last_number = intval($last_number[0]['max_nomor_po'])+1;
                    
        $format      = 'HD'.$cabang_aktif->kode.'-'.date('ym').'-%04d';
        $tindakan_number   = sprintf($format, $last_number, 4);

        $tindakan_hd_sementara = $this->tindakan_hd_m->get_by(array('id' => $tindakan_hd_id), true);
        $tindakan_hd_sementara = object_to_array($tindakan_hd_sementara);
        $tindakan_hd_sementara['no_transaksi'] = $tindakan_number;
        $tindakan_hd_sementara['status'] = 3;
        unset($tindakan_hd_sementara['id']);
        $save_tindakan_hd_history = $this->tindakan_hd_history_m->save($tindakan_hd_sementara);

        
        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            unset($tindakan_hd_sementara['created_by']);
            unset($tindakan_hd_sementara['created_date']);
            unset($tindakan_hd_sementara['modified_by']);
            unset($tindakan_hd_sementara['modified_date']);

        $tindakan_hd_sementara['cabang_id'] = $get_data_tindakan_hd->cabang_pasien_id;

            $path_model_tindakan = 'klinik_hd/tindakan_hd_history_m';
            $cabang_pasien = $this->cabang_m->get_by(array('id' => $get_data_tindakan_hd->cabang_pasien_id), true);
            $save_tindakan_hd_history_cabang = insert_data_api($tindakan_hd_sementara,$cabang_pasien->url,$path_model_tindakan);
            //die(dump($save_tindakan_hd_history_cabang));

        }
        $save_tindakan_hd_history_cabang = str_replace('"', '', $save_tindakan_hd_history_cabang);


        $assesment_data = $this->tindakan_hd_penaksiran_m->get($tindakan_hd_penaksiran_id);
        $assesment_data_sementara = object_to_array($assesment_data);
        $assesment_data_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
        unset($assesment_data_sementara['id']);
        $save_tindakan_hd_penaksiran_history = $this->tindakan_hd_penaksiran_history_m->save($assesment_data_sementara);

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            unset($assesment_data_sementara['created_by']);
            unset($assesment_data_sementara['created_date']);
            unset($assesment_data_sementara['modified_by']);
            unset($assesment_data_sementara['modified_date']);

            $path_model_tindakan_penaksiran = 'klinik_hd/tindakan_hd_penaksiran_history_m';

            $assesment_data_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
            unset($assesment_data_sementara['id']);
            $cabang_pasien = $this->cabang_m->get($get_data_tindakan_hd->cabang_pasien_id);
            $save_tindakan_hd_assesment_history_cabang = insert_data_api($assesment_data_sementara,$cabang_pasien->url,$path_model_tindakan_penaksiran);
        }

        $observasi_dialisis_sementara = $this->observasi_m->get_by(array('transaksi_hd_id' => $tindakan_hd_id));
        $observasi_dialisis_sementara = object_to_array($observasi_dialisis_sementara);

        foreach ($observasi_dialisis_sementara as $key => $obs_dialisis_sementara) {
            $obs_dialisis_sementara['transaksi_hd_id'] = $save_tindakan_hd_history;
            unset($obs_dialisis_sementara['id']);
            $save_observasi_history = $this->observasi_history_m->save($obs_dialisis_sementara);
        }
        
        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){


            foreach ($observasi_dialisis_sementara as $key => $obs_dialisis_sementara) {
                $obs_dialisis_sementara['transaksi_hd_id'] = $save_tindakan_hd_history_cabang;
                

            $path_model_observasi = 'klinik_hd/observasi_history_m';

                unset($obs_dialisis_sementara['id']);
                unset($obs_dialisis_sementara['created_by']);
                unset($obs_dialisis_sementara['created_date']);
                unset($obs_dialisis_sementara['modified_by']);
                unset($obs_dialisis_sementara['modified_date']);

                $cabang_pasien = $this->cabang_m->get($get_data_tindakan_hd->cabang_pasien_id);
                $save_observasi_cabang = insert_data_api($obs_dialisis_sementara,$cabang_pasien->url,$path_model_observasi);
            }
           
        }


        $komplikasi_sementara = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $komplikasi_sementara = object_to_array($komplikasi_sementara);

        foreach ($komplikasi_sementara as $key => $komp_sementara) {
            $komp_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($komp_sementara['id']);
            $save_komplikasi_history = $this->pasien_komplikasi_history_m->save($komp_sementara);
        }

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            $path_model_komplikasi = 'klinik_hd/pasien_komplikasi_history_m';

            foreach ($komplikasi_sementara as $key => $komp_sementara) {
                $komp_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
                unset($komp_sementara['id']);
                unset($komp_sementara['created_by']);
            unset($komp_sementara['created_date']);
            unset($komp_sementara['modified_by']);
            unset($komp_sementara['modified_date']);
                $save_komplikasi_cabang = insert_data_api($komp_sementara,$cabang_pasien->url,$path_model_komplikasi);
            }
           
        }
        

        $problem_sementara = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $problem_sementara = object_to_array($problem_sementara);

        foreach ($problem_sementara as $key => $prob_sementara) {
            $prob_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($prob_sementara['id']);
            $save_komplikasi_history = $this->pasien_problem_history_m->save($prob_sementara);
        }

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            $path_model_problem = 'klinik_hd/pasien_problem_history_m';

            foreach ($problem_sementara as $key => $prob_sementara) {
                $prob_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
                unset($prob_sementara['id']);
                unset($prob_sementara['created_by']);
            unset($prob_sementara['created_date']);
            unset($prob_sementara['modified_by']);
            unset($prob_sementara['modified_date']);
                $save_problem_cabang = insert_data_api($prob_sementara,$cabang_pasien->url,$path_model_problem);
            }
           
        }
        

        $tindakan_item_sementara = $this->tindakan_hd_item_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $tindakan_item_sementara = object_to_array($tindakan_item_sementara);

        foreach ($tindakan_item_sementara as $key => $tind_item_sementara) {
            $tind_item_sementara['`index`'] = $tind_item_sementara['index'];
            $tind_item_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($tind_item_sementara['id']);
            unset($tind_item_sementara['index']);
            $save_tindakan_item_history = $this->tindakan_hd_item_history_m->save($tind_item_sementara);
        }

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            $path_model_item = 'klinik_hd/tindakan_hd_item_m';

            foreach ($tindakan_item_sementara as $key => $tind_item_sementara) {
                $tind_item_sementara['`index`'] = $tind_item_sementara['index'];
                $tind_item_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
                unset($tind_item_sementara['id']);
                unset($tind_item_sementara['index']);
                unset($tind_item_sementara['created_date']);
                unset($tind_item_sementara['modified_by']);
                unset($tind_item_sementara['modified_date']);
                unset($tind_item_sementara['created_by']);

                $save_item_sementara = insert_data_api($tind_item_sementara,$cabang_pasien->url,$path_model_item);
            }
           
        }
        

        $tindakan_resep_sementara = $this->tindakan_resep_obat_m->get_by(array('tindakan_id' => $tindakan_hd_id));
        $tindakan_resep_sementara = object_to_array($tindakan_resep_sementara);

        foreach ($tindakan_resep_sementara as $key => $tind_resep_sementara) {
            $tind_resep_sementara['tindakan_id'] = $save_tindakan_hd_history;
            unset($tind_resep_sementara['id']);

            $save_tindakan_resep_history = $this->tindakan_resep_obat_history_m->save($tind_resep_sementara);
        }
        

        $tindakan_resep_detail_sementara = $this->tindakan_resep_obat_detail_m->get_by(array('tindakan_id' => $tindakan_hd_id));
        $tindakan_resep_detail_sementara = object_to_array($tindakan_resep_detail_sementara);
        foreach ($tindakan_resep_detail_sementara as $key => $tind_resep_detail_sementara) {
            $tind_resep_detail_sementara['tindakan_resep_obat_id'] = $save_tindakan_resep_history;
            $tind_resep_detail_sementara['tindakan_id'] = $save_tindakan_hd_history;
            unset($tind_resep_detail_sementara['id']);
            $save_tindakan_resep_history = $this->tindakan_resep_obat_detail_history_m->save($tind_resep_detail_sementara);
        }
       


        // $tindakan_resep_detail_identitas_sementara = $this->tindakan_resep_obat_detail_identitas_m->get_by(array('tindakan_id' => $tindakan_hd_id));
        // $tindakan_resep_detail_identitas_sementara = object_to_array($tindakan_resep_detail_identitas_sementara);
        // $tindakan_resep_detail_identitas_sementara['tindakan_resep_obat_id'] = $save_tindakan_resep_history;
        // $tindakan_resep_detail_identitas_sementara['tindakan_id'] = $save_tindakan_hd_history;
        // unset($tindakan_resep_detail_identitas_sementara['id']);
        // $save_tindakan_resep_history = $this->tindakan_resep_obat_detail_history_m->save($tindakan_resep_detail_identitas_sementara);

        $diagnosa_sementara = $this->tindakan_hd_diagnosa_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $diagnosa_sementara = object_to_array($diagnosa_sementara);
        foreach ($diagnosa_sementara as $key => $diag_sementara) {
            $diag_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($diag_sementara['id']);
            $save_tindakan_diagnosa_history = $this->tindakan_hd_diagnosa_history_m->save($diag_sementara);
        }

        if($get_data_tindakan_hd->cabang_id != $get_data_tindakan_hd->cabang_pasien_id){

            $path_model_diagnosa = 'klinik_hd/tindakan_hd_item_m';

            foreach ($diagnosa_sementara as $key => $diag_sementara) {
                
                $diag_sementara['tindakan_hd_id'] = $save_tindakan_hd_history_cabang;
                unset($diag_sementara['id']);
                unset($diag_sementara['created_date']);
                unset($diag_sementara['modified_by']);
                unset($diag_sementara['modified_date']);
                unset($diag_sementara['created_by']);
                $save_diagnosa_sementara = insert_data_api($diag_sementara,$cabang_pasien->url,$path_model_diagnosa);
            }
           
        }
        
    
        $visit_sementara = $this->tindakan_hd_visit_m->get_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $visit_sementara = object_to_array($visit_sementara);
        foreach ($visit_sementara as $key => $vst_sementara) {
            $vst_sementara['tindakan_hd_id'] = $save_tindakan_hd_history;
            unset($vst_sementara['id']);
            $save_tindakan_visit_history = $this->tindakan_hd_visit_history_m->save($vst_sementara);
        }
        
        
        

        // $shift = $get_data_tindakan_hd->shift;
        $shift = 1;
        if(date('H:i:s') > '03:00:01' &&  date('H:i:s') <= '12:00:00'){
            $shift = 1;
        }if(date('H:i:s') > '12:00:01' &&  date('H:i:s') <= '18:30:00'){
            $shift = 2;
        }if(date('H:i:s') > '18:30:01' &&  date('H:i:s') <= '23:59:59'){
            $shift = 3;
        }if(date('H:i:s') > '00:00:01' &&  date('H:i:s') <= '03:00:00'){
            $shift = 3;
        }

        if($reuse_dializer == 1)
        {
            $item_digunakan = $this->tindakan_hd_item_m->get_sum_item($tindakan_hd_id)->result_array();
            if($assesment_data->dialyzer_new == 1 && $assesment_data->dialyzer_reuse == 0){
                if(count($item_digunakan))
                {
                    foreach ($item_digunakan as $gunakan) 
                    {
                        $max_id = $this->item_tersimpan_m->get_max_id()->result_array();
                        if($max_id == null)
                        {
                            $max_id = 1;
                        }
                        else
                        {
                            $max_id = $max_id[0]['max_id'] + 1;
                        }

                        $data_simpan_item = array(
                            'simpan_item_id'      => $max_id,
                            'pasien_id'           => $get_data_tindakan_hd->pasien_id,
                            'item_id'             => $gunakan['item_id'],
                            'item_satuan_id'      => $gunakan['item_satuan_id'],
                            'transaksi_simpan_id' => $save_tindakan_hd_history,
                            'tipe_transaksi'      => 1,
                            'jumlah'              => $gunakan['jumlah'],
                            '`index`'             => intval($gunakan['index']),
                            '`counter`'           => 0,
                            'kode_dialyzer'       => $gunakan['kode_dialyzer'],
                            'bn_sn_lot'       => $gunakan['bn_sn_lot'],
                            'expire_date'       => date('Y-m-d', strtotime($gunakan['expire_date'])),
                            'harga_beli'          => $gunakan['harga_beli'],
                            'harga_jual'          => $gunakan['harga_jual'],
                            'status'              => 1,
                            'status_reuse'        => 1,
                            'is_active'           => 1,
                            'created_by'          => $this->session->userdata('user_id'),
                            'created_date'        => date('Y-m-d H:i:s')
                        );
                        $simpan_item_id = $this->item_tersimpan_m->add_data($data_simpan_item);
                    }
                }
            }
            if($assesment_data->dialyzer_new == 0 && $assesment_data->dialyzer_reuse == 1){
                if(count($item_digunakan))
                {
                    foreach ($item_digunakan as $gunakan) {
                        $id_simpan_item =  0;
                        if($gunakan['tipe_pemberian'] == 2){
                            $id_simpan_item = $gunakan['transaksi_pemberian_id'];

                            $simpan_item = $this->item_tersimpan_m->get_by(array('simpan_item_id' => $id_simpan_item), true);
                            $data_simpan_item = array(
                                '`index`'       => intval($simpan_item->index) + 1,
                                '`counter`'     => intval($simpan_item->counter)
                            );

                            $wheres_simpan_item['simpan_item_id'] = $id_simpan_item;
                            $simpan_item_id = $this->item_tersimpan_m->update_by($this->session->userdata('user_id'), $data_simpan_item, $wheres_simpan_item);
                        }
                    }
                }
            }
        }

        if($get_data_tindakan_hd->penjamin_id != 1 && $assesment_data->dialyzer_new == 1 && $assesment_data->dialyzer_reuse == 0 && $assesment_data->reason == 0){
            $data_invoice = get_data_invoice(base_url(), $save_tindakan_hd_history, 1,2);
            $data_invoice_swasta = get_data_invoice(base_url(), $save_tindakan_hd_history, 1,1);
            $item_digunakan_bpjs = $this->tindakan_hd_item_m->get_item_bpjs($tindakan_hd_id);
            $item_digunakan_swasta = $this->tindakan_hd_item_m->get_item_swasta($tindakan_hd_id);

            if(count($data_invoice) == 0){
                $invoice_head_bpjs = array(
                    'no_invoice'      => get_max_no_invoice(base_url()),
                    'tindakan_id'     => $save_tindakan_hd_history,
                    'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                    'waktu_tindakan'  => $assesment_data->waktu,
                    'cabang_id'       => $this->session->userdata('cabang_id'),
                    'tipe_pasien'     => 1,
                    'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                    'nama_pasien'     => $data_pasien->nama,
                    'tipe'            => 1,
                    'jenis_invoice'   => 1,
                    'penjamin_id'     => 2,
                    'nama_penjamin'   => 'BPJS - JKN',
                    'poliklinik_id'   => 1,
                    'nama_poliklinik' => 'Poli HD',
                    'shift'           => $shift,
                    'status'          => 1,
                    'is_active'       => 1,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id_bpjs = insert_data_api($invoice_head_bpjs,base_url(),$path_model_inv);

                $invoice_id_bpjs = str_replace('"', '', $invoice_id_bpjs);

                $invoice_detail_bpjs = array(
                    'invoice_id' => $invoice_id_bpjs,
                    'item_id'    => 1,
                    'tipe_item'  => 1,
                    'qty'        => 1,
                    'harga'      => 1050000,
                    'tipe'       => 1,
                    'status'     => 1,
                    'is_active'  => 1
                );

                $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                // $core = $this->cabang_m->get(1);
                $invoice_detail_id = insert_data_api($invoice_detail_bpjs,base_url(),$path_model_inv_detail);
            
                $total_inv_bpjs = 1050000;

                if(count($item_digunakan_bpjs) != 0){
                    foreach ($item_digunakan_bpjs as $item_bpjs) {
                        $total_inv_bpjs = $total_inv_bpjs + ($item_bpjs['harga_jual'] * 1);
                        $invoice_detail_item_bpjs = array(
                            'invoice_id' => $invoice_id_bpjs,
                            'item_id'    => $item_bpjs['item_id'],
                            'satuan_id'  => $item_bpjs['item_satuan_id'],
                            'tipe_item'  => 2,
                            'qty'        => 1,
                            'harga'      => $item_bpjs['harga_jual'],
                            'tipe'       => 2,
                            'status'     => 1,
                            'is_active'  => 1
                        );

                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        $invoice_detail_id = insert_data_api($invoice_detail_item_bpjs,base_url(),$path_model_inv_detail);
                    }
                }

                $data_inv_bpjs = array(
                    'harga'      => $total_inv_bpjs,
                    'sisa_bayar' => $total_inv_bpjs,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($data_inv_bpjs,base_url(),$path_model_inv,$invoice_id_bpjs);
            }

            if(count($item_digunakan_swasta)){
                if(count($data_invoice_swasta) == 0){
                    $invoice_head = array(
                        'no_invoice'      => get_max_no_invoice(base_url()),
                        'tindakan_id'     => $save_tindakan_hd_history,
                        'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                        'waktu_tindakan'  => $assesment_data->waktu,
                        'cabang_id'       => $this->session->userdata('cabang_id'),
                        'tipe_pasien'     => 1,
                        'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                        'nama_pasien'     => $data_pasien->nama,
                        'tipe'            => 1,
                        'jenis_invoice'            => 1,
                        'penjamin_id'     => 1,
                        'nama_penjamin'   => $data_pasien->nama,
                        'poliklinik_id'   => 1,
                        'nama_poliklinik' => 'Poli HD',
                        'shift'           => $shift,
                        'status'          => 1,
                        'is_active'       => 1,
                    );

                    $path_model_inv = 'reservasi/invoice/invoice_m';
                    // $core = $this->cabang_m->get(1);
                    $invoice_id_swasta = insert_data_api($invoice_head,base_url(),$path_model_inv);

                    $invoice_id_swasta = str_replace('"', '', $invoice_id_swasta);

                    $total_inv_swasta = 0;
                    foreach ($item_digunakan_swasta as $item_swasta) {
                        $total_inv_swasta = $total_inv_swasta + ($item_swasta['harga_jual'] * $item_swasta['jumlah']);
                        $invoice_detail_item_swasta = array(
                            'invoice_id' => $invoice_id_swasta,
                            'item_id'    => $item_swasta['item_id'],
                            'satuan_id'    => $item_swasta['item_satuan_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_swasta['jumlah'],
                            'harga'      => $item_swasta['harga_jual'],
                            'tipe'       => 2,
                            'status'     => 1,
                            'is_active'  => 1
                        );

                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        $invoice_detail_id = insert_data_api($invoice_detail_item_swasta,base_url(),$path_model_inv_detail);
                    }

                    foreach ($item_digunakan_bpjs as $item_bpjs) {
                        if($item_bpjs['jumlah'] > 1){
                            $total_inv_swasta = $total_inv_swasta + ($item_bpjs['harga_jual'] * (intval($item_bpjs['jumlah']) - 1));
                            $invoice_detail_item_bpjs = array(
                                'invoice_id' => $invoice_id_swasta,
                                'item_id'    => $item_bpjs['item_id'],
                                'satuan_id'    => $item_bpjs['item_satuan_id'],
                                'tipe_item'  => 2,
                                'qty'        => intval($item_bpjs['jumlah']) - 1,
                                'harga'      => $item_bpjs['harga_jual'],
                                'tipe'       => 2,
                                'status'     => 1,
                                'is_active'  => 1
                            );

                            $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                            $invoice_detail_id = insert_data_api($invoice_detail_item_bpjs,base_url(),$path_model_inv_detail);
                        }
                    }

                    $data_inv_swasta = array(
                        'harga'      => $total_inv_swasta,
                        'sisa_bayar' => $total_inv_swasta,
                    );

                    $path_model_inv = 'reservasi/invoice/invoice_m';
                    // $core = $this->cabang_m->get(1);
                    $invoice_id = insert_data_api($data_inv_swasta,base_url(),$path_model_inv,$invoice_id_swasta);
                }
            }
        }

        if($get_data_tindakan_hd->penjamin_id != 1 && $assesment_data->dialyzer_new == 1 && $assesment_data->dialyzer_reuse == 0 && $assesment_data->reason == 1){
            $data_invoice = get_data_invoice(base_url(), $save_tindakan_hd_history, 1,2);
            $data_invoice_swasta = get_data_invoice(base_url(), $save_tindakan_hd_history, 1,1);
            $item_digunakan_bpjs = $this->tindakan_hd_item_m->get_item_bpjs($tindakan_hd_id);
            $item_digunakan_swasta = $this->tindakan_hd_item_m->get_item_swasta($tindakan_hd_id);

            if(count($data_invoice) == 0){
                $invoice_head_bpjs = array(
                    'no_invoice'      => get_max_no_invoice(base_url()),
                    'tindakan_id'     => $save_tindakan_hd_history,
                    'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                    'waktu_tindakan'  => $assesment_data->waktu,
                    'cabang_id'       => $this->session->userdata('cabang_id'),
                    'tipe_pasien'     => 1,
                    'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                    'nama_pasien'     => $data_pasien->nama,
                    'tipe'            => 1,
                    'jenis_invoice'   => 1,
                    'penjamin_id'     => 2,
                    'nama_penjamin'   => 'BPJS - JKN',
                    'poliklinik_id'   => 1,
                    'nama_poliklinik' => 'Poli HD',
                    'shift'           => $shift,
                    'status'          => 1,
                    'is_active'       => 1,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id_bpjs = insert_data_api($invoice_head_bpjs,base_url(),$path_model_inv);

                $invoice_id_bpjs = str_replace('"', '', $invoice_id_bpjs);

                $invoice_detail_bpjs = array(
                    'invoice_id' => $invoice_id_bpjs,
                    'item_id'    => 1,
                    'tipe_item'  => 1,
                    'qty'        => 1,
                    'harga'      => 1050000,
                    'tipe'       => 1,
                    'status'     => 1,
                    'is_active'  => 1
                );

                $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                // $core = $this->cabang_m->get(1);
                $invoice_detail_id = insert_data_api($invoice_detail_bpjs,base_url(),$path_model_inv_detail);
            
                $total_inv_bpjs = 1050000;

                if(count($item_digunakan_bpjs) != 0){
                    foreach ($item_digunakan_bpjs as $item_bpjs) {
                        $total_inv_bpjs = $total_inv_bpjs + ($item_bpjs['harga_jual'] * 1);
                        $invoice_detail_item_bpjs = array(
                            'invoice_id' => $invoice_id_bpjs,
                            'item_id'    => $item_bpjs['item_id'],
                            'satuan_id'  => $item_bpjs['item_satuan_id'],
                            'tipe_item'  => 2,
                            'qty'        => 1,
                            'harga'      => $item_bpjs['harga_jual'],
                            'tipe'       => 2,
                            'status'     => 1,
                            'is_active'  => 1
                        );

                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        $invoice_detail_id = insert_data_api($invoice_detail_item_bpjs,base_url(),$path_model_inv_detail);
                    }
                }

                $data_inv_bpjs = array(
                    'harga'      => $total_inv_bpjs,
                    'sisa_bayar' => $total_inv_bpjs,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($data_inv_bpjs,base_url(),$path_model_inv,$invoice_id_bpjs);
            }

            if(count($item_digunakan_swasta)){
                if(count($data_invoice_swasta) == 0){
                    $invoice_head = array(
                        'no_invoice'      => get_max_no_invoice(base_url()),
                        'tindakan_id'     => $save_tindakan_hd_history,
                        'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                        'waktu_tindakan'  => $assesment_data->waktu,
                        'cabang_id'       => $this->session->userdata('cabang_id'),
                        'tipe_pasien'     => 1,
                        'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                        'nama_pasien'     => $data_pasien->nama,
                        'tipe'            => 1,
                        'jenis_invoice'            => 1,
                        'penjamin_id'     => 1,
                        'nama_penjamin'   => $data_pasien->nama,
                        'poliklinik_id'   => 1,
                        'nama_poliklinik' => 'Poli HD',
                        'shift'           => $shift,
                        'status'          => 1,
                        'is_active'       => 1,
                    );

                    $path_model_inv = 'reservasi/invoice/invoice_m';
                    // $core = $this->cabang_m->get(1);
                    $invoice_id_swasta = insert_data_api($invoice_head,base_url(),$path_model_inv);

                    $invoice_id_swasta = str_replace('"', '', $invoice_id_swasta);

                    $total_inv_swasta = 0;
                    foreach ($item_digunakan_swasta as $item_swasta) {
                        $total_inv_swasta = $total_inv_swasta + ($item_swasta['harga_jual'] * $item_swasta['jumlah']);
                        $invoice_detail_item_swasta = array(
                            'invoice_id' => $invoice_id_swasta,
                            'item_id'    => $item_swasta['item_id'],
                            'satuan_id'    => $item_swasta['item_satuan_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_swasta['jumlah'],
                            'harga'      => $item_swasta['harga_jual'],
                            'tipe'       => 2,
                            'status'     => 1,
                            'is_active'  => 1
                        );

                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        $invoice_detail_id = insert_data_api($invoice_detail_item_swasta,base_url(),$path_model_inv_detail);
                    }

                    foreach ($item_digunakan_bpjs as $item_bpjs) {
                        if($item_bpjs['jumlah'] > 1){
                            $total_inv_swasta = $total_inv_swasta + ($item_bpjs['harga_jual'] * (intval($item_bpjs['jumlah']) - 1));
                            $invoice_detail_item_bpjs = array(
                                'invoice_id' => $invoice_id_swasta,
                                'item_id'    => $item_bpjs['item_id'],
                                'satuan_id'    => $item_bpjs['item_satuan_id'],
                                'tipe_item'  => 2,
                                'qty'        => intval($item_bpjs['jumlah']) - 1,
                                'harga'      => $item_bpjs['harga_jual'],
                                'tipe'       => 2,
                                'status'     => 1,
                                'is_active'  => 1
                            );

                            $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                            $invoice_detail_id = insert_data_api($invoice_detail_item_bpjs,base_url(),$path_model_inv_detail);
                        }
                    }

                    $data_inv_swasta = array(
                        'harga'      => $total_inv_swasta,
                        'sisa_bayar' => $total_inv_swasta,
                    );

                    $path_model_inv = 'reservasi/invoice/invoice_m';
                    // $core = $this->cabang_m->get(1);
                    $invoice_id = insert_data_api($data_inv_swasta,base_url(),$path_model_inv,$invoice_id_swasta);
                }
            }
        }

        if($get_data_tindakan_hd->penjamin_id != 1 && $assesment_data->dialyzer_new == 1 && $assesment_data->dialyzer_reuse == 0 && $assesment_data->reason == 2){
            $data_invoice = get_data_invoice(base_url(), $save_tindakan_hd_history, 1,2);
            $data_invoice_swasta = get_data_invoice(base_url(), $save_tindakan_hd_history, 1,1);
            $item_digunakan_bpjs = $this->tindakan_hd_item_m->get_item_bpjs($tindakan_hd_id);
            $item_digunakan_swasta = $this->tindakan_hd_item_m->get_item_swasta($tindakan_hd_id);

            if(count($data_invoice) == 0){
                $invoice_head_bpjs = array(
                    'no_invoice'      => get_max_no_invoice(base_url()),
                    'tindakan_id'     => $save_tindakan_hd_history,
                    'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                    'waktu_tindakan'  => $assesment_data->waktu,
                    'cabang_id'       => $this->session->userdata('cabang_id'),
                    'tipe_pasien'     => 1,
                    'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                    'nama_pasien'     => $data_pasien->nama,
                    'tipe'            => 1,
                    'jenis_invoice'   => 1,
                    'penjamin_id'     => 2,
                    'nama_penjamin'   => 'BPJS - JKN',
                    'poliklinik_id'   => 1,
                    'nama_poliklinik' => 'Poli HD',
                    'shift'           => $shift,
                    'status'          => 1,
                    'is_active'       => 1,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id_bpjs = insert_data_api($invoice_head_bpjs,base_url(),$path_model_inv);

                $invoice_id_bpjs = str_replace('"', '', $invoice_id_bpjs);

                $invoice_detail_bpjs = array(
                    'invoice_id' => $invoice_id_bpjs,
                    'item_id'    => 1,
                    'tipe_item'  => 1,
                    'qty'        => 1,
                    'harga'      => 900000,
                    'tipe'       => 1,
                    'status'     => 1,
                    'is_active'  => 1
                );

                $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                // $core = $this->cabang_m->get(1);
                $invoice_detail_id = insert_data_api($invoice_detail_bpjs,base_url(),$path_model_inv_detail);
            
                $total_inv_bpjs = 900000;

                if(count($item_digunakan_bpjs) != 0){
                    foreach ($item_digunakan_bpjs as $item_bpjs) {
                        $total_inv_bpjs = $total_inv_bpjs + ($item_bpjs['harga_jual'] * 1);
                        $invoice_detail_item_bpjs = array(
                            'invoice_id' => $invoice_id_bpjs,
                            'item_id'    => $item_bpjs['item_id'],
                            'satuan_id'  => $item_bpjs['item_satuan_id'],
                            'tipe_item'  => 2,
                            'qty'        => 1,
                            'harga'      => $item_bpjs['harga_jual'],
                            'tipe'       => 2,
                            'status'     => 1,
                            'is_active'  => 1
                        );

                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        $invoice_detail_id = insert_data_api($invoice_detail_item_bpjs,base_url(),$path_model_inv_detail);
                    }
                }

                $data_inv_bpjs = array(
                    'harga'      => $total_inv_bpjs,
                    'sisa_bayar' => $total_inv_bpjs,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($data_inv_bpjs,base_url(),$path_model_inv,$invoice_id_bpjs);
            }

            if(count($data_invoice_swasta) == 0){
                $invoice_head = array(
                    'no_invoice'      => get_max_no_invoice(base_url()),
                    'tindakan_id'     => $save_tindakan_hd_history,
                    'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                    'waktu_tindakan'  => $assesment_data->waktu,
                    'cabang_id'       => $this->session->userdata('cabang_id'),
                    'tipe_pasien'     => 1,
                    'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                    'nama_pasien'     => $data_pasien->nama,
                    'tipe'            => 1,
                    'jenis_invoice'            => 1,
                    'penjamin_id'     => 1,
                    'nama_penjamin'   => $data_pasien->nama,
                    'poliklinik_id'   => 1,
                    'nama_poliklinik' => 'Poli HD',
                    'shift'           => $shift,
                    'status'          => 1,
                    'is_active'       => 1,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id_swasta = insert_data_api($invoice_head,base_url(),$path_model_inv);

                $invoice_id_swasta = str_replace('"', '', $invoice_id_swasta);
                $total_inv_swasta = 0;

                $item_digunakan_dialyzer = $this->tindakan_hd_item_m->get_item_dialyzer_new($tindakan_hd_id);
                if(count($item_digunakan_dialyzer)){
                    foreach ($item_digunakan_dialyzer as $item_pakai) {
                        $total_inv_swasta = $total_inv_swasta + ($item_pakai['harga_jual'] * $item_pakai['jumlah']);
                        $invoice_detail_item = array(
                            'invoice_id' => $invoice_id_swasta,
                            'item_id'    => $item_pakai['item_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_pakai['jumlah'],
                            'harga'      => $item_pakai['harga_jual'],
                            'tipe'       => 3,
                            'status'     => 1,
                            'is_active'  => 1
                        );
                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        // $core = $this->cabang_m->get(1);
                        $invoice_detail_id = insert_data_api($invoice_detail_item,base_url(),$path_model_inv_detail);
                           
                    }
                }

                if(count($item_digunakan_swasta)){

                    foreach ($item_digunakan_swasta as $item_swasta) {
                        $total_inv_swasta = $total_inv_swasta + ($item_swasta['harga_jual'] * $item_swasta['jumlah']);
                        $invoice_detail_item_swasta = array(
                            'invoice_id' => $invoice_id_swasta,
                            'item_id'    => $item_swasta['item_id'],
                            'satuan_id'    => $item_swasta['item_satuan_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_swasta['jumlah'],
                            'harga'      => $item_swasta['harga_jual'],
                            'tipe'       => 2,
                            'status'     => 1,
                            'is_active'  => 1
                        );

                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        $invoice_detail_id = insert_data_api($invoice_detail_item_swasta,base_url(),$path_model_inv_detail);
                    }

                    foreach ($item_digunakan_bpjs as $item_bpjs) {
                        if($item_bpjs['jumlah'] > 1){
                            $total_inv_swasta = $total_inv_swasta + ($item_bpjs['harga_jual'] * (intval($item_bpjs['jumlah']) - 1));
                            $invoice_detail_item_bpjs = array(
                                'invoice_id' => $invoice_id_swasta,
                                'item_id'    => $item_bpjs['item_id'],
                                'satuan_id'    => $item_bpjs['item_satuan_id'],
                                'tipe_item'  => 2,
                                'qty'        => intval($item_bpjs['jumlah']) - 1,
                                'harga'      => $item_bpjs['harga_jual'],
                                'tipe'       => 2,
                                'status'     => 1,
                                'is_active'  => 1
                            );

                            $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                            $invoice_detail_id = insert_data_api($invoice_detail_item_bpjs,base_url(),$path_model_inv_detail);
                        }
                    }
                }
                $data_inv_swasta_bpjs = array(
                    'harga'      => $total_inv_swasta,
                    'sisa_bayar' => $total_inv_swasta,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($data_inv_swasta_bpjs,base_url(),$path_model_inv,$invoice_id_swasta);
            }
        }

        if($get_data_tindakan_hd->penjamin_id != 1 && $assesment_data->dialyzer_new == 0 && $assesment_data->dialyzer_reuse == 1 ){
            $data_invoice = get_data_invoice(base_url(), $save_tindakan_hd_history, 1,2);
            $data_invoice_swasta = get_data_invoice(base_url(), $save_tindakan_hd_history, 1,1);
            $item_digunakan_bpjs = $this->tindakan_hd_item_m->get_item_bpjs($tindakan_hd_id);
            $item_digunakan_swasta = $this->tindakan_hd_item_m->get_item_swasta($tindakan_hd_id);

            if(count($data_invoice) == 0){
                $invoice_head_bpjs = array(
                    'no_invoice'      => get_max_no_invoice(base_url()),
                    'tindakan_id'     => $save_tindakan_hd_history,
                    'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                    'waktu_tindakan'  => $assesment_data->waktu,
                    'cabang_id'       => $this->session->userdata('cabang_id'),
                    'tipe_pasien'     => 1,
                    'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                    'nama_pasien'     => $data_pasien->nama,
                    'tipe'            => 1,
                    'jenis_invoice'   => 1,
                    'penjamin_id'     => 2,
                    'nama_penjamin'   => 'BPJS - JKN',
                    'poliklinik_id'   => 1,
                    'nama_poliklinik' => 'Poli HD',
                    'shift'           => $shift,
                    'status'          => 1,
                    'is_active'       => 1,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id_bpjs = insert_data_api($invoice_head_bpjs,base_url(),$path_model_inv);

                $invoice_id_bpjs = str_replace('"', '', $invoice_id_bpjs);

                $invoice_detail_bpjs = array(
                    'invoice_id' => $invoice_id_bpjs,
                    'item_id'    => 1,
                    'tipe_item'  => 1,
                    'qty'        => 1,
                    'harga'      => 900000,
                    'tipe'       => 1,
                    'status'     => 1,
                    'is_active'  => 1
                );

                $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                // $core = $this->cabang_m->get(1);
                $invoice_detail_id = insert_data_api($invoice_detail_bpjs,base_url(),$path_model_inv_detail);
            
                $total_inv_bpjs = 900000;

                if(count($item_digunakan_bpjs) != 0){
                    foreach ($item_digunakan_bpjs as $item_bpjs) {
                        $total_inv_bpjs = $total_inv_bpjs + ($item_bpjs['harga_jual'] * 1);
                        $invoice_detail_item_bpjs = array(
                            'invoice_id' => $invoice_id_bpjs,
                            'item_id'    => $item_bpjs['item_id'],
                            'satuan_id'  => $item_bpjs['item_satuan_id'],
                            'tipe_item'  => 2,
                            'qty'        => 1,
                            'harga'      => $item_bpjs['harga_jual'],
                            'tipe'       => 2,
                            'status'     => 1,
                            'is_active'  => 1
                        );

                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        $invoice_detail_id = insert_data_api($invoice_detail_item_bpjs,base_url(),$path_model_inv_detail);
                    }
                }

                $data_inv_bpjs = array(
                    'harga'      => $total_inv_bpjs,
                    'sisa_bayar' => $total_inv_bpjs,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($data_inv_bpjs,base_url(),$path_model_inv,$invoice_id_bpjs);
            }

            if(count($item_digunakan_swasta)){
                if(count($data_invoice_swasta) == 0){
                    $invoice_head = array(
                        'no_invoice'      => get_max_no_invoice(base_url()),
                        'tindakan_id'     => $save_tindakan_hd_history,
                        'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                        'waktu_tindakan'  => $assesment_data->waktu,
                        'cabang_id'       => $this->session->userdata('cabang_id'),
                        'tipe_pasien'     => 1,
                        'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                        'nama_pasien'     => $data_pasien->nama,
                        'tipe'            => 1,
                        'jenis_invoice'            => 1,
                        'penjamin_id'     => 1,
                        'nama_penjamin'   => $data_pasien->nama,
                        'poliklinik_id'   => 1,
                        'nama_poliklinik' => 'Poli HD',
                        'shift'           => $shift,
                        'status'          => 1,
                        'is_active'       => 1,
                    );

                    $path_model_inv = 'reservasi/invoice/invoice_m';
                    // $core = $this->cabang_m->get(1);
                    $invoice_id_swasta = insert_data_api($invoice_head,base_url(),$path_model_inv);

                    $invoice_id_swasta = str_replace('"', '', $invoice_id_swasta);

                    $total_inv_swasta = 0;
                    foreach ($item_digunakan_swasta as $item_swasta) {
                        $total_inv_swasta = $total_inv_swasta + ($item_swasta['harga_jual'] * $item_swasta['jumlah']);
                        $invoice_detail_item_swasta = array(
                            'invoice_id' => $invoice_id_swasta,
                            'item_id'    => $item_swasta['item_id'],
                            'satuan_id'    => $item_swasta['item_satuan_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_swasta['jumlah'],
                            'harga'      => $item_swasta['harga_jual'],
                            'tipe'       => 2,
                            'status'     => 1,
                            'is_active'  => 1
                        );

                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        $invoice_detail_id = insert_data_api($invoice_detail_item_swasta,base_url(),$path_model_inv_detail);
                    }

                    foreach ($item_digunakan_bpjs as $item_bpjs) {
                        if($item_bpjs['jumlah'] > 1){
                            $total_inv_swasta = $total_inv_swasta + ($item_bpjs['harga_jual'] * (intval($item_bpjs['jumlah']) - 1));
                            $invoice_detail_item_bpjs = array(
                                'invoice_id' => $invoice_id_swasta,
                                'item_id'    => $item_bpjs['item_id'],
                                'satuan_id'    => $item_bpjs['item_satuan_id'],
                                'tipe_item'  => 2,
                                'qty'        => intval($item_bpjs['jumlah']) - 1,
                                'harga'      => $item_bpjs['harga_jual'],
                                'tipe'       => 2,
                                'status'     => 1,
                                'is_active'  => 1
                            );

                            $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                            $invoice_detail_id = insert_data_api($invoice_detail_item_bpjs,base_url(),$path_model_inv_detail);
                        }
                    }

                    $data_inv_swasta = array(
                        'harga'      => $total_inv_swasta,
                        'sisa_bayar' => $total_inv_swasta,
                    );

                    $path_model_inv = 'reservasi/invoice/invoice_m';
                    // $core = $this->cabang_m->get(1);
                    $invoice_id = insert_data_api($data_inv_swasta,base_url(),$path_model_inv,$invoice_id_swasta);
                }
            }
        }

            

        if($get_data_tindakan_hd->penjamin_id == 1 && $assesment_data->dialyzer_new == 1 && $assesment_data->dialyzer_reuse == 0 && $assesment_data->reason == 1){
            $data_invoice = get_data_invoice(base_url(), $tindakan_hd_id, 1,1);
            if(count($data_invoice) == 0){
                $invoice_head = array(
                    'no_invoice'      => get_max_no_invoice(base_url()),
                    'tindakan_id'     => $save_tindakan_hd_history,
                    'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                    'waktu_tindakan'  => $assesment_data->waktu,
                    'cabang_id'       => $this->session->userdata('cabang_id'),
                    'tipe_pasien'     => 1,
                    'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                    'nama_pasien'     => $data_pasien->nama,
                    'tipe'            => 1,
                    'jenis_invoice'   => 1,
                    'penjamin_id'     => 1,
                    'nama_penjamin'   => $data_pasien->nama,
                    'poliklinik_id'   => 1,
                    'nama_poliklinik' => 'Poli HD',
                    'shift'           => $shift,
                    'status'          => 1,
                    'is_active'       => 1,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($invoice_head,base_url(),$path_model_inv);

                $invoice_id = str_replace('"', '', $invoice_id);

                $invoice_detail = array(
                    'invoice_id' => $invoice_id,
                    'item_id'    => 1,
                    'tipe_item'  => 1,
                    'qty'        => 1,
                    'harga'      => 1050000,
                    'tipe'       => 1,
                    'status'     => 1,
                    'is_active'  => 1
                );

                $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                // $core = $this->cabang_m->get(1);
                $invoice_detail_id = insert_data_api($invoice_detail,base_url(),$path_model_inv_detail);
            
                $total_inv = 1050000;
                $item_digunakan_all = $this->tindakan_hd_item_m->get_item_not_dialyzer($tindakan_hd_id);
                if(count($item_digunakan_all)){
                    foreach ($item_digunakan_all as $item_pakai) {

                        $data_item = $this->item_m->get($item_pakai['item_id']);
                        $tipe = 1;
                        if(in_array($data_item->item_sub_kategori, config_item('penunjang_medis'))){
                            $tipe = 3;
                        }if(in_array($data_item->item_sub_kategori, config_item('obat_vitamin'))){
                            $tipe = 2;
                        }

                        $total_inv = $total_inv + ($item_pakai['harga_jual'] * $item_pakai['jumlah']);
                        $invoice_detail_item = array(
                            'invoice_id' => $invoice_id,
                            'item_id'    => $item_pakai['item_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_pakai['jumlah'],
                            'harga'      => $item_pakai['harga_jual'],
                            'tipe'       => $tipe,
                            'status'     => 1,
                            'is_active'  => 1
                        );
                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        // $core = $this->cabang_m->get(1);
                        $invoice_detail_id = insert_data_api($invoice_detail_item,base_url(),$path_model_inv_detail);
                           
                    }
                }

                $invoice_head = array(
                    'harga'      => $total_inv,
                    'sisa_bayar' => $total_inv,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($invoice_head,base_url(),$path_model_inv,$invoice_id);
            }
        }

        if($get_data_tindakan_hd->penjamin_id == 1 && $assesment_data->dialyzer_new == 1 && $assesment_data->dialyzer_reuse == 0 && $assesment_data->reason == 2){
            $data_invoice = get_data_invoice(base_url(), $tindakan_hd_id, 1,1);

            if(count($data_invoice) == 0){
                $invoice_head = array(
                    'no_invoice'      => get_max_no_invoice(base_url()),
                    'tindakan_id'     => $save_tindakan_hd_history,
                    'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                    'waktu_tindakan'  => $assesment_data->waktu,
                    'cabang_id'       => $this->session->userdata('cabang_id'),
                    'tipe_pasien'     => 1,
                    'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                    'nama_pasien'     => $data_pasien->nama,
                    'tipe'            => 1,
                    'jenis_invoice'   => 1,
                    'penjamin_id'     => 1,
                    'nama_penjamin'   => $data_pasien->nama,
                    'poliklinik_id'   => 1,
                    'nama_poliklinik' => 'Poli HD',
                    'shift'           => $shift,
                    'status'          => 1,
                    'is_active'       => 1,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($invoice_head,base_url(),$path_model_inv);

                $invoice_id = str_replace('"', '', $invoice_id);

                $invoice_detail = array(
                    'invoice_id' => $invoice_id,
                    'item_id'    => 1,
                    'tipe_item'  => 1,
                    'qty'        => 1,
                    'harga'      => 1050000,
                    'tipe'       => 1,
                    'status'     => 1,
                    'is_active'  => 1
                );

                $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                // $core = $this->cabang_m->get(1);
                $invoice_detail_id = insert_data_api($invoice_detail,base_url(),$path_model_inv_detail);

                $total_inv = 1050000;
                $item_digunakan_dialyzer = $this->tindakan_hd_item_m->get_item_dialyzer_new($tindakan_hd_id);
                if(count($item_digunakan_dialyzer)){
                    foreach ($item_digunakan_dialyzer as $item_pakai) {
                        $total_inv = $total_inv + ($item_pakai['harga_jual'] * $item_pakai['jumlah']);
                        $invoice_detail_item = array(
                            'invoice_id' => $invoice_id,
                            'item_id'    => $item_pakai['item_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_pakai['jumlah'],
                            'harga'      => $item_pakai['harga_jual'],
                            'tipe'       => 3,
                            'status'     => 1,
                            'is_active'  => 1
                        );
                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        // $core = $this->cabang_m->get(1);
                        $invoice_detail_id = insert_data_api($invoice_detail_item,base_url(),$path_model_inv_detail);
                           
                    }
                }
                $item_digunakan_all = $this->tindakan_hd_item_m->get_item_not_dialyzer($tindakan_hd_id);
                if(count($item_digunakan_all)){
                    foreach ($item_digunakan_all as $item_pakai) {

                        $data_item = $this->item_m->get($item_pakai['item_id']);
                        $tipe = 1;
                        if(in_array($data_item->item_sub_kategori, config_item('penunjang_medis'))){
                            $tipe = 3;
                        }if(in_array($data_item->item_sub_kategori, config_item('obat_vitamin'))){
                            $tipe = 2;
                        }

                        $total_inv = $total_inv + ($item_pakai['harga_jual'] * $item_pakai['jumlah']);
                        $invoice_detail_item = array(
                            'invoice_id' => $invoice_id,
                            'item_id'    => $item_pakai['item_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_pakai['jumlah'],
                            'harga'      => $item_pakai['harga_jual'],
                            'tipe'       => $tipe,
                            'status'     => 1,
                            'is_active'  => 1
                        );
                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        // $core = $this->cabang_m->get(1);
                        $invoice_detail_id = insert_data_api($invoice_detail_item,base_url(),$path_model_inv_detail);
                           
                    }
                }

                $invoice_head = array(
                    'harga'      => $total_inv,
                    'sisa_bayar' => $total_inv,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($invoice_head,base_url(),$path_model_inv,$invoice_id);
            }
        }  
            
        if($get_data_tindakan_hd->penjamin_id == 1 && $assesment_data->dialyzer_new == 0 && $assesment_data->dialyzer_reuse == 1){
            $data_invoice = get_data_invoice(base_url(), $tindakan_hd_id, 1,1);
            if(count($data_invoice) == 0){
                $invoice_head = array(
                    'no_invoice'      => get_max_no_invoice(base_url()),
                    'tindakan_id'     => $save_tindakan_hd_history,
                    'no_tindakan'     => $get_data_tindakan_hd->no_transaksi,
                    'waktu_tindakan'  => $assesment_data->waktu,
                    'cabang_id'       => $this->session->userdata('cabang_id'),
                    'tipe_pasien'     => 1,
                    'pasien_id'       => $get_data_tindakan_hd->pasien_id,
                    'nama_pasien'     => $data_pasien->nama,
                    'tipe'            => 1,
                    'jenis_invoice'            => 1,
                    'penjamin_id'     => 1,
                    'nama_penjamin'   => $data_pasien->nama,
                    'poliklinik_id'   => 1,
                    'nama_poliklinik' => 'Poli HD',
                    'shift'           => $shift,
                    'status'          => 1,
                    'is_active'       => 1,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($invoice_head,base_url(),$path_model_inv);

                $invoice_id = str_replace('"', '', $invoice_id);

                $invoice_detail = array(
                    'invoice_id' => $invoice_id,
                    'item_id'    => 1,
                    'tipe_item'  => 1,
                    'qty'        => 1,
                    'harga'      => 900000,
                    'tipe'       => 1,
                    'status'     => 1,
                    'is_active'  => 1
                );

                $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                // $core = $this->cabang_m->get(1);
                $invoice_detail_id = insert_data_api($invoice_detail,base_url(),$path_model_inv_detail);
            
                $total_inv = 900000;
                $item_digunakan_non_dializer = $this->tindakan_hd_item_m->get_item_not_dialyzer($tindakan_hd_id);
                if(count($item_digunakan_non_dializer)){
                    foreach ($item_digunakan_non_dializer as $item_pakai) {

                        $data_item = $this->item_m->get($item_pakai['item_id']);
                        $tipe = 1;
                        if(in_array($data_item->item_sub_kategori, config_item('penunjang_medis'))){
                            $tipe = 3;
                        }if(in_array($data_item->item_sub_kategori, config_item('obat_vitamin'))){
                            $tipe = 2;
                        }

                        $total_inv = $total_inv + ($item_pakai['harga_jual'] * $item_pakai['jumlah']);

                        $invoice_detail = array(
                            'invoice_id' => $invoice_id,
                            'item_id'    => $item_pakai['item_id'],
                            'tipe_item'  => 2,
                            'qty'        => $item_pakai['jumlah'],
                            'harga'      => $item_pakai['harga_jual'],
                            'tipe'       => $tipe,
                            'status'     => 1,
                            'is_active'  => 1
                        );
                        $path_model_inv_detail = 'reservasi/invoice/invoice_detail_m';
                        // $core = $this->cabang_m->get(1);
                        $invoice_detail_id = insert_data_api($invoice_detail,base_url(),$path_model_inv_detail);
                           
                    }
                }

                $invoice_head = array(
                    'harga'      => $total_inv,
                    'sisa_bayar' => $total_inv,
                );

                $path_model_inv = 'reservasi/invoice/invoice_m';
                // $core = $this->cabang_m->get(1);
                $invoice_id = insert_data_api($invoice_head,base_url(),$path_model_inv,$invoice_id);
            }
        }
        
        // INSERT TINDAKAN_HD_SURVEY
        foreach ($pertanyaan as $data_pertanyaan) 
        {
            $data_tindakan_hd_survey = array(
                'tindakan_hd_id'       => $save_tindakan_hd_history,
                'pertanyaan_survey_id' => $data_pertanyaan['survey_id'],
                'nilai'                => $data_pertanyaan['nilai'],
            );            
            $tindakan_hd_survey_id = $this->tindakan_hd_survey_history_m->save($data_tindakan_hd_survey);
        }    

        $cabang = $this->cabang_m->get(1);
        $data_status_pendaftaran_tindakan['status']=3;

        $edit_daftar = $this->pendaftaran_tindakan_history_m->save($data_status_pendaftaran_tindakan, $tindakan_hd_sementara['pendaftaran_tindakan_id']);

        // update_pendaftaran($data_status_pendaftaran_tindakan,base_url(),$get_data_tindakan_hd->pendaftaran_tindakan_id);
        // update_pendaftaran($data_status_pendaftaran_tindakan,$cabang->url,$get_data_tindakan_hd->pendaftaran_tindakan_id);

        $delete_observasi = $this->observasi_m->delete_by(array('transaksi_hd_id' => $tindakan_hd_id));
        $delete_komplikasi = $this->pasien_komplikasi_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_problem = $this->pasien_problem_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_diagnosa = $this->tindakan_hd_diagnosa_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_item = $this->tindakan_hd_item_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_note = $this->tindakan_hd_note_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_penaksiran = $this->tindakan_hd_penaksiran_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_visit = $this->tindakan_hd_visit_m->delete_by(array('tindakan_hd_id' => $tindakan_hd_id));
        $delete_tindakan = $this->tindakan_hd_m->delete_by(array('id' => $tindakan_hd_id));


        if ($update_berat_akhir && $update_assessment_cgs && $tindakan_hd_survey_id) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data selesai tindakan telah ditambahkan", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
        }

        $lantai = $this->bed_m->get($bed_id);
        redirect("klinik_hd/transaksi_perawat#lantai".$lantai->lantai_id);

    }

    public function modal_view_item($tindakan_hd_item_id)
    {
        $data = array(
            'tindakan_hd_item_id' => $tindakan_hd_item_id
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_view_item', $data);
    }

    public function delete_item_digunakan2()
    {
        $id = $this->input->post("id");
        $tindakan_id = $this->input->post("tindakan_id");

        $item_digunakan = $this->tindakan_hd_item_m->get_by(array('id' => $id), true);
        $item_digunakan = object_to_array($item_digunakan);

        $data_tindakan = $this->tindakan_hd_m->get_by(array('id' => $tindakan_id), true);
        $data_bed = $this->bed_m->get($data_tindakan->bed_id);

        if($item_digunakan['tipe_pemberian'] == 3){
            $data_inventory_history = array(

                'transaksi_id'  =>  $tindakan_id,
                'transaksi_tipe' => 5,

            );

            $inventory_history_id = $this->inventory_history_m->save($data_inventory_history);

            $last_inv_tujuan_id = $this->inventory_m->get_last_id()->result_array();
            $last_inv_tujuan_id = intval($last_inv_tujuan_id[0]['inventory_id']) + 1;

            $data_inventory_tujuan = array(
                'inventory_id'        => $last_inv_tujuan_id,
                'gudang_id'           => ($data_bed->lantai_id) + 1,
                'item_id'             => $item_digunakan['item_id'],
                'item_satuan_id'      => $item_digunakan['item_satuan_id'],
                'jumlah'              => $item_digunakan['jumlah'],
                'tanggal_datang'      => date('Y-m-d H:i:s'),
                'harga_beli'          => $item_digunakan['harga_beli'],
                'bn_sn_lot'           => $item_digunakan['bn_sn_lot'],
                'expire_date'         => $item_digunakan['expire_date'],
                'created_by'          => $this->session->userdata('user_id'),
                'created_date'        => date('Y-m-d H:i:s')
            );

            $inv_tujuan = $this->inventory_m->add_data($data_inventory_tujuan);

            $data_inventory_history_detail = array(
                'inventory_history_id' => $inventory_history_id,
                'gudang_id'            => ($data_bed->lantai_id) + 1,
                'item_id'             => $item_digunakan['item_id'],
                'item_satuan_id'      => $item_digunakan['item_satuan_id'],
                'initial_stock'        => 0,
                'change_stock'         => $item_digunakan['jumlah'],
                'final_stock'          => $item_digunakan['jumlah'],
                'harga_beli'           => $item_digunakan['harga_beli'],
                'total_harga'          => $item_digunakan['jumlah'] * $item_digunakan['harga_beli'],
                'bn_sn_lot'            => $item_digunakan['bn_sn_lot'],
                'expire_date'          => $item_digunakan['expire_date'],
                'created_by'           => $this->session->userdata('user_id'),
                'created_date'         => date('Y-m-d H:i:s')
            );

            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);
        }
        

        $data_tindakan_hd_item['is_active'] = 0;
        $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item, $id);


        if ($tindakan_hd_item_id) 
        {
            $flashdata = array(
                "error",
                translate("Item berhasil dihapus", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
        
    }

    public function delete_item_digunakan()
    {
        $id = $this->input->post("id");
        $tindakan_id = $this->input->post("tindakan_id");
        $item_id = $this->input->post("item_id");
        $bn = $this->input->post("bn");

        $data_tindakan_hd_item['is_active'] = 0;
        $tindakan_hd_item_id = $this->tindakan_hd_item_m->save($data_tindakan_hd_item, $id);

        $tindakan_hd_resep_detail = $this->tindakan_resep_obat_detail_m->get_by(array('tindakan_id' => $tindakan_id, 'item_id' => $item_id, 'bawa_pulang' => 0));

        foreach ($tindakan_hd_resep_detail as $row_resep_detail) {
            $resep_detail_identitas = $this->tindakan_resep_obat_detail_identitas_m->get_by(array('tindakan_resep_obat_detail_id' => $row_resep_detail->id, 'item_id' => $item_id, 'bn_sn_lot' => $bn, 'status' => 2), true);

            $id_resep_detail_identitas = $resep_detail_identitas->id;
            $apotik = $this->cabang_m->get(4);

            $status_resep['status'] = 1;
            $edit_resep_identitas_lokal = $this->tindakan_resep_obat_detail_identitas_m->save($status_resep, $id_resep_detail_identitas);

            $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
            $edit_resep_identitas = insert_data_api($status_resep, $apotik->url, $path_model, $id_resep_detail_identitas);

            $response = new stdClass;
            $response->success = false;

            if ($edit_resep_identitas_lokal) 
            {   
                $response->success = true;
                die(json_encode($response));
            }


        }

    }

    public function delete_item_resep()
    {
        $id_resep_detail_identitas = $this->input->post("id");

        $apotik = $this->cabang_m->get(4);

        $status_resep['canceled_by'] = $this->session->userdata('user_id');
        $status_resep['status'] = 3;
        $edit_resep_identitas_lokal = $this->tindakan_resep_obat_detail_identitas_m->save($status_resep, $id_resep_detail_identitas);

        $path_model = 'klinik_hd/tindakan_resep_obat_detail_identitas_m';
        $edit_resep_identitas = insert_data_api($status_resep, $apotik->url, $path_model, $id_resep_detail_identitas);

        $response = new stdClass;
        $response->success = false;

        if ($edit_resep_identitas_lokal) 
        {   
            $response->success = true;
            die(json_encode($response));
        }
        
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
         
          $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_paket',$body);

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

    public function commet_bed()
    {
        $file = urlencode(base64_encode($this->session->userdata('url_login')));

        $filename  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').'notif_bed.txt';
        // die(dump($filename));
        // infinite loop until the data file is not modified
        $lastmodif    = isset($_POST['timestamp']) ? $_POST['timestamp'] : 0;
        $currentmodif = filemtime($filename);

        $data_bed = array();
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

    public function get_notif_bed()
    {

        $response = new stdClass;
        $response->success = false;
        $response->count = false;

        $this->bed_m->set_columns(array('id', 'lantai_id', 'kode'));
        $data_bed = $this->bed_m->get_by(array('status' => 2, 'is_active' => 1));
        if(count($data_bed))
        {
            $response->success = true;
            $response->count = count($data_bed);
            $response->rows = $data_bed;
        }
        else
        {
            $response->success = false;
        }

        die(json_encode($response));
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
                $table.='<tr id="item_row_'.$x.'"><td align="center" width="100px"><div id="inventory_identitas_detail" class="hidden"> </div><input type="hidden" name="item['.$x.'][itemid]" value="'.$row['id'].'"><input type="hidden" name="item['.$x.'][satuanid]" value="'.$row['item_satuan_id'].'">'.$row['kode'].'</td><td>'.$row['nama'].'</td><td align="center" width="100px">'.$row['jumlah'].' '.$row['nama_satuan'].'<input type="hidden" id="item['.$x.'][jatah]" name="item['.$x.'][jatah]" value="'.$row['jumlah'].'"></td><td align="center" width="100px">'.$row['digunakan'].' '.$row['nama_satuan'].'</td><td align="center" width="100px"><div name="item['.$x.'][sisa]">'.($row['jumlah']-$row['digunakan']).'</div><input type="hidden" name="item['.$x.'][sisa2]" value="'.($row['jumlah']-$row['digunakan']).'"></td><td align="center" width="100px">';
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
                $table.='<tr id="item_row_'.$x.'"><td align="center" width="100px"><div id="inventory_identitas_detail" class="hidden"> </div><input type="hidden" name="item['.$x.'][itemid]" value="'.$row['id'].'"><input type="hidden" name="item['.$x.'][satuanid]" value="'.$row['item_satuan_id'].'">'.$row['kode'].'</td><td>'.$row['nama'].'</td><td align="center" width="100px">'.$row['jumlah'].' '.$row['nama_satuan'].'<input type="hidden" id="item['.$x.'][jatah2]" name="item['.$x.'][jatah2]" value="'.$row['jumlah'].'"></td><td align="center" width="100px">'.$row['digunakan'].' '.$row['nama_satuan'].'</td><td align="center" width="100px"><div name="item['.$x.'][sisa2]">'.($row['jumlah']-$row['digunakan']).'</div><input type="hidden" name="item['.$x.'][sisa3]" value="'.($row['jumlah']-$row['digunakan']).'"></td><td align="center" width="100px">';
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

                                    // $harga_jual = $this->item_harga_m->get_harga_item_satuan($row['itemid'], $row['satuanid'])->result_array();
                                    $harga_jual = $this->item_satuan_m->get_by(array('id' => $row['satuanid']), true);

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
                                        'harga_jual'             => (count($harga_jual))?$harga_jual->harga:'-',     // didapatkan dari item_harga sesuai effective date nya <= hari ini
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
                            // $harga_jual = $this->item_harga_m->get_harga_item_satuan($row['itemid'], $row['satuanid'])->result_array();
                            $harga_jual = $this->item_satuan_m->get_by(array('id' => $row['satuanid']), true);
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
                                'harga_jual'             => (count($harga_jual))?$harga_jual->harga:'-',     // didapatkan dari item_harga sesuai effective date nya <= hari ini
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
                
                '<div class="text-left">'.$tipe.'</div>',
               '<div class="text-left">'.$row['nama'].'</div>',
               '<div class="text-left">'.$this->formatrupiah($row['harga']).'</div>',
               '<div class="text-left">'.$row['keterangan'].'</div>',
               '<div class="text-center">'.$action.'</div>',
           
                
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function formatrupiah($val) {
        $hasil ='Rp. ' . number_format($val, 0 , '' , '.' ) . ',-';
        return $hasil;
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
                // die(dump($this->db->last_query()));


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
                    $get_id_tindakan_hd_item=$this->tindakan_hd_item_m->save($data_item);
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
                    $get_id_tindakan_hd_tindakan=$this->tindakan_hd_tindakan_m->save($data_tindakan);
                }
                

                
               
            }
          
        }

        
        echo json_encode('sukses');
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
                    $expire .= '';  
                }
                $expire .= '</label>
                            <div class="col-md-8">
                            <label class="control-label">'.$tanggal_kadaluarsa.'</label>
                        </div>
                    </div>';                 
            }
            else
            {
                $expire = '';
            }

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

                    
                    if(count($detail_tipe_dokumen))
                    {
                        $detail_tipe_id = $detail_tipe_dokumen->id;
                        $value = $detail_tipe_dokumen->value;
                    }


                    if ($data_detail['tipe'] == 1)
                    {
                        $required = '';
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                       
                        $input .= '</label>
                                    <div class="col-md-8">
                                        <label class="control-label">'.$value.'</label>    
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 2)
                    {
                        $required = '';
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $input .= '</label>
                                    <div class="col-md-8">
                                        <label class="control-label">'.$value.'</label>    
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 3) 
                    {
                        $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $input .= '</label>
                                    <div class="col-md-8">
                                        <label class="control-label">'.$value.'</label>    
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
                        
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]', $detail_tipe_option,$value, 'class="form-control" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" disabled')
                                        .'</div>';
                    }
                    elseif ($data_detail['tipe'] == 5)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        

                        $input .= '</label>
                                    <div class="col-md-8"><div class="radio-list">';

                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            if($detail_tipe->value == $value)
                            {
                                $checked = 'checked="checked"';
                            }
                            $input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.' disabled>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div>     
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
                        
                        $input .= '</label>
                                    <div class="col-md-8"><div class="checkbox-list">';
                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            if($detail_tipe->value == $value)
                            {
                                $checked = 'checked="checked"';
                            }
                            $input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.' disabled>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div>         
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
                        
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" multiple="multiple" '.$required.' '.$selected.' disabled')
                                        .'</div>';
                    }
                    elseif ($data_detail['tipe'] == 8) 
                    {
                        $date = '';
                        if($value != '')
                        {
                            $date = date('d-M-Y', strtotime($value));
                        }
                        
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $input .= '</label>
                                    <div class="col-md-8">
                                        <label class="control-label">'.$date.'</label>    
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 9) 
                    {
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        
                        $src = '';
                        $image = '';
                        if($value != '')
                        {
                            if($value != 'doc_global/document.png')
                            {
                                $value = $value;
                                $tipe  = ($data_dokumen->tipe == 1)?'pelengkap':'rekam_medis';
                                $src   = base_url().config_item('site_img_pasien').$data_pasien->no_member.'/dokumen/'.$tipe.'/'.$value;

                                $img_src = base_url().config_item('site_img_pasien').'doc_global/document.png';
                                if (file_exists($src) && is_file($src)) 
                                {
                                    $img_src = $src;
                                }
                            }

                            $image = '<li class="working">
                                        <div class="thumbnail">
                                            <a class="fancybox-button" title="'.$value.'" href="'.$img_src.'" data-rel="fancybox-button">
                                                <img src="'.$img_src.'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                            </a>
                                        </div>
                                    </li>';
                        }

                        $input .= '</label>
                                  <div class="col-md-8">
                                    <div id="upload_dokumen_'.$z.'">
                                    
                                        <ul class="ul-img">
                                            '.$image.'
                                        </ul>
                                    </div>
                                        
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

    public function modal_add_notes($tindakan_hd_id,$bed_id)
    {
        $data_tindakan = $this->tindakan_hd_m->get($tindakan_hd_id);
        $data_tindakan = object_to_array($data_tindakan);

        $data_pasien = $this->pasien_m->get($data_tindakan['pasien_id']);
        $data_pasien = object_to_array($data_pasien);

        $data_bed = $this->bed_m->get($bed_id);
        $data_bed = object_to_array($data_bed);

        $data = array(

            'data_tindakan' => $data_tindakan, 
            'data_pasien'   => $data_pasien,
            'data_bed'   => $data_bed
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_add_notes', $data);
    }

    public function proses_notes($tindakan_hd_id,$bed_id)
    {
        $data_tindakan = $this->tindakan_hd_m->get($tindakan_hd_id);
        $data_tindakan = object_to_array($data_tindakan);

        $data_pasien = $this->pasien_m->get($data_tindakan['pasien_id']);
        $data_pasien = object_to_array($data_pasien);

        $data_bed = $this->bed_m->get($bed_id);
        $data_bed = object_to_array($data_bed);

        $data = array(

            'data_tindakan' => $data_tindakan, 
            'data_pasien'   => $data_pasien,
            'data_bed'   => $data_bed
        );

        $this->load->view('klinik_hd/transaksi_perawat/tab_perawat/modal_proses_notes', $data);
    }

    public function tambah_notes()
    {
        if($this->input->is_ajax_request())
        {
            $array_input = $this->input->post();
            $lantai = $array_input['lantai_id'];

            if($array_input['item'])
            {
                foreach ($array_input['item'] as $item) 
                {
                    if($item['notes'] != '')
                    {
                        $data = array(
                            'tindakan_hd_id' => $array_input['tindakan_hd_id'],
                            'note'           => $item['notes'],
                            'status'         => 0
                        );

                        $tindakan_hd_note_id = $this->tindakan_hd_note_m->save($data);
                    }
                }

                if ($tindakan_hd_note_id) 
                {
                    $flashdata = array(
                        "success",
                        translate("Note berhasil ditambahkan", $this->session->userdata("language")),
                        translate("Sukses", $this->session->userdata("language")),
                        $lantai
                    );
                    
                    echo json_encode($flashdata);
                }
            }
            
        }
    }

    public function listing_note_shift($tindakan_hd_id)
    {
        $result = $this->tindakan_hd_note_m->get_datatable($tindakan_hd_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i = 1;
        foreach($records->result_array() as $row)
        {
            $note_id = '<input type="hidden" id="note_id_'.$i.'" value="'.$row['id'].'" name="note['.$i.'][id]" >';
            
            if($i == 1)
            {
                if($row['status']==0)
                {
                    $action_ya = '<input type="radio" id="note_ya_'.$i.'" value="1" name="note['.$i.'][pilih]" >';
                    $action_tdk = '<input type="radio" id="note_tdk_'.$i.'" value="2" name="note['.$i.'][pilih]" >';
                }

                if($row['status']==1)
                {
                    $action_ya = '<input type="radio" id="note_ya_'.$i.'" value="1"  name="note['.$i.'][pilih]" checked>';
                    $action_tdk = '<input type="radio" id="note_tdk_'.$i.'" value="2" name="note['.$i.'][pilih]" >';
                }
                if($row['status']==2)
                {
                    $action_ya = '<input type="radio" id="note_ya_'.$i.'" value="1"  name="note['.$i.'][pilih]" >';
                    $action_tdk = '<input type="radio" id="note_tdk_'.$i.'" value="2" name="note['.$i.'][pilih]" checked>';
                }                
            }
            else
            {
                if($row['status']==0)
                {
                    $action_ya = '<input type="radio" id="note_ya_'.$i.'" value="1" name="note['.$i.'][pilih]" >';
                    $action_tdk = '<input type="radio" id="note_tdk_'.$i.'" value="2" name="note['.$i.'][pilih]" >';
                }

                if($row['status']==1)
                {
                    $action_ya = '<input type="radio" id="note_ya_'.$i.'" value="1"  name="note['.$i.'][pilih]" checked>';
                    $action_tdk = '<input type="radio" id="note_tdk_'.$i.'" value="2" name="note['.$i.'][pilih]" >';
                }
                if($row['status']==2)
                {
                    $action_ya = '<input type="radio" id="note_ya_'.$i.'" value="1"  name="note['.$i.'][pilih]" >';
                    $action_tdk = '<input type="radio" id="note_tdk_'.$i.'" value="2" name="note['.$i.'][pilih]" checked>';
                }                
            }
            
            $output['data'][] = array(
                 
                '<div class="text-center">'.$i.$note_id.'</div>',
                '<div class="text-left">'.$row['note'].'</div>',
                '<div class="text-left">'.$row['nama_perawat_buat'].'</div>',
                '<div class="text-left">'.$row['nama_perawat_edit'].'</div>',
                '<div class="text-center">'.$action_ya.'</div>',
                '<div class="text-center">'.$action_tdk.'</div>'            
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function edit_notes()
    {
        if($this->input->is_ajax_request())
        {
            $array_input = $this->input->post();

            $lantai = $array_input['lantai_id'];
            
            foreach ($array_input['note'] as $note) 
            {
               if(isset($note['pilih']))
               {
                    $data['status'] = $note['pilih'];

                    $tindakan_hd_note_id = $this->tindakan_hd_note_m->save($data, $note['id']);

               }
            }

            if ($tindakan_hd_note_id) 
            {
                $flashdata = array(
                    "success",
                    translate("Note berhasil diproses", $this->session->userdata("language")),
                    translate("Sukses", $this->session->userdata("language")),
                    $lantai
                );
                
                echo json_encode($flashdata);
            }
        }
    }

    public function get_item_dialyzer()
    {
        if($this->input->is_ajax_request()){
            $type = $this->input->post('type');
            $pasien_id = $this->input->post('pasien_id');
            $tindakan_penaksiran_id = $this->input->post('tindakan_penaksiran_id');

            $assesment = $this->tindakan_hd_penaksiran_m->get($tindakan_penaksiran_id);

            $response = new stdClass;
            
            if($type == 1){
                $dializer = $this->item_m->get_dialyzer()->result_array();
                $id_dializer = $assesment->dialyzer_id;
                $id_reuse = 0;
            }else{

                $dializer = $this->item_tersimpan_m->get_dialyzer($pasien_id)->result_array();
                $id_dializer = $assesment->dialyzer_id;


                $kode_dialyzer = $assesment->kode_dialyzer;
                $data_reuse = $this->item_tersimpan_m->get_by(array('pasien_id' =>$pasien_id, 'item_id' => $id_dializer, 'kode_dialyzer' => $kode_dialyzer),true);
                $id_reuse = $data_reuse->simpan_item_id;
            }

            $response->item = $dializer;
            $response->id = $id_dializer;
            $response->id_reuse = $id_reuse;
            die(json_encode($response));
        }
    }


    public function modal_dialyzer($assesment_id, $item_id, $tipe,$bed_id)
    {
           
        $item = $this->item_m->get($item_id);
        $data_assesment = $this->tindakan_hd_penaksiran_m->get($assesment_id);
        $data_bed = $this->bed_m->get($bed_id);

        $data = array(
            'data_item'      => $item,
            'data_bed'      => $data_bed,
            'assesment_id'   => $assesment_id,
            'tindakan_hd_id' => $data_assesment->tindakan_hd_id,
            'pasien_id'      => $data_assesment->pasien_id,
            'tipe'           => $tipe,
        );

        $this->load->view('klinik_hd/transaksi_perawat/modal_dialyzer_new', $data);
        
    }

    public function get_inventory()
    {
        if($this->input->is_ajax_request()){

            $this->load->model('apotik/inventory_api_m');
            $item_id        = $this->input->post('item_id');
            $assesment_id   = $this->input->post('assesment_id');
            $tindakan_hd_id = $this->input->post('tindakan_hd_id');
            $pasien_id      = $this->input->post('pasien_id');
            $tipe           = $this->input->post('tipe');
            $value          = $this->input->post('value');
            $gudang_id          = $this->input->post('gudang_id');

            $user_id = $this->session->userdata('user_id');

            $data_pasien = $this->pasien_m->get($pasien_id);

            $response = new stdClass;
            $response->success = false;

            $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $item_id, 'is_primary' => 1), true);

            // $harga_item = $this->item_harga_m->get_harga_item_satuan($item_id, $satuan_primary->id)->result_array();
            $harga_item = $this->item_satuan_m->get_by(array('id' => $satuan_primary->id), true);
            // die(dump($harga_item->harga));
            if($tipe == 1){
                $inventory = $this->inventory_api_m->get_by(array('item_id' => $item_id, 'bn_sn_lot' => $value, 'gudang_api_id' => $gudang_id));
               // die(dump($this->db->last_query()));
                $inventory = object_to_array($inventory);
                
                if(count($inventory) != 0){
                    $item_tersimpan = $this->item_tersimpan_m->get_max_kode($pasien_id)->result_array();
                            
                    $tgl_daftar = $data_pasien->tanggal_daftar;
                    $tgl_daftar = date('y', strtotime($tgl_daftar));

                    $no_member = $data_pasien->no_member;
                    if(substr($no_member, 0, 12) == 'KRC0115R027-'){
                        $no_member = substr($no_member, -3);
                    }else{
                        $no_member = substr($no_member, -4);
                        $no_member = str_replace('-', '', $no_member);
                    }
                    
                    if(count($item_tersimpan)){
                        $max_kode = intval($item_tersimpan[0]['max_kode']) + 1;
                    }else{
                        $max_kode = 1;
                    }

                    $format         = $tgl_daftar.$no_member.'-%02d';
                    $kode_reuse       = sprintf($format, $max_kode, 2);

                    $response->success = true;
                    $response->msg = translate('Dializer berhasil dipilih', $this->session->userdata('language'));
                    $response->bn = $kode_reuse;
                }else{
                    $response->success = false; 
                    $response->msg = translate('Dializer tidak tersedia', $this->session->userdata('language'));
                }

            }
            if($tipe == 2){

                $item_tersimpan = $this->item_tersimpan_m->get_by(array('pasien_id' => $pasien_id, 'item_id' => $item_id, 'kode_dialyzer' => $value));
                $item_tersimpan = object_to_array($item_tersimpan);

                
                if(!empty($item_tersimpan)){
                    $response->success = true;
                    $response->msg = translate('Dializer berhasil dipilih', $this->session->userdata('language'));
                    $response->bn = $item_tersimpan['bn_sn_lot'];
                }else{
                    $response->success = false;
                    $response->msg = translate('Dializer tidak tersedia', $this->session->userdata('language'));

                }
            }

            die(json_encode($response));
        }
    }

    public function get_inventory_reuse()
    {
        if($this->input->is_ajax_request()){
            $simpan_item_id        = $this->input->post('id');

            $response = new stdClass;
            
            $item_tersimpan = $this->item_tersimpan_m->get_by(array('simpan_item_id' => $simpan_item_id), true);
            $item_tersimpan = object_to_array($item_tersimpan);
            
            if(!empty($item_tersimpan)){
                $response->success = true;
                $response->msg = translate('Dializer berhasil dipilih', $this->session->userdata('language'));
                $response->bn = $item_tersimpan['bn_sn_lot'];
                $response->kode = $item_tersimpan['kode_dialyzer'];
            }else{
                $response->success = false;
                $response->msg = translate('Dializer tidak tersedia', $this->session->userdata('language'));

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

    public function listing_item_minta($tindakan_hd_id)
    {
        $tindakan_resep = $this->tindakan_resep_obat_m->get_by(array('tipe_tindakan' => 1, 'tindakan_id' => $tindakan_hd_id, 'status' => 1, 'is_active' => 1), true);

        $result = $this->tindakan_resep_obat_detail_m->get_datatable($tindakan_resep->id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die_dump($result->records);

        foreach($records->result_array() as $row)
        {
            $status = '';

            if($row['status'] == 1){
                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Apoteker</div>';
            }elseif($row['status'] == 2){
                $status = '<div class="text-center"><span class="label label-md label-success">Siap Digunakan</span></div>';

            }
            
            $output['data'][] = array(
                 
                // '<div class="text-left">'.$row['kode_item'].'</div>',
                '<div class="text-left">'.$row['nama_item'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['nama_satuan'].'</div>',
                '<div class="text-center inline-button-table">'.$status.'</div>',
                 
            );
        }

        echo json_encode($output);
    }

    public function save_resep()
    {
        if($this->input->is_ajax_request()){

            $array_input = $this->input->post();

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Permintaan Obat/Alkes gagal dikirim', $this->session->userdata('language'));

            $tindakan_id = 0;
            $tipe_tindakan = 0;

            $jenis_tindakan_id  = $array_input['jenis_tindakan_id'];
            $tindakan_hd_id     = $array_input['tindakan_hd_id'];
            $pasien_id          = $array_input['pasien_id'];
            $item_minta         = $array_input['item_minta'];

            if($jenis_tindakan_id == 1){
                $tindakan_id = $tindakan_hd_id;
                $tipe_tindakan = 1;
            }if($jenis_tindakan_id == 2){
                $pasien_tindakan_transfusi = $this->pasien_tindakan_m->get_by(array('pasien_id' => $array_input['pasien_id'], 'tipe_tindakan' => 2), true);
                $tindakan_id = $pasien_tindakan_transfusi->tindakan_id;
                $tipe_tindakan = 2;
            }if($jenis_tindakan_id == 3){

                $pasien_tindakan_cek_lab = $this->pasien_tindakan_m->get_by(array('pasien_id' => $array_input['pasien_id'], 'tipe_tindakan' => 3), true);
                $tindakan_id = $pasien_tindakan_cek_lab->tindakan_id;
                $tipe_tindakan = 3;
            }

            if(count($item_minta) != 0){
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
                    'tipe_tindakan' => $tipe_tindakan,
                    'tipe_resep' => 1,
                    'pasien_id'     => $pasien_id,
                    'dokter_id'     => $this->session->userdata('user_id'),
                    'status'        => 1,
                    'is_active'     => 1
                );
                $resep_id_lokal = $this->tindakan_resep_obat_m->save($data_resep);

                foreach($item_minta as $row){

                    $data6 = array(
                        'tindakan_id'            => $tindakan_id,
                        'tipe_tindakan'          => $tipe_tindakan,
                        'tindakan_resep_obat_id' => $resep_id_lokal,
                        'tipe_item'              => 1,
                        'item_id'                => $row['item_id_hidden'],
                        'jumlah'                 => $row['jumlah'],
                        'satuan_id'              => $row['satuan'],
                        'bawa_pulang'            => 0,
                        'is_active'              => 1
                    );

                    $resep_detail_lokal_id = $this->tindakan_resep_obat_detail_m->save($data6);
                            
                }

               
                
            }

             if($resep_id_lokal){
                    $response->success = true;
                    $response->msg = translate('Permintaan obat/alkes berhasil ditambahkan', $this->session->userdata('language'));
                }

                die(json_encode($response));    
        }
    }

    public function listing_item_transfusi($tindakan_transfusi_id = null, $kategori = null)
    {
        $result = $this->tindakan_resep_obat_detail_identitas_m->get_datatable($tindakan_transfusi_id, 2);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i = 0;
        foreach($records->result_array() as $row)
        {   

            $item_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1), true);
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" data-satuan_primary="'.htmlentities(json_encode($item_satuan_primary)).'" class="btn btn-primary select" href="'.base_url().'klinik_hd/transaksi_perawat/modal_item_transfusi/'.$tindakan_transfusi_id.'/'.$row['id'].'" data-toggle="modal" data-target="#modal_tindakan_lain"><i class="fa fa-check"></i></a>
            <a title="'.translate('Batalkan', $this->session->userdata('language')).'"  name="cancel[]"  data-msg="'.translate('Anda yakin akan membatalkan item resep ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn red cancel"><i class="fa fa-times"></i></a>';
            
            $output['data'][] = array(
                 
                '<div class="text-center"><input type="hidden" name="item['.$i.'][item_id]" id="item_'.$id.'_item_id" value="'.$row['item_id'].'" class="form-control">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][bn_sn_lot]" id="item_'.$id.'_bn_sn_lot" value="'.$row['bn_sn_lot'].'" class="form-control"><input type="hidden" name="item['.$i.'][jumlah]" id="item_'.$id.'_jumlah" value="'.$row['jumlah'].'" class="form-control">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-center"><input type="hidden" name="item['.$i.'][expire_date]" id="item_'.$id.'_expire_date" value="'.$row['expire_date'].'" class="form-control">'.date('d M Y', strtotime($row['expire_date'])).'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function change_status_transfusi()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();

            $id_transfusi = $array_input['id_transfusi'];
            $status = $array_input['status'];

            $pasien = $this->pasien_m->get_by(array('id' => $array_input['pasien_id']), true);

            $user_id = $this->session->userdata('user_id');

            $response->success = false;

            $data_tindakan = array(
                'status' => $status
            );

            $wheres = array(
                'pasien_id' => $array_input['pasien_id'],
                'tindakan_id' => $array_input['id_transfusi']
            );

            $edit_pasien_tindakan = $this->pasien_tindakan_m->update_by($user_id, $data_tindakan, $wheres);

            $edit_tindakan_transfusi = $this->tindakan_transfusi_m->edit_data($data_tindakan, $array_input['id_transfusi']);

            if($status == 2){
                $draf_invoice_swasta = $this->draf_invoice_m->get_by(array('pasien_id' => $array_input['pasien_id'], 'jenis_invoice' => 1), true);

                if(count($draf_invoice_swasta) != 0){

                    $data_draf_inv_swasta['akomodasi'] = 25000;
                    $edit_inv_swasta = $this->draf_invoice_m->edit_data($data_draf_inv_swasta, $draf_invoice_swasta->id);
                    
                    $draf_invoice_detail_swasta = $this->draf_invoice_detail_m->get_by(array('draf_invoice_id' => $draf_invoice_swasta->id, 'tipe_item' => 2, 'item_id' => 45, 'is_active' => 1), true);

                    if(count($draf_invoice_detail_swasta) == 0){

                        $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                        $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                        
                        $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                        $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                        $data_draft_tindakan_detail = array(
                            'id'    => $id_draft_detail,
                            'draf_invoice_id'    => $draf_invoice_swasta->id,
                            'tipe_item' => 2,
                            'item_id'   => 45,
                            'nama_tindakan' => 'Jasa Transfusi',
                            'harga_jual'             => 40000,
                            'status' => 1,
                            'jumlah' => 1,
                            'is_active'    => 1,
                            'created_by'    => $this->session->userdata('user_id'),
                            'created_date'    => date('Y-m-d H:i:s')
                        );

                        $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);

                        $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                        $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                        
                        $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                        $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                        $data_draft_tindakan_detail = array(
                            'id'    => $id_draft_detail,
                            'draf_invoice_id'    => $draf_invoice_swasta->id,
                            'tipe_item' => 2,
                            'item_id'   => 8,
                            'nama_tindakan' => 'Kantong Darah',
                            'tipe_tindakan' => 2,
                            'harga_jual'             => 360000,
                            'status' => 1,
                            'jumlah' => $array_input['kantong_darah'],
                            'is_active'    => 1,
                            'created_by'    => $this->session->userdata('user_id'),
                            'created_date'    => date('Y-m-d H:i:s')
                        );

                        $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                    }
                }elseif(count($draf_invoice_swasta) == 0){
                    $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                    $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                    
                    $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                    $data_draft_tindakan = array(
                        'id'    => $id_draft,
                        'pasien_id'    => $array_input['pasien_id'],
                        'tipe'  => 1,
                        'cabang_id'  => $this->session->userdata('cabang_id'),
                        'tipe_pasien'  => 1,
                        'nama_pasien'  => $pasien->nama,
                        'shift'  => $data_tindakan_hd->shift,
                        'user_level_id'  => $this->session->userdata('level_id'),
                        'jenis_invoice' => 1,
                        'status'    => 1,
                        'is_active'    => 1,
                        'akomodasi'    => 25000,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $id_draft,
                        'tipe_item' => 2,
                        'item_id'   => $id_transfusi,
                        'nama_tindakan' => 'Jasa Transfusi',
                        'harga_jual'             => 40000,
                        'jumlah' => 1,
                        'status' => 1,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $id_draft,
                        'tipe_item' => 2,
                        'item_id'   => 8,
                        'nama_tindakan' => 'Kantong Darah',
                        'tipe_tindakan' => 2,
                        'harga_jual'             => 360000,
                        'status' => 1,
                        'jumlah' => $array_input['kantong_darah'],
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                }
               
            }

            $response->success = true;
            $response->status = $status;
            $response->msg = 'Status Tindakan Transfusi Berhasil diubah';

            die(json_encode($response));    
        }
    }

    public function get_antrian()
    {
        if($this->input->is_ajax_request()){


            $pasien_id = $this->input->post('pasien_id');

            $data_antrian = $this->antrian_pasien_m->get_by(array('pasien_id' => $pasien_id, 'posisi_loket' => 5), true);

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
                'panggilan'    => 'Panggilan untuk pasien '.$data_antrian->nama_pasien.', Ke Ruang Tindakan HD',
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

    public function delete_dialyzer_reuse()
    {
        if($this->input->is_ajax_request()){

            $array_input = $this->input->post();

            $data_reuse = array(
                'status_reuse'  => 5,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            $wheres['simpan_item_id'] = $array_input['id'];

            $edit_reuse = $this->item_tersimpan_m->update_by($this->session->userdata('user_id'),$data_reuse,$wheres);

            $response->success = true;

            die(json_encode($response));

        }
    }
}
