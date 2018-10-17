<?php
/**
 * User: Dhiraj
 * Date: 5/8/2018
 * Time: 8:20 PM
 */

include "db_connection.php";

function sem()
{
    global $connection;
    $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='iqac'";

    $result = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($result))
        echo "<option>" . strtoupper($row['TABLE_NAME']) . "</option>";
}


if (isset($_GET['opt'])) {
    $sem=$_GET['sem'];
    $dept = $_GET['opt'];
    $query = "SELECT DISTINCT(emp_code) FROM $sem WHERE dept_name LIKE '$dept'";

    $result = mysqli_query($connection, $query);
    $temp = array();

    $emp_no =mysqli_num_rows($result);

    while($row=mysqli_fetch_assoc($result))
    {
        $emp=$row['emp_code'];
        $query = "SELECT emp_code,total_assessed FROM $sem WHERE emp_code LIKE '$emp'";
        $r= mysqli_query($connection, $query);

        $t_ass =0;
        $f_name ='';
        while ($row2 = mysqli_fetch_assoc($r))
        {
            $f_name = $row2['faculty_name'];
            $t_ass+=$row2['total_assessed'];
        }

        // $int = preg_replace('/[^0-9]+/', '', $row['faculty_code']);
        $string = $emp ;
        if ($t_ass != 0)
            array_push($temp, $string);

    }


    $temp = array_unique($temp);

    echo "<option value='' disabled selected style='display:none;'>--SELECT--</option>";
    foreach ($temp as $value)
        echo "<option value='$value'>$value</option>";

    echo "<option value='All Faculties'>ALL FACULTIES</option>";
    unset($result,$r,$temp);
}




if (isset($_GET['semester'])) {
    $sem = $_GET['semester'];

    $query = "SELECT dept_name FROM $sem";

    $result = mysqli_query($connection, $query);
    $d_name = array();

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($d_name, $row['dept_name']);

    }
    $d_name = array_unique($d_name);

    echo "<option value='' disabled selected style='display:none;'>--SELECT--</option>";

    foreach ($d_name as $value)
        echo "<option>" .$value. "</option>";

    unset($result,$d_name);
}


function calculate_score_of_fac($fac_code,$sem)
{
//    $fac_code = preg_replace('/[^0-9]+/', '', $fac_code);
//    echo $fac_code;
    global $connection;
    $query = "SELECT course_code, ROUND(((AQ1+AQ2+AQ3+AQ4+AQ5+AQ6+AQ7+AQ8+AQ9+AQ10+AQ11+AQ12+AQ13+AQ14+AQ15+AQ16+AQ17+AQ18)/18),2) AS 'total_AQ', 
              ROUND(((SQ1+SQ2+SQ3+SQ4+SQ5+SQ6+SQ7+SQ8+SQ9+SQ10+SQ11+SQ12+SQ13+SQ14+SQ15+SQ16+SQ17+SQ18)/18),2) AS 'total_SQ' 
              FROM $sem WHERE emp_code like '$fac_code'";

    $result = mysqli_query($connection, $query);
    $n_rows = mysqli_num_rows($result);

    $temp = array();

    $AQ = 0;
    $SQ = 0;
    $fac_name = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $AQ += $row['total_AQ'];
        $SQ += $row['total_SQ'];
    }

    if($n_rows!=0){
        $AQ = $AQ / $n_rows;
        $SQ = $SQ / $n_rows;
    }
    else{
        $AQ = 0;
        $SQ = 0;
    }

    array_push($temp, round($AQ, 2));
    array_push($temp, round($SQ, 2));
    array_push($temp, $fac_code);
    array_push($temp, $fac_name);

    return $temp;

}


function get_all_fac($dept_code,$sem)
{
    global $connection;

    $query = "SELECT faculty_code FROM $sem WHERE dept_code = $dept_code";

    $result = mysqli_query($connection, $query);

    $faculty_code = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $string = $row['faculty_code'];
        $int = preg_replace('/[^0-9]+/', '', $string);
        array_push($faculty_code, $int);
    }

    $faculty_code = array_unique($faculty_code);

    $faculty = array();

    foreach ($faculty_code as $fac_code) {
        $temp = calculate_score_of_fac($fac_code);
        array_push($faculty, $temp);
    }

    // print_r($faculty);

    $AQ = 0;
    $SQ = 0;
    $loop = 0;
    foreach ($faculty as $data_point) {
        $AQ += $data_point[0];
        $SQ += $data_point[1];
        $loop++;
    }

    $mean = $AQ / $loop;
    $sd_mean = $SQ / $loop;

    $ret_array = array($mean, $sd_mean);

    // print_r($ret_array);
    return $ret_array;

}


function dept_mean($dept, $sem)
{
    global $connection;

    $query = "SELECT emp_code FROM $sem WHERE dept_name LIKE '$dept'";

    $result = mysqli_query($connection, $query);

    $faculty_code = array();

    while ($row = mysqli_fetch_assoc($result))
        array_push($faculty_code, $row['emp_code']);

    $faculty_code = array_unique($faculty_code);

    $faculty = array();

    foreach ($faculty_code as $fac_code) {
        $temp = calculate_score_of_fac($fac_code,$sem);
        array_push($faculty, $temp);
    }

    // print_r($faculty);

    $AQ = 0;
    $loop = 0;
    foreach ($faculty as $data_point) {
        if ($data_point[0] != 0) {
            $AQ += $data_point[0];
            $loop++;
        }
    }

    if ($loop != 0)
        $mean = $AQ / $loop;
    else
        $mean = 0;

    return $mean;

}


function scores_of_fac($fac_code)
{

    global $connection;
    $query = "SELECT course_code, ROUND(((AQ1+AQ2+AQ3+AQ4+AQ5+AQ6+AQ7+AQ8+AQ9+AQ10+AQ11+AQ12+AQ13+AQ14+AQ15+AQ16+AQ17+AQ18)/18),2) AS 'total_AQ', 
              ROUND(((SQ1+SQ2+SQ3+SQ4+SQ5+SQ6+SQ7+SQ8+SQ9+SQ10+SQ11+SQ12+SQ13+SQ14+SQ15+SQ16+SQ17+SQ18)/18),2) AS 'total_SQ' 
              FROM `jun_dec_17` WHERE faculty_code like '$fac_code%'";

    $result = mysqli_query($connection, $query);

    $string = "";
    while ($row = mysqli_fetch_assoc($result))
        $string .= "<i><b>" . $row['course_code'] . "</b><br> Avg: " . $row['total_AQ'] . "<br> SD: " . $row['total_SQ'] . "<br><br></i>";

    return $string;

}

function mean($emp_code,$sem)
{
    global $connection;

    $query = "SELECT course_code, ROUND(((AQ1+AQ2+AQ3+AQ4+AQ5+AQ6+AQ7+AQ8+AQ9+AQ10+AQ11+AQ12+AQ13+AQ14+AQ15+AQ16+AQ17+AQ18)/18),2) AS 'total_AQ', 
              ROUND(((SQ1+SQ2+SQ3+SQ4+SQ5+SQ6+SQ7+SQ8+SQ9+SQ10+SQ11+SQ12+SQ13+SQ14+SQ15+SQ16+SQ17+SQ18)/18),2) AS 'total_SQ' 
              FROM $sem WHERE emp_code like '$emp_code'";
    $result = mysqli_query($connection, $query);
    $n_rows = mysqli_num_rows($result);

    $AQ = 0;
    while ($row = mysqli_fetch_assoc($result))
        $AQ += $row['total_AQ'];

    if ($n_rows != 0)
        $mean = round($AQ / ($n_rows), 2);
    else
        $mean = 0;

    return $mean;
}



function report($emp_code,$sem)
{
    global $connection;
    $query = "SELECT emp_code,course_code,semester,total_assessed, ROUND(((AQ1+AQ2+AQ3+AQ4+AQ5+AQ6+AQ7+AQ8+AQ9+AQ10+AQ11+AQ12+AQ13+AQ14+AQ15+AQ16+AQ17+AQ18)/18),2) AS 'total_AQ', 
              ROUND(((SQ1+SQ2+SQ3+SQ4+SQ5+SQ6+SQ7+SQ8+SQ9+SQ10+SQ11+SQ12+SQ13+SQ14+SQ15+SQ16+SQ17+SQ18)/18),2) AS 'total_SQ' 
              FROM $sem WHERE emp_code like '$emp_code%'";

    $result = mysqli_query($connection, $query);
    $n_rows =mysqli_num_rows($result);
    $temp = array();

    $AQ = "";
    $SQ = "";
    $grand_AQ = 0;
    $grand_SQ = 0;
    $course='';
    $semester='';
    $t_assessed='';
    $fac_name = "";
    $fac_code = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $course.=$row['course_code'].'<br><br>';
        $semester.=$row['semester'].'<br><br>';
        $AQ .= strval($row['total_AQ']).'<br><br>';
        $SQ .= strval($row['total_SQ']).'<br><br>';
        $grand_AQ += $row['total_AQ'];
        $grand_SQ += $row['total_SQ'];
        $t_assessed.=strval($row['total_assessed']).'<br><br>';
        $fac_code = $row['emp_code'];
    }

    $fac_name =$fac_code;

    if($n_rows!=0)
    {
        $grand_AQ = round($grand_AQ/$n_rows,2);
        $grand_SQ = round($grand_SQ/$n_rows,2);
    }
    else
    {
        $grand_AQ = 0;
        $grand_SQ = 0;
    }



    array_push($temp, $fac_name);
    array_push($temp, $course);
    array_push($temp, $AQ);
    array_push($temp, $SQ);
    array_push($temp, $t_assessed);
    array_push($temp, $grand_SQ);
    array_push($temp, $grand_AQ);
    array_push($temp, $fac_code);
    array_push($temp, $semester);

    return $temp;

}

