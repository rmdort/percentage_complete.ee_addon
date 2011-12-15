<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
                        'pi_name'           => 'Percentage Complete',
                        'pi_version'        => '1',
                        'pi_author'         => 'Vinay m',
                        'pi_author_url'     => 'http://www.artminister.com/',
                        'pi_description'    => 'Percentage complete like : http://drupal.org/project/pcp',
                        'pi_usage'          => NlbaFormat::usage()
                    );

/**
 * Xhtml Class
 *
 * @package         ExpressionEngine
 * @category        Plugin
 * @author          Artminister
 * @copyright       Copyright (c) 2005 - 2009, EllisLab, Inc.
 * @link            http://artminister.com
 */
class Percentage_complete {

    var $return_data;
      
		function percentage_complete(){
		  
		  $this->EE=& get_instance();
		  $entry_id = intval($this->_get_param('entry_id'));
		  $channel_id = intval($this->_get_param('channel_id'));
		  $site_id = intval($this->_get_param('site_id',1));
		  
		  // Get Field Group ID From Channel_id
		  
		  $field_query = $this->EE->db->query("SELECT field_group FROM exp_channels WHERE channel_id=".$channel_id);
		  $field_group_id = $field_query->row('field_group');
		  
		  
		  $field_array = array();
		  $result_array = array();
		  $final_array = array();
		  $field_names= array();
		  
		  // Query All the Fields and Add them to Array
		  
		  $fquery = $this->EE->db->query("SELECT field_id, field_label FROM exp_channel_fields WHERE site_id = ".$site_id." AND group_id=".$field_group_id);
		  
		  if ($fquery->num_rows() > 0)
      {
          foreach($fquery->result_array() as $row)
          {
              array_push($field_array, "field_id_".$row['field_id']);
              $field_names['field_id_'.$row['field_id']] = $row['field_label'];
          }
      }
      
      // Query Values of the Fields and Add them to Array
      
      $results = $this->EE->db->query("SELECT * FROM exp_channel_data WHERE site_id=".$site_id." AND channel_id=".$channel_id." AND entry_id=".$entry_id." LIMIT 1");
      
      if ($results->num_rows() > 0){
        foreach($results->result_array() as $r => $key)
        {
          $result_array = $key;
        }
      }
      
      foreach($field_array as $field => $k)
      {
        if($k !="field_id_17"){
          $final_array[$k] = $result_array[$k];
        }
        
      }
      
      // Find the Empty Values in the Array      
      
      
      foreach($final_array as $arr => $k){
        if($final_array[$arr] == "") {
          $name = $field_names[$arr];
          break;
        }        
      }      
      
      //print_r($field_names);
      
      $percentage = count(array_filter($final_array))/count($final_array) * 100;
      $percentage = round($percentage/5)*5;
      
      $next_percentage = (count(array_filter($final_array))+1)/count($final_array) * 100;
      $next_percentage = round($next_percentage/5)*5;
      
      $data[] = array(
        'percentage' => $percentage,
        'complete' => "asda",
        'empty_field' => $name,
        'next_percentage' => $next_percentage
      );
      
      $r = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
      
      if($percentage > 95){
        return;
      }else{
        return $r;
      }		  
		  
		}
      
    // --------------------------------------------------------------------
    
    /**
    * Usage
    *
    * Plugin Usage
    *
    * @access   public
    * @return   string
    */
    function usage()
    {
        ob_start(); 
        ?>
        
        {exp:nlbaformat:percentage_complete channel_id="{channel_id}" entry_id="{entry_id}" field_group_id="2"}
          <p>Your profile is {percentage}% complete</p>
          <p>Completing {empty_field} will bring your profile to {next_percentage}% Complete</p>
        {/exp:nlbaformat:percentage_complete}


        <?php
        $buffer = ob_get_contents();
    
        ob_end_clean(); 

        return $buffer;
    }
		
		
		/**
	     * Helper function for getting a parameter
		 */		 
		function _get_param($key, $default_value = '')
		{
			$val = $this->EE->TMPL->fetch_param($key);

			if($val == '') {
				return $default_value;
			}
			return $val;
		}
		
    // --------------------------------------------------------------------

}
// END CLASS

/* End of file pi.xhtml.php */
/* Location: ./system/expressionengine/third_party/xhtml/pi.percentag_complete.php */

