<?php

// The recall information retrieved from the RecallRepository.
class ProductInfo {
	// The details of the product recall.
	public $description;
	
	// The product's manufacturer.
	public $manufacturer;
	
	// The date the recall was made. This is NOT the date the search
	// was made. It's the date the recall was announced to the public.
	public $recalledOn;
	
	// The URL that contains details about the product recall.
	public $recallUrl;
}

?>