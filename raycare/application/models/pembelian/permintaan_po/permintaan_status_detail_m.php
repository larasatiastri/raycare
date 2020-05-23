<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_status_detail_m extends MY_Model {

	protected $_table        = 'permintaan_status_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{	
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="permintaan_status_detail.id";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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
		// die(dump($result->records));
		return $result; 
	}

	public function get_max_id_permintaan_detail()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `permintaan_status_detail` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data($permintaan_id, $tipe)
	{
		$SQL = "SELECT user_level.nama as nama_level, user.inisial, DATE_FORMAT(permintaan_status_detail.tanggal_proses,'%d %b %Y, %H:%i') as tanggal_proses, permintaan_status_detail.waktu_tunggu,permintaan_status_detail.status, permintaan_status_detail.keterangan, permintaan_status_detail.user_level_id
				FROM permintaan_status_detail LEFT JOIN user_level ON permintaan_status_detail.user_level_id = user_level.id
				LEFT JOIN user ON permintaan_status_detail.user_proses = user.id WHERE permintaan_status_detail.permintaan_status_id = '$permintaan_id' AND tipe_pengajuan = $tipe ORDER BY `order` ASC";

		return $this->db->query($SQL);
	}
	public function get_data_detail($wheres, $tipe)
	{
		$this->db->where($wheres);
		if($tipe == 1){
			$this->db->order_by('`order`','asc');
		}if($tipe == 2){
			$this->db->order_by('`order`','desc');
		}
		$this->db->limit(1);

		return $this->db->get($this->_table);
	}

}

/* End of file permintaan_status_detail.php */
/* Location: ./application/models/keuangan/permintaan_status_detail.php */