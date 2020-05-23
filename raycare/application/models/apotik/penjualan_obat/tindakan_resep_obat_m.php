<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_resep_obat_m extends MY_Model {

	protected $_table        = 'tindakan_resep_obat';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		'tindakan_resep_obat.id'                   			=> 'id',
		'tindakan_resep_obat.tindakan_id'          			=> 'tindakan_id',
		'tindakan_resep_obat.tipe_tindakan'        			=> 'tipe_tindakan',
		'tindakan_resep_obat.pasien_id'          			=> 'pasien_id',
		'tindakan_resep_obat.dokter_id'          			=> 'dokter_id',
		'tindakan_resep_obat.status'          				=> 'status',
		'tindakan_resep_obat.is_active'          			=> 'is_active',
		'tindakan_resep_obat_detail.tindakan_resep_obat_id'	=> 'tindakan_resep_obat_id',
		'tindakan_resep_obat_detail.item_id'          		=> 'item_id',
		'tindakan_resep_obat_detail.tipe_item'         		=> 'tipe_item',
		'tindakan_resep_obat_detail.jumlah'         		=> 'jumlah',
		'tindakan_resep_obat_detail.satuan_id'         		=> 'satuan_id',
		'tindakan_resep_obat_detail.dosis'         			=> 'dosis',
		'item.kode'         								=> 'kode',
		'item.nama'         								=> 'nama',
		'item_satuan.nama'         							=> 'nama_satuan',
		'item_harga.harga'         							=> 'harga_item',
	);

	protected $datatable_columns_obat_dijual = array(
		'tindakan_resep_obat.id'                   			=> 'id',
		'tindakan_resep_obat.tindakan_id'          			=> 'tindakan_id',
		'tindakan_resep_obat.tipe_tindakan'        			=> 'tipe_tindakan',
		'tindakan_resep_obat.pasien_id'          			=> 'pasien_id',
		'tindakan_resep_obat.dokter_id'          			=> 'dokter_id',
		'tindakan_resep_obat.status'          				=> 'status',
		'tindakan_resep_obat.is_active'          			=> 'is_active',
		'tindakan_resep_obat_detail.tindakan_resep_obat_id'	=> 'tindakan_resep_obat_id',
		'tindakan_resep_obat_detail.item_id'          		=> 'item_id',
		'tindakan_resep_obat_detail.tipe_item'         		=> 'tipe_item',
		'tindakan_resep_obat_detail.jumlah'         		=> 'jumlah',
		'tindakan_resep_obat_detail.satuan_id'         		=> 'satuan_id',
		'tindakan_resep_obat_detail.dosis'         			=> 'dosis',
		'item.kode'         								=> 'kode',
		'item.nama'         								=> 'nama',
		'item_satuan.nama'         							=> 'nama_satuan',
		'item_harga.harga'         							=> 'harga_item',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($pasien_id, $dokter_id)
	{		
		$join1 = array('tindakan_resep_obat_detail', $this->_table.'.id = tindakan_resep_obat_detail.id', 'LEFT');
		$join2 = array('item', 'tindakan_resep_obat_detail.item_id = item.id', 'LEFT');
		$join3 = array('item_satuan', 'tindakan_resep_obat_detail.satuan_id = item_satuan.id', 'LEFT');
		$join4 = array('item_harga', 'item.id = item_harga.id', 'LEFT');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if ($pasien_id != null)
		{
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.dokter_id', $dokter_id);
		$this->db->where($this->_table.'.status', 1);
		// $this->db->where($this->_table.'.status', 1);
		// $this->db->group_by($this->_table.'.pasien_id');
		} else {
			
		// $this->db->group_by($this->_table.'.pasien_id');
		// $this->db->where($this->_table.'.status', 1);
		$this->db->where($this->_table.'.status', 1);

		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if ($pasien_id != null)
		{
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.dokter_id', $dokter_id);
		$this->db->where($this->_table.'.status', 1);
		// $this->db->where($this->_table.'.status', 1);
		// $this->db->group_by($this->_table.'.pasien_id');
		} else {

		// $this->db->group_by($this->_table.'.pasien_id');
		// $this->db->where($this->_table.'.status', 1);
		$this->db->where($this->_table.'.status', 1);

		}

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if ($pasien_id != null)
		{
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.dokter_id', $dokter_id);
		$this->db->where($this->_table.'.status', 1);
		// $this->db->where($this->_table.'.status', 1);
		// $this->db->group_by($this->_table.'.pasien_id');
		} else {

		// $this->db->group_by($this->_table.'.pasien_id');
		// $this->db->where($this->_table.'.status', 1);
		$this->db->where($this->_table.'.status', 1);

		}
		

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


	public function get_datatable2($pasien_id)
	{		
		$join1 = array('tindakan_resep_obat_detail', $this->_table.'.id = tindakan_resep_obat_detail.id');
		$join2 = array('item', 'tindakan_resep_obat_detail.item_id = item.id');
		$join3 = array('item_satuan', 'tindakan_resep_obat_detail.satuan_id = item_satuan.id');
		$join4 = array('item_harga', 'item.id = item_harga.id');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_obat_dijual);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if ($pasien_id != null)
		{
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.status', 2);
		} else {

		$this->db->where($this->_table.'.pasien_id', 0);
		$this->db->where($this->_table.'.status', 2);
			

		}

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if ($pasien_id != null)
		{
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.status', 2);
		} else {

		$this->db->where($this->_table.'.pasien_id', 0);
		$this->db->where($this->_table.'.status', 2);
			

		}

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if ($pasien_id != null)
		{
		$this->db->where($this->_table.'.pasien_id', $pasien_id);
		$this->db->where($this->_table.'.status', 2);
		} else {

		$this->db->where($this->_table.'.pasien_id', 0);
		$this->db->where($this->_table.'.status', 2);
			

		}

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_obat_dijual as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_obat_dijual;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_dokter($pasien_id)
	{

		$query = "SELECT
					tindakan_resep_obat.id,
					tindakan_resep_obat.pasien_id,
					tindakan_resep_obat.dokter_id,
					`user`.nama,
					`user`.id
					FROM
					tindakan_resep_obat
					LEFT JOIN `user` ON tindakan_resep_obat.dokter_id = `user`.id
					WHERE pasien_id =  $pasien_id
					GROUP BY dokter_id
				  ";
		return $this->db->query($query);
	}

	public function get_no_batch()
	{
		$format = "SELECT MAX(SUBSTRING(`no_batch`,9,4)) AS max_no_batch FROM `racik_obat` WHERE SUBSTRING(`no_batch`,5,2) = DATE_FORMAT(NOW(), '%y') AND SUBSTRING(`no_batch`, 7, 2) = DATE_FORMAT(NOW(), '%m')";	
		return $this->db->query($format);
	}


	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable_add()
	{
		return $this->_fillable_add;
	}
	/**
	 * [fillable_edit description]
	 * @return [type] [description]
	 */
	public function fillable_edit()
	{
		return $this->_fillable_edit;
	}

}

