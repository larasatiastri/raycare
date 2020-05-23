<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class O_s_hutang_m extends MY_Model {

	protected $_table        = 'o_s_hutang';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'o_s_hutang.id'                 	=> 'id', 
		'o_s_hutang.tanggal' 			=> 'tanggal', 
		'o_s_hutang.transaksi_id' 			=> 'transaksi_id', 
		'o_s_hutang.nomor_transaksi' 			=> 'nomor_transaksi', 
		'o_s_hutang.tipe_transaksi'     	=> 'tipe_transaksi',					
		'o_s_hutang.pemberi_hutang_id'      => 'pemberi_hutang_id',
		'o_s_hutang.tipe_pemberi_hutang'    => 'tipe_pemberi_hutang',
		'o_s_hutang.nama_pemberi_hutang'    => 'nama_pemberi_hutang',
		'SUM(o_s_hutang.jumlah)'               	=> 'jumlah',
		'o_s_hutang.created_by'        		=> 'created_by',
		'o_s_hutang.created_date'           => 'created_date'
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
		$this->db->group_by('o_s_hutang.nama_pemberi_hutang');

				// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->group_by('o_s_hutang.nama_pemberi_hutang');
				// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->group_by('o_s_hutang.nama_pemberi_hutang');
		
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

	

	public function get_no_o_s_hutang()
	{
		$format = "SELECT MAX(SUBSTRING(`no_o_s_hutang`,6,3)) AS max_no_o_s_hutang FROM `o_s_hutang` WHERE RIGHT(`no_o_s_hutang`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_o_s_hutang()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `o_s_hutang` WHERE SUBSTR(`id`,5,6) = DATE_FORMAT(NOW(), '%m%Y');";	
		return $this->db->query($format);
	}

	public function get_o_s_hutang($o_s_hutang_id)
	{

		$sql = "SELECT
					o_s_hutang_detail.o_s_hutang_id,
					o_s_hutang_detail.item_id,
					o_s_hutang_detail.item_satuan_id,
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