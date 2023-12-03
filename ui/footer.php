<hr>
<footer class="container-fluid">
    <div class="f-top">
        <div class="row">
            <div class="col">
                <h4>welfare</h4>
                <ul>
                    <li><a href="#">animals</a></li>
                    <li><a href="#">species</a></li>
                    <li><a href="#">sections</a></li>
                    <li><a href="#">checkups</a></li>
                    <li><a href="#">forms</a></li>
                </ul>
            </div>
            <div class="col">
                <h4>diets</h4>
                <ul>
                    <li><a href="#">coming soon</a></li>
                    <!-- <li><a href=''></li> -->
                </ul>
            </div>
            <div class="col">
                <h4>data</h4>
                <ul>
                    <li><a href="#">compare animals</a></li>
                    <li><a href="#">view all</a></li>
                    <li><a href="#">export data</a></li>
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
