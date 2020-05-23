<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Retur_pembelian extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '68ab3de7598242ab9ce189a68f15cee6';                  // untuk check bit_access

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

        $this->load->model('pembelian/retur_pembelian_m');
        $this->load->model('pembelian/retur_pembelian_detail_m');
        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('gudang/barang_datang/pmb_m');
        $this->load->model('gudang/barang_datang/pmb_po_detail_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('master/identitas_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_gambar_m');
        $this->load->model('master/user_m');
        $this->load->model('master/user_level_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('pembelian/supplier_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_telepon_m');
        $this->load->model('master/supplier/supplier_email_m');
        $this->load->model('apotik/penjualan_obat/inventory_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_detail_m');
        $this->load->model('master/info_alamat_m');
    }

    
    public function index()
    {
        $assets = array();
        $config = 'assets/pembelian/retur_pembelian_obat/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Retur Pembelian Obat', $this->session->userdata('language')), 
            'header'         => translate('Retur Pembelian Obat', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/retur_pembelian_obat/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/pembelian/retur_pembelian_obat/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Retur History Pembelian Obat', $this->session->userdata('language')), 
            'header'         => translate('Retur History Pembelian Obat', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/retur_pembelian_obat/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
    
    public function edit($id)
    {
        $assets = array();
        $config = 'assets/pembelian/retur_pembelian_obat/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->retur_pembelian_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->retur_pembelian_detail_m->get_data($id)->result_array();
        $form_data_po = $this->pembelian_m->get_by(array('id' => $form_data->pembelian_id), true);
        $user = $this->user_m->get_by(array('id' => $form_data->created_by), true);
        $supplier = $this->supplier_m->get_by(array('id' => $form_data->supplier_id), true);

        // die_dump($form_data);

        $data = array(
            'title'            => 'Klinik Raycare | '.translate('Edit Retur Pembelian Obat', $this->session->userdata('language')), 
            'header'           => translate('Edit Retur Pembelian Obat', $this->session->userdata('language')), 
            'header_info'      => 'Klinik Raycare', 
            'breadcrumb'       => true,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            'form_data_po' => object_to_array($form_data_po),
            'supplier' => object_to_array($supplier),
            'user' => object_to_array($user),
            'content_view'     => 'pembelian/retur_pembelian_obat/edit',
            'pk_value'         => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/pembelian/retur_pembelian_obat/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->retur_pembelian_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->retur_pembelian_detail_m->get_data($id)->result_array();
        $form_data_po = $this->pembelian_m->get_by(array('id' => $form_data->pembelian_id), true);
        $user = $this->user_m->get_by(array('id' => $form_data->created_by), true);
        $supplier = $this->supplier_m->get_by(array('id' => $form_data->supplier_id), true);

        // die_dump($form_data);

        $data = array(
            'title'            => 'Klinik Raycare | '.translate('View Retur Pembelian Obat', $this->session->userdata('language')), 
            'header'           => translate('View Retur Pembelian Obat', $this->session->userdata('language')), 
            'header_info'      => 'Klinik Raycare', 
            'breadcrumb'       => true,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            'form_data_po' => object_to_array($form_data_po),
            'supplier' => object_to_array($supplier),
            'user' => object_to_array($user),
            'content_view'     => 'pembelian/retur_pembelian_obat/view',
            'pk_value'         => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);

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
        // die(dump($array_input));
        $items = $array_input['item'];
        $user_id = $this->session->userdata('user_id');

        if($array_input['command'] === 'edit'){

            $status = 1;
            if($array_input['tipe_retur'] == 2 && $array_input['no_cn'] != '' && $array_input['nominal_cn'] != '' && $array_input['url_cn'] != '' ){
                $status = 3;
            }

            $data_retur_pembelian = array(
                'tipe'                    => $array_input['tipe_retur'],
                'tanggal'                 => date('Y-m-d', strtotime($array_input['tanggal'])),
                'keterangan'              => $array_input['keterangan'],
                'no_cn'                   => $array_input['no_cn'],
                'nominal_cn'              => $array_input['nominal_cn'],
                'url_cn'                  => $array_input['url_cn'],
                'status'                  => $status,
                'is_active'               => 1,
                'created_by'              => $this->session->userdata('user_id'),
                'created_date'            => date('Y-m-d H:i:s'),
            );

            $retur_pembelian_obat = $this->retur_pembelian_m->edit_data($data_retur_pembelian, $array_input['id']);

            if($array_input['url_cn'] != ''){

                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/pembelian/retur_pembelian_obat/images/'.str_replace(' ', '_', $array_input['id']);
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url_cn'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $array_input['url_cn'];
                $real_file = str_replace(' ', '_', $array_input['id']).'/'.$new_filename;

                copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_faktur_retur_po_farmasi').$real_file);
            }

             redirect(base_url().'pembelian/retur_pembelian/history');

        }
        if($array_input['command'] === 'add'){

            $last_id       = $this->retur_pembelian_m->get_max_id_retur_pembelian()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'RT-'.date('m').'-'.date('Y').'-%04d';
            $id_retur_po = sprintf($format_id, $last_id, 4);
            
            
            $last_number   = $this->retur_pembelian_m->get_no_retur_pembelian()->result_array();
            $last_number   = intval($last_number[0]['max_no_penjualan'])+1;
            
            
            $format        = '#RTP#%03d/RHS/'.romanic_number(date('m'), true).'/'.date('Y');
            $no_retur_po     = sprintf($format, $last_number, 3);

            $last_number_do   = $this->retur_pembelian_m->get_no_surat_jalan()->result_array();
            $last_number_do   = intval($last_number_do[0]['max_no_surat_jalan'])+1;
            
            
            $format        = '#DO#%03d/RHS/'.romanic_number(date('m'), true).'/'.date('Y');
            $no_do     = sprintf($format, $last_number_do, 3);

            $data_retur_pembelian = array(
                'id'                      => $id_retur_po,
                'no_retur'                => $no_retur_po,
                'no_surat_jalan'          => $no_do,
                'tipe'                    => $array_input['tipe_retur'],
                'supplier_id'            => $array_input['supplier_id'],
                'pembelian_id'            => $array_input['pembelian_id'],
                'tipe_po'                 => 1,
                'tanggal'                 => date('Y-m-d', strtotime($array_input['tanggal'])),
                'nominal'                 => $array_input['total'],
                'keterangan'              => $array_input['keterangan'],
                'status'                  => 1,
                'is_active'               => 1,
                'created_by'              => $this->session->userdata('user_id'),
                'created_date'            => date('Y-m-d H:i:s'),
            );

            $retur_pembelian_obat = $this->retur_pembelian_m->add_data($data_retur_pembelian);

            $data_inventory_history = array(
                'transaksi_id'   => $id_retur_po,
                'transaksi_tipe' => '19'
            );

            //save inventory histoy
            $save_inventory_history = $this->inventory_history_m->save($data_inventory_history);

            $data_po = array(
                'status' => 4
            );

            $edit_pembelian = $this->pembelian_m->edit_data($data_po, $array_input['pembelian_id']);


            foreach ($items as $key => $item) {
                if($item['item_id'] != '' && $item['jml_retur'] != 0){

                    $data_inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['item_satuan_id'], 'bn_sn_lot' => $item['bn_sn_lot'], 'expire_date' => date('Y-m-d', strtotime($item['expire_date']))));
                    
                    $data_inventory = object_to_array($data_inventory);

                    $data_po_detail = $this->pembelian_detail_m->get_by(array('id' => $item['po_detail_id']), true);

                    $nilai_konversi         = $this->item_m->get_nilai_konversi($item['item_satuan_id']);

                    // die(dump($nilai_konversi));
                    $jumlah_retur = $item['jml_retur'] * $nilai_konversi;

                    $jumlah_belum_diterima = $data_po_detail->jumlah_belum_diterima + $jumlah_retur;

                    $array_po_detail = array(
                        'jumlah_belum_diterima' => $jumlah_belum_diterima
                    );

                    $edit_po_detail = $this->pembelian_detail_m->edit_data($array_po_detail, $data_po_detail->id);


                    $x = 1;
                    $sisa = 0;
                    foreach ($data_inventory as $row_inv) {
                        if($x == 1 && $item['jml_retur'] >= $row_inv['jumlah']){

                            $last_id_detail       = $this->retur_pembelian_detail_m->get_max_id_retur_pembelian_detail()->result_array();
                            $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                            
                            $format_id_detail     = 'RTD-'.date('m').'-'.date('Y').'-%04d';
                            $id_retur_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                            $data_penjualan_detail = array(
                                'id'                => $id_retur_detail,
                                'retur_pembelian_id' => $id_retur_po,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'item_id'           => $row_inv['item_id'],
                                'item_satuan_id'    => $row_inv['item_satuan_id'],
                                'jumlah'            => $row_inv['jumlah'],
                                'jumlah_belum_diterima'            => $row_inv['jumlah'],
                                'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                'hpp'               => $item['harga_beli_primary'],
                                'created_by'        => $this->session->userdata('user_id'),
                                'created_date'      => date('Y-m-d H:i:s'),
                            );

                            $penjualan_obat_detail = $this->retur_pembelian_detail_m->add_data($data_penjualan_detail);

                            $sisa = $item['jml_retur'] - $row_inv['jumlah'];
                            $sisa_inv = 0;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $save_inventory_history,
                                'gudang_id'            => $row_inv['gudang_id'],
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

                        if($x == 1 && $item['jml_retur'] < $row_inv['jumlah']){
                            $last_id_detail       = $this->retur_pembelian_detail_m->get_max_id_retur_pembelian_detail()->result_array();
                            $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                            
                            $format_id_detail     = 'RTD-'.date('m').'-'.date('Y').'-%04d';
                            $id_retur_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                            $data_penjualan_detail = array(
                                'id'                => $id_retur_detail,
                                'retur_pembelian_id' => $id_retur_po,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'item_id'           => $row_inv['item_id'],
                                'item_satuan_id'    => $row_inv['item_satuan_id'],
                                'jumlah'            => $item['jml_retur'],
                                'jumlah_belum_diterima'            => $item['jml_retur'],
                                'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                               'hpp'               => $item['harga_beli_primary'],
                                'created_by'        => $this->session->userdata('user_id'),
                                'created_date'      => date('Y-m-d H:i:s'),
                            );

                            $penjualan_obat_detail = $this->retur_pembelian_detail_m->add_data($data_penjualan_detail);

                            $sisa = 0;
                            $sisa_inv = $row_inv['jumlah'] - $item['jml_retur'];

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $save_inventory_history,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($item['jml_retur'] * (-1)),
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'          => $item['jml_retur'] * $row_inv['harga_beli'],
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

                            $last_id_detail       = $this->retur_pembelian_detail_m->get_max_id_retur_pembelian_detail()->result_array();
                            $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                            
                            $format_id_detail     = 'RTD-'.date('m').'-'.date('Y').'-%04d';
                            $id_retur_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                            $data_penjualan_detail = array(
                                'id'                => $id_retur_detail,
                                'retur_pembelian_id' => $id_retur_po,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'item_id'           => $row_inv['item_id'],
                                'item_satuan_id'    => $row_inv['item_satuan_id'],
                                'jumlah'            => $row_inv['jumlah'],
                                'jumlah_belum_diterima'            => $row_inv['jumlah'],
                                'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                               'hpp'               => $item['harga_beli_primary'],
                                'created_by'        => $this->session->userdata('user_id'),
                                'created_date'      => date('Y-m-d H:i:s'),
                            );

                            $penjualan_obat_detail = $this->retur_pembelian_detail_m->add_data($data_penjualan_detail);

                            $sisa = $sisa - $row_inv['jumlah'];
                            $sisa_inv = 0;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $save_inventory_history,
                                'gudang_id'            => $row_inv['gudang_id'],
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

                            $last_id_detail       = $this->retur_pembelian_detail_m->get_max_id_retur_pembelian_detail()->result_array();
                            $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                            
                            $format_id_detail     = 'RTD-'.date('m').'-'.date('Y').'-%04d';
                            $id_retur_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                            $data_penjualan_detail = array(
                                'id'                => $id_retur_detail,
                                'retur_pembelian_id' => $id_retur_po,
                                'gudang_id'            => $row_inv['gudang_id'],
                                'item_id'           => $row_inv['item_id'],
                                'item_satuan_id'    => $row_inv['item_satuan_id'],
                                'jumlah'            => $sisa,
                                'jumlah_belum_diterima'            => $sisa,
                                'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                               'hpp'               => $item['harga_beli_primary'],
                                'created_by'        => $this->session->userdata('user_id'),
                                'created_date'      => date('Y-m-d H:i:s'),
                            );

                            $penjualan_obat_detail = $this->retur_pembelian_detail_m->add_data($data_penjualan_detail);

                            $sisa_inv = $row_inv['jumlah'] - $sisa;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $save_inventory_history,
                                'gudang_id'            => $row_inv['gudang_id'],
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

             redirect(base_url().'pembelian/retur_pembelian');

        }
       
    }

    public function listing()
    {
        
        $result = $this->retur_pembelian_m->get_datatable();

        // Output
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
            $retur_buat_detail = $this->retur_pembelian_detail_m->get_by(array('retur_pembelian_id' => $row['id']));
            $retur_buat_detail = object_to_array($retur_buat_detail);

            $grand_total = 0;
            foreach ($retur_buat_detail as $key => $detail) {
                $grand_total = $grand_total + ($detail['jumlah'] * $detail['hpp']);
            }

            $status = '';
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/retur_pembelian/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a><a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'pembelian/retur_pembelian/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a><a title="'.translate('Print', $this->session->userdata('language')).'" href="'.base_url().'pembelian/retur_pembelian/print_bukti_retur/'.$row['id'].'" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i></a>';

            if($row['tipe'] == 1 && $row['status'] == 1)
            {
                
                $status = '<div class="text-left"><span class="label label-md label-danger">Menunggu Penerimaan Barang</span></div>';
            }
            else if($row['tipe'] == 1 && $row['status'] == 2)
            {
                $action .= '<a title="'.translate('Finish Retur PO', $this->session->userdata('language')).'" data-target="#modal_finish_retur" data-toggle="modal" href="'.base_url().'pembelian/retur_pembelian/selesaikan_retur/'.$row['id'].'" class="btn btn-primary finish_retur"><i class="fa fa-check"></i></a>';
          
                                
                $status = '<div class="text-left"><span class="label label-md label-warning">Barang Diterima Sebagian</span></div>';
            }
            else if($row['tipe'] == 1 && $row['status'] == 3)
            {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/retur_pembelian/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';

                $status = '<div class="text-left"><span class="label label-md label-info">Selesai</span></div>';
            }

            if($row['tipe'] == 2 && $row['status'] == 1)
            {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/retur_pembelian/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a><a title="'.translate('Upload CN', $this->session->userdata('language')).'" href="'.base_url().'pembelian/retur_pembelian/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-check"></i></a><a title="'.translate('Print', $this->session->userdata('language')).'" href="'.base_url().'pembelian/retur_pembelian/print_bukti_retur/'.$row['id'].'" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i></a>';

                $status = '<div class="text-left"><span class="label label-md label-warning">Menunggu Pengembalian Dana</span></div>';
            }
            else if($row['tipe'] == 2 && $row['status'] == 3)
            {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'pembelian/retur_pembelian/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';

                $status = '<div class="text-left"><span class="label label-md label-info">Selesai</span></div>';
            }

            $tipe = 'Tukar Barang';
            if($row['tipe'] == 2){
                $tipe = 'Kembali Uang';
            }


            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>' ,
                '<div class="text-left">'.$row['supplier_nama'].'</div>',
                '<div class="text-center">'.$row['no_retur'].'</div>',
                '<div class="text-center">'.$row['no_surat_jalan'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-right">'.formatrupiah($grand_total).'</div>' ,
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-center inline-button-table">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pembelian($supplier_id)
    {
        
        $result = $this->pembelian_m->get_datatable_history_by_supp($supplier_id, 1);

        // Output
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
            $row_detail = $this->pmb_po_detail_m->get_data_terima_po($row['id']);

            $row['tanggal_pesan'] = date('d M Y', strtotime($row['tanggal_pesan']));

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-item_detail="'.htmlentities(json_encode($row_detail)).'" class="btn btn-primary select-pembelian"><i class="fa fa-check"></i></a>';
               
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-center">'.$row['tanggal_pesan'].'</div>' ,
                '<div class="text-center">'.$row['no_po'].'</div>',
                '<div class="text-left">'.$row['nama_sup'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
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

        $penjualan_detail = $this->retur_pembelian_detail_m->get_data_detail($id, $item_id, $item_satuan_id)->result_array();
        
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

    public function print_bukti_retur($id)
    {
        $this->load->library('mpdf/mpdf.php');


        $form_data = $this->retur_pembelian_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->retur_pembelian_detail_m->get_data($id)->result_array();
        $form_data_po = $this->pembelian_m->get_by(array('id' => $form_data->pembelian_id), true);
        $user = $this->user_m->get_by(array('id' => $form_data->created_by), true);
        $supplier = $this->supplier_m->get_by(array('id' => $form_data->supplier_id), true);


        $mpdf = new mPDF('utf-8','A4', 1, '', 10, 10, 28, 28, 0, 5);
        
        $body = array(
            'form_data'  => object_to_array($form_data),
            'form_data_detail'  => object_to_array($form_data_detail),
            'form_data_po'  => object_to_array($form_data_po),
        );

        // die(dump($body));

        $stylesheets = file_get_contents(base_url().'assets/metronic/global/plugins/bootstrap/css/bootstrap.css');
        $mpdf->writeHTML($stylesheets, 1);
        $filename = $form_data->no_retur.'.pdf';
        $filename = str_replace('/', '_', $filename);
        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/pembelian/retur_pembelian_obat/doc/PO/'.$form_data->id;
        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
       
        // $html = str_replace('{PAGECNT}', $mpdf->getPageCount(), $this->load->view('pembelian/retur_pembelian_obat/cetak/header', $body, true));
        $mpdf->SetHTMLHeader($this->load->view('pembelian/retur_pembelian_obat/cetak/header', $body, true));
        $mpdf->SetHTMLFooter($this->load->view('pembelian/retur_pembelian_obat/cetak/footer', $body, true));
        $mpdf->writeHTML($this->load->view('pembelian/retur_pembelian_obat/cetak/print_retur', $body, true));
// 
        $mpdf->Output('retur_pembelian_'.date('Y-m-d H:i:s').'.pdf', 'I');
        $mpdf->Output($path_dokumen.'/'.$filename, 'F');

        $mpdf->Output('retur_pembelian_'.$form_data->no_retur.'.pdf', 'I'); 


    }
    

}

/* End of file pembayaran.php */
/* Location: ./application/controllers/reservasi/pembayaran.php */
