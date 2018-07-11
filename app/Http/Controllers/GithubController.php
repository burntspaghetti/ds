<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class GithubController extends Controller
{
    //pagination issue:
    //https://stackoverflow.com/questions/32935242/github-api-user-followers-paging
    public function search(Request $request)
    {
        if($request->ajax())
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
//                $info = curl_getinfo($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);//get status code
                curl_close($curl);
//                dd($httpcode);//200 is good
                //dd($info);
                
                return $result;
            }
    
        //    $username = 'burntspaghetti';
            $username = $request->search;
            Session::put('username', $username);

            //1. get follower count:
            $getFollowerCountURL = "https://api.github.com/users/". $username;
            $followerCountResponse = json_decode(curl($getFollowerCountURL));
            $followerCount = $followerCountResponse->followers;

            //2. get followers
            $searchURL = "https://api.github.com/users/". $username . "/followers?per_page=100";
            $followers = json_decode(curl($searchURL));
            $userInfoArray = ['user_info' => '<p>' . $username . " - ". $followerCount . " followers" . '</p>'];

            //3. go ahead and see if theres another page with results, in order to populate button in view


            $output="";
            if($followers)
            {
                foreach ($followers as $follower) {

                    $output.= '<img alt="thumbnail" height="42" width="42" src="'.$follower->avatar_url.'">';

                }
                
                $rows = ['row_data' => $output];

                return Response(array_merge($userInfoArray, $rows));
            }
        }
    }

    public function loadMore(Request $request)
    {
        if($request->ajax())
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
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);//get status code
                curl_close($curl);

                return $result;
            }

            //    $username = 'burntspaghetti';
            $username = Session::get('username');


            //2. get followers
            $searchURL = "https://api.github.com/users/". $username . "/followers?per_page=100&page=" . $request->page_counter;
            $followers = json_decode(curl($searchURL));

            //3. go ahead and see if theres another page with results, in order to populate button in view


            $output="";
            if($followers)
            {
                foreach ($followers as $follower) {

                    $output.= '<img alt="thumbnail" height="42" width="42" src="'.$follower->avatar_url.'">';

                }

                return Response($output);
            }
        }
    }
}
