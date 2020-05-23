<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_rugi_laba_m extends MY_Model
{
	protected $_table        = 'laporan_rugi_laba';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'nomor',
		'tanggal',
		'total_pendapatan',
		'no_laporan_rugi_laba',
		'is_suspended',
		'catatan',
		'is_last_child',
		'is_giro'
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'laporan_rugi_laba.id'      => 'id', 
		'laporan_rugi_laba.nomor'   => 'nomor',
		'laporan_rugi_laba.tanggal'      => 'tanggal', 
		'laporan_rugi_laba.laba_kotor' => 'laba_kotor',  
		'laporan_rugi_laba.laba_rugi_bersih_sebelum_pajak' => 'laba_rugi_bersih_sebelum_pajak',  
		'laporan_rugi_laba.pajak_penghasilan_badan' => 'pajak_penghasilan_badan',  
		'laporan_rugi_laba.laba_rugi_bersih_setelah_pajak' => 'laba_rugi_bersih_setelah_pajak', 
		'user.nama'	=> 'nama_user' 

	);
	
	function __construct ()
	{
		parent::__construct();
	}

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{
		$join1 = array('user', $this->_table.'.created_by = user.id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'ASC';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);

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

	public function get_no_laporan_rugi_laba()
	{
		$format = "SELECT MAX(SUBSTRING(`no_laporan_rugi_laba`,5,2)) AS max_no_laporan_rugi_laba FROM `laporan_rugi_laba` WHERE LEFT(`no_laporan_rugi_laba`,4) = '123.'";	
		

		return $this->db->query($format);
	}

	public function fillable()
	{
		return $this->_fillable;
	}

	public function fillable_edit_child()
	{
		return $this->_fillable_edit_child;
	}


	public function fillable_add()
	{
		return $this->_fillable_add;
	}

	public function get_max_id()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `laporan_rugi_laba` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_max_nomor($month)
	{
		$format = "SELECT MAX(RIGHT(`nomor`,2)) AS max_nomor FROM `laporan_rugi_laba` WHERE SUBSTR(`id`,4,6) = '$month';";	
		return $this->db->query($format);
	}


	
}

/* End of file user.php */
/* Location: ./application/models/finance/account_m.php */