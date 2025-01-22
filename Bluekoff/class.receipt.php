<?php

class receipt
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
            $status = -1;
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Receipt</h2>
                <form action="index.php" method="get">
				Status : 
				<select name='status'>
				<?php
					$a = -1;
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
                <input name="searcher" value="<?php echo $searcher;?>">
                <input type="submit" value="Search">
                <?php
					if (($acl == '2') or ($acl > '5')) {
				?>
                <input type='button' value='Add' onclick='window.open("index.php?option=receipt&task=add","_self")'>
                <?php
				}
				?>
                <input type="hidden" name="option" value="receipt">
                <input type="hidden" name="task" value="def">
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No</th>
                            <th class='text-center'>Doc ID</th>
                            <th class='text-center'>Date</th>
                            <th class='text-center'>Customer</th>
							<th class='text-center'>User</th>
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
                        $a = 1;
                        $sql = "select `customer`.`name` as `name`, `receipt`.`id` as `id`, `receipt`.`status` as `status`, `receipt`.`date` as `date`, `receipt`.`uaid` as `uaid`, `receipt`.`uid` as `uid`, `receipt`.`adate` as `adate` from `receipt`, `customer`
                        where `receipt`.`customer_id` = `customer`.`id`";
                        if ($searcher <> null) 
                        {
                            $sql = $sql." and action like '%".$searcher."%' ";
                        }
                        if ($status > -1) 
                        {
                            $sql = $sql." and `receipt`.`status` = '".$status."' ";
                        }
                        $conn = new connect();
                        $res = $conn->query($sql);
                        while ($cdr = $res->fetch())
                        {
                            echo "<tr>";
                            echo "<td>";
                            echo $a;
                            echo "</td>";
                            echo "<td>";
							echo $conn->get_doc_code("receipt", $cdr['date'], $cdr['id']);
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['date'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['name'];
                            echo "</td>";
                            echo "<td>";
                            echo $conn->get_user_name($cdr['uid'], 2);
                            echo "</td>";
                            echo "<td>";
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
								echo "by ".$conn->get_user_name($cdr['uaid'], 2);
								echo " [".$cdr['adate']."]";
                            }
                            echo "</td>";
                            if (($acl == '2') or ($acl > '5')) {
                            echo "<td>";
                            echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=receipt&task=det&id=".$cdr['id']."\",\"_self\")' />";
							if ($cdr['status'] <> 2)
                            {
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=receipt&task=edit&id=".$cdr['id']."\",\"_self\")' />";
                            echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=receipt&task=del&id=".$cdr['id']."&stat=".$dss."&date=".$cdr['date']."\")' />";
							}
							if ($cdr['status'] == 1)
                            {
                            echo "<input type='button' value='Approve' onclick='window.open(\"index.php?option=receipt&task=del&id=".$cdr['id']."&stat=2&date=".$cdr['date']."\",\"_self\")' />";
							}
                            echo "</td>";
                            }
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

	function add()
	{
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
					<h3>
						Select So to Receipt
					</h3>
				</div>
			</div>
            <div class='row'>
                <div class='col-12'>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No</th>
                            <th class='text-center'>Doc ID</th>
                            <th class='text-center'>Date</th>
                            <th class='text-center'>Customer</th>
                            <th class='text-center'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        $sql = "select `customer`.`name` as `name`, `so`.`id` as `id`, `so`.`status` as `status`, `so`.`date` as `date`, (select `users`.`name` from `users` where `users`.`status` = '1' and `users`.`id` = `so`.`uaid`) as `uaid` from `so`, `customer`
                        where `so`.`customer_id` = `customer`.`id` and `so`.`status` = '2' and `so`.`id` not in (select `receipt`.`so_id` from `receipt` where `receipt`.`status` > 0)";
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
							echo $conn->get_doc_code("SO", $cdr['date'], $cdr['id']);
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['date'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['name'];
                            echo "</td>";
                            echo "<td>";
                            echo "<input type='button' value='Select' onclick='window.open(\"index.php?option=receipt&task=edit&id=0&ref=".$cdr['id']."\",\"_self\")' />";
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
        $id = $_REQUEST['id'];
        if ($id == 0) 
        {
            $head = "Add";
            $sql = "select * from so where id = '".$_REQUEST['ref']."'";
            $ref = $_REQUEST['ref'];
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $date = $cdr['date'];
                $customer_id = $cdr['customer_id'];
            }
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from receipt where id = '".$id."'";
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $date = $cdr['date'];
                $ref = $cdr['so_id'];
                $customer_id = $cdr['customer_id'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'><?php echo $head;?> receipt Data</td>
                    </tr>
                    <tr>
                        <td>Customer</td>
                        <td>
                            <select name='customer'>
                            <?php
                            $sql = "select * from customer where status > '0'";
                            $res = $conn->query($sql);
                            while ($cdr = $res->fetch()) 
                            {
                                if ($customer_id == $cdr['id']) 
                                {
                                    echo "<option value='".$cdr['id']."' selected>".$cdr['name']."</option>";
                                }
                                else 
                                {
                                    echo "<option value='".$cdr['id']."'>".$cdr['name']."</option>";
                                }
                            }
                            ?>
                            </select>
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
                        <td>Reference from receipt </td>
                        <td>
                            <input type='hidden' name='ref' value='<?php echo $ref;?>' readonly />
							<?php echo $conn->get_doc_code("SO", $date, $ref);?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='text-center'>
                            <input type="submit" value="Save">
                            <input type="button" value="Back" onclick="window.open('index.php?option=receipt&task=def','_self')">
                            <input type="hidden" name="option" value="receipt">
                            <input type="hidden" name="task" value="save">
                        </td>
                    </tr>
                </table>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No</td>
                        <td class='text-center'>Name</td>
                        <td class='text-center'>unit_price</td>
                        <td class='text-center'>Number</td>
                    </tr>
                    <?php
                    $a = 1;
					if ($id == 0)
					{
                    $sql = "select `inventory`.`name` as `name`
                    , (
					select `so_detail`.`unit_price` from `so_detail` where `so_detail`.`status` = '1' and `inventory`.`id` = `so_detail`.`inventory_id` and `so_detail`.`so_id` = '".$ref."'
					)  as receipt
                    , `inventory`.`id` as stid
                    , (
                    select `num` from `so_detail`
                    where `so_detail`.`status` > 0 
                    and `so_detail`.`inventory_id` = `inventory`.`id`
                    and `so_detail`.`so_id` = '".$ref."'
                    ) as `num`
                    from `inventory` 
                    where `inventory`.`status` > 0 having `num` <> ''";
					}
					else
					{
                    $sql = "select `inventory`.`name` as name
                    , ifnull((
					select `receipt_detail`.`unit_price` from `receipt_detail` where `receipt_detail`.`status` = '1' and `inventory`.`id` = `receipt_detail`.`inventory_id` and `receipt_detail`.`receipt_id` = '".$id."'
					) , `inventory`.`buy`) as receipt
                    , `inventory`.`id` as stid
                    , (
                    select num from receipt_detail 
                    where `receipt_detail`.`status` > 0 
                    and `receipt_detail`.`inventory_id` = `inventory`.`id`
                    and `receipt_detail`.`receipt_id` = '".$id."'
                    ) as num
                    from inventory 
                    where `inventory`.`status` > 0 having `num` <> ''";
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
                        echo "<input name='unit_price-".$a."' value='".$cdr['receipt']."' />";
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
                        echo "<input type='number' min='0' name='num-".$a."' value='".$num."' />";
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
 
    function save()
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        $customer = $_REQUEST['customer'];
        $date = $_REQUEST['date'];
        $ref = $_REQUEST['ref'];
        if ($id > 0) 
        {
            $sql = "update receipt set customer_id = '".$customer."', date = '".$date."' where id = '".$id."'";
            $conn->query($sql);
            $sql = "update receipt_detail set status = '0' where receipt_id = '".$id."'";
            $conn->query($sql);
			$code = $conn->get_doc_code("receipt", $date, $id);
			$conn->save_logs("Edit receipt > ".$code, $_SESSION['uid'], $id);
        }
        else 
        {
            $sql = "insert into receipt set customer_id = '".$customer."', date = '".$date."', `uid` = '".$_SESSION['uid']."', `so_id` = '".$ref."'";
            $id = $conn->query_lastid($sql);
			$code = $conn->get_doc_code("receipt", $date, $id);
			$conn->save_logs("Add receipt > ".$code, $_SESSION['uid'], $id);
        }
        $limit = $_REQUEST['limit'];
        $a = 1;
        while ($a < $limit) 
        {
            if ($_REQUEST['num-'.$a] > 0) 
            {
                $sql = "insert into receipt_detail set receipt_id = '".$id."', inventory_id = '".$_REQUEST['id-'.$a]."', num = '".$_REQUEST['num-'.$a]."', unit_price = '".$_REQUEST['unit_price-'.$a]."'";
                $conn->query($sql);
            }
            $a++;
        }
        header("location:index.php?option=receipt&task=def");
    }
 
    function del()
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        $stat = $_REQUEST['stat'];
        $date = $_REQUEST['date'];
		$code = $conn->get_doc_code("receipt", $date, $id);
		if ($stat == 2)
		{
			$sql = "update receipt set status = '".$stat."', `uaid` = '".$_SESSION['uid']."', `adate` = '".date('Y-m-d')."' where id = '".$id."' ";
            $conn->save_logs("Approve receipt > ".$code, $_SESSION['uid'], $id);
		}
		else
		{
			$sql = "update receipt set status = '".$stat."' where id = '".$id."' ";
            if ($stat == 1)
            {
                $conn->save_logs("Active receipt > ".$code, $_SESSION['uid'], $id);
            }
            elseif ($stat == 0)
            {
                $conn->save_logs("In-Active receipt > ".$code, $_SESSION['uid'], $id);
            }
		}
        $conn->query($sql);
        header("location:index.php?option=receipt&task=def");
    }
 
    function det()
    {
        $conn = new connect();
		$id = $_REQUEST['id'];
		$sql = "select * from `receipt` where `id` = '".$id."'";
		$res = $conn->query($sql);
		while ($cdr = $res->fetch())
		{
			$customer = $cdr['customer_id'];
			$date = $cdr['date'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Receipt</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'>receipt Form</td>
                    </tr>
                    <tr>
                        <td>Doc ID</td>
                        <td>
                            <?php
								echo $conn->get_doc_code("Receipt", $date, $id);
							?>
                        </td>
                    </tr>
                    <tr>
                        <td>Customer</td>
                        <td>
                            <?php
						    $sql = "select * from `customer` where `id` = '".$customer."'";
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
							<?php
								if (isset($_REQUEST['blank'])==false)
								{
									?>
									<input type="button" value="Back" onclick="window.open('index.php?option=receipt&task=def','_self')">
									<?php
								}
								else
								{
									?>
									<input type="button" value="Close" onclick="window.close()">
									<?php
								}
							?>
                        </td>
                    </tr>
                </table>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No</td>
                        <td class='text-center'>Name</td>
                        <td class='text-center'>Value</td>
                        <td class='text-center'>Number</td>
                        <td class='text-center'>Total</td>
                    </tr>
                    <?php
                    $a = 1;
                    $net = 0;
                    $sql = "select `inventory`.`name` as name
                    , `inventory`.`id` as stid
                    , `receipt_detail`.`num` as `num` 
                    , `receipt_detail`.`unit_price` as `receipt` 
                    from `inventory`, `receipt_detail`
				    where `inventory`.`status` > 0
                    and `receipt_detail`.`status` > 0
                    and `receipt_detail`.`inventory_id` = `inventory`.`id`
                    and `receipt_detail`.`receipt_id` = '".$id."'";
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
                        echo number_format($cdr['receipt'],2);
                        echo "</td>";

                        echo "<td>";
                        echo $cdr['num'];
                        echo "<input type='hidden' name='id-".$a."' value='".$cdr['stid']."' />";
                        echo "</td>";
                        echo "<td class='text-end'>";
                        $receipt = $cdr['receipt'];
                        $num = $cdr['num'];
                        $total = ($receipt * $num);
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

}

?>