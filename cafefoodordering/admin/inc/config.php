<?php
    $hname = 'localhost';
    $uname = 'root';
    $pass = '';
    $db = 'cafefoodordering';

    $conn = mysqli_connect($hname, $uname, $pass,$db);

    if(!$conn){
        die("Can't Connect to Database".mysqli_connect_error());
    }

    function filteration($data){
        foreach($data as $key => $value){
            $data[$key] = trim($value);
            $data[$key] = stripslashes($value);
            $data[$key] = htmlspecialchars($value);
            $data[$key] = strip_tags($value);
        }
        return $data;
    }

    function select($sql, $values, $datatypes){
        $conn = $GLOBALS['conn'];
        if($stmt = mysqli_prepare($conn,$sql)){
            mysqli_stmt_bind_param($stmt,$datatypes,...$values);

            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_get_result($stmt); 
                mysqli_stmt_close($stmt);
                return $res;          
            }else{
                mysqli_stmt_close($stmt);
                die("Query can't be exercuted - Select");
            }
        }else{
            die("Query can't be exercuted - Select");
        }
    }

    function selectAll($table){
        $conn = $GLOBALS['conn'];
        $res = mysqli_query($conn, "SELECT * FROM $table");
        return $res;
    }


    function update($sql, $values, $datatypes){
        $conn = $GLOBALS['conn'];
        if($stmt = mysqli_prepare($conn,$sql)){
            mysqli_stmt_bind_param($stmt,$datatypes,...$values);

            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt); 
                mysqli_stmt_close($stmt);
                return $res;          
            }else{
                mysqli_stmt_close($stmt);
                die("Query can't be exercuted - Update");
            }
        }else{
            die("Query can't be exercuted - Update");
        }
    }

    function insert($sql, $values, $datatypes){
        $conn = $GLOBALS['con'];
        if($stmt = mysqli_prepare($conn,$sql)){
            mysqli_stmt_bind_param($stmt,$datatypes,...$values);

            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt); 
                mysqli_stmt_close($stmt);
                return $res;          
            }else{
                mysqli_stmt_close($stmt);
                die("Query can't be exercuted - Insert");
            }
        }else{
            die("Query can't be exercuted - Insert");
        }
    }

    function delete($sql, $values, $datatypes) {
        $conn = $GLOBALS['conn'];
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
    
            if (mysqli_stmt_execute($stmt)) {
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            } else {
                mysqli_stmt_close($stmt);
                die("Query can't be executed - Delete");
            }
        } else {
            die("Query can't be prepared - Delete");
        }
    }
?>