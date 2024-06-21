<!DOCTYPE html>
<html>
<head>
    <title>SoftDeleteData</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: auto;
            overflow: hidden;
        }
        .main-header {
            background-color: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #77b300 3px solid;
        }
        .main-header h1 {
            text-align: center;
            text-transform: uppercase;
            margin: 0;
        }
        .message {
            background: #fff;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    
    <header class="main-header">
        <h1>SoftDeleteData</h1>
    </header>
    <div class="container">
        <div class="message">
            <?php
            // Database configuration
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "data";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if ID is provided
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']); // Ensure the ID is an integer

                // Soft delete query
                $sql_update = "UPDATE softdata SET deleted_at = NOW() WHERE id = $id";

                if ($conn->query($sql_update) === TRUE) {
                    // Check if any row was affected
                    if ($conn->affected_rows > 0) {
                        // Insert into archive table
                        $sql_insert = "INSERT INTO softdata_archive (id, name, email, deleted_at) 
                                       SELECT id, name, email, deleted_at FROM softdata WHERE id = $id";
                        
                        if ($conn->query($sql_insert) === TRUE) {
                            echo "Record soft deleted successfully";
                        } else {
                            echo "Error archiving record: " . $conn->error;
                        }
                    } else {
                        echo "No records were deleted for ID: $id";
                    }
                } else {
                    echo "Error soft deleting record: " . $conn->error;
                }
            } else {
                echo "No ID provided";
            }

            $conn->close();
            
            
            header("location: displaydata.php");
            exit();
            ?>
        </div>
    </div>
</body>
</html>
