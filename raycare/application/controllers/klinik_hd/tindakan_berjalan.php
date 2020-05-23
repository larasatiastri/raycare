<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_berjalan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd1c0d969fcf29102e6ac02fae09c7978';                  // untuk check bit_access

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

      
        $this->load->model('apotik/item_m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/cabang_alamat_m');
        $this->load->model('master/cabang_telepon_m');
        $this->load->model('master/cabang_sosmed_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/poliklinik_tindakan_m');
        $this->load->model('master/poliklinik_harga_tindakan_m');
        $this->load->model('master/transaksi_dokter_m');
        $this->load->model('master/transaksi_dokter2_m');
        $this->load->model('master/transaksi_dokter3_m');
        $this->load->model('master/transaksi_dokter4_m');
        $this->load->model('master/user_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/dokumen_m');
        $this->load->model('master/pasien_dokumen_m');
        $this->load->model('master/pasien_dokumen_detail_m');
        $this->load->model('master/pasien_dokumen_detail_tipe_m');
        $this->load->model('master/penjamin_dokumen_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('klinik_hd/obat_m');
        $this->load->model('klinik_hd/resep_racikan_obat_m');
        $this->load->model('klinik_hd/klaim_m');
        $this->load->model('klinik_hd/rujukan_m');
        $this->load->model('klinik_hd/paket_m');
        $this->load->model('klinik_hd/sejarah_item_m');
        $this->load->model('klinik_hd/observasi_m');
        $this->load->model('klinik_hd/item_tersimpan_m');
        $this->load->model('klinik_hd/item_digunakan_m');
        $this->load->model('klinik_hd/tagihan_paket_m');
        $this->load->model('klinik_hd/view_tagihan_paket_m');
        $this->load->model('klinik_hd/tindakan_hd_m');
        $this->load->model('klinik_hd/tindakan_hd_invoice_m');
        $this->load->model('klinik_hd/pasien_klaim_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual_m');
        $this->load->model('klinik_hd/tindakan_resep_obat_manual2_m');
        $this->load->model('klinik_hd/bed_m');
        $this->load->model('klinik_hd/tindakan_hd_penaksiran_m');
        $this->load->model('klinik_hd/tindakan_hd_diagnosa_m');
        $this->load->model('klinik_hd/pendaftaran_tindakan_m');
        $this->load->model('klinik_hd/transaksi_diproses_m');

        $this->load->model('klinik_hd/resep_obat_racikan_m');
        $this->load->model('klinik_hd/resep_obat_racikan_detail_m');
        $this->load->model('klinik_hd/resep_obat_racikan_detail_manual_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('master/info_umum_m');
        $this->load->model('master/info_alamat_m');
        $this->load->model('master/pasien_telepon_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/region_m');
        $this->load->model('master/pasien_penyakit_m');
        $this->load->model('klinik_hd/pasien_problem_m');
        $this->load->model('klinik_hd/pasien_problem2_m');
        $this->load->model('klinik_hd/pasien_komplikasi_m');
        $this->load->model('klinik_hd/tindakan_hd_paket_m');
        $this->load->model('klinik_hd/tindakan_hd_visit_m');

        $this->load->model('klinik_hd/tindakan_hd_tindakan_m');
        $this->load->model('klinik_hd/paket_item_m');
        $this->load->model('klinik_hd/paket_tindakan_m');
        $this->load->model('master/paket_batch2_m');

        // ITEM
        $this->load->model('klinik_hd/inventory_klinik_m');
        $this->load->model('klinik_hd/tindakan_hd_item_m');
        $this->load->model('apotik/gudang_m');
        $this->load->model('master/item/item_kategori_m');
        $this->load->model('apotik/pemisahan_item/inventory_identitas_m');

        // INVENTORY/SIMPAN_ITEM
        $this->load->model('klinik_hd/simpan_item_identitas_m');
        $this->load->model('klinik_hd/simpan_item_identitas_detail_m');
        $this->load->model('klinik_hd/simpan_item_history_m');
        $this->load->model('klinik_hd/simpan_item_history_detail_m');
        $this->load->model('klinik_hd/simpan_item_history_identitas_m');
        $this->load->model('klinik_hd/tindakan_hd_item_identitas_m');
        $this->load->model('klinik_hd/simpan_item_history_identitas_detail_m');
        $this->load->model('klinik_hd/tindakan_hd_item_identitas_detail_m');


        $this->load->model('master/item/item_harga_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('apotik/inventory_m');
        // $this->load->model('apotik/inventory_identitas_m');
        $this->load->model('apotik/inventory_identitas_detail_m');
        $this->load->model('apotik/inventory_history_m');
        $this->load->model('apotik/inventory_history_detail_m');
        $this->load->model('apotik/inventory_history_identitas_m');
        $this->load->model('apotik/inventory_history_identitas_detail_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/tindakan_berjalan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
       
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Tindakan Berjalan', $this->session->userdata('language')), 
            'header'         => translate('Tindakan Berjalan', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/tindakan_berjalan/index',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_transaksi_diproses()
    {        
        
        $id=array('2','3');
        
        $result = $this->transaksi_diproses_m->get_datatable($id);

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
            $status='';
            if($row['status']=='2'){
                $status='<span class="label label-primary">Sedang Ditindak</span>';
                 $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  href="'.base_url().'klinik_hd/tindakan_berjalan/lihat_detail/'.$row['id'].'/'.$row['pasienid'].'" name="del[]" data-action="view" data-id="'.$row['id'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
                         
            }if($row['status']=='3'){
                $status='<span class="label label-danger">Selesai Ditindak</span>';
                 $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  href="'.base_url().'klinik_hd/tindakan_berjalan/lihat_detail/'.$row['id'].'/'.$row['pasienid'].'" name="del[]" data-action="view" data-id="'.$row['id'].'" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
                         
            }

            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:10px; width:28px; height:28px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }
           
       
            $output['data'][] = array(
              '<div class="text-left">'.$img_url.$row['nama'].'</div>',
              '<div class="text-left">'.$row['no_transaksi'].'</div>',
              '<div class="text-left">'.$row['alamat'].', '.$row['kelurahan'].', '.$row['kecamatan'].', '.$row['kota'].'</div>',
              '<div class="text-left">'.$row['nama_dokter'].'</div>',
              '<div class="text-center">'.$status.'</div>',
              '<div class="text-center inline-button-table">'.$action.'</div>' ,
               
                
            );
            $i++;
        }

        echo json_encode($output);
    }


    public function lihat_detail($id=null,$pasien_id=null)
    {
        $assets = array();
        $config = 'assets/klinik_hd/tindakan_berjalan/lihat_detail';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $form_data_kiri=$this->tindakan_hd_m->detail_histori_kiri($id,$pasien_id)->result_array();
        // die(dump($this->db->last_query()));
        $form_data_kanan=$this->tindakan_hd_m->detail_histori_kanan($id)->result_array();

        $assesment = $this->tindakan_hd_penaksiran_m->get_by(array('tindakan_hd_id' => $id), true);

        $form_pasien = $this->pasien_m->get($pasien_id);

        $data = array(
            'title'                 =>  config_item('site_name').' &gt;'.translate('Detail Tindakan', $this->session->userdata('language')), 
            'header'                =>  translate('Detail Tindakan', $this->session->userdata('language')), 
            'header_info'           =>  config_item('site_name'), 
            'breadcrumb'            =>  true,
            'menus'                 =>  $this->menus,
            'menu_tree'             =>  $this->menu_tree,
            'css_files'             =>  $assets['css'],
            'js_files'              =>  $assets['js'],
            'content_view'          =>  'klinik_hd/tindakan_berjalan/lihat_detail',
            'form_data_kiri'        =>  $form_data_kiri,
            'form_data_kanan'       =>  $form_data_kanan,
            'form_pasien'           =>  object_to_array($form_pasien),
            'assesment'           =>  object_to_array($assesment),
            'pasien_id'             =>  $pasien_id,
            'id'                    =>  $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing_item_telah_digunakan($tindakan_id)
    {
        $result = $this->tindakan_hd_item_m->get_datatable($tindakan_id);
        // die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        foreach($records->result_array() as $row)
        {       
            
            $output['data'][] = array(
                 
                '<div class="text-center">'.date('H:i', strtotime($row['waktu'])).'</div>',
                '<div class="text-center">'.$row['item_nama'].'</div>',
                '<div class="text-center">'.$row['jumlah'].'</div>',
                '<div class="text-center">'.$row['user_nama'].'</div>',
                 
            );
        }

        echo json_encode($output);
    }

   
}

