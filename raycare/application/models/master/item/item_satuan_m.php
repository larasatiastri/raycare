<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_satuan_m extends MY_Model {

	protected $_table        = 'item_satuan';
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
		'item_satuan.id'         => 'id', 
		'item_satuan.item_id'    => 'item_id', 
		'item_satuan.parent_id'  => 'parent_id', 
		'item_satuan.nama'       => 'nama', 
		'item_satuan.jumlah'     => 'jumlah',
		'item_satuan.is_primary' => 'is_primary',
		'item_harga.tanggal'  => 'tanggal',
		'item_harga.harga'    => 'harga',
		
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'item.id'                => 'id', 
		'item.item_sub_kategori' => 'item_sub_kategori', 
		'item.kode'              => 'kode', 
		'item.nama'              => 'nama', 
		'item_satuan.nama'       => 'unit',
		'item_harga.harga'       => 'harga',
		'item_kategori.nama'     => 'kategori_item',
		'item.keterangan'        => 'keterangan',

		
	);

	protected $datatable_columns_harga_item_satuan = array(
		//column di table  => alias
		'item_satuan.item_id'  => 'item_id',
		'item_satuan.id'  => 'satuan_id',
		'item_harga.tanggal'  => 'tanggal',
		'item_satuan.nama'    => 'nama',
		'item_harga.id'    => 'harga_id',
		'item_harga.harga'    => 'harga',

		
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
		// $join4 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');

		$join_tables = array();

		$wheres = array(

				'item_satuan.item_id' => $item_id,
				'item_satuan.is_primary' => 1,

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

		$result->columns               = $this->datatable_columns_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_harga_item_satuan($item_id)
	{	

		$join = array('item_harga', $this->_table.'.id = item_harga.item_satuan_id', 'left');

		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_harga_item_satuan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.item_id',$item_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.item_id',$item_id);

		

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.item_id',$item_id);



		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_harga_item_satuan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_harga_item_satuan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_harga_item_satuan_modal($item_id)
	{	

		$join = array('item_harga', $this->_table.'.id = item_harga.item_satuan_id', 'left');

		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_harga_item_satuan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->group_by($this->_table.'.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->group_by($this->_table.'.id');

		

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->group_by($this->_table.'.id');
		


		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_harga_item_satuan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_harga_item_satuan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_harga_item_satuan_tanggal_sekarang($item_id, $tanggal, $item_satuan_id)
	{	

		$join = array('item_harga', $this->_table.'.id = item_harga.item_satuan_id', 'left');

		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_harga_item_satuan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->where('item_harga.tanggal <=', $tanggal);
		$this->db->group_by('item_harga.item_satuan_id <=', $item_satuan_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->where('item_harga.tanggal <=', $tanggal);
		$this->db->group_by('item_harga.item_satuan_id <=', $item_satuan_id);


		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->where('item_harga.tanggal <=', $tanggal);
		$this->db->group_by('item_harga.item_satuan_id <=', $item_satuan_id);


		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_harga_item_satuan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_harga_item_satuan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_harga_item_satuan_by_tanggal($item_id, $tanggal)
	{	

		$join = array('item_harga', $this->_table.'.id = item_harga.item_satuan_id', 'left');

		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_harga_item_satuan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->where('item_harga.tanggal', $tanggal);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->where('item_harga.tanggal', $tanggal);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.item_id',$item_id);
		$this->db->where('item_harga.tanggal', $tanggal);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_harga_item_satuan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_harga_item_satuan;
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
		$query = $this->db->query('SELECT id, kode FROM item_satuan_satuan');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	public function delete_satuan($item_id)
	{
		$format = "DELETE
				   FROM item_satuan
				   WHERE item_id = $item_id";

		return $this->db->query($format);
	}

	public function set_primary_to_0($item_id)
	{
		$format = "UPDATE item_satuan
				   SET is_primary='0'
				   WHERE item_id = $item_id";

		return $this->db->query($format);
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

	public function get_satuan_terkecil($item_id){

    	$format = "SELECT * FROM item_satuan
					WHERE item_id = '$item_id'
					AND id NOT IN (SELECT parent_id FROM item_satuan WHERE parent_id IS NOT NULL AND parent_id != '' AND item_id = '$item_id')";

		return $this->db->query($format);

    }

    public function get_data_konversi($item_id, $satuan_terkecil_id){

    	$format = "SELECT * FROM item_satuan WHERE id = '$satuan_terkecil_id'";
    	$return = $this->db->query($format)->result_array();

    	$data_konversi[] = $return[0];
    	$parent_id = $data_konversi[0]['parent_id'];

    	$selectable = false;
    	if ($data_konversi[0]['is_primary']) {
    		$selectable = true;
    	}

    	$nilai_konversi = NULL;
    	if ($selectable) {
    		if ($data_konversi[0]['is_primary']) {
    			$nilai_konversi = 1;
    		}
    		else{
    			$nilai_konversi = $nilai_konversi * $data_konversi[0]['jumlah'];
    		}
    	}
	    	
	    $data_konversi[0]['selectable'] = $selectable;
	    $data_konversi[0]['nilai_konversi'] = $nilai_konversi;

	    $i = 1;
    	while ($parent_id != NULL) 
    	{
			$format          = "SELECT * FROM item_satuan WHERE id = '$parent_id'";
			$return          = $this->db->query($format)->result_array();

			$data_konversi[] = $return[0];
			$parent_id       = $data_konversi[$i]['parent_id'];

			if ($data_konversi[$i]['is_primary']) {
	    		$selectable = true;
	    	}

	    	if ($selectable) {
	    		if ($data_konversi[$i]['is_primary']) {
	    			$nilai_konversi = 1;
	    		}
	    		else{
	    			$nilai_konversi = $nilai_konversi * $data_konversi[$i-1]['jumlah'];
	    		}
	    	}

	    	$data_konversi[$i]['selectable'] = $selectable;
	    	$data_konversi[$i]['nilai_konversi'] = $nilai_konversi;

	    	$i++;
    	}

		return $data_konversi;
    }

}

/* End of file Item_satuan_m.php */
/* Location: ./application/models/master/cabang_m.php */