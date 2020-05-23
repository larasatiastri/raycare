<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_faktur_pajak extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 'ae81a318b339a2527a249357f9bd6605';                  // untuk check bit_access

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

        $this->load->model('akunting/daftar_faktur_pajak/pembelian_invoice_m');      
        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_m');
        $this->load->model('keuangan/tanda_terima_faktur/tanda_terima_faktur_detail_m');  
        $this->load->model('keuangan/pembayaran_status/pembayaran_status_m');

    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/akunting/daftar_faktur_pajak/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Daftar Dokumen PO', $this->session->userdata('language')), 
            'header'         => translate('Daftar Dokumen PO', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/daftar_faktur_pajak/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/akunting/daftar_faktur_pajak/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Dokumen PO', $this->session->userdata('language')), 
            'header'         => translate('History Dokumen PO', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/daftar_faktur_pajak/history',
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
        $result = $this->pembelian_invoice_m->get_datatable($status);
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

            $ttf_detail = $this->tanda_terima_faktur_detail_m->get_by(array('url_berkas' => $row['url'], 'url_faktur_pajak' => $row['url_faktur_pajak']), true);
            if(count($ttf_detail) != 0){
                $pemb_status = $this->pembayaran_status_m->get_by(array('transaksi_id' => $ttf_detail->tanda_terima_faktur_id), true);
            }else{
                $pemb_status = $this->pembayaran_status_m->get_by(array('transaksi_id' => $row['id_pembelian']), true);
            }
            $action = '<a title="'.translate('Terima', $this->session->userdata('language')).'" data-id="'.$row['id'].'" data-confirm="'.translate('Anda yakin akan menerima Dokumen PO ini?', $this->session->userdata('language')).'" class="btn btn-primary receive"><i class="fa fa-check"></i></a>';

            $fp = 'Tidak Tersedia';
            if($row['url_faktur_pajak'] != NULL && $row['url_faktur_pajak'] != ''){
                $fp = '<a class="fancybox-button" title="'.$row['url_faktur_pajak'].'" href="'.config_item('folder_cloud').'keuangan/pembayaran_transaksi/images/'.$pemb_status->id.'/'.$row['url_faktur_pajak'].'" data-rel="fancybox-button"><img src="'.config_item('folder_cloud').'keuangan/pembayaran_transaksi/images/'.$pemb_status->id.'/'.$row['url_faktur_pajak'].'" alt="Smiley face" class="img-responsive"></a>';
            }



            $output['aaData'][] = array(
               
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].' ['.$row['kode'].']</div>',
                '<div class="text-left"><a href="'.base_url().'pembelian/history/view/'.$row['id_pembelian'].'" target="_blank">'.$row['no_pembelian'].'</a></div>',
                '<a class="fancybox-button" title="'.$row['url'].'" href="'.config_item('folder_cloud').'keuangan/pembayaran_transaksi/images/'.$pemb_status->id.'/'.$row['url'].'" data-rel="fancybox-button"><img src="'.config_item('folder_cloud').'keuangan/pembayaran_transaksi/images/'.$pemb_status->id.'/'.$row['url'].'" alt="Smiley face" class="img-responsive"></a>',
                '<div class="text-left">'.$row['no_invoice'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tgl_invoice'])).'</div>',
                '<div class="text-right">'.formatrupiah($row['total_invoice']).'</div>',
                $fp,
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
        $user_id = $this->pembelian_invoice_m->edit_data($data, $id);

        $flashdata = array(
            "type"     => "success",
            "msg"      => translate("Dokumen PO berhasil dipilih.", $this->session->userdata("language")),
            "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
            );
        $this->session->set_flashdata($flashdata);
        redirect('akunting/daftar_faktur_pajak');
    }
}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */