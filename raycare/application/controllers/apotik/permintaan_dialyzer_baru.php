<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_dialyzer_baru extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '9379b898069e80d3120a88c78a170aff';                  // untuk check bit_access

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

        $this->load->model('apotik/permintaan_dialyzer_baru_m');
        $this->load->model('apotik/item_m');
        $this->load->model('apotik/item_satuan_m');
        $this->load->model('master/item/item_harga_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('apotik/transfer_item/inventory_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('klinik_hd/tindakan_hd_m');
        $this->load->model('reservasi/invoice/draf_invoice_m');   
        $this->load->model('reservasi/invoice/draf_invoice_detail_m'); 
        
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/apotik/permintaan_dialyzer_baru/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Permintaan Dialyzer Baru', $this->session->userdata('language')), 
            'header'         => translate('Permintaan Dialyzer Baru', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/permintaan_dialyzer_baru/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/apotik/permintaan_dialyzer_baru/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Permintaan Dialyzer Baru', $this->session->userdata('language')), 
            'header'         => translate('Permintaan Dialyzer Baru', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'apotik/permintaan_dialyzer_baru/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing($status)
    {        
        $result = $this->permintaan_dialyzer_baru_m->get_datatable($status);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {

            $action = '<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'apotik/permintaan_dialyzer_baru/proses/'.$row['id'].'" data-toggle="modal" data-target="#modal_proses" class="btn btn-primary"><i class="fa fa-check"></i></a>
             <a title="'.translate('Batalkan', $this->session->userdata('language')).'" data-id="'.$row['id'].'"
                data-confirm="'.translate('Anda yakin untuk membatalkan resep ini?', $this->session->userdata('language')).'" name="delete_resep[]" class="btn btn-danger"><i class="fa fa-times"></i></a>';

            $cabang = $this->cabang_m->get($row['cabang_id']);

            $path_model = 'klinik_hd/tindakan_hd_m';
            $wheres = array(
                'id' => $row['tindakan_id']
            );
            $data_tindakan = get_data_api(config_item('ip_real'),$path_model,$wheres);
            $data_tindakan = object_to_array(json_decode($data_tindakan));

            $path_model = 'klinik_hd/bed_m';
            $wheres_bed = array(
                'id' => $data_tindakan[0]['bed_id']
            );
            $data_bed = get_data_api(config_item('ip_real'),$path_model,$wheres_bed);
            $data_bed = object_to_array(json_decode($data_bed));

        
            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['nama_cabang'].'</div>',
                '<div class="text-left">'.$row['no_permintaan'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$row['nama_minta'].'</div>',
                '<div class="text-center">'.date('d M Y H:i', strtotime($row['created_date'])).'</div>',
                '<div class="text-center">'.$data_bed[0]['kode'].' / '.$data_bed[0]['lantai_id'].'</div>',
                
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    } 

    public function listing_history($status)
    {        
        $result = $this->permintaan_dialyzer_baru_m->get_datatable($status);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=0;
        $pembuat = '';
        foreach($records->result_array() as $row)
        {
            $status = '';
            $cabang = $this->cabang_m->get($row['cabang_id']);

            $path_model = 'klinik_hd/tindakan_hd_m';
            $wheres = array(
                'id' => $row['tindakan_id']
            );
            $data_tindakan = get_data_api(config_item('ip_real'),$path_model,$wheres);
            $data_tindakan = object_to_array(json_decode($data_tindakan));

            $path_model = 'klinik_hd/bed_m';
            $wheres_bed = array(
                'id' => $data_tindakan[0]['bed_id']
            );
            $data_bed = get_data_api(config_item('ip_real'),$path_model,$wheres_bed);
            $data_bed = object_to_array(json_decode($data_bed));

            $datetime1 = new DateTime($row['modified_date']);
            $datetime2 = new DateTime($row['created_date']);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%a d %h h %i m %S s');

            if($row['status'] == 2) {

                $status = '<div class="text-center"><span class="label label-md label-success">Diproses</span></div>';

            } elseif($row['status'] == 3) {

                $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
            }
    

            $output['data'][] = array(
                $row['id'],
                '<div class="text-left">'.$row['nama_cabang'].'</div>',
                '<div class="text-left">'.$row['no_permintaan'].'</div>',
                '<div class="text-left">'.$row['nama_pasien'].'</div>',
                '<div class="text-left">'.$row['nama_minta'].'</div>',
                '<div class="text-center">'.date('d M Y H:i', strtotime($row['created_date'])).'</div>',
                '<div class="text-center">'.$elapsed.'</div>',
                '<div class="text-center">'.$data_bed[0]['kode'].' / '.$data_bed[0]['lantai_id'].'</div>',
                '<div class="text-left">'.$row['nama_apoteker'].'</div>',
                '<div class="text-left">'.$row['nama_terima'].'</div>',
                '<div class="text-left">'.$row['nama_dialyzer'].'</div>',
                '<div class="text-left">'.$row['bn_sn_lot'].'</div>',
                '<div class="text-left">'.$row['expired_date'].'</div>',
                '<div class="text-center inline-button-table">'.$status.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }


    public function save_proses()
    {   
        $array_input = $this->input->post();
        //die(dump($array_input));

        if ($array_input['command'] == "add") {
            
            $response = new stdClass;
            $response->success = false;
            $response->msg = translate("Data permintaan dialyzer gagal diproses", $this->session->userdata("language"));

            $permintaan_id = $array_input['id'];
            $tindakan_id = $array_input['tindakan_id'];
            $cabang_id = $array_input['cabang_id'];
            $pasien_id = $array_input['pasien_id'];
            $bn_ed = $array_input['bn_sn_lot'];
            $reason = $array_input['reason'];
            $bn_ed = explode('|', $bn_ed);

            $data_cabang = $this->cabang_m->get_by(array('id' => $cabang_id), true);
            $data_cabang = object_to_array($data_cabang);

            $data_item = $this->item_m->get_by(array('id' => $array_input['dialyzer_id']), true);
            $data_pasien = $this->pasien_m->get_by(array('id' => $pasien_id), true);

            $tgl_daftar = $data_pasien->tanggal_daftar;
            $tgl_daftar = date('y', strtotime($tgl_daftar));

            $no_member = $data_pasien->no_member;
            if(substr($no_member, 0, 12) == 'KRC0115R027-'){
                $no_member = substr($no_member, -3);
            }else{
                $no_member = substr($no_member, -4);
                $no_member = str_replace('-', '', $no_member);
            }
            
            $max_kode = 1;

            $format         = $tgl_daftar.$no_member.'-%02d';
            $kode_reuse       = sprintf($format, $max_kode, 2);

            $data_history = array(
                'transaksi_id' => $permintaan_id,
                'transaksi_tipe' => 16
            );

            $inventory_history_id = $this->inventory_history_m->save($data_history);
            //die(dump($this->db->last_query()));
  

            $data_inventory = $this->inventory_m->get_by(array('item_id' => $array_input['dialyzer_id'], 'bn_sn_lot' => $bn_ed[0], 'expire_date' => date('Y-m-d', strtotime($bn_ed[1])), 'gudang_id' => $array_input['gudang_id']));
                
            $data_inventory = object_to_array($data_inventory);  
            
            $harga_jual = $this->item_harga_m->get_harga_item_satuan($array_input['dialyzer_id'], $data_inventory[0]['item_satuan_id'])->result_array();

            $date = date('Y-m-d H:i:s');
            $expire_date = date('Y-m-d', strtotime($bn_ed[1]));

            $path_model = 'klinik_hd/tindakan_hd_item_m';
            $data_tindakan_hd_item = array(
                'waktu'          => $date,
                'user_id'        => $this->session->userdata('user_id'),
                'tindakan_hd_id' => $tindakan_id,
                'item_id'        => $array_input['dialyzer_id'],
                'jumlah'         => 1,
                'item_satuan_id' => $data_inventory[0]['item_satuan_id'],
                'kode_dialyzer'  => $kode_reuse,
                'bn_sn_lot'      => $bn_ed[0],
                'expire_date'    => $expire_date,
                'index'          => 1,
                'harga_beli'     => ($data_inventory[0]['harga_beli'] != NULL)?$data_inventory[0]['harga_beli']:0,
                'harga_jual'     => (count($harga_jual) != 0)?$harga_jual[0]['harga']:0,     // didapatkan dari item_harga sesuai 
                'tipe_pemberian' => 3,
                'is_active'      => 1
            );

            $tindakan_hd_item_id = insert_data_api($data_tindakan_hd_item,config_item('ip_real'),$path_model);
            // die(dump($tindakan_hd_item_id));
           
            $path_model = 'klinik_hd/tindakan_hd_penaksiran_m';
            $data_assesment = array(
                'dialyzer_new'   => 1,
                'dialyzer_reuse' => 0,
                'dialyzer_id'    => $array_input['dialyzer_id'],
                'bn_dialyzer'    => $bn_ed[0],
                'dialyzer'       => $data_item->nama,
                'kode_dialyzer'  => $kode_reuse,
                'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => $date,
            );
            $wheres_assesment = array(
                'tindakan_hd_id' => $tindakan_id
            );

            $tindakan_hd_ass_id = update_data_api($data_assesment,config_item('ip_real'),$path_model,$wheres_assesment);

            $data_tindakan_hd = $this->tindakan_hd_m->get_by(array('id' => $tindakan_id), true);

            $item = $this->item_m->get_by(array('id' => $array_input['dialyzer_id']), true);

            if($reason === '2'){
                $draf_invoice_swasta = $this->draf_invoice_m->get_by(array('pasien_id' => $pasien_id, 'jenis_invoice' => 1), true);

                $pasien = $this->pasien_m->get_by(array('id' => $pasien_id), true);


                if(count($draf_invoice_swasta) == 0){
                    $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                    $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                    
                    $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                    $data_draft_tindakan = array(
                        'id'    => $id_draft,
                        'pasien_id'    => $pasien_id,
                        'tipe'  => 1,
                        'cabang_id'  => $this->session->userdata('cabang_id'),
                        'tipe_pasien'  => 1,
                        'nama_pasien'  => $pasien->nama,
                        'shift'  => $data_tindakan_hd->shift,
                        'user_level_id'  => $this->session->userdata('level_id'),
                        'jenis_invoice' => 1,
                        'status'    => 1,
                        'akomodasi' => 0,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $id_draft,
                        'tipe_item' => 3,
                        'item_id'   => $array_input['dialyzer_id'],
                        'nama_tindakan' => $item->nama,
                        'harga_jual'             =>  (count($harga_jual) != 0)?$harga_jual[0]['harga']:0,
                        'status' => 1,
                        'jumlah' => 1,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                }elseif(count($draf_invoice_swasta) != 0){

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $draf_invoice_swasta->id,
                        'tipe_item' => 3,
                        'item_id'   => $array_input['dialyzer_id'],
                        'nama_tindakan' => $item->nama,
                        'harga_jual'             =>  (count($harga_jual) != 0)?$harga_jual[0]['harga']:0,
                        'status' => 1,
                        'jumlah' => 1,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                }

            }
            if($reason === '1' && $data_tindakan_hd->penjamin_id == 1){
                $draf_invoice_swasta = $this->draf_invoice_m->get_by(array('pasien_id' => $pasien_id, 'jenis_invoice' => 1), true);

                $pasien = $this->pasien_m->get_by(array('id' => $pasien_id), true);


                if(count($draf_invoice_swasta) == 0){
                    $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                    $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                    
                    $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                    $data_draft_tindakan = array(
                        'id'    => $id_draft,
                        'pasien_id'    => $pasien_id,
                        'tipe'  => 1,
                        'cabang_id'  => $this->session->userdata('cabang_id'),
                        'tipe_pasien'  => 1,
                        'nama_pasien'  => $pasien->nama,
                        'shift'  => $data_tindakan_hd->shift,
                        'user_level_id'  => $this->session->userdata('level_id'),
                        'jenis_invoice' => 1,
                        'status'    => 1,
                        'akomodasi' => 0,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $id_draft,
                        'tipe_item' => 1,
                        'item_id'   => 1,
                        'nama_tindakan' => 'Paket Hemodialisa',
                        'harga_jual'             =>  1050000,
                        'status' => 1,
                        'jumlah' => 1,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                }elseif(count($draf_invoice_swasta) != 0){

                    $data_draf_invoice_detail_swasta = $this->draf_invoice_detail_m->get_by(array('draf_invoice_id' => $draf_invoice_swasta->id, 'tipe_item' => 1, 'harga_jual' => 900000), true);

                    if(count($data_draf_invoice_detail_swasta) == 0){
                        $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                        $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                        
                        $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                        $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                        $data_draft_tindakan_detail = array(
                            'id'    => $id_draft_detail,
                            'draf_invoice_id'    => $draf_invoice_swasta->id,
                            'tipe_item' => 1,
                            'item_id'   => 1,
                            'nama_tindakan' => 'Paket Hemodialisa',
                            'harga_jual'             =>  1050000,
                            'status' => 1,
                            'jumlah' => 1,
                            'is_active'    => 1,
                            'created_by'    => $this->session->userdata('user_id'),
                            'created_date'    => date('Y-m-d H:i:s')
                        );

                        $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                    }elseif(count($data_draf_invoice_detail_swasta) != 0){

                        $data_draf_detail = array(
                            'hpp' => 1050000,
                            'harga_jual' => 1050000,
                        );
                        
                        $edit_draf_detail = $this->draf_invoice_detail_m->edit_data($data_draf_detail, $data_draf_invoice_detail_swasta->id);
                    }
                    
                }

            }

            if($reason === '1' && $data_tindakan_hd->penjamin_id != 1){
                $draf_invoice_bpjs = $this->draf_invoice_m->get_by(array('pasien_id' => $pasien_id, 'jenis_invoice' => 2), true);

                $pasien = $this->pasien_m->get_by(array('id' => $pasien_id), true);


                if(count($draf_invoice_bpjs) == 0){
                    $last_id_draft       = $this->draf_invoice_m->get_id_draf()->result_array();
                    $last_id_draft       = intval($last_id_draft[0]['max_id'])+1;
                    
                    $format_id_draft     = 'DI-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft = sprintf($format_id_draft, $last_id_draft, 4);

                    $data_draft_tindakan = array(
                        'id'    => $id_draft,
                        'pasien_id'    => $pasien_id,
                        'tipe'  => 1,
                        'cabang_id'  => $this->session->userdata('cabang_id'),
                        'tipe_pasien'  => 1,
                        'nama_pasien'  => $pasien->nama,
                        'shift'  => $data_tindakan_hd->shift,
                        'user_level_id'  => $this->session->userdata('level_id'),
                        'jenis_invoice' => 1,
                        'status'    => 1,
                        'akomodasi' => 0,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf = $this->draf_invoice_m->add_data($data_draft_tindakan);

                    $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                    $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                    
                    $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                    $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                    $data_draft_tindakan_detail = array(
                        'id'    => $id_draft_detail,
                        'draf_invoice_id'    => $id_draft,
                        'tipe_item' => 1,
                        'item_id'   => 1,
                        'nama_tindakan' => 'Paket Hemodialisa',
                        'harga_jual'             =>  1050000,
                        'status' => 1,
                        'jumlah' => 1,
                        'is_active'    => 1,
                        'created_by'    => $this->session->userdata('user_id'),
                        'created_date'    => date('Y-m-d H:i:s')
                    );

                    $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                }elseif(count($draf_invoice_bpjs) != 0){

                   $data_draf_invoice_detail_bpjs = $this->draf_invoice_detail_m->get_by(array('draf_invoice_id' => $draf_invoice_bpjs->id, 'tipe_item' => 1, 'harga_jual' => 900000), true);

                    if(count($data_draf_invoice_detail_bpjs) == 0){
                        $last_id_draft_detail       = $this->draf_invoice_detail_m->get_id_draf_detail()->result_array();
                        $last_id_draft_detail       = intval($last_id_draft_detail[0]['max_id'])+1;
                        
                        $format_id_draft_detail     = 'DID-'.date('m').'-'.date('Y').'-%04d';
                        $id_draft_detail = sprintf($format_id_draft_detail, $last_id_draft_detail, 4);

                        $data_draft_tindakan_detail = array(
                            'id'    => $id_draft_detail,
                            'draf_invoice_id'    => $draf_invoice_swasta->id,
                            'tipe_item' => 1,
                            'item_id'   => 1,
                            'nama_tindakan' => 'Paket Hemodialisa',
                            'harga_jual'             =>  1050000,
                            'status' => 1,
                            'jumlah' => 1,
                            'is_active'    => 1,
                            'created_by'    => $this->session->userdata('user_id'),
                            'created_date'    => date('Y-m-d H:i:s')
                        );

                        $save_draf_detail = $this->draf_invoice_detail_m->add_data($data_draft_tindakan_detail);
                    }elseif(count($data_draf_invoice_detail_bpjs) != 0){
                        $data_draf_detail = array(
                            'hpp' => 1050000,
                            'harga_jual' => 1050000,
                        );
                        $edit_draf_detail = $this->draf_invoice_detail_m->edit_data($data_draf_detail, $data_draf_invoice_detail_bpjs->id);
                    }
                }

            }

            $x = 1;
            $sisa = 0;
            foreach ($data_inventory as $row_inv) {
                $jumlah_pakai = 1;
                if($x == 1 && $jumlah_pakai >= $row_inv['jumlah']){


                    $sisa = $jumlah_pakai - $row_inv['jumlah'];
                    $sisa_inv = 0;

                    $data_inventory_history_detail = array(
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $row_inv['gudang_id'],
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
                if($x == 1 && $jumlah_pakai < $row_inv['jumlah']){

                    $sisa = 0;
                    $sisa_inv = $row_inv['jumlah'] - $jumlah_pakai;

                    $data_inventory_history_detail = array(
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $row_inv['gudang_id'],
                        'pmb_id'               => $row_inv['pmb_id'],
                        'pembelian_detail_id'  => $row_inv['pembelian_detail_id'],
                        'box_paket_id'         => NULL,
                        'kode_box_paket'       => NULL,
                        'item_id'              => $row_inv['item_id'],
                        'item_satuan_id'       => $row_inv['item_satuan_id'],
                        'initial_stock'        => $row_inv['jumlah'],
                        'change_stock'         => ($jumlah_pakai * (-1)),
                        'harga_beli'           => $row_inv['harga_beli'],
                        'total_harga'          => $jumlah_pakai * $row_inv['harga_beli'],
                        'final_stock'          => $sisa_inv,
                        'bn_sn_lot'            => $row_inv['bn_sn_lot'],
                        'expire_date'          => $row_inv['expire_date'],
                        'created_by'           => $this->session->userdata('user_id'),
                        'created_date'         => date('Y-m-d H:i:s')
                    );

                    $inv_history_detail = $this->inventory_history_detail_m->save($data_inventory_history_detail);

                    $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                }

                if($x != 1 && $sisa > 0 && $sisa >= $row_inv['jumlah']){


                    $sisa = $sisa - $row_inv['jumlah'];
                    $sisa_inv = 0;

                    $data_inventory_history_detail = array(
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $row_inv['gudang_id'],
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

                    $sisa_inv = $row_inv['jumlah'] - $sisa;

                    $data_inventory_history_detail = array(
                        'inventory_history_id' => $inventory_history_id,
                        'gudang_id'            => $row_inv['gudang_id'],
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
                    $update_inventory = $this->inventory_m->update_by($this->session->userdata('user_id'),array('jumlah' => $sisa_inv),array('inventory_id' => $row_inv['inventory_id']));
                }

                $x++;        
            }
                

            $data_permintaan = array(
                'perawat_penerima' => $array_input['perawat_penerima'],
                'dialyzer_id'      => $array_input['dialyzer_id'],
                'bn_sn_lot'        => $bn_ed[0],
                'expired_date'     => $expire_date,
                'status'           => 2,
                'modified_by'      => $this->session->userdata('user_id'),
                'modified_date'    => date('Y-m-d H:i:s'),
            );

            $edit_permintaan = $this->permintaan_dialyzer_baru_m->edit_data($data_permintaan,$permintaan_id);

            $path_model = 'apotik/permintaan_dialyzer_baru_m';
            $edit_permintaan_api = insert_data_api_id($data_permintaan,config_item('ip_real'),$path_model,$permintaan_id);
            $edit_permintaan_api = str_replace('"', '', $edit_permintaan_api);

            if ($inventory_history_id) 
            {   
                $response->success = true;
                $response->msg = translate("Data permintaan dialyzer berhasil diproses", $this->session->userdata("language"));
            }

            die(json_encode($response));

        }   
    }


    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $resep_racik_obat_id = $this->permintaan_dialyzer_baru_m->edit_data($data, $id);

        // $update_status = $this->tindakan_resep_obat_manual_m->update_status($id);

        // $max_id = $this->kotak_sampah_m->max();
        // // die_dump($max_id);
        
        // if ($max_id->kotak_sampah_id==null){
        //     $trash_id = 1;
        // } else {
        //     $trash_id = $max_id->kotak_sampah_id+1;
        // }

        // // die_dump($trash_id);

        // $data_trash = array(
        //     'kotak_sampah_id' => $trash_id,
        //     'tipe'            => 5,
        //     'data_id'         => $id,
        //     'created_by'      => $this->session->userdata('user_id'),
        //     'created_date'    => date('Y-m-d H:i:s')
        // );

        // $trash = $this->kotak_sampah_m->simpan($data_trash);
        // die_dump($this->db->last_query());

        if ($resep_racik_obat_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Permintaan dialyzer baru telah ditolak", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("apotik/permintaan_dialyzer_baru");
    }

    public function proses($id)
    {
        $permintaan = $this->permintaan_dialyzer_baru_m->get_by(array('id' => $id), true);
        $pasien = $this->pasien_m->get_by(array('id' => $permintaan->pasien_id), true);
        $pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $permintaan->pasien_id, 'is_primary' => 1, 'is_active' => 1), true);
        $cabang = $this->cabang_m->get_by(array('id' => $permintaan->cabang_id), true);

        $path_model = 'klinik_hd/tindakan_hd_m';
        $wheres = array(
            'id' => $permintaan->tindakan_id
        );
        $data_tindakan = get_data_api(config_item('ip_real'),$path_model,$wheres);
        $data_tindakan = object_to_array(json_decode($data_tindakan));

        $path_model = 'klinik_hd/bed_m';
        $wheres_bed = array(
            'id' => $data_tindakan[0]['bed_id']
        );
        $data_bed = get_data_api(config_item('ip_real'),$path_model,$wheres_bed);
        $data_bed = object_to_array(json_decode($data_bed));

        $data = array(
            'id'            => $id,
            'permintaan'    => object_to_array($permintaan),
            'pasien'        => object_to_array($pasien),
            'pasien_alamat' => object_to_array($pasien_alamat),
            'tindakan'      => $data_tindakan,
            'bed'           => $data_bed
        );
        $this->load->view('apotik/permintaan_dialyzer_baru/modal_proses',$data);
    }

    public function get_dialyzer()
    {
        if($this->input->is_ajax_request()){
            $dialyzer_id = $this->input->post('dialyzer_id');
            $gudang_id = $this->input->post('gudang_id');

            $response = new stdClass;

            $data_inventory = $this->inventory_m->get_data($dialyzer_id, $gudang_id)->result_array();

            if(count($data_inventory != 0)){
                $response->success = true;
                $response->inventory = $data_inventory;
            }else{
                $response->success = false;
            }

            die(json_encode($response));
        }
    }
}

/* End of file resep_obat.php */
/* Location: ./application/controllers/resep_obat/resep_obat.php */