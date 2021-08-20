<?php
    session_start();
    include_once "database.php";
    
    if (isset($_POST['sendmessage'])) {
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $mailFrom = $_POST['email'];
        $message = $_POST['message'];
        $mailTo = "kingmichael9812@gmail.com";
        $headers = "From: ".$mailFrom;
        $msg = "";
        $txt = "You have recieved an email form ".$name." \n\n".$message;

        $sql = "INSERT INTO contact (name, email, subject, message) VALUES('$name','$mailFrom','$subject','$message')";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            $msg = "<center><span style='color: green' id='msg'><b>Message Delivered Successfully</b></span></center>";
            ?>
                <script>
                    swal("Good Job!", "You clicked the button!", "success");
                </script>
            <?php
        } else {
            $msg = "<center><span style='color: red' id='msg'><b>Message Delivered Failed</b></span></center>";
            ?>
                <script>
                    swal("Something Went Wrong!", "Message not Delivered!", "error");
                </script>
            <?php
        }


        // mail($mailTo, $subject, $txt, $headers);
        // header("location index.php?mailsent");
    }
    


    if (isset($_POST['proceed'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];

        if (empty($name) || empty($email)) {
            $msg = "Values Cannot be Empty.";
        } else {
        
            $query1 = mysqli_query($conn,"SELECT * FROM visitors WHERE email='$email'");
            
            if ($query1) {
                if (mysqli_num_rows($query1) > 0) {
                    while ($row = mysqli_fetch_assoc($query1)) {
                        $_SESSION['name'] = $name;
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['setup'] = $row['is_setup'];
    
                        header("Location: resume.php");
                        $message = "<p>Welcome Back <span>".$_SESSION['name']."</span></p>";
                    }
                    
                 
                }  else {
                    $sql = mysqli_query($conn, "INSERT INTO visitors (name,email,is_setup) VALUES ('$name','$email','0')");
                
                    if ($sql) {
                        $sql2 = mysqli_query($conn,"SELECT * FROM visitors WHERE email='$email'");
                        if ($sql2) {
                            if (mysqli_num_rows($sql2) > 0) {
                                while ($rows = mysqli_fetch_assoc($sql2)) {
                                    $_SESSION['name'] = $name;
                                    $_SESSION['email'] = $rows['email'];
                                    $_SESSION['setup'] = $rows['is_setup'];

                                    $misc = $name;
                                    $message = "<p>Welcome <span>".$name."</span></p>";
                                    
                                    header("location: resume.php");
                                    
                                }
                            } else {
                                header("location: index.php?NOtMemberyet");
                            }
                        } else {
                            header("location: index.php?Sqlfailed");
                        }
                        
                    }
                }
            } 

        }


    }