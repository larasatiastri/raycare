<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_m extends MY_Model {

    protected $_table      = 'menu';
    protected $_order_by   = 'parent_id ASC, m_order ASC';
    protected $_timestamps = true;

    private $menufile;

    private $_fillable_add = array(
        'parent_id',
        'nama',
        'm_order',
        'url',
        'icon_class',
    );

    private $_fillable_edit = array(
        'parent_id',
        'nama',
        'url',
        'icon_class',
        'is_active'
    );

    // Array of database columns which should be read and sent back to DataTables
    protected $datatable_columns = array(
        //column di table  => alias
        'menus.id'        => 'id', 
        'menus.parent_id' => 'parent_id',  
        'menus.nama'      => 'nama', 
        'menus.url'       => 'url', 
        'menus.m_order'     => 'm_order',
        'menus.is_active'    => 'is_active'
    );

    public function __construct()
    {
        parent::__construct();
        
        $this->menufile  =  FCPATH . 'application/menu/' 
            . $this->session->userdata('session');
    }

    /**
     * get recursive menu in tree format
     * @param  integer $user_level_id 
     * @return array $menus               
     */
    public function get_nested($user_level_id)
    {
        
        if (file_exists($this->menufile) && is_file($this->menufile)) {
            $serialized = read_file($this->menufile);
            $menus = unserialize($serialized);
            if (! is_array($menus)) $menus = array();

            return($menus);
        }

       
        $where = array(
            'menus.is_active' => 1
            
            );

        $select = array(
            'id', 
            'parent_id', 
            'nama', 
            'url',
            'icon_class'
            );
        
        $this->db
            ->select($select)
            ->where($where)
            ->order_by( $this->_order_by);

        $result     = $this->db->get($this->_table)->result_array();
        $menus      = $this->build_menu($result);
        $serialized = serialize($menus);

        // if (! write_file($this->menufile, $serialized, 'w+') ) {
        //     die('unable to write menu file');
        // }

        return $menus;

    }
    /**
     * [build_menu description]
     * @param  [type]  $rows      [description]
     * @param  integer $parent_id [description]
     * @return [type]             [description]
     */
    private function build_menu($rows, $parent_id=0)
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
    private function has_children($rows, $id)
    {
        foreach ($rows as $row) {
            if ($row['parent_id'] == $id) return true;
        }

        return false;

    }

    public function delete_menu_file()
    {
        if (file_exists($this->menufile) && is_file($this->menufile)) {
            unlink($this->menufile);
        }
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
        $this->db->where('parent_id',0);
        $this->db->or_where('parent_id is null');
       
        // dapatkan total row count;
        $total_records = $this->db->count_all_results();
        // die(dump($total_records));

        // prepare buat total record filtered/search, 
        // filter=true, limit=false
        $this->datatable_prepare($join_tables, $params, true);

        $this->db->where('parent_id',0);
        $this->db->or_where('parent_id is null');
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

        $this->db->where('parent_id',0);
        $this->db->or_where('parent_id is null');
        //return  result object
        $result = new stdClass();

        $result->columns               = $this->datatable_columns;
        $result->total_records         = $total_records;
        $result->total_display_records = $total_display_records;
        $result->records               = $this->db->get(); 

        // die(dump($result->records));

        return $result; 
    }

    public function get_datatable_child($parent_id)
    {

        $join_tables = array();

        // get params dari input postnya datatable
        $params = $this->datatable_param($this->datatable_columns);
        
        // prepare buat total record tanpa filter dan limit
        // filter = false, limit= false 
        $this->datatable_prepare($join_tables, $params);
        $this->db->where('parent_id',$parent_id);
        
       
        // dapatkan total row count;
        $total_records = $this->db->count_all_results();
        // die(dump($total_records));

        // prepare buat total record filtered/search, 
        // filter=true, limit=false
        $this->datatable_prepare($join_tables, $params, true);

        $this->db->where('parent_id',$parent_id);
        
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

        $this->db->where('parent_id',$parent_id);
        
        //return  result object
        $result = new stdClass();

        $result->columns               = $this->datatable_columns;
        $result->total_records         = $total_records;
        $result->total_display_records = $total_display_records;
        $result->records               = $this->db->get(); 

        // die(dump($this->db->last_query()));

        return $result;       
    }

    public function up_order($parent_id,$id,$order)
    {
        $new_order = $order -1;
        $up = array(
            'm_order' => (integer)($order - 1)
        );
        $this->db->where('parent_id',$parent_id);
        $this->db->where('m_order', $order);
        $this->db->where('id', $id);
        //move menu 1 order up
        $this->db->update($this->_table, $up);
        
        $down = array(
            'm_order' => (integer)($new_order + 1)
        );
        $this->db->where('parent_id',$parent_id);
        $this->db->where('m_order', $new_order);
        $this->db->where('id !=', $id);
        //move menu 1 order down
        $this->db->update($this->_table, $down);
    }

    public function down_order($parent_id,$id,$order)
    {
        $new_order = $order +1;
        $down = array(
            'm_order' => (integer)($order + 1)
        );
        $this->db->where('parent_id',$parent_id);       
        $this->db->where('m_order', $order);
        $this->db->where('id', $id);
        //move menu 1 order down
        $this->db->update($this->_table, $down);


        $up = array(
            'm_order' => (integer)($new_order - 1)
        );
        $this->db->where('parent_id',$parent_id);       
        $this->db->where('m_order', $new_order);
        $this->db->where('id !=', $id);
        //move menu 1 order up
        $this->db->update($this->_table, $up);
             
    }

    public function get_parent_menu()
    {
        $SQL = "SELECT * from menus where (id in (select parent_id from menus) and parent_id <> 0) AND is_active = 1 OR (parent_id = 0) AND is_active = 1 order by parent_id, 'order'";
        return $this->db->query($SQL);
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

/* End of file menu_m.php */
/* Location: ./application/models/master/menu_m.php */