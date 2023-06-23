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
        <h1 class=" text-sm-center text-xs-center text-md-left text-lg-left text-xl-left">Admin</h1>
      </div>
      <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
        <button class="btn btn-primary btn-block" onclick="openModal(null,null)">Add Admin</button>
      </div>
    </div>
    <table class="table" id="table">
      <thead>
        <tr> 
          <th>Username</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Middle Name</td>
          <th class="text-center" data-toggle="modal" data-target="#addUpdate">Update</th>
          <th class="text-center">Delete</th>
        </tr>
      </thead>
      <tbody id="tbody">
        <tr>
          <td>OPDIGITALS</td>
          <td>OPDIGITALS</td>
          <td>OPDIGITALS</td>
          <td>OPDIGITALS</td>
          <td class="text-center">
              <button class="btn btn-success" data-toggle="modal" data-target="#addUpdate">
                <i class="fa fa-edit"></i>
              </button></td>
          <td class="text-center"><button class="btn btn-danger"><i class="fa fa-remove"></i></button></td> 
        </tr>
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
                <form id='addForm'>
                    <input type="hidden" name="admin_id" id="admin_id" value=''>
                    <input type="hidden" id="request" name="request" value="add">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Input First Name" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="middleName">Middle Name</label>
                                <input type="text" name="middleName" class="form-control" id="middleName" placeholder="Input Middle Name" required>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-xl-6" id="lastNameDiv">
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" name="lastName" class="form-control" id="lastName" placeholder="Input Last Name" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6 account">
                            <div class="form-group">
                                <label for="userName">Username</label>
                                <input type="text" name="userName" class="form-control" id="userName" placeholder="Input username" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6 account">
                            <div class="form-group">
                                <label for="password">password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Input password" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6 account">
                            <div class="form-group">
                                <label for="retypePassword">Retype Password</label>
                                <input type="password" name="retypePassword" class="form-control" id="retypePassword" placeholder="Input Retype Password" required>
                            </div>
                        </div>
                        <div class="col-12" id="alertMessage">
                        </div>
                    </div>
            </div>
            
            <div class="modal-footer">
              <input type="submit" class="btn btn-primary" name="add">
            </div>
            </form>  
          </div>
        </div>
    </div>
    <script>
      addForm.addEventListener('submit',(e) => {
        e.preventDefault();
        if(confirm('Are you sure you want to submit it?')){
            if(admin_id.value.trim() !== ''){
                update();
            } else {
                add();
            }   
        }
      },false);
      function add(){
        if(password.value !== retypePassword.value) {
            alertService.alert({response:'error',message:'Incorrect Retype Password!'});
            } else {
                const  data = $('#addForm').serializeArray();
                $.ajax({
                url:'../process/AdminRoutes.php',
                method:'POST',
                data:data,
                success:(e)=>{ 
                    const message = e == ' Failed' ? 'Failed to add new Admin!' : 'Successfully added new Admin!';
                    alertService.alert({
                        response:e,
                        message:message
                    });
                    if(e===' Success'){
                        fetchAll();
                        addForm.reset();
                        $('#addUpdate').modal('hide');
                    }
                }
                });
         }
      }
      function update(){
        const  data = $('#addForm').serializeArray();
                $.ajax({
                url:'../process/AdminRoutes.php',
                method:'POST',
                data:data,
                success:(e)=>{ 
                    const message = e == ' Failed' ? 'Failed to update new Admin!' : 'Successfully update  new Admin!';
                    alertService.alert({
                        response:e,
                        message:message
                    });
                    if(e==' Success'){
                        fetchAll();
                        addForm.reset();
                        $('#addUpdate').modal('hide');
                    }
                }
                });
      }
      function deleteAdmin(id){
        deleteService.request({id:id,method:'POST',url:'../process/AdminRoutes.php',isset:'delete',complete:(e)=>{ 
           const message = e.toLowerCase() == ' success' ? 'Successfully deleted!' : 'Failed to Delete!'; 
           alertService.alert({response:e,message:message});
           if(e.toLowerCase() == ' success'){
               deleteService.close();
               fetchAll();
           }
        }});
      }
      window.onload=()=>{
          fetchAll();
      };
      function fetchAll(){
        const data={request:'fetchAll'};
         $.ajax({
             url:'../process/AdminRoutes.php',
             method:'POST',
             data:data,
             success:(e)=>{
                const json=JSON.parse(e);
                let tr = [];
                for(let x=0;x<json.length;x++){
                const dataForUpdate = JSON.stringify({admin_id:json[x].admin_id,fname:json[x].fname,mname:json[x].mname,lname:json[x].lname});
                    tr.push(
                        [
                            `<tr> 
                                <td>${json[x].username}</td>
                                <td>${json[x].lname}</td>
                                <td>${json[x].fname}</td>
                                <td>${json[x].mname}</td>
                                <td class="text-center">
                                    <button class="btn btn-success" onclick='openModal(${json[x].admin_id},${dataForUpdate})'>
                                        <i class="fa fa-edit"></i>
                                    </button></td>
                                <td class="text-center"><button class="btn btn-danger" onclick='deleteAdmin(${json[x].admin_id})'><i class="fa fa-trash"></i></button></td> 
                            </tr>`
                        ].join('')
                    );
                }
                tbody.innerHTML="";
                tbody.innerHTML=tr.join('');
                $("#table").DataTable(); 
             }
         });
      }
      function openModal(id,dataForUpdate){
          if(id === null){
            for(const el of document.getElementsByClassName('account')){
                addForm.reset();
                el.style.display="block";
                el.getElementsByTagName('input')[0].required=true;
            }
            lastNameDiv.setAttribute('class','col-lg-6 col-md-6 col-xl-6');
            request.value='add';
          }else{
            for(const el of document.getElementsByClassName('account')) {
                el.style.display="none";
                el.getElementsByTagName('input')[0].required=false;
            }
            request.value="update";
            admin_id.value = dataForUpdate.admin_id;
            firstName.value=dataForUpdate.fname;
            middleName.value=dataForUpdate.mname;
            lastName.value=dataForUpdate.lname;
            lastNameDiv.setAttribute('class','col-12');
          }
        $("#addUpdate").modal('show');
      }
    </script>
    <?php include '../shared/foot.php'; ?>
    <?php include '../shared/alert.php'; ?>
    <?php include '../shared/delete.php'; ?>
</body>
</html>