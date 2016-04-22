<?php
/**
* This is test code and doesn't work yet!
* Main reference https://twitteroauth.com/
* @since 	22/04/16
*/
    
function dump($var,$die=false){
	echo '<pre>' .print_r($var,1). '</pre>';
	if($die){die();}
}

//////////////////////////////////////////////////

session_start();

define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
define('OAUTH_CALLBACK', getenv('OAUTH_CALLBACK')); // fully-qualified callback url

require "../vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;


if( !isset($_SESSION['access_token']) ){

    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    
    if( !isset($_SESSION['oauth_token']) || !isset($_SESSION['oauth_token_secret']) ){
        /*
            There is not an authorized connection in this session.
            Get a request token from the Twitter API by redirecting to
            the Twitter oauth url.
            
            This is a token to request access for the app. It expires after a few minutes.
        */
    
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    
        if( !isset($request_token['oauth_callback_confirmed']) || $request_token['oauth_callback_confirmed'] !== 'true' ){
            die('Authentification URL refused by Twitter API.');
        }
        
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        
        header('Location:' . $connection->url('oauth/authorize', array('oauth_token' => $_SESSION['oauth_token'])));
    
    }
    
    
    /*
        Coming back from Twitter at this point. $_REQUEST will contain the oauth token because the user
        has given consent for this app to access his Twitter account.
        
        Pull the temporary oauth_token back out of sessions. If the oauth_token is different from the one you sent them 
        to Twitter with, abort the flow and don't continue with authorization.
    
    */
    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
    
    if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
        die('Abort! Something is wrong.');
    }
    
    
    /*
        Now we make a TwitterOAuth instance with the temporary request token.
    */
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
    
    
    /*
        Use the temporary request token to get the long lived access_token
    */
    $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);

    /*
        Store the long-lived access_token
    */
    $_SESSION['access_token'] = $access_token;
}

$access_token = $_SESSION['access_token'];

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$user = $connection->get('user');

dump($connection,1);