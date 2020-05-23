<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buat_invoice extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '6269f025af98392e499f0cfb7538a03c';                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    // private $data_main = array();

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('master/pasien_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/paket_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_history_m');
        $this->load->model('klinik_hd/tindakan_hd_history_m');
        $this->load->model('reservasi/invoice/draf_invoice_m');   
        $this->load->model('reservasi/invoice/draf_invoice_detail_m'); 

   }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/reservasi/buat_invoice/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Invoice', $this->session->userdata('language')), 
            'header'         => translate('Invoice', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/buat_invoice/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/reservasi/buat_invoice/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Buat Invoice', $this->session->userdata('language')), 
            'header'         => translate('Buat Invoice', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/buat_invoice/add',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id, $pasien_id)
    {
        $assets = array();
        $config = 'assets/reservasi/buat_invoice/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        //$form_detail = $this->invoice_m->get($id);
        $form_detail = $this->draf_invoice_m->get_by(array('id' => $id), true);
       // $form_detail_item = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'is_active' => 1));
        $form_detail_item = $this->draf_invoice_detail_m->get_by(array('draf_invoice_id' => $id, 'is_active' => 1));

        $data = array(
            'title'            => config_item('site_name').' | '.translate('Edit Invoice', $this->session->userdata('language')), 
            'header'           => translate('Edit Invoice', $this->session->userdata('language')), 
            'header_info'      => config_item('site_name'), 
            'breadcrumb'       => true,
            'menus'            => $this->menus,
            'menu_tree'        => $this->menu_tree,
            'css_files'        => $assets['css'],
            'js_files'         => $assets['js'],
            'form_detail'      => object_to_array($form_detail),
            'form_detail_item' => object_to_array($form_detail_item),
            'pk_value'         => $id,
            'content_view'     => 'reservasi/buat_invoice/edit'
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
     
    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        
        $result = $this->invoice_m->get_datatable();
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
            $status = '';
            $action = '';
            $noinvoice =$row['no_invoice'];
            
            $jenis = '';

            if($row['jenis_invoice']==1){
                $jenis = 'Internal';
            }if($row['jenis_invoice']==2){
                $jenis = 'External';
            }
            if($row['status'] == 1){
                
                $noinvoice = '<a href="javascript:;" class="bold" style="color:red;">'.$row['no_invoice'].'</a>';

                $action .='<a title="'.translate('Edit Invoice', $this->session->userdata('language')).'" href="'.base_url().'reservasi/buat_invoice/edit/'.$row['id'].'/'.$row['pasien_id'].'" class="btn blue-chambray hidden"><i class="fa fa-edit"></i></a>';
            }
            $action .='<a title="'.translate('Print Invoice', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'reservasi/buat_invoice/print_invoice/'.$row['id'].'/'.$row['pasien_id'].'" class="btn default"><i class="fa fa-print"></i></a><a title="'.translate('View Invoice', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'reservasi/buat_invoice/print_invoice_old/'.$row['id'].'/'.$row['pasien_id'].'" class="btn btn-danger"><i class="fa fa-print"></i></a>';

            $invoice_detail = $this->invoice_detail_m->get_by(array('invoice_id' => $row['id'], 'is_active' => 1));
            $invoice_detail = object_to_array($invoice_detail);

            $total_invoice = 0;
            foreach ($invoice_detail as $inv_detail) {
                $total_invoice = $total_invoice + (($inv_detail['harga']*$inv_detail['qty']) - $inv_detail['diskon_nominal']);
            }

            $total_invoice = $total_invoice + $row['akomodasi'] - $row['diskon_nominal'] ;
            


            $output['data'][] = array(
                '<div class="text-center inline-button-table">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center inline-button-table">'.$row['waktu'].'</div>',
                '<div class="text-center inline-button-table">'.$noinvoice.'</div>',            
                '<div class="text-center inline-button-table">'.$jenis.'</div>',            
                '<div class="text-left inline-button-table">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_pasien'].'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_resepsionis'].'</div>',
                '<div class="text-right inline-button-table">'.formatrupiah($total_invoice).'</div>',
                '<div class="text-left inline-button-table" style="color:red;">'.$action.'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_pasien()
    {
        
        $result = $this->pasien_m->get_datatable_pilih_pasien();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        foreach($records->result_array() as $row)
        {
            
            $action = '';
            if($row['active']== 1)
            {
                if ($row['url_photo'] != '') 
                {
                    if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo'])) 
                    {
                        $row['url_photo'] = base_url().config_item('site_img_pasien').$row['no_ktp'].'/foto/'.$row['url_photo'];
                    }
                    else
                    {
                        $row['url_photo'] = base_url().config_item('site_img_pasien').'global/global_small.png';
                    }
                } else {

                    $row['url_photo'] = base_url().config_item('site_img_pasien').'global/global_small.png';
                }

                if($row['is_meninggal'] == 0)
                {
                    $action    = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                }

            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_ktp'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();
        $cabang_id = $this->session->userdata('cabang_id');
        $data_cabang = $this->cabang_m->get($cabang_id);
        if ($array_input['command'] === 'add')
        {  
            

            $biaya_total = 0;
            foreach ($array_input['item'] as $item) {
                if($item['id'] != '')
                {
                    $biaya_total = $biaya_total + intval($item['sub_total']);
                }
            }
            $biaya_total = $biaya_total + $array_input['akomodasi'];
            // die_dump($last_number);
            if($array_input['penanggung'] == 1)
            {
                $tipe_transaksi = 2;
                $biaya_tunai = 0;
                $biaya_klaim = $biaya_total;
            }
            if($array_input['penanggung'] == 2)
            {
                $tipe_transaksi = 1;
                $biaya_tunai = $biaya_total;
                $biaya_klaim = 0;
            }
            if($array_input['penanggung'] == 3)
            {
                $tipe_transaksi = 3;
                $biaya_tunai = $biaya_total;
                $biaya_klaim = 0;
            }

            $format_invoice = date('Ymd',strtotime($array_input['tanggal'])).' - '.'%06d';
            $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);

            $last_id_invoice       = $this->invoice_m->get_id_invoice()->result_array();
            $last_id_invoice       = intval($last_id_invoice[0]['max_id'])+1;
            
            $format_id_invoice     = 'IV-'.date('m').'-'.date('Y').'-%04d';
            $id_invoice = sprintf($format_id_invoice, $last_id_invoice, 4);


        
                $last_number_invoice  = $this->invoice_m->get_nomor_invoice()->result_array();
                if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
                {
                    $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
                }
                else
                {
                    $last_number_invoice = intval(1);
                }

                $format_invoice = date('Ymd').' - '.'%06d';
                $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);

                if($array_input['penanggung'] == 1)
                {
                    $nama_penjamin = 'BPJS - JKN';
                }
                if($array_input['penanggung'] == 2)
                {
                    $nama_penjamin = $array_input['nama_ref_pasien'];
                }

                $data_pembayaran_detail = array(
                    'id'           => $id_invoice,
                    'no_invoice'           => $no_invoice,
                    'waktu_tindakan'       => $array_input['waktu'],
                    'cabang_id'            => $this->session->userdata('cabang_id'),
                    'tipe_pasien'            => $array_input['tipe_pasien'],
                    'pasien_id'            => $array_input['id_ref_pasien'],
                    'nama_pasien'          => $array_input['nama_ref_pasien'],
                    'tipe'                 => 1,
                    'penjamin_id'          => $tipe_transaksi,
                    'nama_penjamin'        => $nama_penjamin,
                    'is_claim'             => 0,
                    'poliklinik_id'        => 1,
                    'nama_poliklinik'      => 'Poli HD',
                    'status'               => 1,
                    'shift'                => $array_input['tipe'],
                    'jenis_invoice'                => $array_input['jenis_invoice'],
                    'harga'                => $biaya_total,
                    'akomodasi'            => $array_input['akomodasi'],
                    'diskon'               => 0,
                    'harga_setelah_diskon' => $biaya_total,
                    'sisa_bayar' => $biaya_total,
                    'user_level_id' => $this->session->userdata('level_id'),
                    'is_active'            => 1
                );
                
                //$pembayaran_detail_id = $this->invoice_m->save($data_pembayaran_detail);

                $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                
                $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                $data_draft_tindakan = array(
                    'id'    => $id_draft,
                    'pasien_id'    => $array_input['id_ref_pasien'],
                    'tipe'  => 1,
                    'cabang_id'  => $this->session->userdata('cabang_id'),
                    'tipe_pasien'  => 1,
                    'nama_pasien'  => $array_input['nama_ref_pasien'],
                    'shift'  => $array_input['tipe'],
                    'user_level_id'  => $this->session->userdata('level_id'),
                    'jenis_invoice' => $array_input['jenis_invoice'],
                    'akomodasi'            => $array_input['akomodasi'],
                    'status'    => 1,
                    'is_active'    => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')
                );

                $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);

                foreach ($array_input['item'] as $item) {
                    if($item['id'] != '')
                    {
                        $data_detail_item = array(
                           'invoice_id' => $pembayaran_detail_id,
                           'item_id'              => $item['id'],
                           'satuan_id'              => $item['satuan_id'],
                           'qty'                  => $item['qty'],
                           'harga'                => $item['harga'],
                           'tipe'                 => $item['tipe'],
                           'tipe_item'            => 2,
                           'status'               => 1,
                           'is_active'            => 1
                        );

                        //$pembayaran_detail_item_id = $this->invoice_detail_m->save($data_detail_item);

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);
                    

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $id_draft,
                        'tipe_item' => 2,
                        'item_id'   => $item['id'],
                        'nama_tindakan' => $item['name'],
                        'harga_jual'    => $item['harga'],
                        'diskon_persen' => $item['diskon'],
                        'diskon_nominal' => (($item['diskon']/100)*($item['harga'] * $item['qty'])),
                        'status' => 1,
                        'jumlah' => $item['qty'],
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                        
                    }
                }

                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Invoce berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
                           
        } 
        if ($array_input['command'] === 'edit')
        {
            $id_detail  = $array_input['id'];

            $data_invoice = $this->invoice_m->get($id_detail);

            $bayar  = ($data_invoice->harga - $data_invoice->sisa_bayar);

            $biaya_total = 0;
            foreach ($array_input['item'] as $item) {
                if($item['id'] != '' && $item['is_deleted'] == 0)
                {
                    $biaya_total = $biaya_total + intval($item['harga']*$item['qty']);
                }
            }

            $biaya_total = $biaya_total + $array_input['akomodasi'];

            if($array_input['penanggung'] == 1)
            {
                $tipe_transaksi = 2;
                $biaya_tunai = 0;
                $biaya_klaim = $biaya_total;
            }
            if($array_input['penanggung'] == 2)
            {
                $tipe_transaksi = 1;
                $biaya_tunai = $biaya_total;
                $biaya_klaim = 0;
            }
            if($array_input['penanggung'] == 3)
            {
                $tipe_transaksi = 3;
                $biaya_tunai = $biaya_total;
                $biaya_klaim = 0;
            }

           
            if($array_input['penanggung'] == 1)
            {
                $nama_penjamin = 'BPJS - JKN';
            }
            if($array_input['penanggung'] == 2)
            {
                $nama_penjamin = $array_input['nama_ref_pasien'];
            }

            $data_pembayaran_detail = array(
                'created_date'       => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'no_invoice'       => $array_input['no_invoice'],
                'waktu_tindakan'       => $array_input['waktu'],
                'tipe_pasien'            => $array_input['tipe_pasien'],
                'pasien_id'            => $array_input['id_ref_pasien'],
                'nama_pasien'          => $array_input['nama_ref_pasien'],
                'shift'          => $array_input['tipe'],
                'tipe'                 => 1,
                'penjamin_id'          => $tipe_transaksi,
                'nama_penjamin'        => $nama_penjamin,
                'is_claim'             => 0,
                'poliklinik_id'        => 1,
                'nama_poliklinik'      => 'Poli HD',
                'status'               => 1,
                'harga'                => $biaya_total,
                'akomodasi'            => $array_input['akomodasi'],
                'jenis_invoice'                => $array_input['jenis_invoice'],
                'sisa_bayar'           => ($biaya_total-$bayar),
                'diskon'               => 0,
                'harga_setelah_diskon' => $biaya_total,
                'is_active'            => 1
            );
            
            $pembayaran_detail_id = $this->invoice_m->save($data_pembayaran_detail,$id_detail);

            foreach ($array_input['item'] as $item) {
                if($item['id_detail'] == '' && $item['id'] != '')
                {
                    $data_detail_item = array(
                       'invoice_id' => $pembayaran_detail_id,
                       'item_id'              => $item['id'],
                       'satuan_id'              => $item['satuan_id'],
                       'qty'                  => $item['qty'],
                       'harga'                => $item['harga'],
                       'tipe'                 => $item['tipe'],
                       'tipe_item'            => $item['tipe_item'],
                       'is_active'            => 1
                    );
                    $pembayaran_detail_item_id = $this->invoice_detail_m->save($data_detail_item);
                }

                if($item['id_detail'] != '' && $item['id'] != '' && $item['is_deleted'] == 0)
                {
                    $data_detail_item = array(
                       'item_id'              => $item['id'],
                       'qty'                  => $item['qty'],
                       'harga'                => $item['harga'],
                       'tipe'                 => $item['tipe'],
                       'tipe_item'            => $item['tipe_item'],
                       'is_active'            => 1
                    );
                    $pembayaran_detail_item_id = $this->invoice_detail_m->save($data_detail_item,$item['id_detail']);
                }

                if($item['id_detail'] != '' && $item['id'] != '' && $item['is_deleted'] == 1)
                {
                    $data_detail_item = array(
                       'is_active'            => 0
                    );
                    $pembayaran_detail_item_id = $this->invoice_detail_m->save($data_detail_item,$item['id_detail']);
                }
            }

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Invoce berhasil diubah.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            
        }
        redirect("reservasi/buat_invoice");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->pembayaran_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->max_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->max_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'  => 1,
            'data_id'    => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Invoice Sudah Dihapus", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("reservasi/buat_invoice");
    }

    public function restore($id)
    {      
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->pembayaran_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Invoice Sudah Direstore", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("reservasi/buat_invoice");
    }

    public function search_pasien_by_nomor()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Data Pasien Tidak Ditemukan', $this->session->userdata('language'));

            $no_pasien = $this->input->post('no_pasien');

            $pasien = $this->pasien_m->get_pasien_by_nomor($no_pasien)->row(0);

            if(count($pasien))
            {
                if ($pasien->url_photo != '') 
                {
                    if (file_exists(FCPATH.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo) && is_file(FCPATH.config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo)) 
                    {
                        $pasien->url_photo = base_url().config_item('site_img_pasien').$pasien->no_ktp.'/foto/'.$pasien->url_photo;
                    }
                    else
                    {
                        $pasien->url_photo = base_url().config_item('site_img_pasien').'global/global.png';
                    }
                } else {

                    $pasien->url_photo = base_url().config_item('site_img_pasien').'global/global.png';
                }


                $now = date('Y-m-d');
                $lahir = date('Y-m-d', strtotime($pasien->tanggal_lahir));

                $response->success = true;
                $response->msg = translate('Data Pasien Ditemukan', $this->session->userdata('language'));
                $response->rows = $pasien;
            }

            die(json_encode($response));

        }
    }

    public function listing_item()
    {
        $tanggal = date('Y-m-d');

        $result = $this->item_m->get_datatable_index_item($tanggal);
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));

        $i=0;
        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $id = $row['id'];
            $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $row['id']));
            $data_satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'],'is_primary' => 1), true);

            $harga_item = $this->item_harga_m->get_harga_item_satuan($row['id'],$data_satuan_primary->id)->row(0);
            $row['tipe_item'] = '2';

            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-id="'.$i.'" data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($data_satuan)).'" data-satuan_primary="'.htmlentities(json_encode($data_satuan_primary)).'" data-harga="'.htmlentities(json_encode($harga_item)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                $row['nama'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_paket($tipe=null)
    {
        $result = $this->paket_m->get_datatable();
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;
        $action = '';
        foreach($records->result_array() as $row)
        {
            $id = $row['id'];
            $row['tipe_item'] = '1';

            if($tipe != null){
                if($tipe == 2){
                    $row['harga_total'] = $row['harga_total'];
                }elseif($tipe == 1){
                    $row['harga_total'] = $row['harga_total'];
                }
            }

            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-satuan="{}" data-satuan_primary="{}" data-item="'.htmlentities(json_encode($row)).'" data-harga="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                $row['nama'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_tindakan()
    {
        $result = $this->tindakan_m->get_datatable();
        // die_dump($this->db->last_query());
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $action = '';
        foreach($records->result_array() as $row)
        {
            $id = $row['id'];

            $row['tipe_item'] = '3';

            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-satuan="{}" data-satuan_primary="{}" data-item="'.htmlentities(json_encode($row)).'" data-harga="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                $row['nama'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function print_invoice($id_invoice)
    {
        $this->load->library('mpdf/mpdf.php');

        $invoice = $this->invoice_m->get_by(array('id' => $id_invoice), true);
        $invoice->created_date = date('d-M-Y', strtotime($invoice->created_date));
        $pasien = $this->pasien_m->get_by(array('id' => $invoice->pasien_id), true); 
        
        $invoice_detail_tindakan = $this->invoice_detail_m->get_by("invoice_id = '$invoice->id' AND tipe_item = 1 OR invoice_id = '$invoice->id' AND tipe_item = 2");
        $invoice_detail_tindakan = object_to_array($invoice_detail_tindakan);

        $invoice_detail_item = $this->invoice_detail_m->get_by("invoice_id = '$invoice->id' AND tipe_item = 3");
        $invoice_detail_item = object_to_array($invoice_detail_item);

        $data_pendaftaran = $this->pendaftaran_tindakan_history_m->get_by(array('pasien_id' => $invoice->pasien_id, 'date(created_date)' => date('Y-m-d', strtotime($invoice->created_date))), true);
        $penjamin_id = 2;
        if(count($data_pendaftaran))
        {
            $penjamin_id = $data_pendaftaran->penjamin_id;
        }

        $data = array(
            'invoice' => object_to_array($invoice),
            'pasien' => object_to_array($pasien),
            'invoice_detail_tindakan' => object_to_array($invoice_detail_tindakan),
            'invoice_detail_item' => object_to_array($invoice_detail_item),
            'penjamin_id' => $penjamin_id
        );

        $mpdf = new mPDF('','A4', 0, 'dejavusans', 0, 0, 0, 0, 0, 0);
        $mpdf->writeHTML($this->load->view('reservasi/pembayaran/print_invoice', $data, true));
        

        $mpdf->Output('Invoice_'.$data_invoice->no_invoice.'.pdf', 'I'); 


    }

    public function print_invoice_rsud($id_invoice)
    {
        $this->load->library('mpdf/mpdf.php');

        $invoice = $this->invoice_m->get_by(array('id' => $id_invoice), true);
        $invoice->created_date = date('d-M-Y', strtotime($invoice->created_date));
        $pasien = $this->pasien_m->get_by(array('id' => $invoice->pasien_id), true); 
        
        $invoice_detail_tindakan = $this->invoice_detail_m->get_by("invoice_id = '$invoice->id' AND tipe_item = 1 OR invoice_id = '$invoice->id' AND tipe_item = 2");
        $invoice_detail_tindakan = object_to_array($invoice_detail_tindakan);

        $invoice_detail_item = array();

        $data_pendaftaran = $this->pendaftaran_tindakan_history_m->get_by(array('pasien_id' => $invoice->pasien_id, 'date(created_date)' => date('Y-m-d', strtotime($invoice->created_date))), true);
        $penjamin_id = 2;
        if(count($data_pendaftaran))
        {
            $penjamin_id = $data_pendaftaran->penjamin_id;
        }

        $data = array(
            'invoice' => object_to_array($invoice),
            'pasien' => object_to_array($pasien),
            'invoice_detail_tindakan' => object_to_array($invoice_detail_tindakan),
            'invoice_detail_item' => $invoice_detail_item,
            'penjamin_id' => $penjamin_id
        );

        $mpdf = new mPDF('','A4', 0, 'dejavusans', 0, 0, 0, 0, 0, 0);
        $mpdf->writeHTML($this->load->view('reservasi/buat_invoice/print_invoice_rsud', $data, true));
        

        $mpdf->Output('Invoice_'.$data_invoice->no_invoice.'.pdf', 'I'); 


    }

    public function print_invoice_old($id)
    {
        $this->load->library('mpdf/mpdf.php');

        $data_invoice = $this->invoice_m->get_by(array('id' => $id), true);

        if($data_invoice->user_level_id == NULL || $data_invoice->user_level_id == 0){
            if($this->session->userdata('level_id') == 19){
                $invoice['created_by'] = $this->session->userdata('user_id');
                $invoice['user_level_id'] = $this->session->userdata('level_id');
                $edit_invoice = $this->invoice_m->save($invoice,$id);
            }
    
        }

        $data_tindakan_hd = $this->tindakan_hd_history_m->get_by(array('id' => $data_invoice->tindakan_id), true);        
        $data_invoice_paket = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'tipe' => 1, 'tipe_item' => 1, 'is_active' => 1));
        $data_invoice_item = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'tipe' => 2, 'is_active' => 1));
        $data_invoice_items = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'tipe' => 2, 'item_id !=' => 8, 'is_active' => 1));
        $data_invoice_alat = $this->invoice_detail_m->get_by(array('invoice_id' => $id, 'tipe' => 3, 'is_active' => 1));
        $data_pasien = $this->pasien_m->get($data_invoice->pasien_id);
        $data_pendaftaran = $this->pendaftaran_tindakan_history_m->get_by(array('pasien_id' => $pasien_id, 'date(created_date)' => date('Y-m-d', strtotime($data_invoice->created_date))), true);
        $penjamin_id = 2;
        if(count($data_pendaftaran))
        {
            $penjamin_id = $data_pendaftaran->penjamin_id;
        }


        $data = array(
            'tindakan_hd'       => object_to_array($data_tindakan_hd),
            'invoice'       => object_to_array($data_invoice),
            'invoice_paket' => (count($data_invoice_paket) != 0)?object_to_array($data_invoice_paket):'',
            'invoice_item'  => (count($data_invoice_item) != 0)?object_to_array($data_invoice_item):'',
            'invoice_items' => (count($data_invoice_items) != 0)?object_to_array($data_invoice_items):'',
            'invoice_alat'  => (count($data_invoice_alat) != 0)?object_to_array($data_invoice_alat):'',
            'pasien'        => object_to_array($data_pasien),
            'penjamin_id'   => $penjamin_id,
        );

        $mpdf = new mPDF('','A4', 0, 'dejavusans', 0, 0, 0, 0, 0, 0);
        $mpdf->writeHTML($this->load->view('reservasi/buat_invoice/print_invoice', $data, true));
        

        $mpdf->Output('Invoice_'.$data_invoice->no_invoice.'.pdf', 'I'); 
    }

    public function get_harga()
    {
        if($this->input->is_ajax_request()){
            $item_id = $this->input->post('item_id');
            $item_satuan_id = $this->input->post('item_satuan_id');

            $response = new stdClass;
            $response->success = false;

            $harga_item = $this->item_harga_m->get_harga_item_satuan($item_id,$item_satuan_id)->row(0);
            if(count($harga_item) != 0){
                $response->success = true;
                $response->harga = $harga_item->harga;
            }

            die(json_encode($response));

        }
    }

}

/* End of file buat_invoice.php */
/* Location: ./application/controllers/reservasi/buat_invoice.php */