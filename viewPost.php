<?php
require("php/functions.php");
$tag = $_GET['tag'];
$key = $_GET['key'];
?>
<html>
	<head>
		<?php module::title('View Paste', 'View posts on Pastify here!'); ?>
	</head>
	<body>
		<?php module::head(); ?>
		<div class='body'>
			<div class='body-subheader'>View Paste</div>
			<div class='body-container'>
				<?php module::view($tag, $key); ?>
			</div>
		</div>
		<?php module::footer(); ?>
	</body>
</html>