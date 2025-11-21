NEW REVIEW RECEIVED!
================================================================

Hi {{ $clientName }},

Great news! DJ {{ $djName }} just reviewed your track:

TRACK INFORMATION:
------------------
Track: {{ $trackTitle }}
Artist: {{ $trackArtist }}

REVIEW DETAILS:
---------------
Reviewed by: {{ $djName }}
Date: {{ $reviewDate }}
Rating: {{ $stars }} ({{ $rating }} out of 5 stars)

@if(!empty($comment))
COMMENT:
--------
"{{ $comment }}"
@else
No written comment provided.
@endif

================================================================

VIEW FULL ANALYTICS:
{{ $trackUrl }}

DOWNLOAD FULL REPORT (PDF):
{{ $reportDownloadUrl }}

================================================================

Digiwaxx - Your Music Distribution Platform

----------------------------------------------------------------
Don't want to receive review notifications?
Unsubscribe here: {{ $unsubscribeUrl }}
