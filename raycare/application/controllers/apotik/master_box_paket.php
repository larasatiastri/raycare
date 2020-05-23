<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Master_box_paket extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '72d17a72e391bac2d5999855ceb0714c';                  // untuk check bit_access

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

       
        $this->load->model('apotik/box_paket/box_paket_m');
        $this->load->model('apotik/box_paket/box_paket_detail_m');

        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_harga_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/master_box_paket/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Setting Master Box Paket', $this->session->userdata('language')), 
            'header'         => translate('Setting Master Box Paket', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/master_box_paket/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/apotik/master_box_paket/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master Box Paket', $this->session->userdata('language')), 
            'header'         => translate('Master Box Paket', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/master_box_paket/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    
    
    public function view($id)
    {
        $assets = array();
        $config = 'assets/apotik/master_box_paket/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $form_data = $this->box_paket_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->box_paket_detail_m->get_data($id)->result_array();

        $data = array(
            'title'            => 'RayCare | '.translate('View Master Box Paket', $this->session->userdata('language')), 
            'header'           => translate('View Master Box Paket', $this->session->userdata('language')), 
            'header_info'      => 'RayCare', 
            'breadcrumb'       => true,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            'content_view'     => 'apotik/master_box_paket/view',
            'pk_value'         => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

   

    public function listing_item()
    {
        $tanggal = date('Y-m-d');

        $result = $this->item_m->get_datatable_item_box();
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

        if($array_input['command'] === 'add'){

            $biaya_total = 0;
            foreach ($array_input['item'] as $item) {
                if($item['item_id'] != '')
                {

                    $biaya_total = $biaya_total + (intval($item['harga']) * $item['qty']);
                }
            }
            
            $tipe_transaksi = 1;
            $biaya_tunai = $biaya_total;
            
            $last_id       = $this->box_paket_m->get_max_id_box_paket()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'BP-'.date('m').date('y').'-%03d';
            $id_box_paket         = sprintf($format_id, $last_id, 3);
            

            $data_box_paket = array(
                'id'           => $id_box_paket,
                'nama'         => $array_input['nama_paket'],
                'tipe'         => $array_input['tipe_paket'],
                'harga'           => $biaya_total,
                'is_active'           => 1,
                'created_by'   => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s'),
            );

            $box_paket = $this->box_paket_m->add_data($data_box_paket);

            foreach ($items as $key => $item) {
               // for($x=1;$x<=$item['qty'];$x++){
                    $last_id_detail       = $this->box_paket_detail_m->get_max_id_box_paket_detail()->result_array();
                    $last_id_detail       = intval($last_id_detail[0]['max_id'])+1;
                    
                    $format_id_detail     = 'BPD-'.date('m').date('y').'-%03d';
                    $id_box_paket_detail         = sprintf($format_id_detail, $last_id_detail, 3);

                    $data_box_paket_detail = array(
                        'id'                => $id_box_paket_detail,
                        'box_paket_id'      => $id_box_paket,
                        'item_id'           => $item['item_id'],
                        'item_satuan_id'    => $item['satuan_id'],
                        'jumlah'            => $item['qty'],
                        'is_active'         => 1,
                        'created_by'        => $this->session->userdata('user_id'),
                        'created_date'      => date('Y-m-d H:i:s'),
                    );

                    $box_paket_detail = $this->box_paket_detail_m->add_data($data_box_paket_detail);
                //}
            }
        }
        $flashdata = array(
            "type"     => "success",
            "msg"      => translate("Data Box Paket berhasil ditambahkan.", $this->session->userdata("language")),
            "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
        redirect('apotik/master_box_paket');
    }

    public function listing()
    {
        
        $result = $this->box_paket_m->get_datatable();

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
            $box_paket_detail = $this->box_paket_detail_m->get_by(array('box_paket_id' => $row['id']));
            $data_array = object_to_array($box_paket_detail);

            $item = $this->item_m->get_item_master_box_paket($data_array[0]['box_paket_id'])->result_array();
            $jumlah = count($data_array);

            $info = '<a title="'.translate('Daftar Item', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="btn btn-info pilih-item" data-id="'.$row['id'].'" name="info" style="color:#33348e; text-decoration: none;"><u>'.$jumlah.' Item</u></a>';

           

            $output['aaData'][] = array(
                '<div class="text-left inline-button-table">'.$i.'</div>' ,
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga']).'</div>' ,
                '<div class="text-left">'.$info.'</div>',
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
            $t_box_paket_detail = $this->t_box_paket_detail_m->get_by(array('box_paket_id' => $row['id']));
            $data_array = object_to_array($t_box_paket_detail);

            $item = $this->item_m->get_item_box_paket($data_array[0]['box_paket_id'])->result_array();
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
