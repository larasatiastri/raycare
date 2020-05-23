<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tanda_terima_faktur extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'db16a3cd81a3b7b3fd22874e444255c2';                  // untuk check bit_access

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

        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_m');
        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_detail_m');
        $this->load->model('keuangan/tanda_terima_faktur/pembelian_m');
        $this->load->model('keuangan/tanda_terima_faktur/pembelian_detail_m');
        $this->load->model('pembelian/pembelian_biaya_m');
        $this->load->model('pembelian/pembelian_invoice_m');

        $this->load->model('keuangan/tanda_terima_faktur/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m');

        $this->load->model('pembelian/supplier_m');
        $this->load->model('master/supplier/supplier_alamat_m');
        $this->load->model('master/supplier/supplier_telepon_m');
        $this->load->model('master/supplier/supplier_email_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('pembelian/penerima_cabang_m');

        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/biaya_m');

        $this->load->model('others/kotak_sampah_m');
        $this->load->model('keuangan/perubahan_modal/o_s_hutang_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/tanda_terima_faktur/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header'         => translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/tanda_terima_faktur/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_status()
    {       

        $result = $this->pembayaran_status_m->get_datatable_ttf();
        $user_level_id = $this->session->userdata('level_id');
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

            $tipe = 'Tanda Terima Faktur';

            if($row['status'] == 1)
            {                    
                $status = '<div class="text-left"><span class="label label-md label-info">Proses Keuangan</span></div>';
                
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/tanda_terima_faktur/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
            }
            if($row['status'] == 2)
            {                    
                $status = '<div class="text-left"><span class="label label-md label-danger">Proses Pencairan</span></div>';
                
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/tanda_terima_faktur/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
            }
            if($row['status'] == 3)
            {                    
                $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/tanda_terima_faktur/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
            }


            $data_print = '<a title="'.translate('Print', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'keuangan/tanda_terima_faktur/cetak_terima_faktur/'.$row['transaksi_id'].'" class="btn btn-primary"><i class="fa fa-print"></i> </a>';

            $action .=  restriction_button($data_print,$user_level_id,'tanda_terima_faktur','cetak_terima_faktur');

            

            $output['aaData'][] = array(
                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'"></span>',
                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',
                '<div class="text-left">'.$row['nama_supplier'].'</div>',
                '<div class="text-left">'.$row['transaksi_nomor'].'|'.$row['no_pembelian'].'</div>',
                '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                '<div class="text-left">'.$status.'</div>',
                '<div class="text-left">'.$row['nama_level_proses'].'</div>',
                '<div class="text-center inline-button-table">'.$waktu_akhir.'</div>',
                '<div class="text-left inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function listing_tanda_terima_faktur()
    {

        $result = $this->tanda_terima_faktur_m->get_datatable();
        // die_dump($result);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=0;

        $action = '';
        $status = '';

        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            // die_dump($row['tanggal']);
           
            $data_view = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'keuangan/tanda_terima_faktur/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
            $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/tanda_terima_faktur/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
            $data_print = '<a title="'.translate('Print', $this->session->userdata('language')).'" target="_blank" href="'.base_url().'keuangan/tanda_terima_faktur/cetak_terima_faktur/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-print"></i> </a>';

            // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
            // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
                $action =  restriction_button($data_view,$user_level_id,'tanda_terima_faktur','view').restriction_button($data_print,$user_level_id,'tanda_terima_faktur','cetak_terima_faktur');

                $output['data'][] = array(
                    $row['id'],
                    $row['no_tanda_terima_faktur'],
                    '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                    $row['nama_supplier'],
                    $row['no_pembelian'],
                    $row['nama_penyerah'],
                    $row['nama_penerima'],
                    '<div class="text-right">'.formatrupiah($row['nominal']).'</div>',
                    '<div class="text-center">'.$action.'</div>' 
                );

         $i++;
        }

        echo json_encode($output);

    }

    public function listing_supplier($tipe=null)
    {
        
        $result = $this->supplier_m->get_datatable($tipe);
        // die_dump($this->db->last_query());

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
            $rate = '';
            $email_supplier = $this->supplier_email_m->get_by(array('supplier_id' => $row['id'], 'is_active' => 1, 'is_primary' => 1), true);


            $negara= "Indonesia";
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-email="'.htmlentities(json_encode($email_supplier)).'" data-negara="'.$negara.'" class="btn btn-primary select-supplier"><i class="fa fa-check"></i></a>';

            if($row['rating'] == 0.0)
            {
                $rate = '<i class="fa fa-star-o"></i>';
            }

            else if($row['rating'] > 0.0)
            {
                for($x=1;$x<=$row['rating']/2;$x++)
                 {
                     $rate .= '<i class="fa fa-star"></i>';
                 }
            }

 
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                $row['kode'],
                $row['nama'],
                '<div class="text-left">'.$row['kontak_person'].' ('.$row['no_telp'].')'.'</div>',
                '<div class="text-left">'.$row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['propinsi'].', '.$negara.'</div>',
                '<div class="text-center">'.$rate.'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
        $rate = '';
         $i++;
        }

        echo json_encode($output);
    }

    public function listing($tipe=null, $supplier_id=null)
    {
        if($tipe != null){
            $tipe = rtrim($tipe,'-');
        }
        $result = $this->pembelian_m->get_datatable($tipe,$supplier_id);

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
            $row['tanggal'] = date('d M Y', strtotime($row['tanggal']));
            $row['tanggal_kadaluarsa'] = date('d M Y', strtotime($row['tanggal_kadaluarsa']));
            $row['tanggal_garansi'] = date('d M Y', strtotime($row['tanggal_garansi']));
            $data_customer = array();
            if($row['tipe_customer'] == 1){
                $data_customer = $this->penerima_cabang_m->get_data($row['customer_id'])->row(0);
                $data_customer = object_to_array($data_customer);

                $data_customer['alamat'] = $data_customer['alamat'].', '.$data_customer['nama_kelurahan'].', '.$data_customer['nama_kabupatenkota'];
            }

            $data_supplier = $this->supplier_m->get_data($row['supplier_id'])->row(0);
            $data_supplier = object_to_array($data_supplier);

            $data_supplier['alamat'] = $data_supplier['alamat'].', '.$data_supplier['nama_kelurahan'].', '.$data_supplier['nama_kabupatenkota'];

            $data_detail = $this->pembelian_detail_m->get_data_detail($row['id']);


            $satuan_item = array();
            $y = 0;
            foreach ($data_detail as $detail) {
                $satuan_terkecil   = $this->item_satuan_m->get_satuan_terkecil($detail['item_id'])->result_array();

                $data_konversi     = $this->item_satuan_m->get_data_konversi($detail['item_id'], $satuan_terkecil[0]['id']);
                $satuan_item[$y]   = $data_konversi;

                $y++;
            }
            //die(dump($this->db->last_query()));
            // $data_detail['tanggal_kirim'] = date('d M Y', strtotime($data_detail['tanggal_kirim']));

            $form_data_detail = $this->pembelian_detail_m->get_data_detail($row['id']);

            $grand_total = 0;
            $tad = 0;
            $tat = 0;

            foreach ($form_data_detail as $key => $detail) {
                
                $sub_total = $detail['harga_beli'] * $detail['jumlah_disetujui'];
                $tad_detail = $sub_total - (($detail['diskon']/100) * $sub_total);

                $grand_total = $grand_total + $tad_detail;
            }

            $tad = $grand_total - (($row['diskon']/100) * $grand_total);
            $tat = $tad + (($row['pph']/100)* $tad);
            $tat = $tat - $row['pembulatan'];
        
            $action ='<a title="'.translate('Select', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" data-customer="'.htmlentities(json_encode($data_customer)).'" data-supplier="'.htmlentities(json_encode($data_supplier)).'" data-detail="'.htmlentities(json_encode($data_detail)).'" data-satuan_item = "'.htmlentities(json_encode($satuan_item)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
  
            $output['data'][] = array(
                '<div class="text-center">'.$row['no_po'].'</div>',
                '<div class="text-center">'.$row['tanggal'].'</div>',
                '<div class="text-right">'.formatrupiah($tat).'</div>',
               
                '<div class="text-center inline-button-table">'.$action.'</div>'  
            );
        
            $i++;
        
        }

        echo json_encode($output);

    }


    public function add()
    {

        $assets = array();
        $config = 'assets/keuangan/tanda_terima_faktur/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
    
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header'         => translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/tanda_terima_faktur/add',
           
            'flag'           => 1,
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    

    public function save()
    {
        $command = $this->input->post('command');
        $array_input = $this->input->post();

        // die(dump($array_input));

        if ($command === 'add')
        {

            // if($array_input['gudang_ke'] != "")
            // {
            $last_id = $this->tanda_terima_faktur_m->get_max_id_tanda_terima_faktur()->result_array();
            $last_id = intval($last_id[0]['max_id'])+1;

            $format_id = 'TTF-'.date('m').'-'.date('Y').'-%04d';
            $id_ttf = sprintf($format_id, $last_id,4);
            // die_dump($id_ttf);

            $last_number    = $this->tanda_terima_faktur_m->get_no_tanda_terima_faktur()->result_array();
            // die_dump($last_number);
            $last_number    = intval($last_number[0]['max_no_tanda_terima_faktur'])+1;

            $format         = '#TTF#%03d/'.romanic_number(date('m'), true).'/'.date('Y');
            $no_ttf       = sprintf($format, $last_number, 3);

            $data = array(
                'id'                     => $id_ttf,
                'pembelian_id'           => $array_input['id_po'],
                'no_tanda_terima_faktur' => $no_ttf,
                'supplier_id'            => $array_input['id_supplier'],
                'is_pkp'                 => $array_input['is_pkp'],
                'tanggal'                => date('Y-m-d H:i:s', strtotime($array_input['tanggal_faktur'])),
                'diserahkan_oleh'        => $array_input['diserahkan'],
                'no_telepon'             => $array_input['no_telepon'],
                'nominal'                => $array_input['total_invoice'],
                'status'                 => 1,
                'is_active'              => 1,
                'created_by'             => $this->session->userdata('user_id'),
                'created_date'           => date('Y-m-d H:i:s')
            );

            // die_dump($data);

            $tanda_terima_faktur = $this->tanda_terima_faktur_m->add_data($data);

            $last_id_os_hutang   = $this->o_s_hutang_m->get_max_id_o_s_hutang()->result_array();
            $last_id_os_hutang   = intval($last_id_os_hutang[0]['max_id'])+1;
            
            $format_id = 'OSH-'.date('mY').'-%04d';
            $id_os_hutang  = sprintf($format_id, $last_id_os_hutang, 4);

            $supplier = $this->supplier_m->get_by(array('id' => $array_input['id_supplier']), true);

            $insert_os_hutang = array(
                'id'                    => $id_os_hutang,
                'tanggal'               => date('Y-m-d', strtotime($array_input['tanggal_faktur'])),
                'transaksi_id'          => $id_ttf,
                'tipe_transaksi'        => 2,
                'nomor_transaksi'        => $no_ttf,
                'pemberi_hutang_id'     => $array_input['id_supplier'],
                'nama_pemberi_hutang'   => $supplier->nama,
                'tipe_pemberi_hutang'   => 1,
                'jumlah'                => $array_input['total_invoice'],
                'created_by'            => $this->session->userdata('user_id'),
                'created_date'          => date('Y-m-d H:i:s'),
            );

            $save_os_hutang = $this->o_s_hutang_m->add_data($insert_os_hutang);

            $last_id_status       = $this->pembayaran_status_m->get_max_id_pembayaran()->result_array();
            $last_id_status       = intval($last_id_status[0]['max_id'])+1;
            
            $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
            $id_status         = sprintf($format_id_status, $last_id_status, 4);

            $divisi_posisi = $this->user_level_m->get(5);
            $data_status = array(
                'id'              => $id_status,
                'transaksi_id'    => $id_ttf,
                'transaksi_nomor' => $no_ttf,
                'tipe_transaksi'  => 4,
                'nominal'         => $array_input['total_invoice'],
                'status'          => 1,
                'user_level_id'   => 5,
                'divisi'          => $divisi_posisi->divisi_id,
                'waktu_akhir'     => date('Y-m-d', strtotime($array_input['tanggal_faktur'])),
                'is_active'       => 1,
                'created_by'      => $this->session->userdata('user_id'),
                'created_date'    => date('Y-m-d H:i:s')
            );

            $pembayaran_status = $this->pembayaran_status_m->add_data($data_status);

            $nama_user = $this->session->userdata('username');
            sent_notification(12,$nama_user,$id_ttf);

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
                    $status = 2;
                    $keterangan = $array_input['keterangan'];
                    $user_proses = $this->session->userdata('user_id');
                    $tanggal_proses = date('Y-m-d H:i:s');
                    $waktu_tunggu = '-';

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
                    'transaksi_id'         => $id_ttf,
                    'tipe_transaksi'       => 4,
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

            $data_po = array(
                'status_keuangan' => 2
            );

            $edit_po = $this->pembelian_m->edit_data($data_po,$array_input['id_po']);
            // }
            // die_dump($this->db->last_query());
            foreach ($array_input['bon'] as $row) 
            {
                if($row['no_bon'] != "")
                {

                    $last_id_invoice = $this->pembelian_invoice_m->get_max_id_invoice_pembelian()->result_array();
                    $last_id_invoice = intval($last_id_invoice[0]['max_id'])+1;

                    $format_id_invoice   = 'POI-'.date('m').'-'.date('Y').'-%04d';
                    $id_po_invoice       = sprintf($format_id_invoice, $last_id_invoice, 4);

                    $data_bon = array(
                        'id'            => $id_po_invoice,
                        'pembelian_id'  => $array_input['id_po'],
                        'pembayaran_status_id'  => $id_status,
                        'no_invoice'    => $row['no_bon'],
                        'total_invoice' => $row['total_bon'],
                        'tgl_invoice'   => date('Y-m-d', strtotime($row['tanggal'])),
                        'keterangan'    => $row['keterangan'],
                        'url'           => $row['url'],
                        'url_faktur_pajak' => $row['url_pajak'],
                        'is_active'     => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'  => date('Y-m-d H:i:s'),
                    );

                    $pembelian_invoice = $this->pembelian_invoice_m->add_data($data_bon);

                    $last_id_detail = $this->tanda_terima_faktur_detail_m->get_max_id_tanda_terima_faktur_detail()->result_array();
                    $last_id_detail = intval($last_id_detail[0]['max_id'])+1;

                    $format_id_detail   = 'TTFD-'.date('m').'-'.date('Y').'-%04d';
                    $id_ttf_detail       = sprintf($format_id_detail, $last_id_detail, 4);

                    $data_detail = array(
                        'id'                     => $id_ttf_detail,
                        'tanda_terima_faktur_id' => $id_ttf,
                        'pembelian_invoice_id'   => $id_po_invoice,
                        'pembayaran_status_id'   => $id_status,
                        'no_berkas'              => $row['no_bon'],
                        'tanggal'                => date('Y-m-d H:i:s', strtotime($row['tanggal'])),
                        'keterangan'             => $row['keterangan'],
                        'nominal'                => $row['total_bon'],
                        'url_berkas'             => $row['url'],
                        'url_faktur_pajak'       => $row['url_pajak'],
                        'is_active'              => 1,
                        'created_by'             => $this->session->userdata('user_id'),
                        'created_date'           => date('Y-m-d H:i:s')
                    );

                    $tanda_terima_faktur_detail = $this->tanda_terima_faktur_detail_m->add_data($data_detail);


                    if($row['url'] != '')
                    {
                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.str_replace(' ', '_', $id_ttf);
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $row['url'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $row['url'];
                        $real_file = str_replace(' ', '_', $id_ttf).'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_ttf').$real_file);

                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$id_status;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $row['url'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $row['url'];
                        $real_file = $id_status.'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bayar').$real_file);

                    }

                    if($row['url_pajak'] != '')
                    {
                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.str_replace(' ', '_', $id_ttf);
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $row['url_pajak'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $row['url_pajak'];
                        $real_file = str_replace(' ', '_', $id_ttf).'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_ttf').$real_file);

                        $path_dokumen = '../cloud/'.config_item('site_dir').'pages/keuangan/pembayaran_transaksi/images/'.$id_status;
                        if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                        $temp_filename = $row['url_pajak'];

                        $convtofile = new SplFileInfo($temp_filename);
                        $extenstion = ".".$convtofile->getExtension();

                        $new_filename = $row['url_pajak'];
                        $real_file = $id_status.'/'.$new_filename;

                        copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bayar').$real_file);

                    }
                }
            }

            $data_po = array(
                'biaya_tambahan'  => $array_input['biaya_tambah_hidden'],
                'grand_total'     => $array_input['grand_tot_biaya_hidden'],
                'modified_by'     => $this->session->userdata('user_id'),
                'modifed_date'    => date('Y-m-d H:i:s')
            );

            $pembelian_edit = $this->pembelian_m->edit_data($data_po, $array_input['id_po']);


            if($array_input['biaya']){
                foreach ($array_input['biaya'] as $key => $biaya) {
                    if($biaya['id'] == '' && $biaya['biaya_id'] == '' && $biaya['nominal'] == '' ){

                        $last_id_biaya = $this->pembelian_biaya_m->get_max_id_biaya_pembelian()->result_array();
                        $last_id_biaya = intval($last_id_biaya[0]['max_id'])+1;

                        $format_id_biaya   = 'POB-'.date('m').'-'.date('Y').'-%04d';
                        $id_po_biaya       = sprintf($format_id_biaya, $last_id_biaya, 4);

                        $data_biaya = array(
                            'id'           => $id_po_biaya,
                            'pembelian_id' => $array_input['id_po'],
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

            if($tanda_terima_faktur && $tanda_terima_faktur_detail)
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data tanda terima faktur berhasil ditambah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }



        if($command === 'update')
        {

            $pk_value = $this->input->post('pk_value');
            // die_dump($array_input);
            if($array_input['pk_value'] != "")
            {

                $data_tanda_terima_faktur = array(

                    'dari_gudang_id' => $array_input['gudang_dari'],
                    'ke_gudang_id'   => $array_input['gudang_ke'],
                    'tanggal'        => date('Y-m-d H:i:s', strtotime($array_input['tanggal_request'])),
                    'keterangan'     => $array_input['keterangan'],
                    'status'         => 1,
                    'is_active'      => 1,

                );

                $tanda_terima_faktur_update = $this->tanda_terima_faktur_m->save($data_tanda_terima_faktur, $pk_value);
            }

            foreach ($array_input['items'] as $item) 
            {
                # code...
                if($item['item_id'] != "")
                {

                    $data_tanda_terima_faktur_detail = array(

                    'tanda_terima_faktur_id' => $tanda_terima_faktur_update,
                    'item_id'          => $item['item_id'],
                    'item_satuan_id'   => $item['satuan'],
                    'jumlah'           => $item['jumlah_kirim'],    


                    );
                    
                    $tanda_terima_faktur_detail_update = $this->tanda_terima_faktur_detail_m->save($data_tanda_terima_faktur_detail, $tanda_terima_faktur_update);
                    // die_dump($this->db->last_query());
                }
            }

            if ($tanda_terima_faktur_update && $tanda_terima_faktur_detail_update)
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Tanda Terima Faktur berhasil Diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

        }

        redirect('keuangan/tanda_terima_faktur');
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/keuangan/tanda_terima_faktur/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $form_data = $this->tanda_terima_faktur_m->get_by(array('id' => $id), true);
        $form_data_detail = $this->tanda_terima_faktur_detail_m->get_by(array('tanda_terima_faktur_id' => $id));
        // die_dump($form_data);

        $pembelian = $this->pembelian_m->get_by(array('id' => $form_data->pembelian_id), true);
        $pembelian = object_to_array($pembelian);

        $form_data_detail_po = $this->pembelian_detail_m->get_data_detail($pembelian['id']);

        $grand_total = 0;
        $tad = 0;
        $tat = 0;

        foreach ($form_data_detail_po as $key => $detail) {
            
            $sub_total = $detail['harga_beli'] * $detail['jumlah_disetujui'];
            $tad_detail = $sub_total - (($detail['diskon']/100) * $sub_total);

            $grand_total = $grand_total + $tad_detail;
        }

        $tad = $grand_total - (($pembelian['diskon']/100) * $grand_total);
        $tat = $tad + (($pembelian['pph']/100)* $tad);
        $tat = $tat - $pembelian['pembulatan'];
        
        $data = array(
            'title'            => config_item('site_name').' | '.translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header'           => translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => true,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'keuangan/tanda_terima_faktur/view',
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            'tat'              => $tat,
            'pk_value'         => $id,
            'flag'             => 1
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {

        $assets = array();
        $config = 'assets/gudang/tanda_terima_faktur/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $form_data = $this->tanda_terima_faktur_m->get($id);
        // die_dump($form_data);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header'         => translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'gudang/tanda_terima_faktur/edit',
            'form_data'      => object_to_array($form_data),
            'form_item_detail'      => $this->tanda_terima_faktur_detail_m->get_data($id),
            'pk_value'       => $id,
            );
        // die_dump($this->db->last_query());
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function modal_po($supplier_id=null)
    {
        $data_supplier = $this->supplier_m->get($supplier_id);
        $data = array(
            'supplier_id' => $supplier_id,
            'data_supplier' => object_to_array($data_supplier),
        );
        $this->load->view('keuangan/tanda_terima_faktur/modal_po', $data);
    }

    public function modal_pmb($po_detail_id)
    {
        $data_po_detail = $this->pembelian_detail_m->get_po_detail($po_detail_id);

        $data_pmb = $this->pembelian_detail_m->get_data_pmb($po_detail_id);
        $data = array(
            'data_po_detail' => object_to_array($data_po_detail),
            'data_pmb' => object_to_array($data_pmb),
        );
        $this->load->view('keuangan/tanda_terima_faktur/modal_pmb', $data);
    }

    public function cetak_terima_faktur($id)
    {
        $this->load->library('mpdf/mpdf.php');

        $data_faktur = $this->tanda_terima_faktur_m->get_by(array('id' => $id), true);
        $data_supplier = $this->supplier_m->get($data_faktur->supplier_id);
        $data_faktur_detail = $this->tanda_terima_faktur_detail_m->get_by(array('tanda_terima_faktur_id' => $id));

        $mpdf = new mPDF('utf-8','A4', 0, '', 5, 5, 5, 0, 0, 0);
        $stylesheet = file_get_contents(base_url().'assets/mb/global/css/pdf_surat.css');
        $stylesheets = file_get_contents(base_url().'assets/metronic/global/plugins/bootstrap/css/bootstrap.css');
        $mpdf->writeHTML($stylesheet, 1);
        $mpdf->writeHTML($stylesheets, 1);
    
        $body = array(
            'data_faktur'        =>  object_to_array($data_faktur),
            'data_supplier'      => object_to_array($data_supplier),
            'data_faktur_detail' => object_to_array($data_faktur_detail)
        );
        $mpdf->writeHTML($this->load->view('keuangan/tanda_terima_faktur/cetak_terima_faktur', $body, true));

        $mpdf->Output('tanda_terima_faktur'.$data_faktur->no_tanda_terima_faktur.'.pdf', 'I'); 
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

    public function get_item_satuan()
    {
        $item_id           = $this->input->post('item_id');
        
        $satuan_terkecil   = $this->item_satuan_m->get_satuan_terkecil($item_id)->result_array();
        $data_konversi     = $this->item_satuan_m->get_data_konversi($item_id, $satuan_terkecil[0]['id']);


        echo json_encode($data_konversi);
    }

}

/* End of file tanda_terima_faktur.php */
/* Location: ./application/controllers/tanda_terima_faktur/tanda_terima_faktur.php */