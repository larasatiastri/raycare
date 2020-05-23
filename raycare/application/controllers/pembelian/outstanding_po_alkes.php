<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Outstanding_po_alkes extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'af6d9cd5b207a9296b5cfb942543a80a';                  // untuk check bit_access

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

        $this->load->model('pembelian/o_s_pmsn_m');
        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_m');
        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_detail_m');
        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_detail_other_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_permintaan_barang_history_m');
        $this->load->model('pembelian/permintaan_po/o_p_p_d_o_item_file_m');
        $this->load->model('pembelian/daftar_permintaan_po_m');
        $this->load->model('pembelian/item_m');
        $this->load->model('pembelian/draft_po_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/supplier/supplier_m');
        $this->load->model('master/supplier/supplier_item_m');
        $this->load->model('master/user_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/item/item_satuan_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/pembelian/outstanding_po_alkes/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Daftar Outstanding PO', $this->session->userdata('language')), 
            'header'         => translate('Daftar Outstanding PO', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/outstanding_po_alkes/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/pembelian/outstanding_po_alkes/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Daftar Outstanding PO", $this->session->userdata("language")), 
            'header'         => translate("Daftar Outstanding PO", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/outstanding_po_alkes/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/pembelian/permintaan_po/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
               
        $form_data = $this->order_permintaan_barang_m->get_by(array('id' => $id), true);
        $form_data = object_to_array($form_data);
        if($form_data['tipe'] == 1)
        {
            $form_data_detail = $this->order_permintaan_barang_detail_m->get_data_detail($id);
            $form_data_detail = object_to_array($form_data_detail);
            $form_data_detail_other = '';
        }
        else
        {
            $form_data_detail = 'Tests';
            $form_data_detail_other = $this->order_permintaan_barang_detail_other_m->get_data_detail($id);        
            $form_data_detail_other = object_to_array($form_data_detail_other);
        }

        $data = array(
            'title'                  => config_item('site_name').' | '. translate("View Permintaan Barang", $this->session->userdata("language")), 
            'header'                 => translate("View Permintaan Barang", $this->session->userdata("language")), 
            'header_info'            => config_item('site_name'), 
            'breadcrumb'             => TRUE,
            'menus'                  => $this->menus,
            'menu_tree'              => $this->menu_tree,
            'css_files'              => $assets['css'],
            'js_files'               => $assets['js'],
            'content_view'           => 'pembelian/permintaan_po/view',
            'form_data'              => $form_data,
            'form_data_detail'       => $form_data_detail,
            'form_data_detail_other' => $form_data_detail_other,
            'pk_value'               => $id
            //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($tipe)
    {               
        $result = $this->o_s_pmsn_m->get_datatable($tipe);

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
            $info = '';
            $action = '<input type="checkbox" data-item_id="'.$row['item_id'].'" data-item_satuan_id="'.$row['item_satuan_id'].'" data-jumlah="'.$row['jumlah'].'" class="form-control" name="input['.$i.'][pilih]" id="input_pilih_'.$i.'"><input type="hidden" data-index="'.$i.'" class="form-control" name="input['.$i.'][pilih_supplier]" id="input_pilih_supplier_'.$i.'">';
        
            $supplier = $this->supplier_item_m->get_by(array('item_id' => $row['item_id'], 'is_supply' => 1,'is_active' => 1));

            $supplier_option = array(
                ''  => 'Pilih...'
            );

            foreach ($supplier as $sup) {

                $data_supplier = $this->supplier_m->get_by(array('id' => $sup->supplier_id), true);

                $supplier_option[$data_supplier->id] = $data_supplier->nama;
            }

            $select_supplier = form_dropdown('input['.$i.'][supplier_id]', $supplier_option,'','id="input_supplier_id_'.$i.'" class="form-control" data-index="'.$i.'"');
            
            $detail = $this->o_s_pmsn_m->get_detail_os($row['item_id'], $row['item_satuan_id'])->result_array();

            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($detail)).'" class="item-unlist inline-button-table" data-id="'.$row['id'].'" name="info"><u>'.$row['jumlah'].' '.$row['nama_item_satuan'].'</u></a>';

            $output['data'][] = array(
                $row['id'],
                $row['nama_item'],
                $info,
                $select_supplier,
                '<div class="text-center">'.$action.'</div>' 
            );
            
            $i++;
        }

        echo json_encode($output);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing_history($tipe)
    {               
        $result = $this->o_s_pmsn_m->get_datatable_history($tipe);

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

            $detail = $this->o_s_pmsn_m->get_detail_os_processed($row['item_id'], $row['item_satuan_id'])->result_array();

            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($detail)).'" class="item-unlist inline-button-table" data-id="'.$row['id'].'" name="info"><u>'.$row['jumlah'].' '.$row['nama_item_satuan'].'</u></a>';

            $output['data'][] = array(
                $row['id'],
                $row['nama_item'],
                $info
            );
            
            $i++;
        }

        echo json_encode($output);
    }

    public function generate_po()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();

            $response = new stdClass;
            $response->array_supplier = '';

            // die(dump($array_input));
            $supplier = array();
            $item_id = array();
            $item_satuan_id = array();
            $jumlah_pesan = array();

            foreach ($array_input['data_item_id'] as $key_item => $item_value) {
                $item_id[$key_item] = $item_value;
            }

            foreach ($array_input['data_item_satuan_id'] as $key_satuan => $item_satuan_value) {
                $item_satuan_id[$key_satuan] = $item_satuan_value;
            }

            foreach ($array_input['data_jumlah'] as $key_jumlah => $jumlah_value) {
                $jumlah_pesan[$key_jumlah] = $jumlah_value;
            }

            foreach ($array_input['data_supplier_id'] as $key => $supplier_id) {
                $supplier[$supplier_id]['item_id'][] = $item_id[$key];
                $supplier[$supplier_id]['item_satuan_id'][] = $item_satuan_id[$key];
                $supplier[$supplier_id]['jumlah_pesan'][] = $jumlah_pesan[$key];
            }

            foreach ($array_input['data_supplier_id'] as $key => $supplier_id) {
                $supplier[$supplier_id]['item_id_array'] = urlencode(base64_encode(serialize($supplier[$supplier_id]['item_id'])));
                $supplier[$supplier_id]['item_satuan_id_array'] = urlencode(base64_encode(serialize($supplier[$supplier_id]['item_satuan_id'])));
                $supplier[$supplier_id]['jumlah_array'] = urlencode(base64_encode(serialize($supplier[$supplier_id]['jumlah_pesan'])));
            }


            die(json_encode($supplier));


        }   
    }
   

}

/* End of file branch.php */
/* Location: ./application/controllers/pembelian/daftar_permintaan_po.php */