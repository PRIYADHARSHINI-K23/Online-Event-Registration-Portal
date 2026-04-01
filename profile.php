<?php
session_start();
require 'db_connect.php';

// Redirect to login if not logged in
if(!isset($_SESSION['user_email'])){
    header("Location: login.html");
    exit;
}

// Get logged-in user info
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Fetch booked events for this user
$stmt = $conn->prepare("SELECT event_title FROM registrations WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$booked_events = [];
while($row = $result->fetch_assoc()){
    $booked_events[] = $row['event_title'];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EventHub - Profile</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
* {margin:0; padding:0; box-sizing:border-box; font-family:'Roboto', sans-serif;}
body,html {background: linear-gradient(135deg,#4b6cb7,#182848,#ff758c); color:#fff; min-height:100vh; overflow-x:hidden;}
header {width:100%; padding:20px 50px; display:flex; justify-content:space-between; align-items:center; background:rgba(0,0,0,0.2); position:fixed; top:0; left:0; backdrop-filter:blur(12px); box-shadow:0 4px 15px rgba(0,0,0,0.3); z-index:10;}
header .logo {font-size:28px; font-weight:bold; color:#ff758c;}
nav a {margin:0 15px; text-decoration:none; font-weight:600; color:#fff; transition:0.3s;}
nav a:hover {color:#ffb347;}
nav button {margin-left:15px; padding:8px 15px; border:none; border-radius:20px; background:#ff4b6b; color:#fff; font-weight:bold; cursor:pointer; transition:0.3s;}
nav button:hover {background:#ff758c;}
.profile-header {text-align:center; margin-top:140px; margin-bottom:40px;}
.profile-header img {width:120px; height:120px; border-radius:50%; border:3px solid #FFD966; margin-bottom:15px;}
.profile-header h2 {font-size:28px; color:#FFD966; margin-bottom:5px;}
.profile-header p {font-size:16px; opacity:0.8;}
.booked-events {display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:25px; max-width:1200px; margin:0 auto; padding:0 20px;}
.card {background: rgba(255,255,255,0.1); padding:20px; border-radius:20px; backdrop-filter:blur(15px); box-shadow:0 10px 25px rgba(0,0,0,0.4); min-height:100px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center;}
.card h3 {margin-bottom:10px; color:#ff758c;}
.card button {margin-top:10px; padding:8px 16px; border:none; border-radius:20px; background:#4CAF50; color:#fff; font-weight:bold; cursor:not-allowed; opacity:0.7;}
.shape {position:absolute; border-radius:50%; opacity:0.15; animation: float 6s ease-in-out infinite; pointer-events:none; z-index:0;}
.shape1 {width:140px; height:140px; background:#ff758c; top:10%; left:5%;}
.shape2 {width:160px; height:160px; background:#4b6cb7; top:70%; left:85%;}
.shape3 {width:100px; height:100px; background:#9b5de5; top:40%; left:50%;}
@keyframes float {0%,100%{transform:translateY(0px);}50%{transform:translateY(-30px);}}
@media(max-width:768px){.booked-events{grid-template-columns:1fr; padding:0 10px;}}
</style>
</head>
<body>

<div class="shape shape1"></div>
<div class="shape shape2"></div>
<div class="shape shape3"></div>

<header>
  <div class="logo">🌟 EventHub</div>
  <nav>
    <a href="index.html">Home</a>
    <a href="events.html">Events</a>
    <a href="booking.html">Booking</a>
    <a href="profile.php">Profile</a>
    <button onclick="logout()">Logout</button>
  </nav>
</header>

<div class="profile-header">
  <img src="profile.png" alt="User Avatar">
  <h2><?php echo htmlspecialchars($user_name); ?></h2>
  <p>Member since 2025 | Event Enthusiast</p>
</div>

<h2 style="text-align:center; margin-bottom:30px; color:#FFD966;">📌 My Booked Events</h2>

<div class="booked-events" id="bookedEventsGrid">
    <?php if(count($booked_events) > 0): ?>
        <?php foreach($booked_events as $event): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($event); ?></h3>
                <button disabled>Booked</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; grid-column:1/-1; color:#fff; opacity:0.8;">You have not booked any events yet.</p>
    <?php endif; ?>
</div>

<script>
function logout(){
    window.location.href = 'logout.php';
}
</script>

</body>
</html>
