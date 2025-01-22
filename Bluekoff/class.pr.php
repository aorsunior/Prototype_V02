<?php

class pr
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
                <h2>Purchase Requirement</h2>
                <form action="index.php" method="get">
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
                <input name="searcher" value="<?php echo $searcher;?>">
                <input type="submit" value="Search">
                <?php
				if (($acl == '2') or ($acl > '5')) {
				?>
                <input type='button' value='Add' onclick='window.open("index.php?option=pr&task=edit&id=0","_self")'>
                <?php
				}
				if ($acl > '3') {
				?>
                <input type='button' value='Print' onclick='window.open("print.php?cat=pr&typ=all","_self")'>
                <?php
				}
				?>
                <input type="hidden" name="option" value="pr">
                <input type="hidden" name="task" value="def">
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No.</th>
                            <th class='text-center'>Date</th>
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
                        $sql = "select `supplier`.`name` as `name`, `pr`.`id` as `id`, `pr`.`status` as `status`, `pr`.`date` as `date`, (select `users`.`name` from `users` where `users`.`status` = '1' and `users`.`id` = `pr`.`uaid`) as `uaid`, `pr`.`adate` as `adate` from `pr`, `supplier`
                        where `pr`.`supplier_id` = `supplier`.`id`";
                        if ($searcher <> null) 
                        {
                            $sql = $sql." and action like '%".$searcher."%' ";
                        }
                        if ($status <> null) 
                        {
                            $sql = $sql." and `pr`.`status` = '".$status."' ";
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
                            echo $cdr['date'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['name'];
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
                            echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=pr&task=det&id=".$cdr['id']."\",\"_self\")' />";
							if ($cdr['status'] <> 2)
                            {
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=pr&task=edit&id=".$cdr['id']."\",\"_self\")' />";
                            echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=pr&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
							}
							if ($cdr['status'] == 1)
                            {
                            echo "<input type='button' value='Approve' onclick='window.open(\"index.php?option=pr&task=del&id=".$cdr['id']."&stat=2\",\"_self\")' />";
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
 
    function edit()
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        if ($id == 0) 
        {
            $head = "Add";
            $date = date("Y-m-d");
            $supplier_id = 0;
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from pr where id = '".$id."'";
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $date = $cdr['date'];
                $supplier_id = $cdr['supplier_id'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Purchase Requirement</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'><?php echo $head;?> pr</td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td>
                            <select name='supplier'>
                            <?php
                            $sql = "select * from supplier where status > '0'";
                            $res = $conn->query($sql);
                            while ($cdr = $res->fetch()) 
                            {
                                if ($supplier_id == $cdr['id']) 
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
                        <td colspan='2' class='text-center'>
                            <input type="submit" value="Save">
                            <input type="button" value="Back" onclick="window.open('index.php?option=pr&task=def','_self')">
                            <input type="hidden" name="option" value="pr">
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
                    $sql = "select *, `inventory`.`name` as `name`
                    , ifnull((
					select `pr_detail`.`unit_price` from `pr_detail` where `pr_detail`.`status` = '1' and `inventory`.`id` = `pr_detail`.`inventory_id` and `pr_detail`.`pr_id` = '".$id."'
					) , `inventory`.`buy`) as `pr`
                    , `inventory`.`id` as `stid`
                    , (
                    select `num` from `pr_detail` 
                    where `pr_detail`.`status` = '1'
                    and `pr_detail`.`inventory_id` = `inventory`.`id`
                    and `pr_detail`.`pr_id` = '".$id."'
                    ) as `num`
                    from `inventory`
                    where `inventory`.`status` = '1'";
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
                        echo "<input name='unit_price-".$a."' value='".$cdr['pr']."' />";
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
        if ($id > 0) 
        {
            $sql = "update pr set supplier_id = '".$supplier."', date = '".$date."' where id = '".$id."'";
            $conn->query($sql);
            $sql = "update pr_detail set status = '0' where pr_id = '".$id."'";
            $conn->query($sql);
            $conn->save_logs("Edit PR #".$id,$_SESSION['uid']);
        }
        else 
        {
            $sql = "insert into pr set supplier_id = '".$supplier."', date = '".$date."', `uid` = '".$_SESSION['uid']."'";
            $id = $conn->query_lastid($sql);
            $conn->save_logs("Add PR #".$id,$_SESSION['uid']);
        }
        $limit = $_REQUEST['limit'];
        $a = 1;
        while ($a < $limit) 
        {
            if ($_REQUEST['num-'.$a] > 0) 
            {
                $sql = "insert into pr_detail set pr_id = '".$id."', inventory_id = '".$_REQUEST['id-'.$a]."', num = '".$_REQUEST['num-'.$a]."', unit_price = '".$_REQUEST['unit_price-'.$a]."'";
                $conn->query($sql);
            }
            $a++;
        }
        header("location:index.php?option=pr&task=def");
    }
 
    function del()
{
    $conn = new connect();
    $id = $_REQUEST['id'];
    $stat = $_REQUEST['stat'];

    if ($stat == 2)
    {
        $sql = "UPDATE pr SET status = '".$stat."', `uaid` = '".$_SESSION['uid']."', `adate` = '".date('Y-m-d')."' WHERE id = '".$id."' ";
        $conn->save_logs("Approve PR#".$id, $_SESSION['uid']);
    }
    else
    {
        $sql = "UPDATE pr SET status = '".$stat."' WHERE id = '".$id."' ";
        if ($stat == 1)
        {
            $conn->save_logs("Active PR#".$id, $_SESSION['uid']);
        }
        elseif ($stat == 0)
        {
            $conn->save_logs("In-Active PR#".$id, $_SESSION['uid']);
        }
    }
    $conn->query($sql);
    header("location:index.php?option=pr&task=def");
}
 
    function det()
    {
        $conn = new connect();
		$id = $_REQUEST['id'];
		$sql = "select * from `pr` where `id` = '".$id."'";
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
                <h2>Purchase Requirement</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'>pr Form</td>
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
                            <input type="button" value="Print" onclick="window.open('print.php?cat=pr&typ=det&id=<?php echo $id;?>','_self')">
                            <input type="button" value="Back" onclick="window.open('index.php?option=pr&task=def','_self')">
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
                    , `pr_detail`.`num` as `num` 
                    , `pr_detail`.`unit_price` as `pr` 
                    from `inventory`, `pr_detail`
				    where `inventory`.`status` > 0
                    and `pr_detail`.`status` > 0
                    and `pr_detail`.`inventory_id` = `inventory`.`id`
                    and `pr_detail`.`pr_id` = '".$id."'";
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
                        echo number_format($cdr['pr'],2);
                        echo "</td>";
                        
                        echo "<td>";
                        echo $cdr['num'];
                        echo "<input type='hidden' name='id-".$a."' value='".$cdr['stid']."' />";
                        echo "</td>";
                        echo "<td class='text-end'>";
                        $pr = $cdr['pr'];
                        $num = $cdr['num'];
                        $total = ($pr * $num);
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