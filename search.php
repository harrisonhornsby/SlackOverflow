<?php

	require_once("session.php");
  require_once("dbconfig.php");
	
	require_once("class.user.php");
	$auth_user = new USER();
	$user_id = $_SESSION['user_session'];
	
	$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
  $user_is_guest = $userRow['is_guest'];

  // SQL CONNECTION  
$servername = "localhost";
$username = "admin";
$password = "M0n@rch$";
$dbname = "slackoverflow";
$conn = new mysqli($servername, $username, $password, $dbname);
	
// PRINT CHECK/X IF Q HAS BEEN ANSWERED
function printCheck()
{
  return "<span class='glyphicon glyphicon-ok'></span>";
}
function printX()
{
  return "<span class='glyphicon glyphicon-remove'></span>";
}
function Solved($solved)
{

  if($solved == 1)
  {
    return printX();
  }
  else{
    return printCheck();
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
  <script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script>
  <link rel="stylesheet" href="style.css" type="text/css"  />
  <title>SlackOverflow - <?php print($userRow['user_email']); ?></title>
</head>

<body>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="logo" href="home.php">SlackOverflow</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
      </ul>
      <ul class="nav navbar-nav navbar-right">

        <li class="dropdown">
          <a href="#" id="dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            
            <span class="glyphicon glyphicon-user"></span>&nbsp;Hello 
              <?php 
                  if($user_is_guest == FALSE) {
                    echo $userRow['user_email'];}
                  else{
                    echo " Guest";
                  }

              ?>&nbsp;
              <span class="caret">
              </span>
          </a>
          
          <ul class="dropdown-menu">
            <?php 
                  if($user_is_guest == FALSE) 
                  {
                    echo "<li><a href=\"profile.php\"><span class=\"glyphicon glyphicon-user\"></span>&nbsp;View Profile</a></li>";
                  }
            
            if($user_is_guest == TRUE)
            {
              echo "<li><a href=\"index.php?home=yes\"><span class=\"glyphicon glyphicon-off\"></span>&nbsp;Sign In</a></li>";
            }

            if($auth_user->is_loggedin() and $user_name != "guest" and $user_is_guest == FALSE)
            {
              echo "<li><a href=\"logout.php?logout=true\"><span class=\"glyphicon glyphicon-log-out\"></span>&nbsp;Sign Out</a></li>";
            }
            ?>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>

	<div class="clearfix"></div>
	
    <div class="container-fluid" style="margin-top:80px;">
	
    <div class="container">
    
        <h1 id="secondLevelLinks">
          <a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a> &nbsp; 
          <a href="ask.php"><span class="glyphicon glyphicon-question-sign"></span> Ask</a>
          <a href="profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a>
          <a href="browse.php"><span class="glyphicon glyphicon-eye-open"></span> Browse</a>
          <a href="help.php"><span class="glyphicon glyphicon-book"></span> Help</a>
          <a href="search.php"><span class="glyphicon glyphicon-search"></span> User Search</a>
          <?php if($_SESSION['isAdmin']) echo '<a href="admin.php"><span class="glyphicon glyphicon-cog"></span> AdminCP</a>'; ?>
          </h1>
        
        <?php if(!$user_is_guest)echo '
        <div class="container">  
                <br />  
                <h2 align="center">Search For a User</h2><br />  
                <div class="form-group">  
                     <div class="input-group">  
                          <span class="input-group-addon">Search</span>  
                          <input type="text" name="search_text" id="search_text" placeholder="Enter A Username" class="form-control" />  
                     </div>  
                </div>  
                <br />  
                <div id="result"></div>  
           </div>
           ';
           else{
            echo "<h3><hr>Guests cannot perform a search</h3>";
            } ?>  
      

      </body>  
 </html>  
 <script>  
 $(document).ready(function(){  
      $('#search_text').keyup(function(){  
           var txt = $(this).val();  
           if(txt != '')  
           {  
                $.ajax({  
                     url:"searchDatabase.php",  
                     method:"post",  
                     data:{search:txt},  
                     dataType:"text",  
                     success:function(data)  
                     {  
                          $('#result').html(data);  
                     }  
                });  
           }  
           else  
           {  
                $('#result').html('');                 
           }  
      });  
 });  
 </script> 

    </div>

</div>


<script src="bootstrap/js/bootstrap.min.js"></script>

</body>

</html>