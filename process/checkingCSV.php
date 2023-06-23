<?php
    require 'config.php';
    $config = new Config();
    $conn = $config->getConnection();
    //$course = mysqli_real_escape_string($_POST['Course'], $conn);
    //$year = mysqli_real_escape_string($_POST['Year Level'], $conn);
    $csv = $_POST['csv'];
    $row = 0;
    $tr = '';
    $formControls ='';
    $hasError = false;
    $headerCount = 0;
    foreach($csv as $c) {
        $headerCount = count($c);
        $sql = "select * from tblstudent where idno = '".$c['IDNo']."'";
		$query = mysqli_query($conn, $sql);
        $studRes = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $sql = "select * from tblcourse where courseinitial = '".$c['Course']."'";
		$query = mysqli_query($conn, $sql);
		$courseRes = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $sql = "select * from tblyearlevel where yearlevelname = '".$c['YearLevel']."'";
		$query = mysqli_query($conn, $sql);
        $yearRes = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $year= count($yearRes) ? $yearRes[0]['id'] : '';
        $tdRes = count($yearRes) ? $c['YearLevel'] : '<span style="color:red">('.$c['YearLevel'].') Not found in database!</span>';
        $course= count($courseRes) ? $courseRes[0]['id'] : '';
        $tdCourse =  count($courseRes) ? $c['Course'] : '<span style="color:red">('.$c['Course'].') Not found in database!</span>';
        $IDNo = count($studRes) ? $c['IDNo'] : '';
        $tdIDNo = count($studRes) < 1  ? $c['IDNo'] : '<span style="color:red">('.$c['IDNo'].') Already Existed!</span>';
    
        $prefix = md5($IDNo.date('Y-m-d h:i:s a'));
        $uni = uniqid(substr($prefix, 4, 6),true);
        $suffix = str_shuffle('abcdefghijklmnopqrstuvwxyz');
        $votingcode = substr($suffix , 0 ,3).'-'. substr(str_shuffle(strrev(str_replace(".","",$uni))),0 ,6);
        
        $tr =   $tr.'<tr>
                        <td>'.ucwords($tdIDNo).'</td>
                        <td>'.ucwords($c['LastName']).'</td>
                        <td>'.ucwords($c['FirstName']).'</td>
                        <td>'.ucwords($c['MiddleName']).'</td>
                        <td>'.ucwords($tdCourse).'</td>
                        <td>'.ucwords($tdRes).'</td>
                    </tr>';
        if(count($courseRes) < 1 || count($yearRes) < 1 || count($studRes) > 0){
            $hasError = true;
        }

        $formControls = $formControls.'
                                        <input type="hidden" name="IDNo[]" value="'.ucwords($c['IDNo']).'">
                                        <input type="hidden" name="LastName[]" value="'.ucwords($c['LastName']).'">
                                        <input type="hidden" name="FirstName[]" value="'.ucwords($c['FirstName']).'">
                                        <input type="hidden" name="MiddleName[]" value="'.ucwords($c['MiddleName']).'">
                                        <input type="hidden" name="Course[]" value="'.($course).'">
                                        <input type="hidden" name="voting_code_[]" value="'.strtoupper($votingcode).'">
                                        <input type="hidden" name="YearLevel[]" value="'.($year).'">
                                    ';
    }
    echo $tr;
    echo $formControls;
    echo '<tr class="text-right" style="background-color:transparent !important">
             <td colspan="'.$headerCount.'">
             '. ($hasError == 1 ? '<button type="submit" disabled class="btn btn-default">Submit</button>' : '<button type="button" onclick="addBulkSubmit()    " class="btn btn-primary">Submit</button>'
             ).'</td>
            </tr>';
?>