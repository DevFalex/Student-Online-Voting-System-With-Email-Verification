<?php 
define('RESTRICTED', true); 
require_once '../session.php';
require_once '../auth.php';    
?>
<!Doctype html>
<html>
  <?php include '../shared/head.php'; ?>
<body>
  <?php include '../shared/navigation.php'; ?>
  <div class="container-fluid mt-2"> 
    <div class="row">
      <div class=" col-sm-12 col-md-9 col-lg-9 col-xl-9">
        <h1 class=" text-sm-center text-xs-center text-md-left text-lg-left text-xl-left">Candidates Position</h1>
      </div>
      <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#addUpdate">Add Position</button>
      </div>
    </div>
    <table class="table" id="pos_tbl">
      <thead>
        <tr> 
          <th>Position Name</th>
          <th>Sort Order</th>
          <th>Votes Allowed</th>
          <th>Allow Per Party</th>
          <th class="text-center" data-toggle="modal" data-target="#addUpdate">Update</th>
          <th class="text-center">Delete</th>
        </tr>
      </thead>
      <tbody id="position_table"> 
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
            <form action="" method="" id="positionForm">  
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id='id' name="positionId" class="form-control" />
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="positionName">Position Name</label>
                                <input class="form-control" name="positionName" id="positionName" placeholder="Input Position Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="sortOrder">Sort Order</label>
                                <input type="number" min=0 name="sortOrder" class="form-control" id="sortOrder" placeholder="sortOrder">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="voteAllowed">Vote(s) Allowed</label>
                                <input type="number" min=0 class="form-control" name="voteAllowed" id="voteAllowed" placeholder="Vote(s) Allowed">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="Allow Per Party">Allow Per Party</label>
                                <input class="form-control" min=0 type="number" name="allowPerParty" id="allowPerParty" placeholder="Allow Per Party">
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
    <?php include '../shared/alert.php'; ?>
    <?php include '../shared/delete.php'; ?>
</body>
<script type="text/javascript">
    $(document).ready(function(){
        $("#positionForm").on('submit', function(e){
            e.preventDefault();
            let data = $("#positionForm");
            let formData = new FormData(data[0]);
            $.ajax({
                method: "POST",
                url: "../process/PositionRoutes.php",
                data: formData,
                processData: false, 
                contentType: false,
                cache: false,
                success: function(e){
                    let data = JSON.parse(e);
                    alertService.alert({
                        response:data['status'],
                        message: data['message']
                    });
                    $(".form-control").val('');
                    $("#addUpdate").modal('hide');
                },
                error: function(e){

                },
                complete: function(e){
                  $("#pos_tbl").DataTable().destroy();
                  getPosition();
                }
            });
        });
        getPosition();
    });
    function getPosition(){
        $.ajax({
            method: 'GET',
            url: '../process/PositionRoutes.php',
            data:{pos:'g'},
            success: function(e){
                let response = JSON.parse(e);
                $("#position_table").empty();
                $.each(response, function(index, val){
                  let updateData = JSON.stringify({
                        id: val['id'],
                        position_name: val['positionname'],
                        sort_order:  val['sortorder'],
                        votes_allowed: val['votesallowed'],
                        allowed_per_party: val['allowperparty']  
                    });
                    $("#position_table").append(
                        "<tr>"+
                            "<td>"+val['positionname']+"</td>"+
                            "<td>"+val['sortorder']+"</td>"+
                            "<td>"+val['votesallowed']+"</td>"+
                            "<td>"+val['allowperparty']+"</td>"+
                            "<td class='text-center'><button class='btn btn-success' onclick='editPos("+updateData+")'><i class='fa fa-edit'></i></button></td>"+
                            "<td class='text-center'><button class='btn btn-danger' onclick='deletePos("+val['id']+")'><i class='fa fa-trash'></i></button></td>"+
                        "</tr>"
                    );
                });
                $("#pos_tbl").DataTable({
                    sort: false
                });
            }
        });   
    }
    function editPos(data){
        $("#addUpdate").modal('show');
        $("#id").val(data['id']);
        $("#positionName").val(data['position_name']);
        $("#sortOrder").val(data['sort_order']);
        $("#voteAllowed").val(data['votes_allowed']);
        $("#allowPerParty").val(data['allowed_per_party']);
    }
    function deletePos(id){
        deleteService.request({
            id:id,
            method:'POST',
            url:'../process/PositionRoutes.php',
            isset:'id',
            complete:(e)=>{  
                const message = e.toLowerCase() == 'success' ? 'Successfully deleted!' : 'Failed to Delete!';
                alertService.alert({
                    response:e,
                    message:message
                });
                if(e.toLowerCase() == 'success'){
                    deleteService.close();
                    $("#pos_tbl").DataTable().destroy();
                    getPosition();
                }
            }
        });
    }
</script>
</html> 