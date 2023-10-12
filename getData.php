<?php

// class animal extends databaseManipulation
// {
// }
    
function getData($zim, $date, $section, $wid, $type)
{
    include "Includes/DatabaseConnection.php";
    switch ($type) {
        case "avg-a": // Average of all averages
            $getAverages = $connection->prepare("SELECT `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?");
            $getAverages->bind_param("i", $zim);
            $getAverages->execute();
            $getAverages->bind_result($checkupID, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);
            $average = 0;
            $count = 0;
            while ($getAverages->fetch()) {
                $average += ($avgHealth + $avgNutrition + $avgPSE + $avgBehavior + $avgMental) / 5;
                $count++;
            }
            if ($count == 0)
                return "N/A";
            $getAverages->close();
            return $average / $count;
        case "avg-ya": // Nested/key array for all averages
            $getAverages = $connection->prepare("SELECT `wid`, `dates`,`avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?");
            $getAverages->bind_param("i", $zim);
            $getAverages->execute();
            $getAverages->bind_result($checkupID, $checkupDate, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);

            $averages = [];
            while ($getAverages->fetch()) {
                $averages[$checkupID] = ['date' => $checkupDate, 'health' => $avgHealth, 'nutrition' => $avgNutrition, 'pse' => $avgPSE, 'behavior' => $avgBehavior, 'mental' => $avgMental];

            }
            $getAverages->close();

            return $averages;
        case "avg-yat": // Nested/key array for all total averages
            $getAverages = $connection->prepare("SELECT `wid`, `dates`,`avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?");
            $getAverages->bind_param("i", $zim);
            $getAverages->execute();
            $getAverages->bind_result($checkupID, $checkupDate, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);

            $averages = [];
            while ($getAverages->fetch()) {
                $average = ($avgHealth + $avgNutrition + $avgPSE + $avgBehavior + $avgMental) / 5;
                $averages[$checkupDate] = $average;
            }
            $getAverages->close();

            return $averages;
        case "rec-d": // Date of most recent checkup
            $getDate = $connection->prepare("SELECT `dates` FROM welfaresubmission WHERE zim=?");
            $getDate->bind_param("i", $zim);
            $getDate->execute();
            $getDate->bind_result($dates);
            $date = "";
            $num = 0;
            while ($getDate->fetch()) {
                if ($num == 0) {
                    $date = $dates;
                    $num += 1;
                } else {
                    if ($date < $dates) {
                        $date = $dates;
                    }
                }
            }
            $getDate->close();
            return $date;
        case "avg-d": // Average on a specific date
            $getAverage = $connection->prepare("SELECT `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=? AND dates=?");
            $getAverage->bind_param("is", $zim, $date);
            $getAverage->execute();
            $getAverage->bind_result($checkupID, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);

            $average = 0;
            while ($getAverage->fetch()) {
                $average += ($avgHealth + $avgNutrition + $avgPSE + $avgBehavior + $avgMental) / 5;
            }
            $getAverage->close();
            return $average;
        case "avg-yd": // Array of averages on a specific date
            $getAverages = $connection->prepare("SELECT `dates`, `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=? AND dates=?");
            $getAverages->bind_param("is", $zim, $date);
            $getAverages->execute();
            $getAverages->bind_result($checkupDate, $checkupID, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);

            $averages = [];
            while ($getAverages->fetch()) {
                $averages[$checkupDate] = ['id' => $checkupID, 'health' => $avgHealth, 'nutrition' => $avgNutrition, 'pse' => $avgPSE, 'behavior' => $avgBehavior, 'mental' => $avgMental];
            }
            $getAverages->close();
            return $averages;
        case "avg-s": // Average of all in specific section
            $getAverages = $connection->prepare("SELECT `wid`, ? FROM `welfaresubmission` WHERE zim=?");
            $getAverages->bind_param("si", $section, $zim);
            $getAverages->execute();
            $getAverages->bind_result($checkupID, $avgSection);
            $average = 0;
            $count = 0;
            while ($getAverages->fetch()) {
                $average += $avgSection;
                $count++;
            }
            if ($count == 0)
                return "N/A";
            $getAverages->close();
            return $average / $count;
        case "avg-ys": // Nested/key array for all averages in a specific section
            $getAverages = $connection->prepare("SELECT `wid`, `dates`, ? FROM `welfaresubmission` WHERE zim=?");
            $getAverages->bind_param("si", $section, $zim);
            $getAverages->execute();
            $getAverages->bind_result($checkupID, $checkupDate, $avgSection);

            $averages = [];
            while ($getAverages->fetch()) {
                $averages[$checkupDate] = $avgSection;
            }
            $getAverages->close();

            return $averages;
        case "avg-ds": //Average of a specific section on a specific date
            $getAverage = $connection->prepare("SELECT `wid`, ? FROM `welfaresubmission` WHERE zim=? AND dates=?");
            $getAverage->bind_param("sis", $section, $zim, $date);
            $getAverage->execute();
            $getAverage->bind_result($checkupID, $avgSection);

            $average = 0;
            while ($getAverage->fetch()) {
                $average += $averageSection;
            }
            $getAverage->close();
            return $average;
        case "rsp-c": // Responses on a specific checkup no.
            $getAverage = $connection->prepare("SELECT `wid`, `responses` FROM `welfaresubmission` WHERE zim=? AND dates=?");
            $getAverage->bind_param("is", $zim, $date);
            $getAverage->execute();
            $getAverage->bind_result($checkupID, $responses);

            $responses = ["checkupID" =>$checkupID];

            while ($getAverage->fetch()) {
                $res = unpack("C*", $responses);
                $row = [];
                $rowCount = 0;
                $readCount = 0; 
                $next = true;
                foreach ($res as $data) {
                    if (is_null($row[$count]))
                        $row[$count] = "";
                    
                    echo "d-";
                    echo $data;
                    echo " c";
                    echo chr($data);
                   
                    

                    $char = chr($data);
                    echo " c2-";
                    echo $char;
                    $row[$count] = $row[$count] . $char;
                    echo " c3-";
                    echo $row[$count];
                    echo "<br>";
                    // $row[$count] = $row[$count] . chr($data);
                    // echo $row[$count];
                    if ($data == 10) {
                        echo "-----------------<br>";
                        $count++;
                    }
                }
            }
            // $getAverage->close();
            return $responses;
    }
}

// function recentCheckup($zim)
// {
//     include "../animal-welfare/fnc/db/authConnection/dbConnection.php";
//     $getDate = $connection->prepare("SELECT `dates` FROM welfaresubmission WHERE zim=?");
//     $getDate->bind_param($zim);
//     $getDate->execute();
//     $getDate->bind_result($dates);
//     $getDate->store_result();
//     $date = "";
//     $num = 0;
//     while ($getDate->fetch()) {
//         if ($num == 0) {
//             $date = $dates;
//         } else {
//             if ($date < $dates) {
//                 $date = $dates;
//             }
//         }
//     }
//     $getDate->close();
//     $connection->close();
//     return $date;
// }

// function getavg_date($zim, $date)
// {
//     include "../animal-welfare/fnc/db/authConnection/dbConnection.php";
//     $getAverages = $connection->prepare("SELECT `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=? AND dates=?");
//     $getAverages->bind_param($zim);
//     $getAverages->execute();
//     $getAverages->bind_result($checkupID, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);
//     $getAverages->store_results();

//     $average = 0;
//     while ($getAverages->fetch()) {
//         $average += ($avgHealth + $avgNutrition + $avgPSE + $avgBehavior + $avgMental) / 5;
//     }
//     $getAverages->close();
//     $connection->close();
//     return $average;
// }


// function getavg_section($zim, $avgSection)
// {
//     include "../animal-welfare/fnc/db/authConnection/dbConnection.php";
//     $getAverages = $connection->prepare("SELECT `wid`, ? FROM `welfaresubmission` WHERE zim=?");
//     $getAverages->bind_param($avgSection, $zim);
//     $getAverages->execute();
//     $getAverages->bind_result($checkupID, $section);
//     $getAverages->store_results();
//     $getAverages->fetch();
//     $average = 0;
//     $count = 1;
//     while ($getAverages->fetch()) {
//         $average += $section;
//         $count++;
//     }
//     $getAverages->close();
//     $connection->close();
//     return $average / $count;
// }

// 53 44 49 44 50 44 51 44 52 44 53 13 10 
// 49 44 53 44 53 44 53 44 53 44 53 13 10 
// 50 44 53 44 53 44 53 44 53 44 53 13 10 
// 51 44 53 44 53 44 53 44 53 44 53 13 10 
// 52 44 53 44 53 44 53 44 53 44 53 13 10 
// 53 44 53 44 53 44 53 44 53 44 53 13 10 
// 54 44 53 44 44 53 44 53 44 53 13 10 
// 55 44 53 44 44 53 44 53 44 53 13 10 
// 56 44 53 44 44 53 44 53 44 13 10 
// 57 44 53 44 44 53 44 53 44

// 53 44 49 44 50 44 51 44 52 44 53 13 10 
// 49 44 53 44 53 44 53 44 53 44 53 13 10 
// 50 44 53 44 53 44 53 44 53 44 53 13 10 
// 51 44 53 44 53 44 53 44 53 44 53 13 10 
// 52 44 53 44 53 44 53 44 53 44 53 13 10 
// 53 44 53 44 53 44 53 44 53 44 53 13 10 
// 54 44 53 44 00 44 53 44 53 44 53 13 10 
// 55 44 53 44 00 44 53 44 53 44 53 13 10 
// 56 44 53 44 00 44 53 44 53 44 00 13 10 
// 57 44 53 44 00 44 53 44 53 44 00