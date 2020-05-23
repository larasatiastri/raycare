<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class T_box_paket_m extends MY_Model {

	protected $_table        = 't_box_paket';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		't_box_paket.id' => 'id',
		't_box_paket.kode_box_paket' => 'kode_box_paket',
		'box_paket.nama' => 'nama_box_paket',
		't_box_paket.harga_paket' => 'harga_paket',
		't_box_paket.tipe_tindakan' => 'tipe_tindakan',
		't_box_paket.created_date' => 'created_date',
		't_box_paket.status' => 'status',
		'user.nama' => 'nama_dibuat_oleh',
		'box_paket.id'   => 'id_box',
		
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	
		$join1 = array('box_paket', $this->_table.'.box_paket_id = box_paket.id', 'left');
		$join2 = array('user', $this->_table.'.created_by = user.id', 'left');
		$join_tables = array($join1, $join2);

		$wheres = array(
			'status !=' => 4
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);

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

		return $result; 
	}

	public function get_datatable_history()
	{	
		$join1 = array('box_paket', $this->_table.'.box_paket_id = box_paket.id', 'left');
		$join2 = array('user', $this->_table.'.created_by = user.id', 'left');
		$join_tables = array($join1, $join2);

		$wheres = array(
			'status' => 4
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);

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

		return $result; 
	}

	public function get_no_box_paket($tipe)
	{
		if($tipe == 1){
			$format = "SELECT MAX(RIGHT(`kode_box_paket`,4)) AS max_kode_box_paket FROM `t_box_paket` WHERE LEFT(`kode_box_paket`,4) = DATE_FORMAT(NOW(), '%y%m') AND tipe_paket = ".$tipe.";";	
		}if($tipe == 2){
			$format = "SELECT MAX(RIGHT(`kode_box_paket`,2)) AS max_kode_box_paket FROM `t_box_paket` WHERE LEFT(`kode_box_paket`,4) = DATE_FORMAT(NOW(), '%y%m') AND tipe_paket = ".$tipe.";";	
		}

		return $this->db->query($format);
	}

	public function get_max_id_box_paket()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `t_box_paket` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data_box_paket($status)
	{
		$format = " SELECT box_paket.nama, t_box_paket.kode_box_paket, t_box_paket.id, t_box_paket.box_paket_id FROM t_box_paket JOIN box_paket
					ON t_box_paket.box_paket_id = box_paket.id
					WHERE t_box_paket.`status` = $status
					ORDER BY t_box_paket.created_date ASC";
		return $this->db->query($format);

	}

	public function get_data_laporan($tgl_awal, $tgl_akhir, $item_id, $status)
	{
		$SQL = "SELECT
				t_box_paket.tanggal_tindakan,
				item.nama,
				item_satuan.nama AS satuan,
				t_box_paket_detail.jumlah,
				t_box_paket_detail.bn_sn_lot,
				t_box_paket_detail.expire_date,
				t_box_paket_detail.harga_beli,
				t_box_paket.kode_box_paket,
				t_box_paket_detail.`status`,
				pasien.nama AS nama_pasien,
				t_box_paket.created_date AS tanggal_buat
			FROM
				`t_box_paket_detail`
			JOIN item ON t_box_paket_detail.item_id = item.id
			JOIN item_satuan ON t_box_paket_detail.item_satuan_id = item_satuan.id
			JOIN t_box_paket ON t_box_paket_detail.t_box_paket_id = t_box_paket.id
			JOIN pasien ON t_box_paket.pasien_id = pasien.id
			WHERE
				t_box_paket. STATUS = 3
			AND tanggal_tindakan >= '$tgl_awal'
			AND tanggal_tindakan <= '$tgl_akhir'";

		if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $SQL .= " AND t_box_paket_detail.item_id IN ($item_array)";
        }
        if($status != 0){
			$SQL .= " AND t_box_paket_detail.status = '$status' ";
		}

		$SQL .=	"UNION
				SELECT
					t_box_paket_history.tanggal_tindakan,
					item.nama,
					item_satuan.nama AS satuan,
					1 AS jumlah,
					t_box_paket_detail_history.bn_sn_lot,
					t_box_paket_detail_history.expire_date,
					t_box_paket_detail_history.harga_beli,
					t_box_paket_history.kode_box_paket,
					t_box_paket_detail_history.`status`,
					pasien.nama AS nama_pasien,
					t_box_paket_history.created_date AS tanggal_buat
				FROM
					t_box_paket_detail_history
				JOIN item ON t_box_paket_detail_history.item_id = item.id
				JOIN item_satuan ON t_box_paket_detail_history.item_satuan_id = item_satuan.id
				JOIN t_box_paket_history ON t_box_paket_detail_history.t_box_paket_history_id = t_box_paket_history.id
				JOIN pasien ON t_box_paket_history.pasien_id = pasien.id
				WHERE
					t_box_paket_history. STATUS = 4
				AND tanggal_tindakan >= '$tgl_awal'
				AND tanggal_tindakan <= '$tgl_akhir'";

		if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $SQL .= " AND t_box_paket_detail_history.item_id IN ($item_array)";
        }
        if($status != 0){
			$SQL .= " AND t_box_paket_detail_history.status = '$status' ";
		}

		$SQL .= " ORDER BY
					tanggal_tindakan,
					kode_box_paket ASC";


		return $this->db->query($SQL);
	}

	public function get_data_laporan_group($tgl_awal, $tgl_akhir, $item_id, $status)
	{
		$SQL = "SELECT
				t_box_paket.tanggal_tindakan,
				item.nama,
				item_satuan.nama AS satuan,
				t_box_paket_detail.jumlah,
				t_box_paket_detail.bn_sn_lot,
				t_box_paket_detail.expire_date,
				t_box_paket_detail.harga_beli,
				t_box_paket.kode_box_paket,
				t_box_paket_detail.`status`,
				pasien.nama AS nama_pasien,
				t_box_paket.created_date AS tanggal_buat
			FROM
				`t_box_paket_detail`
			JOIN item ON t_box_paket_detail.item_id = item.id
			JOIN item_satuan ON t_box_paket_detail.item_satuan_id = item_satuan.id
			JOIN t_box_paket ON t_box_paket_detail.t_box_paket_id = t_box_paket.id
			JOIN pasien ON t_box_paket.pasien_id = pasien.id
			WHERE
				t_box_paket. STATUS = 3
			AND tanggal_tindakan >= '$tgl_awal'
			AND tanggal_tindakan <= '$tgl_akhir'";

		if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $SQL .= " AND t_box_paket_detail.item_id IN ($item_array)";
        }
        if($status != 0){
			$SQL .= " AND t_box_paket_detail.status = '$status' ";
		}
		$SQL .= " GROUP BY kode_box_paket ";
		$SQL .=	"UNION
				SELECT
					t_box_paket_history.tanggal_tindakan,
					item.nama,
					item_satuan.nama AS satuan,
					1 AS jumlah,
					t_box_paket_detail_history.bn_sn_lot,
					t_box_paket_detail_history.expire_date,
					t_box_paket_detail_history.harga_beli,
					t_box_paket_history.kode_box_paket,
					t_box_paket_detail_history.`status`,
					pasien.nama AS nama_pasien,
					t_box_paket_history.created_date AS tanggal_buat
				FROM
					t_box_paket_detail_history
				JOIN item ON t_box_paket_detail_history.item_id = item.id
				JOIN item_satuan ON t_box_paket_detail_history.item_satuan_id = item_satuan.id
				JOIN t_box_paket_history ON t_box_paket_detail_history.t_box_paket_history_id = t_box_paket_history.id
				JOIN pasien ON t_box_paket_history.pasien_id = pasien.id
				WHERE
					t_box_paket_history. STATUS = 4
				AND tanggal_tindakan >= '$tgl_awal'
				AND tanggal_tindakan <= '$tgl_akhir'";

		if($item_id != '' && $item_id != 'null'){
            $item_array = str_replace('-', ',', $item_id);
            $SQL .= " AND t_box_paket_detail_history.item_id IN ($item_array)";
        }
        if($status != 0){
			$SQL .= " AND t_box_paket_detail_history.status = '$status' ";
		}
		$SQL .= " GROUP BY kode_box_paket ";

		$SQL .= " ORDER BY
					tanggal_tindakan,
					kode_box_paket ASC";


		return $this->db->query($SQL);
	}

}
