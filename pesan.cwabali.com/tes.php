<?php
$text = '
<?php
$lalala = "lorem ipsum dolor sit amet";
function tes(){
	hihihi;


	
}
?>
';
echo "<pre>";
echo htmlentities($text);
echo "</pre>";