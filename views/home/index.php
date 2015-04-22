<?php
	use yii\helpers\Url;
?>
<!-- --------------------------------------------------------------------------- -->
<div class="container">
	<div class="row header">
		<div class="col-md-12">
			<div class="row topbar">
				<div class="col-md-1">
					<div class="pull-left">
						<a href="#">Home</a>
					</div>
				</div>
				<div class="col-md-11">
					<div class="pull-right">
						<a href="#">Sign up</a> | <a href="#">Launguage</a>
					</div>
				</div>
			</div>
			<div class="row logobar">
				<div class="col-md-4">
					<img src="<?= Url::to('@web/images/aec-logo.png') ?>">
				</div>
				<div class="col-md-7">
					<div class="search-contianer pull-left">
						<div class="row">
							<div class="col-md-10">
								<form>
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Search for...">
										<span class="input-group-btn">
											<button class="btn btn-default" type="button">
												<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
											</button>
										</span>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row navbar">
				<div class="col-md-12">
					<nav class="navbar navbar-default">
						<div class="container">
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav">
									<li class="active">
										<a href="#">Home</a>
									</li>
									<li>
										<a href="#">About</a>
									</li>
									<li>
										<a href="#">Criteria</a>
									</li>
									<li>
										<a href="#">Jurnal Submission</a>
									</li>
									<li>
										<a href="#">Contact us</a>
									</li>
									<li>
										<a href="#">Download</a>
									</li>
								</ul>
							</div>
						</div>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<div class="row content">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-12">
					<div class="dummy-data" style="height: 550px;">
						Category
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="dummy-data" style="height: 200px;">
						Browse 99,9999,999 users
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
					<div class="dummy-data" style="height: 250px;">
						Announcement and Events
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="dummy-data" style="height: 750px;">
						Recently Article
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row footer">
		<div class="col-md-12">
			<div class="coppyright">
				This work is licensed under CC BY-SA<br>
				COPYRIGHT ASEAN CITATION INDEX 2014
			</div>
		</div>
	</div>
</div>