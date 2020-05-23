<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengeluaran_kas_eksternal extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '2a0485d5e9dea33ce5ce49f680c7cc94';                  // untuk check bit_access

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

       
        $this->load->model('keuangan/pengeluaran_kas_eksternal/pengeluaran_kas_eksternal_m');
        $this->load->model('keuangan/pengeluaran_kas_eksternal/pengeluaran_kas_eksternal_bon_m');
        $this->load->model('kasir/arus_kas_eksternal/external_arus_kas_m');
     
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/pengeluaran_kas_eksternal/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengeluaran Kas Eksternal', $this->session->userdata('language')), 
            'header'         => translate('Pengeluaran Kas Eksternal', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengeluaran_kas_eksternal/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {

        $result = $this->pengeluaran_kas_eksternal_m->get_datatable();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $count = count($records->result_array());
    
        $i=0;
        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            

            $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pengeluaran_kas_eksternal/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

            // PopOver Notes
            $notes    = $row['keperluan'];
        
            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$notes.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }
    
    public function add()
    {
        $assets = array();
        $assets_config = 'assets/keuangan/pengeluaran_kas_eksternal/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Add Pengeluaran Kas Eksternal", $this->session->userdata("language")), 
            'header'         => translate("Add Pengeluaran Kas Eksternal", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengeluaran_kas_eksternal/add',
            'flag'           => 'add',
            'pk_value'       => '',
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/pengeluaran_kas_eksternal/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->pengeluaran_kas_eksternal_m->get($id);
        $form_data_bon = $this->pengeluaran_kas_eksternal_bon_m->get_by(array('pengeluaran_kas_eksternal_id' => $id));
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("View Pengeluaran Kas Eksternal", $this->session->userdata("language")), 
            'header'         => translate("View Pengeluaran Kas Eksternal", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengeluaran_kas_eksternal/view',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            'form_data_bon'  => object_to_array($form_data_bon)
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    

    
    public function save()
    {
        $array_input = $this->input->post();
        // die(dump($array_input));
        $command = $this->input->post('command');

        if($command === 'add')
        {
            $data = array(
                'diminta_oleh_id' => $array_input['id_ref_pasien'],
                'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'nominal'         => $array_input['nominal'],
                'keperluan'       => $array_input['keperluan'],         
                'is_active'       => 1,
            );

            $pengeluaran_kas_eksternal_id = $this->pengeluaran_kas_eksternal_m->save($data);

            $keterangan = $array_input['keperluan'];

            if($array_input['bon']){
                foreach ($array_input['bon'] as $key => $bon) {
                    if($bon['total_bon'] != ''){

                        if($bon['url'] != ''){
                            $path_dokumen = './assets/mb/pages/keuangan/pengeluaran_kas_eksternal/images/'.$pengeluaran_kas_eksternal_id;
                            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                            $temp_filename = $bon['url'];

                            $convtofile = new SplFileInfo($temp_filename);
                            $extenstion = ".".$convtofile->getExtension();

                            $new_filename = $bon['url'];
                            $real_file = $pengeluaran_kas_eksternal_id.'/'.$new_filename;

                            copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon_eksternal').$real_file);
                        }

                        $data_bon = array(
                            'pengeluaran_kas_eksternal_id' => $pengeluaran_kas_eksternal_id,
                            'no_bon'              => $bon['no_bon'],
                            'total_bon'           => $bon['total_bon'],
                            'keterangan'          => $bon['keterangan'],
                            'tgl_bon'             => date('Y-m-d', strtotime($bon['tanggal'])),
                            'url'                 => $bon['url']
                        );

                        $pengeluaran_kas_eksternal_bon_id = $this->pengeluaran_kas_eksternal_bon_m->save($data_bon);
                    }
                }
            }

            $date = date('Y-m-d', strtotime($array_input['tanggal']));

            $last_saldo = $this->external_arus_kas_m->get_saldo_before($date)->result_array();
            $after_saldo = $this->external_arus_kas_m->get_after_after($date)->result_array();
            
            $saldo_before = 0;
            if(count($last_saldo) != 0){
                $saldo_before = intval($last_saldo[0]['saldo']);
            }

            $data_arus_kas = array(
                'tanggal'      => $date,
                'tipe'         => 5,
                'keterangan'   => rtrim($keterangan,';'),
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'C',
                'rupiah'       => $array_input['nominal'],
                'saldo'        => ($saldo_before - $array_input['nominal']),
                'status'       => 1
            );

            $arus_kas = $this->external_arus_kas_m->save($data_arus_kas);

            if(count($after_saldo) != 0){
                foreach ($after_saldo as $after) {
                    $data_arus_kas_after = array(
                        'saldo'        => ($after['saldo'] - $array_input['nominal']),
                    );

                    $arus_kas = $this->external_arus_kas_m->save($data_arus_kas_after, $after['id']);
                }
            }

            if ($pengeluaran_kas_eksternal_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Pengeluaran Kas Eksternal Ditambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }  

             
        redirect('keuangan/pengeluaran_kas_eksternal');

    }

    public function get_terbilang()
    {
        if($this->input->is_ajax_request()){
            $nominal = $this->input->post('nominal');

            $response = new stdClass;

            $terbilang = terbilang($nominal);

            $response->terbilang = '#'.$terbilang.' Rupiah#';

            die(json_encode($response));
        }
    }
}

/* End of file pengeluaran_kas_eksternal.php */
/* Location: ./application/controllers/keuangan/pengeluaran_kas_eksternal.php */