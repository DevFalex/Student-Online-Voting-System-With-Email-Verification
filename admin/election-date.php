<?php 
define('RESTRICTED', true); 
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
      <div class=" col-sm-12 col-md-8 col-lg-8 col-xl-8">
        <h1 class=" text-sm-center text-xs-center text-md-left text-lg-left text-xl-left">Election Date List</h1>
      </div>
      <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
      </div>
      <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <button class="btn btn-primary btn-block"  onclick="electionIdForm.reset();" id="add" data-toggle="modal" data-target="#addUpdate">Set Election Date</button>
      </div>
    </div> 
    <table class="table" id="election_tbl">
      <thead>
        <tr>  
          <th>Election Date ID</th>
          <th>Election Date</th>
          <th class="text-center" data-toggle="modal" data-target="#addUpdate">Update</th>
          <th class="text-center">Delete</th>
        </tr>
      </thead>
      <tbody id="election_tblbody"> 
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
                <form id="electionIdForm" action="" method="POST">
                    <input type="hidden" name="election_id" id="election_id">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Election Id</label>
                                <input type="date" id="election_date" name="election_date" required class="form-control">
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
  <?php include '../shared/delete.php'; ?>
  <script>
        electionIdForm.onsubmit = (e) => {
            e.preventDefault();
            alert('After election date added student voting code will reset');
            $.ajax({
                url:'../process/election-date-process.php?'+(election_id.value ? 'update' : 'add'),
                method:"GET",
                data:{election_id:election_id.value, election_date:election_date.value},
                success:(ee)=>{
                    if(ee.trim() == 'success') {
                        alertService.alert({
                        response:'success',
                        message: (election_id.value ? 'Successfully Updated!' : 'Successfully Added!' )
                        });
                        $('#addUpdate').modal('hide');
                        displayElection();
                    } else {
                        alertService.alert({
                        response:'error',
                        message: `${election_date.value} Already Exist!`});
                    }
                }
            });
        }

        function displayElection() {
            $.ajax({
                url:'../process/election-date-process.php?get=true',
                method:"GET",
                data:{},
                success:(e)=>{
                    $("#election_tbl").DataTable().destroy();
                    datas = JSON.parse(e);
                    let tr = '';
                    election_tblbody.innerHTML = '';
                    datas.forEach(data => {
                        tr = tr+`<tr>
                                <td>${data.election_date_id}</td>
                                <td>${data.election_date}</td>
                                <td class='text-center'><button class='btn btn-success' onclick='getElection(${JSON.stringify(data)})'><i class='fa fa-edit'></i></button></td>
                                <td class='text-center'><button class='btn btn-danger' onclick='deleteElection(${data.election_date_id})'><i class='fa fa-trash'></i></button></td>
                            </tr>`;
                    }); 
                    election_tblbody.innerHTML = tr;
                    $("#election_tbl").DataTable({sort: false});
                }
            })
        }
        function getElection(election_object) {
            election_id.value = election_object.election_date_id;
            election_date.value = election_object.election_date;
            $('#addUpdate').modal('show');
        }

        function deleteElection(election_id) {
            if(!confirm('Are you sure you want to delete it?')) {
                return false;
            }
            $.ajax({
                url:'../process/election-date-process.php?delete',
                method:"GET",
                data:{election_id:election_id},
                success:(ee)=>{
                        if(ee.trim() == 'success') {
                            alertService.alert({
                            response:'success',
                            message: 'Successfully Deleted!'
                            });
                            $('#addUpdate').modal('hide');
                            displayElection();
                        } else {
                            alertService.alert({
                            response:'error',
                            message: 'Failed to Delete!'});
                        }
                    }
                });
        }
        displayElection();
  </script>
</html> 