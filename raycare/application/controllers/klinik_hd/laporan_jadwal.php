<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_jadwal extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '805038be5ed4860fc2048b788efbf76a';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/surat_dokter_sppd_m');
        $this->load->model('klinik_hd/jadwal_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/cabang_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/laporan_jadwal/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Jadwal Pasien', $this->session->userdata('language')), 
            'header'         => translate('Jadwal Pasien', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/laporan_jadwal/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function print_jadwal($tgl_awal,$tgl_akhir)
    {
        $this->load->library('mpdf/mpdf.php');

        $tgl_awal    = date('Y-m-d', strtotime($tgl_awal));
        $tgl_akhir   = date('Y-m-d', strtotime($tgl_akhir));
        $cabang_id   = $this->session->userdata('cabang_id');
        $data_jadwal = $this->jadwal_m->get_data_jadwal_cetak($tgl_awal, $tgl_akhir, $cabang_id)->result_array();

        $data = array(
            'tanggal_awal'  => $tgl_awal,
            'tanggal_akhir' => $tgl_akhir,
            'jadwal'        => $data_jadwal
        );

        $mpdf = new mPDF('arial','A4-L', 0, '', 3, 3, 5, 0, 0, 0);
        $mpdf->writeHTML($this->load->view('klinik_hd/jadwal/print_jadwal', $data, true));

        $mpdf->Output('Jadwal.pdf', 'I'); 

    }


    public function view_jadwal($data)
    {
        $data = base64_decode(urldecode($data));
        $data = unserialize($data);

        $this->load->view('klinik_hd/jadwal/modal/view_jadwal', $data);
    }

    public function get_tanggal()
        {
                if($this->input->is_ajax_request())
                {
                $senin = $this->input->post('senin');
                $cabang = $this->session->userdata('cabang_id');
                $html = '<table class="table table-striped table-bordered table-hover" id="table_pasien">     <thead>
                
                <tr>
                <th class="text-center" rowspan="2" style ="vertical-align: middle; font-size: 12px;">'.translate("No.Bed", $this->session->userdata("language")).' </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">';
                  
                        $date = $senin;
                        // die_dump($date);
                        $senin = date('d-m-Y', strtotime($date));
                        $mon = date('Y-m-d', strtotime($date));
                        $next_date = strtotime(date('d M Y', strtotime($date)). " +1 days");
                        $selasa = date('d-m-Y', $next_date);
                        $tue = date('Y-m-d', $next_date);
                        $next_date_1 = strtotime(date('d M Y', strtotime($date)). " +2 days");
                        $rabu = date('d-m-Y', $next_date_1);
                        $wed = date('Y-m-d', $next_date_1);       
                        $next_date_2 = strtotime(date('d M Y', strtotime($date)). " +3 days");
                        $kamis = date('d-m-Y', $next_date_2);
                        $thu = date('Y-m-d', $next_date_2);
                        $next_date_3 = strtotime(date('d M Y', strtotime($date)). " +4 days");
                        $jumat = date('d-m-Y', $next_date_3);
                        $fri = date('Y-m-d', $next_date_3);
                        $next_date_4 = strtotime(date('d M Y', strtotime($date)). " +5 days");
                        $sabtu = date('d-m-Y', $next_date_4);
                        $sat = date('Y-m-d', $next_date_4);
                        $next_date_5 = strtotime(date('d M Y', strtotime($date)). " +6 days");
                        $minggu = date('d-m-Y', $next_date_5);
                        $sun = date('Y-m-d', $next_date_5);
                        $valid_date = strtotime(date('d M Y', strtotime($date)). " +7 days");
                        $validasi = date('d-m-Y', $valid_date);
                        // die_dump($minggu);

                        $tgl_sekarang = date('d M Y');
                        $selisih = $tgl_sekarang - $minggu;
                       

                        $tampilan_date_1 = date("(d M Y)", strtotime($date));
                        $tampilan_date_2 = date("(d M Y)", strtotime($selasa));
                        $tampilan_date_3 = date("(d M Y)", strtotime($rabu));
                        $tampilan_date_4 = date("(d M Y)", strtotime($kamis));
                        $tampilan_date_5 = date("(d M Y)", strtotime($jumat));
                        $tampilan_date_6 = date("(d M Y)", strtotime($sabtu));
                        $tampilan_date_7 = date("(d M Y)", strtotime($minggu));

                        $jml_senin_pagi   = $this->jadwal_m->get_data_jadwal_waktu($mon,'7',$cabang)->result_array();
                        $jml_senin_siang  = $this->jadwal_m->get_data_jadwal_waktu($mon,'13',$cabang)->result_array();
                        $jml_senin_sore   = $this->jadwal_m->get_data_jadwal_waktu($mon,'18',$cabang)->result_array();
                        $jml_senin_malam  = $this->jadwal_m->get_data_jadwal_waktu($mon,'23',$cabang)->result_array();
                        $jml_selasa_pagi  = $this->jadwal_m->get_data_jadwal_waktu($tue,'7',$cabang)->result_array();
                        $jml_selasa_siang = $this->jadwal_m->get_data_jadwal_waktu($tue,'13',$cabang)->result_array();
                        $jml_selasa_sore  = $this->jadwal_m->get_data_jadwal_waktu($tue,'18',$cabang)->result_array();
                        $jml_selasa_malam = $this->jadwal_m->get_data_jadwal_waktu($tue,'23',$cabang)->result_array();
                        $jml_rabu_pagi    = $this->jadwal_m->get_data_jadwal_waktu($wed,'7',$cabang)->result_array();
                        $jml_rabu_siang   = $this->jadwal_m->get_data_jadwal_waktu($wed,'13',$cabang)->result_array();
                        $jml_rabu_sore    = $this->jadwal_m->get_data_jadwal_waktu($wed,'18',$cabang)->result_array();
                        $jml_rabu_malam   = $this->jadwal_m->get_data_jadwal_waktu($wed,'23',$cabang)->result_array();
                        $jml_kamis_pagi   = $this->jadwal_m->get_data_jadwal_waktu($thu,'7',$cabang)->result_array();
                        $jml_kamis_siang  = $this->jadwal_m->get_data_jadwal_waktu($thu,'13',$cabang)->result_array();
                        $jml_kamis_sore   = $this->jadwal_m->get_data_jadwal_waktu($thu,'18',$cabang)->result_array();
                        $jml_kamis_malam  = $this->jadwal_m->get_data_jadwal_waktu($thu,'23',$cabang)->result_array();
                        $jml_jumat_pagi   = $this->jadwal_m->get_data_jadwal_waktu($fri,'7',$cabang)->result_array();
                        $jml_jumat_siang  = $this->jadwal_m->get_data_jadwal_waktu($fri,'13',$cabang)->result_array();
                        $jml_jumat_sore   = $this->jadwal_m->get_data_jadwal_waktu($fri,'18',$cabang)->result_array();
                        $jml_jumat_malam  = $this->jadwal_m->get_data_jadwal_waktu($tue,'23',$cabang)->result_array();
                        $jml_sabtu_pagi   = $this->jadwal_m->get_data_jadwal_waktu($sat,'7',$cabang)->result_array();
                        $jml_sabtu_siang  = $this->jadwal_m->get_data_jadwal_waktu($sat,'13',$cabang)->result_array();
                        $jml_sabtu_sore   = $this->jadwal_m->get_data_jadwal_waktu($sat,'18',$cabang)->result_array();
                        $jml_sabtu_malam  = $this->jadwal_m->get_data_jadwal_waktu($sat,'23',$cabang)->result_array();
                        $jml_minggu_pagi  = $this->jadwal_m->get_data_jadwal_waktu($sun,'7',$cabang)->result_array();
                        $jml_minggu_siang = $this->jadwal_m->get_data_jadwal_waktu($sun,'13',$cabang)->result_array();
                        $jml_minggu_sore  = $this->jadwal_m->get_data_jadwal_waktu($sun,'18',$cabang)->result_array();
                        $jml_minggu_malam = $this->jadwal_m->get_data_jadwal_waktu($sun,'23',$cabang)->result_array();

                 $html .= '<input type="hidden" id="test" name="test" value="'.$date.'"><input type="hidden" id="text_senin" name="text_senin" value="'.$senin.'"><input type="hidden" id="text_minggu" name="text_minggu" value="'.$minggu.'">
                    <span class="input-group" style="width: 100%;">
                        <input value="Senin '.$tampilan_date_1.'" class="form-group text-center" id="hari_senin" readonly="readonly" style=" background-color: transparent;border: 0px solid; width: 80%;">
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Selasa '.$tampilan_date_2.'" class="form-group text-center" id="hari_selasa" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Rabu '.$tampilan_date_3.'" class="form-group text-center" id="hari_rabu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Kamis '.$tampilan_date_4.'" class="form-group text-center" id="hari_kamis" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Jumat '.$tampilan_date_5.'" class="form-group text-center" id="hari_jumat" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Sabtu '.$tampilan_date_6.'" class="form-group text-center" id="hari_sabtu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Minggu '.$tampilan_date_7.'" class="form-group text-center" id="hari_minggu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                    </span>
                </th>
            </tr>
            <tr>';
                
                    for ($i=1; $i <= 7 ; $i++) 
                    {
                                $html .=  '<th class="text-center" style ="font-size: 12px;border-top:1px solid red;">'.translate("Pagi", $this->session->userdata("language")).'</th>
                                <th class="text-center" style ="font-size: 12px;border-top:1px solid red;">'.translate("Siang", $this->session->userdata("language")).'</th>
                                <th class="text-center" style ="font-size: 12px;border-top:1px solid red;">'.translate("Sore", $this->session->userdata("language")).'</th>
                                <th class="text-center" style ="font-size: 12px;border-top:1px solid red;">'.translate("Malam", $this->session->userdata("language")).'</th>';
                    }
                    
            $html .= '</tr>
                    </thead>
                    <tbody>';

                    $data_pasien_klinik = $this->jadwal_m->get_data(date('Y-m-d', strtotime($date)), date('Y-m-d', strtotime($validasi)), $cabang)->result_array();
                    $data_pasien = object_to_array($data_pasien_klinik);

                    // $data_row_pasien = "";
                    //die_dump($data_pasien_klinik);

                    for ($i=1; $i <= 22 ; $i++) 
                    { 
                        $html .= '<tr>
                                <td class="text-center">'.$i.'</td>';
                                for ($j=1; $j<=28; $j++) 
                                {
                                    if($j < 5){
                                        $hari = "Senin";
                                        $tanggal = $date;
                                    }
                                    else if($j < 9 && $j > 4)
                                    {
                                        $hari = "Selasa";
                                        $tanggal = $selasa;
                                    }
                                    else if($j < 13 && $j > 8)
                                    {
                                        $hari = "Rabu";
                                        $tanggal = $rabu;
                                    }
                                    else if($j < 17 && $j > 12)
                                    {
                                        $hari = "Kamis";
                                        $tanggal = $kamis;
                                    }
                                    else if($j < 21 && $j > 16)
                                    {
                                        $hari = "Jumat";
                                        $tanggal = $jumat;
                                    }
                                    else if($j < 25 && $j > 20)
                                    {
                                        $hari = "Sabtu";
                                        $tanggal = $sabtu;
                                    }
                                    else
                                    {
                                        $hari = "Minggu";
                                        $tanggal = $minggu;
                                    }

                                    $time_now = date('H:i');
                                    $class_tipe = "glyphicon glyphicon-ok-sign font-grey";
                                    $class_button = "btn edit";
                                    $data_tipe = "";
                                    $data_id = "";
                                    $pop_up = "popup_modal";
                                    $url_function = "add_jadwal";
                                    $pasien_id = "";
                                    $nama_pasien = "...";
                                    $id = "";
                                    $class = 'success';

                                    if($j == 1 || $j == 5 || $j == 9 || $j == 13 || $j == 17 || $j == 21 || $j == 25)
                                    {
                                        $data_tipe = "Pagi (07:00 - 12:00)";
                                    }
                                    else if($j == 2 || $j == 6 || $j == 10 || $j == 14 || $j == 18 || $j == 22 || $j == 26)
                                    {
                                        $data_tipe = "Siang (13:00 - 18:00)";
                                    }
                                    else if($j == 3 || $j == 7 || $j == 11 || $j == 15 || $j == 19 || $j == 23 || $j == 27)
                                    {
                                        $data_tipe = "Sore (18:00 - 23:00)";
                                    }
                                    else
                                    {
                                        $data_tipe = "Malam (23:00 - 03:00)";
                                    }

                                   

                                    foreach ($data_pasien as $data_row) 
                                    {
                                            // die_dump($data_row);
                                        // $data_row_pasien[$data_row[]

                                        $pasien_nama = $data_row['nama'];
                                            $pasien_id = $data_row['pasien_id'];
                                            //$pasien = $this->pasien_m->get($pasien_id);
                                            $keterangan_db = $data_row['keterangan'];
                                            $tanggal_db = $data_row['tanggal'];
                                            $tipe_db = $data_row['tipe'];
                                            $no_bed_db = $data_row['no_urut_bed'];
                                            $tgl =  date('d-m-Y',strtotime($tanggal_db));
                                            $id = $data_row['id'];
                                            $tgl_banding_1 = $senin;
                                            $tgl_banding_2 = $selasa;
                                            $tgl_banding_3 = $rabu;
                                            $tgl_banding_4 = $kamis;
                                            $tgl_banding_5 = $jumat;
                                            $tgl_banding_6 = $sabtu;
                                            $tgl_banding_7 = $minggu;
                                            
                                            $date_now = new DateTime();
                                            if($tipe_db == 1)
                                            {
                                                $tipe_waktu = "Pagi ";
                                            }
                                            else if ($tipe_db == 2) {
                                                $tipe_waktu = "Siang";
                                            }
                                            else if ($tipe_db == 3) {
                                                $tipe_waktu = "Sore ";
                                            }
                                            else
                                            {
                                                $tipe_waktu = "Malam";
                                            }

                                            $tipe_data = substr($data_tipe, 0, 5);
                                            
                                            if ($tgl == $tgl_banding_1)
                                            {   
                                                if($j < 5)
                                                {
                                                    if($tipe_waktu == $tipe_data)
                                                    {
                                                        if($no_bed_db == $i)
                                                        {
                                                            if($data_row['status'] == 0){
                                                                $class = 'danger';
                                                            }
                                                            
                                                            $nama_pasien = $pasien_nama;
                                                            
                                                        }                                                   
                                                    }
                                                }
                                            }
                                            else if ($tgl == $tgl_banding_2) 
                                            {
                                                if($j < 9 && $j > 4)
                                                {
                                                    if($tipe_waktu == $tipe_data)
                                                    {
                                                        if($no_bed_db == $i)
                                                        {
                                                            if($data_row['status'] == 0){
                                                                $class = 'danger';
                                                            }

                                                            $nama_pasien = $pasien_nama; 
                                                       }
                                                    }
                                                }
                                                
                                            }
                                            else if ($tgl == $tgl_banding_3)
                                            {
                                                if($j < 13 && $j > 8)
                                                {
                                                    if($tipe_waktu == $tipe_data)
                                                    {
                                                        if($no_bed_db == $i)
                                                        {
                                                            if($data_row['status'] == 0){
                                                                $class = 'danger';
                                                            }
                                                            $nama_pasien = $pasien_nama; 
                                                       }
                                                    }
                                                }
                                            }

                                            else if ($tgl == $tgl_banding_4)
                                            {
                                                if($j < 17 && $j > 12)
                                                {
                                                    if($tipe_waktu == $tipe_data)
                                                    {
                                                        if($no_bed_db == $i)
                                                        {
                                                            if($data_row['status'] == 0){
                                                                $class = 'danger';
                                                            }
                                                            $nama_pasien = $pasien_nama; 
                                                       }
                                                    }
                                                }
                                            }

                                            else if ($tgl == $tgl_banding_5)
                                            {
                                                if($j < 21 && $j > 16)
                                                {
                                                    if($tipe_waktu == $tipe_data)
                                                    {
                                                        if($no_bed_db == $i)
                                                        {
                                                            if($data_row['status'] == 0){
                                                                $class = 'danger';
                                                            }
                                                            $nama_pasien = $pasien_nama; 
                                                       }
                                                    }
                                                }
                                            }

                                            else if ($tgl == $tgl_banding_6)
                                            {
                                                if($j < 25 && $j > 20)
                                                {
                                                    if($tipe_waktu == $tipe_data)
                                                    {
                                                        if($no_bed_db == $i)
                                                        {
                                                            if($data_row['status'] == 0){
                                                                $class = 'danger';
                                                            }
                                                            $nama_pasien = $pasien_nama; 
                                                       }
                                                    }
                                                }
                                            }

                                            else if($tgl == $tgl_banding_7)
                                            {
                                                if($j < 29 && $j > 24)
                                                {
                                                    if($tipe_waktu == $tipe_data)
                                                    {
                                                        if($no_bed_db == $i)
                                                        {
                                                            if($data_row['status'] == 0){
                                                                $class = 'danger';
                                                            }
                                                            $nama_pasien = $pasien_nama; 
                                                       }
                                                    }
                                                }
                                            }
                                    }
                                    
                                    if($j == 1 || $j == 5 || $j == 9 || $j == 13 || $j == 17 || $j == 21 || $j == 25)
                                    {
                                        
                                        $btn_default = '<td class="text-left '.$class.'"><div class="inline-button-table">'.$nama_pasien.'</div></td>';
                                        $html .= $btn_default;
                                    }
                                    else if($j == 2 || $j == 6 || $j == 10 || $j == 14 || $j == 18 || $j == 22 || $j == 26)
                                    {
                                        
                                        $btn_default = '<td class="text-left '.$class.'"><div class="inline-button-table">'.$nama_pasien.'</div></td>';
                                        $html .= $btn_default;
                                    }
                                    else if($j == 3 || $j == 7 || $j == 11 || $j == 15 || $j == 19 || $j == 23 || $j == 27)
                                    {
                                        
                                       $btn_default = '<td class="text-left '.$class.'"><div class="inline-button-table">'.$nama_pasien.'</div></td>';
                                        $html .= $btn_default;
                                    }
                                    else
                                    {
                                        $btn_default = '<td class="text-left '.$class.'"><div class="inline-button-table">'.$nama_pasien.'</div></td>';
                                        $html .= $btn_default;
                                    }

                                                
                                }
                        $html .= '</tr>';
                    }
                $html .= '</tbody>
                <tfoot>
                    <tr style="text-align:center;">
                        <td>Kosong</td>
                        <td>'.(22 - count($jml_senin_pagi)).'</td>
                        <td>'.(22 - count($jml_senin_siang)).'</td>
                        <td>'.(22 - count($jml_senin_sore)).'</td>
                        <td>'.(22 - count($jml_senin_malam)).'</td>
                        <td>'.(22 - count($jml_selasa_pagi)).'</td>
                        <td>'.(22 - count($jml_selasa_siang)).'</td>
                        <td>'.(22 - count($jml_selasa_sore)).'</td>
                        <td>'.(22 - count($jml_selasa_malam)).'</td>
                        <td>'.(22 - count($jml_rabu_pagi)).'</td>
                        <td>'.(22 - count($jml_rabu_siang)).'</td>
                        <td>'.(22 - count($jml_rabu_sore)).'</td>
                        <td>'.(22 - count($jml_rabu_malam)).'</td>
                        <td>'.(22 - count($jml_kamis_pagi)).'</td>
                        <td>'.(22 - count($jml_kamis_siang)).'</td>
                        <td>'.(22 - count($jml_kamis_sore)).'</td>
                        <td>'.(22 - count($jml_kamis_malam)).'</td>
                        <td>'.(22 - count($jml_jumat_pagi)).'</td>
                        <td>'.(22 - count($jml_jumat_siang)).'</td>
                        <td>'.(22 - count($jml_jumat_sore)).'</td>
                        <td>'.(22 - count($jml_jumat_malam)).'</td>
                        <td>'.(22 - count($jml_sabtu_pagi)).'</td>
                        <td>'.(22 - count($jml_sabtu_siang)).'</td>
                        <td>'.(22 - count($jml_sabtu_sore)).'</td>
                        <td>'.(22 - count($jml_sabtu_malam)).'</td>
                        <td>'.(22 - count($jml_minggu_pagi)).'</td>
                        <td>'.(22 - count($jml_minggu_siang)).'</td>
                        <td>'.(22 - count($jml_minggu_sore)).'</td>
                        <td>'.(22 - count($jml_minggu_malam)).'</td>
                        
                    </tr>
                </tfoot>
                </table>';
            
            }
            echo $html;
        }

}

/* End of file branch.php */
/* Location: ./application/controllers/klinik_hd/jadwal.php */