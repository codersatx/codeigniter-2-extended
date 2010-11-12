<?php

/**
 * Class: MY_Model
 *
 * This is an intermediate class to give models base functions to CRUD database records quickly 
 * w/o additional implementation in individual models.
 *
 * The table will be linked to this model is (by default) based on the class name. For example, if 
 * the class name is "Blog", the table name would be the lowercase plural value "blogs". You can 
 * override the default table naming convention by passing it's value in when instantiating the 
 * new model.
 *
 * @author Beau Frusetta <beau.frusetta@gmail.com>
 * @version 1.0
 */
class MY_Model extends CI_Model {
	
	var $_table_name	= '';
	
	function MY_Model($_table="") 
	{
		parent::CI_Model();
		
		## Assign table name to model
		if ($_table == "") {
			$this->_table_name = plural(strtolower(get_class($this)));
		} else {
			$this->_table_name = strtolower($_table);
		}
	}
	
	/**
	 * Find
	 *
	 * This method is inherited by all models to be the faster way to look 
	 * up a record.
	 *
	 * @author Beau Frusetta <beau.frusetta@gmail.com>
	 * @version 0.1
	 * @param array $params associative array of params to use to find specific record(s) in the database.
	 *											parameter "id" is required for all calls to this method.
	 *											supported: id, field
	 *
	 * <code>
	 * 	$this->load->model('model_name');
	 * 	$this->model_name->find(array("id" => 1));
	 * </code>
	 *
	 */
	function find($params) 
	{	
		$CI =& get_instance();
		
		// params["value"] is required for all calls to find()
		if (empty($params["value"])) {
			return FALSE;
		}
		
		if ($this->_table_name != '' && $CI->db->table_exists($this->_table_name)) {
			if (empty($params["field"])) {
				if ($params["value"] && $params["value"] != '') {
					return $CI->db->get_where($this->_table_name, array('id' => $params["value"]));
				} 
			} else {
				if ($CI->db->field_exists($params["field"],$this->_table_name) && $params["value"] && $params["value"] != '') {
					return $CI->db->get_where($this->_table_name, array($params["field"] => $params["value"]));
				}
			}
		} else {
			return FALSE;
		}
	}
	
	/**
	 * All
	 *
	 * This function returns all the records for the specified model.
	 *
	 * @author Beau Frusetta <beau.frusetta@gmail.com>
	 * @version 1.0
	 * @param *coming soon*
	 *
	 */
	function all($params=array()) 
	{
		$CI =& get_instance();
		
		if ($this->_table_name != '' && $CI->db->table_exists($this->_table_name)) {
			return $CI->db->get($this->_table_name);
		}
		
		return FALSE;
	}
	
	/**
	 * Create
	 *
	 * This method is used to create a new record quickly based on values passed in via params variable
	 *
	 * @author Beau Frusetta <beau.frusetta@gmail.com>
	 * @version 0.1
	 * @param array $params associative array of fields to create a valid record for the model.
	 *
	 */
	function create($params) 
	{
		$CI =& get_instance();
		
		if ($this->_table_name != '' && $CI->db->table_exists($this->_table_name)) {
			
			// Grab a local list of all the fields
			$fields = $CI->db->get_where("information_schema.COLUMNS", array("TABLE_SCHEMA" => $CI->db->database, "TABLE_NAME" => $this->_table_name));
			$field_names = array();
						
			// Find the fields that are "required" per the database
			$required_fields = array();
			foreach($fields->result() as $field) {
				$field_names[] = $field->COLUMN_NAME;
				if ($field->IS_NULLABLE == "NO" && $field->COLUMN_NAME != "id") {
					$required_fields[] = $field->COLUMN_NAME;
				}
			}
			
			// Check for all required fields on input
			foreach($required_fields as $field) {
				if (empty($params[$field])) {
					log_message('error', 'Field "'.$field.'" is required to create a row on table "'.$this->_table_name.'"');
					return FALSE;
				}
			}
			
			// Check for fields that don't exist
			foreach($params as $key => $value) {
				if (!in_array($key, $field_names)) {
					unset($params[$key]);
				}
			}
			
			## Set default fields
			$params["created_at"] = date("Y-m-d h:i:s");
			$params["updated_at"] = date("Y-m-d h:i:s");
			
			if ($CI->db->insert($this->_table_name,$params)) {
				return TRUE;
			} else {
				log_message('error', 'Your insert query has an error - please check your input data.');
			}
			
		}
		
		return FALSE;
	}
	
	/**
	 * Destroy
	 *
	 * This method is used to destroy a record by id. 
	 *
	 * @author Beau Frusetta <beau.frusetta@gmail.com>
	 * @version 0.1
	 * @param int $id REQUIRED
	 *
	 */
	function destroy($id) 
	{
		$CI =& get_instance();
		
		if ($this->_table_name != '' && $CI->db->table_exists($this->_table_name)) {
			return $CI->db->delete($this->_table_name, array('id' => $id));
		}
	}
	
}