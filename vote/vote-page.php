<!Doctype html>
<html>
  <?php require '../session.php'; ?>
  <?php include '../shared/head.php'; ?>
  <?php include '../shared/alert.php'; ?>
  
  <?php
    require '../process/config.php';
    date_default_timezone_set('Asia/Manila');
    $config = new Config();
    $conn = $config->getConnection();
    $sql = "select * from tbl_election_date order by election_date desc";
    $query = mysqli_query($conn, $sql);
    $response = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $latestElection = $response[0]['election_date_id'];
    $latestElectionDate = $response[0]['election_date'];
    $query->close();
    $sql = "select p.*,ed.* from tblparty as p
    INNER JOIN tbl_election_date as ed ON p.party_election_date_id = ed.election_date_id
    INNER  JOIN tblcandidate as c on p.id = c.partyid
    where p.party_election_date_id = ".$latestElection."
    group by p.id";
    $query = mysqli_query($conn, $sql);
    $response = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $partyStraight = '';
    foreach($response as $p) {
        $partyStraight = $partyStraight.'
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="partyStraight m-2" onclick="voteStraight('.$p['id'].')">
                '.$p['partyname'].'
            </div>
        </div>';
    }
    $dd = date('Y-m-d');
    $sql = 'select * from tbl_election_date where election_date = "'.$dd.'"';
    $qry= mysqli_query($conn, $sql);
    $elec_date = mysqli_fetch_all($qry,MYSQLI_ASSOC);
    $datee = isset($elec_date[0]['election_date']) ? date('M d, Y', strtotime($elec_date[0]['election_date'])) : 'No Election Date Set Yet';
    $cannot_vote = isset($elec_date[0]['election_date']) ? true : false;
  ?>
<body>
<style>
    body{
        background-color: whitesmoke;
        margin:0px;
        padding:0px;
        overflow-x:hidden;
        opacity: 1;
    }
    .header-card {
        padding:10px;
        background-color: deepskyblue;
        border-radius:25px;
    }
    .checked {
        display:block;
        color: gray;
    }
    .radio-wrapper{background-color:primary !important;
        width:40px;
    }
    .radio{
        border-style: solid;
        border-width: 1px;
        border-color: transparent;
        margin-top: 10px;
        border-radius:100%;
        cursor: pointer;
        margin-right:10px;
        box-shadow: -1px 1px 4px black;
    }
    .radio:hover {
        box-shadow: 0 0 2px dodgerblue;
    }
    .candidate-name p{
        padding:0px;
        margin: 0px;
    }
    .candidate {
        border-radius:15px;
        padding:5px;
        padding-bottom:0px;
        margin-top:40px;
        transition: 1s;
        opacity:0;
        will-change: transform;
    }
    .candidate-img {
        padding:7px;
    }
    .candidate-img img {
        border-radius:100%;
    }
    .check{
        margin:5px;
        color:#444444;
        opacity:0;
        transition:0.5s;
        transform:scale(0);
        transition-timing-function: cubic-bezier(0.1, 0.4 , 0.3, 1.8);
    }
    .check.checked{
        opacity:1;
        transform:scale(1);
        color: dodgerblue;
    }

    .next-prev {
        position: fixed;
        bottom: 20px;
        right:10px;
        width:100%;
        text-align: right;

    }
    .next-prev button {
        min-width:120px;
        border-radius: 5px;
        height:30px;
        border-style: none;
        box-shadow: -2px 2px 4px gray;
        background-color: white;
        color: dodgerblue;
        transition: 1s;
        margin-left:5px;
        margin-right:5px;
        cursor: pointer;
        height:40px;
        font-weight: bold;
    }
    .next-prev button:hover {
        background-color:white;
    }
    .disabled-button{
        pointer-events: none;
        opacity: 0.4;
    }
    .hide-button{
        display: none;
    }
    #studentCode{
        width:100%;
        height:100%;
        position: fixed;
        z-index:888;
        background-color: lightblue;
        transition: 0.5s;
    }
    .student-code{
        padding:5px;
    }
    #studentCode h1 {
        margin-top:40%;
    }

    .showzz {
      position:fixed;
      top:10px;
      right:10px;
      font-size:25px;
      color:white;
      z-index:9999;
      color:white;
      cursor:pointer;
      text-shadow:0px 0px 9px white;
      box-shadow: 0 0 4px white;
      width:50px;
      height:50px;
      text-align:center;
      border-radius:100%;
      border-style:none;
      background-color:transparent;
    }
    .showzz:hover {
       background-color:white;
       text-shadow: 0 0 9px gray;
       outline-style:none;
    }
    .graph {
        right: 70px;
    }
    #RealTimeChart {
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background-color:white;
        z-index:10000;
        overflow-y:auto;
        transition:1s;
        opacity:0;
        display:none;
    }
    #RealTimeChart.showw {
        opacity:1;
    }
    #RealTimeChart .closez{
        position: absolute;
        right:10px;
        top:10px;
        border-radius:8px;
        background-color:transparent;
        color:white;
        width:30px;
        height:30px;
        font-size:20px;
        border-radius:100%;
        border-style:none;
        box-shadow: 0 0 4px crimson;
        text-shadow: 0 0 9px crimson;
        cursor:pointer;
    }
    #RealTimeChart .closez:hover {
        background-color:crimson;
    }
    .realtime-content {
    }
    #voteStraight {
        position:fixed;
        top:5px;
        right:10px;
        font-size:1.1em;
        padding:8px;
        border-radius:5px;
        border-style:none;
        font-weight:bold;
        cursor:pointer;
        background-color:dodgerblue;
        color:#fff;
    }
    #voteStraight:hover {
        background-color:white;
        color:dodgerblue;
    }
    .partyStraight {
        padding:10px;
        border-radius:10px;
        cursor: pointer;
        text-align:center;
        padding-top:15px;
        padding-bottom:15px;
        font-weight:bold;
        background-color:white !important;
        box-shadow: -2px 2px 4px gray !important;
    }
    .partyStraight:hover{
        box-shadow: 0 0 9px dodgerblue;
        color:dodgerblue;
    }
    #candidatePositionName {
        font-size: 1.5em;
        margin-top:10px;
        color: #fff !important;
    }
    #candidateWrapper {
        margin-bottom:80px;
    }
    #electionDate {
        position: fixed;
        bottom:10px;
        left:10px;
        z-index: 999;
        padding: 10px;
        border-radius: 5px;
        border-radius: 5px;
        background-color:white !important;
    }
    .disabled-page {
    }
    #logo {
        position:fixed;
        top:30px;
        left:30px;
        z-index: 999;
    }
	#logo1 {
        position:fixed;
        top:30px;
        left:200px;
        z-index: 999;
    }
</style>
<h5 id='electionDate'>Election Date: <small><?php echo $datee; ?></small> </h5>
<button id='voteStraight' data-toggle="modal" data-target="#voteStraightModal">Vote Straight</button>
<div class="modal fade" id="voteStraightModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header bg-primary text-white">
              <h4 class="modal-title">Select Party</h4>
              <button type="button" id="close" class="close" data-dismiss="modal">&times;</button>
            </div>

                <div class="modal-body">
                    <div class='row justify-content-center' data-dismiss="modal">
                            <?php echo $partyStraight; ?>
                    </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-default">Close</button>
                </div>
          </div>
        </div>
    </div>
<div id="studentCode">
    <button  class="showzz" onClick="window.location='../admin/';"><i class="fa fa-user"></i></button>
    <button  class="showzz graph" onClick="graph('show')"><i class="fa fa-bar-chart"></i></button>
    <div class="row justify-content-center">
        <!-- <img src="../imgs/JABULOGO.png" width="300px" height="100px" id="logo"> -->
		
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <h1 class="text-center text-white">NACOSS JABU Election</h1>
            <div class="card student-code">
            <div class="d-flex flex-row">
            Voting Here! <?php echo !$cannot_vote ? '<span class="text-warning">(Not Available)</span>' : '' ?></div>
            <input class="form-control" type="text" placeholder="Input Voting Code..." id="votingCodeInput" <?php echo !$cannot_vote ? 'disabled' : ''; ?>/>
            <button class="btn btn-dark btn-block mt-2" onClick="submitVotingCode()">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="p-1 bg-primary disabled-page">
    <h1 id="candidatePositionName" class="text-white"></h1>
</div>
    <div class="row justify-content-center ml-1 mr-1"  id='candidateWrapper'>
    </div>
    <div class='next-prev'>
        <button onClick="prev();" id="prev">Previous</button>
        <button onClick="next();"  id="next">Next</button>
        <button onClick="sumbitVote();" class='hide-button'  id="submitBtn">Submit</button>
    </div>

<div id="RealTimeChart">
    <button class="closez" onClick="graph('close')"><i class="fa fa-close"></i></button>
    <div class="realtime-content">
        <?php include('live.php'); ?>
   </div>
</div>
  <?php include '../shared/foot.php'; ?>
</body>
<script type="text/javascript">
    function graph(req) {
        if(req == 'show') {
            RealTimeChart.style.display= 'block';
            window.setTimeout(()=>{
                RealTimeChart.classList.add('showw');
                timer = true;
                displayz();
            },10);
        } else {
            RealTimeChart.classList.remove('showw');
                timer = false;
            window.setTimeout(()=>{
                RealTimeChart.style.display= 'none';
            },600);
        }
    }
    let targetPosition = 0;
    let candidates = [];
    let positionGroup = [];
    let votes = [];
    let id = "";
    function getCandidate() {
        $.ajax({
            method:'GET',
            url:'../process/CandidateRoutes.php',
            data:{candidate:'candidate_vote',election_id:'<?php echo $latestElection; ?>'},
            success: (e)=> {
                studentCode.style.opacity = "0";
                studentCode.style.pointerEvents = "none";
                candidates = JSON.parse(e);
                getPosition(JSON.parse(e));
            }
        })
    }
    function submitVotingCode() {
        let voteCode = $("#votingCodeInput").val();
        if(voteCode !== ""){
            $.ajax({
                method: 'GET',
                url: '../process/vote_process.php',
                data:{checkIfVoted:123,data:voteCode,election_id:'<?php echo $latestElection; ?>'},
                success: function(e){
                    let data = JSON.parse(e);
                    $("#votingCodeInput").val('');
                    id = String(data['data']);
                    if(data['status']=="success"){
                        alertService.alert({
                            response:data['status'],
                            message: data['message']
                        });
                        voteLogin();
                    } else {
                        alertService.alert({
                            response:data['status'],
                            message: data['message']
                        });
                    }
                },
                error: function(e){
                    alert(e);
                }
            });
        }
    }
    function voteLogin(){
        getCandidate();
        setTimeout(()=>{
            studentCode.style.pointerEvents = "none";
        },500);
    }
    function getPosition(_candidate) {
            let curPositionName = '';
            $.each(_candidate, (index, value) => {
                if(curPositionName != value.positionname) {
                    curPositionName = value.positionname;
                    positionGroup.push({positionName:value.positionname,votesAllowed:value.votesallowed});
                }
            });
            displayCandidate();
            disable_enable_prev_next();
    }
    function prev(){
        if(targetPosition > 0) {
            targetPosition--;
            displayCandidate();
        }
        disable_enable_prev_next();
    }

    function next(){
        if(targetPosition < positionGroup.length-1) {
            targetPosition++;
            displayCandidate();
        }
        disable_enable_prev_next();
    }

   function disable_enable_prev_next() {
        if(targetPosition <= 0) {
            document.getElementById('prev').classList.add('disabled-button');
        } else {
            document.getElementById('prev').classList.remove('disabled-button');
        }
        if(targetPosition >= positionGroup.length-1) {
            document.getElementById('next').classList.add('disabled-button');
            document.getElementById('next').classList.add('hide-button');
            submitBtn.classList.remove('hide-button');
        } else {
            document.getElementById('next').classList.remove('disabled-button');
            document.getElementById('next').classList.remove('hide-button');
            submitBtn.classList.add('hide-button');
        }
        changeHeaderLabel();
    }

    function votesAllowed_1(element_id, candidate_id) {
        const id = votes.find(vote => {
            if(vote.candidate_id == candidate_id && vote.positionName == positionGroup[targetPosition].positionName) {
                return true;
            }
        });
        if(!id){
            uncheckAllFirst(element_id);
            votes = votes.filter(vote => vote.positionName != positionGroup[targetPosition].positionName);
            votes.push({candidate_id:candidate_id,positionName:positionGroup[targetPosition].positionName});
        } else {
            votes = votes.filter(vote => vote.candidate_id != candidate_id);
        }
        checkRadioButton(element_id);
    }

    function votesAllowed_more(element_id, candidate_id) {
        const id = votes.find(vote => {
            if(vote.candidate_id == candidate_id && vote.positionName == positionGroup[targetPosition].positionName) {
                return true;
            }
        });
        if(id === undefined){
            const voteSelected = votes.filter(vote => vote.positionName == positionGroup[targetPosition].positionName);
            if( positionGroup[targetPosition].votesAllowed > voteSelected.length){
                votes.push({candidate_id:candidate_id,positionName:positionGroup[targetPosition].positionName});
                checkRadioButton(element_id);
            }else {
                let position_name = positionGroup[targetPosition].positionName;

                if (position_name[position_name.length-1].toLowerCase() != 's'){
                    position_name = position_name + 's';
                }
                alertService.alert({
                response:'failed',
                message:`You can only select ${positionGroup[targetPosition].votesAllowed} ${position_name}`
            })
            }
        } else {
            votes = votes.filter(vote => vote.candidate_id != candidate_id);
            checkRadioButton(element_id);
        }
    }

    function checkUncheck(element_id, candidate_id) {
        if(parseInt(positionGroup[targetPosition].votesAllowed) == 1) {
            votesAllowed_1(element_id, candidate_id);
        } else {
            votesAllowed_more(element_id, candidate_id);
        }
    }

    function uncheckAllFirst(element_id) {
        const elements = document.getElementsByClassName('check');
        $.each(elements, (index, element) => {
            element.classList.remove('checked');
        });
    }

    function alreadyCheckChecker(element_id, candidate_id) {
        if(votes.find(vote => vote.candidate_id == candidate_id)) {
            checkRadioButton(element_id);
        }
        console.log(candidate_id,votes);

    }

   function checkRadioButton(element_id) {
        document.getElementById(element_id).getElementsByTagName('i')[0].classList.toggle('checked');
    }

    function sumbitVote() {
        if(votes.length > 0){
            if(confirm('Are you sure you want to submit your vote?')){
                let data = new FormData();
                $.each(votes, (index,vote) => {
                    data.append('candidate[]',vote.candidate_id);
                });
                data.append('id', id);
               $.ajax(
                   {
                       url: '../process/VoteRoutes.php',
                       method: 'POST',
                       data: data,
                       processData: false,
                       contentType: false,
                       cache: false,
                       success: (e) => {
                           voteStatusSend();
                          votes = [];
                          targetPosition = 0;
                          positionGroup = [];
                          id="";
                          alertService.alert({
                                response: e,
                                message: 'Your votes has been submitted! Thank you!'
                           });
                           window.setTimeout(function(){
                                studentCode.style.pointerEvents = "all";
                                studentCode.style.opacity = "1";
                           }, 2000)
                       }
                   }
               )
            }
        } else {
            alertService.alert({
                response:'failed',
                message:'Cast your vote first before submitting!'
            })
        }
    }

    function voteStatusSend(){
        $.ajax({
                method: 'GET',
                url: '../process/vote_process.php',
                data:{submitVote:123,id:id,election_id:'<?php echo $latestElection; ?>'},
                success: function(e){
                },
                error: function(e){
                    alert(e);
                }
            });
    }

    function changeHeaderLabel() {
       $('#candidatePositionName').html(positionGroup[targetPosition].positionName+' ['+positionGroup[targetPosition].votesAllowed+']');
    }

    function displayCandidate() {
        changeHeaderLabel();
        const filteredCandidates = candidates.filter( candidate => candidate.positionname == positionGroup[targetPosition].positionName);
        $('#candidateWrapper').html('');
        $.each(filteredCandidates, (index, candidate) => {
            let image = '../person.png';
            if(candidate.image != '') {
                image = '../imgs/'+candidate.image;
            }
            $('#candidateWrapper').append(
                ` <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <div class='candidate card'  id="candidate_${candidate.id}">
                        <div class="d-flex flex-row">
                            <div class="candidate-img"><img src="${image}" width="60" height="60"></div>
                                <div class="flex-grow-1 text-center candidate-name">
                                    <p class="font-weight-bold">${candidate.lastname.toUpperCase()}, ${candidate.firstname.toUpperCase()}</p>
                                    <p style="color:gray">${candidate.partyname.toLowerCase()}</p>
                                </div>
                                <div class="radio-wrapper">
                                    <div class="radio" id="check${candidate.id}" onclick="checkUncheck('check${candidate.id}',${candidate.id})">
                                        <i class="fa fa-check check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`
            );
            alreadyCheckChecker('check'+candidate.id,candidate.id);
            window.setTimeout(() => {
                document.getElementById('candidate_'+candidate.id).style.opacity="1";
                document.getElementById('candidate_'+candidate.id).style.transition= (0.5*(2+index))+'s';
            }, 100);
        });
    }
    function voteStraight(id) {
        if(!confirm('Are you sure you want to Select it?')) {
            return false;
        }
        votes = new Array();
        uncheckAllFirst();
        candidates.forEach(candidate => {
           if(candidate.p_id == id) {
               votes.push({candidate_id:candidate.id,positionName:candidate.positionname});
               if(positionGroup[targetPosition].positionName == candidate.positionname) {
                alreadyCheckChecker(`check${candidate.id}`,candidate.id)
               }
           }
       });
       sumbitVote();
       $('#voteStraightModal').modal('hide');
    }
</script>
</html>
<?php
    if(isset($_SESSION['status'])){
        $login = $_SESSION['status'];
        $idno = $_SESSION['id'];
        echo "<script>
        id = ".$idno."
        voteLogin();
        </script>";
    }
?>
