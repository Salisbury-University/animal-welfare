<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="public.css">
    <style>
      /* Center the content */
      main {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }
      .box {
        border: 6px solid black;
        width: 700px;
        height: 400px;
        padding: 10px;
        background-color: #cbeda6;
        border-radius: 10px;

        /* Center the box */
        display: flex;
        justify-content: center;
         /*align-items: center; this centered the text into the mid of box*/

        margin-top: 10px;
        margin-bottom: 20px;

        vertical-align: top;
      }

      /* Center the search bar and submit button */
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }

      .search-container {
        display: flex;
        align-items: center;
      }

      input[type="text"],
      input[type="submit"] {
        margin: 0 5px;
      }

    </style>
  </head>
  <body>
    <?php include 'header.php';?>
    <main>
      <div>
        <h3>Enter a keyword to search for an animal</h3>
        <form method="POST">
          <div class="search-container">
            <input type="text" placeholder="Search" name="search" />
            <input type="submit" value="Search" />
          </div>
        </form>
      </div>
      <div class="box">
        <!-- This is where the search results will be displayed -->
        <?php
          if(isset($_POST['search'])){
            $search = $_POST['search'];
            }
          }
        ?>
      </div>
    </main>
    <footer>&copy; GRDJ 2023</footer>
  </body>
</html>
                                           
