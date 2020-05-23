<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_harga_item_m extends MY_Model {

	protected $_table        = 'supplier_harga_item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'supplier.id'				=> 'id',
		'supplier.kode'				=> 'kode',
		'supplier.nama'				=> 'nama',
		'supplier.orang_yang_bersangkutan'	=> 'kontak_person',
		'supplier_alamat.alamat'	=> 'alamat',
		'supplier_alamat.kota_id'	=> 'kota',
		'supplier_telp.no_telp'		=> 'no_telp',
		'supplier.rating'			=> 'rating',
		'supplier.email'			=> 'email',
		'(SELECT region.nama FROM region WHERE region.tipe = 5 AND supplier_alamat.kelurahan_id = region.id )' => 'kelurahan',
		'(SELECT region.nama FROM region WHERE region.tipe = 4 AND supplier_alamat.kecamatan_id = region.id )' => 'kecamatan',
		'(SELECT region.nama FROM region WHERE region.tipe = 3 AND supplier_alamat.kota_id = region.id )' => 'kota',
		'(SELECT region.nama FROM region WHERE region.tipe = 2 AND supplier_alamat.provinsi_id = region.id )' => 'propinsi',
		'(SELECT region.nama FROM region WHERE region.tipe = 1 AND supplier_alamat.negara_id = region.id )' => 'negara',
		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tipe)
	{	
		$join1 = array('supplier_alamat', $this->_table.'.id = supplier_alamat.supplier_id');
		$join2 = array('supplier_telp', $this->_table.'.id = supplier_telp.supplier_id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('supplier_telp.is_primary',1);
		$this->db->where($this->_table.'.tipe', $tipe);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('supplier_telp.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.tipe', $tipe);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('supplier_telp.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.tipe', $tipe);

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
		$sekarang = date('Y-m-d');
         $format = "SELECT 
						supplier_harga_item.harga as harga,
						supplier_harga_item.tanggal_efektif as tanggal_efektif
					FROM 
						supplier_harga_item,
						supplier_item
					WHERE 
						supplier_item.id = supplier_harga_item.supplier_item_id AND
						supplier_harga_item.supplier_item_id = $id AND
						supplier_harga_item.tanggal_efektif <= '$sekarang'
					ORDER BY supplier_harga_item.tanggal_efektif DESC
		";

        return $this->db->query($format);
	}

	public function get_harga_edit($id)
	{
		$sekarang = date('Y-m-d');
         $format = "SELECT 
						supplier_harga_item.harga as harga,
						supplier_harga_item.tanggal_efektif as tanggal_efektif
					FROM 
						supplier_harga_item,
						supplier_item
					WHERE 
						supplier_item.id = supplier_harga_item.supplier_item_id AND
						supplier_harga_item.supplier_item_id = $id AND
						supplier_harga_item.tanggal_efektif <= '$sekarang'
					ORDER BY supplier_harga_item.tanggal_efektif DESC
		";

        return $this->db->query($format);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/supplier_m.php */