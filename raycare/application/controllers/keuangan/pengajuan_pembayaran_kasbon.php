<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_pembayaran_kasbon extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '46915e62dbf12e996726549fcfed36bc';                  // untuk check bit_access

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

       
        $this->load->model('keuangan/pengajuan_pembayaran_kasbon/pengajuan_pembayaran_kasbon_m');
        $this->load->model('keuangan/pengajuan_pembayaran_kasbon/pengajuan_pembayaran_kasbon_detail_m');
        $this->load->model('keuangan/pengajuan_pembayaran_kasbon/persetujuan_pengajuan_pembayaran_kasbon_m');
        $this->load->model('keuangan/daftar_kasbon/permintaan_biaya_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_bon_m');
        $this->load->model('apotik/pembelian/pembelian_m');
        $this->load->model('keuangan/arus_kas_kasir/keuangan_arus_kas_m');

        $this->load->model('master/bank_m');
        $this->load->model('master/user_level_persetujuan_m');

        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/pengajuan_pembayaran_kasbon/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengajuan Pembayaran Kasbon', $this->session->userdata('language')), 
            'header'         => translate('Pengajuan Pembayaran Kasbon', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengajuan_pembayaran_kasbon/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function history()
    {
        $assets = array();
        $config = 'assets/keuangan/pengajuan_pembayaran_kasbon/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Pengajuan Pembayaran Kasbon', $this->session->userdata('language')), 
            'header'         => translate('History Pengajuan Pembayaran Kasbon', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengajuan_pembayaran_kasbon/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        $result = $this->pengajuan_pembayaran_kasbon_m->get_datatable();

        $user_level_id = $this->session->userdata('level_id');
        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pengajuan_pembayaran_kasbon/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';
            
            $status = '';
            if($row['status'] == 1)
            {
                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
            
            } elseif($row['status'] == 2){

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';
                $data_upload_cek = '<a title="'.translate('Upload Cek', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pengajuan_pembayaran_kasbon/upload_cek/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $action .= restriction_button($data_upload_cek,$user_level_id,'pengajuan_pembayaran_kasbon','upload_cek');


            } elseif($row['status'] == 3){

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            } elseif($row['status'] == 4){

                $status = '<div class="text-center"><span class="label label-md label-info">Menunggu Bon</span></div>';
                $data_upload_bon = '<a title="'.translate('Upload Bon', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pengajuan_pembayaran_kasbon/upload_bon/'.$row['id'].'/2" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                $action .= restriction_button($data_upload_bon,$user_level_id,'pengajuan_pembayaran_kasbon','upload_bon');
            }

        

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$row['no_cek'].'</div>',
                '<div class="text-left">'.$row['nama_bank'].' a/n '.$row['acc_name'].'</div>',
                '<div class="text-center">'.$status.'</div>',
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

    public function listing_history()
    {
        $result = $this->pengajuan_pembayaran_kasbon_m->get_datatable_history();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pengajuan_pembayaran_kasbon/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';
            
            $status = '';
            if($row['status'] == 3){

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            } elseif($row['status'] == 5){

                $status = '<div class="text-center"><span class="label label-md label-info">Selesai</span></div>';
            }

        

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$row['no_cek'].'</div>',
                '<div class="text-left">'.$row['nama_bank'].' a/n '.$row['acc_name'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_kasbon()
    {
        $result = $this->permintaan_biaya_m->get_datatable_proses();
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;

        $i=0;

        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            // PopOver Notes
            $notes    = $row['keperluan'];

            $tipe = '';
            if($row['tipe'] == 1){
                $tipe = 'Kasbon';
            }if($row['tipe'] == 2){
                $tipe = 'Reimburse / Pencairan';
            }
            $nominal = formatrupiah($row['nominal']);
            if($row['tipe'] == 2){
                $nominal = '<a class="detail-nominal" href="'.base_url().'keuangan/pengajuan_pembayaran_kasbon/modal_detail/'.$row['id'].'" data-target="#modal_detail" data-toggle="modal">'.formatrupiah($row['nominal']).'</a>';
            }

            $output['aaData'][] = array(
                '<div class="text-center"><input class="form-control checkboxes" name="kasbon['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-id="'.$row['id'].'" data-rp="'.$row['nominal_setujui'].'" checked><input class="form-control" name="kasbon['.$i.'][tipe]" id="tipe_'.$i.'" value="'.$row['tipe'].'" type="hidden" ></div>',
                '<div class="text-center"><input class="form-control" name="kasbon['.$i.'][id]" id="id_'.$i.'" type="hidden" value="'.$row['id'].'">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-right">'.$nominal.'</div>',
                '<div class="text-left">'.$notes.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);
    }

    public function add()
    {
        if(restriction_function($this->session->userdata('level_id'), 'pengajuan_pembayaran_kasbon','add'))
        {
            $assets = array();
            $assets_config = 'assets/keuangan/pengajuan_pembayaran_kasbon/add';
            $this->config->load($assets_config, true);

            $assets = $this->config->item('assets', $assets_config);

            $data = array(
                'title'          => config_item('site_name').' | '. translate("Tambah Pengajuan Pembayaran Kasbon", $this->session->userdata("language")), 
                'header'         => translate("Tambah Pengajuan Pembayaran Kasbon", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'keuangan/pengajuan_pembayaran_kasbon/add',
                'flag'           => 'add',
                'pk_value'       => '',
                
            );

            // Load the view
            $this->load->view('_layout', $data);
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function upload_cek($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'pengajuan_pembayaran_kasbon','upload_cek'))
        {
            $assets = array();
            $assets_config = 'assets/keuangan/pengajuan_pembayaran_kasbon/upload_cek';
            $this->config->load($assets_config, true);

            $assets = $this->config->item('assets', $assets_config);

            $form_data = $this->pengajuan_pembayaran_kasbon_m->get_by(array('id' => $id), true);
            // die_dump($form_data);
            $form_data_detail = $this->pengajuan_pembayaran_kasbon_detail_m->get_data_detail($id)->result_array();
            // die_dump($form_data_detail);

            $data = array(
                'title'          => config_item('site_name').' | '. translate("Pengajuan Pembayaran Kasbon", $this->session->userdata("language")), 
                'header'         => translate("Pengajuan Pembayaran Kasbon", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'keuangan/pengajuan_pembayaran_kasbon/upload_cek',
                'pk_value'       => $id,
                'form_data'      => object_to_array($form_data),
                'form_data_detail'  => object_to_array($form_data_detail),
                
            );

            // Load the view
            $this->load->view('_layout', $data);
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    } 

    public function upload_bon($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'pengajuan_pembayaran_kasbon','upload_bon'))
        {
            $assets = array();
            $assets_config = 'assets/keuangan/pengajuan_pembayaran_kasbon/upload_bon';
            $this->config->load($assets_config, true);

            $assets = $this->config->item('assets', $assets_config);

            $form_data = $this->pengajuan_pembayaran_kasbon_m->get_by(array('id' => $id), true);
            // die_dump($form_data);
            $form_data_detail = $this->pengajuan_pembayaran_kasbon_detail_m->get_data_detail($id)->result_array();
            $form_data_detail_kasbon = $this->pengajuan_pembayaran_kasbon_detail_m->get_data_detail_kasbon($id)->result_array();


            $data = array(
                'title'          => config_item('site_name').' | '. translate("Pengajuan Pembayaran Kasbon", $this->session->userdata("language")), 
                'header'         => translate("Pengajuan Pembayaran Kasbon", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'keuangan/pengajuan_pembayaran_kasbon/upload_bon',
                'pk_value'       => $id,
                'form_data'      => object_to_array($form_data),
                'form_data_detail'  => object_to_array($form_data_detail),
                'form_data_detail_kasbon'  => object_to_array($form_data_detail_kasbon),
                
            );

            // Load the view
            $this->load->view('_layout', $data);
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function view($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/pengajuan_pembayaran_kasbon/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->pengajuan_pembayaran_kasbon_m->get_by(array('id' => $id), true);
        // die_dump($form_data);
        $form_data_detail = $this->pengajuan_pembayaran_kasbon_detail_m->get_data_detail($id)->result_array();

        // die_dump($form_data_detail);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("View Pengajuan Pembayaran Kasbon", $this->session->userdata("language")), 
            'header'         => translate("View Pengajuan Pembayaran Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengajuan_pembayaran_kasbon/view',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            'form_data_detail'     => object_to_array($form_data_detail),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    

    public function save()
    {
        $array_input = $this->input->post();
        $command = $this->input->post('command');

        if($command === 'add')
        {
            $cek_user_level_persetujuan   = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $array_input['user_level_id'], 'tipe_persetujuan' => 8));
            $user_level_persetujuan_array = object_to_array($cek_user_level_persetujuan);
           
            if(count($cek_user_level_persetujuan) != 0)
            {
                $last_id       = $this->pengajuan_pembayaran_kasbon_m->get_max_id_pengajuan()->result_array();
                $last_id       = intval($last_id[0]['max_id'])+1;
                
                $format_id     = 'PK-'.date('m').'-'.date('Y').'-%04d';
                $id_pk         = sprintf($format_id, $last_id, 4);

                $data = array(
                    'id'              => $id_pk,
                    'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'nominal'         => $array_input['nominal'],
                    'nominal_setujui' => $array_input['nominal'],
                    'disetujui_oleh'  => 0,
                    'bank_id'         => $array_input['bank_id'],
                    'no_cek'          => $array_input['no_cek'],
                    'subjek'          => $array_input['subjek'],
                    'status'          => 1,   
                    'is_active'       => 1,   
                    'created_by'      => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')    

                );

                $pengajuan_pembayaran_kasbon_id = $this->pengajuan_pembayaran_kasbon_m->add_data($data);
                $nama_user = $this->session->userdata('nama_lengkap');
                sent_notification(9,$nama_user,$id_pk);

                //data tipe bayar
                $found_kasbon = false;
                $found_rembes = false;
                foreach ($array_input['kasbon'] as $kasbon) 
                {
                    if(isset($kasbon['checkbox'])){
                        if($kasbon['tipe'] == 1){
                            $found_kasbon = true;
                        }if($kasbon['tipe'] == 2){
                            $found_rembes = true;
                        }
                        $last_id_detail       = $this->pengajuan_pembayaran_kasbon_detail_m->get_max_id_detail_pengajuan()->result_array();
                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                        
                        $format_id_detail     = 'PKD-'.date('m').'-'.date('Y').'-%04d';
                        $id_pk_detail         = sprintf($format_id_detail, $last_id_detail, 4);


                        $data_detail = array(
                            'id'                             => $id_pk_detail,
                            'pengajuan_pembayaran_kasbon_id' => $id_pk,
                            'permintaan_biaya_id'            => $kasbon['id'],
                            'created_by'                     => $this->session->userdata('user_id'),
                            'created_date'                   => date('Y-m-d H:i:s') 
                        );
                        $pengajuan_pembayaran_kasbon_detail_id = $this->pengajuan_pembayaran_kasbon_detail_m->add_data($data_detail);

                        $data_kasbon = array(
                            'status'    => 6
                        );

                        $update_kasbon = $this->permintaan_biaya_m->save($data_kasbon, $kasbon['id']);
                    }
                }

                if($found_kasbon == true && $found_rembes == false){
                    $data_tipe = array(
                        'tipe' => 1
                    );
                }if($found_kasbon == false && $found_rembes == true){
                    $data_tipe = array(
                        'tipe' => 2
                    );
                }if($found_kasbon == true && $found_rembes == true){
                    $data_tipe = array(
                        'tipe' => 3
                    );
                }
                $pengajuan_pembayaran_kasbon_id = $this->pengajuan_pembayaran_kasbon_m->edit_data($data_tipe,$id_pk);
                //data persetujuan Pengajuan Pembayaran Kasbon
                foreach ($user_level_persetujuan_array as $persetujuan) 
                {
                    $max_id   = '';
                    $maksimum = $this->persetujuan_pengajuan_pembayaran_kasbon_m->get_last_id()->row(0);

                    if(count($maksimum) == NULL)
                    {
                        $max_id = 1;
                    }
                    else {
                        $max_id = $maksimum->last_id;
                        $max_id = $max_id + 1;
                    }

                    $data_persetujuan_pengajuan_pembayaran_kasbon = array(

                        'id'                             => $max_id,
                        'pengajuan_pembayaran_kasbon_id' => $id_pk,
                        'user_level_id'                  => $persetujuan['user_level_menyetujui_id'],
                        '`order`'                        => $persetujuan['level_order'],
                        '`status`'                       => 1,
                        'is_active'                      => 1,
                        'created_by'                     => $this->session->userdata('user_id'),
                        'created_date'                   => date('Y-m-d H:i:s'),

                    );

                    $persetujuan_pengajuan_id = $this->persetujuan_pengajuan_pembayaran_kasbon_m->add_data($data_persetujuan_pengajuan_pembayaran_kasbon);
                }

                if ($pengajuan_pembayaran_kasbon_id) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("Data Pengajuan Pembayaran Kasbon berhasil ditambahkan.", $this->session->userdata("language")),
                        "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }

                redirect('keuangan/pengajuan_pembayaran_kasbon');

            } else {

                $last_id       = $this->pengajuan_pembayaran_kasbon_m->get_max_id_pengajuan()->result_array();
                $last_id       = intval($last_id[0]['max_id'])+1;
                
                $format_id     = 'PK-'.date('m').'-'.date('Y').'-%04d';
                $id_pk         = sprintf($format_id, $last_id, 4);

                $data = array(
                    'id'              => $id_pk,
                    'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'nominal'         => $array_input['nominal'],
                    'nominal_setujui' => 0,
                    'bank_id'         => $array_input['bank_id'],
                    'no_cek'          => $array_input['no_cek'],
                    'subjek'          => $array_input['subjek'],
                    'status'          => 2,   
                    'is_active'       => 1,   
                    'created_by'      => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')    

                );

                $pengajuan_pembayaran_kasbon_id = $this->pengajuan_pembayaran_kasbon_m->add_data($data);

                //data tipe bayar
                $found_kasbon = false;
                $found_rembes = false;
                foreach ($array_input['kasbon'] as $kasbon) 
                {
                    if(isset($kasbon['checkbox'])){
                        if($kasbon['tipe'] == 1){
                            $found_kasbon = true;
                        }if($kasbon['tipe'] == 2){
                            $found_rembes = true;
                        }
                        $last_id_detail       = $this->pengajuan_pembayaran_kasbon_detail_m->get_max_id_detail_pengajuan()->result_array();
                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                        
                        $format_id_detail     = 'PKD-'.date('m').'-'.date('Y').'-%04d';
                        $id_pk_detail         = sprintf($format_id_detail, $last_id_detail, 4);


                        $data_detail = array(
                            'id'                             => $id_pk_detail,
                            'pengajuan_pembayaran_kasbon_id' => $id_pk,
                            'permintaan_biaya_id'            => $kasbon['id'],
                            'created_by'                     => $this->session->userdata('user_id'),
                            'created_date'                   => date('Y-m-d H:i:s') 
                        );
                        $pengajuan_pembayaran_kasbon_detail_id = $this->pengajuan_pembayaran_kasbon_detail_m->add_data($data_detail);

                        $data_kasbon = array(
                            'status'    => 6
                        );

                        $update_kasbon = $this->permintaan_biaya_m->save($data_kasbon, $kasbon['id']);
                    }
                }

                if($found_kasbon == true && $found_rembes == false){
                    $data_tipe = array(
                        'tipe' => 1
                    );
                }if($found_kasbon == false && $found_rembes == true){
                    $data_tipe = array(
                        'tipe' => 2
                    );
                }if($found_kasbon == true && $found_rembes == true){
                    $data_tipe = array(
                        'tipe' => 3
                    );
                }
                $pengajuan_pembayaran_kasbon_id = $this->pengajuan_pembayaran_kasbon_m->edit_data($data_tipe,$id_pk);

                if ($pengajuan_pembayaran_kasbon_id) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("Data Pengajuan Pembayaran Kasbon berhasil ditambahkan.", $this->session->userdata("language")),
                        "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }

                redirect('keuangan/pengajuan_pembayaran_kasbon');

            }

        }  
        if($command === 'upload_cek')
        {
            $id = $array_input['id'];
            $rupiah = $array_input['nominal'];
            $date = date('Y-m-d');

            if($array_input['url_cek'] != ''){
                $path_dokumen = './assets/mb/pages/keuangan/pengajuan_pembayaran_kasbon/images/'.$id;
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url_cek'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $array_input['url_cek'];
                $real_file = $id.'/'.$new_filename;

                copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_kasbon_besar').$real_file);
            }

            if($array_input['tipe'] == 1 || $array_input['tipe'] == 3){
                $data = array(
                    'status'  => 4,
                    'url_cek' => $array_input['url_cek']  
                );

                $pengajuan_pembayaran_kasbon_id = $this->pengajuan_pembayaran_kasbon_m->edit_data($data,$id);
                
                $nama_user = $this->session->userdata('nama_lengkap');
                sent_notification(14,$nama_user,$id);

            }elseif($array_input['tipe'] == 2){
                $data = array(
                    'status'      => 5,
                    'url_cek'     => $array_input['url_cek'],
                    'sisa_kasbon' => 0
                );

                $pengajuan_pembayaran_kasbon_id = $this->pengajuan_pembayaran_kasbon_m->edit_data($data,$id);
            }

            $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();
            foreach ($array_input['kasbon'] as $kasbon) 
            {
                if($kasbon['tipe'] == 2){
                    $data_kasbon = array(
                        'status'          => 5,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id')
                    );
                    $permintaan_biaya_id = $this->permintaan_biaya_m->save($data_kasbon, $kasbon['permintaan_biaya_id']);
                }
            }
            
            $saldo_before_bank = 0;
            if(count($last_saldo_bank) != 0){
                $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
            }

            $data_arus_kas_bank = array(
                'tanggal'      => $date,
                'tipe'         => 5,
                'keterangan'   => $array_input['subjek'],
                'bank_id'      => $array_input['bank_id'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'C',
                'rupiah'       => $rupiah,
                'saldo'        => ($saldo_before_bank - $rupiah),
                'status'       => 1
            );

            $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after) {
                    $data_arus_kas_after_bank = array(
                        'saldo'        => ($after['saldo'] - $rupiah),
                    );

                    $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                }
            }



            if ($pengajuan_pembayaran_kasbon_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Upload Cek Pengajuan Pembayaran Kasbon berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

            redirect('keuangan/pengajuan_pembayaran_kasbon');

        }

        if($command === 'upload_bon')
        {
            // die(dump($array_input));
            $id = $array_input['id'];
            $kasbon = $array_input['kasbon'];
            $bon = $array_input['bon'];
            $date = date('Y-m-d');

            if($array_input['url_bukti_setor'] != ''){
                $path_dokumen = './assets/mb/pages/keuangan/pengajuan_pembayaran_kasbon/images/'.$id;
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url_bukti_setor'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $array_input['url_bukti_setor'];
                $real_file = $id.'/'.$new_filename;

                copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_kasbon_besar').$real_file);
            }
            $data_pengajuan = array(
                'status'    => 5,
                'sisa_kasbon'   => $array_input['nominal_sisa'],
                'url_setor_sisa' => $array_input['url_bukti_setor']
            );

            $pengajuan_pembayaran_kasbon_id = $this->pengajuan_pembayaran_kasbon_m->edit_data($data_pengajuan,$id);

            if($array_input['nominal_sisa'] != 0){
                $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
                $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();
                
                $saldo_before_bank = 0;
                if(count($last_saldo_bank) != 0){
                    $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
                }

                $data_arus_kas_bank = array(
                    'tanggal'      => $date,
                    'tipe'         => 6,
                    'keterangan'   => 'Pengembalian Sisa Kasbon Diatas Satu Juta',
                    'bank_id'      => $array_input['bank_id'],
                    'user_id'      => $this->session->userdata('user_id'),
                    'debit_credit' => 'D',
                    'rupiah'       => $array_input['nominal_sisa'],
                    'saldo'        => ($saldo_before_bank + $array_input['nominal_sisa']),
                    'status'       => 1
                );

                $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

                if(count($after_saldo_bank) != 0){
                    foreach ($after_saldo_bank as $after) {
                        $data_arus_kas_after_bank = array(
                            'saldo'        => ($after['saldo'] + $array_input['nominal_sisa']),
                        );

                        $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                    }
                }
            }

            foreach ($kasbon as $row_kasbon) {
                $data_kasbon = array(
                    'po_id' => $row_kasbon['id_po']
                );
                $edit_permintaan_biaya = $this->permintaan_biaya_m->edit_data($data_kasbon, $row_kasbon['id']);

                $data_po = array(
                    'status_keuangan' => 2
                );
                $pembelian = $this->pembelian_m->edit_data($data_po, $row_kasbon['id_po']);
            }

            $keterangan = '';
            foreach ($bon as $key => $row_bon) {
                if($row_bon['url'] != ''){

                    $keterangan .= $row_bon['keterangan'].';';

                    $path_dokumen = './assets/mb/pages/keuangan/permintaan_biaya/images/'.$row_bon['kasbon_id'];
                    if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                    $temp_filename = $row_bon['url'];

                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = $row_bon['url'];
                    $real_file = $row_bon['kasbon_id'].'/'.$new_filename;

                    copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);

                    $data_bon = array(
                        'permintaan_biaya_id' => $row_bon['kasbon_id'],
                        'no_bon'              => $row_bon['no_bon'],
                        'total_bon'           => $row_bon['total_bon'],
                        'keterangan'          => $row_bon['keterangan'],
                        'tgl_bon'             => date('Y-m-d', strtotime($row_bon['tanggal'])),
                        'url'                 => $row_bon['url']
                    );

                    $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_bon);

                    $date = date('Y-m-d H:i:s');
                    $data_kasbon_update = array(
                        'status'          => 5,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id')
                    );
                    $permintaan_biaya_id = $this->permintaan_biaya_m->save($data_kasbon_update,$row_bon['kasbon_id']);

                }
            }

            if ($pengajuan_pembayaran_kasbon_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Upload Cek Pengajuan Pembayaran Kasbon berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

            redirect('keuangan/pengajuan_pembayaran_kasbon');

        }
    }

    public function modal_add_po()
    {
        $data_po = $this->pembelian_m->get_by(array('status_keuangan', 1));
        $data_po = object_to_array($data_po);

        $body = array(
            'data_po' => $data_po
        );

        $this->load->view('keuangan/pengajuan_pembayaran_kasbon/modal_add_po', $body);
    }

    public function modal_add_bon($permintaan_biaya_id)
    {
        $data_permintaan = $this->permintaan_biaya_m->get_by(array('id' => $permintaan_biaya_id), true);
    
        $body = array(
            'permintaan_biaya_id' => $permintaan_biaya_id,
            'data_permintaan' => $data_permintaan
        );

        $this->load->view('keuangan/pengajuan_pembayaran_kasbon/modal_add_bon', $body);
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

    public function modal_detail($id)
    {
        $data_rembes = $this->permintaan_biaya_m->get_by(array('id' => $id), true);
        $data_rembes_detail = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));
        $user_minta = $this->user_m->get($data_rembes->diminta_oleh_id);
        $user_level = $this->user_level_m->get($user_minta->user_level_id);

        $data = array(
            'user_minta'         => object_to_array($user_minta),
            'user_level'         => object_to_array($user_level),
            'data_rembes'        => object_to_array($data_rembes),
            'data_rembes_detail' => object_to_array($data_rembes_detail)
        );

        $this->load->view('keuangan/pengajuan_pembayaran_kasbon/modal_detail', $data);
    }



}

/* End of file pengajuan_pembayaran_kasbon.php */
/* Location: ./application/controllers/keuangan/pengajuan_pembayaran_kasbon.php */