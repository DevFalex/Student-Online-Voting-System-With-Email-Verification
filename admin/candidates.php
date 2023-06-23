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
        <h1 class=" text-sm-center text-xs-center text-md-left text-lg-left text-xl-left">Candidates</h1>
      </div>
      <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#addUpdate">Add Candidate</button>
      </div>
    </div>
    <table class="table" id="can_tbl">
      <thead>
        <tr>
            <th>Image</th>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Party</th>
            <th>Cadidate Position</th>
            <th>Election Date</th>
            <th class="text-center" data-toggle="modal" data-target="#addUpdate">Update</th>
            <th class="text-center">Delete</th>
        </tr>
      </thead>
      <tbody id="candidate_table">
         
           
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
            <form action="" method="" id="candidatesForm">
                <input type="hidden" name="canId" id="id" />
              <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="election_date">Election Date</label>
                                    <select class="form-control" name="election_date" id="election_date" required onchange="getStudents(); getParties_();">
                                        <option value=''>Select Election Date</option>
                                        <?php echo $yearOpt; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xl-6">
                                <div class="form-group">
                                    <label for="student">Student</label>
                                    <select class="form-control" name="student" id="student" required> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xl-6">
                                <div class="form-group">
                                    <label for="party">Party</label>
                                    <select class="form-control" name="party" id="party" required> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="pos">Candidate Position</label>
                                    <select class="form-control" name="pos" id="pos" required> 
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
    <?php include '../shared/foot.php'; ?>
    <?php include '../shared/alert.php'; ?>
    <?php include '../shared/delete.php'; ?>
</body>
<script type="text/javascript">
    var glob=0;
$(document).ready(function(e){
    getStudents(true);
    getParties();
    getPosition();
    $("#candidatesForm").on('submit', function(e){
        e.preventDefault();
        $("#student").prop("disabled", false);
        let data = $("#candidatesForm");
        let formData = new FormData(data[0]); 
        $.ajax({
            method: 'POST',
            url: '../process/CandidateRoutes.php',
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
                getStudents(true);
                getParties();
                getPosition();
                $("#can_tbl").DataTable().destroy();
                getCandidates();
                $("#addUpdate").modal('hide'); 
            }
        });
    }); 
    getCandidates();
    $("#student").select2({width:'100%'});
});
function getCandidates(){
    $.ajax({
        method: 'GET',
        url: '../process/CandidateRoutes.php',
        data: {candidate:'g'},
        success: function(e){ 
            let response = JSON.parse(e);  
            $("#candidate_table").empty();
            let count=0;
            $.each(response, function(index, val){
                let updateData = JSON.stringify({
                    id: val['id'],
                    student_id: val['s_id'],
                    party_id:  val['p_id'] ,
                    pos_id: val['c_id'],
                    election_date_id: val['election_date_id']
                });
                let im=''; 
                if(val['image'] != ""){
                    im = '../imgs/'+val['image']; 
                } else {
                    im = '../person.png';
                }
                count++;
                $("#candidate_table").append(
                    "<tr>"+
                    "<td ><img class='profPic' src='"+im+"' width='50' height='50'></td>"+
                    "<td>"+val['idno']+"</td>"+
                    "<td>"+val['lastname']+", "+val['firstname']+" "+val['middlename']+"</td>"+
                    "<td>"+val['partyname']+"</td>"+
                    "<td>"+val['positionname']+"</td>"+
                    "<td>"+val['election_date']+"</td>"+
                    "<td class='text-center'><button class='btn btn-success' onclick='editCandidate("+updateData+")'><i class='fa fa-edit'></i></button></td>"+
                    "<td class='text-center'><button class='btn btn-danger' onclick='deleteCandidate("+val['id']+")'><i class='fa fa-trash'></i></button></td>"+
                    "</tr>"
                );
            $("#profilePic").attr('src', );
            }); 
        },
        error: function(e){

        },
        complete: function(e){
            $("#can_tbl").DataTable({
                sort : false
            });
        }
    });
}
function editCandidate(id){
    $("#addUpdate").modal('show');
    getStudents(false); 
    $("#id").val(id.id);
    window.setTimeout(function(){
        $("#student").prop("disabled", true); 
        $("#student").val(String(id.student_id)).trigger('change');
        $("#party").val(String(id.party_id)).trigger('change');
        $("#pos").val(String(id.pos_id)).trigger('change');
        const element  = election_date.getElementsByTagName('option');
        for (let el of element) {
            if(el.getAttribute('value') == id['election_date_id']) {
                el.selected = true;
            } 
        } 
    }, 50); 
}
function deleteCandidate(id){
    deleteService.request({
        id:id,
        method:'POST',
        url:'../process/CandidateRoutes.php',
        isset:'id',
        complete:(e)=>{  
            const message = e.toLowerCase() == 'success' ? 'Successfully deleted!' : 'Failed to Delete!';
            alertService.alert({
                response:e,
                message:message
            });
            if(e.toLowerCase() == 'success'){
                deleteService.close();
                getStudents(true);
                getParties();
                getPosition();
                $("#can_tbl").DataTable().destroy();
                getCandidates(); 
            }
        }
    });
}
function checkCandidate(){ 
    var exist = "";
    $.ajax({
        method:'GET',
        url:'../process/CandidateRoutes.php', 
        data:{checkCandidate:'check', election_date_id: election_date.value},
        async: false,
        success: function(e){
            exist = e;
            let response = JSON.parse(e); 
                $("#student").empty();
                $("#student").append("<option value=''>Select Student</option>");
                $.each(response, function(index, val){
                    //let check = checkCandidate(val['id']);
                        $("#student").append(
                            "<option value='"+val['id']+"'>"+val['lastname']+", "+val['firstname']+" "+val['middlename']+"</option>"
                        ); 
                });
        }
    });
}
function getStudents(trigger){
    checkCandidate();
    /* if(trigger){
        alert('asd');
        $.ajax({
            method: 'GET',
            url: '../process/StudentRoutes.php',
            data: {student_for_candidate:'g'},
            success: function(e){
                let response = JSON.parse(e); 
                $("#student").empty();
                $("#student").append("<option value=''>Select Student</option>");
                $.each(response, function(index, val){
                    //let check = checkCandidate(val['id']);
                    if(check < 1){
                        $("#student").append(
                            "<option value='"+val['id']+"'>"+val['lastname']+", "+val['firstname']+" "+val['middlename']+"</option>"
                        ); 
                    }
                });
            }
        });
    } else { 
        $.ajax({
            method: 'GET',
            url: '../process/StudentRoutes.php',
            data: {student:'g'},
            success: function(e){
                let response = JSON.parse(e); 
                $("#student").empty();
                $("#student").append("<option value=''>Select Student</option>");
                $.each(response, function(index, val){ 
                    $("#student").append(
                        "<option value='"+val['id']+"'>"+val['lastname']+", "+val['firstname']+" "+val['middlename']+"</option>"
                    );  
                });
            }
        });
    }
}*/
}
function getParties(){
    $.ajax({
        method: 'GET',
        url: '../process/PartyRoutes.php',
        data: {getParties:'g'},
        success: function(e){ 
            let response = JSON.parse(e);
            $("#party").empty();
            $("#party").append('<option value="">Select Party</option>');
            $.each(response, function(index, val){
                $("#party").append(
                    "<option value='"+val['id']+"'>"+val['partyname']+"</option>"
                );
            });
            $("#party").select2({
                width:'100%', 
            });
        },
        error: function(e){

        },
        complete: function(e){

        }
    });
} 
function getPosition(){
    $.ajax({
        method: 'GET',
        url: '../process/PositionRoutes.php',
        data:{pos:'g'},
        success: function(e){
            let response = JSON.parse(e);
            $("#pos").empty();
            $("#pos").append("<option value=''>Select Position</option>");
            $.each(response, function(index, val){
                $("#pos").append(
                    "<option value='"+val['id']+"'>"+val['positionname']+"</option>"
                );
            });
            $("#pos").select2({width:'100%'});
        }
    }); 
}  

 function getParties_(){
        $.ajax({
            method: 'GET',
            url: '../process/PartyRoutes.php',
            data: {election_date_id: election_date.value ? election_date.value : 0},
            success: function(e){ 
                let response = JSON.parse(e);
                $("#party").empty();
                $("#party").append('<option value="">Select Party</option>');
                $.each(response, function(index, val){
                    $("#party").append(
                        "<option value='"+val['id']+"'>"+val['partyname']+"</option>"
                    );
                });
                $("#party").select2({
                    width:'100%', 
                });
            },
            error: function(e){

            },
            complete: function(e){

            }
        });
}
this.getParties_();
</script>
</html>
