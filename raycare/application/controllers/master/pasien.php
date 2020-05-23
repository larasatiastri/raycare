<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '07a5414a398745f3c6bbf4065e4decb6';                  // untuk check bit_access

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

        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('master/pasien_hubungan_m');
        $this->load->model('master/pasien_hubungan_telepon_m');
        $this->load->model('master/pasien_hubungan_alamat_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/region_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/penyakit_m');
        $this->load->model('master/penjamin_m');
        $this->load->model('master/faskes_m');
        $this->load->model('master/faskes_temp_m');
        $this->load->model('master/master_faskes_m');
        $this->load->model('master/penjamin_dokumen_m');
        $this->load->model('master/dokumen_m');
        $this->load->model('master/dokumen_detail_m');
        $this->load->model('master/dokumen_detail_tipe_m');
        $this->load->model('master/penjamin_kelompok_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/pasien_dokumen_m');
        $this->load->model('master/pasien_dokumen_detail_m');
        $this->load->model('master/pasien_dokumen_detail_tipe_m');
        $this->load->model('master/transaksi_dokter3_m');

        $this->load->model('master/pasien_dok_history_m');
        $this->load->model('master/pasien_dok_history_detail_m');
        $this->load->model('master/pasien_dok_history_detail_tipe_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/faskes_marketing_m');
       
    }
    
    public function index()
    {

        $assets = array();
        $config = 'assets/master/pasien/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master Pasien', $this->session->userdata('language')), 
            'header'         => translate('Master Pasien', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/pasien/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Tambah Pasien", $this->session->userdata("language")), 
            'header'         => translate("Tambah Pasien", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function penjamin($id_pasien)
    {
        $assets = array();
        $assets_config = 'assets/master/pasien/index_penjamin';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'        => config_item('site_name').' | '. translate("Penjamin Pasien", $this->session->userdata("language")), 
            'header'       => translate("Penjamin Pasien", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'     => $this->menus,
            'menu_tree' => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'master/pasien/index_penjamin',
            'id_pasien'    => $id_pasien,
            'flag'         => 'index_penjamin',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function penjamin_pasien($id_pasien)
    {
        $assets = array();
        $assets_config = 'assets/master/pasien/edit';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->pasien_m->get($id_pasien);

        $data_penjamin = $this->penjamin_m->get_by(array('is_active' => 1));
        $data_penjamin = object_to_array($data_penjamin);

        $penjamin_pasien = $this->pasien_penjamin_m->get_by(array('pasien_id' => $id_pasien, 'is_active' => 1));
        $penjamin_pasien = object_to_array($penjamin_pasien);

        $data = array(
            'title'        => config_item('site_name').' | '. translate("Penjamin Pasien", $this->session->userdata("language")), 
            'header'       => translate("Penjamin Pasien", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'     => $this->menus,
            'menu_tree' => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'master/pasien/penjamin_pasien',
            'form_data' => object_to_array($form_data),
            'data_penjamin' => $data_penjamin,
            'data_penjamin_pasien' => $penjamin_pasien,
            'id_pasien'    => $id_pasien,
            'pk_value'    => $id_pasien,
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function add_penjamin($id_pasien)
    {
        $assets = array();
        $config = 'assets/master/pasien/add_penjamin';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        

        $data = array(
            'title'        => config_item('site_name').' | '. translate("Tambah Penjamin Pasien", $this->session->userdata("language")), 
            'header'       => translate("Tambah Penjamin Pasien", $this->session->userdata("language")), 
            'header_info'  => config_item('site_name'), 
            'breadcrumb'   => TRUE,
            'menus'     => $this->menus,
            'menu_tree' => $this->menu_tree,
            'css_files'    => $assets['css'],
            'js_files'     => $assets['js'],
            'content_view' => 'master/pasien/add_penjamin',
            'id_pasien'    => $id_pasien,
            'flag'         => 'add_penjamin'                         
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $this->load->model('master/info_alamat_m');
        
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/pasien/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->pasien_m->get($id);
        $form_data_faskes = $this->master_faskes_m->get_by(array('id' => $form_data->faskes_id), true);
        if(count($form_data) == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data pasien tidak ditemukan.", $this->session->userdata("language")),
                "msgTitle" => translate("Info", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('master/pasien');
        }

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Pasien", $this->session->userdata("language")), 
            'header'         => translate("Edit Pasien", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien/edit',
            'form_data'      => object_to_array($form_data),
            'form_data_faskes'      => (count($form_data_faskes) != 0 )?object_to_array($form_data_faskes):'',
            'pk_value'       => $id,
            'flag'           => 'edit'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function penanggung($id)
    {
        $this->load->model('master/info_alamat_m');
        
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/pasien/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->pasien_m->get($id);
        $form_data_faskes = $this->master_faskes_m->get_by(array('id' => $form_data->faskes_id), true);
        if(count($form_data) == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data pasien tidak ditemukan.", $this->session->userdata("language")),
                "msgTitle" => translate("Info", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('master/pasien');
        }

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Penanggung Pasien", $this->session->userdata("language")), 
            'header'         => translate("Penanggung Pasien", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien/penanggung',
            'form_data'      => object_to_array($form_data),
            'form_data_faskes'      => (count($form_data_faskes) != 0 )?object_to_array($form_data_faskes):'',
            'pk_value'       => $id,
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function info_lain_pasien($id)
    {
        $this->load->model('master/info_alamat_m');
        
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/pasien/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        $form_data = $this->pasien_m->get($id);
        $form_data_faskes = $this->master_faskes_m->get_by(array('id' => $form_data->faskes_id), true);
        if(count($form_data) == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data pasien tidak ditemukan.", $this->session->userdata("language")),
                "msgTitle" => translate("Info", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('master/pasien');
        }

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Dokumen Pasien", $this->session->userdata("language")), 
            'header'         => translate("Dokumen Pasien", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien/info_lain',
            'form_data'      => object_to_array($form_data),
            'form_data_faskes'      => (count($form_data_faskes) != 0 )?object_to_array($form_data_faskes):'',
            'pk_value'       => $id,
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function dokumen_pasien($id)
    {
        $this->load->model('master/info_alamat_m');
        
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/pasien/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        $form_data = $this->pasien_m->get($id);
        $form_data_faskes = $this->master_faskes_m->get_by(array('id' => $form_data->faskes_id), true);
        if(count($form_data) == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data pasien tidak ditemukan.", $this->session->userdata("language")),
                "msgTitle" => translate("Info", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('master/pasien');
        }

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Dokumen Pasien", $this->session->userdata("language")), 
            'header'         => translate("Dokumen Pasien", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien/dokumen',
            'form_data'      => object_to_array($form_data),
            'form_data_faskes'      => (count($form_data_faskes) != 0 )?object_to_array($form_data_faskes):'',
            'pk_value'       => $id,
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add_dokumen($pasien_id)
    {
        $data = array(
            'pasien' => object_to_array($this->pasien_m->get($pasien_id))
        );
        $this->load->view('master/tambah_dokumen_pasien/modal/tambah_dokumen_pasien', $data);
    }

    public function edit_dokumen($pasien_dok_id)
    {
        $this->load->model('master/dokumen_m');
        $this->load->model('master/pasien_dokumen_m');
        $data = array(
            'pasien_dokumen' => object_to_array($this->pasien_dokumen_m->get($pasien_dok_id))
        );
        $this->load->view('master/tambah_dokumen_pasien/modal/edit_dokumen_pasien', $data);
    }

        public function listing_upload($id=null)
    {        
        $result = $this->transaksi_dokter3_m->get_datatable($id);

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
            $tipe='';    
            $jenis='';
            $status='';
            $status2='';
            $tipe1='';

            if($row['tipe']==1){
                $jenis='Dokumen Pelengkap';
            }else{
                $jenis='Dokumen Rekam Medis';
            }

            $action = '<a title="'.translate('Update', $this->session->userdata('language')).'"  name="update" href="'.base_url().'master/pasien/edit_dokumen/'.$row['id'].'" data-target="#ajax_notes3" data-toggle="modal"  class="btn blue-chambray search-item"><i class="fa fa-edit"></i></a>
                         ';
            
            $notif = $row['notif_hari'].' day';       

            if($row['is_kadaluarsa'] == 1)
            {
                $date1=date_create(date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])));
                date_sub($date1,date_interval_create_from_date_string($notif));
                $startdate=date_format($date1,"Y-m-d");
                
                $tanggal_kadaluarsa = date('d M Y',strtotime($row['tanggal_kadaluarsa']));
            

                if(date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])) < date('Y-m-d') )
                {
                    $status='<span class="label label-danger">Kadaluarsa</span>';
                    $status2='Kadaluarsa';                
                }
                else if(date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])) >= date('Y-m-d') )
                {
                    if($startdate <= date('Y-m-d') )
                    {
                        $status='<span class="label label-warning">Peringatan</span></div>';
                        $status2='Peringatan';
                    }
                    else
                    {
                        $status='<span class="label label-success">Aktif</span></div>';
                        $status2='Aktif';                    
                    }
                }                 
            }
            else
            {
                $tanggal_kadaluarsa = '-';
                $status='<span class="label label-success">Aktif</span></div>';
                $status2='Aktif';
            }
       
            $output['data'][] = array(
                 '<div class="text-left">'.$row['nama'].'</div>',
                 '<div class="text-left">'.$tanggal_kadaluarsa.'</div>',
                 '<div class="text-left">'.$jenis.'</div>',
                 '<div class="text-left">'.$status.'</div>',
                 '<div class="text-left">'.$action.'</div>',
                $status2,
                $row['dokumen_id']
                 
            );
            $i++;
        }

        echo json_encode($output);
    }


    public function kelayakan_anggota($id)
    {
        $this->load->model('master/info_alamat_m');
        
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/pasien/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->pasien_m->get($id);
        $form_data_faskes = $this->master_faskes_m->get_by(array('id' => $form_data->faskes_id), true);
        if(count($form_data) == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data pasien tidak ditemukan.", $this->session->userdata("language")),
                "msgTitle" => translate("Info", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('master/pasien');
        }

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Edit Pasien", $this->session->userdata("language")), 
            'header'         => translate("Edit Pasien", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien/kelayakan_anggota',
            'form_data'      => object_to_array($form_data),
            'form_data_faskes'      => (count($form_data_faskes) != 0 )?object_to_array($form_data_faskes):'',
            'pk_value'       => $id,
            'flag'           => 'edit'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/pasien/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        //$this->cabang_m->set_columns($this->cabang_m->fillable_edit());
        //die_dump($this->user_level_m->get_data($id));
        $form_data = $this->pasien_m->get($id);
        if(count($form_data) == 0)
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data pasien tidak ditemukan.", $this->session->userdata("language")),
                "msgTitle" => translate("Info", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('master/pasien');
        }


        $data = array(
            'title'          => config_item('site_name').' | '. translate("View Pasien", $this->session->userdata("language")), 
            'header'         => translate("View Pasien", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien/view',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id,
            'flag'           => 'view'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit_penjamin($id, $id_pasien)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/pasien/edit_penjamin';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'              => config_item('site_name').' | '. translate("Edit Penjamin Pasien", $this->session->userdata("language")), 
            'header'             => translate("Edit Penjamin Pasien", $this->session->userdata("language")), 
            'header_info'        => config_item('site_name'), 
            'breadcrumb'         => TRUE,
            'menus'              => $this->menus,
            'menu_tree'          => $this->menu_tree,
            'css_files'          => $assets['css'],
            'js_files'           => $assets['js'],
            'content_view'       => 'master/pasien/edit_penjamin',
            'id_pasien_penjamin' => $id,
            'id_pasien'          => $id_pasien,
            'flag'               => 'edit_penjamin'                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($cabang_id, $month)
    {   
       
        if($month != '0'){
            $bln = date('m', strtotime($month));
        }else{
            $bln = 0;
        }   

        //die(dump($bln));

        
        $result = $this->pasien_m->get_datatable($cabang_id, $bln);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i=0;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {   

            if ($row['active'] == '1')
            {
                
                $data_view     = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/view/'.$row['id'].'" class="btn grey-cascade hidden"><i class="fa fa-search"></i></a>';
                $data_penjamin = '<a title="'.translate('Penjamin', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/penjamin/'.$row['id'].'" class="btn btn-primary hidden"><i class="fa fa-list-alt"></i></a>';
                $data_edit     = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete   = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data pasien ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';

                $action =  restriction_button($data_view, $user_level_id, 'master_pasien', 'view').restriction_button($data_penjamin, $user_level_id, 'master_pasien', 'penjamin').restriction_button($data_edit, $user_level_id, 'master_pasien','edit').restriction_button($data_delete, $user_level_id, 'master_pasien','delete');

            }else{
                $action = '<a title="'.translate('Restore', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan mengembalikan data pasien ini?', $this->session->userdata('language')).'" name="restore[]" data-action="restore" data-id="'.$row['id'].'" class="btn yellow"><i class="fa fa-undo"></i> </a>';
            }


            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('base_dir').config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.config_item('base_dir').config_item('site_img_pasien').'global/global_small.png">';
            }

            $output['data'][] = array(
                $row['id'],
                $img_url.$row['nama'],
                '<div class="text-left">'.$row['no_member'].'</div>' ,
                '<div class="text-left">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].', '.$row['kecamatan'].', '.$row['kecamatan'].', '.$row['kota'].'</div>',
                '<div class="text-left">'.$row['nama_cabang'].'</div>' ,
                '<div class="text-left inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_pasien()
    {
        
        $result = $this->pasien_m->get_datatable_pilih_pasien();
        //die_dump($this->db->last_query());

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
            
            $action = '';
            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-center">'.$row['alamat'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listing_penjamin($id_pasien)
    {
        
        $result = $this->pasien_penjamin_m->get_datatable_by_pasien($id_pasien);

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

            $nama_kelompok  ='';
            $action = '';
            if($row['is_active'] == 1 && $row['penjamin_id'] != 1)
            {
                $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/edit_penjamin/'.$row['id'].'/'.$id_pasien.'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                           <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data penjamin pasien ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" data-pasien_id="'.$id_pasien.'" class="btn red"><i class="fa fa-times"></i> </a>';
            }
            elseif($row['is_active'] == 1 && $row['penjamin_id'] == 1)
            {
                 $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/pasien/edit_penjamin/'.$row['id'].'/'.$id_pasien.'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
            }

            $status = '';
            if($row['status'] == 1)
            {
                $status = '<p class="label label-success">Tersedia</p>';
            }else
            {
                $status = '<p class="label label-danger">Tidak Tersedia</p>';
            }
            if ($row['nama_kelompok']!='') {
                    $nama_kelompok  =' ['.$row['nama_kelompok'].']';
            }
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].$nama_kelompok.'</div>',
                '<div class="text-center">'.$row['no_kartu'].'</div>',
                '<div class="text-center">'.$status.'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_data_klaim($id_pasien)
    {
        
        $result = $this->pasien_syarat_penjamin_m->get_datatable_pilih_data_klaim($id_pasien);
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
            
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn green-haze select"><i class="fa fa-check"></i></a>';
                

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['judul'].'</div>',
                '<div class="text-left">'.$row['value'].'</div>',
                '<div class="text-left">'.$row['tipe'].'</div>',
                '<div class="text-left">'.$row['pasien_syarat_penjamin_id'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save_info_lain()
    {
        $array_input = $this->input->post();
        $data_cabang = $this->cabang_m->get_by('tipe = 1 AND is_active = 1 AND id != 1 OR tipe = 0 AND is_active = 1 AND id != 1');

        $id_pasien = $array_input['id'];

        $data_pasien = array(
            'dokter_pengirim'     => $array_input['dokter_pengirim'],
            'pasien_id'           => $array_input['id_ref_pasien']
        );

        $save_pasien = insert_pasien($data_pasien,base_url(),$id_pasien);

        foreach ($data_cabang as $cabang) 
        {
            if($cabang->url != '' || $cabang->url != NULL)
            {
                $save_pasien = insert_pasien($data_pasien,$cabang->url,$id_pasien);
            }
        }

        $path_model = 'master/pasien_penyakit_m';

        $data_dok = array(
            'is_active' => 0
        );
        $wheres['pasien_id'] = $id_pasien;
        $id = update_data_api($data_dok,base_url(),$path_model,$wheres);   
        foreach ($data_cabang as $cabang) 
        {
            if($cabang->is_active == 1)
            {
                if($cabang->url != NULL && $cabang->url != '')
                {
                    $id = update_data_api($data_dok,$cabang->url,$path_model,$wheres);                                                      
                }
            }
        }

        if(isset($array_input['penyakit_bawaan']))
        {
            foreach ($array_input['penyakit_bawaan'] as $id_penyakit) 
            {
                if($id_penyakit != '')
                {
                    $penyakit_bawaan = $this->pasien_penyakit_m->get_by(array('pasien_id' => $id_pasien, 'penyakit_id' => $id_penyakit, 'tipe' => 1));
                    if(count($penyakit_bawaan) > 0)
                    {
                        foreach ($penyakit_bawaan as $bawaan) 
                        {
                            $data_bawaan['is_active'] = 0;

                            insert_data_api($data_bawaan,base_url(),$path_model,$bawaan->id);
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                {
                                    if($cabang->url != NULL && $cabang->url != '')
                                    {
                                        insert_data_api($data_bawaan,$cabang->url,$path_model,$bawaan->id);                     
                                    }
                                }
                            }
                        }
                    }                        
                    else
                    {
                        $data_bawaan_baru = array(
                            'pasien_id'   => $id_pasien,
                            'penyakit_id' => $id_penyakit,
                            'tipe'        => 1,
                            'is_active'   => 1
                        );
                        insert_data_api($data_bawaan_baru,base_url(),$path_model);
                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active == 1)
                            {
                                if($cabang->url != NULL && $cabang->url != '')
                                {
                                    insert_data_api($data_bawaan_baru,$cabang->url,$path_model);                     
                                }
                            }
                        }
                    }
                }
            }
        }

        if(isset($array_input['penyakit_penyebab']))
        {
            foreach ($array_input['penyakit_penyebab'] as $id_penyakit) 
            {
                if($id_penyakit != '')
                {
                    $penyakit_penyebab = $this->pasien_penyakit_m->get_by(array('pasien_id' => $id_pasien, 'penyakit_id' => $id_penyakit, 'tipe' => 2));
                    if(count($penyakit_penyebab) > 0)
                    {
                        foreach ($penyakit_penyebab as $sebab) 
                        {
                            $data_sebab['is_active'] = 0;

                            insert_data_api($data_sebab,base_url(),$path_model,$sebab->id);
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                {
                                    if($cabang->url != NULL && $cabang->url != '')
                                    {
                                        insert_data_api($data_sebab,$cabang->url,$path_model,$sebab->id);                     
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        $data_sebab_baru = array(
                            'pasien_id'   => $id_pasien,
                            'penyakit_id' => $id_penyakit,
                            'tipe'        => 2,
                            'is_active'   => 1
                        );
                        insert_data_api($data_sebab_baru,base_url(),$path_model);
                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->is_active == 1)
                            {
                                if($cabang->url != NULL && $cabang->url != '')
                                {
                                    insert_data_api($data_sebab_baru,$cabang->url,$path_model);                     
                                }
                            }
                        }
                    }                        
                }
            }
        }

    }

    public function save_dokumen()
    {
        $array_input = $this->input->post();
      //  die_dump($array_input);

        $data_cabang = $this->cabang_m->get_by(array('is_active' => 1));

        $pasien_id = $array_input['pasien_id'];
        $dokumen_id = $array_input['dokumen_id'];
        $penjamin_dokumen = $array_input['penjamin_dokumen'];
        $data_pasien = $this->pasien_m->get($pasien_id);

        $response = new stdClass;
        $response->success = false;

        if($array_input['command'] == "add") 
        {
            foreach ($penjamin_dokumen as $penj_dok) 
            {
                $data_penj_dok = array(
                    'pasien_id'          => $pasien_id,
                    'dokumen_id'         => $penj_dok['dokumen_id'],
                    'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                    'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                    'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                    'is_active'         => 1
                );

                $path_model = 'master/pasien_dokumen_m';
                //$pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model);
                //$inserted_pasien_dokumen_id = $pasien_dokumen_id;
                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $inserted_pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model);
                    }
                }

                $inserted_pasien_dokumen_id = str_replace('"', '', $inserted_pasien_dokumen_id);

                $response->success = true;

                $data_dok_history = array(
                    'pasien_id'          => $pasien_id,
                    'dokumen_id'         => $penj_dok['dokumen_id'],
                    'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                    'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                    'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                    'is_active'          => 1
                );

                $path_model = 'master/pasien_dok_history_m';
                //$pasien_dok_history_id = insert_data_api($data_dok_history,base_url(),$path_model);
                //$inserted_dok_history_id = $pasien_dok_history_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $inserted_dok_history_id = insert_data_api($data_dok_history,$cabang->url,$path_model);
                    }
                }
                $inserted_dok_history_id = str_replace('"', '', $inserted_dok_history_id);

                $penjamin_dokumen_detail = $array_input['penjamin_dokumen_detail_'.$penj_dok['dokumen_id']];
                if(isset($penjamin_dokumen_detail))
                {
                    foreach ($penjamin_dokumen_detail as $penj_dok_det) 
                    {
                        $data_penj_dok_det = array(
                            'pasien_dokumen_id' => $inserted_pasien_dokumen_id,
                            'judul'             => $penj_dok_det['judul'],
                            'tipe'              => $penj_dok_det['tipe'],
                            'value'              => $penj_dok_det['value'],
                            'is_active'         => 1
                        );

                        $path_model = 'master/pasien_dokumen_detail_m';
                        $pasien_dok_det_id = insert_data_api($data_penj_dok_det,base_url(),$path_model);
                        $inserted_pas_dok_det_id = $pasien_dok_det_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok_det_id = insert_data_api($data_penj_dok_det,$cabang->url,$path_model,$inserted_pas_dok_det_id);
                            }
                        }
                        $inserted_pas_dok_det_id = str_replace('"', '', $inserted_pas_dok_det_id);    

                        $data_dok_history_det = array(
                            'pasien_dok_history_id' => $inserted_dok_history_id,
                            'pasien_id'             => $pasien_id,
                            'dokumen_id'            => $penj_dok_det['dokumen_id'],
                            'judul'                 => $penj_dok_det['judul'],
                            'tipe'                  => $penj_dok_det['tipe'],
                            'value'                 => $penj_dok_det['value'],
                            'is_active'             => 1
                        );

                        $path_model = 'master/pasien_dok_history_detail_m';
                        $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,base_url(),$path_model);
                        $inserted_dok_history_det_id = $pasien_dok_history_det_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,$cabang->url,$path_model,$inserted_dok_history_det_id);
                            }
                        }
                        $inserted_dok_history_det_id = str_replace('"', '', $inserted_dok_history_det_id); 

                        if( $penj_dok_det['tipe'] == 9){
                            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$penj_dok_det['value']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$penj_dok_det['value'])) 
                            {
                                $data_path = array(
                                    'pasien_id'      => $pasien_id,
                                    'no_pasien'      => $data_pasien->no_member,
                                    'dokumen_id'     => $penj_dok['dokumen_id'],
                                    'nama_dokumen'   => $penj_dok_det['value'],
                                    'path_dokumen'   => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$data_pasien->no_member.'/dokumen',
                                    'tipe_dokumen'   => $penj_dok['tipe_dokumen'],
                                    'path_temporary' => '../cloud/var/temp',
                                    'temp_filename'  => $penj_dok_det['value'],
                                    'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                                );

                                $data_api = serialize($data_path);

                                $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->is_active == 1)
                                    {
                                        if($cabang->url != NULL && $cabang->url != '')
                                        {
                                            $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);  

                                        }
                                    }
                                }
                            }
                        }                    
                    }                    
                }
            }
        }
        if($array_input['command'] == "edit") 
        {
            foreach ($penjamin_dokumen as $penj_dok) 
            {
                $data_penj_dok = array(
                    'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                    'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                    'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                    'is_active'         => 1
                );

                $path_model = 'master/pasien_dokumen_m';
                //$pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model, $array_input['pasien_dokumen_id']);
                //$inserted_pasien_dokumen_id = $pasien_dokumen_id;
                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model,$array_input['pasien_dokumen_id']);
                    }
                }

                $inserted_pasien_dokumen_id = str_replace('"', '', $inserted_pasien_dokumen_id);

                $response->success = true;

                $data_dok_history = array(
                    'pasien_id'          => $pasien_id,
                    'dokumen_id'         => $penj_dok['dokumen_id'],
                    'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                    'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                    'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                    'is_active'          => 1
                );

                $path_model = 'master/pasien_dok_history_m';
                //$pasien_dok_history_id = insert_data_api($data_dok_history,base_url(),$path_model);
                //$inserted_dok_history_id = $pasien_dok_history_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $inserted_dok_history_id = insert_data_api($data_dok_history,$cabang->url,$path_model);
                    }
                }
                $inserted_dok_history_id = str_replace('"', '', $inserted_dok_history_id);

                $penjamin_dokumen_detail = $array_input['penjamin_dokumen_detail_'.$penj_dok['dokumen_id']];
                if(isset($penjamin_dokumen_detail))
                {
                    foreach ($penjamin_dokumen_detail as $penj_dok_det) 
                    {   
                        if($penj_dok_det['pasien_dok_det_id'] === ''){
                            $data_penj_dok_det = array(
                                'pasien_dokumen_id' => $array_input['pasien_dokumen_id'],
                                'judul'             => $penj_dok_det['judul'],
                                'tipe'              => $penj_dok_det['tipe'],
                                'value'              => $penj_dok_det['value'],
                                'is_active'         => 1
                            );

                            $path_model = 'master/pasien_dokumen_detail_m';
                            $pasien_dok_det_id = insert_data_api($data_penj_dok_det,base_url(),$path_model);
                            $inserted_pas_dok_det_id = $pasien_dok_det_id;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_dok_det_id = insert_data_api($data_penj_dok_det,$cabang->url,$path_model,$inserted_pas_dok_det_id);
                                }
                            }
                            $inserted_pas_dok_det_id = str_replace('"', '', $inserted_pas_dok_det_id);
                        }else{
                            $data_penj_dok_det = array(
                                'value'              => $penj_dok_det['value'],
                                'is_active'         => 1
                            );

                            $path_model = 'master/pasien_dokumen_detail_m';
                            $pasien_dok_det_id = insert_data_api($data_penj_dok_det,base_url(),$path_model,$penj_dok_det['pasien_dok_det_id']);

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_dok_det_id = insert_data_api($data_penj_dok_det,$cabang->url,$path_model,$penj_dok_det['pasien_dok_det_id']);
                                }
                            }
                        }

                          

                        $data_dok_history_det = array(
                            'pasien_dok_history_id' => $inserted_dok_history_id,
                            'pasien_id'             => $pasien_id,
                            'dokumen_id'            => $penj_dok_det['dokumen_id'],
                            'judul'                 => $penj_dok_det['judul'],
                            'tipe'                  => $penj_dok_det['tipe'],
                            'value'                 => $penj_dok_det['value'],
                            'is_active'             => 1
                        );

                        $path_model = 'master/pasien_dok_history_detail_m';
                        $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,base_url(),$path_model);
                        $inserted_dok_history_det_id = $pasien_dok_history_det_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,$cabang->url,$path_model,$inserted_dok_history_det_id);
                            }
                        }
                        $inserted_dok_history_det_id = str_replace('"', '', $inserted_dok_history_det_id); 

                        if( $penj_dok_det['tipe'] == 9){
                            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$penj_dok_det['value']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$penj_dok_det['value'])) 
                            {
                                $data_path = array(
                                    'pasien_id'      => $pasien_id,
                                    'no_pasien'      => $data_pasien->no_member,
                                    'dokumen_id'     => $penj_dok['dokumen_id'],
                                    'nama_dokumen'   => $penj_dok_det['value'],
                                    'path_dokumen'   => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$data_pasien->no_member.'/dokumen',
                                    'tipe_dokumen'   => $penj_dok['tipe_dokumen'],
                                    'path_temporary' => '../cloud/var/temp',
                                    'temp_filename'  => $penj_dok_det['value'],
                                    'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                                );

                                $data_api = serialize($data_path);

                                $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->is_active == 1)
                                    {
                                        if($cabang->url != NULL && $cabang->url != '')
                                        {
                                            $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);  

                                        }
                                    }
                                }
                            }
                        }  
                    }                    
                }
            }
        }
        die(json_encode($response));
    }


    public function save_penanggung()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;

            $array_input = $this->input->post();
            $data_cabang = $this->cabang_m->get_by('tipe = 1 AND is_active = 1 AND id != 1 OR tipe = 0 AND is_active = 1 AND id != 1');


            $id_pasien = $array_input['id'];

            $indexHp = 1;
            foreach ($array_input['hubungan_pasien'] as $hp) {

                //die_dump($hp);

                $data_path = array(
                    'no_member'      => $array_input['no_member'],
                    'no_ktp'         => $hp['ktp'],
                    'path_dokumen'   => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$array_input['no_member'].'/keluarga/'.$hp['ktp'],
                    'path_temporary' => '../cloud/var/temp',
                    'temp_filename'  => $hp['url_ktp'],
                    'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir'),
                    'tipe'           => 'keluarga',
                );

                //die_dump($data_path);

                $data_api = serialize($data_path);

                if ($hp['id'] == "" && $hp['is_delete'] == "") 
                {

                    if($hp['url_ktp'] != '')
                    {
                        $foto = move_pasien_keluarga_doc(base_url(),$data_api);
                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $foto = move_pasien_keluarga_doc($cabang->url,$data_api);
                            }
                        }
                    }
                    else
                    {
                        $foto = 'doc_global/document.png';
                    }   
                    

                    $tipe_hubungan = '';
                    if ($hp['set_penanggung_jawab'] == '1') {
                        $tipe_hubungan = '2';
                    }else{
                        $tipe_hubungan = '1';
                    }
                    $data_hp = array(
                        'pasien_id'     => $id_pasien,
                        'tipe_hubungan' => $tipe_hubungan, 
                        'nama'          => $hp['nama'],
                        'no_ktp'        => $hp['ktp'],
                        'url_ktp'       => $foto,
                        'is_active'     => '1',
                    );

                    // die_dump($data_hp);
                    // $save_hp = $this->pasien_hubungan_m->save($data_hp);
                    $save_hp = insert_pasien_hubungan($data_hp,base_url());
                    $inserted_hub_id = $save_hp;
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_hp = insert_pasien_hubungan($data_hp,$cabang->url,$inserted_hub_id);
                        }
                    }

                    $inserted_hub_id = str_replace('"', '', $inserted_hub_id);

                    foreach ($array_input[$indexHp.'_hp_phone'] as $hp_phone) 
                    {
                            
                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] == "" && $hp_phone['is_delete'] == "")
                        {
                            $data_hp_phone = array(
                                'pasien_hubungan_id' => $inserted_hub_id,
                                'subjek_id'          => $hp_phone['subjek'],
                                'nomor'              => $hp_phone['number'],
                                'is_primary'         => $hp_phone['is_primary_hp_phone'],
                                'is_active'          => '1',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model);
                            $inserted_hub_phone_id = $save_hp_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$inserted_hub_phone_id);
                                }
                            }
                        }

                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] != "" && $hp_phone['is_delete'] == "")
                        {
                            $data_hp_phone = array(
                                'subjek_id'  => $hp_phone['subjek'],
                                'nomor'      => $hp_phone['number'],
                                'is_primary' => $hp_phone['is_primary_hp_phone'],
                                'is_active'  => '1',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone, $hp_phone['hp_phone_id']);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model,$hp_phone['hp_phone_id']);

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$hp_phone['hp_phone_id']);
                                }
                            }   
                        }

                        // die_dump($this->db->last_query());

                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] != "" && $hp_phone['is_delete'] == "1")
                        {
                            $data_hp_phone = array(
                                'is_active'  => '0',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone, $hp_phone['hp_phone_id']);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model,$hp_phone['hp_phone_id']);

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$hp_phone['hp_phone_id']);
                                }
                            }   
                        }
                            
                    }

                    foreach ($array_input[$indexHp.'_hp_alamat'] as $hp_alamat) 
                    {
                        
                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] == "" && $hp_alamat['is_delete'] == "")
                        {
                            $data_hp_alamat = array(
                                'pasien_hubungan_id'    => $inserted_hub_id,
                                'subjek_id'    => $hp_alamat['subjek'],
                                'alamat'       => $hp_alamat['alamat'],
                                'rt_rw'        => $hp_alamat['rt'].'_'.$hp_alamat['rw'],
                                'kode_lokasi'    => $hp_alamat['kode'],
                                'kode_pos'     => $hp_alamat['kode_pos'],
                                'is_primary'   => $hp_alamat['is_primary_hp_alamat'],
                                'is_active'    => '1',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$inserted_hub_alm_id);
                                }
                            }

                        }

                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] != "" && $hp_alamat['is_delete'] == "")
                        {
                            $data_hp_alamat = array(
                                // 'pasien_hubungan_id'    => $save_hp,
                                'subjek_id'    => $hp_alamat['subjek'],
                                'alamat'       => $hp_alamat['alamat'],
                                'rt_rw'        => $hp_alamat['rt'].'_'.$hp_alamat['rw'],
                                'kode_lokasi'    => $hp_alamat['kode'],
                                'kode_pos'     => $hp_alamat['kode_pos'],
                                'is_primary'   => $hp_alamat['is_primary_hp_alamat'],
                                'is_active'    => '1',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat, $hp_alamat['hp_alamat_id']); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                                }
                            }
                        }

                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] != "" && $hp_alamat['is_delete'] == "1")
                        {
                            $data_hp_alamat = array(
                                'is_active'    => '0',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat, $hp_alamat['hp_alamat_id']); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                                }
                            }
                        }                    
                            
                    }
                }
                if ($hp['id'] != "" && $hp['is_delete'] == "") 
                {
                    $data_path = array(
                        'no_member'      => $array_input['no_member'],
                        'no_ktp'         => $hp['ktp'],
                        'path_dokumen'   => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$array_input['no_member'].'/penanggung/'.$hp['ktp'],
                        'path_temporary' => '../cloud/var/temp',
                        'temp_filename'  => $hp['url_ktp'],
                        'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir'),
                        'tipe'           => 'penanggung',
                    );

                    $data_api = serialize($data_path);


                    if($hp['url_ktp'] != '')
                    {
                        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$hp['url_ktp']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$hp['url_ktp']))
                        {
                            $foto = move_pasien_keluarga_doc(base_url(),$data_api);
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $foto = move_pasien_keluarga_doc($cabang->url,$data_api);
                                }
                            }                            
                        }
                        else
                        {
                            $foto = $hp['url_ktp'];
                        }
                    }
                    else
                    {
                        $foto = 'doc_global/document.png';
                    }

                    // die_dump($hp['set_penanggung_jawab']);
                    $tipe_hubungan = '';
                    if ($hp['set_penanggung_jawab'] == '1') {
                        $tipe_hubungan = '2';
                    }else{
                        $tipe_hubungan = '1';
                    }
                    $data_hp = array(
                        'pasien_id'     => $id_pasien,
                        'tipe_hubungan' => $tipe_hubungan, 
                        'nama'          => $hp['nama'],
                        'no_ktp'        => $hp['ktp'],
                        'url_ktp'       => $foto,
                        'is_active'     => '1',
                    );

                    // die_dump($data_hp);
                    // $save_hp = $this->pasien_hubungan_m->save($data_hp, $hp['id']);
                    $save_hp = insert_pasien_hubungan($data_hp,base_url(),$hp['id']);
                    $inserted_hub_id = $save_hp;
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_hp = insert_pasien_hubungan($data_hp,$cabang->url,$hp['id']);
                        }
                    }
                    $inserted_hub_id = str_replace('"', '', $inserted_hub_id);

                    foreach ($array_input[$indexHp.'_hp_phone'] as $hp_phone) 
                    {
                            
                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] == "" && $hp_phone['is_delete'] == "")
                        {
                            $data_hp_phone = array(
                                'pasien_hubungan_id'  => $inserted_hub_id,
                                'subjek_id'  => $hp_phone['subjek'],
                                'nomor'      => $hp_phone['number'],
                                'is_primary' => $hp_phone['is_primary_hp_phone'],
                                'is_active'  => '1',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model);
                            $inserted_hub_phone_id = $save_hp_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$inserted_hub_phone_id);
                                }
                            }
                        }

                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] != "" && $hp_phone['is_delete'] == "")
                        {
                            $data_hp_phone = array(
                                'subjek_id'  => $hp_phone['subjek'],
                                'nomor'      => $hp_phone['number'],
                                'is_primary' => $hp_phone['is_primary_hp_phone'],
                                'is_active'  => '1',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone, $hp_phone['hp_phone_id']);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model,$hp_phone['hp_phone_id']);
                            $inserted_hub_phone_id = $save_hp_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$hp_phone['hp_phone_id']);
                                }
                            }
                        }

                        // die_dump($this->db->last_query());

                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] != "" && $hp_phone['is_delete'] == "1")
                        {
                            $data_hp_phone = array(
                                'is_active'  => '0',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone, $hp_phone['hp_phone_id']);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model,$hp_phone['hp_phone_id']);
                            $inserted_hub_phone_id = $save_hp_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$hp_phone['hp_phone_id']);
                                }
                            }

                        }
                            
                    }

                    foreach ($array_input[$indexHp.'_hp_alamat'] as $hp_alamat) 
                    {
                        
                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] == "" && $hp_alamat['is_delete'] == "")
                        {
                            $data_hp_alamat = array(
                                'pasien_hubungan_id'    => $inserted_hub_id,
                                'subjek_id'    => $hp_alamat['subjek'],
                                'alamat'       => $hp_alamat['alamat'],
                                'rt_rw'        => $hp_alamat['rt'].'_'.$hp_alamat['rw'],
                                'kode_lokasi'    => $hp_alamat['kode'],
                                'kode_pos'     => $hp_alamat['kode_pos'],
                                'is_primary'   => $hp_alamat['is_primary_hp_alamat'],
                                'is_active'    => '1',
                            );
                            
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$inserted_hub_alm_id);
                                }
                            }  
                        }

                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] != "" && $hp_alamat['is_delete'] == "")
                        {
                            $data_hp_alamat = array(
                                // 'pasien_hubungan_id'    => $save_hp,
                                'subjek_id'    => $hp_alamat['subjek'],
                                'alamat'       => $hp_alamat['alamat'],
                                'rt_rw'        => $hp_alamat['rt'].'_'.$hp_alamat['rw'],
                                'kode_lokasi'    => $hp_alamat['kode'],
                                'kode_pos'     => $hp_alamat['kode_pos'],
                                'is_primary'   => $hp_alamat['is_primary_hp_alamat'],
                                'is_active'    => '1',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat, $hp_alamat['hp_alamat_id']);
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                                }
                            }  

                        }

                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] != "" && $hp_alamat['is_delete'] == "1")
                        {
                            $data_hp_alamat = array(
                                'is_active'    => '0',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat, $hp_alamat['hp_alamat_id']); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                                }
                            }
                        }                    
                            
                    }
                }
                if ($hp['id'] != "" && $hp['is_delete'] == "1"){
                    $data_hp = array('is_active' => '0');
                    // $save_hp = $this->pasien_hubungan_m->save($data_hp, $hp['id']);
                    $model = 'pasien_hubungan_alamat_m';
                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                    $inserted_hub_alm_id = $save_hp_alamat;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                        }
                    }

                }
                

            $indexHp++;
            }

            if (isset($array_input['penanggung_jawab'])) {
                
                foreach ($array_input['penanggung_jawab'] as $pj) 
                {
                    $data_path = array(
                        'no_member'      => $no_member,
                        'no_ktp'         => $pj['url_ktp'],
                        'path_dokumen'   => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$no_member.'/penanggung/'.$pj['url_ktp'],
                        'path_temporary' => '../cloud/'.config_item('site_dir').'var/temp',
                        'temp_filename'  => $pj['url_ktp'],
                        'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir'),
                        'tipe'           => 'penanggung',
                    );

                    $data_api = serialize($data_path);

                    if($pj['url_ktp'] != '')
                    {
                        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$pj['url_ktp']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$pj['url_ktp']))
                        {
                            $foto = move_pasien_keluarga_doc(base_url(),$data_api);
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $foto = move_pasien_keluarga_doc($cabang->url,$data_api);
                                }
                            }
                            
                        }
                        else
                        {
                            $foto = $pj['url_ktp'];
                        }
                    }
                    else
                    {
                        $foto = 'doc_global/document.png';
                    }
                    
                    $data_pj = array(
                        'pasien_id'     => $id_pasien,
                        'tipe_hubungan' => 2, 
                        'nama'          => $pj['nama'],
                        'no_ktp'        => $pj['ktp'],
                        'url_ktp'       => $foto,
                        'is_active'     => '1',

                    );

                    // $save_pj = $this->pasien_hubungan_m->save($data_pj);
                    $save_pj = insert_pasien_hubungan($data_pj,base_url());
                    $inserted_pj_id = $save_hp;
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_pj = insert_pasien_hubungan($data_pj,$cabang->url,$inserted_pj_id);
                        }
                    }

                    $inserted_pj_id = str_replace('"', '', $inserted_pj_id);
                    // die_dump($this->db->last_query());

                    foreach ($array_input['pj_phone'] as $pj_phone) 
                    {
                            
                        if($pj_phone['number'] != "")
                        {
                            $data_pj_phone = array(
                                'pasien_hubungan_id' => $inserted_pj_id,
                                'subjek_id'          => $pj_phone['subjek'],
                                'nomor'              => $pj_phone['number'],
                                'is_primary'         => $pj_phone['is_primary_pj_phone'],
                                'is_active'          => '1',
                            );
                            
                            // $save_pj_phone = $this->pasien_hubungan_telepon_m->save($data_pj_phone);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_pj_phone = insert_pasien_hub_tlp_alm($data_pj_phone,base_url(),$model);
                            $inserted_pj_phone_id = $save_pj_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_pj_phone = insert_pasien_hub_tlp_alm($data_pj_phone,$cabang->url,$model,$inserted_pj_phone_id);
                                }
                            } 
                        }
                            
                    }

                    foreach ($array_input['pj_alamat'] as $pj_alamat) 
                    {
                        
                        if($pj_alamat['alamat'] != "")
                        {
                            $data_pj_alamat = array(
                                'pasien_hubungan_id' => $inserted_pj_id,
                                'subjek_id'          => $pj_alamat['subjek'],
                                'alamat'             => $pj_alamat['alamat'],
                                'rt_rw'              => $pj_alamat['rt'].'_'.$pj_alamat['rw'],
                                'kode_lokasi'          => $pj_alamat['kode'],
                                'kode_pos'           => $pj_alamat['kode_pos'],
                                'is_primary'         => $pj_alamat['is_primary_pj_alamat'],
                                'is_active'          => '1',
                            );
                            
                            // $save_pj_alamat = $this->pasien_hubungan_alamat_m->save($data_pj_alamat); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_pj_alamat = insert_pasien_hub_tlp_alm($data_pj_alamat,base_url(),$model);
                            $inserted_pj_alm_id = $save_pj_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_pj_alamat = insert_pasien_hub_tlp_alm($data_pj_alamat,$cabang->url,$model,$inserted_pj_alm_id);
                                }
                            }
                        }                    
                            
                    }
                }
            }

            $response->success = true;
            $response->msg = 'Penanggung pasien berhasil diubah';

            die(json_encode($response));


        }
    }

    public function save_profile()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;


            $array_input = $this->input->post();
            $data_cabang = $this->cabang_m->get_by('tipe = 1 AND is_active = 1 AND id != 1 OR tipe = 0 AND is_active = 1 AND id != 1');
            $data_cabang_foto = $this->cabang_m->get_by(array('tipe' => 0, 'is_active' => 1, 'id != ' => 1));

            $id_pasien = $array_input['id'];

            $data_path = array(
                'no_member'           => $array_input['no_member'],
                'path_dokumen'        => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$array_input['no_member'],
                'path_dokumen_thumb'  => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$array_input['no_member'].'/foto/medium',
                'path_dokumen_thumb2' => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$array_input['no_member'].'/foto/small',
                'path_temporary'      => '../cloud/var/temp',
                'temp_filename'       => $array_input['url'],
                'path_temp'           => config_item('base_dir').config_item('user_img_temp_dir'),
                'path_temp_thumbs'    => config_item('base_dir').config_item('user_img_temp_thumb_dir'),
                'path_temp_thumbs2'   => config_item('base_dir').config_item('user_img_temp_thumb_small_dir'),
            );


            $data_api = serialize($data_path);

            //die_dump(file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$array_input['url']));

            if($array_input['url'] != '')
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$array_input['url'])) 
                {

                    $cabang_url = base_url();
                    $foto = move_pasien_photo($cabang_url,$data_api);

                    foreach ($data_cabang_foto as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            move_pasien_photo($cabang->url,$data_api);
                        }
                    }
                }
                else
                {
                    $foto = $array_input['url'];
                } 
            }
            else
            {
                $foto = 'global.png';
            }
            
            
            $is_faskes = '';
            $faskes = '';
            $nama_marketing = '';
            if ($array_input['faskes'] == "lain-lain") {

                if ($array_input['id_faskes_temp'] == "") {
                    $data_faskes_temp = array(
                        'faskes_id' => '0',
                        'nama' => $array_input['tambah_faskes'],
                        'nama_marketing' => $array_input['nama_marketing'],
                        'status' => '1'
                    );
                    // $faskes = $this->faskes_temp_m->save($data_faskes_temp);
                    $faskes = insert_faskes_temp($data_faskes_temp,base_url());
                    $inserted_faskes = $faskes;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $faskes = insert_faskes_temp($data_faskes_temp,$cabang->url,$inserted_faskes);
                        }
                    }
                    $nama = $array_input['tambah_faskes'];
                    $nama_marketing = $array_input['nama_marketing'];
                    $is_faskes = '0';
                }
                else
                {
                    $faskes_temp = $this->faskes_temp_m->get_by(array('nama' => $array_input['tambah_faskes']));
                    if(count($faskes_temp) != 0)
                    {
                        $data_faskes_temp = array(
                            'faskes_id' => '0',
                            'nama' => $array_input['tambah_faskes'],
                            'nama_marketing' => $array_input['nama_marketing'],
                            'status' => '1'
                        );
                        // $faskes = $this->faskes_temp_m->save($data_faskes_temp);
                        $faskes = insert_faskes_temp($data_faskes_temp,base_url());
                        $inserted_faskes = $faskes;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $faskes = insert_faskes_temp($data_faskes_temp,$cabang->url,$inserted_faskes);
                            }
                        }
                        $nama = $array_input['tambah_faskes'];
                        $nama_marketing = $array_input['nama_marketing'];
                        $is_faskes = '1';
                    }
                    else
                    {
                        $data_faskes_temp = array(
                            'faskes_id' => '0',
                            'nama' => $array_input['tambah_faskes'],
                            'nama_marketing' => $array_input['nama_marketing'],
                            'status' => '1'
                        );
                        // $faskes = $this->faskes_temp_m->save($data_faskes_temp, $array_input['id_faskes_temp']);
                        $faskes = insert_faskes_temp($data_faskes_temp,base_url(),$array_input['id_faskes_temp']);
                        $inserted_faskes = $faskes;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $faskes = insert_faskes_temp($data_faskes_temp,$cabang->url,$array_input['id_faskes_temp']);
                            }
                        }

                        $nama = $array_input['tambah_faskes'];
                        $nama_marketing = $array_input['nama_marketing'];
                        $is_faskes = '0';                        
                    }
                }
                
            }else{
                $faskes = $array_input['faskes']; 
                $nama_marketing = $array_input['nama_marketing']; 
                $is_faskes = '1';
            }
            $data_pasien = array(
                'cabang_id'           => $array_input['cabang_id'],
                'gender'              => $array_input['jenis_kelamin'],
                'no_member'           => $array_input['no_member'],
                'no_ktp'           => $array_input['no_ktp'],
                'no_bpjs'           => $array_input['no_bpjs'],
                'nama'                => $array_input['nama_lengkap'],
                'tempat_lahir'        => $array_input['tempat_lahir'],
                'tanggal_lahir'       => date("Y-m-d", strtotime($array_input['tanggal_lahir'])),
                'agama_id'            => $array_input['agama'],
                'golongan_darah_id'   => $array_input['golongan_darah'],
                'pendidikan_id'       => $array_input['pendidikan'],
                'pekerjaan_id'        => $array_input['pekerjaan'],
                'cara_masuk_id'        => $array_input['cara_masuk'],
                'url_photo'           => $foto,
                'is_active'           => '1',
            );

            // $save_pasien = $this->pasien_m->save($data_pasien, $id_pasien);
            $save_pasien = insert_pasien($data_pasien,base_url(),$id_pasien);

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $save_pasien = insert_pasien($data_pasien,$cabang->url,$id_pasien);
                }
            }

            // die_dump($this->db->last_query());
            foreach ($array_input['phone'] as $phone) 
            {
                if ($phone['id'] != "" && $phone['is_delete'] == "") 
                {
                    $data_phone = array(
                        'pasien_id'  => $id_pasien,
                        'subjek_id'  => $phone['subjek'],
                        'nomor'      => $phone['number'],
                        'is_primary' => $phone['is_primary_phone'],
                        'is_active'  => '1',
                    );
                    
                    // $save_phone = $this->pasien_telepon_m->save($data_phone, $phone['id']); 
                    $save_phone = insert_pasien_telepon($data_phone, base_url(), $phone['id']); 
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_phone = insert_pasien_telepon($data_phone,$cabang->url,$phone['id']);
                        }
                    }
                }

                if ($phone['id'] != "" && $phone['is_delete'] == "1") 
                {                       
                    $data_phone = array(
                        'is_active'  => '0',
                    );

                    $save_phone = insert_pasien_telepon($data_phone, base_url(), $phone['id']); 
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_phone = insert_pasien_telepon($data_phone,$cabang->url,$phone['id']);
                        }
                    }
                }
                    
                if ($phone['id'] == "" && $phone['is_delete'] == "" && $phone['number'] != "") 
                {
                    $data_phone = array(
                        'pasien_id'  => $id_pasien,
                        'subjek_id'  => $phone['subjek'],
                        'nomor'      => $phone['number'],
                        'is_primary' => $phone['is_primary_phone'],
                        'is_active'  => '1',
                    );
                    
                    // $save_phone = $this->pasien_telepon_m->save($data_phone); 
                    $save_phone = insert_pasien_telepon($data_phone, base_url()); 
                    $inserted_phone_id = $save_phone;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_phone = insert_pasien_telepon($data_phone,$cabang->url,$inserted_phone_id);
                        }
                    }
                }
                    
                    
                    
            }

            foreach ($array_input['alamat'] as $alamat) 
            {    
                if ($alamat['id'] != "" && $alamat['is_delete'] == "")
                { 
                    $data_alamat = array(
                        'pasien_id'    => $id_pasien,
                        'subjek_id'    => $alamat['subjek'],
                        'alamat'       => $alamat['alamat'],
                        'rt_rw'        => $alamat['rt'].'_'.$alamat['rw'],
                        'kode_lokasi'    => $alamat['kode'],
                        'kode_pos'     => $alamat['kode_pos'],
                        'is_primary'   => $alamat['is_primary_alamat'],
                        'is_active'    => '1',
                    );
                    
                    // $save_alamat = $this->pasien_alamat_m->save($data_alamat, $alamat['id']); 
                    $save_alamat = insert_pasien_alamat($data_alamat,base_url(),$alamat['id']);
                    $inserted_alamat_id = $save_alamat;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_alamat = insert_pasien_alamat($data_alamat,$cabang->url,$alamat['id']);
                        }
                    }
                }

                if ($alamat['id'] != "" && $alamat['is_delete'] == "1")
                { 
                    $data_alamat['is_active']=0;

                    // $save_alamat = $this->pasien_alamat_m->delete($alamat['id']); 
                    $save_alamat = insert_pasien_alamat($data_alamat,base_url(),$alamat['id']);
                    $inserted_alamat_id = $save_alamat;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_alamat = insert_pasien_alamat($data_alamat,$cabang->url,$alamat['id']);
                        }
                    }
                }

                if ($alamat['id'] == "")
                { 
                    if($alamat['alamat'] != "")
                    {
                        $data_alamat = array(
                            'pasien_id'    => $id_pasien,
                            'subjek_id'    => $alamat['subjek'],
                            'alamat'       => $alamat['alamat'],
                            'rt_rw'        => $alamat['rt'].'_'.$alamat['rw'],
                            'kode_lokasi'    => $alamat['kode'],
                            
                            'kode_pos'     => $alamat['kode_pos'],
                            'is_primary'   => ($alamat['is_primary_alamat'] != '')?$alamat['is_primary_alamat']:0,
                            'is_active'    => '1',
                        );
                        
                        $save_alamat = insert_pasien_alamat($data_alamat,base_url());

                        $inserted_alamat_id = $save_alamat;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_alamat = insert_pasien_alamat($data_alamat,$cabang->url,$inserted_alamat_id);
                            }
                        }                      
                    }
                }                    
            }

            $response->success = true;
            $response->msg = 'Profile pasien berhasil diubah';

            die(json_encode($response));
        }
    }

    public function save_kelayakan()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;

            $array_input = $this->input->post();
            $data_cabang = $this->cabang_m->get_by('tipe = 1 AND is_active = 1 AND id != 1 OR tipe = 0 AND is_active = 1 AND id != 1');

            $id_pasien = $array_input['id'];

            $data_pasien = array(
                'ref_kode_cabang'     => $array_input['kode_cabang_rujukan'],
                'ref_kode_rs_rujukan' => $array_input['kode_rs_rujukan'],
                'ref_nomor_rujukan'      => $array_input['nomer_rujukan'],
                'ref_tanggal_rujukan' => date("Y-m-d", strtotime($array_input['tanggal_rujukan'])),
                'kode_faskes'           => $array_input['kode_faskes'],
                'faskes_tk_1_id'      => $array_input['id_faskes_1'],
                'faskes_tk_1'         => $array_input['faskes_1'],
                'faskes_id'           => $array_input['id_faskes'],
                'is_faskes'           => 1,
                'dokter_pengirim'     => $array_input['dokter_pengirim'],
                'pasien_id'           => $array_input['id_ref_pasien'],
                'marketing_id'           => $array_input['id_marketing'],
                'marketing'           => $array_input['nama_marketing'],

            );

            // $save_pasien = $this->pasien_m->save($data_pasien, $id_pasien);
            $save_pasien = insert_pasien($data_pasien,base_url(),$id_pasien);

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $save_pasien = insert_pasien($data_pasien,$cabang->url,$id_pasien);
                }
            }

            $response->success = true;
            $response->msg = 'Kelayakan angoota pasien berhasil diubah';

            die(json_encode($response));
        }
    }

    public function save()
    {
        $array_input = $this->input->post();
        $data_cabang = $this->cabang_m->get_by('tipe = 1 AND is_active = 1 OR tipe = 0 AND is_active = 1');
        $data_cabang_apt = $this->cabang_m->get_by(array('tipe' => 4, 'is_active' => 1));
        $data_cabang_foto = $this->cabang_m->get_by(array('tipe' => 0, 'is_active' => 1, 'id != ' => 1));
        // die_dump($array_input);
        //die_dump($tanggal_lahir);
        if ($array_input['command'] === 'add')
        {  
            $last_number    = $this->pasien_m->get_no_member()->result_array();
            $last_number    = intval($last_number[0]['max_no_member'])+1;

            $format         = date('ym').'%03d';
            $no_member       = sprintf($format, $last_number, 3);
            
            $url = $this->input->post('url');

            $data_path = array(
                'no_member'           => $no_member,
                'path_dokumen'        =>'../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$no_member,
                'path_dokumen_thumb'  =>'../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$no_member.'/foto/medium',
                'path_dokumen_thumb2' =>'../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$no_member.'/foto/small',
                'path_temporary'      =>'../cloud/var/temp',
                'temp_filename'       => $url,
                'path_temp'           => config_item('base_dir').config_item('user_img_temp_dir'),
                'path_temp_thumbs'    => config_item('base_dir').config_item('user_img_temp_thumb_dir'),
                'path_temp_thumbs2'   => config_item('base_dir').config_item('user_img_temp_thumb_small_dir'),
            );

            $data_api = serialize($data_path);


            if($url != "")
            {
                $cabang_url = base_url();
                $foto = move_pasien_photo($cabang_url,$data_api);

                foreach ($data_cabang_foto as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        move_pasien_photo($cabang->url,$data_api);
                    }
                }
            }
            else
            {
                $foto = 'global.png';
            }
            
            $is_faskes = '';
            $faskes = '';
            $nama_marketing = '';
            if ($array_input['faskes'] == "lain-lain"){

                $data_faskes_temp = array(
                    'faskes_id'      => '0',
                    'nama'           => $array_input['tambah_faskes'],
                    'nama_marketing' => $array_input['nama_marketing'],
                    'status'         => '1',
                );

                $faskes_id = insert_faskes_temp($data_faskes_temp,base_url());
                $inserted_faskes_id = $faskes_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $faskes_id = insert_faskes_temp($data_faskes_temp,$cabang->url,$inserted_faskes_id);
                    }
                }
                $faskes = 0;
                $nama = $array_input['tambah_faskes'];
                $nama_marketing = $array_input['nama_marketing'];
                $is_faskes = '0';
            }
            else
            {
                $faskes = $array_input['faskes']; 
                $nama_marketing = $array_input['nama_marketing']; 
                $is_faskes = '1';
            }
            $data_pasien = array(
                'cabang_id'           => $array_input['cabang_id'],
                'gender'              => $array_input['jenis_kelamin'],
                'no_member'           => $no_member,
                'no_ktp'           => $array_input['no_ktp'],
                'no_bpjs'           => $array_input['no_bpjs'],
                'nama'                => $array_input['nama_lengkap'],
                'tempat_lahir'        => $array_input['tempat_lahir'],
                'tanggal_lahir'       => date("Y-m-d", strtotime($array_input['tanggal_lahir'])),
                'tanggal_daftar'      => date("Y-m-d", strtotime($array_input['tanggal_daftar'])),
                'agama_id'            => $array_input['agama'],
                'golongan_darah_id'   => $array_input['golongan_darah'],
                'pendidikan_id'       => $array_input['pendidikan'],
                'pekerjaan_id'        => $array_input['pekerjaan'],
                'cara_masuk_id'       => $array_input['cara_masuk'],
                'is_meninggal'        => '0',
                'berat_badan_kering'  => 'OBS',
                'ref_kode_cabang'     => $array_input['kode_cabang_rujukan'],
                'ref_kode_rs_rujukan' => $array_input['kode_rs_rujukan'],
                'ref_tanggal_rujukan' => date("Y-m-d", strtotime($array_input['tanggal_rujukan'])),
                'ref_nomor_rujukan'      => $array_input['nomer_rujukan'],
                'kode_faskes'         => $array_input['kode_faskes'],
                'faskes_tk_1_id'      => $array_input['id_faskes_1'],
                'faskes_tk_1'         => $array_input['faskes_1'],
                'faskes_id'           => $array_input['id_faskes'],
                'is_faskes'           => $is_faskes,
                'dokter_pengirim'     => $array_input['dokter_pengirim'],
                'pasien_id'           => $array_input['id_ref_pasien'],
                'marketing_id'        => $array_input['id_marketing'],
                'marketing'           => $array_input['nama_marketing'],
                'keterangan'          => $array_input['keterangan'],
                'url_photo'           => $foto,
                'tanggal_registrasi'  => date('Y-m-d'),
                'is_active'           => '1',
                'status'           => '1',
            );
            
            // die_dump($data_pasien);
            // $save_pasien = $this->pasien_m->save($data_pasien);
            $save_pasien = insert_pasien($data_pasien,base_url());
            $inserted_pasien_id = $save_pasien;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $save_pasien = insert_pasien($data_pasien,$cabang->url,$inserted_pasien_id);
                }
            }

            foreach ($data_cabang_apt as $cabang_apt) 
            {
                if($cabang_apt->url != '' || $cabang_apt->url != NULL)
                {
                    $save_pasien = insert_pasien($data_pasien,$cabang_apt->url,$inserted_pasien_id);
                }
            }
            $inserted_pasien_id = str_replace('"', '', $inserted_pasien_id);


            $data_pasien_penjamin = array(
                'pasien_id'            => $inserted_pasien_id,
                'no_kartu'             => '',
                'penjamin_id'          => 1,
                'penjamin_kelompok_id' => '',
                'status'               => '1',
                'is_active'            => 1
            );
            $path_model = 'master/pasien_penjamin_m';
            $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model);
            $inserted_pas_penj_id = $pasien_penjamin_id;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$inserted_pas_penj_id);
                }
            }


            foreach ($array_input['phone'] as $phone) 
            {
                    
                if($phone['number'] != "")
                {
                    $data_phone = array(
                        'pasien_id'  => $inserted_pasien_id,
                        'subjek_id'  => $phone['subjek'],
                        'nomor'      => $phone['number'],
                        'is_primary' => $phone['is_primary_phone'],
                        'is_active'  => '1',
                    );
                    
                    // $save_phone = $this->pasien_telepon_m->save($data_phone);
                    $save_phone = insert_pasien_telepon($data_phone,base_url());
                    $inserted_tlp_id = $save_phone;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_phone = insert_pasien_telepon($data_phone,$cabang->url,$inserted_tlp_id);
                        }
                    }

                    foreach ($data_cabang_apt as $cabang_apt) 
                    {
                        if($cabang_apt->url != '' || $cabang_apt->url != NULL)
                        {
                            $save_phone = insert_pasien_telepon($data_phone,$cabang->url,$inserted_tlp_id);
                        }
                    }

                }
                    
            }


            foreach ($array_input['alamat'] as $alamat) 
            {
                    
                if($alamat['alamat'] != "")
                {
                    $data_alamat = array(
                        'pasien_id'    => $inserted_pasien_id,
                        'subjek_id'    => $alamat['subjek'],
                        'alamat'       => $alamat['alamat'],
                        'rt_rw'        => $alamat['rt'].'_'.$alamat['rw'],
                        'kode_lokasi'  => $alamat['kode'],
                        'kode_pos'     => $alamat['kode_pos'],
                        'is_primary'   => $alamat['is_primary_alamat'],
                        'is_active'    => '1',
                    );
                    
                    $save_alamat = insert_pasien_alamat($data_alamat,base_url());
                    $inserted_alamat_id = $save_alamat;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_alamat = insert_pasien_alamat($data_alamat,$cabang->url,$inserted_alamat_id);
                        }
                    }

                    foreach ($data_cabang_apt as $cabang_apt) 
                    {
                        if($cabang_apt->url != '' || $cabang_apt->url != NULL)
                        {
                            $save_pasien = insert_pasien($data_alamat,$cabang_apt->url,$inserted_alamat_id);
                        }
                    }
                }                    
                    
            }  

            $data_penj_dok = array(
                'pasien_id'          => $inserted_pasien_id,
                'dokumen_id'         => 1,
                'is_kadaluarsa'      => 1,
                'is_required'        => 1,
                'tanggal_kadaluarsa' => date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])),
                'is_active'         => 1
            );

            $data_penj_dok_bpjs = array(
                'pasien_id'          => $inserted_pasien_id,
                'dokumen_id'         => 10,
                'is_kadaluarsa'      => 0,
                'is_required'        => 1,
                'is_active'         => 1
            );

            $path_model = 'master/pasien_dokumen_m';
            //$pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model);
            //$inserted_pasien_dokumen_id = $pasien_dokumen_id;
            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $inserted_pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model);
                    $inserted_pasien_dokumen_id_pbjs = insert_data_api($data_penj_dok_bpjs,$cabang->url,$path_model);
                }
            }

            $inserted_pasien_dokumen_id = str_replace('"', '', $inserted_pasien_dokumen_id);
            $inserted_pasien_dokumen_id_pbjs = str_replace('"', '', $inserted_pasien_dokumen_id_pbjs);

            $response->success = true;

            $data_dok_history = array(
                'pasien_id'          => $inserted_pasien_id,
                'dokumen_id'         => 1,
                'is_kadaluarsa'      => 1,
                'is_required'        => 1,
                'tanggal_kadaluarsa' => date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])),
                'is_active'         => 1
            );

            $data_dok_history_bpjs = array(
                'pasien_id'          => $inserted_pasien_id,
                'dokumen_id'         => 10,
                'is_kadaluarsa'      => 0,
                'is_required'        => 1,
                'is_active'         => 1
            );

            $path_model = 'master/pasien_dok_history_m';
            //$pasien_dok_history_id = insert_data_api($data_dok_history,base_url(),$path_model);
            //$inserted_dok_history_id = $pasien_dok_history_id;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $inserted_dok_history_id = insert_data_api($data_dok_history,$cabang->url,$path_model);
                    $inserted_dok_history_id_bpjs = insert_data_api($data_dok_history_bpjs,$cabang->url,$path_model);
                }
            }
            $inserted_dok_history_id = str_replace('"', '', $inserted_dok_history_id);
            $inserted_dok_history_id_bpjs = str_replace('"', '', $inserted_dok_history_id_bpjs);

            $data_penj_dok_det = array(
                'pasien_dokumen_id' => $inserted_pasien_dokumen_id,
                'judul'             => 'No KTP',
                'tipe'              => 1,
                'value'             => $array_input['no_ktp'],
                'is_active'         => 1
            );

            $path_model = 'master/pasien_dokumen_detail_m';
            $pasien_dok_det_id = insert_data_api($data_penj_dok_det,base_url(),$path_model);
            $inserted_pas_dok_det_id = $pasien_dok_det_id;

            $data_penj_dok_det_1 = array(
                'pasien_dokumen_id' => $inserted_pasien_dokumen_id,
                'judul'             => 'Upload Dokumen',
                'tipe'              => 9,
                'value'             => $array_input['url_ktp'],
                'is_active'         => 1
            );

            $path_model = 'master/pasien_dokumen_detail_m';
            $pasien_dok_det_id_1 = insert_data_api($data_penj_dok_det_1,base_url(),$path_model);
            $inserted_pas_dok_det_id_1 = $pasien_dok_det_id_1;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $pasien_dok_det_id = insert_data_api($data_penj_dok_det,$cabang->url,$path_model,$inserted_pas_dok_det_id);
                    $pasien_dok_det_id_1 = insert_data_api($data_penj_dok_det_1,$cabang->url,$path_model,$inserted_pas_dok_det_id_1);
                }
            }
            $inserted_pas_dok_det_id = str_replace('"', '', $inserted_pas_dok_det_id);    
            $inserted_pas_dok_det_id_1 = str_replace('"', '', $inserted_pas_dok_det_id_1);

            $data_penj_dok_det_bpjs = array(
                'pasien_dokumen_id' => $inserted_pasien_dokumen_id_pbjs,
                'judul'             => 'No BPJS',
                'tipe'              => 1,
                'value'             => $array_input['no_bpjs'],
                'is_active'         => 1
            );

            $path_model = 'master/pasien_dokumen_detail_m';
            $pasien_dok_det_id_bpjs = insert_data_api($data_penj_dok_det_bpjs,base_url(),$path_model);
            $inserted_pas_dok_det_id_bpjs = $pasien_dok_det_id_bpjs;

            $data_penj_dok_det_1_bpjs = array(
                'pasien_dokumen_id' => $inserted_pasien_dokumen_id_pbjs,
                'judul'             => 'Upload Dokumen',
                'tipe'              => 9,
                'value'             => $array_input['url_bpjs'],
                'is_active'         => 1
            );

            $path_model = 'master/pasien_dokumen_detail_m';
            $pasien_dok_det_id_1_bpjs = insert_data_api($data_penj_dok_det_1_bpjs,base_url(),$path_model);
            $inserted_pas_dok_det_id_1_bpjs = $pasien_dok_det_id_1;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $pasien_dok_det_id_bpjs = insert_data_api($data_penj_dok_det_bpjs,$cabang->url,$path_model,$inserted_pas_dok_det_id_bpjs);
                    $pasien_dok_det_id_1_bpjs = insert_data_api($data_penj_dok_det_1_bpjs,$cabang->url,$path_model,$inserted_pas_dok_det_id_1_bpjs);
                }
            }
            $inserted_pas_dok_det_id_bpjs = str_replace('"', '', $inserted_pas_dok_det_id_bpjs);    
            $inserted_pas_dok_det_id_1_bpjs = str_replace('"', '', $inserted_pas_dok_det_id_1_bpjs);    



            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$array_input['url_ktp']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$array_input['url_ktp'])) 
            {
                $data_path = array(
                    'pasien_id'      => $pasien_id,
                    'no_pasien'      => $no_member,
                    'dokumen_id'     => 1,
                    'nama_dokumen'   => $array_input['url_ktp'],
                    'path_dokumen'   => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$no_member.'/dokumen',
                    'tipe_dokumen'   => 'pelengkap',
                    'path_temporary' => '../cloud/var/temp',
                    'temp_filename'  => $array_input['url_ktp'],
                    'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                );

                $data_api = serialize($data_path);

                $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->is_active == 1)
                    {
                        if($cabang->url != NULL && $cabang->url != '')
                        {
                            $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);  

                        }
                    }
                }
            }
            
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$array_input['url_bpjs']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$array_input['url_bpjs'])) 
            {
                $data_path = array(
                    'pasien_id'      => $pasien_id,
                    'no_pasien'      => $no_member,
                    'dokumen_id'     => 1,
                    'nama_dokumen'   => $array_input['url_bpjs'],
                    'path_dokumen'   => '../cloud/'.config_item('site_dir').'pages/master/pasien/images/'.$no_member.'/dokumen',
                    'tipe_dokumen'   => 'pelengkap',
                    'path_temporary' => '../cloud/var/temp',
                    'temp_filename'  => $array_input['url_bpjs'],
                    'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                );

                $data_api = serialize($data_path);

                $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->is_active == 1)
                    {
                        if($cabang->url != NULL && $cabang->url != '')
                        {
                            $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);  

                        }
                    }
                }
            }


            if ($save_pasien || $save_phone || $save_alamat  ) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data pasien berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }
        elseif ($array_input['command'] === 'edit')
        {             
            // die(dump($array_input));
            $id_pasien = $array_input['id'];

            $data_path = array(
                'no_member'           => $array_input['no_member'],
                'path_dokumen'        => './assets/mb/pages/master/pasien/images/'.$array_input['no_member'],
                'path_dokumen_thumb'  => './assets/mb/pages/master/pasien/images/'.$array_input['no_member'].'/foto/medium',
                'path_dokumen_thumb2' => './assets/mb/pages/master/pasien/images/'.$array_input['no_member'].'/foto/small',
                'path_temporary'      => './assets/mb/var/temp',
                'temp_filename'       => $array_input['url'],
                'path_temp'           => config_item('base_dir').config_item('user_img_temp_dir'),
                'path_temp_thumbs'    => config_item('base_dir').config_item('user_img_temp_thumb_dir'),
                'path_temp_thumbs2'   => config_item('base_dir').config_item('user_img_temp_thumb_small_dir'),
            );

            $data_api = serialize($data_path);

            if($array_input['url'] != '')
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$array_input['url']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$array_input['url'])) 
                {

                    $cabang_url = base_url();
                    $foto = move_pasien_photo($cabang_url,$data_api);

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            move_pasien_photo($cabang->url,$data_api);
                        }
                    }
                }
                else
                {
                    $foto = $array_input['url'];
                } 
            }
            else
            {
                $foto = 'global.png';
            }
            
            
            $is_faskes = '';
            $faskes = '';
            $nama_marketing = '';
            if ($array_input['faskes'] == "lain-lain") {

                if ($array_input['id_faskes_temp'] == "") {
                    $data_faskes_temp = array(
                        'faskes_id' => '0',
                        'nama' => $array_input['tambah_faskes'],
                        'nama_marketing' => $array_input['nama_marketing'],
                        'status' => '1'
                    );
                    // $faskes = $this->faskes_temp_m->save($data_faskes_temp);
                    $faskes = insert_faskes_temp($data_faskes_temp,base_url());
                    $inserted_faskes = $faskes;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $faskes = insert_faskes_temp($data_faskes_temp,$cabang->url,$inserted_faskes);
                        }
                    }
                    $nama = $array_input['tambah_faskes'];
                    $nama_marketing = $array_input['nama_marketing'];
                    $is_faskes = '0';
                }
                else
                {
                    $faskes_temp = $this->faskes_temp_m->get_by(array('nama' => $array_input['tambah_faskes']));
                    if(count($faskes_temp) != 0)
                    {
                        $data_faskes_temp = array(
                            'faskes_id' => '0',
                            'nama' => $array_input['tambah_faskes'],
                            'nama_marketing' => $array_input['nama_marketing'],
                            'status' => '1'
                        );
                        // $faskes = $this->faskes_temp_m->save($data_faskes_temp);
                        $faskes = insert_faskes_temp($data_faskes_temp,base_url());
                        $inserted_faskes = $faskes;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $faskes = insert_faskes_temp($data_faskes_temp,$cabang->url,$inserted_faskes);
                            }
                        }
                        $nama = $array_input['tambah_faskes'];
                        $nama_marketing = $array_input['nama_marketing'];
                        $is_faskes = '1';
                    }
                    else
                    {
                        $data_faskes_temp = array(
                            'faskes_id' => '0',
                            'nama' => $array_input['tambah_faskes'],
                            'nama_marketing' => $array_input['nama_marketing'],
                            'status' => '1'
                        );
                        // $faskes = $this->faskes_temp_m->save($data_faskes_temp, $array_input['id_faskes_temp']);
                        $faskes = insert_faskes_temp($data_faskes_temp,base_url(),$array_input['id_faskes_temp']);
                        $inserted_faskes = $faskes;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $faskes = insert_faskes_temp($data_faskes_temp,$cabang->url,$array_input['id_faskes_temp']);
                            }
                        }

                        $nama = $array_input['tambah_faskes'];
                        $nama_marketing = $array_input['nama_marketing'];
                        $is_faskes = '0';                        
                    }
                }
                
            }else{
                $faskes = $array_input['faskes']; 
                $nama_marketing = $array_input['nama_marketing']; 
                $is_faskes = '1';
            }
            $data_pasien = array(
                'cabang_id'           => $array_input['cabang_id'],
                'gender'              => $array_input['jenis_kelamin'],
                'no_member'           => $array_input['no_member'],
                'nama'                => $array_input['nama_lengkap'],
                'tempat_lahir'        => $array_input['tempat_lahir'],
                'tanggal_lahir'       => date("Y-m-d", strtotime($array_input['tanggal_lahir'])),
                'agama_id'            => $array_input['agama'],
                'golongan_darah_id'   => $array_input['golongan_darah'],
                'pendidikan_id'       => $array_input['pendidikan'],
                'pekerjaan_id'        => $array_input['pekerjaan'],
                'cara_masuk_id'        => $array_input['cara_masuk'],
                'ref_kode_cabang'     => $array_input['kode_cabang_rujukan'],
                'ref_kode_rs_rujukan' => $array_input['kode_rs_rujukan'],
                'ref_nomor_rujukan'      => $array_input['nomer_rujukan'],
                'ref_tanggal_rujukan' => date("Y-m-d", strtotime($array_input['tanggal_rujukan'])),
                'kode_faskes'           => $array_input['kode_faskes'],
                'faskes_tk_1_id'      => $array_input['id_faskes_1'],
                'faskes_tk_1'         => $array_input['faskes_1'],
                'faskes_id'           => $array_input['id_faskes'],
                'is_faskes'           => $is_faskes,
                'dokter_pengirim'     => $array_input['dokter_pengirim'],
                'pasien_id'           => $array_input['id_ref_pasien'],
                'marketing_id'           => $array_input['id_marketing'],
                'marketing'           => $array_input['nama_marketing'],
                'keterangan'          => $array_input['keterangan'],
                'url_photo'           => $foto,
                'is_active'           => '1',
            );

            // $save_pasien = $this->pasien_m->save($data_pasien, $id_pasien);
            $save_pasien = insert_pasien($data_pasien,base_url(),$id_pasien);

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $save_pasien = insert_pasien($data_pasien,$cabang->url,$id_pasien);
                }
            }

            // die_dump($this->db->last_query());
            foreach ($array_input['phone'] as $phone) 
            {
                if ($phone['id'] != "" && $phone['is_delete'] == "") 
                {
                    $data_phone = array(
                        'pasien_id'  => $id_pasien,
                        'subjek_id'  => $phone['subjek'],
                        'nomor'      => $phone['number'],
                        'is_primary' => $phone['is_primary_phone'],
                        'is_active'  => '1',
                    );
                    
                    // $save_phone = $this->pasien_telepon_m->save($data_phone, $phone['id']); 
                    $save_phone = insert_pasien_telepon($data_phone, base_url(), $phone['id']); 
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_phone = insert_pasien_telepon($data_phone,$cabang->url,$phone['id']);
                        }
                    }
                }

                if ($phone['id'] != "" && $phone['is_delete'] == "1") 
                {                       
                    $data_phone = array(
                        'is_active'  => '0',
                    );

                    $save_phone = insert_pasien_telepon($data_phone, base_url(), $phone['id']); 
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_phone = insert_pasien_telepon($data_phone,$cabang->url,$phone['id']);
                        }
                    }
                }
                    
                if ($phone['id'] == "" && $phone['is_delete'] == "" && $phone['number'] != "") 
                {
                    $data_phone = array(
                        'pasien_id'  => $id_pasien,
                        'subjek_id'  => $phone['subjek'],
                        'nomor'      => $phone['number'],
                        'is_primary' => $phone['is_primary_phone'],
                        'is_active'  => '1',
                    );
                    
                    // $save_phone = $this->pasien_telepon_m->save($data_phone); 
                    $save_phone = insert_pasien_telepon($data_phone, base_url()); 
                    $inserted_phone_id = $save_phone;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_phone = insert_pasien_telepon($data_phone,$cabang->url,$inserted_phone_id);
                        }
                    }
                }
                    
                    
                    
            }

            foreach ($array_input['alamat'] as $alamat) 
            {    
                if ($alamat['id'] != "" && $alamat['is_delete'] == "")
                { 
                    $data_alamat = array(
                        'pasien_id'    => $id_pasien,
                        'subjek_id'    => $alamat['subjek'],
                        'alamat'       => $alamat['alamat'],
                        'rt_rw'        => $alamat['rt'].'_'.$alamat['rw'],
                        'kode_lokasi'    => $alamat['kode'],
                        'kode_pos'     => $alamat['kode_pos'],
                        'is_primary'   => $alamat['is_primary_alamat'],
                        'is_active'    => '1',
                    );
                    
                    // $save_alamat = $this->pasien_alamat_m->save($data_alamat, $alamat['id']); 
                    $save_alamat = insert_pasien_alamat($data_alamat,base_url(),$alamat['id']);
                    $inserted_alamat_id = $save_alamat;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_alamat = insert_pasien_alamat($data_alamat,$cabang->url,$alamat['id']);
                        }
                    }
                }

                if ($alamat['id'] != "" && $alamat['is_delete'] == "1")
                { 
                    $data_alamat['is_active']=0;

                    // $save_alamat = $this->pasien_alamat_m->delete($alamat['id']); 
                    $save_alamat = insert_pasien_alamat($data_alamat,base_url(),$alamat['id']);
                    $inserted_alamat_id = $save_alamat;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_alamat = insert_pasien_alamat($data_alamat,$cabang->url,$alamat['id']);
                        }
                    }
                }

                if ($alamat['id'] == "")
                { 
                    if($alamat['alamat'] != "")
                    {
                        $data_alamat = array(
                            'pasien_id'    => $id_pasien,
                            'subjek_id'    => $alamat['subjek'],
                            'alamat'       => $alamat['alamat'],
                            'rt_rw'        => $alamat['rt'].'_'.$alamat['rw'],
                            'kode_lokasi'    => $alamat['kode'],
                            
                            'kode_pos'     => $alamat['kode_pos'],
                            'is_primary'   => ($alamat['is_primary_alamat'] != '')?$alamat['is_primary_alamat']:0,
                            'is_active'    => '1',
                        );
                        
                        $save_alamat = insert_pasien_alamat($data_alamat,base_url());

                        $inserted_alamat_id = $save_alamat;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $save_alamat = insert_pasien_alamat($data_alamat,$cabang->url,$inserted_alamat_id);
                            }
                        }                      
                    }
                }                    
            }

            $indexHp = 1;
            foreach ($array_input['hubungan_pasien'] as $hp) {

                $data_path = array(
                    'no_member'      => $array_input['no_member'],
                    'no_ktp'         => $hp['ktp'],
                    'path_dokumen'   => './assets/mb/pages/master/pasien/images/'.$array_input['no_member'].'/keluarga/'.$hp['ktp'],
                    'path_temporary' => './assets/mb/var/temp',
                    'temp_filename'  => $hp['url_ktp'],
                    'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir'),
                    'tipe'           => 'keluarga',
                );

                $data_api = serialize($data_path);

                if ($hp['id'] == "" && $hp['is_delete'] == "") 
                {

                    if($hp['url_ktp'] != '')
                    {
                        $foto = move_pasien_keluarga_doc(base_url(),$data_api);
                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $foto = move_pasien_keluarga_doc($cabang->url,$data_api);
                            }
                        }
                    }
                    else
                    {
                        $foto = 'doc_global/document.png';
                    }   
                    

                    $tipe_hubungan = '';
                    if ($hp['set_penanggung_jawab'] == '1') {
                        $tipe_hubungan = '2';
                    }else{
                        $tipe_hubungan = '1';
                    }
                    $data_hp = array(
                        'pasien_id'     => $id_pasien,
                        'tipe_hubungan' => $tipe_hubungan, 
                        'nama'          => $hp['nama'],
                        'no_ktp'        => $hp['ktp'],
                        'url_ktp'       => $foto,
                        'is_active'     => '1',
                    );

                    // die_dump($data_hp);
                    // $save_hp = $this->pasien_hubungan_m->save($data_hp);
                    $save_hp = insert_pasien_hubungan($data_hp,base_url());
                    $inserted_hub_id = $save_hp;
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_hp = insert_pasien_hubungan($data_hp,$cabang->url,$inserted_hub_id);
                        }
                    }

                    $inserted_hub_id = str_replace('"', '', $inserted_hub_id);

                    foreach ($array_input[$indexHp.'_hp_phone'] as $hp_phone) 
                    {
                            
                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] == "" && $hp_phone['is_delete'] == "")
                        {
                            $data_hp_phone = array(
                                'pasien_hubungan_id' => $inserted_hub_id,
                                'subjek_id'          => $hp_phone['subjek'],
                                'nomor'              => $hp_phone['number'],
                                'is_primary'         => $hp_phone['is_primary_hp_phone'],
                                'is_active'          => '1',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model);
                            $inserted_hub_phone_id = $save_hp_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$inserted_hub_phone_id);
                                }
                            }
                        }

                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] != "" && $hp_phone['is_delete'] == "")
                        {
                            $data_hp_phone = array(
                                'subjek_id'  => $hp_phone['subjek'],
                                'nomor'      => $hp_phone['number'],
                                'is_primary' => $hp_phone['is_primary_hp_phone'],
                                'is_active'  => '1',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone, $hp_phone['hp_phone_id']);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model,$hp_phone['hp_phone_id']);

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$hp_phone['hp_phone_id']);
                                }
                            }   
                        }

                        // die_dump($this->db->last_query());

                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] != "" && $hp_phone['is_delete'] == "1")
                        {
                            $data_hp_phone = array(
                                'is_active'  => '0',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone, $hp_phone['hp_phone_id']);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model,$hp_phone['hp_phone_id']);

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$hp_phone['hp_phone_id']);
                                }
                            }   
                        }
                            
                    }

                    foreach ($array_input[$indexHp.'_hp_alamat'] as $hp_alamat) 
                    {
                        
                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] == "" && $hp_alamat['is_delete'] == "")
                        {
                            $data_hp_alamat = array(
                                'pasien_hubungan_id'    => $inserted_hub_id,
                                'subjek_id'    => $hp_alamat['subjek'],
                                'alamat'       => $hp_alamat['alamat'],
                                'rt_rw'        => $hp_alamat['rt'].'_'.$hp_alamat['rw'],
                                'kode_lokasi'    => $hp_alamat['kode'],
                                'kode_pos'     => $hp_alamat['kode_pos'],
                                'is_primary'   => $hp_alamat['is_primary_hp_alamat'],
                                'is_active'    => '1',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$inserted_hub_alm_id);
                                }
                            }

                        }

                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] != "" && $hp_alamat['is_delete'] == "")
                        {
                            $data_hp_alamat = array(
                                // 'pasien_hubungan_id'    => $save_hp,
                                'subjek_id'    => $hp_alamat['subjek'],
                                'alamat'       => $hp_alamat['alamat'],
                                'rt_rw'        => $hp_alamat['rt'].'_'.$hp_alamat['rw'],
                                'kode_lokasi'    => $hp_alamat['kode'],
                                'kode_pos'     => $hp_alamat['kode_pos'],
                                'is_primary'   => $hp_alamat['is_primary_hp_alamat'],
                                'is_active'    => '1',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat, $hp_alamat['hp_alamat_id']); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                                }
                            }
                        }

                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] != "" && $hp_alamat['is_delete'] == "1")
                        {
                            $data_hp_alamat = array(
                                'is_active'    => '0',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat, $hp_alamat['hp_alamat_id']); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                                }
                            }
                        }                    
                            
                    }
                }
                if ($hp['id'] != "" && $hp['is_delete'] == "") 
                {
                    $data_path = array(
                        'no_member'      => $array_input['no_member'],
                        'no_ktp'         => $hp['ktp'],
                        'path_dokumen'   => './assets/mb/pages/master/pasien/images/'.$array_input['no_member'].'/penanggung/'.$hp['ktp'],
                        'path_temporary' => './assets/mb/var/temp',
                        'temp_filename'  => $hp['url_ktp'],
                        'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir'),
                        'tipe'           => 'penanggung',
                    );

                    $data_api = serialize($data_path);


                    if($hp['url_ktp'] != '')
                    {
                        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$hp['url_ktp']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$hp['url_ktp']))
                        {
                            $foto = move_pasien_keluarga_doc(base_url(),$data_api);
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $foto = move_pasien_keluarga_doc($cabang->url,$data_api);
                                }
                            }                            
                        }
                        else
                        {
                            $foto = $hp['url_ktp'];
                        }
                    }
                    else
                    {
                        $foto = 'doc_global/document.png';
                    }

                    // die_dump($hp['set_penanggung_jawab']);
                    $tipe_hubungan = '';
                    if ($hp['set_penanggung_jawab'] == '1') {
                        $tipe_hubungan = '2';
                    }else{
                        $tipe_hubungan = '1';
                    }
                    $data_hp = array(
                        'pasien_id'     => $id_pasien,
                        'tipe_hubungan' => $tipe_hubungan, 
                        'nama'          => $hp['nama'],
                        'no_ktp'        => $hp['ktp'],
                        'url_ktp'       => $foto,
                        'is_active'     => '1',
                    );

                    // die_dump($data_hp);
                    // $save_hp = $this->pasien_hubungan_m->save($data_hp, $hp['id']);
                    $save_hp = insert_pasien_hubungan($data_hp,base_url(),$hp['id']);
                    $inserted_hub_id = $save_hp;
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_hp = insert_pasien_hubungan($data_hp,$cabang->url,$hp['id']);
                        }
                    }
                    $inserted_hub_id = str_replace('"', '', $inserted_hub_id);

                    foreach ($array_input[$indexHp.'_hp_phone'] as $hp_phone) 
                    {
                            
                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] == "" && $hp_phone['is_delete'] == "")
                        {
                            $data_hp_phone = array(
                                'pasien_hubungan_id'  => $inserted_hub_id,
                                'subjek_id'  => $hp_phone['subjek'],
                                'nomor'      => $hp_phone['number'],
                                'is_primary' => $hp_phone['is_primary_hp_phone'],
                                'is_active'  => '1',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model);
                            $inserted_hub_phone_id = $save_hp_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$inserted_hub_phone_id);
                                }
                            }
                        }

                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] != "" && $hp_phone['is_delete'] == "")
                        {
                            $data_hp_phone = array(
                                'subjek_id'  => $hp_phone['subjek'],
                                'nomor'      => $hp_phone['number'],
                                'is_primary' => $hp_phone['is_primary_hp_phone'],
                                'is_active'  => '1',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone, $hp_phone['hp_phone_id']);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model,$hp_phone['hp_phone_id']);
                            $inserted_hub_phone_id = $save_hp_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$hp_phone['hp_phone_id']);
                                }
                            }
                        }

                        // die_dump($this->db->last_query());

                        if($hp_phone['number'] != "" && $hp_phone['hp_phone_id'] != "" && $hp_phone['is_delete'] == "1")
                        {
                            $data_hp_phone = array(
                                'is_active'  => '0',
                            );
                            
                            // $save_hp_phone = $this->pasien_hubungan_telepon_m->save($data_hp_phone, $hp_phone['hp_phone_id']);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,base_url(),$model,$hp_phone['hp_phone_id']);
                            $inserted_hub_phone_id = $save_hp_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_phone = insert_pasien_hub_tlp_alm($data_hp_phone,$cabang->url,$model,$hp_phone['hp_phone_id']);
                                }
                            }

                        }
                            
                    }

                    foreach ($array_input[$indexHp.'_hp_alamat'] as $hp_alamat) 
                    {
                        
                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] == "" && $hp_alamat['is_delete'] == "")
                        {
                            $data_hp_alamat = array(
                                'pasien_hubungan_id'    => $inserted_hub_id,
                                'subjek_id'    => $hp_alamat['subjek'],
                                'alamat'       => $hp_alamat['alamat'],
                                'rt_rw'        => $hp_alamat['rt'].'_'.$hp_alamat['rw'],
                                'kode_lokasi'    => $hp_alamat['kode'],
                                'kode_pos'     => $hp_alamat['kode_pos'],
                                'is_primary'   => $hp_alamat['is_primary_hp_alamat'],
                                'is_active'    => '1',
                            );
                            
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$inserted_hub_alm_id);
                                }
                            }  
                        }

                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] != "" && $hp_alamat['is_delete'] == "")
                        {
                            $data_hp_alamat = array(
                                // 'pasien_hubungan_id'    => $save_hp,
                                'subjek_id'    => $hp_alamat['subjek'],
                                'alamat'       => $hp_alamat['alamat'],
                                'rt_rw'        => $hp_alamat['rt'].'_'.$hp_alamat['rw'],
                                'kode_lokasi'    => $hp_alamat['kode'],
                                'kode_pos'     => $hp_alamat['kode_pos'],
                                'is_primary'   => $hp_alamat['is_primary_hp_alamat'],
                                'is_active'    => '1',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat, $hp_alamat['hp_alamat_id']);
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                                }
                            }  

                        }

                        if($hp_alamat['alamat'] != "" && $hp_alamat['hp_alamat_id'] != "" && $hp_alamat['is_delete'] == "1")
                        {
                            $data_hp_alamat = array(
                                'is_active'    => '0',
                            );
                            
                            // $save_hp_alamat = $this->pasien_hubungan_alamat_m->save($data_hp_alamat, $hp_alamat['hp_alamat_id']); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                            $inserted_hub_alm_id = $save_hp_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                                }
                            }
                        }                    
                            
                    }
                }
                if ($hp['id'] != "" && $hp['is_delete'] == "1"){
                    $data_hp = array('is_active' => '0');
                    // $save_hp = $this->pasien_hubungan_m->save($data_hp, $hp['id']);
                    $model = 'pasien_hubungan_alamat_m';
                    $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,base_url(),$model,$hp_alamat['hp_alamat_id']);
                    $inserted_hub_alm_id = $save_hp_alamat;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_hp_alamat = insert_pasien_hub_tlp_alm($data_hp_alamat,$cabang->url,$model,$hp_alamat['hp_alamat_id']);
                        }
                    }

                }
                

            $indexHp++;
            }

            if (isset($array_input['penanggung_jawab'])) {
                
                foreach ($array_input['penanggung_jawab'] as $pj) 
                {
                    $data_path = array(
                        'no_member'      => $no_member,
                        'no_ktp'         => $pj['url_ktp'],
                        'path_dokumen'   => './assets/mb/pages/master/pasien/images/'.$no_member.'/penanggung/'.$pj['url_ktp'],
                        'path_temporary' => './assets/mb/var/temp',
                        'temp_filename'  => $pj['url_ktp'],
                        'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir'),
                        'tipe'           => 'penanggung',
                    );

                    $data_api = serialize($data_path);

                    if($pj['url_ktp'] != '')
                    {
                        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$pj['url_ktp']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$pj['url_ktp']))
                        {
                            $foto = move_pasien_keluarga_doc(base_url(),$data_api);
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $foto = move_pasien_keluarga_doc($cabang->url,$data_api);
                                }
                            }
                            
                        }
                        else
                        {
                            $foto = $pj['url_ktp'];
                        }
                    }
                    else
                    {
                        $foto = 'doc_global/document.png';
                    }
                    
                    $data_pj = array(
                        'pasien_id'     => $id_pasien,
                        'tipe_hubungan' => 2, 
                        'nama'          => $pj['nama'],
                        'no_ktp'        => $pj['ktp'],
                        'url_ktp'       => $foto,
                        'is_active'     => '1',

                    );

                    // $save_pj = $this->pasien_hubungan_m->save($data_pj);
                    $save_pj = insert_pasien_hubungan($data_pj,base_url());
                    $inserted_pj_id = $save_hp;
                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $save_pj = insert_pasien_hubungan($data_pj,$cabang->url,$inserted_pj_id);
                        }
                    }

                    $inserted_pj_id = str_replace('"', '', $inserted_pj_id);
                    // die_dump($this->db->last_query());

                    foreach ($array_input['pj_phone'] as $pj_phone) 
                    {
                            
                        if($pj_phone['number'] != "")
                        {
                            $data_pj_phone = array(
                                'pasien_hubungan_id' => $inserted_pj_id,
                                'subjek_id'          => $pj_phone['subjek'],
                                'nomor'              => $pj_phone['number'],
                                'is_primary'         => $pj_phone['is_primary_pj_phone'],
                                'is_active'          => '1',
                            );
                            
                            // $save_pj_phone = $this->pasien_hubungan_telepon_m->save($data_pj_phone);
                            $model = 'pasien_hubungan_telepon_m';
                            $save_pj_phone = insert_pasien_hub_tlp_alm($data_pj_phone,base_url(),$model);
                            $inserted_pj_phone_id = $save_pj_phone;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_pj_phone = insert_pasien_hub_tlp_alm($data_pj_phone,$cabang->url,$model,$inserted_pj_phone_id);
                                }
                            } 
                        }
                            
                    }

                    foreach ($array_input['pj_alamat'] as $pj_alamat) 
                    {
                        
                        if($pj_alamat['alamat'] != "")
                        {
                            $data_pj_alamat = array(
                                'pasien_hubungan_id' => $inserted_pj_id,
                                'subjek_id'          => $pj_alamat['subjek'],
                                'alamat'             => $pj_alamat['alamat'],
                                'rt_rw'              => $pj_alamat['rt'].'_'.$pj_alamat['rw'],
                                'kode_lokasi'          => $pj_alamat['kode'],
                                'kode_pos'           => $pj_alamat['kode_pos'],
                                'is_primary'         => $pj_alamat['is_primary_pj_alamat'],
                                'is_active'          => '1',
                            );
                            
                            // $save_pj_alamat = $this->pasien_hubungan_alamat_m->save($data_pj_alamat); 
                            $model = 'pasien_hubungan_alamat_m';
                            $save_pj_alamat = insert_pasien_hub_tlp_alm($data_pj_alamat,base_url(),$model);
                            $inserted_pj_alm_id = $save_pj_alamat;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $save_pj_alamat = insert_pasien_hub_tlp_alm($data_pj_alamat,$cabang->url,$model,$inserted_pj_alm_id);
                                }
                            }
                        }                    
                            
                    }
                }
            }

            if(isset($array_input['penjamin_dokumen']))
            {
                foreach ($array_input['penjamin_dokumen'] as $penj_dok) 
                {
                    $data_dok_history = array(
                        'pasien_id'          => $id_pasien,
                        'dokumen_id'         => $penj_dok['dokumen_id'],
                        'is_kadaluarsa'      => $penj_dok['is_kadaluarsa'],
                        'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa'] == 0)?NULL:date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])),
                        'is_required'        => $penj_dok['is_required'],
                        'is_active'          => 1
                    );

                    $path_model = 'master/pasien_dok_history_m';
                    $pasien_dok_history_id = insert_data_api($data_dok_history,base_url(),$path_model);
                    $inserted_dok_history_id = $pasien_dok_history_id;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $pasien_dok_history_id = insert_data_api($data_dok_history,$cabang->url,$path_model,$inserted_dok_history_id);
                        }
                    }
                    $inserted_dok_history_id = str_replace('"', '', $inserted_dok_history_id);

                    if($penj_dok['pasien_dokumen_id'] != '')
                    {
                        $data_pasien_dok = array(
                            'tanggal_kadaluarsa'    => ($penj_dok['tanggal_kadaluarsa'] == '')?NULL:date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa']))
                        );

                        $path_model = 'master/pasien_dokumen_m';
                        $pasien_dok = insert_data_api($data_pasien_dok,base_url(),$path_model, $penj_dok['pasien_dokumen_id']);
                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok = insert_data_api($data_pasien_dok,$cabang->url,$path_model,$penj_dok['pasien_dokumen_id']);
                            }
                        }                        
                    }
                    elseif($penj_dok['pasien_dokumen_id'] == '')
                    {
                        $data_pasien_dok = array(
                            'pasien_id'          => $id_pasien,
                            'dokumen_id'         => $penj_dok['dokumen_id'],
                            'is_kadaluarsa'      => $penj_dok['is_kadaluarsa'],
                            'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa'] == 0)?NULL:date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])),
                            'is_required'        => $penj_dok['is_required'],
                            'is_active'          => 1
                        );

                        $path_model = 'master/pasien_dokumen_m';
                        $pasien_dok = insert_data_api($data_pasien_dok,base_url(),$path_model);
                        $inserted_pas_dok_id = $pasien_dok;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok = insert_data_api($data_pasien_dok,$cabang->url,$path_model,$penj_dok['pasien_dokumen_id']);
                            }
                        }
                        $inserted_pas_dok_id = str_replace('"', '', $inserted_pas_dok_id);
                    }

                    $penjamin_dokumen_detail = $array_input['penjamin_dokumen_detail_'.$penj_dok['dokumen_id']];
                    if($penjamin_dokumen_detail)
                    {
                        foreach ($penjamin_dokumen_detail as $penj_dok_det) 
                        {
                            if($penj_dok['pasien_dokumen_id'] == '')
                            {
                                $data_penj_dok_det = array(
                                    'pasien_dokumen_id' => $inserted_pas_dok_id,
                                    'judul'             => $penj_dok_det['judul'],
                                    'tipe'              => $penj_dok_det['tipe'],
                                    'value'                   => $penj_dok_det['value'],
                                    'is_active'         => 1
                                );
                                $path_model = 'master/pasien_dokumen_detail_m';
                                $pasien_dokumen_det_id = insert_data_api($data_penj_dok_det,base_url(),$path_model);
                                $inserted_pasien_dok_det_id = $pasien_dokumen_det_id;

                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $pasien_dokumen_det_id = insert_data_api($data_penj_dok_det,$cabang->url,$path_model,$inserted_pasien_dok_det_id);
                                    }
                                }
                                $inserted_pasien_dok_det_id = str_replace('"', '', $inserted_pasien_dok_det_id);                                
                            }

                            $data_dok_history_det = array(
                                'pasien_dok_history_id' => $inserted_dok_history_id,
                                'pasien_id'             => $id_pasien,
                                'dokumen_id'            => $penj_dok['dokumen_id'],
                                'judul'                 => $penj_dok_det['judul'],
                                'tipe'                  => $penj_dok_det['tipe'],
                                'is_active'             => 1
                            );

                            $path_model = 'master/pasien_dok_history_detail_m';
                            $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,base_url(),$path_model);
                            $inserted_dok_history_det_id = $pasien_dok_history_det_id;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,$cabang->url,$path_model,$inserted_dok_history_det_id);
                                }
                            }
                            $inserted_dok_history_det_id = str_replace('"', '', $inserted_dok_history_det_id);
                        }
                    }
                                  
                    $penj_dok_detail_tipe = $array_input['penjamin_dokumen_detail_tipe'.$penj_dok['dokumen_id']];
                    if(isset($penj_dok_detail_tipe))
                    {
                        $index = 1;
                        foreach ($penj_dok_detail_tipe as $detail_tipe) 
                        {
                            if($detail_tipe['tipe'] == '9')
                            {
                                if($detail_tipe['value'] != '')
                                {
                                    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$detail_tipe['value']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$detail_tipe['value']))
                                    {   
                                        $data_path = array(
                                            'pasien_id'      => $id_pasien,
                                            'no_pasien'      => $array_input['no_member'],
                                            'dokumen_id'     => $detail_tipe['dokumen_id'],
                                            'index'          => $index,
                                            'nama_dokumen'   => str_replace(' ', '_', $detail_tipe['nama_dok']),
                                            'path_dokumen'   => './assets/mb/pages/master/pasien/images/'.$array_input['no_member'].'/dokumen',
                                            'tipe_dokumen'   => $detail_tipe['tipe_dokumen'],
                                            'path_temporary' => './assets/mb/var/temp',
                                            'temp_filename'  => $penj_dok_det['value'],
                                            'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                                        );

                                        $data_api = serialize($data_path);

                                        $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->is_active == 1)
                                            {
                                                if($cabang->url != NULL && $cabang->url != '')
                                                {
                                                   $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);                    
                                                }
                                            }
                                        }
                                        
                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        if($penj_dok['pasien_dokumen_id'] != '')
                                        {
                                            $data_detail_tipe = array(
                                                'text'  => $detail_tipe['text'],
                                                'value' => $file_detail_tipe
                                            );

                                            insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe['id']);
                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->is_active == 1)
                                                {
                                                    if($cabang->url != NULL && $cabang->url != '')
                                                    {
                                                        insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe['id']);                     
                                                    }
                                                }
                                            }                                            
                                        }
                                        elseif($penj_dok['pasien_dokumen_id'] == '')
                                        {
                                            $data_detail_tipe = array(
                                                'pasien_dokumen_detail_id' => $inserted_pasien_dok_det_id,
                                                'dokumen_detail_id'        => $detail_tipe['dokumen_detail_id'],
                                                'text'                     => $detail_tipe['text'],
                                                'value'                    => $file_detail_tipe
                                            );

                                            insert_data_api($data_detail_tipe,base_url(),$path_model);
                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->is_active == 1)
                                                {
                                                    if($cabang->url != NULL && $cabang->url != '')
                                                    {
                                                        insert_data_api($data_detail_tipe,$cabang->url,$path_model);                     
                                                    }
                                                }
                                            } 
                                        }

                                    }
                                    else
                                    {
                                        $file_detail_tipe = $detail_tipe['value'];

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        if($penj_dok['pasien_dokumen_id'] != '')
                                        {
                                            $data_detail_tipe = array(
                                                'text'  => $detail_tipe['text'],
                                                'value' => $file_detail_tipe
                                            );

                                            insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe['id']);
                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->is_active == 1)
                                                {
                                                    if($cabang->url != NULL && $cabang->url != '')
                                                    {
                                                        insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe['id']);                     
                                                    }
                                                }
                                            }                                            
                                        }
                                        elseif($penj_dok['pasien_dokumen_id'] == '')
                                        {
                                            $data_detail_tipe = array(
                                                'pasien_dokumen_detail_id' => $inserted_pasien_dok_det_id,
                                                'dokumen_detail_id'        => $detail_tipe['dokumen_detail_id'],
                                                'text'                     => $detail_tipe['text'],
                                                'value'                    => $file_detail_tipe
                                            );

                                            insert_data_api($data_detail_tipe,base_url(),$path_model);
                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->is_active == 1)
                                                {
                                                    if($cabang->url != NULL && $cabang->url != '')
                                                    {
                                                        insert_data_api($data_detail_tipe,$cabang->url,$path_model);                     
                                                    }
                                                }
                                            } 
                                        }
                                    }
                                }
                                else
                                {
                                    $file_detail_tipe = 'doc_global/document.png';

                                    $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                    if($penj_dok['pasien_dokumen_id'] != '')
                                    {
                                        $data_detail_tipe = array(
                                            'text'  => $detail_tipe['text'],
                                            'value' => $file_detail_tipe
                                        );

                                        insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe['id']);
                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->is_active == 1)
                                            {
                                                if($cabang->url != NULL && $cabang->url != '')
                                                {
                                                    insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe['id']);                     
                                                }
                                            }
                                        }                                            
                                    }
                                    elseif($penj_dok['pasien_dokumen_id'] == '')
                                    {
                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pasien_dok_det_id,
                                            'dokumen_detail_id'        => $detail_tipe['dokumen_detail_id'],
                                            'text'                     => $detail_tipe['text'],
                                            'value'                    => $file_detail_tipe
                                        );

                                        insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->is_active == 1)
                                            {
                                                if($cabang->url != NULL && $cabang->url != '')
                                                {
                                                    insert_data_api($data_detail_tipe,$cabang->url,$path_model);                     
                                                }
                                            }
                                        } 
                                    }
                                }


                                $data_dok_history_det_tipe = array(
                                    'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                    'pasien_id'                    => $id_pasien,
                                    'dokumen_detail_id'            => $detail_tipe['dokumen_detail_id'],
                                    'text'                         => $detail_tipe['text'],
                                    'value'                        => $file_detail_tipe,
                                );

                                $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                    }
                                }
                                $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                            }
                            if($detail_tipe['tipe'] == 7)
                            {
                                $value = $detail_tipe['value'];

                                foreach ($value as $val) 
                                {
                                    $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                    if($penj_dok['pasien_dokumen_id'] != '')
                                    {
                                        $data_detail_tipe = array(
                                            'text'  => $detail_tipe['text'],
                                            'value' => $val
                                        );

                                        insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe['id']);
                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->is_active == 1)
                                            {
                                                if($cabang->url != NULL && $cabang->url != '')
                                                {
                                                    insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe['id']);                     
                                                }
                                            }
                                        }                                            
                                    }
                                    elseif($penj_dok['pasien_dokumen_id'] == '')
                                    {
                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pasien_dok_det_id,
                                            'dokumen_detail_id'        => $detail_tipe['dokumen_detail_id'],
                                            'text'                     => $detail_tipe['text'],
                                            'value'                    => $val
                                        );

                                        insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->is_active == 1)
                                            {
                                                if($cabang->url != NULL && $cabang->url != '')
                                                {
                                                    insert_data_api($data_detail_tipe,$cabang->url,$path_model);                     
                                                }
                                            }
                                        } 
                                    }
                                }

                                $data_dok_history_det_tipe = array(
                                    'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                    'pasien_id'                    => $id_pasien,
                                    'dokumen_detail_id'            => $detail_tipe['dokumen_detail_id'],
                                    'text'                         => $detail_tipe['text'],
                                    'value'                        => $val,
                                );

                                $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                    }
                                }
                                $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                            }
                            elseif($detail_tipe['tipe'] != 7 && $detail_tipe['tipe'] != 9)
                            {
                                $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                if($penj_dok['pasien_dokumen_id'] != '')
                                {
                                    $data_detail_tipe = array(
                                        'text'  => $detail_tipe['text'],
                                        'value' => $detail_tipe['value']
                                    );

                                    insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe['id']);
                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->is_active == 1)
                                        {
                                            if($cabang->url != NULL && $cabang->url != '')
                                            {
                                                insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe['id']);                     
                                            }
                                        }
                                    }                                            
                                }
                                elseif($penj_dok['pasien_dokumen_id'] == '')
                                {
                                    $data_detail_tipe = array(
                                        'pasien_dokumen_detail_id' => $inserted_pasien_dok_det_id,
                                        'dokumen_detail_id'        => $detail_tipe['dokumen_detail_id'],
                                        'text'                     => $detail_tipe['text'],
                                        'value'                    => $detail_tipe['value']
                                    );

                                    insert_data_api($data_detail_tipe,base_url(),$path_model);
                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->is_active == 1)
                                        {
                                            if($cabang->url != NULL && $cabang->url != '')
                                            {
                                                insert_data_api($data_detail_tipe,$cabang->url,$path_model);                     
                                            }
                                        }
                                    } 
                                }

                                $data_dok_history_det_tipe = array(
                                    'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                    'pasien_id'                    => $id_pasien,
                                    'dokumen_detail_id'            => $detail_tipe['dokumen_detail_id'],
                                    'text'                         => $detail_tipe['text'],
                                    'value'                        => $detail_tipe['value'],
                                );

                                $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                    }
                                }
                                $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                            }


                            $index++;
                        }
                    }
                     
                }
            }


            $path_model = 'master/pasien_penyakit_m';

            $data_dok = array(
                'is_active' => 0
            );
            $wheres['pasien_id'] = $id_pasien;
            $id = update_data_api($data_dok,base_url(),$path_model,$wheres);   
            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != NULL && $cabang->url != '')
                    {
                        $id = update_data_api($data_dok,$cabang->url,$path_model,$wheres);                                                      
                    }
                }
            }

            if(isset($array_input['penyakit_bawaan']))
            {
                foreach ($array_input['penyakit_bawaan'] as $id_penyakit) 
                {
                    if($id_penyakit != '')
                    {
                        $penyakit_bawaan = $this->pasien_penyakit_m->get_by(array('pasien_id' => $id_pasien, 'penyakit_id' => $id_penyakit, 'tipe' => 1));
                        if(count($penyakit_bawaan) > 0)
                        {
                            foreach ($penyakit_bawaan as $bawaan) 
                            {
                                $data_bawaan['is_active'] = 0;

                                insert_data_api($data_bawaan,base_url(),$path_model,$bawaan->id);
                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->is_active == 1)
                                    {
                                        if($cabang->url != NULL && $cabang->url != '')
                                        {
                                            insert_data_api($data_bawaan,$cabang->url,$path_model,$bawaan->id);                     
                                        }
                                    }
                                }
                            }
                        }                        
                        else
                        {
                            $data_bawaan_baru = array(
                                'pasien_id'   => $id_pasien,
                                'penyakit_id' => $id_penyakit,
                                'tipe'        => 1,
                                'is_active'   => 1
                            );
                            insert_data_api($data_bawaan_baru,base_url(),$path_model);
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                {
                                    if($cabang->url != NULL && $cabang->url != '')
                                    {
                                        insert_data_api($data_bawaan_baru,$cabang->url,$path_model);                     
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if(isset($array_input['penyakit_penyebab']))
            {
                foreach ($array_input['penyakit_penyebab'] as $id_penyakit) 
                {
                    if($id_penyakit != '')
                    {
                        $penyakit_penyebab = $this->pasien_penyakit_m->get_by(array('pasien_id' => $id_pasien, 'penyakit_id' => $id_penyakit, 'tipe' => 2));
                        if(count($penyakit_penyebab) > 0)
                        {
                            foreach ($penyakit_penyebab as $sebab) 
                            {
                                $data_sebab['is_active'] = 0;

                                insert_data_api($data_sebab,base_url(),$path_model,$sebab->id);
                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->is_active == 1)
                                    {
                                        if($cabang->url != NULL && $cabang->url != '')
                                        {
                                            insert_data_api($data_sebab,$cabang->url,$path_model,$sebab->id);                     
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            $data_sebab_baru = array(
                                'pasien_id'   => $id_pasien,
                                'penyakit_id' => $id_penyakit,
                                'tipe'        => 2,
                                'is_active'   => 1
                            );
                            insert_data_api($data_sebab_baru,base_url(),$path_model);
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->is_active == 1)
                                {
                                    if($cabang->url != NULL && $cabang->url != '')
                                    {
                                        insert_data_api($data_sebab_baru,$cabang->url,$path_model);                     
                                    }
                                }
                            }
                        }                        
                    }
                }
            }


               
            


            


                //die_dump($save_data);

            if ($save_pasien || $save_phone || $save_alamat || $save_penyakit_bawaan || $save_penyakit_penyebab || $save_pelengkap ) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Pasien berhasil diperbaharui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

        } 

        redirect("master/pasien/edit/".$inserted_pasien_id);
    }

    public function save_penjamin_ajax()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;

            $array_input = $this->input->post();
            $data_cabang = $this->cabang_m->get_by('tipe = 1 AND is_active = 1 AND id != 1 OR tipe = 0 AND is_active = 1 AND id != 1');

            $cabang_id = $this->session->userdata('cabang_id');


            if ($array_input['command'] == "edit"){
                $id_pasien = $array_input['id'];

                foreach ($array_input['penjamin'] as $penjamin) {

                    if(isset($penjamin['penjamin_id'])){
                        $data_pasien_penjamin_active = $this->pasien_penjamin_m->get_by(array('pasien_id' => $id_pasien, 'penjamin_id' => $penjamin['penjamin_id'], 'is_active' => 1), true);

                        if(count($data_pasien_penjamin_active) == 0)
                        {
                            $penjamin_kelompok = '';
                            if($penjamin['penjamin_id'] == 2 && $cabang_id == 11){
                                $penjamin_kelompok = 1;
                            }if($penjamin['penjamin_id'] == 2 && $cabang_id == 12){
                                $penjamin_kelompok = 3;
                            }
                            $data_pasien_penjamin = array(
                                'pasien_id'            => $id_pasien,
                                'no_kartu'             => $penjamin['nomor_kartu'],
                                'penjamin_id'          => $penjamin['penjamin_id'],
                                'penjamin_kelompok_id' => $penjamin_kelompok,
                                'status'               => '1',
                                'is_active'            => 1
                            );
                            $path_model = 'master/pasien_penjamin_m';
                            $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model);
                            $inserted_pas_penj_id = $pasien_penjamin_id;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$inserted_pas_penj_id);
                                }
                            }
                            $inserted_pas_penj_id = str_replace('"', '', $inserted_pas_penj_id);

                        }else{

                            $penjamin_kelompok = '';
                            if($penjamin['penjamin_id'] == 2 && $cabang_id == 11){
                                $penjamin_kelompok = 1;
                            }if($penjamin['penjamin_id'] == 2 && $cabang_id == 12){
                                $penjamin_kelompok = 3;
                            }
                            $data_pasien_penjamin = array(
                                'pasien_id'            => $id_pasien,
                                'no_kartu'             => $penjamin['nomor_kartu'],
                                'penjamin_kelompok_id' => $penjamin_kelompok,
                            );
                            $path_model = 'master/pasien_penjamin_m';
                            $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model,$data_pasien_penjamin_active->id);
                            $inserted_pas_penj_id = $pasien_penjamin_id;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$data_pasien_penjamin_active->id);
                                }
                            }
                        }
                    }
                }

                $response->success = true;
                $response->msg = 'Penjamin pasien berhasil diubah';

                die(json_encode($response));
            }
        }
    }

    public function save_penjamin()
    {
        $array_input = $this->input->post();
        
        $data_cabang = $this->cabang_m->get_by(array('is_active' => 1));

        if ($array_input['command'] == "edit"){

            $id_pasien = $array_input['id'];

            foreach ($array_input['penjamin'] as $penjamin) {

                if(isset($penjamin['penjamin_id'])){
                    $data_pasien_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $id_pasien, 'penjamin_id' => $penjamin['penjamin_id']));

                    if(count($data_pas_penjamin) == 0)
                    {

                        $data_pasien_penjamin = array(
                            'pasien_id'            => $id_pasien,
                            'no_kartu'             => $array_input['no_kartu'],
                            'penjamin_id'          => $array_input['penjamin_id'],
                            'penjamin_kelompok_id' => $array_input['penjamin_kelompok'],
                            'status'               => '1',
                            'is_active'            => 1
                        );
                        $path_model = 'master/pasien_penjamin_m';
                        $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model);
                        $inserted_pas_penj_id = $pasien_penjamin_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$inserted_pas_penj_id);
                            }
                        }
                        $inserted_pas_penj_id = str_replace('"', '', $inserted_pas_penj_id);
                    }


                }


            }

        }
        if ($array_input['command'] == "add_penjamin") 
        {
            $id_pasien = $array_input['id_pasien'];
            $data_pasien = $this->pasien_m->get($id_pasien);
            $penjamin_dokumen = $array_input['penjamin_dokumen'];

            $data_pasien_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $id_pasien, 'penjamin_id' => $array_input['penjamin']));

            if(count($data_pas_penjamin) == 0)
            {
                $data_pasien_penjamin = array(
                    'pasien_id'            => $id_pasien,
                    'no_kartu'             => $array_input['no_kartu'],
                    'penjamin_id'          => $array_input['penjamin'],
                    'penjamin_kelompok_id' => $array_input['penjamin_kelompok'],
                    'status'               => '1',
                    'is_active'            => 1
                );
                $path_model = 'master/pasien_penjamin_m';
                $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model);
                $inserted_pas_penj_id = $pasien_penjamin_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$inserted_pas_penj_id);
                    }
                }
                $inserted_pas_penj_id = str_replace('"', '', $inserted_pas_penj_id);

                if($penjamin_dokumen)
                {  
                    foreach ($penjamin_dokumen as $penj_dok) 
                    {
                        $pasien_dokumen = $this->pasien_dokumen_m->get_by(array('pasien_id' => $id_pasien, 'dokumen_id' => $penj_dok['dokumen_id']), true);
                        if(count($pasien_dokumen) == 0)
                        {
                            $data_penj_dok = array(
                                'pasien_id'          => $id_pasien,
                                'dokumen_id'         => $penj_dok['dokumen_id'],
                                'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                                'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                                'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                                'is_active'         => 1
                            );

                            $path_model = 'master/pasien_dokumen_m';
                            $pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model);
                            $inserted_pasien_dokumen_id = $pasien_dokumen_id;
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model,$inserted_pasien_dokumen_id);
                                }
                            }

                            $inserted_pasien_dokumen_id = str_replace('"', '', $inserted_pasien_dokumen_id);
                            
                        }
                        else
                        {
                            $data_penj_dok = array(
                                'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                                'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                                'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                                'is_active'         => 1
                            );

                            $path_model = 'master/pasien_dokumen_m';
                            $pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model,$pasien_dokumen->id);
                            $inserted_pasien_dokumen_id = $pasien_dokumen_id;
                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model,$pasien_dokumen->id);
                                }
                            }
                        }

                        $data_dok_history = array(
                            'pasien_id'          => $id_pasien,
                            'dokumen_id'         => $penj_dok['dokumen_id'],
                            'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                            'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                            'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                            'is_active'          => 1
                        );

                        $path_model = 'master/pasien_dok_history_m';
                        $pasien_dok_history_id = insert_data_api($data_dok_history,base_url(),$path_model);
                        $inserted_dok_history_id = $pasien_dok_history_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok_history_id = insert_data_api($data_dok_history,$cabang->url,$path_model,$inserted_dok_history_id);
                            }
                        }
                        $inserted_dok_history_id = str_replace('"', '', $inserted_dok_history_id);

                        $penjamin_dokumen_detail = $array_input['penjamin_dokumen_detail_'.$penj_dok['dokumen_id']];
                        foreach ($penjamin_dokumen_detail as $penj_dok_det) 
                        {
                            if(count($pasien_dokumen) == 0)
                            {
                                $data_penj_dok_det = array(
                                    'pasien_dokumen_id' => $inserted_pasien_dokumen_id,
                                    'judul'             => $penj_dok_det['judul'],
                                    'tipe'              => $penj_dok_det['tipe'],
                                    'value'                   => $penj_dok_det['value'],
                                    'is_active'         => 1
                                );


                                $path_model = 'master/pasien_dokumen_detail_m';
                                $pasien_dok_det_id = insert_data_api($data_penj_dok_det,base_url(),$path_model);
                                $inserted_pas_dok_det_id = $pasien_dok_det_id;

                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $pasien_dok_det_id = insert_data_api($data_penj_dok_det,$cabang->url,$path_model,$inserted_pas_dok_det_id);
                                    }
                                }
                                $inserted_pas_dok_det_id = str_replace('"', '', $inserted_pas_dok_det_id);                            
                            }

                            $data_dok_history_det = array(
                                'pasien_dok_history_id' => $inserted_dok_history_id,
                                'pasien_id'             => $id_pasien,
                                'dokumen_id'            => $penj_dok_det['dokumen_id'],
                                'judul'                 => $penj_dok_det['judul'],
                                'tipe'                  => $penj_dok_det['tipe'],
                                'is_active'             => 1
                            );

                            $path_model = 'master/pasien_dok_history_detail_m';
                            $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,base_url(),$path_model);
                            $inserted_dok_history_det_id = $pasien_dok_history_det_id;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,$cabang->url,$path_model,$inserted_dok_history_det_id);
                                }
                            }
                            $inserted_dok_history_det_id = str_replace('"', '', $inserted_dok_history_det_id);

                            $penjamin_pasien_dokumen_tipe = $array_input['penjamin_dokumen_detail_tipe_'.$penj_dok_det['id']];
                            foreach ($penjamin_pasien_dokumen_tipe as $penj_pasien_dokumen_tipe) 
                            {
                                $detail_tipe_dokumen = $this->pasien_dokumen_m->get_by_data(array('dokumen_id' => $penj_dok_det['dokumen_id'],'pasien_id' => $id_pasien, 'dokumen_detail_id' => $penj_pasien_dokumen_tipe['dokumen_detail_id']));

                                if($penj_dok_det['tipe'] != 9 && $penj_dok_det['tipe'] != 7)
                                {
                                    if(count($detail_tipe_dokumen) == 0)
                                    {
                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                            'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                          => $penj_pasien_dokumen_tipe['text'],
                                            'value'                         => $penj_pasien_dokumen_tipe['value']
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                    
                                    }
                                    else
                                    {
                                        $data_detail_tipe = array(
                                            'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                          => $penj_pasien_dokumen_tipe['text'],
                                            'value'                         => $penj_pasien_dokumen_tipe['value']
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe_dokumen->id);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe_dokumen->id);
                                            }
                                        }
                                    }

                                    $data_dok_history_det_tipe = array(
                                        'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                        'pasien_id'                    => $id_pasien,
                                        'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                         => $penj_pasien_dokumen_tipe['text'],
                                        'value'                        => $penj_pasien_dokumen_tipe['value']
                                    );

                                    $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                    $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                    $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                        }
                                    }
                                    $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                }
                                if($penj_dok_det['tipe'] == 7)
                                {
                                    $value = $penj_pasien_dokumen_tipe['value'];

                                    foreach ($value as $val) 
                                    {
                                        if(count($detail_tipe_dokumen) == 0)
                                        {   
                                            $data_detail_tipe = array(
                                                'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                                'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                                'text'                          => $penj_pasien_dokumen_tipe['text'],
                                                'value'                         => $val
                                            );

                                            $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                            $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->url != '' || $cabang->url != NULL)
                                                {
                                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                                }
                                            }
                                            $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);
                                        }
                                        else
                                        {
                                            $data_detail_tipe = array(
                                                'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                                'text'                          => $penj_pasien_dokumen_tipe['text'],
                                                'value'                         => $val
                                            );

                                            $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe_dokumen->id);
                                            $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->url != '' || $cabang->url != NULL)
                                                {
                                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe_dokumen->id);
                                                }
                                            }
                                        }
                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $id_pasien,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $val
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                }
                                if($penj_dok_det['tipe'] == 9)
                                {
                                    if($penj_pasien_dokumen_tipe['value'] != '')
                                    {
                                        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$penj_pasien_dokumen_tipe['value']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$penj_pasien_dokumen_tipe['value'])) 
                                        {
                                            $data_path = array(
                                                'pasien_id'      => $id_pasien,
                                                'no_pasien'      => $data_pasien->no_member,
                                                'dokumen_id'     => $penj_dok['dokumen_id'],
                                                'index'          => $index,
                                                'nama_dokumen'   => str_replace(' ', '_', $penj_dok['nama']),
                                                'path_dokumen'   => './assets/mb/pages/master/pasien/images/'.$data_pasien->no_member.'/dokumen',
                                                'tipe_dokumen'   => $penj_dok['tipe_dokumen'],
                                                'path_temporary' => './assets/mb/var/temp',
                                                'temp_filename'  => $penj_pasien_dokumen_tipe['value'],
                                                'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                                            );

                                            $data_api = serialize($data_path);

                                            $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->is_active == 1)
                                                {
                                                    if($cabang->url != NULL && $cabang->url != '')
                                                    {
                                                       $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);                    
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $file_detail_tipe = $penj_pasien_dokumen_tipe['value'];
                                        }
                                        if(count($detail_tipe_dokumen) == 0)
                                        {
                                            $data_detail_tipe = array(
                                                'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                                'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                                'text'                     => $penj_pasien_dokumen_tipe['text'],
                                                'value'                    => $file_detail_tipe
                                            );

                                            $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                            $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->url != '' || $cabang->url != NULL)
                                                {
                                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                                }
                                            }
                                            $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                        
                                        }
                                        elseif(count($detail_tipe_dokumen) != 0)
                                        {
                                            $data_detail_tipe = array(
                                                'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                                'text'                     => $penj_pasien_dokumen_tipe['text'],
                                                'value'                    => $file_detail_tipe
                                            );

                                            $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe_dokumen->id);
                                            $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->url != '' || $cabang->url != NULL)
                                                {
                                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe_dokumen->id);
                                                }
                                            }
                                        }

                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $id_pasien,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                    elseif($penj_pasien_dokumen_tipe['value'] == '')
                                    {
                                        $file_detail_tipe = 'doc_global/document.png';

                                        if(count($detail_tipe_dokumen) == 0)
                                        {
                                            $data_detail_tipe = array(
                                                'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                                'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                                'text'                     => $penj_pasien_dokumen_tipe['text'],
                                                'value'                    => $file_detail_tipe
                                            );

                                            $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                            $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->url != '' || $cabang->url != NULL)
                                                {
                                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                                }
                                            }
                                            $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                        
                                        }
                                        elseif(count($detail_tipe_dokumen) != 0)
                                        {
                                            $data_detail_tipe = array(
                                                'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                                'text'                     => $penj_pasien_dokumen_tipe['text'],
                                                'value'                    => $file_detail_tipe
                                            );

                                            $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe_dokumen->id);
                                            $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                            foreach ($data_cabang as $cabang) 
                                            {
                                                if($cabang->url != '' || $cabang->url != NULL)
                                                {
                                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe_dokumen->id);
                                                }
                                            }
                                        }

                                        $data_dok_history_det_tipe = array(
                                            'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                            'pasien_id'                    => $id_pasien,
                                            'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                         => $penj_pasien_dokumen_tipe['text'],
                                            'value'                        => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                        $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                            }
                                        }
                                        $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                    }
                                }
                            }
                        }  
                    } 
                }          

            }

            if ($pasien_penjamin_id ) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Pasien Penjamin Berhasil Ditambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }    

        } 

        elseif ($array_input['command'] == "edit_penjamin") 
        {
            // die_dump($array_input);
            $id_pasien = $array_input['id_pasien'];
            $id_pasien_penjamin = $array_input['id_pasien_penjamin'];
            $penjamin_dokumen = $array_input['penjamin_dokumen'];

            $pasien_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $id_pasien));
            $data_pasien = $this->pasien_m->get($id_pasien);
            
            $data_pasien_penjamin = array(
                'penjamin_kelompok_id' => $array_input['penjamin_kelompok'],
                'no_kartu'             => $array_input['no_kartu'],
                'status'               => '1',
                'is_active'            => '1'
            );

            $path_model = 'master/pasien_penjamin_m';
            $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model,$id_pasien_penjamin);

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$id_pasien_penjamin);
                }
            }

            if($penjamin_dokumen)
            {
                foreach ($penjamin_dokumen as $penj_dok) 
                {
                    $pasien_dokumen = $this->pasien_dokumen_m->get_by(array('pasien_id' => $id_pasien, 'dokumen_id' => $penj_dok['dokumen_id']), true);
                    if(count($pasien_dokumen) == 0)
                    {
                        $data_penj_dok = array(
                            'pasien_id'          => $id_pasien,
                            'dokumen_id'         => $penj_dok['dokumen_id'],
                            'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                            'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                            'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                            'is_active'         => 1
                        );

                        $path_model = 'master/pasien_dokumen_m';
                        $pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model);
                        $inserted_pasien_dokumen_id = $pasien_dokumen_id;
                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model,$inserted_pasien_dokumen_id);
                            }
                        }

                        $inserted_pasien_dokumen_id = str_replace('"', '', $inserted_pasien_dokumen_id);
                        
                    }
                    else
                    {
                        $data_penj_dok = array(
                            'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                            'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                            'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                            'is_active'         => 1
                        );

                        $path_model = 'master/pasien_dokumen_m';
                        $pasien_dokumen_id = insert_data_api($data_penj_dok,base_url(),$path_model,$pasien_dokumen->id);
                        $inserted_pasien_dokumen_id = $pasien_dokumen_id;
                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dokumen_id = insert_data_api($data_penj_dok,$cabang->url,$path_model,$pasien_dokumen->id);
                            }
                        }
                    }

                    $data_dok_history = array(
                        'pasien_id'          => $id_pasien,
                        'dokumen_id'         => $penj_dok['dokumen_id'],
                        'is_kadaluarsa'      => ($penj_dok['is_kadaluarsa']==1)?$penj_dok['is_kadaluarsa']:0,
                        'tanggal_kadaluarsa' => ($penj_dok['is_kadaluarsa']==1)?date('Y-m-d', strtotime($penj_dok['tanggal_kadaluarsa'])):NULL,
                        'is_required'        => ($penj_dok['is_required']==1)?$penj_dok['is_required']:0,
                        'is_active'          => 1
                    );

                    $path_model = 'master/pasien_dok_history_m';
                    $pasien_dok_history_id = insert_data_api($data_dok_history,base_url(),$path_model);
                    $inserted_dok_history_id = $pasien_dok_history_id;

                    foreach ($data_cabang as $cabang) 
                    {
                        if($cabang->url != '' || $cabang->url != NULL)
                        {
                            $pasien_dok_history_id = insert_data_api($data_dok_history,$cabang->url,$path_model,$inserted_dok_history_id);
                        }
                    }
                    $inserted_dok_history_id = str_replace('"', '', $inserted_dok_history_id);

                    $penjamin_dokumen_detail = $array_input['penjamin_dokumen_detail_'.$penj_dok['dokumen_id']];
                    foreach ($penjamin_dokumen_detail as $penj_dok_det) 
                    {
                        if(count($pasien_dokumen) == 0)
                        {
                            $data_penj_dok_det = array(
                                'pasien_dokumen_id' => $inserted_pasien_dokumen_id,
                                'judul'             => $penj_dok_det['judul'],
                                'tipe'              => $penj_dok_det['tipe'],
                                'value'                   => $penj_dok_det['value'],
                                'is_active'         => 1
                            );


                            $path_model = 'master/pasien_dokumen_detail_m';
                            $pasien_dok_det_id = insert_data_api($data_penj_dok_det,base_url(),$path_model);
                            $inserted_pas_dok_det_id = $pasien_dok_det_id;

                            foreach ($data_cabang as $cabang) 
                            {
                                if($cabang->url != '' || $cabang->url != NULL)
                                {
                                    $pasien_dok_det_id = insert_data_api($data_penj_dok_det,$cabang->url,$path_model,$inserted_pas_dok_det_id);
                                }
                            }
                            $inserted_pas_dok_det_id = str_replace('"', '', $inserted_pas_dok_det_id);                            
                        }

                        $data_dok_history_det = array(
                            'pasien_dok_history_id' => $inserted_dok_history_id,
                            'pasien_id'             => $id_pasien,
                            'dokumen_id'            => $penj_dok_det['dokumen_id'],
                            'judul'                 => $penj_dok_det['judul'],
                            'tipe'                  => $penj_dok_det['tipe'],
                            'is_active'             => 1
                        );

                        $path_model = 'master/pasien_dok_history_detail_m';
                        $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,base_url(),$path_model);
                        $inserted_dok_history_det_id = $pasien_dok_history_det_id;

                        foreach ($data_cabang as $cabang) 
                        {
                            if($cabang->url != '' || $cabang->url != NULL)
                            {
                                $pasien_dok_history_det_id = insert_data_api($data_dok_history_det,$cabang->url,$path_model,$inserted_dok_history_det_id);
                            }
                        }
                        $inserted_dok_history_det_id = str_replace('"', '', $inserted_dok_history_det_id);

                        $penjamin_pasien_dokumen_tipe = $array_input['penjamin_dokumen_detail_tipe_'.$penj_dok_det['id']];
                        foreach ($penjamin_pasien_dokumen_tipe as $penj_pasien_dokumen_tipe) 
                        {
                            $detail_tipe_dokumen = $this->pasien_dokumen_m->get_by_data(array('dokumen_id' => $penj_dok_det['dokumen_id'],'pasien_id' => $id_pasien, 'dokumen_detail_id' => $penj_pasien_dokumen_tipe['dokumen_detail_id']));

                            if($penj_dok_det['tipe'] != 9 && $penj_dok_det['tipe'] != 7)
                            {
                                if(count($detail_tipe_dokumen) == 0)
                                {
                                    $data_detail_tipe = array(
                                        'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                        'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                          => $penj_pasien_dokumen_tipe['text'],
                                        'value'                         => $penj_pasien_dokumen_tipe['value']
                                    );

                                    $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                    $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                        }
                                    }
                                    $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                    
                                }
                                else
                                {
                                    $data_detail_tipe = array(
                                        'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                          => $penj_pasien_dokumen_tipe['text'],
                                        'value'                         => $penj_pasien_dokumen_tipe['value']
                                    );

                                    $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                    $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe_dokumen->id);
                                    $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe_dokumen->id);
                                        }
                                    }
                                }

                                $data_dok_history_det_tipe = array(
                                    'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                    'pasien_id'                    => $id_pasien,
                                    'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                    'text'                         => $penj_pasien_dokumen_tipe['text'],
                                    'value'                        => $penj_pasien_dokumen_tipe['value']
                                );

                                $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                foreach ($data_cabang as $cabang) 
                                {
                                    if($cabang->url != '' || $cabang->url != NULL)
                                    {
                                        $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                    }
                                }
                                $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                            }
                            if($penj_dok_det['tipe'] == 7)
                            {
                                $value = $penj_pasien_dokumen_tipe['value'];

                                foreach ($value as $val) 
                                {
                                    if(count($detail_tipe_dokumen) == 0)
                                    {   
                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                            'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                          => $penj_pasien_dokumen_tipe['text'],
                                            'value'                         => $val
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);
                                    }
                                    else
                                    {
                                        $data_detail_tipe = array(
                                            'dokumen_detail_id'             => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                          => $penj_pasien_dokumen_tipe['text'],
                                            'value'                         => $val
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe_dokumen->id);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe_dokumen->id);
                                            }
                                        }
                                    }
                                    $data_dok_history_det_tipe = array(
                                        'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                        'pasien_id'                    => $id_pasien,
                                        'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                         => $penj_pasien_dokumen_tipe['text'],
                                        'value'                        => $val
                                    );

                                    $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                    $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                    $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                        }
                                    }
                                    $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                }
                            }
                            if($penj_dok_det['tipe'] == 9)
                            {
                                if($penj_pasien_dokumen_tipe['value'] != '')
                                {
                                    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$penj_pasien_dokumen_tipe['value']) && is_file($_SERVER['DOCUMENT_ROOT'].'/cloud/temp/'.$penj_pasien_dokumen_tipe['value'])) 
                                    {
                                        $data_path = array(
                                            'pasien_id'      => $id_pasien,
                                            'no_pasien'      => $data_pasien->no_member,
                                            'dokumen_id'     => $penj_dok['dokumen_id'],
                                            'index'          => $index,
                                            'nama_dokumen'   => str_replace(' ', '_', $penj_dok['nama']),
                                            'path_dokumen'   => './assets/mb/pages/master/pasien/images/'.$data_pasien->no_member.'/dokumen',
                                            'tipe_dokumen'   => $penj_dok['tipe_dokumen'],
                                            'path_temporary' => './assets/mb/var/temp',
                                            'temp_filename'  => $penj_pasien_dokumen_tipe['value'],
                                            'path_temp'      => config_item('base_dir').config_item('user_img_temp_dir')
                                        );

                                        $data_api = serialize($data_path);

                                        $file_detail_tipe = move_pasien_penj_dok(base_url(),$data_api);
                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->is_active == 1)
                                            {
                                                if($cabang->url != NULL && $cabang->url != '')
                                                {
                                                   $file_detail_tipe = move_pasien_penj_dok($cabang->url,$data_api);                    
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $file_detail_tipe = $penj_pasien_dokumen_tipe['value'];
                                    }
                                    if(count($detail_tipe_dokumen) == 0)
                                    {
                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                            'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                     => $penj_pasien_dokumen_tipe['text'],
                                            'value'                    => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                        
                                    }
                                    elseif(count($detail_tipe_dokumen) != 0)
                                    {
                                        $data_detail_tipe = array(
                                            'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                     => $penj_pasien_dokumen_tipe['text'],
                                            'value'                    => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe_dokumen->id);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe_dokumen->id);
                                            }
                                        }
                                    }

                                    $data_dok_history_det_tipe = array(
                                        'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                        'pasien_id'                    => $id_pasien,
                                        'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                         => $penj_pasien_dokumen_tipe['text'],
                                        'value'                        => $file_detail_tipe
                                    );

                                    $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                    $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                    $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                        }
                                    }
                                    $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                }
                                elseif($penj_pasien_dokumen_tipe['value'] == '')
                                {
                                    $file_detail_tipe = 'doc_global/document.png';

                                    if(count($detail_tipe_dokumen) == 0)
                                    {
                                        $data_detail_tipe = array(
                                            'pasien_dokumen_detail_id' => $inserted_pas_dok_det_id,
                                            'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                     => $penj_pasien_dokumen_tipe['text'],
                                            'value'                    => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$inserted_penj_dok_det_tipe_id);
                                            }
                                        }
                                        $inserted_penj_dok_det_tipe_id = str_replace('"', '', $inserted_penj_dok_det_tipe_id);                                        
                                    }
                                    elseif(count($detail_tipe_dokumen) != 0)
                                    {
                                        $data_detail_tipe = array(
                                            'dokumen_detail_id'        => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                            'text'                     => $penj_pasien_dokumen_tipe['text'],
                                            'value'                    => $file_detail_tipe
                                        );

                                        $path_model = 'master/pasien_dokumen_detail_tipe_m';
                                        $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,base_url(),$path_model,$detail_tipe_dokumen->id);
                                        $inserted_penj_dok_det_tipe_id = $pasien_penj_dok_det_tipe_id;

                                        foreach ($data_cabang as $cabang) 
                                        {
                                            if($cabang->url != '' || $cabang->url != NULL)
                                            {
                                                $pasien_penj_dok_det_tipe_id = insert_data_api($data_detail_tipe,$cabang->url,$path_model,$detail_tipe_dokumen->id);
                                            }
                                        }
                                    }

                                    $data_dok_history_det_tipe = array(
                                        'pasien_dok_history_detail_id' => $inserted_dok_history_det_id,
                                        'pasien_id'                    => $id_pasien,
                                        'dokumen_detail_id'            => $penj_pasien_dokumen_tipe['dokumen_detail_id'],
                                        'text'                         => $penj_pasien_dokumen_tipe['text'],
                                        'value'                        => $file_detail_tipe
                                    );

                                    $path_model = 'master/pasien_dok_history_detail_tipe_m';
                                    $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,base_url(),$path_model);
                                    $inserted_dok_history_det_tipe_id = $pasien_dok_history_det_tipe_id;

                                    foreach ($data_cabang as $cabang) 
                                    {
                                        if($cabang->url != '' || $cabang->url != NULL)
                                        {
                                            $pasien_dok_history_det_tipe_id = insert_data_api($data_dok_history_det_tipe,$cabang->url,$path_model,$inserted_dok_history_det_tipe_id);
                                        }
                                    }
                                    $inserted_dok_history_det_tipe_id = str_replace('"', '', $inserted_dok_history_det_tipe_id);
                                }
                            }
                        }
                    }  
                } 
            }

            
                              
        }
        

        if ($save_pasien_penjamin) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Pasien Penjamin Berhasil Diubah", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/pasien/penjamin/$id_pasien");
       
    }
    public function delete($id)
    {
        $data_cabang = $this->cabang_m->get_by('tipe in (0,1)');
        $data = array(
            'is_active'    => 0
        );
        // save data
        //$pasien_id = $this->pasien_m->save($data, $id);
        $path_model = 'master/pasien_m';
        foreach ($data_cabang as $cabang) 
        {
            if($cabang->is_active == 1)
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $pasien_dok_history_det_tipe_id = insert_data_api($data,$cabang->url,$path_model,$id);
                }
            }
        }

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->max_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->max_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 4,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($pasien_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate(" Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/pasien");
    }

    public function delete_syarat_penjamin($id, $id_pasien)
    {
        $data_cabang = $this->cabang_m->get_by('tipe in (0,1)');
        $data = array(
            'is_active'    => 0
        );
        // save data
        //$pasien_id = $this->pasien_m->save($data, $id);
        $path_model = 'master/pasien_penjamin_m';
        foreach ($data_cabang as $cabang) 
        {
            if($cabang->is_active == 1)
            {
                if($cabang->url != '' || $cabang->url != NULL)
                {
                    $pasien_dok_history_det_tipe_id = insert_data_api($data,$cabang->url,$path_model,$id);
                }
            }
        }
        // save data
        //$pasien_penjamin_id = $this->pasien_penjamin_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->max_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->max_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 4,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($pasien_syarat_penjamin_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate(" Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/pasien/penjamin/$id_pasien");
    }

    public function get_negara(){

        //$id_negara = $this->input->post('id_negara');
        //die_dump($id_negara);
        //$this->region_m->set_columns(array('id','nama'));
        $negara       = $this->region_m->get_by(array('parent' => Null));
        //die_dump($this->db->last_query());        
        $hasil_negara = object_to_array($negara);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_negara);
    }

    public function get_provinsi(){

        $id_negara      = $this->input->post('id_negara');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $provinsi       = $this->region_m->get_data_region($id_negara)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_provinsi = object_to_array($provinsi);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_provinsi);
    }

    public function get_kota(){

        $id_provinsi = $this->input->post('id_provinsi');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kota        = $this->region_m->get_data_region($id_provinsi)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kota  = object_to_array($kota);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kota);
    }

    public function get_kecamatan(){

        $id_kota         = $this->input->post('id_kota');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kecamatan       = $this->region_m->get_data_region($id_kota)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kecamatan = object_to_array($kecamatan);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kecamatan);
    }

    public function get_kelurahan(){

        $id_kecamatan    = $this->input->post('id_kecamatan');
        //die_dump($id_negara);
        $this->region_m->set_columns(array('id','nama'));
        $kelurahan       = $this->region_m->get_data_region($id_kecamatan)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_kelurahan = object_to_array($kelurahan);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_kelurahan);
    }

    public function get_kode_cabang(){

        $id_negara    = $this->input->post('id_cabang');
        //die_dump($id_negara);
        $this->cabang_m->set_columns(array('id','kode'));
        $cabang       = $this->cabang_m->get_by(array('id' => $id_negara));
        //die_dump($this->db->last_query());        
        $hasil_cabang = object_to_array($cabang);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_cabang);
    }

    public function get_data_faskes(){

        $id    = $this->input->post('id');
        
        $fakses       = $this->faskes_m->get_data_faskes($id);
        
        $hasil_faskes = $fakses->result_array();

        // die_dump($hasil_faskes);
        echo json_encode($hasil_faskes);
    }

    public function get_subjek(){

        $tipe              = $this->input->post('tipe');
        $data_subjek       = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek = object_to_array($data_subjek);

        echo json_encode($hasil_data_subjek);

    }

    public function save_subjek(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_subjek = array(
            "tipe" => $tipe,
            "nama" => $nama,
        );

        
        $save_data         = $this->subjek_m->save($data_subjek);
        
        $data_subjek       = $this->subjek_m->get_by(array('tipe' => $tipe));
        $hasil_data_subjek = object_to_array($data_subjek);
        
        echo json_encode($hasil_data_subjek);

    }

    public function save_negara(){

        $tipe = $this->input->post('tipe');
        $nama = $this->input->post('nama');

        $data_negara = array(
            "tipe" => $tipe,
            "nama" => $nama,
        );

        $save_data         = $this->region_m->save($data_negara);
        
        $data_negara       = $this->region_m->get_by(array('parent' => null));
        $hasil_data_negara = object_to_array($data_negara);

        echo json_encode($hasil_data_negara);

    }

    public function save_region(){

        $parent = $this->input->post('parent');
        $tipe   = $this->input->post('tipe');
        $nama   = $this->input->post('nama');

        $data_region = array(
            "parent" => $parent,
            "tipe"   => $tipe,
            "nama"   => $nama,
        );

        
        $save_data         = $this->region_m->save($data_region);
        
        $data_region       = $this->region_m->get_by(array('parent' => $parent));
        $hasil_data_region = object_to_array($data_region);

        echo json_encode($hasil_data_region);

    }

    public function show_penjamin_kelompok()
    {
        $id_penjamin = $this->input->post('id_penjamin');

        $penjamin_kelompok = $this->penjamin_m->get_by(array('id' => $id_penjamin));
        
        $data_penjamin_kelompok = object_to_array($penjamin_kelompok);

        
        $show_penjamin_kelompok = "";
        foreach ($data_penjamin_kelompok as $data) {
                
                if ($data['is_parent'] == 1) 
                {
                    $kelompok_detail = $this->penjamin_kelompok_m->get_by(array('penjamin_id' => $data['id']));
                    $data_kelompok_detail = object_to_array($kelompok_detail);
                    $kelompok_detail_option = array(
                        '' => translate('Pilih..', $this->session->userdata('language'))
                    );

                    foreach ($data_kelompok_detail as $data_kelompok)
                    {
                        $kelompok_detail_option[$data_kelompok['id']] = $data_kelompok['nama'];
                    }

                    $show_penjamin_kelompok .= ' '.form_dropdown('penjamin_kelompok', $kelompok_detail_option, "", "id=\"penjamin_kelompok\" class=\"form-control\" required=\"required\" ").'';
                }
                else{
                    $kelompok_detail_option = array(
                        '' => translate('Pilih..', $this->session->userdata('language'))
                    );
                    $show_penjamin_kelompok .= ' '.form_dropdown('penjamin_kelompok', $kelompok_detail_option, "", "id=\"penjamin_kelompok\" class=\"form-control hidden\"").'';
                }
                
        }
        
        echo $show_penjamin_kelompok;
    }
    public function show_edit_claim_kelompok()
    {
        $id_penjamin = $this->input->post('id_penjamin');
        $penjamin_kelompok_id = $this->input->post('penjamin_kelompok_id');

        $penjamin_kelompok = $this->penjamin_m->get_by(array('id' => $id_penjamin));
        
        $data_penjamin_kelompok = object_to_array($penjamin_kelompok);

        
        $show_penjamin_kelompok = "";
        foreach ($data_penjamin_kelompok as $data) {
                
                if ($data['is_parent'] == 1) 
                {
                    $kelompok_detail = $this->penjamin_kelompok_m->get_by(array('penjamin_id' => $data['id']));
                    $data_kelompok_detail = object_to_array($kelompok_detail);
                    $kelompok_detail_option = array(
                        '' => translate('Pilih..', $this->session->userdata('language'))
                    );

                    foreach ($data_kelompok_detail as $data_kelompok)
                    {
                        $kelompok_detail_option[$data_kelompok['id']] = $data_kelompok['nama'];
                    }

                    $show_penjamin_kelompok .= ' '.form_dropdown('penjamin_kelompok', $kelompok_detail_option, $penjamin_kelompok_id, "id=\"penjamin_kelompok\" class=\"form-control\"").'';
                }
                else{
                    $kelompok_detail_option = array(
                        '' => translate('Pilih..', $this->session->userdata('language'))
                    );
                    $show_penjamin_kelompok .= ' '.form_dropdown('penjamin_kelompok', $kelompok_detail_option, "", "id=\"penjamin_kelompok\" class=\"form-control hidden\"").'';
                }
                
        }
        
        echo $show_penjamin_kelompok;
    }

    public function show_dokumen_detail(){
        if($this->input->is_ajax_request())
        {
            $this->load->model('master/dokumen_m');
            $this->load->model('master/dokumen_detail_m');
            $this->load->model('master/dokumen_detail_tipe_m');

            $dokumen_id = $this->input->post('dok_id');
            $data_dokumen = $this->dokumen_m->get($dokumen_id);
            $data_dokumen_detail = $this->dokumen_detail_m->get_by(array('dokumen_id' =>$dokumen_id));
            $data_dokumen_detail = object_to_array($data_dokumen_detail);


            $show_penjamin = '';
            if($data_dokumen->is_kadaluarsa == 1)
            {
                $expire = '<div class="form-group">
                        <label class="control-label col-md-4">Tanggal Kadaluarsa :';

                if($data_dokumen->is_required == 1)
                {
                    $expire .= '<span class="required" aria-required="true">*</span>';  
                }
                $expire .= '</label>
                            <div class="col-md-8">
                            <div class="input-group date" id="penjamin_dokumen[1][tanggal_kadaluarsa]">
                                <input type="text" class="form-control" id="penjamin_dokumen[1][tanggal_kadaluarsa]" name="penjamin_dokumen[1][tanggal_kadaluarsa]" value=""'; 
                if($data_dokumen->is_required == 1)
                {
                    $expire .= ' required="required" '; 
                }
                $expire .='readonly="" aria-required="true">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>';                 
            }
            else
            {
                $expire = '<input type="hidden" class="form-control" id="penjamin_dokumen[1][tanggal_kadaluarsa]" name="penjamin_dokumen[1][tanggal_kadaluarsa]">';
            }
            $expire .= '<input type="hidden" id="penjamin_dokumen[1][dokumen_id]" name="penjamin_dokumen[1][dokumen_id]" value="'.$data_dokumen->id.'"><input type="hidden" id="penjamin_dokumen[1][is_kadaluarsa]" name="penjamin_dokumen[1][is_kadaluarsa]" value="'.$data_dokumen->is_kadaluarsa.'"><input type="hidden" id="penjamin_dokumen[1][is_required]" name="penjamin_dokumen[1][is_required]" value="'.$data_dokumen->is_required.'"><input type="hidden" id="penjamin_dokumen[1][nama]" name="penjamin_dokumen[1][nama]" value="'.$data_dokumen->nama.'"><input type="hidden" id="penjamin_dokumen[1][tipe_dokumen]" name="penjamin_dokumen[1][tipe_dokumen]" value="'.$data_dokumen->tipe.'">';

            $show_penjamin .= $expire;
            if(count($data_dokumen_detail))
            {
                $detail = '';
                $i = 1;
                $ii = 1;
                $z = 0;
                foreach ($data_dokumen_detail as $data_detail) 
                {
                    $detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][dokumen_id]" value="'.$data_dokumen->id.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][nama_dok]" value="'.$data_dokumen->nama.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'">';

                    if ($data_detail['tipe'] == 1)
                    {
                        $required = '';
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="'.$data_detail['maksimal_karakter'].'" value="">
                                                
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 2)
                    {
                        $required = '';
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';

                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .= '</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="'.$data_detail['maksimal_karakter'].'" value=""></textarea>

                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 3) 
                    {
                        $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .='</label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="'.$data_detail['maksimal_karakter'].'" value="">
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 4) 
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );

                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                        }

                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]', $detail_tipe_option,'', 'class="form-control" '.$required.' id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]"').'
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 5)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .= '</label>
                                    <div class="col-md-8"><div class="radio-list">';

                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" '.$required.' id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div>
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 6)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8"><div class="checkbox-list">';
                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" '.$required.' id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div>     
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 7) 
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );

                        $selected = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                        }

                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" multiple="multiple" '.$required.' '.$selected.' ')
                                        .'        
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 8) 
                    {
                        $date = '';
                        
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">
                                    <div class="input-group date" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]">
                                        <input type="text" class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" required="required" readonly="" aria-required="true" '.$required.' value="'.$date.'">
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                        
 
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 9) 
                    {
                        
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                  <div class="col-md-8">
                                    <div id="upload_dokumen_'.$z.'">
                                        <input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" value="" '.$required.' />
                                        <span class="btn default btn-file">
                                        <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>    
                                            <input type="file" name="upl" id="upload_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
                                        </span>
                                        <ul class="ul-img">
                                            
                                        </ul>
                                    </div>
                                        
                                  </div>';
                        $z++;
                    }
                    
                    $show_penjamin .= '<div class="form-group">'.$detail.'</div><div class="form-group">'.$input.'</div>';
                    $i++;
                    $ii++;
                }
                $show_penjamin .= '</div></div>';
    
            }

            echo $show_penjamin;

        }
    }

    public function show_dokumen_detail_edit()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->model('master/dokumen_m');
            $this->load->model('master/pasien_dokumen_m');
            $this->load->model('master/dokumen_detail_m');
            $this->load->model('master/dokumen_detail_tipe_m');

            $dokumen_id = $this->input->post('dok_id');
            $pasien_id = $this->input->post('pasien_id');
            $pasien_dokumen_id = $this->input->post('pasien_dok_id');
            $data_dokumen = $this->dokumen_m->get($dokumen_id);
            $data_pasien = $this->pasien_m->get($pasien_id);
            $data_pasien_dokumen = $this->pasien_dokumen_m->get($pasien_dokumen_id);
            $data_dokumen_detail = $this->dokumen_detail_m->get_by(array('dokumen_id' =>$dokumen_id));
            $data_dokumen_detail = object_to_array($data_dokumen_detail);

            $tanggal_kadaluarsa = '';

            if(count($data_pasien_dokumen))
            {
                $tanggal_kadaluarsa = date('d-M-Y', strtotime($data_pasien_dokumen->tanggal_kadaluarsa));
            }
            $show_penjamin = '';
            if($data_dokumen->is_kadaluarsa == 1)
            {
                $expire = '<div class="form-group">
                        <label class="control-label col-md-4">Tanggal Kadaluarsa :';

                if($data_dokumen->is_required == 1)
                {
                    $expire .= '<span class="required" aria-required="true">*</span>';  
                }
                $expire .= '</label>
                            <div class="col-md-8">
                            <div class="input-group date" id="penjamin_dokumen[1][tanggal_kadaluarsa]">
                                <input type="text" class="form-control" id="penjamin_dokumen[1][tanggal_kadaluarsa]" name="penjamin_dokumen[1][tanggal_kadaluarsa]" value="'.$tanggal_kadaluarsa.'"'; 
                if($data_dokumen->is_required == 1)
                {
                    $expire .= ' required="required" '; 
                }
                $expire .='readonly="" aria-required="true">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>';                 
            }
            else
            {
                $expire = '<input type="hidden" class="form-control" id="penjamin_dokumen[1][tanggal_kadaluarsa]" name="penjamin_dokumen[1][tanggal_kadaluarsa]">';
            }
            $expire .= '<input type="hidden" id="penjamin_dokumen[1][pasien_dokumen_id]" name="penjamin_dokumen[1][pasien_dokumen_id]" value="'.$pasien_dokumen_id.'"><input type="hidden" id="penjamin_dokumen[1][dokumen_id]" name="penjamin_dokumen[1][dokumen_id]" value="'.$data_dokumen->id.'"><input type="hidden" id="penjamin_dokumen[1][is_kadaluarsa]" name="penjamin_dokumen[1][is_kadaluarsa]" value="'.$data_dokumen->is_kadaluarsa.'"><input type="hidden" id="penjamin_dokumen[1][is_required]" name="penjamin_dokumen[1][is_required]" value="'.$data_dokumen->is_required.'"><input type="hidden" id="penjamin_dokumen[1][nama]" name="penjamin_dokumen[1][nama]" value="'.$data_dokumen->nama.'"><input type="hidden" id="penjamin_dokumen[1][tipe_dokumen]" name="penjamin_dokumen[1][tipe_dokumen]" value="'.$data_dokumen->tipe.'">';

            $show_penjamin .= $expire;
            if(count($data_dokumen_detail))
            {
                $detail = '';
                $i = 1;
                $ii = 1;
                $z = 0;
                foreach ($data_dokumen_detail as $data_detail) 
                {
                    $value = '';
                    $detail_tipe_id = '';
                    $detail_tipe_dokumen = $this->pasien_dokumen_m->get_by_data(array('dokumen_id' => $dokumen_id,'pasien_id' => $pasien_id, 'tipe' => $data_detail['tipe']));
                   // die_dump($this->db->last_query());
                    
                    if(count($detail_tipe_dokumen))
                    {
                        $detail_tipe_id = $detail_tipe_dokumen->id;
                        $value = $detail_tipe_dokumen->value;
                    }

                    $detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][pasien_dok_det_id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][pasien_dok_det_id]" value="'.$detail_tipe_id.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][dokumen_id]" value="'.$data_dokumen->id.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][nama_dok]" value="'.$data_dokumen->nama.'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'">';

                    if ($data_detail['tipe'] == 1)
                    {
                        $required = '';
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 2)
                    {
                        $required = '';
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';

                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .= '</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'"></textarea>

                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 3) 
                    {
                        $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .='</label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 4) 
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );

                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                        }

                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]', $detail_tipe_option,$value, 'class="form-control" '.$required.' id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]"')
                                        .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 5)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }

                        $input .= '</label>
                                    <div class="col-md-8"><div class="radio-list">';

                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            if($detail_tipe->value == $value)
                            {
                                $checked = 'checked="checked"';
                            }
                            $input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" '.$required.' id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 6)
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );


                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8"><div class="checkbox-list">';
                        $checked = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            if($detail_tipe->value == $value)
                            {
                                $checked = 'checked="checked"';
                            }
                            $input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" '.$required.' id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                        }
                         $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 7) 
                    {
                        $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                        $detail_tipe_option = array(
                            '' => translate('Pilih..', $this->session->userdata('language'))
                        );

                        $selected = '';
                        foreach ($detail_tipe as $detail_tipe)
                        {   
                            if($detail_tipe->value == $value)
                            {
                                $selected = 'selected="selected"';
                            }
                            $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                        }

                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">'.
                                        form_dropdown('penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" multiple="multiple" '.$required.' '.$selected.' ')
                                        .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 8) 
                    {
                        $date = '';
                        if($value != '')
                        {
                            $date = date('d-M-Y', strtotime($value));
                        }
                        
                        $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $input .= '</label>
                                    <div class="col-md-8">
                                    <div class="input-group date" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]">
                                        <input type="text" class="form-control" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" required="required" readonly="" aria-required="true" '.$required.' value="'.$date.'">
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                        
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">         
                                    </div>';
                    }
                    elseif ($data_detail['tipe'] == 9) 
                    {
                        
                        $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                        $required = '';
                        if($data_dokumen->is_required == 1)
                        {
                            $input .= '<span class="required" aria-required="true">*</span>';
                            $required = 'required="required"';  
                        }
                        $src = '';
                        $image = '';
                        if($value != '')
                        {
                            if($value != 'doc_global/document.png')
                            {
                                $value = $value;
                                $tipe  = ($data_dokumen->tipe == 1)?'pelengkap':'rekam_medis';
                                $src   = $data_pasien->no_member.'/dokumen/'.$tipe.'/'.$value;
                            }

                            $image = '<li class="working">
                                        <div class="thumbnail">
                                            <a class="fancybox-button" title="'.$value.'" href="'.config_item('base_dir').config_item('site_img_pasien').$src.'" data-rel="fancybox-button">
                                                <img src="'.config_item('base_dir').config_item('site_img_pasien').$src.'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                            </a>
                                        </div>
                                    </li>';
                        }

                        $input .= '</label>
                                  <div class="col-md-8">
                                    <div id="upload_dokumen_'.$z.'">
                                        <input type="hidden" id="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" name="penjamin_dokumen_detail_'.$data_dokumen->id.'['.$ii.'][value]" value="'.$value.'" '.$required.' />
                                        <span class="btn default btn-file">
                                        <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>    
                                            <input type="file" name="upl" id="upload_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
                                        </span>
                                        <ul class="ul-img">
                                            '.$image.'
                                        </ul>
                                    </div>
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                        <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'"><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][pas_dok_det_id]" value="'.$detail_tipe_id.'">
                                  </div>';
                        $z++;
                    }
                    
                    $show_penjamin .= '<div class="form-group">'.$detail.'</div><div class="form-group">'.$input.'</div>';
                    $i++;
                    $ii++;
                }
                $show_penjamin .= '</div></div>';
    
            }

            echo $show_penjamin;

        }
    }







    public function show_penjamin()
    {
        $id_penjamin = $this->input->post('id_penjamin');
        $id_pasien = $this->input->post('id_pasien');

        $penjamin_dokumen = $this->penjamin_dokumen_m->get_data_penjamin_dokumen($id_penjamin)->result_array();

        $data_penjamin_dokumen = object_to_array($penjamin_dokumen);
        $data_pasien = $this->pasien_m->get($id_pasien);

        $show_penjamin = '';
        $y = 1;
        $z = 0;
        $radio = '';
        foreach ($data_penjamin_dokumen as $data) 
        {

            $dokumen_detail = $this->dokumen_detail_m->get_by(array('dokumen_id' => $data['dokumen_id']));
            $dokumen_value = $this->pasien_dokumen_m->get_by(array('pasien_id' => $id_pasien,'dokumen_id' => $data['dokumen_id'], 'is_active' => 1), true);
            $tanggal_kadaluarsa = '';
            if(count($dokumen_value) != 0)
            {    
                $dokumen = $this->dokumen_m->get($data['dokumen_id']);

                if($data['is_kadaluarsa'] == 1)  
                {
                    $tanggal_kadaluarsa = date('d-M-Y', strtotime($dokumen_value->tanggal_kadaluarsa));
                    $exp_date = new DateTime(date('d-M-Y', strtotime($dokumen_value->tanggal_kadaluarsa)));
                    $date = new DateTime();
                    $notif = new DateTime(date('d-M-Y', strtotime($dokumen_value->tanggal_kadaluarsa." -$dokumen->notif_hari days")));

                    $exp_date = $exp_date->getTimestamp();
                    $date = $date->getTimestamp();
                    $notif = $notif->getTimestamp();

                    if($date >= $notif && $date <= $exp_date )
                    {
                        $show_penjamin .= '<div class="portlet box red">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    '.translate($data['nama'], $this->session->userdata('language')).' ('.translate('Kadaluarsa', $this->session->userdata('language')).')
                                                </div>
                                            </div>
                                            <div class="portlet-body">';
                        if($data['is_kadaluarsa'] == 1)
                        {
                            $expire = '<div class="form-group">
                                    <label class="control-label col-md-4">Tanggal Kadaluarsa :';

                            if($data['is_required'] == 1)
                            {
                                $expire .= '<span class="required" aria-required="true">*</span>';  
                            }
                            $expire .= '</label>
                                        <div class="col-md-8">
                                        <div class="input-group date" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]">
                                            <input type="text" class="form-control" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" name="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" value="'.$tanggal_kadaluarsa.'"'; 
                            if($data['is_required'] == 1)
                            {
                                $expire .= ' required="required" '; 
                            }
                            $expire .='readonly="" aria-required="true">
                                            <span class="input-group-btn">
                                                <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>';                 
                        }
                        else
                        {
                            $expire = '<input type="hidden" class="form-control" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" name="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]">';
                        }
                        $expire .= '<input type="hidden" id="penjamin_dokumen['.$y.'][dokumen_id]" name="penjamin_dokumen['.$y.'][dokumen_id]" value="'.$data['dokumen_id'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_kadaluarsa]" name="penjamin_dokumen['.$y.'][is_kadaluarsa]" value="'.$data['is_kadaluarsa'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_required]" name="penjamin_dokumen['.$y.'][is_required]" value="'.$data['is_required'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][nama]" name="penjamin_dokumen['.$y.'][nama]" value="'.$data['nama'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][tipe_dokumen]" name="penjamin_dokumen['.$y.'][tipe_dokumen]" value="'.$data['tipe'].'">';

                        $show_penjamin .= $expire;
                        if(count($dokumen_detail))
                        {
                            $detail = '';
                            $dokumen_detail = object_to_array($dokumen_detail);
                            // die(dump($this->db->last_query()));
                            $i = 0;
                            $ii = 0;
                            foreach ($dokumen_detail as $data_detail) 
                            {
                                $value = '';
                                $detail_tipe_dokumen = $this->pasien_dokumen_m->get_by_data(array('dokumen_id' => $data['dokumen_id'],'pasien_id' => $id_pasien, 'dokumen_detail_id' => $data_detail['id']));

                                
                                if(count($detail_tipe_dokumen))
                                {
                                    $value = $detail_tipe_dokumen->value;
                                }
                                

                                $detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][dokumen_id]" value="'.$data['dokumen_id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'">';

                                if ($data_detail['tipe'] == 1)
                                {
                                    $required = '';
                                    $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }
                                
                                elseif ($data_detail['tipe'] == 2)
                                {
                                    $required = '';
                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';

                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }

                                    $input .= '</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">'.$value.'</textarea>

                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }

                                elseif ($data_detail['tipe'] == 3) 
                                {
                                    $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }

                                    $input .='</label>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }

                                elseif ($data_detail['tipe'] == 4) 
                                {
                                    $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                                    $detail_tipe_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );

                                    foreach ($detail_tipe as $detail_tipe)
                                    {   
                                        $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                                    }

                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8">'.
                                                    form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]', $detail_tipe_option, $value, 'class="form-control" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]"')
                                                    .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }
                                
                                elseif ($data_detail['tipe'] == 5)
                                {
                                    $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                                    $detail_tipe_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );


                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }

                                    $input .= '</label>
                                                <div class="col-md-8"><div class="radio-list">';

                                    $checked = '';
                                    foreach ($detail_tipe as $detail_tipe)
                                    {   
                                        if($value == $detail_tipe->value)
                                        {
                                            $checked = 'checked="checked"';
                                        }
                                        $input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                                    }
                                     $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }
                                
                                elseif ($data_detail['tipe'] == 6)
                                {
                                    $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                                    $detail_tipe_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );


                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8"><div class="checkbox-list">';
                                    $checked = '';
                                    foreach ($detail_tipe as $detail_tipe)
                                    {   
                                        if($value == $detail_tipe->value)
                                        {
                                            $checked = 'checked="checked"';
                                        }
                                        $input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                                    }
                                     $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }

                                elseif ($data_detail['tipe'] == 7) 
                                {
                                    $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                                    $detail_tipe_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );

                                    $selected = '';
                                    foreach ($detail_tipe as $detail_tipe)
                                    {   
                                        if($detail_tipe->value == $value)
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                                    }

                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8">'.
                                                    form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" multiple="multiple" '.$required.' '.$selected.' ')
                                                    .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }

                                elseif ($data_detail['tipe'] == 8) 
                                {
                                    $date = '';
                                    if($value != '')
                                    {
                                        $date = date('d-M-Y', strtotime($value));
                                    }
                                    $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8">
                                                <div class="input-group date" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]">
                                                    <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" required="required" readonly="" aria-required="true" '.$required.' value="'.$date.'">
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                                    
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }
                                elseif ($data_detail['tipe'] == 9) 
                                {
                                    $image = '';
                                    if($value != '')
                                    {
                                        if($value != 'doc_global/document.png')
                                        {
                                            $tipe = ($dokumen->tipe == 1)?'pelengkap':'rekam_medis';
                                            $value = $data_pasien->no_member.'/dokumen/'.$tipe.'/'.$value;
                                        }

                                        $image = '<li class="working">
                                                    <div class="thumbnail">
                                                        <a class="fancybox-button" title="'.$value.'" href="'.base_url().config_item('site_img_pasien').$value.'" data-rel="fancybox-button">
                                                            <img src="'.base_url().config_item('site_img_pasien').$value.'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                                        </a>
                                                    </div>
                                                </li>';
                                    }
                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                              <div class="col-md-8">
                                                <div id="upload_dokumen_'.$z.'">
                                                    <input type="hidden" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$value.'" '.$required.' />
                                                    <span class="btn default btn-file">
                                                    <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>    
                                                        <input type="file" name="upl" id="upload_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
                                                    </span>
                                                    <ul class="ul-img">
                                                        '.$image.'
                                                    </ul>
                                                </div>
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">
                                              </div>';
                                    $z++;
                                }
                                
                                $show_penjamin .= '<div class="form-group">'.$detail.'</div><div class="form-group">'.$input.'</div>';
                                $i++;
                                $ii++;
                            }
                        }

                        $show_penjamin .= '</div></div>';
                        $y++;
                    }
                } 
            }
            if(count($dokumen_value) == 0)
            {               
                $show_penjamin .= '<div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            '.translate($data['nama'], $this->session->userdata('language')).'
                                        </div>
                                    </div>
                                    <div class="portlet-body">';
                if($data['is_kadaluarsa'] == 1)
                {
                    $expire = '<div class="form-group">
                            <label class="control-label col-md-4">Tanggal Kadaluarsa :';

                    if($data['is_required'] == 1)
                    {
                        $expire .= '<span class="required" aria-required="true">*</span>';  
                    }
                    $expire .= '</label>
                                <div class="col-md-8">
                                <div class="input-group date" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]">
                                    <input type="text" class="form-control" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" name="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" value="'.$tanggal_kadaluarsa.'"'; 
                    if($data['is_required'] == 1)
                    {
                        $expire .= ' required="required" '; 
                    }
                    $expire .='readonly="" aria-required="true">
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>';                 
                }
                else
                {
                    $expire = '<input type="hidden" class="form-control" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" name="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]">';
                }
                $expire .= '<input type="hidden" id="penjamin_dokumen['.$y.'][dokumen_id]" name="penjamin_dokumen['.$y.'][dokumen_id]" value="'.$data['dokumen_id'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_kadaluarsa]" name="penjamin_dokumen['.$y.'][is_kadaluarsa]" value="'.$data['is_kadaluarsa'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_required]" name="penjamin_dokumen['.$y.'][is_required]" value="'.$data['is_required'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][nama]" name="penjamin_dokumen['.$y.'][nama]" value="'.$data['nama'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][tipe_dokumen]" name="penjamin_dokumen['.$y.'][tipe_dokumen]" value="'.$data['tipe'].'">';

                $show_penjamin .= $expire;
                if(count($dokumen_detail))
                {
                    $detail = '';
                    $dokumen_detail = object_to_array($dokumen_detail);
                    // die(dump($this->db->last_query()));
                    $i = 0;
                    $ii = 0;
                    foreach ($dokumen_detail as $data_detail) 
                    {
                        $value = '';
                        $detail_tipe_dokumen = $this->pasien_dokumen_m->get_by_data(array('dokumen_id' => $data['dokumen_id'],'pasien_id' => $id_pasien, 'dokumen_detail_id' => $data_detail['id']));

                        
                        if(count($detail_tipe_dokumen))
                        {
                            $value = $detail_tipe_dokumen->value;
                        }
                        

                        $detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][dokumen_id]" value="'.$data['dokumen_id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'">';

                        if ($data_detail['tipe'] == 1)
                        {
                            $required = '';
                            $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }
                            $input .= '</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                        </div>';
                        }
                        
                        elseif ($data_detail['tipe'] == 2)
                        {
                            $required = '';
                            $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';

                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }

                            $input .= '</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">'.$value.'</textarea>

                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                        </div>';
                        }

                        elseif ($data_detail['tipe'] == 3) 
                        {
                            $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                            $required = '';
                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }

                            $input .='</label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                        </div>';
                        }

                        elseif ($data_detail['tipe'] == 4) 
                        {
                            $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                            $detail_tipe_option = array(
                                '' => translate('Pilih..', $this->session->userdata('language'))
                            );

                            foreach ($detail_tipe as $detail_tipe)
                            {   
                                $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                            }

                            $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                            $required = '';
                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }
                            $input .= '</label>
                                        <div class="col-md-8">'.
                                            form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]', $detail_tipe_option, $value, 'class="form-control" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]"')
                                            .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                        </div>';
                        }
                        
                        elseif ($data_detail['tipe'] == 5)
                        {
                            $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                            $detail_tipe_option = array(
                                '' => translate('Pilih..', $this->session->userdata('language'))
                            );


                            $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                            $required = '';
                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }

                            $input .= '</label>
                                        <div class="col-md-8"><div class="radio-list">';

                            $checked = '';
                            foreach ($detail_tipe as $detail_tipe)
                            {   
                                if($value == $detail_tipe->value)
                                {
                                    $checked = 'checked="checked"';
                                }
                                $input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                            }
                             $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                        </div>';
                        }
                        
                        elseif ($data_detail['tipe'] == 6)
                        {
                            $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                            $detail_tipe_option = array(
                                '' => translate('Pilih..', $this->session->userdata('language'))
                            );


                            $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                            $required = '';
                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }
                            $input .= '</label>
                                        <div class="col-md-8"><div class="checkbox-list">';
                            $checked = '';
                            foreach ($detail_tipe as $detail_tipe)
                            {   
                                if($value == $detail_tipe->value)
                                {
                                    $checked = 'checked="checked"';
                                }
                                $input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                            }
                             $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                        </div>';
                        }

                        elseif ($data_detail['tipe'] == 7) 
                        {
                            $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                            $detail_tipe_option = array(
                                '' => translate('Pilih..', $this->session->userdata('language'))
                            );

                            $selected = '';
                            foreach ($detail_tipe as $detail_tipe)
                            {   
                                if($detail_tipe->value == $value)
                                {
                                    $selected = 'selected="selected"';
                                }
                                $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                            }

                            $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                            $required = '';
                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }
                            $input .= '</label>
                                        <div class="col-md-8">'.
                                            form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" multiple="multiple" '.$required.' '.$selected.' ')
                                            .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                        </div>';
                        }

                        elseif ($data_detail['tipe'] == 8) 
                        {
                            $date = '';
                            if($value != '')
                            {
                                $date = date('d-M-Y', strtotime($value));
                            }
                            $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                            $required = '';
                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }
                            $input .= '</label>
                                        <div class="col-md-8">
                                        <div class="input-group date" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]">
                                            <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" required="required" readonly="" aria-required="true" '.$required.' value="'.$date.'">
                                            <span class="input-group-btn">
                                                <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                            
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                        </div>';
                        }
                        elseif ($data_detail['tipe'] == 9) 
                        {
                            $image = '';
                            if($value != '')
                            {
                                if($value != 'doc_global/document.png')
                                {
                                    $tipe = ($dokumen->tipe == 1)?'pelengkap':'rekam_medis';
                                    $value = $data_pasien->no_member.'/dokumen/'.$tipe.'/'.$value;
                                }


                                $image = '<li class="working">
                                            <div class="thumbnail">
                                                <a class="fancybox-button" title="'.$value.'" href="'.base_url().config_item('site_img_pasien').$value.'" data-rel="fancybox-button">
                                                    <img src="'.base_url().config_item('site_img_pasien').$value.'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                                </a>
                                            </div>
                                        </li>';
                            }
                            $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                            $required = '';
                            if($data['is_required'] == 1)
                            {
                                $input .= '<span class="required" aria-required="true">*</span>';
                                $required = 'required="required"';  
                            }
                            $input .= '</label>
                                      <div class="col-md-8">
                                        <div id="upload_dokumen_'.$z.'">
                                            <input type="hidden" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$value.'" '.$required.' />
                                            <span class="btn default btn-file">
                                            <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>    
                                                <input type="file" name="upl" id="upload_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
                                            </span>
                                            <ul class="ul-img">
                                                '.$image.'
                                            </ul>
                                        </div>
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                            <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">
                                      </div>';
                            $z++;
                        }
                        
                        $show_penjamin .= '<div class="form-group">'.$detail.'</div><div class="form-group">'.$input.'</div>';
                        $i++;
                        $ii++;
                    }
                }

                $show_penjamin .= '</div></div>';
                $y++;
            }
        }
        echo $show_penjamin;
    }

    public function show_edit_claim()
    {
        $id_penjamin = $this->input->post('id_penjamin');
        $id_pasien = $this->input->post('id_pasien');
        $id_pasien_penjamin = $this->input->post('id_pasien_penjamin');

        $penjamin_dokumen = $this->penjamin_dokumen_m->get_data_penjamin_dokumen($id_penjamin)->result_array();

        $data_penjamin_dokumen = object_to_array($penjamin_dokumen);
        $data_pasien = $this->pasien_m->get($id_pasien);

        $show_penjamin = '';
        $y = 1;
        $z = 0;
        $radio = '';
        foreach ($data_penjamin_dokumen as $data) 
        {
            $dokumen = $this->dokumen_m->get($data['dokumen_id']);

            $dokumen_detail = $this->dokumen_detail_m->get_by(array('dokumen_id' => $data['dokumen_id']));
            $dokumen_value = $this->pasien_dokumen_m->get_by(array('pasien_id' => $id_pasien,'dokumen_id' => $data['dokumen_id'], 'is_active' => 1), true);
            $tanggal_kadaluarsa = '';
            if(count($dokumen_value))
            {
                if($data['is_kadaluarsa'] == 1)  
                {
                    $tanggal_kadaluarsa = date('d-M-Y', strtotime($dokumen_value->tanggal_kadaluarsa));
                    $exp_date = new DateTime(date('d-M-Y', strtotime($dokumen_value->tanggal_kadaluarsa)));
                    $date = new DateTime();
                    $notif = new DateTime(date('d-M-Y', strtotime($dokumen_value->tanggal_kadaluarsa." -$dokumen->notif_hari days")));

                    $exp_date = $exp_date->getTimestamp();
                    $date = $date->getTimestamp();
                    $notif = $notif->getTimestamp();

                    if($date >= $notif && $date <= $exp_date )
                    {

                        $show_penjamin .= '<div class="portlet box red">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    '.translate($data['nama'], $this->session->userdata('language')).'
                                                </div>
                                            </div>
                                            <div class="portlet-body">';
                        if($data['is_kadaluarsa'] == 1)
                        {
                            $expire = '<div class="form-group">
                                    <label class="control-label col-md-4">Tanggal Kadaluarsa :';

                            if($data['is_required'] == 1)
                            {
                                $expire .= '<span class="required" aria-required="true">*</span>';  
                            }
                            $expire .= '</label>
                                        <div class="col-md-8">
                                        <div class="input-group date" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]">
                                            <input type="text" class="form-control" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" name="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" value="'.$tanggal_kadaluarsa.'"'; 
                            if($data['is_required'] == 1)
                            {
                                $expire .= ' required="required" '; 
                            }
                            $expire .='readonly="" aria-required="true">
                                            <span class="input-group-btn">
                                                <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>';                 
                        }
                        else
                        {
                            $expire = '<input type="hidden" class="form-control" id="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]" name="penjamin_dokumen['.$y.'][tanggal_kadaluarsa]">';
                        }
                        $expire .= '<input type="hidden" id="penjamin_dokumen['.$y.'][dokumen_id]" name="penjamin_dokumen['.$y.'][dokumen_id]" value="'.$data['dokumen_id'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_kadaluarsa]" name="penjamin_dokumen['.$y.'][is_kadaluarsa]" value="'.$data['is_kadaluarsa'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][is_required]" name="penjamin_dokumen['.$y.'][is_required]" value="'.$data['is_required'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][nama]" name="penjamin_dokumen['.$y.'][nama]" value="'.$data['nama'].'"><input type="hidden" id="penjamin_dokumen['.$y.'][tipe_dokumen]" name="penjamin_dokumen['.$y.'][tipe_dokumen]" value="'.$data['tipe'].'">';

                        $show_penjamin .= $expire;
                        if(count($dokumen_detail))
                        {
                            $detail = '';
                            $dokumen_detail = object_to_array($dokumen_detail);
                            // die(dump($this->db->last_query()));
                            $i = 0;
                            $ii = 0;
                            foreach ($dokumen_detail as $data_detail) 
                            {
                                $value = '';
                                $detail_tipe_dokumen = $this->pasien_dokumen_m->get_by_data(array('dokumen_id' => $data['dokumen_id'],'pasien_id' => $id_pasien, 'dokumen_detail_id' => $data_detail['id']));

                                
                                if(count($detail_tipe_dokumen))
                                {
                                    $value = $detail_tipe_dokumen->value;
                                }
                                

                                $detail .= '<input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][id]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][id]" value="'.$data_detail['id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][dokumen_id]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][dokumen_id]" value="'.$data['dokumen_id'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][nama_dok]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][nama_dok]" value="'.$data['nama'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][judul]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][judul]" value="'.$data_detail['judul'].'"><input type="hidden" id="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe]" name="penjamin_dokumen_detail_'.$data['dokumen_id'].'['.$ii.'][tipe]" value="'.$data_detail['tipe'].'">';

                                if ($data_detail['tipe'] == 1)
                                {
                                    $required = '';
                                    $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' placeholder="'.$data_detail['judul'].'" maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }
                                
                                elseif ($data_detail['tipe'] == 2)
                                {
                                    $required = '';
                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';

                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }

                                    $input .= '</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' maxlength="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">'.$value.'</textarea>

                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }

                                elseif ($data_detail['tipe'] == 3) 
                                {
                                    $input = ' <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }

                                    $input .='</label>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" placeholder="'.$data_detail['judul'].'" '.$required.' min="0" max="'.$data_detail['maksimal_karakter'].'" value="'.$value.'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }

                                elseif ($data_detail['tipe'] == 4) 
                                {
                                    $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                                    $detail_tipe_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );

                                    foreach ($detail_tipe as $detail_tipe)
                                    {   
                                        $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                                    }

                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8">'.
                                                    form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]', $detail_tipe_option, $value, 'class="form-control" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]"')
                                                    .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }
                                
                                elseif ($data_detail['tipe'] == 5)
                                {
                                    $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                                    $detail_tipe_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );


                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }

                                    $input .= '</label>
                                                <div class="col-md-8"><div class="radio-list">';

                                    $checked = '';
                                    foreach ($detail_tipe as $detail_tipe)
                                    {   
                                        if($value == $detail_tipe->value)
                                        {
                                            $checked = 'checked="checked"';
                                        }
                                        $input .= '<label class="radio-inline"><input type="radio" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                                    }
                                     $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }
                                
                                elseif ($data_detail['tipe'] == 6)
                                {
                                    $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                                    $detail_tipe_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );


                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8"><div class="checkbox-list">';
                                    $checked = '';
                                    foreach ($detail_tipe as $detail_tipe)
                                    {   
                                        if($value == $detail_tipe->value)
                                        {
                                            $checked = 'checked="checked"';
                                        }
                                        $input .= '<label class="checkbox-inline"><input type="checkbox" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" '.$required.' id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$detail_tipe->value.'" '.$checked.'>'.$detail_tipe->text.'</label>';
                                    }
                                     $input .= '</div><input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }

                                elseif ($data_detail['tipe'] == 7) 
                                {
                                    $detail_tipe = $this->dokumen_detail_tipe_m->get_by(array('dokumen_detail_id' => $data_detail['id']));
                                    $detail_tipe_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );

                                    $selected = '';
                                    foreach ($detail_tipe as $detail_tipe)
                                    {   
                                        if($detail_tipe->value == $value)
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        $detail_tipe_option[$detail_tipe->value] = $detail_tipe->text;
                                    }

                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8">'.
                                                    form_dropdown('penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value][]', $detail_tipe_option, '', 'class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" multiple="multiple" '.$required.' '.$selected.' ')
                                                    .'<input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }

                                elseif ($data_detail['tipe'] == 8) 
                                {
                                    $date = '';
                                    if($value != '')
                                    {
                                        $date = date('d-M-Y', strtotime($value));
                                    }
                                    $input = '  <label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                                <div class="col-md-8">
                                                <div class="input-group date" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]">
                                                    <input type="text" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" required="required" readonly="" aria-required="true" '.$required.' value="'.$date.'">
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                                    
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">         
                                                </div>';
                                }
                                elseif ($data_detail['tipe'] == 9) 
                                {
                                    $image = '';
                                    if($value != '')
                                    {
                                        if($value != 'doc_global/document.png')
                                        {
                                            $value = $value;
                                            $tipe  = ($dokumen->tipe == 1)?'pelengkap':'rekam_medis';
                                            $src   = $data_pasien->no_member.'/dokumen/'.$tipe.'/'.$value;
                                        }

                                        $image = '<li class="working">
                                                    <div class="thumbnail">
                                                        <a class="fancybox-button" title="'.$value.'" href="'.base_url().config_item('site_img_pasien').$src.'" data-rel="fancybox-button">
                                                            <img src="'.base_url().config_item('site_img_pasien').$src.'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                                        </a>
                                                    </div>
                                                </li>';
                                    }
                                    $input = '<label class="control-label col-md-4">'.$data_detail['judul'].' :';
                                    $required = '';
                                    if($data['is_required'] == 1)
                                    {
                                        $input .= '<span class="required" aria-required="true">*</span>';
                                        $required = 'required="required"';  
                                    }
                                    $input .= '</label>
                                              <div class="col-md-8">
                                                <div id="upload_dokumen_'.$z.'">
                                                    <input type="hidden" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][value]" value="'.$value.'" '.$required.' />
                                                    <span class="btn default btn-file">
                                                    <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>    
                                                        <input type="file" name="upl" id="upload_'.$z.'" data-url="'.base_url().'upload/upload_photo" class="uploadbutton" multiple />
                                                    </span>
                                                    <ul class="ul-img">
                                                        '.$image.'
                                                    </ul>
                                                </div>
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][text]" value="'.$data_detail['judul'].'">
                                                    <input type="hidden" class="form-control" id="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" name="penjamin_dokumen_detail_tipe_'.$data_detail['id'].'['.$i.'][dokumen_detail_id]" value="'.$data_detail['id'].'">
                                              </div>';
                                    $z++;
                                }
                                
                                $show_penjamin .= '<div class="form-group">'.$detail.'</div><div class="form-group">'.$input.'</div>';
                                $i++;
                                $ii++;
                            }
                        }

                        $show_penjamin .= '</div></div>';
                        $y++;
                    }
                }
            }
        }
        echo $show_penjamin;
    }

    public function search_kelurahan($tipe,$index)
    {
       $data = array(
        'tipe' => $tipe,
        'index' => $index
        );
       $this->load->view('master/pasien/modal/search_alamat', $data);
    }

    public function listing_alamat()
    {
        $this->load->model('master/info_alamat_m');
        $result = $this->info_alamat_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
      

        foreach($records->result_array() as $row)
        {             
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-confirm="'.translate('Pilih alamat ini?', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select_alamat"><i class="fa fa-check"></i> </a>';
            
            $output['data'][] = array(
                '<div class="text-left">'.$row['kelurahan'].'</div>' ,
                '<div class="text-left">'.$row['kecamatan'].'</div>' ,
                '<div class="text-left">'.$row['kotkab'].'</div>',
                '<div class="text-left">'.$row['propinsi'].'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
        }

        echo json_encode($output);
    }

    public function search_alamat()
    {
       if($this->input->is_ajax_request())
       {
            $this->load->model('master/info_alamat_m');
            $nama_kelurahan = $this->input->post('nama_kelurahan');

            $data_alamat = $this->info_alamat_m->get_by_nama($nama_kelurahan)->result_array();

            $html ='';
            if(count($data_alamat))
            {
                foreach ($data_alamat as $alamat)
                {
                    $action = '<a title="'.translate('Cari', $this->session->userdata('language')).'" data-confirm="'.translate('Pilih alamat ini?', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($alamat)).'" class="btn btn-primary"><i class="fa fa-check"></i> </a>';
                    $html .= '<tr><td>'.$alamat['kelurahan'].'</td><td>'.$alamat['kecamatan'].'</td><td>'.$alamat['kotkab'].'</td><td>'.$alamat['propinsi'].'</td><td>'.$action.'</td></tr>';
                }
            }
            else
            {
                $html .= '<tr> <td colspan="5" style="text-align:center;"><span>'.translate('Tidak ada data tersedia', $this->session->userdata('language')).'</span></td> </tr>';
            }

            echo $html;
       }
    }

    public function listing_faskes()
    {
        $result = $this->master_faskes_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        foreach($records->result_array() as $row)
        {             
            $marketing = $this->faskes_marketing_m->get_data_marketing($row['kode_faskes'])->row(0);

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-confirm="'.translate('Pilih faskes ini?', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" data-marketing="'.htmlentities(json_encode(object_to_array($marketing))).'" class="btn btn-primary select_faskes"><i class="fa fa-check"></i> </a>';
            
            $output['data'][] = array(
                '<div class="text-left">'.$row['jenis'].'</div>' ,
                '<div class="text-left">'.$row['kode_faskes'].'</div>' ,
                '<div class="text-left">'.$row['nama_faskes'].'</div>',
                '<div class="text-left">'.$row['alamat'].'</div>' ,
                '<div class="text-left">'.$row['nama_reg'].'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
        }

        echo json_encode($output);
    }

    public function search_faskes()
    {
       $this->load->view('master/pasien/modal/search_faskes');
    }

    public function search_faskes_1()
    {
       $this->load->view('master/pasien/modal/search_faskes_1');
    }

}

/* End of file pasien.php */
/* Location: ./application/controllers/master/pasien.php */