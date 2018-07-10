<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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
                $info = curl_getinfo($curl);
                curl_close($curl);
                //dd($httpcode);//200 is good
                //dd($info);
                
                return $result;
            }
    
        //    $username = 'burntspaghetti';
            $username = 'taylorotwell';
            $searchURL = "https://api.github.com/users/". $username . "/followers";
            $followers = json_decode(curl($searchURL));

            $output="";
            if($followers)
            {
                foreach ($followers as $follower) {

                    $output.=
                        '<tr>'.
                            '<td>'.$follower->avatar_url.'</td>'.
                        '</tr>';

                }

                return Response($output);
            }
        }
    }
}
