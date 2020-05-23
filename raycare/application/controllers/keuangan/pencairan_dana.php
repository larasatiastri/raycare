<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pencairan_dana extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '9d9c58b794be000e0f0fdd46c190d302';                  // untuk check bit_access

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

       
        $this->load->model('keuangan/pencairan_dana/m_pencairan_dana');
        $this->load->model('keuangan/pencairan_dana/biaya_permintaan_dana_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_bon_m');

        $this->load->model('keuangan/pencairan_dana/persetujuan_permintaan_biaya_m');

        $this->load->model('keuangan/pencairan_dana/persetujuan_permintaan_biaya_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');

        $this->load->model('master/biaya_m');
        $this->load->model('master/bank_m');
        $this->load->model('master/divisi_m');
        $this->load->model('master/user_level_persetujuan_m');

        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/pencairan_dana/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Permintaan Dana', $this->session->userdata('language')), 
            'header'         => translate('Permintaan Dana', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pencairan_dana/index',
            // 'content_view'   => 'under_maintenance',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/keuangan/pencairan_dana/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Permintaan Biaya', $this->session->userdata('language')), 
            'header'         => translate('History Permintaan Biaya', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pencairan_dana/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_status()
    {       

        $result = $this->m_pencairan_dana->get_datatable_biaya();
        //die_dump($result);

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die_dump($records);
        $i=0;

        foreach($records->result_array() as $row)
        {
            $i++;

            $tipe = '';
            $status = '';
            $action = '';
            $waktu_akhir = '-';

            $status_detail_awal = $this->pembayaran_status_detail_m->get_data($row['id'],0)->result_array();
            $status_detail_revisi = $this->pembayaran_status_detail_m->get_data($row['id'],1)->result_array();

            if($row['waktu_akhir'] != NULL){
                $waktu_akhir = date('d M Y, H:i', strtotime($row['waktu_akhir']));
            }
            if($row['tipe_transaksi'] == 2){
                $tipe = 'Kasbon';

                if($row['status'] == 1)
                {
                    $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                
                } elseif($row['status'] == 2){

                    $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

                }elseif($row['status'] == 3){

                    $status = '<span class="label label-danger">Menunggu Diproses</span>';
                    

                } elseif($row['status'] == 4){
                    
                    $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
                }elseif($row['status'] == 5){
                    
                    $status = '<div class="text-center"><span class="label label-md label-default">Diproses</span></div>';
                }elseif($row['status'] == 6){
                    $status = '<div class="text-center"><span class="label label-md label-info">Proses Pengajuan</span></div>';
                }elseif($row['status'] == 7){
                    $status = '<div class="text-center"><span class="label label-md label-info">Proses Persetujuan Direktur</span></div>';
                }elseif($row['status'] == 8){
                    $status = '<div class="text-center"><span class="label label-md label-success">Proses Pencairan</span></div>';
                }if($row['status'] == 11){
                    $status = '<span class="label label-warning">Menunggu Persetujuan Keuangan</span>';
                }if($row['status'] == 12){
                    $status = '<span class="label label-warning">Proses Persetujuan Keuangan</span>';
                }if($row['status'] == 13){
                    $status = '<span class="label label-success">Disetujui Keuangan</span>';
                    
                    
                }if($row['status'] == 15){
                    $status = '<span class="label label-warning">Menunggu Verif Bon</span>';
                    
                }if($row['status'] == 16){
                    $status = '<span class="label label-warning">Menunggu Verifikasi Keuangan</span>';
                    
                }if($row['status'] == 18 || $row['status'] == 19){
                    $status = '<span class="label label-info">Diverifikasi Keuangan</span>';
                }
                if($row['status'] == 21){
                    $status = '<span class="label label-warning">Proses Revisi</span>';
                    $action .= '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/revisi/'.$row['transaksi_id'].'"  class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                }
                $action .='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/view/'.$row['transaksi_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

            }if($row['tipe_transaksi'] == 3){
                $tipe = 'Reimburse';
                if($row['status'] == 1)
                {
                    $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                
                } elseif($row['status'] == 2){

                    $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

                }elseif($row['status'] == 3){

                    $status = '<span class="label label-danger">Menunggu Diproses</span>';

                } elseif($row['status'] == 4){
                    
                    $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
                }elseif($row['status'] == 5){
                    
                    $status = '<div class="text-center"><span class="label label-md label-default">Diproses</span></div>';
                }elseif($row['status'] == 6){
                    $status = '<div class="text-center"><span class="label label-md label-info">Proses Pengajuan</span></div>';
                }elseif($row['status'] == 7){
                    $status = '<div class="text-center"><span class="label label-md label-info">Proses Persetujuan Direktur</span></div>';
                }elseif($row['status'] == 8){
                    $status = '<div class="text-center"><span class="label label-md label-success">Proses Pencairan</span></div>';
                }
                if($row['status'] == 16){
                    $status = '<span class="label label-warning">Menunggu Verifikasi Keuangan</span>';
                }if($row['status'] == 18 || $row['status'] == 19){
                    $status = '<span class="label label-info">Diverifikasi Keuangan</span>';
                }if($row['status'] == 20){
                    $status = '<span class="label label-warning">Menunggu Proses Keuangan</span>';
                }
                if($row['status'] == 21){
                    $status = '<span class="label label-warning">Proses Revisi</span>';
                    $action .= '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/revisi/'.$row['transaksi_id'].'"  class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                }
            $action .='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/view_reimburse/'.$row['transaksi_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';


            }


            $output['aaData'][] = array(
                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'"></span>',
                '<div class="text-center">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-left inline-button-table">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-left">'.$row['nama_level_proses'].'</div>',
                '<div class="text-center">'.$waktu_akhir.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function listing()
    {

        $result = $this->m_pencairan_dana->get_datatable();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $count = count($records->result_array());
        //die(dump($records));
    
        $i=0;
        foreach($records->result_array() as $row)
        {
            $status = '';
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            if($row['status'] == 1)
            {
                $action = '';
                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
            
            } elseif($row['status'] == 2){

                $action = '';
                $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

            }elseif($row['status'] == 3){

                $action = '';
                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status'] == 4){
                $action = '';

                if($row['status_proses'] == 0){
                    $action .= '<a title="'.translate('OK', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/proses/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
                }
                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            }elseif($row['status'] == 5){
                $action = '';

                if($row['status_proses'] == 0){
                    $action .= '<a title="'.translate('OK', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/proses/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
                }
                $status = '<div class="text-center"><span class="label label-md label-default">Diproses</span></div>';
            }elseif($row['status'] == 6){
                $action = '';

                $status = '<div class="text-center"><span class="label label-md label-info">Proses Pengajuan</span></div>';
            }elseif($row['status'] == 7){
                $action = '';

                $status = '<div class="text-center"><span class="label label-md label-info">Proses Persetujuan Direktur</span></div>';
            }elseif($row['status'] == 8){
                $action = '';

                $status = '<div class="text-center"><span class="label label-md label-success">Proses Pencairan</span></div>';
            }

            $tipe = '';

            if($row['tipe'] == 1){
                $tipe = 'Kas';
            }if($row['tipe'] == 2){
                $tipe = 'Reimburse / Pencairan';
            }

            $action .='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

            if($row['status_revisi'] == 1){
                $action .='<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/revisi/'.$row['id'].'"  class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
            }
            // PopOver Notes
            $notes = explode("\n", $row['keperluan']);
            $notes    = implode('</br>', $notes);
        
            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
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

        $result = $this->m_pencairan_dana->get_datatable_history();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $count = count($records->result_array());
    
        $i=0;
        foreach($records->result_array() as $row)
        {
            $status = '';
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            if($row['status'] == 1)
            {
                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
            
            } elseif($row['status'] == 2){

                $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

            }elseif($row['status'] == 3){

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status'] == 4){

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            }elseif($row['status'] == 5){

                $status = '<div class="text-center"><span class="label label-md label-default">Diproses</span></div>';
            }

            $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

            
            $tipe = '';

            if($row['tipe'] == 1){
                $tipe = 'Kas';
            }if($row['tipe'] == 2){
                $tipe = 'Reimburse / Pencairan';
            }
            // PopOver Notes
            $notes    = $row['keperluan'];
        
            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$notes.'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/keuangan/pencairan_dana/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Tambah Permintaan Dana", $this->session->userdata("language")), 
            'header'         => translate("Tambah Permintaan Dana", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pencairan_dana/add',
            'flag'           => 'add',
            'pk_value'       => '',
            
        );

        // Load the view
        $this->load->view('_layout', $data);
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
            'title'          => config_item('site_name').' | '. translate("View Dana", $this->session->userdata("language")), 
            'header'         => translate("View Dana", $this->session->userdata("language")), 
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

    public function view_reimburse($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id, 'is_active' => 1));
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

    public function revisi($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/permintaan_biaya/revisi';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_detail = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id, 'is_active' => 1));
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Revisi Kasbon", $this->session->userdata("language")), 
            'header'         => translate("Revisi Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/permintaan_biaya/revisi',
            'flag'           => 'revisi',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            'form_data_detail' => $form_data_detail,
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    
    public function save()
    {
        $array_input = $this->input->post();
        //die(dump($array_input));
        $command = $this->input->post('command');

        $level_id = $this->session->userdata('level_id');
        $user_level_buat = $this->user_level_m->get($level_id);

        $divisi_buat = $this->divisi_m->get($user_level_buat->divisi_id);

        if($command === 'add')
        {
            $cek_user_level_persetujuan   = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $array_input['user_level_id'], 'tipe_persetujuan' => 5));
            $user_level_persetujuan_array = object_to_array($cek_user_level_persetujuan);

            $count_biaya_setuju = 0;
            
            if(count($cek_user_level_persetujuan))
            {              
                $last_number   = $this->permintaan_biaya_m->get_no_permintaan()->result_array();
                $last_number   = intval($last_number[0]['max_no_permintaan'])+1;
                
                if($array_input['tipe'] == 1){
                    $format        = '#CH#%03d/RCKLD-'.strtoupper($divisi_buat->kode).'/'.romanic_number(date('m'), true).'/'.date('Y');
                    $tipe_transaksi = 2;
                    $waktu_akhir = date('Y-m-d H:i:s', strtotime("+48 hours"));
                }if($array_input['tipe'] == 2){
                    $format        = '#RB#%03d/RCKLD-'.strtoupper($divisi_buat->kode).'/'.romanic_number(date('m'), true).'/'.date('Y');
                    $tipe_transaksi = 3;
                    $waktu_akhir = date('Y-m-d H:i:s', strtotime("+168 hours"));
                }
                $no_permintaan     = sprintf($format, $last_number, 3);

                $data = array(
                    'nomor_permintaan' => $no_permintaan,
                    'diminta_oleh_id'  => $array_input['id_ref_pasien'],
                    'tanggal'          => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'nominal'          => $array_input['nominal'],
                    'nominal_setujui'  => $array_input['nominal'],
                    'keperluan'        => $array_input['keperluan'],
                    'tipe'             => $array_input['tipe'],
                    'status'           => 1,
                    'status_revisi'    => 0,
                    'status_proses'    => 0,
                    'is_active'        => 1,

                );
                if($array_input['tipe'] == 1){
                    $biaya = $array_input['biaya'];

                    foreach ($biaya as $row_biaya) {
                        
                        $data['biaya_id']   = $row_biaya['biaya_id'];
                        $data['keperluan']   = $array_input['keperluan'];

                        $data_biaya_dana = $this->biaya_permintaan_dana_m->get_by(array('biaya_id' => $row_biaya['biaya_id'], 'is_active' => 1));
                        $count_biaya_setuju = count($data_biaya_dana);
                    }
                }

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data);

                if($array_input['tipe'] == 2){
                    $bon = $array_input['bon'];
                    $keterangan = '';
                    foreach ($bon as $row_bon) {
                        if($row_bon['url'] != ''){

                            $keterangan .= $row_bon['keterangan'].';';

                            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/permintaan_biaya/images/'.$permintaan_biaya_id;
                            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                            $temp_filename = $row_bon['url'];

                            $convtofile = new SplFileInfo($temp_filename);
                            $extenstion = ".".$convtofile->getExtension();

                            $new_filename = $row_bon['url'];
                            $real_file = $permintaan_biaya_id.'/'.$new_filename;

                            copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);
                            unlink(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename);

                            $data_detail = array(
                                'permintaan_biaya_id' => $permintaan_biaya_id,
                                'biaya_id'           => $row_bon['biaya_id'],
                                'no_bon'              => $row_bon['no_bon'],
                                'total_bon'           => $row_bon['nominal_bon'],
                                'keterangan'          => $row_bon['keterangan'],
                                'tgl_bon'             => date('Y-m-d', strtotime($row_bon['tanggal'])),
                                'url'                 => $row_bon['url'],
                                'is_active'           => 1
                            );

                            $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_detail);

                        }
                    }
                }

                $last_id_status       = $this->pembayaran_status_m->get_max_id_pembayaran()->result_array();
                $last_id_status       = intval($last_id_status[0]['max_id'])+1;
                
                $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
                $id_status         = sprintf($format_id_status, $last_id_status, 4);

                $posisi = $user_level_persetujuan_array[0]['user_level_menyetujui_id'];
                $divisi_posisi = $this->user_level_m->get($posisi);

                $data_status = array(
                    'id'              => $id_status,
                    'transaksi_id'    => $permintaan_biaya_id,
                    'transaksi_nomor' => $no_permintaan,
                    'tipe_transaksi'  => $tipe_transaksi,
                    'nominal'         => $array_input['nominal'],
                    'status'          => $data['status'],
                    'user_level_id'   => $posisi,
                    'divisi'          => $divisi_posisi->divisi_id,
                    'waktu_akhir'     => $waktu_akhir,
                    'is_active'       => 1,
                    'created_by'      => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                );

                $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);
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
                        'tipe'                            => 1,
                        '`order`'                         => $persetujuan['level_order'],
                        '`status`'                        => 1,
                        'is_active'                       => 1,
                        'created_by'                      => $this->session->userdata('user_id'),
                        'created_date'                    => date('Y-m-d H:i:s'),

                    );

                    $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);

                    $user_level = $this->user_level_m->get($persetujuan['user_level_menyetujui_id']);

                    $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    
                    $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'pembayaran_status_id' => $id_status,
                        'transaksi_id'         => $permintaan_biaya_id,
                        'tipe_transaksi'       => $tipe_transaksi,
                        '`order`'              => $persetujuan['level_order'],
                        'divisi'               => $user_level->divisi_id,
                        'user_level_id'        => $persetujuan['user_level_menyetujui_id'],
                        'tipe'                 => 1,
                        'tipe_pengajuan'       => 0,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                    );

                    $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                }

                if($array_input['tipe'] == 1){
                    $biaya = $array_input['biaya'];

                    $order_status = count($user_level_persetujuan_array);

                    foreach ($biaya as $row_biaya) {
                        
                        $data_biaya_dana = $this->biaya_permintaan_dana_m->get_by(array('biaya_id' => $row_biaya['biaya_id'], 'is_active' => 1));
                        $data_biaya_dana = object_to_array($data_biaya_dana);
                        $count_biaya_setuju = count($data_biaya_dana);

                        foreach ($data_biaya_dana as $persetujuan) 
                        {
                            $order_status = $order_status + 1;
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
                                'user_level_id'                   => $persetujuan['user_level_id'],
                                'tipe'                            => 1,
                                '`order`'                         => $order_status,
                                '`status`'                        => 1,
                                'is_active'                       => 1,
                                'created_by'                      => $this->session->userdata('user_id'),
                                'created_date'                    => date('Y-m-d H:i:s'),

                            );

                            $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);

                            $user_level = $this->user_level_m->get($persetujuan['user_level_id']);

                            $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                            $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                            
                            $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                            $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                            $data_status_detail = array(
                                'id'                   => $id_status_detail,
                                'pembayaran_status_id' => $id_status,
                                'transaksi_id'         => $permintaan_biaya_id,
                                'tipe_transaksi'       => $tipe_transaksi,
                                '`order`'              => $order_status,
                                'divisi'               => $user_level->divisi_id,
                                'user_level_id'        => $persetujuan['user_level_id'],
                                'tipe'                 => 1,
                                'tipe_pengajuan'       => 0,
                                'is_active'            => 1,
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                            );

                            $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                        }
                    }
                }

                $order_status_kasir = count($user_level_persetujuan_array) + $count_biaya_setuju;
                for($i=0; $i<3; $i++){
                    $order_status_kasir = $order_status_kasir + 1;

                    $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    
                    $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    if($i == 0 || $i == 2){
                        $user_level = $this->user_level_m->get(21);

                        $divisi_id = $user_level->divisi_id;
                        $user_level_id = 21;
                    }if($i == 1){
                        $user_level = $this->user_level_m->get(5);

                        $divisi_id = $user_level->divisi_id;
                        $user_level_id = 5;
                    }

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'pembayaran_status_id' => $id_status,
                        'transaksi_id'         => $permintaan_biaya_id,
                        'tipe_transaksi'       => $tipe_transaksi,
                        '`order`'              => $order_status_kasir,
                        'divisi'               => $divisi_id,
                        'user_level_id'        => $user_level_id,
                        'tipe'                 => 2,
                        'tipe_pengajuan'       => 0,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                    );

                    $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                }

                $level_id = $array_input['user_level_id'];

                if($level_id == 20){
                    $nama_user = $this->session->userdata('nama_lengkap');
                    sent_notification(8,$nama_user,$permintaan_biaya_id);
                }if($level_id == 19 || $level_id == 10 || $level_id == 8 || $level_id == 18){
                    $nama_user = $this->session->userdata('nama_lengkap');
                    sent_notification(7,$nama_user,$permintaan_biaya_id);
                }if($level_id == 3 || $level_id == 5 || $level_id == 27){
                    $nama_user = $this->session->userdata('nama_lengkap');
                    sent_notification(15,$nama_user,$permintaan_biaya_id);
                }

            } else {

                $last_number   = $this->permintaan_biaya_m->get_no_permintaan()->result_array();
                $last_number   = intval($last_number[0]['max_no_permintaan'])+1;
                
                if($array_input['tipe'] == 1){
                    $format        = '#CH#%03d/RCKLD-'.strtoupper($divisi_buat->kode).'/'.romanic_number(date('m'), true).'/'.date('Y');
                    $tipe_transaksi = 2;
                    $waktu_akhir = date('Y-m-d H:i:s', strtotime("+48 hours"));
                }if($array_input['tipe'] == 2){
                    $format        = '#RB#%03d/RCKLD-'.strtoupper($divisi_buat->kode).'/'.romanic_number(date('m'), true).'/'.date('Y');
                    $tipe_transaksi = 3;
                    $waktu_akhir = date('Y-m-d H:i:s', strtotime("+168 hours"));
                }
                $no_permintaan     = sprintf($format, $last_number, 3);

                $data = array(
                    'nomor_permintaan' => $no_permintaan,
                    'diminta_oleh_id' => $array_input['id_ref_pasien'],
                    'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'nominal'         => $array_input['nominal'],
                    'nominal_setujui' => $array_input['nominal'],
                    'keperluan'       => $array_input['keperluan'],
                    'tipe'            => $array_input['tipe'],
                    'status'          => 3,
                    'disetujui_oleh'   => 0,
                    'status_revisi'   => 0,
                    'status_proses'   => 0,
                    'is_active'       => 1,
                );

                if($array_input['tipe'] == 1){
                    $biaya = $array_input['biaya'];

                    foreach ($biaya as $row_biaya) {
                        
                        $data['biaya_id']   = $row_biaya['biaya_id'];
                        $data['keperluan']   = $array_input['keperluan'].' '.$row_biaya['keterangan'];
                        
                    }
                }

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data);

                if($array_input['tipe'] == 2){
                    $bon = $array_input['bon'];
                    $keterangan = '';
                    foreach ($bon as $row_bon) {
                        if($row_bon['url'] != ''){

                           // $keterangan .= $row_bon['keterangan'].';';

                            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/permintaan_biaya/images/'.$permintaan_biaya_id;
                            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                            $temp_filename = $row_bon['url'];

                            $convtofile = new SplFileInfo($temp_filename);
                            $extenstion = ".".$convtofile->getExtension();

                            $new_filename = $row_bon['url'];
                            $real_file = $permintaan_biaya_id.'/'.$new_filename;

                            copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);
                            unlink(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename);
                            
                            $data_detail = array(
                                'permintaan_biaya_id' => $permintaan_biaya_id,
                                'biaya_id'           => $row_bon['biaya_id'],
                                'no_bon'              => $row_bon['no_bon'],
                                'total_bon'           => $row_bon['nominal_bon'],
                                'keterangan'          => $row_bon['keterangan'],
                                'tgl_bon'             => date('Y-m-d', strtotime($row_bon['tanggal'])),
                                'url'                 => $row_bon['url'],
                                'is_active'           => 1
                            );

                            $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_detail);

                        }
                    }
                }

                $last_id_status       = $this->pembayaran_status_m->get_max_id_pembayaran()->result_array();
                $last_id_status       = intval($last_id_status[0]['max_id'])+1;
                
                $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
                $id_status         = sprintf($format_id_status, $last_id_status, 4);

                $user_level_posisi = $this->user_level_m->get(21);

                $data_status = array(
                    'id'             => $id_status,
                    'transaksi_id'   => $permintaan_biaya_id,
                    'transaksi_nomor'   => $no_permintaan,
                    'tipe_transaksi' => $tipe_transaksi,
                    'nominal'        => $array_input['nominal'],
                    'status'         => $data['status'],
                    'user_level_id'   => 21,
                    'divisi'          => $user_level_posisi->divisi_id,
                    'waktu_akhir'    => $waktu_akhir,
                    'is_active'      => 1,
                    'created_by'     => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s'),
                    //'created_date'   => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                );

                $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);

                $order_status = count($user_level_persetujuan_array);
                for($i=0; $i<3; $i++){
                    $order_status = $order_status + 1;

                    $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    
                    $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    if($i == 0 || $i == 2){
                        $user_level = $this->user_level_m->get(21);

                        $divisi_id = $user_level->divisi_id;
                        $user_level_id = 21;
                    }if($i == 1){
                        $user_level = $this->user_level_m->get(5);

                        $divisi_id = $user_level->divisi_id;
                        $user_level_id = 5;
                    }

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'pembayaran_status_id' => $id_status,
                        'transaksi_id'         => $permintaan_biaya_id,
                        'tipe_transaksi'       => $tipe_transaksi,
                        '`order`'              => $order_status,
                        'divisi'               => $divisi_id,
                        'user_level_id'        => $user_level_id,
                        'tipe'                 => 2,
                        'tipe_pengajuan'       => 0,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                    );

                    $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                }
                
                if($array_input['nominal'] >= config_item('limit_cash')){
                    $nama_user = $this->session->userdata('nama_lengkap');
                    sent_notification(11,$nama_user,$permintaan_biaya_id);
                }if($array_input['nominal'] < config_item('limit_cash')){
                    $nama_user = $this->session->userdata('nama_lengkap');
                    sent_notification(12,$nama_user,$permintaan_biaya_id);
                }
            }

            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Ditambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }  

        if($command === 'revisi')
        {
            $id = $array_input['id'];
            $data = array(
                'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'nominal'         => $array_input['nominal'],
                'nominal_setujui' => $array_input['nominal'],
                'keperluan'       => $array_input['keperluan'],
                'tipe'            => $array_input['tipe'],
                'status'          => 3,
                'status_proses'   => 0,
                'status_revisi'   => 0,
                'is_active'       => 1,
            );

            if($array_input['tipe'] == 1){
                $biaya = $array_input['biaya'];

                foreach ($biaya as $row_biaya) {
                    
                    $data['biaya_id']   = $row_biaya['biaya_id'];
                    $data['keperluan']   = $array_input['keperluan'].' '.$row_biaya['keterangan'];
                    
                }
            }

            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data, $id);

            if($array_input['tipe'] == 1){
                $tipe_transaksi = 2;
            }if($array_input['tipe'] == 2){
                $tipe_transaksi = 3;
            }

            $user_level_kasir = $this->user_level_m->get(21);
            $level_id = $this->session->userdata('user_id');
            $data_status = array(
                'status'          => 3,
                'user_level_id'   => 21,
                'divisi'          => $user_level_kasir->divisi_id,
                'nominal'         => $array_input['nominal'],
                'created_date'    => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
            );

            $wheres_status = array(
                'transaksi_id' => $id,
                'tipe_transaksi' => $tipe_transaksi
            );

            $pembayaran_status = $this->pembayaran_status_m->update_by($level_id,$data_status,$wheres_status);

            if($array_input['tipe'] == 2){
                $bon = $array_input['bon'];
                $keterangan = '';
                foreach ($bon as $row_bon) {
                    if($row_bon['id'] == ''){
                        if($row_bon['url'] != ''){

                            $keterangan .= $row_bon['keterangan'].';';

                            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/permintaan_biaya/images/'.$id;
                            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                            $temp_filename = $row_bon['url'];

                            $convtofile = new SplFileInfo($temp_filename);
                            $extenstion = ".".$convtofile->getExtension();

                            $new_filename = $row_bon['url'];
                            $real_file = $id.'/'.$new_filename;

                            copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);
                            unlink(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename);

                            $data_detail = array(
                                'permintaan_biaya_id' => $id,
                                'biaya_id'           => $row_bon['biaya_id'],
                                'no_bon'              => $row_bon['no_bon'],
                                'total_bon'           => $row_bon['nominal_bon'],
                                'keterangan'          => $row_bon['keterangan'],
                                'tgl_bon'             => date('Y-m-d', strtotime($row_bon['tanggal'])),
                                'url'                 => $row_bon['url'],
                                'is_active'           => 1
                            );

                            $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_detail);
                        }
                    }if($row_bon['id'] != ''){
                        if($row_bon['is_active'] == '0'){
                            $data_detail = array(
                                'is_active'           => 0
                            );

                            $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_detail,$row_bon['id']);
                        }if($row_bon['is_active'] == '1'){
                            if($row_bon['url'] != ''){

                                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/permintaan_biaya/images/'.$id;
                                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                                $temp_filename = $row_bon['url'];

                                $convtofile = new SplFileInfo($temp_filename);
                                $extenstion = ".".$convtofile->getExtension();

                                $new_filename = $row_bon['url'];
                                $real_file = $id.'/'.$new_filename;

                                // if (file_exists(FCPATH.config_item('user_img_temp_dir_new').$temp_filename) && is_file(FCPATH.config_item('user_img_temp_dir_new').$temp_filename)){
                                    copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);
                                
                                    unlink(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename);
                                // } 

                                $data_detail = array(
                                    'biaya_id'           => $row_bon['biaya_id'],
                                    'no_bon'              => $row_bon['no_bon'],
                                    'total_bon'           => $row_bon['nominal_bon'],
                                    'keterangan'          => $row_bon['keterangan'],
                                    'tgl_bon'             => date('Y-m-d', strtotime($row_bon['tanggal'])),
                                    'url'                 => $row_bon['url'],
                                    'is_active'           => 1
                                );

                                $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_detail,$row_bon['id']);
                            }
                        }
                    }
                }
            }
        }

        if($command === 'revisi_old')
        {
            $id = $array_input['id'];

            $cek_user_level_persetujuan   = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $array_input['user_level_id'], 'tipe_persetujuan' => 5));
            $user_level_persetujuan_array = object_to_array($cek_user_level_persetujuan);
            
            if(count($cek_user_level_persetujuan))
            {              
                $data = array(
                    'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'nominal'         => $array_input['nominal'],
                    'nominal_setujui' => 0,
                    'keperluan'       => $array_input['keperluan'],
                    'tipe'            => $array_input['tipe'],
                    'status'          => 1,
                    'status_proses'   => 0,
                    'is_active'       => 1,
                );

                if($array_input['tipe'] == 1){
                    $biaya = $array_input['biaya'];

                    foreach ($biaya as $row_biaya) {
                        
                        $data['biaya_id']   = $row_biaya['biaya_id'];
                        $data['keperluan']   = $array_input['keperluan'].' '.$row_biaya['keterangan'];
                        
                    }
                }

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data, $id);

                if($array_input['tipe'] == 2){
                    $bon = $array_input['bon'];
                    $keterangan = '';
                    foreach ($bon as $row_bon) {
                        if($row_bon['id'] == ''){
                            if($row_bon['url'] != ''){

                                $keterangan .= $row_bon['keterangan'].';';

                                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/permintaan_biaya/images/'.$id;
                                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                                $temp_filename = $row_bon['url'];

                                $convtofile = new SplFileInfo($temp_filename);
                                $extenstion = ".".$convtofile->getExtension();

                                $new_filename = $row_bon['url'];
                                $real_file = $id.'/'.$new_filename;

                                copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);

                                unlink(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename);

                                $data_detail = array(
                                    'permintaan_biaya_id' => $id,
                                    'biaya_id'           => $row_bon['biaya_id'],
                                    'no_bon'              => $row_bon['no_bon'],
                                    'total_bon'           => $row_bon['nominal_bon'],
                                    'keterangan'          => $row_bon['keterangan'],
                                    'tgl_bon'             => date('Y-m-d', strtotime($row_bon['tanggal'])),
                                    'url'                 => $row_bon['url'],
                                    'is_active'           => 1
                                );

                                $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_detail);
                            }
                        }if($row_bon['id'] != ''){
                            if($row_bon['is_active'] == '0'){
                                $data_detail = array(
                                    'is_active'           => 0
                                );

                                $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_detail,$row_bon['id']);
                            }if($row_bon['is_active'] == '1'){
                                if($row_bon['url'] != ''){

                                    $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/permintaan_biaya/images/'.$id;
                                    if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                                    $temp_filename = $row_bon['url'];

                                    $convtofile = new SplFileInfo($temp_filename);
                                    $extenstion = ".".$convtofile->getExtension();

                                    $new_filename = $row_bon['url'];
                                    $real_file = $id.'/'.$new_filename;

                                    // if (file_exists(FCPATH.config_item('user_img_temp_dir_new').$temp_filename) && is_file(FCPATH.config_item('user_img_temp_dir_new').$temp_filename)){
                                        copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);
                                        
                                        unlink(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename);
                                    // } 

                                    $data_detail = array(
                                        'biaya_id'           => $row_bon['biaya_id'],
                                        'no_bon'              => $row_bon['no_bon'],
                                        'total_bon'           => $row_bon['nominal_bon'],
                                        'keterangan'          => $row_bon['keterangan'],
                                        'tgl_bon'             => date('Y-m-d', strtotime($row_bon['tanggal'])),
                                        'url'                 => $row_bon['url'],
                                        'is_active'           => 1
                                    );

                                    $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_detail);
                                }
                            }
                        }
                    }
                }

                
                $posisi = $user_level_persetujuan_array[0]['user_level_menyetujui_id'];
                $divisi_posisi = $this->user_level_m->get($posisi);

                if($array_input['tipe'] == 1){
                    $waktu_akhir = date('Y-m-d H:i:s', strtotime("+48 hours"));
                    $tipe_transaksi = 2;
                }if($array_input['tipe'] == 2){
                    $waktu_akhir = date('Y-m-d H:i:s', strtotime("+168 hours"));
                    $tipe_transaksi = 3;
                }

                $data_status = array(
                    'status'          => 1,
                    'user_level_id'   => $posisi,
                    'divisi'          => $divisi_posisi->divisi_id,
                    'waktu_akhir'     => $waktu_akhir
                );

                $wheres_status = array(
                    'transaksi_id' => $id,
                    'tipe_transaksi' => $tipe_transaksi
                );

                $pembayaran_status = $this->pembayaran_status_m->update_by($level_id,$data_status,$wheres_status);

                $pembayaran_status_data = $this->pembayaran_status_m->get_by($wheres_status, true);
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
                        'permintaan_biaya_id'             => $id,
                        'user_level_id'                   => $persetujuan['user_level_menyetujui_id'],
                        'tipe'                            => 1,
                        '`order`'                         => $persetujuan['level_order'],
                        '`status`'                        => 1,
                        'is_active'                       => 1,
                        'created_by'                      => $this->session->userdata('user_id'),
                        'created_date'                    => date('Y-m-d H:i:s'),

                    );

                    $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);

                    $user_level = $this->user_level_m->get($persetujuan['user_level_menyetujui_id']);

                    $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    
                    $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'pembayaran_status_id' => $pembayaran_status_data->id,
                        'transaksi_id'         => $id,
                        'tipe_transaksi'       => $tipe_transaksi,
                        '`order`'              => $persetujuan['level_order'],
                        'divisi'               => $user_level->divisi_id,
                        'user_level_id'        => $persetujuan['user_level_menyetujui_id'],
                        'tipe'                 => 1,
                        'tipe_pengajuan'       => 1,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                    );

                    $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                }

                $order_status = count($user_level_persetujuan_array);
                for($i=0; $i<3; $i++){
                    $order_status = $order_status + 1;

                    $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    
                    $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    if($i == 0 || $i == 2){
                        $user_level = $this->user_level_m->get(21);

                        $divisi_id = $user_level->divisi_id;
                        $user_level_id = 21;
                    }if($i == 1){
                        $user_level = $this->user_level_m->get(5);

                        $divisi_id = $user_level->divisi_id;
                        $user_level_id = 5;
                    }

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'pembayaran_status_id' => $pembayaran_status_data->id,
                        'transaksi_id'         => $id,
                        'tipe_transaksi'       => $tipe_transaksi,
                        '`order`'              => $order_status,
                        'divisi'               => $divisi_id,
                        'user_level_id'        => $user_level_id,
                        'tipe'                 => 2,
                        'tipe_pengajuan'       => 1,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                }

            } else {

                $data = array(
                    'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'nominal'         => $array_input['nominal'],
                    'nominal_setujui' => $array_input['nominal'],
                    'keperluan'       => $array_input['keperluan'],
                    'tipe'            => $array_input['tipe'],
                    'status'          => 3,
                    'disetujui_oleh'   => 0,
                    'status_revisi'   => 0,
                    'status_proses'   => 0,
                    'is_active'       => 1,
                );

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data, $id);

                if($array_input['tipe'] == 1){
                    $waktu_akhir = date('Y-m-d H:i:s', strtotime("+48 hours"));
                    $tipe_transaksi = 2;
                }if($array_input['tipe'] == 2){
                    $waktu_akhir = date('Y-m-d H:i:s', strtotime("+168 hours"));
                    $tipe_transaksi = 3;
                }

                $user_level_posisi = $this->user_level_m->get(21);

                $data_status = array(
                    'status'          => 1,
                    'user_level_id'   => 21,
                    'divisi'          => $user_level_posisi->divisi_id,
                    'waktu_akhir'     => $waktu_akhir
                );

                $wheres_status = array(
                    'transaksi_id' => $id,
                    'tipe_transaksi' => $tipe_transaksi
                );

                $pembayaran_status = $this->pembayaran_status_m->update_by($level_id,$data_status,$wheres_status);
                $pembayaran_status_data = $this->pembayaran_status_m->get_by($wheres_status, true);

                $order_status = count($user_level_persetujuan_array);
                for($i=0; $i<3; $i++){
                    $order_status = $order_status + 1;

                    $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    
                    $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    if($i == 0 || $i == 2){
                        $user_level = $this->user_level_m->get(21);

                        $divisi_id = $user_level->divisi_id;
                        $user_level_id = 21;
                    }if($i == 1){
                        $user_level = $this->user_level_m->get(5);

                        $divisi_id = $user_level->divisi_id;
                        $user_level_id = 5;
                    }

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'pembayaran_status_id' => $pembayaran_status_data->id,
                        'transaksi_id'         => $id,
                        'tipe_transaksi'       => $tipe_transaksi,
                        '`order`'              => $order_status,
                        'divisi'               => $divisi_id,
                        'user_level_id'        => $user_level_id,
                        'tipe'                 => 2,
                        'tipe_pengajuan'       => 1,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
                }
            }

            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Ditambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }        
        redirect('keuangan/permintaan_biaya');

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

    public function proses($id)
    {
        $data_biaya = array(
            'status_proses' => 1
        );

        $this->permintaan_biaya_m->save($data_biaya, $id);

        redirect('keuangan/permintaan_biaya');
    }
}

/* End of file permintaan_biaya.php */
/* Location: ./application/controllers/keuangan/permintaan_biaya.php */