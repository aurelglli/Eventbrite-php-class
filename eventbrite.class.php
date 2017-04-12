<?php
/*
    Copyright 2017 Aurélien Girelli (aka aurelglli) aurelien@daylug.fr
    Licensed under the Apache License, Version 2.0 (the "License"); you may not
    use this file except in compliance with the License. You may obtain a copy of
    the License at
    http://www.apache.org/licenses/LICENSE-2.0
    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
    WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
    License for the specific language governing permissions and limitations under
    the License.
 */
class eventbrite
  {
    const ACCOUNT_PASSWORD = '';
    const KEY_ID = '';
    const KEY_SECRET = '';
    function __construct()
      {
        switch ($_GET[state])
        {
            case "authorized":

                $getTokens = self::getTokens();
		print_r('$getTokens');
                break;
            default:
                header("Location: " . self::getAuthorizeLink());
        }
      }
    public function getAuthorizeLink()
      {
        $url = 'https://www.eventbrite.com/oauth/authorize?response_type=code';
        $url .= '&client_id=' . self::KEY_ID;
        return $url;
      }
    public function getTokens()
      {
        $o = array('Content-type: application/x-www-form-urlencoded');
        $postfields = array(
            'code' => $_GET[code],
            'client_secret' => self::KEY_SECRET,
            'client_id' => self::KEY_ID,
            'grant_type' => 'authorization_code'
        );
        
        return self::call('https://www.eventbrite.com/oauth/token', $o, $postfields);
      }
    public function call($url, $o, $postfields)
      {
	    
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_VERBOSE, 0);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        if ($postfields)
          {
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, $postfields);
          }
          
        return json_decode(curl_exec($c));
      }
    public function test($access_token)
      {
        $o = array(
            'Authorization: Bearer ' . $access_token
        );
        return self::call('https://www.eventbriteapi.com/v3/users/me/?token='.$access_token, $o);
      }

  }
new eventbrite();
?>
