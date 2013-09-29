<div id='pagination'>
<?php

	if($pagination->setPaging($paging)){

	$prev = $pagination->prevPage('Prev',true,""); // Any text you want 4 link.
	$prev = $prev?$prev:''; // If no link display something.
	$next = $pagination->nextPage('Next',true,""); // Any text you want 4 link.
	$next = $next?$next:''; // In no link dislpay something.

	// If required/desired - define the first and last pages. Not automatic at time of writing.
	//$first = "";
	//$last = "";
	

	$pages = $pagination->pageNumbers(" | ");
//	echo $pagination->resultsPerPage('Show ', ' | ')." per page. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo $pagination->result('Showing ')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo $prev." ".$pages." ".$next."<br/>";	
	}

?>
</div>