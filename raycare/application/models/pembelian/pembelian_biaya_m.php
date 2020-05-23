<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_biaya_m extends MY_Model {

	protected $_table        = 'pembelian_biaya';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian_biaya.id'           => 'id',
		'pembelian_biaya.pembelian_id' => 'id_pembelian',
		'pembelian_biaya.nominal'      => 'nominal',
		'pembelian.no_pembelian'       => 'no_pembelian'
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
		$join1 = array('pembelian', $this->_table.'.pembelian_id = pembelian.id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'asc';

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

	public function get_max_id_biaya_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `pembelian_biaya` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data($id_po)
	{
		$this->db->select($this->_table.'.*, biaya.nama');
		$this->db->join('biaya',$this->_table.'.biaya_id = biaya.id','left');
		$this->db->where($this->_table.'.pembelian_id', $id_po);
		$this->db->where($this->_table.'.is_active', 1);

		return $this->db->get($this->_table);
	}

	
}

/* End of file pembelian_biaya_m.php */
/* Location: ./application/models/pembelian/pembelian_biaya_m.php */