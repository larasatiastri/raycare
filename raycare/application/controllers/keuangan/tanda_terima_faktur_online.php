<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tanda_terima_faktur_online extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '6b04da9511c37a09b9fe81453fae3103';                  // untuk check bit_access

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
        $this->load->model('pembelian/supplier_tipe_pembayaran_m');

        $this->load->model('keuangan/tanda_terima_faktur/pembayaran_status_online_m');
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
        $this->load->model('master/user_supplier_m');

        $this->load->model('others/kotak_sampah_m');
        $this->load->model('keuangan/perubahan_modal/o_s_hutang_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/tanda_terima_faktur_online/index';
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
            'content_view'   => 'keuangan/tanda_terima_faktur_online/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_status()
    {       

        $result = $this->pembayaran_status_online_m->get_datatable_ttf();
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

            if($row['status'] == 0 && $row['status_revisi'] == 0)
            {                    
                $status = '<div class="text-left"><span class="label label-md label-warning">Menunggu Verifikasi</span></div>';
                
                $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/tanda_terima_faktur_online/proses_verif/'.$row['transaksi_id'].'"><i class="fa fa-cogs"></i></a>';
            }if($row['status'] == 0 && $row['status_revisi'] == 1)
            {                    
                $status = '<div class="text-left"><span class="label label-md label-danger">Proses Revisi</span></div>';
                
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/tanda_terima_faktur_online/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
            }if($row['status'] == 1){                    
                $status = '<div class="text-center"><span class="label label-md label-danger">Menunggu Diproses</span></div>';
                
                $action = '<a title="'.translate('Process', $this->session->userdata('language')).'" class="btn btn-primary" href="'.base_url().'keuangan/proses_pembayaran_transaksi/proses_ttf_po/'.$row['transaksi_id'].'"><i class="fa fa-check"></i></a>';
            }

            if($row['status'] == 2)
            {                    
                $status = '<div class="text-center"><span class="label label-md label-success">Pencairan Dana</span></div>';
                
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/proses_pembayaran_transaksi/view_proses_bayar_po_ttf/'.$row['id'].'/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
            }
            if($row['status'] == 3)
            {                    
                $status = '<div class="text-left"><span class="label label-md label-success">Selesai</span></div>';
                
                $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade" href="'.base_url().'keuangan/tanda_terima_faktur_online/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i></a>';
            }


            

            

            $output['aaData'][] = array(
                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'"></span>',
                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['inisial'].'</div>',
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
        $user_id = $this->session->userdata('user_id');

        $url_website = 'http://klinikraycare.com/supkld/';

        if ($command === 'verifikasi')
        {
            $ttf_id = $array_input['id'];
            $data_pembayaran_status = $this->pembayaran_status_online_m->get_by(array('transaksi_id' => $ttf_id), true);

            $data_verif = array(
                'status' => 1,
                'is_grab' => 1
            );

            $edit_ttf = $this->tanda_terima_faktur_m->edit_data($data_verif, $ttf_id);

            $path_model = 'keuangan/tanda_terima_faktur/tanda_terima_faktur_m';
            $edit_api_ttf = insert_data_api_id($data_verif, $url_website, $path_model, $ttf_id);


            $edit_pembayaran_status = $this->pembayaran_status_online_m->edit_data($data_verif, $data_pembayaran_status->id);

            $path_model = 'keuangan/tanda_terima_faktur/pembayaran_status_m';
            $edit_api_ps = insert_data_api_id($data_verif, $url_website, $path_model, $data_pembayaran_status->id);

            $waktu_proses = $data_pembayaran_status->created_date;
            

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $wheres_bayar_detail = array(
                'pembayaran_status_id' => $data_pembayaran_status->id,
                '`order`'              => 1,
                'user_level_id'        => 5
            );

            $data_pembayaran_status_detail = array(
                'status'         => 2,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'keterangan'     => $array_input['keterangan'],
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->update_by($user_id,$data_pembayaran_status_detail,$wheres_bayar_detail);

            $path_model = 'keuangan/tanda_terima_faktur/pembayaran_status_detail_m';
            $edit_api_ps_detail = update_data_api($data_pembayaran_status_detail, $url_website, $path_model, $wheres_bayar_detail);

        
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Verifikasi berhasil", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        if ($command === 'tolak_ttf')
        {
            $ttf_id = $array_input['id'];
            $data_pembayaran_status = $this->pembayaran_status_online_m->get_by(array('transaksi_id' => $ttf_id), true);

            $data_verif = array(
                'status' => 0,
                'is_revisi' => 1,
                'keterangan_revisi' => $array_input['keterangan_tolak'],
            );

            $edit_ttf = $this->tanda_terima_faktur_m->edit_data($data_verif, $ttf_id);

            $path_model = 'keuangan/tanda_terima_faktur/tanda_terima_faktur_m';
            $edit_api_ttf = insert_data_api_id($data_verif, $url_website, $path_model, $ttf_id);

            $data_revisi = array(
                'status' => 0,
                'status_revisi' => 1
            );

            $edit_pembayaran_status = $this->pembayaran_status_online_m->edit_data($data_revisi, $data_pembayaran_status->id);

            $path_model = 'keuangan/tanda_terima_faktur/pembayaran_status_m';
            $edit_api_ps = insert_data_api_id($data_revisi, $url_website, $path_model, $data_pembayaran_status->id);

            $waktu_proses = $data_pembayaran_status->created_date;
            

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($waktu_proses);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            $wheres_bayar_detail = array(
                'pembayaran_status_id' => $data_pembayaran_status->id,
                // '`order`'              => 1,
                'user_level_id'        => 5
            );

            $data_pembayaran_status_detail = array(
                'status'         => 0,
                'tanggal_proses' => date('Y-m-d H:i:s'),
                'user_proses'    => $user_id,
                'waktu_tunggu'   => $elapsed,
                'keterangan'     => $array_input['keterangan_tolak'],
                'modified_by'    => $user_id,
                'modifed_date'   => date('Y-m-d H:i:s')
            );

            $pembayaran_status_detail = $this->pembayaran_status_detail_m->update_by($user_id,$data_pembayaran_status_detail,$wheres_bayar_detail);

            $path_model = 'keuangan/tanda_terima_faktur/pembayaran_status_detail_m';
            $edit_api_ps_detail = update_data_api($data_pembayaran_status_detail, $url_website, $path_model, $wheres_bayar_detail);

        
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Tukar Faktur berhasil ditolak", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        redirect('keuangan/tanda_terima_faktur_online');
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

    public function proses_verif($id)
    {
        $assets = array();
        $config = 'assets/keuangan/tanda_terima_faktur_online/proses_verif';
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
        
        $form_data_po = $this->pembelian_m->get_data($form_data->pembelian_id)->result_array();
        $supplier_tipe_bayar = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data_po[0]['id'])->result_array();
        $tipe_bayar = $this->supplier_tipe_pembayaran_m->get_tipe_pembayaran($form_data_po[0]['tipe_pembayaran'])->result_array();
        $form_data_detail_po = $this->pembelian_detail_m->get_data_detail($form_data->pembelian_id);
        $data_invoice = $this->pembelian_invoice_m->get_by(array('pembelian_id' =>$form_data->pembelian_id));
        $data_biaya = $this->pembelian_biaya_m->get_data($form_data->pembelian_id)->result_array();
        $data_bayar = $this->pembayaran_status_online_m->get_by(array('transaksi_id' => $ttf_id, 'tipe_transaksi' => 4), true);

        $data = array(
            'title'            => config_item('site_name').' | '.translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header'           => translate('Tanda Terima Faktur', $this->session->userdata('language')), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => true,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'content_view'     => 'keuangan/tanda_terima_faktur_online/proses_verif',
            'form_data'        => object_to_array($form_data),
            'form_data_detail' => object_to_array($form_data_detail),
            'form_data_po'           => object_to_array($form_data_po),
            'data_biaya'          => object_to_array($data_biaya),
            'data_invoice'        => object_to_array($data_invoice),
            'data_bayar'          => object_to_array($data_bayar),
            'supplier_tipe_bayar' => $supplier_tipe_bayar,
            'tipe_bayar'          => $tipe_bayar,
            'form_data_detail_po'    => (count($form_data_detail_po) != 0)?object_to_array($form_data_detail_po):'',
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

    public function reject_proses($ttf_id)
    {
        $data_ttf = $this->tanda_terima_faktur_m->get_by(array('id' => $ttf_id), true);

        $body = array(
            'data_ttf' => object_to_array($data_ttf)
        );

        $this->load->view('keuangan/tanda_terima_faktur_online/modal_reject', $body);
    }

}

/* End of file tanda_terima_faktur.php */
/* Location: ./application/controllers/tanda_terima_faktur/tanda_terima_faktur.php */