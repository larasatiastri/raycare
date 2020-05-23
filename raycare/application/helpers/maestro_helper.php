<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('datatable_language'))
{
    function datatable_language($lang='en')
    {
        return array(
            'emptyTable'     => translate('No data available in table', $lang), 
            'info'           => translate('Showing', $lang) . ' _START_ ' . translate('to', $lang) . ' _END_ ' . translate('of', $lang) . ' _TOTAL_ ' . translate('entries', $lang), 
            'infoEmpty'      => translate('Showing', $lang) . ' 0 ' . translate('to', $lang) . ' 0 ' . translate('of', $lang) . ' 0 ' . translate('entries', $lang),
            'infoFiltered'   => '(' .  translate('filtered from', $lang) . ' _MAX_ ' . translate('total entries', $lang) . ')',
            'infoPostFix'    => '',
            'search'         => translate('Search', $lang) . ':',
            'thousands'  => ',', 
            'lengthMenu'     => translate('Show', $lang)  . ' _MENU_ '  . translate('entries', $lang),
            'loadingRecords' => translate('Loading', $lang) . '...',
            'processing'     => translate('Processing', $lang) . '...',
            'zeroRecords'    => translate('No matching records found', $lang),
            'paginate'       => array(
                'first'     => translate('First', $lang),
                'last'      => translate('Last', $lang),
                'next'      => translate('Next', $lang),
                'previous'  => translate('Previous', $lang),
                'page'      => translate('Page', $lang),
                'pageOf'    => translate('of', $lang),
                ),
            'aria'=>  array( 
                'sortAscending'  =>  ': '. translate('activate to sort column ascending', $lang),
                'sortDescending' =>  ': '. translate('activate to sort column descending', $lang),
            ),            
        );      
    }
}

if(!function_exists('translate'))
{
    function translate($string, $language)
    {

        $lang_file = FCPATH . 'application/language/' . $language . '.php';

        if(file_exists($lang_file))
        {
            include($lang_file);

            if (isset($lang[$string]))
            {
                if ($lang[$string] != '') return $lang[$string];
                else return $string;
            }

            else //if languange not exist, add to the language database file
            {
                $data = "\$lang['$string'] = '';\n";
                $file = fopen($lang_file, 'a');
                fwrite($file, $data, strlen($data));
                fclose($file);
                return $string;
            }
        }

        else
        {   //when language file not found, create new language file
            $data = "<?php\n\$lang = array();\n";          
            $data .= "\$lang['$string'] = '';\n";
            $file = fopen($lang_file, 'w+');
            fwrite($file, $data, strlen($data));
            fclose($file);
            return $string;
        }
    }
}

if(!function_exists('get_all_lang'))
{
    function get_all_lang($language)
    {

        $lang_file = FCPATH . 'application/language/' . $language . '.php';

        if(file_exists($lang_file))
        {
            include($lang_file);

            return $lang;
        }
        else
        {
            //when language file not found, create new language file
            $data ="<?php\n\$lang = array();\n";

            $file = fopen($lang_file, 'w+');
            fwrite($file, $data, strlen($data));
            fclose($file);

            $lang = array();

            return $lang;
        }
    }
}

/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable 
        ob_start();
        var_dump($var);
        $output = ob_get_clean();        

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';        

        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

if (!function_exists('die_dump')) {
    function die_dump ($var, $label = 'Dump', $echo = TRUE)
    {
        die(dump ($var, $label, $echo));
    }
}

/**
 * generate user menus
 * $menus [array]
 */

if (!function_exists('generate_user_menus'))
{
    // function generate_user_menus ($menus=array(), $menu_id=null, $menu_parent_id=null)
    function generate_user_menus ($menus=array(), $menu_tree=array())
    {
        // die(dump($menus));
        $CI =& get_instance();
        $str = '';
        // $m_first = $menu_tree[0];
        // $m_last = $menu_tree[count($menu_tree) -1];

        foreach ($menus as $menu)
        {
            $m_id = intval($menu['id']);            

            $str .= '<li id="menu_id_' . $menu['id'] . '" class="menu-dropdown classic-menu-dropdown">'; 
          
            if ($menu['base_url'] === NULL && $menu['url'] === NULL)
                $str .= '<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">';
            else 
            {
                if(substr($menu['url'], 0,4) != 'http')
                {
                    $base_url = base_url();
                    if($menu['base_url'] !== NULL) $base_url = $menu['base_url'];
                    $str .= '<a href="'.$base_url. $menu['url'] . '" >';    
                }
                else
                {
                    $str .= '<a href="'.$menu['url'] . '">';
                }
            }
       
            $str .= translate($menu['nama'], $CI->session->userdata("language"));            

            // span class arrow
            if ($menu['url'] === '' || $menu['url'] === NULL) {
                $str .= '<i class="fa fa-angle-down"></i>';
            }
            else
            {
                $str .= '';
            }

            $tgl_buat_menu = date('Y-m-d', strtotime($menu['created_date']));
            $tgl_1_bulan = date('Y-m-d', strtotime("-1 months"));
            $tgl_skrg = date('Y-m-d');

            if($tgl_buat_menu <= $tgl_skrg && $tgl_buat_menu >= $tgl_1_bulan){
                $str .= '&nbsp;&nbsp;<span class="badge badge-roundless badge-success" style="border-radius: 0 !important">new</span>';   

            }
            $str .= '</a>' . PHP_EOL;

            // generate sub menu kalo ada
            if (isset($menu['children']) && count($menu['children']))
            {              
                $has_child = false;
                $str .= '<ul class="dropdown-menu pull-left">';
               
                foreach ($menu['children'] as $child) 
                {
                    $has_child = false;
                    if (isset($child['children']) && count($child['children']))
                    {
                        $has_child = true; 
                        $str .= generate_user_menus_sub_parent($child, $menu_tree);
                    }
                    else
                    {
                        $str .= generate_user_menus_child($child, $menu_tree);
                    }
                }
   
      
                $str .= '</ul>' . PHP_EOL;
            }   

            $str .= '</li>' . PHP_EOL;  
        }

        return($str);
    }
}

function generate_user_menus_sub_parent($menu=array(), $menu_tree=array())
{
    $CI =& get_instance();
    $str = '';
 
    $m_id = intval($menu['id']);            
    $icon_class = (string) $menu['icon_class'] ;
    
    $str .= '<li id="menu_id_' . $menu['id'] . '" class="dropdown-submenu">';
    $str .= '<a href=javascript:;>';
    $str .= '<i class="' . $icon_class . '"></i>';
    $str .= '&nbsp;&nbsp;'.translate($menu['nama'],$CI->session->userdata('language')); 

    $tgl_buat_menu = date('Y-m-d', strtotime($menu['created_date']));
    $tgl_1_bulan = date('Y-m-d', strtotime("-1 months"));
    $tgl_skrg = date('Y-m-d');

    if($tgl_buat_menu <= $tgl_skrg && $tgl_buat_menu >= $tgl_1_bulan){
        $str .= '&nbsp;&nbsp;<span class="badge badge-roundless badge-success" style="border-radius: 0 !important">new</span>';   

    }
    $str .= '</a>' . PHP_EOL;

    // generate sub menu kalo ada
    if (isset($menu['children']) && count($menu['children']))
    {      
        $str .= '<ul class="dropdown-menu">';
        foreach ($menu['children'] as $child) 
        {
            $str .= generate_user_menus_child($child, $menu_tree);
        }
        $str .= '</ul>';
    }   

    $str .= '</li>'. PHP_EOL;  

    return($str);
}

function generate_user_menus_child ($menu=array(), $menu_tree=array())
{
    $CI =& get_instance();
    $str = '';
    $m_id = intval($menu['id']);            
    $str .= '<li id="menu_id_' . $menu['id'] . '" ';

    $str .= '>' . PHP_EOL;   

    if ($menu['base_url'] === NULL && $menu['url'] === NULL)
        $str .= '';
    else
    {
        if(substr($menu['url'], 0,4) != 'http')
        {
            $base_url = base_url();
            if($menu['base_url'] !== NULL) $base_url = $menu['base_url'];
            $str .= '<a href="'.$base_url. $menu['url'] . '" class="iconify">';    
        }
        else
        {
            $str .= '<a href="'.$menu['url'] . '" class="iconify">';
        }
    }
   
   
    $icon_class = (string) $menu['icon_class'] ;

    $str .= '<i class="' . $icon_class . '"></i>';
    $str .= '&nbsp;&nbsp;'.translate($menu['nama'], $CI->session->userdata("language"));            

    // span class arrow
    if ($menu['base_url'] === NULL && $menu['url'] === NULL) {

        $str .= '<span class="arrow';

        $str .= '"></span>';
    }
    else
    {
        $str .= '';
    }

    $tgl_buat_menu = date('Y-m-d', strtotime($menu['created_date']));
    $tgl_1_bulan = date('Y-m-d', strtotime("-1 months"));
    $tgl_skrg = date('Y-m-d');

    if($tgl_buat_menu <= $tgl_skrg && $tgl_buat_menu >= $tgl_1_bulan){
        $str .= '&nbsp;&nbsp;<span class="badge badge-roundless badge-success" style="border-radius: 0 !important">new</span>';   

    }

    $str .= '</a>' . PHP_EOL;

    // generate sub menu kalo ada
    if (isset($menu['children']) && count($menu['children']))
    {               
        $str .= generate_user_menus_child($menu['children'], $menu_tree);
    }   

    $str .= '</li>'. PHP_EOL;  
    
    return($str);
}



/**
 * generate user menus
 * $menus [array]
 */
if (!function_exists('bs_anchor_btn')) 
{
    function bs_anchor_btn ($text, $attrs=array(), $icon_class = '')
    {
        $str = '<a ';

        foreach ($attrs as $attr_name => $attr_value){
            $str .= $attr_name . '="' . $attr_value . '" ';
        }

        $str .= '><i class="' . $icon_class. '"></i>' . $text . '</a>';

        return $str;
    }
}

if(!function_exists('konversi')){
    function konversi($x){
  
      $x = abs($x);
      $angka = array ("","Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
      $temp = "";
      
      if($x < 12){
       $temp = " ".$angka[$x];
      }else if($x<20){
       $temp = konversi($x - 10)." Belas";
      }else if ($x<100){
       $temp = konversi($x/10)." Puluh". konversi($x%10);
      }else if($x<200){
       $temp = " Seratus".konversi($x-100);
      }else if($x<1000){
       $temp = konversi($x/100)." Ratus".konversi($x%100);   
      }else if($x<2000){
       $temp = " Seribu".konversi($x-1000);
      }else if($x<1000000){
       $temp = konversi($x/1000)." Ribu".konversi($x%1000);   
      }else if($x<1000000000){
       $temp = konversi($x/1000000)." Juta".konversi($x%1000000);
      }else if($x<1000000000000){
       $temp = konversi($x/1000000000)." Milyar".konversi($x%1000000000);
      }
      
      return $temp;
    }
}

if(!function_exists('t_koma')){
    function tkoma($x){
      $str = stristr($x,".");
      $ex = explode('.',$x);
      
      if(($ex[1]/10) >= 1){
       $a = abs($ex[1]);
      }
      $string = array("Nol", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan");
      $temp = "";
     
      $a2 = $ex[1]/10;
      $pjg = strlen($str);
      $i =1;
        
      
      if($a>=1 && $a < 10){   
       $temp .= " ".$string[$a];
      }else if ($a>9 && $a<100){   
       $temp .= konversi($a / 10)." ". konversi($a % 10);
      }else{
       if($a2<1){
        
        while ($i<$pjg){     
         $char = substr($str,$i,1);     
         $i++;
         $temp .= " ".$string[$char];
        }
       }
      }  
      return $temp;
    }
}

if(!function_exists('terbilang'))
{
     function terbilang($x) {
  //       $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
  // if ($x < 12)
  //   return " " . $abil[$x];
  // elseif ($x < 20)
  //   return Terbilang($x - 10) . " Belas";
  // elseif ($x < 100)
  //   return Terbilang($x / 10) . " Puluh" . Terbilang($x % 10);
  // elseif ($x < 200)
  //   return " Seratus" . Terbilang($x - 100);
  // elseif ($x < 1000)
  //   return Terbilang($x / 100) . " Ratus" . Terbilang($x % 100);
  // elseif ($x < 2000)
  //   return " Seribu" . Terbilang($x - 1000);
  // elseif ($x < 1000000)
  //   return Terbilang($x / 1000) . " Ribu" . Terbilang($x % 1000);
  // elseif ($x < 1000000000)
  //   return Terbilang($x / 1000000) . " Juta" . Terbilang($x % 1000000);
        if($x<0){
            $hasil = "minus ".trim(konversi($x));
        }else{
           $poin = trim(tkoma($x));
           $hasil = trim(konversi($x));
        }
      
        if($poin){
            $hasil = $hasil." koma ".$poin;
        }else{
            $hasil = $hasil;
        }
        return $hasil; 
    }
}

