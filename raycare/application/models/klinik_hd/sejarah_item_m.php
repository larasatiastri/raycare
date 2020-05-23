<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sejarah_item_m extends MY_Model {

	protected $_table        = 'rm_obat_pasien';
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
		'b.kode'         		 => 'kode', 
		'b.nama'   			 => 'nama', 
		'rm_obat_pasien.jumlah'   			 => 'jumlah', 
		'c.nama'   			 => 'satuan', 
		'rm_obat_pasien.tanggal_resep'   			 => 'tanggal_resep', 
		'rm_obat_pasien.tanggal_habis'   			 => 'tanggal_habis', 
		'rm_obat_pasien.tindakan_id'   			 => 'id', 
		'd.tanggal'   			 => 'tanggal', 
		 


		
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
	 	$join2 = array('item_satuan c','b.id = c.item_id');
	 	$join3 = array('tindakan_hd d','rm_obat_pasien.tindakan_id = d.id');
		$join_tables = array($join1,$join2,$join3);

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 $this->db->where('rm_obat_pasien.pasien_id',$id);
		 $this->db->where('rm_obat_pasien.is_active',1);
		 $this->db->where('rm_obat_pasien.tipe_tindakan',1);
		 $this->db->where('rm_obat_pasien.tipe_obat',1);
		  $this->db->where('c.is_primary',1);
		//$this->db->where('pasien_penjamin.pasien_id',$id);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
	 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		 $this->db->where('rm_obat_pasien.pasien_id',$id);
		 $this->db->where('rm_obat_pasien.is_active',1);
		 $this->db->where('rm_obat_pasien.tipe_tindakan',1);
		 $this->db->where('rm_obat_pasien.tipe_obat',1);
		  $this->db->where('c.is_primary',1);
	// 	$this->db->where('pasien_penjamin.pasien_id',$id);
	 //	$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
	 	 
	 	 
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 $this->db->where('rm_obat_pasien.pasien_id',$id);
		 $this->db->where('rm_obat_pasien.is_active',1);
		 $this->db->where('rm_obat_pasien.tipe_tindakan',1);
		 $this->db->where('rm_obat_pasien.tipe_obat',1);
		  $this->db->where('c.is_primary',1);
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
  // die(dump($result->records));
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