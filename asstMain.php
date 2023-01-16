<?php //http://localhost/MaoManlinCodingAsst/asstMain.php
require_once("asstInclude.php");
require_once("clsDeleteSunglassRecord.php");
// main
date_default_timezone_set ('America/Toronto');
$mysqlObj=CreateConnectionObject(); 
$TableName = "Sunglasses"; 
// writeHeaders call  
WriteHeaders("Bluetooth Smart Sunglasses", "Sunglasses","asstStyle.css");
if (isset($_POST['f_CreateTable']))
  createTableForm($mysqlObj,$TableName);
else if (isset($_POST['f_Save'])) saveRecordtoTableForm($mysqlObj,$TableName) ;
   else if (isset($_POST['f_AddRecord'])) addRecordForm($mysqlObj,$TableName) ;	   
 else if (isset($_POST['f_DeleteRecord'])) 
  deleteRecordForm($mysqlObj,$TableName);	 
  else if (isset($_POST['f_DisplayData'])) 
    displayDataForm ($mysqlObj,$TableName);
    		else if (isset($_POST['f_IssueDelete'])) 
          issueDeleteForm ($mysqlObj,$TableName);
 		        else displayMainForm();
if (isset($mysqlObj)) $mysqlObj->close();
writeFooters();

function DisplayMainForm()
{
  echo "<form action=? method=post>";
  DisplayButton("f_CreateTable","Create Table","createTable.png");
  DisplayButton("f_AddRecord","Add Record","addRecord.png");
  DisplayButton("f_DeleteRecord","Delete Record","deleteRecord.png");
  DisplayButton("f_DisplayData","Display Data","displayData.png");
  echo "</form>";
}

function CreateTableForm(&$mysqlObj,$TableName)
{
  echo "<form action=? method=post>";
  $stmtObj=$mysqlObj->prepare("drop table if exists $TableName");
  $stmtObj->execute();
  
  $BrandName="BrandName varchar(10) PRIMARY KEY";
  $DateMan="DateMan date";
  $CameraMP="CameraMP int";
  $Color="Color varchar(15)";
  $query="Create table $TableName($BrandName,$DateMan,$CameraMP,$Color)";
 
  $stmtObj=$mysqlObj->prepare($query);
  if($stmtObj==false)
  {
    echo "prepare failed on query $query";
    exit;
  }
  $createResults=$stmtObj->execute();
  if($createResults)
    echo"Table $TableName Created";
  echo "<br>";
  DisplayButton("f_home","Home","home.png","home button");
  $stmtObj->close();
  echo "</form>";
}

function addRecordForm(&$mysqlObj,$TableName)
{
  echo"<div class=\"container\">";
  echo "<form action=? method=post>";
  
  
  echo "<div class=\"datapair\">";
  DisplayLabel("Brand Name ");
  DisplayTextbox("textBox","f_brandName",10,"");
  echo "</div>";
       
  echo "<div class=\"datapair\">";
  DisplayLabel("Date Manufactured ");
  DisplayTextbox("date","f_dataMan",5,"Y-m-d");
  echo "</div>";
  
  
  echo "<div class=\"datapair\" >";
  DisplayLabel("Camera");
  echo "<br>";
  echo "<div class=\"radio\">";

  DisplayTextbox("radio","f_camera",5,5,"checked");
  DisplayLabel(" 5MP");
  echo "<br>";
  DisplayTextbox("radio","f_camera",5,10);
  DisplayLabel(" 10MP");
  echo "</div>";
  echo "</div>";
          
  echo "<div class=\"datapair\">";
  DisplayLabel("Color ");
  DisplayTextbox("color","f_color",15,"#e66465");
  echo "</div>";
          
  DisplayButton("f_Save","Save Record","saveRecord.png");
  DisplayButton("home","Home","home.png","home button");
  
  echo "</form>";
  echo"<div>";
}

function saveRecordToTableForm(&$mysqlObj,$TableName)
{
  echo "<form action=? method=post>";

  $BrandName=$_POST["f_brandName"];
  $DateMan=$_POST["f_dataMan"];
  $CameraMP=$_POST["f_camera"];
  $Color=$_POST["f_color"];

  $query ="INSERT INTO $TableName 
  (BrandName, DateMan, CameraMP,Color) Values(?,?,?,?)";

  $stmtObj=$mysqlObj->prepare($query);
  if($stmtObj==false)
  {
    echo "prepare failed on query $query";
    exit;
  }

  $bindSuccess=$stmtObj->bind_param("ssis",$BrandName,
  $DateMan,$CameraMP,$Color);
  if($bindSuccess)
    $success = $stmtObj->execute();
  else
    echo "Bind failed: ".$stmtObj->error;

  if($success)
    echo "Record successfully added to $TableName";

  $stmtObj->close();
  echo "<br>";
  DisplayButton("home","Home","home.png");

  echo "</form>";
  
}

function displayDataForm(&$mysqlObj,$TableName)
{
  echo "<form action=? method=post>";

  $query="SELECT BrandName, DateMan, CameraMP,
  Color FROM $TableName ORDER BY BrandName";
  
  $stmtObj=$mysqlObj->prepare($query);
  if($stmtObj==false)
  {
    echo "prepare failed on query $query";
    exit;
  }

  $bindSuccess=$stmtObj->bind_result($BrandName,$DateMan,$CameraMP,$Color);
  if ($bindSuccess)
      $succes = $stmtObj -> execute();
  else
      echo"Bind failed.".$stmtObj->error;

  if($succes){
    echo "<table class= \"styled-table\">
            <thead>
              <tr>
                <th>BrandName</th>
                <th>DateMan</th>
                <th>CameraMP</th>
                <th>Color</th>
              </tr>
              </thead>";
      while($stmtObj->fetch())
      {
        echo"
        <tbody>
        <tr>
              <td> $BrandName</td>
              <td>$DateMan</td>
              <td>$CameraMP</td>
              <td bgcolor=\"$Color\" ></td>
            </tr>
            </tbody>";
      }
      echo "</table>";
  }
  
  DisplayButton("home","Home","home.png");

  echo "</form>";
}


function deleteRecordForm(&$mysqlObj,$TableName)
{
  echo "<form action=? method=post>";

    DisplayLabel("Please enter brand name to be deleted: ");
    DisplayTextbox("textbox","f_brandName",20,"");
    echo "<br>";
    DisplayLabel("The deletion is final");
    echo "<br>";
    DisplayButton("f_IssueDelete","Delete!","delete.png");
    DisplayButton("home","Home","home.png");

  echo "</form>";
}

function issueDeleteForm(&$mysqlObj,$TableName)
{
  echo "<form action=? method=post>";
  $BrandName=$_POST["f_brandName"];
  
  $deleteObj = new clsDeleteSunglassRecord; 
  $numberOfRecords = $deleteObj->deleteTheRecord($mysqlObj,$TableName,
  $BrandName);

  if($numberOfRecords==0)
      echo "$BrandName record does not exist";
  else
      echo "$BrandName deleted";

  echo"<br>";
  DisplayButton("home","Home","home.png");

  echo "</form>";
}
?>