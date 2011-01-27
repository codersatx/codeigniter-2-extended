<?php

$CI->load->dbforge();

echo "Removing the 'blog' table...\n";
$CI->dbforge->drop_table('blogs');

echo "Remove the 'blog' model...\n";
unlink('./application/models/blog.php');

echo "Done with uninstall.\n";

?>