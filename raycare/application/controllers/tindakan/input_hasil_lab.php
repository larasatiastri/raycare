<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Input_hasil_lab extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'e903aba7c58bb5ff5471ef9e7051e2ea';                  // untuk check bit_access

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

        
        $this->load->model('master/laboratorium_klinik_m');
        $this->load->model('master/kategori_pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_detail_m');
        $this->load->model('tindakan/hasil_lab_m');
        $this->load->model('tindakan/hasil_lab_detail_m');
        $this->load->model('tindakan/hasil_lab_dokumen_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/info_alamat_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/tindakan/input_hasil_lab/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Input Hasil Lab', $this->session->userdata('language')), 
            'header'         => translate('Input Hasil Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'tindakan/input_hasil_lab/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/tindakan/input_hasil_lab/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data_hasil_lab = $this->hasil_lab_m->get_by(array('id' => $id), true);
        $data_hasil_lab_detail = $this->hasil_lab_detail_m->get_by(array('hasil_lab_id' => $id));
        $data_hasil_lab_dokumen = $this->hasil_lab_dokumen_m->get_by(array('hasil_lab_id' => $id));

        $lab_klinik = $this->laboratorium_klinik_m->get_by(array('id' => $data_hasil_lab->laboratorium_klinik_id), true);

        $data = array(
            'title'                     => config_item('site_name').' | '.translate('View Input Hasil Lab', $this->session->userdata('language')), 
            'header'                    => translate('View Input Hasil Lab', $this->session->userdata('language')), 
            'header_info'               => config_item('site_name'), 
            'breadcrumb'                => true,
            'menus'                     => $this->menus,
            'menu_tree'                 => $this->menu_tree,
            'css_files'                 => $assets['css'],
            'js_files'                  => $assets['js'],
            'data_hasil_lab'            => object_to_array($data_hasil_lab),
            'data_hasil_lab_detail'     => object_to_array($data_hasil_lab_detail),
            'data_hasil_lab_dokumen'    => object_to_array($data_hasil_lab_dokumen),
            'lab_klinik'                => object_to_array($lab_klinik),
            'content_view'              => 'tindakan/input_hasil_lab/view',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/tindakan/input_hasil_lab/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah Input Hasil Lab", $this->session->userdata("language")), 
            'header'         => translate("Tambah Input Hasil Lab", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'tindakan/input_hasil_lab/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        $result = $this->hasil_lab_m->get_datatable(2);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));

        $i=0;
        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'tindakan/input_hasil_lab/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';
                 
                
            $output['data'][] = array(
                $row['id'],
                '<div class="text-left inline-button">'.date('d M Y', strtotime($row['tanggal'])).'</div>' ,                
                $row['no_hasil_lab'],                
                $row['nama_lab_klinik'],                
                $row['nama_pasien'],                
                $row['usia'],                
                $row['dokter'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listing_pilih_pasien()
    {
        
        $result = $this->pasien_m->get_datatable_pilih_pasien_penjualan_obat();
        // die_dump($this->db->last_query());

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
            
            $action = '';
            $row['alamat'] = $row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['propinsi'];

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($row['tanggal_lahir']);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%y Tahun %m Bulan %d Hari');

            $row['usia'] = $elapsed;

            $row['umur'] = intval($row['umur']);

            $kategori_umur = '';
            if($row['umur'] >= 0 AND $row['umur'] <= 1){
                $kategori_umur = '1';
            }if($row['umur'] > 1 AND $row['umur'] <= 12){
                $kategori_umur = '2';
            }if($row['umur'] >= 13 AND $row['umur'] <= 50){
                $kategori_umur = '3';
            }if($row['umur'] > 50){
                $kategori_umur = '4';
            }

            $row['kategori_umur'] = $kategori_umur;

            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select-pasien"><i class="fa fa-check"></i></a>';          
            }


            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listing_item($kategori_umur_id)
    {

        $result = $this->pemeriksaan_lab_m->get_datatable();
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));

        $i=0;
        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            
            $pemeriksaan_lab_detail = $this->pemeriksaan_lab_detail_m->get_by(array('pemeriksaan_lab_id' => $row['id'], 'kategori_usia_id' => $kategori_umur_id), true);
            $pemeriksaan_lab_detail = object_to_array($pemeriksaan_lab_detail);
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-item_detail ="'.htmlentities(json_encode($pemeriksaan_lab_detail)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                $row['tipe'],                
                $row['nama_kategori'],                
                $row['nama'],                
                $row['satuan'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function save()
    {
        $array_input = $this->input->post();
        // die(dump($array_input));

        if($array_input['command'] == 'add'){

            $last_number   = $this->hasil_lab_m->get_max_id_hasil_lab()->result_array();
            $last_number   = intval($last_number[0]['max_id'])+1;
            $format        = 'HL-'.date('m-Y').'%04d';
            $id_hasil     = sprintf($format, $last_number, 4);

            $data = array(
                'id'                        => $id_hasil,
                'tanggal'                   => date('Y-m-d', strtotime($array_input['tanggal'])),
                'no_hasil_lab'              => $array_input['no_hasil_lab'],
                'laboratorium_klinik_id'    => $array_input['laboratorium_klinik_id'],
                'pasien_id'                 => $array_input['id_ref_pasien'],
                'usia'                      => $array_input['usia'],
                'kategori_usia_id'          => $array_input['kategori_usia_id'],
                'dokter'                    => $array_input['nama_dokter'],
                'is_active'                 => 1,
                'created_by'                => $this->session->userdata('user_id'),
                'created_date'              => date('Y-m-d H:i:s')
            );

            $save_hasil_lab = $this->hasil_lab_m->add_data($data);


            foreach ($array_input['item'] as $key => $item) {

                $last_number_detail   = $this->hasil_lab_detail_m->get_max_id_hasil_lab_detail()->result_array();
                $last_number_detail   = intval($last_number_detail[0]['max_id'])+1;
                $format_detail        = 'HLD-'.date('m-Y').'%04d';
                $id_hasil_detail     = sprintf($format_detail, $last_number_detail, 4);

                $data_detail = array(
                    'id'                        => $id_hasil_detail,
                    'hasil_lab_id'              => $id_hasil,
                    'pemeriksaan_lab_id'        => $item['item_id'],
                    'pemeriksaan_lab_detail_id' => $item['id_detail'],
                    'hasil'                     => $item['hasil'],
                    'is_active'                 => 1,
                    'created_by'                => $this->session->userdata('user_id'),
                    'created_date'              => date('Y-m-d H:i:s')
                );

                $save_hasil_lab_detail = $this->hasil_lab_detail_m->add_data($data_detail);
            }


            foreach ($array_input['hasil_lab'] as $key => $hasil_lab) {
                if($hasil_lab['url'] != ''){
                    $last_id_dok   = $this->hasil_lab_dokumen_m->get_max_id_hasil_lab_dokumen()->result_array();
                    $last_id_dok   = intval($last_id_dok[0]['max_id'])+1;
                    $format_dokumen        = 'HDK-'.date('m-Y').'%04d';
                    $id_dokumen     = sprintf($format_dokumen, $last_id_dok, 4);

                    $data_dokumen = array(
                        'id'                        => $id_dokumen,
                        'hasil_lab_id'              => $id_hasil,
                        'url'                       => $hasil_lab['url'],
                        'is_active'                 => 1,
                        'created_by'                => $this->session->userdata('user_id'),
                        'created_date'              => date('Y-m-d H:i:s')
                    );

                    $save_hasil_lab_dokumen = $this->hasil_lab_dokumen_m->add_data($data_dokumen);

                    $path_dokumen = '../cloud/'.config_item('site_dir').'pages/tindakan/input_hasil_lab/images/'.str_replace(' ', '_', $id_hasil);
                    if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                    $temp_filename = $hasil_lab['url'];

                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = $hasil_lab['url'];
                    $real_file = str_replace(' ', '_', $id_hasil).'/'.$new_filename;

                    copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_hasil_lab').$real_file);
                }
            }
        }
        redirect('tindakan/input_hasil_lab');
        
    }


    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $rujukan_id = $this->surat_traveling_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        // die_dump($max_id);
        
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 8,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->save($data_trash);
        // die_dump($this->db->last_query());
        
        if ($trash || $rujukan_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("tindakan/input_hasil_lab");
    }

    public function search_pasien_by_nomor()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Data Pasien Tidak Ditemukan', $this->session->userdata('language'));

            $no_pasien = $this->input->post('no_pasien');

            $pasien = $this->pasien_m->get_pasien_by_nomor($no_pasien)->row(0);

            if(count($pasien))
            {
                if ($pasien->url_photo != '') 
                {
                    if (file_exists(FCPATH.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo) && is_file(FCPATH.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo)) 
                    {
                        $pasien->url_photo = base_url().config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo;
                    }
                    else
                    {
                        $pasien->url_photo = base_url().config_item('site_img_pasien').'global/global.png';
                    }
                } else {

                    $pasien->url_photo = base_url().config_item('site_img_pasien').'global/global.png';
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

    public function get_harga()
    {
        if($this->input->is_ajax_request()){
            $item_id = $this->input->post('item_id');
            $item_satuan_id = $this->input->post('item_satuan_id');

            $response = new stdClass;
            $response->success = false;

            $harga_item = $this->item_harga_m->get_harga_item_satuan($item_id,$item_satuan_id)->row(0);
            if(count($harga_item) != 0){
                $response->success = true;
                $response->harga = $harga_item->harga;
            }

            die(json_encode($response));

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

/* End of file surat_traveling.php */
/* Location: ./application/controllers/tindakan/input_hasil_lab.php */