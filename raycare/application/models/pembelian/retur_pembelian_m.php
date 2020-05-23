<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retur_pembelian_m extends MY_Model {

	protected $_table        = 'retur_pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'retur_pembelian.tanggal'      => 'tanggal',
		'supplier.id' 				=> 'supplier_id',
		'supplier.kode' 			=> 'supplier_kode',
		'supplier.nama' 			=> 'supplier_nama',
		'retur_pembelian.no_retur' => 'no_retur',
		'retur_pembelian.no_surat_jalan' => 'no_surat_jalan',
		'retur_pembelian.tipe' => 'tipe',
		'retur_pembelian.nominal' => 'nominal',
		'retur_pembelian.keterangan'  => 'keterangan',
		'user.nama'                   => 'nama_dibuat_oleh',
		'retur_pembelian.status'  => 'status',
		'retur_pembelian.id'      => 'id',

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
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id','left');
		$join2 = array('user', $this->_table.'.created_by = user.id','left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable

		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'desc';
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

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_akan_datang($tipe_po)
	{		
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id','left');
		$join2 = array('user', $this->_table.'.created_by = user.id','left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable

		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe', 1);
		$this->db->where($this->_table.'.status', 1);
		$this->db->where($this->_table.'.tipe_po', $tipe_po);
		$this->db->or_where($this->_table.'.tipe', 1);
		$this->db->where($this->_table.'.status', 2);
		$this->db->where($this->_table.'.tipe_po', $tipe_po);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe', 1);
		$this->db->where($this->_table.'.status', 1);
		$this->db->where($this->_table.'.tipe_po', $tipe_po);
		$this->db->or_where($this->_table.'.tipe', 1);
		$this->db->where($this->_table.'.status', 2);
		$this->db->where($this->_table.'.tipe_po', $tipe_po);


		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe', 1);
		$this->db->where($this->_table.'.status', 1);
		$this->db->where($this->_table.'.tipe_po', $tipe_po);
		$this->db->or_where($this->_table.'.tipe', 1);
		$this->db->where($this->_table.'.status', 2);
		$this->db->where($this->_table.'.tipe_po', $tipe_po);


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

	
	public function get_item_satuan($item_id)
	{

		$format = "SELECT *
						FROM 
						inventory
						LEFT JOIN item_satuan on inventory.item_satuan_id = item_satuan.id
						WHERE inventory.item_id =  $item_id
						
						GROUP BY inventory.item_satuan_id
						ORDER BY 'inventory_id'";

		return $this->db->query($format);
	}


	public function get_item_identitas($id)
	{
		$format = "SELECT
					inventory_identitas.inventory_identitas_id as inventory_identitas_id,
					inventory_identitas.inventory_id,
					inventory_identitas.jumlah,
					inventory_identitas_detail.identitas_id,
					inventory_identitas_detail.judul,
					inventory_identitas_detail.`value`,
					identitas.tipe,
					inventory.harga_beli,
					inventory.jumlah as jumlah_inventory
					FROM
					inventory_identitas
					LEFT JOIN inventory_identitas_detail ON inventory_identitas.inventory_identitas_id = inventory_identitas_detail.inventory_identitas_id
					LEFT JOIN identitas ON inventory_identitas_detail.identitas_id = identitas.id
					LEFT JOIN inventory ON inventory_identitas.inventory_id = inventory.inventory_id
					WHERE
					inventory_identitas.inventory_identitas_id = $id
					";

		return $this->db->query($format);
	}

	public function update_jumlah_inventory($jumlah,$modified_by, $modified_date, $id){
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_id =  $id";
		return $this->db->query($format);
	}

	public function cek_identitas($inventory_id)
	{
		$format = "SELECT *
					FROM `inventory_identitas`
					WHERE
					inventory_identitas.inventory_id = $inventory_id
					";

		return $this->db->query($format);
	}

	public function delete_inventory($inventory_id)
	{
		$format = "DELETE FROM inventory WHERE id = $inventory_id";

		return $this->db->query($format);
	}

	public function get_last_id()
	{
		$format = "SELECT MAX(id) FROM inventory";

		return $this->db->query($format);
	}

	public function insert_inventory($id, $gudang_id, $item_id, $item_satuan_id, $jumlah, $tanggal){

		$id_user = $this->session->userdata('user_id');
		$format="INSERT INTO inventory(id, gudang_id, item_id, item_satuan_id, jumlah, tanggal_datang, created_by, created_date)
				 VALUE ($id, $gudang_id, $item_id, $item_satuan_id, $jumlah, '$tanggal', $id_user, '$tanggal')";

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

	public function get_datatable_retur_pembelian($status_so_history)
	{	

		$join1 = array('item', $this->_table.'.item_id = item.id', 'left');
		$join2 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id', 'left');
		$join3 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		$join4 = array('item_harga', 'item_harga.item_id = item.id', 'left');
		$join5 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');
		$join6 = array('gudang', $this->_table.'.gudang_id = gudang.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);
		
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
		// die_dump($params);
		$params["sort_by"] = "inventory.inventory_id";
		$params['sord_dir'] = "asc";
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->group_by('id', 'asc');


		} else {

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
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->group_by('id', 'asc');


		} else {

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
			$this->db->where('item_kategori_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->group_by('id', 'asc');


		} else {
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

	public function get_no_retur_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`no_retur`,6,3)) AS max_no_retur FROM `retur_pembelian` WHERE RIGHT(`no_retur`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_no_surat_jalan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_surat_jalan`,5,3)) AS max_no_surat_jalan FROM `retur_pembelian` WHERE RIGHT(`no_surat_jalan`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_retur_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `retur_pembelian` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}

