<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_perpanjang_rujukan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '070ef7e06b8c4b674fc765ef023cc31c';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/surat_perpanjang_rujukan_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
       
    }
    
    public function history()
    {
        $assets = array();
        $config = 'assets/klinik_hd/surat_perpanjang_rujukan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Surat Perpanjang Rujukan', $this->session->userdata('language')), 
            'header'         => translate('Surat Perpanjang Rujukan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/surat_perpanjang_rujukan/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function index($pasien_id=null)
    {
        $assets = array();
        $assets_config = 'assets/klinik_hd/surat_perpanjang_rujukan/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah Surat Perpanjang Rujukan", $this->session->userdata("language")), 
            'header'         => translate("Tambah Surat Perpanjang Rujukan", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/surat_perpanjang_rujukan/add',
            'flag'           => 'add',
            'pasien_id'      => $pasien_id
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/klinik_hd/surat_perpanjang_rujukan/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data            = $this->surat_perpanjang_rujukan_m->get($id);
        $data_pasien          = $this->pasien_m->get_by(array('id' => $form_data->pasien_id));
        $data_poliklinik      = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_tujuan_id));
        $data_poliklinik_asal = $this->poliklinik_m->get_by(array('id' => $form_data->poliklinik_asal_id));
        // die(dump($this->db->last_query()));
        // die(dump($data_poliklinik));

        $data = array(
            'title'                => config_item('site_name').' | '. translate("View Rujukan", $this->session->userdata("language")), 
            'header'               => translate("View Rujukan", $this->session->userdata("language")), 
            'header_info'          => config_item('site_name'), 
            'breadcrumb'           => TRUE,
            'menus'                => $this->menus,
            'menu_tree'            => $this->menu_tree,
            'css_files'            => $assets['css'],
            'js_files'             => $assets['js'],
            'content_view'         => 'klinik_hd/surat_perpanjang_rujukan/view',
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
        $result = $this->surat_perpanjang_rujukan_m->get_datatable();

        // die_dump($this->db->last_query());      
        // die_dump($result);      
        
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $nama_poli = '';
        $nama_poli_asal = '';

        foreach($records->result_array() as $row)
        {
           
            if ($row['is_active'] == '1')
            {
                $action = '
                    <a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/surat_perpanjang_rujukan/view/'.$row['id'].'" class="btn btn-xs grey-cascade hidden"><i class="fa fa-search"></i></a>
                    <a target="_blank" title="'.translate('Cetak', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/surat_perpanjang_rujukan/cetak_perpanjang_rujukan/'.$row['id'].'" class="btn btn-xs default"><i class="fa fa-print"></i></a>
                    <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data rujukan ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red hidden"><i class="fa fa-times"></i> </a>';
            
            }
    
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
                '<div class="text-left">'.$row['no_surat'].'</div>' ,
                '<div class="text-left">'.$img_url.$row['nama'].'</div>' ,
                '<div class="text-left">'.$row['nama_dokter_buat'].'</div>' ,
                '<div class="text-left">'.$row['kepada'].'</div>' ,
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>'
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
        // die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            
            $action = '';
            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs green-haze select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_ktp'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_icd_code($first_code=null,$last_code=null)
    {        
        $this->load->model('master/icd/icd_code_m');
        $result = $this->icd_code_m->get_datatable($first_code,$last_code);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        foreach($records->result_array() as $row)
        {
           $action='';
       
            $output['data'][] = array(

                '<div class="text-center">'.$row['code_ast'].'</div>',
                '<div class="text-left"><a class="icd_name" data-code="'.$row['code_ast'].'" data-name="'.$row['name'].'">'.$row['name'].'</a></div>'            
            );
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();
        $cabang_login = $this->cabang_m->get($this->session->userdata('cabang_id'));
        // die(dump($array_input));
        if ($array_input['command'] === 'add')
        {  
            $roman_month = romanic_number(date('m'), true);
            $max_no = $this->surat_perpanjang_rujukan_m->get_max_no($roman_month)->result_array();

            if(count($max_no))
            {
                $last_nosurat = intval($max_no[0]['max_no']) + 1;
            }
            else
            {
                $last_nosurat = 1;
            }

            $format       = '#SPR#%03d';
            $no_surat    = sprintf($format, $last_nosurat, 3);
            $no_surat_new = $no_surat.'#RHS#'.$roman_month.'#'.date('Y').'#'.$cabang_login->kode;

            $data_surat_pengantar = array(
                'pasien_id'            => $array_input['id_ref_pasien'],
                'cabang_id'            => $this->session->userdata('cabang_id'),
                'no_surat'             => $no_surat_new,
                'deskripsi'            => $array_input['keterangan'],
                'is_active'            => 1,
                'kepada'               => $array_input['kepada'],
            );

            $surat_perpanjang_rujukan_id = $this->surat_perpanjang_rujukan_m->save($data_surat_pengantar);

            if ($surat_perpanjang_rujukan_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data surat perpanjang rujukan berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("klinik_hd/surat_perpanjang_rujukan");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $rujukan_id = $this->surat_perpanjang_rujukan_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        // die_dump($max_id);
        
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 8,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->save($data_trash);
        // die_dump($this->db->last_query());
        
        if ($trash || $rujukan_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("klinik_hd/surat_perpanjang_rujukan");
    }

    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->cabang_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Branch Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("klinik_hd/surat_perpanjang_rujukan");
    }

    function cetak_perpanjang_rujukan($id) 
    {
        $this->load->library('mpdf/mpdf.php');

        $data_pengantar = $this->surat_perpanjang_rujukan_m->get($id);
        $data_pengantar = object_to_array($data_pengantar);

        $pasien = $this->pasien_m->get($data_pengantar['pasien_id']);
        $pasien = object_to_array($pasien);

        $alamat_pasien = $this->pasien_alamat_m->get_data($pasien['id'])->result_array();
        $data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $alamat_pasien[0]['kode_lokasi']),true);

        $form_kel_alamat  = '';
        $form_kec_alamat  = '';
        $form_kota_alamat = '';

        if(count($data_lokasi))
        {
            $form_kel_alamat  = $data_lokasi->nama_kelurahan;
            $form_kec_alamat  = $data_lokasi->nama_kecamatan;
            $form_kota_alamat = $data_lokasi->nama_kabupatenkota;            
        }

        $dokter = $this->user_m->get($data_pengantar['created_by']);
        $dokter = object_to_array($dokter);

        $body = array(
            'pengantar'        => $data_pengantar,
            'pasien'           => $pasien,
            'dokter'           => $dokter,
            'alamat_pasien'    => (count($alamat_pasien))?$alamat_pasien[0]:'',
            'form_kel_alamat'  => $form_kel_alamat,
            'form_kec_alamat'  => $form_kec_alamat,
            'form_kota_alamat' => $form_kota_alamat,
        );

        $mpdf = new mPDF('utf-8','A5', 0, '', 5, 5, 5, 0, 0, 0);
        $stylesheet = file_get_contents(base_url().'assets/mb/global/css/pdf_surat.css');
        $stylesheets = file_get_contents(base_url().'assets/metronic/global/plugins/bootstrap/css/bootstrap.css');
        $stylesheets_fa = file_get_contents(base_url().'assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css');
        $mpdf->writeHTML($stylesheet, 1);
        $mpdf->writeHTML($stylesheets, 1);
        $mpdf->writeHTML($stylesheets_fa, 1);
        $mpdf->writeHTML($this->load->view('klinik_hd/surat_perpanjang_rujukan/print_surat_perpanjang_rujukan', $body, true));

        $mpdf->Output('perpanjang_rujukan_'.$data_pengantar['no_surat'].'.pdf', 'I'); 
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
                if ($pasien->url_photo != '') 
                {
                    if (file_exists(FCPATH.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo) && is_file(FCPATH.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo)) 
                    {
                        $pasien->url_photo = base_url().config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo;
                    }
                    else
                    {
                        $pasien->url_photo = base_url().config_item('site_img_pasien').'global/global.png';
                    }
                } else {

                    $pasien->url_photo = base_url().config_item('site_img_pasien').'global/global.png';
                }


                $now = date('Y-m-d');
                $lahir = date('Y-m-d', strtotime($pasien->tanggal_lahir));

                $response->success = true;
                $response->msg = translate('Data Pasien Ditemukan', $this->session->userdata('language'));
                $response->rows = $pasien;
            }

            die(json_encode($response));

        }
    }


}

/* End of file surat_perpanjang_rujukan.php */
/* Location: ./application/controllers/klinik_hd/surat_perpanjang_rujukan.php */