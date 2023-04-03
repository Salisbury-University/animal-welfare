<?php

##REUSABLE QUERY FUNCTION##

function query($connection, $sql, $parameters = []) {
	$query = mysqli_query($connection, $sql, $parameters = []);
	return $query;
}

##QUESTION FUNCTIONS##

function getQ($connection, $id) {
	$parameters = [':id' => $id];
	$query = query($connection, 'SELECT * FROM `questions` WHERE id = :id', $parameters);
	return $query->fetch();
}

function insertQ($connection, $text) {
	$query = 'INSERT INTO `questions` (question)
	VALUES (:question)';
	$parameters = [':question' => $text];
	query($connection, $query, $parameters);
}

function updateQ($connection, $id, $text) {
	$query = 'UPDATE `questions` SET question = :question WHERE id = :id';
	$parameters = [':question' => $text, ':id' => $id];
	query($connection, $query, $parameters);
}

function deleteQ($connection, $id) {
	$parameters = [':id' => $id];
	query($connection, 'DELETE FROM `questions` WHERE id = :id', $parameters);
}
