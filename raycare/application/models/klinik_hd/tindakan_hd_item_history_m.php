<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_item_history_m extends MY_Model {

	protected $_table        = 'tindakan_hd_item_history';
	protected $_order_by     = 'waktu desc';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_hd_item_history.waktu'                  => 'waktu',
		'item.nama'                               => 'item_nama',
		'tindakan_hd_item_history.tipe_pemberian'         => 'tipe_pemberian',
		'tindakan_hd_item_history.jumlah'	              => 'jumlah',
		'tindakan_hd_item_history.item_satuan_id'         => 'item_satuan_id',
		'item_satuan.nama'                        => 'item_satuan_nama',
		'user.nama'                               => 'user_nama',
		'tindakan_hd_item_history.id'                     => 'id',
		'tindakan_hd_item_history.item_id'                => 'item_id',
		'tindakan_hd_item_history.bn_sn_lot'                => 'bn_sn_lot',
		'tindakan_hd_item_history.expire_date'                => 'expire_date',
		'tindakan_hd_item_history.transaksi_pemberian_id' => 'transaksi_pemberian_id',
		'tindakan_hd_item_history.created_by'             => 'created_by',
		'item.is_identitas'						=> 'is_identitas',
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tindakan_id)
	{		
		$join1 = array('user', $this->_table.'.user_id = user.id', 'left');
		$join2 = array('item', $this->_table.'.item_id = item.id', 'left');
		$join3 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable

		
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan_hd_item_history.waktu';
		$params['sort_dir'] = 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tindakan_hd_item_history.tindakan_hd_id', $tindakan_id );
		$this->db->where('tindakan_hd_item_history.is_active', 1 );
		$this->db->where('tindakan_hd_item_history.jumlah !=', 0 );
		
		// dapatkan total row count;
		//$query = $this->db->select('(1)')->get();
        //$total_records = $query->num_rows();
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tindakan_hd_item_history.tindakan_hd_id', $tindakan_id );
		$this->db->where('tindakan_hd_item_history.is_active', 1 );
		$this->db->where('tindakan_hd_item_history.jumlah !=', 0 );
		

		// dapatkan total record filtered/search;
		//$query = $this->db->select('(1)')->get();
        //$total_display_records = $query->num_rows();
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('tindakan_hd_item_history.tindakan_hd_id', $tindakan_id );
		$this->db->where('tindakan_hd_item_history.is_active', 1 );
		$this->db->where('tindakan_hd_item_history.jumlah !=', 0 );
		
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


	// digunakan di menu transaksi perawat > observasi dialisis 
	// dibuat oleh Abu Rizal
	public function get_datatable_diluar_paket($gudang_id, $kategori)
	{		
		$join1 = array('gudang', $this->_table.'.gudang_id = gudang.id', 'left');
		$join2 = array('item', 'inventory.item_id = item.id', 'left');
		$join4 = array('item_sub_kategori', 'item_sub_kategori.id = item.item_sub_kategori', 'left');
		$join5 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		$join_tables = array($join1, $join2, $join4, $join5);

		// get params dari input postnya datatable

		if ($gudang_id == 0) {
			$wheres = array(
				'gudang.is_active' => 1
			);
		}else{
			$wheres = array(
				'gudang.is_active'    => 1,
				'inventory.gudang_id' => $gudang_id
			);
		}

		if ($kategori == 0) {
			$wheres2 = array(
				'item_kategori.is_active' => 1
			);
		}else{
			$wheres2 = array(
				'item_kategori.is_active'   => 1,
				'item_kategori.id' 			=> $kategori
			);
		}
		
		$params = $this->datatable_param($this->datatable_columns_diluar_paket);
		$params["sort_by"] = "inventory.inventory_id";
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where($wheres2);
		$this->db->group_by('inventory.item_id, gudang.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where($wheres2);
		$this->db->group_by('inventory.item_id, gudang.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where($wheres2);
		$this->db->group_by('inventory.item_id, gudang.id');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_diluar_paket as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_diluar_paket;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	
	public function get_data_group($tindakan_hd_id, $item_id, $item_satuan_id, $harga_beli)
	{

		$format = "SELECT * FROM
						tindakan_hd_item_history
					WHERE
						tindakan_hd_item_history.tindakan_hd_id = ".$tindakan_hd_id."
					AND tindakan_hd_item_history.item_id = ".$item_id."
					AND tindakan_hd_item_history.item_satuan_id = ".$item_satuan_id."
					AND tindakan_hd_item_history.harga_beli = ".$harga_beli."
				";

		return $this->db->query($format)->result_array();
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
					inventory_identitas.inventory_id = $id
					";

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

	public function get_is_assessment($tindakan_hd_id)
	{
		$format="SELECT tindakan_hd_item_history.id, item.nama, item.is_show_assessment
					FROM tindakan_hd_item_history
					JOIN  item ON tindakan_hd_item_history.item_id = item.id
					WHERE tindakan_hd_item_history.tindakan_hd_id = ".$tindakan_hd_id."
					AND item.is_show_assessment = 1 AND item.is_active = 1 AND tindakan_hd_item_history.jumlah != 0
					GROUP BY item.id";

		return $this->db->query($format);
	}

	public function get_sum_item($tindakan_hd_id)
	{
		$SQL = 'SELECT * FROM `tindakan_hd_item_history` where tindakan_hd_id = ? and jumlah != 0 and item_id IN '.config_item('dialyzer_id').' and is_active = 1';
		return $this->db->query($SQL, array($tindakan_hd_id));
	}

	public function get_item_invoice($wheres)
	{
		$this->db->select('SUM(jumlah) AS jumlah, item_id, item_satuan_id, harga_beli, harga_jual');
		$this->db->join('item','tindakan_hd_item_history.item_id = item.id');
		$this->db->where($wheres);
		$this->db->group_by('item_id, item_satuan_id');

		return $this->db->get($this->_table);
	}

	public function get_item_dialyzer_reuse($tindakan_hd_id)
	{
		$this->db->where('tindakan_hd_id',$tindakan_hd_id);
		$this->db->where('is_active', 1);
		$this->db->where('tipe_pemberian', 2);
		$this->db->where('item_id IN '.config_item('dialyzer_id'));

		return $this->db->get($this->_table)->result_array();
	}
	public function get_item_dialyzer_new($tindakan_hd_id)
	{
		$this->db->where('tindakan_hd_id',$tindakan_hd_id);
		$this->db->where('is_active', 1);
		$this->db->where('tipe_pemberian', 3);
		$this->db->where('item_id IN '.config_item('dialyzer_id'));

		return $this->db->get($this->_table)->result_array();
	}
	public function get_item_not_dialyzer($tindakan_hd_id)
	{
		$this->db->where('tindakan_hd_id',$tindakan_hd_id);
		$this->db->where('is_active', 1);
		$this->db->where('tipe_pemberian !=', 1);
		$this->db->where('item_id NOT IN '.config_item('dialyzer_id'));

		return $this->db->get($this->_table)->result_array();
	}

	public function get_item_bpjs($tindakan_hd_id)
	{
		$this->db->where('tindakan_hd_id',$tindakan_hd_id);
		$this->db->where('is_active', 1);
		$this->db->where('tipe_pemberian !=', 1);
		$this->db->where('item_id NOT IN '.config_item('dialyzer_id'));
		$this->db->where('item_id IN (SELECT item_id FROM item_klaim)');

		return $this->db->get($this->_table)->result_array();
	}

	public function get_item_swasta($tindakan_hd_id)
	{
		$this->db->where('tindakan_hd_id',$tindakan_hd_id);
		$this->db->where('is_active', 1);
		$this->db->where('tipe_pemberian !=', 1);
		$this->db->where('item_id NOT IN '.config_item('dialyzer_id'));
		$this->db->where('item_id NOT IN (SELECT item_id FROM item_klaim)');

		return $this->db->get($this->_table)->result_array();
	}

}

