<?php

class payroll
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
		if (isset($_REQUEST['syear'])) 
		{
			$syear = $_REQUEST['syear'];
		}
		else
		{
			$syear = date('Y');
		}
		?>
		<div class='container'>
			<div class='row'>
				<div class='col-12'>
					<form action="index.php" method="get">
						<input type="hidden" name="option" value="payroll" />
						<input type="hidden" name="task" value="def" />
						Name : <input name="searcher" value="<?php echo $searcher;?>" />
						Year : 
						<select name="syear">
						<?php
							$y =  date('Y') - 2;
							$limit = date('Y') + 2;
							while ($y <= $limit) 
							{
								if ($syear == $y) 
								{
									echo "<option value='".$y."' selected>".$y."</option>";
								}
								else 
								{
									echo "<option value='".$y."'>".$y."</option>";
								}
								$y++;
							}
						?>
						</select>
						<input type="submit" value="Search" />
						<input type='button' value='Add' onclick='window.open("index.php?option=payroll&task=edit&id=0", "_self")' />
					</form>
					<table id='datatable' class='table table-striped table-bordered'>
					<thead>
						<tr>
							<th class='text-center'>
								ID
							</th>
							<th class='text-center'>
								Name
							</th>
							<th class='text-center'>
								Year
							</th>
							<th class='text-center'>
								Salary
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
						$a = 0;
						$sql = "select *, (select concat(`fname`, ' ', `lname`) from `employee_info` where `employee_info`.`id` = `payroll`.`emp_id`) as `name` from `payroll` where `payroll`.`status` <> ''";
						if ($searcher <> null) 
						{
							$sql = $sql." and `name` like '%".$searcher."%' ";
						}
						if ($syear <> null) 
						{
							$sql = $sql." and `year` = '".$syear."' ";
						}
						$conn = new connect();
						$res = $conn->query($sql);
						while ($cdr = $res->fetch())
						{
							$a++;
							echo "<tr>";
							echo "<td>";
							echo  $a;
							echo "</td>";
							echo "<td>";
							echo  $cdr['name'];
							echo "</td>";
							echo "<td class='text-center'>";
							echo  $cdr['year'];
							echo "</td>";
							echo "<td>";
							echo  number_format($cdr['salary'],2);
							echo "</td>";
							echo "<td class='text-center'>";
							if ($cdr['status'] == 1)
							{
								echo "Active";
								$ds = "In-Active";
								$dss = "0";
							}
							else
							{
								echo "In-Active";
								$ds = "Active";
								$dss = "1";
							}
							echo "</td>";
							echo "<td class='text-center'>";
							echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=payroll&task=edit&id=".$cdr['id']."\", \"_self\")' />";
							echo "<input type='button' value='".$ds."' onclick='window.open(\"index.php?option=payroll&task=del&id=".$cdr['id']."&stat=".$dss."\", \"_self\")' />";
							echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=payroll&task=det&id=".$cdr['id']."\", \"_self\")' />";
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
		if ($id > 0) 
		{
			$head = "Edit";
			$sql = "select * from `payroll` where `id` = '".$id."'";
			$res = $conn->query($sql);
			while ($cdr = $res->fetch())
			{
				$emp_id = $cdr['emp_id'];
				$year = $cdr['year'];
				$salary = $cdr['salary'];
			}
		}
		else 
		{
			$head = "Add";
			$emp_id = 1;
			$year = date('Y');
			$salary = 0;
		}
		?>
		<div class='container'>
			<div class='row'>
				<div class='col-12'>
				<form action='index.php' method='get'>
				<table class='table table-bordered table-striped'>
				<thead>
					<tr>
						<th class='text-center' colspan='2'>
							<?php echo $head;?> Salary Data
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							Name
						</td>
						<td>
							<select name='emp_id'>
							<?php
								$sql = "select * from `employee_info` where `status` = '1'";
								$res = $conn->query($sql);
								while ($cdr = $res->fetch())
								{
									$cemp_id = $cdr['id'];
									$fname = $cdr['fname'];
									$lname = $cdr['lname'];
									if ($emp_id == $cemp_id) 
									{
										echo "<option value='".$cemp_id."' selected>".$fname." ".$lname."</option>";
									}
									else
									{
										echo "<option value='".$cemp_id."'>".$fname." ".$lname."</option>";
									}
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							Year
						</td>
						<td>
							<select name="year">
							<?php
								$y =  date('Y') - 2;
								$limit = date('Y') + 2;
								while ($y <= $limit) 
								{
									if ($year == $y) 
									{
										echo "<option value='".$y."' selected>".$y."</option>";
									}
									else 
									{
										echo "<option value='".$y."'>".$y."</option>";
									}
									$y++;
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							Value
						</td>
						<td>
							<input name='salary' value='<?php echo $salary;?>' />
						</td>
					</tr>
					<tr>
						<td class='text-center' colspan='2'>
							<input type='submit' value='Save' />
							<input type='button' value='Back' onclick='window.open("index.php?option=payroll&task=def","_self")' />
							<input type='hidden' name='option' value='payroll' />
							<input type='hidden' name='task' value='save' />
							<input type='hidden' name='id' value='<?php echo $id;?>' />
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
		$stat = $_REQUEST['stat'];
		$sql = "update `payroll` set `status` = '".$stat."' where `id` = '".$id."' ";
		$conn = new connect();
		$conn->query($sql);
		header("location:index.php?option=payroll&task=def");
	}
	
	function save()
	{
		$id = $_REQUEST['id'];
		$emp_id = $_REQUEST['emp_id'];
		$year = $_REQUEST['year'];
		$salary = $_REQUEST['salary'];
		if ($id <> 0) 
		{
			$sql = "update `payroll` set `emp_id` = '".$emp_id."', `year` = '".$year."', `salary` = '".$salary."' where `id` = '".$id."'";
		}
		else
		{
			$sql = "insert into `payroll` set `emp_id` = '".$emp_id."', `year` = '".$year."', `salary` = '".$salary."'";
		}
		$conn = new connect();
		$conn->query($sql);
		header("location:index.php?option=payroll&task=def");		
	}
	
	function det()
	{
		$id = $_REQUEST['id'];
		$sql = "select *, (select concat(`fname`, ' ', `lname`) from `employee_info` where `employee_info`.`id` = `payroll`.`emp_id`) as `name` from `payroll` where `id` = '".$id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch())
		{
			$name = $cdr['name'];
			$year = $cdr['year'];
			$salary = $cdr['salary'];
		}
		?>
		<div class='container'>
			<div class='row'>
				<div class='col-12'>
				<table class='table table-bordered table-striped'>
				<thead>
					<tr>
						<th class='text-center' colspan='2'>
							Salary Data
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
							Year
						</td>
						<td>
							<?php echo $year;?>
						</td>
					</tr>
					<tr>
						<td>
							Salary
						</td>
						<td>
							<?php echo number_format($salary,2);?>
						</td>
					</tr>
					<tr>
						<td class='text-center' colspan='2'>
							<input type='button' value='Back' onclick='window.open("index.php?option=payroll&task=def","_self")' />
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