<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resep_obat_racikan_detail_m extends MY_Model {

	protected $_table        = 'resep_obat_racikan_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'item_sub_kategori',
		'kode',
		'nama', 
		'keterangan', 
		'is_discontinue', 
		'buffer_stock', 		
		'is_jual', 		
		'id_identitas', 		
	);

	private $_fillable_edit = array(
		'id',
		'item_sub_kategori',
		'kode',
		'nama', 
		'keterangan', 
		'is_discontinue', 
		'buffer_stock', 		
		'is_jual', 		
		'id_identitas',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'resep_racik_obat.id'         		 => 'id', 
		'resep_racik_obat.nama' => 'nama', 
		'resep_racik_obat.keterangan'   			 => 'keterangan', 
	 
		
	);

 
protected $datatable_columns2 = array(
		//column di table  => alias
		'b.nama'         		 => 'nama', 
		' Case
                                        When tindakan_resep_obat.tipe_item  = 1 Then "Obat"
                                        Else "Racikan"
                                        End ' => 'tipe_item', 
		'tindakan_resep_obat.jumlah'   			 => 'jumlah', 
	 	'c.nama'   			 => 'nama_satuan', 
	 	'tindakan_resep_obat.dosis'   			 => 'dosis', 
		
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
		
		 

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

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

	public function get_datatable2($id=null)
	{	
 
	 	 
		$join1 = array('item b',$this->_table . '.item_id = b.id');
		$join2 = array('item_satuan c',$this->_table . '.satuan_id = c.id');
		$join_tables = array($join1,$join2);

		

		$params = $this->datatable_param($this->datatable_columns2);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.tindakan_id', $id);
	 
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.tindakan_id', $id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.tindakan_id', $id);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns2 as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns2;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
//die(dump($result->records));
		return $result; 
	}

	

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM item');
		
		return $query->row();
		return $this->db->get($this->_table);
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

	public function getsatuanobat($id)
	{
		$sql= "select * from item_satuan where item_id=?";

		return $this->db->query($sql,$id);
	}
}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */