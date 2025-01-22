<?php

class recieve
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
                <h2>Recieve</h2>
                <form action="index.php" method="get">
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
                <input name="searcher" value="<?php echo $searcher;?>">
                <input type="submit" value="Search">
                <?php
					if (($acl == '2') or ($acl > '5')) {
				?>
                <input type='button' value='Add' onclick='window.open("index.php?option=recieve&task=add","_self")'>
                <?php
				}
				if ($acl > '3') {
				?>
                <input type='button' value='Print' onclick='window.open("recieveint.php?cat=recieve&typ=all","_self")'>
                <?php
				}
				?>
                <input type="hidden" name="option" value="recieve">
                <input type="hidden" name="task" value="def">
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No.</th>
                            <th class='text-center'>Date</th>
                            <th class='text-center'>Receive Type</th>
                            <th class='text-center'>Supplier</th>
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
                        $sql = "select `recieve`.`id` as `id`, `recieve`.`po_id` as `po_id`, `recieve`.`rec_typ` as `typ`, `recieve`.`status` as `status`, `recieve`.`date` as `date`, (select `users`.`name` from `users` where `users`.`status` = '1' and `users`.`id` = `recieve`.`uaid`) as `uaid`, (select `supplier`.`name` from `supplier` where `supplier`.`id` = `recieve`.`supplier_id`) as `name`, `recieve`.`adate` as `adate` from `recieve`
                        where `recieve`.`status` >= '0'";
                        if ($searcher <> null) 
                        {
                            $sql = $sql." and action like '%".$searcher."%' ";
                        }
                        if ($status <> null) 
                        {
                            $sql = $sql." and `recieve`.`status` = '".$status."' ";
                        }
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
							if ($cdr['typ'] == 1)
							{
								echo "From PO [".$cdr['po_id']."]";
								echo " <input type='button' value='Link' onclick='window.open(\"index.php?option=po&task=det&id=".$cdr['po_id']."\",\"_blank\",\"toolbar=0\")'>";
							}
							elseif ($cdr['typ'] == 2)
							{
								echo "From Production [".$cdr['po_id']."]";
								echo " <input type='button' value='Link' onclick='window.open(\"index.php?option=production&task=det&id=".$cdr['po_id']."\",\"_blank\",\"toolbar=0\")'>";
							}
                            echo "</td>";
                            echo "<td>";
							if ($cdr['name'] <> '')
							{
								echo $cdr['name'];
							}
							else
							{
								echo "By System";
							}
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
								echo "by ".$cdr['uaid'];
								echo " [".$cdr['adate']."]";
                            }
                            echo "</td>";
                            if (($acl == '2') or ($acl > '5')) {
                            echo "<td>";
                            echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=recieve&task=det&id=".$cdr['id']."\",\"_self\")' />";
							if ($cdr['status'] <> 2)
                            {
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=recieve&task=edit&id=".$cdr['id']."\",\"_self\")' />";
                            echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=recieve&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
							}
							if ($cdr['status'] == 1)
                            {
                            echo "<input type='button' value='Approve' onclick='window.open(\"index.php?option=recieve&task=del&id=".$cdr['id']."&stat=2&typ=".$cdr['typ']."\",\"_self\")' />";
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
						Select Purchase Order to Receive
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
                        $sql = "select `supplier`.`name` as `name`, `po`.`id` as `id`, `po`.`status` as `status`, `po`.`date` as `date`, (select `users`.`name` from `users` where `users`.`status` = '1' and `users`.`id` = `po`.`uaid`) as `uaid` from `po`, `supplier`
                        where `po`.`supplier_id` = `supplier`.`id` and `po`.`status` = '2' and `po`.`id` not in (select `recieve`.`po_id` from `recieve` where `recieve`.`status` > 0)";
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
                            echo "<input type='button' value='Select' onclick='window.open(\"index.php?option=recieve&task=edit&id=0&ref=".$cdr['id']."\",\"_self\")' />";   
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=recieve&task=edit&id=0&ref=".$cdr['id']."\",\"_self\")' />";  
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
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'><?php echo $head;?> Receive</td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
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
                        <td>Reference from Purchase Order </td>
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
                        <td class='text-center'>unit_price</td>
                        <td class='text-center'>Number</td>
                    </tr>
                    <?php
                    $a = 1;
					if ($id == 0)
					{
                    $sql = "select `inventory`.`name` as `name`
                    , (
					select `po_detail`.`unit_price` from `po_detail` where `po_detail`.`status` = '1' and `inventory`.`id` = `po_detail`.`inventory_id` and `po_detail`.`po_id` = '".$ref."'
					)  as recieve
                    , `inventory`.`id` as stid
                    , (
                    select `num` from `po_detail`
                    where `po_detail`.`status` > 0 
                    and `po_detail`.`inventory_id` = `inventory`.`id`
                    and `po_detail`.`po_id` = '".$ref."'
                    ) as `num`
                    from `inventory` 
                    where `inventory`.`status` > 0 having `num` <> ''";
					}
					else
					{
                    $sql = "select `inventory`.`name` as name
                    , ifnull((
					select `recieve_detail`.`unit_price` from `recieve_detail` where `recieve_detail`.`status` = '1' and `inventory`.`id` = `recieve_detail`.`inventory_id` and `recieve_detail`.`recieve_id` = '".$id."'
					) , `inventory`.`buy`) as recieve
                    , `inventory`.`id` as stid
                    , (
                    select num from recieve_detail 
                    where `recieve_detail`.`status` > 0 
                    and `recieve_detail`.`inventory_id` = `inventory`.`id`
                    and `recieve_detail`.`recieve_id` = '".$id."'
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
                        echo "<input type='hidden' name='unit_price-".$a."' value='".$cdr['recieve']."' />";
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
 
    function save()
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        $supplier = $_REQUEST['supplier'];
        $date = $_REQUEST['date'];
        $ref = $_REQUEST['ref'];
        if ($id > 0) 
        {
            $sql = "update recieve set supplier_id = '".$supplier."', date = '".$date."' where id = '".$id."'";
            $conn->query($sql);
            $sql = "update recieve_detail set status = '0' where recieve_id = '".$id."'";
            $conn->query($sql);
            $conn->save_logs("Edit Receive #".$id,$_SESSION['uid']);
        }
        else 
        {
            $sql = "insert into recieve set supplier_id = '".$supplier."', date = '".$date."', `uid` = '".$_SESSION['uid']."', `po_id` = '".$ref."'";
            $id = $conn->query_lastid($sql);
            $conn->save_logs("Add Receive #".$id,$_SESSION['uid']);
        }
        $limit = $_REQUEST['limit'];
        $a = 1;
        while ($a < $limit) 
        {
            if ($_REQUEST['num-'.$a] > 0) 
            {
                $sql = "insert into recieve_detail set recieve_id = '".$id."', inventory_id = '".$_REQUEST['id-'.$a]."', num = '".$_REQUEST['num-'.$a]."', unit_price = '".$_REQUEST['unit_price-'.$a]."', `locatiion_id` = '".$_REQUEST['locatiion_id-'.$a]."'";
                $conn->query($sql);
            }
            $a++;
        }
        header("location:index.php?option=recieve&task=def");
    }
 
    function del()
    {
        $id = $_REQUEST['id'];
        $stat = $_REQUEST['stat'];
        $typ = $_REQUEST['typ'];
        $conn = new connect();
		if ($stat == 2)
		{
			$sql = "update `recieve` set `status` = '".$stat."', `uaid` = '".$_SESSION['uid']."', `adate` = '".date('Y-m-d')."' where `id` = '".$id."' ";
			$conn->query($sql);
			if ($typ == '1')
			{
				$sql = "select `supplier_id` as `sup`, (select sum(`recieve_detail`.`unit_price` * `recieve_detail`.`num`) from `recieve_detail` where `recieve_detail`.`status` = '1' and `recieve_detail`.`recieve_id` = '".$id."' group by `recieve_detail`.`recieve_id`) as `val` from `recieve` where `id` = '".$id."'";
				$res = $conn->query($sql);
				while ($cdr = $res->fetch()) 
				{
					$detail = "Reference From Receive #".$id;
					$value = $cdr['val'];
					$supplier = $cdr['sup'];
				}
				$sql = "insert into `payment` set `supplier_id` = '".$supplier."', `date` = '".date('Y-m-d')."', `detail` = '".$detail."', `value` = '".$value."', `user_id` = '".$_SESSION['uid']."', `ref_id` = '".$id."', `typ` ='1'";
				$conn->query($sql);
				$sql = "insert into acc set typ = '3', action = 'Purcahse', date = '".date('Y-m-d')."', detail = 'Data from Purcahse Rec#".$id."', `uid` = '".$_SESSION['uid']."'";
				$acc_id = $conn->query_lastid($sql);
				$sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '8', `typ` = '1', `value` = '".$value."'";
				$res = $conn->query($sql);
				$sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '9', `typ` = '2', `value` = '".$value."'";
				$res = $conn->query($sql);
			}
			elseif ($typ == '2')
			{
				$sql = "select sum(`recieve_detail`.`unit_price` * `recieve_detail`.`num`) as `val` from `recieve_detail` where `recieve_detail`.`status` = '1' and `recieve_detail`.`recieve_id` = '".$id."' group by `recieve_detail`.`recieve_id`";
				$res = $conn->query($sql);
				while ($cdr = $res->fetch()) 
				{
					$value = $cdr['val'];
				}
				$sql = "insert into acc set typ = '3', action = 'Production', date = '".date('Y-m-d')."', detail = 'Data from Production#".$id."', `uid` = '".$_SESSION['uid']."'";
				$acc_id = $conn->query_lastid($sql);
				$sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '11', `typ` = '1', `value` = '".$value."'";
				$res = $conn->query($sql);
				$sql = "insert into `acc_detail` set `acc_id` = '".$acc_id."', `typ_id` = '10', `typ` = '2', `value` = '".$value."'";
				$res = $conn->query($sql);
			}
            $conn->save_logs("Approve Receive#".$id, $_SESSION['uid']);
		}
		else
		{
			$sql = "update `recieve` set `status` = '".$stat."' where `id` = '".$id."' ";
			$conn->query($sql);
            if ($stat == 1)
            {
                $conn->save_logs("Active Receive#".$id, $_SESSION['uid']);
            }
            elseif ($stat == 0)
            {
                $conn->save_logs("In-Active Receive#".$id, $_SESSION['uid']);
            }
		}
        header("location:index.php?option=recieve&task=def");
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
                <h2>Recieve</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'>recieve Form</td>
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
                            <input type="button" value="print" onclick="window.open('recieveint.php?cat=recieve&typ=det&id=<?php echo $id;?>','_self')">
                            <input type="button" value="Back" onclick="window.open('index.php?option=recieve&task=def','_self')">
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
                    $sql = "select `inventory`.`name` as name
                    , `inventory`.`id` as stid
                    , `recieve_detail`.`num` as `num` 
                    , `recieve_detail`.`unit_price` as `recieve` 
                    from `inventory`, `recieve_detail`
				    where `inventory`.`status` > 0
                    and `recieve_detail`.`status` > 0
                    and `recieve_detail`.`inventory_id` = `inventory`.`id`
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

}

?>