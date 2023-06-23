<?php 
require_once '../session.php';
require_once '../auth.php';    
?>
<!Doctype html>
<html>
  <?php include '../shared/head.php'; ?>
  <?php include '../shared/alert.php'; ?>
<body>
  <?php include '../shared/navigation.php'; ?>
  <div class="container-fluid mt-2"> 
    <div class="row">
      <div class=" col-sm-12 col-md-9 col-lg-9 col-xl-9">
        <h1 class=" text-sm-center text-xs-center text-md-left text-lg-left text-xl-left">Course</h1>
      </div>
      <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#addUpdate">Add Course</button>
      </div>
    </div>
    <table class="table" id="course_tbl">
      <thead>
        <tr> 
          <th>Course Initial</th>
          <th>Course Name</th>
          <th class="text-center" data-toggle="modal" data-target="#addUpdate">Update</th>
          <th class="text-center">Delete</th>
        </tr>
      </thead>
      <tbody id="course_body">
        
      </tbody>
    </table>
  </div>
  <div class="modal fade" id="addUpdate">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
          
            <div class="modal-header">
              <h4 class="modal-title">Add/Update</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form method="POST" action="../process/routes.php" id="courseForm">
                    <input type="hidden" name="courseId" id="courseId">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="courseInitial">Course Initial</label>
                                <input class="form-control" name="courseInitial" id="courseInitial" placeholder="Input Course Initial">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="courseName">Course Name</label>
                                <input type="text" name="courseName" id="courseName" class="form-control" placeholder="Input Course Name">
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
<?php include '../shared/foot.php'; ?>
</body>
<script>
    $(document).ready(function(){
        $("#courseForm").submit(function(e){
            e.preventDefault();   
            let theData = $("#courseForm");
            let formData = new FormData(theData[0]);
            $.ajax({
                method: 'POST',
                url: '../process/CourseRoutes.php',
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function(e){
                    const message = e.toLowerCase() == 'success' ? 'Successfully Added!' : 'Failed to Delete!';
                    alertService.alert({response:e,message:message});
                    $(".form-control").val("");
                    $("#addUpdate").modal('hide');
                },
                error: function(e){
                    alert(e);
                },
                complete: function(e){
                     $("#course_tbl").DataTable().destroy();
                    getCourses();
                }
            }); 
        });
        getCourses();
    });
    function getCourses(){
        $.ajax({
            method: 'GET',
            url: '../process/CourseRoutes.php',
            data: {courses:"g"},
            success: function(e){
                let response = JSON.parse(e); 
                $("#course_body").empty();
                $.each(response, function(index, value){
                    $("#course_body").append(
                        "<tr>"+
                        "<td>"+value['courseinitial']+"</td>"+
                        "<td>"+value['coursename']+"</td>"+
                        "<td class='text-center'><button class='btn btn-success' onclick='getCourse("+value['id']+")'><i class='fa fa-edit'></i></button></td>"+
                         "<td class='text-center'><button class='btn btn-danger' onclick='deleteCourse("+value['id']+")'><i class='fa fa-trash'></i></button></td>"+
                        "</tr>");
                });
                $("#course_tbl").DataTable();
            }
        });
    }
    function getCourse(id){
        $.ajax({
            method: 'GET',
            url: '../process/CourseRoutes.php',
            data: {courseId:id},
            success: function(e){ 
                let response = JSON.parse(e);
                $("#courseId").val(response['id']);
                $("#courseInitial").val(response['courseinitial']);
                $("#courseName").val(response['coursename']);
            },
            error: function(e){

            },
            complete: function(e){
                $("#addUpdate").modal('show');
            }
        })
    }
    function deleteCourse(id){
        deleteService.request({id:id,method:'POST',url:'../process/CourseRoutes.php',isset:'deleteCourseId',complete:(e)=>{ 
           const message = e.toLowerCase() == ' success' ? 'Successfully deleted!' : 'Failed to Delete!'; 
           alertService.alert({response:e,message:message});
           if(e.toLowerCase().trim() == 'success'){
               deleteService.close();
               $("#course_tbl").DataTable().destroy();
                getCourses();
           }
        }});    
    }
</script>
  <?php include '../shared/delete.php'; ?>
</html>