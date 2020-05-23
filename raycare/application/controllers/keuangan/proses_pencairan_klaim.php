<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses_pencairan_klaim extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'bb45f54399dc7e89960ce3901c44e945';                  // untuk check bit_access

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
        $this->load->model('klaim/proses_klaim/proses_klaim_kwitansi_m');        
        $this->load->model('klinik_hd/tindakan_hd_m');        
        $this->load->model('keuangan/arus_kas_bank/keuangan_arus_kas_m');        
        $this->load->model('master/petugas_bpjs_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/bank_m');
        $this->load->model('keuangan/arus_kas_bank/rekening_koran_m');
    }

    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/proses_pencairan_klaim/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_pencairan_klaim/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/keuangan/proses_pencairan_klaim/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Proses Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_pencairan_klaim/history',
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
        
        $status = array('4');
        
        
        $result = $this->proses_klaim_m->get_datatable_keuangan($status,$flag);

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
            if($flag == 1){
                $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/proses_pencairan_klaim/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-cogs"></i></a>';
            }if($flag == 2){
                $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/proses_pencairan_klaim/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';
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
                $flag,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing_history($flag)
    {        
        
        $status = array('4');
        
        
        $result = $this->proses_klaim_m->get_datatable_keuangan_history($status,$flag);

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
            if($flag == 1){
                $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/proses_pencairan_klaim/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-cogs"></i></a>';
            }if($flag == 2){
                $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/proses_pencairan_klaim/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Cetak', $this->session->userdata('language')).'"  href="'.base_url().'keuangan/proses_pencairan_klaim/cetak_voucher/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
            }

            $output['data'][] = array(
                '<div class="text-left">'.date('F Y', strtotime($row['periode_tindakan'])).'</div>',
                '<div class="text-center">'.$row['no_surat'].'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_tarif_ina']).'</div>',
                '<div class="text-left">'.$row['jumlah_tindakan_verif'].'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_tarif_ina_verif']).'</div>',
                '<div class="text-right">'.formatrupiah($row['jumlah_admin']).'</div>',
                '<div class="text-right">'.formatrupiah($row['total_diterima']).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_terima'])).'</div>',
                $flag,
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
        if($array_input['command'] == 'proses')
        {
            $data_klaim = array(
                'jumlah_admin'   => $array_input['biaya_admin'],
                'total_diterima' => $array_input['jumlah_terima'] - $array_input['biaya_admin'],
                'tanggal_terima' => date('Y-m-d', strtotime($array_input['tanggal_terima'])),
                'status'         => 2
            );

            $proses_klaim_id = $this->proses_klaim_kwitansi_m->save($data_klaim,$array_input['id_kwitansi']);

            $date = date('Y-m-d', strtotime($array_input['tanggal_terima']));
            $last_saldo_bank_plus = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
            $after_saldo_bank_plus = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();

            
            $saldo_before_bank_plus = 0;
            if(count($last_saldo_bank_plus) != 0){
                $saldo_before_bank_plus = intval($last_saldo_bank_plus[0]['saldo']);
            }

            $data_arus_kas_bank = array(
                'tanggal'      => $date,
                'tipe'         => 1,
                'keterangan'   => 'Pencairan Klaim BPJS Bulan '.date('M-Y', strtotime($array_input['periode_tindakan'])),
                'bank_id'      => $array_input['bank_id'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'D',
                'rupiah'       => $array_input['jumlah_terima'],
                'saldo'        => ($saldo_before_bank_plus + $array_input['jumlah_terima']),
                'status'       => 1
            );

            $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

            
            $last_id       = $this->rekening_koran_m->get_max_id_rekening_koran()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'RK-'.date('m').'-'.date('Y').'-%04d';
            $id_rek_koran         = sprintf($format_id, $last_id, 4);

            $data = array(
                'id'                   => $id_rek_koran,
                'tanggal'              => $date,
                'bank_id'              => $array_input['bank_id'],
                'jumlah'               => $array_input['jumlah_terima'],
                'keterangan'           => 'Pencairan Klaim BPJS Bulan '.date('M-Y', strtotime($array_input['periode_tindakan'])),
                'keuangan_arus_kas_id' => $arus_kas_bank,
                'is_active'            => 1,
                'created_by'           => $this->session->userdata('user_id'),
                'created_date'         => date('Y-m-d H:i:s')
            );

            $rekening_koran = $this->rekening_koran_m->add_data($data);

            if(count($after_saldo_bank_plus) != 0){
                foreach ($after_saldo_bank_plus as $after) {
                    $data_arus_kas_after_bank_plus = array(
                        'saldo'        => ($after['saldo'] + $array_input['jumlah_terima']),
                    );

                    $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank_plus, $after['id']);
                }
            }

            $date = date('Y-m-d');
            $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();

            
            $saldo_before_bank = 0;
            if(count($last_saldo_bank) != 0){
                $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
            }

            $data_arus_kas_bank = array(
                'tanggal'      => $date,
                'tipe'         => 5,
                'keterangan'   => 'Potongan Administrasi Bulan '.date('M-Y', strtotime($array_input['periode_tindakan'])),
                'bank_id'      => $array_input['bank_id'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'C',
                'rupiah'       => $array_input['biaya_admin'],
                'saldo'        => ($saldo_before_bank - $array_input['biaya_admin']),
                'status'       => 1
            );

            $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

            $last_id       = $this->rekening_koran_m->get_max_id_rekening_koran()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'RK-'.date('m').'-'.date('Y').'-%04d';
            $id_rek_koran         = sprintf($format_id, $last_id, 4);

            $data = array(
                'id'                   => $id_rek_koran,
                'tanggal'              => $date,
                'bank_id'              => $array_input['bank_id'],
                'jumlah'               => $array_input['biaya_admin'],
                'keterangan'           => 'Potongan Administrasi Bulan '.date('M-Y', strtotime($array_input['periode_tindakan'])),
                'keuangan_arus_kas_id' => $arus_kas_bank,
                'is_active'            => 1,
                'created_by'           => $this->session->userdata('user_id'),
                'created_date'         => date('Y-m-d H:i:s')
            );

            $rekening_koran = $this->rekening_koran_m->add_data($data);

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after) {
                    $data_arus_kas_after_bank = array(
                        'saldo'        => ($after['saldo'] - $array_input['biaya_admin']),
                    );

                    $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                }
            }

            


            if($proses_klaim_id)
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data klaim tindakan berhasil diproses.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }
        redirect("keuangan/proses_pencairan_klaim");
    }

    public function proses($id)
    {
        $assets = array();
        $config = 'assets/keuangan/proses_pencairan_klaim/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_klaim  = $this->proses_klaim_m->get($id);
        $data_klaim = object_to_array($data_klaim);

        $data_klaim_kwitansi = $this->proses_klaim_kwitansi_m->get_by(array('proses_klaim_id' => $id), true);
        $data_klaim_kwitansi = object_to_array($data_klaim_kwitansi);
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Pencairan Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Proses Pencairan Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_klaim'     => $data_klaim,
            'data_klaim_kwitansi'     => $data_klaim_kwitansi,
            'content_view'   => 'keuangan/proses_pencairan_klaim/proses',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/keuangan/proses_pencairan_klaim/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_klaim  = $this->proses_klaim_m->get($id);
        $data_klaim = object_to_array($data_klaim);

        $data_klaim_kwitansi = $this->proses_klaim_kwitansi_m->get_by(array('proses_klaim_id' => $id), true);
        $data_klaim_kwitansi = object_to_array($data_klaim_kwitansi);
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses Pencairan Klaim BPJS', $this->session->userdata('language')), 
            'header'         => translate('Proses Pencairan Klaim BPJS', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_klaim'     => $data_klaim,
            'data_klaim_kwitansi'     => $data_klaim_kwitansi,
            'content_view'   => 'keuangan/proses_pencairan_klaim/view',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
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

    public function cetak_voucher($id)
    {
        $this->load->view('keuangan/proses_pencairan_klaim/voucher');
        # code...
    }


}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */