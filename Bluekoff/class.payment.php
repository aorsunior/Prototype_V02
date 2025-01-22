<?php

class payment
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
        if (isset($_REQUEST['status']))
        {
            $status = $_REQUEST['status'];
        }
        else
        {
            $status = 1;
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
				<h2>Payment</h2>
                <form action='index.php' method='get'>
				Status : 
				<select name='status'>
				<?php
					$a = 0;
					while ($a < 3) 
					{
						$cc = '';
						if ($a == $status)
						{
							$cc = 'selected';
						}
						echo "<option value='".$a."' ".$cc.">".$conn->get_status($a)."</option>";
						$a++;
					}
				?>
				</select>
                <input name='searcher' value='<?php echo $searcher;?>'>
                <input type='submit' value='Search'>
                <?php
					if (($acl == '2') or ($acl > '5')) {
				?>
                <input type='button' value='Add' onclick='window.open("index.php?option=payment&task=edit&id=0","_self")'>
                <?php
				}
				if ($acl > '3') {
				?>
                <input type='button' value='Print' onclick='window.open("print.php?cat=payment&typ=all","_self")'>
                <?php
				}
				?>
                <input type='hidden' name='option' value='payment'>
                <input type='hidden' name='task' value='def'>
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>Id</th>
                            <th class='text-center'>Detail</th>
                            <th class='text-center'>Value</th>
                            <th class='text-center'>Status</th>
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
                        $sql = "select `payment`.`id` as `id`, `payment`.`value` as `value`, `payment`.`status` as `status`, `payment`.`detail` as `detail`, `payment`.`date` as `date`, (select `users`.`name` from `users` where `users`.`status` = 1 and `users`.`id` = `payment`.`uaid`) as `uaid`, `payment`.`typ` as `ptyp` from `payment` where `payment`.`status` > '-1'";
                        if ($status <> null) 
                        {
                            $sql = $sql." and `payment`.`status` = '".$status."' ";
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
                            echo $cdr['detail'];
                            echo "<br />";
                            echo $cdr['date'];
                            echo "</td>";
                            echo "<td class='text-end'>";
                            echo number_format($cdr['value'],2);
                            echo "</td>";
                            echo "<td class='text-center'>";
                            if ($cdr['status'] == 1)
                            {
                                echo "Active";
                                $ds = "In-Active";
                                $dss = "0";
                            }
                            elseif ($cdr['status'] == 0)
                            {
                                echo "In-Active";
                                $ds = "Active";
                                $dss = "1";
                            }
                            elseif ($cdr['status'] == 2)
                            {
                                echo "Approve ";
								echo "by ".$cdr['uaid'];
                            }
                            echo "</td>";
                            if (($acl == '2') or ($acl > '5')) {
                            echo "<td class='text-center'>";
                            echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=payment&task=det&id=".$cdr['id']."\",\"_self\")' />";
                            if ($cdr['status'] <> 2)
                            {
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=payment&task=edit&id=".$cdr['id']."\",\"_self\")' />";
						    echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=payment&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
                            }
							if ($cdr['status'] == 1)
                            {
                            echo "<input type='button' value='Approve' onclick='window.open(\"index.php?option=payment&task=approve&id=".$cdr['id']."&stat=2\",\"_self\")' />";
							}
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

    function add()
	{
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
					<h3>
						Select Receive to Payment
					</h3>
				</div>
			</div>
            <div class='row'>
                <div class='col-12'>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No.</th>
                            <th class='text-center'>Date</th>
                            <th class='text-center'>Supplier</th>
                            <th class='text-center'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        $sql = "select `supplier`.`name` as `name`, `recieve`.`id` as `id`, `recieve`.`status` as `status`, `recieve`.`date` as `date`, (select `users`.`name` from `users` where `users`.`status` = '1' and `users`.`id` = `recieve`.`uaid`) as `uaid` from `recieve`, `supplier`
                        where `recieve`.`supplier_id` = `supplier`.`id` and `recieve`.`status` = '2' and `recieve`.`id` not in (select `payment`.`ref_id` from `payment` where `payment`.`status` > 0)";
						//echo $sql;
                        $conn = new connect();
                        $res = $conn->query($sql);
                        while ($cdr = $res->fetch())
                        {
                            echo "<tr>";
                            echo "<td>";
                            echo $a;
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['date'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['name'];
                            echo "</td>";
                            echo "<td>";
                            echo "<input type='button' value='Select' onclick='window.open(\"index.php?option=payment&task=edit&id=0&ref=".$cdr['id']."\",\"_self\")' />";   
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=payment&task=edit&id=0&ref=".$cdr['id']."\",\"_self\")' />";  
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

    function approve() 
    {
        $id = $_REQUEST['id'];
		$sql = "select * from `payment` where `id` = '".$id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$detail = $cdr['detail'];
			$date = $cdr['date'];
			$user_id = $cdr['user_id'];
			$value = $cdr['value'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <form action='index.php' method='get'>
				<table class='table table-bordered table-striped'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'>Approve Payment</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Detail</td>
							<td>
							<?php
								echo $detail;
							?>
							</td>
						</tr>
						<tr>
							<td>Date</td>
							<td>
							<?php
								echo $date;
							?>
							</td>
						</tr>
						<tr>
							<td>User</td>
							<td>
							<?php
								echo $user_id;
							?>
							</td>
						</tr>
						<tr>
							<td>Value</td>
							<td>
							<?php
								echo number_format($value,2);
								echo "<input type='hidden' name='value' value='".$value."' />";
							?>
							</td>
						</tr>
						<tr>
							<td>From Account</td>
							<td>	
								<select name='typ_id'>
								<?php
								$sql = "select * from `acc_typ` where `acc_typ`.`status` = '1' and `root` = '0'";
								$res = $conn->query($sql);
								while ($cdr = $res->fetch()) 
								{
									echo "<optgroup label='".$cdr['name']."'>";
									$sqls = "select * from `acc_typ` where `acc_typ`.`status` = '1' and `root` = '".$cdr['id']."'";
									$ress = $conn->query($sqls);
									while ($cdrs = $ress->fetch()) 
									{
										echo "<option value='".$cdrs['id']."'>>".$cdrs['name']."</option>";
									}
									echo "</optgroup>";
								}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Approve Date</td>
							<td>
								<input type = 'text' id='datepicker' name='app_date' value='' />
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='hidden' name="option" value='payment'>
								<input type='hidden' name="task" value='approval'>
								<input type='hidden' name="id" value='<?php echo $id;?>'>
								<input type='submit' value='Save'>
								<input type='button' value='Back' onclick='window.open("index.php?option=payment&task=def","_self")'>
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

    function approval() 
    {
        $id = $_REQUEST['id'];
        $typ_id = $_REQUEST['typ_id'];
        $app_date = $_REQUEST['app_date'];
        $value = $_REQUEST['value'];
		$conn = new connect();
		$sql = "update `payment` set `status` = '2', `app_date` = '".$app_date."', `app_date` = '".$app_date."', `uaid` = '".$_SESSION['uid']."'  where `id` = '".$id."'";
		$conn->query($sql);
        $sql = "insert into acc set typ = '4', action = 'Payment', date = '".date('Y-m-d')."', detail = 'Data from Payment Rec#".$id."', `uid` = '".$_SESSION['uid']."'";
        $acc_id = $conn->query_lastid($sql);
        $sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '9', `typ` = '1', `value` = '".$value."'";
        $res = $conn->query($sql);
        $sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '".$typ_id."', `typ` = '2', `value` = '".$value."'";
        $res = $conn->query($sql);
        if ($stat == 2)
		{
			$sql = "update payment set status = '".$stat."', `uaid` = '".$_SESSION['uid']."' where id = '".$id."' ";
            $conn->save_logs("Approve Payment#".$id, $_SESSION['uid']);
		}
		header('location:index.php?option=payment&task=def');
    }

    function edit() 
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        if ($id == 0) 
        {
            $head = "Add";
            $sql = "select * from pr where id = '".$_REQUEST['ref']."'";
            $ref = $_REQUEST['ref'];
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $date = $cdr['date'];
                $supplier_id = $cdr['supplier_id'];
            }
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from recieve where id = '".$id."'";
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $date = $cdr['date'];
                $ref = $cdr['po_id'];
                $supplier_id = $cdr['supplier_id'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
				<h2>Payment</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'><?php echo $head;?> Payment</td>
                    </tr>
                    <tr>
                        <td>Payee</td>
                        <td>
                            <?php
                            $sql = "select * from supplier where status > '0' and `id` = '".$supplier_id."'";
                            $res = $conn->query($sql);
                            while ($cdr = $res->fetch()) 
                            {
                                echo $cdr['name'];
								echo "<input type='hidden' name='supplier' value='".$cdr['id']."' />";
                            }
                            ?>
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
                        <td>Reference from Receive </td>
                        <td>
                            <input name='ref' value='<?php echo $ref;?>' readonly />
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='text-center'>
                            <input type="submit" value="Save">
                            <input type="button" value="Back" onclick="window.open('index.php?option=recieve&task=def','_self')">
                            <input type="hidden" name="option" value="recieve">
                            <input type="hidden" name="task" value="save">
                        </td>
                    </tr>
                </table>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No.</td>
                        <td class='text-center'>Name</td>
                        <td class='text-center'>Cost</td>
                        <td class='text-center'>Number</td>
                    </tr>
                    <?php
                    $a = 1;
					if ($id == 0)
					{
                    $sql = "select `stock`.`name` as `name`
                    , (
					select `po_detail`.`cost` from `po_detail` where `po_detail`.`status` = '1' and `stock`.`id` = `po_detail`.`stock_id` and `po_detail`.`po_id` = '".$ref."'
					)  as recieve
                    , `stock`.`id` as stid
                    , (
                    select `num` from `po_detail`
                    where `po_detail`.`status` > 0 
                    and `po_detail`.`stock_id` = `stock`.`id`
                    and `po_detail`.`po_id` = '".$ref."'
                    ) as `num`
                    from `stock` 
                    where `stock`.`status` > 0 having `num` <> ''";
					}
					else
					{
                    $sql = "select `stock`.`name` as name
                    , ifnull((
					select `recieve_detail`.`cost` from `recieve_detail` where `recieve_detail`.`status` = '1' and `stock`.`id` = `recieve_detail`.`stock_id` and `recieve_detail`.`recieve_id` = '".$id."'
					) , `stock`.`buy`) as recieve
                    , `stock`.`id` as stid
                    , (
                    select num from recieve_detail 
                    where `recieve_detail`.`status` > 0 
                    and `recieve_detail`.`stock_id` = `stock`.`id`
                    and `recieve_detail`.`recieve_id` = '".$id."'
                    ) as num
                    from stock 
                    where `stock`.`status` > 0 having `num` <> ''";
					}
					//echo $sql;
                    $res = $conn->query($sql);
                    while ($cdr = $res->fetch()) 
                    {
                        echo "<tr>";
                        echo "<td class='text-center'>";
                        echo $a;
                        echo "</td>";
                        echo "<td>";
                        echo $cdr['name'];
                        echo "</td>";
                        echo "<td class='text-end'>";
                        echo "<input type='hidden' name='cost-".$a."' value='".$cdr['recieve']."' />";
						echo $cdr['recieve'];
                        echo "</td>";
                        echo "<td>";
                        if ($cdr['num'] == null) 
                        {
                            $num = 0;
                        }
                        else
                        {
                            $num = $cdr['num'];
                        }
                        echo "<input type='number' name='num-".$a."' value='".$num."' />";
                        echo "<input type='hidden' name='id-".$a."' value='".$cdr['stid']."' />";
                        echo "</td>";
                        echo "</tr>";
                        $a++;
                    }
                    echo "<input type='hidden' name='limit' value='".$a."' />";
                    echo "<input type='hidden' name='id' value='".$id."' />";
                    ?>
                </table>
                </form>
                </div>
            </div>
        </div>
        </div>
        <?php
    }

    function del() 
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        $stat = $_REQUEST['stat'];

			$sql = "update payment set status = '".$stat."' where id = '".$id."' ";
            if ($stat == 1)
            {
                $conn->save_logs("Active Payment#".$id, $_SESSION['uid']);
            }
            elseif ($stat == 0)
            {
                $conn->save_logs("In-Active Payment#".$id, $_SESSION['uid']);
            }
		
		
		$conn->query($sql);
		header('location:index.php?option=payment&task=def');
    }

    function save() 
    {
        $id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		if ($id == 0) 
		{
			$sql = "insert into `payment` set `name` = '".$name."'";
            $conn->save_logs("Edit Payment #".$id,$_SESSION['uid']);
		}
		else 
		{
			$sql = "update `payment` set `name` = '".$name."' where `id` = '".$id."'";
            $conn->save_logs("Add Payment #".$id,$_SESSION['uid']);
		}
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=payment&task=def');
    }

    function det()
    {
        $conn = new connect();
		$id = $_REQUEST['id'];
		$sql = "select * from `recieve` where `id` = '".$id."'";
		$res = $conn->query($sql);
		while ($cdr = $res->fetch())
		{
			$supplier = $cdr['supplier_id'];
			$date = $cdr['date'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
				<h2>Payment</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'>Payment Form</td>
                    </tr>
                    <tr>
                        <td>Doc ID</td>
                        <td>
                            <?php echo $id;?>
                        </td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td>
                            <?php
						    $sql = "select * from `supplier` where `id` = '".$supplier."'";
						    $res = $conn->query($sql);
						    while ($cdr = $res->fetch())
						    {
							    echo $cdr['name'];
						    }
					        ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>
                            <?php echo $date;?>
                        </td>
                    </tr>
                    <tr>
                        <td>User</td>
                        <td>
                            <?php echo $_SESSION['uname'];?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='text-center'>
                        <input type='button' value='Print' onclick='window.open("print.php?cat=payment&typ=all","_self")'>
                        <input type='button' value='Back' onclick='window.open("index.php?option=payment&task=def","_self")'>
                        </td>
                    </tr>
                </table>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No.</td>
                        <td class='text-center'>Name</td>
                        <td class='text-center'>Value</td>
                        <td class='text-center'>Number</td>
                        <td class='text-center'>Total</td>
                    </tr>
                    <?php
                    $a = 1;
                    $net = 0;
                    $sql = "select `stock`.`name` as name
                    , `stock`.`id` as stid
                    , `recieve_detail`.`num` as `num` 
                    , `recieve_detail`.`cost` as `recieve` 
                    from `stock`, `recieve_detail`
				    where `stock`.`status` > 0
                    and `recieve_detail`.`status` > 0
                    and `recieve_detail`.`stock_id` = `stock`.`id`
                    and `recieve_detail`.`recieve_id` = '".$id."'";
                    $res = $conn->query($sql);
                    while ($cdr = $res->fetch()) 
                    {
                        echo "<tr>";
                        echo "<td class='text-center'>";
                        echo $a;
                        echo "</td>";
                        echo "<td>";
                        echo $cdr['name'];
                        echo "</td>";

                        echo "<td class='text-end'>";
                        echo number_format($cdr['recieve'],2);
                        echo "</td>";

                        echo "<td>";
                        echo $cdr['num'];
                        echo "<input type='hidden' name='id-".$a."' value='".$cdr['stid']."' />";
                        echo "</td>";
                        echo "<td class='text-end'>";
                        $recieve = $cdr['recieve'];
                        $num = $cdr['num'];
                        $total = ($recieve * $num);
                        echo number_format($total,2);
                        echo "</td>";
                        echo "</tr>";
                        $net = $net + $total;
                        $a++;
                    }
                    echo "<tr>";
                    echo "<td colspan='4'>";
                    echo "Net Total";
                    echo "</td>";
                    echo "<td class='text-end'>"; 
                    echo number_format($net,2);
                    echo "</td>";
                    echo "</tr>";
                    echo "<input type='hidden' name='limit' value='".$a."' />";
                    echo "<input type='hidden' name='id' value='".$id."' />";
                    ?>
                </table>
                </form>
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
                    echo "payment Summary";
                ?>
                <form action='index.php' method='get'>
                <input name='searcher' value='<?php echo $searcher;?>'>
                <input type='submit' value='Search'>
                <input type='hidden' name='option' value='payment'>
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
                        and `sale_detail`.`stock_id` = `stock`.`id` and `sale`.`payment_id` = `payment`.`id`),0) as sum from `payment`";
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
                <center><input type='button' value='Back' onclick='window.open("index.php?option=payment&task=def","_self")'></center>
                </div>
            </div>
        </div>
        <?php
    }

}

?>