<?php echo doctype('xhtml11'); ?>

<html>
<head>
	<title><?php if (!empty($page_title)) { echo $page_title; } else { echo 'Codeigniter 2.0 Installation - Tweaked'; } ?></title>
	
	<?php echo link_tag('/public/styles/application.css'); ?>
	<?php echo link_tag('/public/styles/ui-lightness/jquery-ui-1.8.6.css'); ?>
	
	<script type="text/javascript" src="/public/scripts/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="/public/scripts/jquery-ui-1.8.6.min.js"></script>
	<script type="text/javascript" src="/public/scripts/application.js"></script>
</head>
<body>
	
	<!-- START NAVIGATION --> 
	<div id="nav"><div id="nav_inner"></div></div> 
	<div id="nav2"><a name="top">&nbsp;</a></div> 
	<div id="masthead"> 
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%"> 
			<tr> 
				<td><h1>Codeigniter 2 Tweaked (Beta)</h1></td> 
			</tr> 
		</table> 
	</div> 
	<!-- END NAVIGATION -->
	
	<!-- START BREADCRUMB --> 
	<table cellpadding="0" cellspacing="0" border="0" style="width:100%"> 
		<tr> 
			<td id="breadcrumb"> 
				<a href="<?php echo base_url(); ?>">Project Home</a> &nbsp;&#8250;&nbsp;
				<a href="https://github.com/beaufrusetta/codeigniter-2-tweaked">Codeigniter 2 Tweaked (GitHub)</a> &nbsp;&#8250;&nbsp;
				<a href="http://beau.frusetta.com" title="PHP Web Developer Phoenix">Beau's Blog</a>
			</td> 
		</tr> 
	</table> 
	<!-- END BREADCRUMB --> 

	<br clear="all" />
	
	<div id="content">