<?php
require "connect.php";

// Check if the necessary POST data is received
if (isset($_POST['Club_ID'], $_POST['Activity_Name'], $_POST['Date'], $_POST['Venue'], $_POST['Persons_Involved'])) {
    // Sanitize and store the received data
    $Club_ID = $_POST['Club_ID'];
    $Activity_Name = $_POST['Activity_Name'];
    $Date = $_POST['Date'];
    $Venue = $_POST['Venue'];
    $Persons_Involved = $_POST['Persons_Involved'];

    try {
        // Establish a database connection
        $pdo = Database::letsconnect();

        // Get the last Activity_ID from the database and increment it by one
        $sql = "SELECT MAX(Activity_ID) AS max_id FROM events";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $next_activity_id = $row['max_id'] + 1;

        // Prepare the SQL query to insert new event data with the automatically assigned Activity_ID
        $sql = "INSERT INTO events (Activity_ID, Club_ID, Activity_Name, Date, Venue, Persons_Involved) 
                VALUES (:Activity_ID, :Club_ID, :Activity_Name, :Date, :Venue, :Persons_Involved)";

        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':Activity_ID', $next_activity_id, PDO::PARAM_INT);
        $stmt->bindParam(':Club_ID', $Club_ID, PDO::PARAM_STR);
        $stmt->bindParam(':Activity_Name', $Activity_Name, PDO::PARAM_STR);
        $stmt->bindParam(':Date', $Date, PDO::PARAM_STR);
        $stmt->bindParam(':Venue', $Venue, PDO::PARAM_STR);
        $stmt->bindParam(':Persons_Involved', $Persons_Involved, PDO::PARAM_STR);
        $stmt->execute();

        // Output success message along with the assigned Activity_ID
        echo "Event added successfully! Assigned Activity ID: $next_activity_id";
    } catch (PDOException $e) {
        // Output error message if database operation fails
        echo "Error adding event: " . $e->getMessage();
    }
} else {
    // Output error message if required POST data is not received
    echo "Error: Incomplete data received.";
}
?>
