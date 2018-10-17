<!---->
<!--/**-->
<!-- * User: Dhiraj-->
<!-- * Date: 5/8/2018-->
<!-- * Time: 8:35 PM-->
<!-- * Created On: PHP Storm-->
<!-- */-->

<?php

include "php_function.php";

if (isset($_GET['fac']) && $_GET['fac'] != null && $_GET['fac'] != "--Select--") {

    $fac = $_GET['fac'];
    $sem=$_GET['sem'];
    $dept = $_GET['dept'];

    $query = "SELECT * FROM $sem WHERE emp_code LIKE '$fac'";
    $mean = mean($fac,$sem);

    $course = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($course);


    $dept_mean=dept_mean($dept,$_GET['sem']);

    $fac_name = $fac;

//    $r = mysqli_query($connection,"SHOW COLUMNS FROM $sem LIKE 'desig'");
//    $exists = mysqli_num_rows($r)?TRUE:FALSE;
//
//    if($exists)
//        $fac_desig =  $row['desig'];
//    else
        $fac_desig='';

    $fac_dept = $row['dept_name'];

} else {
    $mean = 0;
    $fac_name = '';
    $fac_desig = '';
    $fac_dept = '';
    $dept_mean=0;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chart of Faculty Evaluation By Coursewise</title>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0">
    <style>
        @media print {
            @page  {
                size: landscape;
            }
        }
    </style>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                theme: "light1",
                animationEnabled: true,
                exportEnabled: true,
                animationDuration: 200,
                title: {
                    text: "IQAC Student's Evaluation of Faculty",
                    fontSize: 30
                },
                subtitles: [{
                    text: "<?php echo "Employee Code: ". $fac_name;?>",
                    fontColor: "#5A55A3",
                    fontSize: 25
                }, {
                    text: "<?php echo $fac_dept;?>",
                    fontColor: "#5A55A3",
                    fontSize: 20
                }],
                axisX: {
                    interval: 1,
                    title: "Questions"
                },
                axisY: {
                    title: "Score",
                    //titleFontColor: "#4F81BC",
                    suffix: " pt",
                    includeZero: true,
                    tickLength: 0,
                    gridDashType: "dash",
                    showInLegend: true,
                    stripLines: [{
                        value: <?php echo $mean;?>,
                        label: "Faculty Average - <?php echo $mean;?>",
                        labelFontColor: "#000000",
                        labelFontStyle: "italic",
                        thickness: 1.5,
                        showOnTop: true,
                        labelAlign: "center",
                        color: "#FF7300",
                        showInLegend: true
                    },{
                        value: <?php echo $dept_mean;?>,
                        label: "Department Average- <?php echo round($dept_mean,2);?>",
                        labelFontColor: "#000000",
                        labelFontStyle: "italic",
                        thickness: 3,
                        showOnTop: true,
                        labelAlign: "far",
                        color: "#132f98",
                        showInLegend: true,
                        lineDashType:'solid'
                    }]
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                data: [

                    <?php
                    if(isset($_GET['fac']) && $_GET['fac'] != null)
                    {
                    foreach ($course as $c_code)
                    {
                    $code = $c_code['course_code'];
                    //        echo $code;
                    $query = "SELECT * FROM $sem WHERE course_code LIKE '%$code%'";
                    global $connection;
                    $result = mysqli_query($connection, $query);

                    $row = mysqli_fetch_assoc($result);

                    ?>

                    {
                        type: "spline",
                        visible: true,
                        toolTipContent: "<b>{label}</b><br><span style=\"color:#4F81BC\">Course: {name}</span><br>Average: {y} pt",
                        showInLegend: true,
                        markerBorderColor: "black",
                        markerSize: 6,
                        markerBorderThickness: 1.5,
                        name: "<?php echo $code;?>",
                        dataPoints: [
                            {label: "Q1", y: <?php echo $row['AQ1'];?> },
                            {label: "Q2", y: <?php echo $row['AQ2'];?> },
                            {label: "Q3", y: <?php echo $row['AQ3'];?> },
                            {label: "Q4", y: <?php echo $row['AQ4'];?> },
                            {label: "Q5", y: <?php echo $row['AQ5'];?> },
                            {label: "Q6", y: <?php echo $row['AQ6'];?> },
                            {label: "Q7", y: <?php echo $row['AQ7'];?> },
                            {label: "Q8", y: <?php echo $row['AQ8'];?> },
                            {label: "Q9", y: <?php echo $row['AQ9'];?> },
                            {label: "Q10", y: <?php echo $row['AQ10'];?> },
                            {label: "Q11", y: <?php echo $row['AQ11'];?> },
                            {label: "Q12", y: <?php echo $row['AQ12'];?> },
                            {label: "Q13", y: <?php echo $row['AQ13'];?> },
                            {label: "Q14", y: <?php echo $row['AQ14'];?> },
                            {label: "Q15", y: <?php echo $row['AQ15'];?> },
                            {label: "Q16", y: <?php echo $row['AQ16'];?> },
                            {label: "Q17", y: <?php echo $row['AQ17'];?> },
                            {label: "Q18", y: <?php echo $row['AQ18'];?> }
                        ]
                    },

                    {
                        type: "error",
                        name: "Standard Deviation",
                        toolTipContent: "<span style=\"color:#C0504E\">{name}</span>: {y[0]} pt - {y[1]} pt",
                        dataPoints: [
                            {
                                label: "Q1",
                                y: [<?php echo round($row['AQ1'] - $row['SQ1'], 2) . "," . round($row['AQ1'] + $row['SQ1'], 2)?>]
                            },
                            {
                                label: "Q2",
                                y: [<?php echo round($row['AQ2'] - $row['SQ2'], 2) . "," . round($row['AQ2'] + $row['SQ2'], 2)?>]
                            },
                            {
                                label: "Q3",
                                y: [<?php echo round($row['AQ3'] - $row['SQ3'], 2) . "," . round($row['AQ3'] + $row['SQ3'], 2)?>]
                            },
                            {
                                label: "Q4",
                                y: [<?php echo round($row['AQ4'] - $row['SQ4'], 2) . "," . round($row['AQ4'] + $row['SQ4'], 2)?>]
                            },
                            {
                                label: "Q5",
                                y: [<?php echo round($row['AQ5'] - $row['SQ5'], 2) . "," . round($row['AQ5'] + $row['SQ5'], 2)?>]
                            },
                            {
                                label: "Q6",
                                y: [<?php echo round($row['AQ6'] - $row['SQ6'], 2) . "," . round($row['AQ6'] + $row['SQ6'], 2)?>]
                            },
                            {
                                label: "Q7",
                                y: [<?php echo round($row['AQ7'] - $row['SQ7'], 2) . "," . round($row['AQ7'] + $row['SQ7'], 2)?>]
                            },
                            {
                                label: "Q8",
                                y: [<?php echo round($row['AQ8'] - $row['SQ8'], 2) . "," . round($row['AQ8'] + $row['SQ8'], 2)?>]
                            },
                            {
                                label: "Q9",
                                y: [<?php echo round($row['AQ9'] - $row['SQ9'], 2) . "," . round($row['AQ9'] + $row['SQ9'], 2)?>]
                            },
                            {
                                label: "Q10",
                                y: [<?php echo round($row['AQ10'] - $row['SQ10'], 2) . "," . round($row['AQ10'] + $row['SQ10'], 2)?>]
                            },
                            {
                                label: "Q11",
                                y: [<?php echo round($row['AQ11'] - $row['SQ11'], 2) . "," . round($row['AQ11'] + $row['SQ11'], 2)?>]
                            },
                            {
                                label: "Q12",
                                y: [<?php echo round($row['AQ12'] - $row['SQ12'], 2) . "," . round($row['AQ12'] + $row['SQ12'], 2)?>]
                            },
                            {
                                label: "Q13",
                                y: [<?php echo round($row['AQ13'] - $row['SQ13'], 2) . "," . round($row['AQ13'] + $row['SQ13'], 2)?>]
                            },
                            {
                                label: "Q14",
                                y: [<?php echo round($row['AQ14'] - $row['SQ14'], 2) . "," . round($row['AQ14'] + $row['SQ14'], 2)?>]
                            },
                            {
                                label: "Q15",
                                y: [<?php echo round($row['AQ15'] - $row['SQ15'], 2) . "," . round($row['AQ15'] + $row['SQ15'], 2)?>]
                            },
                            {
                                label: "Q16",
                                y: [<?php echo round($row['AQ16'] - $row['SQ16'], 2) . "," . round($row['AQ16'] + $row['SQ16'], 2)?>]
                            },
                            {
                                label: "Q17",
                                y: [<?php echo round($row['AQ17'] - $row['SQ17'], 2) . "," . round($row['AQ17'] + $row['SQ17'], 2)?>]
                            },
                            {
                                label: "Q18",
                                y: [<?php echo round($row['AQ18'] - $row['SQ18'], 2) . "," . round($row['AQ18'] + $row['SQ18'], 2)?>]
                            }
                        ]
                    },

                    <?php }

                    }

                    else {
                        echo "{
		type: 'spline',
			name: 'Predicted',
			markerType: 'none',
			dataPoints: []
		},
		{
			type: 'error',
			name: 'Error Range',
			dataPoints: []
	}";
                    }

                    ?>
                ]
            });
            chart.render();

            function toggleDataSeries(e) {
                var index = e.dataSeriesIndex;
                index += 1;

                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                    chart.data[index].set("visible", false);
                }
                else {
                    e.dataSeries.visible = true;
                    chart.data[index].set("visible", true);
                }
                chart.render();
            }
        }
    </script>
</head>
<body>
<div id="chartContainer" style="height: 550px; width: 90%;" class="ml-4"></div>
<div style='padding: 0 2%'>
<?php
if (isset($_GET['fac']) && $_GET['fac'] != null){
echo"<div ><img class=\"center\" src=\"css/av.JPG\" width=\"500\" style='margin-left:25%'></div>";
    foreach ($course as $c_code){
    $code = $c_code['course_code'];
    $query = "SELECT * FROM $sem WHERE course_code LIKE '%$code%'";
    global $connection;
    $result = mysqli_query($connection, $query);

    $row = mysqli_fetch_assoc($result);
    echo "<h3>Course: " . $code . "</h3>";
    echo "<h4>No. of Student Participated: " . $row['total_assessed'] . "</h4>";
    echo "<h5><i>".$row['semester'] . " Semester</i></h5>";
    ?>
    <table class="table">
        <tr>
        <td></td>
        <td><b>Q1</b></td>
        <td><b>Q2</b></td>
        <td><b>Q3</b></td>
        <td><b>Q4</b></td>
        <td><b>Q5</b></td>
        <td><b>Q6</b></td>
        <td><b>Q7</b></td>
        <td><b>Q8</b></td>
        <td><b>Q9</b></td>
        <td><b>Q10</b></td>
        <td><b>Q11</b></td>
        <td><b>Q12</b></td>
        <td><b>Q13</b></td>
        <td><b>Q14</b></td>
        <td><b>Q15</b></td>
        <td><b>Q16</b></td>
        <td><b>Q17</b></td>
        <td><b>Q18</b></td>
        </tr>

        <tr>
            <td><b>Avg</b></td>
            <?php
            echo "<td>" . $row['AQ1'] . "</td>";
            echo "<td>" . $row['AQ1'] . "</td>";
            echo "<td>" . $row['AQ3'] . "</td>";
            echo "<td>" . $row['AQ4'] . "</td>";
            echo "<td>" . $row['AQ5'] . "</td>";
            echo "<td>" . $row['AQ6'] . "</td>";
            echo "<td>" . $row['AQ7'] . "</td>";
            echo "<td>" . $row['AQ8'] . "</td>";
            echo "<td>" . $row['AQ9'] . "</td>";
            echo "<td>" . $row['AQ10'] . "</td>";
            echo "<td>" . $row['AQ11'] . "</td>";
            echo "<td>" . $row['AQ12'] . "</td>";
            echo "<td>" . $row['AQ13'] . "</td>";
            echo "<td>" . $row['AQ14'] . "</td>";
            echo "<td>" . $row['AQ15'] . "</td>";
            echo "<td>" . $row['AQ16'] . "</td>";
            echo "<td>" . $row['AQ17'] . "</td>";
            echo "<td>" . $row['AQ18'] . "</td>"; ?>
        </tr>

        <tr>
            <td><b>SD</b></td>
            <?php
            echo "<td>" . round($row['SQ1'], 2) . "</td>";
            echo "<td>" . round($row['SQ1'], 2) . "</td>";
            echo "<td>" . round($row['SQ3'], 2) . "</td>";
            echo "<td>" . round($row['SQ4'], 2) . "</td>";
            echo "<td>" . round($row['SQ5'], 2) . "</td>";
            echo "<td>" . round($row['SQ6'], 2) . "</td>";
            echo "<td>" . round($row['SQ7'], 2) . "</td>";
            echo "<td>" . round($row['SQ8'], 2) . "</td>";
            echo "<td>" . round($row['SQ9'], 2) . "</td>";
            echo "<td>" . round($row['SQ10'], 2) . "</td>";
            echo "<td>" . round($row['SQ11'], 2) . "</td>";
            echo "<td>" . round($row['SQ12'], 2) . "</td>";
            echo "<td>" . round($row['SQ13'], 2) . "</td>";
            echo "<td>" . round($row['SQ14'], 2) . "</td>";
            echo "<td>" . round($row['SQ15'], 2) . "</td>";
            echo "<td>" . round($row['SQ16'], 2) . "</td>";
            echo "<td>" . round($row['SQ17'], 2) . "</td>";
            echo "<td>" . round($row['SQ18'], 2) . "</td>";

            echo "</tr></table>";
            }

            echo "\n<h3 style='float: right; margin-right: 2%;'>Grand Average: " . $mean . "</h3>";
            }

            unset($result,$row);
        ?>
</div>
        <script src="js/canvasjs.min.js"></script>
</body>
</html>
