<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = 3;                  // untuk check bit_access

    private $menus = array();
    
    private $menu_tree = array(2, 3);       // untuk keperluan item menu dengan class 'open', 'selected'

    // private $data_main = array();

    public function __construct()
    {       
        parent::__construct();

        if( !$this->input->is_ajax_request())
        {
            $user_level = $this->user_m->level_id();
            $this->menus = $this->user_level_menu_m->get_nested($user_level);
        }

        $this->load->model('master/cabang_m');
        $this->load->model('master/alamat_m');
        $this->load->model('master/telepon_m');
        $this->load->model('master/region_m');
        $this->load->model('master/subjek_m');
        $this->load->model('master/pasien_m');
        $this->load->model('master/poliklinik_m');
        $this->load->model('master/cabang_poliklinik_m');
        $this->load->model('master/cabang_poliklinik_dokter_m');
        $this->load->model('master/cabang_poliklinik_perawat_m');
        $this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_m');
        $this->load->model('reservasi/pendaftaran/antrian_m');
        $this->load->model('reservasi/pendaftaran/rujukan_m');
        $this->load->model('reservasi/pendaftaran/pembayaran_m');
        $this->load->model('others/kotak_sampah_m'); 
        $this->load->model('klinik_hd/klaim_m');
        $this->load->model('master/transaksi_dokter3_m');
         $this->load->model('master/transaksi_dokter_m');
        $this->load->model('master/transaksi_dokter4_m');
        $this->load->model('reservasi/keuangan/laporan_keuangan_m');
       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/reservasi/keuangan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
       
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header'         => translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/keuangan/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/reservasi/keuangan/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
       
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header'         => translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'reservasi/keuangan/add',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

     public function refresh()
    {
        // $assets = array();
        // $config = 'assets/reservasi/pendaftaran/index';
        // $this->config->load($config, true);
        // $assets = $this->config->item('assets', $config);
        // $this->load->model('reservasi/pendaftaran/pembayaran_m');
        // // die(dump( $assets['css'] ));
        // $data = array(
        //     'title'          => config_item('site_name').' | '.translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
        //     'header'         => translate('Reservasi Pendaftaran', $this->session->userdata('language')), 
        //     'header_info'    => config_item('site_name'), 
        //     'breadcrumb'     => true,
        //     'menus'          => $this->menus,
        //     'menu_tree'      => $this->menu_tree,
        //     'css_files'      => $assets['css'],
        //     'js_files'       => $assets['js'],
        //     'content_view'   => 'reservasi/pendaftaran/index',
        //     );
        
        // // Load the view
        // $this->load->view('_layout', $data);
         redirect("reservasi/keuangan");
    }

     
    /**     
     * [list description]
     * @return [type] [description]
     */
    public function listing()
    {        
        $result = $this->cabang_m->get_datatable();
        //die_dump($result);
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
            
            $action = '<a title="'.translate('View', $this->session->userdata('language')).'" href="'.base_url().'master/cabang/view/'.$row['id'].'" class="btn btn-xs grey-cascade"><i class="fa fa-search"></i></a>
            <a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'master/cabang/edit/'.$row['id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
            <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data cabang ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" class="btn btn-xs red"><i class="fa fa-times"></i> </a>';
           

            $output['data'][] = array(
                $row['id'],
                '<div class="text-center">'.$row['kode'].'</div>',
                $row['nama'],
                '<div class="text-center">'.$row['nomor'].'</div>',
                $row['alamat'],
                $row['keterangan'],
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

     public function listing_pilih_pasien()
    {
        
        $result = $this->pasien_m->get_datatable_pilih_pasien();
        //die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            
            $action = '';
            if($row['active']== 1)
            {
                $action = '<a title="'.translate('Select', $this->session->userdata('language')).'"  data-item="'.htmlentities(json_encode($row)).'" class="btn btn-xs green-haze select"><i class="fa fa-check"></i></a>';
                
            }

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['tempat_lahir'].', '.date('d M Y', strtotime($row['tanggal_lahir'])).'</div>' ,
                '<div class="text-center">'.$row['alamat'].'</div>' ,
                '<div class="text-center">'.$action.'</div>' 
            );
         $i++;
        }

        echo json_encode($output);
    }

    public function save()
    {
         
       $command=$this->input->post('command');
        if ($command === 'add')
        {  
             
                $tanggal= date('Y-m-d',strtotime($this->input->post('tggl2')));
                $tipe     = $this->input->post('tipe2');
                $rupiah     = $this->input->post('rupiah2');
                $keterangan     = $this->input->post('keterangan2');
                $user_id     =  $this->session->userdata("user_id");
                if($this->input->post('tipe2')==1){
                    $debit_credit="d";
                }else{
                    $debit_credit="c";
                }

                $data=array('tanggal' =>$tanggal,
                            'tipe' =>$tipe,
                            'rupiah' =>$rupiah,
                            'keterangan'=>$keterangan,
                            'user_id'=>$user_id,
                            'debit_credit' =>$debit_credit,
                             'saldo' =>$rupiah,
                            );

                $kasir_arus_kas_id = $this->laporan_keuangan_m->save($data);
          
                $temprupiah=0;
                $temptipe='';
                $saldo=0;
                $x=0;

                $result=$this->laporan_keuangan_m->getdatasaldo($this->session->userdata("user_id"))->result_array();
                foreach($result as $row)
                {
                    $x++;
                    if($x==1)
                    {
                        $temprupiah=$row['rupiah'];
                        $temptipe=$row['debit_credit'];
                        $saldo=$row['saldo'];
                    }else{
                        if($row['debit_credit']=="d" && $temptipe=="d")
                        {
                            
                            $saldo=$row['rupiah']+$saldo;
                           // $temprupiah=$row['rupiah'];
                            $temptipe=$row['debit_credit'];
                        }else if($row['debit_credit']=="c" && $temptipe=="d"){
                            
                            if($saldo>$row['rupiah'])
                            {
                                 $saldo=$saldo-$row['rupiah'];
                             }else{
                                 $saldo=$row['rupiah']-$saldo;
                             }
                           //  $saldo=$saldo-$row['rupiah'];
                        //    $temprupiah=$row['rupiah'];
                            $temptipe=$row['debit_credit'];
                        }else if($row['debit_credit']=="d" && $temptipe=="c"){
                            if($x==2)
                            {
                                 if($saldo>$row['rupiah'])
                                {
                                    $saldo=$saldo-$row['rupiah'];
                                }else{
                                    $saldo=$row['rupiah']-$saldo;
                                }
                            }else{
                                $saldo=$row['rupiah']+$saldo;
                            }
                           
                            //$saldo=$row['rupiah']-$saldo;
                           // $temprupiah=$row['rupiah'];
                            $temptipe=$row['debit_credit'];
                        }else if($row['debit_credit']=="c" && $temptipe=="c"){
                          
                            $saldo=$saldo-$row['rupiah'];
                           // $temprupiah=$row['rupiah'];
                            $temptipe=$row['debit_credit'];
                        }
                       $data2['saldo']=$saldo;
                       $result3=$this->laporan_keuangan_m->save($data2,$row['id']);
                    }


                    
                }
             
            
            //die_dump($this->db->last_query());           

            if ($kasir_arus_kas_id) 
            {
                $flashdata = array(
                    "type"     => "success",
                    "msg"      => translate("Data Keuangan berhasil ditambahkan.", $this->session->userdata("language")),
                    "msgTitle" => translate("Sukses", $this->session->userdata("language"))    
                    );
                $this->session->set_flashdata($flashdata);
            }
            redirect("reservasi/keuangan/refresh");
            
        }
        elseif ($command === 'edit')
        {
            $id = $this->input->post('id');
            $id_alamat = $this->input->post('id_alamat');

            $data = array(
                "id" => $id,
                "tipe" => $tipe,
                "kode"  => $kode,
                "nama"  => $nama,
                "keterangan" => $ket,
                "is_active" => 1
            );
            $cabang_id = $this->cabang_m->save($data, $id);
            //die_dump($this->db->last_query());

            $data_alamat = array(
                "id" => $id_alamat,
                "cabang_id" => $cabang_id,
                "subjek_id" => $sub_alamat,
                "alamat" => $alamat,
                "rt_rw" => $rt_rw,
                "kode_pos" => $kode_pos,
                "negara_id" => $negara,
                "propinsi_id" => $provinsi,
                "kota_id" => $kota_kabupaten,
                "kecamatan_id" => $kecamatan,
                "kelurahan_id" => $kelurahan,
                "is_primary" => 1,
                "is_active" => 1
            );
            //die_dump($data_alamat);
            $alamat_id = $this->alamat_m->save($data_alamat, $id_alamat);
            //die_dump($this->db->last_query());

            foreach ($telepon as $input) 
            {
                if(isset($input['is_primary']) && $input['is_primary'] !== false)
                {
                    $stat_primary = 1;
                }
                else
                {
                    $stat_primary = 0;
                }

                if ($input['id'] != "" && $input['is_delete'] == "") 
                {
                    $data_telepon = array(
                        "cabang_id"  => $cabang_id,
                        "subjek_id"  => $input['subjek'],
                        "nomor"      => $input['number'],
                        "is_primary" => $stat_primary,
                        "is_active"  => 1
                    );
                    $telepon_id = $this->telepon_m->save($data_telepon, $input['id']);
                    // $save_phone = $this->pasien_telepon_m->save($data_phone, $phone['id']); 
                }

                if ($input['id'] != "" && $input['is_delete'] == "1") 
                {                       
                    $save_phone = $this->telepon_m->delete($input['id']); 
                }
                
                if ($input['id'] == "" && $input['is_delete'] == "" && $input['number'] != "") 
                {
                    $data_telepon = array(
                        "cabang_id"  => $cabang_id,
                        "subjek_id"  => $input['subjek'],
                        "nomor"      => $input['number'],
                        "is_primary" => $stat_primary,
                        "is_active"  => 1
                    );
                    
                    $save_phone = $this->telepon_m->save($data_telepon); 
                }
                
            }

            foreach ($poliklinik as $cabang) 
            {
                //die_dump($cabang);
                if ($cabang['id'] != "" && $cabang['is_deleted'] == "")
                {
                    $data_poliklinik = array(
                        "cabang_id"     => $cabang_id,
                        "poliklinik_id" => $cabang['subjek'],
                        "jam_buka"      => date('H:i:s', strtotime($cabang['jam_buka'])),
                        "jam_tutup"     => date('H:i:s', strtotime($cabang['jam_tutup'])),
                        "is_active"     => 1
                    );
                     die_dump($data_poliklinik);
                    $poliklinik_id = $this->cabang_poliklinik_m->save($data_poliklinik, $cabang['id']);
                   
                }
                if($cabang['id'] != "" && $cabang['is_deleted'] == "1")
                {
                    $delete_poliklinik = $this->cabang_poliklinik_m->delete($cabang['id']);
                }
                if ($cabang['id'] == "" && $cabang['is_deleted'] == "")
                {
                    $data_poliklinik = array(
                        "cabang_id"     => $cabang_id,
                        "poliklinik_id" => $cabang['subjek'],
                        "jam_buka"      => date('H:i:s', strtotime($cabang['jam_buka'])),
                        "jam_tutup"     => date('H:i:s', strtotime($cabang['jam_tutup'])),
                        "is_active"     => 1
                    );

                    $poliklinik_id = $this->cabang_poliklinik_m->save($data_poliklinik);
                }               
               
                // die_dump($cabang['dokter']);
                foreach ($cabang['dokter'] as $dokter) 
                {
                    if($cabang['id'] != "" && $cabang['is_deleted'] == "")
                    {
                        $data_dokter = array(
                            "cabang_poliklinik_id" => $poliklinik_id,
                            "dokter_id" => $dokter
                        );
                    
                    $cabang_dokter = $this->cabang_poliklinik_dokter_m->save($data_dokter, $cabang['id']);
                    }
                    
                    if($cabang['id'] != "" && $cabang['is_deleted'] == "1")
                    {
                        $delete_poliklinik_dokter = $this->cabang_poliklinik_dokter_m->delete($cabang['id']);
                    }
                    
                    if ($cabang['id'] == "" && $cabang['is_deleted'] == "")
                    {
                        $data_dokter = array(
                            "cabang_poliklinik_id" => $poliklinik_id,
                            "dokter_id" => $dokter
                        );
                    
                        $cabang_dokter = $this->cabang_poliklinik_dokter_m->save($data_dokter);
                    }
                    
                }

                foreach ($cabang['perawat'] as $perawat) 
                {
                    if($cabang['id'] != "" && $cabang['is_deleted'] == "")
                    {
                        $data_perawat = array(
                            "cabang_poliklinik_id" => $poliklinik_id,
                            "perawat_id" => $perawat
                        );
                    
                    $cabang_perawat = $this->cabang_poliklinik_perawat_m->save($data_perawat, $cabang['id']);
                    }
                    
                    if($cabang['id'] != "" && $cabang['is_deleted'] == "1")
                    {
                        $delete_poliklinik_perawat = $this->cabang_poliklinik_perawat_m->delete($cabang['id']);
                    }
                    
                    if ($cabang['id'] == "" && $cabang['is_deleted'] == "")
                    {
                        $data_perawat = array(
                            "cabang_poliklinik_id" => $poliklinik_id,
                            "perawat_id" => $perawat
                        );
                    
                        $cabang_perawat = $this->cabang_poliklinik_perawat_m->save($data_perawat);
                    }
                }
            }


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

      //  redirect("reservasi/pendaftaran_tindakan",'refresh');
    }

    public function delete($id)
    {
           
        $data = array(
            'is_active'    => 0
        );
        // save data
        $user_id = $this->cabang_m->save($data, $id);

        $max_id = $this->kotak_sampah_m->max();
        if ($max_id->max_id==null){
            $trash_id = 1;
        } else {
            $trash_id = $max_id->max_id+1;
        }

        $data_trash = array(
            'kotak_sampah_id' => $trash_id,
            'tipe'  => 1,
            'data_id'    => $id,
            'created_by'      => $this->session->userdata('user_id'),
            'created_date'    => date('Y-m-d H:i:s')
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

    public function get_dokter(){

        $id_poliklinik = $this->input->post('id_poliklinik');
        
        $poliklinik = $this->cabang_m->get_data_dokter_pendaftaran($id_poliklinik)->result_array();
        //die_dump($this->db->last_query());        
        $hasil_poliklinik = object_to_array($poliklinik);

        //die_dump($this->db->last_query());
        echo json_encode($hasil_poliklinik);
    }

     public function get_status_poli(){

        $status='';
        $id_poliklinik = $this->input->post('id_poliklinik');
         $cabang_id = $this->input->post('cabang_id');
        
        $poliklinik = $this->poliklinik_m->get_status_poli($id_poliklinik,$cabang_id)->result_array();
        $poliklinik2 = $this->poliklinik_m->get_status_poli2($id_poliklinik)->result_array();
        //die_dump($this->db->last_query());        
       // $hasil_poliklinik = object_to_array($poliklinik);
        if($poliklinik[0]['counts'] > 0)
        {
             $status='Buka';
        }else{
            $status='Tidak Ada';
        }

        $body=array($status,$poliklinik2[0]['tipe']);
        //die_dump($this->db->last_query());
        echo json_encode($body);
    }

     public function listing_antrian($poliklinik_id=null,$dokter_id=null)
    {
        
        $result = $this->antrian_m->get_datatable($poliklinik_id,$dokter_id);
        //die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            
           

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">'.$row['antrian'].'</div>',
                 );
         $i++;
        }

        echo json_encode($output);
    }

     public function listing_rujukan($id=null,$cabang_id=null)
    {        

       
        $result = $this->rujukan_m->get_datatable($id,$cabang_id);

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
           
            
        $status='';
        if(date('H:i') >= date('H:i',strtotime($row['jam_buka'])) && date('H:i') <= date('H:i',strtotime($row['jam_tutup'])))
        {
           $status='<span class="label label-xs label-success">Buka</span></div>';
        }else{
            $status='<span class="label label-xs label-danger">tutup</span></div>';
        }

            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['asal'].'</div>',
               '<div class="text-center">'.$row['tujuan'].'</div>',
               '<div class="text-center">'.date('d F Y',strtotime($row['tggldirujuk'])).'</div>',
               '<div class="text-center">'.date('d F Y',strtotime($row['tgglrujukan'])).'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center"><a title="'.translate('Pilih', $this->session->userdata('language')).'"  name="pilihid[]" data-id="'.$row['poliklinik_tujuan_id'].'" class="btn btn-primary"><i class="fa fa-check"></i></a></div>',
                '<div class="text-center">'.$row['asal'].'</div>',
            );
            $i++;
        }

        echo json_encode($output);
    }

      public function listing_klaim($id=null)
    {        

       
        $result = $this->klaim_m->get_datatable($id);

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
           $i++;
            $penggunaan='';
            $penggunaan2='';
            $nokartu='';
            $aktif='';
            $pilih='';

             if($row['id']!=1)
             {
                if(($row['tggl']!=null) && ($row['tipe']!=null))
                {
                   
                    $penggunaan='<span class="label label-xs label-danger">Tidak Tersedia</span>';
                    $penggunaan2='Tidak Tersedia';

                    $nokartu=$row['no_kartu'];
                    $aktif='<span class="label label-xs label-success">Aktif</span>';
                    $pilih='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" disabled required="required">';
                     $hiddenval='<input type="text" id="pilihklaim2_'.$i.'" name="pilihklaim2['.$i.'][claimed2]" value="'.$row['id'].'">';
                }else{
                     $nokartu=$row['no_kartu'];
                    $aktif='<span class="label label-xs label-success">Aktif</span>';
                    $penggunaan='<span class="label label-xs label-danger">Tersedia</span>';
                    $penggunaan2='Tersedia';
                    $pilih='<input type="radio" id="pilihklaim['.$i.'][claimed]" name="pilihklaim[]" value="'.$row['id'].'" class="code" required="required">';
                     $hiddenval='<input type="text" id="pilihklaim2_'.$i.'" name="pilihklaim2['.$i.'][claimed2]" value="'.$row['id'].'">';
                }
            }else{
                 $penggunaan='-';
                  $penggunaan2='-';
                 $nokartu='-';
                 $aktif='-';
                 $hiddenval='<input type="text" id="pilihklaim2_'.$i.'" name="pilihklaim2['.$i.'][claimed2]" value="'.$row['id'].'">';
                 $pilih='<input type="radio" id="pilihklaim2_'.$i.'" name="pilihklaim[]" value="'.$row['id'].'" required="required">';
            }

            $output['data'][] = array(
                 
                '<div class="text-center">'.$row['nama'].'</div>',
                '<div class="text-center">'.$nokartu.'</div>',
                '<div class="text-center">'.$aktif.'</div>',
                '<div class="text-center">'.$penggunaan.'</div>',
                '<div class="text-center">'.$pilih.'</div>',
                 $penggunaan2 
                 
            );
            
        }

        echo json_encode($output);
    }

     public function get_antrian(){

        $id_poliklinik = $this->input->post('poliklinik_id');
        $id_dokter = $this->input->post('dokter_id');

        $poliklinik = $this->antrian_m->get_antrian($id_poliklinik,$id_dokter)->result_array();
        //die_dump($this->db->last_query());        
   

        //die_dump($this->db->last_query());
        echo json_encode(($poliklinik[0]['no_antrian']+1));
    }

     public function listing_upload($id=null)
    {        
        $result = $this->transaksi_dokter3_m->get_datatable($id);

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
            $tipe='';    
            $jenis='';
            $status='';
            $status2='';
            $tipe1='';

               
            
            if($row['tipe']==1){
                $tipe='File';
            }else{
                $tipe='Gambar';
            }

             if($row['jenis']==1){
                $jenis='Dokumen Pelengkap';
            }else{
                $jenis='Dokumen Rekam Medis';
            }

            if($row['tipe']==1){
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="view" href="'.base_url().$row['url_file'].'" target="_blank" class="btn btn-xs grey-cascade search-item"><i class="fa fa-search"></i></a>';
            }else{
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="viewpic[]" data-id="'.$row['url_file'].'" data-target="#ajax_notes2" data-toggle="modal"  class="btn btn-xs grey-cascade search-item"><i class="fa fa-search"></i></a>';
            }

            $action = '<a title="'.translate('Update', $this->session->userdata('language')).'"  name="update[]"   data-item="'.htmlentities(json_encode($row)).'" data-target="#ajax_notes3" data-toggle="modal"  class="btn btn-xs blue-chambray search-item"><i class="fa fa-edit"></i></a>
                         '.$tipe1;
                         

             $date1=date_create(date('Y-m-d'));
             date_add($date1,date_interval_create_from_date_string("10 day"));
             $startdate=date_format($date1,"Y-m-d");

            if(date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])) > date('Y-m-d') ){
                if($startdate == date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])) ){
                    $status='<span class="label label-xs label-danger">Peringatan</span></div>';
                    $status2='Peringatan';
                }else{
                    $status='<span class="label label-xs label-danger">Kadaluarsa</span>';
                    $status2='Kadaluarsa';
                }
                
            }else if(date('Y-m-d',strtotime($row['tanggal_kadaluarsa'])) <= date('Y-m-d') ){
                $status='<span class="label label-xs label-success">Aktif</span></div>';
                $status2='Aktif';
            } 
       
            $output['data'][] = array(
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-center">'.$row['no_dokumen'].'</div>',
                '<div class="text-center">'.date('d M Y',strtotime($row['tanggal_kadaluarsa'])).'</div>',
                 '<div class="text-center">'.$jenis.'</div>',
                '<div class="text-center">'.$tipe.'</div>',
                '<div class="text-center">'.$status.'</div>',
                '<div class="text-center">'.$action.'</div>',
                $status2
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

       public function adddokumen()
    {
        
        
        $nama=$this->input->post('nama');
        $jenisdokumen=$this->input->post('jenisdokumen');
        $namadokumen=$this->input->post('namadokumen');
        $nodokumen=$this->input->post('nodokumen');
        $tipe=$this->input->post('tipedokumen');
        $tggl=$this->input->post('tggl');
        $url=$this->input->post('url');


        $url1='';
        $url2='';
        $full_path_new_filename='';
        $temp_filename='';

        $path_dokumen = 'assets/mb/pages/klinik_hd/transaksi_dokter/images/dokumen/'.$this->input->post('pasienid');
        $path_temporary = 'assets/mb/pages/klinik_hd/transaksi_dokter/images/temp/';
            
         //   echo base_url().$path_temporary.$array_input['default_file_name_1'].'<br>'.base_url().$path_dokumen.'/'.$array_input['default_file_name_1'];
            //jika folder pasien untuk dokumen belum ada, buat folder nya pakai nomor member pasien
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
            
           
                if(!$url == "")
                {
                    $temp_filename = $url;               
                 
                    $full_path_new_filename = $path_dokumen."/".$temp_filename;
                    
                     
                  //  $this->model_approval->set_Url_Scan_Surat_Keterangan($full_path_new_filename);
                     
                    $data['url_file']=$full_path_new_filename;
                    

                    $url1 = $path_temporary.$url;
                    $url2 = $path_dokumen.'/'.$url;
                    rename($url1, $url2);
                }

        $data['pasien_id']=$this->input->post('pasienid');
        $data['tipe']=$tipe;
        $data['jenis']=$jenisdokumen;
        $data['subjek']=$namadokumen;
        $data['no_dokumen']=$nodokumen;
        $data['tanggal_kadaluarsa']=date('Y-m-d',strtotime($tggl));
       // $data['url_file']=$url;
        
        $dokumenid=$this->transaksi_dokter3_m->save($data);

        //     $array_input = $this->input->post('upload');
             
            
            
              
        // // save data
        // $result=$this->poliklinik_tindakan_m->getdata2($pk)->result_array();
       
        echo json_encode('sukses');
    }

     public function updatedokumen()
    {
        
        
    
     
        $tggl=$this->input->post('tggl');
        $url=$this->input->post('url');
        $id=$this->input->post('id');


        $url1='';
        $url2='';
        $full_path_new_filename='';
        $temp_filename='';

        $path_dokumen = 'assets/mb/pages/klinik_hd/transaksi_dokter/images/dokumen/'.$this->input->post('pasienid');
        $path_temporary = 'assets/mb/pages/klinik_hd/transaksi_dokter/images/temp/';
            
         //   echo base_url().$path_temporary.$array_input['default_file_name_1'].'<br>'.base_url().$path_dokumen.'/'.$array_input['default_file_name_1'];
            //jika folder pasien untuk dokumen belum ada, buat folder nya pakai nomor member pasien
        if(!$url==''){
            if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
            
           
                if(!$url == "")
                {
                    $temp_filename = $url;               
                 
                    $full_path_new_filename = $path_dokumen."/".$temp_filename;
                    
                     
                  //  $this->model_approval->set_Url_Scan_Surat_Keterangan($full_path_new_filename);
                     
                    $data['url_file']=$full_path_new_filename;
                    

                    $url1 = $path_temporary.$url;
                    $url2 = $path_dokumen.'/'.$url;
                    rename($url1, $url2);
                }

        
        $data['tanggal_kadaluarsa']=date('Y-m-d',strtotime($tggl));
       // $data['url_file']=$url;
        
        $dokumenid=$this->transaksi_dokter3_m->save($data,$id);

       
       
        echo json_encode('sukses');
    }else{
        $data['tanggal_kadaluarsa']=date('Y-m-d',strtotime($tggl));
        $dokumenid=$this->transaksi_dokter3_m->save($data,$id);
         echo json_encode('sukses');
    }
}
     public function listing_pembayaran($pasien_id=null,$cabang_id=null)
    {
        $query='';
        $query2='';
        $x=0;
        $hasil=$this->pembayaran_m->get_id_o_s($pasien_id,$cabang_id)->result_array();
    if(count($hasil) >0){
        foreach($hasil as $row)
        {
            $x++;
            if($row['tipe']==1){
                $query.='(select id,no_transaksi,tanggal,"1" as tipe from tindakan_hd where id="'.$row['tindakan_id'].'")';
            }else if($row['tipe']==2){
                 $query.='(select id,no_transaksi,tanggal,"2" as tipe from tindakan_umum where id="'.$row['tindakan_id'].'")' ;
            }else if($row['tipe']==3){
                $query.='(select id,no_transaksi,tanggal,"3" as tipe from tindakan_mata where id="'.$row['tindakan_id'].'")';
            }else if($row['tipe']==4){
                $query.='(select id,no_transaksi,tanggal,"4" as tipe from tindakan_gigi where id="'.$row['tindakan_id'].'")';
            }else if($row['tipe']==5){
                $query.='(select id,no_transaksi,tanggal,"5" as tipe from tindakan_lab where id="'.$row['tindakan_id'].'")';
            }else{
                 $query.='(select id,no_transaksi,tanggal,"6" as tipe from tindakan_lab where id="'.$row['tindakan_id'].'")';
            }

           if(count($hasil) != $x){
                 $query2=' union all ';
                 $query=$query.$query2;
            }

            
            
            
        }
    }else{
        $query='tidakada';
    }
        $result = $this->pembayaran_m->get_datatable($pasien_id,$cabang_id,$query);
        //die_dump($this->db->last_query());

        // Output
        $output = array(
            'sEcho'                => intval($this->input->post('sEcho', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'aaData'               => array()
        );
    
        $records = $result->records;
        $i=0;
        foreach($records->result_array() as $row)
        {
            
           
            $status='';
            if($row['is_pay']==1){
                 $status='<span class="label label-xs label-danger">Belum Lunas</span></div>';
             }else{
                $status='<span class="label label-xs label-success">Klaim</span></div>';
             }
            
             
            $output['aaData'][] = array(
                '<div class="text-left">'.$row['no_transaksi'].'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center">'.date('d M Y', strtotime($row['tanggal'])).'</div>',
                '<div class="text-right">'.'Rp. ' . number_format($row['rupiah'], 0 , '' , '.' ) . ',-'.'</div>',
                '<div class="text-center">'.$status.'</div>',
                $row['is_pay']
                 );
         $i++;
        }

        echo json_encode($output);
    }

    public function get_data_pasien(){

        $id= $this->input->post('pasienid');

          $startdate='';
            $enddate='';
            $date1='';
            $date2='';
            $now=date("Y-m-d");
            if(date("l")=='Monday' || date("l")=='Senin'){
                $startdate=$now;
                $date1=date_create($startdate);
                date_add($date1,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date1,"Y-m-d");
            }elseif (date("l")=='Tuesday' || date("l")=='Selasa') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-1 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }elseif (date("l")=='Wednesday' || date("l")=='Rabu') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-2 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }elseif (date("l")=='Thursday' || date("l")=='Kamis') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-3 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }elseif (date("l")=='Friday' || date("l")=='Jumat') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-4 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }elseif (date("l")=='Saturday' || date("l")=='Sabtu') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-5 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }else{
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-6 day"));
                $startdate=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("6 day"));
                $enddate=date_format($date2,"Y-m-d");
            }

        $form_data2=$this->transaksi_dokter_m->getdatatindakanfrekuensi($startdate,$enddate,$id)->result_array();

        
        $form_data5=$this->pembayaran_m->getdatapasien2($id)->result_array();
        $form_data6=$this->pembayaran_m->getdatapasienphone2($id)->result_array();
        $form_data7=$this->pembayaran_m->getdatapasienalamat($id)->result_array();
        $form_data8=$this->pembayaran_m->getdatapasienalamat2($id)->result_array();
        $form_data9=$this->pembayaran_m->getdatapasienpenyakit($id)->result_array();

       // $poliklinik = $this->antrian_m->get_antrian($id_poliklinik,$id_dokter)->result_array();
        //die_dump($this->db->last_query());        
   
        $data=array(
                    'form_data2' => $form_data2,
                    'form_data5' => $form_data5,
                    'form_data6' => $form_data6,
                    'form_data7' => $form_data7,
                    'form_data8' => $form_data8,
                    'form_data9' => $form_data9,
                );

        //die_dump($this->db->last_query());
        echo json_encode($data);
    }

     public function listing4($id=null,$flag=null,$pasienid=null)
    {        

        $where='';
        if($flag==1)
        {
            $where='DATE_FORMAT(tanggal_kadaluarsa, "%y-%m-%d") >= DATE_FORMAT(now(), "%y-%m-%d")';
        }else{
            $where='DATE_FORMAT(tanggal_kadaluarsa, "%y-%m-%d") < DATE_FORMAT(now(), "%y-%m-%d")';
        }
        $result = $this->transaksi_dokter4_m->get_datatable($where,$id,$pasienid);

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
            $tipe='';    
            $jenis='';
            $status='';
            $tipe1='';

               
            
          
            if($row['tipe']==1){
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="view" href="'.base_url().$row['url_file'].'" target="_blank" class="btn btn-xs green-haze search-item"><i class="fa fa-search"></i></a>';
            }else{
                $tipe1='<a title="'.translate('View', $this->session->userdata('language')).'"  name="viewpic[]" data-id="'.$row['url_file'].'" data-target="#ajax_notes2" data-toggle="modal"  class="btn btn-xs green-haze search-item"><i class="fa fa-search"></i></a>';
            }

           

           
       
            $output['data'][] = array(
                '<div class="text-left">'.$row['subjek'].'</div>',
                '<div class="text-center">'.$row['no_dokumen'].'</div>',
               
                '<div class="text-center">'.date('d M Y',strtotime($row['tanggal_kadaluarsa'])).'</div>',
                 '<div class="text-center">'.$row['no_dokumen'].'</div>',
                '<div class="text-center">'.$tipe1.'</div>',
                 
            );
            $i++;
        }

        echo json_encode($output);
    }

      public function get_pasien_data(){

        $pasien_id = $this->input->post('pasien_id');
        

        $data = $this->pembayaran_m->get_pasien_data($pasien_id)->result_array();
        //die_dump($this->db->last_query());        
   

        //die_dump($this->db->last_query());
        echo json_encode($data);
    }

    public function check_jadwal(){

        $cabang_id = $this->input->post('cabang_id');
        // $bed = $this->input->post('bed');
        // $tipe = $this->input->post('tipe');
        $date = $this->input->post('date');
        

        $result=$this->pembayaran_m->check_jadwal2($cabang_id,$date)->result_array();
        //die_dump($this->db->last_query());        
   
        // if(count($result) > 0){
        //      echo json_encode('<div class="text-center"><a class="btn btn-xs green-haze selectjadwal" data-item="'.$result[0]['pasien_id'].'" ><i class="fa fa-check"></i></a></div>');
        //  }else{
        //     echo json_encode("-");
        //  }
        echo json_encode($result);
        //die_dump($this->db->last_query());
       
    }

      public function tanggal_jadwal(){

         
           
            $startdate2='';
            $enddate2='';
            $enddate3='';
            $enddate4='';
            $enddate5='';
            $enddate6='';
            $enddate7='';

             $startdate='';
           
             $date1='';
             $date2='';
             $date3='';
             $date4='';
             $date5='';
             $date6='';
             $date7='';

             $enddate22='';
             $enddate33='';
             $enddate44='';
             $enddate55='';
             $enddate66='';
             $enddate77='';
            
             
            $now=date("Y-m-d");
            if(date("l")=='Monday' || date("l")=='Senin'){
                // $startdate=$now;
                // $date1=date_create($startdate);
                $date1=date_create($now);
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
              
            }elseif (date("l")=='Tuesday' || date("l")=='Selasa') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-1 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }elseif (date("l")=='Wednesday' || date("l")=='Rabu') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-2 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                 $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
                
            }elseif (date("l")=='Thursday' || date("l")=='Kamis') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-3 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                 $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }elseif (date("l")=='Friday' || date("l")=='Jumat') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-4 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                 $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }elseif (date("l")=='Saturday' || date("l")=='Sabtu') {
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-5 day"));
               $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }else{
                $date1=date_create($now);
                date_add($date1,date_interval_create_from_date_string("-6 day"));
                $startdate=date_format($date1,"d F Y");
                $startdate2=date_format($date1,"Y-m-d");

                 $date2=date_create($startdate);
                date_add($date2,date_interval_create_from_date_string("1 day"));

                $date3=date_create($startdate);
                date_add($date3,date_interval_create_from_date_string("2 day"));

                $date4=date_create($startdate);
                date_add($date4,date_interval_create_from_date_string("3 day"));

                $date5=date_create($startdate);
                date_add($date5,date_interval_create_from_date_string("4 day"));

                $date6=date_create($startdate);
                date_add($date6,date_interval_create_from_date_string("5 day"));

                $date7=date_create($startdate);
                date_add($date7,date_interval_create_from_date_string("6 day"));

                $enddate2=date_format($date2,"d M Y");
                $enddate3=date_format($date3,"d M Y");
                $enddate4=date_format($date4,"d M Y");
                $enddate5=date_format($date5,"d M Y");
                $enddate6=date_format($date6,"d M Y");
                $enddate7=date_format($date7,"d M Y");

                $enddate22=date_format($date2,"Y-m-d");
                $enddate33=date_format($date3,"Y-m-d");
                $enddate44=date_format($date4,"Y-m-d");
                $enddate55=date_format($date5,"Y-m-d");
                $enddate66=date_format($date6,"Y-m-d");
                $enddate77=date_format($date7,"Y-m-d");
            }

            $data=array($startdate2,$enddate22,$enddate33,$enddate44,$enddate55,$enddate66,$enddate77,$startdate,$enddate2,$enddate3,$enddate4,$enddate5,$enddate6,$enddate7);
 
        echo json_encode($data);
        //die_dump($this->db->last_query());
       
    }

     public function listing_laporan_biaya($id=null,$tgl=null)
    {   
        $tggl=str_replace("%20","-",$tgl);  
        $tggl=date('m-Y',strtotime($tggl));
        $result = $this->laporan_keuangan_m->get_datatable($id,$tggl);
       $result2 = $this->laporan_keuangan_m->get_datatable_total($id,$tggl);
        //die_dump($result);
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
         $records2 = $result2->records;
        $total=0;
        $total2=0;
        $total3=0;
        foreach($records2->result_array() as $row)
        {
             if($row['debit_credit']=='d')
             {
                $total+=$row['rupiah'];
             } 
             if($row['debit_credit']=='c')
             {
                $total2+=$row['rupiah'];
             } 
             $total3+=$row['saldo'];
        };

        $i=0;
        foreach($records->result_array() as $row)
        {
             $d=0;
             $c=0;

             if($row['debit_credit']=='d')
             {
                $d='Rp. ' .number_format($row['rupiah'], 0 , '' , '.' ). ',-';
             }else{
                $d='';
             }

               if($row['debit_credit']=='c')
             {
                $c='Rp. ' .number_format($row['rupiah'], 0 , '' , '.' ). ',-';
             }else{
                 $c='';
             }
            $output['data'][] = array(
               '<div class="text-center">'. date('d',strtotime($row['tanggal'])).'<input type="hidden" id="total_'.$i.'" name="total['.$i.']"></div>',
                
                '<div class="text-left">'.$row['keterangan'].'</div>',
                '<div class="text-right">'.$d.'</div>',
                '<div class="text-right">'.$c.'</div>',
               '<div class="text-right">Rp. ' . number_format($row['saldo'], 0 , '' , '.' ) . ',-</div>',
               $total,
               $total2,
               $total3
              
            );
         $i++;
        }

        echo json_encode($output);
    }

}

/* End of file branch.php */
/* Location: ./application/controllers/branch/branch.php */