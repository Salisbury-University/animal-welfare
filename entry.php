<?php
include 'Includes/DatabaseConnection.php';

$formID = 1;

#Create 2D array
$post = array();
$post[0] = array();


##Initializes sections variable with all questions and ids
$sql = "SELECT title, hsq.id, s.id 
        from sections s 
        join hasSectionQuestions hsq on s.id =hsq.section_id
        WHERE hsq.form_id = 1;";
$sections = mysqli_query($connection, $sql);

##Counts Sections and Initializes questions
$count = 0;
while ($row = mysqli_fetch_array($sections)) {
    $posts[$count][0] = $row;
    $count++;
}


##Initializes questions variable with all questions and ids
for ($i = 0; $i < $count; $i++) {
    $x = 1; //Start at 1 to not override sections which are in x=0
    $sql = "SELECT q.question, hsq.id
        from questions q
        join hasSectionQuestions hsq on q.id = hsq.question_id
        where hsq.section_id =". ($i+1) ." and hsq.form_id = 1;";
    $questions = mysqli_query($connection, $sql);
    
    #Loads question from section into col1+ of array
    while ($col = mysqli_fetch_array($questions)) {
        $posts[$i][$x] = $col;
        $x++;
    }
}


#Include html
ob_start();
include 'entry.html';
$output = ob_get_clean();

##Needed for styling
include 'page_frame.php';
?>