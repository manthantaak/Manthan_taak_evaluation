<?php
    require_once __DIR__ . '/db.php';
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $statement = $conn->query(
        'SELECT photographid, photo_title, photographer_name, category, description, image
        FROM photographs ORDER BY photographid'
    );
    $photographs = $statement->fetch_all(MYSQLI_ASSOC);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="photographs.csv"');
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF");
    fputcsv($output, ['Photograph ID', 'Photograph Title', 'Photographer Name', 'Category', 'Description', 'Image Path']);

    foreach ($photographs as $photograph) {
        fputcsv($output, [
            $photograph['photographid'],
            $photograph['photo_title'],
            $photograph['photographer_name'],
            $photograph['category'],
            $photograph['description'],
            $photograph['image']
        ]);
    }

    fclose($output);
    exit;
