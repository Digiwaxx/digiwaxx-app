<!DOCTYPE html>
<html lang="en">
<head>
  <title>Reset your Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Reset Your Password</h2>
  
@if (\Session::has('password_changed'))
<div class="alert alert-success">
<ul>
    <li>{!! \Session::get('password_changed') !!}</li>
</ul>
</div>
@endif
  <form action="{{ route('submit_reset_admin_password', $ad_mail) }}">
  @csrf
    <div class="form-group">
    <input type="hidden" class="form-control" id="get_id" placeholder="{{ $ad_mail }}" name="get_id">
      <label for="password">Enter New Password:</label>
      <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" required placeholder="New Password" name="new_password">
      @error('new_password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

</body>
</html>
