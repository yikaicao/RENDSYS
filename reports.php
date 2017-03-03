<!DOCTYPE html>
<html lang="en">

<head>
    <title>UA-CEAC's Lunar Greenhouse</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/formStyle.css">
    <style>

  .area {
    fill: steelblue;
    clip-path: url(#clip);
  }

  </style>
</head>


<script src="https://d3js.org/d3.v4.min.js"></script>




  <?php
  //define variabe
  $startDate = $endDate = $dataChosen = "";
  $startErr = $endErr = $dataErr = "";
  if ($_SERVER["REQUEST_METHOD"] = "POST") {
    if (empty($_POST["startDate"])) {
      $startErr = "startDate is required.";
    }else{
      $startDate = $_POST["startDate"];
    }

    if (empty($_POST["endDate"])) {
      $endErr = "endDate is required.";
    }else{
      $endDate = $_POST["endDate"];
    }

    if (empty($_POST["dataChosen"])) {
      $dataErr = "startDate is required.";
    }else{
      $dataChosen = $_POST["dataChosen"];
    }

  }
  ?>
<script>
//Make other headers inactive
document.getElementById('overviewHead').setAttribute('class', "inactive");
document.getElementById('reportsHead').setAttribute('class', "active");
document.getElementById('loginHead').setAttribute('class', "inactive");
document.getElementById('alarmsHead').setAttribute('class', "inactive");

document.getElementById('pagestyle').setAttribute('href', "css/formStyle.css");
</script>	
<!--Added code (and stylesheet)-->
<div id="wholeForm">
<h3>Generate Reports - View Stored Data</h3>
<form action="" method="post">
<!--<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">-->
<div>
  <div class="myRow">
  <div class="fLeft">
  <fieldset>
  <legend>Data Collection</legend>
  <select name="dCollection" id="dCollection">
  <option value=1 selected="selected">Lunar Greenhouse</option>
  </select>
  </fieldset>
  </div>

  <div class="fRight" id="dataInfo">
  <fieldset>
  <legend>Data Information</legend>
  First Entry: 
  <?php
  $con = mysql_connect("yikaicao.com", "cs436db", "cs436db");
 // $con = mysql_connect("localhost", "root");
  if (!$con)
    {
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("cr3000", $con);

  $firstEntry = mysql_query("SELECT TIMESTAMP, Record From cr3000_Table
  order by TIMESTAMP asc limit 1");

  while($row = mysql_fetch_array($firstEntry)){
    echo $row['TIMESTAMP'] ." " . $row['Record'];
    //$firstRecord = $row['Record'];
    $firstDate = strtotime($row['TIMESTAMP']);
    echo "<br />";
  }
  ?>
  Latest Entry: 
  <?php
  $con = mysql_connect("yikaicao.com", "cs436db", "cs436db");
 // $con = mysql_connect("localhost", "root");
  if (!$con)
    {
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("cr3000", $con);

  $lastEntry = mysql_query("SELECT TIMESTAMP, Record From cr3000_Table
  order by TIMESTAMP desc limit 1");

  while($row = mysql_fetch_array($lastEntry)){
    echo $row['TIMESTAMP'] ." " . $row['Record'];
    //$lastRecord = $row['Record'];
    $latestDate = strtotime($row['TIMESTAMP']);
    echo "<br />";
  }
  ?>
  Entry Interval: <?php  
  $con = mysql_connect("yikaicao.com", "cs436db", "cs436db");
 // $con = mysql_connect("localhost", "root");
  if (!$con)
    {
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("cr3000", $con);

  $sql = mysql_query("SELECT COUNT(Record) From cr3000_Table");

  while($row = mysql_fetch_array($sql)){
    //echo $row['TIMESTAMP'] ." " . $row['Record'];
    $rowCount = $row[0];
  }
  $timeDiff = ($latestDate - $firstDate)/ ($rowCount - 1);
  $min = floor($timeDiff / 60);
  echo $min;
  ?>(min)
  </fieldset>
  </div>
  </div>
  </div>
  
  <div class="myRow">
    <div class="fLeft">
    <fieldset>
    <legend>Available Data</legend>
    <select name="dataType" id="dataType" class="largeSelect" multiple="multiple">
    <option>Loading...</option>
    </select>
    <br>
    <button type=button name="selectButton" id="selectButton" onclick="moveData('dataType','dataChosen')">Select</button>
    </fieldset>
    </div>
    <div class="fRight">
    <fieldset>
    <legend>Data Selections</legend>
    <select name="dataChosen[]" id="dataChosen" class="largeSelect" multiple="multiple">
    </select>
    <br>
    <button type=button name="removeButton" id="removeButton" onclick="moveData('dataChosen','dataType')">Remove</button>
    </fieldset>
    </div>	
    
    
  </div>
  <br><br><br><br>
  <div class="myRow">
  <div class="fLeft">
  <fieldset>
  <legend>Data Time Frame</legend>
  Start:
  <input type="datetime-local" name="startDate" id="startDate">
  <br>
  End:
  <input type="datetime-local" name="endDate" id="endDate">
  </fieldset>
  </div>

  <div class="fRight">
  <fieldset>
  <legend>Data Output</legend>
  <input type="radio" name="output" id="screen" value="screen" checked>Output Data to Screen
  <br>
  <input type="radio" name="output" id="graph" value="graph">Output Graph to Screen (Only choose 1 data attribute)
  <br>
  <!--<input type="radio" name="output" id="excel" value="excel" >Output Data to Excel-->
  <br>
  </fieldset>
  </div>

  </div>
  
   
   
  <div class="cBoth">
  <input type="submit" name="generateButton" id="generateButton" value="Generate">
  </div>
	
	
  </form>
</form>
<script>

  //Move data from available to selections
  function moveData(src, dest){
    var srcData = document.getElementById(src);
    var destData = document.getElementById(dest);

    var selectedOption = srcData.options[srcData.selectedIndex];
    var len = srcData.selectedOptions.length;
    for(i = 0; i < len; i++){
      destData.add(srcData.selectedOptions[0]);
    }
    
  }


  function getDataTypes(id){
    var dataText;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
      if(this.readyState == 4 && this.status == 200){
        //Split text by newline, then comma
        var optionHTML = ""
        var values = this.responseText.split('\n');
        values = values[1].split(',');

        //Remove double quotes and parenthesis and add underscore
        for(i = 0; i < values.length; i++){
          values[i] = values[i].replace(/["]+/g, '').replace(/[(]+/g, '_').replace(/[)]+/g, '');
        }

        //Skip first value, which is TIMESTAMP
        for(i = 1; i < values.length; i++){
          if(i == 1){
            optionHTML = optionHTML + '<option value=' + values[i] + ' selected="selected">' + values[i] + '</option>';
          }else{
            optionHTML = optionHTML + '<option value=' + values[i] + '>' + values[i] + '</option>';
          }
        }
        document.getElementById(id).innerHTML = optionHTML;
      }
    };
    xhttp.open("GET", "dat/CR3000.dat", true);
    xhttp.send();
  }
  getDataTypes("dataType");
	
	
</script>

<?php
//echo "<div class = "center">";
echo ("<h3>Generate Result is: </h3>");
$wantedField = "*";
// $data_select = $_POST["dataType"];
// echo $data_select;
echo ("<p> startDate is $startDate</p>");
echo ("<p> endDate is $endDate </p>");
  echo "<br>";


    if ($_POST["output"] == "graph"){
      echo  '<svg id="svg_graph" width="960" height="500"></svg>';
    }
$data_chosen = $dataChosen;

foreach ($data_chosen as $value) {
  if (strcmp($value, "RECORD") == 0) {
      $value = "Record";
  }
  echo ("<p>data chosen are: $value</p>");
}

//$condition = "2015-08-01 00:00:00";
$con = mysql_connect("yikaicao.com", "cs436db", "cs436db");
 // $con = mysql_connect("localhost", "root");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("cr3000", $con);

 //mysql_select_db("LunarGreenHouse", $con);
$result = mysql_query("SELECT $wantedField From cr3000_Table
  WHERE TIMESTAMP >= '$startDate' 
  AND TIMESTAMP <= '$endDate'");

$fp = fopen('data.csv', 'w');

$title = array("TIMESTAMP", $data_chosen[0]);
fputcsv($fp, $title);

while($row = mysql_fetch_array($result))
  {
  //echo "Temperature<br>";
  foreach ($data_chosen as $outputData) {
    if (strcmp($outputData, "RECORD") == 0) {
      $outputData = "Record";
    }
    $time = $row["TIMESTAMP"];
    str_replace('"', '', $time);
    $field = array($time, $row[$outputData]);
    fputcsv($fp, $field);
    if ($_POST["output"] == "screen"){
    echo $row["TIMESTAMP"]. ": ";
    echo $outputData;
    echo ": ";
    echo $row[$outputData];
    echo "<br />";
  }
  }
  }

 fclose($fp);

//exit;
?>


<script>

var svg = d3.select("svg"),
    margin = {top: 20, right: 20, bottom: 30, left: 40},
    width = +svg.attr("width") - margin.left - margin.right,
    height = +svg.attr("height") - margin.top - margin.bottom;

var parseDate = d3.timeParse("%Y-%m-%d %H:%M:%S");

var x = d3.scaleTime().range([0, width]),
    y = d3.scaleLinear().range([height, 0]);

var xAxis = d3.axisBottom(x),
    yAxis = d3.axisLeft(y);

var zoom = d3.zoom()
    .scaleExtent([1, 32])
    .translateExtent([[0, 0], [width, height]])
    .extent([[0, 0], [width, height]])
    .on("zoom", zoomed);

var area = d3.area()
    .curve(d3.curveMonotoneX)
    .x(function(d) { return x(d.TIMESTAMP); })
    .y0(height)
    .y1(function(d) { return y(d.diff); });

svg.append("defs").append("clipPath")
    .attr("id", "clip")
  .append("rect")
    .attr("width", width)
    .attr("height", height);

var g = svg.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

var attrName = "Attribute";
d3.csv("data.csv", type, function(error, data) {
  if (error) throw error;

  x.domain(d3.extent(data, function(d) { return d.TIMESTAMP; }));
  y.domain([d3.min(data, function(d) { return d.diff; }), d3.max(data, function(d) { return d.diff; })]);

  g.append("path")
      .datum(data)
      .attr("class", "area")
      .attr("d", area);

  g.append("g")
      .attr("class", "axis axis--x")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
      .append("text")
      .attr("transform", "rotate(-90)")
      .attr("x", (height/2))
      .attr("y", -30)
      .attr("fill", "black")
      .text(attrName);

  g.append("g")
      .attr("class", "axis axis--y")
      .call(yAxis)
      .append("text")
      .attr("x", (width/2))
      .attr("y", height + 30)
      .attr("fill", "black")
      .text("Time");

  //Gratuitous intro zoom!
  svg.call(zoom).transition()
      .duration(1500)
      .call(zoom.transform, d3.zoomIdentity
          .scale(1)
          .translate(0, 0));
});

function zoomed() {
  var t = d3.event.transform, xt = t.rescaleX(x);
  g.select(".area").attr("d", area.x(function(d) { return xt(d.TIMESTAMP); }));
  g.select(".axis--x").call(xAxis.scale(xt));
}

function type(d) {
  var i = 0;
  var x;
  for(x in d){
    if(i > 0){
      d.diff = +d[x];
      attrName = x;
    }
    i = i + 1;
  }
  d.TIMESTAMP = parseDate(d.TIMESTAMP);
  //d.diff = d.diff;
  return d;
}
</script>

</div>


</html>
