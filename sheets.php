<?php
require __DIR__ . '/vendor/autoload.php'; // Убедитесь, что библиотека google/apiclient установлена
include_once "config.php";
include_once "connect.php";

$data = [
    ['Бренд', 'Модель', 'Остаток', 'РРЦ', 'ОПТ', 'БН', 'Примечание'],
];
$query_bace = "SELECT * FROM bace WHERE ost != '0' ORDER BY brand, model ASC";
$stmt_bace = $conn->prepare($query_bace);
if ($stmt_bace->execute()) {
    $result_bace = $stmt_bace->get_result();
    while ($row_bace=$result_bace->fetch_assoc()) {
        $row_bace['bn'] == 1 ? $bn = 'Да' : $bn = 'Нет';
        $data [] = [$row_bace['brand'],$row_bace['model'],$row_bace['ost'],$row_bace['price'],$row_bace['opt'],$bn,$row_bace['position']];
    }
}
$stmt_bace->close();

function clearGoogleSheet($spreadsheetId, $range) {
    $client = initializeGoogleClient();
    $service = new Google_Service_Sheets($client);

    try {
        $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest());
        echo "Sheet cleared successfully.\n";
    } catch (Exception $e) {
        echo 'Error clearing sheet: ' . $e->getMessage();
    }
}


function initializeGoogleClient() {
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API PHP');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig($linkSheets); // Путь к вашему файлу учетных данных
    $client->setAccessType('offline');
    return $client;
}

function updateGoogleSheet($spreadsheetId, $range, $values) {
    $client = initializeGoogleClient();
    $service = new Google_Service_Sheets($client);

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);

    $params = [
        'valueInputOption' => 'RAW'
    ];

    try {
        $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
        printf("%d cells updated.\n", $result->getUpdatedCells());
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}




clearGoogleSheet($spreadsheetId, $range);
updateGoogleSheet($spreadsheetId, $range, $data);
?>