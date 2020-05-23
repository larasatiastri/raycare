<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_file_txt_m extends MY_Model {

	protected $_table        = 'pengiriman_file_txt';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kode',
		'tanggal_awal',
		'tanggal_akhir',
		'path_file_ori',
		'path_file_modif',
		'email_ke',
		'cc',
		'subjek',
		'isi_email'
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table          => alias
		'pengiriman_file_txt.kode'          => 'kode',
		'pengiriman_file_txt.tanggal_awal'  => 'tanggal_awal',
		'pengiriman_file_txt.tanggal_akhir' => 'tanggal_akhir',
		'pengiriman_file_txt.file_ori'      => 'file_ori',
		'pengiriman_file_txt.file_modif'    => 'file_modif',
		'pengiriman_file_txt.email_ke'      => 'email_ke',
		'pengiriman_file_txt.cc'            => 'cc',
		'pengiriman_file_txt.subjek'        => 'subjek',
		'pengiriman_file_txt.isi_email'     => 'isi_email'
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

		$join_tables = array();


		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']	= 'pengiriman_file_txt.kode';
		$params['sort_dir']	= 'desc';

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
		return $result; 
	}

	public function get_max_kode()
	{
		$format = "SELECT MAX(SUBSTRING(`kode`,10,3)) AS max_kode FROM `pengiriman_file_txt` WHERE SUBSTRING(`kode`,5,4) = DATE_FORMAT(NOW(), '%m%y')";	
		return $this->db->query($format);
	}
}
