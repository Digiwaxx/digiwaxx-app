<html>
<body>
<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                   <div class="card-body">					  
						<h3>Hi,</h3>
						<p>Dj member requested for additional services from the Artist at DigiWaxx. Please check:</p>
		                <p>Track Name : <?php echo $data['trackname']; ?></p>
		                <p>Track Artist : <?php echo $data['trackArtist']; ?></p>
		                <p>Dj Member Info : <?php echo $data['name'].', '.$data['userEmailId']; ?></p>
		                <p>Additional Things Requested from Artist : <?php echo $data['additional_things_requested']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>