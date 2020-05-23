<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kartu_stok_item extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd446bfa745c5947052e30dab2964149f';                  // untuk check bit_access
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
        $this->load->model('gudang/barang_datang/pmb_m');
        $this->load->model('gudang/barang_datang/pembelian_m');
        $this->load->model('gudang/supplier_m');
        $this->load->model('gudang/barang_datang/pmb_po_detail_m');
        $this->load->model('apotik/transfer_item/transfer_item_m');

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/laporan/kartu_stok_item/index';
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
            'content_view'   => 'laporan/kartu_stok_item/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function get_data_report($tgl_awal=null,$tgl_akhir=null,$gudang_id,$kategori=null,$sub_kategori = null, $item_id = null)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }
        $response = new stdClass;
        $response->success = false;

        $sql = "SELECT
                inventory_history_detail.inventory_history_id,
                date(
                    inventory_history_detail.created_date
                ) AS tanggal,
                item.kode,
                item.id as item_id,
                item.nama,
                item_satuan.nama as satuan,
                item_satuan.id as satuan_id,
                bn_sn_lot AS bn_sn_lot,
                change_stock AS masuk,
                harga_beli AS harga_masuk,
                '' AS keluar,
                '' AS harga_keluar,
                final_stock,
                harga_beli,
                inventory_history_detail.created_date  as bikin
                
            FROM
                `inventory_history_detail`
            JOIN item ON inventory_history_detail.item_id = item.id
            JOIN item_satuan ON inventory_history_detail.item_satuan_id = item_satuan.id
            WHERE
                gudang_id = '$gudang_id'
            AND change_stock > 0
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

            $sql .= "UNION
                (
                    SELECT
                        inventory_history_detail.inventory_history_id,
                        date(
                            inventory_history_detail.created_date
                        ) AS tanggal,
                        item.kode,
                        item.id as item_id,
                        item.nama,
                        item_satuan.nama as satuan,
                        item_satuan.id as satuan_id,
                        bn_sn_lot AS bn_sn_lot,
                        '' AS masuk,
                        '' AS harga_masuk,
                        (change_stock * - 1) AS keluar,
                        harga_beli AS harga_keluar,
                        final_stock,
                        harga_beli,
                        inventory_history_detail.created_date  as bikin
                        
                    FROM
                        `inventory_history_detail`
                    JOIN item ON inventory_history_detail.item_id = item.id
                    JOIN item_satuan ON inventory_history_detail.item_satuan_id = item_satuan.id
                    WHERE
                        gudang_id = '$gudang_id'
                    AND change_stock < 0
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
            $sql .= " )
            ORDER BY
                bikin ASC;";

        $query = $this->db->query(
            $sql,
            array( $tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir)
        );
        
        $response->success = true;
        $response->rows = $query->result();
        $response->query = $this->db->last_query();
        

        die(json_encode($response));
    }

   

    public function get_info($inventory_history_id, $item_id, $satuan_id)
    {
        $data_history = $this->inventory_history_m->get_by(array('id' => $inventory_history_id), true);
        $item = $this->item_m->get($item_id);
        $satuan = $this->item_satuan_m->get($satuan_id);

        $body = array(
            'data_history' => object_to_array($data_history),
            'item' => object_to_array($item),
            'satuan' => object_to_array($satuan)
        );


        $this->load->view('laporan/kartu_stok_item/modal_detail', $body);
    }

    


}

/* End of file spesialis.php */
/* Location: ./application/controllers/spesialis/spesialis.php */