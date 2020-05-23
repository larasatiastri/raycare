<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Model extends CI_Model {

	protected $_table           = '';
	protected $_primary_key     = 'id';
	protected $_primary_filter  = 'intval';
	protected $_order_by        = '';
	public    $rules            = array();	

	// blameable setting
	protected $_timestamps      = FALSE;
	protected $_col_created_by  = 'created_by';
	protected $_col_created_at  = 'created_date';
	protected $_col_modified_by = 'modified_by';
	protected $_col_modified_at = 'modified_date';

	// datatable columns
	protected $datatable_columns = array();

	// array untuk menyimpan columns table yang mau diselect
	private $columns_select = array();

	public function __construct() 
	{
		parent::__construct();
	}
	
	public function array_from_post($fields)
	{
		$data = array();
		foreach ($fields as $field) {
			$data[$field] = $this->input->post($field);
		}
		return $data;
	}
	

	public function array_from_get($fields)
	{
		$data = array();
		foreach ($fields as $field) {
			$data[$field] = $this->input->get($field);
		}
		return $data;
	}

	public function array_from_get_post($fields)
	{
		$data = array();
		foreach ($fields as $field) {
			$data[$field] = $this->input->get_post($field);
		}
		return $data;
	}

	public function get($id = NULL, $single = FALSE){		

		if ($id != NULL) {
			$filter = $this->_primary_filter;
			$id = $filter($id);

			$this->db->where($this->_primary_key, $id);
			$method = 'row';
		}
		elseif($single == TRUE) {
			$method = 'row';
		}
		else {
			$method = 'result';
		}
		if (!count($this->db->ar_orderby)) {
			!$this->_order_by || $this->db->order_by( $this->db->escape($this->_order_by) );
		}

		$columns = $this->columns_select;
		if (is_array($columns) && count($columns)){
			$this->db->select($columns);
		}

		$result = $this->db->get($this->_table)->$method(); 
		// dump($method, 'method');
		// clear columns_select
		$this->columns_select = array();		

		return $result;
	}

	/**
	 * [get_by description]
	 * @param  [type]  $where  [description]
	 * @param  boolean $single [description]
	 * @return [type]          [description]
	 */

	public function get_by($where, $single = FALSE, $field_order = NULL){
		$this->db->where($where);
		if($field_order != NULL){
			$this->db->order_by($field_order,'DESC');
		}
		return $this->get(NULL, $single);
	}

	public function get_data_by_id($id)
    {
        $this->db->where('id', $id);
        $method = 'row';

        $result = $this->db->get($this->_table)->$method(); 
        return $result;
    }

	/**
	 * [set_columns description]
	 * @param [type] $columns [description]
	 */
	public function set_columns($columns)
	{
		if (is_array($columns)) $this->columns_select = $columns;
	}

	/**
	 * save method: bisa insert atau update
	 * @param  array $data [description]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function save($data, $id = NULL){
		// Set timestamps
		if ($this->_timestamps == TRUE) {
			$now = date('Y-m-d H:i:s');
			$user_id = $this->session->userdata('user_id');
			// $id || $data['created_at'] = $now;

			if ($id === NULL) {
				$data[$this->_col_created_by] = $user_id;
				$data[$this->_col_created_at] = $now;
			}

			else {
				$data[$this->_col_modified_by] = $user_id;			
				$data[$this->_col_modified_at] = $now;
			}
		}

		// Insert
		if ($id === NULL) {
			// jika tidak ada $data['id'] lanjut ke baris selanjutnya
			// jika ada set dulu NULL $data['id']
			!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;

			$this->db->set($data);
			$this->db->insert($this->_table);
			$id = $this->db->insert_id();
		}
		// Update
		else {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->set($data);
			$this->db->where($this->_primary_key, $id);
			$this->db->update($this->_table);
		}	
		return $id;
	}

	/**
	 * save method: bisa insert atau update
	 * @param  array $data [description]
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function save_api($user_login_id,$data, $id = NULL){
		// Set timestamps
		if ($this->_timestamps == TRUE) {
			$now = date('Y-m-d H:i:s');
			$user_id = $user_login_id;
			// $id || $data['created_at'] = $now;

			if ($id === NULL) {
				$data[$this->_col_created_by] = $user_id;
				$data[$this->_col_created_at] = $now;
			}

			else {
				$data[$this->_col_modified_by] = $user_id;			
				$data[$this->_col_modified_at] = $now;
			}
		}

		// Insert
		if ($id === NULL) {
			// jika tidak ada $data['id'] lanjut ke baris selanjutnya
			// jika ada set dulu NULL $data['id']
			!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;

			$this->db->set($data);
			$this->db->insert($this->_table);
			$id = $this->db->insert_id();
		}
		// Update
		else {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->set($data);
			$this->db->where($this->_primary_key, $id);
			$this->db->update($this->_table);
		}	
		return $id;
	}

	public function delete($id){
		$filter = $this->_primary_filter;
		$id = $filter($id);
		
		if (!$id) {
			return FALSE;
		}
		$this->db->where($this->_primary_key, $id);
		$this->db->limit(1);
		$this->db->delete($this->_table);
	}

	public function delete_by($wheres){
        
        $this->db->where($wheres);
        $this->db->delete($this->_table);
    }

	public function update_by($user_login_id,$data,$wheres)
    {   
        $now = date('Y-m-d H:i:s');
        $user_id = $user_login_id;
       
        $data[$this->_col_modified_by] = $user_id;          
        $data[$this->_col_modified_at] = $now;
    
        $this->db->where($wheres);
        $this->db->update($this->_table,$data);
    }

/**
     * datatable_prepare
     * @param  array   $join_tables - array joined table dan criterianya,
     *                 set $join_tables = false, jika tidak ada join tables
     * @param  array   $params      param dari input post datatable
     * @param  boolean $filter      [description]
     * @param  boolean $limit       [description]
     * @return [type]               [description]
     */
    public function datatable_prepare(
        $join_tables,
        $params,
        $filter = false,
        $limit = false
        )
    {
        $this->db->from($this->_table);

        if (is_array($join_tables) && count($join_tables)) {
            // format array $join_tables
            // array('users_level',
            //  '.level_id = user_levels.id',
            //  'left'
            //  )
            foreach ($join_tables as $join) {
                $table    = $join[0];
                $criteria = $join[1];
                $dir      = null;
                if (count($join) === 3) $dir = $join[2];

                $this->db->join($table, $criteria, $dir);
            }
        }

        if ($filter) {
            $columns = $this->datatable_columns;
            $where = '';
            foreach ( $columns as $column => $alias ) {
                if (isset($params[$alias])) {
                    $value = $this->db->escape("%{$params[$alias]}%");

                    $where .= "$column LIKE $value || ";
                    // $this->db->or_like($column, $params[$alias]);
                }
            }

            if (!empty($where)){
                // hapus string || terakhir dari $where
                $where = substr($where, 0, strlen($where)-3);
                //parentheses/kurungin >> LIKE ... OR LIKE ...
                $this->db->where("($where)");
            }

            if (isset($params['sort_by']))
                $this->db->order_by($params['sort_by'], $params['sort_dir']);

        }

        if ($limit) {
            if (isset($params['limit']))
                $this->db->limit($params['limit']['end'], $params['limit']['start']);
        }
    }

    /**
     * datatable_prepare
     * @param  array   $join_tables - array joined table dan criterianya,
     *                 set $join_tables = false, jika tidak ada join tables
     * @param  array   $params      param dari input post datatable
     * @param  boolean $filter      [description]
     * @param  boolean $limit       [description]
     * @return [type]               [description]
     */
    public function datatable_prepare_or(
        $params,
        $filter = false,
        $limit = false
        )
    {

        if ($filter) {
            $columns = $this->datatable_columns;
            $where = '';
            foreach ( $columns as $column => $alias ) {
                if (isset($params[$alias])) {
                    $value = $this->db->escape("%{$params[$alias]}%");

                    $where .= "$column LIKE $value || ";
                    // $this->db->or_like($column, $params[$alias]);
                }
            }

            if (!empty($where)){
                // hapus string || terakhir dari $where
                $where = substr($where, 0, strlen($where)-3);
                //parentheses/kurungin >> LIKE ... OR LIKE ...
                $this->db->where("($where)");
            }

        }

        if ($limit) {
            if (isset($params['limit']))
                $this->db->limit($params['limit']['end'], $params['limit']['start']);
        }
    }

    /**
     * [datatable_param description]
     * @return array $param
     */
    public function datatable_param()
    {
    	$orders = $this->input->post('order');
    	$_columns = $this->input->post('columns');
    	$search = $this->input->post('search');

        $start        = intval($this->input->post('start', true));
        $limit        = intval($this->input->post('length', true));
        $sort_col0    = intval($orders[0]['column'], true);
        $sort_dir0    = $orders[0]['dir'];
        $sort_able0   = $_columns[$sort_col0]['orderable'] === 'true';
        $sorting_cols = intval(count($_columns));
        $str_search   = $search['value'];

        $params = array();
        $columns = $this->datatable_columns; //default definisi column untuk datatable filter
        //die (dump($columns));
        // implementasi definisi columns dari input post datatable
       
    	$s_columns = $this->input->post('columns');	
    	
        $datatable_s_columns = array();
        foreach ($s_columns as $s_column) {
        	if(isset($s_column['name']) && $s_column['name'] != '')
        	{
        		$datatable_s_columns[] = $s_column['name'];
        	}
        }


        // cari table column sebenarnya dari tiap column di $datatable_s_columns
        if (count($datatable_s_columns)) {
            $columns = $datatable_s_columns;
            foreach ($datatable_s_columns as $key => $alias ) {
            	$array_alias = explode(' ', $alias);
              
                $columns[$alias] = $array_alias[1];
                
                unset($columns[$key]);
            }
            // die(dump($columns));
            // print_r($columns);
        }
        // if (isset($this->datatable_columns_index)) $columns_idx = $this->datatable_columns_index;

        // Filtering
        if (isset($str_search) && !empty($str_search))
        {
            $i = 0;
            foreach ($columns as $column => $alias ) {
                $bSearchable = $_columns[$i]['searchable'];

                // Individual column filtering
                if (isset($bSearchable) && $bSearchable === 'true') {
                    $params[$alias] = $str_search;
                }
                $i++;
            }
        }

        // Paging
        if (isset($start) && $limit >= 0) {
            $params['limit']['end']   = $limit;
            $params['limit']['start'] = $start;
        }

        // Ordering
        if ($sort_able0)
        {
            // cari nama kolom yang mau di sort
            $i = 0;
            foreach ($columns as $column => $alias ) {
                if ($i == $sort_col0)
                {
                    $params['sort_by']  = $column;
                    $params['sort_dir'] = $sort_dir0;
                    break;
                }
                $i++;
            }

        }

        // TODO : implement multicolumn sort
        //

        return $params;
    }

    public function add_data($data)
	{
		return $this->db->insert($this->_table,$data);
	}

	public function edit_data($data,$id)
	{
		$this->db->where('id',$id);
		return $this->db->update($this->_table,$data);
	}

}



/* End of file MY_Model.php */

/* Location: ./application/models/MY_Model.php */