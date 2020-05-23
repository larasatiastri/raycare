<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_identitas_m extends MY_Model {

	protected $_table        = 'inventory_identitas';
	protected $_order_by     = '';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'cabang.id'         => 'id', 
		'cabang.kode'   => 'kode', 
		'cabang.nama'   => 'nama', 
		'cabang.alamat'   => 'alamat',
		'cabang.nomor_telepon1'     => 'nomor_telepon1',
		'cabang.nomor_telepon2'     => 'nomor_telepon2',
		'cabang.nomor_fax'     => 'nomor_fax',
		'cabang.is_active'     => 'is_active'
		
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
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.id';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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

	public function get_data_identitas($gudang_id, $item_id, $item_satuan_id)
	{
		$format = "SELECT
					inventory_identitas_detail.judul as judul,
					inventory_identitas_detail.`value` as batch_number,
					inventory_identitas.jumlah as jumlah,
					inventory_identitas.inventory_id as inventory_id,
					inventory.pmb_id,
					inventory_identitas.inventory_identitas_id as id
					FROM
					inventory ,
					inventory_identitas ,
					inventory_identitas_detail
					WHERE
					inventory.id = inventory_identitas.inventory_id AND
					inventory_identitas.inventory_identitas_id = inventory_identitas_detail.inventory_identitas_id AND
					inventory.item_id = $item_id AND
					inventory.item_satuan_id = $item_satuan_id AND
					inventory.gudang_id = $gudang_id
				";

		return $this->db->query($format);
	}

	public function update_inventory_identitas($inventory_id, $jumlah)
	{
		$date = date('Y-m-d H:i:s');
		$id = $this->session->userdata('user_id');
		$format = "UPDATE inventory_identitas SET jumlah = $jumlah, modified_by = $id, modified_date = '$date' WHERE inventory_id = $inventory_id";

		return $this->db->query($format);
		// $data['jumlah'] = $jumlah;
		// $this->db->where('inventory_id', $inventory_id);
		// return $this->db->update($this->_table, $data);
	}

	public function delete_inventory_identitas($inventory_id)
	{
		$format = "DELETE FROM inventory_identitas WHERE inventory_id = $inventory_id";

		return $this->db->query($format);
	}

	public function save_inventory_identitas($data, $inventory_identitas_id)
	{
		$this->db->where('inventory_identitas_id', $inventory_identitas_id);
		$this->db->update($this->_table, $data);
	}

	public function delete_inventory_identitas_kosong($inventory_identitas_id){

		$this->db->where('inventory_identitas_id', $inventory_identitas_id);
		$this->db->delete($this->_table);
	}
}

/* End of file cabangm.php */
/* Location: ./application/models/master/cabangm.php */