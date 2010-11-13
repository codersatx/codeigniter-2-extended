<?php $this->load->view('common/header', $this->data); ?>

<h2>Modifications/Additions</h2>
<p>Below are some of the first modifications I made to the Codeigniter 2 framework. There's more to come...really...I put them in a roadmap!</p>

<h2>Extending the Core</h2>
<ul>
	<li>
		<strong>application/core/MY_Model</strong> - this is a class that sits on top of the CI_Model class to give Codeigniter a more "Ruby on Rails" feel. All models should extend from this class to take advantage of the additional functions below.
		<ul>
			<li><strong>$this->model_name->find()</strong></li>
			<li><strong>$this->model_name->all()</strong></li>
			<li><strong>$this->model_name->create()</strong></li>
			<li><strong>$this->model_name->destroy()</strong></li>
		</ul>
	</li>
	
	<li>
		<strong>application/core/MY_Controller</strong>
		<ul>
			<li><strong>$this->my_controller->is_installed()</strong> - this function was created to check for a proper installation of this modified framework</li>
		</ul>
	</li>
	
	<li>
		<strong>application/core/MY_Config</strong>
		<ul>
			<li><strong>$this->config->site_url()</strong> - this function was overridden to remove index.php from the URL's when using the URL helpers.</li>
		</ul>
	</li>
</ul>

<h2>Custom Libraries</h2>

<h3>Migration</h3>
<p>I honestly think that this is the best part of this whole "tweaking" process. All of these tasks are wrapped up in phake tasks for right now, but they can be used anywhere in the codebase as long as it's loaded/included in the autoloader.</p>
<ul>
	<li>
		<strong>application/libraries/Migration</strong>
		<ul>
			<li>
				<strong>$this->migration->create_migration()</strong> - used to create new migration files in application/migrations folder
				<code>$ phake db:create_migration</code>
			</li>
			<li>
				<strong>$this->migration->run()</strong> - used to run all new migrations in the application/migrations folder
				<code>$ phake db:migrate</code>
			</li>
			<li>
				<strong>$this->migration->reset()</strong> - used to completely reset the database and run all migrations from the first file to the most recent
				<code>$ phake db:reset</code>
			</li>
		</ul>
	</li>
</ul>
<p>Right now, I haven't spent enough time messing with phake to figure out how I can pass variables in to the tasks, but I'm working on it!</p>
<p>There is a whole section on <a href="/codeigniter-and-phake">Codeigniter &amp; Phake</a> - you should check it out - it'll make your life that much better.</p>

<h3>Paypal</h3>
<ul>
	<li>
		<strong>application/libraries/Paypal</strong> - this is a class that is used to process PayPal payments.
		<ul><li>Coming soon.</li></ul>
	</li>
</ul>

<h3>Yelp</h3>
<ul>
	<li>
		<strong>application/libraries/Yelp</strong> - this is a class that interacts with the Yelp API (2.0).
		<ul><li>Coming soon.</li></ul>
	</li>
</ul>

<h3>Google</h3>
<ul>
	<li>
		<strong>application/libraries/Google</strong> - this is a class that interacts with various Google API's.
		<ul><li>Coming soon.</li></ul>
	</li>
</ul>

<h3>Scraper</h3>
<ul>
	<li>
		<strong>application/libraries/Scraper</strong> - this is a class that will scrape specific data from web pages.
		<ul><li>Coming soon.</li></ul>
	</li>
</ul>

<?php $this->load->view('common/footer', $this->data); ?>