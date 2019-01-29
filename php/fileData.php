<?php
function fileToVar($file) {
	return file_get_contents("[data location]" . $file, FILE_USE_INCLUDE_PATH);
}
?>
