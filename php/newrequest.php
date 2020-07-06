<?php
    include ("./connections.php");
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST["subject"]){
            $description = $_POST["subject"];
        }
        if($_POST["usyd_no"]){
            $usyd_no = $_POST["usyd_no"];
        }
        if($_POST["priority"]){
            $priority = $_POST["priority"];
        }
        if($_POST["applicable"]){
            $applicable = $_POST["applicable"];
        }
        if($_POST["sre_name"]){
            $sre_name = $_POST["sre_name"];
        }
        if($_POST["prior"]){
            $prior = $_POST["prior"];
        }
        if($_POST["action"]){
            $action_after = $_POST["action"];
        }
        if($_POST["ssr_owner"]){
            $ssr_owner = $_POST["ssr_owner"];
        }
        if($_POST["exec_date"]){
            $exec_date = $_POST["exec_date"];
        }
        if($_POST["start_time"]){
            $start_time = $_POST["start_time"];
        }
        if($_POST["end_time"]){
            $end_time = $_POST["end_time"];
        }
        if($_POST["usyd_cat"]){
            $usyd_cat = $_POST["usyd_cat"];
            $dxc_cat = $usyd_cat;
        }
        if($_POST["description"]){
            $perform = $_POST["description"];
        }

        //date & time in a different time zone e.g. Australia/sydney
        //date_default_timezone_set('Australia/Sydney');
        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        //status of request
        $status = 'For change creation';
        
        //dxc_contact - PDLs
        if($usyd_cat === "asa"){
            $dxc_contact = "cyber_nss_unisyd@dxc.com";
        }
        if($usyd_cat === "backup"){
            $dxc_contact = "itogdcgocphbursusyd@dxc.com";
        }
        if($usyd_cat === "oracle"){
            $dxc_contact = "redrocksupport@dxc.com";
        }
        if($usyd_cat === "sql"){
            $dxc_contact = "rocksolid.sqlsupport@dxc.com";
        }
        if($usyd_cat === "f5"){
            $dxc_contact = "sim@dxc.com";
        }
        if($usyd_cat === "msv"){
            $dxc_contact = "anz-ph-cloudops-uos@dxc.com";
        }
        if($usyd_cat === "nsx"){
            $dxc_contact = "anz-ph-wintel-iaction@dxc.com";
        }
        if($usyd_cat === "aws"){
            $dxc_contact = "dxc_in_aws_cloudops_pod1@dxc.com";
        }
        if($usyd_cat === "azure"){
            $dxc_contact = "dxc_in_azure_cloudops_pod2@dxc.com";
        }
        if($usyd_cat === "storage"){
            $dxc_contact = "itogdcgocphbursusyd@dxc.com";
        }
        if($usyd_cat === "unix"){
            $dxc_contact = "BSS_ITO_GOC_PH_UNIX_USYD@dxc.com";
        }
        if($usyd_cat === "vmware"){
            $dxc_contact = "anz-ph-wintel-iaction@dxc.com";
        }
        if($usyd_cat === "wintel"){
            $dxc_contact = "anz-ph-wintel-iaction@dxc.com";
        }
        
        
            if($query = mysqli_query($connections, "INSERT INTO ssr_tracker(description, usyd_no, priority, applicable, sre_name, prior, action_after, ssr_owner, exec_date, start_time, end_time, usyd_cat, dxc_cat, perform, date, status, dxc_contact) 
            VALUES ('$description','$usyd_no','$priority','$applicable','$sre_name','$prior','$action_after','$ssr_owner','$exec_date','$start_time','$end_time','$usyd_cat','$dxc_cat','$perform', '$date', '$status', '$dxc_contact')")){



                        //IMPORTANT CHANGE THE FOLDER OF USYD_NO TO DXCSSR
            // Uploads files
        if (!$_POST['myfile']) {

            // Create Folder
            $query = mysqli_query($connections, "SELECT * FROM ssr_tracker ORDER BY dxc_ssr DESC LIMIT 1;");
            $row = mysqli_fetch_assoc($query);

            $dxc_ssr = $row['dxc_ssr'];

            if (!file_exists('../uploads/' . $row['dxc_ssr'])) {
                mkdir('../uploads/' . $row['dxc_ssr'], 0777, true);
            }
           
            $i = 0;
            foreach ($_FILES['myfile']['name'] as $filename){
                $destination = '../uploads/' . $row['dxc_ssr'] . '/' . $filename;
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                //temp
                $file = $_FILES['myfile']['tmp_name'][$i];
                $size = $_FILES['myfile']['size'][$i];
                //size
                if ($_FILES['myfile']['size'][$i] > 10000000) {
                    echo "File too large!";
                } 
                else {
                //temp to dest
                    if (move_uploaded_file($file, $destination)) {
                        $sql = "INSERT INTO ssr_files (dxc_ssr, name, size, downloads) VALUES ('".$row['dxc_ssr']."','$filename', $size, 0)";
                        if (mysqli_query($connections, $sql)) {
                            echo "File uploaded successfully";
                        }
                    } 
                    else {
                        echo "Failed to upload file.";
                    }
                }
                $i++;
            }
        }
                 
    }
    

    $msg = "Hi, \n\nThis is acknowledged.\nThe DXC SSR no. for this request is";

    //use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);
    
    // send email
    //mail($dxc_contact,$description,$msg);
    if(mail("arcedada@gmail.com",$description,$msg)){
       echo "Email successfully sent to";
    } else {
        echo "Email sending failed...";
      }
      
      

       //SNOW CREATION
                
       $category = "Software";
       $risk = $priority;
       $sdescription = $dxc_ssr . " - " . $usyd_no . " - " . $description;
       $time = "2020-06-14 06:22:29";
       $_SESSION['category'] = $category;
       $_SESSION['priority'] = $priority;
       $_SESSION['risk'] = $risk;
       $_SESSION['sdescription'] = $sdescription;
       $_SESSION['time'] = $time;
       $_SESSION['dxcssr'] = $dxc_ssr;

    }
?>
<script>
var category = <?php echo $category ?>;
var priority = <?php echo $priority ?>;
var risk = <?php echo $risk ?>;
var sdescription = <?php echo $sdescription ?>;
var time = <?php echo $time ?>;
var dxcssr = <?php echo $dxc_ssr ?>;

var requestBody = "{\"category\":\"" + category + "\",\"priority\":\"" + priority + "\",\"risk\":\"" + risk + "\",\"short_description\":\"" + sdescription +
    "\",\"start_date\":\"" + time + "\",\"end_date\":\"" + time + "\"}";

var client = new XMLHttpRequest();
client.open("post", "https://dev93193.service-now.com/api/sn_chg_rest/change/normal");

client.setRequestHeader('Accept', 'application/json');
client.setRequestHeader('Content-Type', 'application/json');

//Eg. UserName="admin", Password="admin" for this code sample.
client.setRequestHeader('Authorization', 'Basic ' + btoa('admin' + ':' + '!QAZxsw2#EDCvfr4'));

client.onreadystatechange = function() {
    if (this.readyState == this.DONE) {
        //document.getElementById("response").innerHTML = this.status + this.response;
        var res = this.response;
        parsedData = JSON.parse(res);
        window.location.href = "./normalpost.php?dxcssr=" + dxc_ssr + "&sys_id=" + parsedData.result.sys_id.value + "&number=" + parsedData.result.number.value + "&state=draft";
        //return [dxcssr, parsedData.result.sys_id.value, parsedData.result.number.value, "draft"];
        alert('New Ticket has been created!');
    }
};
client.send(requestBody);

</script>