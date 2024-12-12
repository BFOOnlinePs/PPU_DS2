<!doctype html>
<html lang="en">
  <head>
  	<title>تسجيل الدخول :: كلية الدراسات الثنائية</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
      <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="{{ asset('assets/login/css/style.css') }}">

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-3">
 				</div>
			</div>
			<div class="row justify-content-center">



				<div class="col-md-6 col-lg-5">
					<div class="login-wrap p-4 p-md-5" align="center" >

					<img src="{{ asset('assets/login/images/ds-ppu.png') }}" width="250"   >


		      	<h3 class="text-center mb-4">Login to your account </h3>
						<form action="{{ route('login') }}" method='post' class="login-form">
                            @csrf
		      		<div class="form-group">
		      			<input type="email" id="email" name="email" class="form-control rounded-left" placeholder="Email" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
		      		</div>
	            <div class="form-group d-flex">
	              <input type="password" id="password" class="form-control rounded-left" name="password" placeholder="Password" required>
	            </div>
	            <div class="form-group d-md-flex">
	            	<div class="w-50">

					</div>
	            </div>

{{--                <div class="mt-2">--}}
{{--                                <div class="div p-1 ">--}}
{{--                                    <button onclick="login('admin@gmail.com','123456789')" type="button" class="btn btn-info btn-sm form-control">أدمن</button>--}}
{{--                                </div>--}}
{{--                                <div class="div p-1">--}}
{{--                                    <button onclick="login('EzdeharJwabreh@ppu.edu.ps','123456789')" type="button" class="btn  btn-info btn-sm form-control">المشرف</button>--}}
{{--                                </div>--}}
{{--                                <div class="div p-1">--}}
{{--                                    <button onclick="login('mazen@ppu.edu.ps','123456789')" type="button" class="btn  btn-info btn-sm form-control">مساعد إداري</button>--}}
{{--                                </div>--}}
{{--                                <div class="div p-1">--}}
{{--                                    <button onclick="login('ayman@ppu.edu.ps','123456789')" type="button" class="btn  btn-info btn-sm form-control">مسؤول متابعة وتقييم</button>--}}
{{--                                </div>--}}
{{--                                <div class="div p-1">--}}
{{--                                    <button onclick="login('abdelmajeed@jawwal.ps','123456789')" type="button" class="btn  btn-dark btn-sm form-control">مدير الشركة</button>--}}
{{--                                </div>--}}

{{--                                <div class="div p-1">--}}
{{--                                    <button onclick="login('179033@ppu.edu.ps','123456789')" type="button" class="btn  btn-info btn-sm form-control">طالب</button>--}}
{{--                                </div>--}}
{{--                                <div class="div p-1">--}}
{{--                                    <button onclick="login('mohamadher@ppu.edu.ps','123456789')" type="button" class="btn  btn-info btn-sm form-control">مسؤول التواصل مع الشركات</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
	            <div class="form-group">
	            	<button type="submit" class="btn btn-primary rounded submit p-3 px-5">  Login </button>
	            </div>

	          </form>
	        </div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

  <script>
        function login(username,password) {
            document.getElementById('email').value = username;
            document.getElementById('password').value = password;
        }
    </script>
	</body>
</html>

