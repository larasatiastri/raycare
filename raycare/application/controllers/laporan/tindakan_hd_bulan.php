<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_bulan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '3649cf33092f8c3e0c58f69f04caa665';                  // untuk check bit_access
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
        $this->load->model('klinik_hd/pasien_problem_m');
        $this->load->model('klinik_hd/pasien_komplikasi_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/laporan/tindakan_hd_bulan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Laporan Tindakan HD', $this->session->userdata('language')), 
            'header'         => translate('Laporan Tindakan HD', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laporan/tindakan_hd_bulan/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($tgl_awal=null,$tgl_akhir=null, $penjamin_id = null)
    {        
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $result = $this->tindakan_hd_m->get_datatable($tgl_awal,$tgl_akhir,$penjamin_id);

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
            $assesment = explode("\n", $row['assessment_cgs']);
            $assesment = implode('<br>', $assesment);

            $data_problems = $this->pasien_problem_m->get_by(array('tindakan_hd_id' => $row['id'], 'nilai' => 1));
            $data_complic = $this->pasien_komplikasi_m->get_by(array('tindakan_hd_id' => $row['id'], 'nilai' => 1));

            $peny_penyebab = $this->pasien_penyakit_m->get_tipe_penyakit($row['id_pasien'], 2)->result_array();
            $peny_bawaan = $this->pasien_penyakit_m->get_tipe_penyakit($row['id_pasien'], 1)->result_array();

            if ($row['url_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }

            $heparin = '-';
            if($row['heparin_reguler'] == 1){
                $heparin = "Regular";
            }if($row['heparin_minimal'] == 1){
                $heparin = "Minimal";
            }if($row['heparin_free'] == 1){
                $heparin = "Free";
            }

            $jenis_dialyzer = '-';
            if($row['dialyzer_new'] == 1){
                $jenis_dialyzer = "New";
            }if($row['dialyzer_reuse'] == 1){
                $jenis_dialyzer = "Reuse";
            }

            $blood_access = '-';
            if($row['ba_avshunt'] == 1){
                $blood_access = "AV Shunt";
            }if($row['ba_femoral'] == 1){
                $blood_access = "Femoral";
            }if($row['ba_catheter'] == 1){
                $blood_access = "Catheter";
            }

            $dialysate = '';
            if($row['dialyzer_type'] == 1){
                $dialysate = " | Bicarbonate";
            }

            $problems = '';
            foreach ($data_problems as $row_problems) {
                if($row_problems->problem_id == 1){
                    $problems .= 'Airway Clearance, ineffective; ';
                }if($row_problems->problem_id == 2){
                    $problems .= 'Fluid balance; ';
                }if($row_problems->problem_id == 3){
                    $problems .= 'High risk of infection; ';
                }if($row_problems->problem_id == 4){
                    $problems .= 'Impaired sense of comfort pain; ';
                }if($row_problems->problem_id == 5){
                    $problems .= 'Disequilibrium Syndrome; ';
                }if($row_problems->problem_id == 6){
                    $problems .= 'Shock Risk; ';
                }
            }

            $complic = '';
            foreach ($data_complic as $row_complic) {
                if($row_complic->komplikasi_id == 1){
                    $complic .= 'Bleeding; ';
                }if($row_complic->komplikasi_id == 2){
                    $complic .= 'Pruritus; ';
                }if($row_complic->komplikasi_id == 3){
                    $complic .= 'Alergie; ';
                }if($row_complic->komplikasi_id == 4){
                    $complic .= 'Headache; ';
                }if($row_complic->komplikasi_id == 5){
                    $complic .= 'Nausea; ';
                }if($row_complic->komplikasi_id == 6){
                    $complic .= 'Chest Pain; ';
                }if($row_complic->komplikasi_id == 7){
                    $complic .= 'Hypotension; ';
                }if($row_complic->komplikasi_id == 8){
                    $complic .= 'Shiver; ';
                }
            }

            $sebab = '';
            foreach ($peny_penyebab as $penyebab) {
                $sebab .= $penyebab->nama.', ';
            }

            $bawa = '';
            foreach ($peny_bawaan as $bawaan) {
                $bawa .= $bawaan->nama.', ';
            }

            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>', 
                '<div class="text-left">'.$row['nama'].'</div>', 
                '<div class="text-left">'.$row['time_of_dialysis'].' Jam</div>', 
                '<div class="text-left">'.$row['waktu'].'</div>', 
                '<div class="text-left">'.$assesment.'</div>', 
                '<div class="text-left">'.$row['medical_diagnose'].'</div>',
                '<div class="text-left">'.$row['quick_of_blood'].' ml/Jam</div>', 
                '<div class="text-left">'.$row['quick_of_dialysis'].' ml/Jam </div>', 
                '<div class="text-left">'.$row['uf_goal'].' Liter</div>', 
                '<div class="text-left">'.$heparin.'</div>', 
                '<div class="text-left">'.$row['dose'].'</div>', 
                '<div class="text-left">'.$row['first'].' U</div>', 
                '<div class="text-left">'.$row['maintenance'].' U / '.$row['hours'].' Jam</div>', 
                '<div class="text-center">'.$row['no_mesin'].'</div>',
                '<div class="text-left">'.$jenis_dialyzer.'</div>',
                '<div class="text-left">'.$row['dialyzer'].' BN: '.$row['bn_dialyzer'].'</div>',
                '<div class="text-left">'.$blood_access.$dialysate.'</div>',
                '<div class="text-left">'.rtrim($problems,'; ').'</div>',
                '<div class="text-left">'.rtrim($complic,'; ').'</div>',
                '<div class="text-left">'.$row['dokter_pengirim'].'</div>',
                '<div class="text-left">'.rtrim($bawa,', ').'</div>',
                '<div class="text-left">'.rtrim($sebab,', ').'</div>',
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function cetak_pdf($tgl_awal=null, $tgl_akhir=null,$penjamin_id=null)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $this->tindakan_hd_m->csv($tgl_awal,$tgl_akhir,$penjamin_id);
    }
}

/* End of file spesialis.php */
/* Location: ./application/controllers/spesialis/spesialis.php */