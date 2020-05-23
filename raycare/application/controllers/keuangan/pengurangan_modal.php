<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengurangan_modal extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '2d9b4203bd492588a264985b1036a1e6';                  // untuk check bit_access

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

        $this->load->model('keuangan/perubahan_modal/pengurangan_modal_m');
        $this->load->model('keuangan/perubahan_modal/o_s_hutang_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');
        $this->load->model('master/bank_m');
        
        $this->load->model('others/kotak_sampah_m');       
    }
    
    public function index()
    {

        // $test = get_http_header_bpjs_new_kld();
        $test = get_http_header_bpjs_new();
        // $test = get_http_header_bpjs();
        //die(dump($test));
        $assets = array();
        $config = 'assets/keuangan/pengurangan_modal/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengurangan Modal', $this->session->userdata('language')), 
            'header'         => translate('Pengurangan Modal', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengurangan_modal/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/keuangan/pengurangan_modal/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tambah Pengurangan Modal', $this->session->userdata('language')), 
            'header'         => translate('Tambah Pengurangan Modal', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengurangan_modal/add',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/keuangan/pengurangan_modal/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data_modal = $this->pengurangan_modal_m->get_by(array('id' => $id), true);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengurangan Modal', $this->session->userdata('language')), 
            'header'         => translate('Pengurangan Modal', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pengurangan_modal/view',
            'data_modal'  => object_to_array($data_modal),
            'pk_value'       => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        $result = $this->pengurangan_modal_m->get_datatable();

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die_dump($records);
        $count = count($records->result_array());

        $i=0;
        $total_saldo = 0;
        $input_saldo = '';

        foreach($records->result_array() as $row)
        {
            $i++;

            $status = '';

            $total_saldo = $total_saldo + $row['nominal'];

            if($i == $count)
            {
            $input_saldo  = '<input type="hidden" name="items['.$i.'][saldo]" id="grandtotal_saldo" class="form-control input-sm" value="'.$total_saldo.'">';
            }

            if($row['status'] == 1)
            {            
                $status = '<div class="text-left"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
            }
            else if($row['status'] == 2)
            {
                $status = '<div class="text-left"><span class="label label-md label-primary">Proses Persetujuan</span></div>';
            }
            else if($row['status'] == 3)
            {
                $status = '<div class="text-left"><span class="label label-md label-danger">Menunggu Diproses</span></div>';
            }
            else if($row['status'] == 4)
            {
                $status = '<div class="text-left"><span class="label label-md label-success">Konfirmasi Disetujui</span></div>';
            }

            $keterangan = explode("\n", $row['keterangan']);
            $keterangan = implode('<br/>', $keterangan);

            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pengurangan_modal/edit/'.$row['id'].'" class="btn btn-default"><i class="fa fa-search"></i></a>';

            $output['aaData'][] = array(
                '<div class="text-left inline-button-table">'.$row['no_permintaan'].'</div>',
                '<div class="text-left inline-button-table">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-right">'.$status.'</div>',
                '<div class="text-right">'.$row['keperluan'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function get_terbilang()
    {
        if($this->input->is_ajax_request()){
            $nominal = $this->input->post('nominal');

            $response = new stdClass;

            $terbilang = terbilang($nominal);

            $response->terbilang = '#'.$terbilang.' Rupiah #';

            die(json_encode($response));
        }
    }


    public function save()
    {
        $array_input = $this->input->post();
        $command = $array_input['command'];

        if($command === 'add'){

            $last_id   = $this->pengurangan_modal_m->get_max_id_pengurangan_modal()->result_array();
            $last_id   = intval($last_id[0]['max_id'])+1;
            
            $format_id = 'KMD-'.date('mY').'-%04d';
            $id_pengurangan_modal  = sprintf($format_id, $last_id, 4);

            $format_nomor = 'KMD#'.date('mY').'/%04d';
            $no_pengurangan_modal  = sprintf($format_nomor, $last_id, 4);

            $insert_pengurangan_modal = array(
                'id'                => $id_pengurangan_modal,
                'no_permintaan'     => $no_pengurangan_modal,
                'tanggal'           => date('Y-m-d'),
                'nominal'           => $array_input['nominal'],
                'keperluan'         => $array_input['keperluan'],
                'no_rek_tujuan'     => $array_input['no_rek_tujuan'],
                'bank_tujuan'       => $array_input['bank_tujuan'],
                'a_n_bank_tujuan'   => $array_input['a_n_bank_tujuan'],
                'status'            => 1,
                'is_active'         => 1,
                'created_by'        => $this->session->userdata('user_id'),
                'created_date'      => date('Y-m-d H:i:s'),
            );

            $save_pengurangan_modal = $this->pengurangan_modal_m->add_data($insert_pengurangan_modal);

            $last_id_os_hutang   = $this->o_s_hutang_m->get_max_id_o_s_hutang()->result_array();
            $last_id_os_hutang   = intval($last_id_os_hutang[0]['max_id'])+1;
            
            $format_id = 'OSH-'.date('mY').'-%04d';
            $id_os_hutang  = sprintf($format_id, $last_id_os_hutang, 4);

            $user_direktur = $this->user_m->get_by(array('id' => 22), true);

            $insert_os_hutang = array(
                'id'                    => $id_os_hutang,
                'tanggal'               => date('Y-m-d'),
                'transaksi_id'          => $id_pengurangan_modal,
                'tipe_transaksi'        => 4,
                'pemberi_hutang_id'     => 22,
                'nama_pemberi_hutang'   => $user_direktur->nama,
                'tipe_pemberi_hutang'   => 3,
                'jumlah'                => $array_input['nominal'],
                'created_by'            => $this->session->userdata('user_id'),
                'created_date'          => date('Y-m-d H:i:s'),
            );

            $save_os_hutang = $this->o_s_hutang_m->add_data($insert_os_hutang);

            $last_id_status       = $this->pembayaran_status_m->get_max_id_pembayaran()->result_array();
            $last_id_status       = intval($last_id_status[0]['max_id'])+1;
            
            $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
            $id_status         = sprintf($format_id_status, $last_id_status, 4);

            $divisi_posisi = $this->user_level_m->get(21);

            $data_status = array(
                'id'              => $id_status,
                'transaksi_id'    => $id_ttf,
                'transaksi_nomor' => $id_pengurangan_modal,
                'tipe_transaksi'  => 5,
                'nominal'         => $array_input['nominal'],
                'status'          => 1,
                'user_level_id'   => 21,
                'divisi'          => $divisi_posisi->divisi_id,
                'waktu_akhir'     => date('Y-m-d', strtotime($array_input['tanggal_faktur'])),
                'is_active'       => 1,
                'created_by'      => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s')
            );

            $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);

            $order_status = 0;
            for($i=0; $i<3; $i++){
                $order_status = $order_status + 1;

                $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                
                $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                $status = 0;
                $keterangan = '';
                $user_proses = '';
                $tanggal_proses = '';
                $waktu_tunggu = '';
                if($i == 0){
                    $user_level = $this->user_level_m->get(21);

                    $divisi_id = $user_level->divisi_id;
                    $user_level_id = 21;

                }if($i == 2){
                    $user_level = $this->user_level_m->get(21);

                    $divisi_id = $user_level->divisi_id;
                    $user_level_id = 21;
                }if($i == 1){
                    $user_level = $this->user_level_m->get(5);

                    $divisi_id = $user_level->divisi_id;
                    $user_level_id = 5;
                }

                $data_status_detail = array(
                    'id'                   => $id_status_detail,
                    'pembayaran_status_id' => $id_status,
                    'transaksi_id'         => $id_pengurangan_modal,
                    'tipe_transaksi'       => 5,
                    '`order`'              => $order_status,
                    'divisi'               => $divisi_id,
                    'user_level_id'        => $user_level_id,
                    'tipe'                 => 2,
                    'tipe_pengajuan'       => 0,
                    'user_proses'          => $user_proses,
                    'tanggal_proses'          => $tanggal_proses,
                    'waktu_tunggu'          => $waktu_tunggu,
                    'status'               => $status,
                    'keterangan'           => $keterangan,
                    'is_active'            => 1,
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s')
                );

                $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
            }

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data pengurangan modal berhasil diubah", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
        }

        redirect('keuangan/pengurangan_modal');

    }

    public function delete($id,$keuangan_arus_kas_id,$bank_id)
    {
        $data_rekening = array(
            'is_active' => 0
        );
        $delete_rekening = $this->rekening_koran_m->edit_data($data_rekening, $id);

        $data_kas = $this->keuangan_arus_kas_m->get_by(array('id' => $keuangan_arus_kas_id), true);

        $tanggal_kas = date('Y-m-d', strtotime($data_kas->tanggal));

        $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($tanggal_kas,$bank_id)->result_array();
        // die(dump($this->db->last_query()));

        if(count($after_saldo_bank) != 0){
            foreach ($after_saldo_bank as $after_bank_kas) {

                $data_arus_kas_bank_after = array(
                    'saldo'        => $after_bank_kas['saldo'] - $data_kas->rupiah,
                );

                $keuangan_arus_kas_edit = $this->keuangan_arus_kas_m->save($data_arus_kas_bank_after,$after_bank_kas['id']);
            }
        }

        $delete_kas = $this->keuangan_arus_kas_m->delete($keuangan_arus_kas_id);

        $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Data pengurangan modal berhasil dihapus", $this->session->userdata("language")),
            "msgTitle" => translate("Success", $this->session->userdata("language"))    
        );
        $this->session->set_flashdata($flashdata);
        redirect('keuangan/rekening_koran');

    }

}

/* End of file arus_kas_bank.php */
/* Location: ./application/controllers/keuangan/arus_kas_bank.php */