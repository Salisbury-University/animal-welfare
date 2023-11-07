<!-- <NOTE>: Changes - Switched the select location radio buttons to a dropdown. 
    Should probably consider making the MALE FEMALE selector into a radio button -->

<?php
// require_once "../vendor/autoload.php";
include_once "header.php";

// use admin\SessionUser;
require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

?>

<link href="../style/admin.css" rel="stylesheet">

<!--Only edit main-->
<main>
    <?php
    $user->checkError(); //Displays error if something is NULL
    
    //Displays Sections:
    $query = 'SELECT DISTINCT `section` FROM `animals` ORDER BY `section` ASC';
    $resultSec = $user->getDatabase()->runQuery_UNSAFE($query);

    //Displays Species:
    $query = 'SELECT DISTINCT `id` FROM `species`';
    $resultSpec = $user->getDatabase()->runQuery_UNSAFE($query);
    ?>

    <!--Start HTML-->

    <div class="my-container" style="border:5px solid #000000;"">
        <h1>Create Animal Form:</h1>
        <!--Start form-->       
        <form action='../db/_add_animal.php' method='post'>
            <!--Enter ID--> 
            <div class=" form-group">
        <label for="id">ID:</label>
        <input type='text' class="form-control" name='id' placeholder='Enter ID'>
    </div>
    <!---->

    <!--Select Location-->
    <div class="form-group">
        <label for="sex">Select Location:</label>
        <select class="form-control" name="location" id="location">
            <option value="">Select Location:</option>
            <?php
            while ($sections = $resultSec->fetch_array(MYSQLI_ASSOC)) {
                ?>
                <option value="<?php echo $sections['section']; ?>"><?php echo $sections['section']; ?></option>
                <?php
            }
            ?>
        </select>
    </div>
    <!---->


    <!--Sex Dropdown add values-->
    <div class="form-group">
        <label for="sex">Select Sex:</label>
        <select class="form-control" name="Sex">
            <option value="">Select..</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
    </div>
    <!---->

    <!--Select Birthdate-->
    <div class="form-group">
        <label for="bd">Birthdate:</label>
        <input type="text" class="form-control" name='bd' placeholder="Year-Month-Day">
    </div>
    <!---->

    <!--Select Species-->
    <div class="form-group">
        <label for="ad">Species:</label>
        <input type="text" class="form-control" name='species' placeholder="Enter Species">
    </div>
    <!---->

    <!--Form ID-->
    <div class="form-group">
        <label for="form">Select Form:</label>
        <select class="form-control" name="form">
            <option value="">Select..</option>
            <option value="1">Education Bird Welfare Assessment Form</option>
            <option value="2">Education Mammal Welfare Assessment Form</option>
            <option value="3">Education Ectotherm Welfare Assessment Form</option>
            <option value="4">Collection Bird Welfare Assessment Form</option>
            <option value="5">Collection Mammal Welfare Assessment Form</option>
            <option value="6">Collection Ectotherm Welfare Assessment Form</option>
        </select>
    </div>
    <!---->

    <!--Select Acquisition Date-->
    <div class="form-group">
        <label for="bd">Acquisition Date:</label>
        <input type="text" class="form-control" name='ad' placeholder="Year-Month-Day">
    </div>
    <!---->

    <!--Enter Name-->
    <div class="form-group">
        <label for="name">House Name:</label>
        <input type="text" class="form-control" name='name' placeholder="Enter Name">
    </div>
    <!---->

    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
    <!--End HTML-->
</main>

<?php
include_once "footer.php";
?>