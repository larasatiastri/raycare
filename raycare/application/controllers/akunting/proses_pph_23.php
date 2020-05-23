<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses_pph_23 extends MY_Controller {

    // protected $menu_id = '7935f149dd9c1c8d88ce7296c2fdcd4b';                  // untuk check bit_access
    protected $menu_id = 'd825e840d3df7474ac0e708f76b3fe66';                  // untuk check bit_access
    
    private $menus     = array();
    
    private $menu_tree = array();       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level  = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('pembelian/pembelian_pph_23_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_detail_m'); 
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/akunting/proses_pph_23/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Proses PPH 23', $this->session->userdata('language')), 
            'header'         => translate('Proses PPH 23', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/proses_pph_23/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function history()
    {
        $assets = array();
        $config = 'assets/akunting/proses_pph_23/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Proses PPH 23', $this->session->userdata('language')), 
            'header'         => translate('History Proses PPH 23', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/proses_pph_23/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($tipe)
    {        

        $result = $this->pembelian_pph_23_m->get_datatable($tipe);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die(dump($records));
        $i=1;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {   
            $status = '';
            $action = '<div id="action_edit_'.$i.'"><a title="'.translate('Edit', $this->session->userdata('language')).'"  class="btn blue-chambray edit"  data-index="'.$i.'" id="btn_edit_'.$i.'"><i class="fa fa-edit"></i></a></div>
            <div id="action_save_cancel_'.$i.'" class="hidden"><a title="'.translate('Simpan', $this->session->userdata('language')).'"  class="btn btn-success simpan" id="btn_simpan_'.$i.'" data-index="'.$i.'"data-msg="Anda yakin akan menyimpan kode pajak ini?"><i class="fa fa-save"></i></a><a title="'.translate('Batal', $this->session->userdata('language')).'"  class="btn btn-danger batal" id="btn_batal_'.$i.'" data-index="'.$i.'"><i class="fa fa-times"></i></a></div>';


            if($row['status'] == 1){
                 $status = '<div class="text-left"><span class="label label-md label-danger">Menunggu Kode</span></div>';
            }if($row['status'] == 2){
                 $status = '<div class="text-left"><span class="label label-md label-success">Menunggu Proses Keuangan</span></div>';
            }if($row['status'] == 3){
                 $status = '<div class="text-left"><span class="label label-md label-info">Selesai</span></div>';
            }


            
            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-left">'.$row['nomor_pph'].'</div>',
                '<div class="text-left"><a target="_blank" href="'.base_url().'pembelian/history/view/'.$row['pembelian_id'].'">'.$row['nomor_pembelian'].'</a><input type="text" name="id_pph_'.$i.'" id="id_pph_'.$i.'" value="'.$row['id'].'" class="form-control hidden"><input type="text" name="id_po_'.$i.'" id="id_po_'.$i.'" class="form-control hidden" value="'.$row['pembelian_id'].'"></div>',
                '<div class="text-right">'.formatrupiah($row['pph_23_nominal']).'</div>',
                '<div class="text-center inline-button-table">'.$status.'</div>',
                '<div class="text-center inline-button-table"><label id="label_kode_'.$i.'">'.$row['kode_pajak'].'</label><input type="text" name="kode_'.$i.'" id="kode_'.$i.'" class="form-control hidden"></div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

     /**
     * [list description]
     * @return [type] [description]
     */
    public function listing_history()
    {        
        $cabang_id=$this->session->userdata('cabang_id');

        $result = $this->pendaftaran_tindakan_m->get_datatable_antri_history($cabang_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        $i=1;

        $action = '';
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {   

            $action = '';
            $shift = '';

            $user_level_id = $this->session->userdata('level_id');
            
            $url = array();
            if ($row['url_photo'] != '') 
            {
                if (file_exists(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'])) 
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').$row['no_member'].'/foto/small/'.$row['url_photo'].'">';
                }
                else
                {
                    $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
                }
            } else {

                $img_url = '<img class="img-circle" style="margin-right:4px; width:20px; height:20px;" src="'.base_url().config_item('site_img_pasien').'global/global_small.png">';
            }
            if($row['shift'] == 1){
                $shift = '<i class="fa fa-cloud font-blue-madison" style="font-size:16px;"></i>';
            }if($row['shift'] == 2){
                $shift = '<i class="fa fa-certificate font-yellow-lemon" style="font-size:16px;"></i>';
            }if($row['shift'] == 3){
                $shift = '<i class="fa fa-star font-blue-ebonyclay" style="font-size:16px;"></i>';
            }

       
            $output['data'][] = array(
                '<div class="text-center">'.$i.'</div>',
                '<div class="text-left">'.$row['no_member'].'</div>',
                '<div class="text-left">'.$img_url.$shift.' '.$row['nama'].'</div>',
                '<div class="text-left">'.ucwords(strtolower($row['alamat'])).', '.ucwords(strtolower($row['kelurahan'])).', '.ucwords(strtolower($row['kecamatan'])).', '.ucwords(strtolower($row['kota'])).'</div>',
                '<div class="text-left">'.$row['berat_badan'].' Kg</div>',
                '<div class="text-left">'.str_replace('_', ' / ', $row['tekanan_darah']).'</div>',
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        if($this->input->is_ajax_request()){

            $response = new stdClass;
            $response->success = false;
            $response->msg = translate('Kode Gagal Diinput', $this->session->userdata('language'));

            $array_input = $this->input->post();
         //   die(dump($array_input));
       
            $data_pph_23 = array(
                'status'        => 2,
                'kode_pajak'    => $array_input['kode'],
                'processed_by'  => $this->session->userdata('user_id'),
                'modified_by'   => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            $edit_po_pph = $this->pembelian_pph_23_m->edit_data($data_pph_23, $array_input['id']);


            $data_ps = array(
                'status'        => 2,
                'user_level_id'    => 5,
                'divisi'  => 7,
                'modified_by'   => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d H:i:s')
            );

            $wheres_ps = array(
                'transaksi_id'   => $array_input['id'],
                'tipe_transaksi' => 7,
            );

            $edit_po_pph = $this->pembayaran_status_m->update_by($this->session->userdata('user_id'),$data_ps, $wheres_ps);


            //if($edit_pendaftaran_id){
                $response->success = true;
                $response->msg = translate('Kode Berhasil Diinput', $this->session->userdata('language'));
            //}

            die(json_encode($response));

        }
    }

}

/* End of file antrian_tensi_bb.php */
/* Location: ./application/controllers/akunting/proses_pph_23.php */