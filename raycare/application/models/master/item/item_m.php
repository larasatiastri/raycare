<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_m extends MY_Model {

	protected $_table        = 'item';
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
		'is_identitas', 		
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
		'is_identitas',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item.id'                => 'id', 
		'item.item_sub_kategori' => 'item_sub_kategori', 
		'item.kode'              => 'kode', 
		'item.nama'              => 'nama', 
		'item.keterangan'        => 'keterangan',
		'item.is_discontinue'    => 'is_discontinue',
		'item.buffer_stock'      => 'buffer_stock',
		'item.is_jual'           => 'is_jual',
		'item.is_identitas'      => 'is_identitas',
		'item_satuan.nama'       => 'unit',
		'item_harga.harga'       => 'harga',
		'item_kategori.nama'     => 'kategori_item',
		
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'item.kode'              => 'kode', 
		'item.nama'              => 'nama', 
		'item_satuan.nama'       => 'unit',
		'item_harga.harga'       => 'harga',
		'item.is_identitas'      => 'is_identitas',
		'item_kategori.id'       => 'item_kategori_id',
		'item.keterangan'        => 'keterangan',
		'item.id'                => 'id', 
		'item_kategori.nama'     => 'kategori_item',
		'item.item_sub_kategori' => 'item_sub_kategori', 

	);

	protected $datatable_columns_index_item = array(
		//column di table  => alias
		'item.id'                => 'id',	
		'item.kode'              => 'kode',
		'item.nama'              => 'nama',
		'item_satuan.id'         => 'satuan_id',
		'item_satuan.nama'       => 'satuan',
		'item.is_identitas'      => 'is_identitas',
		'item_harga.harga'       => 'harga',
		'item_satuan.jumlah'     => 'jumlah',
		'item.keterangan'        => 'keterangan',
		'item_satuan.is_primary' => 'is_primary',
		'item_harga.tanggal'     => 'tanggal'

	);

	protected $datatable_columns_item_stok = array(
		//column di table  => alias
		'item.id'                     => 'id', 
		'item.item_sub_kategori'      => 'item_sub_kategori', 
		'item.kode'                   => 'kode', 
		'item.nama'                   => 'nama', 
		'item_satuan.nama'            => 'satuan',
		'item_kategori.id'            => 'item_kategori_id',
		'item_kategori.nama'          => 'kategori_item',
		'item.is_active' => 'is_active',
		'item.keterangan'             => 'keterangan',
		'item.is_identitas'        => 'is_identitas'
		
	);

	protected $datatable_columns_stok = array(
		//column di table  => alias
		'inventory.inventory_id'   => 'id',
		'inventory.gudang_id'      => 'gudang_id',
		'inventory.item_id'        => 'item_id',
		'inventory.item_satuan_id' => 'satuan_id',
		'SUM(inventory.jumlah)'    => 'jumlah',
		'item.id'                  => 'item_id',
		'item.kode'                => 'item_kode',
		'item.nama'                => 'item_nama',
		'item_satuan.nama'         => 'satuan',
		'item_satuan.id'           => 'item_satuan_id',
		'item_kategori.nama'       => 'kategori',
		'item_sub_kategori.nama'   => 'sub_kategori',
		'item.is_identitas'        => 'is_identitas'
	);

	protected $datatable_columns_buffer = array(
		//column di table  => alias
		'item.id'                => 'id',
		'item.kode'              => 'kode', 
		'item.nama'              => 'nama', 
		'item.buffer_stok'       => 'buffer_stok', 
		'item_satuan.nama'       => 'unit', 
		'item_sub_kategori.nama' => 'nama_subkat'
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($cabang_id, $status_so_history)
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
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
			
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
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('id', 'asc');


		} else {
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
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

	public function get_datatable2($cabang_id, $tipe_item)
	{	

		$join1 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id', 'left');
		$join2 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		$join3 = array('item_harga', 'item_harga.item_id = item.id', 'left');
		$join4 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);

		if ($cabang_id == null && $tipe_item == null) 
		{
			$cabang_id = '';
			$tipe_item = '';
		}
		elseif ($cabang_id == null && $tipe_item != null) 
		{
			$cabang_id = '';
			$tipe_item = '';
		}
		elseif ($cabang_id != null && $tipe_item != null) 
		{
			if($tipe_item == 1) 
			{

				$kategori = 1;

			} elseif ($tipe_item == 2) 

			{

				$kategori = 2;

			}
		}		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
		if ($tipe_item == null)
		{
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
			
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($wheres);
		if ($tipe_item == null)
		{
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);
		if ($tipe_item == null)
		{
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('id', 'asc');


		} else {
			$this->db->where('item_harga.cabang_id', $cabang_id);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
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

	public function get_datatable_index_item($tanggal)
	{	
		$join1 = array('item_harga', $this->_table.'.id = item_harga.item_id', 'left');
		$join2 = array('item_satuan', $this->_table.'.id = item_satuan.item_id AND item_satuan.id = item_harga.item_satuan_id', 'left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_index_item);
		$params['sort_by'] = 'item.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('date(item_harga.tanggal) <=', $tanggal);
		$this->db->group_by('item.kode');
		
		// $this->db->order_by('item.id', 'desc');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		//$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('date(item_harga.tanggal) <=', $tanggal);
		$this->db->group_by('item.kode');

		// $this->db->order_by('item.id', 'desc');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('date(item_harga.tanggal) <=', $tanggal);
		$this->db->group_by('item.kode');
		// $this->db->order_by('item.id', 'desc');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_index_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_index_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_item_box()
	{	
		$join1 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id');
		$join2 = array('item_kategori', 'item_sub_kategori.item_kategori_id = item_kategori.id');
		$join3 = array('item_satuan', $this->_table.'.id = item_satuan.item_id');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item_stok);
		$params['sort_by'] = 'item.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('item.kode');
		
		// $this->db->order_by('item.id', 'desc');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('item.kode');

		// $this->db->order_by('item.id', 'desc');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('item.kode');
		// $this->db->order_by('item.id', 'desc');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item_stok as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item_stok;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_penjualan_obat($status_so_history)
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
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
			
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
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
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
			$this->db->group_by('id', 'asc');


		} else {
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('id', 'asc');
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

	public function get_datatable_item($kategori_sub)
	{		
		$join1 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id');
		$join2 = array('item_kategori', 'item_sub_kategori.item_kategori_id = item_kategori.id');
		$join3 = array('item_satuan', $this->_table.'.id = item_satuan.item_id');
		$join_tables = array($join1, $join2, $join3);

		$date_now = date('Y-m-d');

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item_stok);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($kategori_sub != NULL){
			$this->db->where('item.item_sub_kategori', $kategori_sub);
		}
		$this->db->where('item.is_active',1);
		$this->db->group_by('item.id');
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// dapatkan total row count;
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($kategori_sub != NULL){
			$this->db->where('item.item_sub_kategori', $kategori_sub);
		}
		$this->db->where('item.is_active',1);
		$this->db->group_by('item.id');

		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		// dapatkan total record filtered/search;
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($kategori_sub != NULL){
			$this->db->where('item.item_sub_kategori', $kategori_sub);
		}
		$this->db->where('item.is_active',1);
		$this->db->group_by('item.id');
		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item_stok as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item_stok;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_stok($kategori, $sub_kategori, $item_id)
	{		
		$join1 = array('inventory', $this->_table.'.id = inventory.item_id', 'left');
		$join2 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id');
		$join3 = array('item_kategori', 'item_sub_kategori.item_kategori_id = item_kategori.id');
		$join4 = array('item_satuan', $this->_table.'.id = item_satuan.item_id');
		$join_tables = array($join1, $join2, $join3, $join4);	

		if(($kategori==null && $sub_kategori==null && $item_id==null) || ($kategori==0 && $sub_kategori==0 && $item_id==0)|| ($kategori=='' && $sub_kategori=='' && $item_id==''))
		{
			$wheres = array('');
		}
		else if(($kategori!=null && $sub_kategori!=null && $item_id==null) || ($kategori!=0 && $sub_kategori!=0 && $item_id==0))
		{
			$wheres = array(
				'item.item_sub_kategori =' => $sub_kategori,
				'item_sub_kategori.item_kategori_id =' => $kategori
			);
		}
		else if(($kategori!=null && $sub_kategori==null && $item_id==null) || ($kategori!=0 && $sub_kategori==0 && $item_id==0))
		{
			$wheres = array(
				'item_sub_kategori.item_kategori_id =' => $kategori
			);
		}
		else
		{
			$wheres = array(
				'item.item_sub_kategori =' => $sub_kategori,
				'item_sub_kategori.item_kategori_id =' => $kategori,
				'item.id'	=> $item_id
			);
		}

		// get params dari input postnya datatable
		
		$params = $this->datatable_param($this->datatable_columns_stok);
		$params['sort_by'] = 'item.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_satuan.item_id = item.id');
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($wheres);
		$this->db->group_by('item.id, item_satuan.id');
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_satuan.item_id = item.id');
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($wheres);
		$this->db->group_by('item.id, item_satuan.id');
		// $this->db->where($wheres);

		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_satuan.item_id = item.id');
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($wheres);
		$this->db->group_by('item.id, item_satuan.id');
		// $this->db->where($wheres);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_stok as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_stok;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_buffer_stock($gudang_id=0, $kategori_id=0, $sub_kategori_id)
	{
		$join1 = array('item_satuan', $this->_table.'.id = item_satuan.item_id', 'LEFT');
		$join2 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id');
		
		$join_tables = array($join1, $join2);

		$wheres = array(
			'item.is_active'         => 1,
			'item_satuan.is_primary' => 1
		);
	
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_buffer);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);
		// $this->db->group_by('item.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);

		// $this->db->group_by('item.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);

		// $this->db->group_by('item.id');
		

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

	public function get_nilai_konversi($item_satuan_id) {

		$sql = "SELECT * FROM item_satuan WHERE parent_id = '$item_satuan_id' ";
    	$return = $this->db->query($sql)->result_array();

    	if ($return) {
    		
    		$id = $return[0]['id'];

    		$nilai_konversi = $return[0]['jumlah'];
    		while ($id != NULL) 
	    	{
				$format = "SELECT * FROM item_satuan WHERE parent_id = '$id'";
				$return = $this->db->query($format)->result_array();

	    		if (empty($return)) {
	    			$nilai_konversi = $nilai_konversi;
	    			break;
	    		} else{

	    			$nilai_konversi = $nilai_konversi * $return[0]['jumlah'];
    				$id = $return[0]['id'];
	    		}
	    	}

    	} else {
    		$nilai_konversi = 1;
    	}

    	return $nilai_konversi;
	}

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM item');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	public function get_no_kode($initial_item)
	{
		$format = "SELECT MAX(SUBSTRING(`kode`,3,5)) AS max_no_kode FROM `item` WHERE SUBSTRING(kode,2,1) = '$initial_item'";	
		return $this->db->query($format);
	}

	public function cek_no_kode($initial_item)
	{
		$format = "SELECT MAX(kode) AS no_kode FROM `item` WHERE SUBSTRING(kode,2,1) = '$initial_item'";	
		return $this->db->query($format);
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

	public function get_dialyzer()
	{
		$format = "SELECT * FROM `item` WHERE id IN ".config_item('dialyzer_id');	
		return $this->db->query($format);
	}

	public function get_data_sub_kategori($id_sub_kategori)
	{
		$id_sub_kategori = rtrim($id_sub_kategori,', ');

		$sql = "SELECT * FROM item WHERE is_active = 1 AND item_sub_kategori IN ($id_sub_kategori)";
		return $this->db->query($sql);

	}

	public function get_datatable_buffer_stock_kosong($gudang_id, $kategori_id=0, $sub_kategori_id)
	{
		$join1 = array('item_satuan', $this->_table.'.id = item_satuan.item_id', 'LEFT');
		$join2 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id');
		
		$join_tables = array($join1, $join2);

		$wheres = array(
			'item.is_active'         => 1,
		);
	
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_buffer);
		$params['sort_by']	= 'item.nama';
		$params['sort_dir']	= 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where_not_in('item_id','SELECT item.id AS id FROM inventory LEFT JOIN item ON inventory.item_id = item.id LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id WHERE item.is_active =  1 AND inventory.gudang_id =  '.$gudang_id.' GROUP BY inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date ORDER BY inventory.item_id ASC'); 
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where_not_in('item_id','SELECT item.id AS id FROM inventory LEFT JOIN item ON inventory.item_id = item.id LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id WHERE item.is_active =  1 AND inventory.gudang_id =  '.$gudang_id.' GROUP BY inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date ORDER BY inventory.item_id ASC'); 
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);


		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where_not_in('item_id','SELECT item.id AS id FROM inventory LEFT JOIN item ON inventory.item_id = item.id LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id WHERE item.is_active =  1 AND inventory.gudang_id =  '.$gudang_id.' GROUP BY inventory.item_id, inventory.item_satuan_id, inventory.bn_sn_lot, inventory.expire_date ORDER BY inventory.item_id ASC'); 
		if($sub_kategori_id != 0) $this->db->where_in('item.item_sub_kategori',$sub_kategori_id);
		

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

	public function get_item_box_paket($id, $created_date)
	{

		$format = "SELECT
					item.kode AS kode_item,
					item.nama AS nama_item,
					item_satuan.nama AS nama_satuan,
					t_box_paket_detail.jumlah AS jumlah,
					t_box_paket_detail.bn_sn_lot AS bn_sn_lot,
					t_box_paket_detail.expire_date AS expire_date
					FROM
					t_box_paket_detail
					LEFT JOIN item ON t_box_paket_detail.item_id = item.id
					LEFT JOIN item_satuan ON t_box_paket_detail.item_satuan_id = item_satuan.id
					WHERE
					t_box_paket_detail.t_box_paket_id = '$id'
					AND date(t_box_paket_detail.created_date) = '$created_date'";

		return $this->db->query($format);

	}

	public function get_item_master_box_paket($id)
	{

		$format = "SELECT
					item.kode AS kode_item,
					item.nama AS nama_item,
					item_satuan.nama AS nama_satuan,
					box_paket_detail.jumlah AS jumlah
					FROM
					box_paket_detail
					LEFT JOIN item ON box_paket_detail.item_id = item.id
					LEFT JOIN item_satuan ON box_paket_detail.item_satuan_id = item_satuan.id
					WHERE
					box_paket_detail.box_paket_id = '$id'";

		return $this->db->query($format);

	}

}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */