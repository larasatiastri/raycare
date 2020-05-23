<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_biaya extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '744f82e8c8ccc5e6e84747542030f029';                  // untuk check bit_access

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

       
        $this->load->model('keuangan/persetujuan_permintaan_biaya/persetujuan_permintaan_biaya_m');
        $this->load->model('keuangan/persetujuan_permintaan_biaya/persetujuan_permintaan_biaya_history_m');
        $this->load->model('keuangan/persetujuan_permintaan_biaya/biaya_permintaan_dana_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_bon_m');
        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_barang_m');

        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_m');
        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_cetak_m');
        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_tipe_m');
        $this->load->model('keuangan/permintaan_biaya/persetujuan_permintaan_biaya_m');

        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');


        $this->load->model('master/bank_m');
        $this->load->model('master/biaya_m');
        $this->load->model('master/user_level_persetujuan_m');

        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/persetujuan_permintaan_biaya/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Persetujuan Permintaan Dana', $this->session->userdata('language')), 
            'header'         => translate('Persetujuan Permintaan Dana', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_permintaan_biaya/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
    
    public function history()
    {
        $assets = array();
        $config = 'assets/keuangan/persetujuan_permintaan_biaya/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Persetujuan Permintaan Dana', $this->session->userdata('language')), 
            'header'         => translate('History Persetujuan Permintaan Dana', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_permintaan_biaya/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        
        $user_level_id = $this->session->userdata('level_id');

        $result = $this->persetujuan_permintaan_biaya_m->get_datatable($user_level_id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $count = count($records->result_array());

        // die_dump($count);

        $i=0;

        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            $order = '';

            $data_order = $this->persetujuan_permintaan_biaya_m->data_order($row['permintaan_biaya_id'], $row['order_persetujuan'])->result_array();
            $action = '';   

            foreach ($data_order as $order) 
            {
                if($order['order'] == 1 && $order['user_level_id'] == $user_level_id)
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_permintaan_biaya/view/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>
                               <a title="'.translate('Process', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/persetujuan_permintaan_biaya/proses/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                } 

                elseif ($order['user_level_id'] == $user_level_id && $order['order'] != 1 ) 
                {
                    $data_order2 = $this->persetujuan_permintaan_biaya_m->data_order2($order['permintaan_biaya_id'], $order['order'])->result_array();
                    
                    foreach ($data_order2 as $order2) 
                    {
                        if($order2['status'] == 4 || $order2['status'] == 3)
                        {

                            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_permintaan_biaya/view/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>
                                       <a title="'.translate('Process', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/persetujuan_permintaan_biaya/proses/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                        } else {

                            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_permintaan_biaya/view/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                        }
                    }
                }

                if($order['order'] == 1 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_permintaan_biaya/view/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                } elseif ($order['order'] == 2 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_permintaan_biaya/view/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                } elseif ($order['order'] == 3 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_permintaan_biaya/view/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                }
            }

            

            // PopOver Notes
            $notes    = $row['keperluan'];
            $words    = explode(' ', $notes);
            $impWords = implode(' ', array_splice($words, 0, 6));
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="input-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$row['keperluan'].'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function listing_history()
    {
        
        $user_level_id = $this->session->userdata('level_id');
        $result = $this->persetujuan_permintaan_biaya_history_m->get_datatable($user_level_id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $count = count($records->result_array());

        // die_dump($count);

        $i=0;

        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_permintaan_biaya/view_history/'.$row['permintaan_biaya_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';

            // PopOver Notes
            $status = '';
            $notes    = $row['keperluan'];
            $words    = explode(' ', $notes);
            $impWords = implode(' ', array_splice($words, 0, 6));
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';


            if($row['status_persetujuan'] == 1)
            {
                $status = '<span class="label label-md label-warning">Menunggu Persetujuan</span>';
            
            } elseif($row['status_persetujuan'] == 2){

                $status = '<span class="label label-md label-info">Dibaca</span>';

            }elseif($row['status_persetujuan'] == 3 || $row['status_persetujuan'] == 8){

                $status = '<span class="label label-md label-success">Disetujui</span>';

            } elseif($row['status_persetujuan'] == 4 || $row['status_persetujuan'] == 19){

                $status = '<span class="label label-md label-danger">Ditolak</span>';
            } elseif($row['status_persetujuan'] == 5 || $row['status_persetujuan'] == 18){

                $status = '<span class="label label-md label-success">Diproses</span>';
            } elseif($row['status_persetujuan'] == 16 ){

                $status = '<span class="label label-warning">Menunggu Verifikasi Keuangan</span>';
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="input-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="input-right">'.$status.'</div>',
                '<div class="text-left">'.$row['keperluan'].'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function proses($id, $order)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/persetujuan_permintaan_biaya/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $user_level_id = $this->session->userdata('level_id');

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_detail = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id, 'is_active' => 1));

        $form_data_persetujuan = $this->persetujuan_permintaan_biaya_m->get_by(array('permintaan_biaya_id' => $id, '`order`' => $order), true);
        $data_persetujuan = object_to_array($form_data_persetujuan);

        $user_id = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');
        $date = date('Y-m-d H:i:s');

        $tipe_permintaan = 2;

        if($form_data->tipe == 1){
            $tipe_permintaan = 2;
        }else{
            $tipe_permintaan = 3;
        }

        if($data_persetujuan['status'] == 1)
        {
            $update_status = array(
                'status'        => 2,
                'tanggal_baca'  => $date,
                'dibaca_oleh'   => $user_id,
                'modified_by'   => $user_id,
                'modified_date' => $date,
            );

            $wheres_setujui = array(
                'permintaan_biaya_id' => $data_persetujuan['permintaan_biaya_id'],
                'user_level_id' => $user_level_id,
            );

            $update = $this->persetujuan_permintaan_biaya_m->update_status($update_status, $data_persetujuan['persetujuan_permintaan_biaya_id'], $data_persetujuan['permintaan_biaya_id'], $user_level_id);
            
            $data_status = array(
                'status'        => 2,
                'modified_by'   => $user_id,
                'modified_date' => $date
            );      

            $wheres_status = array(
                'transaksi_id'   => $data_persetujuan['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
            );    

            $update_status = $this->pembayaran_status_m->update_by($user_level_id,$data_status,$wheres_status);  

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $data_persetujuan['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 1,
                'user_level_id'  => $level_id
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'transaksi_id'   => $data_persetujuan['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 1,
                '`order`'        => $pembayaran_status_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $pembayaran_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 1,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

        }

        if($form_data->status <= 2){
            $data_minta = array(
                'status' => 2
            );

            $this->permintaan_biaya_m->save($data_minta, $id);
        }if($form_data->status == 11){
            $data_minta = array(
                'status' => 12
            );

            $this->permintaan_biaya_m->save($data_minta, $id);
        }if($form_data->status == 16){
            $data_minta = array(
                'status' => 17
            );

            $this->permintaan_biaya_m->save($data_minta, $id);
        }

        $data = array(
            'title'                 => config_item('site_name').' &gt;'. translate("Proses Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header'                => translate("Proses Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header_info'           => config_item('site_name'), 
            'breadcrumb'            => TRUE,
            'menus'                 => $this->menus,
            'menu_tree'             => $this->menu_tree,
            'css_files'             => $assets['css'],
            'js_files'              => $assets['js'],
            'content_view'          => 'keuangan/persetujuan_permintaan_biaya/proses',
            'flag'                  => 'proses',
            'pk_value'              => $id,
            'order'                 => $order,
            'form_data'             => object_to_array($form_data),
            'form_data_detail'             => object_to_array($form_data_detail),
            'form_data_persetujuan' => object_to_array($form_data_persetujuan),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id, $order)
    {

        $id            = intval($id);
        $user_level_id = $this->session->userdata('level_id');
        $id || redirect(base_Url());


        $assets = array();
        $assets_config = 'assets/keuangan/persetujuan_permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_detail = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id, 'is_active' => 1));
        
        $form_persetujuan = $this->persetujuan_permintaan_biaya_m->get_by(array('permintaan_biaya_id' => $id, '`order`' => $order), true);
        $data_persetujuan = object_to_array($form_persetujuan);

        $user_id = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');


        // if($data_persetujuan['status'] == 1)
        // {
        //     $update_status = array(
        //         'status'        => 2,
        //         'tanggal_baca'  => $date,
        //         'dibaca_oleh'   => $user_id,
        //         'modified_by'   => $user_id,
        //         'modified_date' => $date,
        //     );

        //     $wheres_setujui = array(
        //         'permintaan_biaya_id' => $data_persetujuan['permintaan_biaya_id'],
        //         'user_level_id' => $user_level_id,
        //     );

        //     $update = $this->persetujuan_permintaan_biaya_m->update_status($update_status, $data_persetujuan['persetujuan_permintaan_biaya_id'], $data_persetujuan['permintaan_biaya_id'], $user_level_id);

        // }

        // $data_status = array(
        //     'status'    => 2,
        //     'user_level_id' => $this->session->userdata('level_id'),
        // );
        // $wheres_status = array(
        //     'transaksi_id'   => $data_persetujuan['permintaan_biaya_id'],
        // );
        // $update_status = $this->pembayaran_status_m->update_by($user_level_id,$data_status,$wheres_status);


        $data = array(
            'title'                 => config_item('site_name').' &gt;'. translate("View Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header'                => translate("View Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header_info'           => config_item('site_name'), 
            'breadcrumb'            => TRUE,
            'menus'                 => $this->menus,
            'menu_tree'             => $this->menu_tree,
            'css_files'             => $assets['css'],
            'js_files'              => $assets['js'],
            'content_view'          => 'keuangan/persetujuan_permintaan_biaya/view',
            'flag'                  => 'view',
            'pk_value'              => $id,
            'order'                 => $order,
            'form_data'             => object_to_array($form_data),
            'form_data_detail'      => object_to_array($form_data_detail),
            'form_data_persetujuan' => object_to_array($form_persetujuan),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_history($id, $order)
    {

        $id            = intval($id);
        $user_level_id = $this->session->userdata('level_id');
        $id || redirect(base_Url());


        $assets = array();
        $assets_config = 'assets/keuangan/persetujuan_permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        
        
        $form_persetujuan = $this->persetujuan_permintaan_biaya_history_m->get_by(array('permintaan_biaya_id' => $id, '`order`' => $order), true);
        $data_persetujuan = object_to_array($form_persetujuan);

        $user_id = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');



        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("View Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header'         => translate("View Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_permintaan_biaya/view_history',
            'flag'           => 'view',
            'pk_value'              => $id,
            'order'                 => $order,
            'form_data'      => object_to_array($form_data),
            'form_data_persetujuan' => object_to_array($form_persetujuan),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function modal_view($permintaan_id)
    {
        $data_persetujuan = $this->persetujuan_permintaan_biaya_m->get_data_full($permintaan_id);
        $data = array(
            'data_setuju' => $data_persetujuan
        );

        $this->load->view('keuangan/persetujuan_permintaan_biaya/modal/modal_view', $data);
    }

    public function modal_view_history($permintaan_id)
    {
        $data_persetujuan = $this->persetujuan_permintaan_biaya_history_m->get_data_full($permintaan_id);
        $data = array(
            'data_setuju' => $data_persetujuan
        );

        $this->load->view('keuangan/persetujuan_permintaan_biaya/modal/modal_view', $data);
    }

    public function reject_proses($persetujuan_permintaan_biaya_id, $permintaan_biaya_id, $order)
    {
        $data = array( 
            'persetujuan_permintaan_biaya_id' => $persetujuan_permintaan_biaya_id,
            'permintaan_biaya_id'             => $permintaan_biaya_id,
            'order'                           => $order,
        );

        $this->load->view('keuangan/persetujuan_permintaan_biaya/modal/modal_proses', $data);
    }

    
    public function tolak_permintaan()
    {
        $array_input = $this->input->post();
        $user        = $this->session->userdata('user_id');
        $date        = date('Y-m-d H:i:s');
        $user_level_id = $this->session->userdata('level_id');
        $status = 0;

        $tipe_permintaan = 2;
        if($array_input['tipe_dana'] == 1){
            $tipe_permintaan = 2;
        }if($array_input['tipe_dana'] == 2){
            $tipe_permintaan = 3;
        }
        
        $data = array(
            'status'              => 4,
            'keterangan'          => $array_input['keterangan_tolak'],
            'tanggal_persetujuan' => $date,
            'disetujui_oleh'      => $user,
            'nominal'             => 0
        );

        $wheres_update_last = array(
            'permintaan_biaya_id' => $array_input['permintaan_biaya_id'],
        );

        $update = $this->persetujuan_permintaan_biaya_m->update_by($user_level_id,$data, $wheres_update_last);

        if($array_input['tipe'] == 1){
            $data_permintaan = array(
                'status'          => 4,
                'nominal_setujui' => 0
            );         
            $status = 4;  
        }if($array_input['tipe'] == 2){
            $data_permintaan = array(
                'status'          => 14,
                'nominal_setujui' => 0
            ); 
            $status = 14;          
        }if($array_input['tipe'] == 3){
            $data_permintaan = array(
                'status'          => 19,
                'nominal_setujui' => 0
            );  
            $status = 19;         
        }
        $update_permintaan = $this->permintaan_biaya_m->save($data_permintaan, $array_input['permintaan_biaya_id']);   

        $data_status = array(
            'status'        => $status,
            'modified_by'   => $user,
            'modified_date' => $date
        );      

        $wheres_status = array(
            'transaksi_id'   => $data_persetujuan['permintaan_biaya_id'],
            'tipe_transaksi' => $tipe_permintaan,
        );    

        $update_status = $this->pembayaran_status_m->update_by($user_level_id,$data_status,$wheres_status);  

        $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

        $wheres_bayar_detail = array(
            'transaksi_id'   => $data_persetujuan['permintaan_biaya_id'],
            'tipe_transaksi' => $tipe_permintaan,
            'tipe_pengajuan' => 0,
            'tipe'           => 1,
            'user_level_id'  => $user_level_id
        );
        $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

        $wheres_bayar_detail_before = array(
            'transaksi_id'   => $data_persetujuan['permintaan_biaya_id'],
            'tipe_transaksi' => $tipe_permintaan,
            'tipe_pengajuan' => 0,
            'tipe'           => 1,
            '`order`'        => $pembayaran_status_detail_id->order - 1
        );

        $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

        if(count($pembayaran_status_detail_before) != 0){
            $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
        }else{
            $waktu_proses = $pembayaran_status_id->created_date;
        }

        $datetime1 = new DateTime();
        $datetime2 = new DateTime($waktu_proses);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%a d %h h %i m %S s');

        $data_pembayaran_status_detail = array(
            'status'         => 3,
            'tanggal_proses' => date('Y-m-d H:i:s'),
            'user_proses'    => $user_id,
            'waktu_tunggu'   => $elapsed,
            'modified_by'    => $user_id,
            'modifed_date'   => date('Y-m-d H:i:s')
        );

        $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id); 
        //proses save ke history
        $get_data_persetujuan = $this->persetujuan_permintaan_biaya_m->get_by(array('permintaan_biaya_id' => $array_input['permintaan_biaya_id']));
        $data_persetujuan = object_to_array($get_data_persetujuan);

        foreach ($data_persetujuan as $row) 
        {          
            $data_history = array(

                'permintaan_biaya_id' => $row['permintaan_biaya_id'],
                'user_level_id'       => $row['user_level_id'],
                '`order`'             => $row['order'],
                '`status`'            => $row['status'],
                'tanggal_baca'        => $row['tanggal_baca'],
                'dibaca_oleh'         => $row['dibaca_oleh'],
                'tanggal_persetujuan' => $row['tanggal_persetujuan'],
                'disetujui_oleh'      => $row['disetujui_oleh'],
                'nominal'             => $row['nominal'],
                'keterangan'          => $row['keterangan'],
            );

            $history_persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_history_m->save($data_history);
            $delete = $this->persetujuan_permintaan_biaya_m->delete_id($row['persetujuan_permintaan_biaya_id']);
        }
         
        

        if ($update || $history_persetujuan_permintaan_biaya_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data Permintaan Ditolak.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('keuangan/persetujuan_permintaan_biaya');

    }

    public function save()
    {

        // $command = $this->input->post('command');
        $array_input   = $this->input->post();
        // die_dump($array_input);
        $date          = date('Y-m-d H:i:s');
        $user          = $this->session->userdata('user_id');
        $user_level_id = $this->session->userdata('level_id');

        $tipe_permintaan = 2;
        if($array_input['tipe_dana'] == 1){
            $tipe_permintaan = 2;
        }if($array_input['tipe_dana'] == 2){
            $tipe_permintaan = 3;
        }
        
        
        $get_order_max = $this->persetujuan_permintaan_biaya_m->get_max_order($array_input['permintaan_biaya_id'])->row(0);
        // die_dump($get_order_max);
        if($array_input['order'] != $get_order_max->max_order)
        {
            // die_dump('bukan order terakhir');
            $data = array(
                'status'              => 3,
                'keterangan'          => $array_input['keterangan'],
                'tanggal_persetujuan' => $date,
                'disetujui_oleh'      => $user,
                'nominal'             => $array_input['nominal_setujui']
            );

            $wheres_update = array(
                'persetujuan_permintaan_biaya_id' => $array_input['persetujuan_permintaan_biaya_id'],
                'permintaan_biaya_id'             => $array_input['permintaan_biaya_id'],
                'user_level_id'                   => $user_level_id,
            );

            $update = $this->persetujuan_permintaan_biaya_m->update_by($user_level_id,$data, $wheres_update);

            $data_permintaan = array(
                'keterangan_tolak' => $array_input['keterangan'],
                'nominal_setujui'  => $array_input['nominal_setujui'],
                'disetujui_oleh'   => $user
            );

            $update_permintaan = $this->permintaan_biaya_m->save($data_permintaan, $array_input['permintaan_biaya_id']);
   

            $wheres_status = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
            );    


            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 1,
                'user_level_id'  => $user_level_id
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 1,
                '`order`'        => $pembayaran_status_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $pembayaran_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 2,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user,
                'waktu_tunggu'   => $elapsed,
                'modified_by'    => $user,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

            $wheres_bayar_detail_after = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                '`order`'        => $pembayaran_status_detail_id->order + 1
            );

            $pembayaran_status_detail_after = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_after, true);

            $data_status_after = array(
                'status'           => 1,
                'modified_by'      => $user,
                'modified_date'    => date('Y-m-d H:i:s')
            );      

            if(count($pembayaran_status_detail_after) != 0){
                $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
            }

            $wheres_status = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
            );    

            $update_status = $this->pembayaran_status_m->update_by($user_level_id,$data_status_after,$wheres_status);  

            // die_dump($this->db->last_query());
        } else {

            $data = array(

                'status'              => 3,
                'keterangan'          => $array_input['keterangan'],
                'tanggal_persetujuan' => $date,
                'disetujui_oleh'      => $user,
                'nominal'             => $array_input['nominal_setujui']
            );

            $wheres_update_last = array(
                'persetujuan_permintaan_biaya_id' => $array_input['persetujuan_permintaan_biaya_id'],
                'permintaan_biaya_id' => $array_input['permintaan_biaya_id'],
                'user_level_id' => $user_level_id,
            );

            $update = $this->persetujuan_permintaan_biaya_m->update_by($user_level_id,$data, $wheres_update_last);

            if($array_input['tipe'] == 1){

                $data_permintaan = array(
                    'status'           => 3,
                    'nominal_setujui'  => $array_input['nominal_setujui'],
                    'keterangan_tolak' => $array_input['keterangan'],
                    'disetujui_oleh'   => $user
                );  

                $biaya = $array_input['biaya'];
                foreach ($biaya as $row) {
                    $data_setuju_biaya = $this->biaya_permintaan_dana_m->get_by(array('biaya_id' => $row['biaya_id']));
                    $data_setuju_biaya = object_to_array($data_setuju_biaya);

                    if(count($data_setuju_biaya) != 0){
                // die(dump($data_setuju_biaya));
                        $data_permintaan['status'] = 3;

                        foreach ($data_setuju_biaya as $setujui) {
                            $max_id   = 0;
                            $maksimum = $this->persetujuan_permintaan_biaya_m->get_max()->row(0);

                            if(count($maksimum) == NULL){
                                $max_id = 1;
                            }
                            else {
                                $max_id = $maksimum->max_id;
                                $max_id = $max_id + 1;
                            }

                            $data_persetujuan_permintaan_biaya = array(

                                'persetujuan_permintaan_biaya_id' => $max_id,
                                'permintaan_biaya_id'             => $array_input['permintaan_biaya_id'],
                                'permintaan_biaya_bon_id'         => $row['id_bon'],
                                'user_level_id'                   => $setujui['user_level_id'],
                                'tipe'                            => 1,
                                '`order`'                         => $setujui['level_order'],
                                '`status`'                        => 1,
                                'is_active'                       => 1,
                                'created_by'                      => $this->session->userdata('user_id'),
                                'created_date'                    => date('Y-m-d H:i:s'),

                            );

                            $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya); 
                            // die(dump($this->db->last_query()));
                        }
                    }else{
                        $data_permintaan['status'] = 2;
                    }
                }
                
                if($array_input['nominal_setujui'] >= config_item('limit_cash')){
                    $nama_user = $this->session->userdata('nama_lengkap');
                    sent_notification(11,$nama_user,$array_input['permintaan_biaya_id']);
                }if($array_input['nominal_setujui'] < config_item('limit_cash')){
                    $nama_user = $this->session->userdata('nama_lengkap');
                    sent_notification(12,$nama_user,$array_input['permintaan_biaya_id']);
                }  
            }if($array_input['tipe'] == 2){
                $data_permintaan = array(
                    'status'           => 13,
                    'keterangan_tolak' => $array_input['keterangan'],
                    'nominal_setujui'  => $array_input['nominal_setujui'],
                    'disetujui_oleh'   => $user
                );    
                $biaya = $array_input['biaya'];

                foreach ($biaya as $row) {
                    $data_setuju_biaya = $this->biaya_permintaan_dana_m->get_by(array('biaya_id' => $row['biaya_id']));
                    $data_setuju_biaya = object_to_array($data_setuju_biaya);

                    if(count($data_setuju_biaya) != 0){
                        $data_permintaan['status'] = 13;

                        foreach ($data_setuju_biaya as $setujui) {
                            $max_id   = 0;
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
                                'permintaan_biaya_id'             => $array_input['permintaan_biaya_id'],
                                'permintaan_biaya_bon_id'         => $row['id_bon'],
                                'user_level_id'                   => $setujui['user_level_id'],
                                'tipe'                            => 1,
                                '`order`'                         => $setujui['level_order'],
                                '`status`'                        => 1,
                                'is_active'                       => 1,
                                'created_by'                      => $this->session->userdata('user_id'),
                                'created_date'                    => date('Y-m-d H:i:s'),

                            );

                            $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);
                            
                        }
                    }else{
                        $data_permintaan['status'] = 2;
                    }
                }
            }if($array_input['tipe'] == 3){
                $data_permintaan = array(
                    'status'           => 18,
                    'keterangan_tolak' => $array_input['keterangan'],
                );    
            }

            $update_permintaan = $this->permintaan_biaya_m->save($data_permintaan, $array_input['permintaan_biaya_id']);

            
            $wheres_status = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
            );    

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 1,
                'user_level_id'  => $user_level_id
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 1,
                '`order`'        => $pembayaran_status_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $pembayaran_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 2,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user,
                'waktu_tunggu'   => $elapsed,
                'modified_by'    => $user,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);
            // die_dump($this->db->last_query());
            
            $wheres_bayar_detail_after = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                '`order`'        => $pembayaran_status_detail_id->order + 1
            );

            $pembayaran_status_detail_after = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_after, true);

            $data_status_after = array(
                'status'           => 12,
                'modified_by'      => $user,
                'modified_date'    => date('Y-m-d H:i:s')
            );      

            if(count($pembayaran_status_detail_after) != 0){
                $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
            }

            $wheres_status = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_permintaan,
            );    

            $update_status = $this->pembayaran_status_m->update_by($user_level_id,$data_status_after,$wheres_status);  

            //proses save ke history
            $get_data_persetujuan = $this->persetujuan_permintaan_biaya_m->get_by(array('permintaan_biaya_id' => $array_input['permintaan_biaya_id'], 'permintaan_biaya_bon_id IS NULL' ));
            $data_persetujuan = object_to_array($get_data_persetujuan);

            foreach ($data_persetujuan as $row) 
            {
                
                $data_history = array(

                    'permintaan_biaya_id' => $row['permintaan_biaya_id'],
                    'user_level_id'       => $row['user_level_id'],
                    '`order`'             => $row['order'],
                    '`status`'            => $row['status'],
                    'tanggal_baca'        => $row['tanggal_baca'],
                    'dibaca_oleh'         => $row['dibaca_oleh'],
                    'tanggal_persetujuan' => $row['tanggal_persetujuan'],
                    'disetujui_oleh'      => $row['disetujui_oleh'],
                    'nominal'             => $row['nominal'],
                    'keterangan'          => $row['keterangan']

                );

                $history_persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_history_m->save($data_history);
                
                $wheres_delete = array(
                   'persetujuan_permintaan_biaya_id' =>  $row['persetujuan_permintaan_biaya_id']
                );
                $delete = $this->persetujuan_permintaan_biaya_m->delete_by($wheres_delete);
                // die_dump($this->db->last_query());
            }
        }
        

        if ($update) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Permintaan Disetujui.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('keuangan/persetujuan_permintaan_biaya');

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

/* End of file permintaan_biaya.php */
/* Location: ./application/controllers/keuangan/Permintaan_biaya.php */