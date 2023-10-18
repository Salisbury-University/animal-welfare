<?php
include_once("/home/joshb/website/final/slog/animal-welfare/ui/model/header.php");

$isAdmin = checkIsAdmin();
if($isAdmin == false){
  header('Location: /home/joshb/website/final/slog/animal-welfare/ui/model/home.php');
}

?>

<link href="/home/joshb/website/final/slog/animal-welfare/style/admin.css" rel="stylesheet">

    <!--Only edit main-->
    <main><!-- Main jumbotron for a primary marketing message or call to action -->
    <?php


      // Display table of users
      $sql = "SELECT email, pass, administrator FROM `users`";
      $result = $database->runQuery_UNSAFE($sql);
      if(mysqli_num_rows($result) > 0){
    ?>  
  <h1 class = "text-center"><u>All Users:</u></h1>

    <div class = "container">
     <table class="table table-bordered">
        <thead>
              <tr>
                <th> Email </th>
                <th> Pass </th>
                <th> Is_admin </th>
              </tr>
            </thead>
           
            <?php while($row = mysqli_fetch_array($result)){ ?>
          
            <tbody>
              <tr>
                <td><?=htmlspecialchars($row['email'],ENT_QUOTES,'UTF-8')?></td>
                <td><?=htmlspecialchars($row['pass'],ENT_QUOTES,'UTF-8')?></td>
                <td><?=htmlspecialchars($row['administrator'],ENT_QUOTES,'UTF-8')?></td>
                <td>
                  <form action = "admin_modifyUser.php" method = "post">
                    <input type = "hidden" name = "email" value = "<?=$row['email']?>">
                    <button type="submit" class="btn btn-dark">Modify</button></td>
                  </form>
                <td>
                  <form action = "/home/joshb/website/final/slog/animal-welfare/admin/deleteUser.php" method = "post">
                    <input type = "hidden" name = "email" value = "<?=$row['email']?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            </tbody>
          <?php
              }
          }
          ?>
        </table>
      </div>
    </main>

<?php
include_once("/home/joshb/website/final/slog/animal-welfare/ui/model/footer.php");
?>