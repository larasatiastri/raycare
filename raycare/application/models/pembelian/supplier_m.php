<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_m extends MY_Model {

	protected $_table        = 'supplier';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'supplier.id'					=> 'id',
		'supplier.kode'				=> 'kode',
		'supplier.nama'				=> 'nama',
		'supplier.orang_yang_bersangkutan'	=> 'kontak_person',
		'supplier_alamat.alamat'	=> 'alamat',
		'supplier_alamat.kota_id'	=> 'kota',
		'supplier_telp.no_telp'		=> 'no_telp',
		'supplier.rating'			=> 'rating',
		'supplier.is_pkp'			=> 'is_pkp',
		'supplier.is_harga_flexible'	=> 'is_harga_flexible',
		'inf_lokasi.nama_kelurahan'	=> 'kelurahan',
		'inf_lokasi.nama_kecamatan'	=> 'kecamatan',
		'inf_lokasi.nama_kabupatenkota'	=> 'kota',
		'inf_lokasi.nama_propinsi'	=> 'propinsi',
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
		$join1 = array('supplier_alamat', $this->_table.'.id = supplier_alamat.supplier_id','left');
		$join2 = array('supplier_telp', $this->_table.'.id = supplier_telp.supplier_id','left');
		$join3 = array('inf_lokasi', 'supplier_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('supplier_telp.is_primary',1);
		$this->db->where('supplier_alamat.is_primary',1);
		$this->db->where($this->_table.'.tipe', $tipe);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('supplier_telp.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('supplier_alamat.is_primary',1);

		$this->db->where($this->_table.'.tipe', $tipe);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('supplier_telp.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('supplier_alamat.is_primary',1);
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

	public function get_data($supplier_id)
	{
		$this->db->select('supplier.*, supplier_alamat.alamat, supplier_alamat.rt_rw, supplier_alamat.kode_pos, inf_lokasi.nama_kelurahan, inf_lokasi.nama_kecamatan, inf_lokasi.nama_kabupatenkota, inf_lokasi.nama_propinsi, supplier_email.email, supplier_telp.no_telp');
		$this->db->join('supplier_alamat', $this->_table.'.id = supplier_alamat.supplier_id','left');
		$this->db->join('supplier_email', $this->_table.'.id = supplier_email.supplier_id', 'left');
		$this->db->join('supplier_telp', $this->_table.'.id = supplier_telp.supplier_id', 'left');
		$this->db->join('inf_lokasi', 'supplier_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.id',$supplier_id);
		$this->db->where('supplier_alamat.is_primary',1);
		$this->db->where('supplier_telp.is_primary',1);

		return $this->db->get($this->_table);

	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/supplier_m.php */