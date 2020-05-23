<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Antrian_tensi_bb extends MY_Controller {

    protected $menu_id = '7935f149dd9c1c8d88ce7296c2fdcd4b';                  // untuk check bit_access
    
    private $menus     = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level  = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_m');  
        $this->load->model('reservasi/antrian/antrian_pasien_m');  
        $this->load->model('master/pasien_m');
        $this->load->model('master/cabang_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/antrian_tensi_bb/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_antrian = $this->antrian_pasien_m->get_data_loket_panggil(2)->row(0);

        $list_antrian = $this->antrian_pasien_m->get_data_loket(2)->result_array();

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Antrian Tensi-BB', $this->session->userdata('language')), 
            'header'         => translate('Antrian Tensi-BB', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_antrian'   => object_to_array($data_antrian),
            'list_antrian'   => object_to_array($list_antrian),
            'content_view'   => 'klinik_hd/antrian_tensi_bb/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function history()
    {
        $assets = array();
        $config = 'assets/klinik_hd/antrian_tensi_bb/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Antrian Tensi-BB', $this->session->userdata('language')), 
            'header'         => translate('History Antrian Tensi-BB', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/antrian_tensi_bb/history',
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
        $cabang_id=$this->session->userdata('cabang_id');

        $result = $this->pendaftaran_tindakan_m->get_datatable_antri($cabang_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=1;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {   

            $action = '';
            $shift = '';

            $user_level_id = $this->session->userdata('level_id');
            
            $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'"  name="view" data-index="'.$i.'" data-id="'.$row['id'].'" data-dokter_id="'.$row['dokter_id'].'"
            data-shift="'.$row['shift'].'" class="btn btn-primary proses"><i class="fa fa-check"></i></a>';
            
            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }
            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }

       
            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>',
                '<div class="text-left">'.$img_url.$shift.' '.$row['nama'].'</div>',
                '<div class="text-left">'.ucwords(strtolower($row['alamat'])).', '.ucwords(strtolower($row['kelurahan'])).', '.ucwords(strtolower($row['kecamatan'])).', '.ucwords(strtolower($row['kota'])).'</div>',
                '<div class="text-left"><div class="input-group"><input type="number" name="bb_'.$i.'" id="bb_'.$i.'" class="form-control" min="0"><span class="input-group-addon">
                                    Kg
                                </span></div></div>',
                '<div class="text-left"><div class="input-group"><input type="number" name="tensi_atas_'.$i.'" id="tensi_atas_'.$i.'" class="form-control" min="0"><span class="input-group-addon">/</span><input type="number" name="tensi_bwh_'.$i.'" id="tensi_bwh_'.$i.'" class="form-control" min="0"></div></div>',
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
        $cabang_id=$this->session->userdata('cabang_id');

        $result = $this->pendaftaran_tindakan_m->get_datatable_antri_history($cabang_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=1;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {   

            $action = '';
            $shift = '';

            $user_level_id = $this->session->userdata('level_id');
            
            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }
            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }

       
            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>',
                '<div class="text-left">'.$img_url.$shift.' '.$row['nama'].'</div>',
                '<div class="text-left">'.ucwords(strtolower($row['alamat'])).', '.ucwords(strtolower($row['kelurahan'])).', '.ucwords(strtolower($row['kecamatan'])).', '.ucwords(strtolower($row['kota'])).'</div>',
                '<div class="text-left">'.$row['berat_badan'].' Kg</div>',
                '<div class="text-left">'.str_replace('_', ' / ', $row['tekanan_darah']).'</div>',
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Berat Badan dan Tekanan Darah Pasien Gagal Diinput', $this->session->userdata('language'));

            $array_input = $this->input->post();

            $max_antrian = $this->pendaftaran_tindakan_m->get_max_antrian($array_input['shift'],$array_input['dokter_id'])->result_array();


            if($max_antrian[0]['antrian_dokter'] == NULL){
                $max_antrian = 1;
            }
            else{
                $max_antrian = intval($max_antrian[0]['antrian_dokter']) + 1;
            }
            $cabang = $this->cabang_m->get(1);

            $data_daftar = array(
                'status'        => 1,
                'antrian_dokter'        => $max_antrian,
                'berat_badan'   => $array_input['bb'],
                'tekanan_darah' => $array_input['ta'].'_'.$array_input['tb']
            );

            $edit_pendaftaran_id = $this->pendaftaran_tindakan_m->edit_data($data_daftar, $array_input['id']);

            $data_pendaftaran = $this->pendaftaran_tindakan_m->get_by(array('id' => $array_input['id']), true);

           // $data_antrian = $this->antrian_pasien_m->get_by(array('pasien_id' => $data_pendaftaran->pasien_id, 'posisi_loket' => 2, 'status' => 1), true);
            $data_antrian = $this->antrian_pasien_m->get_by(array('pasien_id' => $data_pendaftaran->pasien_id, 'posisi_loket' => 2, 'date(created_date)' => date('Y-m-d')), true);

            $last_id       = $this->antrian_pasien_m->get_max_id()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'ANT-'.date('m').'-'.date('Y').'-%04d';
            $id_antrian    = sprintf($format_id, $last_id, 4);

            $no_urut = $this->antrian_pasien_m->get_max_no_urut_dokter(3,$array_input['dokter_id'])->result_array();
            $no_urut = intval($no_urut[0]['max_no_urut'])+1;



            $data_simpan = array(
                'id'    => $id_antrian,
                "dokter_id"           => $array_input['dokter_id'],
                "pasien_id"           => $data_antrian->pasien_id,
                'nama_pasien' => $data_antrian->nama_pasien,
                'no_telp' => $data_antrian->no_telp,
                'posisi_loket' => 3,
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

            

            //if($edit_pendaftaran_id){
                $response->success = true;
                $response->msg = translate('Berat Badan dan Tekanan Darah Pasien Berhasil Diinput', $this->session->userdata('language'));
            //}

            die(json_encode($response));

        }
    }


    public function get_antrian()
    {
        if($this->input->is_ajax_request()){


            $id_antrian = $this->input->post('antrian_id');
            $counter = $this->input->post('counter');

            $data_antrian = $this->antrian_pasien_m->get_by(array('id' => $id_antrian), true);

            if($data_antrian->posisi_loket != 2 && $data_antrian->status != 0){
                $data_antrian = $this->antrian_pasien_m->get_by(array('posisi_loket' => 2, 'status' => 0),true);
            }

            if(count($data_antrian)){
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
                    'panggilan'    => 'Panggilan untuk pasien '.$data_antrian->nama_pasien.', Ke Ruang Tensi Timbang',
                    'urutan' => $no_urut
                );

                $save_panggilan = $this->antrian_pasien_m->add_data_panggilan($data_antrian_panggil);


                $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
                $date = getDate();
                $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                file_put_contents($file,$jam);

                $response->success = true;
                $response->file = file_put_contents($file,$jam);
            }

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

            $antrian_plus_tiga = $this->antrian_pasien_m->get_data_loket_panggil_tiga(2,($data_antrian->no_urut+4))->result_array();
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

   


}

/* End of file antrian_tensi_bb.php */
/* Location: ./application/controllers/klinik_hd/antrian_tensi_bb.php */