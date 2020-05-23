<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class verifikasi_pendaftaran extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'cf515e756907e6b901a6d24a10b855d0';                  // untuk check bit_access

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

        
        $this->load->model('klaim/verifikasi_pendaftaran/pendaftaran_tindakan_m');
        $this->load->model('klaim/verifikasi_pendaftaran/pendaftaran_tindakan_history_m');

        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_penjamin_m');
        
        $this->load->model('master/cabang_m');
        $this->load->model('master/poliklinik_m');
        
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('others/jenis_peserta_bpjs_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klaim/verifikasi_pendaftaran/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Verifikasi Pendaftaran', $this->session->userdata('language')), 
            'header'         => translate('Verifikasi Pendaftaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/verifikasi_pendaftaran/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_verifikasi_pendaftaran','history'))
        {
            $assets = array();
            $config = 'assets/klaim/verifikasi_pendaftaran/history';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            $data = array(
                'title'          => config_item('site_name').' | '. translate("History Verifikasi Pendaftaran", $this->session->userdata("language")), 
                'header'         => translate("History Verifikasi Pendaftaran", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'klaim/verifikasi_pendaftaran/history',
            );

            // Load the view
            $this->load->view('_layout', $data);
        }else{
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }
    
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        
        $result = $this->pendaftaran_tindakan_m->get_datatable();
        // die_dump($this->db->last_query());
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
           
            $action = '';
            $shift = '';
            $proses     = '<a title="'.translate('Proses', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_modal_proses" href="'.base_url().'klaim/verifikasi_pendaftaran/modal_proses/'.$row['id'].'/'.$row['pasien_id'].'/'.$row['pasien_penjamin_id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
            $tolak      = '<a title="'.translate('Tolak', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_modal_tolak" href="'.base_url().'klaim/verifikasi_pendaftaran/modal_tolak/'.$row['id'].'" class="btn red-intense"><i class="fa fa-times"></i></a>';
            
            $action =  restriction_button($proses, $user_level_id, 'klaim_verifikasi_tindakan', 'proses').restriction_button($tolak, $user_level_id, 'klaim_verifikasi_tindakan', 'tolak');

            if ($row['pasien_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['pasien_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['pasien_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['pasien_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }

            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }


            $output['data'][] = array(
                $row['no_member'],
                '<div>'.$img_url.$row['nama_pasien'].'</div>',
                '<div class="text-center">'.$shift.' '.date('d F Y H:i:s', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.$row['nama_poliklinik'].'</div>',
                '<div class="text-left">'.$row['nama_penjamin'].'('.$row['no_kartu'].')</div>',
                '<div class="text-center">'.$row['nama_user'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_history()
    {        
        $result = $this->pendaftaran_tindakan_history_m->get_datatable_history();
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');

        foreach($records->result_array() as $row)
        {
           
            $status = '';
            $shift = '';
            if ($row['status_verif'] == 2) {
                $status = '<span class="label bg-green-haze">'.translate("Disetujui", $this->session->userdata("language")).'</span>';
            }elseif ($row['status_verif'] == 3) {
                $status = '<span class="label bg-red-intense">'.translate("Ditolak", $this->session->userdata("language")).'</span>';
            }

            if ($row['pasien_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['pasien_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['pasien_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['pasien_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }

            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }

            $output['data'][] = array(
                '<div>'.$img_url.$row['nama_pasien'].' ['.$row['no_member'].']</div>',
                '<div class="text-center">'.$shift.' '.date('d F Y H:i:s', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.$row['nama_poliklinik'].'</div>',
                '<div class="text-left">'.$row['nama_penjamin'].'('.$row['no_kartu'].')</div>',
                '<div class="text-center">'.$row['nama_user'].'</div>',
                '<div class="text-center">'.$row['user_verif'].'</div>',
                '<div class="text-center">'.date('d F Y H:i:s', strtotime($row['tanggal_verif'])).'</div>',
                '<div class="text-center">'.$status.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }



    public function modal_proses($pendaftaran_tindakan_id, $pasien_id, $pasien_penjamin_id)
    {
       $data = array(
            'pendaftaran_tindakan_id' => $pendaftaran_tindakan_id,
            'pasien_id' => $pasien_id,
            'pasien_penjamin_id' => $pasien_penjamin_id,
        );
       $this->load->view('klaim/verifikasi_pendaftaran/modal/proses', $data);
    }

    public function modal_tolak($pendaftaran_tindakan_id)
    {
       $data = array(
            'pendaftaran_tindakan_id' => $pendaftaran_tindakan_id,
        );
       $this->load->view('klaim/verifikasi_pendaftaran/modal/tolak', $data);
    }

    public function save()
    {
        $array_input = $this->input->post();
        // die_dump($array_input);
        $data_pendaftaran = array(
            'poliklinik_id' => $array_input['poliklinik_id'],
            'status_verif' => '2',
            'tanggal_verif' => date('Y-m-d H:i:s'),
            'user_verif_id' => $this->session->userdata('user_id'),
        );

        $path_model = 'klaim/verifikasi_pendaftaran/pendaftaran_tindakan_m';
        
        $edit_pendaftaran = $this->pendaftaran_tindakan_m->edit_data($data_pendaftaran, $array_input['pendaftaran_tindakan_id']);
        // $inserted_pendaftaran_tindakan_id = $pendaftaran_tindakan_id;

        // $cabang = $this->cabang_m->get($array_input['cabang_id']);
        

        // if($cabang->url != '' || $cabang->url != NULL)
        // {
        //     $pendaftaran_tindakan_id = insert_data_api($data_pendaftaran,$cabang->url,$path_model,$inserted_pendaftaran_tindakan_id);
        // }

        // $inserted_pendaftaran_tindakan_id = str_replace('"', '', $inserted_pendaftaran_tindakan_id);

        $data_sep_tindakan = array(
            'cabang_id'     => $array_input['cabang_id'],
            'pasien_id'     => $array_input['pasien_id'],
            'tindakan_id'   => $array_input['pendaftaran_tindakan_id'],
            'poliklinik_id' => $array_input['poliklinik_id'],
            'tipe_tindakan' => 0,
            'jenis_peserta' => $array_input['peserta'],
            'cob'           => $array_input['cob'],
            'jenis_rawat'   => $array_input['jenis_rawat'],
            'kelas_rawat'   => $array_input['kelas_rawat'],
            'catatan'       => $array_input['catatan'],
            'is_active'     => 1
        );

        $path_model = 'klaim/verifikasi_pendaftaran/sep_tindakan_m';
        
        $sep_tindakan_id = insert_data_api($data_sep_tindakan,base_url(),$path_model);
        $inserted_sep_tindakan_id = $sep_tindakan_id;

        if($cabang->url != '' || $cabang->url != NULL)
        {
            $sep_tindakan_id = insert_data_api($data_sep_tindakan,$cabang->url,$path_model,$inserted_sep_tindakan_id);
        }

        $inserted_pendaftaran_tindakan_id = str_replace('"', '', $inserted_pendaftaran_tindakan_id);


        $data_pasien_penjamin = array(
            'no_kartu' => $array_input['no_bpjs'],
        );

        $path_model = 'master/pasien_penjamin_m';
        
        $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model, $array_input['pasien_penjamin_id']);
        $inserted_pasien_penjamin_id = $pasien_penjamin_id;

        if($cabang->url != '' || $cabang->url != NULL)
        {
            $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$inserted_pasien_penjamin_id);
        }

        $data_faskes_pasien = array(
            'ref_kode_rs_rujukan' => $array_input['asal_faskes'],
        );

        $path_model = 'master/pasien_m';
        
        $faskes_pasien_id = insert_data_api($data_faskes_pasien,base_url(),$path_model, $array_input['pasien_id']);

        if($cabang->url != '' || $cabang->url != NULL)
        {
            $faskes_pasien_id = insert_data_api($data_faskes_pasien,$cabang->url,$path_model,$array_input['pasien_id']);
        }

        $data_pasien = $this->pasien_m->get($array_input['pasien_id']);
        $nama_pasien = str_replace(' ', '_', $data_pasien->nama);

        sent_notification(1,$nama_pasien,$inserted_pendaftaran_tindakan_id); 
        $filename = urlencode(base64_encode($this->session->userdata('url_login')));
        if($cabang->url != '' || $cabang->url != NULL)
        {
            change_file_notif($cabang->url,$filename); 
        }

        // die_dump($inserted_pasien_penjamin_id);
        if ($inserted_pendaftaran_tindakan_id) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Verifikasi Berhasil.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
            
        redirect("klaim/verifikasi_pendaftaran");
    }

    public function tolak(){
        $array_input = $this->input->post();

        $data_pendaftaran = array(
            'status_verif' => '3',
            'tanggal_verif' => date('Y-m-d H:i:s'),
            'user_verif_id' => $this->session->userdata('user_id'),
            'keterangan' => $array_input['keterangan_db'].' '.$array_input['keterangan'],
        );

        // die_dump($array_input);

        //$path_model = 'klaim/verifikasi_pendaftaran/pendaftaran_tindakan_m';
        
        // $pendaftaran_tindakan_id = insert_data_api($data_pendaftaran,base_url(),$path_model, $array_input['pendaftaran_tindakan_id']);
        // $inserted_pendaftaran_tindakan_id = $pendaftaran_tindakan_id;

        $update_pendaftaran = $this->pendaftaran_tindakan_m->edit_data($data_pendaftaran, $array_input['pendaftaran_tindakan_id']);

        // $cabang = $this->cabang_m->get($array_input['cabang_id']);
        

        // if($cabang->url != '' || $cabang->url != NULL)
        // {
        //     $pendaftaran_tindakan_id = insert_data_api($data_pendaftaran,$cabang->url,$path_model,$inserted_pendaftaran_tindakan_id);
        // }

        // $inserted_pendaftaran_tindakan_id = str_replace('"', '', $inserted_pendaftaran_tindakan_id);

        $data_pasien = $this->pasien_m->get($array_input['pasien_id']);
        $nama_pasien = str_replace(' ', '_', $data_pasien->nama);

        //sent_notification(4,$nama_pasien,$array_input['pendaftaran_tindakan_id']); 
        $filename = urlencode(base64_encode($this->session->userdata('url_login')));
        $all_cabang = $this->cabang_m->get();
        foreach ($all_cabang as $cabang) 
        {
            change_file_notif($cabang->url,$filename);                    
        }

        // die_dump($inserted_pendaftaran_tindakan_id);
        if ($inserted_pendaftaran_tindakan_id) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Tolak Berhasil.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
            
        redirect("klaim/verifikasi_pendaftaran");
    }
}

/* End of file verifikasi_pendaftaran.php */
/* Location: ./application/controllers/klaim/verifikasi_pendaftaran.php */