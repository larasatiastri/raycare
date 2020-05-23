<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_cek_lab extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '15851406ed920e1fbbfd2b4c994c4577';                  // untuk check bit_access

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

        $this->load->model('master/kategori_pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_m');
        $this->load->model('master/pemeriksaan_lab_detail_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('tindakan/tindakan_lab_m');
        $this->load->model('tindakan/tindakan_lab_detail_m');    
        $this->load->model('tindakan/hasil_lab_m');
        $this->load->model('tindakan/hasil_lab_detail_m');
        $this->load->model('tindakan/hasil_lab_dokumen_m');

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/laboratorium/tindakan_cek_lab/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Daftar Tindakan Lab', $this->session->userdata('language')), 
            'header'         => translate('Daftar Tindakan Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laboratorium/tindakan_cek_lab/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/laboratorium/tindakan_cek_lab/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Tindakan Lab', $this->session->userdata('language')), 
            'header'         => translate('History Tindakan Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laboratorium/tindakan_cek_lab/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/laboratorium/tindakan_cek_lab/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data_tindakan_lab = $this->tindakan_lab_m->get_by(array('id' => $id), true);
        $data_tindakan_lab_detail = $this->tindakan_lab_detail_m->get_by(array('tindakan_lab_id' => $id));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('View Tindakan Lab', $this->session->userdata('language')), 
            'header'         => translate('View Tindakan Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_tindakan_lab' => object_to_array($data_tindakan_lab),
            'data_tindakan_lab_detail' => object_to_array($data_tindakan_lab_detail),
            'pk_value'      => $id,
            'content_view'   => 'laboratorium/tindakan_cek_lab/view',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_proses($id)
    {
        $assets = array();
        $config = 'assets/laboratorium/tindakan_cek_lab/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data_tindakan_lab = $this->tindakan_lab_m->get_by(array('id' => $id), true);

        $data_hasil_lab = $this->hasil_lab_m->get_by(array('tindakan_lab_id' => $id), true);
        $data_hasil_lab_detail = $this->hasil_lab_detail_m->get_by(array('hasil_lab_id' => $data_hasil_lab->id));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('View Tindakan Lab', $this->session->userdata('language')), 
            'header'         => translate('View Tindakan Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_tindakan_lab' => object_to_array($data_tindakan_lab),
            'data_hasil_lab_detail' => object_to_array($data_hasil_lab_detail),
            'pk_value'      => $id,
            'content_view'   => 'laboratorium/tindakan_cek_lab/view_proses',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses($id)
    {
        $assets = array();
        $config = 'assets/laboratorium/tindakan_cek_lab/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data_tindakan_lab = $this->tindakan_lab_m->get_by(array('id' => $id), true);
        $data_tindakan_lab_detail = $this->tindakan_lab_detail_m->get_by(array('tindakan_lab_id' => $id));

        $data = array(
            'title'          => config_item('site_name').' | '.translate('View Tindakan Lab', $this->session->userdata('language')), 
            'header'         => translate('View Tindakan Lab', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_tindakan_lab' => object_to_array($data_tindakan_lab),
            'data_tindakan_lab_detail' => object_to_array($data_tindakan_lab_detail),
            'pk_value'      => $id,
            'content_view'   => 'laboratorium/tindakan_cek_lab/proses',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($tipe)
    {        
        $result = $this->tindakan_lab_m->get_datatable($tipe);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $status = '';
            $tipe_pasien = ($row['tipe_pasien'] == 1)?'Pasien Klinik':'Pasien Umum';
        
            
            switch ($row['status']) {
                case 1:
                    $status = '<div class="text-left"><span class="label label-warning">Menunggu Pembayaran</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'laboratorium/tindakan_cek_lab/view/'.$row['id'].'"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Batal', $this->session->userdata('language')).'" class="btn btn-danger batal" name="delete[]" data-id="'.$row['id'].'" data-confirm="Anda yakin akan membatalkan tindakan lab ini?"><i class="fa fa-times"></i></a>';
            
                    break; 

                case 2:
                    $status = '<div class="text-left"><span class="label label-info">Menunggu Diproses</span></div>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" class="btn btn-success" href="'.base_url().'laboratorium/tindakan_cek_lab/proses/'.$row['id'].'"><i class="fa fa-check"></i></a>';
                    break;

                case 3:
                    $status = '<div class="text-left"><span class="label label-success">Selesai Diproses</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'laboratorium/tindakan_cek_lab/view_proses/'.$row['id'].'"><i class="fa fa-search"></i></a>';
                    break;

                case 4:
                    $status = '<div class="text-left"><span class="label label-danger">Dibatalkan</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'laboratorium/tindakan_cek_lab/view/'.$row['id'].'"><i class="fa fa-search"></i></a>';
                    break;
                
                default:
                    break;
            }

            $output['data'][] = array(
                '<div class="text-left inline-button-table">'.$row['no_pemeriksaan'].'</div>',
                '<div class="text-left inline-button-table">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left inline-button-table">'.$tipe_pasien.'</div>',
                '<div class="text-left">'.$row['nama_pasien'].' / '.$row['jenis_kelamin'].'</div>',
                '<div class="text-left inline-button-table">'.date('d M Y', strtotime($row['tanggal_lahir'])).' / '.$row['umur_pasien'].'</div>',
                '<div class="text-left">'.$row['no_telp_pasien'].'</div>',
                '<div class="text-left">'.$row['nama_dokter'].'</div>',
                '<div class="text-center inline-button-table">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();

        // die(dump($array_input));

        if($array_input['command'] === 'add'){

            $last_number   = $this->hasil_lab_m->get_max_id_hasil_lab()->result_array();
            $last_number   = intval($last_number[0]['max_id'])+1;
            $format        = 'HL-'.date('m-Y').'%04d';
            $id_hasil     = sprintf($format, $last_number, 4);

            $datetime1 = new DateTime($array_input['tanggal_periksa']);
            $datetime2 = new DateTime($array_input['tanggal_lahir']);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%y');
            $umur = intval($elapsed);

            $kategori_umur = '';
            if($umur >= 0 AND $umur <= 1){
                $kategori_umur = '1';
            }if($umur > 1 AND $umur <= 12){
                $kategori_umur = '2';
            }if($umur >= 13 AND $umur <= 50){
                $kategori_umur = '3';
            }if($umur > 50){
                $kategori_umur = '4';
            }


            $data = array(
                'id'                        => $id_hasil,
                'tanggal'                   => date('Y-m-d', strtotime($array_input['tanggal_periksa'])),
                'jenis'                     => 2,
                'no_hasil_lab'              => $array_input['no_pemeriksaan'],
                'tindakan_lab_id'    => $array_input['tindakan_lab_id'],
                'laboratorium_klinik_id'    => $array_input['laboratorium_klinik_id'],
                'tipe_pasien'                 => $array_input['tipe_pasien'],
                'pasien_id'                 => $array_input['pasien_id'],
                'nama_pasien'                 => $array_input['nama_pasien'],
                'tanggal_lahir'                 => date('Y-m-d', strtotime($array_input['tanggal_lahir'])),
                'usia'                      => $array_input['umur_pasien'],
                'kategori_usia_id'          => $kategori_umur,
                'dokter'                    => $array_input['nama_dokter'],
                'is_active'                 => 1,
                'created_by'                => $this->session->userdata('user_id'),
                'created_date'              => date('Y-m-d H:i:s')
            );

            $save_hasil_lab = $this->hasil_lab_m->add_data($data);

            foreach ($array_input['item'] as $key => $item) {

                $last_number_detail   = $this->hasil_lab_detail_m->get_max_id_hasil_lab_detail()->result_array();
                $last_number_detail   = intval($last_number_detail[0]['max_id'])+1;
                $format_detail        = 'HLD-'.date('m-Y').'%04d';
                $id_hasil_detail     = sprintf($format_detail, $last_number_detail, 4);

                $data_detail = array(
                    'id'                        => $id_hasil_detail,
                    'hasil_lab_id'              => $id_hasil,
                    'pemeriksaan_lab_id'               => $item['pemeriksaan_lab_id'],
                    'pemeriksaan'               => $item['pemeriksaan'],
                    'hasil'                     => $item['hasil'],
                    'nilai_normal'              => $item['nilai_normal'],
                    'satuan'                    => $item['satuan'],
                    'keterangan'                => $item['keterangan'],
                    'is_active'                 => 1,
                    'created_by'                => $this->session->userdata('user_id'),
                    'created_date'              => date('Y-m-d H:i:s')
                );

                $save_hasil_lab_detail = $this->hasil_lab_detail_m->add_data($data_detail);
            }

            $data_lab = array(
                'status' => 3
            );

            $edit_tindakan_lab = $this->tindakan_lab_m->edit_data($data_lab, $array_input['tindakan_lab_id']);

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Tindakan cek lab berhasil diproses", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

            redirect('laboratorium/tindakan_cek_lab');
        }
        
    }

    public function delete($id)
    {
        $data = array(
            'status' => 4
        );

        $edit_tindakan_lab = $this->tindakan_lab_m->edit_data($data, $id);

        $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Tindakan cek lab dibatalkan", $this->session->userdata("language")),
            "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);

        redirect("laboratorium/tindakan_cek_lab");

    }

}

/* End of file surat_dokter_sppd.php */
/* Location: ./application/controllers/laboratorium/tindakan_cek_lab.php */