<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_rugi_laba_detail_m extends MY_Model
{
	protected $_table        = 'laporan_rugi_laba_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'nomor',
		'tanggal',
		'total_pendapatan',
		'no_laporan_rugi_laba_detail',
		'is_suspended',
		'catatan',
		'is_last_child',
		'is_giro'
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'laporan_rugi_laba_detail.id'      => 'id', 
		'laporan_rugi_laba_detail.nomor'   => 'nomor',
		'laporan_rugi_laba_detail.tanggal'      => 'tanggal', 
		'laporan_rugi_laba_detail.total_pendapatan' => 'total_pendapatan',  
		'laporan_rugi_laba_detail.total_hpp'        => 'total_hpp', 
		'laporan_rugi_laba_detail.total_beban'      => 'total_beban',  
		'laporan_rugi_laba_detail.total_pendapatan_lain'   => 'total_pendapatan_lain',
		'laporan_rugi_laba_detail.total_beban_lain'   => 'total_beban_lain',

	);
	
	function __construct ()
	{
		parent::__construct();
	}

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($laporan_rugi_laba_detail_tipe, $is_suspended)
	{
		
		$join_tables = array();

		$wheres = array();
		
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'ASC';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($wheres);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);

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

	public function fillable()
	{
		return $this->_fillable;
	}

	public function fillable_add()
	{
		return $this->_fillable_add;
	}

	public function get_max_id()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `laporan_rugi_laba_detail` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_item_laporan_rugi_laba($laporan_rugi_laba_id, $tipe)
	{
		$format = "SELECT
						laporan_rugi_laba_detail.*, akun.no_akun,
						akun.nama
					FROM
						`laporan_rugi_laba_detail`
					LEFT JOIN akun ON laporan_rugi_laba_detail.akun_id = akun.id
					WHERE
						laporan_rugi_laba_detail.laporan_rugi_laba_id = '$laporan_rugi_laba_id'
					AND laporan_rugi_laba_detail.kategori_id = $tipe
					ORDER BY
						akun.no_akun ASC;";

		return $this->db->query($format);

	}


	
}

/* End of file user.php */
/* Location: ./application/models/finance/account_m.php */