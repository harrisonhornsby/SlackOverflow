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

function setGravatarBool()
{
  if (isset($_POST['use_gravatar']) && ($_POST['use_gravatar'] == "yes")) 
  {
    $query .= "yes";
  }
  else
  {
    $query .= "no";
  }

}

  function get_gravatar( $email, $s = 120, $d = 'identicon', $r = 'x', $img = true, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
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

function getUsersEmail($userId)
{
  $servername = "localhost";
$username = "admin";
$password = "M0n@rch$";
$dbname = "slackoverflow";
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT user_email FROM users WHERE user_id=$userId";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$email = $row["user_email"];

return $email;
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
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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
        <hr />

        <?php

          //Set externalUser bool to true, this controls how profile is displayed
          $isExternalProfileViewer = FALSE;
          if (isset($_REQUEST['ext_user']))
          {
            $ext_user_id = $_GET["ext_user"];
            $ext_user_name = $_GET["ext_user_name"];

            //echo $ext_user_name;
            $isExternalProfileViewer = TRUE;
          }
          
          if($user_is_guest == FALSE or $user_is_guest && $isExternalProfileViewer)
          {
          
            if(!$isExternalProfileViewer)
            {
              echo "<table>
            <tr><td>
            <label class=\"h2\">".$userRow['user_name']."</label><td></tr>";
            }else{
            echo "<table>
            <tr><td>
            <label class=\"h2\">".$ext_user_name."</label><td></tr>";
            }
            
            if(!$isExternalProfileViewer)
              {
                $sql = "SELECT * FROM `images` WHERE avatar_user_id=$user_id";
              }
              else{
                $sql = "SELECT * FROM `images` WHERE avatar_user_id=$ext_user_id";
              }


            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            ////Check to see if user has uploaded photo already
            $UserHasPhoto = FALSE;
            if(mysqli_num_rows($result) > 0)
            {
            $UserHasPhoto = TRUE;
            }
            $_SESSION["UserHasPhoto"] = $UserHasPhoto;
          
          if($UserHasPhoto) echo '<tr><td><img style="width:128px;height:128px" src="data:image/jpeg;base64,'.base64_encode( $row['data'] ).'"/></td><tr>';

          if(!$UserHasPhoto && !$isExternalProfileViewer)
          {
          echo "<b><h3>Please select a profile photo.</h3></b>";
          echo "<tr><td>
          <form method=\"post\" enctype=\"multipart/form-data\" action=\"upload.php\">
          <input type=\"file\" name=\"image\" />
          <input type=\"submit\" />
          </form></td></tr>
          </table>";
        }
          
          }
          else{
            echo "Guests have no profile!";
          }

          ////// DISPLAY USERS QUESTIONS BELOW

          $targetId = "";

          if(!$isExternalProfileViewer){
            $sql = "SELECT question_title, question, question_id, asker_id, answer_id, user_id, user_name, is_solved, num_upvotes, num_downvotes
                FROM questions 
                join users 
                on asker_id=user_id
                WHERE asker_id = $user_id
                order by num_upvotes-num_downvotes desc";

                $targetId = $user_id;
          }
          else{
            $sql = "SELECT question_title, question, question_id, asker_id, answer_id, user_id, user_name, is_solved, num_upvotes, num_downvotes
                FROM questions 
                join users 
                on asker_id=user_id
                WHERE asker_id = $ext_user_id
                order by num_upvotes-num_downvotes desc";

                $targetId = $ext_user_id;
          } 
            

        //Store collection of rows in variable called result
        $result = $conn->query($sql);

        $email = getUsersEmail($targetId);
        echo "<h1>Gravatar:</h1>";
        echo get_gravatar($email);
        echo "<br>E-mail reigstered with Gravatar: ".$email;

        if(!$isExternalProfileViewer)//If you are viewing your own profile then allow user to pick which avatar to use grav or not
        {
            
            echo '
            <form action="updateGravatarBool.php?id='.$targetId.'" method="post">
              <br><input type="radio" name="use_gravatar" value="1"> Use Custom<br>
                  <input type="radio" name="use_gravatar" value="0"> Use Gravatar
              <br><input type="submit" name="submit" value="Save Changes">
            </form>

            ';

            updateGravatarBool($targetId);
        }
        
        ?>


        <?php

        //echo '<img src='.get_gravatar($email).'/> ';

         /*<div>
        <?php echo getUsersEmail($userId); ?>
          <img src="https://s.gravatar.com/avatar/e6df65b079187d467dc5b1718cda9e5d?s=80" />
          <?php echo "<br>Gravatar official: https://s.gravatar.com/avatar/e6df65b079187d467dc5b1718cda9e5d?s=80" ?>
          <?php echo "<br>What is returned from function: ".get_gravatar("harrisonhornsby@gmail.com") ?>
          
        </div>*/


        
                echo "<table class=\"questionTable\"> 
                <th class=\"header\">Questions</th>
                <th class=\"header\">Asker</th>
                <th class=\"header\">Solved</th>
                <th class=\"header\">Score</th>";


          if($result->num_rows > 0)
        {
                 //This puts the resulting row into an array for access
            while($row = $result->fetch_assoc())
            {          
                //Question score
                $QuestionScore = $row["num_upvotes"] - $row["num_downvotes"];
                $solved = is_null($row["is_solved"]);//Pass this variable into method to determine if x or check will print

                    echo "<tr>
                  
                    <td>
                    <a href=\"answer.php?q_id=".$row["question_id"]. "\">
                    ".$row["question_title"]."
                    </a>
                    </td>

                    <td>"
                    .$row["user_name"].
                    "</td>";
                    
                    echo "<td>" 
                    .Solved($solved). 
                    "</td>

                    <td>".$QuestionScore."</td>

                    
                                      
                    </tr>";
      }
    }

        ?>
        <hr />


    </div>

</div>


<script src="bootstrap/js/bootstrap.min.js"></script>

</body>

</html>