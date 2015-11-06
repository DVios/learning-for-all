<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/scripts/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/styles.css">

        <script>
            var data = new Array(3);
            data[0] = "<h1>Linear Algebra</h1><h2>Introduction</h2><p>This is a introduction to linear algebra.</p><h2>Theorem 1</h2><p>This is most basic theorem of linear algebra.</p>";
            data[1] = "<body><h1>Linear Algebra</h1><h2>Theorem 2</h2><p>This is theorem 2 of linear algebra.</p><h2>Theorem 3</h2><p>This is theorem 3 of linear algebra.</p></body>";
            data[2] = "<body><h1>Linear Algebra</h1><h2>Theorem 4</h2><p>This is theorem 4 of linear algebra.</p><h2>Theorem 5</h2><p>This is theorem 5 of linear algebra.</p></body>";


//            var data = new Array(3);
//            $(document).ready(function () {
//                $.getJSON('DATABASE.php', function (d) {
//                    if (data)
//                    {
//                        data = d;
//                        alert('success');
//                    }
//                    else
//                    {
//                        alert('error');
//                    }
//                });
//            });

            var counter = 0;
            $(document).ready(function () {
                counter = -1;
                onNextClick();
            });

            function onPrevClick() {
                if (counter > 0) {
                    counter--;
                    document.getElementById('tutorial').innerHTML = data[counter];
                }
            }

            function onNextClick() {
                if (counter < data.length - 1) {
                    counter++;
                    document.getElementById('tutorial').innerHTML = data[counter];
                }
            }
        </script>
    </head>

    <body>
        <div id="container" style="clear:both;">
            <div>
                <p id="tutorial"></p>
            </div>

            <button id="left" type="button" class="btn" name="submit_value" onclick="onPrevClick()">Previous Question</button>
            <button id="right" type="button" class="btn" name="submit_value" onclick="onNextClick()">Next Question</button>
        </div>
    </body>

</html>
