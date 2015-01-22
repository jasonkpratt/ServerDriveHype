<?php
session_start();
$token = $_SESSION['token'];
$gid = $_GET['id'];

class Request
	{
		var $_fp;
		var $_url;
		var $_host;
		var $_protocol;
		var $_uri;
		var $_port;
 
		private function _scan_url()
		{
			$req = $this->_url;
			$pos = strpos($req, '://');
			$this->_protocol = strtolower(substr($req, 0, $pos));
			$req = substr($req, $pos+3);
			$pos = strpos($req, '/');
			if($pos === false)
			{
				$pos = strlen($req);
			}
			$host = substr($req, 0, $pos);
			if(strpos($host, ':') !== false)
			{
				list($this->_host, $this->_port) = explode(':', $host);
			}
			else
			{
				$this->_host = $host;
				$this->_port = ($this->_protocol == 'https') ? 443 : 80;
			}
			$this->_uri = substr($req, $pos);
			if($this->_uri == '')
			{
				$this->_uri = '/';
			}
		}
 
		//constructor
		function Request($url)
		{
			$this->_url = $url;
			$this->_scan_url();
		}
 
		//download URL to string
		function DownloadToString()
		{
			$crlf = "\r\n";
			//generate request
			$response = '';
			$req = 'GET '.$this->_uri.' HTTP/1.0'.$crlf. 'Host: '.$this->_host.$crlf.
				'User-Agent: Mozilla/5.0 (Windows NT 6.0) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7'.$crlf.$crlf;
			$this->_fp = fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port);
			fwrite($this->_fp, $req);
			while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
			{
				$response .= fread($this->_fp, 1024);
			}
			fclose($this->_fp);
			//split header and body
			$pos = strpos($response, $crlf.$crlf);
			if($pos === false)
			{
				return $response;
			}
			$header = substr($response, 0, $pos);
			$body = substr($response, $pos + 2*strlen($crlf));
			//parse headers
			$headers = array();
			$lines = explode($crlf, $header);
			foreach($lines as $line)
			{
				if(($pos = strpos($line, ':')) !== false)
				{
					$headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));
				}
			}
			//redirection
			if(isset($headers['location']))
			{
				$http = new HTTPRequest($headers['location']);
				return($http->DownloadToString($http));
			}
			return $body;
		}
 
		function DownloadHeadersOnly()
		{
			$crlf = "\r\n";
			//generate request
			$response = '';
			$req = 'GET '.$this->_uri.' HTTP/1.0'.$crlf.'Host: '.$this->_host.$crlf.$crlf;
			$this->_fp = fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port);
			fwrite($this->_fp, $req);
			while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
			{
				$response .= fread($this->_fp, 1024);
			}
			fclose($this->_fp);
			//split header and body
			$pos = strpos($response, $crlf.$crlf);
			if($pos === false)
			{
				return $response;
			}
			$header = substr($response, 0, $pos);
			$body = substr($response, $pos + 2*strlen($crlf));
			$headers = array();
			$lines = explode($crlf, $header);
			foreach($lines as $line)
			{
				if(($pos = strpos($line, ':')) !== false)
				{
					$headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));
				}
			}
			return $headers;
		}
	}


///////////////////////////////////////////////////////
$accessToken = $_POST['accessToken'];


//echo 'token is set for user session';

//make API REST CALL TO FACEBOOK HERE

$url = "https://graph.facebook.com/me?fields=id,first_name,last_name,email&access_token=$token";
$req = new Request($url);
$d = $req->DownloadToString();
$meObject = (json_decode($d,true));
//echo $meObject['id'];


$fName = $meObject['first_name'];

$lName = $meObject['last_name'];

$id = $meObject['id'];

$email = $meObject['email'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Title</title>
<meta charset="utf-8"/>
<link href="css/reset.css" rel="stylesheet" type="text/css"/>
<link href="css/960.css" rel="stylesheet" type="text/css"/>
<link href="coolMenu.css" rel="stylesheet" type="text/css" media="screen"/>
<script type="text/javascript" src="js/modernizr-1.6.min.js"></script>

<style>


</style>


</head>

<body>

<div class="container_16">
		<div class="grid_16">
		
		<h1>DriveHype</h1>
		
		<div id="status" >
		
		<?php echo $fName." ".$lName; ?><br>
		<?php echo $id; ?><br>
		<?php echo $token; ?><br>
		
</div>
		<ul id="coolMenu">

  
 <li><a href="">Me</a>
<ul class="noJS">
            <li><a href="#">Profile</a></li>
            <li><a href="#">Activity</a></li>
            <li><a href="Account.html">Account</a></li>
        </ul></li>



 <li><a href="#">Friends</a>
<ul>
            <li><a href="#">List</a></li>
            <li><a href="#">Message</a></li>
            <li><a href="#">Online</a></li>
        </ul></li>


 <li><a href="#">Media</a><ul>
            <li><a href="albums.html">Photos</a></li>
            <li><a href="#">Trending</a></li>
            <li><a href="#">Sharing</a></li>
        </ul></li>
 <li><a href="#">Groups</a><ul>
            <li><a href="#">join</a></li>
            <li><a href="#">list</a></li>
            <li><a href="#">find</a></li>
        </ul></li>
<li><a href="#">Explore</a><ul>
            <li><a href="#">Community</a></li>
            <li><a href="#">Discussion</a></li>
            <li><a href="#">DriveHype</a></li>
        </ul></li>
<li><a href="#">Help</a><ul>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">about</a></li>
            <li><a href="#">contact us</a></li>
        </ul></li>
</ul>  


</div>
		
		</div>
<img src="logo.png" alt="Mountain View" style="width:950px;height:50px">


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript" src="scripts.js"></script>
<script>





  // This is called with the results from from FB.getLoginStatus().
/*  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      var accessToken = response.authResponse.accessToken;
      var text="";
      
      testAPI(accessToken);
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

   window.fbAsyncInit = function() {
    FB.init({
      appId      : '334273433411246',
      xfbml      : true,
      version    : 'v2.1'
    });
    

FB.login(function(){
console.log('get pictures');
FB.api(
    "/me/photos/uploaded",
    function (response) {
      if (response && !response.error) {
        /* handle the result */
    //  }
    //}
//);

//}, { scope: 'publish_stream, email, user_likes, user_birthday, user_location user_friends user_photos publish_actions' });






  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  //FB.getLoginStatus(function(response) {
    //statusChangeCallback(response);
  //});

  //};

  // Load the SDK asynchronously
  //(function(d, s, id){
     //var js, fjs = d.getElementsByTagName(s)[0];
     //if (d.getElementById(id)) {return;}
     //js = d.createElement(s); js.id = id;
     //js.src = "http://connect.facebook.net/en_US/sdk.js";
     //fjs.parentNode.insertBefore(js, fjs);
   //}(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
 /* function testAPI(accessToken) {
    console.log('Welcome!  Fetching your information.... ');
    
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        '<p></p>You are logged in as ' + response.name + '<p></p>';
      
    });
    
 
  }*/
</script>
</body>
</html>

