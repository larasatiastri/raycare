<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pemeriksaan_lab_m extends MY_Model {

	protected $_table        = 'pemeriksaan_lab';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kode',
		'nama',
		'keterangan', 
		'tipe_akun', 
	);

	private $_fillable_edit = array(
		'kode',
		'nama',
		'keterangan', 
		'tipe_akun', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'kategori_pemeriksaan_lab.tipe'  		=> 'tipe', 
		'kategori_pemeriksaan_lab.nama'  		=> 'nama_kategori', 
		'pemeriksaan_lab.kode'  		=> 'kode', 
		'pemeriksaan_lab.nama'  		=> 'nama', 
		'pemeriksaan_lab.satuan'  => 'satuan',
		'pemeriksaan_lab.harga'  => 'harga',
		'pemeriksaan_lab.id'   		=> 'id', 
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
		$join1 = array('kategori_pemeriksaan_lab', $this->_table.'.kategori_pemeriksaan_id = kategori_pemeriksaan_lab.id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']	= $this->_table.'.id';
		$params['sort_dir']	= 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);

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

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM cabang_');
		
		return $query->row();
		return $this->db->get($this->_table);
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

	public function get_data_join_tindakan($kategori_pemeriksaan_id)
	{
		$this->db->select('pemeriksaan_lab.*, tindakan.id as tindakan_id, tindakan.harga');
		$this->db->join('tindakan', $this->_table.'.id = tindakan.pemeriksaan_lab_id', 'left');
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.kategori_pemeriksaan_id', $kategori_pemeriksaan_id);

		return $this->db->get($this->_table);

	}

	public function get_data_join_kategori()
	{
		$this->db->select('pemeriksaan_lab.id, pemeriksaan_lab.nama, kategori_pemeriksaan_lab.nama as kategori');
		$this->db->join('kategori_pemeriksaan_lab', $this->_table.'.kategori_pemeriksaan_id = kategori_pemeriksaan_lab.id', 'left');
		$this->db->where($this->_table.'.is_active', 1);

		return $this->db->get($this->_table);

	}

	public function get_max_id()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,11,4)) AS max_id FROM `pemeriksaan_lab` WHERE SUBSTR(`id`,4,6) = DATE_FORMAT(NOW(), '%m%Y');";	
		return $this->db->query($format);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */