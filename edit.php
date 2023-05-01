<?php
//in isset() insert the question into the questions
$insertIgnore = "INSERT IGNORE INTO questions ('id', 'text') VALUES (, $_POST['text']);";
				
//select autoincrementedid will return to $question_id and $form_id
$getlastID = "SELECT LAST_INSERT_ID();";

//insert the association pair into $form_id is the new form id - auto inc then select
$insert = "INSERT IGNORE INTO hasSectionQuestions ('id','form_id', 'section_id', 'question_id') VALUES (,"$form_id", "$section_id","$question_id");" 

//this is run after the insert into hsq to clear out unused values
$kill = "DELETE
		FROM hasSectionQuestions hsq
		WHERE "$old_form_id" = hsq.form_id
		
		DELETE
		FROM questions q 
		WHERE NOT EXISTS (
			SELECT NULL 
			FROM hasSectionQuestions hsq
			WHERE hsq.question_id = q.id
		)
		
		DELETE
		FROM sections s
		WHERE NOT EXISTS (
			SELECT NULL 
			FROM hasSectionQuestions hsq
			WHERE hsq.section_id = s.id
		)
		
		DELETE
		FROM forms f
		WHERE NOT EXISTS (
			SELECT NULL
			FROM hasSectionQuestions hsq
			WHERE hsq.form_id = form_id
		);";

ob_start();
include 'edit.html';
$output = ob_get_clean();
include 'header.php';
