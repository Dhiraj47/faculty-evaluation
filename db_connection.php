<?php
/**
 * User: Dhiraj
 * Date: 5/8/2018
 * Time: 8:00 PM
 */

$connection = mysqli_connect('localhost', 'root', '', 'evaluation');

if(!$connection)
{
    $msg=mysqli_error($connection);
    echo "<script type='text/javascript'>alert('Error : $msg');</script>";
}

?>