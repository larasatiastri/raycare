<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_po_history_m extends MY_Model {

	protected $_table        = 'persetujuan_pembelian_history';
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
	public function get_datatable($id)
	{	

		$join1 = array('user_level', $this->_table.'.user_level_id = user_level.id');
		$join2 = array('item_satuan', $this->_table.'.satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2);

		$datatable_columns = array(
		//column di table  => alias 
			'persetujuan_pembelian_history.id'                                                  => 'id', 
			'user_level.nama'                                                                   => 'user_level', 
			'persetujuan_pembelian_history.order'                                               => '`order`', 
			'persetujuan_pembelian_history.status'                                              => '`status`',
			'persetujuan_pembelian_history.tanggal_baca'                                        => 'tanggal_baca',
			'persetujuan_pembelian_history.tanggal_persetujuan'                                 => 'tanggal_persetujuan',
			'persetujuan_pembelian_history.jumlah_persetujuan'                                  => 'jumlah',
			'persetujuan_pembelian_history.keterangan'                                          => 'keterangan',
			'item_satuan.nama'                                                                  => 'nama_satuan',
			'(SELECT `user`.nama FROM `user` WHERE persetujuan_pembelian_history.dibaca_oleh    = `user`.id)' => 'dibaca_oleh',
			'(SELECT `user`.nama FROM `user` WHERE persetujuan_pembelian_history.disetujui_oleh = `user`.id)' => 'disetujui_oleh',
		);
		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pembelian_detail_id', $id);
		$this->db->order_by('persetujuan_pembelian_history.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pembelian_detail_id', $id);
		$this->db->order_by('persetujuan_pembelian_history.id');
		// $this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pembelian_detail_id', $id);
		$this->db->order_by('persetujuan_pembelian_history.id');
		// $this->db->where('is_active',1);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function update_persetujuan($id)
	{
		$sekarang = date('Y-m-d H:i:s');
		$user = $this->session->userdata('user_id');
		$user_level = $this->session->userdata('level_id');

		$format = "UPDATE persetujuan_pembelian SET status = 2, tanggal_baca = '$sekarang', dibaca_oleh = $user WHERE pembelian_id = $id AND user_level_id = $user_level";

		return $this->db->query($format);
	}

	public function update($id, $jumlah, $satuan, $id_detail, $status, $user, $keterangan)
	{
		$sekarang = date('Y-m-d H:i:s');
		$user = $this->session->userdata('user_id');
		$user_level = $this->session->userdata('level_id');

		$format = "UPDATE persetujuan_pembelian SET `status` = $status, tanggal_persetujuan = '$sekarang', disetujui_oleh = $user, jumlah_persetujuan = $jumlah, satuan_id = $satuan, modified_by = $user, modified_date = '$sekarang', keterangan = '$keterangan'  WHERE pembelian_id = $id AND pembelian_detail_id = $id_detail AND user_level_id = $user_level";

		return $this->db->query($format);
	}

	public function update_all($id, $jumlah, $satuan, $id_detail, $status, $user, $keterangan, $order)
	{
		$sekarang = date('Y-m-d H:i:s');
		$user = $this->session->userdata('user_id');

		$format = "UPDATE persetujuan_pembelian SET `status` = $status, tanggal_persetujuan = '$sekarang', disetujui_oleh = $user, jumlah_persetujuan = $jumlah, satuan_id = $satuan, modified_by = $user, modified_date = '$sekarang', keterangan = '$keterangan'  WHERE pembelian_id = $id AND pembelian_detail_id = $id_detail AND `order` >= $order";

		return $this->db->query($format);
	}

	public function get_data_persetujuan($id_beli, $id_beli_detail)
	{
		$format = "SELECT 
						persetujuan_pembelian_history.jumlah_persetujuan AS jumlah,
						persetujuan_pembelian_history.`status` As status
					FROM
						persetujuan_pembelian_history
					WHERE 
						persetujuan_pembelian_history.pembelian_id = $id_beli AND
						persetujuan_pembelian_history.pembelian_detail_id = $id_beli_detail";

		return $this->db->query($format);
	}

	public function get_order_before($pembelian_id, $order)
	{	
		$format = "SELECT 
						MIN(persetujuan_pembelian_history.`status`) AS stat
					FROM
						persetujuan_pembelian
					WHERE 
						persetujuan_pembelian_history.pembelian_id = $pembelian_id AND
						persetujuan_pembelian_history.`order` < $order";

		return $this->db->query($format);
	}

	public function get_data_item_view($id, $id_sup)
	{
		$format = "SELECT
						persetujuan_pembelian_history.id as id_persetujuan,
						persetujuan_pembelian_history.user_level_id as user_level_id,
						supplier.nama,
						pembelian_detail.diskon,
						pembelian_detail.id AS id_detail,
						pembelian_detail.jumlah_pesan,
						pembelian_detail.jumlah_disetujui,
						pembelian_detail.harga_beli,
						pembelian_detail.is_active AS is_active,
						item_satuan.nama AS satuan,
						item_satuan.id AS id_satuan,
						item.nama AS nama,
						item.kode AS kode,
						item.id AS id,
						supplier_item.minimum_order AS min_order,
						supplier_item.kelipatan_order AS max_order,
						pembelian.id AS id_draf
					FROM
						pembelian
					JOIN pembelian_detail ON pembelian.id = pembelian_detail.pembelian_id
					LEFT JOIN supplier ON pembelian.supplier_id = supplier.id
					LEFT JOIN item ON pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan ON pembelian_detail.item_satuan_id = item_satuan.id
					LEFT JOIN supplier_item ON supplier_item.item_id = item.id
					LEFT JOIN persetujuan_pembelian_history ON pembelian_detail.id = persetujuan_pembelian_history.pembelian_detail_id
					AND supplier_item.item_satuan_id = item_satuan.id
					WHERE
						pembelian.id = '".$id."'
					AND pembelian.supplier_id = $id_sup AND
					pembelian_detail.is_active = 1
					GROUP BY pembelian_detail.id";

		return $this->db->query($format, $id);
	}

	public function get_data_setuju($po_id)
	{
		$SQL = "SELECT * FROM persetujuan_pembelian_history WHERE pembelian_id = '$po_id' ORDER BY `order` DESC LIMIT 1";
		return $this->db->query($SQL);

	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */