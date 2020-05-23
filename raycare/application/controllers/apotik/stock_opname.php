<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_opname extends MY_Controller {

 protected $menu_id = 3;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(2, 3);        // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

         if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }
        $this->load->model('master/item/item/item_m');
        $this->load->model('master/warehouse_m');
        $this->load->model('master/warehouse_people_m');
         $this->load->model('apotik/racik_obat/inventory_identitas_m');
        $this->load->model('apotik/stock_opname/stock_opname_m');
        $this->load->model('apotik/stock_opname/stock_opname_detail_m');
        $this->load->model('apotik/stock_opname/stock_opname_template_m');
        $this->load->model('apotik/stock_opname/stock_opname_template_detail_m');
         $this->load->model('apotik/item_satuan_m');
         $this->load->model('apotik/stock_opname/stock_opname_identitas_m');
         $this->load->model('apotik/stock_opname/stock_opname_identitas_detail_m');
        $this->load->model('others/kotak_sampah_m');
         $this->load->model('apotik/resep_obat_racikan_m');





    }

	public function index($wareid=null)
	{
       $this->load->model('master/warehouse_m');
        
            
		$assets = array();
        $config = 'assets/apotik/stock_opname/stock_opname';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Stok Opname', $this->session->userdata('language')), 
            'header'         => translate('Stok Opname', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'wareid'         => $wareid,
            'content_view'   => 'apotik/stock_opname/stock_opname',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
	}
    public function refresh($pk)
    {
         redirect('apotik/stock_opname/index/'.$pk);  
    }

    public function refresh2($access_id,$warehouse_id,$warehouse_people_id)
    {

         redirect('apotik/stock_opname/konfirmasi_input_result/'.$access_id.'/'.$warehouse_id.'/'.$warehouse_people_id.'');  
    }
    public function add($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $this->load->library('form_builder');
        
        $assets = array();
        $config = 'assets/apotik/stock_opname/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Add Stock Opname', $this->session->userdata('language')), 
            'header'         => translate('Tambah Stok Opname', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stock_opname/add',
            'pk_value'       => $id                         //table primary key value
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing($dt) {
        // die_dump($dt);
        $result = $this->stock_opname_m->get_datatable($dt);
        // die_dump($result);

        // Output

        $output = array(
            'draw'                => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        foreach($records->result_array() as $row)
        {
            $date = date_create($row['create_date']);
            $create_date = date_format($date, 'd M Y H:i');

            $start_date='';
            $end_date='';

            if($row['start_date']==null){
                $start_date='-';
            }else{
                $start_date=date('d M Y H:i',strtotime($row['start_date']));
            }

             if($row['end_date']==null){
                $end_date='-';
            }else{
                $end_date=date('d M Y H:i',strtotime($row['end_date']));
            }
            $action = '';
            if ($row['is_active'] == 1) {
                $action = '<a title="'.translate('Print', $this->session->userdata('language')).'" href="'.base_url().'apotik/stock_opname/printpdf/'.$row['id'].'/'.$row['warehouse_id'].'/'.$row['warehouse_people_id'].'" target="_blank" class="btn btn-xs default"><i class="fa fa-print"></i></a>
                           <a title="'.translate('Input Hasil', $this->session->userdata('language')).'" href="'.base_url().'apotik/stock_opname/input_result/'.$row['id'].'/'.$row['warehouse_id'].'/'.$row['warehouse_people_id'].'" class="btn btn-xs green-haze"><i class="fa fa-list"></i></a>
                           <a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'apotik/stock_opname/view/'.$row['id'].'/'.$row['warehouse_id'].'/'.$row['warehouse_people_id'].'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>
                           <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'apotik/stock_opname/edit/'.$row['id'].'/'.$row['warehouse_id'].'/'.$row['warehouse_people_id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                           <a title="'.translate('Hapus', $this->session->userdata('language')).'" name="delete[]" id="delete" data-action="delete" data-confirm="'.translate('Apakah anda yakin ingin menghapus item ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn btn-xs red-intense"><i class="fa fa-times"></i></a>';        
            } else{ 
                $action = '<a title="'.translate('Restore', $this->session->userdata('language')).'" data-confirm="'.translate('Are you sure you want to restore this stock opname?', $this->session->userdata('language')).'" name="restore[]" data-action="restore" data-id="'.$row['id'].'" class="btn btn-xs yellow"><i class="fa fa-undo"></i> </a>';
            }
            
            $output['data'][] = array(
                '<div class="text-center">'.$row['stock_opname_number'].'</div>',
                '<div class="text-center">'.$row['user'].'</div>',
                '<div class="text-center">'.$row['people'].'</div>',
                '<div class="text-center">'.$start_date.'</div>',
                 '<div class="text-center">'.$end_date.'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        // die_dump(json_encode$output);
        echo json_encode($output);
    }

    public function listingHistory($dt, $status=null) {
        // die_dump($dt);
        $result = $this->stock_opname_m->get_datatable_history($dt, $status);
        // die_dump($result);

        // Output
          $output = array(
            'draw'                => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $date = date_create($row['create_date']);
            $create_date = date_format($date, 'd M Y H:i');

            $date1 = date_create($row['start_date']);
            $start_date = date_format($date1, 'd M Y H:i');

            $date2 = date_create($row['end_date']);
            $end_date = date_format($date2, 'd M Y H:i');

            $action = '';
            if ($row['is_active'] == 1) {
                $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'" href="'.base_url().'apotik/stock_opname/view_history/'.$row['id'].'/'.$row['warehouse_id'].'/'.$row['warehouse_people_id'].'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>';        
            } 
            
            $output['data'][] = array(
                '<div class="text-center">'.$row['stock_opname_number'].'</div>',
                '<div class="text-center">'.$row['user'].'</div>',
                '<div class="text-center">'.$row['people'].'</div>',
                 
                '<div class="text-center">'.$start_date.'</div>',
                '<div class="text-center">'.$end_date.'</div>',
                '<div class="text-center">'.$row['is_mismatch'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listingInventory($warehouse_id) {
        $result = $this->item_m->get_datatable_item($warehouse_id);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action2='';
            $itemsatuan=$this->item_satuan_m->get_by(array('item_id'=>$row['item_id']));
            $action2.='<select name="satuanitem['.$i.'][item_satuan1]" id="satuanitem['.$i.'][item_satuan1]" class="form-control sat">';
            foreach ($itemsatuan as $row1) {
                 $action2.='<option value="'.$row1->id.'">'.$row1->nama.'</option>';
            }
            $action2.='</select>';

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" name="choose1[]" class="btn btn-xs green-haze select"><i class="fa fa-check"></i></a>';
           // die_dump(htmlentities(json_encode($row)));
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['item_id'].'</div>',
                '<div class="text-center">'.$row['code'].'</div>',
                $row['name'],
                $action2.'<input type="hidden" name="satuanitem['.$i.'][item_jumlah_sistem]">',
                '<div class="text-center">'.$row['system_qty'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);

    }

    public function listingTemplate($warehouse_id)
    {
        $result = $this->stock_opname_template_m->get_datatable();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $detail = $this->stock_opname_template_detail_m->get_data_by_template_id($row['id'], $warehouse_id)->result_array();
           
            $action_view = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  data-id="'.$row['id'].'" name="view[]" class="btn btn-xs default view"><i class="fa fa-search"></i></a>&nbsp;';
            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($detail)).'" name="select[]" class="btn btn-xs green-haze select"><i class="fa fa-check"></i></a>';
           // die_dump(htmlentities(json_encode($row)));
            $output['aaData'][] = array(
               	$row['name'],
                $row['warehouse_name'].' - '.$row['people'],
                '<div class="text-center">'.$action_view.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listingDetailTemplate($id)
    {
        $result = $this->stock_opname_template_detail_m->get_datatable($id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $output['aaData'][] = array(
                $row['item_code'],
                $row['item_name'],
                $row['item_satuan']
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function edit($id, $warehouse_id, $warehouse_people_id)
    {
        
        $id = intval($id);
        $id || redirect(base_Url());
        $this->load->library('form_builder');
        
        $assets = array();
        $config = 'assets/apotik/stock_opname/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->stock_opname_m->get($id);
        $form_data_people = $this->warehouse_people_m->get($warehouse_people_id);
        $form_data_warehouse = $this->warehouse_m->get($warehouse_id);
        
        $form_data2 = $this->stock_opname_m->getmodified($id)->result_array();
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Edit Stock Opname', $this->session->userdata('language')), 
            'header'         => translate('Edit Stok Opname', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stock_opname/edit',
            'form_data'             => object_to_array($form_data),
            'form_data_people'      => object_to_array($form_data_people),
            'form_data_warehouse'   => object_to_array($form_data_warehouse),
            'form_data_item'        => $this->stock_opname_detail_m->get_data($id),
            'form_data2'             => $form_data2,
            'pk_value'              => $id,
            'warehouse_id'          => $warehouse_id,
            'warehouse_people_id'   => $warehouse_people_id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id, $warehouse_id, $warehouse_people_id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/stock_opname/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $form_data = $this->stock_opname_m->get($id);
        $form_data_people = $this->warehouse_people_m->get($warehouse_people_id);
        $form_data_warehouse = $this->warehouse_m->get($warehouse_id);

        $data = array(
            'title'                 => config_item('site_name').' | '.translate('View Stock Opname', $this->session->userdata('language')), 
            'header'                => translate('Lihat Stok Opname', $this->session->userdata('language')), 
            'header_info'           => config_item('site_name'), 
            'breadcrumb'            => true,
            'menus'                 => $this->menus,
            'menu_tree'             => $this->menu_tree,
            'css_files'             => $assets['css'],
            'js_files'              => $assets['js'],
            'content_view'          => 'apotik/stock_opname/view',
            'form_data'             => object_to_array($form_data),
            'form_data_people'      => object_to_array($form_data_people),
            'form_data_warehouse'   => object_to_array($form_data_warehouse),
            'pk_value'              => $id,
            'warehouse_id'          => $warehouse_id,
            'warehouse_people_id'   => $warehouse_people_id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listingView($id){

        $result = $this->stock_opname_detail_m->get_datatable($id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['item_code'].'</div>',
                '<div class="text-left">'.$row['item_name'].'</div>',
                 '<div class="text-left">'.$row['nama_satuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function input_result($id, $warehouse_id, $warehouse_people_id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/stock_opname/input_result';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->stock_opname_m->get($id);
        
        $form_data_people = $this->warehouse_people_m->get($warehouse_people_id);
        $form_data_warehouse = $this->warehouse_m->get($warehouse_id);

        $data = array(
            'title'             => config_item('site_name').' | '.translate('Input Stock Opname Result', $this->session->userdata('language')), 
            'header'            => translate('Input Hasil Stok Opname', $this->session->userdata('language')), 
            'header_info'       => config_item('site_name'), 
            'breadcrumb'        => true,
            'menus'             => $this->menus,
            'menu_tree'         => $this->menu_tree,
            'css_files'         => $assets['css'],
            'js_files'          => $assets['js'],
            'content_view'      => 'apotik/stock_opname/input_result',
            'form_data'         => object_to_array($form_data),
            'form_data_people'  => object_to_array($form_data_people),
            // 'form_data_people'  => $this->stock_opname_m->get_people($id),
            // 'form_data_warehouse' => $this->stock_opname_m->get_warehouse($id),
            'form_data_warehouse' => object_to_array($form_data_warehouse),
            'pk_value'          => $id,
            'wareid'    =>$warehouse_id,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listingItem($id, $warehouse_id){
        $result = $this->stock_opname_detail_m->get_datatable_qty($id, $warehouse_id);

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        foreach($records->result_array() as $row)
        {
            // $qty = '<input class="form-control input-sm text-right" type="number" min="0" id="input_qty" name="quantity['.($i + 1).'][jumlah_input]">
            //         <input class="form-control input-sm hidden" name="quantity['.($i + 1).'][id]" id="id_so_detail" value="'.$row['id'].'">
            //         <input class="form-control input-sm hidden" name="quantity['.($i + 1).'][jumlah_sistem]" id="qty" value="'.$row['system_qty'].'">';
              $qty = '<input class="form-control input-sm text-right" type="hidden"  id="input_item" name="quantity['.($i + 1).'][itemid]" value="'.$row['item_id'].'">
                    <input class="form-control input-sm text-right" type="hidden"  id="input_satuan" name="quantity['.($i + 1).'][satuanid]"> 
                    <input class="form-control input-sm text-right" type="number" min="0" id="input_qty" name="quantity['.($i + 1).'][jumlah_input]"> 
                    <input class="form-control input-sm hidden" name="quantity['.($i + 1).'][id]" id="id_so_detail" value="'.$row['id'].'">
                    <input class="form-control input-sm hidden" name="quantity['.($i + 1).'][jumlah_sistem]" id="qty" value="'.$row['system_qty'].'">';

            // $result_qty = $this->inventory_m->get_datatable_qty($row['item_id'], $warehouse_id);
            // die_dump($result_qty)
            $action2='';
            $itemsatuan=$this->item_satuan_m->get_by(array('item_id'=>$row['item_id']));
            $action2.='<select name="items['.($i + 1).'][item_satuan1]" id="items['.($i + 1).'][item_satuan1]" class="sat">';
            foreach ($itemsatuan as $row1) {
                if($row1->id==$row['item_satuan_id'])
                {
                    $action2.='<option value="'.$row1->id.'"  selected>'.$row1->nama.'</option>';
                }else{
                    $action2.='<option value="'.$row1->id.'">'.$row1->nama.'</option>';
                }
                 
            }
            $action2.='</select>'; 

            $attrs_jumlah = array(
                        'id'    => 'items_jumlah_'.$i.'',
                        'name'  => 'items['.($i + 1).'][item_jumlah]', 
                        'type'  => 'number',
                        'min'   => 0,
                        'value' => 0,
                        'class' => 'form-control text-right hidden',
                    );       
            $jumlah='<div class="input-group text-center">
                                                        <label class="control-label" name="items['.($i + 1).'][item_jumlah]" style="display: table-cell; width: 100%; text-align: center;">0</label>
                                                        <a class="btn btn-primary identitas hidden" id="info_identitas_'.$i.'" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'apotik/stock_opname/modal_identitas/'.$row['item_id'].'/'.$row['item_satuan_id'].'/item_row_'.$i.'" data-itemid="'.$row['item_id'].'" data-itemsatuanid="'.$row['item_satuan_id'].'" data-rowid="item_row_'.$i.'"><i class="fa fa-info"></i></a>
                                                        <a class="btn btn-primary check-identitas" data-row-check="'.$i.'" data-confirm="'.translate("Apakah anda ingin mengganti identitas sebelumnya ?", $this->session->userdata("language")).'"><i class="fa fa-info"></i></a>
                                                      </div>'.form_input($attrs_jumlah).'';
          //  $info = '<a title="'.translate('Komposisi', $this->session->userdata('language')).'" name="komposisi[]" class="btn btn-primary komposisi-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';
            $output['data'][] = array(
                '<div class="text-center">'.$row['item_code'].'<input class="form-control input-sm hidden" name="items['.($i + 1).'][id]" id="id_so_detail1" value="'.$row['id'].'"><input class="form-control input-sm hidden" name="items['.($i + 1).'][item_id]" id="id_item_id" value="'.$row['item_id'].'"></div>',
                '<div class="text-left">'.$row['item_name'].'</div>',
                '<div class="text-center">'.$qty.$jumlah.'</div>',
                '<div class="text-center">'.$action2.'<div id="simpan_identitas" class="hidden"></div></div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listingItemKonfirmasi($id, $warehouse_id){
        $result = $this->stock_opname_detail_m->get_datatable_qty($id, $warehouse_id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array() 
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '<a id="btn_edit_'.$row['id'].'" title="'.translate('Edit', $this->session->userdata('language')).'" data-id="'.$row['id'].'" href="javascript:;" class="btn btn-xs blue-chambray edit"><i class="fa fa-edit"></i></a>
                        <a id="btn_save_'.$row['id'].'" title="'.translate('Simpan', $this->session->userdata('language')).'" data-id="'.$row['id'].'" data-confirm="'.translate('Date has been updated successfully', $this->session->userdata('language')).'" class="btn btn-xs green-haze hidden save"><i class="fa fa-check save"></i></a>    
                        <a id="btn_cancel_'.$row['id'].'" title="'.translate('Batal', $this->session->userdata('language')).'" data-id="'.$row['id'].'" class="btn btn-xs red-intense hidden cancel"><i class="fa fa-reply cancel"></i></a>
                        <input class="form-control input-sm hidden" name="quantity['.$row['id'].'][id]" id="id_so_detail" value="'.$row['id'].'">';

           

            $qty = '<div class="text-center"><span name="quantity['.$row['id'].'][system_qty]">'.$row['system_qty'].'</span></div>
                    <input type="hidden" name="quantity['.$row['id'].'][system_qty]" id="qty_'.$row['id'].'" class="form-control input-sm" value="'.$row['system_qty'].'">';

            $counted_qty = '<div class="text-center"><span id="counted_qty_'.$row['id'].'">'.$row['input_qty'].'</span></div>
                            <input type="number" name="quantity['.$row['id'].'][input_qty]" id="input_qty_'.$row['id'].'" class="form-control input-sm text-right hidden" value="'.$row['input_qty'].'">';

            if ($row['system_qty'] != $row['input_qty']) {
                
                $qty = '<div class="text-center"><span name="quantity['.$row['id'].'][system_qty]" class="label label-danger">'.$row['system_qty'].'</span></div>
                        <input type="hidden" name="quantity['.$row['id'].'][system_qty]" id="qty_'.$row['id'].'" class="form-control input-sm" value="'.$row['system_qty'].'">';

                $counted_qty = '<div class="text-center"><span id="counted_qty_'.$row['id'].'" class="label label-danger">'.$row['input_qty'].'</span></div>
                                <input type="number" name="quantity['.$row['id'].'][input_qty]" id="input_qty_'.$row['id'].'" class="form-control input-sm text-right hidden" value="'.$row['input_qty'].'">';
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['item_code'].'</div>',
                '<div class="text-left">'.$row['item_name'].'</div>',
                '<div class="text-left">'.$row['nama_satuan'].'</div>',
                $qty,
                $counted_qty,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function konfirmasi_input_result($id, $warehouse_id, $warehouse_people_id){
        
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/stock_opname/konfirmasi_input_result';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $form_data = $this->stock_opname_m->get($id);
        $form_data_people = $this->warehouse_people_m->get($warehouse_people_id);
        $form_data_warehouse = $this->warehouse_m->get($warehouse_id);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Konfirmasi Stock Opname Result', $this->session->userdata('language')), 
            'header'         => translate('Konfirmasi Result Stok Opname', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stock_opname/konfirmasi_input_result',
            'form_data'             => object_to_array($form_data),
            'form_data_people'      => object_to_array($form_data_people),
            'form_data_warehouse'   => object_to_array($form_data_warehouse),
            'pk_value'              => $id,
            'wareid'              => $warehouse_id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_history($id, $warehouse_id, $warehouse_people_id)
    {

        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/stock_opname/view_history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->stock_opname_m->get($id);
        $form_data_people = $this->warehouse_people_m->get($warehouse_people_id);
        $form_data_warehouse = $this->warehouse_m->get($warehouse_id);
        $form_data_user = $this->user_m->get($this->session->userdata('user_id'));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('View Stock Opname History', $this->session->userdata('language')), 
            'header'         => translate('Lihat Histori Stok Opname', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stock_opname/view_history',
            'form_data'             => object_to_array($form_data),
            'form_data_people'      => object_to_array($form_data_people),
            'form_data_warehouse'   => object_to_array($form_data_warehouse),
            'form_data_user'        => object_to_array($form_data_user),
            'pk_value'              => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listingHistoryItem($id, $warehouse_id){
        $result = $this->stock_opname_detail_m->get_datatable_qty($id, $warehouse_id);

        // Output
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

            $qty = '<div class="text-center"><span name="quantity['.$row['id'].'][system_qty]">'.$row['system_qty'].'</span></div>
                    <input type="hidden" name="quantity['.$row['id'].'][system_qty]" id="qty_'.$row['id'].'" class="form-control input-sm" value="'.$row['system_qty'].'">';

            $counted_qty = '<div class="text-center"><span id="counted_qty_'.$row['id'].'">'.$row['input_qty'].'</span></div>
                            <input type="number" name="quantity['.$row['id'].'][input_qty]" id="input_qty_'.$row['id'].'" class="form-control input-sm text-right hidden" value="'.$row['input_qty'].'">';

            if ($row['system_qty'] != $row['input_qty']) {
                
                $qty = '<div class="text-center"><span name="quantity['.$row['id'].'][system_qty]" class="label label-danger">'.$row['system_qty'].'</span></div>
                        <input type="hidden" name="quantity['.$row['id'].'][system_qty]" id="qty_'.$row['id'].'" class="form-control input-sm" value="'.$row['system_qty'].'">';

                $counted_qty = '<div class="text-center"><span id="counted_qty_'.$row['id'].'" class="label label-danger">'.$row['input_qty'].'</span></div>
                                <input type="number" name="quantity['.$row['id'].'][input_qty]" id="input_qty_'.$row['id'].'" class="form-control input-sm text-right hidden" value="'.$row['input_qty'].'">';
            }

            $output['data'][] = array(
                '<div class="text-center">'.$row['item_code'].'</div>',
                '<div class="text-left">'.$row['item_name'].'</div>',
                 '<div class="text-left">'.$row['nama_satuan'].'</div>',
                $qty,
                $counted_qty,
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function search_item()
    {
        // $this->load->view("master/item/suppliers/seach_supplier");
        $assets = array();
        $config = 'assets/apotik/stock_opname/search_item';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Items', $this->session->userdata('language')), 
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stock_opname/search_item',
            );
        
        // Load the view
        $this->load->view('_layout_popup', $data);
    }

    public function search_template()
    {
        $assets = array();
        $config = 'assets/apotik/stock_opname/search_template';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Templates', $this->session->userdata('language')), 
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stock_opname/search_template',
            );
        
        // Load the view
        $this->load->view('_layout_popup', $data);
    }

    public function search_user()
    {   
        $assets = array();
        $config = 'assets/apotik/stock_opname/search_user';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Users', $this->session->userdata('language')), 
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/stock_opname/search_user',
            );
        
        // Load the view
        $this->load->view('_layout_popup', $data);
    }

    public function save_qty(){

        $id = $this->input->post('id');
        $value = $this->input->post('value');
        
       // $data = $this->stock_opname_detail_m->array_from_post( $this->stock_opname_detail_m->fillable());
        // die_dump($data);
 
        $data['jumlah_input'] = $value;
        
        // unset($data['stock_opname_id']);
        // unset($data['item_id']);
        // unset($data['counted_qty']);

        $stock_detail_id = $this->stock_opname_detail_m->save($data, $id); 


        // die_dump($this->db->last_query());
    }

    public function save()
    {
        $command = $this->input->post('command');
        $array_input = $this->input->post();
        
        if ($command === 'add')
        {  
         //   $data = $this->stock_opname_m->array_from_post( $this->stock_opname_m->fillable());

            $last_number = $this->stock_opname_m->get_nomor_stk()->result_array();
            $last_number = intval($last_number[0]['max_nomor_stk'])+1;
            
            $format      = 'STK-'.date('ym').'-%03d';
            $stk_number  = sprintf($format, $last_number, 3);
            
            $data['no_stok_opname'] = $stk_number;
            $data['gudang_id'] = $this->input->post('warehouse_id');
            $data['gudang_orang_id'] = $this->input->post('warehouse_people_id');
            $data['is_active'] = 1;
            $data['is_finish'] = 0;
          //  $data['is_approve'] = 0;

            // unset($data['tanggal_mulai']);
            // unset($data['tanggal_akhir']);
            // unset($data['keterangan']);

            // die_dump($data);

            $access_id = $this->stock_opname_m->save($data);

            if($this->input->post('save_template')!=null)
            {
                $data_template = array(
                    'nama'              => $this->input->post('template_name'),
                    'gudang_id'         => $this->input->post('warehouse_id'),
                    'gudang_orang_id'   => $this->input->post('warehouse_people_id'),
                    'is_active'   => 1
                ); 
                $stk_template_id = $this->stock_opname_template_m->save($data_template);
                
                if($stk_template_id)
                {
                    $data_items = $this->input->post('items');
                    // die_dump($data_items);
                    foreach ($data_items as $item) 
                    {
                        if($item['item_id'] != '')
                        {
                            

                            // $item['input_qty'];
                            $item['stok_opname_template_id'] = $stk_template_id;
                          //  $item['jumlah_sistem']=$item['system_qty'];

                            unset($item['code']);
                            unset($item['name']);
                            unset($item['system_qty']);
                            unset($item['satuan_text']);

                            $this->stock_opname_template_detail_m->save($item); 
                            // die_dump($this->db->last_query());
                        }
                    }
                }
            }

            $data_inventory = $this->input->post('items');
            // die_dump($data_inventory);

            foreach ($data_inventory as $inventory) 
            {
                if($inventory['item_id'] != '')
                {
                    
                    // $inventory['input_qty'];
                    $inventory['stok_opname_id'] = $access_id;
                    $inventory['jumlah_sistem']=$inventory['system_qty'];

                    unset($inventory['code']);
                    unset($inventory['name']);
                    unset($inventory['system_qty']);
                    unset($inventory['satuan_text']);
                    // die_dump($inventory);
                    $get_id_stock_opname_detail=$this->stock_opname_detail_m->save($inventory); 
                    // die_dump($this->db->last_query());

                    //=======================
                    $data_inventory_identitas = $this->input->post('items');
             
                   // foreach ($data_inventory_identitas as $inventory_identitas) 
                   // {
                        // if($inventory_identitas['item_id'] != '')
                        // {
                    
                            $getidinventory1=$this->stock_opname_m->getid1($inventory['item_id'],$inventory['item_satuan_id'],$this->input->post('warehouse_id'))->result_array();
                          // die(dump($this->db->last_query()));
                            foreach($getidinventory1 as $row)
                            {
                                $data3['stok_opname_detail_id']=$get_id_stock_opname_detail;
                                $data3['jumlah_sistem']=$row['jumlah'];
                                $getidinventory3=$this->stock_opname_identitas_m->save($data3); 
                               // die(dump($this->db->last_query()));
                                $getidinventory2=$this->stock_opname_m->getid2($row['inventory_identitas_id'])->result_array();
                           
                                foreach($getidinventory2 as $row)
                                {
                                    $getlastid=$this->stock_opname_m->getlastid()->result_array();
                                       
                                    $data2['id']=$getlastid[0]['id']+1;
                                    $data2['stok_opname_identitas_id']=$getidinventory3;
                                    $data2['identitas_id']=$row['identitas_id'];
                                    $data2['judul']=$row['judul'];
                                    $data2['value']=$row['value'];

                                    $data2['created_by']=$this->session->userdata('user_id');
                                    $data2['created_date']=date('Y-m-d H:i:s');
                            
                                    $getidinventory4=$this->stock_opname_identitas_detail_m->add_data($data2); 
                                     //   die(dump($this->db->last_query()));
                                }   
                            }
                    
                            
                    
                        //}
                  //  }
                    //=======================
                }
            }

            

            // die_dump($this->db->last_query());
            if ($access_id) {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Stock Opname has been added", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
                $this->session->set_flashdata($flashdata);
            }   
                    
        }

        if ($command === 'input')
        {  

            $this->load->model('apotik/racik_obat/inventory_m');
            $this->load->model('apotik/racik_obat/inventory_history_m');
            $this->load->model('apotik/racik_obat/inventory_history_detail_m');
            $this->load->model('apotik/racik_obat/inventory_history_identitas_m');
            $this->load->model('apotik/racik_obat/inventory_history_identitas_detail_m');
           // $this->load->model('apotik/racik_obat/inventory_identitas_m');
            $this->load->model('apotik/racik_obat/inventory_identitas_detail_m');
            $id = $this->input->post('id');

           // $data = $this->stok_opname_m->array_from_post( $this->stock_opname_m->fillable());
           
            $data['is_finish'] = 1;
            $data['tanggal_mulai'] = date('Y-m-d H:i', strtotime($this->input->post('start_date')));
            $data['tanggal_selesai'] = date('Y-m-d H:i', strtotime($this->input->post('end_date')));
            $data['gudang_id']=$this->input->post('warehouse_id');;
            $data['gudang_orang_id']=$this->input->post('warehouse_people_id');;
            
            $data['keterangan']=$this->input->post('note');;
            // save stock_opname

            $data_detail = $this->input->post('quantity');
            // die_dump($data_detail);            

            $is_mismatch = 0;
            foreach ($data_detail as $detail) 
            {
                $data2['jumlah_hitung']=$detail['jumlah_input'];
                $data2['jumlah_input']= $detail['jumlah_input'];  
                $data2['jumlah_sistem']= $detail['jumlah_sistem']; 

                //$detail['jumlah_hitung'] = $detail['input_qty'];   

                if ($detail['jumlah_input'] != $detail['jumlah_sistem']) {

                    $data['is_mismatch'] = 1;
                    $is_mismatch = 1;
                }

                // unset($detail['system_qty']);
                // die_dump($detail);
                $stock_detail_id = $this->stock_opname_detail_m->save($data2, $detail['id']); 

            }    

            $data['is_mismatch'] = $is_mismatch;

            $access_id = $this->stock_opname_m->save($data, $id);
            
            if ($access_id) {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Stock Opname has been inputed", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
                $this->session->set_flashdata($flashdata);
            }

            //====== identitas =======


            $data_inventory_history = array(
                'transaksi_id' => $access_id,
                'transaksi_tipe' => '6'
            );

            $id_inventory_history = $this->inventory_history_m->save($data_inventory_history);
        
            // $jumlah_item = 0;
            foreach ($array_input['items'] as $item) 
            {

                if($item['item_id'] != "")
                {
                   
                    $harga_sebelumnya = 0;
                    if (isset($array_input['identitas_'.$item['item_id']])) {
                        
                        $i = 1;
                        foreach ($array_input['identitas_'.$item['item_id']] as $identitas) {
                            if (isset($identitas)) {
                               // if ($identitas['jumlah'] != 0) {

                                    $cek_stock_opname_detail = $this->stock_opname_detail_m->get_by(array(
                                        'stok_opname_id' => $access_id,
                                        'item_id' => $item['item_id'],
                                        'item_satuan_id' => $item['item_satuan1']
                                       
                                    ));

                                     $cek_stock_opname_detail_array = object_to_array($cek_stock_opname_detail);
                                     

                                     foreach($cek_stock_opname_detail_array as $row_stock_opname_detail)
                                     {
                                        $cek_stock_opname_identitas = $this->stock_opname_identitas_m->get_by(array(
                                            'stok_opname_detail_id' => $row_stock_opname_detail['id'],
                                            
                                       
                                        ));

                                        $cek_stock_opname_identitas_array = object_to_array($cek_stock_opname_identitas);
                                        
                                        foreach($cek_stock_opname_identitas_array as $row_cek_stock_opname_identitas)
                                        {
                                                $data_item = array(
                                                    'jumlah_hitung' => $identitas['jumlah'],
                                                    'jumlah_input' => $identitas['jumlah']
                                           
                                                );
                                        
                                   
                                                $id_stock_opname_identitas = $this->stock_opname_identitas_m->save($data_item,$row_cek_stock_opname_identitas['id']);
                                              // die(dump($this->db->last_query()));
                                        }   
                                            
                                     }
                                      
                                   

                                    $data_inventory_history_detail = array(
                                        'inventory_history_id' => $id_inventory_history,
                                        'gudang_id' => $identitas['gudang_id'],
                                        'pmb_id' => $identitas['pmb_id'],
                                        'item_id' => $item['item_id'],
                                        'item_satuan_id' => $item['item_satuan1'],
                                        'initial_stock' => $identitas['stock'],
                                        'change_stock' => '-'.$identitas['jumlah'],
                                        'final_stock' => intval($identitas['stock'])-intval($identitas['jumlah']),
                                        'harga_beli' => $identitas['harga_beli'],
                                        'total_harga' => intval($identitas['harga_beli'])*intval($identitas['jumlah']),

                                    );

                                    $id_inventory_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);
                                    

                                    $data_inventory_history_identitas = array(
                                        'inventory_history_detail_id' => $id_inventory_history_detail,
                                        'jumlah' => $identitas['jumlah'],
                                    );

                                    $inventory_history_identitas_id = $this->inventory_history_identitas_m->save($data_inventory_history_identitas);
                                    
                                    // $get_jumlah_inventory = $this->inventory_m->get_by(array('inventory_id' => $identitas['inventory_id']));
                                    // $array_jumlah_inventory = object_to_array($get_jumlah_inventory);

                                    // $jumlah_inventory = intval($array_jumlah_inventory[0]['jumlah']-intval($identitas['jumlah']));
                                    // $modified_by      = $this->session->userdata('user_id');
                                    // $modified_date    = date('Y-m-d H:i:s');

                                    // $save_inventory = $this->inventory_m->update_jumlah_inventory($jumlah_inventory, $modified_by, $modified_date, $identitas['inventory_id']);

                                    // $get_jumlah_identitas = $this->inventory_identitas_m->get_by(array('inventory_identitas_id' => $identitas['inventory_identitas_id']));
                                    // $array_jumlah_identitas = object_to_array($get_jumlah_identitas);

                                    // // // die_dump($array_jumlah_identitas[0]['jumlah']);
                                    // $jumlah = intval($array_jumlah_identitas[0]['jumlah'])-intval($identitas['jumlah']);
                                    // $modified_by      = $this->session->userdata('user_id');
                                    // $modified_date    = date('Y-m-d H:i:s');

                                    // $save_inventory_identitas = $this->inventory_identitas_m->update_stock_identitas($jumlah, $identitas['inventory_identitas_id'], $modified_by, $modified_date);
                                    

                                    // $cek_stock_inventory_habis = $this->inventory_m->get_by(array('jumlah' => 0));
                                    // $cek_stock_inventory_habis_array = object_to_array($cek_stock_inventory_habis);

                                    // if(!empty($cek_stock_inventory_habis_array)){
                                    //     // die_dump($cek_stock_inventory_habis_array);
                                    //     foreach ($cek_stock_inventory_habis_array as $delete_stock_inventory) {
                                    //         $this->inventory_m->delete_inventory($delete_stock_inventory['inventory_id']);


                                    //     }

                                    //     // die_dump($this->db->last_query());
                                    // }

                                    // $cek_stock_inventory_identitas_habis = $this->inventory_identitas_m->get_by(array('jumlah' => 0));
                                    // $cek_stock_inventory_identitas_habis_array = object_to_array($cek_stock_inventory_identitas_habis);


                                    // if(!empty($cek_stock_inventory_identitas_habis_array)){
                                    //     // die_dump($cek_stock_inventory_habis_array);
                                        
                                    //     foreach ($cek_stock_inventory_identitas_habis_array as $delete_stock_inventory_identitas) {
                                    //         $this->inventory_identitas_m->delete_inventory_identitas($delete_stock_inventory_identitas['inventory_identitas_id']);
                                    //         $this->inventory_identitas_detail_m->delete_inventory_identitas_detail($delete_stock_inventory_identitas['inventory_identitas_id']);
                                    //     }

                                    //     // die_dump($cek_stock_inventory_identitas_habis_array);
                                    //     // die_dump($this->db->last_query());
                                    // }

                                    // die_dump($this->db->last_query());
                                    // die_dump($data_inventory_history_detail);

                                    // die_dump($array_input['identitas_detail_'.$item['item_id'].'_'.$i]);
                                    // foreach ($array_input['identitas_detail_'.$item['item_id'].'_'.$i] as $identitas_detail) {
                                        
                                    //     // $indentitas_detail = $array_input['identitas_'.$item['item_id'].'_'.$master_identitas['id']][$indexIdentitas];

                                    //         $data_identitas_detail = array(
                                    //             'racik_obat_identitas_id' => $id_racik_obat_identitas, 
                                    //             'identitas_id' => $identitas_detail['id'],
                                    //             'judul' => $identitas_detail['judul'],
                                    //             // 'tipe' => $racik_obat_identitas_detail['tipe'],
                                    //             // '`group`' => $racik_obat_identitas_detail['group'],
                                    //             'value' => $identitas_detail['value'],
                                    //             // 'jumlah' => $identitas['jumlah'],
                                    //         );
                                    //         // die_dump($indentitas_detail['value']);
                                    //         $id_racik_obat_identitas_detail = $this->racik_obat_identitas_detail_m->save($data_identitas_detail);
                                            
                                    //         $data_inventory_history_identitas_detail = array(
                                    //             'inventory_history_identitas_id' => $inventory_history_identitas_id,
                                    //             'identitas_id' => $identitas_detail['id'],
                                    //             'judul' => $identitas_detail['judul'],
                                    //             'value' => $identitas_detail['value'],
                                    //         );

                                    //         $inventory_history_identitas_detail = $this->inventory_history_identitas_detail_m->save($data_inventory_history_identitas_detail);
                                    //     }
                                        // $indexIdentitas++;
                                   // }
                                
                                // die_dump($this->db->last_query());
                            }
                        $i++;
                        } 
                                    // die_dump($data_item);
                    }

                    // $data_item2=array(
                    //     'jumlah_hitung'=>$item['item_jumlah'],
                    //     'jumlah_input'=>$item['item_jumlah'],
                    //     );
                    // $this->stock_opname_detail_m->save($data_item2,$item['id']);
                   // die(dump($this->db->last_query()));
                }                     
                    
            }

            // die_dump('refresh');

            // $data_resep_obat_racikan = array('`status`' => '2');
            // $update_resep_obat_racikan = $this->resep_obat_racikan_m->save($data_resep_obat_racikan, $array_input['resep_obat_racikan_id']);
            

            //========================

            if ($data['is_mismatch'] == 1) 
            {
            //     $data_detail2 = $this->input->post('quantity');
            //     foreach ($data_detail2 as $detail) 
            //     {
                

            //         if ($detail['jumlah_input'] != $detail['jumlah_sistem']) {
            //                 if($detail['jumlah_input']==0)
            //                 {
            //                     $delid=$this->stock_opname_detail_m->delete($detail['id']);
            //                     $getiidstockopnamedetail=$this->stock_opname_identitas_m->get_by(array('stok_opname_detail_id'=>$detail['id']));
            //                     foreach ($getiidstockopnamedetail as $rowstokopname ) {
            //                          $delid2=$this->stock_opname_identitas_m->delete($rowstokopname->id);
            //                     }

            //                    $get_id_inventory=$this->inventory_m->get_by(array('item_id'=>$detail['itemid'],'item_satuan_id'=>$detail['satuanid']));
            //                    foreach($get_id_inventory as $rowinventory)
            //                    {
            //                         $this->inventory_m->delete($rowinventory->id);
            //                    }
                                 
                                
                               

            //                 }else{
            //                     die(dump($this->input->post('identitas_'.$detail['itemid'])));
            //                      foreach ($this->input->post('identitas_'.$detail['itemid']) as $identitas) {
            //                             if($identitas['jumlah']==0)
            //                             {
            //                                 $this->inventory_identitas_m->delete($identitas['inventory_identitas_id']);
            //                             }
            //                      }

            //                     $result=$this->inventory_m->get_by(array('item_id'=>$detail['itemid'],'item_satuan_id'=>$detail['satuanid']));
            //                     foreach($result as $row)
            //                     {
            //                         $result2=$this->stock_opname_m->checkjumlah($row->id)->result_array();
            //                         if($result2[0]['counts']==0)
            //                         {
            //                             $this->inventory_m->delete($row->id);
            //                         }else{
            //                             $result3=$this->stock_opname_m->getjumlah($row->id)->result_array();
            //                             $data_jumlah['jumlah']=$result3[0]['jumlah'];
            //                             $this->inventory_m->save($data_jumlah,$row->id);
            //                         }

            //                     }

            //                     // $getiidstockopnamedetail=$this->stock_opname_identitas_m->get_by(array('stok_opname_detail_id'=>$detail['id']));
            //                     // foreach ($getiidstockopnamedetail as $rowstokopname ) 
            //                     // {
            //                     //     if($rowstokopname->jumlah_input==0)
            //                     //     {
            //                     //         $this->stock_opname_identitas_m->delete($rowstokopname->id);
            //                     //     }else{
            //                     //             $data1['jumlah_sistem']=$rowstokopname->jumlah_input;
            //                     //             $this->stock_opname_identitas_m->save($data1,$rowstokopname->id);
            //                     //     }

            //                     //     $result4=$this->stock_opname_m->gettotaljumlah($detail['id'])->result_array();
            //                     //     $data2['jumlah_sistem']=$resul4[0]['jumlah'];
            //                     //     $data2['jumlah_input']=$resul4[0]['jumlah'];
            //                     //     $data2['jumlah_hitung']=$resul4[0]['jumlah'];
            //                     //     $this->stock_opname_detail_m->save($data2,$detail['id']);
                                      
            //                     // }
 
            //                 }
                       
            //         }

            //     // unset($detail['system_qty']);
            //     // die_dump($detail);
            //    // $stock_detail_id = $this->stock_opname_detail_m->save($data2, $detail['id']); 

            // }    

             //   redirect('apotik/stock_opname/refresh2/'.$access_id.'/'.$this->input->post('warehouse_id').'/'.$this->input->post('warehouse_people_id').'');  
            } 
           // redirect('warehouse/rawgoods/stock_opname');
           //  redirect('apotik/stock_opname/index/'.$this->input->post('pk'));

        }

        if ($command == 'konfirmasi') {

            $id = $this->input->post('id');

            $data_detail = $this->input->post('quantity');
            // die_dump($data_detail);
            $is_mismatch = 0;
            foreach ($data_detail as $detail) 
            {
                if ($detail['input_qty'] != $detail['system_qty)']) {

                    $data['is_mismatch'] = 1;
                    $is_mismatch = 1;
                }
            }    

            $data['is_mismatch'] = $is_mismatch;

            $access_id = $this->stock_opname_m->save($data, $id);
            
            if ($access_id) {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Stock Opname has been inputed", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
                $this->session->set_flashdata($flashdata);
            }
          //  redirect('apotik/stock_opname/index/'.$this->input->post('pk'));            
        }

        if ($command === 'edit')
        {  

            $id = $this->input->post('id');
            $data_inventory = $this->input->post('items');
            // die_dump($data_inventory);
            foreach ($data_inventory as $inventory) 
            {
                if($inventory['item_id'] != '')
                {
                    unset($inventory['code']);
                    unset($inventory['name']);
                    
                    // $inventory['input_qty'];
                    $inventory['stok_opname_id'] = $id;

                    if ($inventory['is_deleted'] == 1) {
                        
                        $stock_opname_identitas_del=$this->stock_opname_identitas_m->get_by(array('stok_opname_detail_id'=>$inventory['id']));
                        foreach($stock_opname_identitas_del as $row)
                        {
                            $stock_opname_identitas_detail_del=$this->stock_opname_identitas_detail_m->get_by(array('stok_opname_identitas_id'=>$row->id));
                                foreach($stock_opname_identitas_detail_del as $row2)
                                {
                                    $stock_opname_identitas_detail_delete=$this->stock_opname_identitas_detail_m->delete($row2->id);
                                }
                                $stock_opname_identitas_delete=$this->stock_opname_identitas_m->delete($row->id);
                        }

                        $stock_opname_del = $this->stock_opname_detail_m->delete($inventory['id']); 
                        // die_dump($this->db->last_query());
                    }

                    $stock_opname_detail_id = $this->stock_opname_detail_m->get_by(array('stok_opname_id' => $id, 'item_id' => $inventory['item_id'],'item_satuan_id'=>$inventory['item_satuan_id']));
                    // die_dump($stock_opname_detail_id);
                    if (count($stock_opname_detail_id) == 0 && $inventory['is_deleted'] == '') {

                        unset($inventory['is_deleted']);
                        unset($inventory['id']);
                        unset($inventory['item_satuan_text']);


                        $inventory['jumlah_sistem']=$inventory['system_qty'];

                   
                        unset($inventory['system_qty']);
                    
                    
                        $stock_opname_id = $this->stock_opname_detail_m->save($inventory); 

                          //=======================
                        //$data_inventory_identitas = $this->input->post('items');
             
                        // foreach ($data_inventory_identitas as $inventory_identitas) 
                        // {
                            // if($inventory_identitas['item_id'] != '')
                            // {
                           //  $stock_opname_detail_id2 = $this->stock_opname_detail_m->get_by(array('stok_opname_id' => $id, 'item_id' => $inventory_identitas['item_id'],'item_satuan_id'=>$inventory_identitas['item_satuan_id']));
                            // if (count($stock_opname_detail_id2) == 0 && $inventory_identitas['is_deleted'] == '') {
                                $getidinventory1=$this->stock_opname_m->getid1($inventory['item_id'],$inventory['item_satuan_id'],$this->input->post('pk'))->result_array();
                           
                                foreach($getidinventory1 as $row)
                                {
                                    $data3['stok_opname_detail_id']=$stock_opname_id;
                                    $data3['jumlah_sistem']=$row['jumlah'];
                                    $getidinventory3=$this->stock_opname_identitas_m->save($data3); 
 
                                    $getidinventory2=$this->stock_opname_m->getid2($row['inventory_identitas_id'])->result_array();
                           
                                    foreach($getidinventory2 as $row)
                                    {
                                        $getlastid=$this->stock_opname_m->getlastid()->result_array();
                                       
                                        $data2['id']=$getlastid[0]['id']+1;
                                        $data2['stok_opname_identitas_id']=$getidinventory3;
                                        $data2['identitas_id']=$row['identitas_id'];
                                        $data2['judul']=$row['judul'];
                                        $data2['value']=$row['value'];

                                        $data2['created_by']=$this->session->userdata('user_id');
                                        $data2['created_date']=date('Y-m-d H:i:s');
                            
                                        $getidinventory4=$this->stock_opname_identitas_detail_m->add_data($data2); 
                                     //   die(dump($this->db->last_query()));
                                    }   
                                }


                               
                         //       }
                      //     }
                      //  }
                    //=======================
                       //  die(dump($stock_opname_id));
                    }

                    $data_modified['modified_by']=$this->session->userdata('user_id');
                    $data_modified['modified_date']=date('Y-m-d H:i:s');
                    $this->stock_opname_m->save($data_modified,$id);
                            

                    // die_dump($this->db->last_query());
 
                    if ($stock_opname_id) {
                        $flashdata = array(
                            "type"     => "success",
                            "msg"      => translate("Stock Opname has been edited", $this->session->userdata("language")),
                            "msgTitle" => translate("Success", $this->session->userdata("language"))    
                        );
                        $this->session->set_flashdata($flashdata);
                    }
                }
            }
        }
     //  redirect('apotik/stock_opname/refresh/'.$this->input->post('pk'));  
    }

    public function delete($id,$wareid)
    {
        $data = array(
            'is_active'    => 0
        );
    
        // save data
        $stock_id = $this->stock_opname_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id'   => $trash_id,
            'tipe'              => 2,
            'data_id'           => $id,
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);
        if ($stock_id) {
            $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Stok Opname sudah dihapus", $this->session->userdata("language")),
            "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
        }
       redirect('apotik/stock_opname/refresh/'.$wareid);
    }

    public function restore($id,$wareid)
    {
           
        $data = array(
            'is_active'    => 1
        );
    
        // save data
        $stock_id = $this->stock_opname_m->save($data, $id);
        if ($stock_id) {
            $flashdata = array(
            "type"     => "warning",
            "msg"      => translate("Stock Opname has been restored", $this->session->userdata("language")),
            "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
        }
        redirect('apotik/stock_opname/refresh/'.$wareid);
    }

    public function listingUser()
    {
        $result = $this->user_m->get_datatable();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            $i++;

            $action = '<a class="btn btn-xs blue" data-action="select" name="select[]" data-user_id="' . $row['id'] . '" title="'.translate("Pilih", $this->session->userdata("language")).'"><i class="fa fa-check"></i></a>';

            $output['aaData'][] = array(
                $row['id'],
                $row['fullname'],
                $row['branch_name'],
                '<div class="text-center">'.$action.'</div>'
            );
        }

        echo json_encode($output);

    }

    public function saveUserData()
    {

        if($this->input->is_ajax_request())
        {
            $id = $this->input->post('user_id');
            $arr_result = $this->user_m->get($id);
            // die(dump($arr_result));
            echo json_encode($arr_result);
        }
        else
            redirect(base_url());
    }

    public function printpdf($id,$warehouse_id,$warehouse_people_id){
        $result = $this->stock_opname_m->printpdf($id)->result_array();
       //  die_dump($this->db->last_query());
        $form_data = $this->stock_opname_m->get($id);
        $form_data_people = $this->warehouse_people_m->get($warehouse_people_id);
        $form_data_warehouse = $this->warehouse_m->get($warehouse_id);
       // $this->fpdf->fpdf("P", "mm", array(95,230));
         $this->fpdf->fpdf("P", "mm", "A4");
      //  $this->fpdf->Header(base64_encode(serialize($data_header)));
        $this->fpdf->Open();
        $this->fpdf->SetTitle("Judul Hardcode");
        $this->fpdf->SetAuthor("Author Hardcode");
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage();
        $this->fpdf->SetAutoPageBreak(TRUE, 10);
        $this->fpdf->SetSubject("Ayam Bakar");

        $posisi_y = $this->fpdf->GetY();

        $form_data2=object_to_array($form_data);
        $date = date_create($form_data2['tanggal_mulai']);
        if ($form_data2['tanggal_mulai'] != NULL) {
            $start_date = date_format($date, 'd F Y h:i');
        } else{
            $start_date = '';
        }
        $x=0;
   
         $form_data_people2=object_to_array($form_data_people);
         $form_data_warehouse2=object_to_array($form_data_warehouse);

         $this->fpdf->SetFont("Arial", "B", 7);
         $this->fpdf->Cell(30,6,'Nomor Stok Opname',0);
         $this->fpdf->Cell(5,6,':',0);
         $this->fpdf->Cell(30,6,$form_data2['no_stok_opname'],0);
         $this->fpdf->Ln();
         $this->fpdf->Cell(30,6,'Stok Opname Oleh',0);
         $this->fpdf->Cell(5,6,':',0);
         $this->fpdf->Cell(30,6,$form_data_people2['nama'],0);
         $this->fpdf->Ln();
         $this->fpdf->Cell(30,6,'Tanggal Mulai',0);
         $this->fpdf->Cell(5,6,':',0);
         $this->fpdf->Cell(30,6,$start_date,0);
         $this->fpdf->Ln();
         $this->fpdf->Cell(30,6,'Tanggal Selesai',0);
         $this->fpdf->Cell(5,6,':',0);
         $this->fpdf->Cell(30,6,$form_data2['tanggal_selesai'],0);
         $this->fpdf->Ln();
         $this->fpdf->Cell(30,6,'Gudang',0);
         $this->fpdf->Cell(5,6,':',0);
         $this->fpdf->Cell(30,6,$form_data_warehouse2['nama'],0);
    
         $this->fpdf->Ln(15);

        $this->fpdf->SetFont("Arial", "B", 7);
        $this->fpdf->Cell(70,6,'Kode',1,'','C');
        $this->fpdf->Cell(80,6,'Nama',1,'','C');
        $this->fpdf->Cell(20,6,'Satuan',1,'','C');
        $this->fpdf->Cell(20,6,'Jumlah',1,'','C');
        $this->fpdf->Ln();
        
        $i = 1;
        $sub_total_paket = 0;
        
        foreach ($result as $row) 
        {
            $this->fpdf->SetFont("Arial", "", 7);
            $this->fpdf->Cell(70,6,$row['item_code'],1,'','C');
            $this->fpdf->Cell(80,6,$row['item_name'],1);
            $this->fpdf->Cell(20,6,$row['nama_satuan'],1);
            $this->fpdf->Cell(20,6,'',1);
            
            $this->fpdf->Ln();
            $i++;

            $posisiY_obat = $this->fpdf->GetY();
           
            if($this->fpdf->GetY()>=260)
            { 
                $this->fpdf->AddPage();
                $this->fpdf->SetFont("Arial", "B", 7);
                $this->fpdf->Cell(70,6,'Kode',1);
                $this->fpdf->Cell(80,6,'Nama',1);
                $this->fpdf->Cell(20,6,'Satuan',1,'','C');
                $this->fpdf->Cell(20,6,'Jumlah',1,'','C');
                $this->fpdf->Ln();
            }
            
             else
          //  echo 'hii2' ;
                 $this->fpdf->SetY($posisiY_obat);

        }
        $this->fpdf->Ln(10);
         if($this->fpdf->GetY()>=260){
             $this->fpdf->AddPage();
              $this->fpdf->Cell(20,6,'Catatan',0);
              $this->fpdf->Cell(170,25,'',1);
              $this->fpdf->Ln(25);
              $this->fpdf->Cell(190,15,'Penanggungjawab,',0,'','R');
              $this->fpdf->Ln(18);
              $this->fpdf->Cell(168,15,'',0,'','R');
              $this->fpdf->Cell(22,15,'( '.$form_data_people2['name'].' )',0,'','C');
              

         }else{
              $this->fpdf->Cell(20,6,'Catatan',0);
              $this->fpdf->Cell(170,25,'',1);
              $this->fpdf->Ln(25);
              $this->fpdf->Cell(190,15,'Penanggung jawab,',0,'','R');
              $this->fpdf->Ln(18);
              $this->fpdf->Cell(168,15,'',0,'','R');
              $this->fpdf->Cell(22,15,'( '.$form_data_people2['nama'].' )',0,'','C');

               
               
         }

          $this->fpdf->Output("coba.pdf", "I");

    }

    public function getjumlahsistem()
    {
        
        
        $item_satuan_id=$this->input->post('item_satuan_id');
        $item_id=$this->input->post('item_id');
        $warehouse_id=$this->input->post('warehouse_id');
        
       
        $rows_assesment = $this->stock_opname_m->getjumlahsistem($item_satuan_id,$item_id,$warehouse_id)->result_array();
        // $rows_assesment=object_to_array($rows_assesment);

        
      
       
        echo json_encode($rows_assesment[0]['jumlah']);
    }

    public function checkmodifieddate()
    {
        $modifieddate='';
        if($this->input->post('modifieddate')=='-')
        {
            $modifieddate=null;
        }else{
            $modifieddate=$this->input->post('modifieddate');
        }
     //   $modifieddate=$this->input->post('modifieddate');
        $id=$this->input->post('id');
         $result='';
        
       
        $rows_assesment = $this->stock_opname_m->get_by(array('id'=>$id,'modified_date'=>$modifieddate,'is_active'=>1));
        if(count($rows_assesment) > 0)
        {
            $result='sukses';
        }else{
            $result='gagal';
        }
        // $rows_assesment=object_to_array($rows_assesment);

        
      
       
        echo json_encode($result);
    }

     public function listing_komposisi_item($id=null)
    {
        // $id = '1';
        $result = $this->resep_obat_racikan_m->get_datatable_komposisi_item($id);
        // die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['item_id'].'</div>',
                '<div class="text-center">'.$row['item_kode'].'</div>',
                '<div class="text-center">'.$row['item_nama'].'</div>',
                '<div class="text-center">'.$row['jumlah'].' '.$row['nama_satuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_komposisi_manual($id=null)
    {
        // $id = '1';
        $result = $this->resep_obat_racikan_m->get_datatable_komposisi_manual($id);
        // die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=1;
        foreach($records->result_array() as $row)
        {

            $keterangan = $row['keterangan'];
          
            $words = explode(' ', $keterangan);
          
            $impWords = implode(' ', array_splice($words, 0, 50));
            
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Keterangan',$this->session->userdata('language')).'" data-content="'.$keterangan.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

     public function modal_identitas($item_id, $item_satuan_id, $row_id)
    {
        
        $data = array(
        
            'item_id'        => $item_id,
            'item_satuan_id' => $item_satuan_id,
            'row_id'         => $row_id
             
        );
        $this->load->model('apotik/racik_obat/inventory_m');
        $this->load->view('apotik/stock_opname/modal/modal_identitas.php', $data);
    
    }

     public function get_jumlah_identitas(){

        $inventory_id   = $this->input->post('inventory_id');
        
        $inventory_identitas = $this->inventory_identitas_m->get_by(array('inventory_id' => $inventory_id));

        $hasil_inventory_identitas = object_to_array($inventory_identitas);

        echo json_encode($hasil_inventory_identitas);
    }
    
    
}

/* End of file  */
/* Location: ./application/controllers/ */