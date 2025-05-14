<?php
session_start();
require '../../db2.php';
header('Content-Type: application/json');

// Fetch all employees upfront
function fetchAllEmployees($conn) {
    $employees = [];
    $res = $conn->query("SELECT id, name FROM employees WHERE status = 'active'");
    while ($row = $res->fetch_assoc()) {
        $employees[$row['id']] = $row['name'];
    }
    return $employees;
}

// 1) Single-Day Summary
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $employees = fetchAllEmployees($conn);

    $stmt = $conn->prepare(
        "SELECT employee_id, time_in, time_out, TIME_FORMAT(TIMEDIFF(time_out, time_in), '%H:%i') AS hours, IFNULL(remarks, '') AS remarks
         FROM attendance
         WHERE date_made = ?"
    );
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $res = $stmt->get_result();

    $attendance = [];
    while ($r = $res->fetch_assoc()) {
        $attendance[$r['employee_id']] = $r;
    }

    $out = [];
    foreach ($employees as $eid => $name) {
        $record = [
            'name'    => $name,
            'time_in' => $attendance[$eid]['time_in'] ?? '',
            'time_out'=> $attendance[$eid]['time_out'] ?? '',
            'hours'   => $attendance[$eid]['hours'] ?? '',
            'remarks' => $attendance[$eid]['remarks'] ?? '',
        ];
        $out[] = $record;
    }

    echo json_encode($out);
    exit;
}

// 2) Week-Long Summary
// 2) Week-Long Summary
if (isset($_GET['week'])) {
    list($year, $weekNum) = explode('-W', $_GET['week']);
    $dto = new DateTime();
    $dto->setISODate((int)$year, (int)$weekNum);

    $daysDate = [];
    $daysLabel = [];
    for ($i = 0; $i < 7; $i++) {
        $d = clone $dto;
        $d->modify("+{$i} days");
        $daysDate[]  = $d->format('Y-m-d');
        $daysLabel[] = $d->format('D d');
    }
    $start = $daysDate[0];
    $end = $daysDate[6];

    $employees = fetchAllEmployees($conn);

    $stmt = $conn->prepare(
        "SELECT employee_id, date_made, TIME_TO_SEC(TIMEDIFF(time_out, time_in)) AS seconds
         FROM attendance
         WHERE date_made BETWEEN ? AND ?
           AND time_in IS NOT NULL"
    );
    $stmt->bind_param('ss', $start, $end);
    $stmt->execute();
    $res = $stmt->get_result();

    $attendance = [];
    while ($r = $res->fetch_assoc()) {
        $attendance[$r['employee_id']][$r['date_made']] = (int)$r['seconds'];
    }

    $records = [];
    foreach ($employees as $eid => $name) {
        $row = ['name' => $name, 'hours' => [], 'total_seconds' => 0];
        foreach ($daysDate as $dt) {
            if (isset($attendance[$eid][$dt])) {
                $seconds = $attendance[$eid][$dt];
                $row['hours'][$dt] = gmdate('H:i', $seconds);
                $row['total_seconds'] += $seconds;
            } else {
                $row['hours'][$dt] = '-';
            }
        }
        $row['total_hours'] = $row['total_seconds'] > 0 ? gmdate('H:i', $row['total_seconds']) : '00:00';
        $records[] = $row;
    }

    echo json_encode(['days' => $daysLabel, 'records' => $records]);
    exit;
}


// 3) Month-Long Summary
if (isset($_GET['month'])) {
    $month = $_GET['month']; // e.g., 2025-04
    $dt = DateTime::createFromFormat('Y-m', $month);
    if (!$dt) {
        echo json_encode([]);
        exit;
    }
    $start = $dt->format('Y-m-01');
    $end = $dt->format('Y-m-t');
    $numDays = (int)$dt->format('t');

    $days = [];
    for ($i = 1; $i <= $numDays; $i++) {
        $days[] = sprintf('%02d', $i);
    }

    $employees = fetchAllEmployees($conn);

    $stmt = $conn->prepare(
        "SELECT employee_id, date_made, TIME_FORMAT(TIMEDIFF(time_out, time_in), '%H:%i') AS hours
         FROM attendance
         WHERE date_made BETWEEN ? AND ?
           AND time_in IS NOT NULL
           AND time_out IS NOT NULL"
    );
    $stmt->bind_param('ss', $start, $end);
    $stmt->execute();
    $res = $stmt->get_result();

    $attendance = [];
    $totalHours = [];
    while ($r = $res->fetch_assoc()) {
        $eid = $r['employee_id'];
        $attendance[$eid][] = $r['date_made']; // Store the full date
        // Sum total hours (convert HH:MM to minutes)
        list($h, $m) = explode(':', $r['hours']);
        $totalHours[$eid] = ($totalHours[$eid] ?? 0) + ($h * 60) + $m;
    }

    $records = [];
    foreach ($employees as $eid => $name) {
        $row = [
            'name' => $name,
            'present_days' => $attendance[$eid] ?? [],
            'total_hours' => isset($totalHours[$eid]) ? sprintf('%.2f', $totalHours[$eid] / 60) : '0.00'
        ];
        $records[] = $row;
    }

    echo json_encode(['days' => $days, 'records' => $records]);
    exit;
}
?>
