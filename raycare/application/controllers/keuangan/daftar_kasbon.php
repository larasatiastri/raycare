<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kasbon extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '52c75532c566787880e0337a7443f723';                  // untuk check bit_access

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

       
        $this->load->model('keuangan/daftar_kasbon/permintaan_biaya_m');

        $this->load->model('master/bank_m');
        $this->load->model('master/user_level_persetujuan_m');

        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/daftar_kasbon/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Daftar Kasbon', $this->session->userdata('language')), 
            'header'         => translate('Daftar Kasbon', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/daftar_kasbon/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {
        // $replace = str_replace('%20', ' ', $date);
        // $bulan = date('Y-m', strtotime($replace));

        $result = $this->permintaan_biaya_m->get_datatable();
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $count = count($records->result_array());

        // die_dump($count);

        $i=0;
        $total_debit = 0;
        $total_kredit = 0;
        $total_saldo = 0;
        $input_debit = '';
        $input_kredit = '';
        $input_saldo = '';
        $status = '';

        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            if($row['status'] == 3)
            {
                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Diproses</span></div>';
            
            } elseif($row['status'] == 5){

                $status = '<div class="text-center"><span class="label label-md label-success">Sudah diproses kasir</span></div>';

            } elseif($row['status'] == 6){

                $status = '<div class="text-center"><span class="label label-md label-danger">Sudah Dibuat Permintaan</span></div>';
            } elseif($row['status'] == 7){

                $status = '<div class="text-center"><span class="label label-md label-danger">Proses Persetujuan</span></div>';
            } elseif($row['status'] == 8){

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';
            } elseif($row['status'] == 9){

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            } elseif($row['status'] == 10){

                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Kwitansi</span></div>';
            }



            $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/daftar_kasbon/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

            $tipe = '';

            if($row['tipe'] == 1){
                $tipe = 'Kasbon';
            }if($row['tipe'] == 2){
                $tipe = 'Reimburse / Pencairan';
            }

            // PopOver Notes
            $notes = explode("\n", $row['keperluan']);
            $notes    = implode('</br>', $notes);

            $output['aaData'][] = array(
                // '<div class="text-left">'.$row['nominal'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal_setujui']).'</div>',
                '<div class="text-left">'.$notes.'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/keuangan/daftar_kasbon/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Add Kasbon", $this->session->userdata("language")), 
            'header'         => translate("Add Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/daftar_kasbon/add',
            'flag'           => 'add',
            'pk_value'       => '',
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("View Kasbon", $this->session->userdata("language")), 
            'header'         => translate("View Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/daftar_kasbon/view',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_pilih_user()
    {

        // $cabang_id = $this->session->userdata('cabang_id');
        // die_dump($this->db->last_query());
                // die_dump($cabang_id);
        $result = $this->user_m->get_datatable_pilih_user_po();
        // die_dump($this->db->last_query());   
        // die_dump($result);
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
            if($row['is_active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id_user'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center">'.$row['nama_user_level'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {

        $array_input = $this->input->post();
        $command = $this->input->post('command');

        // die_dump($array_input);

        if($command === 'add')
        {

            $cek_user_level_persetujuan   = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $array_input['user_level_id'], 'tipe_persetujuan' => 5));
            $user_level_persetujuan_array = object_to_array($cek_user_level_persetujuan);
            

            // die_dump($is_print);
            if(count($cek_user_level_persetujuan))
            {
                // die_dump($is_print);
                // die_dump('data ada');

                //insert juga ke persetujuan
                $data = array(

                    'diminta_oleh_id' => $array_input['id_ref_pasien'],
                    'tipe_transaksi'  => 0,
                    'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'nominal'         => $array_input['nominal'],
                    'keperluan'       => $array_input['keperluan'],
                    'status'          => 1,
                    'is_manual'       => 1,
                    'is_active'       => 1,

                );

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data);
                die_dump($this->db->last_query());
                $update_data = array(

                    'transaksi_id' => $permintaan_biaya_id,

                );

                $update = $this->permintaan_biaya_m->save($update_data, $permintaan_biaya_id);

                //data persetujuan permintaan pembayaran
                foreach ($user_level_persetujuan_array as $persetujuan) 
                {
                    $max_id   = '';
                    $maksimum = $this->persetujuan_permintaan_biaya_m->get_max()->row(0);

                    if(count($maksimum) == NULL)
                    {
                        $max_id = 1;
                    }
                    else {
                        $max_id = $maksimum->max_id;
                        $max_id = $max_id + 1;
                    }

                    $data_persetujuan_permintaan_biaya = array(

                        'persetujuan_permintaan_biaya_id' => $max_id,
                        'permintaan_biaya_id'             => $permintaan_biaya_id,
                        'user_level_id'                   => $persetujuan['user_level_menyetujui_id'],
                        '`order`'                         => $persetujuan['level_order'],
                        '`status`'                        => 1,
                        'is_active'                       => 1,
                        'created_by'                      => $this->session->userdata('user_id'),
                        'created_date'                    => date('Y-m-d H:i:s'),

                    );

                    $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);
                }

                if ($persetujuan_permintaan_biaya_id) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("Data Permintaan Pembayaran berhasil ditambahkan.", $this->session->userdata("language")),
                        "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }

                redirect('keuangan/permintaan_biaya');

            } else {

                // die_dump('data tidak ada');
                // die_dump($is_print);
                //insert juga ke persetujuan
                $data = array(

                    'diminta_oleh_id' => $array_input['id_ref_pasien'],
                    'tipe_transaksi'  => 0,
                    'tanggal'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                    'nominal'         => $array_input['nominal'],
                    'keperluan'       => $array_input['keperluan'],
                    'status'          => 2,
                    'is_manual'       => 1,
                    'is_active'       => 1,

                );

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data);
                // die_dump($this->db->last_query());
                $update_data = array(

                    'transaksi_id' => $permintaan_biaya_id,

                );

                $update = $this->permintaan_biaya_m->save($update_data, $permintaan_biaya_id);

                //data permintaan biaya cetak
                if($is_print == 1)
                {
                    $last_number = $this->permintaan_biaya_cetak_m->get_nomor_cetak()->result_array();
                    $last_number = intval($last_number[0]['max_nomor_cetak'])+1;
                    // die_dump($last_number);
                    
                    $format      = 'BIL-'.date('ymd').'-%03d';
                    $no_cetak    = sprintf($format, $last_number, 3);
                    // die_dump($no_cetak);

                    $data_permintaan_biaya_cetak = array(

                        'permintaan_biaya_id' => $permintaan_biaya_id,
                        'no_cetak'            => $no_cetak,

                    );

                    $permintaan_biaya_cetak_id = $this->permintaan_biaya_cetak_m->save($data_permintaan_biaya_cetak);

                }

                redirect('keuangan/permintaan_biaya');

            }

        }        

    }

}

/* End of file permintaan_biaya.php */
/* Location: ./application/controllers/keuangan/permintaan_biaya.php */