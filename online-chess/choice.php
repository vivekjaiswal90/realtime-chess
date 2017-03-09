<!DOCTYPE html>
<html>
<head>

      <meta charset="utf-8">  
   	
      <title>Online Chess</title>  
    
      <meta name="description" content="Play Chess Online.">  
 	
      <link rel="icon" href="./favicon.ico">
    
      <link rel="stylesheet" href="stylesheets/reset.css">
   
      <link rel="stylesheet" href="stylesheets/layout.css">
   	
      <link rel="stylesheet" href="stylesheets/style.css">
      
      <link rel="stylesheet" href="stylesheets/extra.css">
    
<style>
		
       body {background-image:url('./chess.jpg');}
	
</style>

</head>

<body>

<div id="banner">ONLINE CHESS</div>
<div id="single"><a href="./play1.html"><img src="stylesheets/images/singlelink.png" width="260" height="147"></a></div>
<div id="multi"><a href="./play.html"><img src="stylesheets/images/multilink.png" width="260" height="147"></a></div>
<div id="logout-button"><a href="./include/logout.php">Sign Out</a></div>
<div id="welcome-bar">
<div>Hi, <?php echo $_SESSION['login_name']; ?></div>
</body>
</html>
