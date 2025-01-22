<?php

class employee
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
				<input type="button" value="Add" onclick='window.open("index.php?option=employee&task=edit&id=0","_self")' />
				<input type="button" value="PayRoll" onclick='window.open("index.php?option=employee&task=payroll","_self")' />
				<input type="hidden" name="option" value="employee" />
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
								Address
							</th>
							<th class='text-center'>
								Contact
							</th>
							<th class='text-center'>
								Working
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
						$sql = "select * from `employee_info`";
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
							echo "Name : ";
							echo $cdr['fname'];
							echo "<br />";
							echo "Suname : ";
							echo $cdr['lname'];
							echo "<br />";
							echo "Nickname : ";
							echo $cdr['nickname'];
							echo "</td>";
							echo "<td>";
							echo "Birth : ";
							echo $cdr['birth'];
							echo "<br />";
							echo "Sex : ";
							if ($cdr['sex'] == 0) 

							{
								echo "Female";
							}
							else
							{
								echo "Male";
							}
							echo "</td>";
							echo "<td>";
							echo $cdr['address'];
							echo "</td>";
							echo "<td>";
							echo "Tel. : ";
							echo $cdr['tel'];
							echo "<br />";
							echo "Mail : ";
							echo $cdr['mail'];
							echo "</td>";
							echo "<td>";
							echo "Position : ";
							echo $cdr['depart'];
							echo "<br />";
							echo "Start : ";
							echo $cdr['start'];
							echo "</td>";
							echo "<td class='text-center'>";
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
							echo "<td class='text-center'>";
							echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=employee&task=edit&id=".$cdr['id']."\",\"_self\")' />";
							echo "<input type='button' value='".$ds."' onclick='window.open(\"index.php?option=employee&task=del&id=".$cdr['id']."&stat=".$dss."\",\"_self\")' />";
							echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=employee&task=det&id=".$cdr['id']."\",\"_self\")' />";
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
		$id = $_REQUEST['id'];
		if ($id == 0) 
		{
			$fname = "";
			$lname = "";
			$nickname = "";
			$birth = "";
			$sex = 0;
			$address = "";
			$tel = "";
			$mail = "";
			$depart = "";
			$start = "";
		}
		else 
		{
			$sql = "select * from `employee_info` where `id` = '".$id."'";
			$conn = new connect();
			$res = $conn->query($sql);
			while ($cdr = $res->fetch()) 
			{
				$fname = $cdr['fname'];
				$lname = $cdr['lname'];
				$nickname = $cdr['nickname'];
				$birth = $cdr['birth'];
				$sex = $cdr['sex'];
				$address = $cdr['address'];
				$tel = $cdr['tel'];
				$mail = $cdr['mail'];
				$depart = $cdr['depart'];
				$start = $cdr['start'];
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
									<input name='fname' value='<?php echo $fname;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Surname
								</td>
								<td>
									<input name='lname' value='<?php echo $lname;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Nickname
								</td>
								<td>
									<input name='nickname' value='<?php echo $nickname;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Birth
								</td>
								<td>
									<input type='date' name='birth' value='<?php echo $birth;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Sex
								</td>
								<td>
									<?php
										if ($sex == "0") 
										{
											echo "<input type='radio' name='sex' value='0' checked /> Female";
											echo "<br />";
											echo "<input type='radio' name='sex' value='1' /> Male";
										}
										else
										{
											echo "<input type='radio' name='sex' value='0' /> Female";
											echo "<br />";
											echo "<input type='radio' name='sex' value='1' checked /> Male";
										}
									?>
								</td>
							</tr>
							<tr>
								<td>
									Address
								</td>
								<td>
									<input name='address' value='<?php echo $address;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Tel
								</td>
								<td>
									<input name='tel' value='<?php echo $tel;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Mail
								</td>
								<td>
									<input name='mail' value='<?php echo $mail;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Position
								</td>
								<td>
									<input name='depart' value='<?php echo $depart;?>' />
								</td>
							</tr>
							<tr>
								<td>
									Start
								</td>
								<td>
									<input type='date' name='start' value='<?php echo $start;?>' />
								</td>
							</tr>
							<tr>
								<td colspan='2' class='text-center'>
									<input type='hidden' name="option" value='employee' />
									<input type='hidden' name="task" value='save' />
									<input type='hidden' name="id" value='<?php echo $id;?>' />
									<input type='submit' value='Save' />
									<input type='button' value='Back' onclick='window.open("index.php?option=employee&task=def","_self")' />
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
		$sql = "update `employee_info` set `status` = '".$_REQUEST['stat']."' where `id` = '".$id."'";
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=employee&task=def');
	}
	
	function save()
	{
		$id = $_REQUEST['id'];
		$fname = $_REQUEST['fname'];
		$lname = $_REQUEST['lname'];
		$nickname = $_REQUEST['nickname'];
		$birth = $_REQUEST['birth'];
		$sex = $_REQUEST['sex'];
		$address = $_REQUEST['address'];
		$tel = $_REQUEST['tel'];
		$mail = $_REQUEST['mail'];
		$depart = $_REQUEST['depart'];
		$start = $_REQUEST['start'];
		if ($id == 0) 
		{
			$sql = "insert into `employee_info` set `fname` = '".$fname."', `lname` = '".$lname."', `nickname` = '".$nickname."', `birth` = '".$birth."', `sex` = '".$sex."', `address` = '".$address."', `tel` = '".$tel."', `mail` = '".$mail."', `depart` = '".$depart."', `start` = '".$start."'";
		}
		else 
		{
			$sql = "update `employee_info` set `fname` = '".$fname."', `lname` = '".$lname."', `nickname` = '".$nickname."', `birth` = '".$birth."', `sex` = '".$sex."', `address` = '".$address."', `tel` = '".$tel."', `mail` = '".$mail."', `depart` = '".$depart."', `start` = '".$start."' where `id` = '".$id."'";
		}
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=employee&task=def');
	}
	
	function det()
	{
		$id = $_REQUEST['id'];
		$sql = "select * from `employee_info` where `id` = '".$id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$fname = $cdr['fname'];
			$lname = $cdr['lname'];
			$nickname = $cdr['nickname'];
			$birth = $cdr['birth'];
			$sex = $cdr['sex'];
			$address = $cdr['address'];
			$tel = $cdr['tel'];
			$mail = $cdr['mail'];
			$depart = $cdr['depart'];
			$start = $cdr['start'];
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
									<?php echo $fname;?>
								</td>
							</tr>
							<tr>
								<td>
									Surname
								</td>
								<td>
									<?php echo $lname;?>
								</td>
							</tr>
							<tr>
								<td>
									Nickname
								</td>
								<td>
									<?php echo $nickname;?>
								</td>
							</tr>
							<tr>
								<td>
									Birth
								</td>
								<td>
									<?php echo $birth;?>
								</td>
							</tr>
							<tr>
								<td>
									Sex
								</td>
								<td>
									<?php 
										if ($sex == "0") 
										{
											echo "Female";
										}
										else
										{
											echo "Male";
										}
									?>
								</td>
							</tr>
							<tr>
								<td>
									Address
								</td>
								<td>
									<?php echo $address;?>
								</td>
							</tr>
							<tr>
								<td>
									Tel.
								</td>
								<td>
									<?php echo $tel;?>
								</td>
							</tr>
							<tr>
								<td>
									Mail
								</td>
								<td>
									<?php echo $mail;?>
								</td>
							</tr>
							<tr>
								<td>
									Position
								</td>
								<td>
									<?php echo $depart;?>
								</td>
							</tr>
							<tr>
								<td>
									Start
								</td>
								<td>
									<?php echo $start;?>
								</td>
							</tr>
							<tr>
								<td colspan='2' class='text-center'>
									<input type='button' value='Back' onclick='window.open("index.php?option=employee&task=def","_self")' />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}

	function payroll()
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
				Month
				<select name='month'>
				<?php
					$a = 0;
					while ($a < 12)
					{
						$a++;
						if ($a == date('m'))
						{
							echo "<option value='".$a."' selected>".$a."</a>";
						}
						else
						{
							echo "<option value='".$a."'>".$a."</a>";
						}
					}
				?>
				</select>
				<input type="submit" value="Payment" />
				<input type="hidden" name="option" value="employee" />
				<input type="hidden" name="task" value="save_payroll" />
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
								Leave
							</th>
							<th class='text-center'>
								Check
							</th>
							<th class='text-center'>
								Salary
							</th>
							<th class='text-center'>
								Paid
							</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$sql = "select `employee_info`.`id` as `id`, concat(`employee_info`.`fname`, ' ', `employee_info`.`lname`) as `name`, `payroll`.`salary` as `value` from `employee_info`, `payroll` where `payroll`.`uid` = `employee_info`.`id`";
						if ($searcher <> null) 
						{
							$sql = $sql." `name` like '%".$searcher."%' ";
						}
						$conn = new connect();
						$res = $conn->query($sql);
						$a = 0;
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
							echo $cdr['name'];
							echo "</td>";
							echo "<td>";
							echo $cdr['name'];
							echo "</td>";
							echo "<td>";
							echo number_format($cdr['value'],2);
							echo "</td>";
							echo "<td>";
							echo "<input name='v-".$a."' value='".$cdr['value']."' />";
							echo "<input type='hidden' name='d-".$a."' value='".$cdr['id']."' />";
							echo "</td>";
							echo "</tr>";
							$a++;
						}
					?>
					</tbody>
				</table>
				<input type='hidden' name='limit' value='<?php echo $a;?>' />
				</form>
				</div>
			</div>
		</div>
		<?php
	}

	function save_payroll()
	{
        $month = $_REQUEST['month'];
        $limit = $_REQUEST['limit'];
        $conn = new connect();
		$sql = "insert into acc set typ = '4', action = 'Payroll', date = '".date('Y-m-d')."', detail = 'Payroll Month#".$month."', `uid` = '".$_SESSION['uid']."'";
		$acc_id = $conn->query_lastid($sql);
		$a = 0;
		$tt = 0;
		while ($a < $limit) 
		{
			$value = $_REQUEST['v-'.$a];
			$det = $_REQUEST['d-'.$a];
			$sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '12', `typ` = '1', `detail` = 'Salary Employee ID#".$det."', `value` = '".$value."'";
			$res = $conn->query($sql);
			$a++;
			$tt = $tt + $value;
		}
		$sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '10', `typ` = '2', `detail` = 'Payroll Month#".$month."', `value` = '".$tt."'";
		$res = $conn->query($sql);

		$detail = "Reference From Payroll Month#".$month;
		$value = $tt;
		$sql = "insert into `payment` set `supplier_id` = '0', `date` = '".date('Y-m-d')."', `detail` = '".$detail."', `value` = '".$value."', `user_id` = '".$_SESSION['uid']."', `ref_id` = '".$month."', `typ` ='2'";
		$conn->query($sql);

        $conn->save_logs("Payroll Month#".$month, $_SESSION['uid']);
		header("location:index.php?option=employee&task=payroll");
	}
	
}

?>