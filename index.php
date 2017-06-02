<?php

session_start();

require 'vendor/autoload.php';

#TT
//$client_id='dde6b4b46e8f42d893c31c4c81a891af';
//$client_secret='13527f7ffa2d453eb297069d80759e4d';

#Ben
$client_id="65988f8cab504e6d9f2d4ffbadd14dc0";
$client_secret="3f1064a50b844254aae45663369da971";
$redirect_uri='http://localhost:4000/social/instagram';
$access_token = null;

if(isset($_SESSION['access_token']))
{
    $access_token = $_SESSION['access_token'];
}

// STEP THREE: REGULAR REQUESTS WITH ACCESS_TOKEN
// ACCESS_TOKEN
$instagram = \Bencavens\Instagram\Instagram::init($client_id, $client_secret, $access_token);

if(!$access_token)
{
    $oauth = $instagram->oauth($redirect_uri);
    echo '<a href="'.$oauth->getAuthorizationUrl(['basic','public_content','likes','follower_list','relationships']).'">LOGIN</a>';

    if(isset($_GET['code']))
    {
        $_SESSION['access_token'] = $oauth->getAccessToken($_GET['code']);

        session_write_close();
    }

    die('FINISH');
}


/**
 * --------------------------------------------------------
 * MEDIA
 * --------------------------------------------------------
 */
// Media from user
//$media = $instagram->user(2456033649)->media()->limit(10)->get();
//
//// Liked media by you
////$media = $instagram->you()->likes()->limit(4)->get();
//
////var_dump($media);
//foreach($media as $medium)
//{
//    echo '<img src='.$medium->images->standard_resolution->url.'">';
//}
//
//die();

/**
 * --------------------------------------------------------
 * USERS
 * --------------------------------------------------------
 */
try{

    $media = $instagram->you()->media()->limit(5)->get();
//    $media = $instagram->user(2456033649)->media()->limit(1)->get();

//    $likes = $instagram->you()->likes()->get();
//
//    $user = $instagram->user()->search('thinktomorrowbe')->first();

//    var_dump($user);
//    echo '<h1>'.$user->username.'</h1>';
//    echo '<h3>'.$user->id.'</h3>';
//    echo '<p>'.$user->bio.'</p>';
//    echo '<img src="'.$user->profile_picture.'">';
//    echo '<hr>';

}
catch(\Bencavens\Instagram\Exceptions\OAuthAccessTokenException $e)
{
    unset($_SESSION['access_token']);
    echo('you should refresh the page');
}
//$users = $instagram->findUserByName('be')->data;

//$users = $instagram->you()->requestedBy()->get();

foreach($media as $medium)
{
    echo '<img src="'.$medium->images->standard_resolution->url.'"><br>';
    echo $medium->caption->text;
    echo '<hr>';
}

//$user = $instagram->user(2456033649)->first();
//$user = $instagram->user()->search('thinktomorrowbe')->first();
//var_dump($user);
//echo '<h1>'.$user->username.'</h1>';
//echo '<h3>'.$user->id.'</h3>';
//echo '<p>'.$user->bio.'</p>';
//echo '<img src="'.$user->profile_picture.'">';
//echo '<hr>';
//die();
foreach($users as $user)
{
    echo '<h1>'.$user->username.'</h1>';
    echo '<h3>'.$user->id.'</h3>';
//    echo '<p>'.$user->bio.'</p>';
    echo '<img src="'.$user->profile_picture.'">';
    echo '<hr>';
}

//var_dump($instagram->findUser('thinktomorrow'));
die('DUDE');
