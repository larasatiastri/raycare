<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_dokter_sppd extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd33ab6d11e792c09bde4168135777a05';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/surat_dokter_sppd_m');
        $this->load->model('klinik_hd/surat_dokter_sppd_foto_m');
        $this->load->model('klinik_hd/tindakan_hd_history_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
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
        $config = 'assets/klinik_hd/surat_dokter_sppd/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Surat Pengantar HD 3x', $this->session->userdata('language')), 
            'header'         => translate('Surat Pengantar HD 3x', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/surat_dokter_sppd/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function index()
    {
        $assets = array();
        $assets_config = 'assets/klinik_hd/surat_dokter_sppd/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah Surat Pengantar HD 3x", $this->session->userdata("language")), 
            'header'         => translate("Tambah Surat Pengantar HD 3x", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/surat_dokter_sppd/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/klinik_hd/surat_dokter_sppd/add';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
       
        $form_data = $this->surat_dokter_sppd_m->get_by(array('id' => $id), true);
        $form_data_gambar = $this->surat_dokter_sppd_foto_m->get_by(array('surat_dokter_sppd_id' => $id));

        $data = array(
            'title'          => config_item('site_name').' | '.translate("View Surat Pengantar HD 3x", $this->session->userdata("language")), 
            'header'         => translate("View Surat Pengantar HD 3x", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/surat_dokter_sppd/view',
            'form_data'      => object_to_array($form_data),
            'form_data_gambar'      => object_to_array($form_data_gambar),
            'pk_value'       => $id                         //table primary key value
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
        $result = $this->surat_dokter_sppd_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $status = '';
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/surat_dokter_sppd/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
           
            switch ($row['status']) {
                case '1':
                    $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                    break;
                case '2':
                    $status = '<div class="text-center"><span class="label label-md label-info">Disetujui</span></div>';
                    $action .= '<a title="'.translate('Cetak', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klinik_hd/surat_dokter_sppd/print_surat/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';

                    break;
                case '3':
                    $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
                    $action .= '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus surat ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
                    break;
                
                default:
                    break;
            }


            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['nama'],
                $row['dokter'],
                $row['diagnosa1'].', '.$row['diagnosa2'],
                $row['alasan'],
                $status,
                '<div class="text-center">'.$action.'</div>' 
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
                '<div class="text-center">'.$row['no_ktp'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                $row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['propinsi'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $command = $this->input->post('command');
        $dokter = $this->input->post('id_dokter');
        $tanggal = $this->input->post('tanggal');
        $pasien = $this->input->post('id_pasien');
        $diagnosa1 = $this->input->post('diagnosa1');
        $diagnosa2 = $this->input->post('diagnosa2');
        $alasan = $this->input->post('alasan');
        $url = $this->input->post('gambar');


        $array_input = $this->input->post();

        if ($command === 'add')
        {  
            $data = array(
                "dokter_id"     => $this->session->userdata('user_id'),
                "pasien_id"     => $pasien,
                "tanggal"       => date('Y-m-d'),
                "diagnosa1"     => $diagnosa1,
                "diagnosa2"     => $diagnosa2,
                "alasan"        => $alasan,
                "status"        => 1
            );
            // die_dump($data);
            // die_dump($this->input->post());
            $surat_id = $this->surat_dokter_sppd_m->save($data);
            
            foreach ($url as $gambar) {

                if($gambar['nama'] != ''){
                    $path_dokumen = '../cloud/'.config_item('site_dir').'pages/klinik_hd/surat_dokter_sppd/images/'.$surat_id;
                    if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                    $temp_filename = $gambar['nama'];

                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = $gambar['nama'];
                    $real_file = $surat_id.'/'.$new_filename;

                    copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_sppd').$real_file);

                    $data_gambar = array(
                        'surat_dokter_sppd_id'  => $surat_id,
                        'url'                   => $gambar['nama'],
                        'is_active'             => 1,
                    );

                    $surat_gambar_id = $this->surat_dokter_sppd_foto_m->save($data_gambar);
                }  
            } 

            if ($surat_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data cabang berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }

        redirect("klinik_hd/surat_dokter_sppd");
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
                    $url = explode('/', $pasien->url_photo);
                    if (file_exists(FCPATH.config_item('site_img_pasien').$url[0].'/'.$url[1]) && is_file(FCPATH.config_item('site_img_pasien').$url[0].'/'.$url[1])) 
                    {
                        $pasien->url_photo = base_url().config_item('site_img_pasien').$url[0].'/'.$url[1];
                    }
                    else
                    {
                        $pasien->url_photo = base_url().config_item('site_img_pasien').'global/global.png';
                    }
                } 
                else 
                {

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


    public function get_past_tindakan($pasien_id, $tanggal = NULL)
    {
        if($tanggal == NULL) $tanggal = date('Y-m-d');
        $data_tindakan = $this->tindakan_hd_history_m->get_data_tiga_bulan($pasien_id, $tanggal)->result_array();
        // die(dump($this->db->last_query()));
        $data_tindakan_tgl = $this->tindakan_hd_history_m->get_data_per_tanggal($pasien_id, $tanggal)->row(0);

        $xhtml = '';

        foreach ($data_tindakan as $tindakan) {
            $xhtml .= '<tr>';
            $xhtml .= '<td>'.date('D d M Y', strtotime($tindakan['tanggal'])).'</td>';
            $xhtml .= '<td>'.$tindakan['berat_awal'].'</td>';
            $xhtml .= '<td>'.$tindakan['berat_akhir'].'</td>';
            $xhtml .= '<td>'.$tindakan['time_of_dialysis'].'</td>';
            $xhtml .= '<td>'.$tindakan['quick_of_blood'].'</td>';
            $xhtml .= '<td>'.$tindakan['quick_of_dialysis'].'</td>';
            $xhtml .= '<td>'.$tindakan['uf_goal'].'</td>';
            $xhtml .='</tr>';
        }


        echo $xhtml;



    }

    function print_surat($id) 
    {
        $this->load->library('mpdf/mpdf.php');

        $data_surat_sppd = $this->surat_dokter_sppd_m->get_by(array('id' =>$id),true);
        $data_surat_sppd = object_to_array($data_surat_sppd);

        $pasien = $this->pasien_m->get($data_surat_sppd['pasien_id']);
        $pasien = object_to_array($pasien);

        $umur_pasien = date_diff(date_create($pasien['tanggal_lahir']), date_create('today'))->y.' Tahun';

        if ($umur_pasien < 1) {
            $umur_pasien = translate('Dibawah 1 tahun', $this->session->userdata('language'));
        }

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

        $dokter = $this->user_m->get($data_surat_sppd['created_by']);
        $dokter = object_to_array($dokter);

        $dokter_setuju = $this->user_m->get($data_surat_sppd['disetujui_oleh']);
        $dokter_setuju = object_to_array($dokter_setuju);

        $body = array(
            'data_surat_sppd'    => $data_surat_sppd,
            'pasien'            => $pasien,
            'dokter'            => $dokter,
            'dokter_setuju'            => $dokter_setuju,
            'umur_pasien'       => $umur_pasien,
            'alamat_pasien'     => (count($alamat_pasien))?$alamat_pasien[0]:'',
            'form_kel_alamat'   => $form_kel_alamat,
            'form_kec_alamat'   => $form_kec_alamat,
            'form_kota_alamat'  => $form_kota_alamat
        );

        $mpdf = new mPDF('utf-8','A5', 0, '', 5, 5, 5, 0, 0, 0);
        $stylesheet = file_get_contents(base_url().'assets/mb/global/css/pdf_surat.css');
        $stylesheets = file_get_contents(base_url().'assets/metronic/global/plugins/bootstrap/css/bootstrap.css');
        $stylesheets_fa = file_get_contents(base_url().'assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css');
        $mpdf->writeHTML($stylesheet, 1);
        $mpdf->writeHTML($stylesheets, 1);
        $mpdf->writeHTML($stylesheets_fa, 1);
        $mpdf->writeHTML($this->load->view('klinik_hd/surat_dokter_sppd/print_surat_sppd', $body, true));

        $mpdf->Output('keterangan_istirahat_'.$data_surat_sppd['no_surat'].'.pdf', 'I'); 
    }

}

/* End of file surat_dokter_sppd.php */
/* Location: ./application/controllers/klinik_hd/surat_dokter_sppd.php */