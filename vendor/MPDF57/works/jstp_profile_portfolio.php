<?php
	
$html = '
<style>
	h1,h2,h3,h4{
		margin: 0;
		padding: 0;
	}
	h1 {
		font-size: 44px;
	}
	h2 {
		font-size: 40px;
	}
	h3 {
		font-size: 36px;
	}
	h4 {
		font-size: 32px;
	}
</style>
<body>
	<div name="page1" class="page">
		<div style="margin: 150px 0 0 300px;">
			<h3 style="color: #47B224;">JSPT OFFICIAL</h3>
			<h1 style="color: #333333;">PROFILE &
			PORTFOLIO</h1>
			<h4 style="color: #333333; font-weight: normal">Junior talented <span style="color: #47B224; font-weight: bold;">scientist</span></h4>
			<hr style="height: 5px; color: #14330A;">

			<h4 style="margin: 300px 0 0 0;">Name Surname</h4>
		</div>
	</div>
	<pagebreak />

	<div name="page2" class="page">
		<table>
			<tr></tr>
		</table>
	</div>
	<pagebreak />

	<div name="page2" class="page">page 3</div>
	<pagebreak />

	<div name="page2" class="page">page 4</div>
<body>
';

//==============================================================
//==============================================================
//==============================================================

include("../mpdf.php");

$mpdf=new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//==============================================================
//==============================================================
//==============================================================


?>