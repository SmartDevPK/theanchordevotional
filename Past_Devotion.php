<?php
include("db.php");

// Get filters from query string
$month = $_GET['month'] ?? '';
$year  = $_GET['year'] ?? '';
$topic = $_GET['topic'] ?? '';

// Build query
$query = "SELECT * FROM devotions WHERE 1=1";
$params = [];
$types = '';

if(!empty($month)){
    $query .= " AND MONTH(devotion_date) = ?";
    $params[] = $month;
    $types .= 'i';
}

if(!empty($year)){
    $query .= " AND YEAR(devotion_date) = ?";
    $params[] = $year;
    $types .= 'i';
}

if(!empty($topic)){
    $query .= " AND topic LIKE ?";
    $params[] = "%$topic%";
    $types .= 's';
}

$query .= " ORDER BY devotion_date DESC";

$stmt = $mysqli->prepare($query);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$devotions = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="devotions-list">
    <?php if(!empty($devotions)): ?>
        <?php foreach($devotions as $d): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($d['topic']); ?></h5>
                    <p class="card-text"><?php echo date("F j, Y", strtotime($d['devotion_date'])); ?></p>
                    <a href="edit_devotion.php?edit=<?php echo $d['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="manage_devotions.php?delete=<?php echo $d['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this devotion?')">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">No devotions found.</p>
    <?php endif; ?>
</div>
