<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * PHP based database schema migrations similar to Ruby on Rails migrations.
 *
 * Migration tasks available: create_table, drop_table, add_column, remove_column
 *
 * Migration tasks are built by calling 'phake db:create_migration'. Running that task will create 
 * a migration file under application/migrations. Modify that file using the proper 
 * migration layout for the task you wish to complete.
 *
 * Migration examples are located at the top of each migration file created from 
 * application/migrations/default.xml.
 *
 * @author 	Beau Frusetta <beau.frusetta@gmail.com>
 * @version 1.0
 */
class Migration {
	
	/**
	 * The allowed_actions class var is an array that contains all the actions a migration may have in them. If an action is passed in and 
	 * does not exist in this list, the action will fail.
	 */
	var $allowed_actions = array("create_table", "drop_table", "add_column", "change_column", 
															 "rename_column", "remove_column", "add_index", "remove_index");
	
	/**
	 * Default constructor.
	 *
	 * @access public
	 */
	public function Migration()
	{
		## Default Constructor
	}
	
	/**
	 * This function creates a default migration file under the application/migration path. Files are named <unix_timestamp>.xml and are generated 
	 * from application/migration/default.xml.
	 *
	 * @return boolean
	 * @access public
	 */
	public function create_migration()
	{
		$default_migration = read_file(APPPATH.'migrations/default.xml');
		$filename = APPPATH.'migrations/'.time().'_Migration.xml';
		write_file($filename,$default_migration);
		return true;
	}
	
	/**
	 * This function creates the database listed in application/config/database.php. If the database exists, a value of FALSE will be returned, 
	 * otherwise a value of TRUE will be returned on success.
	 *
	 * @return boolean
	 * @access public
	 */
	public function create_database()
	{
		$CI =& get_instance();
		$CI->load->dbforge();
		$CI->load->dbutil();
		
		## Try to create the database, if it already exists, dbforge() will return FALSE
		if (!$CI->dbutil->database_exists($CI->db->database)) {
			if ($CI->dbforge->create_database($CI->db->database)) {
				$this->_show_message("Database '".$CI->db->database."' created successfully.");
			} else {
				$this->_show_message("Database '".$CI->db->database."' did not create successfully. Please check your config values in application/config/database.php.");
			}
		} else {
			$this->_show_message("Database '".$CI->db->database."' already exists.");
			return FALSE;
		}
	}
	
	/**
	 * This function runs all database migrations that haven't been run against 
	 * the database specified in application/config/database.php. A record of 
	 * migrations that have been applied are placed in to the schema_migrations 
	 * table in the database.
	 *
	 * @return boolean
	 * @access public
	 */
	public function run()
	{
		$CI =& get_instance();
		
		## Check for the schema migrations table
		$this->_check_schema_migrations();
		
		## Grab all the files
		$files = get_filenames(APPPATH.'migrations/');
		
		echo "\n";
		
		foreach($files as $file) {
			if ($file != 'default.xml') {
				
				$query = $CI->db->get_where('schema_migrations', array("filename" => $file));
				if ($query->num_rows() == 0) {
					$this->_run_migration($file);
				}
				
			}
		}
		
		// $migration = new SimpleXMLElement(read_file(APPPATH.'migrations/default.xml'));
		return true;
	}
	
	/**
	 * This function resets the database completely, and runs each migration in 
	 * application/migrations from the first to the last.
	 * 
	 * @return boolean
	 * @access public
	 */
	public function reset() 
	{
		$CI =& get_instance();
		$CI->load->dbforge();
		$CI->dbforge->drop_database($CI->db->database);
		$CI->dbforge->create_database($CI->db->database);
		
		$CI->db->query("USE ".$CI->db->database.";");
		
		$this->run();
		return TRUE;
	}
	
	/**
	 * @access private
	 */
	private function _run_migration($file) 
	{
		## Get default CodeIgniter instance
		$CI =& get_instance();
		
		## Load up the migration xml
		$migration = new SimpleXMLElement(read_file(APPPATH.'migrations/'.$file));
		
		## Are there tasks in the migration file?
		if (!empty($migration->tasks)) {
			
			foreach($migration->tasks->task as $task) {
				
				if (in_array($task->action, $this->allowed_actions)) {
					$f_task = "_".$task->action;
					$this->$f_task($task);
				} else {
					$this->_show_message("Action '".$t->task->action."' is not a valid action.");
					return FALSE;
				}
				
				echo "\n";
				
			}
			
		}
		
		## Create record in schema_migrations table
		$CI->db->insert('schema_migrations', array("filename" => $file));
		
		return TRUE;
	}
	
	/**
	 * @access private
	 */
	private function _create_table($task)
	{
		$CI =& get_instance();
		$CI->load->dbforge();
		
		## Check for a table name
		if (empty($task->table_name)) {
			$this->_show_message("**ERROR** Your table must have a 'table_name' property.");
			return FALSE;
		}
		
		## Remove the table from the database first
		if ($CI->db->table_exists($task->table_name)) {
			$CI->dbforge->drop_table($task->table_name);
		}
		
		$this->_show_message("Creating table '".$task->table_name."'...");
		
		## Always create the id field for every table - web developers are sloppy with databases
		$CI->dbforge->add_field("id INT NOT NULL PRIMARY KEY AUTO_INCREMENT");
		
		## Loop through all fields in migration and add to db->create_table
		foreach($task->fields->field as $field) {
			$this->_append_field($field,&$CI);
		}
		
		$this->_append_default_fields(&$CI);
		$CI->dbforge->create_table($task->table_name, TRUE);
		
		return TRUE;
	}
	
	/**
	 * @access private
	 */
	private function _drop_table($task)
	{
		$CI =& get_instance();
		$CI->load->dbforge();
		
		$this->_show_message("Dropping table '".$task->table_name."'...");
		
		## Remove the table from the database first
		if (!empty($task->table_name) && $CI->db->table_exists($task->table_name)) {
			$CI->dbforge->drop_table($task->table_name);
		} else {
			$this->_show_message("**ERROR** Could not drop table '".$task->table_name."'...");
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * This function will add a column to the specified table in the migration task.
	 * You may only pass in 1 column at a time to this action. 
	 *
	 * @access private
	 */
	private function _add_column($task)
	{	
		$CI =& get_instance();
		$CI->load->dbforge();
		
		if (empty($task->field->name)) {
			$this->_show_message("**ERROR** You must supply a 'name' property for a 'field' when running the 'add_column' action.");
			return FALSE;
		}
		
		$this->_show_message("Adding column '".$task->field->name."' to table '".$task->table_name."'...");
		
		if (empty($task->table_name) || !$CI->db->table_exists($task->table_name)) {
			$this->_show_message("**ERROR** You must supply a 'table_name' property for the 'add_column' action.");
			return FALSE;
		}
		
		$field_spec = $this->_append_field($task->field, &$CI, TRUE);
		$CI->dbforge->add_column($task->table_name, $field_spec);
		
		return TRUE;
	}
	
	/**
	 * TODO: Need to force the ONE FIELD ONLY requirement with this function.
	 *
	 * This function will allow you to change a column's definition based on the field 
	 * name and table name supplied in the migration. You may only pass in 1 column at a 
	 * time to this action.
	 *
	 * @access private
	 */
	private function _change_column($task)
	{
		return TRUE;
	}
	
	/**
	 * This function will allow you to rename a column based on the existing field name 
	 * and new field name properties in your migration. You may only change 1 column at a 
	 * time with this function. 
	 *
	 * @access private
	 */
	private function _rename_column($task)
	{
		return TRUE;
	}
	
	/**
	 * This function will allow you to remove a column based on the field name and table 
	 * name supplied in your migration. You may only remove 1 column from a table at a time.
	 *
	 * @access private
	 * @return boolean
	 */
	private function _remove_column($task)
	{
		$CI =& get_instance();
		$CI->load->dbforge();
		
		if (empty($task->table_name) || !$CI->db->table_exists($task->table_name)) {
			$this->_show_message("**ERROR** You must supply a 'table_name' property for the 'remove_column' action.");
			return FALSE;
		}
		
		if (!$this->_field_exists($task->table_name, $task->field->name)) {
			$this->_show_message("**ERROR** The field '".$task->field->name."' does not exist in table '".$task->table_name."'.");
			return FALSE;
		}
		
		$this->_show_message("Removing column '".$task->field->name."' from table '".$task->table_name."'...");
		$CI->dbforge->drop_column(strtolower($task->table_name), strtolower($task->field->name));
		
		return TRUE;
	}
	
	/**
	 * This function will allow you to add an index to the table supplied in your migration.
	 *
	 * @access private
	 * @return boolean
	 */
	private function _add_index($task)
	{
		return TRUE;
	}
	
	/**
	 * This function will allow you to remove an index on the table supplied in your migration.
	 *
	 * @access private
	 * @return boolean
	 */
	private function _remove_index($task)
	{
		return TRUE;
	}
	
	/**
	 * This is an internal function that builds field definitions for use with create_table and 
	 * add_column.
	 *
	 * @access private
	 * @return boolean
	 */
	private function _append_field($field, $instance, $return = FALSE)
	{
		$field_spec = "";
		
		if ($return) {
			$_field_name = (string) $field->name;
			$return_array = array();
			$return_array[$_field_name] = array();
		}
		
		## Check for a field name
		if (!empty($field->name)) {
			$field_spec .= $field->name." ";
		} else {
			$this->_show_message("Every field must have a 'name' property.");
			return FALSE;
		}
		
		## Check for a field type
		if (!empty($field->type)) {
			switch($field->type) {
				case "integer":
					$field_type = "INT";
					break;
				case "string":
					$field_type = "VARCHAR(255)";
					break;
				case "boolean":
					$field_type = "TINYINT(1)";
					break;
				default:
					$this->_show_message("Invalid field type (".$field->type.")");
					return FALSE;
			}
			
			$field_spec .= $field_type." ";
			
			if ($return) {
				switch($field_type) {
					case "VARCHAR(255)":
						$return_array[$_field_name]["type"] = "VARCHAR";
						$return_array[$_field_name]["constraint"] = 255;
						break;
					case "INT":
						$return_array[$_field_name]["type"] = "INT";
						break;
					case "TINYINT(1)":
						$return_array[$_field_name]["type"] = "TINYINT";
						$return_array[$_field_name]["constraint"] = 1;
						break;
				}
			}
			
		} else {
			$this->_show_message("Every field must have a 'type' property.");
			return FALSE;
		}
		
		## Check for nullability
		if (!empty($field->null) && strtolower($field->null) == "yes") {
			$field_spec .= "NULL ";
			
			if ($return) {
				$return_array[$_field_name]["null"] = TRUE;
			}
		} else {
			$field_spec .= "NOT NULL ";
		}
		
		## Check for primary key
		if (!empty($field->key) && strtolower($field->key) == "primary") {
			$instance->dbforge->add_key($field->name,TRUE);
		}
		
		## Check for auto increment
		if (!empty($field->auto) && strtolower($field->auto) == "yes") {
			$field_spec .= "AUTO_INCREMENT ";
			
			if ($return) {
				$return_array[$_field_name]["auto_increment"] = TRUE;
			}
		}
		
		## Check for default value
		if (!empty($field->default)) {
			switch($field->type) {
				case "integer":
				case "boolean":
					$field_spec .= "DEFAULT ".$field->default." ";
					break;
				case "string":
					$field_type = "DEFAULT '".$field->default."' ";
					break;
				default:
					$this->_show_message("Can't set default because field type was not set.");
					return FALSE;
			}
			
			if ($return) {
				$return_array[$_field_name]["default"] = $field->default;
			}
		}
		
		$this->_show_message("--Adding field ".$field->name."...");
		
		## Add the field
		if ($return) {
			return $return_array;
		} else {
			$instance->dbforge->add_field($field_spec);
			return TRUE;
		}
	}
	
	/**
	 * This function checks to see if the "schema_migrations" table exists in your database, 
	 * if not, it creates the table. This table is used when running your migrations to map 
	 * what files in application/migrations have been run against the currently connected 
	 * database.
	 * 
	 * @return boolean
	 * @access private
	 */
	private function _check_schema_migrations() 
	{
		$CI =& get_instance();
		
		if (!$CI->db->table_exists('schema_migrations')) {
			$this->_show_message("\nCreating schema_migrations table...");
			$CI->load->dbforge();
			$CI->dbforge->add_field("filename VARCHAR(255) NOT NULL DEFAULT ''");
			$this->_append_default_fields(&$CI);
			$CI->dbforge->create_table("schema_migrations", TRUE);
		}
		
		return TRUE;
	}
	
	/**
	 * This function is used when creating tables. It will append the default "id", "created_at" 
	 * and "updated_at" fields to the end of each table. These fields are used in conjunction 
	 * with the extended MY_Model class to keep track of when each row was created and if/when 
	 * it was updated.
	 * 
	 * @return boolean
	 * @access private
	 */
	private function _append_default_fields($instance)
	{
		$this->_show_message("--Adding default fields...");
		$instance->dbforge->add_field("created_at DATETIME");
		$instance->dbforge->add_field("updated_at DATETIME");
		return TRUE;
	}
	
	/**
	 * This function checks to see if a field exists in a table.
	 *
	 * @access private
	 * @return boolean
	 */
	private function _field_exists($table,$field) {
		$CI =& get_instance();
		$CI->load->dbforge();
		
		$query = $CI->db->get_where("information_schema.COLUMNS", array("TABLE_SCHEMA" => $CI->db->database, "TABLE_NAME" => "'".$table."'", "COLUMN_NAME" => "'".$field."'"));
		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * This function is used to display messages to the screen during migrations. 
	 * 
	 * @access private
	 */
	private function _show_message($message) 
	{
		echo $message."\n";
	}
	
}

?>