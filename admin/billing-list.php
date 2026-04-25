<?php require('layout/header.php'); ?>
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>
<?php require_once('../backends/connection-pdo.php'); ?>

<?php
// Filter logic
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$where_clause = "";
if ($status_filter == 'pending') {
    $where_clause = "AND b.status = 'pending'";
} elseif ($status_filter == 'completed') {
    $where_clause = "AND b.status = 'completed'";
}

// Fetch all billing records with custom sorting
$sql = "SELECT b.*, p.created_at as prescription_time, d.name as doctor_name, u.name as patient_name, u.id as patient_id 
        FROM billings b 
        JOIN prescriptions p ON b.prescription_id = p.id 
        JOIN doctors d ON p.doctor_id = d.id 
        JOIN users u ON b.patient_email = u.email 
        WHERE 1=1 $where_clause
        ORDER BY (b.status = 'pending') DESC, 
                 CASE WHEN b.status = 'pending' THEN p.created_at END ASC, 
                 CASE WHEN b.status = 'completed' THEN b.paid_at END DESC";
$query = $pdoconn->prepare($sql);
$query->execute();
$billings = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="section admin-theme-bg center" style="margin-top: 20px;">
    <h4>Billing Management</h4>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
    
    <div class="row">
        <div class="col s12 center" style="margin-bottom: 20px;">
            <a href="?status=all" class="btn-small <?php echo $status_filter == 'all' ? 'admin-theme-bg' : 'grey darken-1'; ?> waves-effect waves-light" style="margin: 5px; border-radius: 20px; padding: 0 25px;">Show Both</a>
            <a href="?status=pending" class="btn-small <?php echo $status_filter == 'pending' ? 'orange darken-2' : 'grey darken-1'; ?> waves-effect waves-light" style="margin: 5px; border-radius: 20px; padding: 0 25px;">Only Pending</a>
            <a href="?status=completed" class="btn-small <?php echo $status_filter == 'completed' ? 'green darken-2' : 'grey darken-1'; ?> waves-effect waves-light" style="margin: 5px; border-radius: 20px; padding: 0 25px;">Only Paid</a>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <h5 class="admin-theme-text" style="margin-bottom: 30px; font-weight: 700;">Financial Overview</h5>
                    <table class="highlight responsive-table striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Amount</th>
                                <th>Prescribed On</th>
                                <th>Paid At</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($billings) > 0): ?>
                                <?php foreach ($billings as $bill): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($bill['patient_id']); ?></td>
                                        <td>
                                            <div style="font-weight: 600;"><?php echo htmlspecialchars($bill['patient_name']); ?></div>
                                            <div style="font-size: 0.8rem; color: #777;"><?php echo htmlspecialchars($bill['patient_email']); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($bill['doctor_name']); ?></td>
                                        <td style="font-weight: 700; color: #ef6c00;">৳<?php echo number_format($bill['amount']); ?></td>
                                        <td><?php echo date('d M Y, h:i A', strtotime($bill['prescription_time'])); ?></td>
                                        <td><?php echo $bill['paid_at'] ? date('d M Y, h:i A', strtotime($bill['paid_at'])) : '-'; ?></td>
                                        <td><?php echo $bill['payment_method'] ? htmlspecialchars($bill['payment_method']) : '-'; ?></td>
                                        <td>
                                            <?php if ($bill['status'] == 'completed'): ?>
                                                <span class="new badge green darken-2" data-badge-caption="" style="font-weight: 600;">Paid</span>
                                            <?php else: ?>
                                                <span class="new badge orange darken-2" data-badge-caption="" style="font-weight: 600;">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="center">No records found for the selected filter.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('layout/footer.php'); ?>
