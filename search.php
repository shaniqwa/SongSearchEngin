 <?php
 
    $user = 'root';
    $password = 'root';
    $host = 'localhost';
    $port = 8889;
    
    $link = mysql_connect(
       "$host:$port", 
       $user, 
       $password
    );
     //testing connection success
     if(mysqli_connect_errno()) {
     die("DB connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")"
     );
     }
    $db_selected = mysql_select_db("SongSearchEngin", $link); 




        // GET PARAMS
        $search = $_GET["search"];
        // GET PARAMS

        // ESCAPE STRINGS
        $search = mysql_real_escape_string($search);
        // ESCAPE STRINGS


        // CLEAN STRING
        $splitted = preg_split('#\s+#', $search, null, PREG_SPLIT_NO_EMPTY); 
        // CLEAN STRING

        //OPERATOR OR
        if(preg_grep( "/OR/i" , $splitted )){
            // RUN ON EVERY KEYWORDe

                // CHECK IF KEYWORD EXISTS in DB
                $query = "SELECT * FROM words WHERE word = '$splitted[0]' OR word = '$splitted[2]' ";
                $result = mysql_query($query) or die('Query failedss: ' . mysql_error());

                // CHECK IF KEYWORD EXISTS in DB
                if(mysql_num_rows($result)>0) { //keyword exists
                    $query2 = "SELECT file FROM words WHERE word = '$splitted[0]' OR word = '$splitted[2]' ";// select documents of keyword
                    $result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
                    $values2 = mysql_fetch_array($result2); //convert results to array
                    $data   = preg_split('/\s+/',$values2[0]); // remove whitespaces
                    $d = array();
                    foreach ($data as $AA2) {

                            $query5 = 'SELECT * FROM files WHERE id  = '.$AA2;
                            $result5 = mysql_query($query5) or die('Query failedaa: ' . mysql_error());
                            $values5 = mysql_fetch_assoc($result5);
                            $d[] = $values5;
                    }      
                       
                   
                } else { // keyword dosent exist
                    $norlst = array("result" => "none");
                    header('Content-Type: application/json');
                    echo json_encode($norlst);
                    exit();
                }
                header('Content-Type: application/json');
                echo json_encode($d);
             
        }

        //NO OPERATORS
        else{
            // RUN ON EVERY KEYWORD
            foreach ($splitted as $AA) {
                $AA = preg_replace("/[^ \w]+/", "", $AA);
                
                // CHECK IF KEYWORD EXISTS in DB
                $query = 'SELECT * FROM words WHERE word="'.$AA.'"';
                $result = mysql_query($query) or die('Query failedss: ' . mysql_error());
                // CHECK IF KEYWORD EXISTS in DB
                if(mysql_num_rows($result)>0) { //keyword exists
                    $query2 = 'SELECT file FROM words WHERE word = "'.$AA.'"'; // select documents of keyword
                    $result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
                    $values2 = mysql_fetch_array($result2);
                    $data   = preg_split('/\s+/',$values2[0]); // split whitespaces
                    $d = array();
                    foreach ($data as $AA2) {

                            $query5 = 'SELECT * FROM files WHERE id  = '.$AA2;
                            $result5 = mysql_query($query5) or die('Query failedaa: ' . mysql_error());
                            $values5 = mysql_fetch_assoc($result5);
                            $d[] = $values5;
                    }      
                       
                   
                } else { // keyword dosent exist
                    $norlst = array("result" => "none");
                    header('Content-Type: application/json');
                    echo json_encode($norlst);
                    exit();
                }
                header('Content-Type: application/json');
                echo json_encode($d);
            }    
        }
?>