<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retur_pembelian_detail_m extends MY_Model {

	protected $_table        = 'retur_pembelian_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'retur_pembelian_detail.id'      => 'id',
		'retur_pembelian_detail.tanggal'      => 'tanggal',
		'retur_pembelian_detail.no_retur_pembelian_detail' => 'no_retur_pembelian_detail',
		'retur_pembelian_detail.nama_pasien'  => 'nama_pasien',
		'retur_pembelian_detail.alamat_pasien'  => 'alamat_pasien',
		'user.nama'                   => 'nama_dibuat_oleh',
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
		$join1 = array('user', $this->_table.'.created_by = user.id','left');
		$join_tables = array($join1);

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

	// public function get_item_identitas($id)
	// {
	// 	$format = "SELECT
	// 				inventory.id,
	// 				inventory.item_id,
	// 				inventory_identitas.identitas_id,
	// 				inventory_identitas.judul,
	// 				inventory_identitas.`value`,
	// 				inventory_identitas.jumlah,
	// 				identitas.tipe
	// 				FROM
	// 				inventory
	// 				LEFT JOIN inventory_identitas ON inventory.id = inventory_identitas.inventory_id
	// 				LEFT JOIN identitas ON inventory_identitas.identitas_id = identitas.id
	// 				WHERE
	// 				inventory.id = $id";

	// 	return $this->db->query($format);
	// }
	
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

	public function get_datatable_retur_pembelian_detail($status_so_history)
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

	public function get_max_id_retur_pembelian_detail()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `retur_pembelian_detail` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data($retur_pembelian_id)
	{
		$format = "SELECT
						retur_pembelian_detail.id,
						retur_pembelian_detail.retur_pembelian_id,
						retur_pembelian_detail.item_id,
						retur_pembelian_detail.item_satuan_id,
						retur_pembelian_detail.hpp,
						retur_pembelian_detail.bn_sn_lot,
						retur_pembelian_detail.expire_date,
						item.kode as kode,
						item.nama as nama,
						item_satuan.nama as nama_satuan,
						SUM(retur_pembelian_detail.jumlah) as jumlah,
						SUM(retur_pembelian_detail.jumlah_belum_diterima) as jumlah_belum_diterima,
						SUM(retur_pembelian_detail.jumlah_diterima) as jumlah_diterima
						FROM
						retur_pembelian_detail JOIN item
						ON retur_pembelian_detail.item_id = item.id JOIN item_satuan
						ON retur_pembelian_detail.item_satuan_id = item_satuan.id
						WHERE retur_pembelian_detail.retur_pembelian_id = '$retur_pembelian_id'
						GROUP BY retur_pembelian_detail.item_id,retur_pembelian_detail.item_satuan_id,retur_pembelian_detail.bn_sn_lot,retur_pembelian_detail.expire_date";

		return $this->db->query($format);
	}

	public function get_data_detail($id, $item_id)
	{
		$format = "SELECT id FROM `retur_pembelian_detail` WHERE item_id = $item_id AND retur_pembelian_id in ('$id')";	
		return $this->db->query($format);
	}

	public function get_data_item($retur_id, $item_id)
	{
		$retur_id = rtrim($retur_id,',');

		$format = "SELECT
					retur_pembelian_detail.id,
					retur_pembelian_detail.retur_pembelian_id,
					retur_pembelian_detail.item_id,
					retur_pembelian_detail.item_satuan_id,
					(SUM(retur_pembelian_detail.jumlah)) as jumlah,
					(SUM(jumlah_diterima)) as jumlah_diterima,
					(SUM(jumlah_belum_diterima)) as jumlah_belum_diterima,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS satuan_nama,
					retur_pembelian.no_retur as no_retur,
					retur_pembelian_detail.hpp as hpp
					FROM
					retur_pembelian_detail
					LEFT JOIN item ON retur_pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON retur_pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN retur_pembelian ON retur_pembelian_detail.retur_pembelian_id = retur_pembelian.id
					WHERE
					retur_pembelian_detail.retur_pembelian_id in ('$retur_id')
					AND retur_pembelian_detail.item_id = $item_id
					GROUP BY retur_pembelian_detail.item_id, retur_pembelian_detail.item_satuan_id
					ORDER BY retur_pembelian_detail.id";
					
		return $this->db->query($format);
	}

	public function get_data_belumterima($retur_id, $item_id)
	{
		$SQL = "SELECT * FROM retur_pembelian_detail WHERE retur_pembelian_id IN ('$retur_id') AND item_id = $item_id AND jumlah_belum_diterima != 0 ORDER BY retur_pembelian_id ASC";
		return $this->db->query($SQL);
	}

}

