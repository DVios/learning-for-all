<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(document).on('submit', '#loginForm', function (event) {
            event.preventDefault();
            var username = $("#username").val();
            var password = $("#password").val();
            $.post('controller/authentication/auth.php', {
                password: password,
                username: username
            }, function (data, textStatus, xhr) {
                if ($.parseJSON(data) == false) {
                    $.post('modals/login_fail.php', function (data, textStatus, xhr) {
                        $("#modal_handler").html(data);
                        $("#login_fail").modal('show');
                    });
                } else {
                    location.reload();
                }
            });
        });
    });
</script>
<div id="main" class="container ver-center">
    <div class="col-sm-12 well">
        <h1 class="text-center">Welcome!</h1>
        <hr/>	
        <div class="container row">
            <div class="col-sm-4">
                <img class="img-responsive" style="margin: 0 auto" src="images/ayubowan.gif" alt="AYUBOWAN" width="200" height="200">
            </div>

            <div class="col-sm-6">
                <br/><br/><br/>
                <form id="loginForm" class="form-horizontal" role="form" onsubmit="return false;">
                    <div class="form-group">
                        <label class="control-label col-sm-4">NIC</label>
                        <div class="col-sm-8">
                            <input id="username" type="text" class="form-control" placeholder="Enter your NIC" required/>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label class="control-label col-sm-4">Password</label>
                        <div class="col-sm-8">
                            <input id="password" type="password" class="form-control" placeholder="Enter the password" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-default">Login</button>
                        </div>		
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>