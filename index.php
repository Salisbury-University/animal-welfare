<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Dashboard</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="../style/index.css">

        <!--Boostrap javascript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

    </head>

    <main>
    <div class="container-fluid">
    <div class="row align-items-start">
        <!--Flamingo-->
        <div class="col-md-4">
            
        </div>
        <!--Login-->
        <div class="col-md-8">
        
        <img class="salisburyZooLogoImage" src="../img/Header/logo.png" />
        
        <form class="loginForm" action="../fnc/auth/loginHandler.php" method="post">
                <input type="text" placeholder="Email" name="submittedEmail"/>
                <input type="password" placeholder="Password" name="submittedPassword"/>
                <input type="submit" value="Log in"/> <br>
                <?php include "../fnc/auth/loginError.php"; ?>
        </form>
        </div>
    </div>
    </div>
    </main>   
</html>
