<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pmb_po_detail_m extends MY_Model {

	protected $_table        = 'pmb_po_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pmb_po_detail.po_detail_id' => 'id',
		'pmb_po_detail.jumlah' => 'jumlah',
		'pmb_detail.id' => 'pmb_detail_id',
		'pmb.tanggal' => 'tanggal',
		'pmb.created_by' => 'created_by',
		'`user`.nama' => 'nama',
		'user_level.nama' => 'user_level',
		'pmb.no_pmb' => 'no_pmb',
		'item_satuan.nama' => 'satuan_nama'
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
		$join1 = array('pmb_detail', 'pmb_po_detail.pmb_detail_id = pmb_detail.id', 'left');
		$join2 = array('pmb', 'pmb_detail.pmb_id = pmb.id', 'left');
		$join3 = array('`user`', 'pmb.created_by = `user`.id', 'left');
		$join4 = array('user_level', 'user_level.id = `user`.user_level_id', 'left');
		$join5 = array('item_satuan', 'pmb_po_detail.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pmb_po_detail.po_detail_id',$id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pmb_po_detail.po_detail_id',$id);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pmb_po_detail.po_detail_id',$id);

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

	public function get_data_laporan($tgl_awal,$tgl_akhir,$supplier_id, $item_id)
	{
		$SQL = "SELECT 
				pembelian.id as po_id,
				pembelian.tanggal_pesan,
				pembelian.tanggal_kadaluarsa,
				pembelian.no_pembelian,
				supplier.nama as nama_supplier,
				pembelian.jenis_pembelian,
				pembelian.tipe_pembayaran,
				pembelian.pph,
				pembelian.diskon,
				pembelian.pph_23,
				pembelian.pph_23_nominal,
				pembelian.biaya_tambahan,
				item.nama as nama_item,
				a.nama as nama_satuan,
				pembelian_detail.jumlah_pesan,
				pembelian_detail.item_satuan_id as satuan_pesan,
				pembelian_detail.diskon as diskon_item,
				pembelian_detail.harga_beli,
				pembelian_detail.harga_beli_primary,
				pembelian_detail.is_pph,
				pmb.tanggal,
				pmb.no_pmb,
				pmb.no_surat_jalan,
				pmb_detail.jumlah_diterima,
				pmb_detail.item_satuan_id as satuan_terima,
				b.nama as nama_satuan_terima,
				pmb_detail.bn_sn_lot,
				pmb_detail.expire_date
			FROM `pmb_po_detail` JOIN pembelian ON pmb_po_detail.po_id = pembelian.id
			JOIN pmb ON pmb_po_detail.pmb_id = pmb.id
			JOIN pmb_detail ON pmb_po_detail.pmb_detail_id = pmb_detail.id
			JOIN item_satuan b ON pmb_detail.item_satuan_id = b.id
			JOIN supplier ON pmb.supplier_id = supplier.id
			JOIN pembelian_detail ON pmb_po_detail.po_detail_id = pembelian_detail.id
			JOIN item ON pembelian_detail.item_id = item.id
			JOIN item_satuan a ON pembelian_detail.item_satuan_id = a.id
			WHERE pmb.tanggal >= '$tgl_awal' AND pmb.tanggal <= '$tgl_akhir'";

		if($supplier_id != 0){
			$SQL .= " AND pembelian.supplier_id = '$supplier_id' ";
		}
		if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $SQL .= " AND pembelian_detail.item_id IN ($item_array)";
        }

        $SQL .= " ORDER BY
				pembelian.tanggal_pesan ASC";

		return $this->db->query($SQL);

	}


	public function get_data_item_po($po_id)
	{
		$SQL = "SELECT * FROM `pmb_po_detail` WHERE po_id = '$po_id' GROUP BY po_detail_id;";
		return $this->db->query($SQL);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */