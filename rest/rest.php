<?php

error_reporting(E_ALL); // Report on all errors
ini_set('display_errors', '1');
session_start();
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();




$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

/*$app->post('/addUser', function() use ($app){
//$request = \Slim\Slim::getInstance()->request();
//echo json_encode($request);
$request = $app->request->post('response');
echo json_encode($request);
});*/

$app->post('/getAlbumInfo', function() use ($app){


$request = $app->request->post();
$token = $request['accessToken'];
$albumID = $request['id'];

$url = "https://graph.facebook.com/$albumID?fields=id,name,count,privacy,created_time,description&access_token=$token";
$response = file_get_contents($url);
//$result = json_decode($response,true);

echo stripslashes($response);
});

$app->post('/getAlbumPhotos', function() use ($app){
//$request = \Slim\Slim::getInstance()->request();
//echo json_encode($request);


$request = $app->request->post();
$token = $request['accessToken'];
$albumID = $request['id'];
//echo $request;
$url = "https://graph.facebook.com/$albumID/photos?&access_token=$token";
$response = file_get_contents($url);
//$result = json_decode($response,true);
//echo $url;
echo $response;
});

$app->post('/getUserAlbums', function() use ($app){
//$request = \Slim\Slim::getInstance()->request();
//echo json_encode($request);
//$request = $app->request->post('response');
//echo json_encode($request);

$request = $app->request->post('accessToken');
//$token = $request['accessToken'];
//$albumID = $request['response'];

$url = "https://graph.facebook.com/me/albums?fields=id,name,count,privacy,created_time,description&access_token=$request";
$response = file_get_contents($url);
//$result = json_decode($response,true);

echo $response;
});

$app->post('/createAlbum', function() use ($app){
//$request = \Slim\Slim::getInstance()->request();
//echo json_encode($request);
$request = $app->request->post();
$token = $request['accessToken'];
//$album = $request['id'];

//$url = "https://graph.facebook.com/$photoID?method=delete&access_token=$token";
$url = "https://graph.facebook.com/me/albums?name=test2&message=test2&privacy.value=SELF&method=POST&access_token=$token";
//echo $url;
$response = file_get_contents($url);
$result = json_decode($response,true);
echo $result['id'];
});

$app->post('/addPhoto', function() use ($app){
//$request = \Slim\Slim::getInstance()->request();
//echo json_encode($request);
$request = $app->request->post();
$token = $request['accessToken'];
$albumID = $request['id'];
$photoURL = $request['url'];

$url = "https://graph.facebook.com/$albumID/photos?message=test2&url=$photoURL&method=POST&access_token=$token";
$response = file_get_contents($url);
$result = json_decode($response,true);
echo $result['id'];

});


$app->post('/addFormPhoto', function() use ($app){
//$request = \Slim\Slim::getInstance()->request();
//echo json_encode($request);
$request = $app->request->post();
$token = $request['accessToken'];
$albumID = $request['id'];
$source = $request['source'];

$url = "https://graph.facebook.com/$albumID/photos?message=test2&source=$source&method=POST&access_token=$token";
$response = file_get_contents($url);
$result = json_decode($response,true);
echo $result['id'];

});

$app->post('/deletePhoto', function() use ($app){
//$request = \Slim\Slim::getInstance()->request();
//echo json_encode($request);
//$request = $app->request->post('response');
//$data = $request['success'];
//echo $data;

$request = $app->request->post();
$token = $request['accessToken'];
$photoID = $request['response'];

//$url = "https://graph.facebook.com/$photoID?method=delete&access_token=$token";
//$response = file_get_contents($url);
//$result = json_decode($response,true);

echo $token;
});


$app->run();
?>