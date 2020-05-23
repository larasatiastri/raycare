<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_pengajuan_pembayaran_kasbon_m extends MY_Model
{
	protected $_table        = 'persetujuan_pengajuan_pembayaran_kasbon';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pengajuan_pembayaran_kasbon.tanggal' => 'tanggal',
		'pengajuan_pembayaran_kasbon.subjek'  => 'subjek',
		'pengajuan_pembayaran_kasbon.nominal' => 'nominal',
		'pengajuan_pembayaran_kasbon.no_cek'  => 'no_cek',
		'user.nama'                                                                     => 'nama_dibuat_oleh',
		'persetujuan_pengajuan_pembayaran_kasbon.id' => 'id', 
		'persetujuan_pengajuan_pembayaran_kasbon.pengajuan_pembayaran_kasbon_id'                  => 'pengajuan_pembayaran_kasbon_id',
		'persetujuan_pengajuan_pembayaran_kasbon.user_level_id'                              => 'user_level_id',
		'persetujuan_pengajuan_pembayaran_kasbon.`order`'                                    => 'order_persetujuan',
		'persetujuan_pengajuan_pembayaran_kasbon.`status`'                                   => 'status_persetujuan',
		'persetujuan_pengajuan_pembayaran_kasbon.tanggal_baca'                               => 'tanggal_baca',
		'persetujuan_pengajuan_pembayaran_kasbon.dibaca_oleh'                                => 'dibaca_oleh',
		'persetujuan_pengajuan_pembayaran_kasbon.tanggal_persetujuan'                        => 'tanggal_persetujuan',
		'persetujuan_pengajuan_pembayaran_kasbon.disetujui_oleh'                             => 'disetujui_oleh',
		'pengajuan_pembayaran_kasbon.created_by'                                             => 'diminta_oleh_id',
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($user_level_id)
	{	
		$join1 = array('pengajuan_pembayaran_kasbon', $this->_table.'.pengajuan_pembayaran_kasbon_id = pengajuan_pembayaran_kasbon.id', 'LEFT');
		$join2 = array('`user`', 'pengajuan_pembayaran_kasbon.created_by = `user`.id', 'LEFT');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.user_level_id', $user_level_id);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.is_active', 1);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.`status` <= 3');
		$this->db->group_by('persetujuan_pengajuan_pembayaran_kasbon.user_level_id');
		$this->db->group_by('persetujuan_pengajuan_pembayaran_kasbon.pengajuan_pembayaran_kasbon_id');

		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.user_level_id', $user_level_id);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.is_active', 1);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.`status` <= 3');
		$this->db->group_by('persetujuan_pengajuan_pembayaran_kasbon.user_level_id');
		$this->db->group_by('persetujuan_pengajuan_pembayaran_kasbon.pengajuan_pembayaran_kasbon_id');
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.user_level_id', $user_level_id);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.is_active', 1);
		$this->db->where('persetujuan_pengajuan_pembayaran_kasbon.`status` <= 3');
		$this->db->group_by('persetujuan_pengajuan_pembayaran_kasbon.user_level_id');
		$this->db->group_by('persetujuan_pengajuan_pembayaran_kasbon.pengajuan_pembayaran_kasbon_id');

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

	public function get_last_id()
	{
		$sql = "SELECT MAX(id) as last_id FROM persetujuan_pengajuan_pembayaran_kasbon";

		return $this->db->query($sql);
	}

	public function data_order($pengajuan_pembayaran_kasbon_id, $order)
	{

		$sql = "SELECT
				persetujuan_pengajuan_pembayaran_kasbon.id,
				persetujuan_pengajuan_pembayaran_kasbon.pengajuan_pembayaran_kasbon_id,
				persetujuan_pengajuan_pembayaran_kasbon.`order` AS `order`,
				persetujuan_pengajuan_pembayaran_kasbon.user_level_id,
				persetujuan_pengajuan_pembayaran_kasbon.`status` AS `status`
				FROM
				persetujuan_pengajuan_pembayaran_kasbon
				WHERE
				persetujuan_pengajuan_pembayaran_kasbon.pengajuan_pembayaran_kasbon_id = '$pengajuan_pembayaran_kasbon_id'
				AND
				persetujuan_pengajuan_pembayaran_kasbon.`order` = $order";

		return $this->db->query($sql);

	}

	public function data_order2($pengajuan_pembayaran_kasbon_id, $order)
	{

		$sql = "SELECT
				persetujuan_pengajuan_pembayaran_kasbon.id,
				persetujuan_pengajuan_pembayaran_kasbon.pengajuan_pembayaran_kasbon_id,
				persetujuan_pengajuan_pembayaran_kasbon.`order` AS `order`,
				persetujuan_pengajuan_pembayaran_kasbon.user_level_id,
				persetujuan_pengajuan_pembayaran_kasbon.`status` AS `status`
				FROM
				persetujuan_pengajuan_pembayaran_kasbon
				WHERE
				persetujuan_pengajuan_pembayaran_kasbon.pengajuan_pembayaran_kasbon_id = '$pengajuan_pembayaran_kasbon_id'
				AND
				persetujuan_pengajuan_pembayaran_kasbon.`order` < $order";

		return $this->db->query($sql);

	}	

	public function get_max_order($pengajuan_pembayaran_kasbon_id)
	{
		$sql = "select max(`order`) as max_order from persetujuan_pengajuan_pembayaran_kasbon where pengajuan_pembayaran_kasbon_id = '$pengajuan_pembayaran_kasbon_id'";
		return $this->db->query($sql);
	}

	public function delete_id($id)
	{

		$sql = "DELETE FROM persetujuan_pengajuan_pembayaran_kasbon
				WHERE persetujuan_pengajuan_pembayaran_kasbon_id =  $id";
		
		return $this->db->query($sql);
	}
}

/* End of file persetujuan_pengajuan_pembayaran_kasbon_m.php */
/* Location: ./application/models/persetujuan_pengajuan_pembayaran_kasbon_m.php */