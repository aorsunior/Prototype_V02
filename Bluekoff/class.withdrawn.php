<?php

class withdrawn
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
                <h2>Withdrawn</h2>
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
                <input type='button' value='Add' onclick='window.open("index.php?option=withdrawn&task=add","_self")'>
                <?php
				}
				if ($acl > '3') {
				?>
                <input type='button' value='Print' onclick='window.open("withdrawnint.php?cat=withdrawn&typ=all","_self")'>
                <?php
				}
				?>
                <input type="hidden" name="option" value="withdrawn">
                <input type="hidden" name="task" value="def">
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No.</th>
                            <th class='text-center'>Date</th>
                            <th class='text-center'>Withdrawn Type</th>
                            <th class='text-center'>Customer</th>
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
                        $sql = "select `withdrawn`.`id` as `id`, `withdrawn`.`so_id` as `so_id`, `withdrawn`.`wd_typ` as `typ`, `withdrawn`.`status` as `status`, `withdrawn`.`date` as `date`, (select `users`.`name` from `users` where `users`.`status` = '1' and `users`.`id` = `withdrawn`.`uaid`) as `uaid`, (select `customer`.`name` from `customer` where `customer`.`id` = `withdrawn`.`customer_id`) as `name`, `withdrawn`.`adate` as `adate` from `withdrawn`
                        where `withdrawn`.`status` >= '0'";
                        if ($searcher <> null) 
                        {
                            $sql = $sql." and action like '%".$searcher."%' ";
                        }
                        if ($status <> null) 
                        {
                            $sql = $sql." and `withdrawn`.`status` = '".$status."' ";
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
								echo "From SO [".$cdr['so_id']."]";
								echo " <input type='button' value='Link' onclick='window.open(\"index.php?option=so&task=det&id=".$cdr['so_id']."\",\"_blank\",\"toolbar=0\")'>";
							}
							elseif ($cdr['typ'] == 2)
							{
								echo "From Production [".$cdr['so_id']."]";
								echo " <input type='button' value='Link' onclick='window.open(\"index.php?option=production&task=det&id=".$cdr['so_id']."\",\"_blank\",\"toolbar=0\")'>";
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
                            echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=withdrawn&task=det&id=".$cdr['id']."\",\"_self\")' />";
							if ($cdr['status'] <> 2)
                            {
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=withdrawn&task=edit&id=".$cdr['id']."\",\"_self\")' />";
                            echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=withdrawn&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
							}
							if ($cdr['status'] == 1)
                            {
                            echo "<input type='button' value='Approve' onclick='window.open(\"index.php?option=withdrawn&task=del&id=".$cdr['id']."&stat=2&typ=".$cdr['typ']."\",\"_self\")' />";
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
						Select Sale Order to Withdrawn
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
                            <th class='text-center'>Customer</th>
                            <th class='text-center'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        $sql = "select `customer`.`name` as `name`, `so`.`id` as `id`, `so`.`status` as `status`, `so`.`date` as `date`, (select `users`.`name` from `users` where `users`.`status` = '1' and `users`.`id` = `so`.`uaid`) as `uaid` from `so`, `customer`
                        where `so`.`customer_id` = `customer`.`id` and `so`.`status` = '2' and `so`.`id` not in (select `withdrawn`.`so_id` from `withdrawn` where `withdrawn`.`status` > 0)";
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
                            echo "<input type='button' value='Select' onclick='window.open(\"index.php?option=withdrawn&task=edit&id=0&ref=".$cdr['id']."\",\"_self\")' />";   
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=withdrawn&task=edit&id=0&ref=".$cdr['id']."\",\"_self\")' />";  
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
            $sql = "select * from withdrawn where id = '".$id."'";
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
                        <td colspan='2' class='text-center'><?php echo $head;?> Withdrawn</td>
                    </tr>
                    <tr>
                        <td>Customer</td>
                        <td>
                            <?php
                            $sql = "select * from customer where status > '0' and `id` = '".$customer_id."'";
                            $res = $conn->query($sql);
                            while ($cdr = $res->fetch()) 
                            {
                                echo $cdr['name'];
								echo "<input type='hidden' name='customer' value='".$cdr['id']."' />";
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
                        <td>Reference from Sale Order </td>
                        <td>
                            <input name='ref' value='<?php echo $ref;?>' readonly />
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='text-center'>
                            <input type="submit" value="Save">
                            <input type="button" value="Back" onclick="window.open('index.php?option=withdrawn&task=def','_self')">
                            <input type="hidden" name="option" value="withdrawn">
                            <input type="hidden" name="task" value="save">
                        </td>
                    </tr>
                </table>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No.</td>
                        <td class='text-center'>Name</td>
                        <td class='text-center'>Unit_price</td>
                        <td class='text-center'>Number</td>
                    </tr>
                    <?php
                    $a = 1;
					if ($id == 0)
					{
                    $sql = "select `inventory`.`name` as `name`
                    , (
					select `so_detail`.`unit_price` 
                    from `so_detail` where `so_detail`.`status` = '1' 
                    and `inventory`.`id` = `so_detail`.`inventory_id` 
                    and `so_detail`.`so_id` = '".$ref."'
					)  as withdrawn
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
					select `withdrawn_detail`.`unit_price` 
                    from `withdrawn_detail` where `withdrawn_detail`.`status` = '1' 
                    and `inventory`.`id` = `withdrawn_detail`.`inventory_id` 
                    and `withdrawn_detail`.`withdrawn_id` = '".$id."'
					) , `inventory`.`buy`) as withdrawn
                    , `inventory`.`id` as stid
                    , (
                    select num from withdrawn_detail 
                    where `withdrawn_detail`.`status` > 0 
                    and `withdrawn_detail`.`inventory_id` = `inventory`.`id`
                    and `withdrawn_detail`.`withdrawn_id` = '".$id."'
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
                        echo "<input type='hidden' name='unit_price-".$a."' value='".$cdr['withdrawn']."' />";
						echo $cdr['withdrawn'];
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
        $customer = $_REQUEST['customer'];
        $date = $_REQUEST['date'];
        $ref = $_REQUEST['ref'];
        if ($id > 0) 
        {
            $sql = "update withdrawn set customer_id = '".$customer."', date = '".$date."' where id = '".$id."'";
            $conn->query($sql);
            $sql = "update withdrawn_detail set status = '0' where withdrawn_id = '".$id."'";
            $conn->query($sql);
            $conn->save_logs("Edit Withdrawn #".$id,$_SESSION['uid']);
        }
        else 
        {
            $sql = "insert into withdrawn set customer_id = '".$customer."', date = '".$date."', `uid` = '".$_SESSION['uid']."', `so_id` = '".$ref."'";
            $id = $conn->query_lastid($sql);
            $conn->save_logs("Add Withdrawn #".$id,$_SESSION['uid']);
        }
        $limit = $_REQUEST['limit'];
        $a = 1;
        while ($a < $limit) 
        {
            if ($_REQUEST['num-'.$a] > 0) 
            {
                $sql = "insert into withdrawn_detail set withdrawn_id = '".$id."', inventory_id = '".$_REQUEST['id-'.$a]."', num = '".$_REQUEST['num-'.$a]."', unit_price = '".$_REQUEST['unit_price-'.$a]."', `locatiion_id` = '".$_REQUEST['locatiion_id-'.$a]."'";
                $conn->query($sql);
            }
            $a++;
        }
        header("location:index.php?option=withdrawn&task=def");
    }
 
    function del()
    {
        $id = $_REQUEST['id'];
        $stat = $_REQUEST['stat'];
        $typ = $_REQUEST['typ'];
        $conn = new connect();
		if ($stat == 2)
		{
			$sql = "update `withdrawn` set `status` = '".$stat."', `uaid` = '".$_SESSION['uid']."', `adate` = '".date('Y-m-d')."' where `id` = '".$id."' ";
			$conn->query($sql);
			if ($typ == '1')
			{
				$sql = "select `customer_id` as `sup`, (select sum(`withdrawn_detail`.`unit_price` * `withdrawn_detail`.`num`) from `withdrawn_detail` where `withdrawn_detail`.`status` = '1' and `withdrawn_detail`.`withdrawn_id` = '".$id."' group by `withdrawn_detail`.`withdrawn_id`) as `val` from `withdrawn` where `id` = '".$id."'";
				$res = $conn->query($sql);
				while ($cdr = $res->fetch()) 
				{
					$detail = "Reference From Withdrawn #".$id;
					$value = $cdr['val'];
					$customer = $cdr['sup'];
				}
				$sql = "insert into `payment` set `customer_id` = '".$customer."', `date` = '".date('Y-m-d')."', `detail` = '".$detail."', `value` = '".$value."', `user_id` = '".$_SESSION['uid']."', `ref_id` = '".$id."', `typ` ='1'";
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
				$sql = "select sum(`withdrawn_detail`.`unit_price` * `withdrawn_detail`.`num`) as `val` from `withdrawn_detail` where `withdrawn_detail`.`status` = '1' and `withdrawn_detail`.`withdrawn_id` = '".$id."' group by `withdrawn_detail`.`withdrawn_id`";
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
            $conn->save_logs("Approve Withdrawn#".$id, $_SESSION['uid']);
		}
		else
		{
			$sql = "update `withdrawn` set `status` = '".$stat."' where `id` = '".$id."' ";
			$conn->query($sql);
            if ($stat == 1)
            {
                $conn->save_logs("Active Withdrawn#".$id, $_SESSION['uid']);
            }
            elseif ($stat == 0)
            {
                $conn->save_logs("In-Active Withdrawn#".$id, $_SESSION['uid']);
            }
		}
        header("location:index.php?option=withdrawn&task=def");
    }
 
    function det()
    {
        $conn = new connect();
		$id = $_REQUEST['id'];
		$sql = "select * from `withdrawn` where `id` = '".$id."'";
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
                <h2>Withdrawn</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'>Withdrawn Form</td>
                    </tr>
                    <tr>
                        <td>Doc ID</td>
                        <td>
                            <?php echo $id;?>
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
                            <input type="button" value="print" onclick="window.open('withdrawnint.php?cat=withdrawn&typ=det&id=<?php echo $id;?>','_self')">
                            <input type="button" value="Back" onclick="window.open('index.php?option=withdrawn&task=def','_self')">
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
                    , `withdrawn_detail`.`num` as `num` 
                    , `withdrawn_detail`.`unit_price` as `withdrawn` 
                    from `inventory`, `withdrawn_detail`
				    where `inventory`.`status` > 0
                    and `withdrawn_detail`.`status` > 0
                    and `withdrawn_detail`.`inventory_id` = `inventory`.`id`
                    and `withdrawn_detail`.`withdrawn_id` = '".$id."'";
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
                        echo number_format($cdr['withdrawn'],2);
                        echo "</td>";

                        echo "<td>";
                        echo $cdr['num'];
                        echo "<input type='hidden' name='id-".$a."' value='".$cdr['stid']."' />";
                        echo "</td>";
                        echo "<td class='text-end'>";
                        $withdrawn = $cdr['withdrawn'];
                        $num = $cdr['num'];
                        $total = ($withdrawn * $num);
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