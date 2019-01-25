
<?php

session_start();
require('dbinfo.php');

//variables 
$error	= "";
$id = "";
$firstname = "";
$lastname = "";
$user = "";
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(mysqli_connect_errno() != 0){
    $error = $error . "<p>There was an error connecting to the SQL server</p>";
    $_SESSION['errormessage'] = $error;
    header('location: index.php');
    die();
}
//check that all fields are set
if( isset($_POST['id']) && isset($_POST['firstname']) && isset($_POST['lastname'])){

    //ensure they're not tampered with and then trim 
    $id = trim($db->real_escape_string($_POST['id']));
    $firstname = trim($db->real_escape_string($_POST['firstname']));
    $lastname = trim($db->real_escape_string($_POST['lastname']));

    $user = "$id $firstname $lastname";

    //check fields aren't blank
    if($id == '' || $firstname == '' || $lastname ==''){
        $error = $error . "<p>Please ensure all inputs are filled in.</p>";
        $_SESSION['errormessage'] = $error;
        header('location: adduserform.php');
        die();
    }else{
        //check student number is valid
        if(preg_match("/^a0[0-9]{7}$/i", $id) == false){
            $error = $error . "<p>Invalid Student Number. $id $firstname $lastname was not updated.</p>";
            $_SESSION['errormessage'] = $error;
            header('location: index.php');
            die();
        }
        //check that the student number isn't taken
        $query = "SELECT * FROM students WHERE id='".$id."';";
        $result = $db->query($query);
        $numberOfRows = $result->num_rows;
        if($numberOfRows >= 1){
            $error = $error . "<p>Student $user is already in the database.</p>";
            $_SESSION['errormessage'] = $error;
            header('location: adduserform.php');
            die();
        }else{
            //format ID and insert into database
            $id = strtoupper($id);
            $query = "INSERT INTO students (id,firstname,lastname) VALUES( '$id','$firstname','$lastname');";
            $result = $db->query($query);
            $affected = $db->affected_rows;
            
            //check for success
            if($affected == true){
             $_SESSION['update'] = "<p>Student $user was was added.</p>";
             header("location: index.php");
             die();
            }else{
             $_SESSION['update'] = "<p>Student $user was not added.</p>";
             header("location: index.php");
             die();
            }
        }
    }
}else{
    $error = $error . "<p>Error with form input fields.</p>";
    $_SESSION['errormessage'] = $error;
    header('location: index.php');
    die();
}

$db->close();
?>