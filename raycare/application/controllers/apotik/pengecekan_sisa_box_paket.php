<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pengecekan_sisa_box_paket extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'cd34199ce862987d650ac362adade0f7';                  // untuk check bit_access

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
        $config = 'assets/apotik/pengecekan_sisa_box_paket/index';
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
            'content_view'   => 'apotik/pengecekan_sisa_box_paket/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/apotik/pengecekan_sisa_box_paket/history';
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
            'content_view'   => 'apotik/pengecekan_sisa_box_paket/history',
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

   

    public function listing_item($tgl_awal, $tgl_akhir)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }


        $result = $this->t_box_paket_detail_m->get_datatable_terpakai($tgl_awal, $tgl_akhir);
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
            
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                $row['kode'],                
                $row['nama'],                
                $row['bn_sn_lot'],                
                $row['expire_date'],                
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

        $items = $array_input['item'];
        $user_id = $this->session->userdata('user_id');

        $tgl_awal = date('Y-m-d', strtotime($array_input['tgl_awal']));
        $tgl_akhir = date('Y-m-d', strtotime($array_input['tgl_akhir']));

        if($array_input['command'] === 'add'){

            foreach ($items as $item) {
                
                $data_detail = $this->t_box_paket_detail_m->get_data_detail($tgl_awal, $tgl_akhir, $item['item_id'], $item['bn'])->row(0);

                $wheres['status'] = 4;
                $edit_t_box_detail = $this->t_box_paket_detail_m->edit_data($wheres, $data_detail->id);

                $edit_t_box = $this->t_box_paket_m->edit_data($wheres, $data_detail->id_t_box_paket);
                
            }

            $t_box_paket_history = $this->t_box_paket_m->get_by(array('status' => 4));
            $t_box_paket_history = object_to_array($t_box_paket_history);

            foreach ($t_box_paket_history as $box_paket_history) {

                $last_id       = $this->t_box_paket_history_m->get_max_id_box_paket()->result_array();
                $last_id       = intval($last_id[0]['max_id'])+1;
                
                $format_id     = 'TBPH-'.date('m').'-'.date('Y').'-%04d';
                $id_t_box_paket_history         = sprintf($format_id, $last_id, 4);

                $data_box_paket_history = array(
                    'id'                => $id_t_box_paket_history,
                    'box_paket_id'      => $box_paket_history['box_paket_id'],
                    'kode_box_paket'    => $box_paket_history['kode_box_paket'],
                    'tipe_paket'        => $box_paket_history['tipe_paket'],
                    'harga_paket'       => $box_paket_history['harga_paket'],
                    'harga_beli'        => $box_paket_history['harga_beli'],
                    'status'            => $box_paket_history['status'],
                    'dokter_id'         => $box_paket_history['dokter_id'],
                    'pasien_id'         => $box_paket_history['pasien_id'],
                    'tindakan_id'       => $box_paket_history['tindakan_id'],
                    'tanggal_tindakan'  => date('Y-m-d', strtotime($box_paket_history['tanggal_tindakan'])),
                    'tipe_tindakan'     => $box_paket_history['tipe_tindakan'],
                    'created_by'        => $this->session->userdata('user_id'),
                    'created_date'      => date('Y-m-d H:i:s'),
                );

                $t_box_paket_history_save = $this->t_box_paket_history_m->add_data($data_box_paket_history);

                $data_inventory_history = array(
                    'transaksi_id'   => $id_t_box_paket_history,
                    'transaksi_tipe' => '17'
                );

                //save inventory histoy
                $save_inventory_history = $this->inventory_history_m->save($data_inventory_history);

                $t_box_paket_detail_history = $this->t_box_paket_detail_m->get_by(array('t_box_paket_id' => $box_paket_history['id']));
                $t_box_paket_detail_history = object_to_array($t_box_paket_detail_history);

                foreach ($t_box_paket_detail_history as $box_paket_detail_history) {
                    
                    $last_id_detail             = $this->t_box_paket_detail_history_m->get_max_id_box_paket_detail()->result_array();
                    $last_id_detail             = intval($last_id_detail[0]['max_id'])+1;
                    
                    $format_id_detail           = 'TBPDH-'.date('m').'-'.date('Y').'-%04d';
                    $id_t_box_paket_detail_history  = sprintf($format_id_detail, $last_id_detail, 4);

                    $data_penjualan_detail_history = array(
                        'id'                     => $id_t_box_paket_detail_history,
                        't_box_paket_history_id' => $id_t_box_paket_history,
                        'item_id'                => $box_paket_detail_history['item_id'],
                        'item_satuan_id'         => $box_paket_detail_history['item_satuan_id'],
                        'bn_sn_lot'              => $box_paket_detail_history['bn_sn_lot'],
                        'expire_date'            => date('Y-m-d', strtotime($box_paket_detail_history['expire_date'])),
                        'harga_beli'             => $box_paket_detail_history['harga_beli'],
                        'status'                 => $box_paket_detail_history['status'],
                        'perawat_id'             => $box_paket_detail_history['perawat_id'],
                        'tanggal_pakai'          => date('Y-m-d', strtotime($box_paket_detail_history['tanggal_pakai'])),
                        'created_by'             => $this->session->userdata('user_id'),
                        'created_date'           => date('Y-m-d H:i:s'),
                    );

                    $t_box_paket_detail_history_save = $this->t_box_paket_detail_history_m->add_data($data_penjualan_detail_history);

                    if($box_paket_detail_history['status'] == 4){
                        $max_inventory_id = $this->inventory_m->get_max_id()->result_array();
                        if(count($max_inventory_id) != 0){
                            $max_inventory_id = intval($max_inventory_id[0]['max_id'])+1;
                        }else{
                            $max_inventory_id = 1;
                        }

                        $data_inventory = array(
                            'inventory_id'        => $max_inventory_id,
                            'pmb_id'              => $id_t_box_paket_history,  
                            'pembelian_detail_id' => $id_t_box_paket_detail_history,
                            'gudang_id'           => 'WH-05-2016-002',
                            'item_id'             => $box_paket_detail_history['item_id'],
                            'item_satuan_id'      => $box_paket_detail_history['item_satuan_id'],
                            'bn_sn_lot'           => $box_paket_detail_history['bn_sn_lot'],
                            'expire_date'         => date('Y-m-d', strtotime($box_paket_detail_history['expire_date'])),
                            'jumlah'              => 1,
                            'tanggal_datang'      => date('Y-m-d H:i:s'),
                            'harga_beli'          => $box_paket_detail_history['harga_beli'],
                            'created_by'          => $this->session->userdata('user_id'),
                            'created_date'        => date('Y-m-d H:i:s'),
                        );

                        $inventory  = $this->inventory_m->add_data($data_inventory);

                        $data_inv_hist_detail = array(
                            'inventory_history_id' => $save_inventory_history,
                            'gudang_id'           => 'WH-05-2016-002',
                            'pmb_id'              => $id_t_box_paket_history,  
                            'pembelian_detail_id' => $id_t_box_paket_detail_history,
                            'item_id'             => $box_paket_detail_history['item_id'],
                            'item_satuan_id'      => $box_paket_detail_history['item_satuan_id'],
                            'bn_sn_lot'           => $box_paket_detail_history['bn_sn_lot'],
                            'expire_date'         => date('Y-m-d', strtotime($box_paket_detail_history['expire_date'])),
                            'initial_stock'       => 0,
                            'change_stock'        => 1,
                            'final_stock'         => 1,
                            'harga_beli'          => $box_paket_detail_history['harga_beli'],
                            'total_harga'         => $box_paket_detail_history['harga_beli'],
                        );
                        
                        $save_inventory_history_detail = $this->inventory_history_detail_m->save($data_inv_hist_detail);

                        $delete_t_box_detail = $this->t_box_paket_detail_m->delete_by(array('id' => $box_paket_detail_history['id']));

                    }
                }

                $delete_t_box_paket = $this->t_box_paket_m->delete_by(array('id' => $box_paket_history['id']));

            }

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Sisa Box Paket berhasil ditambahkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

        }
        redirect('apotik/pengecekan_sisa_box_paket');
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
    

}

/* End of file pembayaran.php */
/* Location: ./application/controllers/reservasi/pembayaran.php */
