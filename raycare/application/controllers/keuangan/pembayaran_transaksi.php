<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_transaksi extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd95c214b630639a78e9d2f18887ba644';                  // untuk check bit_access

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

        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');
        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_m');
        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_detail_m');
        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('pembelian/pembelian_penawaran_m');
        $this->load->model('pembelian/pembelian_pembayaran_m');
        $this->load->model('pembelian/pembelian_pembayaran_detail_m');
        $this->load->model('pembelian/pembelian_kredit_m');
        $this->load->model('pembelian/pembelian_biaya_m');
        $this->load->model('pembelian/pembelian_invoice_m');
        $this->load->model('pembelian/supplier_tipe_pembayaran_m');
        $this->load->model('pembelian/supplier_harga_item_m');
        $this->load->model('pembelian/supplier_item_m');
        $this->load->model('pembelian/penerima_cabang_m');
        $this->load->model('pembelian/penerima_customer_m');
        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_pembayaran_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_m');
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_bon_m');
        $this->load->model('keuangan/proses_permintaan_biaya/persetujuan_permintaan_biaya_m');
        $this->load->model('keuangan/arus_kas_kasir/kasir_arus_kas_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/supplier/supplier_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_telepon_m');
        $this->load->model('master/supplier/supplier_email_m');
        $this->load->model('master/supplier/supplier_bank_m');
        $this->load->model('master/bank_m');
        $this->load->model('pembelian/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/biaya_m');
        $this->load->model('keuangan/arus_kas_kasir/keuangan_arus_kas_m');
        $this->load->model('others/kotak_sampah_m');   
        $this->load->model('master/user_level_persetujuan_m');       
        $this->load->model('keuangan/pembayaran_status/voucher_m');       
        $this->load->model('keuangan/perubahan_modal/o_s_hutang_m');

    }
    
    public function index()
    {
        
        $assets = array();
        $config = 'assets/keuangan/pembayaran_transaksi/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Verifikasi Dokumen Pembayaran', $this->session->userdata('language')), 
            'header'         => translate('Verifikasi Dokumen Pembayaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pembayaran_transaksi/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        
        $assets = array();
        $config = 'assets/keuangan/pembayaran_transaksi/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Pembayaran Transaksi', $this->session->userdata('language')), 
            'header'         => translate('History Pembayaran Transaksi', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/pembayaran_transaksi/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {       

        $result = $this->pembayaran_status_m->get_datatable();
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

            $status_detail_awal = $this->pembayaran_status_detail_m->get_data($row['id'],0)->result_array();
            $status_detail_revisi = $this->pembayaran_status_detail_m->get_data($row['id'],1)->result_array();

            if($row['waktu_akhir'] != NULL){
                $waktu_akhir = date('d M Y, H:i', strtotime($row['waktu_akhir']));
            }
            if($row['tipe_transaksi'] == 1){
                $tipe = 'Pembelian';
                if($row['status'] == 1)
                {            
                    $status = '<div class="text-left"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';

                }
                else if($row['status'] == 2)
                {
                    $status = '<div class="text-left"><span class="label label-md label-primary">Proses Persetujuan</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                }
                else if($row['status'] == 3)
                {
                    $status = '<div class="text-left"><span class="label label-md label-danger">Menunggu Diproses</span></div>';
                    $action = '<a title="'.translate('Process', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/pembayaran_transaksi/proses_po/'.$row['transaksi_id'].'"><i class="fa fa-check"></i></a>';

                    if($row['status_cancel'] == 1){
                        $status = '<span class="label label-danger">Batal</span>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                    }
                }
                else if($row['status'] == 4)
                {
                    $status = '<div class="text-left"><span class="label label-md label-success">Konfirmasi Disetujui</span></div>';
                    $action = '<a title="'.translate('Process', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/pembayaran_transaksi/proses_po/'.$row['transaksi_id'].'"><i class="fa fa-check"></i></a>';
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
                }else if($row['status'] == 9)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-info">Proses Verifikasi Dokumen</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                }else if($row['status'] == 10)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-info">Proses Keuangan</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                }
                else if($row['status'] == 11)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-danger">Menunggu Diproses</span></div>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/pembayaran_transaksi/proses_bayar_po/'.$row['id'].'/'.$row['transaksi_id'].'"><i class="fa fa-check"></i></a>';
                    $action .= '<a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher_po/'.$row['id'].'/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';

                }else if($row['status'] == 13)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-danger">Verifikasi Invoice</span></div>';
                    
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'pembelian/history/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                }
            }if($row['tipe_transaksi'] == 2){
                $tipe = 'Kasbon';

                if($row['status'] == 1)
                {
                    $status = '<div class="text-left"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';
                
                } elseif($row['status'] == 2){

                    $status = '<div class="text-left"><span class="label label-md label-info">Dibaca</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';

                }elseif($row['status'] == 3){

                    $status = '<span class="label label-danger">Menunggu Diproses</span>';
                    if($row['status_revisi'] == 0){
                        $action .='<a title="'.translate('Revisi', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_revisi" href="'.base_url().'keuangan/pembayaran_transaksi/proses_revisi/'.$row['transaksi_id'].'" class="btn red revisi"><i class="fa fa-undo"></i></a>';
                    }

                    $action .='<a title="'.translate('Proses', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_proses_kasbon" href="'.base_url().'keuangan/pembayaran_transaksi/proses_kasbon/'.$row['transaksi_id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';

                } elseif($row['status'] == 4){
                    
                    $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
                }elseif($row['status'] == 5){
                    
                    $status = '<div class="text-left"><span class="label label-md label-default">Diproses</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_proses/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }elseif($row['status'] == 6){
                    $status = '<div class="text-left"><span class="label label-md label-info">Proses Pengajuan</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }elseif($row['status'] == 7){
                    $status = '<div class="text-left"><span class="label label-md label-info">Proses Persetujuan Direktur</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }elseif($row['status'] == 8){
                    $status = '<div class="text-left"><span class="label label-md label-success">Proses Pencairan</span></div>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }if($row['status'] == 11){
                    $status = '<span class="label label-warning">Menunggu Persetujuan Keuangan</span>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }if($row['status'] == 12){
                    $status = '<span class="label label-warning">Proses Persetujuan Keuangan</span>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }if($row['status'] == 13){
                    $status = '<span class="label label-success">Disetujui Keuangan</span>';
                    
                    $action .='<a title="'.translate('Proses Pencairan', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/proses_pencairan_besar/'.$row['transaksi_id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';    
                    
                    $action .= '<a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }
                if($row['status'] == 14){
                    $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak Keuangan</span></div>';
                    $action = '<a title="'.translate('Revisi', $this->session->userdata('language')).'" data-toggle="modal" data-target="#modal_revisi" href="'.base_url().'keuangan/pembayaran_transaksi/proses_revisi/'.$row['transaksi_id'].'" class="btn red revisi"><i class="fa fa-undo"></i></a><a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_reimburse/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';
                
                }
                if($row['status'] == 15){
                    $status = '<span class="label label-warning">Menunggu Verif Bon</span>';
                    $action .= '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/proses_verifikasi/'.$row['transaksi_id'].'"  class="btn btn-success"><i class="fa fa-cogs"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                    
                }if($row['status'] == 16){
                    $status = '<span class="label label-warning">Menunggu Verifikasi Keuangan</span>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                    
                }if($row['status'] == 18 || $row['status'] == 19){
                    $status = '<span class="label label-info">Diverifikasi Keuangan</span>';
                    $action .= '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_proses/'.$row['transaksi_id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a><a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn purple"><i class="fa fa-file"></i></a>';
                }
                if($row['status'] == 21){
                    $status = '<span class="label label-warning">Proses Revisi</span>';
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_kasbon/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>';
                }
            }

            $output['aaData'][] = array(
                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'" data-posisi="'.$row['user_level_id'].'"></span>',
                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left inline-button-table">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left inline-button-table">'.$tipe.'</div>',
                '<div class="text-left inline-button-table">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-right inline-button-table">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_level_proses'].'</div>',
                '<div class="text-center inline-button-table">'.$waktu_akhir.'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function listing_history()
    {       

        $result = $this->pembayaran_status_m->get_datatable_history();
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
                    $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                    $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/pembayaran_transaksi/view_proses_bayar_po/'.$row['id'].'/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
                    $action .= '<a title="'.translate('View Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher_po/'.$row['id'].'/'.$row['transaksi_id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
                }
            }if($row['tipe_transaksi'] == 2){
                $permintaan_biaya = $this->permintaan_biaya_m->get_by(array('id' => $row['transaksi_id']), true);
                $tipe = 'Kasbon';
                if($row['status'] == 5){
                    
                    $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                    if($permintaan_biaya->status_proses == 2 || $permintaan_biaya->status_proses == 4){
                        $status = '<div class="text-center"><span class="label label-md label-success">Selesai</span></div>';
                    }
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_proses/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Print Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
                }
                
            }if($row['tipe_transaksi'] == 3){
                $permintaan_biaya = $this->permintaan_biaya_m->get_by(array('id' => $row['transaksi_id']), true);
                $tipe = 'Reimburse';
                if($row['status'] == 5){
                    
                    $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                    if($permintaan_biaya->status_proses == 2 || $permintaan_biaya->status_proses == 4){
                        $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                    }
                    $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/pembayaran_transaksi/view_proses/'.$row['transaksi_id'].'"  class="btn grey-cascade"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Print Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher/'.$row['transaksi_id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
;
                }
            }if($row['tipe_transaksi'] == 4){
                $tipe = 'Tanda Terima Faktur';
                if($row['status'] == 3)
                {                    
                    $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                    $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/tanda_terima_faktur/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>
                    <a title="'.translate('Print Voucher', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'keuangan/pembayaran_transaksi/print_voucher_ttf/'.$row['id'].'/'.$row['transaksi_id'].'" class="btn btn-primary"><i class="fa fa-print"></i></a>';
                }
            }

            $output['aaData'][] = array(
                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'"></span>',
                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left inline-button-table">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left inline-button-table">'.$tipe.'</div>',
                '<div class="text-left inline-button-table">'.$row['transaksi_nomor'].'</div>',
                '<div class="text-right inline-button-table">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_level_proses'].'</div>',
                '<div class="text-center inline-button-table">'.$waktu_akhir.'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function proses_po($id)
    {
        $assets = array();
        $config = 'assets/keuangan/pembayaran_transaksi/proses_po';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_data = $this->pembelian_m->get_data($id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();

        $pembayaran_status = $this->pembayaran_status_m->get_by(array('transaksi_id' => $id, 'tipe_transaksi' => 1), true);
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id);

        $data = array(
            'title'               => config_item('site_name').' | '. translate("Proses Pembelian", $this->session->userdata("language")), 
            'header'              => translate("Proses Pembelian", $this->session->userdata("language")), 
            'header_info'         => config_item('site_name'), 
            'breadcrumb'          => TRUE,
            'menus'               => $this->menus,
            'menu_tree'           => $this->menu_tree,
            'css_files'           => $assets['css'],
            'js_files'            => $assets['js'],
            'content_view'        => 'keuangan/pembayaran_transaksi/proses_po',
            'form_data'           => object_to_array($form_data),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'tipe_bayar'          => $tipe_bayar,
            'pembayaran_status'          => object_to_array($pembayaran_status),
            'form_data_detail'    => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'pk_value'            => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function proses_bayar_po($id,$id_po)
    {
        $assets = array();
        $config = 'assets/keuangan/pembayaran_transaksi/proses_bayar_po';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_data = $this->pembelian_m->get_data($id_po)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id_po);
        $data_invoice = $this->pembelian_invoice_m->get_by(array('pembelian_id' =>$id_po));
        $data_biaya = $this->pembelian_biaya_m->get_data($id_po)->result_array();
        $data_bayar = $this->pembayaran_status_m->get_by(array('transaksi_id' => $id_po, 'tipe_transaksi' => 1), true);
        $data_bayar_detail = $this->pembayaran_status_detail_m->get_by(array('pembayaran_status_id' => $data_bayar->id, 'user_level_id' => 5, 'tipe' => 2), true);
        $data_bayar_po = $this->pembelian_pembayaran_m->get_by(array('pembayaran_status_id' => $id, 'pembelian_id' => $id_po), true);
        $data_bayar_detail_po = $this->pembelian_pembayaran_detail_m->get_data_invoice($data_bayar_po->id)->result_array();
        $data_bayar_detail_tipe_po = $this->pembelian_pembayaran_detail_m->get_data_invoice_tipe($data_bayar_po->id)->result_array();


        $data = array(
            'title'                     => config_item('site_name').' | '. translate("Proses Pembelian", $this->session->userdata("language")), 
            'header'                    => translate("Proses Pembelian", $this->session->userdata("language")), 
            'header_info'               => config_item('site_name'), 
            'breadcrumb'                => TRUE,
            'menus'                     => $this->menus,
            'menu_tree'                 => $this->menu_tree,
            'css_files'                 => $assets['css'],
            'js_files'                  => $assets['js'],
            'content_view'              => 'keuangan/pembayaran_transaksi/proses_bayar_po',
            'form_data'                 => object_to_array($form_data),
            'data_biaya'                => object_to_array($data_biaya),
            'data_invoice'              => object_to_array($data_invoice),
            'data_bayar'                => object_to_array($data_bayar),
            'data_bayar_detail'         => object_to_array($data_bayar_detail),
            'supplier_tipe_bayar'       => $supplier_tipe_bayar,
            'tipe_bayar'                => $tipe_bayar,
            'form_data_detail'          => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'data_bayar_po'             => (count($data_bayar_po) != 0)?object_to_array($data_bayar_po):'',
            'data_bayar_detail_po'      => (count($data_bayar_detail_po) != 0)?object_to_array($data_bayar_detail_po):'',
            'data_bayar_detail_tipe_po' => (count($data_bayar_detail_tipe_po) != 0)?object_to_array($data_bayar_detail_tipe_po):'',
            'pk_value'                  => $id_po,                         //table primary key value
            'pembayaran_status_id'      => $id,                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function proses_bayar_po_ttf($id,$id_ttf)
    {
        $assets = array();
        $config = 'assets/keuangan/pembayaran_transaksi/proses_bayar_po';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_ttf = $this->tanda_terima_faktur_m->get_by(array('id' => $id_ttf), true);
        $id_po = $form_ttf->pembelian_id;
        $form_data = $this->pembelian_m->get_data($id_po)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id_po);
        $data_invoice = $this->pembelian_invoice_m->get_by(array('pembelian_id' =>$id_po));
        $data_biaya = $this->pembelian_biaya_m->get_data($id_po)->result_array();
        $data_bayar = $this->pembayaran_status_m->get_by(array('transaksi_id' => $id_ttf, 'tipe_transaksi' => 4), true);
        $data_bayar_detail = $this->pembayaran_status_detail_m->get_by(array('pembayaran_status_id' => $data_bayar->id, 'user_level_id' => 5, 'tipe' => 2), true);
        $data_bayar_po = $this->pembelian_pembayaran_m->get_by(array('pembayaran_status_id' => $id, 'pembelian_id' => $id_po), true);
        $data_bayar_detail_po = $this->pembelian_pembayaran_detail_m->get_data_invoice($data_bayar_po->id)->result_array();
        $data_bayar_detail_tipe_po = $this->pembelian_pembayaran_detail_m->get_data_invoice_tipe($data_bayar_po->id)->result_array();


        $data = array(
            'title'                     => config_item('site_name').' | '. translate("Proses Pembelian", $this->session->userdata("language")), 
            'header'                    => translate("Proses Pembelian", $this->session->userdata("language")), 
            'header_info'               => config_item('site_name'), 
            'breadcrumb'                => TRUE,
            'menus'                     => $this->menus,
            'menu_tree'                 => $this->menu_tree,
            'css_files'                 => $assets['css'],
            'js_files'                  => $assets['js'],
            'content_view'              => 'keuangan/pembayaran_transaksi/proses_bayar_po_ttf',
            'form_ttf'                 => object_to_array($form_ttf),
            'form_data'                 => object_to_array($form_data),
            'data_biaya'                => object_to_array($data_biaya),
            'data_invoice'              => object_to_array($data_invoice),
            'data_bayar'                => object_to_array($data_bayar),
            'data_bayar_detail'         => object_to_array($data_bayar_detail),
            'supplier_tipe_bayar'       => $supplier_tipe_bayar,
            'tipe_bayar'                => $tipe_bayar,
            'form_data_detail'          => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'data_bayar_po'             => (count($data_bayar_po) != 0)?object_to_array($data_bayar_po):'',
            'data_bayar_detail_po'      => (count($data_bayar_detail_po) != 0)?object_to_array($data_bayar_detail_po):'',
            'data_bayar_detail_tipe_po' => (count($data_bayar_detail_tipe_po) != 0)?object_to_array($data_bayar_detail_tipe_po):'',
            'pk_value'                  => $id_po,                         //table primary key value
            'id_ttf'                  => $id_ttf,                         //table primary key value
            'pembayaran_status_id'      => $id,                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function view_proses_bayar_po($id,$id_po)
    {
        $assets = array();
        $config = 'assets/keuangan/pembayaran_transaksi/proses_bayar_po';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        $form_data = $this->pembelian_m->get_data($id_po)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data[0]['tipe_pembayaran'])->result_array();
        $form_data_detail = $this->pembelian_detail_m->get_data_detail($id_po);
        $data_invoice = $this->pembelian_invoice_m->get_by(array('pembelian_id' =>$id_po));
        $data_biaya = $this->pembelian_biaya_m->get_data($id_po)->result_array();
        $data_bayar = $this->pembayaran_status_m->get_by(array('transaksi_id' => $id_po, 'tipe_transaksi' => 1), true);
        $data_bayar_detail = $this->pembayaran_status_detail_m->get_by(array('pembayaran_status_id' => $data_bayar->id, 'user_level_id' => 5, 'tipe' => 2), true);
        $data_bayar_po = $this->pembelian_pembayaran_m->get_by(array('pembayaran_status_id' => $id, 'pembelian_id' => $id_po), true);
        $data_bayar_detail_po = $this->pembelian_pembayaran_detail_m->get_data_invoice($data_bayar_po->id)->result_array();
        // die(dump($data_bayar_detail_po));
        $data_bayar_detail_tipe_po = $this->pembelian_pembayaran_detail_m->get_data_invoice_tipe($data_bayar_po->id)->result_array();


        $data = array(
            'title'                     => config_item('site_name').' | '. translate("Proses Pembelian", $this->session->userdata("language")), 
            'header'                    => translate("Proses Pembelian", $this->session->userdata("language")), 
            'header_info'               => config_item('site_name'), 
            'breadcrumb'                => TRUE,
            'menus'                     => $this->menus,
            'menu_tree'                 => $this->menu_tree,
            'css_files'                 => $assets['css'],
            'js_files'                  => $assets['js'],
            'content_view'              => 'keuangan/pembayaran_transaksi/view_proses_bayar_po',
            'form_data'                 => object_to_array($form_data),
            'data_biaya'                => object_to_array($data_biaya),
            'data_invoice'              => object_to_array($data_invoice),
            'data_bayar'                => object_to_array($data_bayar),
            'data_bayar_detail'         => object_to_array($data_bayar_detail),
            'supplier_tipe_bayar'       => $supplier_tipe_bayar,
            'tipe_bayar'                => $tipe_bayar,
            'form_data_detail'          => (count($form_data_detail) != 0)?object_to_array($form_data_detail):'',
            'data_bayar_po'             => (count($data_bayar_po) != 0)?object_to_array($data_bayar_po):'',
            'data_bayar_detail_po'      => (count($data_bayar_detail_po) != 0)?object_to_array($data_bayar_detail_po):'',
            'data_bayar_detail_tipe_po' => (count($data_bayar_detail_tipe_po) != 0)?object_to_array($data_bayar_detail_tipe_po):'',
            'pk_value'                  => $id_po,                         //table primary key value
            'pembayaran_status_id'      => $id,                         //table primary key value
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
        // die(dump($array_input));

        $user_id = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');

        $command = $array_input['command'];
        $beliItem = $array_input['items'];

        if($command === 'proses_po'){
            $pembelian_id = $array_input['pembelian_id'];

            $data = array(
                'status_keuangan' => 3,
                'biaya_tambahan'  => $array_input['biaya_tambah_hidden'],
                'grand_total_po'  => $array_input['grand_tot_hidden'],
                'grand_total'     => $array_input['grand_tot_biaya_hidden'],
                'modified_by'     => $user_id,
                'modifed_date'    => date('Y-m-d H:i:s')
            );

            $pembelian_edit = $this->pembelian_m->edit_data($data, $pembelian_id);

            if($array_input['depe'] != 0){
                $nominal = ($array_input['depe']/100) * $array_input['grand_tot_hidden'];
            }
            else{
                $nominal = $array_input['grand_tot_hidden'];
            }

            $user_keuangan = $this->user_level_m->get(5);
            $data_bayar = array(
                'status_keuangan' => 3,
                'status'          => 9,
                'user_level_id'   => 5,
                'divisi'          => $user_keuangan->divisi_id,
                'nominal'         => $nominal + $array_input['biaya_tambah_hidden']
            );

            $wheres_bayar = array(
                'transaksi_id'   => $pembelian_id,
                'tipe_transaksi' => 1
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
                'keterangan'     => $array_input['keterangan'],
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

            $delete_pembelian_invoice = $this->pembelian_invoice_m->delete_by(array('pembelian_id' => $pembelian_id));

            if($array_input['bon']){
                foreach ($array_input['bon'] as $key => $bon) {
                    $last_id_invoice = $this->pembelian_invoice_m->get_max_id_invoice_pembelian()->result_array();
                    $last_id_invoice = intval($last_id_invoice[0]['max_id'])+1;

                    $format_id_invoice   = 'POI-'.date('m').'-'.date('Y').'-%04d';
                    $id_po_invoice       = sprintf($format_id_invoice, $last_id_invoice, 4);

                    if($bon['url'] != ''){


                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$pembayaran_status_id->id;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $bon['url'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $bon['url'];
                        $real_file = $pembayaran_status_id->id.'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bayar').$real_file);

                    }

                    if($bon['url_pajak'] != '')
                    {
                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$pembayaran_status_id->id;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $bon['url_pajak'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $bon['url_pajak'];
                        $real_file = $pembayaran_status_id->id.'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bayar').$real_file);

                    }

                    $data_bon = array(
                        'id'            => $id_po_invoice,
                        'pembelian_id'  => $pembelian_id,
                        'no_invoice'    => $bon['no_bon'],
                        'total_invoice' => $bon['total_bon'],
                        'tgl_invoice'   => date('Y-m-d', strtotime($bon['tanggal'])),
                        'keterangan'    => $bon['keterangan'],
                        'url'           => $bon['url'],
                        'url_faktur_pajak' => $bon['url_pajak'],
                        'is_active'     => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'  => date('Y-m-d H:i:s'),
                    );

                    $pembelian_invoice = $this->pembelian_invoice_m->add_data($data_bon);
                }
            }

            if($array_input['biaya']){
                foreach ($array_input['biaya'] as $key => $biaya) {
                    if($biaya['id'] == '' && $biaya['biaya_id'] == '' && $biaya['nominal'] == '' ){

                        $last_id_biaya = $this->pembelian_biaya_m->get_max_id_biaya_pembelian()->result_array();
                        $last_id_biaya = intval($last_id_biaya[0]['max_id'])+1;

                        $format_id_biaya   = 'POB-'.date('m').'-'.date('Y').'-%04d';
                        $id_po_biaya       = sprintf($format_id_biaya, $last_id_biaya, 4);

                        $data_biaya = array(
                            'id'           => $id_po_biaya,
                            'pembelian_id' => $pembelian_id,
                            'biaya_id'     => $biaya['biaya_id'],
                            'nominal'      => $biaya['nominal'],
                            'is_active'    => 1,
                            'created_by'   => $this->session->userdata('user_id'),
                            'created_date' => date('Y-m-d H:i:s')
                        );

                        $pembelian_biaya = $this->pembelian_biaya_m->add_data($data_biaya); 
                    }if($biaya['id'] != '' && $biaya['is_active'] == 1){

                        $data_biaya = array(
                            'biaya_id'     => $biaya['biaya_id'],
                            'nominal'      => $biaya['nominal'],
                            'is_active'    => 1,
                            'modified_by'  => $this->session->userdata('user_id'),
                            'modifed_date' => date('Y-m-d H:i:s')
                        );

                        $pembelian_biaya = $this->pembelian_biaya_m->edit_data($data_biaya, $biaya['id']); 
                    }if($biaya['id'] != '' && $biaya['is_active'] == 0){

                        $wheres_biaya = array(
                            'id'    => $biaya['id']
                        );

                        $pembelian_biaya = $this->pembelian_biaya_m->delete_by($wheres_biaya); 
                    }
                }
            }


            $jml_item = $array_input['jml_baris'];
            foreach ($beliItem as $item) 
            {
                if($item['item_id'] != "" && $item['item_satuan'] != ""  )
                {
                    $get_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $item['item_id'], 'is_primary' => 1));
                    $satuan_primary = object_to_array($get_satuan_primary);
                    // die_dump($get_satuan_primary);
                    $nilai_konversi         = $this->item_m->get_nilai_konversi($item['item_satuan']);
                    
                    $harga_satuan = $item['item_total']/(intval($item['jumlah_setujui']*$nilai_konversi));

                    $diskon_tambahan = 0;
                    if($array_input['disk_hidden'] != 0){
                        $diskon_tambahan = (intval($array_input['disk_hidden'])/$jml_item) / (intval($item['jumlah_setujui']*$nilai_konversi));
                    }

                    $biaya_tambahan = 0;
                    if($array_input['biaya_tambah_hidden'] != 0){
                        $biaya_tambahan = (intval($array_input['biaya_tambah_hidden'])/$jml_item) / (intval($item['jumlah_setujui']*$nilai_konversi));
                    }

                    $tad_tambahan = $harga_satuan - $diskon_tambahan;

                    $ppn_tambahan = 0;
                    if($array_input['ppn_hidden'] != 0){
                        $ppn_tambahan = (intval($array_input['ppn_hidden'])/100) * $tad_tambahan;
                    }

                    $tax_tambahan = $tad_tambahan + $ppn_tambahan;

                    $pembulatan = 0;

                    $harga_satuan = ($tax_tambahan - $pembulatan) + $biaya_tambahan;
                    $data_item = array(
                        'jumlah_belum_diterima'       => intval($item['jumlah_setujui']*$nilai_konversi),
                        'diskon'                      => $item['item_diskon'],
                        'diskon_tambahan_primary'     => $diskon_tambahan,
                        'ppn_tambahan_primary'        => $ppn_tambahan,
                        'pembulatan_tambahan_primary' => $pembulatan,
                        'harga_beli_primary'          => $harga_satuan,
                        'harga_beli'                  => $item['item_harga']

                    );
                    
                    $pembelian_detail = $this->pembelian_detail_m->edit_data($data_item, $item['id']); 
                    // die_dump($pembelian_detail);
                }
            }

        }

        if($command === 'proses_bayar_po'){
            // die(dump($array_input));
            $pembelian_id = $array_input['pembelian_id'];

            $data = array(
                'status_keuangan' => 5,
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembelian_edit = $this->pembelian_m->edit_data($data, $pembelian_id);

            // $user_kasir = $this->user_level_m->get($this->session->userdata('level_id'));
            $user_keuangan = $this->user_level_m->get(5);
            $data_bayar = array(
                'status_keuangan' => 5,
                'status'          => 13, //selesai
                'user_level_id'   => 5,
                'divisi'          => $user_keuangan->divisi_id,
            );

            $wheres_bayar = array(
                'transaksi_id'   => $pembelian_id,
                'tipe_transaksi' => 1
            );
            $pembayaran_status = $this->pembayaran_status_m->update_by($user_id,$data_bayar,$wheres_bayar);

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_bayar, true);

            $wheres_bayar_detail = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
                'user_level_id'        => $level_id
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 2)->row(0);
            // die(dump($this->db->last_query()));

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
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
                'keterangan'     => $array_input['keterangan'],
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

            // $where_delete = array(
            //     'transaksi_id'   => $pembelian_id,
            //     'tipe_transaksi' => 1
            // );

            // $delete_hutang = $this->o_s_hutang_m->delete_by($where_delete);
            $delete_pembelian_invoice = $this->pembelian_invoice_m->delete_by(array('pembelian_id' => $pembelian_id));

            $total_bayar = 0;
            $total_biaya = 0;
            if($array_input['bon']){
                foreach ($array_input['bon'] as $invoice) {

                    $last_id_invoice = $this->pembelian_invoice_m->get_max_id_invoice_pembelian()->result_array();
                    $last_id_invoice = intval($last_id_invoice[0]['max_id'])+1;

                    $format_id_invoice   = 'POI-'.date('m').'-'.date('Y').'-%04d';
                    $id_po_invoice       = sprintf($format_id_invoice, $last_id_invoice, 4);
                    
                    $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/proses_pembayaran_transaksi/images/'.$invoice['id'];
                    if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                    $temp_filename = $invoice['url'];

                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = $invoice['url'];
                    $real_file = $invoice['id'].'/'.$new_filename;

                    copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_proses_bayar').$real_file);

                    if($invoice['url_pajak'] != '')
                    {
                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/proses_pembayaran_transaksi/images/'.$invoice['id'];
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $invoice['url_pajak'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $invoice['url_pajak'];
                        $real_file = $invoice['id'].'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_proses_bayar').$real_file);

                    }

                    $data_bon = array(
                        'url_bukti_bayar' => $invoice['url'],
                        'modified_by'     => $this->session->userdata('user_id'),
                        'modifed_date'    => date('Y-m-d H:i:s'),
                    );

                    $pembelian_invoice = $this->pembelian_pembayaran_detail_m->edit_data($data_bon, $invoice['id']);


                    if($invoice['url'] != ''){

                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$pembayaran_status_id->id;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $invoice['url'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $invoice['url'];
                        $real_file = $pembayaran_status_id->id.'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bayar').$real_file);

                    }

                    if($invoice['url_pajak'] != '')
                    {
                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$pembayaran_status_id->id;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $invoice['url_pajak'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $invoice['url_pajak'];
                        $real_file = $pembayaran_status_id->id.'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bayar').$real_file);

                    }

                     $data_bon = array(
                        'id'            => $id_po_invoice,
                        'pembelian_id'  => $pembelian_id,
                        'no_invoice'    => $invoice['no_bon'],
                        'total_invoice' => $invoice['total_bon'],
                        'tgl_invoice'   => date('Y-m-d', strtotime($invoice['tanggal'])),
                        'keterangan'    => $invoice['keterangan'],
                        'url'           => $invoice['url'],
                        'url_faktur_pajak' => $invoice['url_pajak'],
                        'is_active'     => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'  => date('Y-m-d H:i:s'),
                    );

                    $pembelian_invoice = $this->pembelian_invoice_m->add_data($data_bon);

                    $total_bayar = $total_bayar + $invoice['total_invoice'];
                    $total_biaya = $total_biaya + $invoice['jml_biaya'];

                    
                }
            }
        }

        if($command === 'proses_bayar_po_ttf'){
            $pembelian_id = $array_input['pembelian_id'];
            $ttf_id = $array_input['ttf_id'];

            $data = array(
                'status_keuangan' => 5,
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembelian_edit = $this->pembelian_m->edit_data($data, $pembelian_id);


            $last_number = $this->voucher_m->get_max_nomor()->result_array();
            $last_number   = intval($last_number[0]['max_no'])+1;
                
            $format        = '#VCH#%03d/RI/'.romanic_number(date('m'), true).'/'.date('Y');
            $no_voucher     = sprintf($format, $last_number, 3);


            $data_voucher = array(
                'nomor_voucher' => $no_voucher,
                'transaksi_id' => $ttf_id,
                'transaksi_tipe' => 4,
                'is_active' => 1
            );

            $voucher_id = $this->voucher_m->save($data_voucher);

            $user_kasir = $this->user_level_m->get(9);
            $data_bayar = array(
                'status_keuangan' => 5,
                'status'          => 3, //selesai
                'user_level_id'   => $this->session->userdata('level_id'),
                'divisi'          => $user_kasir->divisi_id,
            );

            $wheres_bayar = array(
                'transaksi_id'   => $ttf_id,
                'tipe_transaksi' => 4
            );
            $pembayaran_status = $this->pembayaran_status_m->update_by($user_id,$data_bayar,$wheres_bayar);

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_bayar, true);

            $wheres_bayar_detail = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
                'user_level_id'        => $level_id
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 2)->row(0);
            // die(dump($this->db->last_query()));

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
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
                'keterangan'     => $array_input['keterangan'],
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);


            $where_delete_ttf = array(
                'transaksi_id' => $ttf_id,
                'tipe_transaksi' => 2,
            );

            $delete_hutang = $this->o_s_hutang_m->delete_by($where_delete_ttf);

            $total_bayar = 0;
            $total_biaya = 0;
            if($array_input['invoice']){
                foreach ($array_input['invoice'] as $invoice) {
                    $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/proses_pembayaran_transaksi/images/'.$invoice['id'];
                    if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                    $temp_filename = $invoice['url'];

                    $convtofile = new SplFileInfo($temp_filename);
                    $extenstion = ".".$convtofile->getExtension();

                    $new_filename = $invoice['url'];
                    $real_file = $invoice['id'].'/'.$new_filename;

                    copy(config_item('base_dir').config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_proses_bayar').$real_file);

                    $data_bon = array(
                        'url_bukti_bayar' => $invoice['url'],
                        'modified_by'     => $this->session->userdata('user_id'),
                        'modifed_date'    => date('Y-m-d H:i:s'),
                    );

                    $pembelian_invoice = $this->pembelian_pembayaran_detail_m->edit_data($data_bon, $invoice['id']);

                    $total_bayar = $total_bayar + $invoice['total_invoice'];
                    $total_biaya = $total_biaya + $invoice['jml_biaya'];

                    $date = date('Y-m-d');
                    $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $invoice['bank_id'])->result_array();
                    $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $invoice['bank_id'])->result_array();

                    
                    $saldo_before_bank = 0;
                    if(count($last_saldo_bank) != 0){
                        $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
                    }

                    $data_arus_kas_bank = array(
                        'tanggal'      => $date,
                        'tipe'         => 5,
                        'keterangan'   => 'Pembayaran Invoice No. '.$invoice['no_invoice'],
                        'bank_id'      => $invoice['bank_id'],
                        'user_id'      => $this->session->userdata('user_id'),
                        'debit_credit' => 'C',
                        'rupiah'       => $invoice['total_invoice'],
                        'saldo'        => ($saldo_before_bank - $invoice['total_invoice']),
                        'status'       => 1
                    );

                    $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

                    if(count($after_saldo_bank) != 0){
                        foreach ($after_saldo_bank as $after) {
                            $data_arus_kas_after_bank = array(
                                'saldo'        => ($after['saldo'] - $invoice['total_invoice']),
                            );

                            $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                        }
                    }

                    if($invoice['jml_biaya'] != ''){
                        $date = date('Y-m-d');
                        $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $invoice['bank_id'])->result_array();
                        $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $invoice['bank_id'])->result_array();

                        
                        $saldo_before_bank = 0;
                        if(count($last_saldo_bank) != 0){
                            $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
                        }

                        $data_arus_kas_bank = array(
                            'tanggal'      => $date,
                            'tipe'         => 5,
                            'keterangan'   => 'Pembayaran Biaya Tambahan Invoice No. '.$invoice['no_invoice'],
                            'bank_id'      => $invoice['bank_id'],
                            'user_id'      => $this->session->userdata('user_id'),
                            'debit_credit' => 'C',
                            'rupiah'       => $invoice['jml_biaya'],
                            'saldo'        => ($saldo_before_bank - $invoice['jml_biaya']),
                            'status'       => 1
                        );

                        $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

                        if(count($after_saldo_bank) != 0){
                            foreach ($after_saldo_bank as $after) {
                                $data_arus_kas_after_bank = array(
                                    'saldo'        => ($after['saldo'] - $invoice['jml_biaya']),
                                );

                                $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                            }
                        }
                    }
                }
            }
        }
        if($command === 'proses_reimburse')
        {            
            $id = $array_input['id'];
            $level_id = $this->session->userdata('level_id');

            $date = date('Y-m-d');
            $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date)->result_array();

            $status = 1;

            $cek_user_level_persetujuan   = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $level_id, 'tipe_persetujuan' => 10));
            $user_level_persetujuan_array = object_to_array($cek_user_level_persetujuan);

            if(count($user_level_persetujuan_array) == 0){
                $data = array(
                    'status'          => 16,
                    'tanggal_proses'  => $date,
                    'diproses_oleh'   => $this->session->userdata('user_id')
                );
                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

                $status = 5;

            }elseif(count($user_level_persetujuan_array) != 0){
                if($array_input['tipe'] == 1){
                    $data = array(
                        'status'          => 16,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id')
                    );

                    $status = 16;

                }if($array_input['tipe'] == 2 && $array_input['nominal'] < config_item('limit_cash')){
                    $data = array(
                        'status'          => 16,
                        'tanggal_proses'  => $date,
                        'diproses_oleh'   => $this->session->userdata('user_id')
                    );
                    $status = 16;
                    
                }if($array_input['tipe'] == 2 && $array_input['nominal'] >= config_item('limit_cash')){
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

                if($array_input['tipe'] == 1){
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
                            'permintaan_biaya_id'             => $id,
                            'user_level_id'                   => $persetujuan['user_level_menyetujui_id'],
                            'tipe'                            => 3,
                            '`order`'                         => $persetujuan['level_order'],
                            '`status`'                        => 1,
                            'is_active'                       => 1,
                            'created_by'                      => $this->session->userdata('user_id'),
                            'created_date'                    => date('Y-m-d H:i:s'),

                        );

                        $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);
                    }
                }if($array_input['tipe'] == 2 && $array_input['nominal'] < config_item('limit_cash')){
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
                            'permintaan_biaya_id'             => $id,
                            'user_level_id'                   => $persetujuan['user_level_menyetujui_id'],
                            'tipe'                            => 3,
                            '`order`'                         => $persetujuan['level_order'],
                            '`status`'                        => 1,
                            'is_active'                       => 1,
                            'created_by'                      => $this->session->userdata('user_id'),
                            'created_date'                    => date('Y-m-d H:i:s'),

                        );

                        $persetujuan_permintaan_biaya_id = $this->persetujuan_permintaan_biaya_m->save($data_persetujuan_permintaan_biaya);
                    }
                }

            }
            
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


            if(count($user_level_persetujuan_array) == 0){
                $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date)->result_array();
                $after_saldo = $this->kasir_arus_kas_m->get_after_after($date)->result_array();
                
                $saldo_before = 0;
                if(count($last_saldo) != 0){
                    $saldo_before = intval($last_saldo[0]['saldo']);
                }

                $data_arus_kas = array(
                    'tanggal'      => $date,
                    'tipe'         => 5,
                    'keterangan'   => rtrim($keterangan,';'),
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
        if($command === 'proses_verif')
        {      
            $date = date('Y-m-d');
            $id = $array_input['id'];
            $level_id = $this->session->userdata('level_id');

            if($array_input['nominal_bon'] > $array_input['nominal']){
                $data = array(
                    'status'          => 16,
                    'tanggal_proses'  => $date,
                    'diproses_oleh'   => $this->session->userdata('user_id'),
                    'po_id'           => $array_input['id_po'],
                    'sisa'            => $array_input['nominal_bon'] - $array_input['nominal']
                );
                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

                $data_bayar = array(
                    'status'    => 16,
                );
                $wheres_bayar = array(
                    'transaksi_id' => $id,
                    'tipe_transaksi' => 2
                );

                $edit_pembayaran_status = $this->pembayaran_status_m->update_by($level_id, $data_bayar, $wheres_bayar);

                
            }elseif($array_input['nominal_bon'] <= $array_input['nominal']){
                $sisa = $array_input['nominal'] - $array_input['nominal_bon'];

                $data = array(
                    'status'          => 5,
                    'tanggal_proses'  => $date,
                    'diproses_oleh'   => $this->session->userdata('user_id'),
                    'po_id'           => $array_input['id_po'],
                    'sisa'            => $array_input['nominal_bon'] - $array_input['nominal']
                );
                $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

                $data_bayar = array(
                    'status'    => 5,
                );

                $wheres_bayar = array(
                    'transaksi_id' => $id,
                    'tipe_transaksi' => 2
                );

                $edit_pembayaran_status = $this->pembayaran_status_m->update_by($level_id, $data_bayar, $wheres_bayar);

                $last_saldo_tambah = $this->kasir_arus_kas_m->get_saldo_before($date)->result_array();
                $after_saldo_tambah = $this->kasir_arus_kas_m->get_after_after($date)->result_array();
                
                $saldo_before_tambah = 0;
                if(count($last_saldo_tambah) != 0){
                    $saldo_before_tambah = intval($last_saldo_tambah[0]['saldo']);
                }

                $data_arus_kas_tambah = array(
                    'tanggal'      => $date,
                    'tipe'         => 6,
                    'keterangan'   => 'Tambahan Kas Dari Sisa Kasbon',
                    'user_id'      => $this->session->userdata('user_id'),
                    'debit_credit' => 'D',
                    'rupiah'       => $sisa,
                    'saldo'        => ($saldo_before_tambah + $sisa),
                    'status'       => 1
                );

                $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_tambah);

                if(count($after_saldo_tambah) != 0){
                    foreach ($after_saldo_tambah as $after) {
                        $data_arus_kas_after = array(
                            'saldo'        => ($after['saldo'] + $sisa),
                        );

                        $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                    }
                }
            }
            
            $keterangan = '';
            if($array_input['bon']){
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
                            'biaya_id'              => $bon['biaya_id'],
                            'no_bon'              => $bon['no_bon'],
                            'total_bon'           => $bon['total_bon'],
                            'keterangan'          => $bon['keterangan'],
                            'tgl_bon'             => date('Y-m-d', strtotime($bon['tanggal'])),
                            'url'                 => $bon['url']
                        );

                        $permintaan_biaya_bon = $this->permintaan_biaya_bon_m->save($data_bon);

                    }
                }
            }

            $data_beli = array(
                'status_keuangan' => 2
            );

            $pembelian = $this->pembelian_m->update_by($data_beli, $array_input['id_po']);

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

        if($command === 'view')
        {
            $date = date('Y-m-d');
            $id = $array_input['id'];
            $user_id = $this->session->userdata('user_id');

            $data = array(
                'status'          => 5,
                'tanggal_proses'  => $date,
                'diproses_oleh'   => $this->session->userdata('user_id'),
            );
            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

            $data_bayar = array(
                'status_keuangan' => 3,
                'status'          => 5,
                
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
                'user_level_id'        => 5
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 2)->row(0);
            // die(dump($this->db->last_query()));

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
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

            $sisa = $array_input['sisa'];

            if($array_input['status_kasbon'] == 18){
                if($array_input['tipe'] == 2){
                    $last_saldo_rembes = $this->kasir_arus_kas_m->get_saldo_before($date)->result_array();
                    $after_saldo_saldo = $this->kasir_arus_kas_m->get_after_after($date)->result_array();
                    
                    $saldo_before = 0;
                    if(count($last_saldo_rembes) != 0){
                        $saldo_before = intval($last_saldo_rembes[0]['saldo']);
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

                    if(count($after_saldo_saldo) != 0){
                        foreach ($after_saldo_saldo as $after) {
                            $data_arus_kas_after = array(
                                'saldo'        => ($after['saldo'] - $array_input['nominal']),
                            );

                            $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                        }
                    }
                }

                if($sisa != 0){
                    $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date)->result_array();
                    $after_saldo = $this->kasir_arus_kas_m->get_after_after($date)->result_array();
                    
                    $saldo_before = 0;
                    if(count($last_saldo) != 0){
                        $saldo_before = intval($last_saldo[0]['saldo']);
                    }

                    $data_arus_kas = array(
                        'tanggal'      => $date,
                        'tipe'         => 5,
                        'keterangan'   => 'Tambahan '.$array_input['keperluan'],
                        'user_id'      => $this->session->userdata('user_id'),
                        'debit_credit' => 'D',
                        'rupiah'       => $sisa,
                        'saldo'        => ($saldo_before + $sisa),
                        'status'       => 1
                    );

                    $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

                    if(count($after_saldo) != 0){
                        foreach ($after_saldo as $after) {
                            $data_arus_kas_after = array(
                                'saldo'        => ($after['saldo'] + $sisa),
                            );

                            $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                        }
                    }
                }  
            }

            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Selesai Diproses", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }
        if($command === 'persetujuan_biaya_besar'){

            // die(dump($array_input));
            $date = date('Y-m-d');
            $id = $array_input['id'];
            $user_id = $this->session->userdata('user_id');
            $biaya_bon = $array_input['biaya_bon'];

            $last_number = $this->voucher_m->get_max_nomor()->result_array();
            $last_number   = intval($last_number[0]['max_no'])+1;
                
            $format        = '#VCH#%03d/RI/'.romanic_number(date('m'), true).'/'.date('Y');
            $no_voucher     = sprintf($format, $last_number, 3);

            $tipe_transaksi = 2;
            if($array_input['tipe_dana'] == 1){
                $tipe_transaksi = 2;
            }if($array_input['tipe_dana'] == 2){
                $tipe_transaksi = 3;
            }
            $data = array(
                'status'          => 15,
                'tanggal_proses'  => $date,
                'diproses_oleh'   => $this->session->userdata('user_id'),
            );
            $permintaan_biaya_id = $this->permintaan_biaya_m->save($data,$id);

            $data_bayar = array(
                'status_keuangan' => 3,
                'status'          => 15,
                
            );

            $wheres_bayar = array(
                'transaksi_id'   => $id,
                'tipe_transaksi' => $tipe_transaksi
            );
            $pembayaran_status = $this->pembayaran_status_m->update_by($user_id,$data_bayar,$wheres_bayar);

            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_bayar, true);

            $wheres_bayar_detail = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
                'user_level_id'        => $this->session->userdata('level_id')
            );
            $pembayaran_status_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 2)->row(0);
            // die(dump($this->db->last_query()));

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
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

            //looping sebanyak data permintaan_biaya_pembayaran
            foreach ($biaya_bon as $bon) {

                if($bon['total_biaya'] <= 1000000){
                    $date_bon = date('Y-m-d');
                    $last_saldo = $this->kasir_arus_kas_m->get_saldo_before($date_bon)->result_array();
                    $after_saldo = $this->kasir_arus_kas_m->get_after_after($date_bon)->result_array();
                    
                    $saldo_before = 0;
                    if(count($last_saldo) != 0){
                        $saldo_before = intval($last_saldo[0]['saldo']);
                    }

                    $data_arus_kas = array(
                        'tanggal'      => $date_bon,
                        'tipe'         => 5,
                        'keterangan'   => $bon['keterangan'],
                        'user_id'      => $this->session->userdata('user_id'),
                        'debit_credit' => 'C',
                        'rupiah'       => $bon['total_biaya'],
                        'saldo'        => ($saldo_before - $bon['total_biaya']),
                        'status'       => 1
                    );

                    $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

                    if(count($after_saldo) != 0){
                        foreach ($after_saldo as $after) {
                            $data_arus_kas_after = array(
                                'saldo'        => ($after['saldo'] - $bon['total_biaya']),
                            );

                            $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after['id']);
                        }
                    }

                    if($bon['nominal'] > 0){
                        $last_saldo_biaya = $this->kasir_arus_kas_m->get_saldo_before($date_bon)->result_array();
                        $after_saldo_biaya = $this->kasir_arus_kas_m->get_after_after($date_bon)->result_array();
                        
                        $saldo_before_biaya = 0;
                        if(count($last_saldo_biaya) != 0){
                            $saldo_before_biaya = intval($last_saldo_biaya[0]['saldo']);
                        }

                        $data_arus_kas = array(
                            'tanggal'      => $date_bon,
                            'tipe'         => 5,
                            'keterangan'   => $bon['biaya_tambah_nama'],
                            'user_id'      => $this->session->userdata('user_id'),
                            'debit_credit' => 'C',
                            'rupiah'       => $bon['nominal'],
                            'saldo'        => ($saldo_before_biaya - $bon['nominal']),
                            'status'       => 1
                        );

                        $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas);

                        if(count($after_saldo_biaya) != 0){
                            foreach ($after_saldo_biaya as $after_biaya) {
                                $data_arus_kas_after = array(
                                    'saldo'        => ($after_biaya['saldo'] - $bon['nominal']),
                                );

                                $arus_kas = $this->kasir_arus_kas_m->save($data_arus_kas_after, $after_biaya['id']);
                            }
                        }
                    }
                }elseif($bon['total_biaya'] > 1000000){

                    $date = date('Y-m-d');
                    $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $bon['bank_id'])->result_array();
                    $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $bon['bank_id'])->result_array();

                    
                    $saldo_before_bank = 0;
                    if(count($last_saldo_bank) != 0){
                        $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
                    }

                    $data_arus_kas_bank = array(
                        'tanggal'      => $date,
                        'tipe'         => 5,
                        'keterangan'   => $bon['keterangan'],
                        'bank_id'      => $bon['bank_id'],
                        'user_id'      => $this->session->userdata('user_id'),
                        'debit_credit' => 'C',
                        'rupiah'       => $bon['total_biaya'],
                        'saldo'        => ($saldo_before_bank - $bon['total_biaya']),
                        'status'       => 1
                    );

                    $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

                    if(count($after_saldo_bank) != 0){
                        foreach ($after_saldo_bank as $after) {
                            $data_arus_kas_after_bank = array(
                                'saldo'        => ($after['saldo'] - $bon['total_biaya']),
                            );

                            $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                        }
                    }

                    if($bon['nominal'] > 0){
                        $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($date, $bon['bank_id'])->result_array();
                        $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($date, $bon['bank_id'])->result_array();

                        
                        $saldo_before_bank = 0;
                        if(count($last_saldo_bank) != 0){
                            $saldo_before_bank = intval($last_saldo_bank[0]['saldo']);
                        }

                        $data_arus_kas_bank = array(
                            'tanggal'      => $date,
                            'tipe'         => 5,
                            'keterangan'   => $bon['biaya_tambah_nama'],
                            'bank_id'      => $bon['bank_id'],
                            'user_id'      => $this->session->userdata('user_id'),
                            'debit_credit' => 'C',
                            'rupiah'       => $bon['nominal'],
                            'saldo'        => ($saldo_before_bank - $bon['nominal']),
                            'status'       => 1
                        );

                        $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);

                        if(count($after_saldo_bank) != 0){
                            foreach ($after_saldo_bank as $after) {
                                $data_arus_kas_after_bank = array(
                                    'saldo'        => ($after['saldo'] - $bon['nominal']),
                                );

                                $arus_kas_bank = $this->keuangan_arus_kas_m->save($data_arus_kas_after_bank, $after['id']);
                            }
                        }
                    }
                }
                
            }

            $where_delete_rb = array(
                'transaksi_id' => $id,
                'tipe_transaksi' => 3,
            );

            $delete_hutang = $this->o_s_hutang_m->delete_by($where_delete_rb);

            $data_voucher = array(
                'nomor_voucher' => $no_voucher,
                'transaksi_id' => $id,
                'transaksi_tipe' => $tipe_transaksi,
                'is_active' => 1
            );

            $voucher_id = $this->voucher_m->save($data_voucher);

            if ($permintaan_biaya_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Permintaan Biaya Selesai Diproses", $this->session->userdata("language")),
                    "msgTitle" => translate("Success", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }

        redirect('keuangan/pembayaran_transaksi');
    }

    public function view_biaya($id_po)
    {
        $data_biaya = $this->pembelian_biaya_m->get_data($id_po)->result_array();
        $form_data = $this->pembelian_m->get_data($id_po)->result_array();

        $data = array(
            'data_biaya' => $data_biaya,
            'form_data' => $form_data,
        );
        $this->load->view('keuangan/proses_pembayaran_transaksi/view_biaya', $data);
    }

    public function proses_reimburse($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_permintaan_biaya/proses';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->permintaan_biaya_m->get($id);
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
            'flag'             => 'view',
            'pk_value'         => $id,
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
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
            'content_view'   => 'keuangan/proses_permintaan_biaya/view',
            'flag'           => 'view',
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
            
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
                'user_level_id'  => $this->session->userdata('level_id'),
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

    public function proses_kasbon($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);

        $data = array(  
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
        );

        // Load the view
        $this->load->view('keuangan/proses_permintaan_biaya/proses_kasbon', $data);
    }
    public function proses_pencairan($id)
    {
        $form_data = $this->permintaan_biaya_m->get($id);

        $data = array(  
            'pk_value'       => $id,
            'form_data'      => object_to_array($form_data),
        );

        // Load the view
        $this->load->view('keuangan/proses_permintaan_biaya/proses_pencairan', $data);
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

            $edit_pembayaran_status = $this->pembayaran_status_m->update_by($level_id, $data_bayar, $wheres_bayar);
            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_bayar, true);
            //data persetujuan permintaan pembayaran
            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['id'],
                'tipe_transaksi' => $tipe_transaksi,
                'user_level_id'  => $this->session->userdata('level_id'),
                'tipe'           => 2,
                'tipe_pengajuan' => 0
            );

            $data_pembayaran_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 1)->row(0);

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 1,
                '`order`'              => $data_pembayaran_detail_id->order - 1
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

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $data_pembayaran_detail_id->id);
            

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

            $edit_pembayaran_status = $this->pembayaran_status_m->update_by($level_id, $data_bayar, $wheres_bayar);

            $data_bayar = array(
                'status'        => 15,
            );
            $wheres_bayar = array(
                'transaksi_id' => $array_input['id'],
                'tipe_transaksi' => 2,
            );

            $edit_pembayaran_status = $this->pembayaran_status_m->update_by($level_id, $data_bayar, $wheres_bayar);
            $pembayaran_status_id = $this->pembayaran_status_m->get_by($wheres_bayar, true);
            //data persetujuan permintaan pembayaran
            $wheres_bayar_detail = array(
                'transaksi_id'   => $array_input['id'],
                'tipe_transaksi' => 2,
                'user_level_id'  => $this->session->userdata('level_id'),
                'tipe'           => 2,
                'tipe_pengajuan' => 0
            );

            $data_pembayaran_detail_id = $this->pembayaran_status_detail_m->get_data_detail($wheres_bayar_detail, 2)->row(0);

            $wheres_bayar_detail_before = array(
                'pembayaran_status_id' => $pembayaran_status_id->id,
                'tipe_pengajuan'       => 0,
                'tipe'                 => 2,
                '`order`'              => $data_pembayaran_detail_id->order - 1
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

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $data_pembayaran_detail_id->id);

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

    public function proses_pencairan_besar($id)
    {
        $assets = array();
        $assets_config = 'assets/keuangan/proses_pembayaran_transaksi/proses_persetujuan';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $user_level_id = $this->session->userdata('level_id');

        $form_data = $this->permintaan_biaya_m->get($id);
        $form_data_detail = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $id, 'is_active' => 1));

        $permintaan_biaya_bayar = $this->permintaan_biaya_pembayaran_m->get_by(array('permintaan_biaya_id' => $id));

        $user_id = $this->session->userdata('user_id');
        $level_id = $this->session->userdata('level_id');
        $date = date('Y-m-d H:i:s');

        $tipe_permintaan = 2;

        if($form_data->tipe == 1){
            $tipe_permintaan = 2;
        }else{
            $tipe_permintaan = 3;
        }

        $wheres_status = array(
            'transaksi_id'   => $id,
            'tipe_transaksi' => $tipe_permintaan,
        );    

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
            'status'         => 1,
            'tanggal_proses' => date('Y-m-d H:i:s'),
            'user_proses'    => $user_id,
            'waktu_tunggu'   => $elapsed,
            'modified_by'    => $user_id,
            'modifed_date'   => date('Y-m-d H:i:s')
        );

        $pembayaran_status_detail = $this->pembayaran_status_detail_m->edit_data($data_pembayaran_status_detail, $pembayaran_status_detail_id->id);

        
        $data = array(
            'title'            => config_item('site_name').' &gt;'. translate("Proses Pencairan Dana", $this->session->userdata("language")), 
            'header'           => translate("Proses Pencairan Dana", $this->session->userdata("language")), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => TRUE,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'keuangan/pembayaran_transaksi/proses_pencairan_besar',
            'flag'             => 'proses',
            'pk_value'         => $id,
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            'permintaan_biaya_bayar' => object_to_array($permintaan_biaya_bayar),
            
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function print_voucher($id)
    {
        $this->load->library('mpdf/mpdf.php');

        $form_data        = $this->permintaan_biaya_m->get($id);

        $tipe = 2;
        if($form_data->tipe == 2){
            $tipe = 3;
        }

        $data_voucher = $this->voucher_m->get_by(array('transaksi_id' => $id, 'transaksi_tipe' => $tipe), true);

        $body = array(

            'form_data'        => object_to_array($form_data),
            'data_voucher'        => object_to_array($data_voucher),

        );

        $mpdf = new mPDF('utf-8','A4', 1, '', 5, 5, 5, 2, 0, 0);
        
        $mpdf->writeHTML($this->load->view('keuangan/proses_permintaan_biaya/print_voucher/voucher', $body, true));

        $mpdf->Output('voucher_'.date('Y-m-d H:i:s', strtotime($form_data->tanggal_proses)).'.pdf', 'I'); 
    } 

    public function print_voucher_ttf($id,$trans_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $form_data        = $this->pembayaran_status_m->get_by(array('id'=>$id),true);
        $form_data_ttf    = $this->tanda_terima_faktur_m->get_by(array('id' => $trans_id), true);
        $form_data_po     = $this->pembelian_detail_m->get_data_detail($form_data_ttf->pembelian_id);


        $data_voucher = $this->voucher_m->get_by(array('transaksi_id' => $trans_id, 'transaksi_tipe' => 4), true);

        $body = array(

            'form_data'        => object_to_array($form_data),
            'form_data_ttf'        => object_to_array($form_data_ttf),
            'data_voucher'        => object_to_array($data_voucher),
            'form_data_po'        => object_to_array($form_data_po)

        );

        $mpdf = new mPDF('utf-8','A4', 1, '', 5, 5, 5, 2, 0, 0);
        
        $mpdf->writeHTML($this->load->view('keuangan/proses_permintaan_biaya/print_voucher/voucher_ttf', $body, true));

        $mpdf->Output('voucher_'.date('Y-m-d H:i:s', strtotime($form_data->tanggal_proses)).'.pdf', 'I'); 
    } 

    public function print_voucher_po($id,$trans_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $form_data        = $this->pembayaran_status_m->get_by(array('id'=>$id),true);
        $form_data_ttf     = $this->pembelian_m->get_by(array('id'=>$trans_id),true);
        $form_data_po     = $this->pembelian_detail_m->get_data_detail($trans_id);


        $data_voucher = $this->voucher_m->get_by(array('transaksi_id' => $trans_id, 'transaksi_tipe' => 1), true);

        $body = array(

            'form_data'           => object_to_array($form_data),
            'data_voucher'        => object_to_array($data_voucher),
            'form_data_ttf'        => object_to_array($form_data_ttf),
            'form_data_po'        => object_to_array($form_data_po)

        );

        $mpdf = new mPDF('utf-8','A4', 1, '', 5, 5, 5, 2, 0, 0);
        
        $mpdf->writeHTML($this->load->view('keuangan/proses_permintaan_biaya/print_voucher/voucher_po', $body, true));

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

}

/* End of file pembayaran_transaksi.php */
/* Location: ./application/controllers/keuangan/pembayaran_transaksi.php */