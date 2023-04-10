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
    echo "<p>". $row["title"] ."</p>";
    $posts[count][0] = $row;
    $count++;
}
##works^

##Initializes questions variable with all questions and ids
for ($i = 0; $i < count; $i++) {
    $sql = "SELECT q.question, hsq.id
        from questions q
        join hasSectionQuestions hsq on q.id = hsq.question_id
        where hsq.section_id = ". $i['id'] ."and hsq.form_id = ". $formId . ";";
    $questions = mysqli_query($connection, $sql);

    #Loads question from section into col1+ of array
    $x = 1;
    while ($col = mysqli_fetch_array($sections)) {
        $post[$i][x] = $col;
        $x++;
    }
}

##Printing
for ($row = 0; $row <2; $row++) {
    echo "<p> Row number ".$posts[$row["title"]][0]."</p>";
    for ($col = 1; $col < 3; $col++) {
        echo "<li>".$posts[$row][$col["question"]]."</li>";
    }
}

#Include html
ob_start();
include 'entry.html';
$output = ob_get_clean();

##Needed for styling
include 'page_frame.php';
?>