<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penambahan_modal_m extends MY_Model {

	protected $_table        = 'penambahan_modal';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'penambahan_modal.id'                     	=> 'id', 
		'penambahan_modal.no_permintaan' 			=> 'no_permintaan', 
		'penambahan_modal.tanggal'          		=> 'tanggal',					
		'penambahan_modal.nominal'                	=> 'nominal',
		'penambahan_modal.status'                	=> 'status',
		'penambahan_modal.keperluan'               => 'keperluan',
		'penambahan_modal.no_rek_tujuan'        	=> 'no_rek_tujuan',
		'penambahan_modal.bank_tujuan'             => 'bank_tujuan',
		'penambahan_modal.a_n_bank_tujuan'        => 'a_n_bank_tujuan',
		'penambahan_modal.is_active'              	=> 'is_active',
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

		$join_tables = array();

		
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		$this->db->where('penambahan_modal.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		$this->db->where('penambahan_modal.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		

		$this->db->where('penambahan_modal.is_active',1);
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

	

	public function get_no_penambahan_modal()
	{
		$format = "SELECT MAX(SUBSTRING(`no_penambahan_modal`,6,3)) AS max_no_penambahan_modal FROM `penambahan_modal` WHERE RIGHT(`no_penambahan_modal`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_penambahan_modal()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `penambahan_modal` WHERE SUBSTR(`id`,5,6) = DATE_FORMAT(NOW(), '%m%Y');";	
		return $this->db->query($format);
	}

	public function get_penambahan_modal($penambahan_modal_id)
	{

		$sql = "SELECT
					penambahan_modal_detail.penambahan_modal_id,
					penambahan_modal_detail.item_id,
					penambahan_modal_detail.item_satuan_id,
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