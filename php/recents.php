<?php
require("functions.php");
$file = "24c3f21f5e2eccb.txt";
echo file_get_contents("../data/" . $file, FILE_USE_INCLUDE_PATH);
?>