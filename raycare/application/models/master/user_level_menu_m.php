<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_level_menu_m extends MY_Model {

	var $user_level_id;
	var $menu_id;

	protected $_table      = 'user_level_menu';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $menufile;
	private $menufile_core;
	private $menufile_ravena;

	private $_fillable = array(
		'nama',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'user_level.id'            => 'id', 
		'user_level.nama'          => 'nama', 
		'user_level.dashboard_url' => 'dashboard_url',
		'user_level.is_active'     => 'active'
	);

	function __construct()
	{
		parent::__construct();

		$this->menufile  =  'application/menu/'. $this->session->userdata('session');

	}

	/**
	 * digunakan untuk penciptaan menu per user_level
     * get recursive menu in tree format
     * @param  integer $user_level_id 
     * @return array $menus               
     */
    public function get_nested($user_level_id)
    {       

		return get_menu_api($user_level_id);
   

    }

    /**
     * [build_menu description]
     * @param  [type]  $rows      [description]
     * @param  integer $parent_id [description]
     * @return [type]             [description]
     */
    public function build_menu($rows, $parent_id=0)
    {
        $menus = array();

        foreach ($rows as $row)
        {
            if ($row['parent_id'] == $parent_id)
            {
                // buat sub menu jika ada
                if ($this->has_children($rows, $row['id']))
                {
                    $row['children']  = $this->build_menu($rows, $row['id']);
                }
                $menus[] = $row;
            }
        }

      return $menus;
    }

    /**
     * [has_children description]
     * @param  array    $rows array menu
     * @param  integer  $id   menu id
     * @return boolean        true kalau menu $id punya sub menu
     */
    public function has_children($rows, $id)
    {
        foreach ($rows as $row) {
            if ($row['parent_id'] == $id) return true;
        }

        return false;

    }

    public function delete_menu_file()
    {
        // if (file_exists($this->menufile) && is_file($this->menufile)) {
        //     unlink($this->menufile);
        // }
    	delete_menu_api();
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

		return $result; 
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_active()
	{

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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

	public function set_user_level_id($user_level_id)
	{
		$this->user_level_id = $user_level_id;
	}
	
	public function set_user_level_menu_id($menu_id)
	{
		$this->menu_id = $menu_id;
	}

	
	public function get_data_by_user_level_id_and_user_level_menu_id($user_level_id, $menu_id)
	{
		$this->db->select('user_level_menu.id,
							user_level_menu.user_level_id,
							user_level_menu.menu_id,
							menu.nama,
							user_level_menu.is_active,
							user_level_menu.created_by,
							user_level_menu.created_date,
							user_level_menu.modified_by,
							user_level_menu.modified_date');
		$this->db->join("menu", $this->_table.".menu_id = menu.id");
		$this->db->join("user_level", $this->_table.".user_level_id = user_level.id");
		$this->db->where($this->_table.".user_level_id", $user_level_id);
		$this->db->where('menu.id', $menu_id);

		return $this->db->get($this->_table);
	}

	public function delete_data($id)
	{
		$SQL = "DELETE FROM user_level_menu
				WHERE user_level_id = $id";
        return $this->db->query($SQL);
	}

	public function delete_by_parent($parent_id)
	{
		$this->db->where('parent_id', $parent_id);
		$this->db->delete($this->_table);
	}

	public function add()
	{
		$data = array(
			"user_level_id"		=> $this->user_level_id,
			"menu_id"	=> $this->menu_id
		);
		
		$this->db->insert($this->_table, $data);
	}
	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_by_level_parent_id($level_id, $parent_id)
	{
		$this->db->where('parent_id', $parent_id);
		$this->db->where('user_level_id', $level_id);

		return $this->db->get($this->_table);
	}

	public function get_max_id()
	{
		$this->db->select('max(id) as max_id');
		return $this->db->get($this->_table);
	}

	public function get_order_max($parent_id,$level_id,$m_order)
	{
		$this->db->where('parent_id', $parent_id);
		$this->db->where('user_level_id', $level_id);
		$this->db->where('m_order >', $m_order);
		$this->db->order_by('m_order','asc');

		return $this->db->get($this->_table);
	}

	public function get_by_parent_order($level_id,$parent_id)
	{
		$columns = array(
            't1.*',
            't2.url as base_url'
            );

        $menu_user_level = $this->db
            ->select($columns)
            ->from($this->_table . ' AS t1')
            ->where('t1.parent_id', $parent_id)
            ->where('t1.user_level_id', $level_id)
            ->join('cabang t2', 't1.cabang_id = t2.id','left')
            ->order_by('t1.m_order ASC')
            ->get()->result();

        return $menu_user_level;
	}

	public function up_order($parent_id,$id,$order,$level_id)
    {
        $new_order = $order -1;
        $up = array(
            'm_order' => (integer)($order - 1)
        );
        $this->db->where('user_level_id',$level_id);
        $this->db->where('parent_id',$parent_id);
        $this->db->where('m_order', $order);
        $this->db->where('id', $id);
        //move menu 1 order up
        $this->db->update($this->_table, $up);
        
        $down = array(
            'm_order' => (integer)($new_order + 1)
        );
        $this->db->where('user_level_id',$level_id);
        $this->db->where('parent_id',$parent_id);
        $this->db->where('m_order', $new_order);
        $this->db->where('id !=', $id);
        //move menu 1 order down
        $this->db->update($this->_table, $down);
    }

    public function down_order($parent_id,$id,$order,$level_id)
    {
        $new_order = $order +1;
        $down = array(
            'm_order' => (integer)($order + 1)
        );
        $this->db->where('user_level_id',$level_id);
        $this->db->where('parent_id',$parent_id);       
        $this->db->where('m_order', $order);
        $this->db->where('id', $id);
        //move menu 1 order down
        $this->db->update($this->_table, $down);


        $up = array(
            'm_order' => (integer)($new_order - 1)
        );
        $this->db->where('user_level_id',$level_id);
        $this->db->where('parent_id',$parent_id);       
        $this->db->where('m_order', $new_order);
        $this->db->where('id !=', $id);
        //move menu 1 order up
        $this->db->update($this->_table, $up);
             
    }

}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */