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
    <style>
        .candidates-position{
            margin-left:10px;
            border-radius:5px;
        }
        .percentage{
            border-radius:5px;
            padding:4px;
            color:white;
            transition: 2s;
            transform: scaleX(0);
            transform-origin:left;
        }
        .graphWrapper {
            
            box-shadow: 0 0 2px gray;
            border-radius:5px;
            background-color: gray;
        }
        .icon {
            font-size: 60px;
        }
        .flex-card {
            width:240px;
        }
        #printTarget {
            display:none;
        }
    </style>
    <style media='print'>
        .not-print, .container-fluid, .modal {
            display:none;
        }
        #printTarget{
            display:block;
        }
        .table {
            width:98%;
            margin:auto;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            border-style:solid;
            border-width:1px;
            border-color:gray;
            padding:4px;
        }
    </style>
<body>
<div id="printTarget">
    <center><h2><span id='printTitle'>Winners</span> (<span id="dateHolder"></span>)</h2></center>
    <table class="table">
        <thead>
            <tr id="printTr">
                
            </tr>
        </thead>
        <tbody id="printBody">
        </tbody>
    </table>
</div>

<div class='not-print'>
    
  <?php include '../shared/navigation.php'; ?>
    <div class="container-fluid mt-2" >
    
    <h1 class="pl-2">Dashboard</h1>
        <div>
            <div class="d-flex justify-content-center">
                <div class="p-2 flex-card card">
                    <div class='d-flex'>
                        <div>
                            <i class='text-primary icon fa fa-group'></i>
                        </div>
                        <div class="flex-grow-1">
                            <center>
                                <h6>Voted</h6>
                                <h3 class="m-0" id='voted'>0</h3>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="p-2 flex-card card">
                    <div class='d-flex'>
                        <div>
                            <i class='text-danger icon fa fa-group'></i>
                        </div>
                        <div class="flex-grow-1">
                            <center>
                                <h6>Not-Voted</h6>
                                <h3 class="m-0" id='notVoted'>0</h3>
                            </center>
                        </div>
                    </div>
                </div><!-- !-->
                <div class="p-2 flex-card card">
                    <div class='d-flex'>
                        <div>
                            <i class='icon fa fa-group'></i>
                        </div>
                        <div class="flex-grow-1">
                            <center>
                                <h6>Total Voters</h6>
                                <h3 class="m-0" id='voters'>0</h3>
                            </center>
                        </div>
                    </div>
                </div><!--!-->
            </div>
        </div>
        <hr>
        <div class="d-flex flex-row">
            <h1 class="flex-grow-1 ml-2">Live Result</h2>
            <div>
                <button class="btn btn-success text-white" onClick="print_who_voted()">Print Voted <i class='fa fa-print'></i></button>
                <button class="btn btn-warning text-white" onClick="print_winner('canvas')">Print Canvas <i class='fa fa-print'></i></button>
                <button class="btn btn-info" onClick="print_winner('candidates')">Print Candidates <i class='fa fa-print'></i></button>
                <button class="btn btn-primary" onClick="print_winner('winner')">Print Winners <i class='fa fa-print'></i></button>
            </div>
        </div>
        <div class="form-group ml-2">
                    <label for="election_date">Election Date</label>
                    <select class="form-control" name="election_date" id="election_date" required onChange="clearTimeout(timeout);displayz()">
                            <?php echo $yearOpt; ?>
                    </select>
        </div>
        <div class="row justify-content-center" id="wrapper">
        </div>
    </div>
    <?php include '../shared/foot.php'; ?>
    <?php include '../shared/alert.php'; ?>
    <?php include '../shared/delete.php'; ?>
</div>
</body>
</html>

    <script>
        let posCan = [];
        let candidateByPos = [];
        let timeout;
        function displayz() {
            const x = [...election_date.getElementsByTagName('option')];
           dateHolder.innerHTML =  x.find(el => el.value==election_date.value).innerHTML;                                                                                                                                                                                                                                                        
            $.ajax({
                url:'../process/VoteRoutes.php',
                method:'get',
                data:{count:'---', election_date: election_date.value},
                success:(e)=>{
                    const data = JSON.parse(e);
                    const position = [];
                    candidateByPos = [];
                    voted.innerHTML= data.vote_status[0].voted;
                    notVoted.innerHTML = data.vote_status[0].not_voted;
                    voters.innerHTML = data.vote_status[0].student_count;
                    percentage_ = [];
                    let htmlData = '';
                    for (el of data.individual_count) {
                        const checkPosition = position.find(pos => pos.positionname == el.positionname);
                        if(!checkPosition) {
                            const candidate = data.individual_count.filter(indi => el.positionname === indi.positionname);
                            position.push({positionname:el.positionname,candidates:candidate});
                        }
                    }

                    for (pos of position) { 
                        let candidateWrapper = '';
                        let total_votes = 0;
                        let total_can = pos.candidates.length;
                        let currentPercent =0;
                        pos.candidates.forEach((candidate1) => {
                            total_votes += candidate1.vote_count;
                        });
                        for (el of data.position_count) {
                            const checkPosition = candidateByPos.find(posz => posz.positionname == el.positionname);
                            if(!checkPosition) {
                                candidateByPos.push({...el,candidates:position.find(poz => poz.positionname == el.positionname).candidates}); 
                            }
                        }

                        pos.candidates = pos.candidates.sort((arr1,arr2) => {
                            return arr2.vote_count-arr1.vote_count;
                        });

                        for (can of pos.candidates) {
                            let percent = (can.vote_count / total_votes) * 1;
                            let class_ = '';
                            if(!percent) {
                                percent = 0;
                            } else {
                                class_ = 'bg-warning'
                                percent = percent;
                            }
                            if (document.getElementById(`p${can.candidateid}`)) {
                                currentPercent = document.getElementById(`p${can.candidateid}`).getAttribute('percent');
                            }
                            const image = can.image ? '../imgs/'+can.image : '../person.png';
                            candidateWrapper += `
                                    <div class='list-group secret candidate_' id='can${can.candidateid}'  
                                        datas=  '{\"fullname\":\"${can.lastname}, ${can.firstname}, ${can.middlename}\",
                                                    \"vote_count\":\"${can.partyname}\",\"candidate_id\":\"${can.candidateid}\"
                                                }'>
                                        <div class='list-group-item'>
                                            <div class='d-flex flex-row' secret>
                                                <div><img src='${image}' style='border-radius:100%; box-shadow:-2px 2px 4px gray' width='50px' height='50px'></div>
                                                    <div class='flex-grow-1 text-center'>${can.lastname}, ${can.firstname}, ${can.middlename}</div>
                                                        <div class="partyname_">${can.partyname}</div>
                                                    </div>
                                                </div>
                                                <div class='list-group-item'>
                                                    <span>No. Votes: ${can.vote_count}</span>
                                                    <div class='graphWrapper'>
                                                        <div class='${class_} percentage' percent='${percent}'
                                                         id='p${can.candidateid}'
                                                         style='transform:scaleX(${currentPercent})'></div>
                                                    </div>
                                                </div>
                                    </div>`;
                                    percentage_.push({canidateIdElement: `p${can.candidateid}`,percent: percent});
                        }
                        position_id.push(`position_${pos.positionname}`);
                        pos['total_candidate'] = total_can;
                        pos[''];
                        posCan.push(pos);
                        htmlData +=
                        `<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12'>
                                <div class='card candidates-position' style='margin-bottom:10px'>
                                    <div class='d-flex p-2 bg-dark'>
                                    <h5 class='m-0 text-white flex-grow-1' id="position_${pos.positionname}">${pos.positionname} (${total_can})</h5>
                                    <!--<div style='cursor: pointer' onclick='showHide("can${can.candidateid}")'>
                                        <i class="fa fa-caret-down text-white"></i>
                                    </div>!-->
                                    </div>
                                        ${candidateWrapper}
                                </div>
                        </div>`;
                    }
                    $('#wrapper').html(htmlData);
                    time_out = window.setTimeout(()=>{
                        percentage_.forEach((element) => {
                            document.getElementById(element.canidateIdElement).style.transform = `scaleX(${element.percent})`;
                        });
                        }, 100);
                    window.setTimeout(() => {
                        displayz();
                    }, 5000);
                }
            });
        }
        displayz();
        let position_id = [];
        function print_winner(print_type){
           let winner = [];
           const finalWinner=[]; 
               candidateByPos.forEach(el => {
               let can = el.candidates;
               const len = can.length;
                for(let x = 0; x < len; x++) {
                    for(let i = len-1; i >= 0 ; i-- ) {
                    if(can[x].vote_count < can[i].vote_count) {
                        const tem = can[x];
                        can[x] = can[i];
                        can[i] = tem;
                    }
                    }     
                } 
                if(print_type=='winner') {
                    winner.push({positionName:el.positionname, candidates:can.sort((arr1, arr2) => {
                        return arr2.vote_count-arr1.vote_count;
                    }).slice(0,el.allowPerParty)});
                } else if(print_type == 'candidates' || print_type == 'canvas') {
                        winner.push({positionName:el.positionname, candidates:can.sort((arr1, arr2) => {
                            return arr2.vote_count-arr1.vote_count;
                        })});
                    
                }
               });
               let data = '';
               winner.forEach(val => {
                    dataTemp = '';//`<tr style='text-align:center'><td colspan='4'>${val.positionName}</td></tr>`;
                    val.candidates.forEach(val2 => {
                        const tdd = print_type !== 'candidates' ? `<td style='text-align:center'>${val2.vote_count}</td>` : '';
                        dataTemp = dataTemp +   `<tr>
                                                    <td>${val2.lastname.toUpperCase()}, ${val2.firstname.toUpperCase()} ${val2.middlename.toUpperCase()}</td>
                                                    <td style='text-align:center'>${val2.partyname}</td>
                                                    ${tdd}
                                                    <td style='text-align:center'>${val2.positionname}</td>
                                                </tr>`;
                    });
                    data+=dataTemp;
               });
               printBody.innerHTML = '';
               printTitle.innerHTML = print_type.toUpperCase();
               printTr.innerHTML = print_type != 'candidates' ? th.winner : th.winner.replace('[candidates-print]',`style='display:none;'`);
               printBody.innerHTML = data;
               print();
              // console.log(winner);
        }

        const th = {winner: `<th>Candidate</th>
                            <th>Partyname</td>
                            <th [candidates-print]>Total Votes</th>
                            <th>Position</td>`
                        };
        function print_who_voted() {
            const el = election_date.getElementsByTagName('option');
            let datee='';
            for(let elem of el){
                if(elem.getAttribute('value') == election_date.value) {
                    date = elem.innerHTML;
                    break;
                }
            }
            window.open(`print-voted.php?election_date=${election_date.value}&date=${date}` , '_blank');
        }
    </script>