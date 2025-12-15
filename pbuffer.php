<html>
<head>
<title>Alliance</title>
<link rel="icon" type="image/ico" href="favicon.ico">
<script>
    
</script>    
<style>
    .main{
        margin-left:47%;
  margin-top:15%;
    }
    .loader {
  transform: translateZ(1px);
}
.loader:after {
  content: '$';
  display: inline-block;
  width: 98px;
  height: 98px;
  border-radius: 50%;
  text-align: center;
  line-height:80px;
  font-size: 72px;
  font-weight: bold;
  background: #FFD700;
  color: #DAA520;
  border: 4px double ;
  box-sizing: border-box;
  box-shadow:  2px 2px 2px 1px rgba(0, 0, 0, .1);
  animation: coin-flip 4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}
@keyframes coin-flip {
  0%, 100% {
    animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
  }
  0% {
    transform: rotateY(0deg);
  }
  50% {
    transform: rotateY(1800deg);
    animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
  }
  100% {
    transform: rotateY(3600deg);
  }
}
      
</style>
</head>    
<body>
<?php include 'header.php';
if (isset($_GET['ticket_id'])) {
  $ticket_id = $_GET['ticket_id'];
  echo '<script>
  setTimeout(function() {
    window.location.href = "ticket.php?&ticket_id=' .$ticket_id. '";}, 2000); 
</script>';
}

?>
<div class="main">
<span class="loader"></span>
</div>
</body>
</html>