<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Step 1: Connect to the database
$servername = "localhost";
$username1 = "root";
$password = "";
$dbname = "log_book";

// Create connection
$conn = new mysqli($servername, $username1, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $_SESSION['message'] = "Database connection failed: " . $conn->connect_error;
    $_SESSION['message_type'] = "error";
    header("Location: manage_signup_requests.php");
    exit();
}

// Step 2: Get the ID and action from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

if ($id > 0 && in_array($action, ['approve', 'reject', 'fired'])) {
    // Step 3: Fetch the user's details from signup_table
    $sql_fetch = "SELECT username, password, role FROM signup_table WHERE id = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['username'];
        $password = $user['password'];
        $role = $user['role'];

        // Step 4: Update the approved status in signup_table
        $newStatus = ($action === 'approve') ? 'approved' : ($action === 'reject' ? 'rejected' : 'fired');
        $sql = "UPDATE signup_table SET approved = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Step 5: Handle the specific action
                if ($action === 'approve') {
                    // Insert the user into login_table
                    $sql1 = "INSERT INTO login_table (username, password, role) VALUES (?, ?, ?)";
                    $stmt1 = $conn->prepare($sql1);
                    $stmt1->bind_param("sss", $username, $password, $role);

                    if ($stmt1->execute()) {
                        $_SESSION['message'] = "User approved and added to login table successfully.";
                        $_SESSION['message_type'] = "success";
                    } else {
                        $_SESSION['message'] = "User approved, but failed to add to login table: " . $conn->error;
                        $_SESSION['message_type'] = "error";
                    }
                    $stmt1->close();
                } elseif ($action === 'reject') {
                    $_SESSION['message'] = "User rejected successfully.";
                    $_SESSION['message_type'] = "success";
                } elseif ($action === 'fired') {
                    // Delete the user from login_table using username (primary key)
                    $sql_delete = "DELETE FROM login_table WHERE username = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("s", $username);

                    if ($stmt_delete->execute()) {
                        if ($stmt_delete->affected_rows > 0) {
                            $_SESSION['message'] = "User request marked as Fired and removed from login table successfully.";
                            $_SESSION['message_type'] = "success";
                        } else {
                            // Treat "user not found" as a non-error
                            $_SESSION['message'] = "User request marked as Fired successfully   .";
                            $_SESSION['message_type'] = "success";
                        }
                    } else {
                        $_SESSION['message'] = "User request marked as Fired, but failed to delete from login table: " . $conn->error;
                        $_SESSION['message_type'] = "error";
                    }
                    $stmt_delete->close();
                }
            } else {
                $_SESSION['message'] = "No user found with ID $id or the request was already processed.";
                $_SESSION['message_type'] = "error";
            }
        } else {
            $_SESSION['message'] = "Error updating signup_table: " . $conn->error;
            $_SESSION['message_type'] = "error";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "User not found in signup_table.";
        $_SESSION['message_type'] = "error";
    }

    $stmt_fetch->close();
} else {
    $_SESSION['message'] = "Invalid request. Missing or invalid ID/action.";
    $_SESSION['message_type'] = "error";
}

// Step 6: Close the database connection and redirect
$conn->close();
header("Location: manage_signup_requests.php");
exit();
?>