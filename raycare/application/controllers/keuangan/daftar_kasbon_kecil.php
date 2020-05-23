<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kasbon_kecil extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '14bfc3f8bbe08a7812aef6e2a7774ea5';                  // untuk check bit_access

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

       
        $this->load->model('keuangan/daftar_kasbon/permintaan_biaya_m');

        $this->load->model('master/bank_m');
        $this->load->model('master/user_level_persetujuan_m');

        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/daftar_kasbon_kecil/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Daftar Kasbon', $this->session->userdata('language')), 
            'header'         => translate('Daftar Kasbon', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/daftar_kasbon_kecil/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        // $replace = str_replace('%20', ' ', $date);
        // $bulan = date('Y-m', strtotime($replace));

        $result = $this->permintaan_biaya_m->get_datatable_kecil();
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $count = count($records->result_array());

        // die_dump($count);

        $i=0;
        $total_debit = 0;
        $total_kredit = 0;
        $total_saldo = 0;
        $input_debit = '';
        $input_kredit = '';
        $input_saldo = '';
        $status = '';

        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            if($row['status'] == 3)
            {
                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Diproses</span></div>';
            
            } elseif($row['status'] == 5){

                $status = '<div class="text-center"><span class="label label-md label-success">Sudah diproses kasir</span></div>';

            } elseif($row['status'] == 6){

                $status = '<div class="text-center"><span class="label label-md label-danger">Sudah Dibuat Permintaan</span></div>';
            } elseif($row['status'] == 7){

                $status = '<div class="text-center"><span class="label label-md label-danger">Proses Persetujuan</span></div>';
            } elseif($row['status'] == 8){

                $status = '<div class="text-center"><span class="label label-md label-danger">Disetujui</span></div>';
            } elseif($row['status'] == 9){

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            } elseif($row['status'] == 10){

                $status = '<div class="text-center"><span class="label label-md label-danger">Menunggu Kwitansi</span></div>';
            }



            $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/daftar_kasbon_kecil/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';
            $tipe = '';

            if($row['tipe'] == 1){
                $tipe = 'Kasbon';
            }if($row['tipe'] == 2){
                $tipe = 'Reimburse / Pencairan';
            }

                $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/daftar_kasbon_kecil/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';


            // PopOver Notes
            $notes = explode("\n", $row['keperluan']);
            $notes    = implode('</br>', $notes);

            $output['aaData'][] = array(
                // '<div class="text-left">'.$row['nominal'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal_setujui']).'</div>',
                '<div class="text-left">'.$notes.'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }


    public function view($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("View Kasbon", $this->session->userdata("language")), 
            'header'         => translate("View Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/permintaan_biaya/view',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


}

/* End of file permintaan_biaya.php */
/* Location: ./application/controllers/keuangan/permintaan_biaya.php */