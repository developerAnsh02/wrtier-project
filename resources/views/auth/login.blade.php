
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="https://warrgyizmorsch.com/wp-content/uploads/2021/09/warris-logo.png">
    <title>Writer Portal</title>
    <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
</head>

<body class="skin-default card-no-border">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Writer admin</p>
        </div>
    </div>
    <section id="wrapper">
        <div class="login-register" style="background-image:url(../assets/images/background/login-register.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    @if (session('danger'))
                        <div class="alert alert-danger">
                            {{ session('danger') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="form-horizontal form-material" id="loginform">
                        @csrf
                        <h3 class="text-center m-b-20">Sign In</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="email"  name="email" :value="old('email')" required> 
                            </div>
                            <x-input-error :messages="$errors->get('email')" style="color:red" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="password" id="password" type="password" required="" placeholder="Password"> 
                            </div>
                            <x-input-error :messages="$errors->get('password')" style="color:red" class="mt-2" />
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1 remember_me">
                                        
                                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button class="btn w-100 btn-lg btn-info btn-rounded text-white" type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="../assets/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
    </script>
    
</body>

</html>
