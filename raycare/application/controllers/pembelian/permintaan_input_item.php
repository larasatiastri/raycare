<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_input_item extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '85578adf12ce6cf87958c753afe6eda3';                  // untuk check bit_access

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

        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_m');
        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_detail_m');
        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_detail_other_m');
        $this->load->model('pembelian/permintaan_po/persetujuan_permintaan_barang_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_permintaan_barang_history_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_detail_m');
        $this->load->model('pembelian/permintaan_po/user_level_persetujuan_m');
        $this->load->model('pembelian/permintaan_po/o_p_p_d_o_item_file_m');
        $this->load->model('pembelian/permintaan_po/box_paket_m');
        $this->load->model('pembelian/permintaan_po/box_paket_detail_m');
        $this->load->model('pembelian/permintaan_po/supplier_item_m');

        $this->load->model('pembelian/daftar_permintaan_po_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('pembelian/persetujuan_po/inventory_m');

        $this->load->model('master/cabang_m');
       
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('pembelian/item_sub_kategori_pembelian_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/item/item_spesifikasi_m');
        $this->load->model('master/item/item_spesifikasi_detail_m');
        $this->load->model('master/item/item_gambar_m');
        $this->load->model('master/item/item_identitas_m');
        $this->load->model('master/item/item_klaim_m');
        $this->load->model('master/item/item_pabrik_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/identitas_m');
        $this->load->model('master/identitas_detail_m');
        $this->load->model('master/pabrik_m');        
        

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/pembelian/permintaan_input_item/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Input Item', $this->session->userdata('language')), 
            'header'         => translate('Input Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pembelian/permintaan_input_item/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'item','add'))
        {
            $assets = array();
            $assets_config = 'assets/pembelian/permintaan_input_item/add';
            $this->config->load($assets_config, true);

            $assets = $this->config->item('assets', $assets_config);

            $data_item = $this->order_permintaan_barang_detail_other_m->get_by(array('id' => $id), true);

            $data = array(
                'title'        => config_item('site_name').' | '.translate("Tambah Item", $this->session->userdata("language")), 
                'header'       => translate("Tambah Item", $this->session->userdata("language")), 
                'header_info'  => config_item('site_name'), 
                'breadcrumb'   => TRUE,
                'menus'        => $this->menus,
                'menu_tree'    => $this->menu_tree,
                'css_files'    => $assets['css'],
                'js_files'     => $assets['js'],
                'data_item'    => object_to_array($data_item),
                'content_view' => 'pembelian/permintaan_input_item/add',
                'flag'         => 'add',
                'pk_value'     => $id,
            );

            // Load the view
            $this->load->view('_layout', $data);
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }


    public function edit($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'item','edit'))
        {
            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/pembelian/permintaan_input_item/edit';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            // $this->paket_m->set_columns($this->paket_m->fillable_edit());
           
            $form_data = $this->item_m->get($id);
            // die_dump($data_poliklinik_paket);

            $data = array(
                'title'        => config_item('site_name').' | '.translate("Edit Item", $this->session->userdata("language")), 
                'header'       => translate("Edit Item", $this->session->userdata("language")), 
                'header_info'  => config_item('site_name'), 
                'breadcrumb'   => TRUE,
                'menus'        => $this->menus,
                'menu_tree'    => $this->menu_tree,
                'css_files'    => $assets['css'],
                'js_files'     => $assets['js'],
                'content_view' => 'master/item/edit',
                'form_data'    => object_to_array($form_data),
                'pk_value'     => $id,                         //table primary key value
                'flag'         => 'edit',                         //table primary key value
            );

            // Load the view
            $this->load->view('_layout', $data);

        }else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }
    
    public function delete($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'item','delete'))
        {
            $data = array(
                'is_active'    => 0
            );
            // save data
            // $item_id = $this->item_m->save($data, $id);
            $path_model = 'master/item/item_m';
            $item_id = insert_data_api($data,base_url(),$path_model);
            $inserted_item_id = $item_id;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $item_id = insert_data_api($data,$cabang->url,$path_model,$inserted_item_id);
                }
            }
            $inserted_item_id = str_replace('"', '', $inserted_item_id);

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
                'tipe'            => 5,
                'data_id'         => $id,
                'created_by'      => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s')
            );

            $trash = $this->kotak_sampah_m->simpan($data_trash);
            // die_dump($this->db->last_query());

            if ($item_id) 
            {
                $flashdata = array(
                    "type"     => "error",
                    "msg"      => translate("Item Telah di Delete", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            redirect("master/item");
        }else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function listing()
    {        
        $result = $this->order_permintaan_barang_detail_other_m->get_datatable_index();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $jenis = '';

            if($row['jenis'] == 1){
                $jenis = 'Barang';
            }if($row['jenis'] == 2){
                $jenis = 'Jasa';
            }
                
            $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'pembelian/permintaan_input_item/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a>';

            $output['data'][] = array(
                $row['nama'],
                $row['satuan'],
                $jenis,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_harga_item_satuan($item_id)
    {        
        $result = $this->item_satuan_m->get_datatable_harga_item_satuan($item_id);
        // die_dump($this->db->last_query());
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
            $tanggal = '';
            if ($row['tanggal'] != "") {
                $tanggal = date('Y-m-d', strtotime($row['tanggal']));
            }else{
                $tanggal = '';
            }
            $output['data'][] = array(
                '<div class="text-center">'.$row['item_id'].'</div>',
                '<div class="text-center">'.$row['satuan_id'].'</div>',
                '<div class="text-center">'.$tanggal.'</div>',
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-right"><label name="harga_jual[]" id="harga_jual_'.$i.'">Rp. ' . number_format($row['harga'],0,'','.').',-</label></div>'
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_harga_item_satuan_modal($item_id)
    {        
        $result = $this->item_satuan_m->get_datatable_harga_item_satuan($item_id);
        //die_dump($result);
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
            $i++;
            $output['data'][] = array(
                '<div class="text-center">'.$row['item_id'].'</div>',
                '<div class="text-center">'.$row['satuan_id'].'</div>',
                '<div class="text-center">'.$row['tanggal'].'</div>',
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">
                <input type="number" min=0 class="form-control text-right" name="harga['.$i.'][harga]" id="harga_'.$i.'" value="0">
                <input type="hidden" name="harga['.$i.'][item_satuan_id]" class="form-control" id="satuan_id'.$i.'" value='.$row['satuan_id'].'>
                <input type="hidden" name="harga['.$i.'][item_id]" class="form-control" id="item_id_'.$i.'" value='.$row['item_id'].'>
                <input type="hidden" name="harga['.$i.'][harga_id]" class="form-control" id="item_id_'.$i.'" value="">
                </div>'
            );
         
        }

        echo json_encode($output);
    }

    public function listing_harga_item_satuan_modal_by_tanggal($item_id, $tanggal)
    {        
       
        $result_edit = $this->item_satuan_m->get_datatable_harga_item_satuan_by_tanggal($item_id, $tanggal);
        // die_dump($this->db->last_query());
        
        $result_add = $this->item_satuan_m->get_datatable_harga_item_satuan_modal($item_id);
        $output_edit = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result_edit->total_records,
            'iTotalDisplayRecords' => $result_edit->total_display_records,
            'data'                 => array()
        );

        $output_add = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result_add->total_records,
            'iTotalDisplayRecords' => $result_add->total_display_records,
            'data'                 => array()
        );
    
        $records_edit = $result_edit->records;
        $records_add = $result_add->records;
        $data_edit = $records_edit->result_array();
        // die_dump($this->db->last_query());
        if (empty($data_edit)) {
            $i=0;
            foreach($records_add->result_array() as $row)
            {   
                $i++;
                $output_add['data'][] = array(
                    '<div class="text-center">'.$row['item_id'].'</div>',
                    '<div class="text-center">'.$row['satuan_id'].'</div>',
                    '<div class="text-center">'.$row['tanggal'].'</div>',
                    '<div class="text-center">'.$row['nama'].'</div>',
                    '<div class="text-center">
                    <input type="number" min=0 class="form-control text-right" name="harga['.$i.'][harga]" id="harga_'.$i.'" value="0">
                    <input type="hidden" name="harga['.$i.'][item_satuan_id]" class="form-control" id="satuan_id'.$i.'" value='.$row['satuan_id'].'>
                    <input type="hidden" name="harga['.$i.'][item_id]" class="form-control" id="item_id_'.$i.'" value='.$row['item_id'].'>
                    <input type="hidden" name="harga['.$i.'][harga_id]" class="form-control" id="item_id_'.$i.'" value="">
                    </div>'
                );
             
            }
            echo json_encode($output_add);
        }
        else{
            $i=0;
            foreach($records_edit->result_array() as $row)
            {   
                $i++;
                $output_edit['data'][] = array(
                    '<div class="text-center">'.$row['item_id'].'</div>',
                    '<div class="text-center">'.$row['satuan_id'].'</div>',
                    '<div class="text-center">'.$row['tanggal'].'</div>',
                    '<div class="text-center">'.$row['nama'].'</div>',
                    '<div class="text-center">
                    <input type="number" min=0 class="form-control text-right" name="harga['.$i.'][harga]" id="harga_'.$i.'" value='.$row['harga'].'>
                    <input type="hidden" name="harga['.$i.'][item_satuan_id]" class="form-control" id="satuan_id'.$i.'" value='.$row['satuan_id'].'>
                    <input type="hidden" name="harga['.$i.'][item_id]" class="form-control" id="item_id_'.$i.'" value='.$row['item_id'].'>
                    <input type="hidden" name="harga['.$i.'][harga_id]" class="form-control" id="item_id_'.$i.'" value='.$row['harga_id'].'>
                    </div>'
                );
             
            }
            echo json_encode($output_edit);
        }
        

        
    }

    public function save()
    {   
        $cabang_id = $this->session->userdata('cabang_id');
        // die_dump($cabang_id);
        $array_input = $this->input->post();

        $data_cabang = $this->cabang_m->get_by('tipe in (0,1,2,3)');

        if ($array_input['command'] == "add") {

            //save item
            $initial_item = substr($array_input['nama'], 0,1);
            $last_number  = $this->item_m->get_no_kode($initial_item)->result_array();
            $last_number  = intval($last_number[0]['max_no_kode'])+1;
        
            $format       = 'I'.$initial_item.'%04d';
            $kode         = sprintf($format, $last_number, 3);

            $discontinue = "";
            if (isset($array_input['discontinue'])){
                $discontinue = "1";
            }else{
                $discontinue = "0";
            }

            $keepable = "";
            if (isset($array_input['keepable'])){
                $keepable = "1";
            }else{
                $keepable = "0";
            }

            $is_identitas = "";
            if (isset($array_input['is_identitas'])){
                $is_identitas = "1";
            }else{
                $is_identitas = "0";
            } 

            $is_sale = "";
            if (isset($array_input['is_sale'])){
                $is_sale = "1";
            }else{
                $is_sale = "0";
            }

            $show_assessment = "";
            if (isset($array_input['show_assessment'])){
                $show_assessment = "1";
            }else{
                $show_assessment = "0";
            }

            $identitas_byte = '';
            if($is_identitas == 1){
                $identitas_byte = '11000';
            }

            $data_item = array(
                'kode'               => $array_input['kode'],
                'item_sub_kategori'  => $array_input['sub_kategori'],
                'nama'               => $array_input['nama'],
                'keterangan'         => $array_input['keterangan'],
                'standar_simpan'     => 0,
                'is_discontinue'     => $discontinue,
                'is_keep'            => $keepable,
                'buffer_stok'        => $array_input['persediaan_minimum'],
                'is_sale'            => $is_sale,
                'is_active'          => '1',
                'is_identitas'       => $is_identitas,
                'identitas_byte'     => $identitas_byte,
                'is_show_assessment' => $show_assessment,
            );

            //die_dump($data_item);
            // $save_item = $this->item_m->save($data_item, $array_input['item_id']);
            $path_model = 'master/item/item_m';
            $save_item = insert_data_api($data_item,base_url(),$path_model,$array_input['item_id']);
            $inserted_save_item = $save_item;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_item = insert_data_api($data_item,$cabang->url,$path_model,$array_input['item_id']);
                    }
                    
                }
            }
            $inserted_save_item = str_replace('"', '', $inserted_save_item);

            $max_kode_permintaan_item = $this->order_permintaan_barang_detail_m->get_max_kode()->result_array();
            if(count($max_kode_permintaan_item) == 0)
            {
                $max_kode_permintaan_item = 1;
            }
            else
            {
                $max_kode_permintaan_item = intval($max_kode_permintaan_item[0]['max_kode']) + 1;
            }

            $format_item           = 'OPBI-'.date('m').'-'.date('Y').'-%04d';
            $no_permintaan_item    = sprintf($format_item, $max_kode_permintaan_item, 4);


            $data_detail_minta = array(
                'id'                         => $no_permintaan_item,
                'order_permintaan_barang_id' => $array_input['id_permintaan'],
                'item_id'                    => $array_input['item_id'],
                'item_satuan_id'             => $array_input['satuan_primary'],
                'jumlah'                     => $array_input['jumlah_minta'],
                'status'                     => 1,
                'is_selected'                => 1,
                'created_by'                 => $array_input['created_by'],
                'created_date'               => date('Y-m-d H:i:s'),
            );

            $order_permintaan_barang_detail = $this->order_permintaan_barang_detail_m->add_data($data_detail_minta);

            $data_minta_item_other = array(
                'order_permintaan_barang_detail_id' => $no_permintaan_item,
                'item_id'                           => $array_input['item_id'],
                'item_satuan_id'                    => $array_input['satuan_primary'],
            );
            $order_permintaan_barang_detail_other = $this->order_permintaan_barang_detail_other_m->edit_data($data_minta_item_other, $array_input['id_detail']);
            // //save primary satuan_item
            $data_satuan_primary = array(
                'is_primary' => '1',
            );
            $path_model = 'master/item/item_satuan_m';
            $save_satuan_primary = insert_data_api($data_satuan_primary,base_url(),$path_model,$array_input['satuan_primary']);
            $inserted_satuan_item = $save_satuan_primary;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_satuan_primary = insert_data_api($data_satuan_primary,$cabang->url,$path_model,$array_input['satuan_primary']);
                    }
                }
            }
            $inserted_satuan_item = str_replace('"', '', $inserted_satuan_item);

            $path_model = 'penjualan/o_s_pmsn_m';

            $cabang_jual = $this->cabang_m->get_by(array('id' => 2), true);
            $jumlah_os = 1;

            $id_no_osp = get_max_id_os_pesan($cabang_jual->url);
            $id_osp = $id_no_osp['id_osp'];

            $data_item_os = array(
                'id'                     => $id_osp,
                'tanggal'                => date('Y-m-d'),
                'pemesanan_detail_id'    => 0,
                'item_id'                => $array_input['item_id'],
                'item_satuan_id'         => $array_input['satuan_primary'],
                'jumlah'                 => $jumlah_os,
                'status'                 => 1,
                'created_by'             => $this->session->userdata('user_id'),
                'created_date'           => date('Y-m-d H:i:s')
            );
            
            // $save_os_pesan = $this->o_s_pmsn_m->add_data($data_item_os); 
            $save_os_pesan = insert_data_api_id($data_item_os,$cabang_jual->url,$path_model);
            // //save spesifikasi
            for ($i = 0; $i <= $array_input['total_dokumen']; $i++)
            {
                if (isset($array_input['dokumen_'.$i]) != "") 
                {
                    
                    
                    if ($array_input['tipe_'.$i] == "1" || $array_input['tipe_'.$i] == "2" || $array_input['tipe_'.$i] == "3" || $array_input['tipe_'.$i] == "4" || $array_input['tipe_'.$i] == "5"){
                        $data_item_spesifikasi = array
                        (
                            'item_id'        => $array_input['item_id'],
                            'spesifikasi_id' => $array_input['spesifikasi_id_'.$i],
                            'tipe'           => $array_input['tipe_'.$i],
                            'judul'          => $array_input['judul_'.$i],
                        );

                        // $save_item_spesifikasi = $this->item_spesifikasi_m->save($data_item_spesifikasi);

                        $path_model = 'master/item/item_spesifikasi_m';
                        $save_item_spesifikasi = insert_data_api($data_item_spesifikasi,base_url(),$path_model);
                        $inserted_item_spesifikasi = $save_item_spesifikasi;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active == 1)
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_item_spesifikasi = insert_data_api($data_item_spesifikasi,$cabang->url,$path_model,$inserted_item_spesifikasi);
                                }
                            }
                        }
                        $inserted_item_spesifikasi = str_replace('"', '', $inserted_item_spesifikasi);
                    
                        $data_item_spesifikasi_detail = array(
                            'item_spesifikasi_id' => $inserted_item_spesifikasi,
                            'value'               => $array_input['dokumen_'.$i],
                        );

                        // $save_item_spesifikasi_detail = $this->item_spesifikasi_detail_m->save($data_item_spesifikasi_detail);
                        $path_model = 'master/item/item_spesifikasi_detail_m';
                        $save_item_spesifikasi_detail = insert_data_api($data_item_spesifikasi_detail,base_url(),$path_model);
                        $inserted_item_spesifikasi_detail = $save_item_spesifikasi_detail;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active == 1)
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_item_spesifikasi_detail = insert_data_api($data_item_spesifikasi_detail,$cabang->url,$path_model,$inserted_item_spesifikasi_detail);
                                }
                            }
                        }
                        $inserted_item_spesifikasi_detail = str_replace('"', '', $inserted_item_spesifikasi_detail);

                    }
                    else if($array_input['tipe_'.$i] == "6" || $array_input['tipe_'.$i] == "7")
                    {
                        $data_item_spesifikasi = array
                        (
                            'item_id'        => $array_input['item_id'],
                            'spesifikasi_id' => $array_input['spesifikasi_id_'.$i],
                            'tipe'           => $array_input['tipe_'.$i],
                            'judul'          => $array_input['judul_'.$i],
                        );

                        // $save_item_spesifikasi = $this->item_spesifikasi_m->save($data_item_spesifikasi);
                        $path_model = 'master/item/item_spesifikasi_m';
                        $save_item_spesifikasi = insert_data_api($data_item_spesifikasi,base_url(),$path_model);
                        $inserted_item_spesifikasi = $save_item_spesifikasi;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active == 1)
                            { 
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_item_spesifikasi = insert_data_api($data_item_spesifikasi,$cabang->url,$path_model,$inserted_item_spesifikasi);
                                }
                            }
                        }
                        $inserted_item_spesifikasi = str_replace('"', '', $inserted_item_spesifikasi);
                    
                        foreach ($array_input['dokumen_'.$i] as $data) {
                            
                            $data_item_spesifikasi_detail = array(
                                'item_spesifikasi_id' => $inserted_item_spesifikasi,
                                'value'               => $data,
                            );

                            // $save_item_spesifikasi_detail = $this->item_spesifikasi_detail_m->save($data_item_spesifikasi_detail);
                            $path_model = 'master/item/item_spesifikasi_detail_m';
                            $save_item_spesifikasi_detail = insert_data_api($data_item_spesifikasi_detail,base_url(),$path_model);
                            $inserted_item_spesifikasi_detail = $save_item_spesifikasi_detail;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                { 
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $save_item_spesifikasi_detail = insert_data_api($data_item_spesifikasi_detail,$cabang->url,$path_model,$inserted_item_spesifikasi_detail);
                                    }
                                }
                            }
                            $inserted_item_spesifikasi_detail = str_replace('"', '', $inserted_item_spesifikasi_detail);
                        }
                    }
                    //die_dump($this->db->last_query());    
                }   
            }

            $level_id = $this->session->userdata('level_id');
            $user_id = $this->session->userdata('user_id');

            $wheres_status = array(
                'transaksi_id'   => $array_input['id_permintaan'],
                'tipe_transaksi' => 1
            );    

            $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);
            $permintaan_status_detail_id = $this->permintaan_status_detail_m->get_by($wheres_status);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['id_permintaan'],
                'tipe_transaksi' => 1,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                'user_level_id'  => $level_id
            );
            $permintaan_status_detail = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'transaksi_id'   => $array_input['id_permintaan'],
                'tipe_transaksi' => 1,
                'tipe_pengajuan' => 0,
                '`order`'        => $permintaan_status_detail->order - 1
            );

            $pembayaran_status_detail_before = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $permintaan_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 2,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'keterangan'     => 'Nama Item : '.$array_input['nama'],
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->permintaan_status_detail_m->edit_data($data_pembayaran_status_detail, $permintaan_status_detail->id);

            $get_sub_kategori = $this->item_sub_kategori_pembelian_m->get_by(array('item_sub_kategori_id' => $array_input['sub_kategori']));
            $sub_kategori = object_to_array($get_sub_kategori);
            
            // die_dump(count($get_sub_kategori));

            if(count($get_sub_kategori) != 0)
            {
                foreach ($sub_kategori as $kategori) 
                {
                   
                    $max_id = '';
                    $maksimum = $this->persetujuan_permintaan_barang_m->get_max()->row(0);

                    if(count($maksimum) == NULL)
                    {
                        $max_id = 1;
                    }
                    else 
                    {
                        $max_id = $maksimum->max_id;
                        $max_id = $max_id + 1;
                    }

                    $data_save = array(
                        'persetujuan_permintaan_barang_id'  => $max_id,
                        'order_permintaan_barang_id'        => $array_input['id_permintaan'],
                        'order_permintaan_barang_detail_id' => $no_permintaan_item,
                        'tipe_permintaan'                   => 1,
                        'user_level_id'                     => $kategori['user_level_id'],
                        '`order`'                           => $kategori['level_order'],
                        '`status`'                          => 1,
                        'created_by'                        => $this->session->userdata('user_id'),
                        'created_date'                      => date('Y-m-d H:i:s'),
                    );

                    $p_p_p_save = $this->persetujuan_permintaan_barang_m->add_data($data_save);

                    $user_level = $this->user_level_m->get($kategori['user_level_id']);

                    $wheres_status = array(
                        'transaksi_id'   => $array_input['id_permintaan'],
                        'tipe_transaksi' => 1
                    );    

                    $permintaan_status_detail_id = $this->permintaan_status_detail_m->get_by($wheres_status);

                    $last_id_status_detail       = $this->permintaan_status_detail_m->get_max_id_permintaan_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    
                    $format_id_status_detail     = 'OID-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'permintaan_status_id' => $permintaan_status_id->id,
                        'transaksi_id'    => $array_input['id_permintaan'],
                        'tipe_transaksi'  => 1,
                        '`order`'              => count($permintaan_status_detail_id)+1,
                        'divisi'               => $user_level->divisi_id,
                        'user_level_id'        => $kategori['user_level_id'],
                        'tipe'                 => 3,
                        'tipe_pengajuan'       => 0,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->permintaan_status_detail_m->add_data($data_status_detail);
                }
            }
            elseif(count($get_sub_kategori) == 0){

                $user_level = $this->user_level_m->get(3);
                $wheres_status = array(
                    'transaksi_id'   => $array_input['id_permintaan'],
                    'tipe_transaksi' => 1
                );    

                $permintaan_status_id = $this->permintaan_status_m->get_by($wheres_status, true);
                $permintaan_status_detail_id = $this->permintaan_status_detail_m->get_by($wheres_status);

                $last_id_status_detail       = $this->permintaan_status_detail_m->get_max_id_permintaan_detail()->result_array();
                $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                
                $format_id_status_detail     = 'OID-'.date('m').'-'.date('Y').'-%04d';
                $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                $data_status_detail = array(
                    'id'                   => $id_status_detail,
                    'permintaan_status_id' => $permintaan_status_id->id,
                    'transaksi_id'    => $array_input['id_permintaan'],
                    'tipe_transaksi'  => 1,
                    '`order`'              => count($permintaan_status_detail_id)+1,
                    'divisi'               => $user_level->divisi_id,
                    'user_level_id'        => 3,
                    'tipe'                 => 3,
                    'tipe_pengajuan'       => 0,
                    'is_active'            => 1,
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s')
                );

                $pembayaran_status_detail = $this->permintaan_status_detail_m->add_data($data_status_detail);
            }

            $level_id = $this->session->userdata('level_id');
            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['id_permintaan'],
                'tipe_transaksi' => 1,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                'user_level_id'  => $level_id
            );
            $permintaan_status_detail_now = $this->permintaan_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);


            $wheres_bayar_detail_after = array(
                'transaksi_id'   => $array_input['id_permintaan'],
                'tipe_transaksi' => 1,
                'tipe_pengajuan' => 0,
                '`order`'        => $permintaan_status_detail_now->order + 1
            );

            $pembayaran_status_detail_after = $this->permintaan_status_detail_m->get_by($wheres_bayar_detail_after, true);

            $data_status_after = array(
                'status'        => 3,
                'modified_by'   => $user_id,
                'modified_date' => date('Y-m-d H:i:s')
            );      

            if(count($pembayaran_status_detail_after) != 0){
                $data_status_after['status'] = 1;
                $data_status_after['user_level_id'] = $pembayaran_status_detail_after->user_level_id;
                $data_status_after['divisi'] = $pembayaran_status_detail_after->divisi;
            }

            $wheres_status = array(
                'transaksi_id'   => $array_input['id_permintaan'],
                'tipe_transaksi' => 1
            );    

            $update_status = $this->permintaan_status_m->update_by($user_id,$data_status_after,$wheres_status);  

            // //save gambar
            // if(isset($array_input['gambar'])){
            //     $gambar_index=1;
            //     foreach ($array_input['gambar'] as $gambar) 
            //     {
            //         if($gambar['url'] != ""){
            //             $path_dokumen        = './assets/mb/pages/master/item/images/'.strtolower(str_replace(' ', '_', $array_input['nama']));
            //             $path_dokumen_thumb  = './assets/mb/pages/master/item/images/'.strtolower(str_replace(' ', '_', $array_input['nama'])).'/medium';
            //             $path_dokumen_thumb2 = './assets/mb/pages/master/item/images/'.strtolower(str_replace(' ', '_', $array_input['nama'])).'/small';
            //             $path_temporary      = './assets/mb/var/temp';

            //             if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
            //             if (!is_dir($path_dokumen_thumb)){mkdir($path_dokumen_thumb, 0777, TRUE);}
            //             if (!is_dir($path_dokumen_thumb2)){mkdir($path_dokumen_thumb2, 0777, TRUE);}
                        
            //             $temp_filename = strtolower($gambar['url']);             
            //             $full_path_temp_filename = $path_temporary."/".$temp_filename;

                        
            //             $convtofile = new SplFileInfo($temp_filename);
            //             $extenstion = ".".$convtofile->getExtension();

            //             $new_filename = strtolower(str_replace(' ', '_', $array_input['nama'])).'_'.$gambar_index.$extenstion;

            //             $foto        = strtolower(str_replace(' ', '_', $array_input['nama'])).'/'.$new_filename;
            //             $medium_file = strtolower(str_replace(' ', '_', $array_input['nama'])).'/medium/'.$new_filename;
            //             $small_file  = strtolower(str_replace(' ', '_', $array_input['nama'])).'/small/'.$new_filename;

            //             copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'].config_item('site_img_item_temp_dir_copy').$foto);
            //             copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_thumb_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_item_temp_dir_copy').$medium_file);
            //             copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_thumb_small_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_item_temp_dir_copy').$small_file);
                        
            //             if ($gambar['url'] != "") {
                    
            //                 $data_gambar = array(
            //                     'item_id'    => $array_input['item_id'],
            //                     'gambar_url' => $new_filename,
            //                     'is_primary' => $gambar['is_primary_gambar'],
            //                     'is_active'  => '1',
            //                 );

            //                 // $save_gambar = $this->item_gambar_m->save($data_gambar); 
            //                 $path_model = 'master/item/item_gambar_m';
            //                 $save_item_gambar = insert_data_api($data_gambar,base_url(),$path_model);
            //                 $inserted_item_gambar = $save_item_gambar;

            //                 foreach ($data_cabang as $cabang) 
            //                 {
            //                     if($cabang->is_active == 1)
            //                     { 
            //                         if($cabang->url != '' || $cabang->url != NULL)
            //                         {
            //                             $save_item_gambar = insert_data_api($data_gambar,$cabang->url,$path_model,$inserted_item_gambar);
            //                         }
            //                     }
            //                 }

            //                 // die_dump($this->db->last_query());
            //             }
            //         $gambar_index++;
            //         }  
            //     }
                    
            // }

            // if($array_input['identitas']){
            //     // save identitas
            //     foreach ($array_input['identitas'] as $identitas) 
            //     {
            //         if ($identitas['identitas_id'] != '') 
            //         {
            //             $data_item_identitas = array(
            //                 'item_id'      => $array_input['item_id'],
            //                 'identitas_id' => $identitas['identitas_id'],
            //                 'is_active'    => '1'
            //             );
            //             // SAVE ITEM IDENTIAS
            //             // $save_item_identitas = $this->item_identitas_m->save($data_item_identitas);
            //             $path_model = 'master/item/item_identitas_m';
            //             $save_item_identitas = insert_data_api($data_item_identitas,base_url(),$path_model);
            //             $inserted_item_identitas = $save_item_identitas;

            //             foreach ($data_cabang as $cabang) 
            //             {
            //                 if($cabang->is_active == 1)
            //                 { 
            //                     if($cabang->url != '' || $cabang->url != NULL)
            //                     {
            //                         $save_item_identitas = insert_data_api($data_item_identitas,$cabang->url,$path_model,$inserted_item_identitas);
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }

            // // if($array_input['penjamin']){
            // //     //save item penjamin
            // //     foreach ($array_input['penjamin'] as $penjamin) 
            // //     {
            // //         $data_penjamin = array(
            // //             'item_id'     => $array_input['item_id'],
            // //             'cabang_id'   => $cabang_id,
            // //             'penjamin_id' => $penjamin,
            // //             'is_active'   => '1',
            // //         );

            // //         // $save_item_penjamin = $this->item_klaim_m->save($data_penjamin);
            // //         $path_model = 'master/item/item_klaim_m';
            // //         $save_identitas_klaim = insert_data_api($data_penjamin,base_url(),$path_model);
            // //         $inserted_item_klaim = $save_identitas_klaim;

            // //         foreach ($data_cabang as $cabang) 
            // //         {
            // //             if($cabang->is_active == 1)
            // //             { 
            // //                 if($cabang->url != '' || $cabang->url != NULL)
            // //                 {
            // //                     $save_identitas_klaim = insert_data_api($data_penjamin,$cabang->url,$path_model,$inserted_item_klaim);
            // //                 }
            // //             }
            // //         }
                          
            // //     }
            // // }

            // // foreach ($array_input['pabrik'] as $pabrik) 
            // // {
            // //     $data_pabrik = array(
            // //         'item_id'   => $array_input['item_id'],
            // //         'pabrik_id' => $pabrik,
            // //         'is_active' => '1',
            // //     );
                
            // //     // $save_item_pabrik = $this->item_pabrik_m->save($data_pabrik);
            // //     $path_model = 'master/item/item_pabrik_m';
            // //     $save_item_pabrik = insert_data_api($data_pabrik,base_url(),$path_model);
            // //     $inserted_item_pabrik = $save_item_pabrik;

            // //     foreach ($data_cabang as $cabang) 
            // //     {
            // //         if($cabang->is_active == 1)
            // //         { 
            // //             if($cabang->url != '' || $cabang->url != NULL)
            // //             {
            // //                 $save_item_pabrik = insert_data_api($data_pabrik,$cabang->url,$path_model,$inserted_item_pabrik);
            // //             }
            // //         }
            // //     }
            // // }

            

            if ($save_item ) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Item berhasil di Tambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
                $this->session->set_flashdata($flashdata);
            }
        }
        elseif ($array_input['command'] == "edit") 
        {
            $initial_item = substr($array_input['nama'], 0,1);
            $nomor_item = substr($array_input['kode'], 2,4);
            $cek_kode_number  = $this->item_m->cek_no_kode($initial_item)->result_array();
            $get_last_number  = $this->item_m->get_no_kode($initial_item)->result_array();
            $last_number  = intval($get_last_number[0]['max_no_kode'])+1;
        
            $format       = 'I'.$initial_item.'%04d';

            $discontinue = "";
            
            $kode = '';
            if ($array_input['kode'] == $cek_kode_number[0]['no_kode']) {
                $kode = $array_input['kode'];
            }
            else{
                $kode         = sprintf($format, $last_number, 3);
            }
            //----update item----
            $discontinue = "";
            if (isset($array_input['discontinue'])){
                $discontinue = "1";
            }else{
                $discontinue = "0";
            }

            $keepable = "";
            if (isset($array_input['keepable'])){
                $keepable = "1";
            }else{
                $keepable = "0";
            }

            $is_identitas = "";
            if (isset($array_input['is_identitas'])){
                $is_identitas = "1";
            }else{
                $is_identitas = "0";
            } 

            $is_sale = "";
            if (isset($array_input['is_sale'])){
                $is_sale = "1";
            }else{
                $is_sale = "0";
            }

            $show_assessment = "";
            if (isset($array_input['show_assessment'])){
                $show_assessment = "1";
            }else{
                $show_assessment = "0";
            }

            $data_item = array(
                'kode'               => $array_input['kode'],
                'item_sub_kategori'  => $array_input['sub_kategori'],
                'nama'               => $array_input['nama'],
                'keterangan'         => $array_input['keterangan'],
                'standar_simpan'     => $array_input['standar_simpan'],
                'is_discontinue'     => $discontinue,
                'is_keep'            => $keepable,
                'buffer_stok'        => $array_input['persediaan_minimum'],
                'is_sale'            => $is_sale,
                'is_identitas'       => $is_identitas,
                'is_show_assessment' => $show_assessment,
                'is_active'          => '1',
            );

            // die_dump($data_item);
            // $save_item = $this->item_m->save($data_item, $array_input['item_id']);
            $path_model = 'master/item/item_m';
            $save_item = insert_data_api($data_item,base_url(),$path_model,$array_input['item_id']);
            $inserted_save_item = $save_item;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_item = insert_data_api($data_item,$cabang->url,$path_model,$array_input['item_id']);
                    }
                    
                }
            }
            $inserted_save_item = str_replace('"', '', $inserted_save_item);

            //----update primary----
            $data_satuan_primary = array(
                'is_primary' => '0',
            );
            $wheres = array(
                'item_id' => $array_input['item_id']
            );

            $path_model = 'master/item/item_satuan_m';
            $save_satuan_primary = update_data_api($data_satuan_primary,base_url(),$path_model,$wheres);
            $inserted_satuan_item = $save_satuan_primary;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_satuan_primary = update_data_api($data_satuan_primary,$cabang->url,$path_model,$wheres);
                    }
                }
            }
            // $set_primary_to_0 = $this->item_satuan_m->set_primary_to_0($array_input['item_id']);
            $data_satuan_primary = array(
                'is_primary' => '1',
            );
            $path_model = 'master/item/item_satuan_m';
            $save_satuan_primary = insert_data_api($data_satuan_primary,base_url(),$path_model,$array_input['satuan_primary']);
            $inserted_satuan_item = $save_satuan_primary;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_satuan_primary = insert_data_api($data_satuan_primary,$cabang->url,$path_model,$array_input['satuan_primary']);
                    }
                }
            }
            // $save_satuan_primary = $this->item_satuan_m->save($data_satuan_primary, $array_input['satuan_primary']);
            // die_dump($array_input);
            for ($i = 0; $i <= $array_input['total_dokumen']; $i++)
            {
                if (isset($array_input['dokumen_'.$i]) != "") 
                {
                    
                    if ($array_input['tipe_'.$i] == "1" || $array_input['tipe_'.$i] == "2" || $array_input['tipe_'.$i] == "3" || $array_input['tipe_'.$i] == "4" || $array_input['tipe_'.$i] == "5"){
                    
                        $data_item_spesifikasi_detail = array(
                            'value'               => $array_input['dokumen_'.$i],
                        );

                        // $save_item_spesifikasi_detail = $this->item_spesifikasi_detail_m->save($data_item_spesifikasi_detail, $array_input['spesifikasi_detail_id_'.$i]);

                        $path_model = 'master/item/item_spesifikasi_detail_m';
                        $save_item_spesifikasi_detail = insert_data_api($data_item_spesifikasi_detail,base_url(),$path_model, $array_input['spesifikasi_detail_id_'.$i]);
                        $inserted_item_spesifikasi_detail = $save_item_spesifikasi_detail;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active == 1)
                            { 
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_item_spesifikasi_detail = insert_data_api($data_item_spesifikasi_detail,$cabang->url,$path_model, $array_input['spesifikasi_detail_id_'.$i]);
                                }
                            }
                        }
                        $inserted_item_spesifikasi_detail = str_replace('"', '', $inserted_item_spesifikasi_detail);     
                    }
                    else if($array_input['tipe_'.$i] == "6" || $array_input['tipe_'.$i] == "7")
                    {
                        
                        $spesifikasi_detail = $this->item_spesifikasi_m->get_data_spesifikasi_detail($array_input['spesifikasi_id_'.$i])->result_array();

                        foreach ($spesifikasi_detail as $spesifikasi_detail_row) 
                        {
                                      
                            $item_spesifikasi_detail = $this->item_spesifikasi_m->get_data_item_spesifikasi_by_val($array_input['item_id'], $array_input['spesifikasi_id_'.$i], $spesifikasi_detail_row['value']);
                            // die_dump($item_spesifikasi_detail);

                            $item_spesifikasi_detail_array = $item_spesifikasi_detail->result_array();
                            // die_dump($item_spesifikasi_detail_array);
                            if ($item_spesifikasi_detail->num_rows() == 0)
                            {
                                if(in_array($spesifikasi_detail_row['value'], $array_input['dokumen_'.$i]))
                                {
                                    $data_item_spesifikasi_detail = array(
                                        'item_spesifikasi_id'       => $array_input['spesifikasi_id_'.$i],
                                        'value'                     => $spesifikasi_detail_row['value'],
                                    );
                                    // $save_item_spesifikasi_detail = $this->item_spesifikasi_detail_m->save($data_item_spesifikasi_detail);
                                    $path_model = 'master/item/item_spesifikasi_detail_m';
                                    $save_item_spesifikasi_detail = insert_data_api($data_item_spesifikasi_detail,base_url(),$path_model);
                                    $inserted_item_spesifikasi_detail = $save_item_spesifikasi_detail;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->is_active == 1)
                                        { 
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $save_item_spesifikasi_detail = insert_data_api($data_item_spesifikasi_detail,$cabang->url,$path_model, $inserted_item_spesifikasi_detail);
                                            }
                                        }
                                    }
                                    $inserted_item_spesifikasi_detail = str_replace('"', '', $inserted_item_spesifikasi_detail);
                                    // die_dump($save_item_spesifikasi_detail);   
                                }     
                            }
                            else
                            {
                                
                                if (!in_array($spesifikasi_detail_row['value'], $array_input['dokumen_'.$i])) 
                                {
                                    $save_item_spesifikasi_detail = $this->item_spesifikasi_detail_m->delete($item_spesifikasi_detail_array[0]['spesifikasi_detail_id']);
                                }
                                
                            }   
                                                                                      
                        }
                    }
                    //die_dump($this->db->last_query());    
                }   
            }
            $gambar_index = 1;
            foreach ($array_input['gambar'] as $gambar) 
            {
                $path_dokumen        = './assets/mb/pages/master/item/images/'.strtolower(str_replace(' ', '_', $array_input['nama']));
                $path_dokumen_thumb  = './assets/mb/pages/master/item/images/'.strtolower(str_replace(' ', '_', $array_input['nama'])).'/medium';
                $path_dokumen_thumb2 = './assets/mb/pages/master/item/images/'.strtolower(str_replace(' ', '_', $array_input['nama'])).'/small';
                $path_temporary      = './assets/mb/var/temp';

                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
                if (!is_dir($path_dokumen_thumb)){mkdir($path_dokumen_thumb, 0777, TRUE);}
                if (!is_dir($path_dokumen_thumb2)){mkdir($path_dokumen_thumb2, 0777, TRUE);}
                
                
                
                if ($gambar['url'] != "" && $gambar['id'] != "") {
                    $temp_filename = strtolower($gambar['url']);             
                    $full_path_temp_filename = $path_temporary."/".$temp_filename;

                    
                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = strtolower(str_replace(' ', '_', $array_input['nama'])).'_'.$gambar_index.$extenstion;

                    $foto        = strtolower(str_replace(' ', '_', $array_input['nama'])).'/'.$new_filename;
                    $medium_file = strtolower(str_replace(' ', '_', $array_input['nama'])).'/medium/'.$new_filename;
                    $small_file  = strtolower(str_replace(' ', '_', $array_input['nama'])).'/small/'.$new_filename;

                    // die_dump($foto);

                    $cek_url_gambar = $this->item_gambar_m->get($gambar['id']);
                    $url_gambar = object_to_array($cek_url_gambar);

                    // die_dump($url_gambar['gambar_url']);

                    if ($url_gambar['gambar_url'] != $new_filename) {
                        copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'].config_item('site_img_item_temp_dir_copy').$foto);
                        copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_thumb_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_item_temp_dir_copy').$medium_file);
                        copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_thumb_small_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_item_temp_dir_copy').$small_file);
                    }
                    

                    $data_gambar = array(
                        'item_id'    => $array_input['item_id'],
                        'gambar_url' => $new_filename,
                        'is_primary' => $gambar['is_primary_gambar'],
                        'is_active'  => '1',
                    );
                    // die_dump($data_gambar);
                    // $save_gambar = $this->item_gambar_m->save($data_gambar, $gambar['id']); 
                    $path_model = 'master/item/item_gambar_m';
                    $save_item_gambar = insert_data_api($data_gambar,base_url(),$path_model,$gambar['id']);
                    $inserted_item_gambar = $save_item_gambar;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->is_active == 1)
                        { 
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_item_gambar = insert_data_api($data_gambar,$cabang->url,$path_model,$gambar['id']);
                            }
                        }
                    }

                }

                if ($gambar['url'] != "" && $gambar['id'] == "") {

                    $temp_filename = strtolower($gambar['url']);             
                    $full_path_temp_filename = $path_temporary."/".$temp_filename;

                    
                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = strtolower(str_replace(' ', '_', $array_input['nama'])).'_'.$gambar_index.$extenstion;

                    $foto        = strtolower(str_replace(' ', '_', $array_input['nama'])).'/'.$new_filename;
                    $medium_file = strtolower(str_replace(' ', '_', $array_input['nama'])).'/medium/'.$new_filename;
                    $small_file  = strtolower(str_replace(' ', '_', $array_input['nama'])).'/small/'.$new_filename;

                    // die_dump($foto);
                    copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'].config_item('site_img_item_temp_dir_copy').$foto);
                    copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_thumb_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_item_temp_dir_copy').$medium_file);
                    copy($_SERVER['DOCUMENT_ROOT'].config_item('site_img_temp_thumb_small_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_item_temp_dir_copy').$small_file);
                    
                    $data_gambar = array(
                        'item_id'    => $array_input['item_id'],
                        'gambar_url' => $foto,
                        'is_primary' => $gambar['is_primary_gambar'],
                        'is_active'  => '1',
                    );

                    // $save_gambar = $this->item_gambar_m->save($data_gambar); 
                    $path_model = 'master/item/item_gambar_m';
                    $save_item_gambar = insert_data_api($data_gambar,base_url(),$path_model);
                    $inserted_item_gambar = $save_item_gambar;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->is_active == 1)
                        { 
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_item_gambar = insert_data_api($data_gambar,$cabang->url,$path_model,$inserted_item_gambar);
                            }
                        }
                    }
                }

                if ($gambar['url'] != "" && $gambar['id'] != "" && $gambar['is_delete'] == "1") {

                    $delete_gambar = $this->item_gambar_m->delete($gambar['id']); 

                    // die_dump($this->db->last_query());
                }
            $gambar_index++;
                      
            }

            //update penjamin
            $penjamin = $this->item_klaim_m->data_penjamin($cabang_id)->result_array();
            // die_dump($penjamin);
            foreach ($penjamin as $penjamin_row) 
            {
                //cek apakah ada menu id
                $item_klaim = $this->item_klaim_m->get_data_item_penjamin($array_input['item_id'], $penjamin_row['cabang_id'], $penjamin_row['penjamin_id']);
                
                $item_klaim_array = $item_klaim->result_array();
                if ($item_klaim->num_rows() == 0)
                {
                    if(in_array($penjamin_row['id'], $array_input['penjamin']))
                    {
                        $data_penjamin = array(
                            'item_id'     => $array_input['item_id'],
                            'cabang_id'   => $penjamin_row['cabang_id'],
                            'penjamin_id' => $penjamin_row['penjamin_id'],
                            'is_active'   => '1',
                        );
                        // $save_item_penjamin = $this->item_klaim_m->save($data_penjamin);
                        $path_model = 'master/item/item_klaim_m';
                        $save_identitas_klaim = insert_data_api($data_penjamin,base_url(),$path_model);
                        $inserted_item_klaim = $save_identitas_klaim;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active == 1)
                            { 
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_identitas_klaim = insert_data_api($data_penjamin,$cabang->url,$path_model,$inserted_item_klaim);
                                }
                            }
                        }
                    }
                }
                else
                {
                    //die_dump(in_array($penyakit_row['id'], $array_input['penyakit_bawaan']));
                    if(in_array($penjamin_row['id'], $array_input['penjamin']))
                    {
                         //di select
                        if ($item_klaim_array[0]['is_active'] != 1)
                        {
                            //tidak aktif
                            //update IsActive jadi 1 dan modified nya di ubah
                            $data_penjamin = array(
                                "is_active" => '1',
                            );

                            // $save_item_penjamin = $this->item_klaim_m->save($data_penjamin, $item_klaim_array[0]['id']);
                            $path_model = 'master/item/item_klaim_m';
                            $save_identitas_klaim = insert_data_api($data_penjamin,base_url(),$path_model, $item_klaim_array[0]['id']);
                            $inserted_item_klaim = $save_identitas_klaim;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                { 
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $save_identitas_klaim = insert_data_api($data_penjamin,$cabang->url,$path_model,$item_klaim_array[0]['id']);
                                    }
                                }
                            }
                        }

                    }
                    else
                    {
                        //tidak di select
                        if ($item_klaim_array[0]['is_active'] == 1)
                        {
                            //tidak aktif
                            $data_penjamin = array(
                                "is_active" => '0',
                            );

                            $path_model = 'master/item/item_klaim_m';
                            $save_identitas_klaim = insert_data_api($data_penjamin,base_url(),$path_model, $item_klaim_array[0]['id']);
                            $inserted_item_klaim = $save_identitas_klaim;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                { 
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $save_identitas_klaim = insert_data_api($data_penjamin,$cabang->url,$path_model,$item_klaim_array[0]['id']);
                                    }
                                }
                            }
                        }
                    }

                }   
            }

            $get_pabrik = $this->pabrik_m->get_data_is_active()->result_array();
            // die_dump($pabrik);
            foreach ($get_pabrik as $pabrik) 
            {
                //cek apakah ada menu id
                $item_pabrik = $this->item_pabrik_m->get_data_item_pabrik($array_input['item_id'], $pabrik['id']);
                $item_pabrik_array = $item_pabrik->result_array();
                if ($item_pabrik->num_rows() == 0)
                {   
                    if(in_array($pabrik['id'], $array_input['pabrik']))
                    {
                        $data_pabrik = array(
                            'item_id'   => $array_input['item_id'],
                            'pabrik_id' => $pabrik['id'],
                            'is_active' => '1',
                        );
                        // $save_item_pabrik = $this->item_pabrik_m->save($data_pabrik);
                        $path_model = 'master/item/item_pabrik_m';
                        $save_item_pabrik = insert_data_api($data_pabrik,base_url(),$path_model);
                        $inserted_item_pabrik = $save_item_pabrik;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active == 1)
                            { 
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_item_pabrik = insert_data_api($data_pabrik,$cabang->url,$path_model,$inserted_item_pabrik);
                                }
                            }
                        }
                    }
                }
                else
                {
                    if(in_array($pabrik['id'], $array_input['pabrik']))
                    {
                        if ($item_pabrik_array[0]['is_active'] != 1)
                        {
                            $data_pabrik = array(
                                "is_active" => '1',
                            );

                            // $save_item_pabrik = $this->item_pabrik_m->save($data_pabrik, $item_pabrik_array[0]['id']);
                            $path_model = 'master/item/item_pabrik_m';
                            $save_item_pabrik = insert_data_api($data_pabrik,base_url(),$path_model,$item_pabrik_array[0]['id']);
                            $inserted_item_pabrik = $save_item_pabrik;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                { 
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $save_item_pabrik = insert_data_api($data_pabrik,$cabang->url,$item_pabrik_array[0]['id']);
                                    }
                                }
                            }
                        }

                    }
                    else
                    {
                        if ($item_pabrik_array[0]['is_active'] == 1)
                        {
                            $data_pabrik = array(
                                "is_active" => '0',
                            );

                            // $save_item_pabrik = $this->item_pabrik_m->save($data_pabrik, $item_pabrik_array[0]['id']);
                            $path_model = 'master/item/item_pabrik_m';
                            $save_item_pabrik = insert_data_api($data_pabrik,base_url(),$path_model,$item_pabrik_array[0]['id']);
                            $inserted_item_pabrik = $save_item_pabrik;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                { 
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $save_item_pabrik = insert_data_api($data_pabrik,$cabang->url,$item_pabrik_array[0]['id']);
                                    }
                                }
                            }
                        }
                    }

                }   
            }
            
            foreach ($array_input['identitas'] as $identitas) 
            {
                if ($identitas['id'] == '' && $identitas['identitas_id'] != '') 
                {
                    $data_item_identitas = array(
                        'item_id'      => $array_input['item_id'],
                        'identitas_id' => $identitas['identitas_id'],
                        'is_active'    => '1'
                    );
                    // SAVE ITEM IDENTIAS
                    // $save_item_identitas = $this->item_identitas_m->save($data_item_identitas);
                    $path_model = 'master/item/item_identitas_m';
                    $save_item_identitas = insert_data_api($data_item_identitas,base_url(),$path_model);
                    $inserted_item_identitas = $save_item_identitas;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->is_active == 1)
                        { 
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_item_identitas = insert_data_api($data_item_identitas,$cabang->url,$path_model,$inserted_item_identitas);
                            }
                        }
                    }
                }

                if ($identitas['identitas_id'] != '' && $identitas['id'] != '' && $identitas['is_active'] == 1) 
                {
                    $data_item_identitas = array(
                        'item_id'      => $array_input['item_id'],
                        'identitas_id' => $identitas['identitas_id'],
                        'is_active'    => '1'
                    );
                    // SAVE ITEM IDENTIAS
                    // $save_item_identitas = $this->item_identitas_m->save($data_item_identitas);
                    $path_model = 'master/item/item_identitas_m';
                    $save_item_identitas = insert_data_api($data_item_identitas,base_url(),$path_model,$identitas['id']);
                    $inserted_item_identitas = $save_item_identitas;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->is_active == 1)
                        { 
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_item_identitas = insert_data_api($data_item_identitas,$cabang->url,$path_model,$identitas['id']);
                            }
                        }
                    }
                }
                if ($identitas['identitas_id'] != '' && $identitas['id'] != '' && $identitas['is_active'] == 0) 
                {
                    $data_item_identitas = array(
                        'is_active'    => '0'
                    );
                    // SAVE ITEM IDENTIAS
                    // $save_item_identitas = $this->item_identitas_m->save($data_item_identitas);
                    $path_model = 'master/item/item_identitas_m';
                    $save_item_identitas = insert_data_api($data_item_identitas,base_url(),$path_model,$identitas['id']);
                    $inserted_item_identitas = $save_item_identitas;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->is_active == 1)
                        { 
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_item_identitas = insert_data_api($data_item_identitas,$cabang->url,$path_model,$identitas['id']);
                            }
                        }
                    }
                }
            }

            
        if ($save_item || $save_satuan_primary || $save_item_spesifikasi_detail || $save_gambar || $save_item_penjamin || $save_item_pabrik || $save_item_identitas || $save_identitas_detail) 
            {
                $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Item berhasil di Tambahkan", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
                $this->session->set_flashdata($flashdata);
            }
        }
        
        redirect("pembelian/permintaan_input_item");
    }


    public function save_item()
    {
        $array_input = $this->input->post();
        $data_cabang = $this->cabang_m->get_by('tipe in (0,1,2,3)');
        // die_dump($array_input);
        if ($array_input['command'] == "add") {

            $data_item = array(
                'item_sub_kategori' => $array_input['sub_kategori'],
                'nama'              => $array_input['nama'],
                'keterangan'        => $array_input['keterangan'],
                'is_sale'           => '1',
            );
            // $save_item = $this->item_m->save($data_item);

            $path_model = 'master/item/item_m';
            $save_item = insert_data_api($data_item,base_url(),$path_model);
            $inserted_save_item = $save_item;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_item = insert_data_api($data_item,$cabang->url,$path_model,$inserted_save_item);
                    }
                    
                }
            }
            $inserted_save_item = str_replace('"', '', $inserted_save_item);

            //die_dump($this->db->last_query());
            echo json_encode($inserted_save_item);
        }
        // redirect("master/item");
    }

    public function save_satuan()
    {
        $array_input = $this->input->post();
        $data_cabang = $this->cabang_m->get_by('tipe in (0,1,2,3)');
        // die_dump($array_input);
        if ($array_input['command'] == "add_parent") {

            $data_satuan = array(
                'item_id' => $array_input['item_id'],
                'nama'    => $array_input['satuan'],
                'jumlah'  => $array_input['jumlah'],
            );

            // $save_satuan = $this->item_satuan_m->save($data_satuan);
            $path_model = 'master/item/item_satuan_m';
            $save_item_satuan = insert_data_api($data_satuan,base_url(),$path_model);
            $inserted_save_item_satuan = $save_item_satuan;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_item_satuan = insert_data_api($data_satuan,$cabang->url,$path_model,$inserted_save_item_satuan);
                    }
                    
                }
            }
            $inserted_save_item_satuan = str_replace('"', '', $inserted_save_item_satuan);

            $data_satuan_harga = array(
                'cabang_id'      => $this->session->userdata('cabang_id'),
                'item_id'        => $array_input['item_id'],
                'item_satuan_id' => $inserted_save_item_satuan,
                'tanggal'        => date('Y-m-d'),
                'harga'          => '0'
            );
            $path_model = 'master/item/item_harga_m';
            $save_harga = insert_data_api($data_satuan_harga,base_url(),$path_model);
            $inserted_save_harga = $save_harga;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_harga = insert_data_api($data_satuan_harga,$cabang->url,$path_model, $inserted_save_harga);
                    }
                    
                }
            }
            $inserted_save_harga = str_replace('"', '', $inserted_save_harga); 

            //die_dump($this->db->last_query());
            echo json_encode($inserted_save_item_satuan);
        }
        elseif ($array_input['command'] == "add_child") {

            $data_satuan = array(
                'item_id'   => $array_input['item_id'],
                'nama'      => $array_input['satuan'],
                'jumlah'    => $array_input['jumlah'],
                'parent_id' => $array_input['parent'],
            );

            // $save_satuan = $this->item_satuan_m->save($data_satuan);
            $path_model = 'master/item/item_satuan_m';
            $save_item_satuan = insert_data_api($data_satuan,base_url(),$path_model);
            $inserted_save_item_satuan = $save_item_satuan;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_item_satuan = insert_data_api($data_satuan,$cabang->url,$path_model,$inserted_save_item_satuan);
                    }
                    
                }
            }
            $inserted_save_item_satuan = str_replace('"', '', $inserted_save_item_satuan);

            $data_satuan_harga = array(
                'cabang_id'      => $this->session->userdata('cabang_id'),
                'item_id'        => $array_input['item_id'],
                'item_satuan_id' => $inserted_save_item_satuan,
                'tanggal'        => date('Y-m-d'),
                'harga'          => '0'
            );
            $path_model = 'master/item/item_harga_m';
            $save_harga = insert_data_api($data_satuan_harga,base_url(),$path_model);
            $inserted_save_harga = $save_harga;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_harga = insert_data_api($data_satuan_harga,$cabang->url,$path_model, $inserted_save_harga);
                    }
                    
                }
            }
            $inserted_save_harga = str_replace('"', '', $inserted_save_harga); 

            echo json_encode($inserted_save_item_satuan);
        }
        elseif ($array_input['command'] == "edit_jumlah") {

            $data_satuan = array(
                'jumlah'      => $array_input['jumlah']
            );

            // $save_satuan = $this->item_satuan_m->save($data_satuan, $array_input['satuan_id']);
            $path_model = 'master/item/item_satuan_m';
            $save_item_satuan = insert_data_api($data_satuan,base_url(),$path_model, $array_input['satuan_id']);
            $inserted_save_item_satuan = $save_item_satuan;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_item_satuan = insert_data_api($data_satuan,$cabang->url,$path_model,$array_input['satuan_id']);
                    }
                    
                }
            }
            $inserted_save_item_satuan = str_replace('"', '', $inserted_save_item_satuan);

            //die_dump($this->db->last_query());
            echo json_encode($array_input['satuan_id']);
        }
        elseif ($array_input['command'] == "edit_satuan") {

            $data_satuan = array(
                'nama'      => $array_input['satuan']
            );
            // $save_satuan = $this->item_satuan_m->save($data_satuan, $array_input['satuan_id']);
            $path_model = 'master/item/item_satuan_m';
            $save_item_satuan = insert_data_api($data_satuan,base_url(),$path_model, $array_input['satuan_id']);
            $inserted_save_item_satuan = $save_item_satuan;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_item_satuan = insert_data_api($data_satuan,$cabang->url,$path_model,$array_input['satuan_id']);
                    }
                    
                }
            }
            $inserted_save_item_satuan = str_replace('"', '', $inserted_save_item_satuan);
            //die_dump($this->db->last_query());
            echo json_encode($array_input['satuan_id']);
        }
        // redirect("master/item");
    }

    public function save_satuan_harga()
    {
        if ($this->input->is_ajax_request()){
            $data_cabang = $this->cabang_m->get_by('tipe in (0,1,2,3)');
            $cabang_id = $this->session->userdata('cabang_id');
            $array_input = $this->input->post();
            // die_dump($array_input);
            $tanggal = date('Y-m-d', strtotime($array_input['tanggal']));

            // die_dump($tanggal);
            foreach ($array_input['harga'] as $harga) 
            {
                if ($harga['harga'] != 0 && $harga['harga_id'] == "") {
                    $data_harga = array(
                        'cabang_id'      => $cabang_id,
                        'harga'          => $harga['harga'],
                        'item_id'        => $harga['item_id'],
                        'item_satuan_id' => $harga['item_satuan_id'],
                        'tanggal'        => $tanggal,
                    );
                    // $save_harga = $this->item_harga_m->save($data_harga);
                    $path_model = 'master/item/item_harga_m';
                    $save_harga = insert_data_api($data_harga,base_url(),$path_model);
                    $inserted_save_harga = $save_harga;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->is_active == 1)
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_harga = insert_data_api($data_harga,$cabang->url,$path_model,$inserted_save_harga);
                            }
                            
                        }
                    }
                    $inserted_save_harga = str_replace('"', '', $inserted_save_harga); 
                }

                if ($harga['harga'] != 0 && $harga['harga_id'] != "") {
                    $data_harga = array(
                        'cabang_id'      => $cabang_id,
                        'harga'          => $harga['harga'],
                        'item_id'        => $harga['item_id'],
                        'item_satuan_id' => $harga['item_satuan_id'],
                        'tanggal'        => $tanggal,
                    );

                    // $save_harga = $this->item_harga_m->save($data_harga, $harga['harga_id']); 
                    $path_model = 'master/item/item_harga_m';
                    $save_harga = insert_data_api($data_harga,base_url(),$path_model, $harga['harga_id']);
                    $inserted_save_harga = $save_harga;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->is_active == 1)
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_harga = insert_data_api($data_harga,$cabang->url,$path_model, $harga['harga_id']);
                            }
                            
                        }
                    }
                    $inserted_save_harga = str_replace('"', '', $inserted_save_harga); 
                }                   
            }

            echo json_encode($inserted_save_harga);
        }
    }


    public function modal_harga_jual($flag)
    {

        $data = array(
            'flag'        => $flag
        );

        $this->load->view('pembelian/permintaan_input_item/modal/modal_harga_jual.php', $data);
    
    }

    public function delete_satuan()
    {
        $array_input = $this->input->post();
        

        $delete_satuan = $this->item_satuan_m->delete_satuan($array_input['item_id']);
        // die_dump($this->db->last_query());
        echo json_encode($delete_satuan);
        
    }
    

    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->paket_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Paket Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/paket");
    }

    public function formatrupiah($val) {
                    $hasil ='Rp.' . number_format($val, 0 , '' , '.' ) . ',-';
                    return $hasil;
    }

    public function get_harga_satuan(){

        $satuan_id = $this->input->post('satuan_id');
        $item_id = $this->input->post('item_id');
        $tanggal = $this->input->post('tanggal');
        $cabang_id = $this->session->userdata('cabang_id');
        
        $data = array('satuan_id' => $satuan_id, 'item_id' => $item_id);

        //die_dump($data);
        //$this->region_m->set_columns(array('id','nama'));
        $harga       = $this->item_harga_m->get_by(array('item_id' => $item_id, 'item_satuan_id' => $satuan_id , 'tanggal <=' => $tanggal, 'cabang_id' => $cabang_id));
        // die_dump($this->db->last_query());        
        $hasil_harga = object_to_array($harga);

        //die_dump($this->db->last_query());

        echo json_encode($hasil_harga);
    }

    public function get_tipe_akun(){

        $id_kategori = $this->input->post('id_kategori');
                
        //die_dump($id_kategori);
        //$this->region_m->set_columns(array('id','nama'));
        $tipe_akun       = $this->item_kategori_m->get_data_akun_tipe($id_kategori)->result_array();
        // die_dump($this->db->last_query());        
 
        $hasil_tipe_akun = object_to_array($tipe_akun);

        // die_dump($hasil_tipe_akun);
        //die_dump($this->db->last_query());

        echo json_encode($hasil_tipe_akun);
    }

    public function get_sub_kategori(){

        $id_kategori = $this->input->post('id_kategori');
                
        //die_dump($id_kategori);
        //$this->region_m->set_columns(array('id','nama'));
        $sub_kategori       = $this->item_sub_kategori_m->get_by(array('item_kategori_id' => $id_kategori));
        //die_dump($this->db->last_query());        
        $hasil_sub_kategori = object_to_array($sub_kategori);

        //die_dump($hasil_tipe_akun);
        //die_dump($this->db->last_query());

        echo json_encode($hasil_sub_kategori);
    }

    public function item_identitas()
    {
        
        $item_id = $this->input->post('item_id');
        
        // die_dump($item_id);
        // save data
        $result=$this->item_identitas_m->data_item_identitas($item_id)->result_array();

        // die_dump($this->db->last_query());
        echo json_encode($result);
    }

    public function item_identitas_detail()
    {
        
        $item_id = $this->input->post('item_id');
        $identitas_id = $this->input->post('identitas_id');
        
        // die_dump($item_id);
        // save data
        $result=$this->item_identitas_m->data_item_identitas_detail($item_id, $identitas_id)->result_array();

        // die_dump($this->db->last_query());
        echo json_encode($result);
    }

    public function show_spesifikasi()
    {
        $sub_kategori_id = $this->input->post('sub_kategori_id');
        // die_dump($sub_kategori_id);
        $item_sub_kategori_spesifikasi = $this->item_sub_kategori_m->get_data_kategori_spesifikasi($sub_kategori_id)->result_array();

        $data_item_sub_kategori_spesifikasi = object_to_array($item_sub_kategori_spesifikasi);
        // die_dump($this->db->last_query());
        $show_spesifikasi = "";
        $input      = "";
        $radio      = "";
        $checkbox   = "";
        $i          = 0;
        foreach ($data_item_sub_kategori_spesifikasi as $data) 
        {
            if ($data['tipe'] == 1)
            {
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" id="dokumen_'.$i.'" name="dokumen_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 2)
            {
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <textarea class="form-control" id="dokumen_'.$i.'" name="dokumen_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" rows="4"></textarea>
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 3) {
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <input type="number" min="1" class="form-control text-right" id="'.$data['judul'].'" name="dokumen_'.$i.'" value="1">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 4) 
            {
                // $judul = $data['judul'];
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 4)->result_array();
                $spesifikasi_detail_option = array(
                    '' => translate('Pilih..', $this->session->userdata('language'))
                );

                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $spesifikasi_detail_option[$data_spesifikasi['value']] = $data_spesifikasi['text'];
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            '.form_dropdown('dokumen_'.$i, $spesifikasi_detail_option, "", "id=\"dokumen_$i\" class=\"form-control\"").'
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }
            elseif ($data['tipe'] == 5)
            {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 5)->result_array();
                
                foreach ($spesifikasi_detail as $data_spesifikasi)
                {

                    $radio .= '<label class="radio-inline" style="padding-left:20px;">
                                <input type="radio" name="dokumen_'.$i.'" value="'.$data_spesifikasi['value'].'">'.$data_spesifikasi['text'].'
                              </label>';
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <div class="radio-list">
                                '.$radio.'
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                              </div>
                          </div>';
            }
            elseif ($data['tipe'] == 6)
            {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 6)->result_array();
                
                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $checkbox .= '<label class="checkbox-inline" style="padding-left:20px;">
                                    <input type="checkbox" name="dokumen_'.$i.'[]" value="'.$data_spesifikasi['value'].'">'.$data_spesifikasi['text'].'
                                  </label>';
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <div class="checkbox-list">
                                '.$checkbox.'
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                              </div>
                          </div>';
            }elseif ($data['tipe'] == 7) {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 7)->result_array();
                $spesifikasi_detail_option = array();

                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $spesifikasi_detail_option[$data_spesifikasi['value']] = $data_spesifikasi['text'];
                }

                $judul = $data['judul'];
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            '.form_dropdown("dokumen_".$i."[]", $spesifikasi_detail_option, "", "id=\"$judul\" class=\"multi-select\" multiple=\"multiple\"").'
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }
            $show_spesifikasi .= '<div class="form-group">'.$input.'</div>';
            $i++;
        }
            $show_spesifikasi .= '<div class="form-group">
                                <label class="control-label col-md-2 hidden">Total Dokumen</label>
                                <div class="col-md-3 hidden">
                                    <input type="text" name="total_dokumen" value="'.$i.'">
                                </div>
                            </div>
                            <script type="text/javascript" language="javascript">
                                $(".multi-select").multiSelect(); 
 
                            </script>               
                            ';
        //die_dump($show_claim);
        echo $show_spesifikasi;
    }

    public function show_edit_spesifikasi()
    {
        $sub_kategori_id = $this->input->post('sub_kategori_id');
        $item_id = $this->input->post('item_id');
        // die_dump($sub_kategori_id);
        $item_sub_kategori_spesifikasi = $this->item_sub_kategori_m->get_data_kategori_spesifikasi($sub_kategori_id)->result_array();

        $data_item_sub_kategori_spesifikasi = object_to_array($item_sub_kategori_spesifikasi);
        // die_dump($this->db->last_query());
        $show_spesifikasi      = "";
        $input                 = "";
        $radio                 = "";
        $checkbox              = "";
        $value                 = "";
        $spesifikasi_detail_id = "";
        $i                     = 0;
        foreach ($data_item_sub_kategori_spesifikasi as $data) 
        {
            $item_spesifikasi = $this->item_spesifikasi_m->get_data_item_spesifikasi($item_id, $data['spesifikasi_id'])->result_array();
            $data_item_spesifikasi = object_to_array($item_spesifikasi);
            // die_dump($this->db->last_query());
            if ($data['tipe'] == 1)
            {
                $value = $data_item_spesifikasi[0]['value'];
                $spesifikasi_detail_id = $data_item_spesifikasi[0]['spesifikasi_detail_id'];
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" id="dokumen_'.$i.'" name="dokumen_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$value.'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$spesifikasi_detail_id.'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 2)
            {
                $value = $data_item_spesifikasi[0]['value'];
                $spesifikasi_detail_id = $data_item_spesifikasi[0]['spesifikasi_detail_id'];

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <textarea class="form-control" id="dokumen_'.$i.'" name="dokumen_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" rows="4">'.$value.'</textarea>
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$spesifikasi_detail_id.'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 3) {
                $value = $data_item_spesifikasi[0]['value'];
                $spesifikasi_detail_id = $data_item_spesifikasi[0]['spesifikasi_detail_id'];

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <input type="number" min="1" class="form-control text-right" id="'.$data['judul'].'" name="dokumen_'.$i.'" value="'.$value.'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$spesifikasi_detail_id.'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 4) 
            {
                // $judul = $data['judul'];
                $value = $data_item_spesifikasi[0]['value'];
                $spesifikasi_detail_id = $data_item_spesifikasi[0]['spesifikasi_detail_id'];

                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 4)->result_array();
                $spesifikasi_detail_option = array(
                    '' => translate('Pilih..', $this->session->userdata('language'))
                );

                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $spesifikasi_detail_option[$data_spesifikasi['value']] = $data_spesifikasi['text'];
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            '.form_dropdown('dokumen_'.$i, $spesifikasi_detail_option, $value, "id=\"dokumen_$i\" class=\"form-control\"").'
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$spesifikasi_detail_id.'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }
            elseif ($data['tipe'] == 5)
            {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 5)->result_array();
                
                $spesifikasi_detail_id = $data_item_spesifikasi[0]['spesifikasi_detail_id'];
               
                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $check = "";
                    $found = false;
                    foreach ($data_item_spesifikasi as $item_spesifikasi_value) {
                        if ($data_spesifikasi['value'] == $item_spesifikasi_value['value']){
                           $found = true;
                        }          
                    }
                    

                    ($found == true) ? $check = 'checked' : $check = '';

                    $radio .= '<label class="radio-inline" style="padding-left:20px;">
                                <input type="radio" name="dokumen_'.$i.'" '.$check.' value="'.$data_spesifikasi['value'].'">'.$data_spesifikasi['text'].'
                              </label>';
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <div class="radio-list">
                                '.$radio.'
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$spesifikasi_detail_id.'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                              </div>
                          </div>';
            }
            elseif ($data['tipe'] == 6)
            {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 6)->result_array();
                
                $spesifikasi_detail_id = $data_item_spesifikasi[0]['spesifikasi_detail_id'];
                
                foreach ($spesifikasi_detail as $data_spesifikasi)
                {   
                    $check = "";
                    $found = false;
                    foreach ($data_item_spesifikasi as $item_spesifikasi_value) {
                       if ($data_spesifikasi['value'] == $item_spesifikasi_value['value']){
                           $found = true;
                        }          
                    }

                    ($found == true) ? $check = 'checked' : $check = '';

                    $checkbox .= '<label class="checkbox-inline" style="padding-left:20px;">
                                    <input type="checkbox" name="dokumen_'.$i.'[]" '.$check.' value="'.$data_spesifikasi['value'].'">'.$data_spesifikasi['text'].'
                                  </label>';
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <div class="checkbox-list">
                                '.$checkbox.'
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$spesifikasi_detail_id.'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                              </div>
                          </div>';
            }elseif ($data['tipe'] == 7) {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 7)->result_array();
                $spesifikasi_detail_option = array();

                $spesifikasi_detail_id = $data_item_spesifikasi[0]['spesifikasi_detail_id'];

                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $spesifikasi_detail_option[$data_spesifikasi['value']] = $data_spesifikasi['text'];
                }

                $selected = array();
                foreach ($data_item_spesifikasi as $item_spesifikasi_value) {
                    $selected[$item_spesifikasi_value['value']] = $item_spesifikasi_value['value'];
                }
                $judul = $data['judul'];
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            '.form_dropdown("dokumen_".$i."[]", $spesifikasi_detail_option, $selected, "id=\"$judul\" class=\"multi-select\" multiple=\"multiple\"").'
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['spesifikasi_id'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="spesifikasi_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$spesifikasi_detail_id.'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }
            $show_spesifikasi .= '<div class="form-group">'.$input.'</div>';
            
            
            $i++;
        }
            $show_spesifikasi .= '<div class="form-group">
                                <label class="control-label col-md-2 hidden">Total Dokumen</label>
                                <div class="col-md-3 hidden">
                                    <input type="text" name="total_dokumen" value="'.$i.'">
                                </div>
                            </div>
                            <script type="text/javascript" language="javascript">
                                $(".multi-select").multiSelect(); 
 
                            </script>               
                            ';
        //die_dump($show_claim);
        echo $show_spesifikasi;
    }

    public function lihat_gambar($id_order_detail)
    {
        $data_img = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $id_order_detail, 'tipe' => 2 ));

        $data_order_detail = $this->order_permintaan_barang_detail_other_m->get_by(array('id' => $id_order_detail),true);
        $data = array(        
            'data_img'          => ($data_img)?object_to_array($data_img):'',
            'data_order_detail' => object_to_array($data_order_detail)
        );

        $this->load->view('pembelian/persetujuan_permintaan_po/unggah_gambar', $data);
    }
}

/* End of file permintaan_po.php */
/* Location: ./application/controllers/pembelian/permintaan_po.php */