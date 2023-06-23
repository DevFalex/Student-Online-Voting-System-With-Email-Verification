<?php 
define('RESTRICTED', true); 
require_once '../session.php';
require_once '../auth.php';  
require '../process/config.php';
$config = new Config();
$conn = $config->getConnection();
$sql = "select * from tbl_election_date order by election_date desc";
$query = mysqli_query($conn, $sql);
$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
$yearOpt = '';
foreach($response as $row) {
    $yearOpt = $yearOpt.'<option value="'.$row['election_date_id'].'">'.(date('M d, Y',strtotime($row['election_date']))).'</option>';
}   
?>
<!Doctype html>
<html>
  <?php include '../shared/head.php'; ?>
  <?php include '../shared/alert.php'; ?>
<body>
  <?php include '../shared/navigation.php'; ?>
  <div class="container-fluid mt-2"> 
    <div class="row">
      <div class=" col-sm-12 col-md-8 col-lg-8 col-xl-8">
        <h1 class=" text-sm-center text-xs-center text-md-left text-lg-left text-xl-left">Students List</h1>
      </div>
      <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <div class='d-flex flex-row w-100 justify-content-end'>
            <input type="file" name="csv_name" id="csv_id" style='display:none' onChange="readCSV()">
            <button class="btn btn-warning text-white mr-1" onClick="csv_id.click()">Import CSV</button>
            <button class="btn btn-primary mr-1" id="add" data-toggle="modal" data-target="#addUpdate">Add Student</button>
            <a class="btn btn-success" href="print.php" target="_blank" >Print Voting Codes</a>
        </div>
        <script>
            function CSVReader() {
                return new Promise((res,rej) => {
                    let csv = [];
                    const file = csv_id.files[0];
                    const fr = new FileReader();
                    let jsonData = [];
                    fr.onload = function (e) {
                        let c = fr.result.split('\n');
                        csv = c.map(data => data.split(','));
                        const csvLen = csv.length;
                        for(let x = 1; x < csvLen-1; x++) {
                            let data = {};
                            for(let y = 0; y < csv[0].length; y++) {
                                data[((csv[0][y].trim()).replace(' ', ''))] = csv[x][y].trim();
                            }
                            jsonData.push(data);
                        }
                        csv_id.value = '';
                        res(jsonData);
                    } 
                    fr.readAsText(file); 
                });
                        
            }
            function readCSV() {
                let csv = CSVReader(); //promise
                csv.then(data => {
                    $.ajax({url:'../process/checkingCSV.php',
                    data:{csv:data},
                    method:'post',
                    success:(e) => {
                        bulkTbody.innerHTML = e;    
                        $('#bulkadd').modal('show');
                    }})
                });
            }
        </script>
      </div>
    </div> 
    <table class="table" id="student_tbl" style='width:100%'>
      <thead>
      <tr>
        <th colspan='8'>
            <div class='d-flex flex-row'>
                <div class='d-flex flex-row'>        
                <label for="" class='ml-1'>Election Date</label>
                <select class="form-control ml-1"  style='width:200px;' id="yearOpt" onChange="getStudents()">
                    <?php echo $yearOpt; ?>
                </select>

                </div>
            </div>
        </th>
      </tr>
        <tr>  
          <th style='width:50px'>ID No.</th>
          <th>Full Name</th> 
          <th style='width:300px'>Course</th>
          <th>Year Level</th>
          <th>Voting Code</th>
          <th>Vote Status</th>
          <th class="text-center" data-toggle="modal" data-target="#addUpdate">Update</th>
          <th class="text-center">Delete</th>
        </tr>
      </thead>
      <tbody id="student_table"> 
      </tbody>
    </table>
  </div> 
  

    <div class="modal fade" id="addUpdate">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
          
            <div class="modal-header">
              <h4 class="modal-title">Add/Update</h4>
              <button type="button" id="close" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="studentForm" action="../process/StudentRoutes.php" method="POST">
                    <div class="row">
                      <input type="hidden" class="form-control" name="id" id="id">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" name="image" id="image">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="studentID">Student ID</label>
                                <input class="form-control" name="studentID" id="studentID" placeholder="Input Student ID" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" name="lastName" class="form-control" id="lastName" placeholder="Input Last Name" required>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" name="firstName" class="form-control" id="firstName" placeholder="Input First Name" required>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="middleName">Middle Name</label>
                                <input type="text" name="middleName" class="form-control" id="middleName" placeholder="Input Middle Name">
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-xl-6" class="selectz"> 
                            <div class="form-group">
                                <label for="course">Course</label>
                                <select name="course" class="form-control" id="course" required> 

                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6" class="selectz"> 
                            <div class="form-group">
                                <label for="yearlevel">Year Level</label>
                                <select name="yearlevel" class="form-control" id="yearlevel"> 
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div> 
                </form>  
          </div>
        </div>
    </div>



    <div class="modal fade" id="bulkadd">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
          
            <div class="modal-header">
              <h4 class="modal-title">Students</h4>
              <button type="button" id="close" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="bulkaddform">
            <div class="modal-body">
                <table class='table'>
                    <thead>
                        <tr>
                            <th style='width:100px'>ID NO</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Course</th>
                            <th>Year Level</th>
                        </tr>
                    </thead>
                    <tbody id='bulkTbody'></tbody>
                </table>
            </div>
            </form>  
          </div>
        </div>
    </div>

    <?php include '../shared/foot.php'; ?>
</body>
<script type="text/javascript">
    $(document).ready(function(){
        getCourses();
        getYrLvl();
        
        $("#studentForm").on("submit", function(e){
            e.preventDefault(); 
            let theData = $("#studentForm");
            let formData = new FormData(theData[0]); 
            $.ajax({
                method : $("#studentForm").attr('method'),
                url : $("#studentForm").attr('action'),
                data : formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(e){
                    $(".form-control").val('');
                    $("#addUpdate").modal('hide');
                    const message = e.toLowerCase() == 'success' ? 'Successfully Added!' : 'Failed to add!';
                    alertService.alert({response:e,message:message});
                },
                error: function(e){

                },
                complete: function(e){
                    $("#student_tbl").DataTable().destroy();
                    getStudents();
                }
            });
        });
        $("#close").on('click', function(e){
            $(".form-control").val('');
        });
        getStudents();
    });
    function getCourses(){
        $.ajax({
            method: 'GET',
            url : '../process/CourseRoutes.php',
            data : {courses: 'g'},
            success : function(e){
                let response = JSON.parse(e);
                $("#course").empty();
                $("#course").append("<option value=''>Please Select</option>");
                $.each(response, function(index, value){
                    $("#course").append(
                            "<option class='courses' value='"+value['id']+"'>"+value['courseinitial']+"</option>"
                        );
                });
            }
        })
    }
    function getYrLvl(){
        $.ajax({
            method: 'GET',
            url : '../process/YearLevelRoutes.php',
            data: {yearlvl: 'g'},
            success: function(e){
                let response = JSON.parse(e);
                $("#yearlevel").empty();
                $("#yearlevel").append("<option value=''>Please Select</option>");
                $.each(response, function(index, val){
                    $("#yearlevel").append(
                        "<option value='"+val['id']+"'>"+val['yearlevelinitial'] + "</option>"
                    );
                })
            }
        })
    }
    function getStudents(){
        $.ajax({
            method: 'GET',
            url : '../process/StudentRoutes.php',
            data: {student:'g',election_date:yearOpt.value},
            success : function(e){
                student_table.innerHTML = '';
                let response = JSON.parse(e);
                let status="";
                let code=""; 
                $("#student_tbl").DataTable().destroy();
                student_table.innerHTML = '';
                $.each(response, function(index, value){
                    if(!value['votingcode']){
                        value['votingcode']=" ";
                    } 
                    $("#student_table").append(
                            "<tr>"+
                            "<td>"+value['idno']+"</td>"+
                            "<td>"+"<a href='#'>"+value['lastname']+", "+value['firstname']+" "+value['middlename']+"</a></td>"+
                            "<td>"+value['coursename']+"</td>"+
                            "<td>"+value['yearlevelname']+"</td>"+
                            "<td>"+value['votingcode']+"</td>"+
                            "<td>"+(value['vote_status_'] > 0 ? '<span style="color:green">Done Voting</span>' :  '<span style="color:orange">Not Yet Voted</span>' )+"</td>"+
                            "<td class='text-center'><button class='btn btn-success' onclick='getStudent("+value['id']+")'><i class='fa fa-edit'></i></button></td>"+
                            "<td class='text-center'><button class='btn btn-danger' onclick='deleteStudent("+value['id']+")'><i class='fa fa-trash'></i></button></td>"+
                            "</tr>"
                        );
                });
                $("#student_tbl").DataTable({
                    sort : false
                });  
            },
            error: function(e){

            },
            complete: function(e){
            }
        });
    }
    function getStudent(id){
        getCourses(); 
        $.ajax({
            method: 'GET',
            url : '../process/StudentRoutes.php',
            data: {id:id},
            success: function(e){ 
                $("#addUpdate").modal('show');
                let response = JSON.parse(e);
                $("#id").val(response['id']);
                $("#studentID").val(response['idno']);
                $("#lastName").val(response['lastname']);
                $("#firstName").val(response['firstname']);
                $("#middleName").val(response['middlename']);
                for(let i=0; i<$(".courses").length; i++){ 
                    if(response['courseid'] == $(".courses").eq(i).val()){
                        $(".courses").eq(i).attr("selected", true);
                    }
                }
            },
            error: function(e){

            },
            complete: function(e){
                $("#student_tbl").DataTable().destroy();
                getStudents();
            }
        });
    }
    function deleteStudent(id){
        deleteService.request({id:id,method:'post',url:'../process/StudentRoutes.php',isset:'deleteId',complete:(e)=>{ 
           const message = e.toLowerCase() == ' success' ? 'Successfully deleted!' : 'Failed to Delete!'; 
           alert(e);
           alertService.alert({response:e,message:message});
           if(e.toLowerCase().trim() == 'success'){
               deleteService.close();
               $("#student_tbl").DataTable().destroy();
                getStudents();
           }
        }});
    } 
    function resetVotingCode() {
        $.ajax({url:'../process/ '})
    }

    function addBulkSubmit() {
        if (confirm('Are you sure you want to submit it?')) {
            const data = new FormData($('#bulkaddform')[0]);
            $.ajax({
                method : "POST",
                url : "../process/bulk-add-student.php", 
                data: data,  
                processData:false,
                contentType:false,
                cache: false,
                success: (e) => {
                    alertService.alert({response:'success',message:'Successfully Added!'});
                    $('#bulkadd').modal('hide');
                    getStudents();
                }});
        }
    }
</script>
  <?php include '../shared/delete.php'; ?>
</html> 