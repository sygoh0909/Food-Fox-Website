<?php
function cookie(){
    session_start();
    //check if cookie is set here
    if (isset($_COOKIE['visitCount'])) {
        //if yes, then increase 1
        $visitCount = $_COOKIE['visitCount'] + 1;
    } else {
        //if not then set initial one
        $visitCount = 1;
    }
    //set cookie
    setcookie('visitCount', $visitCount, time() + (86400 * 30), "/"); //expire in 30 days
    return $visitCount; //return the variable so that can be used by other pages
}
?>
