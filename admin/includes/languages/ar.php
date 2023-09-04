<?php
function lang($phrase)
{
    static $lang = array(
        "massage" => "اهلا",
        "admin" => "ادمن"
    );
    return $lang[$phrase];
}