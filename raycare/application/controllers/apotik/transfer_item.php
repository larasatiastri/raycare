<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer_item extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '2ff2f93137833384cfa1b09a4a3df936';                  // untuk check bit_access

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

        $this->load->model('apotik/transfer_item/request_item_m');
        $this->load->model('apotik/transfer_item/transfer_item_m');
        $this->load->model('apotik/transfer_item/transfer_item_detail_m');
        $this->load->model('apotik/transfer_item/transfer_item_identitas_m');
        $this->load->model('apotik/transfer_item/transfer_item_identitas_detail_m');
        $this->load->model('apotik/transfer_item/item_m');
        $this->load->model('apotik/transfer_item/inventory_m');
        $this->load->model('apotik/transfer_item/inventory_temporary_m');
        $this->load->model('apotik/transfer_item/inventory_history_m');
        $this->load->model('apotik/transfer_item/inventory_history_detail_m');
        $this->load->model('apotik/transfer_item/inventory_history_identitas_m');
        $this->load->model('apotik/transfer_item/inventory_history_identitas_detail_m');
        $this->load->model('apotik/transfer_item/inventory_identitas_m');
        $this->load->model('apotik/transfer_item/inventory_identitas_detail_m');
        $this->load->model('master/item/item_satuan_m');


        $this->load->model('apotik/gudang_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/transfer_item/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transfer Item', $this->session->userdata('language')), 
            'header'         => translate('Transfer Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_permintaan($gudang_id = null)
    {        
        $result = $this->request_item_m->get_datatable($gudang_id);
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
        $info = '';

        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
           
                $data_proses = '<a title="'.translate('Finish Process', $this->session->userdata('language')).'" href="'.base_url().'apotik/transfer_item/proses/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data permintaan ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete_request" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';

                // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_proses,$user_level_id,'transfer_item','proses').restriction_button($data_delete,$user_level_id,'transfer_item','delete');

            $info = '<a title="'.translate('Info', $this->session->userdata('language')).'" name="info[]" class="pilih-item" data-id="'.$row['id'].'">'.$row['jumlah_request_item_detail'].' Item</a>';
            
            $notes = $row['keterangan'];
          
            $words = explode(' ', $notes);
          
            $impWords = implode(' ', array_splice($words, 0, 6));
            
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            

            $output['data'][] = array(
                $row['id'],
                $row['nama_gudang'],
                $row['dibaca_oleh'],
                $info,
                $row['keterangan'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_item($id=null)
    {
        
        $result = $this->item_m->get_datatable($id);
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
        $i=1;
        foreach($records->result_array() as $row)
        {

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['jumlah'].' '.$row['nama_satuan'].'</div>',
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listing_kirim($gudang_id = null)
    {        
        $result = $this->transfer_item_m->get_datatable_kirim($gudang_id);
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
        $info = '';

        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            // die_dump($row['tanggal']);
           
                $data_view = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/transfer_item/view/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
                $data_edit = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'apotik/transfer_item/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
                $data_delete = '<a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data kirim ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete_kirim" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';

                // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_view,$user_level_id,'transfer_item','view');

            $notes = $row['keterangan'];
          
            $words = explode(' ', $notes);
          
            $impWords = implode(' ', array_splice($words, 0, 6));
            
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            

            $output['data'][] = array(
                $row['id'],
                $row['no_transfer'],
                $row['no_surat_jalan'],
                $row['nama_gudang_dari'],
                $row['nama_gudang_ke'],
                '<div class="text-center">'.date(' d M Y', strtotime($row['tanggal'])).'</div>',
                $row['dikirim_oleh'],
                $row['keterangan'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_terima($gudang_id = null)
    {        
        $result = $this->transfer_item_m->get_datatable_terima($gudang_id);
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
        $info = '';

        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            // die_dump($row['tanggal']);
           
                $data_receive = '<a title="'.translate('Receive', $this->session->userdata('language')).'" href="'.base_url().'apotik/transfer_item/receive/'.$row['id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a>';
                $data_reject = '<a title="'.translate('Reject', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'apotik/transfer_item/reject/'.$row['id'].'" class="btn red"><i class="fa fa-times"></i></a>';

                // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
                // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
                $action =  restriction_button($data_receive,$user_level_id,'transfer_item','receive').restriction_button($data_reject,$user_level_id,'transfer_item','reject');

            $notes = $row['keterangan'];
          
            $words = explode(' ', $notes);
          
            $impWords = implode(' ', array_splice($words, 0, 6));
            
            $preNotes =  '<p>'.$impWords.' ... <a class="show-notes" data-toggle="popover" title="'.translate('Notes',$this->session->userdata('language')).'" data-content="'.$notes.'">'.translate('more',$this->session->userdata('language')).'</a></p>';
            

            $output['data'][] = array(
                $row['id'],
                $row['no_transfer'],
                $row['no_surat_jalan'],
                $row['nama_gudang_dari'],
                $row['nama_gudang_ke'],
                '<div class="text-center">'.date(' d M Y H:i:s', strtotime($row['tanggal'])).'</div>',
                $row['dikirim_oleh'],
                $row['keterangan'],
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function history_transfer_item()
    {
        $assets = array();
        $config = 'assets/apotik/transfer_item/history_transfer_item';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Transfer Item', $this->session->userdata('language')), 
            'header'         => translate('History Transfer Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/history_transfer_item',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history_terima_item()
    {
        $assets = array();
        $config = 'assets/apotik/transfer_item/history_terima_item';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Terima Item', $this->session->userdata('language')), 
            'header'         => translate('History Terima Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/history_terima_item',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

     public function listing_history_transfer_item($gudang_id = null)
    {        
        $result = $this->transfer_item_m->get_datatable_history($gudang_id);
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
           
            $data_view_history = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/transfer_item/view_history/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
            

            // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
            // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
            $action =  restriction_button($data_view_history,$user_level_id,'transfer_item','view_history');

            if($row['status'] == 2) {

                $status = '<div class="text-left"><span class="label label-md label-success">Diterima</span></div>';

            } elseif($row['status'] == 3) {

                $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';
            }

            $output['data'][] = array(
                $row['id'],
                '<div class="inline-button-table">'.$row['no_surat_jalan'].'</div>',
                $row['nama_gudang_dari'],
                $row['nama_gudang_ke'],
                '<div class="text-center">'.date(' d M Y H:i:s', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.date(' d M Y H:i:s', strtotime($row['diterima_tanggal'])).'</div>',
                $row['dikirim_oleh'],
                '<div class="inline-button-table">'.$row['keterangan'].'</div>',
                $status,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function listing_history_terima_item($gudang_id = null)
    {        
        $result = $this->transfer_item_m->get_datatable_history_terima($gudang_id);

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
           
            $data_view_history = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'apotik/transfer_item/view_history/'.$row['id'].'" class="btn grey-cascade"><i class="fa fa-search"></i></a>';
            

            // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="edit"
            // //tambahkan data ke tabel fitur_tombol. Field page="user_level", button="delete"
            $action =  restriction_button($data_view_history,$user_level_id,'transfer_item','view_history');

            if($row['status'] == 2) {

                $status = '<div class="text-left"><span class="label label-md label-success">Diterima</span></div>';

            } elseif($row['status'] == 3) {

                $status = '<div class="text-left"><span class="label label-md label-danger">Ditolak</span></div>';
            }

            $output['data'][] = array(
                $row['id'],
                '<div class="inline-button-table">'.$row['no_surat_jalan'].'</div>',
                $row['nama_gudang_dari'],
                $row['nama_gudang_ke'],
                '<div class="text-center">'.date(' d M Y H:i:s', strtotime($row['tanggal'])).'</div>',
                '<div class="text-center">'.date(' d M Y H:i:s', strtotime($row['diterima_tanggal'])).'</div>',
                $row['dikirim_oleh'],
                $row['keterangan'],
                $status,
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function delete_request($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'transfer_item','delete_request'))
        {
            $data = array(
                'is_active'    => 0
            );
            // save data
            $request_id = $this->request_item_m->save($data, $id);

            // $max_id = $this->kotak_sampah_m->max();
            // if ($max_id->kotak_sampah_id==null){
            //     $trash_id = 1;
            // } else {
            //     $trash_id = $max_id->kotak_sampah_id+1;
            // }

            // $data_trash = array(
            //     'kotak_sampah_id' => $trash_id,
            //     'tipe'            => 4,
            //     'data_id'         => $id,
            //     'created_by'      => $this->session->userdata('user_id'),
            //     'created_date'    => date('Y-m-d H:i:s')
            // );

            // $trash = $this->kotak_sampah_m->save($data_trash);

            if ($request_id) 
            {
                $flashdata = array(
                    "type"     => "error",
                    "msg"      => translate("Request telah dihapus", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            redirect("apotik/transfer_item");
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function delete_kirim($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'transfer_item','delete_kirim'))
        {
            $data = array(
                'is_active'    => 0
            );
            // save data
            $transfer_item_id = $this->transfer_item_m->save($data, $id);

            if ($transfer_item_id) 
            {
                $flashdata = array(
                    "type"     => "error",
                    "msg"      => translate("Data Transfer Item telah dihapus", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            redirect("apotik/transfer_item");
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function view_history($id)
    {
        $assets = array();
        $config = 'assets/apotik/transfer_item/view_history_transfer_item';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $form_data = $this->transfer_item_m->get_by(array('id' =>$id), true);
        // die_dump($form_data);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transfer Item', $this->session->userdata('language')), 
            'header'         => translate('Transfer Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/view_history_transfer_item',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id,
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function receive($id)
    {

        $assets = array();
        $config = 'assets/apotik/transfer_item/receive';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $form_data = $this->transfer_item_m->get_by(array('id' => $id), true);
        // die_dump($form_data);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transfer Item', $this->session->userdata('language')), 
            'header'         => translate('Transfer Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/receive',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id,
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function reject_proses($request_item_id)
    {

        $data = array(
        
            'request_item_id'       => $request_item_id,

        );

        $this->load->view('apotik/transfer_item/modal/modal_reject_proses', $data);

    }

    public function reject_terima($transfer_item_id)
    {

        $data = array(
        
            'transfer_item_id'       => $transfer_item_id,
        );

        $this->load->view('apotik/transfer_item/modal/modal_reject_terima', $data);

    }

    public function reject($transfer_item_id)
    {

        $data = array(
        
        'transfer_item_id'       => $transfer_item_id,

        );

        $this->load->view('apotik/transfer_item/modal/modal_reject', $data);

    }

    public function proses($id)
    {

        $assets = array();
        $config = 'assets/apotik/transfer_item/proses';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $form_data = $this->request_item_m->get($id);
        // die_dump($form_data);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transfer Item', $this->session->userdata('language')), 
            'header'         => translate('Transfer Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/proses',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id,
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function add_kirim_item($id)
    {

        $assets = array();
        $config = 'assets/apotik/transfer_item/add_kirim_item';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $data_gudang_kirim = $this->gudang_m->get_by(array('id' =>$id));
        $data = object_to_array($data_gudang_kirim);

        $form_data = $this->transfer_item_m->get_by(array('dari_gudang_id' => $id));
        $data_transfer_item = object_to_array($form_data);
        // die_dump($data_transfer_item);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transfer Item', $this->session->userdata('language')), 
            'header'         => translate('Transfer Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/add_kirim_item',
            'data_gudang_kirim'      => $data,
            // 'pk_value'       => $id,
            'flag'           => 1,
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function view($id)
    {
        $assets = array();
        $config = 'assets/apotik/transfer_item/view';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $form_data = $this->transfer_item_m->get_by(array('id' => $id), true);
        // die_dump($form_data);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transfer Item', $this->session->userdata('language')), 
            'header'         => translate('Transfer Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/view',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id,
            'flag'           => 1,
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {

        $assets = array();
        $config = 'assets/apotik/transfer_item/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        
        $form_data = $this->transfer_item_m->get_by(array('id' =>$id));
        // die_dump($form_data);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Transfer Item', $this->session->userdata('language')), 
            'header'         => translate('Transfer Item', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/transfer_item/edit',
            'form_data'      => object_to_array($form_data),
            'form_item_detail'      => $this->transfer_item_detail_m->get_data($id),
            'pk_value'       => $id,
            );
        
        // Load the view
        $this->load->view('_layout', $data);

    }

    public function reject_terima_item()
    {

        $array_input = $this->input->post();
        $user = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');
        
        if($array_input['keterangan'] != "")
        {
            $data_transfer_item = $this->transfer_item_m->get_by(array('id' => $array_input['transfer_item_id']), true);
            $data_transfer_item = object_to_array($data_transfer_item);

            $data = array(
                'status' => 3,
                'keterangan_tolak' => $array_input['keterangan'],
                'diterima_oleh'    => $user,
                'diterima_tanggal' => $date,
            );

            $update_transfer_item = $this->transfer_item_m->update_by($user, $data, array('id' => $array_input['transfer_item_id']));

            $data_inventory_history = array(

                'transaksi_id'  =>  $array_input['transfer_item_id'],
                'transaksi_tipe' => 11,

            );

            $inventory_history_id = $this->inventory_history_m->save($data_inventory_history);

            $inventory_temporary = $this->inventory_temporary_m->get_by(array('transaksi_id' => $array_input['transfer_item_id'], 'tipe_transaksi' => 1));
            $inventory_temporary = object_to_array($inventory_temporary);

            foreach ($inventory_temporary as $row_inv) {
                
                $last_inv_tujuan_id = $this->inventory_m->get_last_id()->result_array();
                $last_inv_tujuan_id = intval($last_inv_tujuan_id[0]['inventory_id']) + 1;

                $data_inventory_tujuan = array(
                    'inventory_id'        => $last_inv_tujuan_id,
                    'gudang_id'           => $data_transfer_item['dari_gudang_id'],
                    'pmb_id'              => $row_inv['pmb_id'],
                    'pembelian_detail_id' => $row_inv['pembelian_detail_id'],
                    'box_paket_id'        => NULL,
                    'kode_box_paket'      => NULL,
                    'item_id'             => $row_inv['item_id'],
                    'item_satuan_id'      => $row_inv['item_satuan_id'],
                    'jumlah'              => $row_inv['jumlah'],
                    'tanggal_datang'      => $row_inv['tanggal_datang'],
                    'harga_beli'          => $row_inv['harga_beli'],
                    'bn_sn_lot'           => $row_inv['bn_sn_lot'],
                    'expire_date'         => $row_inv['expire_date'],
                    'created_by'          => $this->session->userdata('user_id'),
                    'created_date'        => date('Y-m-d H:i:s')
                );

                $inv_tujuan = $this->inventory_m->add_data($data_inventory_tujuan);

                $data_inventory_history_detail = array(
                    'inventory_history_id' => $inventory_history_id,
                    'gudang_id'            => $data_transfer_item['dari_gudang_id'],
                    'pmb_id'               => $row_inv['pmb_id'],
                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                    'box_paket_id'         => NULL,
                    'kode_box_paket'       => NULL,
                    'item_id'              => $row_inv['item_id'],
                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                    'initial_stock'        => 0,
                    'change_stock'         => $row_inv['jumlah'],
                    'final_stock'          => $row_inv['jumlah'],
                    'harga_beli'           => $row_inv['harga_beli'],
                    'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                    'expire_date'          => $row_inv['expire_date'],
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s')
                );

                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                $delete_inv_temp = $this->inventory_temporary_m->delete_by(array('id' => $row_inv['id']));
            }


        }

        if ($update_transfer_item) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data Transfer Item Ditolak.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('apotik/transfer_item');

    }

     public function reject_request_item()
    {

        // $command = $this->input->post('command');
        $array_input = $this->input->post();
        $user = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');
        // die_dump($array_input);
        
        if($array_input['keterangan'] != "")
        {
            $data = array(

                'status' => 3,
                'keterangan_tolak' => $array_input['keterangan'],
                // 'diterima_oleh'    => $user,
                // 'diterima_tanggal' => $date,
            );

            $update_request_item = $this->request_item_m->save($data, $array_input['request_item_id']);
            // die_dump($this->db->last_query());
        }

        if ($update_request_item) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Data Request Item Ditolak.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('apotik/transfer_item');

    }

    public function receive_terima_item()
    {

        $command = $this->input->post('command');
        $array_input = $this->input->post();
        $user = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');
               
        if($command === 'receive')
        {
            if($array_input['pk_value'] != "")
            {
                $data = array(
                    'status' => 2,
                    'diterima_oleh'    => $user,
                    'diterima_tanggal' => $date,
                );

                $update_transfer_item = $this->transfer_item_m->update_by($user, $data, array('id' => $array_input['pk_value']));
                // die_dump($this->db->last_query());
            }

            $data_inventory_history = array(

                'transaksi_id'  =>  $array_input['pk_value'],
                'transaksi_tipe' => 11,

            );

            $inventory_history_id = $this->inventory_history_m->save($data_inventory_history);

            $inventory_temporary = $this->inventory_temporary_m->get_by(array('transaksi_id' => $array_input['pk_value'], 'tipe_transaksi' => 1));
            $inventory_temporary = object_to_array($inventory_temporary);

            foreach ($inventory_temporary as $row_inv) {
                
                $last_inv_tujuan_id = $this->inventory_m->get_last_id()->result_array();
                $last_inv_tujuan_id = intval($last_inv_tujuan_id[0]['inventory_id']) + 1;

                $data_inventory_tujuan = array(
                    'inventory_id'        => $last_inv_tujuan_id,
                    'gudang_id'           => $row_inv['gudang_id'],
                    'pmb_id'              => $row_inv['pmb_id'],
                    'pembelian_detail_id' => $row_inv['pembelian_detail_id'],
                    'box_paket_id'        => NULL,
                    'kode_box_paket'      => NULL,
                    'item_id'             => $row_inv['item_id'],
                    'item_satuan_id'      => $row_inv['item_satuan_id'],
                    'jumlah'              => $row_inv['jumlah'],
                    'tanggal_datang'      => $row_inv['tanggal_datang'],
                    'harga_beli'          => $row_inv['harga_beli'],
                    'bn_sn_lot'           => $row_inv['bn_sn_lot'],
                    'expire_date'         => $row_inv['expire_date'],
                    'created_by'          => $this->session->userdata('user_id'),
                    'created_date'        => date('Y-m-d H:i:s')
                );

                $inv_tujuan = $this->inventory_m->add_data($data_inventory_tujuan);

                $data_inventory_history_detail = array(
                    'inventory_history_id' => $inventory_history_id,
                    'gudang_id'            => $row_inv['gudang_id'],
                    'pmb_id'               => $row_inv['pmb_id'],
                    'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                    'box_paket_id'         => NULL,
                    'kode_box_paket'       => NULL,
                    'item_id'              => $row_inv['item_id'],
                    'item_satuan_id'       => $row_inv['item_satuan_id'],
                    'initial_stock'        => 0,
                    'change_stock'         => $row_inv['jumlah'],
                    'final_stock'          => $row_inv['jumlah'],
                    'harga_beli'           => $row_inv['harga_beli'],
                    'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                    'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                    'expire_date'          => $row_inv['expire_date'],
                    'created_by'           => $this->session->userdata('user_id'),
                    'created_date'         => date('Y-m-d H:i:s')
                );

                $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                $delete_inv_temp = $this->inventory_temporary_m->delete_by(array('id' => $row_inv['id']));
            }
            

            // if ($update_transfer_item && $inventory_history_identitas_detail) 
            // {
            //     $flashdata = array(
            //         "type"     => "success",
            //         "msg"      => translate("Data Transfer Item Diterima.", $this->session->userdata("language")),
            //         "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            //         );
            //     $this->session->set_flashdata($flashdata);
            // }
            
            redirect('apotik/transfer_item');
            
        } 


        

    }


    public function listing_item($gudang_id)
    {


        $result = $this->inventory_m->get_datatable_item($gudang_id);
        // die(dump($this->db->last_query()));

  
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
        $records = $result->records;
        // die(dump($records));
        $i = 0;
        foreach($records->result_array() as $row)
        {

            
            $satuan = $this->item_satuan_m->get_by(array('item_id' => $row['item_id']));
            $satuan_primary = $this->item_satuan_m->get_by(array('item_id' => $row['item_id'], 'is_primary' => 1),true);
            $satuan = object_to_array($satuan);
            // $satuan_primary = object_to_array($satuan_primary);

            $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" data-satuan="'.htmlentities(json_encode($satuan)).'" data-satuan_primary="'.htmlentities(json_encode($satuan_primary)).'" class="btn btn-primary select"><i class="fa fa-check"></i></a>';
             
             $output['aaData'][] = array(
                '<div class="text-center">'.$row['item_id'].'</div>',
                '<div class="text-left">'.$row['item_kode'].'</div>',
                '<div class="text-left">'.$row['item_nama'].'</div>',
                '<div class="text-left">'.$row['satuan'].'</div>',
                '<div class="text-left">'.$row['bn'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['ed'])).'</div>',
                '<div class="text-center">'.$action.'</div>'
               );             
         $i++;
        }

       // die(dump($this->db->last_query()));


      echo json_encode($output);

    }

    public function proses_item()
    {

        $command = $this->input->post('command');
        $array_input = $this->input->post();
        $user = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');
        // die_dump($array_input);
        
        if($command === 'submit')
        {

            $last_number  = $this->transfer_item_m->get_nomor_transfer()->result_array();
            $last_number  = intval($last_number[0]['max_nomor_transfer'])+1;
            // die_dump($last_number);

            $format          = 'TRI-'.date('ymd').'-%03d';
            $no_transfer     = sprintf($format, $last_number, 3);

            if($array_input['pk_value'] != "")
            {

                ////////////////update status request item menjadi 2///////////////////////
                $data = array(

                    'status' => 2,
                );

                $update_request_item = $this->request_item_m->save($data, $array_input['pk_value']);
                // die_dump($this->db->last_query());
                /////////////////////////////////end//////////////////////////////////////
                

                $data_transfer_item = array(

                    'dari_gudang_id' => $array_input['dari_gudang_id'],
                    'ke_gudang_id'   => $array_input['ke_gudang_id'],
                    'tanggal'        => date('Y-m-d', strtotime($array_input['tanggal_transfer'])),
                    'no_transfer'    => $no_transfer,
                    'no_surat_jalan' => $array_input['no_surat_jalan'],
                    'keterangan'     => $array_input['keterangan'],
                    'status'         => 1,
                    'is_active'      => 1,

                );

                $transfer_item = $this->transfer_item_m->save($data_transfer_item);

                $data_inventory_history = array(

                    'transaksi_id'  =>  $transfer_item,
                    'transaksi_tipe' => 8,

                );

                $save_inventory_history = $this->inventory_history_m->save($data_inventory_history);
                // die_dump($this->db->last_query());

                //////////////////////////////////////**//////////////////////////////////////

                foreach ($array_input['items'] as $item) 
                {

                    if($item['item_id'] != "")
                    {

                        if(isset($array_input['identitas_'.$item['item_id']]))
                        {

                            $i = 1;
                            foreach ($array_input['identitas_'.$item['item_id']] as $identitas) 
                            {
                                if(isset($identitas))
                                {

                                    $cek_transfer_item_detail = $this->transfer_item_detail_m->get_by(array(
                                        'transfer_item_id'  => $transfer_item,
                                        'item_id'           => $item['item_id'],
                                        'item_satuan_id'    => $item['item_satuan_id'],
                                        'jumlah'            => $identitas['jumlah']
                                    ));

                                    $cek_transfer_item_detail_array = object_to_array($cek_transfer_item_detail);
                                    // die_dump($this->db->last_query());

                                    ////////////////////////**//////////////////////////////
                                    
                                    if(empty($cek_transfer_item_detail_array))
                                    {
                                        $data_transfer_item_detail = array(
                                            'transfer_item_id' => $transfer_item,
                                            'item_id'          => $item['item_id'],
                                            'item_satuan_id'   => $item['item_satuan_id'],
                                            'jumlah'           => $identitas['jumlah'],

                                            );

                                        $transfer_item_detail = $this->transfer_item_detail_m->save($data_transfer_item_detail);
                                        // die_dump($this->db->last_query());
                                    
                                    } 
                                        else
                                    {
                                        $jumlah_sebelumnya = intval($cek_transfer_item_detail_array[0]['jumlah']);
                                        $jumlah_sekarang = $jumlah_sebelumnya + intval($identitas['jumlah']);

                                        $transfer_item_detail_id = $cek_transfer_item_detail_array[0]['id'];

                                        $data_update_jumlah = array('jumlah' => $jumlah_sekarang);
                                        $transfer_item_detail = $this->transfer_item_detail_m->save($data_update_jumlah, $transfer_item_detail_id);
                                    }

                                    /////////////////////////**//////////////////////////////
                                    
                                    $data_identitas = array(
                                        'transfer_item_detail_id'  => $transfer_item_detail,
                                        'jumlah'                   => $identitas['jumlah'],
                                    );

                                    $id_transfer_item_identitas = $this->transfer_item_identitas_m->save($data_identitas);

                                    ///////////////////////**/////////////////////////////
                                    
                                    $data_inventory_history_detail = array(
                                        'inventory_history_id'  => $save_inventory_history,
                                        'gudang_id'             => $identitas['gudang_id'],
                                        'pmb_id'                => $identitas['pmb_id'],
                                        'item_id'               => $item['item_id'],
                                        'item_satuan_id'        => $item['item_satuan_id'],
                                        'initial_stock'         => $identitas['stock'],
                                        'change_stock'          => '-'.$identitas['jumlah'],
                                        'final_stock'           => intval($identitas['stock'])-intval($identitas['jumlah']),
                                        'harga_beli'            => $identitas['harga_beli'],
                                        'total_harga'           => intval($identitas['harga_beli'])*intval($identitas['jumlah']),
                                    );

                                    $id_inventory_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                                    //////////////////////////**////////////////////////
                                    
                                    $data_inventory_history_identitas = array(
                                        'inventory_history_detail_id' => $id_inventory_history_detail,
                                        'jumlah' => $identitas['jumlah'],
                                    );

                                    $inventory_history_identitas_id = $this->inventory_history_identitas_m->save($data_inventory_history_identitas);
                                    

                                    //////////////////////////**////////////////////////
                                    
                                    //////////////////////////**////////////////////////

                                    $get_jumlah_inventory = $this->inventory_m->get_by(array('inventory_id' => $identitas['inventory_id']));
                                    $array_jumlah_inventory = object_to_array($get_jumlah_inventory);


                                     // // die_dump($array_jumlah_inventory[0]['jumlah']);
                                    $jumlah = intval($array_jumlah_inventory[0]['jumlah'])-intval($identitas['jumlah']);
                                    $modified_by      = $this->session->userdata('user_id');
                                    $modified_date    = date('Y-m-d H:i:s');

                                    $save_inventory_identitas = $this->inventory_identitas_m->update_stock_identitas($jumlah, $identitas['inventory_identitas_id'], $modified_by, $modified_date);
                                    $update_stock_inventory = $this->inventory_m->update_stock_identitas($jumlah, $identitas['inventory_id'], $modified_by, $modified_date);
                                    

                                    $cek_stock_inventory_habis = $this->inventory_m->get_by(array('jumlah' => 0));
                                    $cek_stock_inventory_habis_array = object_to_array($cek_stock_inventory_habis);

                                    if(!empty($cek_stock_inventory_habis_array)){
                                        // die_dump($cek_stock_inventory_habis_array);
                                        foreach ($cek_stock_inventory_habis_array as $delete_stock_inventory) {
                                            $this->inventory_m->delete_inventory($delete_stock_inventory['inventory_id']);


                                        }

                                        // die_dump($this->db->last_query());
                                    }

                                    $cek_stock_inventory_identitas_habis = $this->inventory_identitas_m->get_by(array('jumlah' => 0));
                                    $cek_stock_inventory_identitas_habis_array = object_to_array($cek_stock_inventory_identitas_habis);


                                    if(!empty($cek_stock_inventory_identitas_habis_array)){
                                        // die_dump($cek_stock_inventory_habis_array);
                                        
                                        foreach ($cek_stock_inventory_identitas_habis_array as $delete_stock_inventory_identitas) {
                                            $this->inventory_identitas_m->delete_inventory_identitas($delete_stock_inventory_identitas['inventory_identitas_id']);
                                            $this->inventory_identitas_detail_m->delete_inventory_identitas_detail($delete_stock_inventory_identitas['inventory_identitas_id']);
                                        }

                                        // die_dump($cek_stock_inventory_identitas_habis_array);
                                        // die_dump($this->db->last_query());
                                    }

                                    // die_dump($this->db->last_query());
                                    // die_dump($data_inventory_history_detail);

                                    ////////////////////////////////**////////////////////////////////

                                    foreach ($array_input['identitas_detail_'.$item['item_id'].'_'.$i] as $identitas_detail) 
                                    {

                                        $data_identitas_detail = array(
                                            'transfer_item_identitas_id' => $id_transfer_item_identitas,
                                            'identitas_id'                => $identitas_detail['id'],
                                            'judul'                       => $identitas_detail['judul'],
                                            'value'                       => $identitas_detail['value'],
                                        );

                                        $id_transfer_item_identitas_detail = $this->transfer_item_identitas_detail_m->save($data_identitas_detail);

                                        $data_inventory_history_identitas_detail = array(
                                            'inventory_history_identitas_id' => $inventory_history_identitas_id,
                                            'identitas_id' => $identitas_detail['id'],
                                            'judul' => $identitas_detail['judul'],
                                            'value' => $identitas_detail['value'],
                                        );

                                        $inventory_history_identitas_detail = $this->inventory_history_identitas_detail_m->save($data_inventory_history_identitas_detail);
                                    }

                                }
                            }
                        }
                    }
                }
            }
        } 

        if ($update_request_item && $transfer_item && $save_inventory_history && $transfer_item_detail)
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data Request Item Dikirim.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        
        redirect('apotik/transfer_item');

    }

    public function kirim_item()
    {

        $command = $this->input->post('command');
        $array_input = $this->input->post();
        $user = $this->session->userdata('user_id');
        $date = date('Y-m-d H:i:s');

        // die(dump($array_input));
        
        if($command === 'kirim')
        {   
            $gudang_asal = $this->gudang_m->get($array_input['pk_value']);
            $gudang_asal = object_to_array($gudang_asal);

            $gudang_kirim = $this->gudang_m->get($array_input['gudang_ke']);
            $gudang_kirim = object_to_array($gudang_kirim);

            $last_id        = $this->transfer_item_m->get_id_transfer()->result_array();
            $last_id        = intval($last_id[0]['max_id'])+1;

            $format         = 'TR-'.date('m-Y').'-%04d';
            $id_transfer    = sprintf($format, $last_id, 4);
            
            
            $last_number    = $this->transfer_item_m->get_nomor_transfer()->result_array();
            $last_number    = intval($last_number[0]['max_no_transfer'])+1;

            $format_no      = '#TR#%04d/'.$gudang_asal['kode'].'-'.$gudang_kirim['kode'].'/'.romanic_number(date('m')).'/'.date('Y');
            $no_transfer    = sprintf($format_no, $last_number, 4);
            
            
            $last_number_do = $this->transfer_item_m->get_nomor_do()->result_array();
            $last_number_do = intval($last_number_do[0]['max_nomor_do'])+1;

            $format_do      = 'DO/%04d/'.$gudang_asal['kode'].'-'.$gudang_kirim['kode'].'/'.romanic_number(date('m')).'/'.date('Y');
            $no_do          = sprintf($format_do, $last_number_do, 4);

            $data_transfer_item = array(
                'id'             => $id_transfer,
                'dari_gudang_id' => $array_input['pk_value'],
                'ke_gudang_id'   => $array_input['gudang_ke'],
                'tanggal'        => date('Y-m-d H:i:s', strtotime($array_input['tanggal_transfer'])),
                'no_transfer'    => $no_transfer,
                'no_surat_jalan' => $no_do,
                'keterangan'     => $array_input['keterangan'],
                'status'         => 1,
                'is_active'      => 1,
                'created_by'     => $this->session->userdata('user_id'),
                'created_date'   => date('Y-m-d H:i:s')
            );

            $transfer_item = $this->transfer_item_m->add_data($data_transfer_item);

            $data_history = array(
                'transaksi_id' => $id_transfer,
                'transaksi_tipe' => 10
            );

            $inventory_history_id = $this->inventory_history_m->save($data_history);
            
            foreach ($array_input['items'] as $item)
            {

                if($item['item_id'] != "") 
                {
                    $last_id_detail        = $this->transfer_item_detail_m->get_id_transfer_detail()->result_array();
                    $last_id_detail        = intval($last_id_detail[0]['max_id'])+1;

                    $format_detail         = 'TRD-'.date('m-Y').'-%04d';
                    $id_transfer_detail          = sprintf($format_detail, $last_id_detail, 4);

                    $data_transfer_item_detail = array(
                        'id'               => $id_transfer_detail,
                        'transfer_item_id' => $id_transfer,
                        'item_id'          => $item['item_id'],
                        'item_satuan_id'   => $item['id_satuan'],
                        'jumlah'           => $item['jumlah_kirim'],    
                        'bn_sn_lot'        => $item['bn'],    
                        'expire_date'      => date('Y-m-d', strtotime($item['ed'])),
                        'created_by'       => $this->session->userdata('user_id'),
                        'created_date'     => date('Y-m-d H:i:s')
                    );

                    $transfer_item_detail = $this->transfer_item_detail_m->add_data($data_transfer_item_detail);

                    $data_inventory = $this->inventory_m->get_by(array('item_id' => $item['item_id'], 'item_satuan_id' => $item['id_satuan'], 'bn_sn_lot' => $item['bn'], 'expire_date' => date('Y-m-d', strtotime($item['ed'])), 'gudang_id' => $array_input['pk_value']));
                    
                    $data_inventory = object_to_array($data_inventory);

                    $x = 1;
                    $sisa = 0;
                    foreach ($data_inventory as $row_inv) {
                        
                        if($x == 1 && $item['jumlah_kirim'] >= $row_inv['jumlah']){

                            $last_inv_temp_id = $this->inventory_temporary_m->get_last_id()->result_array();
                            $last_inv_temp_id = intval($last_inv_temp_id[0]['id']) + 1;

                            $data_inventory_temporary = array(
                                'id'             => $last_inv_temp_id,
                                'tipe_transaksi' => 1,
                                'transaksi_id'   => $id_transfer,
                                'gudang_id'      => $array_input['gudang_ke'],
                                'pmb_id'         => $row_inv['pmb_id'],
                                'pembelian_detail_id'         => $row_inv['pembelian_detail_id'],
                                'box_paket_id'   => NULL,
                                'kode_box_paket' => NULL,
                                'item_id'        => $row_inv['item_id'],
                                'item_satuan_id' => $row_inv['item_satuan_id'],
                                'jumlah'         => $row_inv['jumlah'],
                                'tanggal_datang' => $row_inv['tanggal_datang'],
                                'harga_beli'     => $row_inv['harga_beli'],
                                'bn_sn_lot'      => $row_inv['bn_sn_lot'],
                                'expire_date'    => $row_inv['expire_date'],
                                'created_by'     => $this->session->userdata('user_id'),
                                'created_date'   => date('Y-m-d H:i:s')
                            );

                            $inv_temporary = $this->inventory_temporary_m->add_data($data_inventory_temporary);

                            $sisa = $item['jumlah_kirim'] - $row_inv['jumlah'];
                            $sisa_inv = 0;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $array_input['gudang_ke'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                'final_stock'          => $sisa_inv,
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'        => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                'expire_date'          => $row_inv['expire_date'],
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));


                        }
                        if($x == 1 && $item['jumlah_kirim'] < $row_inv['jumlah']){

                            $last_inv_temp_id = $this->inventory_temporary_m->get_last_id()->result_array();
                            $last_inv_temp_id = intval($last_inv_temp_id[0]['id']) + 1;

                            $data_inventory_temporary = array(
                                'id'             => $last_inv_temp_id,
                                'tipe_transaksi' => 1,
                                'transaksi_id'   => $id_transfer,
                                'gudang_id'      => $array_input['gudang_ke'],
                                'pmb_id'         => $row_inv['pmb_id'],
                                'box_paket_id'   => NULL,
                                'kode_box_paket' => NULL,
                                'item_id'        => $row_inv['item_id'],
                                'item_satuan_id' => $row_inv['item_satuan_id'],
                                'jumlah'         => $item['jumlah_kirim'],
                                'tanggal_datang' => $row_inv['tanggal_datang'],
                                'harga_beli'     => $row_inv['harga_beli'],
                                'bn_sn_lot'      => $row_inv['bn_sn_lot'],
                                'expire_date'    => $row_inv['expire_date'],
                                'created_by'     => $this->session->userdata('user_id'),
                                'created_date'   => date('Y-m-d H:i:s')
                            );

                            $inv_temporary = $this->inventory_temporary_m->add_data($data_inventory_temporary);

                            $sisa = 0;
                            $sisa_inv = $row_inv['jumlah'] - $item['jumlah_kirim'];

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $array_input['gudang_ke'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($item['jumlah_kirim'] * (-1)),
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'          => $item['jumlah_kirim'] * $row_inv['harga_beli'],
                                'final_stock'          => $sisa_inv,
                                'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                'expire_date'          => $row_inv['expire_date'],
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                        }

                        if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){

                            $last_inv_temp_id = $this->inventory_temporary_m->get_last_id()->result_array();
                            $last_inv_temp_id = intval($last_inv_temp_id[0]['id']) + 1;

                            $data_inventory_temporary = array(
                                'id'             => $last_inv_temp_id,
                                'tipe_transaksi' => 1,
                                'transaksi_id'   => $id_transfer,
                                'gudang_id'      => $array_input['gudang_ke'],
                                'pmb_id'         => $row_inv['pmb_id'],
                                'box_paket_id'   => NULL,
                                'kode_box_paket' => NULL,
                                'item_id'        => $row_inv['item_id'],
                                'item_satuan_id' => $row_inv['item_satuan_id'],
                                'jumlah'         => $row_inv['jumlah'],
                                'tanggal_datang' => $row_inv['tanggal_datang'],
                                'harga_beli'     => $row_inv['harga_beli'],
                                'bn_sn_lot'      => $row_inv['bn_sn_lot'],
                                'expire_date'    => $row_inv['expire_date'],
                                'created_by'     => $this->session->userdata('user_id'),
                                'created_date'   => date('Y-m-d H:i:s')
                            );

                            $inv_temporary = $this->inventory_temporary_m->add_data($data_inventory_temporary);

                            $sisa = $sisa - $row_inv['jumlah'];
                            $sisa_inv = 0;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $array_input['gudang_ke'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($row_inv['jumlah'] * (-1)),
                                'final_stock'          => $sisa_inv,
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'          => $row_inv['jumlah'] * $row_inv['harga_beli'],
                                'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                'expire_date'          => $row_inv['expire_date'],
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            $delete_inventory = $this->inventory_m->delete_by(array('inventory_id' => $row_inv['inventory_id']));
                        }
                        if($x != 1 && $sisa > 0 && $sisa < $row_inv['jumlah']){

                            $last_inv_temp_id = $this->inventory_temporary_m->get_last_id()->result_array();
                            $last_inv_temp_id = intval($last_inv_temp_id[0]['id']) + 1;

                            $data_inventory_temporary = array(
                                'id'             => $last_inv_temp_id,
                                'tipe_transaksi' => 1,
                                'transaksi_id'   => $id_transfer,
                                'gudang_id'      => $array_input['gudang_ke'],
                                'pmb_id'         => $row_inv['pmb_id'],
                                'box_paket_id'   => NULL,
                                'kode_box_paket' => NULL,
                                'item_id'        => $row_inv['item_id'],
                                'item_satuan_id' => $row_inv['item_satuan_id'],
                                'jumlah'         => $sisa,
                                'tanggal_datang' => $row_inv['tanggal_datang'],
                                'harga_beli'     => $row_inv['harga_beli'],
                                'bn_sn_lot'      => $row_inv['bn_sn_lot'],
                                'expire_date'    => $row_inv['expire_date'],
                                'created_by'     => $this->session->userdata('user_id'),
                                'created_date'   => date('Y-m-d H:i:s')
                            );

                            $inv_temporary = $this->inventory_temporary_m->add_data($data_inventory_temporary);

                            $sisa_inv = $row_inv['jumlah'] - $sisa;

                            $data_inventory_history_detail = array(
                                'inventory_history_id' => $inventory_history_id,
                                'gudang_id'            => $array_input['gudang_ke'],
                                'pmb_id'               => $row_inv['pmb_id'],
                                'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                                'box_paket_id'         => NULL,
                                'kode_box_paket'       => NULL,
                                'item_id'              => $row_inv['item_id'],
                                'item_satuan_id'       => $row_inv['item_satuan_id'],
                                'initial_stock'        => $row_inv['jumlah'],
                                'change_stock'         => ($sisa * (-1)),
                                'final_stock'          => $sisa_inv,
                                'harga_beli'           => $row_inv['harga_beli'],
                                'total_harga'          => $sisa * $row_inv['harga_beli'],
                                'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                                'expire_date'          => $row_inv['expire_date'],
                                'created_by'           => $this->session->userdata('user_id'),
                                'created_date'         => date('Y-m-d H:i:s')
                            );

                            $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                            $sisa = 0;



                            $update_inventory = $this->inventory_m->update_by($this->session->userdata('user'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                        }

                        $x++;        
                    }
                }
           }



            if ($transfer_item_detail && $transfer_item)
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Transfer Item berhasil Ditambah.", $this->session->userdata("language")),
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

                $data_transfer_item = array(

                    'dari_gudang_id' => $array_input['gudang_dari'],
                    'ke_gudang_id'   => $array_input['gudang_ke'],
                    'tanggal'        => date('Y-m-d H:i:s', strtotime($array_input['tanggal_transfer'])),
                    'no_surat_jalan' => $array_input['no_surat_jalan'],
                    'keterangan'     => $array_input['keterangan'],
                    'status'         => 1,
                    'is_active'      => 1,

                );

                $transfer_item_update = $this->transfer_item_m->save($data_transfer_item, $pk_value);
            }

            foreach ($array_input['items'] as $item) 
            {
                # code...
                if($item['item_id'] != "")
                {

                    $data_transfer_item_detail = array(

                    'transfer_item_id' => $transfer_item_update,
                    'item_id'          => $item['item_id'],
                    'item_satuan_id'   => $item['satuan'],
                    'jumlah'           => $item['jumlah_kirim'],    


                    );
                    
                    $transfer_item_detail_update = $this->transfer_item_detail_m->save($data_transfer_item_detail, $transfer_item_update);
                    // die_dump($this->db->last_query());
                }
            }

            if ($transfer_item_update && $transfer_item_detail_update)
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Transfer Item berhasil Diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }


        }
        
        redirect('apotik/transfer_item');

    }



     public function modal_identitas($item_id, $item_satuan_id, $row_id)
    {
        $data = array(
        
            'item_id'        => $item_id,
            'item_satuan_id' => $item_satuan_id,
            'row_id'         => $row_id
    
        );

        $this->load->view('apotik/transfer_item/modal/modal_identitas.php', $data);
    
    }

     public function modal_identitas_receive($item_id, $item_satuan_id, $row_id)
    {
        $data = array(
        
            'item_id'        => $item_id,
            'item_satuan_id' => $item_satuan_id,
            'row_id'         => $row_id
    
        );

        $this->load->view('apotik/transfer_item/modal/modal_identitas_receive.php', $data);
    
    }

    public function modal_identitas_view($item_id, $item_satuan_id, $row_id)
    {
        $data = array(
        
            'item_id'        => $item_id,
            'item_satuan_id' => $item_satuan_id,
            'row_id'         => $row_id
    
        );

        $this->load->view('apotik/transfer_item/modal/modal_identitas_view.php', $data);
    
    }

    public function modal_identitas_proses_permintaan($item_id, $item_satuan_id, $row_id)
    {
        $data = array(
        
            'item_id'        => $item_id,
            'item_satuan_id' => $item_satuan_id,
            'row_id'         => $row_id
    
        );

        $this->load->view('apotik/transfer_item/modal/modal_identitas_proses_permintaan.php', $data);
    
    }

    



    

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */