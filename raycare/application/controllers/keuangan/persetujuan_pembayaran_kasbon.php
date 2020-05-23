<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_pembayaran_kasbon extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'b5318327ed7c20072841b0773b57e93f';                  // untuk check bit_access

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
        $this->load->model('keuangan/pengajuan_pembayaran_kasbon/persetujuan_pengajuan_pembayaran_kasbon_history_m');
        $this->load->model('keuangan/daftar_kasbon/permintaan_biaya_m');

        $this->load->model('master/bank_m');
        $this->load->model('master/user_level_persetujuan_m');

        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        //$url_encoded = urlencode(base64_encode('http://simrhs.com/raycare/keuangan/persetujuan_pembayaran_kasbon'));
        //die(dump($url_encoded));
        $assets = array();
        $config = 'assets/keuangan/persetujuan_pembayaran_kasbon/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Persetujuan Pembarayan Biaya', $this->session->userdata('language')), 
            'header'         => translate('Persetujuan Pembarayan Biaya', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_pembayaran_kasbon/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function history()
    {
        $assets = array();
        $config = 'assets/keuangan/persetujuan_pembayaran_kasbon/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Persetujuan Pembarayan Biaya', $this->session->userdata('language')), 
            'header'         => translate('History Persetujuan Pembarayan Biaya', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_pembayaran_kasbon/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        $user_level_id = $this->session->userdata('level_id');

        $result = $this->persetujuan_pengajuan_pembayaran_kasbon_m->get_datatable($user_level_id);

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

            $order = '';

            $data_order = $this->persetujuan_pengajuan_pembayaran_kasbon_m->data_order($row['pengajuan_pembayaran_kasbon_id'], $row['order_persetujuan'])->result_array();
            $action = '';   

            foreach ($data_order as $order) 
            {
                if($order['order'] == 1 && $order['user_level_id'] == $user_level_id)
                {
                    $action = '
                               <a title="'.translate('Finish Process', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/persetujuan_pembayaran_kasbon/proses/'.$row['pengajuan_pembayaran_kasbon_id'].'/'.$row['order_persetujuan'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                } 

                elseif ($order['user_level_id'] == $user_level_id && $order['order'] != 1 ) 
                {
                    $data_order2 = $this->persetujuan_pengajuan_pembayaran_kasbon_m->data_order2($order['pengajuan_pembayaran_kasbon_id'], $order['order'])->result_array();
                    
                    foreach ($data_order2 as $order2) 
                    {
                        if($order2['status'] == 4 || $order2['status'] == 3)
                        {

                            $action = '
                                       <a title="'.translate('Finish Process', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/persetujuan_pembayaran_kasbon/proses/'.$row['pengajuan_pembayaran_kasbon_id'].'/'.$row['order_persetujuan'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                        } else {

                            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_pembayaran_kasbon/view/'.$row['pengajuan_pembayaran_kasbon_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                        }
                    }
                }

                if($order['order'] == 1 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_pembayaran_kasbon/view/'.$row['pengajuan_pembayaran_kasbon_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                } elseif ($order['order'] == 2 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_pembayaran_kasbon/view/'.$row['pengajuan_pembayaran_kasbon_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                } elseif ($order['order'] == 3 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_pembayaran_kasbon/view/'.$row['pengajuan_pembayaran_kasbon_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                }
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="input-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);
    }

    public function listing_history()
    {
        $user_level_id = $this->session->userdata('level_id');
        $result = $this->persetujuan_pengajuan_pembayaran_kasbon_history_m->get_datatable($user_level_id);

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

            $status = '';
            if($row['status_persetujuan'] == 3){

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';
            } elseif($row['status_persetujuan'] == 4){

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            }

            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_pembayaran_kasbon/view_history/'.$row['pengajuan_pembayaran_kasbon_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="input-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-left">'.$status.'</div>',
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
                $tipe = 'Rembes';
            }
            $nominal = formatrupiah($row['nominal']);
            if($row['tipe'] == 2){
                $nominal = '<a class="detail-nominal" href="'.base_url().'keuangan/pengajuan_pembayaran_kasbon/modal_detail/'.$row['id'].'" data-target="#modal_detail" data-toggle="modal">'.formatrupiah($row['nominal']).'</a>';
            }

            $output['aaData'][] = array(
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

    

    public function proses($id, $order)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/persetujuan_pembayaran_kasbon/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $user_id = $this->session->userdata('user_id');
        $user_level_id = $this->session->userdata('level_id');

        $form_data = $this->pengajuan_pembayaran_kasbon_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->pengajuan_pembayaran_kasbon_detail_m->get_data_detail($id)->result_array();


        $form_data_persetujuan = $this->persetujuan_pengajuan_pembayaran_kasbon_m->get_by(array('pengajuan_pembayaran_kasbon_id' => $id, '`order`' => $order), true);
        $data_persetujuan = object_to_array($form_data_persetujuan);

        $user_id = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');

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
                'pengajuan_pembayaran_kasbon_id' => $data_persetujuan['pengajuan_pembayaran_kasbon_id'],
                'user_level_id' => $user_level_id,
            );

            $update = $this->persetujuan_pengajuan_pembayaran_kasbon_m->update_by($user_id,$update_status, $wheres_setujui);
        }


        $data = array(
            'title'                 => config_item('site_name').' &gt;'. translate("Proses Persetujuan Pembayaran Kasbon", $this->session->userdata("language")), 
            'header'                => translate("Proses Persetujuan Pembayaran Kasbon", $this->session->userdata("language")), 
            'header_info'           => config_item('site_name'), 
            'breadcrumb'            => TRUE,
            'menus'                 => $this->menus,
            'menu_tree'             => $this->menu_tree,
            'css_files'             => $assets['css'],
            'js_files'              => $assets['js'],
            'content_view'          => 'keuangan/persetujuan_pembayaran_kasbon/proses',
            'flag'                  => 'proses',
            'pk_value'              => $id,
            'order'                 => $order,
            'form_data'             => object_to_array($form_data),
            'form_data_detail'   => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'form_data_persetujuan' => object_to_array($form_data_persetujuan),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id, $order)
    {

        $assets = array();
        $assets_config = 'assets/keuangan/persetujuan_pembayaran_kasbon/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->pengajuan_pembayaran_kasbon_m->get_by(array('id' => $id), true);
        
        
        $form_persetujuan = $this->persetujuan_pengajuan_pembayaran_kasbon_m->get_by(array('pengajuan_pembayaran_kasbon_id' => $id, '`order`' => $order), true);
        $data_persetujuan = object_to_array($form_persetujuan);

        $user_id = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');


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
                'pengajuan_pembayaran_kasbon_id' => $data_persetujuan['pengajuan_pembayaran_kasbon_id'],
                'user_level_id' => $user_level_id,
            );

            $update = $this->persetujuan_pengajuan_pembayaran_kasbon_m->update_by($user_id, $update_status, $wheres_setujui);

        }

        $data = array(
            'title'                 => config_item('site_name').' &gt;'. translate("View Persetujuan Pembayaran Kasbon", $this->session->userdata("language")), 
            'header'                => translate("View Persetujuan Pembayaran Kasbon", $this->session->userdata("language")), 
            'header_info'           => config_item('site_name'), 
            'breadcrumb'            => TRUE,
            'menus'                 => $this->menus,
            'menu_tree'             => $this->menu_tree,
            'css_files'             => $assets['css'],
            'js_files'              => $assets['js'],
            'content_view'          => 'keuangan/persetujuan_pembayaran_kasbon/view',
            'flag'                  => 'view',
            'pk_value'              => $id,
            'order'                 => $order,
            'form_data'             => object_to_array($form_data),
            'form_data_persetujuan' => object_to_array($form_persetujuan),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_history($id, $order)
    {

        $assets = array();
        $assets_config = 'assets/keuangan/persetujuan_pembayaran_kasbon/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $user_id = $this->session->userdata('user_id');
        $user_level_id = $this->session->userdata('level_id');

        $form_data = $this->pengajuan_pembayaran_kasbon_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->pengajuan_pembayaran_kasbon_detail_m->get_data_detail($id)->result_array();
        
        
        $form_persetujuan = $this->persetujuan_pengajuan_pembayaran_kasbon_history_m->get_by(array('pengajuan_pembayaran_kasbon_id' => $id, '`order`' => $order), true);
        $data_persetujuan = object_to_array($form_persetujuan);

        $user_id = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');



        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("View Persetujuan Pembayaran Kasbon History", $this->session->userdata("language")), 
            'header'         => translate("View Persetujuan Pembayaran Kasbon History", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_pembayaran_kasbon/view_history',
            'flag'           => 'view',
            'pk_value'              => $id,
            'order'                 => $order,
            'form_data'      => object_to_array($form_data),
            'form_data_detail'      => object_to_array($form_data_detail),
            'form_data_persetujuan' => object_to_array($form_persetujuan),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    

    public function save()
    {

        // $command = $this->input->post('command');
        $array_input   = $this->input->post();
        // die_dump($array_input);
        $date          = date('Y-m-d H:i:s');
        $user          = $this->session->userdata('user_id');
        $user_level_id = $this->session->userdata('level_id');
        
        
        $get_order_max = $this->persetujuan_pengajuan_pembayaran_kasbon_m->get_max_order($array_input['pengajuan_pembayaran_kasbon_id'])->row(0);
        //die_dump($get_order_max);
        if($array_input['order'] != $get_order_max->max_order)
        {
            // die_dump('bukan order terakhir');
            $data = array(
                'status'              => 3,
                'tanggal_persetujuan' => $date,
                'disetujui_oleh'      => $user,
                'nominal'             => $array_input['nominal_setujui']
            );

            $wheres_update = array(
                'id'                             => $array_input['persetujuan_pembayaran_kasbon_id'],
                'pengajuan_pembayaran_kasbon_id' => $array_input['pengajuan_pembayaran_kasbon_id'],
                'user_level_id'                  => $user_level_id,
            );

            $update = $this->persetujuan_pengajuan_pembayaran_kasbon_m->update_by($user,$data, $wheres_update);

            $data_pengajuan = array(
                'nominal_setujui' => $array_input['nominal_setujui'],
                'disetujui_oleh' => $this->session->userdata('user_id')
            );

            $update_setoran = $this->pengajuan_pembayaran_kasbon_m->edit_data($data_pengajuan, $array_input['pengajuan_pembayaran_kasbon_id']);


            foreach ($array_input['kasbon'] as $bon) {
                
                $data_bon['status'] = 8;

                $update_bon = $this->permintaan_biaya_m->save($data_bon, $bon['permintaan_biaya_id']);
            }
            // die_dump($this->db->last_query());
        } else {

            $data = array(
                'status'              => 3,
                'tanggal_persetujuan' => $date,
                'disetujui_oleh'      => $user,
                'nominal'             => $array_input['nominal_setujui']
            );

            $wheres_update_last = array(
                'id'                             => $array_input['persetujuan_pembayaran_kasbon_id'],
                'pengajuan_pembayaran_kasbon_id' => $array_input['pengajuan_pembayaran_kasbon_id'],
                'user_level_id'                  => $user_level_id,
            );

            $update = $this->persetujuan_pengajuan_pembayaran_kasbon_m->update_by($user,$data, $wheres_update_last);

            $data_pengajuan = array(
                'status'          => 2,
                'nominal_setujui' => $array_input['nominal_setujui'],
                'disetujui_oleh' => $this->session->userdata('user_id')
            );

            $update_setoran = $this->pengajuan_pembayaran_kasbon_m->edit_data($data_pengajuan, $array_input['pengajuan_pembayaran_kasbon_id']);
            // die_dump($this->db->last_query());

            foreach ($array_input['kasbon'] as $bon) {
                
                $data_bon['status'] = 8;

                $update_bon = $this->permintaan_biaya_m->save($data_bon, $bon['permintaan_biaya_id']);
            }
            
            //proses save ke history
            $get_data_persetujuan = $this->persetujuan_pengajuan_pembayaran_kasbon_m->get_by(array('pengajuan_pembayaran_kasbon_id' => $array_input['pengajuan_pembayaran_kasbon_id']));
            $data_persetujuan = object_to_array($get_data_persetujuan);

            foreach ($data_persetujuan as $row) 
            {
                
                $data_history = array(

                    'pengajuan_pembayaran_kasbon_id' => $row['pengajuan_pembayaran_kasbon_id'],
                    'user_level_id'       => $row['user_level_id'],
                    '`order`'             => $row['order'],
                    '`status`'              => $row['status'],
                    'tanggal_baca'        => $row['tanggal_baca'],
                    'dibaca_oleh'         => $row['dibaca_oleh'],
                    'tanggal_persetujuan' => $row['tanggal_persetujuan'],
                    'disetujui_oleh'      => $row['disetujui_oleh'],
                    'keterangan'          => $row['keterangan'],

                );

                $history_persetujuan_pembayaran_kasbon_id = $this->persetujuan_pengajuan_pembayaran_kasbon_history_m->save($data_history);
                
                $wheres_delete = array(
                   'id' =>  $row['id']
                );
                $delete = $this->persetujuan_pengajuan_pembayaran_kasbon_m->delete_by($wheres_delete);
                // die_dump($this->db->last_query());

            }
        }
        

        if ($update || $history_persetujuan_pembayaran_kasbon_id) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Permintaan Disetujui.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('keuangan/persetujuan_pembayaran_kasbon');

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

    public function reject_proses($persetujuan_pembayaran_kasbon_id, $pengajuan_pembayaran_kasbon_id, $order)
    {
        $data = array( 
            'persetujuan_pembayaran_kasbon_id' => $persetujuan_pembayaran_kasbon_id,
            'pengajuan_pembayaran_kasbon_id'             => $pengajuan_pembayaran_kasbon_id,
            'order'                           => $order,
        );

        $this->load->view('keuangan/persetujuan_pembayaran_kasbon/modal/modal_proses', $data);
    }

    public function tolak_permintaan()
    {
        $array_input = $this->input->post();
        $user        = $this->session->userdata('user_id');
        $date        = date('Y-m-d H:i:s');
        $user_level_id = $this->session->userdata('level_id');
        
        $data = array(
            'status'              => 4,
            'tanggal_persetujuan' => $date,
            'disetujui_oleh'      => $user,
            'nominal'             => $array_input['nominal_setujui'],
            'keterangan'             => $array_input['keterangan_tolak'],
        );

        $wheres_update_last = array(
            'pengajuan_pembayaran_kasbon_id' => $array_input['pengajuan_pembayaran_kasbon_id'],
        );

        $update = $this->persetujuan_pengajuan_pembayaran_kasbon_m->update_by($user,$data, $wheres_update_last);

        $data_pengajuan = array(
            'status'          => 3,
            'nominal_setujui' => 0,
            'disetujui_oleh'  => $this->session->userdata('user_id'),
            'keterangan_tolak'  => $array_input['keterangan_tolak'],
        );

        $update_setoran = $this->pengajuan_pembayaran_kasbon_m->edit_data($data_pengajuan, $array_input['pengajuan_pembayaran_kasbon_id']);
        // die_dump($this->db->last_query());
        $daftar_kasbon = $this->pengajuan_pembayaran_kasbon_detail_m->get_data_detail($array_input['pengajuan_pembayaran_kasbon_id'])->result_array();
        foreach ($daftar_kasbon as $bon) {
            
            $data_bon['status'] = 6;
            $data_bon['keterangan_tolak']  = $array_input['keterangan_tolak'];

            $update_bon = $this->permintaan_biaya_m->save($data_bon, $bon['permintaan_biaya_id']);
        }  
        //proses save ke history
        $get_data_persetujuan = $this->persetujuan_pengajuan_pembayaran_kasbon_m->get_by(array('pengajuan_pembayaran_kasbon_id' => $array_input['pengajuan_pembayaran_kasbon_id']));
        $data_persetujuan = object_to_array($get_data_persetujuan);

        foreach ($data_persetujuan as $row) 
        {
            
            $data_history = array(

                'pengajuan_pembayaran_kasbon_id' => $row['pengajuan_pembayaran_kasbon_id'],
                'user_level_id'       => $row['user_level_id'],
                '`order`'             => $row['order'],
                '`status`'              => $row['status'],
                'tanggal_baca'        => $row['tanggal_baca'],
                'dibaca_oleh'         => $row['dibaca_oleh'],
                'tanggal_persetujuan' => $row['tanggal_persetujuan'],
                'disetujui_oleh'      => $row['disetujui_oleh'],
                'keterangan'          => $row['keterangan'],

            );

            $history_persetujuan_pembayaran_kasbon_id = $this->persetujuan_pengajuan_pembayaran_kasbon_history_m->save($data_history);
            
            $wheres_delete = array(
               'id' =>  $row['id']
            );
            $delete = $this->persetujuan_pengajuan_pembayaran_kasbon_m->delete_by($wheres_delete);
            // die_dump($this->db->last_query());

        }
         
        if ($update || $history_persetujuan_pembayaran_kasbon_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data Permintaan Ditolak.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('keuangan/persetujuan_pembayaran_kasbon');

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

/* End of file persetujuan_pembayaran_kasbon.php */
/* Location: ./application/controllers/keuangan/persetujuan_pembayaran_kasbon.php */