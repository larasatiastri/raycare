<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_m extends MY_Model {

	protected $_table        = 'pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian.id'                        => 'id',
		'pembelian.no_pembelian'              => 'no_po',
		'pembelian.tanggal_pesan'             => 'tanggal',
		'pembelian.tanggal_kadaluarsa'        => 'tanggal_kadaluarsa',
		'pembelian.tanggal_garansi'           => 'tanggal_garansi',
		'pembelian.grand_total'               => 'grand_total',
		'pembelian.grand_total_po'            => 'grand_total_po',
		'pembelian.diskon'                    => 'diskon',
		'pembelian.pph'                       => 'pph',
		'pembelian.dp'                        => 'dp',
		'pembelian.biaya_tambahan'                        => 'biaya_tambahan',
		'pembelian.keterangan'                => 'keterangan',
		'pembelian.status'                    => 'status',
		'pembelian.customer_id'               => 'customer_id',
		'pembelian.tipe_customer'             => 'tipe_customer',
		'pembelian.supplier_id'               => 'supplier_id',
		'pembelian.supplier_id'               => 'supplier_id',
		'pembelian.master_tipe_pembayaran_id' => 'master_tipe_pembayaran_id',
		'pembelian.tipe_pembayaran'           => 'tipe_pembayaran',
		'master_tipe_bayar.nama'              => 'nama_bayar',
		'supplier_tipe_pembayaran.lama_tempo' => 'lama_tempo'
	);


	public function __construct()
	{
		parent::__construct();
	}	

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tipe=null, $supplier_id=null)
	{	
		$join1 = array('supplier_tipe_pembayaran',$this->_table.'.tipe_pembayaran = supplier_tipe_pembayaran.id' ,'left');
		$join2 = array('master_tipe_bayar','supplier_tipe_pembayaran.tipe_bayar_id = master_tipe_bayar.id' ,'left');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'asc';

		if($tipe != null){
			$tipe = str_replace('-', ',', $tipe);
		}
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.status',3);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");

		$this->db->or_where($this->_table.'.status',4);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");

		$this->db->or_where($this->_table.'.status',5);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',2);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");

		$this->db->or_where($this->_table.'.status',5);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");
		
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.status',3);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");
		
		$this->db->or_where($this->_table.'.status',4);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");

		$this->db->or_where($this->_table.'.status',5);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',2);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");

		$this->db->or_where($this->_table.'.status',5);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");
				
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.status',3);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");

		$this->db->or_where($this->_table.'.status',4);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");
		
		$this->db->or_where($this->_table.'.status',5);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',2);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");
		
		$this->db->or_where($this->_table.'.status',5);
		$this->db->where('pembelian.is_active', 1);
		$this->db->where('pembelian.master_tipe_pembayaran_id IN ('.$tipe.')');
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('pembelian.status_keuangan',1);
		$this->db->where("pembelian.id NOT IN (SELECT pembelian_id FROM tanda_terima_faktur)");
				

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

	
	public function get_no_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`no_pembelian`,5,3)) AS max_no_pembelian FROM `pembelian` WHERE RIGHT(`no_pembelian`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `pembelian` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data_supplier($id)
	{
		 $format = "SELECT
					supplier.id,
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
					pembelian ,
					supplier,
					supplier_alamat
					WHERE
					pembelian.supplier_id = supplier.id AND
					supplier_alamat.supplier_id = supplier.id AND
					pembelian.id = '".$id."'
					";

		 return $this->db->query($format);
	}

	public function get_data_no_telp($id)
	{
		$format = "SELECT
					supplier_telp.no_telp as no_telp			   
					FROM
					pembelian ,
					supplier,
					supplier_telp
					WHERE
					pembelian.supplier_id = supplier.id AND
					supplier_telp.supplier_id = supplier.id AND
					pembelian.id =  $id
		";

		return $this->db->query($format);
	}

	public function get_data_item($id, $id_sup)
	{
		$format = "SELECT
						supplier.nama,
						pembelian_detail.diskon,
						pembelian_detail.id AS id_detail,
						pembelian_detail.jumlah_pesan,
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
					AND supplier_item.item_satuan_id = item_satuan.id
					WHERE
						pembelian.id = '".$id."'
					AND pembelian.supplier_id = $id_sup AND
					pembelian_detail.is_active = 1
					GROUP BY pembelian_detail.id


					";

		return $this->db->query($format, $id);
	}

	public function get_data_item_view($id, $id_sup)
	{
		$format = "SELECT
						supplier.nama,
						pembelian_detail.diskon,
						pembelian_detail.id AS id_detail,
						pembelian_detail.jumlah_pesan,
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
					AND supplier_item.item_satuan_id = item_satuan.id
					WHERE
						pembelian.id = '".$id."'
					AND pembelian.supplier_id = $id_sup AND
					pembelian_detail.is_active = 1
					GROUP BY pembelian_detail.id";

		return $this->db->query($format, $id);
	}

	public function get_satuan($id)
	{
		$format = "SELECT id, nama FROM item_satuan WHERE item_satuan.item_id = '$id'";

		return $this->db->query($format);
	}

	public function get_data($id)
	{
		$format = "SELECT pembelian.tanggal_pesan as tanggal_pesan,
						pembelian.tanggal_kadaluarsa as tanggal_kadaluarsa,
						pembelian.tanggal_garansi as tanggal_kirim,
						pembelian.customer_id as customer_id,
						pembelian.tipe_customer as tipe_customer,
						pembelian.tipe_pembayaran as tipe_pembayaran,
						pembelian.keterangan as keterangan,
						pembelian.diskon as diskon,
						pembelian.pph as pph,
						pembelian.biaya_tambahan as biaya_tambahan,
						supplier.tipe as tipe_supplier,
						supplier.nama as nama,
						supplier.kode as kode,
						supplier.id as id,
						supplier_email.email as email,
						supplier_telp.no_telp as no_telp,
						supplier_alamat.alamat as alamat,
						supplier_alamat.rt_rw as rt_rw,
						inf_lokasi.nama_kelurahan as kelurahan,
						inf_lokasi.nama_kecamatan as kecamatan,
						inf_lokasi.nama_kabupatenkota as kota,
						inf_lokasi.nama_propinsi as propinsi
				   FROM pembelian JOIN supplier ON pembelian.supplier_id = supplier.id
				   LEFT JOIN supplier_telp ON supplier_telp.supplier_id = supplier.id
				   LEFT JOIN supplier_alamat ON supplier_alamat.supplier_id = supplier.id
				   LEFT JOIN supplier_email ON supplier_email.supplier_id = supplier.id
				   LEFT JOIN inf_lokasi ON supplier_alamat.kode_lokasi = inf_lokasi.lokasi_kode
				   WHERE 
						supplier_telp.is_primary = 1
						AND
						pembelian.id = '".$id."' ";
				   

		return $this->db->query($format, $id);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/pembelian_m.php */