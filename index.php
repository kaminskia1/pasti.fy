<?php
require("php/functions.php");
?>
<html>
	<head>
		<?php module::title(); ?>
	</head>
	<body>
		<?php module::head(); ?>
		<div class='body'>
			<div class='body-header'>Welcome to Pasti.fy!</div>
			<div class='body-container'>
				<div class='new-paste'>
					<div class='new-header'>Create a new paste</div>
					<div class='new-paste-container'>
						<?php module::paste(); ?>
					</div>
				</div>
				<div class='recent-paste'>
					<div class='recent-paste-header'>Recent Pastes</div>
					<div class='recent-paste-container'>
						<?php module::recent('10'); ?>
					</div>
				</div>
			</div>
		</div>
		<?php module::footer(); ?>
	</body>
</html>