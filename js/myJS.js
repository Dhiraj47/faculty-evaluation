/*

* User: Dhiraj,Koyel
 * Date: 5/8/2018
 * Time: 8:30 PM

 */

function update_faculty(str,sem) {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById("faculties").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "php_function.php?opt=" + str+"&sem="+sem, true);
        xmlhttp.send();
}

function update_dept(str) {
     if(str==='--Select--')
        document.getElementById("faculties").innerHTML = '<option>--Select--</option>';

     else{
         var xmlhttp = new XMLHttpRequest();

         xmlhttp.onreadystatechange = function () {
             if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                 document.getElementById("dept_code").innerHTML = xmlhttp.responseText;
             }
         }

         xmlhttp.open("GET", "php_function.php?semester=" + str, true);
         xmlhttp.send();
     }

}

