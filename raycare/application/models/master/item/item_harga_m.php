<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_harga_m extends MY_Model {

	protected $_table        = 'item_harga';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'item_id',
		'parent_id',
		'nama', 
		'jumlah', 
		'is_primary', 
	);

	private $_fillable_edit = array(
		'id',
		'item_id',
		'parent_id',
		'nama', 
		'jumlah', 
		'is_primary', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item_harga.id'         		 => 'id', 
		'item_harga.item_id' 			=> 'item_id', 
		'item_harga.parent_id'   			 => 'parent_id', 
		'item_harga.nama'   			 => 'nama', 
		'item_harga.jumlah'  		 => 'jumlah',
		'item_harga.is_primary'    => 'is_primary',
		
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'item.id'         		 => 'id', 
		'item.item_sub_kategori' => 'item_sub_kategori', 
		'item.kode'   			 => 'kode', 
		'item.nama'   			 => 'nama', 
		'item_harga.nama'      => 'unit',
		'item_harga.harga'      => 'harga',
		'item_kategori.nama'      => 'kategori_item',
		'item.keterangan'  		 => 'keterangan',

		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($item_id)
	{	

		// $join1 = array('item_sub_kategori', $this->_table.'.item_sub_kategori = item_sub_kategori.id', 'left');
		// $join2 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		// $join3 = array('item_harga', 'item_harga.item_id = item.id', 'left');
		// $join4 = array('item_harga', 'item_harga.item_id = item.id', 'left');

		$join_tables = array();

		$wheres = array(

				'item_harga.item_id' => $item_id,
				'item_harga.is_primary' => 1,

			);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);


		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item as $col => $alias)
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
		$query = $this->db->query('SELECT id, kode FROM item_harga');
		
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

	public function get_harga($id)
	{
		
		$cols = array(
            'item_harga.*',
        );
		$result = $this->db
            ->select($cols)
            ->from('item_harga')
            ->where('item_harga.item_satuan_id', $id)
            ->where('item_harga.tanggal <=', date('Y-m-d H:i:s'))
            ->order_by('item_harga.tanggal', 'desc')
            ->get();

        return $result;
	}

	public function get_harga_item_satuan($item_id, $satuan_id)
	{
		$cabang_id = $this->session->userdata('cabang_id');
		
		$cols = array(
            'item_harga.*',
        );
		$result = $this->db
            ->select($cols)
            ->from('item_harga')
            ->where('item_harga.cabang_id', $cabang_id)
            ->where('item_harga.item_id', $item_id)
            ->where('item_harga.item_satuan_id', $satuan_id)
            ->where('item_harga.harga != ', 0)
            ->where('date(item_harga.tanggal) <=', date('Y-m-d'))
            ->order_by('item_harga.tanggal', 'desc')
            ->get();

        return $result;
	}

}

/* End of file Item_harga_m.php */
/* Location: ./application/models/master/cabang_m.php */