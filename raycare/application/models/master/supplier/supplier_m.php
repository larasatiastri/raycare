<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_m extends MY_Model {

	protected $_table      = 'supplier';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'supplier.id'                      => 'id',
		'supplier.kode'                    => 'kode',
		'supplier.nama'                    => 'nama',
		'supplier.rating'                  => 'rating',
		'supplier.orang_yang_bersangkutan' => 'orang_yang_bersangkutan',
		'supplier.is_active'               => 'is_active'
	);

	function __construct()
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
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') == 3){
			$this->db->like($this->_table.'.tipe_barang', 3);
		}if($this->session->userdata('level_id') == 23 || $this->session->userdata('level_id') == 32){
			$this->db->like($this->_table.'.tipe_barang', '1-0-0');
			$this->db->or_like($this->_table.'.tipe_barang', '1-2-0');
			$this->db->or_like($this->_table.'.tipe_barang', '1-2-3');
		}
		// 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') == 3){
			$this->db->like($this->_table.'.tipe_barang', 3);
		}if($this->session->userdata('level_id') == 23 || $this->session->userdata('level_id') == 32){
			$this->db->like($this->_table.'.tipe_barang', '1-0-0');
			$this->db->or_like($this->_table.'.tipe_barang', '1-2-0');
			$this->db->or_like($this->_table.'.tipe_barang', '1-2-3');
		}
		// 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		if($this->session->userdata('level_id') == 3){
			$this->db->like($this->_table.'.tipe_barang', 3);
		}if($this->session->userdata('level_id') == 23 || $this->session->userdata('level_id') == 32){
			$this->db->like($this->_table.'.tipe_barang', '1-0-0');
			$this->db->or_like($this->_table.'.tipe_barang', '1-2-0');
			$this->db->or_like($this->_table.'.tipe_barang', '1-2-3');
		}
		// 

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

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_data_is_active()
    {
        $this->db->where("is_active", 1);
        return $this->db->get($this->_table);
    }

    public function get_data_subjek($type)
	{
		$format = "SELECT id as id, nama as nama
				   FROM subjek
				   WHERE tipe = $type";

		return $this->db->query($format);
	}
	
	public function get_no_kode($inisial)
	{
		$format = "SELECT
						MAX(
							SUBSTRING(`kode`, 2, 4)
						) AS max_kode
					FROM
						supplier
					WHERE
						SUBSTRING(`kode`, 1, 1) = '$inisial'";	
		return $this->db->query($format);
	}
}

/* End of file supplier_m.php */
/* Location: ./application/models/supplier_m.php */