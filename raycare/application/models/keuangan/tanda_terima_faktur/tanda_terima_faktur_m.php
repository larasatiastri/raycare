<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tanda_terima_faktur_m extends MY_Model {

	protected $_table        = 'tanda_terima_faktur';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'tanda_terima_faktur.id'                     => 'id', 
		'tanda_terima_faktur.no_tanda_terima_faktur' => 'no_tanda_terima_faktur', 
		'tanda_terima_faktur.supplier_id'            => 'supplier_id',
		'tanda_terima_faktur.pembelian_id'           => 'pembelian_id',				
		'tanda_terima_faktur.nominal'                => 'nominal',
		'tanda_terima_faktur.created_date'           => 'tanggal',
		'tanda_terima_faktur.is_active'              => 'is_active',
		'tanda_terima_faktur.created_by'             => 'created_by',
		'tanda_terima_faktur.diserahkan_oleh'        => 'nama_penyerah',
		'user.nama'                                  => 'nama_penerima',
		'pembelian.no_pembelian'                     => 'no_pembelian',
		'supplier.nama'                              => 'nama_supplier',
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

		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id', 'left');
		$join2 = array('pembelian', $this->_table.'.pembelian_id = pembelian.id', 'left');
		$join3 = array('user', $this->_table.'.created_by = user.id', 'left');
		
		$join_tables = array($join1,$join2,$join3);

		
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		$this->db->where('tanda_terima_faktur.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		$this->db->where('tanda_terima_faktur.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		

		$this->db->where('tanda_terima_faktur.is_active',1);
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

	

	public function get_no_tanda_terima_faktur()
	{
		$format = "SELECT MAX(SUBSTRING(`no_tanda_terima_faktur`,6,3)) AS max_no_tanda_terima_faktur FROM `tanda_terima_faktur` WHERE RIGHT(`no_tanda_terima_faktur`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_tanda_terima_faktur()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `tanda_terima_faktur` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_tanda_terima_faktur($tanda_terima_faktur_id)
	{

		$sql = "SELECT
					tanda_terima_faktur_detail.tanda_terima_faktur_id,
					tanda_terima_faktur_detail.item_id,
					tanda_terima_faktur_detail.item_satuan_id,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS item_satuan,
					SUM(inventory.jumlah) AS stock,
					tanda_terima_faktur_detail.jumlah AS jumlah_permintaan,
					item.is_identitas AS is_identitas,
					inventory.gudang_id as gudang_id,
					inventory.pmb_id as pmb_id,
					inventory.harga_beli as harga_beli
				FROM
					tanda_terima_faktur_detail
				JOIN item ON item.id = tanda_terima_faktur_detail.item_id
				JOIN item_satuan ON item_satuan.id = tanda_terima_faktur_detail.item_satuan_id
				JOIN inventory ON inventory.item_id = tanda_terima_faktur_detail.item_id
				AND inventory.item_satuan_id = tanda_terima_faktur_detail.item_satuan_id
				WHERE
					tanda_terima_faktur_detail.tanda_terima_faktur_id = $tanda_terima_faktur_id
				GROUP BY
					tanda_terima_faktur_detail.id";

		return $this->db->query($sql);

	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */