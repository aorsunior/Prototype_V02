<?php

class time_stamp
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
		if (isset($_REQUEST['sdate']))
		{
			$sdate = $_REQUEST['sdate'];
		}
		else
		{
			$sdate = date('Y-m-d');
		}
		?>
		<div class='container'>
			<div class='row'>
				<div class='col-12'>
				<form action="index.php" method="get">
				<input type='date' name='sdate' value="<?php echo $sdate;?>" />
				<input name="searcher" value="<?php echo $searcher;?>" />
				<input type="submit" value="Search" />
				<input type='button' value='Add' onclick='window.open("index.php?option=time_stamp&task=edit", "_self")' />
				<input type="hidden" name="option" value="time_stamp" />
				<input type="hidden" name="task" value="def" />
				</form>
				<table id='datatable' class='table table-bordered table-striped'>
					<thead>
						<tr>
							<th class='text-center'>
								No
							</th>
							<th class='text-center'>
								Users
							</th>
							<th class='text-center'>
								In
							</th>
							<th class='text-center'>
								Out
							</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$a = 1;
						$sql = "select concat(`employee_info`.`fname`, ' ', `employee_info`.`lname`) as `name`, group_concat(`timestamp`.`in-out`) as `check` from `employee_info` left join `timestamp` on `employee_info`.`id` = `timestamp`.`emp_id`";
						if ($searcher <> null) 
						{
							$sql = $sql." and `employee_info`.`fname` like '%".$searcher."%' ";
						}
						if ($sdate <> null) 
						{
							$sql = $sql." and date(`timestamp`.`in-out`) = '".$sdate."' ";
						}
						$sql = $sql." group by `employee_info`.`id`, date(`timestamp`.`in-out`)";
						$conn = new connect();
						$res = $conn->query($sql);
						while ($cdr = $res->fetch())
						{
							echo "<tr>";
							echo "<td>";
							echo $a;
							echo "</td>";
							echo "<td>";
							echo $cdr['name'];
							echo "</td>";
							$q = explode(',',$cdr['check']);
							sort($q);
							$in = $q[0];
							if (count($q) > 1) 
							{
								$ou = $q[count($q)-1];
							}
							else 
							{
								$ou = "-";
							}
							if ($in == null)
							{
								$in = '-';
							}
							echo "<td class='text-center'>";
							echo $in;
							echo "</td>";
							echo "<td class='text-center'>";
							echo $ou;
							echo "</td>";
							echo "</tr>";
							$a++;
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
		$head = "Add";
		?>
		<div class='container'>
			<div class='row'>
				<div class='col-12'>
				<form action='index.php' method='get'>
				<table class='table table-bordered table-striped'>
				<thead>
					<tr>
						<th class='text-center' colspan='2'>
							<?php echo $head;?> Data
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
									echo "<option value='".$cemp_id."'>".$fname." ".$lname."</option>";
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td class='text-center' colspan='2'>
							<input type='submit' value='Save' />
							<input type='button' value='Back' onclick='window.open("index.php?option=time_stamp&task=def","_self")' />
							<input type='hidden' name='option' value='time_stamp' />
							<input type='hidden' name='task' value='save' />
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
	
	function save()
	{
		$emp_id = $_REQUEST['emp_id'];
		$sql = "insert into `timestamp` set `emp_id` = '".$emp_id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		header("location:index.php?option=time_stamp&task=def");
	}
	
}

?>