<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_dokter2_m extends MY_Model {

	protected $_table        = 'simpan_item';
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
		'item.kode'          => 'kode', 
		'item.nama'          => 'nama', 
		'simpan_item.jumlah' => 'jumlah',
		'item_satuan.nama'   => 'nama_satuan',
		'simpan_item.index'  => 'idx'
		 
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id)
	{	

	 $join1 = array('item', $this->_table . '.item_id = item.id');
	 $join2 = array('item_satuan', 'item_satuan.item_id = item.id');
	 
		 $join_tables = array($join1,$join2);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filtesimpan_item.item_id, simpan_item.pasien_idr dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);  
		$this->db->where('simpan_item.pasien_id',$id);
		$this->db->where('simpan_item.status',1);
		$this->db->where('simpan_item.status_reuse',3);
		 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('simpan_item.pasien_id',$id);
		$this->db->where('simpan_item.status',1);
		$this->db->where('simpan_item.status_reuse',3);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('simpan_item.pasien_id',$id);
		$this->db->where('simpan_item.status',1);
		$this->db->where('simpan_item.status_reuse',3);
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

	public function getdatatindakan($id){
		$sql= "select a.created_date,b.nama,a.pasien_id from pendaftaran_tindakan a join user b on a.dokter_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}

	public function getdatatindakanfrekuensi($startdate,$enddate,$pasienid){
		$sql= "select count(*) as counts from tindakan_hd where pasien_id=".$pasienid." and tanggal between '".$startdate."' and '".$enddate."'";

		return $this->db->query($sql);
	}

	public function getdatapasien($id){
		$sql= "select b.nama,c.alamat,b.gender,b.tanggal_lahir,DATEDIFF(now(),tanggal_lahir) AS usia from pendaftaran_tindakan a join pasien b on a.pasien_id=b.id left join pasien_alamat c on c.pasien_id=b.id where a.id=?";

		return $this->db->query($sql,$id);
	}
	 

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */