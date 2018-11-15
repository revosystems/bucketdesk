<html>
<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            background-color:#2F3235;
        }
        .login{
            width:500px;
            margin:0 auto;
            margin-top:150px;
            text-align: center;
        }
        .login input{
            height:35px;
            margin-bottom:4px;
        }
    </style>
</head>
    <body>
        <div class="login">
            <img src="/images/bones.png" class="mb3">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="E-Mail">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif

                <a class="btn btn-link" href="{{ route('password.request') }}" style="margin-left:-60px;">
                    {{ __('Forgot?') }}
                </a>

                <br>
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
                <br>
                <button type="submit" class="btn btn-primary" style="width:300px; height:35px;">
                    {{ __('Login') }}
                </button>


            </form>
        </div>
    </body>
</html>