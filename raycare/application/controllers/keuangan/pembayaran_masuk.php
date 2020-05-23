<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_masuk extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '770f46865245cad937939767c894b30f';                  // untuk check bit_access

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

        $this->load->model('keuangan/pembayaran_masuk/pembayaran_masuk_status_m');
        $this->load->model('keuangan/pembayaran_masuk/pembayaran_masuk_status_detail_m'); 
        $this->load->model('keuangan/pembayaran_masuk/voucher_m');  
        $this->load->model('keuangan/arus_kas_bank/rekening_koran_m');
        $this->load->model('keuangan/arus_kas_bank/keuangan_arus_kas_m'); 
        $this->load->model('keuangan/pengajuan_pemegang_saham_m');        
        $this->load->model('master/bank_m');
        $this->load->model('master/mesin_edc_m');
        $this->load->model('kasir/setoran_kasir_m');
        $this->load->model('kasir/setoran_kasir_detail_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_detail_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_detail_item_m');
        $this->load->model('reservasi/pembayaran/os_pembayaran_transaksi_m');
        $this->load->model('reservasi/pembayaran/os_pembayaran_obat_m');
        $this->load->model('reservasi/pembayaran/pembayaran_pasien_m');
        $this->load->model('reservasi/pembayaran/pembayaran_tipe_m');
        $this->load->model('reservasi/pembayaran/pembayaran_tindakan_pasien_m');
        $this->load->model('reservasi/pembayaran/pembayaran_obat_pasien_m');
        $this->load->model('reservasi/pembayaran/pembayaran_history_m');
        $this->load->model('reservasi/pembayaran/pembayaran_cetak_m');   

    }
    
    public function index()
    {
        
        $assets = array();
        $config = 'assets/keuangan/pembayaran_masuk/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Pembayaran Masuk', $this->session->userdata('language')), 
            'header'         => translate('Pembayaran Masuk', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pembayaran_masuk/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        
        $assets = array();
        $config = 'assets/keuangan/pembayaran_masuk/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Pembayaran Masuk', $this->session->userdata('language')), 
            'header'         => translate('History Pembayaran Masuk', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pembayaran_masuk/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {       

        $result = $this->pembayaran_masuk_status_m->get_datatable();
        // die_dump($result);

        // Output
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
            $i++;

            $tipe = '';
            $status = '';
            $action = '';
            $waktu_akhir = '-';

            $status_detail_awal = $this->pembayaran_masuk_status_detail_m->get_data($row['id'],0)->result_array();
            $status_detail_revisi = $this->pembayaran_masuk_status_detail_m->get_data($row['id'],1)->result_array();

            if($row['waktu_akhir'] != NULL){
                $waktu_akhir = date('d M Y, H:i', strtotime($row['waktu_akhir']));
            }
            if($row['tipe_transaksi'] == 1){
                $tipe = 'Setoran Invoice';
                if($row['status'] == 1)
                {            
                    $status = '<div class="text-left"><span class="label label-md label-warning">Menunggu Diproses</span></div>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/pembayaran_masuk/proses_setoran/'.$row['id'].'/'.$row['transaksi_id'].'"><i class="fa fa-check"></i></a>';

                }
                else if($row['status'] == 2)
                {
                    $status = '<div class="text-left"><span class="label label-md label-primary">Diterima</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/pembayaran_masuk/view_invoice/'.$row['id'].'/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                }
                else if($row['status'] == 3)
                {
                    $status = '<div class="text-left"><span class="label label-md label-danger">Menunggu Diproses</span></div>';
                    $action = '<a title="'.translate('Process', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/pembayaran_masuk/proses_setoran/'.$row['transaksi_id'].'"><i class="fa fa-check"></i></a>';

                    if($row['status_cancel'] == 1){
                        $status = '<span class="label label-danger">Batal</span>';
                        $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                    }
                }
                else if($row['status'] == 4)
                {
                    $status = '<div class="text-left"><span class="label label-md label-success">Konfirmasi Disetujui</span></div>';
                    $action = '<a title="'.translate('Process', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/pembayaran_masuk/proses_setoran/'.$row['transaksi_id'].'"><i class="fa fa-check"></i></a>';
                }
                else if($row['status'] == 5)
                {
                    $status = '<div class="text-left"><span class="label label-md label-info">Selesai</span></div>';
                }
                else if($row['status'] == 6)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';
                }
                else if($row['status'] == 7)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-danger">Konfirmasi Ditolak</span></div>';
                }
                else if($row['status'] == 10)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-info">Proses Keuangan</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                }
                else if($row['status'] == 11)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-danger">Menunggu Diproses</span></div>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/pembayaran_masuk/proses_bayar_po/'.$row['id'].'/'.$row['transaksi_id'].'"><i class="fa fa-check"></i></a>';
                    $action .= '<a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_masuk/print_voucher_po/'.$row['id'].'/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';

                }
            }if($row['tipe_transaksi'] == 2){
                $tipe = 'Penambahan Modal';

                if($row['status'] == 1){

                    $status = '<span class="label label-danger">Menunggu Diproses</span>';
                    if($row['status_revisi'] == 0){
                        $action .='<a title="'.translate('Revisi', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_revisi" href="'.base_url().'keuangan/pembayaran_masuk/proses_revisi/'.$row['transaksi_id'].'" class="btn red revisi hidden"><i class="fa fa-undo"></i></a>';
                    }

                    $action .='<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_masuk/proses_pps/'.$row['id'].'/'.$row['transaksi_id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';

                }elseif($row['status'] == 2){
                    
                    $status = '<div class="text-left"><span class="label label-md label-danger">Belum Selesai</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_masuk/view_proses_ps/'.$row['transaksi_id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
                }elseif($row['status'] == 4){
                    
                    $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_masuk/view_kasbon/'.$row['transaksi_id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
                }elseif($row['status'] == 5){
                    
                    $status = '<div class="text-left"><span class="label label-md label-default">Selesai</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_masuk/view_proses/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_masuk/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }
            }

            $output['aaData'][] = array(
                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left inline-button-table">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left inline-button-table">'.$tipe.'</div>',
                '<div class="text-left inline-button-table">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-right inline-button-table">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$waktu_akhir.'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function listing_history()
    {       

        $result = $this->pembayaran_masuk_status_m->get_datatable_history();
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

            $status_detail_awal = $this->pembayaran_masuk_status_detail_m->get_data($row['id'],0)->result_array();
            $status_detail_revisi = $this->pembayaran_masuk_status_detail_m->get_data($row['id'],1)->result_array();

            if($row['waktu_akhir'] != NULL){
                $waktu_akhir = date('d M Y, H:i', strtotime($row['waktu_akhir']));
            }
            if($row['tipe_transaksi'] == 1){
                $tipe = 'Setoran Invoice';
                if($row['status'] == 5)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_masuk/view_proses_setoran/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Print Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_masuk/print_voucher/'.$row['transaksi_id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
                }
            }if($row['tipe_transaksi'] == 2){
                $tipe = 'Penambahan Modal';
                if($row['status'] == 5){
                    
                    $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                    
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_masuk/view_proses_ps/'.$row['id'].'/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Print Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_masuk/print_voucher/'.$row['id'].'/'.$row['transaksi_id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
                }
                
            }

            $output['aaData'][] = array(
                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left inline-button-table">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left inline-button-table">'.$tipe.'</div>',
                '<div class="text-left inline-button-table">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-right inline-button-table">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$waktu_akhir.'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function listing_invoice_view($tgl,$shift=null)
    {   
        $tgl = str_replace('%20', '-', $tgl);

        $tgl = date('Y-m-d', strtotime($tgl));

        $pembayaran = $this->setoran_kasir_m->get_by(array('date(tanggal)' => $tgl, 'shift' => $shift));
        $id_bayar = 0;
        if($pembayaran){
            $id_bayar = array();
            foreach($pembayaran as $bayar){
                $id_bayar[] = $bayar->id;
            }
        }

        $result = $this->setoran_kasir_detail_m->get_datatable($id_bayar);
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

        $i=0;
        $total_debit = 0;
        $total_kradd = 0;
        $total_saldo = 0;

        foreach($records->result_array() as $row)
        {
            $i++;

            $invoice_detail = $this->invoice_detail_m->get_by(array('invoice_id' => $row['invoice_id'], 'is_active' => 1));
            $invoice_detail = object_to_array($invoice_detail);

            $total_invoice = 0;
            foreach ($invoice_detail as $inv_detail) {
                $total_invoice = $total_invoice + (($inv_detail['harga']*$inv_detail['qty']) - $inv_detail['diskon_nominal']);
            }

            $total_invoice = $total_invoice + $row['akomodasi'] - $row['diskon_nominal'] ;

            
            $output['aaData'][] = array(
                '<div class="text-left">'.substr($row['no_invoice'], 12).'<input type="hidden" name="invoice['.$i.'][pembayaran_id]" id="invoice_pembayaran_id_'.$i.'" value="'.$row['pembayaran_id'].'"><input type="hidden" name="invoice['.$i.'][invoice_id]" id="invoice_invoice_id_'.$i.'" value="'.$row['invoice_id'].'"></div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_invoice'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($total_invoice).'</div>',
            );
        }

        echo json_encode($output);

    }

    public function proses_setoran($id, $transaksi_id)
    {
        $assets = array();
        $config = 'assets/keuangan/pembayaran_masuk/proses_setoran';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $data_setoran = $this->setoran_kasir_m->get_by(array('id' => $transaksi_id), true);
        $data_setoran = object_to_array($data_setoran);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("Proses Setoran", $this->session->userdata("language")), 
            'header'              => translate("Proses Setoran", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'keuangan/pembayaran_masuk/proses_setoran',
            'form_data'        => object_to_array($data_setoran),
            'setoran_id'          => $transaksi_id,                         //table primary key value
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    

    public function get_biaya_tambahan()
    {
        if($this->input->is_ajax_request()){
            $id_po = $this->input->post('id_po');

            $response = new stdClass;
            $response->success = false;

            $data_biaya = $this->pembelian_biaya_m->get_by(array('pembelian_id' => $id_po, 'is_active' => 1));

            if(count($data_biaya) != 0){
                $response->success = true;
                $response->rows = object_to_array($data_biaya);
            }

            die(json_encode($response));    
        }
    }

    public function save()
    {
        $array_input = $this->input->post();

        $user_id = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');

        // die(dump($array_input));

        $command = $array_input['command'];

        if($command === 'proses_setoran'){
            
            $data_setoran = array(
                'status' => 2
            );

            $edit_invoice = $this->setoran_kasir_m->edit_data($data_setoran, $array_input['setoran_id']);

            $pembayaran_status_id = $this->pembayaran_masuk_status_m->get_by(array('id' => $array_input['id']), true);


            $data_pembayaran_masuk = array(
                'status' => 2,
                'status_keuangan' => 1
            );

            $edit_pembayaran_masuk = $this->pembayaran_masuk_status_m->edit_data($data_pembayaran_masuk, $array_input['id']);


            $date = date('Y-m-d', strtotime($array_input['tanggal_setor']));
            $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();

            
            $saldo_before_bank = 0;
            if(count($last_saldo_bank) != 0){
                $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
            }


            $data_arus_kas_bank = array(
                'tanggal'      => $date,
                'tipe'         => 0,
                'transaksi_id' => $array_input['setoran_id'],
                'keterangan'   => 'Setoran tindakan tanggal '.$array_input['tanggal'].' shift '.$array_input['tipe'],
                'bank_id'      => $array_input['bank_id'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'd',
                'rupiah'       => $array_input['total'],
                'saldo'        => ($saldo_before_bank + $array_input['total']),
                'status'       => 1
            );

            $keuangan_arus_kas_id = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after) {
                    $data_arus_kas_after_bank = array(
                        'saldo'        => ($after['saldo'] + $array_input['total']),
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
                'tanggal'              => date('Y-m-d H:i:s', strtotime($array_input['tanggal_setor'])),
                'bank_id'              => $array_input['bank_id'],
                'jumlah'               => $array_input['total'],
                'keterangan'           => 'Setoran tindakan tanggal '.$array_input['tanggal'].' shift '.$array_input['tipe'],
                'keuangan_arus_kas_id' => $keuangan_arus_kas_id,
                'is_active'            => 1,
                'created_by'           => $this->session->userdata('user_id'),
                'created_date'         => date('Y-m-d H:i:s')
            );

            $rekening_koran = $this->rekening_koran_m->add_data($data);

            $bank = $this->bank_m->get_by(array('id' => $array_input['bank_id']), true);

            $last_no_voucher   = $this->voucher_m->get_max_no_voucher()->result_array();
            $last_no_voucher   = intval($last_no_voucher[0]['max_no_voucher'])+1;
            
            $format_no_voucher = '%03d/'.date('m/y').'/B-'.$bank->nob.'/IN/RC-OFC';
            $no_voucher  = sprintf($format_no_voucher, $last_no_voucher, 3);

            $data_voucher = array(
                'nomor_voucher' => $no_voucher,
                'nomor_voucher_manual' => $array_input['nomor_voucher'],
                'tipe_voucher' => 2,
                'transaksi_id' => $array_input['setoran_id'],
                'transaksi_tipe' => 2,
                'is_active' => 1,
                'created_by'          => $user_id,
                'created_date'        => $date
            );

            $insert_voucher = $this->voucher_m->add_data($data_voucher);
 
        }

        if($command === 'proses_pps'){

            $data_edit_pps = array(
                'status' => 2
            );
            $edit_pps = $this->pengajuan_pemegang_saham_m->edit_data($data_edit_pps, $array_input['pps_id']);

            $data_edit_pm = array(
                'status' => 5
            );
            $edit_pm = $this->pembayaran_masuk_status_m->edit_data($data_edit_pm, $array_input['id']);


            $date = date('Y-m-d', strtotime($array_input['tanggal_terima']));
            $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $array_input['bank_id'])->result_array();

            
            $saldo_before_bank = 0;
            if(count($last_saldo_bank) != 0){
                $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
            }

            $data_arus_kas_bank = array(
                'tanggal'      => $date,
                'tipe'         => 0,
                'transaksi_id' => $array_input['pps_id'],
                'keterangan'   => 'Pengembalian dana dari No. Rek '.$array_input['nomor_rekening_pengirim'].' a/n '.$array_input['atas_nama_pengirim'],
                'bank_id'      => $array_input['bank_id'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'd',
                'rupiah'       => $array_input['nominal'],
                'saldo'        => ($saldo_before_bank + $array_input['nominal']),
                'status'       => 1
            );

            $keuangan_arus_kas_id = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after) {
                    $data_arus_kas_after_bank = array(
                        'saldo'        => ($after['saldo'] + $array_input['nominal']),
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
                'tanggal'              => date('Y-m-d H:i:s', strtotime($array_input['tanggal_terima'])),
                'bank_id'              => $array_input['bank_id'],
                'jumlah'               => $array_input['nominal'],
                'keterangan'           => 'Pengembalian dana dari No. Rek '.$array_input['nomor_rekening_pengirim'].' a/n '.$array_input['atas_nama_pengirim'],
                'keuangan_arus_kas_id' => $keuangan_arus_kas_id,
                'is_active'            => 1,
                'created_by'           => $this->session->userdata('user_id'),
                'created_date'         => date('Y-m-d H:i:s')
            );

            $rekening_koran = $this->rekening_koran_m->add_data($data);

            $bank = $this->bank_m->get_by(array('id' => $array_input['bank_id']), true);

            $last_no_voucher   = $this->voucher_m->get_max_no_voucher()->result_array();
            $last_no_voucher   = intval($last_no_voucher[0]['max_no_voucher'])+1;
            
            $format_no_voucher = '%03d/'.date('m/y').'/B-'.$bank->nob.'/IN/RC-OFC';
            $no_voucher  = sprintf($format_no_voucher, $last_no_voucher, 3);

            $data_voucher = array(
                'nomor_voucher' => $no_voucher,
                'nomor_voucher_manual' => $array_input['nomor_voucher'],
                'tipe_voucher' => 2,
                'transaksi_id' => $array_input['pps_id'],
                'transaksi_tipe' => 2,
                'is_active' => 1,
                'created_by'          => $user_id,
                'created_date'        => $date
            );

            $insert_voucher = $this->voucher_m->add_data($data_voucher);
        }


        redirect('keuangan/pembayaran_masuk');
    }

    public function view_invoice($id, $transaksi_id)
    {
        $assets = array();
        $config = 'assets/keuangan/pembayaran_masuk/proses_setoran';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $data_setoran = $this->setoran_kasir_m->get_by(array('id' => $transaksi_id), true);
        $data_setoran = object_to_array($data_setoran);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("Proses Setoran", $this->session->userdata("language")), 
            'header'              => translate("Proses Setoran", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'keuangan/pembayaran_masuk/view_invoice',
            'form_data'        => object_to_array($data_setoran),
            'setoran_id'          => $transaksi_id,                         //table primary key value
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    
    public function save_proses()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();            

            $level_id = $this->session->userdata('level_id');
            $user_id = $this->session->userdata('user_id');

            $tipe_transaksi = 2;
            if($array_input['tipe_biaya'] == 1){
                $tipe_transaksi = 2;
            }if($array_input['tipe_biaya'] == 2){
                $tipe_transaksi = 3;
            }


            $response = new stdClass;
            $response->success = false;
             
            $data = array(
                'status'          => 11
            );

            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data, $array_input['id']);

            $user_level = $this->user_level_m->get(5);
            $data_bayar = array(
                'status'        => 11,
                'user_level_id' => 5,
                'divisi'        => $user_level->divisi_id
            );
            $wheres_bayar = array(
                'transaksi_id' => $array_input['id'],
                'tipe_transaksi' => $tipe_transaksi,
            );

            $edit_pembayaran_status = $this->pembayaran_masuk_status_m->update_by($level_id, $data_bayar, $wheres_bayar);
            $pembayaran_status_id = $this->pembayaran_masuk_status_m->get_by($wheres_bayar, true);
            //data persetujuan permintaan pembayaran
            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['id'],
                'tipe_transaksi' => $tipe_transaksi,
                'user_level_id'  => 21,
                'tipe'           => 2,
                'tipe_pengajuan' => 0
            );

            $data_pembayaran_detail_id = $this->pembayaran_masuk_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 1,
                '`order`'              => $data_pembayaran_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_masuk_status_detail_m->get_by($wheres_bayar_detail_before, true);

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

            $pembayaran_status_detail = $this->pembayaran_masuk_status_detail_m->edit_data($data_pembayaran_status_detail, $data_pembayaran_detail_id->id);


            

            if ($permintaan_biaya_id) 
            {
                $response->msg = translate('Permintaan biaya berhasil diproses', $this->session->userdata('language'));
                $response->success = true;

            }

            die(json_encode($response));


        } 
    }

    public function save_proses_cair()
    {
        if($this->input->is_ajax_request()){
            $array_input = $this->input->post();
            $date = date('Y-m-d');
            $user_id = $this->session->userdata('user_id');
            $level_id = $this->session->userdata('level_id');

            $response = new stdClass;
            $response->success = false;

            $data = array(
                'status'          => 15
            );

            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data, $array_input['id']);

            $data_bayar = array(
                'status'    => 15,
            );
            $wheres_bayar = array(
                'transaksi_id' => $array_input['id'],
                'tipe_transaksi' => 2
            );

            $edit_pembayaran_status = $this->pembayaran_masuk_status_m->update_by($level_id, $data_bayar, $wheres_bayar);

            $data_bayar = array(
                'status'        => 15,
            );
            $wheres_bayar = array(
                'transaksi_id' => $array_input['id'],
                'tipe_transaksi' => 2,
            );

            $edit_pembayaran_status = $this->pembayaran_masuk_status_m->update_by($level_id, $data_bayar, $wheres_bayar);
            $pembayaran_status_id = $this->pembayaran_masuk_status_m->get_by($wheres_bayar, true);
            //data persetujuan permintaan pembayaran
            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['id'],
                'tipe_transaksi' => 2,
                'user_level_id'  => 21,
                'tipe'           => 2,
                'tipe_pengajuan' => 0
            );

            $data_pembayaran_detail_id = $this->pembayaran_masuk_status_detail_m->get_data_detail($wheres_bayar_detail, 2)->row(0);

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
                '`order`'              => $data_pembayaran_detail_id->order - 1
            );

            $pembayaran_status_detail_before = $this->pembayaran_masuk_status_detail_m->get_by($wheres_bayar_detail_before, true);

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

            $pembayaran_status_detail = $this->pembayaran_masuk_status_detail_m->edit_data($data_pembayaran_status_detail, $data_pembayaran_detail_id->id);

            $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date)->result_array();
            $after_saldo = $this->kasir_arus_kas_m->get_after_after($date)->result_array();
            
            $saldo_before = 0;
            if(count($last_saldo) != 0){
                $saldo_before = intval($last_saldo[0]['saldo']);
            }

            $data_arus_kas = array(
                'tanggal'      => $date,
                'tipe'         => 5,
                'keterangan'   => $array_input['keperluan'],
                'user_id'      => $this->session->userdata('user_id'),
                'debit_credit' => 'C',
                'rupiah'       => $array_input['nominal'],
                'saldo'        => ($saldo_before - $array_input['nominal']),
                'status'       => 1
            );

            $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

            if(count($after_saldo) != 0){
                foreach ($after_saldo as $after) {
                    $data_arus_kas_after = array(
                        'saldo'        => ($after['saldo'] - $array_input['nominal']),
                    );

                    $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                }
            }

            if ($permintaan_biaya_id) 
            {
                $response->msg = translate('Permintaan biaya berhasil dicairkan', $this->session->userdata('language'));
                $response->success = true;
            }

            die(json_encode($response));
        }
    }


    public function listing_pembelian()
    {
        $result = $this->pembelian_m->get_datatable_belum_bayar();

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
      //  die(dump($records));
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action ='<a title="'.translate('Select', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
  
            $output['data'][] = array(
                '<div class="text-center">'.$row['no_po'].'</div>',
                '<div class="text-left">'.$row['nama_sup'].' ['.$row['kode_sup'].']'.'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_pesan'])).'</div>',
                '<div class="text-right">'.formatrupiah($row['grand_total']).'</div>',
               
                '<div class="text-center inline-button-table">'.$action.'</div>'  
            );
        
            $i++;
        }

        echo json_encode($output);
    }

    public function view_proses($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/view';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id));
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
            'form_data_bon'  => object_to_array($form_data_bon)
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    

    public function print_voucher_invoice($id,$trans_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $form_data        = $this->pembayaran_masuk_status_m->get_by(array('id'=>$id),true);
        $form_data_invoice    = $this->invoice_m->get_by(array('id' => $trans_id), true);


        $data_voucher = $this->voucher_m->get_by(array('transaksi_id' => $trans_id, 'transaksi_tipe' => 5), true);

        $body = array(

            'form_data'        => object_to_array($form_data),
            'form_data_invoice'        => object_to_array($form_data_ttf),
            'data_voucher'        => object_to_array($data_voucher),

        );

        $mpdf = new mPDF('utf-8','A4', 1, '', 5, 5, 5, 2, 0, 0);
        
        $mpdf->writeHTML($this->load->view('keuangan/pembayaran_masuk/print_voucher/voucher_invoice_masuk', $body, true));

        $mpdf->Output('voucher_'.date('Y-m-d H:i:s', strtotime($form_data->tanggal_proses)).'.pdf', 'I'); 
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

    public function proses_pps($id, $transaksi_id)
    {
        $assets = array();
        $config = 'assets/keuangan/pembayaran_masuk/proses_pps';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $data_pps = $this->pengajuan_pemegang_saham_m->get_by(array('id' => $transaksi_id), true);
        $data_pps = object_to_array($data_pps);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("Proses Penambahan Modal", $this->session->userdata("language")), 
            'header'              => translate("Proses Penambahan Modal", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'keuangan/pembayaran_masuk/proses_pps',
            'form_data'           => object_to_array($data_pps),
            'pps_id'          => $transaksi_id,                         //table primary key value
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view_proses_ps($id, $transaksi_id)
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
            'title'               => config_item('site_name').' | '. translate("Proses Penambahan Modal", $this->session->userdata("language")), 
            'header'              => translate("Proses Penambahan Modal", $this->session->userdata("language")), 
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
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


}

/* End of file pembayaran_masuk.php */
/* Location: ./application/controllers/keuangan/pembayaran_masuk.php */