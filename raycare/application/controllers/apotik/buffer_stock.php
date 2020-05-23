<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buffer_stock extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'c6f5d4a7e2cceb27e82c21c1571f0aa1';                  // untuk check bit_access

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
        $this->load->model('apotik/gudang_m');
        $this->load->model('apotik/buffer_stock_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/buffer_stock/index';
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
            'content_view'   => 'apotik/buffer_stock/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

  
    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing($gudang_id, $kategori_id, $sub_kategori_id,$tipe)
    {        
        if($kategori_id != 0 && $sub_kategori_id == 0)
        {
            $sub_kategori_id = array();
            $item_sub = $this->item_sub_kategori_m->get_by(array('item_kategori_id' => $kategori_id, 'is_active' => 1));
            foreach ($item_sub as $row_sub) 
            {
                $sub_kategori_id[] = $row_sub->id;
            }

        }
        if($tipe == 1){
            $result = $this->buffer_stock_m->get_datatable_buffer_stock($gudang_id, $kategori_id, $sub_kategori_id);
        }else{
            $result = $this->item_m->get_datatable_buffer_stock_kosong($gudang_id, $kategori_id, $sub_kategori_id);
        }

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
            $status = '<div class="text-left"><span class="label label-md label-info">Normal</span></div>';

            $wheres['item_id'] = $row['id'];
            if($gudang_id != 0)
            {
                $wheres['gudang_id'] = $gudang_id;
            }

            $inventory = $this->buffer_stock_m->get_by($wheres);
            
            $date = '';
            $stok = 0;
            if(isset($row['expire_date'])){
                $date = date('d M Y', strtotime($row['expire_date']));
            }
            if(isset($row['stok'])){
                $stok = $row['stok'];
            }

            if($row['stok'] <= ($row['buffer_stok']*2) && $row['stok'] > $row['buffer_stok']){
                $status = '<div class="text-left"><span class="label label-md label-warning">Peringatan</span></div>';
            }elseif($row['stok'] <= $row['buffer_stok']){
                $status = '<div class="text-left"><span class="label label-md label-danger">Bahaya</span></div>';
            }

            $stok_item_detail = $this->buffer_stock_m->get_stok_item_detail($gudang_id, $row['id'],$row['item_satuan_id'])->result_array();

            $output['data'][] = array(
                '<span class="row-details row-details-close" data-detail="'.htmlentities(json_encode($stok_item_detail)).'" ></span>',
                '<div class="text-left">'.$row['kode'].'</div>',
                $row['nama'],
                '<div class="text-left">'.$row['buffer_stok'].'</div>',
                '<div class="text-left">'.$stok.'</div>',
                $row['unit'],
                '<div class="text-left">'.$status.'</div>' 
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

    public function cetak_stok($gudang_id, $kategori_id, $sub_kategori_id, $tipe)
    {
        $this->load->library('mpdf/mpdf.php');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');

        if($kategori_id != 0 && $sub_kategori_id == 0)
        {
            $sub_kategori_id = array();
            $item_sub = $this->item_sub_kategori_m->get_by(array('item_kategori_id' => $kategori_id, 'is_active' => 1));
            foreach ($item_sub as $row_sub) 
            {
                $sub_kategori_id[] = $row_sub->id;
            }
        }

        $result = $this->buffer_stock_m->get_data_buffer_stock($gudang_id, $kategori_id, $sub_kategori_id)->result_array();
        // $result2 = $this->buffer_stock_m->get_data_buffer_stock_kosong($gudang_id, $kategori_id, $sub_kategori_id)->result_array();

        $gudang = $this->gudang_m->get_by(array('id' => $gudang_id), true);
        $gudang = object_to_array($gudang);

        $form_cabang                 = $this->cabang_m->get_by(array('id' => $this->session->userdata('cabang_id')), true);
        $cabang_alamat               = $this->cabang_alamat_m->get_by(array('cabang_id' => $this->session->userdata('cabang_id'), 'is_primary' => 1, 'is_active' => 1));
        $cabang_alamat               = object_to_array($cabang_alamat);
        $cabang_telepon              = $this->cabang_telepon_m->get_by(array('cabang_id' => $this->session->userdata('cabang_id'),'is_active' => 1, 'subjek_id' => 8));
        $cabang_telepon              = object_to_array($cabang_telepon);
        $cabang_fax                  = $this->cabang_telepon_m->get_by(array('cabang_id' => $this->session->userdata('cabang_id'),'is_active' => 1, 'subjek_id' => 9));
        $cabang_fax                  = object_to_array($cabang_fax);
        $cabang_email                = $this->cabang_sosmed_m->get_by(array('tipe' => 1,'cabang_id' => $this->session->userdata('cabang_id'),'is_active' => 1));
        $cabang_email                = object_to_array($cabang_email);
        $cabang_fb                   = $this->cabang_sosmed_m->get_by(array('tipe' => 3,'cabang_id' => $this->session->userdata('cabang_id'),'is_active' => 1));
        $cabang_fb                   = object_to_array($cabang_fb);
        $cabang_twitter              = $this->cabang_sosmed_m->get_by(array('tipe' => 4,'cabang_id' => $this->session->userdata('cabang_id'),'is_active' => 1));
        $cabang_twitter              = object_to_array($cabang_twitter);
        $cabang_website              = $this->cabang_sosmed_m->get_by(array('tipe' => 2,'cabang_id' => $this->session->userdata('cabang_id'),'is_active' => 1));
        $cabang_website              = object_to_array($cabang_website);
        $data_email = '';
        foreach ($cabang_email as $email) 
        {
            $data_email .= $email['url'].', ';
        }

        $data_cetak = array(
            'result'    => $result,
            // 'result2'    => $result2,
            'gudang'    => $gudang,
            'form_cabang'                 => object_to_array($form_cabang),
            'cabang_alamat'               => $cabang_alamat,
            'cabang_telepon'              => $cabang_telepon,
            'cabang_fax'                  => $cabang_fax,
            'cabang_email'                => $cabang_email,
            'cabang_fb'                   => $cabang_fb,
            'cabang_twitter'              => $cabang_twitter,
            'cabang_website'              => $cabang_website,
            'data_email'                  => $data_email
        );


        $mpdf = new mPDF('utf-8','A4', 0, '', 5, 5, 5, 8, 0, 0);
        $mpdf->SetHTMLFooter($this->load->view('apotik/buffer_stock/footer', $body, true));
        $mpdf->writeHTML($this->load->view('apotik/buffer_stock/cetak_stok', $data_cetak, true));

        // $mpdf->AddPage();
        // $mpdf->SetHTMLFooter($this->load->view('apotik/buffer_stock/footer2', $body, true));
        // $mpdf->writeHTML($this->load->view('apotik/buffer_stock/cetak_stok2', $data_cetak, true));

        $mpdf->Output('Data Stok.pdf', 'I');



    }

    
}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */