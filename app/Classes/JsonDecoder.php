<?php

namespace App\Classes;

class JsonDecoder
{
    public function decodeJson($jsonFile)
    {
        //Get the jsonFile contents
        $jsonString = file_get_contents($jsonFile);

        //Decode the jsonString and return the stdClass objects array
        $decodedJson = json_decode($jsonString, true);
        return $decodedJson;
    }

}