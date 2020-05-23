<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Simpan_item_history_detail_m extends MY_Model {

	protected $_table        = 'simpan_item_history_detail';
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
		
	);

	protected $datatable_columns = array(
		
		//column di table            => alias
		'simpan_item.simpan_item_id' => 'simpan_item_id',
		'b.nama'                     => 'nama', 
		'simpan_item.status'         => 'status',
		 
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id=null)
	{	
 
	 	 
		$join1 = array('item b',$this->_table . '.item_id = b.id');
	  
		$join_tables = array($join1);

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
	 
		 $this->db->where('transaksi_simpan_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
	 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
	 
		  $this->db->where('transaksi_simpan_id',$id);
	// 	$this->db->where('pasien_penjamin.pasien_id',$id);
	 //	$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
	 	 
	 	 
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 
		 $this->db->where('transaksi_simpan_id',$id);
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
		 
	  

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

	
}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */