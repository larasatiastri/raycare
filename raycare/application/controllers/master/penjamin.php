<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjamin extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '0a2a65bea7948abb5c753468dbbd58e4';                  // untuk check bit_access

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

        $this->load->model('master/cabang_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/tindakan_m');
        $this->load->model('master/tindakan_view_m');
        $this->load->model('master/poliklinik_tindakan_m');
        $this->load->model('master/poliklinik_harga_tindakan_m');
        $this->load->model('master/kelompok_klaim_m');
        $this->load->model('master/kelompok_klaim2_m');
        $this->load->model('master/kelompok_klaim3_m');
        $this->load->model('master/penjamin_m');
        $this->load->model('master/syarat_m');
        $this->load->model('master/syarat_detail_m');
        $this->load->model('master/penjamin_syarat_m');
        $this->load->model('master/penjamin_scan_dokumen_m');
        $this->load->model('others/kotak_sampah_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/master/penjamin/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Penjamin', $this->session->userdata('language')), 
            'header'         => translate('Master Penjamin', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'master/penjamin/index',
            'flag'           => 2,
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function poliklinik_tindakan($id)
    {
        $assets = array();
        $config = 'assets/master/tindakan/tindakan';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $form_data  = $this->tindakan_m->get($id);
        $form_data2 = $this->poliklinik_tindakan_m->getdata($id)->result_array();
        $form_data3 = $this->poliklinik_tindakan_m->getdata4($id)->result_array();

        $data = array(
            'title'          => config_item('site_name').' &gt;'.translate('Master Cabang', $this->session->userdata('language')), 
            'header'         => translate('Master Cabang', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'pk'             => $id,
            'form_data'      => $form_data,
            'form_data2'     => $form_data2,
            'form_data3'     => $form_data3,
            'flag'           => 1,
            'content_view'   => 'master/tindakan/tindakan',
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_penjamin','add'))
        {
            $assets = array();
            $assets_config = 'assets/master/penjamin/add';
            $this->config->load($assets_config, true);

            $assets = $this->config->item('assets', $assets_config);

            $data = array(
                'title'          => config_item('site_name').' &gt;'. translate("Tambah Tindakan", $this->session->userdata("language")), 
                'header'         => translate("Tambah Tindakan", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'flag'           => 1,
                
                'content_view'   => 'master/penjamin/add',
            );

            // Load the view
            $this->load->view('_layout', $data);
        }
        else 
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
    }

    public function edit($id)
    {
        if(restriction_function($this->session->userdata('level_id'), 'master_penjamin','edit'))
        {
            $id = intval($id);
            $id || redirect(base_Url());

            $assets = array();
            $config = 'assets/master/penjamin/edit';
            $this->config->load($config, true);

            $assets = $this->config->item('assets', $config);
            
            // $this->cabang_m->set_columns($this->cabang_m->fillable_edit());
           
            // $form_data = $this->cabang_m->get($id);
            $form_data=$this->penjamin_m->get($id);
          //  $form_data2=$this->poliklinik_tindakan_m->getdata($id)->result_array();

            $data = array(
                'title'          => config_item('site_name').' &gt;'. translate("Edit Tindakan", $this->session->userdata("language")), 
                'header'         => translate("Edit Tindakan", $this->session->userdata("language")), 
                'header_info'    => config_item('site_name'), 
                'breadcrumb'     => TRUE,
                'menus'          => $this->menus,
                'menu_tree'      => $this->menu_tree,
                'css_files'      => $assets['css'],
                'js_files'       => $assets['js'],
                'content_view'   => 'master/penjamin/edit',
                'form_data'      => $form_data,
                'pk'             => $id,                    //table primary key value
                'flag'           => 2
            );

            // Load the view
            $this->load->view('_layout', $data);
        }
        else
        {
            $flashdata = array(
                "type"     => "error",
                "msg"      => translate("Anda tidak memiliki akses fitur tersebut.", $this->session->userdata("language")),
                "msgTitle" => translate("Peringatan", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
            redirect('home/dashboard');
        }
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
            // 'menus'          => $this->menus,
            // 'menu_tree'      => $this->menu_tree,
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
   

    public function listing($id=null)
    {        
        $cari='';
        if($id=='3'){
            $cari='is_suspended in ("0","1")';
        }else{
            $cari='is_suspended='.$id;
        }
        $result = $this->penjamin_m->get_datatable($cari);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;

        $i=0;
        $user_level_id = $this->session->userdata('level_id');
        foreach($records->result_array() as $row)
        {
            $action = '';
            $status = '';
            
            if($row['is_suspended']==1)
            {
                $status='<span class="label label-xs label-success">Tidak Aktif</span>';
            }else
            {
                $status='<span class="label label-xs label-danger">Aktif</span></div>';
            }

            $data_edit   = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/penjamin/edit/'.$row['id'].'" class="btn blue-chambray"><i class="fa fa-edit"></i></a>';
            $data_delete = '<a title="'.translate('Hapus', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data penjamin ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn red"><i class="fa fa-times"></i> </a>';
            

            $action =  restriction_button($data_edit,$user_level_id,'master_penjamin','edit').restriction_button($data_delete,$user_level_id,'master_penjamin','delete');

            $output['data'][] = array(
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>' 
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
            
            $action = '<div class="text-center"><a class="btn green-haze select" data-item="'.htmlentities(json_encode($row)).'"><i class="fa fa-check"></i></a></div>';

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
        $result = $this->tindakan_view_m->get_datatable($id);

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
                $modal=$this->formatrupiah($row['harga']).' <a  href="#" id="select2[]" name="select[]2" data-id="'.$row['id'].'"  data-target="#ajax_notes" data-toggle="modal" class="btn grey-cascade search-item"><i class="fa fa-search"></i></a>';
            }else{
                $modal=$this->formatrupiah($row['harga']);
            }
            

            $action = '<div class="text-center"><a class="btn green-haze select" id="select[]" name="select[]" data-id="'.$row['id'].'"  data-target="#ajax_notes" data-toggle="modal"><i class="fa fa-dollar"></i></a></div>';
           

            $output['data'][] = array(
               
                '<div class="text-center">'.$row['nama'].'</div>' ,
                
                '<div class="text-right">'.$modal.'</div>' ,
                '<div class="text-center">'.$row['nama'].'</div>' ,
               
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
           // echo $this->input->post('nama');
           // $data = $this->cabang_m->array_from_post( $this->cabang_m->fillable_add());
            $data['nama'] = $this->input->post('nama');
            $data['tanggal_aktif'] = date('Y-m-d',strtotime($this->input->post('tggl1')));
            $data['tanggal_non_aktif'] = date('Y-m-d',strtotime($this->input->post('tggl2')));
            $data['keterangan'] = $this->input->post('keterangan1');
            $data['is_suspended'] = $this->input->post('suspval');
            $data['is_active'] = 1;
            if( $this->input->post('kel')==1)
            {
                $data['is_parent']=1;

            }

            if( $this->input->post('kel')==2)
            {
                $data['url']=$this->input->post('url');
                $data['is_parent']=0;
            }

            $penjamin_id = $this->penjamin_m->save($data);

          
            if( $this->input->post('kel')==1)
            {
                $kelid=$this->input->post('check');
                foreach ($kelid as $key=>$value) {
                     $data5['penjamin_id']=$penjamin_id;
                     $this->kelompok_klaim_m->save($data5,$value);
                }
                
                

            }
               
 
            $jaminan=$this->input->post('payment');

            foreach ($jaminan as $row) {
                if($row['idsyarat']!=null)
                {
                    $data4['penjamin_id'] = $penjamin_id;
                    $data4['syarat_id'] =  $row['idsyarat'];
                    $data4['is_active'] =  1;

                    $penjamin_syarat_id=$this->penjamin_syarat_m->save($data4);
                }else{
                    $data2['judul'] = $row['judul'];
                    $data2['tipe'] =  $row['payment_type'];
                    $data2['maksimal_karakter'] = $row['text'];
                    $data2['is_active'] = 1;
                    $syarat_id=$this->syarat_m->save($data2);

                    $data4['penjamin_id'] = $penjamin_id;
                    $data4['syarat_id'] =  $syarat_id;
                    $data4['is_active'] =  1;

                    $penjamin_syarat_id=$this->penjamin_syarat_m->save($data4);

                    if($row['payment_type']==4 || $row['payment_type']==5 || $row['payment_type']==6 || $row['payment_type']==7)
                    {
 
                        $field=$this->input->post($row['idcount'].'tindakan');
                        foreach($field as $row)
                        {
                            $data3['syarat_id'] = $syarat_id;
                            $data3['text'] =  $row['text1'];
                            $data3['value'] = $row['isi'];
                            $this->syarat_detail_m->save($data3);
                        }
                    }
                }
                

                
            }
          
            
//============Upload=======
            $array_input = $this->input->post('upload');
             
            
            $path_dokumen = 'assets/mb/pages/master/penjamin/images/dokumen/'.$penjamin_id;
            $path_temporary = 'assets/mb/pages/master/penjamin/images/temp/';
            
         //   echo base_url().$path_temporary.$array_input['default_file_name_1'].'<br>'.base_url().$path_dokumen.'/'.$array_input['default_file_name_1'];
            //jika folder pasien untuk dokumen belum ada, buat folder nya pakai nomor member pasien
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
            
             foreach($array_input as $row)
             {
                if(!$row['filename'] == "")
                {
                    $temp_filename = $row['filename'];               
                 
                    $full_path_new_filename = $path_dokumen."/".$temp_filename;
                    
                     
                  //  $this->model_approval->set_Url_Scan_Surat_Keterangan($full_path_new_filename);
                     
                    $data6['penjamin_id']=$penjamin_id;
                    $data6['url']=$full_path_new_filename;
                    $data6['is_active']=1;
                    $this->penjamin_scan_dokumen_m->save($data6);

                    $url1 = $path_temporary.$row['filename'];
                    $url2 = $path_dokumen.'/'.$row['filename'];
                    rename($url1, $url2);
                }
             }
                
             
                
                    
                    // if(!$array_input['default_file_name_1'] == "")
                    // {
                    //     $url1 = $path_temporary.$array_input['default_file_name_1'];
                    //     $url2 = $path_dokumen.'/'.$array_input['default_file_name_1'];
                    //     rename($url1, $url2);
                    // }

//=========================
            if ($penjamin_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data penjamin berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            
        }

        elseif ($command === 'edit')
        {
            $data['nama'] = $this->input->post('nama');
            $data['tanggal_aktif'] = date('Y-m-d',strtotime($this->input->post('tggl1')));
            $data['tanggal_non_aktif'] = date('Y-m-d',strtotime($this->input->post('tggl2')));
            $data['keterangan'] = $this->input->post('keterangan1');
            $data['is_suspended'] = $this->input->post('suspval');
            
            if( $this->input->post('kel')==1)
            {
                $data['is_parent']=1;
                $data['url']='';
            }

            if( $this->input->post('kel')==2)
            {
                $data['url']=$this->input->post('url');
                $data['is_parent']=0;
            }

            $penjamin_id = $this->penjamin_m->save($data,$this->input->post('pk'));

          
            $this->syarat_m->update_penjamin_id_kelompok($this->input->post('pk'));
            if( $this->input->post('kel')==1)
            {
                $kelid=$this->input->post('check');
                foreach ($kelid as $key=>$value) {
                     $data5['penjamin_id']=$penjamin_id;
                     $this->kelompok_klaim_m->save($data5,$value);
                }
                
                

             }

            $this->syarat_m->deletesyarat($this->input->post('pk'));  
           

            $jaminan=$this->input->post('payment');

            foreach ($jaminan as $row) {
                if($row['idsyarat']!=null)
                {
                    $data4['penjamin_id'] = $penjamin_id;
                    $data4['syarat_id'] =  $row['idsyarat'];
                    $data4['is_active'] =  1;

                    $penjamin_syarat_id=$this->penjamin_syarat_m->save($data4);
                }else{
                    // if($row['isdeleted']!='')
                    // {

                    // }
                    $data2['judul'] = $row['judul'];
                    $data2['tipe'] =  $row['payment_type'];
                    $data2['maksimal_karakter'] = $row['text'];
                    $data2['is_active'] = 1;
                    $syarat_id=$this->syarat_m->save($data2);
//+++++++++++
                    $this->syarat_m->update_syarat_id($syarat_id,$row['idsyarat2']);

                    if($row['isdeleted']==1)
                    {
                        $data4['penjamin_id'] = $penjamin_id;
                        $data4['syarat_id'] =  $syarat_id;
                        $data4['is_active'] =  1;

                        $penjamin_syarat_id=$this->penjamin_syarat_m->save($data4);

                    }
                    
                    if($row['payment_type']==4 || $row['payment_type']==5 || $row['payment_type']==6 || $row['payment_type']==7)
                    {
 
                        $field=$this->input->post($row['idcount'].'tindakan');
                        foreach($field as $row)
                        {
                            $data3['syarat_id'] = $syarat_id;
                            $data3['text'] =  $row['text1'];
                            $data3['value'] = $row['isi'];
                            $this->syarat_detail_m->save($data3);
                        }
                    }
                }
                

                
            }
          
            
//============Upload=======
            $array_input = $this->input->post('upload');
             
            
            $path_dokumen = 'assets/mb/pages/master/penjamin/images/dokumen/'.$penjamin_id;
            $path_temporary = 'assets/mb/pages/master/penjamin/images/temp/';
            
            delete_files($path_dokumen, true);
         //   echo base_url().$path_temporary.$array_input['default_file_name_1'].'<br>'.base_url().$path_dokumen.'/'.$array_input['default_file_name_1'];
            //jika folder pasien untuk dokumen belum ada, buat folder nya pakai nomor member pasien
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
            
             foreach($array_input as $row)
             {
                if(!$row['filename'] == "")
                {
                    $temp_filename = $row['filename'];               
                 
                    $full_path_new_filename = $path_dokumen."/".$temp_filename;
                    
                     
                  //  $this->model_approval->set_Url_Scan_Surat_Keterangan($full_path_new_filename);
                     
                    $data6['penjamin_id']=$penjamin_id;
                    $data6['url']=$full_path_new_filename;
                    $data6['is_active']=1;
                    $this->penjamin_scan_dokumen_m->save($data6);

                    $url1 = $path_temporary.$row['filename'];
                    $url2 = $path_dokumen.'/'.$row['filename'];
                    rename($url1, $url2);
                }
             }
                
            if ($penjamin_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data penjamin berhasil diubah.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
        } 

        redirect("master/penjamin");
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
        
        $data = array(
                'is_active'    => 0
            );
            $msg = "Penjamin sudah dihapus";
 
        // save data
        $user_id = $this->penjamin_m->save($data, $id);

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
            'tipe'  => 6,
            'data_id'    => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
        );

        $trash = $this->kotak_sampah_m->simpan($data_trash);

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
        $poli_id=$this->input->post('poli_id');
        
        // save data
       if($tindakan_id!='')
        {
            $data1['poliklinik_id']=$poli_id;
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
        $result=$this->poliklinik_tindakan_m->getdata4($pk)->result_array();
       
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

      public function checkpoli()
    {
        
        $poli_id=$this->input->post('poli_id');
        $tindakan_id=$this->input->post('tindakan_id');
       
        // save data
        $result=$this->tindakan_m->checkpoli($poli_id,$tindakan_id)->result_array();
       
        echo json_encode($result[0]['counts']);
    }

     public function listing_kelompok($flag=null,$id=null)
    {        
        $result='';
        if($flag==1){
            $result = $this->kelompok_klaim2_m->get_datatable();
        }else{
            $result = $this->kelompok_klaim3_m->get_datatable($id);
        }
        

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
            
            if($row['penjamin_id']!=null && $row['penjamin_id']==$id){
                $action = '<div class="text-center"><input type="checkbox" id="check[]" name="check[]" value="'.$row['id'].'" checked></div>';
            }else{
                $action = '<div class="text-center"><input type="checkbox" id="check[]" name="check[]" value="'.$row['id'].'"></div>';
            }
            

            $output['data'][] = array(
               
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-left">'.$row['url'].'</div>',
                '<div class="text-left">'.$row['url'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

     public function listing_syarat()
    {        
        $result = $this->syarat_m->get_datatable();

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
            
            $action = '<a title="'.translate('Tambah', $this->session->userdata('language')).'"   name="pilih[]" data-action="pilih" data-id="'.$row['id'].'" data-tipe="'.$row['tipe'].'" data-judul="'.$row['judul'].'" data-max="'.$row['maksimal_karakter'].'" class="btn btn-primary"><i class="fa fa-check"></i> </a>';

            $output['data'][] = array(
               
                '<div class="text-left">'.$row['judul'].'</div>',
                 '<div class="text-left">'.$row['tipe'].'</div>',
                 '<div class="text-left">'.$row['maksimal_karakter'].'</div>',
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

        public function syaratdetail()
    {
        
        $id=$this->input->post('id');
        
       
        // save data
        $result=$this->syarat_detail_m->syaratdetail($id)->result_array();
       
        echo json_encode($result);
    }

      public function syarat()
    {
        
        $id=$this->input->post('id');
        
       
        // save data
        $result=$this->syarat_m->syarat($id)->result_array();
       
        echo json_encode($result);
    }

     public function syarat2()
    {
        
        $id1=$this->input->post('id1');
        $id2=$this->input->post('id2');
       
        // save data
        $result=$this->syarat_m->syarat2($id1,$id2)->result_array();
       
        echo json_encode($result);
    }

      public function scan()
    {
        
        $id=$this->input->post('id');
        $path_dokumen = 'assets/mb/pages/master/penjamin/images/dokumen/'.$id;
        $path_temporary = 'assets/mb/pages/master/penjamin/images/temp/';
       
        // save data
        $result=$this->syarat_m->scan($id)->result_array();

        foreach ($result as $row) {
            $pieces = explode("/", $row['url']);
             if(read_file($path_dokumen.'/'.$pieces[8]))
             {
                 $url1 = $path_temporary.$pieces[8];
                 $url2 = $path_dokumen.'/'.$pieces[8];
                 copy($url2, $url1);
             }
           
        }
        

        echo json_encode($result);
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */