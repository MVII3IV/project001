<?php


    header('Content-Type: application/json; charset=utf-8');

    require_once('config.php');
    require_once('twitteroauth/autoloader.php');

    use Abraham\TwitterOAuth\TwitterOAuth;


    // settings
    $settings = $twitter_settings;

    $username = ( isset($_GET["username"]) && !empty($_GET["username"]) ) ? trim( $_GET["username"] ) : $settings['username'] ;
    $count = ( isset($_GET["count"])  && !empty($_GET["count"]) ) ? trim( $_GET["count"] ) :  $settings['count'] ;

    // Making an object to access our library class
    $twitter = new TwitterOAuth( $settings['consumer_key'] , $settings['consumer_secret'] , $settings['oauth_access_token'] , $settings['oauth_access_token_secret']);

    $data = $twitter->get("statuses/user_timeline", array("count" => $count , "screen_name" => $username ));
    $json = [];

    if ($data) {
        
        $data = objectToArray($data);


        if (!isset($data["errors"])) {

            foreach ($data as $key => $value) {
                $json[$key]["date"] = time_elapsed_string(date( 'Y-m-d H:i:s', strtotime($value["created_at"])) );
                $json[$key]["tweet"] = tweet_html_text($value);
            }

        }else{
            $json[0]["date"] = "";
            $json[0]["tweet"] = $data["errors"]["0"]["message"];
        }

    }else{
        $json[0]["date"] = "";
        $json[0]["tweet"] = "";
    }


    echo json_encode($json);




function tweet_html_text(array $tweet) {
    $text = $tweet['text'];

    // hastags
    $linkified = array();
    foreach ($tweet['entities']['hashtags'] as $hashtag) {
        $hash = $hashtag['text'];

        if (in_array($hash, $linkified)) {
            continue; // do not process same hash twice or more
        }
        $linkified[] = $hash;

        // replace single words only, so looking for #Google we wont linkify >#Google<Reader
        $text = preg_replace('/#\b' . $hash . '\b/', sprintf('<a href="https://twitter.com/search?q=%%23%2$s&src=hash" target="_blank">#%1$s</a>', $hash, urlencode($hash)), $text);
    }

    // user_mentions
    $linkified = array();
    foreach ($tweet['entities']['user_mentions'] as $userMention) {
        $name = $userMention['name'];
        $screenName = $userMention['screen_name'];

        if (in_array($screenName, $linkified)) {
            continue; // do not process same user mention twice or more
        }
        $linkified[] = $screenName;

        // replace single words only, so looking for @John we wont linkify >@John<Snow
        $text = preg_replace('/@\b' . $screenName . '\b/', sprintf('<a href="https://www.twitter.com/%1$s" title="%2$s" target="_blank">@%1$s</a>', $screenName, $name), $text);
    }

    // urls
    $linkified = array();
    foreach ($tweet['entities']['urls'] as $url) {
        $url = $url['url'];

        if (in_array($url, $linkified)) {
            continue; // do not process same url twice or more
        }
        $linkified[] = $url;

        $text = str_replace($url, sprintf('<a href="%1$s" target="_blank">%1$s</a>', $url), $text);
    }

    return $text;
}


function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }
 
        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
    }


function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}