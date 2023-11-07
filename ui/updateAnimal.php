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
    //Get ID:
    $zim = $_GET['id'];
    $query = 'SELECT * FROM `animals` WHERE `id` = ?';
    $animals = $user->getDatabase()->runParameterizedQuery($query, "i", array($zim));
    $animal = $result->fetch_array(MYSQLI_ASSOC);

    $query = 'SELECT DISTINCT `section` FROM `animals` ORDER BY `section` ASC';
    $sections = $user->getDatabase()->runQuery_UNSAFE($query);

    ?>

    <!--Start HTML-->
    <div class="my-container" style="border:5px solid #000000;"" id = 'myTable'>
        <h1>Modify Animal</h1>
        <!--Start form-->       
        <form action='../db/_update_animal.php' method='post'>
            <!--Enter ID--> 
            <div class=" form-group">
        <label for="id">ID:</label>
        <input type='text' class="form-control" name='id' placeholder='<?php echo $zims; ?>' readonly>
        <input type='hidden' value='<?php echo $zims; ?>' name='id'>
    </div>
    <!---->

    <!--Select Location-->
    <div class="form-group">
        <label for="location">Location:</label>
        <br>

        <?php
        while ($section = $sections->fetch_array(MYSQLI_ASSOC)) {
            ?>
            <div class="row">
                <div class="col">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="location" id="location"
                            value="<?php echo $section['section']; ?>">
                        <?php echo $section['section']; ?>
                    </div>

                    <?php if ($section = $sections->fetch_array(MYSQLI_ASSOC)) { ?>
                    </div>
                    <div class="col">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="location" id="location"
                                value="<?php echo $section['section']; ?>">
                            <?php echo $section['section']; ?>
                        </div>
                    </div>

                <?php }
                    if ($section = $sections->fetch_array(MYSQLI_ASSOC)) { ?>

                    <div class="col">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="location" id="location"
                                value="<?php echo $section['section']; ?>">
                            <?php echo $section['section']; ?>
                        </div>
                    </div>
                <?php } else {
                        ?>
                    <div class="col">

                    </div>
                <?php } ?>
            </div>
        <?php } ?>
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