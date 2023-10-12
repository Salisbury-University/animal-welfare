<?php
include_once("../../ui/model/header.php");

$isAdmin = checkIsAdmin();
if($isAdmin == false){
  header('Location: ../../ui/home.php');
}

?>

<link href="../../style/admin.css" rel="stylesheet">

    <!--Only edit main-->
    <main>
      <?php
      
      ?>

        <!--Start HTML-->
    <div class = "my-container" style="border:5px solid #000000;"">
        <h1>Create User Form: </h1>
  
        <form action='../addUser.php' method='post'>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type='text' class="form-control" name='email' placeholder="Enter Email Address">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" class="form-control" name='password' placeholder="Enter Password">
            </div>
            <div class="form-group">
                <label for="admin">Admin</label>
                <input type="text" class="form-control" name='admin' placeholder="Enter 1 For Admin, 0 For User">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
        <!--End HTML-->
    </main>

<?php
include_once("../../ui/model/footer.php");
?>