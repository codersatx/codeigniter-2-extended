<?php $this->load->view('common/header', $this->data); ?>

<h2>Install Codeigniter 2 - Tweaked</h2>
<ul>
	<li>Navigate to the application/config folder. Find and open the file "database.example.php".</li>
	<li>Create a copy of "database.example.php" and name it "database.php".</li>
	<li>
		Modify (at least) the following fields with the proper database connection information:
		<code>
			$db['default']['hostname'] = "127.0.0.1";<br />
			$db['default']['username'] = "root";<br />
			$db['default']['password'] = "";<br />
			$db['default']['database'] = "";
		</code>
		<strong>Tip:</strong> if you aren't going to utilize a database, just create a blank database.php file with opening/closing PHP tags.
	</li>
	<li>
		Open the file "autoload.php" and modify the "libraries" section:
		<code>$autoload['libraries'] = array('database','migration');</code>
	</li>
	<li>Next, open up a terminal (I'm assuming you're on a Mac or some other Unix flavored machine).</li>
	<li>
		From the root directory, run the following command:
		<code>$ sudo php install/install.php</code>
	</li>
	<li>
		You should see the following line (and you should follow it's directions as well):
		<code>
			Installation complete!<br />
			- Add the following directory to your PATH to use phake: /usr/bin/phake
		</code>
	</li>
	<li>Once all of that is done, you're all set...<a href="/" title="launch the home page">launch the home page</a>!</li>
</ul>

<h2>Sum-ting-wong?</h2>
<p>If you've clicked on the "launch the home page" link and you still get back to this page, you did something wrong. Run through the installation steps again. If you still can't figure it out, email me - beau.frusetta *AT* gmail *DOT* com.</p>

<?php $this->load->view('common/footer', $this->data); ?>