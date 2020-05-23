<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rujukan_m extends MY_Model {

	protected $_table        = 'rujukan';
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
	// protected $datatable_columns = array(
	// 	//column di table  => alias
	// 	'item.id'         		 => 'id', 
	// 	'item.item_sub_kategori' => 'item_sub_kategori', 
	// 	'item.kode'   			 => 'kode', 
	// 	'item.nama'   			 => 'nama', 
	// 	'item.keterangan'  		 => 'keterangan',
	// 	'item.is_discontinue'    => 'is_discontinue',
	// 	'item.buffer_stock'      => 'buffer_stock',
	// 	'item.is_jual'     		 => 'is_jual',
	// 	'item.id_identitas'      => 'id_identitas',
	// 	'item_satuan.nama'      => 'unit',
	// 	'item_harga.harga'      => 'harga',
	// 	'item_kategori.nama'      => 'kategori_item',
		
	// );

	protected $datatable_columns = array(
		//column di table  => alias
		'a.nama'         		 => 'asal', 
		 'b.nama'   			 => 'tujuan', 
		'rujukan.tanggal_dirujuk'   			 => 'tggldirujuk', 
		'rujukan.tanggal_rujukan'  		 => 'tgglrujukan',
		'b.id'   			 => 'id', 
		
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

	 	 
		
	
		  $join1 = array('poliklinik a', $this->_table . '.poliklinik_asal_id = a.id','left');
		  $join2 = array('poliklinik b', $this->_table . '.poliklinik_tujuan_id = b.id','left');
		 $join_tables = array($join1,$join2);

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
	 	$this->db->where('rujukan.pasien_id',$id);
	 	$this->db->where(' now() <= rujukan.tanggal_rujukan');
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('rujukan.pasien_id',$id); 
		$this->db->where(' now() <= rujukan.tanggal_rujukan');
		 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('rujukan.pasien_id',$id); 
		$this->db->where(' now() <= rujukan.tanggal_rujukan');
	 

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
	 //  die(dump($result->records));
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