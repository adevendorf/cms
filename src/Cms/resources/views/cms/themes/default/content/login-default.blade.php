<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="cms-form" method="POST" action="/login">

                        <div class="form-group">
                            <input type="email" class="form-control" name="email" value="cms@devendorf.com" xvalue="" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" name="password" value="123456" placeholder="Password">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="/password/reset">Forgot Your Password?</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>