<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/scripts/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/styles.css">
        <script src="js/script.js"></script>

        <?php
        require_once './header.php';
        require_once './php/API/DatabaseCredentials.php';
        $main_category = $_GET['key'];

        $complete_array = array();
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Database connection failed. " . mysqli_error($dbc));
        $query = "SELECT DISTINCT sub_category_type FROM `content` WHERE main_category_type='$main_category' ";
        $result = mysqli_query($dbc, $query) or die("Database error" . mysqli_error($dbc));
        $sub_category_array = array();
        while ($row = mysqli_fetch_array($result)) {
            $sub_category = $row["sub_category_type"];
            $sub_category_array["main_title"] = $sub_category;
            $query1 = "SELECT DISTINCT category_type FROM `content` WHERE sub_category_type='$sub_category'";
            $result1 = mysqli_query($dbc, $query1) or die("Database error" . mysqli_error($dbc));
            $category_array = array();
            while ($row1 = mysqli_fetch_array($result1)) {
                array_push($category_array, $row1["category_type"]);
            }
            $sub_category_array["sub_titles"] = $category_array;
            array_push($complete_array, $sub_category_array);
        }
        mysqli_close($dbc);
        $complete_array = json_encode($complete_array);
        ?>

        <script>
            $(document).ready(function () {
                var titles = '<?php echo $complete_array; ?>';
//                alert(titles);
                titles = JSON.parse(titles);
                if (titles)
                {
                    var main_list = document.getElementById('main_list');
                    for (i = 0; i < titles.length; i++) {
                        var main_list_item = document.createElement("li");
                        main_list_item.id = "main_list_item_" + i;
                        main_list_item.className = "nav-header";

                        var a = document.createElement("a");
                        a.id = "main_title_" + i;
                        a.href = "#";
                        a.setAttribute("data-toggle", "collapse");
                        a.setAttribute("data-target", "#" + i + "_child_list");
                        a.innerHTML = titles[i]['main_title'];

                        var _i = document.createElement("i");
                        _i.className = "glyphicon glyphicon-chevron-right";

                        var child_list = document.createElement("ul");
                        child_list.id = i + "_child_list";
                        child_list.className = "nav nav-stacked collapse";

                        for (j = 0; j < titles[i]['sub_titles'].length; j++) {
                            //                                alert(titles[i]['sub_titles'][j]);
                            var child_list_item = document.createElement("li");
                            child_list_item.id = i + "_child_list_item_" + j;

                            var a_sub = document.createElement("a");
                            a_sub.id = i + "_subtitle_" + j;
                            a_sub.href = "tutorial_view.php?key=" + titles[i]['sub_titles'][j];
//                                a_sub.href = "#"
//                                a_sub.setAttribute("onclick", loadTutorial());
                            a_sub.innerHTML = titles[i]['sub_titles'][j];

                            child_list_item.appendChild(a_sub);
                            child_list.appendChild(child_list_item);
                        }
                        a.appendChild(_i);
                        main_list_item.appendChild(a);
                        main_list_item.appendChild(child_list);
                        main_list.appendChild(main_list_item);
                    }

                }
                else
                {

                }

            });

        </script>

    </head>

    <body id="header_footer" style="margin-top: 50px">

        <?php
        require './header.php';
        ?>

        <div class="col-md-3">

        </div>

        <div class="col-md-6">
            <ul id="main_list" class="nav nav-stacked">
            </ul>
        </div>

        <div class="col-md-3">

        </div>

    </body>
</html>
