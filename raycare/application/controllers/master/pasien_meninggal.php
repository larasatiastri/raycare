<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien_meninggal extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '87c8ef1fcc14be2f891e0d0ec1320747';                  // untuk check bit_access

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
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_meninggal_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/pasien_meninggal/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pasien Meninggal', $this->session->userdata('language')), 
            'header'         => translate('Pasien Meninggal', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien_meninggal/index',
            );
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/master/pasien_meninggal/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tambah Pasien Meninggal', $this->session->userdata('language')), 
            'header'         => translate('Tambah Pasien Meninggal', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/pasien_meninggal/add',
            );
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());


        $assets = array();
        $config = 'assets/master/pasien_meninggal/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $form_data = $this->pasien_meninggal_m->get($id);
        $pasien = $this->pasien_m->get($form_data->pasien_id);

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tambah Pasien Meninggal', $this->session->userdata('language')), 
            'header'         => translate('Tambah Pasien Meninggal', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'form_data'      => object_to_array($form_data),
            'pasien'        => object_to_array($pasien),
            'content_view'   => 'master/pasien_meninggal/view',
            );
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        $result = $this->pasien_meninggal_m->get_datatable();
        //die_dump($result);
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
            $action = '<a href="'.base_url().'master/pasien_meninggal/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';

            $lokasi_meninggal = '';

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

            if($row['tipe_lokasi'] == 1)
            {
                $cabang = $this->cabang_m->get($row['cabang_meninggal']);
                $lokasi_meninggal = $cabang->nama;
            }
            else
            {
                $lokasi_meninggal = $row['lokasi_meninggal'];
            }

            $output['data'][] = array(
                '<div class="text-center">'.$row['no_member'].'</div>' ,
                $img_url.$row['nama'],
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_meninggal'])).'</div>' ,
                '<div class="text-left">'.$lokasi_meninggal.'</div>',
                '<div class="text-center">'.$row['keterangan'].'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );

            
        }
        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();

        $data = array(
            'pasien_id'          => $array_input['id_pasien'],
            'tanggal_meninggal'  => date('Y-m-d H:i:s', strtotime($array_input['tanggal_meninggal'])),
            'tipe_lokasi'        => $array_input['tipe_lokasi'],
            'cabang_meninggal'   => $array_input['cabang_meninggal'],
            'lokasi_meninggal'   => $array_input['lokasi_meninggal'],
            'tipe_lokasi_tujuan' => $array_input['tipe_lokasi_tujuan'],
            'lokasi_tujuan'      => $array_input['lokasi_tujuan'],
            'tipe_kendaraan'     => $array_input['tipe_kendaraan'],
            'no_kendaraan'       => $array_input['no_kendaraan'],
            'keterangan'       => $array_input['keterangan'],
        );

        $path_model = 'master/pasien_meninggal_m';
        $cabang = $this->cabang_m->get_by('tipe in (0,1)');
        foreach ($cabang as $row_cabang) 
        {
            if($row_cabang->is_active == 1)
            {
                if($row_cabang->url != '' || $row_cabang->url != NULL)
                {
                    $save_pasien_id = insert_data_api($data,$row_cabang->url,$path_model);                    
                }
            }
        }
        
        // $pasien_meninggal_id = $this->pasien_meninggal_m->save($data);

        $data_pasien['is_meninggal'] = 1;
        $path_model = 'master/pasien_m';
        $cabang = $this->cabang_m->get_by('tipe in (0,1)');
        foreach ($cabang as $row_cabang) 
        {
            if($row_cabang->is_active == 1)
            {
                if($row_cabang->url != '' || $row_cabang->url != NULL)
                {
                    $save_pasien_id = insert_data_api($data_pasien,$row_cabang->url,$path_model,$array_input['id_pasien']);                    
                }
            }
        }

        $data_jadwal['is_active'] = 0;
        $wheres = array(
            'pasien_id' => $array_input['id_pasien'],
            'date(jadwal.tanggal) >' => date('Y-m-d')
        );

        $path_model = 'klinik_hd/jadwal_m';
        $cabang = $this->cabang_m->get_by(array('tipe' => 1));
        foreach ($cabang as $row_cabang) 
        {
            if($row_cabang->is_active == 1)
            {
                if($row_cabang->url != '' || $row_cabang->url != NULL)
                {
                    $ubah_jadwal = update_data_api($data_jadwal,$row_cabang->url,$path_model,$wheres);                    
                }
            }
        }

        $flashdata = array(
            "type"     => "success",
            "msg"      => translate("Data pasien meninggal ditambahkan", $this->session->userdata("language")),
            "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
        

        redirect('master/pasien_meninggal');
    }

    public function search_pasien_by_nomor()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Data Pasien Tidak Ditemukan', $this->session->userdata('language'));

            $no_pasien = $this->input->post('no_pasien');

            $pasien = $this->pasien_m->get_pasien_by_nomor($no_pasien)->row(0);

            if(count($pasien))
            {

                $response->success = true;
                $response->msg = translate('Data Pasien Ditemukan', $this->session->userdata('language'));
                $response->rows = $pasien;
            }

            die(json_encode($response));

        }
    }
    /**
     * [list description]
     * @return [type] [description]
     */

    public function listing_pilih_pasien()
    {
        $cabang_id = $this->session->userdata('cabang_id');
        $result = $this->pasien_m->get_datatable_pilih_pasien($cabang_id);

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
            
            $action = '';
            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_ktp'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    
}

/* End of file pabrik.php */
/* Location: ./application/controllers/pabrik/pabrik.php */