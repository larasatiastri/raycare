<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses_permintaan_biaya extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '5793dbbbca9c688b4d5d7bdcaf7ddf0b';                  // untuk check bit_access

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

       
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_bon_m');
        $this->load->model('keuangan/proses_permintaan_biaya/persetujuan_permintaan_biaya_m');
        $this->load->model('keuangan/kasir_arus_kas_m');
        $this->load->model('apotik/pembelian/pembelian_m');
        $this->load->model('master/bank_m');
        $this->load->model('master/user_level_persetujuan_m');

        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/proses_permintaan_biaya/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Kasbon', $this->session->userdata('language')), 
            'header'         => translate('Proses Kasbon', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_permintaan_biaya/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/keuangan/proses_permintaan_biaya/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Kasbon', $this->session->userdata('language')), 
            'header'         => translate('History Kasbon', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_permintaan_biaya/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {

        $result = $this->permintaan_biaya_m->get_datatable();

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $count = count($records->result_array());
    
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
            $status = '';

            $action ='';

            if($row['status_revisi'] == 1){
                $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';
            }
            // PopOver Notes
            $notes = explode("\n", $row['keperluan']);
            $notes    = implode('</br>', $notes);
            $tipe = '';

            if($row['tipe'] == 1){
                $tipe = 'Kasbon';
                $action ='<a title="'.translate('Revisi', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_revisi" href="'.base_url().'keuangan/proses_permintaan_biaya/proses_revisi/'.$row['id'].'" class="btn red revisi"><i class="fa fa-undo"></i></a><a title="'.translate('Proses', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_proses_kasbon" href="'.base_url().'keuangan/proses_permintaan_biaya/proses_kasbon/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
                if($row['status'] == 3){
                    $status = '<span class="label label-danger">Menunggu Diproses</span>';
                }if($row['status'] == 11){
                    $status = '<span class="label label-warning">Menunggu Persetujuan Keuangan</span>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';
                }if($row['status'] == 12){
                    $status = '<span class="label label-warning">Proses Persetujuan Keuangan</span>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';
                }if($row['status'] == 13){
                    $status = '<span class="label label-success">Disetujui Keuangan</span>';
                    $action ='<a title="'.translate('Revisi', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_revisi" href="'.base_url().'keuangan/proses_permintaan_biaya/proses_revisi/'.$row['id'].'" class="btn red revisi"><i class="fa fa-undo"></i></a><a title="'.translate('Proses Pencairan', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_pencairan_kasbon" href="'.base_url().'keuangan/proses_permintaan_biaya/proses_pencairan/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
                }if($row['status'] == 15){
                    $status = '<span class="label label-warning">Menunggu Verif Bon</span>';
                    $action ='<a title="'.translate('Revisi', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_revisi" href="'.base_url().'keuangan/proses_permintaan_biaya/proses_revisi/'.$row['id'].'" class="btn red revisi"><i class="fa fa-undo"></i></a><a title="'.translate('Proses Verifikasi', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/proses_verifikasi/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
                }if($row['status'] == 16){
                    $status = '<span class="label label-warning">Menunggu Verifikasi Keuangan</span>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';
                }if($row['status'] == 18 || $row['status'] == 19){
                    $status = '<span class="label label-info">Diverifikasi Keuangan</span>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view_proses/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
                }
            }if($row['tipe'] == 2){
                $tipe = 'Reimburse / Pencairan';
                $action ='<a title="'.translate('Revisi', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_revisi" href="'.base_url().'keuangan/proses_permintaan_biaya/proses_revisi/'.$row['id'].'" class="btn red revisi"><i class="fa fa-undo"></i></a><a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a><a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/proses/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
                 $status = '<span class="label label-danger">Menunggu Diproses</span>';
                if($row['status'] == 16){
                    $status = '<span class="label label-warning">Menunggu Verifikasi Keuangan</span>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view_proses/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';
                }if($row['status'] == 18 || $row['status'] == 19){
                    $status = '<span class="label label-info">Diverifikasi Keuangan</span>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view_proses/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
                }if($row['status'] == 20){
                    $status = '<span class="label label-warning">Menunggu Proses Keuangan</span>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';
                }
            }
            if($row['status_revisi'] == 1){
                $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';
            }

            
        
            $output['aaData'][] = array(
                '<div class="text-center" style="vertical-align:top;">'.date('d M Y', strtotime($effective_date)).'</div>',
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
    public function listing_history()
    {

        $result = $this->permintaan_biaya_m->get_datatable_history();

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

            $action ='<a title="'.translate('View Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_permintaan_biaya/view_proses/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a><a title="'.translate('Print Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/proses_permintaan_biaya/print_voucher/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';

            // PopOver Notes
            $notes    = $row['keperluan'];
            $tipe = '';

            if($row['tipe'] == 1){
                $tipe = 'Kasbon';
            }if($row['tipe'] == 2){
                $tipe = 'Reimburse / Pencairan';
            }

            $total = $row['nominal_setujui'];

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal_setujui']+$row['sisa']).'</div>',
                '<div class="text-left">'.$notes.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function listing_pembelian()
    {
        $result = $this->pembelian_m->get_datatable_belum_bayar();

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
      //  die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action ='<a title="'.translate('Select', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
  
            $output['data'][] = array(
                '<div class="text-center">'.$row['no_po'].'</div>',
                '<div class="text-left">'.$row['nama_sup'].' ['.$row['kode_sup'].']'.'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_pesan'])).'</div>',
                '<div class="text-right">'.formatrupiah($row['grand_total']).'</div>',
               
                '<div class="text-center inline-button-table">'.$action.'</div>'  
            );
        
            $i++;
        }

        echo json_encode($output);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Add Kasbon", $this->session->userdata("language")), 
            'header'         => translate("Add Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_permintaan_biaya/add',
            'flag'           => 'add',
            'pk_value'       => '',
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/view';
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
            'content_view'   => 'keuangan/proses_permintaan_biaya/view',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_proses($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));
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
            'content_view'   => 'keuangan/proses_permintaan_biaya/view_proses',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            'form_data_bon'  => object_to_array($form_data_bon)
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Proses Kasbon", $this->session->userdata("language")), 
            'header'         => translate("Proses Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_permintaan_biaya/proses',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    
    public function proses_kasbon($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);

        $data = array(  
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
        );

        // Load the view
        $this->load->view('keuangan/proses_permintaan_biaya/proses_kasbon', $data);
    }
    public function proses_pencairan($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);

        $data = array(  
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
        );

        // Load the view
        $this->load->view('keuangan/proses_permintaan_biaya/proses_pencairan', $data);
    }

    public function proses_revisi($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);

        $data = array(  
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
        );

        // Load the view
        $this->load->view('keuangan/proses_permintaan_biaya/proses_revisi', $data);
    }

    public function revisi()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Permintaan biaya gagal direvisi', $this->session->userdata('language'));

            $data_biaya = array(
                'status_revisi' => 1,
                'keterangan_revisi' => $array_input['keterangan_tolak'],
            );

            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data_biaya, $array_input['permintaan_biaya_id']);

            if($permintaan_biaya_id){
                $response->success = true;
                $response->msg = translate('Permintaan biaya berhasil direvisi', $this->session->userdata('language'));
            }
            
            die(json_encode($response));
        }
    }

    public function proses_verifikasi($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/proses_verif';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Proses Kasbon", $this->session->userdata("language")), 
            'header'         => translate("Proses Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_permintaan_biaya/proses_verif',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    
    public function save()
    {
        $array_input = $this->input->post();
        $command = $array_input['command'];
        $id = $array_input['id'];
        $level_id = $this->session->userdata('level_id');

        $date = date('Y-m-d');
        $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date,2)->result_array();

        $cek_user_level_persetujuan   = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $level_id, 'tipe_persetujuan' => 10));
        $user_level_persetujuan_array = object_to_array($cek_user_level_persetujuan);

        if($command === 'proses')
        {            
            if(count($user_level_persetujuan_array) == 0){
                $data = array(
                    'status'          => 5,
                    'tanggal_proses'  => $date,
                    'diproses_oleh'   => $this->session->userdata('user_id')
                );
                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

            }elseif(count($user_level_persetujuan_array) != 0){
                if($array_input['tipe'] == 1){
                    $data = array(
                        'status'          => 16,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id')
                    );
                }if($array_input['tipe'] == 2 && $array_input['nominal'] < 1000000){
                    $data = array(
                        'status'          => 16,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id')
                    );
                }if($array_input['tipe'] == 2 && $array_input['nominal'] >= 1000000){
                    $data = array(
                        'status'          => 20,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id')
                    );
                }
                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

                if($array_input['tipe'] == 1){
                    foreach ($user_level_persetujuan_array as $persetujuan) 
                    {
                        $max_id   = '';
                        $maksimum = $this->persetujuan_permintaan_biaya_m->get_max()->row(0);

                        if(count($maksimum) == NULL)
                        {
                            $max_id = 1;
                        }
                        else {
                            $max_id = $maksimum->max_id;
                            $max_id = $max_id + 1;
                        }

                        $data_persetujuan_permintaan_biaya = array(

                            'persetujuan_permintaan_biaya_id' => $max_id,
                            'permintaan_biaya_id'             => $id,
                            'user_level_id'                   => $persetujuan['user_level_menyetujui_id'],
                            'tipe'                            => 3,
                            '`order`'                         => $persetujuan['level_order'],
                            '`status`'                        => 1,
                            'is_active'                       => 1,
                            'created_by'                      => $this->session->userdata('user_id'),
                            'created_date'                    => date('Y-m-d H:i:s'),

                        );

                        $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);
                    }
                }if($array_input['tipe'] == 2 && $array_input['nominal'] < 1000000){
                    foreach ($user_level_persetujuan_array as $persetujuan) 
                    {
                        $max_id   = '';
                        $maksimum = $this->persetujuan_permintaan_biaya_m->get_max()->row(0);

                        if(count($maksimum) == NULL)
                        {
                            $max_id = 1;
                        }
                        else {
                            $max_id = $maksimum->max_id;
                            $max_id = $max_id + 1;
                        }

                        $data_persetujuan_permintaan_biaya = array(

                            'persetujuan_permintaan_biaya_id' => $max_id,
                            'permintaan_biaya_id'             => $id,
                            'user_level_id'                   => $persetujuan['user_level_menyetujui_id'],
                            'tipe'                            => 3,
                            '`order`'                         => $persetujuan['level_order'],
                            '`status`'                        => 1,
                            'is_active'                       => 1,
                            'created_by'                      => $this->session->userdata('user_id'),
                            'created_date'                    => date('Y-m-d H:i:s'),

                        );

                        $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);
                    }
                }

            }
            
            $keterangan = '';
            if($array_input['bon']){
                foreach ($array_input['bon'] as $key => $bon) {
                    if($bon['url'] != ''){

                        $keterangan .= $bon['keterangan'].';';

                        $path_dokumen = './assets/mb/pages/keuangan/permintaan_biaya/images/'.$id;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $bon['url'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $bon['url'];
                        $real_file = $id.'/'.$new_filename;

                        copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);

                        $data_bon = array(
                            'permintaan_biaya_id' => $id,
                            'no_bon'              => $bon['no_bon'],
                            'total_bon'           => $bon['total_bon'],
                            'keterangan'          => $bon['keterangan'],
                            'tgl_bon'             => date('Y-m-d', strtotime($bon['tanggal'])),
                            'url'                 => $bon['url']
                        );

                        $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_bon);

                    }
                }
            }

            if(count($user_level_persetujuan_array) == 0){
                $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date,2)->result_array();
                $after_saldo = $this->kasir_arus_kas_m->get_after_after($date,2)->result_array();
                
                $saldo_before = 0;
                if(count($last_saldo) != 0){
                    $saldo_before = intval($last_saldo[0]['saldo']);
                }

                $data_arus_kas = array(
                    'tanggal'      => $date,
                    'tipe'         => 5,
                    'tipe_kasir'   => 2,  
                    'keterangan'   => rtrim($keterangan,';'),
                    'user_id'      => $this->session->userdata('user_id'),
                    'debit_credit' => 'C',
                    'rupiah'       => $array_input['nominal'],
                    'saldo'        => ($saldo_before - $array_input['nominal']),
                    'status'       => 1
                );

                $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

                if(count($after_saldo) != 0){
                    foreach ($after_saldo as $after) {
                        $data_arus_kas_after = array(
                            'saldo'        => ($after['saldo'] - $array_input['nominal']),
                        );

                        $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                    }
                }
            }

            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Diproses", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }  

        if($command === 'proses_verif')
        {      

            if(count($user_level_persetujuan_array) == 0){      
                $data = array(
                    'status'          => 5,
                    'tanggal_proses'  => $date,
                    'diproses_oleh'   => $this->session->userdata('user_id'),
                    'po_id'           => $array_input['id_po'],
                    'sisa'            => $array_input['nominal_bon'] - $array_input['nominal']
                );
                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);
            }
            elseif(count($user_level_persetujuan_array) != 0){
                if($array_input['nominal_bon'] > $array_input['nominal']){
                    $data = array(
                        'status'          => 16,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id'),
                        'po_id'           => $array_input['id_po'],
                        'sisa'            => $array_input['nominal_bon'] - $array_input['nominal']
                    );
                    $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

                    foreach ($user_level_persetujuan_array as $persetujuan) 
                    {
                        $max_id   = '';
                        $maksimum = $this->persetujuan_permintaan_biaya_m->get_max()->row(0);

                        if(count($maksimum) == NULL)
                        {
                            $max_id = 1;
                        }
                        else {
                            $max_id = $maksimum->max_id;
                            $max_id = $max_id + 1;
                        }

                        $data_persetujuan_permintaan_biaya = array(

                            'persetujuan_permintaan_biaya_id' => $max_id,
                            'permintaan_biaya_id'             => $id,
                            'user_level_id'                   => $persetujuan['user_level_menyetujui_id'],
                            'tipe'                            => 3,
                            '`order`'                         => $persetujuan['level_order'],
                            '`status`'                        => 1,
                            'is_active'                       => 1,
                            'created_by'                      => $this->session->userdata('user_id'),
                            'created_date'                    => date('Y-m-d H:i:s'),

                        );

                        $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);
                    }
                }elseif($array_input['nominal_bon'] <= $array_input['nominal']){
                    $data = array(
                        'status'          => 5,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id'),
                        'po_id'           => $array_input['id_po'],
                        'sisa'            => $array_input['nominal_bon'] - $array_input['nominal']
                    );
                    $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);
                }
            }
            
            $keterangan = '';
            if($array_input['bon']){
                foreach ($array_input['bon'] as $key => $bon) {
                    if($bon['url'] != ''){

                        $keterangan .= $bon['keterangan'].';';

                        $path_dokumen = './assets/mb/pages/keuangan/permintaan_biaya/images/'.$id;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $bon['url'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $bon['url'];
                        $real_file = $id.'/'.$new_filename;

                        copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);

                        $data_bon = array(
                            'permintaan_biaya_id' => $id,
                            'no_bon'              => $bon['no_bon'],
                            'total_bon'           => $bon['total_bon'],
                            'keterangan'          => $bon['keterangan'],
                            'tgl_bon'             => date('Y-m-d', strtotime($bon['tanggal'])),
                            'url'                 => $bon['url']
                        );

                        $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_bon);

                    }
                }
            }

            if(count($user_level_persetujuan_array) == 0){ 
                if($array_input['nominal_bon'] <= $array_input['nominal']){
                    $sisa = $array_input['nominal'] - $array_input['nominal_bon'];

                    $data = array(
                        'sisa'        => $array_input['nominal_bon'] - $array_input['nominal']
                    );
                    $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);


                    $last_saldo_tambah = $this->kasir_arus_kas_m->get_saldo_before($date,2)->result_array();
                    $after_saldo_tambah = $this->kasir_arus_kas_m->get_after_after($date,2)->result_array();
                    
                    $saldo_before_tambah = 0;
                    if(count($last_saldo_tambah) != 0){
                        $saldo_before_tambah = intval($last_saldo_tambah[0]['saldo']);
                    }

                    $data_arus_kas_tambah = array(
                        'tanggal'      => $date,
                        'tipe'         => 6,
                        'tipe_kasir'   => 2,
                        'keterangan'   => 'Tambahan Kas Dari Sisa Kasbon',
                        'user_id'      => $this->session->userdata('user_id'),
                        'debit_credit' => 'D',
                        'rupiah'       => $sisa,
                        'saldo'        => ($saldo_before_tambah + $sisa),
                        'status'       => 1
                    );

                    $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_tambah);

                    if(count($after_saldo_tambah) != 0){
                        foreach ($after_saldo_tambah as $after) {
                            $data_arus_kas_after = array(
                                'saldo'        => ($after['saldo'] + $sisa),
                            );

                            $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                        }
                    }
                }
            }

            $data_beli = array(
                'status_keuangan' => 2
            );

            $pembelian = $this->pembelian_m->update_by($data_beli, $array_input['id_po']);

            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Diproses", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }  
        if($command === 'view')
        {
            $data = array(
                'status'          => 5,
                'tanggal_proses'  => $date,
                'diproses_oleh'   => $this->session->userdata('user_id'),
            );
            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

            $sisa = $array_input['sisa'];

            if($array_input['status_kasbon'] == 18){
                if($array_input['tipe'] == 2){
                    $last_saldo_rembes = $this->kasir_arus_kas_m->get_saldo_before($date,2)->result_array();
                    $after_saldo_saldo = $this->kasir_arus_kas_m->get_after_after($date,2)->result_array();
                    
                    $saldo_before = 0;
                    if(count($last_saldo_rembes) != 0){
                        $saldo_before = intval($last_saldo_rembes[0]['saldo']);
                    }

                    $data_arus_kas = array(
                        'tanggal'      => $date,
                        'tipe'         => 5,
                        'tipe_kasir'   => 2,
                        'keterangan'   => $array_input['keperluan'],
                        'user_id'      => $this->session->userdata('user_id'),
                        'debit_credit' => 'C',
                        'rupiah'       => $array_input['nominal'],
                        'saldo'        => ($saldo_before - $array_input['nominal']),
                        'status'       => 1
                    );

                    $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

                    if(count($after_saldo_saldo) != 0){
                        foreach ($after_saldo_saldo as $after) {
                            $data_arus_kas_after = array(
                                'saldo'        => ($after['saldo'] - $array_input['nominal']),
                            );

                            $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                        }
                    }
                }

                if($sisa > 0){
                    $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date,2)->result_array();
                    $after_saldo = $this->kasir_arus_kas_m->get_after_after($date,2)->result_array();
                    
                    $saldo_before = 0;
                    if(count($last_saldo) != 0){
                        $saldo_before = intval($last_saldo[0]['saldo']);
                    }

                    $data_arus_kas = array(
                        'tanggal'      => $date,
                        'tipe'         => 5,
                        'tipe_kasir'   => 2,
                        'keterangan'   => 'Tambahan '.$array_input['keperluan'],
                        'user_id'      => $this->session->userdata('user_id'),
                        'debit_credit' => 'D',
                        'rupiah'       => $sisa,
                        'saldo'        => ($saldo_before + $sisa),
                        'status'       => 1
                    );

                    $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

                    if(count($after_saldo) != 0){
                        foreach ($after_saldo as $after) {
                            $data_arus_kas_after = array(
                                'saldo'        => ($after['saldo'] + $sisa),
                            );

                            $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                        }
                    }
                }
            }

            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Selesai Diproses", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

              
        redirect('keuangan/proses_permintaan_biaya');

    }

    public function save_proses()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();            

            $level_id = $this->session->userdata('level_id');

            $response = new stdClass;
            $response->success = false;

            $cek_user_level_persetujuan   = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $level_id, 'tipe_persetujuan' => 9));
            $user_level_persetujuan_array = object_to_array($cek_user_level_persetujuan);
            
            if(count($cek_user_level_persetujuan))
            {              
                $data = array(
                    'status'          => 11
                );

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data, $array_input['id']);
                //data persetujuan permintaan pembayaran
                foreach ($user_level_persetujuan_array as $persetujuan) 
                {
                    $max_id   = '';
                    $maksimum = $this->persetujuan_permintaan_biaya_m->get_max()->row(0);

                    if(count($maksimum) == NULL)
                    {
                        $max_id = 1;
                    }
                    else {
                        $max_id = $maksimum->max_id;
                        $max_id = $max_id + 1;
                    }

                    $data_persetujuan_permintaan_biaya = array(

                        'persetujuan_permintaan_biaya_id' => $max_id,
                        'permintaan_biaya_id'             => $permintaan_biaya_id,
                        'user_level_id'                   => $persetujuan['user_level_menyetujui_id'],
                        'tipe'                            => 2,
                        '`order`'                         => $persetujuan['level_order'],
                        '`status`'                        => 1,
                        'is_active'                       => 1,
                        'created_by'                      => $this->session->userdata('user_id'),
                        'created_date'                    => date('Y-m-d H:i:s'),

                    );

                    $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);
                }

            } else {

                $data = array(
                    'status'          => 13
                );

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data, $array_input['id']);
            }

            if ($permintaan_biaya_id) 
            {
                $response->msg = translate('Permintaan biaya berhasil diproses', $this->session->userdata('language'));
                $response->success = true;

            }

            die(json_encode($response));


        } 
    }

    public function save_proses_cair()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();
            $date = date('Y-m-d');

            $response = new stdClass;
            $response->success = false;

            $data = array(
                'status'          => 15
            );

            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data, $array_input['id']);

            $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date,2)->result_array();
            $after_saldo = $this->kasir_arus_kas_m->get_after_after($date,2)->result_array();
            
            $saldo_before = 0;
            if(count($last_saldo) != 0){
                $saldo_before = intval($last_saldo[0]['saldo']);
            }

            $data_arus_kas = array(
                'tanggal'      => $date,
                'tipe'         => 5,
                'tipe_kasir'   => 2,
                'keterangan'   => $array_input['keperluan'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'C',
                'rupiah'       => $array_input['nominal'],
                'saldo'        => ($saldo_before - $array_input['nominal']),
                'status'       => 1
            );

            $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

            if(count($after_saldo) != 0){
                foreach ($after_saldo as $after) {
                    $data_arus_kas_after = array(
                        'saldo'        => ($after['saldo'] - $array_input['nominal']),
                    );

                    $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                }
            }



            if ($permintaan_biaya_id) 
            {
                $response->msg = translate('Permintaan biaya berhasil dicairkan', $this->session->userdata('language'));
                $response->success = true;

            }

            die(json_encode($response));
        }
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

    public function print_voucher($id)
    {
        $this->load->library('mpdf/mpdf.php');

        $form_data        = $this->permintaan_biaya_m->get($id);
        // die_dump($form_data);
        
        $body = array(

            'form_data'        => object_to_array($form_data),

        );

        $mpdf = new mPDF('utf-8','A5-L', 1, '', 5, 5, 10, 2, 0, 0);
        
        $mpdf->writeHTML($this->load->view('keuangan/proses_permintaan_biaya/print_voucher/voucher', $body, true));

        $mpdf->Output('voucher_'.date('Y-m-d H:i:s', strtotime($form_data->tanggal_proses)).'.pdf', 'I'); 
    }
}

/* End of file permintaan_biaya.php */
/* Location: ./application/controllers/keuangan/proses_permintaan_biaya.php */