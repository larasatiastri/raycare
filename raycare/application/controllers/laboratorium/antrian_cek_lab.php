<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Antrian_cek_lab extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd5d9eb70f1f6fe99f08e02b08750fca4';                  // untuk check bit_access

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

        $this->load->model('laboratorium/pendaftaran_tindakan_m');  
        $this->load->model('laboratorium/pendaftaran_tindakan_history_m');  
        $this->load->model('reservasi/antrian/antrian_m');  
        $this->load->model('master/kategori_pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_detail_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('tindakan/tindakan_lab_m');
        $this->load->model('tindakan/tindakan_lab_detail_m');
        $this->load->model('reservasi/invoice/draf_invoice_m');   
        $this->load->model('reservasi/invoice/draf_invoice_detail_m'); 
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/laboratorium/antrian_cek_lab/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Antrian Lab', $this->session->userdata('language')), 
            'header'         => translate('Antrian Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laboratorium/antrian_cek_lab/index',
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
        $result = $this->pendaftaran_tindakan_m->get_datatable();

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
            $status = '';
            
            $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'laboratorium/antrian_cek_lab/proses/'.$row['id'].'" ><i class="fa fa-check"></i></a>
            <a title="'.translate('Batal', $this->session->userdata('language')).'" class="btn btn-danger batal" id="btn_batal_'.$i.'" data-index="'.$i.'" data-id="'.$row['id'].'"><i class="fa fa-times"></i></a>';
            
            $output['data'][] = array(
                $row['id'],
                '<div class="text-center inline-button-table">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$row['no_telp'].'</div>',
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
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function proses($id)
    {
        $assets = array();
        $config = 'assets/laboratorium/antrian_cek_lab/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $pendaftaran = $this->pendaftaran_tindakan_m->get_by(array('id' => $id), true);
        $kategori_lab = $this->kategori_pemeriksaan_lab_m->get_tipe_kategori()->result_array();

        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Antrian Lab', $this->session->userdata('language')), 
            'header'         => translate('Antrian Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laboratorium/antrian_cek_lab/proses',
            'pendaftaran'    =>  object_to_array($pendaftaran),
            'kategori_lab'    =>  object_to_array($kategori_lab),
            'pk_value'      => $id,
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function save()
    {
        $array_input = $this->input->post();

        // die(dump($array_input));
        $cabang_id = $this->session->userdata('cabang_id');

        $cabang_setting = $this->cabang_setting_m->get_by(array('cabang_id' => $cabang_id), true);
        if($array_input['command'] === 'add'){

            $last_id          = $this->tindakan_lab_m->get_max_id()->result_array();
            $last_id          = intval($last_id[0]['max_id'])+1;

            $format_id        = 'TL-'.date('m').'-'.date('Y').'-%04d';
            $id_tindakan_lab  = sprintf($format_id, $last_id, 4);

            $format_nomor        = 'LAB/'.date('m').'/'.date('Y').'/%04d';
            $nomor_lab  = sprintf($format_nomor, $last_id, 4);

            $value_tindakan_lab = array(
                'id'                        => $id_tindakan_lab,
                'laboratorium_klinik_id'    => 'LK-122018-0001',
                'no_pemeriksaan'            => $nomor_lab,
                'cabang_id'            => $this->session->userdata('cabang_id'),
                'pasien_id'     => $array_input['id_ref_pasien'],
                'nama_pasien'     => $array_input['nama_ref_pasien'],
                'tipe_pasien'     => $array_input['tipe_pasien'],
                'jenis_kelamin'     => $array_input['jenis_kelamin'],
                'umur_pasien'     => $array_input['umur_pasien'],
                'tanggal_lahir'     => date('Y-m-d', strtotime($array_input['tanggal_lahir'])),
                'alamat_pasien'     => $array_input['alamat_pasien'],
                'no_telp_pasien'     => $array_input['no_telp'],
                'tanggal'     => date('Y-m-d'),
                'nama_dokter'     => $array_input['dokter_pengirim'],
                'alamat_dokter'     => $array_input['alamat'],
                'no_telp_dokter'     => $array_input['no_telp_dokter'],
                'diagnosa_klinis'     => $array_input['diagnosa_klinis'],
                'status'     => 1,
                'created_by'     => $this->session->userdata('user_id'),
                'created_date'   => date('Y-m-d H:i:s')
            );

            $insert_tindakan_lab = $this->tindakan_lab_m->add_data($value_tindakan_lab);

            $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
            $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
            
            $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
            $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

            $data_draft_tindakan = array(
                'id'    => $id_draft,
                'pasien_id'    => $array_input['id_ref_pasien'],
                'tipe'  => 1,
                'cabang_id'  => $this->session->userdata('cabang_id'),
                'tipe_pasien'  => $array_input['tipe_pasien'],
                'nama_pasien'  => $array_input['nama_ref_pasien'],
                'user_level_id'  => $this->session->userdata('level_id'),
                'jenis_invoice' => 3,
                'akomodasi'     => $cabang_setting->akomodasi_lab,
                'status'    => 1,
                'is_active'    => 1,
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s')
            );

            $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);

            foreach ($array_input['input'] as $input) {
                
                $urutan = $input['urutan'];

                foreach ($array_input['input_kategori_'.$urutan] as $input_kategori) {
                    $kategori_id = $input_kategori['id'];

                    if(isset($array_input['input_'.$kategori_id])){
                        foreach ($array_input['input_'.$kategori_id] as $pemeriksaan) {

                           
                            if(isset($pemeriksaan['pilih'])){
                                $last_id_detail          = $this->tindakan_lab_detail_m->get_max_id()->result_array();
                                $last_id_detail          = intval($last_id_detail[0]['max_id'])+1;

                                $format_id_detail        = 'TLD-'.date('m').'-'.date('Y').'-%04d';
                                $id_tindakan_lab_detail  = sprintf($format_id_detail, $last_id_detail, 4);

                                $value_tindakan_lab_detail = array(
                                    'id'        => $id_tindakan_lab_detail,
                                    'tindakan_lab_id'        => $id_tindakan_lab,
                                    'tindakan_id'        => $pemeriksaan['tindakan_id'],
                                    'harga_jual'        => $pemeriksaan['harga'],
                                    'pemeriksaan_lab_id'        => $pemeriksaan['pilih'],
                                    'kode'        => $pemeriksaan['kode'],
                                    'nama_pemeriksaan'        => $pemeriksaan['nama'],
                                    'status'                => 1,
                                    'created_by'     => $this->session->userdata('user_id'),
                                    'created_date'   => date('Y-m-d H:i:s')
                                );

                                $insert_tindakan_lab_detail = $this->tindakan_lab_detail_m->add_data($value_tindakan_lab_detail);

                                $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                                $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                                
                                $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                                $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);
                                

                                $data_draft_tindakan_detail = array(
                                    'id'    => $id_draft_detail,
                                    'draf_invoice_id'    => $id_draft,
                                    'tipe_item' => 2,
                                    'item_id'   => $pemeriksaan['tindakan_id'],
                                    'nama_tindakan' => $pemeriksaan['nama'],
                                    'harga_jual'             => $pemeriksaan['harga'],
                                    'status' => 1,
                                    'jumlah' => 1,
                                    'is_active'    => 1,
                                    'created_by'    => $this->session->userdata('user_id'),
                                    'created_date'    => date('Y-m-d H:i:s')
                                );

                                $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                            }
                        
                            
                        } 
                    }
                    
                }
            }

            $data_daftar = array(
                'id'        => $tindakan_lab_id,
                'cabang_id'    => $cabang_id,
                'poliklinik_id'    => 3,
                 'pasien_id'     => $array_input['id_ref_pasien'],
                'nama_pasien'     => $array_input['nama_ref_pasien'],
                'tipe_pasien'     => $array_input['tipe_pasien'],
                'no_telp'     => $array_input['no_telp'],
                'penjamin_id' => 1,
                'penanggung_jawab_id' => 1,
                'status' => 3,
                'is_active' => 1,
            );

            $save_pendaftaran = $this->pendaftaran_tindakan_history_m->add_data($data_daftar);

            $delete_daftar = $this->pendaftaran_tindakan_m->delete_by(array('id' => $array_input['pendaftaran_id']));

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Pengisian formulir cek lab berhasil diproses", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect("laboratorium/antrian_cek_lab");
        }
    }

    public function get_umur_pasien()
    {
        if($this->input->is_ajax_request()){

            $tanggal_lahir = $this->input->post('tanggal_lahir');
            $tanggal_periksa = $this->input->post('tanggal_periksa');

            $datetime1 = new DateTime($tanggal_periksa);
            $datetime2 = new DateTime($tanggal_lahir);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%y Tahun %m Bulan %d Hari');

            $response = new stdClass;
            $response->success = true;
            $response->umur = $elapsed;
            
            die(json_encode($response));
        }
    }

}

/* End of file surat_dokter_sppd.php */
/* Location: ./application/controllers/laboratorium/antrian_cek_lab.php */