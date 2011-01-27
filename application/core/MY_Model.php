<?php

/**
 * Class: MY_Model
 *
 * @author Beau Frusetta <beau.frusetta@gmail.com>
 * @version 2.0
 */
class MY_Model extends CI_Model {
	
	var $attributes				= FALSE;
	var $has_many				= FALSE;
	var $has_one				= FALSE;
	var $belongs_to				= FALSE;
	var $spawn					= TRUE;
	var $primary_key			= "id";
	
	private $_table_name		= '';
	private $_table_prefix		= '';
	private $_error_message		= FALSE;
	private $_load_time			= 0;

	/**
	 * Function: MY_Model
	 * Description: This is the default constructor for the MY_Model class.
	 *
	 * @param id (mixed) - set to FALSE by default, requires integer value when value is given
	 * @param table (mixed) - set to FALSE by default, requires a string of a valid table name in the database selected
	 * @return boolean
	 */
	function MY_Model($id=FALSE, $table=FALSE) 
	{
		$time_start = microtime(true);
		
		## Setup the table variable
		if ($table) {
			if ($this->table($table)) {
				## Did they pass in an id?
				if ($id) {
					return $this->_setup($id);
				}
			}
		}
		
		$time_end = microtime(true);
		$this->_load_time = $time_end - $time_start;
	}
	
	/**
	 * Function: __get
	 * Description: Added in the __get() function to intercept any calls to potential attributes and route/return them properly.
	 *
	 * @param var (mixed) - by default, this the variable that was being called upon the class object.
	 * @return mixed - value, null, blank, or false depending on what was called.
	 */
	function __get($var) 
	{
		## Check to see if var exists as attribute
		if (isset($this->attributes->$var)) {
			return $this->attributes->$var;
		}
	}
	
	/**
	 * TODO
	 */
	function table($val=FALSE) 
	{
		if ($val) {
			$CI =& get_instance();
			if ($CI->db->table_exists($val)) {
				return $this->_table_name = $val;
			}
		} else {
			return $this->_table_name;
		}
	}
	
	/**
	 * TODO
	 */
	function error($val=FALSE) 
	{
		if ($val) {
			return $this->_error_message .= ' '.$val;
		} else {
			return $this->_error_message;
		}
	}
	
	/**
	 * TODO
	 */
	function refresh()
	{
		return $this->_setup($this->attributes->id);
	}
	
	/**
	 * TODO
	 */
	function create($params)
	{
		if ($this->table()) {
			
			$CI =& get_instance();
			
			## Grab a local list of all the fields
			$fields = $CI->db->get_where("information_schema.COLUMNS", array("TABLE_SCHEMA" => $CI->db->database, "TABLE_NAME" => $this->_table_name));
			$field_names = array();
						
			## Find the fields that are "required" per the database
			$required_fields = array();
			foreach($fields->result() as $field) {
				$field_names[] = $field->COLUMN_NAME;
				if ($field->IS_NULLABLE == "NO" && $field->COLUMN_NAME != "id" && is_null($field->COLUMN_DEFAULT)) {
					$required_fields[] = $field->COLUMN_NAME;
				}
			}
			
			## Check for all required fields on input
			foreach($required_fields as $field) {
				if (empty($params[$field]) === true) {
					error_log('Field "'.$field.'" is required to create a row on table "'.$this->_table_name.'"');
					return FALSE;
				}
			}
			
			## Check for fields that don't exist
			foreach($params as $key => $value) {
				if (!in_array($key, $field_names)) {
					unset($params[$key]);
				}
			}
			
			## Set default fields
			$params["created_at"] = date("Y-m-d h:i:s");
			$params["updated_at"] = date("Y-m-d h:i:s");
			
			if ($CI->db->insert($this->_table_name,$params)) {
				$return_id = $CI->db->insert_id();
				$this->_setup($return_id);
				return $return_id;
			} else {
				error_log('Your insert query has an error. '.$CI->db->_error_message());
			}
			
		} else {
			$this->error("Your object is not setup. Please set a table using the table() function.");
			return FALSE;
		}
		
		return FALSE;
	}
	
	/**
	 * TODO
	 */
	function update_attribute($field,$value)
	{
		if ($this->table()) {
			$CI =& get_instance();
			$CI->db->update($this->_table_name, array($field => $value, "updated_at" => date("Y-m-d h:i:s")), array("id" => $this->attributes->id));
			
			if ($CI->db->_error_message() == '') { 
				$this->refresh();
				return TRUE; 
			} else {
				$this->_error_message = $CI->db->_error_message();
				return FALSE;
			}
		}
		return FALSE;
	}
	
	/**
	 * TODO
	 */
	function update_attributes($fields)
	{
		foreach ($fields as $name => $value) {
			if (!$this->update_attribute($name, $value)) {
				return FALSE;
			}
		}
		return TRUE;
	}
	
	/**
	 * TODO
	 */
	function destroy()
	{
		if ($this->table()) {
			
			$CI =& get_instance();
			
				if ($return = $CI->db->delete($this->_table_name, array('id' => $this->attributes->id))) {
				$this->attributes = FALSE;
				return TRUE;
			}
			
		}
		return FALSE;
	}
	
	/**
	 * TODO
	 */
	function all($filter=FALSE)
	{
		if ($this->table()) {
			$CI =& get_instance();
			
			if ($filter) {
				return ($CI->db->get_where($this->_table_name, $filter));
			} else {
				return ($CI->db->get($this->_table_name));	
			}
		} else {
			$this->error("Your object is not setup. Please set a table using the table() function.");
			return FALSE;
		}
	}
	
	/**
	 * TODO
	 */
	private function _spawn($params)
	{
		$CI =& get_instance();
		
		## Set Class Variable
		if (isset($params["class"])) { $class = strtolower(singular($params["class"])); } else { return FALSE; }
		
		## Set Type Variable
		if (isset($params["type"])) { $type = $params["type"]; } else { return FALSE; }
		
		## Load up an instance of the class
		$CI->load->model($class);
		$obj = new $class;
		
		## Proceed based on "type"
		switch($type) {
			
			case "has_many":
				
				## Check for specific foreign key
				if (isset($params["foreign_key"])) { $foreign_key = $params["foreign_key"]; } else { $foreign_key = $this->_foreign_key(); }
				
				## Setup the children
				$obj_array = array();
				$children = $obj->all(array($foreign_key => $this->id));
				
				## Push children in to array of objects
				foreach($children->result() as $child) {
					$obj_class = new $class;
					$obj_class->_setup($child->id);
					$obj_array[] = $obj_class;
				}

				## Pluralize the class and create an array
				$var_name = plural($class);
				$this->$var_name = $obj_array;
			
				break;
				
			case "belongs_to":
			case "has_one":
				
				if ($type == "belongs_to") {
					
					## Check for specific foreign key
					if (isset($params["foreign_key"])) { $foreign_key = $params["foreign_key"]; } else { $foreign_key = $class."_id"; }

					## SUPER IMPORTANT: Always set this to false so it doesn't spawn DOWN - endless loop much?
					$obj->spawn = FALSE;
					
				}
				
				if ($type == "has_one") {
					
					## Check for specific foreign key
					if (isset($params["foreign_key"])) { $foreign_key = $params["foreign_key"]; } else { $foreign_key = $this->_foreign_key(); }
					
				}
				
				## Setup the parent record
				$obj->_setup($this->$foreign_key);
				
				## Save it in to the object
				$this->$class = $obj;
			
				break;
				
			default:
				return FALSE;
				
		}
		
		return TRUE;
	}

	/**
	 * TODO
	 */ 
	private function _setup($id) 
	{
		if ($this->table()) {
			$CI =& get_instance();
			
			## Determine the table prefix
			$prefix = explode("_", $this->_table_name);
			if (count($prefix) > 1) { 
				$this->_table_prefix = $prefix[0]."_";
			} 
			
			$query = $CI->db->get_where($this->table(), array($this->primary_key => $id));
			if ($query->num_rows() > 0) {
				
				## Set the attributes in the object
				$this->attributes = $query->row();
				
				if ($this->spawn) {
					
					## Process the "has_many" relationships
					if ($this->has_many) {
						
						foreach ($this->has_many as $child) {
							
							## Consistency Checks
							if (!is_array($child)) { $child = array("class" => $child); } 
							$child["class"] = singular($child["class"]);
							$child["type"] = "has_many";
							
							## Spawn the child!
							$this->_spawn($child);
						}

					}

					## Process the "has_one" relationships
					if ($this->has_one) {
				
						foreach ($this->has_one as $child) {
							
							## Consistency Checks
							if (!is_array($child)) { $child = array("class" => $child); } 
							$child["class"] = singular($child["class"]);
							$child["type"] = "has_one";
							
							## Spawn the child!
							$this->_spawn($child);
							
						}

					}
					
				}
				
				## Process the "belongs_to" relationships
				if ($this->belongs_to) {

					foreach ($this->belongs_to as $parent) {
						
						## Consistency Checks
						if (!is_array($parent)) { $parent = array("class" => $parent); } 
						$parent["class"] = singular($parent["class"]);
						$parent["type"] = "belongs_to";
						
						## Spawn the child!
						$this->_spawn($parent);
						
					}

				}
				
				## Kill the relationship vars
				unset($this->has_one);
				unset($this->has_many);
				unset($this->belongs_to);
				unset($this->spawn);
				
				return TRUE;
			} else {
				$this->error("A record can not be found using ID:$id.");
				return FALSE;
			}
		} else {
			$this->error("Your object is not setup. Please set a table using the table() function.");
			return FALSE;
		}
	}
	
	/**
	 * TODO
	 */
	private function _foreign_key()
	{
		$key = singular(str_replace($this->_table_prefix, '', $this->_table_name))."_id";
		return $key;
	}

	
}