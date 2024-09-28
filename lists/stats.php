<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1); // E_ALL
?>
<!DOCTYPE html>
<html>
<head>
    <title>Good Sports Stats</title>
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
// print_r($pr);
// print_r($teamRanking);
// echo "</pre>";

if (count($pr) != 32) {
	echo "<pre>";
	echo "NOT exactly 32 teams found!\n\n";
	print_r($pr);
	echo "</pre>";
	die("NOT exactly 32 teams found!");
}

function findUserIndex($array, $user) {

	// print_r($array);

	foreach ($array as $index => $playerList) {
		if (in_array($user, $playerList)) {
			// print_r($index);
			return $index; // Return the index of the array where the user is found
		}
	}

    // foreach ($array as $team => $players) {
    //     foreach ($players as $index => $playerList) {
    //         if (in_array($user, $playerList)) {
    //             return $index; // Return the index of the array where the user is found
    //         }
    //     }
    // }
    return null; // Return null if the user is not found
}

function getRankerDelta($pr, $lists, $teamRanking) {
	$tmp = [];
	foreach($lists as $filename) {
		$filenameUser = explode(".", $filename)[0];
		$tmp[$filenameUser] = 0;
		foreach($teamRanking as $team => $prRank) {
			$userRanking = findUserIndex($pr[$team], $filenameUser);
			// print($userRanking . " // " . $prRank . " // " . abs($userRanking - $prRank)  . " // " . $tmp[$filenameUser] . "\n" );
			$tmp[$filenameUser] += abs($userRanking - $prRank);
		}
		$tmp[$filenameUser] = round($tmp[$filenameUser], 2);
	}
	return $tmp;
}

$deltas = getRankerDelta($pr, $lists, $teamRanking);
asort($deltas);

echo "<pre>";

// print_r($deltas);

print "<h4>Group Thought</h4>";
print "<div style='margin-left: 2.5em'>";
print "<p>Most Basic</p>";
print "<table style='margin-left: 1.75em'>";
foreach($deltas as $user => $delta) {
	// echo "<p style='margin-left: 1.25em'>" . $user . "</p>";
	print "<tr><td>$user</td><td>$delta</td></tr>";
}
print "</table>";
print "<p>Most Wild</p>";
print "</div>";

echo "</pre>";
?>

</body>
</html>
