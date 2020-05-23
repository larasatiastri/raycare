<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pembayaran extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '4df894259b4d7ac0e9c1a3edb83ed18d';                  // untuk check bit_access

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

        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_poliklinik_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/paket_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/paket_item_m');
        $this->load->model('master/paket_tindakan_m');
        $this->load->model('master/paket_batch_m');
        $this->load->model('master/paket_batch_item_m');
        $this->load->model('master/paket_batch_tindakan_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/poliklinik_paket_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_klaim_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/user_m');
        $this->load->model('master/user_level_m');
        $this->load->model('master/bank_m');
        $this->load->model('master/mesin_edc_m');
        $this->load->model('master/pasien_penjamin_m');
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
        $this->load->model('global/rm_transaksi_pasien_m');
        $this->load->model('reservasi/invoice/draf_invoice_m');   
        $this->load->model('reservasi/invoice/draf_invoice_detail_m');       
        $this->load->library('mpdf/mpdf.php');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_history_m');
        $this->load->model('klinik_umum/pasien_tindakan_m');
        $this->load->model('klinik_umum/pasien_tindakan_history_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_history_m');
        $this->load->model('master/pasien_penjamin_m');
        $this->load->model('reservasi/antrian/antrian_pasien_m');  

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/reservasi/pembayaran/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $user_id = $this->session->userdata('user_level_id');
        $user_login = $this->user_m->get_by(array('user_level_id' => $user_id), true);

        $data_antrian = $this->antrian_pasien_m->get_data_loket_panggil(6)->row(0);
        $list_antrian = $this->antrian_pasien_m->get_data_loket(6)->result_array();
        

        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Pembayaran', $this->session->userdata('language')), 
            'header'         => translate('Pembayaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'data_antrian'   => object_to_array($data_antrian),
            'list_antrian'   => object_to_array($list_antrian),
            'content_view'   => 'reservasi/pembayaran/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function batch($id)
    {
        $assets = array();
        $config = 'assets/master/paket/batch';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));

        $data_paket = $this->paket_m->get($id);
        // die_dump($data_paket);

        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Batch', $this->session->userdata('language')), 
            'header'         => translate('Master Batch', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/paket/batch',
            'data_paket'     => object_to_array($data_paket),
            'pk'             => $id,
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/paket/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). translate("Tambah Paket", $this->session->userdata("language")), 
            'header'         => translate("Tambah Paket", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/paket/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add_batch($id)
    {
        $assets = array();
        $assets_config = 'assets/master/paket/add_batch';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->paket_m->get($id);
        // die_dump($form_data);

        $data = array(
            'title'          => config_item('site_name'). translate("Tambah Paket Batch", $this->session->userdata("language")), 
            'header'         => translate("Tambah Paket Batch", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/paket/add_batch',
            'form_data'      => object_to_array($form_data),
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/master/paket/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $paket_data = $this->paket_m->get($id);
        $tipe       = object_to_array($paket_data);
        // die_dump($paket_data);

        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => 'RayCare &gt;'.translate('Paket', $this->session->userdata('language')), 
            'header'         => translate('View Paket', $this->session->userdata('language')), 
            'header_info'    => 'RayCare', 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'paket_data'       => object_to_array($paket_data),
            'content_view'   => 'master/paket/view',
            'pk_value'      => $id
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function view_batch($id)
    {
        $assets = array();
        $config = 'assets/master/paket/view_batch';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
         
        $paket_batch = $this->paket_batch_m->get($id);
        $tipe       = object_to_array($paket_batch);
        // die_dump($paket_batch);

        // die(dump( $assets['css'] ));

        $data = array(
            'title'          => 'RayCare &gt;'.translate('Paket', $this->session->userdata('language')), 
            'header'         => translate('View Paket', $this->session->userdata('language')), 
            'header_info'    => 'RayCare', 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'paket_batch'    => object_to_array($paket_batch),
            'content_view'   => 'master/paket/view_batch',
            'pk_value'       => $id
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/paket/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $this->paket_m->set_columns($this->paket_m->fillable_edit());
       
        $form_data = $this->paket_m->get($id);
        $data_paket_item = $this->paket_item_m->get_by(array('paket_id' => $id ));
        $data_paket_tindakan = $this->paket_tindakan_m->get_by(array('paket_id' => $id ));
        $data_poliklinik_paket = $this->poliklinik_paket_m->get_by(array('paket_id' => $id ));
        // die_dump($data_poliklinik_paket);

        $data = array(
            'title'          => config_item('site_name'). translate("Edit Paket", $this->session->userdata("language")), 
            'header'         => translate("Edit Paket", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/paket/edit',
            'form_data'      => object_to_array($form_data),
            'data_paket_item'      => object_to_array($data_paket_item),
            'data_paket_tindakan'      => object_to_array($data_paket_tindakan),
            'data_poliklinik_paket'      => object_to_array($data_poliklinik_paket),
            'pk_value'       => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    public function edit_batch($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/paket/edit_batch';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->paket_batch_m->set_columns($this->paket_m->fillable_edit());
       
        $form_data = $this->paket_batch_m->get($id);
        // die_dump($form_data);
        $data_paket_item = $this->paket_batch_item_m->get_by(array('paket_batch_id' => $id ));
        $data_paket_tindakan = $this->paket_batch_tindakan_m->get_by(array('paket_batch_id' => $id ));
        // die_dump($data_paket_item);

        $data = array(
            'title'                 => config_item('site_name'). translate("Edit Paket Batch", $this->session->userdata("language")), 
            'header'                => translate("Edit Paket Batch", $this->session->userdata("language")), 
            'header_info'           => config_item('site_name'), 
            'breadcrumb'            => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'             => $assets['css'],
            'js_files'              => $assets['js'],
            'content_view'          => 'master/paket/edit_batch',
            'form_data'             => object_to_array($form_data),
            'data_paket_item'       => object_to_array($data_paket_item),
            'data_paket_tindakan'   => object_to_array($data_paket_tindakan),
            'pk_value'              => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }


    /**
     * [list description]
     * @return [type] [description]
     */

    public function change_order()
    {
        if(! $this->input->is_ajax_request()) redirect(base_url());

        $paket_id = $this->input->post('paket_id');
        $id = $this->input->post('id');
        $order  = intval($this->input->post('order'));
        $command = $this->input->post('command');

        if($command == 'up')
            $this->paket_batch_m->up_order($paket_id,$id, $order);    
        else
            $this->paket_batch_m->down_order($paket_id,$id, $order);
        
    }

    public function listing_pilih_pasien()
    {
        
        $result = $this->pasien_m->get_datatable_pilih_pasien_all(1);

        // die_dump($this->db->last_query());
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

            }

            $transaksi = $this->rm_transaksi_pasien_m->get_by(array('pasien_id' => $row['id']));
            $tagihan = $this->invoice_m->get_data_tagihan($row['id']);

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-transaksi="'.count($transaksi).'" data-tagihan="'.count($tagihan).'" data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select-pasien"><i class="fa fa-check"></i></a>';


            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_ktp'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-left">'.$row['alamat'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    
    public function listing()
    {        
        $result = $this->paket_m->get_datatable();
        // die_dump($result);
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
            $action = '';
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/paket/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>
                       <a title="'.translate('Batch', $this->session->userdata('language')).'" href="'.base_url().'master/paket/batch/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-list"></i></a>
                       <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/paket/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>
                       <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data Paket ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
           

            $output['data'][] = array(
                $row['kode'],
                $row['nama'],
                '<div class="text-center">'.$this->formatrupiah($row['harga_total']).'</div>',
                $row['keterangan'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_invoice()
    {        
        $result = $this->invoice_m->get_datatable_belum_setor();
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die_dump($records);

        $i=0;
        foreach($records->result_array() as $row)
        {
            $status = '';
            $action = '';
            $noinvoice =$row['no_invoice'];
            
            
            $action .='
                <a title="'.translate('Print Invoice', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'reservasi/pembayaran/print_invoice/'.$row['id'].'/'.$row['pasien_id'].'" class="btn default"><i class="fa fa-print"></i></a>
                <a title="'.translate('Print Invoice Dot Metric', $this->session->userdata('language')).'" target="_blank" name="print_invoice" href="'.base_url().'reservasi/pembayaran/print_invoice_dot/'.$row['id'].'/'.$row['pasien_id'].'" class="btn blue"><i class="fa fa-list"></i></a>';
            
            $invoice_detail = $this->invoice_detail_m->get_by(array('invoice_id' => $row['id'], 'is_active' => 1));
            $invoice_detail = object_to_array($invoice_detail);

            $total_invoice = 0;
            foreach ($invoice_detail as $inv_detail) {
                $total_invoice = $total_invoice + ($inv_detail['harga']*$inv_detail['qty']);
            }

            $total_invoice = $total_invoice + $row['akomodasi'];


            $output['data'][] = array(
                '<div class="text-center inline-button-table">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center inline-button-table">'.$noinvoice.'</div>',            
                '<div class="text-left inline-button-table">'.$row['nama_penjamin'].'</div>',
                '<div class="text-left inline-button-table">'.$row['nama_pasien'].'</div>',
                '<div class="text-right inline-button-table">'.formatrupiah($total_invoice).'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_alat_obat($cabang_id = null, $status_so_history = null)
    {
        //  $this->load->model('finance/account_categories_m');
        $result = $this->item_m->get_datatable($cabang_id, $status_so_history);
        // die(dump($result));
        // die(dump($this->db->last_query()));

  
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        // die_dump($records);
        $i = 0;
        foreach($records->result_array() as $row)
        {

            $kategori = '';
            if ($row['item_kategori_id'] == 1)
                $kategori = '<div class="text-center">'.$row['kategori_item'].'</div>';
            if ($row['item_kategori_id'] == 2)
                $kategori = '<div class="text-center">'.$row['kategori_item'].'</div>';
                // $kategori = $row['kategori_item'];

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
             
             $output['aaData'][] = array(
                $row['kode'],
                $row['nama'],
                $row['unit'],
                '<div class="text-center">'.$this->formatrupiah($row['harga']).'</div>',
                '<div class="text-center">'.$kategori.'</div>',
                // $row['kategori_item'],
                $row['keterangan'],
                '<div class="text-center">'.$action.'</div>'
               );             
         $i++;
        }

       // die(dump($this->db->last_query()));


      echo json_encode($output);

    }

    public function listing_tindakan()
    {
        //  $this->load->model('finance/account_categories_m');
        $result = $this->tindakan_m->get_datatable();
        // die(dump($this->db->last_query()));     
  
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        
        foreach($records->result_array() as $row)
        {
            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select-tindakan"><i class="fa fa-check"></i></a>';
             $output['aaData'][] = array(
                $row['kode'],
                $row['nama'],
                '<div class="text-center">'.$this->formatrupiah($row['harga']).'</div>',
                $row['keterangan'],
                '<div class="text-center">'.$action.'</div>'
               );             
        }

       // die(dump($this->db->last_query()));


      echo json_encode($output);

    }

    public function listing_paket_item($so_id)
    {
        
        $result = $this->paket_item_m->get_datatable_view($so_id);

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

        $subtotal = 0;


          $i = 0;
          $count = count($records->result_array());
          $inputSubtotal = '';
          $status = '';

        foreach($records->result_array() as $row)
        {        
            $i++;        

            $subtotal = $subtotal + ($row['jumlah'] * $row['harga']);

            if ($i == $count) 
            {
                $inputSubtotal = '<input type="hidden" id="subtotal" name="subtotal" value="'.$subtotal.'">';

            }

            if ($row['is_sale'] == 1)
            {
                $status = 'Ya';
            } else {
                $status = 'Tidak';
            }

            $output['aaData'][] = array(
                    '<div class="text-center">'.$row['kode'].'</div>',
                    '<div class="text-center">'.$row['nama_item'].'</div>',
                    '<div class="text-center">'.$status.'</div>',
                    '<div class="text-center">'.$row['jumlah'].'</div>',
                    '<div class="text-center">'.$row['nama_satuan'].'</div>',
                    '<div class="text-center">'.'Rp.' . number_format($row['harga'],0,'','.').'</div>',
                    '<div class="text-center">'.'Rp.' . number_format($subtotal,0,'','.'). ',-'.$inputSubtotal.'</div>',
                   );                      

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function listing_daftar_tindakan($pasien_id = 0)
    {
        
        $result = $this->invoice_m->get_datatable_bayar($pasien_id);

        // die_dump($this->db->last_query());   
        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        foreach($records->result_array() as $row)
        {        

            $output['aaData'][] = array(
                '<div class="text-center" '.$style.'>'.substr($row['no_invoice'], 12).'</div>',
                '<div class="text-center" '.$style.'>HD</div>',
                '<div class="text-center" '.$style.'>'.$row['nama_cabang'].'</div>',
                '<div class="text-center" '.$style.'>'.$row['nama_penjamin'].'</div>',
                '<div class="text-right" '.$style.'>'.formatrupiah($row['harga']).'</div>',
                '<div class="text-right" '.$style.'><a class="detail_invoice" data-toggle="modal" data-target="#modal_detail" href="'.base_url().'reservasi/pembayaran/modal_detail/'.$row['id'].'">'.formatrupiah($row['sisa_bayar']).'</a></div>',        
            );    
            $i++;        

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function get_data_invoice()
    {
        if($this->input->is_ajax_request()){
            $pasien_id = $this->input->post('pasien_id');

            $data_invoice = $this->invoice_m->get_data_invoice($pasien_id)->result_array();
            
            $html = '';
            $i = 0;
            if(count($data_invoice)){
                $html = '';
                $i = 0;
                foreach ($data_invoice as $key => $row) {
                    $html .= '<tr id="item_row_'.$i.'">';
                    $html .= '<td><div class="text-center"><input type="radio" name="select_invoice" data-index="'.$i.'" id="invoice_select_radio_'.$i.'" value="'.$row['id'].'" data-rp="'.$row['sisa_bayar'].'" required><input type="hidden" name="invoice['.$i.'][select]" id="invoice_select_'.$i.'" value="" class="selected_radio"></div></td>';
                    $html .= '<td><div class="text-center"><input type="hidden" name="invoice['.$i.'][id]" id="invoice_id_'.$i.'" value="'.$row['id'].'"><input type="hidden" name="invoice['.$i.'][jenis_invoice]" id="invoice_jenis_invoice_'.$i.'" value="'.$row['jenis_invoice'].'"><input type="hidden" name="invoice['.$i.'][akomodasi]" id="invoice_akomodasi_'.$i.'" value="'.$row['akomodasi'].'"><input type="hidden" name="invoice['.$i.'][harga_invoice]" id="invoice_harga_invoice_'.$i.'" value="'.$row['sisa_bayar'].'"><input type="hidden" name="invoice['.$i.'][total_invoice]" id="invoice_total_invoice_'.$i.'" value="'.$row['sisa_bayar'].'" ><input type="hidden" name="invoice['.$i.'][no_invoice]" id="invoice_no_invoice_'.$i.'" value="'.$row['no_invoice'].'" >'.substr($row['no_invoice'], 12).'</div></td>';
                    $html .= '<td><div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div></td>';
                    $html .= '<td><div class="text-center">HD<div id="temporary_data" class="hidden"></div></td>';
                    $html .= '<td><div class="text-center">'.$row['nama_cabang'].'</div></td>';
                    $html .= '<td><div class="text-center">'.$row['nama_penjamin'].'</div></td>';
                    $html .= '<td><div class="text-right">'.formatrupiah($row['harga']).'</div></td>';
                    $html .= '</tr>';
                    $i++;
                }
            }else{
                $html .= '<tr id="item_row_'.$i.'">';
                $html .= '<td colspan="7" class="text-center">'.translate('No data available in table', $this->session->userdata('language')).'<td>';
                $html .= '</tr>';
            }

            echo $html;   
        }
    }

    public function get_data_invoice_nama()
    {
        if($this->input->is_ajax_request()){
            $nama_pasien = $this->input->post('nama_pasien');

            $tanggal = $this->input->post('tanggal');
            $tanggal = date('Y-m-d', strtotime($tanggal));

            $data_invoice = $this->invoice_m->get_data_invoice_nama($nama_pasien, $tanggal)->result_array();
            //die(dump($this->db->last_query()));
            $html = '';
            $i = 0;
            if(count($data_invoice)){
                $html = '';
                $i = 0;
                foreach ($data_invoice as $key => $row) {
                    $html .= '<tr id="item_row_'.$i.'">';
                    $html .= '<td><div class="text-center"><input type="radio" name="select_invoice" data-index="'.$i.'" id="invoice_select_radio_'.$i.'" value="'.$row['id'].'" data-rp="'.$row['sisa_bayar'].'" required><input type="hidden" name="invoice['.$i.'][select]" id="invoice_select_'.$i.'" value="" class="selected_radio"></div></td>';
                    $html .= '<td><div class="text-center"><input type="hidden" name="invoice['.$i.'][id]" id="invoice_id_'.$i.'" value="'.$row['id'].'"><input type="hidden" name="invoice['.$i.'][jenis_invoice]" id="invoice_jenis_invoice_'.$i.'" value="'.$row['jenis_invoice'].'"><input type="hidden" name="invoice['.$i.'][akomodasi]" id="invoice_akomodasi_'.$i.'" value="'.$row['akomodasi'].'"><input type="hidden" name="invoice['.$i.'][harga_invoice]" id="invoice_harga_invoice_'.$i.'" ><input type="hidden" name="invoice['.$i.'][total_invoice]" id="invoice_total_invoice_'.$i.'" value="'.$row['sisa_bayar'].'" ><input type="hidden" name="invoice['.$i.'][no_invoice]" id="invoice_no_invoice_'.$i.'" value="'.$row['no_invoice'].'" >'.substr($row['no_invoice'], 12).'</div></td>';
                    $html .= '<td><div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div></td>';
                    $html .= '<td><div class="text-center">HD<div id="temporary_data" class="hidden"></div></td>';
                    $html .= '<td><div class="text-center">'.$row['nama_cabang'].'</div></td>';
                    $html .= '<td><div class="text-center">'.$row['nama_penjamin'].'</div></td>';
                    $html .= '<td><div class="text-right">'.formatrupiah($row['harga']).'</div></td>';
                   
                    $html .= '</tr>';
                    $i++;
                }
            }else{
                $html .= '<tr id="item_row_'.$i.'">';
                $html .= '<td colspan="7" class="text-center">'.translate('No data available in table', $this->session->userdata('language')).'<td>';
                $html .= '</tr>';
            }

            echo $html;   
        }
    }

    public function listing_daftar_obat()
    {
        
        $result = $this->os_pembayaran_obat_m->get_datatable();

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

          $i = 0;
          $subtotal = 0;
          $rupiah = '';
          $nama_tipe = '';
          $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {        

            $subtotal = $subtotal + $row['harga'] - ($row['harga'] * $row['diskon']/100);

            $in_tindakan_id     = '<input class="form-control hidden" id="tindakan_id" name="obat['.$i.'][tindakan_id]" value="'.$row['tindakan_id'].'">';
            $in_klaim           = '<input class="form-control hidden" id="klaim" name="obat['.$i.'][klaim]" value="'.$row['penjamin_id'].'">';
            $in_count           = '<input class="form-control hidden" id="count_temp" value="'.$i.'">';
            $in_harga           = '<input class="form-control hidden" id="harga" name="obat['.$i.'][harga]" value="'.$row['harga'].'">';
            $in_diskon          = '<input class="form-control hidden" id="diskon" name="obat['.$i.'][diskon]" value="'.$row['diskon'].'">';
            $in_subtotal        = '<input class="form-control hidden" id="in_subtotal_obat" name="obat['.$i.'][in_subtotal_obat]" value="'.$subtotal.'">';
            $in_jumlah          = '<input class="form-control hidden" id="in_jumlah" name="obat['.$i.'][in_jumlah]" value="'.$row['jumlah'].'">';
            $in_obat_id         = '<input class="form-control hidden" id="obat_id" name="obat['.$i.'][obat_id]" value="'.$row['item_id'].'">';
            $in_tipe_tindakan   = '<input class="form-control hidden" id="tipe_tindakan" name="obat['.$i.'][tipe_tindakan]" value="'.$row['tipe'].'">';
            $in_id              = '<input class="form-control hidden" id="id" name="obat['.$i.'][id]" value="'.$row['id'].'">';

            if ($row['tipe'] == 1) {
                $nama_tipe = 'HD';
            } elseif ($row['tipe'] == 2){

                $nama_tipe = 'Umum';
            } elseif ($row['tipe'] == 3){

                $nama_tipe = 'Mata';
            } elseif ($row['tipe'] == 4){

                $nama_tipe = 'Gigi';

            }


            $output['aaData'][] = array(
                    '<div class="text-center">'.$row['kode'].$in_tindakan_id.$in_id.'</div>',
                    '<div class="text-center">'.$row['nama_item'].$in_obat_id.'</div>',
                    '<div class="text-center">'.$row['nama_cabang'].$in_tipe_tindakan.'</div>',
                    // '<div class="text-center">'.$nama_tipe.'</div>',
                    '<div class="text-center">'.$row['nama_penjamin'].$in_klaim.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$in_jumlah.'</div>',
                    '<div class="text-center">'.'Rp.' . number_format($row['harga'],0,'','.'). ',-'.$in_harga.'</div>',
                    '<div class="text-center">'.$row['diskon'].$in_diskon.'</div>',
                    '<div class="text-center">'.'Rp.' . number_format($subtotal,0,'','.'). ',-'.$in_subtotal.'</div>',
                    // '<div class="text-center">'.$in_jumlah.'</div>',
                    // '<div class="text-center">'.$in_satuan.'</div>',
                    ($i == $count) ? '<div class="text-center"><input class="checkboxes" name="obat['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$subtotal.'" data-klaim="'.$row['nama_penjamin'].'">'.$in_count.'</div>' : '<div class="text-center"><input class="checkboxes" name="obat['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$subtotal.'" data-klaim="'.$row['nama_penjamin'].'"></div>',
                    
                   );    
            $i++;        

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }


    public function listing_paket_item_batch_edit($paket_batch_id)
    {
        
        $result = $this->paket_batch_item_m->get_datatable_edit_batch($paket_batch_id);

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

          $i = 0;
          $count = count($records->result_array());

          $checked = '';

        foreach($records->result_array() as $row)
        {        

            if ($row['paket_batch_id'] != null) {

                $checked = 'checked';

            } else {

                $checked = '';
            }

            $nama_item          = '<input class="form-control hidden" id="nama_item" name="items['.$i.'][nama_item]" value="'.$row['nama_item'].'">';
            $item_id            = '<input class="form-control hidden" id="item_id" name="items['.$i.'][item_id]" value="'.$row['item_id'].'">';
            $jumlah             = '<input class="form-control hidden" id="jumlah_item" name="items['.$i.'][jumlah_item]" value="'.$row['jumlah_item'].'">';
            $in_count           = '<input class="form-control hidden" id="count_temp" value="'.$i.'">';
            $in_paket_batch_id  = '<input class="form-control hidden" id="id_batch_item" name="items['.$i.'][id_batch_item]" value="'.$row['id'].'">';
            $in_jumlah          = '<input class="form-control" id="jumlah" name="items['.$i.'][jumlah]" value="'.$row['jumlah'].'">';
            $in_satuan          = '<select class="form-control" id="tipe_satuan" name="items['.$i.'][tipe_satuan]">
                                    <option value="0">Pilih Satuan</option>
                                    <option value="1">Dus</option>
                                    <option value="2">Kotak</option>
                                    <option value="3">Strip</option>
                                    <option value="4">Tablet</option>
                                    <option value="5">Unit</option>
                                 </select>';

            $output['aaData'][] = array(
                    ($i == $count) ? '<div class="text-center"><input class="checkboxes" checked="'.$checked.'" name="items['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['id'].'">'.$in_count.'</div>' : '<div class="text-center"><input class="checkboxes" checked="'.$checked.'" name="items['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['id'].'"></div>',
                    '<div class="text-center">'.$row['nama_item'].$nama_item.$item_id.$in_paket_batch_id.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$jumlah.'</div>',
                    '<div class="text-center">'.$in_jumlah.'</div>',
                    '<div class="text-center">'.$in_satuan.'</div>',
                    
                   );    
            $i++;        

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function listing_paket_item_batch_view($paket_batch_id)
    {
        
        $result = $this->paket_batch_item_m->get_datatable_view_batch($paket_batch_id);

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

          $i = 0;
          $tipe_satuan = '';
          $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {        

            $nama_item    = '<input class="form-control hidden" id="nama_item" name="items['.$i.'][nama_item]" value="'.$row['nama_item'].'">';
            $item_id      = '<input class="form-control hidden" id="item_id" name="items['.$i.'][item_id]" value="'.$row['item_id'].'">';
            $jumlah       = '<input class="form-control hidden" id="jumlah_item" name="items['.$i.'][jumlah_item]" value="'.$row['jumlah'].'">';

            if ($row['item_satuan_id'] == 1)
                $tipe_satuan = 'Dus';
            if ($row['item_satuan_id'] == 2)
                $tipe_satuan = 'Kotak';
            if ($row['item_satuan_id'] == 3)
                $tipe_satuan = 'Strip';
            if ($row['item_satuan_id'] == 4)
                $tipe_satuan = 'Tablet';
            if ($row['item_satuan_id'] == 5)
                $tipe_satuan = 'Unit';

            $output['aaData'][] = array(
                    '<div class="text-center">'.$row['nama_item'].$nama_item.$item_id.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$jumlah.'</div>',
                    '<div class="text-center">'.$tipe_satuan.'</div>',
                    
                   );    
            $i++;        

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function listing_paket_tindakan_batch($id)
    {
        
        $result = $this->paket_tindakan_m->get_datatable($id);

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

          $i = 0;
          $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {        

            $nama_tindakan = '<input class="form-control hidden" id="nama_tindakan" name="tindakan['.$i.'][nama_tindakan]" value="'.$row['nama_tindakan'].'">';
            $tindakan_id   = '<input class="form-control hidden" id="tindakan_id" name="tindakan['.$i.'][tindakan_id]" value="'.$row['tindakan_id'].'">';
            $jumlah        = '<input class="form-control hidden" id="jumlah_tindakan" name="tindakan['.$i.'][jumlah_tindakan]" value="'.$row['jumlah'].'">';
            $in_count      = '<input class="form-control hidden" id="count_temp" value="'.$i.'">';
            $in_jumlah     = '<input class="form-control" id="jumlah" name="tindakan['.$i.'][jumlah]" value="0">';


            $output['aaData'][] = array(
                    ($i == $count) ? '<div class="text-center"><input class="checkboxes" name="tindakan['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['paket_id'].'">'.$in_count.'</div>' : '<div class="text-center"><input class="checkboxes" name="tindakan['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['paket_id'].'"></div>',
                    '<div class="text-center">'.$row['nama_tindakan'].$nama_tindakan.$tindakan_id.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$jumlah.'</div>',
                    '<div class="text-center">'.$in_jumlah.'</div>',
                    
                   );                      
            $i++;        
            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function listing_paket_tindakan_batch_edit($paket_batch_id)
    {
        
        $result = $this->paket_batch_tindakan_m->get_datatable_edit_batch($paket_batch_id);
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
        // die_dump($records);

          $i = 0;
          $count = count($records->result_array());
          $checked = '';

        foreach($records->result_array() as $row)
        {        

            if ($row['paket_batch_id'] != null) {

                $checked = 'checked';

            } else {

                $checked = '';
            }

            $nama_tindakan       = '<input class="form-control hidden" id="nama_tindakan" name="tindakan['.$i.'][nama_tindakan]" value="'.$row['nama_tindakan'].'">';
            $tindakan_batch_id   = '<input class="form-control hidden" id="tindakan_batch_id" name="tindakan['.$i.'][tindakan_batch_id]" value="'.$row['id'].'">';
            $tindakan_id         = '<input class="form-control hidden" id="tindakan_id" name="tindakan['.$i.'][tindakan_id]" value="'.$row['tindakan_id'].'">';
            $jumlah              = '<input class="form-control hidden" id="jumlah_tindakan" name="tindakan['.$i.'][jumlah_tindakan]" value="'.$row['jumlah_tindakan'].'">';
            $in_count            = '<input class="form-control hidden" id="count_temp" value="'.$i.'">';
            $in_jumlah           = '<input class="form-control" id="jumlah" name="tindakan['.$i.'][jumlah]" value="'.$row['jumlah'].'">';


            $output['aaData'][] = array(
                    ($i == $count) ? '<div class="text-center"><input class="checkboxes" chekced="'.$checked.'"name="tindakan['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['id'].'">'.$in_count.'</div>' : '<div class="text-center"><input class="checkboxes" checked="'.$checked.'" name="tindakan['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['id'].'"></div>',
                    '<div class="text-center">'.$row['nama_tindakan'].$nama_tindakan.$tindakan_id.$tindakan_batch_id.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$jumlah.'</div>',
                    '<div class="text-center">'.$in_jumlah.'</div>',
                    
                   );                      
            $i++;        
            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function listing_paket_tindakan_batch_view($paket_batch_id)
    {
        
        $result = $this->paket_batch_tindakan_m->get_datatable_view_batch($paket_batch_id);

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

          $i = 0;
          $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {        

            $nama_tindakan = '<input class="form-control hidden" id="nama_tindakan" name="tindakan['.$i.'][nama_tindakan]" value="'.$row['nama_tindakan'].'">';
            $tindakan_id   = '<input class="form-control hidden" id="tindakan_id" name="tindakan['.$i.'][tindakan_id]" value="'.$row['tindakan_id'].'">';
            $jumlah        = '<input class="form-control hidden" id="jumlah_tindakan" name="tindakan['.$i.'][jumlah_tindakan]" value="'.$row['jumlah'].'">';


            $output['aaData'][] = array(
                    '<div class="text-center">'.$row['nama_tindakan'].$nama_tindakan.$tindakan_id.'</div>',
                    '<div class="text-center">'.$row['jumlah'].$jumlah.'</div>',
                    
                   );                      
            $i++;        
            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }


    public function listing_paket_tindakan($so_id)
    {
        
        $result = $this->paket_tindakan_m->get_datatable_view($so_id);

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

        $subtotal = 0;


          $i = 0;
          $count = count($records->result_array());
          $inputSubtotal = '';

        foreach($records->result_array() as $row)
        {        
            $i++;        

            $subtotal = $subtotal + ($row['jumlah'] * $row['harga']);

            if ($i == $count) 
            {
                $inputSubtotal = '<input type="hidden" id="subtotal_tindakan" name="subtotal_tindakan" value="'.$subtotal.'">';

            }

            $output['aaData'][] = array(
                    '<div class="text-center">'.$row['kode'].'</div>',
                    '<div class="text-center">'.$row['nama_tindakan'].'</div>',
                    '<div class="text-center">'.$row['jumlah'].'</div>',
                    '<div class="text-center">'.'Rp.' . number_format($row['harga'],0,'','.').'</div>',
                    '<div class="text-center">'.'Rp.' . number_format($subtotal,0,'','.'). ',-'.$inputSubtotal.'</div>',
                   );                      

            
        }
        // die_dump($this->db->last_query());
      echo json_encode($output);
    }

    public function save()
    {
        $array_input = $this->input->post();
        $command = $array_input['command'];
        $payment = $array_input['payment'];
        $invoice = $array_input['invoice'];

       // die_dump($array_input);
        $cabang_id = $this->session->userdata('cabang_id');
        $cabang = $this->cabang_m->get($cabang_id);

        if ($command === 'add')
        {  

            $data_draf_invoice = $this->draf_invoice_m->get_by(array('pasien_id' => $this->input->post('id_ref_pasien'), 'jenis_invoice !=' => 0));
            $data_draf_invoice = object_to_array($data_draf_invoice);

            //die(dump($data_draf_invoice));

            foreach ($data_draf_invoice as $key => $draf_invoice) {
                $data_draf_invoice_detail = $this->draf_invoice_detail_m->get_by(array('draf_invoice_id' => $draf_invoice['id']));
                $data_draf_invoice_detail = object_to_array($data_draf_invoice_detail);

                $data_draf_invoice_detail_hd = $this->draf_invoice_detail_m->get_by(array('draf_invoice_id' => $draf_invoice['id'], 'tipe_item' => 1), true);

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

                $assesment = $this->tindakan_hd_penaksiran_history_m->get_by(array('pasien_id' => $draf_invoice['pasien_id'], 'date(created_date)' => date('Y-m-d')), true);

                $data_invoice_real = array(
                    'id'    => $id_invoice,
                    'no_invoice'           => $no_invoice,
                    'waktu_tindakan'       => (count($assesment)==0)?date('H:i'):$assesment->waktu,
                    'cabang_id'            => $this->session->userdata('cabang_id'),
                    'tipe_pasien'            => 1,
                    'pasien_id'            => $draf_invoice['pasien_id'],
                    'nama_pasien'          => $draf_invoice['nama_pasien'],
                    'tipe'                 => 1,
                    'penjamin_id'          => $draf_invoice['jenis_invoice'],
                    'nama_penjamin'        => ($draf_invoice['jenis_invoice']==1)?$draf_invoice['nama_pasien']:'BPJS - JKN',
                    'is_claim'             => 0, 
                    'poliklinik_id'        => 1,
                    'nama_poliklinik'      => 'Poli HD',
                    'status'               => 2,
                    'shift'                => $draf_invoice['shift'],
                    'jenis_invoice'                => 1,
                    'harga'                => $biaya_total,
                    'akomodasi'            => $draf_invoice['akomodasi'],
                    'diskon'               => $draf_invoice['diskon_persen'],
                    'diskon_nominal'       => $draf_invoice['diskon_nominal'],
                    'harga_setelah_diskon' => $biaya_total,
                    'sisa_bayar' => $biaya_total,
                    'user_level_id' => $this->session->userdata('level_id'),
                    'is_active'            => 1,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'    => date('Y-m-d H:i:s')
                );

                $save_invoice = $this->invoice_m->add_data($data_invoice_real);


                foreach ($data_draf_invoice_detail as $draf_invoice_detail) {
                    $last_id_invoice_detail       = $this->invoice_detail_m->get_id_invoice_detail()->result_array();
                    $last_id_invoice_detail       = intval($last_id_invoice_detail[0]['max_id'])+1;
                    
                    $format_id_invoice_detail     = 'IVD-'.date('m').'-'.date('Y').'-%04d';
                    $id_invoice_detail = sprintf($format_id_invoice_detail, $last_id_invoice_detail, 4);


                    $data_invoice_real = array(
                        'id'    => $id_invoice_detail,
                        'invoice_id'           => $id_invoice,
                        'item_id'   => $draf_invoice_detail['item_id'],
                        'nama_tindakan'   => $draf_invoice_detail['nama_tindakan'],
                        'tipe_item'   => $draf_invoice_detail['tipe_item'],
                        'qty'   => $draf_invoice_detail['jumlah'],
                        'hpp'   => $draf_invoice_detail['hpp'],
                        'harga'   => $draf_invoice_detail['harga_jual'],
                        'diskon_persen'   => $draf_invoice_detail['diskon_persen'],
                        'diskon_nominal'   => $draf_invoice_detail['diskon_nominal'],
                        'status'            => 2,
                        'is_active'            => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_invoice_detail = $this->invoice_detail_m->add_data($data_invoice_real);

                    $delete_draf_invoice_detail = $this->draf_invoice_detail_m->delete_by(array('id' => $draf_invoice_detail['id']));

                }
            }

            $delete_draf_invoice = $this->draf_invoice_m->delete_by(array('pasien_id' => $this->input->post('id_ref_pasien')));

            $pasien_tindakan = $this->pasien_tindakan_m->get_by(array('pasien_id' => $this->input->post('id_ref_pasien'), 'tipe_tindakan' => 1));
            $pasien_tindakan_trans = $this->pasien_tindakan_m->get_by(array('pasien_id' => $this->input->post('id_ref_pasien'), 'tipe_tindakan' => 2, 'status >=' => 2));

            foreach ($pasien_tindakan as $pas_tdk) {
                $save_pasien_tindakan_history = $this->pasien_tindakan_history_m->add_data($pas_tdk);
            }
            foreach ($pasien_tindakan_trans as $pas_tdk_trans) {
                $save_pasien_tindakan_history = $this->pasien_tindakan_history_m->add_data($pas_tdk_trans);
            }
            $delete_pasien_tindakan = $this->pasien_tindakan_m->delete_by(array('pasien_id' => $this->input->post('id_ref_pasien'), 'tipe_tindakan' => 1));
            $delete_pasien_tindakan_trans = $this->pasien_tindakan_m->delete_by(array('pasien_id' => $this->input->post('id_ref_pasien'), 'tipe_tindakan' => 2, 'status >=' => 2));


            $last_number  = $this->pembayaran_pasien_m->get_nomor_faktur()->result_array();
            $last_number  = intval($last_number[0]['max_nomor_faktur'])+1;
            // die_dump($last_number);

            $format       = 'PYC-'.date('ym').'-%03d';
            $no_faktur    = sprintf($format, $last_number, 3);

            if($this->input->post('id_ref_pasien') != '')
            {
                $pasien = $this->pasien_m->get($this->input->post('id_ref_pasien'));
                $nama_pasien = $pasien->nama;
                $tipe_pasien = 1;
            }
            else
            {
                $nama_pasien = $this->input->post('pasien_option');
                $tipe_pasien = 2;
            }
            $tipe = '';
            if($array_input['grand_total_hidden'] != '0' && $array_input['grand_total_klaim_hidden'] == '0')
            {
                $tipe = 1;
            }
            if($array_input['grand_total_hidden'] == '0' && $array_input['grand_total_klaim_hidden'] != '0')
            {
                $tipe = 2;
            }
            if($array_input['grand_total_hidden'] == '0' && $array_input['grand_total_klaim_hidden'] == '0')
            {
                $tipe = 3;
            }

            $keterangan_int = '';
            $keterangan_ext = '';

            $shift_bayar = 1;
            if(date('H:i:s') > '03:00:01' &&  date('H:i:s') <= '12:00:00'){
                $shift_bayar = 1;
            }if(date('H:i:s') > '12:00:01' &&  date('H:i:s') <= '18:30:00'){
                $shift_bayar = 2;
            }if(date('H:i:s') > '18:30:01' &&  date('H:i:s') <= '23:59:59'){
                $shift_bayar = 3;
            }if(date('H:i:s') > '00:00:01' &&  date('H:i:s') <= '03:00:00'){
                $shift_bayar = 3;
            }

            $data = array(
                'cabang_id'      => $this->session->userdata('cabang_id'),
                'no_pembayaran'  => $no_faktur,
                'pasien_id'      => $this->input->post('id_ref_pasien'),
                'nama_pasien'    => $nama_pasien,
                'tipe_pasien'    => $tipe_pasien,
                'kasir_id'       => $array_input['user_id'],
                'shift'          => $shift_bayar,
                'tanggal'        => date('Y-m-d'),
                'tipe_transaksi' => 1,
                'biaya_tunai'    => $array_input['grand_total_hidden'],
                'biaya_klaim'    => $array_input['grand_total_klaim_hidden'],
                'diskon'         => $array_input['diskon_hidden'],
                'pph'            => $array_input['pph_hidden'],
                'pembulatan'     => 0,
                'bayar_tunai'    => $array_input['bayar_hidden'],
                'kembali'        => $array_input['kembali_hidden'],   
                'status'         => 1,   
                'is_active'      => 1,   
            );
            // die_dump($data);

            $pembayaran_pasien_id = $this->pembayaran_pasien_m->save($data);
            // die(dump($this->db->last_query()));

            $data_detail = array(
                'pembayaran_id' => $pembayaran_pasien_id,
                'invoice_id'    => $id_invoice,
                'total_bayar'   => $inv['harga_invoice'],
                'status'        => 1
            );

            $pembayaran_detail_id = $this->pembayaran_detail_m->save($data_detail);
                
            $total_int = 0;
            $total_ext = 0;
            foreach ($invoice as $key=>$inv) {
                if($inv['select'] == 1){
                    if($inv['jenis_invoice'] == 1){
                        $total_int = $total_int + $inv['harga_invoice'];

                        $data_detail = array(
                            'pembayaran_id' => $pembayaran_pasien_id,
                            'invoice_id'    => $id_invoice,
                            'total_bayar'   => $inv['harga_invoice'],
                            'status'        => 1
                        );

                        $pembayaran_detail_id = $this->pembayaran_detail_m->save($data_detail);
                        
                        $data_invoice = array(
                            'status'     => 2,
                            'sisa_bayar' => 0
                        );

                        $invoice  = $this->invoice_m->save($data_invoice, $inv['id']);


                        $keterangan_int .="Pembayaran invoice ".$inv['no_invoice'].";";

                        
                    }elseif($inv['jenis_invoice'] == 2){
                        $total_ext = $total_ext + $inv['harga_invoice'];

                        $data_detail = array(
                            'pembayaran_id' => $pembayaran_pasien_id,
                            'invoice_id'    => $inv['id'],
                            'total_bayar'   => $inv['harga_invoice'],
                            'status'        => 1
                        );

                        $pembayaran_detail_id = $this->pembayaran_detail_m->save($data_detail);

                        
                        $data_invoice = array(
                            'status'     => 2,
                            'sisa_bayar' => 0
                        );

                        $invoice  = $this->invoice_m->save($data_invoice, $inv['id']);


                        $keterangan_ext .="Pembayaran invoice ".$inv['no_invoice'].";";

                        foreach ($array_input['items_'.$key] as $item) {
                            
                            if(isset($item['checkbox'])){
                                $data_detail_item = array(
                                    'pembayaran_detail_id' => $pembayaran_detail_id,
                                    'item_id'              => $item['id'],
                                    'tipe_item'            => $item['tipe_item'],
                                    'harga'                => $item['harga'],
                                    'is_active'            => 0
                                );

                                $pembayaran_detail_item_id = $this->pembayaran_detail_item_m->save($data_detail_item);

                                $data_invoice_detail['status'] = 2;
                                $invoice_detail = $this->invoice_detail_m->save($data_invoice_detail, $item['id']);

                                $keterangan_ext .= "-".$item['nama_item']." ".formatrupiah($item['harga']).";";
                            }
                        }

                        if($inv['akomodasi'] != NULL && $inv['akomodasi'] != 0){ 
                            $keterangan_ext .=" -Akomodasi ".formatrupiah($inv['akomodasi']).";";
                        }
                    }
                }   
            }
                

            if($payment)
            {
                foreach ($payment as $pay) 
                {
                    if($pay['payment_type'] == 1){
                        if($total_int > 0){
                            $last_saldo = get_saldo($cabang->url, date('Y-m-d', strtotime($array_input['tanggal'])));
                    
                            $saldo_before = 0;
                            if(count($last_saldo['before']) != 0){
                                $saldo_before = $last_saldo['before'][0]['saldo'];
                            }

                            $data_arus_kas = array(
                                'tanggal'      => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                                'tipe'         => 7,
                                'tipe_kasir'         => 1,
                                'keterangan'   => rtrim($keterangan_int,';'),
                                'user_id'      => $this->session->userdata('user_id'),
                                'debit_credit' => 'D',
                                'rupiah'       => $total_int,
                                'saldo'        => ($saldo_before + $total_int),
                                'status'       => 1
                            );

                            $path_model = 'keuangan/kasir_arus_kas_m';
                            $save_arus_kas = insert_data_api($data_arus_kas,$cabang->url,$path_model);
                            $inserted_save_arus_kas = $save_arus_kas;
                            
                            $inserted_save_arus_kas = str_replace('"', '', $inserted_save_arus_kas);

                            if(count($last_saldo['after']) != 0){
                                foreach ($last_saldo['after'] as $after) {
                                    $data_arus_kas_after = array(
                                        'saldo'        => ($after['saldo'] + $total_int),
                                    );

                                    $path_model = 'keuangan/kasir_arus_kas_m';
                                    $save_arus_kas = insert_data_api($data_arus_kas_after,$cabang->url,$path_model, $after['id']);
                                    $inserted_save_arus_kas = $save_arus_kas;
                                    
                                    $inserted_save_arus_kas = str_replace('"', '', $inserted_save_arus_kas);
                                }
                            }
                        }

                        if($total_ext > 0){
                            $last_saldo_ext = get_saldo_external($cabang->url, date('Y-m-d', strtotime($array_input['tanggal'])));
                    
                            $saldo_before = 0;
                            if(count($last_saldo_ext['before']) != 0){
                                $saldo_before = $last_saldo_ext['before'][0]['saldo'];
                            }

                            $data_arus_kas_ext = array(
                                'tanggal'      => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                                'tipe'         => 7,
                                'keterangan'   => rtrim($keterangan_ext,';'),
                                'user_id'      => $this->session->userdata('user_id'),
                                'debit_credit' => 'D',
                                'rupiah'       => $total_ext,
                                'saldo'        => ($saldo_before + $total_ext),
                                'status'       => 1
                            );

                            $path_model = 'keuangan/external_arus_kas_m';
                            $save_arus_kas = insert_data_api($data_arus_kas_ext,$cabang->url,$path_model);
                            $inserted_save_arus_kas = $save_arus_kas;
                            
                            $inserted_save_arus_kas = str_replace('"', '', $inserted_save_arus_kas);

                            if(count($last_saldo_ext['after']) != 0){
                                foreach ($last_saldo_ext['after'] as $after) {
                                    $data_arus_kas_after = array(
                                        'saldo'        => ($after['saldo'] + $total_ext),
                                    );

                                    $path_model = 'keuangan/external_arus_kas_m';
                                    $save_arus_kas = insert_data_api($data_arus_kas_after,$cabang->url,$path_model, $after['id']);
                                    $inserted_save_arus_kas = $save_arus_kas;
                                    
                                    $inserted_save_arus_kas = str_replace('"', '', $inserted_save_arus_kas);
                                }
                            }
                        }
                    }
                    $data_tipe_bayar = array(
                        'pembayaran_id' => $pembayaran_pasien_id,
                        'tipe_bayar'    => $pay['payment_type'],
                        'mesin_edc_id'  => ($pay['payment_type'] == 1)?'':$pay['mesin_edc_id'],
                        'jumlah_bayar'        => $pay['jumlah_bayar'],
                        'rupiah'        => $pay['nominal'],
                        'no_kartu'      => ($pay['payment_type'] == 1)?'':$pay['no_kartu'],
                        'jenis_kartu'   => ($pay['payment_type'] == 1)?0:$pay['jenis_kartu'],
                        'status'        => 1,
                    );
                    $pembayaran_tipe = $this->pembayaran_tipe_m->save($data_tipe_bayar);

                }
            }
            redirect('reservasi/pembayaran');
            
        }
        
    }

       

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->paket_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        // die_dump($max_id);
        
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // die_dump($trash_id);

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 5,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);
        // die_dump($this->db->last_query());

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Paket Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/paket");
    }

    public function delete_batch($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->paket_batch_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        // die_dump($max_id);
        
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // die_dump($trash_id);

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 10,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);
        // die_dump($this->db->last_query());

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Paket Batch Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/paket");
    }

    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->paket_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Paket Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/paket");
    }

    public function formatrupiah($val) {
        $hasil ='Rp.' . number_format($val, 0 , '' , '.' ) . ',-';
        return $hasil;
    }

    public function get_cabang_id(){
        
        $id = $this->input->post('id');
        // $poliklinik_id = $this->input->post('poliklinik_id');

        $this->cabang_poliklinik_m->set_columns(array('id', 'nama', 'kode'));
        $items = $this->cabang_poliklinik_m->get_cabang_id($id);
        // die(dump($this->db->last_query()));
        // die_dump($items->result());


        $items_array = object_to_array($items->result());
        echo json_encode($items_array);

    }

    function cetak_invoice_pasien($id) 
    {


        $data_bayar    = $this->pembayaran_pasien_m->get($id);
        $data_bayar    = object_to_array($data_bayar);

        $data_pasien   = $this->pembayaran_pasien_m->get_by(array('id' => $id, 'is_active' => 1));
        $data_pasien   = object_to_array($data_pasien);
        $nama_pasien   = $this->pasien_m->get($data_pasien[0]['pasien_id']);
        $nama_pasien   = object_to_array($nama_pasien);


        $data_tindakan = $this->pembayaran_tindakan_pasien_m->get_by(array('pembayaran_id' => $id, 'is_active' => 1));
        $result_tindakan = object_to_array($data_tindakan);
        // die(dump($result_tindakan));    
        
        $data_obat     = $this->pembayaran_obat_pasien_m->get_by(array('pembayaran_id' => $id, 'is_active' => 1));
        $data_obat     = object_to_array($data_obat);

        $result_obat = array();
        $result_pasien =array();

        
        // foreach ($data_obat as $obat) 
       
        ///////////////////////////////////

        $data_header = array(

            "id_transaksi"  => $data_bayar['id'],
            "no_transaksi"  => $data_bayar['no_pembayaran'],


        );


        //default setting

        $this->kcc_paket_fpdf->KCC_PAKET_FPDF("P", "mm", array(95,230));
        $this->kcc_paket_fpdf->Header(base64_encode(serialize($data_header)));
        $this->kcc_paket_fpdf->Open();
        $this->kcc_paket_fpdf->SetTitle("Judul Hardcode");
        $this->kcc_paket_fpdf->SetAuthor("Author Hardcode");
        $this->kcc_paket_fpdf->AliasNbPages();
        $this->kcc_paket_fpdf->AddPage();
        $this->kcc_paket_fpdf->SetAutoPageBreak(TRUE, 10);
        $this->kcc_paket_fpdf->SetSubject("Ayam Bakar");

        //baris pertama

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->SetXY(5,35);
        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
        $this->kcc_paket_fpdf->Cell(16, 4, "No. Pembayaran", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->MultiCell(30, 4, $data_bayar['no_pembayaran'], 0,"L");
        $this->kcc_paket_fpdf->Ln(6);

        //baris kedua

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->SetXY(5,40);
        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
        $this->kcc_paket_fpdf->Cell(16, 4, "Tanggal", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->MultiCell(27, 4, date('d F Y', strtotime($data_bayar['tanggal'])),0);
        $this->kcc_paket_fpdf->Ln(6);

        //baris ketiga

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->SetXY(5,45);
        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
        $this->kcc_paket_fpdf->Cell(16, 4, "Nama Pasien", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->MultiCell(27, 4, $nama_pasien['nama'],0);
        $this->kcc_paket_fpdf->Ln(6);

        //baris keEmpat

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->SetXY(5,50);
        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
        $this->kcc_paket_fpdf->Cell(16, 4, "Nomor Pasien", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(4, 4, ":", '', 0, 'L');

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->MultiCell(35, 4, $nama_pasien['no_member'],0);
        $this->kcc_paket_fpdf->Ln(6);

        //////////////////Line//////////////////////////////////
        $posisi_y = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->Line(0, $posisi_y-2, 95, $posisi_y-2);
        $this->kcc_paket_fpdf->Line(0, $posisi_y-1, 95, $posisi_y-1);

        ////////////////////////end Line///////////////////////////////
        

        $posisi_y = $this->kcc_paket_fpdf->GetY();

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->SetFont("Arial", "", 7);
        $this->kcc_paket_fpdf->Cell(16, 4, "Tindakan", '', 0, 'L');
        $this->kcc_paket_fpdf->Ln(6);

        //baris content
        $lineX_begin = $this->kcc_paket_fpdf->GetX();
        $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
        $this->kcc_paket_fpdf->Cell(5, 4, "No.", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(15, 4, "No. Transaksi", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(10, 4, "Poli", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(10, 4, "Penjamin", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(20, 4, "Harga", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(10, 4, "Diskon", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(20, 4, "Subtotal", '', 0, 'C');
        $this->kcc_paket_fpdf->Ln(6);

        $i = 1;
        $sub_total_tindakan = 0;

        foreach ($result_tindakan as $row_tindakan) 
        {

            // die(dump($row_tindakan));

            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
            $this->kcc_paket_fpdf->Cell(5, 4, $i, '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(15, 4, $row_tindakan['no_tindakan'], '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(10, 4, $row_tindakan['nama_poliklinik'], '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(10, 4, $row_tindakan['nama_penjamin'], '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(20, 4, "Rp. ".number_format($row_tindakan['harga'], 0, ',', '.')." #", '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(10, 4, $row_tindakan['diskon']." %", '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(20, 4, "Rp. ".number_format(($row_tindakan['harga']-$row_tindakan['diskon']), 0, ',', '.')." #", '', 0, 'C');
            // $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
            // $this->kcc_paket_fpdf->Cell(10, 4, $row_tindakan['Satuan'], '', 0, 'L');
            // $this->kcc_paket_fpdf->Cell(35, 4, "IDR ".number_format($row_tindakan['Total'], 0, ',', '.')." #", '', 0, 'C');
            $sub_total_tindakan += ($row_tindakan['harga']-$row_tindakan['diskon']);
            $this->kcc_paket_fpdf->Ln(6);

            $i++;

        }

        // $this->kcc_paket_fpdf->Ln(6);



        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $posisi_y = $this->kcc_paket_fpdf->GetY();

        $lineY_begin = $this->kcc_paket_fpdf->GetY();
        $this->kcc_paket_fpdf->SetFont("Arial", "", 7);
        $this->kcc_paket_fpdf->Cell(16, 4, "Obat", '', 0, 'L');
        $this->kcc_paket_fpdf->Ln(6);

        //baris content
        $lineX_begin = $this->kcc_paket_fpdf->GetX();
        $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
        $this->kcc_paket_fpdf->Cell(5, 4, "No.", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(15, 4, "Nama Obat", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(10, 4, "Qty", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(5, 4, "tipe", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(10, 4, "Penjamin", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(15, 4, "Harga", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(15, 4, "Diskon", '', 0, 'C');
        $this->kcc_paket_fpdf->Cell(15, 4, "Subtotal", '', 0, 'C');
        $this->kcc_paket_fpdf->Ln(6);

        $i = 1;
        $sub_total_obat = 0;
        foreach ($result_obat as $row_obat) 

        {

            // die(dump($row_obat));

            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
            $this->kcc_paket_fpdf->Cell(5, 4, $i, '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(15, 4, $row_obat[0]['nama'], '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(10, 4, $row_obat[0]['jumlah'], '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(5, 4, $row_obat[0]['tipe'], '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(10, 4, $row_obat[0]['penjamin'], '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(15, 4, "Rp. ".number_format($row_obat[0]['harga'], 0, ',', '.')." #", '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(15, 4, $row_obat[0]['diskon']." %", '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(15, 4, "Rp. ".number_format($row_obat[0]['sub_total'], 0, ',', '.')." #", '', 0, 'C');
            // $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
            // $this->kcc_paket_fpdf->Cell(10, 4, $row_obat['Satuan'], '', 0, 'L');
            // $this->kcc_paket_fpdf->Cell(35, 4, "IDR ".number_format($row_obat['Total'], 0, ',', '.')." #", '', 0, 'C');
            $sub_total_obat += $row_obat[0]['sub_total'];
            $this->kcc_paket_fpdf->Ln(6);

            $i++;

        }

        // $this->kcc_paket_fpdf->Ln(8);


        $grand_total = $sub_total_tindakan + $sub_total_obat;
        $pembulatan = 0;
        $total = $grand_total + $pembulatan;


        // $this->kcc_paket_fpdf->SetY($posisi_y + 4 + 5 + 4 + 5 + 4 + 10 + (substr_count($terbilang, "\n") * 4) + 5 );


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
        $this->kcc_paket_fpdf->Cell(70, 4, "TOTAL TRANSAKSI", '', 0, 'R');
        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
        $this->kcc_paket_fpdf->Cell(12, 4, "IDR ".number_format($grand_total, 0, ',', '.')." #", '', 0, 'C');
        $this->kcc_paket_fpdf->Ln(5);

        

        $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
        $this->kcc_paket_fpdf->Cell(70, 4, "PEMBULATAN", '', 0, 'R');
        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
        $this->kcc_paket_fpdf->Cell(12, 4, ($pembulatan!=0)?"IDR ".number_format($pembulatan, 0, ',', '.')." #":"-", '', 0, 'C');
        $this->kcc_paket_fpdf->Ln(5);

        

        $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
        $this->kcc_paket_fpdf->Cell(70, 4, "TOTAL BIAYA", '', 0, 'R');
        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
        $this->kcc_paket_fpdf->Cell(12, 4, "IDR ".number_format($total, 0, ',', '.')." #", '', 0, 'C');
        $this->kcc_paket_fpdf->Ln(10);



        $terbilang = terbilang($total);

        $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
        $this->kcc_paket_fpdf->Cell(11, 4, "Terbilang :", '', 0, 'L');
        $this->kcc_paket_fpdf->MultiCell(65, 4, "# ".$terbilang." Rupiah #",0, 'L');
        $this->kcc_paket_fpdf->Ln(5);



        $posisiY_garis = $this->kcc_paket_fpdf->GetY();

        $this->kcc_paket_fpdf->Line(0, $posisiY_garis, 95, $posisiY_garis);
        $this->kcc_paket_fpdf->Line(0, $posisiY_garis+1, 95, $posisiY_garis+1); 
        $this->kcc_paket_fpdf->Ln();

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
        $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(50, 4, "Jakarta, ".date("d M Y"), 0, "J");
        $this->kcc_paket_fpdf->Ln(4);

        $this->kcc_paket_fpdf->Cell(20, 4, "", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(35, 4, "Kasir", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(30, 4, "Pasien", '', 0, 'L');
        $this->kcc_paket_fpdf->Ln(20);

        $user_id = $this->session->userdata("user_id");
        $user_login = $this->user_m->get_by(array('id' => $user_id), true);
        // die_dump($this->db->last_query());
        // die_dump($user_login);

        $this->kcc_paket_fpdf->Cell(20, 4, "", '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(35, 4, $user_login->nama, '', 0, 'L');
        $this->kcc_paket_fpdf->Cell(30, 4, $nama_pasien['nama'], '', 0, 'L');
        $this->kcc_paket_fpdf->Ln();
        

        //Save PDF

        $this->kcc_paket_fpdf->Output($data_bayar['no_pembayaran'].".pdf", "I");

        $flashdata = array(

            "information"   => "Berhasil membuat invoice"

        );

        $this->session->set_flashdata($flashdata);
    }


    public function history()
    {
        $assets = array();
        $config = 'assets/reservasi/pembayaran/history';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $user_id = $this->session->userdata('user_level_id');
        $user_login = $this->user_m->get_by(array('user_level_id' => $user_id), true);
        

        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('History Pembayaran', $this->session->userdata('language')), 
            'header'         => translate('History Pembayaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/pembayaran/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function listing_history()
    {
        $result = $this->pembayaran_history_m->get_datatable();

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        foreach($records->result_array() as $row)
        {
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';

            $tipe = "-";
            $total = 0;
            $total_klaim = 0;
            $total_klaim_format = '';
            if ($row['tipe_transaksi'] == 1) 
            {
                $tipe = "Tunai";

                $tunai = intval($row['biaya_tunai']) - (intval($row['biaya_tunai'])*intval($row['diskon'])/100);
                $pph   = intval($row['biaya_tunai'])*intval($row['pph'])/100;

                $total = $tunai + $pph + $row['pembulatan']; 
            } 
            elseif ($row['tipe_transaksi'] == 2) 
            {
                $tipe = "Klaim";

                $tunai = intval($row['biaya_klaim']) - (intval($row['biaya_klaim'])*intval($row['diskon'])/100);
                $pph   = intval($row['biaya_klaim'])*intval($row['pph'])/100;

                $total = $tunai + $pph + $row['pembulatan']; 
            } 
            elseif ($row['tipe_transaksi'] == 3) 
            {
                $tipe = "Tunai / Klaim";

                $tunai = intval($row['biaya_tunai']) - (intval($row['biaya_tunai'])*intval($row['diskon'])/100);
                $pph   = intval($row['biaya_tunai'])*intval($row['pph'])/100;

                $total = $tunai + $pph + $row['pembulatan']; 

                $tunai = intval($row['biaya_klaim']) - (intval($row['biaya_klaim'])*intval($row['diskon'])/100);
                $pph   = intval($row['biaya_klaim'])*intval($row['pph'])/100;

                $total_klaim = $tunai + $pph + $row['pembulatan']; 
                $total_klaim_format = '/ Rp. '.number_format($total_klaim, 0, '', '.').',-';

            }

            $no_cetak = ' ';
            if ($row['no_cetak']) 
            {
                $cetak_data = $this->pembayaran_cetak_m->get_by(array('pembayaran_id' => $row['id']));
                $cetak_data = object_to_array($cetak_data);
           
                $no_cetak = '<div class="input-group"> <input class="form-control text-center" readonly value="'.$row['no_cetak'].'"> <span class="input-group-btn"> <a title="'.translate('Info', $this->session->userdata('language')).'" data-cetak="'.htmlentities(json_encode($cetak_data)).'" class="btn btn-primary no_cetak"><i class="fa fa-info"></i></a> </span> </div>';
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['no_pembayaran'].'</div>',
                '<div class="text-left">'.$row['pasien'].'</div>',
                '<div class="text-left">'.$row['kasir'].'</div>',
                '<div class="text-center">'.$tipe.'</div>',
                '<div class="text-right">Rp. '.number_format($total, 0, '', '.').',- '.$total_klaim_format.'</div>' ,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
        }

        echo json_encode($output);

    }

    public function cetak_sep($pembayaran_id)
    {
        $mpdf = new mPDF('arial',array(210,93), 1, '', 0, 0, 7, 2, 0, 0);
        
        $data = array(
            'pembayaran_id' => $pembayaran_id,
        );

        $mpdf->writeHTML($this->load->view('reservasi/pembayaran/pdf/print_sep', $data,true));
        $mpdf->Output('invoice_'.date('Y-m-d H:i:s').'.pdf', 'I');    
    }

    public function cetak_invoice_pembayaran_history($id) 
    {
        $data_bayar    = $this->pembayaran_pasien_m->get($id);
        $data_bayar    = object_to_array($data_bayar);

        $data_pasien   = $this->pembayaran_pasien_m->get_by(array('id' => $id, 'is_active' => 1));
        $data_pasien   = object_to_array($data_pasien);
        $nama_pasien   = $this->pasien_m->get($data_pasien[0]['pasien_id']);
        $nama_pasien   = object_to_array($nama_pasien);


        $pembayaran_detail = $this->pembayaran_detail_m->get_by(array('pembayaran_id' => $id));
        $pembayaran_detail = object_to_array($pembayaran_detail);
        ///////////////////////////////////

        $data_header = array(
            "id_transaksi"  => $data_bayar['id'],
            "no_transaksi"  => $data_bayar['no_pembayaran']
        );


        //default setting

        $this->kcc_paket_fpdf->KCC_PAKET_FPDF("P", "mm", array(95,230));
        $this->kcc_paket_fpdf->Header(base64_encode(serialize($data_header)));
        $this->kcc_paket_fpdf->Open();
        $this->kcc_paket_fpdf->SetTitle("Judul Hardcode");
        $this->kcc_paket_fpdf->SetAuthor("Author Hardcode");
        $this->kcc_paket_fpdf->AliasNbPages();
        $this->kcc_paket_fpdf->AddPage();
        $this->kcc_paket_fpdf->SetAutoPageBreak(TRUE, 10);
        $this->kcc_paket_fpdf->SetSubject("Ayam Bakar");

        //baris pertama

        foreach ($pembayaran_detail as $detail) 
        {

            $this->kcc_paket_fpdf->SetFont('Arial','',7);
            $this->kcc_paket_fpdf->Cell(0,0,"TANDA TERIMA ".strtoupper($detail['nama_poliklinik']),0,0,'C');
            $this->kcc_paket_fpdf->Ln(4);

            $pasien_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $data_pasien[0]['pasien_id'], 'penjamin_id' => $detail['penjamin_id']), true);

            $cabang = $this->cabang_m->get($detail['cabang_id']);

            $data_paket = array();
            $data_item = array();
            $data_item_non_klaim = array();
            $data_item_vitamin = array();
            $data_item_vitamin_non_klaim = array();
            $data_item_alkes = array();
            $data_item_alkes_non_klaim = array();
            if($detail['tipe'] == 1)
            {
                $path_model = 'klinik_hd/tindakan_hd_paket_m';
                $wheres_trx = array(
                    'tindakan_hd_id' => $detail['tindakan_id']
                );
                $data_paket = json_decode(get_data_api($cabang->url,$path_model,$wheres_trx));
                $data_paket = object_to_array($data_paket);

                $path_model = 'klinik_hd/tindakan_hd_item_m';
                $wheres_trx = array(
                    'tindakan_hd_id' => $detail['tindakan_id'],
                    'tipe_pemberian' => 3,
                    'jumlah != '    => 0,
                    'item.item_sub_kategori' => 2
                );
                $data_item = json_decode(get_data_api_item($cabang->url,$path_model,$wheres_trx));
                $data_item = object_to_array($data_item);

                $wheres_trx2 = array(
                    'tindakan_hd_id' => $detail['tindakan_id'],
                    'tipe_pemberian' => 3,
                    'jumlah != '    => 0,
                    'item.item_sub_kategori' => 3
                );
                $data_item_vitamin = json_decode(get_data_api_item($cabang->url,$path_model,$wheres_trx2));
                $data_item_vitamin = object_to_array($data_item_vitamin);

                $wheres_trx3 = array(
                    'tindakan_hd_id' => $detail['tindakan_id'],
                    'tipe_pemberian' => 3,
                    'jumlah != '    => 0,
                    'item.item_sub_kategori' => 1
                );
                $data_item_alkes = json_decode(get_data_api_item($cabang->url,$path_model,$wheres_trx3));
                $data_item_alkes = object_to_array($data_item_alkes);

                // die(dump($data_item_alkes));
                

            }
            # code...
            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->SetXY(5,35);
            $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
            $this->kcc_paket_fpdf->Cell(16, 2, "No. Tagihan", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(4, 2, ":", '', 0, 'L');

            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->MultiCell(30, 2, $data_bayar['no_pembayaran'], 0,"L");

            $lineY_left = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->SetY($lineY_begin);
            $this->kcc_paket_fpdf->Cell(48, 2, "", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(20,2, "Penanggung", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(2, 2, ":", '', 0, 'L');
            $this->kcc_paket_fpdf->MultiCell(20, 2,$detail['nama_penjamin'], 0, "L");

            $lineY_right = $this->kcc_paket_fpdf->GetY();

            if ($lineY_left > $lineY_right)
            {
                $this->kcc_paket_fpdf->SetY($lineY_left);
            }
            //baris kedua

            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->SetXY(5,40);
            $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
            $this->kcc_paket_fpdf->Cell(16, 2, "Tanggal", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(4, 2, ":", '', 0, 'L');

            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->MultiCell(27, 2, date('d F Y', strtotime($data_bayar['tanggal'])),0);

            //baris ketiga

            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->SetXY(5,45);
            $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
            $this->kcc_paket_fpdf->Cell(16, 2, "Nama Pasien", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(4, 2, ":", '', 0, 'L');

            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->MultiCell(27, 2, $nama_pasien['nama'],0);
            $this->kcc_paket_fpdf->SetY($lineY_begin);

            $this->kcc_paket_fpdf->Cell(48, 2, "", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(20, 2, "No. Penanggung", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(2, 2, ":", '', 0, 'L');
            $this->kcc_paket_fpdf->MultiCell(20, 2, ($detail['penjamin_id'] == 1)?'-':$pasien_penjamin->no_kartu, 0, "L");
            $lineY_right = $this->kcc_paket_fpdf->GetY();
            if ($lineY_left > $lineY_right)
            {
                $this->kcc_paket_fpdf->SetY($lineY_left);
            }


            //baris keEmpat
            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->SetXY(5,50);
            $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
            $this->kcc_paket_fpdf->Cell(16, 2, "Nomor Pasien", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(4, 2, ":", '', 0, 'L');

            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->MultiCell(35, 2, $nama_pasien['no_member'],0);
            $lineY_left = $this->kcc_paket_fpdf->GetY();

            $this->kcc_paket_fpdf->SetY($lineY_begin);
            $this->kcc_paket_fpdf->Cell(48, 2, "", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(20, 2, "Waktu", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(2, 2, ":", '', 0, 'L');
            $this->kcc_paket_fpdf->MultiCell(20, 2, "", 0, "L");  

            $this->kcc_paket_fpdf->Ln(6);
            

            $posisi_y = $this->kcc_paket_fpdf->GetY();

            $lineY_begin = $this->kcc_paket_fpdf->GetY();
            $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
            $this->kcc_paket_fpdf->Cell(55, 4, "KETERANGAN", '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(10, 4, "QTY", '', 0, 'C');
            $this->kcc_paket_fpdf->Cell(15, 4, "BIAYA", '', 0, 'C');
            $this->kcc_paket_fpdf->Ln(4);
            

            $total_paket = 0;
            if(count($data_paket) != 0)
            {
                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                $this->kcc_paket_fpdf->Cell(55, 4, "BIAYA TINDAKAN", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'C');
                $this->kcc_paket_fpdf->Cell(15, 4, "", '', 0, 'C');
                $this->kcc_paket_fpdf->Ln(3);
                $total_paket = 0;
                foreach ($data_paket as $paket) 
                {
                    $master_paket = $this->paket_m->get($paket['paket_id']);

                    $lineY_begin = $this->kcc_paket_fpdf->GetY();
                    $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                    $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                    $this->kcc_paket_fpdf->Cell(53, 4, $master_paket->nama, '', 0, 'L');
                    $this->kcc_paket_fpdf->Cell(10, 4, '1', '', 0, 'C');
                    $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($paket['rupiah'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                    $this->kcc_paket_fpdf->Ln(2);

                    $total_paket += $paket['rupiah'];
                }
            }

            $total_item = 0;
            if(count($data_item) != 0)
            {
                $this->kcc_paket_fpdf->Ln(3);
                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                $this->kcc_paket_fpdf->Cell(55, 4, "BIAYA OBAT", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'C');
                $this->kcc_paket_fpdf->Cell(15, 4, "", '', 0, 'C');
                $this->kcc_paket_fpdf->Ln(3);
                if($detail['penjamin_id'] == 1)
                {
                    $total_item = 0;
                    foreach ($data_item as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);

                        $lineY_begin = $this->kcc_paket_fpdf->GetY();
                        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                        $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                        $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                        $this->kcc_paket_fpdf->Ln(3);

                        $total_item += $item['jumlah']*$item['harga_jual'];
                    }
                }
                else
                {
                    $total_item = 0;
                    foreach ($data_item as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);
                        $item_klaim = $this->item_klaim_m->get_by(array('item_id' => $item['item_id'], 'penjamin_id' => 2));

                        if(count($item_klaim) != 0)
                        {
                            $lineY_begin = $this->kcc_paket_fpdf->GetY();
                            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                            $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                            $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                            $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                            $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                            $this->kcc_paket_fpdf->Ln(3);
                            $total_item += $item['jumlah']*$item['harga_jual'];                            
                        }
                        else
                        {
                            $data_item_non_klaim[] = $item;
                        }

                    }
                }
            }
            
            $total_item_vitamin = 0;
            if(count($data_item_vitamin) != 0)
            {
                $this->kcc_paket_fpdf->Ln(3);
                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                $this->kcc_paket_fpdf->Cell(55, 4, "BIAYA VITAMIN", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'C');
                $this->kcc_paket_fpdf->Cell(15, 4, "", '', 0, 'C');
                $this->kcc_paket_fpdf->Ln(3);
                if($detail['penjamin_id'] == 1)
                {
                    $total_item_vitamin = 0;
                    foreach ($data_item_vitamin as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);

                        $lineY_begin = $this->kcc_paket_fpdf->GetY();
                        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                        $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                        $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                        $this->kcc_paket_fpdf->Ln(3);

                        $total_item_vitamin += $item['jumlah']*$item['harga_jual'];
                    }
                }
                else
                {
                    $total_item_vitamin = 0;
                    foreach ($data_item_vitamin as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);
                        $item_klaim = $this->item_klaim_m->get_by(array('item_id' => $item['item_id'], 'penjamin_id' => 2));

                        if(count($item_klaim) != 0)
                        {
                            $lineY_begin = $this->kcc_paket_fpdf->GetY();
                            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                            $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                            $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                            $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                            $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                            $this->kcc_paket_fpdf->Ln(3);
                            $total_item_vitamin += $item['jumlah']*$item['harga_jual'];                            
                        }
                        else
                        {
                            $data_item_vitamin_non_klaim[] = $item;
                        }

                    }
                }
            }

            $total_item_alkes = 0;
            if(count($data_item_alkes) != 0)
            {
                $this->kcc_paket_fpdf->Ln(3);
                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                $this->kcc_paket_fpdf->Cell(55, 4, "BIAYA ALKES", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'C');
                $this->kcc_paket_fpdf->Cell(15, 4, "", '', 0, 'C');
                $this->kcc_paket_fpdf->Ln(3);
                if($detail['penjamin_id'] == 1)
                {
                    $total_item_alkes = 0;
                    foreach ($data_item_alkes as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);

                        $lineY_begin = $this->kcc_paket_fpdf->GetY();
                        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                        $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                        $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                        $this->kcc_paket_fpdf->Ln(3);

                        $total_item_alkes += $item['jumlah']*$item['harga_jual'];
                    }
                }
                else
                {
                    $total_item_alkes = 0;
                    foreach ($data_item_alkes as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);
                        $item_klaim = $this->item_klaim_m->get_by(array('item_id' => $item['item_id'], 'penjamin_id' => 2));

                        if(count($item_klaim) != 0)
                        {
                            $lineY_begin = $this->kcc_paket_fpdf->GetY();
                            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                            $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                            $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                            $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                            $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                            $this->kcc_paket_fpdf->Ln(3);
                            $total_item_alkes += $item['jumlah']*$item['harga_jual'];                            
                        }
                        else
                        {
                            $data_item_alkes_non_klaim[] = $item;
                        }

                    }
                }
            }



            $grand_total = $total_paket + $total_item + $total_item_alkes + $total_item_vitamin;
            $pembulatan = 0;
            $total = $grand_total + $pembulatan;


            // $this->kcc_paket_fpdf->SetY($posisi_y + 4 + 5 + 4 + 5 + 4 + 10 + (substr_count($terbilang, "\n") * 4) + 5 );


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $this->kcc_paket_fpdf->Ln(3);
            $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
            $this->kcc_paket_fpdf->Cell(70, 4, "TOTAL TRANSAKSI", '', 0, 'R');
            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
            $this->kcc_paket_fpdf->Cell(12, 4, "IDR ".number_format($grand_total, 0, ',', ',')." #", '', 0, 'C');
            $this->kcc_paket_fpdf->Ln(5);

            

            $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
            $this->kcc_paket_fpdf->Cell(70, 4, "PEMBULATAN", '', 0, 'R');
            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
            $this->kcc_paket_fpdf->Cell(12, 4, ($pembulatan!=0)?"IDR ".number_format($pembulatan, 0, ',', ',')." #":"-", '', 0, 'C');
            $this->kcc_paket_fpdf->Ln(5);

            

            $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
            $this->kcc_paket_fpdf->Cell(70, 4, "TOTAL BIAYA", '', 0, 'R');
            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
            $this->kcc_paket_fpdf->Cell(12, 4, "IDR ".number_format($total, 0, ',', ',')." #", '', 0, 'C');
            $this->kcc_paket_fpdf->Ln(10);



            $terbilang = terbilang($total);

            $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
            $this->kcc_paket_fpdf->Cell(11, 4, "Terbilang :", '', 0, 'L');
            $this->kcc_paket_fpdf->MultiCell(65, 4, "# ".$terbilang." Rupiah #",0, 'L');
            $this->kcc_paket_fpdf->Ln(5);

            
            $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
            $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(50, 4, "Jakarta, ".date("d M Y"), 0, "J");
            $this->kcc_paket_fpdf->Ln(4);

            $this->kcc_paket_fpdf->Cell(20, 4, "", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(35, 4, "Kasir", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(30, 4, "Pasien", '', 0, 'L');
            $this->kcc_paket_fpdf->Ln(20);

            $user_id = $this->session->userdata("user_id");
            $user_login = $this->user_m->get_by(array('id' => $user_id), true);
            // die_dump($this->db->last_query());
            // die_dump($user_login);

            $this->kcc_paket_fpdf->Cell(20, 4, "", '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(35, 4, $user_login->nama, '', 0, 'L');
            $this->kcc_paket_fpdf->Cell(30, 4, $nama_pasien['nama'], '', 0, 'L');
            $this->kcc_paket_fpdf->Ln();

            if(count($data_item_non_klaim) != 0 || count($data_item_alkes_non_klaim) != 0 || count($data_item_vitamin_non_klaim) != 0)
            {
                $this->kcc_paket_fpdf->AddPage();
                $this->kcc_paket_fpdf->SetFont('Arial','',7);
                $this->kcc_paket_fpdf->Cell(0,0,"TANDA TERIMA ".strtoupper($detail['nama_poliklinik']),0,0,'C');
                $this->kcc_paket_fpdf->Ln(4);

                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetXY(5,35);
                $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
                $this->kcc_paket_fpdf->Cell(16, 2, "No. Tagihan", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(4, 2, ":", '', 0, 'L');

                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->MultiCell(30, 2, $data_bayar['no_pembayaran'], 0,"L");

                $lineY_left = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetY($lineY_begin);
                $this->kcc_paket_fpdf->Cell(48, 2, "", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(20,2, "Penanggung", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(2, 2, ":", '', 0, 'L');
                $this->kcc_paket_fpdf->MultiCell(20, 2,$nama_pasien['nama'], 0, "L");

                $lineY_right = $this->kcc_paket_fpdf->GetY();

                if ($lineY_left > $lineY_right)
                {
                    $this->kcc_paket_fpdf->SetY($lineY_left);
                }
                //baris kedua

                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetXY(5,40);
                $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
                $this->kcc_paket_fpdf->Cell(16, 2, "Tanggal", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(4, 2, ":", '', 0, 'L');

                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->MultiCell(27, 2, date('d F Y', strtotime($data_bayar['tanggal'])),0);

                //baris ketiga

                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetXY(5,45);
                $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
                $this->kcc_paket_fpdf->Cell(16, 2, "Nama Pasien", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(4, 2, ":", '', 0, 'L');

                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->MultiCell(27, 2, $nama_pasien['nama'],0);
                $this->kcc_paket_fpdf->SetY($lineY_begin);

                $this->kcc_paket_fpdf->Cell(48, 2, "", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(20, 2, "No. Penanggung", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(2, 2, ":", '', 0, 'L');
                $this->kcc_paket_fpdf->MultiCell(20, 2, '-', 0, "L");
                $lineY_right = $this->kcc_paket_fpdf->GetY();
                if ($lineY_left > $lineY_right)
                {
                    $this->kcc_paket_fpdf->SetY($lineY_left);
                }


                //baris keEmpat
                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetXY(5,50);
                $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
                $this->kcc_paket_fpdf->Cell(16, 2, "Nomor Pasien", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(4, 2, ":", '', 0, 'L');

                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->MultiCell(35, 2, $nama_pasien['no_member'],0);
                $lineY_left = $this->kcc_paket_fpdf->GetY();

                $this->kcc_paket_fpdf->SetY($lineY_begin);
                $this->kcc_paket_fpdf->Cell(48, 2, "", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(20, 2, "Waktu", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(2, 2, ":", '', 0, 'L');
                $this->kcc_paket_fpdf->MultiCell(20, 2, "", 0, "L");  

                $this->kcc_paket_fpdf->Ln(6);
                

                $posisi_y = $this->kcc_paket_fpdf->GetY();

                $lineY_begin = $this->kcc_paket_fpdf->GetY();
                $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                $this->kcc_paket_fpdf->Cell(55, 4, "KETERANGAN", '', 0, 'C');
                $this->kcc_paket_fpdf->Cell(10, 4, "QTY", '', 0, 'C');
                $this->kcc_paket_fpdf->Cell(15, 4, "BIAYA", '', 0, 'C');

                $total_paket_non = 0;
                if(count($data_paket) != 0)
                {
                    $lineY_begin = $this->kcc_paket_fpdf->GetY();
                    $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                    $this->kcc_paket_fpdf->Cell(55, 4, "BIAYA TINDAKAN", '', 0, 'L');
                    $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'C');
                    $this->kcc_paket_fpdf->Cell(15, 4, "", '', 0, 'C');
                    $this->kcc_paket_fpdf->Ln(3);
                    $total_paket_non = 0;
                    foreach ($data_paket as $paket) 
                    {
                        $master_paket = $this->paket_m->get($paket['paket_id']);

                        $lineY_begin = $this->kcc_paket_fpdf->GetY();
                        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                        $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(53, 4, $master_paket->nama, '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(10, 4, '1', '', 0, 'C');
                        $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($paket['rupiah'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                        $this->kcc_paket_fpdf->Ln(2);

                        $total_paket_non += $paket['rupiah'];
                    }
                } 

                $total_item_non = 0;
                if(count($data_item_non_klaim) != 0)
                {
                    $this->kcc_paket_fpdf->Ln(3);
                    $lineY_begin = $this->kcc_paket_fpdf->GetY();
                    $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                    $this->kcc_paket_fpdf->Cell(55, 4, "BIAYA OBAT", '', 0, 'L');
                    $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'C');
                    $this->kcc_paket_fpdf->Cell(15, 4, "", '', 0, 'C');
                    $this->kcc_paket_fpdf->Ln(3);
                    
                    $total_item_non = 0;
                    foreach ($data_item_non_klaim as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);                       
                        
                        $lineY_begin = $this->kcc_paket_fpdf->GetY();
                        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                        $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                        $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                        $this->kcc_paket_fpdf->Ln(3);
                        $total_item_non += $item['jumlah']*$item['harga_jual'];                           
                    }
                    
                } 

                $total_item_vitamin_non = 0;
                if(count($data_item_vitamin_non_klaim) != 0)
                {
                    $this->kcc_paket_fpdf->Ln(3);
                    $lineY_begin = $this->kcc_paket_fpdf->GetY();
                    $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                    $this->kcc_paket_fpdf->Cell(55, 4, "BIAYA VITAMIN", '', 0, 'L');
                    $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'C');
                    $this->kcc_paket_fpdf->Cell(15, 4, "", '', 0, 'C');
                    $this->kcc_paket_fpdf->Ln(3);
                    
                    $total_item_vitamin_non = 0;
                    foreach ($data_item_vitamin_non_klaim as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);                       
                        
                        $lineY_begin = $this->kcc_paket_fpdf->GetY();
                        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                        $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                        $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                        $this->kcc_paket_fpdf->Ln(3);
                        $total_item_vitamin_non += $item['jumlah']*$item['harga_jual'];                           
                    }
                    
                }

                $total_item_alkes_non = 0;
                if(count($data_item_alkes_non_klaim) != 0)
                {
                    $this->kcc_paket_fpdf->Ln(3);
                    $lineY_begin = $this->kcc_paket_fpdf->GetY();
                    $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                    $this->kcc_paket_fpdf->Cell(55, 4, "BIAYA ALKES", '', 0, 'L');
                    $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'C');
                    $this->kcc_paket_fpdf->Cell(15, 4, "", '', 0, 'C');
                    $this->kcc_paket_fpdf->Ln(3);
                    
                    $total_item_alkes_non = 0;
                    foreach ($data_item_alkes_non_klaim as $item) 
                    {
                        $master_item = $this->item_m->get($item['item_id']);                       
                        
                        $lineY_begin = $this->kcc_paket_fpdf->GetY();
                        $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                        $this->kcc_paket_fpdf->Cell(2, 4, "", '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(53, 4, $master_item->nama, '', 0, 'L');
                        $this->kcc_paket_fpdf->Cell(10, 4, $item['jumlah'], '', 0, 'C');
                        $this->kcc_paket_fpdf->Cell(15, 4, 'IDR ' . number_format($item['jumlah']*$item['harga_jual'], 0 , '' , ',' ) . ' #', '', 0, 'C');
                        $this->kcc_paket_fpdf->Ln(3);
                        $total_item_alkes_non += $item['jumlah']*$item['harga_jual'];                           
                    }
                    
                }

                $grand_total_non = $total_paket_non + $total_item_non + $total_item_alkes_non + $total_item_vitamin_non;
                $pembulatan_non = 0;
                $total_non = $grand_total_non + $pembulatan_non;


                // $this->kcc_paket_fpdf->SetY($posisi_y + 4 + 5 + 4 + 5 + 4 + 10 + (substr_count($terbilang, "\n") * 4) + 5 );


                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $this->kcc_paket_fpdf->Ln(3);
                $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                $this->kcc_paket_fpdf->Cell(70, 4, "TOTAL TRANSAKSI", '', 0, 'R');
                $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                $this->kcc_paket_fpdf->Cell(12, 4, "IDR ".number_format($grand_total_non, 0, ',', ',')." #", '', 0, 'C');
                $this->kcc_paket_fpdf->Ln(5);

                

                $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                $this->kcc_paket_fpdf->Cell(70, 4, "PEMBULATAN", '', 0, 'R');
                $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                $this->kcc_paket_fpdf->Cell(12, 4, ($pembulatan_non!=0)?"IDR ".number_format($pembulatan_non, 0, ',', ',')." #":"-", '', 0, 'C');
                $this->kcc_paket_fpdf->Ln(5);

                

                $this->kcc_paket_fpdf->SetFont("Arial", "B", 5);
                $this->kcc_paket_fpdf->Cell(70, 4, "TOTAL BIAYA", '', 0, 'R');
                $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                $this->kcc_paket_fpdf->Cell(12, 4, "IDR ".number_format($total_non, 0, ',', ',')." #", '', 0, 'C');
                $this->kcc_paket_fpdf->Ln(10);



                $terbilang_non = terbilang($total_non);

                $this->kcc_paket_fpdf->SetFont("Arial", "", 6);
                $this->kcc_paket_fpdf->Cell(11, 4, "Terbilang :", '', 0, 'L');
                $this->kcc_paket_fpdf->MultiCell(65, 4, "# ".$terbilang_non." Rupiah #",0, 'L');
                $this->kcc_paket_fpdf->Ln(5);

                
                $this->kcc_paket_fpdf->SetFont("Arial", "", 5);
                $this->kcc_paket_fpdf->Cell(10, 4, "", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(50, 4, "Jakarta, ".date("d M Y"), 0, "J");
                $this->kcc_paket_fpdf->Ln(4);

                $this->kcc_paket_fpdf->Cell(20, 4, "", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(35, 4, "Kasir", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(30, 4, "Pasien", '', 0, 'L');
                $this->kcc_paket_fpdf->Ln(20);

                $user_id = $this->session->userdata("user_id");
                $user_login = $this->user_m->get_by(array('id' => $user_id), true);
                // die_dump($this->db->last_query());
                // die_dump($user_login);

                $this->kcc_paket_fpdf->Cell(20, 4, "", '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(35, 4, $user_login->nama, '', 0, 'L');
                $this->kcc_paket_fpdf->Cell(30, 4, $nama_pasien['nama'], '', 0, 'L');
                $this->kcc_paket_fpdf->Ln();                          



            }

            
        }


        //Save PDF

        $this->kcc_paket_fpdf->Output($data_bayar['no_pembayaran'].".pdf", "I");

      
    }

    public function modal_detail($row_id,$id)
    {
        $data_invoice  = $this->invoice_m->get($id);
        $data_paket    = $this->invoice_detail_m->get_data_paket_bayar($id)->result_array();
        $data_items    = $this->invoice_detail_m->get_data_items_bayar($id)->result_array();
        $data_tindakan = $this->invoice_detail_m->get_data_tindakan_bayar($id)->result_array();

        $data = array(
            'data_invoice'  => object_to_array($data_invoice),
            'row_id'        => $row_id,
            'index'        => substr($row_id, 9),
            'data_paket'    => $data_paket,
            'data_items'    => $data_items,
            'data_tindakan' => $data_tindakan
        );

        $this->load->view('reservasi/pembayaran/modal_detail', $data);
    }

    public function search_pasien_by_nomor()
    {
        if($this->input->is_ajax_request())
        {
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Data Pasien Tidak Ditemukan', $this->session->userdata('language'));

            $no_pasien = $this->input->post('no_pasien');
            $tipe = $this->input->post('tipe');

            $pasien = $this->pasien_m->get_pasien_by_nomor($no_pasien,$tipe)->row(0);

            if(count($pasien))
            {
                $now = date('Y-m-d');
                $lahir = date('Y-m-d', strtotime($pasien->tanggal_lahir));

                $data_draf_invoice_umum = $this->draf_invoice_m->get_by(array('pasien_id' => $pasien->id, 'jenis_invoice' => 1), true);
                $data_draf_invoice_umum->created_date = date('d-M-Y', strtotime($data_draf_invoice_umum->created_date));
                $data_draf_invoice_bpjs = $this->draf_invoice_m->get_by(array('pasien_id' => $pasien->id, 'jenis_invoice' => 2), true);
                $data_draf_invoice_bpjs->created_date = date('d-M-Y', strtotime($data_draf_invoice_bpjs->created_date));

                $data_draf_invoice_umum_detail_item_transfusi = $this->draf_invoice_detail_m->get_by("draf_invoice_id = '$data_draf_invoice_umum->id' AND tipe_item = 3 AND tipe_tindakan = 2 OR draf_invoice_id = '$data_draf_invoice_umum->id' AND tipe_item = 2 AND tipe_tindakan = 2.");
                $data_draf_invoice_umum_detail_item_transfusi = object_to_array($data_draf_invoice_umum_detail_item_transfusi);

                $total_transfusi = 0;
                foreach ($data_draf_invoice_umum_detail_item_transfusi as $key => $item_transfusi) {
                    $total_transfusi = $total_transfusi + (($item_transfusi['jumlah']*$item_transfusi['harga_jual']) - $item_transfusi['diskon_nominal']);
                }

                $jasa_transfusi = (3/100) * $total_transfusi;

                $where_transfusi = array(
                    'draf_invoice_id' => $data_draf_invoice_umum_detail_item_transfusi[0]['draf_invoice_id'],
                    'tipe_item' => 2,
                    'nama_tindakan' => 'Jasa Transfusi',
                );

                $data_transfusi = array(
                    'harga_jual' => $jasa_transfusi
                );

                $edit_jasa_transfusi = $this->draf_invoice_detail_m->update_by(0,$data_transfusi,$where_transfusi);

                
                $data_draf_invoice_umum_detail_tindakan = $this->draf_invoice_detail_m->get_by("draf_invoice_id = '$data_draf_invoice_umum->id' AND tipe_item = 1 OR draf_invoice_id = '$data_draf_invoice_umum->id' AND tipe_item = 2");
                $data_draf_invoice_umum_detail_tindakan = object_to_array($data_draf_invoice_umum_detail_tindakan);

                $data_draf_invoice_umum_detail_item = $this->draf_invoice_detail_m->get_by("draf_invoice_id = '$data_draf_invoice_umum->id' AND tipe_item = 3");
                $data_draf_invoice_umum_detail_item = object_to_array($data_draf_invoice_umum_detail_item);

                $data_draf_invoice_umum_detail_item_transfusi = $this->draf_invoice_detail_m->get_by("draf_invoice_id = '$data_draf_invoice_umum->id' AND tipe_item = 3 AND tipe_tindakan = 2");
                $data_draf_invoice_umum_detail_item_transfusi = object_to_array($data_draf_invoice_umum_detail_item_transfusi);

                $data_draf_invoice_bpjs_detail = $this->draf_invoice_detail_m->get_by("draf_invoice_id = '$data_draf_invoice_bpjs->id' AND tipe_item = 1 OR draf_invoice_id = '$data_draf_invoice_bpjs->id' AND tipe_item = 2");
                $data_draf_invoice_bpjs_detail = object_to_array($data_draf_invoice_bpjs_detail);

                $data_draf_invoice_bpjs_detail_item = $this->draf_invoice_detail_m->get_by("draf_invoice_id = '$data_draf_invoice_bpjs->id' AND tipe_item = 3");
                $data_draf_invoice_bpjs_detail_item = object_to_array($data_draf_invoice_bpjs_detail_item);

                $total_umum = 0;
                foreach ($data_draf_invoice_umum_detail_tindakan as $inv_umum_tindakan) {
                    
                    $total_umum = $total_umum + (($inv_umum_tindakan['jumlah']*$inv_umum_tindakan['harga_jual']) - $inv_umum_tindakan['diskon_nominal']);

                }
                foreach ($data_draf_invoice_umum_detail_item as $inv_umum_item) {
                    
                    $total_umum = $total_umum + (($inv_umum_item['jumlah']*$inv_umum_item['harga_jual']) - $inv_umum_item['diskon_nominal']);

                }

                $grand_total_umum = $total_umum + $data_draf_invoice_umum->akomodasi;

                $total_bpjs = 0;
                foreach ($data_draf_invoice_bpjs_detail as $inv_bpjs_tindakan) {
                    
                    $total_bpjs = $total_bpjs + ($inv_bpjs_tindakan['jumlah']*$inv_bpjs_tindakan['harga_jual']);

                }
                foreach ($data_draf_invoice_bpjs_detail_item as $inv_bpjs_item) {
                    
                    $total_bpjs = $total_bpjs + ($inv_bpjs_item['jumlah']*$inv_bpjs_item['harga_jual']);

                }


                $response->success = true;
                $response->msg = translate('Data Pasien Ditemukan', $this->session->userdata('language'));
                $response->rows = $pasien;
                $response->invoice_umum_detail = $data_draf_invoice_umum_detail_tindakan;        
                $response->invoice_umum_detail_item = $data_draf_invoice_umum_detail_item;
                $response->invoice_umum = object_to_array($data_draf_invoice_umum);
                $response->invoice_bpjs = object_to_array($data_draf_invoice_bpjs);
                $response->invoice_bpjs_detail = $data_draf_invoice_bpjs_detail;
                $response->invoice_bpjs_detail_item = $data_draf_invoice_bpjs_detail_item;
                $response->terbilang_umum = terbilang($grand_total_umum);
                $response->terbilang_bpjs = terbilang($total_bpjs);


            }


            die(json_encode($response));

        }
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

    public function print_invoice_dot($id_invoice)
    {
        // $this->load->library('mpdf/mpdf.php');

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

        $pembayaran_detail = $this->pembayaran_detail_m->get_by(array('invoice_id' => $id_invoice),true);
        $pembayaran_tipe = $this->pembayaran_tipe_m->get_by(array('pembayaran_id' => $pembayaran_detail->pembayaran_id), true);
        $data = array(
            'invoice' => object_to_array($invoice),
            'pasien' => object_to_array($pasien),
            'invoice_detail_tindakan' => object_to_array($invoice_detail_tindakan),
            'invoice_detail_item' => object_to_array($invoice_detail_item),
            'penjamin_id' => $penjamin_id,
            'pembayaran_tipe' => object_to_array($pembayaran_tipe)
        );

        // $mpdf = new mPDF('','A4', 0, 'dejavusans', 0, 0, 0, 0, 0, 0);
        // $mpdf->writeHTML($this->load->view('reservasi/pembayaran/print_invoice_dot', $data, true));
        

        // $mpdf->Output('Invoice_'.$data_invoice->no_invoice.'.pdf', 'I'); 

        $this->load->view('reservasi/pembayaran/print_invoice_dot', $data);


    }

    public function get_antrian()
    {
        if($this->input->is_ajax_request()){


            $id_antrian = $this->input->post('antrian_id');
            $counter = $this->input->post('counter');

            $data_antrian = $this->antrian_pasien_m->get_by(array('id' => $id_antrian), true);

            if($data_antrian->posisi_loket != 6 && $data_antrian->status != 0){
                $data_antrian = $this->antrian_pasien_m->get_by(array('posisi_loket' => 6, 'status' => 0),true);
            }

            if(count($data_antrian)){
                $data = array(
                    'is_panggil' => 1,
                    'modified_date' => date('Y-m-d H:i:s'),
                );

                $edit_antrian = $this->antrian_pasien_m->edit_data($data, $data_antrian->id);

                $last_id_panggil       = $this->antrian_pasien_m->get_max_id_panggilan()->result_array();
                $last_id_panggil       = intval($last_id_panggil[0]['max_id'])+1;
                
                $format_id_panggil     = 'PGL-'.date('m').'-'.date('Y').'-%04d';
                $id_antrian_panggil    = sprintf($format_id_panggil, $last_id_panggil, 4);

                $no_urut = $this->antrian_pasien_m->get_max_no_urut_panggil()->result_array();
                $no_urut = intval($no_urut[0]['max_no_urut'])+1;

                $data_antrian_panggil = array(
                    'id'    => $id_antrian_panggil,
                    'antrian_id'    => $id_antrian,
                    'panggilan'    => 'Panggilan untuk pasien '.$data_antrian->nama_pasien.', Ke Loket Pembayaran',
                    'urutan' => $no_urut
                );

                $save_panggilan = $this->antrian_pasien_m->add_data_panggilan($data_antrian_panggil);


                $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
                $date = getDate();
                $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                file_put_contents($file,$jam);

                $response->success = true;
                $response->file = file_put_contents($file,$jam);
            }

            die(json_encode($response));

        }
    }

    public function tindak_antrian()
    {
        if($this->input->is_ajax_request()){


            $id_antrian = $this->input->post('antrian_id');

            $data_antrian = $this->antrian_pasien_m->get_by(array('id' => $id_antrian), true);

            if($data_antrian->is_panggil == 1){
                $data = array(
                    'status' => 1,
                    'modified_date' => date('Y-m-d H:i:s'),
                );

                $edit_antrian = $this->antrian_pasien_m->edit_data($data, $id_antrian);
                $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
                $date = getDate();
                $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                file_put_contents($file,$jam);

                $response->success = true;
                $response->file = file_put_contents($file,$jam);
            }else{
                $response->success = false;
            }

            die(json_encode($response));

        }
    }

    public function lewati_antrian()
    {
        if($this->input->is_ajax_request()){


            $id_antrian = $this->input->post('antrian_id');

            $data_antrian = $this->antrian_pasien_m->get_by(array('id' => $id_antrian), true);

            $antrian_plus_tiga = $this->antrian_pasien_m->get_data_loket_panggil_tiga(6,($data_antrian->no_urut+4))->result_array();
           // $antrian_plus_tiga = object_to_array($antrian_plus_tiga);

            foreach ($antrian_plus_tiga as $plus_tiga) {
                $update = array(
                    'no_urut' => ($plus_tiga['no_urut'] + 1)
                );

                $edit_antrian_tiga = $this->antrian_pasien_m->edit_data($update, $plus_tiga['id']);

            }

            if($data_antrian->is_panggil == 1){
                $data = array(
                    'status' => 0,
                    'is_panggil' => NULL,
                    'no_urut' => ($data_antrian->no_urut+3),
                    'modified_date' => date('Y-m-d H:i:s'),
                );

                $edit_antrian = $this->antrian_pasien_m->edit_data($data, $id_antrian);

                $file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_antrian_location').'notif_antrian.txt';
                $date = getDate();
                $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
                file_put_contents($file,$jam);

                $response->success = true;
                $response->file = file_put_contents($file,$jam);
            }else{
                $response->success = false;
            }

            die(json_encode($response));

        }
    }


}

/* End of file pembayaran.php */
/* Location: ./application/controllers/reservasi/pembayaran.php */