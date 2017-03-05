<?php

//Enter your Client ID here.
$clientId = "REPLACE_WITH_THE_CLIENT_ID_OF_YOUR_HUBSPOT_APP";

//If you want, you can customize the permissions that your app will ask your user for. It is currently set to request all available permissions. You can view all available permissions at http://developers.hubspot.com/docs/methods/auth/initiate-oauth.
$scopeArray = array(
	"offline",
	"contacts-rw",
	"blog-rw",
	"events-rw",
	"keyword-rw"
);




//Get the url of the current page, which we will use later.
$currentUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


//If the form has not been submitted, and there's not access token to retrieve from the URL, then show the form.
if($_SERVER['REQUEST_METHOD'] != 'POST' && !isset($_GET['access_token'])){
?>

	<form method="post" action="<?php echo $currentUrl; ?>">
		<label>Enter your Hub ID:</label><br>
		<input type="text" name="portalId"><br>
		<button type="submit">Submit</button>
	</form>

<?php	
}

//When the user submits the form, get the portal ID that they entered and redirect them so they can authorize your app.
elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
	$portalId = $_POST['portalId'];
	$scopeStringWithPlusSigns = implode("+", $scopeArray);
	
	header ("Location: https://app.hubspot.com/auth/authenticate?client_id=$clientId&portalId=$portalId&redirect_uri=$currentUrl&scope=$scopeStringWithPlusSigns");
	exit;
}

//Once the user has authorized your app, set the access token as a session variable and then move on to whatever script you would like to run.
elseif(isset($_GET['access_token'])){
	session_start();
	$_SESSION['hubspotAccessToken'] = $_GET['access_token'];
	
	echo "Your access token is: " . $_SESSION['hubspotAccessToken'];
	
	//The authorization process is complete.
	//Enter your script here.

}

?>