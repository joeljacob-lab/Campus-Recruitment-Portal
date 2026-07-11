<?php
require('../lib/fpdf.php');
require('../config/db.php'); // adjust this path based on your project structure

if (isset($_GET['id'])) {
    $recruiter_id = $_GET['id'];

    // Fetch recruiter details
    $stmt = $conn->prepare("SELECT name, email, phone, name, company_address FROM recruiters WHERE id = ?");
    $stmt->bind_param("i", $recruiter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recruiter = $result->fetch_assoc();

    // Fetch application stats
    $stmt2 = $conn->prepare("SELECT 
        SUM(CASE WHEN status = 'shortlisted' THEN 1 ELSE 0 END) AS shortlisted,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected
        FROM applications WHERE recruiter_id = ?");
    $stmt2->bind_param("i", $recruiter_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $stats = $result2->fetch_assoc();

    // Start PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Company Heading
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $recruiter['company_name'], 0, 1, 'C');
    $pdf->Ln(5);

    // Recruiter Info Table
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Recruiter Details', 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, 'Name:', 1);
    $pdf->Cell(140, 10, $recruiter['name'], 1, 1);
    $pdf->Cell(50, 10, 'Email:', 1);
    $pdf->Cell(140, 10, $recruiter['email'], 1, 1);
    $pdf->Cell(50, 10, 'Phone:', 1);
    $pdf->Cell(140, 10, $recruiter['phone'], 1, 1);
    $pdf->Cell(50, 10, 'Company Address:', 1);
    $pdf->MultiCell(140, 10, $recruiter['company_address'], 1);

    $pdf->Ln(5);

    // Application Stats
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Application Summary', 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 10, 'Shortlisted Applications', 1);
    $pdf->Cell(95, 10, $stats['shortlisted'], 1, 1);
    $pdf->Cell(95, 10, 'Rejected Applications', 1);
    $pdf->Cell(95, 10, $stats['rejected'], 1, 1);

    // Output
    $pdf->Output('D', 'Recruiter_Report_' . $recruiter['company_name'] . '.pdf');
} else {
    echo "Recruiter ID not provided.";
}
?>
