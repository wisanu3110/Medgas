<?php
    include 'dbconnect.php';
 
    $sql = "SELECT * FROM ORdata ORDER by N DESC LIMIT 60";
    $result      = $dbh->query($sql);
    $resultchart = $dbh->query($sql);

    //for chart
    $temp = array();
    $humi = array();
    $O2 = array();
    $CO2 = array();
    $time = array();
    while($rs = mysqli_fetch_array($resultchart)){ 
        $temp[] = "\"".$rs['Temp']."\"";
        $humi[] = "\"".$rs['Humid']."\"";
        $O2[] = "\"".$rs['O2']."\"";
        $CO2[] = "\"".$rs['CO2']."\"";
        $time[] = "\"".$rs['day']."\"";  
    }  
    $temp = array_reverse($temp);
    $humi = array_reverse($humi);
    $O2 = array_reverse($O2);
    $CO2 = array_reverse($CO2);
    $time = array_reverse($time);

    $temp = implode(",", $temp);
    $humi = implode(",", $humi);
    $O2 = implode(",", $O2);
    $CO2 = implode(",", $CO2);
    $time = implode(",", $time);
    
    date_default_timezone_set('Asia/Bangkok');
    $date  = date("Y-m-d");
    $datetime = date("H:i:s");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="10">
    <title>MedGas-Monitor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    
    <style>
    * {
    box-sizing: border-box;
    }
    [class*="col-"] {
        float: left;
    }
        table,
        thead,
        tr,
        tbody,
        th,
        td {
            text-align: center;
        } 
        .medgas{
            color: white;
            text-align: center;
            background-color: rgba(135,206,235,1);
            font-family: Arial;
        }
        
        /* For mobile phones: */
        [class*="col-"] {
            width: 100%;
        }

        @media only screen and (min-width: 768px) {
            /* For desktop: */
            .col-1 {width: 8.33%;}
            .col-2 {width: 16.66%;}
            .col-3 {width: 25%;}
            .col-4 {width: 33.33%;}
            .col-5 {width: 41.66%;}
            .col-6 {width: 50%;}
            .col-7 {width: 58.33%;}
            .col-8 {width: 66.66%;}
            .col-9 {width: 75%;}
            .col-10 {width: 83.33%;}
            .col-11 {width: 91.66%;}
            .col-12 {width: 100%;}
        }

        @media screen and (min-width: 600px) {
            div.example {
                font-size: 60px;
            }
            .head1{
                font-size: 14px;
            }
            .imhead1{
                width: 50%
                
            }
            .monitor{
                color: white;
                font-size: 60px;
            }
            .hmonitor{
                color: white;
                font-size: 40px;
            }
            .hgraph{
                font-size: 32px;
                text-align: center;
            }
        }

        @media screen and (max-width: 600px) {
            div.example {
                font-size: 35px;
            }
            .head1{
                font-size: 11px;
            }
            .monitor{
                color: white;
                font-size: 40px;
            }
            .hmonitor{
                color: white;
                font-size: 30px;
            }
            .hgraph{
                font-size: 20px;
                text-align: center;
            }
        }
    </style>

</head>
<body class="w3-light-grey">
<!-- Top container -->

<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <span class="col-3 w3-bar-item w3-left">
  <img src="https://sv1.picz.in.th/images/2019/05/15/wDk01Z.png" alt="wDk01Z.png" border="0" width=43 height=43>
    <span class="head1"> MEDICAL GAS DEVICE MONITORING IN OPERATING ROOM </span>
  </span>
</div>
<div class="w3-bar w3-bottom w3-black w3-large" style="z-index:4">
  <span class="col-3 w3-bar-item w3-right">
    <span class="head1">Powered by KMUTNB & RJ Hospital</span>
    <img src="https://sv1.picz.in.th/images/2019/05/15/wD4B9W.png" alt="wD4B9W.png" border="0" width=43 height=43>
    <img src="https://sv1.picz.in.th/images/2019/05/15/wD4fb2.png" alt="wD4fb2.png" border="0" width=43 height=43>
  </span>
</div>



<!-- !PAGE CONTENT! -->
<div class="w3-main col-12" style="margin-top:60px; margin-bottom:60px;">

<?php
    include 'dbconnect.php';
 
    $sql = "SELECT * FROM ORdata ORDER by N DESC LIMIT 12";
    $sqlTemp = "SELECT * FROM ORdata ORDER by N DESC LIMIT 1";
    $sqlHumid = "SELECT * FROM ORdata ORDER by N DESC LIMIT 1";
    $sqlO2 = "SELECT * FROM ORdata ORDER by N DESC LIMIT 1";
    $sqlCO2 = "SELECT * FROM ORdata ORDER by N DESC LIMIT 1";

    $result = $dbh->query($sql);
    $resultTemp = $dbh->query($sqlTemp);
    $resultHumid = $dbh->query($sqlHumid);
    $resultO2 = $dbh->query($sqlO2);
    $resultCO2 = $dbh->query($sqlCO2);
    ?>
    <div class="medgas example">Medical Gas Monitoring</div>
    <br>
    <!-- monitor -->
    <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
    <?php 
            while ($TempD = mysqli_fetch_array($resultTemp)){
                $TempCheck = number_format($TempD['Temp'],2);
            }
    if($TempCheck > 27 || $TempCheck < 14){$colorT = 'red';}
    else if(($TempCheck>24 && $TempCheck<=27)||($TempCheck<17 && $TempCheck>=14)){$colorT = 'yellow';}
    else{$colorT = 'green';} 
    echo '<div class= "w3-container w3-'. $colorT .' ' .'w3-padding-16">'; 
    ?>
        <div class="w3-left"><img src="https://sv1.picz.in.th/images/2019/05/14/wlO4cJ.png" alt="wlO4cJ.png" border="0"></div>
        <div class="w3-right">
          <h3 class="monitor">
          <?php echo $TempCheck; ?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4 class="hmonitor">Temperature(*C)</h4>
      </div>
    </div>
    <div class="w3-quarter">
    <?php 
            while ($HumidD = mysqli_fetch_array($resultHumid)){
                $HumidCheck = number_format($HumidD['Humid'],2);
            }
    if($HumidCheck > 70 || $HumidCheck < 30){$colorH = 'red';}
    else if(($HumidCheck>=30 && $HumidCheck<40)||($HumidCheck<=70 && $HumidCheck>60)){$colorH = 'yellow';}
    else{$colorH = 'green';} 
    echo '<div class= "w3-container w3-'. $colorH .' ' .'w3-padding-16">'; 
    ?>
        <div class="w3-left"><img src="https://sv1.picz.in.th/images/2019/05/14/wlOklq.png" alt="wlOklq.png" border="0"></div>
        <div class="w3-right">
          <h3 class="monitor">
          <?php echo $HumidCheck; ?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4 class="hmonitor">Humidity(%)</h4>
      </div>
    </div>
    <div class="w3-quarter">
    <?php 
            while ($O2D = mysqli_fetch_array($resultO2)){
                $O2Check = number_format($O2D['O2'],2);
            }
    if($O2Check < 16){$colorO2 = 'red';}
    else if($O2Check < 18){$colorO2 = 'yellow';}
    else{$colorO2 = 'green';} 
    echo '<div class= "w3-container w3-'. $colorO2 .' ' .'w3-padding-16">'; 
    ?>
        <div class="w3-left"><img src="https://sv1.picz.in.th/images/2019/05/14/wlO3Gz.png" alt="wlO3Gz.png" border="0"></div>
        <div class="w3-right">
          <h3 class="monitor">
          <?php echo $O2Check; ?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4 class="hmonitor">Oxygen(%)</h4>
      </div>
    </div>
    <div class="w3-quarter">
    <?php 
            while ($CO2D = mysqli_fetch_array($resultCO2)){
                $CO2Check = number_format($CO2D['CO2'],2);
            }
    if($CO2Check > 0.15){$colorCO2 = 'red';}
    else if($CO2Check > 0.1 && $CO2Check <= 0.15){$colorCO2 = 'yellow';}
    else{$colorCO2 = 'green';} 
    echo '<div class= "w3-container w3-'. $colorCO2 .' ' .'w3-padding-16">'; 
    ?>
        <div class="w3-left"><img src="https://sv1.picz.in.th/images/2019/05/14/wlOtqR.png" alt="wlOtqR.png" border="0"></div>
        <div class="w3-right">
          <h3 class="monitor">
          <?php echo $CO2Check;?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4 class="hmonitor">Carbondioxide(%)</h4>
      </div>
    </div>
  </div>

  <!-- Table -->
    <div class="container">
        <br>
        <h2 align="center" ><font size="5">Date: <?php echo $date.'   '. 'Time: '.$datetime; ?></font></h2>
        <table class="table table-bordered" width="400px">
            <thead>
                <tr>
                    <th>TEMP</th>
                    <th>HUMID</th>
                    <th>O2</th>
                    <th>CO2</th>
                    <th>TIME</th>
                </tr>
            </thead>
            <?php 
                while($row = mysqli_fetch_array($result)) {
                    echo "<tr class='success'>";
                    echo "<td>" . number_format($row['Temp'],2) . "</td>";
                    echo "<td>" . number_format($row['Humid'],2) . "</td>";
                    echo "<td>" . number_format($row['O2'],2) . "</td>";
                    echo "<td>" . number_format($row['CO2'],2) . "</td>";
                    echo "<td>" . $row['day'] . "</td>"; 
                    echo "</tr>"; 
                }
                $dbh->close();
            ?>
        </table>
    </div>
    <hr>
    <br>

    <!-- Graph -->
    <div>
        <h3 class="hgraph"> ข้อมูลย้อนหลังอุณหภูมิ 30 นาที </h3>
        <div align="center">
        <div style="margin-right:43px;">
            <canvas id="myChart" width="800px" height="360px"></canvas>
        </div> 
        </div>

        <br>
        <h3 class="hgraph"> ข้อมูลย้อนหลังความชื้นสัมพัทธ์ 30 นาที </h3>
        <div align="center">
        <div style="margin-right:43px;">
            <canvas id="myChart1" width="800px" height="360px"></canvas>
        </div>  
        </div>

        <br>
        <h3 class="hgraph"> ข้อมูลย้อนหลังออกซิเจน 30 นาที </h3>
        <div align="center">
        <div style="margin-right:43px;">
            <canvas id="myChart2" width="800px" height="360px"></canvas> 
        </div>
        </div>

        <br>
        <h3 class="hgraph"> ข้อมูลย้อนหลังคาร์บอนไดออกไซด์ 30 นาที </h3>
        <div align="center">
        <div style="margin-right:43px;">
            <canvas id="myChart3" width="800px" height="360px"></canvas> 
        </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w3-container w3-padding-16 w3-light-grey">
    <h4>OR RJHospital</h4>
    <p>Powered by Biomedical Engineer</p>
    </footer>
</body>
</html>

<script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo $time;?>],
            datasets: [{
                label: 'Temperature (*C)',
                data: [<?php echo $temp;?>],
                backgroundColor : "rgba(255, 0, 0, 0.8)",
                borderColor : "rgba(255, 0, 0, 0.8)",
                fill : false,
                lineTension : 0,
                pointRadius : 3
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        max: 40,
                        min: 10,
                        stepSize: 6
                    }
                }]
            }
        }
    });

    var ctx = document.getElementById("myChart1").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo $time;?>],
            datasets: [{
                label: 'Humidity (%)',
                data: [<?php echo $humi;?>],
                backgroundColor : "rgba(0,0,255,0.8)",
                borderColor : "rgba(0,0,255,0.8)",
                fill : false,
                lineTension : 0,
                pointRadius : 3
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var ctx = document.getElementById("myChart2").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo $time;?>],
            datasets: [{
                label: 'Oxygen (%)',
                data: [<?php echo $O2;?>],
                backgroundColor : "rgba(0,255,0,0.8)",
                borderColor : "rgba(0,255,0,0.8)",
                fill : false,
                lineTension : 0,
                pointRadius : 3
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        max: 24,
                        min: 12,
                        stepSize: 2
                    }
                }]
            }
        }
    });

    var ctx = document.getElementById("myChart3").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo $time;?>],
            datasets: [{
                label: 'Carbondioxide (%)',
                data: [<?php echo $CO2;?>],
                backgroundColor : "rgba(255,165,0,0.8)",
                borderColor : "rgba(255,165,0,0.8)",
                fill : false,
                lineTension : 0,
                pointRadius : 3
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        max: 0.3,
                        min: 0,
                        stepSize: 0.05
                    }
                }]
            }
        }
    });
</script> 