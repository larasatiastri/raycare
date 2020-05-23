<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_level_m extends MY_Model {

	protected $_table      = 'user_level';
	protected $_timestamps = true;
	protected $_order_by   = 'name';

	private $_fillable = array(
		'nama',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'user_level.id'            => 'id', 
		'user_level.nama'          => 'nama', 
		'user_level.dashboard_url' => 'dashboard_url',
		// 'user_level.is_active'        => 'is_active'
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active', 1);

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

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_active()
	{

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_data($id)
	{	
		$this->db->select('user_level.nama,
							user_level_persetujuan.id,
							user_level_persetujuan.user_level_id,
							user_level_persetujuan.level_order,
							user_level_persetujuan.user_level_menyetujui_id
						');
		$this->db->join('user_level_persetujuan',$this->_table.'.id = user_level_persetujuan.user_level_id','left');
		$this->db->where($this->_table.'.id',$id);
		return $this->db->get($this->_table);
	}

	public function get_nama($user_level_id)
	{

		$sql = "SELECT
				user_level.id,
				user_level.nama,
				persetujuan_permintaan_pembelian.order_permintaan_pembelian_id,
				persetujuan_permintaan_pembelian.tipe_permintaan,
				persetujuan_permintaan_pembelian.user_level_id
				FROM
				user_level
				LEFT JOIN persetujuan_permintaan_pembelian ON user_level.id = persetujuan_permintaan_pembelian.user_level_id
				WHERE
				persetujuan_permintaan_pembelian.user_level_id = $user_level_id";
		return $this->db->query($sql);

	}

	public function get_data_is_active()
    {
        $this->db->where("is_active", 1);
        return $this->db->get($this->_table);
    }
}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */