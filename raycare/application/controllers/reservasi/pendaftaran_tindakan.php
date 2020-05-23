<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_tindakan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '3058e580b6c8dae5017c57f8ad94c792';                  // untuk check bit_access

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

        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('master/cabang_m');
        $this->load->model('master/alamat_m');
        $this->load->model('master/telepon_m');
        $this->load->model('master/region_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/penjamin_m');
        $this->load->model('master/dokumen_m');
        $this->load->model('master/pasien_dokumen_m');
        $this->load->model('master/pasien_dokumen_detail_m');
        $this->load->model('master/pasien_dokumen_detail_tipe_m');
        $this->load->model('master/penjamin_dokumen_m');
        $this->load->model('master/penjamin_kelompok_m');
        $this->load->model('master/cabang_poliklinik_m');
        $this->load->model('master/cabang_poliklinik_dokter_m');
        $this->load->model('master/cabang_poliklinik_perawat_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_history_m');
        $this->load->model('reservasi/pendaftaran/antrian_m');
        $this->load->model('reservasi/pendaftaran/rujukan_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_detail_m');
        $this->load->model('reservasi/pembayaran/os_pembayaran_transaksi_m');
        $this->load->model('others/kotak_sampah_m'); 
        $this->load->model('klinik_hd/klaim_m');
        $this->load->model('master/transaksi_dokter3_m');
        $this->load->model('master/transaksi_dokter_m');
        $this->load->model('master/transaksi_dokter4_m');
        $this->load->model('global/rm_transaksi_pasien_m');
        $this->load->model('reservasi/antrian/antrian_pasien_m');  
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/reservasi/pendaftaran/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        $this->load->model('reservasi/pendaftaran/pembayaran_m');
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header'         => translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/pendaftaran/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/reservasi/pendaftaran/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
   
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header'         => translate('History Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/pendaftaran/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history_all()
    {
        $assets = array();
        $config = 'assets/reservasi/pendaftaran/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
   
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header'         => translate('History Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/pendaftaran/history_all',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function refresh()
    {
       
         redirect("reservasi/pendaftaran_tindakan");
    }

     
    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {   
        $date_awal = date('Y-m-d', strtotime("- 3 months"));
        $date_akhir = date('Y-m-d');
        
        $result = $this->pendaftaran_tindakan_m->get_datatable($date_awal,$date_akhir);
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
            $status = '';
            $shift = '';

            if($row['status'] == 0 && $row['status_verif'] == 2)
            {
                $status = '<span class="label label-warning">Ditolak Dokter</span>';
            }
            if($row['status'] == 4 && $row['status_verif'] == 1)
            {
                $status = '<span class="label label-info">Menunggu Verif</span>';
            }
            if($row['status'] == 4 && $row['status_verif'] == 2)
            {
                $status = '<span class="label label-success">Verif Disetujui</span>';
            }
            if($row['status'] == 4 && $row['status_verif'] == 3)
            {
                $status = '<span class="label label-danger">Verif Ditolak</span>';
            }
            if( $row['status'] == 1 && $row['status_verif'] == 2)
            {
                $status = '<span class="label bg-green">Proses Dokter</span>';
            }
            if($row['status'] == 2 && $row['status_verif'] == 2)
            {
                $status = '<span class="label bg-green">Sedang Ditindak</span>';
            }
            if($row['status'] == 3 && $row['status_verif'] == 2)
            {
                $status = '<span class="label bg-blue">Selesai Ditindak</span>';
            }
            
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global_small.png">';
            }

            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }

            $action='<a title="'.translate('Print Dokumen Pasien', $this->session->userdata('language')).'" target="_blank" name="print_dokumen" href="'.base_url().'reservasi/pendaftaran_tindakan/cetak_dokumen/'.$row['pasien_id'].'/'.$row['penjamin_id'].'" class="btn default"><i class="fa fa-print"></i></a>';

            $output['data'][] = array(
                $row['id'],
                '<div class="text-center inline-button-table">'.$shift.' '.date('d M Y H:i:s', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$img_url.'&nbsp;'.$row['nama_pasien'].'</div>',
                '<div class="text-left inline-button-table bold">'.$row['no_member'].'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left inline-button-table bold">'.$row['no_kartu'].'</div>',

                '<div class="text-left inline-button-table">'.$row['asal_rujukan'].'</div>',
                '<div class="text-left inline-button-table bold">'.$row['no_rujukan'].'</div>',

                '<div class="text-left">'.$row['nama_poli'].'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_dokter'].'</div>',
                '<div class="text-left">'.$row['nama_fo'].'</div>',               
                '<div class="text-left">'.$row['keterangan'].'</div>',               
                '<div class="text-left">'.$status.'</div>', 
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }
    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing_history()
    {   
        $date_awal = date('Y-m-d', strtotime("- 2 months"));
        $date_akhir = date('Y-m-d');
        
        $result = $this->pendaftaran_tindakan_history_m->get_datatable($date_awal,$date_akhir);
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
            $status = '';
            $shift = '';

            if($row['status'] == 0 && $row['status_verif'] == 2)
            {
                $status = '<span class="label label-warning">Ditolak Dokter</span>';
            }
            if($row['status'] == 4 && $row['status_verif'] == 1)
            {
                $status = '<span class="label label-info">Menunggu Verif</span>';
            }
            if($row['status'] == 4 && $row['status_verif'] == 2)
            {
                $status = '<span class="label label-success">Verif Disetujui</span>';
            }
            if($row['status'] == 4 && $row['status_verif'] == 3)
            {
                $status = '<span class="label label-danger">Verif Ditolak</span>';
            }
            if( $row['status'] == 1 && $row['status_verif'] == 2)
            {
                $status = '<span class="label bg-green">Proses Dokter</span>';
            }
            if($row['status'] == 2 && $row['status_verif'] == 2)
            {
                $status = '<span class="label bg-green">Sedang Ditindak</span>';
            }
            if($row['status'] == 3 && $row['status_verif'] == 2)
            {
                $status = '<span class="label bg-blue">Selesai Ditindak</span>';
            }
            
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global_small.png">';
            }

            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }

            $action='<a title="'.translate('Print Dokumen Pasien', $this->session->userdata('language')).'" target="_blank" name="print_dokumen" href="'.base_url().'reservasi/pendaftaran_tindakan/cetak_dokumen/'.$row['pasien_id'].'/'.$row['penjamin_id'].'" class="btn default"><i class="fa fa-print"></i></a>';

            $output['data'][] = array(
                $row['id'],
                '<div class="text-center inline-button-table">'.$shift.' '.date('d M Y H:i:s', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$img_url.'&nbsp;'.$row['nama_pasien'].'</div>',
                '<div class="text-left inline-button-table bold">'.$row['no_member'].'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left inline-button-table bold">'.$row['no_kartu'].'</div>',

                '<div class="text-left inline-button-table">'.$row['asal_rujukan'].'</div>',
                '<div class="text-left inline-button-table bold">'.$row['no_rujukan'].'</div>',
                '<div class="text-left">'.$row['nama_poli'].'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_dokter'].'</div>',
                '<div class="text-left">'.$row['nama_fo'].'</div>',               
                '<div class="text-left">'.$row['keterangan'].'</div>',               
                '<div class="text-center">'.$status.'</div>', 
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
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
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo'])) 
                    {
                        $row['url_photo'] = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo'];
                    }
                    else
                    {
                        $row['url_photo'] = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global_small.png';
                    }
                } else {

                    $row['url_photo'] = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global_small.png';
                }

                if($row['is_meninggal'] == 0)
                {
                    $transaksi = $this->rm_transaksi_pasien_m->get_by(array('pasien_id' => $row['id']));
                    $tagihan = $this->os_pembayaran_transaksi_m->get_by(array('pasien_id' => $row['id']));
                    $action    = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-transaksi="'.count($transaksi).'" data-tagihan="'.count($tagihan).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
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

    public function save()
    {
        $array_input = $this->input->post();
        $command = $this->input->post('command');
        $poliklinik = $this->input->post('poliklinik');
        $dokter = $this->input->post('dokter');
        $shift = $this->input->post('shift');
        $pasien_id = $this->input->post('id_pasien');
        $penjamin_id='';
        $no_antrian= $this->input->post('noantrianval');
        $klaim=$this->input->post('pilihklaim');
        $cabang_id=$this->input->post('cabangid');

        $data_cabang = $this->cabang_m->get_by(array('id'=> $this->session->userdata('cabang_id')));
        $all_cabang = $this->cabang_m->get_by(array('is_active' => 1, 'url !=' => ''));
        $data_pasien = $this->pasien_m->get($pasien_id);
        if ($command === 'add')
        {  
            foreach($klaim as $key=>$value)
            {
                if(isset($value))
                {
                    $penjamin_id = $value;
                }
            }

            $penanggungjawab_id = 0;
            if($array_input['tipe_pj_daftar'] != 1)
            {
                if($array_input['penanggungjawab_id'] == '')
                {
                    $penanggungjawab_id = $array_input['nama_pj'];
                }
                else
                {
                    $penanggungjawab_id = $array_input['penanggungjawab_id'];
                }
            }

            $last_id_pendaftaran       = $this->pendaftaran_tindakan_m->get_max_id_pendaftaran()->result_array();
            $last_id_pendaftaran       = intval($last_id_pendaftaran[0]['max_id'])+1;
            
            $format_id_daftar     = 'PD-'.date('m').'-'.date('Y').'-%04d';
            $id_pendaftaran    = sprintf($format_id_daftar, $last_id_pendaftaran, 4);

            $data = array( 
                "id"        => $id_pendaftaran,
                "cabang_id"           => $cabang_id,
                "cabang_pasien_id"           => $data_pasien->cabang_id,
                "poliklinik_id"       => $poliklinik,
                "dokter_id"           => $dokter,
                "pasien_id"           => $pasien_id,
                "penjamin_id"         => $penjamin_id,
                "penanggung_jawab_id" => $penanggungjawab_id,
                "antrian"             => $no_antrian,
                "antrian_dokter"      => 0,
                "shift"               => $shift,
                "status"              => 4,
                "is_active"           => 1,
                "created_by"          => $this->session->userdata('user_id'),
                "created_date"          => date('Y-m-d H:i:s'),
            );
            $status_verif = 1;
            if($penjamin_id == 1)
            {
               $data['user_verif_id'] = 0;                   
               $data['status_verif']  = 2;                   
               $data['tanggal_verif'] = date('Y-m-d H:i:s');   

               $status_verif = 1;                
            }

            // die(dump($data));
            elseif(in_array($penjamin_id, config_item('penjamin_id')))
            {

                $data_penjamin = $this->penjamin_m->get($penjamin_id);
                $url = '';
                $no_kartu = '';
                $pas_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $pasien_id, 'penjamin_id' => $penjamin_id, 'is_active' =>1), true);
                if(count($pas_penjamin))
                {
                    $no_kartu = $pas_penjamin->no_kartu;
                }
                if($data_penjamin->is_parent == 0)
                {
                    $url = $data_penjamin->url;
                }
                else
                {
                    $penjamin_kel = $this->penjamin_kelompok_m->get_by(array('penjamin_id' => $penjamin_id), true);
                    $url = $penjamin_kel->url;
                }

                $cek_bpjs  = check_bpjs($url.'peserta/'.$no_kartu);


                if($cek_bpjs != false && $cek_bpjs != 'not_responding')
                {
                    $data['user_verif_id'] = 0;                   
                    $data['status_verif']  = 2;                   
                    $data['jenis_peserta']  = $cek_bpjs['response']['peserta']['jenisPeserta']['nmJenisPeserta'];                   
                    $status_verif = 1;
                    $data['tanggal_verif'] = date('Y-m-d H:i:s');
                }
                elseif($cek_bpjs == false && $cek_bpjs != 'not_responding')
                {
                   $data['status_verif']  = 1; 
                   $status_verif = 2;
                }
                elseif($cek_bpjs == 'not_responding')
                {
                    $data['status_verif']  = 1; 
                    $status_verif = 2;
                }


            }

            $data_pendaftaran1 = $this->pendaftaran_tindakan_m->get_data_by_status($poliklinik,$pasien_id)->result_array();
            $data_pendaftaran2 = $this->pendaftaran_tindakan_history_m->get_data_by_status($poliklinik,$pasien_id)->result_array();

            // die(dump($data));

            if(count($data_pendaftaran1) == 0 && count($data_pendaftaran2) == 0)
            {
                $url = base_url();
                $pendaftaran_id = $this->pendaftaran_tindakan_m->add_data($data);

                $last_id       = $this->antrian_pasien_m->get_max_id()->result_array();
                $last_id       = intval($last_id[0]['max_id'])+1;
                
                $format_id     = 'ANT-'.date('m').'-'.date('Y').'-%04d';
                $id_antrian    = sprintf($format_id, $last_id, 4);


                $no_urut = $this->antrian_pasien_m->get_max_no_urut(2)->result_array();
                $no_urut = intval($no_urut[0]['max_no_urut'])+1;


                $data_antrian = array(
                    'id'    => $id_antrian,
                    "dokter_id"           => $dokter,
                    "pasien_id"           => $pasien_id,
                    'nama_pasien' => ucwords($data_pasien->nama),
                    'no_telp' => $array_input['no_telp'],
                    'posisi_loket' => 2,
                    'status' => 0,
                    'no_urut' => $no_urut,
                    'created_date' => date('Y-m-d H:i:s')
                );

                $save_antrian = $this->antrian_pasien_m->add_data($data_antrian);

                
                // $inserted_id = $pendaftaran_id;
                // foreach ($data_cabang as $cabang) 
                // {
                //     $pendaftaran_id = insert_pendaftaran_tindakan($data,$cabang->url,$inserted_id);                   
                // }

                // $inserted_id = str_replace('"', '', $inserted_id);
                $nama_pasien = str_replace(' ', '_', $data_pasien->nama);

                if($status_verif == 1)
                {
                   sent_notification(1,$nama_pasien,$pendaftaran_id);                    
                }
                else
                {
                    sent_notification(3,$nama_pasien,$pendaftaran_id);
                }

                $filename = urlencode(base64_encode($this->session->userdata('url_login')));

                foreach ($data_cabang as $cabang) 
                {
                    change_file_notif($cabang->url,$filename);                    
                }


                if ($pendaftaran_id) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("Data Pasien berhasil ditambahkan.", $this->session->userdata("language")),
                        "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }                
            }
            else
            {
                $flashdata = array(
                    "type"     => "error",
                    "msg"      => translate("Pasien sudah didaftarkan sebelumnya.", $this->session->userdata("language")),
                    "msgTitle" => translate("Error", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            redirect("reservasi/pendaftaran_tindakan");
       
        } 

      //  redirect("reservasi/pendaftaran_tindakan",'refresh');
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->cabang_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->max_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->max_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'  => 1,
            'data_id'    => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
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

    public function get_dokter(){

        $id_poliklinik = $this->input->post('id_poliklinik');
        
        $poliklinik = $this->cabang_m->get_data_dokter_pendaftaran($id_poliklinik,$this->session->userdata("cabang_id"))->result_array();
        // die_dump($this->db->last_query());        
        $hasil_poliklinik = object_to_array($poliklinik);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_poliklinik);
    }

     public function get_status_poli(){

        $status='';
        $id_poliklinik = $this->input->post('id_poliklinik');
         $cabang_id = $this->input->post('cabang_id');
        
        $poliklinik = $this->poliklinik_m->get_status_poli($id_poliklinik,$cabang_id)->result_array();
        // die_dump($this->db->last_query());
        $poliklinik2 = $this->poliklinik_m->get_status_poli2($id_poliklinik)->result_array();
       // $hasil_poliklinik = object_to_array($poliklinik);
        if($poliklinik[0]['counts'] > 0)
        {
             $status = 1;
        }else{
            $status = 0;
        }

        $body=array($status,$poliklinik2[0]['tipe']);
        echo json_encode($body);
    }

    public function listing_antrian($poliklinik_id=null,$dokter_id=null,$shift=null)
    {
        
        $result = $this->antrian_m->get_datatable($poliklinik_id,$dokter_id, $shift);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.$_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global_small.png">';
            }
           

            $output['aaData'][] = array(
                '<div class="text-left">'.$img_url.$row['nama'].'</div>',
                '<div class="text-center">'.$row['antrian'].'</div>',
                 );
         $i++;
        }

        echo json_encode($output);
    }

     public function listing_rujukan($id=null,$cabang_id=null)
    {        

       
        $result = $this->rujukan_m->get_datatable($id,$cabang_id);

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
            
           
            
        $status='';
        if(date('H:i') >= date('H:i',strtotime($row['jam_buka'])) && date('H:i') <= date('H:i',strtotime($row['jam_tutup'])))
        {
           $status='<span class="label label-success">Buka</span></div>';
        }else{
            $status='<span class="label label-danger">tutup</span></div>';
        }

            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['asal'].'</div>',
               '<div class="text-center">'.$row['tujuan'].'</div>',
               '<div class="text-center">'.date('d F Y',strtotime($row['tggldirujuk'])).'</div>',
               '<div class="text-center">'.date('d F Y',strtotime($row['tgglrujukan'])).'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center"><a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="pilihid[]" data-id="'.$row['poliklinik_tujuan_id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a></div>',
                '<div class="text-center">'.$row['asal'].'</div>',
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
                        // $cek_bpjs  = check_bpjs($url.'Peserta/peserta/'.$nokartu);
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
                        // $cek_bpjs  = check_bpjs($url.'Peserta/peserta/'.$nokartu);

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
                $pilih       ='<input type="radio" class="pilih_klaim" data-id="'.$row['id'].'" id="pilihklaim2_'.$i.'" name="pilihklaim[]" value="'.$row['id'].'" required="required" >';
                //checked
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

     public function get_antrian(){

        $id_poliklinik = $this->input->post('poliklinik_id');
        $id_dokter = $this->input->post('dokter_id');
        $shift = $this->input->post('shift');

        $poliklinik = $this->antrian_m->get_antrian($id_poliklinik,$id_dokter, $shift)->result_array();
        // die_dump($this->db->last_query());        
        if($poliklinik[0]['no_antrian'] == NULL)
        {
            $antrian = 1;
        }
        else
        {
            $antrian = $poliklinik[0]['no_antrian'];
        }

        //die_dump($this->db->last_query());
        echo json_encode(($antrian));
    }

     public function listing_upload($id=null)
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

            $action = '<a title="'.translate('Update', $this->session->userdata('language')).'"  name="update" href="'.base_url().'reservasi/pendaftaran_tindakan/edit_dokumen/'.$row['id'].'" data-target="#ajax_notes3" data-toggle="modal"  class="btn btn-xs blue-chambray search-item"><i class="fa fa-edit"></i></a>
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
                $status2,
                $row['dokumen_id']
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function add_dokumen($pasien_id)
    {
        $data = array(
            'pasien' => object_to_array($this->pasien_m->get($pasien_id))
        );
        $this->load->view('reservasi/pendaftaran/modal/tambah_dokumen_pasien', $data);
    }

    public function edit_dokumen($pasien_dok_id)
    {
        $this->load->model('master/dokumen_m');
        $this->load->model('master/pasien_dokumen_m');
        $data = array(
            'pasien_dokumen' => object_to_array($this->pasien_dokumen_m->get($pasien_dok_id))
        );
        $this->load->view('reservasi/pendaftaran/modal/edit_dokumen_pasien', $data);
    }

    public function save_dokumen()
    {
        $array_input = $this->input->post();

        $data_cabang = $this->cabang_m->get_by(array('tipe' => 1, 'is_active' => 1));

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
                                        if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.'assets/mb/var/temp/'.$penj_pasien_dokumen_tipe['value']) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.'assets/mb/var/temp/'.$penj_pasien_dokumen_tipe['value'])) 
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
                                                'path_temp'      => $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('user_img_temp_dir')
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
                                        if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.'assets/mb/var/temp/'.$penj_pasien_dokumen_tipe['value']) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.'assets/mb/var/temp/'.$penj_pasien_dokumen_tipe['value'])) 
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
                                                'path_temp'      => $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('user_img_temp_dir')
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
        
    public function listing_pembayaran($pasien_id=null,$cabang_id=null)
    {
        
        $result = $this->pembayaran_m->get_datatable($pasien_id,$cabang_id);
        //die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            
           
            $status='';
            if($row['is_pay']==0){
                 $status='<span class="label label-danger">Belum Lunas</span></div>';
             }else{
                $status='<span class="label label-success">Klaim</span></div>';
             }
            
             
            $output['aaData'][] = array(
                '<div class="text-left">'.$row['no_transaksi'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-right">'.'Rp. ' . number_format($row['rupiah'], 0 , '' , '.' ) . ',-'.'</div>',
                '<div class="text-center">'.$status.'</div>',
                $row['is_pay']
                 );
         $i++;
        }

        echo json_encode($output);
    }

    public function get_data_pasien(){

        $id= $this->input->post('pasienid');

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

        $cabang_id = $this->session->userdata('cabang_id');
        $cabang = $this->cabang_m->get($cabang_id);

        $form_data2=get_frequensi_tindakan($cabang->url,$startdate,$enddate,$id);

        
        $form_data5=$this->pembayaran_m->getdatapasien2($id)->result_array();
        $form_data6=$this->pembayaran_m->getdatapasienphone2($id)->result_array();
        $form_data7=$this->pembayaran_m->getdatapasienalamat($id)->result_array();
        $form_data8=$this->pembayaran_m->getdatapasienalamat2($id)->result_array();
        $form_data9=$this->pembayaran_m->getdatapasienpenyakit($id)->result_array();

       // $poliklinik = $this->antrian_m->get_antrian($id_poliklinik,$id_dokter)->result_array();
        //die_dump($this->db->last_query());        
   
        $data=array(
                    'form_data2' => $form_data2,
                    'form_data5' => $form_data5,
                    'form_data6' => $form_data6,
                    'form_data7' => $form_data7,
                    'form_data8' => $form_data8,
                    'form_data9' => $form_data9,
                );

        //die_dump($this->db->last_query());
        echo json_encode($data);
    }

     public function listing4($id=null,$flag=null,$pasienid=null)
    {        

        $where='';
        if($flag==1)
        {
            $where='DATE_FORMAT(tanggal_kadaluarsa, "%y-%m-%d") >= DATE_FORMAT(now(), "%y-%m-%d")';
        }else{
            $where='DATE_FORMAT(tanggal_kadaluarsa, "%y-%m-%d") < DATE_FORMAT(now(), "%y-%m-%d")';
        }
        $result = $this->transaksi_dokter4_m->get_datatable($where,$id,$pasienid);

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
            $tipe1='';

               
            
          
            if($row['tipe']==1){
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="view" href="'.base_url().$row['url_file'].'" target="_blank" class="btn green-haze search-item"><i class="fa fa-search"></i></a>';
            }else{
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="viewpic[]" data-id="'.$row['url_file'].'" data-target="#ajax_notes2" data-toggle="modal"  class="btn green-haze search-item"><i class="fa fa-search"></i></a>';
            }

           

           
       
            $output['data'][] = array(
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-center">'.$row['no_dokumen'].'</div>',
               
                '<div class="text-center">'.date('d M Y',strtotime($row['tanggal_kadaluarsa'])).'</div>',
                 '<div class="text-center">'.$row['no_dokumen'].'</div>',
                '<div class="text-center">'.$tipe1.'</div>',
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

      public function get_pasien_data(){

        $pasien_id = $this->input->post('pasien_id');
        

        $data = $this->pembayaran_m->get_pasien_data($pasien_id)->result_array();
        if ($data[0]['url_photo'] != '') 
        {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$data[0]['no_ktp'].'/foto/'.$data[0]['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$data[0]['no_ktp'].'/foto/'.$data[0]['url_photo'])) 
            {
                $data[0]['url_photo'] = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$data[0]['no_ktp'].'/foto/'.$data[0]['url_photo'];
            }
            else
            {
                $data[0]['url_photo'] = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global.png';
            }
        } else {

            $data[0]['url_photo'] = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global.png';
        }
        //die_dump($this->db->last_query());        
   

        //die_dump($this->db->last_query());
        echo json_encode($data);
    }

    public function check_jadwal(){

        $cabang_id = $this->input->post('cabang_id');
        // $bed = $this->input->post('bed');
        // $tipe = $this->input->post('tipe');
        $date = $this->input->post('date');
        
        $cabang= $this->cabang_m->get($cabang_id);
        // die(dump($cabang->url));
        $result = get_jadwal_cabang($cabang->url,$cabang_id,$date);

        // $result=$this->pembayaran_m->check_jadwal2($cabang_id,$date)->result_array();
   
        echo json_encode($result);
       
    }

      public function tanggal_jadwal(){

         
           
            $startdate2='';
            $enddate2='';
            $enddate3='';
            $enddate4='';
            $enddate5='';
            $enddate6='';
            $enddate7='';

             $startdate='';
           
             $date1='';
             $date2='';
             $date3='';
             $date4='';
             $date5='';
             $date6='';
             $date7='';

             $enddate22='';
             $enddate33='';
             $enddate44='';
             $enddate55='';
             $enddate66='';
             $enddate77='';
            
             
            $now=date("Y-m-d");
            if(date("l")=='Monday' || date("l")=='Senin'){
                // $startdate=$now;
                // $date1=date_create($startdate);
                $date1=date_create($now);
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
              
            }elseif (date("l")=='Tuesday' || date("l")=='Selasa') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-1 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }elseif (date("l")=='Wednesday' || date("l")=='Rabu') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-2 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                 $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
                
            }elseif (date("l")=='Thursday' || date("l")=='Kamis') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-3 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                 $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }elseif (date("l")=='Friday' || date("l")=='Jumat') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-4 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                 $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }elseif (date("l")=='Saturday' || date("l")=='Sabtu') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-5 day"));
               $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }else{
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-6 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                 $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }

            $data=array($startdate2,$enddate22,$enddate33,$enddate44,$enddate55,$enddate66,$enddate77,$startdate,$enddate2,$enddate3,$enddate4,$enddate5,$enddate6,$enddate7);
 
        echo json_encode($data);
        //die_dump($this->db->last_query());
       
    }

    public function search_pasien_by_nomor()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Data Pasien Tidak Ditemukan', $this->session->userdata('language'));

            $no_pasien = $this->input->post('no_pasien');
            $tipe = $this->input->post('tipe');

            $pasien = $this->pasien_m->get_pasien_by_nomor($no_pasien,$tipe)->row(0);

            if(count($pasien))
            {
                if ($pasien->url_photo != '') 
                {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo)) 
                    {
                        $pasien->url_photo = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo;
                    }
                    else
                    {
                        $pasien->url_photo = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global.png';
                    }
                } else {

                    $pasien->url_photo = $_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').'global/global.png';
                }


                $now = date('Y-m-d');
                $lahir = date('Y-m-d', strtotime($pasien->tanggal_lahir));

                $response->success = true;
                $response->msg = translate('Data Pasien Ditemukan', $this->session->userdata('language'));
                $response->rows = $pasien;
            }

            die(json_encode($response));

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
                                            <a class="fancybox-button" title="'.$value.'" href="'.$_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien').$src.'" data-rel="fancybox-button">
                                                <img src="'.$_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien').$src.'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
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

    public function modal_warning($pasien_id,$dok_id='')
    {
        $dok_id = explode('_',$dok_id);

        $data_dokumen = $this->dokumen_m->get_data_dokumen($pasien_id,$dok_id);
        $data = array(
            'data_dokumen' => $data_dokumen
        );

        $this->load->view('reservasi/pendaftaran/modal_warning', $data);
    }

    public function get_jumlah_tindakan()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->model('master/dokumen_m');

            $response = new stdClass;
            $response->success = true;

            $penjamin_id = $this->input->post('penjamin_id');
            $pasien_id = $this->input->post('pasien_id');

            $pasien_dokumen = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_tiga_kali'), 'is_active' => 1), true);

            $dokumen = $this->dokumen_m->get_by(array('id' => config_item('id_surat_tiga_kali')), true);
            if(in_array($penjamin_id, config_item('penjamin_id')))
            {
                $cabang_id = $this->session->userdata('cabang_id');

                $data_cabang = $this->cabang_m->get($cabang_id);

                if($data_cabang->is_active == 1)
                {
                    if($data_cabang->url != '')
                    {
                        $jumlah_tindakan = get_jumlah_tindakan_minggu($data_cabang->url, $pasien_id, $penjamin_id);
                        if(count($jumlah_tindakan))
                        {
                            if( $jumlah_tindakan[0]['jumlah_tindakan'] >= 2 )
                            {
                                if(count($pasien_dokumen))
                                {
                                    if($dokumen->is_kadaluarsa == 1){
                                        if(date('Y-m-d',strtotime($pasien_dokumen->tanggal_kadaluarsa)) <= date('Y-m-d'))
                                        {
                                            $response->success = false;
                                            $response->flag = 'expire';
                                            $response->msg = translate('Dokumen Surat Sppd sudah kadaluarsa', $this->session->userdata('language'));
                                        }
                                    }
                                    
                                }
                                else
                                {
                                    $response->success = false;
                                    $response->flag = 'nothing';
                                    $response->msg = translate('Dokumen Surat Sppd belum diupload', $this->session->userdata('language'));
                                }
                            }
                        }
                        
                    }
                }
            }

            echo json_encode($response);
        }
    }

     public function cetak_dokumen($pasien_id, $penjamin_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $data_pasien = $this->pasien_m->get($pasien_id);
        $data_pasien = object_to_array($data_pasien);

        $ktp = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 1), true);
        $ktp_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 1), true);
        $ktp_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $ktp_pasien->id, 'tipe' => 9), true);
        $ktp_detail_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $ktp_detail->id), true);

        $kartu_penjamin = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 2), true);

        $kartu_penjamin_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => $kartu_penjamin->dokumen_id), true);
        $kartu_penjamin_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $kartu_penjamin_pasien->id, 'tipe' => 9), true);
        $kartu_penjamin_detail_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $kartu_penjamin_detail->id), true);

        $kartu_keluarga = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 3), true);
        $kartu_keluarga_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 3), true);
        $kartu_keluarga_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $kartu_keluarga_pasien->id, 'tipe' => 9), true);
        $kartu_keluarga_detail_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $kartu_keluarga_detail->id), true);

        $rujukan = $this->penjamin_dokumen_m->get_by(array('penjamin_id' => $penjamin_id, 'd_order' => 4), true);
        $rujukan_pasien = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 7), true);
        $rujukan_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien->id, 'tipe' => 9), true);
        $rujukan_detail_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $rujukan_detail->id), true);

        $rujukan_memo = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 23), true);
        $rujukan_detail_memo = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_memo->id, 'tipe' => 9), true);
        $rujukan_detail_memo_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $rujukan_detail_memo->id), true);

        $rujukan_pasien_rs = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 22), true);
        $rujukan_detail_rs = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien_rs->id, 'tipe' => 9), true);
        $rujukan_detail_rs_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $rujukan_detail_rs->id), true);

        $rujukan_pasien_luar = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 13), true);
        $rujukan_detail_luar = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $rujukan_pasien_luar->id, 'tipe' => 9), true);
        $rujukan_detail_luar_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $rujukan_detail_luar->id), true);
        //die(dump($rujukan_pasien_luar));
        $sppd = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_sppd')), true);
        $sppd_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $sppd->id, 'tipe' => 9), true);
        $sppd_detail_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $sppd_detail->id), true);

        $sppd_tiga = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => config_item('id_surat_tiga_kali')), true);
        $sppd_tiga_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $sppd_tiga->id, 'tipe' => 9), true);
        $sppd_tiga_detail_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $sppd_tiga_detail->id), true);

        $traveling = $this->pasien_dokumen_m->get_by(array('pasien_id' => $pasien_id, 'dokumen_id' => 4), true);
        $traveling_detail = $this->pasien_dokumen_detail_m->get_by(array('pasien_dokumen_id' => $traveling->id, 'tipe' => 9), true);
        $traveling_detail_value = $this->pasien_dokumen_detail_tipe_m-> get_by(array('pasien_dokumen_detail_id' => $traveling_detail->id), true);
    //die(dump($sppd_detail_value));

        $body = array(
            'data_pasien'           => object_to_array($data_pasien),
            'ktp_pasien'            => object_to_array($ktp_detail),
            'kartu_penjamin_pasien' => object_to_array($kartu_penjamin_detail),
            'kartu_keluarga_pasien' => object_to_array($kartu_keluarga_detail),
            'rujukan_pasien'        => object_to_array($rujukan_detail),
            'rujukan_pasien_rs'     => object_to_array($rujukan_detail_rs),
            'rujukan_pasien_luar'   => object_to_array($rujukan_detail_luar),
            'sppd'                  => object_to_array($sppd_detail),
            'sppd_tiga'             => object_to_array($sppd_tiga_detail),
            'traveling'             => object_to_array($traveling_detail),
            'memo'             => object_to_array($rujukan_detail_memo),
        );


        $mpdf = new mPDF('utf-8','A4', 0, '', 8, 5, 15, 5, 0, 0);
        $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_ktp', $body,true));
        $mpdf->AddPage();
        $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_rujukan_puskesmas', $body,true));
        if(count($rujukan_memo))
        {
            $mpdf->AddPage();
            $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_rujukan_memo', $body,true));
        }
        if(count($rujukan_pasien_rs))
        {
            $mpdf->AddPage();
            $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_rujukan_rs', $body,true));
        }
        if(count($rujukan_pasien_luar))
        {
            $mpdf->AddPage();
            $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_rujukan_luar', $body,true));
        } 
        if(count($sppd))
        {
            $mpdf->AddPage();
            $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_sppd', $body,true));
        }
        if(count($sppd_tiga))
        {
            $mpdf->AddPage();
            $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_sppd_tiga_kali', $body,true));
        }
        
        $mpdf->AddPage();
        $mpdf->writeHTML($this->load->view('klinik_hd/history_transaksi/cetak_dokumen_traveling', $body,true));
        
        $mpdf->Output($data_pasien['no_member'].'.pdf', 'I'); 

    }

    public function add_pj($pasien_id=null)
    {
        $pasien = $this->pasien_m->get($pasien_id);

        $data = array(
            'pasien'    => $pasien
        );

        $this->load->view('reservasi/pendaftaran/modal/add_penjamin', $data);
    }

    public function search_kelurahan()
    {
        $data = array(
        
        );
        $this->load->view('reservasi/pendaftaran/modal/search_alamat');
    }

    public function listing_alamat()
    {
        $this->load->model('master/info_alamat_m');
        $result = $this->info_alamat_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
      

        foreach($records->result_array() as $row)
        {             
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-confirm="'.translate('Pilih alamat ini?', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select_alamat"><i class="fa fa-check"></i> </a>';
            
            $output['data'][] = array(
                '<div class="text-left">'.$row['kelurahan'].'</div>' ,
                '<div class="text-left">'.$row['kecamatan'].'</div>' ,
                '<div class="text-left">'.$row['kotkab'].'</div>',
                '<div class="text-left">'.$row['propinsi'].'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
        }

        echo json_encode($output);
    }

    public function save_pj()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->model('master/pasien_hubungan_m');
            $this->load->model('master/pasien_hubungan_alamat_m');
            $this->load->model('master/pasien_hubungan_telepon_m');

            $array_input = $this->input->post();

            $penanggungjawab = array(
                '2' => translate('Suami', $this->session->userdata('language')),
                '3' => translate('Istri', $this->session->userdata('language')),
                '4' => translate('Anak', $this->session->userdata('language')),
                '5' => translate('Ayah', $this->session->userdata('language')),
                '6' => translate('Ibu', $this->session->userdata('language')),
                '7' => translate('Lain - lain', $this->session->userdata('language'))
            );

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Penanggungjawab tindakan gagal ditambahkan', $this->session->userdata('language'));

            $path_dokumen = './assets/mb/pages/master/pasien/images/'.$array_input['no_member'].'/penanggung';
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

            $temp_filename = $array_input['url_ktp'];

            $convtofile = new SplFileInfo($temp_filename);
            $extenstion = ".".$convtofile->getExtension();

            $new_filename = str_replace(' ', '_', $array_input['no_ktp']).$extenstion;
            $real_file = $array_input['no_member'].'/penanggung/'.$new_filename;

            copy($_SERVER['DOCUMENT_ROOT'] .config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$real_file);
            

            $data_hub_pasien = array(
                'pasien_id'     => $array_input['id_pasien'],
                'tipe_hubungan' => 2,
                'tipe_pj'       => $array_input['tipe_pj'],
                'alias_pj'      => $array_input['alias_pj'],
                'nama'          => $array_input['nama'],
                'no_ktp'        => $array_input['no_ktp'],
                'url_ktp'       => $new_filename,
                'is_primary'    => 0,
                'is_active'     => 1
            );

            $path_model = 'master/pasien_hubungan_m';
            $cabang = $this->cabang_m->get_by('tipe in (0,1)');
            foreach ($cabang as $row_cabang) 
            {
                if($row_cabang->is_active == 1)
                {
                    if($row_cabang->url != '' && $row_cabang->url != NULL)
                    {
                        $save_pasien_hub_id = insert_data_api($data_hub_pasien,$row_cabang->url,$path_model);      
                    }
                }
            }
            $inserted_pasien_hub_id = str_replace('"', '', $save_pasien_hub_id);
            if($inserted_pasien_hub_id)
            {
                $data_hub_pasien_alamat = array(
                    'pasien_hubungan_id' => $inserted_pasien_hub_id,
                    'subjek_id'          => $array_input['subject'],
                    'alamat'             => $array_input['alamat'],
                    'rt_rw'              => $array_input['rt'].'_'.$array_input['rw'],
                    'kode_lokasi'        => $array_input['kode'],
                    'kode_pos'           => $array_input['kodepos'],
                    'is_active'          => 1,
                    'is_primary'         => 0
                ); 

                $path_model = 'master/pasien_hubungan_alamat_m';
                $data_cabang = $this->cabang_m->get_by('tipe in (0,1)');
                foreach ($data_cabang as $row_data_cabang) 
                {
                    if($row_data_cabang->is_active == 1)
                    {
                        if($row_data_cabang->url != '' && $row_data_cabang->url != NULL)
                        {
                            $save_pasien_hub_alamat_id = insert_data_api($data_hub_pasien_alamat,$row_data_cabang->url,$path_model); 
                        }
                    }
                }

                $data_hub_pasien_telepon = array(
                    'pasien_hubungan_id' => $inserted_pasien_hub_id,
                    'subjek_id'          => $array_input['subject_telepon'],
                    'nomor'              => $array_input['no_telepon'],
                    'is_active'          => 1,
                    'is_primary'         => 0
                ); 
                $path_model = 'master/pasien_hubungan_telepon_m';
                $data_cabang = $this->cabang_m->get_by('tipe in (0,1)');
                foreach ($data_cabang as $row_data_cabang) 
                {
                    if($row_data_cabang->is_active == 1)
                    {
                        if($row_data_cabang->url != '' && $row_data_cabang->url != NULL)
                        {
                            $save_pasien_hub_tlp_id = insert_data_api($data_hub_pasien_telepon,$row_data_cabang->url,$path_model); 
                        }
                    }
                }

                $tipe_option = array(
                    '1' => translate('Diri Sendiri', $this->session->userdata('language')), 
                );

                $tipe_pj = $this->pasien_hubungan_m->get_data_pj_is_active($array_input['id_pasien']);
                foreach ($tipe_pj as $pj) {
                    $tipe_option[$pj['tipe_pj']] = $penanggungjawab[$pj['tipe_pj']];
                }
                $response->success = true;
                $response->pj_id = $inserted_pasien_hub_id;
                $response->tipe_pj_id = $array_input['tipe_pj'];
                $response->tipe_pj_option = $tipe_option;
                $response->msg = translate('Penanggungjawab tindakan berhasil ditambahkan', $this->session->userdata('language'));
                
                die(json_encode($response));
            }
        }
    }

    public function get_penanggungjawab()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->model('master/pasien_hubungan_m');
            $this->load->model('master/pasien_hubungan_alamat_m');
            $this->load->model('master/pasien_hubungan_telepon_m');
            $pasien_id = $this->input->post('pasienid');

            $response = new stdClass;

            $penanggungjawab = array(
                '2' => translate('Suami', $this->session->userdata('language')),
                '3' => translate('Istri', $this->session->userdata('language')),
                '4' => translate('Anak', $this->session->userdata('language')),
                '5' => translate('Ayah', $this->session->userdata('language')),
                '6' => translate('Ibu', $this->session->userdata('language')),
                '7' => translate('Lain - lain', $this->session->userdata('language'))
            );

            $tipe_option = array(
                '1' => translate('Diri Sendiri', $this->session->userdata('language')), 
            );

            $tipe_pj = $this->pasien_hubungan_m->get_data_pj_is_active($pasien_id);
            foreach ($tipe_pj as $pj) {
                $tipe_option[$pj['tipe_pj']] = $penanggungjawab[$pj['tipe_pj']];
            }

            $response->tipe_pj_option = $tipe_option;
                
            die(json_encode($response));

        }
    }

    public function get_data_pj_pasien()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->model('master/pasien_hubungan_m');
            $this->load->model('master/pasien_hubungan_alamat_m');
            $this->load->model('master/pasien_hubungan_telepon_m');
            $pasien_id = $this->input->post('pasienid');
            $tipe_pj = $this->input->post('tipe_pj');

            $response = new stdClass;

            $nama_pj_option = $this->pasien_hubungan_m->get_nama_pj_is_active($pasien_id, $tipe_pj);
            
            $response->nama_pj_option = $nama_pj_option;
                
            die(json_encode($response));

        }
    }

    public function get_alamat_telepon_pj()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;

            $this->load->model('master/info_alamat_m');
            $this->load->model('master/pasien_hubungan_alamat_m');
            $this->load->model('master/pasien_hubungan_telepon_m');

            $pasien_hubungan_id = $this->input->post('pasien_hub_id');

            $pasien_hub_alamat = $this->pasien_hubungan_alamat_m->get_data($pasien_hubungan_id)->result_array();
            $pasien_hub_telepon = $this->pasien_hubungan_telepon_m->get_data($pasien_hubungan_id)->result_array();

            if(count($pasien_hub_alamat) != 0)
            {
                $pj_data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $pasien_hub_alamat[0]['kode_lokasi']),true);

                $response->success = true;
                $response->hub_alamat = $pasien_hub_alamat[0];
                if(count($pj_data_lokasi))
                {
                    $response->form_kel_alamat  = $pj_data_lokasi->nama_kelurahan;
                    $response->form_kec_alamat  = $pj_data_lokasi->nama_kecamatan;
                    $response->form_kota_alamat = $pj_data_lokasi->nama_kabupatenkota;            
                }
            }
            if(count($pasien_hub_telepon) != 0)
            {
                $response->success = true;
                $response->hub_telepon = $pasien_hub_telepon[0];
            }

            die(json_encode($response));

        }
    }

    public function get_jadwal_tindakan()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;

            $shift = $this->input->post('shift');
            $url = $this->session->userdata('url_login');
            $cabang_id = $this->session->userdata('cabang_id');

            $jadwal_pasien = get_data_pasien_tindakan($url,$shift,$cabang_id);
            if(count($jadwal_pasien['jadwal']) != 0)
            {
                $response->success = true;
                $response->rows = $jadwal_pasien['jadwal'];
            }
            $response->jumlah_all = count($jadwal_pasien['jadwal_all']);

            die(json_encode($response));

        }
    }

    public function get_data_pasien_non_tindakan()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;

            $shift = $this->input->post('shift');
            $url = $this->session->userdata('url_login');
            $cabang_id = $this->session->userdata('cabang_id');

            $tindakan_pasien = get_data_pasien_non_tindakan($url,$shift,$cabang_id);
            if(count($tindakan_pasien['tindakan']) != 0)
            {
                $response->success = true;
                $response->rows = $tindakan_pasien['tindakan'];
            }

            die(json_encode($response));

        }
    }
}

/* End of file pendaftaran_tindakan.php */
/* Location: ./application/controllers/reservasi/pendaftaran_tindakan.php */