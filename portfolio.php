<!DOCTYPE html>
<!--
IMAGE SOURCES
history - http://mrewert.pbworks.com/f/1348008812/why-study-history-2c3t0vv.jpg
'phyci - http://www.wesleyan.edu/history/newsandevents.html

chemistry - http://64.131.110.41/moodle/pluginfile.php/5026/course/summary/chemistry.jpg
bio - https://sites.google.com/site/indianabiologyteachers/
commerce - https://www.yaaka.cc/cgi-sys/suspendedpage.cgi 

acc - https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTeANIwd6nlYTR0xkxZ2m8PNXHNdOhrqIH7GS1DU7g5JRE8ocmxtg-->

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
                        //alert("right");        
                        $(function () {
                            dummy += 1;
                            if (dummy >= 6) {
                                dummy = 5;
                            } else {
                                $("#selector").text("<<" + arr[dummy] + ">>");
                            }
                        });

                        // perform right slide
                    } else if (leftAl) {
                        // perform left slide
                        $(function () {
                            dummy -= 1;
                            if (dummy < 0) {
                                dummy = 0;
                            } else {
                                $("#selector").text("<<" + arr[dummy] + ">>");
                            }
                        });
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
                    console.log("update timer");
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
                if (currentNoseDist > initnosedistance * 2) {
                    movedown = true;
                    document.getElementById("scroll").innerHTML = "Down";
                } else if (currentNoseDist < initnosedistance * 0.5) {
                    document.getElementById("scroll").innerHTML = "UP";
                    moveup = true;
                } else {
                    if (movedown) {
                        // scroll down
                        gotopage();
                    } else if (moveup) {
                        gotopage();
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
            function gotopage() {
                window.location.href = "title_view.php?key=" + arr[dummy];
            }
        </script>
        <!--as-->
    </head>

    <body id="categories">
        <div id="interface" class="bg-light-gray">
            <div class="container">
                <div class="row col-sm-8">
                    <div class="col-md-3 portfolio-item">
                        <a href="title_view.php?key=History" class="portfolio-link" data-toggle="modal">
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content">
                                    <i class="fa fa-plus fa-3x"></i>
                                </div>
                            </div>
                            <img src="img/portfolio/history.png" class="img-responsive" alt="">
                        </a>
                        <div class="portfolio-caption">
                            <h4 align="center">History</h4>
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <a href="title_view.php?key=Physics" class="portfolio-link" data-toggle="modal">
=======
                    <div class="col-md-3 portfolio-item">
                        <a href="title_view.php?key=physics" class="portfolio-link" data-toggle="modal">
>>>>>>> 00539eb123362da8eb117690ee59283c0e30be34
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content">
                                    <i class="fa fa-plus fa-3x"></i>
                                </div>
                            </div>
                            <img src="img/portfolio/physics.png" class="img-responsive" alt="">
                        </a>
                        <div class="portfolio-caption">
                            <h4 align="center">Physics</h4>
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <a href="title_view.php?key=Chemistry" class="portfolio-link" data-toggle="modal">
=======
                    <div class="col-md-3 portfolio-item">
                        <a href="title_view.php?key=chemistry" class="portfolio-link" data-toggle="modal">
>>>>>>> 00539eb123362da8eb117690ee59283c0e30be34
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content">
                                    <i class="fa fa-plus fa-3x"></i>
                                </div>
                            </div>
                            <img src="img/portfolio/chemistry.png" class="img-responsive" alt="">
                        </a>
                        <div class="portfolio-caption">
                            <h4 align="center">Chemistry</h4>
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <a href="title_view.php?key=Biology" class="portfolio-link" data-toggle="modal">
=======
                    <div class="col-md-3 portfolio-item">
                        <a href="title_view.php?key=biology" class="portfolio-link" data-toggle="modal">
>>>>>>> 00539eb123362da8eb117690ee59283c0e30be34
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content">
                                    <i class="fa fa-plus fa-3x"></i>
                                </div>
                            </div>
                            <img src="img/portfolio/biology.png" class="img-responsive" alt="">
                        </a>
                        <div class="portfolio-caption">
                            <h4 align="center">Biology</h4>
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <a href="title_view.php?key=Commerce" class="portfolio-link" data-toggle="modal">
=======
                    <div class="col-md-3 portfolio-item">
                        <a href="title_view.php?key=commerce" class="portfolio-link" data-toggle="modal">
>>>>>>> 00539eb123362da8eb117690ee59283c0e30be34
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content">
                                    <i class="fa fa-plus fa-3x"></i>
                                </div>
                            </div>
                            <img src="img/portfolio/commerce.png" class="img-responsive" alt="">
                        </a>
                        <div class="portfolio-caption">
                            <h4 align="center">Commerce</h4>
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <a href="title_view.php?key=Accounting" class="portfolio-link" data-toggle="modal">
=======
                    <div class="col-md-3 portfolio-item">
                        <a href="title_view.php?key=accounting" class="portfolio-link" data-toggle="modal">
>>>>>>> 00539eb123362da8eb117690ee59283c0e30be34
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content">
                                    <i class="fa fa-plus fa-3x"></i>
                                </div>
                            </div>
                            <img src="img/portfolio/accounting.png" class="img-responsive" alt="">
                        </a>
                        <div class="portfolio-caption">
                            <h4 align="center">Accounting</h4>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                var dummy = 0;
                var arr = ["History", "physics", "chemistry", "biology", "commerce", "accounting"];
                $(document).ready(function () {
                    $("#selector").text("<<" + arr[dummy] + ">>");
                });
            </script>
            <div class="container">
                <p  class="text-center">Please sway your head to select the option</p>
                <p class="text-center" style="font-size: 3em" id="selector"></p>
            </div>
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
