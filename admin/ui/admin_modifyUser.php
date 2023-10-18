<?php
include_once("/home/joshb/website/final/slog/animal-welfare/ui/model/header.php");

$isAdmin = checkIsAdmin();
if($isAdmin == false){
  header('Location: /home/joshb/website/final/slog/animal-welfare/ui/model/home.php');
}

?>

<link href="/home/joshb/website/final/slog/animal-welfare/style/admin.css" rel="stylesheet">

    <!--Only edit main-->
    <main>
      <?php
        
      ?>
      
        <!--Start HTML-->
    <div class = "my-container" style="border:5px solid #000000;"">
        <h1>Modify User </h1>
  
        <form action='/home/joshb/website/final/slog/animal-welfare/admin/modifyUser.php' method='post'>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type='text' class="form-control" name='email' placeholder=<?php echo $_POST['email']; ?> readonly>
                <input type='hidden' value='<?php echo $_POST['email']; ?>' name='email'>
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
include_once("/home/joshb/website/final/slog/animal-welfare/ui/model/footer.php");
?>