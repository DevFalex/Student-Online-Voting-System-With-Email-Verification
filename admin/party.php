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
<body>
  <?php include '../shared/navigation.php'; ?>
  <div class="container-fluid mt-2"> 
    <div class="row">
      <div class=" col-sm-12 col-md-9 col-lg-9 col-xl-9">
        <h1 class=" text-sm-center text-xs-center text-md-left text-lg-left text-xl-left">Available Parties</h1>
      </div>
      <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#addUpdate">Add Party</button>
      </div>
    </div>
    <table class="table" id="party_tbl">
      <thead>
        <tr> 
          <th>Party Initial</th>
          <th>Party Name</th>
          <th>Election Date</th>
          <th class="text-center" data-toggle="modal" data-target="#addUpdate">Update</th>
          <th class="text-center">Delete</th>
        </tr>
      </thead>
      <tbody id="party_table">
         
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
                    <form action="../process/PartyRoutes.php" method="POST" id="partyForm">
                        <input type="hidden" class="form-control" id="id" name="partyId" />
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xl-6">
                                <div class="form-group">
                                    <label for="partyInitial">Election Date</label>
                                    <select class="form-control" name="election_date" id="election_date" required>
                                        <?php echo $yearOpt; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xl-6">
                                <div class="form-group">
                                    <label for="partyInitial">Party Initial</label>
                                    <input class="form-control" name="partyInitial" id="partyInitial" placeholder="Input Party Initial" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xl-6">
                                <div class="form-group">
                                    <label for="partyName">Party Name</label>
                                    <input type="text" name="partyName" class="form-control" id="partyName" placeholder="Input Party Name" required>
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
        getParties();
        $("#partyForm").on('submit', function(e){
            e.preventDefault();
            let data = $("#partyForm");
            let formData = new FormData(data[0]);  
            $.ajax({
                method : "POST",
                url : "../process/PartyRoutes.php", 
                data: formData,  
                processData:false,
                contentType:false,
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
                    $("#party_tbl").DataTable().destroy();
                    getParties();
                }
            });
        });
        $("#close").on('click', function(e){
            $(".form-control").val('');
        }); 
    });
    function getParties(){
        $.ajax({
            method: 'GET',
            url: '../process/PartyRoutes.php',
            data: {getParties:'g'},
            success: function(e){ 
                let response = JSON.parse(e);
                $("#party_table").empty();
                $.each(response, function(index, val){
                    let updateData = JSON.stringify({
                        id: val['id'],
                        party_initial: val['partyinitial'],
                        party_name:  val['partyname'],
                        election_id: val['party_election_date_id'],  
                    });
                    $("#party_table").append(
                        "<tr>"+
                            "<td>"+val['partyinitial']+"</td>"+
                            "<td>"+val['partyname']+"</td>"+
                            "<td>"+val['election_date']+"</td>"+
                            "<td class='text-center'><button class='btn btn-success' onclick='getParty("+updateData+")'><i class='fa fa-edit'></i></button></td>"+
                            "<td class='text-center'><button class='btn btn-danger' onclick='deleteParty("+val['id']+")'><i class='fa fa-trash'></i></button></td>"+
                        "</tr>"
                    );
                });
                $("#party_tbl").DataTable();
            },
            error: function(e){

            },
            complete: function(e){

            }
        });
    }
    function getParty(data){
        $("#addUpdate").modal('show');
        $("#id").val(data['id']);
        $("#partyInitial").val(data['party_initial']);
        $("#partyName").val(data['party_name']);
        const element  = election_date.getElementsByTagName('option');
        for (let el of element) {
            if(el.getAttribute('value') == data['election_id']) {
                el.selected = true;
            } 
        } 
    }
    function deleteParty(id){
        deleteService.request({
            id:id,
            method:'POST',
            url:'../process/PartyRoutes.php',
            isset:'id',
            complete:(e)=>{  
                const message = e.toLowerCase() == 'success' ? 'Successfully deleted!' : 'Failed to Delete!';
                alertService.alert({
                    response:e,
                    message:message
                });
                if(e.toLowerCase() == 'success'){
                    deleteService.close();
                    $("#party_tbl").DataTable().destroy();
                    getParties();
                }
            }
        });
    }
</script>
</html>
 