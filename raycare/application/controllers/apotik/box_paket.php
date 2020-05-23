<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Box_paket extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'f5a96b9a3e89ea5844a3210f481698e1';                  // untuk check bit_access

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

        $this->load->model('apotik/penjualan_obat/inventory_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_detail_m');
        $this->load->model('apotik/box_paket/box_paket_m');
        $this->load->model('apotik/box_paket/box_paket_detail_m');
        $this->load->model('apotik/box_paket/t_box_paket_m');
        $this->load->model('apotik/box_paket/t_box_paket_detail_m');
        $this->load->model('apotik/box_paket/t_box_paket_history_m');
        $this->load->model('apotik/box_paket/t_box_paket_detail_history_m');

        $this->load->model('apotik/item_identitas_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('master/identitas_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_gambar_m');
        $this->load->model('master/user_m');
        $this->load->model('master/user_level_m');
        $this->load->model('master/item/item_harga_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/box_paket/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Box Paket', $this->session->userdata('language')), 
            'header'         => translate('Box Paket', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/box_paket/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/apotik/box_paket/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Stok Box Paket', $this->session->userdata('language')), 
            'header'         => translate('Stok Box Paket', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/box_paket/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
    

    public function history_all()
    {
        $assets = array();
        $config = 'assets/apotik/box_paket/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Box Paket', $this->session->userdata('language')), 
            'header'         => translate('History Box Paket', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/box_paket/history_all',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
    
    public function view($id)
    {
        $assets = array();
        $config = 'assets/apotik/box_paket/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->t_box_paket_m->get_by(array('id' => $id), true);
        $form_data_invoice = $this->invoice_m->get_by(array('id' => $form_data->invoice_id), true);
        $form_data_detail = $this->t_box_paket_detail_m->get_data($id)->result_array();

        $data = array(
            'title'            => 'RayCare | '.translate('View Box Paket', $this->session->userdata('language')), 
            'header'           => translate('View Box Paket', $this->session->userdata('language')), 
            'header_info'      => 'RayCare', 
            'breadcrumb'       => true,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'form_data'        => object_to_array($form_data),
            'form_data_invoice'        => object_to_array($form_data_invoice),
            'form_data_detail' => object_to_array($form_data_detail),
            'content_view'     => 'apotik/box_paket/view',
            'pk_value'         => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

   

    public function listing_item()
    {
        $tanggal = date('Y-m-d');

        $result = $this->item_m->get_datatable_index_item($tanggal);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));

        $i=0;
        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $id = $row['id'];
            $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $row['id']));
            $data_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'],'is_primary' => 1), true);

            $harga_item = $this->item_harga_m->get_harga_item_satuan($row['id'],$data_satuan_primary->id)->row(0);

            $data_gambar_primary = $this->item_gambar_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1, 'is_active' => 1), true);
            $data_gambar_primary = object_to_array($data_gambar_primary);

           
            $row['tipe_item'] = '2';

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($data_satuan)).'" data-satuan_primary="'.htmlentities(json_encode($data_satuan_primary)).'" data-harga="'.htmlentities(json_encode($harga_item)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                $row['kode'],                
                $row['nama'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function add_identitas($row_id,$item_id,$item_satuan_id, $gudang_id)
    {
        $data_item = $this->item_m->get($item_id);
        $data_item_satuan = $this->item_satuan_m->get($item_satuan_id);
        $data_item_identitas = str_split($data_item->identitas_byte);

        $data_inventory = $this->inventory_m->get_data_inventory($item_id,$item_satuan_id,$gudang_id)->result_array();
        $data_inventory = (count($data_inventory) != 0)?object_to_array($data_inventory):'';

        $index = explode('_', $row_id);

        $body = array(
            'row_id'              => $row_id,
            'item_id'             => $item_id,
            'item_satuan_id'      => $item_satuan_id,
            'data_item'           => object_to_array($data_item),
            'data_item_satuan'    => object_to_array($data_item_satuan),
            'data_item_identitas' => $data_item_identitas,
            'data_inventory'      => $data_inventory,
            'index'               => $index[2]
        );

        $this->load->view('apotik/penjualan_obat/modal/modal_identitas', $body);
    }

    public function get_harga()
    {
        if($this->input->is_ajax_request()){
            $item_id = $this->input->post('item_id');
            $item_satuan_id = $this->input->post('item_satuan_id');

            $response = new stdClass;
            $response->success = false;

            $harga_item = $this->item_harga_m->get_harga_item_satuan($item_id,$item_satuan_id)->row(0);
            if(count($harga_item) != 0){
                $response->success = true;
                $response->harga = $harga_item->harga;
            }

            die(json_encode($response));

        }
    }

    public function save()
    {
        $array_input = $this->input->post();
        //die(dump($array_input));
        $tipe_paket = $array_input['tipe_paket'];
        $items = $array_input['item'];
        $user_id = $this->session->userdata('user_id');

        if($array_input['command'] === 'add'){

            $biaya_total = 0;
            foreach ($array_input['item'] as $item) {
                if($item['item_id'] != '')
                {
                    $biaya_total = $biaya_total + intval($item['sub_total']);
                }
            }
            
            $tipe_transaksi = 1;
            $biaya_tunai = $biaya_total;
            $biaya_klaim = 0;
            
            $last_id       = $this->t_box_paket_m->get_max_id_box_paket()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'TBP-'.date('m').'-'.date('Y').'-%04d';
            $id_t_box_paket         = sprintf($format_id, $last_id, 4);
            
            
            $last_number   = $this->t_box_paket_m->get_no_box_paket($tipe_paket)->result_array();
            $last_number   = intval($last_number[0]['max_kode_box_paket'])+1;
            
            if($tipe_paket == 1){
                $format             = date('ym').'%04d';
                $no_t_box_paket     = sprintf($format, $last_number, 4);
            }if($tipe_paket == 2){
                $format             = date('ym').'%02d';
                $no_t_box_paket     = sprintf($format, $last_number, 2);
            }
            

            $data_box_paket = array(
                'id'           => $id_t_box_paket,
                'box_paket_id'           => $array_input['box_paket_id_hidden'],
                'kode_box_paket'           => $no_t_box_paket,
                'tipe_paket'    => $tipe_paket,
                'harga_paket'           => $array_input['harga_hidden'],
                'status'           => 1,
                'created_by'   => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s'),
            );

            $penjualan_obat = $this->t_box_paket_m->add_data($data_box_paket);

            $data_inventory_history = array(
                'transaksi_id'   => $id_t_box_paket,
                'transaksi_tipe' => '15'
            );

            //save inventory histoy
            $save_inventory_history = $this->inventory_history_m->save($data_inventory_history);

            /*$max_inventory_id = $this->inventory_m->max_inventory_id()->result_array();
            if(count($max_inventory_id) != 0){
                $max_inventory_id = intval($max_inventory_id[0]['max_id'])+1;
            }else{
                $max_inventory_id = 1;
            }

            $data_inventory = array(
                'inventory_id'        => $max_inventory_id,
                'box_paket_id'           => $array_input['box_paket_id_hidden'],
                'kode_box_paket'           => $no_t_box_paket,
                'gudang_id'           => $array_input['gudang_stok_id'],
                'jumlah'              => 1,
                'tanggal_datang'      => date('Y-m-d H:i:s'),
                'created_by'          => $this->session->userdata('user_id'),
                'created_date'        => date('Y-m-d H:i:s'),
            );

            $inventory  = $this->inventory_m->add_data($data_inventory);

            $data_inv_hist_detail = array(
                'inventory_history_id' => $save_inventory_history,
                'gudang_id'           => $array_input['gudang_stok_id'],
                'box_paket_id'           => $array_input['box_paket_id_hidden'],
                'kode_box_paket'           => $no_t_box_paket,
                'initial_stock'       => 0,
                'change_stock'        => 1,
                'final_stock'        => 1,
            );

            $inv_history_detail = $this->inventory_history_detail_m->save($data_inv_hist_detail);*/
            foreach ($items as $key => $item) {
                if($item['item_id'] != '' && $item['qty'] != 0){
                    

                    if(isset($array_input['identitas_'.$item['item_id']])){
                        $harga_beli_total = 0;
                        foreach ($array_input['identitas_'.$item['item_id']] as $key_identitas => $identitas) {
                            
                            if($identitas['jumlah_identitas'] != 0){

                                $data_inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['satuan_id'], 'bn_sn_lot' => $identitas['bn_sn_lot'], 'expire_date' => date('Y-m-d', strtotime($identitas['expire_date'])), 'gudang_id' => $array_input['gudang_id']));
                    
                                $data_inventory = object_to_array($data_inventory);
                                //die(dump($this->db->last_query()));

                                $x = 1;
                                $sisa = 0;
                                foreach ($data_inventory as $row_inv) {
                                    if($x == 1 && $identitas['jumlah_identitas'] >= $row_inv['jumlah']){

                                        for($y=0;$y<$row_inv['jumlah'];$y++){
                                            $last_id_detail       = $this->t_box_paket_detail_m->get_max_id_box_paket_detail()->result_array();
                                            $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                            
                                            $format_id_detail     = 'TBPD-'.date('m').'-'.date('Y').'-%04d';
                                            $id_t_box_paket_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                            $data_penjualan_detail = array(
                                                'id'                => $id_t_box_paket_detail,
                                                't_box_paket_id' => $id_t_box_paket,
                                                'item_id'           => $row_inv['item_id'],
                                                'item_satuan_id'    => $row_inv['item_satuan_id'],
                
                                                'jumlah'            => 1,
                                                'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                                'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                                'harga_beli'        => $row_inv['harga_beli'],
                                                'created_by'        => $this->session->userdata('user_id'),
                                                'created_date'      => date('Y-m-d H:i:s'),
                                            );

                                            $penjualan_obat_detail = $this->t_box_paket_detail_m->add_data($data_penjualan_detail);
                                        }

                                        
                                        $harga_beli_total += $row_inv['jumlah'] * $row_inv['harga_beli'];

                                        $sisa = $identitas['jumlah_identitas'] - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $save_inventory_history,
                                            'gudang_id'            => $array_input['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'        => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));

                                    }

                                    if($x == 1 && $identitas['jumlah_identitas'] < $row_inv['jumlah']){

                                        for($a=0;$a<$identitas['jumlah_identitas'];$a++){
                                            $last_id_detail       = $this->t_box_paket_detail_m->get_max_id_box_paket_detail()->result_array();
                                            $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                            
                                            $format_id_detail     = 'TBPD-'.date('m').'-'.date('Y').'-%04d';
                                            $id_t_box_paket_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                            $data_penjualan_detail = array(
                                                'id'                => $id_t_box_paket_detail,
                                                't_box_paket_id' => $id_t_box_paket,
                                                'item_id'           => $row_inv['item_id'],
                                                'item_satuan_id'    => $row_inv['item_satuan_id'],
                
                                                'jumlah'            => 1,
                                                'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                                'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                                'harga_beli'        => $identitas['harga_beli'],
                                                'created_by'        => $this->session->userdata('user_id'),
                                                'created_date'      => date('Y-m-d H:i:s'),
                                            );

                                            $penjualan_obat_detail = $this->t_box_paket_detail_m->add_data($data_penjualan_detail);
                                        }
                                        
                                        $harga_beli_total += $row_inv['jumlah'] * $row_inv['harga_beli'];

                                        $sisa = 0;
                                        $sisa_inv = $row_inv['jumlah'] - $identitas['jumlah_identitas'];

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $save_inventory_history,
                                            'gudang_id'            => $array_input['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($identitas['jumlah_identitas'] * (-1)),
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $identitas['jumlah_identitas'] * $row_inv['harga_beli'],
                                            'final_stock'          => $sisa_inv,
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $data_update = array('jumlah' => $sisa_inv);
                                        $where_update = array('inventory_id' => $row_inv['inventory_id']);
                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),$data_update, $where_update);
                                    }

                                    if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                                        for($i=0;$i<$row_inv['jumlah'];$i++){
                                            $last_id_detail       = $this->t_box_paket_detail_m->get_max_id_box_paket_detail()->result_array();
                                            $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                            
                                            $format_id_detail     = 'TBPD-'.date('m').'-'.date('Y').'-%04d';
                                            $id_t_box_paket_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                            $data_penjualan_detail = array(
                                                'id'                => $id_t_box_paket_detail,
                                                't_box_paket_id' => $id_t_box_paket,
                                                'item_id'           => $row_inv['item_id'],
                                                'item_satuan_id'    => $row_inv['item_satuan_id'],
                
                                                'jumlah'            => 1,
                                                'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                                'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                                'harga_beli'        => $identitas['harga_beli'],
                                                'created_by'        => $this->session->userdata('user_id'),
                                                'created_date'      => date('Y-m-d H:i:s'),
                                            );

                                            $penjualan_obat_detail = $this->t_box_paket_detail_m->add_data($data_penjualan_detail);
                                        }

                                        
                                        $harga_beli_total += $row_inv['jumlah'] * $row_inv['harga_beli'];

                                        $sisa = $sisa - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $save_inventory_history,
                                            'gudang_id'            => $array_input['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){

                                        for($i=0;$i<$sisa;$i++){
                                            $last_id_detail       = $this->t_box_paket_detail_m->get_max_id_box_paket_detail()->result_array();
                                            $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                            
                                            $format_id_detail     = 'TBPD-'.date('m').'-'.date('Y').'-%04d';
                                            $id_t_box_paket_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                            $data_penjualan_detail = array(
                                                'id'                => $id_t_box_paket_detail,
                                                't_box_paket_id' => $id_t_box_paket,
                                                'item_id'           => $row_inv['item_id'],
                                                'item_satuan_id'    => $row_inv['item_satuan_id'],
                
                                                'jumlah'            => $sisa,
                                                'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                                'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                                'harga_beli'        => $identitas['harga_beli'],
                                                'created_by'        => $this->session->userdata('user_id'),
                                                'created_date'      => date('Y-m-d H:i:s'),
                                            );

                                            $penjualan_obat_detail = $this->t_box_paket_detail_m->add_data($data_penjualan_detail);
                                        }
                                        $harga_beli_total += $row_inv['jumlah'] * $row_inv['harga_beli'];

                                        $sisa_inv = $row_inv['jumlah'] - $sisa;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $save_inventory_history,
                                            'gudang_id'            => $array_input['gudang_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'box_paket_id'         => NULL,
                                            'kode_box_paket'       => NULL,
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($sisa * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => $sisa * $row_inv['harga_beli'],
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => $row_inv['expire_date'],
                                            'created_by'           => $this->session->userdata('user_id'),
                                            'created_date'         => date('Y-m-d H:i:s')
                                        );

                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $sisa = 0;

                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    $x++; 
                                }
                            }
                        }
                    }
                    else{

                        $last_id_detail       = $this->t_box_paket_detail_m->get_max_id_box_paket_detail()->result_array();
                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                        
                        $format_id_detail     = 'TBPD-'.date('m').'-'.date('Y').'-%04d';
                        $id_t_box_paket_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                        $data_penjualan_detail = array(
                            'id'                => $id_t_box_paket_detail,
                            't_box_paket_id'    => $id_t_box_paket,
                            'item_id'           => $item['item_id'],
                            'item_satuan_id'    => $item['satuan_id'],
                            'jumlah'            => $item['qty'],
                            'harga_beli'        => $item['harga_beli'],
                            'created_by'        => $this->session->userdata('user_id'),
                            'created_date'      => date('Y-m-d H:i:s'),
                        );

                        $penjualan_obat_detail = $this->t_box_paket_detail_m->add_data($data_penjualan_detail);
                        $harga_beli_total += $item['qty'] * $item['harga_beli'];

                        $data_inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['satuan_id'], 'gudang_id' => $array_input['gudang_id']));
                        $data_inventory = object_to_array($data_inventory); 
                        if(count($data_inventory)){

                            $sisa = 0;
                            $x = 1;
                            foreach ($data_inventory as $inventory) {
                                if($x == 1){
                                    if($item['qty'] >= $inventory['jumlah']){

                                        $data_inventory_nol['jumlah'] = 0;
                                        $wheres1['inventory_id']   = $inventory['inventory_id'];

                                        $inventory_update = $this->inventory_m->update_by($user_id, $data_inventory_nol, $wheres1);

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $save_inventory_history,
                                            'gudang_id'            => $array_input['gudang_id'],
                                            'pembelian_detail_id'  => $inventory['pembelian_detail_id'],
                                            'pmb_id'               => $inventory['pmb_id'],
                                            'item_id'              => $inventory['item_id'],
                                            'item_satuan_id'       => $inventory['item_satuan_id'],
                                            'initial_stock'        => $inventory['jumlah'],
                                            'change_stock'         => ($item['qty'] * (-1)),
                                            'final_stock'          => ($inventory['jumlah'] - $item['qty']),
                                            'harga_beli'           => $inventory['harga_beli'],
                                            'total_harga'          => ($inventory['harga_beli']*($item['qty'] * (-1))),
                                        );
                                        
                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);
                                        $sisa = ($item['qty'] - $inventory['jumlah']);

                                        $delete_inventory = $this->inventory_m->delete_by($wheres1);
                                    }
                                    elseif($item['qty'] < $inventory['jumlah']){

                                        $data_inventory_sisa['jumlah'] = intval($inventory['jumlah']) - intval($item['qty']);
                                        $wheres2['inventory_id']   = $inventory['inventory_id'];

                                        $inventory_update = $this->inventory_m->update_by($user_id, $data_inventory_sisa, $wheres2);

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $save_inventory_history,
                                            'gudang_id'            => $array_input['gudang_id'],
                                            'pembelian_detail_id'  => $inventory['pembelian_detail_id'],
                                            'pmb_id'               => $inventory['pmb_id'],
                                            'item_id'              => $inventory['item_id'],
                                            'item_satuan_id'       => $inventory['item_satuan_id'],
                                            'initial_stock'        => $inventory['jumlah'],
                                            'change_stock'         => ($item['qty'] * (-1)),
                                            'final_stock'          => ($inventory['jumlah'] - $item['qty']),
                                            'harga_beli'           => $inventory['harga_beli'],
                                            'total_harga'          => ($inventory['harga_beli']*($item['qty'] * (-1))),
                                        );
                                        
                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);
                                        $sisa = 0;
                                    }
                                }elseif($x != 1){
                                    if($sisa != 0){
                                        if($sisa >= $inventory['jumlah']){

                                            $data_inventory_nol1['jumlah'] = 0;
                                            $wheres3['inventory_id']   = $inventory['inventory_id'];

                                            $inventory_update = $this->inventory_m->update_by($user_id, $data_inventory_nol1, $wheres3);

                                            $data_inventory_history_detail = array(
                                                'inventory_history_id' => $save_inventory_history,
                                                'gudang_id'            => $array_input['gudang_id'],
                                                'pembelian_detail_id'  => $inventory['pembelian_detail_id'],
                                                'pmb_id'               => $inventory['pmb_id'],
                                                'item_id'              => $inventory['item_id'],
                                                'item_satuan_id'       => $inventory['item_satuan_id'],
                                                'initial_stock'        => $inventory['jumlah'],
                                                'change_stock'         => ($sisa * (-1)),
                                                'final_stock'          => ($inventory['jumlah'] - $sisa),
                                                'harga_beli'           => $inventory['harga_beli'],
                                                'total_harga'          => ($inventory['harga_beli']*($sisa * (-1))),
                                            );
                                            
                                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);
                                            $sisa = ($sisa - $inventory['jumlah']);

                                            $delete_inventory = $this->inventory_m->delete_by($wheres3);
                                        }elseif($sisa < $inventory['jumlah']){

                                            $data_inventory_sisa1['jumlah'] = ($inventory['jumlah'] - $sisa);
                                            $wheres4['inventory_id']   = $inventory['inventory_id'];

                                            $inventory_update = $this->inventory_m->update_by($user_id, $data_inventory_sisa1, $wheres4);

                                            $data_inventory_history_detail = array(
                                                'inventory_history_id' => $save_inventory_history,
                                                'gudang_id'            => $array_input['gudang_id'],
                                                'pembelian_detail_id'  => $inventory['pembelian_detail_id'],
                                                'pmb_id'               => $inventory['pmb_id'],
                                                'item_id'              => $inventory['item_id'],
                                                'item_satuan_id'       => $inventory['item_satuan_id'],
                                                'initial_stock'        => $inventory['jumlah'],
                                                'change_stock'         => ($sisa * (-1)),
                                                'final_stock'          => ($inventory['jumlah'] - $sisa),
                                                'harga_beli'           => $inventory['harga_beli'],
                                                'total_harga'          => ($inventory['harga_beli']*($sisa * (-1))),
                                            );
                                            
                                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);
                                            $sisa = 0;
                                        }
                                    }
                                }
                                
                                $x++;
                            }
                        }

                    }

                }
            }

            $data_box_paket_edit = array(
                'harga_beli'     => $harga_beli_total
            );

            $penjualan_obat = $this->t_box_paket_m->edit_data($data_box_paket_edit, $id_t_box_paket);

        }
        $flashdata = array(
            "type"     => "success",
            "msg"      => translate("Data Box Paket berhasil ditambahkan.", $this->session->userdata("language")),
            "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
        redirect('apotik/box_paket');
    }

    public function listing()
    {
        
        $result = $this->t_box_paket_m->get_datatable();

        // Output
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
            $t_box_paket_detail = $this->t_box_paket_detail_m->get_by(array('t_box_paket_id' => $row['id'], 'date(created_date)' => date('Y-m-d', strtotime($row['created_date']))));
            $data_array = object_to_array($t_box_paket_detail);

            $item = $this->item_m->get_item_box_paket($data_array[0]['t_box_paket_id'],date('Y-m-d', strtotime($row['created_date'])))->result_array();
            $jumlah = count($data_array);

            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['id'].'" name="info" style="color:#33348e; text-decoration: none;"><u>'.$jumlah.' item</u></a>';

            if($row['status'] == 1) {
                $status = '<div class="text-center"><span class="label label-md label-warning">Siap Pakai</span></div>';
            }if($row['status'] == 2) {
                $status = '<div class="text-left"><span class="label label-md label-info">Dibooking</span></div>';
            }if($row['status'] == 3) {
                $status = '<div class="text-left"><span class="label label-md label-success">Telah Dipakai</span></div>';
            } 

            $output['aaData'][] = array(
                '<div class="text-left inline-button-table">'.$i.'</div>' ,
                '<div class="text-left">'.$row['kode_box_paket'].'</div>',
                '<div class="text-left">'.$row['nama_box_paket'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga_paket']).'</div>' ,
                '<div class="text-left">'.$info.'</div>',
                '<div class="text-left">'.date('d M Y', strtotime($row['created_date'])).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_dibuat_oleh'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_history()
    {
        
        $result = $this->t_box_paket_history_m->get_datatable();

        // Output
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
            $t_box_paket_detail = $this->t_box_paket_detail_m->get_by(array('t_box_paket_id' => $row['id']));
            $data_array = object_to_array($t_box_paket_detail);

            $item = $this->item_m->get_item_box_paket($data_array[0]['t_box_paket_id'])->result_array();
            $jumlah = count($data_array);

            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['id'].'" name="info" style="color:#33348e; text-decoration: none;"><u>'.$jumlah.' item</u></a>';

            if($row['status'] == 1) {
                $status = '<div class="text-center"><span class="label label-md label-warning">Siap Pakai</span></div>';
            }if($row['status'] == 2) {
                $status = '<div class="text-left"><span class="label label-md label-info">Dibooking</span></div>';
            }if($row['status'] == 3) {
                $status = '<div class="text-left"><span class="label label-md label-success">Telah Dipakai</span></div>';
            } 

            $output['aaData'][] = array(
                '<div class="text-left inline-button-table">'.$i.'</div>' ,
                '<div class="text-left">'.$row['kode_box_paket'].'</div>',
                '<div class="text-left">'.$row['nama_box_paket'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga_paket']).'</div>' ,
                '<div class="text-left">'.$info.'</div>',
                '<div class="text-left">'.date('d M Y', strtotime($row['created_date'])).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_dibuat_oleh'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function modal_detail($id, $item_id, $item_satuan_id)
    {   
        $data_item = $this->item_m->get($item_id);
        $data_item_satuan = $this->item_satuan_m->get($item_satuan_id);
        $data_item_identitas = str_split($data_item->identitas_byte);

        $penjualan_detail = $this->t_box_paket_detail_m->get_data_detail($id, $item_id, $item_satuan_id)->result_array();
        
        $data = array(
            'penjualan_detail'  => $penjualan_detail,
            'item_id'             => $item_id,
            'item_satuan_id'      => $item_satuan_id,
            'data_item'           => object_to_array($data_item),
            'data_item_identitas'           => object_to_array($data_item_identitas),
            'data_item_satuan'    => object_to_array($data_item_satuan),
        );
        $this->load->view('apotik/penjualan_obat/modal/modal_detail', $data);  
    }

    public function get_inventory()
    {
        if($this->input->is_ajax_request()){
            $result = new stdClass;
            $result->success = false;

            $item_id = $this->input->post('item_id');
            $item_satuan_id = $this->input->post('item_satuan_id');
            $gudang_id = $this->input->post('gudang_id');

            $data_inventory = $this->inventory_m->get_data_inventory($item_id,$item_satuan_id,$gudang_id)->result_array();

            if(count($data_inventory) != 0){
                $result->success = true;
            }

            die(json_encode($result));

        }
    }

    public function get_item_box_paket()
    {
        if($this->input->is_ajax_request()){
            $box_paket_id = $this->input->post('box_paket_id');
            $gudang_id = $this->input->post('gudang_id');

            $data_item = $this->box_paket_detail_m->get_item_box_paket($box_paket_id)->result_array();

            $x = 0;
            $xhtml = ""; 
            foreach ($data_item as $item) {
                $xhtml .= '<tr id="item_row_'.$x.'" class="row_item"><td><input name="item['.$x.'][kode]" value="'.$item['kode_item'].'" id="item_kode_'.$x.'" class="form-control" readonly="readonly" width="50%" type="text"><input name="item['.$x.'][item_id]" value="'.$item['item_id'].'" id="item_id_'.$x.'" class="form-control hidden" type="text"></td><td><input name="item['.$x.'][name]" value="'.$item['nama_item'].'" id="item_name_'.$x.'" class="form-control" readonly="readonly" type="text"><div id="identitas_row" class="hidden"></div></td><td><select name="item['.$x.'][satuan_id]" id="item_satuan_id_'.$x.'" class="form-control"><option value="'.$item['item_satuan_id'].'">'.$item['nama_satuan'].'</option></select><input name="item['.$x.'][harga]" value="0" id="item_harga_'.$x.'" class="form-control" type="hidden"></td><td><div class="input-group"><input name="item['.$x.'][qty]" value="0" id="item_qty_'.$x.'" class="form-control" min="0" readonly="readonly" type="number"><span class="input-group-btn"><button type="button" data-toggle="modal" data-target="#popup_modal_jumlah_keluar" href="'.base_url().'apotik/box_paket/add_identitas/item_row_'.$x.'/'.$item['item_id'].'/'.$item['item_satuan_id'].'/'.$gudang_id.'" class="btn blue-chambray add-identitas" name="item['.$x.'][identitas]" title="Tambah Jumlah"><i class="fa fa-info"></i></button></span></div></td></tr>';

                $x++;
            }

            echo $xhtml;

        }
    }
    

}

/* End of file pembayaran.php */
/* Location: ./application/controllers/reservasi/pembayaran.php */
