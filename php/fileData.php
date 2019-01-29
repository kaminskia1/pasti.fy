<?php
function fileToVar($file) {
	return file_get_contents("/var/www/vhosts/testing.cheapboost.me/httpdocs/AppSpot/app/pastify/data/" . $file, FILE_USE_INCLUDE_PATH);
}
?>