<?php
require "connect.php";
$pdo = Database::letsconnect();

$transaction = "GET_EVENTS_DATA"; // Default transaction

// Check if a search query is received
if (isset($_POST['searchQuery'])) {
    $searchQuery = $_POST['searchQuery'];
    $transaction = "SEARCH_EVENTS_DATA";
}

switch ($transaction) {
    case "GET_EVENTS_DATA":
        getEventsData();
        break;
    case "SEARCH_EVENTS_DATA":
        searchEventsData($searchQuery);
        break;
    default:
        // Handle other cases
}

function getEventsData() {
    $sql = "SELECT * FROM events"; // Replace with your actual query
    $data = Database::GetAllData($GLOBALS['pdo'], $sql);
    echo json_encode($data);
}

function searchEventsData($searchQuery) {
    // Prepare the SQL query to search for records
    $sql = "SELECT * FROM events WHERE Activity_ID LIKE '%$searchQuery%' OR Club_ID LIKE '%$searchQuery%' OR Activity_Name LIKE '%$searchQuery%' OR Date LIKE '%$searchQuery%' OR Venue LIKE '%$searchQuery%' OR Persons_Involved LIKE '%$searchQuery%'";

    // Fetch the filtered records from the database
    $data = Database::GetAllData($GLOBALS['pdo'], $sql);
    echo json_encode($data);
}
?>
