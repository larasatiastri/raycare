<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses_tindakan_vaksin extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '0345472f1366b6dcea3df7c4d6d39cfd';                  // untuk check bit_access

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

        $this->load->model('tindakan/tindakan_vaksin_m');
        $this->load->model('tindakan/tindakan_vaksin_item_m');
        $this->load->model('tindakan/tindakan_vaksin_item_identitas_m');
        $this->load->model('tindakan/notifikasi_vaksin_m');
        $this->load->model('master/master_vaksin_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('apotik/transfer_item/inventory_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_detail_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/tindakan/proses_tindakan_vaksin/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tindakan Vaksin', $this->session->userdata('language')), 
            'header'         => translate('Tindakan Vaksin', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'tindakan/proses_tindakan_vaksin/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    

    public function history()
    {
        $assets = array();
        $config = 'assets/tindakan/proses_tindakan_vaksin/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Tindakan Vaksin', $this->session->userdata('language')), 
            'header'         => translate('History Tindakan Vaksin', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'tindakan/proses_tindakan_vaksin/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses($id)
    {
        $assets = array();
        $assets_config = 'assets/tindakan/proses_tindakan_vaksin/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);


        $form_data = $this->tindakan_vaksin_m->get_by(array('id' => $id), true);

        $form_data_item = $this->tindakan_vaksin_item_m->get_data_item($id)->result_array();

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate("Proses Tindakan Vaksin", $this->session->userdata("language")), 
            'header'         => translate("Proses Tindakan Vaksin", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'form_data'        => object_to_array($form_data),
            'form_data_item'   => object_to_array($form_data_item),
            'content_view'   => 'tindakan/proses_tindakan_vaksin/proses',
            'pk_value'       => $id
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/tindakan/proses_tindakan_vaksin/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data            = $this->surat_traveling_m->get($id);
        $data_pasien          = $this->pasien_m->get_by(array('id' => $form_data->pasien_id));
        $data_poliklinik      = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_tujuan_id));
        $data_poliklinik_asal = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_asal_id));
        // die(dump($this->db->last_query()));
        // die(dump($data_poliklinik));

        $data = array(
            'title'                => config_item('site_name').' | '. translate("View Rujukan", $this->session->userdata("language")), 
            'header'               => translate("View Rujukan", $this->session->userdata("language")), 
            'header_info'          => config_item('site_name'), 
            'breadcrumb'           => TRUE,
            'menus'                => $this->menus,
            'menu_tree'            => $this->menu_tree,
            'css_files'            => $assets['css'],
            'js_files'             => $assets['js'],
            'content_view'         => 'tindakan/proses_tindakan_vaksin/view',
            'form_data'            => object_to_array($form_data),
            'data_pasien'          => object_to_array($data_pasien),
            'data_poliklinik'      => object_to_array($data_poliklinik),
            'data_poliklinik_asal' => object_to_array($data_poliklinik_asal),
            'pk_value'             => $id,
            'flag'                 => 'view'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

  

    public function listing_pilih_pasien()
    {
        
        $result = $this->pasien_m->get_datatable_pilih_pasien_penjualan_obat();
        // die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            
            $action = '';
            $row['alamat'] = $row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['propinsi'];

            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select-pasien"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
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

        $i=0;
        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $id = $row['id'];
            $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $row['id']));
            $data_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'],'is_primary' => 1), true);

            $harga_item = $this->item_harga_m->get_harga_item_satuan($row['id'],$data_satuan_primary->id)->row(0);
            // die(dump($this->db->last_query()));
           
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

    public function listing($status)
    {
        $result = $this->tindakan_vaksin_m->get_datatable($status);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {
            $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'tindakan/proses_tindakan_vaksin/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-cogs"></i></a>';

            $output['data'][] = array(
                $i,   
                $row['nama_cabang'],                
                $row['nama_pasien'],                
                $row['nama_vaksin'],                
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['nama_dokter'],                
                $row['nama_perawat'],  
                $action              
            );
         $i++;
        }

        echo json_encode($output);
    }
    
    public function add_identitas($row_id,$item_id,$cabang_id)
    {
        $data_item = $this->item_m->get($item_id);
        $data_item_identitas = str_split($data_item->identitas_byte);

        $data_inventory = $this->inventory_m->get_data_inventory($item_id,$cabang_id)->result_array();

        $data_inventory = (count($data_inventory) != 0)?object_to_array($data_inventory):'';
        $index = explode('_', $row_id);

        $body = array(
            'row_id'              => $row_id,
            'item_id'             => $item_id,
            'data_item'           => object_to_array($data_item),
            'data_inventory'      => $data_inventory,
            'data_item_identitas' => $data_item_identitas,
            'index'               => $index[2]
        );

        $this->load->view('apotik/resep_obat/modal_identitas', $body);
    }

   

    public function save()
    {
        $array_input = $this->input->post();
        $cabang_login = $this->cabang_m->get($this->session->userdata('cabang_id'));

        if ($array_input['command'] == "proses") {
            
            $tindakan_vaksin_id = $array_input['tindakan_vaksin_id'];
            $pasien_id = $array_input['pasien_id'];
            $cabang_id = $array_input['cabang_id'];

            $data_cabang = $this->cabang_m->get_by(array('id' => $cabang_id), true);
            $data_cabang = object_to_array($data_cabang);

            $data_history = array(
                'transaksi_id' => $tindakan_vaksin_id,
                'transaksi_tipe' => 17
            );

            $inventory_history_id = $this->inventory_history_m->save($data_history);

            $data_vaksin = array(
                'status'        => 2,
                'modified_by'   => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            $tindakan_vaksin = $this->tindakan_vaksin_m->edit_data($data_vaksin, $tindakan_vaksin_id);

            $path_model = 'tindakan/tindakan_vaksin_m';
            $tindakan_vaksin = insert_data_api_id($data_vaksin,$data_cabang['url'],$path_model);

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
            
            $last_number_invoice  = $this->invoice_m->get_nomor_invoice()->result_array();
            if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
            {
                $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
            }
            else
            {
                $last_number_invoice = intval(1);
            }

            $format_invoice = date('Ymd').' - '.'%06d';
            $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);


            $data_pembayaran_detail = array(
                'no_invoice'           => $no_invoice,
                'cabang_id'            => $this->session->userdata('cabang_id'),
                'tipe_pasien'          => $array_input['tipe_pasien'],
                'pasien_id'            => $array_input['pasien_id'],
                'nama_pasien'          => $array_input['pasien_nama'],
                'penjamin_id'          => 1,
                'nama_penjamin'        => $array_input['pasien_nama'],
                'status'               => 1,
                'jenis_invoice'        => 1,
                'harga'                => $biaya_total,
                'diskon'               => 0,
                'harga_setelah_diskon' => $biaya_total,
                'sisa_bayar'           => $biaya_total,
                'user_level_id'        => $this->session->userdata('level_id'),
                'is_active'            => 1
            );
            
            $invoice_id = $this->invoice_m->save($data_pembayaran_detail);

            foreach ($array_input['item'] as $item) {
                if($item['item_id'] != '')
                {
                    $data_detail_item = array(
                       'invoice_id'     => $invoice_id,
                       'item_id'        => $item['item_id'],
                       'satuan_id'      => $item['item_satuan_id'],
                       'qty'            => $item['jumlah_pesan'],
                       'harga'          => $item['harga_jual'],
                       'tipe'           => 2,
                       'tipe_item'      => 2,
                       'status'         => 1,
                       'is_active'      => 1
                    );

                    $invoice_detail = $this->invoice_detail_m->save($data_detail_item);
                    
                }
            }
            
            $last_id       = $this->penjualan_obat_m->get_max_id_penjualan()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'PJO-'.date('m').'-'.date('Y').'-%04d';
            $id_jual         = sprintf($format_id, $last_id, 4);
            
            
            $last_number   = $this->penjualan_obat_m->get_no_penjualan()->result_array();
            $last_number   = intval($last_number[0]['max_no_penjualan'])+1;
            
            
            $format        = '#PJO#%03d/RHS/'.romanic_number(date('m'), true).'/'.date('Y');
            $no_jual     = sprintf($format, $last_number, 3);

            $pasien_id = ($array_input['pasien_id'] != '')?$array_input['pasien_id']:0;
            $data_penjualan = array(
                'id'           => $id_jual,
                'pasien_id'    => $array_input['pasien_id'],
                'nama_pasien'  => $array_input['pasien_nama'],
                'tipe_pasien'  => $array_input['tipe_pasien'],
                'alamat_pasien' => $array_input['alamat_pasien'],
                'no_penjualan' => $no_jual,
                'invoice_id'   => $invoice_id,
                'no_invoice'   => $no_invoice,
                'tanggal'      => date('Y-m-d'),
                'status'       => 1,
                'grand_total'  => $biaya_total,
                'is_active'    => 1,
                'created_by'   => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s'),
            );

            $penjualan_obat = $this->penjualan_obat_m->add_data($data_penjualan);


            foreach ($array_input['items'] as $key => $item) 
            {
                if($item['jumlah_kirim'] != "" && $item['jumlah_kirim'] != "0")
                {
                    if(isset($array_input['identitas_'.$item['item_id'].'_'.$key])){

                        foreach ($array_input['identitas_'.$item['item_id'].'_'.$key] as $keys => $identitas) {
                                // die(dump($identitas));
                            if($identitas['jumlah_identitas'] != 0){

                                $parameter = array(
                                    'item_id'        => $item['item_id'], 
                                    'item_satuan_id' => $identitas['item_satuan'], 
                                    'bn_sn_lot'      => $identitas['bn_sn_lot'], 
                                    'expire_date'    => date('Y-m-d', strtotime($identitas['expire_date'])), 
                                    'gudang_id'      => $identitas['gudang_id']
                                );

                                $data_inventory = $this->inventory_m->get_by($parameter);
                                $data_inventory = object_to_array($data_inventory);

                                $x = 1;
                                $sisa = 0;
                                foreach ($data_inventory as $row_inv) {

                                    $last_id_vaksin_item = $this->tindakan_vaksin_item_m->get_max_id_item()->result_array();
                                    $last_id_vaksin_item = intval($last_id_vaksin_item[0]['max_id'])+1;

                                    $format_id_vaksin_item   = 'TVII-'.date('m').'-'.date('Y').'-%04d';
                                    $id_vaksin_item       = sprintf($format_id_vaksin_item, $last_id_vaksin_item, 4);
                                    
                                    if($x == 1 && $identitas['jumlah_identitas'] >= $row_inv['jumlah']){


                                        $data_identitas = array(
                                            'id'                      => $id_vaksin_item,
                                            'tindakan_vaksin_item_id' => $item['tindakan_resep_obat_detail_id'],
                                            'item_id'                 => $item['item_id'],
                                            'item_satuan_id'          => $identitas['item_satuan'],
                                            'qty'                     => $row_inv['jumlah'],
                                            'bn_sn_lot'               => $identitas['bn_sn_lot'],
                                            'expire_date'             => date('Y-m-d', strtotime($identitas['expire_date'])),
                                            'is_active'               => 1,
                                            'created_by'              => $this->session->userdata('user_id'),
                                            'created_date'            => date('Y-m-d H:i:s')
                                        );

                                        $save_identitas = $this->tindakan_vaksin_item_identitas_m->save($data_identitas);

                                        $path_model = 'tindakan/tindakan_vaksin_item_identitas_m';
                                        $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                        $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                        
                                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                        $data_penjualan_detail = array(
                                            'id'                => $id_jual_detail,
                                            'penjualan_obat_id' => $id_jual,
                                            'item_id'           => $row_inv['item_id'],
                                            'item_satuan_id'    => $row_inv['item_satuan_id'],
                                            'tipe_obat'         => 2,
                                            'jumlah'            => $row_inv['jumlah'],
                                            'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                            'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_jual'        => $item['harga'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                        $sisa = $identitas['jumlah_identitas'] - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
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
                                    if($x == 1 && $identitas['jumlah_identitas'] < $row_inv['jumlah']){

                                        $data_identitas = array(
                                            'id'                      => $id_vaksin_item,
                                            'tindakan_vaksin_item_id' => $item['tindakan_resep_obat_detail_id'],
                                            'item_id'                 => $item['item_id'],
                                            'item_satuan_id'          => $identitas['item_satuan'],
                                            'qty'                     => $identitas['jumlah_identitas'],
                                            'bn_sn_lot'               => $identitas['bn_sn_lot'],
                                            'expire_date'             => date('Y-m-d', strtotime($identitas['expire_date'])),
                                            'is_active'               => 1,
                                            'created_by'              => $this->session->userdata('user_id'),
                                            'created_date'            => date('Y-m-d H:i:s')
                                        );

                                        $save_identitas = $this->tindakan_vaksin_item_identitas_m->save($data_identitas);

                                        $path_model = 'tindakan/tindakan_vaksin_item_identitas_m';
                                        $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                        $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                        
                                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                        $data_penjualan_detail = array(
                                            'id'                => $id_jual_detail,
                                            'penjualan_obat_id' => $id_jual,
                                            'item_id'           => $row_inv['item_id'],
                                            'item_satuan_id'    => $row_inv['item_satuan_id'],
                                            'tipe_obat'         => 2,
                                            'jumlah'            => $identitas['jumlah_identitas'],
                                            'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                            'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_jual'        => $item['harga'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                        $sisa = 0;
                                        $sisa_inv = $row_inv['jumlah'] - $identitas['jumlah_identitas'];

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
                                            'gudang_id'            => $row_inv['gudang_id'],
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

                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                                       $data_identitas = array(
                                            'id'                      => $id_vaksin_item,
                                            'tindakan_vaksin_item_id' => $item['tindakan_resep_obat_detail_id'],
                                            'item_id'                 => $item['item_id'],
                                            'item_satuan_id'          => $identitas['item_satuan'],
                                            'qty'                     => $row_inv['jumlah'],
                                            'bn_sn_lot'               => $identitas['bn_sn_lot'],
                                            'expire_date'             => date('Y-m-d', strtotime($identitas['expire_date'])),
                                            'is_active'               => 1,
                                            'created_by'              => $this->session->userdata('user_id'),
                                            'created_date'            => date('Y-m-d H:i:s')
                                        );

                                        $save_identitas = $this->tindakan_vaksin_item_identitas_m->save($data_identitas);

                                        $path_model = 'tindakan/tindakan_vaksin_item_identitas_m';
                                        $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                        $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                        
                                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                        $data_penjualan_detail = array(
                                            'id'                => $id_jual_detail,
                                            'penjualan_obat_id' => $id_jual,
                                            'item_id'           => $row_inv['item_id'],
                                            'item_satuan_id'    => $row_inv['item_satuan_id'],
                                            'tipe_obat'         => 2,
                                            'jumlah'            => $row_inv['jumlah'],
                                            'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                            'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_jual'        => $item['harga'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                        $sisa = $sisa - $row_inv['jumlah'];
                                        $sisa_inv = 0;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
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

                                        $data_identitas = array(
                                            'id'                      => $id_vaksin_item,
                                            'tindakan_vaksin_item_id' => $item['tindakan_resep_obat_detail_id'],
                                            'item_id'                 => $item['item_id'],
                                            'item_satuan_id'          => $identitas['item_satuan'],
                                            'qty'                     => $sisa,
                                            'bn_sn_lot'               => $identitas['bn_sn_lot'],
                                            'expire_date'             => date('Y-m-d', strtotime($identitas['expire_date'])),
                                            'is_active'               => 1,
                                            'created_by'              => $this->session->userdata('user_id'),
                                            'created_date'            => date('Y-m-d H:i:s')
                                        );

                                        $save_identitas = $this->tindakan_vaksin_item_identitas_m->save($data_identitas);

                                        $path_model = 'tindakan/tindakan_vaksin_item_identitas_m';
                                        $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                        $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                        
                                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                        $data_penjualan_detail = array(
                                            'id'                => $id_jual_detail,
                                            'penjualan_obat_id' => $id_jual,
                                            'item_id'           => $row_inv['item_id'],
                                            'item_satuan_id'    => $row_inv['item_satuan_id'],
                                            'tipe_obat'         => 2,
                                            'jumlah'            => $sisa,
                                            'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                            'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                            'harga_jual'        => $item['harga'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                        $sisa_inv = $row_inv['jumlah'] - $sisa;

                                        $data_inventory_history_detail = array(
                                            'inventory_history_id' => $inventory_history_id,
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
                                        $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                                    }

                                    $x++;        
                                }
                            }
                            
                        }    
                    }else{

                        $x = 1;
                        $sisa = 0;
                        foreach ($data_inventory as $row_inv) {
                            
                            if($x == 1 && $item['jumlah_kirim'] >= $row_inv['jumlah']){

                                
                                $data_identitas = array(
                                    'id'                      => $id_vaksin_item,
                                    'tindakan_vaksin_item_id' => $item['tindakan_resep_obat_detail_id'],
                                    'item_id'                 => $item['item_id'],
                                    'item_satuan_id'          => $identitas['item_satuan'],
                                    'qty'                     => $row_inv['jumlah'],
                                    'bn_sn_lot'               => $identitas['bn_sn_lot'],
                                    'expire_date'             => date('Y-m-d', strtotime($identitas['expire_date'])),
                                    'is_active'               => 1,
                                    'created_by'              => $this->session->userdata('user_id'),
                                    'created_date'            => date('Y-m-d H:i:s')
                                );

                                $save_identitas = $this->tindakan_vaksin_item_identitas_m->save($data_identitas);

                                $path_model = 'tindakan/tindakan_vaksin_item_identitas_m';
                                $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                
                                $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                $data_penjualan_detail = array(
                                    'id'                => $id_jual_detail,
                                    'penjualan_obat_id' => $id_jual,
                                    'item_id'           => $row_inv['item_id'],
                                    'item_satuan_id'    => $row_inv['item_satuan_id'],
                                    'tipe_obat'         => 2,
                                    'jumlah'            => $row_inv['jumlah'],
                                    'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                    'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                    'harga_jual'        => $item['harga'],
                                    'created_by'        => $this->session->userdata('user_id'),
                                    'created_date'      => date('Y-m-d H:i:s'),
                                );

                                $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                $sisa = $item['jumlah_kirim'] - $row_inv['jumlah'];
                                $sisa_inv = 0;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
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
                            if($x == 1 && $item['jumlah_kirim'] < $row_inv['jumlah']){

                                $data_identitas = array(
                                    'id'                      => $id_vaksin_item,
                                    'tindakan_vaksin_item_id' => $item['tindakan_resep_obat_detail_id'],
                                    'item_id'                 => $item['item_id'],
                                    'item_satuan_id'          => $identitas['item_satuan'],
                                    'qty'                     => $item['jumlah_kirim'],
                                    'bn_sn_lot'               => $identitas['bn_sn_lot'],
                                    'expire_date'             => date('Y-m-d', strtotime($identitas['expire_date'])),
                                    'is_active'               => 1,
                                    'created_by'              => $this->session->userdata('user_id'),
                                    'created_date'            => date('Y-m-d H:i:s')
                                );

                                $save_identitas = $this->tindakan_vaksin_item_identitas_m->save($data_identitas);

                                $path_model = 'tindakan/tindakan_vaksin_item_identitas_m';
                                $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                
                                $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                $data_penjualan_detail = array(
                                    'id'                => $id_jual_detail,
                                    'penjualan_obat_id' => $id_jual,
                                    'item_id'           => $row_inv['item_id'],
                                    'item_satuan_id'    => $row_inv['item_satuan_id'],
                                    'tipe_obat'         => 2,
                                    'jumlah'            => $item['jumlah_kirim'],
                                    'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                    'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                    'harga_jual'        => $item['harga'],
                                    'created_by'        => $this->session->userdata('user_id'),
                                    'created_date'      => date('Y-m-d H:i:s'),
                                );

                                $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);


                                $sisa = 0;
                                $sisa_inv = $row_inv['jumlah'] - $item['jumlah_kirim'];

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
                                    'gudang_id'            => $row_inv['gudang_id'],
                                    'pmb_id'               => $row_inv['pmb_id'],
                                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                    'box_paket_id'         => NULL,
                                    'kode_box_paket'       => NULL,
                                    'item_id'              => $row_inv['item_id'],
                                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                                    'initial_stock'        => $row_inv['jumlah'],
                                    'change_stock'         => ($item['jumlah_kirim'] * (-1)),
                                    'harga_beli'           => $row_inv['harga_beli'],
                                    'total_harga'          => $item['jumlah_kirim'] * $row_inv['harga_beli'],
                                    'final_stock'          => $sisa_inv,
                                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                    'expire_date'          => $row_inv['expire_date'],
                                    'created_by'           => $this->session->userdata('user_id'),
                                    'created_date'         => date('Y-m-d H:i:s')
                                );

                                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                            }

                            if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                                $data_identitas = array(
                                    'id'                      => $id_vaksin_item,
                                    'tindakan_vaksin_item_id' => $item['tindakan_resep_obat_detail_id'],
                                    'item_id'                 => $item['item_id'],
                                    'item_satuan_id'          => $identitas['item_satuan'],
                                    'qty'                     => $row_inv['jumlah'],
                                    'bn_sn_lot'               => $identitas['bn_sn_lot'],
                                    'expire_date'             => date('Y-m-d', strtotime($identitas['expire_date'])),
                                    'is_active'               => 1,
                                    'created_by'              => $this->session->userdata('user_id'),
                                    'created_date'            => date('Y-m-d H:i:s')
                                );

                                $save_identitas = $this->tindakan_vaksin_item_identitas_m->save($data_identitas);

                                $path_model = 'tindakan/tindakan_vaksin_item_identitas_m';
                                $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                
                                $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                $data_penjualan_detail = array(
                                    'id'                => $id_jual_detail,
                                    'penjualan_obat_id' => $id_jual,
                                    'item_id'           => $row_inv['item_id'],
                                    'item_satuan_id'    => $row_inv['item_satuan_id'],
                                    'tipe_obat'         => 2,
                                    'jumlah'            => $row_inv['jumlah'],
                                    'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                    'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                    'harga_jual'        => $item['harga'],
                                    'created_by'        => $this->session->userdata('user_id'),
                                    'created_date'      => date('Y-m-d H:i:s'),
                                );

                                $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                $sisa = $sisa - $row_inv['jumlah'];
                                $sisa_inv = 0;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
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

                                $data_identitas = array(
                                    'id'                      => $id_vaksin_item,
                                    'tindakan_vaksin_item_id' => $item['tindakan_resep_obat_detail_id'],
                                    'item_id'                 => $item['item_id'],
                                    'item_satuan_id'          => $identitas['item_satuan'],
                                    'qty'                     => $sisa,
                                    'bn_sn_lot'               => $identitas['bn_sn_lot'],
                                    'expire_date'             => date('Y-m-d', strtotime($identitas['expire_date'])),
                                    'is_active'               => 1,
                                    'created_by'              => $this->session->userdata('user_id'),
                                    'created_date'            => date('Y-m-d H:i:s')
                                );

                                $save_identitas = $this->tindakan_vaksin_item_identitas_m->save($data_identitas);

                                $path_model = 'tindakan/tindakan_vaksin_item_identitas_m';
                                $identitas_resep_id = insert_data_api($data_identitas,$data_cabang['url'],$path_model);
                                $identitas_resep_id = str_replace('"', '', $identitas_resep_id);

                                $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                                $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                                
                                $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                                $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                                $data_penjualan_detail = array(
                                    'id'                => $id_jual_detail,
                                    'penjualan_obat_id' => $id_jual,
                                    'item_id'           => $row_inv['item_id'],
                                    'item_satuan_id'    => $row_inv['item_satuan_id'],
                                    'tipe_obat'         => 2,
                                    'jumlah'            => $sisa,
                                    'bn_sn_lot'         => $row_inv['bn_sn_lot'],
                                    'expire_date'       => date('Y-m-d', strtotime($row_inv['expire_date'])),
                                    'harga_jual'        => $item['harga'],
                                    'created_by'        => $this->session->userdata('user_id'),
                                    'created_date'      => date('Y-m-d H:i:s'),
                                );

                                $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

                                $sisa_inv = $row_inv['jumlah'] - $sisa;

                                $data_inventory_history_detail = array(
                                    'inventory_history_id' => $inventory_history_id,
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
                                $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                            }

                            $x++;        
                        }
                    }
                }         
            }
        }

        redirect('tindakan/tindakan_vaksin');
        
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

}

/* End of file surat_traveling.php */
/* Location: ./application/controllers/tindakan/tindakan_vaksin.php */