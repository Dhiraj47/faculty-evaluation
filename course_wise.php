<?php
/**
 * User: Dhiraj
 * Date: 5/8/2018
 * Time: 9:10 PM
 */

include "php_function.php"; ?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Faculty Evaluation By Coursewise</title>

    <script type="text/javascript" src="js/myJS.js"></script>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0">
</head>
<body>
<div style="background-color: #0080FF" align="center">
    <img class="img-fluid" src="css/logo.png">
</div>
<div class="row p-4" style="width:100%;">

    <div class="col-xl-2">
        <label>Semester / Year</label>
        <select class="form-control" name="sem" id="sem" onchange="update_dept(this.value)">
            <option>--Select--</option>
            <?php sem(); ?>
        </select>
    </div>


    <div class="col-xl-6">
        <label>Department</label>
        <select class="form-control" name="dept_code" id="dept_code"
                onchange="update_faculty(this.value,document.getElementById('sem').value)">
            <option>--Select--</option>
        </select>
    </div>

    <div class="col-xl-2">
        <label>Faculty (Employee Code)</label>
        <select class="form-control" name="faculties" id="faculties"
                onchange="update_chart(this.value,document.getElementById('myIframe'))">
            <option>--Select--</option>
        </select>
    </div>


    <div class="col-xl-2" style="padding-top: 2%">
        <button class="btn btn-primary" style="float: right; margin-left: 2%;"
                onclick=printFrame()>Print
        </button>

        <input style="float: right;margin-left: 4%;" class="btn btn-info" type="button"
               onclick="location.href='index.php';"
               value="Back">
    </div>
</div>

<br>
<div id="HTMLtoPDF">
    <iframe class="embed-responsive embed-responsive-16by9" id="myIframe" src="chart_course_wise.php"
            onload="resizeIframe(this)" scrolling="no"
            frameborder="0"></iframe>
</div>
<script type="text/javascript">
    function update_chart(str, myIframe) {
        if (str !== 'All Faculties') {

            var iframes = document.querySelectorAll('iframe');

            for (var i = 1; i < iframes.length; i++) {
                iframes[i].parentNode.removeChild(iframes[i]);
            }

            var dept = document.getElementById('dept_code').value;
            var sem = document.getElementById('sem').value;
            var emp_code =str;
            myIframe.src = "chart_course_wise.php?fac=" + emp_code + "&dept=" + dept + "&sem=" + sem;
        }
        else
            generator();

    }

    function printFrame() {

        var iframes = document.querySelectorAll('iframe');
        if (iframes.length === 1) {
            var frm = document.getElementById('myIframe').contentWindow;
            frm.focus();// focus on contentWindow is needed on some ie versions
            frm.print();
            return false;
        }
        else {
            window.focus();
            window.print();
        }
    }

    function generator() {
        var element = document.getElementById('faculties'),
            i;
        var length = element.options.length;

        var dept = document.getElementById('dept_code').value;
        var sem = document.getElementById('sem').value;
       // var regExp = /\(([^)]+)\)/;

        var iframes = document.querySelectorAll('iframe');

        for (var i = 1; i < iframes.length; i++) {
            iframes[i].parentNode.removeChild(iframes[i]);
        }

        for (i = 1; i < length - 1; i++) {
            var ddl = element.options[i].value;
            var emp_code = ddl;
            var src = "chart_course_wise.php?fac=" + emp_code + "&dept=" + dept + "&sem=" + sem;

            if (i === 1) {
                var myframe = document.getElementById('myIframe');
                myframe.src = src;
            }
            else {
                var frame = document.createElement('IFRAME');
                frame.setAttribute("src", src);
                frame.setAttribute('class', 'embed-responsive embed-responsive-16by9');
                frame.setAttribute('height', '2600');
                frame.setAttribute('frameborder', '0');
                frame.setAttribute('id', '' + i.toString());
                document.body.appendChild(frame);
            }
        }

        resizeIframe();
    }

    function resizeIframe() {
        var iframes = document.querySelectorAll('iframe');
        for (var i = 0; i < iframes.length; i++)
            iframes[i].style.height = iframes[i].contentWindow.document.body.scrollHeight +150+ 'px';
    }

</script>

</body>
</html>