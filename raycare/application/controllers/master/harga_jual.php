<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Harga_jual extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '426b84faa027c6e5d6f287c2e49d291e';                  // untuk check bit_access
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

        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_identitas_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/identitas_m');
        $this->load->model('master/harga_jual_m');
        $this->load->model('master/cabang_m');
        $this->load->model('pembelian/supplier_item_m');
        $this->load->model('pembelian/pembelian_detail_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/harga_jual/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').'| '.translate('Daftar Harga Jual', $this->session->userdata('language')), 
            'header'         => translate('Daftar Harga Jual', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/harga_jual/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing($item_kategori_id, $item_sub_kategori_id) {


        $result = $this->harga_jual_m->get_datatable($item_kategori_id,$item_sub_kategori_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );

        $i=1;
        $records = $result->records;
           // die_dump($records);
     
        foreach($records->result_array() as $row)
        {   
            $harga_beli = 0;
            $action = '<div id="btn_edit_'.$i.'"><a title="'.translate('Edit', $this->session->userdata('language')).'" data-index="'.$i.'" name="edit[]" class="btn blue-chambray"><i class="fa fa-edit"></i></a></div><div id="btn_cancel_save_'.$i.'" class="hidden"><a title="'.translate('Batal', $this->session->userdata('language')).'" data-index="'.$i.'" name="batal[]" class="btn btn-danger"><i class="fa fa-undo"></i></a><a title="'.translate('Simpan', $this->session->userdata('language')).'" data-index="'.$i.'" name="simpan[]" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i></a></div>';
            
            $date = date('d M Y');

            if($row['tanggal'] != NULL){
                $date = date('d M Y', strtotime($row['tanggal']));
            }

            $konversi = $this->item_m->get_nilai_konversi($row['item_satuan_id']);

            $hbb_update = $this->pembelian_detail_m->get_max_hpp($row['item_id'],$row['item_satuan_id'])->row(0);

            $harga_beli = $hbb_update->max_harga_primary * $konversi;

            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>' ,
                '<div class="text-left">'.$row['kode'].'</div><input type="hidden" class="form-control text-right" id="input_item_id_'.$i.'" name="input['.$i.'][item_id]"  value="'.$row['item_id'].'" >' ,
                '<div class="text-left inline-button-table">'.$row['nama'].'</div>' ,
                '<div class="text-left">'.$row['satuan'].'</div><input type="hidden" class="form-control text-right" id="input_item_satuan_id_'.$i.'" name="input['.$i.'][item_satuan_id]"  value="'.$row['item_satuan_id'].'" >' ,
                '<div class="text-left">'.$row['sub_kategori'].'/'.$row['kategori'].'</div>',
                '<div class="text-right">'.formatrupiah($harga_beli).'</div>',
                '<div class="text-left" id="div_tanggal_'.$i.'">'.$date.'</div>
                <div class="form-group hidden" id="div_tanggal_edit_'.$i.'">                         
                    <div class="col-md-12">
                        <div class="input-group date">
                            <input type="text" class="form-control" id="input_tanggal_'.$i.'" name="input['.$i.'][tanggal]" placeholder="Tanggal" value="'.$date.'" readonly="" style="width:100px;">
                            <span class="input-group-btn">
                                <button class="btn default date-set" type="button" data-original-title="" title=""><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                </div>' ,
                '<div class="text-right" id="div_harga_'.$i.'">'.formatrupiah($row['harga']).'</div><input class="form-control text-right hidden" id="input_harga_'.$i.'" name="input['.$i.'][harga]"  value="'.$row['harga'].'" >',
                '<div class="text-left inline-button-table">'.$action.'</div>' ,
                
            );
        $i++;
            
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();
        
        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Harga gagal diedit', $this->session->userdata('language'));

        $get_harga_item = $this->item_harga_m->get_by(array('cabang_id' => $this->session->userdata('cabang_id'), 'item_id' => $array_input['item_id'], 'item_satuan_id' => $array_input['item_satuan_id'], 'date(tanggal)' => date('Y-m-d', strtotime($array_input['tanggal']))), true);

        $data = array(
            'cabang_id' => $this->session->userdata('cabang_id'),
            'item_id' => $array_input['item_id'],
            'item_satuan_id' => $array_input['item_satuan_id'],
            'harga' => $array_input['harga'],
            'tanggal' => date('Y-m-d', strtotime($array_input['tanggal']))
        );

        if(count($get_harga_item) != 0){
            $edit_harga = $this->item_harga_m->edit_data($data, $get_harga_item->id);
        }else{
            $edit_harga = $this->item_harga_m->save($data);
        }

        if( date('Y-m-d', strtotime($array_input['tanggal'])) <= date('Y-m-d') ){

            $data_satuan = array(
                'tanggal' => date('Y-m-d', strtotime($array_input['tanggal'])),
                'harga' => $array_input['harga']
            );

            $edit_satuan = $this->item_satuan_m->edit_data($data_satuan, $array_input['item_satuan_id']);
        }
        if($edit_harga){
            $response->success = true;
            $response->msg = translate('Harga berhasil diedit', $this->session->userdata('language'));
        }

        die(json_encode($response));
    }
   
    public function get_sub_kategori(){

        $kategori_id = $this->input->post('kategori_id');
        
        $result = $this->item_sub_kategori_m->get_by(array('item_kategori_id'=>$kategori_id,'is_active'=>1));
        $result_array=object_to_array($result);

        echo json_encode($result_array);
    }

}

/* End of file spesialis.php */
/* Location: ./application/controllers/spesialis/spesialis.php */