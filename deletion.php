
<?php

session_start();
require('dbinfo.php');
$error	= "";
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//check for error connecting to db
if(mysqli_connect_errno() != 0){
    $error = $error . "<p>There was an error connecting to the SQL server</p>";
    $_SESSION['errormessage'] = $error;
    header('location: index.php');
    die();
}
//check that id field is in the URL
if( isset($_GET['id']) ){
    $id = $db->real_escape_string($_GET['id']);
    $query = "SELECT * FROM students WHERE id='".$id."';";
    $result = $db->query($query);
    while(   $record = $result->fetch_assoc() ){
        $user = $record['id'] . " " . $record['firstname'] . " " . $record['lastname'];
    }
    //check that the radio button was selected
    if(isset($_POST['delete'])){
        //if user changes mind and does not want to delete
        if($_POST['delete'] == 'no'){
            $_SESSION['update'] = "<p>User $user was not deleted.</p>";
            header("location: index.php");
            die();
        }
        //if deletion is confirmed
       if($_POST['delete'] == 'yes'){
           $query = "DELETE FROM students WHERE id ='".$id."';";
           $result = $db->query($query);
           $affected = $db->affected_rows;

           if($affected == true){
                $_SESSION['update'] = "<p>User $user was was deleted.</p>";
                header("location: index.php");
                die();
           }else{
                //if there is an issue with deleting the student
                $_SESSION['update'] = "<p>User $user was not deleted.</p>";
                header("location: index.php");
                die();
           }
       }else{
        //undefined error? 
        $error = $error . "<p>There was an error.</p>";
        $_SESSION['errormessage'] = $error;
        header('location: index.php');
        die();
       }
    }else{
        //if radio button was not selected
        $error = $error . "<p>You did not select a radio button.</p>";
        $_SESSION['errormessage'] = $error;
        header('location: index.php');
        die();
    }
}else{
    $error = $error . "<p>There was an error getting the student info. Please don't tamper with the URLS.</p>";
    $_SESSION['errormessage'] = $error;
    header('location: index.php');
    die();
}
$db->close();
?>