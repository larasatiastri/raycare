<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jurnal_sistem_detail_template_m extends MY_Model
{
	protected $_table        = 'jurnal_sistem_detail_template';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
			'jurnal_sistem_template_id',
			'akun_id',
			'debit_credit',
			'variable'
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'jurnal_sistem_detail_template.id'        => 'id', 
		'jurnal_sistem_detail_template.jurnal_sistem_template_id'   => 'jurnal_sistem_template_id',
		'jurnal_sistem_detail_template.akun_id'      => 'akun_id',  
		'jurnal_sistem_detail_template.debit_credit'      => 'debit_credit', 
	);

	protected $datatable_columns_journal = array(
		//column di table  => alias
		'jurnal_sistem_detail_template.id'                         => 'id', 
		'jurnal_sistem_detail_template.jurnal_sistem_template_id' => 'jurnal_sistem_template_id',
		'jurnal_sistem_detail_template.akun_tipe'               => 'akun_tipe',
		'jurnal_sistem_detail_template.akun_id'                 => 'akun_id',  
		'jurnal_sistem_detail_template.debit_credit'               => 'debit_credit', 
		'jurnal_sistem_template.tipe_transaksi'               => 'tipe_transaksi', 

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
		$join_tables = array();
		$params = $this->datatable_param($this->datatable_columns);
		$this->datatable_prepare($join_tables, $params);
		$total_records = $this->db->count_all_results();
		$this->datatable_prepare($join_tables, $params, true);
		$total_display_records = $this->db->count_all_results();
		$this->datatable_prepare($join_tables, $params, true, true);
		foreach ($this->datatable_columns as $col => $alias)
		{
			$this->db->select($col . ' AS ' . $alias);
		}
		$result = new stdClass();
		$result->columns               = $this->datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		return $result; 
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_journal($temp_id=null)
	{

		$join1 = array("jurnal_sistem_template", $this->_table . '.jurnal_sistem_template_id = jurnal_sistem_template.id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_journal);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('jurnal_sistem_template_id', $temp_id);
		// $this->db->where('tipe_transaksi', 5);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('jurnal_sistem_template_id', $temp_id);
		// $this->db->where('tipe_transaksi', 5);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('jurnal_sistem_template_id', $temp_id);
		// $this->db->where('tipe_transaksi', 5);


		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_journal as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_journal;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
	 //die_dump($result);

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


	public function get_debt_cred($temp_id,$type)
	{
		$this->db->where('jurnal_sistem_template_id', $temp_id);
		$this->db->where('debit_credit', $type);
		return $this->db->get($this->_table);
	}

}

