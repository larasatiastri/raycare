<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arus_kas_bank extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '8b734fa897caa79c4794d9068017f1ee';                  // untuk check bit_access

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

        $this->load->model('keuangan/arus_kas_bank/keuangan_arus_kas_m');
        $this->load->model('master/bank_m');
        
        $this->load->model('others/kotak_sampah_m');       
    }
    
    public function index()
    {
        $assets = array();
        $config = 'assets/keuangan/arus_kas_bank/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Arus Kas kasir', $this->session->userdata('language')), 
            'header'         => translate('Arus Kas kasir', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'keuangan/arus_kas_bank/index',
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

        $result = $this->keuangan_arus_kas_m->get_datatable($tgl_awal,$tgl_akhir,$bank_id);
        // die_dump($result);

        // Output
        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records = $result->records;
       // die_dump($records);
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

            if($row['debit_credit'] == 'C' || $row['debit_credit'] == 'c') {

                $debit =formatrupiah($row['rupiah']);
                $total_debit = $total_debit + $row['rupiah'];

            } else {

                $debit = "";
            }

            if($row['debit_credit'] == 'D' || $row['debit_credit'] == 'd') {

                $credit =formatrupiah($row['rupiah']);
                $total_kredit = $total_kredit + $row['rupiah'];

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

            $output['aaData'][] = array(
                '<div class="text-center">'.$row['id'].'</div>',
                '<div class="text-center">'.date('d M', strtotime($effective_date)).'</div>',
                '<div class="text-left">'.$row['nama'].'</div>',
                '<div class="text-left">'.$keterangan.'</div>',
                '<div class="text-right">'.$debit.$input_debit.'</div>',
                '<div class="text-right">'.$credit.$input_kredit.'</div>',
                '<div class="text-right">'.formatrupiah($row['saldo']).$input_saldo.'</div>',
                
            );
        }
                    // <input class="text-right form-control" name="items['.$i.'][saldo]" id="saldo'.$i.'" value="'.number_format($row['saldo'], 0,',-','.').'">

        echo json_encode($output);

    }


}

/* End of file arus_kas_bank.php */
/* Location: ./application/controllers/keuangan/arus_kas_bank.php */