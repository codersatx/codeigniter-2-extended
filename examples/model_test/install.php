<?php

$CI->load->dbforge();
$CI->load->helper('file');

##
## "Model Test" install file
##

## Create test table
echo "Creating table 'blogs'...\n";
$fields = array(
	"category_id" => array(
		"type" => "INT", 
		"null" => FALSE
	), 
	"title" => array(
		"type" => "VARCHAR", 
		"constraint" => "255"
	), 
	"created_at" => array(
		"type" => "DATETIME"
	), 
	"updated_at" => array(
		"type" => "DATETIME"
	)
);
$CI->dbforge->add_field("id");
$CI->dbforge->add_field($fields);
$CI->dbforge->create_table("blogs");

echo "Creating 'blog' model...\n";
$model_file  = "<?php\n\n";
$model_file .= "class Blog extends MY_Model {\n\n";
$model_file .= "\tfunction Blog(\$id=FALSE)\n";
$model_file .= "\t{\n";
$model_file .= "\t\tparent::MY_Model(\$id, 'blogs');\n";
$model_file .= "\t}\n\n";
$model_file .= "}\n\n";
$model_file .= "?>";
write_file('./application/models/blog.php', $model_file);

echo "Creating table 'categories'...\n";
$fields = array(
	"title" => array(
		"type" => "VARCHAR", 
		"constraint" => "255"
	), 
	"created_at" => array(
		"type" => "DATETIME"
	), 
	"updated_at" => array(
		"type" => "DATETIME"
	)
);
$CI->dbforge->add_field("id");
$CI->dbforge->add_field($fields);
$CI->dbforge->create_table("categories");

echo "Done with install.\n";

?>