
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kirim_petty_cash extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '8ebfea2b70c1c908da0a57611609e00a';                  // untuk check bit_access

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

        $this->load->model('keuangan/kirim_petty_cash/setoran_keuangan_kasir_m');
        $this->load->model('keuangan/kirim_petty_cash/persetujuan_permintaan_setoran_keuangan_m');
        $this->load->model('keuangan/kirim_petty_cash/persetujuan_permintaan_setoran_keuangan_history_m');
        $this->load->model('master/bank_m');
        $this->load->model('master/user_level_persetujuan_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/kirim_petty_cash/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Kirim Petty Cash', $this->session->userdata('language')), 
            'header'         => translate('Kirim Petty Cash', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/kirim_petty_cash/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing(){

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

            if ($row['status'] == 1){
                $status = '<span class="label label-warning">Menunggu Persetujuan</span>';
                $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_edit" href="'.base_url().'keuangan/kirim_petty_cash/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a> ';
            }

            if ($row['status'] == 2){
                $status = '<span class="label label-success">Disetujui</span>';
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_view" href="'.base_url().'keuangan/kirim_petty_cash/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a> <a title="'.translate('Kirim', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_edit" href="'.base_url().'keuangan/kirim_petty_cash/send/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-send"></i></a>  ';

            }

            if ($row['status'] == 3){
                $status = '<span class="label label-danger">Ditolak</span>';
                $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_edit" href="'.base_url().'keuangan/kirim_petty_cash/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a> ';

            }
            if ($row['status'] == 4){
                $status = '<span class="label label-warning">Belum Diterima</span>';
                $action = '<a title="'.translate('Kirim', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_edit" href="'.base_url().'keuangan/kirim_petty_cash/send/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-send"></i></a> ';

            }
            if ($row['status'] == 5){
                $status = '<span class="label label-danger">Revisi</span>';
                $action = '<a title="'.translate('Kirim', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_edit" href="'.base_url().'keuangan/kirim_petty_cash/send/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-send"></i></a>  ';

            }
            if ($row['status'] == 6){
                $status = '<span class="label label-info">Diterima</span>';
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_view" href="'.base_url().'keuangan/kirim_petty_cash/view/'.$row['id'].'" class="btn default"><i class="fa fa-search"></i></a>';

            }
            
            $output['data'][] = array(
                
                '<div class="text-center">'.$effective_date.'</div>',
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

    public function add()
    {
        // Load the view
        $this->load->view('keuangan/kirim_petty_cash/add');
    }

    public function edit($id)
    {
        $setoran = $this->setoran_keuangan_kasir_m->get($id);

        $data = array(
            'setoran'   => object_to_array($setoran),
            'pk_value'  => $id,
            'flag'      => 'edit'
        );
        // Load the view
        $this->load->view('keuangan/kirim_petty_cash/edit',$data);
    }
    
    public function send($id)
    {
        $setoran = $this->setoran_keuangan_kasir_m->get($id);

        $data = array(
            'setoran'   => object_to_array($setoran),
            'pk_value'  => $id,
            'flag'      => 'send'
        );
        // Load the view
        $this->load->view('keuangan/kirim_petty_cash/edit',$data);
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

    public function save()
    {
        $array_input = $this->input->post();
        $level_id = $this->session->userdata('level_id');

        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Data gagal disimpan', $this->session->userdata('language'));

        $level_persetujuan = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $level_id, 'tipe_persetujuan' => 7));
        
        if ($array_input['command'] === 'add')
        {   
            $data_setoran_keuangan = array(
                'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'total_setor'     => $array_input['rupiah'],
                'nominal_setujui'     => $array_input['rupiah'],
                'bank_id'         => $array_input['bank_id'],
                'url_bukti_setor' => $array_input['url_bukti_setor'],
                'subjek'          => $array_input['subjek'],
                'no_cek'          => $array_input['no_cek']
            );

            if(count($level_persetujuan) != 0){
                $data_setoran_keuangan['status'] = 1;
            }
            if(count($level_persetujuan) == 0){
                $data_setoran_keuangan['status'] = 4;
            }

            $save_setoran_keuangan_kasir = $this->setoran_keuangan_kasir_m->save($data_setoran_keuangan);

            if(count($level_persetujuan) != 0){
                $level_persetujuan = object_to_array($level_persetujuan);
                sent_notification(10,$this->session->userdata('nama_lengkap'), $save_setoran_keuangan_kasir);

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
                        'setoran_keuangan_kasir_id'                  => $save_setoran_keuangan_kasir,
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


            if ($save_setoran_keuangan_kasir) 
            {
                $response->success = true;
                $response->msg = translate('Petty Cash Berhasil Dikirim', $this->session->userdata('language'));
            }

            die(json_encode($response));
        }
        if ($array_input['command'] === 'edit')
        {   
            if($array_input['flag'] === 'edit'){
                $id = $array_input['id'];

                $data_setoran_keuangan = array(
                    'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'total_setor'     => $array_input['rupiah'],
                    'bank_id'         => $array_input['bank_id'],
                    'url_bukti_setor' => $array_input['url_bukti_setor'],
                    'subjek'          => $array_input['subjek'],
                );

                if(count($level_persetujuan) != 0){
                    $data_setoran_keuangan['status'] = 1;
                }
                if(count($level_persetujuan) == 0){
                    $data_setoran_keuangan['status'] = 4;
                }
                
                $save_setoran_keuangan_kasir = $this->setoran_keuangan_kasir_m->save($data_setoran_keuangan, $id);


                if ($save_setoran_keuangan_kasir) 
                {
                    $response->success = true;
                    $response->msg = translate('Petty Cash Berhasil Diedit', $this->session->userdata('language'));
                }

                die(json_encode($response));
            }
            if($array_input['flag'] === 'send'){
                $id = $array_input['id'];

                $data_setoran_keuangan_send = array(
                    'url_bukti_setor' => $array_input['url_bukti_setor'],
                    'subjek'          => $array_input['subjek'],
                    '`status`'        => 4
                );

                $save_setoran_keuangan_kasir_send = $this->setoran_keuangan_kasir_m->save($data_setoran_keuangan_send, $id);

                if($array_input['url_bukti_setor'] != ''){
                    $path_dokumen = './assets/mb/pages/keuangan/kirim_petty_cash/images/'.$save_setoran_keuangan_kasir_send;
                    if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                    $temp_filename = $array_input['url_bukti_setor'];

                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = $array_input['url_bukti_setor'];
                    $real_file = $save_setoran_keuangan_kasir_send.'/'.$new_filename;

                    copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_petty_cash').$real_file);
                }

                if ($save_setoran_keuangan_kasir_send) 
                {
                    $response->success = true;
                    $response->msg = translate('Petty Cash Berhasil Dikirim', $this->session->userdata('language'));
                }

                die(json_encode($response));
            }
        }
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
/* Location: ./application/controllers/keuangan/kirim_petty_cash.php */