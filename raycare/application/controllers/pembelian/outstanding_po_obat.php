<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Outstanding_po_obat extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '7169e2f99510f6dffa664fe5d244ebac';                  // untuk check bit_access

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
        $this->load->model('pembelian/permintaan_po/permintaan_status_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_detail_m');
        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_barang_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/pembelian/outstanding_po_obat/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data_kasbon = $this->permintaan_biaya_barang_m->get_data_group(3)->result_array();

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
            'data_kasbon'    => $data_kasbon,
            'content_view'   => 'pembelian/outstanding_po_obat/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/pembelian/outstanding_po_obat/add';
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
            'content_view'   => 'pembelian/outstanding_po_obat/add',
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
        //die(dump($records));
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
    public function listing_kasbon($tipe, $permintaan_biaya_id)
    {               
        $result = $this->o_s_pmsn_m->get_datatable_kasbon($tipe, $permintaan_biaya_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $info = '';
            $action = '<input type="checkbox" data-item_id="'.$row['item_id'].'" data-item_satuan_id="'.$row['item_satuan_id'].'" data-permintaan_biaya_id="'.$row['permintaan_biaya_id'].'" data-jumlah="'.$row['jumlah'].'" class="form-control" name="input['.$i.'][pilih]" id="input_pilih_'.$i.'"><input type="hidden" data-index="'.$i.'" class="form-control" name="input['.$i.'][pilih_supplier]" id="input_pilih_supplier_'.$i.'">';
        
            $supplier = $this->supplier_item_m->get_by(array('item_id' => $row['item_id'], 'is_supply' => 1,'is_active' => 1));

            $supplier_option = array(
                ''  => 'Pilih...'
            );

            foreach ($supplier as $sup) {

                $data_supplier = $this->supplier_m->get_by(array('id' => $sup->supplier_id), true);

                $supplier_option[$data_supplier->id] = $data_supplier->nama;
            }

            $detail = $this->o_s_pmsn_m->get_detail_os_kasbon($row['item_id'], $row['item_satuan_id'])->result_array();

            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($detail)).'" class="item-unlist inline-button-table" data-id="'.$row['id'].'" name="info"><u>'.$row['jumlah'].' '.$row['nama_item_satuan'].'</u></a>';

            $select_supplier = form_dropdown('input['.$i.'][supplier_id]', $supplier_option,'','id="input_supplier_id_'.$i.'" class="form-control" data-index="'.$i.'"');

            $output['data'][] = array(
                $row['id'],
                $row['nama_item'],
                $info,
                '<div class="input-group select2-bootstrap-append select2-bootstrap-prepend input-large">'.$select_supplier.'<span class="select2 select2-container select2-container--bootstrap" dir="ltr"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-vlfm-container"><span class="select2-selection__rendered" id="select2-vlfm-container"></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><div class="input-group-addon"><a href="http://simrhskld.com/raycare/master/supplier/add" class="btn green" target="_blank">Tambah Supplier</a></div></div>',
                '<div class="text-center">'.$action.'</div>' 
            );
            
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_tolak_permintaan($tipe)
    {               
        $result = $this->o_s_pmsn_m->get_datatable_tolak($tipe);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $info = '';
            $action = '<input type="checkbox" data-item_id="'.$row['item_id'].'" data-item_satuan_id="'.$row['item_satuan_id'].'" data-jumlah="'.$row['jumlah'].'" class="form-control" name="input['.$i.'][pilih]" id="input_pilih_'.$i.'"><input type="hidden" data-index="'.$i.'" class="form-control" name="input['.$i.'][pilih_supplier]" id="input_pilih_supplier_'.$i.'">';
                
            $jumlah_tolak = array(
                'id' => 'input_jumlah_tolak_'.$i,
                'name' => 'input['.$i.'][jumlah_tolak]',
                'class' => 'form-control',
                'type' => 'number',
                'max' => $row['jumlah']
            );

            $input_jumlah = '<div class="input-group">'.form_input($jumlah_tolak).'
                                <span class="input-group-addon">
                                    &nbsp;'.$row['nama_item_satuan'].'&nbsp;
                                </span>
                            </div>';




            $output['data'][] = array(
                $row['id'],
                $row['nama_item'].'<input type="hidden" class="form-control" name="input['.$i.'][id]" id="input_id_'.$i.'" value="'.$row['id'].'"><input type="hidden" class="form-control" name="input['.$i.'][pemesanan_detail_id]" id="input_pemesanan_detail_id_'.$i.'" value="'.$row['pemesanan_detail_id'].'"><input type="hidden" class="form-control" name="input['.$i.'][item_id]" id="input_item_id_'.$i.'" value="'.$row['item_id'].'"><input type="hidden" class="form-control" name="input['.$i.'][item_satuan_id]" id="input_item_satuan_id_'.$i.'" value="'.$row['item_satuan_id'].'"><input type="hidden" class="form-control" name="input['.$i.'][jumlah_minta]" id="input_jumlah_minta_'.$i.'" value="'.$row['jumlah'].'">',
                $row['nama_user'],
                date('d M Y', strtotime($row['tanggal_permintaan'])),
                $row['jumlah'].' '.$row['nama_item_satuan'],
                $input_jumlah,
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
        //die(dump($records));

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

    public function generate_kasbon()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();

            $response = new stdClass;

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


            $response->item_id_array = urlencode(base64_encode(serialize($item_id)));
            $response->item_satuan_id_array = urlencode(base64_encode(serialize($item_satuan_id)));
            $response->jumlah_array = urlencode(base64_encode(serialize($jumlah_pesan)));

            die(json_encode($response));
        }   
    }

    public function tolak_permintaan()
    {
        $this->load->view('pembelian/outstanding_po_obat/modals/tolak_permintaan'); 
    }

    public function tolak_os()
    {
        if($this->input->is_ajax_request()){

            $array_input = $this->input->post();
            $user_id = $this->session->userdata('user_id');

            $response = new stdClass;
            $response->success = false;

            foreach ($array_input['input'] as $input) {
                
                if($input['jumlah_tolak'] != '' && $input['jumlah_tolak'] != 0){

                    $jumlah_proses = $input['jumlah_minta'] - $input['jumlah_tolak'];

                    if($jumlah_proses == 0){
                        $status = 4;
                    }
                    if($jumlah_proses > 0){
                        $status = 3;
                    } 

                    $data_os = array(
                        'jumlah' => $jumlah_proses,
                        'jumlah_tolak' => $input['jumlah_tolak'],
                        'status' => $status,
                        'keterangan_tolak' => $array_input['keterangan'],
                        'modified_by'   => $this->session->userdata('user_id'),
                        'modified_date'   => date('Y-m-d H:i:s')
                    ); 

                    $edit_os_pesan = $this->o_s_pmsn_m->edit_data($data_os, $input['id']);

                    $data_permintaan_detail = array(
                        'jumlah_ditolak'    => $input['jumlah_tolak'],
                        'jumlah_sisa'       => $jumlah_proses,
                        'status'            => $status,
                        'alasan_tolak'      => $array_input['keterangan'],
                        'modified_by'       => $this->session->userdata('user_id'),
                        'modified_date'     => date('Y-m-d H:i:s')
                    ); 

                    $wheres_permintaan_detail = array(
                        'order_permintaan_barang_id' => $input['pemesanan_detail_id'],
                        'item_id'                    => $input['item_id'],
                        'item_satuan_id'             => $input['item_satuan_id'],
                    );

                    $edit_permintaan_detail = $this->order_permintaan_barang_detail_m->update_by($user_id,$data_permintaan_detail, $wheres_permintaan_detail);
                }    

                $data_permintaan = array(
                    'keterangan_tolak'  => $array_input['keterangan'],
                    'modified_by'       => $this->session->userdata('user_id'),
                    'modified_date'     => date('Y-m-d H:i:s')
                );

                if($jumlah_proses == 0){
                    $data_permintaan['status'] = 4;
                }
                if($jumlah_proses > 0){
                    $data_permintaan['status'] = 6;
                }      

                $edit_permintaan = $this->order_permintaan_barang_m->edit_data($data_permintaan, $input['pemesanan_detail_id']);

                $data_permintaan_status = array(
                    'user_level_id'     => 3,
                    'divisi'            => 5,
                    'modified_by'       => $this->session->userdata('user_id'),
                    'modified_date'     => date('Y-m-d H:i:s')
                );

                if($jumlah_proses == 0){
                    $data_permintaan_status['status'] = 4;
                }
                if($jumlah_proses > 0){
                    $data_permintaan_status['status'] = 6;
                }  

                $wheres_permintaan_status = array(
                    'transaksi_id' => $input['pemesanan_detail_id'],
                    'tipe_transaksi' => 1
                );    

                $edit_permintaan_status = $this->permintaan_status_m->update_by($user_id,$data_permintaan_status, $wheres_permintaan_status);

                $wheres_bayar_detail = array(
                    'transaksi_id'         => $input['pemesanan_detail_id'],
                    'tipe_pengajuan'       => 0,
                    'tipe'                 => 2,
                    'user_level_id'        => 3
                );
                $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

                $wheres_bayar_detail_before = array(
                    'transaksi_id'         => $input['pemesanan_detail_id'],
                    'tipe_pengajuan'       => 0,
                    'tipe'                 => 2,
                    '`order`'              => $pembayaran_status_detail_id->order - 1
                );

                $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

                if(count($pembayaran_status_detail_before) != 0){
                    $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                }else{
                    $waktu_proses = $pembayaran_status_id->created_date;
                }

                $datetime1 = new DateTime();
                $datetime2 = new DateTime($waktu_proses);
                $interval = $datetime1->diff($datetime2);
                $elapsed = $interval->format('%a d %h h %i m %S s');


                $data_permintaan_status_detail = array(
                    'user_proses'       => $user_id,
                    'tanggal_proses'    => date('Y-m-d H:i:s'),
                    'waktu_tunggu'      => $elapsed,
                    'keterangan'        => $array_input['keterangan'],
                    'modified_by'       => $this->session->userdata('user_id'),
                    'modified_date'     => date('Y-m-d H:i:s')
                );

                if($jumlah_proses == 0){
                    $data_permintaan_status_detail['status'] = 3;
                }
                if($jumlah_proses > 0){
                    $data_permintaan_status_detail['status'] = 2;
                }  

                $wheres_permintaan_status_detail = array(
                    'transaksi_id' => $input['pemesanan_detail_id'],
                    'tipe_transaksi' => 1,
                    'user_level_id' => 3
                );    

                $edit_permintaan_status_detail = $this->permintaan_status_detail_m->update_by($user_id,$data_permintaan_status_detail, $wheres_permintaan_status_detail);

            }

            
            $response->success = true;
            die(json_encode($response));

        }
    }
   

}

/* End of file branch.php */
/* Location: ./application/controllers/pembelian/daftar_permintaan_po.php */