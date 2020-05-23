<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer_item_detail_m extends MY_Model {

	protected $_table        = 'transfer_item_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'transfer_item_detail.id'         		=> 'id', 
		'transfer_item_detail.transfer_item_id'  => 'transfer_item_id',
		'transfer_item_detail.item_id'   	=> 'item_id',
		'transfer_item_detail.item_satuan_id'   		=> 'item_satuan_id',
		'transfer_item_detail.jumlah'   		=> 'jumlah',
		'transfer_item_detail.created_by'   	=> 'created_by',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_kirim($gudang_id)
	{	

		$join1 = array('user', $this->_table.'.created_by = user.id', 'left');
		$join2 = array('gudang dari', $this->_table.'.dari_gudang_id = dari.id', 'left');
		$join3 = array('gudang ke', 'ke.id = transfer_item.ke_gudang_id', 'left');

		$join_tables = array($join1, $join2, $join3);

		if($gudang_id == 1) {

			$gudang = 1;
		
		} elseif($gudang_id == 2) {

			$gudang = 2;

		} elseif($gudang_id == 3) {

			$gudang = 3;

		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($gudang_id == null) 
		{
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
		} else {
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
			$this->db->where('transfer_item.`dari_gudang_id`',$gudang);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($gudang_id == null) 
		{
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
		} else {
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
			$this->db->where('transfer_item.`dari_gudang_id`',$gudang);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($gudang_id == null) 
		{
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
		} else {
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
			$this->db->where('transfer_item.`dari_gudang_id`',$gudang);
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
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_terima($gudang_id)
	{	

		$join1 = array('user', $this->_table.'.created_by = user.id', 'left');
		$join2 = array('gudang dari', $this->_table.'.dari_gudang_id = dari.id', 'left');
		$join3 = array('gudang ke', 'ke.id = transfer_item.ke_gudang_id', 'left');

		$join_tables = array($join1, $join2, $join3);

		if($gudang_id == 1) {

			$gudang = 1;
		
		} elseif($gudang_id == 2) {

			$gudang = 2;

		} elseif($gudang_id == 3) {

			$gudang = 3;

		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($gudang_id == null) 
		{
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
		} else {
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
			$this->db->where('transfer_item.`ke_gudang_id`',$gudang);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($gudang_id == null) 
		{
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
		} else {
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
			$this->db->where('transfer_item.`ke_gudang_id`',$gudang);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($gudang_id == null) 
		{
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
		} else {
			$this->db->where('transfer_item.is_active',1);
			$this->db->where('transfer_item.`status`',1);
			$this->db->where('transfer_item.`ke_gudang_id`',$gudang);
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
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_history($gudang_id)
	{	

		$join1 = array('user', $this->_table.'.created_by = user.id', 'left');
		$join2 = array('gudang dari', $this->_table.'.dari_gudang_id = dari.id', 'left');
		$join3 = array('gudang ke', 'ke.id = transfer_item.ke_gudang_id', 'left');

		$join_tables = array($join1, $join2, $join3);

		if($gudang_id == 1) {

			$gudang = 1;
		
		} elseif($gudang_id == 2) {

			$gudang = 2;

		} elseif($gudang_id == 3) {

			$gudang = 3;

		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where('transfer_item.is_active',1);
		if($gudang_id == null) 
		{
		
			$this->db->where('transfer_item.`status` !=',1);
		
		} else {

			$this->db->where('transfer_item.`status` !=',1);
			$this->db->where('transfer_item.`dari_gudang_id`', $gudang);

		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('transfer_item.is_active',1);
		if($gudang_id == null) 
		{
		
			$this->db->where('transfer_item.`status` !=',1);
		
		} else {

			$this->db->where('transfer_item.`status` !=',1);
			$this->db->where('transfer_item.`dari_gudang_id`', $gudang);

		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('transfer_item.is_active',1);
		if($gudang_id == null) 
		{
		
			$this->db->where('transfer_item.`status` !=',1);
		
		} else {

			$this->db->where('transfer_item.`status` !=',1);
			$this->db->where('transfer_item.`dari_gudang_id`', $gudang);

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
		// die(dump($result->records));
		return $result; 
	}

	public function get_data_gudang()
	{
		$format = "SELECT id as id, informasi as nama_gudang
					FROM gudang
					WHERE is_active = 1";

		return $this->db->query($format);
	}

	public function get_transfer_item_history($transfer_item_id)
	{

		$sql = "SELECT
				transfer_item_detail.transfer_item_id,
				transfer_item_detail.item_id,
				transfer_item_detail.item_satuan_id,
				transfer_item_detail.jumlah AS jumlah,
				item.kode AS kode,
				item.nama AS nama_item,
				item_satuan.nama AS nama_satuan
				FROM
				transfer_item
				RIGHT JOIN transfer_item_detail ON transfer_item.id = transfer_item_detail.transfer_item_id
				LEFT JOIN item ON transfer_item_detail.item_id = item.id
				LEFT JOIN item_satuan ON transfer_item_detail.item_satuan_id = item_satuan.id
				WHERE
				transfer_item_detail.transfer_item_id = $transfer_item_id";

		return $this->db->query($sql);

	}

	public function get_transfer_item($transfer_item_id)
	{

		$sql = "SELECT
					transfer_item_detail.transfer_item_id,
					transfer_item_detail.item_id,
					transfer_item_detail.item_satuan_id,
					transfer_item_detail.jumlah AS jumlah,
					transfer_item.dari_gudang_id AS dari_gudang_id,
					transfer_item.ke_gudang_id AS ke_gudang_id,
					item.kode AS kode,
					item.nama AS nama_item,
					item_satuan.nama AS nama_satuan,
					item.is_identitas AS is_identitas
				FROM
					transfer_item
				RIGHT JOIN transfer_item_detail ON transfer_item.id = transfer_item_detail.transfer_item_id
				LEFT JOIN item ON transfer_item_detail.item_id = item.id
				LEFT JOIN item_satuan ON transfer_item_detail.item_satuan_id = item_satuan.id

				WHERE
					transfer_item_detail.transfer_item_id = $transfer_item_id
				";

		return $this->db->query($sql);

	}

	public function get_stock($transfer_item_id, $gudang_id)
	{


		$sql = "SELECT
				transfer_item_detail.transfer_item_id,
				transfer_item_detail.item_id,
				transfer_item_detail.item_satuan_id,
				transfer_item_detail.jumlah AS jumlah,
				SUM(inventory.jumlah) AS stock
				FROM
				transfer_item_detail
				LEFT JOIN inventory ON transfer_item_detail.item_id = inventory.item_id AND transfer_item_detail.item_satuan_id = inventory.item_satuan_id
				WHERE
					transfer_item_detail.transfer_item_id = $transfer_item_id
				AND
				inventory.gudang_id = $gudang_id";

		return $this->db->query($sql);
		

	}

	public function get_nama_gudang($dari_gudang_id)
	{

		$sql = "SELECT
				gudang.nama AS nama_gudang,
				transfer_item.dari_gudang_id
				FROM
				transfer_item
				LEFT JOIN gudang ON transfer_item.dari_gudang_id = gudang.id
				WHERE
				transfer_item.dari_gudang_id = $dari_gudang_id";

		return $this->db->query($sql);

	}

	public function get_nomor_transfer()
	{
		$format = "SELECT MAX(SUBSTRING(`no_transfer`,12,3)) AS max_nomor_transfer FROM `transfer_item` WHERE SUBSTRING(`no_transfer`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

	public function get_data($id)
	{
		$sql = "SELECT
				transfer_item_detail.transfer_item_id AS transfer_item_id,
				transfer_item_detail.item_id AS item_id,
				transfer_item_detail.item_satuan_id AS item_satuan_id,
				transfer_item_detail.jumlah AS jumlah,
				item_satuan.nama AS nama_satuan,
				item.kode AS item_kode,
				item.nama AS item_nama,
				transfer_item.dari_gudang_id AS dari_gudang_id,
				Sum(inventory.jumlah) AS stock,
				inventory.inventory_id
				FROM
				transfer_item_detail
				LEFT JOIN transfer_item ON transfer_item_detail.transfer_item_id = transfer_item.id
				LEFT JOIN item ON transfer_item_detail.item_id = item.id
				LEFT JOIN item_satuan ON transfer_item_detail.item_satuan_id = item_satuan.id
				LEFT JOIN inventory ON transfer_item.dari_gudang_id = inventory.gudang_id AND transfer_item_detail.item_id = inventory.item_id AND transfer_item_detail.item_satuan_id = inventory.item_satuan_id
				WHERE
				transfer_item_detail.transfer_item_id = $id
				";
		return $this->db->query($sql);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */