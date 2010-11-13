<?php $this->load->view('common/header', $this->data); ?>

<h2>Codeigniter &amp; Capistrano</h2>
<p>This project uses Capistrano for it's deployment. To use Capistrano with your Codeigniter project, complete the following steps (I'm on a Mac, so these steps are for specific to Macs):</p>
<ul>
	<li>
		Install Ruby (>=1.8.6)
		<code>$ sudo port install ruby</code>
	</li>
	<li>
		Install RubyGems
		<ul>
			<li>Download Latest Version of <a href="http://rubygems.org/pages/download" title="RubyGems">RubyGems</a></li>
			<li>Unpack &amp; go to that directory in a terminal window</li>
			<li>
				Run the following:
				<code>$ sudo ruby setup.rb</code>
			</li>
		</ul>
	</li>
	<li>
		Install Capistrano Gem
		<code>$ sudo gem install capistrano</code>
	</li>
	<li>
		Install Railsless-Deploy Gem
		<code>$ sudo gem install railsless-deploy</code>
	</li>
	<li>
		Move to the root of your directory and execute the following command:
		<code>$ capify .</code>
	</li>
	<li>
		You should now have a "Capfile" in your root directory. Open that file and put this code inside:
		<code>
			require 'rubygems'<br />
			require 'railsless-deploy'<br />
			load 'application/config/deploy.rb' if respond_to?(:namespace)
		</code>
	</li>
	<li>Running "capify ." usually creates a "config" directory, but when I ran it, it didn't, so I recommend just using the default config directory under application/config. Create a file named "deploy.rb" in the application/config directory.</li>
	<li>There are a lot of "Capistrano Recipes" out there - I've included an example one (from the one I use for this site) in the application/config folder named "deploy.example.rb".</li>
	<li>
		Once you have your deploy file created, you'll need to setup your server by running:
		<code>$ cap setup</code>
	</li>
	<li>Before you can deploy your site, you'll need to go in to the "shared" directory on your server and create the following file: application/config/database.php. Fill this file with the database settings for your server, using your local copy as an example.</li>
	<li>
		After setup, you can then do your first deployment:
		<code>$ cap deploy</code>
	</li>
	<li>You should be all good to go at this point, however, if you aren't, then leave me some comments below.</li>
</ul>

<?php $this->load->view('common/footer', $this->data); ?>