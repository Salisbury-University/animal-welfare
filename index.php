<!DOCTYPE html>

<html>
    <!-- Body -->
    <link rel="stylesheet" href="CSS/index.css">
    <body background="Images/login-bg.jpg" onLoad="onLoginPageLoad()">

        <img class="flamingoImage" src="Images/ChileanFlamingo.jpg" ALIGN="left" />

        <img class="salisburyZooLogoImage" src="Images/salisburyZooLogo.png" />

                <!-- Replace demo_index.php with whatever PHP file is going to handle
                the login. (SET THIS UP GEORGE, PLS)
                -->
        <form class="loginForm" action="demo_index.php" method="get">
            <input type="text" placeholder="Username" name="user"/>
            <input type="password" placeholder="Password" name="pwd"/>
            <br> <br>
            <input type="submit" value="Log in"/>
        </form>

        <script>
            function onLoginPageLoad() {
                // Keeping this useless function here since we most likely
                // will need it later.
                // window.alert("Loaded!");
            }
        </script>

    </body>
</html>
