<?php $this->load->view('common/header', $this->data); ?>

<h2>Codeigniter &amp; Phake</h2>
<p>Being a developer that works in both PHP &amp; Rails on a daily basis, I tend to lean more towards Rails when choosing a framework for new projects. Why? Well...because it's just that much faster to create an application.</p>

<h2>Phake Tasks?</h2>
<p>A big part of Rails are things called "rake tasks". These are tasks that you can run from the command line, in a cron job, or a capistrano deployment script. They are very handy. Phake brings this to life, but there had to be some proper hacking to gain access to all your Codeigniter goodies w/o using the HTTP layer.</p>

<h2>Gaining Access to your Codeigniter Instance</h2>
<p>I basically started breaking the scripts down. You first route through index.php, include some files, create some basic libraries, and then include in system/core/Codeigniter.php. This is where all the magic happens.</p>
<p>If you've ever worked with Codeigniter, you've probably had to grab the instance via "get_instance()" in your models every once in a while - and you know that instance gives you access to everything that you've built into your site.</p>
<p>In order to make these "phake tasks" work just like "rake tasks", I had to get access to that - enter "external.php".</p>

<h2>External Access to Codeigniter</h2>
<p>I'm not going to go in to detail about external.php, because honestly, I don't even really know how I did it, I just know that it works, and for right now, that's good enough for me.</p>
<p>I now use this file in conjunction with my "phake tasks" to run tasks just like you would in a Ruby on Rails project:</p>
<code>$ phake db:migrate</code>

<h2>Phake</h2>
<p>After a little bit of searching around the internet, I found <a href="https://github.com/jaz303/phake" title="jaz303's phake implementation" target="_blank">jaz303's "phake" implementation</a>.</p>
<p>Pretty standard stuff - you have a "Phakefile" that sits in the root of your project, you install the library (its included as a part of the <a href="/install">installation</a>), map your PATH, and you're off! If you look at the "Phakefile" in the root directory of this project, you'll see how easy it is to setup and work with it. There are also quite a few examples listed in the readme on the github page for phake.</p>

<?php $this->load->view('common/footer', $this->data); ?>