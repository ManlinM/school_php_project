<?php

//function write headers
function WriteHeaders($Heading = "Welcome", $TitleBar = "MySite",$FileName="")
{
	echo "
        <!doctype html>
        <html lang=\"en\">

        <head>
	        <meta charset=\"UTF-8\">
	        <title>$TitleBar</title>
        </head>
        <link rel =\"stylesheet\" type = \"text/css\" href=\"$FileName\"/>
        <body>
        <h1>$Heading - Manlin Mao</h1>\n";
}


//function display label
function DisplayLabel($TextInput)
{
    echo "<label>$TextInput</label>";
}

//function display textbox
function DisplayTextbox($InputV,$Name,$Size,$Value=0,$Checked="")
{
    
    echo "<input type=$InputV name=\"$Name\" Size=$Size value=$Value $Checked>";
}

//function to display an image
function DisplayImage($FileName,$Alt,$Height=200,$Width=150)
{
    echo "<img src = $FileName alt=$Alt height=$Height width= $Width>";
}

//function that will display buttons
function DisplayButton($Name,$Text,$FileName="",$Alt="")
{
    if ($FileName=== "")
    {
        echo" <button name=\"$Name\" 
        >$Text";
        echo "</button>";}
       else 
     {
        echo " <button name=\"$Name\" 
        >";
        DisplayImage($FileName,$Alt,$Height=100,$Width=80);
         echo "</button>";
        }
}

//function display contact information
function DisplayContactInfo(){
    echo "<footer>Questions? Comments?
   <a href= \"mailto:manlin.mao@student.sl.on.ca\"> manlin.mao@student.sl.on.ca</a></footer>
    ";
}

//footer function
function WriteFooters()
{
    DisplayContactInfo();
	echo "</body>\n";
	echo "</html>\n";
}

//create functions
function CreateConnectionObject()
{

    $fh = fopen("auth.txt","r");//read the authentications
    $Host =  trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh)); 
fclose($fh);

    $mysqlObj = new mysqli($Host, $UserName, $Password,$Database,$Port);//mysqli is anAPI

    if ($mysqlObj->connect_errno != 0) //not successful
    {
     echo "<p>Connection failed. Unable to open database $Database. Error: "
              . $mysqlObj->connect_error . "</p>";
     exit;
    }
    return ($mysqlObj);//return an objects, as it’s a big variable
}



?>