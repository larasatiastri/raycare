<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_m extends MY_Model {

	protected $_table        = 'pmb';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pmb.id' 		=>'id',
		'pmb.no_pmb'	=>'no_pmb',
		'pmb.no_surat_jalan'	=>'no_surat',
		'supplier.nama'	=>'nama_supplier',
		'pmb.tanggal'	=>'tanggal_datang',
		'supplier.kode'	=>'kode_supplier',
		'pmb.is_active'	=>'is_active'
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
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);

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

	public function get_data($id)
	{
		$format = "SELECT
					pembelian.no_pembelian AS no_po
					FROM
					pmb ,
					pmb_detail ,
					pembelian ,
					pmb_po_detail
					WHERE
					pmb.id = pmb_detail.pmb_id AND
					pmb_detail.id = pmb_po_detail.pmb_detail_id AND
					pmb_po_detail.po_detail_id = pembelian.id AND
					pmb.id = $id
					";

		return $this->db->query($format);
	}

	public function get_data_supplier($id)
	{
		 $format = "SELECT
					supplier.nama,
					supplier.kode,
					supplier.orang_yang_bersangkutan,
					supplier_alamat.alamat as alamat,
					supplier_alamat.rt_rw as rt_rw,
					(SELECT region.nama FROM region WHERE region.tipe = 5 AND supplier_alamat.kelurahan_id = region.id ) kelurahan,
					(SELECT region.nama FROM region WHERE region.tipe = 4 AND supplier_alamat.kecamatan_id = region.id ) kecamatan,
					(SELECT region.nama FROM region WHERE region.tipe = 3 AND supplier_alamat.kota_id = region.id ) kota,
					(SELECT region.nama FROM region WHERE region.tipe = 2 AND supplier_alamat.provinsi_id = region.id ) propinsi,
					(SELECT region.nama FROM region WHERE region.tipe = 1 AND supplier_alamat.negara_id = region.id ) negara
				   
					FROM
					pmb ,
					supplier,
					supplier_alamat
					WHERE
					pmb.supplier_id = supplier.id AND
					supplier_alamat.supplier_id = supplier.id AND
					pmb.id = $id
					";

		 return $this->db->query($format);
	}

	public function get_data_no_telp($id)
	{
		$format = "SELECT
					supplier_telp.no_telp as no_telp			   
					FROM
					pmb ,
					supplier,
					supplier_telp
					WHERE
					pmb.supplier_id = supplier.id AND
					supplier_telp.supplier_id = supplier.id AND
					pmb.id =  $id
		";

		return $this->db->query($format);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */