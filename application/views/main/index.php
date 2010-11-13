<?php $this->load->view('common/header', $this->data); ?>

<h2>What is different?</h2>
<p>I've been a PHP developer for a long time, and then one day, the good guys at <a href="http://integrumtech.com">Integrum</a> gave me a job as a Ruby on Rails developer. Did you know how easy Rails makes some mundane tasks? Well, if you don't, it's stupid easy to get your models/controllers/routes all setup in a few quick commands. Since I'm still a PHP developer, and I love using the Codeigniter framework, I figured I might as well start extending Codeigniter to work like Rails.</p>
<ul>
	<li><strong><a href="/codeigniter-2-modifications-additions" title="Codeigniter 2 Modifications/Additions">Codeigniter 2 Modifications/Additions</a></strong> - extended &amp; modified Model, Controller, and Config classes</li>
	<li><strong><a href="/codeigniter-and-phake" title="Codeigniter 2 &amp; Phake">Codeigniter 2 &amp; Phake</a></strong> - very "rake" like (from Rails) - meaning, you're going to get it done faster</li>
	<li><strong><a href="/codeigniter-and-capistrano" title="Codeigniter 2 &amp; Capistrano">Codeigniter 2 &amp; Capistrano</a></strong> - because if you're still deploying via FTP...you're doing it wrong</li>
</ul>
<p>My plan is to continue building on this base framework using my existing projects: (<a href="http://datedesinger.com">DateDesigner.com</a> &amp; <a href="http://lotdrifter.com">LotDrifter.com</a>).</p>
<ul>
	<li><strong><a href="/codeigniter-2-tweaked-roadmap" title="Codeigniter 2 Tweaked Roadmap">Codeigniter 2 Tweaked Roadmap</a></strong> - it's always good to have some clearly defined goals</li>
</ul>

<h2>Download/Installation</h2>
<ul>
	<li>Download <a href="https://github.com/beaufrusetta/codeigniter-2-tweaked" title="Codeigniter 2 Tweaked on GitHub">Codeigniter 2 Tweaked on GitHub</a></li>
	<li>Follow these <a href="/install" title="installation steps">installation steps</a></li>
</ul>

<h2>To-Do List</h2>
<ul>
	<li>Need to create the presence of "layouts" - and being able to set a default application layout as well as a default layout for each controller.</li>
</ul>

<h2>Codeigniter 2.0 User Guide</h2>
<p>Should you not have a copy of the user guide locally, you can view it here - <a href="/user_guide" title="Codeigniter 2.0 User Guide">Codeigniter 2.0 User Guide</a>.</p>

<?php $this->load->view('common/footer', $this->data); ?>