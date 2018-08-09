<?php
	require('fpdf181/fpdf.php');
	
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetTitle('Regulamin gry Arena');
	$pdf->SetAuthor('Administracja gry Arena');
	$pdf->AddFont('JosefinSans', '', 'JosefinSans.php');
	
	$pdf->Image('logo.jpg');
	$pdf->Line(0, 50, 220, 50);
	
	$pdf->SetFont('JosefinSans', '', 32);
	$pdf->Cell(0, 32, 'Regulamin gry', 0, 32, 'C');
	
	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(128, 0, 0);
	$pdf->Cell(0, 10, 'I. Postanowienia ogolne', 0, 16);
	
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetTextColor(0);
	$pdf->MultiCell(0, 5, 
	"
	1. Rejestracja w grze jest nieodplatna, jak rowniez uzytkowanie gry.\n
	2. Prawo do zarejestrowania sie posiada kazdy, kto skonczyl 18 lat z wylaczeniem osob, ktore zostaly ukarane permanentnym banem za lamanie regulaminu gry.\n
	3. Zabronione jest uzywanie bugow (tzw. bledow w grze).\n
	4. Niedozwolone jest uzywanie botow (tzn. programow zautomatyzowanych, ktore prowadza rozgrywke za gracza)./n
	5. Niedozwolone jest uzywanie skryptow ulatwiajacych rozgrywke.
	", 0);
	
	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(128, 0, 0);
	$pdf->Cell(0, 10, 'II. Login i nazwa uzytkownika', 0, 16);
	
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetTextColor(0);
	$pdf->MultiCell(0, 5, 
	"
	1. Musi sie skladac wylacznie z liter.\n
	2. Nie moze byc zlepkiem przykladowych liter.\n
	3. Nie moze obrazac ani ponizac innych osob.
	", 0);
	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(128, 0, 0);
	$pdf->Cell(0, 10, 'III. Pozostale zasady', 0, 16);
	
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetTextColor(0);
	$pdf->MultiCell(0, 5, 
	"
	1. Obsluga gry zastrzega sobie prawo do zmian regulaminu.\n
	2. Regulamin obowiazuje kazdego uzytkownika zarejestrowanego w grze.
	", 0);
	
	
	$pdf->Output();
?>
