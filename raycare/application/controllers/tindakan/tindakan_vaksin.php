<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_vaksin extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '45c67b8aebc2b4cb9682ae27fe4d3d1c';                  // untuk check bit_access

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

        $this->load->model('tindakan/tindakan_vaksin_m');
        $this->load->model('tindakan/tindakan_vaksin_item_m');
        $this->load->model('tindakan/notifikasi_vaksin_m');
        $this->load->model('master/master_vaksin_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/info_alamat_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/tindakan/tindakan_vaksin/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tindakan Vaksin', $this->session->userdata('language')), 
            'header'         => translate('Tindakan Vaksin', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'tindakan/tindakan_vaksin/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/tindakan/tindakan_vaksin/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Tindakan Vaksin', $this->session->userdata('language')), 
            'header'         => translate('History Tindakan Vaksin', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'tindakan/tindakan_vaksin/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add($pasien_id=null)
    {
        $assets = array();
        $assets_config = 'assets/tindakan/tindakan_vaksin/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah Tindakan Vaksin", $this->session->userdata("language")), 
            'header'         => translate("Tambah Tindakan Vaksin", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'tindakan/tindakan_vaksin/add',
            'flag'           => 'add',
            'pasien_id'      => $pasien_id
        );

        // Load the view
        $this->load->view('_layout', $data);
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


    public function listing_item()
    {
        $tanggal = date('Y-m-d');

        $result = $this->item_m->get_datatable_index_item($tanggal);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $id = $row['id'];
            $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $row['id']));
            $data_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'],'is_primary' => 1), true);

            $harga_item = $this->item_harga_m->get_harga_item_satuan($row['id'],$data_satuan_primary->id)->row(0);
            // die(dump($this->db->last_query()));
           
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($data_satuan)).'" data-satuan_primary="'.htmlentities(json_encode($data_satuan_primary)).'" data-harga="'.htmlentities(json_encode($harga_item)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
               
                $row['kode'],                
                $row['nama'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_history_vaksin($pasien_id = null, $vaksin_id = null)
    {
        $result = $this->tindakan_vaksin_m->get_datatable_history($pasien_id,$vaksin_id);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {
        
            $output['data'][] = array(
                $i,   
                $row['nama_vaksin'],                
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['nama_dokter'],                
                $row['nama_perawat'],                
                $row['nama_cabang'],                
            );
         $i++;
        }

        echo json_encode($output);
    }
    

    public function listing_history_vaksin_all()
    {
        $result = $this->tindakan_vaksin_m->get_datatable();
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {
        
            $output['data'][] = array(
                $i,   
                $row['nama_pasien'],                
                $row['nama_vaksin'],                
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['nama_dokter'],                
                $row['nama_perawat'],                
                $row['nama_cabang'],                
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();
        $cabang_login = $this->cabang_m->get($this->session->userdata('cabang_id'));
        $data_apotik = $this->cabang_m->get_by('tipe in (4)');

        $item = $array_input['item'];

        $last_id_vaksin = $this->tindakan_vaksin_m->get_max_id()->result_array();
        $last_id_vaksin = intval($last_id_vaksin[0]['max_id'])+1;

        $format_id_vaksin   = 'TVK-'.date('m').'-'.date('Y').'-%04d';
        $id_vaksin       = sprintf($format_id_vaksin, $last_id_vaksin, 4);

        $data = array(
            'id'               => $id_vaksin,
            'master_vaksin_id' => $array_input['master_vaksin_id'],
            'cabang_id'        => $this->session->userdata('cabang_id'),
            'tipe_pasien'      => $array_input['tipe_pasien'],
            'pasien_id'        => $array_input['id_ref_pasien'],
            'pasien_nama'      => $array_input['nama_ref_pasien'],
            'pasien_alamat'    => $array_input['alamat_pasien'],
            'tanggal'          => date('Y-m-d', strtotime($array_input['tanggal'])),
            'shift'            => $array_input['shift'],
            'dokter_id'        => $array_input['dokter_id'],
            'perawat_id'       => $array_input['perawat_id'],
            'status'           => 1,
            'created_by'       => $this->session->userdata('user_id'),
            'created_date'     => date('Y-m-d H:i:s'),
        );

        $tindakan_vaksin = $this->tindakan_vaksin_m->add_data($data);

        $path_model = 'tindakan/tindakan_vaksin_m';
        foreach ($data_apotik as $apotik) {
            if($apotik->url != ''){
                $tindakan_vaksin = insert_data_api_id($data,$apotik->url,$path_model);
            }
        }

        foreach ($item as $itm) {

            if($itm['item_id'] != '' && $itm['item_id'] != 0){
                $last_id_vaksin_item = $this->tindakan_vaksin_item_m->get_max_id_item()->result_array();
                $last_id_vaksin_item = intval($last_id_vaksin_item[0]['max_id'])+1;

                $format_id_vaksin_item   = 'TVKI-'.date('m').'-'.date('Y').'-%04d';
                $id_vaksin_item       = sprintf($format_id_vaksin_item, $last_id_vaksin_item, 4);

                $data_item = array(
                    'id'                 => $id_vaksin_item,
                    'tindakan_vaksin_id' => $id_vaksin,
                    'item_id'            => $itm['item_id'],
                    'item_satuan_id'     => $itm['satuan_id'],
                    'qty'                => $itm['qty'],
                    'harga_jual'         => $itm['harga'],
                    'is_active'          => 1,
                    'created_by'         => $this->session->userdata('user_id'),
                    'created_date'       => date('Y-m-d H:i:s')
                );

                $tindakan_vaksin_item = $this->tindakan_vaksin_item_m->add_data($data_item);
                $path_model = 'tindakan/tindakan_vaksin_item_m';
                foreach ($data_apotik as $apotik) {
                    if($apotik->url != ''){
                        $tindakan_vaksin_item = insert_data_api_id($data_item,$apotik->url,$path_model);
                    }
                } 
            }
        }

        if($array_input['is_lanjut'] == 1){
            $last_id_notifikasi = $this->notifikasi_vaksin_m->get_max_id()->result_array();
            $last_id_notifikasi = intval($last_id_notifikasi[0]['max_id'])+1;

            $data_notifikasi = array(
                'id'               => $last_id_notifikasi,
                'pasien_id'        => $array_input['id_ref_pasien'],
                'master_vaksin_id' => $array_input['master_vaksin_id'],
                'tanggal'          => date('Y-m-d', strtotime($array_input['tanggal_lanjut'])),
                'status'           => 1,
                'created_by'       => $this->session->userdata('user_id'),
                'created_date'     => date('Y-m-d H:i:s')
            );

            $notifikasi = $this->notifikasi_vaksin_m->add_data($data_notifikasi);

        }

        redirect('tindakan/tindakan_vaksin');
        
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
        redirect("tindakan/tindakan_vaksin");
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

}

/* End of file surat_traveling.php */
/* Location: ./application/controllers/tindakan/tindakan_vaksin.php */