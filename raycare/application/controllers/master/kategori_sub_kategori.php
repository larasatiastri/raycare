<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kategori_sub_kategori extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id  = 3;                  // untuk check bit_access
    private $menus      = array();
    private $menu_tree  = array(2, 3);       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('finance/akun_kategori_m');
        $this->load->model('master/kategori_m');
        $this->load->model('master/item/item_sub_kategori_m');
        $this->load->model('master/item/item_sub_kategori_pembelian_m');
        $this->load->model('master/item/item_sub_kategori_spesifikasi_m');
        $this->load->model('master/user_level_m');
        $this->load->model('master/spesifikasi_m');
        $this->load->model('master/spesifikasi_detail_m');
        $this->load->model('others/kotak_sampah_m');

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/kategori_sub_kategori/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Kategori Sub Kategori', $this->session->userdata('language')), 
            'header'         => translate('Kategori Sub Kategori', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_sub_kategori/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {        
        $result = $this->kategori_m->get_datatable();

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
            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/kategori_sub_kategori/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                        <a title="'.translate('Hapus', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data kategori ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
       
            $output['data'][] = array(
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_sub()
    {        
        $result = $this->item_sub_kategori_m->get_datatable();
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
            $tipe = '';
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/kategori_sub_kategori/view_sub/'.$row['id'].'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>
                        <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/kategori_sub_kategori/edit_sub/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                        <a title="'.translate('Hapus', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data sub kategori ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
            
            if($row['tipe'] == 1){
                $tipe = "Umum";
            }if($row['tipe'] == 2){
                $tipe = "Obat";
            }if($row['tipe'] == 3){
                $tipe = "Alkes";
            }

            $output['data'][] = array(
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['kategori'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function tambah()
    {
        $assets = array();
        $assets_config = 'assets/master/kategori_sub_kategori/tambah';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("Tambah Kategori", $this->session->userdata("language")), 
            'header'         => translate("Tambah Kategori", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_sub_kategori/tambah',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function tambah_sub()
    {
        $assets = array();
        $assets_config = 'assets/master/kategori_sub_kategori/tambah_sub';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("Tambah Sub Kategori", $this->session->userdata("language")), 
            'header'         => translate("Tambah Sub Kategori", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_sub_kategori/tambah_sub',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_persetujuan()
    {        
        $result = $this->user_level_m->get_datatable();

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
            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" id="select" name="select" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></a>';

            $output['data'][] = array(
                $row['id'],
                $row['nama'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/kategori_sub_kategori/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data = $this->kategori_m->get($id);

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("Edit Kategori", $this->session->userdata("language")), 
            'header'         => translate("Edit Kategori", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_sub_kategori/edit',
            'form_data'      => object_to_array($form_data),
            'pk'             => $id,                    //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit_sub($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/kategori_sub_kategori/edit_sub';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data      = $this->item_sub_kategori_m->get($id);
        $form_pembelian = $this->item_sub_kategori_pembelian_m->get_data($id)->result();
        // die_dump($form_pembelian);

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("Edit Sub Kategori", $this->session->userdata("language")), 
            'header'         => translate("Edit Sub Kategori", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_sub_kategori/edit_sub',
            'form_data'      => object_to_array($form_data),
            'form_pembelian' => object_to_array($form_pembelian),
            'pk'             => $id,                    //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_sub($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/kategori_sub_kategori/edit_sub';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data      = $this->item_sub_kategori_m->get($id);
        $form_pembelian = $this->item_sub_kategori_pembelian_m->get_data($id)->result();
        // die_dump($form_pembelian);

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("View Sub Kategori", $this->session->userdata("language")), 
            'header'         => translate("View Sub Kategori", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kategori_sub_kategori/view_sub',
            'form_data'      => object_to_array($form_data),
            'form_pembelian' => object_to_array($form_pembelian),
            'pk'             => $id,                    //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function save()
    {
        $command = $this->input->post('command');

        if ($command === 'add_kategori')
        {  
            $data = $this->kategori_m->array_from_post($this->kategori_m->fillable_add());
            $data['is_active'] = 1;
            $kategori_id = $this->kategori_m->save($data);

            if ($kategori_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data kategori berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }

        elseif ($command === 'edit_kategori')
        {
            $id = $this->input->post("id");

            $data = $this->kategori_m->array_from_post( $this->kategori_m->fillable_edit());
            $kategori_id = $this->kategori_m->save($data, $id);
            
            if ($kategori_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data kategori berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        if ($command === 'add_sub_kategori')
        {  
            // die_dump($this->input->post());
            
            // BEGIN ITEM SUB KATEGORI
            $data = $this->item_sub_kategori_m->array_from_post($this->item_sub_kategori_m->fillable_add());
            $data['is_active'] = 1;

            // SAVE ITEM SUB KATEGORI (ATAS)
            $sub_kategori_id = $this->item_sub_kategori_m->save($data);
            // END ITEM SUB KATEGORI


            // BEGIN TAB SPESIFIKASI
            $spesifikasi = $this->input->post('spesifikasi');
            // die_dump($spesifikasi);
            foreach ($spesifikasi as $row_s) 
            {
                if ($row_s['judul'] != '') 
                {
                    $data_spesifikasi['judul']             = $row_s['judul'];
                    $data_spesifikasi['tipe']              = $row_s['spesifikasi_type'];
                    $data_spesifikasi['maksimal_karakter'] = $row_s['max_text'];
                    $data_spesifikasi['is_active']         = 1;

                    // SAVE SPESIFIKASI
                    $spesifikasi_id = $this->spesifikasi_m->save($data_spesifikasi);

                    $item_spesifikasi['item_sub_kategori_id'] = $sub_kategori_id;
                    $item_spesifikasi['spesifikasi_id']       = $spesifikasi_id;
                    $item_spesifikasi['is_active']            = 1;

                    // SAVE SUB KATEGORI SPESIFIKASI
                    $item_sub_kategori_spesifikasi_id = $this->item_sub_kategori_spesifikasi_m->save($item_spesifikasi);
                }

                if ($row_s['spesifikasi_type'] == 4 || $row_s['spesifikasi_type'] == 5 || $row_s['spesifikasi_type'] == 6 || $row_s['spesifikasi_type'] == 7) 
                {
                    $detail = $this->input->post($row_s['count'].'detail');
                    foreach ($detail as $row_d) 
                    {
                        $data_detail = array(
                            'spesifikasi_id' => $spesifikasi_id,
                            'text'           => $row_d['text'],
                            'value'          => $row_d['value'],
                        );

                        $spesifikasi_detail_id = $this->spesifikasi_detail_m->save($data_detail);
                    }
                }
            }
            // END TAB SPESIFIKASI


            // BEGIN TAB PEMBELIAN PERSETUJUAN
            $pembelian = $this->input->post('user_level');
            foreach ($pembelian as $row_p) 
            {
                if ($row_p['user_level_id'] != '') 
                {
                    $data_pembelian = array(
                        'item_sub_kategori_id' => $sub_kategori_id,
                        'user_level_id'        => $row_p['user_level_id'],
                        'level_order'          => $row_p['order']
                    );

                    (isset($row_p['lewati'])) ? $data_pembelian['lewati'] = 1 : $data_pembelian['lewati'] = 0;
                    (isset($row_p['req'])) ? $data_pembelian['req'] = 1 : $data_pembelian['req'] = 0;
                    
                    // SAVE TAB PEMBELIAN PERSETUJUAN
                    $pembelian_id = $this->item_sub_kategori_pembelian_m->save($data_pembelian);
                }
            }
            // END TAB PEMBELIAN PERSETUJUAN


            if ($sub_kategori_id && $item_sub_kategori_spesifikasi_id && $pembelian_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data sub kategori berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
                $this->session->set_flashdata($flashdata);
            }
        }

        elseif ($command === 'edit_sub_kategori')
        {
            $id = $this->input->post("id");

            // die_dump($this->input->post());

            $data = $this->item_sub_kategori_m->array_from_post($this->item_sub_kategori_m->fillable_edit());

            $sub_kategori_id = $this->item_sub_kategori_m->save($data, $id);
    
            // BEGIN TAB SPESIFIKASI
            $spesifikasi = $this->input->post('spesifikasi');
            foreach ($spesifikasi as $row_s) 
            {
                // BEGIN DATA BARU (ADD)
                if ($row_s['item_spesifikasi_id'] == '') 
                {
                    if ($row_s['judul'] != '') 
                    {
                        $data_spesifikasi = array(
                            'judul'             => $row_s['judul'],
                            'tipe'              => $row_s['spesifikasi_type'],
                            'maksimal_karakter' => $row_s['max_text'],
                            'is_active'         => 1,
                        );

                        // SAVE SPESIFIKASI
                        $spesifikasi_id = $this->spesifikasi_m->save($data_spesifikasi);

                        $item_spesifikasi['item_sub_kategori_id'] = $sub_kategori_id;
                        $item_spesifikasi['spesifikasi_id']       = $spesifikasi_id;
                        $item_spesifikasi['is_active']            = 1;

                        // SAVE SUB KATEGORI SPESIFIKASI
                        $item_sub_kategori_spesifikasi_id = $this->item_sub_kategori_spesifikasi_m->save($item_spesifikasi);
                    }
                    
                    if ($row_s['spesifikasi_type'] == 4 || $row_s['spesifikasi_type'] == 5 || $row_s['spesifikasi_type'] == 6 || $row_s['spesifikasi_type'] == 7) 
                    {
                        $detail = $this->input->post($row_s['count'].'detail');
                        foreach ($detail as $row_d) 
                        {
                            $data_detail = array(
                                'spesifikasi_id' => $spesifikasi_id,
                                'text'           => $row_d['text'],
                                'value'          => $row_d['value'],
                            );

                            $spesifikasi_detail_id = $this->spesifikasi_detail_m->save($data_detail);
                        }
                    }
                }
                // END DATA BARU (ADD)
                
                
                // BEGIN UPDATE
                if ($row_s['item_spesifikasi_id'] != '') 
                {   
                    // UPDATE
                    if ($row_s['is_delete'] == '') 
                    {
                        $data_spesifikasi = array(
                            'judul'             => $row_s['judul'],
                            'tipe'              => $row_s['spesifikasi_type'],
                            'maksimal_karakter' => $row_s['max_text'],
                        );

                        // SAVE SPESIFIKASI
                        $spesifikasi_id = $this->spesifikasi_m->save($data_spesifikasi, $row_s['spesifikasi_id']);

                        // $item_spesifikasi['item_sub_kategori_id'] = $sub_kategori_id;
                        // $item_spesifikasi['spesifikasi_id']       = $spesifikasi_id;

                        // // SAVE SUB KATEGORI SPESIFIKASI
                        // $item_sub_kategori_spesifikasi_id = $this->item_sub_kategori_spesifikasi_m->save($item_spesifikasi, $row_s['item_spesifikasi_id']);
                         
                        if ($row_s['spesifikasi_type'] == 4 || $row_s['spesifikasi_type'] == 5 || $row_s['spesifikasi_type'] == 6 || $row_s['spesifikasi_type'] == 7) 
                        {
                            $detail = $this->input->post($row_s['count'].'detail');
                            foreach ($detail as $row_d) 
                            {
                                // INSERT DETAIL
                                if ($row_d['id_detail'] == '') 
                                {
                                    $data_detail = array(
                                        'spesifikasi_id' => $spesifikasi_id,
                                        'text'           => $row_d['text'],
                                        'value'          => $row_d['value'],
                                    );

                                    $spesifikasi_detail_id = $this->spesifikasi_detail_m->save($data_detail);
                                }
                                
                                // UPDATE DETAIL
                                if ($row_d['id_detail'] != '' && $row_d['delete_detail'] == '') 
                                {
                                    $data_detail = array(
                                        'text'           => $row_d['text'],
                                        'value'          => $row_d['value'],
                                    );

                                    $spesifikasi_detail_id = $this->spesifikasi_detail_m->save($data_detail, $row_d['id_detail']);
                                } else
                                {
                                    // DELETE DETAIL
                                    $spesifikasi_detail_id = $this->spesifikasi_detail_m->delete($row_d['id_detail']);
                                }
                                
                            }
                        }

                    } else 
                    {
                        $spesifikasi_id = $this->spesifikasi_m->delete($row_s['spesifikasi_id']);
                        $item_sub_kategori_spesifikasi_id = $this->item_sub_kategori_spesifikasi_m->delete($row_s['item_spesifikasi_id']);

                        if ($row_s['spesifikasi_type'] == 4 || $row_s['spesifikasi_type'] == 5 || $row_s['spesifikasi_type'] == 6 || $row_s['spesifikasi_type'] == 7) 
                        {
                            $detail = $this->input->post($row_s['count'].'detail');
                            foreach ($detail as $row_d) 
                            {
                                // DELETE DETAIL
                                $spesifikasi_detail_id = $this->spesifikasi_detail_m->delete($row_d['id_detail']);
                                
                            }
                        }

                    }


                }

            }
            // END TAB SPESIFIKASI


            // TAB PEMBELIAN
            $pembelian = $this->input->post('user_level');
            foreach ($pembelian as $row) 
            {
                // INSERT BARU
                if ($row['id'] == '' && $row['user_level_id'] != '') 
                {
                    $data_pembelian = array(
                        'item_sub_kategori_id' => $sub_kategori_id,
                        'user_level_id'        => $row['user_level_id'],
                        'level_order'          => $row['order']
                    );

                    (isset($row['lewati'])) ? $data_pembelian['lewati'] = 1 : $data_pembelian['lewati'] = 0;
                    (isset($row['req'])) ? $data_pembelian['req'] = 1 : $data_pembelian['req'] = 0;
                    
                    $pembelian_id = $this->item_sub_kategori_pembelian_m->save($data_pembelian);
                } 

                if ($row['id'] != '') 
                {
                    // UPDATE
                    if ($row['is_delete'] == '') {
                        $data_pembelian = array(
                            'level_order'          => $row['order']
                        );

                        (isset($row['lewati'])) ? $data_pembelian['lewati'] = 1 : $data_pembelian['lewati'] = 0;
                        (isset($row['req'])) ? $data_pembelian['req'] = 1 : $data_pembelian['req'] = 0;
                        
                        // die_dump($data_pembelian);
                        $pembelian_id = $this->item_sub_kategori_pembelian_m->save($data_pembelian, $row['id']);
                    } else 
                    {
                        // DELETE ITEM
                        $pembelian_id = $this->item_sub_kategori_pembelian_m->delete($row['id']);
                    }
                }
            }
            // END TAB PEMBELIAN

            
            if ($sub_kategori_id && $item_sub_kategori_spesifikasi_id && $pembelian_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data sub kategori berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/kategori_sub_kategori");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->kategori_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id'   => $trash_id,
            'tipe'              => 7,
            'data_id'           => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Kategori Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/kategori_sub_kategori");
    }

    public function delete_sub($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->item_sub_kategori_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id'   => $trash_id,
            'tipe'              => 8,
            'data_id'           => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Sub Kategori Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/kategori_sub_kategori");
    }

    public function kategori_spesifikasi_detail(){

        $kategori_sub_id = $this->input->post('kategori_sub_id');
        $spesifikasi_id  = $this->input->post('spesifikasi_id');
        
        $result = $this->item_sub_kategori_m->get_data_spesifikasi2($kategori_sub_id, $spesifikasi_id)->result_array();
        // die_dump($result);
        
        echo json_encode($result);
    }

    public function spesifikasi_detail(){

        $kategori_sub_id = $this->input->post('kategori_sub_id');
        
        $result = $this->item_sub_kategori_m->get_data_spesifikasi($kategori_sub_id)->result_array();
        // die_dump($result);
        
        echo json_encode($result);
    }

    public function deleteajax()
    {
        
        $id     = $this->input->post('id');
        $type   = $this->input->post('type');
        $msg    = '';

        if($type==1)
        {
            $data = array(
                'is_active'    => 0
            );
            $msg = "Poliklinik sudah dihapus";

        } else
        {
            $data = array(
                'is_active'    => 1
            );
            $msg = "Poliklinik sudah dikembalikan";
        }
       
        // save data
        $user_id = $this->poliklinik_m->save($data, $id);

        $trash_id='';
        $max_id = $this->kotak_sampah_m->max2();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // Poliklinik
        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'  => 2,
            'data_id'    => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "error",
                translate($msg, $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
       // redirect("master/cabang");
    }

    public function formatrupiah($val) {
        $hasil ='Rp. ' . number_format($val, 0 , '' , '.' ) . ',-';
        return $hasil;
    }

    public function check_modified_kategori()
    {
        if($this->input->is_ajax_request())
        {
            $response = new StdClass;
            $response->success = false;
            $response->msg = translate('Data yang akan anda ubah telah diubah oleh user lain. Apakah anda ingin melihat perubahannya?', $this->session->userdata('language'));

            $id = $this->input->post('id');
            $modified_date = $this->input->post('modified_date');

            $kategori = $this->kategori_m->get($id);

            if($kategori->modified_date == $modified_date)
            {
                $response->success = true;
                $response->msg = '';
            }

            echo json_encode($response);

        }
    }
}

