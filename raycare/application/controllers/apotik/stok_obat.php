<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_obat extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '0434cff65c1c6a5d184f07f19abfd36e';                  // untuk check bit_access

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

        $this->load->model('master/cabang_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('apotik/buffer_stock_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/stok_obat/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Buffer Stock', $this->session->userdata('language')), 
            'header'         => translate('Buffer Stock', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stok_obat/index',
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
        $cabang_id = $this->session->userdata('cabang_id');

        $result = $this->buffer_stock_m->get_datatable_stock_dokter($cabang_id);
        
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
            $harga = 0;

            $item_harga = $this->item_harga_m->get_harga_item_satuan($row['id'],$row['item_satuan_id'],$cabang_id)->result_array();

            if(count($item_harga) != 0){
                $harga = $item_harga[0]['harga'];
            }

            $date = '';
            $stok = 0;
            if(isset($row['expire_date'])){
                $date = date('d M Y', strtotime($row['expire_date']));
            }
            if(isset($row['stok'])){
                $stok = $row['stok'];
            }


            $output['data'][] = array(
               
                '<div class="text-left">'.$row['kode'].'</div>',
                $row['nama'],
                '<div class="text-left">'.$stok.' '.$row['unit'].'</div>',
                '<div class="text-center">'.$date.'</div>',
                '<div class="text-right">'.formatrupiah($harga).' / '.$row['unit'].'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    

    public function get_sub_kategori(){

        $kategori_id = $this->input->post('kategori_id');
        
        $result = $this->item_sub_kategori_m->get_by(array('item_kategori_id'=>$kategori_id,'is_active'=>1));
        $result_array=object_to_array($result);

        echo json_encode($result_array);
    }
}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */