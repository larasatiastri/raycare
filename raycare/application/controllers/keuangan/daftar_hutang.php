<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_hutang extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    // protected $menu_id = 'keuangan/daftar_hutang';                
    protected $menu_id = '5c277dc4f701a7f661a9e21ddf6c2a6d';            

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

        $this->load->model('keuangan/perubahan_modal/o_s_hutang_m');
        $this->load->model('keuangan/perubahan_modal/pembayaran_status_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/daftar_hutang/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Daftar Hutang', $this->session->userdata('language')), 
            'header'         => translate('Daftar Hutang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/daftar_hutang/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);

        $assets = array();
        $config = 'assets/keuangan/daftar_hutang/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        // $form_data = $this->cabang__m->get($id);
        $form_data = $this->pembayaran_hutang_m->get_data($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' | '.translate("View Daftar Hutang", $this->session->userdata("language")), 
            'header'         => translate("View Daftar Hutang Ke Supplier", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/daftar_hutang/view',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id                         //table primary key value
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
        $result = $this->pembayaran_status_m->get_datatable_ttf();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die(dump($records));

        $total_hutang=0;
        $i=0;
        $count = count($records->result_array());
        $input_total = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            $tipe = '';
          
            $total_hutang = $total_hutang + $row['nominal'];
            
            if($i == $count){
                $input_total='<input id="input_total" type="hidden" value='.$total_hutang.'>';
            }

            $info = formatrupiah($row['nominal']);
            // die_dump($faktur_po);
            $output['data'][] = array(

                '<div class="text-left">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-left">'.$row['nama_supplier'].'</div>',
                '<div class="text-right">'.$info.$input_total.'</div>'
            );
               
         
        }

        echo json_encode($output);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing_po()
    {        
        $result = $this->pembayaran_status_m->get_datatable_po();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die(dump($records));

        $total_hutang=0;
        $i=0;
        $count = count($records->result_array());
        $input_total = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            $tipe = '';
          
            $total_hutang = $total_hutang + $row['nominal'];
            
            if($i == $count){
                $input_total='<input id="input_total_po" type="hidden" value='.$total_hutang.'>';
            }

            $info = formatrupiah($row['nominal']);
            // die_dump($faktur_po);
            $output['data'][] = array(

                '<div class="text-left">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-left">'.$row['nama_supplier'].'</div>',
                '<div class="text-right">'.$info.$input_total.'</div>'
            );
               
         
        }

        echo json_encode($output);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing_rb()
    {        
        $result = $this->pembayaran_status_m->get_datatable_reimburse();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        // die(dump($records));

        $total_hutang=0;
        $i=0;
        $count = count($records->result_array());
        $input_total = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            $tipe = '';
          
            $total_hutang = $total_hutang + $row['nominal'];
            
            if($i == $count){
                $input_total='<input id="input_total_rb" type="hidden" value='.$total_hutang.'>';
            }

            $info = formatrupiah($row['nominal']);
            // die_dump($faktur_po);
            $output['data'][] = array(

                '<div class="text-left">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-right">'.$info.$input_total.'</div>'
            );
               
         
        }

        echo json_encode($output);
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */