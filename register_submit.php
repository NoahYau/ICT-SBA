<?php

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $contact = $_POST["contact"];
    $city = $_POST["city"];
    $address = $_POST["address"];
    $admin = $_POST["admin"];

#    if (strlen($password) < 8) {
#        echo "Password must be 8 charaters or more";
#        die();
#    }
    $connectdb = mysqli_connect("localhost","root","","dogebee");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $sql = "INSERT INTO users (name, email, password, contact, city, address, admin) VALUES ('$name', '$email', '$password', '$contact', '$city', '$address', '$admin')";
    #echo $sql;
    $result = mysqli_query($connectdb, $sql);
    mysqli_close($connectdb);
?>
<script>
    alert("Sign up succeeded")
    window.location.href = "products.php";
</script>