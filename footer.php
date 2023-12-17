 <hr>
<footer class="container-fluid">
    <div class="f-top">
        <div class="row">
            <div class="col">
                <h4>animals</h4>
                <ul>
                    <li><a href="search.php">search</a></li>
                    <!--<li><a href="#">Blank</a></li>-->
                </ul>
            </div>
            
            <div class="col">
                <h4>data</h4>
                <ul>
                    <li><a href="exportData.php">export data</a></li>
                    <!-- <li><a href="#">interactive map</a></li> -->
                    <!-- <li><a href=''></li> -->
                </ul>
            </div>
            <div class="col">
                <h4>help</h4>
                <ul>
                    <li><a href="help.php">help page</a></li>
                    <?php
                    // require_once "../vendor/autoload.php";
                    // use admin\SessionUser;

                    require_once "../admin/SessionUser.php";
                    SessionUser::sessionStatus();

                    $user = unserialize($_SESSION['user']);
                    // Check if recovery account
                    if ($user->checkIsRecover() == 0) {
                        echo "<li><a href='changePassword.php'>Change Password</a></li>";
                    }
                    ?>
                    <!-- <li><a href=''></li> -->
                </ul>
            </div>
        </div>
    </div>
</footer>
</body>
