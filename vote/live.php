
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
    </style>
    <div class="container-fluid mt-2" style='width:100%;margin:0 !important'>
        <div class=" d-none">
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
        <center><h1>Live Result</h2></center>
        <div class="row justify-content-center" id="wrapper">
        </div>
    </div>
    <script>
        let timer = false;
        function displayz() {
            $.ajax({
                url:'../process/VoteRoutes.php',
                method:'get',
                data:{count:'---',election_date:'<?php echo $latestElection; ?>'},
                success:(e)=>{
                    const data = JSON.parse(e);
                    const position = [];
                    voted.innerHTML= data.vote_status[0].voted;
                    notVoted.innerHTML = data.vote_status[0].not_voted;
                    voters.innerHTML = data.vote_status[0].student_count;
                    percentage_ = [];
                    let htmlData = '';
                    console.log(data);
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
                            candidateWrapper += `
                                    <div class='list-group secret' id='can${can.candidateid}'>
                                        <div class='list-group-item'>
                                            <div class='d-flex flex-row' secret>
                                                <div><img src='../person.png' height='50px' height='50px'></div>
                                                    <div class='flex-grow-1 text-center'>${can.lastname}, ${can.firstname}, ${can.middlename}</div>
                                                        <div>${can.partyname}</div>
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
                        htmlData +=
                        `<div class='col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12'>
                                <div class='card candidates-position' style='margin-bottom:10px'>
                                    <div class='d-flex p-2 bg-dark'>
                                    <h5 class='m-0 text-white flex-grow-1'>${pos.positionname} (${total_can})</h5>
                                    <!--<div style='cursor: pointer' onclick='showHide("can${can.candidateid}")'>
                                        <i class="fa fa-caret-down text-white"></i>
                                    </div>!-->
                                    </div>
                                        ${candidateWrapper}
                                </div>
                        </div>`;
                    }
                    $('#wrapper').html(htmlData);
                    window.setTimeout(()=>{
                        percentage_.forEach((element) => {
                            document.getElementById(element.canidateIdElement).style.transform = `scaleX(${element.percent})`;
                        });
                        }, 100);
                    if(timer == true) {
                        window.setTimeout(() => {
                            displayz();
                        }, 5000);
                    }
                }
            });
        }
    </script>