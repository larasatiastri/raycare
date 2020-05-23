<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arus_barang extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '982bd91087b83f9c70ecfb1ab037df5c';                  // untuk check bit_access
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

        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_identitas_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/identitas_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('apotik/pengeluaran_barang/inventory_m');
        $this->load->model('apotik/pengeluaran_barang/inventory_identitas_m');
        $this->load->model('apotik/pengeluaran_barang/inventory_identitas_detail_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/laporan/arus_barang/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Arus Keluar Masuk Barang', $this->session->userdata('language')), 
            'header'         => translate('Arus Keluar Masuk Barang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laporan/arus_barang/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    function get_barang_keluar($tgl_awal=null,$tgl_akhir=null,$gudang_id,$kategori=null,$sub_kategori = null, $item_id = null)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $response = new stdClass;
        $response->success = false;

        $sql = " SELECT
                    item.kode AS kode_item,
                    item.nama AS nama_item,
                    item_satuan.nama AS nama_satuan,
                    SUM(
                        inventory_history_detail.change_stock * (-1)
                    ) AS jumlah
                FROM
                    inventory_history_detail
                LEFT JOIN item ON inventory_history_detail.item_id = item.id
                LEFT JOIN item_satuan ON inventory_history_detail.item_satuan_id = item_satuan.id
                LEFT JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id
                LEFT JOIN item_kategori ON item_sub_kategori.item_kategori_id = item_kategori.id
                WHERE
                    inventory_history_detail.change_stock <= 0
                AND
                    inventory_history_detail.gudang_id = '$gudang_id'
                AND date(
                    inventory_history_detail.created_date
                ) >= ?
                AND date(
                    inventory_history_detail.created_date
                ) <= ?";

        if($kategori!=0 && $sub_kategori!=0 )
        {
            $sql .= " AND item.item_sub_kategori = $sub_kategori AND item_sub_kategori.item_kategori_id = $kategori";
        }
        else if($kategori!=0 && $sub_kategori==0)
        {
            $sql .= " AND item_sub_kategori.item_kategori_id = $kategori";
            
        }   
        if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $sql .= " AND inventory_history_detail.item_id IN ($item_array)";
        }
        $sql .= " GROUP BY
                    inventory_history_detail.item_id,
                    inventory_history_detail.item_satuan_id
                ORDER BY
                    inventory_history_detail.created_date ASC";

        $query = $this->db->query(
            $sql,
            array( $tgl_awal,$tgl_akhir)
        );
        
        $response->success = true;
        $response->rows = $query->result();
        

        die(json_encode($response));
    }

    function get_barang_masuk($tgl_awal=null,$tgl_akhir=null,$gudang_id,$kategori=null,$sub_kategori = null, $item_id = null)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $response = new stdClass;
        $response->success = false;

        $sql = " SELECT
                    item.kode AS kode_item,
                    item.nama AS nama_item,
                    item_satuan.nama AS nama_satuan,
                    SUM(
                        inventory_history_detail.change_stock
                    ) AS jumlah
                FROM
                    inventory_history_detail
                LEFT JOIN item ON inventory_history_detail.item_id = item.id
                LEFT JOIN item_satuan ON inventory_history_detail.item_satuan_id = item_satuan.id
                LEFT JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id
                LEFT JOIN item_kategori ON item_sub_kategori.item_kategori_id = item_kategori.id
                WHERE
                    inventory_history_detail.change_stock > 0
                AND
                    inventory_history_detail.gudang_id = '$gudang_id'
                AND date(
                    inventory_history_detail.created_date
                ) >= ?
                AND date(
                    inventory_history_detail.created_date
                ) <= ?";
        if($kategori!=0 && $sub_kategori!=0)
        {
            $sql .= " AND item.item_sub_kategori = $sub_kategori AND item_sub_kategori.item_kategori_id = $kategori";
        }
        else if($kategori!=0 && $sub_kategori==0)
        {
            $sql .= " AND item_sub_kategori.item_kategori_id = $kategori";
            
        }   
        if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $sql .= " AND inventory_history_detail.item_id IN ($item_array)";
        }
        $sql .= "
                GROUP BY
                    inventory_history_detail.item_id,
                    inventory_history_detail.item_satuan_id
                ORDER BY
                    inventory_history_detail.created_date ASC";

        $query = $this->db->query(
            $sql,
            array( $tgl_awal,$tgl_akhir)
        );
        
        $response->success = true;
        $response->rows = $query->result();
        //$response->rows = $this->db->last_query();
        

        die(json_encode($response));
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing_barang_masuk($tgl_awal=null,$tgl_akhir=null,$tipe,$gudang_id,$kategori=null,$sub_kat = null, $item_id = null)
    {        
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $result = $this->inventory_history_detail_m->get_datatable_report($tgl_awal,$tgl_akhir,$tipe,$gudang_id,$kategori,$sub_kat,$item_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records      = $result->records;
        $i = 1;
        foreach($records->result_array() as $row)
        {     
            $inv_history = $this->inventory_history_detail_m->get_by(array('item_id' => $row['item_id'], 'item_satuan_id' => $row['item_satuan_id'], 'change_stock >' => 0 ,'date(created_date)' => date('Y-m-d', strtotime($row['tanggal']))));

            $id = '';
            foreach ($inv_history as $key => $history) {
                $id .= $history->id.',';
            }
            $id = urlencode(base64_encode($id));

            $output['data'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['kode_item'].'</div>', 
                '<div class="text-left">'.$row['nama_item'].'</div>', 
                '<div class="text-left"><a data-toggle="modal" data-target="#modal_detail" href="'.base_url().'laporan/arus_barang/modal_detail/'.$id.'" >'.abs($row['jumlah']).' '.$row['nama_satuan'].'</a></div>', 
                '<div class="text-left"><a data-toggle="modal" data-target="#modal_detail_stok" href="'.base_url().'laporan/arus_barang/modal_detail_stok/'.$row['id_item'].'/'.$row['id_item_satuan'].'/'.$gudang_id.'" >'.$row['stock'].' '.$row['nama_satuan'].'</a></div>',             
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_barang_keluar($tgl_awal=null,$tgl_akhir=null,$tipe,$gudang_id,$kategori=null,$sub_kat = null, $item_id = null)
    {        
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $result = $this->inventory_history_detail_m->get_datatable_report($tgl_awal,$tgl_akhir,$tipe,$gudang_id,$kategori,$sub_kat,$item_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records      = $result->records;
        $i = 1;
        foreach($records->result_array() as $row)
        {     
            $inv_history = $this->inventory_history_detail_m->get_by(array('item_id' => $row['item_id'], 'item_satuan_id' => $row['item_satuan_id'], 'change_stock <=' => 0 ,'date(created_date)' => date('Y-m-d', strtotime($row['tanggal']))));

            $id = '';
            foreach ($inv_history as $key => $history) {
                $id .= $history->id.',';
            }
            $id = urlencode(base64_encode($id));

            $output['data'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['kode_item'].'</div>', 
                '<div class="text-left">'.$row['nama_item'].'</div>', 
                '<div class="text-left"><a data-toggle="modal" data-target="#modal_detail" href="'.base_url().'laporan/arus_barang/modal_detail/'.$id.'" >'.abs($row['jumlah']).' '.$row['nama_satuan'].'</a></div>', 
                '<div class="text-left"><a data-toggle="modal" data-target="#modal_detail_stok" href="'.base_url().'laporan/arus_barang/modal_detail_stok/'.$row['id_item'].'/'.$row['id_item_satuan'].'/'.$gudang_id.'" >'.$row['stock'].' '.$row['nama_satuan'].'</a></div>',                         
            );
            $i++;
        }

        echo json_encode($output);
    }
    public function cetak_pdf($tgl_awal=null, $tgl_akhir=null)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $this->tindakan_hd_m->csv($tgl_awal,$tgl_akhir);
    }

    public function modal_detail($id)
    {
        $body = array(
            'id' => $id
        );

        $this->load->view('laporan/arus_barang/modal_detail', $body);
    }

    public function modal_detail_stok($item_id,$item_satuan_id, $gudang_id)
    {
        $data_item = $this->item_m->get($item_id);
        $data_item_satuan = $this->item_satuan_m->get($item_satuan_id);
        $data_item_identitas = $this->item_identitas_m->data_item_identitas($item_id)->result_array();
        $data_item_identitas = (count($data_item_identitas) != 0)?object_to_array($data_item_identitas):'';

        $data_inventory = $this->inventory_m->get_data_inventory_gudang($item_id,$item_satuan_id, $gudang_id)->result_array();
        $data_inventory = (count($data_inventory) != 0)?object_to_array($data_inventory):'';


        $body = array(
            'item_id'             => $item_id,
            'item_satuan_id'      => $item_satuan_id,
            'data_item'           => object_to_array($data_item),
            'data_item_satuan'    => object_to_array($data_item_satuan),
            'data_item_identitas' => $data_item_identitas,
            'data_inventory'      => $data_inventory,
        );

        $this->load->view('laporan/arus_barang/modal_identitas', $body);
    }

    public function get_sub_kategori()
    {
        $id_kategori = $this->input->post('id_kategori');

        $response = new stdClass;

        $sub_kategori = $this->item_sub_kategori_m->get_data_sub_kategori($id_kategori)->result_array();
        //die_dump($this->db->last_query());        
        $item_sub_kategori  = object_to_array($sub_kategori);

        if($id_kategori == 0){
            $item = $this->item_m->get_by(array('is_active' => '1'));
        }else{
            $id_sub_kategori = '';
            foreach ($item_sub_kategori as $row) {
                $id_sub_kategori .= $row['id'].', ';
            }
            $item = $this->item_m->get_data_sub_kategori($id_sub_kategori)->result_array();
        }

        $response->sub_kategori = $item_sub_kategori;
        $response->item = object_to_array($item);

        //die_dump($this->db->last_query());
        die(json_encode($response));
    }

    public function get_item()
    {
        if($this->input->is_ajax_request()){
            $id_sub_kategori = $this->input->post('id_sub_kategori');
            $id_kategori = $this->input->post('id_kategori');

            if($id_sub_kategori != 0){
               $item = $this->item_m->get_by(array('item_sub_kategori' => $id_sub_kategori, 'is_active' => 1));
            }elseif($id_sub_kategori == 0){
                if($id_kategori == 0){
                    $item = $this->item_m->get_by(array('is_active' => '1'));
                }elseif($id_kategori != 0){
                    $sub_kategori = $this->item_sub_kategori_m->get_data_sub_kategori($id_kategori)->result_array();
                    $id_sub_kategori = '';
                    foreach ($sub_kategori as $row) {
                        $id_sub_kategori .= $row['id'].', ';
                    }
                    $item = $this->item_m->get_data_sub_kategori($id_sub_kategori)->result_array();
                }
                
            }

            
            $item = object_to_array($item);
            echo json_encode($item);

        }
    }



}

/* End of file spesialis.php */
/* Location: ./application/controllers/spesialis/spesialis.php */