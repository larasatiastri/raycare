<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buat_sep extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '23d5b28ecd18d00530d8e6f3aa04d8b7';                  // untuk check bit_access

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

        $this->load->model('klaim/buat_sep/tindakan_hd_m');        
        $this->load->model('klaim/buat_sep/sep_tindakan_m');        
        $this->load->model('master/pasien_penjamin_m');        
        $this->load->model('master/cabang_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('others/jenis_peserta_bpjs_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klaim/buat_sep/index';
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
            'content_view'   => 'klaim/buat_sep/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/klaim/buat_sep/index';
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
            'content_view'   => 'klaim/buat_sep/history',
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
        $result = $this->tindakan_hd_m->get_datatable();
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            
            $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'klaim/buat_sep/proses/'.$row['id'].'/'.$row['pendaftaran_tindakan_id'].'/'.$row['pasien_penjamin_id'].'"  data-target="#ajax_notes1" data-toggle="modal" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></a>';

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
                $row['pendaftaran_tindakan_id'],
                $row['no_transaksi'],
                '<div class="text-center">'.$row['tanggal'].'</div>',
                $row['no_member'],
                $img_url.$row['nama_pasien'],
                $row['nama_penjamin'].' ('.$row['no_kartu'].')',
                $row['nama_dokter'],
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
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klaim/buat_sep/view_proses/'.$row['id'].'/'.$row['pendaftaran_tindakan_id'].'"  data-target="#ajax_notes1" data-toggle="modal" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>';

                $status = '<div class="text-center"><span class="label label-md label-success">SEP Aktif</span></div>';
            }
            elseif ($row['status'] == 5 && $row['is_active'] == 1) {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klaim/buat_sep/view_proses/'.$row['id'].'/'.$row['pendaftaran_tindakan_id'].'"  data-target="#ajax_notes1" data-toggle="modal" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data SEP ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i></a>';

                $status = '<div class="text-center"><span class="label label-md label-warning">Tindakan Dibatalkan</span></div>';
            }
            elseif ($row['status'] == 5 && $row['is_active'] == 0) {
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'klaim/buat_sep/view_proses/'.$row['id'].'/'.$row['pendaftaran_tindakan_id'].'"  data-target="#ajax_notes1" data-toggle="modal" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>';

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

    public function proses($id, $pendaftaran_tindakan_id, $pasien_penjamin_id)
    {   

        $data_sep = $this->sep_tindakan_m->get_by(array('tindakan_id' => $pendaftaran_tindakan_id, 'tipe_tindakan' => 0), true);
        if(count($data_sep))
        {
            $data_sep = object_to_array($data_sep);
        }
        else
        {
            $data_sep = '';
        }

        $data = array(
            'tindakan_hd_id'          => $id,
            'pendaftaran_tindakan_id' => $pendaftaran_tindakan_id,
            'pasien_penjamin_id'      => $pasien_penjamin_id,
            'data_sep'                => $data_sep
        );
        $this->load->view('klaim/buat_sep/modals/proses.php', $data);
    }

    public function view_proses($id, $pendaftaran_tindakan_id)
    {   
        $data = array(
            'tindakan_hd_id'    => $id,
            'pendaftaran_tindakan_id'   => $pendaftaran_tindakan_id
        );
        $this->load->view('klaim/buat_sep/modals/view_proses.php', $data);
    }

    public function save()
    {
        if ($this->input->is_ajax_request()){
            $array_input = $this->input->post();
            // die_dump($array_input);

            $data_cabang = $this->cabang_m->get_cabang()->result();
            // die_dump($data_cabang);
            $upd_array = array(
                'is_sep'    => 1
            );
            $upd_tdk = $this->tindakan_hd_m->save($upd_array, $array_input['tindak_hd_id']);

            $get_status = $this->tindakan_hd_m->get($array_input['tindak_hd_id']);
            // die_dump($get_status->status);

            if($get_status->status == '1' || $get_status->status == '2')
            {
                // die_dump('a');
                $data_sep_tindakan = array(
                    'poliklinik_id' => $array_input['jenis_poli'],
                    'tindakan_id'   => $array_input['tindak_hd_id'],
                    'tipe_tindakan' => 1,
                    'no_sep'        => $array_input['no_sep'],
                    'tanggal_sep'   => date('Y-m-d', strtotime($array_input['tanggal'])),
                    'diagnosa_awal' => $array_input['kd_diagnosa'],
                    'jenis_peserta' => $array_input['jenis_peserta'],
                    'cob'           => $array_input['cob'],
                    'jenis_rawat'   => $array_input['jenis_rawat'],
                    'kelas_rawat'   => $array_input['kelas_rawat'],
                    'catatan'       => $array_input['catatan'],
                    'cetakan_ke'    => $array_input['cetakan_ke'],
                    'tanggal_cetak' => $array_input['tgl'].'-'.$array_input['bln'].'-'.$array_input['thn'].' '.$array_input['jam'].':'.$array_input['mnt'].':'.$array_input['dtk'].' '.$array_input['am_pm'],
                    'is_active'     => 1
                );

                $path_model = 'klaim/buat_sep/sep_tindakan_m';
                
                $sep_tindakan_id = insert_data_api($data_sep_tindakan,base_url(),$path_model, $array_input['sep_tindakan_id']);
                // die_dump($sep_tindakan_id);

                $inserted_sep_tindakan_id = $sep_tindakan_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $sep_tindakan_id = insert_data_api($data_sep_tindakan,$cabang->url,$path_model,$inserted_sep_tindakan_id);
                    }
                }

                $data_pasien_penjamin = array(
                    'no_kartu' => $array_input['no_bpjs'],
                );

                $path_model = 'master/pasien_penjamin_m';
                
                $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model, $array_input['pasien_penjamin_id']);
                $inserted_pasien_penjamin_id = $pasien_penjamin_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $sep_tindakan_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$inserted_pasien_penjamin_id);
                    }
                }

            }
            else if($get_status->status == '3')
            {
                   $data_sep_tindakan = array(
                    'poliklinik_id' => $array_input['jenis_poli'],
                    'tindakan_id'   => $array_input['tindak_hd_id'],
                    'tipe_tindakan' => 1,
                    'no_sep'        => $array_input['no_sep'],
                    'tanggal_sep'   => date('Y-m-d', strtotime($array_input['tanggal'])),
                    'diagnosa_awal' => $array_input['kd_diagnosa'],
                    'jenis_peserta' => $array_input['jenis_peserta'],
                    'cob'           => $array_input['cob'],
                    'jenis_rawat'   => $array_input['jenis_rawat'],
                    'kelas_rawat'   => $array_input['kelas_rawat'],
                    'catatan'       => $array_input['catatan'],
                    'is_active'     => 1
                );

                $path_model = 'klaim/buat_sep/sep_tindakan_m';
                
                $sep_tindakan_id = insert_data_api($data_sep_tindakan,base_url(),$path_model, $array_input['sep_tindakan_id']);
                $inserted_sep_tindakan_id = $sep_tindakan_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $sep_tindakan_id = insert_data_api($data_sep_tindakan,$cabang->url,$path_model,$inserted_sep_tindakan_id);
                    }
                }

                $data_pasien_penjamin = array(
                    'no_kartu' => $array_input['no_bpjs'],
                );

                $path_model = 'master/pasien_penjamin_m';
                
                $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,base_url(),$path_model, $array_input['pasien_penjamin_id']);
                $inserted_pasien_penjamin_id = $pasien_penjamin_id;

                foreach ($data_cabang as $cabang) 
                {
                    if($cabang->url != '' || $cabang->url != NULL)
                    {
                        $pasien_penjamin_id = insert_data_api($data_pasien_penjamin,$cabang->url,$path_model,$inserted_pasien_penjamin_id);
                    }
                }

                $data_pembayaran_transaksi = array(
                    'status' => 2,
                );

                $wheres = array(
                    'transaksi_id'  => $array_input['tindak_hd_id'],
                    'tipe'      => 1
                );
                $path_model = 'reservasi/pembayaran/os_pembayaran_transaksi_m';

                foreach ($data_cabang as $cabang) {
                    if($cabang->tipe == 0)
                    {
                        $sep_tindakan_id = update_data_api($data_pembayaran_transaksi,$cabang->url,$path_model,$wheres);
                    }
                }
            }
            die(json_encode($upd_tdk));
        }
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->cabang_m->save($data, $id);

        redirect("klaim/buat_sep/history");
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */