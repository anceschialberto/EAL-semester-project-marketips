<?php
require 'vendor/parse/php-sdk/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;



function getLastXTraffic($x){
    ParseClient::initialize('P60EfTUuOZoeZyD2qSLpOrc8DWwUk2YjEqU2HY1R', 's3b2cfGtQhSFYM16ZIJQ7yXioTjt35Um5mn9SyP8', '3jz6CONqt5psS4UlGu3RB28ldIw311Iv2I8eA3Mh');

    $query = new ParseQuery("Reading");
    if($x<32){
        $period = date('Y-m-d\TH:i:s.u\Z',strtotime("-".$x. "day"));
    } else {
        $period = date('Y-m-d\TH:i:s.u\Z',strtotime("-7 day"));
        $x=7;
    }

    $vars = "<h2> Last " . $x . " Days </h2>";

    $query->greaterThan("createdAt",  $period);
    $query->limit(1000);
    $results = $query->find();


    $weeklyTraffic = Array();
    $currentDay = $results[0]->getCreatedAt()->format('d');
    $weeklyTraffic[$currentDay] = 0;

    $labelsString = "";
    $data = "";

    for($i = 0; $i < count($results); $i++){

        if($currentDay == $results[$i]->getCreatedAt()->format('d')){
            $weeklyTraffic[$results[$i]->getCreatedAt()->format('d').""] = $weeklyTraffic[$results[$i]->getCreatedAt()->format('d').""]+1;
        } else{
            /*
             * Prepare strings for x axis in javascript
             */
            $labelsString .= "'" . $currentDay . "',";
            $data .= "'" . $weeklyTraffic[$currentDay] . "',";

            $weeklyTraffic[$results[$i]->getCreatedAt()->format('d').""] = 1;
            $currentDay = $results[$i]->getCreatedAt()->format('d');
        }

    }



    $vars .=  "<div style=\"width:30%\">
        <div>
            <canvas id=\"canvas\" height=\"450\" width=\"600\"></canvas>
        </div>
    </div>


    <script>
        var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
        var lineChartData = {
            labels : [" .
                $labelsString .

                "],
            datasets : [
                {
                    label: \"Last ". $x ." Days\",
                    fillColor : \"rgba(151,187,205,0.2)\",
                    strokeColor : \"rgba(151,187,205,1)\",
                    pointColor : \"rgba(151,187,205,1)\",
                    pointStrokeColor : \"#fff\",
                    pointHighlightFill : \"#fff\",
                    pointHighlightStroke : \"rgba(151,187,205,1)\",
                    data : [" .
                $data .
                "]
                }
            ]

        }

    window.onload = function(){
        var ctx = document.getElementById(\"canvas\").getContext(\"2d\");
        window.myLine = new Chart(ctx).Line(lineChartData, {
            responsive: true
        });
    }


    </script>";


    return $vars;
}

function infoArticle($title){
    ParseClient::initialize('P60EfTUuOZoeZyD2qSLpOrc8DWwUk2YjEqU2HY1R', 's3b2cfGtQhSFYM16ZIJQ7yXioTjt35Um5mn9SyP8', '3jz6CONqt5psS4UlGu3RB28ldIw311Iv2I8eA3Mh');


    $query = new ParseQuery("Reading");

    $query->equalTo("title",  $title);
    $query->limit(10000);
    $results = $query->find();


    // in result I have all the first 1000 results stored (title)
    // I count the average time spent on an article
    $startReadingCount = 0;
    $startReadingHowMany = 0;
    $readArticleCount = 0;
    $readArticleHowMany = 0;
    $pageBottomCount = 0;
    $pageBottomHowMany = 0;
    $scanned = 0;
    $readed = 0;

    for($i=0; $i<count($results); $i++){
        //StartReading count
        if($results[$i]->startReading != null){
            $startReadingCount += $results[$i]->startReading;
            $startReadingHowMany++;
        }

        //ReadArticleCount
        if($results[$i]->readArticle != null){
            $readArticleCount += $results[$i]->readArticle;
            $readArticleHowMany++;
        }

        //pageBottomCount
       if($results[$i]->pageBottom != null){
            $pageBottomCount += $results[$i]->pageBottom;
            $pageBottomHowMany++;
       }

        //Scanned or readed
        if($results[$i]->type != null){
            if($results[$i]->type == "Scanner"){
                $scanned++;
            } else {
                $readed++;
            }
        }
    }

    //return data
    $node['nViewers'] = count($results);

    if($startReadingHowMany > 0){
        $node['startReadingPercentage'] = round($startReadingHowMany/$node['nViewers'], 3);
        $node['startReadingAfterAVG'] = round($startReadingCount/$startReadingHowMany, 3);
    }

    if($readArticleHowMany > 0){
        $node['readArticlePercentage'] = round($readArticleHowMany/$node['nViewers'], 3);
        $node['readArticleAfterAVG'] = round($readArticleCount/$readArticleHowMany, 3);
    }

    if($pageBottomHowMany > 0){
        $node['pageBottomPercentage'] = round($pageBottomHowMany/$node['nViewers'], 3);
        $node['pageBottomAfterAVG'] = round($pageBottomCount/$pageBottomHowMany, 3);
    }



    return $node;

}

/**
 * @param $uid
 */
function getTagUser($cid){
    ParseClient::initialize('P60EfTUuOZoeZyD2qSLpOrc8DWwUk2YjEqU2HY1R', 's3b2cfGtQhSFYM16ZIJQ7yXioTjt35Um5mn9SyP8', '3jz6CONqt5psS4UlGu3RB28ldIw311Iv2I8eA3Mh');

    $query = new ParseQuery("Reading");

    $query->equalTo("user",  $cid."");
    $query->limit(1000);
    $results = $query->find();

  // echo "<br><br><br><br><h2>  Retto ";
    //echo  print_r($results[99]->title) . "</h2>";


    $list = Array();

    for($t=0; $t<count($results); $t++){
    //  echo "wwwwwwidnefwdis jxkzurvaijsriafklns girwkovls " . $results[$t]->title;
        $title = $results[$t]->title;

        //echo "<h3> jjjj" . $title . "</h3>";

        $query2 = "SELECT name FROM taxonomy_term_data WHERE tid IN (
            SELECT field_tags_tid FROM field_data_field_tags WHERE entity_id IN (
                SELECT vid FROM node WHERE title = '". $title ."'
            )
        )";

        //echo "<h1>". $query2 . "</h1>";

        $tags = db_query($query2);

      //  print_r($tags);


        foreach ($tags as $record) {
            if(empty($list[$record->name])){
                $list[$record->name] = 1;
                //echo $record->name;
            } else {
                $list[$record->name] = $list[$record->name]+1;
              //  echo $record->name ." <---";
            }
        }
    }


    return $list;
}
