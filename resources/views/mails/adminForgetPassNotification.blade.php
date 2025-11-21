{{-- SECURITY FIX: Using nl2br with escaped output instead of raw --}}
<pre>{!! nl2br(e($m_msg)) !!}</pre>