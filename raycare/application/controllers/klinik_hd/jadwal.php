<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jadwal extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '97c76bc20c7c2da2df88a4b6b69f2af9';                  // untuk check bit_access

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
        $this->load->model('master/pasien_m');
        $this->load->model('master/cabang_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/jadwal/index';
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
            'content_view'   => 'klinik_hd/jadwal/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    /**
     * [list description]
     * @return [type] [description]
     */

    public function listing_pilih_pasien()
    {
        $cabang_id = $this->session->userdata('cabang_id');
        $result = $this->pasien_m->get_datatable_pilih_pasien($cabang_id);

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
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary select"><i class="fa fa-check"></i></a>';
                
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

    public function save_jadwal()
    {
        
        if ($this->input->is_ajax_request()){

            $array_input = $this->input->post();
            
            // die(dump($array_input));
            if($array_input['command'] == 'add')
            {
                $tipe_waktu = substr($array_input['waktu'], 0, 5);
                if ($tipe_waktu == "Pagi ") {
                    $tipe = 1;
                    $waktu = substr($array_input['waktu'], 6, 5);
                }
                else if ($tipe_waktu == "Siang") {
                    $tipe = 2;
                    $waktu = substr($array_input['waktu'], 7, 5);
                }
                else if ($tipe_waktu == "Sore ") {
                    $tipe = 3;
                    $waktu = substr($array_input['waktu'], 6, 5);
                }
                else
                {
                    $tipe = 4;
                    $waktu = substr($array_input['waktu'], 7, 5);
                }

                
                //die_dump($array_input['minggu']);
                // die_dump($array_input['tanggal'].' '.$waktu);
                for ($i=0; $i <= $array_input['minggu'] ; $i++)
                { 
                    if ($array_input['id'] == "") 
                    {
                        $tanggal = strtotime(date('Y-m-d H:i:s', strtotime($array_input['tanggal'].' '.$waktu)). " +".$i." week");
                        $minggu = date('Y-m-d H:i:s', $tanggal);
                        $date = date('Y-m-d', $tanggal);

                        $data_jadwal = $this->jadwal_m->get_by(array('pasien_id' => $array_input['id_pasien'], 'date(tanggal)' => $date, 'is_active' => 1, 'status' => 1 ));
                        // die(dump($data_jadwal));

                        if(count($data_jadwal) == 0){
                            $no_bed = $this->jadwal_m->get_max_bed($date, $waktu)->result_array();

                            $no_urut_bed = ($no_bed[0]['nomor'] == null)?1:$no_bed[0]['nomor'];
                            
                            $data_pasien = array(
                                'cabang_id'   => $array_input['cabang'],
                                'pasien_id'   => $array_input['id_pasien'],
                                'no_urut_bed' => $no_urut_bed,
                                'tanggal'     => $minggu,
                                'tipe'        => $tipe,
                                'waktu'       => $waktu,
                                'status'      => 1,
                                'keterangan'  => $array_input['keterangan'],
                                'is_active'   => 1
                            );
                            
                            $save_pasien = $this->jadwal_m->save($data_pasien);
                        }else{
                            
                        }
                    }
                }
            }
            else if($array_input['command'] == 'edit')
            {
                $id = $array_input['id'];
                $tgl = $array_input['tanggal_id'];

                $date = date('Y-m-d', strtotime($tgl));
                $time = date('H', strtotime($tgl));
                
                $no_bed = $this->jadwal_m->get_max_bed($date,$time)->result_array();
                $jadwal = $this->jadwal_m->get_by_tgl_waktu($date,$time)->result_array();
                
                if($time == '23')
                {
                    $tipe = 4;        
                }
                if($time == '07')
                {
                    $tipe = 1; 
                }
                if($time == '13')
                {
                    $tipe = 2;        
                }
                if($time == '18')
                {
                    $tipe = 3;     
                }

                if($array_input['option_alasan'] == 1) //hapus jadwal
                {
                    $data_jadwal = array(
                        'is_active'   => 0
                    );
                    $save_jadwal = $this->jadwal_m->save($data_jadwal, $id);
                }
                if($array_input['option_alasan'] == 1) //pasien hadir
                {
                    $data_jadwal = array(
                        'status'   => 1,
                        'keterangan' => $array_input['keterangan']
                    );
                    $save_jadwal = $this->jadwal_m->save($data_jadwal, $id);
                }
                if($array_input['option_alasan'] == 3) // pasien tidak hadir dan tidak ada pengganti
                {
                    $data_tidak_hadir = array(
                        'status'    => 0,
                        'keterangan' => $array_input['keterangan']
                    );
                    $save_jadwal = $this->jadwal_m->save($data_tidak_hadir, $id);
                }
                if($array_input['option_alasan'] == 4) // ubah jadwal (tanggal/jam)
                {
                    $no_urut_bed = ($no_bed[0]['nomor'] == null)?1:$no_bed[0]['nomor'];


                    $data_ubah = array(
                        'cabang_id'   => $array_input['cabang'],
                        'pasien_id'   => $array_input['id_pasien_awal'],
                        'no_urut_bed' => $no_urut_bed,
                        'tanggal'     => date('Y-m-d H:i:s', strtotime($tgl)),
                        'tipe'        => $tipe,
                        'waktu'       => $time,
                        'status'      => 1,
                        'keterangan'  => $array_input['keterangan'],
                        'is_active'   => 1
                    );

                    if(count($jadwal) < 33)
                    {
                        $save_jadwal = $this->jadwal_m->save($data_ubah, $id);
                    }                    
                }                

                if($array_input['option_alasan'] == 5)// pasien digantikan
                {
                    $data_tidak_hadir = array(
                        'status'    => 0,
                        'keterangan' => $array_input['keterangan']
                    );
                    $save_jadwal = $this->jadwal_m->save($data_tidak_hadir, $id);


                    $tipe_waktu = substr($array_input['waktu'], 0, 5);
                    if ($tipe_waktu == "Pagi ") {
                        $tipe = 1;
                        $waktu = substr($array_input['waktu'], 6, 5);
                    }
                    else if ($tipe_waktu == "Siang") {
                        $tipe = 2;
                        $waktu = substr($array_input['waktu'], 7, 5);
                    }
                    else if ($tipe_waktu == "Sore ") {
                        $tipe = 3;
                        $waktu = substr($array_input['waktu'], 6, 5);
                    }
                    else
                    {
                        $tipe = 4;
                        $waktu = substr($array_input['waktu'], 7, 5);
                    }

                    $jadwal = $this->jadwal_m->get($id);
                    $tanggal = date('Y-m-d H:i:s', strtotime($jadwal->tanggal));

                    $data_pasien = array(
                        'cabang_id'   => $array_input['cabang'],
                        'pasien_id'   => $array_input['id_pasien'],
                        'no_urut_bed' => $array_input['no_bed'],
                        'tanggal'     => $tanggal,
                        'tipe'        => $tipe,
                        'waktu'       => $waktu,
                        'status'      => 1,
                        'keterangan'  => $array_input['keterangan'],
                        'is_active'   => 1
                    );
                    
                    $save_pasien = $this->jadwal_m->save($data_pasien);
                }

                // die_dump($this->db->last_query()); 
            }
                    
        }
    }
    public function add_jadwal($data)
    {
        $data = base64_decode(urldecode($data));
        $data = unserialize($data);

        $this->load->view('klinik_hd/jadwal/modal/tambah_jadwal', $data);
    }

    public function view_jadwal($data)
    {
        $data = base64_decode(urldecode($data));
        $data = unserialize($data);

        $this->load->view('klinik_hd/jadwal/modal/view_jadwal', $data);
    }

    public function edit_jadwal($data)
    {
        $data = base64_decode(urldecode($data));
        $data = unserialize($data);
        $jadwal = $this->jadwal_m->get($data['id']);
        
        $tipe = '';
        if($jadwal->tipe == 1) $tipe = 'Pagi (07:00 - 12:00)';
        if($jadwal->tipe == 2) $tipe = 'Siang (13:00 - 18:00)';
        if($jadwal->tipe == 3) $tipe = 'Sore (18:00 - 23:00)';
        if($jadwal->tipe == 4) $tipe = 'Malam (23:00 - 03:00)';

        $body = array(
            'id'            => $jadwal->id,
            'cabang_id'     => $jadwal->cabang_id,
            'pasien_id'     => $jadwal->pasien_id,
            'urut'          => $jadwal->no_urut_bed,
            'tipe'          => $tipe,
            'hari'          => $data['hari'],
            'keterangan'    => $jadwal->keterangan,
            'waktu'         => $jadwal->waktu,
            'tanggal'       => $jadwal->tanggal,
            'created_by'    => $jadwal->created_by,
            'created_date'  => $jadwal->created_date,
            'modified_by'   => $jadwal->modified_by,
            'modified_date' => $jadwal->modified_date  
        );

        $this->load->view('klinik_hd/jadwal/modal/edit_jadwal', $body);
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

                 $html .= '<input type="hidden" id="test" name="test" value="'.$date.'">
                    <span class="input-group" style="width: 100%;">
                        <input value="Senin '.$tampilan_date_1.'" class="form-group text-center" id="hari_senin" readonly="readonly" style=" background-color: transparent;border: 0px solid; width: 80%;">
                        <!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
                        <a class="btn maximize" title="'.translate('Maximize', $this->session->userdata('language')).'" style="font-size: 14px;" id="view_senin"><i class="fa fa-search-plus"></i></a>
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Selasa '.$tampilan_date_2.'" class="form-group text-center" id="hari_selasa" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                        <!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
                        <a class="btn maximize" title="'.translate('Maximize', $this->session->userdata('language')).'" style="font-size: 14px;" id="view_selasa"><i class="fa fa-search-plus"></i></a>
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Rabu '.$tampilan_date_3.'" class="form-group text-center" id="hari_rabu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                        <!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
                        <a class="btn maximize" title="'.translate('Maximize', $this->session->userdata('language')).'" style="font-size: 14px;" id="view_rabu"><i class="fa fa-search-plus"></i></a>
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Kamis '.$tampilan_date_4.'" class="form-group text-center" id="hari_kamis" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                        <!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
                        <a class="btn maximize" title="'.translate('Maximize', $this->session->userdata('language')).'" style="font-size: 14px;" id="view_kamis"><i class="fa fa-search-plus"></i></a>
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Jumat '.$tampilan_date_5.'" class="form-group text-center" id="hari_jumat" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                        <!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
                        <a class="btn maximize" title="'.translate('Maximize', $this->session->userdata('language')).'" style="font-size: 14px;" id="view_jumat"><i class="fa fa-search-plus"></i></a>
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Sabtu '.$tampilan_date_6.'" class="form-group text-center" id="hari_sabtu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                        <!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
                        <a class="btn maximize" title="'.translate('Maximize', $this->session->userdata('language')).'" style="font-size: 14px;" id="view_sabtu"><i class="fa fa-search-plus"></i></a>
                    </span>
                </th>
                <th class="text-center" colspan="4" style ="font-size: 12px;">
                    <span class="input-group" style="width: 100%;">
                        <input value="Minggu '.$tampilan_date_7.'" class="form-group text-center" id="hari_minggu" readonly="readonly" style="background-color: transparent;border: 0px solid; width: 80%;">
                        <!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
                        <a class="btn maximize" title="'.translate('Maximize', $this->session->userdata('language')).'" style="font-size: 14px;" id="view_minggu"><i class="fa fa-search-plus"></i></a>
                    </span>
                </th>
            </tr>
            <tr>';
                
                    for ($i=1; $i <= 7 ; $i++) 
                    {
                                $html .=  '<th class="text-center" style ="font-size: 12px;">'.translate("Pagi", $this->session->userdata("language")).'</th>
                                <th class="text-center" style ="font-size: 12px;">'.translate("Siang", $this->session->userdata("language")).'</th>
                                <th class="text-center" style ="font-size: 12px;">'.translate("Sore", $this->session->userdata("language")).'</th>
                                <th class="text-center" style ="font-size: 12px;">'.translate("Malam", $this->session->userdata("language")).'</th>';
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

                                    $keterangan_db ="";
                                    $ket = "";
                                    $id_pasien_jadwal = "";
                                    $disable = "";
                                    $id = "";
                                   $data_url = array(
                                        'hari'      => $hari,
                                        'tanggal'   => $tanggal,
                                        'urut'      => $i,
                                        'tipe'      => $data_tipe,
                                        'pasien_id' => $pasien_id,
                                        'id'        => $id,
                                        'keterangan' => $ket
                                    );
                                    $data_url = serialize($data_url);

                                    $url = urlencode(base64_encode($data_url));
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
                                                $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                    

                                                if($tgl < $date_now)
                                                {
                                                    if($j < 5)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                $class_button = "btn view";
                                                                $pop_up = "popup_modal_view";
                                                                $url_function = "view_jadwal";
                                                                $disable = "";
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }                                                   
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if($j < 5)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                $class_button = "btn move";
                                                                $pop_up = "popup_modal_move";
                                                                $url_function = "edit_jadwal";
                                                                $ket = $keterangan_db;
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket,
                                                                    'minggu'    => $minggu
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }                                                       
                                                        }
                                                    }
                                                }

                                            }
                                            else if ($tgl == $tgl_banding_2) 
                                            {
                                                $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');

                                                if($tgl < $date_now)
                                                {
                                                    if($j < 9 && $j > 4)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                $class_button = "btn view";
                                                                $pop_up = "popup_modal_view";
                                                                $url_function = "view_jadwal";
                                                                $disable = "";
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if($j < 9 && $j > 4)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                $class_button = "btn move";
                                                                $pop_up = "popup_modal_move";
                                                                $url_function = "edit_jadwal";
                                                                $ket = $keterangan_db;
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket,
                                                                    'minggu'    => $minggu
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            else if ($tgl == $tgl_banding_3)
                                            {
                                                $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');

                                                if($tgl < $date_now)
                                                {
                                                    if($j < 13 && $j > 8)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                $class_button = "btn view";
                                                                $pop_up = "popup_modal_view";
                                                                $url_function = "view_jadwal";
                                                                $disable = "";
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if($j < 13 && $j > 8)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                $class_button = "btn move";
                                                                $pop_up = "popup_modal_move";
                                                                $url_function = "edit_jadwal";
                                                                $ket = $keterangan_db;
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket,
                                                                    'minggu'    => $minggu
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            else if ($tgl == $tgl_banding_4)
                                            {
                                                $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');

                                                if($tgl < $date_now)
                                                {
                                                    if($j < 17 && $j > 12)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                $class_button = "btn view";
                                                                $pop_up = "popup_modal_view";
                                                                $url_function = "view_jadwal";
                                                                $disable = "";
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if($j < 17 && $j > 12)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                $class_button = "btn move";
                                                                $pop_up = "popup_modal_move";
                                                                $url_function = "edit_jadwal";
                                                                $ket = $keterangan_db;
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket,
                                                                    'minggu'    => $minggu
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            else if ($tgl == $tgl_banding_5)
                                            {
                                                $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');

                                                if($tgl < $date_now)
                                                {
                                                    if($j < 21 && $j > 16)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                $class_button = "btn view";
                                                                $pop_up = "popup_modal_view";
                                                                $url_function = "view_jadwal";
                                                                $disable = "";
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if($j < 21 && $j > 16)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                $class_button = "btn move";
                                                                $pop_up = "popup_modal_move";
                                                                $url_function = "edit_jadwal";
                                                                $ket = $keterangan_db;
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket,
                                                                    'minggu'    => $minggu
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            else if ($tgl == $tgl_banding_6)
                                            {
                                                $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');

                                                if($tgl < $date_now)
                                                {
                                                    if($j < 25 && $j > 20)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                $class_button = "btn view";
                                                                $pop_up = "popup_modal_view";
                                                                $url_function = "view_jadwal";
                                                                $disable = "";
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if($j < 25 && $j > 20)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                $class_button = "btn move";
                                                                $pop_up = "popup_modal_move";
                                                                $url_function = "edit_jadwal";
                                                                $ket = $keterangan_db;
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket,
                                                                    'minggu'    => $minggu
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            else if($tgl == $tgl_banding_7)
                                            {
                                                $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                                                            
                                                if($tgl < $date_now)
                                                {
                                                    if($j < 29 && $j > 24)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                $class_button = "btn view";
                                                                $pop_up = "popup_modal_view";
                                                                $url_function = "view_jadwal";
                                                                $disable = "";
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-blue-sharp";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if($j < 29 && $j > 24)
                                                    {
                                                        if($tipe_waktu == $tipe_data)
                                                        {
                                                            if($no_bed_db == $i)
                                                            {
                                                                // die(dump($data_row['id']));
                                                                $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                $class_button = "btn move";
                                                                $pop_up = "popup_modal_move";
                                                                $url_function = "edit_jadwal";
                                                                $ket = $keterangan_db;
                                                                $pasien_id = $data_row['pasien_id'];
                                                                $nama_pasien = $pasien_nama;
                                                                $id = $data_row['id'];                                                  
                                                                // die(dump($data_row['id']));
                                                                $data_url = array(
                                                                    'hari'      => $hari,
                                                                    'tanggal'   => $tanggal,
                                                                    'urut'      => $i,
                                                                    'tipe'      => $data_tipe,
                                                                    'pasien_id' => $pasien_id,
                                                                    'id'        => $id,
                                                                    'keterangan' => $ket,
                                                                    'minggu'    => $minggu
                                                                );
                                                                $data_url = serialize($data_url);

                                                                $url = urlencode(base64_encode($data_url));

                                                                if($data_row['status'] == 0){
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                    }
                                    
                                    if($j == 1 || $j == 5 || $j == 9 || $j == 13 || $j == 17 || $j == 21 || $j == 25)
                                    {
                                        
                                        $btn_default = '<td class="text-center"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-pasien-id="'.$pasien_id.'" data-id="'.$id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.' ><i class="'.$class_tipe.'"></i></a></td>';
                                        $html .= $btn_default;
                                    }
                                    else if($j == 2 || $j == 6 || $j == 10 || $j == 14 || $j == 18 || $j == 22 || $j == 26)
                                    {
                                        
                                        $btn_default = '<td class="text-center"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'"  href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
                                        $html .= $btn_default;
                                    }
                                    else if($j == 3 || $j == 7 || $j == 11 || $j == 15 || $j == 19 || $j == 23 || $j == 27)
                                    {
                                        
                                       $btn_default = '<td class="text-center"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'"  href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
                                        $html .= $btn_default;
                                    }
                                    else
                                    {
                                        $btn_default = '<td class="text-center"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'"  href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
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

        public function get_tanggal_hari()
        {
            if($this->input->is_ajax_request())
            {
                $tanggal = $this->input->post('tanggal');
                $hari_view = $this->input->post('hari');
                $cabang = $this->session->userdata('cabang_id');

                $htmlx ='<table class="table table-striped table-bordered table-hover hidden" id="table_pasien_move">
                <thead>
                <tr>
                    <th class="text-center" rowspan="2" style ="vertical-align: middle; font-size: 12px; width: 50px;">'.translate("No.Bed", $this->session->userdata("language")).' </th>
                    <th class="text-center" colspan="4" style ="font-size: 12px;">
                        <span class="input-group" style="width: 100%;">
                            <input class="form-group text-center" id="hari_tanggal" readonly="readonly" style=" background-color: transparent;border: 0px solid; width: 95%;">
                            <!-- <?=translate("Senin", $this->session->userdata("language"))?> -->
                            <a class="btn maximize" title="'.translate('Maximize', $this->session->userdata('language')).'" style="font-size: 14px;" id="back"><i class="fa fa-search-minus"></i></a>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <input type="text" class="form-control hidden" id="cabang_harian" name="cabang">
                                </div>
                            </div>
                        </span>
                    </th>
                </tr>
                <tr>

                    <th class="text-center" style ="font-size: 12px;">'.translate("Pagi (07:00 - 12:00)", $this->session->userdata("language")).'</th>
                    <th class="text-center" style ="font-size: 12px;">'.translate("Siang (13:00 - 18:00)", $this->session->userdata("language")).'</th>
                    <th class="text-center" style ="font-size: 12px;">'.translate("Sore (18:00 - 23:00)", $this->session->userdata("language")).'</th>
                    <th class="text-center" style ="font-size: 12px;">'.translate("Malam (23:00 - 03:00)", $this->session->userdata("language")).'</th>
        
                </tr>
                </thead>
                <tbody>';
                        $date = $tanggal;
                        $senin = $date;
                        $hari = $hari_view;
                        $next_date = strtotime(date('d M Y', strtotime($date)). " +1 days");
                        $selasa = date('d M Y', $next_date);
                        $next_date_1 = strtotime(date('d M Y', strtotime($date)). " +2 days");
                        $rabu = date('d M Y', $next_date_1);
                        $next_date_2 = strtotime(date('d M Y', strtotime($date)). " +3 days");
                        $kamis = date('d M Y', $next_date_2);
                        $next_date_3 = strtotime(date('d M Y', strtotime($date)). " +4 days");
                        $jumat = date('d M Y', $next_date_3);
                        $next_date_4 = strtotime(date('d M Y', strtotime($date)). " +5 days");
                        $sabtu = date('d M Y', $next_date_4);
                        $next_date_5 = strtotime(date('d M Y', strtotime($date)). " +6 days");
                        $minggu = date('d M Y', $next_date_5);
                        $minggu_ = date('d-m-Y', $next_date_5);
                        $valid_date = strtotime(date('d M Y', strtotime($date)). " +7 days");
                        $validasi = date('d M Y', $valid_date);

                        $data_pasien_klinik_move = $this->jadwal_m->get_data_tanggal(date('Y-m-d', strtotime($date)), $cabang)->result_array();
                       // die(dump($this->db->last_query()));
                        $data_pasien_move = object_to_array($data_pasien_klinik_move);

                        //die_dump($data_pasien_move);
                       
                        for ($i=1; $i <= 22 ; $i++) 
                        { 
                            $htmlx .= '<tr>
                                    <td class="text-center">'.$i.'</td>';
                                    for ($j=1; $j<=4; $j++) {

                                        $class_tipe = "glyphicon glyphicon-ok-sign font-grey";
                                        $class_button = "btn edit";
                                        $data_tipe = "";
                                        $data_id = "";
                                        $pop_up = "popup_modal";
                                        $url_function = "add_jadwal";
                                        $pasien_id = "";
                                        $nama_pasien = "...";
                                        $id = "";


                                        if($j == 1)
                                        {
                                            $data_tipe = "Pagi (07:00 - 12:00)";
                                        }
                                        else if($j == 2)
                                        {
                                            $data_tipe = "Siang (13:00 - 18:00)";
                                        }
                                        else if($j == 3)
                                        {
                                            $data_tipe = "Sore (18:00 - 23:00)";
                                        }
                                        else
                                        {
                                            $data_tipe = "Malam (23:00 - 03:00)";
                                        }

                                        $keterangan_db ="";
                                        $ket = "";
                                        $id_pasien_jadwal = "";
                                        $disable = "";
                                        $id = "";
                                        $data_url = array(
                                            'hari'      => $hari,
                                            'tanggal'   => $tanggal,
                                            'urut'      => $i,
                                            'tipe'      => $data_tipe,
                                            'pasien_id' => $pasien_id,
                                            'id'        => $id,
                                            'keterangan' => $ket,
                                            'minggu'   => $minggu_,

                                        );
                                        $data_url = serialize($data_url);

                                        $url = urlencode(base64_encode($data_url));
                                        $nama = "...";
                                        foreach ($data_pasien_move as $data_row) 
                                                {
                                                    // $data_row_pasien[$data_row[]

                                                    $pasien_nama = $data_row['nama'];
                                                    $pasien_id = $data_row['pasien_id'];
                                                    //$pasien = $this->pasien_m->get($pasien_id);
                                                    $keterangan_db = $data_row['keterangan'];
                                                    $tanggal_db = $data_row['tanggal'];
                                                    $tipe_db = $data_row['tipe'];
                                                    $no_bed_db = $data_row['no_urut_bed'];
                                                    $tgl =  date('Y-m-d',strtotime($tanggal_db));
                                                    $id = $data_row['id'];
                                                    $tgl_banding_1 = date('Y-m-d',strtotime($date));
                                                    $tgl_banding_2 = date('Y-m-d',strtotime($selasa));
                                                    $tgl_banding_3 = date('Y-m-d',strtotime($rabu));
                                                    $tgl_banding_4 = date('Y-m-d',strtotime($kamis));
                                                    $tgl_banding_5 = date('Y-m-d',strtotime($jumat));
                                                    $tgl_banding_6 = date('Y-m-d',strtotime($sabtu));
                                                    $tgl_banding_7 = date('Y-m-d',strtotime($minggu));
                                                    

                                                    $date_now = date('Y-m-d');

                                                    
                                                    if ($tgl == $tgl_banding_1)
                                                    {  
                                                        $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                                                        if($tgl < $date_now)
                                                        {
                                                                if($tipe_db == $j)
                                                                {
                                                                    
                                                                    if($no_bed_db == $i)
                                                                    {
                                                                        $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                        $class_button = "btn view";
                                                                        $pop_up = "popup_modal_view";
                                                                        $url_function = "view_jadwal";
                                                                        $disable = "";
                                                                        $pasien_id = $data_row['pasien_id'];
                                                                        $nama_pasien = $pasien_nama;
                                                                        $id = $data_row['id'];
                                                                        $data_url = array(
                                                                            'hari'      => $hari,
                                                                            'tanggal'   => $tanggal,
                                                                            'urut'      => $i,
                                                                            'tipe'      => $data_tipe,
                                                                            'pasien_id' => $pasien_id,
                                                                            'id'        => $id,
                                                                            'keterangan' => $ket
                                                                        );
                                                                        $data_url = serialize($data_url);

                                                                        $url = urlencode(base64_encode($data_url));
                                                                        $nama = $pasien_nama;
                                                                    }
                                                                                                                      
                                                                }
                                                        }
                                                        else
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                    $class_button = "btn move";
                                                                    $pop_up = "popup_modal_move";
                                                                    $url_function = "edit_jadwal";
                                                                    $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;

                                                                    
                                                                }                                                       
                                                            }
                                                        }

                                                    }
                                                    else if ($tgl == $tgl_banding_2) 
                                                    {
                                                        $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                                                        if($tgl < $date_now)
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;                                                                   
                                                                }
                                                            }
                                                            
                                                        }
                                                        else
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                    $class_button = "btn move";
                                                                    $pop_up = "popup_modal_move";
                                                                    $url_function = "edit_jadwal";
                                                                    $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;

                                                                    
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else if ($tgl == $tgl_banding_3)
                                                    {
                                                        $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                                                        if($tgl < $date_now)
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;
                                                                }
                                                            }
                                                            
                                                        }
                                                        else
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                    $class_button = "btn move";
                                                                    $pop_up = "popup_modal_move";
                                                                    $url_function = "edit_jadwal";
                                                                    $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;

                                                                    
                                                                }
                                                            }
                                                        }
                                                    }

                                                    else if ($tgl == $tgl_banding_4)
                                                    {
                                                        $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                                                        if($tgl < $date_now)
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;
                                                                }
                                                            }
                                                            
                                                        }
                                                        else
                                                        {
                                                            if($tipe_db == $j)
                                                            {

                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                    $class_button = "btn move";
                                                                    $pop_up = "popup_modal_move";
                                                                    $url_function = "edit_jadwal";
                                                                    $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;

                                                                    
                                                                }
                                                            }
                                                            
                                                        }
                                                    }

                                                    else if ($tgl == $tgl_banding_5)
                                                    {
                                                        $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                                                        if($tgl < $date_now)
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;
                                                                }
                                                            }
                                                        }
                                                        else
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                    $class_button = "btn move";
                                                                    $pop_up = "popup_modal_move";
                                                                    $url_function = "edit_jadwal";
                                                                    $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;

                                                                    
                                                                }
                                                            }
                                                            
                                                        }
                                                    }

                                                    else if ($tgl == $tgl_banding_6)
                                                    {
                                                        $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                                                        if($tgl < $date_now)
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;
                                                                }
                                                            }
                                                            
                                                        }
                                                        else
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                    $class_button = "btn move";
                                                                    $pop_up = "popup_modal_move";
                                                                    $url_function = "edit_jadwal";
                                                                    $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;

                                                                    
                                                                }
                                                            }
                                                            
                                                        }
                                                    }

                                                    else 
                                                    {
                                                        $date_now = new DateTime();
                                                $date_now = $date_now->format('Y-m-d');
                                                $tgl = new DateTime($tgl);
                                                $tgl = $tgl->format('Y-m-d');
                                                        if($tgl < $date_now)
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-info-sign font-grey-cascade";
                                                                    $class_button = "btn view";
                                                                    $pop_up = "popup_modal_view";
                                                                    $url_function = "view_jadwal";
                                                                    $disable = "";
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;
                                                                }
                                                            }
                                                        }
                                                        else
                                                        {
                                                            if($tipe_db == $j)
                                                            {
                                                                if($no_bed_db == $i)
                                                                {
                                                                    $class_tipe = "glyphicon glyphicon-tag font-blue-sharp";
                                                                    $class_button = "btn move";
                                                                    $pop_up = "popup_modal_move";
                                                                    $url_function = "edit_jadwal";
                                                                    $ket = $keterangan_db;
                                                                    $pasien_id = $data_row['pasien_id'];
                                                                    $nama_pasien = $pasien_nama;
                                                                    $id = $data_row['id'];
                                                                    $data_url = array(
                                                                        'hari'      => $hari,
                                                                        'tanggal'   => $tanggal,
                                                                        'urut'      => $i,
                                                                        'tipe'      => $data_tipe,
                                                                        'pasien_id' => $pasien_id,
                                                                        'id'        => $id,
                                                                        'keterangan' => $ket,
                                                                        'minggu'   => $minggu_
                                                                    );
                                                                    $data_url = serialize($data_url);

                                                                    $url = urlencode(base64_encode($data_url));
                                                                    $nama = $pasien_nama;     

                                                                               
                                                                }
                                                            }
                                                            
                                                        }
                                                       
                                                    }
                                                }
                                                if($j == 1)
                                                    {
                                                        
                                                        $btn_default = '<td class="text-center" style="width: 25%"><input tipe="text" id="nama_pasien_harian" style=" background-color: transparent;border: 0px solid; width: 80%;" readonly="readonly" value="'.$nama.'"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-nama="'.$nama.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.' ><i class="'.$class_tipe.'"></i></a></td>';
                                                        $htmlx .= $btn_default;
                                                    }
                                                    else if($j == 2)
                                                    {
                                                        
                                                        $btn_default = '<td class="text-center" style="width: 25%"><input tipe="text" id="nama_pasien_harian" style=" background-color: transparent;border: 0px solid; width: 80%;" readonly="readonly" value="'.$nama.'"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-nama="'.$nama.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
                                                        $htmlx .= $btn_default;
                                                    }
                                                    else if($j == 3)
                                                    {
                                                        
                                                        $btn_default = '<td class="text-center" style="width: 25%"><input tipe="text" id="nama_pasien_harian" style=" background-color: transparent;border: 0px solid; width: 80%;" readonly="readonly" value="'.$nama.'"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-nama="'.$nama.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
                                                        $htmlx .= $btn_default;
                                                    }
                                                    else
                                                    {
                                                        $btn_default = '<td class="text-center" style="width: 25%"><input tipe="text" id="nama_pasien_harian" style=" background-color: transparent;border: 0px solid; width: 80%;" readonly="readonly" value="'.$nama.'"><a data-target="#'.$pop_up.'" title="'.$nama_pasien.'" href="'.base_url().'klinik_hd/jadwal/'.$url_function.'/'.$url.'" data-tanggal="'.$tanggal.'" data-nama="'.$nama.'" data-pasien-id="'.$id_pasien_jadwal.'" data-id="'.$data_id.'" data-keterangan="'.$ket.'" data-hari="'.$hari.'" data-toggle="modal" data-tipe="'.$data_tipe.'" data-urut="'.$i.'" id="edit_'.$i.'_'.$j.'" class="'.$class_button.'" '.$disable.'><i class="'.$class_tipe.'"></i></a></td>';
                                                        $htmlx .= $btn_default;
                                                    }
                                        
                                    }
                            $htmlx .= '</tr>';
                        }

                $htmlx .= '</tbody>
                </table>';
            }
            echo $htmlx;
        }
        
        // redirect("master/item");

    public function get_jadwal()
    {
        if($this->input->is_ajax_request())
        {
            $tgl = $this->input->post('tgl');

            $response = new StdClass;
            $response->success = false;
            $response->msg = translate('Jadwal Penuh', $this->session->userdata('language'));

            $date = date('Y-m-d', strtotime($tgl));
            $time = date('H', strtotime($tgl));


            $jadwal = $this->jadwal_m->get_by_tgl_waktu($date,$time)->result_array();
            
            if(count($jadwal) < 22)
            {
                $response->success = true;
                $response->msg = translate('Jadwal Tersedia', $this->session->userdata('language'));
            }

            die(json_encode($response));

        }
    }

    public function check_modified()
    {
        if($this->input->is_ajax_request())
        {
            $response = new StdClass;
            $response->success = false;
            $response->msg = translate('Data yang akan anda ubah telah diubah oleh user lain. Apakah anda ingin membatalkannya?', $this->session->userdata('language'));

            $id = $this->input->post('id');
            $modified_date = $this->input->post('modified_date');

            $jadwal = $this->jadwal_m->get($id);

            if($jadwal->modified_date == $modified_date)
            {
                $response->success = true;
                $response->msg = '';
            }

            echo json_encode($response);

        }
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

                $response->success = true;
                $response->msg = translate('Data Pasien Ditemukan', $this->session->userdata('language'));
                $response->rows = $pasien;
            }

            die(json_encode($response));

        }
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/klinik_hd/jadwal.php */