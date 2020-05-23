<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Antrian_m extends MY_Model {

	protected $_table        = 'pendaftaran_tindakan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 		
	);

	private $_fillable_edit = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'b.nama'                       => 'nama', 
		'pendaftaran_tindakan.antrian' => 'antrian', 
		'b.url_photo'                  => 'url_photo', 
		'b.no_member'                  => 'no_member', 
		 
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($poliklinik_id=null,$dokter_id=null, $shift)
	{	
		  $join1 = array('pasien b', $this->_table.'.pasien_id = b.id','left');
		 // $join2 = array('cabang_telepon', $this->_table.'.id = cabang_telepon.cabang_id','left');
		 $join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']  = 'pendaftaran_tindakan.antrian';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.poliklinik_id',$poliklinik_id);
		$this->db->where($this->_table.'.dokter_id',$dokter_id);
		$this->db->where($this->_table.'.shift',$shift);
		$this->db->where($this->_table.'.status',4);
		$this->db->where($this->_table.'.status_verif != ',3);
		// $this->db->where('cabang_telepon.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.poliklinik_id',$poliklinik_id);
		$this->db->where($this->_table.'.dokter_id',$dokter_id);
		$this->db->where($this->_table.'.shift',$shift);
		$this->db->where($this->_table.'.status',4);
		$this->db->where($this->_table.'.status_verif != ',3);

		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.poliklinik_id',$poliklinik_id);
		$this->db->where($this->_table.'.dokter_id',$dokter_id);
		$this->db->where($this->_table.'.shift',$shift);
		$this->db->where($this->_table.'.status',4);
		$this->db->where($this->_table.'.status_verif != ',3);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
			$this->db->where($this->_table.'.shift',$shift);
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
	//   die(dump($result->records));
		return $result; 
	}

	public function get_data_poli($cabang_id)
	{
		$this->db->select("cabang_poliklinik.id as id,
							poliklinik.nama as nama,
							cabang_poliklinik.jam_buka as jam_buka,
							cabang_poliklinik.jam_tutup as jam_tutup");
		$this->db->join("poliklinik", $this->_table.".poliklinik_id = poliklinik.id");
		$this->db->where($this->_table.".cabang_id", $cabang_id);

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

	public function get_antrian($poliklinik_id,$dokter_id,$shift){
		$sql="SELECT COUNT(pendaftaran_tindakan.id) + 1 AS no_antrian   FROM pendaftaran_tindakan LEFT JOIN pasien b ON pendaftaran_tindakan.pasien_id = b.id WHERE pendaftaran_tindakan.poliklinik_id = ".$poliklinik_id." AND pendaftaran_tindakan.status = 4 AND pendaftaran_tindakan.status_verif != 3 AND pendaftaran_tindakan.dokter_id =".$dokter_id." AND shift=".$shift;
		

		return $this->db->query($sql);

	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */