<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_item_inventory_m extends MY_Model {

	protected $_table        = 'inventory';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		
		'item.nama'                => 'item_nama',
		'item.kode'                => 'item_kode',
		'SUM(inventory.jumlah)'    => 'stock',
		'item_satuan.nama'         => 'item_satuan',
		'item_satuan.id'           => 'item_satuan_id',
		'item.id'                  => 'item_id',
		'inventory.box_paket_id'   => 'box_paket_id',
		'inventory.kode_box_paket' => 'kode_box_paket',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable($id_gudang, $kategori, $sub_kategori)
	{	

		$join1 = array('item', $this->_table.'.item_id = item.id', 'right');
		$join2 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$join3 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id');
		$join4 = array('item_kategori', 'item_sub_kategori.item_kategori_id = item_kategori.id');
		$join5 = array('gudang', $this->_table.'.gudang_id = gudang.id');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		if($id_gudang == 0 && $kategori == 0 && $sub_kategori == 0)
		{
			$wheres = array(
				'gudang.is_salable'              => 1,
				'inventory.box_paket_id IS NULL' => NULL
			);
		}
		else
		{
			// jika gudang dipilih
			if ($kategori == 0 && $sub_kategori == 0) 
			{
				$wheres = array(
					'gudang.is_salable'              => 1,
					'inventory.box_paket_id IS NULL' => NULL,
					'gudang.cabang_id'               => $this->session->userdata('cabang_id'),
					'inventory.gudang_id'            => $id_gudang,
				);
			}

			// jika kategori dipilih
			elseif ($id_gudang == 0 && $sub_kategori == 0) 
			{
				$wheres = array(
					'gudang.is_salable'              => 1,
					'inventory.box_paket_id IS NULL' => NULL,
					'gudang.cabang_id'               => $this->session->userdata('cabang_id'),
					'item_kategori.id'               => $kategori,
				);
			}

			// jika kategori dan sub kategori dipilih
			elseif ($id_gudang == 0) 
			{
				$wheres = array(
					'gudang.is_salable'              => 1,
					'inventory.box_paket_id IS NULL' => NULL,
					'gudang.cabang_id'               => $this->session->userdata('cabang_id'),
					'item_kategori.id'               => $kategori,
					'item_sub_kategori.id'           => $sub_kategori
				);
			}

			// jika kategori dan gudang dipilih
			elseif ($sub_kategori == 0) 
			{
				$wheres = array(
					'gudang.is_salable'              => 1,
					'inventory.box_paket_id IS NULL' => NULL,
					'gudang.cabang_id'               => $this->session->userdata('cabang_id'),
					'inventory.gudang_id'            => $id_gudang,
					'item_kategori.id'               => $kategori,
				);
			}

			// jika semua dipilih
			elseif ($id_gudang != 0 && $kategori != 0 && $sub_kategori != 0) 
			{
				$wheres = array(
					'gudang.is_salable'              => 1,
					'inventory.box_paket_id IS NULL' => NULL,
					'gudang.cabang_id'               => $this->session->userdata('cabang_id'),
					'inventory.gudang_id'            => $id_gudang,
					'item_kategori.id'               => $kategori,
					'item_sub_kategori.id'           => $sub_kategori
				);
			}
		}


		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		// $this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		// $this->db->group_by('inventory.item_satuan_id');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		// $this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		// $this->db->group_by('inventory.item_satuan_id');
		
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		// $this->db->where('item_satuan.is_primary', 1);
		$this->db->group_by('inventory.item_id');
		// $this->db->group_by('inventory.item_satuan_id');

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

	public function get_satuan($item_id, $gudang_id)
	{

		$format = "SELECT
						item.id AS item_id,
						item.kode AS item_kode,
						item.nama AS item_nama,
						SUM(inventory.jumlah) AS stock,
						item_satuan.nama AS item_satuan,
						item_satuan.id AS satuan_id
					FROM
						inventory
					RIGHT JOIN item ON inventory.item_id = item.id
					LEFT JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id
					WHERE inventory.item_id = '$item_id'
					AND	inventory.gudang_id = '$gudang_id'
					GROUP BY
						inventory.item_id,
						inventory.item_satuan_id
		";

		return $this->db->query($format);
	}

	public function get_harga($item_id)
	{
		$format = "SELECT
						item.id AS item_id,
						item.kode AS item_kode,
						item.nama AS item_name,
						item_satuan.nama AS item_satuan,
						item_satuan.id AS item_satuan_id,
						item_harga.harga AS harga,
						item_harga.tanggal AS tanggal
					FROM
						inventory
					RIGHT JOIN item ON item.id = inventory.item_id
					LEFT JOIN item_satuan ON item_satuan.id = inventory.item_satuan_id
					LEFT JOIN item_harga ON item_harga.item_id = inventory.item_id AND item_harga.item_satuan_id = inventory.item_satuan_id
					WHERE inventory.item_id = '$item_id' AND item_harga.tanggal <= '".date('Y-m-d H:i:s')."' AND item_satuan.is_primary = 1
					ORDER BY item_harga.tanggal DESC
		";

		return $this->db->query($format);
	}

	public function get_satuan_harga($item_satuan_id)
	{
		$format = "SELECT
						item.id AS item_id,
						item.nama AS item_name,
						item_harga.harga AS harga,
						item_harga.tanggal AS tanggal
					FROM
						inventory
					RIGHT JOIN item ON item.id = inventory.item_id
					LEFT JOIN item_harga ON item_harga.item_id = inventory.item_id
										AND item_harga.item_satuan_id = inventory.item_satuan_id
					WHERE
						inventory.item_satuan_id = '$item_satuan_id'
					AND item_harga.tanggal <= '".date('Y-m-d H:i:s')."'
					GROUP BY item_harga.harga
					ORDER BY
						item_harga.tanggal DESC

		";

		return $this->db->query($format);
	}
}
