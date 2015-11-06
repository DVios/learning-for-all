<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/scripts/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/styles.css">
        <!--as-->
        <script src="Face%20tracker_files/utils.js"></script>
        <script src="Face%20tracker_files/numeric-1.js"></script>
        <script src="Face%20tracker_files/mosse.js"></script>
        <script src="Face%20tracker_files/jsfeat-min.js"></script>
        <script src="Face%20tracker_files/frontalface.js"></script>
        <script src="Face%20tracker_files/jsfeat_detect.js"></script>
        <script src="Face%20tracker_files/left_eye_filter.js"></script>
        <script src="Face%20tracker_files/right_eye_filter.js"></script>
        <script src="Face%20tracker_files/nose_filter.js"></script>
        <script src="Face%20tracker_files/model_pca_20_svm.js"></script>
        <script src="Face%20tracker_files/clm.js"></script>
        <script src="Face%20tracker_files/svmfilter_webgl.js"></script>
        <script src="Face%20tracker_files/svmfilter_fft.js"></script>
        <script src="Face%20tracker_files/mossefilter.js"></script>
        <script src="Face%20tracker_files/Stats.js"></script>
        <!--as-->
        <script>
            /**
             * Coded by Anuradha Wickramarachchi
             */

            /*--Initializing variables--*/
            var eyedistance = 0; // for scaling the emotion detection
            var initeyedistance = -1; // for scaling the initial eyedistance
            var mouthGap = -1; // check if surprised
            var eyeBrowHeight = -1; //
            var mouthWidth = -1; // check if happy
            var eyelidgap = -1; // check if sleepy
            /*--Initializing variables--*/
            var modalOpen = false;

            function detect(positions) {
                // Initializing base variables
                var x1, y1, x2, y2, x3, x4, y3, y4, correctionFactor;
                /*
                 * All the measurements will be considered after correcting with the ratio current eyedistance/initeyedistance
                 * This will avoid the confusion caused by the change in distance from the computer camera
                 * */
                // Initializing the scaling factor
                x1 = positions[27][0];
                y1 = positions[27][1];
                x2 = positions[32][0];
                y2 = positions[32][1];
                eyedistance = Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2));
                if (initeyedistance == -1) {
                    initeyedistance = eyedistance;
                }
                correctionFactor = initeyedistance / eyedistance;
                // Detecting surprise
                x1 = positions[60][0];
                y1 = positions[60][1];
                x2 = positions[57][0];
                y2 = positions[57][1];
                if (mouthGap == -1) {
                    mouthGap = Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2));
                }
                if (mouthGap < Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2)) * correctionFactor) {
                    document.getElementById("surprised").innerHTML = "Surprised";
                } else {
                    document.getElementById("surprised").innerHTML = "Not so Surprised";
                }
                // Detect boring
                if (2 * mouthGap < Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2)) * correctionFactor) {
                    document.getElementById("bored").innerHTML = "Bored";
                } else {
                    document.getElementById("bored").innerHTML = "Not so bored";
                }

                // Detecting smile
                x1 = positions[44][0];
                y1 = positions[44][1];
                x2 = positions[50][0];
                y2 = positions[50][1];
                if (mouthWidth == -1) {
                    mouthWidth = Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2));
                }
                if (mouthWidth < Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2) * correctionFactor)
                        ) {
                    document.getElementById("happy").innerHTML = "Happy";
                }
                else {
                    document.getElementById("happy").innerHTML = "Not so happy";
                }
                // Detect sleepy
                x1 = positions[24][0];
                y1 = positions[24][1];
                x2 = positions[26][0];
                y2 = positions[26][1];
                if (eyelidgap == -1) {
                    eyelidgap = Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2));
                }
                if (eyelidgap * 0.9 > Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2)) * correctionFactor) {
                    document.getElementById("sleepy").innerHTML = "Sleepy";
                }
                else {
                    document.getElementById("sleepy").innerHTML = "Not so sleepy";
                }
                // Detect confusion
                x1 = positions[24][0];
                y1 = positions[24][1];
                x2 = positions[21][0];
                y2 = positions[21][1];
                if (eyeBrowHeight == -1) {
                    eyeBrowHeight = Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2));
                }
                if (eyeBrowHeight > Math.sqrt(Math.pow(y1 - y2, 2) + Math.pow(x1 - x2, 2)) * correctionFactor) {
                    document.getElementById("confused").innerHTML = "Confusing";
                    $(function () {
                        if (!modalOpen) {
                            //$("#myModal").modal("show");
                            modalOpen = true;
                        }
                    });
                }
                else {
                    document.getElementById("confused").innerHTML = "Not so confusing";
                }

            }
// Detecting the inclination of the face for changing slides
            var lx, ly, rx, ry;// cordinates of eyes
            var m; // gradient of the head
            var tolarance = 0.2;
            var rightAl = false;
            var leftAl = true;
            function slide(positions) {
                lx = positions[27][0];
                ly = positions[27][1];
                rx = positions[32][0];
                ry = positions[32][1];
                m = (ly - ry) / (lx - rx);
                document.getElementById("incline").innerHTML = "";
                if (m < 0 && Math.abs(m) > tolarance) {
                    //turning right
                    document.getElementById("incline").innerHTML = "Right" + m;
                    rightAl = true;
                } else if (m > 0 && Math.abs(m) > tolarance) {
                    //turning left
                    document.getElementById("incline").innerHTML = "Left" + m;

                    leftAl = true;
                } else {
                    // if the position comes below the tolerance
                    // from right inclination
                    if (rightAl) {
                        onNextClick();
                        //alert("right");        


                        // perform right slide
                    } else if (leftAl) {
                        onPrevClick();
                        // perform left slide
                    }
                    leftAl = false;
                    rightAl = false;
                }
            }

// Detecting the scrolling notion of the user
            var nx, ny;// cordinates of nose upper
            var nx2, ny2;// cordinates of nose lower
            var initnosedistance = -1; // initial height of the nose
            var moveup = false;
            var movedown = false;
            var t = new Date();
            var scrol_time = t.getTime();
            function scroll(positions) {
                // Update the initial nodes periodically
                /*-Initializing timer-*/
                var t = new Date();
                if (t.getTime() > scrol_time + 5000) {
                    scrol_time = t.getTime();
                    nx = positions[33][0];
                    ny = positions[33][1];
                    nx2 = positions[62][0];
                    ny2 = positions[62][1];
                    initnosedistance = Math.sqrt(Math.pow(ny - ny2, 2) + Math.pow(nx - nx2, 2));
                    movedown = false;
                    moveup = false;
                }
                if (initnosedistance == -1) {
                    // taking the initial nose point
                    // initializing the face height (Height of the nose)
                    nx = positions[33][0];
                    ny = positions[33][1];
                    nx2 = positions[62][0];
                    ny2 = positions[62][1];
                    initnosedistance = Math.sqrt(Math.pow(ny - ny2, 2) + Math.pow(nx - nx2, 2));
                }
                // detecting the changes
                // if the nose deviates 10% keep track of the direction
                // once the nose reaches the initial position update the nose point so that the scrolling is consistent
                var currentNoseDist = Math.sqrt(Math.pow(positions[62][1] - ny, 2) + Math.pow(positions[62][0] - nx, 2));
                document.getElementById("scroll").innerHTML = "";
                if (currentNoseDist > initnosedistance * 1.5) {
                    movedown = true;
                    document.getElementById("scroll").innerHTML = "Down";
                } else if (currentNoseDist < initnosedistance * 0.5) {
                    document.getElementById("scroll").innerHTML = "UP";
                    moveup = true;
                } else {
                    if (movedown) {
                        // scroll down
                        scrollWindow(2);
                    } else if (moveup) {
                        scrollWindow(1);
                        // scroll up
                    }
                    moveup = false;
                    movedown = false;
                }
                //document.getElementById("incline").innerHTML = "";

            }
        </script>
        <script>
            /**
             * Created by Anuradha Sanjeewa on 05/11/2015.
             */
//            init vars
            function scrollWindow(cs) {
                switch (cs) {
                    // up
                    case 1:
                        //window.scrollBy(0, -300);
                        selectPrevRadio();
                        break;
                        // down
                    case 2:
                        selectNextRadio();
                        //window.scrollBy(0, 300);
                        break;
                }
            }
        </script>
        <!--as-->
        <?php
        require_once './header.php';
        require_once './php/API/DatabaseCredentials.php';
        ?>

        <?php
        $category = $_GET['key'];
        $key2= $_GET['key2'];

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Database connection failed. " . mysqli_error($dbc));
        $query = "SELECT DISTINCT qstatus FROM `questions` WHERE category_type='$category' ";
        $result = mysqli_query($dbc, $query) or die("Database error" . mysqli_error($dbc));
        $full_question_array = array();
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {
            $qstatus = $row["qstatus"];
            $query1 = "SELECT * FROM `questions` WHERE category_type='$category' AND qstatus='$qstatus'";
            $result1 = mysqli_query($dbc, $query1) or die("Database error" . mysqli_error($dbc));
            $question_option_array = array();

            $j = 1;
            while ($row1 = mysqli_fetch_array($result1)) {
                $question_array = array();
                $question_array['q'] = $row1["question"];
                $question_array['a1'] = $row1["optionone"];
                $question_array['a2'] = $row1["optiontwo"];
                $question_array['a3'] = $row1["optionthree"];
                $question_array['a4'] = $row1["optionfour"];

                $question_array['c'] = $row1["correct_answer"];
                $question_array['h'] = $row1["hint"];

                $question_option_array['qs' . $j] = $question_array;
                $j = $j + 1;
            }

            $full_question_array['q' . $i] = $question_option_array;
            $i = $i + 1;
        }
        mysqli_close($dbc);
        $arr = array();
        $arr['key'] = $full_question_array;
        $arr = json_encode($arr);
//        print_r($arr);
//        print_r($full_question_array);
        ?>

        <script>
            var data;
            var key;
            var key2;
            $(document).ready(function () {
                key =  '<?php echo $category; ?>';
                key2 =  '<?php echo $key2; ?>';
                data = <?php echo $arr; ?>;
                data = data['key'];

//                data = JSON.parse(data);

//                data = JSON.parse(data);

            })
            // This array is for testing only. Data is fetched from the database in real system. 
            var counter = 0;
            $(document).ready(function () {
                counter = -1;
                onNextClick();
            });

            var correct = 0;
            var radioCounter = 0;
            function onPrevClick() {
//                if (counter > 0) {
//                    counter--;
//                    document.getElementById('question').innerHTML = 'Q' + (counter + 1) + ": " + data['q' + (counter + 1)]['qs1']['q'];
//                    document.getElementById('ap0').innerHTML = data['q' + (counter + 1)]['qs1']['a1'];
//                    document.getElementById('ap1').innerHTML = data['q' + (counter + 1)]['qs1']['a2'];
//                    document.getElementById('ap2').innerHTML = data['q' + (counter + 1)]['qs1']['a3'];
//                    document.getElementById('ap3').innerHTML = data['q' + (counter + 1)]['qs1']['a4'];
//                }
            }
            var flag = false;
            function onNextClick() {
                if (flag && data['q' + (counter + 1)]['qs1']['c'] == $('input[name="optradio"]:checked').val()) {
                    document.getElementById("correct_p").innerHTML = "Correct answer!";
                    setTimeout(function () {
                        correct++;
                        flag = false;
                        onNextClick();//alert('1');
                    }, 500);
//                    radioCounter = -1;
                    return;
                }
                else if (flag && data['q' + (counter + 1)]['qs1']['c'] != $('input[name="optradio"]:checked').val()) {
                    $("#modal_p").html(data['q' + (counter + 1)]['qs1']['h']);
                    $("#myModal").modal("show");//alert('2');
                    setTimeout(function () {
                        $("#myModal").modal("hide");
                    }, 3000);
                }
                else if (counter < 1) {
                    document.getElementById("correct_p").innerHTML = "";
                    flag = true;
                    counter++;
//                    alert(counter);
//                    alert(data['q' + counter + 1]['qs1']['q']);
                    document.getElementById('question').innerHTML = 'Q' + (counter + 1) + ": " + data['q' + (counter + 1)]['qs1']['q'];
                    document.getElementById('ap0').innerHTML = data['q' + (counter + 1)]['qs1']['a1'];
                    document.getElementById('ap1').innerHTML = data['q' + (counter + 1)]['qs1']['a2'];
                    document.getElementById('ap2').innerHTML = data['q' + (counter + 1)]['qs1']['a3'];
                    document.getElementById('ap3').innerHTML = data['q' + (counter + 1)]['qs1']['a4'];
                }
                else if (counter == 1) {
                    $("#mymodal_title").html("Quiz over!");
                    $("#modal_p").html("End of Quiz.");
                    $("#myModal").modal("show");
                    setTimeout(function () {
                        $("#myModal").modal("hide");
//                        console.log(key);
                        window.location.href = "title_view.php?key=" + key2;
                    }, 3000);
                }
            }


            function selectNextRadio() {
//                alert("#a" + radioCounter % 4);
//                document.getElementById("a" + radioCounter % 4).checked = true;
                if (radioCounter < 3) {
                    $("#a" + radioCounter).prop("checked", true);
                    radioCounter++;
                }
                else if (radioCounter == 3) {
                    radioCounter = 0;
                    $("#a" + radioCounter).prop("checked", true);
                }
            }

            function selectPrevRadio() {
//                alert("#a" + radioCounter % 4);
//                document.getElementById("a" + radioCounter % 4).checked = true;
                if (radioCounter > 0) {
                    $("#a" + radioCounter % 4).prop("checked", true);
                    radioCounter--;
                }
                else if (radioCounter == 0) {
                    radioCounter = 3;
                    $("#a" + radioCounter).prop("checked", true);
                }
            }
        </script>

    </head>

    <body style="margin-top: 50px">
        <div class="container"><div class="col-md-3"></div>

            <div class="col-md-6" >
                <form class="form-inline">
                    <p id="question"></p>
                    <input type="radio" name="optradio" value="1" id="a0" checked="checked" ><p id="ap0" class="radio"></p><br>
                    <input type="radio" name="optradio" value="2" id="a1"><p id="ap1" class="radio"></p><br>
                    <input type="radio" name="optradio" value="3" id="a2"><p id="ap2" class="radio"></p><br>
                    <input type="radio" name="optradio" value="4" id="a3"><p id="ap3" class="radio"></p><br>
                </form>
                <br>
                <button id="left" type="button" class="btn" name="submit_value" onclick="onPrevClick()">Previous Question</button>
                <button id="right" type="button" class="btn" name="submit_value" onclick="onNextClick()">Next Question</button>

                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 id="mymodal_title" class="modal-title">Wrong answer</h4>
                            </div>
                            <div class="modal-body">
                                <p id="modal_p"></p>
                            </div>
                            <div class="modal-footer">
                                <!--<button id="button_prev" class="btn left" onclick="prev()">No</button>-->
                                <button id="button_next" class="btn right" onclick="">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <p><font color="green" size="20" id="correct_p"></font></p>
                </div>
            </div>

            <div class="col-md-3"></div>
            <!--as-->
            <div id="container"  class="col-md-3">
                <video id="videoel" preload="auto" loop="" height="300" width="400" >

                </video>
                <canvas id="overlay" width="400" height="300"></canvas>

                <!--<input class="btn" value="start" onclick="" id="startbutton" type="button">-->

                <div id="tt">
                    <p id="surprised"></p>

                    <p id="confused"></p>

                    <p id="happy"></p>

                    <p id="bored"></p>

                    <p id="sleepy"></p>

                    <p id="incline"></p>

                    <p id="scroll"></p>
                </div>

            </div>
            <?php require_once './facetracking.php';
            ?>
            <!--as-->
        </div>

    </body>

</html>
