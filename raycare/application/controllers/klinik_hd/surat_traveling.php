<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_traveling extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '0dd571acded79f3bcc075b97fcb68018';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/surat_traveling_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
       
    }
    
    public function history()
    {
        $assets = array();
        $config = 'assets/klinik_hd/surat_traveling/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Surat Traveling', $this->session->userdata('language')), 
            'header'         => translate('Surat Traveling', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/surat_traveling/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function index($pasien_id=null)
    {
        $assets = array();
        $assets_config = 'assets/klinik_hd/surat_traveling/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah Surat Traveling", $this->session->userdata("language")), 
            'header'         => translate("Tambah Surat Traveling", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/surat_traveling/add',
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
        $config = 'assets/klinik_hd/surat_traveling/view';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $form_data            = $this->surat_traveling_m->get($id);
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
            'content_view'         => 'klinik_hd/surat_traveling/view',
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
        $result = $this->surat_traveling_m->get_datatable();

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

        foreach($records->result_array() as $row)
        {
           
            if ($row['is_active'] == '1')
            {
                $action = '
                    <a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/surat_traveling/view/'.$row['id'].'" class="btn btn-xs grey-cascade hidden"><i class="fa fa-search"></i></a>
                    <a target="_blank" title="'.translate('Cetak', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/surat_traveling/cetak_surat_traveling/'.$row['id'].'" class="btn btn-xs default"><i class="fa fa-print"></i></a>
                    <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data rujukan ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red hidden"><i class="fa fa-times"></i> </a>';
            
            }
    
            if ($row['url_photo'] != '') 
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
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

    public function save()
    {
        $array_input = $this->input->post();
        $cabang_login = $this->cabang_m->get($this->session->userdata('cabang_id'));
        $data_cabang = $this->cabang_m->get_by('tipe in (0,1)');
        // die(dump($array_input));
        if ($array_input['command'] === 'add')
        {  
            $roman_month = romanic_number(date('m'), true);
            $max_no = $this->surat_traveling_m->get_max_no($roman_month)->result_array();

            if(count($max_no))
            {
                $last_nosurat = intval($max_no[0]['max_no']) + 1;
            }
            else
            {
                $last_nosurat = 1;
            }

            $format       = '#RODP#%03d';
            $no_surat     = sprintf($format, $last_nosurat, 3);
            $no_surat_new = $no_surat.'#RHS#'.$roman_month.'#'.date('Y').'#'.$cabang_login->kode;
            
            $cdl          = (isset($array_input['cdl']))?1:0;
            $av_shunt     = (isset($array_input['av_shunt']))?1:0;
            $femoral      = (isset($array_input['femoral']))?1:0;
            
            $monday       = (isset($array_input['monday']))?1:0;
            $tuesday      = (isset($array_input['tuesday']))?1:0;
            $wednesday    = (isset($array_input['wednesday']))?1:0;
            $thursday     = (isset($array_input['thursday']))?1:0;
            $friday       = (isset($array_input['friday']))?1:0;
            $saturday     = (isset($array_input['saturday']))?1:0;
            $sunday       = (isset($array_input['sunday']))?1:0;


            $rs_tujuan = $array_input['rs_tujuan'];
            if($array_input['tipe_pindah'] == 1){
                $cabang = $this->cabang_m->get_by(array('id' => $array_input['rs_tujuan_internal']), true);
                $rs_tujuan = $cabang->nama;

                $data_pasien = array(
                    'cabang_id'      => $array_input['rs_tujuan_internal'],
                    'tanggal_daftar' => date('Y-m-d', strtotime($array_input['tanggal_surat'])),
                );

                $all_cabang = $this->cabang_m->get_by(array('is_active' => 1));

                foreach ($all_cabang as $all_cbg) {
                    $path_model = 'master/pasien_m';
                    $pasien_penjamin_id = insert_data_api($data_pasien,$all_cbg->url,$path_model,$array_input['id_ref_pasien']);
                }
            }

            $data_surat_traveling = array(
                'pasien_id'                     => $array_input['id_ref_pasien'],
                'cabang_id'                     => $this->session->userdata('cabang_id'),
                'no_surat'                      => $no_surat_new,
                'alasan'                        => $array_input['alasan'],
                'rs_tujuan'                     => $rs_tujuan,
                'status'                        => 0,
                'lama_traveling'                => $array_input['lama_traveling'],
                'jenis_lama'                    => $array_input['jenis_lama'],
                'alasan_traveling'              => $array_input['alasan_traveling'],
                'alasan_pindah'                 => $array_input['alasan_pindah'],
                'tanggal_surat'                 => date('Y-m-d H:i:s', strtotime($array_input['tanggal_surat'])),
                'date_of_first'                 => date('Y-m-d H:i:s', strtotime($array_input['date_of_first'])),
                'date_of_inition'               => date('Y-m-d H:i:s', strtotime($array_input['date_of_inition'])),
                'vascular_access'               => $cdl.$av_shunt.$femoral,
                'frequency_of_hemodialysis'     => $monday.$tuesday.$wednesday.$thursday.$friday.$saturday.$sunday,
                'body_weight_min'               => $array_input['min'],
                'body_weight_max'               => $array_input['max'],
                'body_dry_weight'               => $array_input['dry'],
                'lab_ur'                        => $array_input['ur'],
                'lab_cr'                        => $array_input['cr'],
                'lab_hb'                        => $array_input['hb'],
                'lab_hbsag'                     => $array_input['hbsag'],
                'lab_anti_hcv'                  => $array_input['hcv'],
                'lab_anti_hiv'                  => $array_input['hiv'],
                'blood_group'                   => $array_input['blood_group'],
                'total_heparin_dose'            => $array_input['total_heparin_dose'],
                'initial'                       => $array_input['initial'],
                'hourly'                        => $array_input['hourly'],
                'blood_flow'                    => $array_input['blood_flow'],
                'medication'                    => $array_input['medication'],
                'complication_on_hemodialysis'  => $array_input['complication'],
                'remarks'                       => $array_input['remarks'],
                'is_active'                     => 1,
                'is_manual'                     => 0
            );

            $surat_traveling_id = $this->surat_traveling_m->save($data_surat_traveling);

            $data_pasien = array(
                'status' => 3
            );

            $path_model = 'master/pasien_m';
            $save_item = insert_data_api($data_pasien,base_url(),$path_model,$array_input['id_ref_pasien']);
            $inserted_save_item = $save_item;

            foreach ($data_cabang as $cabang) 
            {
                if($cabang->is_active == 1)
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $save_item = insert_data_api($data_pasien,$cabang->url,$path_model,$array_input['id_ref_pasien']);
                    }
                    
                }
            }

            if($array_input['alasan'] == 1){
                $data_jadwal['is_active'] = 0;
                $wheres = array(
                    'pasien_id'                 => $array_input['id_ref_pasien'],
                    'date(jadwal.tanggal) >'    => date('Y-m-d')
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
            }
            
            if ($surat_traveling_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Surat Traveling berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("klinik_hd/surat_traveling");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $rujukan_id = $this->surat_traveling_m->save($data, $id);

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
        redirect("klinik_hd/surat_traveling");
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
        redirect("klinik_hd/surat_traveling");
    }

    function cetak_surat_traveling($id) 
    {
        $this->load->library('mpdf/mpdf.php');

        $data_traveling = $this->surat_traveling_m->get($id);
        $data_traveling = object_to_array($data_traveling);

        $pasien = $this->pasien_m->get($data_traveling['pasien_id']);
        $pasien = object_to_array($pasien);

        $umur_pasien = date_diff(date_create($pasien['tanggal_lahir']), date_create('today'))->y.' y.o ';

        if ($umur_pasien < 1) {
            $umur_pasien = translate('Under 1 y.o ', $this->session->userdata('language'));
        }

        $form_pekerjaan   = $this->info_umum_m->get($pasien['pekerjaan_id']);
        $nama_pekerjaan = (count($form_pekerjaan)!=0)?$form_pekerjaan->nama:'-';

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

        $dokter = $this->user_m->get($data_traveling['created_by']);
        $dokter = object_to_array($dokter);

        $body = array(
            'data_traveling'    => $data_traveling,
            'pasien'            => $pasien,
            'dokter'            => $dokter,
            'nama_pekerjaan'    => $nama_pekerjaan,
            'umur_pasien'       => $umur_pasien,
            'alamat_pasien'     => (count($alamat_pasien))?$alamat_pasien[0]:'',
            'form_kel_alamat'   => $form_kel_alamat,
            'form_kec_alamat'   => $form_kec_alamat,
            'form_kota_alamat'  => $form_kota_alamat
        );

        $mpdf = new mPDF('utf-8','A4', 0, '', 10, 10, 10, 0, 0, 0);
        $mpdf->writeHTML($this->load->view('klinik_hd/surat_traveling/print_surat_traveling', $body, true));

        $mpdf->Output('surat_traveling_'.$data_traveling['no_surat'].'.pdf', 'I'); 
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
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo) && is_file($_SERVER['DOCUMENT_ROOT'] .'/'.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo)) 
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

/* End of file surat_traveling.php */
/* Location: ./application/controllers/klinik_hd/surat_traveling.php */