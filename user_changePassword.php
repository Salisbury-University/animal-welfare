<?php
include_once("Templates/header.php");

    // Keep recovery accounts off this page.
    // Had trouble with redirecting the user to a different page
    // This solution is acceptable aswell since the button to get to this page is hidden.
if(isset($_SESSION['isRecoveryAccount']) == 1){
    exit();
}

?>

<link href="CSS/admin.css" rel="stylesheet">

    <!--Only edit main-->
    <main>
      <?php
      
      ?>

        <!--Start HTML-->
    <div class = "my-container" style="border:5px solid #000000;"">
        <h1>Change Password Form </h1>
  
        <form action='Admin/changePassword.php' method='post'>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type='text' class="form-control" name='email' placeholder=<?php echo $_SESSION['email']; ?> readonly>
                <input type='hidden' value='<?php echo $_SESSION['email']; ?>' name='email'>
            </div>
            <div class="form-group">
                <label for="password">Type new password</label>
                <input type="text" class="form-control" name='password' placeholder="Enter Password">
            </div>
            <div class="form-group">
                <label for="password">Retype new password</label>
                <input type="text" class="form-control" name='retypedPassword' placeholder="Retype Password to confirm">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <br>
        <?php include "userChangePasswordError.php"; ?>
        </div>
        <!--End HTML-->
    </main>

<?php
include_once("Templates/footer.php");
?>