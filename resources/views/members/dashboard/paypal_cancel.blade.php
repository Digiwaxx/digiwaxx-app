<div>
	<h3 style="font-family: 'quicksandbold'; font-size:16px; color:#313131; padding-bottom:8px;">Dear User</h3>
    <span style="color:#D70000; font-size:16px; font-weight:bold;">We are sorry! Your last transaction was cancelled.</span>
        @if (session('return_status') === TRUE)
            <div class="alert alert-success">
               {{ session('return_message') }}
            </div>
            @elseif(session('return_status') === FALSE)
            <div class="alert alert-danger">
               {{ session('return_message') }}
            </div>
            @endif
</div>
