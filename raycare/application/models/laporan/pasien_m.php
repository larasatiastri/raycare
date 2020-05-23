<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien_m extends MY_Model {

	protected $_table      = 'pasien';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pasien.id'             => 'id',
		'pasien.nama'           => 'nama_pasien',
		'pasien.tanggal_daftar' => 'tanggal_daftar',
		'pasien.faskes_tk_1' => 'faskes_tk_1',
		'master_faskes.nama_faskes' => 'nama_faskes',
		'user.nama' => 'nama_marketing',
	);

	protected $datatable_columns_data = array(
		//column di table  => alias
		'pasien.id'                     => 'id',
		'pasien.nama'                   => 'nama_pasien',
		'pasien_alamat.alamat'          => 'alamat',
		'inf_lokasi.nama_kelurahan'     => 'nama_kelurahan',
		'inf_lokasi.nama_kecamatan'     => 'nama_kecamatan',
		'inf_lokasi.nama_kabupatenkota' => 'nama_kabupatenkota',
		'pasien.tempat_lahir'           => 'tempat_lahir',
		'pasien.tanggal_lahir'          => 'tanggal_lahir',
		'pasien.tanggal_daftar'         => 'tanggal_daftar',
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_report($start_month,$start_year)
	{
		$level_id = $this->session->userdata('level_id');
		$user_id = $this->session->userdata('user_id');

		$join1 = array('master_faskes',$this->_table.'.kode_faskes = master_faskes.kode_faskes','left');
		$join2 = array('user',$this->_table.'.marketing_id = user.id','left');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pasien.id';
		$params['sort_dir'] = 'asc';


		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('MONTH(pasien.tanggal_daftar)', $start_month);
		$this->db->where('YEAR(pasien.tanggal_daftar)', $start_year);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		$this->db->where('pasien.is_active', 1);
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('MONTH(pasien.tanggal_daftar)', $start_month);
		$this->db->where('YEAR(pasien.tanggal_daftar)', $start_year);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		$this->db->where('pasien.is_active', 1);
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('MONTH(pasien.tanggal_daftar)', $start_month);
		$this->db->where('YEAR(pasien.tanggal_daftar)', $start_year);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		$this->db->where('pasien.is_active', 1);
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_data_bulan($start_month,$start_year,$penjamin_id)
	{
		$level_id = $this->session->userdata('level_id');
		$user_id = $this->session->userdata('user_id');
		
		$join1 = array('tindakan_hd_history',$this->_table.'.id = tindakan_hd_history.pasien_id','left');
		$join2 = array('pasien_alamat',$this->_table.'.id = pasien_alamat.pasien_id','left');
		$join3 = array('inf_lokasi','pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join_tables = array($join1,$join2,$join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_data);
		$params['sort_by'] = 'pasien.id';
		$params['sort_dir'] = 'asc';


		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pasien_alamat.is_primary', 1);
		$this->db->where('MONTH(tindakan_hd_history.tanggal)', $start_month);
		$this->db->where('YEAR(tindakan_hd_history.tanggal)', $start_year);
		$this->db->where('tindakan_hd_history.cabang_id', $this->session->userdata('cabang_id'));
		if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where('tindakan_hd_history.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where('tindakan_hd_history.penjamin_id !=', 1);
		}if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by('tindakan_hd_history.pasien_id');
		// dapatkan total row count;

		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pasien_alamat.is_primary', 1);
		$this->db->where('MONTH(tindakan_hd_history.tanggal)', $start_month);
		$this->db->where('YEAR(tindakan_hd_history.tanggal)', $start_year);
		$this->db->where('tindakan_hd_history.cabang_id', $this->session->userdata('cabang_id'));
		if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where('tindakan_hd_history.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where('tindakan_hd_history.penjamin_id !=', 1);
		}if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by('tindakan_hd_history.pasien_id');

		
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pasien_alamat.is_primary', 1);
		$this->db->where('MONTH(tindakan_hd_history.tanggal)', $start_month);
		$this->db->where('YEAR(tindakan_hd_history.tanggal)', $start_year);
		$this->db->where('tindakan_hd_history.cabang_id', $this->session->userdata('cabang_id'));
		if($penjamin_id != 0 && $penjamin_id == 1){
			$this->db->where('tindakan_hd_history.penjamin_id', $penjamin_id);
		}if($penjamin_id != 0 && $penjamin_id != 1){
			$this->db->where('tindakan_hd_history.penjamin_id !=', 1);
		}if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by('tindakan_hd_history.pasien_id');


		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_data as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_data;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}



	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function csv($start_month,$start_year,$penjamin_id)
    {
        $wheres = '';
        if($penjamin_id != 0 && $penjamin_id == 1){
            $wheres = ' AND tindakan_hd_history.penjamin_id = 1';
        }if($penjamin_id != 0 && $penjamin_id != 1){
            $wheres = ' AND tindakan_hd_history.penjamin_id != 1';
        }

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $query = $this->db->query("SELECT 'Nama Pasien','Alamat','Tempat/Tgl Lahir','Tanggal Daftar' UNION ALL (SELECT pasien.nama as nama, CONCAT(pasien_alamat.alamat,' ',inf_lokasi.nama_kelurahan,' ',inf_lokasi.nama_kecamatan,' ',inf_lokasi.nama_kabupatenkota) as alamat, CONCAT(pasien.tempat_lahir,'/',pasien.tanggal_lahir) as ttl, pasien.tanggal_daftar FROM pasien LEFT JOIN tindakan_hd_history ON (pasien.id = tindakan_hd_history.pasien_id) LEFT JOIN pasien_alamat ON (pasien.id = pasien_alamat.pasien_id) LEFT JOIN inf_lokasi ON (pasien_alamat.kode_lokasi = inf_lokasi.lokasi_kode ) WHERE pasien_alamat.is_primary = 1 AND MONTH(tindakan_hd_history.tanggal) = '".$start_month."' AND YEAR(tindakan_hd_history.tanggal) = '".$start_year."' ".$wheres." GROUP BY tindakan_hd_history.pasien_id ORDER BY pasien.id ASC)");
        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Data Pasien Tindakan '.$start_month.'-'.$start_year.'.csv', $data);
    }

}

/* End of file pasien_m.php */
/* Location: ./application/models/pasien_m.php */