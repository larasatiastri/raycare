<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class daftar_pasien extends MY_Controller {

    protected $menu_id = '12cc8b85535ab7ece95855989385ec52';                  // untuk check bit_access
    
    private $menus     = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level  = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/info_umum_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/daftar_pasien/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Daftar Pasien', $this->session->userdata('language')), 
            'header'         => translate('Daftar Pasien', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/daftar_pasien/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/klinik_hd/daftar_pasien/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data            = $this->daftar_pasien_m->get($id);
        $data_pasien          = $this->pasien_m->get_by(array('id' => $form_data->pasien_id));
        $data_poliklinik      = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_tujuan_id));
        $data_poliklinik_asal = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_asal_id));

        $data = array(
            'title'                => config_item('site_name').' | '. translate("View Daftar Pasien", $this->session->userdata("language")), 
            'header'               => translate("View Daftar Pasien", $this->session->userdata("language")), 
            'header_info'          => config_item('site_name'), 
            'breadcrumb'           => TRUE,
            'menus'                => $this->menus,
            'menu_tree'            => $this->menu_tree,
            'css_files'            => $assets['css'],
            'js_files'             => $assets['js'],
            'content_view'         => 'klinik_hd/daftar_pasien/view',
            'form_data'            => object_to_array($form_data),
            'data_pasien'          => object_to_array($data_pasien),
            'data_poliklinik'      => object_to_array($data_poliklinik),
            'data_poliklinik_asal' => object_to_array($data_poliklinik_asal),
            'pk_value'             => $id,
            'flag'                 => 'view'                         //table primary key value
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
        $result = $this->pasien_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {   

            if ($row['active'] == '1')
            {
                
                $data_view     = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/daftar_pasien/view/'.$row['id'].'" class="btn btn-xs grey-cascade hidden"><i class="fa fa-search"></i></a>';
                $data_surat = '<a title="'.translate('Buat Surat', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/daftar_pasien/buat_surat/'.$row['id'].'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal_surat"><i class="fa fa-envelope"></i></a>';
               

                $action =  restriction_button($data_view, $user_level_id, 'master_pasien', 'view').restriction_button($data_surat, $user_level_id, 'master_pasien', 'surat');

            }

            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }

            $output['data'][] = array(
                $row['id'],
                $img_url.$row['nama'],
                '<div class="text-center">'.$row['no_member'].'</div>' ,
                '<div class="text-center">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].', '.$row['kecamatan'].', '.$row['kecamatan'].', '.$row['kota'].'</div>',
                '<div class="text-center">'.$row['nama_cabang'].'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function buat_surat($pasien_id)
    {
        $data['pasien_id'] = $pasien_id;

        $this->load->view('klinik_hd/daftar_pasien/modal_buat_surat', $data);
    }

   


}

/* End of file daftar_pasien.php */
/* Location: ./application/controllers/klinik_hd/daftar_pasien.php */