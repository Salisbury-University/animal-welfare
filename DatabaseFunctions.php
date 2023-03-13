<?php

##REUSABLE QUERY FUNCTION##

function query($conn, $sql, $parameters = []) {
	$query = $conn->prepare($sql);
	$query->execute($parameters);
	return $query;
}

##Returns all tuples from Forms##
function get($conn) {
	$forms = query($conn, 'SELECT * FROM Forms');
	return $forms->mysqli_fetch_all(MYSQLI_ASSOC);
}

?>