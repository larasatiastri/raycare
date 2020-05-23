<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekening_koran extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '4ad3f91294ff53e85ab7065d9c4a70b1';                  // untuk check bit_access

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

        $this->load->model('keuangan/arus_kas_bank/rekening_koran_m');
        $this->load->model('keuangan/arus_kas_bank/keuangan_arus_kas_m');
        $this->load->model('master/bank_m');
        
        $this->load->model('others/kotak_sampah_m');       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/rekening_koran/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Rekening Koran', $this->session->userdata('language')), 
            'header'         => translate('Rekening Koran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/rekening_koran/index',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function add()
    {
        $assets = array();
        $config = 'assets/keuangan/rekening_koran/add';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Tambah Rekening Koran', $this->session->userdata('language')), 
            'header'         => translate('Tambah Rekening Koran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/rekening_koran/add',
            );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function edit($id)
    {
        $assets = array();
        $config = 'assets/keuangan/rekening_koran/edit';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        $data_rekening = $this->keuangan_arus_kas_m->get_by(array('id' => $id), true);
        
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Edit Rekening Koran', $this->session->userdata('language')), 
            'header'         => translate('Edit Rekening Koran', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/rekening_koran/edit',
            'data_rekening'  => object_to_array($data_rekening),
            'pk_value'       => $id
        );
        
        // Load the view
        $this->load->view('_layout', $data);
    }

    public function listing($tgl_awal = null, $tgl_akhir=null,$bank_id)
    {
        if($tgl_awal != null && $tgl_akhir != null)
        {
            $tgl_awal  = date('Y-m-d', strtotime($tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $result = $this->rekening_koran_m->get_datatable($tgl_awal,$tgl_akhir,$bank_id);
        // die_dump($result);

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
        //die_dump($records);
        $count = count($records->result_array());

        // die_dump($count);

        $i=0;
        $total_debit = 0;
        $total_kredit = 0;
        $total_saldo = 0;
        $input_debit = '';
        $input_kredit = '';
        $input_saldo = '';

        foreach($records->result_array() as $row)
        {
            $i++;
            $date = date_create($row['tanggal']);
            $effective_date = date_format($date, 'd F Y');

            $format_id     = '%03d/BANK/'.romanic_number(date('m')).date('/y');
            $nomor  = sprintf($format_id,$i, 3);

            if($row['debit_credit'] == 'C' || $row['debit_credit'] == 'c') {

                $debit =formatrupiah($row['jumlah']);
                $total_debit = $total_debit + $row['jumlah'];

            } else {

                $debit = "";
            }

            if($row['debit_credit'] == 'D' || $row['debit_credit'] == 'd') {

                $credit =formatrupiah($row['jumlah']);
                $total_kredit = $total_kredit + $row['jumlah'];

            } else {

                $credit = '';
            
            }

            $total_saldo = $total_debit - $total_kredit;


            if($i == $count)
            {
            $input_debit  = '<input type="hidden" name="items['.$i.'][rupiah_debit]" id="grandtotal_debit" class="form-control input-sm" value="'.$total_debit.'">';
            $input_kredit = '<input type="hidden" name="items['.$i.'][rupiah_credit]" id="grandtotal_kredit" class="form-control input-sm" value="'.$total_kredit.'">';
            $input_saldo  = '<input type="hidden" name="items['.$i.'][saldo]" id="grandtotal_saldo" class="form-control input-sm" value="'.$total_saldo.'">';
            }

            $keterangan = explode(';', $row['keterangan']);
            $keterangan = implode('<br/>', $keterangan);

            $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/rekening_koran/edit/'.$row['keuangan_arus_kas_id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>';

            if($row['tipe'] == 0){
                $action = '<a title="'.translate('Edit', $this->session->userdata('language')).'" href="'.base_url().'keuangan/rekening_koran/edit/'.$row['keuangan_arus_kas_id'].'" class="btn btn-xs blue-chambray"><i class="fa fa-edit"></i></a>
                <a title="'.translate('Delete', $this->session->userdata('language')).'" data-confirm="'.translate('Anda yakin akan menghapus data rekening koran ini?', $this->session->userdata('language')).'" name="delete[]" data-action="delete" data-id="'.$row['id'].'" data-keuangan_arus_kas_id="'.$row['keuangan_arus_kas_id'].'" class="btn red"><i class="fa fa-times"></i> </a>';    
            }
            

            $output['aaData'][] = array(
                '<div class="text-center">'.date('d/m/y', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nomor'].'</div>',
                '<div class="text-left">'.$row['nomor_cek'].'</div>',
                '<div class="text-left">'.$keterangan.'</div>',
                '<div class="text-right">'.$debit.$input_debit.'</div>',
                '<div class="text-right">'.$credit.$input_kredit.'</div>',
                '<div class="text-right">'.formatrupiah($row['saldo']).$input_saldo.'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-center inline-button-table">'.$action.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }

    public function get_terbilang()
    {
        if($this->input->is_ajax_request()){
            $nominal = $this->input->post('nominal');

            $response = new stdClass;

            $terbilang = terbilang($nominal);

            $response->terbilang = '#'.$terbilang.' Rupiah #';

            die(json_encode($response));
        }
    }


    public function save()
    {
       $array_input = $this->input->post();
       $command = $array_input['command'];

        if($command === 'add'){

            $tanggal = date('Y-m-d', strtotime($array_input['tanggal']));

            $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($tanggal,$array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($tanggal,$array_input['bank_id'])->result_array();

            $saldo_before_bank = 0;
            if(count($last_saldo_bank) != 0){
                $saldo_before_bank = $last_saldo_bank[0]['saldo'];
            }

            $saldo = $array_input['jumlah'];
            $debit_credit = $array_input['debit_credit'];

            if($debit_credit === 'd'){
                $saldo = $saldo_before_bank + $array_input['jumlah'];
            }if($debit_credit === 'c'){
                $saldo = $saldo_before_bank - $array_input['jumlah'];
            }


            $data_arus_kas_bank = array(
                'tanggal'      => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'tipe'         => 0,
                'keterangan'   => $array_input['keterangan'],
                'user_id'      => $this->session->userdata('user_id'),
                'bank_id'      => $array_input['bank_id'],
                'debit_credit' => $debit_credit,
                'rupiah'       => $array_input['jumlah'],
                'saldo'        => $saldo,
                'status'       => 1
            );

            $keuangan_arus_kas_id = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);
            

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after_bank) {
                    $saldo_after = $array_input['jumlah'];
                    $debit_credit = $array_input['debit_credit'];

                    if($debit_credit === 'd'){
                        $saldo_after = $after_bank['saldo'] + $array_input['jumlah'];
                    }if($debit_credit === 'c'){
                        $saldo_after = $after_bank['saldo'] - $array_input['jumlah'];
                    }

                    $data_arus_kas_bank_after = array(
                        'saldo'        => $saldo_after,
                    );

                    $keuangan_arus_kas_edit = $this->keuangan_arus_kas_m->save($data_arus_kas_bank_after,$after_bank['id']);
                }
            }


            $last_id       = $this->rekening_koran_m->get_max_id_rekening_koran()->result_array();
            $last_id       = intval($last_id[0]['max_id'])+1;
            
            $format_id     = 'RK-'.date('m').'-'.date('Y').'-%04d';
            $id_rek_koran         = sprintf($format_id, $last_id, 4);

            $data = array(
                'id'                   => $id_rek_koran,
                'nomor'            => $array_input['nomor'],
                'nomor_cek'            => $array_input['nomor_cek'],
                'tanggal'              => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'bank_id'              => $array_input['bank_id'],
                'jumlah'               => $array_input['jumlah'],
                'keterangan'           => $array_input['keterangan'],
                'keuangan_arus_kas_id' => $keuangan_arus_kas_id,
                'is_active'            => 1,
                'created_by'           => $this->session->userdata('user_id'),
                'created_date'         => date('Y-m-d H:i:s')
            );

            $rekening_koran = $this->rekening_koran_m->add_data($data);

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data rekening koran berhasil ditambahkan", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
        }if($command === 'edit'){
            $id = $array_input['rekening_koran_id'];
            $kas_id = $array_input['id'];


            $tanggal_kas = date('Y-m-d', strtotime($array_input['tanggal_kas']));

            $last_saldo_bank_kas = $this->keuangan_arus_kas_m->get_saldo_before($tanggal_kas,$array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($tanggal_kas,$array_input['bank_id'])->result_array();

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after_bank_kas) {

                    $data_arus_kas_bank_after = array(
                        'saldo'        => $after_bank_kas['saldo'] - $array_input['jumlah_kas'],
                    );

                    $keuangan_arus_kas_edit = $this->keuangan_arus_kas_m->save($data_arus_kas_bank_after,$after_bank_kas['id']);
                }
            }

            $delete_kas = $this->keuangan_arus_kas_m->delete($kas_id);


            $tanggal = date('Y-m-d', strtotime($array_input['tanggal']));

            $last_saldo_bank = $this->keuangan_arus_kas_m->get_saldo_before($tanggal,$array_input['bank_id'])->result_array();
            $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($tanggal,$array_input['bank_id'])->result_array();

            $saldo_before_bank = 0;
            if(count($last_saldo_bank) != 0){
                $saldo_before_bank = $last_saldo_bank[0]['saldo'];
            }

            $saldo = $array_input['jumlah'];
            $debit_credit = $array_input['debit_credit'];

            if($debit_credit === 'd'){
                $saldo = $saldo_before_bank + $array_input['jumlah'];
            }if($debit_credit === 'c'){
                $saldo = $saldo_before_bank - $array_input['jumlah'];
            }


            $data_arus_kas_bank = array(
                'tanggal'      => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'tipe'         => 0,
                'keterangan'   => $array_input['keterangan'],
                'user_id'      => $this->session->userdata('user_id'),
                'bank_id'      => $array_input['bank_id'],
                'debit_credit' => $debit_credit,
                'rupiah'       => $array_input['jumlah'],
                'saldo'        => $saldo,
                'status'       => 1
            );

            $keuangan_arus_kas_id = $this->keuangan_arus_kas_m->save($data_arus_kas_bank);
            

            if(count($after_saldo_bank) != 0){
                foreach ($after_saldo_bank as $after_bank) {
                    $saldo_after = $array_input['jumlah'];
                    $debit_credit = $array_input['debit_credit'];

                    if($debit_credit === 'd'){
                        $saldo_after = $after_bank['saldo'] + $array_input['jumlah'];
                    }if($debit_credit === 'c'){
                        $saldo_after = $after_bank['saldo'] - $array_input['jumlah'];
                    }

                    $data_arus_kas_bank_after = array(
                        'saldo'        => $saldo_after,
                    );

                    $keuangan_arus_kas_edit = $this->keuangan_arus_kas_m->save($data_arus_kas_bank_after,$after_bank['id']);
                }
            }

            $data = array(
                'nomor'            => $array_input['nomor'],
                'nomor_cek'            => $array_input['nomor_cek'],
                'tanggal'              => date('Y-m-d H:i:s', strtotime($array_input['tanggal'])),
                'bank_id'              => $array_input['bank_id'],
                'jumlah'               => $array_input['jumlah'],
                'keterangan'           => $array_input['keterangan'],
                'keuangan_arus_kas_id' => $keuangan_arus_kas_id,
                'modified_by'           => $this->session->userdata('user_id'),
                'modified_date'         => date('Y-m-d H:i:s')
            );

            $rekening_koran = $this->rekening_koran_m->edit_data($data,$id);

            $flashdata = array(
                "type"     => "success",
                "msg"      => translate("Data rekening koran berhasil diubah", $this->session->userdata("language")),
                "msgTitle" => translate("Success", $this->session->userdata("language"))    
            );
            $this->session->set_flashdata($flashdata);
        }

       redirect('keuangan/rekening_koran');

    }

    public function delete($id,$keuangan_arus_kas_id,$bank_id)
    {
        $data_rekening = array(
            'is_active' => 0
        );
        $delete_rekening = $this->rekening_koran_m->edit_data($data_rekening, $id);

        $data_kas = $this->keuangan_arus_kas_m->get_by(array('id' => $keuangan_arus_kas_id), true);

        $tanggal_kas = date('Y-m-d', strtotime($data_kas->tanggal));

        $after_saldo_bank = $this->keuangan_arus_kas_m->get_after_after($tanggal_kas,$bank_id)->result_array();
        // die(dump($this->db->last_query()));

        if(count($after_saldo_bank) != 0){
            foreach ($after_saldo_bank as $after_bank_kas) {

                $data_arus_kas_bank_after = array(
                    'saldo'        => $after_bank_kas['saldo'] - $data_kas->rupiah,
                );

                $keuangan_arus_kas_edit = $this->keuangan_arus_kas_m->save($data_arus_kas_bank_after,$after_bank_kas['id']);
            }
        }

        $delete_kas = $this->keuangan_arus_kas_m->delete($keuangan_arus_kas_id);

        $flashdata = array(
            "type"     => "error",
            "msg"      => translate("Data rekening koran berhasil dihapus", $this->session->userdata("language")),
            "msgTitle" => translate("Success", $this->session->userdata("language"))    
        );
        $this->session->set_flashdata($flashdata);
        redirect('keuangan/rekening_koran');

    }

}

/* End of file arus_kas_bank.php */
/* Location: ./application/controllers/keuangan/arus_kas_bank.php */