 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_m extends MY_Model {

	protected $_table        = 'pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian.id'                 => 'id',
		'pembelian.no_pembelian'       => 'no_pembelian',
		'pembelian.tanggal_pesan'      => 'tanggal_pesan',
		'pembelian.tanggal_kadaluarsa' => 'tanggal_kadaluarsa',
		'pembelian.tanggal_garansi'    => 'tanggal_garansi',
		'pembelian.keterangan'         => 'keterangan',
		'pembelian.supplier_id'        => 'supplier_id',
		'pembelian.`status`'           => 'status',
		'supplier.nama'                => 'supplier_nama',
		'supplier.kode'                => 'supplier_kode'
	);

	protected $datatable_columns_detail = array(
		//column di table  => alias
		'pembelian.id'                           => 'id',
		'pembelian.no_pembelian'                 => 'no_pembelian',
		'pembelian.tanggal_pesan'                => 'tanggal_pesan',
		'pembelian.tanggal_kadaluarsa'           => 'tanggal_kadaluarsa',
		'pembelian.tanggal_garansi'              => 'tanggal_garansi',
		'pembelian_detail.jumlah_disetujui'      => 'jumlah_pesan',
		'pembelian_detail.harga_beli'            => 'harga_beli',
		'pembelian_detail.jumlah_diterima'       => 'jumlah_diterima',
		'pembelian_detail.jumlah_belum_diterima' => 'jumlah_belum_diterima',
		'pembelian.supplier_id'                  => 'supplier_id',
		'pembelian_detail.item_id'               => 'item_id',
		'pembelian_detail.item_satuan_id'        => 'item_satuan_id',
		'item_satuan.nama'                       => 'nama_satuan',
		'satuan_primary.nama'                    => 'nama_satuan_primary',
		'pembelian_detail.id'                    => 'id_detail',

	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($supplier_id, $tipe_supplier)
	{	
		$join1 = array('supplier', 'pembelian.supplier_id = supplier.id', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		$params['sort_by'] = 'pembelian.no_pembelian';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$status = array('4');
		$this->db->where_in('pembelian.status', $status);
		
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status_cancel',0);
		$this->db->where_in('pembelian.status', $status);
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('supplier.tipe',$tipe_supplier);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status_cancel',0);
		$this->db->where_in('pembelian.status', $status);
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('supplier.tipe',$tipe_supplier);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pembelian.is_active',1);
		$this->db->where('pembelian.status_cancel',0);
		$this->db->where_in('pembelian.status', $status);
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where('supplier.tipe',$tipe_supplier);

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

	public function get_datatable_detail($supplier_id, $item_id, $item_satuan_id, $po_id)
	{	
		$join1 = array('pembelian_detail', 'pembelian.id = pembelian_detail.pembelian_id', 'left');
		$join2 = array('item_satuan', 'pembelian_detail.item_satuan_id = item_satuan.id', 'left');
		$join3 = array('item_satuan satuan_primary', 'pembelian_detail.item_satuan_id_primary = satuan_primary.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		$data = base64_decode(urldecode($po_id));
        $data = unserialize($data);

        // die_dump($data);
        $wheres = '';
        $count = count($data);
        // die_dump($count);
        foreach ($data as $po_id) {
        	$wheres .= $po_id."','";
        }

        $wheres = rtrim($wheres,"','");
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_detail);

		// die_dump($params);
		// $params['sort_by'] = 'pembelian.no_pembelian';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where("pembelian.id in ('".$wheres."')");
		$this->db->where('pembelian_detail.item_id',$item_id);
		// $this->db->where('pembelian_detail.item_satuan_id',$item_satuan_id);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where("pembelian.id in ('".$wheres."')");
		// $this->db->where($wheres);
		$this->db->where('pembelian_detail.item_id',$item_id);
		// $this->db->where('pembelian_detail.item_satuan_id',$item_satuan_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pembelian.supplier_id',$supplier_id);
		$this->db->where("pembelian.id in ('".$wheres."')");
		// $this->db->where($wheres);
		$this->db->where('pembelian_detail.item_id',$item_id);
		// $this->db->where('pembelian_detail.item_satuan_id',$item_satuan_id);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_detail as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_detail;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_detail_supplier_lain($supplier_id, $item_id, $item_satuan_id)
	{	
		$join1 = array('pembelian_detail', 'pembelian.id = pembelian_detail.pembelian_id', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_detail);

		// $params['sort_by'] = 'pembelian.no_pembelian';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pembelian.supplier_id !=',$supplier_id);
		$this->db->where('pembelian_detail.item_id',$item_id);
		$this->db->where('pembelian_detail.item_satuan_id',$item_satuan_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pembelian.supplier_id !=',$supplier_id);
		$this->db->where('pembelian_detail.item_id',$item_id);
		$this->db->where('pembelian_detail.item_satuan_id',$item_satuan_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pembelian.supplier_id !=',$supplier_id);
		$this->db->where('pembelian_detail.item_id',$item_id);
		$this->db->where('pembelian_detail.item_satuan_id',$item_satuan_id);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_detail as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_detail;
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

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */