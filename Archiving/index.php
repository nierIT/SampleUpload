<!DOCTYPE html>
<html>
  <head>
    <title>File Catalog</title>
	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<style type='text/css'>
		@font-face { 
			font-family: Android; 
			src: url('fonts/androidnation.ttf'); 
		} 
	</style>
  </head>
  <body style='background:url(img/bg.jpg); overflow:hidden;'>
  <form action="index.php" method="post" enctype="multipart/form-data" id='frm1'>
  <?php
	include("db_connect.php");
	error_reporting(0);
  ?>
  
	<br/><br/>
<!--ADD FILE MODAL-->
			 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					  <h4 class="modal-title">Add File</h4>
					</div>
					<div class="modal-body">
					  <table>
						<tr>
							<td>Title</td>
							<td><input type='text' name='title'/></td>
						</tr>	
						<tr>
							<td>Author</td>
							<td><input type='text' name='author'/></td>
						</tr>
						<tr>
							<td>Date</td>
							<td><input type='date' name='date'/></td>
						</tr>
						<tr>
							<td>Tag to</td>
							<td>
								<select name='tag'>
									<?php 
										$q=mysql_query("SELECT * FROM tag_category");
										while($getq=mysql_fetch_array($q)){
									?>
										<option value='<?php echo $getq['tagIn_Id'];?>'><?php echo $getq['description'];?></option>
									<?php 
										}
									?>
								</select>
							</td>
						</tr>
						
						<tr>
							<td colspan='2'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan='2'>Upload File:</td>
						</tr>
						<tr>
							<td>PDF/Doc File</td>
							<td><input type='file' name='pdf'  id="file"/></td>
						</tr>
						<tr>
							<td>Text File</td>
							<td><input type='file' name='txt'  id="txt"/></td>
						</tr>
					
					  </table>
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  <input type="submit" class="btn btn-primary" name='save' value='Save'>
					</div>
				  </div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			  </div><!-- /.modal -->
		  <?php 
			if(isset($_POST['save'])){
				$title=$_POST['title'];
				$author=$_POST['author'];
				$date=$_POST['date'];
				$tag=$_POST['tag'];

				if($title!=""&&$author!=""&&$date!=""&&$_FILES["pdf"]["error"]<=0&&$_FILES["txt"]["error"]<=0){
						$allowedExts = array("pdf", "doc", "docx");
						$temp = explode(".", $_FILES["pdf"]["name"]);
						$extension = end($temp);
						if (($_FILES["pdf"]["type"] == "application/pdf") || ($_FILES["pdf"]["type"] == "application/msword") || ($_FILES["pdf"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") 
						&& ($_FILES["pdf"]["size"] < 20000000) && in_array($extension, $allowedExts)){
							
							 if ($_FILES["pdf"]["error"] > 0){
								echo "Error: " . $_FILES["pdf"]["error"] . "<br>";
							}else{
								if (file_exists("doc/" . $_FILES["pdf"]["name"])){
								  echo $_FILES["pdf"]["name"] . " already exists. ";
								}else{
								  move_uploaded_file($_FILES["pdf"]["tmp_name"],
								  "doc/" . $_FILES["pdf"]["name"]);
								}
							}
						}
						
						$allowedExts = array("txt");
						$temp = explode(".", $_FILES["txt"]["name"]);
						$extension = end($temp);
						if ($_FILES['txt']['type'] == 'text/plain' && ($_FILES["txt"]["size"] < 20000000) && in_array($extension, $allowedExts)){
							
							 if ($_FILES["txt"]["error"] > 0){
								echo "Error: " . $_FILES["txt"]["error"] . "<br>";
							}else{
								if (file_exists("txt/" . $_FILES["txt"]["name"])){
								  echo $_FILES["txt"]["name"] . " already exists. ";
								}else{
								  move_uploaded_file($_FILES["txt"]["tmp_name"],
								  "txt/" . $_FILES["txt"]["name"]);
								}
							}
						}
						
						$insert=mysql_query("INSERT INTO file_info(title,author,date,tagIn_fk) VALUES ('$title','$author','$date','$tag')");
						$last=mysql_insert_id();
						$name=$_FILES['pdf']['name'];
						$name2=$_FILES['txt']['name'];
						$insert=mysql_query("INSERT INTO docetc_format(filename,file_fk) VALUES ('$name','$last')");
						$insert=mysql_query("INSERT INTO text_format(filename,file_fk) VALUES ('$name2','$last')");
						
					}else{
						echo "<script>
							alert('Please Check your Inputs');
						</script>";
					}
			}
					
		  ?>
<!-- END OF ADD FILE MODAL-->

<!--ADD DEPARTMENT MODAL-->
			 <div class="modal fade" id="myModall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					  <h4 class="modal-title">Add Category</h4>
					</div>
					<div class="modal-body">
					  <table>
						<tr>
							<td>Category Name: </td>
							<td><input type='text' name='departmentName'/></td>
						</tr>	
					  </table>
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  <input type="submit" class="btn btn-primary" name='save2' value='Save'>
					</div>
				  </div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			  </div><!-- /.modal -->
<!--END OF ADD DEPARTMENT-->

<?php
	if(isset($_POST['save2'])){
		$departmentName=$_POST['departmentName'];
		
		if($departmentName!="")
			$insert=mysql_query("INSERT INTO tag_dept(description) VALUES('$departmentName')");
		else
			echo "<script>
				alert('Please Check your Input');
			</script>";
	}
?>
   <div class="row">
	  <div class="col-md-12">
		  <div class="col-md-12" style='background:#303030;border-top:3px solid yellow;border-bottom:3px solid yellow;margin-top:-20px;height:100px;'>
			<div class="col-md-5" style='font-family:Android; color:#33FF33; font-size:18px; margin-top:15px;'><img src='img/catalog.png' width='50px;'/> Document Management System</div>
			<div class="col-md-3" ></div>
			<div class="col-md-4" style='color:white; font-family:Arial; font-size:20px; margin-top:50px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="date_time"></span></div>
		  </div>
		  <br/>
		  <div class="col-md-4">
					<div class="panel-group" id="accordion">
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
							 <span class="glyphicon glyphicon-folder-open"></span>&nbsp; Documents
							</a>
						  </h4>
						</div>
					<?php
						if(isset($_GET['active'])==1||isset($_POST['search'])){
					?>
						<div id="collapseOne" class="panel-collapse collapse in">
					<?php 
						}else{
					?>
						<div id="collapseOne" class="panel-collapse collapse">
					<?php
						}
					?>
						  <div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
								<li><a href="index.php?active=1">Search</a></li>
								<li><a data-toggle="modal" href="#myModal">Add File</a></li>
							</ul>
								
						  </div>
						</div>
					  </div>
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
							 <span class="glyphicon glyphicon-folder-open"></span>&nbsp; CMS
							</a>
						  </h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse">
						  <div class="panel-body">
								<ul class="nav nav-pills nav-stacked">
								  <li><a data-toggle="modal" href="#myModall">Add Category</a></li>
								</ul>
						  </div>
						 </div>
						</div>
					</div>
			</div>
		  <div class="col-md-4">
		  <script type="text/javascript">
			checked=false;
			function checkedAll (frm1) {
				var aa= document.getElementById('frm1');
				 if (checked == false)
					  {
					   checked = true
					  }
					else
					  {
					  checked = false
					  }
				for (var i =0; i < aa.elements.length; i++) 
				{
				 aa.elements[i].checked = checked;
				}
				  }
			</script>
				<?php
					if(isset($_GET['active'])==1){
				?>
						<div class="panel panel-default">
						  <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Categories</div>
						  <div class="panel-body">
							<table>
							<tr>
								<td><input type='checkbox' name='checkall' onclick='checkedAll(frm1);'></td>
								<td>Select All</td>
							</tr>
								<?php
										$i=1;
										if(isset($_GET['pick']))
											$pick=$_GET['pick'];
										else
											$pick=0;
										$q=mysql_query("SELECT * FROM tag_category");
										while($getq=mysql_fetch_array($q)){
								?>	
										<tr>
											<td><input type='checkbox' name='dept[]' value='<?php echo $getq['tagIn_Id'];?>'/></td>
											<td><?php echo $getq['description'];?></td>
										</tr>
								<?php
										}
								?>
							</table>
						  </div>
						</div>
								
					<?php
					}
					?>
		  </div>
		  <div class="col-md-4">
		  <?php
			if(isset($_GET['active'])==1){
			?>
					<div class="panel panel-default">
					  <div class="panel-heading"> <span class="glyphicon glyphicon-search"></span> Search</div>
					  <div class="panel-body">
						<table>
							<tr>
								<td>Full Text Search: </td>
								<td><input type='text' name='full'/></td>
							</tr>
							<tr>
								<td>Title: </td>
								<td><input type='text' name='title'/></td>
							</tr>
							<tr>
								<td>Author: </td>
								<td><input type='text' name='author'/></td>
							</tr>
							<tr>
								<td>Date: </td>
								<td><input type='date' name='date'/></td>
							</tr>
							<tr>
								<td colspan='2'><input type='submit' name='search' value='Submit' class="btn btn-primary"/></td>
							</tr>
						</table>
					  </div>
					</div>
						
			<?php
			}
			?>
		  </div>
		  
		  <?php
			if(isset($_POST['search'])){
				$full=$_POST['full'];
				$title=$_POST['title'];
				$author=$_POST['author'];
				$date=$_POST['date'];
				$dept=$_POST['dept'];
				$where=" ";
				$ok="false";
				
					if($full!=""){
						$ok="true";
					}
					if($title!=""){
						$where = $where." AND fi.title LIKE '%$title%'";
						$ok="true";
					}
					if($author!=""){
						$where = $where." AND fi.author LIKE '%$author%'";
						$ok="true";
					}
					if($date!=""){
						$where = $where." AND fi.date LIKE '%$date%'";
						$ok="true";
					}
				if((count($dept)==0) || $ok=="false"){
					echo "<script>
						alert('Please Check your Inputs');
						  window.location.href='index.php?active=1';
					</script>";
				}else{
			?>
					 <div class="col-md-8">
							<div class="panel panel-default">
							  <div class="panel-heading"> <span class="glyphicon glyphicon-list-alt"></span> Results</div>
							  <div class="panel-body">
							<?php
								if($full==""){  
							?>
								<ul class="nav nav-tabs" id="myTab">
								  <li class="active"> <a href="#home" data-toggle="tab">List of Documents</a></li>
								  <li><a href="#profile" data-toggle="tab">Others</a></li>

								</ul>
								 
								<div class="tab-content">
								  <div class="tab-pane active" id="home">
									<table class="table">
										<tr>
											<th>TITLE</th>
											<th>AUTHOR</th>
											<th>DATE</th>
										</tr>
										<?php
										$x=0;
										$i=0;
										while($i!=count($dept)){
											$q=mysql_query("SELECT * FROM file_info fi LEFT JOIN docetc_format df ON fi.file_Id = df.file_fk WHERE fi.tagIn_fk='$dept[$i]' $where");
											if(mysql_num_rows($q)>0){
												while($getq=mysql_fetch_array($q)){
										?>
													<tr>
														<td><a href='download.php?download_file=<?php echo $getq['filename']; ?>'><?php echo $getq['title'];?></doc></td>
														<td><?php echo $getq['author'];?></td>
														<td><?php echo $getq['date'];?></td>
													</tr>
										<?php
													$x++;
												}
											}
											$i++;
										}
										?>	
									</table>	
								<?php		
									if($x==0){
								?>
									<div class="alert alert-danger">No Results Found</div>
								<?php
									}
								?>
								  </div>
								  <div class="tab-pane" id="profile">Others</div>
								</div>
								 <?php 
									}else{
								 ?>
								 
									<ul class="nav nav-tabs" id="myTab">
								  <li class="active"> <a href="#home" data-toggle="tab">List of Documents</a></li>
								 

								</ul>
								 
								<div class="tab-content">
								  <div class="tab-pane active" id="home">

									<table class="table">
										<tr>
											<th>TITLE</th>
											<th>AUTHOR</th>
											<th>DATE</th>
										</tr>
								<?php			
									$x=0;
									$i=0;
									while($i!=count($dept)){
										$q=mysql_query("SELECT *,df.filename as docname,tf.filename as txtname FROM text_format tf INNER JOIN file_info fi ON tf.file_fk = fi.file_Id LEFT JOIN docetc_format df ON df.file_fk = fi.file_id WHERE fi.tagIn_fk = $dept[$i]");
										
										while($getq=mysql_fetch_array($q)){
										
											$find="txt/$getq[txtname]";
											
											$text = file_get_contents($find);
											
											$str = strtolower($text);
											
											$lines = explode(" ", $str);

											$full=strtolower($full);
											if (preg_match('/\s/',$full)){
											
												$enter = explode(" ", $full);
												$y=0;
												$validate="false";
												
												while($y!=count($enter)){
													if(in_array($enter[$y], $lines)){
														$validate="true";
														$y++;
													}else{
														$y++;
														$validate="false";
														break;
													}
													
												}
												
												if($validate=="true"){
													echo "<tr>";
														echo "<td><a href='download.php?download_file=$getq[docname]'>$getq[title]</a></td>";
														echo "<td>$getq[author]</td>";
														echo "<td>$getq[date]</td>";
													echo "</tr>";
													$validate="false";
													$x++;
												}
												
											}else{
												if(in_array($full, $lines)){
													echo "<tr>";
														echo "<td><a href='download.php?download_file=$getq[docname]'>$getq[title]</a></td>";
														echo "<td>$getq[author]</td>";
														echo "<td>$getq[date]</td>";
													echo "</tr>";
													$x++;
												}
											}
										
										}
										$i++;
									}
					
								?>
										</table>
										
								<?php		
									if($x==0){
									//if($x==0){
								?>
									<div class="alert alert-danger">No Results Found	</div>
								<?php
									}
								?>
								  </div>
								  
								</div>
								<?php
									}
								?>
							  </div>
							</div>
					 </div>
		  <?php
				}
			}
		  ?>
		</div>
		</div>
	</div>
   </form>

    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	
	 	<script>
		function date_time(id)
{
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
	
        result = ''+days[day]+' / '+months[month]+' '+d+', '+year+' / '+h+':'+m+':'+s;
        document.getElementById(id).innerHTML = result;
        setTimeout('date_time("'+id+'");','1000');
        return true;
}
	</script>
	 <script type="text/javascript">window.onload = date_time('date_time');</script>
  </body>
</html>