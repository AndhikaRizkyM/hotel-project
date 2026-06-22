<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | PPKD HOTEL HMS</title>

  <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
</head>

<body class="auth-body">
  <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
    <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
  </button>
  
  <main class="auth-page">
    <section class="auth-card">
      <a class="auth-brand" href="#"><span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span><span><strong>PPKD HOTEL</strong><small>Create your HMS management account.</small></span></a>
      <div class="auth-visual"><img src="{{ asset('template/assets/images/png/dasher-ui-bootstrap-5.jpg') }}" alt="adminHMD dashboard interface"></div>
      
      <form class="needs-validation" action="{{ route('register') }}" method="POST" novalidate>
        @csrf
        <div class="mb-4">
          <p class="eyebrow mb-1">Secure Access</p>
          <h1 class="h3 mb-1">Register</h1>
          <p class="text-muted mb-0">Create your PPKD HMS account.</p>
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

        <div class="mb-3">
          <label class="form-label" for="registerName">Full name</label>
          <input class="form-control" name="name" id="registerName" type="text" value="{{ old('name') }}" required>
          <div class="invalid-feedback">Full name is required.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="registerEmail">Email address</label>
          <input class="form-control" name="email" id="registerEmail" type="email" value="{{ old('email') }}" required>
          <div class="invalid-feedback">Enter a valid email.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="registerRole">Workspace Role</label>
          <select class="form-select" name="role" id="registerRole" required>
            <option value="" disabled selected>Select Role</option>
            <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin / Superadmin</option>
            <option value="FO" {{ old('role') === 'FO' ? 'selected' : '' }}>Front Office (FO)</option>
            <option value="HK" {{ old('role') === 'HK' ? 'selected' : '' }}>Housekeeping (HK)</option>
            <option value="FB" {{ old('role') === 'FB' ? 'selected' : '' }}>Food & Beverage (FB)</option>
          </select>
          <div class="invalid-feedback">Please select a role.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="registerPassword">Password</label>
          <input class="form-control" name="password" id="registerPassword" type="password" minlength="8" required>
          <div class="invalid-feedback">Password must be at least 8 characters.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="registerPasswordConfirm">Confirm Password</label>
          <input class="form-control" name="password_confirmation" id="registerPasswordConfirm" type="password" required>
          <div class="invalid-feedback">Please confirm your password.</div>
        </div>

        <button class="btn btn-primary w-100" type="submit"><i class="bi bi-person-plus" aria-hidden="true"></i> Create Account</button>
      </form>
      
      <div class="auth-footer">Already have an account? <a href="{{ route('login') }}">Sign in</a></div>
    </section>
  </main>

  <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/assets/js/main.js') }}"></script>
</body>
</html>
