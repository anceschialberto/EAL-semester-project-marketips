<?php
require 'vendor/parse/php-sdk/autoload.php';
require_once 'analytics.php';
require_once 'mail/send.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;

$users = db_query("SELECT uid, mail, name FROM users");
$oneWeekAgo =  time()-(60*60*24*7);
$articles = db_query("SELECT nid, title FROM node WHERE type='article' AND created>'" . $oneWeekAgo . "' ORDER BY created");

print_r($articles);

foreach ($users as $user) {
    $tags = getTagUser($user->uid);

    echo"<br> 222222 ". $user->mail ."<br>";
    print_r($tags);

    echo"<br>";
    echo"<br>";
    echo"<br>";

    $toSend = Array();

    foreach ($tags as $key => $value){
        print ($key. "<br>");

        $articles = db_query("SELECT nid, title FROM node WHERE type='article' AND created>'" . $oneWeekAgo . "' ORDER BY created");
        foreach($articles as $article){


            print_r($key);
            echo"<br> 3";
            echo"<br>";
            echo"<br>";

            $newArticleTags = getTag($article->title);
            print_r($newArticleTags);

            echo "-----<br>";
            print_r($newArticleTags);
            echo "-----<br>";

            foreach($newArticleTags as $arTag => $numTag){
                echo $key . "    vs    " . $arTag ."<br>";
                if($arTag == $key){
                    if(!in_array($article->nid, $toSend)){
                        array_push($toSend, $article->nid);
                    }
                    echo "ta daaaa " . $article->nid ."<br>";
                }
            }
        }
    }
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>******<br>";
    print_r($toSend);
    echo "<br>******<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";

    $message = "
            <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
            <html xmlns=\"http://www.w3.org/1999/xhtml\">
             <head>
              <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
              <title>Demystifying Email Design</title>
              <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
            </head>
            <body style=\"margin: 0; padding: 0;\">
            <table align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">
             <tr>
              <td>
               Row 1
              </td>
             </tr>
             <tr>
              <td>
               Row 2
              </td>
             </tr>
             <tr>
              <td>
               Row 3
              </td>
             </tr>
            </table>
            </body>
            </html>

        ";

    echo "<br> rrr" . $user->mail . "    " . $user->name . " ppp";

    print_r($user);


    //Send the email
    if($user->uid!=0 || $user->name != "" || $user->mail!=null){
        send($user->mail, $message, $user->name);
    }

    echo "gay";


}