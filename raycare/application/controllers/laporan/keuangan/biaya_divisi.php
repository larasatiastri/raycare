<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biaya_divisi extends MY_Controller {

    // protected $menu_parent_id = 2;           // deprecated, pake $menu_tree
    protected $menu_id = '915437869d5a086ec7200a9f0ee1ef1a';                  // untuk check bit_access
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

        $this->load->model('keuangan/permintaan_biaya/permintaan_biaya_m');
        $this->load->model('master/divisi_m');
    }
    public function index()
    {
        $assets = array();
        $config = 'assets/laporan/keuangan/biaya_divisi/index';
        $this->config->load($config, true);
        $assets = $this->config->item('assets', $config);
        
        // die(dump( $assets['css'] ));
        $data = array(
            'title'          => config_item('site_name').' | '.translate('Grafik Laporan Biaya Per Divisi', $this->session->userdata('language')), 
            'header'         => translate('Grafik Laporan Biaya Per Divisi', $this->session->userdata('language')), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => true,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'laporan/keuangan/biaya_divisi/index',
        );
    
        // Load the view
        $this->load->view('_layout', $data);
    }

    function get_all_divisi($start,$divisi_id,$tipe)
    {
        $start = str_replace('%20', '-' , $start);

        if($tipe == 1){
            $param_year  = date('Y', strtotime($start));
            $param_month = date('m', strtotime($start)); 
        }elseif($tipe == 2){
            $param_year  = date('Y', strtotime($start." -1 months"));
            $param_month = date('m', strtotime($start." -1 months"));
        }
        
        $response = new stdClass;
        $response->success = false;

        $sql = "SELECT divisi.kode, divisi.nama, sum(permintaan_biaya.nominal_setujui) as total FROM `permintaan_biaya` 
                JOIN user ON permintaan_biaya.diminta_oleh_id = `user`.id
                JOIN user_level ON `user`.user_level_id = user_level.id 
                LEFT JOIN divisi ON user_level.divisi_id = divisi.id
                WHERE month(permintaan_biaya.tanggal) = ? 
                    AND year(permintaan_biaya.tanggal) = ? 
                    AND divisi.id = ?
                    AND permintaan_biaya.`status` = 5 
                GROUP BY divisi.id";

        $query = $this->db->query(
            $sql,
            array( $param_month,$param_year,$divisi_id)
        );
        
        $response->success = true;
        $response->rows = $query->result();
        

        die(json_encode($response));
    }

    public function listing_biaya_per_tanggal($start,$divisi_id)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        $result = $this->permintaan_biaya_m->get_datatable_per_tanggal($param_month,$param_year,$divisi_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records  = $result->records;
        // die(dump($records));
        $i = 1;
        foreach($records->result_array() as $row)
        { 
            $data_user = $this->permintaan_biaya_m->get_user_name(date('Y-m-d', strtotime($row['tanggal'])),$divisi_id)->result_array();
            $user = '';
            if(count($data_user) != 0){
                foreach ($data_user as $row_user) {
                    $user .= $row_user['nama'].' ,';
                }
            }
            $output['data'][] = array(
               '<div class="text-center">'.$i.'</div>',
                '<div class="text-center">'.date('d M', strtotime($row['tanggal'])).'</div>',
                '<div class="text-left">'.rtrim($user,' ,').'</div>', 
                '<div class="text-left">'.formatrupiah($row['nominal']).'</div>', 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function listing_biaya_per_user($start,$divisi_id)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        $result = $this->permintaan_biaya_m->get_datatable_per_user($param_month,$param_year,$divisi_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records,
            'iTotalDisplayRecords' => $result->total_display_records,
            'data'                 => array()
        );
    
        $records  = $result->records;
        // die(dump($records));
        $i = 1;
        foreach($records->result_array() as $row)
        { 
            
            $output['data'][] = array(
               '<div class="text-center">'.$i.'</div>',
                '<div class="text-left">'.$row['nama_dibuat_oleh'].'</div>', 
                '<div class="text-left">'.formatrupiah($row['nominal']).'</div>', 
            );

            $i++;
        }

        echo json_encode($output);
    }

    public function listing_biaya_per_kategori($start,$divisi_id)
    {
        $start = str_replace('%20', '-' , $start);

        $param_year = date('Y', strtotime($start));
        $param_month = date('m', strtotime($start));

        $result = $this->permintaan_biaya_m->get_datatable_per_biaya_kasbon($param_month,$param_year,$divisi_id);
        $result2 = $this->permintaan_biaya_m->get_datatable_per_biaya_reimburse($param_month,$param_year,$divisi_id);

        $output = array(
            'draw'                 => intval($this->input->post('draw', true)),
            'iTotalRecords'        => $result->total_records+$result2->total_records,
            'iTotalDisplayRecords' => $result->total_display_records+$result2->total_display_records,
            'data'                 => array()
        );
    
        $records  = $result->records;
        $records2  = $result2->records;
        // die(dump($records2));
        $i = 1;
        foreach($records->result_array() as $row)
        { 
            $data_user = $this->permintaan_biaya_m->get_user_name_biaya_kas($row['biaya_id'],$param_month,$param_year,$divisi_id)->result_array();
            $user = '';
            if(count($data_user) != 0){
                foreach ($data_user as $row_user) {
                    $user .= $row_user['nama'].' ,';
                }
            }
            $output['data'][] = array(
               '<div class="text-center">'.$i.'</div>',
                '<div class="text-left">'.$row['nama_biaya'].'</div>',
                '<div class="text-left">'.rtrim($user,' ,').'</div>', 
                '<div class="text-left">'.formatrupiah($row2['nominal']).'</div>', 
            );

            $i++;
        } 

        foreach($records2->result_array() as $row2)
        { 
            $data_user = $this->permintaan_biaya_m->get_user_name_biaya_reimburse($row2['biaya_id'],$param_month,$param_year,$divisi_id,$divisi_id)->result_array();
            // die(dump($this->db->last_query()));
            $user = '';
            if(count($data_user) != 0){
                foreach ($data_user as $row_user) {
                    $user .= $row_user['nama'].' ,';
                }
            }
            $output['data'][] = array(
               '<div class="text-center">'.$i.'</div>',
                '<div class="text-left">'.$row2['nama_biaya'].'</div>',
                '<div class="text-left">'.rtrim($user,' ,').'</div>', 
                '<div class="text-left">'.formatrupiah($row2['nominal']).'</div>', 
            );

            $i++;
        }

        echo json_encode($output);
    }
}

/* End of file spesialis.php */
/* Location: ./application/controllers/spesialis/spesialis.php */