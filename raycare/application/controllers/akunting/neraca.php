<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Neraca extends MY_Controller {

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
        $this->load->model('akunting/neraca_m');
        $this->load->model('akunting/neraca_detail_m'); 
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/akunting/neraca/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Neraca', $this->session->userdata('language')), 
            'header'         => translate('Neraca', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/neraca/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function add()
    {
        $assets = array();
        $config = 'assets/akunting/neraca/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $tipe_current = array(1);
        $tipe_fixed = array(2);
        $tipe_liability = array(3,4);
        $tipe_equity = array(5,6);

        $akun_current_asset = $this->akun_m->get_akun_by_tipe($tipe_current)->result_array();
        $akun_fixed_asset = $this->akun_m->get_akun_by_tipe($tipe_fixed)->result_array();
        $akun_liability = $this->akun_m->get_akun_by_tipe($tipe_liability)->result_array();
        $akun_equity = $this->akun_m->get_akun_by_tipe($tipe_equity)->result_array();
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Buat Neraca', $this->session->userdata('language')), 
            'header'         => translate('Buat Neraca', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/neraca/add',
            'akun_current_asset'    => $akun_current_asset,
            'akun_fixed_asset'    => $akun_fixed_asset,
            'akun_liability'    => $akun_liability,
            'akun_equity'    => $akun_equity,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $assets = array();
        $config = 'assets/akunting/neraca/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $form_data = $this->neraca_m->get_by(array('id' => $id), true);

        $tipe_current = 1;
        $tipe_fixed = 2;
        $tipe_liability = 3;
        $tipe_equity = 4;

        $akun_current_asset = $this->neraca_detail_m->get_item_neraca($id,$tipe_current)->result_array();
        $akun_fixed_asset = $this->neraca_detail_m->get_item_neraca($id,$tipe_fixed)->result_array();
        $akun_liability = $this->neraca_detail_m->get_item_neraca($id,$tipe_liability)->result_array();
        $akun_equity = $this->neraca_detail_m->get_item_neraca($id,$tipe_equity)->result_array();
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Edit Neraca', $this->session->userdata('language')), 
            'header'         => translate('Edit Neraca', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/neraca/edit',
            'form_data'     => object_to_array($form_data),
            'akun_current_asset'    => $akun_current_asset,
            'akun_fixed_asset'    => $akun_fixed_asset,
            'akun_liability'    => $akun_liability,
            'akun_equity'    => $akun_equity,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/akunting/neraca/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $form_data = $this->neraca_m->get_by(array('id' => $id), true);

        $tipe_current = 1;
        $tipe_fixed = 2;
        $tipe_liability = 3;
        $tipe_equity = 4;

        $akun_current_asset = $this->neraca_detail_m->get_item_neraca($id,$tipe_current)->result_array();
        $akun_fixed_asset = $this->neraca_detail_m->get_item_neraca($id,$tipe_fixed)->result_array();
        $akun_liability = $this->neraca_detail_m->get_item_neraca($id,$tipe_liability)->result_array();
        $akun_equity = $this->neraca_detail_m->get_item_neraca($id,$tipe_equity)->result_array();
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('View Neraca', $this->session->userdata('language')), 
            'header'         => translate('View Neraca', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/neraca/view',
            'form_data'     => object_to_array($form_data),
            'akun_current_asset'    => $akun_current_asset,
            'akun_fixed_asset'    => $akun_fixed_asset,
            'akun_liability'    => $akun_liability,
            'akun_equity'    => $akun_equity,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function cetak_neraca($id)
    {
        
        $this->load->library('mpdf/mpdf.php');

        $form_data = $this->neraca_m->get_by(array('id' => $id), true);

        $tipe_current = 1;
        $tipe_fixed = 2;
        $tipe_liability = 3;
        $tipe_equity = 4;

        $akun_current_asset = $this->neraca_detail_m->get_item_neraca($id,$tipe_current)->result_array();
        $akun_fixed_asset = $this->neraca_detail_m->get_item_neraca($id,$tipe_fixed)->result_array();
        $akun_liability = $this->neraca_detail_m->get_item_neraca($id,$tipe_liability)->result_array();
        $akun_equity = $this->neraca_detail_m->get_item_neraca($id,$tipe_equity)->result_array();
        
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
        $mpdf->writeHTML($this->load->view('akunting/neraca/cetak_neraca', $data, true));

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

        $result = $this->neraca_m->get_datatable();

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

            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" class="btn blue-chambray edit" id="btn_edit_'.$i.'" href="'.base_url().'akunting/neraca/edit/'.$row['id'].'"><i class="fa fa-edit"></i></a>
            <a title="'.translate('Edit', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'akunting/neraca/view/'.$row['id'].'"><i class="fa fa-search"></i></a>
            <a title="'.translate('Cetak', $this->session->userdata('language')).'" class="btn default hidden" target="_blank" href="'.base_url().'akunting/neraca/cetak_neraca/'.$row['id'].'"><i class="fa fa-print"></i></a>';


            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-left inline-button-table">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-right">'.$row['nomor'].'</div>',
                '<div class="text-right">'.formatrupiah($row['total_aktiva']).'</div>',
                '<div class="text-right">'.formatrupiah($row['total_pasiva']).'</div>',
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
            $last_id       = $this->neraca_m->get_max_id_neraca()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'BS-'.date('m').'-'.date('Y').'-%04d';
            $id_neraca         = sprintf($format_id, $last_id, 4);


            $month = date('mY', strtotime($array_input['tanggal']));

            $last_nomor       = $this->neraca_m->get_max_nomor_neraca($month)->result_array();
            $last_nomor       = intval($last_nomor[0]['max_nomor'])+1;

            $format_nomor     = 'BS#'.$month.'#%02d';
            $nomor_neraca         = sprintf($format_nomor, $last_nomor, 2);

            $data_neraca = array(
                'id'            => $id_neraca,
                'nomor'            => $nomor_neraca,
                'tanggal'       => date('Y-m-d', strtotime($array_input['tanggal'])),
                'total_aktiva'  => $array_input['total_aktiva'],
                'total_pasiva'  => $array_input['total_pasiva'],
                'is_active'     => 1,
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'  => date('Y-m-d H:i:s'),
            );

            $neraca = $this->neraca_m->add_data($data_neraca);

            foreach ($array_input['akun_current'] as $key => $current) {
                $last_id_detail       = $this->neraca_detail_m->get_max_id_neraca_detail()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'BSD-'.date('m').'-'.date('Y').'-%04d';
                $id_neraca_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_neraca_detail = array(
                    'id'            => $id_neraca_detail,
                    'neraca_id'     => $id_neraca,
                    'tipe'          => 1,
                    'akun_id'       => $current['id'],
                    'parent'       => $current['parent'],
                    'nominal'       => $current['nominal'],
                    'is_active'     => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s'),
                );

                $neraca_detail = $this->neraca_detail_m->add_data($data_neraca_detail);
            }

            foreach ($array_input['akun_fixed'] as $key => $fixed) {
                $last_id_detail       = $this->neraca_detail_m->get_max_id_neraca_detail()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'BSD-'.date('m').'-'.date('Y').'-%04d';
                $id_neraca_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_neraca_detail = array(
                    'id'            => $id_neraca_detail,
                    'neraca_id'     => $id_neraca,
                    'tipe'          => 2,
                    'akun_id'       => $fixed['id'],
                    'parent'       => $fixed['parent'],
                    'nominal'       => $fixed['nominal'],
                    'is_active'     => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s'),
                );

                $neraca_detail = $this->neraca_detail_m->add_data($data_neraca_detail);
            }

            foreach ($array_input['akun_liability'] as $key => $liability) {
                $last_id_detail       = $this->neraca_detail_m->get_max_id_neraca_detail()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'BSD-'.date('m').'-'.date('Y').'-%04d';
                $id_neraca_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_neraca_detail = array(
                    'id'            => $id_neraca_detail,
                    'neraca_id'     => $id_neraca,
                    'tipe'          => 3,
                    'akun_id'       => $liability['id'],
                    'parent'       => $liability['parent'],
                    'nominal'       => $liability['nominal'],
                    'is_active'     => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s'),
                );

                $neraca_detail = $this->neraca_detail_m->add_data($data_neraca_detail);
            }

            foreach ($array_input['akun_equity'] as $key => $equity) {
                $last_id_detail       = $this->neraca_detail_m->get_max_id_neraca_detail()->result_array();
                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                
                $format_id_detail     = 'BSD-'.date('m').'-'.date('Y').'-%04d';
                $id_neraca_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                $data_neraca_detail = array(
                    'id'            => $id_neraca_detail,
                    'neraca_id'     => $id_neraca,
                    'tipe'          => 4,
                    'akun_id'       => $equity['id'],
                    'parent'       => $equity['parent'],
                    'nominal'       => $equity['nominal'],
                    'is_active'     => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s'),
                );

                $neraca_detail = $this->neraca_detail_m->add_data($data_neraca_detail);
            }

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Neraca berhasil ditambahkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }if($array_input['command'] == 'edit'){
            
            $id_neraca = $array_input['id'];

            $data_neraca = array(
                'tanggal'       => date('Y-m-d', strtotime($array_input['tanggal'])),
                'total_aktiva'  => $array_input['total_aktiva'],
                'total_pasiva'  => $array_input['total_pasiva'],
                'is_active'     => 1,
                'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => date('Y-m-d H:i:s'),
            );

            $neraca = $this->neraca_m->edit_data($data_neraca, $id_neraca);

            foreach ($array_input['akun_current'] as $key => $current) {

                $data_neraca_detail = array(
                    'nominal'       => $current['nominal'],
                    'is_active'     => 1,
                    'modified_by'    => $this->session->userdata('user_id'),
                    'modified_date'  => date('Y-m-d H:i:s'),
                );

                $neraca_detail = $this->neraca_detail_m->edit_data($data_neraca_detail, $current['detail_id']);
            }

            foreach ($array_input['akun_fixed'] as $key => $fixed) {
                $data_neraca_detail = array(
                    'nominal'       => $fixed['nominal'],
                    'is_active'     => 1,
                    'modified_by'    => $this->session->userdata('user_id'),
                    'modified_date'  => date('Y-m-d H:i:s'),
                );

                $neraca_detail = $this->neraca_detail_m->edit_data($data_neraca_detail, $fixed['detail_id']);
            }

            foreach ($array_input['akun_liability'] as $key => $liability) {
                $data_neraca_detail = array(
                    'nominal'       => $liability['nominal'],
                    'is_active'     => 1,
                    'modified_by'    => $this->session->userdata('user_id'),
                    'modified_date'  => date('Y-m-d H:i:s'),
                );

                $neraca_detail = $this->neraca_detail_m->edit_data($data_neraca_detail, $liability['detail_id']);
            }

            foreach ($array_input['akun_equity'] as $key => $equity) {
                $data_neraca_detail = array(
                    'nominal'       => $equity['nominal'],
                    'is_active'     => 1,
                    'modified_by'    => $this->session->userdata('user_id'),
                    'modified_date'  => date('Y-m-d H:i:s'),
                );

                $neraca_detail = $this->neraca_detail_m->edit_data($data_neraca_detail, $equity['detail_id']);
            }

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Neraca berhasil diubah.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        redirect('akunting/neraca');
            
    }

}

/* End of file antrian_tensi_bb.php */
/* Location: ./application/controllers/akunting/neraca.php */