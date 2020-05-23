<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draft_po_m extends MY_Model {

	protected $_table        = 'draf_pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'draf_pembelian.id'                 => 'id',
		'supplier.id'						=> 'id_sup', 
		'supplier.kode'                     => 'kode', 
		'supplier.nama'                     => 'nama', 
		'draf_pembelian.tanggal_pesan'      => 'tanggal_pesan',
		'draf_pembelian.tanggal_kadaluarsa' => 'tanggal_kadaluarsa',
		'draf_pembelian.keterangan'         => 'keterangan'
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
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id' );
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_po', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_po', 0);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_po', 0);

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

	public function get_datatable_pembelian($tipe)
	{	
		$join1 = array('supplier', $this->_table.'.supplier_id = supplier.id' );
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_po', 0);
		$this->db->where('supplier.tipe', $tipe);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_po', 0);
		$this->db->where('supplier.tipe', $tipe);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_po', 0);
		$this->db->where('supplier.tipe', $tipe);

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
		$format = "SELECT draf_pembelian.tanggal_pesan as tanggal_pesan,
						draf_pembelian.tanggal_kadaluarsa as tanggal_kadaluarsa,
						draf_pembelian.tanggal_garansi as tanggal_kirim,
						draf_pembelian.customer_id as customer_id,
						draf_pembelian.tipe_customer as tipe_customer,
						draf_pembelian.tipe_pembayaran as tipe_pembayaran,
						draf_pembelian.keterangan as keterangan,
						supplier.tipe as tipe_supplier,
						supplier.nama as nama,
						supplier.kode as kode,
						supplier.id as id,
						supplier_email.email as email,
						supplier_telp.no_telp as no_telp,
						supplier_alamat.alamat as alamat,
						supplier_alamat.rt_rw as rt_rw
				   FROM draf_pembelian JOIN supplier ON draf_pembelian.supplier_id = supplier.id
				   LEFT JOIN supplier_telp ON supplier_telp.supplier_id = supplier.id
				   LEFT JOIN supplier_alamat ON supplier_alamat.supplier_id = supplier.id
				   LEFT JOIN supplier_email ON supplier_email.supplier_id = supplier.id
				   WHERE 
						supplier_telp.is_primary = 1
						AND
						draf_pembelian.id = $id";
				   

		return $this->db->query($format, $id);
	}

	public function get_data_item($id, $id_sup)
	{
		$format = "SELECT
						supplier.nama,
						draf_pembelian_detail.diskon,
						draf_pembelian_detail.id as id_detail,
						draf_pembelian_detail.jumlah_pesan,
						item_satuan.nama as satuan,
						item_satuan.id as id_satuan,
						item.nama as nama,
						item.kode as kode,
						item.id as id,
						supplier_item.minimum_order as min_order,
						supplier_item.kelipatan_order as max_order,
						draf_pembelian.id as id_draf
					FROM
						draf_pembelian ,
						draf_pembelian_detail ,
						supplier ,
						item ,
						item_satuan,
						supplier_item
						WHERE
						draf_pembelian.id = draf_pembelian_detail.draf_pembelian_id AND
						draf_pembelian.supplier_id = supplier.id AND
						draf_pembelian_detail.item_id = item.id AND
						draf_pembelian_detail.item_satuan_id = item_satuan.id AND
						supplier.id = supplier_item.id AND
						draf_pembelian.id = $id AND
						draf_pembelian.supplier_id = $id_sup

					";

		return $this->db->query($format, $id);
	}

	public function get_data_link($id)
	{
		$format = "SELECT
						order_permintaan_pembelian.tanggal as tanggal,
						`user`.nama as user,
						user_level.nama as user_level,
						order_permintaan_pembelian.subjek as subjek,
						order_permintaan_pembelian.keterangan as keterangan,
						link_pembelian_d_ke_permintaan_d.jumlah as jumlah,
						link_pembelian_d_ke_permintaan_d.satuan_id as id_satuan,
						item_satuan.nama as satuan
					FROM
						link_pembelian_d_ke_permintaan_d ,
						order_permintaan_pembelian ,
						order_permintaan_pembelian_detail ,
						`user` ,
						user_level ,
						item_satuan
					WHERE
						order_permintaan_pembelian.user_id = `user`.id AND
						order_permintaan_pembelian.user_level_id = user_level.id AND
						order_permintaan_pembelian_detail.order_permintaan_pembelian_id = order_permintaan_pembelian.id AND
						link_pembelian_d_ke_permintaan_d.satuan_id = item_satuan.id AND
						link_pembelian_d_ke_permintaan_d.order_permintaan_pembelian_detail_id = order_permintaan_pembelian_detail.id AND
						link_pembelian_d_ke_permintaan_d.pembelian_detail_id = $id
		";

		return $this->db->query($format);
	}

	public function get_data_penerima_cabang()
	{
		$format = "SELECT
					cabang.kode as kode,
					cabang.nama as nama,
					cabang.id as id,
					cabang_telepon.nomor as no_telp,
					cabang_alamat.alamat as alamat,
					cabang_alamat.rt_rw as rt_rw,
					cabang_email.email as email
				   FROM
					cabang ,
					cabang_alamat,
					cabang_telepon,
					cabang_email,
					draf_pembelian
					WHERE
					cabang.id = draf_pembelian.customer_id AND
					cabang.id = cabang_alamat.cabang_id AND
					cabang.id = cabang_telepon.cabang_id AND
					cabang.id = cabang_email.cabang_id AND
					draf_pembelian.tipe_customer = 1

		";

		return $this->db->query($format);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */