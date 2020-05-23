<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_kirim_petty_cash extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '66201c7488f0937757eea480777ec069';                  // untuk check bit_access

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

        $this->load->model('keuangan/proses_terima_setoran/titip_setoran_m');
        $this->load->model('keuangan/proses_terima_setoran/titip_setoran_detail_m');
        $this->load->model('keuangan/kirim_petty_cash/setoran_keuangan_kasir_m');
        $this->load->model('keuangan/kirim_petty_cash/persetujuan_permintaan_setoran_keuangan_m');
        $this->load->model('keuangan/kirim_petty_cash/persetujuan_permintaan_setoran_keuangan_history_m');
        $this->load->model('keuangan/proses_terima_setoran/permintaan_biaya_m');
        $this->load->model('keuangan/proses_terima_setoran/permintaan_biaya_bon_m');
        $this->load->model('master/bank_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/persetujuan_kirim_petty_cash/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Persetujuan Pengiriman Petty Cash', $this->session->userdata('language')), 
            'header'         => translate('Persetujuan Pengiriman Petty Cash', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_kirim_petty_cash/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
    
    public function history()
    {
        $assets = array();
        $config = 'assets/keuangan/persetujuan_kirim_petty_cash/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('History Persetujuan Permintaan Biaya', $this->session->userdata('language')), 
            'header'         => translate('History Persetujuan Permintaan Biaya', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_kirim_petty_cash/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        
        $user_level_id = $this->session->userdata('level_id');

        $result = $this->persetujuan_permintaan_setoran_keuangan_m->get_datatable($user_level_id);

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

            $data_order = $this->persetujuan_permintaan_setoran_keuangan_m->data_order($row['setoran_keuangan_kasir_id'], $row['order_persetujuan'])->result_array();
            $action = '';   

            foreach ($data_order as $order) 
            {
                if($order['order'] == 1 && $order['user_level_id'] == $user_level_id)
                {
                    $action = '
                               <a title="'.translate('Finish Process', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/persetujuan_kirim_petty_cash/proses/'.$row['setoran_keuangan_kasir_id'].'/'.$row['order_persetujuan'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                } 

                elseif ($order['user_level_id'] == $user_level_id && $order['order'] != 1 ) 
                {
                    $data_order2 = $this->persetujuan_permintaan_setoran_keuangan_m->data_order2($order['setoran_keuangan_kasir_id'], $order['order'])->result_array();
                    
                    foreach ($data_order2 as $order2) 
                    {
                        if($order2['status'] == 4 || $order2['status'] == 3)
                        {

                            $action = '
                                       <a title="'.translate('Finish Process', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/persetujuan_kirim_petty_cash/proses/'.$row['setoran_keuangan_kasir_id'].'/'.$row['order_persetujuan'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                        } else {

                            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_kirim_petty_cash/view/'.$row['setoran_keuangan_kasir_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                        }
                    }
                }

                if($order['order'] == 1 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_kirim_petty_cash/view/'.$row['setoran_keuangan_kasir_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                } elseif ($order['order'] == 2 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_kirim_petty_cash/view/'.$row['setoran_keuangan_kasir_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                } elseif ($order['order'] == 3 && $order['user_level_id'] == $user_level_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_kirim_petty_cash/view/'.$row['setoran_keuangan_kasir_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';
                }
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="input-right">'.formatrupiah($row['total_setor']).'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function listing_add_detail_setoran_biaya($titip_setoran_id)
    {

        $result = $this->titip_setoran_detail_m->get_datatable($titip_setoran_id);

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
        $total = 0;
        $input_total = '';
        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            $total = $total + $row['nominal_setujui'];

            if($i == ($count-1)){
                $input_total = '<input type="hidden" id="total_biaya" name="total_biaya" value="'.$total.'">';
            }
            // PopOver Notes
            $notes    = $row['keperluan'];
        
            $output['aaData'][] = array(
                '<div class="text-center"><input type="hidden" name="biaya['.$i.'][id]" value="'.$row['id'].'"><input type="hidden" name="biaya['.$i.'][tanggal]" value="'.$row['tanggal'].'">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-right"><input type="hidden" name="biaya['.$i.'][rupiah]" value="'.$row['nominal_setujui'].'"><a href="'.base_url().'keuangan/persetujuan_kirim_petty_cash/modal_detail/'.$row['id'].'" data-toggle="modal" data-target="#popup_modal">'.formatrupiah($row['nominal_setujui']).$input_total.'</a></div>',
                '<div class="text-left">'.$notes.'</div>'

            );
        
            $i++;
        
        }

        echo json_encode($output);
    }

    public function listing_history()
    {
        
        $user_level_id = $this->session->userdata('level_id');
        $result = $this->persetujuan_kirim_petty_cash_history_m->get_datatable($user_level_id);

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

            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/persetujuan_kirim_petty_cash/view_history/'.$row['setoran_keuangan_kasir_id'].'/'.$row['order_persetujuan'].'"  class="btn btn-xs default"><i class="fa fa-search"></i></a>';

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
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function proses($id, $order)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/persetujuan_kirim_petty_cash/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $user_id = $this->session->userdata('user_id');
        $user_level_id = $this->session->userdata('level_id');

        $form_data = $this->setoran_keuangan_kasir_m->get($id);
        $data_setoran = $this->titip_setoran_m->get_by(array('id' => $form_data->titip_setoran_id), true);
        $data_setoran_detail = $this->titip_setoran_detail_m->get_by(array('titip_setoran_id' => $form_data->titip_setoran_id));


        $form_data_persetujuan = $this->persetujuan_permintaan_setoran_keuangan_m->get_by(array('setoran_keuangan_kasir_id' => $id, '`order`' => $order), true);
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
                'setoran_keuangan_kasir_id' => $data_persetujuan['setoran_keuangan_kasir_id'],
                'user_level_id' => $user_level_id,
            );

            $update = $this->persetujuan_permintaan_setoran_keuangan_m->update_by($user_id,$update_status, $wheres_setujui);
        }

        if($form_data->status <= 2){
            $data_minta = array(
                'status' => 2
            );

            $this->setoran_keuangan_kasir_m->save($data_minta, $id);
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
            'content_view'          => 'keuangan/persetujuan_kirim_petty_cash/proses',
            'flag'                  => 'proses',
            'pk_value'              => $id,
            'order'                 => $order,
            'form_data'             => object_to_array($form_data),
            'data_setoran'   => (count($data_setoran) != 0)?object_to_array($data_setoran):'',
            'data_setoran_detail'   => (count($data_setoran_detail) != 0)?object_to_array($data_setoran_detail):'',
            'form_data_persetujuan' => object_to_array($form_data_persetujuan),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id, $order)
    {

        $assets = array();
        $assets_config = 'assets/keuangan/persetujuan_kirim_petty_cash/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->setoran_keuangan_kasir_m->get($id);
        
        
        $form_persetujuan = $this->persetujuan_permintaan_setoran_keuangan_m->get_by(array('setoran_keuangan_kasir_id' => $id, '`order`' => $order), true);
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
                'setoran_keuangan_kasir_id' => $data_persetujuan['setoran_keuangan_kasir_id'],
                'user_level_id' => $user_level_id,
            );

            $update = $this->persetujuan_permintaan_setoran_keuangan_m->update_by($user_id, $update_status, $wheres_setujui);

        }

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("View Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header'         => translate("View Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/persetujuan_kirim_petty_cash/view',
            'flag'           => 'view',
            'pk_value'              => $id,
            'order'                 => $order,
            'form_data'      => object_to_array($form_data),
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
        $assets_config = 'assets/keuangan/persetujuan_kirim_petty_cash/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->setoran_keuangan_kasir_m->get($id);
        
        
        $form_persetujuan = $this->persetujuan_kirim_petty_cash_history_m->get_by(array('setoran_keuangan_kasir_id' => $id, '`order`' => $order), true);
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
            'content_view'   => 'keuangan/persetujuan_kirim_petty_cash/view_history',
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
        $data_persetujuan = $this->persetujuan_permintaan_setoran_keuangan_m->get_data_full($permintaan_id);
        $data = array(
            'data_setuju' => $data_persetujuan
        );

        $this->load->view('keuangan/persetujuan_kirim_petty_cash/modal/modal_view', $data);
    }

    public function modal_view_history($permintaan_id)
    {
        $data_persetujuan = $this->persetujuan_kirim_petty_cash_history_m->get_data_full($permintaan_id);
        $data = array(
            'data_setuju' => $data_persetujuan
        );

        $this->load->view('keuangan/persetujuan_kirim_petty_cash/modal/modal_view', $data);
    }

    public function reject_proses($persetujuan_kirim_petty_cash_id, $setoran_keuangan_kasir_id, $order)
    {
        $data = array( 
            'persetujuan_permintaan_setoran_keuangan_id' => $persetujuan_kirim_petty_cash_id,
            'setoran_keuangan_kasir_id'             => $setoran_keuangan_kasir_id,
            'order'                           => $order,
        );

        $this->load->view('keuangan/persetujuan_kirim_petty_cash/modal/modal_proses', $data);
    }

    
    public function tolak_permintaan()
    {
        $array_input = $this->input->post();
        $user        = $this->session->userdata('user_id');
        $date        = date('Y-m-d H:i:s');
        $user_level_id = $this->session->userdata('level_id');
        
        $data = array(
            'status'              => 4,
            'keterangan'          => $array_input['keterangan_tolak'],
            'tanggal_persetujuan' => $date,
            'disetujui_oleh'      => $user,
            'nominal'             => 0
        );

        $wheres_update_last = array(
            'setoran_keuangan_kasir_id' => $array_input['setoran_keuangan_kasir_id'],
        );

        $update = $this->persetujuan_permintaan_setoran_keuangan_m->update_by($user,$data, $wheres_update_last);

        $data_permintaan = array(
            'status'          => 3,
            'nominal_setujui' => 0
        );           
        $update_permintaan = $this->setoran_keuangan_kasir_m->save($data_permintaan, $array_input['setoran_keuangan_kasir_id']);    
        //proses save ke history
        $get_data_persetujuan = $this->persetujuan_permintaan_setoran_keuangan_m->get_by(array('setoran_keuangan_kasir_id' => $array_input['setoran_keuangan_kasir_id']));
        $data_persetujuan = object_to_array($get_data_persetujuan);

        foreach ($data_persetujuan as $row) 
        {          
            $data_history = array(

                'setoran_keuangan_kasir_id' => $row['setoran_keuangan_kasir_id'],
                'user_level_id'       => $row['user_level_id'],
                '`order`'             => $row['order'],
                '`status`'            => $row['status'],
                'tanggal_baca'        => $row['tanggal_baca'],
                'dibaca_oleh'         => $row['dibaca_oleh'],
                'tanggal_persetujuan' => $row['tanggal_persetujuan'],
                'disetujui_oleh'      => $row['disetujui_oleh'],
                'keterangan'          => $row['keterangan'],
            );

            $history_persetujuan_kirim_petty_cash_id = $this->persetujuan_kirim_petty_cash_history_m->save($data_history);
            $delete = $this->persetujuan_permintaan_setoran_keuangan_m->delete_id($row['persetujuan_kirim_petty_cash_id']);
        }
         
        

        if ($update || $history_persetujuan_kirim_petty_cash_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data Permintaan Ditolak.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('keuangan/persetujuan_kirim_petty_cash');

    }

    public function save()
    {

        // $command = $this->input->post('command');
        $array_input   = $this->input->post();
        // die_dump($array_input);
        $date          = date('Y-m-d H:i:s');
        $user          = $this->session->userdata('user_id');
        $user_level_id = $this->session->userdata('level_id');
        
        
        $get_order_max = $this->persetujuan_permintaan_setoran_keuangan_m->get_max_order($array_input['setoran_keuangan_kasir_id'])->row(0);
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
                'persetujuan_kirim_petty_cash_id' => $array_input['persetujuan_kirim_petty_cash_id'],
                'setoran_keuangan_kasir_id'             => $array_input['setoran_keuangan_kasir_id'],
                'user_level_id'                   => $user_level_id,
            );

            $update = $this->persetujuan_permintaan_setoran_keuangan_m->update_by($user,$data, $wheres_update);

            $data_setoran = array(
                'nominal_setujui' => $array_input['nominal_setujui'],
                'disetujui_oleh' => $this->session->userdata('user_id')
            );

            $update_setoran = $this->setoran_keuangan_kasir_m->save($data_setoran, $array_input['setoran_keuangan_kasir_id']);


            // die_dump($this->db->last_query());
        } else {

            $data = array(

                'status'              => 3,
                'tanggal_persetujuan' => $date,
                'disetujui_oleh'      => $user,
                'nominal'             => $array_input['nominal_setujui']
            );

            $wheres_update_last = array(
                'persetujuan_permintaan_setoran_keuangan_id' => $array_input['persetujuan_setoran_keuangan_kasir_id'],
                'setoran_keuangan_kasir_id' => $array_input['setoran_keuangan_kasir_id'],
                'user_level_id' => $user_level_id,
            );

            $update = $this->persetujuan_permintaan_setoran_keuangan_m->update_by($user,$data, $wheres_update_last);

            $data_permintaan = array(
                'status'          => 2,
                'nominal_setujui' => $array_input['nominal_setujui'],
                'disetujui_oleh' => $this->session->userdata('user_id')
            );

            $update_setoran = $this->setoran_keuangan_kasir_m->save($data_permintaan, $array_input['setoran_keuangan_kasir_id']);
            // die_dump($this->db->last_query());
            
            //proses save ke history
            $get_data_persetujuan = $this->persetujuan_permintaan_setoran_keuangan_m->get_by(array('setoran_keuangan_kasir_id' => $array_input['setoran_keuangan_kasir_id']));
            $data_persetujuan = object_to_array($get_data_persetujuan);

            foreach ($data_persetujuan as $row) 
            {
                
                $data_history = array(

                    'setoran_keuangan_kasir_id' => $row['setoran_keuangan_kasir_id'],
                    'user_level_id'       => $row['user_level_id'],
                    '`order`'             => $row['order'],
                    '`status`'              => $row['status'],
                    'tanggal_baca'        => $row['tanggal_baca'],
                    'dibaca_oleh'         => $row['dibaca_oleh'],
                    'tanggal_persetujuan' => $row['tanggal_persetujuan'],
                    'disetujui_oleh'      => $row['disetujui_oleh'],
                    'keterangan'          => $row['keterangan'],

                );

                $history_persetujuan_kirim_petty_cash_id = $this->persetujuan_permintaan_setoran_keuangan_history_m->save($data_history);
                
                $wheres_delete = array(
                   'persetujuan_permintaan_setoran_keuangan_id' =>  $row['persetujuan_permintaan_setoran_keuangan_id']
                );
                $delete = $this->persetujuan_permintaan_setoran_keuangan_m->delete_by($wheres_delete);
                // die_dump($this->db->last_query());

            }
        }
        

        if ($update || $history_persetujuan_kirim_petty_cash_id) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Permintaan Disetujui.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('keuangan/persetujuan_kirim_petty_cash');

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
        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));

        $data = array(
            'id' => $id,
            'form_data_bon' => object_to_array($form_data_bon)
        );
        $this->load->view('keuangan/proses_terima_setoran/modal_detail',$data);
    }
}

/* End of file permintaan_biaya.php */
/* Location: ./application/controllers/keuangan/Permintaan_biaya.php */