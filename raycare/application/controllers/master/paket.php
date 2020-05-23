<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paket extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 4;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(2, 4);       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('master/cabang__m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_poliklinik_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/paket_m');
        $this->load->model('master/paket_item_m');
        $this->load->model('master/paket_tindakan_m');
        $this->load->model('master/paket_batch_m');
        $this->load->model('master/paket_batch_item_m');
        $this->load->model('master/paket_batch_tindakan_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/poliklinik_paket_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_harga_m');
        // $this->load->model('master/item/item_sub_kategori_m');
        // $this->load->model('master/item/item_kategori_m');
        // $this->load->model('master/item/item_harga_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/paket/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Paket', $this->session->userdata('language')), 
            'header'         => translate('Master Paket', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/paket/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/paket/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). translate("Tambah Paket", $this->session->userdata("language")), 
            'header'         => translate("Tambah Paket", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/paket/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/paket/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->paket_m->set_columns($this->paket_m->fillable_edit());
       
        $form_data = $this->paket_m->get($id);
        $data_paket_item = $this->paket_item_m->get_by(array('paket_id' => $id ));
        $data_paket_tindakan = $this->paket_tindakan_m->get_by(array('paket_id' => $id ));
        $data_poliklinik_paket = $this->poliklinik_paket_m->get_by(array('paket_id' => $id ));
        // die_dump($data_poliklinik_paket);

        $data = array(
            'title'                 => config_item('site_name'). translate("Edit Paket", $this->session->userdata("language")), 
            'header'                => translate("Edit Paket", $this->session->userdata("language")), 
            'header_info'           => config_item('site_name'), 
            'breadcrumb'            => TRUE,
            'menus'                 => $this->menus,
            'menu_tree'             => $this->menu_tree,
            'css_files'             => $assets['css'],
            'js_files'              => $assets['js'],
            'content_view'          => 'master/paket/edit',
            'form_data'             => object_to_array($form_data),
            'data_paket_item'       => object_to_array($data_paket_item),
            'data_paket_tindakan'   => object_to_array($data_paket_tindakan),
            'data_poliklinik_paket' => object_to_array($data_poliklinik_paket),
            'pk_value'              => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function batch($id)
    {
        $assets = array();
        $config = 'assets/master/paket/batch';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));

        $data_paket = $this->paket_m->get($id);
        // die_dump($data_paket);

        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Batch', $this->session->userdata('language')), 
            'header'         => translate('Master Batch', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/paket/batch',
            'data_paket'     => object_to_array($data_paket),
            'pk'             => $id,
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/master/paket/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $paket_data = $this->paket_m->get($id);
        $tipe       = object_to_array($paket_data);
        // die_dump($paket_data);

        // die(dump( $assets['css'] ));

        $data = array(
            'title'        => 'RayCare &gt;'.translate('Paket', $this->session->userdata('language')), 
            'header'       => translate('View Paket', $this->session->userdata('language')), 
            'header_info'  => 'RayCare', 
            'breadcrumb'   => true,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'paket_data'   => object_to_array($paket_data),
            'content_view' => 'master/paket/view',
            'pk_value'     => $id
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function add_batch($id)
    {
        $assets = array();
        $assets_config = 'assets/master/paket/add_batch';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->paket_m->get($id);
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name'). translate("Tambah Paket Batch", $this->session->userdata("language")), 
            'header'         => translate("Tambah Paket Batch", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/paket/add_batch',
            'form_data'      => object_to_array($form_data),
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit_batch($paket_id, $id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/paket/edit_batch';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
       
        $form_data           = $this->paket_batch_m->get($id);
        $data_paket_item     = $this->paket_batch_item_m->get_by(array('paket_batch_id' => $id ));
        $data_paket_tindakan = $this->paket_batch_tindakan_m->get_by(array('paket_batch_id' => $id ));

        $data = array(
            'title'               => config_item('site_name'). translate("Edit Paket Batch", $this->session->userdata("language")), 
            'header'              => translate("Edit Paket Batch", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'master/paket/edit_batch',
            'form_data'           => object_to_array($form_data),
            'data_paket_item'     => object_to_array($data_paket_item),
            'data_paket_tindakan' => object_to_array($data_paket_tindakan),
            'pk_value'            => $id                        
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    
    public function view_batch($id)
    {
        $assets = array();
        $config = 'assets/master/paket/view_batch';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $paket_batch = $this->paket_batch_m->get($id);
        $tipe       = object_to_array($paket_batch);
        // die_dump($paket_batch);

        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => 'RayCare &gt;'.translate('Paket', $this->session->userdata('language')), 
            'header'         => translate('View Paket', $this->session->userdata('language')), 
            'header_info'    => 'RayCare', 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'paket_batch'    => object_to_array($paket_batch),
            'content_view'   => 'master/paket/view_batch',
            'pk_value'       => $id
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }


    /**
     * [list description]
     * @return [type] [description]
     */

    public function change_order()
    {
        if(! $this->input->is_ajax_request()) redirect(base_url());

        $paket_id = $this->input->post('paket_id');
        $id = $this->input->post('id');
        $order  = intval($this->input->post('order'));
        $command = $this->input->post('command');

        if($command == 'up')
            $this->paket_batch_m->up_order($paket_id,$id, $order);    
        else
            $this->paket_batch_m->down_order($paket_id,$id, $order);
        
    }

    


    public function listing()
    {        
        $result = $this->paket_m->get_datatable();
        // die_dump($result);
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
            $action = '';
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/paket/view/'.$row['id'].'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>
                       <a title="'.translate('Batch', $this->session->userdata('language')).'" href="'.base_url().'master/paket/batch/'.$row['id'].'" class="btn btn-xs btn-primary"><i class="fa fa-list"></i></a>
                       <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/paket/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data Paket ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
           

            $output['data'][] = array(
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-right">'.$this->formatrupiah($row['harga_total']).'</div>',
                $row['keterangan'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_alat_obat($cabang_id = null, $status_so_history = null)
    {
        $result = $this->item_m->get_datatable2($cabang_id, $status_so_history);
        // die(dump($this->db->last_query()));

        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        // die_dump($records);
        $i = 0;
        foreach($records->result_array() as $row)
        {

            $kategori = '';
            if ($row['item_kategori_id'] == 1) $kategori = '<div class="text-center">'.$row['kategori_item'].'</div>';
            if ($row['item_kategori_id'] == 2) $kategori = '<div class="text-center">'.$row['kategori_item'].'</div>';
            
            // $kategori = $row['kategori_item'];
            
            $satuan         = $this->item_satuan_m->get_by(array('item_id' => $row['id']));
            $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1),true);
            $satuan         = object_to_array($satuan);

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($satuan)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-xs btn-primary select"><i class="fa fa-check"></i></a>';
             
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['unit'].'</div>',
                '<div class="text-right">'.$this->formatrupiah($row['harga']).'</div>',
                '<div class="text-center">'.$kategori.'</div>',
                $row['keterangan'],
                '<div class="text-center">'.$action.'</div>'
            );      

            $i++;
        }

      echo json_encode($output);

    }

    public function listing_tindakan()
    {
        //  $this->load->model('finance/account_categories_m');
        $result = $this->tindakan_m->get_datatable();
        // die(dump($this->db->last_query()));     
  
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        
        foreach($records->result_array() as $row)
        {
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary select-tindakan"><i class="fa fa-check"></i></a>';
             $output['aaData'][] = array(
                $row['kode'],
                $row['nama'],
                '<div class="text-center">'.$this->formatrupiah($row['harga']).'</div>',
                $row['keterangan'],
                '<div class="text-center">'.$action.'</div>'
               );             
        }

       // die(dump($this->db->last_query()));


      echo json_encode($output);

    }

    public function listing_paket_item($so_id)
    {
        
        $result = $this->paket_item_m->get_datatable_view($so_id);

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

        $subtotal = 0;


          $i = 0;
          $count = count($records->result_array());
          $inputSubtotal = '';
          $status = '';

        foreach($records->result_array() as $row)
        {        
            $i++;        

            $subtotal = $subtotal + ($row['jumlah'] * $row['harga']);

            if ($i == $count) 
            {
                $inputSubtotal = '<input type="hidden" id="subtotal" name="subtotal" value="'.$subtotal.'">';

            }

            if ($row['is_sale'] == 1)
            {
                $status = 'Ya';
            } else {
                $status = 'Tidak';
            }

            $output['aaData'][] = array
            (
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama_item'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
                '<div class="text-center">'.$row['nama_satuan'].'</div>',
                '<div class="text-right">'.'Rp. ' . number_format($row['harga'],0,'','.').'</div>',
                '<div class="text-right">'.'Rp. ' . number_format($subtotal,0,'','.'). ',-'.$inputSubtotal.'</div>',
            );                      

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function listing_batch($id)
    {        
        $result = $this->paket_batch_m->get_datatable($id);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $count = count($records->result_array());

        $i=0;
        $tipe = '';
        foreach($records->result_array() as $row)
        {

            $order = '';
            if($i == 0) //first row
                $order = '<a title="" data-action="move_order" data-id="'.$row['id'].'" data-paket_id="'.$row['paket_id'].'" data-command="down" data-order="'.$row['level_order'].'" class="btn btn-xs default"><i class="fa fa-caret-down"></i></a>';
            elseif($i == $count-1)  //last row
                $order = '<a data-action="move_order" data-id="'.$row['id'].'" data-paket_id="'.$row['paket_id'].'" data-command="up" data-order="'.$row['level_order'].'" class="btn btn-xs default"><i class="fa fa-caret-up"></i></a>';
            else    //middle row
                $order = '<a data-action="move_order" data-id="'.$row['id'].'" data-paket_id="'.$row['paket_id'].'" data-command="up" data-order="'.$row['level_order'].'" class="btn btn-xs default"><i class="fa fa-caret-up"></i></a>
                            <a data-action="move_order" data-id="'.$row['id'].'" data-paket_id="'.$row['paket_id'].'"  data-command="down" data-order="'.$row['level_order'].'" class="btn btn-xs default"><i class="fa fa-caret-down"></i></a>';


            if ($row['tipe'] == 1) {

                $tipe = 'Obat';

            } else {

                $tipe = 'Tindakan';

            }


            $action = '';
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/paket/view_batch/'.$row['id'].'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>
                       <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/paket/edit_batch/'.$id.'/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data Paket Batch ini?', $this->session->userdata('language')).'" name="delete_batch[]" data-action="delete_batch" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
           

            $output['data'][] = array(
                $tipe,
                $row['nama'],
                '<div class="text-center">'.$order.'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_paket_item_batch($id)
    {
        
        $result = $this->paket_item_m->get_datatable($id);

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

        $i = 0;
        $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {        

            $nama_item    = '<input class="form-control input-sm hidden" id="nama_item" name="items['.$i.'][nama_item]" value="'.$row['nama_item'].'">';
            $item_id      = '<input class="form-control input-sm hidden" id="item_id" name="items['.$i.'][item_id]" value="'.$row['item_id'].'">';
            $jumlah       = '<input class="form-control input-sm hidden" id="jumlah_item" name="items['.$i.'][jumlah_item]" value="'.$row['jumlah'].'">';
            $in_count     = '<input class="form-control input-sm hidden" id="count_temp" value="'.$i.'">';
            $in_jumlah    = '<input type="number" min="0" max="'.$row['jumlah'].'" class="form-control input-sm" id="jumlah" name="items['.$i.'][jumlah]" value="0">';

            $satuan = $this->item_satuan_m->get_by(array('item_id' => $row['item_id']));      
            $satuan_option = array(
                ''  => 'Pilih Satuan',
            );
            foreach ($satuan as $data_satuan)   
            {
                $satuan_option[$data_satuan->id] = $data_satuan->nama;
            }

            $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1));      

            $in_satuan    = form_dropdown('items['.$i.'][tipe_satuan]', $satuan_option, $satuan_primary[0]->id, 'id="tipe_satuan_'.$i.'" class="form-control" ');
            

            $output['aaData'][] = array(
                    ($i == $count) ? '<div class="text-center"><input class="checkboxes" name="items['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['paket_id'].'">'.$in_count.'</div>' : '<div class="text-center"><input class="checkboxes" name="items['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['paket_id'].'"></div>',
                    '<div class="text-center">'.$row['nama_item'].$nama_item.$item_id.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$jumlah.'</div>',
                    '<div class="text-center">'.$in_jumlah.'</div>',
                    '<div class="text-center">'.$in_satuan.'</div>',
                    
                   );    
            $i++;        

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function listing_paket_item_batch_edit($paket_id, $paket_batch_id)
    {

        $batch_item_checked = $this->paket_batch_item_m->get_data_checked($paket_batch_id)->result_array();

        $result = $this->paket_item_m->get_datatable($paket_id);
        
        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        // die_dump($records);

        $i = 0;
        $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {   
            
            $batch_item_id = '';
            $checkbox   = '';
            $val_jml    = '0';
            $set_satuan = '';
            $found = false;
            foreach ($batch_item_checked as $item_batch) 
            {
                if ($item_batch['item_id'] == $row['item_id']) 
                {
                    $found         = true;
                    $val_jml       = $item_batch['jumlah'];
                    $set_satuan    = $item_batch['item_satuan_id'];
                    $batch_item_id = $item_batch['id'];
                }
                
            }
            
            ($found == true) ? $check = 'checked' : $check = '';

            $checkbox      = '<input class="checkboxes" '.$check.' name="items['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['paket_id'].'">';
            $item_id       = '<input class="form-control input-sm hidden" id="item_id" name="items['.$i.'][item_id]" value="'.$row['item_id'].'">';
            $batch_item_id = '<input class="form-control input-sm hidden" id="batch_item_id_'.$i.'" name="items['.$i.'][batch_item_id]" value="'.$batch_item_id.'">';
            $in_jumlah     = '<input type="number" min="0" max="'.$row['jumlah'].'" class="form-control input-sm" id="jumlah" name="items['.$i.'][jumlah]" value="'.$val_jml.'">';


            $satuan = $this->item_satuan_m->get_by(array('item_id' => $row['item_id']));            
            $satuan_option = array(
                ''  => 'Pilih Satuan',
            );
            foreach ($satuan as $data_satuan)   
            {
                $satuan_option[$data_satuan->id] = $data_satuan->nama;
            }

            $in_satuan = form_dropdown('items['.$i.'][tipe_satuan]', $satuan_option, $set_satuan, 'id="tipe_satuan_'.$i.'" class="form-control" ');

            $output['aaData'][] = array(

                '<div class="text-center">'.$checkbox.'</div>',
                '<div class="text-center">'.$row['nama_item'].$item_id.$batch_item_id.'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
                '<div class="text-center">'.$in_jumlah.'</div>',
                '<div class="text-center">'.$in_satuan.'</div>',
                    
            );    
            $i++;        

            
        }
        
        // die_dump($this->db->last_query());
        echo json_encode($output);
    }

    public function listing_paket_item_batch_view($paket_batch_id)
    {
        $result = $this->paket_batch_item_m->get_datatable_edit_batch($paket_batch_id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );

        $i = 0;
        $records = $result->records;
        foreach($records->result_array() as $row)
        {        

            $satuan = $this->item_satuan_m->get_by(array('item_id' => $row['item_id']));            
            // die_dump($satuan);   
            $satuan_option = array(
                ''  => 'Pilih Satuan',
            );
            foreach ($satuan as $data_satuan)   
            {
                $satuan_option[$data_satuan->id] = $data_satuan->nama;
            }

            $in_satuan    = form_dropdown('items['.$i.'][tipe_satuan]', $satuan_option, $row['item_satuan_id'], 'id="tipe_satuan_'.$i.'" disabled class="form-control" ');

            $output['aaData'][] = array(
                    '<div class="text-center">'.$row['nama_item'].'</div>',
                    '<div class="text-center">'.$row['jumlah'].'</div>',
                    '<div class="text-center">'.$in_satuan.'</div>',
                    
                   );    
            $i++;        

        }
        
        echo json_encode($output);
    }

    public function listing_paket_tindakan_batch($id)
    {
        
        $result = $this->paket_tindakan_m->get_datatable($id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        // die_dump($records);

          $i = 0;
          $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {        

            $nama_tindakan = '<input class="form-control input-sm hidden" id="nama_tindakan" name="tindakan['.$i.'][nama_tindakan]" value="'.$row['nama_tindakan'].'">';
            $tindakan_id   = '<input class="form-control input-sm hidden" id="tindakan_id" name="tindakan['.$i.'][tindakan_id]" value="'.$row['tindakan_id'].'">';
            $jumlah        = '<input class="form-control input-sm hidden" id="jumlah_tindakan" name="tindakan['.$i.'][jumlah_tindakan]" value="'.$row['jumlah'].'">';
            $in_count      = '<input class="form-control input-sm hidden" id="count_temp" value="'.$i.'">';
            $in_jumlah     = '<input type="number" min="0" max="'.$row['jumlah'].'" class="form-control input-sm" id="jumlah" name="tindakan['.$i.'][jumlah]" value="0">';


            $output['aaData'][] = array(
                    ($i == $count) ? '<div class="text-center"><input class="checkboxes" name="tindakan['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['paket_id'].'">'.$in_count.'</div>' : '<div class="text-center"><input class="checkboxes" name="tindakan['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['paket_id'].'"></div>',
                    '<div class="text-center">'.$row['nama_tindakan'].$nama_tindakan.$tindakan_id.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$jumlah.'</div>',
                    '<div class="text-center">'.$in_jumlah.'</div>',
                    
                   );                      
            $i++;        
            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function listing_paket_tindakan_batch_edit($paket_id, $paket_batch_id)
    {

        $batch_tindakan_checked = $this->paket_batch_tindakan_m->get_data_checked($paket_batch_id)->result_array();

        $result = $this->paket_tindakan_m->get_datatable($paket_id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );

        $i = 0;
        $records = $result->records;
        foreach($records->result_array() as $row)
        {        

            $checkbox          = '';
            $batch_tindakan_id = '';
            $val_jml           = '0';
            $found             = false;
            foreach ($batch_tindakan_checked as $tindakan_batch) 
            {
                if ($tindakan_batch['tindakan_id'] == $row['tindakan_id']) 
                {
                    $found             = true;
                    $val_jml           = $tindakan_batch['jumlah'];
                    $batch_tindakan_id = $tindakan_batch['id'];
                }
                
            }
            
            ($found == true) ? $check = 'checked' : $check = '';

            $tindakan_id       = '<input class="form-control input-sm hidden" id="tindakan_id" name="tindakan['.$i.'][tindakan_id]" value="'.$row['tindakan_id'].'">';
            $batch_tindakan_id = '<input class="form-control input-sm hidden" id="batch_tindakan_id_'.$i.'" name="tindakan['.$i.'][batch_tindakan_id]" value="'.$batch_tindakan_id.'">';
            $in_jumlah         = '<input type="number" min="0" max="'.$row['jumlah'].'" class="form-control input-sm" id="jumlah" name="tindakan['.$i.'][jumlah]" value="'.$val_jml.'">';

            $output['aaData'][] = array(

                '<div class="text-center"><input '.$check.' class="checkboxes" name="tindakan['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['paket_id'].'"></div>',
                '<div class="text-center">'.$row['nama_tindakan'].$tindakan_id.$batch_tindakan_id.'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
                '<div class="text-center">'.$in_jumlah.'</div>',
                
            );                      
            
            $i++;        
        }
        
        echo json_encode($output);
    }

    public function listing_paket_tindakan_batch_view($paket_batch_id)
    {
        
        $result = $this->paket_batch_tindakan_m->get_datatable_view_batch($paket_batch_id);

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

          $i = 0;
          $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {        

            $nama_tindakan = '<input class="form-control input-sm hidden" id="nama_tindakan" name="tindakan['.$i.'][nama_tindakan]" value="'.$row['nama_tindakan'].'">';
            $tindakan_id   = '<input class="form-control input-sm hidden" id="tindakan_id" name="tindakan['.$i.'][tindakan_id]" value="'.$row['tindakan_id'].'">';
            $jumlah        = '<input class="form-control input-sm hidden" id="jumlah_tindakan" name="tindakan['.$i.'][jumlah_tindakan]" value="'.$row['jumlah'].'">';


            $output['aaData'][] = array(
                    '<div class="text-center">'.$row['nama_tindakan'].$nama_tindakan.$tindakan_id.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$jumlah.'</div>',
                    
                   );                      
            $i++;        
            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }


    public function listing_paket_tindakan($so_id)
    {
        
        $result = $this->paket_tindakan_m->get_datatable_view($so_id);

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

        $subtotal = 0;


          $i = 0;
          $count = count($records->result_array());
          $inputSubtotal = '';

        foreach($records->result_array() as $row)
        {        
            $i++;        

            $subtotal = $subtotal + ($row['jumlah'] * $row['harga']);

            if ($i == $count) 
            {
                $inputSubtotal = '<input type="hidden" id="subtotal_tindakan" name="subtotal_tindakan" value="'.$subtotal.'">';

            }

            $output['aaData'][] = array(
                    '<div class="text-center">'.$row['kode'].'</div>',
                    '<div class="text-left">'.$row['nama_tindakan'].'</div>',
                    '<div class="text-center">'.$row['jumlah'].'</div>',
                    '<div class="text-right">'.'Rp. ' . number_format($row['harga'],0,'','.').'</div>',
                    '<div class="text-right">'.'Rp. ' . number_format($subtotal,0,'','.'). ',-'.$inputSubtotal.'</div>',
                   );                      

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function save()
    {
        $command     = $this->input->post('command');
        $array_input = $this->input->post();
        $items       = $this->input->post('account');
        $items2      = $this->input->post('tindakan');
        $poli        = $this->input->post('poli');
        

        if ($command === 'add')
        {  
            $data = array(

                'cabang_id'         => $this->input->post('tipe_transaksi'),
                'kode'              => $this->input->post('kode'),
                'nama'              => $this->input->post('nama_paket'),
                'biaya_tambahan'    => $this->input->post('biaya_tambahan'),
                'harga_total'       => $this->input->post('total_keseluruhan_hidden'),
                'keterangan'        => $this->input->post('keterangan'),
                'is_active'         => 1,
            );

            $paket_id = $this->paket_m->save($data);
            // die_dump($this->db->last_query());
            
            foreach ($poli as $poli_id)
            {

                $data_poliklinik_paket = array( 

                    'poliklinik_id'     => $poli_id,
                    'paket_id'          => $paket_id,
                    'is_active'         => 1,
                );

                $poliklinik_paket_id = $this->poliklinik_paket_m->save($data_poliklinik_paket);

            }

            foreach ($items as $item) 
            {
                if ($item['account_id'] != '')
                {
                    $data_item = array
                    (
                        'paket_id'       => $paket_id,
                        'item_id'        => $item['account_id'],
                        'item_satuan_id' => $item['satuan'],
                        'jumlah'         => $item['jumlah'],
                        'harga'          => $item['harga'],
                        'is_sale'        => $item['dijual'],
                        'is_active'      => 1,
                    );
                    $paket_item = $this->paket_item_m->save($data_item);
                }
            }

            foreach ($items2 as $item2)
            {
                if ($item2['tindakan_id'] != '')
                {
                    $data_item_tindakan = array(

                        'paket_id'    => $paket_id,
                        'tindakan_id' => $item2['tindakan_id'],
                        'jumlah'      => $item2['jumlah_tindakan'],
                        'harga'       => $item2['harga_tindakan'],
                        'is_active'   => 1,
                    );

                    $paket_tindakan = $this->paket_tindakan_m->save($data_item_tindakan);
                }
            }


            if ($paket_id && $paket_item && $paket_tindakan) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data paket berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }
        
        elseif ($command === 'edit')
        {
            $pak_id        = $this->input->post('id');
            $data_item     = $this->input->post('account');
            $data_tindakan = $this->input->post('tindakan');
            

            // =========================== PAKET ======================================

            $data = array(
                'cabang_id'         => $this->input->post('tipe_transaksi'),
                'nama'              => $this->input->post('nama_paket'),
                'biaya_tambahan'    => $this->input->post('biaya_tambahan'),
                'harga_total'       => $this->input->post('total_keseluruhan_hidden'),
                'keterangan'        => $this->input->post('keterangan'),
            );
            $paket_id = $this->paket_m->save($data, $pak_id);


            // ======================= POLIKLINIK PAKET ========================
            
            $cabang_poliklinik = $this->cabang_poliklinik_m->get_cabang_id($array_input['tipe_transaksi'])->result_array();
            // die_dump($cabang_poliklinik);
            foreach ($cabang_poliklinik as $data_cabang_pol) 
            {
                $poliklinik_id = $this->poliklinik_paket_m->get_poliklinik_paket_id($paket_id, $data_cabang_pol['poliklinik_id']);

                $poliklinik_id_result_array = $poliklinik_id->result_array();

                if($poliklinik_id->num_rows() == 0)
                {
                    if(in_array($data_cabang_pol['poliklinik_id'], $array_input['poli']))
                    {
                        $data_poli_paket = array
                        (
                            "poliklinik_id" => $data_cabang_pol['poliklinik_id'],
                            "paket_id"      => $paket_id,
                            "is_active"     => 1
                        );
                        $save_poli_paket = $this->poliklinik_paket_m->save($data_poli_paket);
                    }
                }
                else 
                {
                    if (!in_array($data_cabang_pol['poliklinik_id'], $array_input['poli'])) 
                    {
                        $this->poliklinik_paket_m->delete($poliklinik_id_result_array[0]['id']);
                    }
                }
                
            }


            // =========================== TABLE ALAT & OBAT ======================================
            
            $data_item = $this->input->post('account');
            foreach ($data_item as $item)
            {

                // INSERT BARU ====================
                if ($item['account_id'] == '' && $item['item_id'] != '') 
                {
                    $data_item = array
                    (
                        'paket_id'       => $paket_id,
                        'item_id'        => $item['item_id'],
                        'item_satuan_id' => $item['satuan'],
                        'jumlah'         => $item['jumlah'],
                        'harga'          => $item['harga'],
                        'is_sale'        => $item['dijual'],
                        'is_active'      => 1,
                    );
                    $paket_item_id = $this->paket_item_m->save($data_item);
                }

                
                if ($item['item_id'] != '') 
                {
                    if ($item['is_delete'] == '') 
                    {
                        // UPDATE 
                        $data_update = array
                        (
                            'item_satuan_id' => $item['satuan'],
                            'jumlah'         => $item['jumlah'],
                            'harga'          => $item['harga'],
                            'is_sale'        => $item['dijual'],
                        );
                        $paket_item_id = $this->paket_item_m->save($data_update, $item['account_id']);
                    } 
                    else 
                    {
                        // DELETE 
                        $this->paket_item_m->delete($item['account_id']);
                    }
                }
            }


            // =========================== TABLE TINDAKAN ======================================
            // die_dump($this->input->post('tindakan'));
            $data_tindakan = $this->input->post('tindakan');
            foreach ($data_tindakan as $item_tindakan)
            {
                // SAVE BARU ======================
                if ($item_tindakan['paket_tindakan_id'] == '' && $item_tindakan['tindakan_id'] != '') 
                {
                    $data_item_tindakan = array
                    (
                        'paket_id'    => $paket_id,
                        'tindakan_id' => $item_tindakan['tindakan_id'],
                        'jumlah'      => $item_tindakan['jumlah_tindakan'],
                        'harga'       => $item_tindakan['harga_tindakan'],
                        'is_active'   => 1,
                    );

                    $paket_tindakan_id = $this->paket_tindakan_m->save($data_item_tindakan);
                }

                if($item_tindakan['tindakan_id'] != '') 
                {
                    if ($item_tindakan['is_delete'] == '') 
                    {
                        // UPDATE ==================
                        $data_item_tindakan = array
                        (
                            'jumlah'      => $item_tindakan['jumlah_tindakan'],
                        );

                        $paket_tindakan_id = $this->paket_tindakan_m->save($data_item_tindakan, $item_tindakan['paket_tindakan_id']);
                        
                    }
                    else 
                    {
                        // DELETE ==================
                        $this->paket_tindakan_m->delete($item_tindakan['paket_tindakan_id']);
                    }
                }
            }

            if ($paket_id && $paket_item_id && $paket_tindakan_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Paket berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/paket");
    }

    public function save_batch()
    {
        $command                 = $this->input->post('command');
        $array_input             = $this->input->post();
        $items                   = $this->input->post('items');
        $items2                  = $this->input->post('tindakan');
        $paket_id                = $this->input->post('paket_id');
        $paket_batch_id_item     = $this->input->post('paket_batch_id_item');
        $paket_batch_id_tindakan = $this->input->post('paket_batch_id_tindakan');
        // die_dump($paket_id);
        // $items = $this->input->post('items');
        // $items2 = $this->input->post('tindakan');
        // die_dump($this->input->post());

        if ($command === 'add')
        {

            $level_order = array('paket_id' => $paket_id);
            $count = count($this->paket_batch_m->get_by($level_order));

            $data_batch = array(

                'paket_id'      => $paket_id,
                'tipe'          => $this->input->post('tipe'),
                'nama'          => $this->input->post('nama_paket_batch'),
                'level_order'   => $count + 1,
                'is_active'     => 1,

            );

            $paket_batch = $this->paket_batch_m->save($data_batch);

            foreach ($items as $item) {
                
                if (isset($item['checkbox'])) {

                    $data_paket_batch_item = array(

                            'paket_batch_id' => $paket_batch,
                            'item_id'        => $item['item_id'],
                            'jumlah'         => $item['jumlah'],
                            'item_satuan_id' => $item['tipe_satuan'],
                            'is_active'      => 1,

                        );

                    $paket_batch_item = $this->paket_batch_item_m->save($data_paket_batch_item);

                }

            }

            foreach ($items2 as $tindakan) {

                 if (isset($tindakan['checkbox'])) {

                    $data_paket_batch_tindakan = array(

                            'paket_batch_id' => $paket_batch,
                            'tindakan_id'    => $tindakan['tindakan_id'],
                            'jumlah'         => $tindakan['jumlah'],
                            'is_active'      => 1,

                        );

                    $paket_batch_tindakan = $this->paket_batch_tindakan_m->save($data_paket_batch_tindakan);

                }
            }
        }

        if ($command === 'edit')
        {
            
            $level_order = array('paket_id' => $paket_id);
            $count       = count($this->paket_batch_m->get_by($level_order));

            $data_batch = array(

                'tipe'          => $this->input->post('tipe'),
                'nama'          => $this->input->post('nama_paket_batch'),
            );

            $paket_batch = $this->paket_batch_m->save($data_batch, $this->input->post('pk_value'));

            foreach ($items as $item) {

                if (isset($item['checkbox'])) 
                {
                    if ($item['batch_item_id'] == '') 
                    {
                        $data_paket_batch_item = array(

                            'paket_batch_id' => $paket_batch,
                            'item_id'        => $item['item_id'],
                            'jumlah'         => $item['jumlah'],
                            'item_satuan_id' => $item['tipe_satuan'],
                            'is_active'      => 1,

                        );

                        $paket_batch_item = $this->paket_batch_item_m->save($data_paket_batch_item);
                    } 
                    else 
                    {
                        $data_paket_batch_item = array(

                            'jumlah'         => $item['jumlah'],
                            'item_satuan_id' => $item['tipe_satuan'],

                        );

                        $paket_batch_item = $this->paket_batch_item_m->save($data_paket_batch_item, $item['batch_item_id']);
                    }
                    
                } 
                else 
                {
                    $paket_batch_item = $this->paket_batch_item_m->delete($item['batch_item_id']);
                }

            }

            foreach ($items2 as $tindakan) {

                if (isset($tindakan['checkbox'])) 
                {
                    if ($tindakan['batch_tindakan_id'] == '') 
                    {
                        $data_paket_batch_tindakan = array(

                            'paket_batch_id' => $paket_batch,
                            'tindakan_id'    => $tindakan['tindakan_id'],
                            'jumlah'         => $tindakan['jumlah'],
                            'is_active'      => 1,

                        );

                        $paket_batch_tindakan = $this->paket_batch_tindakan_m->save($data_paket_batch_tindakan);
                    }
                    else
                    {
                        $data_paket_batch_tindakan = array(

                            'jumlah'         => $tindakan['jumlah'],

                        );

                        $paket_batch_tindakan = $this->paket_batch_tindakan_m->save($data_paket_batch_tindakan, $tindakan['batch_tindakan_id']);
                    }
                }

                else
                {
                    $paket_batch_tindakan = $this->paket_batch_tindakan_m->delete($tindakan['batch_tindakan_id']);
                }

            }

        }

        if ($paket_batch && $paket_batch_item && $paket_batch_tindakan) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Paket Batch berhasil diubah", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        
        redirect("master/paket/batch/$paket_id");

    }


    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->paket_m->save($data, $id);

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

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Paket Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/paket");
    }

    public function delete_batch($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->paket_batch_m->save($data, $id);

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
            'tipe'            => 10,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);
        // die_dump($this->db->last_query());

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Paket Batch Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/paket");
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

    public function formatrupiah($val) 
    {
        $hasil ='Rp. ' . number_format($val, 0 , '' , '.' ) . ',-';
        return $hasil;
    }

    public function get_cabang_id(){
        
        $id = $this->input->post('id');
        $items = $this->cabang_poliklinik_m->get_cabang_id($id);

        $items_array = object_to_array($items->result());
        echo json_encode($items_array);

    }

    public function get_data_cabang_id(){
        
        $paket_id = $this->input->post('paket_id');

        $this->poliklinik_paket_m->set_columns(array('poliklinik_id'));
        $data_poliklinik = $this->poliklinik_paket_m->get_by(array('paket_id' => $paket_id));

        $poliklinik_paket = object_to_array($data_poliklinik);
        echo json_encode($poliklinik_paket);

    }

    public function get_satuan_harga()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;

            $id = $this->input->post('id');

            $harga = $this->item_harga_m->get_harga($id)->result_array();;

            if (count($harga)){
                $response->success = true;
                $response->rows = $harga[0];
            }

            die(json_encode($response));

        }
    }

    public function check_modified_paket()
    {
        if($this->input->is_ajax_request())
        {
            $response = new StdClass;
            $response->success = false;
            $response->msg = translate('Data yang akan anda ubah telah diubah oleh user lain. Apakah anda ingin melihat perubahannya?', $this->session->userdata('language'));

            $id = $this->input->post('id');
            $modified_date = $this->input->post('modified_date');

            $paket = $this->paket_m->get($id);

            if($paket->modified_date == $modified_date)
            {
                $response->success = true;
                $response->msg = '';
            }

            echo json_encode($response);

        }
    }


}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */