<!DOCTYPE html>
<html>
<head>
    <title>Good Sports List Save</title>
    <meta charset="UTF-8">
    <meta name="description" content="Good Sports List Save">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>
.hidden {
	display: none;	
}
.flex-text-container {
	display: flex;
}
.flex-text-container h3 {
	width: max-content;
}
.flex-text-container textarea {
	min-height: 125px;
}
.flex-text-container .flex-text-single {
	display: flex;
	flex-direction: column;
	margin: 1em;
}
button {
	width: max-content;
}
</style>
</head>
<body>

<?php



?>

<form action="lists-save.php" method="post">
	<div class="flex-text-container">
		<div class="flex-text-single">
			<h3>d00t</h3>
			<button>Toggle Save</button>
			<textarea name="d00t"   placeholder="<?php echo file_get_contents("d00t.txt"); ?>"></textarea>
			<textarea class="hidden file-contents"><?php echo file_get_contents("d00t.txt"); ?></textarea>
		</div>
		<div class="flex-text-single">
			<h3>J</h3>
			<button>Toggle Save</button>
			<textarea name="j"      placeholder="<?php echo file_get_contents("j.txt"); ?>"></textarea>
			<textarea class="hidden file-contents"><?php echo file_get_contents("j.txt"); ?></textarea>
		</div>
		<div class="flex-text-single">
			<h3>JLaw</h3>
			<button>Toggle Save</button>
			<textarea name="jlaw"   placeholder="<?php echo file_get_contents("jlaw.txt"); ?>"></textarea>
			<textarea class="hidden file-contents"><?php echo file_get_contents("jlaw.txt"); ?></textarea>
		</div>
		<div class="flex-text-single">
			<h3>Zombl</h3>
			<button>Toggle Save</button>
			<textarea name="zombl"  placeholder="<?php echo file_get_contents("zombl.txt"); ?>"></textarea>
			<textarea class="hidden file-contents"><?php echo file_get_contents("zombl.txt"); ?></textarea>
		</div>
		<div class="flex-text-single">
			<h3>Twenty</h3>
			<button>Toggle Save</button>
			<textarea name="twenty" placeholder="<?php echo file_get_contents("twenty.txt"); ?>"></textarea>
			<textarea class="hidden file-contents"><?php echo file_get_contents("twenty.txt"); ?></textarea>
		</div>
		<div class="flex-text-single">
			<h3>Dom</h3>
			<button>Toggle Save</button>
			<textarea name="dom"    placeholder="<?php echo file_get_contents("dom.txt"); ?>"></textarea>
			<textarea class="hidden file-contents"><?php echo file_get_contents("dom.txt"); ?></textarea>
		</div>
	</div>

	<hr>

	<input type="submit" value="Send Lists">
</form>


</body>
</html>
