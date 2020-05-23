<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Permintaan_po extends MY_Controller {


    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree

    protected $menu_id = '22c8b50c37cad8e71606c18864ab600b';                  // untuk check bit_access
   
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

        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_m');
        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_detail_m');
        $this->load->model('pembelian/permintaan_po/order_permintaan_barang_detail_other_m');
        $this->load->model('pembelian/permintaan_po/persetujuan_permintaan_barang_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_m');
        $this->load->model('pembelian/permintaan_po/permintaan_status_detail_m');
        $this->load->model('pembelian/persetujuan_po/persetujuan_permintaan_barang_history_m');
        $this->load->model('pembelian/permintaan_po/user_level_persetujuan_m');
        $this->load->model('pembelian/permintaan_po/o_p_p_d_o_item_file_m');
        $this->load->model('pembelian/permintaan_po/box_paket_m');
        $this->load->model('pembelian/permintaan_po/box_paket_detail_m');
        $this->load->model('pembelian/permintaan_po/pilih_user_m');
        $this->load->model('pembelian/permintaan_po/supplier_item_m');
        $this->load->model('pembelian/supplier_harga_item_m');
        $this->load->model('pembelian/daftar_permintaan_po_m');
        $this->load->model('pembelian/item_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('pembelian/persetujuan_po/inventory_m');

        $this->load->model('master/divisi_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_divisi_setting_m');
        $this->load->model('master/cabang__m');
        $this->load->model('master/cabang_poliklinik_m');

        $this->load->model('master/paket_m');
        $this->load->model('master/paket_item_m');
        $this->load->model('master/paket_tindakan_m');
        $this->load->model('master/paket_batch_m');
        $this->load->model('master/paket_batch_item_m');
        $this->load->model('master/paket_batch_tindakan_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/poliklinik_paket_m');

        $this->load->model('master/tindakan_m');
        $this->load->model('master/item/item_m');
        $this->load->model('master/item/item_satuan_m');
        $this->load->model('master/item/item_gambar_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/item/item_sub_kategori_pembelian_m');
        $this->load->model('master/item/item_sub_kategori_m');

        $this->load->model('master/supplier/supplier_m');
        $this->load->model('master/supplier/supplier_tipe_pembayaran_m');
        $this->load->model('pembelian/supplier_harga_item_m');
        $this->load->model('pembelian/permintaan_po/supplier_item_m');

        $this->load->model('pembelian/pembelian_m');
        $this->load->model('pembelian/pembelian_detail_m');
        $this->load->model('penjualan/o_s_pmsn_m');
        $this->load->model('pembelian/setting_persetujuan_po_m');

    }

    

    public function index()

    {

        $assets = array();

        $config = 'assets/pembelian/permintaan_po/index';

        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        

        // die(dump( $assets['css'] ));

        $data = array(

            'title'          => config_item('site_name').' | '.translate('Permintaan PO', $this->session->userdata('language')), 

            'header'         => translate('Permintaan PO', $this->session->userdata('language')), 

            'header_info'    => config_item('site_name'), 

            'breadcrumb'     => true,

            'menus'          => $this->menus,

            'menu_tree'      => $this->menu_tree,

            'css_files'      => $assets['css'],

            'js_files'       => $assets['js'],

            'content_view'   => 'pembelian/permintaan_po/index',

            );

        

        // Load the view

        $this->load->view('_layout', $data);

    }



    public function history()

    {

        $assets = array();

        $config = 'assets/pembelian/permintaan_po/history';

        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);

        

        // die(dump( $assets['css'] ));

        $data = array(

            'title'          => config_item('site_name').' | '.translate('History Permintaan PO', $this->session->userdata('language')), 

            'header'         => translate('History Permintaan PO', $this->session->userdata('language')), 

            'header_info'    => config_item('site_name'), 

            'breadcrumb'     => true,

            'menus'          => $this->menus,

            'menu_tree'      => $this->menu_tree,

            'css_files'      => $assets['css'],

            'js_files'       => $assets['js'],

            'content_view'   => 'pembelian/permintaan_po/history',

            );

        

        // Load the view

        $this->load->view('_layout', $data);

    }



    public function add()

    {

        $assets = array();

        $assets_config = 'assets/pembelian/permintaan_po/add';

        $this->config->load($assets_config, true);



        $assets = $this->config->item('assets', $assets_config);



        $data = array(

            'title'          => config_item('site_name').' | '. translate("Tambah Permintaan Barang & Jasa", $this->session->userdata("language")), 

            'header'         => translate("Tambah Permintaan Barang & Jasa", $this->session->userdata("language")), 

            'header_info'    => config_item('site_name'), 

            'breadcrumb'     => TRUE,

            'menus'          => $this->menus,

            'menu_tree'      => $this->menu_tree,

            'css_files'      => $assets['css'],

            'js_files'       => $assets['js'],

            'content_view'   => 'pembelian/permintaan_po/add',

            'flag'           => 'add',

            'pk_value'       => '',

            

        );



        // Load the view

        $this->load->view('_layout', $data);

    }



    public function view($id)

    {

        

        $assets = array();

        $config = 'assets/pembelian/permintaan_po/view';

        $this->config->load($config, true);



        $assets = $this->config->item('assets', $config);

               

        $form_data = $this->order_permintaan_barang_m->get_by(array('id' => $id), true);

        $form_data = object_to_array($form_data);

        if($form_data['tipe'] == 1)

        {

            $form_data_detail = $this->order_permintaan_barang_detail_m->get_data_detail($id);

            $form_data_detail = object_to_array($form_data_detail);

            $form_data_detail_other = '';

        }

        else

        {

            $form_data_detail = 'Tests';

            $form_data_detail_other = $this->order_permintaan_barang_detail_other_m->get_data_detail($id);        

            $form_data_detail_other = object_to_array($form_data_detail_other);

        }



        $data = array(

            'title'                  => config_item('site_name').' | '. translate("View Permintaan Barang", $this->session->userdata("language")), 

            'header'                 => translate("View Permintaan Barang", $this->session->userdata("language")), 

            'header_info'            => config_item('site_name'), 

            'breadcrumb'             => TRUE,

            'menus'                  => $this->menus,

            'menu_tree'              => $this->menu_tree,

            'css_files'              => $assets['css'],

            'js_files'               => $assets['js'],

            'content_view'           => 'pembelian/permintaan_po/view',

            'form_data'              => $form_data,

            'form_data_detail'       => $form_data_detail,

            'form_data_detail_other' => $form_data_detail_other,

            'pk_value'               => $id

            //table primary key value

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

        $result = $this->order_permintaan_barang_m->get_datatable();

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

            $action = '';

            $info = '';

            $jumlah = 0;

            $status = '';

            if($row['tipe'] == 1)

            {

             

                $order_permintaan_barang_detail = $this->order_permintaan_barang_detail_m->get_by(array('order_permintaan_barang_id' => $row['id']));

                $data_array = object_to_array($order_permintaan_barang_detail);

                

                $item = $this->item_m->get_item_order_permintaan_barang_detail($data_array[0]['order_permintaan_barang_id'])->result_array();

                $jumlah = $row['jumlah_terdaftar'];

                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';

               



            }



            if($row['tipe'] == 2)

            {



                $order_permintaan_barang_detail_other = $this->order_permintaan_barang_detail_other_m->get_by(array('order_permintaan_barang_id' => $row['id']));

                $data_array_other = object_to_array($order_permintaan_barang_detail_other);

                // die_dump($data_array_other);

                $item = $this->item_m->get_item_order_permintaan_barang_detail_other($data_array_other[0]['order_permintaan_barang_id'])->result_array();

                // die_dump($this->db->last_query());    

                $jumlah = $row['jumlah_tidak_terdaftar'];

                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="item-unlist" data-id="'.$row['id'].'" name="info"><u>'.$jumlah.' item</u></a>';





            }

            $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'pembelian/permintaan_po/view/'.$row['id'].'"><i class="fa fa-search"></i> </a>';           



            if($row['status'] == 1)

            {

                $action .= '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data permintaan po ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';      

                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';

               

                

            }

            if($row['status'] == 2) {



                $action .= '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data permintaan po ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';      

                $status = '<div class="text-left"><span class="label label-md label-info">Sudah Dibaca</span></div>';

            

            } elseif($row['status'] == 3) {



                $status = '<div class="text-left"><span class="label label-md label-success">Disetujui</span></div>';



            } elseif($row['status'] == 4) {



                $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';

            }

                

            // PopOver Notes

            $preNotes = $row['keterangan'];



            if(strlen($row['keterangan'] > 10))

            {

                $notes = $row['keterangan'];

              

                $words = explode(' ', $notes);

              

                $impWords = implode(' ', array_splice($words, 0, 6));

                

                $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';



            }

              

            $output['data'][] = array(

                $row['id'],

                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',

                $row['user'].' ('.$row['user_level'].')',

                $row['subjek'],

                $info,

                $preNotes,

                '<div class="text-left">'.$status.'</div>',

                '<div class="text-left">'.$action.'</div>',

            );

         $i++;

        }



        echo json_encode($output);

    }



    /**
     * [list description]
     * @return [type] [description]
     */

    public function listing_status()

    {        

        $result = $this->permintaan_status_m->get_datatable();

        $output = array(

            'draw'                 => intval($this->input->post('draw', true)),

            'iTotalRecords'        => $result->total_records,

            'iTotalDisplayRecords' => $result->total_display_records,

            'data'                 => array()

        );

    

        $records = $result->records;

        //die(dump($records));

        $i=0;

        foreach($records->result_array() as $row)

        {

            $action = '';

            $info = '';

            $jumlah = 0;

            $status = '';



            $status_detail_awal = $this->permintaan_status_detail_m->get_data($row['id'],0)->result_array();

            $status_detail_revisi = $this->permintaan_status_detail_m->get_data($row['id'],1)->result_array();



            if($row['tipe'] == 1)

            {

             

                $order_permintaan_barang_detail = $this->order_permintaan_barang_detail_m->get_by(array('order_permintaan_barang_id' => $row['transaksi_id']));

                $data_array = object_to_array($order_permintaan_barang_detail);

                

                $item = $this->item_m->get_item_order_permintaan_barang_detail($data_array[0]['order_permintaan_barang_id'])->result_array();

                $jumlah = count($data_array);

                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="pilih-item" data-id="'.$row['transaksi_id'].'" name="info" style="color:#33348e; text-decoration: none;"><u>'.$jumlah.' item</u></a>';

               



            }



            if($row['tipe'] == 2)

            {



                $order_permintaan_barang_detail_other = $this->order_permintaan_barang_detail_other_m->get_by(array('order_permintaan_barang_id' => $row['transaksi_id']));

                $data_array_other = object_to_array($order_permintaan_barang_detail_other);

                //die_dump($data_array_other);

                $item = $this->item_m->get_item_order_permintaan_barang_detail_other($data_array_other[0]['order_permintaan_barang_id'])->result_array();

                //die_dump($this->db->last_query());    

                $jumlah = count($data_array_other);

                $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($item)).'" class="item-unlist inline-button-table" data-id="'.$row['transaksi_id'].'" name="info" style="color:#33348e; text-decoration: none;"><u>'.$jumlah.' item</u></a>';


            }

            $action .= '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'pembelian/permintaan_po/view/'.$row['transaksi_id'].'"><i class="fa fa-search"></i> </a>';           



            if($row['status'] == 1)

            {

                $action .= '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data permintaan po ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['transaksi_id'].'" class="btn red"><i class="fa fa-times"></i> </a>';      

                $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';

            }

            if($row['status'] == 2) {



                $action .= '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data permintaan po ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['transaksi_id'].'" class="btn red"><i class="fa fa-times"></i> </a>';      

                $status = '<div class="text-left"><span class="label label-md label-info">Sudah Dibaca</span></div>';

            

            } elseif($row['status'] == 3) {



                if($row['tipe_persetujuan'] == 2){

                    $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

                }

                if($row['tipe_persetujuan'] == 1 || $row['tipe_persetujuan'] == 0){

                    $status = '<div class="text-left"><span class="label label-md bg-blue-steel">Menunggu Proses</span></div>';

                }

                



            } elseif($row['status'] == 4) {



                $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';

            }


            $output['data'][] = array(


                '<span class="row-details row-details-close" data-row="'.htmlentities(json_encode($status_detail_awal)).'" data-row_revisi="'.htmlentities(json_encode($status_detail_revisi)).'" data-posisi="'.$row['user_level_id'].'"></span>',

                '<div class="text-center inline-button-table">'.date('d M Y, H:i' , strtotime($row['tanggal'])).'</div>',

                '<div class="text-left">'.$row['inisial'].' / '.$row['kode_divisi_buat'].'</div>',

                '<div class="text-left inline-button-table">'.$row['transaksi_nomor'].'</div>',

                '<div class="text-left">'.$row['subjek'].'</div>',

                '<div class="inline-button-table">'.$info.'</div>',

                '<div class="text-left">'.$status.'</div>',

                '<div class="text-left">'.$row['nama_level_proses'].'</div>',

                '<div class="text-left inline-button-table">'.$action.'</div>',

            );

         $i++;

        }



        echo json_encode($output);

    }





    public function listing_proses($status=null)
    {        

        $result = $this->order_permintaan_barang_m->get_datatable_proses($status);

        // die_dump($this->db->last_query());

        $output = array(
            'iTotalDisplayRecords' => $result->total_display_records,

            'data'                 => array(),
            'draw'                 => intval($this->input->post('draw', true)),

            'iTotalRecords'        => $result->total_records,

        );

    

        $records = $result->records;

        // die_dump($records->result_array());



        $i=0;

        foreach($records->result_array() as $row)

        {

            $action = '';

            $info = '';



            $aksi = '<a title="'.translate('View', $this->session->userdata('language')).'" class="btn default" href="'.base_url().'pembelian/permintaan_po/view/'.$row['id'].'"><i class="fa fa-search"></i> </a>';           



            

            if($row['tipe'] == 1)

            {

                $order_permintaan_barang_detail = $this->order_permintaan_barang_detail_m->get_by(array('order_permintaan_barang_id' => $row['id']));

                // die_dump($order_permintaan_barang_detail);

                $data_array = object_to_array($order_permintaan_barang_detail);

                

                $item = $this->item_m->get_item_order_permintaan_barang_detail($data_array[0]['order_permintaan_barang_id'])->result_array();

                $jumlah = $row['jumlah_terdaftar'];

                $info = '<a data-item="'.htmlentities(json_encode($item)).'" class="pilih-item-history" data-id="'.$row['id'].'" name="info_history"><u>'.$jumlah.' item</u></a>';

               



            }



            if($row['tipe'] == 2)

            {



                $order_permintaan_barang_detail_other = $this->order_permintaan_barang_detail_other_m->get_by(array('order_permintaan_barang_id' => $row['id']));

                $data_array_other = object_to_array($order_permintaan_barang_detail_other);

                // die_dump($data_array_other);

                $item = $this->item_m->get_item_order_permintaan_barang_detail_other($data_array_other[0]['order_permintaan_barang_id'])->result_array();

                // die_dump($this->db->last_query());    

                $jumlah = $row['jumlah_tidak_terdaftar'];

                $info = '<a data-item="'.htmlentities(json_encode($item)).'" class="item-unlist-history" data-id="'.$row['id'].'" name="info_history"><u>'.$jumlah.' item</u></a>';





            }

                

               if($row['status_terakhir'] < 4)

               {

                    $action = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';

               }

               elseif ($row['status_terakhir'] == 4)

               {

                    $action = '<div class="text-center"><span class="label label-md label-success">Diproses</span></div>';

               }



            $preNotes = $row['keterangan'];



            if(strlen($row['keterangan'] > 10))

            {

                $notes = $row['keterangan'];

              

                $words = explode(' ', $notes);

              

                $impWords = implode(' ', array_splice($words, 0, 6));

                

                $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';



            }

            $output['data'][] = array(

                $row['id'],

                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',

                $row['user'].' ('.$row['user_level'].')',

                $row['subjek'],

                $info,

                $preNotes,

                '<div class="text-center">'.$action.'</div>', 

                '<div class="text-center">'.$aksi.'</div>' 

            );

        // die_dump($this->db->last_query());



         $i++;

        }



        echo json_encode($output);

    }



    public function listing_pilih_item($id=null)

    {

        

        $result = $this->item_m->get_datatable($id);

        // die_dump($result);



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



            $output['aaData'][] = array(

                '<div class="text-center">'.$row['id'].'</div>',

                '<div class="text-left">'.$row['kode'].'</div>',

                '<div class="text-left">'.$row['nama'].'</div>',

                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',

            );

         $i++;

        }



        echo json_encode($output);

    }



    public function listing_pilih_item_tidak_terdaftar($id=null)

    {

        

        $result = $this->daftar_permintaan_po_m->get_datatable_item($id);

        // die_dump($result);



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



            $output['aaData'][] = array(

                '<div class="text-center">'.$row['id'].'</div>',

                '<div class="text-left">'.$row['nama'].'</div>',

                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',

            );

         $i++;

        }



        echo json_encode($output);

    }



    public function listing_pilih_item_history($id=null)

    {

        

        $result = $this->persetujuan_permintaan_barang_m->get_datatable_item_terdaftar($id);

        // die_dump($result);



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



            $output['aaData'][] = array(

                '<div class="text-center">'.$row['id'].'</div>',

                '<div class="text-left">'.$row['kode'].'</div>',

                '<div class="text-left">'.$row['nama'].'</div>',

                '<div class="text-left">'.$row['jumlah'].' '.$row['satuan'].'</div>',

            );

         $i++;

        }



        echo json_encode($output);

    }



    public function listing_pilih_item_tidak_terdaftar_history($id=null)

    {

        

        $result = $this->persetujuan_permintaan_barang_m->get_datatable_item_tidak_terdaftar($id);

        // die_dump($result);



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



            $output['aaData'][] = array(

                '<div class="text-center">'.$row['id'].'</div>',

                '<div class="text-left">'.$row['nama'].'</div>',

                '<div class="text-left">'.$row['jumlah'].'</div>',

            );

         $i++;

        }



        echo json_encode($output);

    }



    public function save()
    {

        $command     = $this->input->post('command');
        $array_input = $this->input->post();
        $items       = $this->input->post('account');
        $items2      = $this->input->post('tindakan');
        $level_id = $this->session->userdata('level_id');
        $user_level_buat = $this->user_level_m->get($level_id);
        $divisi_buat = $this->divisi_m->get($user_level_buat->divisi_id);
        $jenis_permintaan = $array_input['jenis_permintaan'];

        if ($command === 'add')
        {  

            $max_kode_permintaan = $this->order_permintaan_barang_m->get_max_kode()->result_array();

            if(count($max_kode_permintaan) == 0){
                $max_kode_permintaan = 1;
            }else{
                $max_kode_permintaan = intval($max_kode_permintaan[0]['max_kode']) + 1;
            }


            $format           = 'OPB-'.date('m').'-'.date('Y').'-%04d';
            $no_permintaan    = sprintf($format, $max_kode_permintaan, 4);

            $last_number   = $this->order_permintaan_barang_m->get_no_permintaan()->result_array();
            $last_number   = intval($last_number[0]['max_no_permintaan'])+1;
            $format        = '#OI#%03d/RHS-'.strtoupper($divisi_buat->kode).'/'.romanic_number(date('m'), true).'/'.date('Y');
            $nomor_permintaan     = sprintf($format, $last_number, 3);

            if($jenis_permintaan == 1){
                $tipe_po = 3;
                $user_level_posisi = 9;
            }if($jenis_permintaan == 2){
                $tipe_po = 1;
                $user_level_posisi = 32;
            }

            $data_user_level_persetujuan = $this->setting_persetujuan_po_m->get_by(array('tipe_po' => $tipe_po, 'is_active' => 1));
            $user_level_persetujuan = object_to_array($data_user_level_persetujuan);
            $order_setuju = count($user_level_persetujuan);
            

            if (!empty($user_level_persetujuan) && $array_input['tipe_item'] == 1){

                $data = array(
                    'id'            => $no_permintaan,
                    'nomor_permintaan'            => $nomor_permintaan,
                    'tanggal'       => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal'))),
                    'divisi_id'     => 1,
                    'cabang_id'     => $this->session->userdata('cabang_id'),
                    'user_level_id' => $this->input->post('user_level_id'),
                    'user_id'       => $this->input->post('id_ref_user'),
                    'subjek'        => $this->input->post('subjek'),
                    'keterangan'    => $this->input->post('keterangan'),
                    'status'        => 1,
                    'tipe_persetujuan'  => 0,
                    'tipe'          => 1,
                    'is_active'     => 1,
                    'is_finish'     => 0,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s'),
                );

                $order_permintaan_pembelian = $this->order_permintaan_barang_m->add_data($data);

                $divisi_posisi = $this->user_level_m->get($user_level_posisi);

                $last_id_status       = $this->permintaan_status_m->get_max_id_permintaan()->result_array();
                $last_id_status       = intval($last_id_status[0]['max_id'])+1;

                $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
                $id_status            = sprintf($format_id_status, $last_id_status, 4);

                $data_status = array(
                    'id'               => $id_status,
                    'transaksi_id'     => $no_permintaan,
                    'transaksi_nomor'  => $nomor_permintaan,
                    'tipe_transaksi'   => 1,
                    'tipe_persetujuan' => 0,
                    'status'           => 1,
                    'user_level_id'    => $user_level_posisi,
                    'divisi'           => $divisi_posisi->divisi_id,
                    'is_active'        => 1,
                    'created_by'       => $this->session->userdata('user_id'),
                    'created_date'     => date('Y-m-d H:i:s')
                );

                $pembayaran_status = $this->permintaan_status_m->add_data($data_status);

                $last_id_status_detail       = $this->permintaan_status_detail_m->get_max_id_permintaan_detail()->result_array();
                $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                $format_id_status_detail     = 'OID-'.date('m').'-'.date('Y').'-%04d';
                $id_status_detail            = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                $data_status_detail = array(
                    'id'                   => $id_status_detail,
                    'permintaan_status_id' => $id_status,
                    'transaksi_id'         => $no_permintaan,
                    'tipe_transaksi'       => 1,
                    '`order`'              => 1,
                    'divisi'               => $divisi_posisi->divisi_id,
                    'user_level_id'        => $user_level_posisi,
                    'tipe'                 => 1,
                    'tipe_pengajuan'       => 0,
                    'is_active'            => 1,
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s')
                );

                $pembayaran_status_detail = $this->permintaan_status_detail_m->add_data($data_status_detail);
                
                foreach ($user_level_persetujuan as $kategori) {
                    $user_level = $this->user_level_m->get($kategori['user_level_menyetujui_id']);

                    $last_id_status_detail       = $this->permintaan_status_detail_m->get_max_id_permintaan_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    $format_id_status_detail     = 'OID-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail            = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'permintaan_status_id' => $id_status,
                        'transaksi_id'         => $no_permintaan,
                        'tipe_transaksi'       => 1,
                        '`order`'              => ($kategori['level_order'] + 1),
                        'divisi'               => $user_level->divisi_id,
                        'user_level_id'        => $kategori['user_level_menyetujui_id'],
                        'tipe'                 => 1,
                        'tipe_pengajuan'       => 0,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->permintaan_status_detail_m->add_data($data_status_detail);
                }
            } 


            if (!empty($user_level_persetujuan) && $array_input['tipe_item'] == 2){

                $data = array(
                    'id'            => $no_permintaan,
                    'nomor_permintaan'            => $nomor_permintaan,
                    'tanggal'       => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal'))),
                    'divisi_id'     => 1,
                    'cabang_id'     => $this->input->post('cabang_id'),
                    'user_level_id' => $this->input->post('user_level_id'),
                    'user_id'       => $this->input->post('id_ref_user'),
                    'subjek'        => $this->input->post('subjek'),
                    'keterangan'    => $this->input->post('keterangan'),
                    'status'        => 3,
                    'tipe_persetujuan'  => 1,
                    'tipe'          => 2,
                    'is_active'     => 1,
                    'is_finish'     => 0,
                    'created_by'    => $this->session->userdata('user_id'),
                    'created_date'  => date('Y-m-d H:i:s'),
                );

                $order_permintaan_pembelian = $this->order_permintaan_barang_m->add_data($data);

                if($jenis_permintaan == 1){
                    $user_level_posisi = 9;
                }if($jenis_permintaan == 2){
                    $user_level_posisi = 32;
                }

                $divisi_posisi_akunting = $this->user_level_m->get(14);

                $last_id_status       = $this->permintaan_status_m->get_max_id_permintaan()->result_array();
                $last_id_status       = intval($last_id_status[0]['max_id'])+1;

                $format_id_status     = 'PS-'.date('m').'-'.date('Y').'-%04d';
                $id_status            = sprintf($format_id_status, $last_id_status, 4);

                $data_status = array(
                    'id'               => $id_status,
                    'transaksi_id'     => $no_permintaan,
                    'transaksi_nomor'  => $nomor_permintaan,
                    'tipe_transaksi'   => 1,
                    'tipe_persetujuan' => 0,
                    'status'           => 1,
                    'user_level_id'    => 14,
                    'divisi'           => $divisi_posisi_akunting->divisi_id,
                    'is_active'        => 1,
                    'created_by'       => $this->session->userdata('user_id'),
                    'created_date'     => date('Y-m-d H:i:s')
                );

                $pembayaran_status = $this->permintaan_status_m->add_data($data_status);

                $last_id_status_detail       = $this->permintaan_status_detail_m->get_max_id_permintaan_detail()->result_array();
                $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                $format_id_status_detail     = 'OID-'.date('m').'-'.date('Y').'-%04d';
                $id_status_detail            = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                $data_status_detail = array(
                    'id'                   => $id_status_detail,
                    'permintaan_status_id' => $id_status,
                    'transaksi_id'         => $no_permintaan,
                    'tipe_transaksi'       => 1,
                    '`order`'              => 1,
                    'divisi'               => $divisi_posisi_akunting->divisi_id,
                    'user_level_id'        => 14,
                    'tipe'                 => 1,
                    'tipe_pengajuan'       => 0,
                    'is_active'            => 1,
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s')
                );

                $pembayaran_status_detail = $this->permintaan_status_detail_m->add_data($data_status_detail);

                $divisi_posisi = $this->user_level_m->get($user_level_posisi);

                $last_id_status_detail       = $this->permintaan_status_detail_m->get_max_id_permintaan_detail()->result_array();
                $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                $format_id_status_detail     = 'OID-'.date('m').'-'.date('Y').'-%04d';
                $id_status_detail            = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                $data_status_detail = array(
                    'id'                   => $id_status_detail,
                    'permintaan_status_id' => $id_status,
                    'transaksi_id'         => $no_permintaan,
                    'tipe_transaksi'       => 1,
                    '`order`'              => 1,
                    'divisi'               => $divisi_posisi->divisi_id,
                    'user_level_id'        => $user_level_posisi,
                    'tipe'                 => 1,
                    'tipe_pengajuan'       => 0,
                    'is_active'            => 1,
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s')
                );

                $pembayaran_status_detail = $this->permintaan_status_detail_m->add_data($data_status_detail);

                foreach ($user_level_persetujuan as $kategori) {
                    $user_level = $this->user_level_m->get($kategori['user_level_menyetujui_id']);

                    $last_id_status_detail       = $this->permintaan_status_detail_m->get_max_id_permintaan_detail()->result_array();
                    $last_id_status_detail       = intval($last_id_status_detail[0]['max_id'])+1;
                    $format_id_status_detail     = 'OID-'.date('m').'-'.date('Y').'-%04d';
                    $id_status_detail            = sprintf($format_id_status_detail, $last_id_status_detail, 4);

                    $data_status_detail = array(
                        'id'                   => $id_status_detail,
                        'permintaan_status_id' => $id_status,
                        'transaksi_id'         => $no_permintaan,
                        'tipe_transaksi'       => 1,
                        '`order`'              => ($kategori['level_order'] + 2),
                        'divisi'               => $user_level->divisi_id,
                        'user_level_id'        => $kategori['user_level_menyetujui_id'],
                        'tipe'                 => 1,
                        'tipe_pengajuan'       => 0,
                        'is_active'            => 1,
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $pembayaran_status_detail = $this->permintaan_status_detail_m->add_data($data_status_detail);
                }
            }

 

            $harga_beli = 0;

            foreach ($items as $item) 

            {

                if ($item['id_box'] != "" && $item['nama'] != "")

                {

                    

                    $get_detail_box = $this->box_paket_detail_m->get_by(array('box_paket_id' => $item['id_box']));

                    $detail_box = object_to_array($get_detail_box);



                    foreach ($detail_box as $row) 

                    {

                        $max_kode_permintaan_item = $this->order_permintaan_barang_detail_m->get_max_kode()->result_array();

                        if(count($max_kode_permintaan_item) == 0)

                        {

                            $max_kode_permintaan_item = 1;

                        }

                        else

                        {

                            $max_kode_permintaan_item = intval($max_kode_permintaan_item[0]['max_kode']) + 1;

                        }



                        $format_item           = 'OPBI-'.date('m').'-'.date('Y').'-%04d';

                        $no_permintaan_item    = sprintf($format_item, $max_kode_permintaan_item, 4);



                        $jumlah = $item['jumlah'] * $row['jumlah'];



                        $data_item = array(

                            'id'                         => $no_permintaan_item,

                            'order_permintaan_barang_id' => $no_permintaan,

                            'item_id'                    => $row['item_id'],

                            'item_satuan_id'             => $row['item_satuan_id'],

                            'item_satuan_disetujui_id'   => $row['item_satuan_id'],

                            'jumlah'                     => $jumlah,

                            'jumlah_disetujui'           => $jumlah,

                            'box_paket_id'               => $item['id_box'],

                            'jumlah_paket'               => $item['jumlah'],

                            'harga_ref'                  => $item['harga'],

                            'supplier_id'                => $item['supp_id'],

                            'is_selected'                => 1,

                            'created_by'                 => $this->session->userdata('user_id'),

                            'created_date'               => date('Y-m-d H:i:s'),

                        );



                        $order_permintaan_barang_detail = $this->order_permintaan_barang_detail_m->add_data($data_item);

                    }



                    $data_setuju = $this->user_level_persetujuan_m->get_data_order($this->input->post('user_level_id'),1)->result_array();

                    $data_p_p_p = object_to_array($data_setuju);



                    if(!empty($data_setuju))

                    {



                        $level_id = $this->session->userdata('level_id');

                

                        if($level_id == 8 || $level_id == 19 || $level_id == 18 || $level_id == 20|| $level_id == 10){

                            $nama_user = $this->session->userdata('nama_lengkap');

                            sent_notification(1,$nama_user,$no_permintaan);

                        }



                        foreach ($data_setuju as $data) 

                        {

                            $max_id = '';

                            $maksimum = $this->persetujuan_permintaan_barang_m->get_max()->row(0);

 

                            if(count($maksimum) == NULL)

                            {

                                $max_id = 1;

                            }

                            else 

                            {

                                $max_id = $maksimum->max_id;

                                $max_id = $max_id + 1;

                            }



                            $data_save = array(

                                'persetujuan_permintaan_barang_id'  => $max_id,

                                'order_permintaan_barang_id'        => $no_permintaan,

                                'order_permintaan_barang_detail_id' => $no_permintaan_item,

                                'box_paket_id'                      => $item['id_box'],

                                'tipe_permintaan'                   => 1,

                                'user_level_id'                     => $data['user_level_menyetujui_id'],

                                '`order`'                           => $data['level_order'],

                                '`status`'                          => 1,

                                'created_by'                        => $this->session->userdata('user_id'),

                                'created_date'                      => date('Y-m-d H:i:s'),

                            );



                            $p_p_p_save = $this->persetujuan_permintaan_barang_m->add_data($data_save);

                        }

                    }

                }



                ///////item terpisah////////

                if ($item['account_id'] != "" && $item['nama'] != "" && $item['jumlah'] != "")

                {

                    $data_setuju = $this->user_level_persetujuan_m->get_data_order($this->input->post('user_level_id'),1)->result_array();

                    $data_p_p_p = object_to_array($data_setuju);



                    $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $item['account_id'], 'is_primary' => 1),true);

                    $konversi = $this->item_m->get_nilai_konversi($item['satuan']);



                    $jumlah_item_minta = ($item['jumlah'] * $konversi);



                    // $harga_item = $this->item_harga_m->get_harga_item_ravena($item['account_id'], $item['satuan'])->result_array();   

                    // $harga_item_primary = $this->item_harga_m->get_harga_item_ravena($item['account_id'], $satuan_primary->id)->result_array();  

                    

                    if(!empty($data_setuju))

                    {    




                        $max_kode_permintaan_item = $this->order_permintaan_barang_detail_m->get_max_kode()->result_array();

                        if(count($max_kode_permintaan_item) == 0)

                        {

                            $max_kode_permintaan_item = 1;

                        }

                        else

                        {

                            $max_kode_permintaan_item = intval($max_kode_permintaan_item[0]['max_kode']) + 1;

                        }



                        $format_item           = 'OPBI-'.date('m').'-'.date('Y').'-%04d';

                        $no_permintaan_item    = sprintf($format_item, $max_kode_permintaan_item, 4);



                        $data_item = array(

                            

                            'id'                         => $no_permintaan_item,

                            'order_permintaan_barang_id' => $no_permintaan,

                            'item_id'                    => $item['account_id'],

                            'item_satuan_id'             => $item['satuan'],

                            'item_satuan_disetujui_id'   => $item['satuan'],

                            'jumlah'                     => $item['jumlah'],

                            'jumlah_disetujui'           => $item['jumlah'],

                            'harga_ref'                  => $item['harga'],

                            'supplier_id'                => $item['supp_id'],

                            'is_selected'                => 1,

                            '`status`'                   => 1,

                            'created_by'                 => $this->session->userdata('user_id'),

                            'created_date'               => date('Y-m-d H:i:s'),



                        );



                        $order_permintaan_barang_detail = $this->order_permintaan_barang_detail_m->add_data($data_item);


                        foreach ($data_setuju as $data) 

                        {

                            $max_id = '';

                            $maksimum = $this->persetujuan_permintaan_barang_m->get_max()->row(0);

                            // die_dump($this->db->last_query());

                            // die_dump($maksimum);    

                            

                            if(count($maksimum) == NULL)

                            {

                                $max_id = 1;

                            }

                            else 

                            {

                                $max_id = $maksimum->max_id;

                                $max_id = $max_id + 1;

                            }



                            $data_save = array(

                                'persetujuan_permintaan_barang_id'  => $max_id,

                                'order_permintaan_barang_id'        => $no_permintaan,

                                'order_permintaan_barang_detail_id' => $no_permintaan_item,

                                'tipe_permintaan'                   => 1,

                                'user_level_id'                     => $data['user_level_menyetujui_id'],

                                '`order`'                           => $data['level_order'],

                                '`status`'                          => 1,

                                'created_by'                        => $this->session->userdata('user_id'),

                                'created_date'                      => date('Y-m-d H:i:s'),

                            );



                            $p_p_p_save = $this->persetujuan_permintaan_barang_m->add_data($data_save);

                        }

                    }

                    else{



                        $max_kode_permintaan_item = $this->order_permintaan_barang_detail_m->get_max_kode()->result_array();

                        if(count($max_kode_permintaan_item) == 0)

                        {

                            $max_kode_permintaan_item = 1;

                        }

                        else

                        {

                            $max_kode_permintaan_item = intval($max_kode_permintaan_item[0]['max_kode']) + 1;

                        }



                        $format_item           = 'OPBI-'.date('m').'-'.date('Y').'-%04d';

                        $no_permintaan_item    = sprintf($format_item, $max_kode_permintaan_item, 4);



                        $data_item = array(

                            

                            'id'                         => $no_permintaan_item,

                            'order_permintaan_barang_id' => $no_permintaan,

                            'item_id'                    => $item['account_id'],

                            'item_satuan_id'             => $item['satuan'],

                            'item_satuan_disetujui_id'   => $item['satuan'],

                            'jumlah'                     => $item['jumlah'],

                            'jumlah_disetujui'           => $item['jumlah'],

                            'harga_ref'                  => $item['harga'],

                            'supplier_id'                => $item['supp_id'],

                            'is_selected'                => 1,

                            '`status`'                   => 1,

                            'created_by'                 => $this->session->userdata('user_id'),

                            'created_date'               => date('Y-m-d H:i:s'),



                        );



                        $order_permintaan_barang_detail = $this->order_permintaan_barang_detail_m->add_data($data_item);

                        $last_id_osp       = $this->o_s_pmsn_m->get_max_id_os_pesan()->result_array();
                        $last_id_osp       = intval($last_id_osp[0]['max_id'])+1;
                        
                        $format_id_osp     = 'OSP-'.date('m').'-'.date('Y').'-%04d';
                        $id_osp            = sprintf($format_id_osp, $last_id_osp, 4);

                        $data_item_os = array(
                            'id'                     => $id_osp,
                            'tanggal'                => date('Y-m-d'),
                            'pemesanan_detail_id'    => $no_permintaan,
                            'item_id'                => $item['account_id'],
                            'item_satuan_id'         => $item['satuan'],
                            'jumlah'                 => $item['jumlah'],
                            'status'                 => 1,
                            'created_by'             => $this->session->userdata('user_id'),
                            'created_date'           => date('Y-m-d H:i:s')
                        );
                        
                        $save_os_pesan = $this->o_s_pmsn_m->add_data($data_item_os);             

                    }

                }

            }



            

            $i = 0;

            foreach ($items2 as $item2)

            {

                $max_kode_permintaan_detail = $this->order_permintaan_barang_detail_other_m->get_max_kode()->result_array();

                if(count($max_kode_permintaan_detail) == 0)

                {

                    $max_kode_permintaan_detail = 1;

                }

                else

                {

                    $max_kode_permintaan_detail = intval($max_kode_permintaan_detail[0]['max_kode']) + 1;

                }



                $format_item           = 'OPBIO-'.date('m').'-'.date('Y').'-%04d';

                $no_permintaan_item    = sprintf($format_item, $max_kode_permintaan_detail, 4);



                if($item2['nama_tindakan'] != "" && $item2['jumlah_tindakan'] != "" && $item2['satuan_tindakan'] != "" )

                {



                    $data_item_tindakan = array(

                        'id'                         => $no_permintaan_item,

                        'order_permintaan_barang_id' => $no_permintaan,

                        'nama'                       => $item2['nama_tindakan'],

                        'jumlah'                     => $item2['jumlah_tindakan'],

                        'satuan'                     => $item2['satuan_tindakan'],

                        'jenis'                      => $item2['jenis'],

                        'harga_ref'                  => $item2['harga_ref'],

                        'supplier'                   => $item2['supplier_name'],

                        'is_selected'                => 1,

                        'created_by'                 => $this->session->userdata('user_id'),

                        'created_date'               => date('Y-m-d H:i:s'),

                    );



                    $order_permintaan_barang_detail_other = $this->order_permintaan_barang_detail_other_m->add_data($data_item_tindakan);

                     

                    if(isset($array_input['pdf_'.$i]))

                    {



                        foreach ($array_input['pdf_'.$i] as $pdf) 

                        {



                            if($pdf['url'] != '')

                            {

                                $path_dokumen = './assets/mb/pages/pembelian/permintaan_po/doc/'.strtolower(str_replace(' ', '_', $item2['nama_tindakan']));

                                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}



                                $temp_filename = $pdf['url'];



                                $convtofile = new SplFileInfo($temp_filename);

                                $extenstion = ".".$convtofile->getExtension();



                                $new_filename = $pdf['url'];

                                $real_file = strtolower(str_replace(' ', '_', $item2['nama_tindakan'])).'/'.$new_filename;



                                copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_doc_permintaan').$real_file);     

                            }

                            else

                            {

                                $new_filename = 'global.png';

                            }





                            if ($pdf['url'] != "") {



                                $data_file = array(



                                    'order_permintaan_pembelian_detail_other_id' => $no_permintaan_item,

                                    'tipe'                                       => 1,

                                    'url'                                        => $pdf['url'],



                                    );

                                

                                $save_file = $this->o_p_p_d_o_item_file_m->save($data_file); 

                                // die_dump($save_file);

                                

                            }

                        }

                    }



                    if(isset($array_input['gambar_'.$i]))

                    {



                        foreach ($array_input['gambar_'.$i] as $gambar) 

                        {



                            if($gambar['url'] != '')

                            {

                                $path_dokumen = './assets/mb/pages/pembelian/permintaan_po/images/'.strtolower(str_replace(' ', '_', $item2['nama_tindakan']));

                                if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}



                                $temp_filename = $gambar['url'];



                                $convtofile = new SplFileInfo($temp_filename);

                                $extenstion = ".".$convtofile->getExtension();



                                $new_filename = $gambar['url'];

                                $real_file = strtolower(str_replace(' ', '_', $item2['nama_tindakan'])).'/'.$new_filename;



                                copy(base_url().config_item('user_img_temp_dir').$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_permintaan').$real_file);  

                            }

                            else

                            {

                                $new_filename = 'global.png';

                            }





                            if ($gambar['url'] != "") {

                                $data_gambar = array(

                                    'order_permintaan_pembelian_detail_other_id' => $no_permintaan_item,

                                    'tipe'                                       => 2,

                                    'url'                                        => $gambar['url'],

                                );

                                

                                $save_gambar = $this->o_p_p_d_o_item_file_m->save($data_gambar); 

                            }

                        } 

                    }  

                }

                $i++;

            }            

        }



        if ($order_permintaan_pembelian) 

            {

                $flashdata = array(

                    "type"     => "success",

                    "msg"      => translate("Data Permintaan Order berhasil ditambahkan.", $this->session->userdata("language")),

                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    

                    );

                $this->session->set_flashdata($flashdata);

            }



        redirect("pembelian/permintaan_po");

    }



    public function delete($id)

    {

           

        $data = array(

            'is_active'    => 0

        );

        // save data

        $user_id = $this->order_permintaan_barang_m->edit_data($data, $id);



        $wheres_status = array(

            'tipe_transaksi'    => 1,

            'transaksi_id'      => $id

        );



        $user_id = $this->session->userdata('user_id');



        $status_permintaan = $this->permintaan_status_m->update_by($user_id,$data,$wheres_status);

        

        $where_setuju['order_permintaan_barang_id'] = $id;

        $delete_persetujuan = $this->persetujuan_permintaan_barang_m->delete_by($where_setuju);

        

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

            'tipe'            => 1,

            'data_id'         => $id,

            'created_by'      => $this->session->userdata('user_id'),

            'created_date'    => date('Y-m-d H:i:s')

        );



        $trash = $this->kotak_sampah_m->simpan($data_trash);

        // die_dump($this->db->last_query());



        // if ($user_id) 

        // {

        //     $flashdata = array(

        //         "type"     => "error",

        //         "msg"      => translate("Branch Deleted", $this->session->userdata("language")),

        //         "msgTitle" => translate("Success", $this->session->userdata("language"))    

        //         );

        //     $this->session->set_flashdata($flashdata);

        // }

        redirect("pembelian/permintaan_po");

    }



    public function listing_alat_obat($tipe = null)

    {



        $result = $this->item_m->get_datatable_item_po($tipe);

        // die(dump($this->db->last_query()));



        $output = array(

            'sEcho'                => intval($this->input->post('sEcho', true)),

            'iTotalRecords'        => $result->total_records,

            'iTotalDisplayRecords' => $result->total_display_records,

            'aaData'               => array()

        );

        $records = $result->records;

       //die(dump($records));

        $i = 0;

        foreach($records->result_array() as $row)

        {

            $row['stock'] = 0;

            

            $item_id = $row['id'];



            $inventory = $this->inventory_m->get_by("item_id = $item_id AND gudang_id = 'WH-05-2016-002'");

            if(count($inventory))

            {

                $stok = 0;

                $inventory = object_to_array($inventory);

                foreach ($inventory as $row_inventory) {

                    $konversi = $this->item_m->get_nilai_konversi($row_inventory['item_satuan_id']);

                    $stok = $stok + ($row_inventory['jumlah'] * $konversi);
                }



                $row['stock'] = $stok;

            }


            $satuan = $this->item_satuan_m->get_by(array('item_id' => $row['id']));

            $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['id'], 'is_primary' => 1),true);

            $satuan = object_to_array($satuan);

            // $satuan_primary = object_to_array($satuan_primary);



            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($satuan)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';

             

             $output['aaData'][] = array(

                $row['kode'],

                $row['nama'],

                $row['stock']." ".$satuan_primary->nama,

                '<div class="text-center inline-button-table">'.$action.'</div>'

               );             

         $i++;

        }



       // die(dump($this->db->last_query()));



      echo json_encode($output);



    }

    

    public function listing_supplier($item_id = null)

    {



        $result = $this->supplier_item_m->get_datatable($item_id);



        $output = array(

            'sEcho'                => intval($this->input->post('sEcho', true)),

            'iTotalRecords'        => $result->total_records,

            'iTotalDisplayRecords' => $result->total_display_records,

            'aaData'               => array()

        );

        $records = $result->records;

        $i = 0;

        foreach($records->result_array() as $row)

        {



            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select-supplier"><i class="fa fa-check"></i></a>';

             

             $output['aaData'][] = array(

                $row['kode'],

                $row['nama'],

                '<div class="text-center">'.$action.'</div>'

               );             

         $i++;

        }



       // die(dump($this->db->last_query()));



      echo json_encode($output);



    }



    public function listing_box_paket()

    {





        $result = $this->box_paket_m->get_datatable();

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



            

            $box_detail = $this->box_paket_detail_m->get_by(array('box_paket_id' => $row['id']));

            $jumlah_box_detail = $this->box_paket_detail_m->get_box_detail($row['id'])->result_array();

            $jumlah_box = object_to_array($jumlah_box_detail);

            // die_dump($box_detail);

            // die_dump($this->db->last_query());

            

            // $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($satuan)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select-box"><i class="fa fa-check"></i></a>';

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-box="'.htmlentities(json_encode($row)).'" data-jumlah="'.htmlentities(json_encode($jumlah_box)).'" data-box-detail="'.htmlentities(json_encode($box_detail)).'" class="btn btn-primary select-box"><i class="fa fa-check"></i></a>';

             

             $output['aaData'][] = array(

                $row['id'],

                $row['box_paket_nama'],

                '<div class="text-center">'.$action.'</div>'

               );             

         $i++;

        }



       // die(dump($this->db->last_query()));



      echo json_encode($output);



    }



    public function listing_pilih_user_permintaan_po()

    {



        // $cabang_id = $this->session->userdata('cabang_id');

        // die_dump($this->db->last_query());

                // die_dump($cabang_id);

        $result = $this->pilih_user_m->get_datatable_pilih_user_po();

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



        $i=0;

        foreach($records->result_array() as $row)

        {

            

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';



            $output['aaData'][] = array(

                '<div class="text-center">'.$row['id_user'].'</div>',

                '<div class="text-left">'.$row['nama_user'].'</div>',

                '<div class="text-center">'.$row['nama_user_level'].'</div>' ,

                '<div class="text-center">'.$action.'</div>' 

            );

         $i++;

        }



        echo json_encode($output);

    }



    public function unggah_gambar($id_row_gambar, $index_row)

    {

        $data = array(

            'id_row_gambar'       => $id_row_gambar,

            'index_row'           => $index_row,

        );





        $this->load->view('pembelian/permintaan_po/unggah_gambar', $data);

    }



    public function unggah_file($id_row_file, $id_row)

    {



        $data = array(

            'flag'        => 'add',

            'id_row_file' => $id_row_file,

            'index_row'   => $id_row,

        );



        $this->load->view('pembelian/permintaan_po/unggah_file', $data);

    }



    public function lihat_gambar($id_order_detail)

    {

        $data_img = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $id_order_detail, 'tipe' => 2 ));

        // die(dump($this->db->last_query()));



        $data_order_detail = $this->order_permintaan_barang_detail_other_m->get_by(array('id' => $id_order_detail),true);

        $data = array(        

            'data_img'          => ($data_img)?object_to_array($data_img):'',

            'data_order_detail' => object_to_array($data_order_detail)

        );



        $this->load->view('pembelian/persetujuan_permintaan_po/unggah_gambar', $data);

    }



    public function listing_pilih_item_user_view_history($id=null, $user_level_id=null, $order_permintaan_barang_detail_id = null,$order=null)

    {

        $result = $this->persetujuan_permintaan_barang_history_m->get_datatable_user_setujui($id, $user_level_id, $order_permintaan_barang_detail_id, $order);



        // Output

        $output = array(

            'sEcho'                => intval($this->input->post('sEcho', true)),

            'iTotalRecords'        => $result->total_records,

            'iTotalDisplayRecords' => $result->total_display_records,

            'aaData'               => array()

        );

    

        $records = $result->records;

        $i=1;

        $status = '';

        foreach($records->result_array() as $row)

        {



            $data_order_view = $this->persetujuan_permintaan_barang_history_m->get_data_order($row['id'], $row['order_permintaan_barang_id'], $row['user_level_id'])->result_array();

            // die_dump($data_order_view);



            if($row['status_ppp'] == 4) {



                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';

            

            } elseif($row['status_ppp'] == 3) {



                $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';



            } elseif($row['status_ppp'] == 2) {



                $status = '<div class="text-center"><span class="label label-md label-warning">Sudah Dibaca</span></div>';



            } elseif($row['status_ppp'] == 1) {



                $status = '<div class="text-center"><span class="label label-md label-info">Belum Dibaca</span></div>';



            }



            if ($row['tanggal_persetujuan'] != null) {



                $tanggal_persetujuan = date('d M Y', strtotime($row['tanggal_persetujuan']));

            

            } else {



                $tanggal_persetujuan = "";

            }



            if ($row['tanggal_baca'] != null) {



                $tanggal_baca = date('d M Y', strtotime($row['tanggal_baca']));

            

            } else {



                $tanggal_baca = "";



            }



            $output['aaData'][] = array(

                '<div class="text-center">'.$row['id'].'</div>',

                '<div class="text-left">'.$row['nama_user_level'].'</div>',

                '<div class="text-center">'.$row['order_ppp'].'</div>',

                '<div class="text-center">'.$status.'</div>',

                '<div class="text-center">'.$tanggal_baca.'</div>',

                '<div class="text-left">'.$row['nama_user_dibaca_oleh'].'</div>',

                '<div class="text-center">'.$tanggal_persetujuan.'</div>',

                '<div class="text-left">'.$row['nama_user_disetujui_oleh'].'</div>',

                '<div class="text-left">'.$row['jumlah_persetujuan'].'</div>',

            );

         $i++;

        }



        echo json_encode($output);

    }





}



/* End of file permintaan_po.php */

/* Location: ./application/controllers/pembelian/permintaan_po.php */