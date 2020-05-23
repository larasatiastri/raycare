<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_detail_m extends MY_Model {

	protected $_table        = 'pembelian_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
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
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);

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

	public function get_data_gudang()
	{
		$format = "SELECT id as id, informasi as nama_gudang
					FROM gudang
					WHERE is_active = 1";

		return $this->db->query($format);
	}

	public function get_data_pembelian_detail($id)
	{
		$id = rtrim($id,',');
		$format = "SELECT
					pembelian_detail.id,
					pembelian_detail.pembelian_id,
					pembelian_detail.item_id,
					pembelian_detail.item_satuan_id,
					(SUM(jumlah_pesan)) as jumlah_pesan,
					(SUM(jumlah_disetujui)) as jumlah_disetujui,
					(SUM(jumlah_diterima)) as jumlah_diterima,
					(SUM(jumlah_belum_diterima)) as jumlah_belum_diterima,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS satuan_nama,
					pembelian.no_pembelian as no_po,
					pembelian_detail.harga_beli as harga_beli,
					pembelian_detail.item_satuan_id_primary as `primary`
					FROM
					pembelian_detail
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN pembelian ON pembelian_detail.pembelian_id = pembelian.id
					WHERE
					pembelian_detail.pembelian_id in ('$id')
					AND pembelian_detail.`status` = 2
					AND pembelian_detail.jumlah_belum_diterima != 0
					GROUP BY pembelian_detail.item_id
					ORDER BY pembelian_detail.id";

		return $this->db->query($format);
	}

	public function get_data_po_detail($id)
	{
		$format = "SELECT
					pembelian_detail.id,
					pembelian_detail.pembelian_id,
					pembelian_detail.item_id,
					pembelian_detail.item_satuan_id,
					(SUM(jumlah_pesan)) as jumlah_pesan,
					(SUM(jumlah_disetujui)) as jumlah_disetujui,
					(SUM(jumlah_diterima)) as jumlah_diterima,
					(SUM(jumlah_belum_diterima)) as jumlah_belum_diterima,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS satuan_nama,
					pembelian.no_pembelian as no_po,
					pembelian_detail.harga_beli as harga_beli,
					pembelian_detail.item_satuan_id_primary as `primary`
					FROM
					pembelian_detail
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN pembelian ON pembelian_detail.pembelian_id = pembelian.id
					WHERE
					pembelian_detail.pembelian_id = '$id'
					AND pembelian_detail.`status` = 2
					AND pembelian_detail.jumlah_belum_diterima != 0
					GROUP BY pembelian_detail.item_id
					ORDER BY pembelian_detail.id";

		return $this->db->query($format);
	}

	public function get_data_pembelian_detail_save($id)
	{
		$id = rtrim($id,',');
		$format = "SELECT
					pembelian_detail.id,
					pembelian_detail.pembelian_id,
					pembelian_detail.item_id,
					pembelian_detail.item_satuan_id,
					(SUM(jumlah_pesan)) as jumlah_pesan,
					(SUM(jumlah_disetujui)) as jumlah_disetujui,
					(SUM(jumlah_diterima)) as jumlah_diterima,
					(SUM(jumlah_belum_diterima)) as jumlah_belum_diterima,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS satuan_nama,
					pembelian.no_pembelian as no_po,
					pembelian_detail.harga_beli as harga_beli,
					pembelian_detail.item_satuan_id_primary as `primary`
					FROM
					pembelian_detail
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN pembelian ON pembelian_detail.pembelian_id = pembelian.id
					WHERE
					pembelian_detail.pembelian_id in ('$id')
					AND pembelian_detail.`status` = 2
					AND pembelian_detail.jumlah_belum_diterima != 0
					GROUP BY pembelian_detail.item_id, 
					ORDER BY pembelian_detail.id";

		return $this->db->query($format);
	}

	public function get_data_detail_satuan($id)
	{
		$id = rtrim($id,',');
		$format = "SELECT
					pembelian_detail.id,
					pembelian_detail.pembelian_id,
					pembelian_detail.item_id,
					pembelian_detail.item_satuan_id,
					jumlah_pesan as jumlah_pesan,
					jumlah_disetujui as jumlah_disetujui,
					jumlah_diterima as jumlah_diterima,
					jumlah_belum_diterima as jumlah_belum_diterima,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS satuan_nama,
					pembelian.no_pembelian as no_po,
					pembelian_detail.harga_beli as harga_beli,
					pembelian_detail.item_satuan_id_primary as `primary`
					FROM
					pembelian_detail
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN pembelian ON pembelian_detail.pembelian_id = pembelian.id
					WHERE
					pembelian_detail.pembelian_id in ('$id')
					AND pembelian_detail.`status` = 2
					AND pembelian_detail.jumlah_belum_diterima != 0
					ORDER BY pembelian_detail.id ASC";

		return $this->db->query($format);
	}
	
	public function get_data_pembelian_detail_view($pmb_id)
	{
		$format = "SELECT
					pembelian_detail.id,
					pembelian_detail.pembelian_id,
					pembelian_detail.item_id,
					pembelian_detail.item_satuan_id,
					Sum(pembelian_detail.jumlah_pesan) AS jumlah_pesan,
					Sum(pembelian_detail.jumlah_diterima) AS jumlah_diterima,
					Sum(pembelian_detail.jumlah_belum_diterima) AS jumlah_belum_diterima,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS satuan_nama,
					pembelian.no_pembelian AS no_po,
					pembelian_detail.harga_beli AS harga_beli,
					pmb_po_detail.jumlah
					FROM
					pembelian_detail
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN pembelian ON pembelian_detail.pembelian_id = pembelian.id
					LEFT JOIN pmb_po_detail ON pembelian_detail.id = pmb_po_detail.po_detail_id
					LEFT JOIN pmb_detail ON pmb_po_detail.pmb_detail_id = pmb_detail.id
					LEFT JOIN pmb ON pmb_detail.pmb_id = pmb.id
					WHERE
					pmb.id = $pmb_id
					GROUP BY
						pembelian_detail.item_id
					";

		return $this->db->query($format);
	}

	public function get_data_jumlah_pesan_supplier_lain($supplier_id, $item_id, $item_satuan_id)
	{
		$format = "SELECT
						pembelian.id AS id,
						pembelian.no_pembelian AS no_pembelian,
						pembelian.tanggal_pesan AS tanggal_pesan,
						pembelian.tanggal_kadaluarsa AS tanggal_kadaluarsa,
						pembelian.tanggal_kirim AS tanggal_kirim,
						(SUM(pembelian_detail.jumlah_pesan)) AS jumlah_pesan,
						(SUM(pembelian_detail.jumlah_diterima)) AS jumlah_diterima,
						(SUM(pembelian_detail.jumlah_belum_diterima)) AS jumlah_belum_diterima,
						pembelian.supplier_id AS supplier_id,
						pembelian_detail.item_id AS item_id,
						pembelian_detail.item_satuan_id AS item_satuan_id
					FROM
						pembelian
					LEFT JOIN pembelian_detail ON pembelian.id = pembelian_detail.pembelian_id
					WHERE
						pembelian.supplier_id != $supplier_id
					AND pembelian_detail.item_id = '$item_id'
					AND pembelian_detail.item_satuan_id = '$item_satuan_id'";

		return $this->db->query($format);
	}

	public function get_pembelian_detail_by_item($item_id, $item_satuan_id, $pembelian_id){
		$format = "SELECT *
					FROM pembelian_detail
					WHERE item_id =  '$item_id'
					AND item_satuan_id =  '$item_satuan_id'
					AND pembelian_id in ($pembelian_id)
					ORDER BY 'id'";
		return $this->db->query($format);
	}

	public function get_data_detail($id, $item_id)
	{
		$format = "SELECT id FROM `pembelian_detail` WHERE item_id = '$item_id' AND pembelian_id in ('$id')";	
		return $this->db->query($format);
	}

	public function get_data_item($po_id, $item_id)
	{
		$po_id = rtrim($po_id,',');

		$format = "SELECT
					pembelian_detail.id,
					pembelian_detail.pembelian_id,
					pembelian_detail.item_id,
					pembelian_detail.item_satuan_id,
					(SUM(jumlah_pesan)) as jumlah_pesan,
					(SUM(jumlah_disetujui)) as jumlah_disetujui,
					(SUM(jumlah_diterima)) as jumlah_diterima,
					(SUM(jumlah_belum_diterima)) as jumlah_belum_diterima,
					item.kode AS item_kode,
					item.nama AS item_nama,
					item_satuan.nama AS satuan_nama,
					pembelian.no_pembelian as no_po,
					pembelian_detail.harga_beli as harga_beli,
					pembelian_detail.item_satuan_id_primary as `primary`
					FROM
					pembelian_detail
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN pembelian ON pembelian_detail.pembelian_id = pembelian.id
					WHERE
					pembelian_detail.pembelian_id in ('$po_id')
					AND pembelian_detail.item_id = '$item_id'
					AND pembelian_detail.`status` = 2
					GROUP BY pembelian_detail.item_id, pembelian_detail.item_satuan_id
					ORDER BY pembelian_detail.id";
					
		return $this->db->query($format);
	}

	public function get_data_belumterima($po_id, $item_id)
	{
		$SQL = "SELECT * FROM pembelian_detail WHERE pembelian_id IN ('$po_id') AND item_id = '$item_id' AND `status` = 2 AND jumlah_belum_diterima != 0 ORDER BY pembelian_id ASC";
		return $this->db->query($SQL);
	}

}



/* End of file pembelian_detail_m.php */
/* Location: ./application/models/gudang/pembelian_detail_m.php */