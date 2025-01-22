<?php

class leave_management
{
	
	function def()
	{
	if (isset($_REQUEST['searcher']))
	{
		$searcher = $_REQUEST['searcher'];
	}
	else
	{
		$searcher = null;
	}
	?>
	<div class='container'>
		<div class='row'>
			<div class='col-12'>
			<form action="index.php" method="get">
			<input name="searcher" value="<?php echo $searcher;?>" />
			<input type="submit" value="Search" />
			<input type="button" value="Add" onclick='window.open("index.php?option=leave_management&task=edit&id=0","_self")' />
			<input type="hidden" name="option" value="leave_management" />
			<input type="hidden" name="task" value="def" />
			</form>
			<table id='datatable' class='table table-bordered table-striped'>
				<thead>
					<tr>
						<th class='text-center'>
							Id
						</th>
						<th class='text-center'>
							Name
						</th>
						<th class='text-center'>
							Detail
						</th>
						<th class='text-center'>
							Date
						</th>
						<th class='text-center'>
							Status
						</th>
						<th class='text-center'>
							Action
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sql = "select *, (select concat(`fname`, ' ', `lname`) as `name` from `employee_info` where `employee_info`.`id` = `leave_management`.`uid`) as `name` from `leave_management`";
					if ($searcher <> null) 
					{
						$sql = $sql." where `name` like '%".$searcher."%' ";
					}
					$conn = new connect();
					$res = $conn->query($sql);
					while ($cdr = $res->fetch())
					{
						echo "<tr>";
						echo "<td>";
						echo $cdr['id'];
						echo "</td>";
						echo "<td>";
						echo $cdr['name'];
						echo "</td>";
						echo "<td>";
						echo "Purpose : ";
						if ($cdr['purpose'] == 1) 
						{
							echo "ลาป่วย";
						}
						elseif ($cdr['purpose'] == 2) 
						{
							echo "ลากิจ";
						}
						elseif ($cdr['purpose'] == 3) 
						{
							echo "ลาพักร้อน";
						}
						echo "<br />";
						echo "Type : ";
						if ($cdr['typ'] == 1) 
						{
							echo "เต็มวัน";
						}
						elseif ($cdr['typ'] == 2) 
						{
							echo "ครึ่งวัน";
						}
						echo "<br />";
						echo "Reason : ";
						echo $cdr['reason'];
						echo "</td>";
						echo "<td>";
						if ($cdr['leave_start'] == $cdr['leave_end']) 
						{
							echo $cdr['leave_start'];
						}
						else 
						{
							echo $cdr['leave_start'];
							echo " to ";
							echo $cdr['leave_end'];
						}
						echo "</td>";
						echo "<td>";
						if ($cdr['status'] == 1) 
						{
							echo "Active";
							$ds = "In-Active";
							$dss = '0';
						}
						else
						{
							echo "In-Active";
							$ds = "Active";
							$dss = '1';
						}
						echo "</td>";
						echo "<td>";
						echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=leave_management&task=edit&id=".$cdr['id']."\",\"_self\")' />";
						echo "<input type='button' value='".$ds."' onclick='window.open(\"index.php?option=leave_management&task=del&id=".$cdr['id']."&stat=".$dss."\",\"_self\")' />";
						echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=leave_management&task=det&id=".$cdr['id']."\",\"_self\")' />";
						echo "</td>";
						echo "</tr>";
					}
				?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
	<?php
	}
	
	function edit()
	{
		$conn = new connect();
		$id = $_REQUEST['id'];
		if ($id == 0) 
		{
			$uid = 0;
			$purpose = 1;
			$reason = "";
			$leave_start = date('Y-m-d');
			$leave_end = date('Y-m-d');
			$typ= 1;
		}
		else 
		{
			$sql = "select * from `leave_management` where `id` = '".$id."'";
			$res = $conn->query($sql);
			while ($cdr = $res->fetch()) 
			{
				$uid = $cdr['uid'];
				$purpose = $cdr['purpose'];
				$reason = $cdr['reason'];
				$leave_start = $cdr['leave_start'];
				$leave_end = $cdr['leave_end'];
				$typ= $cdr['typ'];
			}
		}
		?>
		<div class='container'>
			<div class='row'>
				<div class='col-12'>
					<form action='index.php' method='get'>
					<table class='table'>
						<thead>
							<tr>
								<th colspan='2' class='text-center'>
									Edit Data
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									Name
								</td>
								<td>
									<select name='uid'>
									<?php
										$sql = "select * from `employee_info` where `status` = '1'";
										$res = $conn->query($sql);
										while ($cdr = $res->fetch())
										{
											$cuid = $cdr['id'];
											$fname = $cdr['fname'];
											$lname = $cdr['lname'];
											if ($uid == $cuid) 
											{
												echo "<option value='".$cuid."' selected>".$fname." ".$lname."</option>";
											}
											else
											{
												echo "<option value='".$cuid."'>".$fname." ".$lname."</option>";
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									Purpose
								</td>
								<td>
								<?php
									if ($purpose == 1) 
									{
										echo "<input name='purpose' type='radio' value='1' checked /> ลาป่วย";
										echo "<br />";
										echo "<input name='purpose' type='radio' value='2' /> ลากิจ";
										echo "<br />";
										echo "<input name='purpose' type='radio' value='3' /> ลาพักร้อน";
									}
									elseif ($purpose == 2) 
									{
										echo "<input name='purpose' type='radio' value='1' /> ลาป่วย";
										echo "<br />";
										echo "<input name='purpose' type='radio' value='2' checked /> ลากิจ";
										echo "<br />";
										echo "<input name='purpose' type='radio' value='3' /> ลาพักร้อน";
									}
									elseif ($purpose == 3) 
									{
										echo "<input name='purpose' type='radio' value='1' /> ลาป่วย";
										echo "<br />";
										echo "<input name='purpose' type='radio' value='2' /> ลากิจ";
										echo "<br />";
										echo "<input name='purpose' type='radio' value='3' checked /> ลาพักร้อน";
									}
								?>
								</td>
							</tr>
							<tr>
								<td>
									Reason
								</td>
								<td>
									<input name='reason' value='<?php echo $reason;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Leave Start
								</td>
								<td>
									<input type='date' name='leave_start' value='<?php echo $leave_start;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Leave End
								</td>
								<td>
									<input type='date' name='leave_end' value='<?php echo $leave_end;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Type
								</td>
								<td>
								<?php
									if ($typ == 1) 
									{
										echo "<input name='typ' type='radio' value='1' checked /> เต็มวัน";
										echo "<br />";
										echo "<input name='typ' type='radio' value='2' /> ครึ่งวัน";
									}
									elseif ($typ == 2) 
									{
										echo "<input name='typ' type='radio' value='1' /> เต็มวัน";
										echo "<br />";
										echo "<input name='typ' type='radio' value='2' checked /> ครึ่งวัน";
									}
								?>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='text-center'>
									<input type='hidden' name="option" value='leave_management' />
									<input type='hidden' name="task" value='save' />
									<input type='hidden' name="id" value='<?php echo $id;?>' />
									<input type='submit' value='Save' />
									<input type='button' value='Back' onclick='window.open("index.php?option=leave_management&task=def","_self")' />
								</td>
							</tr>
						</tbody>
					</table>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
	
	function del()
	{
		$id = $_REQUEST['id'];
		$sql = "update `leave_management` set `status` = '".$_REQUEST['stat']."' where `id` = '".$id."'";
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=leave_management&task=def');
	}
	
	function save()
	{
		$id = $_REQUEST['id'];
		$uid = $_REQUEST['uid'];
		$purpose = $_REQUEST['purpose'];
		$reason = $_REQUEST['reason'];
		$leave_start = $_REQUEST['leave_start'];
		$leave_end = $_REQUEST['leave_end'];
		$typ= $_REQUEST['typ'];
		if ($id == 0) 
		{
			$sql = "insert into `leave_management` set `uid` = '".$uid."', `purpose` = '".$purpose."', `reason` = '".$reason."', `leave_start` = '".$leave_start."', `leave_end` = '".$leave_end."', `typ` = '".$typ."'";
		}
		else 
		{
			$sql = "update `leave_management` set `uid` = '".$uid."', `purpose` = '".$purpose."', `reason` = '".$reason."', `leave_start` = '".$leave_start."', `leave_end` = '".$leave_end."', `typ` = '".$typ."' where `id` = '".$id."'";
		}
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=leave_management&task=def');
	}
	
	function det()
	{
		$id = $_REQUEST['id'];
		$sql = "select *, (select concat(`fname`, ' ', `lname`) as `name` from `employee_info` where `employee_info`.`id` = `leave_management`.`emp_id`) as `name` from `leave_management` where `id` = '".$id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$name = $cdr['name'];
			$purpose = $cdr['purpose'];
			$reason = $cdr['reason'];
			$leave_start = $cdr['leave_start'];
			$leave_end = $cdr['leave_end'];
			$typ= $cdr['typ'];
		}
		?>
		<div class='container'>
			<div class='row'>
				<div class='col-12'>
					<table class='table'>
						<thead>
							<tr>
								<th colspan='2' class='text-center'>
									User Data
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									Name
								</td>
								<td>
									<?php echo $name;?>
								</td>
							</tr>
							<tr>
								<td>
									Purpose
								</td>
								<td>
									<?php 
										if ($purpose == 1) 
										{
											echo "ลาป่วย";
										}
										elseif ($purpose == 2) 
										{
											echo "ลากิจ";
										}
										elseif ($purpose == 3) 
										{
											echo "ลาพักร้อน";
										}
									;?>
								</td>
							</tr>
							<tr>
								<td>
									Reason
								</td>
								<td>
									<?php echo $reason;?>
								</td>
							</tr>
							<tr>
								<td>
									Date
								</td>
								<td>
									<?php
										if ($leave_start == $leave_end) 
										{
											echo $leave_start;
										}
										else 
										{
											echo $leave_start;
											echo " to ";
											echo $leave_end;
										}
									?>
								</td>
							</tr>
							<tr>
								<td>
									Type
								</td>
								<td>
									<?php
										if ($typ == 1) 
										{
											echo "เต็มวัน";
										}
										elseif ($typ == 2) 
										{
											echo "ครึ่งวัน";
										}
									?>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='text-center'>
									<input type='button' value='Back' onclick='window.open("index.php?option=leave_management&task=def","_self")' />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}
				
}

?>