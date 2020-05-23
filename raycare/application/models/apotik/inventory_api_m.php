<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_api_m extends MY_Model {

	protected $_table        = 'inventory_api';
	protected $_order_by     = 'inventory_api_id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'inventory_api.inventory_api_id'    => 'id',
		'inventory_api.gudang_api_id'           => 'gudang_api_id',
		'inventory_api.pmb_id'              => 'pmb_id',
		'inventory_api.item_id'             => 'item_id',
		'inventory_api.item_satuan_id'      => 'satuan_id',
		'inventory_api.jumlah'              => 'jumlah',
		'inventory_api.harga_beli'          => 'harga_beli',
		'gudang.nama'                       => 'nama_gudang',
		'item.kode'                         => 'item_kode',
		'item.nama'                         => 'item_nama',
		'item_satuan.nama'                  => 'satuan',
		'gudang.is_active'                  => 'is_active',
		'item_harga.harga'                  => 'harga'
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'inventory_api.gudang_api_id'        => 'gudang_api_id',
		'inventory_api.inventory_api_id' => 'inventory_api_id',
		'inventory_api.pmb_id'           => 'pmb_id',
		'inventory_api.item_id'          => 'item_id',
		'inventory_api.item_satuan_id'   => 'satuan_id',
		'inventory_api.jumlah'           => 'jumlah',
		'inventory_api.harga_beli'       => 'harga',
		'gudang.nama'                    => 'nama_gudang',
		'item.kode'                      => 'kode', 
		'item.nama'                      => 'nama', 
		'item_satuan.nama'               => 'unit',
		'item_harga.harga'               => 'harga',
		'item_kategori.id'               => 'item_kategori_id',
		'item.keterangan'                => 'keterangan',
		'item.id'                        => 'id', 
		'item_kategori.nama'             => 'kategori_item',
		'item.item_sub_kategori'         => 'item_sub_kategori'

	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($gudang_api_id)
	{		
		$join1 = array('gudang', $this->_table.'.gudang_api_id = gudang.id', 'left');
		$join2 = array('item', 'inventory_api.item_id = item.id', 'left');
		$join3 = array('item_satuan', 'inventory_api.item_satuan_id = item_satuan.id', 'left');
		$join4 = array('item_harga', 'item_satuan.id = item_harga.item_satuan_id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable

		if ($gudang_api_id == null) {
			$wheres = array(
				'gudang.is_active' => 1
			);
		}else{
			$wheres = array(
				'gudang.is_active'    => 1,
				'inventory_api.gudang_api_id' => $gudang_api_id
			);
		}
		
		$params = $this->datatable_param($this->datatable_columns);

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
	// 				inventory_api.id,
	// 				inventory_api.item_id,
	// 				inventory_api_identitas.identitas_id,
	// 				inventory_api_identitas.judul,
	// 				inventory_api_identitas.`value`,
	// 				inventory_api_identitas.jumlah,
	// 				identitas.tipe
	// 				FROM
	// 				inventory_api
	// 				LEFT JOIN inventory_api_identitas ON inventory_api.id = inventory_api_identitas.inventory_api_id
	// 				LEFT JOIN identitas ON inventory_api_identitas.identitas_id = identitas.id
	// 				WHERE
	// 				inventory_api.id = $id";

	// 	return $this->db->query($format);
	// }
	
	public function get_item_satuan($item_id)
	{

		$format = "SELECT *
						FROM 
						inventory_api
						LEFT JOIN item_satuan on inventory_api.item_satuan_id = item_satuan.id
						WHERE inventory_api.item_id =  '$item_id'
						
						GROUP BY inventory_api.item_satuan_id
						ORDER BY 'inventory_api_id'";

		return $this->db->query($format);
	}


	public function get_item_identitas($id)
	{
		$format = "SELECT
					inventory_api_identitas.inventory_api_identitas_id as inventory_api_identitas_id,
					inventory_api_identitas.inventory_api_id,
					inventory_api_identitas.jumlah,
					inventory_api_identitas_detail.identitas_id,
					inventory_api_identitas_detail.judul,
					inventory_api_identitas_detail.`value`,
					identitas.tipe,
					inventory_api.harga_beli,
					inventory_api.jumlah as jumlah_inventory_api,
					inventory_api.gudang_api_id as gudang_api_id, 
					inventory_api.gudang_api_id as pmb_id

					FROM
					inventory_api_identitas
					LEFT JOIN inventory_api_identitas_detail ON inventory_api_identitas.inventory_api_identitas_id = inventory_api_identitas_detail.inventory_api_identitas_id
					LEFT JOIN identitas ON inventory_api_identitas_detail.identitas_id = identitas.id
					LEFT JOIN inventory_api ON inventory_api_identitas.inventory_api_id = inventory_api.inventory_api_id
					WHERE
					inventory_api_identitas.inventory_api_identitas_id = $id
					";

		return $this->db->query($format);
	}

	public function update_jumlah_inventory_api($jumlah,$modified_by, $modified_date, $id){
		$format = "UPDATE inventory_api SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_api_id =  $id";
		return $this->db->query($format);
	}

	// Created by Abu for update jumlah in inventory_api
	public function save_inventory_api($data, $inventory_api_id){

		$this->db->where('inventory_api_id', $inventory_api_id);
		$this->db->update($this->_table, $data);
	}

	// Created by Abu for delete jumlah in inventory_api = 0
	public function delete_inventory_api_kosong($inventory_api_id){

		$this->db->where('inventory_api_id', $inventory_api_id);
		$this->db->delete($this->_table);
	}

	public function cek_identitas($inventory_api_id)
	{
		$format = "SELECT *
					FROM `inventory_api_identitas`
					WHERE
					inventory_api_identitas.inventory_api_id = $inventory_api_id
					";

		return $this->db->query($format);
	}

	public function delete_inventory_api($inventory_api_id)
	{
		$format = "DELETE FROM inventory_api WHERE inventory_api_id = $inventory_api_id";

		return $this->db->query($format);
	}

	public function get_last_id()
	{
		$format = "SELECT MAX(inventory_api_id) FROM inventory_api";

		return $this->db->query($format);
	}
	public function get_max_id()
	{
		$format = "SELECT MAX(inventory_api_id) as max_id FROM inventory_api";

		return $this->db->query($format);
	}

	public function insert_inventory_api($id, $gudang_api_id, $item_id, $item_satuan_id, $jumlah, $tanggal){

		$id_user = $this->session->userdata('user_id');
		$format="INSERT INTO inventory_api(inventory_api_id, gudang_api_id, pmb_id, item_id, item_satuan_id, jumlah, tanggal_datang, created_by, created_date)
				 VALUE ($id, $gudang_api_id, '0', $item_id, $item_satuan_id, $jumlah, '$tanggal', $id_user, '$tanggal')";

		return $this->db->query($format);
	}

	public function get_fifo($gudang_api_id, $item_id, $item_satuan_id)
	{
		$sql = "SELECT
				inventory_api.inventory_api_id,
				inventory_api.jumlah
				FROM
				inventory_api
				WHERE
				gudang_api_id = $gudang_api_id AND
				item_id = $item_id AND
				item_satuan_id = '$item_satuan_id'
				ORDER BY
				tanggal_datang";

		return $this->db->query($sql);
	}

	public function update_inventory_api($id, $jumlah, $modified_date){

		$modified_by= $this->session->userdata('user_id');
		$format = "UPDATE inventory_api SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_api_id =  $id";
		return $this->db->query($format);
	}

	public function save_data($jumlah, $inventory_api_id)
	{
		$user = $this->session->userdata('user_id');
		$modified_date = date('Y-m-d H:i:s');
		$format = "UPDATE inventory_api SET jumlah = $jumlah, modified_by = $user, modified_date = '$modified_date' WHERE inventory_api_id = $inventory_api_id";

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

	public function get_datatable_penjualan_obat($status_so_history)
	{	

		$join1 = array('item', $this->_table.'.item_id = item.id', 'left');
		$join2 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id', 'left');
		$join3 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		$join4 = array('item_harga', 'item_harga.item_id = item.id', 'left');
		$join5 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');
		$join6 = array('gudang', $this->_table.'.gudang_api_id = gudang.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);
		
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
		// die_dump($params);
		$params["sort_by"] = "inventory_api.inventory_api_id";
		$params['sord_dir'] = "asc";
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_kategori_id', 1);
			$this->db->where('inventory_api.gudang_api_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('inventory_api.gudang_api_id', 2);
			$this->db->or_where('inventory_api.gudang_api_id', 3);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_kategori_id', $kategori);
			$this->db->where('inventory_api.gudang_api_id', $kategori);
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
			$this->db->where('inventory_api.gudang_api_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('inventory_api.gudang_api_id', 2);
			$this->db->or_where('inventory_api.gudang_api_id', 3);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_kategori_id', $kategori);
			$this->db->where('inventory_api.gudang_api_id', $kategori);
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
			$this->db->where('inventory_api.gudang_api_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('inventory_api.gudang_api_id', 2);
			$this->db->or_where('inventory_api.gudang_api_id', 3);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_kategori_id', $kategori);
			$this->db->where('inventory_api.gudang_api_id', $kategori);
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

	public function get_nomor_penjualan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_penjualan`,12,3)) AS max_nomor_penjualan FROM `penjualan_obat` WHERE SUBSTRING(`no_penjualan`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

	public function delete_inventory_api_id($inventory_api_id)
	{

		$this->db->where('inventory_api_id', $inventory_api_id);
		$this->db->delete($this->_table);		
	}

	public function get_nilai_konversi($item_satuan_id) {


		$sql = "SELECT * FROM item_satuan WHERE parent_id = '$item_satuan_id' ";
    	$return = $this->db->query($sql)->result_array();

    	if ($return) {
    		
    		$id = $return[0]['id'];

    		$nilai_konversi[0]['id'] =  $return[0]['id'];
    		$nilai_konversi[0]['jumlah'] =  $return[0]['jumlah'];
    		$nilai_konversi[0]['nama'] = $return[0]['nama'];

    		$jumlah_konversi =  $return[0]['jumlah'];

    		$i = 1;
    		while ($id != NULL) 
	    	{
				$format = "SELECT * FROM item_satuan WHERE parent_id = '$id'";
				$return = $this->db->query($format)->result_array();

	    		if (empty($return)) {
	    			$nilai_konversi = $nilai_konversi;
	    			break;
	    		} else{

	    			$jumlah_konversi = $jumlah_konversi * $return[0]['jumlah'];

    				$nilai_konversi[$i]['id'] =  $return[0]['id'];
	    			$nilai_konversi[$i]['jumlah'] = $jumlah_konversi;
	    			$nilai_konversi[$i]['nama'] = $return[0]['nama'];
    				$id = $return[0]['id'];
	    		}
	    		$i++;
	    	}

    	} else {
    		$sql = "SELECT * FROM item_satuan WHERE id = '$item_satuan_id' ";
    		$return = $this->db->query($sql)->result_array();

    		$nilai_konversi[0]['id'] =  $return[0]['id'];
    		$nilai_konversi[0]['jumlah'] = 1;
    		$nilai_konversi[0]['nama'] = $return[0]['nama'];
    	}

    	return $nilai_konversi;
	}

	public function get_data_inventory_api($item_id)
	{
		$SQL = "SELECT item.id as item_id, item.kode as item_kode, item.nama as item_nama, item_satuan.id as item_satuan_id, item_satuan.nama as nama_satuan, inventory_api.inventory_api_id, SUM(inventory_api.jumlah) as jumlah, inventory_api.bn_sn_lot, inventory_api.expire_date FROM inventory_api LEFT JOIN item ON inventory_api.item_id = item.id LEFT JOIN item_satuan ON inventory_api.item_satuan_id = item_satuan.id WHERE inventory_api.item_id = '$item_id' GROUP BY inventory_api.item_id, inventory_api.item_satuan_id, inventory_api.bn_sn_lot, inventory_api.expire_date ORDER BY inventory_api.item_id asc, inventory_api.expire_date asc";

		return $this->db->query($SQL);
	}

	public function get_by_item($item_id)
	{
		$this->db->where('item_id', $item_id);
		$this->db->group_by('bn_sn_lot');

		return $this->db->get($this->_table);
	}

}

