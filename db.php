<?php
   $hostname = "localhost";
   $username = "root";
   $password = "";
   $dbname = "db_sekolah";

   $conn = mysqli_connect($hostname, $username, $password, $dbname) or die ('gagal terhubung ke database');
?>