<?php
    session_start();

    $email = $_POST["email"];
    echo "$email</br>";
    $password = $_POST["password"];
    echo $password;

    $connectdb = mysqli_connect("localhost","root","","dogebee");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $stmt = $connectdb->prepare("select admin from users where email = ? and password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->bind_result($admin);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_row();

    if ($result->num_rows != 0) {

#    if ($result = mysqli_query($connectdb, $sql)) {
        #$no_rows = mysqli_num_rows($result); // $no_rows tells how many of rows in the result set.
        #if ($no_rows > 0) { #successful
            $_SESSION["email"] = $email;
            $_SESSION["admin"] = $row[0]; #fetch the admin state (1 or 0)
            header("location: products.php");
        }
        else { #failed
?>
	    <script>
            alert("Wrong username or password");
            window.location.href = "login.html";
        </script>
<?php
        }
   # }
    #echo $sql;

    mysqli_close($connectdb);
?>