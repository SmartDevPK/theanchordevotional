<?php
include("db.php"); 

// Total devotions 
$sql = "SELECT COUNT(*) AS total_devotions FROM landing_page_content";
$result = $mysqli->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total = $row['total_devotions'];
    // echo "Total Devotions: " . $total . "<br>";
} else {
    echo "Error: " . $mysqli->error . "<br>";
}

// Total approved devotions 
$sql2 = "SELECT COUNT(*) AS approved_devotions FROM devotion"; 
$result2 = $mysqli->query($sql2);

if ($result2) {
    $row2 = $result2->fetch_assoc();
    $approved_total = $row2['approved_devotions'];
    // echo "Total Approved Devotions: " . $approved_total . "<br>";
} else {
    echo "Error: " . $mysqli->error . "<br>";
}

// Total approved daily verses
$sql3 = "SELECT COUNT(*) AS approved_daily_verses FROM daily_verse";   
$result3 = $mysqli->query($sql3);   

if ($result3) {
    $row3 = $result3->fetch_assoc();
    $approved_verses_total = $row3['approved_daily_verses'];
    // echo "Total Approved Daily Verses: " . $approved_verses_total;
} else {
    echo "Error: " . $mysqli->error;
}

// Total subscribers
$sql4 = "SELECT COUNT(*) AS total_subscribers FROM subscribers";
$result4 = $mysqli->query($sql4);

if ($result4) {
    $row4 = $result4->fetch_assoc();
    $total_subscribers = $row4['total_subscribers'];
    // echo "Total Subscribers: " . $total_subscribers . "<br>";
} else {
    echo "Error fetching subscribers: " . $mysqli->error . "<br>";
}

// Total Prayer requests
$sql5 = "SELECT COUNT(*) AS total_prayer FROM prayer_requests";
$result5 = $mysqli->query($sql5);

if ($result5) {
    $row5 = $result5->fetch_assoc();
    $prayer_total = $row5['total_prayer'];
    // echo "Total prayer request:" . $prayer_total;
} else {
    echo "Error: " . $mysqli->error;
}

//Total family resources
$sql6 = "SELECT COUNT(*) AS total_family_resource FROM family";
$result6 = $mysqli->query($sql6);

if ($result6) {
    $row6 = $result6->fetch_assoc();
    $total_family_resources = $row6['total_family_resource'];
    // echo "Total Family Resources: " . $total_family_resources . "<br>";
} else {
    echo "Error fetching family resources: " . $mysqli->error . "<br>";
}

// how to get recent activities from multiple tables
$activities = [];

 // Devotions
$sql01 = "SELECT topic AS description, created_at, 'Devotion' AS activity_type 
         FROM devotion ORDER BY created_at DESC LIMIT 5";
$res1 = $mysqli->query($sql01);
while ($row = $res1->fetch_assoc()) { $activities[] = $row; }

// Prayer Requests
$sql02 = "SELECT name AS description, created_at, 'Prayer Request' AS activity_type 
         FROM prayer_requests ORDER BY created_at DESC LIMIT 5";
$res2 = $mysqli->query($sql02);
while ($row = $res2->fetch_assoc()) { $activities[] = $row; }

// Family
$sql03 = "SELECT name AS description, created_at, 'Family' AS activity_type 
         FROM family ORDER BY created_at DESC LIMIT 5";
$res3 = $mysqli->query($sql03);
while ($row = $res3->fetch_assoc()) { $activities[] = $row; }

// Sort all activities by date DESC
usort($activities, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

// Limit to 10 most recent
$activities = array_slice($activities, 0, 10);

?>
