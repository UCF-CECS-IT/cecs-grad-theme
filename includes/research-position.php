<?php

/**
 * Checks to see if any of the filter query values have been set
 *
 * @return boolean
 */
function hasResearchPostionFilter() {

	$keyword = $_GET['keyword'] ?? false;
	$department = $_GET['department'] ?? false;
	$degree_program = $_GET['degree_programs'] ?? false;

	return ($keyword || $department || $degree_program);
}
