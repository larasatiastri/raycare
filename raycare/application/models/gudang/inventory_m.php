<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_m extends MY_Model {

	protected $_table        = 'inventory';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'inventory.id'             => 'id',
		'inventory.gudang_id'      => 'gudang_id',
		'inventory.item_id'        => 'item_id',
		'inventory.item_satuan_id' => 'satuan_id',
		'inventory.jumlah'         => 'jumlah',
		'inventory.harga_beli'     => 'harga',
		'gudang.nama'              => 'nama_gudang',
		'item.kode'                => 'item_kode',
		'item.nama'                => 'item_nama',
		'item_satuan.nama'         => 'satuan',
		'gudang.is_active'		   => 'is_active'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($gudang_id)
	{		
		$join1 = array('gudang', $this->_table.'.gudang_id = gudang.id', 'left');
		$join2 = array('item', 'inventory.item_id = item.id', 'left');
		$join3 = array('item_satuan', 'inventory.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable

		if ($gudang_id == null) {
			$wheres = array(
				'gudang.is_active' => 1
			);
		}else{
			$wheres = array(
				'gudang.is_active'    => 1,
				'inventory.gudang_id' => $gudang_id
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

	public function get_item_identitas($id)
	{
		$format = "SELECT
					inventory.id,
					inventory.item_id,
					inventory_identitas.identitas_id,
					inventory_identitas.judul,
					inventory_identitas.`value`,
					inventory_identitas.jumlah,
					identitas.judul,
					identitas.tipe
					FROM
					inventory
					LEFT JOIN inventory_identitas ON inventory.id = inventory_identitas.inventory_id
					LEFT JOIN identitas ON inventory_identitas.identitas_id = identitas.id
					WHERE
					inventory.id = $id";

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

	public function get_data_identitas($id)
	{
		$format = "SELECT
					inventory_identitas.inventory_identitas_id as inventory_identitas_id,
					inventory_identitas.inventory_id,
					inventory_identitas.jumlah,
					inventory_identitas_detail.identitas_id,
					inventory_identitas_detail.judul,
					inventory_identitas_detail.`value`,
					identitas.tipe,
					inventory.inventory_id,
					inventory.gudang_id,
					inventory.pmb_id,
					inventory.tanggal_datang,
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

	public function get_fifo($gudang_id, $item_id, $item_satuan_id)
	{
		$sql = "SELECT
				inventory.inventory_id,
				inventory.jumlah
				FROM
				inventory
				WHERE
				gudang_id = $gudang_id AND
				item_id = '$item_id' AND
				item_satuan_id = '$item_satuan_id'
				ORDER BY
				tanggal_datang";

		return $this->db->query($sql);
	}

	public function update_inventory($id, $jumlah, $modified_date){

		$modified_by= $this->session->userdata('user_id');
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_id =  $id";
		return $this->db->query($format);
	}

	public function save_data($jumlah, $inventory_id)
	{
		$user = $this->session->userdata('user_id');
		$modified_date = date('Y-m-d H:i:s');
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $user, modified_date = '$modified_date' WHERE inventory_id = $inventory_id";

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

	public function get_stok($item_id, $item_satuan_id)
	{
		// SELECT SUM(jumlah) FROM inventory WHERE item_id = 1 AND item_satuan_id = 1 AND box_paket_id IS NULL GROUP BY item_id, item_satuan_id 
		$this->db->select('SUM(jumlah) as stok');
		$this->db->where('item_id', $item_id);
		$this->db->where('item_satuan_id', $item_satuan_id);
		$this->db->where('box_paket_id IS NULL');
		$this->db->group_by('item_id, item_satuan_id');

		return $this->db->get($this->_table)->result_array();
	}

}

