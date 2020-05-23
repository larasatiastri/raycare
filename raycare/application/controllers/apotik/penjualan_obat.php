<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Penjualan_obat extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '67ee3f6d82f70c6a026efab8ed45a6f8';                  // untuk check bit_access

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

        $this->load->model('apotik/penjualan_obat/tindakan_resep_obat_m');
        $this->load->model('apotik/penjualan_obat/racik_obat_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_detail_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_identitas_m');
        $this->load->model('apotik/penjualan_obat/penjualan_obat_identitas_detail_m');
        $this->load->model('apotik/penjualan_obat/inventory_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_detail_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_identitas_m');
        $this->load->model('apotik/penjualan_obat/inventory_history_identitas_detail_m');
        $this->load->model('apotik/penjualan_obat/inventory_identitas_m');
        $this->load->model('apotik/penjualan_obat/inventory_identitas_detail_m');

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
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('reservasi/invoice/draf_invoice_m');   
        $this->load->model('reservasi/invoice/draf_invoice_detail_m'); 

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/penjualan_obat/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Penjualan Obat', $this->session->userdata('language')), 
            'header'         => translate('Penjualan Obat', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/penjualan_obat/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/apotik/penjualan_obat/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Penjualan Obat', $this->session->userdata('language')), 
            'header'         => translate('History Penjualan Obat', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/penjualan_obat/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
    
    public function view($id)
    {
        $assets = array();
        $config = 'assets/apotik/penjualan_obat/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->penjualan_obat_m->get_by(array('id' => $id), true);
        $form_data_invoice = $this->invoice_m->get_by(array('id' => $form_data->invoice_id), true);
        $form_data_detail = $this->penjualan_obat_detail_m->get_data($id)->result_array();

        // die_dump($form_data);

        $data = array(
            'title'            => 'RayCare | '.translate('View Penjualan Obat', $this->session->userdata('language')), 
            'header'           => translate('View Penjualan Obat', $this->session->userdata('language')), 
            'header_info'      => 'RayCare', 
            'breadcrumb'       => true,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'form_data'        => object_to_array($form_data),
            'form_data_invoice'        => object_to_array($form_data_invoice),
            'form_data_detail' => object_to_array($form_data_detail),
            'content_view'     => 'apotik/penjualan_obat/view',
            'pk_value'         => $id
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
        //die(dump($records));

        $i=0;
        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $id = $row['id'];
            $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $row['id']));
            $data_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'],'is_primary' => 1), true);

            // $harga_item = $this->item_harga_m->get_harga_item_satuan($row['id'],$data_satuan_primary->id)->row(0);
            $harga_item = $this->item_satuan_m->get_by(array('id' => $data_satuan_primary->id), true);

            $data_gambar_primary = $this->item_gambar_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1, 'is_active' => 1), true);
            $data_gambar_primary = object_to_array($data_gambar_primary);

            $gambar = base_url().'assets/mb/pages/master/item/images/item_global.jpg';
            if (file_exists(FCPATH.'assets/mb/pages/master/item/images/'.$row['id'].'/medium/'.$data_gambar_primary['gambar_url']) && is_file(FCPATH.'assets/mb/pages/master/item/images/'.$row['id'].'/medium/'.$data_gambar_primary['gambar_url'])) {
                $gambar = base_url().'assets/mb/pages/master/item/images/'.$id.'/medium/'.$data_gambar_primary['gambar_url'];

            }
           
            $row['tipe_item'] = '2';

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($data_satuan)).'" data-satuan_primary="'.htmlentities(json_encode($data_satuan_primary)).'" data-harga="'.htmlentities(json_encode($harga_item)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                '<div class="text-center"><a class="fancybox-button" title="160401123556_bon_46.jpg" href="'.$gambar.'" data-rel="fancybox-button"><img src="'.$gambar.'" alt="Smiley face" class="img-responsive" style="width:103px; height:77px;"></a></div>',
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
        $data_item = $this->item_m->get_by(array('id' => $item_id), true);
        $data_item_satuan = $this->item_satuan_m->get_by(array('id' => $item_satuan_id), true);
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
            
            // $last_number_invoice  = $this->invoice_m->get_nomor_invoice()->result_array();
            // if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
            // {
            //     $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
            // }
            // else
            // {
            //     $last_number_invoice = intval(1);
            // }

            // $format_invoice = date('Ymd').' - '.'%06d';
            // $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);


            // $data_pembayaran_detail = array(
            //     'no_invoice'           => $no_invoice,
            //     'cabang_id'            => $this->session->userdata('cabang_id'),
            //     'tipe_pasien'          => $array_input['tipe_pasien'],
            //     'pasien_id'            => $array_input['id_ref_pasien'],
            //     'nama_pasien'          => $array_input['nama_ref_pasien'],
            //     'penjamin_id'          => 1,
            //     'nama_penjamin'        => $array_input['nama_ref_pasien'],
            //     'status'               => 1,
            //     'jenis_invoice'        => 1,
            //     'harga'                => $array_input['grand_total'],
            //     'diskon'               => 0,
            //     'harga_setelah_diskon' => $array_input['grand_total'],
            //     'sisa_bayar'           => $array_input['grand_total'],
            //     'user_level_id'        => $this->session->userdata('level_id'),
            //     'is_active'            => 1
            // );
            
            // $invoice_id = $this->invoice_m->save($data_pembayaran_detail);

            $draf_invoice_swasta = $this->draf_invoice_m->get_by(array('pasien_id' => $array_input['id_ref_pasien'], 'jenis_invoice' => 1), true);
            if(count($draf_invoice_swasta) == 0){
                $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                
                $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                $data_draft_tindakan = array(
                    'id'    => $id_draft,
                    'pasien_id'    => $array_input['id_ref_pasien'],
                    'tipe'  => 1,
                    'cabang_id'  => $this->session->userdata('cabang_id'),
                    'tipe_pasien'  => 1,
                    'nama_pasien'  => $array_input['nama_ref_pasien'],
                    'user_level_id'  => $this->session->userdata('level_id'),
                    'jenis_invoice' => 1,
                    'status'    => 1,
                    'is_active'    => 1,
                    'akomodasi'    => 0,
                    'diskon_persen'    => ($array_input['diskon_persen'] != '')?$array_input['diskon_persen']:0,
                    'diskon_nominal'    => ($array_input['diskon'] != '')?$array_input['diskon']:0,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')
                );

                $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);
            }elseif(count($draf_invoice_swasta) != 0){
                $id_draft = $draf_invoice_swasta->id;
            }



            foreach ($array_input['item'] as $item) {
                if($item['item_id'] != '')
                {
                    // $data_detail_item = array(
                    //    'invoice_id'     => $invoice_id,
                    //    'item_id'        => $item['item_id'],
                    //    'satuan_id' => $item['satuan_id'],
                    //    'qty'            => $item['qty'],
                    //    'harga'          => $item['harga'],
                    //    'tipe'           => 2,
                    //    'tipe_item'      => 2,
                    //    'status'         => 1,
                    //    'is_active'      => 1
                    // );

                    // $invoice_detail = $this->invoice_detail_m->save($data_detail_item);

                    $data_item = $this->item_m->get_by(array('id' => $item['item_id']), true);

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;

                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $id_draft,
                        'tipe_item' => 3,
                        'item_id'   => $item['item_id'],
                        'jumlah'                 => $item['qty'],
                        'nama_tindakan' => $data_item->nama,
                        'harga_jual'             => $item['harga'],
                        'status' => 1,
                        'is_active'    => 1,
                        'diskon_persen'    => $item['diskon_persen'],
                        'diskon_nominal'    => $item['diskon'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                    
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

            $pasien_id = ($array_input['id_ref_pasien'] != '')?$array_input['id_ref_pasien']:0;
            $data_penjualan = array(
                'id'           => $id_jual,
                'pasien_id'    => $array_input['id_ref_pasien'],
                'nama_pasien'  => $array_input['nama_ref_pasien'],
                'tipe_jual'    => 0,
                'tipe_pasien'  => $array_input['tipe_pasien'],
                'alamat_pasien' => $array_input['alamat_pasien'],
                'no_penjualan' => $no_jual,
                'invoice_id'   => $invoice_id,
                'no_invoice'   => $no_invoice,
                'tanggal'      => date('Y-m-d'),
                'total'       => $array_input['total'],
                'diskon'       => $array_input['diskon'],
                'status'       => 1,
                'grand_total'  => $array_input['grand_total'],
                'is_active'    => 1,
                'created_by'   => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s'),
            );

            $penjualan_obat = $this->penjualan_obat_m->add_data($data_penjualan);

            $data_inventory_history = array(
                'transaksi_id'   => $id_jual,
                'transaksi_tipe' => '3'
            );

            //save inventory histoy
            $save_inventory_history = $this->inventory_history_m->save($data_inventory_history);

            foreach ($items as $key => $item) {
                if($item['item_id'] != '' && $item['qty'] != 0){
                    

                    if(isset($array_input['identitas_'.$item['item_id']])){
                        foreach ($array_input['identitas_'.$item['item_id']] as $key_identitas => $identitas) {
                            
                            if($identitas['jumlah_identitas'] != 0){

                                $data_inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['satuan_id'], 'bn_sn_lot' => $identitas['bn_sn_lot'], 'expire_date' => date('Y-m-d', strtotime($identitas['expire_date'])), 'gudang_id' => $array_input['gudang_id']));
                    
                                $data_inventory = object_to_array($data_inventory);

                                $x = 1;
                                $sisa = 0;
                                foreach ($data_inventory as $row_inv) {
                                    if($x == 1 && $identitas['jumlah_identitas'] >= $row_inv['jumlah']){

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
                                            'diskon_persen'    => $item['diskon_persen'],
                                            'diskon_nominal'    => $item['diskon'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

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
                                            'diskon_persen'    => $item['diskon_persen'],
                                            'diskon_nominal'    => $item['diskon'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

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
                                            'diskon_persen'    => $item['diskon_persen'],
                                            'diskon_nominal'    => $item['diskon'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

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
                                            'diskon_persen'    => $item['diskon_persen'],
                                            'diskon_nominal'    => $item['diskon'],
                                            'created_by'        => $this->session->userdata('user_id'),
                                            'created_date'      => date('Y-m-d H:i:s'),
                                        );

                                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

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

                        $last_id_detail       = $this->penjualan_obat_detail_m->get_max_id_penjualan_detail()->result_array();
                        $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                        
                        $format_id_detail     = 'PJOD-'.date('m').'-'.date('Y').'-%04d';
                        $id_jual_detail         = sprintf($format_id_detail, $last_id_detail, 4);

                        $data_penjualan_detail = array(
                            'id'                => $id_jual_detail,
                            'penjualan_obat_id' => $id_jual,
                            'item_id'           => $item['item_id'],
                            'item_satuan_id'    => $item['satuan_id'],
                            'tipe_obat'         => 2,
                            'jumlah'            => $item['qty'],
                            'harga_jual'        => $item['harga'],
                            'diskon_persen'    => $item['diskon_persen'],
                                            'diskon_nominal'    => $item['diskon'],
                            'created_by'        => $this->session->userdata('user_id'),
                            'created_date'      => date('Y-m-d H:i:s'),
                        );

                        $penjualan_obat_detail = $this->penjualan_obat_detail_m->add_data($data_penjualan_detail);

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

        }
        redirect(base_url().'apotik/penjualan_obat');
    }

    public function listing()
    {
        
        $result = $this->penjualan_obat_m->get_datatable();

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
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/penjualan_obat/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';
            $action .='<a title="'.translate('Print Invoice', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'apotik/penjualan_obat/print_invoice/'.$row['invoice_id'].'/'.$row['pasien_id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
               
            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>' ,
                '<div class="text-center">'.$row['no_penjualan'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$row['alamat_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($row['grand_total']).'</div>' ,
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function modal_detail($id, $item_id, $item_satuan_id)
    {   
        $data_item = $this->item_m->get_by(array('id' => $item_id), true);
        $data_item_satuan = $this->item_satuan_m->get_by(array('id' => $item_satuan_id), true);
        $data_item_identitas = str_split($data_item->identitas_byte);

        $penjualan_detail = $this->penjualan_obat_detail_m->get_data_detail($id, $item_id, $item_satuan_id)->result_array();
        
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

    // public function print_invoice($id, $pasien_id)
    // {
    //     $this->load->library('mpdf/mpdf.php');

    //     $data_invoice = $this->invoice_m->get($id);

    //     $data_invoice_paket = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'tipe' => 1, 'tipe_item' => 1, 'is_active' => 1));
    //     $data_invoice_item = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'tipe' => 2, 'is_active' => 1));
    //     $data_invoice_items = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'tipe' => 2, 'item_id !=' => 8, 'is_active' => 1));
    //     $data_invoice_alat = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'tipe' => 3, 'is_active' => 1));
    //     $data_pasien = $this->pasien_m->get($pasien_id);
    
    //     // die(dump($data_invoice_item));
    //     $data = array(
    //         'invoice'       => object_to_array($data_invoice),
    //         'invoice_paket' => (count($data_invoice_paket) != 0)?object_to_array($data_invoice_paket):'',
    //         'invoice_item'  => (count($data_invoice_item) != 0)?object_to_array($data_invoice_item):'',
    //         'invoice_items' => (count($data_invoice_items) != 0)?object_to_array($data_invoice_items):'',
    //         'invoice_alat'  => (count($data_invoice_alat) != 0)?object_to_array($data_invoice_alat):'',
    //         'pasien'        => object_to_array($data_pasien),
    //         'penjamin_id'   => 1,
    //     );

    //     $mpdf = new mPDF('','A4', 0, 'dejavusans', 0, 0, 0, 0, 0, 0);
    //     $mpdf->writeHTML($this->load->view('apotik/penjualan_obat/print_invoice', $data, true));
        

    //     $mpdf->Output('Invoice_'.$data_invoice->no_invoice.'.pdf', 'I'); 
    // }
    

}

/* End of file pembayaran.php */
/* Location: ./application/controllers/reservasi/pembayaran.php */
