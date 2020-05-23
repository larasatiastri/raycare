<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_invoice extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd3587933c343ae79c7b948190054a6c2';                  // untuk check bit_access

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
        $this->load->model('master/paket_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('master/tarif_ina_m');
        $this->load->model('reservasi/invoice/invoice_m');
        $this->load->model('reservasi/invoice/invoice_detail_m');
        $this->load->model('reservasi/pembayaran/pembayaran_pasien_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_detail_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_detail_item_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_m');
        $this->load->model('reservasi/pembayaran/pembayaran_tipe_m');
        $this->load->model('reservasi/pembayaran/pembayaran_tindakan_pasien_m');
        $this->load->model('reservasi/pembayaran/pembayaran_obat_pasien_m');
        $this->load->model('reservasi/pembayaran/pembayaran_history_m');
        $this->load->model('reservasi/pembayaran/pembayaran_cetak_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/kasir/laporan_invoice/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Laporan Invoice', $this->session->userdata('language')), 
            'header'         => translate(' Laporan Invoice', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'kasir/laporan_invoice/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }
     
    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing($tgl_awal = null, $tgl_akhir = null, $user_id = null,  $shift = null, $penjamin = null)
    {   
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }
        
        $month = date('Y-m', strtotime($tgl_awal));
        $tarif = $this->tarif_ina_m->get_tarif($month)->row(0);
        $result = $this->invoice_m->get_datatable_laporan($tgl_awal,$tgl_akhir,$user_id,$shift,$penjamin);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $total_invoice=0;
        $total_invoice_ina=0;
        $i=0;
        $count = count($records->result_array());
        $input_total = '';
        $input_total_ina = '';
        foreach($records->result_array() as $row)
        {
            $i++;
            $tarif_ina = 0;
            if($row['penjamin_id'] != 1){
                $tarif_ina = $tarif->tarif;
            }
            $total_invoice_ina=$total_invoice_ina+$tarif_ina;

            $invoice_detail = $this->invoice_detail_m->get_by(array('invoice_id' => $row['id'], 'is_active' => 1));
            $invoice_detail = object_to_array($invoice_detail);

            $total_invoice_tindakan = 0;
            foreach ($invoice_detail as $inv_detail) {
                $total_invoice_tindakan = $total_invoice_tindakan + ($inv_detail['harga']*$inv_detail['qty']);
            }

            $total_invoice_tindakan = $total_invoice_tindakan + $row['akomodasi'];
            $total_invoice=$total_invoice+$total_invoice_tindakan;
            
            if($i == $count){
                $input_total='<input id="input_total" type="hidden" value='.$total_invoice.'>';
                $input_total_ina='<input id="input_total_ina" type="hidden" value='.$total_invoice_ina.'>';
            }

            $data_items = $invoice_detail;

            $output['data'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.$row['waktu'].'</div>',
                '<div class="text-center">'.substr($row['no_invoice'], 12).'</div>',
                '<div class="text-left">'.$row['nama_resepsionis'].'</div>',
                '<div class="text-left">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($tarif_ina).$input_total_ina.'</div>',
                '<div class="text-right"><a class="detail_item" data-item="'.htmlentities(json_encode($data_items)).'" >'.formatrupiah($total_invoice_tindakan).'</a>'.$input_total.'</div>',


            );

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

            $last_number  = $this->pembayaran_pasien_m->get_nomor_faktur()->result_array();

            if(count($last_number))
            {
                $last_number  = intval($last_number[0]['max_nomor_faktur'])+1;
            }
            else
            {
                $last_number = 1;
            }

            $format       = 'PYC-'.date('ym').'-%03d';
            $no_faktur    = sprintf($format, $last_number, 3);

            $data_pembayaran = array(
                'cabang_id'      => $this->session->userdata('cabang_id'),
                'no_pembayaran'  => $no_faktur,
                'pasien_id'      => $array_input['id_ref_pasien'],
                'nama_pasien'    => $array_input['nama_ref_pasien'],
                'tipe_pasien'    => 1,
                'kasir_id'       => $this->session->userdata('user_id'),
                'tanggal'        => date('Y-m-d H:i:s'),
                'tipe_transaksi' => $tipe_transaksi,
                'biaya_tunai'    => $biaya_tunai,
                'biaya_klaim'    => $biaya_klaim,
                'diskon'         => 0,
                'pph'            => 0,
                'pph'            => 0,
                'pembulatan'     => 0,
                'bayar_tunai'    => $biaya_tunai,
                'kembali'        => 0,
                'status'         => 1,
                'is_active'      => 1,
            ); 

            $pembayaran_id = $this->pembayaran_pasien_m->save($data_pembayaran);
                
            if ($pembayaran_id) 
            {
                $last_number_invoice  = $this->pembayaran_detail_m->get_nomor_invoice()->result_array();
                if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
                {
                    $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
                }
                else
                {
                    $last_number_invoice = intval(12696);
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
                    'no_invoice'           => $no_invoice,
                    'pembayaran_id'        => $pembayaran_id,
                    'waktu_tindakan'       => $array_input['waktu'],
                    'cabang_id'            => $this->session->userdata('cabang_id'),
                    'pasien_id'            => $array_input['id_ref_pasien'],
                    'nama_pasien'          => $array_input['nama_ref_pasien'],
                    'tipe'                 => 1,
                    'penjamin_id'          => $tipe_transaksi,
                    'nama_penjamin'        => $nama_penjamin,
                    'is_claim'             => 0,
                    'poliklinik_id'        => 1,
                    'nama_poliklinik'      => 'Poli HD',
                    'status'               => 1,
                    'harga'                => $biaya_total,
                    'diskon'               => 0,
                    'harga_setelah_diskon' => $biaya_total,
                    'is_active'            => 1
                );
                
                $pembayaran_detail_id = $this->pembayaran_detail_m->save($data_pembayaran_detail);

                foreach ($array_input['item'] as $item) {
                    if($item['id'] != '')
                    {
                        $data_detail_item = array(
                           'pembayaran_detail_id' => $pembayaran_detail_id,
                           'item_id'              => $item['id'],
                           'qty'                  => $item['qty'],
                           'harga'                => $item['harga'],
                           'tipe'                 => $item['tipe'],
                           'tipe_item'                 => $item['tipe_item'],
                           'is_active'            => 1
                        );
                        $pembayaran_detail_item_id = $this->pembayaran_detail_item_m->save($data_detail_item);
                    }
                }

                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Invoce berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }                
       
        } 
        if ($array_input['command'] === 'edit')
        {
            $id_bayar   = $array_input['pembayaran_id'];
            $id_detail  = $array_input['id'];

            $biaya_total = 0;
            foreach ($array_input['item'] as $item) {
                if($item['id'] != '' && $item['is_deleted'] == 0)
                {
                    $biaya_total = $biaya_total + intval($item['sub_total']);
                }
            }

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

            $data_pembayaran = array(
                'pasien_id'      => $array_input['id_ref_pasien'],
                'nama_pasien'    => $array_input['nama_ref_pasien'],
                'tipe_pasien'    => 1,
                'tipe_transaksi' => $tipe_transaksi,
                'biaya_tunai'    => $biaya_tunai,
                'biaya_klaim'    => $biaya_klaim,
                'diskon'         => 0,
                'pph'            => 0,
                'pph'            => 0,
                'pembulatan'     => 0,
                'bayar_tunai'    => $biaya_tunai,
                'kembali'        => 0,
                'status'         => 1,
                'is_active'      => 1,
            ); 

            $pembayaran_id = $this->pembayaran_pasien_m->save($data_pembayaran, $id_bayar);
            if($pembayaran_id)
            {
                if($array_input['penanggung'] == 1)
                {
                    $nama_penjamin = 'BPJS - JKN';
                }
                if($array_input['penanggung'] == 2)
                {
                    $nama_penjamin = $array_input['nama_ref_pasien'];
                }

                $data_pembayaran_detail = array(
                    'waktu_tindakan'       => $array_input['waktu'],
                    'pasien_id'            => $array_input['id_ref_pasien'],
                    'nama_pasien'          => $array_input['nama_ref_pasien'],
                    'tipe'                 => 1,
                    'penjamin_id'          => $tipe_transaksi,
                    'nama_penjamin'        => $nama_penjamin,
                    'is_claim'             => 0,
                    'poliklinik_id'        => 1,
                    'nama_poliklinik'      => 'Poli HD',
                    'status'               => 1,
                    'harga'                => $biaya_total,
                    'diskon'               => 0,
                    'harga_setelah_diskon' => $biaya_total,
                    'is_active'            => 1
                );
                
                $pembayaran_detail_id = $this->pembayaran_detail_m->save($data_pembayaran_detail,$id_detail);

                foreach ($array_input['item'] as $item) {
                    if($item['id_detail'] == '' && $item['id'] != '')
                    {
                        $data_detail_item = array(
                           'pembayaran_detail_id' => $pembayaran_detail_id,
                           'item_id'              => $item['id'],
                           'qty'                  => $item['qty'],
                           'harga'                => $item['harga'],
                           'tipe'                 => $item['tipe'],
                           'tipe_item'            => $item['tipe_item'],
                           'is_active'            => 1
                        );
                        $pembayaran_detail_item_id = $this->pembayaran_detail_item_m->save($data_detail_item);
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
                        $pembayaran_detail_item_id = $this->pembayaran_detail_item_m->save($data_detail_item,$item['id_detail']);
                    }

                    if($item['id_detail'] != '' && $item['id'] != '' && $item['is_deleted'] == 1)
                    {
                        $data_detail_item = array(
                           'is_active'            => 0
                        );
                        $pembayaran_detail_item_id = $this->pembayaran_detail_item_m->save($data_detail_item,$item['id_detail']);
                    }
                }

                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Invoce berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
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

            $row['tipe_item'] = '2';

            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                $row['nama'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_paket()
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

        $i=0;
        $action = '';
        foreach($records->result_array() as $row)
        {
            $id = $row['id'];
            $row['tipe_item'] = '1';

            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
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

            $action = '<a title="'.translate('Pilih', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
                 
                
            $output['data'][] = array(
                $id,
                $row['nama'],                
                '<div class="text-center inline-button">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function print_invoice($id, $pasien_id)
    {
        $this->load->library('mpdf/mpdf.php');

        $data_invoice = $this->pembayaran_detail_m->get($id);
        $data_invoice_paket = $this->pembayaran_detail_item_m->get_by(array('pembayaran_detail_id' => $id, 'tipe' => 1, 'tipe_item' => 1));
        $data_invoice_item = $this->pembayaran_detail_item_m->get_by(array('pembayaran_detail_id' => $id, 'tipe' => 2));
        $data_invoice_items = $this->pembayaran_detail_item_m->get_by(array('pembayaran_detail_id' => $id, 'tipe' => 2, 'item_id !=' => 8 ));
        $data_invoice_alat = $this->pembayaran_detail_item_m->get_by(array('pembayaran_detail_id' => $id, 'tipe' => 3));
        $data_pasien = $this->pasien_m->get($pasien_id);
        $data_pendaftaran = $this->pendaftaran_tindakan_m->get_by(array('pasien_id' => $pasien_id, 'date(created_date)' => date('Y-m-d', strtotime($data_invoice->created_date))), true);
        $penjamin_id = 2;
        if(count($data_pendaftaran))
        {
            $penjamin_id = $data_pendaftaran->penjamin_id;
        }


        $data = array(
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

}

/* End of file buat_invoice.php */
/* Location: ./application/controllers/reservasi/buat_invoice.php */