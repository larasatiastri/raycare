<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paket_batch2_m extends MY_Model {

	protected $_table        = 'paket_batch';
	protected $_order_by     = 'id ASC';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'paket_id',
		'tipe',
		'nama',
		'level_order', 
		'is_active', 		
	);

	private $_fillable_edit = array(
		'id',
		'paket_id',
		'tipe',
		'nama',
		'level_order', 
		'is_active', 	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'paket_batch.id'          => 'id', 
		'paket_batch.paket_id'    => 'paket_id', 
		'paket_batch.tipe'        => 'tipe', 
		'paket_batch.nama'        => 'nama', 
		'paket_batch.level_order' => 'level_order',
		// 'paket_batch.is_active'     	=> 'is_active',
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id)
	{	
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = "paket_batch.level_order";
		// die_dump($params);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('paket_batch.paket_id',$id);
		$this->db->where('is_active',1);
		// $this->db->order_by('paket_batch.level_order', "asc");
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('paket_batch.paket_id',$id);
		$this->db->where('is_active',1);
		// $this->db->order_by('paket_batch.level_order', "asc");
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('paket_batch.paket_id',$id);
		$this->db->where('is_active',1);
		// $this->db->order_by('paket_batch.level_order', "asc");

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

	public function up_order($paket_id,$id,$order)
    {
        $new_order = $order -1;
        $up = array(
            'level_order' => (integer)($order - 1)
        );
        $this->db->where('paket_id',$paket_id);
        $this->db->where('level_order', $order);
        $this->db->where('id', $id);
        //move menu 1 order up
        $this->db->update($this->_table, $up);
        
        $down = array(
            'level_order' => (integer)($new_order + 1)
        );
        $this->db->where('paket_id',$paket_id);
        $this->db->where('level_order', $new_order);
        $this->db->where('id !=', $id);
        //move menu 1 order down
        $this->db->update($this->_table, $down);
    }

    public function down_order($paket_id,$id,$order)
    {
        $new_order = $order +1;
        $down = array(
            'level_order' => (integer)($order + 1)
        );
        $this->db->where('paket_id',$paket_id);
        $this->db->where('level_order', $order);
        $this->db->where('id', $id);
        //move menu 1 order down
        $this->db->update($this->_table, $down);


        $up = array(
            'level_order' => (integer)($new_order - 1)
        );
        $this->db->where('paket_id',$paket_id);
        $this->db->where('level_order', $new_order);
        $this->db->where('id !=', $id);
        //move menu 1 order up
        $this->db->update($this->_table, $up);
             
    }

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM paket_batch');
		
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

/* End of file paket_batch_m.php */
/* Location: ./application/models/master/paket_batch_m.php */