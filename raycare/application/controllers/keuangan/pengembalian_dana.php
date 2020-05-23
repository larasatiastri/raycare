<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengembalian_dana extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '955e723376d48793069667e76f588eec';                  // untuk check bit_access

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
    
        $this->load->model('keuangan/pengajuan_pemegang_saham_m');        
        $this->load->model('keuangan/pembayaran_masuk/pembayaran_masuk_status_m');
        $this->load->model('keuangan/pembayaran_masuk/voucher_m'); 
        $this->load->model('keuangan/pembayaran_status/voucher_m'); 
        $this->load->model('keuangan/arus_kas_bank/keuangan_arus_kas_m');        
        $this->load->model('keuangan/arus_kas_bank/rekening_koran_m');
        $this->load->model('master/bank_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/pengembalian_dana/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengembalian Dana', $this->session->userdata('language')), 
            'header'         => translate('Pengembalian Dana', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengembalian_dana/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/keuangan/pengembalian_dana/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengembalian Dana', $this->session->userdata('language')), 
            'header'         => translate('Pengembalian Dana', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengembalian_dana/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $assets = array();
        $config = 'assets/keuangan/pengembalian_dana/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_pps = $this->pengajuan_pemegang_saham_m->get_by(array('id' => $id), true);
        $data_pps = object_to_array($data_pps);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengembalian Dana', $this->session->userdata('language')), 
            'header'         => translate('Pengembalian Dana', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_pps'       => $data_pps,
            'content_view'   => 'keuangan/pengembalian_dana/edit',
            'pk_value'       => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function save()
    {
        $array_input = $this->input->post();
        $user_id = $this->session->userdata('user_id');
        // die(dump($array_input));

        if($array_input['command'] === 'add'){
            $last_id = $this->pengajuan_pemegang_saham_m->get_max_id()->result_array();
            $last_id = intval($last_id[0]['max_id'])+1;

            $format_id   = 'PPS-'.date('my').'-%03d';
            $id_pps    = sprintf($format_id, $last_id, 3);

            $last_number = $this->pengajuan_pemegang_saham_m->get_max_nomor()->result_array();
            $last_number = intval($last_number[0]['max_id'])+1;

            $format_number   = 'PPS/'.date('my').'/%03d';
            $number_pps    = sprintf($format_number, $last_number, 3);

            $data_pps = array(
                'id' => $id_pps,
                'tanggal' => date('Y-m-d', strtotime($array_input['tanggal'])),
                'nomor_pengajuan' => $number_pps,
                'nominal' => $array_input['nominal_show'],
                'keterangan' => $array_input['keterangan'],
                'bank_id' => $array_input['bank_id'],
                'nama_bank_pengirim' => $array_input['nama_bank_pengirim'],
                'nomor_rekening_pengirim' => $array_input['nomor_rekening_pengirim'],
                'atas_nama_pengirim' => $array_input['atas_nama_pengirim'],
                'url' => $array_input['url'],
                'status' => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s')
            );

            if($array_input['url'] != ''){
                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/pengembalian_dana/images/'.$id_pps;
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $array_input['url'];
                $real_file = $id_pps.'/'.$new_filename;

                copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pengajuan_ps').$real_file);
                unlink(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename);
            }

            $save_pps = $this->pengajuan_pemegang_saham_m->add_data($data_pps);

            $last_id_bayar_masuk = $this->pembayaran_masuk_status_m->get_max_id_pembayaran()->result_array();
            $last_id_bayar_masuk = intval($last_id_bayar_masuk[0]['max_id'])+1;

            $format_id_detail_kirim   = 'PMS-'.date('m').'-'.date('Y').'-%04d';
            $id_bayar_masuk       = sprintf($format_id_detail_kirim, $last_id_bayar_masuk, 4);

            $data_pembayaran_masuk = array(
                'id' => $id_bayar_masuk,
                'transaksi_id' => $id_pps,
                'transaksi_nomor' => $number_pps,
                'tipe_transaksi' => 2,
                'status' => 1,
                'status_keuangan' => 1,
                'user_level_id' => 5,
                'divisi' => 7,
                'nominal' => $array_input['nominal_show'],
                'waktu_akhir' => date('Y-m-d', strtotime('+7 days')),
                'is_active' => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s'),
            );

            $add_bayar_masuk = $this->pembayaran_masuk_status_m->add_data($data_pembayaran_masuk);

            if($id_pps){
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Pengembalian Dana Berhasil Ditambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }
        if($array_input['command'] === 'edit'){

            $id_pps = $array_input['id'];

            $data_pps = array(
                'tanggal' => date('Y-m-d', strtotime($array_input['tanggal'])),
                'nominal' => $array_input['nominal_show'],
                'keterangan' => $array_input['keterangan'],
                'bank_id' => $array_input['bank_id'],
                'nama_bank_pengirim' => $array_input['nama_bank_pengirim'],
                'nomor_rekening_pengirim' => $array_input['nomor_rekening_pengirim'],
                'atas_nama_pengirim' => $array_input['atas_nama_pengirim'],
                'url' => $array_input['url'],
                'status' => 1,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            if($array_input['url'] != ''){
                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/pengembalian_dana/images/'.$id_pps;
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $array_input['url'];
                $real_file = $id_pps.'/'.$new_filename;

                copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pengajuan_ps').$real_file);
                unlink(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename);
            }

            $edit_pps = $this->pengajuan_pemegang_saham_m->add_data($data_pps, $id_pps);

            $data_pem_masuk = array(
                'status' => 1,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')   
            );

            $where_pem_masuk = array(
                'transaksi_id' => $id_pps,
                'tipe_transaksi' => 2,
            );

            $edit_pembayaran_masuk = $this->pembayaran_masuk_status_m->edit_data($user_id, $data_pem_masuk, $where_pem_masuk);

            if($id_pps){
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Pengembalian Dana Berhasil Ditambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }

        redirect('keuangan/pengembalian_dana');       
    }

    public function listing(){  
        
        $result = $this->pengajuan_pemegang_saham_m->get_datatable();

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
            $status = '';
            $action = '';

            if($row['status'] == 2){
                $status = '<div class="text-center"><span class="label label-md label-danger">Menunggu Pengembalian</span></div>';
                $action ='<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pengembalian_dana/edit/'.$row['id'].'"  class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
            
            }elseif($row['status'] == 1){

                $status = '<div class="text-center"><span class="label label-md label-info">Selesai</span></div>';
                $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pengembalian_dana/view_proses_ps/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

            }

            $output['data'][] = array(
                '<div class="text-left">'.date('d M Y',strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['nomor_pengajuan'].'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-left">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-center inline-button-table">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function get_terbilang()
    {
        if($this->input->is_ajax_request()){
            $nominal = $this->input->post('nominal');

            $response = new stdClass;

            $terbilang = terbilang($nominal);

            $response->terbilang = '#'.$terbilang.' Rupiah#';

            die(json_encode($response));
        }
    }

    public function view_proses_ps($transaksi_id)
    {
        $assets = array();
        $config = 'assets/keuangan/pembayaran_masuk/proses_pps';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $data_pps = $this->pengajuan_pemegang_saham_m->get_by(array('id' => $transaksi_id), true);
        $data_pps = object_to_array($data_pps);

        $keuangan_arus_kas = $this->keuangan_arus_kas_m->get_by(array('transaksi_id' => $transaksi_id), true);
        $keuangan_arus_kas = object_to_array($keuangan_arus_kas);

        $rekening_koran = $this->rekening_koran_m->get_by(array('keuangan_arus_kas_id' => $keuangan_arus_kas['id']), true);
        $rekening_koran = object_to_array($rekening_koran);

        $voucher = $this->voucher_m->get_by(array('transaksi_id' => $transaksi_id), true);
        $voucher = object_to_array($voucher);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("View Pengembalian Dana", $this->session->userdata("language")), 
            'header'              => translate("View Pengembalian Dana", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'keuangan/pembayaran_masuk/view_proses_pps',
            'form_data'           => object_to_array($data_pps),
            'form_data_arus_kas'           => object_to_array($keuangan_arus_kas),
            'form_data_rk'           => object_to_array($rekening_koran),
            'form_data_voucher'           => object_to_array($voucher),
            'pps_id'          => $transaksi_id,                         //table primary key value
            'pk_value'            => $transaksi_id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */