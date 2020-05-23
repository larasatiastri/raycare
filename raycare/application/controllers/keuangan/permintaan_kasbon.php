<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_kasbon extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '2b4e0cf0d7390ed5e70e6dab913dcc6f';                  // untuk check bit_access
    // protected $menu_id = 'keuangan/permintaan_kasbon';                  // untuk check bit_access

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

       
        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_m');
        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_barang_m');
        $this->load->model('keuangan/permintaan_biaya/biaya_permintaan_dana_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_bon_m');
         $this->load->model('pembelian/pembelian_invoice_m');

        $this->load->model('keuangan/permintaan_biaya/persetujuan_permintaan_biaya_m');

        $this->load->model('keuangan/permintaan_biaya/persetujuan_permintaan_biaya_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');

        $this->load->model('keuangan/pengajuan_pemegang_saham_m');        
        $this->load->model('keuangan/pembayaran_masuk/pembayaran_masuk_status_m');        

        $this->load->model('master/biaya_m');
        $this->load->model('master/bank_m');
        $this->load->model('master/divisi_m');
        $this->load->model('master/user_level_persetujuan_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('pembelian/o_s_pmsn_m');

        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/permintaan_kasbon/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Permintaan Dana', $this->session->userdata('language')), 
            'header'         => translate('Permintaan Dana', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/permintaan_kasbon/index',
            // 'content_view'   => 'under_maintenance',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/keuangan/permintaan_kasbon/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Permintaan Kasbon', $this->session->userdata('language')), 
            'header'         => translate('History Permintaan Kasbon', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/permintaan_kasbon/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_status()
    {       

        $result = $this->pembayaran_status_m->get_datatable_biaya_kasbon();
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
            if($row['tipe_transaksi'] == 2){
                $tipe = 'Kasbon';

                if($row['status'] == 1)
                {
                    $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                
                } elseif($row['status'] == 2){

                    $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

                }elseif($row['status'] == 3){

                    $status = '<span class="label label-danger">Menunggu Diproses</span>';
                    

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
                }if($row['status'] == 11){
                    $status = '<span class="label label-warning">Menunggu Persetujuan Keuangan</span>';
                }if($row['status'] == 12){
                    $status = '<span class="label label-warning">Proses Persetujuan Keuangan</span>';
                }if($row['status'] == 13){
                    $status = '<span class="label label-success">Disetujui Keuangan</span>';
                    
                    
                }if($row['status'] == 15){
                    $status = '<span class="label label-warning">Menunggu Verif Bon</span>';
                    $action .= '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_kasbon/proses_verifikasi/'.$row['transaksi_id'].'"  class="btn btn-success"><i class="fa fa-cogs"></i></a>';
                    
                }if($row['status'] == 16){
                    $status = '<span class="label label-warning">Menunggu Verifikasi Keuangan</span>';
                    
                }if($row['status'] == 18 || $row['status'] == 19){
                    $status = '<span class="label label-info">Diverifikasi Keuangan</span>';
                }
                if($row['status'] == 21){
                    $status = '<span class="label label-warning">Proses Revisi</span>';
                    $action .= '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/revisi/'.$row['transaksi_id'].'"  class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                }
                $action .='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/view/'.$row['transaksi_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

            }if($row['tipe_transaksi'] == 3){
                $tipe = 'Reimburse';
                if($row['status'] == 1)
                {
                    $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                
                } elseif($row['status'] == 2){

                    $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

                }elseif($row['status'] == 3){

                    $status = '<span class="label label-danger">Menunggu Diproses</span>';

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
                if($row['status'] == 16){
                    $status = '<span class="label label-warning">Menunggu Verifikasi Keuangan</span>';
                }if($row['status'] == 18 || $row['status'] == 19){
                    $status = '<span class="label label-info">Diverifikasi Keuangan</span>';
                }if($row['status'] == 20){
                    $status = '<span class="label label-warning">Menunggu Proses Keuangan</span>';
                }
                if($row['status'] == 21){
                    $status = '<span class="label label-warning">Proses Revisi</span>';
                    $action .= '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/revisi/'.$row['transaksi_id'].'"  class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                }
            $action .='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/view_reimburse/'.$row['transaksi_id'].'"  class="btn default"><i class="fa fa-search"></i></a>';


            }


            $output['aaData'][] = array(
                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'"></span>',
                '<div class="text-center">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-left">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-left">'.$row['nama_level_proses'].'</div>',
                '<div class="text-center">'.$waktu_akhir.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function listing_history()
    {

        $result = $this->permintaan_biaya_m->get_datatable_history();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $count = count($records->result_array());
    
        $i=0;
        foreach($records->result_array() as $row)
        {
            $status = '';
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            if($row['status'] == 1)
            {
                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
            
            } elseif($row['status'] == 2){

                $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

            }elseif($row['status'] == 3){

                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

            } elseif($row['status'] == 4){

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            }elseif($row['status'] == 5){

                $status = '<div class="text-center"><span class="label label-md label-default">Diproses</span></div>';
            }

            $action ='<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/permintaan_biaya/view/'.$row['id'].'"  class="btn default"><i class="fa fa-search"></i></a>';

            
            $tipe = '';

            if($row['tipe'] == 1){
                $tipe = 'Kas';
            }if($row['tipe'] == 2){
                $tipe = 'Reimburse / Pencairan';
            }
            // PopOver Notes
            $notes    = $row['keperluan'];
        
            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>',
                '<div class="text-left">'.$tipe.'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$notes.'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',

            );
        
            $i++;
        
        }

        echo json_encode($output);

    }

    public function add($param_item = NULL, $param_item_satuan = NULL, $param_jumlah = NULL)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/permintaan_kasbon/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data_item = array();
        $data_item_satuan = array();
        $data_jumlah = array();

        $data_item = unserialize(base64_decode(urldecode($param_item)));
        $data_item_satuan = unserialize(base64_decode(urldecode($param_item_satuan)));
        $data_jumlah = unserialize(base64_decode(urldecode($param_jumlah)));

        // die(dump($data_item));

        $data = array(
            'title'             => config_item('site_name').' | '. translate("Tambah Permintaan Kasbon", $this->session->userdata("language")), 
            'header'            => translate("Tambah Permintaan Kasbon", $this->session->userdata("language")), 
            'header_info'       => config_item('site_name'), 
            'breadcrumb'        => TRUE,
            'menus'             => $this->menus,
            'menu_tree'         => $this->menu_tree,
            'css_files'         => $assets['css'],
            'js_files'          => $assets['js'],
            'data_item'         => $data_item,
            'data_item_satuan'  => $data_item_satuan,
            'data_jumlah'       => $data_jumlah,
            'content_view'      => 'keuangan/permintaan_kasbon/add',
            'flag'              => 'add',
            'pk_value'          => '',
            
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

            $response->terbilang = '#'.$terbilang.' Rupiah#';

            die(json_encode($response));
        }
    }

    public function save()
    {
        $array_input = $this->input->post();
        $command = $this->input->post('command');
        $user_id = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');

        $user_level_buat = $this->user_level_m->get($level_id);
        $divisi_buat = $this->divisi_m->get($user_level_buat->divisi_id);

        if($command == 'proses_verif'){

            // die(dump($array_input));

            $id = $array_input['id'];
            $biaya_id = $array_input['biaya_id'];
            $status_kasbon = 5;
            $date = date('Y-m-d');

            $data = array(
                'status'          => $status_kasbon,
                'tanggal_proses'  => $date,
                'diproses_oleh'   => $this->session->userdata('user_id')
            );

            $edit_permintaan_kasbon = $this->permintaan_biaya_m->save($data,$id);

            $wheres_bayar = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => 2
            );

            $pembayaran_status = $this->pembayaran_status_m->get_by($wheres_bayar, true);

            $user_keuangan = $this->user_level_m->get(5);
            $data_bayar = array(
                'status_keuangan' => 3,
                'status'          => $status_kasbon,
                'user_level_id'   => 5,
                'divisi'          => $user_keuangan->divisi_id,
            );

            $edit_pembayaran_status = $this->pembayaran_status_m->update_by($user_id,$data_bayar,$wheres_bayar);


            if(isset($array_input['bon'])){
                foreach ($array_input['bon'] as $key => $bon) {
                    if($bon['url'] != ''){

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
                            'biaya_id'            => $biaya_id,
                            'no_bon'              => $bon['no_bon'],
                            'total_bon'           => $bon['total_bon'],
                            'keterangan'          => $bon['keterangan'],
                            'tgl_bon'             => date('Y-m-d', strtotime($bon['tanggal'])),
                            'url'                 => $bon['url'],
                            'is_active'           => 1
                        );

                        $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_bon);

                        $last_id_invoice = $this->pembelian_invoice_m->get_max_id_invoice_pembelian()->result_array();
                        $last_id_invoice = intval($last_id_invoice[0]['max_id'])+1;

                        $format_id_invoice   = 'POI-'.date('m').'-'.date('Y').'-%04d';
                        $id_po_invoice       = sprintf($format_id_invoice, $last_id_invoice, 4);

                        $dpp = ($bon['total_bon'] / 1.1);

                        $data_bon = array(
                            'id'            => $id_po_invoice,
                            'pembelian_id'  => $array_input['id_po'],
                            'pembayaran_status_id'  => $pembayaran_status->id,
                            'no_invoice'    => $bon['no_bon'],
                            'total_invoice' => $bon['total_bon'],
                            'tgl_invoice'   => date('Y-m-d', strtotime($bon['tanggal'])),
                            'keterangan'    => $bon['keterangan'],
                            'url'           => $bon['url'],
                            'no_faktur_pajak'           => $bon['no_faktur_pajak'],
                            'url_faktur_pajak' => $bon['url_faktur_pajak'],
                            'tanggal_faktur_pajak'   => date('Y-m-d', strtotime($bon['tanggal'])),
                            'dpp'           => $dpp,
                            'ppn_nominal'           => ($dpp * 0.1),
                            'is_active'     => 1,
                            'created_by'    => $this->session->userdata('user_id'),
                            'created_date'  => date('Y-m-d H:i:s'),
                        );

                        $pembelian_invoice = $this->pembelian_invoice_m->add_data($data_bon);

                    }
                }
            }

            //jika nominal kasbon lebih kecil dari nominal sebenarnya
            if($array_input['nominal'] < $array_input['nominal_bon']){

                $nominal_tambah = ($array_input['nominal_bon'] - $array_input['nominal']);
               
                $last_number   = $this->permintaan_biaya_m->get_no_permintaan()->result_array();
                $last_number   = intval($last_number[0]['max_no_permintaan'])+1;

                $format        = '#CH#%03d/RCKLD-'.strtoupper($divisi_buat->kode).'/'.romanic_number(date('m'), true).'/'.date('Y');
                $tipe_transaksi = 2;
                $waktu_akhir = date('Y-m-d H:i:s', strtotime("+48 hours"));
                
                $no_permintaan     = sprintf($format, $last_number, 3);

                $data = array(
                    'nomor_permintaan' => $no_permintaan,
                    'diminta_oleh_id'  => $user_id,
                    'tanggal'          => date('Y-m-d H:i:s'),
                    'nominal'          => $nominal_tambah,
                    'nominal_setujui'  => $nominal_tambah,
                    'keperluan'        => 'Permintaan Biaya Tambahan untuk kekurangan dari kasbon nomor '.$array_input['nomor_permintaan'],
                    'biaya_id'         => 'COST-05-2018-0049',
                    'tipe'             => 1,
                    'tipe_kasbon'      => 2,
                    'status'           => 11,
                    'status_revisi'    => 0,
                    'status_proses'    => 0,
                    'is_active'        => 1,

                );

                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data);

                $last_id_status       = $this->pembayaran_status_m->get_max_id_pembayaran()->result_array();
                $last_id_status       = intval($last_id_status[0]['max_id'])+1;
                
                $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
                $id_status           = sprintf($format_id_status, $last_id_status, 4);

                $divisi_posisi = $this->user_level_m->get(5);

                $data_status = array(
                    'id'              => $id_status,
                    'transaksi_id'    => $permintaan_biaya_id,
                    'transaksi_nomor' => $no_permintaan,
                    'tipe_transaksi'  => 2,
                    'nominal'         => $nominal_tambah,
                    'status'          => 11,
                    'user_level_id'   => 5,
                    'divisi'          => $divisi_posisi->divisi_id,
                    'waktu_akhir'     => $waktu_akhir,
                    'is_active'       => 1,
                    'created_by'      => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')
                );

                $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);

                $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                
                $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                $data_status_detail = array(
                    'id'                   => $id_status_detail,
                    'pembayaran_status_id' => $id_status,
                    'transaksi_id'         => $permintaan_biaya_id,
                    'tipe_transaksi'       => 2,
                    '`order`'              => 1,
                    'divisi'               => $divisi_posisi->divisi_id,
                    'user_level_id'        => 5,
                    'tipe'                 => 1,
                    'tipe_pengajuan'       => 0,
                    'is_active'            => 1,
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s')
                );

                $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);

            }
            if($array_input['nominal'] > $array_input['nominal_bon']){

                $nominal_kembali = ($array_input['nominal'] - $array_input['nominal_bon']);

                $last_id = $this->pengajuan_pemegang_saham_m->get_max_id()->result_array();
                $last_id = intval($last_id[0]['max_id'])+1;

                $format_id   = 'PPS-'.date('my').'-%03d';
                $id_pps    = sprintf($format_id, $last_id, 3);

                $last_number = $this->pengajuan_pemegang_saham_m->get_max_nomor()->result_array();
                $last_number = intval($last_number[0]['max_id'])+1;

                $format_number   = 'PPS/'.date('my').'/%03d';
                $number_pps    = sprintf($format_number, $last_number, 3);

                $data_bank = $this->bank_m->get_by(array('is_active' => 1), true);

                $data_pps = array(
                    'id' => $id_pps,
                    'tanggal' => date('Y-m-d'),
                    'nomor_pengajuan' => $number_pps,
                    'nominal' => $nominal_kembali,
                    'keterangan' => 'Pengembalian dana untuk kelebihan nominal dari kasbon nomor '.$array_input['nomor_permintaan'],
                    'bank_id' => $data_bank->id,
                    'status' => 2,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_date' => date('Y-m-d H:i:s')
                );

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
                    'status' => 2,
                    'status_keuangan' => 1,
                    'user_level_id' => 5,
                    'divisi' => 7,
                    'nominal' => $nominal_kembali,
                    'waktu_akhir' => date('Y-m-d', strtotime('+7 days')),
                    'is_active' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_date' => date('Y-m-d H:i:s'),
                );

                $add_bayar_masuk = $this->pembayaran_masuk_status_m->add_data($data_pembayaran_masuk);


            }


        }

        if($command == 'add'){

            $level_id = $this->session->userdata('level_id');
            $user_level_buat = $this->user_level_m->get($level_id);

            $divisi_buat = $this->divisi_m->get($user_level_buat->divisi_id);

            $cek_user_level_persetujuan   = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $array_input['user_level_id'], 'tipe_persetujuan' => 5, 'is_active' => 1));
            $user_level_persetujuan_array = object_to_array($cek_user_level_persetujuan);

            $last_number   = $this->permintaan_biaya_m->get_no_permintaan()->result_array();
            $last_number   = intval($last_number[0]['max_no_permintaan'])+1;
            
            if($array_input['tipe'] == 1){
                $format        = '#CH#%03d/RCKLD-'.strtoupper($divisi_buat->kode).'/'.romanic_number(date('m'), true).'/'.date('Y');
                $tipe_transaksi = 2;
                $waktu_akhir = date('Y-m-d H:i:s', strtotime("+48 hours"));
            }
            $no_permintaan     = sprintf($format, $last_number, 3);

            $data = array(
                'nomor_permintaan' => $no_permintaan,
                'diminta_oleh_id'  => $array_input['id_ref_pasien'],
                'tanggal'          => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'nominal'          => $array_input['nominal'],
                'nominal_setujui'  => $array_input['nominal'],
                'keperluan'        => $array_input['keperluan'],
                'biaya_id'         => 'COST-05-2018-0049',
                'tipe'             => 1,
                'status'           => 1,
                'status_revisi'    => 0,
                'status_proses'    => 0,
                'is_active'        => 1,

            );

            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data);

            $last_id_status       = $this->pembayaran_status_m->get_max_id_pembayaran()->result_array();
            $last_id_status       = intval($last_id_status[0]['max_id'])+1;
            
            $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
            $id_status           = sprintf($format_id_status, $last_id_status, 4);

            $posisi = $user_level_persetujuan_array[0]['user_level_menyetujui_id'];
            $divisi_posisi = $this->user_level_m->get($posisi);

            $data_status = array(
                'id'              => $id_status,
                'transaksi_id'    => $permintaan_biaya_id,
                'transaksi_nomor' => $no_permintaan,
                'tipe_transaksi'  => $tipe_transaksi,
                'nominal'         => $array_input['nominal'],
                'status'          => 1,
                'user_level_id'   => $posisi,
                'divisi'          => $divisi_posisi->divisi_id,
                'waktu_akhir'     => $waktu_akhir,
                'is_active'       => 1,
                'created_by'      => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
            );

            $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);

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
                    'tipe'                            => 1,
                    '`order`'                         => $persetujuan['level_order'],
                    '`status`'                        => 1,
                    'is_active'                       => 1,
                    'created_by'                      => $this->session->userdata('user_id'),
                    'created_date'                    => date('Y-m-d H:i:s'),

                );

                $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);

                $user_level = $this->user_level_m->get($persetujuan['user_level_menyetujui_id']);

                $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                
                $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                $id_status_detail         = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                $data_status_detail = array(
                    'id'                   => $id_status_detail,
                    'pembayaran_status_id' => $id_status,
                    'transaksi_id'         => $permintaan_biaya_id,
                    'tipe_transaksi'       => $tipe_transaksi,
                    '`order`'              => $persetujuan['level_order'],
                    'divisi'               => $user_level->divisi_id,
                    'user_level_id'        => $persetujuan['user_level_menyetujui_id'],
                    'tipe'                 => 1,
                    'tipe_pengajuan'       => 0,
                    'is_active'            => 1,
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                );

                $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
            }

            $order_status_kasir = count($user_level_persetujuan_array) + $count_biaya_setuju;
            for($i=0; $i<2; $i++){
                $order_status_kasir = $order_status_kasir + 1;

                $last_id_status_detail       = $this->pembayaran_status_detail_m->get_max_id_pembayaran_detail()->result_array();
                $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                
                $format_id_status_detail     = 'PSD-'.date('m').'-'.date('Y').'-%04d';
                $id_status_detail            = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                $user_level = $this->user_level_m->get(5);

                $divisi_id = $user_level->divisi_id;
                $user_level_id = 5;

                $data_status_detail = array(
                    'id'                   => $id_status_detail,
                    'pembayaran_status_id' => $id_status,
                    'transaksi_id'         => $permintaan_biaya_id,
                    'tipe_transaksi'       => $tipe_transaksi,
                    '`order`'              => $order_status_kasir,
                    'divisi'               => $divisi_id,
                    'user_level_id'        => $user_level_id,
                    'tipe'                 => 2,
                    'tipe_pengajuan'       => 0,
                    'is_active'            => 1,
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s', strtotime($array_input['tanggal']))
                );

                $pembayaran_status_detail = $this->pembayaran_status_detail_m->add_data($data_status_detail);
            } 


            foreach ($array_input['biaya'] as $item) {
                $data_os_pesan = $this->o_s_pmsn_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['item_satuan_id'], 'status' => 1));
                $data_os_pesan = object_to_array($data_os_pesan);

                $x = 1;
                $sisa = 0;
                foreach ($data_os_pesan as $row_os_pesan) {
                    $last_id_permintaan_biaya_barang = $this->permintaan_biaya_barang_m->get_max_id()->result_array();
                    $last_id_permintaan_biaya_barang = intval($last_id_permintaan_biaya_barang[0]['max_id'])+1;

                    $format_id_biaya_brg   = 'PBB-'.date('m').'-'.date('Y').'-%04d';
                    $id_biaya_barang       = sprintf($format_id_biaya_brg, $last_id_permintaan_biaya_barang, 4);

                    if($x == 1 && $item['jumlah'] >= $row_os_pesan['jumlah']){

                        $sisa = $item['jumlah'] - $row_os_pesan['jumlah'];
                        $sisa_os = 0;

                        $array_os = array(
                            'status' => 5,
                        );

                        $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);

                        $data_biaya_barang = array(
                            'id'                                => $id_biaya_barang,
                            'permintaan_biaya_id'               => $permintaan_biaya_id,
                            'order_permintaan_barang_detail_id' => $row_os_pesan['pemesanan_detail_id'], 
                            'o_s_pmsn_id'                       => $row_os_pesan['id'], 
                            'item_id'                           => $row_os_pesan['item_id'], 
                            'item_satuan_id'                    => $row_os_pesan['item_satuan_id'], 
                            'jumlah'                            => $row_os_pesan['jumlah'],
                            'harga'                             => $item['harga']
                        );

                        $save_biaya_barang = $this->permintaan_biaya_barang_m->add_data($data_biaya_barang);

                    }
                    if($x == 1 && $item['jumlah'] < $row_os_pesan['jumlah']){

                        $sisa = 0;
                        $sisa_os = $row_os_pesan['jumlah'] - $item['jumlah'];

                        $array_os = array(
                            'jumlah' => $sisa_os,
                            'status' => 1,
                        );

                        $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);

                        $data_biaya_barang = array(
                            'id'                                => $id_biaya_barang,
                            'permintaan_biaya_id'               => $permintaan_biaya_id,
                            'order_permintaan_barang_detail_id' => $row_os_pesan['pemesanan_detail_id'], 
                            'o_s_pmsn_id'                       => $row_os_pesan['id'], 
                            'item_id'                           => $row_os_pesan['item_id'], 
                            'item_satuan_id'                    => $row_os_pesan['item_satuan_id'], 
                            'jumlah'                            => $item['jumlah'],
                            'harga'                             => $item['harga']
                        );

                        $save_biaya_barang = $this->permintaan_biaya_barang_m->add_data($data_biaya_barang);
                    }
                    if($x != 1 && $sisa > 0 && $sisa >= $row_os_pesan['jumlah']){
                        
                        $sisa = $sisa - $row_os_pesan['jumlah'];
                        $sisa_os = 0;

                        $array_os = array(
                            'status' => 5,
                        );

                        $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);

                        $data_biaya_barang = array(
                            'id'                                => $id_biaya_barang,
                            'permintaan_biaya_id'               => $permintaan_biaya_id,
                            'order_permintaan_barang_detail_id' => $row_os_pesan['pemesanan_detail_id'], 
                            'o_s_pmsn_id'                       => $row_os_pesan['id'], 
                            'item_id'                           => $row_os_pesan['item_id'], 
                            'item_satuan_id'                    => $row_os_pesan['item_satuan_id'], 
                            'jumlah'                            => $row_os_pesan['jumlah'],
                            'harga'                             => $item['harga']
                        );

                        $save_biaya_barang = $this->permintaan_biaya_barang_m->add_data($data_biaya_barang);
                    }

                    if($x != 1 && $sisa > 0 && $sisa < $row_os_pesan['jumlah']){

                        $sisa_os = $row_os_pesan['jumlah'] - $sisa;
                        $sisa = 0;

                        $array_os = array(
                            'jumlah' => $sisa_os,
                            'status' => 1,
                        );

                        $update_os = $this->o_s_pmsn_m->edit_data($array_os, $row_os_pesan['id']);

                        $data_biaya_barang = array(
                            'id'                                => $id_biaya_barang,
                            'permintaan_biaya_id'               => $permintaan_biaya_id,
                            'order_permintaan_barang_detail_id' => $row_os_pesan['pemesanan_detail_id'], 
                            'o_s_pmsn_id'                       => $row_os_pesan['id'], 
                            'item_id'                           => $row_os_pesan['item_id'], 
                            'item_satuan_id'                    => $row_os_pesan['item_satuan_id'], 
                            'jumlah'                            => $sisa,
                            'harga'                             => $item['harga']
                        );

                        $save_biaya_barang = $this->permintaan_biaya_barang_m->add_data($data_biaya_barang);
                    }
                    $x++;
                }
            }

            
            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Ditambahkan", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            } 
        }

        redirect('keuangan/permintaan_kasbon'); 

    }

    public function proses_verifikasi($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/proses_verif';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name').' | '. translate("Proses Kasbon", $this->session->userdata("language")), 
            'header'         => translate("Proses Kasbon", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/proses_permintaan_biaya/proses_verif',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
}

/* End of file permintaan_biaya.php */
/* Location: ./application/controllers/keuangan/permintaan_biaya.php */