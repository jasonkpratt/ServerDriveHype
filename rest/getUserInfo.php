<?php



header("Content-Type:application/json");
$id = $_GET['id'];
$host = 'drivehype.db.4163804.hostedresource.com';
$userName = 'drivehype';
$pw = 'Capstone2014!';
$conn = mysql_connect($host,$userName,$pw);

//throw a window if we can't connect
if(!$conn){
die ('could not connect'.mysql_error());
}
//echo 'Connected!';
mysql_select_db("drivehype");
$query = "SELECT FirstName, LastName, Email, token FROM Users WHERE id =".$id;
$result = mysql_query($query);


if (!$result){echo "FAIL". mysql_error();}

else{
//echo 'SUCCESS';
while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  //$dName = $row['FirstName'];
  $dName = htmlspecialchars($row['FirstName'],ENT_QUOTES);
  //echo $dName;
  
  $dLast = $row['LastName'];
  $dEmail = $row['Email'];
  $dToken = $row['token'];
}
}
 
if(!empty($id)){
    del_response($dName,$dLast,$id,$dEmail,$dToken);
}
else{
    del_response_(400,"invalid",NULL,NULL,NULL);
}


function del_response($firstName,$lastName, $id, $email,$token){
    //$response array of html
    $response['firstName' ] = $firstName;
    $response['lastName']=$lastName;
    $response['id']=$id;
	$response['email'] = $email;
    $response['token']=$token;
    
    //$json_response is an array of json
    $json_response = json_encode($response);
    echo $json_response;
}
    


?>
