<?php
function getData($zim, $date, $section, $wid, $type)
{
    include "../slog/animal-welfare/fnc/db/auth/dbConnection.php";
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
            $getAverage->bind_result($checkupID, $responseList);

            
            

            while ($getAverage->fetch()) { //error
                $responses = ['checkupID' =>$checkupID, 'formID' => null,'sections'=>[]];
                $res = unpack("C*", $responseList);
                $rowCount = $colCount = 0; 
                $next = $header = true;
                foreach ($res as $data) {
                    if(chr($data) == ','){ // Split the data by comma
                        if($next){
                            $colCount++;
                            $next = false;
                        } else {
                            $next = true;
                        }
                    } else if($data == 10){ // Split data by row
                        $colCount = 0;
                        if($header)
                            $header = false;
                        $rowCount++;
                    } else if($data != 13 && chr($data) != ',' && $data != 00){
                        if($header){
                            if($colCount == 0){
                                $responses['formID'] = chr($data);
                            } else {
                                $responses["sections"][$colCount]['colID'] = chr($data);
                            }
                        } else {
                            $responses["sections"][$colCount][$rowCount] = chr($data);
                            
                        }
                        $colCount++;
                        $next = false;
                    }
                }
            }
            $getAverage->close();
            return $responses;
    }
}
