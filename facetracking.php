<style>
/*        body {
            font-family: 'Lato';
            background-color: #f0f0f0;
            margin: 0px auto;
            max-width: 1150px;
        }*/

        #overlay {
            position: fixed;
            right: 0px;
            top: 0px;
            -o-transform: scaleX(-1);
            -webkit-transform: scaleX(-1);
            transform: scaleX(-1);
            -ms-filter: fliph; /*IE*/
            filter: fliph; /*IE*/
        }

        #videoel {
            position: fixed;
            right: 0px;
            top: 50px;
            -o-transform: scaleX(-1);
            -webkit-transform: scaleX(-1);
            transform: scaleX(-1);
            -ms-filter: fliph; /*IE*/
            filter: fliph; /*IE*/
        }
            
        #tt {
            position: fixed;
            right: 0px;
            width: 400px;
            top: 350px;
        }
        

/*        #content {
            margin-top: 70px;
            margin-left: 100px;
            margin-right: 100px;
            max-width: 950px;
        }*/

        
    </style>
<!--<script src="Face%20tracker_files/utils.js"></script>
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
<script src="emotiondetect.js"></script>
<script src="scroller.js"></script>-->

<script>
//    $(document).ready(function () {
//
//        $('.modal').on('hidden.bs.modal', function () {
//            modalOpen = false;
//        });
//    });
//    function f() {
//        document.getElementById('butt').click();
//    }
//    f();
</script>

<!--<div class="navbar-fixed-top" style="margin-top: 50px">

    <div id="container" class="col-md-3 col-md-offset-9">
        <video id="videoel" preload="auto" loop="" height="300" width="400">
            
        </video>
        <canvas id="overlay" width="400" height="300"></canvas>

        <input class="btn" value="start" onclick="startVideo()" id="startbutton" type="button">

        <div>
            <p id="surprised"></p>

            <p id="confused"></p>

            <p id="happy"></p>

            <p id="bored"></p>

            <p id="sleepy"></p>

            <p id="incline"></p>

            <p id="scroll"></p>
        </div>

    </div>
</div>-->



    <script>
        
        var vid = document.getElementById('videoel');
        var overlay = document.getElementById('overlay');
        var overlayCC = overlay.getContext('2d');

        var ctrack = new clm.tracker({useWebGL: true});
        ctrack.init(pModel);

        //        stats = new Stats();
        //        stats.domElement.style.position = 'absolute';
        //        stats.domElement.style.top = '0px';
        //        document.getElementById('container').appendChild(stats.domElement);

        function enablestart() {
//            var startbutton = document.getElementById('startbutton');
//            startbutton.value = "start";
//            startbutton.disabled = null;
            var startVidCall = setTimeout(startVideo(),3000);
        }

        var insertAltVideo = function (video) {
            if (supports_video()) {
                if (supports_ogg_theora_video()) {
                    video.src = "./media/cap12_edit.ogv";
                } else if (supports_h264_baseline_video()) {
                    video.src = "./media/cap12_edit.mp4";
                } else {
                    return false;
                }
                //video.play();
                return true;
            } else return false;
        }
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
        window.URL = window.URL || window.webkitURL || window.msURL || window.mozURL;

        // check for camerasupport
        if (navigator.getUserMedia) {
            // set up stream

            var videoSelector = {video: true};
            if (window.navigator.appVersion.match(/Chrome\/(.*?) /)) {
                var chromeVersion = parseInt(window.navigator.appVersion.match(/Chrome\/(\d+)\./)[1], 10);
                if (chromeVersion < 20) {
                    videoSelector = "video";
                }
            }
            ;

            navigator.getUserMedia(videoSelector, function (stream) {
                if (vid.mozCaptureStream) {
                    vid.mozSrcObject = stream;
                } else {
                    vid.src = (window.URL && window.URL.createObjectURL(stream)) || stream;
                }
                vid.play();
            }, function () {
                insertAltVideo(vid);
                document.getElementById('gum').className = "hide";
                document.getElementById('nogum').className = "nohide";
                alert("There was some problem trying to fetch video from your webcam, using a fallback video instead.");
            });
        } else {
            insertAltVideo(vid);
            document.getElementById('gum').className = "hide";
            document.getElementById('nogum').className = "nohide";
            alert("Your browser does not seem to support getUserMedia, using a fallback video instead.");
        }

        vid.addEventListener('canplay', enablestart, false);

        function startVideo() {
            // start video
            vid.play();
            // start tracking
            ctrack.start(vid);
            // start loop to draw face
            drawLoop();
        }

        function drawLoop() {
            requestAnimFrame(drawLoop);
            overlayCC.clearRect(0, 0, 400, 300);
            //psrElement.innerHTML = "score :" + ctrack.getScore().toFixed(4);
            if (ctrack.getCurrentPosition()) {
                var positions = ctrack.getCurrentPosition();
                detect(positions);
                slide(positions);
                scroll(positions);

                ctrack.draw(overlay);
            }
        }

        // update stats on every iteration
        //        document.addEventListener('clmtrackrIteration', function (event) {
        //            stats.update();
        //        }, false);


    </script>