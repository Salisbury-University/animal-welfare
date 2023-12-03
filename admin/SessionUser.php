<?php
// namespace admin;
// require_once "../vendor/autoload.php";
// use \auth\DatabaseManager; // not working
// use \config\ConfigHandler; // not working

/**
 * Represents a user session.
 *
 * This class manages user authentication, session status, and various user-related operations.
 *
 * @class SessionUser
 */
class SessionUser
{
    /**
     * The user's authentication token.
     */
    private $token = null;
    private $username = null;
    private $database = null;
    private $admin = null;
    private $recover = null;

    /**
     * Constructs a new SessionUser instance.
     *
     * Creates a token for the current session and initializes session variables.
     */
    function __construct($username, $password)
    {
        // Creates a user token for the current session 
        // ::: Will be used when the DatabaseManager is not stored in the SessionUser class to cross verify
        $this->token = bin2hex(random_bytes(32) . microtime(true));

        // Checks whether the session has started or not
        if ($this->sessionStatus()) {
            $_SESSION["token"] = $this->token;
            $_SESSION['loginFailures'] = 0;
        }

        require_once "../config/ConfigHandler.php";
        $config = new ConfigHandler();

        // Reads the config file in
        $configFile = $config->readConfigFile();

        // If a recovery account is enabled, it will log user in as the recovery
        if ($configFile['Recovery']['recoveryAccountEnabled'] == 1) {
            $recoveryUsername = $configFile['Recovery']['recoveryAccountUsername'];
            $recoveryPassword = $configFile['Recovery']['recoveryAccountPassword'];
            if ($username == $recoveryUsername && $password == $recoveryPassword) {
                // Disable the account so it can't be used anymore
                $config->writeToConfigFile("recoveryAccountEnabled", "0", "1");

                // Set the session variables and log the recovery user in.
                $this->username = $username;
                $this->admin = 1;
                $this->recover = 1;

                // Relocates user and exits script
                header("Location: ../ui/home.php");
                exit();
            }
        } else { // Regular or Admin
            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            require_once "../auth/DatabaseManager.php";
            $database = new DatabaseManager;

            $query = "SELECT pass, administrator FROM users WHERE email = ?";
            $userData = $database->runParameterizedQuery($query, 's', array($username));

            if ($userData && $userData->num_rows == 1) {
                $row = $userData->fetch_assoc();
                $storedHash = $row['pass'];

                // Verify the user's password against the stored hash
                if (password_verify($password, $storedHash)) {
                    // Password is correct, assign values
                    $this->admin = $row['administrator'];
                    $this->username = $username;
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

    /**
     * Checks the session status.
     *
     * @static
     */
    static public function sessionStatus()
    {
        if (session_status() != 2) {
            session_start();
            return 0;
        }
        return 1;
    }

    /**
     * Gets the associated database manager instance.
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Checks if the user is an admin.
     */
    public function checkIsAdmin()
    {
        return $this->admin;
    }

    /**
     * Checks if the user is logged in.
     *
     * @return bool Returns true if the user is logged in, false otherwise.
     */
    public function checkIsLoggedIn()
    {
        if ($this->username != null)
            return true;
        return false;
    }

    /**
     * Checks if the user is in recovery mode.
     *
     * @return int|null The recovery status of the user.
     */
    public function checkIsRecover()
    {
        if ($this->recover != null)
            return true;
        return false;
    }

    /**
     * Checks for errors in the user session.
     *
     * Redirects to the login page if the user is not logged in.
     * Displays login and change password errors if any.
     */
    public function checkError()
    {
        if ($this->checkIsLoggedIn() == false) {
            header("Location: ../ui/index.php"); // Send user to login screen
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

    /**
     * Opens a database connection using the DatabaseManager.
     *
     * Creates a new DatabaseManager instance and assigns it to the $database property.
     * 
     * NOTE: This is referenced far too heavily for scoping reasons, must find a way to reduce
     */
    public function openDatabase()
    {
        require_once "../auth/DatabaseManager.php";
        $this->database = new DatabaseManager;
    }

    /**
     * Logs out the current user.
     *
     * Updates the last logged-in timestamp in the database.
     * Clears cookies and session data to log the user out.
     * Redirects to the login page.
     */
    public function logOut()
    {
        // Magical
        $this->openDatabase();

        if ($this->checkIsLoggedIn()) {
            // Bind and execute the query with the username of the logged-in user
            $query = "UPDATE users SET lastLoggedIn = NOW() WHERE email = ?";
            $this->database->runParameterizedQuery($query, 's', [$this->username]);

            // Clear cookies 
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            }

            // Clear session data
            session_destroy();

            SessionUser::redirectUser("../index.php");
        }
    }

    public static function redirectUser($stringPath){
        if(headers_sent() == false){
            header("Location: " + $stringPath);
        }else{
            echo "<script type='text/JavaScript'>
            function redirect(){
                window.location.href = '$stringPath';
            }

            redirect();
            
            </script>";
        }
    }


    /**
     * Creates a new user in the database.
     *
     * Hashes the password and inserts user data into the 'users' table.
     * Redirects to the admin page after creating the user.
     */
    public function createUser($username, $password, $adminFlag)
    {
        // Magical
        $this->openDatabase();
        if ($this->admin == 0) {
            return;
        }
        // Hash the password
        $storedHash = password_hash($password, PASSWORD_DEFAULT);

        // Bind and execute the query with the username, pass, and admin flag of the logged-in user
        $query = "INSERT INTO users(email, `pass`, administrator) VALUES (?, ?, ?)";
        $this->database->runParameterizedQuery($query, "ssi", array($username, $storedHash, $adminFlag));

        // Redirect to the admin home page
        header("Location: ../ui/admin.php");
    }

    /**
     * Modifies an existing user in the database.
     *
     * Updates the user's password, admin status, or both based on input parameters.
     * Redirects to the admin page after modifying the user.
     */
    public function modifyUser($username, $password, $adminFlag)
    {
        // Magical
        $this->openDatabase();

        if ($this->admin == 0) {
            return;
        }
        if ($password == null && $adminFlag == null) {
            header("Location: ../ui/admin.php");
            return;
        }

        // Hashes password
        $storedHash = password_hash($password, PASSWORD_DEFAULT);

        // Tests which information entered into the function
        if ($password != NULL && $adminFlag != NULL) {
            $query = "UPDATE `users` SET `pass`=?, `administrator`=? WHERE `users`.`email`=?;";
            $this->database->runParameterizedQuery($query, "sis", array($storedHash, $adminFlag, $username));

        } elseif ($password == NULL && $adminFlag != NULL) { // Only updates the admin flag
            $query = "UPDATE `users` SET `administrator`=? WHERE `users`.`email`=?;";
            $this->database->runParameterizedQuery($query, "is", array($adminFlag, $username));

        } elseif ($password != NULL && $adminFlag == NULL) { // Only updates the password
            $query = "UPDATE `users` SET `pass`=? WHERE `users`.`email`=?;";
            $this->database->runParameterizedQuery($query, "ss", array($storedHash, $username));

        }

        // Redirect to home directory
        header("Location: ../ui/admin.php");
    }

    /**
     * Deletes a user from the database.
     *
     * Removes the user from the 'users' table.
     * Redirects to the admin page after deleting the user.
     */
    public function deleteUser($username)
    {
        // Magical
        $this->openDatabase();

        if ($this->admin == 0) {
            return;
        }

        // Binds and execute the delete query
        $query = "DELETE FROM users WHERE users.email = ?;";
        $result = $this->database->runParameterizedQuery($query, "s", array($username));
        unset($result);

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

    /**
     * Changes the password for a user in the database.
     *
     * Updates the user's password based on input parameters.
     * Stores change password success or failure messages in session variables.
     * Redirects to the admin page.
     */
    public function changePassword($username, $password1, $password2)
    {
        // Magical
        $this->openDatabase();

        // Verify
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