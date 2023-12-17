<?php
// require_once "../vendor/autoload.php";
include_once "header.php";

// use admin\SessionUser;
require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);

if ($user->checkIsAdmin() == false) {
    header('Location: home.php');
}

$user->openDatabase();
?>

<link href="../style/admin.css" rel="stylesheet">

<!--Only edit main-->
<main><!-- Main jumbotron for a primary marketing message or call to action -->
    <?php


    // Display table of users
    $sql = "SELECT email, administrator FROM `users`";
    $result = $user->getDatabase()->runQuery_UNSAFE($sql);
    if ($result->num_rows > 0) {
        ?>
        <h1 class="text-center"><u>All Users:</u></h1>

        <style>
            .container{
                margin-left: auto;
                margin-right: auto;
            }
        </style>

        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th> Email </th>
                        <!-- <th> Pass </th> -->
                        <th> Is_admin </th>
                    </tr>
                </thead>

                <?php while ($row = $result->fetch_array(MYSQLI_ASSOC)) { ?>

                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['administrator'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form action="modifyUser.php" method="post">
                                    <input type="hidden" name="email" value="<?= $row['email'] ?>">
                                    <button type="submit" class="btn btn-dark">Modify</button>
                                </form>
                                <form action="../admin/_delete_user.php" method="post">
                                    <input type="hidden" name="email" value="<?= $row['email'] ?>">
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
include_once "footer.php";
?>