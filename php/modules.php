<?php
require("[login functions]");
if (loggedIn()) {
	$userInfo = userInfo();
} else {
	denyAccess();
}
class main {
	public function head() { ?>
		<nav class="navbar">
			<div class="title">Pasti.fy</div>
			<ul class="navbar-container">
			<li class="navbar-tab active">Home</li>
			<li class="navbar-tab">New Paste</li>
			<li class="navbar-tab">Recents</li>
			<li class="navbar-tab">Info</li>
			</ul>
		</nav>
	<?php }
	public function footer() { ?>		
		<footer class='footer'>
			<div class='copyright'>&copy; <?php echo date("Y"); ?> - AK AppSpot</div>
		</footer>
	<?php }
	public function newPaste() {
		
	}
}
$module = new main();
