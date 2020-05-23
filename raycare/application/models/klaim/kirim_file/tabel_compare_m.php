<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tabel_compare_m extends MY_Model {

	protected $_table        = 'tabel_compare';
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
		'tabel_compare.kode'          => 'kode',
		'tabel_compare.tanggal_awal'  => 'tanggal_awal',
		'tabel_compare.tanggal_akhir' => 'tanggal_akhir',
		'tabel_compare.file_ori'      => 'file_ori',
		'tabel_compare.file_modif'    => 'file_modif',
		'tabel_compare.email_ke'      => 'email_ke',
		'tabel_compare.cc'            => 'cc',
		'tabel_compare.subjek'        => 'subjek',
		'tabel_compare.isi_email'     => 'isi_email'
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
		$params['sort_by']	= 'tabel_compare.kode';
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
}
