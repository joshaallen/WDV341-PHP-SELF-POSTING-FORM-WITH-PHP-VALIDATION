<?php
//Setting up data model
$eventName= "" ;
$eventDescription= "";
$eventPresenter= "";
$eventDate="";
$eventTime="";
//Form Flag
$validForm = false;
//End of Response Object Flag
$endOfResposeObject = false;
//Error Messages
$errorEventNameMsg= "";
$errorEventDescriptionMsg= "";
$errorEventPresenterMsg= "";
$errorEventDateMsg= "";
$errorEventTimeMsg= "";
//Selection block to determine if form has been submitted
if(isset($_POST["submit"]))
	{	
          //selection structure to determine if honey pot name value pair was submitted
        if(!isset($_POST["eventInfo"]) || $_POST["eventInfo"] == false) {
            //The form has been submitted and needs to be processed
        //Get the name value pairs from the $_POST variable into PHP variables
        $eventName = trim($_POST["eventName"]);
        $eventDescription = trim($_POST["eventDescription"]);
        $eventPresenter= trim($_POST["eventPresenter"]);
        $eventDate= $_POST["eventDate"];
        $eventTime= $_POST["eventTime"];
        //Form Flag
        $validForm= true;
        //Validate the form data here!
        if(empty($eventName)) {
            $validForm= false;
            $errorEventNameMsg= "Please supply an event name";
        }
        if(empty($eventDescription)) {
            $validForm= false;
            $errorEventDescriptionMsg= "Please supply an event description";
        }
        if(empty($eventPresenter)) {
            $validForm= false;
            $errorEventPresenterMsg= "Please supply an event presenter";
        }
        if(empty($eventDate)) {
            $validForm= false;
            $errorEventDateMsg= "Please supply an event date";
        }
        if(empty($eventTime)) {
            $validForm= false;
            $errorEventTimeMsg= "Please supply an event time";
        }
        else {
                //concataniting seconds onto eventTime value in prep for inserting into database
                $eventTime .= ":00";
           }
            //Determine if form has error messages 
           if($validForm) {
                try {
                /*is identical to require except PHP will check if the file has already been included, and if so , not require it again
               */
                require_once('connection.php');
                require_once('event.php');
                //instaniating new Event object to hold user data
                $eventObject = new Event();
                $eventObject->set_eventName("$eventName");
                $eventObject->set_eventPresenter("$eventPresenter");
                $eventObject->set_eventDate("$eventDate");
                $eventObject->set_eventTime("$eventTime");
                $eventObject->set_eventDescription("$eventDescription");
                //entering sanitzed values into php vairables 
                $name = $eventObject->get_eventName();
                $time = $eventObject->get_eventTime();
                $presenter = $eventObject->get_eventPresenter();
                $date = $eventObject->get_eventDate();
                $description = $eventObject->get_eventDescription();
                //PHP object assigned to an instance of the connection class
                $connection = new Connection();
                 //open connection 
                $conn = $connection->open();
                //SQL query that "
                $sql = "INSERT INTO wdv341_events (";
                $sql .= "name, ";
                $sql .= "description, ";
                $sql .= "presenter, ";
                $sql .= "date, ";
                $sql .= "time "; //Last column does NOT have a comma after it.
                $sql .= ") VALUES (:name, :description, :presenter, :date, :time)";
                //PREPARE the SQL statement
                $stmt = $conn->prepare($sql);
                //Bind the statement
                $stmt->bindParam(':name',$name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':presenter', $presenter);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':time', $time);
                //EXECUTE the prepared statement
                $stmt->execute();
                //capturing the number of row that were inserted
                $count = $stmt->rowCount();
                //capture id of last row inserted
                $lastInsertID = $conn->lastInsertId();
                 //close connection
                $conn = $connection->close();  
               
                 
               }
               catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
               
        }
        }
        //end of if truth block
        // Entering block for honeypot field submission
        else {
        /**storing ip address of visitor. will need away to transport the data with ip adddress
        **/
                $ip = getenv("REMOTE_ADDR");
                header("Location: form-handler-homework-honeyPot.php?ip=" . $ip);
        }
        
    }
?><!--end of IF SUBMIT BLOCK -->

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Self-Posting Form Validation</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *,:after,:before{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box}body{font:normal 15px/25px 'Open Sans',Arial,Helvetica,sans-serif;color:#444;text-align:left}h1,h2,h3{font-weight:400}h1{font:normal 40px/120px 'Open Sans',Arial,Helvetica,sans-serif;text-align:center;color:#444;margin:0}h1 span{color:#484c9b}h2{font-size:25px;line-height:30px;color:#484c9b;margin:50px 0 10px}h3{font-size:18px;line-height:35px;margin:50px 0 0}a{color:#484c9b;text-decoration:none}a:focus,a:hover{text-decoration:underline}p{margin:0 0 2rem}p span{color:#aaa}header{width:98%;margin:40px auto 0;border-bottom:1px solid #ddd;padding-bottom:40px;text-align:center}header p{margin:0}section{width:95%;max-width:910px;margin:40px auto}pre{background:#f9f9f9;padding:10px;font-size:12px;border:1px solid #eee;white-space:pre-wrap;border-radius:10px}table{border:1px solid #eee;background:#f9f9f9;width:100%;border-collapse:collapse;border-spacing:0;margin-bottom:3rem}thead{background:#5965af;color:#fff}tbody tr td,thead td{padding:.5rem .75rem}tbody tr:nth-child(even){background:#efefef}tbody tr td:first-child{padding-left:1.25rem}tbody tr td:first-child,tbody tr td:nth-child(3),thead td:first-child,thead td:nth-child(3){width:15%}tbody tr td:nth-child(2),thead td:nth-child(2){width:20%}tbody tr td:last-child,thead td:last-child{width:50%}@media only screen and (min-width:768px){body{font-size:20px;line-height:30px}h2{font-size:30px;line-height:45px}h3{font-size:22px;line-height:45px;margin-top:50px}p{margin-bottom:2rem}h1{font-size:60px}pre{padding:20px;font-size:15px}}
        #form-content {padding-bottom:5rem;display:flex;flex-wrap:wrap;justify-content:space-between;max-width:50rem;margin:0 auto;}
        #form-content div:first-child {width:100%;}
        .error {display:block;font-size:0.7rem;color:#cc0000;}
        #form-1 p:nth-child(1){
            display: none;
        }
        #error {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <header>
        <h1>WDV341 Intro <span>PHP</span></h1>
        <p>Unit-11 Self Posting Form With Validation</p>
    </header>
    <section>
        <?php /**
         * testing if form is valid so submission with data errors won't step into this branch
         * 
         * testing $count to ensure that the insert query was successful 
         */
            if($validForm) {
                if($count>0) {
                    $endOfResposeObject = true;

            ?>
            <!-- 
                This HTML block will only be visible with a valid form and a successful insert query
            -->
            <div>
                <h2>Submission Successful!</h2>
                <p>Thank you for submitting your info.</p>
                <p>The id of the last record you inserted is: <?php echo $lastInsertID; ?></p>
            </div>
        <?php
            }
                else {
                    $endOfResposeObject = true;
        ?>
        <!-- 
                This HTML block will only be visible with a valid form and an unsuccessful insert query
            -->
            <div>
                <h2>Uh Oh!</h2>
                <p>There was a problem.</p>
                <p>Please click <a href="self-post-validation.php">Here!!!</a> to attempt form again</p>
            </div>
        <?php
                }
            }
            if(!$endOfResposeObject) {

            
        ?>
            <div id="form-content">
                <div>
                    <h3><strong>Events Form</strong></h3>
                    <p>Insert Event</p>
                </div>
                <form name="form1" id="form-1" method="post" action="self-post-validation.php">
                    <!--Honey Pot-->
                    <p>
                        <label for="eventInfo">Event Info: </label>
                        <input type="text" name="eventInfo" id="eventInfo" size=25>
                    </p>
                    <p>
                        <label for="eventName">Event Name: </label>
                        <input type="text" name="eventName" id="eventName" value=<?php echo htmlentities($eventName); ?>>
                        <span id="error"><?php echo $errorEventNameMsg;?></span>
                    </p>
                    <p>
                        <label for="eventDescription">Event Description: </label>
                        <input type="text" name="eventDescription" id="eventDescription" value=<?php echo htmlentities($eventDescription); ?>> 
                        <span id="error"><?php echo $errorEventDescriptionMsg;?></span>
                        
                    </p>
                    <p>
                        <label for="eventPresenter">Event Presenter: </label>
                        <input type="text" name="eventPresenter" id="eventPresenter" value=<?php echo htmlentities($eventPresenter); ?>>
                        <span id="error"><?php echo $errorEventPresenterMsg;?></span>
                    </p>
                    <p>
                        <label for="eventDate">Event Date</label>
                        <input type="date" name="eventDate" id="eventDate"><?php echo $eventDate; ?>
                        <span id="error"><?php echo $errorEventDateMsg;?></span>
                        
                    </p>
                    <p>
                        <label for="eventTime">Event Time</label>
                        <input type="time" name="eventTime" id="eventTime"><?php echo $eventTime;?>
                        <span id="error"><?php echo $errorEventTimeMsg;?></span>
                        
                    </p>
                    <p>
                        <input type="submit" name="submit" id="submit" value="submit">
                        <input type="reset" name="Reset" id="reset" value="reset">
                    </p>
                </form>
            </div>
            <?php 
            }
            ?>
    </section>
    <!--
        This is a honeypot javascript redudancy for the CSS embedded style rule
     -->
    <script>
        document.querySelector("#form-1 p:nth-child(1)").style.display = "none";
    </scirpt>
</body>
</html>


