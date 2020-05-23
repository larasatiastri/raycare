<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setoran_kasir extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'ea1209ba6af2f1a5b6d1f186f19bd734';                  // untuk check bit_access

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
        $this->load->model('keuangan/pembayaran_masuk/pembayaran_masuk_status_m');        
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/kasir/setoran_kasir/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Setoran Kasir', $this->session->userdata('language')), 
            'header'         => translate('Setoran Kasir', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'kasir/setoran_kasir/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing()
    {

        $result = $this->setoran_kasir_m->get_datatable();
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
            $notes = '';

            $notes = explode("\n", $row['keterangan']);
            $notes = implode("<br>", $notes);

            $jenis_bayar = 'Tunai';
            if($row['jenis_bayar'] == 2){
                $jenis_bayar = 'Mesin EDC';
            }

            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'kasir/setoran_kasir/view/'.$row['id'].'" name="view[]" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
            
            $output['aaData'][] = array(
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_setor'])).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['shift'].'</div>',
                '<div class="text-left">'.$jenis_bayar.'</div>',
                '<div class="text-left">'.$row['nob'].'</div>',
                '<div class="text-left">'.$row['nama_resepsionis'].'</div>',
                '<div class="text-left">'.$row['nomor_bukti_setor'].'</div>',
                '<div class="text-right">'.formatrupiah($row['total']).'</div>',
                '<div class="text-left">'.$notes.'</div>',
                '<div class="text-left">'.$action.'</div>',
            );
        }

        echo json_encode($output);

    }


    public function listing_invoice($tipe,$tgl,$shift=null)
    {

        $tgl = date('Y-m-d', strtotime($tgl));

        $result = $this->pembayaran_tipe_m->get_datatable($tipe,$tgl,$shift,1);
        // die_dump($result);

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
       //die_dump($records);
        $count = count($records->result_array());

        $i=0;
        $total_debit = 0;
        $total_kradd = 0;
        $total_saldo = 0;

        foreach($records->result_array() as $row)
        {
            $i++;

            $bayar_tipe = $this->pembayaran_tipe_m->get_data_mesin($row['pembayaran_id'])->result_array();

            $keterangan = '';
            $x = 1;
            foreach ($bayar_tipe as $tipe) {
                $nama = 'Tunai';
                if($tipe['nama'] != NULL){
                    $nama = $tipe['nama'];
                }
                $keterangan .= $x.'. '.$nama.' : '.formatrupiah($tipe['rupiah'])."\n";
                $x++;
            }


            $output['aaData'][] = array(
                '<div class="text-left">'.substr($row['no_invoice'], 12).'<input type="hidden" name="invoice['.$i.'][pembayaran_id]" id="invoice_pembayaran_id_'.$i.'" value="'.$row['pembayaran_id'].'"><input type="hidden" name="invoice['.$i.'][invoice_id]" id="invoice_invoice_id_'.$i.'" value="'.$row['invoice_id'].'"></div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).' Shift '.$row['shift_inv'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_bayar'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($row['rupiah']).'</div>',
                '<div class="text-right">'.formatrupiah($row['rupiah']).'<input type="hidden" name="invoice['.$i.'][total_bayar]" id="invoice_pembayaran_total_'.$i.'" value="'.$row['rupiah'].'"></div>',
                '<div class="text-left">'.$keterangan.'</div>',
            );
        }

        echo json_encode($output);

    }

    public function listing_invoice_non($tipe,$tgl,$shift=null)
    {

        $tgl = date('Y-m-d', strtotime($tgl));

        $result = $this->pembayaran_tipe_m->get_datatable_non($tipe,$tgl,$shift,1);
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

        $y=0;
        $total_debit = 0;
        $total_kradd = 0;
        $total_saldo = 0;

        foreach($records->result_array() as $row)
        {

            $bayar_tipe = $this->pembayaran_tipe_m->get_data_mesin($row['pembayaran_id'])->result_array();

            $keterangan = '';
            $x = 1;
            foreach ($bayar_tipe as $tipe) {
                $nama = 'Tunai';
                if($tipe['nama'] != NULL){
                    $nama = $tipe['nama'];
                }
                $keterangan .= $x.'. '.$nama.' : '.formatrupiah($tipe['rupiah'])."\n";
                $x++;
            }


            $output['aaData'][] = array(
                '<div class="text-left">'.substr($row['no_invoice'], 12).'<input type="hidden" name="invoice_non['.$y.'][pembayaran_id]" id="invoice_pembayaran_id_'.$y.'" value="'.$row['pembayaran_id'].'"><input type="hidden" name="invoice_non['.$y.'][invoice_id]" id="invoice_invoice_id_'.$y.'" value="'.$row['invoice_id'].'"></div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).' Shift '.$row['shift_inv'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_bayar'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($row['rupiah']).'</div>',
                '<div class="text-right">'.formatrupiah($row['rupiah']).'<input type="hidden" name="invoice_non['.$y.'][total_bayar_non]" id="invoice_pembayaran_total_'.$y.'" value="'.$row['rupiah'].'"></div>',
                '<div class="text-left">'.$keterangan.'</div>',
            );
            $y++;

        }

        echo json_encode($output);

    }

    public function listing_invoice_edc($bank_id,$tipe,$tgl,$shift=null)
    {

        $tgl = date('Y-m-d', strtotime($tgl));

        $result = $this->pembayaran_tipe_m->get_datatable_edc($bank_id,$tipe,$tgl,$shift,1);
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

            $bayar_tipe = $this->pembayaran_tipe_m->get_data_mesin($row['pembayaran_id'])->result_array();

            $keterangan = '';
            $x = 1;
            foreach ($bayar_tipe as $tipe) {
                $keterangan .= $x.'. '.$tipe['nama'].' : '.formatrupiah($tipe['rupiah'])."\n";
                $x++;
            }

            $btn_unggah_gbr        = '<div class="text-center"><button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'kasir/setoran_kasir/unggah_gambar/'.$row['pembayaran_id'].'/'.$row['id'].'" class="btn blue-chambray unggah-gambar name="tindakan[{0}][gambar]" title="Unggah Gambar"><i class="fa fa-image"></i></button></div>'; 
            
            $output['aaData'][] = array(
                '<div class="text-left">'.substr($row['no_invoice'], 12).'<input type="hidden" name="invoice_edc['.$i.'][pembayaran_id]" id="invoice_pembayaran_id_'.$i.'" value="'.$row['pembayaran_id'].'"><input type="hidden" name="invoice_edc['.$i.'][invoice_id]" id="invoice_invoice_id_'.$i.'" value="'.$row['invoice_id'].'"></div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_bayar'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga']).'</div>',
                '<div class="text-right">'.formatrupiah($row['rupiah']).'<input type="hidden" name="invoice_edc['.$i.'][total_bayar]" id="invoice_pembayaran_total_'.$i.'" value="'.$row['rupiah'].'"></div>',
                '<div class="text-right">'.$row['nama_mesin'].'</div><div id="item_row_'.$i.'"></div>',
                '<div class="text-right">'.$btn_unggah_gbr.'</div>',
            );
        }

        echo json_encode($output);

    }

    public function listing_invoice_edc_non($bank_id,$tipe,$tgl,$shift=null)
    {

        $tgl = date('Y-m-d', strtotime($tgl));

        $result = $this->pembayaran_tipe_m->get_datatable_edc_non($bank_id,$tipe,$tgl,$shift,1);
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

            $bayar_tipe = $this->pembayaran_tipe_m->get_data_mesin($row['pembayaran_id'])->result_array();

            $keterangan = '';
            $x = 1;
            foreach ($bayar_tipe as $tipe) {
                $keterangan .= $x.'. '.$tipe['nama'].' : '.formatrupiah($tipe['rupiah'])."\n";
                $x++;
            }

            $btn_unggah_gbr        = '<div class="text-center"><button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'kasir/setoran_kasir/unggah_gambar/'.$row['pembayaran_id'].'/'.$row['id'].'" class="btn blue-chambray unggah-gambar name="tindakan[{0}][gambar]" title="Unggah Gambar"><i class="fa fa-image"></i></button></div>'; 
            
            $output['aaData'][] = array(
                '<div class="text-left">'.substr($row['no_invoice'], 12).'<input type="hidden" name="invoice_edc_non['.$i.'][pembayaran_id]" id="invoice_pembayaran_id_'.$i.'" value="'.$row['pembayaran_id'].'"><input type="hidden" name="invoice_edc_non['.$i.'][invoice_id]" id="invoice_invoice_id_'.$i.'" value="'.$row['invoice_id'].'"></div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_invoice'])).'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_bayar'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga']).'</div>',
                '<div class="text-right">'.formatrupiah($row['rupiah']).'<input type="hidden" name="invoice_edc_non['.$i.'][total_bayar_non]" id="invoice_pembayaran_total_'.$i.'" value="'.$row['rupiah'].'"></div>',
                '<div class="text-right">'.$row['nama_mesin'].'</div><div id="item_row_'.$i.'"></div>',
                '<div class="text-right">'.$btn_unggah_gbr.'</div>',
            );
        }

        echo json_encode($output);

    }

    public function listing_invoice_view($tgl,$shift=null)
    {
        $tgl = str_replace('%20', ' ', $tgl);
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

            $pembayaran_detail = $this->pembayaran_detail_m->get_by(array('pembayaran_id' => $row['pembayaran_id']));
            $pembayaran_detail = object_to_array($pembayaran_detail);

            $data_paket_bayar = $this->invoice_detail_m->get_data_paket_sudah_bayar($row['invoice_id'])->result_array();
            $data_items_bayar = $this->invoice_detail_m->get_data_items_sudah_bayar($row['invoice_id'])->result_array();
            $data_tindakan_bayar = $this->invoice_detail_m->get_data_tindakan_sudah_bayar($row['invoice_id'])->result_array();
            
            $output['aaData'][] = array(
                '<div class="text-left">'.substr($row['no_invoice'], 12).'<input type="hidden" name="invoice['.$i.'][pembayaran_id]" id="invoice_pembayaran_id_'.$i.'" value="'.$row['pembayaran_id'].'"><input type="hidden" name="invoice['.$i.'][invoice_id]" id="invoice_invoice_id_'.$i.'" value="'.$row['invoice_id'].'"></div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal_invoice'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right">'.formatrupiah($row['harga']).'</div>',
                '<div class="text-right"><a class="detail_item_bayar" data-paket="'.htmlentities(json_encode($data_paket_bayar)).'" data-item="'.htmlentities(json_encode($data_items_bayar)).'" data-tindakan="'.htmlentities(json_encode($data_tindakan_bayar)).'">'.formatrupiah($row['total_bayar']).'</a><input type="hidden" name="invoice['.$i.'][total_bayar]" id="invoice_pembayaran_total_'.$i.'" value="'.$row['total_bayar'].'"></div>',
            );
        }

        echo json_encode($output);

    }

    public function listing_belum_bayar($tgl_awal=null,$tgl_akhir=null,$shift=null)
    {
       
        $result = $this->invoice_m->get_datatable_hutang($tgl_awal,$tgl_akhir,$shift);
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

       
        $i=0;
        foreach($records->result_array() as $row)
        {
            $i++;
            
            
            $output['aaData'][] = array(
                '<div class="text-center">'.substr($row['no_invoice'],12).'</div>',
                '<div class="text-center">'.date('d-M-Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-right"><a class="detail_item" >'.formatrupiah($row['harga']).'</a></div>',
                '<div class="text-right"><a class="detail_item_bayar">'.formatrupiah($row['harga'] - $row['sisa_bayar']).'</a></div>',
                '<div class="text-right"><a class="detail_item_hutang" >'.formatrupiah($row['sisa_bayar']).'</a></div>',

            );
        }

        echo json_encode($output);

    }

    public function add()
    {
        $assets = array();
        $config = 'assets/kasir/setoran_kasir/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate('Buat Setoran', $this->session->userdata('language')), 
            'header'         => translate('Buat Setoran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'kasir/setoran_kasir/add',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/kasir/setoran_kasir/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $data_setoran = $this->setoran_kasir_m->get_by(array('id' => $id), true);
        $data_setoran = object_to_array($data_setoran);

        $data = array(
            'title'          => config_item('site_name').' | '.translate('View Setoran', $this->session->userdata('language')), 
            'header'         => translate('View Setoran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'kasir/setoran_kasir/view',
            'form_data'      => $data_setoran,
            'pk_value'      => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function save()
    {
        $array_input       = $this->input->post();
        $command           = $array_input['command'];

        //die(dump($array_input));

        if($command == 'add'){

            $last_id = $this->setoran_kasir_m->get_max_id_setoran()->result_array();
            $last_id = intval($last_id[0]['max_id'])+1;

            $format_id   = 'ST-'.date('m').'-'.date('Y').'-%04d';
            $id_setoran       = sprintf($format_id, $last_id, 4);

            $new_filename = '';
            if($array_input['url_bukti_setor'] != '')
            {
                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/kasir/setoran_kasir/images/'.str_replace(' ', '_', $id_setoran);
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $array_input['url_bukti_setor'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $new_filename = $id_setoran.$extenstion;
                $real_file = str_replace(' ', '_', $id_setoran).'/'.$new_filename;

                copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_bukti_setoran').$real_file);
            }

            $data = array(
                'id'                => $id_setoran,
                'cabang_id'         => $this->session->userdata('cabang_id'),
                'tanggal_setor'     => date('Y-m-d H:i:s', strtotime($array_input['tanggal_setor'])),
                'tanggal'           => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'jenis_bayar'       => $array_input['jenis_bayar'],
                'shift'             => $array_input['tipe'],
                'bank_id'             => $array_input['bank_id'],
                'total'             => $array_input['total'],
                'nomor_bukti_setor' => $array_input['nomor_bukti_setor'],
                'url_bukti_setor'   => $new_filename,
                'status'   => 1,
                'keterangan'        => $array_input['keterangan'],
                'created_by'        => $this->session->userdata('user_id'),
                'created_date'      => date('Y-m-d H:i:s')
            );

            $setoran_id = $this->setoran_kasir_m->add_data($data);

            $last_id_bayar_masuk = $this->pembayaran_masuk_status_m->get_max_id_pembayaran()->result_array();
            $last_id_bayar_masuk = intval($last_id_bayar_masuk[0]['max_id'])+1;

            $format_id_detail_kirim   = 'PMS-'.date('m').'-'.date('Y').'-%04d';
            $id_bayar_masuk       = sprintf($format_id_detail_kirim, $last_id_bayar_masuk, 4);
            
            $data_pembayaran_masuk = array(
                'id' => $id_bayar_masuk,
                'transaksi_id' => $id_setoran,
                'transaksi_nomor' => 'Setoran tanggal '.date('d-m-Y', strtotime($array_input['tanggal'])).' shift '.$array_input['tipe'],
                'tipe_transaksi' => 1,
                'status' => 1,
                'status_keuangan' => 1,
                'user_level_id' => 5,
                'divisi' => 7,
                'nominal' => $array_input['total'],
                'waktu_akhir' => date('Y-m-d', strtotime('+7 days')),
                'is_active' => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s', strtotime($array_input['tanggal_setor'])),
            );

            $add_bayar_masuk = $this->pembayaran_masuk_status_m->add_data($data_pembayaran_masuk); 

            if($array_input['jenis_bayar'] == 1){
                $cabang_id = $this->session->userdata('cabang_id');
                $cabang = $this->cabang_m->get($cabang_id);

                $last_saldo = get_saldo($cabang->url, date('Y-m-d', strtotime($array_input['tanggal_setor'])));
                // $last_saldo_bank = get_saldo_bank($cabang->url, date('Y-m-d', strtotime($array_input['tanggal_setor'])),$array_input['bank_id']);
                
                // die(dump($last_saldo['after']));
                $saldo_before = 0;
                if(count($last_saldo['before']) != 0){
                    $saldo_before = $last_saldo['before'][0]['saldo'];
                }

                $data_arus_kas = array(
                    'tanggal'      => date('Y-m-d H:i:s', strtotime($array_input['tanggal_setor'])),
                    'tipe'         => 7,
                    'tipe_kasir'         => 1,
                    'keterangan'   => 'Setoran tanggal '.date('d-m-Y', strtotime($array_input['tanggal'])).' shift '.$array_input['tipe'],
                    'user_id'      => $this->session->userdata('user_id'),
                    'debit_credit' => 'C',
                    'rupiah'       => $array_input['total'],
                    'saldo'        => ($saldo_before - $array_input['total']),
                    'status'       => 1
                );

                $path_model = 'keuangan/kasir_arus_kas_m';
                $save_arus_kas = insert_data_api($data_arus_kas,$cabang->url,$path_model);
                $inserted_save_arus_kas = $save_arus_kas;
                
                $inserted_save_arus_kas = str_replace('"', '', $inserted_save_arus_kas);

                if(count($last_saldo['after']) != 0){
                    foreach ($last_saldo['after'] as $after) {
                        $data_arus_kas_after = array(
                            'saldo'        => ($after['saldo'] - $array_input['total']),
                        );

                        $path_model = 'keuangan/kasir_arus_kas_m';
                        $save_arus_kas = insert_data_api($data_arus_kas_after,$cabang->url,$path_model, $after['id']);
                        $inserted_save_arus_kas = $save_arus_kas;
                        
                        $inserted_save_arus_kas = str_replace('"', '', $inserted_save_arus_kas);
                    }
                }


                // $saldo_before_bank = 0;
                // if(count($last_saldo_bank['before']) != 0){
                //     $saldo_before_bank = $last_saldo_bank['before'][0]['saldo'];
                // }

                // $data_arus_kas_bank = array(
                //     'tanggal'      => date('Y-m-d H:i:s', strtotime($array_input['tanggal_setor'])),
                //     'tipe'         => 4,
                //     'keterangan'   => 'Terima dari Pembayaran Invoice Tgl '.date('d/m/Y', strtotime($array_input['tanggal'])).' Shift '.$array_input['tipe'],
                //     'user_id'      => $this->session->userdata('user_id'),
                //     'bank_id'      => 1,
                //     'debit_credit' => 'D',
                //     'rupiah'       => $array_input['total'],
                //     'saldo'        => ($saldo_before_bank + $array_input['total']),
                //     'status'       => 1
                // );

                // $path_model_bank = 'keuangan/arus_kas_bank/keuangan_arus_kas_m';
                // $save_arus_kas_bank = insert_data_api($data_arus_kas_bank,$cabang->url,$path_model_bank);
                // $inserted_save_arus_kas_bank = $save_arus_kas_bank;
                
                // $inserted_save_arus_kas_bank = str_replace('"', '', $inserted_save_arus_kas_bank);

                // if(count($last_saldo_bank['after']) != 0){
                //     foreach ($last_saldo_bank['after'] as $after_bank) {
                //         $data_arus_kas_bank_after = array(
                //             'saldo'        => ($after_bank['saldo'] + $array_input['total']),
                //         );

                //         $path_model = 'keuangan/arus_kas_bank/keuangan_arus_kas_m';
                //         $save_arus_kas = insert_data_api($data_arus_kas_bank_after,$cabang->url,$path_model_bank, $after_bank['id']);
                //         $inserted_save_arus_kas_bank = $save_arus_kas;
                        
                //         $inserted_save_arus_kas_bank = str_replace('"', '', $inserted_save_arus_kas_bank);
                //     }
                // } 
            }
            

            if($array_input['invoice']){
                foreach ($array_input['invoice'] as $inv) {
                    
                    $last_id_detail = $this->setoran_kasir_detail_m->get_max_id_setoran_detail()->result_array();
                    $last_id_detail = intval($last_id_detail[0]['max_id'])+1;

                    $format_id_detail   = 'STD-'.date('m').'-'.date('Y').'-%04d';
                    $id_setoran_detail  = sprintf($format_id_detail, $last_id_detail, 4);

                    $data_detail = array(
                        'id'            => $id_setoran_detail,
                        'setoran_id'    => $id_setoran,
                        'pembayaran_id' => $inv['pembayaran_id'],
                        'invoice_id'    => $inv['invoice_id'],
                        'jumlah'        => $inv['total_bayar'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'  => date('Y-m-d H:i:s')
                    );

                    $data_bayar['status'] = 2;
                    $pembayaran = $this->pembayaran_pasien_m->save($data_bayar, $inv['pembayaran_id']);

                    $setoran_detail_id = $this->setoran_kasir_detail_m->add_data($data_detail);

                }
            }
            if($array_input['invoice_non']){
                foreach ($array_input['invoice_non'] as $inv) {
                    
                    $last_id_detail = $this->setoran_kasir_detail_m->get_max_id_setoran_detail()->result_array();
                    $last_id_detail = intval($last_id_detail[0]['max_id'])+1;

                    $format_id_detail   = 'STD-'.date('m').'-'.date('Y').'-%04d';
                    $id_setoran_detail  = sprintf($format_id_detail, $last_id_detail, 4);

                    $data_detail = array(
                        'id'            => $id_setoran_detail,
                        'setoran_id'    => $id_setoran,
                        'pembayaran_id' => $inv['pembayaran_id'],
                        'invoice_id'    => $inv['invoice_id'],
                        'jumlah'        => $inv['total_bayar'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'  => date('Y-m-d H:i:s')
                    );

                    $data_bayar['status'] = 2;
                    $pembayaran = $this->pembayaran_pasien_m->save($data_bayar, $inv['pembayaran_id']);

                    $setoran_detail_id = $this->setoran_kasir_detail_m->add_data($data_detail);

                }
            }
            if($array_input['invoice_edc']){
                foreach ($array_input['invoice_edc'] as $inv) {
                    
                    $last_id_detail = $this->setoran_kasir_detail_m->get_max_id_setoran_detail()->result_array();
                    $last_id_detail = intval($last_id_detail[0]['max_id'])+1;

                    $format_id_detail   = 'STD-'.date('m').'-'.date('Y').'-%04d';
                    $id_setoran_detail  = sprintf($format_id_detail, $last_id_detail, 4);

                    $data_detail = array(
                        'id'            => $id_setoran_detail,
                        'setoran_id'    => $id_setoran,
                        'pembayaran_id' => $inv['pembayaran_id'],
                        'invoice_id'    => $inv['invoice_id'],
                        'jumlah'        => $inv['total_bayar'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'  => date('Y-m-d H:i:s')
                    );

                    $data_bayar['status'] = 2;
                    $pembayaran = $this->pembayaran_pasien_m->save($data_bayar, $inv['pembayaran_id']);

                    $setoran_detail_id = $this->setoran_kasir_detail_m->add_data($data_detail);

                }
            }
            if($array_input['invoice_edc_non']){
                foreach ($array_input['invoice_edc_non'] as $inv) {
                    
                    $last_id_detail = $this->setoran_kasir_detail_m->get_max_id_setoran_detail()->result_array();
                    $last_id_detail = intval($last_id_detail[0]['max_id'])+1;

                    $format_id_detail   = 'STD-'.date('m').'-'.date('Y').'-%04d';
                    $id_setoran_detail  = sprintf($format_id_detail, $last_id_detail, 4);

                    $data_detail = array(
                        'id'            => $id_setoran_detail,
                        'setoran_id'    => $id_setoran,
                        'pembayaran_id' => $inv['pembayaran_id'],
                        'invoice_id'    => $inv['invoice_id'],
                        'jumlah'        => $inv['total_bayar'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'  => date('Y-m-d H:i:s')
                    );

                    $data_bayar['status'] = 2;
                    $pembayaran = $this->pembayaran_pasien_m->save($data_bayar, $inv['pembayaran_id']);

                    $setoran_detail_id = $this->setoran_kasir_detail_m->add_data($data_detail);

                }
            }
        }

        redirect('kasir/setoran_kasir');
    }

    public function get_mesin_edc()
    {
       if($this->input->is_ajax_request()){
            $bank_id = $this->input->post('bank_id');   

            $response = new stdClass;
            $response->success = false;

            $data_mesin = $this->mesin_edc_m->get_by(array('bank_id' => $bank_id));

            if($data_mesin){
                $response->success = true;
                $response->data_mesin = $data_mesin;
            }

            die(json_encode($response));

       }
    }

    public function unggah_gambar($pembayaran_id, $pembayaran_tipe_id)
    {
        $data_bayar = $this->pembayaran_pasien_m->get_by(array('id' => $pembayaran_id), true);
        $data_bayar_tipe = $this->pembayaran_tipe_m->get_by(array('id' => $pembayaran_tipe_id), true);
        $data = array(
            'data_bayar'       => object_to_array($data_bayar),
            'data_bayar_tipe'           => object_to_array($data_bayar_tipe),
        );


        $this->load->view('kasir/setoran_kasir/upload_bukti_edc', $data);
    }

    public function save_upload_edc()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Bukti bayar edc gagal ditambahkan', $this->session->userdata('language'));

            $array_input = $this->input->post();

            $upload = $array_input['upload'];
            foreach ($upload as $row) 
            { 
                $path_dokumen = '../cloud/'.config_item('site_dir').'pages/reservasi/pembayaran/images/'.$array_input['pembayaran_id'].'/'.$array_input['pembayaran_tipe_id'];
                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

                $temp_filename = $row['url'];

                $convtofile = new SplFileInfo($temp_filename);
                $extenstion = ".".$convtofile->getExtension();

                $real_file = $array_input['pembayaran_id'].'/'.$array_input['pembayaran_tipe_id'].'/'.$row['url'];

                if (file_exists(FCPATH.config_item('user_img_temp_dir_new').$temp_filename) && is_file(FCPATH.config_item('user_img_temp_dir_new').$temp_filename)){
                     copy(config_item('base_dir').config_item('user_img_temp_dir_new').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_edc').$real_file);

                }
                
                $data = array(
                    'url_bukti_edc' => $row['url']
                );

                $pembayran_tipe_id = $this->pembayaran_tipe_m->save($data,$array_input['pembayaran_tipe_id']);
                if($pembayran_tipe_id)
                {
                    $response->success = true;
                    $response->msg = translate('Bukti bayar edc berhasil ditambahkan',$this->session->userdata('language'));
                }
            }

            die(json_encode($response));

        }
    }




}

/* End of file setoran_kasir.php */
/* Location: ./application/controllers/keuangan/setoran_kasir.php */