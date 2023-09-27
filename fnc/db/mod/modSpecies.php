<!DOCTYPE html>
<html>
 <!-- ToDo tmrw:
    ez      1. Recreate the submission table in db make sure to have individual responses (csv format) /done
    ez      2. populate with test data      /done
    med-hrd 3. fixup header on my trial     ~last 
    ez      4. pick a visualization software (chart.css) /done 
    ez      5. queries for add animals, delete animals, and update animals
    med     6. php for back button to work with the previous search - will need to save search as a ? query url
    med-hrd 7. queries and php for grabbing data from the db and taking the average - unfortunately we will be scrapping George's 
            8
         -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- This will need to be changed to the specific animal we are viewing -->
    <title>Animal</title>

    <link rel="stylesheet" href="animalpage.css">
</head>

<body>
    <header>

        <div id="h-dec">
            <img src="C:/Users/Joshua/AnimalWelfare/images/Header/foilage-left.png" id="foil-l" title="leavesL">
            <img src="C:/Users/Joshua/AnimalWelfare/images/Header/foilage-center.png" id="foil-m" title="leavesM">
            <img src="C:/Users/Joshua/AnimalWelfare/images/Header/foilage-right.png" id="foil-r" title="leavesR">
        </div>
        <div id="h-nav">
            <ul>
                <li><a href="#">animals</a></li>
                <li><a href="#">species</a></li>
                <li><a href="#">sections</a></li>
                <li><a href="#">checkups</a></li>
                <li><a href="#">forms</a></li>
                <li><a href="#">    </a></li>
                <li><a href="#">    </a></li>
                <li><a href="#">    </a></li>
                <li><a id="log" href="logoutHandler.php" role="button">Logout</a></li>
                
            </ul>
            
        </div>
        
        <img src="C:/Users/Joshua/AnimalWelfare/images/Header/logo.png" id="logo" title="logo">
    </header>

    <main>
        <!-- Profile card start -->
        <div class="card" style="flex-shrink: 2; margin-top: 2em; padding: 1em;">
            <div class="container">
                <!-- 
                #animal name (sex)       id
                #species
                #id
                #section
                #birthdate
                #acquistion date
                
                //Recent Notes:
                #notes 
                
                if any empty return N/A
                -->
                
                <div class="display">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Name:</strong> #577</li>
                        <li class="list-group-item"><strong>ISIS:</strong> 599</li>
                        <li class="list-group-item"><strong>Section:</strong> N A Duck Exhibit</li>
                        <li class="list-group-item"><strong>Species:</strong> Duck, Lesser Scaup</li>
                        <li class="list-group-item"><strong>Sex: </strong> Female</li>
                        <li class="list-group-item"><strong>Acquisition Date: </strong> 1981-12-21</li>
                        <li class="list-group-item"><strong>Birthdate: </strong> 1981-06-15</li>
                        <li class="list-group-item"><strong>Last Entry:</strong> N/A</li>
                    </ul>
                    
                </div>
            </div>
        </div>
        <!-- Profile card end -->

        <div class="column" style="flex-shrink: 1; margin-right: 1em; margin-top: 1em;">
            <!-- Wellness Tracker card start -->
            <div class="card"> 
                <!-- 
                    Header with drop down to scroll to different sections
                   
                    Graph that grabs data depending on whatever the header is
                 -->
            </div>
            <!-- Wellness Tracker card end -->
            <!-- Diet Tracker card start -->
            <div class="card">
                <!-- add more detail dont really know whats going on in here
                    Header

                    Graph
                 -->
            </div>
            <!-- Diet Tracker card end -->
        </div>
        
        
    </main>

    <footer>
        <div id="ft-nav">
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
                        <li><a href="#">interactive map (Coming Soon)</a></li>
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
                <div class="col">
                    <h4>help</h4>
                    <ul>
                        <li><a href="help.php">help page</a></li>
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- <button type="button" id="coming-soon">COMING SOON</button> -->
        <img src="C:/Users/Joshua/AnimalWelfare/images/turtle.svg" id="turtle" title="turtle-icon">
        <img src="C:/Users/Joshua/AnimalWelfare/images/parrot.svg" id="parrot" title="parrot-icon">
        <img src="C:/Users/Joshua/AnimalWelfare/images/birds.png" id="birds" title="birds-icon">
    </footer>
</body>

</html>