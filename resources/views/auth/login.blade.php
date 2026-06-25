<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | PPKD HOTEL HMS</title>

  <!-- Google Fonts Geist -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
  
  <style>
    body {
      font-family: 'Geist', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
    }
  </style>
</head>

<body class="auth-body">
  <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
    <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
  </button>

  <main class="auth-page">
    <section class="auth-card">
      <a class="auth-brand" href="#"><span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span><span><strong>PPKD HOTEL</strong><small>HMS Management Workspace</small></span></a>
      <div class="auth-visual"><img src="{{ asset('template/assets/images/png/dasher-ui-bootstrap-5.jpg') }}" alt="adminHMD dashboard interface"></div>

      <form class="needs-validation" action="{{ route('login') }}" method="POST" novalidate>
        @csrf
        <div class="mb-4">
          <p class="eyebrow mb-1">Secure Access</p>
          <h1 class="h3 mb-1">Login</h1>
          <p class="text-muted mb-0">Sign in to your admin workspace.</p>
        </div>

        @if($errors->any())
          <div class="alert alert-danger border-0 small py-2">
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(session('success'))
          <div class="alert alert-success border-0 small py-2">
            {{ session('success') }}
          </div>
        @endif

        <div class="mb-3">
          <label class="form-label" for="loginEmail">Email address</label>
          <input class="form-control" name="email" id="loginEmail" type="email" value="{{ old('email') }}" required>
          <div class="invalid-feedback">Enter a valid email.</div>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <label class="form-label" for="loginPassword">Password</label>
          </div>
          <input class="form-control" name="password" id="loginPassword" type="password" required>
          <div class="invalid-feedback">Please enter your password.</div>
        </div>

        {{-- <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
          <label class="form-check-label" for="rememberMe">Remember me</label>
        </div> --}}

        <button class="btn btn-primary btn-tactile w-100" type="submit"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Sign In</button>
      </form>

      {{-- <div class="auth-footer">New here? <a href="{{ route('register') }}">Create an account</a></div> --}}
    </section>
  </main>

  <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/assets/js/main.js') }}"></script>
</body>
</html>
