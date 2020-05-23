<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_reuse extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '7cfe4163368b6d57554526fe2d47f0d0';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/item_tersimpan_m');
        $this->load->model('master/pasien_m');       
        $this->load->model('klinik_hd/tindakan_hd_history_m');
        $this->load->model('klinik_hd/tindakan_hd_item_history_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/transaksi_reuse/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reuse Dialyzer', $this->session->userdata('language')), 
            'header'         => translate('Reuse Dialyzer', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/transaksi_reuse/index',
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
        $result = $this->item_tersimpan_m->get_datatable_simpan_item_pasien();

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
            $status = '';
            
            $action = '<div id="action_edit_'.$i.'"><a title="'.translate('Edit', $this->session->userdata('language')).'"  class="btn blue-chambray edit"  data-index="'.$i.'" id="btn_edit_'.$i.'"><i class="fa fa-edit"></i></a></div>
            <div id="action_save_cancel_'.$i.'" class="hidden"><a title="'.translate('Simpan', $this->session->userdata('language')).'"  class="btn btn-success simpan" id="btn_simpan_'.$i.'" data-index="'.$i.'"data-msg="Anda yakin akan menyimpan data reuse ini?"><i class="fa fa-save"></i></a><a title="'.translate('Batal', $this->session->userdata('language')).'"  class="btn btn-danger batal" id="btn_batal_'.$i.'" data-index="'.$i.'"><i class="fa fa-times"></i></a></div>';
            
            switch ($row['status_reuse']) {
                case 1:
                    $status = '<div class="text-left"><span class="label label-warning">Menunggu Reuse</span></div>';
                    break; 

                case 2:
                    $status = '<div class="text-left"><span class="label label-info">Proses Reuse</span></div>';
                    break;

                case 3:
                    $status = '<div class="text-left"><span class="label label-success">Siap Pakai</span></div>';
                    break;

                case 4:
                    $status = '<div class="text-left"><span class="label label-danger">Rusak</span></div>';
                    break;
                
                default:
                    break;
            }

            $status_option = array(
                '1' => 'Menunggu Reuse',
                '2' => 'Proses Reuse',
                '3' => 'Siap Pakai',
                '4' => 'Rusak Total',
                '5' => 'Rusak',
            );

            $status_select = '<div id="select_status_'.$i.'" class="hidden">'.form_dropdown('select_status_'.$i,$status_option,'1','id="status_'.$i.'" class="form-control"').'</div>';
            $index_select = '<div id="index_ubah_'.$i.'" class="hidden"><input type="number" min="1" id="index_'.$i.'" name="index_ubah_'.$i.'" value="'.$row['idx'].'"></div>'; 
            $volume_select = '<div id="volume_ubah_'.$i.'" class="hidden"><input type="number" min="1" max="100" id="volume_'.$i.'" name="volume_ubah_'.$i.'" value="'.$row['volume'].'"></div>';

            $output['data'][] = array(
                $row['simpan_item_id'],
                $row['nama_pasien'].'<input type="hidden" id="simpan_item_id_'.$i.'" value="'.$row['simpan_item_id'].'">',
                $row['nama_item'],
                '<div id="index_tampil_'.$i.'">'.'R['.$row['idx'].']'.'</div>'.$index_select,
                $row['bn_sn_lot'],
                '<div id="volume_tampil_'.$i.'">'.$row['volume'].'</div>'.$volume_select,
                date('d M Y', strtotime($row['expire_date'])),
                '<div id="status_tampil_'.$i.'">'.$status.'</div>'.$status_select,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {

        $response = new stdClass;
        $response->success = false;
        $response->msg = translate('Proses simpan reuse dialyzer gagal', $this->session->userdata('language'));

        $array_input = $this->input->post();

        $user_id = $this->session->userdata('user_id');

        if($array_input['status'] != 4){
            $data_reuse = array(
                '`index`'       => $array_input['idx'],
                'volume'        => $array_input['volume'],
                'status_reuse'        => $array_input['status'],
                'petugas_reuse' => $user_id
            );

            $wheres['simpan_item_id'] = $array_input['id'];

            $edit_reuse = $this->item_tersimpan_m->update_by($user_id,$data_reuse,$wheres);
        } elseif($array_input['status'] == 4){
            $wheres['simpan_item_id'] = $array_input['id'];

            $hapus_reuse = $this->item_tersimpan_m->delete_by($wheres);
        }

        $response->success = true;
        $response->msg = translate('Proses simpan data reuse berhasil', $this->session->userdata('language'));

        die(json_encode($response));
    }

}

/* End of file surat_dokter_sppd.php */
/* Location: ./application/controllers/klinik_hd/transaksi_reuse.php */