<?php

class acc
{
    function def() 
    {
        $conn = new connect();
		$acl = $conn->check_acl();
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
				<h2>Accounting</h2>
                <form action='index.php' method='get'>
                <input name='searcher' value='<?php echo $searcher;?>'>
                <input type='submit' value='Search'>
				<?php
					if (($acl == '2') or ($acl > '5'))
					{
					?>
                        <input type='button' value='Add' onclick='window.open("index.php?option=acc&task=edit&id=0","_self")'>
					<?php
					}
					if ($acl > '3')
					{
					?>
                        <!-- <input type='button' value='Summary' onclick='window.open("index.php?option=acc&taskprofitk=sum&id=0","_self")'> -->
                        <input type='button' value='profit' onclick='window.open("index.php?option=acc&task=sum&id=0","_self")'>
                        <input type='button' value='loss' onclick='window.open("index.php?option=acc&task=loss&id=0","_self")'>
                        <input type='button' value='Print' onclick='window.open("print.php?cat=acc&typ=all","_self")'>
					<?php
					}
				?>
                <input type='hidden' name='option' value='acc'>
                <input type='hidden' name='task' value='def'>
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>Id</th>
                            <th class='text-center'>Date</th>
                            <th class='text-center'>Type</th>
                            <th class='text-center'>Name</th>
                            <th class='text-center'>Detail</th>
                    <?php
					if (($acl == '2') or ($acl > '5')) {
					?>
                        <th class='text-center'>Action</th>
					<?php
					}
                    ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = 'select * from `acc`';
                        if ($searcher <> null)
                        {
                            $sql = $sql."where `name` like '%".$searcher."%'";
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
                            echo $cdr['date'];
                            echo "</td>";
                            echo "<td>";
                            if ($cdr['typ'] == 1 ) {
                                echo "System";
                            };
                            if ($cdr['typ'] == 2 ) {
                                echo "Manual";
                            };
                            if ($cdr['typ'] == 3 ) {
                                echo "Receive";
                            };
                            if($cdr['typ'] == 4) {
                                echo "Payment";
                            }
                            if ($cdr['typ'] == 5 ) {
                                echo "Production";
                            };
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['action'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['detail'];
                            
                            echo "</td>";
                            if (($acl == '2') or ($acl > '5')) {
                            echo "<td>";
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=acc&task=edit&id=".$cdr['id']."\",\"_self\")' />";
						    echo "<input type='button' value='Del' onclick='confirm_del(\"index.php?option=acc&task=del&id=".$cdr['id']."&stat=0\")' />";
						    echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=acc&task=det&id=".$cdr['id']."\",\"_self\")' />";
						    echo "</td>";
                            }
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
            $head = "Add";
            $date = date("Y-m-d");
            $typ = 2;
            $action = "";
            $uid = $_SESSION['uid'];
            $detail = "";
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from acc where id = '".$id."'";
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $date = $cdr['date'];
                $action = $cdr['action'];
                $typ = $cdr['typ'];
                $uid = $cdr['uid'];
                $detail = $cdr['detail'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
				<h2>Accounting</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'><?php echo $head;?> Accouting List</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>
                            <select name='typ' >
								<option value='1'>System</option>
								<option value='2'>Manual</option>
                                <option value='3'>Receive</option>
                                <option value='4'>Payment</option>
                                <option value='5'>Production</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Action</td>
                        <td>
                            <input name='action' value="<?php echo $action;?>">
                        </td>
                    </tr>
                    <tr>
                        <td>User</td>
                        <td>
                            <?php echo $_SESSION['uname'];?>
                        </td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>
                            <input type = 'text' id='datepicker' name='date' value='<?php echo $date;?>'>
                        </td>
                    </tr>
                    <tr>
                        <td>Detail</td>
                        <td>
                            <input name='detail' value="<?php echo $detail;?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='text-center'>
                            <input type="submit" value="Save" >
                            <input type="button" value="Back" onclick="window.open('index.php?option=acc&task=def','_self')">
                            <input type="hidden" name="option" value="acc">
                            <input type="hidden" name="task" value="save">
                            <input type="hidden" name="id" value="<?php echo $id;?>">
                        </td>
                    </tr>
                </table>
                </form>
				<hr />
				<?php
				if ($id <> 0)
				{
				?>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No.</td>
                        <td class='text-center'>Account</td>
                        <td class='text-center'>Dr.</td>
                        <td class='text-center'>Cr.</td>
                        <td class='text-center'>Action</td>
                    </tr>
                    <?php
                    $a = 1;
                    $sql = "select `acc_typ`.`name` as `name`, `acc_detail`.`value` as `value`, `acc_detail`.`id` as `id`, `acc_detail`.`typ` as `typ` from `acc_detail`, `acc_typ` where `acc_detail`.`status` = '1' and `acc_typ`.`status` = '1' and `acc_typ`.`id` = `acc_detail`.`typ_id` and `acc_detail`.`acc_id` = '".$id."'";
					//echo $sql;
                    $res = $conn->query($sql);
					$dr = 0;
					$cr = 0;
                    while ($cdr = $res->fetch()) 
                    {
                        echo "<tr>";
                        echo "<td class='text-center'>";
                        echo $a;
                        echo "</td>";
                        echo "<td>";
                        echo $cdr['name'];
                        echo "</td>";
						if ($cdr['typ'] == 1)
						{
							echo "<td class='text-end'>";
							echo number_format($cdr['value'],2);
							echo "</td>";
							echo "<td class='text-end'>";
							echo "0.00";
							echo "</td>";
							$dr += $cdr['value'];
						}
						else
						{
							echo "<td class='text-end'>";
							echo "0.00";
							echo "</td>";
							echo "<td class='text-end'>";
							echo number_format($cdr['value'],2);
							echo "</td>";
							$cr += $cdr['value'];
						}
                        echo "<td class='text-center'>";
                        echo "<input type='button' value='Delete' onclick='window.open(\"index.php?option=acc&task=del_detail&id=".$cdr['id']."&acc_id=".$id."\",\"_self\")' />";
                        echo "</td>";
                        echo "</tr>";
                        $a++;
                    }
                    ?>
					<tfoot>
						<tr>
							<td colspan='2'>
							</td>
							<td class='text-end'>
							<?php
								echo number_format($dr,2);
							?>
							</td>
							<td class='text-end'>
							<?php
								echo number_format($dr,2);
							?>
							</td>
							<td class='text-end'>
							<?php
								$tt = $dr - $cr;
								echo number_format($tt,2);
							?>
							</td>
						</tr>
					</tfoot>
                </table>
				<hr />
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'>Add Accounting Detail</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>
                            <select name='typ'>
								<option value='1'>Dr.</option>
								<option value='2'>Cr.</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Account</td>
                        <td>
                            <select name='typ_id'>
							<?php
								$sql = "select * from `acc_typ` where `acc_typ`.`status` = '1'";
								$res = $conn->query($sql);
								while ($cdr = $res->fetch()) 
								{
									echo "<option value='".$cdr['id']."'>".$cdr['name']."</option>";
								}
							?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Value</td>
                        <td>
                            <input type='text' name='value' value='0'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='text-center'>
                            <input type="submit" value="Save">
                            <input type="hidden" name="option" value="acc">
                            <input type="hidden" name="task" value="save_detail">
                            <input type="hidden" name="acc_id" value="<?php echo $id;?>">
                        </td>
                    </tr>
                </table>
                </form>
				<?php
				}
				?>
                </div>
            </div>
        </div>
                </div>
        <?php

    }
 
    function save()
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        $typ = $_REQUEST['typ'];
        $action = $_REQUEST['action'];
        $date = $_REQUEST['date'];
        $detail = $_REQUEST['detail'];
        if ($id > 0) 
        {
            $sql = "update acc set typ = '".$typ."', action = '".$action."', date = '".$date."', detail = '".$detail."' where id = '".$id."'";
            $conn->query($sql);
        }
        else 
        {
            $sql = "insert into acc set typ = '".$typ."', action = '".$action."', date = '".$date."', detail = '".$detail."'";
            $id = $conn->query_lastid($sql);
        }
        header("location:index.php?option=acc&task=edit&id=".$id);
    }

    function save_detail() 
    {
        $acc_id = $_REQUEST['acc_id'];
		$typ_id = $_REQUEST['typ_id'];
		$typ = $_REQUEST['typ'];
		$value = $_REQUEST['value'];
		$sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '".$typ_id."', `typ` = '".$typ."', `value` = '".$value."'";
		$conn = new connect();
		$conn->query($sql);
        header("location:index.php?option=acc&task=edit&id=".$acc_id);
    }

    function del_detail() 
    {
        $acc_id = $_REQUEST['acc_id'];
		$id = $_REQUEST['id'];
		$sql = "update `acc_detail` set `status` = '0' where `id` = '".$id."'";
		$conn = new connect();
		$conn->query($sql);
        header("location:index.php?option=acc&task=edit&id=".$acc_id);
    }

    function del() 
    {
        $id = $_REQUEST['id'];
		$sql = "update `acc` set `status` = '".$_REQUEST['stat']."' where `id` = '".$id."'";
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=acc&task=def');
    }

    function det() 
    {
        $id = $_REQUEST['id'];
        
		$sql = "select * from `acc` where `id` = '".$id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
            $id = $cdr['id'];
            $date = $cdr['date'];
		    $action = $cdr['action'];
            $typ = $cdr['typ'];
            if ($cdr['typ'] == 1 ) {
                $typ = "System";
            };
            if ($cdr['typ'] == 2 ) {
                $typ = "Manual";
            };
            if ($cdr['typ'] == 3 ) {
                $typ = "Receive";
            };
            if($cdr['typ'] == 4) {
                $typ = "Payment";
            }
            if ($cdr['typ'] == 5 ) {
                $typ = "Production";
            };
            $name = $cdr['action'];
            $detail = $cdr['detail'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
				<h2>Accounting</h2>
                <table class='table table-bordered table-striped'>
					<thead>
						<tr>
							<td colspan='2' class='text-center'>Accounting Data</td>
						</tr>
					</thead>
					<tbody>
                        <tr>
							<td>Id</td>
							<td>
								<?php echo $id;?>
							</td>
						</tr>
						<tr>
							<td>Date</td>
							<td>
								<?php echo $date;?>
							</td>
						</tr>
						<tr>
							<td>Type</td>
							<td>
                                <?php echo $typ;?>
							</td>
						</tr>
						<tr>
							<td>Name</td>
							<td>
								<?php echo $name;?>
							</td>
						</tr>
						<tr>
							<td>Datail</td>
							<td>
								<?php echo $detail;?>
							</td>
						</tr>
						<tr>
                        <td colspan='2' class='text-center'>
                        <input type="button" value="Print" onclick="window.open('print.php?cat=acc&typ=det&id=<?php echo $id;?>','_self')">
                        <input type="button" value="Back" onclick="window.open('index.php?option=acc&task=def','_self')">
                        </td>
                    </tr>
					</tbody>
				</table>
                <hr />

                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No.</td>
                        <td class='text-center'>Account</td>
                        <td class='text-center'>Detail</td>
                        <td class='text-center'>Dr.</td>
                        <td class='text-center'>Cr.</td>
                        <td class='text-center'>Total</td>
                    </tr>
                    <?php
                    $a = 1;
                    $sql = "select `acc_typ`.`name` as `name`, `acc_detail`.`detail` as `detail`, `acc_detail`.`value` as `value`, `acc_detail`.`id` as `id`, `acc_detail`.`typ` as `typ` from `acc_detail`, `acc_typ` where `acc_detail`.`status` = '1' and `acc_typ`.`status` = '1' and `acc_typ`.`id` = `acc_detail`.`typ_id` and `acc_detail`.`acc_id` = '".$id."'";
					//echo $sql;
                    $res = $conn->query($sql);
					$dr = 0;
					$cr = 0;
                    while ($cdr = $res->fetch()) 
                    {
                        echo "<tr>";
                        echo "<td class='text-center'>";
                        echo $a;
                        echo "</td>";
                        echo "<td>";
                        echo $cdr['name'];
                        echo "</td>";
                        echo "<td>";
                        echo $cdr['detail'];
                        echo "</td>";
						if ($cdr['typ'] == 1)
						{
							echo "<td class='text-end'>";
							echo number_format($cdr['value'],2);
							echo "</td>";
							echo "<td class='text-end'>";
							echo "0.00";
							echo "</td>";
                            echo "<td class='text-end'>";
							echo "</td>";
							$dr += $cdr['value'];
						}
						else
						{
							echo "<td class='text-end'>";
							echo "0.00";
							echo "</td>";
							echo "<td class='text-end'>";
							echo number_format($cdr['value'],2);
							echo "</td>";
							$cr += $cdr['value'];
                            echo "<td class='text-end'>";
							echo "</td>"; 
						}
                        echo "</tr>";
                        $a++;
                    }
                    ?>
					<tfoot>
						<tr>
							<td colspan='2'>
							</td>
							<td class='text-end'>
							<?php
								echo number_format($dr,2);
							?>
							</td>
							<td class='text-end'>
							<?php
								echo number_format($dr,2);
							?>
							</td>
							<td class='text-end'>
							<?php
								$tt = $dr - $cr;
								echo number_format($tt,2);
							?>
							</td>
						</tr>
					</tfoot>
                </table>
                </div>
            </div>
        </div>
        <?php
    }

    function sum() 
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
                <center>
                <?php
                    echo "acc Summary";
                ?>
                <form action='index.php' method='get'>
                <input name='searcher' value='<?php echo $searcher;?>'>
                <input type='submit' value='Search'>
                <input type='hidden' name='option' value='acc'>
                <input type='hidden' name='task' value='sum'>
                </form>
                </center>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No.</th>
                            <th class='text-center'>Name</th>
                            <th class='text-center'>Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "select *,
                        ifnull((select (`sale_detail`.`num` * `stock`.`sale`) as sum from `sale`, `sale_detail`, stock where `sale`.`id` = `sale_detail`.`sale_id`
                        and `sale_detail`.`stock_id` = `stock`.`id` and `sale`.`acc_id` = `acc`.`id`),0) as sum from `acc`";
                        if ($searcher <> null)
                        {
                            $sql = $sql."where `name` like '%".$searcher."%'";
                        }
                        $conn = new connect();
                        $res = $conn->query($sql);
                        while ($cdr = $res->fetch())
                        {
                            echo "<tr>";
                            echo "<td class='text-center'>";
                            echo $cdr['id'];
                            echo "</td>";
                            echo "<td class='text-center'>";
                            echo $cdr['name'];
                            echo "</td>";
                            echo "<td class='text-end'>";
                            echo number_format($cdr['sum'],2);
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <center><input type='button' value='Back' onclick='window.open("index.php?option=acc&task=def","_self")'></center>
                </div>
            </div>
        </div>
        <?php
    }

}

?>