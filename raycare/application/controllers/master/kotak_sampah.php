<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kotak_sampah extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 5;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(2, 5);       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level  = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('master/cabang__m');
        $this->load->model('master/cabang_m');
        $this->load->model('master/user_m');
        $this->load->model('master/user_level_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/poliklinik_tindakan_m');
        $this->load->model('others/kotak_sampah_m');
        $this->load->model('master/kategori_m');
        $this->load->model('master/item/item_sub_kategori_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/kotak_sampah/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Kotak Sampah', $this->session->userdata('language')), 
            'header'         => translate('Master Kotak Sampah', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kotak_sampah/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/kotak_sampah/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). translate("Tambah Cabang", $this->session->userdata("language")), 
            'header'         => translate("Tambah Cabang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kotak_sampah/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/kotak_sampah/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        $this->cabang__m->set_columns($this->cabang__m->fillable_edit());
       
        $form_data = $this->cabang__m->get($id);

        $data = array(
            'title'          => config_item('site_name'). translate("Edit Cabang", $this->session->userdata("language")), 
            'header'         => translate("Edit Cabang", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/kotak_sampah/edit',
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
    public function listing()
    {        
        $result = $this->kotak_sampah_m->get_datatable();

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
            
            // $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/Kotak_sampah/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
            // <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data cabang ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
            $action = '<a title="'.translate('Restore', $this->session->userdata('language')).'" data-confirm="'.translate('Are you sure you want to restore this customer?', $this->session->userdata('language')).'" name="restore[]" data-action="restore" data-id="'.$row['kotak_sampah_id'].'/'.$row['data_id'].'/'.$row['tipe'].'" class="btn btn-xs yellow"><i class="fa fa-undo"></i> </a>';
            
            $array_tipe = array(
                'Cabang', 'Poliklinik', 'Poliklinik Tindakan', 'User Level', 'User', 
                'Tindakan', 'Kategori', 'Sub Kategori', 'Kosong', 'Kosong', 
                'Kosong', 'Kosong', 'Kosong', 'Kosong', 'Kosong', 
                'Kosong', 'Kosong', 'Kosong', 'Kosong', 'Kosong', 
                'Kosong', 'Kosong', 'Kosong', 'Kosong', 'Kosong', 
            );

            $tipe = $array_tipe[$row['tipe']-1];
            $user = $this->session->userdata('nama_lengkap');

            $date = date_create($row['created_date']);
            $effective_date = date_format($date, 'd F Y H:i:s');

            $output['data'][] = array(
                '<div class="text-center">'.$effective_date.'</div>',
                $tipe,
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
        $command = $this->input->post('command');

        if ($command === 'add')
        {  
            $data = $this->cabang__m->array_from_post( $this->cabang__m->fillable_add());
            $data['is_active'] = 1;
            $cabang_id = $this->cabang__m->save($data);
            if ($cabang_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data cabang berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }
        elseif ($command === 'edit')
        {
            $id = $this->input->post('id');
           
            $data = $this->cabang__m->array_from_post( $this->cabang__m->fillable_edit());
            // save data
            $cabang_id = $this->cabang__m->save($data, $id);
            if ($cabang_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data cabang berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/cabang_");
    }

    public function restore($id, $data_id, $tipe)
    {
        // 'Cabang', 'Poliklinik', 'Poliklinik Tindakan', 'User Level', 'User',

        if ($tipe == 1) 
        {
            $data = array(
               'is_active' => 1
            );

            $cabang = $this->cabang__m->save($data, $data_id);
            $this->kotak_sampah_m->delete_id($id);
        }

        if ($tipe == 2) 
        {
            $data = array(
               'is_active' => 1
            );

            $poliklinik = $this->poliklinik_m->save($data, $data_id);
            $this->kotak_sampah_m->delete_id($id);
        }

        if ($tipe == 3 ) 
        {
            $data = array(
                'is_active' => 1
            );
            
            $poliklinik_tindakan = $this->poliklinik_tindakan_m->save($data, $data_id);
            $this->kotak_sampah_m->delete_id($id);
        }

        if ($tipe == 4) 
        {
            $data = array(
                'is_active' => 1
            );
            
            $user_level = $this->user_level_m->save($data, $data_id);
            $this->kotak_sampah_m->delete_id($id);
        }

        if ($tipe == 5) 
        {
            $data = array(
                'is_active' => 1
            );

            $user = $this->user_m->save($data, $data_id);
            $this->kotak_sampah_m->delete_id($id);
        }

        if ($tipe == 7) 
        {
            $data = array(
                'is_active' => 1
            );

            $user = $this->kategori_m->save($data, $data_id);
            $this->kotak_sampah_m->delete_id($id);
        }

        if ($tipe == 8) 
        {
            $data = array(
                'is_active' => 1
            );

            $user = $this->item_sub_kategori_m->save($data, $data_id);
            $this->kotak_sampah_m->delete_id($id);
        }

        if ($cabang || $poliklinik || $poliklinik_tindakan || $user_level || $user) 
        {
            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data telah dikembalikan", $this->session->userdata("language")),
                "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/kotak_sampah/");
    }


}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */