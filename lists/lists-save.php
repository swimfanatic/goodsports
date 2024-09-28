<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1); // E_ALL


echo "Returning to previous page! Results trying to save.\n\n\n";
echo "<hr>";


var_dump($_POST);

if (isset($_POST['d00t']) && $_POST['d00t'] != "") {
	file_put_contents("d00t.txt"  , $_POST['d00t']);
}
if (isset($_POST['j']) && $_POST['j'] != "") {
	file_put_contents("j.txt"     , $_POST['j']);
}
if (isset($_POST['jlaw']) && $_POST['jlaw'] != "") {
	file_put_contents("jlaw.txt"  , $_POST['jlaw']);
}
if (isset($_POST['zombl']) && $_POST['zombl'] != "") {
	file_put_contents("zombl.txt" , $_POST['zombl']);
}
if (isset($_POST['twenty']) && $_POST['twenty'] != "") {
	file_put_contents("twenty.txt", $_POST['twenty']);
}
if (isset($_POST['dom']) && $_POST['dom'] != "") {
	file_put_contents("dom.txt"   , $_POST['dom']);
}


?>

<script>

setTimeout(function() {
	window.history.back();
}, 1250);

</script>

</script>