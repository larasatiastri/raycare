<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_po extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd011654752eefa528b18f67a3b18bc64';                  // untuk check bit_access

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

        $this->load->model('pembelian/persetujuan_po/order_permintaan_barang_m');
        $this->load->model('pembelian/persetujuan_po/order_permintaan_barang_detail_m');
        $this->load->model('pembelian/persetujuan_po/order_permintaan_barang_detail_other_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_permintaan_barang_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_permintaan_barang_history_m');
        $this->load->model('pembelian/persetujuan_po/user_level_persetujuan_m');
        $this->load->model('pembelian/persetujuan_po/o_p_p_d_o_item_file_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_detail_m');
        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('pembelian/item_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_po_m');

        $this->load->model('pembelian/persetujuan_po/inventory_m');
        $this->load->model('pembelian/persetujuan_po/inventory_m');

        $this->load->model('pembelian/persetujuan_po/o_s_piutang_customer_m');
        
        $this->load->model('pembelian/persetujuan_po/draft_item_m');
        $this->load->model('pembelian/persetujuan_po/permintaan_pengepakan_m');
        $this->load->model('pembelian/persetujuan_po/permintaan_pengepakan_detail_m');



        $this->load->model('pembelian/permintaan_po/box_paket_m');
        $this->load->model('pembelian/permintaan_po/box_paket_detail_m');


        $this->load->model('pembelian/persetujuan_po/permintaan_item_baru_m');
        $this->load->model('pembelian/persetujuan_po/permintaan_item_baru_detail_m');


        $this->load->model('master/user_level_m');
        $this->load->model('master/user_m');


        $this->load->model('pembelian/daftar_permintaan_po_m');
        $this->load->model('others/kotak_sampah_m');

        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_poliklinik_m');
        $this->load->model('master/cabang_divisi_setting_m');

        $this->load->model('master/paket_m');
        $this->load->model('master/paket_item_m');
        $this->load->model('master/paket_tindakan_m');
        $this->load->model('master/paket_batch_m');
        $this->load->model('master/paket_batch_item_m');
        $this->load->model('master/paket_batch_tindakan_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/poliklinik_paket_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_gambar_m');
        $this->load->model('master/item/item_sub_kategori_m');

        $this->load->model('master/supplier/supplier_m');
        $this->load->model('master/supplier/supplier_tipe_pembayaran_m');
        $this->load->model('pembelian/supplier_harga_item_m');
        $this->load->model('pembelian/permintaan_po/supplier_item_m');

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/pembelian/persetujuan_permintaan_po/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Persetujuan Permintaan Barang', $this->session->userdata('language')), 
            'header'         => translate('Persetujuan Permintaan Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/persetujuan_permintaan_po/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history_po()
    {
        $assets = array();
        $config = 'assets/pembelian/persetujuan_permintaan_po/history_po';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Persetujuan Permintaan Barang', $this->session->userdata('language')), 
            'header'         => translate('History Persetujuan Permintaan Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/persetujuan_permintaan_po/history_po',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add_persetujuan($id, $id_user_level)
    {
        $assets = array();
        $config = 'assets/pembelian/persetujuan_permintaan_po/add';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $level_id = $this->session->userdata('level_id');

        $form_data         = $this->persetujuan_permintaan_barang_m->get_by(array('order_permintaan_barang_id' => $id, 'user_level_id' => $level_id));
        $data             = object_to_array($form_data);
        // die(dump($data));

        $data_order        = $this->order_permintaan_barang_m->get_by(array('id' => $id));
        $data_order_detail = $this->order_permintaan_barang_detail_m->get_by(array('order_permintaan_barang_id' => $id));

        $user_id = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');
        foreach ($data as $row) 
        {
            if($row['status'] != 4)
            {
                $update_status = array(
                    'status'        => 2,
                    'tanggal_baca'  => $date,
                    'dibaca_oleh'   => $user_id,
                    'modified_by'   => $user_id,
                    'modified_date' => $date,
                );

                $wheres = array(
                    'persetujuan_permintaan_barang_id'  => $row['persetujuan_permintaan_barang_id'],
                    'order_permintaan_barang_id'        => $row['order_permintaan_barang_id'],
                    'order_permintaan_barang_detail_id' => $row['order_permintaan_barang_detail_id']
                );

                $update = $this->persetujuan_permintaan_barang_m->update_by($user_id, $update_status, $wheres);      

            }    
        }

        $data_persetujuan = array(
            'status'       => 2,
            'tanggal_baca' => date('Y-m-d H:i:s'),
            'dibaca_oleh'  => $this->session->userdata('user_id')
        );

        $data_permintaan['status'] = 2;

        $edit_permintaan = $this->order_permintaan_barang_m->edit_data($data_permintaan,$id);

        $data_status = array(
            'status'        => 2,
            'modified_by'   => $user_id,
            'modified_date' => $date
        );      

        $wheres_status = array(
            'transaksi_id'   => $id,
            'tipe_transaksi' => 1
        );    

        $update_status = $this->permintaan_status_m->update_by($user_id,$data_status,$wheres_status);  

        $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);

        $wheres_bayar_detail = array(
            'transaksi_id'   => $id,
            'tipe_transaksi' => 1,
            'tipe_pengajuan' => 0,
            'tipe'           => 1,
            'user_level_id'  => $level_id
        );
        $permintaan_status_detail = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

        $wheres_bayar_detail_before = array(
            'transaksi_id'   => $id,
            'tipe_transaksi' => 1,
            'tipe_pengajuan' => 0,
            'tipe'           => 1,
            '`order`'        => $permintaan_status_detail->order - 1
        );

        $pembayaran_status_detail_before = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_before, true);

        if(count($pembayaran_status_detail_before) != 0){
            $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
        }else{
            $waktu_proses = $permintaan_status_id->created_date;
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

        $pembayaran_status_detail = $this->permintaan_status_detail_m->edit_data($data_pembayaran_status_detail, $permintaan_status_detail->id);

        if($form_data)
        {
            foreach ($form_data as $row_data) 
            {
                if($row_data->dibaca_oleh == NULL || $row_data->dibaca_oleh == 0)
                {
                    $wheres = array(
                        'persetujuan_permintaan_barang_id' => $row_data->persetujuan_permintaan_barang_id
                    );

                    $edit = $this->persetujuan_permintaan_barang_m->update_by($this->session->userdata('user_id'),$data_persetujuan,$wheres);
                }
            }
        }

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Persetujuan Permintaan Barang", $this->session->userdata("language")), 
            'header'         => translate("Persetujuan Permintaan Barang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/persetujuan_permintaan_po/add',
            'form_data'      => object_to_array($form_data),
            'data_order'     => object_to_array($data_order),
            'id_user_level'  => $id_user_level,                    
            'pk_value'       => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_history($id, $id_user_level)
    {
        
        $assets = array();
        $config = 'assets/pembelian/persetujuan_permintaan_po/view_history';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        ///////////////////////////////////data ppp//////////////////////////////

        $form_data        = $this->persetujuan_permintaan_barang_history_m->get_by(array('order_permintaan_barang_id' => $id));
        $data             = object_to_array($form_data);
        // die_dump($data);

        
        ///////////////////////////////data order permintaan pembelian///////////////////////////////////

        $data_order       = $this->order_permintaan_barang_m->get_by(array('id' => $id));
        // die_dump($this->db->last_query());       
        $order            = object_to_array($data_order);
        // die_dump($data_order);
        // die_dump($this->db->last_query());   
         
        /////////////////////////////data nama user dari ordder permintaan pembelian//////////////////////    

        $data_user        = $this->user_m->get_by(array('id' => $order[0]['user_id']));
        $user             = object_to_array($data_user);
        
        // $user_level_id = $this->user_level_m->get_by(array('id', $order[0]['user_level_id']));

        // die_dump($user_level_id);
        
        ///////////////////////////////data nama user level dari order permintaan pembelian////////////////////        

        $data_user_level  = $this->user_level_m->get_by(array('id' => $order[0]['user_level_id']));
        $user_level       = object_to_array($data_user_level);
        // die_dump($data_user_level);
         
        
        //////////////////////////////data nama cabang dari order permintaan pembelian//////////////////

        $data_cabang      = $this->cabang_m->get_by(array('id' => $order[0]['cabang_id']));
        $cabang           = object_to_array($data_cabang);

        //////////////////////////////

        $user_level_id    = $this->user_level_m->get_by(array('id' => $user[0]['user_level_id']));
        $level_id         = object_to_array($user_level_id);

        $user_id = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');
        
        $data = array(
            'title'          => config_item('site_name'). ' | '.translate("View History Persetujuan Permintaan PO", $this->session->userdata("language")), 
            'header'         => translate("View History Persetujuan Permintaan PO", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/persetujuan_permintaan_po/view',
            'form_data'      => object_to_array($form_data),
            'data_order'      => object_to_array($data_order),
            'data_user'       => object_to_array($data_user),
            'data_user_level' => object_to_array($data_user_level),
            'data_cabang'     => object_to_array($data_cabang),
            'id_user_level'   => $id_user_level,                         //table primary key value
            'pk_value'        => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id, $id_user_level)
    {
        $assets = array();
        $config = 'assets/pembelian/persetujuan_permintaan_po/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        ///////////////////////////////////data ppp//////////////////////////////
        $form_data        = $this->persetujuan_permintaan_barang_m->get_by(array('order_permintaan_barang_id' => $id));
        $data             = object_to_array($form_data);

        ///////////////////////////////data order permintaan pembelian///////////////////////////////////
        $data_order       = $this->order_permintaan_barang_m->get_by(array('id' => $id));
        $order            = object_to_array($data_order);

         
        /////////////////////////////data nama user dari ordder permintaan pembelian//////////////////////    
        $data_user        = $this->user_m->get_by(array('id' => $order[0]['user_id']));
        $user             = object_to_array($data_user);
           
        ///////////////////////////////data nama user level dari order permintaan pembelian////////////////////        
        $data_user_level  = $this->user_level_m->get_by(array('id' => $order[0]['user_level_id']));
        $user_level       = object_to_array($data_user_level);
        // die_dump($data_user_level);
         
        
        //////////////////////////////data nama cabang dari order permintaan pembelian//////////////////

        $data_cabang      = $this->cabang_m->get_by(array('id' => $order[0]['cabang_id']));
        $cabang           = object_to_array($data_cabang);

        //////////////////////////////

        $user_level_id    = $this->user_level_m->get_by(array('id' => $user[0]['user_level_id']));
        $level_id         = object_to_array($user_level_id);

        // die_dump($user_level_id);

        $user_id = $this->session->userdata('user_id');
        // die_dump($user_id);
        $date = date('Y-m-d H:i:s');

        foreach ($data as $row) 
        {
            if($row['status'] == 1 && $row['status'] != 4)
            {
                $update_status = array(

                    'status'        => 2,
                    'tanggal_baca'  => $date,
                    'dibaca_oleh'   => $user_id,
                    'modified_by'   => $user_id,
                    'modified_date' => $date,
                );

                $update = $this->persetujuan_permintaan_barang_m->update_status_p_p_p($update_status, $row['persetujuan_permintaan_barang_id'], $row['order_permintaan_barang_id'], $id_user_level);                    
            }
            
        }

        if($order[0]['status'] == 1)
        {
            $data_persetujuan = array(
                'status'       => 2,
                'tanggal_baca' => date('Y-m-d H:i:s'),
                'dibaca_oleh'  => $this->session->userdata('user_id')
            );

            $data_permintaan['status'] = 2;

            $edit_permintaan = $this->order_permintaan_barang_m->edit_data($data_permintaan,$id);
        }

        
        $data = array(
            'title'          => config_item('site_name'). ' | '.translate("View Persetujuan Permintaan Barang", $this->session->userdata("language")), 
            'header'         => translate("View Persetujuan Permintaan Barang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/persetujuan_permintaan_po/view',
            'form_data'      => object_to_array($form_data),
            'data_order'      => object_to_array($data_order),
            'data_user'       => object_to_array($data_user),
            'data_user_level' => object_to_array($data_user_level),
            'data_cabang'     => object_to_array($data_cabang),
            'id_user_level'   => $id_user_level,                         //table primary key value
            'pk_value'        => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
     public function listing()
    {        

        $user_id = $this->session->userdata('level_id');
        $result = $this->persetujuan_permintaan_barang_m->get_datatable($user_id);
        
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
            // die(dump($records));


        $i=0;
        foreach($records->result_array() as $row)
        {

            $order = '';

            $data_order = $this->persetujuan_permintaan_barang_m->data_order($row['order_permintaan_barang_id'], $row['p_p_p_order'])->result_array();
            // die(dump($data_order));

            $action = '';
            $jumlah = 0;

            foreach ($data_order as $order) 
            {

                if($order['order'] == 1 && $order['user_level_id'] == $user_id)
                {
                    $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>
                               <a title="'.translate('Proses', $this->session->userdata('language')).'"  href="'.base_url().'pembelian/persetujuan_permintaan_po/add_persetujuan/'.$row['id'].'/'.$row['user_level_id'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';

                } 

                elseif ($order['user_level_id'] == $user_id && ($order['order'] != 1 && $order['order'] != 2)) 

                {
                    // die(dump('2'));
                    $data_order2 = $this->persetujuan_permintaan_barang_m->data_order2($order['order_permintaan_barang_id'], $order['order'])->result_array();
                    // die_dump($this->db->last_query());
                    // die_dump($data_order2);
                    
                    foreach ($data_order2 as $order2) 
                    {
                        if($order2['status'] == 3 || $order2['status'] == 4)
                        {
                            $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>
                                       <a title="'.translate('Proses', $this->session->userdata('language')).'"  href="'.base_url().'pembelian/persetujuan_permintaan_po/add_persetujuan/'.$row['id'].'/'.$row['user_level_id'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';

                        } else {
                            $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

                        }
                    }
                }
                elseif ($order['user_level_id'] == $user_id && $order['order'] == 2) 

                {
                    $data_order2 = $this->persetujuan_permintaan_barang_m->data_order2($order['order_permintaan_barang_id'], $order['order'])->result_array();
                   
                    
                    foreach ($data_order2 as $order2) 
                    {

                        if($order2['status'] == 3 || $order2['status'] == 4)
                        {
                            $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>
                                       <a title="'.translate('Proses', $this->session->userdata('language')).'"  href="'.base_url().'pembelian/persetujuan_permintaan_po/add_persetujuan/'.$row['id'].'/'.$row['user_level_id'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';

                        } else {
                            $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

                        }
                    }
                }

                //kondisi jika salah satu user_level sudah memproses barang yang di minta//


                if($order['order'] == 1 && $order['user_level_id'] == $user_id && ($order['status'] == 3 || $order['status'] == 4))
                {
                    $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

                } 
                elseif ($order['order'] == 2 && $order['user_level_id'] == $user_id && ($order['status'] == 3 && $order['status'] != 4))
                {
                    $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';


                }elseif ($order['order'] == 3 && $order['user_level_id'] == $user_id && ($order['status'] == 3))
                {
                    $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

                }

                // die_dump($this->db->last_query());
            }
                
                // $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>
                           // <a title="'.translate('Proses', $this->session->userdata('language')).'"  href="'.base_url().'pembelian/persetujuan_permintaan_po/add_persetujuan/'.$row['id'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                
            $info = '';
             if($row['tipe'] == 1)
            {
             
                $item   = $this->item_m->get_item_order_permintaan_barang_detail($row['order_permintaan_barang_id'])->result_array();
                
                $jumlah = $row['jumlah_terdaftar'];
                $info   = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';

            }

            if($row['tipe'] == 2)
            {

                $item = $this->item_m->get_item_order_permintaan_barang_detail_other($row['order_permintaan_barang_id'])->result_array();
                // die_dump($this->db->last_query());    
                $jumlah = $row['jumlah_tidak_terdaftar'];
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="item-unlist" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';


            }

            // $info = '<a title="'.translate('Order Item', $this->session->userdata('language')).'" name="info[]" class="pilih-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';

            // PopOver Notes
            $preNotes = $row['keterangan'];

            if(strlen($row['keterangan'] > 10))
            {
                $notes = $row['keterangan'];
              
                $words = explode(' ', $notes);
              
                $impWords = implode(' ', array_splice($words, 0, 6));
                
                $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';

            }
            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['user'].' ('.$row['user_level'].')',
                $row['subjek'],
                $info,
                $preNotes,
                '<div class="text-center inline-button-table">'.$action.'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

     public function listing_history_po()
    {        

        $user_id = $this->session->userdata('level_id');
        // die_dump($user_id);
        // die_dump($this->db->last_query());
        $result = $this->persetujuan_permintaan_barang_history_m->get_datatable($user_id);
        // die_dump($result);
        
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {

            $order = '';

            $data_order = $this->persetujuan_permintaan_barang_m->data_order($row['order_permintaan_barang_id'], $row['p_p_p_order'])->result_array();
            // die_dump($this->db->last_query());
            // die_dump($data_order);

            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view_history/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

                // $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/persetujuan_permintaan_po/view/'.$row['order_permintaan_barang_id'].'/'.$row['user_level_id'].'"  class="btn default"><i class="fa fa-search"></i></a>
                           // <a title="'.translate('Proses', $this->session->userdata('language')).'"  href="'.base_url().'pembelian/persetujuan_permintaan_po/add_persetujuan/'.$row['id'].'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                
            $info = '';
             if($row['tipe'] == 1)
            {
             
                $item   = $this->item_m->get_item_order_permintaan_barang_detail($row['order_permintaan_barang_id'])->result_array();
                
                $jumlah = $row['jumlah_terdaftar'];
                $info   = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';

            }

            if($row['tipe'] == 2)
            {

                $item = $this->item_m->get_item_order_permintaan_barang_detail_other($row['order_permintaan_barang_id'])->result_array();
                // die_dump($this->db->last_query());    
                $jumlah = $row['jumlah_tidak_terdaftar'];
                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="item-unlist" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';


            }

            // $info = '<a title="'.translate('Order Item', $this->session->userdata('language')).'" name="info[]" class="btn btn-primary pilih-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';

            // PopOver Notes
            $preNotes = $row['keterangan'];

            if(strlen($row['keterangan'] > 10))
            {
                $notes = $row['keterangan'];
              
                $words = explode(' ', $notes);
              
                $impWords = implode(' ', array_splice($words, 0, 6));
                
                $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            }

            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['user'].' ('.$row['user_level'].')',
                $row['subjek'],
                $info,
                $preNotes,
                '<div class="text-center inline-button-table">'.$action.'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_box_paket($id=null, $user_level_id = null)
    {
        
        $result = $this->order_permintaan_barang_detail_m->get_datatable_box_paket($id, $user_level_id);
        // die_dump($this->db->last_query());   
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
        $i=0;
        $status = '';
        $count = count($records->result_array());
        foreach($records->result_array() as $row)
        {

            if ($row['status_ppp'] == 3 || $row['status_ppp'] == 4) {

                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_terdaftar_user[]" class="btn btn-primary pilih-item-terdaftar-user" data-id="'.$row['order_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;">'.$row['jumlah_persetujuan'].'</a>';

            } else {

                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" class="btn green-haze" style="margin:0px; text-align:right;">'.$row['jumlah_persetujuan'].'</a>';

            }

                // $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_terdaftar_user[]" class="btn btn-primary pilih-item-terdaftar-user" data-id="'.$row['order_permintaan_barang_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

            $in_count  = '<input class="form-control input-sm hidden" id="count_terdaftar" value="'.$i.'">';

            $checked = '';

            if($row['status_ppp'] == 4)
            {

                $checked = "checked";

            } elseif ($row['status_ppp'] != 4) {

                $checked = "";
            }

            $item = $this->item_m->get_item_order_permintaan_barang_detail_box($row['order_permintaan_barang_id'], $row['box_paket_id'])->result_array();
            // die_dump($item);
            $item_box = '<a title="'.translate('Item Box', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="btn btn-primary pilih-item-box" data-id="'.$row['id'].'" name="item_box"   style="margin:0px;"><i class="fa fa-briefcase"></i></a>';

            // die_dump($checked);

            // die_dump($checked);

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                // '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="row" style="margin-left : 0px !important; margin-right : 0px !important">
                    <div class="col-xs-10 text-left" style="text-align:left; padding-left : 0px !important; padding-right : 0px !important;">
                        '.$row['nama_box'].'
                    </div>
                    <div class="col-xs-2" style="text-align:right; padding-left : 0px !important; padding-right : 0px !important;">
                        <span class="input-group-button">'.$item_box.'</span>
                    </div>
                </div>',
                '<div class="text-center">'.$row['jumlah_pesan'].' '.$row['nama_satuan'].'</div>',
                '<div class="text-left">'.$info.'</div>',
                // '<div class="text-center">'.$row['nama_satuan_ppp'].'</div>',
                '<div class="text-center"><input class="checkboxes" name="item['.$i.'][checkbox]" '.$checked.' id="checkbox_'.$i.'" type="checkbox" data-status="'.$row['status_ppp'].'" data-rp="'.$row['persetujuan_permintaan_barang_id'].'"></div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_item($id=null, $user_level_id = null)
    {
        
        $result = $this->order_permintaan_barang_detail_m->get_datatable($id, $user_level_id);
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
        $status = '';
        $order = '';
        $count = count($records->result_array());
        foreach($records->result_array() as $row)
        {
            
            $data_order_view = $this->persetujuan_permintaan_barang_m->get_data_order($row['id'], $row['order_permintaan_barang_id'], $row['user_level_id'])->result_array();
            // die_dump($data_order_view);

            if(count($data_order_view))
            {

                if ($row['status_ppp'] == 2 && $data_order_view[0]['status_view'] == 3 || $row['status_ppp'] == 2 && $data_order_view[0]['status_view'] == 4) {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="pilih-item-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;">'.$row['jumlah_persetujuan'].'</a>';

                } else {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="pilih-item-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;">'.$row['jumlah_persetujuan'].'</a>';

                }

            } else {

                 if ($row['status_ppp'] == 3 || $row['status_ppp'] == 4 ) {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="pilih-item-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;">'.$row['jumlah_persetujuan'].'</a>';

                } else {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="pilih-item-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;">'.$row['jumlah_persetujuan'].'</a>';

                }

            }
                // $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_terdaftar_user[]" class="btn btn-primary pilih-item-terdaftar-user" data-id="'.$row['order_permintaan_barang_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

            $in_count  = '<input class="form-control input-sm hidden" id="count_terdaftar" value="'.$i.'">';

            $checked = '';

            if($row['status_ppp'] == 4)
            {

                $checked = "checked";

            } elseif ($row['status_ppp'] != 4) {

                $checked = "";
            }

            // die_dump($checked);

            // die_dump($checked);

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama_item'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['nama_satuan'].'</div>',
                '<div class="text-left">'.$info.'</div>',
                '<div class="text-center">'.$row['nama_satuan_ppp'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga_ref']).'</div>',
                '<div class="text-left">'.$row['nama_supp'].' ['.$row['kode_supp'].']</div>',
                '<div class="text-center"><input class="checkboxes" name="item['.$i.'][checkbox]" '.$checked.' id="checkbox_'.$i.'" type="checkbox" data-status="'.$row['status_ppp'].'" data-rp="'.$row['persetujuan_permintaan_barang_id'].'" disabled></div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_history($id=null, $user_level_id = null)
    {
        
        $result = $this->persetujuan_permintaan_barang_history_m->get_datatable_view_terdaftar($id, $user_level_id);
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
        $i=0;
        $status = '';
        $order = '';
        $count = count($records->result_array());
        foreach($records->result_array() as $row)
        {
            
            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="pilih-item-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_history_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;">'.$row['jumlah_persetujuan'].'</a>';


            $in_count  = '<input class="form-control input-sm hidden" id="count_terdaftar" value="'.$i.'">';

            $checked = '';

            if($row['status_ppp'] == 3)
            {

                $checked = "checked";

            } elseif ($row['status_ppp'] != 3) {

                $checked = "";
            }

            // die_dump($checked);

            // die_dump($checked);

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama_item'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['nama_satuan'].'</div>',
                '<div class="text-left">'.$info.'</div>',
                '<div class="text-center">'.$row['nama_satuan_ppp'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga_ref']).'</div>',
                '<div class="text-left">'.$row['nama_supp'].' ['.$row['kode_supp'].']</div>',
                '<div class="text-center"><input class="checkboxes" name="item['.$i.'][checkbox]" '.$checked.' id="checkbox_'.$i.'" type="checkbox" data-status="'.$row['status_ppp'].'" data-rp="'.$row['persetujuan_permintaan_barang_history_id'].'" disabled></div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_tidak_terdaftar($id=null, $user_level_id)
    {
        
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_view_tidak_terdaftar($id, $user_level_id);
        // die_dump($result);
        // die_dump($this->db->last_query());
        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        $count = count($records->result_array());
        foreach($records->result_array() as $row)
        {

            $info       = '';

            $data_order_view = $this->persetujuan_permintaan_barang_m->get_data_order($row['id'], $row['order_permintaan_barang_id'], $row['user_level_id'])->result_array();
            // die_dump($data_order_view);

            if(count($data_order_view))
            {

                if ($row['status_ppp'] == 2 && $data_order_view[0]['status_view'] == 3 || $row['status_ppp'] == 2 && $data_order_view[0]['status_view'] == 4) {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

                } else {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

                }

            } else {

                if ($row['status_ppp'] == 3 || $row['status_ppp'] == 4) {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;">'.$row['jumlah_persetujuan'].'</a>';

                } else {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

                }

            }

            $link_pdf = '';
            $link_img = '';
            $data_pdf = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $row['id'], 'tipe' => 1 ), true);
            $data_img = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $row['id'], 'tipe' => 2 ), true);

            if(count($data_pdf))
            {
                $link_pdf = '<a target="_blank" href="'.base_url().'assets/mb/pages/pembelian/permintaan_po/doc/'.$row['nama'].'/'.$data_pdf->url.'" class="btn grey-cascade unggah-file" name="item2[{0}][file]" title="Lihat File"><i class="fa fa-file"></i></a>';
            }
            if(count($data_img))
            {
                $link_img = '<button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'pembelian/persetujuan_permintaan_po/lihat_gambar/'.$row['id'].'" class="btn blue-chambray unggah-gambar name="item2[{0}][gambar]" title="Lihat Gambar"><i class="fa fa-image"></i></button>';
            }

            


            $in_count   = '<input class="form-control input-sm hidden" id="count" value="'.$i.'">';

            $action     = $link_pdf.$link_img;


            $checked = '';

            if($row['status_ppp'] == 4)
            {

                $checked = "checked";

            } elseif ($row['status_ppp'] != 4) {

                $checked = "";
            }

            $harga_ref = ($row['harga_ref'] == '')?0:$row['harga_ref'];

            // die_dump($checked);

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
                '<div class="row" style="margin-left : 0px !important; margin-right : 0px !important">
                    <div class="col-xs-6 style="text-align:center; padding-left : 0px !important; padding-right : 0px !important; >
                        <input type="text" value="'.$row['jumlah_persetujuan'].'" readonly style="background-color: transparent;border: 0px solid; text-align: center;">
                    </div>
                    <div class="col-xs-4" style="text-align:right; padding-left : 0px !important; padding-right : 0px !important;">
                        <span class="input-group-button">'.$info.'</span>
                    </div>
                </div>',
                '<div class="text-left">'.$row['satuan'].'</div>',
                '<div class="text-right">'.formatrupiah($harga_ref).'</div>',
                '<div class="text-right">'.$row['supplier'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                '<div class="text-center"><input class="checkboxes" '.$checked.' name="item['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['persetujuan_permintaan_barang_id'].'"></div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_tidak_terdaftar_history($id=null, $user_level_id)
    {
        
        $result = $this->persetujuan_permintaan_barang_history_m->get_datatable_view_tidak_terdaftar($id, $user_level_id);
        // die_dump($result);
        // die_dump($this->db->last_query());
        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        $count = count($records->result_array());
        foreach($records->result_array() as $row)
        {

            $info       = '';

            $data_order_view = $this->persetujuan_permintaan_barang_history_m->get_data_order($row['id'], $row['order_permintaan_barang_id'], $row['user_level_id'])->result_array();
            // die_dump($data_order_view);

            if(count($data_order_view))
            {

                if ($row['status_ppp'] == 2 && $data_order_view[0]['status_view'] == 3 || $row['status_ppp'] == 2 && $data_order_view[0]['status_view'] == 4) {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

                } else {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

                }

            } else {

                if ($row['status_ppp'] == 3 || $row['status_ppp'] == 4) {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info_item_tidak_terdaftar_user[]" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

                } else {

                    $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-order="'.$row['order_ppp'].'" data-detail-id="'.$row['id'].'" data-id="'.$row['order_permintaan_barang_id'].'" data-persetujuan="'.$row['persetujuan_permintaan_barang_id'].'" data-level-id="'.$row['user_level_id'].'" style="margin:0px; text-align:right;"><i class="fa fa-info"></i></a>';

                }

            }

            $link_pdf = '';
            $link_img = '';
            $data_pdf = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $row['id'], 'tipe' => 1 ), true);
            $data_img = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $row['id'], 'tipe' => 2 ), true);

            if(count($data_pdf))
            {
                $link_pdf = '<a target="_blank" href="'.base_url().'assets/mb/pages/pembelian/permintaan_po/doc/'.$row['nama'].'/'.$data_pdf->url.'" class="btn grey-cascade unggah-file" name="item2[{0}][file]" title="Lihat File"><i class="fa fa-file"></i></a>';
            }
            if(count($data_img))
            {
                $link_img = '<button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'pembelian/persetujuan_permintaan_po/lihat_gambar/'.$row['id'].'" class="btn blue-chambray unggah-gambar name="item2[{0}][gambar]" title="Lihat Gambar"><i class="fa fa-image"></i></button>';
            }

            


            $in_count   = '<input class="form-control input-sm hidden" id="count" value="'.$i.'">';

            $action     = $link_pdf.$link_img;


            $checked = '';

            if($row['status_ppp'] == 4)
            {

                $checked = "checked";

            } elseif ($row['status_ppp'] != 4) {

                $checked = "";
            }

            $harga_ref = ($row['harga_ref'] == '')?0:$row['harga_ref'];

            // die_dump($checked);

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
                '<div class="row" style="margin-left : 0px !important; margin-right : 0px !important">
                    <div class="col-xs-6 style="text-align:center; padding-left : 0px !important; padding-right : 0px !important; >
                        <input type="text" value="'.$row['jumlah_persetujuan'].'" readonly style="background-color: transparent;border: 0px solid; text-align: center;">
                    </div>
                    <div class="col-xs-4" style="text-align:right; padding-left : 0px !important; padding-right : 0px !important;">
                        <span class="input-group-button">'.$info.'</span>
                    </div>
                </div>',
                '<div class="text-left">'.$row['satuan'].'</div>',
                '<div class="text-right">'.formatrupiah($harga_ref).'</div>',
                '<div class="text-right">'.$row['supplier'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                '<div class="text-center"><input class="checkboxes" '.$checked.' name="item['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['persetujuan_permintaan_barang_id'].'"></div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item($id=null)
    {
        
        $result = $this->item_m->get_datatable($id);
        // die_dump($result);   

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_tidak_terdaftar($id=null)
    {
        
        $result = $this->daftar_permintaan_po_m->get_datatable_item($id);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_user_box_view($persetujuan_permintaan_barang_id, $id=null, $user_level_id=null)
    {
        
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_user_setujui_box_view($persetujuan_permintaan_barang_id, $id, $user_level_id);
        // die_dump($result);          

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        $status = '';
        foreach($records->result_array() as $row)
        {

            if($row['status_ppp'] == 4) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            
            } elseif($row['status_ppp'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status_ppp'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            } elseif($roow['status_ppp'] == 1) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            }

            if ($row['tanggal_persetujuan'] != null) {

                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));
            
            } else {

                $tanggal_persetujuan = "";
            }

            if ($row['tanggal_baca'] != null) {

                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));
            
            } else {

                $tanggal_baca = "";

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user_level'].'</div>',
                '<div class="text-center">'.$row['order_ppp'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tanggal_baca.'</div>',
                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tanggal_persetujuan.'</div>',
                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',
                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_user_box($id=null, $user_level_id=null, $order_permintaan_barang_detail_id = null)
    {
        
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_user_setujui_box($id, $user_level_id, $order_permintaan_barang_detail_id);
        // die_dump($result);          

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        $status = '';
        foreach($records->result_array() as $row)
        {

            if($row['status_ppp'] == 4) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            
            } elseif($row['status_ppp'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status_ppp'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            } elseif($roow['status_ppp'] == 1) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            }

            if ($row['tanggal_persetujuan'] != null) {

                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));
            
            } else {

                $tanggal_persetujuan = "";
            }

            if ($row['tanggal_baca'] != null) {

                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));
            
            } else {

                $tanggal_baca = "";

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user_level'].'</div>',
                '<div class="text-center">'.$row['order_ppp'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tanggal_baca.'</div>',
                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tanggal_persetujuan.'</div>',
                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',
                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_user_view($id=null, $user_level_id=null, $order_permintaan_barang_detail_id = null,$order=null)
    {
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_user_setujui($id, $user_level_id, $order_permintaan_barang_detail_id, $order);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        $status = '';
        foreach($records->result_array() as $row)
        {

            $data_order_view = $this->persetujuan_permintaan_barang_m->get_data_order($row['id'], $row['order_permintaan_barang_id'], $row['user_level_id'])->result_array();
            // die_dump($data_order_view);

            if($row['status_ppp'] == 4) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            
            } elseif($row['status_ppp'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status_ppp'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            } elseif($row['status_ppp'] == 1) {

                $status = '<div class="text-center"><span class="label label-md label-info">Belum Dibaca</span></div>';

            }

            if ($row['tanggal_persetujuan'] != null) {

                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));
            
            } else {

                $tanggal_persetujuan = "";
            }

            if ($row['tanggal_baca'] != null) {

                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));
            
            } else {

                $tanggal_baca = "";

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user_level'].'</div>',
                '<div class="text-center">'.$row['order_ppp'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tanggal_baca.'</div>',
                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tanggal_persetujuan.'</div>',
                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',
                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_user_view_history($id=null, $user_level_id=null, $order_permintaan_barang_detail_id = null,$order=null)
    {
        $result = $this->persetujuan_permintaan_barang_history_m->get_datatable_user_setujui($id, $user_level_id, $order_permintaan_barang_detail_id, $order);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        $status = '';
        foreach($records->result_array() as $row)
        {

            $data_order_view = $this->persetujuan_permintaan_barang_history_m->get_data_order($row['id'], $row['order_permintaan_barang_id'], $row['user_level_id'])->result_array();
            // die_dump($data_order_view);

            if($row['status_ppp'] == 4) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            
            } elseif($row['status_ppp'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status_ppp'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            } elseif($row['status_ppp'] == 1) {

                $status = '<div class="text-center"><span class="label label-md label-info">Belum Dibaca</span></div>';

            }

            if ($row['tanggal_persetujuan'] != null) {

                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));
            
            } else {

                $tanggal_persetujuan = "";
            }

            if ($row['tanggal_baca'] != null) {

                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));
            
            } else {

                $tanggal_baca = "";

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user_level'].'</div>',
                '<div class="text-center">'.$row['order_ppp'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tanggal_baca.'</div>',
                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tanggal_persetujuan.'</div>',
                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',
                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_user($id=null, $user_level_id=null, $order_permintaan_barang_detail_id = null,$order=null)
    {
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_user_setujui($id, $user_level_id, $order_permintaan_barang_detail_id, $order);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        $status = '';
        foreach($records->result_array() as $row)
        {

            $data_order_view = $this->persetujuan_permintaan_barang_m->get_data_order($row['id'], $row['order_permintaan_barang_id'], $row['user_level_id'])->result_array();
            // die_dump($data_order_view);

            if($row['status_ppp'] == 4) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            
            } elseif($row['status_ppp'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status_ppp'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            } elseif($row['status_ppp'] == 1) {

                $status = '<div class="text-center"><span class="label label-md label-info">Belum Dibaca</span></div>';

            }

            if ($row['tanggal_persetujuan'] != null) {

                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));
            
            } else {

                $tanggal_persetujuan = "";
            }

            if ($row['tanggal_baca'] != null) {

                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));
            
            } else {

                $tanggal_baca = "";

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user_level'].'</div>',
                '<div class="text-center">'.$row['order_ppp'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tanggal_baca.'</div>',
                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tanggal_persetujuan.'</div>',
                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',
                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listing_pilih_item_user_tidak_terdaftar_view($id=null, $user_level_id=null, $order_permintaan_barang_detail_id = null)
    {
        
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_user_setujui2_view($id, $user_level_id, $order_permintaan_barang_detail_id);
        // die_dump($result);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {

            if($row['status_ppp'] == 4) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            
            } elseif($row['status_ppp'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status_ppp'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            } elseif($row['status_ppp'] == 1) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';
                
            }

            if ($row['tanggal_persetujuan'] != null) {

                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));
            
            } else {

                $tanggal_persetujuan = "";
            }

            if ($row['tanggal_baca'] != null) {

                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));
            
            } else {

                $tanggal_baca = "";

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user_level'].'</div>',
                '<div class="text-center">'.$row['order_ppp'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tanggal_baca.'</div>',
                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tanggal_persetujuan.'</div>',
                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',
                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_user_tidak_terdaftar_view_history($id=null, $user_level_id=null, $order_permintaan_barang_detail_id = null)
    {
        
        $result = $this->persetujuan_permintaan_barang_history_m->get_datatable_user_setujui2_view($id, $user_level_id, $order_permintaan_barang_detail_id);
        // die_dump($result);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {

            if($row['status_ppp'] == 4) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            
            } elseif($row['status_ppp'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status_ppp'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            } elseif($row['status_ppp'] == 1) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';
                
            }

            if ($row['tanggal_persetujuan'] != null) {

                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));
            
            } else {

                $tanggal_persetujuan = "";
            }

            if ($row['tanggal_baca'] != null) {

                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));
            
            } else {

                $tanggal_baca = "";

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user_level'].'</div>',
                '<div class="text-center">'.$row['order_ppp'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tanggal_baca.'</div>',
                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tanggal_persetujuan.'</div>',
                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',
                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }
    

    public function listing_pilih_item_user_tidak_terdaftar($id=null, $user_level_id=null, $order_permintaan_barang_detail_id = null)
    {
        
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_user_setujui2($id, $user_level_id, $order_permintaan_barang_detail_id);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {

            if($row['status_ppp'] == 4) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            
            } elseif($row['status_ppp'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status_ppp'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';

            } elseif($row['status_ppp'] == 1) {

                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';
                
            }

            if ($row['tanggal_persetujuan'] != null) {

                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));
            
            } else {

                $tanggal_persetujuan = "";
            }

            if ($row['tanggal_baca'] != null) {

                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));
            
            } else {

                $tanggal_baca = "";

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user_level'].'</div>',
                '<div class="text-center">'.$row['order_ppp'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$tanggal_baca.'</div>',
                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',
                '<div class="text-center">'.$tanggal_persetujuan.'</div>',
                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',
                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_history($id=null)
    {
        
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_item_terdaftar($id);
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

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item_tidak_terdaftar_history($id=null)
    {
        
        $result = $this->persetujuan_permintaan_barang_m->get_datatable_item_tidak_terdaftar($id);
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

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $command     = $this->input->post('command');
        $array_input = $this->input->post();
        $items       = $this->input->post('items');
        $itemsBox    = $this->input->post('box');
        $items2      = $this->input->post('item2');
        $user_id     = $this->session->userdata('user_id');
        $level_id     = $this->session->userdata('level_id');
        $data_cabang = $this->cabang_m->get_by(array('tipe' => 0, 'is_active' => 1));


        // die(dump($array_input));
        if ($command === 'proses')
        {          
            if(!empty($items2))
            {

                $user_id   = $this->session->userdata('user_id');
                
                $max_order = $array_input['order'];                
                $maksimum  = $this->persetujuan_permintaan_barang_m->get_max_order($array_input['pk_value'])->row(0);

                if($array_input['order'] == $maksimum->max_order)
                {

                    $data_setuju = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $this->input->post('user_level_id'), 'tipe_persetujuan' => "2"));
                    
                    if(!empty($data_setuju))
                    {

                        $data_permintaan_item_baru =  array(
                            'user_id'        => $user_id,
                            'transaksi_id'   => $array_input['pk_value'],
                            'tipe_transaksi' => 1,
                            'keterangan'     => 'Permintaan Item Baru dari permintaan barang tidak terdaftar',
                            'status'         => 1,
                            'is_active'      => 1,
                        );

                        $save_permintaan_item_baru = $this->permintaan_item_baru_m->save($data_permintaan_item_baru);

                    } else {


                        $data_permintaan_item_baru =  array(
                            'user_id'        => $user_id,
                            'transaksi_id'   => $array_input['pk_value'],
                            'tipe_transaksi' => 1,
                            'keterangan'     => 'Permintaan Item Baru dari permintaan barang tidak terdaftar',
                            'status'         => 2,
                            'is_active'      => 1,
                        );

                        $save_permintaan_item_baru = $this->permintaan_item_baru_m->save($data_permintaan_item_baru);
                    }
                }



                $item_tolak = 0;
                foreach ($items2 as $row) 
                {                   
                    $user_id     = $this->session->userdata('user_id');
                    $data_cabang = $this->cabang_m->get_by(array('tipe' => 0, 'is_active' => 1));

                    // die(dump('item ada'));
                    if(isset($row['checkbox']))
                    {
                        $data = array(
                            'status'              => 4,
                            'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                            'disetujui_oleh'      => $user_id,
                            'jumlah_persetujuan'  => 0,
                            'satuan_id'           => 0,
                            'keterangan'          => $row['keterangan'],
                            'is_active'           => 1,
                        );
                        $wheres = array(
                            'order_permintaan_barang_id'  => $array_input['pk_value'],
                            'order_permintaan_barang_detail_id' => $row['order_permintaan_barang_detail_id']
                        );

                        $update = $this->persetujuan_permintaan_barang_m->update_by($user_id, $data, $wheres);

                        $data_permintaan_detail = array(
                            'jumlah_disetujui' => 0,
                            'satuan_disetujui' => $row['satuan'],
                            'status' => 3,
                        );

                        $wheres_id = array(
                            'id' => $row['order_permintaan_barang_detail_id']
                        );

                        $update_permintaan_detail = $this->order_permintaan_barang_detail_other_m->update_by($user_id, $data_permintaan_detail, $wheres_id);

                        $item_tolak++;

                        // die_dump($this->db->last_query());                    
                    } else {

                        $data = array(
                            'status'              => 3,
                            'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                            'disetujui_oleh'      => $user_id,
                            'jumlah_persetujuan'  => $row['jumlah'],
                            'satuan_id'           => $row['satuan'],
                            'keterangan'          => $row['keterangan'],
                            'is_active'           => 1,
                        );

                        $update = $this->persetujuan_permintaan_barang_m->update_persetujuan_permintaan_barang($data, $row['persetujuan_permintaan_barang_id'], $array_input['pk_value'], $array_input['user_level_id']);

                        $data_permintaan_detail = array(
                            'jumlah_disetujui' => $row['jumlah'],
                            'satuan_disetujui' => $row['satuan'],
                            'status' => 2,
                        );

                        $wheres_id = array(
                            'id' => $row['order_permintaan_barang_detail_id']
                        );

                        $update_permintaan_detail = $this->order_permintaan_barang_detail_other_m->update_by($user_id, $data_permintaan_detail, $wheres_id);
                    }

                    $maksimum = $this->persetujuan_permintaan_barang_m->get_max_order($row['order_permintaan_barang_id'])->row(0);

                    if($row['data_order'] == $maksimum->max_order)
                    {
                        if(isset($row['checkbox']))
                        {
                            $data = array(
                                'status'              => 4,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => 0,
                                'satuan_id'           => 0,
                                'keterangan'          => $row['keterangan'],
                                'is_active'           => 1,
                            );
                            $wheres = array(
                                'order_permintaan_barang_id'  => $array_input['pk_value'],
                                'order_permintaan_barang_detail_id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_by($user_id, $data, $wheres);
                            // die(dump($this->db->last_query()));

                            $data_permintaan_detail = array(
                                'jumlah_disetujui' => 0,
                                'satuan_disetujui' => $row['satuan'],
                                'status' => 3,
                            );

                            $wheres_id = array(
                                'id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update_permintaan_detail = $this->order_permintaan_barang_detail_other_m->update_by($user_id, $data_permintaan_detail, $wheres_id);


                            // die_dump($this->db->last_query());                    
                        } else {

                            $data = array(
                                'status'              => 3,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => $row['jumlah'],
                                'satuan_id'           => $row['satuan'],
                                'keterangan'          => $row['keterangan'],
                                'is_active'           => 1,
                            );
                            $wheres = array(
                                'persetujuan_permintaan_barang_id' => $row['persetujuan_permintaan_barang_id']
                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_by($user_id, $data, $wheres);

                            $data_permintaan_detail = array(
                                'jumlah_disetujui' => $row['jumlah'],
                                'satuan_disetujui' => $row['satuan'],
                                'status' => 2,
                            );

                            $wheres_id = array(
                                'id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update_permintaan_detail = $this->order_permintaan_barang_detail_other_m->update_by($user_id, $data_permintaan_detail, $wheres_id);
                        }

                        $data_setuju = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $this->input->post('user_level_id'), 'tipe_persetujuan' => "2"));

                        if(!empty($data_setuju))
                        {
                            $data_permintaan_item_baru_detail = array(

                                'permintaan_item_baru_id' => $save_permintaan_item_baru,
                                'nama'                    => $row['nama_item'],
                                'satuan'                  => null,
                                'item_id'                 => null,
                                'item_satuan_id'          => null,
                                'status'                  => 1
                            );
                        
                            $save_detail_permintaan_item_baru = $this->permintaan_item_baru_detail_m->save($data_permintaan_item_baru_detail);

                        } else {

                            $data_permintaan_item_baru_detail = array(

                                'permintaan_item_baru_id' => $save_permintaan_item_baru,
                                'nama'                    => $row['nama_item'],
                                'satuan'                  => $row['satuan'],
                                'item_id'                 => null,
                                'item_satuan_id'          => null,
                                'status'                  => 2,
                            );
                        
                            $save_detail_permintaan_item_baru = $this->permintaan_item_baru_detail_m->save($data_permintaan_item_baru_detail);
                        }

                        $last_draft_id = $this->draft_item_m->get_last_id()->result_array();
                        $last_draft_id = intval($last_draft_id[0]['id']) + 1;

                        $data_draft_item = array(
                            'draft_item_id'                  => $last_draft_id,
                            'permintaan_item_baru_id'        => $save_permintaan_item_baru,
                            'permintaan_item_baru_detail_id' => $save_detail_permintaan_item_baru,
                            'nama'                           => $row['nama_item'],
                            'keterangan'                     => null,
                            'is_discontinue'                 => 0,
                            'buffer_stok'                    => 0,
                            'standar_simpan'                 => 0,
                            'is_sale'                        => 0,
                            'is_identitas'                   => 0,
                            'is_keep'                        => 0,
                            'is_show_assessment'             => 0,
                            'is_active'                      => 1,
                            'created_by'                     => $this->session->userdata('user_id'),
                            'created_date'                   => date('Y-m-d H:i:s'),
                        );

                        $draft_item_id = $this->draft_item_m->add_data($data_draft_item);
                
                        //proses save ke history//
                        $get_data_persetujuan_permintaan_barang = $this->persetujuan_permintaan_barang_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value'], 'box_paket_id' => null));
                        $array_persetujuan_permintaan_barang    = object_to_array($get_data_persetujuan_permintaan_barang);
                        
                        foreach ($array_persetujuan_permintaan_barang as $persetujuan) 
                        {
                            // die_dump($persetujuan);
                            $data_history = array(

                                'order_permintaan_barang_id'        => $persetujuan['order_permintaan_barang_id'],
                                'order_permintaan_barang_detail_id' => $persetujuan['order_permintaan_barang_detail_id'],
                                'box_paket_id'                      => $persetujuan['box_paket_id'],
                                'tipe_permintaan'                   => $persetujuan['tipe_permintaan'],
                                'user_level_id'                     => $persetujuan['user_level_id'],
                                '`order`'                           => $persetujuan['order'],
                                '`status`'                          => $persetujuan['status'],
                                'tanggal_baca'                      => $persetujuan['tanggal_baca'],
                                'dibaca_oleh'                       => $persetujuan['dibaca_oleh'],
                                'tanggal_persetujuan'               => $persetujuan['tanggal_persetujuan'],
                                'disetujui_oleh'                    => $persetujuan['disetujui_oleh'],
                                'jumlah_persetujuan'                => $persetujuan['jumlah_persetujuan'],
                                'satuan_id'                         => $persetujuan['satuan_id'],
                                'keterangan'                        => $persetujuan['keterangan'],
                                'is_active'                         => 1,

                            );
                        
                            $save_history = $this->persetujuan_permintaan_barang_history_m->save($data_history);
                            $delete       = $this->persetujuan_permintaan_barang_m->delete_id($persetujuan['persetujuan_permintaan_barang_id']);
                        }     
                    } 
                }
                $data_permintaan_detail_other = $this->order_permintaan_barang_detail_other_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value']));
                $data_permintaan_detail_other_tolak = $this->order_permintaan_barang_detail_other_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value'], 'status' => 3));

                if(count($data_permintaan_detail_other) == count($data_permintaan_detail_other_tolak))
                {
                    $data_permintaan = array(
                        'status' => 4
                    );
                    $wheres = array(
                        'id'    => $array_input['pk_value']
                    );

                    $update_permintaan = $this->order_permintaan_barang_m->update_by($user_id, $data_permintaan, $wheres);

                    $wheres_status = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1
                    );    

                    $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);

                    $wheres_bayar_detail = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        'tipe'           => 1,
                        'user_level_id'  => $level_id
                    );
                    $permintaan_status_detail = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

                    $wheres_bayar_detail_before = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        'tipe'           => 1,
                        '`order`'        => $permintaan_status_detail->order - 1
                    );

                    $pembayaran_status_detail_before = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_before, true);

                    if(count($pembayaran_status_detail_before) != 0){
                        $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                    }else{
                        $waktu_proses = $permintaan_status_id->created_date;
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
                        'keterangan'     => $row['keterangan'],
                        'modified_by'    => $user_id,
                        'modifed_date'   => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->permintaan_status_detail_m->edit_data($data_pembayaran_status_detail, $permintaan_status_detail->id);

                    $wheres_bayar_detail_after = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        '`order`'        => $permintaan_status_detail->order + 1
                    );

                    $pembayaran_status_detail_after = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_after, true);

                    $data_status_after = array(
                        'status'        => 4,
                        'tipe_persetujuan' => 1,
                        'modified_by'   => $user_id,
                        'modified_date' => date('Y-m-d H:i:s')
                    );      

                    if(count($pembayaran_status_detail_after) != 0){
                        $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                        $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
                    }

                    $wheres_status = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1
                    );    

                    $update_status = $this->permintaan_status_m->update_by($user_id,$data_status_after,$wheres_status);  
                    //proses save ke history//
                    $get_data_persetujuan_permintaan_barang = $this->persetujuan_permintaan_barang_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value'], 'box_paket_id' => null));
                    $array_persetujuan_permintaan_barang    = object_to_array($get_data_persetujuan_permintaan_barang);
                    
                    foreach ($array_persetujuan_permintaan_barang as $persetujuan) 
                    {
                        // die_dump($persetujuan);
                        $data_history = array(

                            'order_permintaan_barang_id'        => $persetujuan['order_permintaan_barang_id'],
                            'order_permintaan_barang_detail_id' => $persetujuan['order_permintaan_barang_detail_id'],
                            'box_paket_id'                      => $persetujuan['box_paket_id'],
                            'tipe_permintaan'                   => $persetujuan['tipe_permintaan'],
                            'user_level_id'                     => $persetujuan['user_level_id'],
                            '`order`'                           => $persetujuan['order'],
                            '`status`'                          => $persetujuan['status'],
                            'tanggal_baca'                      => $persetujuan['tanggal_baca'],
                            'dibaca_oleh'                       => $persetujuan['dibaca_oleh'],
                            'tanggal_persetujuan'               => $persetujuan['tanggal_persetujuan'],
                            'disetujui_oleh'                    => $persetujuan['disetujui_oleh'],
                            'jumlah_persetujuan'                => $persetujuan['jumlah_persetujuan'],
                            'satuan_id'                         => $persetujuan['satuan_id'],
                            'keterangan'                        => $persetujuan['keterangan'],
                            'is_active'                         => 1,

                        );
                    
                        $save_history = $this->persetujuan_permintaan_barang_history_m->save($data_history);
                        $delete       = $this->persetujuan_permintaan_barang_m->delete_id($persetujuan['persetujuan_permintaan_barang_id']);
                    }
                }
                elseif(count($data_permintaan_detail_other) != count($data_permintaan_detail_other_tolak))
                {
                    $data_permintaan = array(
                        'status' => 3,
                        'tipe_persetujuan' => 1
                    );
                    $wheres = array(
                        'id'    => $array_input['pk_value']
                    );

                    $update_permintaan = $this->order_permintaan_barang_m->update_by($user_id, $data_permintaan, $wheres);

                    $wheres_status = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1
                    );    

                    $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);

                    $wheres_bayar_detail = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        'tipe'           => 1,
                        'user_level_id'  => $level_id
                    );
                    $permintaan_status_detail = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

                    $wheres_bayar_detail_before = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        'tipe'           => 1,
                        '`order`'        => $permintaan_status_detail->order - 1
                    );

                    $pembayaran_status_detail_before = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_before, true);

                    if(count($pembayaran_status_detail_before) != 0){
                        $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                    }else{
                        $waktu_proses = $permintaan_status_id->created_date;
                    }

                    $datetime1 = new DateTime();
                    $datetime2 = new DateTime($waktu_proses);
                    $interval = $datetime1->diff($datetime2);
                    $elapsed = $interval->format('%a d %h h %i m %S s');

                    $data_pembayaran_status_detail = array(
                        'status'         => 2,
                        'tanggal_proses' => date('Y-m-d H:i:s'),
                        'user_proses'    => $user_id,
                        'waktu_tunggu'   => $elapsed,
                        'keterangan'     => $row['keterangan'],
                        'modified_by'    => $user_id,
                        'modifed_date'   => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->permintaan_status_detail_m->edit_data($data_pembayaran_status_detail, $permintaan_status_detail->id);

                    $wheres_bayar_detail_after = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1,
                        'tipe_pengajuan' => 0,
                        '`order`'        => $permintaan_status_detail->order + 1
                    );

                    $pembayaran_status_detail_after = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_after, true);

                    $data_status_after = array(
                        'status' => 3,
                        'tipe_persetujuan' => 1,
                        'modified_by'   => $user_id,
                        'modified_date' => date('Y-m-d H:i:s')
                    );   

                    if($array_input['tipe'] == 3){
                        if($array_input['order_setuju'] == $maksimum->max_order ){
                            $data_status_after['status'] = 3;
                            $data_status_after['tipe_persetujuan'] = 2;
                        }
                    }   

                    if(count($pembayaran_status_detail_after) != 0){
                        $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                        $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
                    }

                    $wheres_status = array(
                        'transaksi_id'   => $array_input['pk_value'],
                        'tipe_transaksi' => 1
                    );    

                    $update_status = $this->permintaan_status_m->update_by($user_id,$data_status_after,$wheres_status);
                }     
            }
            
            //save_item_terdaftar
            if(!empty($items))
            {
                $jml_tolak = 0;
                foreach ($items as $row) 
                {
                    $user_id   = $this->session->userdata('user_id');
                    
                    $max_order = $array_input['order'];                
                    $maksimum  = $this->persetujuan_permintaan_barang_m->get_max_order($array_input['pk_value'])->row(0);


                    if($array_input['order'] == $maksimum->max_order)
                    {

                        //*proses update persetujuan*//
                        if(isset($row['checkbox']))
                        {
                            // die_dump($row);
                            $data = array(
                                'status'              => 4,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => 0,
                                'satuan_id'           => 0,
                                'keterangan'          => $row['keterangan'],
                                'is_active'           => 1,
                            );
                            $wheres = array(
                                'order_permintaan_barang_id'  => $array_input['pk_value'],
                                'order_permintaan_barang_detail_id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_by($user_id, $data, $wheres);
                            // die(dump($this->db->last_query()));

                            $data_permintaan_detail = array(
                                'jumlah_disetujui'         => 0,
                                'item_satuan_disetujui_id' => 0,
                                'status'                   => 3,
                            );

                            $wheres_id = array(
                                'id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update_permintaan_detail = $this->order_permintaan_barang_detail_m->update_by($user_id, $data_permintaan_detail, $wheres_id);
                            
                            $jml_tolak = $jml_tolak + 1;

                        } 
                        else 
                        {

                            $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1),true);
                            $konversi = $this->item_m->get_nilai_konversi($row['satuan']);


                            $jumlah_item_minta = ($row['jumlah'] * $konversi);
                            $data = array(
                                'status'              => 3,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => $row['jumlah'],
                                'satuan_id'           => $row['satuan'],
                                'keterangan'          => $row['keterangan'],
                                'is_active'           => 1,
                            );
                            $wheres = array(
                                'persetujuan_permintaan_barang_id' => $row['persetujuan_permintaan_barang_id']
                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_by($user_id, $data, $wheres);

                            $data_permintaan_detail = array(
                                'jumlah_disetujui' => $row['jumlah'],
                                'item_satuan_disetujui_id' => $row['satuan'],
                                'status' => 2,
                            );

                            $wheres_id = array(
                                'id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update_permintaan_detail = $this->order_permintaan_barang_detail_m->update_by($user_id, $data_permintaan_detail, $wheres_id);

                            $cabang_login = $this->cabang_m->get($this->session->userdata('cabang_id'));

                            $item_sub_kat = $this->item_sub_kategori_m->get_by(array('id' => $row['item_sub_kategori_id']), true);
                            $cabang_divisi = $this->cabang_divisi_setting_m->get_by(array('divisi_setting_id' => $item_sub_kat->divisi_setting_id), true);

                            $supplier = $this->supplier_m->get_by(array('cabang_id' => $cabang_divisi->cabang_id), true);


                            $data_pembelian =  $this->pembelian_m->get_by(array('id_permintaan' => $array_input['pk_value'], 'tipe_pembelian' => 1, 'supplier_id' => $supplier->id, 'divisi_setting_id' => $item_sub_kat->divisi_setting_id), true);
                            $data_pembelian = object_to_array($data_pembelian);


                            $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_by(array('supplier_id' => $supplier->id, 'is_active' => 1), true);
                            $supplier_tipe_bayar = object_to_array($supplier_tipe_bayar);
                            $supplier_item = $this->supplier_item_m->get_by(array('supplier_id' => $supplier->id, 'item_id' => $row['item_id'], 'item_satuan_id' => $row['item_satuan_id']), true);
                            $harga_item = $this->supplier_harga_item_m->get_harga($supplier_item->id)->result_array();

                                // die(dump($supplier_tipe_bayar));
                            if(count($data_pembelian) == 0){
                                $last_id       = $this->pembelian_m->get_max_id_pembelian()->result_array();
                                $last_id       = intval($last_id[0]['max_id'])+1;
                                $format_id     = 'PO-'.date('m').'-'.date('Y').'-%04d';
                                $id_po         = sprintf($format_id, $last_id, 4);

                                $last_number   = $this->pembelian_m->get_no_pembelian()->result_array();
                                $last_number   = intval($last_number[0]['max_no_pembelian'])+1;
                                $format        = '#PO#%03d/RHS-RI/'.date('Y');
                                $no_po         = sprintf($format, $last_number, 3);
                                $id_po_rc = $id_po;
                                $no_po_rc = $no_po;

                                $data_pembelian = array(
                                    'id'              => $id_po_rc,
                                    'no_pembelian'    => $no_po_rc,
                                    'tipe_pembelian'  => 1,
                                    'id_permintaan'   => $array_input['pk_value'],
                                    'divisi_setting_id'   => $item_sub_kat->divisi_setting_id,
                                    'tanggal_pesan'   => date("Y-m-d H:i:s"),
                                    'tanggal_kadaluarsa'   => date("Y-m-d H:i:s", strtotime('+14 days')),
                                    'supplier_id'     => $supplier->id,
                                    'tipe_supplier'   => 1,
                                    'customer_id'     => $this->session->userdata('cabang_id'),
                                    'tipe_customer'   => 1,
                                    'master_tipe_pembayaran_id' => 3,
                                    'tipe_pembayaran' => $supplier_tipe_bayar['id'],
                                    'status'          => 4,
                                    'status_keuangan'          => 1,
                                    'status_cancel'          => 0,
                                    'diskon'          => 0,
                                    'pph'             => 0,
                                    'biaya_tambahan'  => 0,
                                    'dp'              => 0,
                                    'sisa_bayar'      => 0,
                                    'grand_total'     => 0,
                                    'is_active'     => 1,
                                    'created_by'      => $this->session->userdata('user_id'),
                                    'created_date'    => date('Y-m-d H:i:s')
                                );

                                $insert_pembelian = $this->pembelian_m->add_data($data_pembelian);
                            }elseif(count($data_pembelian) != 0){
                                $id_po_rc = $data_pembelian['id'];
                            }

                            $last_id      = $this->pembelian_detail_m->get_max_id_detail_pembelian()->result_array();
                            $last_id      = intval($last_id[0]['max_id'])+1;
                            $format_id    = 'POD-'.date('m').'-'.date('Y').'-%04d';
                            $id_po_detail = sprintf($format_id, $last_id, 4);

                            $data_pembelian_detail = array(
                                'id'                     => $id_po_detail,
                                'pembelian_id'           => $id_po_rc,
                                'permintaan_detail_id'           => $no_permintaan_item,
                                'item_id'                => $row['item_id'],
                                'item_satuan_id'         => $row['item_satuan_id'],
                                'item_satuan_id_primary' => $satuan_primary->id,
                                'jumlah_pesan'           => $row['jumlah'],
                                'jumlah_belum_diterima'  => $jumlah_item_minta,
                                'harga_beli'             => (count($harga_item) == 0)?0:$harga_item[0]['harga'],
                                'harga_beli_primary'     => (count($harga_item) == 0)?0:$harga_item[0]['harga'],
                                'status'                 => 2,
                                'jumlah_disetujui'       => $jumlah_item_minta,
                                'disetujui_oleh'         => $this->session->userdata('user_id'),
                                'keterangan'             => '',
                                'tanggal_kirim'          => date('Y-m-d', strtotime($row['item_tanggal_kirim'])),
                                'is_active'              => 1,
                                'created_by'             => $this->session->userdata('user_id'),
                                'created_date'           => date('Y-m-d H:i:s')
                            );
                            
                            $insert_pembelian_detail = $this->pembelian_detail_m->add_data($data_pembelian_detail);
                        
                            $harga_item = $this->supplier_harga_item_m->get_harga($supplier_item->id)->result_array(); 

                            // $cabang_jual = $this->cabang_m->get_by(array('divisi_id' => $item_sub_kat->divisi_id, 'is_pusat' => 1), true);
                            $cabang_jual = $this->cabang_m->get_by(array('id' => $cabang_divisi->cabang_id), true);
                            // die(dump($cabang_jual));
                            
                            $pemesanan = get_data_pemesanan($cabang_jual->url,$id_po_rc,1,$this->session->userdata('cabang_id'), $item_sub_kat->divisi_setting_id);
                            //die(dump($pemesanan));//

                            if(count($pemesanan) == 0){
                                $id_no_so = get_max_id_pemesanan($cabang_jual->url);

                                $id_so = $id_no_so['id_so'];
                                $no_jual = $id_no_so['no_so'];
                                
                                $data_jual = array(
                                    'id'                 => $id_so,                                
                                    'no_pemesanan'       => $no_jual,
                                    'jenis_pemesanan'    => $item_sub_kat->divisi_setting_id,
                                    'divisi_setting_id'  => $item_sub_kat->divisi_setting_id,
                                    'tanggal_pesan'      => date("Y-m-d H:i:s"),
                                    'tanggal_kadaluarsa' => date("Y-m-d H:i:s", strtotime("+30 days")),
                                    'tanggal_garansi'    => date("Y-m-d H:i:s", strtotime("+14 days")),
                                    'is_single_kirim'    => 1,
                                    'tanggal_kirim'      => date("Y-m-d H:i:s", strtotime("+1 days")),
                                    'customer_id'        => $this->session->userdata('cabang_id'),
                                    'tipe_customer'     => 1,
                                    'tipe_pembayaran_id'   => 'TP003',
                                    'transaksi_id'      => $id_po_rc,
                                    'tipe_transaksi'    => 1,
                                    'status'            => 1,
                                    'status_keuangan'   => 2,
                                    'is_active'        => 1,
                                    'created_by'        => $this->session->userdata('user_id'),
                                    'created_date'      => date('Y-m-d H:i:s'),
                                );

                                $path_model = 'penjualan/pemesanan/pemesanan_m';

                                $insert_penjualan = insert_data_api_id($data_jual,$cabang_jual->url,$path_model);
                            }else{
                                $id_so = $pemesanan['id'];
                            }
                            $id_so_detail = get_max_id_pemesanan_detail($cabang_jual->url);
                            $id_so_detail = $id_so_detail['id_so_detail'];

                            $data_jual_item = array(
                                'id'                     => $id_so_detail,
                                'pemesanan_id'           => $id_so,
                                'pembelian_detail_id'    => $id_po_detail,
                                'item_id'                => $row['item_id'],
                                'diskon'                 => 0,
                                'item_satuan_id'         => $row['item_satuan_id'],
                                'item_satuan_id_primary' => $satuan_primary->id,
                                'jumlah_pesan'                 => $row['jumlah'],
                                'jumlah_konversi'        => ($row['jumlah'] * $konversi),
                                'jumlah_diterima'     => 0,
                                'jumlah_belum_diterima'     => ($row['jumlah'] * $konversi),
                                'status'                 => 1,
                                'is_active'        => 1,
                                'jumlah_disetujui'       => ($row['jumlah'] * $konversi),
                                'disetujui_oleh'       => 0,
                                'harga_beli'                  => (count($harga_item) == 0)?0:$harga_item[0]['harga'],
                                'tanggal_kirim'          => date("Y-m-d H:i:s", strtotime("+1 days")),
                                'created_by'             => $this->session->userdata('user_id'),
                                'created_date'           => date('Y-m-d H:i:s'),
                            );

                            // $jual_item = $this->penjualan_detail_m->add_data($data_jual_item);  
                            $path_model = 'penjualan/pemesanan/pemesanan_detail_m';

                            $insert_penjualan_detail = insert_data_api_id($data_jual_item,$cabang_jual->url,$path_model);

                            $id_so_kirim_detail = get_max_id_pemesanan_kirim_detail($cabang_jual->url);
                            $id_so_kirim_detail = $id_so_kirim_detail['id_so_kirim_detail'];

                            // die_dump($id_so_kirim_detail);

                            $data_jual_kirim_item = array(
                                'id'                     => $id_so_kirim_detail,
                                'pemesanan_id'           => $id_so,
                                'pemesanan_detail_id'    => $id_so_detail,
                                'jumlah_kirim'           => $row['jumlah'],
                                'tanggal_kirim'          => date("Y-m-d H:i:s", strtotime("+1 days")),
                                'created_by'             => $this->session->userdata('user_id'),
                                'created_date'           => date('Y-m-d H:i:s'),
                            );

                            // $jual_item = $this->penjualan_detail_m->add_data($data_jual_kirim_item);  
                            $path_model = 'penjualan/pemesanan/pemesanan_detail_tanggal_kirim_m';

                            $insert_penjualan_detail_kirim = insert_data_api_id($data_jual_kirim_item,$cabang_jual->url,$path_model); 
                        }
                            

                        $data_permintaan_detail = $this->order_permintaan_barang_detail_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value']));
                        $data_permintaan_detail_tolak = $this->order_permintaan_barang_detail_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value'], 'status' => 3));

                        if(count($data_permintaan_detail) == count($data_permintaan_detail_tolak))
                        {
                            $data_permintaan = array(
                                'status' => 4
                            );
                            $wheres = array(
                                'id'    => $array_input['pk_value']
                            );

                            $update_permintaan = $this->order_permintaan_barang_m->update_by($user_id, $data_permintaan, $wheres);

                            $wheres_status = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1
                            );    

                            $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);

                            $wheres_bayar_detail = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1,
                                'tipe_pengajuan' => 0,
                                'tipe'           => 1,
                                'user_level_id'  => $level_id
                            );
                            $permintaan_status_detail = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

                            $wheres_bayar_detail_before = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1,
                                'tipe_pengajuan' => 0,
                                'tipe'           => 1,
                                '`order`'        => $permintaan_status_detail->order - 1
                            );

                            $pembayaran_status_detail_before = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_before, true);

                            if(count($pembayaran_status_detail_before) != 0){
                                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                            }else{
                                $waktu_proses = $permintaan_status_id->created_date;
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
                                'keterangan'     => $row['keterangan'],
                                'modified_by'    => $user_id,
                                'modifed_date'   => date('Y-m-d H:i:s')
                            );

                            $pembayaran_status_detail = $this->permintaan_status_detail_m->edit_data($data_pembayaran_status_detail, $permintaan_status_detail->id);

                            $wheres_bayar_detail_after = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1,
                                'tipe_pengajuan' => 0,
                                '`order`'        => $permintaan_status_detail->order + 1
                            );

                            $pembayaran_status_detail_after = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_after, true);

                            $data_status_after = array(
                                'status'        => 4,
                                'tipe_persetujuan' => 1,
                                'modified_by'   => $user_id,
                                'modified_date' => date('Y-m-d H:i:s')
                            );      

                            if(count($pembayaran_status_detail_after) != 0){
                                $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                                $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
                            }

                            $wheres_status = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1
                            );    

                            $update_status = $this->permintaan_status_m->update_by($user_id,$data_status_after,$wheres_status);  

                        }
                        elseif(count($data_permintaan_detail) != count($data_permintaan_detail_tolak))
                        {
                            $data_permintaan = array(
                                'status' => 3
                            );
                            $wheres = array(
                                'id'    => $array_input['pk_value']
                            );

                            $update_permintaan = $this->order_permintaan_barang_m->update_by($user_id, $data_permintaan, $wheres);

                            
                            $wheres_status = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1
                            );    

                            $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);

                            $wheres_bayar_detail = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1,
                                'tipe_pengajuan' => 0,
                                'tipe'           => 1,
                                'user_level_id'  => $level_id
                            );
                            $permintaan_status_detail = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

                            $wheres_bayar_detail_before = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1,
                                'tipe_pengajuan' => 0,
                                'tipe'           => 1,
                                '`order`'        => $permintaan_status_detail->order - 1
                            );

                            $pembayaran_status_detail_before = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_before, true);

                            if(count($pembayaran_status_detail_before) != 0){
                                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                            }else{
                                $waktu_proses = $permintaan_status_id->created_date;
                            }

                            $datetime1 = new DateTime();
                            $datetime2 = new DateTime($waktu_proses);
                            $interval = $datetime1->diff($datetime2);
                            $elapsed = $interval->format('%a d %h h %i m %S s');

                            $data_pembayaran_status_detail = array(
                                'status'         => 2,
                                'tanggal_proses' => date('Y-m-d H:i:s'),
                                'user_proses'    => $user_id,
                                'waktu_tunggu'   => $elapsed,
                                'keterangan'     => $row['keterangan'],
                                'modified_by'    => $user_id,
                                'modifed_date'   => date('Y-m-d H:i:s')
                            );

                            $pembayaran_status_detail = $this->permintaan_status_detail_m->edit_data($data_pembayaran_status_detail, $permintaan_status_detail->id); 
                            
                            $wheres_bayar_detail_after = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1,
                                'tipe_pengajuan' => 0,
                                '`order`'        => $permintaan_status_detail->order + 1
                            );

                            $pembayaran_status_detail_after = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_after, true);

                            $data_status_after = array(
                                'status' => 3,
                                'tipe_persetujuan' => 1,
                                'modified_by'   => $user_id,
                                'modified_date' => date('Y-m-d H:i:s')
                            );   

                            if($array_input['tipe'] == 3){
                                if($array_input['order_setuju'] == $maksimum->max_order ){
                                    $data_status_after['status'] = 3;
                                    $data_status_after['tipe_persetujuan'] = 2;
                                }
                            }

                            if(count($pembayaran_status_detail_after) != 0){
                                $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                                $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
                            }

                            $wheres_status = array(
                                'transaksi_id'   => $array_input['pk_value'],
                                'tipe_transaksi' => 1
                            );    

                            $update_status = $this->permintaan_status_m->update_by($user_id,$data_status_after,$wheres_status);  
                        }
                        
                        //proses save ke history//
                        $get_data_persetujuan_permintaan_barang = $this->persetujuan_permintaan_barang_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value'], 'order_permintaan_barang_detail_id' => $row['order_permintaan_barang_detail_id'],'box_paket_id' => null));
                        $array_persetujuan_permintaan_barang    = object_to_array($get_data_persetujuan_permintaan_barang);
                        
                        foreach ($array_persetujuan_permintaan_barang as $persetujuan) 
                        {
                            $data_history = array(

                                'order_permintaan_barang_id'        => $persetujuan['order_permintaan_barang_id'],
                                'order_permintaan_barang_detail_id' => $persetujuan['order_permintaan_barang_detail_id'],
                                'box_paket_id'                      => $persetujuan['box_paket_id'],
                                'tipe_permintaan'                   => $persetujuan['tipe_permintaan'],
                                'user_level_id'                     => $persetujuan['user_level_id'],
                                '`order`'                           => $persetujuan['order'],
                                '`status`'                          => $persetujuan['status'],
                                'tanggal_baca'                      => $persetujuan['tanggal_baca'],
                                'dibaca_oleh'                       => $persetujuan['dibaca_oleh'],
                                'tanggal_persetujuan'               => $persetujuan['tanggal_persetujuan'],
                                'disetujui_oleh'                    => $persetujuan['disetujui_oleh'],
                                'jumlah_persetujuan'                => $persetujuan['jumlah_persetujuan'],
                                'satuan_id'                         => $persetujuan['satuan_id'],
                                'keterangan'                        => $persetujuan['keterangan'],
                                'is_active'                         => 1,

                            );
                        
                            $save_history = $this->persetujuan_permintaan_barang_history_m->save($data_history);
                            $delete       = $this->persetujuan_permintaan_barang_m->delete_id($persetujuan['persetujuan_permintaan_barang_id']);

                        }

                        
                    }
                    elseif($array_input['order'] != $maksimum->max_order) 
                    {
                        // die_dump('bukan order terakhir');
                        //*proses update persetujuan*//
                        if(isset($row['checkbox']))
                        {
                            // die_dump('item ceklis / tolak');
                            $data = array(
                                'status'              => 4,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => 0,
                                'satuan_id'           => 0,
                                'keterangan'          => $row['keterangan'],
                                'is_active'           => 1,
                            );
                            $wheres = array(
                                'order_permintaan_barang_id'        => $array_input['pk_value'],
                                'order_permintaan_barang_detail_id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_by($user_id, $data, $wheres);
                            // die(dump($this->db->last_query()));

                            $data_permintaan_detail = array(
                                'jumlah_disetujui' => 0,
                                'item_satuan_disetujui_id' => 0,
                                'status' => 3,
                            );

                            $wheres_id = array(
                                'id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update_permintaan_detail = $this->order_permintaan_barang_detail_m->update_by($user_id, $data_permintaan_detail, $wheres_id);

                            
                            $jml_tolak = $jml_tolak + 1;
                        
                        } else {

                            $data = array(
                                'status'              => 3,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => $row['jumlah'],
                                'satuan_id'           => $row['satuan'],
                                'keterangan'          => $row['keterangan'],
                                'is_active'           => 1,
                            );
                            $wheres = array(
                                'persetujuan_permintaan_barang_id' => $row['persetujuan_permintaan_barang_id']
                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_by($user_id, $data, $wheres);

                            $data_permintaan_detail = array(
                                'jumlah_disetujui' => $row['jumlah'],
                                'item_satuan_disetujui_id' => $row['satuan'],
                                'status' => 2,
                            );

                            $wheres_id = array(
                                'id' => $row['order_permintaan_barang_detail_id']
                            );

                            $update_permintaan_detail = $this->order_permintaan_barang_detail_m->update_by($user_id, $data_permintaan_detail, $wheres_id);

                        }

                        $wheres_status = array(
                            'transaksi_id'   => $array_input['pk_value'],
                            'tipe_transaksi' => 1
                        );    

                        $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);

                        $wheres_bayar_detail = array(
                            'transaksi_id'   => $array_input['pk_value'],
                            'tipe_transaksi' => 1,
                            'tipe_pengajuan' => 0,
                            'tipe'           => 1,
                            'user_level_id'  => $level_id
                        );
                        $permintaan_status_detail = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

                        $wheres_bayar_detail_before = array(
                            'transaksi_id'   => $array_input['pk_value'],
                            'tipe_transaksi' => 1,
                            'tipe_pengajuan' => 0,
                            'tipe'           => 1,
                            '`order`'        => $permintaan_status_detail->order - 1
                        );

                        $pembayaran_status_detail_before = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_before, true);

                        if(count($pembayaran_status_detail_before) != 0){
                            $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                        }else{
                            $waktu_proses = $permintaan_status_id->created_date;
                        }

                        $datetime1 = new DateTime();
                        $datetime2 = new DateTime($waktu_proses);
                        $interval = $datetime1->diff($datetime2);
                        $elapsed = $interval->format('%a d %h h %i m %S s');

                        $data_pembayaran_status_detail = array(
                            'status'         => 2,
                            'tanggal_proses' => date('Y-m-d H:i:s'),
                            'user_proses'    => $user_id,
                            'waktu_tunggu'   => $elapsed,
                            'keterangan'     => $row['keterangan'],
                            'modified_by'    => $user_id,
                            'modifed_date'   => date('Y-m-d H:i:s')
                        );

                        $pembayaran_status_detail = $this->permintaan_status_detail_m->edit_data($data_pembayaran_status_detail, $permintaan_status_detail->id); 
                        
                        $wheres_bayar_detail_after = array(
                            'transaksi_id'   => $array_input['pk_value'],
                            'tipe_transaksi' => 1,
                            'tipe_pengajuan' => 0,
                            '`order`'        => $permintaan_status_detail->order + 1
                        );

                        $pembayaran_status_detail_after = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_after, true);

                        $data_status_after = array(
                            'status' => 3,
                            'tipe_persetujuan' => 1,
                            'modified_by'   => $user_id,
                            'modified_date' => date('Y-m-d H:i:s')
                        );   

                        if($array_input['tipe'] == 3){
                            if($array_input['order_setuju'] == $maksimum->max_order ){
                                $data_status_after['status'] = 3;
                                $data_status_after['tipe_persetujuan'] = 2;
                            }
                        }

                        if(count($pembayaran_status_detail_after) != 0){
                            $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                            $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
                        }

                        $wheres_status = array(
                            'transaksi_id'   => $array_input['pk_value'],
                            'tipe_transaksi' => 1
                        );    

                        $update_status = $this->permintaan_status_m->update_by($user_id,$data_status_after,$wheres_status);
                        //*end of proses update persetujuan*//
                    }
                    // die_dump($this->db->last_query());
                }

            $user_id   = $this->session->userdata('user_id');
                
            $max_order = $array_input['order'];                
            $maksimum  = $this->persetujuan_permintaan_barang_m->get_max_order($array_input['pk_value'])->row(0);

            if($array_input['order'] != $maksimum->max_order) 
            {
                $data_permintaan_detail = $this->order_permintaan_barang_detail_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value']));
                $data_permintaan_detail_tolak = $this->order_permintaan_barang_detail_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value'], 'status' => 3));

                if(count($data_permintaan_detail) == count($data_permintaan_detail_tolak))
                {
                    $data_permintaan = array(
                        'status' => 4
                    );
                    $wheres = array(
                        'id'    => $array_input['pk_value']
                    );

                    $update_permintaan = $this->order_permintaan_barang_m->update_by($user_id, $data_permintaan, $wheres);

                    //proses save ke history//
                    $get_data_persetujuan_permintaan_barang = $this->persetujuan_permintaan_barang_m->get_by(array('order_permintaan_barang_id' => $array_input['pk_value'], 'box_paket_id' => null));
                    $array_persetujuan_permintaan_barang    = object_to_array($get_data_persetujuan_permintaan_barang);
                    
                    foreach ($array_persetujuan_permintaan_barang as $persetujuan) 
                    {
                        $data_history = array(

                            'order_permintaan_barang_id'        => $persetujuan['order_permintaan_barang_id'],
                            'order_permintaan_barang_detail_id' => $persetujuan['order_permintaan_barang_detail_id'],
                            'box_paket_id'                      => $persetujuan['box_paket_id'],
                            'tipe_permintaan'                   => $persetujuan['tipe_permintaan'],
                            'user_level_id'                     => $persetujuan['user_level_id'],
                            '`order`'                           => $persetujuan['order'],
                            '`status`'                          => $persetujuan['status'],
                            'tanggal_baca'                      => $persetujuan['tanggal_baca'],
                            'dibaca_oleh'                       => $persetujuan['dibaca_oleh'],
                            'tanggal_persetujuan'               => $persetujuan['tanggal_persetujuan'],
                            'disetujui_oleh'                    => $persetujuan['disetujui_oleh'],
                            'jumlah_persetujuan'                => $persetujuan['jumlah_persetujuan'],
                            'satuan_id'                         => $persetujuan['satuan_id'],
                            'keterangan'                        => $persetujuan['keterangan'],
                            'is_active'                         => 1,
                        );
                    
                        $save_history = $this->persetujuan_permintaan_barang_history_m->save($data_history);
                        $delete       = $this->persetujuan_permintaan_barang_m->delete_id($persetujuan['persetujuan_permintaan_barang_id']);
                    }
                }
                elseif(count($data_permintaan_detail) != count($data_permintaan_detail_tolak))
                {
                    $data_permintaan = array(
                        'status' => 3
                    );
                    $wheres = array(
                        'id'    => $array_input['pk_value']
                    );

                    $update_permintaan = $this->order_permintaan_barang_m->update_by($user_id, $data_permintaan, $wheres);
                }
            }       



            }

            //proses jika item box paket 
            if(!empty($itemsBox))
            {
                // die_dump($itemsBox);
                
                // die(dump('proses jika item merupakan box paket'));
                // die_dump('masuk ke pengecekan stock box tersedia atau tidak ?');
                foreach ($itemsBox as $box) 
                {
                    //proses update persetujuan item box//
                    
                    $user_id   = $this->session->userdata('user_id');
                    
                    $max_order = $array_input['order'];                
                    $maksimum  = $this->persetujuan_permintaan_barang_m->get_max_order($array_input['pk_value'])->row(0);
                    // die_dump($maksimum->max_order);

                    if($array_input['order'] == $maksimum->max_order)
                    {
                        // die_dump('order terakhir di box paket');

                        //*proses update persetujuan*//
                        if(isset($box['checkbox']))
                        {
                            // die_dump('item ceklis / tolak');
                            $data = array(

                                'status'              => 4,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => 0,
                                'keterangan'          => $box['keterangan'],
                                'is_active'           => 1,

                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_persetujuan_permintaan_barang($data, $box['persetujuan_permintaan_barang_id'], $array_input['pk_value'], $array_input['user_level_id']);
                            // die_dump($this->db->last_query());
                        
                        } else {

                            $data = array(

                                'status'              => 3,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => $box['jumlah_setujui'],
                                'keterangan'          => $box['keterangan'],
                                'is_active'           => 1,

                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_persetujuan_permintaan_barang($data, $box['persetujuan_permintaan_barang_id'], $array_input['pk_value'], $array_input['user_level_id']);
                            // die_dump($this->db->last_query());

                        }
                        //*end of proses update persetujuan*//
                        
                        //proses save ke history//
                        $get_data_persetujuan_permintaan_barang = $this->persetujuan_permintaan_barang_m->get_data_persetujuan_box($array_input['pk_value'])->result_array();
                        // die_dump($get_data_persetujuan_permintaan_barang);
                        // die_dump($this->db->last_query());
                        
                        foreach ($get_data_persetujuan_permintaan_barang as $persetujuan) 
                        {
                            // die_dump($persetujuan);
                            $data_history = array(

                                'order_permintaan_barang_id'        => $persetujuan['order_permintaan_barang_id'],
                                'order_permintaan_barang_detail_id' => $persetujuan['order_permintaan_barang_detail_id'],
                                'box_paket_id'                      => $persetujuan['box_paket_id'],
                                'tipe_permintaan'                   => $persetujuan['tipe_permintaan'],
                                'user_level_id'                     => $persetujuan['user_level_id'],
                                '`order`'                           => $persetujuan['order'],
                                '`status`'                          => $persetujuan['status'],
                                'tanggal_baca'                      => $persetujuan['tanggal_baca'],
                                'dibaca_oleh'                       => $persetujuan['dibaca_oleh'],
                                'tanggal_persetujuan'               => $persetujuan['tanggal_persetujuan'],
                                'disetujui_oleh'                    => $persetujuan['disetujui_oleh'],
                                'jumlah_persetujuan'                => $persetujuan['jumlah_persetujuan'],
                                'satuan_id'                         => $persetujuan['satuan_id'],
                                'keterangan'                        => $persetujuan['keterangan'],
                                'is_active'                         => 1,

                            );
                        
                            $save_history = $this->persetujuan_permintaan_barang_history_m->save($data_history);
                            // die_dump($this->db->last_query());
                            $delete       = $this->persetujuan_permintaan_barang_m->delete_id($persetujuan['persetujuan_permintaan_barang_id']);
                            // die_dump($this->db->last_query());

                        }
                        //end of proses save ke history//

                        // die_dump('pengecekan box_paket_id di inventory');   
                        $invent = $this->inventory_m->get_by(array('box_paket_id' => $box['id_box']));
                        // die_dump($invent);
                        if(count($invent) != 0)
                        {

                            // die_dump('stock box tersedia');
                            // die_dump($box);
                            $data = $this->persetujuan_permintaan_barang_m->get_by(array('persetujuan_permintaan_barang_id' => $box['persetujuan_permintaan_barang_id']));
                            // die_dump($data);
                            $data_array = object_to_array($data);
                            foreach ($data_array as $array) 
                            {
                                // die_dump($array);
                                $this->cek_cabang_id($array['order_permintaan_barang_id']);

                            }


                        } else {
                            // die_dump('stock box tidak tersedia');
                            
                            $item_box_paket = $this->box_paket_detail_m->get_by(array('box_paket_id' => $box['id_box']));
                            foreach ($item_box_paket as $item_box) 
                            {
                                //cek ke inventory
                                $item_inventory = $this->inventory_m->get_stock_item_box_permintaan_barang($item_box->item_id, $item_box->jumlah)->result_array();
                                // die_dump($this->db->last_query());
                                // die_dump($item_inventory);
                                if(!empty($item_inventory))
                                {
                                    die_dump('save ke table permintaan pengepakan');
                                    // save ke table permintaan pengepakan
                                    $data_pengepakan = array(

                                        'transaksi_id'   => $box['order_permintaan_barang_id'],
                                        'tipe_transaksi' => 1,
                                        'tanggal'        => date('Y-m-d H:i:s'),
                                        'subjek'         => '',
                                        'keterangan'     => '',
                                        'status'         => 1,

                                    );

                                    $insert_permintaan_pengepakan = $this->permintaan_pengepakan_m->save($data_pengepakan);

                                    $get_detail_order_box = $this->order_permintaan_barang_detail_m->get_detail_order_box($box['order_permintaan_barang_id'])->result_array();
                                    foreach ($get_detail_order_box as $order_detail_box) 
                                    {
                                        //save ke table permintaan pengepakan detail
                                        $data_pengepakan_detail = array(

                                            'permintaan_pengepakan_id'          => $insert_permintaan_pengepakan,
                                            'order_permintaan_barang_id'        => $order_detail_box['order_permintaan_barang_id'],
                                            'order_permintaan_barang_detail_id' => $order_detail_box['id'],
                                            'box_paket_id'                      => 1,
                                            'jumlah_minta'                      => $order_detail_box['jumlah'],
                                            'jumlah_proses'                     => 0,
                                            'is_active'                         => 1,
                                        );

                                        $insert_permintaan_pengepakan_detail = $this->permintaan_pengepakan_detail_m->save($data_pengepakan_detail);
                                    }

                                } else {

                                    die_dump('save ke table permintaan pembelian');

                                }

                            }

                            // die_dump($this->db->last_query()); 
                            // die_dump($invent);
                            // die_dump($item_box_paket);    
                        }

                    
                    } else {

                        // die_dump('bukan order terakhir di box paket');
                        //*proses update persetujuan*//
                        if(isset($box['checkbox']))
                        {
                            // die_dump('item ceklis / tolak');
                            $data = array(

                                'status'              => 4,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => 0,
                                'keterangan'          => $box['keterangan'],
                                'is_active'           => 1,

                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_persetujuan_permintaan_barang($data, $box['persetujuan_permintaan_barang_id'], $array_input['pk_value'], $array_input['user_level_id']);
                            // die_dump($this->db->last_query());
                        
                        } else {

                            $data = array(

                                'status'              => 3,
                                'tanggal_persetujuan' => date('Y-m-d H:i:s'),
                                'disetujui_oleh'      => $user_id,
                                'jumlah_persetujuan'  => $box['jumlah_setujui'],
                                'keterangan'          => $box['keterangan'],
                                'is_active'           => 1,

                            );

                            $update = $this->persetujuan_permintaan_barang_m->update_persetujuan_permintaan_barang($data, $box['persetujuan_permintaan_barang_id'], $array_input['pk_value'], $array_input['user_level_id']);
                            // die_dump($this->db->last_query());

                        }

                        //*end of proses update persetujuan*//

                    }

                }
            
            }

        }


        if ($update) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Order Barang berhasil diproses.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        redirect("pembelian/persetujuan_permintaan_po");
    }

    public function cek_cabang_id($order_permintaan_barang_id,$order_permintaan_barang_detail_id)
    {

        // die_dump('masuk pengecekan cabang');

        $order_permintaan_barang = $this->order_permintaan_barang_m->get_by(array('id' => $order_permintaan_barang_id));
        $array = object_to_array($order_permintaan_barang);
        // die_dump($order_permintaan_barang);
        
        foreach ($array as $row) 
        {
            // die_dump($row);
            $get_alamat = $this->cabang_alamat_m->get_by(array('cabang_id' => $row['cabang_id'], 'is_primary' => 1), true);
            $alamat     = object_to_array($get_alamat);
            // die_dump($alamat['alamat']);
            
            // die(dump($this->session->userdata('cabang_id')));
            if($row['cabang_id'] == 2)
            {
                // die_dump('konversi stock ke biaya');
            
            } 
            elseif($row['cabang_id'] != 2 && $row['cabang_id'] >= 11) 
            {
                
            }
        }
    }


    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->order_permintaan_barang_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        // die_dump($max_id);
        
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // die_dump($trash_id);

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 1,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);
        // die_dump($this->db->last_query());

        // if ($user_id) 
        // {
        //     $flashdata = array(
        //         "type"     => "error",
        //         "msg"      => translate("Branch Deleted", $this->session->userdata("language")),
        //         "msgTitle" => translate("Success", $this->session->userdata("language"))    
        //         );
        //     $this->session->set_flashdata($flashdata);
        // }
        redirect("pembelian/daftar_permintaan_po");
    }

    public function listing_alat_obat($status_so_history = null)
    {


        $result = $this->item_m->get_datatable_item_po($status_so_history);
        // die(dump($result));
        // die(dump($this->db->last_query()));

  
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        // die_dump($records);
        $i = 0;
        foreach($records->result_array() as $row)
        {

            $kategori = '';
            if ($row['item_kategori_id'] == 1)
                $kategori = '<div class="text-center">'.$row['kategori_item'].'</div>';
            if ($row['item_kategori_id'] == 2)
                $kategori = '<div class="text-center">'.$row['kategori_item'].'</div>';
                // $kategori = $row['kategori_item'];
                // 
            $satuan = $this->item_satuan_m->get_by(array('item_id' => $row['id']));
            $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1),true);
            $satuan = object_to_array($satuan);
            // $satuan_primary = object_to_array($satuan_primary);

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($satuan)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
             
             $output['aaData'][] = array(
                $row['kode'],
                $row['nama'],
                '<div class="text-center">'.$action.'</div>'
               );             
         $i++;
        }

       // die(dump($this->db->last_query()));


      echo json_encode($output);

    }

    public function listing_pilih_user_permintaan_po()
    {

        // $cabang_id = $this->session->userdata('cabang_id');
        // die_dump($this->db->last_query());
                // die_dump($cabang_id);
        $result = $this->user_m->get_datatable_pilih_user_po();
        // die_dump($this->db->last_query());
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
            
            $action = '';
            if($row['is_active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id_user'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center">'.$row['nama_user_level'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function lihat_gambar($id_order_detail)
    {
        $data_img = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $id_order_detail, 'tipe' => 2 ));
        // die(dump($this->db->last_query()));

        $data_order_detail = $this->order_permintaan_barang_detail_other_m->get_by(array('id' => $id_order_detail),true);
        $data = array(        
            'data_img'          => ($data_img)?object_to_array($data_img):'',
            'data_order_detail' => object_to_array($data_order_detail)
        );

        $this->load->view('pembelian/persetujuan_permintaan_po/unggah_gambar', $data);
    }

}

/* End of file permintaan_po.php */
/* Location: ./application/controllers/pembelian/permintaan_po.php */