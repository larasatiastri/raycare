<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buffer_stock_m extends MY_Model {

	protected $_table        = 'inventory';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'item_sub_kategori',
		'kode',
		'nama', 
		'keterangan', 
		'is_discontinue', 
		'buffer_stock', 		
		'is_jual', 		
		'id_identitas', 		
	);

	private $_fillable_edit = array(
		'id',
		'item_sub_kategori',
		'kode',
		'nama', 
		'keterangan', 
		'is_discontinue', 
		'buffer_stock', 		
		'is_jual', 		
		'id_identitas',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias 
		'item.kode'             => 'kode', 
		'item.nama'             => 'nama',
		'sum(inventory.jumlah)' => 'jumlah',  
		'item.buffer_stok'      => 'buffer_stock',
		'item_satuan.nama'      => 'item_satuan_id',
		'(Case
               When sum(inventory.jumlah) < buffer_stok Then "Danger"
               When sum(inventory.jumlah) >  (buffer_stok+10) Then "Normal"             
               Else "Warning"
               End)' => 'status2'
		
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'item.id'                => 'id', 
		'item.item_sub_kategori' => 'item_sub_kategori', 
		'item.kode'              => 'kode', 
		'item.nama'              => 'nama', 
		'item_satuan.nama'       => 'unit',
		'item_harga.harga'       => 'harga',
		'item_kategori.id'       => 'item_kategori_id',
		'item_kategori.nama'     => 'kategori_item',
		'item.keterangan'        => 'keterangan',	
	);

	protected $datatable_columns2 = array(
		//column di table  => alias
		'item.id'         		 => 'id', 
		'item.kode'   			 => 'kode', 
		'item.nama'   			 => 'nama', 
		 
	);


	protected $datatable_columns_buffer = array(
		//column di table  => alias
		'item.id'                => 'id',
		'item.kode'              => 'kode', 
		'item.nama'              => 'nama', 
		'item.buffer_stok'       => 'buffer_stok', 
		'item_satuan.nama'       => 'unit', 
		'item_sub_kategori.nama' => 'nama_subkat',
		'sum(inventory.jumlah)'	 => 'stok',
		'inventory.bn_sn_lot'	 => 'bn_sn_lot',
		'inventory.expire_date'	 => 'expire_date',
		'inventory.item_satuan_id'	 => 'item_satuan_id',
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($cabang_id, $item_kategori)
	{	

		  $join1 = array('item', $this->_table.'.item_id = item.id', 'left');
		  $join2 = array('item_satuan', 'inventory.item_satuan_id = item_satuan.id', 'left');
		// $join3 = array('item_harga', 'item_harga.item_id = item.id', 'left');
		// $join4 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');

		//$join_tables = array($join1, $join2, $join3, $join4);
		$join_tables = array($join1,$join2);
		
		 
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
		 
		$this->db->where('inventory.gudang_id', $cabang_id);
	 	$this->db->where('inventory.item_id in (select id from item where item_sub_kategori in (select id from item_sub_kategori where item_kategori_id='.$item_kategori.')) group by inventory.item_id,item_satuan_id,buffer_stok');

 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($wheres);
		 

		$this->db->where('inventory.gudang_id', $cabang_id);
		$this->db->where('inventory.item_id in (select id from item where item_sub_kategori in (select id from item_sub_kategori where item_kategori_id='.$item_kategori.')) group by inventory.item_id,item_satuan_id,buffer_stok');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);
	 
		$this->db->where('inventory.gudang_id', $cabang_id);
		$this->db->where('inventory.item_id in (select id from item where item_sub_kategori in (select id from item_sub_kategori where item_kategori_id='.$item_kategori.')) group by inventory.item_id,item_satuan_id,buffer_stok');
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
	 //   die(dump($result->records));
		return $result; 
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_stock_dokter($cabang_id)
	{
		$cabang_id = $this->session->userdata('cabang_id');
		$gudang = $this->gudang_m->get_by(array('cabang_klinik' => $cabang_id));

		$gudang_id = "";
		foreach ($gudang as $row_gudang) {
			$gudang_id .= "'".$row_gudang->id."', ";
		}

		$gudang_id = rtrim($gudang_id,", ");

		$join1 = array('item', $this->_table.'.item_id = item.id', 'LEFT');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'LEFT');
		$join3 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id');
		
		$join_tables = array($join1, $join2,$join3);

		$wheres = array(
			'item.is_active'         => 1
		);
	
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_buffer);
		$params['sort_by'] = 'item.nama';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where("inventory.gudang_id IN ($gudang_id)");
		$this->db->group_by('inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where("inventory.gudang_id IN ($gudang_id)");
		$this->db->group_by('inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date');


		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where("inventory.gudang_id IN ($gudang_id)");
		$this->db->group_by('inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date');
		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_buffer as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_buffer;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_item_po($status_so_history)
	{	

		$join1 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id', 'left');
		$join2 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		$join3 = array('item_harga', 'item_harga.item_id = item.id', 'left');
		$join4 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);
		
		if($status_so_history == 1) 
		{

			$kategori = 1;

		} elseif ($status_so_history == 2) 

		{

			$kategori = 2;

		} elseif ($status_so_history == 3) 

		{

			$kategori = 3;

		}

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_satuan.is_primary', 1);


		} else {

			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));
		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_satuan.is_primary', 1);


		} else {

			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_satuan.is_primary', 1);


		} else {
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
		}

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM item');
		
		return $query->row();
		return $this->db->get($this->_table);
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

	public function get_datatable2($id)
	{		
		$join = array('item_sub_kategori', $this->_table . '.item_sub_kategori = item_sub_kategori.id');
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns2);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_sub_kategori.id',$id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_sub_kategori.id',$id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_sub_kategori.id',$id);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns2 as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns2;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_buffer_stock($gudang_id, $kategori_id=0, $sub_kategori_id)
	{
		$join1 = array('item', $this->_table.'.item_id = item.id', 'LEFT');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'LEFT');
		$join3 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id');
		
		$join_tables = array($join1, $join2, $join3);

		$wheres = array(
			'item.is_active'         => 1
		);
	
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_buffer);
		$params['sort_by'] = 'item.nama';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);
		$this->db->where('inventory.gudang_id',$gudang_id);
		$this->db->group_by('inventory.item_id, inventory.item_satuan_id');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);
		$this->db->where('inventory.gudang_id',$gudang_id);
		$this->db->group_by('inventory.item_id, inventory.item_satuan_id');


		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);
		$this->db->where('inventory.gudang_id',$gudang_id);
		$this->db->group_by('inventory.item_id, inventory.item_satuan_id');
		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_buffer as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_buffer;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}


	public function getdatabufferstock($id,$supplier_id)
	{
	$format ="select kode,nama,jumlah,buffer_stock,d.item_satuan_id,d.item_id,status2,item_satuan_id2 from(select kode,nama,jumlah,buffer_stock,item_satuan_id,item_id,item_satuan_id2,(Case
                                        When jumlah < buffer_stock Then 'Danger'
                                         When jumlah > status Then 'Normal'             
                                        Else 'Warning'
                                        End) as status2 from(select kode,nama,jumlah,buffer_stock,item_satuan_id,item_id,buffer_stock + 10 as status,item_satuan_id2 from (SELECT item.kode AS kode, item.nama AS nama, sum(inventory.jumlah) AS jumlah, item.buffer_stok AS buffer_stock, item_satuan.nama AS item_satuan_id,inventory.item_satuan_id as item_satuan_id2,item.id as item_id
				 	FROM inventory
					LEFT JOIN item ON inventory.item_id = item.id
					LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id
					 group by inventory.item_id,item_satuan_id,buffer_stok
					ORDER BY item.kode asc)b)c)d join (select item_id,supplier_id from supplier_item group by item_id) f on f.item_id=d.item_id where d.status2 in (".$id.") and f.supplier_id=".$supplier_id;
		return $this->db->query($format);
	}

	public function getdatabufferstockso($id,$cabang_id)
	{
	$format ="select kode,nama,jumlah,buffer_stock,d.item_satuan_id,d.item_id,status2,item_satuan_id2 from(select kode,nama,jumlah,buffer_stock,item_satuan_id,item_id,item_satuan_id2,(Case
                                        When jumlah < buffer_stock Then 'Danger'
                                         When jumlah > status Then 'Normal'             
                                        Else 'Warning'
                                        End) as status2 from(select kode,nama,jumlah,buffer_stock,item_satuan_id,item_id,buffer_stock + 10 as status,item_satuan_id2 from (SELECT item.kode AS kode, item.nama AS nama, sum(inventory.jumlah) AS jumlah, item.buffer_stok AS buffer_stock, item_satuan.nama AS item_satuan_id,inventory.item_satuan_id as item_satuan_id2,item.id as item_id
				 	FROM inventory
					LEFT JOIN item ON inventory.item_id = item.id
					LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id where inventory.gudang_id in (select id from gudang where cabang_id=".$cabang_id.")
					group by inventory.item_id,item_satuan_id,buffer_stok
					ORDER BY item.kode asc)b)c)d  where d.status2 in (".$id.")";
		return $this->db->query($format);
	}

	public function get_data_buffer_stock($gudang_id, $kategori_id=0, $sub_kategori_id)
	{

		$this->db->select('item.id as id, item.kode as kode, item.nama as nama, item.buffer_stok as buffer_stok, item_satuan.nama as unit, item_sub_kategori.nama as nama_subkat, sum(inventory.jumlah) as stok, inventory.bn_sn_lot as bn_sn_lot, inventory.expire_date as expire_date');

		$this->db->join('item', $this->_table.'.item_id = item.id', 'LEFT');
		$this->db->join('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'LEFT');
		$this->db->join('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id');
		
		$wheres = array(
			'item.is_active'         => 1
		);

		$this->db->where($wheres);
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);
		$this->db->where('inventory.gudang_id',$gudang_id);
		$this->db->order_by('item.nama','asc');
		$this->db->group_by('inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date');
		return $this->db->get($this->_table);
	}

	public function get_data_buffer_stock_kosong($gudang_id, $kategori_id=0, $sub_kategori_id)
	{

		$this->db->select('item.id as id, item.kode as kode, item.nama as nama, item.buffer_stok as buffer_stok, item_satuan.nama as unit, item_sub_kategori.nama as nama_subkat');

		$this->db->join('item_satuan', 'item.id = item_satuan.item_id', 'LEFT');
		$this->db->join('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id');

		$wheres = array(
			'item.is_active'         => 1,
		);
		
		$this->db->where($wheres);
		$this->db->where_not_in('item_id','SELECT item.id AS id FROM inventory LEFT JOIN item ON inventory.item_id = item.id LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id WHERE item.is_active =  1 AND inventory.gudang_id =  '.$gudang_id.' GROUP BY inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date ORDER BY inventory.item_id ASC'); 
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);
		$this->db->order_by('item.nama','asc');

		return $this->db->get('item');
	}

	public function get_stok_item_detail($gudang_id, $item_id, $item_satuan_id)
	{
		$this->db->select("item.kode as kode, item.nama as nama, item_satuan.nama as nama_satuan, SUM(inventory.jumlah) as jumlah, inventory.bn_sn_lot as bn_sn_lot, DATE_FORMAT(inventory.expire_date,'%d %b %Y') as expire_date");
		$this->db->join('item', $this->_table.'.item_id = item.id', 'LEFT');
		$this->db->join('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'LEFT');
		$this->db->where('inventory.item_id',$item_id);
		$this->db->where('inventory.item_satuan_id',$item_satuan_id);
		$this->db->where('inventory.gudang_id',$gudang_id);
		$this->db->order_by('item.nama','asc');
		$this->db->group_by('inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date');

		return $this->db->get($this->_table);
	}

	public function get_data_expired()
	{
		$date = date('Y-m-d', strtotime("+2 months"));

		$SQL = "SELECT
					gudang.nama AS nama_gudang,
					inventory.item_id,
					inventory.item_satuan_id,
					inventory.gudang_id,
					item.kode AS kode_item,
					item.nama AS nama_item,
					item_satuan.nama AS nama_satuan,
					inventory.bn_sn_lot,
					inventory.expire_date,
					sum(inventory.jumlah) AS jumlah,
					inventory.harga_beli AS harga_beli,
				    item_satuan.harga AS harga_jual,
					supplier.nama AS nama_supplier,
					supplier.id AS supplier_id
				FROM
					inventory
				LEFT JOIN item ON inventory.item_id = item.id
				LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id
				LEFT JOIN gudang ON inventory.gudang_id = gudang.id
				LEFT JOIN pmb ON inventory.pmb_id = pmb.id
				LEFT JOIN supplier ON pmb.supplier_id = supplier.id
				WHERE
					date(inventory.expire_date) <= '$date'
					AND inventory.gudang_id = 'WH-05-2016-002'
					AND inventory.expire_date IS NOT NULL
					AND date(inventory.expire_date) != '1970-01-01'
				GROUP BY
					inventory.item_id,
				inventory.item_satuan_id,
					inventory.bn_sn_lot,
					inventory.expire_date,
					inventory.gudang_id,
					inventory.pmb_id
				ORDER BY
					inventory.expire_date ASC";

		return $this->db->query($SQL);

	}

	public function get_data_buffer()
	{
		$SQL = "SELECT
					gudang.nama AS nama_gudang,
					inventory.item_id,
					inventory.item_satuan_id,
					inventory.gudang_id,
					item.kode AS kode_item,
					item.nama AS nama_item,
					item.buffer_stok AS buffer_stok,
					item_satuan.nama AS nama_satuan,
					inventory.bn_sn_lot,
					inventory.expire_date,
					sum(inventory.jumlah) AS jumlah,
					inventory.harga_beli AS harga_beli,
				item_satuan.harga AS harga_jual
				FROM
					inventory
				LEFT JOIN item ON inventory.item_id = item.id
				LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id
				LEFT JOIN gudang ON inventory.gudang_id = gudang.id
				WHERE inventory.gudang_id = 'WH-05-2016-002'
				AND inventory.expire_date IS NOT NULL
				AND date(inventory.expire_date) != '1970-01-01'
				GROUP BY
					inventory.item_id,
					inventory.item_satuan_id,
					inventory.gudang_id
				HAVING (sum(inventory.jumlah) >= item.buffer_stok AND sum(inventory.jumlah) <= (item.buffer_stok * 2))
				ORDER BY
					inventory.expire_date ASC";

		return $this->db->query($SQL);
	}


}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */