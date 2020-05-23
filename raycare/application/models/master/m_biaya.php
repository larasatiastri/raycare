<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_biaya extends MY_Model {

	protected $_table        = 'biaya';
	protected $_order_by     = 'id';
 	protected $_timestamps   = true;
// 
	private $_fillable_add = array(
		
		'nama_biaya',
		'kategori_biaya',
		'tipe_biaya',
 		'is_active',
		'created_by',
		'created_date',
		'modified_by',
		'modified_date',
		'kategori_biaya_id',
	);

	private $_fillable_edit = array(
		
		'nama_biaya',
		'kategori_biaya',
		'tipe_biaya',
		'is_active',
		'created_by',
		'created_date',
		'modified_by',
		'modified_date',
		'kategori_biaya_id'

	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'biaya.id'         => 'id', 
		'biaya.nama'   		=> 'nama_biaya', 
		'kategori_biaya.nama'	=> 'kategori_biaya',
		'kategori_biaya.tipe'		=> 'tipe_kategori_biaya',
		'biaya.is_active'   		=> 'is_active'
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
		$join1 = array('kategori_biaya', $this->_table.'.kategori_biaya_id = kategori_biaya.id', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'biaya.id';
		$params['sort_dir'] = 'asc';
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

	public function create_multiple_table($biaya_input_data, $kategori_biaya_input_data)
	{
		$this->db->insert('biaya',$biaya_input_data);
		$pasien_insert_id = $this->db->insert_id();

		// Insert into dosen_id table
		$kategori_biaya_input_data['kategori_biaya_id'] = $kategori_biaya_insert_id;
		$this->db->insert('kategori_biaya_id', $kategori_biaya_input_data);
		return $insert_id = $this->db->insert_id();
	}

	public function tambahBiaya($table_name,$data,$data2){
		$tambah = $this->db->insert($table_name,$data,$data2);
		return $tambah;
	}

	public function simpan_biaya($biaya_input_data, $kategori_biaya_input_data )
	{
		$this->db->where('id', $id);
		$this->db->update('biaya', $biaya_input_data);

		$this->db->where('nama', $id);
		$this->db->update('kategori_biaya', $kategori_biaya_input_data);
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

	// public function get_data_full()
	// {
	// 	$this->db->select('biaya.id, biaya.nama_biaya, bank.nob');
	// 	$this->db->join('bank', $this->_table.'.bank_id = bank.id','left');
	// 	$this->db->where($this->_table.'.is_active', 1);

	// 	return $this->db->get($this->_table);
	// }

	public function get_max_id()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `biaya` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function checkpoli($poli_id,$tindakan_id){
		$sql= "select count(*) as counts from poliklinik_tindakan where poliklinik_id=".$poli_id." and tindakan_id=".$tindakan_id." and is_active=1";

		return $this->db->query($sql);
	}

	public function get_data()
	{
  		return $this->db->get("kategori_biaya");
	}

	
	public function hapusData($table_name,$id){
		$this->db->where('id',$id);
		$hapus= $this->db->delete($table_name);
		return $hapus;
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */