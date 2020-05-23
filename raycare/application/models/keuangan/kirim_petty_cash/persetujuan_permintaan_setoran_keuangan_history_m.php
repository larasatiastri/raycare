<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_setoran_keuangan_history_m extends MY_Model
{
	protected $_table        = 'persetujuan_permintaan_setoran_keuangan_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'persetujuan_permintaan_setoran_keuangan_history.id'          => 'id', 
		'persetujuan_permintaan_setoran_keuangan_history.tanggal'     => 'tanggal', 
		'persetujuan_permintaan_setoran_keuangan_history.total_setor' => 'total_setor',
		'persetujuan_permintaan_setoran_keuangan_history.status'      => 'status',
		'persetujuan_permintaan_setoran_keuangan_history.subjek'      => 'subjek',
		'a.nama'                             => 'nama_user_created',
		'b.nama'                             => 'nama_user',
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
		$join1 = array('user a', $this->_table.'.created_by = a.id','left');
		$join2 = array('user b', $this->_table.'.user_terima_id = b.id','left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

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
}

/* End of file persetujuan_permintaan_setoran_keuangan_history.php */
/* Location: ./application/models/persetujuan_permintaan_setoran_keuangan_history.php */