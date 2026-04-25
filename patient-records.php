<?php
session_start();
if (!isset($_SESSION['user']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'doctor')) {
    header('location: index.php');
    exit();
}
require_once('backends/connection-pdo.php');
if (!isset($_SESSION['user_id'])) {
    header('location: logout.php');
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch Prescriptions
$sql_presc = "SELECT p.*, u.name as doctor_name, dept.dept_name 
            FROM prescriptions p 
            JOIN users u ON p.doctor_id = u.id 
            JOIN doctors d ON p.doctor_id = d.id 
            JOIN departments dept ON d.dept_id = dept.id 
            WHERE p.patient_id = ? 
            ORDER BY p.id DESC";
$query_presc = $pdoconn->prepare($sql_presc);
$query_presc->execute([$user_id]);
$prescriptions = $query_presc->fetchAll(PDO::FETCH_ASSOC);

// Fetch Billing Records
$sql_bill = "SELECT b.*, p.medications, p.created_at as prescription_time, u_doc.name as doctor_name, u_pat.id as patient_id, u_pat.name as patient_name 
            FROM billings b 
            JOIN prescriptions p ON b.prescription_id = p.id 
            JOIN users u_doc ON p.doctor_id = u_doc.id 
            JOIN users u_pat ON b.patient_id = u_pat.id 
            WHERE b.patient_id = ? 
            ORDER BY b.id DESC";
$query_bill = $pdoconn->prepare($sql_bill);
$query_bill->execute([$user_id]);
$billings = $query_bill->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Medical Records | HealthCare MS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/healthcare-theme.css">
</head>
<body>
    <?php require('chunks/login-modal.php'); ?>
    <?php require('chunks/register-modal.php'); ?>
    <?php require('chunks/info-modal.php'); ?>
    <?php require('chunks/navbar.php'); ?>

    <div class="section white-text center" style="background: #4a6a5c; margin-top: 20px;">
        <h4>Medical Records</h4>
        <p>Your complete health history in one place</p>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 50px;">
        
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s6"><a class="active" href="#prescriptions" style="color: #6b9080;">Prescriptions</a></li>
                    <li class="tab col s6"><a href="#billing" style="color: #6b9080;">Billing & History</a></li>
                </ul>
            </div>
            
            <!-- Prescriptions Tab -->
            <div id="prescriptions" class="col s12" style="margin-top: 20px;">
                <div class="card">
                    <div class="card-content">
                        <h5 style="color: #4a6a5c; margin-bottom: 20px;">My Prescriptions</h5>
                        <table class="highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Department</th>
                                    <th>Medications</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($prescriptions) > 0): ?>
                                    <?php foreach ($prescriptions as $presc): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(date('d M Y', strtotime($presc['created_at']))); ?></td>
                                            <td><?php echo htmlspecialchars($presc['doctor_name']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['dept_name']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['medications']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['instructions']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="center">No prescriptions found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Billing & History Tab -->
            <div id="billing" class="col s12" style="margin-top: 20px;">
                <div class="card">
                    <div class="card-content">
                        <h5 style="color: #4a6a5c; margin-bottom: 20px;">Consultation History & Bills</h5>
                        <table class="highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Doctor</th>
                                    <th>Prescription Date</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($billings) > 0): ?>
                                    <?php foreach ($billings as $bill): ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($bill['patient_id']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['patient_name']); ?></td>
                                            <td><?php echo htmlspecialchars($bill['doctor_name']); ?></td>
                                            <td><?php echo htmlspecialchars(date('d M Y, h:i A', strtotime($bill['prescription_time']))); ?></td>
                                            <td>৳<?php echo number_format($bill['amount']); ?></td>
                                            <td><?php echo $bill['payment_method'] ? htmlspecialchars($bill['payment_method']) : '-'; ?></td>
                                            <td>
                                                <?php if ($bill['status'] == 'completed'): ?>
                                                    <span class="new badge green darken-2" data-badge-caption="">Completed</span>
                                                <?php else: ?>
                                                    <span class="new badge orange darken-2" data-badge-caption="">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($bill['status'] == 'pending'): ?>
                                                    <button class="btn-small waves-effect waves-light pay-btn" 
                                                            data-id="<?php echo $bill['id']; ?>"
                                                            data-amount="<?php echo $bill['amount']; ?>"
                                                            data-meds="<?php echo htmlspecialchars($bill['medications']); ?>"
                                                            style="background: #6b9080 !important;">
                                                        Pay
                                                    </button>
                                                <?php else: ?>
                                                    <span class="grey-text">None</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="center">No billing history found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal" style="border-radius: 15px;">
        <div class="modal-content">
            <h4 style="color: #4a6a5c; font-family: 'Bree Serif', serif;">Complete Your Payment</h4>
            <div class="divider"></div>
            <div style="margin-top: 20px;">
                <div class="row">
                    <div class="col s12 m6">
                        <p style="font-size: 1.1rem;"><strong>Consultation Fee:</strong></p>
                        <p id="modalAmount" style="font-size: 2rem; color: #6b9080; font-weight: bold; margin-top: -10px;"></p>
                    </div>
                    <div class="col s12 m6">
                        <p style="font-size: 1.1rem;"><strong>Prescription Summary:</strong></p>
                        <div id="modalMeds" style="padding: 12px; background: #f4f7f6; border-radius: 8px; border-left: 4px solid #6b9080; color: #555;"></div>
                    </div>
                </div>
                
                <p style="font-size: 1.1rem; margin-top: 20px;"><strong>Choose Payment Method:</strong></p>
                <div class="row" style="margin-top: 15px;">
                    <div class="col s4 center">
                        <label>
                            <input name="payment_method" type="radio" value="Cash" checked />
                            <span style="color: #333; font-weight: 500;">Cash</span>
                        </label>
                    </div>
                    <div class="col s4 center">
                        <label>
                            <input name="payment_method" type="radio" value="BKash" />
                            <span style="color: #333; font-weight: 500;">BKash</span>
                        </label>
                    </div>
                    <div class="col s4 center">
                        <label>
                            <input name="payment_method" type="radio" value="Debit Card" />
                            <span style="color: #333; font-weight: 500;">Debit Card</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="background: #f9f9f9; padding: 0 20px 20px 0; height: 80px;">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat" style="margin-top: 15px;">Cancel</a>
            <button id="confirmPayBtn" class="waves-effect waves-light btn-large" style="background: #6b9080 !important; border-radius: 30px; padding: 0 40px; margin-top: 10px;">
                <i class="material-icons left">check_circle</i> Pay Now
            </button>
        </div>
    </div>

    <?php require('chunks/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="js/loaders.js"></script>
    <script>
        $(document).ready(function(){
            $('.tabs').tabs();
            $('.modal').modal();

            $('.pay-btn').click(function(){
                var id = $(this).data('id');
                var amount = $(this).data('amount');
                var meds = $(this).data('meds');
                
                $('#modalAmount').text('৳' + amount);
                $('#modalMeds').text(meds);
                $('#confirmPayBtn').data('id', id);
                
                $('#paymentModal').modal('open');
            });

            $('#confirmPayBtn').click(function(){
                var id = $(this).data('id');
                var method = $('input[name="payment_method"]:checked').val();
                
                $(this).addClass('disabled').html('<i class="material-icons left">sync</i> Processing...');
                
                $.ajax({
                    url: 'backends/pay-bill.php',
                    type: 'POST',
                    data: {bill_id: id, payment_method: method},
                    success: function(response){
                        var res = JSON.parse(response);
                        if(res.code == "1"){
                            M.toast({html: '<i class="material-icons left">check</i> ' + res.msg, classes: 'rounded green darken-2'});
                            $('#paymentModal').modal('close');
                            setTimeout(function(){ location.reload(); }, 1500);
                        } else {
                            M.toast({html: '<i class="material-icons left">error</i> ' + res.msg, classes: 'rounded red darken-2'});
                            $('#confirmPayBtn').removeClass('disabled').html('<i class="material-icons left">check_circle</i> Pay Now');
                        }
                    },
                    error: function() {
                        M.toast({html: 'Connection error!', classes: 'rounded red darken-2'});
                        $('#confirmPayBtn').removeClass('disabled').html('<i class="material-icons left">check_circle</i> Pay Now');
                    }
                });
            });
        });
    </script>
</body>
</html>
