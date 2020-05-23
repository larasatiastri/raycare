<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bed_m extends MY_Model {

	protected $_table        = 'bed';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'bed.nama'         		 => 'nama', 
		'bed.tipe'   			 => 'tipe', 
		'bed.created_date'   	 => 'tggl', 
		 


		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id=null)
	{	
 
	 	 
		$join1 = array('pasien_penjamin b',$this->_table . '.id = b.pasien_id');
		$join2 = array('(select tipe,pasien_klaim.created_date,penjamin_id,nama from pasien_klaim join penjamin on pasien_klaim.penjamin_id=penjamin.id where pasien_id="'.$id.'") c','c.penjamin_id = b.penjamin_id');
		$join_tables = array($join1,$join2);

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);
		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
	 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	// 	$this->db->where('pasien_penjamin.pasien_id',$id);
	 //	$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
	 	 
	 	 
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
		 
	  

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

	function get_lantai()		// Abu* digunakan utk transaksi_perawat
	{	
		$this->db->group_by('lantai_id');
		return $this->db->get($this->_table);
	}

	function get_bed_pasien($bed_id, $shift)		// Abu* digunakan utk transaksi_perawat ketika lihat detail
	{	
		$format = "SELECT bed.id AS bed_id, tindakan_hd.id AS tindakan_id, tindakan_hd.no_transaksi AS no_transaksi, bed.kode AS bed_kode, bed.nama AS bed_nama, bed.lantai_id AS lantai, pasien.id AS pasien_id, pasien.tanggal_registrasi AS tanggal_registrasi, pasien.tanggal_lahir AS tanggal_lahir, pasien.nama AS pasien, pasien.url_photo AS url_photo,pasien.no_member AS no_member,
									SUBSTRING(tindakan_hd.jam_mulai, 12, 5) AS jam_mulai,
									(SELECT user.nama FROM user WHERE tindakan_hd.dokter_id = `user`.id AND `user`.user_level_id = 10) AS dokter,
									(SELECT user.nama FROM user WHERE tindakan_hd.perawat_id = `user`.id AND `user`.user_level_id = 18) AS perawat,
									(SELECT user.nama FROM user WHERE bed.user_edit_id = `user`.id ) AS perawat_open
					FROM bed
					JOIN tindakan_hd ON bed.id = tindakan_hd.bed_id
					JOIN pasien ON tindakan_hd.pasien_id = pasien.id
					WHERE bed_id =  '".$bed_id."' AND tindakan_hd.status = 2 AND tindakan_hd.shift = '".$shift."' ORDER BY tindakan_hd.tanggal DESC";	

		return $this->db->query($format)->result_array();
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable_add()
	{
		return $this->_fillable_add;
	}

	function get_bed_booking()
	{
		$this->db->where('status', 2);
		$this->db->where('is_active', 1);
		$this->db->order_by('modified_date', 'desc');
		return $this->db->get($this->_table);
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

