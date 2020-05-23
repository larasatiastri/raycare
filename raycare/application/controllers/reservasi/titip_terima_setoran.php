
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Titip_terima_setoran extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 7;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(15, 7);       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('reservasi/titip_terima_setoran/titip_setoran_m');
        $this->load->model('reservasi/titip_terima_setoran/titip_setoran_detail_m');
        $this->load->model('reservasi/titip_terima_setoran/setoran_keuangan_kasir_m');
        $this->load->model('reservasi/titip_terima_setoran/setoran_kasir_keuangan_detail_m');
        $this->load->model('reservasi/titip_terima_uang/kasir_titip_uang_m');
        $this->load->model('reservasi/titip_terima_uang/kasir_terima_uang_m');
        $this->load->model('reservasi/titip_terima_uang/kasir_biaya_m');
        $this->load->model('reservasi/pembayaran/pembayaran_pasien_m');
        $this->load->model('master/cabang_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/reservasi/titip_terima_setoran/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reservasi Titip & Terima Uang', $this->session->userdata('language')), 
            'header'         => translate('Reservasi Titip & Terima Uang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/titip_terima_setoran/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/reservasi/titip_terima_setoran/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah Titip Setoran", $this->session->userdata("language")), 
            'header'         => translate("Tambah Titip Setoran", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/titip_terima_setoran/add',
            'flag'           => 'add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

        
    public function listing_titip_setoran($status = null){

        $result = $this->titip_setoran_m->get_datatable($status);
        // die_dump($this->db->last_query());  
        // die_dump($result);

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        $i=0;
        $rupiah = 0;
        $rupiah_terima = 0;
        $nama = '';
        $status = '';
        $info = '';
        $in_titip_setoran_id = '';

        foreach($records->result_array() as $row)
        {
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd');
            $bulan = explode('-', $row['tanggal']);

            if ($row['status'] == 1)
                $status = '<span class="label label-warning">Belum Diterima</span>';

            if ($row['status'] == 2)
                $status = '<span class="label label-success">Sudah Diterima</span>';

            $info = '<span class="input-group-btn">
                        <a class="btn btn-xs btn-primary pilih-user" data-id="'.$row['id'].'" name="pilih_user" id="pilih_user" title="'.translate('Daftar Biaya', $this->session->userdata('language')).'">
                            <i class="fa fa-info"></i>
                        </a>
                    </span>';

            $in_titip_setoran_id = '<input class="form-control input-sm hidden" id="titip_setoran_id" name="titip_setoran_id" value="'.$row['id'].'">';
 

            $output['data'][] = array(
                
                '<div class="text-center">'.$effective_date.$in_titip_setoran_id.'</div>',
                '<div class="text-left">'.$row['nama_user_created'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="row"><div class="col-md-2">'.$info.'</div><div class="col-md-9 text-right style="margin-right:-30px !important""> Rp. '.number_format($row['rupiah'], 0,'','.').',-</div>',
                '<div class="text-center">'.$status.'</div>',
            );

            $i++;
        }

        echo json_encode($output);
    }



    public function listing_terima_setoran()
    {

        $user_id = $this->session->userdata('user_id');
                
        $result = $this->setoran_keuangan_kasir_m->get_datatable_pilih_setoran_keuangan_kasir($user_id);
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
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd');
            $bulan = explode('-', $row['tanggal']);

            $action = '';
            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Terima', $this->session->userdata('language')).'" href="'.base_url().'reservasi/titip_terima_setoran/terima_setoran/'.$row['id'].'" data-toggle="modal" data-target="#popup_modal" class="btn btn-xs green-haze"><i class="fa fa-check"></i></a>';
                
                       // <a title="'.translate('Terima', $this->session->userdata('language')).'" href="'.base_url().'reservasi/titip_terima_setoran/terima/'.$row['id'].'" data-toggle="modal" class="btn btn-xs green-haze"><i class="fa fa-check"></i></a>
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$effective_date.'</div>',
                '<div class="text-left">'.$row['nama_user_created'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-right">Rp. '.number_format($row['total_setor'], 0,'','.').',-</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_kasir_biaya($titip_setoran_id)
    {

        $result = $this->titip_setoran_detail_m->get_datatable_kasir_biaya($titip_setoran_id);
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
            $date = date_create($row['tanggal_kasir_biaya']);
            $effective_date = date_format($date, 'd M y');
            
            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-right">Rp. '.number_format($row['rupiah'], 0,'','.').',-</div>' ,
                '<div class="text-center">'.$effective_date.'</div>' ,
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pembayaran_pasien($titip_setoran_id)
    {

        $result = $this->titip_setoran_detail_m->get_datatable_pembayaran_pasien($titip_setoran_id);
        //die_dump($this->db->last_query());
        // die(dump($result));
        
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
            
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd M y');

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['no_faktur'].'</div>',
                '<div class="text-right">Rp. '.number_format($row['bayar'], 0,'','.').',-</div>' ,
                '<div class="text-center">'.$effective_date.'</div>' ,
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_add_detail_setoran_biaya()
    {

        $result = $this->kasir_biaya_m->get_datatable_setoran_biaya();
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
        $rupiah = '';
        $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {

            $in_count       = '<input class="form-control input-sm hidden" id="count_temp" value="'.$i.'">';
            $in_biaya_id    = '<input class="form-control input-sm hidden" id="biaya_id" name="biaya['.$i.'][biaya_id]" value="'.$row['id'].'">';
            $in_rupiah      = '<input class="form-control input-sm hidden" id="rupiah" name="biaya['.$i.'][rupiah]" value="'.$row['rupiah'].'">';
            $in_rupiah      = '<input class="form-control input-sm hidden" id="rupiah" name="biaya['.$i.'][rupiah]" value="'.$row['rupiah'].'">';
            $in_tanggal     = '<input class="form-control input-sm hidden" id="tanggal" name="biaya['.$i.'][tanggal]" value="'.$row['tanggal'].'">';

            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd M y');
            
            $output['aaData'][] = array(
                '<div class="text-left">'.$row['keterangan'].$in_biaya_id.'</div>',
                '<div class="text-right">Rp. '.number_format($row['rupiah'], 0,'','.').',-'.$in_rupiah.'</div>' ,
                '<div class="text-center">'.$effective_date.$in_tanggal.'</div>' ,
                ($i == $count) ? '<div class="text-center"><input class="checkboxes" name="biaya['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['kasir_terima_uang_id'].'">'.$in_count.'</div>' : '<div class="text-center"><input class="checkboxes" name="biaya['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['kasir_terima_uang_id'].'"></div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_add_detail_setoran_invoice()
    {

        $result = $this->pembayaran_pasien_m->get_datatable_setoran_invoice();
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
        $rupiah = '';
        $count = count($records->result_array());

        foreach($records->result_array() as $row)
        {

            $in_count       = '<input class="form-control input-sm hidden" id="count_temp" value="'.$i.'">';
            $in_invoice_id  = '<input class="form-control input-sm hidden" id="invoice_id" name="invoice['.$i.'][invoice_id]" value="'.$row['id'].'">';
            $in_rupiah      = '<input class="form-control input-sm hidden" id="bayar" name="invoice['.$i.'][bayar]" value="'.$row['bayar'].'">';
            $in_tanggal     = '<input class="form-control input-sm hidden" id="tanggal" name="invoice['.$i.'][tanggal]" value="'.$row['tanggal'].'">';

            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd M y');
            
            $output['aaData'][] = array(
                '<div class="text-left">'.$row['no_faktur'].$in_invoice_id.'</div>',
                '<div class="text-right">Rp. '.number_format($row['bayar'], 0,'','.').',-'.$in_rupiah.'</div>' ,
                '<div class="text-center">'.$effective_date.$in_tanggal.'</div>' ,
                ($i == $count) ? '<div class="text-center"><input class="checkboxes" name="invoice['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['kasir_terima_uang_id'].'">'.$in_count.'</div>' : '<div class="text-center"><input class="checkboxes" name="invoice['.$i.'][checkbox]" id="checkbox_'.$i.'" type="checkbox" data-rp="'.$row['kasir_terima_uang_id'].'"></div>',
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_pilih_user_terima_uang()
    {

        // $cabang_id = $this->session->userdata('cabang_id');
        // die_dump($this->db->last_query());
                // die_dump($cabang_id);
        $result = $this->user_m->get_datatable_pilih_user_setoran();
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
            
            $action = '';
            if($row['is_active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs btn-primary select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['kasir_titip_uang_id'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-center">'.$row['nama_user_level'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function verifikasi()
    {
        $array_input = $this->input->post();
        $user_id     = $this->session->userdata('user_id');
        $user        = $this->user_m->get($user_id);
        $password    = $this->user_m->hash($array_input['password']);
        // die_dump($array_input['username']);


        if ($array_input['command'] === 'add')
        {
            if($password == $user->password && $array_input['username'] == $user->username) 
            {
                // die_dump('data benar');
                $data = array(

                    'status' => 2,

                );
            
                $update_terima_setoran = $this->setoran_keuangan_kasir_m->save($data, $array_input['id_terima_setoran']);

                if ($update_terima_setoran) 
                {
                    $flashdata = array(
                        "type"     => "success",
                        "msg"      => translate("Data Telah Di verifikasi", $this->session->userdata("language")),
                        "msgTitle" => translate("Berhasil", $this->session->userdata("language"))    
                        );
                    $this->session->set_flashdata($flashdata);
                }
            } 

            elseif ($password != $user->password && $array_input['username'] != $user->username) 
            {
                $flashdata = array(
                    "type"     => "error",
                    "msg"      => translate("Username & Password Salah", $this->session->userdata("language")),
                    "msgTitle" => translate("Coba Lagi", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }

            redirect("reservasi/titip_terima_setoran");

        }


    }

    public function save()
    {
        $array_input = $this->input->post();
        $items  = $this->input->post('biaya');
        $items2 = $this->input->post('invoice');
        // die_dump($array_input);
        // die_dump($items);
        if ($array_input['command'] === 'add')
        {  

                if($array_input['id_ref_pasien'] != "")
                {
                    $data_titip_setoran = array(
                        'tanggal'               => date("Y-m-d", strtotime($array_input['tanggal'])),
                        'penerima_id'           => $array_input['id_ref_pasien'],
                        'rupiah'                => $array_input['rupiah'],
                        'subjek'                => $array_input['subjek'],
                        'keterangan'            => $array_input['keterangan'],
                        'status'                => 1,
                   );
                    
                    $save_setoran_keuangan_kasir = $this->titip_setoran_m->save($data_titip_setoran);
                    // die_dump($this->db->last_query());
                    // 

                    
                }

                foreach ($items as $biaya) {
                
                    if (isset($biaya['checkbox'])) {

                        $data_setoran_biaya = array(

                            'titip_setoran_id' => $save_setoran_keuangan_kasir,
                            'detail_id'                 => $biaya['biaya_id'],
                            'tipe_detail'               => 1,
                            'tanggal'                   => date("Y-m-d", strtotime($biaya['tanggal'])),
                            'rupiah'                    => $biaya['rupiah'],
                            'status'                    => 1,

                            );

                        $setoran_kasir_keuangan_detail = $this->titip_setoran_detail_m->save($data_setoran_biaya);
                        // $pembayaran_tindakan_pasien2 = $this->os_pembayaran_tindakan_m->delete($biaya['id']);
                        // die(dump($this->db->last_query()));
                        
                        $data_kasir_biaya = array(

                            'is_done'   => 1,

                        );

                        $kasir_biaya = $this->kasir_biaya_m->save($data_kasir_biaya, $biaya['biaya_id']);

                    }


                } 

                foreach ($items2 as $invoice) {
                
                    if (isset($invoice['checkbox'])) {

                        $data_setoran_invoice = array(

                            'titip_setoran_id' => $save_setoran_keuangan_kasir,
                            'detail_id'                 => $invoice['invoice_id'],
                            'tipe_detail'               => 2,
                            'tanggal'                   => date("Y-m-d", strtotime($invoice['tanggal'])),
                            'rupiah'                    => $invoice['bayar'],
                            'status'                    => 1,

                            );

                        $setoran_kasir_keuangan_detail = $this->titip_setoran_detail_m->save($data_setoran_invoice);
                        // $pembayaran_tindakan_pasien2 = $this->os_pembayaran_tindakan_m->delete($biaya['id']);
                        // 
                        
                        $data_pembayaran_pasien = array(

                            'is_done'   => 1,

                        );

                        $pembayaran_pasien = $this->pembayaran_pasien_m->save($data_pembayaran_pasien, $invoice['invoice_id']);
                    } 
                }

            if ($save_setoran_keuangan_kasir && $setoran_kasir_keuangan_detail && $kasir_biaya && $pembayaran_pasien) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Titip Setoran berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        }

        redirect("reservasi/titip_terima_setoran");
    }


    public function terima_setoran($id)
    {
        $data = array(
            'id' => $id
        );
        $this->load->view('reservasi/titip_terima_setoran/modals_setoran',$data);
    }


}

/* End of file titip_terima_setoran.php */
/* Location: ./application/controllers/reservasi/titip_terima_setoran.php */