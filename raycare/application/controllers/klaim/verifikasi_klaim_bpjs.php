<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verifikasi_klaim_bpjs extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'a7530bdb1a8fe270d95ff9e2623a9444';                  // untuk check bit_access

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

        $this->load->model('klaim/proses_klaim/proses_klaim_m');        
        $this->load->model('klaim/proses_klaim/proses_klaim_tidak_layak_m');        
        $this->load->model('klinik_hd/tindakan_hd_m');        
        $this->load->model('master/pasien_m');       
        $this->load->model('master/petugas_bpjs_m');  
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klaim/verifikasi_klaim_bpjs/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Verifikasi Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Verifikasi Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/verifikasi_klaim_bpjs/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/klaim/verifikasi_klaim_bpjs/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Buat SEP Tindakan', $this->session->userdata('language')), 
            'header'         => translate('Buat SEP Tindakan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klaim/verifikasi_klaim_bpjs/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($flag)
    {        
        if($flag == 1)
        {
            $status = array('1','2','3');
        }
        if($flag == 2)
        {
            $status = array('4');
        }
        $result = $this->proses_klaim_m->get_datatable($status);

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

            if($row['status'] == 1 || $row['status'] == 2)
            {
                $action = '<a title="'.translate('Proses Verif', $this->session->userdata('language')).'" href="'.base_url().'klaim/verifikasi_klaim_bpjs/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
            }
            
            if($row['status'] == 3 || $row['status'] == 4)
            {
                $action = '<a title="'.translate('Print Dokumen', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'klaim/verifikasi_klaim_bpjs/cetak_dokumen/'.$row['id'].'" class="btn default"><i class="fa fa-print"></i></a>';
            }

            $output['data'][] = array(
                '<div class="text-left">'.date('F Y', strtotime($row['periode_tindakan'])).'</div>',
                '<div class="text-center">'.$row['no_surat'].'</div>',
                '<div class="text-left">'.$row['jumlah_tindakan'].'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_tarif_riil']).'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_tarif_ina']).'</div>',
                '<div class="text-left">'.$row['jumlah_tindakan_verif'].'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_tarif_ina_verif']).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['status'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_history()
    {        
        $result = $this->sep_tindakan_m->get_datatable();
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
            
            if($row['status'] != 5 && $row['is_active'] == 1)
            {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klaim/verifikasi_klaim_bpjs/view_proses/'.$row['id'].'/'.$row['pendaftaran_tindakan_id'].'"  data-target="#ajax_notes1" data-toggle="modal" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>';

                $status = '<div class="text-center"><span class="label label-md label-success">SEP Aktif</span></div>';
            }
            elseif ($row['status'] == 5 && $row['is_active'] == 1) {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klaim/verifikasi_klaim_bpjs/view_proses/'.$row['id'].'/'.$row['pendaftaran_tindakan_id'].'"  data-target="#ajax_notes1" data-toggle="modal" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data SEP ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i></a>';

                $status = '<div class="text-center"><span class="label label-md label-warning">Tindakan Dibatalkan</span></div>';
            }
            elseif ($row['status'] == 5 && $row['is_active'] == 0) {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klaim/verifikasi_klaim_bpjs/view_proses/'.$row['id'].'/'.$row['pendaftaran_tindakan_id'].'"  data-target="#ajax_notes1" data-toggle="modal" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>';

                $status = '<div class="text-center"><span class="label label-md label-danger">SEP Dihapus</span></div>';
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
                $row['id_sep'],
                $row['no_transaksi'],
                $row['no_sep'],
                '<div class="text-center">'.$row['tanggal_sep'].'</div>',
                $row['no_member'],
                $img_url.$row['nama_pasien'],
                $row['nama_penjamin'].' ('.$row['no_kartu'].')',
                $row['nama_dokter'],
                $row['dibuat_oleh'],
                $status,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_pasien()
    {
        
        $result = $this->pasien_m->get_datatable_pilih_pasien();

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
                '<div class="text-left">'.$row['no_ktp'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['alamat'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function proses($id)
    {   
        $assets = array();
        $config = 'assets/klaim/verifikasi_klaim_bpjs/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_klaim  = $this->proses_klaim_m->get($id);
        $data_klaim = object_to_array($data_klaim);
        // die(dump( $assets['css'] ));
        $klaim['status'] = 2;
        $this->proses_klaim_m->save($klaim, $id);

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Verifikasi Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Proses Verifikasi Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_klaim'     => $data_klaim,
            'content_view'   => 'klaim/verifikasi_klaim_bpjs/proses',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
       
    }

    public function get_tanggal_tindakan()
    {   
        if($this->input->is_ajax_request())
        {
            $pasien_id = $this->input->post('pasien_id');
            $periode_tindakan = $this->input->post('periode_tindakan');

            $response = new stdClass;
            $response->success = false;

            $data_tindakan = $this->tindakan_hd_m->get_data_tindakan($pasien_id,$periode_tindakan);

            if(count($data_tindakan))
            {
                $response->success = true;
                $response->rows = $data_tindakan;
            }

            die(json_encode($response));
        }
    }

    public function save()
    {
        $array_input = $this->input->post();
        // die_dump($array_input);
        if($array_input['command'] == 'edit')
        {
            $id = $array_input['proses_id'];

            $data_klaim = array(
                'jumlah_tindakan_verif'  => $array_input['jumlah_tindakan_verif'],
                'jumlah_tarif_ina_verif' => $array_input['jumlah_tarif_ina_verif'],
                'amhp_verif'             => 0,
                'biaya_lain_verif'       => 0,
                'status'                 => 3,
                'penerima_id'            => $array_input['penerima_id'],
                'verif_id'               => $array_input['verif_id'],
                'keterangan'             => $array_input['keterangan'],
                'no_fpk'             => $array_input['no_fpk'],
                'tanggal_proses'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal_proses']))
            );

            $proses_klaim_id = $this->proses_klaim_m->save($data_klaim, $id);

            if($array_input['verif'])
            {
                foreach ($array_input['verif'] as $verif) 
                {
                    if($verif['id_pasien'] != '')
                    {
                        $data_tidak_layak = array(
                            'proses_klaim_id'  => $id,
                            'pasien_id'        => $verif['id_pasien'],
                            'tanggal_tindakan' => date('Y-m-d', strtotime($verif['tanggal_tindakan'])),
                            'no_skp'           => $verif['no_skp'],
                            'ina_cbg'          => $verif['ina_cbg'],
                            'tarif'            => $verif['tarif'],
                            'ahmp'             => $verif['amhp'],
                            'biaya_lain'       => $verif['biaya_lain'],
                            'total'            => $verif['total'],
                            'keterangan'       => $verif['keterangan'],
                            'is_active'        =>  1
                        );

                        $proses_tidak_layak_id = $this->proses_klaim_tidak_layak_m->save($data_tidak_layak);
                    }
                }
            }

            if($proses_klaim_id)
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data klaim berhasil diverifikasi.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            redirect("klaim/verifikasi_klaim_bpjs");
        }
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->cabang_m->save($data, $id);

        redirect("klaim/verifikasi_klaim_bpjs/history");
    }

    public function cetak_dokumen($id)
    {
        $this->load->library('mpdf/mpdf.php');

        $data_proses      = $this->proses_klaim_m->get($id);
        $data_tidak_layak = $this->proses_klaim_tidak_layak_m->get_data($id);
        $data_tidak_layak = object_to_array($data_tidak_layak);


        $body = array(
            'data_proses'      => object_to_array($data_proses),
            'form_cabang'      => $form_cabang,
            'cabang_alamat'    => $cabang_alamat,
            'cabang_telepon'   => $cabang_telepon,
            'cabang_fax'       => $cabang_fax,
            'cabang_email'     => $cabang_email,
            'cabang_fb'        => $cabang_fb,
            'cabang_twitter'   => $cabang_twitter,
            'cabang_website'   => $cabang_website,
            'data_email'       => $data_email,
            'data_tidak_layak' => $data_tidak_layak,
        );

        $mpdf = new mPDF('utf-8','A4', 0, '', 10, 10, 10, 5, 0, 0);

        $mpdf->writeHTML($this->load->view('klaim/verifikasi_klaim_bpjs/print_berita_acara', $body, true));
        $mpdf->AddPage('L');
        $mpdf->writeHTML($this->load->view('klaim/verifikasi_klaim_bpjs/print_faskes', $body, true));
        $mpdf->AddPage('L');
        $mpdf->writeHTML($this->load->view('klaim/verifikasi_klaim_bpjs/print_klaim_tidak_layak', $body, true));
        $mpdf->AddPage('L');
        $mpdf->writeHTML($this->load->view('klaim/verifikasi_klaim_bpjs/print_berita_acara2', $body, true));
        $mpdf->AddPage('L');
        $mpdf->writeHTML($this->load->view('klaim/verifikasi_klaim_bpjs/print_umpan_balik', $body, true));


        $mpdf->Output('Dokumen_'.$data_proses->no_surat.'.pdf', 'I');
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */