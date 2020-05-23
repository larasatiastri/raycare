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
		//'item.id'         		 => 'id', 
		//'item.item_sub_kategori' => 'item_sub_kategori', 
		'item.kode'             => 'kode', 
		'item.nama'             => 'nama',
		'sum(inventory.jumlah)' => 'jumlah',  
		//'item.keterangan'     => 'keterangan',
		//'item.is_discontinue' => 'is_discontinue',
		'item.buffer_stok'      => 'buffer_stock',
		'item_satuan.nama'      => 'item_satuan_id',
		'(Case
               When sum(inventory.jumlah) < buffer_stok Then "Danger"
               When sum(inventory.jumlah) >  (buffer_stok+10) Then "Normal"             
               Else "Warning"
               End)' => 'status2',
		//'item.is_jual'     		 => 'is_jual',
		//'item.id_identitas'      => 'id_identitas',
		//'item_satuan.nama'      => 'unit',
		//'item_harga.harga'      => 'harga',
		//'item_kategori.nama'      => 'kategori_item',
		
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'item.id'         		 => 'id', 
		'item.item_sub_kategori' => 'item_sub_kategori', 
		'item.kode'   			 => 'kode', 
		'item.nama'   			 => 'nama', 
		'item_satuan.nama'      => 'unit',
		'item_harga.harga'      => 'harga',
		'item_kategori.id'      => 'item_kategori_id',
		'item_kategori.nama'      => 'kategori_item',
		'item.keterangan'  		 => 'keterangan',

		
	);

	protected $datatable_columns2 = array(
		//column di table  => alias
		'item.id'         		 => 'id', 
		'item.kode'   			 => 'kode', 
		'item.nama'   			 => 'nama', 
		 
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

}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */