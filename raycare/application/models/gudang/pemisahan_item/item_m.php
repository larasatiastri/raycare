<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_m extends MY_Model {

	protected $_table        = 'item';
	// protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'order_permintaan_pembelian_detail.id'     => 'id', 
		'item.kode'                                => 'kode', 
		'item.nama'                                => 'nama',
		'order_permintaan_pembelian_detail.jumlah' => 'jumlah', 
		'item_satuan.nama'                         => 'satuan',
		'order_permintaan_pembelian.is_active'	   => 'active'
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

	protected $datatable_columns_item_beli = array(
		//column di table  => alias
		'item.id'                                  => 'id',
		'item.kode'                                => 'item_kode',
		'item.nama'                                => 'item_nama',
		'item_satuan.nama'                         => 'satuan',
		'item_satuan.jumlah'                       => 'jumlah',
		'item_satuan.is_primary'                   => 'is_primary',
		'item.is_active'                           => 'is_active',
		'supplier.nama'                            => 'nama_sup',
		'supplier.kode'                            => 'kode_sup',
		'MAX(supplier_harga_item.harga)'           => 'harga',
		'MAX(supplier_harga_item.tanggal_efektif)' => 'tanggal_efektif',
		'supplier_item.minimum_order'              => 'min_order',
		'supplier_item.kelipatan_order'            => 'max_order',
		'supplier.id'                              => 'id_sup'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id)
	{	
		$join1 = array('order_permintaan_pembelian_detail', $this->_table.'.id = order_permintaan_pembelian_detail.item_id');
		$join2 = array('item_satuan',$this->_table.'.id = item_satuan.item_id');
		$join3 = array('order_permintaan_pembelian','order_permintaan_pembelian.id = order_permintaan_pembelian_detail.order_permintaan_pembelian_id');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		$this->db->where('item_satuan.id = order_permintaan_pembelian_detail.item_satuan_id');
		$this->db->group_by('order_permintaan_pembelian_detail.item_id');
		// dapatkan total row count;

		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		//$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		$this->db->where('item_satuan.id = order_permintaan_pembelian_detail.item_satuan_id');
		$this->db->group_by('order_permintaan_pembelian_detail.item_id');

		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		$this->db->where('item_satuan.id = order_permintaan_pembelian_detail.item_satuan_id');
		$this->db->group_by('order_permintaan_pembelian_detail.item_id');

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

	public function get_datatable_item()
	{		
		$join1 = array('item_satuan', $this->_table.'.id = item_satuan.item_id');
		$join2 = array('supplier_item', $this->_table.'.id = supplier_item.item_id');
		$join3 = array('supplier', 'supplier_item.supplier_id = supplier.id');
		$join4 = array('supplier_harga_item', 'supplier_item.id = supplier_harga_item.supplier_item_id');
		$join_tables = array($join1, $join2, $join3, $join4);

		$date_now = date('Y-m-d');

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item_beli);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('supplier_harga_item.tanggal_efektif <=', $date_now);
		$this->db->group_by('item.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('supplier_harga_item.tanggal_efektif <=', $date_now);
		$this->db->group_by('item.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('supplier_harga_item.tanggal_efektif <=', $date_now);
		$this->db->group_by('item.id');
		

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item_beli as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item_beli;
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


		// get params dari input postnya datatable
			$this->db->where('item_satuan.is_primary', 1);


		$params = $this->datatable_param($this->datatable_columns_item);
		// prepare buat total record tanpa filter dan limit
		if ($status_so_history == null)
		{
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->or_where('item_kategori_id', 3);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('item.id');
		} else {

			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('item.id');
			
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
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
			$this->db->or_where('item_kategori_id', 3);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('item.id');


		} else {

			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('item.id');
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
			$this->db->or_where('item_kategori_id', 3);
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->group_by('item.id');


		} else {
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item_kategori_id', $kategori);
			$this->db->group_by('item.id');
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

	public function get_data_item($id)
	{
		$format = "	SELECT
						(SELECT SUM(inventory.jumlah) FROM inventory WHERE item.id = inventory.item_id AND item_satuan.id = inventory.item_satuan_id) as stok,
						item_satuan.nama
					FROM
						item ,
						item_satuan ,
						inventory
					WHERE
						item.id = item_satuan.item_id AND
						item.id = inventory.item_id AND
						item_satuan.id = inventory.item_satuan_id AND
						item.id = $id
					GROUP BY inventory.item_satuan_id
		";

		return $this->db->query($format, $id);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/item_m.php */