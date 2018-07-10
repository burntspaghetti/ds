<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('search', 'GithubController@search');



Route::get('/test2', function()
{
    function curl($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Accept: application/vnd.github.v3+json'
            )
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_USERAGENT, 'cURL');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING , "gzip");
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        
        return $result;
    }

//    $username = 'burntspaghetti';
    $username = 'taylorotwell';
    $searchURL = "https://api.github.com/users/". $username . "/followers";
    $followers = json_decode(curl($searchURL));

    dd($response);

    return $response;
});