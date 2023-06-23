<style>
    #list .list-group-item{
        background-color:transparent !important;
        color: #222222;
    }
    #list .list-group-item:hover{
        background-color: #007b !important;
        color: white;
    }
    .side-bar{
        background-color:whitesmoke !important;
        backface-visibility: hidden;
    }
    .side-bar.shadow { 
        box-shadow: 0 0 3px rgba(0,0,0,0.7);
    }
    #navbar{
        background-color: #007b;
    }
</style>
<nav class='head-nav shows' id="navbar">
    <div>
        <div class="d-flex flex-row">
            <div onclick="showSideBar()">
                <div class="burger show-burger" id="burger">
                    <div class="b1"></div>
                    <div class="b2"></div>
                    <div class="b3"></div>
                </div>
            </div>
            <div>
                <h4 class="title"> NACOS JABU ELECTION</h4>  
            </div>
        </div>
    </div>
</nav>
<div class="side-bar shows">
    <div class="header">
        <h4 class="header-title">CSC ELECTION </h4>
    </div>
    <di class="side-bar-content" id="list">
        <div class="list-group">
            <a id='dashboard.php' href="dashboard.php" class="list-group-item" style="border-radius:0px !important">Dashboard</a>
            <a id="election-date.php"  href="election-date.php" class="list-group-item">Election Date</a>
            <a id='candidates.php' href="candidates.php" class="list-group-item">Candidates</a>
            <a id='position.php' href="position.php" class="list-group-item">Position</a>
            <a id='course.php' href="course.php" class="list-group-item">Course</a>
            <a id='yearlevel.php' href="yearlevel.php" class="list-group-item">
            <i class="fa fa-level"></i>Year Level</a>
           <a id='party.php' href="party.php" class="list-group-item">Party</a>
            <a id='student.php' href="student.php" class="list-group-item">Student</a>
            <!-- <a href="votes.php" class="list-group-item">Votes</a> -->
            <a id='admin.php' href="admin.php" class="list-group-item">Admin</a>
            <a href="#" class="list-group-item" data-target='#account' data-toggle="modal">Account</a>
            <a href="logout.php" class="list-group-item">Logout</a>
        </div>
    </div>
</div>
<div class="modal fade" id="account">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
          
            <div class="modal-header">
              <h4 class="modal-title">Change Password</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form method="POST" action="" id="changePasswordForm">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="courseInitial">Old Password</label>
                                <input class="form-control" type='password' name="oldPassword" id='oldPassword' placeholder="Old Password" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="courseInitial">New Password</label>
                                <input class="form-control" type='password' name="newPassword" id='newPassword' placeholder="New Password" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group">
                                <label for="courseInitial">Retype New Password</label>
                                <input class="form-control" type='password' name="retypeNewPassword" id='retypeNewPassword' placeholder="Retype New Password" required>
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
<script>
    function showSideBar(){ 
        burger.classList.toggle('show-burger');
        document.getElementsByClassName('side-bar')[0].classList.toggle('shows');
        document.getElementsByClassName('head-nav')[0].classList.toggle('shows');
        document.getElementsByClassName('position-absolute')[0].classList.toggle('shows');
    }
    const locationz =location.pathname.split('/');
    document.getElementById(locationz[locationz.length-1]).classList.add('active');
    window.setTimeout(()=>{
        document.querySelector('body').style.opacity = '1';
    },500);

    window.addEventListener('scroll',()=>{
        if(window.scrollY > 0) {
            navbar.classList.add('shadow');
        }else {
            navbar.classList.remove('shadow');
        }
    },false);

    changePasswordForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if(newPassword.value != retypeNewPassword.value) {
            alertService.alert({
                        response:'error',
                        message: `Incorrect Retype New password`});
        } else {
            if(confirm('Are you sure you want to submit it?')) {
                $.ajax({
                    url:'../process/change-password.php',
                    method:'post',
                    data:{
                            oldPassword:oldPassword.value,
                            newPassword:newPassword.value
                        },
                    success:(e) => {
                        const ee = e.trim().replace(' ', '');
                        alertService.alert({
                        response:ee,
                        message: ee != 'success' ? 'Incorrect Old Password!' : 'Account Successfully Change!'});
                        if(ee=='success') {
                            window.setTimeout(()=> {
                                alertService.alert({
                                response:ee,
                                message: 'Your Account will logout..'});
                                window.setTimeout(()=> {
                                    window.location = 'logout.php';
                                },1000);
                            },1020);
                        }
                    }
                });
            }
        }
    } , false);
</script>