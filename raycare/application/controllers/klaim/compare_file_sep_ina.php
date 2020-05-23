<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compare_file_sep_ina extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '3a64ecad2756853853784acdc59ae950';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/tindakan_hd_history_m');        
        $this->load->model('klaim/compare_file_sep_ina/laporan_sep_file_m');        
        $this->load->model('klaim/compare_file_sep_ina/laporan_ina_file_m');        
         
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klaim/compare_file_sep_ina/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $this->laporan_sep_file_m->truncate_table();
        $this->laporan_ina_file_m->truncate_table();
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Bandingkan File', $this->session->userdata('language')), 
            'header'         => translate('Bandingkan File', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/compare_file_sep_ina/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/klaim/compare_file_sep_ina/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/compare_file_sep_ina/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }


    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($tgl_awal=null,$tgl_akhir=null)
    {        
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $result = $this->inacbg_modif_m->get_datatable($tgl_awal,$tgl_akhir);

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
            $action = '<input type="checkbox" id="inacbg_'.$i.'_check" name="inacbg['.$i.'][check]" value="1" checked>';

            $output['data'][] = array(
                '<div class="text-center">'.$row['Tglmsk'].'<input type="hidden" id="inacbg_'.$i.'_tgl" name="inacbg['.$i.'][tgl]" value="'.$row['Tglmsk'].'"></div>',
                '<div class="text-left">'.$row['Norm'].'<input type="hidden" id="inacbg_'.$i.'_norm" name="inacbg['.$i.'][norm]" value="'.$row['Norm'].'"></div>',
                '<div class="text-left">'.$row['NamaPasien'].'<input type="hidden" id="inacbg_'.$i.'_nama" name="inacbg['.$i.'][nama]" value="'.$row['NamaPasien'].'"></div>',
                '<div class="text-center">'.$row['no_sep'].'<input type="hidden" id="inacbg_'.$i.'_nosep" name="inacbg['.$i.'][nosep]" value="'.$row['no_sep'].'"></div>',
                '<div class="text-center">'.$row['no_penjamin_ina'].'<input type="hidden" id="inacbg_'.$i.'_penjaminina" name="inacbg['.$i.'][penjaminina]" value="'.$row['no_penjamin_ina'].'"></div>',
                '<div class="text-center">'.$row['no_penjamin'].'<input type="hidden" id="inacbg_'.$i.'_penjaminsep" name="inacbg['.$i.'][penjaminsep]" value="'.$row['no_penjamin'].'"></div>',
                '<div class="text-center ">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }


    public function send_mail()
    {
        $array_input = $this->input->post();

        $filename = $array_input['url'];
        $export   = $this->inacbg_modif_m->export_file($filename);
        
        $file     = config_item('file_ina_modif_exp').$filename;

        $max_kode = $this->pengiriman_file_txt_m->get_max_kode()->result_array();
        if(count($max_kode))
        {
            $max_kode = intval($max_kode[0]['max_kode']) + 1;
        }
        else
        {
            $max_kode = 1;
        }

        $format     = 'PFT/'.date('my').'/'.'%03d';
        $kode_kirim = sprintf($format, $max_kode, 3);

        if($export)
        {
            $data_kirim = array(
                'kode'            => $kode_kirim,
                'tanggal_awal'    => date('Y-m-d', strtotime($array_input['tgl_awal'])),
                'tanggal_akhir'   => date('Y-m-d', strtotime($array_input['tgl_akhir'])),
                'path_file_ori'   => config_item('file_ina_ori_import').$filename,
                'path_file_modif' => config_item('file_ina_modif_exp').$filename,
                'email_ke'        => $array_input['kirim_ke'],
                'cc'              => $array_input['cc'],
                'subjek'          => $array_input['subject'],
                'isi_email'       => $array_input['isi_email'],
                'is_active'       => 1
            );

            $kirim_file_id = $this->pengiriman_file_txt_m->add_data($data_kirim);

            $this->load->library('email');
            $config = array(
              'protocol'  => config_item('email_protocol'),
              'smtp_host' => config_item('email_smtp_host'),
              'smtp_port' => config_item('email_smtp_port'),
              'smtp_user' => config_item('email_smtp_user'), // change it to yours
              'smtp_pass' => config_item('email_smtp_pass'), // change it to yours
            );

            // $this->email->initialize($config);
            // $this->email->set_newline("\r\n");  
            // $this->email->from(config_item('email_smtp_user'));
            // $this->email->to($array_input['kirim_ke']);                      
            // $this->email->subject($array_input['subject']);
            // $this->email->message($array_input['isi_email']);
            // $this->email->attach($file);

            // if($this->email->send())
            // {
            //     $flashdata = array(
            //         "type"     => "success",
            //         "msg"      => translate("File TXT berhasil dikirim", $this->session->userdata("language")),
            //         "msgTitle" => translate("Success", $this->session->userdata("language"))    
            //         );
            //     $this->session->set_flashdata($flashdata);
            // }

            redirect('klaim/kirim_file_txt');

        }
    }


    public function import_file()
    {
        if($this->input->is_ajax_request())
        {
            $filename_sep = $this->input->post('filename_sep');
            $filename_ina = $this->input->post('filename_ina');

            $tgl_awal = $this->input->post('tgl_awal');
            $tgl_awal = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = $this->input->post('tgl_akhir');
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));

            $this->laporan_sep_file_m->truncate_table();
            $this->laporan_ina_file_m->truncate_table();

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Gagal mengimport file', $this->session->userdata('language'));

            $import_sep = $this->laporan_sep_file_m->import_file($filename_sep);
            $import_ina = $this->laporan_ina_file_m->import_file($filename_ina);

            if($import_sep == true && $import_ina == true)
            {
                $data_tindakan_hd = $this->tindakan_hd_history_m->get_data_tindakan_bpjs($tgl_awal, $tgl_akhir)->result_array();
                $data_tindakan_hd_double = $this->tindakan_hd_history_m->get_data_tindakan_double($tgl_awal, $tgl_akhir)->result_array();
                $data_laporan_sep = $this->laporan_sep_file_m->get_all()->result_array();
                $data_laporan_sep_double = $this->laporan_sep_file_m->get_data_sep_double($tgl_awal, $tgl_akhir)->result_array();
                $data_laporan_sep_tanpa_ina = $this->laporan_sep_file_m->get_data_sep_tanpa_ina($tgl_awal, $tgl_akhir)->result_array();
                $data_laporan_ina = $this->laporan_ina_file_m->get_all()->result_array();
                $data_laporan_ina_double = $this->laporan_ina_file_m->get_data_ina_double($tgl_awal, $tgl_akhir)->result_array();
                $data_laporan_ina_tanpa_sep = $this->laporan_ina_file_m->get_data_ina_tanpa_sep($tgl_awal, $tgl_akhir)->result_array();

                $response->count_hd = count($data_tindakan_hd);
                $response->count_hd_double = count($data_tindakan_hd_double);
                $response->count_sep = count($data_laporan_sep);
                $response->count_sep_double = count($data_laporan_sep_double);
                $response->count_sep_tanpa_ina = count($data_laporan_sep_tanpa_ina);
                $response->count_ina = (count($data_laporan_ina)-2);
                $response->count_ina_double = count($data_laporan_ina_double);
                $response->count_ina_tanpa_sep = count($data_laporan_ina_tanpa_sep);

                $response->success = true;
                $response->msg = translate('Sukses mengimport file', $this->session->userdata('language'));
            }

            echo json_encode($response);
        }
    }

    public function update_inacbg()
    {
        if($this->input->is_ajax_request())
        {
            $array_input = $this->input->post();
            // foreach ($array_input['inacbg'] as $inacbg) 
            // {
            //     $response = new stdClass;
            //     $response->success = false;
            //     $response->msg = translate('Gagal mengubah file txt', $this->session->userdata('language'));

            //     if(isset($inacbg['check']))
            //     {
            //         $data['SEP'] = $inacbg['nosep'].'^'.$inacbg['penjaminsep'];

            //         $where = array(
            //             'Norm'   => $inacbg['norm'],
            //             'Tglmsk' => $inacbg['tgl']
            //         );

            //         $update = $this->inacbg_modif_m->update_data($data,$where);
            //     }
            // }

            if($update)
            {
                $response->success = true;
                $response->msg = translate('Sukses mengubah file txt', $this->session->userdata('language'));
            }

            echo json_encode($response);
        }
    }
   

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */