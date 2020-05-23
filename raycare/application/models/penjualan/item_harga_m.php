<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_harga_m extends MY_Model {

	protected $_table        = 'item_harga';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item_sub_kategori.kode'       => 'kode', 
		'item_sub_kategori.nama'       => 'nama', 
		'item_kategori.nama'           => 'kategori', 
		'item_sub_kategori.keterangan' => 'keterangan', 
		'item_sub_kategori.id'         => 'id', 
		
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
		$join = array('item_kategori', $this->_table . '.item_kategori_id = item_kategori.id');
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_sub_kategori.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_sub_kategori.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_sub_kategori.is_active',1);

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


	public function get_harga_item($item, $satuan)
	{
		$cabang_id = $this->session->userdata('cabang_id');
		$cols = array(
            'item_harga.harga',
        );
		$result = $this->db
            ->select($cols)
            ->from('item_harga')
            ->where('item_harga.item_satuan_id', $satuan)
            ->where('item_harga.item_id', $item)
            ->where('item_harga.tanggal <=', date('Y-m-d H:i:s'))
            ->where('item_harga.cabang_id', $cabang_id)
            ->order_by('item_harga.tanggal', 'desc')
            ->get();

        return $result;
	}

}

