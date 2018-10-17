<?php
/**
 * User: Dhiraj
 * Date: 5/8/2018
 */

include "php_function.php";

if (isset($_GET['chart']) && $_GET['chart'] != null && $_GET['chart'] != "--Select--") {

    $dept_name = $_GET['chart'];

    $table_name = $_GET['sem'];

    $query = "SELECT emp_code FROM $table_name WHERE dept_name LIKE '$dept_name'";
    $result = mysqli_query($connection, $query);

    $faculty_code = array();
    $faculty = array();

    while ($row = mysqli_fetch_assoc($result))
        array_push($faculty_code, $row['emp_code']);

    $faculty_code = array_unique($faculty_code);

    foreach ($faculty_code as $fac_code) {
        $temp = report($fac_code, $table_name);
        array_push($faculty, $temp);
    }

    //  print_r($faculty);
    $dataPoints1 = array();
    $dataPoints2 = array();
    $mean = 0;
    $loop = 0;
    foreach ($faculty as $data_point) {
        if ($data_point[6] != 0) {
            $loop++;
            array_push($dataPoints1, array("y" => $data_point[6], "label" => $data_point[7]));
            $mean += $data_point[6];
            array_push($dataPoints2, array("y" => array(($data_point[6] - $data_point[5]), ($data_point[6] + $data_point[5])), "label" => $data_point[7]));
        }
    }
    if ($loop != 0)
        $mean = round($mean / ($loop), 2);
    else
        $mean = 0;

//    echo "mean= ".$mean." - loop=".$loop;
//     print_r($dataPoints1);

} else {
    $dataPoints1 = array();

    $dataPoints2 = array();
    $dept_name = "";
    $mean = 0;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0">
    <style>
        thead td {
            cursor: pointer;
        }

        @media print {
            @page {
                size: landscape;
            }
        }
    </style>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                animationDuration: 200,
                theme: "light1", //dark1,dark2,light1,light2
                title: {
                    text: "IQAC - Student's Evaluation of Faculties",
                    fontSize: 30
                    // borderThickness: 2,
                    // padding: 6
                },
                subtitles: [{
                    text: "<?php echo $dept_name; ?>",
                    fontColor: "#5A55A3",
                    fontSize: 22,
                    fontStyle: "italic"
                }],
                axisX: {
                    title: "Faculties (Employee code)",
                    interval: 1
                },
                axisY: {
                    title: "Score",
                    //titleFontColor: "#4F81BC",
                    suffix: " pt",
                    includeZero: true,
                    tickLength: 0,
                    gridDashType: "dash",
                    stripLines: [{
                        value: <?php echo $mean;?>,
                        label: "Department Average - <?php echo $mean;?>",
                        labelFontColor: "#000000",
                        labelFontStyle: "italic",
                        thickness: 1.5,
                        showOnTop: true,
                        labelAlign: "center",
                        color: "#FF7300"

                        //startValue:0//,
                        //endValue:6,
                        //lineThickness:3,
                        //opacity: 0.2,
                        //lineDashType:'dot'
                    }]
                },
                toolTip: {
                    shared: true
                },
                data: [{
                    type: "spline",
                    color: "white",
                    name: "Average",
                    toolTipContent: "Faculty: <b>{label}</b><br><span style=\"color:#4F81BC\">{name}</span>: {y} pt",
                    markerColor: "white",
                    markerBorderColor: "black",
                    markerSize: 10,
                    markerBorderThickness: 1.5,
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                },
                    {
                        type: "error",
                        color: "#33558B",
                        name: "Standard Deviation",
                        toolTipContent: "<span style=\"color:#C0504E\">{name}</span>: {y[0]} pt - {y[1]} pt",
                        dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                    }]
            });
            chart.render();

        }
    </script>

</head>
<body>
<div id="chartContainer" style="height: 550px; width: 100%;"></div>

<?php
if (isset($_GET['chart']) && $_GET['chart'] != null && $_GET['chart'] != "--Select--") {

    echo "<div align=\"center\"><img class=\"center\" src=\"css/av2.JPG\" width=\"300\"></div>";
    echo "<div class=\"table-responsive\" style=\"padding-left: 0.5%\">";
    echo "<input type=\"button\" onclick=\"tableToExcel('myTable', 'W3C Example Table')\" value=\"Export to Excel\" 
            class='btn btn-success' style='float: right; margin-right: 1%'>";
    echo "<h2>Total Faculties Evaluated: $loop </h2>";
    echo "<h3>Department Average: $mean </h3>";

    $serial = 1;
    echo "<table class=\"table\" id='myTable'>
    <thead>
    <td onclick=\"sortTable(0)\"><h6>Serial No.</h6></td>
    <td onclick=\"sortTable(1)\"><h6>Faculty Name (Employee Code)</h6></td>
    <td onclick=\"sortTable(2)\"><h6>Courses</h6></td>
    <td onclick=\"sortTable(3)\"><h6>Semester</h6></td>
    <td onclick=\"sortTable(4)\"><h6>Average</h6></td>
    <td onclick=\"sortTable(5)\"><h6>Std. Deviation</h6></td>
    <td onclick=\"sortTable(6)\"><h6>No. of Students</h6></td>
    <td onclick=\"sortTable(7)\"><h6>Grand Std. Deviation</h6></td>
    <td onclick=\"sortTable(8)\"><h6>Grand Average</h6></td>
    </thead>";

    foreach ($faculty as $detail) {
        ?>
        <tr>
            <td><?php echo $serial; ?></td>
            <td><?php echo $detail[0]; ?></td>
            <td><?php echo $detail[1]; ?></td>
            <td><?php echo $detail[8]; ?></td>
            <td><?php echo $detail[2]; ?></td>
            <td><?php echo $detail[3]; ?></td>
            <td><?php echo $detail[4]; ?></td>
            <td><?php echo $detail[5]; ?></td>
            <td><?php echo $detail[6]; ?></td>
        </tr>
        <?php
        $serial++;
    }
    echo "</table></div>";


    unset($result, $row, $faculty_code, $faculty, $dataPoints1, $dataPoints2);
}
?>


<script src="js/canvasjs.min.js"></script>
<script>
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("myTable");
        switching = true;
        //Set the sorting direction to ascending:
        dir = "asc";
        /*Make a loop that will continue until
        no switching has been done:*/
        while (switching) {
            //start by saying: no switching is done:
            switching = false;
            rows = table.getElementsByTagName("TR");
            /*Loop through all table rows (except the
            first, which contains table headers):*/
            for (i = 1; i < (rows.length - 1); i++) {
                //start by saying there should be no switching:
                shouldSwitch = false;
                /*Get the two elements you want to compare,
                one from current row and one from the next:*/
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /*check if the two rows should switch place,
                based on the direction, asc or desc:*/
                if (dir === "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir === "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /*If a switch has been marked, make the switch
                and mark that a switch has been done:*/
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                //Each time a switch is done, increase this count by 1:
                switchcount++;
            } else {
                /*If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again.*/
                if (switchcount === 0 && dir === "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>
<script type="text/javascript">
    var tableToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
            ,
            template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"><head></head><body><table>{table}</table></body></html>'
            , base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            }
            , format = function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                })
            }
        return function (table, name) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            window.location.href = uri + base64(format(template, ctx))
        }
    })()
</script>

</body>
</html>