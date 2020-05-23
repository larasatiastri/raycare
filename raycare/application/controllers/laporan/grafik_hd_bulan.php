<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grafik_hd_bulan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '74c6626df34ee38caabbe2878e138ed8';                  // untuk check bit_access
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

        $this->load->model('laporan/tindakan_hd_m');
        $this->load->model('laporan/pasien_m');
        $this->load->model('master/pasien_meninggal_m');
        $this->load->model('klinik_hd/surat_traveling_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/laporan/grafik_hd_bulan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Grafik Tindakan HD', $this->session->userdata('language')), 
            'header'         => translate('Grafik Tindakan HD', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laporan/grafik_hd_bulan/index',
        );
    
        // Load the view
        $this->load->view('_layout', $data);
    }

    function get_day_trans($start, $penjamin_id)
    {
        $level_id = $this->session->userdata('level_id');
        $user_id = $this->session->userdata('user_id');
        $cabang_id = $this->session->userdata('cabang_id');

        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));


        $response = new stdClass;
        $response->success = false;

        $sql = " SELECT
                    DATE_FORMAT(a.`tanggal`,'%Y-%m-%d') AS 'date',
                    COUNT(*) AS this_trans_count
                FROM
                    tindakan_hd_history a
                JOIN 
                    pasien
                ON a.pasien_id = pasien.id
                WHERE
                    MONTH(a.tanggal) = ?
                AND
                    YEAR(a.tanggal) = ?";
        if($penjamin_id != 0 && $penjamin_id == 1){
            $sql .= " AND a.penjamin_id = 1"; 
        }if($penjamin_id != 0 && $penjamin_id != 1){
            $sql .= " AND a.penjamin_id != 1"; 
        }if($penjamin_id == 0 ){
            $sql .= " AND a.penjamin_id IS NOT NULL"; 
        }
        if($level_id == config_item('level_marketing_id')){
            $sql .= " AND pasien.marketing_id = $user_id";
        }
        $sql .= " AND a.status != 1 AND a.status != 4 AND a.is_manual IS NULL AND a.is_active = 1 AND a.cabang_pasien_id = $cabang_id AND a.cabang_id = $cabang_id OR ";
        $sql .= " MONTH(a.tanggal) = ?
                AND
                    YEAR(a.tanggal) = ?";
        if($penjamin_id != 0 && $penjamin_id == 1){
            $sql .= " AND a.penjamin_id = 1"; 
        }if($penjamin_id != 0 && $penjamin_id != 1){
            $sql .= " AND a.penjamin_id != 1"; 
        }if($penjamin_id == 0 ){
            $sql .= " AND a.penjamin_id IS NOT NULL"; 
        }
        if($level_id == config_item('level_marketing_id')){
            $sql .= " AND pasien.marketing_id = $user_id";
        }
        $sql .= " AND a.status != 1 AND a.status != 4 AND a.is_manual = 0 AND a.is_active = 1 AND a.cabang_pasien_id = $cabang_id AND a.cabang_id = $cabang_id ";

        $sql .= " GROUP BY date(`tanggal`)";

        $query = $this->db->query(
            $sql,
            array( $param_month,$param_year,$param_month,$param_year)
        );
        
        $response->success = true;
        $response->rows = $query->result();
        $response->query = $this->db->last_query();

        die(json_encode($response));
    }

    function get_day_patient($start, $penjamin_id)
    {
        $level_id = $this->session->userdata('level_id');
        $user_id = $this->session->userdata('user_id');

        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));


        $response = new stdClass;
        $response->success = false;

        $sql = " SELECT
                    COUNT(DISTINCT a.pasien_id) AS jml_pasien
                FROM
                    tindakan_hd_history a
                JOIN 
                    pasien
                ON a.pasien_id = pasien.id
                WHERE
                    MONTH(a.tanggal) = ?
                AND
                    YEAR(a.tanggal) = ?";
        if($penjamin_id != 0 && $penjamin_id == 1){
            $sql .= " AND a.penjamin_id = 1"; 
        }if($penjamin_id != 0 && $penjamin_id != 1){
            $sql .= " AND a.penjamin_id != 1"; 
        }
        if($level_id == config_item('level_marketing_id')){
            $sql .= " AND pasien.marketing_id = $user_id";
        }
        $sql .= " AND a.status != 1 AND a.status != 4 AND a.is_manual IS NULL AND a.is_active = 1";

        $query = $this->db->query(
            $sql,
            array( $param_month,$param_year)
        );
        
        $response->success = true;
        $response->rows = $query->row(0);
        

        die(json_encode($response));
    }

    function get_last_trans($start, $penjamin_id)
    {
        $level_id = $this->session->userdata('level_id');
        $user_id = $this->session->userdata('user_id');
        $cabang_id = $this->session->userdata('cabang_id');

        $start = str_replace('%20', '-' , $start);

        $param_last_year = date('Y', strtotime($start." -1 months"));
        $param_last_month = date('m', strtotime($start." -1 months"));

        $response = new stdClass;
        $response->success = false;

        $sql = " SELECT
                    DATE_FORMAT(a.`tanggal`,'%Y-%m-%d') AS 'date',
                    COUNT(*) AS last_trans_count
                FROM
                    tindakan_hd_history a
                JOIN 
                    pasien
                ON a.pasien_id = pasien.id
                WHERE
                    MONTH(a.tanggal) = ?
                AND
                    YEAR(a.tanggal) = ?";
        if($penjamin_id != 0 && $penjamin_id == 1){
            $sql .= " AND a.penjamin_id = 1"; 
        }if($penjamin_id != 0 && $penjamin_id != 1){
            $sql .= " AND a.penjamin_id != 1"; 
        }if($penjamin_id == 0 ){
            $sql .= " AND a.penjamin_id IS NOT NULL"; 
        }
        if($level_id == config_item('level_marketing_id')){
            $sql .= " AND pasien.marketing_id = $user_id";
        }
        $sql .= " AND a.status != 1 AND a.status != 4 AND a.is_manual IS NULL AND a.is_active = 1 AND a.cabang_pasien_id = $cabang_id AND a.cabang_id = $cabang_id OR ";
        $sql .= " MONTH(a.tanggal) = ?
                AND
                    YEAR(a.tanggal) = ?";
        if($penjamin_id != 0 && $penjamin_id == 1){
            $sql .= " AND a.penjamin_id = 1"; 
        }if($penjamin_id != 0 && $penjamin_id != 1){
            $sql .= " AND a.penjamin_id != 1"; 
        }if($penjamin_id == 0 ){
            $sql .= " AND a.penjamin_id IS NOT NULL"; 
        }
        if($level_id == config_item('level_marketing_id')){
            $sql .= " AND pasien.marketing_id = $user_id";
        }
        $sql .= " AND a.status != 1 AND a.status != 4 AND a.is_manual = 0 AND a.is_active = 1 AND a.cabang_pasien_id = $cabang_id AND a.cabang_id = $cabang_id ";

        $sql .= " GROUP BY date(`tanggal`)";

        $query = $this->db->query(
            $sql,
            array( $param_last_month,$param_last_year, $param_last_month,$param_last_year)
        );
        
        $response->success = true;
        $response->rows = $query->result();
        

        die(json_encode($response));
    }

    function get_last_patient($start, $penjamin_id)
    {

        $level_id = $this->session->userdata('level_id');
        $user_id = $this->session->userdata('user_id');

        $start = str_replace('%20', '-' , $start);

        $param_last_year = date('Y', strtotime($start." -1 months"));
        $param_last_month = date('m', strtotime($start." -1 months"));


        $response = new stdClass;
        $response->success = false;

        $sql = " SELECT
                    COUNT(DISTINCT a.pasien_id) AS jml_pasien
                FROM
                    tindakan_hd_history a
                JOIN 
                    pasien
                ON a.pasien_id = pasien.id
                WHERE
                    MONTH(a.tanggal) = ?
                AND
                    YEAR(a.tanggal) = ?";

        if($penjamin_id != 0 && $penjamin_id == 1){
            $sql .= " AND a.penjamin_id = 1"; 
        }if($penjamin_id != 0 && $penjamin_id != 1){
            $sql .= " AND a.penjamin_id != 1"; 
        }
        if($level_id == config_item('level_marketing_id')){
            $sql .= " AND pasien.marketing_id = $user_id";
        }
        $sql .= " AND a.status != 1 AND a.status != 4 AND a.is_manual IS NULL AND a.is_active = 1";

        $query = $this->db->query(
            $sql,
            array( $param_last_month,$param_last_year)
        );
        
        $response->success = true;
        $response->rows = $query->row(0);
        

        die(json_encode($response));
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($start, $penjamin_id)
    {        
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        $result = $this->tindakan_hd_m->get_datatable_report($param_month,$param_year,$penjamin_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records      = $result->records;
    //die(dump($records));
        $i = 1;
        $jml_tindakan = 0;
        $jml_pagi     = 0;
        $jml_siang    = 0;
        $jml_sore     = 0;
        $jml_malam    = 0;
        $input_tindakan = '';
        $input_pagi = '';
        $input_siang = '';
        $input_sore = '';
        $input_malam = '';
        $count = count($records->result_array());
        foreach($records->result_array() as $row)
        {         
            $day = '';
            $current_date = date('D', strtotime($row['date']));

            if($current_date == 'Mon') $day = "Senin";
            if($current_date == 'Tue') $day = "Selasa";
            if($current_date == 'Wed') $day = "Rabu";
            if($current_date == 'Thu') $day = "Kamis";
            if($current_date == 'Fri') $day = "Jumat";
            if($current_date == 'Sat') $day = "Sabtu";
            if($current_date == 'Sun') $day = "Minggu";

            $jml_tindakan = $jml_tindakan + $row['trans_count'];
            $jml_pagi     = $jml_pagi + $row['pagi'];
            $jml_siang    = $jml_siang + $row['siang'];
            $jml_sore     = $jml_sore + $row['sore'];
            $jml_malam    = $jml_malam + $row['malam'];

            if($i == $count)
            {
                $input_tindakan = '<input type="hidden" id="input_tindakan" value="'.$jml_tindakan.'">';
                $input_pagi     = '<input type="hidden" id="input_pagi" value="'.$jml_pagi.'">';
                $input_siang    = '<input type="hidden" id="input_siang" value="'.$jml_siang.'">';
                $input_sore     = '<input type="hidden" id="input_sore" value="'.$jml_sore.'">';
                $input_malam    = '<input type="hidden" id="input_malam" value="'.$jml_malam.'">';
            }
            $output['data'][] = array(
                '<div class="text-center"><b><a href="'.base_url().'laporan/grafik_hd_bulan/get_tindakan/'.date('d-M-Y', strtotime($row['date'])).'/'.$penjamin_id.'" data-toggle="modal" data-target="#modal_laporan">'.date('d', strtotime($row['date'])).'</a></b></div>',
                '<div class="text-left">'.$day.'</div>', 
                '<div class="text-left">'.$row['trans_count'].$input_tindakan.'</div>', 
                '<div class="text-left">'.$row['pagi'].$input_pagi.'</div>', 
                '<div class="text-left">'.$row['siang'].$input_siang.'</div>', 
                '<div class="text-left">'.$row['sore'].$input_sore.'</div>', 
                '<div class="text-left">'.$row['malam'].$input_malam.'</div>'
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_pasien_baru($start)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        $result = $this->pasien_m->get_datatable_report($param_month,$param_year);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records  = $result->records;

        $i = 1;
        foreach($records->result_array() as $row)
        { 
            $output['data'][] = array(
               '<div class="text-center">'.$i.'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_pasien'].'</div>', 
                '<div class="text-center inline-button-table">'.date('d M', strtotime($row['tanggal_daftar'])).'</div>',
                '<div class="text-left inline-button-table">'.$row['faskes_tk_1'].'</div>', 
                '<div class="text-left inline-button-table">'.$row['nama_faskes'].'</div>', 
                '<div class="text-left inline-button-table">'.$row['nama_marketing'].'</div>', 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function cetak_pdf($tgl_awal=null, $tgl_akhir=null)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $this->tindakan_hd_m->csv($tgl_awal,$tgl_akhir);
    }

    public function listing_pasien_meninggal($start)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        $result = $this->pasien_meninggal_m->get_datatable_report($param_month, $param_year);
        //die_dump($result);
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
            $lokasi_meninggal = '';

            if($row['tipe_lokasi'] == 1)
            {
                $cabang = $this->cabang_m->get($row['cabang_meninggal']);
                $lokasi_meninggal = $cabang->nama;
            }
            else
            {
                $lokasi_meninggal = $row['lokasi_meninggal'];
            }

            $output['data'][] = array(
                $i,
                $row['nama'],
                '<div class="text-center inline-button-table">'.date('d M', strtotime($row['tanggal_meninggal'])).'</div>' ,
                '<div class="text-left inline-button-table">'.$lokasi_meninggal.'</div>',
            );
            $i++;
            
        }
        echo json_encode($output);
    }

    public function listing_pasien_pindah($start)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        $result = $this->surat_traveling_m->get_datatable_report($param_month, $param_year);
        //die_dump($result);
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
                 '<div class="text-left inline-button-table">'.$row['nama'].'</div>',
                 '<div class="text-center inline-button-table">'.date('d M y', strtotime($row['tanggal'])).'</div>' ,
                 '<div class="text-left inline-button-table">'.$row['rs_tujuan'].'</div>' ,
                 '<div class="text-left inline-button-table">'.$row['alasan_pindah'].'</div>' ,
            );
            $i++;
            
        }
        echo json_encode($output);
    }
    public function listing_pasien_traveling($start)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        $result = $this->surat_traveling_m->get_datatable_report_travel($param_month, $param_year);
        //die_dump($result);
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
            $tipe = '';
            $batas = '';
            $alasan = '';
            if($row['jenis_lama'] == 1){
                $tipe = 'Hari';
                $batas = date('d M y', strtotime($row['tanggal'].' +'.$row['lama_traveling'].' days'));
            }
            if($row['jenis_lama'] == 2){
                $tipe = 'Minggu';
                $batas = date('d M y', strtotime($row['tanggal'].' +'.$row['lama_traveling'].' weeks'));
            }
            if($row['jenis_lama'] == 3){
                $tipe = 'Bulan';
                $batas = date('d M y', strtotime($row['tanggal'].' +'.$row['lama_traveling'].' months'));
            }

            if($row['alasan_traveling'] == 1){
                $alasan = 'Liburan';
            }
            if($row['alasan_traveling'] == 2){
                $alasan = 'Dirawat';
            }
            if($row['alasan_traveling'] == 3){
                $alasan = 'Dinas';
            }

            $status = '<div class="text-left"><span class="label label-md label-danger">Belum Kembali</span></div>';
            if($row['status'] == 1){
                $status = '<div class="text-left"><span class="label label-md label-success">Kembali</span></div>';
            }

            $output['data'][] = array(
                $i,
                 '<div class="text-left inline-button-table">'.$row['nama'].'</div>',
                 '<div class="text-center inline-button-table">'.date('d M y', strtotime($row['tanggal'])).'</div>' ,
                 '<div class="text-left inline-button-table">'.$batas.'</div>' ,
                 '<div class="text-left inline-button-table">'.$row['rs_tujuan'].'</div>' ,
                 '<div class="text-left inline-button-table">'.$alasan.'</div>' ,
                 $status,
                 '<div class="text-left inline-button-table">'.$row['nama_dokter_buat'].'</div>' ,
                 
            );
            $i++;
            
        }
        echo json_encode($output);
    }

    public function cetak_data_pasien($start, $penjamin_id,$tipe)
    {
        $assets = array();
        $config = 'assets/laporan/grafik_hd_bulan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'        => config_item('site_name').' | '.translate('Data Pasien', $this->session->userdata('language')), 
            'header'       => translate('Data Pasien', $this->session->userdata('language')), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => true,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'start'        => $start,
            'penjamin_id'  => $penjamin_id,
            'tipe'         => $tipe,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'laporan/grafik_hd_bulan/cetak_data_pasien',
        );
    
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_data_pasien($start,$penjamin_id,$tipe)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        if($tipe == 2)
        {
            $param_year = date('Y', strtotime($start." -1 months"));
            $param_month = date('m', strtotime($start." -1 months"));
        }

        $result = $this->pasien_m->get_datatable_data_bulan($param_month, $param_year,$penjamin_id);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));

        $i=1;
        foreach($records->result_array() as $row)
        {
            

            $output['data'][] = array(
                $i,
                '<div class="text-left inline-button-table">'.$row['nama_pasien'].'</div>',
                '<div class="text-left inline-button-table">'.$row['alamat'].' '.$row['nama_kelurahan'].' '.$row['nama_kecamatan'].' '.$row['nama_kabupatenkota'].'</div>',
                 '<div class="text-left inline-button-table">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                 '<div class="text-left inline-button-table">'.date('d M Y', strtotime($row['tanggal_daftar'])).'</div>' ,
            );
            $i++;
            
        }
        echo json_encode($output);
    }

    public function csv($start,$penjamin_id,$tipe)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        if($tipe == 2)
        {
            $param_year = date('Y', strtotime($start." -1 months"));
            $param_month = date('m', strtotime($start." -1 months"));
        }

        $this->pasien_m->csv($param_month,$param_year,$penjamin_id);
    }

    public function get_tindakan($tgl,$penjamin_id)
    {
        $param = date('Y-m-d', strtotime($tgl));
        $param_year = date('Y', strtotime($tgl));
        $param_month = date('m', strtotime($tgl));

        $result = new stdClass;

        $param_past = date('Y-m-d', strtotime($tgl." -1 months"));
        $param_past_year = date('Y', strtotime($tgl." -1 months"));
        $param_past_month = date('m', strtotime($tgl." -1 months"));

        // die(dump($param_past));


        $param_past2 = date('Y-m-d', strtotime($tgl." -2 months"));
        $param_past2_year = date('Y', strtotime($tgl." -2 months"));
        $param_past2_month = date('m', strtotime($tgl." -2 months"));

        $param_past3 = date('Y-m-d', strtotime($tgl." -3 months"));
        $param_past3_year = date('Y', strtotime($tgl." -3 months"));
        $param_past3_month = date('m', strtotime($tgl." -3 months"));

        $data_per_tgl = $this->tindakan_hd_m->get_data_pertanggal($param,$penjamin_id)->row(0);
        $data_per_bulan = $this->tindakan_hd_m->get_data_perbulan($param,$param_month,$param_year,$penjamin_id)->row(0);

        $data_per_tgl_past = $this->tindakan_hd_m->get_data_pertanggal($param_past,$penjamin_id)->row(0);
        $data_per_bulan_past = $this->tindakan_hd_m->get_data_perbulan($param_past,$param_past_month,$param_past_year,$penjamin_id)->row(0);

        $data_per_tgl_past2 = $this->tindakan_hd_m->get_data_pertanggal($param_past2,$penjamin_id)->row(0);
        $data_per_bulan_past2 = $this->tindakan_hd_m->get_data_perbulan($param_past2,$param_past2_month,$param_past2_year,$penjamin_id)->row(0);

        $data_per_tgl_past3 = $this->tindakan_hd_m->get_data_pertanggal($param_past3,$penjamin_id)->row(0);
        $data_per_bulan_past3 = $this->tindakan_hd_m->get_data_perbulan($param_past3,$param_past3_month,$param_past3_year,$penjamin_id)->row(0);

        $countdatenow = $data_per_tgl->jumlah;
        $countmonthnow = $data_per_bulan->jumlah_total;

        $countdatepast = $data_per_tgl_past->jumlah;
        $countmonthpast = $data_per_bulan_past->jumlah_total;

        $countdatepast2 = $data_per_tgl_past2->jumlah;
        $countmonthpast2 = $data_per_bulan_past2->jumlah_total;

        $countdatepast3 = $data_per_tgl_past3->jumlah;
        $countmonthpast3 = $data_per_bulan_past3->jumlah_total;

        $grand_total_all = $this->tindakan_hd_m->get_data_all(0,$penjamin_id)->result_array();
        $grand_total_year = $this->tindakan_hd_m->get_data_all($param_year,$penjamin_id)->result_array();


        $data = array(
            'tanggal'      => $param,
            'tanggalpast'      => $param_past,
            'tanggalpast2'      => $param_past2,
            'tanggalpast3'      => $param_past3,
            'countdatenow' => $countdatenow,
            'countmonthnow' => $countmonthnow,
            'countdatepast' => $countdatepast,
            'countmonthpast' => $countmonthpast,
            'countdatepast2' => $countdatepast2,
            'countmonthpast2' => $countmonthpast2,
            'countdatepast3' => $countdatepast3,
            'countmonthpast3' => $countmonthpast3,
            'grand_total_all' => $grand_total_all,
            'grand_total_year' => $grand_total_year,
        );

        $this->load->view('laporan/grafik_hd_bulan/data_tindakan', $data);

    }
}

/* End of file spesialis.php */
/* Location: ./application/controllers/spesialis/spesialis.php */