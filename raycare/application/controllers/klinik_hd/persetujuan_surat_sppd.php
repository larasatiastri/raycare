<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_surat_sppd extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '3e97fad631e66cc9bd4a53dcb515bbd4';                  // untuk check bit_access

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

        $this->load->model('klinik_hd/surat_dokter_sppd_m');
        $this->load->model('klinik_hd/surat_dokter_sppd_foto_m');
        $this->load->model('klinik_hd/tindakan_hd_m');

        $this->load->model('master/pasien_m');
        $this->load->model('master/pasien_alamat_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/klinik_hd/persetujuan_surat_sppd/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Persetujuan Surat Pengantar HD 3x', $this->session->userdata('language')), 
            'header'         => translate('persetujuan Surat Pengantar HD 3x', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/persetujuan_surat_sppd/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function history()
    {
        $assets = array();
        $config = 'assets/klinik_hd/persetujuan_surat_sppd/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('History Persetujuan Surat Pengantar HD 3x', $this->session->userdata('language')), 
            'header'         => translate('History Persetujuan Surat Pengantar HD 3x', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/persetujuan_surat_sppd/history',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add($id)
    {
        $assets = array();
        $assets_config = 'assets/klinik_hd/persetujuan_surat_sppd/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $form_data = $this->surat_dokter_sppd_m->get_by(array('id' => $id), true);
        $form_data_gambar = $this->surat_dokter_sppd_foto_m->get_by(array('surat_dokter_sppd_id' => $id));

        $data = array(
            'title'          => config_item('site_name').' | '.translate("Tambah Surat Pengantar HD 3x", $this->session->userdata("language")), 
            'header'         => translate("Tambah Surat Pengantar HD 3x", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/persetujuan_surat_sppd/add',
            'form_data'      => object_to_array($form_data),
            'form_data_gambar'      => object_to_array($form_data_gambar),
            'pk_value'       => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $form_data = $this->surat_dokter_sppd_m->get_data($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' | '.translate("View Surat Pengantar HD 3x", $this->session->userdata("language")), 
            'header'         => translate("View Surat Pengantar HD 3x", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'klinik_hd/persetujuan_surat_sppd/view',
            'form_data'      => object_to_array($form_data),
            'pk_value'       => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($status)
    {        
        $result = $this->surat_dokter_sppd_m->get_datatable_setuju($status);

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
            $status = '';
            
            $action .='<a title="'.translate('Proses', $this->session->userdata('language')).'" href="'.base_url().'klinik_hd/persetujuan_surat_sppd/add/'.$row['id'].'"  class="btn btn-primary"><i class="fa fa-check"></i></a>';
           
            switch ($row['status']) {
                case '1':
                    $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
                    break;
                case '2':
                    $status = '<div class="text-center"><span class="label label-md label-info">Disetujui</span></div>';
                    break;
                default:
                    break;
            }


            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                $row['nama'],
                $row['dokter'],
                $row['diagnosa1'].', '.$row['diagnosa2'],
                $row['alasan'],
                $status,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $command = $this->input->post('command');
        
        $id = $this->input->post('id');

        if ($command === 'add')
        {  
            $data = array(
                "status"        => 2,
                "disetujui_oleh"    => $this->session->userdata('user_id'),
                "tanggal_setujui"    => date('Y-m-d'),
            );
            // die_dump($data);
            // die_dump($this->input->post());
            $surat_id = $this->surat_dokter_sppd_m->save($data,$id);
            // die_dump($this->db->last_query());
            if ($surat_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Surat keterangan Sp.Pd berhasil disetujui.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }

        redirect("klinik_hd/persetujuan_surat_sppd");
    }

}

/* End of file surat_dokter_sppd.php */
/* Location: ./application/controllers/klinik_hd/surat_dokter_sppd.php */