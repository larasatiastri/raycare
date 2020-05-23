<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang_datang_farmasi extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '2c920d3d1ff6edb348ff8aefc98f0d3b';                  // untuk check bit_access

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

        $this->load->model('gudang/barang_datang/draft_pmb_m');
        $this->load->model('gudang/barang_datang/draft_pmb_po_m');
        $this->load->model('gudang/barang_datang/draft_pmb_detail_m');
        $this->load->model('gudang/barang_datang/draft_pmb_detail_actual_m');
        $this->load->model('gudang/barang_datang/draft_pmb_identitas_m');
        $this->load->model('gudang/barang_datang/draft_pmb_identitas_detail_m');
        $this->load->model('pembelian/retur_pembelian_m');

        $this->load->model('gudang/barang_datang/pmb_m');
        $this->load->model('gudang/barang_datang/pmb_detail_m');
        $this->load->model('gudang/barang_datang/pmb_identitas_m');
        $this->load->model('gudang/barang_datang/pmb_identitas_detail_m');
        $this->load->model('gudang/barang_datang/pmb_po_detail_m');
        $this->load->model('gudang/barang_datang/pembelian_m');
        $this->load->model('gudang/barang_datang/pembelian_detail_m');
        $this->load->model('gudang/barang_datang/item_identitas_m');
        $this->load->model('gudang/barang_datang/item_satuan_m');
        $this->load->model('gudang/barang_datang/inventory_m');
        $this->load->model('gudang/barang_datang/inventory_identitas_m');
        $this->load->model('gudang/barang_datang/inventory_identitas_detail_m');
        $this->load->model('gudang/barang_datang/inventory_history_m');
        $this->load->model('gudang/barang_datang/inventory_history_detail_m');
        $this->load->model('gudang/barang_datang/inventory_history_identitas_m');
        $this->load->model('gudang/barang_datang/inventory_history_identitas_detail_m');
        $this->load->model('gudang/gudang_m');
        $this->load->model('gudang/supplier_m');
        $this->load->model('gudang/supplier_telp_m');
        $this->load->model('gudang/supplier_alamat_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/identitas_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/region_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('pembelian/pembelian_detail_tanggal_kirim_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/gudang/barang_datang_farmasi/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Penerimaan Barang', $this->session->userdata('language')), 
            'header'         => translate('Penerimaan Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'gudang/barang_datang_farmasi/index',
            // 'content_view'   => 'under_maintenance',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add_terima_barang($item_id, $item_satuan_id, $jumlah, $row, $draft_detail_id, $draft_id, $supplier_id, $gudang_id,$po_detail_id)
    {
        $assets = array();
        $config = 'assets/gudang/barang_datang_farmasi/add_terima_barang';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $draft_pmb_actual = $this->draft_pmb_detail_actual_m->get_by(array('draft_pmb_detail_id' => $draft_detail_id));

        if(count($draft_pmb_actual) != 0)
        {
            $draft_pmb_actual = object_to_array($draft_pmb_actual);
        }
        else
        {
            $draft_pmb_actual = '';
        }
        $data = array(
            'item_id'          => $item_id,
            'item_satuan_id'   => $item_satuan_id,
            'jumlah'           => $jumlah,
            'row_id'           => $row,
            'draft_detail_id'  => $draft_detail_id,
            'draft_pmb_actual' => $draft_pmb_actual,
            'draft_pmb_id'     => $draft_id,
            'supplier_id'      => $supplier_id,
            'gudang_id'        => $gudang_id,
            'po_detail_id'     => $po_detail_id
        );

        // Load the view
        $this->load->view('gudang/barang_datang_farmasi/add_terima_barang', $data);
    }

    public function proses($gudang_id, $supplier_id, $data)
    {
        // $id = intval($id);
        // $id || redirect(base_Url());
        if(restriction_function($this->session->userdata('level_id'), 'barang_datang','proses'))
        {
            $assets = array();
            $config = 'assets/gudang/barang_datang_farmasi/proses';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            

            $data = array(
                'title'        => config_item('site_name').' | '.translate("Proses Barang Datang", $this->session->userdata("language")), 
                'header'       => translate("View Barang Datang", $this->session->userdata("language")), 
                'header_info'  => config_item('site_name'), 
                'breadcrumb'   => TRUE,
                'menus'        => $this->menus,
                'menu_tree'    => $this->menu_tree,
                'css_files'    => $assets['css'],
                'js_files'     => $assets['js'],
                'content_view' => 'gudang/barang_datang_farmasi/proses',                  
                'draft_pmb_id' => $data,                   
                'supplier_id'  => $supplier_id,                   
                'gudang_id'    => $gudang_id,                   
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

    public function proses_tambah_po($data, $supplier_id, $gudang_id)
    {
        // $id = intval($id);
        // $id || redirect(base_Url());
            $assets = array();
            $config = 'assets/gudang/barang_datang_farmasi/proses_draft';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            // $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
            // $data = base64_decode(urldecode($data));
            // $data = unserialize($data);

            // die_dump($data);
            // foreach ($data as $id) {
            //     # code...
            // }

            // $form_data = $this->pembelian_m->get($id);

            $data = array(
                'title'        => config_item('site_name').' | '.translate("Proses Barang Datang", $this->session->userdata("language")), 
                'header'       => translate("View Barang Datang", $this->session->userdata("language")), 
                'header_info'  => config_item('site_name'), 
                'breadcrumb'   => TRUE,
                'menus'        => $this->menus,
                'menu_tree'    => $this->menu_tree,
                'css_files'    => $assets['css'],
                'js_files'     => $assets['js'],
                'content_view' => 'gudang/barang_datang_farmasi/proses_draft',                  
                'draft_pmb_id' => $data,                   
                'supplier_id'  => $supplier_id,                   
                'gudang_id'    => $gudang_id,                   
            );

            // Load the view
            $this->load->view('_layout', $data);
    }

    public function view($id, $supplier_id, $gudang_id, $flag)
    {
       
        $assets = array();
        $config = 'assets/gudang/barang_datang_farmasi/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $data = array();
        
        if($flag == 'pmb')
        {
            $get_pembelian_id = $this->pmb_detail_m->get_pembelian_id($id)->result_array();

            foreach ($get_pembelian_id as $pembelian_id) {
                $data[] = $pembelian_id['pembelian_id'];
            }
            
            $form_data = $this->pmb_m->get_by(array('id' => $id), true);
        }        
        else
        {
            $get_draft_pembelian_id = $this->draft_pmb_po_m->get_id($id);

            foreach ($get_draft_pembelian_id as $pembelian_id) {
                $data[] = $pembelian_id['po_id'];
            }

            $form_data = $this->draft_pmb_m->get_id($id)->row(0);
        }

        // die(dump($form_data));
        // $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
       

        $data = array(
            'title'        => config_item('site_name').' | '.translate("View Barang Datang", $this->session->userdata("language")), 
            'header'       => translate("View Barang Datang", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'gudang/barang_datang_farmasi/view',
            'form_data'    => object_to_array($form_data),
            'pembelian_id' => $data,                   
            'supplier_id'  => $supplier_id,                   
            'gudang_id'    => $gudang_id,
            'pk_value'     => $id,                         //table primary key value
            'flag'         => $flag
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        
        $result = $this->pmb_m->get_datatable();
        // die_dump($this->db->last_query());
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
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'gudang/barang_datang_farmasi/view/'.$row['id'].'/'.$row['supplier_id'].'/'.$row['gudang_id'].'/'.$flag.'" name="view[]" class="btn grey-cascade pecah-item" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-search"></i></a>';
            

            $output['data'][] = array(
                $row['id'],
                '<div class="text-left inline-button-table">'.$row['no_pmb'].'</div>' ,
                '<div class="text-left inline-button-table">'.$row['no_surat_jalan'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>' ,
                $row['supplier_nama'].'&nbsp;<b>"'.$row['supplier_kode'].'"</b>',
                $row['keterangan_gudang'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pembelian_datang()
    {        
        $result = $this->pembelian_detail_tanggal_kirim_m->get_datatable_datang(1);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
        
        
        $records = $result->records;
        //die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '<a href="'.base_url().'gudang/barang_datang_farmasi/select_po/'.$row['po_id'].'/'.$row['supplier_id'].'/WH-05-2016-002" title="'.translate('Pilih', $this->session->userdata('language')).'" class="btn btn-primary"><i class="fa fa-check"></i></a>';

            $tgl_kirim = date('Y-m-d', strtotime($row['tanggal_kirim']));
            $pembelian_detail = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim_detail($row['po_id'], $tgl_kirim);
            $pembelian_detail = object_to_array($pembelian_detail);
            
            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($pembelian_detail)).'" class="pilih-item" data-id="'.$row['po_id'].'" name="info"><u>'.count($pembelian_detail).' item</u></a>';
           

            $output['data'][] = array(
                '<div class="text-center">'.$row['po_id'].'</div>' ,
                '<div class="text-left inline-button-table">'.date('d M Y', strtotime($row['tanggal_pesan'])).'</div>' ,
                '<div class="text-left inline-button-table">'.$row['no_pembelian'].'</div>' ,
                '<div class="text-left inline-button-table">'.$row['supplier_nama'].' ['.$row['supplier_kode'].']</div>' ,
                '<div class="text-center bold">'.$info.'</div>' ,
                '<div class="text-left inline-button-table">'.date('d M Y', strtotime($row['tanggal_kirim'])).'</div>' ,
                $row['pj_po'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_draft()
    {        
        $result = $this->draft_pmb_m->get_datatable();
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $flag = 'draft_pmb';
        $i=0;
        foreach($records->result_array() as $row)
        {
            // $action = '';
            // if(empty($row['diproses_oleh']))
            // {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'gudang/barang_datang_farmasi/view/'.$row['id'].'/'.$row['supplier_id'].'/'.$row['gudang_id'].'/'.$flag.'" name="view[]" class="btn grey-cascade pecah-item" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-search"></i></a>
                       <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'gudang/barang_datang_farmasi/proses_tambah_po/'.$row['id'].'/'.$row['supplier_id'].'/'.$row['gudang_id'].'" name="view[]" class="btn blue-chambray pecah-item" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Tambah PO', $this->session->userdata('language')).'" href="'.base_url().'gudang/barang_datang_farmasi/modal_add_po/'.$row['id'].'/'.$row['supplier_id'].'/'.$row['gudang_id'].'" name="view[]" data-toggle="modal" data-target="#popup_modal_add_po" class="btn btn-primary pecah-item" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-plus"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data draft barang datang ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
            // }
            

            $output['data'][] = array(
                $row['id'],
                $row['supplier_nama'].'&nbsp;"'.$row['supplier_kode'].'"',
                '<div class="text-center">'.date('d/m/Y', strtotime($row['tanggal'])).'</div>' ,
                $row['keterangan_gudang'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_daftar_pembelian($tipe_supplier=null, $supplier_id=null){
        $result = $this->pembelian_m->get_datatable($supplier_id, $tipe_supplier);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
        
        
        $records = $result->records;
        // die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {
            
            $checkbox      = '<input class="checkboxes" name="checkbox_pembelian" id="checkbox_pembelian" type="checkbox" data-id="'.$row['id'].'">';

            $output['data'][] = array(
                '<div class="text-left">'.$row['no_pembelian'].'</div>' ,
                '<div class="text-left">'.date('d F Y', strtotime($row['tanggal_pesan'])).'</div>' ,
                '<div class="text-left">'.date('d F Y', strtotime($row['tanggal_kadaluarsa'])).'</div>' ,
                $row['keterangan'],
                '<div class="text-center inline-button-table">'.$checkbox.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_daftar_pembelian_draft($draft_id=null, $supplier_id=null, $tipe_supplier=null){
        $result = $this->pembelian_m->get_datatable($supplier_id, $tipe_supplier);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
        
        // die_dump($this->db->last_query());
        
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
                
            $search_data = $this->draft_pmb_po_m->get_data($draft_id, $row['id']);
            $checked = '';
            $disabled = '';
            if($search_data)
            {
                $checked = 'checked';
                $disabled = 'disabled';
            }

            $checkbox      = '<input class="checkboxes" '.$checked.' '.$disabled.' name="checkbox_pembelian" id="checkbox_pembelian" type="checkbox" data-id="'.$row['id'].'">';

            $output['data'][] = array(
                '<div class="text-center">'.$row['no_pembelian'].'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal_pesan'])).'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal_kadaluarsa'])).'</div>' ,
                $row['keterangan'],
                '<div class="text-center inline-button-table">'.$checkbox.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_jumlah_pesan($supplier_id=null, $item_id=null, $item_satuan_id=null, $po_id){
        $result = $this->pembelian_m->get_datatable_detail($supplier_id, $item_id, $item_satuan_id, $po_id);
       

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
            $tgl_garansi = ($row['tanggal_garansi'] != '1970-01-01')?date('d F Y', strtotime($row['tanggal_garansi'])):'-';

            $jml_diterima = 0;
            $diterima_detail = $this->pmb_po_detail_m->get_data_diterima($row['id_detail']);
            if(count($diterima_detail)){
                $jml_diterima = '';
                foreach ($diterima_detail as $diterima) {
                   $jml_diterima .= $diterima['jumlah'].' '.$diterima['nama_satuan'].', '; 
                }

                $jml_diterima = rtrim($jml_diterima,', ');
            }

            $output['data'][] = array(
                '<div class="text-center">'.$row['no_pembelian'].'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal_pesan'])).'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal_kadaluarsa'])).'</div>' ,
                '<div class="text-center">'.$tgl_garansi.'</div>' ,
                '<div class="text-center">'.$row['jumlah_pesan'].' '.$row['nama_satuan'].'</div>',
                '<div class="text-center">'.formatrupiah($row['harga_beli']).'</div>',
                '<div class="text-center">'.$jml_diterima.'</div>',
                '<div class="text-center">'.$row['jumlah_belum_diterima'].' '.$row['nama_satuan_primary'].'</div>'
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_jumlah_pesan_supplier_lain($supplier_id=null, $item_id=null, $item_satuan_id=null){
        $result = $this->pembelian_m->get_datatable_detail_supplier_lain($supplier_id, $item_id, $item_satuan_id);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
        
        // die_dump($this->db->last_query());
        
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {

            $output['data'][] = array(
                '<div class="text-center">'.$row['no_pembelian'].'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal_pesan'])).'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal_kadaluarsa'])).'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal_kirim'])).'</div>' ,
                '<div class="text-center">'.$row['jumlah_pesan'].'</div>',
                '<div class="text-center">'.$row['jumlah_diterima'].'</div>',
                '<div class="text-center">'.$row['jumlah_belum_diterima'].'</div>'
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listing_jumlah_terima($supplier_id=null, $item_id=null, $item_satuan_id=null){
        $result = $this->pmb_m->get_datatable_detail($supplier_id, $item_id, $item_satuan_id);
        // die_dump($po_id);

        // $data = base64_decode(urldecode($po_id));
        // $data = unserialize($data);

        // die_dump($this->db->last_query());

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
        
        // die_dump($this->db->last_query());
        
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
            $output['data'][] = array(
                '<div class="text-center">'.$row['no_pmb'].'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.$row['jumlah_diterima'].' '.$row['nama_satuan'].'</div>'
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_jumlah_terima_supplier_lain($supplier_id=null, $item_id=null, $item_satuan_id=null){
        $result = $this->pmb_m->get_datatable_detail_supplier_lain($supplier_id, $item_id, $item_satuan_id);
        // die_dump($po_id);

        // $data = base64_decode(urldecode($po_id));
        // $data = unserialize($data);

        // die_dump($this->db->last_query());

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
        
        // die_dump($this->db->last_query());
        
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
            $output['data'][] = array(
                '<div class="text-center">'.$row['no_pmb'].'</div>' ,
                '<div class="text-center">'.date('d F Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.$row['jumlah_diterima'].'</div>'
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();

        // die(dump($array_input));

        $po_id = '';
        foreach ($array_input['po'] as $po) {
            $po_id .= $po['po_id']."','";
        }
        $po_id = rtrim($po_id,"','");

        $last_number = $this->pmb_m->get_no_pmb()->result_array();
        $last_number = intval($last_number[0]['max_no_pmb'])+1;
        
        $format      = '#PMB#%04d/RHS-RI/'.romanic_number(date('m'), true).'/'.date('Y');
        $no_pmb      = sprintf($format, $last_number, 4);
        
        $max_id_pmb = $this->pmb_m->get_max_id_pmb()->result_array();

        if(count($max_id_pmb) == 0)
        {
            $max_id_pmb = 1;
        }
        else
        {
            $max_id_pmb = intval($max_id_pmb[0]['max_id']) + 1;
        }

        $format           = 'PMB-'.date('m').'-'.date('Y').'-%04d';
        $id_pmb    = sprintf($format, $max_id_pmb, 4);

        $tanggal = date("Y-m-d", strtotime($array_input['tanggal']));

        $new_filename = '';
        if($array_input['url_faktur'] != '')
        {
            $path_dokumen = '../cloud/'.config_item('site_dir').'pages/gudang/barang_datang_farmasi/images/'.$id_pmb;
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

            $temp_filename = $array_input['url_faktur'];

            $convtofile = new SplFileInfo($temp_filename);
            $extenstion = ".".$convtofile->getExtension();

            $new_filename = $array_input['url_faktur'];
            $real_file = $id_pmb.'/'.$new_filename;

            copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_faktur_pmb_farmasi').$real_file);
        }

        $data_pmb = array(
            'id'                => $id_pmb,
            'gudang_id'         => $array_input['gudang_id'],
            'supplier_id'       => $array_input['supplier_id'],
            'tanggal'           => $tanggal,
            'no_pmb'            => $no_pmb,
            'no_surat_jalan'    => $array_input['surat_jalan'],
            'no_faktur'         => $array_input['no_faktur'],
            'url_surat_jalan'        => $new_filename,
            'status'            => 1,
            'is_active'         => '1',
            'keterangan_gudang' => $array_input['keterangan'],
            'created_by'        => $this->session->userdata('user_id'),
            'created_date'      => date('Y-m-d H:i:s')
        );

        //save pmb
        $save_pmb = $this->pmb_m->add_data($data_pmb);
        
        $data_inventory_history = array(
            'transaksi_id'   => $id_pmb,
            'transaksi_tipe' => '4'
        );

        //save inventory histoy
        $save_inventory_history = $this->inventory_history_m->save($data_inventory_history);

        $get_detail = $this->draft_pmb_detail_m->get_by(array('draft_pmb_id' => $array_input['draft_id']));
        $detail     = object_to_array($get_detail);


        foreach ($detail as $draft_detail) {
            // $po_detail = $this->pembelian_detail_m->get_data_item($po_id,$draft_detail['item_id'])->result_array();
            
            $get_actual_detail = $this->draft_pmb_detail_actual_m->get_by(array('draft_pmb_detail_id' => $draft_detail['draft_pmb_detail_id']));
            $actual_detail     = object_to_array($get_actual_detail);

            foreach ($actual_detail as $draft_actual_detail)
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

                $item_satuan_primary = $this->item_satuan_m->get_by(array('item_id'=> $draft_actual_detail['item_id'], 'is_primary' => 1), true);

                $pmb_detail = array(
                    'id'                     => $id_pmb_detail,
                    'pmb_id'                 => $id_pmb,
                    'item_id'                => $draft_actual_detail['item_id'],
                    'item_satuan_id'         => $draft_actual_detail['item_satuan_id'],
                    'jumlah_diterima'        => $draft_actual_detail['jumlah_diterima'],
                    'item_satuan_primary_id' => $item_satuan_primary->id,
                    'jumlah_primary'         => $draft_actual_detail['jumlah_konversi'],
                    'bn_sn_lot'             => $draft_actual_detail['bn_sn_lot'],
                    'expire_date'           => date('Y-m-d', strtotime($draft_actual_detail['expire_date'])),
                    'created_by'             => $this->session->userdata('user_id'),
                    'created_date'           => date('Y-m-d H:i:s')
                );

                //save pmb_detail
                $save_detail    = $this->pmb_detail_m->add_data($pmb_detail);

                //insert inventory
                $max_inventory_id = $this->inventory_m->max_inventory_id()->result_array();
                if(count($max_inventory_id) != 0){
                    $max_inventory_id = intval($max_inventory_id[0]['max_id'])+1;
                }else{
                    $max_inventory_id = 1;
                }

                $data_inventory = array(
                    'inventory_id'        => $max_inventory_id,
                    'gudang_id'           => $array_input['gudang_id'],
                    'pembelian_detail_id' => $draft_actual_detail['po_detail_id'],
                    'pmb_id'              => $id_pmb,
                    'item_id'             => $draft_actual_detail['item_id'],
                    'item_satuan_id'      => $draft_actual_detail['item_satuan_id'],
                    'jumlah'              => $draft_actual_detail['jumlah_diterima'],
                    'tanggal_datang'      => date('Y-m-d H:i:s'),
                    'harga_beli'          => $draft_detail['harga_beli'],
                    'bn_sn_lot'             => $draft_actual_detail['bn_sn_lot'],
                    'expire_date'           => date('Y-m-d', strtotime($draft_actual_detail['expire_date'])),
                    'created_by'          => $this->session->userdata('user_id'),
                    'created_date'        => date('Y-m-d H:i:s'),
                );

                $inventory  = $this->inventory_m->add_data($data_inventory);

                $data_inv_hist_detail = array(
                    'inventory_history_id' => $save_inventory_history,
                    'gudang_id'           => $array_input['gudang_id'],
                    'pembelian_detail_id' => $draft_actual_detail['po_detail_id'],
                    'pmb_id'              => $id_pmb,
                    'item_id'             => $draft_actual_detail['item_id'],
                    'item_satuan_id'      => $draft_actual_detail['item_satuan_id'],
                    'harga_beli'          => $draft_detail['harga_beli'],
                    'initial_stock'       => 0,
                    'change_stock'        => $draft_actual_detail['jumlah_diterima'],
                    'final_stock'        => 0 + $draft_actual_detail['jumlah_diterima'],
                    'total_harga'         => $draft_detail['harga_beli'] * $draft_actual_detail['jumlah_diterima'],
                    'bn_sn_lot'             => $draft_actual_detail['bn_sn_lot'],
                    'expire_date'           => date('Y-m-d', strtotime($draft_actual_detail['expire_date']))
                );

                $inv_history_detail = $this->inventory_history_detail_m->save($data_inv_hist_detail);
                
            }
            $this->draft_pmb_detail_actual_m->delete_by(array('draft_pmb_detail_id' => $draft_detail['draft_pmb_detail_id']));

        }

        foreach ($array_input['items'] as $items) {
            // $data_po_detail = $this->pembelian_detail_m->get_by(array('pembelian_id' => $items['po'], 'item_id' => $items['item_id'], 'status' => 2, 'is_active' => 1, 'jumlah_belum_diterima' != 0), true);
            // $data_po_detail = object_to_array($data_po_detail);

            $data_pmb_detail_real = $this->pmb_detail_m->get_by(array('pmb_id' => $id_pmb, 'item_id' => $items['item_id']));
            $data_pmb_detail_real = object_to_array($data_pmb_detail_real);
            
            $sisa = 0;
            $z = 1;
            foreach ($data_pmb_detail_real as $pmb_detail) {
                $data_po_detail = $this->pembelian_detail_m->get_data_belumterima($po_id,$items['item_id'])->row(0);
                $data_po_detail = object_to_array($data_po_detail);
                
                if($pmb_detail['jumlah_primary'] > $data_po_detail['jumlah_belum_diterima']){

                    $data_pod = array(
                        'jumlah_belum_diterima' => 0,
                        'modified_by'           => $this->session->userdata('user_id'),
                        'modified_date'         => date('Y-m-d H:i:s')
                    );

                    $update_pod = $this->pembelian_detail_m->edit_data($data_pod, $data_po_detail['id']);

                    $data_pmb_po_detail = array(
                        'po_id'     => $po_id,
                        'pmb_id'     => $id_pmb,
                        'po_detail_id'           => $data_po_detail['id'],
                        'pmb_detail_id'          => $pmb_detail['id'],
                        'jumlah'                 => $data_po_detail['jumlah_disetujui'],
                        'item_satuan_id'         => $data_po_detail['item_satuan_id'],
                        'jumlah_primary'         => $data_po_detail['jumlah_belum_diterima'],
                        'item_satuan_primary_id' => $data_po_detail['item_satuan_id_primary'],
                    );

                    $pmb_po_detail_id = $this->pmb_po_detail_m->save($data_pmb_po_detail);

                    

                    $sisa = ($pmb_detail['jumlah_primary'] - $data_po_detail['jumlah_belum_diterima']);


                    if($sisa != 0){

                        $sisa = $sisa;

                        while($sisa > 0){
                            $data_po_detail = $this->pembelian_detail_m->get_data_belumterima($po_id,$items['item_id'])->row(0);
                            $data_po_detail = object_to_array($data_po_detail);

                            if($sisa >= $data_po_detail['jumlah_belum_diterima']){
                                $data_pod = array(
                                    'jumlah_belum_diterima' => 0,
                                    'modified_by'           => $this->session->userdata('user_id'),
                                    'modified_date'         => date('Y-m-d H:i:s')
                                );

                                $update_pod = $this->pembelian_detail_m->edit_data($data_pod, $data_po_detail['id']);

                                $data_pmb_po_detail = array(
                                    'po_id'     => $po_id,
                                    'pmb_id'     => $id_pmb,
                                    'po_detail_id'           => $data_po_detail['id'],
                                    'pmb_detail_id'          => $pmb_detail['id'],
                                    'jumlah'                 => $data_po_detail['jumlah_disetujui'],
                                    'item_satuan_id'         => $data_po_detail['item_satuan_id'],
                                    'jumlah_primary'         => $sisa,
                                    'item_satuan_primary_id' => $data_po_detail['item_satuan_id_primary'],
                                );

                                //insert pmb_po_detail
                                $pmb_po_detail_id = $this->pmb_po_detail_m->save($data_pmb_po_detail);

                                $sisa = $sisa  - $data_po_detail['jumlah_belum_diterima'];


                            }
                            elseif($sisa < $data_po_detail['jumlah_belum_diterima']){
                                $data_pod = array(
                                    'jumlah_belum_diterima' => $data_po_detail['jumlah_belum_diterima'] - $sisa,
                                    'modified_by'           => $this->session->userdata('user_id'),
                                    'modified_date'         => date('Y-m-d H:i:s')
                                );

                                $update_pod = $this->pembelian_detail_m->edit_data($data_pod, $data_po_detail['id']);

                                $data_pmb_po_detail = array(
                                    'po_id'     => $po_id,
                                    'pmb_id'     => $id_pmb,
                                    'po_detail_id'           => $data_po_detail['id'],
                                    'pmb_detail_id'          => $pmb_detail['id'],
                                    'jumlah'                 => $sisa,
                                    'item_satuan_id'         => $data_po_detail['item_satuan_id_primary'],
                                    'jumlah_primary'         => $sisa,
                                    'item_satuan_primary_id' => $data_po_detail['item_satuan_id_primary'],
                                );

                                $pmb_po_detail_id = $this->pmb_po_detail_m->save($data_pmb_po_detail);

                                $sisa = 0;
                            }
                        }
                    }
                }elseif($pmb_detail['jumlah_primary'] < $data_po_detail['jumlah_belum_diterima']){
                    $data_pod = array(
                        'jumlah_belum_diterima' => $data_po_detail['jumlah_belum_diterima'] - $pmb_detail['jumlah_primary'],
                        'modified_by'           => $this->session->userdata('user_id'),
                        'modified_date'         => date('Y-m-d H:i:s')
                    );

                    $update_pod = $this->pembelian_detail_m->edit_data($data_pod, $data_po_detail['id']);

                    $data_pmb_po_detail = array(
                        'po_id'     => $po_id,
                        'pmb_id'     => $id_pmb,
                        'po_detail_id'           => $data_po_detail['id'],
                        'pmb_detail_id'          => $pmb_detail['id'],
                        'jumlah'                 => $pmb_detail['jumlah_diterima'],
                        'item_satuan_id'         => $pmb_detail['item_satuan_id'],
                        'jumlah_primary'         => $pmb_detail['jumlah_primary'],
                        'item_satuan_primary_id' => $data_po_detail['item_satuan_id_primary'],
                    );

                    $pmb_po_detail_id = $this->pmb_po_detail_m->save($data_pmb_po_detail);

                    $sisa = 0;

                }
                elseif($pmb_detail['jumlah_primary'] == $data_po_detail['jumlah_belum_diterima']){
                    $data_pod = array(
                        'jumlah_belum_diterima' => $data_po_detail['jumlah_belum_diterima'] - $pmb_detail['jumlah_primary'],
                        'modified_by'           => $this->session->userdata('user_id'),
                        'modified_date'         => date('Y-m-d H:i:s')
                    );

                    $update_pod = $this->pembelian_detail_m->edit_data($data_pod, $data_po_detail['id']);

                    $data_pmb_po_detail = array(
                        'po_id'     => $po_id,
                        'pmb_id'     => $id_pmb,
                        'po_detail_id'           => $data_po_detail['id'],
                        'pmb_detail_id'          => $pmb_detail['id'],
                        'jumlah'                 => $pmb_detail['jumlah_diterima'],
                        'item_satuan_id'         => $pmb_detail['item_satuan_id'],
                        'jumlah_primary'         => $pmb_detail['jumlah_primary'],
                        'item_satuan_primary_id' => $data_po_detail['item_satuan_id_primary'],
                    );

                    $pmb_po_detail_id = $this->pmb_po_detail_m->save($data_pmb_po_detail);

                    $sisa = 0;

                    
                }

            }

            $delete_data = $this->draft_pmb_m->delete_by(array('draft_pmb_id' => $array_input['draft_id']));
            $delete_data_pmb_detail = $this->draft_pmb_detail_m->delete_by(array('draft_pmb_id' => $array_input['draft_id']));
            $delete_data_pmb_po = $this->draft_pmb_po_m->delete_by(array('draft_pmb_id' => $array_input['draft_id']));

            foreach ($array_input['po'] as $po) {

                // $data_po_kirim['is_kirim'] = 1;
                // $data_po_kirim['status'] = 4;

                // $po = $this->pembelian_m->edit_data($data_po_kirim, $po['po_id']);
                
                $data_po_detail = $this->pembelian_detail_m->get_by(array('pembelian_id' => $po['po_id'], 'status' => 2, 'is_active' => 1, 'jumlah_belum_diterima !=' => 0));

                $data_retur = $this->retur_pembelian_m->get_by(array('pembelian_id' => $po['po_id']), true);

                if(count($data_po_detail) == 0)
                {
                    $data_po['is_kirim'] = 1;
                    $data_po['status'] = 5;

                    $po = $this->pembelian_m->edit_data($data_po, $po['po_id']);
                    $array_retur = array(
                        'status' => 3
                    );
                    $edit_retur = $this->retur_pembelian_m->edit_data($array_retur, $data_retur->id);
                }if(count($data_po_detail) != 0)
                {
                    $data_po['is_kirim'] = 1;
                    $data_po['status'] = 4;

                    $po = $this->pembelian_m->edit_data($data_po, $po['po_id']);

                    $array_retur = array(
                        'status' => 2
                    );
                    $edit_retur = $this->retur_pembelian_m->edit_data($array_retur, $data_retur->id);
                }
            }

        }

        if ($save_pmb) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data barang datang berhasil ditambahkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }


        redirect("gudang/barang_datang");
    }

    public function modal_add()
    {

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_add.php');
    
    }

    public function modal_add_po($id_draft, $supplier_id, $gudang_id)
    {
        $data = array(
            'id_draft'    => $id_draft,
            'supplier_id' => $supplier_id,
            'gudang_id'   => $gudang_id
        );

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_add_po.php', $data);
    
    }

    public function modal_jumlah_pesan($supplier_id, $item_id, $item_satuan_id, $po_id)
    {
        $data = array(
            'po_id'          => $po_id,
            'supplier_id'    => $supplier_id,
            'item_id'        => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_jumlah_pesan.php', $data);
    
    }

    public function modal_jumlah_pesan_supplier_lain($supplier_id, $item_id, $item_satuan_id)
    {
        $data = array(
            'supplier_id' => $supplier_id,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_jumlah_pesan_supplier_lain.php', $data);
    
    }

    public function modal_jumlah_terima($supplier_id, $item_id, $item_satuan_id)
    {
        $data = array(
            'supplier_id' => $supplier_id,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_jumlah_terima.php', $data);
    
    }

    public function modal_jumlah_terima_supplier_lain($supplier_id, $item_id, $item_satuan_id)
    {
        $data = array(
            'supplier_id' => $supplier_id,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_jumlah_terima_supplier_lain.php', $data);
    
    }

    public function modal_identitas($item_id, $item_satuan_id, $row)
    {
        $data = array(
            'row_id' => $row,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_identitas.php', $data);
    
    }

    public function modal_identitas_view($pmb_id, $item_id, $item_satuan_id, $row)
    {
        $data = array(
            'pmb_id' => $pmb_idp,
            'row_id' => $row,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_identitas_view.php', $data);
    
    }

    public function modal_pemisahan_item($item_id, $item_satuan_id, $jumlah, $row)
    {
        $data = array(
            'jumlah' => $jumlah,
            'row_id' => $row,
            'item_id' => $item_id,
            'item_satuan_id' => $item_satuan_id
        );

        $this->load->view('gudang/barang_datang_farmasi/modal/modal_pemisahan_item.php', $data);
    
    }

    public function select_po($po_id, $supplier_id, $gudang_id)
    {
       
        $last_id = $this->draft_pmb_m->get_last_id()->result_array();

        $data_draft_pmb = array(
            'draft_pmb_id'  => intval($last_id[0]['last_id'])+1,
            'gudang_id'     => $gudang_id,
            'supplier_id'   => $supplier_id,
            'diproses_oleh' => $this->session->userdata('user_id'),
            'tanggal'       => date('Y-m-d H:i:s'),
            'diproses_oleh' => $this->session->userdata('user_id'),
            'is_active'     => 1
        );

        $save_draft_pmb = $this->draft_pmb_m->add_data($data_draft_pmb);
        // die(dump($this->db->last_query()));

        $last_pmb_po_id = $this->draft_pmb_po_m->get_last_id()->result_array();

        $data_draft_pmb_po = array(
            'draft_pmb_po_id'   => intval($last_pmb_po_id[0]['last_id'])+1,
            'draft_pmb_id'      => intval($last_id[0]['last_id'])+1,
            'po_id'             => $po_id
        );

        $save_draft_pmb_po = $this->draft_pmb_po_m->add_data($data_draft_pmb_po);
        
        $get_data_po = $this->pembelian_detail_m->get_data_po_detail($po_id)->result_array();

        foreach ($get_data_po as $data_po) {
            $last_detail_id = $this->draft_pmb_detail_m->get_last_id()->result_array();
            
            $data_draft_pmb_detail = array(
                'draft_pmb_detail_id'   => intval($last_detail_id[0]['last_id'])+1,
                'draft_pmb_id'          => intval($last_id[0]['last_id'])+1,
                'po_id'                 => $data_po['pembelian_id'],
                'po_detail_id'          => $data_po['id'],
                'item_id'               => $data_po['item_id'],
                'item_satuan_id'        => $data_po['item_satuan_id'],
                'jumlah_diterima'       => $data_po['jumlah_diterima'],
                'harga_beli'            => $data_po['harga_beli'],
            );

            $save_draft_pmb_detail = $this->draft_pmb_detail_m->add_data($data_draft_pmb_detail);

        }

        // $url_serialize = urlencode(base64_encode(serialize($data)));
        // $url = base_url().$url_serialize;
        $draft_pmb_id_last = intval($last_id[0]['last_id'])+1;
        // die(dump($draft_pmb_id_last));

        redirect("gudang/barang_datang_farmasi/proses/$gudang_id/$supplier_id/$draft_pmb_id_last");
        // die_dump($url);
    }

    public function generate_pembelian_id()
    {
        $data_id = $this->input->post('data_id');
        $gudang_id = $this->input->post('gudang_id');
        $supplier_id = $this->input->post('supplier_id');

        // die_dump($data_id);
        $last_id = $this->draft_pmb_m->get_last_id()->result_array();

        $data_draft_pmb = array(
            'draft_pmb_id'  => intval($last_id[0]['last_id'])+1,
            'gudang_id'     => $gudang_id,
            'supplier_id'   => $supplier_id,
            'diproses_oleh' => $this->session->userdata('user_id'),
            'tanggal'       => date('Y-m-d H:i:s'),
            'diproses_oleh' => $this->session->userdata('user_id'),
            'is_active'     => 1
        );

        $save_draft_pmb = $this->draft_pmb_m->add_data($data_draft_pmb);
        // die(dump($this->db->last_query()));
        $id= '';
        foreach ($data_id as $array) {
           $data[] = $array; 
           $id .= $array."','";
            
            $last_pmb_po_id = $this->draft_pmb_po_m->get_last_id()->result_array();

            $data_draft_pmb_po = array(
                'draft_pmb_po_id'   => intval($last_pmb_po_id[0]['last_id'])+1,
                'draft_pmb_id'      => intval($last_id[0]['last_id'])+1,
                'po_id'             => $array
            );

           $save_draft_pmb_po = $this->draft_pmb_po_m->add_data($data_draft_pmb_po);
        }
        // die_dump($array);

        // $data['draft_id'] = intval($last_id[0]['last_id'])+1;

        // die_dump($data);
        $id = rtrim($id,"','");
        $get_data_po = $this->pembelian_detail_m->get_data_pembelian_detail($id)->result_array();

        foreach ($get_data_po as $data_po) {
            $last_detail_id = $this->draft_pmb_detail_m->get_last_id()->result_array();
            
            $data_draft_pmb_detail = array(
                'draft_pmb_detail_id'   => intval($last_detail_id[0]['last_id'])+1,
                'draft_pmb_id'          => intval($last_id[0]['last_id'])+1,
                'po_id'                 => $data_po['pembelian_id'],
                'po_detail_id'          => $data_po['id'],
                'item_id'               => $data_po['item_id'],
                'item_satuan_id'        => $data_po['item_satuan_id'],
                'jumlah_diterima'       => $data_po['jumlah_diterima'],
                'harga_beli'            => $data_po['harga_beli'],
            );

            $save_draft_pmb_detail = $this->draft_pmb_detail_m->add_data($data_draft_pmb_detail);
        // die(dump($this->db->last_query()));

        }

        // $url_serialize = urlencode(base64_encode(serialize($data)));
        // $url = base_url().$url_serialize;
        $draft_pmb_id_last = array(
            'draft_pmb_id_last' => intval($last_id[0]['last_id'])+1
        );
        // die(dump($draft_pmb_id_last));

        echo json_encode($draft_pmb_id_last);
        // die_dump($url);
    }

    public function generate_pembelian_id_draft()
    {
        $data_id = $this->input->post('data_id');
        $draft_id = $this->input->post('draft_id');
        $gudang_id = $this->input->post('gudang_id');
        $supplier_id = $this->input->post('supplier_id');

        // die_dump($draft_id);
        
        $id= '';
        foreach ($data_id as $array) {
           $data[] = $array; 
           $id .= $array.',';
            
            $search_data = $this->draft_pmb_po_m->get_by(array('draft_pmb_id' => $draft_id, 'po_id' => $array));
            $seleksi = object_to_array($search_data);
            if(!$search_data)
            {
                $last_pmb_po_id = $this->draft_pmb_po_m->get_last_id()->result_array();

                $data_draft_pmb_po = array(
                    'draft_pmb_po_id'   => intval($last_pmb_po_id[0]['last_id'])+1,
                    'draft_pmb_id'      => intval($draft_id),
                    'po_id'             => $array
                );

               $save_draft_pmb_po = $this->draft_pmb_po_m->save($data_draft_pmb_po);
            }
            
        }
        // die_dump($this->db->last_query());

        // $data['draft_id'] = intval($last_id[0]['last_id'])+1;

        // die_dump($data);
        $delete = $this->draft_pmb_detail_m->delete($draft_id);

        $get_data_po = $this->pembelian_detail_m->get_data_pembelian_detail($id)->result_array();

        foreach ($get_data_po as $data_po) {
            $last_detail_id = $this->draft_pmb_detail_m->get_last_id()->result_array();
            
            $data_draft_pmb_detail = array(
                'draft_pmb_detail_id'   => intval($last_detail_id[0]['last_id'])+1,
                'draft_pmb_id'          => intval($draft_id),
                'item_id'               => $data_po['item_id'],
                'item_satuan_id'        => $data_po['item_satuan_id'],
                'jumlah_diterima'       => $data_po['jumlah_diterima'],
                'harga_beli'            => $data_po['harga_beli'],
            );

            $save_draft_pmb_detail = $this->draft_pmb_detail_m->save($data_draft_pmb_detail);
        }

        // $url_serialize = urlencode(base64_encode(serialize($data)));
        // $url = base_url().$url_serialize;
        
        echo json_encode($draft_id);
        // die_dump($url);
    }

    public function get_konversi_item()
    {
        $item_id = $this->input->post('item_id');
        $item_satuan_id = $this->input->post('item_satuan_id');
        $satuan_convert = $this->input->post('satuan_convert');
        $flag = $this->input->post('flag');
        // die_dump($id_awal);

        if ($flag == 'down_convert') {
            $konversi_item = $this->item_satuan_m->get_jumlah($item_id, $item_satuan_id, $satuan_convert)->result_array();
        }else{
            $konversi_item = $this->item_satuan_m->get_jumlah_up_convert($item_id, $satuan_convert ,$item_satuan_id )->result_array();
        }
        // die_dump($this->db->last_query());        
        // $hasil_konversi_item = object_to_array($konversi_item);

        // die_dump($konversi_item);
        echo json_encode($konversi_item);
    }

    public function save_draft_detail()
    {
        $array_input = $this->input->post();

        // die(dump($array_input));
        $user_id = $this->session->userdata('user_id');
        $jml_pesan = $array_input['jumlah_pesan'];
        $jml_item = 0;
        $actual_id = 0;
        for($i=0;$i<=$array_input['jml_row'];$i++) 
        {
            if(isset($array_input['identitas_'.$i][$i]['draft_actual_id']))
            { 
                $jml_item = $jml_item + $array_input['identitas_'.$i][$i]['nilai_konversi'] * $array_input['identitas_'.$i][$i]['jumlah']; 
            }
        }

        if($jml_item <= $jml_pesan)
        {
            for($i=0;$i<=$array_input['jml_row'];$i++) 
            {
                if(isset($array_input['identitas_'.$i][$i]['draft_actual_id']))
                {
                    if($array_input['identitas_'.$i][$i]['draft_actual_id'] == '' && $array_input['identitas_'.$i][$i]['is_active'] == 1 && $array_input['identitas_'.$i][$i]['jumlah'] != 0)
                    {
                        $draft_pmb_detail_actual_id = $this->draft_pmb_detail_actual_m->get_last_id()->result_array();
                        $draft_pmb_identitas_id = $this->draft_pmb_identitas_m->get_last_id()->result_array();
                        // die_dump($draft_pmb_detail_actual_id);
                        $data_detail_actual = array(
                            'draft_pmb_actual_id' => intval($draft_pmb_detail_actual_id[0]['last_id'])+1,
                            'draft_pmb_detail_id' => $array_input['draft_detail_id'],
                            'po_detail_id'        => $array_input['po_detail_id'],
                            'item_id'             => $array_input['item_id'],
                            'item_satuan_id'      => $array_input['identitas_'.$i][$i]['satuan'],
                            'jumlah_diterima'     => $array_input['identitas_'.$i][$i]['jumlah'],
                            'jumlah_konversi'     => $array_input['identitas_'.$i][$i]['nilai_konversi'] * $array_input['identitas_'.$i][$i]['jumlah'],
                            'bn_sn_lot'           => $array_input['identitas_'.$i][$i]['bn_sn_lot'],
                            'expire_date'           => date('Y-m-d', strtotime($array_input['identitas_'.$i][$i]['expire_date'])),
                        );

                        $save_draft_pmb_actual = $this->draft_pmb_detail_actual_m->save($data_detail_actual);  

                        $actual_id = intval($draft_pmb_detail_actual_id[0]['last_id'])+1;
                    }

                    if($array_input['identitas_'.$i][$i]['draft_actual_id'] != '' && $array_input['identitas_'.$i][$i]['is_active'] == 1)
                    {     
                        $data_detail_actual = array(
                            'po_detail_id'        => $array_input['po_detail_id'],
                            'item_id'             => $array_input['item_id'],
                            'item_satuan_id'      => $array_input['identitas_'.$i][$i]['satuan'],
                            'jumlah_diterima'     => $array_input['identitas_'.$i][$i]['jumlah'],
                            'jumlah_konversi'     => $array_input['identitas_'.$i][$i]['nilai_konversi'] * $array_input['identitas_'.$i][$i]['jumlah'],
                            'bn_sn_lot'           => $array_input['identitas_'.$i][$i]['bn_sn_lot'],
                            'expire_date'           => date('Y-m-d', strtotime($array_input['identitas_'.$i][$i]['expire_date'])),
                        );

                        $wheres['draft_pmb_actual_id'] = $array_input['identitas_'.$i][$i]['draft_actual_id'];
                        $save_draft_pmb_actual = $this->draft_pmb_detail_actual_m->update_by($user_id,$data_detail_actual,$wheres);  
                        $actual_id  =  $array_input['identitas_'.$i][$i]['draft_actual_id'];          
                    }
                    if($array_input['identitas_'.$i][$i]['draft_actual_id'] != '' && $array_input['identitas_'.$i][$i]['is_active'] == 0)
                    {
                        $save_draft_pmb_actual = $this->draft_pmb_detail_actual_m->delete_by(array('draft_pmb_actual_id' => $array_input['identitas_'.$i][$i]['draft_actual_id'])); 
                        
                        $actual_id  =  $array_input['identitas_'.$i][$i]['draft_actual_id'];
                    }
                   
                    $data = array(
                        'id' => $actual_id,
                        'jml_item' => $jml_item
                    );  
                }
            }
        }elseif($jml_item > $jml_pesan){
            $data = array(
                'id' => $actual_id,
                'jml_item' => $jml_item
            );
        }

        

        
        echo json_encode($data);
    }

    public function delete_draft($id)
    {
           
        // die_dump($id);
        $this->draft_pmb_m->delete_data($id);

        $delete_data = array('draft_pmb_id' => $id);
        $this->draft_pmb_detail_m->delete_all_by($delete_data);

        // die_dump($this->db->last_query());
        
        if ($delete_data) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate(" Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("gudang/barang_datang");
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */