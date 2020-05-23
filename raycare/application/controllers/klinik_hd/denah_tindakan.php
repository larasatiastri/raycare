<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Denah_tindakan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd27a53a1a65328e1cfce8ee06382c34f';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/tindakan_hd_m');
        $this->load->model('klinik_hd/bed_m');
        
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/denah_tindakan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Denah Tindakan', $this->session->userdata('language')), 
            'header'         => translate('Denah Tindakan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/denah_tindakan/index',
            'flag'           => 2,
            'tanda'         =>1,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    
    // Kebutuhan Denah Create Tindakan | Created by Abu
    public function show_denah_lantai_html_create()
    {   
        $lantai = $this->input->post('lantai');
        $shift = $this->input->post('shift');
        $shift_prev = 0;
        if($shift != 1){
            $shift_prev = intval($this->input->post('shift')) - 1;
            
   
        }
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
                            $logo = '';
                            $color_note = '';
                            if ($row['status'] == 1 && $row['status_antrian'] == 0) 
                            {
                                $status = '#44b6ae';//Hijau

                            } elseif ($row['status'] == 2) 
                            {
                                $status = '#f3c200'; //KUNING
                                if($row['status_antrian'] == 0 && $row['shift'] == $shift){
                                    if(count($cek_tindakan))
                                    {
                                        $nama_pasien = $cek_tindakan->nama_pasien;
                                    }
                                }if($row['status_antrian'] == 0 && $row['shift'] != $shift){
                                    if(count($cek_tindakan_next))
                                    {
                                        $nama_pasien = $cek_tindakan_next->nama_pasien;
                                    }if(count($cek_tindakan_prev))
                                    {
                                        $nama_pasien = $cek_tindakan_prev->nama_pasien;
                                    }
                                }

                            } elseif ($row['status'] == 3) 
                            {
                                $status = '#d91e18';//MERAH
                                $estimate_time = "<rect id='box_time_".$i."' class='rectangle' x='".($split_str[0])."' y='".($split_str[1]-25)."' rx='10' ry='10' width='100' height='20' style='fill:#000;' />";
                                
                                if($row['status_antrian'] == 0 && $row['shift'] == $shift){
                                   if(count($data_tindakan_hd))
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
                                        $start = 'Waiting..';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    
                                }
                                if($row['status_antrian'] == 0 && $row['shift'] < $shift){
                                    if(count($data_tindakan_hd_prev))
                                    {
                                        $nama_pasien = $data_tindakan_hd_prev->nama_pasien;
                                        $start = substr($data_tindakan_hd_prev->waktu, 0,5);
                                        $time = $data_tindakan_hd_prev->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    } 
                                    if(count($data_tindakan_hd))
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
                                        $start = 'Waiting..';
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
                                    <br><a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/denah_tindakan/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }if($row['status_antrian'] == 0 && $row['shift'] > $shift){
                                    if(count($data_tindakan_hd_next))
                                    {
                                        $nama_pasien = $data_tindakan_hd_next->nama_pasien;
                                        $start = substr($data_tindakan_hd_next->waktu, 0,5);
                                        $time = $data_tindakan_hd_next->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    } 
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);
                                    // die(dump($start));

                                    if($start_time < 0.00){
                                        $start = 'Waiting..';
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
                                    <br><a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/denah_tindakan/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
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
                                        $logo = '&#xf017';
                                        $color_note = '#ffffff';
                                        $nama_pasien_next = $cek_tindakan->nama_pasien;
                                        
                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);

                                    if($start_time < 0.00){
                                        $start = 'Waiting..';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";   
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
                                        $logo = '&#xf017';
                                        $color_note = '#ffffff';
                                        $nama_pasien_next = $cek_tindakan_next->nama_pasien;
                                        
                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);

                                    if($start_time < 0.00){
                                        $start = 'Waiting..';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";   
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
                                   if(count($data_tindakan_hd))
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
                                        $start = 'Waiting..';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";
                                    
                                }
                                if($row['status_antrian'] == 0 && $row['shift'] > $shift){
                                    if(count($data_tindakan_hd_next))
                                    {
                                        $nama_pasien = $data_tindakan_hd_next->nama_pasien;
                                        $start = substr($data_tindakan_hd_next->waktu, 0,5);
                                        $time = $data_tindakan_hd_next->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);
                                    // die(dump($start));

                                    if($start_time < 0.00){
                                        $start = 'Waiting..';
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
                                    <br><a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/denah_tindakan/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
                                }if($row['status_antrian'] == 0 && $row['shift'] < $shift){
                                    if(count($data_tindakan_hd_prev))
                                    {
                                        $nama_pasien = $data_tindakan_hd_prev->nama_pasien;
                                        $start = substr($data_tindakan_hd_prev->waktu, 0,5);
                                        $time = $data_tindakan_hd_prev->time_of_dialysis * 60;
                                        $start = date('H:i', strtotime($start.' +'.$time.' minutes'));

                                    }
                                    if(count($data_tindakan_hd))
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
                                        $start = 'Waiting..';
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
                                    <br><a id="detail" data-toggle="modal" data-target="#ajax_notes" href="'.base_url().'klinik_hd/denah_tindakan/modal_detail/'.$lantai.'/'.$row['id'].'/'.$row['shift'].'" data-id="'.$row['id'].'" style="margin-bottom:5px;" class="btn btn-primary detail"><i class="fa fa-search"></i> Lihat Detail</a>';
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
                                        $logo = '&#xf017';
                                        $color_note = '#ffffff';
                                        $nama_pasien_next = $cek_tindakan->nama_pasien;
                                        
                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);

                                    if($start_time < 0.00){
                                        $start = 'Waiting..';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";   
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
                                        $logo = '&#xf017';
                                        $color_note = '#ffffff';
                                        $nama_pasien_next = $cek_tindakan_next->nama_pasien;
                                        
                                    }
                                    $to_time =  strtotime(date('H:i'));
                                    $from_time = strtotime($start);
                                    $start_time = round(($from_time - $to_time) / 60,2);

                                    if($start_time < 0.00){
                                        $start = 'Waiting..';
                                    }
                                    if($start_time < 60.00 && $start_time >= 0.00){
                                        $start = round(abs($from_time - $to_time) / 60).' minutes';
                                    }if($start_time == 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h 0 minutes";
                                    }elseif($start_time > 60.00){
                                        $start = intval((round(abs($from_time - $to_time) / 60) / 60)). " h ".intval((round(abs($from_time - $to_time) / 60) % 60)). " m";
                                    }
                                    $estimate_time_text = "<text x='".($split_str[0]+10)."' y='".($split_str[1]-10)."' font-family='arial' font-size='14' font-weight='bold' fill='".$color."' > ".$start."</text>";   
                                }

                            }

                            
                            $denah_html .= $estimate_time;
                            $denah_html .= "<rect id='box_bed_".$i."' class='rectangle' x='".$split_str[0]."' y='".$split_str[1]."' rx='10' ry='10' width='150' height='".$height."' style='fill:".$status.";' />";
                            $multiply = 15 + (10 * strlen($row['kode']));
                           
                            $denah_html .= $estimate_time_text;
                            $denah_html .= "<text x='".($split_str[0]+10)."' y='".($split_str[1]+16)."' font-family='arial' font-size='16' font-weight='bold' fill='".$color."' > ".$row['kode']."</text>";
                            $denah_html .= "<text x='".($split_str[0]+$multiply)."' y='".($split_str[1]+16)."' font-family='arial' font-size='12' font-weight='' fill='".$color."' > ".$nama_pasien."</text>";
                            $denah_html .= "<text x='".($split_str[0]+$multiply)."' y='".($split_str[1]+30)."' font-family='arial' font-size='12' font-weight='' fill='".$color."' > ".$nama_pasien_next."</text>";
                            $denah_html .= "<g><text x='".($split_str[0]+10)."' y='".($split_str[1]+30)."' font-family='FontAwesome' fill='".$color_note."'>".$logo."</text></g>";

                            $i++;
                        };

        $denah_html .= "</svg>";

            

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

    
}

/* End of file transaksi_dokter.php */
/* Location: ./application/controllers/klinik_hd/transaksi_dokter.php */