<?php
ob_start();
session_start();
include 'Includes/DatabaseConnection.php';
include "Includes/loggedInUserHelper.php";

//Add code here later that checks if the current user has the Is_Admin flag.
//Redirect to the homepage if theyre not an admin.
$isAdmin = checkIsAdmin();
if($isAdmin == false){
    header('Location: home.php');
}

// Display table of users
// TODO: Instead of displaying everything at once automatically,
// add the ability to search or display all users.
$sql = "select email, pass, administrator from users";
$result = mysqli_query($connection, $sql);
if(mysqli_num_rows($result) > 0){
    echo "<Table border = '1'>
            <thead>
                <tr>
                    <th> email </th>
                    <th> pass </th>
                    <th> is_admin </th>
                </tr>
            </thead>";
    while($row = mysqli_fetch_array($result)){
        echo "<tr>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['pass'] . "</td>";
        echo "<td>" . $row['administrator'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
else{
    echo "Error: Empty table <br>";
}

//Separator
echo "<br> <br>";

// TODO: Make this more user friendly. 
// They may not know that 1=yes and 0=no. Perhaps this could be a check box?
echo "Create user form <br>";
echo "<form action='admin_createUser.php' method='post'>
        Email: <input type='text' name='email'> <br>
        Password: <input type='password' name='password'> <br>
        Admin: <input type='text' name='admin'> <br>
        <input type='submit'>
    </form>";

echo "<br> <br>";

echo "Delete user form <br>";
echo "<form action='admin_deleteUser.php' method='post'>
        Email: <input type='text' name='email'> <br>
        <input type='submit'>
    </form>";

$output = ob_get_clean();
include 'page_frame.php';
?>