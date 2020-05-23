<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Racik_obat extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'b8f9bf82795e25a7cc54f9943cf4eb9b';                  // untuk check bit_access

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

        $this->load->model('others/kotak_sampah_m');
        $this->load->model('apotik/racik_obat/racik_obat_m');
        $this->load->model('apotik/racik_obat/racik_obat_detail_m');
        $this->load->model('apotik/racik_obat/racik_obat_identitas_m');
        $this->load->model('apotik/racik_obat/racik_obat_identitas_detail_m');
        $this->load->model('apotik/racik_obat/resep_racik_obat_m');
        $this->load->model('apotik/racik_obat/resep_racik_obat_detail_m');
        $this->load->model('apotik/racik_obat/tindakan_resep_obat_manual_m');
        $this->load->model('apotik/racik_obat/item_m');
        $this->load->model('apotik/racik_obat/item_satuan_m');
        $this->load->model('apotik/racik_obat/item_identitas_m');
        $this->load->model('apotik/racik_obat/inventory_m');
        $this->load->model('apotik/racik_obat/inventory_history_m');
        $this->load->model('apotik/racik_obat/inventory_history_detail_m');
        $this->load->model('apotik/racik_obat/inventory_history_identitas_m');
        $this->load->model('apotik/racik_obat/inventory_history_identitas_detail_m');
        $this->load->model('apotik/racik_obat/inventory_identitas_m');
        $this->load->model('apotik/racik_obat/inventory_identitas_detail_m');
        $this->load->model('apotik/racik_obat/gudang_m');
        $this->load->model('master/identitas_m');
        
        //ini yg baru
        $this->load->model('apotik/resep_obat_racikan_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/racik_obat/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Racik Obat', $this->session->userdata('language')), 
            'header'         => translate('Racik Obat', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/racik_obat/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/apotik/racik_obat/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'        => config_item('site_name'). translate("Tambah Racik Obat", $this->session->userdata("language")), 
            'header'       => translate("Tambah Racik Obat", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'apotik/racik_obat/add',
            'flag'         => 'add',
            'pk_value'     => '',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function resep_history($id, $resep_racik_obat_id)
    {
        $id = intval($id);
        $resep_racik_obat_id = intval($resep_racik_obat_id);
        $id || redirect(base_Url());
        $resep_racik_obat_id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/resep_obat/view_resep_history';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->paket_m->set_columns($this->paket_m->fillable_edit());
       
        $form_data = $this->resep_racik_obat_m->get($resep_racik_obat_id);
        $form_data_tindakan = $this->tindakan_resep_obat_manual_m->get($id);
        // die_dump($this->db->last_query());

        $data = array(
            'title'              => config_item('site_name'). translate("View Resep History", $this->session->userdata("language")), 
            'header'             => translate("View Resep History", $this->session->userdata("language")), 
            'header_info'        => config_item('site_name'), 
            'breadcrumb'         => TRUE,
            'menus'              => $this->menus,
            'menu_tree'          => $this->menu_tree,
            'css_files'          => $assets['css'],
            'js_files'           => $assets['js'],
            'content_view'       => 'apotik/resep_obat/view_resep_history',
            'form_data'          => object_to_array($form_data),
            'form_data_tindakan' => object_to_array($form_data_tindakan),
            'pk_value'           => $id,                         //table primary key value
            'flag'               => 'view_resep_history',                         //table primary key value
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
            'title'          => 'RayCare | '.translate('Paket', $this->session->userdata('language')), 
            'header'         => translate('View Paket', $this->session->userdata('language')), 
            'header_info'    => 'RayCare', 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'paket_data'       => object_to_array($paket_data),
            'content_view'   => 'master/paket/view',
            'pk_value'      => $id
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function proses($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/racik_obat/proses';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->paket_m->set_columns($this->paket_m->fillable_edit());
       
        $form_data = $this->resep_obat_racikan_m->get_proses_obat_racikan($id)->result_array();
        // die_dump($form_data[0]);

        $data = array(
            'title'        => config_item('site_name'). translate("Proses Racik Obat", $this->session->userdata("language")), 
            'header'       => translate("Proses Racik Obat", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'apotik/racik_obat/proses',
            'form_data'    => $form_data[0],
            'pk_value'     => $id,                         //table primary key value
            'flag'         => 'proses',                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses_manual($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/racik_obat/proses_manual';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->paket_m->set_columns($this->paket_m->fillable_edit());
       
        $form_data = $this->tindakan_resep_obat_manual_m->get_data_resep_manual($id)->result_array();
        // die_dump($form_data[0]);

        $data = array(
            'title'        => config_item('site_name'). translate("Proses Racik Obat Manual", $this->session->userdata("language")), 
            'header'       => translate("Proses Racik Obat Manual", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'apotik/racik_obat/proses_manual',
            'form_data'    => $form_data[0],
            'pk_value'     => $id,                         //table primary key value
            'flag'         => 'proses_manual',                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses_view($id, $flag)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/apotik/racik_obat/proses_view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->paket_m->set_columns($this->paket_m->fillable_edit());
        $form_data = '';
        if ($flag == "racik_obat") {
            $form_data = $this->racik_obat_m->get_data_racik_obat($id)->result_array();
        }else{
            $form_data = $this->racik_obat_m->get_data_racik_obat_manual($id)->result_array();
        }
        // die_dump($form_data[0]);

        $data = array(
            'title'        => config_item('site_name'). translate("View Racik Obat", $this->session->userdata("language")), 
            'header'       => translate("View Racik Obat", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'        => $this->menus,
            'menu_tree'    => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'apotik/racik_obat/proses_view',
            'form_data'    => $form_data[0],
            'pk_value'     => $id,                         //table primary key value
            'flag'         => $flag,                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
   
    public function listing($status=1)
    {        
        $user_level = $this->session->userdata('nama_level');
        $result = $this->resep_obat_racikan_m->get_datatable($status);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $pembuat = '';
        // $komposisi = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            $url = array();
            if($row['photo_dokter'] != '')
            {
                $url = explode('/', $row['photo_dokter']); 
                $image_user = '<img class="user-pic" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">';
            }
            else
            {
                $image_user = '<img class="user-pic" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/">';
            }
            
            $komposisi = $this->resep_obat_racikan_m->total_komposisi($row['id'])->result_array();
            // die_dump($this->db->last_query());
            $keterangan = $row['keterangan'];
          
            $words = explode(' ', $keterangan);
          
            $impWords = implode(' ', array_splice($words, 0, 6));
            
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Keterangan',$this->session->userdata('language')).'" data-content="'.$keterangan.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            

            $action     = '<a class="btn btn-primary" title="'.translate("Proses", $this->session->userdata("language")).'" href="'.base_url().'apotik/racik_obat/proses/'.$row['id'].'" ><i class="fa fa-check"></i></a>';
            $info = '<a title="'.translate('Komposisi', $this->session->userdata('language')).'" name="komposisi[]" class="btn btn-primary komposisi-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';

            $output['data'][] = array(
                $row['id'],
                '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">'.$row['nama_dokter'],
                '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('site_img_pasien_temp_dir_copy').$row['photo_pasien'].'">'.$row['nama_pasien'],
                $row['nama_resep'],
                '<div class="text-center">'.$row['jumlah'].'&nbsp;'.$row['nama_satuan'].'</div>',
                '<div class="col-md-12" style="padding:0px;">
                    <div class="input-group">
                        <input type="text" class="form-control" value="'.$komposisi[0]['jumlah_item'].' items" id="komposisi_'.$row['id'].'" readonly>
                        <span class="input-group-btn">'.$info.'</span>
                    </div>
                </div>',
                $preNotes,
                '<div class="text-center">'.$action.'</div>'


            );
         
        }

        echo json_encode($output);
    }

    public function listing_history()
    {        
        $user_level = $this->session->userdata('nama_level');
        $result = $this->racik_obat_m->get_datatable();
        // $result = $this->racik_obat_m->get_datatable_racik_obat();
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );


        $records = $result->records;

        $i=0;
        $pembuat = '';
        // $komposisi = '';
        foreach($records->result_array() as $row)
        {

            $i++;

            $get_nama_pembuat = $this->user_m->get($row['dibuat_oleh']);

            // die_dump($get_nama_pembuat->nama);
            if ($row['resep_obat_racikan_id'] != "") {

                $action = '';
                $image_dokter ='';
                $image_pasien ='';
                if ($row['tipe_resep'] == '1') {
                    $data_racik_obat = $this->racik_obat_m->get_racik_obat($row['id'])->result_array(); 
                    // die_dump($data_racik_obat);
                    // die_dump($this->db->last_query());
                    $username = $data_racik_obat[0]['username'];
                    $nama_dokter = $data_racik_obat[0]['nama_dokter'];
                    
                    if($data_racik_obat[0]['photo_dokter'] != '')
                    {
                        $url = explode('/', $data_racik_obat[0]['photo_dokter']); 
                        $image_dokter = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').$data_racik_obat[0]['username'].'/small/'.$url[1].'">'.$data_racik_obat[0]['nama_dokter'];
                    }

                    if($data_racik_obat[0]['photo_pasien'] != '')
                    {
                        // $url = explode('/', $data_racik_obat[0]['photo_dokter']); 
                        $image_pasien = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('site_img_pasien_temp_dir_copy').$data_racik_obat[0]['photo_pasien'].'">'.$data_racik_obat[0]['nama_pasien'];
                    }
                    
                    $action     = '<a class="btn grey-cascade" title="'.translate("View", $this->session->userdata("language")).'" href="'.base_url().'apotik/racik_obat/proses_view/'.$row['id'].'/racik_obat" ><i class="fa fa-search"></i></a>';
                }elseif ($row['tipe_resep'] == '2') {
                    $data_racik_obat = $this->racik_obat_m->get_racik_obat_manual($row['id'])->result_array();

                    $username = $data_racik_obat[0]['username'];
                    $nama_dokter = $data_racik_obat[0]['nama_dokter'];
                    
                    if($data_racik_obat[0]['photo_dokter'] != '')
                    {
                        $url = explode('/', $data_racik_obat[0]['photo_dokter']); 
                        $image_dokter = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').$data_racik_obat[0]['username'].'/small/'.$url[1].'">'.$data_racik_obat[0]['nama_dokter'];
                    }

                    if($data_racik_obat[0]['photo_pasien'] != '')
                    {
                        // $url = explode('/', $data_racik_obat[0]['photo_dokter']); 
                        $image_pasien = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('site_img_pasien_temp_dir_copy').$data_racik_obat[0]['photo_pasien'].'">'.$data_racik_obat[0]['nama_pasien'];
                    }
                    
                    $action     = '<a class="btn grey-cascade" title="'.translate("View", $this->session->userdata("language")).'" href="'.base_url().'apotik/racik_obat/proses_view/'.$row['id'].'/racik_obat_manual" ><i class="fa fa-search"></i></a>'; 
                }

                
                
                $info = '<a title="'.translate('Komposisi', $this->session->userdata('language')).'" name="komposisi_history[]" class="btn btn-primary komposisi-history-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';

                $output['data'][] = array(
                    $row['id'],
                    $row['no_batch'],
                    $row['nama_resep'],
                    date('d F Y', strtotime($row['tanggal_kadaluarsa'])) ,
                    // $row['dibuat_oleh'],
                    $get_nama_pembuat->nama,
                    $image_dokter,
                    $image_pasien,
                    // '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('site_img_pasien_temp_dir_copy').$row['photo_pasien'].'">'.$row['nama_pasien'],
                    '<div class="text-center">'.$row['jumlah_produksi'].'&nbsp;'.$row['satuan_produksi'].'</div>',
                    '<div class="text-center">'.$action.'</div>'


                );
            }
         
        }

        echo json_encode($output);
    }

    // public function listing_history($status=2)
    // {        
    //     $user_level = $this->session->userdata('nama_level');
    //     $result = $this->resep_obat_racikan_m->get_datatable($status);
    //     // die_dump($this->db->last_query());
    //     $output = array(
    //         'draw'                 => intval($this->input->post('draw', true)),
    //         'iTotalRecords'        => $result->total_records,
    //         'iTotalDisplayRecords' => $result->total_display_records,
    //         'data'                 => array()
    //     );
    
    //     $records = $result->records;

    //     $i=0;
    //     $pembuat = '';
    //     // $komposisi = '';
    //     foreach($records->result_array() as $row)
    //     {
    //         $i++;
    //         $url = array();
    //         if($row['photo_dokter'] != '')
    //         {
    //             $url = explode('/', $row['photo_dokter']); 
    //             $image_user = '<img class="user-pic" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">';
    //         }
    //         else
    //         {
    //             $image_user = '<img class="user-pic" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/">';
    //         }
            
    //         $komposisi = $this->resep_obat_racikan_m->total_komposisi($row['id'])->result_array();
    //         // die_dump($this->db->last_query());
    //         $keterangan = $row['keterangan'];
          
    //         $words = explode(' ', $keterangan);
          
    //         $impWords = implode(' ', array_splice($words, 0, 6));
            
    //         $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Keterangan',$this->session->userdata('language')).'" data-content="'.$keterangan.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            

    //         $action     = '<a class="btn btn-primary" title="'.translate("Proses", $this->session->userdata("language")).'" href="'.base_url().'apotik/racik_obat/proses/'.$row['id'].'" ><i class="fa fa-search"></i></a>';
    //         $info = '<a title="'.translate('Komposisi', $this->session->userdata('language')).'" name="komposisi_history[]" class="btn btn-primary komposisi-history-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';

    //         $output['data'][] = array(
    //             $row['id'],
    //             '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">'.$row['nama_dokter'],
    //             '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('site_img_pasien_temp_dir_copy').$row['photo_pasien'].'">'.$row['nama_pasien'],
    //             $row['nama_resep'],
    //             '<div class="text-center">'.$row['jumlah'].'&nbsp;'.$row['nama_satuan'].'</div>',
    //             '<div class="row" style="margin-left : 0px !important; margin-right : 0px !important">
    //                 <div class="col-xs-8" style="text-align:left; padding-left : 0px !important; padding-right : 0px !important; ">
    //                     <input type="text" value="'.$komposisi[0]['jumlah_item'].' items" id="komposisi_'.$row['id'].'" readonly style="background-color: transparent;border: 0px solid;">
    //                 </div>
    //                 <div class="col-xs-4" style="text-align:right; padding-left : 0px !important; padding-right : 0px !important;">
    //                     <span class="input-group-button">'.$info.'</span>
    //                 </div>
    //             </div>',
    //             $preNotes,
    //             '<div class="text-center">'.$action.'</div>'


    //         );
         
    //     }

    //     echo json_encode($output);
    // }

    public function listing_resep_manual($status=1)
    {        
        $user_level = $this->session->userdata('nama_level');
        $result = $this->tindakan_resep_obat_manual_m->get_datatable_resep_manual($status);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $pembuat = '';
        // $komposisi = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            $url = array();
            if($row['photo_dokter'] != '')
            {
                $url = explode('/', $row['photo_dokter']); 
                $image_user = '<img class="user-pic" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">';
            }
            else
            {
                $image_user = '<img class="user-pic" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/">';
            }
            
            $komposisi = $this->resep_obat_racikan_m->total_komposisi($row['id'])->result_array();
            // die_dump($this->db->last_query());
            $keterangan = $row['keterangan'];
          
            $words = explode(' ', $keterangan);
          
            $impWords = implode(' ', array_splice($words, 0, 6));
            
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Keterangan',$this->session->userdata('language')).'" data-content="'.$keterangan.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            

            $action     = '<a class="btn btn-primary" title="'.translate("Proses", $this->session->userdata("language")).'" href="'.base_url().'apotik/racik_obat/proses_manual/'.$row['id'].'" ><i class="fa fa-check"></i></a>';
            $info = '<a title="'.translate('Komposisi', $this->session->userdata('language')).'" name="komposisi[]" class="btn btn-primary komposisi-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-info"></i></a>';

            $output['data'][] = array(
                $row['id'],
                '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">'.$row['nama_dokter'],
                '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('site_img_pasien_temp_dir_copy').$row['photo_pasien'].'">'.$row['nama_pasien'],
                $preNotes,
                '<div class="text-center">'.$action.'</div>'


            );
         
        }

        echo json_encode($output);
    }

    public function listing_resep_manual_history($status=2)
    {        
        $user_level = $this->session->userdata('nama_level');
        $result = $this->tindakan_resep_obat_manual_m->get_datatable_resep_manual($status);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $pembuat = '';
        // $komposisi = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            $url = array();
            if($row['photo_dokter'] != '')
            {
                $url = explode('/', $row['photo_dokter']); 
                $image_user = '<img class="user-pic" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">';
            }
            else
            {
                $image_user = '<img class="user-pic" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/">';
            }
            
            $komposisi = $this->resep_obat_racikan_m->total_komposisi($row['id'])->result_array();
            // die_dump($this->db->last_query());
            $keterangan = $row['keterangan'];
          
            $words = explode(' ', $keterangan);
          
            $impWords = implode(' ', array_splice($words, 0, 6));
            
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Keterangan',$this->session->userdata('language')).'" data-content="'.$keterangan.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            

            $action     = '<a class="btn btn-primary" title="'.translate("Proses", $this->session->userdata("language")).'" href="'.base_url().'apotik/racik_obat/proses/'.$row['id'].'" ><i class="fa fa-check"></i></a>';
            $info = '<a title="'.translate('Komposisi', $this->session->userdata('language')).'" name="komposisi[]" class="btn btn-primary komposisi-item" data-id="'.$row['id'].'" style="margin:0px;"><i class="fa fa-search"></i></a>';

            $output['data'][] = array(
                $row['id'],
                '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_user_img_dir').$row['username'].'/small/'.$url[1].'">'.$row['nama_dokter'],
                '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('site_img_pasien_temp_dir_copy').$row['photo_pasien'].'">'.$row['nama_pasien'],
                $preNotes,
                '<div class="text-center">'.$action.'</div>'


            );
         
        }

        echo json_encode($output);
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

    public function listing_komposisi_item_history($id=null)
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

    public function listing_komposisi_manual_history($id=null)
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

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-center" style="width : 75px;">'.$i.'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_resep()
    {
        // $id = '1';
        $result = $this->resep_racik_obat_m->get_datatable();
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {
            $user = $this->user_m->get($row['user_id']);
            $user_array = object_to_array($user);
            $pembuat = $user_array['nama'];

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-resep="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';

            $output['aaData'][] = array(
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$pembuat.'</div>',
                '<div class="text-center">'.$action.'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_search_item()
    {
        // $id = '1';
        $result = $this->inventory_m->get_datatable();
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

            $cek_harga_terbaru = $this->inventory_m->get_harga_terbaru($row['satuan_id'])->result_array();

            // die_dump($cek_harga_terbaru[0]['harga_terbaru']);

            if ($row['harga'] == $cek_harga_terbaru[0]['harga_terbaru']) {
                $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1),true);
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                
                $output['aaData'][] = array(
                    '<div class="text-center">'.$row['id'].'</div>',
                    '<div class="text-center">'.$row['item_kode'].'</div>',
                    $row['item_nama'],
                    $row['jumlah'].' '.$row['satuan'],
                    '<div class="text-center">'.$action.'</div>' 
                );
            }
            

            
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_item_digunakan($resep_racik_obat_id, $jumlah_produksi)
    {
        // $id = '1';
        $result = $this->resep_racik_obat_m->get_datatable_item_digunakan($resep_racik_obat_id);
        // die_dump($result->records->result_array());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $count = count($records->result_array());
        $i=0;
        
        $harga ='';
        $input = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            
            $harga = $harga + $row['satuan_harga'];
            // die_dump($count);
            if ($i == $count) {
                $input = '<input type="hidden" id="sub_total" name="sub_total" value="'.$harga.'""></div>';
            }
            

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-resep="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
            $identitas = '<a title="'.translate('Identitas', $this->session->userdata('language')).'" name="identitas[]" href="#ajax_notes" data-toggle="modal" class="btn btn-primary info-item" data-item="'.htmlentities(json_encode($row)).'" style="margin:0px;"><i class="fa fa-info"></i></a>';


            $output['aaData'][] = array(
                '<div class="text-center">'.$row['item_kode'].'<input type="hidden" class="form-control" name="items['.$i.'][item_id]" value = "'.$row['item_id'].'"></div>',
                '<div class="text-center">'.$row['item_nama'].'</div>',
                '<div class="row" style="margin-left : 0px !important; margin-right : 0px !important">
                    <div class="col-xs-10" style="padding-left : 0px !important; padding-right : 0px !important; ">
                        <input type="hidden" class="text-center" value="'.$row['jumlah'].'" name="items['.$i.'][item_jumlah_database]" readonly style="background-color: transparent;border: 0px solid;">
                        <input type="text" class="text-center" value="'.$row['jumlah']*$jumlah_produksi.'" name="items['.$i.'][item_jumlah_produksi]" readonly style="background-color: transparent;border: 0px solid;">
                    </div>
                    <div class="col-xs-2" style="text-align:right; padding-left : 0px !important; padding-right : 0px !important;">
                        <span class="input-group-button">'.$identitas.'</span>
                    </div>
                </div>',
                '<div class="text-center">'.$row['satuan_nama'].'<input type="hidden" class="form-control" name="items['.$i.'][item_satuan_id]" value = "'.$row['item_satuan_id'].'"></div>',
                '<div class="text-right">Rp. ' . number_format($row['satuan_harga'],0,'','.').',- '.$input.'',
            );
         
        }

        echo json_encode($output);
    }

    public function save()
    {   
        $user_id = $this->session->userdata('user_id');
        $array_input = $this->input->post();
        
        // die_dump($array_input);
        if ($array_input['command'] == "proses") {
            
            // die_dump($this->db->last_query());

            $last_number    = $this->racik_obat_m->get_no_batch()->result_array();
            $last_number    = intval($last_number[0]['max_no_batch'])+1;

            $format         = 'BCH-'.date('y').date('m').'%03d';
            $no_batch       = sprintf($format, $last_number, 3);

            $tanggal_kadaluarsa = date("Y-m-d", strtotime($array_input['tanggal_kadaluarsa']));
            // die_dump($no_batch);
            // die_dump($tanggal_kadaluarsa);
            $data_racik_obat = array(
                'no_batch'              => $no_batch,
                'tanggal_kadaluarsa'    => $tanggal_kadaluarsa,
                'pasien_id'             => $array_input['pasien_id'],
                'tipe_resep'            => '1',
                'nama'                  => $array_input['nama_resep'],
                'resep_obat_racikan_id' => $array_input['resep_obat_racikan_id'],
                'jumlah_produksi'       => $array_input['jumlah_produksi'],
                'satuan_produksi'       => $array_input['satuan_produksi'],
                'harga_produksi'        => $array_input['sub_total'],
                'harga_jual'            => $array_input['harga_jual'],
                'biaya_tambahan'        => $array_input['biaya_tambahan'],
                'is_active'             => '1',
            );

            // die_dump($data_racik_obat);
            $id_racik_obat = $this->racik_obat_m->save($data_racik_obat);

            $data_inventory_history = array(
                'transaksi_id' => $id_racik_obat,
                'transaksi_tipe' => '2'
            );

            $id_inventory_history = $this->inventory_history_m->save($data_inventory_history);
        
            // $jumlah_item = 0;
            foreach ($array_input['items'] as $item) 
            {
                if($item['item_id'] != "")
                {
                    // die_dump($array_input['identitas_'.$item['item_id']]);
                    $harga_sebelumnya = 0;
                    if (isset($array_input['identitas_'.$item['item_id']])) {
                        $i = 1;
                        foreach ($array_input['identitas_'.$item['item_id']] as $identitas) {
                            if (isset($identitas)) {
                                if ($identitas['jumlah'] != 0) {


                                    $cek_racik_obat_detail = $this->racik_obat_detail_m->get_by(array(
                                        'racik_obat_id' => $id_racik_obat,
                                        'item_id' => $item['item_id'],
                                        'item_satuan_id' => $item['item_satuan_id'],
                                        'harga_beli' => $identitas['harga_beli']
                                    ));

                                    $cek_racik_obat_detail_array = object_to_array($cek_racik_obat_detail);

                                    // die_dump($cek_racik_obat_detail_array);
                                    if (empty($cek_racik_obat_detail_array)) {
                                        $data_item = array(
                                            'racik_obat_id' => $id_racik_obat,
                                            'item_id' => $item['item_id'],
                                            'item_satuan_id' => $item['item_satuan_id'],
                                            // 'jumlah' => $item['item_jumlah'],
                                            'jumlah' => $identitas['jumlah'],
                                            'harga_jual' => $item['item_harga'],
                                            'harga_beli' => $identitas['harga_beli'],
                                        );
                                        
                                        // die_dump($array_input);
                                        $id_racik_obat_detail = $this->racik_obat_detail_m->save($data_item);
                                    }else{
                                        $jumlah_sebelumnya = intval($cek_racik_obat_detail_array[0]['jumlah']);
                                        $jumlah_sekarang = $jumlah_sebelumnya + intval($identitas['jumlah']);

                                        $racik_obat_detail_id = $cek_racik_obat_detail_array[0]['id'];

                                        $data_update_jumlah = array('jumlah' => $jumlah_sekarang);
                                        $id_racik_obat_detail = $this->racik_obat_detail_m->save($data_update_jumlah, $racik_obat_detail_id);
                                        // die_dump($id_racik_obat_detail);
                                    }
                                     
                                    $data_identitas = array(
                                        'racik_obat_detail_id' => $id_racik_obat_detail, 
                                        'jumlah' => $identitas['jumlah'],
                                    );

                                    $id_racik_obat_identitas = $this->racik_obat_identitas_m->save($data_identitas);


                                    $data_inventory_history_detail = array(
                                        'inventory_history_id' => $id_inventory_history,
                                        'gudang_id' => $identitas['gudang_id'],
                                        'pmb_id' => $identitas['pmb_id'],
                                        'item_id' => $item['item_id'],
                                        'item_satuan_id' => $item['item_satuan_id'],
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
                                    
                                    $get_jumlah_inventory = $this->inventory_m->get_by(array('inventory_id' => $identitas['inventory_id']));
                                    $array_jumlah_inventory = object_to_array($get_jumlah_inventory);

                                    $jumlah_inventory = intval($array_jumlah_inventory[0]['jumlah']-intval($identitas['jumlah']));
                                    $modified_by      = $this->session->userdata('user_id');
                                    $modified_date    = date('Y-m-d H:i:s');

                                    $save_inventory = $this->inventory_m->update_jumlah_inventory($jumlah_inventory, $modified_by, $modified_date, $identitas['inventory_id']);

                                    $get_jumlah_identitas = $this->inventory_identitas_m->get_by(array('inventory_identitas_id' => $identitas['inventory_identitas_id']));
                                    $array_jumlah_identitas = object_to_array($get_jumlah_identitas);

                                    // // die_dump($array_jumlah_identitas[0]['jumlah']);
                                    $jumlah = intval($array_jumlah_identitas[0]['jumlah'])-intval($identitas['jumlah']);
                                    $modified_by      = $this->session->userdata('user_id');
                                    $modified_date    = date('Y-m-d H:i:s');

                                    $save_inventory_identitas = $this->inventory_identitas_m->update_stock_identitas($jumlah, $identitas['inventory_identitas_id'], $modified_by, $modified_date);
                                    

                                    $cek_stock_inventory_habis = $this->inventory_m->get_by(array('jumlah' => 0));
                                    $cek_stock_inventory_habis_array = object_to_array($cek_stock_inventory_habis);

                                    if(!empty($cek_stock_inventory_habis_array)){
                                        // die_dump($cek_stock_inventory_habis_array);
                                        foreach ($cek_stock_inventory_habis_array as $delete_stock_inventory) {
                                            $this->inventory_m->delete_inventory($delete_stock_inventory['inventory_id']);


                                        }

                                        // die_dump($this->db->last_query());
                                    }

                                    $cek_stock_inventory_identitas_habis = $this->inventory_identitas_m->get_by(array('jumlah' => 0));
                                    $cek_stock_inventory_identitas_habis_array = object_to_array($cek_stock_inventory_identitas_habis);


                                    if(!empty($cek_stock_inventory_identitas_habis_array)){
                                        // die_dump($cek_stock_inventory_habis_array);
                                        
                                        foreach ($cek_stock_inventory_identitas_habis_array as $delete_stock_inventory_identitas) {
                                            $this->inventory_identitas_m->delete_inventory_identitas($delete_stock_inventory_identitas['inventory_identitas_id']);
                                            $this->inventory_identitas_detail_m->delete_inventory_identitas_detail($delete_stock_inventory_identitas['inventory_identitas_id']);
                                        }

                                        // die_dump($cek_stock_inventory_identitas_habis_array);
                                        // die_dump($this->db->last_query());
                                    }

                                    // die_dump($this->db->last_query());
                                    // die_dump($data_inventory_history_detail);

                                    // die_dump($array_input['identitas_detail_'.$item['item_id'].'_'.$i]);
                                    foreach ($array_input['identitas_detail_'.$item['item_id'].'_'.$i] as $identitas_detail) {
                                        
                                        // $indentitas_detail = $array_input['identitas_'.$item['item_id'].'_'.$master_identitas['id']][$indexIdentitas];

                                            $data_identitas_detail = array(
                                                'racik_obat_identitas_id' => $id_racik_obat_identitas, 
                                                'identitas_id' => $identitas_detail['id'],
                                                'judul' => $identitas_detail['judul'],
                                                // 'tipe' => $racik_obat_identitas_detail['tipe'],
                                                // '`group`' => $racik_obat_identitas_detail['group'],
                                                'value' => $identitas_detail['value'],
                                                // 'jumlah' => $identitas['jumlah'],
                                            );
                                            // die_dump($indentitas_detail['value']);
                                            $id_racik_obat_identitas_detail = $this->racik_obat_identitas_detail_m->save($data_identitas_detail);
                                            
                                            $data_inventory_history_identitas_detail = array(
                                                'inventory_history_identitas_id' => $inventory_history_identitas_id,
                                                'identitas_id' => $identitas_detail['id'],
                                                'judul' => $identitas_detail['judul'],
                                                'value' => $identitas_detail['value'],
                                            );

                                            $inventory_history_identitas_detail = $this->inventory_history_identitas_detail_m->save($data_inventory_history_identitas_detail);
                                        }
                                        // $indexIdentitas++;
                                    }
                                
                                // die_dump($this->db->last_query());
                            }
                        $i++;
                        } 
                                    // die_dump($data_item);
                    }
                }                     
                    
            }

            // die_dump('refresh');

            $data_resep_obat_racikan = array('`status`' => '2');
            $update_resep_obat_racikan = $this->resep_obat_racikan_m->save($data_resep_obat_racikan, $array_input['resep_obat_racikan_id']);
            
        }

        if ($array_input['command'] == "proses_manual") {
            
            // die_dump($array_input);

            $last_number    = $this->racik_obat_m->get_no_batch()->result_array();
            $last_number    = intval($last_number[0]['max_no_batch'])+1;

            $format         = 'BCH-'.date('y').date('m').'%03d';
            $no_batch       = sprintf($format, $last_number, 3);

            $tanggal_kadaluarsa = date("Y-m-d", strtotime($array_input['tanggal_kadaluarsa']));
            // die_dump($no_batch);
            // die_dump($tanggal_kadaluarsa);
            $data_racik_obat = array(
                'no_batch'              => $no_batch,
                'tanggal_kadaluarsa'    => $tanggal_kadaluarsa,
                'nama'                  => $array_input['nama_resep'],
                'pasien_id'             => $array_input['pasien_id'],
                'tipe_resep'            => '2',
                'resep_obat_racikan_id' => $array_input['tindakan_resep_obat_manual_id'],
                'jumlah_produksi'       => $array_input['jumlah_produksi'],
                'satuan_produksi'       => $array_input['satuan_produksi'],
                'harga_produksi'        => $array_input['sub_total'],
                'harga_jual'            => $array_input['harga_jual'],
                'biaya_tambahan'        => $array_input['biaya_tambahan'],
                'is_active'             => '1',
            );

            // die_dump($data_racik_obat);
            $id_racik_obat = $this->racik_obat_m->save($data_racik_obat);

            $data_inventory_history = array(
                'transaksi_id' => $id_racik_obat,
                'transaksi_tipe' => '2'
            );

            $id_inventory_history = $this->inventory_history_m->save($data_inventory_history);
        
            // $jumlah_item = 0;
            foreach ($array_input['items'] as $item) 
            {
                if($item['item_id'] != "")
                {
                    // die_dump($array_input['identitas_'.$item['item_id']]);
                    $harga_sebelumnya = 0;
                    if (isset($array_input['identitas_'.$item['item_id']])) {
                        $i = 1;
                        foreach ($array_input['identitas_'.$item['item_id']] as $identitas) {
                            if (isset($identitas)) {
                                if ($identitas['jumlah'] != 0) {


                                    $cek_racik_obat_detail = $this->racik_obat_detail_m->get_by(array(
                                        'racik_obat_id' => $id_racik_obat,
                                        'item_id' => $item['item_id'],
                                        'item_satuan_id' => $item['item_satuan_id'],
                                        'harga_beli' => $identitas['harga_beli']
                                    ));

                                    $cek_racik_obat_detail_array = object_to_array($cek_racik_obat_detail);

                                    // die_dump($cek_racik_obat_detail_array);
                                    if (empty($cek_racik_obat_detail_array)) {
                                        $data_item = array(
                                            'racik_obat_id' => $id_racik_obat,
                                            'item_id' => $item['item_id'],
                                            'item_satuan_id' => $item['item_satuan_id'],
                                            // 'jumlah' => $item['item_jumlah'],
                                            'jumlah' => $identitas['jumlah'],
                                            'harga_jual' => $item['item_harga'],
                                            'harga_beli' => $identitas['harga_beli'],
                                        );
                                        
                                        $id_racik_obat_detail = $this->racik_obat_detail_m->save($data_item);
                                    }else{
                                        $jumlah_sebelumnya = intval($cek_racik_obat_detail_array[0]['jumlah']);
                                        $jumlah_sekarang = $jumlah_sebelumnya + intval($identitas['jumlah']);

                                        $racik_obat_detail_id = $cek_racik_obat_detail_array[0]['id'];

                                        $data_update_jumlah = array('jumlah' => $jumlah_sekarang);
                                        $id_racik_obat_detail = $this->racik_obat_detail_m->save($data_update_jumlah, $racik_obat_detail_id);
                                        // die_dump($id_racik_obat_detail);
                                    }
                                     
                                    $data_identitas = array(
                                        'racik_obat_detail_id' => $id_racik_obat_detail, 
                                        'jumlah' => $identitas['jumlah'],
                                    );

                                    $id_racik_obat_identitas = $this->racik_obat_identitas_m->save($data_identitas);


                                    $data_inventory_history_detail = array(
                                        'inventory_history_id' => $id_inventory_history,
                                        'gudang_id' => $identitas['gudang_id'],
                                        'pmb_id' => $identitas['pmb_id'],
                                        'item_id' => $item['item_id'],
                                        'item_satuan_id' => $item['item_satuan_id'],
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
                                    
                                    $get_jumlah_inventory = $this->inventory_m->get_by(array('inventory_id' => $identitas['inventory_id']));
                                    $array_jumlah_inventory = object_to_array($get_jumlah_inventory);

                                    $jumlah_inventory = intval($array_jumlah_inventory[0]['jumlah']-intval($identitas['jumlah']));
                                    $modified_by      = $this->session->userdata('user_id');
                                    $modified_date    = date('Y-m-d H:i:s');

                                    $save_inventory = $this->inventory_m->update_jumlah_inventory($jumlah_inventory, $modified_by, $modified_date, $identitas['inventory_id']);

                                    $get_jumlah_identitas = $this->inventory_identitas_m->get_by(array('inventory_identitas_id' => $identitas['inventory_identitas_id']));
                                    $array_jumlah_identitas = object_to_array($get_jumlah_identitas);

                                    // // die_dump($array_jumlah_identitas[0]['jumlah']);
                                    $jumlah = intval($array_jumlah_identitas[0]['jumlah'])-intval($identitas['jumlah']);
                                    $modified_by      = $this->session->userdata('user_id');
                                    $modified_date    = date('Y-m-d H:i:s');

                                    $save_inventory_identitas = $this->inventory_identitas_m->update_stock_identitas($jumlah, $identitas['inventory_identitas_id'], $modified_by, $modified_date);
                                    

                                    $cek_stock_inventory_habis = $this->inventory_m->get_by(array('jumlah' => 0));
                                    $cek_stock_inventory_habis_array = object_to_array($cek_stock_inventory_habis);

                                    if(!empty($cek_stock_inventory_habis_array)){
                                        // die_dump($cek_stock_inventory_habis_array);
                                        foreach ($cek_stock_inventory_habis_array as $delete_stock_inventory) {
                                            $this->inventory_m->delete_inventory($delete_stock_inventory['inventory_id']);


                                        }

                                        // die_dump($this->db->last_query());
                                    }

                                    $cek_stock_inventory_identitas_habis = $this->inventory_identitas_m->get_by(array('jumlah' => 0));
                                    $cek_stock_inventory_identitas_habis_array = object_to_array($cek_stock_inventory_identitas_habis);


                                    if(!empty($cek_stock_inventory_identitas_habis_array)){
                                        // die_dump($cek_stock_inventory_habis_array);
                                        
                                        foreach ($cek_stock_inventory_identitas_habis_array as $delete_stock_inventory_identitas) {
                                            $this->inventory_identitas_m->delete_inventory_identitas($delete_stock_inventory_identitas['inventory_identitas_id']);
                                            $this->inventory_identitas_detail_m->delete_inventory_identitas_detail($delete_stock_inventory_identitas['inventory_identitas_id']);
                                        }

                                        // die_dump($cek_stock_inventory_identitas_habis_array);
                                        // die_dump($this->db->last_query());
                                    }

                                    // die_dump($this->db->last_query());
                                    // die_dump($data_inventory_history_detail);

                                    // die_dump($array_input['identitas_detail_'.$item['item_id'].'_'.$i]);
                                    foreach ($array_input['identitas_detail_'.$item['item_id'].'_'.$i] as $identitas_detail) {
                                        
                                        // $indentitas_detail = $array_input['identitas_'.$item['item_id'].'_'.$master_identitas['id']][$indexIdentitas];

                                            $data_identitas_detail = array(
                                                'racik_obat_identitas_id' => $id_racik_obat_identitas, 
                                                'identitas_id' => $identitas_detail['id'],
                                                'judul' => $identitas_detail['judul'],
                                                // 'tipe' => $racik_obat_identitas_detail['tipe'],
                                                // '`group`' => $racik_obat_identitas_detail['group'],
                                                'value' => $identitas_detail['value'],
                                                // 'jumlah' => $identitas['jumlah'],
                                            );
                                            // die_dump($indentitas_detail['value']);
                                            $id_racik_obat_identitas_detail = $this->racik_obat_identitas_detail_m->save($data_identitas_detail);
                                            
                                            $data_inventory_history_identitas_detail = array(
                                                'inventory_history_identitas_id' => $inventory_history_identitas_id,
                                                'identitas_id' => $identitas_detail['id'],
                                                'judul' => $identitas_detail['judul'],
                                                'value' => $identitas_detail['value'],
                                            );

                                            $inventory_history_identitas_detail = $this->inventory_history_identitas_detail_m->save($data_inventory_history_identitas_detail);
                                        }
                                        // $indexIdentitas++;
                                    }
                                
                                // die_dump($this->db->last_query());
                            }
                        $i++;
                        } 
                                    // die_dump($data_item);
                    }
                }                     
                    
            }

            $data_tindakan_resep_obat_manual = array('`status`' => '2');
            $update_tindakan_resep_obat_manual = $this->tindakan_resep_obat_manual_m->save($data_tindakan_resep_obat_manual, $array_input['tindakan_resep_obat_manual_id']);
            
        }
        
        if ($id_racik_obat || $save_racik_obat_detail) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Racik Obat berhasil di Tambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        redirect("apotik/racik_obat");
    }


    public function modal_identitas($item_id, $item_satuan_id, $row_id)
    {
        $data = array(
        
            'item_id'        => $item_id,
            'item_satuan_id' => $item_satuan_id,
            'row_id'         => $row_id
        );

        $this->load->view('apotik/racik_obat/modal/modal_identitas.php', $data);
    
    }

    public function modal_identitas_view($racik_obat_id, $item_id, $item_satuan_id, $row_id)
    {
        $data = array(
        
            'racik_obat_id'  => $racik_obat_id,
            'item_id'        => $item_id,
            'item_satuan_id' => $item_satuan_id,
            'row_id'         => $row_id
        );

        $this->load->view('apotik/racik_obat/modal/modal_identitas_view.php', $data);
    
    }
    

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $resep_racik_obat_id = $this->resep_racik_obat_m->save($data, $id);

        $update_status = $this->tindakan_resep_obat_manual_m->update_status($id);

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

        if ($resep_racik_obat_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Resep Telah Di Delete", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("apotik/resep_obat");
    }

    public function get_user(){

       $user_id = $this->input->post('user_id');
       //die_dump($id_negara);
       //$this->region_m->set_columns(array('id','nama'));
       $user       = $this->user_m->get($user_id);
       //die_dump($this->db->last_query());        
       $data_user = object_to_array($user);

       // die_dump($data_user);

       //die_dump($this->db->last_query());
       echo json_encode($data_user);
   }

    public function get_identitas(){

        $item_id = $this->input->post('item_id');
                
        //die_dump($id_kategori);
        //$this->region_m->set_columns(array('id','nama'));
        $data_identitas       = $this->item_identitas_m->data_item_identitas($item_id)->result_array();
        // die_dump($this->db->last_query());        
        
        // die_dump($data_identitas);
        $hasil_data_identitas = object_to_array($data_identitas);

        // die_dump($hasil_data_identitas);
        //die_dump($hasil_tipe_akun);
        //die_dump($this->db->last_query());

        echo json_encode($hasil_data_identitas);
    }

    public function get_jumlah_identitas(){

        $inventory_id   = $this->input->post('inventory_id');
        
        $inventory_identitas = $this->inventory_identitas_m->get_by(array('inventory_id' => $inventory_id));

        $hasil_inventory_identitas = object_to_array($inventory_identitas);

        echo json_encode($hasil_inventory_identitas);
    }

    public function show_identitas()
    {
        $item_id              = $this->input->post('item_id');
        $data_identitas       = $this->item_identitas_m->data_item_identitas($item_id)->result_array();
        $hasil_data_identitas = object_to_array($data_identitas);
        
        $identitas_id         = $this->input->post('identitas_id');
        $identitas            = $this->identitas_m->get_by(array('id' => $identitas_id));
        $data_identitas       = object_to_array($identitas);


        $item_satuan_id       = $this->input->post('item_satuan_id');
        $item_satuan = $this->item_satuan_m->get_by(array('id' => $item_satuan_id));

        $data_item_satuan = object_to_array($item_satuan);

        // die_dump($data_item_satuan);

        $show_identitas = "";
        $input      = "";
        $radio      = "";
        $checkbox   = "";
        $i          = $this->input->post('i');
        $total_dokumen = 0;
        foreach ($hasil_data_identitas as $data) 
        {
            if ($data['tipe'] == 1)
            {
                $check = '';
                if ($data['identitas_id'] == $identitas_id) {
                    $check = '';
                }else{
                    $check = 'hidden';
                }

                // die_dump($hasil_data_identitas);

                $input = '<label class="control-label col-md-3 '.$check.'" id="identitas_'.$data['id'].'">'.$data['judul'].' :</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control sendData '.$check.'" id="identitas_'.$data['id'].'" name="'.strtolower(str_replace(" ", "_", $data['judul'])).'_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'">
                            <input type="hidden" class="form-control identitas_id" id="identitas_id_'.$data['id'].'_'.$i.'" name="identitas_id_'.$data['id'].'_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['id'].'">
                            <input type="hidden" class="form-control" id="'.$data['id'].'_'.$i.'" name="tipe_'.$data['id'].'_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['id'].'_'.$i.'" name="judul_'.$data['id'].'_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 2)
            {
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <textarea class="form-control" id="identitas_'.$i.'" name="identitas_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" rows="4"></textarea>
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 3) {
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <input type="number" min="1" class="form-control text-right" id="'.$data['judul'].'" name="identitas_'.$i.'" value="1">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }elseif ($data['tipe'] == 4) 
            {
                // $judul = $data['judul'];
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 4)->result_array();
                $spesifikasi_detail_option = array(
                    '' => translate('Pilih..', $this->session->userdata('language'))
                );

                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $spesifikasi_detail_option[$data_spesifikasi['value']] = $data_spesifikasi['text'];
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            '.form_dropdown('identitas_'.$i, $spesifikasi_detail_option, "", "id=\"identitas_$i\" class=\"form-control\"").'
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }
            elseif ($data['tipe'] == 5)
            {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 5)->result_array();
                
                foreach ($spesifikasi_detail as $data_spesifikasi)
                {

                    $radio .= '<label class="radio-inline" style="padding-left:20px;">
                                <input type="radio" name="identitas_'.$i.'" value="'.$data_spesifikasi['value'].'">'.$data_spesifikasi['text'].'
                              </label>';
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <div class="radio-list">
                                '.$radio.'
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                              </div>
                          </div>';
            }
            elseif ($data['tipe'] == 6)
            {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 6)->result_array();
                
                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $checkbox .= '<label class="checkbox-inline" style="padding-left:20px;">
                                    <input type="checkbox" name="identitas_'.$i.'[]" value="'.$data_spesifikasi['value'].'">'.$data_spesifikasi['text'].'
                                  </label>';
                }

                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            <div class="checkbox-list">
                                '.$checkbox.'
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                              </div>
                          </div>';
            }elseif ($data['tipe'] == 7) {
                $spesifikasi_detail = $this->item_sub_kategori_m->get_data_spesifikasi_detail($data['spesifikasi_id'], 7)->result_array();
                $spesifikasi_detail_option = array();

                foreach ($spesifikasi_detail as $data_spesifikasi)
                {
                    $spesifikasi_detail_option[$data_spesifikasi['value']] = $data_spesifikasi['text'];
                }

                $judul = $data['judul'];
                $input = '<label class="control-label col-md-2">'.$data['judul'].' :</label>
                          <div class="col-md-3">
                            '.form_dropdown("identitas_".$i."[]", $spesifikasi_detail_option, "", "id=\"$judul\" class=\"multi-select\" multiple=\"multiple\"").'
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
                            <input type="hidden" class="form-control" id="'.$data['judul'].'" name="judul_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['judul'].'">
                          </div>';
            }
            $show_identitas .= '<div class="form-group">'.$input.'</div>';
            $total_dokumen++;
        }
            $show_identitas .= '
                            <div class="form-group">
                                <label class="control-label col-md-3">Jumlah</label>
                                <div class="col-md-4 text">
                                    <input type="text" class="form-control jumlah sendData" name="jumlah_'.$i.'">
                                </div>
                                <div class="col-md-3">
                                    <span class="control-label" style="display: inline-block; margin-left: -15px !important;">'.$data_item_satuan[0]['nama'].'</span>
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label class="control-label col-md-3">Total identitas</label>
                                <div class="col-md-4 text">
                                    <input type="text" class="form-control" name="total_identitas" value="'.$total_dokumen.'">
                                </div>
                            </div>               
                            ';
        //die_dump($show_claim);
        echo $show_identitas;
    }


}

/* End of file resep_obat.php */
/* Location: ./application/controllers/racik_obat/racik_obat.php */