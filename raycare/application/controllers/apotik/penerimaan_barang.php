<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_barang extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'b759bb784f3bf30eae44c4c2b0dad61e';                  // untuk check bit_access

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


        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');

        $this->load->model('apotik/penerimaan_barang/gudang_m');

        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_identitas_m');
        $this->load->model('master/identitas_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/region_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/supplier/supplier_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_telp_m');
        
        // $this->load->model('apotik/penerimaan_barang/inventory_api_m');
        $this->load->model('apotik/penerimaan_barang/inventory_identitas_m');
        $this->load->model('apotik/penerimaan_barang/inventory_identitas_detail_m');

        $this->load->model('apotik/inventory_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');

        $this->load->model('apotik/penerimaan_barang/pengiriman_m');
        $this->load->model('apotik/penerimaan_barang/pengiriman_detail_m');
        $this->load->model('apotik/penerimaan_barang/pengiriman_identitas_detail_m');
        $this->load->model('apotik/penerimaan_barang/pengiriman_identitas_m');
        $this->load->model('apotik/penerimaan_barang/penjualan_m');
        $this->load->model('apotik/penerimaan_barang/pmb_m');
        $this->load->model('apotik/penerimaan_barang/pmb_detail_m');
        $this->load->model('apotik/penerimaan_barang/pmb_identitas_m');
        $this->load->model('apotik/penerimaan_barang/pmb_identitas_detail_m');
        $this->load->model('apotik/pembelian/pembelian_m');
        $this->load->model('apotik/pembelian/pembelian_detail_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/penerimaan_barang/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Daftar Penerimaan Barang', $this->session->userdata('language')), 
            'header'         => translate('Daftar Penerimaan Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/penerimaan_barang/index',
            'flag'           => 2,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/apotik/penerimaan_barang/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('History Daftar Penerimaan Barang', $this->session->userdata('language')), 
            'header'         => translate('History Daftar Penerimaan Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/penerimaan_barang/history',
            'flag'           => 2,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses($id)
    {
        $assets = array();
        $config = 'assets/apotik/penerimaan_barang/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);


        $form_data_pengiriman = $this->pengiriman_m->get_by(array('id' => $id), true);


        $cabang_ravena = $this->cabang_m->get(2);
        $form_data = get_data_penjualan($cabang_ravena->url,$form_data_pengiriman->penjualan_id);
// die(dump($form_data));
        $form_data_item = $this->pengiriman_detail_m->get_data_item($id)->result_array();
        // die_dump($form_data_item);
        $form_data_box  = $this->pengiriman_detail_m->get_data_box($id)->result_array();
        // die_dump($form_data_box);

        $data = array(
            'title'                => config_item('site_name').' &gt;'.translate('View Daftar Penerimaan Barang', $this->session->userdata('language')), 
            'header'               => translate('View Daftar Penerimaan Barang', $this->session->userdata('language')), 
            'header_info'          => config_item('site_name'), 
            'breadcrumb'           => true,
            'menus'                => $this->menus,
            'menu_tree'            => $this->menu_tree,
            'css_files'            => $assets['css'],
            'js_files'             => $assets['js'],
            'content_view'         => 'apotik/penerimaan_barang/proses',
            'flag'                 => 2,
            'form_data'            => object_to_array($form_data),
            'form_data_pengiriman' => object_to_array($form_data_pengiriman),
            'form_data_item'       => object_to_array($form_data_item),
            'form_data_box'        => object_to_array($form_data_box),
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id, $supplier_id, $gudang_id, $flag)
    {
       
        $assets = array();
        $config = 'assets/apotik/penerimaan_barang/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $data = array();
        
        $form_data = $this->pmb_m->get_by(array('id' => $id), true);      
        
        $data = array(
            'title'        => config_item('site_name').' | '.translate("View Barang Datang", $this->session->userdata("language")), 
            'header'       => translate("View Barang Datang", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'apotik/penerimaan_barang/view',
            'form_data'    => object_to_array($form_data),
            'supplier_id'  => $supplier_id,                   
            'gudang_id'    => $gudang_id,
            'pk_value'     => $id,                         //table primary key value
            'flag'         => $flag
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {

        $customer_id = $this->session->userdata('cabang_id');
        // die_dump($this->session->userdata('cabang_id'));

        $result = $this->pengiriman_m->get_datatable($customer_id);
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
        $count = count($records->result_array());
        // die_dump($count);

        $i=0;
       

        foreach($records->result_array() as $row)
        {
            $status = '';
            $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'apotik/penerimaan_barang/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-cogs"></i></a>';

            if($row['status'] == 1)
            {
                $status = '<div class="text-center"><span class="label label-md label-warning">Belum Diterima</span></div>';
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['no_surat_jalan'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function listing_history()
    {
        $result = $this->pmb_m->get_datatable();
        // die_dump($result);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $flag = 'pmb';
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/penerimaan_barang/view/'.$row['id'].'/'.$row['supplier_id'].'/'.$row['gudang_id'].'/'.$flag.'" name="view[]" class="btn grey-cascade pecah-item" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-search"></i></a>';
            

            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.$row['no_pmb'].'</div>' ,
                $row['no_surat_jalan'],
                '<div class="text-center">'.date('d/m/Y', strtotime($row['tanggal'])).'</div>' ,
                $row['supplier_nama'].'&nbsp;"'.$row['supplier_kode'].'"',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);

    }

    public function tolak($id)
    {
        $data = array(
            'id' => $id,
        );

        $this->load->view('apotik/penerimaan_barang/modal/modal_tolak', $data);

    }

    public function tolak_penerimaan($id)
    {

        $array_input = $this->input->post();
        $data_cabang = $this->cabang_m->get_by(array('tipe' => 2, 'is_active' => 1));
        $cabang      = object_to_array($data_cabang);
        $cabang_id   = $cabang[0]['url']; 
        // die_dump($data_cabang[0]['url']);
        // die_dump($array_input);

        $data_inventory_pengiriman = array();
        $data_inventory_pengiriman_identitas = array();
        $data_inventory_pengiriman_identitas_detail = array();
        
        $path_model = 'gudang/inventory_pengiriman_m';
        $wheres_pengiriman_id = array(
            'pengiriman_id' => $id,
        );

        $data_inventory_pengiriman = json_decode(get_data_api($cabang[0]['url'],$path_model,$wheres_pengiriman_id));
        $inventory_pengiriman = object_to_array($data_inventory_pengiriman);
        // die_dump($this->db->last_query());
        // die_dump($inventory_pengiriman);
                
        foreach ($inventory_pengiriman as $inventory) 
        {
            // $get_max_id = $this->
                        
            $data_inventory_pengiriman = array(

                'inventory_id'   => $inventory['inventory_pengiriman_id'],
                'gudang_id'      => $inventory['gudang_id'],
                'pmb_id'         => $inventory['pmb_id'],
                'box_paket_id'   => NULL,
                'kode_box_paket' => NULL,
                'item_id'        => $inventory['item_id'],
                'item_satuan_id' => $inventory['item_satuan_id'],
                'jumlah'         => $inventory['jumlah'],
                'tanggal_datang' => $inventory['tanggal_datang'],
                'harga_beli'     => NULL,
                'created_by'     => $inventory['created_by'],
                'created_date'   => $inventory['created_date'],
                'modified_by'    => NULL,
                'modified_date'  => NULL,

            );

            $path_model = 'gudang/inventory_m';
            $path_model_delete = 'gudang/inventory_pengiriman_m';
            $wheres_delete = array(

                'inventory_pengiriman_id' => $inventory['inventory_pengiriman_id'],

            );
            $inserted_inventory_id = $inventory['inventory_pengiriman_id'];
            // die_dump($inserted_inventory_id);

            foreach ($data_cabang as $row_cabang) 
            {
                if($row_cabang->is_active == 1)
                {
                    if($row_cabang->url != '' || $row_cabang->url != NULL)
                    {
                        $insert_inventory_id = insert_data_api_id($data_inventory_pengiriman,$row_cabang->url,$path_model, $inserted_inventory_id);                    
                        $delete_inventory_id = delete_data_api($row_cabang->url,$path_model_delete, $wheres_delete); 
                        // die_dump($delete_inventory_id);                   
                        
                    }
                }
            }
            $inserted_inventory_id = str_replace('"', '', $inserted_inventory_id);
            // die_dump($this->db->last_query());
            // die_dump($inserted_inventory_id);
        
            //**identitas
            $path_model = 'gudang/inventory_pengiriman_identitas_m';
            $wheres_inventory_pengiriman_id = array(
                'inventory_pengiriman_id' => $inventory['inventory_pengiriman_id'],
            );

            $data_inventory_pengiriman_identitas = json_decode(get_data_api($cabang[0]['url'],$path_model,$wheres_inventory_pengiriman_id));
            $inventory_pengiriman_identitas      = object_to_array($data_inventory_pengiriman_identitas);
            // die_dump($this->db->last_query());
            // die_dump($inventory_pengiriman_identitas);

            foreach ($inventory_pengiriman_identitas as $inventory_identitas) 
            {

                $data_inventory_pengiriman_identitas = array(

                    'inventory_identitas_id' => $inventory_identitas['inventory_pengiriman_identitas_id'],
                    'inventory_id'           => $inventory_identitas['inventory_pengiriman_id'],
                    'jumlah'                 => $inventory_identitas['jumlah'],
                    'created_by'             => $inventory_identitas['created_by'],
                    'created_date'           => $inventory_identitas['created_date'],
                );

                $path_model = 'gudang/inventory_identitas_m';
                $inserted_inventory_identitas_id = $inventory_identitas['inventory_pengiriman_identitas_id'];
                
                $path_model_delete = 'gudang/inventory_pengiriman_identitas_m';
                $wheres_delete = array(

                    'inventory_pengiriman_identitas_id' => $inventory_identitas['inventory_pengiriman_identitas_id'],

                );
                // die_dump($inserted_inventory_identitas_id);

                foreach ($data_cabang as $row_cabang) 
                {
                    if($row_cabang->is_active == 1)
                    {
                        if($row_cabang->url != '' || $row_cabang->url != NULL)
                        {
                            $insert_inventory_identitas_id = insert_data_api_id($data_inventory_pengiriman_identitas,$row_cabang->url,$path_model, $inserted_inventory_identitas_id);                    
                            $delete_inventory_identitas_id = delete_data_api($row_cabang->url,$path_model_delete, $wheres_delete);                    
                        }
                    }
                }
                $inserted_inventory_identitas_id = str_replace('"', '', $inserted_inventory_identitas_id);
                // die_dump($this->db->last_query());
                // die_dump($inserted_inventory_identitas_id);


                //***identitas detail
                $path_model = 'gudang/inventory_pengiriman_identitas_detail_m';
                $wheres_inventory_pengiriman_identitas_id = array(
                    'inventory_pengiriman_identitas_id' => $inventory_identitas['inventory_pengiriman_identitas_id'],
                );

                $data_inventory_pengiriman_identitas_detail = json_decode(get_data_api($cabang[0]['url'],$path_model,$wheres_inventory_pengiriman_identitas_id));
                $inventory_pengiriman_identitas_detail      = object_to_array($data_inventory_pengiriman_identitas_detail);
                // die_dump($this->db->last_query());
                // die_dump($inventory_pengiriman_identitas_detail);
                
                foreach ($inventory_pengiriman_identitas_detail as $inventory_identitas_detail) 
                {
                    $data_inventory_pengiriman_identitas_detail = array(
                        'inventory_identitas_detail_id' => $inventory_identitas_detail['inventory_pengiriman_identitas_detail_id'],
                        'inventory_identitas_id'        => $inventory_identitas_detail['inventory_pengiriman_identitas_id'],
                        'identitas_id'                  => $inventory_identitas_detail['identitas_id'],
                        'judul'                         => $inventory_identitas_detail['judul'],
                        'value'                         => $inventory_identitas_detail['value'],
                        'created_by'                    => $inventory_identitas_detail['created_by'],
                        'created_date'                  => $inventory_identitas_detail['created_date'],
                    );

                    $path_model = 'gudang/inventory_identitas_detail_m';
                    $inserted_inventory_identitas_detail_id = $inventory_identitas_detail['inventory_pengiriman_identitas_detail_id'];
                    
                    $path_model_delete = 'gudang/inventory_pengiriman_identitas_detail_m';
                    $wheres_delete = array(

                        'inventory_pengiriman_identitas_detail_id' => $inventory_identitas['inventory_pengiriman_identitas_id'],

                    );
                    // die_dump($inserted_inventory_identitas_detail_id);

                    foreach ($data_cabang as $row_cabang) 
                    {
                        if($row_cabang->is_active == 1)
                        {
                            if($row_cabang->url != '' || $row_cabang->url != NULL)
                            {
                                $insert_inventory_identitas_detail_id = insert_data_api_id($data_inventory_pengiriman_identitas_detail,$row_cabang->url,$path_model, $inserted_inventory_identitas_detail_id);                    
                                $delete_inventory_identitas_detail_id = delete_data_api($row_cabang->url,$path_model_delete, $wheres_delete);                    
                            }
                        }
                    }
                    $inserted_inventory_identitas_detail_id = str_replace('"', '', $inserted_inventory_identitas_detail_id);
                    // die_dump($this->db->last_query());
                    // die_dump($inserted_inventory_identitas_detail_id);

                }

            }
        }
        //**

        if($array_input['keterangan'] != '')
        {
            $data_pengiriman = array(
                'status' => 5,
            );

            $wheres = array(
                'id' => $id,
            );

            $path_model             = 'apotik/penerimaan_barang/pengiriman_m';
            $pengiriman_id          = update_data_api($data_pengiriman,base_url(),$path_model, $wheres);
            $inserted_pengiriman_id = $pengiriman_id;

            // die_dump($inserted_pengiriman_id);
            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $pengiriman_id = update_data_api($data_pengiriman,$cabang->url,$path_model, $wheres);
                }
            }
            $inserted_pengiriman_id = str_replace('"', '', $inserted_pengiriman_id);
            // die_dump($inserted_pengiriman_id);
            
           
        }

        if($pengiriman_id)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda telah menolak semua item tersebut", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        redirect('apotik/penerimaan_barang');
    
    }

    public function terima()
    {
        $array_input = $this->input->post();
        $data_cabang = $this->cabang_m->get_by(array('tipe' => 2, 'is_active' => 1));
        $cabang = object_to_array($data_cabang);
        
        $id_kirim = $array_input['pengiriman_id'];
        $data_inventory_pengiriman = array();
        $data_inventory_pengiriman_identitas = array();
        $data_inventory_pengiriman_identitas_detail = array();

        $last_id     = $this->pmb_m->get_max_id_pmb()->result_array();
        $last_id     = intval($last_id[0]['max_id'])+1;
        
        $format_id   = 'PMB-'.date('m').'-'.date('Y').'-%04d';
        $id_pmb       = sprintf($format_id, $last_id, 4);

        $last_number = $this->pmb_m->get_no_pmb()->result_array();
        $last_number = intval($last_number[0]['max_no_pmb'])+1;
        
        $format      = '#PMB#%03d/RHS-RI/'.romanic_number(date('m'), true).'/'.date('Y');
        $no_pmb      = sprintf($format, $last_number, 3);

        $tanggal = date("Y-m-d");

        $new_filename = '';
        if($array_input['url_faktur'] != '')
        {
            $path_dokumen = './assets/mb/pages/apotik/penerimaan_barang/images/'.str_replace(' ', '_', $id_pmb);
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

            $temp_filename = $array_input['url_faktur'];

            $convtofile = new SplFileInfo($temp_filename);
            $extenstion = ".".$convtofile->getExtension();

            $new_filename = $id_pmb.$extenstion;
            $real_file = str_replace(' ', '_', $id_pmb).'/'.$new_filename;

            copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_faktur_pmb').$real_file);
        }

        $data_pmb = array(
            'id'              => $id_pmb,
            'gudang_id'       => 1,
            'supplier_id'     => 1,
            'tipe_supplier'   => 1,
            'tanggal'         => $tanggal,
            'no_pmb'          => $no_pmb,
            'no_surat_jalan'  => $array_input['no_surat_jalan'],
            'no_faktur'       => '',
            'url_surat_jalan' => $new_filename,
            'status'          => 1,
            'is_active'       => 1
        );

        $pmb_save = $this->pmb_m->add_data($data_pmb);

        $apotik = $this->cabang_m->get_by(array('id' => config_item('apotik_id')), true);

        $data_inventory_history = array(
            'transaksi_id'   => $id_pmb,
            'transaksi_tipe' => 6
        );
        $inventory_history_id = $this->inventory_history_m->save($data_inventory_history);

        // $path_model = 'apotik/inventory_history_m';
        // $inv_detail_tujuan_api = insert_data_api($data_inventory_history, $apotik->url, $path_model);

        //proses insert inventory, inventory_identitas, inventory_identitas_detail
        $get_data_pengiriman_detail = $this->pengiriman_detail_m->get_by(array('pengiriman_id' => $id_kirim));
        $data_pengiriman_detail     = object_to_array($get_data_pengiriman_detail);

        foreach ($data_pengiriman_detail as $row) 
        {            
            $max_id_pmb_detail = $this->pmb_detail_m->get_max_id_pmb_detail()->result_array();

            if(count($max_id_pmb_detail) == 0)
            {
                $max_id_pmb_detail = 1;
            }
            else
            {
                $max_id_pmb_detail = intval($max_id_pmb_detail[0]['max_id']) + 1;
            }

            $format           = 'PMBD-'.date('m').'-'.date('Y').'-%04d';
            $id_pmb_detail    = sprintf($format, $max_id_pmb_detail, 4);


            $data_pmb_detail = array(
                'id'                     => $id_pmb_detail,
                'pmb_id'                 => $id_pmb,
                'item_id'                => $row['item_id'],
                'item_satuan_id'         => $row['item_satuan_id'],
                'jumlah_diterima'        => $row['jumlah'],
                'item_satuan_primary_id' => $row['item_satuan_primary_id'],
                'jumlah_primary'         => $row['jumlah_konversi'],
                'bn_sn_lot'              => $row['bn_sn_lot'],
                'expire_date'            => date('Y-m-d', strtotime($row['expire_date'])),
                'created_by'             => $this->session->userdata('user_id'),
                'created_date'           => date('Y-m-d H:i:s')
            );

            $pmb_detail_save = $this->pmb_detail_m->add_data($data_pmb_detail);
            

            $max_id   = '';
            $maksimum = $this->inventory_m->get_max_id()->result_array();

            if(count($maksimum) == 0)
            {
                $max_id = 1;
            }
            else {
                $max_id = intval($maksimum[0]['max_id'])+1;
            }


            $data_inventory_tujuan = array(
                'inventory_id'        => $max_id,
                'gudang_id'           => 'WH-05-2016-001',
                'pmb_id'              => $id_pmb,
                'pembelian_detail_id' => $id_pmb_detail,
                'box_paket_id'        => $row['box_paket_id'],
                'kode_box_paket'      => $row['kode_box_paket'],
                'item_id'             => $row['item_id'],
                'item_satuan_id'      => $row['item_satuan_primary_id'],
                'jumlah'              => $row['jumlah_konversi'],
                'tanggal_datang'      => date('Y-m-d H:i:s'),
                'bn_sn_lot'           => $row['bn_sn_lot'],
                'expire_date'         => date('Y-m-d', strtotime($row['expire_date'])),
                'harga_beli'          => null,
                'created_by'          => $this->session->userdata('user_id'),
                'created_date'        => date('Y-m-d H:i:s')
            );
           
            $inventory_id = $this->inventory_m->add_data($data_inventory_tujuan);


            $data_inventory_history_detail = array(
                'inventory_history_id' => $inventory_history_id,
                'gudang_id'            => $row_inv['gudang_id'],
                'pmb_id'               => $id_pmb,
                'pembelian_detail_id'  => $id_pmb_detail,
                'box_paket_id'         => $row['box_paket_id'],
                'kode_box_paket'       => $row['kode_box_paket'],
                'item_id'              => $row['item_id'],
                'item_satuan_id'       => $row['item_satuan_id'],
                'initial_stock'        => 0,
                'bn_sn_lot'              => $row['bn_sn_lot'],
                'expire_date'            => date('Y-m-d', strtotime($row['expire_date'])),
                'change_stock'         => $row['jumlah'],
                'final_stock'          => (0 + intval($row['jumlah'])),
                'harga_beli'           => 0,
                'total_harga'          => (intval($row['jumlah'])*0),
            );

            $inventory_history_detail_id = $this->inventory_history_detail_m->save($data_inventory_history_detail);

        }
        //end of proses insert inventory, inventory_identitas, inventory_identitas_detail

        //start update pengiriman
        $data_pengiriman = array(
            'status'        => 2,
            'diterima_oleh' => $this->session->userdata('user_id'),
            'tanggal_diterima' => date('Y-m-d H:i:s'),
        );

        $wheres = array(
            'id' => $id_kirim,
        );

        $path_model             = 'apotik/penerimaan_barang/pengiriman_m';
        $pengiriman_id          = update_data_api($data_pengiriman,base_url(),$path_model, $wheres);
        $inserted_pengiriman_id = $pengiriman_id;

        // die_dump($inserted_pengiriman_id);
        foreach ($data_cabang as $cabang) 
        {
            if($cabang->url != '' || $cabang->url != NULL)
            {
                $pengiriman_id = update_data_api($data_pengiriman,$cabang->url,$path_model, $wheres);
            }
        }
        $inserted_pengiriman_id = str_replace('"', '', $inserted_pengiriman_id);
        // die_dump($inserted_pengiriman_id);
        //end of update pengiriman 
    
        $get_data_pengiriman_detail = $this->pengiriman_detail_m->get_by(array('pengiriman_id' => $id));

        if($pengiriman_id)
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Anda telah Menerima semua item tersebut", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        redirect('apotik/penerimaan_barang');
    }


    public function modal_detail($pengiriman_id,$item_id)
    {
        $data_detail = $this->pengiriman_detail_m->get_by(array('pengiriman_id' => $pengiriman_id,'item_id' => $item_id));
        $data_item = $this->item_m->get($item_id);

        $data_item_identitas = $this->item_identitas_m->get_item_identitas($item_id)->result_array();

        $data = array(
            'pengiriman_id' => $pengiriman_id,
            'item_id'       => $item_id,
            'data_detail'   => object_to_array($data_detail),
            'data_item'   => object_to_array($data_item),
            'data_item_identitas' => $data_item_identitas
        );

        $this->load->view('apotik/penerimaan_barang/modal/modal_identitas', $data);
    }


}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */