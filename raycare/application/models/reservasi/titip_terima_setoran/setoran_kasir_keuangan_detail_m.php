<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setoran_kasir_keuangan_detail_m extends MY_Model
{
	protected $_table        = 'setoran_kasir_keuangan_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'setoran_kasir_keuangan_detail.id'         				=> 'id', 
		'setoran_kasir_keuangan_detail.setoran_keuangan_kasir_id'  => 'setoran_keuangan_kasir_id',
		'setoran_kasir_keuangan_detail_level.detail_id'  			=> 'detail_id',
		'setoran_kasir_keuangan_detail_level.tipe_detail'  		=> 'tipe_detail',
		'setoran_kasir_keuangan_detail.tanggal' 					=> 'tanggal', 
		'setoran_kasir_keuangan_detail_level.rupiah'   			=> 'rupiah',
		// 'cabang.nama'        => 'nama_cabang',
		// 'setoran_kasir_keuangan_detail.is_active'    => 'active'
	);

	public function get_datatable()
	{	
		$join1 = array("setoran_kasir_keuangan_detail_level", $this->_table . '.setoran_kasir_keuangan_detail_level_id = setoran_kasir_keuangan_detail_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);

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

	public function get_datatable_staff()
	{
		$join1 = array("setoran_kasir_keuangan_detail_level", $this->_table . '.setoran_kasir_keuangan_detail_level_id = setoran_kasir_keuangan_detail_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('setoran_kasir_keuangan_detail_level_id', 12);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('setoran_kasir_keuangan_detail_level_id', 12);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('setoran_kasir_keuangan_detail_level_id', 12);

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
		$params = $this->datatable_param($this->datatable_columns_setoran_kasir_keuangan_detail);

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
		foreach ($this->datatable_columns_setoran_kasir_keuangan_detail as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_setoran_kasir_keuangan_detail;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_pilih_setoran_kasir_keuangan_detail($user_id)
	{
		$datatable_columns_pilih_setoran_kasir_keuangan_detail = array(
		//column di table  => alias
		'setoran_kasir_keuangan_detail.id'				=> 'id',
		'setoran_kasir_keuangan_detail.tanggal'		=> 'tanggal',
		'setoran_kasir_keuangan_detail.user_id'		=> 'user_id',
		'setoran_kasir_keuangan_detail.total_setor'	=> 'total_setor',
		'setoran_kasir_keuangan_detail.status'			=> 'status',
		'setoran_kasir_keuangan_detail.keterangan'		=> 'keterangan',
		'user_level.nama'						=> 'nama_user_level',
		'a.id'									=> 'id_user',
		'a.nama'								=> 'nama_user_created',
		'b.nama'								=> 'nama_user',
		'b.is_active'							=> 'active',
		'titip_setoran.subjek'					=> 'subjek',

		);

		$join1 = array('user a', $this->_table.'.created_by = a.id','left');
		$join2 = array('user b', $this->_table.'.user_id = b.id','left');
		$join3 = array("user_level", 'b.user_level_id = user_level.id', 'left');
		$join4 = array('titip_setoran', $this->_table.'.user_id = titip_setoran.id','left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_setoran_kasir_keuangan_detail);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('b.is_active', 1);
		$this->db->where($this->_table.'.user_id', $user_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('b.is_active', 1);
		$this->db->where($this->_table.'.user_id', $user_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('b.is_active', 1);
		$this->db->where($this->_table.'.user_id', $user_id);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_setoran_kasir_keuangan_detail as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_setoran_kasir_keuangan_detail;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pilih_gudang_orang($cabang_id)
	{
		$datatable_columns_pilih_setoran_kasir_keuangan_detail = array(
		//column di table  => alias
		'setoran_kasir_keuangan_detail.id'				=> 'id',
		'setoran_kasir_keuangan_detail.setoran_kasir_keuangan_detail_level_id'	=> 'setoran_kasir_keuangan_detail_level_id',
		'setoran_kasir_keuangan_detail.cabang_id'		=> 'cabang_id',
		'setoran_kasir_keuangan_detail.nama'				=> 'nama',
		'setoran_kasir_keuangan_detail.setoran_kasir_keuangan_detailname'			=> 'setoran_kasir_keuangan_detailname',
		'setoran_kasir_keuangan_detail.is_active'		=> 'active',
		'setoran_kasir_keuangan_detail_level.nama'		=> 'nama_setoran_kasir_keuangan_detail_level',
		'cabang.id'				=> 'cabang_id',
		'gudang_orang.id'				=> 'setoran_kasir_keuangan_detail_id',
		'gudang_orang.nama'				=> 'nama_gudang_orang',
		);

		$join1 = array("setoran_kasir_keuangan_detail_level", $this->_table . '.setoran_kasir_keuangan_detail_level_id = setoran_kasir_keuangan_detail_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');
		$join3 = array("gudang_orang", 'setoran_kasir_keuangan_detail.id = gudang_orang.id', 'inner');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_setoran_kasir_keuangan_detail);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_setoran_kasir_keuangan_detail as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_setoran_kasir_keuangan_detail;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable_add()
	{
		return $this->_fillable_add;
	}
	/**
	 * [fillable_edit description]
	 * @return [type] [description]
	 */
	public function fillable_edit()
	{
		return $this->_fillable_edit;
	}
	/**
	 * [fillable_changepass description]
	 * @return [type] [description]
	 */
	public function fillable_changepass()
	{
		return $this->_fillable_changepass;
	}

	function validate_password($id, $password)
	{
		$where = array(
			"id"	=> $id,
			"password"	=> $this->hash($password)
		);
		
		$setoran_kasir_keuangan_detail = $this->get_by($where,true);
		
		if(is_object($setoran_kasir_keuangan_detail) && $setoran_kasir_keuangan_detail->id) return true;
		
		return false;
	}

	public function get_data_active()
	{
		$this->db->where('is_active',1);
		$this->db->order_by('id','asc');
		return $this->db->get($this->_table);
	}

	public function get_data_dokter()
	{
		$this->db->where('setoran_kasir_keuangan_detail_level_id', 10);
		return $this->db->get($this->_table);
	}
}

/* End of file setoran_kasir_keuangan_detail.php */
/* Location: ./application/models/setoran_kasir_keuangan_detail.php */