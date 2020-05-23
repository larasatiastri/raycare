<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses_pembayaran_reimburse extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '913796fba1559d9d4dc6bf5997891ca5';                  // untuk check bit_access
    // protected $menu_id = 'keuangan/proses_pembayaran_transaksi';                  // untuk check bit_access

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


        $this->load->model('keuangan/pembayaran_status/voucher_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');
        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_pembayaran_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_bon_m');
        $this->load->model('keuangan/proses_permintaan_biaya/persetujuan_permintaan_biaya_m');
        $this->load->model('keuangan/arus_kas_kasir/kasir_arus_kas_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/bank_m');
        $this->load->model('pembelian/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/biaya_m');
        $this->load->model('pegawai/pegawai_bank_m'); 
        $this->load->model('pegawai/pegawai_user_m'); 
        $this->load->model('keuangan/perubahan_modal/o_s_hutang_m');
       
        $this->load->model('others/kotak_sampah_m');        
    }
    
    public function index()
    {
        
        $assets = array();
        $config = 'assets/keuangan/proses_pembayaran_reimburse/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pembayaran Reimburse', $this->session->userdata('language')), 
            'header'         => translate('Pembayaran Reimburse', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_pembayaran_reimburse/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        
        $assets = array();
        $config = 'assets/keuangan/proses_pembayaran_reimburse/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Pembayaran Reimburse', $this->session->userdata('language')), 
            'header'         => translate('History Pembayaran Reimburse', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_pembayaran_reimburse/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
   
    public function listing()
    {       

        $result = $this->pembayaran_status_m->get_datatable_reimburse();
        // die_dump($result);

        // Output
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
            $i++;

            $tipe = '';
            $status = '';
            $action = '';
            $waktu_akhir = '-';

            $status_detail_awal = $this->pembayaran_status_detail_m->get_data($row['id'],0)->result_array();
            $status_detail_revisi = $this->pembayaran_status_detail_m->get_data($row['id'],1)->result_array();

            if($row['waktu_akhir'] != NULL){
                $waktu_akhir = date('d M Y, H:i', strtotime($row['waktu_akhir']));
            }
            if($row['tipe_transaksi'] == 3){
                $tipe = 'Reimburse';
                if($row['status'] == 1)
                {
                    $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                
                } elseif($row['status'] == 2){

                    $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

                }elseif($row['status'] == 3){

                    $status = '<span class="label label-warning">Verifikasi Dokumen</span>';
                    // $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';
                    $action ='<a title="'.translate('Revisi', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_revisi" href="'.base_url().'keuangan/proses_pembayaran_reimburse/proses_revisi/'.$row['transaksi_id'].'" class="btn red revisi"><i class="fa fa-undo"></i></a><a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/view_reimburse/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/proses_reimburse/'.$row['transaksi_id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';

                } elseif($row['status'] == 4){
                    
                    $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
                }elseif($row['status'] == 5){
                    
                    $status = '<div class="text-center"><span class="label label-md label-default">Diproses</span></div>';
                }elseif($row['status'] == 6){
                    $status = '<div class="text-center"><span class="label label-md label-info">Proses Pengajuan</span></div>';
                }elseif($row['status'] == 7){
                    $status = '<div class="text-center"><span class="label label-md label-info">Proses Persetujuan Direktur</span></div>';
                }elseif($row['status'] == 8){
                    $status = '<div class="text-center"><span class="label label-md label-success">Proses Pencairan</span></div>';
                }
                if($row['status'] == 14){
                    $status = '<span class="label label-danger">Ditolak Keuangan</span>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/view_proses/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';

                }
                if($row['status'] == 16 || $row['status'] == 17){
                    $status = '<span class="label label-danger">Menunggu Diproses</span>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/proses_persetujuan/'.$row['transaksi_id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';

                }if($row['status'] == 18 || $row['status'] == 19){
                    $status = '<span class="label label-info">Diverifikasi Keuangan</span>';                    
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/view_proses/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';

                }if($row['status'] == 20){
                    $status = '<span class="label label-danger">Menunggu Diproses</span>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/proses_persetujuan/'.$row['transaksi_id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';

                }if($row['status'] == 21){
                    $status = '<span class="label label-danger">Proses Revisi</span>';
                    $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/view_reimburse/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';

                }           
            }

            $output['aaData'][] = array(
                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'"></span>',
                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-left">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-left">'.$row['nama_level_proses'].'</div>',
                '<div class="text-center inline-button-table">'.$waktu_akhir.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function listing_history()
    {       

        $result = $this->pembayaran_status_m->get_datatable_reimburse_history();
        // die_dump($result);

        // Output
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
            $i++;

            $tipe = '';
            $status = '';
            $action = '';
            $waktu_akhir = '-';

            $status_detail_awal = $this->pembayaran_status_detail_m->get_data($row['id'],0)->result_array();
            $status_detail_revisi = $this->pembayaran_status_detail_m->get_data($row['id'],1)->result_array();

            if($row['waktu_akhir'] != NULL){
                $waktu_akhir = date('d M Y, H:i', strtotime($row['waktu_akhir']));
            }
            if($row['tipe_transaksi'] == 1){
                $tipe = 'Pembelian';
                if($row['status'] == 12)
                {                    
                    $status = '<div class="text-center"><span class="label label-md label-success">Selesai</span></div>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" class="btn grey-cararra" href="'.base_url().'keuangan/proses_pembayaran_reimburse/view_proses_bayar_po/'.$row['id'].'/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                }
            }if($row['tipe_transaksi'] == 2){
                $tipe = 'Kasbon';
                if($row['status'] == 5){
                    
                    $status = '<div class="text-center"><span class="label label-md label-success">Diproses</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/view_proses/'.$row['transaksi_id'].'"  class="btn grey-cararra"><i class="fa fa-search"></i></a>';
                }
                
            }if($row['tipe_transaksi'] == 3){
                $tipe = 'Reimburse';
                if($row['status'] == 5){
                    
                    $status = '<div class="text-center"><span class="label label-md label-success">Diproses</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/proses_pembayaran_reimburse/view_proses/'.$row['transaksi_id'].'"  class="btn grey-cararra"><i class="fa fa-search"></i></a>';
                }
            }if($row['tipe_transaksi'] == 4){
                $tipe = 'Tanda Terima Faktur';
                if($row['status'] == 3)
                {                    
                    $status = '<div class="text-center"><span class="label label-md label-success">Selesai</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cararra" href="'.base_url().'keuangan/tanda_terima_faktur/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                }
            }

            $output['aaData'][] = array(
                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'"></span>',
                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-left">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-left">'.$row['nama_level_proses'].'</div>',
                '<div class="text-center inline-button-table">'.$waktu_akhir.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }
    

    public function listing_pegawai_bank($user_id)
    {
        $pegawai_user = $this->pegawai_user_m->get_by(array('user_id' => $user_id), true);
        $pegawai_id = $pegawai_user->pegawai_id;

        $result = $this->pegawai_bank_m->get_datatable($pegawai_id);

        // Output
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
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select-pegawai-bank"><i class="fa fa-check"></i></a>';

            $output['aaData'][] = array(
                '<div class="text-left">'.$row['nob'].' - '.$row['cabang_bank'].'</div>',
                '<div class="text-left">'.$row['acc_name'].'</div>',
                '<div class="text-left">'.$row['acc_number'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
            );
            $i++;

        }

        echo json_encode($output);
    }

    
    public function save()
    {
        $array_input = $this->input->post();


        $user_id = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');

        $command = $array_input['command'];

        if($command === 'proses_reimburse')
        {            
            $id = $array_input['id'];
            $level_id = $this->session->userdata('level_id');

            $date = date('Y-m-d');

            $status = 1;

            if($array_input['tipe'] == 1){
                $data = array(
                    'status'          => 16,
                    'tanggal_proses'  => $date,
                    'diproses_oleh'   => $this->session->userdata('user_id')
                );

                $status = 16;

            }if($array_input['tipe'] == 2){
                $data = array(
                    'status'          => 20,
                    'tanggal_proses'  => $date,
                    'diproses_oleh'   => $this->session->userdata('user_id')
                );
                $status = 20;   
            }

            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

            $last_id_os_hutang   = $this->o_s_hutang_m->get_max_id_o_s_hutang()->result_array();
            $last_id_os_hutang   = intval($last_id_os_hutang[0]['max_id'])+1;
            
            $format_id = 'OSH-'.date('mY').'-%04d';
            $id_os_hutang  = sprintf($format_id, $last_id_os_hutang, 4);

            $user = $this->user_m->get_by(array('id' => $array_input['diminta_oleh']), true);

            $insert_os_hutang = array(
                'id'                    => $id_os_hutang,
                'tanggal'               => date('Y-m-d'),
                'transaksi_id'          => $id,
                'tipe_transaksi'        => 3,
                'pemberi_hutang_id'     => $array_input['diminta_oleh'],
                'nama_pemberi_hutang'   => $user->nama,
                'tipe_pemberi_hutang'   => 3,
                'jumlah'                => $array_input['nominal'],
                'created_by'            => $this->session->userdata('user_id'),
                'created_date'          => date('Y-m-d H:i:s'),
            );

            $save_os_hutang = $this->o_s_hutang_m->add_data($insert_os_hutang);

            $keterangan = '';
            if(isset($array_input['bon'])){
                foreach ($array_input['bon'] as $key => $bon) {
                    if($bon['url'] != ''){

                        $keterangan .= $bon['keterangan'].';';

                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/permintaan_biaya/images/'.$id;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $bon['url'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $bon['url'];
                        $real_file = $id.'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bon').$real_file);

                        $data_bon = array(
                            'permintaan_biaya_id' => $id,
                            'biaya_id'            => $bon['biaya_id'],
                            'no_bon'              => $bon['no_bon'],
                            'total_bon'           => $bon['total_bon'],
                            'keterangan'          => $bon['keterangan'],
                            'tgl_bon'             => date('Y-m-d', strtotime($bon['tanggal'])),
                            'url'                 => $bon['url'],
                            'is_active'           => 1
                        );

                        $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_bon);

                    }
                }
            }


            $user_keuangan = $this->user_level_m->get(5);
            $data_bayar = array(
                'status_keuangan' => 3,
                'status'          => $status,
                'user_level_id'   => 5,
                'divisi'          => $user_keuangan->divisi_id,
            );

            $wheres_bayar = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => 3
            );
            $pembayaran_status = $this->pembayaran_status_m->update_by($user_id,$data_bayar,$wheres_bayar);

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_bayar, true);

            $wheres_bayar_detail = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
                'user_level_id'        => $level_id
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);
            // die(dump($this->db->last_query()));

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 1,
                '`order`'              => $pembayaran_status_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $pembayaran_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 2,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'keterangan'     => '-',
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Diproses", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }


        
        if($command === 'persetujuan_biaya'){

            $user_id = $this->session->userdata('user_id');
            $level_id = $this->session->userdata('level_id');
            $date = date('Y-m-d H:i:s');

            $id = $array_input['permintaan_biaya_id'];
            $status = 13;
            $tipe_permintaan = 2;

            if($array_input['tipe_dana'] == 1){
                $tipe_permintaan = 2;
                $status = 13;
            }if($array_input['tipe_dana'] == 2){
                $tipe_permintaan = 3;
                $status = 18;
            }

            $data_minta = array(
                'status' => $status,
                'disetujui_oleh' => $this->session->userdata('user_id')
            );

            $this->permintaan_biaya_m->save($data_minta, $id);

            $user_level_data = $this->user_level_m->get(21);
            $data_status = array(
                'status'        => $status,
                'user_level_id' => 21,
                'divisi'        => $user_level_data->divisi_id,
                'modified_by'   => $user_id,
                'modified_date' => $date
            );      

            $wheres_status = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
            );    

            $update_status = $this->pembayaran_status_m->update_by($user_id,$data_status,$wheres_status);  

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                'user_level_id'  => 5
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                '`order`'        => $pembayaran_status_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $pembayaran_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 2,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);
        }

        if($command === 'persetujuan_biaya_besar'){

            $user_id = $this->session->userdata('user_id');
            $level_id = $this->session->userdata('level_id');
            $date = date('Y-m-d H:i:s');

            $id = $array_input['permintaan_biaya_id'];
            $biaya_bon = $array_input['biaya_bon'];

            foreach ($biaya_bon as $rows) {

                $last_no_voucher   = $this->permintaan_biaya_pembayaran_m->get_max_no_voucher()->result_array();
                $last_no_voucher   = intval($last_no_voucher[0]['max_no_voucher'])+1;
                
                $format_no_voucher = '%03d/'.date('m/y').'/B-'.$bank->nob.'/OUT/RC-KLD';
                $no_voucher  = sprintf($format_no_voucher, $last_no_voucher, 3);

                $data_voucher = array(
                    'nomor_voucher' => $no_voucher,
                    'tipe_voucher' => 1,
                    'transaksi_tipe' => 1,
                    'is_active' => 1,
                    'created_by'          => $user_id,
                    'created_date'        => $date
                );


                $insert_voucher = $this->voucher_m->add_data($data_voucher);
                $voucher_id = $this->db->insert_id();

                $last_kode_bayar   = $this->permintaan_biaya_pembayaran_m->get_max_no_voucher()->result_array();
                $last_kode_bayar   = intval($last_kode_bayar[0]['max_kode'])+1;
                
                $format_kode_bayar = 'PB/%03d/'.date('my');
                $kode_bayar  = sprintf($format_kode_bayar, $last_kode_bayar, 3);

                $last_id_bayar = $this->permintaan_biaya_pembayaran_m->get_max_id_biaya_bon_id()->result_array();
                $last_id_bayar = intval($last_id_bayar[0]['max_id'])+1;

                $format_id_bayar   = 'PBP-'.date('m').'-'.date('Y').'-%04d';
                $id_po_bayar       = sprintf($format_id_bayar, $last_id_bayar, 4);

                $no_tipe = '';
                if($rows['tipe'] == 1){
                    $no_tipe = $rows['bank_no_cek'];
                }if($rows['tipe'] == 2){
                    $no_tipe = $rows['bank_no_giro'];
                }if($rows['tipe'] == 3){
                    $no_tipe = $rows['bank_no_rek'];
                }

                $data_biaya_bon = array(
                    'id'                  => $id_po_bayar,
                    'permintaan_biaya_id' => $id,
                    'permintaan_biaya_bon_id'   => $rows['permintaan_biaya_bon_id'],
                    'kode_pembayaran'           => $kode_bayar,
                    'nomor_voucher'           => $no_voucher,
                    'proses_bayar'           => 1,
                    'pembayaran_tipe'     => $rows['tipe'],
                    'nomor_tipe'          => $no_tipe,
                    'penerima'            => $rows['bank_penerima_cek'],
                    'bank_pegawai_id'     => $rows['bank_pegawai_id'],
                    'nama_bank'           => $rows['bank_nama'],
                    'bank_id'             => $rows['bank_id'],
                    'biaya_tambahan_id'   => $rows['biaya_tambah_id'],
                    'jumlah_biaya'        => $rows['nominal'],
                    'jumlah'              => $rows['total_biaya'],
                    'total'               => $rows['nominal'] + $rows['total_biaya'],
                    'tanggal'             => date('Y-m-d', strtotime($rows['tanggal'])),
                    'jatuh_tempo'         => date('Y-m-d', strtotime($rows['jatuh_tempo'])),
                    'created_by'          => $user_id,
                    'created_date'        => $date
                );

                $permintaan_bayar = $this->permintaan_biaya_pembayaran_m->add_data($data_biaya_bon);
            }

            
            // $status = 13;
            $status = 5;
            $tipe_permintaan = 2;

            if($array_input['tipe_dana'] == 1){
                $tipe_permintaan = 2;
                // $status = 13;
            }if($array_input['tipe_dana'] == 2){
                $tipe_permintaan = 3;
                // $status = 18;
            }

            $data_minta = array(
                'nominal' => $array_input['nominal_setujui'],
                'nominal_setujui' => $array_input['nominal_setujui'],
                'status' => $status,
                'disetujui_oleh' => $this->session->userdata('user_id')
            );

            $this->permintaan_biaya_m->save($data_minta, $id);

            $user_level_data = $this->user_level_m->get(5);
            $data_status = array(
                'status'        => $status,
                'nominal'       => $array_input['nominal_setujui'],
                'user_level_id' => 5,
                'divisi'        => $user_level_data->divisi_id,
                'modified_by'   => $user_id,
                'modified_date' => $date
            );      

            $wheres_status = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
            );    

            $update_status = $this->pembayaran_status_m->update_by($user_id,$data_status,$wheres_status);  

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                'user_level_id'  => 5
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 2)->row(0);

            $wheres_bayar_detail_before = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                '`order`'        => $pembayaran_status_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $pembayaran_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 2,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

            $date = date('Y-m-d', strtotime($array_input['tanggal_jatuh_tempo']));
            $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();

            
            $saldo_before_bank = 0;
            if(count($last_saldo_bank) != 0){
                $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
            }

            $data_arus_kas_bank = array(
                'tanggal'      => $date,
                'tipe'         => 5,
                'transaksi_id' => $id,
                'keterangan'   => $array_input['keterangan'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'C',
                'rupiah'       => $array_input['nominal'],
                'saldo'        => ($saldo_before_bank + $array_input['nominal']),
                'status'       => 1
            );

            $keuangan_arus_kas_id = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after) {
                    $data_arus_kas_after_bank = array(
                        'saldo'        => ($after['saldo'] - $array_input['nominal']),
                    );

                    $arus_kas_bank_edit = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                }
            }

            $last_id       = $this->rekening_koran_m->get_max_id_rekening_koran()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'RK-'.date('m').'-'.date('Y').'-%04d';
            $id_rek_koran         = sprintf($format_id, $last_id, 4);

            $data = array(
                'id'                   => $id_rek_koran,
                'nomor'            => $array_input['nomor_rk'],
                'tanggal'              => date('Y-m-d H:i:s', strtotime($array_input['tanggal_jatuh_tempo'])),
                'bank_id'              => $array_input['bank_id'],
                'jumlah'               => $array_input['nominal'],
                'keterangan'           => $array_input['keterangan'],
                'keuangan_arus_kas_id' => $keuangan_arus_kas_id,
                'is_active'            => 1,
                'created_by'           => $this->session->userdata('user_id'),
                'created_date'         => date('Y-m-d H:i:s')
            );

            $rekening_koran = $this->rekening_koran_m->add_data($data);

            $bank = $this->bank_m->get_by(array('id' => $array_input['bank_id']), true);
        }

        if($command === 'proses_batch'){

            $user_id = $this->session->userdata('user_id');
            $level_id = $this->session->userdata('level_id');
            $date = date('Y-m-d H:i:s');

            $input_pilih = $array_input['input'];

            $bank = $this->bank_m->get_by(array('id' => $array_input['bank_id']), true);

            $last_no_voucher   = $this->permintaan_biaya_pembayaran_m->get_max_no_voucher()->result_array();
            $last_no_voucher   = intval($last_no_voucher[0]['max_no_voucher'])+1;
            
            $format_no_voucher = '%03d/'.date('m/y').'/B-'.$bank->nob.'/OUT/RC-KLD';
            $no_voucher  = sprintf($format_no_voucher, $last_no_voucher, 3);

            $data_voucher = array(
                'nomor_voucher' => $no_voucher,
                'tipe_voucher' => 1,
                'transaksi_tipe' => 1,
                'is_active' => 1,
                'created_by'          => $user_id,
                        'created_date'        => $date
            );


            $insert_voucher = $this->voucher_m->add_data($data_voucher);
            $voucher_id = $this->db->insert_id();

            $last_kode_bayar   = $this->permintaan_biaya_pembayaran_m->get_max_no_voucher()->result_array();
            $last_kode_bayar   = intval($last_kode_bayar[0]['max_kode'])+1;
            
            $format_kode_bayar = 'PB/%03d/'.date('my');
            $kode_bayar  = sprintf($format_kode_bayar, $last_kode_bayar, 3);


            foreach ($input_pilih as $inp_pilih) {
                if(isset($inp_pilih['pilih'])){

                    $last_id_bayar = $this->permintaan_biaya_pembayaran_m->get_max_id_biaya_bon_id()->result_array();
                    $last_id_bayar = intval($last_id_bayar[0]['max_id'])+1;

                    $format_id_bayar   = 'PBP-'.date('m').'-'.date('Y').'-%04d';
                    $id_po_bayar       = sprintf($format_id_bayar, $last_id_bayar, 4);

                    $no_tipe = '';
                    if($array_input['payment_type'] == 1){
                        $no_tipe = $array_input['bank_no_cek'];
                    }if($array_input['payment_type'] == 2){
                        $no_tipe = $array_input['bank_no_giro'];
                    }if($array_input['payment_type'] == 3){
                        $no_tipe = $array_input['bank_pegawai_nomor'];
                    }

                    $data_biaya_bon = array(
                        'id'                        => $id_po_bayar,
                        'permintaan_biaya_id'       => $inp_pilih['pilih'],
                        'permintaan_biaya_bon_id'   => $inp_pilih['permintaan_biaya_bon_id'],
                        'kode_pembayaran'           => $kode_bayar,
                        'nomor_voucher'           => $no_voucher,
                        'pembayaran_tipe'           => $array_input['payment_type'],
                        'proses_bayar'          => 2,
                        'nomor_tipe'          => $no_tipe,
                        'penerima'            => $array_input['bank_penerima_cek'],
                        'bank_pegawai_id'     => $array_input['bank_pegawai_id'],
                        'nama_bank'           => $array_input['bank_pegawai_name'],
                        'bank_id'             => $array_input['bank_id'],
                        'jumlah'              => $inp_pilih['nominal_bon'],
                        'total'               => $array_input['nominal'],
                        'tanggal'             => date('Y-m-d', strtotime($array_input['tanggal'])),
                        'jatuh_tempo'         => date('Y-m-d', strtotime($array_input['jatuh_tempo'])),
                        'created_by'          => $user_id,
                        'created_date'        => $date
                    );


                    $permintaan_bayar = $this->permintaan_biaya_pembayaran_m->add_data($data_biaya_bon);
                    // die_dump($this->db->last_query());

                    // $status = 13;
                    $status = 5;
                    $tipe_permintaan = 3;

                    $data_minta = array(
                        'nominal' => $inp_pilih['nominal_bon'],
                        'nominal_setujui' => $inp_pilih['nominal_bon'],
                        'status' => $status,
                        'disetujui_oleh' => $this->session->userdata('user_id')
                    );

                    $this->permintaan_biaya_m->save($data_minta,  $inp_pilih['pilih']);

                    $user_level_data = $this->user_level_m->get(5);
                    $data_status = array(
                        'status'        => $status,
                        'nominal'       => $inp_pilih['nominal_bon'],
                        'user_level_id' => 5,
                        'divisi'        => $user_level_data->divisi_id,
                        'modified_by'   => $user_id,
                        'modified_date' => $date
                    );      

                    $wheres_status = array(
                        'transaksi_id'   =>  $inp_pilih['pilih'],
                        'tipe_transaksi' => $tipe_permintaan,
                    );    

                    $update_status = $this->pembayaran_status_m->update_by($user_id,$data_status,$wheres_status);  

                    $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

                    $wheres_bayar_detail = array(
                        'transaksi_id'   =>  $inp_pilih['pilih'],
                        'tipe_transaksi' => $tipe_permintaan,
                        'tipe_pengajuan' => 0,
                        'tipe'           => 2,
                        'user_level_id'  => 5
                    );
                    $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 2)->row(0);

                    $wheres_bayar_detail_before = array(
                        'transaksi_id'   =>  $inp_pilih['pilih'],
                        'tipe_transaksi' => $tipe_permintaan,
                        'tipe_pengajuan' => 0,
                        'tipe'           => 2,
                        '`order`'        => $pembayaran_status_detail_id->order - 1
                    );

                    $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

                    if(count($pembayaran_status_detail_before) != 0){
                        $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
                    }else{
                        $waktu_proses = $pembayaran_status_id->created_date;
                    }

                    $datetime1 = new DateTime();
                    $datetime2 = new DateTime($waktu_proses);
                    $interval = $datetime1->diff($datetime2);
                    $elapsed = $interval->format('%a d %h h %i m %S s');

                    $data_pembayaran_status_detail = array(
                        'status'         => 2,
                        'tanggal_proses' => date('Y-m-d H:i:s'),
                        'user_proses'    => $user_id,
                        'waktu_tunggu'   => $elapsed,
                        'modified_by'    => $user_id,
                        'modifed_date'   => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);
                }
            }

            $date = date('Y-m-d', strtotime($array_input['tanggal_jatuh_tempo']));
            $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();

            
            $saldo_before_bank = 0;
            if(count($last_saldo_bank) != 0){
                $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
            }

            $data_arus_kas_bank = array(
                'tanggal'      => $date,
                'tipe'         => 5,
                'transaksi_id' => $id,
                'keterangan'   => $array_input['keterangan'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'C',
                'rupiah'       => $array_input['nominal'],
                'saldo'        => ($saldo_before_bank + $array_input['nominal']),
                'status'       => 1
            );

            $keuangan_arus_kas_id = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after) {
                    $data_arus_kas_after_bank = array(
                        'saldo'        => ($after['saldo'] - $array_input['nominal']),
                    );

                    $arus_kas_bank_edit = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                }
            }

            $last_id       = $this->rekening_koran_m->get_max_id_rekening_koran()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'RK-'.date('m').'-'.date('Y').'-%04d';
            $id_rek_koran         = sprintf($format_id, $last_id, 4);

            $data = array(
                'id'                   => $id_rek_koran,
                'nomor'            => $array_input['nomor_rk'],
                'tanggal'              => date('Y-m-d H:i:s', strtotime($array_input['tanggal_jatuh_tempo'])),
                'bank_id'              => $array_input['bank_id'],
                'jumlah'               => $array_input['nominal'],
                'keterangan'           => $array_input['keterangan'],
                'keuangan_arus_kas_id' => $keuangan_arus_kas_id,
                'is_active'            => 1,
                'created_by'           => $this->session->userdata('user_id'),
                'created_date'         => date('Y-m-d H:i:s')
            );

            $rekening_koran = $this->rekening_koran_m->add_data($data);

            $bank = $this->bank_m->get_by(array('id' => $array_input['bank_id']), true);
           
        }

        redirect('keuangan/proses_pembayaran_reimburse');
    }

    public function view_biaya($id_po)
    {
        $data_biaya = $this->pembelian_biaya_m->get_data($id_po)->result_array();
        $form_data = $this->pembelian_m->get_data($id_po)->result_array();

        $data = array(
            'data_biaya' => $data_biaya,
            'form_data' => $form_data,
        );
        $this->load->view('keuangan/proses_pembayaran_reimburse/view_biaya', $data);
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

    public function view_reimburse($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("View Reimburse", $this->session->userdata("language")), 
            'header'         => translate("View Reimburse", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_permintaan_biaya/view_proses',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            'form_data_bon'  => object_to_array($form_data_bon)
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_kasbon($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));

        $data = array(
            'title'          => config_item('site_name').' | '. translate("View Kasbon", $this->session->userdata("language")), 
            'header'         => translate("View Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_permintaan_biaya/view_proses',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            'form_data_bon'  => object_to_array($form_data_bon)
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses_reimburse($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));

        // die_dump($form_data_detail);

        $data = array(
            'title'            => config_item('site_name').' | '. translate("Proses Reimburse", $this->session->userdata("language")), 
            'header'           => translate("Proses Reimburse", $this->session->userdata("language")), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => TRUE,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'keuangan/proses_permintaan_biaya/proses',
            'flag'             => 'reimburse',
            'pk_value'         => $id,
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function proses_batch()
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_pembayaran_reimburse/proses_batch';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'            => config_item('site_name').' | '. translate("Proses Reimburse", $this->session->userdata("language")), 
            'header'           => translate("Proses Reimburse", $this->session->userdata("language")), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => TRUE,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'keuangan/proses_pembayaran_reimburse/proses_batch',
            'flag'             => 'keuangan',
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses_revisi($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);

        $data = array(  
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
        );

        // Load the view
        $this->load->view('keuangan/proses_permintaan_biaya/proses_revisi', $data);
    }

    public function revisi()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();
            $level_id = $this->session->userdata('level_id');

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Permintaan biaya gagal direvisi', $this->session->userdata('language'));

            $tipe_transaksi = 2;
            if($array_input['tipe_biaya'] == 1){
                $tipe_transaksi = 2;
            }if($array_input['tipe_biaya'] == 2){
                $tipe_transaksi = 3;
            }

            $data_biaya = array(
                'status_revisi' => 1,
                'keterangan_revisi' => $array_input['keterangan_tolak'],
            );

            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data_biaya, $array_input['permintaan_biaya_id']);

            $data_bayar = array(
                'status'        => 21,
                'status_revisi' => 1,
            );

            $wheres_bayar = array(
                'transaksi_id' => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_transaksi
            );

            $edit_pembayaran_status = $this->pembayaran_status_m->update_by($level_id, $data_bayar, $wheres_bayar);

            $data_pembayaran = $this->pembayaran_status_m->get_by($wheres_bayar, true);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['permintaan_biaya_id'],
                'tipe_transaksi' => $tipe_transaksi,
                'user_level_id'  => 21,
                'tipe'           => 2,
                'tipe_pengajuan' => 0
            );

            $data_pembayaran_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);
            if(count($data_pembayaran_detail_id) != 0){
                $data_bayar_detail = array(
                    'status'        => 4,
                );

                $edit_bayar_detail = $this->pembayaran_status_detail_m->update_by($level_id, $data_bayar_detail, $data_pembayaran_detail_id->id);
            }



            if($permintaan_biaya_id){
                $response->success = true;
                $response->msg = translate('Permintaan biaya berhasil direvisi', $this->session->userdata('language'));
            }
            
            die(json_encode($response));
        }
    }

    public function proses_persetujuan($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_pembayaran_reimburse/proses_persetujuan';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $user_level_id = $this->session->userdata('level_id');

        $form_data = $this->permintaan_biaya_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id, 'is_active' => 1));

        $user_minta_id = $form_data->diminta_oleh_id;
        $pegawai_user = $this->pegawai_user_m->get_by(array('user_id' => $user_minta_id), true);

        $user_id = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');
        $date = date('Y-m-d H:i:s');

        $tipe_permintaan = 2;

        if($form_data->tipe == 1){
            $tipe_permintaan = 2;
        }else{
            $tipe_permintaan = 3;
        }

        if($form_data->status == 11){
            $data_minta = array(
                'status' => 12,
            );

            $this->permintaan_biaya_m->save($data_minta, $id);

            $user_level_data = $this->user_level_m->get(5);
            $data_status = array(
                'status'        => 12,
                'user_level_id' => 5,
                'divisi'        => $user_level_data->divisi_id,
                'modified_by'   => $user_id,
                'modified_date' => $date
            );      

            $wheres_status = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
            );    

            $update_status = $this->pembayaran_status_m->update_by($user_level_id,$data_status,$wheres_status);  

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                'user_level_id'  => 5
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                '`order`'        => $pembayaran_status_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $pembayaran_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 2,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

        }if($form_data->status == 16){
            $data_minta = array(
                'status' => 17
            );

            $this->permintaan_biaya_m->save($data_minta, $id);

            $user_level_data = $this->user_level_m->get(5);
            $data_status = array(
                'status'        => 17,
                'user_level_id' => 5,
                'divisi'        => $user_level_data->divisi_id,
                'modified_by'   => $user_id,
                'modified_date' => $date
            );      

            $wheres_status = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
            );    

            $update_status = $this->pembayaran_status_m->update_by($user_level_id,$data_status,$wheres_status);  

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

            $wheres_bayar_detail = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                'user_level_id'  => 5
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_permintaan,
                'tipe_pengajuan' => 0,
                'tipe'           => 2,
                '`order`'        => $pembayaran_status_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

            if(count($pembayaran_status_detail_before) != 0){
                $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
            }else{
                $waktu_proses = $pembayaran_status_id->created_date;
            }

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $data_pembayaran_status_detail = array(
                'status'         => 1,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);
        }

        $data = array(
            'title'            => config_item('site_name').' &gt;'. translate("Proses Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header'           => translate("Proses Persetujuan Permintaan Biaya", $this->session->userdata("language")), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => TRUE,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'keuangan/proses_pembayaran_reimburse/proses_persetujuan',
            'flag'             => 'proses',
            'pk_value'         => $id,
            'pegawai_id'       => $pegawai_user->pegawai_id,
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function reject_proses($permintaan_biaya_id,$tipe)
    {
        $data = array( 
            'permintaan_biaya_id'             => $permintaan_biaya_id,
            'tipe_dana'             => $tipe,
        );

        $this->load->view('keuangan/proses_pembayaran_reimburse/modal_reject', $data);
    }

    
    public function tolak_permintaan()
    {
        $array_input = $this->input->post();
        $user        = $this->session->userdata('user_id');
        $date        = date('Y-m-d H:i:s');
        $user_level_id = $this->session->userdata('level_id');
        $status = 0;

        $tipe_permintaan = 2;
        if($array_input['tipe_dana'] == 1){
            $tipe_permintaan = 2;
        }if($array_input['tipe_dana'] == 2){
            $tipe_permintaan = 3;
        }
        

        if($array_input['tipe'] == 1){
            $data_permintaan = array(
                'status'          => 4,
                'nominal_setujui' => 0
            );         
            $status = 4;  
        }if($array_input['tipe'] == 2){
            $data_permintaan = array(
                'status'          => 14,
                'nominal_setujui' => 0
            ); 
            $status = 14;          
        }if($array_input['tipe'] == 3){
            $data_permintaan = array(
                'status'          => 19,
                'nominal_setujui' => 0
            );  
            $status = 19;         
        }
        $update_permintaan = $this->permintaan_biaya_m->save($data_permintaan, $array_input['permintaan_biaya_id']);   

        $user_level_data = $this->user_level_m->get(5);
        $data_status = array(
            'status'        => $status,
            'user_level_id' => 5,
            'divisi'        => $user_level_data->divisi_id,
            'modified_by'   => $user,
            'modified_date' => $date
        );      

        $wheres_status = array(
            'transaksi_id'   => $array_input['permintaan_biaya_id'],
            'tipe_transaksi' => $tipe_permintaan,
        );    

        $update_status = $this->pembayaran_status_m->update_by($user_level_id,$data_status,$wheres_status);  

        $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_status, true);

        $wheres_bayar_detail = array(
            'transaksi_id'   => $array_input['permintaan_biaya_id'],
            'tipe_transaksi' => $tipe_permintaan,
            'tipe_pengajuan' => 0,
            'tipe'           => 1,
            'user_level_id'  => $user_level_id
        );
        $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

        $wheres_bayar_detail_before = array(
            'transaksi_id'   => $array_input['permintaan_biaya_id'],
            'tipe_transaksi' => $tipe_permintaan,
            'tipe_pengajuan' => 0,
            'tipe'           => 1,
            '`order`'        => $pembayaran_status_detail_id->order - 1
        );

        $pembayaran_status_detail_before = $this->pembayaran_status_detail_m->get_by($wheres_bayar_detail_before, true);

        if(count($pembayaran_status_detail_before) != 0){
            $waktu_proses = $pembayaran_status_detail_before->tanggal_proses;
        }else{
            $waktu_proses = $pembayaran_status_id->created_date;
        }

        $datetime1 = new DateTime();
        $datetime2 = new DateTime($waktu_proses);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%a d %h h %i m %S s');

        $data_pembayaran_status_detail = array(
            'status'         => 3,
            'tanggal_proses' => date('Y-m-d H:i:s'),
            'user_proses'    => $user_id,
            'waktu_tunggu'   => $elapsed,
            'keterangan'     => $array_input['keterangan_tolak'],
            'modified_by'    => $user_id,
            'modifed_date'   => date('Y-m-d H:i:s')
        );

        $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id); 
       

        if ($update || $history_persetujuan_permintaan_biaya_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data Permintaan Ditolak.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('keuangan/proses_pembayaran_reimburse');

    }

    public function view_proses($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));
        $form_data_pembayaran = $this->permintaan_biaya_pembayaran_m->get_by(array('permintaan_biaya_id' => $id), true);
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
            'content_view'   => 'keuangan/proses_permintaan_biaya/view_proses',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            'form_data_bon'  => object_to_array($form_data_bon),
            'form_data_pembayaran'  => object_to_array($form_data_pembayaran)
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function get_data_invoice()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;

            $array_input = $this->input->post();

            $user_id = $array_input['user_id'];

            $data_permintaan_bon = $this->permintaan_biaya_bon_m->get_invoice_user($user_id)->result_array();
            // die_dump($data_permintaan_bon);

            if(count($data_permintaan_bon)){
                $response->success = true;
                $response->data = $data_permintaan_bon;
            }

            die(json_encode($response));
        }          
    }


}

/* End of file pembayaran_transaksi.php */
/* Location: ./application/controllers/keuangan/proses_pembayaran_reimburse.php */