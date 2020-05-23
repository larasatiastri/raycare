<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_sub_kategori_m extends MY_Model {

	protected $_table        = 'item_sub_kategori';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'item_kategori_id',
		'kode',
		'nama', 
		'keterangan', 
	);

	private $_fillable_edit = array(
		'item_kategori_id',
		'kode',
		'nama', 
		'keterangan', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item_sub_kategori.kode'       => 'kode', 
		'item_sub_kategori.nama'       => 'nama', 
		'item_kategori.nama'           => 'kategori', 
		'item_sub_kategori.keterangan' => 'keterangan', 
		'item_sub_kategori.tipe' 	   => 'tipe', 
		'item_sub_kategori.id'         => 'id', 
		
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
		$join = array('item_kategori', $this->_table . '.item_kategori_id = item_kategori.id');
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_sub_kategori.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_sub_kategori.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_sub_kategori.is_active',1);

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

	// abu* utk kebutuhan edit spesifikasi sub kategori
	public function get_data_spesifikasi($kategori_sub_id){

		$format = " SELECT
						item_sub_kategori_spesifikasi.id AS sub_kat_id,
						item_sub_kategori_spesifikasi.spesifikasi_id,
						spesifikasi.judul,
						spesifikasi.tipe,
						spesifikasi.maksimal_karakter,
						spesifikasi_detail.id AS detail_id,
						spesifikasi_detail.text,
						spesifikasi_detail.`value`
					FROM
						item_sub_kategori_spesifikasi
					LEFT JOIN spesifikasi ON item_sub_kategori_spesifikasi.spesifikasi_id = spesifikasi.id
					LEFT JOIN spesifikasi_detail ON spesifikasi.id = spesifikasi_detail.spesifikasi_id
					WHERE
						item_sub_kategori_id = ".$kategori_sub_id."
					GROUP BY
						spesifikasi_id";

		return $this->db->query($format);
	}

	// abu* utk kebutuhan edit spesifikasi detail sub kategori
	public function get_data_spesifikasi2($kategori_sub_id, $spesifikasi_id){

		$format = " SELECT
						item_sub_kategori_spesifikasi.id AS sub_kat_id,
						item_sub_kategori_spesifikasi.spesifikasi_id,
						spesifikasi.judul,
						spesifikasi.tipe,
						spesifikasi.maksimal_karakter,
						spesifikasi_detail.id AS detail_id,
						spesifikasi_detail.text,
						spesifikasi_detail.`value`
					FROM
						item_sub_kategori_spesifikasi
					JOIN spesifikasi ON item_sub_kategori_spesifikasi.spesifikasi_id = spesifikasi.id
					JOIN spesifikasi_detail ON spesifikasi.id = spesifikasi_detail.spesifikasi_id
					WHERE
						item_sub_kategori_id = ".$kategori_sub_id." 
					AND spesifikasi_detail.spesifikasi_id = ".$spesifikasi_id." ";

		return $this->db->query($format);
	}

	public function get_data_kategori_spesifikasi($sub_kategori_id){
		//di pakai untuk controler pasien/show_claim
		$format = " SELECT
						item_sub_kategori.id,
						item_sub_kategori.kode,
						item_sub_kategori.nama,
						spesifikasi.judul,
						spesifikasi.tipe,
						spesifikasi.maksimal_karakter,
						spesifikasi.id AS spesifikasi_id
					FROM
						item_sub_kategori
					LEFT JOIN item_sub_kategori_spesifikasi ON item_sub_kategori.id = item_sub_kategori_spesifikasi.item_sub_kategori_id
					LEFT JOIN spesifikasi ON item_sub_kategori_spesifikasi.spesifikasi_id = spesifikasi.id
					WHERE
					item_sub_kategori.id = $sub_kategori_id";

		return $this->db->query($format);
	}

	public function get_data_spesifikasi_detail($spesifikasi_id, $tipe){
		//di pakai untuk controler pasien/show_claim
		$format = " SELECT
					spesifikasi.judul,
					spesifikasi.tipe,
					spesifikasi.id,
					spesifikasi_detail.text,
					spesifikasi_detail.`value`
					FROM
					spesifikasi
					LEFT JOIN spesifikasi_detail ON spesifikasi.id = spesifikasi_detail.spesifikasi_id
					WHERE
					spesifikasi.id = $spesifikasi_id AND
					spesifikasi.tipe = $tipe
					";

		return $this->db->query($format);
	}

	public function get_data_sub_kategori($id_kategori)
	{
		$format = "SELECT
					item_sub_kategori.id as id,
					item_sub_kategori.nama as nama
					FROM
					item_sub_kategori,
					item_kategori
					WHERE
					item_kategori.id = item_sub_kategori.item_kategori_id AND
					item_kategori.id = $id_kategori
		";

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

}

