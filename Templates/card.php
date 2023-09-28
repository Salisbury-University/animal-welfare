<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- This will need to be changed to the specific species we are viewing -->
    <title>Species</title>

    <link rel="stylesheet" href="animalpage.css">
</head>

<!-- Apparently body is for all layout param/elem so header and footer can go inside -->
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
            </ul>
        </div>
        <img src="C:/Users/Joshua/AnimalWelfare/images/Header/logo.png" id="logo" title="logo">
    </header>

    <!-- Not repeated anywhere on the site -->
    <main>
        <!-- Profile card start -->
        <div class="card" style="flex-shrink: 2; height: 73vh; margin-top: 2em; padding: 1em;">
            <div class="container">
                <!-- 
                #species name           form id
                //Recent Notes:
                #notes 
                
                if any empty return N/A
                -->
            </div>
        </div>
        <!-- Profile card end -->

        <div class="column" style="flex-shrink: 1; margin-right: 2em; margin-top: 1em;">
            <!-- Species members card start -->
            <div class="card"> 
                <!-- will eventually add diet score if there is one to this info list
                    Header = "Animals"

                    list that gives basic and updated information on each member of the species
                    : zid (hyperlinked), name, section latest checkup, latest note, concern icon
                    # based on latest checkup score, a checkmark, warning sign, or red exclam. mark will be shown
                      - check 5-3.8
                      - warn 3.8- 2.5
                      - mark 2.5 - 1
                 -->
            </div>
            <!-- Species members card end -->
            <!-- Group tracker card start -->
            <div class="card">
                <!-- add more detail dont really know whats going on in here
                    Header

                    Graph
                 -->
            </div>
            <!-- group tracker card end -->
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

        <button type="button" id="coming-soon">COMING SOON</button>
        <img src="C:/Users/Joshua/AnimalWelfare/images/turtle.svg" id="turtle" title="turtle-icon">
        <img src="C:/Users/Joshua/AnimalWelfare/images/parrot.svg" id="parrot" title="parrot-icon">
        <img src="C:/Users/Joshua/AnimalWelfare/images/birds.png" id="birds" title="birds-icon">
    </footer>
</body>

</html>
