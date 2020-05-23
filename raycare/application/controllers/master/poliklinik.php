<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poliklinik extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 3;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(2, 3);       // untuk keperluan item menu dengan class 'open', 'selected'

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('master/cabang_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/poliklinik_tindakan_m');
        $this->load->model('master/poliklinik_harga_tindakan_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/poliklinik/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Poliklinik', $this->session->userdata('language')), 
            'header'         => translate('Master Poliklinik', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/poliklinik/index',
            'flag'           => 2,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function poliklinik_tindakan($id)
    {
        $assets = array();
        $config = 'assets/master/poliklinik/tindakan';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data  = $this->poliklinik_m->get($id);
        $form_data2 = $this->poliklinik_tindakan_m->getdata($id)->result_array();
        $form_data3 = $this->poliklinik_tindakan_m->getdata3($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Cabang', $this->session->userdata('language')), 
            'header'         => translate('Master Cabang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'pk'             => $id,
            'form_data'      => $form_data,
            'form_data2'     => $form_data2,
            'form_data3'     => $form_data3,
            'flag'           => 1,
            'content_view'   => 'master/poliklinik/tindakan',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $assets_config = 'assets/master/poliklinik/add';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("Tambah Poliklinik", $this->session->userdata("language")), 
            'header'         => translate("Tambah Poliklinik", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            
            'content_view'   => 'master/poliklinik/add',
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/poliklinik/edit';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        // $form_data = $this->cabang_m->get($id);
        $form_data=$this->poliklinik_m->get($id);
        $form_data2=$this->poliklinik_tindakan_m->getdata($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' &gt;'. translate("Edit Poliklinik", $this->session->userdata("language")), 
            'header'         => translate("Edit Poliklinik", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/poliklinik/edit',
            'form_data'      => $form_data,
            'form_data2'     => $form_data2,
            'pk'             => $id,                    //table primary key value
            'flag'           => 2
        );

        // Load the view
        $this->load->view('_layout', $data);
    }

    public function view($id)
    {
        $id = intval($id);
        $id || redirect(base_Url());

        $assets = array();
        $config = 'assets/master/poliklinik/tindakan';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
        
        // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
       
        // $form_data = $this->cabang_m->get($id);
        $form_data=$this->poliklinik_m->get($id);
        $data = array(
            'title'          => config_item('site_name'). translate("Lihat Poliklinik", $this->session->userdata("language")), 
            'header'         => translate("Lihat Poliklinik", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'flag'          =>2,
            'pk'             => $id,
            'content_view'   => 'master/poliklinik/tindakan',
            'form_data'      => $form_data,
            'pk_value'       => $id                         //table primary key value
        );

        // Load the view
        $this->load->view('_layout', $data);
    }
   

    public function listing()
    {        
        $result = $this->poliklinik_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
                
            $action = '<a title="'.translate('Lihat', $this->session->userdata('language')).'"  name="view[]" data-action="view" data-id="'.$row['id'].'" class="btn btn-xs grey-cascade search-item"><i class="fa fa-search"></i></a>
                        <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/poliklinik/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                        <a title="'.translate('Poliklinik Tindakan', $this->session->userdata('language')).'" href="'.base_url().'master/poliklinik/poliklinik_tindakan/'.$row['id'].'" class="btn btn-xs yellow"><i class="fa fa-list-alt"></i></a>
                        <a title="'.translate('Hapus', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data poliklinik ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
       
            $output['data'][] = array(
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
            $i++;
        }

        echo json_encode($output);
    }

    public function listing_item()
    {        
        $result = $this->tindakan_m->get_datatable();

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            
            $action = '<div class="text-center"><a class="btn btn-xs green-haze select" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-check"></i></a></div>';

            $output['data'][] = array(
               
                '<div class="text-center">'.$row['kode'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-right">'.$this->formatrupiah($row['harga']).'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function listing_tindakan($id=null,$type=null)
    {        
        $result = $this->poliklinik_tindakan_m->get_datatable($id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
            $action = '';
            if($type==2){
                $modal=$this->formatrupiah($row['harga']).' <a  href="#" id="select2[]" name="select[]2" data-id="'.$row['id'].'"  data-target="#ajax_notes" data-toggle="modal" class="btn btn-xs grey-cascade search-item"><i class="fa fa-search"></i></a>';
            }else{
                $modal=$this->formatrupiah($row['harga']);
            }
            

            $action = '<div class="text-center"><a class="btn btn-xs green-haze select" id="select[]" name="select[]" data-id="'.$row['id'].'"  data-target="#ajax_notes" data-toggle="modal"><i class="fa fa-dollar"></i></a></div>';
           

            $output['data'][] = array(
               
                '<div class="text-center">'.$row['kode'].'</div>' ,
                $row['nama'],
                '<div class="text-right">'.$modal.'</div>' ,
              
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

     public function listing_tindakan_2($id=null)
    {        
        $result = $this->poliklinik_harga_tindakan_m->get_datatable($id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        foreach($records->result_array() as $row)
        {
          
            $output['data'][] = array(
                '<div class="text-center">'.date('d F Y',strtotime($row['tanggal'])).'</div>',
                '<div class="text-right">'.$this->formatrupiah($row['harga']).'</div>',
               $row['harga'],
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
           // $data = $this->cabang_m->array_from_post( $this->cabang_m->fillable_add());
            $data['kode'] = $this->input->post('kode1');
            $data['nama'] = $this->input->post('nama1');
            $data['keterangan'] = $this->input->post('keterangan1');
            $data['is_active'] = 1;
            $poliklinik_id = $this->poliklinik_m->save($data);

            // $tindakan=$this->input->post('tindakan');
            // foreach ($tindakan as $row) 
            // {
            //     if($row['tindakan_id']!='')
            //     {
            //         $data1['poliklinik_id']=$poliklinik_id;
            //         $data1['tindakan_id']=$row['tindakan_id'];
            //         $data1['is_active'] = 1;
            //         $this->poliklinik_tindakan_m->save($data1);
            //     }
            //  }   

            if ($poliklinik_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data poliklinik berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }

        elseif ($command === 'edit')
        {
            $data['kode'] = $this->input->post('kode1');
            $data['nama'] = $this->input->post('nama1');
            $data['keterangan'] = $this->input->post('keterangan1');
            
            $poliklinik_id = $this->poliklinik_m->save($data, $this->input->post('pk'));

            // $result=$this->poliklinik_tindakan_m->get_by(array('poliklinik_id' => $this->input->post('pk')));
            // $result=object_to_array($result);
            // foreach($result as $row)
            // {
            //     $this->poliklinik_tindakan_m->delete($row['id']);
            // }

            // $tindakan=$this->input->post('tindakan');
            // foreach ($tindakan as $row) 
            // {
            //     if($row['tindakan_id']!='')
            //     {
            //         $data1['poliklinik_id']=$this->input->post('pk');
            //         $data1['tindakan_id']=$row['tindakan_id'];
            //         $data1['is_active'] = 1;
            //         $this->poliklinik_tindakan_m->save($data1);
            //     }
            //  }   

            if ($poliklinik_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data poliklinik berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/poliklinik");
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->cabang_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id'   => $trash_id,
            'tipe'              => 2,
            'data_id'           => $id,
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Branch Deleted", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/cabang");
    }

    public function deleteajax()
    {
        
        $id     = $this->input->post('id');
        $type   = $this->input->post('type');
        $msg    = '';

        if($type==1)
        {
            $data = array(
                'is_active'    => 0
            );
            $msg = "Poliklinik sudah dihapus";

        } else
        {
            $data = array(
                'is_active'    => 1
            );
            $msg = "Poliklinik sudah dikembalikan";
        }
       
        // save data
        $user_id = $this->poliklinik_m->save($data, $id);

        $trash_id='';
        $max_id = $this->kotak_sampah_m->max2();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // Poliklinik
        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 2,
            'data_id'         => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->save($data_trash);

        if ($user_id) 
        {
            $flashdata = array(
                "error",
                translate($msg, $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );

            echo json_encode($flashdata);
        }
       // redirect("master/cabang");
    }


    public function restore($id)
    {
           
        $data = array(
            'is_active'    => 1
        );

        // save data
        $user_id = $this->cabang_m->save($data, $id);
        if ($user_id) 
        {
            $flashdata = array(
                "type"     => "warning",
                "msg"      => translate("Branch Restored", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
                );
            $this->session->set_flashdata($flashdata);
        }
        redirect("master/cabang");
    }

    public function formatrupiah($val) {
        $hasil ='Rp. ' . number_format($val, 0 , '' , '.' ) . ',-';
        return $hasil;
    }

    public function insertharga()
    {
        
        $id=$this->input->post('id');
        $tggl=$this->input->post('tggl');
        $harga=$this->input->post('harga');
 
        // save data
        $data['poliklinik_tindakan_id']=$id;
        $data['tanggal']=date('Y-m-d',strtotime($tggl));
        $data['harga']=$harga;
        $data['is_active']=1;
        $user_id = $this->poliklinik_harga_tindakan_m->save($data);

       
        if ($user_id) 
        {
            $flashdata = array("success",translate("Harga telah ditambahkan", $this->session->userdata("language")),translate("Sukses", $this->session->userdata("language")));
            echo json_encode($flashdata);
          //  $this->session->set_flashdata($flashdata);
        }
       // redirect("master/cabang");
    }

    public function getdata()
    {
        
        $id=$this->input->post('id');
        
        $form_data=$this->poliklinik_tindakan_m->getdata($id)->result_array();
        echo json_encode($form_data);
           
         
       // redirect("master/cabang");
    }

    public function getdata2()
    {
        
        $id=$this->input->post('id');
        
        $form_data=$this->poliklinik_m->getdata($id)->result_array();
      //  $form_data=object_to_array($form_data);
        echo json_encode($form_data);
           
         
       // redirect("master/cabang");
    }

     public function saveajax()
    {
        
        $tindakan_id=$this->input->post('tindakan_id');
        $pk=$this->input->post('pk');
        
        // save data
       if($tindakan_id!='')
        {
            $data1['poliklinik_id']=$pk;
            $data1['tindakan_id']=$tindakan_id;
            $data1['is_active'] = 1;
            $result=$this->poliklinik_tindakan_m->save($data1);
         }

       
        if ($result) 
        {
            $flashdata = array(
                "success",
                translate("Tindakan telah ditambahkan", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language")),
                $result
            );
            echo json_encode($flashdata);
          //  $this->session->set_flashdata($flashdata);
        }
       // redirect("master/cabang");
    }

     public function getdataajax()
    {
        
        
        $pk=$this->input->post('pk');
        
        // save data
        $result=$this->poliklinik_tindakan_m->getdata2($pk)->result_array();
       
        echo json_encode($result);
    }

    public function deletetindakanajax()
    {
        
        $id=$this->input->post('id');
        $pk=$this->input->post('pk');

        $data = array(
            'is_active'    => 0
        );

        $user_id = $this->poliklinik_tindakan_m->save($data, $id);
        // die_dump($user_id);

        $trash_id='';
        $max_id = $this->kotak_sampah_m->max2();
        if ($max_id->kotak_sampah_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->kotak_sampah_id+1;
        }

        // 3 Poliklinik Tindakan
        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'            => 3,
            'data_id'         => $pk,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

         $trash = $this->kotak_sampah_m->simpan($data_trash);

       
          if ($user_id) 
        {
            $flashdata = array(
                "error",
                translate("Poliklinik tindakan dihapus", $this->session->userdata("language")),
                translate("Sukses", $this->session->userdata("language"))
            );
            echo json_encode($flashdata);
         }
        
        // save data
       // $result=$this->poliklinik_tindakan_m->getdata2($pk)->result_array();
       
         
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */