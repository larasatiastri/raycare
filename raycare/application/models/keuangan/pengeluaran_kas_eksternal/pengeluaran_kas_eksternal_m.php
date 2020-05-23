<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengeluaran_kas_eksternal_m extends MY_Model {

	protected $_table        = 'pengeluaran_kas_eksternal';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pengeluaran_kas_eksternal.id'              => 'id', 
		'pengeluaran_kas_eksternal.diminta_oleh_id' => 'diminta_oleh_id',
		'pengeluaran_kas_eksternal.tanggal'         => 'tanggal',
		'pengeluaran_kas_eksternal.nominal'         => 'nominal',
		'pengeluaran_kas_eksternal.keperluan'       => 'keperluan',
		'pengeluaran_kas_eksternal.is_active'       => 'is_active',
		'user.nama'                                 => 'nama_dibuat_oleh',
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
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id', 'LEFT');
		// $join2 = array('pengeluaran_kas_eksternal_cetak', $this->_table.'.id = pengeluaran_kas_eksternal_cetak.pengeluaran_kas_eksternal_id', 'LEFT');
		// $join3 = array('pengeluaran_kas_eksternal_tipe', 'pengeluaran_kas_eksternal_tipe.pengeluaran_kas_eksternal_id = pengeluaran_kas_eksternal.id ', 'LEFT');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="pengeluaran_kas_eksternal.id";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_history()
	{	
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id', 'LEFT');
		// $join2 = array('pengeluaran_kas_eksternal_cetak', $this->_table.'.id = pengeluaran_kas_eksternal_cetak.pengeluaran_kas_eksternal_id', 'LEFT');
		// $join3 = array('pengeluaran_kas_eksternal_tipe', 'pengeluaran_kas_eksternal_tipe.pengeluaran_kas_eksternal_id = pengeluaran_kas_eksternal.id ', 'LEFT');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']="pengeluaran_kas_eksternal.id";
		$params['sort_dir']="desc";

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('status <=', 5);
		$this->db->where('status_proses', 1);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('status <=', 5);
		$this->db->where('status_proses', 1);
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();
		$this->db->where('status <=', 5);
		$this->db->where('status_proses', 1);
		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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

	public function get_datatable_persetujuan()
	{	
		$join1 = array('`user`', $this->_table.'.diminta_oleh_id = `user`.id', 'LEFT');
		// $join2 = array('pengeluaran_kas_eksternal_cetak', $this->_table.'.id = pengeluaran_kas_eksternal_cetak.pengeluaran_kas_eksternal_id', 'LEFT');
		// $join3 = array('pengeluaran_kas_eksternal_tipe', 'pengeluaran_kas_eksternal_tipe.pengeluaran_kas_eksternal_id = pengeluaran_kas_eksternal.id ', 'LEFT');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('`status`', 1);
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('`status`', 1);
		
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('`status`', 1);
		
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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
	


}

/* End of file pengeluaran_kas_eksternal.php */
/* Location: ./application/models/keuangan/pengeluaran_kas_eksternal.php */