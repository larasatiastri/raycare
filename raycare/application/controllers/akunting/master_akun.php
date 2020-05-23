<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_akun extends MY_Controller {

    // protected $menu_id = '7935f149dd9c1c8d88ce7296c2fdcd4b';                  // untuk check bit_access
    protected $menu_id = '9b1a60e15bd3390393efb7a94835b202';                  // untuk check bit_access
    
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

        $this->load->model('akunting/akun_m');
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/akunting/master_akun/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Master Akun', $this->session->userdata('language')), 
            'header'         => translate('Master Akun', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/master_akun/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    } 

    public function add()
    {
        $assets = array();
        $config = 'assets/akunting/master_akun/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tambah Akun', $this->session->userdata('language')), 
            'header'         => translate('Tambah Akun', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/master_akun/add'
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $assets = array();
        $config = 'assets/akunting/master_akun/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);

        $form_data = $this->akun_m->get_by(array('id' => $id), true);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Edit Akun', $this->session->userdata('language')), 
            'header'         => translate('Edit Akun', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'akunting/master_akun/edit',
            'form_data'      => object_to_array($form_data)
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    /**
     * [list description]
     * @return [type] [description]
     */
    public function listing($akun_tipe,$is_suspended=NULL)
    {        

        $result = $this->akun_m->get_datatable($akun_tipe,$is_suspended);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        // die(dump($records));
        $i=1;

        foreach($records->result_array() as $row)
        {   
            $kategori = '';
            switch ($row['akun_tipe']) {
                case 1:
                    $kategori = 'Harta Lancar';
                    break;
                case 2:
                    $kategori = 'Harta Tidak Lancar';
                    break;
                case 3:
                    $kategori = 'Utang Lancar';
                    break;
                case 4:
                    $kategori = 'Utang Jangka Panjang';
                    break;
                case 5:
                    $kategori = 'Modal';
                    break;
                case 6:
                    $kategori = 'Laba';
                    break; 
                case 7:
                    $kategori = 'Pendapatan';
                    break;
                case 8:
                    $kategori = 'Harga Pokok Penjualan';
                    break;
                case 9:
                    $kategori = 'Beban Operasi';
                    break;
                case 10:
                    $kategori = 'Pendapatan Lain - lain';
                    break;
                case 11:
                    $kategori = 'Beban Lain - lain';
                    break;
                default:
                    break;
            }

            $status = ($row['is_suspended'] == 0)?'<div class="text-left"><span class="label label-info">Aktif</span></div>':'<div class="text-left"><span class="label label-danger">Non Aktif</span></div>';
            $bold = ($row['parent'] == '0')?'bold':'';

            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" class="btn blue-chambray edit" id="btn_edit_'.$i.'" href="'.base_url().'akunting/master_akun/edit/'.$row['id'].'"><i class="fa fa-edit"></i></a>';

            if($row['is_suspended'] == 0){
                $action .= '<a title="'.translate('Non Aktif', $this->session->userdata('language')).'" class="btn btn-danger" data-confirm="Anda yakin akan me-nonaktifikan akun ini?" data-id="'.$row['id'].'" name="suspend[]"><i class="fa fa-times"></i></a>';
            }if($row['is_suspended'] == 1){
                $action .= '<a title="'.translate('Aktifkan', $this->session->userdata('language')).'" class="btn btn-warning" data-confirm="Anda yakin akan mengaktifkan akun ini?" data-id="'.$row['id'].'" name="un_suspend[]"><i class="fa fa-reply"></i></a>';
            }


            $output['data'][] = array(
                '<div class="text-left '.$bold.'">'.$kategori.'</div>',
                '<div class="text-left '.$bold.'">'.$row['no_akun'].'</div>',
                '<div class="text-left '.$bold.'">'.$row['nama'].'</div>',
                '<div class="text-center inline-button-table">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

   

    public function save()
    {

        $cabang_klinik = $this->cabang_m->get_by(array('tipe' => 1, 'is_active' => 1));
        $path_model = 'akunting/akun_m';
       
        $array_input = $this->input->post();

        if($array_input['command'] == 'add'){
            $last_id       = $this->akun_m->get_max_id()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'COA-'.date('m').'-'.date('Y').'-%04d';
            $id_akun         = sprintf($format_id, $last_id, 4);

            $akun_parent = $this->akun_m->get_by(array('parent' => $array_input['parent']), true);

            $data_akun = array(
                'id'            => $id_akun,
                'parent'  => $array_input['parent'],
                'no_akun'  => $array_input['no_akun'],
                'nama'  => $array_input['nama'],
                'akun_tipe'  => $array_input['akun_tipe'],
                'akun_kategori_id'  => $akun_parent->akun_kategori_id,
                'is_selectable'     => 1,
                'is_suspended'     => 0,
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'  => date('Y-m-d H:i:s'),
            );

            // $neraca = $this->akun_m->add_data($data_akun);

            foreach($cabang_klinik as $klinik){
                $insert_akun = insert_data_api_id($data_akun,$klinik->url,$path_model);
            }

          
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Akun berhasil ditambahkan.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);

        }if($array_input['command'] == 'edit'){
            
            $id_akun = $array_input['id'];

            $data_akun = array(
                'no_akun'  => $array_input['no_akun'],
                'nama'  => $array_input['nama'],
                'modified_by'    => $this->session->userdata('user_id'),
                'modified_date'  => date('Y-m-d H:i:s'),
            );

            // $neraca = $this->akun_m->edit_data($data_akun, $id_akun);

            foreach($cabang_klinik as $klinik){
                $edit_akun = insert_data_api_id($data_akun,$klinik->url,$path_model,$id_akun);
            }
            
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Akun berhasil diubah.", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }

        redirect('akunting/master_akun');
            
    }

    public function suspend($id)
    {
           
        $cabang_klinik = $this->cabang_m->get_by(array('tipe' => 1, 'is_active' => 1));
        $path_model = 'akunting/akun_m';

        $data = array(
            'is_suspended'    => 1,
            'modified_by'       => $this->session->userdata('user_id'),
            'modified_date'     => date('Y-m-d')
        );
        // save data
        // $edit_akun = $this->akun_m->edit_data($data, $id);
        foreach($cabang_klinik as $klinik){
            $edit_akun = insert_data_api_id($data,$klinik->url,$path_model,$id);
        }

        redirect('akunting/master_akun');
    } 

    public function un_suspend($id)
    {
        $cabang_klinik = $this->cabang_m->get_by(array('tipe' => 1, 'is_active' => 1));
        $path_model = 'akunting/akun_m';
           
        $data = array(
            'is_suspended'    => 0,
            'modified_by'       => $this->session->userdata('user_id'),
            'modified_date'     => date('Y-m-d')
        );
        // save data
        // $edit_akun = $this->akun_m->edit_data($data, $id);
        foreach($cabang_klinik as $klinik){
            $edit_akun = insert_data_api_id($data,$klinik->url,$path_model,$id);
        }

        redirect('akunting/master_akun');
    }

}

/* End of file antrian_tensi_bb.php */
/* Location: ./application/controllers/akunting/master_akun.php */