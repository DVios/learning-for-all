<!DOCTYPE html>
<html>
    <head>
        <title>Big Data Big Learning</title>
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
            /*-Initializing timer-*/
            var t = new Date();
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
                        //alert("right");        
                        next();

                        // perform right slide
                    } else if (leftAl) {
                        // perform left slide
                        prev();
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
            var scrol_time = t.getTime();
            function scroll(positions) {
                // Update the initial nodes periodically
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
            function scrollWindow(cs) {
                var H, y;
                var intervaller = setInterval(move, 20);
                var timeouter = setTimeout(stop, 1000);
                switch (cs) {
                    // up
                    case 1:
                        H = -400;
                        //window.scrollBy(0, -300);
                        break;
                        // down
                    case 2:
                        //window.scrollBy(0, 300);
                        H = 400;
                        break;
                }
                function move() {
                    window.scrollBy(0, H / 20);
                }
                function stop() {
                    clearInterval(intervaller);
                }
            }
        </script>
        <!--as-->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/scripts/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/styles.css">
        <script src="js/script.js"></script>   

        <?php
        require_once './header.php';
        require_once './php/API/DatabaseCredentials.php';
        $category = $_GET['key'];
        $key2=$_GET['key2'];

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Database connection failed. " . mysqli_error($dbc));
        $query = "SELECT slides FROM `content` WHERE category_type='$category'";
        $result = mysqli_query($dbc, $query) or die("Database error" . mysqli_error($dbc));
        $slides = array();
        while ($row = mysqli_fetch_array($result)) {
            // print_r($row);
            array_push($slides, $row['slides']);
        }
        mysqli_close($dbc);
//        print_r($slides);
        $arr = array();
        $arr['key'] = $slides;
        $slides = json_encode($arr);
//        print_r($slides);
        ?>

        <script>
            var slides;
            var key;
            var key2;
            $(document).ready(function () {
                key = '<?php echo $category; ?>';
                key2 = '<?php echo $key2; ?>';
                slides = <?php echo $slides; ?>;
                //                slides = JSON.parse(slides);
                slides = slides['key'];
//                alert(slides);

                //                slides = JSON.parse(slides);
                //                alert(slides);
                document.getElementById("tutorial").innerHTML = slides[0];
            });
            var counter = 0;
            function prev() {
                if (counter == slides.length) {
                    $("#myModal").modal("hide");
                }
                else if (counter > 0) {
                    $("#tutorial").fadeOut("slow", function () {
                        document.getElementById("tutorial").innerHTML = slides[--counter];
                        $("#tutorial").fadeIn("slow");
                    });
                }
            }
            function next() {
                if (counter < slides.length - 1) {
                    $("#tutorial").fadeOut("slow", function () {
                        document.getElementById("tutorial").innerHTML = slides[++counter];
                        $("#tutorial").fadeIn("slow");
                    });
                }
                else if (counter == slides.length - 1) {
                    counter++;
                    $("#myModal").modal("show");
                }
                else if (counter == slides.length) {
                    $("#myModal").modal("hide");
                    window.location.href = "quiz.php?key=" + key + "&key2=" + key2 ;
                }

            }

        </script>
    </head>

    <body id="home" style="margin-top: 50px">
        <div class="container">
            <div class="col-md-3">

            </div>

            <div class="col-md-6">
                <button id="button_prev" class="btn left" onclick="prev()">Previous</button>
                <button id="button_next" class="btn right" onclick="next()">Next</button>
                <hr>
                <div class="well"><p id="tutorial"></p></div>       

                <div class="container">
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <!--                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Modal Header</h4>
                                                            </div>-->
                                <div class="modal-body">
                                    <p>Start quiz now?</p>
                                </div>
                                <div class="modal-footer">
                                    <button id="button_prev" class="btn left" onclick="prev()">No</button>
                                    <button id="button_next" class="btn right" onclick="next()">Yes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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
