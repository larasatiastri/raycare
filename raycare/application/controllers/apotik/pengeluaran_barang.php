<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengeluaran_barang extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'cf571dbff7c1a875350f6f2dc2a08981';                  // untuk check bit_access

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

        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/item/item_identitas_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');
        $this->load->model('apotik/pengeluaran_barang/pengeluaran_barang_m');
        $this->load->model('apotik/pengeluaran_barang/pengeluaran_barang_detail_m');
        $this->load->model('apotik/pengeluaran_barang/pengeluaran_barang_identitas_m');
        $this->load->model('apotik/pengeluaran_barang/pengeluaran_barang_identitas_detail_m');
        $this->load->model('apotik/pengeluaran_barang/inventory_m');
        $this->load->model('apotik/pengeluaran_barang/inventory_identitas_m');
        $this->load->model('apotik/pengeluaran_barang/inventory_identitas_detail_m');
        $this->load->model('apotik/pengeluaran_barang/gudang_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/pengeluaran_barang/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengeluaran Barang', $this->session->userdata('language')), 
            'header'         => translate('Pengeluaran Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/pengeluaran_barang/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/apotik/pengeluaran_barang/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tambah Pengeluaran Barang', $this->session->userdata('language')), 
            'header'         => translate('Tambah Pengeluaran Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/pengeluaran_barang/add',
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
        $result = $this->pengeluaran_barang_m->get_datatable();
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/pengeluaran_barang/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';

            $data_detail = $this->pengeluaran_barang_detail_m->get_data($row['id'])->result_array();
            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($data_detail)).'" class="item-unlist" data-id="'.$row['id'].'" name="info"><u>'.count($data_detail).' item</u></a>';

            

            $output['data'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.$row['user_penerima'].'</div>',
                '<div class="text-center">'.$row['user_buat'].'</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/apotik/pengeluaran_barang/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->pengeluaran_barang_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->pengeluaran_barang_detail_m->get_data($id)->result_array();
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('View Pengeluaran Barang', $this->session->userdata('language')), 
            'header'         => translate('View Pengeluaran Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/pengeluaran_barang/view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            'form_data_detail'      => object_to_array($form_data_detail),
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_search_item($kategori_sub = null)
    {
        $result = $this->item_m->get_datatable_item($kategori_sub);

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
            if($row['is_active']==1)
            {
                $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $row['id']));
                $data_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'],'is_primary' => 1), true);
                
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($data_satuan)).'" data-satuan_primary="'.htmlentities(json_encode($data_satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';


                $output['aaData'][] = array(
                    '<div class="text-center">'.$row['kode'].'</div>',
                    $row['nama'],
                    '<div class="text-center">'.$action.'</div>',
                );
            $i++;
            }
        }
        echo json_encode($output);
    }

    public function add_identitas($row_id,$item_id,$item_satuan_id)
    {
        $data_item = $this->item_m->get($item_id);
        $data_item_satuan = $this->item_satuan_m->get($item_satuan_id);
        $data_item_identitas = str_split($data_item->identitas_byte);

        $cabang_id = $this->session->userdata('cabang_id');
        // $gudang_id = $this->gudang_m->get_by(array('cabang_klinik' => $cabang_id, ), true);

        $data_inventory = $this->inventory_m->get_data_inventory($item_id,$item_satuan_id)->result_array();
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

        $this->load->view('apotik/pengeluaran_barang/modal_identitas', $body);
    }

    public function get_stok()
    {
        if($this->input->is_ajax_request()){
            $item_id = $this->input->post('item_id');
            $item_satuan_id = $this->input->post('item_satuan_id');

            $cabang_id = $this->session->userdata('cabang_id');
            $gudang_id = $this->gudang_m->get_by(array('cabang_apotik' => $cabang_id, 'cabang_klinik' => 0), true);

            $response = new stdClass;
            $response->stok = 0;

            $stok = $this->inventory_m->get_data_stock($item_id, $item_satuan_id, $gudang_id->id)->row(0);
            if(count($stok)) $response->stok = $stok->jumlah;

            echo json_encode($response);

        }
    }

    public function get_stok_identitas()
    {
        if($this->input->is_ajax_request()){
            $inv_identitas_id = $this->input->post('id');

            $response = new stdClass;
            $response->stok = 0;

            $stok = $this->inventory_identitas_m->get_stok($inv_identitas_id)->row(0);
            if(count($stok)) $response->stok = $stok->jumlah;

            echo json_encode($response);

        }
    }

    public function save()
    {
        $array_input = $this->input->post();
        $command = $array_input['command'];
        $items = $array_input['item'];
        $user_id = $this->session->userdata('user_id');

        // die(dump($array_input));
        if($array_input['command'] == 'add'){

            $data_pengeluaran = array(
                'tanggal'       => date('Y-m-d', strtotime($array_input['tanggal'])),
                'user_penerima' => $array_input['user_penerima'],
                'keterangan'    => $array_input['keterangan'],
            );

            $pengeluaran_brg_id = $this->pengeluaran_barang_m->save($data_pengeluaran);

            $data_inventory_history = array(
                'transaksi_id'   => $pengeluaran_brg_id,
                'transaksi_tipe' => '5'
            );

            //save inventory histoy
            $save_inventory_history = $this->inventory_history_m->save($data_inventory_history);

            foreach ($items as $key => $item) {
                if($item['item_id'] != '' && $item['jumlah'] != 0){

                    if(isset($array_input['identitas_'.$item['item_id']])){
                        foreach ($array_input['identitas_'.$item['item_id']] as $key_identitas => $identitas) {

                            if($identitas['jumlah_identitas'] != 0 && $identitas['jumlah_identitas'] != ''){
                                $data_pengeluaran_detail = array(
                                    'pengeluaran_barang_id' => $pengeluaran_brg_id,
                                    'item_id'               => $item['item_id'],
                                    'item_satuan_id'        => $item['satuan_id'],
                                    'jumlah'                => $identitas['jumlah_identitas'],
                                    'bn_sn_lot'             => $identitas['bn_sn_lot'],
                                    'expire_date'           => date('Y-m-d', strtotime($identitas['expire_date'])),
                                );

                                $pengeluaran_brg_detail_id = $this->pengeluaran_barang_detail_m->save($data_pengeluaran_detail);

                                $cabang_id = $this->session->userdata('cabang_id');
                                $gudang_id = $this->gudang_m->get_by(array('cabang_apotik' => $cabang_id, 'cabang_klinik' => 0), true);
                                
                                $inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['satuan_id'], 'bn_sn_lot' => $identitas['bn_sn_lot'], 'date(expire_date)' => date('Y-m-d', strtotime($identitas['expire_date'])), 'gudang_id' => $identitas['gudang_id'] ));

                                $data_inventory = object_to_array($inventory);

                                 $x = 1;
                                $sisa = 0;
                                foreach ($data_inventory as $row_inv) {
                                    if($x == 1 && $identitas['jumlah_identitas'] >= $row_inv['jumlah']){

                                        $sisa = $identitas['jumlah_identitas'] - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => ($row_inv['harga_beli']*$row_inv['jumlah']),
                                        );
                                        
                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                                    }
                                    if($x == 1 && $identitas['jumlah_identitas'] < $row_inv['jumlah']){

                                        $sisa = 0;
                                        $sisa_inv = $row_inv['jumlah'] - $identitas['jumlah_identitas'];

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($identitas['jumlah_identitas'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => ($row_inv['harga_beli']*$identitas['jumlah_identitas']),
                                        );
                                        
                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                                    }
                                    if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){
                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
                                            'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                            'pmb_id'               => $row_inv['pmb_id'],
                                            'item_id'              => $row_inv['item_id'],
                                            'item_satuan_id'       => $row_inv['item_satuan_id'],
                                            'initial_stock'        => $row_inv['jumlah'],
                                            'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                            'final_stock'          => $sisa_inv,
                                            'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                            'expire_date'          => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_beli'           => $row_inv['harga_beli'],
                                            'total_harga'          => ($row_inv['harga_beli']*$row_inv['jumlah']),
                                        );
                                        
                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                        $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){

                                        $sisa_inv = $row_inv['jumlah'] - $sisa;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $array_input['gudang_ke'],
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

                        $data_inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['satuan_id'])); 
                        $data_inventory = object_to_array($data_inventory);

                        if(count($data_inventory)){

                            $sisa = 0;
                            $x = 1;
                            foreach ($data_inventory as $inventory) {
                                if($x == 1){
                                    if($item['jumlah'] >= $inventory['jumlah']){

                                        $data_inventory_nol['jumlah'] = 0;
                                        $wheres1['inventory_id']   = $inventory['inventory_id'];

                                        $inventory_update = $this->inventory_m->update_by($user_id, $data_inventory_nol, $wheres1);

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $save_inventory_history,
                                            'gudang_id'            => $inventory['gudang_id'],
                                            'pembelian_detail_id'  => $inventory['pembelian_detail_id'],
                                            'pmb_id'               => $inventory['pmb_id'],
                                            'item_id'              => $inventory['item_id'],
                                            'item_satuan_id'       => $inventory['item_satuan_id'],
                                            'initial_stock'        => $inventory['jumlah'],
                                            'change_stock'         => ($item['jumlah'] * (-1)),
                                            'final_stock'          => ($inventory['jumlah'] - $item['jumlah']),
                                            'harga_beli'           => $inventory['harga_beli'],
                                            'total_harga'          => ($inventory['harga_beli']*($item['jumlah'] * (-1))),
                                        );
                                        
                                        $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);
                                        $sisa = ($item['jumlah'] - $inventory['jumlah']);

                                        $delete_inventory = $this->inventory_m->delete_by($wheres1);
                                    }
                                    elseif($item['jumlah'] < $inventory['jumlah']){

                                        $data_inventory_sisa['jumlah'] = intval($inventory['jumlah']) - intval($item['jumlah']);
                                        $wheres2['inventory_id']   = $inventory['inventory_id'];

                                        $inventory_update = $this->inventory_m->update_by($user_id, $data_inventory_sisa, $wheres2);

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $save_inventory_history,
                                            'gudang_id'            => $inventory['gudang_id'],
                                            'pembelian_detail_id'  => $inventory['pembelian_detail_id'],
                                            'pmb_id'               => $inventory['pmb_id'],
                                            'item_id'              => $inventory['item_id'],
                                            'item_satuan_id'       => $inventory['item_satuan_id'],
                                            'initial_stock'        => $inventory['jumlah'],
                                            'change_stock'         => ($item['jumlah'] * (-1)),
                                            'final_stock'          => ($inventory['jumlah'] - $item['jumlah']),
                                            'harga_beli'           => $inventory['harga_beli'],
                                            'total_harga'          => ($inventory['harga_beli']*($item['jumlah'] * (-1))),
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
                                                'gudang_id'            => $inventory['gudang_id'],
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
                                                'gudang_id'            => $inventory['gudang_id'],
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

        }
        redirect('apotik/pengeluaran_barang');
    }

    public function modal_detail($id, $item_id, $item_satuan_id)
    {   
        $data_item = $this->item_m->get($item_id);
        $data_item_satuan = $this->item_satuan_m->get($item_satuan_id);
        $data_item_identitas = $this->item_identitas_m->data_item_identitas($item_id)->result_array();
        $data_item_identitas = (count($data_item_identitas) != 0)?object_to_array($data_item_identitas):'';

        $pengeluaran_detail = $this->pengeluaran_barang_detail_m->get_data_detail($id, $item_id, $item_satuan_id)->result_array();
        
        $data = array(
            'pengeluaran_detail'  => $pengeluaran_detail,
            'item_id'             => $item_id,
            'item_satuan_id'      => $item_satuan_id,
            'data_item'           => object_to_array($data_item),
            'data_item_satuan'    => object_to_array($data_item_satuan),
            'data_item_identitas' => $data_item_identitas,
        );
        $this->load->view('apotik/pengeluaran_barang/modal_detail', $data);  
    }
}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */