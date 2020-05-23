<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_opname_m extends MY_Model
{
	protected $_table        = 'stok_opname';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
		'warehouse_id',
		'user_id',
		'start_date',
		'end_date',
		'note',
	);




	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'stok_opname.no_stok_opname'	=> 'stock_opname_number', 
		'stok_opname.id'               	=> 'id', 
		'stok_opname.gudang_orang_id'  => 'warehouse_people_id', 
		'gudang_orang.nama'				=> 'people',
		'stok_opname.gudang_id'         => 'warehouse_id',
		'stok_opname.is_active'         	=> 'is_active',
		'stok_opname.created_by'         	=> 'create_by',
		'stok_opname.created_date'         	=> 'create_date',
		'stok_opname.tanggal_mulai'         	=> 'start_date',
		'stok_opname.tanggal_selesai'         	=> 'end_date',
		'stok_opname.keterangan'         	=> 'is_mismatch',
		'user.nama'					=> 'user'
	);

	protected $datatableStockopname_columns = array(
		//column di table  => alias
		'stok_opname.id'               	=> 'id', 
		'stock_opname.no_stok_opname'	=> 'stock_opname_number', 
		'gudang_orang.nama'				=> 'people',
		'stok_opname.warehouse_id'         => 'warehouse_id',
		'stok_opname.tanggal_mulai'         	=> 'start_date',
		'stok_opname.tanggal_selesai'         	=> 'end_date',
		'stok_opname.is_mismatch'         	=> 'is_mismatch',
		'stok_opname.is_finish'         	=> 'is_finish',
		//'stok_opname.is_approve'         	=> 'is_approve',
		'stok_opname.is_journal'         	=> 'is_journal',
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */


public function get_datatable($dt)
	{
		$join1 = array("gudang_orang", $this->_table . '.gudang_orang_id = gudang_orang.id', 'left');
		$join2 = array("user", $this->_table . '.created_by = user.id', 'left');
		
		$join_tables = array($join1, $join2);

		$wheres = array();

		if($dt != null)
		{
			$wheres['stok_opname.gudang_id'] = $dt;
			$wheres['is_finish'] = 0;
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'no_stok_opname';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('stok_opname.is_active',1);
		$this->db->where($wheres);


		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('stok_opname.is_active',1);
		$this->db->where($wheres);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('stok_opname.is_active',1);
		$this->db->where($wheres);

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

	// 	  die(dump($this->db->last_query()));
		return $result; 
		// die_dump($result->records);
	}

public function get_datatable_stockOpname()
	{
		$join1 = array("gudang_orang", $this->_table . '.gudang_orang_id = gudang_orang.id', 'left');
		// $join1 = array("gudang", $this->_table . '.gudang_id = gudang.id', 'left');
		// $join2 = array("gudang_orang", 'gudang_orang.gudang_id = gudang.id', 'left');
		
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatableStockopname_columns);
		$wheres = array(
			'is_finish' => 1,
			'is_mismatch' => 1,
			//'is_approve' => 1,
			'is_journal' => 0,
			);
		
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);

		// tentukan kolom yang mau diselect
		foreach ($this->datatableStockopname_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatableStockopname_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_mismatch_history()
	{
		$join1 = array("gudang_orang", $this->_table . '.gudang_orang_id = gudang_orang.id', 'left');
		$join2 = array("user", $this->_table . '.created_by = user.id', 'left');
		
		$join_tables = array($join1, $join2);

		$wheres = array(
				'is_journal'	=> 1,
				'is_mismatch' 	=> 1,
				'is_finish'		=> 1,
				//'is_approve'	=> 1,
			);
		$wheres_or = array(
				
				'is_mismatch' 	=> 1,
				'is_finish'		=> 1,
				//'is_approve'	=> 1,
			);


		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->or_where('is_journal',2);
		$this->db->where($wheres_or);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->or_where('is_journal',2);
		$this->db->where($wheres_or);


		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->or_where('is_journal',2);
		$this->db->where($wheres_or);


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

	public function get_datatable_history($dt, $status=null)
	{
		$join1 = array("gudang_orang", $this->_table . '.gudang_orang_id = gudang_orang.id', 'left');
		$join2 = array("user", $this->_table . '.created_by = user.id', 'left');
		
		$join_tables = array($join1, $join2);

		$wheres = array();

		if($dt != null)
		{
			$wheres['stok_opname.gudang_id'] = $dt;
			$wheres['is_finish'] = 1;

			if ($status != null && $status != 2) {
	
				$wheres['is_mismatch'] = $status;
			}
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('stok_opname.is_active',1);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('stok_opname.is_active',1);
		$this->db->where($wheres);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('stok_opname.is_active',1);
		$this->db->where($wheres);

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

		//  die(dump($result->records));
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
	public function get_people($id)
	{ 

		$this->db->join('gudang_orang',$this->_table.'.gudang_orang_id = gudang_orang.id','left');
		$this->db->where($this->_table.'.id',$id);

		return $this->db->get($this->_table);
	}

	public function get_warehouse($id)
	{
		$this->db->join('gudang',$this->_table.'.gudang_id = gudang.id','left');
		$this->db->where($this->_table.'.id',$id);

		return $this->db->get($this->_table);
	}

	public function get_nomor_stk()
	{
		$format = "SELECT MAX(SUBSTRING(`no_stok_opname`,10,3)) AS max_nomor_stk FROM `stok_opname` WHERE SUBSTRING(`no_stok_opname`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

public function printpdf($id)
	{
		$format = "SELECT `item`.`kode` AS item_code, `item`.`nama` AS item_name, 
						`stok_opname_detail`.`jumlah_hitung` AS counted_qty, 
						`stok_opname_detail`.`jumlah_sistem` AS system_qty, 
						`stok_opname_detail`.`id` AS id, 
						`stok_opname_detail`.`stok_opname_id` AS stock_opname_id, 
						`stok_opname_detail`.`item_id` AS item_id, 
						`item_satuan`.`nama` AS nama_satuan
					FROM `stok_opname_detail`
					LEFT JOIN `item` ON `stok_opname_detail`.`item_id` = `item`.`id`
					LEFT JOIN `item_satuan` ON `stok_opname_detail`.`item_satuan_id` = `item_satuan`.`id`
					WHERE `stok_opname_id` =  ?
					ORDER BY `item`.`kode` asc";

		return $this->db->query($format,$id);
	}

	public function getjumlahsistem($item_satuan_id,$item_id,$warehouse_id)
	{
		$format = "SELECT SUM(inventory.jumlah) as jumlah FROM inventory WHERE item_id =".$item_id."  AND gudang_id =".$warehouse_id." and item_satuan_id=".$item_satuan_id;	
		return $this->db->query($format);
	}

	public function getid1($item_id,$item_satuan_id,$warehouse_id)
	{
		$format = "select * from inventory_identitas where inventory_id in (SELECT  inventory_id FROM inventory WHERE item_id =".$item_id."  AND gudang_id =".$warehouse_id." and item_satuan_id=".$item_satuan_id.")";	
		return $this->db->query($format);
	}
	 
	public function getid2($id)
	{
		$format = "SELECT  * FROM inventory_identitas_detail WHERE inventory_identitas_id =".$id;	
		return $this->db->query($format);
	}
	public function getlastid()
	{
		$format = "SELECT MAX(id) as id FROM stok_opname_identitas_detail";	
		return $this->db->query($format);
	}

	public function getmodified($id)
	{
		$format = "select c.id,a.nama as nama1,ifnull(b.nama,'-') as nama2,a.created_date,ifnull(b.modified_date,'-') as modified_date from stok_opname c left join (SELECT a.id,a.created_by,b.nama,a.created_date from stok_opname a join user b on a.created_by=b.id where a.id=".$id.") a on c.id=a.id left join (SELECT a.id,a.modified_by,b.nama,a.modified_date from stok_opname a join user b on a.modified_by=b.id where a.id=".$id.") b on c.id=b.id where c.id=".$id;	
		return $this->db->query($format);
	}

	public function checkjumlah($id)
	{
		$format = "select count(*) as counts from inventory_identitas where inventory_id=".$id;	
		return $this->db->query($format);
	}

	public function getjumlah($id)
	{
		$format = "select sum(jumlah) as jumlah from inventory_identitas where inventory_id=".$id;	
		return $this->db->query($format);
	}
	public function gettotaljumlah($id)
	{
		$format = "select sum(jumlah_sistem) as jumlah from stok_opname_identitas where stok_opname_detail_id=".$id;	
		return $this->db->query($format);
	}
	public function update_stok_opname_identitas($jumlah,$stok_opname_detail_id,$inventory_identitas_id)
	{
		$format = "update stok_opname_identitas set jumlah_hitung=".$jumlah.",jumlah_input=".$jumlah.",modified_by=".$this->session->userdata("user_id").",modified_date=now() where stok_opname_detail_id=".$stok_opname_detail_id." and inventory_identitas_id=".$inventory_identitas_id;	
		return $this->db->query($format);
	}
}
