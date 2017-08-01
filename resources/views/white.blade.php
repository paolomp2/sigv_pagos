<?php
	$array = array();
	for ($i=0; $i < 100; $i++) { 
		array_push($array, $i);
	}

	$array2 = array();
	$i = 0;
	foreach ($array as $row) {
		array_push($array2, $row);
	}

	dd($array2);
?>