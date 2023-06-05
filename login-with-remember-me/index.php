<html>  
<head>  
    <title>Login Form</title>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
</head>
<style>
 .box
 {
  width:100%;
  max-width:600px;
  background-color:#f9f9f9;
  border:1px solid #ccc;
  border-radius:5px;
  padding:16px;
  margin:0 auto;
 }
 input.parsley-success,
 select.parsley-success,
 textarea.parsley-success {
   color: #468847;
   background-color: #DFF0D8;
   border: 1px solid #D6E9C6;
 }

 input.parsley-error,
 select.parsley-error,
 textarea.parsley-error {
   color: #B94A48;
   background-color: #F2DEDE;
   border: 1px solid #EED3D7;
 }

 .parsley-errors-list {
   margin: 2px 0 3px;
   padding: 0;
   list-style-type: none;
   font-size: 0.9em;
   line-height: 0.9em;
   opacity: 0;

   transition: all .3s ease-in;
   -o-transition: all .3s ease-in;
   -moz-transition: all .3s ease-in;
   -webkit-transition: all .3s ease-in;
 }

 .parsley-errors-list.filled {
   opacity: 1;
 }
 
 .parsley-type, .parsley-required, .parsley-equalto{
  color:#ff0000;
 }
.error
{
  color: red;
  font-weight: 700;
} 
</style>
<?php
session_start();
include('connection.php');
if(isset($_COOKIE['emailid']) && isset($_COOKIE['password']))
{
  $emailid = $_COOKIE['emailid'];
  $password = $_COOKIE['password'];
}
else
{
  $emailid = $password ="";
}

if(isset($_REQUEST['login']))
{
  $email = $_REQUEST['email'];
  $pwd = md5($_REQUEST['pwd']);
  $select_query = mysqli_query($connection,"select * from tbl_student where email='$email' and password='$pwd'");
  $res = mysqli_num_rows($select_query);
  if($res>0)
  {
    $data = mysqli_fetch_array($select_query);
    $name = $data['name'];
    $_SESSION['name'] = $name;
    if(isset($_REQUEST['rememberMe']))
    {
      setcookie('emailid',$_REQUEST['email'],time()+60*60);//1 hour
      setcookie('password',$_REQUEST['pwd'],time()+60*60); //1 hour
    }
    else
    {
      setcookie('emailid',$_REQUEST['email'],time()-10);//10 seconds
      setcookie('password',$_REQUEST['pwd'],time()-10); //10 seconds
    }
    header('location:dashboard.php');
  }
  else
  {
    $msg = "Please enter valid Emailid or Password.";
  }
}
?>
 <body>  
    <div class="container">  
    <div class="table-responsive">  
    <h3 align="center">Login Form</h3><br/>
    <div class="box">
     <form id="validate_form" method="post" >  
       <div class="form-group">
       <label for="email">Email</label>
       <input type="text" name="email" id="email" placeholder="Enter Email" required 
       data-parsley-type="email" data-parsley-trigg
       er="keyup" class="form-control" value="<?php echo $emailid; ?>"/>
      </div>
      <div class="form-group">
       <label for="password">Password</label>
       <input type="password" name="pwd" id="pwd" placeholder="Enter Password" required  data-parsley-trigger="keyup" class="form-control" value="<?php echo $password; ?>"/>
      </div>
     <input type="checkbox" name="rememberMe" id="rememberMe"> <label for="rememberMe">Remember me</label>
      <div class="form-group">
       <input type="submit" id="login" name="login" value="LogIn" class="btn btn-success" />
       </div>
       
       <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
     </form>
     </div>
   </div>  
  </div>
 </body>  
</html>  
