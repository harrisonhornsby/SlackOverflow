<?php
session_start();

		$servername = "localhost";
        $username = "root";
        $password = "odu2017";
        $dbname = "slackoverflow";
        
        $conn = new mysqli($servername, $username, $password, $dbname);
		
		if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
		} 
		if(!$conn->connect_error)
		{
			echo "Connection OKAY.";
		}

		$q_id = $_GET["q_id"];

		echo "       ".$q_id;

		$sql = "SELECT question_title, question, question_id, asker_id, answer_id, user_id, user_name, is_solved FROM questions join users on asker_id=user_id
			WHERE question_id=$q_id";
        //Store collection of rows in variable called result
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        

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
<title>welcome - <?php print($userRow['user_email']); ?></title>
</head>

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
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hello <?php echo $userRow['user_email']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li>
                <li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
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
          <a href="profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a>
          <a href="browse.php"><span class="glyphicon glyphicon-eye-open"></span> Browse</a>
          </h1>
                
       	<hr />

<body>
	
	<h1 id="questionAnswerPage"><?php echo $row['question_title']; ?></h1>
	<h3 id="questionAnswerPage"><?php echo $row['question']; ?></h1>
	<h4>asked by <?php echo $row['user_name']; ?></h3>

	
<!--PREVIOUS ANSWERS TABLE BELOW-->
	
		
	<?php

	$sql = "SELECT answer_id, answer, responder_id, user_name 
			FROM answers JOIN users WHERE question_id=$q_id and responder_id=user_id";

	        //Store collection of rows in variable called result
        $result = $conn->query($sql);
        
        while($row = $result->fetch_assoc())
        {
        	echo "<hr><table>";
        	echo "<tr>
        		<td id=\"answerTD\"><p id=\"responseBody\">".$row["answer"]."</p></td>
        		<td id=\"answerTD\"> <span class=\"glyphicon glyphicon-chevron-up\"></span></td>
        		</tr>";
        	echo"<tr>
        			<td><div align=\"right\">".$row["user_name"]."</div>
        		</tr>
        	";
        	echo"</table>";
        	/*echo "<tr>
        		<td>".$row["answer"]."</td><tr><td>".$row["user_name"].
        		"<td><a href=\"#\" class=\"btn btn-info btn-lg\"><span class=\"glyphicon glyphicon-chevron-up\"></span> Vote Up</a></td>
        		<td>3 Upvotes</td>

        	</tr>";




        	*/
        }

        

        




        if ($conn->query($sql) === FALSE) {
    	    	echo "Error: " . $insert_query . "<br>" . $conn->error;
		}


	
    ?>
	</table>

	<hr>

	<center>
<!--LOGGED IN USERS RESPONSE ENTRY BELOW-->
	<h3>Your Response:</h3>

	<form class="form" method="post" action="postAnswer.php">
	<p class="Answer Body">
        <textarea  name="answerBody" id="answerBody" placeholder="Enter your answer here." rows="4" cols="50"/></textarea>
    </p>

    <input type="hidden" name="questionID" id="questionID" value="<?php echo $q_id ?>"/>

    <p class="submit">
        <input type="submit" value="Post Response">
    </p>
    
    </form>
    </center>


<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>