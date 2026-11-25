{{-- Supplier sign-up form. Shown inside auth/login.blade.php. --}}
<div class="card p-4">
  <h1 class="h4 mb-1">Create a supplier account</h1>
  <p class="text-muted mb-4">Sell your calligraphy to buyers all over Lebanon.</p>

  <form action="{{ route('register.custom') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="d-flex align-items-center gap-3 mb-4">
      <label for="photoprofile" class="mb-0" style="cursor:pointer" title="Choose photo">
        <img src="{{ asset('img/profile.png') }}" id="profilephoto" alt="" class="rounded-circle border object-fit-cover" width="72" height="72">
      </label>
      <div>
        <label for="photoprofile" class="btn btn-light btn-sm mb-0">Upload photo</label>
        <div class="text-muted small mt-1">Optional, shown on your profile</div>
      </div>
      <input type="file" name="photoprofile" id="photoprofile" accept="image/*" class="d-none" onchange="loadFile(event)">
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
    </div>
    <div class="mb-3">
      <label for="reg-email" class="form-label">Email</label>
      <input type="text" name="email" id="reg-email" class="form-control" value="{{ old('email') }}">
    </div>
    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label for="reg-password" class="form-label">Password</label>
        <input type="password" name="password" id="reg-password" class="form-control" autocomplete="new-password">
      </div>
      <div class="col-sm-6">
        <label for="password_confirmation" class="form-label">Confirm password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
      </div>
    </div>
    <div class="mb-3">
      <label for="address" class="form-label">Address</label>
      <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
    </div>
    <div class="mb-4">
      <label for="phone" class="form-label">Phone number</label>
      <div class="input-group">
        @include('layouts.phonecodes')
        <input type="text" name="phone" id="phone" class="form-control">
      </div>
    </div>

    <button type="submit" id="reg" name="btnadd" class="btn btn-dark w-100">Create account</button>
  </form>

  <p class="text-center text-muted small mt-4 mb-0">
    Already registered? <button type="button" id="btnhide" class="btn btn-link btn-sm p-0 align-baseline">Sign in</button>
  </p>
</div>

<script>
  var loadFile = function (event) {
    document.getElementById('profilephoto').src = URL.createObjectURL(event.target.files[0]);
  };
</script>
