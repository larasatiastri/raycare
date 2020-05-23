<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pemisahan_item extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '579567fc34a1b1e14a8642d27604644d';                  // untuk check bit_access

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

        $this->load->model('apotik/pemisahan_item/item_m');
        $this->load->model('apotik/item_satuan_m');
        $this->load->model('apotik/inventory_m');
        $this->load->model('apotik/inventory_identitas_detail_m');

        $this->load->model('apotik/pemisahan_item/list_inventory_m');
        $this->load->model('apotik/pemisahan_item/inventory_identitas_m');
        $this->load->model('apotik/pemisahan_item/inventory_history_m');
        $this->load->model('apotik/pemisahan_item/inventory_history_detail_m');
        $this->load->model('apotik/pemisahan_item/inventory_history_identitas_m');
        $this->load->model('apotik/pemisahan_item/inventory_history_identitas_detail_m');
        $this->load->model('apotik/pemisahan_item/pemisahan_item_m');
        $this->load->model('apotik/pemisahan_item/pemisahan_item_detail_m');
        $this->load->model('apotik/pemisahan_item/pemisahan_item_kondisi_m');
        $this->load->model('apotik/pemisahan_item/pemisahan_item_identitas_m');

        $this->load->model('apotik/gudang_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/kategori_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/pemisahan_item/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Pemisahan Item ', $this->session->userdata('language')), 
            'header'         => translate('Pemisahan Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/pemisahan_item/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/apotik/pemisahan_item/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('History Pemisahan Item ', $this->session->userdata('language')), 
            'header'         => translate('History Pemisahan Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/pemisahan_item/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add($id, $id_gudang)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $assets_config = 'assets/apotik/pemisahan_item/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->item_m->get($id);
        $form_data_gudang = $this->gudang_m->get_by(array('id' => $id_gudang), true);


        $data = array(
            'title'          => config_item('site_name').' | '. translate("Tambah Pemisahan Item", $this->session->userdata("language")), 
            'header'         => translate("Tambah Pemisahan Item", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/pemisahan_item/add',
            'form_data'      => object_to_array($form_data),
            'form_data_gudang'=> object_to_array($form_data_gudang)
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/pemisahan_item/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
       
        // $form_data = $this->cabang__m->get($id);

        $data = array(
            'title'          => config_item('site_name'). translate("View Pemisahan Item", $this->session->userdata("language")), 
            'header'         => translate("View Pemisahan Item", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/pemisahan_item/view',
            // 'form_data'      => object_to_array($form_data),
            'pk_value'       => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($id_gudang = null, $kategori = null, $sub_kategori = null)
    {        
        $result = $this->list_inventory_m->get_datatable($id_gudang, $kategori, $sub_kategori);
        // die_dump($result);
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
            $action = '';
            
            if($row['jumlah_satuan'] > 1)
            {
                $action = '<a title="'.translate('Pecah Item', $this->session->userdata('language')).'" href="'.base_url().'apotik/pemisahan_item/add/'.$row['id'].'/'.$row['id_gudang'].'" name="pecah[]" class="btn blue-chambray pecah-item" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-chain-broken"></i></a>';
            }

            $satuan = $this->list_inventory_m->get_info_satuan($row['id'], $row['id_gudang'])->result_array();
            $satuan_data = htmlentities(json_encode($satuan));
           
            // $info = '<a title="'.translate('', $this->session->userdata('language')).'" name="info[]" class="btn btn-primary pilih-info" data-id="'.$row['id'].'" data-id_gudang="'.$row['id_gudang'].'" style="margin:0px;">'.$row['jumlah_satuan'].' satuan</a>';
            $info = '<a class="show-popover btn btn-primary" data-satuan="'.$satuan_data.'">'.$row['jumlah_satuan'].' satuan</a>';


            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.$row['kode_item'].'</div>',
                $row['nama_item'],
                '<div class="text-center">'.$info.'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_info_item($id=null, $id_gudang=null)
    {        
        $result = $this->list_inventory_m->get_datatable_info_item($id, $id_gudang);
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
            $output['data'][] = array(
                '<div class="text-center">'.$row['jumlah_item'].'</div>',
                '<div class="text-center">'.$row['satuan'].'</div>'
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_history_pecah($id_gudang=null)
    {        
        $result = $this->pemisahan_item_m->get_datatable($id_gudang);
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
            $action = '';
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/pemisahan_item/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';


            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.date('d M Y H:i', strtotime($row['tanggal_pecah'])).'</div>',
                '',
                $row['subjek'],
                '<div class="text-center">'.$row['kode_item'].'</div>',
                $row['nama_item'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function tambah_pisah_item($id, $id_gudang, $index)
    {
        $data_detail = $this->list_inventory_m->get_data_stok_awal($id, $id_gudang)->result_array();

        $data = array(
            'data_konvert'      => object_to_array($data_detail),  
            'id'                => $id,          
            'id_gudang'         => $id_gudang,          
            'index'         => $index,          
        );
        // $this->load->view('_layout', $data);
        $this->load->view('apotik/pemisahan_item/modals/tambah_pemisahan_item_modal', $data);
    }

    public function data_batch_number($gudang_id, $item_id, $item_satuan_id, $identitas_row)
    {
        $data = array(
            'gudang_id'      => $gudang_id,  
            'item_id'        => $item_id,                  
            'item_satuan_id' => $item_satuan_id ,
            'identitas_row'  => $identitas_row               
        );
        // die_dump($data);
        // $this->load->view('_layout', $data);
        $this->load->view('apotik/pemisahan_item/modals/batch_number_modal', $data);
    }

    public function save()
    {
        $array_input = $this->input->post();
        // die_dump($array_input);

        $data_head = array(
            'item_id'      => $array_input['id_item'],
            'gudang_id'     => $array_input['id_gudang'],
            'tanggal'       => date('Y-m-d H:i:s', strtotime($array_input['tanggal_pecah'])),
            'subjek'        => $array_input['subjek'],
            'keterangan'    => $array_input['keterangan'],
            'is_active'     => 1
        );
        $pemisahan_item_id = $this->pemisahan_item_m->save($data_head);
        // die_dump($pemisahan_item_id);

        $data_inventory_history = array(
            'transaksi_id'     => $pemisahan_item_id,
            'transaksi_tipe'    => 1  
        );
        $inventory_history = $this->inventory_history_m->save($data_inventory_history);

        $i = 1;
        foreach ($array_input['items'] as $idx => $konversi) {

            if($konversi['item_sebelum_satuan'] != null && $konversi['item_sesudah_satuan'] != null && $konversi['item_sebelum_jumlah'] != null && $konversi['item_sesudah_jumlah'] != null)
            {
                $data_pemisahan_detail = array(
                    'pemisahan_item_id'      => $pemisahan_item_id,
                    'item_satuan_sebelum_id' => $konversi['item_sebelum_satuan'],
                    'item_satuan_sesudah_id' => $konversi['item_sesudah_satuan'],
                    'jumlah_sebelum'         => $konversi['item_sebelum_jumlah'],
                    'jumlah_sudah'           => $konversi['item_sesudah_jumlah'],
                );
                $pemisahan_item_detail_id = $this->pemisahan_item_detail_m->save($data_pemisahan_detail);

                // die_dump($pemisahan_item_detail_id);
                $data_kondisi_awal_sebelum = array(
                    'pemisahan_id'   => $pemisahan_item_id,
                    'tipe'           => 1,
                    'item_satuan_id' => $konversi['item_sebelum_satuan'],
                    'jumlah'         => $konversi['stok_awal_sebelum'],
                );
                $pemisahan_item_kondisi_awal = $this->pemisahan_item_kondisi_m->save($data_kondisi_awal_sebelum);

                $data_kondisi_akhir_sebelum = array(
                    'pemisahan_id'   => $pemisahan_item_id,
                    'tipe'           => 2,
                    'item_satuan_id' => $konversi['item_sebelum_satuan'],
                    'jumlah'         => $konversi['stok_akhir_sebelum'],
                );
                $pemisahan_item_kondisi_akhir_sebelum = $this->pemisahan_item_kondisi_m->save($data_kondisi_akhir_sebelum);

                $data_kondisi_awal_sesudah = array(
                    'pemisahan_id'   => $pemisahan_item_id,
                    'tipe'           => 1,
                    'item_satuan_id' => $konversi['item_sesudah_satuan'],
                    'jumlah'         => $konversi['stok_awal_sesudah'],
                );
                $pemisahan_item_kondisi_awal_sesudah = $this->pemisahan_item_kondisi_m->save($data_kondisi_awal_sesudah);

                $data_kondisi_akhir_sesudah = array(
                    'pemisahan_id'   => $pemisahan_item_id,
                    'tipe'           => 2,
                    'item_satuan_id' => $konversi['item_sesudah_satuan'],
                    'jumlah'         => $konversi['stok_akhir_sesudah'],
                );
                $pemisahan_item_kondisi_akhir_sesudah = $this->pemisahan_item_kondisi_m->save($data_kondisi_akhir_sesudah);

                $data_inventory_history_detail_awal = array(
                    'inventory_history_id' => $inventory_history,
                    'gudang_id'            => $array_input['id_gudang'],  
                    'pmb_id'               => 1,  
                    'item_id'              => $array_input['id_item'],  
                    'item_satuan_id'       => $konversi['item_sebelum_satuan'],  
                    'initial_stock'        => $konversi['stok_awal_sebelum'],  
                    'change_stock'         => '-'.$konversi['item_sebelum_jumlah'],  
                    'final_stock'          => $konversi['stok_akhir_sebelum'],  
                );
                $inventory_history_detail_awal = $this->inventory_history_detail_m->save($data_inventory_history_detail_awal);

                $data_inventory_history_detail_akhir = array(
                    'inventory_history_id' => $inventory_history,
                    'gudang_id'            => $array_input['id_gudang'],  
                    'pmb_id'               => 1,  
                    'item_id'              => $array_input['id_item'],  
                    'item_satuan_id'       => $konversi['item_sesudah_satuan'],  
                    'initial_stock'        => $konversi['stok_awal_sesudah'],  
                    'change_stock'         => '+'.$konversi['item_sesudah_jumlah'],  
                    'final_stock'          => $konversi['stok_akhir_sesudah'],  
                );
                $inventory_history_detail_akhir = $this->inventory_history_detail_m->save($data_inventory_history_detail_akhir);

                // die_dump($this->db->last_query());
                if(empty($array_input['identitas_'.$array_input['id_item'].'_'.$idx]))
                {
                    $tanggal_sekarang = date('Y-m-d H:i:s');
                    // die_dump('a');
                    $data_inventory_1 = $this->inventory_m->get_fifo($array_input['id_gudang'], $array_input['id_item'], $konversi['item_sebelum_satuan'])->result_array();
                    $inventory_1 = object_to_array($data_inventory_1);
                    $inventory = $this->inventory_m->update_inventory($inventory_1[0]['inventory_id'], $konversi['stok_akhir_sebelum'], $tanggal_sekarang);

                    $stok = $konversi['item_sebelum_jumlah'];

                    foreach ($inventory_1 as $invent) 
                    {   
                        if($stok > $invent['jumlah']){
                            $stok = $stok-$invent['jumlah'];
                            // die_dump($stok);
                            $delete=$this->inventory_m->delete_inventory($invent['inventory_id']);
                        }
                        else
                        {
                            $stok = $invent['jumlah']-$stok;
                            // die_dump($stok);
                            $update_jumlah = $this->inventory_m->save_data($stok, $invent['inventory_id']);

                            if($stok != 0)
                            {
                                break; 
                            }
                        }
                    }


                    $data_inventory_2 = $this->inventory_m->get_fifo($array_input['id_gudang'], $array_input['id_item'], $konversi['item_sesudah_satuan'])->result_array();
                    $inventory_2 = object_to_array($data_inventory_2);
                    // die_dump($this->db->last_query());

                    if(!empty($inventory_2))
                    {
                        $inventory = $this->inventory_m->update_inventory($inventory_2[0]['inventory_id'], $konversi['stok_akhir_sesudah'], $tanggal_sekarang);
                    }
                    else
                    {
                        $id = $this->inventory_m->get_last_id()->result_array();

                        $inventory = $this->inventory_m->insert_inventory(intval($id[0]['MAX(inventory_id)'])+1, $array_input['id_gudang'], $array_input['id_item'], $konversi['item_sesudah_satuan'], $konversi['stok_akhir_sesudah'], $tanggal_sekarang);  
                    }
                    // die_dump($this->db->last_query());
                }
                else
                {
                    $z = 0;
                    foreach ($array_input['identitas_'.$array_input['id_item'].'_'.$idx] as $key => $identitas)
                    {
                        if($identitas['jumlah'] != 0){
                            $data_pemisahan_identitas = array(
                                'pemisahan_item_detail_id'     => $pemisahan_item_detail_id,
                                'jumlah'    => $identitas['jumlah'],
                            );
                            $pemisahan_item_identitas_id = $this->pemisahan_item_identitas_m->save($data_pemisahan_identitas);
                            
                            // die_dump($this->db->last_query());

                            $data_inventory_history_identitas_awal = array(
                                'inventory_history_detail_id'     => $inventory_history_detail_awal,
                                'jumlah'    => $identitas['jumlah'],
                            );
                            $inventory_history_identitas_awal = $this->inventory_history_identitas_m->save($data_inventory_history_identitas_awal);

                            $invent_jumlah = $this->inventory_m->get_by(array('inventory_id' => $identitas['inventory_id']));
                            $data_invent_jumlah = object_to_array($invent_jumlah);

                            // die_dump($data_invent_jumlah);
                            $tanggal_sekarang = date('Y-m-d H:i:s');

                            $jml_invent = $data_invent_jumlah[0]['jumlah']-$identitas['jumlah'];
                            $inventory = $this->inventory_m->update_inventory($identitas['inventory_id'], $jml_invent, $tanggal_sekarang);

                            $jumlah_inventory = ($identitas['jumlah']/$konversi['item_sebelum_jumlah'])*$konversi['item_sesudah_jumlah'];

                            $id = $this->inventory_m->get_last_id()->result_array();
                            $identitas_id = $this->inventory_identitas_m->get_last_id()->result_array();
                           
                            
                            if($identitas['update_jumlah'] == 0 && $identitas['update_jumlah'] != '')
                            {
                                $data_inv = array(
                                    'inventory_id'   => intval($id[0]['MAX(inventory_id)'])+1, 
                                    'gudang_id'      => $array_input['id_gudang'], 
                                    'pmb_id'         => 0,
                                    'item_id'        => $array_input['id_item'], 
                                    'item_satuan_id' => $konversi['item_sesudah_satuan'], 
                                    'bn_sn_lot'      => $identitas['bn_sn_lot'], 
                                    'expire_date'    => date('Y-m-d', strtotime($identitas['expire_date'])), 
                                    'jumlah'         => $jumlah_inventory, 
                                    'tanggal_datang' => $tanggal_sekarang, 
                                    'created_by'     => $this->session->userdata('user_id'), 
                                    'created_date'   => date('Y-m-d H:i:s')
                                );
                                $inventory = $this->inventory_m->add_data($data_inv);

                                $data_inv_identitas = array(
                                    'inventory_identitas_id' => intval($identitas_id[0]['MAX(inventory_identitas_id)'])+1, 
                                    'inventory_id'           => intval($id[0]['MAX(inventory_id)'])+1, 
                                    'jumlah'                 => $jumlah_inventory, 
                                    'created_by'             => $this->session->userdata('user_id'), 
                                    'created_date'           => date('Y-m-d H:i:s')
                                );

                                $inventory_identitas = $this->inventory_identitas_m->add_data($data_inv_identitas);
                                // die_dump($this->db->last_query());

                                foreach ($array_input['identitas_detail_'.$array_input['id_item'].'_'.$key.'_'.$idx] as $identitas_detail) {
                                    $identitas_detail_id = $this->inventory_identitas_detail_m->get_last_id()->result_array();
                                    $data_inv_identitas_detail = array(
                                        'inventory_identitas_detail_id' => intval($identitas_detail_id[0]['MAX(inventory_identitas_detail_id)'])+1, 
                                        'inventory_identitas_id'        => intval($identitas_id[0]['MAX(inventory_identitas_id)'])+1, 
                                        'identitas_id'                  => $identitas_detail['id'], 
                                        'judul'                         => $identitas_detail['judul'], 
                                        'value'                         => $identitas_detail['value'], 
                                        'created_by'                    => $this->session->userdata('user_id'), 
                                        'created_date'                  => date('Y-m-d H:i:s')
                                    );
                                    $inventory_identitas_detail = $this->inventory_identitas_detail_m->add_data($data_inv_identitas_detail);

                                }

                                $del_inventory = $this->inventory_m->delete_inventory($identitas['inventory_id']);
                                $del_inventory_identitas = $this->inventory_identitas_m->delete_inventory_identitas($identitas['inventory_identitas_id']);
                                $del_inventory_identitas_detail = $this->inventory_identitas_detail_m->delete_inventory_identitas_detail_kosong($identitas['inventory_identitas_id']);
                                // die_dump($this->db->last_query());
                            }
                            elseif($identitas['update_jumlah'] > 0 && $identitas['update_jumlah'] != '')
                            {

                                $data_inv = array(
                                    'inventory_id'   => intval($id[0]['MAX(inventory_id)'])+1, 
                                    'gudang_id'      => $array_input['id_gudang'], 
                                    'pmb_id'         => 0,
                                    'item_id'        => $array_input['id_item'], 
                                    'item_satuan_id' => $konversi['item_sesudah_satuan'], 
                                    'bn_sn_lot'      => $identitas['bn_sn_lot'], 
                                    'expire_date'    => date('Y-m-d', strtotime($identitas['expire_date'])), 
                                    'jumlah'         => $jumlah_inventory, 
                                    'tanggal_datang' => $tanggal_sekarang, 
                                    'created_by'     => $this->session->userdata('user_id'), 
                                    'created_date'   => date('Y-m-d H:i:s')
                                );
                                $inventory = $this->inventory_m->add_data($data_inv);

                                $data_inv_identitas = array(
                                    'inventory_identitas_id' => intval($identitas_id[0]['MAX(inventory_identitas_id)'])+1, 
                                    'inventory_id'           => intval($id[0]['MAX(inventory_id)'])+1, 
                                    'jumlah'                 => $jumlah_inventory, 
                                    'created_by'             => $this->session->userdata('user_id'), 
                                    'created_date'           => date('Y-m-d H:i:s')
                                );

                                $inventory_identitas = $this->inventory_identitas_m->add_data($data_inv_identitas);
                                // die_dump($this->db->last_query());

                                foreach ($array_input['identitas_detail_'.$array_input['id_item'].'_'.$key.'_'.$idx] as $identitas_detail) {

                                    $identitas_detail_id = $this->inventory_identitas_detail_m->get_last_id()->result_array();
                                    $data_inv_identitas_detail = array(
                                        'inventory_identitas_detail_id' => intval($identitas_detail_id[0]['MAX(inventory_identitas_detail_id)'])+1, 
                                        'inventory_identitas_id'        => intval($identitas_id[0]['MAX(inventory_identitas_id)'])+1, 
                                        'identitas_id'                  => $identitas_detail['id'], 
                                        'judul'                         => $identitas_detail['judul'], 
                                        'value'                         => $identitas_detail['value'], 
                                        'created_by'                    => $this->session->userdata('user_id'), 
                                        'created_date'                  => date('Y-m-d H:i:s')
                                    );
                                    $inventory_identitas_detail = $this->inventory_identitas_detail_m->add_data($data_inv_identitas_detail);

                                }

                                $up_inventory_identitas = $this->inventory_identitas_m->update_inventory_identitas($identitas['inventory_id'], $identitas['update_jumlah']);
                            }      
                        }
                    }
                    $z++;
                }
            }

            $i++;
        }

        redirect("apotik/pemisahan_item");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->cabang__m->save($data, $id);

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

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Branch Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("apotik/pemisahan_item");
    }

    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->cabang__m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Branch Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("apotik/pemisahan_item");
    }

    public function get_konversi()
    {
        $id_konversi = $this->input->post('id_konversi');
        $id_item = $this->input->post('item');

        $nilai_konversi         = $this->inventory_m->get_nilai_konversi($id_konversi);
       
        echo json_encode($nilai_konversi);
    }

    public function get_identitas_item()
    {
        $item_satuan_id = $this->input->post('id_konversi');
        $item_id     = $this->input->post('item');
        $id_gudang   = $this->input->post('id_gudang');
        $index   = $this->input->post('index');

        $item_identitas='';
        $identitas_row_template='';
        $check = '';
        $get_group_indetitas = $this->inventory_m->get_data($item_id,$item_satuan_id, $id_gudang)->result_array();

        $group_indetitas = object_to_array($get_group_indetitas);
        $i = 1;
        

        foreach ($group_indetitas as $group) {
            $type = '';
            $type .= '<td class="text-center no_urut" id="no">'.$i.'</td>';
            $type .= '<td>
                        <label class="control-label">'.$group['bn_sn_lot'].'</label>
                        <input type="hidden" id="identitas_bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][bn_sn_lot]" value="'.$group['bn_sn_lot'].'">
                      </td>';
            $type .= '<td>
                        <label class="control-label">'.date('Y M d', strtotime($group['expire_date'])).'</label>
                        <input type="hidden" id="identitas_expire_date_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][expire_date]" value="'.$group['expire_date'].'">
                      </td>';

            $type .= '<td class="text-center">
                    <label class="control-label">'.$group['jumlah'].'</label>
                    <input type="number" class="form-control text-right stock_item hidden" id="identitas_stock_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][stock]" min="1" value="'.$group['jumlah'].'">
                    <input type="hidden" class="form-control text-right update_jumlah" id="identitas_update_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][update_jumlah]">
                    <input type="number" class="hidden" id="identitas_harga_beli_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][harga_beli]" value="'.$data['harga_beli'].'">
                    <input type="hidden" id="identitas_gudang_id_'.$i.'_'.$index.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][gudang_id]" value="'.$group['gudang_id'].'">
                    <input type="hidden" id="identitas_pmb_id_'.$i.'_'.$index.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][pmb_id]" min="1" value="'.$group['pmb_id'].'">
                    <input type="hidden" id="identitas_inventory_id_'.$i.'_'.$index.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][inventory_id]" value="'.$group['inventory_id'].'">
                  </td>';
            $type .= '<td>
                        <input type="number" class="form-control text-right jumlah_item" id="identitas_jumlah_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][jumlah]" min="0"  max="'.$data['jumlah'].'" data-row="'.$i.'" value="0">
                      </td>';
                $identitas_row_template[] =  '<tr id="identitas_row_template'.$i.'" class="table_item">'.$type.'</tr>';
                // die_dump($identitas_row_template);
        $i++;                      
        }   


        $htmls = ' <table class="table table-striped table-bordered table-hover" id="table_identitas_item">
        <thead>
            <tr class="heading">
                <th class="text-center" style="width : 5% !important;">'.translate("No", $this->session->userdata("language")).'</th>';
                
        $htmls .= '<th class="text-center" style="width : 5% !important;">'.translate("Batch Number", $this->session->userdata("language")).'</th>
                <th class="text-center" style="width : 15% !important;">'.translate("Expire Date", $this->session->userdata("language")).'</th>';

        $htmls .= '<th class="text-center" style="width : 5% !important;">'.translate("Stock", $this->session->userdata("language")).'</th>
                <th class="text-center" style="width : 15% !important;">'.translate("Jumlah", $this->session->userdata("language")).'</th>
            </tr>
        </thead>
        <tbody>';
            foreach ($identitas_row_template as $row){
                $htmls .= $row;
            }
        $htmls .= '</tbody></table>';

        echo $htmls;

    }

    public function get_konversi_item()
    {
        $id_konversi = $this->input->post('id_konversi');
        $id_item = $this->input->post('item');
        $id_awal = $this->input->post('id_awal');
        // die_dump($id_awal);


        $konversi_item = $this->list_inventory_m->get_jumlah($id_item, $id_konversi, $id_awal)->result_array();
        // die_dump($this->db->last_query());        
        // $hasil_konversi_item = object_to_array($konversi_item);

        // die_dump($konversi_item);
        echo json_encode($konversi_item);
    }

    public function get_sub_kategori(){

        $id_kategori = $this->input->post('id_kategori');
                
        $sub_kategori       = $this->item_sub_kategori_m->get_by(array('item_kategori_id' => $id_kategori));
        $hasil_sub_kategori = object_to_array($sub_kategori);

        echo json_encode($hasil_sub_kategori);
    }
}

