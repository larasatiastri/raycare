<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class O_s_pembayaran_pembelian_m extends MY_Model {

	protected $_table        = 'o_s_pembayaran_pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'o_s_pembayaran_pembelian.id'           => 'id',
		'o_s_pembayaran_pembelian.pembelian_id' => 'pembelian_id',
		'o_s_pembayaran_pembelian.tanggal'      => 'tanggal',
		'o_s_pembayaran_pembelian.nominal'      => 'nominal',
		'o_s_pembayaran_pembelian.status'       => 'status',
		'pembelian.no_pembelian'                => 'no_pembelian',
		'pembelian.sisa_bayar'                  => 'sisa_bayar',
		'supplier.kode'                         => 'kode_supp',
		'supplier.nama'                         => 'nama_supp',
		
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
		$join1 = array('o_s_pembayaran_pembelian', $this->_table.'.id = o_s_pembayaran_pembelian.o_s_pembayaran_pembelian_id','left');
		$join2 = array('o_s_pembayaran_pembelian', $this->_table.'.id = o_s_pembayaran_pembelian.o_s_pembayaran_pembelian_id','left');
		$join3 = array('inf_lokasi', 'o_s_pembayaran_pembelian.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('o_s_pembayaran_pembelian.is_primary',1);
		$this->db->where('o_s_pembayaran_pembelian.is_primary',1);
		$this->db->where($this->_table.'.tipe', $tipe);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('o_s_pembayaran_pembelian.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('o_s_pembayaran_pembelian.is_primary',1);

		$this->db->where($this->_table.'.tipe', $tipe);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('o_s_pembayaran_pembelian.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('o_s_pembayaran_pembelian.is_primary',1);
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

	public function get_max_id_os_po()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `o_s_pembayaran_pembelian` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}
}

/* End of file o_s_pembayaran_pembelian_m.php */
/* Location: ./application/models/pembelian/o_s_pembayaran_pembelian_m.php */