
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses_terima_setoran extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '55a38020784efa22c400fa3d29478fba';                  // untuk check bit_access

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

        $this->load->model('keuangan/proses_terima_setoran/titip_setoran_m');
        $this->load->model('keuangan/proses_terima_setoran/titip_setoran_detail_m');
        $this->load->model('keuangan/proses_terima_setoran/setoran_keuangan_kasir_m');
        $this->load->model('keuangan/proses_terima_setoran/setoran_kasir_keuangan_detail_m');
        $this->load->model('keuangan/proses_terima_setoran/permintaan_biaya_m');
        $this->load->model('keuangan/proses_terima_setoran/permintaan_biaya_bon_m');
        $this->load->model('keuangan/kirim_petty_cash/persetujuan_permintaan_setoran_keuangan_m');
        $this->load->model('keuangan/kirim_petty_cash/persetujuan_permintaan_setoran_keuangan_history_m');

        $this->load->model('keuangan/arus_kas_kasir/kasir_arus_kas_m');
        $this->load->model('keuangan/arus_kas_kasir/keuangan_arus_kas_m');
      
        $this->load->model('master/cabang_m');
        $this->load->model('master/user_level_persetujuan_m');
        $this->load->model('master/bank_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/proses_terima_setoran/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Terima Setoran', $this->session->userdata('language')), 
            'header'         => translate('Terima Setoran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_terima_setoran/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_terima_setoran/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data_setoran = $this->titip_setoran_m->get($id);
        $data_setoran_detail = $this->titip_setoran_detail_m->get_by(array('titip_setoran_id' => $id));

        $data = array(
            'title'               => config_item('site_name').' | '.translate("Proses Terima Setoran", $this->session->userdata("language")), 
            'header'              => translate("Proses Terima Setoran", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'keuangan/proses_terima_setoran/proses',
            'data_setoran'        => object_to_array($data_setoran),
            'data_setoran_detail' => object_to_array($data_setoran_detail),
            'pk_value'            => $id
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_terima_setoran/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data_setoran = $this->titip_setoran_m->get($id);
        $data_setoran_detail = $this->titip_setoran_detail_m->get_by(array('titip_setoran_id' => $id));

        $data = array(
            'title'               => config_item('site_name').' | '.translate("Proses Terima Setoran", $this->session->userdata("language")), 
            'header'              => translate("Proses Terima Setoran", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'keuangan/proses_terima_setoran/view',
            'data_setoran'        => object_to_array($data_setoran),
            'data_setoran_detail' => object_to_array($data_setoran_detail),
            'pk_value'            => $id
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

        
    public function listing_titip_setoran($status = null){

        $result = $this->titip_setoran_m->get_datatable($status);
       
        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        $rupiah = 0;
        $rupiah_terima = 0;
        $nama = '';
        $status = '';
        $info = '';
        $in_titip_setoran_id = '';

        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd M Y');
            $bulan = explode('-', $row['tanggal']);

            if ($row['status'] == 1){
                $status = '<span class="label label-warning">Belum Diterima</span>';
                $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_terima_setoran/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-gears"></i></a><a title="'.translate('Revisi', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_terima_setoran/revisi/'.$row['id'].'" class="btn btn-danger hidden"><i class="fa fa-undo"></i></a> ';
            }

            if ($row['status'] == 2){
                $status = '<span class="label label-danger">Revisi</span>';
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_terima_setoran/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a> ';

            }

            if ($row['status'] == 3){
                $status = '<span class="label label-success">Sudah Diterima</span>';
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_terima_setoran/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a> ';

            }


            $item = $this->titip_setoran_detail_m->get_detail($row['id']);

            $info = formatrupiah($row['rupiah']);
            $info_biaya = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="item-unlist" data-id="'.$row['id'].'" name="info"><u>'.formatrupiah($row['rupiah_bon']).'</u></a>';

            $in_titip_setoran_id = '<input class="form-control hidden" id="titip_setoran_id" name="titip_setoran_id" value="'.$row['id'].'">';
            

            $output['data'][] = array(
                
                '<div class="text-center inline-button-table">'.$effective_date.$in_titip_setoran_id.'</div>',
                '<div class="text-left">'.$row['nama_user_created'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-right">'.$info_biaya.'</div>',
                '<div class="text-right">'.$info.'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function listing_add_detail_setoran_biaya($titip_setoran_id)
    {

        $result = $this->titip_setoran_detail_m->get_datatable($titip_setoran_id);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records; 
        $count = count($records->result_array());
        $i=0;
        $total = 0;
        $input_total = '';
        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            $total = $total + $row['nominal_setujui'];

            if($i == ($count-1)){
                $input_total = '<input type="hidden" id="total_biaya" name="total_biaya" value="'.$total.'">';
            }
            // PopOver Notes
            $notes    = $row['keperluan'];
        
            $output['aaData'][] = array(
                '<div class="text-left inline-button-table" style="font-size:11px;">'.$row['nomor_permintaan'].'</div>',
                '<div class="text-center inline-button-table"><input type="hidden" name="biaya['.$i.'][id]" value="'.$row['id'].'"><input type="hidden" name="biaya['.$i.'][tanggal]" value="'.$row['tanggal'].'">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-right inline-button-table"><input type="hidden" name="biaya['.$i.'][rupiah]" value="'.$row['nominal_setujui'].'"><a href="'.base_url().'keuangan/proses_terima_setoran/modal_detail/'.$row['id'].'" data-toggle="modal" data-target="#popup_modal">'.formatrupiah($row['nominal_setujui']).$input_total.'</a></div>',
                '<div class="text-left">'.$notes.'</div>'

            );
        
            $i++;
        
        }

        echo json_encode($output);
    }

   

    public function verifikasi()
    {
        $array_input = $this->input->post();
        $user_id     = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');

        $user        = $this->user_m->get($user_id);
        $password    = $this->user_m->hash($array_input['password']);
        $rupiah      = $array_input['rupiah'];

        $date = date('Y-m-d', strtotime($array_input['tanggal']));
        $id_titip = $array_input['id'];
        $items  = $this->input->post('biaya');

        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Username dan Password tidak valid', $this->session->userdata('language'));

        $level_persetujuan = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $level_id, 'tipe_persetujuan' => 7));


        if ($array_input['command'] === 'proses')
        {
            if($password == $user->password && $array_input['username'] == $user->username) 
            {
                // die_dump('data benar');
                $data_titip_setoran = array(
                    'penerima_id' => $this->session->userdata('user_id'),
                    'status'          => 3
                );
                
                $save_setoran_keuangan_kasir = $this->titip_setoran_m->save($data_titip_setoran,$id_titip);

                $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date,2)->result_array();
                $after_saldo = $this->kasir_arus_kas_m->get_after_after($date,2)->result_array();
                
                $saldo_before = 0;
                if(count($last_saldo) != 0){
                    $saldo_before = intval($last_saldo[0]['saldo']);
                }

                $data_arus_kas = array(
                    'tanggal'      => $date,
                    'tipe'         => 5,
                    'tipe_kasir'         => 2,
                    'keterangan'   => $array_input['subjek_'],
                    'user_id'      => $this->session->userdata('user_id'),
                    'debit_credit' => 'C',
                    'rupiah'       => $rupiah,
                    'saldo'        => ($saldo_before - $rupiah),
                    'status'       => 1
                );

                $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

                if(count($after_saldo) != 0){
                    foreach ($after_saldo as $after) {
                        $data_arus_kas_after = array(
                            'saldo'        => ($after['saldo'] - $rupiah),
                        );

                        $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                    }
                }
                
                $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
                $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();
                
                $saldo_before_bank = 0;
                if(count($last_saldo_bank) != 0){
                    $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
                }

                $data_arus_kas_bank = array(
                    'tanggal'      => $date,
                    'tipe'         => 5,
                    'keterangan'   => $array_input['subjek_'],
                    'bank_id'      => $array_input['bank_id'],
                    'user_id'      => $this->session->userdata('user_id'),
                    'debit_credit' => 'D',
                    'rupiah'       => $rupiah,
                    'saldo'        => ($saldo_before_bank + $rupiah),
                    'status'       => 1
                );

                $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

                if(count($after_saldo_bank) != 0){
                    foreach ($after_saldo_bank as $after) {
                        $data_arus_kas_after_bank = array(
                            'saldo'        => ($after['saldo'] + $rupiah),
                        );

                        $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                    }
                }

                if($items){
                    foreach ($items as $biaya) {
                
                        $data_bon = array(
                            'status_proses' => 4
                        );

                        $bon_id = $this->permintaan_biaya_m->save($data_bon, $biaya['id']);
                    } 
                }

                if($array_input['nominal'] != '' && $array_input['nominal'] != 0 ){
                    $data_setoran_keuangan = array(
                        'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                        'titip_setoran_id' => $id_titip,
                        'total_setor'     => $array_input['nominal'],
                        'bank_id'         => $array_input['bank_id'],
                        'subjek'          => $array_input['subjek'],
                        'jenis_bayar_id'          => $array_input['jenis_bayar'],
                        'no_cek'          => $array_input['no_cek'],
                        'bank_tujuan'          => $array_input['bank_tujuan'],
                        'norek_tujuan'          => $array_input['norek_tujuan'],
                        'an_tujuan'          => $array_input['atas_nama'],
                    );

                    if(count($level_persetujuan) != 0){
                        $data_setoran_keuangan['status'] = 1;
                    }
                    if(count($level_persetujuan) == 0){
                        $data_setoran_keuangan['status'] = 4;
                    }

                    $save_petty_cash_id = $this->setoran_keuangan_kasir_m->save($data_setoran_keuangan);
                   


                    if(count($level_persetujuan) != 0){
                        $level_persetujuan = object_to_array($level_persetujuan);
                         sent_notification(5,$this->session->userdata('nama_lengkap'), $save_petty_cash_id);
                        foreach ($level_persetujuan as $key => $setuju) {
                            $maksimum = $this->persetujuan_permintaan_setoran_keuangan_m->get_last_id()->row(0);
                        
                            if(count($maksimum) == NULL)
                            {
                                $max_id = 1;
                            }
                            else 
                            {
                                $max_id = $maksimum->last_id;
                                $max_id = $max_id + 1;
                            }

                            $data_persetujuan = array(
                                'persetujuan_permintaan_setoran_keuangan_id' => $max_id,
                                'setoran_keuangan_kasir_id'                  => $save_petty_cash_id,
                                'user_level_id'                              => $setuju['user_level_menyetujui_id'],
                                '`order`'                                    => $setuju['level_order'],
                                '`status`'                                   =>  1,
                                'is_active'                                  =>  1,
                                'created_by'                                 => $this->session->userdata('user_id'),
                                'created_date'                               => date('Y-m-d H:i:s'),
                            );

                            $persetujuan_save = $this->persetujuan_permintaan_setoran_keuangan_m->add_data($data_persetujuan);
                        }
                    }
                }
                

                if ($save_petty_cash_id) 
                {
                    $response->success = true;
                    $response->msg = translate('Username dan Password valid', $this->session->userdata('language'));
                    die(json_encode($response));
                }
            } 

            elseif ($password != $user->password && $array_input['username'] != $user->username) 
            {
                $response->success = false;
                $response->msg = translate('Username dan Password tidak valid', $this->session->userdata('language'));
                die(json_encode($response));
            }

            die(json_encode($response));

        }


    }

    public function save()
    {
        $array_input = $this->input->post();


        $id_titip = $array_input['id'];
        $items  = $this->input->post('biaya');
        
        if ($array_input['command'] === 'proses')
        {   
            $data_titip_setoran = array(
                'penerima_id' => $this->session->userdata('user_id'),
                'status'          => 3
            );
            
            $save_setoran_keuangan_kasir = $this->titip_setoran_m->save($data_titip_setoran,$id_titip);

            foreach ($items as $biaya) {
        
                $data_bon = array(
                    'status_proses' => 4
                );

                $bon_id = $this->permintaan_biaya_m->save($data_bon, $biaya['id']);
            } 

                
            if ($save_setoran_keuangan_kasir && $setoran_kasir_keuangan_detail) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Setoran Berhasil Diterima.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }

        redirect("keuangan/proses_terima_setoran");
    }

    public function modal_detail($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id, 'is_active' => 1));

        $data = array(
            'id' => $id,
            'form_data_bon' => object_to_array($form_data_bon)
        );
        $this->load->view('keuangan/proses_terima_setoran/modal_detail',$data);
    }

    public function modal_verif($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));

        $data = array(
            'id' => $id,
            'form_data_bon' => object_to_array($form_data_bon)
        );
        $this->load->view('keuangan/proses_terima_setoran/modal_verif',$data);
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

}

/* End of file titip_terima_setoran.php */
/* Location: ./application/controllers/keuangan/proses_terima_setoran.php */