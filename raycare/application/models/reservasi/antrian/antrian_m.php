<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Antrian_m extends MY_Model {

	protected $_table        = 'antrian';
	protected $_order_by     = 'no_urut';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

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
		//$join1 = array('kasir_terima_uang', $this->_table.'.kasir_terima_uang_id = kasir_terima_uang.id','left');
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
	 //	$this->db->order_by('kasir_biaya.tanggal desc');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	 //	$this->db->order_by('kasir_biaya.tanggal desc');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
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

	public function get_max_id()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `antrian` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_max_no_urut($posisi_loket)
	{
		$format = "SELECT MAX(`no_urut`) AS max_no_urut FROM `antrian` WHERE posisi_loket = $posisi_loket;";	
		return $this->db->query($format);
	}

	public function get_max_no_urut_dokter($posisi_loket, $dokter_id)
	{
		$format = "SELECT MAX(`no_urut`) AS max_no_urut FROM `antrian` WHERE posisi_loket = $posisi_loket AND dokter_id = $dokter_id;";	
		return $this->db->query($format);
	}
	

	public function get_max_id_panggilan()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `antrian_panggil` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_max_no_urut_panggil()
	{
		$format = "SELECT MAX(`urutan`) AS max_no_urut FROM `antrian_panggil` ;";	
		return $this->db->query($format);
	}

	public function add_data_panggilan($data)
	{
		
		return $this->db->insert('antrian_panggil',$data);
	}

	public function get_data_loket($loket_id)
	{
		$format = "SELECT * FROM `antrian` WHERE posisi_loket = $loket_id ORDER BY no_urut ASC;";	
		return $this->db->query($format);
	}

	public function get_data_loket_panggil($loket_id)
	{
		$format = "SELECT * FROM `antrian` WHERE posisi_loket = $loket_id AND status = 0 AND is_panggil IS NULL OR posisi_loket = $loket_id AND status = 0 AND is_panggil = 1 ORDER BY no_urut ASC;";	
		return $this->db->query($format);
	}

	public function get_data_loket_panggil_tiga($loket_id, $no_urut)
	{
		$format = "SELECT * FROM `antrian` WHERE posisi_loket = $loket_id AND no_urut >= $no_urut ORDER BY no_urut ASC;";	
		return $this->db->query($format);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */