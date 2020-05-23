<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_biaya_m extends MY_Model {

	protected $_table        = 'kasir_biaya';
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
		'kasir_biaya.tanggal'         => 'tanggal', 
		//'kasir_terima_uang.subjek'         => 'subjek', 
		'kasir_biaya.keterangan'         => 'keterangan', 
		'kasir_biaya.rupiah'         => 'rupiah', 
		'kasir_biaya.rupiah'         => 'rupiah', 
	 
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id=null,$tgl=null)
	{	
		//$join1 = array('kasir_terima_uang', $this->_table.'.kasir_terima_uang_id = kasir_terima_uang.id','left');
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
	 	$this->db->where('kasir_biaya.created_by',$id);
	 	$this->db->where('DATE_FORMAT(kasir_biaya.tanggal,"%m-%Y")',$tgl);
	 //	$this->db->order_by('kasir_biaya.tanggal desc');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('kasir_biaya.created_by',$id);
	 	$this->db->where('DATE_FORMAT(kasir_biaya.tanggal,"%m-%Y")',$tgl);
	 //	$this->db->order_by('kasir_biaya.tanggal desc');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('kasir_biaya.created_by',$id);
	 	$this->db->where('DATE_FORMAT(kasir_biaya.tanggal,"%m-%Y")',$tgl);
	 //	$this->db->order_by('kasir_biaya.tanggal desc');

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

	public function get_datatable_total($id=null,$tgl=null)
	{
		//$join1 = array('kasir_terima_uang', $this->_table.'.kasir_terima_uang_id = kasir_terima_uang.id','left');
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		 
 

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, false);
		$this->db->where('kasir_biaya.created_by',$id);
	 	$this->db->where('DATE_FORMAT(kasir_biaya.tanggal,"%m-%Y")',$tgl);
	 //	$this->db->order_by('kasir_biaya.tanggal desc');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns;
	 
	 
		$result->records               = $this->db->get(); 
	   // die(dump($result->records));
		return $result; 
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

	public function getdata($id){
		$sql= "select * from poliklinik where id=? and is_active=1";

		return $this->db->query($sql,$id);
	}

	public function getdataall($id){
		$sql= "select * from poliklinik a join cabang_poliklinik b on a.id=b.poliklinik_id where b.cabang_id=?  and a.is_active=1 and b.is_active=1";

		return $this->db->query($sql,$id);
	}

	public function get_status_poli($poliklinik_id,$cabang_id){
		$sql= "select count(*) as counts from poliklinik a join cabang_poliklinik b on a.id=b.poliklinik_id where b.cabang_id=".$cabang_id." and poliklinik_id=".$poliklinik_id." and DATE_FORMAT(now(), '%H%i') between DATE_FORMAT(jam_buka, '%H%i') and DATE_FORMAT(jam_tutup, '%H%i') and a.is_active=1 and b.is_active=1";

		return $this->db->query($sql);
	}

	public function get_status_poli2($id){
		$sql= "select b.tipe from cabang_poliklinik b  where b.poliklinik_id=?  and b.is_active=1";

		return $this->db->query($sql,$id);
	}
	

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */