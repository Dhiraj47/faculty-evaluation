<?php
/**
 * User: Dhiraj
 * Date: 5/8/2018
 * Time: 8:39 PM
 */

include "php_function.php"; ?>

<!DOCTYPE HTML>
<html>
<head>
    <title>All Faculties In Department</title>
    <script type="text/javascript" src="js/myJS.js"></script>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0">

</head>
<body>
<div style="background-color: #0080FF" align="center">
    <img class="img-fluid" src="css/logo.png">
</div>
<div class="row" style="width:100%;padding:0.5%;margin: 0.5%;">

    <div class="col-xl-2">
        <label>Semester / Year</label>
        <select class="form-control" name="sem" id="sem" onchange="update_dept(this.value)">
            <option>--Select--</option>
            <?php sem(); ?>
        </select>
    </div>

    <div class="col-xl-7">
        <label>Department</label>
        <select class="form-control" name="dept_code" id="dept_code"
                onchange="update_chart(this.value,document.getElementById('myIframe'),document.getElementById('sem').value)">
            <option>--Select--</option>
        </select>
    </div>

    <div class="col-xl-3" style="padding-top: 2.5%">
        <button class="btn btn-primary" style="float: right; margin-left: 4%;"
                onclick=printFrame()>Print
        </button>
        <input style="float: right;" class="btn btn-info" type="button"
               onclick="location.href='index.php';" value="Back">
    </div>
</div>
<br>
<iframe class="embed-responsive embed-responsive-16by9" id="myIframe" src="chart_all_fac_dept.php" scrolling="no" onload="resizeIframe(this)"
        frameborder="0" style="margin-left:0.5% "></iframe>

<script type="text/javascript">
    function update_chart(dept_name, myIframe, sem) {
        myIframe.src = "chart_all_fac_dept.php?chart=" + dept_name + "&sem=" + sem;

    }

    function printFrame() {
        var frm = document.getElementById('myIframe').contentWindow;
        frm.focus();// focus on contentWindow is needed on some ie versions
        frm.print();
        return false;
    }

    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 150 +'px';
    }

</script>

</body>
</html>
