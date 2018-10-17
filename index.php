<!---->
<!--/**-->
<!-- * User: Dhiraj-->
<!-- * Date: 5/8/2018-->
<!-- * Time: 8:05 PM-->
<!-- * Created On: PHP Storm-->
<!-- */-->


<!DOCTYPE HTML>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0">
	<title>IQAC - Student's Evaluation of Faculties</title>
</head>
<body>
<div style="background-color: #0080FF" align="center">
    <img class="img-fluid" src="css/logo.png">
</div>
<br>
<div class="container" align="center">

    <h1 id="someElement" class="h1"><b></b></h1>
<br>
    <div>
        <div class="row-sm-4">
            <a class="btn btn-primary" href="course_wise.php">Faculty Wise</a>
        </div>
        <br>

        <div class="row-sm-4">
            <a class="btn btn-primary" href="all_fac_in_dept.php">All Faculties in a Department</a>
        </div>
        <br>

        <br>
<!--        <div class="row-sm-4">-->
<!--            <a class="btn btn-primary" href="chart_all_depts.php">Department Wise</a>-->
<!--        </div>-->
    </div>
</div>

<div id="" style="width: content-box">

</div>

<script>
    function printLetterByLetter(destination, message, speed){
        var i = 0;
        var interval = setInterval(function(){
            document.getElementById(destination).innerHTML += message.charAt(i);
            i++;
            if (i > message.length){
                clearInterval(interval);
            }
        }, speed);
    }

    printLetterByLetter("someElement", "IQAC - Student's Evaluation of Faculties", 70);
</script>

<footer class="card-footer" style="position: fixed; bottom:0;width: 100%">     
	<a style="float: left; margin-right: 2%">Copyright © 2017-18 </a>
	<a style="float: left; margin-right: 2%; font-size: smaller" >Developed by : Dhiraj</a>
	<a style="float: left; margin-right: 2%; font-size: smaller" >email : dxk.r47@gmail.com</a>
		
	<a style="float: right; margin-right: 2%" >Help Line (0XXX-XXX-XXX)</a>

</footer>


</body>
</html>