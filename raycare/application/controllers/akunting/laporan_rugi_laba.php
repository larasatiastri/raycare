<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_rugi_laba extends MY_Controller {

    // protected $menu_id = '7935f149dd9c1c8d88ce7296c2fdcd4b';                  // untuk check bit_access
    protected $menu_id = '71c391066460bff07a09b843a2971c72';                  // untuk check bit_access
    
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

        $this->load->model('akunting/akun_m');
        $this->load->model('akunting/laporan_rugi_laba_m');
        $this->load->model('akunting/laporan_rugi_laba_detail_m');
        
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/akunting/laporan_rugi_laba/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Laporan Rugi Laba', $this->session->userdata('language')), 
            'header'         => translate('Laporan Rugi Laba', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/laporan_rugi_laba/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function add()
    {
        $assets = array();
        $config = 'assets/akunting/laporan_rugi_laba/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $tipe_pendapatan = array(7);
        $tipe_hpp = array(8);
        $tipe_beban = array(9);
        $tipe_pendapatan_lain = array(10);
        $tipe_beban_lain = array(11);

        $akun_pendapatan = $this->akun_m->get_akun_by_tipe($tipe_pendapatan)->result_array();
        $akun_hpp = $this->akun_m->get_akun_by_tipe($tipe_hpp)->result_array();
        $akun_beban = $this->akun_m->get_akun_by_tipe($tipe_beban)->result_array();
        $akun_pendapatan_lain = $this->akun_m->get_akun_by_tipe($tipe_pendapatan_lain)->result_array();
        $akun_beban_lain = $this->akun_m->get_akun_by_tipe($tipe_beban_lain)->result_array();
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Buat Laporan Rugi Laba', $this->session->userdata('language')), 
            'header'         => translate('Buat Laporan Rugi Laba', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/laporan_rugi_laba/add',
            'akun_pendapatan'    => $akun_pendapatan,
            'akun_hpp'    => $akun_hpp,
            'akun_beban'    => $akun_beban,
            'akun_pendapatan_lain'    => $akun_pendapatan_lain,
            'akun_beban_lain'    => $akun_beban_lain,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $assets = array();
        $config = 'assets/akunting/laporan_rugi_laba/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $form_data = $this->laporan_rugi_laba_m->get_by(array('id' => $id), true);

        $tipe_pendapatan = 7;
        $tipe_hpp = 8;
        $tipe_beban = 9;
        $tipe_pendapatan_lain = 10;
        $tipe_beban_lain = 11;

        $akun_pendapatan = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_pendapatan)->result_array();
        $akun_hpp = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_hpp)->result_array();
        $akun_beban = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_beban)->result_array();
        $akun_pendapatan_lain = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_pendapatan_lain)->result_array();
        $akun_beban_lain = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_beban_lain)->result_array();
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Edit Laporan Rugi Laba', $this->session->userdata('language')), 
            'header'         => translate('Edit Laporan Rugi Laba', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/laporan_rugi_laba/edit',
            'form_data'     => object_to_array($form_data),
            'akun_pendapatan'    => $akun_pendapatan,
            'akun_hpp'    => $akun_hpp,
            'akun_beban'    => $akun_beban,
            'akun_pendapatan_lain'    => $akun_pendapatan_lain,
            'akun_beban_lain'    => $akun_beban_lain,
            'pk_value'  => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/akunting/laporan_rugi_laba/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $form_data = $this->laporan_rugi_laba_m->get_by(array('id' => $id), true);

        $tipe_pendapatan = 7;
        $tipe_hpp = 8;
        $tipe_beban = 9;
        $tipe_pendapatan_lain = 10;
        $tipe_beban_lain = 11;

        $akun_pendapatan = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_pendapatan)->result_array();
        $akun_hpp = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_hpp)->result_array();
        $akun_beban = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_beban)->result_array();
        $akun_pendapatan_lain = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_pendapatan_lain)->result_array();
        $akun_beban_lain = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_beban_lain)->result_array();
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('View Laporan Rugi Laba', $this->session->userdata('language')), 
            'header'         => translate('View Laporan Rugi Laba', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/laporan_rugi_laba/view',
            'form_data'     => object_to_array($form_data),
            'akun_pendapatan'    => $akun_pendapatan,
            'akun_hpp'    => $akun_hpp,
            'akun_beban'    => $akun_beban,
            'akun_pendapatan_lain'    => $akun_pendapatan_lain,
            'akun_beban_lain'    => $akun_beban_lain,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function cetak_neraca($id)
    {
        
        $this->load->library('mpdf/mpdf.php');

        $form_data = $this->laporan_rugi_laba_m->get_by(array('id' => $id), true);

        $tipe_pendapatan = 1;
        $tipe_hpp = 2;
        $tipe_beban = 3;
        $tipe_equity = 4;

        $akun_current_asset = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_pendapatan)->result_array();
        $akun_fixed_asset = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_hpp)->result_array();
        $akun_liability = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_beban)->result_array();
        $akun_equity = $this->laporan_rugi_laba_detail_m->get_item_laporan_rugi_laba($id,$tipe_equity)->result_array();
        
        $data = array(
            'form_data'     => object_to_array($form_data),
            'akun_current_asset'    => $akun_current_asset,
            'akun_fixed_asset'    => $akun_fixed_asset,
            'akun_liability'    => $akun_liability,
            'akun_equity'    => $akun_equity,
        );
        
        $mpdf = new mPDF('utf-8','A4', 1, 'L', 10, 10, 10, 10, 0, 5);
        $stylesheets = file_get_contents(base_url().'assets/metronic/global/plugins/bootstrap/css/bootstrap.css');
        $mpdf->writeHTML($stylesheets, 1);
        $mpdf->writeHTML($this->load->view('akunting/laporan_rugi_laba/cetak_neraca', $data, true));

        $mpdf->Output('neraca_'.date('Y-m-d', strtotime($form_data->tanggal)).'.pdf', 'I');
        $mpdf->Output($path_dokumen.'/'.$filename, 'F');
        // Load the view
    }

    

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        

        $result = $this->laporan_rugi_laba_m->get_datatable();

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

            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" class="btn blue-chambray edit" id="btn_edit_'.$i.'" href="'.base_url().'akunting/laporan_rugi_laba/edit/'.$row['id'].'"><i class="fa fa-edit"></i></a>
            <a title="'.translate('Edit', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'akunting/laporan_rugi_laba/view/'.$row['id'].'"><i class="fa fa-search"></i></a>
            <a title="'.translate('Cetak', $this->session->userdata('language')).'" class="btn default hidden" target="_blank" href="'.base_url().'akunting/laporan_rugi_laba/cetak_neraca/'.$row['id'].'"><i class="fa fa-print"></i></a>';


            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-left inline-button-table">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-right">'.$row['nomor'].'</div>',
                '<div class="text-right">'.formatrupiah($row['laba_kotor']).'</div>',
                '<div class="text-right">'.formatrupiah($row['laba_rugi_bersih_sebelum_pajak']).'</div>',
                '<div class="text-right">'.formatrupiah($row['pajak_penghasilan_badan']).'</div>',
                '<div class="text-right">'.formatrupiah($row['laba_rugi_bersih_setelah_pajak']).'</div>',
                '<div class="text-center inline-button-table">'.$row['nama_user'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

   

    public function save()
    {
       
        $array_input = $this->input->post();

        if($array_input['command'] == 'add'){

            $last_id       = $this->laporan_rugi_laba_m->get_max_id()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'PL-'.date('m').'-'.date('Y').'-%04d';
            $id_labrug         = sprintf($format_id, $last_id, 4);


            $month = date('mY', strtotime($array_input['tanggal']));

            $last_nomor       = $this->laporan_rugi_laba_m->get_max_nomor($month)->result_array();
            $last_nomor       = intval($last_nomor[0]['max_nomor'])+1;

            $format_nomor     = 'PL#'.$month.'#%02d';
            $nomor_labrug         = sprintf($format_nomor, $last_nomor, 2);

            $data = array(
                'id'                    => $id_labrug,
                'nomor'                 => $nomor_labrug,
                'tanggal'               => date('Y-m-d', strtotime($array_input['tanggal'])),
                'total_pendapatan'      => $array_input['total_pendapatan'],
                'prosentase_pendapatan' => $array_input['prosentase_total_pendapatan'],
                'total_hpp'             => $array_input['total_hpp'],
                'prosentase_hpp'        => $array_input['prosentase_total_hpp'],
                'laba_kotor'        => $array_input['laba_kotor'],
                'prosentase_laba_kotor'        => $array_input['prosentase_laba_kotor'],
                'total_beban'           => $array_input['total_beban_operasional'],
                'prosentase_beban'      => $array_input['prosentase_total_beban_operasional'],
                'total_pendapatan_lain' => $array_input['total_pendapatan_lain'],
                'prosentase_pendapatan_lain' => $array_input['prosentase_total_pendapatan_lain'],
                'total_beban_lain' => $array_input['total_beban_lain'],
                'prosentase_beban_lain' => $array_input['prosentase_total_beban_lain'],
                'laba_rugi_bersih_sebelum_pajak' => $array_input['labrug_sebelum_pajak'],
                'prosentase_labrug_sebelum_pajak' => $array_input['prosentase_labrug_sebelum_pajak'],
                'pajak_penghasilan_badan' => $array_input['pajak_penghasilan_badan'],
                'prosentase_pajak_penghasilan_badan' => $array_input['prosentase_pajak_penghasilan_badan'],
                'prosentase_pajak_penghasilan_badan' => $array_input['prosentase_pajak_penghasilan_badan'],
                'laba_rugi_bersih_setelah_pajak' => $array_input['labrug_setelah_pajak'],
                'prosentase_labrug_setelah_pajak' => $array_input['prosentase_labrug_setelah_pajak'],
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'  => date('Y-m-d H:i:s')
            );

            $save_laporan_labrug = $this->laporan_rugi_laba_m->add_data($data);

            foreach ($array_input['akun_pendapatan'] as $key => $pendapatan) {
                
                $last_id_detail       = $this->laporan_rugi_laba_detail_m->get_max_id()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'PLD-'.date('m').'-'.date('Y').'-%04d';
                $id_labrug_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_detail = array(
                    'id'    => $id_labrug_detail,
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $pendapatan['akun_tipe'],
                    'akun_id'    => $pendapatan['id'],
                    'akun_nama'    => $pendapatan['nama'],
                    'nominal'    => $pendapatan['nominal_pendapatan'],
                    'prosentase'    => $pendapatan['prosentase'],
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s')
                );

                $save_detail_pendapatan = $this->laporan_rugi_laba_detail_m->add_data($data_detail);

            }

            foreach ($array_input['akun_hpp'] as $key => $hpp) {
                
                $last_id_detail       = $this->laporan_rugi_laba_detail_m->get_max_id()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'PLD-'.date('m').'-'.date('Y').'-%04d';
                $id_labrug_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_detail = array(
                    'id'    => $id_labrug_detail,
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $hpp['akun_tipe'],
                    'akun_id'    => $hpp['id'],
                    'akun_nama'    => $hpp['nama'],
                    'nominal'    => $hpp['nominal_hpp'],
                    'prosentase'    => $hpp['prosentase'],
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s')
                );

                $save_detail_hpp = $this->laporan_rugi_laba_detail_m->add_data($data_detail);
            }

            foreach ($array_input['akun_beban'] as $key => $beban) {
                
                $last_id_detail       = $this->laporan_rugi_laba_detail_m->get_max_id()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'PLD-'.date('m').'-'.date('Y').'-%04d';
                $id_labrug_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_detail = array(
                    'id'    => $id_labrug_detail,
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $beban['akun_tipe'],
                    'akun_id'    => $beban['id'],
                    'akun_nama'    => $beban['nama'],
                    'nominal'    => $beban['nominal_beban'],
                    'prosentase'    => $beban['prosentase'],
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s')
                );

                $save_detail_beban = $this->laporan_rugi_laba_detail_m->add_data($data_detail);
            }
            foreach ($array_input['akun_pendapatan_lain'] as $key => $pendapatan_lain) {
                
                $last_id_detail       = $this->laporan_rugi_laba_detail_m->get_max_id()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'PLD-'.date('m').'-'.date('Y').'-%04d';
                $id_labrug_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_detail = array(
                    'id'    => $id_labrug_detail,
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $pendapatan_lain['akun_tipe'],
                    'akun_id'    => $pendapatan_lain['id'],
                    'akun_nama'    => $pendapatan_lain['nama'],
                    'nominal'    => $pendapatan_lain['nominal_pendapatan_lain'],
                    'prosentase'    => $pendapatan_lain['prosentase'],
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s')
                );

                $save_detail_pendapatan_lain = $this->laporan_rugi_laba_detail_m->add_data($data_detail);
            }

            foreach ($array_input['akun_beban_lain'] as $key => $beban_lain) {
                
                $last_id_detail       = $this->laporan_rugi_laba_detail_m->get_max_id()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'PLD-'.date('m').'-'.date('Y').'-%04d';
                $id_labrug_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_detail = array(
                    'id'    => $id_labrug_detail,
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $beban_lain['akun_tipe'],
                    'akun_id'    => $beban_lain['id'],
                    'akun_nama'    => $beban_lain['nama'],
                    'nominal'    => $beban_lain['nominal_beban_lain'],
                    'prosentase'    => $beban_lain['prosentase'],
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s')
                );

                $save_detail_beban_lain = $this->laporan_rugi_laba_detail_m->add_data($data_detail);
            }

            
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Laporan Rugi Laba berhasil ditambahkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }if($array_input['command'] == 'edit'){
            
            $data = array(
                'tanggal'               => date('Y-m-d', strtotime($array_input['tanggal'])),
                'total_pendapatan'      => $array_input['total_pendapatan'],
                'prosentase_pendapatan' => $array_input['prosentase_total_pendapatan'],
                'total_hpp'             => $array_input['total_hpp'],
                'prosentase_hpp'        => $array_input['prosentase_total_hpp'],
                'laba_kotor'        => $array_input['laba_kotor'],
                'prosentase_laba_kotor'        => $array_input['prosentase_laba_kotor'],
                'total_beban'           => $array_input['total_beban_operasional'],
                'prosentase_beban'      => $array_input['prosentase_total_beban_operasional'],
                'total_pendapatan_lain' => $array_input['total_pendapatan_lain'],
                'prosentase_pendapatan_lain' => $array_input['prosentase_total_pendapatan_lain'],
                'total_beban_lain' => $array_input['total_beban_lain'],
                'prosentase_beban_lain' => $array_input['prosentase_total_beban_lain'],
                'laba_rugi_bersih_sebelum_pajak' => $array_input['labrug_sebelum_pajak'],
                'prosentase_labrug_sebelum_pajak' => $array_input['prosentase_labrug_sebelum_pajak'],
                'pajak_penghasilan_badan' => $array_input['pajak_penghasilan_badan'],
                'prosentase_pajak_penghasilan_badan' => $array_input['prosentase_pajak_penghasilan_badan'],
                'prosentase_pajak_penghasilan_badan' => $array_input['prosentase_pajak_penghasilan_badan'],
                'laba_rugi_bersih_setelah_pajak' => $array_input['labrug_setelah_pajak'],
                'prosentase_labrug_setelah_pajak' => $array_input['prosentase_labrug_setelah_pajak'],
                'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => date('Y-m-d H:i:s')
            );

            $edit_laporan_labrug = $this->laporan_rugi_laba_m->edit_data($data, $array_input['id']);

            foreach ($array_input['akun_pendapatan'] as $key => $pendapatan) {
                
                $data_detail = array(
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $pendapatan['akun_tipe'],
                    'akun_id'    => $pendapatan['id'],
                    'akun_nama'    => $pendapatan['nama'],
                    'nominal'    => $pendapatan['nominal_pendapatan'],
                    'prosentase'    => $pendapatan['prosentase'],
                    'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => date('Y-m-d H:i:s')
                );

                $edit_detail_pendapatan = $this->laporan_rugi_laba_detail_m->edit_data($data_detail, $pendapatan['id_detail']);

            }

            foreach ($array_input['akun_hpp'] as $key => $hpp) {

                $data_detail = array(
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $hpp['akun_tipe'],
                    'akun_id'    => $hpp['id'],
                    'akun_nama'    => $hpp['nama'],
                    'nominal'    => $hpp['nominal_hpp'],
                    'prosentase'    => $hpp['prosentase'],
                    'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => date('Y-m-d H:i:s')
                );

                $edit_detail_hpp = $this->laporan_rugi_laba_detail_m->edit_data($data_detail, $hpp['id_detail']);
            }

            foreach ($array_input['akun_beban'] as $key => $beban) {
            
                $data_detail = array(
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $beban['akun_tipe'],
                    'akun_id'    => $beban['id'],
                    'akun_nama'    => $beban['nama'],
                    'nominal'    => $beban['nominal_beban'],
                    'prosentase'    => $beban['prosentase'],
                    'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => date('Y-m-d H:i:s')
                );

                $edit_detail_beban = $this->laporan_rugi_laba_detail_m->edit_data($data_detail,$beban['id_detail']);
            }
            foreach ($array_input['akun_pendapatan_lain'] as $key => $pendapatan_lain) {
            
                $data_detail = array(
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $pendapatan_lain['akun_tipe'],
                    'akun_id'    => $pendapatan_lain['id'],
                    'akun_nama'    => $pendapatan_lain['nama'],
                    'nominal'    => $pendapatan_lain['nominal_pendapatan_lain'],
                    'prosentase'    => $pendapatan_lain['prosentase'],
                    'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => date('Y-m-d H:i:s')
                );

                $edit_detail_pendapatan_lain = $this->laporan_rugi_laba_detail_m->edit_data($data_detail, $pendapatan_lain['id_detail']);
            }

            foreach ($array_input['akun_beban_lain'] as $key => $beban_lain) {
             
                $data_detail = array(
                    'laporan_rugi_laba_id'    => $id_labrug,
                    'kategori_id'    => $beban_lain['akun_tipe'],
                    'akun_id'    => $beban_lain['id'],
                    'akun_nama'    => $beban_lain['nama'],
                    'nominal'    => $beban_lain['nominal_beban_lain'],
                    'prosentase'    => $beban_lain['prosentase'],
                    'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => date('Y-m-d H:i:s')
                );

                $edit_detail_beban_lain = $this->laporan_rugi_laba_detail_m->edit_data($data_detail, $beban_lain['id_detail']);
            }
            
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Laporan Rugi Laba berhasil diubah.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        redirect('akunting/laporan_rugi_laba');
            
    }

}

/* End of file antrian_tensi_bb.php */
/* Location: ./application/controllers/akunting/neraca.php */