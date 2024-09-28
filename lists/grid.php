<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1); // E_ALL
?>
<!DOCTYPE html>
<html>
<head>
    <title>Good Sports PR Grid</title>
    <meta charset="UTF-8">
    <meta name="description" content="Good Sports List Save">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/w3-4.css"><!-- https://www.w3schools.com/w3css/4/w3.css -->
    <link rel="stylesheet" href="/css/fonts-googleapis-raleway.css"><!-- https://fonts.googleapis.com/css?family=Raleway -->
<style>
* {
	box-sizing: border-box;
}
.hidden {
	display: none;	
}
.cell-single {
	border: 2px solid red;
}
.grid-table {
	border-collapse: collapse;
}
.grid-table img {
	max-width: 1.5em;
	max-height: 1.5em;
}
.grid-table .grid-table-text {
	padding: 0 1.5em 0 0.25em;
}
.grid-table .grid-table-rank {
	min-width: 2.0em;
	text-align: center;
	padding: 0.25em;
	position: relative; /* Required for absolute positioning of the tooltip */
}
#tooltip {
	position: absolute;
	background-color: rgba(0, 0, 0, 0.7);
	color: white;
	padding: 5px;
	border-radius: 5px;
	z-index: 1000; /* Ensure it appears above other elements */
}
</style>
</head>
<body>
	
<header>
	<?php include($_SERVER['DOCUMENT_ROOT'].'/header.html'); ?>
</header>

<?php


$lists = ["d00t.txt", "j.txt", "jlaw.txt", "twenty.txt", "zombl.txt", "dom.txt"];
$rankerCount = count($lists);

$pr = [];
$teamRanking = [];

$teamAbbr = [
	"Cardinals" => "ari",
	"Falcons" => "atl",
	"Ravens" => "bal",
	"Bills" => "buf",
	"Panthers" => "car",
	"Bears" => "chi",
	"Bengals" => "cin",
	"Browns" => "cle",
	"Cowboys" => "dal",
	"Broncos" => "den",
	"Lions" => "det",
	"Packers" => "gb",
	"Texans" => "hou",
	"Colts" => "ind",
	"Jaguars" => "jac",
	"Chiefs" => "kc",
	"Chargers" => "lac",
	"Rams" => "lar",
	"Raiders" => "lv",
	"Dolphins" => "mia",
	"Vikings" => "min",
	"Patriots" => "ne",
	"Saints" => "no",
	"Giants" => "nyg",
	"Jets" => "nyj",
	"Eagles" => "phi",
	"Steelers" => "pit",
	"Seahawks" => "sea",
	"49ers" => "sf",
	"Bucs" => "tb",
	"Buccaneers" => "tb",
	"Titans" => "ten",
	"Commanders" => "was"
];
?>

<?php

function getPowerRanking($lists) {

	$pr = [];

	foreach ($lists as $filename) {
		$filenameUser = explode(".", $filename)[0];
		foreach(file($filename) as $line) {
			$team = trim(explode("\t", $line)[0]);
			$rank = trim(explode("\t", $line)[1]);
			if (array_key_exists($team, $pr)) {
				$pr[$team][$rank][] = $filenameUser;
				// array_push()
			}
			else {
				// array_push($pr, array($rank => $filenameUser));
				// $pr[$team] = [$rank => $filenameUser];
				$pr[$team][$rank] = [$filenameUser];
			}
		}
	}

	return $pr;
}


function getTeamRankings($pr, $rankerCount) {
	$tmp = [];

	foreach($pr as $team => $rankings) {
		$avg = 0;
		foreach($rankings as $rank => $rankArray) {
			$avg += $rank * count($rankArray);
		}
		$avg = $avg / $rankerCount;
		$tmp[$team] = $avg;
	}

	return $tmp;
}

$pr = getPowerRanking($lists);
$teamRanking = getTeamRankings($pr, $rankerCount);
asort($teamRanking);

// echo "<pre>";
// print_r($teamRanking);
// echo "</pre>";

if (count($pr) != 32) {
	echo "<pre>";
	echo "NOT exactly 32 teams found!\n\n";
	print_r($pr);
	echo "</pre>";
	die("NOT exactly 32 teams found!");
}


function getColorInGradient($value, $min = 1, $max = 32) {
    // Normalize the value to a range of 0 to 1
    $normalized = ($value - $min) / ($max - $min);

    // Determine the color components
    if ($normalized <= 0.5) {
        // From green (0, 255, 0) to yellow (255, 255, 0)
        $r = intval(255 * ($normalized * 2)); // Red increases from 0 to 255
        $g = 255;                             // Green stays at 255
        $b = 0;                               // Blue stays at 0
    } else {
        // From yellow (255, 255, 0) to red (255, 0, 0)
        $r = 255;                             // Red stays at 255
        $g = intval(255 * (1 - ($normalized - 0.5) * 2)); // Green decreases from 255 to 0
        $b = 0;                               // Blue stays at 0
    }
    
    // return sprintf("#%02x%02x%02x", $r, $g, $b); // Return hex color
	return ($r .",". $g .",". $b .",". 0.7);
}


echo "<table class='grid-table'>";
for ($x = 0; $x < 32; $x++) {
	$team = array_keys($teamRanking)[$x];
	echo "<tr>";
	echo "<td><img src='/images/" . $teamAbbr[$team] . ".png'></td>";
	echo "<td class='grid-table-text'>$team</td>";
	echo "<td class='grid-table-text' style='background-color: rgba(" . getColorInGradient(round($teamRanking[$team], 2)) . ")'>" . round($teamRanking[$team], 2) . "</td>";
	echo "<td style='width: 0.99em'></td>";
	for ($y = 1; $y < 33; $y++) {
		$b = 0;
		$single = false;
		$tooltipText = "";
		
		if (isset($pr[$team][$y])) {
			$b = count($pr[$team][$y]) / $rankerCount;
			$teamMin = min(array_keys($pr[$team]));
			$teamMax = max(array_keys($pr[$team]));
			if (count($pr[$team][$y]) == 1 && $y == $teamMin ||
				count($pr[$team][$y]) == 1 && $y == $teamMax ) {
				$b = 0;
				$single = true;
			}

			$tooltipText = implode(", " , $pr[$team][$y]);
		}

		// Color correction. Add more highlights to weak numbers (1's stand out more)
		if ($b != 0) {
			$b =  pow($b, 0.85);
		}

		echo "<td title='$tooltipText' data-tooltip='$tooltipText' class='grid-table-rank " . (($single) ? "cell-single" : "") . "' style='background-color: rgba(" . 255 . ", " . 160 . ", " . 0 . ", " . ($b) . ")'>" 
			. $y . "</td>";
	}
	echo "</tr>";
}
echo "</table>";

?>

<div class="hidden" id="tooltip"></div>

<script>

const tooltip = document.querySelector('#tooltip');
const cells = document.querySelectorAll('.grid-table-rank');

cells.forEach(cell => {
  cell.addEventListener('click', function (event) {
    const tooltipText = this.getAttribute('data-tooltip');

    // Set tooltip text and position
    tooltip.textContent = tooltipText;
    tooltip.style.left = `${event.pageX}px`;
    tooltip.style.top = `${event.pageY}px`;

	tooltip.classList.remove("hidden");

    // Hide the tooltip when clicking elsewhere
    document.addEventListener('click', function hideTooltip(e) {
      if (!cell.contains(e.target)) {
		tooltip.classList.add("hidden");
        document.removeEventListener('click', hideTooltip);
      }
    });
  });
});

</script>

</body>
</html>
