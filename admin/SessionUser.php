<?php
// namespace admin;

// require_once "../vendor/autoload.php";

// use \auth\DatabaseManager; // not working
// use \config\ConfigHandler; // not working




class SessionUser
{
    private $token = null;
    private $username = null;
    private $database = null;
    private $admin = null;
    private $password = null;
    private $recover = null;

    function __construct($username, $password)
    {
        // Creates a token for the current session
        $this->token = bin2hex(random_bytes(32) . microtime(true));
        if ($this->sessionStatus()) {
            $_SESSION["token"] = $this->token;
            $_SESSION['loginFailures'] = 0;
        }

        require_once "../config/ConfigHandler.php";
        // Reads in configuration settings
        $config = new ConfigHandler();
        $configFile = $config->readConfigFile();

        if ($configFile['Recovery']['recoveryAccountEnabled'] == 1) {
            $recoveryUsername = $configFile['Recovery']['recoveryAccountUsername'];
            $recoveryPassword = $configFile['Recovery']['recoveryAccountPassword'];
            if ($username == $recoveryUsername && $password == $recoveryPassword) {
                $config->writeToConfigFile("recoveryAccountEnabled", "0", "1"); // Disable the account so it cant be used anymore

                // Set the session variables and log the recovery user in.
                $this->username = $username;
                $this->password = $password;
                $this->admin = 1;
                $this->recover = 1;
                header("Location: ../ui/home.php");
                exit();
            }
        } else {
            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Using DatabaseManager, retrieve password_hash and is_admin where username = $username
            $this->openDatabase();

            // Replace with your actual SQL query to retrieve user data
            $query = "SELECT pass, administrator FROM users WHERE email = ?";

            // Bind and execute the query with the username
            $userData = $database->runParameterizedQuery($query, 's', [$username]);

            if ($userData && $userData->num_rows == 1) {
                $row = $userData->fetch_assoc();
                $storedHash = $row['pass'];

                // Verify the user's password against the stored hash
                if (password_verify($password, $storedHash)) {
                    // Password is correct, assign values
                    $this->database = $database;
                    $this->admin = $row['administrator'];
                    $this->username = $username;
                    $this->password = $passwordHash;
                } else {
                    // Password is incorrect, increment loginAttempts
                    if (isset($_SESSION['loginFailures'])) {
                        $_SESSION['loginFailures']++;
                        header("Location: ../ui/index.php");
                        $_SESSION['loginError'] = "Incorrect Password";
                    }
                }
            }
        }
    }

    static public function sessionStatus()
    {
        if (session_status() != 2) {
            session_start();
            return 0;
        }
        return 1;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function checkIsAdmin()
    {
        return $this->admin;
    }

    public function checkIsLoggedIn()
    {
        if ($this->password != null)
            return true;
        return false;
    }
    public function checkIsRecover()
    {
        if ($this->recover != null)
            return true;
        return false;
    }

    public function checkError()
    {
        if ($this->checkIsLoggedIn() == false) {
            header("Location: ../ui/index.php");
        }

        if (isset($_SESSION['loginError']) == true) {
            $temp = $_SESSION['loginError'];
            $_SESSION['loginError'] = NULL; // Clear out the session variable
            echo "<body style='color: white;'> $temp </body>";
        }

        if (isset($_SESSION['changePasswordError']) == true) {
            $temp = $_SESSION['changePasswordError'];
            unset($_SESSION['changePasswordError']); // Clear out the session variable
            echo "<body> $temp </body>";
        }
    }

    public function openDatabase()
    {
        require_once "../auth/DatabaseManager.php";
        // return var_dump($this->database);

        $this->database = new DatabaseManager;
    }

    public function logOut()
    {
        $this->openDatabase();

        if ($this->checkIsLoggedIn()) {
            // Replace with your actual SQL query
            $query = "UPDATE users SET lastLoggedIn = NOW() WHERE email = ?";

            // Bind and execute the query with the username of the logged-in user
            $this->database->runParameterizedQuery($query, 's', [$this->username]);

            // Clear cookies (example: clearing a specific cookie named 'user_cookie')
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            }

            // Clear session data
            session_destroy();

            header("Location: ../ui/index.php");
        }
    }

    public function createUser($username, $password, $adminFlag)
    {
        $this->openDatabase();

        if ($this->admin == 0) {
            return;
        }
        // Hash the password
        $storedHash = password_hash($password, PASSWORD_DEFAULT);

        // Create an array with the values
        $valueArr = array($username, $storedHash, $adminFlag);

        // Define the SQL statement
        $query = "INSERT INTO users(email, `pass`, administrator) VALUES (?, ?, ?)";

        // Insert the user into the database
        $this->database->runParameterizedQuery($query, "ssi", $valueArr);

        // Redirect to the desired page (e.g., admin home page)
        header("Location: ../ui/admin.php");
    }

    public function modifyUser($username, $password, $adminFlag)
    {
        $this->openDatabase();

        if ($this->admin == 0) {
            return;
        }
        if ($password == null && $adminFlag == null) {
            header("Location: ../ui/admin.php");
            return;
        }

        $storedHash = password_hash($password, PASSWORD_DEFAULT);

        if ($password != NULL && $adminFlag != NULL) {
            $query = "UPDATE `users` SET `pass`=?, `administrator`=? WHERE `users`.`email`=?;";
            $this->database->runParameterizedQuery($query, "sis", array($storedHash, $adminFlag, $username));

        } elseif ($password == NULL && $adminFlag != NULL) { // Only want to modify the admin flag
            $query = "UPDATE `users` SET `administrator`=? WHERE `users`.`email`=?;";
            $this->database->runParameterizedQuery($query, "is", array($adminFlag, $username));

        } elseif ($password != NULL && $adminFlag == NULL) { // Only want to update the password
            $query = "UPDATE `users` SET `pass`=? WHERE `users`.`email`=?;";
            $this->database->runParameterizedQuery($query, "ss", array($storedHash, $username));

        }

        // Redirect to home directory
        header("Location: ../ui/admin.php");
    }

    public function deleteUser($username)
    {
        $this->openDatabase();

        if ($this->admin == 0) {
            return;
        }

        $query = "DELETE FROM users WHERE users.email = ?;";
        $result = $this->database->runParameterizedQuery($query, "s", array($username));

        // Commenting this debug code out in case its needed in the future.
        // If the query fails, leave the user on the page.
        /*if($result == false){
            echo "MYSQL query failed <br>";
            exit;
        } else{
         echo "MSQL query successfull <br>";
        }*/

        // Redirect to home directory
        header("Location: ../ui/admin.php");
    }

    public function changePassword($username, $password1, $password2)
    {
        $this->openDatabase();

        if ($password1 == $password2) {
            $sql = "UPDATE `users` SET `pass`=? WHERE `users`.`email`=?;";
            $storedHash = password_hash($password1, PASSWORD_DEFAULT);
            $this->database->runParameterizedQuery($sql, "ss", array($storedHash, $username));

            $_SESSION['changePasswordError'] = "Password successfully changed.";
        } else { // If they dont match then we store the error in the session variable.
            $_SESSION['changePasswordError'] = "Passwords do not match!";
        }

        header("Location: ../ui/admin.php"); // needs to be redirected somewhere else
    }

}