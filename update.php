<?php 
require_once("dbinfo.php");
session_start();

//set variables
$firstid = $_SESSION['originalid'];
$id= "";
$firstname="";
$lastname="";
$error	= "";
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(mysqli_connect_errno() != 0){
    $error = $error . "<p>There was an error connecting to the SQL server</p>";
    $_SESSION['errormessage'] = $error;
    header('location:index.php');
    die();
}


//check fields exist
if( isset($_POST['id']) && isset($_POST['firstname']) && isset($_POST['lastname'])){
    //format data and ensure it was not tampered with
    $id = trim($db->real_escape_string($_POST['id']));
    $firstname = trim($db->real_escape_string($_POST['firstname']));
    $lastname = trim($db->real_escape_string($_POST['lastname']));



    //check that fields were filled in
    if($id == '' || $firstname == '' || $lastname ==''){
        $error = $error . "<p>Please ensure all inputs are filled in.</p>";
        $_SESSION['errormessage'] = $error;
        header('location: updateform.php');
        die();
    }else{
        //check that student number is a correct format
        if(preg_match("/^a0[0-9]{7}$/i", $id) == false){
            $error = $error . "<p>Invalid Student Number. $firstid $firstname $lastname was not updated.</p>";
            $_SESSION['errormessage'] = $error;
            header('location: index.php');
            die();
        }

        //check students with user number
        $query = "SELECT * FROM students WHERE id='".$id."';";
        $result = $db->query($query);
        $numberOfRows = $result->num_rows;
        
    
        if($numberOfRows >= 1){
            while(   $record = $result->fetch_assoc() ){
                //ensure that the new student number is not the same as a different student's number
                if($record['primary_key'] != $_SESSION['primary_key']){
                    $error = $error . "<p>ID is already in database.</p>";
                    $_SESSION['errormessage'] = $error;
                    header('location: index.php');
                    die();
                }else{
                    //format id and then update the database
                    $id = strtoupper($id);
                    $query = "UPDATE students SET id='".$id."', firstname='".$firstname."', lastname='".$lastname."' WHERE id='".$firstid."';";
                    $result = $db->query($query);
                    $affected = $db->affected_rows;

                    //check if database was updated or not and display on the index page
                    if($affected == true){
                        $user = "$id $firstname $lastname";
                     $_SESSION['update'] = "<p>Student $user was was updated.</p>";
                     header("location: index.php");
                     die();
                    }else{
                        $user = "$firstid $firstname $lastname";
                     $_SESSION['update'] = "<p>Student $user was not updated.</p>";
                     header("location: index.php");
                     die();
                    }
                }

            }
        
            }
            //required when changing the student number to another number.
            else{
            $id = strtoupper($id);
            $query = "UPDATE students SET id='".$id."', firstname='".$firstname."', lastname='".$lastname."' WHERE id='".$firstid."';";
            $result = $db->query($query);
            $affected = $db->affected_rows;
            $user = "$id $firstname $lastname";

            
                if($affected == true){
                    $user = "$id $firstname $lastname";
                    $_SESSION['update'] = "<p>User $user was was updated.</p>";
                    header("location: index.php");
                    die();
                }
                else{
                    $user = "$firstid $firstname $lastname";
                    $_SESSION['update'] = "<p>User $user was not updated.</p>";
                    header("location: index.php");
                    die();
                }
            }
    }
}else{
    //outputs error to index.php
    $error = $error . "<p>Error with form input fields.</p>";
    $_SESSION['errormessage'] = $error;
    header('location: index.php');
    die();
}
$db->close();

unset($_SESSION['originalid']);
?>