<?php
require("php/functions.php");
$file = "24c3f21f5e2eccb.txt";
$file = fopen("data/" . $file, "r");
var_dump($file);
?>