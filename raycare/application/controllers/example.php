<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller {

	public function __construct()
	{		
		parent::__construct();
	}

	public function index()
	{
		redirect('example/form');
	}

	public function form()
	{
		$assets = array();
        $config = 'assets/example/index';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
		
		$data = array(
			'title'          => config_item('site_name').' &gt;'.translate('Index', $this->session->userdata('language')), 
			'header'         => translate('Index', $this->session->userdata('language')), 
			'header_info'    => config_item('site_name'), 
			'breadcrumb'     => true,
			'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
			'css_files'      => $assets['css'],
			'js_files'       => $assets['js'],
			'content_view'   => 'example/index',
			);
		
		// Load the view
		$this->load->view('_layout', $data);
	}

	public function table()
	{
		$assets = array();
        $config = 'assets/example/table';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
		
		$data = array(
			'title'          => config_item('site_name').' &gt;'.translate('Table', $this->session->userdata('language')), 
			'header'         => translate('Table', $this->session->userdata('language')), 
			'header_info'    => config_item('site_name'), 
			'breadcrumb'     => true,
			'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
			'css_files'      => $assets['css'],
			'js_files'       => $assets['js'],
			'content_view'   => 'example/table',
			);
		
		// Load the view
		$this->load->view('_layout', $data);
	}

	public function pickers()
	{
		$assets = array();
        $config = 'assets/example/pickers';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
		
		$data = array(
			'title'          => config_item('site_name').' &gt;'.translate('Pickers', $this->session->userdata('language')), 
			'header'         => translate('Pickers', $this->session->userdata('language')), 
			'header_info'    => config_item('site_name'), 
			'breadcrumb'     => true,
			'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
			'css_files'      => $assets['css'],
			'js_files'       => $assets['js'],
			'content_view'   => 'example/pickers',
			);
		
		// Load the view
		$this->load->view('_layout', $data);
	}

	public function dropdowns()
	{
		$assets = array();
        $config = 'assets/example/dropdowns';
        $this->config->load($config, true);

        $assets = $this->config->item('assets', $config);
		
		$data = array(
			'title'          => config_item('site_name').' &gt;'.translate('Dropdowns', $this->session->userdata('language')), 
			'header'         => translate('Dropdowns', $this->session->userdata('language')), 
			'header_info'    => config_item('site_name'), 
			'breadcrumb'     => true,
			'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
			'css_files'      => $assets['css'],
			'js_files'       => $assets['js'],
			'content_view'   => 'example/dropdowns',
			);
		
		// Load the view
		$this->load->view('_layout', $data);
	}
}

/* End of file example.php */
/* Location: ./application/controllers/example.php */