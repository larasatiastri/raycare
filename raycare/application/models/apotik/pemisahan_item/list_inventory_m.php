<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_inventory_m extends MY_Model {

	protected $_table        = 'item_satuan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item.id'		=> 'id',
		'item.kode'			=> 'kode_item',		
		'item.nama'			=> 'nama_item',
		'(SELECT COUNT(*) FROM item_satuan WHERE item.id = item_satuan.item_id)' => 'jumlah_satuan',
		'gudang.id'			=> 'id_gudang'
		
	);	

	 // Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_info_item = array(
		//column di table  => alias
		'SUM(inventory.jumlah)'		=> 'jumlah_item',
		'item_satuan.nama'			=> 'satuan'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id_gudang, $kategori, $sub_kategori)
	{	

		$join1 = array('inventory', $this->_table.'.id = inventory.item_satuan_id');
		$join2 = array('item', 'inventory.item_id = item.id');
		$join3 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id');
		$join4 = array('item_kategori', 'item_sub_kategori.item_kategori_id = item_kategori.id');
		$join5 = array('gudang', 'inventory.gudang_id = gudang.id');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		if($id_gudang == 0 && $kategori == 0 && $sub_kategori == 0)
		{
			$wheres = array();
		}
		else
		{
			// jika gudang dipilih
			if ($kategori == 0 && $sub_kategori == 0) 
			{
				$wheres = array(
					'gudang.cabang_id' 		=> $this->session->userdata('cabang_id'),
					'inventory.gudang_id' 	=> $id_gudang,
				);
			}

			// jika kategori dipilih
			elseif ($id_gudang == 0 && $sub_kategori == 0) 
			{
				$wheres = array(
					'gudang.cabang_id' 		=> $this->session->userdata('cabang_id'),
					'item_kategori.id' 		=> $kategori,
				);
			}

			// jika kategori dan sub kategori dipilih
			elseif ($id_gudang == 0) 
			{
				$wheres = array(
					'gudang.cabang_id' 		=> $this->session->userdata('cabang_id'),
					'item_kategori.id' 		=> $kategori,
					'item_sub_kategori.id' 	=> $sub_kategori
				);
			}

			// jika kategori dan gudang dipilih
			elseif ($sub_kategori == 0) 
			{
				$wheres = array(
					'gudang.cabang_id' 		=> $this->session->userdata('cabang_id'),
					'inventory.gudang_id' 	=> $id_gudang,
					'item_kategori.id' 		=> $kategori,
				);
			}

			// jika semua dipilih
			elseif ($id_gudang != 0 && $kategori != 0 && $sub_kategori != 0) 
			{
				$wheres = array(
					'gudang.cabang_id' 		=> $this->session->userdata('cabang_id'),
					'inventory.gudang_id' 	=> $id_gudang,
					'item_kategori.id' 		=> $kategori,
					'item_sub_kategori.id' 	=> $sub_kategori
				);
			}

			
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item.is_active',1);
		$this->db->where($wheres);
		$this->db->group_by('item.id, gudang.id');
		// dapatkan total row count;

		$query = $this->db->select('(1)')->get();
	    $total_records = $query->num_rows();
		//$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item.is_active',1);
		$this->db->group_by('item.id, gudang.id');
		$this->db->where($wheres);
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
	    $total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item.is_active',1);
		$this->db->group_by('item.id, gudang.id');
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
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_info_item($id, $id_gudang)
	{	
		$join1 = array('inventory', $this->_table.'.id = inventory.item_satuan_id');
		$join2 = array('item', 'inventory.item_id = item.id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_info_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		$this->db->where('item.id', $id);
		$this->db->where('inventory.gudang_id', $id_gudang);
		$this->db->group_by($this->_table.'.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		$this->db->where('item.id', $id);
		$this->db->where('inventory.gudang_id', $id_gudang);
		$this->db->group_by($this->_table.'.id');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);
		$this->db->where('item.id', $id);
		$this->db->where('inventory.gudang_id', $id_gudang);
		$this->db->group_by($this->_table.'.id',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_info_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_info_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_data_stok_awal($id, $id_gudang)
	{
		$format = "SELECT inventory.item_satuan_id AS id_satuan, 
						SUM(inventory.jumlah) AS jumlah_item, 
						item_satuan.nama AS satuan
				FROM item_satuan
				JOIN inventory ON item_satuan.id = inventory.item_satuan_id
				JOIN item ON inventory.item_id = item.id
				JOIN gudang ON inventory.gudang_id = gudang.id
				WHERE item.is_active =  1
					AND item.id = $id
					AND gudang.id = '$id_gudang'
				GROUP BY item_satuan.id";

		return $this->db->query($format, $id);
	}

	public function get_stok_awal($id_satuan, $id, $id_gudang)
	{
		$format = "SELECT inventory.item_satuan_id AS id_satuan, 
						SUM(inventory.jumlah) AS jumlah_item, 
						item_satuan.nama AS satuan
				FROM item_satuan
				JOIN inventory ON item_satuan.id = inventory.item_satuan_id
				JOIN item ON inventory.item_id = item.id
				JOIN gudang ON inventory.gudang_id = gudang.id
				WHERE item.is_active =  1
					AND item.id = $id
					AND gudang.id = '$id_gudang'
					AND inventory.item_satuan_id = $id_satuan
				GROUP BY item_satuan.id";

		return $this->db->query($format, $id);
	}

	public function get_data_stok_akhir($id)
	{
		$format = "SELECT
					item_satuan.id AS id,
					SUM(inventory.jumlah) AS jumlah_item,
					item_satuan.nama as satuan,
					item_satuan.item_id,
					inventory.id AS id_inventory
					FROM
					inventory
					LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id
					WHERE
					item_satuan.item_id = $id
					GROUP BY
					item_satuan.id
		";

		return $this->db->query($format, $id);
	}

	public function get_jumlah($id, $id_konversi, $id_awal)
	{
		$format = "SELECT *
					FROM item_satuan
					WHERE item_id =  $id
					AND parent_id BETWEEN $id_awal AND $id_konversi
					ORDER BY id";

		return $this->db->query($format);
	}

	public function get_info_satuan($item_id, $gudang_id)
	{
		$format = "SELECT
						SUM(inventory.jumlah) AS jumlah_item,
						item_satuan.nama AS satuan
					FROM
						item_satuan
					LEFT JOIN inventory ON item_satuan.id = inventory.item_satuan_id
					JOIN item ON inventory.item_id = item.id
					WHERE
						is_active = 1
					AND item.id = ".$item_id."
					AND inventory.gudang_id = '".$gudang_id."'
					GROUP BY
						item_satuan.id";

		return $this->db->query($format);
	}

	public function get_data($id)
	{
		$sql = "SELECT
				*
				FROM
				item_satuan
				WHERE
				(
				id IN (SELECT parent_id FROM item_satuan)
				AND parent_id IS NOT NULL
				AND item_id = $id
				)

				OR (parent_id IS NULL)
				AND item_id = $id

				ORDER BY
				parent_id";

		return $this->db->query($sql);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */