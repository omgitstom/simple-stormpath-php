<?php
require 'vendor/autoload.php';

//Simple variables to initialize - Please change these
$apiKeyFile = '/Users/tom/.stormpath/apiKey.properties';
$app_href = 'https://api.stormpath.com/v1/applications/3QIMlJKKN2whGCYzXXw1t8';

//Initialize the client for Stormpath
$builder = new \Stormpath\ClientBuilder();
$client = $builder->setApiKeyFileLocation($apiKeyFile)->build();

/*
  When there is a POST, we want to get the application 
  (which was the app href above), get the POST variable 
  from the request, and create an account with them.

*/
if($_SERVER['REQUEST_METHOD'] == 'POST') {

	$application = $client->
               dataStore->
               getResource($app_href, \Stormpath\Stormpath::APPLICATION);
	
	$account = $client->dataStore->instantiate(\Stormpath\Stormpath::ACCOUNT);
	$account->email = $_POST['email'];
	$account->password = $_POST['password'];
	$account->givenName = $_POST['firstName'];
	$account->surname = $_POST['lastName'];

	try{
		$account = $application->createAccount($account);
	}catch(\Stormpath\Resource\ResourceError $re){
		$error = $re->getMessage();
	}
}
?>

<html>
<head>
</head>
<body>

	<form class="form-horizontal" method="POST" target="_self">
		<fieldset>

		<!-- Form Name -->
		<legend>Register User</legend>

		<!-- Text input-->
		<div class="control-group">
		  <label class="control-label" for="firstName">First Name</label>
		  <div class="controls">
		    <input id="firstName" name="firstName" type="text" placeholder="First Name" class="input-xlarge">
		    
		  </div>
		</div>

		<!-- Text input-->
		<div class="control-group">
		  <label class="control-label" for="lastName">Last Name</label>
		  <div class="controls">
		    <input id="lastName" name="lastName" type="text" placeholder="Last Name" class="input-xlarge">
		    
		  </div>
		</div>

		<!-- Text input-->
		<div class="control-group">
		  <label class="control-label" for="email">Email</label>
		  <div class="controls">
		    <input id="email" name="email" type="text" placeholder="something@something.com" class="input-xlarge" required="">
		    
		  </div>
		</div>

		<!-- Password input-->
		<div class="control-group">
		  <label class="control-label" for="password">Password</label>
		  <div class="controls">
		    <input id="password" name="password" type="password" placeholder="******" class="input-xlarge">
		    
		  </div>
		</div>
		<div class="control-group">

		  <div class="controls">
		    <input type="submit" value="Submit">
		  </div>
		</div>
		</fieldset>
	</form>
	<!--If post and account is created, display some info -->
	<?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error)): ?>
	<div>
		<strong><span>Account HREF</span>: <span><?=$account->href ?></span> was created!</strong>
	</div>
	<?php else: ?>
	<div>
		<strong><span>Error</span>: <span><?=$error?></span></strong>
	</div>
	<?php endif ?>
</body>
</html>
