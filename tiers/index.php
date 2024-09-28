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
    <meta name="description" content="Good Sports Tier List Text">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/w3-4.css"><!-- https://www.w3schools.com/w3css/4/w3.css -->
    <link rel="stylesheet" href="/css/fonts-googleapis-raleway.css"><!-- https://fonts.googleapis.com/css?family=Raleway -->
<style>
:root {
	--cell-size: 100px;
}
* {
	box-sizing: border-box;
}
.hidden {
	display: none;	
}
table .tier-list-label {
	padding: 0 1.5em;
}
table .tier-list-cell {
	display: flex;
	align-items: center; /* Centers images vertically */
	flex-wrap: wrap; /* Allows wrapping if images exceed the cell width */
	lmax-height: var(--cell-size); /* Sets the height of the cell */
	overflow: hidden;
	background-color: #eee;
}
table .tier-list-cell .img-holder {
	min-width: var(--cell-size); /* Sets the width of each image */
	min-height: var(--cell-size); /* Sets the height of each image */
	display: flex;
	object-fit: cover; /* Ensures images cover the box without distorting */
}
table .tier-list-cell img {
	max-width: var(--cell-size); /* Sets the width of each image */
	max-height: var(--cell-size); /* Sets the height of each image */
	object-fit: cover; /* Ensures images cover the box without distorting */
	margin: 0 0.5em; /* Adds spacing between images */
}
/* Mobile Only */
@media only screen and (max-width: 600px) {
	:root {
		--cell-size: 75px; /* Note: Could use calc() ubstracting the width of the 'label' left column. Then divide by 3 */
	}
}
</style>
</head>
<body>
	
<header>
	<!-- PHP Includes the header so one change is always synced across -->
	<?php include($_SERVER['DOCUMENT_ROOT'].'/header.html'); ?>
</header>

<?php

$k = 6; // Number of clusters
$optimalAttempts = 25; // How many attempts to score a better cluster? (sporadic at <10)

// Converting 0,1,... to Tier Names
$tiers = array(
	5 => ["S", "A", "B", "C", "F"],
	6 => ["S", "A", "B", "C", "D", "F"]
);

// r,g,b values for each tier
$tiersColors = array(
	5 => [],
	6 => ["127, 255, 127", "191, 255, 127", "255, 255, 127", "255, 223, 127", "255, 191, 127", "255, 127, 127"]
);

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

// Function Declarations

function assignClusters($data, $centroids) {
    $clusters = [];
    foreach ($data as $point) {
        $distances = [];
        foreach ($centroids as $centroid) {
            $distances[] = abs($point['value'] - $centroid);
        }
        $nearestCentroid = array_keys($distances, min($distances))[0];
        $clusters[$nearestCentroid][] = $point;
    }
    return $clusters;
}

function updateCentroids($clusters) {
    $newCentroids = [];
    foreach ($clusters as $points) {
        if (!empty($points)) {
            $newCentroids[] = array_sum(array_column($points, 'value')) / count($points);
        }
    }
    return $newCentroids;
}

?>

<?php

$data = [];

foreach(file("tiers.txt") as $line) { // 1.333333333	Chiefs
	$value = doubleval(explode("\t", $line)[0]);
	$team = trim(explode("\t", $line)[1]);
	$data[] = ["value" => $value, "team" => $team];
}

$clustersOptimal = [];
$clustersOptimalScore = 100000000;

for ($x = 0; $x < $optimalAttempts; $x++) {

	$maxIterations = 100;
	$iteration = 0;

	// Randomly select starting centroids, will have large impact by end
	$randomIndices = array_rand($data, $k);
	$centroids = [];
	foreach ($randomIndices as $index) {
		$centroids[] = $data[$index]['value'];
	}
	
	while ($iteration < $maxIterations) {
		$clusters = assignClusters($data, $centroids);
		$newCentroids = updateCentroids($clusters);
		
		// Check for convergence
		if ($newCentroids === $centroids) {
			break;
		}
		
		$centroids = $newCentroids;
		$iteration++;
	}

	// Calculate how 'good' a clustering is   (using midpoints)
	$score = 0;
	foreach($clusters as $index => $points) {
		//print_r(array_column($points, "value"));
		$min = PHP_INT_MAX;
		$max = 0;
		foreach($points as $point) {
			$min = min($min, $point["value"]);
			$max = min($max, $point["value"]);
		}
		$avg = ($min + $max) / 2;
		foreach($points as $point) {
			$score += abs($point["value"] - $avg);
		}
	}

	// print "score $x = " . $score . "\n";

	// Update the 'optimal' cluster using score function
	if ($score < $clustersOptimalScore) {
		$clustersOptimal = $clusters;
		$clustersOptimalScore = $score;
	}
}

?>

<div>
	<table>
		<?php

		$rank = 1;
		foreach ($clustersOptimal as $index => $teamsWithinTier) {
		?>
			
		<tr>
			<td class="tier-list-label" style="background-color: rgb(<?php echo $tiersColors[$k][$index] ;?>)"><?php echo $tiers[$k][$index]; ?></td>
			<td class="tier-list-cell">
			<?php
			foreach ($teamsWithinTier as $team) {
				echo "<div class='img-holder'><img src='/images-square/" . $teamAbbr[$team["team"]] . ".png'></img></div>";
				$rank++;
			}
			?>
			</td>
		</tr>
		
		<?php
		}

		?>
	</table>
</div>


<pre>

<?php

// Comments below give full insight to tier list making

/*

echo "Cluster Count (desired) = " . $k . "\n";
echo "\n";

echo "Clusters:\n";
foreach ($clustersOptimal as $index => $points) {
    $team = array_column($points, 'team');
    // echo "Cluster " . ($index + 1) . ": " . implode(", ", $team) . "\n";
	echo "Cluster " . ($tiers[count($clustersOptimal)][$index]) . ": " . implode(", ", $team) . "\n";
}
// echo "Final Centroids: " . implode(", ", $centroids) . "\n";

print "\n";
print "score = " . $clustersOptimalScore . "\n";
print "\n";

print_r($clustersOptimal);
*/

?>

</pre>

</body>
</html>
