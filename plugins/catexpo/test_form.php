<?php
	$req=fopen('php://input','r');
	echo stream_get_contents($req);
$i=1;
while (isset($_POST['id_notice'.$i])) {
	echo '$_POST'.'<br/>';
	print_r($_POST['id_notice'.$i]);
	echo '<br/>';
	echo '$_REQUEST : '.'<br/>';
	print_r($_REQUEST['id_notice'.$i]);
	echo '<br/>';
	$i++;
}
?>

<form enctype="" action="test_form.php" method="post">

<!--With "multiple" attribute-->

<input type="hidden" value="article|33" name="id_notice1[]"></input>

<input type="hidden" value="article_33" name="id_notice2[]"></input>

<input type="hidden" value="33" name="id_notice3[]"></input>


<!--Without "multiple" attribute-->

<input type="hidden" value="article|33" name="id_notice4"></input>

<input type="hidden" value="article_33" name="id_notice5"></input>

<input type="hidden" value="33" name="id_notice6"></input>

<input type="submit"/>

</form>