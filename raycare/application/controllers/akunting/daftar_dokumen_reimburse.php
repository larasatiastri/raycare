<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_dokumen_reimburse extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'd2fc9c6ebc2afec069f4271de44447f1';                  // untuk check bit_access

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

        $this->load->model('akunting/daftar_dokumen_reimburse/permintaan_biaya_bon_m');      
        $this->load->model('keuangan/proses_permintaan_biaya/permintaan_biaya_m');
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/akunting/daftar_dokumen_reimburse/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Daftar Dokumen Reimburse', $this->session->userdata('language')), 
            'header'         => translate('Daftar Dokumen Reimburse', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/daftar_dokumen_reimburse/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/akunting/daftar_dokumen_reimburse/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Dokumen Reimburse', $this->session->userdata('language')), 
            'header'         => translate('History Dokumen Reimburse', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/daftar_dokumen_reimburse/history',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($status=NULL)
    {        
        $result = $this->permintaan_biaya_bon_m->get_datatable($status);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die_dump($records);
        
        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            $style = '';

            
            $action = '<a title="'.translate('Terima', $this->session->userdata('language')).'" data-id="'.$row['id_permintaan_detail'].'" data-confirm="'.translate('Anda yakin akan menerima Dokumen Reimburse ini?', $this->session->userdata('language')).'" class="btn btn-primary receive"><i class="fa fa-check"></i></a>';


            $output['aaData'][] = array(
               
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama_user'].'</div>',
                '<div class="text-left">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.$row['nomor_permintaan'].'</div>',
                '<div class="text-left">'.$row['no_bon'].'</div>',
                '<div class="text-left">'.date('d M Y', strtotime($row['tgl_bon'])).'</div>',
                '<div class="text-right">'.formatrupiah($row['total_bon']).'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<a class="fancybox-button" title="'.$row['url'].'" href="'.config_item('folder_cloud').'keuangan/permintaan_biaya/images/'.$row['id_permintaan'].'/'.$row['url'].'" data-rel="fancybox-button"><img src="'.config_item('folder_cloud').'keuangan/permintaan_biaya/images/'.$row['id_permintaan'].'/'.$row['url'].'" alt="Smiley face" class="img-responsive"></a>',
                '<div class="text-left inline-button-table">'.$action.'</div>',
                
            );
        
         $i++;
        }                               //end of foreach($records->result_array() as $row)

        echo json_encode($output);
    }

    public function update($id){
        $data = array(
            'status'    => 1
        );
        // save data
        $user_id = $this->permintaan_biaya_bon_m->edit_data($data, $id);

        $flashdata = array(
            "type"     => "success",
            "msg"      => translate("Dokumen Reimburse berhasil dipilih.", $this->session->userdata("language")),
            "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
        redirect('akunting/daftar_dokumen_reimburse');
    }
}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */