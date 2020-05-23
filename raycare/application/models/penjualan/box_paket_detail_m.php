 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Box_paket_detail_m extends MY_Model {

	protected $_table        = 'box_paket_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		'box_paket.nama' => 'box_nama',
		'box_paket.id'   => 'id',
		
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	

		$join = array();
		$join_tables = array();

		$wheres = array(
			'is_active' => 1
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

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


	public function get_box_paket_detail($box_id)
	{
		$format = "SELECT
						item.id AS item_id,
						item.nama AS item_name,
						item_satuan.id AS item_satuan_id,
						item_satuan.nama AS item_satuan,
						item_harga.harga AS harga
					FROM
						box_paket_detail
					LEFT JOIN item ON item.id = box_paket_detail.item_id
					LEFT JOIN item_satuan ON item_satuan.id = box_paket_detail.item_satuan_id
					LEFT JOIN item_harga ON item_harga.item_id = box_paket_detail.item_id
										AND item_harga.item_satuan_id = box_paket_detail.item_satuan_id
					WHERE
						box_paket_detail.box_paket_id = ".$box_id."
					AND item_harga.tanggal <= '".date('Y-m-d H:i:s')."'
					GROUP BY item_harga.harga
					ORDER BY
						item_harga.tanggal DESC

		";

		return $this->db->query($format);
	}

}
