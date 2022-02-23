<?php

function grad_defense_connection() {
    return mysqli_connect("localhost","defenseview","5Yu#TBQsmgvRkkN","graddef");
}

function grad_defense_start_date($year = null) {
	if ($year) {
		return "$year-01-01 00:00:00";
	}

    return date( "Y-m-d 00:00:00", time() );
}

function grad_defense_end_date($year = null) {
	if ($year) {
		return "$year-12-31 23:59:00";
	} else {
		return false;
	}

}

function get_grad_defenses( $connection, $start, $end = null ) {
	if ($end) {
		mysqli_query($connection,"SELECT * FROM `submissions` WHERE Approved = 'Yes' AND Date < '$end' AND Date > '$start' ORDER BY Date desc");
	} else {
		return mysqli_query($connection,"SELECT * FROM `submissions` WHERE Approved = 'Yes' AND Date >= '$start' ORDER BY Date asc");
	}
}

function grad_defenses_build_array( $result ) {
    $submissionArray = [];

    while( $row = mysqli_fetch_assoc($result) ) {
        $defenseDate = date( "M j Y", strtotime( $row['date'] ) );

        $submissionArray[$defenseDate][] = array(
            'ID' => $row['ID'],
            'date' => $row['date'],
            'department' => $row['department'],
            'fname' => $row['fname'],
            'lname' => $row['lname'],
        );
    }

    return $submissionArray;
}


function grad_defense_year(string $slug) {
	$slugArray = explode('-', $slug);
	$year = array_pop($slugArray);

	if ( is_numeric($year) ) {
		return $year;
	} else {
		return false;
	}
}
