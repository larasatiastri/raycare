<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tanda_terima_faktur_detail_m extends MY_Model {

	protected $_table        = 'tanda_terima_faktur_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'tanda_terima_faktur_detail.id'                     => 'id', 
		'tanda_terima_faktur_detail.tanda_terima_faktur_id' => 'tanda_terima_faktur_id', 
		'tanda_terima_faktur_detail.no_berkas'              => 'no_berkas', 
		'tanda_terima_faktur_detail.nominal'                => 'nominal',
		'tanda_terima_faktur_detail.keterangan'             => 'keterangan',
		'tanda_terima_faktur_detail.is_active'              => 'is_active'
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

		$join1 = array('user', $this->_table.'.created_by = user.id', 'left');
		// $join2 = array('gudang dari', $this->_table.'.dari_gudang_id = dari.id', 'left');
		// $join3 = array('gudang ke', $this->_table.'.ke_gudang_id = ke.id', 'left');
		// $join3 = array('tanda_terima_faktur_detail', $this->_table.'.id = tanda_terima_faktur_detail.tanda_terima_faktur_id', 'left');

		$join_tables = array($join1);

		// if($gudang_id == 1) {

		// 	$gudang = 1;

		// } elseif ($gudang_id == 2) {

		// 	$gudang = 2;

		// } elseif ($gudang_id) {
			
		// 	$gudang = 3;

		// }

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// if($gudang_id == null)
		// {
		// 	$this->db->where('tanda_terima_faktur.is_active',1);
		// } else {

		// 	$this->db->where('tanda_terima_faktur.`ke_gudang_id`',$gudang);
		// }
		$this->db->where('tanda_terima_faktur.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// if($gudang_id == null)
		// {
		// 	$this->db->where('tanda_terima_faktur.is_active',1);
		// } else {

		// 	$this->db->where('tanda_terima_faktur.`ke_gudang_id`',$gudang);
		// }
		$this->db->where('tanda_terima_faktur.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// if($gudang_id == null)
		// {
		// 	$this->db->where('tanda_terima_faktur.is_active',1);
		// } else {

		// 	$this->db->where('tanda_terima_faktur.`ke_gudang_id`',$gudang);
		// }

		$this->db->where('tanda_terima_faktur.is_active',1);
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

	// public function get_data_gudang()
	// {
	// 	$format = "SELECT id as id, informasi as nama_gudang
	// 				FROM gudang
	// 				WHERE is_active = 1";

	// 	return $this->db->query($format);
	// }

	public function get_tanda_terima_faktur($tanda_terima_faktur_id)
	{

		$sql = "SELECT
					tanda_terima_faktur_detail.item_id,
					tanda_terima_faktur_detail.item_satuan_id,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS item_satuan,
					SUM(inventory.jumlah) AS stock,
					tanda_terima_faktur_detail.jumlah AS jumlah_permintaan,
					item.is_identitas AS is_identitas,
					inventory.gudang_id as gudang_id,
					inventory.pmb_id as pmb_id,
					inventory.harga_beli as harga_beli
				FROM
					tanda_terima_faktur_detail
				JOIN item ON item.id = tanda_terima_faktur_detail.item_id
				JOIN item_satuan ON item_satuan.id = tanda_terima_faktur_detail.item_satuan_id
				JOIN inventory ON inventory.item_id = tanda_terima_faktur_detail.item_id
				AND inventory.item_satuan_id = tanda_terima_faktur_detail.item_satuan_id
				WHERE
					tanda_terima_faktur_detail.tanda_terima_faktur_id = $tanda_terima_faktur_id
				GROUP BY
					tanda_terima_faktur_detail.id";

		return $this->db->query($sql);

	}

	public function get_data($id)
	{
		$sql = "SELECT
				tanda_terima_faktur_detail.tanda_terima_faktur_id AS tanda_terima_faktur_id,
				tanda_terima_faktur_detail.item_id AS item_id,
				tanda_terima_faktur_detail.item_satuan_id AS item_satuan_id,
				tanda_terima_faktur_detail.jumlah AS jumlah,
				item_satuan.nama AS nama_satuan,
				item.kode AS item_kode,
				item.nama AS item_nama,
				tanda_terima_faktur.dari_gudang_id AS dari_gudang_id,
				Sum(inventory.jumlah) AS stock,
				inventory.inventory_id
				FROM
				tanda_terima_faktur_detail
				LEFT JOIN tanda_terima_faktur ON tanda_terima_faktur_detail.tanda_terima_faktur_id = tanda_terima_faktur.id
				LEFT JOIN item ON tanda_terima_faktur_detail.item_id = item.id
				LEFT JOIN item_satuan ON tanda_terima_faktur_detail.item_satuan_id = item_satuan.id
				LEFT JOIN inventory ON tanda_terima_faktur.dari_gudang_id = inventory.gudang_id AND tanda_terima_faktur_detail.item_id = inventory.item_id AND tanda_terima_faktur_detail.item_satuan_id = inventory.item_satuan_id
				WHERE
				tanda_terima_faktur_detail.tanda_terima_faktur_id = $id
				";
		return $this->db->query($sql);
	}

	public function get_max_id_tanda_terima_faktur_detail()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `tanda_terima_faktur_detail` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */