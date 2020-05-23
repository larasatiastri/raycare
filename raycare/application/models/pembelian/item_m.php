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
		'item_satuan.harga'      => 'harga',
		'item_kategori.id'      => 'item_kategori_id',
		'item_kategori.nama'      => 'kategori_item',
		'item.keterangan'  		 => 'keterangan',
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
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		$this->db->where('item_satuan.id = order_permintaan_pembelian_detail.item_satuan_id');
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		$this->db->where('item_satuan.id = order_permintaan_pembelian_detail.item_satuan_id');
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');

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

	public function get_datatable_item($id_supplier)
	{	
		$datatable_columns_item_beli = array(
			//column di table  => alias
			'item.id'                                  => 'id',
			'item.kode'                                => 'item_kode',
			'item.nama'                                => 'item_nama',
			'item_satuan.id'                         => 'item_satuan_id',
			'item_satuan.nama'                         => 'satuan',
			'(SELECT SUM(jumlah_belum_diterima) FROM pembelian_detail JOIN pembelian ON (pembelian_detail.pembelian_id = pembelian.id) WHERE pembelian.supplier_id = '.$id_supplier.' AND pembelian_detail.item_id = item.id GROUP BY pembelian_detail.item_id)'                       => 'jumlah',
			'item_satuan.is_primary'                   => 'is_primary',
			'item.is_active'                           => 'is_active',
			'supplier.nama'                            => 'nama_sup',
			'supplier.kode'                            => 'kode_sup',
			'supplier_item.minimum_order'              => 'min_order',
			'supplier_item.kelipatan_order'            => 'max_order',
			'supplier.id'                              => 'id_sup'
		);	
		$join1 = array('item_satuan', $this->_table.'.id = item_satuan.item_id','left');
		$join2 = array('supplier_item', $this->_table.'.id = supplier_item.item_id','left');
		$join3 = array('supplier', 'supplier_item.supplier_id = supplier.id','left');
		$join_tables = array($join1, $join2, $join3);

		$date_now = date('Y-m-d');

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_item_beli);

		$params['sort_by'] = 'item.kode';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('supplier_item.supplier_id', $id_supplier);
		$this->db->where('supplier_item.is_supply', 1);
		$this->db->where('supplier_item.is_active', 1);
		$this->db->group_by('item.id');
		$this->db->order_by('item.id','asc');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('supplier_item.supplier_id', $id_supplier);
		$this->db->where('supplier_item.is_supply', 1);
		$this->db->where('supplier_item.is_active', 1);
		$this->db->group_by('item.id');
		$this->db->order_by('item.id','asc');

		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('supplier_item.supplier_id', $id_supplier);
		$this->db->where('supplier_item.is_supply', 1);
		$this->db->where('supplier_item.is_active', 1);
		$this->db->group_by('item.id');
		$this->db->order_by('item.id','asc');
		

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_item_beli as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_item_beli;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_item_po($tipe)
	{	


		$join1 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id', 'left');
		$join2 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		$join3 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');

		$join_tables = array($join1, $join2, $join3);

		if ($tipe == 1) {
			$tipe = array('1');
		}

		if ($tipe == 2) {
			$tipe = array('2','3');
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);
		$params['sort_by'] = 'item.id';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if ($tipe == null)
		{
			$this->db->where('item_satuan.is_primary', 1);
			
			$this->db->group_by('item.id');
		} else {

			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item.is_active', 1);
			$this->db->where_in('item_sub_kategori.tipe', $tipe);
			$this->db->group_by('item.id');
			
		}
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $this->db->where($wheres);
		// die(dump($total_records));
		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($wheres);
		if ($tipe == null)
		{
			$this->db->where('item_satuan.is_primary', 1);
			
			$this->db->group_by('item.id');
		} else {

			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item.is_active', 1);
			$this->db->where_in('item_sub_kategori.tipe', $tipe);
			$this->db->group_by('item.id');
		}
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);
		if ($tipe == null)
		{
			$this->db->where('item_satuan.is_primary', 1);
			
			$this->db->group_by('item.id');
		} else {
			$this->db->where('item_satuan.is_primary', 1);
			$this->db->where('item.is_active', 1);
			$this->db->where_in('item_sub_kategori.tipe', $tipe);
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

	public function get_item_order_permintaan_barang_detail($id)
	{

		$format = "SELECT
					item.kode AS kode_item,
					item.nama AS nama_item,
					item_satuan.nama AS nama_satuan,
					order_permintaan_barang_detail.jumlah AS jumlah
					FROM
					order_permintaan_barang_detail
					LEFT JOIN item ON order_permintaan_barang_detail.item_id = item.id
					LEFT JOIN item_satuan ON order_permintaan_barang_detail.item_satuan_id = item_satuan.id
					WHERE
					order_permintaan_barang_detail.order_permintaan_barang_id = '$id'";

		return $this->db->query($format);

	}

	public function get_item_order_permintaan_barang_detail_box($order_permintaan_barang_id, $box_paket_id)
	{

		$format = "SELECT
					item.kode AS kode_item,
					item.nama AS nama_item,
					item_satuan.nama AS nama_satuan,
					order_permintaan_barang_detail.jumlah AS jumlah
					FROM
					order_permintaan_barang_detail
					LEFT JOIN item ON order_permintaan_barang_detail.item_id = item.id
					LEFT JOIN item_satuan ON order_permintaan_barang_detail.item_satuan_id = item_satuan.id
					WHERE
					order_permintaan_barang_detail.order_permintaan_barang_id = '$order_permintaan_barang_id'
					AND
					order_permintaan_barang_detail.box_paket_id = $box_paket_id";

		return $this->db->query($format);

	}

	public function get_item_order_permintaan_barang_detail_other($id)
	{

		$format = "SELECT
					order_permintaan_barang_detail_other.order_permintaan_barang_id,
					order_permintaan_barang_detail_other.nama,
					order_permintaan_barang_detail_other.jumlah,
					order_permintaan_barang_detail_other.satuan
					FROM
					order_permintaan_barang_detail_other
					WHERE
					order_permintaan_barang_detail_other.order_permintaan_barang_id = '$id'";

		return $this->db->query($format);

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

}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/item_m.php */