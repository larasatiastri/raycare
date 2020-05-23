<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '33ff08c31fc5473f4cb109cf923b689b';                  // untuk check bit_access

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

        $this->load->model('master/supplier/supplier_m');
        $this->load->model('master/supplier/supplier_telepon_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_email_m');
        $this->load->model('master/supplier/supplier_item_m');
        $this->load->model('master/supplier/supplier_harga_item_m');
        $this->load->model('master/supplier/supplier_tipe_pembayaran_m');
        $this->load->model('master/supplier/supplier_bank_m');
        $this->load->model('master/supplier/akun_m');
        $this->load->model('master/supplier/item_m');
        $this->load->model('master/supplier/item_satuan_m');
        $this->load->model('master/supplier/item_harga_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/region_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/master_tipe_bayar_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/supplier/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Supplier', $this->session->userdata('language')), 
            'header'         => translate('Supplier', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/supplier/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_supplier','add'))
        {
            $assets = array();
            $config = 'assets/master/supplier/add';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            $data = array(
                'title'          => config_item('site_name').' | '.translate("Add Supplier", $this->session->userdata("language")), 
                'header'         => translate("Add Supplier", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/supplier/add',
            );

            // Load the view
            $this->load->view('_layout', $data);
        }else{
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
        if(restriction_function($this->session->userdata('level_id'), 'master_supplier','edit'))
        {
            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/master/supplier/edit';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            $supplier_data = $this->supplier_m->get($id);

            $data = array(
                'title'          => config_item('site_name').' | '.translate("Edit Supplier", $this->session->userdata("language")), 
                'header'         => translate("Edit Supplier", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/supplier/edit',
                'form_data'      => object_to_array($supplier_data),
                'pk_value'       => $id,
            );

            // Load the view
            $this->load->view('_layout', $data);
        }else{
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function view($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_supplier','view'))
        {
            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/master/supplier/view';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            $supplier_data = $this->supplier_m->get($id);

            $data = array(
                'title'          => config_item('site_name').' | '.translate("View Supplier", $this->session->userdata("language")), 
                'header'         => translate("View Supplier", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/supplier/view',
                'form_data'      => object_to_array($supplier_data),
                'pk_value'       => $id,
            );

            // Load the view
            $this->load->view('_layout', $data);
        }else{
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function supplier_item($supplier_id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_supplier','supplier_item'))
        {
            $assets = array();
            $config = 'assets/master/supplier/supplier_item';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            $data = array(
                'title'          => config_item('site_name').' | '.translate("Supplier Item", $this->session->userdata("language")), 
                'header'         => translate("Supplier Item", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/supplier/supplier_item',
                'supplier_id'   => $supplier_id,
            );

            // Load the view
            $this->load->view('_layout', $data);
        }else{
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }


    public function delete_supplier_item($suppliers_item_id, $supplier_id)
    {

    
        $data = array(
            'is_active'    => 0
        );

        // save data
        $save_supplier_item = $this->supplier_item_m->save($data, $suppliers_item_id);
        
        if ($save_supplier_item) 
        {
            

            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Item Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        //redirect($this->uri->uri_string());
        //  $id2=$id;
        // die(dump($id2));
       redirect('master/supplier/supplier_item/'.$supplier_id);
    }

    public function restore_supplier_item($suppliers_item_id, $supplier_id)
    {

    
        $data = array(
            'is_active'    => 1
        );

        // save data
        $save_supplier_item = $this->supplier_item_m->save($data, $suppliers_item_id);
        
        if ($save_supplier_item) 
        {
            

            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Item Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        //redirect($this->uri->uri_string());
        //  $id2=$id;
        // die(dump($id2));
       redirect('master/supplier/supplier_item/'.$supplier_id);
    }

    
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        
        $result = $this->supplier_m->get_datatable();
        // die_dump($this->db->last_query());
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
           
            $action = '
                <a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/supplier/view/'.$row['id'].'" class="btn grey-cascade  hidden"><i class="fa fa-search"></i></a>
                <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/supplier/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';

            $data_supplier_item = '<a title="'.translate('Supplier Item', $this->session->userdata('language')).'" href="'.base_url().'master/supplier/supplier_item/'.$row['id'].'" class="btn blue-madison"><i class="fa fa-briefcase"></i></a>';

            $action .= restriction_button($data_supplier_item,$user_level_id,'master_supplier','supplier_item');

            $action .= '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data supplier ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
            
            $rate = '';

            if($row['rating'] == 0.0)
            {
                $rate = '<i class="fa fa-star-o"></i>';
            }

            else if($row['rating'] > 0.0)
            {
                    for($x=1;$x<=intval($row['rating'] / 2);$x++)
                 {
                     $rate .= '<i class="fa fa-star"></i>';
                 }
            }

            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div>'.$row['nama'].'</div>',
                '<div class="text-left font-yellow">'.$rate.'</div>',
                '<div>'.$row['orang_yang_bersangkutan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_detail($param = null)
    {        
        $param  = str_replace("%2C",",", $param);
        $result = $this->item_m->get_datatable($param);
        // die_dump($this->db->last_query());
        
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=1;

        $action = '';
        

        foreach($records->result_array() as $row) {
            
            $checkbox          = '<input class="checkboxes check-item" value="'.$row['id'].'" name="checkbox_pembelian[]" id="checkbox_item_detail" type="checkbox" data-row_id="'.$i.'">';
            $input_item_id     = '<input type="hidden" class="form-control" id="item_id_'.$i.'" name="items_berdasarkan_detail['.$i.'][item_id]" value="'.$row['id'].'">';    
            $input_is_selected = '<input type="hidden" class="form-control" id="is_selected_'.$i.'" name="items_berdasarkan_detail['.$i.'][is_selected]">';    
            
            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.$row['kode'].$input_item_id.$input_is_selected.'</div>',
                '<div>'.$row['nama'].'</div>',
                '<div>'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$checkbox.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_supplier_item($supplier_id)
    {        
        $result = $this->supplier_item_m->get_datatable($supplier_id);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;

        $action = '';
        

        foreach($records->result_array() as $row)
        {
           
            $action    = '';
            $status    = '';
            $is_supply = '';

            if($row['is_supply'] == 1){
                $status='<div class="text-center"><span class="label label-success">Supply</span></div>';
            }else{
                $status='<div class="text-center"><span class="label bg-grey-cascade">Not Supply</span></div>';
            }

            if($row['is_supply'] == 1)
            {
                $is_supply='<a title="'.translate('Not Supply', $this->session->userdata('language')).'"   name="not_supply[]" id="not" data-action="not"  data-id="'.$row['id'].'" data-confirm="'.translate("Apakah anda yakin untuk tidak memasok item ini ?", $this->session->userdata("language")).'" class="btn grey-cascade"><i class="fa fa-share"></i></a>';
            }else{   
                $is_supply='<a title="'.translate('Supply', $this->session->userdata('language')).'"   name="supply[]" id="set" data-action="set"  data-id="'.$row['id'].'" data-confirm="'.translate("Apakah anda yakin untuk memasok item ini ?", $this->session->userdata("language")).'" class="btn btn-success"><i class="fa fa-share"></i></a>';
            }

            if($row['is_harga_flexible'] == 1)
            {
                $is_harga_flexible='<a title="'.translate('Set Harga Tetap', $this->session->userdata('language')).'" name="not_flexible[]" id="not_flexible" data-action="not_flexible"  data-id="'.$row['id'].'" data-confirm="'.translate("Harga item jarang berubah?", $this->session->userdata("language")).'" class="btn grey-cascade"><i class="fa fa-bar-chart-o"></i></a>';
                $status .='<div class="text-center"><span class="label label-success">Flexible</span></div>';
            }else{   
                $is_harga_flexible='<a title="'.translate('Set Harga Flexible', $this->session->userdata('language')).'"  name="flexible[]" id="flexible" data-action="flexible"  data-id="'.$row['id'].'" data-confirm="'.translate("Harga item sering berubah?", $this->session->userdata("language")).'" class="btn btn-success"><i class="fa fa-bar-chart-o"></i></a>';
                $status .='<div class="text-center"><span class="label bg-grey-cascade">Harga Tetap</span></div>';
            }
            if($row['is_pph'] == 1)
            {
                $is_pph ='<a title="'.translate('Item Non Jasa', $this->session->userdata('language')).'" name="not_pph[]" id="not_pph" data-action="not_pph"  data-id="'.$row['id'].'" data-confirm="'.translate("Bukan item jasa?", $this->session->userdata("language")).'" class="btn grey-cascade"><i class="fa fa-file"></i></a>';
                $status .='<div class="text-center"><span class="label label-success">Jasa</span></div>';
            }else{   
                $is_pph ='<a title="'.translate('Item Jasa', $this->session->userdata('language')).'"  name="pph[]" id="pph" data-action="pph"  data-id="'.$row['id'].'" data-confirm="'.translate("Termasuk item jasa?", $this->session->userdata("language")).'" class="btn btn-success"><i class="fa fa-file"></i></a>';
                $status .='<div class="text-center"><span class="label bg-grey-cascade">Non Jasa</span></div>';
            }
            
            if($row['is_active']==1)
            {
                $action = '<a title="'.translate('Harga Baru', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_modal_harga" href="'.base_url().'master/supplier/modal_harga/'.$row['supplier_harga_item_id'].'"  class="btn btn-primary"><i class="fa fa-dollar"></i></a>
                          '.$is_supply.$is_harga_flexible.$is_pph.'<a title="'.translate('Delete', $this->session->userdata('language')).'"  name="delete[]" id="delete" data-action="delete" data-id="'.$row['id'].'" data-confirm="'.translate("Apakah anda yakin untuk menghapus item ini ?", $this->session->userdata("language")).'" class="btn red-intense"><i class="fa fa-times"></i></a>';
            }
            else
            {
                $action = '<a title="'.translate('Restore', $this->session->userdata('language')).'" name="restore[]" data-action="restore" data-id="'.$row['id'].'" data-confirm="'.translate("Apakah anda yakin untuk mengembalikan item ini ?", $this->session->userdata("language")).'" class="btn btn-xs yellow"><i class="fa fa-undo"></i> </a>';
            }


            $harga_item = 0;
            $get_harga = $this->item_harga_m->get_harga_item($row['item_id'], $row['item_satuan_id'])->result_array();
            if(count($get_harga))
            {
                $harga_item = $get_harga[0]['harga'];
            }
            
            $minimum_order = intval($row['minimum_order']);
            $kelipatan_order = intval($row['kelipatan_order']);
            
            $harga = 'Rp. '.number_format($row['harga'], 0 , '' , '.' ).',-';
            $het_pusat = 'Rp. '.number_format($harga_item, 0 , '' , '.' ).',-';

            $presentasi = (intval($harga_item) == 0)?0:(intval($row['harga'])/intval($harga_item))*100;
            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['item_kode'].'</div>',
                '<div class="text-left">'.$row['item_nama'].'</div>',
                '<div class="text-left">'.$row['satuan_nama'].'</div>',
                '<div class="text-center">'.$minimum_order.'/'.$kelipatan_order.'</div>',
                '<div class="text-right">'.$harga.'</div>',
                '<div class="text-right">'.$het_pusat.'</div>',
                '<div class="text-right">'.$presentasi.' %</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_efektif'])).'</div>',
                '<div>'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }
    public function listing_alamat()
    {
        $result = $this->info_alamat_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
      

        foreach($records->result_array() as $row)
        {             
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-confirm="'.translate('Pilih alamat ini?', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select_alamat"><i class="fa fa-check"></i> </a>';
            
            $output['data'][] = array(
                '<div class="text-left">'.$row['kelurahan'].'</div>' ,
                '<div class="text-left">'.$row['kecamatan'].'</div>' ,
                '<div class="text-left">'.$row['kotkab'].'</div>',
                '<div class="text-left">'.$row['propinsi'].'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
        }

        echo json_encode($output);
    }

    public function modal_search_alamat($index)
    {
       $data = array(
            'index' => $index
        );
       $this->load->view('master/supplier/modal/search_alamat', $data);
    }

    public function save()
    {
        $array_input = $this->input->post();
        // die(dump($array_input));
 
        if ($array_input['command'] === 'add')
        {             
            $nama = '';
            $perusahaan = strtolower($array_input['nama']);
            
            if (preg_match("/^pt./", $perusahaan)){
                $nama_potong = explode('pt.', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^pt[\s]/", $perusahaan)) {
                $nama_potong = explode('pt ', $perusahaan);
                $nama = $nama_potong[1];

            }

            if (preg_match("/^pt.[\s]/", $perusahaan)) {
                // $nama_potong = ltrim($perusahaan, 'pt. ');
                $nama_potong = explode('pt. ', $perusahaan);
                $nama = $nama_potong[1];
                // die_dump($nama_potong[1]);
            }

            if (preg_match("/^cv./", $perusahaan)){
                $nama_potong = explode('cv.', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^cv[\s]/", $perusahaan)) {
                $nama_potong = explode('cv ', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^cv.[\s]/", $perusahaan)) {
                $nama_potong = explode('cv. ', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^perum/", $perusahaan)) {
                $nama_potong = explode('perum ', $perusahaan);
                $nama = $nama_potong[1];
            }
            

            // die_dump($nama);
            $inisial_perusahaan = strtoupper($nama[0]);

            $last_no_akun = $this->akun_m->get_no_akun()->result_array();
            $last_no_akun = intval($last_no_akun[0]['max_no_akun'])+1;
            
            $format       = '2000.'.'%02d';
            $no_akun      = sprintf($format, $last_no_akun, 2);
            
            $last_number  = $this->supplier_m->get_no_kode($inisial_perusahaan)->result_array();
            $last_number  = intval($last_number[0]['max_kode'])+1;
            
            $format       = $inisial_perusahaan.'%03d';
            $kode         = sprintf($format, $last_number, 3);

            // die_dump($kode);
            $data_akun = array(
                'akun_kategori_id' => '7',
                'akun_tipe'        => '1',
                'parent'           => '16',
                'nama'             => 'Hutang Supplier '.$kode,
                'no_akun'          => $no_akun,
                'is_selectable'    => '1',
                'is_active'        => '1',
            );

            // $save_akun = $this->akun_m->save($data_akun);

            // $delete_akun = $this->akun_m->delete($save_akun-1);

            $obat = (isset($array_input['obat']))?$array_input['obat']:'0';
            $alkes = (isset($array_input['alkes']))?$array_input['alkes']:'0';
            $lain = (isset($array_input['lain']))?$array_input['lain']:'0';

            $jenis_barang = $obat.'-'.$alkes.'-'.$lain;

            $data_supplier = array(
                'tipe'                       => $array_input['tipe_supplier'],
                'tipe_barang'                => $jenis_barang,
                'kode'                       => $array_input['kode'],
                'nama'                       => $array_input['nama'],
                'is_pkp'                     => $array_input['is_pkp'],
                'npwp'                       => $array_input['npwp'],
                'is_pph'                     => $array_input['is_pph'],
                'rating'                     => 2*intval($array_input['rating']),
                'orang_yang_bersangkutan'    => $array_input['orang_yang_bersangkutan'],
                'akun_hutang_jurnal_akun_id' => $save_akun,
                'is_active'                  => 1,
            );
            
            $save_supplier = $this->supplier_m->save($data_supplier);

            foreach ($array_input['phone'] as $phone) 
            {
                    
                if($phone['number'] != "")
                {
                    $data_phone = array(
                        'supplier_id'    => $save_supplier,
                        'subjek_telp_id' => $phone['subjek'],
                        'no_telp'        => $phone['number'],
                        'is_primary'     => $phone['is_primary_phone'],
                        'is_active'      => '1',
                    );
                    
                    $save_phone = $this->supplier_telepon_m->save($data_phone);
                }
                    
            }

            foreach ($array_input['email'] as $email) 
            {
                    
                if($email['email'] != "")
                {
                    $data_email = array(
                        'supplier_id' => $save_supplier,
                        'email'       => $email['email'],
                        'is_primary'  => $email['is_primary_email'],
                        'is_active'   => '1',
                    );
                    
                    $save_email = $this->supplier_email_m->save($data_email);
                    // die_dump($save_email);
                }
            }


            foreach ($array_input['alamat'] as $alamat) 
            {
                    
                if($alamat['alamat'] != "")
                {
                    $data_alamat = array(
                        'supplier_id'      => $save_supplier,
                        'subjek_alamat_id' => $alamat['subjek'],
                        'alamat'           => $alamat['alamat'],
                        'rt_rw'            => $alamat['rt'].'/'.$alamat['rw'],
                        'kode_lokasi'      => $alamat['kode'],
                        'kode_pos'         => $alamat['kode_pos'],
                        'is_primary'       => $alamat['is_primary_alamat'],
                        'is_active'        => '1',
                    );
                    
                    $save_alamat = $this->supplier_alamat_m->save($data_alamat); 
                }                    
                    
            }

            $items_berdasarkan_kode = $array_input['items_berdasarkan_kode'];

            if (isset($items_berdasarkan_kode)) {
                foreach ($items_berdasarkan_kode as $item_kode) {
                    if ($item_kode != "") {
                        $get_satuan = $this->item_satuan_m->get_by(array('item_id' => $item_kode));

                        $satuan = object_to_array($get_satuan);

                        foreach ($satuan as $item_satuan) {
                            // die_dump($item_kode);
                            $data_supplier_item = array(
                                'supplier_id'    => $save_supplier,
                                'item_id'        => $item_kode,
                                'item_satuan_id' => $item_satuan['id'],
                                'is_supply'      => 1,
                                'is_active'      => 1,
                            );

                            $save_supplier_item = $this->supplier_item_m->save($data_supplier_item);

                            $data_supplier_harga_item = array(
                                'supplier_item_id' => $save_supplier_item,
                                'harga' => 0,
                                'tanggal_efektif' => date('Y-m-d'),
                            );

                            $save_supplier_harga_item = $this->supplier_harga_item_m->save($data_supplier_harga_item);
                        }
                    }
                }
            }

            $items_berdasarkan_detail = $array_input['items_berdasarkan_detail'];

            if (isset($items_berdasarkan_detail)) {
                foreach ($items_berdasarkan_detail as $item_detail) {
                    if ($item_detail['is_selected'] == "1") {
                        
                        $get_satuan = $this->item_satuan_m->get_by(array('item_id' => $item_detail['item_id']));

                        $satuan = object_to_array($get_satuan);

                        // die_dump($satuan);
                        foreach ($satuan as $item_satuan) {
                            // die_dump($item_kode);
                            $data_supplier_item = array(
                                'supplier_id'    => $save_supplier,
                                'item_id'        => $item_detail['item_id'],
                                'item_satuan_id' => $item_satuan['id'],
                                'is_supply'      => 1,
                                'is_active'      => 1,
                            );

                            $save_supplier_item = $this->supplier_item_m->save($data_supplier_item);

                            $data_supplier_harga_item = array(
                                'supplier_item_id' => $save_supplier_item,
                                'harga'            => 0,
                                'tanggal_efektif'  => date('Y-m-d'),
                            );

                            $save_supplier_harga_item = $this->supplier_harga_item_m->save($data_supplier_harga_item);
                        }
                    }
                }
            }


            foreach ($array_input['pembayaran'] as $pembayaran) {
                
                $data_tipe_bayar = array(
                    'supplier_id'   => $save_supplier,
                    'tipe_bayar_id' => $pembayaran['tipe_bayar'],
                    'lama_tempo'    => $pembayaran['tempo'],
                    'is_active'     => 1,
                );

                $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->save($data_tipe_bayar);
            }

            foreach ($array_input['bank'] as $bank) {
                if($bank['nama'] != '')
                {
                    $data_bank = array(
                        'supplier_id' => $save_supplier,
                        'nob'         => $bank['nama'],
                        'cabang_bank' => $bank['cabang'],
                        'acc_name'    => $bank['atasnama'],
                        'acc_number'  => $bank['norek'],
                        'is_active'   => 1
                    );
                    $supplier_bank = $this->supplier_bank_m->save($data_bank);
                }
            }

            

            if ($save_supplier) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Supplier berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }
        
        elseif ($array_input['command'] === 'edit')
        {  
            
            // die_dump($array_input);
            
            $nama = '';
            $perusahaan = strtolower($array_input['nama']);
            
            if (preg_match("/^pt./", $perusahaan)){
                $nama_potong = explode('pt', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^pt[\s]/", $perusahaan)) {
                $nama_potong = explode('pt ', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^pt.[\s]/", $perusahaan)) {
                // $nama_potong = ltrim($perusahaan, 'pt. ');
                $nama_potong = explode('pt. ', $perusahaan);
                $nama = $nama_potong[1];
                // die_dump($nama_potong[1]);
            }

            if (preg_match("/^cv./", $perusahaan)){
                $nama_potong = explode('cv.', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^cv[\s]/", $perusahaan)) {
                $nama_potong = explode('cv ', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^cv.[\s]/", $perusahaan)) {
                $nama_potong = explode('cv. ', $perusahaan);
                $nama = $nama_potong[1];
            }

            if (preg_match("/^perum/", $perusahaan)) {
                $nama_potong = explode('perum ', $perusahaan);
                $nama = $nama_potong[1];
            }
            

            // die_dump($nama);
            $inisial_perusahaan = strtoupper($nama);

            $last_no_akun = $this->akun_m->get_no_akun()->result_array();
            $last_no_akun = intval($last_no_akun[0]['max_no_akun'])+1;
            
            $format       = '2000.'.'%02d';
            $no_akun      = sprintf($format, $last_no_akun, 2);
            
            $last_number  = $this->supplier_m->get_no_kode($inisial_perusahaan)->result_array();
            $last_number  = intval($last_number[0]['max_kode'])+1;
            
            $format       = $inisial_perusahaan.'%03d';
            $kode         = sprintf($format, $last_number, 3);

            $kode_supplier = '';

            if ($array_input['kode'][0] == $kode[0]) {
                $kode_supplier = $array_input['kode'];
            }else{
                $kode_supplier = $kode;
            }


            // die_dump($perusahaan);

            $data_akun = array(
                'nama'             => 'Hutang Supplier '.$kode_supplier,
            );

            // $save_akun = $this->akun_m->save($data_akun, $array_input['akun_hutang_jurnal_akun_id']);

            // $delete_akun = $this->akun_m->delete($save_akun-1);

            $obat = (isset($array_input['obat']))?$array_input['obat']:'0';
            $alkes = (isset($array_input['alkes']))?$array_input['alkes']:'0';
            $lain = (isset($array_input['lain']))?$array_input['lain']:'0';

            $jenis_barang = $obat.'-'.$alkes.'-'.$lain;

            $data_supplier = array(
                'tipe'                       => $array_input['tipe_supplier'],
                'tipe_barang'                => $jenis_barang,
                'kode'                       => $array_input['kode'],
                'nama'                       => $array_input['nama'],
                'is_pkp'                     => $array_input['is_pkp'],
                'npwp'                       => $array_input['npwp'],
                'is_pph'                     => $array_input['is_pph'],
                'rating'                     => 2*intval($array_input['rating']),
                'orang_yang_bersangkutan'    => $array_input['orang_yang_bersangkutan'],
                'akun_hutang_jurnal_akun_id' => $save_akun,
                'is_active'                  => 1,
            );
            
            $save_supplier = $this->supplier_m->save($data_supplier, $array_input['id']);

            // die_dump($this->db->last_query());
            foreach ($array_input['phone'] as $phone) 
            {
                    
                if($phone['number'] != "" && $phone['supplier_telp_id'] == "" && $phone['is_delete'] == "")
                {
                    $data_phone = array(
                        'supplier_id'    => $array_input['id'],
                        'subjek_telp_id' => $phone['subjek'],
                        'no_telp'        => $phone['number'],
                        'is_primary'     => $phone['is_primary_phone'],
                        'is_active'      => '1',
                    );
                    
                    $save_phone = $this->supplier_telepon_m->save($data_phone);
                }

                if($phone['number'] != "" && $phone['supplier_telp_id'] != "" && $phone['is_delete'] == "")
                {
                    $data_phone = array(
                        'supplier_id'    => $array_input['id'],
                        'subjek_telp_id' => $phone['subjek'],
                        'no_telp'        => $phone['number'],
                        'is_primary'     => $phone['is_primary_phone'],
                        'is_active'      => '1',
                    );
                    
                    $save_phone = $this->supplier_telepon_m->save($data_phone, $phone['supplier_telp_id']);
                }

                if($phone['number'] != "" && $phone['supplier_telp_id'] != "" && $phone['is_delete'] == "1")
                {
                    $data_phone = array(
                        'is_active'      => '0',
                    );
                    
                    $save_phone = $this->supplier_telepon_m->save($data_phone, $phone['supplier_telp_id']);
                }
                    
            }

            foreach ($array_input['email'] as $email) 
            {
                    
                if($email['email'] != "" && $email['supplier_email_id'] == "" && $email['is_delete'] == "")
                {
                    $data_email = array(
                        'supplier_id' => $array_input['id'],
                        'email'       => $email['email'],
                        'is_primary'  => $email['is_primary_email'],
                        'is_active'   => '1',
                    );
                    
                    $save_email = $this->supplier_email_m->save($data_email);
                }

                if($email['email'] != "" && $email['supplier_email_id'] != "" && $email['is_delete'] == "")
                {
                    $data_email = array(
                        'supplier_id' => $array_input['id'],
                        'email'       => $email['email'],
                        'is_primary'  => $email['is_primary_email'],
                        'is_active'   => '1',
                    );
                    
                    $save_email = $this->supplier_email_m->save($data_email, $email['supplier_email_id']);
                }

                if($email['email'] != "" && $email['supplier_email_id'] != "" && $email['is_delete'] == "1")
                {
                    $data_email = array(
                        'is_active'      => '0',
                    );
                    
                    $save_email = $this->supplier_email_m->save($data_email, $email['supplier_email_id']);
                }
                    
            }


            foreach ($array_input['alamat'] as $alamat) 
            {
                    
                if($alamat['alamat'] != "" && $alamat['supplier_alamat_id'] == "" && $alamat['is_delete'] == "")
                {
                    $data_alamat = array(
                        'supplier_id'      => $array_input['id'],
                        'subjek_alamat_id' => $alamat['subjek'],
                        'alamat'           => $alamat['alamat'],
                        'rt_rw'            => $alamat['rt'].'/'.$alamat['rw'],
                        'kode_lokasi'      => $alamat['kode'],
                        'kode_pos'         => $alamat['kode_pos'],
                        'is_primary'       => $alamat['is_primary_alamat'],
                        'is_active'        => '1',
                    );
                    
                    $save_alamat = $this->supplier_alamat_m->save($data_alamat); 
                }

                if($alamat['alamat'] != "" && $alamat['supplier_alamat_id'] != "" && $alamat['is_delete'] == "")
                {
                    $data_alamat = array(
                        'supplier_id'      => $array_input['id'],
                        'subjek_alamat_id' => $alamat['subjek'],
                        'alamat'           => $alamat['alamat'],
                        'rt_rw'            => $alamat['rt'].'/'.$alamat['rw'],
                        'kode_lokasi'      => $alamat['kode'],
                        'kode_pos'         => $alamat['kode_pos'],
                        'is_primary'       => $alamat['is_primary_alamat'],
                        'is_active'        => '1',
                    );
                    
                    $save_alamat = $this->supplier_alamat_m->save($data_alamat, $alamat['supplier_alamat_id']); 
                }

                if($alamat['alamat'] != "" && $alamat['supplier_alamat_id'] != "" && $alamat['is_delete'] == "1")
                {
                    $data_alamat = array(
                        'is_active'        => '0',
                    );
                    
                    $save_alamat = $this->supplier_alamat_m->save($data_alamat, $alamat['supplier_alamat_id']); 
                }                    
                    
            }

            // die_dump($this->db->last_query());

            $items_berdasarkan_kode = $array_input['items_berdasarkan_kode'];
            if (isset($items_berdasarkan_kode)) {
                foreach ($items_berdasarkan_kode as $item_kode) {
                    if ($item_kode != "") 
                    {
                        $data_supplier_item_db = $this->supplier_item_m->get_by(array('supplier_id' => $array_input['id'], 'item_id' => $item_kode), true);

                        if(count($data_supplier_item_db) == 0)
                        {
                            $get_satuan = $this->item_satuan_m->get_by(array('item_id' => $item_kode));

                            $satuan = object_to_array($get_satuan);

                            foreach ($satuan as $item_satuan) {
                                // die_dump($item_kode);
                                $data_supplier_item = array(
                                    'supplier_id'    => $array_input['id'],
                                    'item_id'        => $item_kode,
                                    'item_satuan_id' => $item_satuan['id'],
                                    'is_supply'      => 1,
                                    'is_active'      => 1,
                                );

                                $save_supplier_item = $this->supplier_item_m->save($data_supplier_item);

                                $data_supplier_harga_item = array(
                                    'supplier_item_id' => $save_supplier_item,
                                    'harga' => 0,
                                    'tanggal_efektif' => date('Y-m-d'),
                                );

                                $save_supplier_harga_item = $this->supplier_harga_item_m->save($data_supplier_harga_item);
                            }
                        }
                        else
                        {
                            if($data_supplier_item_db->is_active == 0)
                            {
                                $get_satuan = $this->item_satuan_m->get_by(array('item_id' => $item_kode));

                                $satuan = object_to_array($get_satuan);

                                foreach ($satuan as $item_satuan) {
                                    // die_dump($item_kode);
                                    $data_supplier_item = array(
                                        'item_id'        => $item_kode,
                                        'item_satuan_id' => $item_satuan['id'],
                                        'is_supply'      => 1,
                                        'is_active'      => 1,
                                    );

                                    $save_supplier_item = $this->supplier_item_m->save($data_supplier_item, $data_supplier_item_db->id);

                                    $data_supplier_harga_item = array(
                                        'supplier_item_id' => $data_supplier_item_db->id,
                                        'harga' => 0,
                                        'tanggal_efektif' => date('Y-m-d'),
                                    );

                                    $save_supplier_harga_item = $this->supplier_harga_item_m->save($data_supplier_harga_item);
                                }
                            }
                        }
                    }
                }
            }

            // $items_berdasarkan_detail = $array_input['items_berdasarkan_detail'];

            // if (isset($items_berdasarkan_detail)) {
            //     foreach ($items_berdasarkan_detail as $item_detail) {
            //         if ($item_detail['is_selected'] == "1") {
                        
            //             $get_satuan = $this->item_satuan_m->get_by(array('item_id' => $item_detail['item_id']));

            //             $satuan = object_to_array($get_satuan);

            //             // die_dump($satuan);
            //             foreach ($satuan as $item_satuan) {
            //                 // die_dump($item_kode);
            //                 $data_supplier_item = array(
            //                     'supplier_id' => $array_input['id'],
            //                     'item_id' => $item_detail['item_id'],
            //                     'item_satuan_id' => $item_satuan['id'],
            //                     'is_supply' => 1,
            //                     'is_active' => 1,
            //                 );

            //                 $save_supplier_item = $this->supplier_item_m->save($data_supplier_item);

            //                 $data_supplier_harga_item = array(
            //                     'supplier_item_id' => $save_supplier_item,
            //                     'harga' => 0,
            //                     'tanggal_efektif' => date('Y-m-d'),
            //                 );

            //                 $save_supplier_harga_item = $this->supplier_harga_item_m->save($data_supplier_harga_item);
            //             }
            //         }
            //     }
            // }

            //die(dump($array_input['pembayaran']));

            foreach ($array_input['pembayaran'] as $pembayaran) {
                
                if($pembayaran['id'] == '' && $pembayaran['is_active'] == 1 && $pembayaran['tipe_bayar'] != '')
                {
                    $data_tipe_bayar = array(
                        'supplier_id'   => $array_input['id'],
                        'tipe_bayar_id' => $pembayaran['tipe_bayar'],
                        'lama_tempo'    => $pembayaran['tempo'],
                        'is_active'     => 1,
                    );

                    $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->save($data_tipe_bayar);
                }
                if($pembayaran['id'] != '' && $pembayaran['is_active'] == 1)
                {
                    $data_tipe_bayar = array(
                        'supplier_id'   => $array_input['id'],
                        'tipe_bayar_id' => $pembayaran['tipe_bayar'],
                        'lama_tempo'    => $pembayaran['tempo'],
                        'is_active'     => 1,
                    );

                    $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->save($data_tipe_bayar, $pembayaran['id']);
                }
                if($pembayaran['id'] != '' && $pembayaran['is_active'] == 0)
                {
                    $data_tipe_bayar = array(
                        'is_active'     => 0
                    );

                    $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->save($data_tipe_bayar, $pembayaran['id']);
                }
            }

            foreach ($array_input['bank'] as $bank) {
                
                if($bank['id'] == '' && $bank['nama'] != '' && $bank['is_active'] == 1)
                {
                    $data_bank = array(
                        'supplier_id' => $array_input['id'],
                        'nob'         => $bank['nama'],
                        'cabang_bank' => $bank['cabang'],
                        'acc_name'    => $bank['atasnama'],
                        'acc_number'  => $bank['norek'],
                        'is_active'   => 1
                    );
                    $supplier_bank = $this->supplier_bank_m->save($data_bank);
                }
                if($bank['id'] != '' && $bank['nama'] != '' && $bank['is_active'] == 1)
                {
                    $data_bank = array(
                        'supplier_id' => $array_input['id'],
                        'nob'         => $bank['nama'],
                        'cabang_bank' => $bank['cabang'],
                        'acc_name'    => $bank['atasnama'],
                        'acc_number'  => $bank['norek'],
                        'is_active'   => 1
                    );
                    $supplier_bank = $this->supplier_bank_m->save($data_bank, $bank['id']);
                }
                if($bank['id'] != '' && $bank['is_active'] == 0)
                {
                    $data_bank = array(
                        'is_active'   => 0
                    );
                    $supplier_bank = $this->supplier_bank_m->save($data_bank, $bank['id']);
                }
            }

            
            if ($save_supplier) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Supplier berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }

        redirect("master/supplier");
    }

    public function save_claim(){
        $array_input = $this->input->post();
        // die_dump($array_input);

        if ($array_input['command'] == "add_claim") {
            $id_pasien = $array_input['id_pasien'];
            $data_pasien_penjamin = array
            (
                'pasien_id'            => $id_pasien,
                'no_kartu'             => $array_input['no_kartu'],
                'penjamin_id'          => $array_input['penjamin'],
                'penjamin_kelompok_id' => $array_input['penjamin_kelompok'],
                'status'               => '1',
                'is_active'            => '1'
            );
            
            $save_pasien_penjamin = $this->pasien_penjamin_m->save($data_pasien_penjamin);
            //die_dump($this->db->last_query());
            for ($i = 0; $i <= $array_input['total_dokumen']; $i++)
            {
                if (isset($array_input['dokumen_'.$i]) != "") 
                {
                    
                    
                    if ($array_input['tipe_'.$i] == "4" || $array_input['tipe_'.$i] == "5"){
                        $data_pasien_syarat_penjamin = array
                        (
                            'pasien_id'       => $id_pasien,
                            'syarat_id'       => $array_input['syarat_id_'.$i],
                            'is_active'       => '1',
                        );

                        $save_pasien_syarat_penjamin = $this->pasien_syarat_penjamin_m->save($data_pasien_syarat_penjamin);
                    
                        $data_pasien_syarat_detail_penjamin = array(
                            'pasien_syarat_penjamin_id' => $save_pasien_syarat_penjamin,
                            'value'                     => $array_input['dokumen_'.$i],
                        );

                        $save_pasien_syarat_detail_penjamin = $this->pasien_syarat_penjamin_detail_m->save($data_pasien_syarat_detail_penjamin);

                    }else if($array_input['tipe_'.$i] == "6" || $array_input['tipe_'.$i] == "7")
                    {
                        $data_pasien_syarat_penjamin = array
                        (
                            'pasien_id'       => $id_pasien,
                            'syarat_id'       => $array_input['syarat_id_'.$i],
                            'is_active'       => '1',
                        );
                        $save_pasien_syarat_penjamin = $this->pasien_syarat_penjamin_m->save($data_pasien_syarat_penjamin);
                        foreach ($array_input['dokumen_'.$i] as $data) {
                            
                            
                            
                        
                            $data_pasien_syarat_detail_penjamin = array(
                                'pasien_syarat_penjamin_id' => $save_pasien_syarat_penjamin,
                                'value'                     => $data,
                            );

                            $save_pasien_syarat_detail_penjamin = $this->pasien_syarat_penjamin_detail_m->save($data_pasien_syarat_detail_penjamin);
                        }
                    }else
                    {
                        if ($array_input['pasien_syarat_penjamin_id_'.$i] == "") {
                            $data_pasien_syarat_penjamin = array
                            (
                                'pasien_id'       => $id_pasien,
                                'syarat_id'       => $array_input['syarat_id_'.$i],
                                'value'           => $array_input['dokumen_'.$i],
                                'is_active'       => '1',
                            );

                            $save_pasien_syarat_penjamin = $this->pasien_syarat_penjamin_m->save($data_pasien_syarat_penjamin);
                            
                            $data_pasien_isi_syarat_penjamin = array
                            (
                                'pasien_penjamin_id'        => $save_pasien_penjamin,
                                'pasien_syarat_penjamin_id' => $save_pasien_syarat_penjamin,
                                'is_active'                 => '1',
                            );

                            $save_pasien_isi_syarat_penjamin = $this->pasien_isi_syarat_penjamin_m->save($data_pasien_isi_syarat_penjamin);
                        }else{

                            //die_dump($array_input['pasien_syarat_penjamin_id_'.$i]);
                            $data_pasien_isi_syarat_penjamin = array
                            (
                                'pasien_penjamin_id'        => $save_pasien_penjamin,
                                'pasien_syarat_penjamin_id' => $array_input['pasien_syarat_penjamin_id_'.$i],
                                'is_active'                 => '1',
                            );

                            $save_pasien_isi_syarat_penjamin = $this->pasien_isi_syarat_penjamin_m->save($data_pasien_isi_syarat_penjamin);
                        }   
                        
                    }
                    
                    //die_dump($this->db->last_query());
                   

                }   
            }
        }elseif ($array_input['command'] == "edit_claim") {
                $id_pasien = $array_input['id_pasien'];
                $id_pasien_penjamin = $array_input['id_pasien_penjamin'];
                

                $data_pasien_penjamin = array
                (
                    'no_kartu'             => $array_input['no_kartu'],
                    'penjamin_kelompok_id' => $array_input['penjamin_kelompok'],
                    'status'               => '1',
                    'is_active'            => '1'
                );
                
                $save_pasien_penjamin = $this->pasien_penjamin_m->save($data_pasien_penjamin, $id_pasien_penjamin);
                //die_dump($this->db->last_query());
                for ($i = 0; $i <= $array_input['total_dokumen']; $i++)
                {
                    if (isset($array_input['dokumen_'.$i]) != "") 
                    {
                        
                        

                        if ($array_input['tipe_'.$i] == "4" || $array_input['tipe_'.$i] == "5"){
                            $data_pasien_syarat_penjamin = array
                            (
                                'pasien_id'       => $id_pasien,
                                'syarat_id'       => $array_input['syarat_id_'.$i],
                                'is_active'       => '1',
                            );

                            $save_pasien_syarat_penjamin = $this->pasien_syarat_penjamin_m->save($data_pasien_syarat_penjamin, $array_input['pasien_syarat_penjamin_id_'.$i]);
                            
                            //die_dump($this->db->last_query());
                            $data_pasien_syarat_detail_penjamin = array(
                                'pasien_syarat_penjamin_id' => $save_pasien_syarat_penjamin,
                                'value'                     => $array_input['dokumen_'.$i],
                            );

                            $save_pasien_syarat_detail_penjamin = $this->pasien_syarat_penjamin_detail_m->save($data_pasien_syarat_detail_penjamin, $array_input['pasien_syarat_penjamin_detail_id_'.$i]);
                            die_dump($this->db->last_query());
                        }else if($array_input['tipe_'.$i] == "6" || $array_input['tipe_'.$i] == "7")
                        {
                            
                            $syarat_detail = $this->pasien_penjamin_m->get_data_syarat_detail($array_input['syarat_id_'.$i], $array_input['tipe_'.$i])->result_array();
                            
                            foreach ($syarat_detail as $syarat_detail_row) 
                            {
                                          
                                    $pasien_syarat_penjamin_detail = $this->pasien_syarat_penjamin_detail_m->get_data_pasien_syarat_penjamin_detail_by_value($id_pasien_penjamin, $array_input['syarat_id_'.$i], $syarat_detail_row['value']);
                                
                                    $pasien_syarat_penjamin_detail_result_array = $pasien_syarat_penjamin_detail->result_array();
                                    
                                    if ($pasien_syarat_penjamin_detail->num_rows() == 0)
                                    {

                                        if(in_array($syarat_detail_row['value'], $array_input['dokumen_'.$i]))
                                        {
                                            $data_pasien_syarat_detail_penjamin = array(
                                                'pasien_syarat_penjamin_id' => $array_input['pasien_syarat_penjamin_id_'.$i],
                                                'value'                     => $syarat_detail_row['value'],
                                            );

                                            $save_pasien_syarat_detail_penjamin = $this->pasien_syarat_penjamin_detail_m->save($data_pasien_syarat_detail_penjamin);
                                            
                                        }
                                           
                                            
                                    }
                                    else
                                    {
                                        
                                        if (!in_array($syarat_detail_row['value'], $array_input['dokumen_'.$i])) 
                                        {
                                            $save_pasien_syarat_detail_penjamin = $this->pasien_syarat_penjamin_detail_m->delete($pasien_syarat_penjamin_detail_result_array[0]['id_detail']);
                                        }
                                        
                                    }   
                                                                                          
                            }
                        }else
                        {
                            $data_pasien_syarat_penjamin = array
                            (
                                'pasien_id'       => $id_pasien,
                                'syarat_id'       => $array_input['syarat_id_'.$i],
                                'value'           => $array_input['dokumen_'.$i],
                                'is_active'       => '1',
                            );

                            $save_pasien_syarat_penjamin = $this->pasien_syarat_penjamin_m->save($data_pasien_syarat_penjamin, $array_input['pasien_syarat_penjamin_id_'.$i]);
                            
                        }
                        
                        //die_dump($this->db->last_query());
                        // $data_pasien_isi_syarat_penjamin = array
                        // (
                        //     'pasien_penjamin_id'        => $save_pasien_penjamin,
                        //     'pasien_syarat_penjamin_id' => $save_pasien_syarat_penjamin,
                        //     'is_active'                 => '1',
                        // );

                        // $save_pasien_isi_syarat_penjamin = $this->pasien_isi_syarat_penjamin_m->save($data_pasien_isi_syarat_penjamin);

                    }   
                }
        }
        

        if ($save_pasien_penjamin || $save_pasien_syarat_penjamin || $save_pasien_isi_syarat_penjamin) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Pasien Penjamin Berhasil Di Tambahkan", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/pasien/claim/$id_pasien");
       
    }
    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $pasien_id = $this->supplier_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->max_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->max_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 4,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($pasien_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate(" Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/supplier");
    }

    public function delete_syarat_penjamin($id, $id_pasien)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $pasien_penjamin_id = $this->pasien_penjamin_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->max_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->max_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 4,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($pasien_syarat_penjamin_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate(" Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/pasien/claim/$id_pasien");
    }

    public function get_negara(){

        //$id_negara = $this->input->post('id_negara');
        //die_dump($id_negara);
        //$this->region_m->set_columns(array('id','nama'));
        $negara       = $this->region_m->get_by(array('parent' => Null));
        //die_dump($this->db->last_query());        
        $hasil_negara = object_to_array($negara);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_negara);
    }

    public function get_provinsi(){

        $id_negara      = $this->input->post('id_negara');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $provinsi       = $this->region_m->get_data_region($id_negara)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_provinsi = object_to_array($provinsi);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_provinsi);
    }

    public function get_kota(){

        $id_provinsi = $this->input->post('id_provinsi');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kota        = $this->region_m->get_data_region($id_provinsi)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kota  = object_to_array($kota);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kota);
    }

    public function get_kecamatan(){

        $id_kota         = $this->input->post('id_kota');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kecamatan       = $this->region_m->get_data_region($id_kota)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kecamatan = object_to_array($kecamatan);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kecamatan);
    }

    public function get_kelurahan(){

        $id_kecamatan    = $this->input->post('id_kecamatan');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kelurahan       = $this->region_m->get_data_region($id_kecamatan)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kelurahan = object_to_array($kelurahan);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kelurahan);
    }

    public function get_kode_cabang(){

        $id_negara    = $this->input->post('id_cabang');
        //die_dump($id_negara);
        $this->cabang_m->set_columns(array('id','kode'));
        $cabang       = $this->cabang_m->get_by(array('id' => $id_negara));
        //die_dump($this->db->last_query());        
        $hasil_cabang = object_to_array($cabang);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_cabang);
    }

    public function get_subjek(){

        $tipe              = $this->input->post('tipe');
        $data_subjek       = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek = object_to_array($data_subjek);

        echo json_encode($hasil_data_subjek);

    }

    public function save_subjek(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_subjek = array(
            "tipe" => $tipe,
            "nama" => $nama,
        );

        
        $save_data         = $this->subjek_m->save($data_subjek);
        
        $data_subjek       = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek = object_to_array($data_subjek);
        
        echo json_encode($hasil_data_subjek);

    }

    public function save_negara(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_negara = array(
            "tipe" => $tipe,
            "nama" => $nama,
        );

        $save_data         = $this->region_m->save($data_negara);
        
        $data_negara       = $this->region_m->get_by(array('parent' => null));
        $hasil_data_negara = object_to_array($data_negara);

        echo json_encode($hasil_data_negara);

    }

    public function save_region(){

        $parent = $this->input->post('parent');
        $tipe   = $this->input->post('tipe');
        $nama   = $this->input->post('nama');

        $data_region = array(
            "parent" => $parent,
            "tipe"   => $tipe,
            "nama"   => $nama,
        );

        
        $save_data         = $this->region_m->save($data_region);
        
        $data_region       = $this->region_m->get_by(array('parent' => $parent));
        $hasil_data_region = object_to_array($data_region);

        echo json_encode($hasil_data_region);

    }

    public function modal_harga($id)
    {
        
        $data = array(
            'supplier_harga_item_id' => $id,             
        );

        $this->load->view('master/supplier/modal/modal_harga.php', $data);
    
    }

    public function modal_order($id)
    {
        
        $data = array(
            'supplier_item_id' => $id,             
        );

        $this->load->view('master/supplier/modal/modal_order.php', $data);
    
    }

    public function save_supplier_harga_item()
    {
        $array_input = $this->input->post();

        $data_supplier_harga_item = array(
            'harga' => $array_input['harga'],
            'tanggal_efektif' => date('Y-m-d', strtotime($array_input['tanggal_efektif'])),
        );

        $save_supplier_harga_item = $this->supplier_harga_item_m->save($data_supplier_harga_item, $array_input['supplier_harga_item_id']);

        echo json_encode($save_supplier_harga_item);
    }

    public function save_supplier_item()
    {
        $array_input = $this->input->post();

        $data_supplier_item = array(
            'minimum_order' => $array_input['minimum_order'],
            'kelipatan_order' => $array_input['kelipatan_order'],
        );

        $save_supplier_item = $this->supplier_item_m->save($data_supplier_item, $array_input['supplier_item_id']);
        // die_dump($save_supplier_item);

        echo json_encode($save_supplier_item);
    }

    public function update_status_supply()
    {
        $array_input = $this->input->post();

        $data_supplier_item = array(
            'is_supply' => $array_input['is_supply'],
        );

        $save_supplier_item = $this->supplier_item_m->save($data_supplier_item, $array_input['supplier_item_id']);
        // die_dump($save_supplier_item);

        echo json_encode($save_supplier_item);
    }

    public function update_flexible()
    {
        $array_input = $this->input->post();

        $data_supplier_item = array(
            'is_harga_flexible' => $array_input['is_harga_flexible'],
        );

        $save_supplier_item = $this->supplier_item_m->save($data_supplier_item, $array_input['supplier_item_id']);
        // die_dump($save_supplier_item);

        echo json_encode($save_supplier_item);
    }

    public function update_pph()
    {
        $array_input = $this->input->post();

        $data_supplier_item = array(
            'is_pph' => $array_input['is_pph'],
        );

        $save_supplier_item = $this->supplier_item_m->save($data_supplier_item, $array_input['supplier_item_id']);
        // die_dump($save_supplier_item);

        echo json_encode($save_supplier_item);
    }

    public function get_item() {

        $item_id = $this->input->post('item_id');
        $item_id = str_replace("%2C",",", $item_id);  

        $data_item = $this->item_m->get_data_item($item_id);
        
        echo json_encode($data_item);
    }
}

/* End of file supplier.php */
/* Location: ./application/controllers/master/supplier.php */