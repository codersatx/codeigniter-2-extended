<?php

$CI->load->model('Blog');

$blog = new Blog();
$blog_id = $blog->create(array("category_id" => 1, "title" => "testing"));

// $blog = new Blog($blog_id);

print_r($blog);

?>