<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit_transaksi extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '073129c979193c4b1964408c612d189b';                  // untuk check bit_access

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
        $this->load->model('master/penjamin_kelompok_m');

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
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_history_m');

        $this->load->model('klinik_hd/tindakan_hd_m');
        $this->load->model('klinik_hd/tindakan_hd_history_m');
        $this->load->model('klinik_hd/pasien_klaim_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual2_m');
        $this->load->model('klinik_hd/bed_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_history_m');
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
        $this->load->model('master/pasien_penjamin_m');
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


        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/penjamin_m');
        $this->load->model('klinik_hd/pertanyaan_surey_m');
        $this->load->model('apotik/inventory_m');
        // $this->load->model('apotik/inventory_identitas_m');
        $this->load->model('apotik/inventory_identitas_detail_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');
        $this->load->model('klaim/buat_sep/sep_tindakan_m');  
        $this->load->model('klinik_hd/klaim_m');
    
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('klinik_hd/outstanding_upload_dokumen_klaim_m');

        $this->load->library('mpdf/mpdf.php');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/edit_transaksi/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
       
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Edit Transaksi', $this->session->userdata('language')), 
            'header'         => translate('Edit Transaksi', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/edit_transaksi/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/klinik_hd/edit_transaksi/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        // Load the view
        $data = array(
            'title'            => config_item('site_name').' | '. translate("Tambah Tindakan", $this->session->userdata("language")), 
            'header'           => translate("Tambah Tindakan", $this->session->userdata("language")), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => TRUE,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'klinik_hd/edit_transaksi/add'
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_pilih_pasien()
    {
        
        $result = $this->pasien_m->get_datatable_pilih_pasien();

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
        foreach($records->result_array() as $row)
        {
            
            $action = '';
            if($row['active']== 1)
            {
                if ($row['url_photo'] != '') 
                {
                    if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo'])) 
                    {
                        $row['url_photo'] = base_url().config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo'];
                    }
                    else
                    {
                        $row['url_photo'] = base_url().config_item('site_img_pasien').'global/global_small.png';
                    }
                } else {

                    $row['url_photo'] = base_url().config_item('site_img_pasien').'global/global_small.png';
                }

                if($row['is_meninggal'] == 0)
                {
                   
                    $action    = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                }

                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_ktp'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_histori($id=null)
    {        
        $user_level_id = $this->session->userdata('level_id');
        $result = $this->transaksi_diproses_m->get_datatable2(array('3'));
        // die_dump($result);
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
            
            $url = array();//URL PHOTO PASIEN
            if ($row['url_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }

            $url1 = array();//URL PHOTO DOKTER
            if ($row['url_photo1'] != '') 
            {
                $url1 = explode('/', $row['url_photo1']);
                // die(dump($row['url_photo']));
                if (file_exists(FCPATH.config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1]) && is_file(FCPATH.config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1])) 
                {
                    $img_url1 = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').$url1[0].'/small/'.$url1[1].'">';
                }
                else
                {
                    $img_url1 = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').'global/small/global.png">';
                }
            } else {

                $img_url1 = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').'global/small/global.png">';
            }

            $data_observasi_dialisis = '<a title="'.translate('Proses', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/edit_transaksi/observasi_dialisis/'.$row['bed_id'].'/0/'.$row['id'].'" class="btn btn-primary search-item"><i class="fa fa-cogs"></i></a>';
            $data_delete = '<a title="'.translate('Hapus', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_konfirm_password" href="'.base_url().'klinik_hd/edit_transaksi/hapus_transaksi/'.$row['id'].'" class="btn btn-danger search-item"><i class="fa fa-times"></i></a>';
            
           
                
            //tambahkan data ke tabel fitur_tombol. Field page="klinik_hd_edit_transaksi", button="observasi_dialisis"
            $action = restriction_button($data_observasi_dialisis,$user_level_id,'klinik_hd_edit_transaksi','observasi_dialisis').restriction_button($data_delete,$user_level_id,'klinik_hd_edit_transaksi','hapus_transaksi');
                


            // $action='<a title="'.translate('Print Assesment', $this->session->userdata('language')).'" target="_blank" name="print" href="'.base_url().'klinik_hd/edit_transaksi/print_assesment/'.$row['id'].'/'.$row['pasienid'].'" class="btn default search-item"><i class="fa fa-print"></i></a><a title="'.translate('Print Dokumen Pasien', $this->session->userdata('language')).'" target="_blank" name="print_dokumen" href="'.base_url().'klinik_hd/edit_transaksi/print_dokumen/'.$row['id'].'/'.$row['pasienid'].'" class="btn btn-primary"><i class="fa fa-print"></i></a><a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="view"   href="'.base_url().'klinik_hd/edit_transaksi/detail_history/'.$row['id'].'/'.$row['pasienid'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
                         
       
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


    public function listing_klaim($id=null)
    {        
        $result = $this->klaim_m->get_datatable($id);

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
           $i++;
            $penggunaan='';
            $penggunaan2='';
            $nokartu='';
            $aktif='';
            $pilih='';

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

                        $cek_bpjs  = check_bpjs($url.'peserta/'.$nokartu);
                        // die(dump($url.'peserta/'.$nokartu));
                        if($cek_bpjs != false && $cek_bpjs != 'not_responding')
                        {
                            $aktif       ='<span class="label label-success">Aktif</span>';

                            if(($row['tggl']!=null) && ($row['tipe']!=null))
                            {
                                $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                                $penggunaan2 = 'Tidak Tersedia';
                                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled required="required">';
                            }
                            else
                            {                            
                            
                                $penggunaan  = '<span class="label label-success">Tersedia</span>';
                                $penggunaan2 = 'Tersedia';
                                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" required="required">';                            
                            }
                        }
                        elseif($cek_bpjs == false && $cek_bpjs != 'not_responding')
                        {
                            $aktif       ='<span class="label label-danger">Tidak Aktif</span>';
                            $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled  required="required">';
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
                                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled required="required">';
                            }
                            else
                            {                          
                                $penggunaan  = '<span class="label label-success">Tersedia</span>';
                                $penggunaan2 = 'Tersedia';
                                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" required="required">';                            
                            }
                        }

                    }
                    else
                    {
                        $url         = $row['url'];
                        $nokartu     = $row['no_kartu'];
                        $aktif       ='<span class="label label-danger">Tidak Aktif</span>';
                        $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'"  required="required">';
                        $hiddenval   ='<input type="text" id="pilihklaim2_'.$i.'" name="pilihklaim2['.$i.'][claimed2]" value="'.$row['id'].'">';
                        $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                        $penggunaan2 = 'Tidak Tersedia';

                        $cek_bpjs  = check_bpjs($url.'peserta/'.$nokartu);

                        if($cek_bpjs != false && $cek_bpjs != 'not_responding')
                        {
                            $aktif       ='<span class="label label-success">Aktif</span>';

                            if(($row['tggl']!=null) && ($row['tipe']!=null))
                            {
                                $penggunaan  = '<span class="label label-danger">Tidak Tersedia</span>';
                                $penggunaan2 = 'Tidak Tersedia';
                                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled required="required">';
                            }
                            else
                            {                            
                            
                                $penggunaan  = '<span class="label label-success">Tersedia</span>';
                                $penggunaan2 = 'Tersedia';
                                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" required="required">';                            
                            }
                        }
                        elseif($cek_bpjs == false && $cek_bpjs != 'not_responding')
                        {
                            $aktif       ='<span class="label label-danger">Tidak Aktif</span>';
                            $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled  required="required">';
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
                                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled required="required">';
                            }
                            else
                            {                          
                                $penggunaan  = '<span class="label label-success">Tersedia</span>';
                                $penggunaan2 = 'Tersedia';
                                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" required="required">';                            
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
                    $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'"  required="required">';
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
                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim2_'.$i.'" name="pilihklaim[]" value="'.$row['id'].'" required="required" checked>';
            }

            $output['data'][] = array(
                 
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$nokartu.'</div>',
                '<div class="text-center">'.$aktif.'</div>',
                '<div class="text-center">'.$penggunaan.'</div>',
                '<div class="text-center">'.$pilih.'</div>',
                $penggunaan2 
                 
            );
            
        }

        echo json_encode($output);
    }

    public function save_add()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();

            // die(dump($array_input));
            $klaim=$array_input['pilihklaim'];
            $pasien_id = $array_input['id_pasien'];

            $data_pasien = $this->pasien_m->get($pasien_id);
           
            $array_daftar = array(
                'cabang_id'     => $this->session->userdata('cabang_id'),
                'poliklinik_id' => 1,
                'dokter_id'     => $array_input['dokter'],
                'pasien_id'     => $array_input['id_pasien'],
                'penjamin_id'   => $array_input['penjamin_id'],
                'penanggung_jawab_id' => 0,
                'antrian'       => 1,
                'antrian_dokter' => 1,
                'status' => 3,
                'shift' => $array_input['shift'],
                'keterangan' => '',
                'waktu_proses' => date('Y-m-d', strtotime($array_input['tanggal'])),
                'berat_badan' => $this->input->post('berat'),
                'tekanan_darah' => $this->input->post('tdatas').'_'.$this->input->post('tdbawah'),
                'jenis_peserta' => $data_daftar->jenis_peserta,
                'is_manual' => 1,
                'is_active' => 1,
                'user_verif_id' => 0,                  
                'status_verif'  => 2,                   
                'tanggal_verif' => date('Y-m-d', strtotime($array_input['tanggal'])),
                'created_by' => $array_input['recept_id'],
                'created_date' => date('Y-m-d', strtotime($array_input['tanggal'])),
            );

           

            $save_history_daftar_id = $this->pendaftaran_tindakan_history_m->add_data($array_daftar);
            $save_history_daftar_id = $this->db->insert_id();

            $cabang_id = $this->session->userdata('cabang_id');
            $cabang = $this->cabang_m->get($cabang_id);

            $last_number = $this->tindakan_hd_history_m->get_nomor_tindakan($cabang->kode)->result_array();
            $last_number = intval($last_number[0]['max_nomor_po'])+1;
                    
            $format      = 'HD'.$cabang->kode.'-'.date('ym').'-%04d';
            $po_number   = sprintf($format, $last_number, 4);

            $data['no_transaksi']            = $po_number;
            $data['pendaftaran_tindakan_id'] = $save_history_daftar_id;
            $data['pasien_id']               = $this->input->post('id_pasien');
            $data['dokter_id']               = $array_input['dokter'];
            $data['tanggal']                 = date('Y-m-d H:i:s', strtotime($array_input['tanggal']));
            $data['shift']                   = $array_input['shift'];
            $data['cabang_id']               = $cabang->id;
            $data['cabang_pasien_id']               = $data_pasien->cabang_id;
            $data['penjamin_id']             = $array_input['penjamin_id'];
            $data['status']                  = 3;
            $data['jangka_waktu']            = $array_input['freq'];
            $data['berat_awal']              = $array_input['berat'];
            $data['berat_akhir']              = $array_input['berat_akhir'];
            $data['bed_id']                  = $array_input['bed_id'];      // *abu
            $data['jenis_peserta']           = $cek_bpjs['response']['peserta']['jenisPeserta']['nmJenisPeserta'];      // *abu
            $data['rupiah']                  = 0;
            $data['is_manual']               = 1;
            $data['is_active']               = 1;
            $data['is_sep']                  = 0;

            $tindakan_id=$this->tindakan_hd_history_m->add_data($data);
            $tindakan_id = $this->db->insert_id();


            $last_number_id_os_upload = $this->outstanding_upload_dokumen_klaim_m->get_max_id()->result_array();
            $last_number_id_os_upload = intval($last_number_id_os_upload[0]['max_id'])+1;

            $data_os_upload = array(
                'id'    => $last_number_id_os_upload,
                'tindakan_hd_history_id'    => $tindakan_id,
                'pasien_id' => $this->input->post('id_pasien'),
                'status'    => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s')
            );

            $add_os_upload = $this->outstanding_upload_dokumen_klaim_m->add_data($data_os_upload);

            $data_bed = $this->bed_m->get_by(array('id' => $array_input['bed_id']), true);

            $data_penaksiran = array(
                'tindakan_hd_id'     => $tindakan_id,
                'pasien_id'          => $array_input['id_pasien'],
                'tanggal'            => date('Y-m-d', strtotime($array_input['tanggal'])),
                'waktu'              => $array_input['waktu'],
                'alergic_medicine'              => $array_input['alergic_medicine'],
                'alergic_food'              => $array_input['alergic_food'],
                'assessment_cgs'              => $array_input['assessment_cgs_'],
                'medical_diagnose'              => $array_input['medical_diagnose_'],
                'blood_preasure'     => $array_input['tdatas'].'_'.$array_input['tdbawah'],
                'time_of_dialysis'   => $array_input['time_dialisis'],
                'quick_of_blood'     => $array_input['qb'],
                'quick_of_dialysis'  => $array_input['qd'],
                'uf_goal'            => $array_input['ufg'],
                'heparin_reguler'            => $array_input['regular_'],
                'heparin_minimal'            => $array_input['minimal_'],
                'heparin_free'            => $array_input['free_'],
                'dose'            => $array_input['dose_'],
                'first'            => $array_input['first_'],
                'maintenance'            => $array_input['maintenance_'],
                'hours'            => $array_input['hour_'],
                'machine_no'         => $data_bed->kode,
                'dialyzer_new'            => ($array_input['dializer'] == 1)?1:0,
                'dialyzer_reuse'            => ($array_input['dializer'] == 2)?1:0,
                'dialyzer'              => $array_input['dialyzer_'],
                'ba_avshunt'            => $array_input['av_shunt_'],
                'ba_femoral'            => $array_input['femoral_'],
                'ba_catheter'            => $array_input['double_lument_'],
                'dialyzer_type'            => $array_input['bicarbonate_'],
                'remaining_of_priming'            => $array_input['remaining'],
                'wash_out'            => $array_input['washout'],
                'drip_of_fluid'            => $array_input['drip_of_fluid'],
                'blood'            => $array_input['blood'],
                'drink'            => $array_input['drink'],
                'vomiting'            => $array_input['vomiting'],
                'urinate'            => $array_input['urinate'],
                'transfusion_type'            => $array_input['type'],
                'transfusion_qty'            => $array_input['quantity'],
                'transfusion_blood_type'            => $array_input['blood_type'],
                'serial_number'            => $array_input['serial_number'],
                'laboratory'            => $array_input['laboratory'],
                'ecg'            => $array_input['ecg'],
                'priming'            => $array_input['priming'],
                'initiation'            => $array_input['initiation'],
                'termination'            => $array_input['termination']
            );

            $tindakan_hd_penaksiran_id=$this->tindakan_hd_penaksiran_history_m->add_data($data_penaksiran);                
            
            $problem_pasien = $this->pasien_problem_history_m->get_by(array('tindakan_hd_id' => $tindakan_id));
            if(count($problem_pasien) == 0)
            {
                // MENGISI DATA KE TABEL PASIEN_PROBLEM
                for ($i=1; $i <= 6; $i++) 
                { 
                    $problem = array(
                        'tindakan_hd_id' => $tindakan_id,
                        'pasien_id'      => $this->input->post('id_pasien'),
                        'problem_id'     => $i,
                        'nilai'          => (isset($array_input['problem_'.$i]))?1:0
                    );
                    $this->pasien_problem_history_m->save($problem);
                }                
            }
            
            $komplikasi_pasien = $this->pasien_komplikasi_history_m->get_by(array('tindakan_hd_id' => $tindakan_id));
            if(count($komplikasi_pasien) == 0)
            {
                // MENGISI DATA KE TABEL PASIEN_KOMPLIKASI
                for ($i=1; $i <= 9; $i++) 
                { 
                    $komplikasi = array(
                        'tindakan_hd_id' => $tindakan_id,
                        'pasien_id'      => $this->input->post('id_pasien'),
                        'komplikasi_id'  => $i,
                        'nilai'          => (isset($array_input['komplikasi_'.$i]))?1:0
                    );
                    $this->pasien_komplikasi_history_m->save($komplikasi);
                }

            }

            $monitoring_dialisis = $array_input['monitoring'];

            foreach ($monitoring_dialisis as $key => $monitoring) {
                if($monitoring['tekanan_darah_1_add'] != ''){

                    $data_monitoring = array(
                        'transaksi_hd_id' => $tindakan_id,
                        'user_id' => $monitoring['perawat_id'],
                        'waktu_pencatatan' => date("Y-m-d H:i:s", strtotime($monitoring['waktu_add'])),
                        'tekanan_darah_1' => $monitoring['tekanan_darah_1_add'],
                        'tekanan_darah_2' => $monitoring['tekanan_darah_2_add'],
                        'kuf' => $monitoring['kuf'],
                        'ufg' => $monitoring['ufg_add'],
                        'ufr' => $monitoring['ufr_add'],
                        'ufv' => $monitoring['ufv_add'],
                        'qb' => $monitoring['qb_add'],
                        'qd' => $monitoring['qd_add'],
                        'tmp' => $monitoring['tmp_add'],
                        'vp' => $monitoring['vp_add'],
                        'ap' => $monitoring['ap_add'],
                        'cond' => $monitoring['cond_add'],
                        'temperature' => $monitoring['temp_add'],
                        'keterangan' => $monitoring['keterangan_add'],
                        'is_active'  => 1
                    );

                    $save_observasi_dialisis = $this->observasi_history_m->save($data_monitoring);
                }
            }

            $last_number_invoice  = $this->invoice_m->get_nomor_invoice()->result_array();
            if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
            {
                $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
            }
            else
            {
                $last_number_invoice = intval(1);
            }

            $format_invoice = date('Ymd',strtotime($array_input['tanggal'])).' - '.'%06d';
            $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);

            $last_id_invoice       = $this->invoice_m->get_id_invoice()->result_array();
            $last_id_invoice       = intval($last_id_invoice[0]['max_id'])+1;
            
            $format_id_invoice     = 'IV-'.date('m').'-'.date('Y').'-%04d';
            $id_invoice = sprintf($format_id_invoice, $last_id_invoice, 4);

            $data_pembayaran_detail = array(
                'id'           => $id_invoice,
                'no_invoice'           => $no_invoice,
                'tindakan_id'          => $tindakan_id,
                'no_tindakan'          => $po_number,
                'waktu_tindakan'       => $array_input['waktu'],
                'cabang_id'            => $this->session->userdata('cabang_id'),
                'tipe_pasien'          => 0,
                'pasien_id'            => $this->input->post('id_pasien'),
                'nama_pasien'          => $data_pasien->nama,
                'tipe'                 => 1,
                'penjamin_id'          => 2,
                'nama_penjamin'        => 'BPJS - JKN',
                'is_claim'             => 0,
                'poliklinik_id'        => 1,
                'nama_poliklinik'      => 'Poli HD',
                'status'               => 1,
                'shift'                => $array_input['shift'],
                'jenis_invoice'        => 1,
                'harga'                => 900000,
                'akomodasi'            => 0,
                'diskon'               => 0,
                'harga_setelah_diskon' => 900000,
                'sisa_bayar'           => 900000,
                'is_active'            => 1,
                'created_by'           => $array_input['recept_id'],
                'created_date'         => date('Y-m-d', strtotime($array_input['tanggal'])),
            );
            
            $invoice_id = $this->invoice_m->add_data($data_pembayaran_detail);
            //$invoice_id = $this->db->insert_id();

            $last_id_invoice_detail       = $this->invoice_detail_m->get_id_invoice_detail()->result_array();
            $last_id_invoice_detail       = intval($last_id_invoice_detail[0]['max_id'])+1;
            
            $format_id_invoice_detail     = 'IVD-'.date('m').'-'.date('Y').'-%04d';
            $id_invoice_detail = sprintf($format_id_invoice_detail, $last_id_invoice_detail, 4);

            $data_detail_item = array(
                'id'                    => $id_invoice_detail,
               'invoice_id'             => $id_invoice,
               'item_id'                => 1,
               'nama_tindakan'          => 'Paket Hemodialisa',
               'qty'                    => 1,
               'harga'                  => 900000,
               'tipe'                   => 1,
               'tipe_item'              => 1,
               'status'                 => 1,
               'is_active'              => 1,
               'created_by'           => $array_input['recept_id'],
               'created_date'         => date('Y-m-d', strtotime($array_input['tanggal'])),
            );

            $invoice_detail_id = $this->invoice_detail_m->add_data($data_detail_item);
                       

            $response = new stdClass;
            $response->success = true;

            die(json_encode($response));

        }
    }
    public function observasi_dialisis($bed_id, $type, $id_tindakan = null)
    {
        $this->load->model('master/info_alamat_m');
        $id = intval($bed_id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/klinik_hd/edit_transaksi/observasi_dialisis';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_tindakan = $this->tindakan_hd_history_m->get($id_tindakan);
        
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
        
        
        // TAB ASSESMENT
        $form_assesment   = $this->tindakan_hd_penaksiran_history_m->get_by(array('tindakan_hd_id' => $id_tindakan));
        $form_problem     = $this->pasien_problem_history_m->get_by(array('tindakan_hd_id' => $id_tindakan));
        $form_komplikasi  = $this->pasien_komplikasi_history_m->get_by(array('tindakan_hd_id' => $id_tindakan));

        $cabang = $this->cabang_m->get(1);
        $result = get_data_sejarah_api($form_pasien->id,$cabang->url);
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
            'content_view'     => 'klinik_hd/edit_transaksi/observasi_dialisis',
            'pk_value'         => $bed_id,
            'flag'             => $type,
            'form_tindakan'    => object_to_array($form_tindakan),
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
            'data_sejarah'      => $result,
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function hapus_transaksi($id)
    {
        $data_tindakan = $this->tindakan_hd_history_m->get($id);
        $data_tindakan = object_to_array($data_tindakan);

        $user_id = $this->session->userdata('user_id');
        $data_user = $this->user_m->get($user_id);
        $data_user = object_to_array($data_user);

        $data = array(
            'transaksi_id' => $id, 
            'form_data'    => $data_tindakan,
            'form_user'    => $data_user
        );

        $this->load->view('klinik_hd/edit_transaksi/modal/password', $data);
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

    public function listing_item_telah_digunakan($tindakan_id)
    {
        $result = $this->tindakan_hd_item_history_m->get_datatable($tindakan_id);
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

    public function listing_monitoring($id_tindakan_hd = null)
    {        
        $tipe = 1;
        $result = $this->observasi_history_m->get_datatable($id_tindakan_hd,$tipe);

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
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'"   data-id="'.$row['id'].'" name="editobservasi[]" data-toggle="modal" data-target="#modal_dialisis" href="'.base_url().'klinik_hd/edit_transaksi/editdataobservasi/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
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
                '<div class="text-center">'.$row['nama'].' [ '.$row['user_level'].' ]</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                '<div class="text-center">'.$row['is_active'].'</div>'
                 
            );
        }

        echo json_encode($output);
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
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="viewpic[]" data-id="'.$row['url_file'].'" data-target="#ajax_notes2" data-toggle="modal"  class="btn green-haze search-item"><i class="fa fa-picture-o"></i></a>';
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
           
            $action='<a data-target="#modal_item_tersimpan" data-toggle="modal" href="'.base_url().'klinik_hd/edit_transaksi/modal_item_tersimpan/'.$row['id'].'/'.$row['item_id'].'/'.$row['item_satuan_id'].'/'.str_replace(' ','_',$row['item_satuan']).'/'.$tindakan_hd_id.'/'.$pasien_id.'" title="'.translate('Gunakan Item', $this->session->userdata('language')).'" name="pakai[]"  data-id="'.$row['id'].'" class="btn btn-primary" style="margin-left: 10px;"><i class="fa fa-check"></i></a>';
             
            $output['data'][] = array(
                 
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['jumlah'].' '.$row['item_satuan'].' ('.$row['idx'].' kali)'.$action.'</div>'
            );
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

    public function listing_observasi($id = null)
    {        

        $result = $this->observasi_history_m->get_datatable($id);

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
                '<div class="text-center">'.$row['nama'].' [ '.$row['user_level'].' ]</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                '<div class="text-center">'.$row['is_active'].'</div>'
                 
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

    public function listing_observasi2($id=null)
    {        

        $result = $this->observasi_history_m->get_datatable($id);

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
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>'
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
        // die(dump($records));
        foreach($records->result_array() as $row)
        {   
            $satuan_primary = $this->inventory_klinik_m->get_satuan_inventori($row['gudang_id'], $row['item_id'])->result_array();
             $item_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1), true);
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan ="'.htmlentities(json_encode($satuan_primary)).'" data-satuan_primary="'.htmlentities(json_encode($item_satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
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
            $data_tindakan = $this->tindakan_hd_history_m->get($tindakan_id);
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


    public function simpan_assesment()
    {
        // die(dump($this->input->post()));
        
        $tindakan_hd_id         = $this->input->post('tindakan_hd_id');
        $id_tindakan_penaksiran = $this->input->post('id_tindakan_penaksiran');       
        $tanggal                = $this->input->post('tanggal');
        $waktu                  = $this->input->post('waktu');
        $array_waktu            = explode('-', $waktu);
        
        $td_atas                = $this->input->post('tdatas');
        $td_bawah               = $this->input->post('tdbawah');
        $bed_id                 = $this->input->post('machine_no');
        $berat_awal             =  $this->input->post('berat_awal');
        $berat_akhir            =  $this->input->post('berat_akhir');
        
        $data_bed               = $this->bed_m->get($bed_id);
        
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
        
        $machine_no             = $data_bed->kode;
        $new                    = $this->input->post('new_');
        $reuse                  = $this->input->post('reuse');
        $dialyzer               = $this->input->post('dialyzer');
        $av_shunt               = $this->input->post('av_shunt');
        $femoral                = $this->input->post('femoral');
        $double_lument          = $this->input->post('double_lument');
        $bicarbonate            = $this->input->post('bicarbonate');

        $data_tindakan_hd = array(
            'tanggal'     => date('Y-m-d H:i:s', strtotime($tanggal)),
            'bed_id'      => $bed_id,
            'berat_awal'  => $berat_awal,
            'berat_akhir' => $berat_akhir,
            'jam_mulai'   => date('Y-m-d H:i:s', strtotime($tanggal.' '.$array_waktu[0])),
            'jam_selesai' => date('Y-m-d H:i:s', strtotime($tanggal.' '.$array_waktu[1]))
        );

        $tindakan = $this->tindakan_hd_history_m->save($data_tindakan_hd, $tindakan_hd_id);


        $data_assesment = array(
            'blood_preasure'    => $td_atas.'_'.$td_bawah,
            'tanggal'           => $tanggal,
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
            'dialyzer_new'      => $new,
            'dialyzer_reuse'    => $reuse,
            'dialyzer'          => $dialyzer,
            'ba_avshunt'        => $av_shunt,
            'ba_femoral'        => $femoral,
            'ba_catheter'       => $double_lument,
            'dialyzer_type'     => $bicarbonate,

        );

        $assesment = $this->tindakan_hd_penaksiran_history_m->save($data_assesment, $id_tindakan_penaksiran);

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

        // $get_assesment = $this->tindakan_hd_penaksiran_history_m->get($id_tindakan_penaksiran);
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

        $assesment = $this->tindakan_hd_penaksiran_history_m->save($data_assesment, $id_tindakan_penaksiran);

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

        $assesment = $this->tindakan_hd_penaksiran_history_m->save($data_assesment, $id_tindakan_penaksiran);

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

        $pasien_problem_id = $this->pasien_problem_history_m->get_by(array('problem_id' => $problem_id, 'tindakan_hd_id' => $tindakan_hd_id));
        
        // $update_nol = $this->pasien_problem_history_m->set_nilai_nol($tindakan_hd_id);

        $data_pasien_problem['nilai'] = 1;
        $pasien_problem = $this->pasien_problem_history_m->save($data_pasien_problem, $pasien_problem_id[0]->id);

    }

    // utk mngupdate checkbox yg di unchek kemudian di beri nilai 0
    public function update_pasien_problem(){
        
        $tindakan_hd_id = $this->input->post('tindakan_hd_id');
        $problem_id     = $this->input->post('problem_id');

        $pasien_problem_id = $this->pasien_problem_history_m->get_by(array('problem_id' => $problem_id, 'tindakan_hd_id' => $tindakan_hd_id));
        
        $data_pasien_problem['nilai'] = 0;
        $pasien_problem = $this->pasien_problem_history_m->save($data_pasien_problem, $pasien_problem_id[0]->id);

    }

    // utk mengeset checkbox kemudian di beri nilai 1
    public function simpan_pasien_komplikasi(){
        
        $tindakan_hd_id = $this->input->post('tindakan_hd_id');
        $komplikasi_id  = $this->input->post('komplikasi_id');

        $pasien_komplikasi_id = $this->pasien_komplikasi_history_m->get_by(array('komplikasi_id' => $komplikasi_id, 'tindakan_hd_id' => $tindakan_hd_id));

        $data_pasien_komplikasi['nilai'] = 1;
        $pasien_komplikasi = $this->pasien_komplikasi_history_m->save($data_pasien_komplikasi, $pasien_komplikasi_id[0]->id);

    }

    // utk mngupdate checkbox yg di unchek kemudian di beri nilai 0
    public function update_pasien_komplikasi(){
        
        $tindakan_hd_id = $this->input->post('tindakan_hd_id');
        $komplikasi_id  = $this->input->post('komplikasi_id');

        $pasien_komplikasi_id = $this->pasien_komplikasi_history_m->get_by(array('komplikasi_id' => $komplikasi_id, 'tindakan_hd_id' => $tindakan_hd_id));
        
        $data_pasien_komplikasi['nilai'] = 0;
        $pasien_komplikasi = $this->pasien_komplikasi_history_m->save($data_pasien_komplikasi, $pasien_komplikasi_id[0]->id);

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
       
        $id = $this->tindakan_hd_history_m->get($transaksiid);

        $body=array('id' => $id->no_transaksi);
     
        echo json_encode($body);
             
    }

    public function add_monitoring($trans_id, $tindakan_penaksiran_id)
    {
        $rows = $this->observasi_history_m->get_data_last($trans_id)->result_array();

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
                "keterangan_value"       => $rows[0]['keterangan'],
                "user_id"                => $rows[0]['user_id'],
                "tindakan_penaksiran_id" => $tindakan_penaksiran_id,
            );
        } 
        else 
        {
            $assesment = $this->tindakan_hd_penaksiran_history_m->get_by(array('tindakan_hd_id'=> $trans_id),true);
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
                "user_id"                => '',
                "tindakan_penaksiran_id" => $tindakan_penaksiran_id,
            );
        }

        $this->load->view('klinik_hd/edit_transaksi/monitoring_dialisis', $body);
    }

    public function editdataobservasi($id)
    {
        
        $rows = $this->observasi_history_m->get_data_by_id($id)->result_array();
        $user = $this->user_m->get($rows[0]['user_id']);
        $body = array(

            "observasi_id_value"     => $rows[0]['id'],
            "transaksi_id_value"     => $rows[0]['transaksi_hd_id'], 
            "user_id"                => $rows[0]['user_id'],
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
        $this->load->view('klinik_hd/edit_transaksi/edit_observasi_dialisis', $body);

    }

    public function setnamapriming()
    {
        $taksir_id = $this->input->post('tindakan_penaksiran_id');

        $data_save['priming'] = $this->session->userdata('nama_lengkap');
        $save_taksir = $this->tindakan_hd_penaksiran_history_m->save($data_save, $taksir_id);

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

    public function modal_item_diluar_paket($tindakan_hd_id)
    {
        $data = array(
            'tindakan_hd_id' => $tindakan_hd_id
        );

        $this->load->view('klinik_hd/edit_transaksi/tab_perawat/modal_item_diluar_paket', $data);
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
        
        $observasi_id = $this->observasi_history_m->save($data);

        $user_id = $this->input->post('userid');
        $user = $this->user_m->get($user_id);
        // UNTUK EXAMINATION SUPPORT PRIMING, INITIATION, TERMINATION
        $tindakan_penaksiran_id = $this->input->post('tindakan_penaksiran_id');

        $data_taksir = $this->tindakan_hd_penaksiran_history_m->get($tindakan_penaksiran_id);

        $priming = '';
        if ($data_taksir->priming == NULL && $data_taksir->initiation == NULL || $data_taksir->priming == '' && $data_taksir->initiation == '') 
        {   
            $data_save['priming']       = $user->nama;
            $data_save['initiation']    = $user->nama;

            $save_taksir = $this->tindakan_hd_penaksiran_history_m->save($data_save, $tindakan_penaksiran_id);

            $priming = $user->nama;
        }
        
        $data_save['termination'] = $user->nama;
        $save_taksir = $this->tindakan_hd_penaksiran_history_m->save($data_save, $tindakan_penaksiran_id);
        $termination = $user->nama;

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
        
        $observasi_id = $this->observasi_history_m->save($data, $this->input->post('id_observasi'));

        $flashdata = array(
            "success",
            translate("Observasi sudah diupdate", $this->session->userdata("language")),
            translate("Sukses", $this->session->userdata("language"))
        );

        echo json_encode($flashdata);
    }

    public function deleteajax2()
    {        
        $id     = $this->input->post('id');

        $data = array(
            'is_active'    => 0
        );
         
        $msg = "Observasi sudah dihapus";
        // save data
        $user_id = $this->observasi_history_m->save($data, $id);

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
        
        $user_id = $this->observasi_history_m->save($data, $id);

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

        $this->load->view('klinik_hd/edit_transaksi/tab_perawat/modal_item_tersimpan', $data);
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
                    $tindakan_hd_item_id = $this->tindakan_hd_item_history_m->save($data_tindakan_hd_item);

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
            $tindakan_hd_item_id = $this->tindakan_hd_item_history_m->save($data_tindakan_hd_item);

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
                    $tindakan_hd_item_id = $this->tindakan_hd_item_history_m->save($data_tindakan_hd_item);
                    

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
                $tindakan_hd_item_id = $this->tindakan_hd_item_history_m->save($data_tindakan_hd_item);
                // die(dump($this->db->last_query()));

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

    public function check_password()
    {
        $pwd = $this->input->post('pwd');
        $pwd_input = $this->input->post('pwd_input');

        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Password yang anda masukan salah', $this->session->userdata('language'));

        $pwd_hash = $this->user_m->hash($pwd_input);

        if($pwd_hash == $pwd)
        {
            $response->success = true;
            $response->msg = translate('Password cocok', $this->session->userdata('language'));
        }

        die(json_encode($response));
    }

    public function delete_tindakan()
    {
        $tindakan_id = $this->input->post('tindakan_id');

        $response = new stdClass;
        $response->success = false;

        $user_id = $this->session->userdata('user_id');
        $data_tindakan['is_active'] = 0;

        $tindakan_hd = $this->tindakan_hd_history_m->save($data_tindakan, $tindakan_id);

        $wheres['transaksi_hd_id'] = $tindakan_id;
        $observasi = $this->observasi_history_m->update_by($user_id,$data_tindakan,$wheres);

        $wheres_item['tindakan_hd_id'] = $tindakan_id;
        $tindakan_item = $this->tindakan_hd_item_history_m->update_by($user_id,$data_tindakan,$wheres_item);


        if($tindakan_hd)
        {
            $response->success = true;
        }

        die(json_encode($response));
    }

   
}

