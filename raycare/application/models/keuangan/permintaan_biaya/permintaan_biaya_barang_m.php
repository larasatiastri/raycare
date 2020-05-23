<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_biaya_barang_m extends MY_Model {

	protected $_table        = 'permintaan_biaya_barang';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
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

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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

	public function get_max_id()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `permintaan_biaya_barang` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data_group($tipe)
	{
		if ($tipe == 1) {
			$tipe = '(1)';
		}

		if ($tipe == 3) {
			$tipe = '(2,3)';
		}

		$SQL = "SELECT
					permintaan_biaya_barang.*, permintaan_biaya.nomor_permintaan,
					permintaan_biaya.nominal_setujui
				FROM
					`permintaan_biaya_barang`
				JOIN permintaan_biaya ON permintaan_biaya_barang.permintaan_biaya_id = permintaan_biaya.id
				JOIN o_s_pmsn ON permintaan_biaya_barang.o_s_pmsn_id = o_s_pmsn.id
				JOIN item ON o_s_pmsn.item_id = item.id
				JOIN item_sub_kategori ON item.item_sub_kategori = item_sub_kategori.id
				WHERE item_sub_kategori.tipe IN $tipe
				GROUP BY
				permintaan_biaya_id;";
					
		return $this->db->query($SQL);
	}

	public function get_data_detail($permintaan_biaya_id)
	{
		
		$SQL = "SELECT
					permintaan_biaya_barang.*, 
					item.kode as kode_item,
					item.nama as nama_item,
					item_satuan.nama as nama_satuan
				FROM
					`permintaan_biaya_barang`
				JOIN item ON permintaan_biaya_barang.item_id = item.id
				JOIN item_satuan ON permintaan_biaya_barang.item_satuan_id = item_satuan.id
				WHERE permintaan_biaya_barang.permintaan_biaya_id = '$permintaan_biaya_id'";
					
		return $this->db->query($SQL);
	}


}

/* End of file Permintaan_biaya.php */
/* Location: ./application/models/keuangan/Permintaan_biaya.php */