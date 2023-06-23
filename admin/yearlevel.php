<?php 
define('RESTRICTED', true); 
require_once '../session.php';
require_once '../auth.php';    
?>
<!Doctype html>
<html>
  <?php include '../shared/head.php'; ?>
  <?php include '../shared /alert.php'; ?>
<body>
  <?php include '../shared/navigation.php'; ?>
  <div class="container-fluid mt-2"> 
    <div class="row">
      <div class=" col-sm-12 col-md-9 col-lg-9 col-xl-9">
        <h1 class=" text-sm-center text-xs-center text-md-left text-lg-left text-xl-left">Year Level</h1>
      </div>
      <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#addUpdate">Add Year Level</button>
      </div>
    </div>
    <table class="table" id="yrlvl_tbl">
      <thead>
        <tr> 
          <th>Year Level Initial</th>
          <th>Year Level Name</th>
          <th>Population</th>
          <th class="text-center">Update</th>
          <th class="text-center">Delete</th>
        </tr>
      </thead>
      <tbody id="yrlvl_body">
        
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
                <form method="POST" action="../process/YearLevelRoutes.php" id="yearlevelForm">
                    <input type="hidden" class='form-control' name="yearLevelId" id="yearLevelId">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="yearLevelInitial">Year Level Initial</label>
                                <input class="form-control" name="yearLevelInitial" id="yearLevelInitial" placeholder="Input Year Level Initial">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="yearLevelName">Year Level Name</label>
                                <input type="text" name="yearLevelName" class="form-control" id="yearLevelName" placeholder="Input Year Level Name">
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
        $("#yearlevelForm").on('submit', function(e){
            e.preventDefault();
            let data = $("#yearlevelForm");
            let formData = new FormData(data[0]);
            $.ajax({
                method: 'POST',
                url: '../process/YearLevelRoutes.php',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(e){
                    const message = e.toLowerCase() == 'success' ? 'Successfully Added!' : 'Failed to Delete!';
                    alertService.alert({response:e,message:message});
                    $(".form-control").val('');
                    $("#addUpdate").modal('hide');
                },
                error: function(e){

                },
                complete: function(e){
                    $("#yrlvl_tbl").DataTable().destroy();
                    getYearLevels();
                }
            });
        });
        getYearLevels();
    });
    function getYearLevels(){
        $.ajax({
            method: 'GET',
            url: '../process/YearLevelRoutes.php',
            data: {yearlvl: 'g'},
            success: function(e){
                let response = JSON.parse(e);
                $("#yrlvl_body").empty();
                $.each(response, function(index, val){
                    $("#yrlvl_body").append(
                        "<tr>"+
                            "<td>"+val['yearlevelinitial']+"</td>"+
                            "<td>"+val['yearlevelname']+"</td>"+
                            "<td>"+val['cnt']+"</td>"+
                            "<td class='text-center'><button class='btn btn-success' onclick='editYearLevel("+val['id']+")'><i class='fa fa-edit'></i></button></td>"+
                            "<td class='text-center'><button class='btn btn-danger' onclick='deleteYearLevel("+val['id']+")'><i class='fa fa-trash'></i></button></td>"+
                        "</tr>"
                    );
                });
            },
            error: function(e){

            },
            complete: function(e){
                $("#yrlvl_tbl").DataTable();
            }
        });
    }
    function editYearLevel(id){
        $.ajax({
            method: 'GET',
            url: '../process/YearLevelRoutes.php',
            data: {yearLvlId: id},
            success: function(e){
                let data = JSON.parse(e);
                $("#addUpdate").modal('show');
                $("#yearLevelId").val(data['yearlevelId']);
                $("#yearLevelInitial").val(data['yearLevelInitial']);
                $("#yearLevelName").val(data['yearLevelName']);
            }
        });
    }
    function deleteYearLevel(id){
        if(confirm("Are you sure you want to delete this year level?")){
            $.ajax({
                method: 'GET',
                url: '../process/YearLevelRoutes.php',
                data: {delYrLvl: id},
                success: function(e){
                    const message = e.toLowerCase() == 'success' ? 'Successfully Deleted!' : 'Failed to Delete!';
                    alertService.alert({response:e,message:message});
                },
                error: function(e){

                },
                complete: function(e){
                    $("#yrlvl_tbl").DataTable().destroy();
                    getYearLevels();
                }
            });
        }
    }
</script>
</html> 