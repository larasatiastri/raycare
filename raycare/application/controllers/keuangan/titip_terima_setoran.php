<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Titip_terima_setoran extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '789d56101e08bd583e8276174aa4e596';                  // untuk check bit_access

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

        $this->load->model('keuangan/titip_terima_setoran/titip_setoran_m');
        $this->load->model('keuangan/titip_terima_setoran/titip_setoran_detail_m');
        $this->load->model('keuangan/titip_terima_setoran/setoran_keuangan_kasir_m');
        $this->load->model('keuangan/titip_terima_setoran/setoran_kasir_keuangan_detail_m');
        $this->load->model('keuangan/titip_terima_setoran/permintaan_biaya_m');
        $this->load->model('keuangan/titip_terima_setoran/permintaan_biaya_bon_m');
        $this->load->model('keuangan/arus_kas_kasir/kasir_arus_kas_m');
        $this->load->model('keuangan/arus_kas_kasir/keuangan_arus_kas_m');
      
        $this->load->model('master/cabang_m');
        $this->load->model('master/bank_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/titip_terima_setoran/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pengiriman Setoran & Penerimaan Saldo', $this->session->userdata('language')), 
            'header'         => translate('Pengiriman Setoran & Penerimaan Saldo', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/titip_terima_setoran/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/keuangan/titip_terima_setoran/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah Kirim Setoran", $this->session->userdata("language")), 
            'header'         => translate("Tambah Kirim Setoran", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/titip_terima_setoran/add',
            'flag'           => 'add',
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

            if ($row['status'] == 1)
                $status = '<span class="label label-warning">Belum Diterima</span>';

            if ($row['status'] == 2)
                $status = '<span class="label label-danger">Revisi</span>';
            if ($row['status'] == 3)
                $status = '<span class="label label-success">Sudah Diterima</span>';


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
            );

            $i++;
        }

        echo json_encode($output);
    }



    public function listing_terima_setoran()
    {

        $result = $this->setoran_keuangan_kasir_m->get_datatable();
       
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

        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd M Y');

            if ($row['status'] == 4){
                $status = '<span class="label label-warning">Belum Diterima</span>';
                $action = '<a title="'.translate('Terima', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_verif" href="'.base_url().'keuangan/titip_terima_setoran/modal_verif/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a><a title="'.translate('Tolak', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_tolak" href="'.base_url().'keuangan/titip_terima_setoran/modal_tolak/'.$row['id'].'" class="btn btn-danger"><i class="fa fa-times"></i></a> ';
            }

            if ($row['status'] == 5){
                $status = '<span class="label label-danger">Revisi</span>';
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_view" href="'.base_url().'keuangan/titip_terima_setoran/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a> ';

            }

            if ($row['status'] == 6){
                $status = '<span class="label label-success">Sudah Diterima</span>';
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_view" href="'.base_url().'keuangan/titip_terima_setoran/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a> ';

            }
            
            $output['data'][] = array(
                
                '<div class="text-center inline-button-table">'.$effective_date.'</div>',
                '<div class="text-left">'.$row['nama_user_created'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-left">'.formatrupiah($row['total_setor']).'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function listing_add_detail_setoran_biaya()
    {

        $result = $this->permintaan_biaya_m->get_datatable();

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
                '<div class="text-left bold ""><input type="hidden" name="biaya['.$i.'][id]" value="'.$row['id'].'"><input type="hidden" name="biaya['.$i.'][tanggal]" value="'.$row['tanggal'].'">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left inline-button-table">'.$row['nomor_permintaan'].'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-right bold inline-button-table"><input type="hidden" name="biaya['.$i.'][rupiah]" value="'.$row['nominal_setujui'].'"><a href="'.base_url().'keuangan/titip_terima_setoran/modal_detail/'.$row['id'].'" data-toggle="modal" data-target="#popup_modal">'.formatrupiah($row['nominal_setujui']).$input_total.'</a></div>',
                '<div class="text-left">'.$notes.'</div>'

            );
        
            $i++;
        
        }

        echo json_encode($output);
    }

   

    public function verifikasi()
    {
        $array_input = $this->input->post();
        // die_dump($array_input);
        $user_id     = $this->session->userdata('user_id');
        $user        = $this->user_m->get($user_id);
        $password    = $this->user_m->hash($array_input['password']);

        $date = date('Y-m-d', strtotime($array_input['tanggal']));
        $date_bank = date('Y-m-d', strtotime($array_input['tanggal']));
        $rupiah = $array_input['rupiah'];


        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Data gagal disimpan', $this->session->userdata('language'));

        if ($array_input['command'] === 'add')
        {
            if($password == $user->password && $array_input['username'] == $user->username) 
            {
                $data_setoran = array(
                    'user_terima_id' => $this->session->userdata('user_id'),
                    'status' => 6,
                );
            
                $update_terima_setoran = $this->setoran_keuangan_kasir_m->save($data_setoran, $array_input['id_terima_setoran']);

                $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date,2)->result_array();
                $after_saldo = $this->kasir_arus_kas_m->get_after_after($date,2)->result_array();
                
                $saldo_before = 0;
                if(count($last_saldo) != 0){
                    $saldo_before = intval($last_saldo[0]['saldo']);
                }

                $data_arus_kas = array(
                    'tanggal'      => $date,
                    'tipe'         => 7,
                    'tipe_kasir'   => 2,
                    'keterangan'   => $array_input['subjek'],
                    'user_id'      => $this->session->userdata('user_id'),
                    'debit_credit' => 'D',
                    'rupiah'       => $rupiah,
                    'saldo'        => ($saldo_before + $rupiah),
                    'status'       => 1
                );

                $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

                if(count($after_saldo) != 0){
                    foreach ($after_saldo as $after) {
                        $data_arus_kas_after = array(
                            'saldo'        => ($after['saldo'] + $rupiah),
                        );

                        $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                    }
                }
                
                $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date_bank, $array_input['bank_id'])->result_array();
                $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date_bank, $array_input['bank_id'])->result_array();
                
                $saldo_before_bank = 0;
                if(count($last_saldo_bank) != 0){
                    $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
                }

                $data_arus_kas_bank = array(
                    'tanggal'      => $date_bank,
                    'tipe'         => 5,
                    'keterangan'   => $array_input['subjek'],
                    'bank_id'      => $array_input['bank_id'],
                    'user_id'      => $this->session->userdata('user_id'),
                    'debit_credit' => 'C',
                    'rupiah'       => $rupiah,
                    'saldo'        => ($saldo_before_bank - $rupiah),
                    'status'       => 1
                );

                $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

                if(count($after_saldo_bank) != 0){
                    foreach ($after_saldo_bank as $after) {
                        $data_arus_kas_after_bank = array(
                            'saldo'        => ($after['saldo'] - $rupiah),
                        );

                        $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                    }
                }

                if ($update_terima_setoran) 
                {
                    $response->success = true;
                    $response->msg = translate('Saldo berhasil diterima', $this->session->userdata('language'));
                }
            } 

            elseif ($password != $user->password && $array_input['username'] != $user->username) 
            {
                $response->success = false;
                $response->msg = translate('Username dan Password tidak valid', $this->session->userdata('language'));
            }

            die(json_encode($response));

        }


    }

    public function save()
    {
        $array_input = $this->input->post();
        $items  = $this->input->post('biaya');
        
        if ($array_input['command'] === 'add')
        {   
            $data_titip_setoran = array(
                'tanggal'         => date("Y-m-d", strtotime($array_input['tanggal'])),
                'rupiah_bon'      => $array_input['rupiah_bon'],
                'rupiah'          => $array_input['rupiah'],
                'subjek'          => $array_input['subjek'],
                'keterangan'      => $array_input['keterangan'],
                'url_bukti_setor' => $array_input['url_bukti_setor'],
                'bank_id' => $array_input['bank_id'],
                'status'          => 1
            );
            
            $save_setoran_keuangan_kasir = $this->titip_setoran_m->save($data_titip_setoran);

            if($array_input['url_bukti_setor'] != ''){
                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/titip_terima_setoran/images/'.$save_setoran_keuangan_kasir;
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url_bukti_setor'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $array_input['url_bukti_setor'];
                $real_file = $save_setoran_keuangan_kasir.'/'.$new_filename;

                copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_saldo').$real_file);
            }
                 

            foreach ($items as $biaya) {
            
                $data_setoran_biaya = array(

                    'titip_setoran_id'          => $save_setoran_keuangan_kasir,
                    'detail_id'                 => $biaya['id'],
                    'tipe_detail'               => 1,
                    'tanggal'                   => date("Y-m-d", strtotime($biaya['tanggal'])),
                    'rupiah'                    => $biaya['rupiah'],
                    'status'                    => 1,

                );

                $setoran_kasir_keuangan_detail = $this->titip_setoran_detail_m->save($data_setoran_biaya);

                $data_bon = array(
                    'status_proses' => 2
                );

                $bon_id = $this->permintaan_biaya_m->save($data_bon, $biaya['id']);
            } 

                
            if ($save_setoran_keuangan_kasir && $setoran_kasir_keuangan_detail) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Permintaan Saldo Berhasil Ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }

        redirect("keuangan/titip_terima_setoran");
    }


    public function terima_setoran($id)
    {
        $data = array(
            'id' => $id
        );
        $this->load->view('keuangan/titip_terima_setoran/modals_setoran',$data);
    }


    public function modal_detail($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));

        $data = array(
            'id' => $id,
            'form_data_bon' => object_to_array($form_data_bon)
        );
        $this->load->view('keuangan/titip_terima_setoran/modal_detail',$data);
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

    public function modal_verif($id)
    {
        $form_data = $this->setoran_keuangan_kasir_m->get($id);

        $data = array(
            'id' => $id,
            'form_data' => object_to_array($form_data)
        );
        $this->load->view('keuangan/titip_terima_setoran/modal_verif',$data);
    }

    public function view($id)
    {
        $setoran = $this->setoran_keuangan_kasir_m->get($id);

        $data = array(
            'setoran'   => object_to_array($setoran),
            'pk_value'  => $id
        );
        // Load the view
        $this->load->view('keuangan/kirim_petty_cash/view',$data);
    }

}

/* End of file titip_terima_setoran.php */
/* Location: ./application/controllers/keuangan/titip_terima_setoran.php */