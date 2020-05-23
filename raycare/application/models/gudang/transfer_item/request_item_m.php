<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_item_m extends MY_Model {

	protected $_table        = 'request_item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'request_item.id'         		=> 'id', 
		'request_item.dari_gudang_id'   => 'dari_gudang_id',
		'request_item.ke_gudang_id'   	=> 'ke_gudang_id',
		'request_item.tanggal'   		=> 'tanggal',
		'request_item.keterangan'   	=> 'keterangan',
		'request_item.status'   		=> 'status',
		'request_item.is_active'   		=> 'is_active',
		'request_item.created_by'   	=> 'created_by',
		'user.nama'   					=> 'dibaca_oleh',
		'gudang.nama'   				=> 'nama_gudang',
		'(SELECT COUNT(request_item_id) from request_item_detail where request_item_detail.request_item_id = request_item.id )' => 'jumlah_request_item_detail',
		// 'request_item_detail.request_item_id' => 'request_item_id',
		// 'request_item_detail.item_id' => 'item_id',
		// 'request_item_detail.item_satuan_id' => 'item_satuan_id',
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

		$join1 = array('user', $this->_table.'.created_by = user.id', 'left');
		$join2 = array('gudang', $this->_table.'.dari_gudang_id = gudang.id', 'left');
		// $join3 = array('request_item_detail', $this->_table.'.id = request_item_detail.request_item_id', 'left');

		$join_tables = array($join1, $join2);

		if($gudang_id == 1) {

			$gudang = 1;

		} elseif ($gudang_id == 2) {

			$gudang = 2;

		} elseif ($gudang_id) {
			
			$gudang = 3;

		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($gudang_id == null)
		{
			$this->db->where('request_item.is_active',1);
			$this->db->where('request_item.`status`',1);
		} else {

			$this->db->where('request_item.is_active',1);
			$this->db->where('request_item.`status`',1);
			$this->db->where('request_item.`ke_gudang_id`',$gudang);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($gudang_id == null)
		{
			$this->db->where('request_item.is_active',1);
			$this->db->where('request_item.`status`',1);
		} else {

			$this->db->where('request_item.is_active',1);
			$this->db->where('request_item.`status`',1);
			$this->db->where('request_item.`ke_gudang_id`',$gudang);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($gudang_id == null)
		{
			$this->db->where('request_item.is_active',1);
			$this->db->where('request_item.`status`',1);
		} else {

			$this->db->where('request_item.is_active',1);
			$this->db->where('request_item.`status`',1);
			$this->db->where('request_item.`ke_gudang_id`',$gudang);
		}

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

	public function get_data_gudang()
	{
		$format = "SELECT id as id, informasi as nama_gudang
					FROM gudang
					WHERE is_active = 1";

		return $this->db->query($format);
	}

	public function get_request_item($request_item_id)
	{

		$sql = "SELECT
					request_item_detail.item_id,
					request_item_detail.item_satuan_id,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS item_satuan,
					SUM(inventory.jumlah) AS stock,
					request_item_detail.jumlah AS jumlah_permintaan,
					item.is_identitas AS is_identitas,
					inventory.gudang_id as gudang_id,
					inventory.pmb_id as pmb_id,
					inventory.harga_beli as harga_beli
				FROM
					request_item_detail
				JOIN item ON item.id = request_item_detail.item_id
				JOIN item_satuan ON item_satuan.id = request_item_detail.item_satuan_id
				JOIN inventory ON inventory.item_id = request_item_detail.item_id
				AND inventory.item_satuan_id = request_item_detail.item_satuan_id
				WHERE
					request_item_detail.request_item_id = $request_item_id
				GROUP BY
					request_item_detail.id";

		return $this->db->query($sql);

	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */