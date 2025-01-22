<?php

class farming
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
                <h2>farming</h2>
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
                <input type='button' value='Add' onclick='window.open("index.php?option=farming&task=add","_self")'>
                <?php
				}
				if ($acl > '3') {
				?>
                <input type='button' value='Print' onclick='window.open("farmingint.php?cat=farming&typ=all","_self")'>
                <?php
				}
				?>
                <input type="hidden" name="option" value="farming">
                <input type="hidden" name="task" value="def">
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No</th>
                            <th class='text-center'>Doc ID</th>
                            <th class='text-center'>Date</th>
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
                        $sql = "select `farming`.`id` as `id`, `farming`.`status` as `status`, `farming`.`date` as `date`, `farming`.`uaid` as `uaid`, `farming`.`uid` as `uid`, `farming`.`adate` as `adate` from `farming` where '1' = '1'";
                        if ($searcher <> null) 
                        {
                            $sql = $sql." and action like '%".$searcher."%' ";
                        }
                        if ($status > -1) 
                        {
                            $sql = $sql." and `farming`.`status` = '".$status."' ";
                        }
                        $conn = new connect();
                        $res = $conn->query($sql);
                        while ($cdr = $res->fetch())
                        {
                            echo "<tr>";
                            echo "<td class='text-center'>";
                            echo $a;
                            echo "</td>";
                            echo "<td class='text-center'>";
							echo $conn->get_doc_code("farming", $cdr['date'], $cdr['id']);
                            echo "</td>";
                            echo "<td class='text-center'>";
                            echo $cdr['date'];
                            echo "</td>";
                            echo "<td class='text-center'>";
                            echo $conn->get_user_name($cdr['uid'], 2);
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
								echo "by ".$conn->get_user_name($cdr['uaid'], 2);
								echo " [".$cdr['adate']."]";
                            }
                            elseif ($cdr['status'] == 3)
                            {
                                echo "Complete ";
								echo "by ".$conn->get_user_name($cdr['uaid'], 2);
								echo " [".$cdr['adate']."]";
                            }
                            echo "</td>";
                            if (($acl == '2') or ($acl > '5')) {
                            echo "<td class='text-center'>";
                            echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=farming&task=det&id=".$cdr['id']."\",\"_self\")' />";
							if ($cdr['status'] < 2)
                            {
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=farming&task=edit&id=".$cdr['id']."\",\"_self\")' />";
                            echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=farming&task=del&id=".$cdr['id']."&stat=".$dss."&date=".$cdr['date']."\")' />";
							}
							if ($cdr['status'] == 1)
                            {
                            echo "<input type='button' value='Approve' onclick='window.open(\"index.php?option=farming&task=del&id=".$cdr['id']."&stat=2&date=".$cdr['date']."\",\"_self\")' />";
							}
                            if ($cdr['status'] == 2)
                            {
                            echo "<input type='button' value='Save Product' onclick='window.open(\"index.php?option=farming&task=save_product&id=".$cdr['id']."\",\"_self\")' />";
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
						Select Batch to Farming
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
                            <th class='text-center'>Name</th>
                            <th class='text-center'>Date</th>
                            <th class='text-center'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $a = 1;
                        $sql = "select `batch`.`id` as `id`, `batch`.`status` as `status`, `batch`.`name` as `name`, `batch`.`date` as `date`, `batch`.`uaid` as `uaid` from `batch` 
                        where `batch`.`status` = '2' and `batch`.`id` not in (select `farming`.`batch_id` from `farming` where `farming`.`status` > 0)";
						//echo $sql;
                        $conn = new connect();
                        $res = $conn->query($sql);
                        while ($cdr = $res->fetch())
                        {
                            echo "<tr>";
                            echo "<td class='text-center'>";
                            echo $a;
                            echo "</td>";
                            echo "<td class='text-center'>";
							echo $conn->get_doc_code("BATCH", $cdr['date'], $cdr['id']);
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['name'];
                            echo "</td>";
                            echo "<td class='text-center'>";
                            echo $cdr['date'];
                            echo "</td>";
                            echo "<td class='text-center'>";
                            echo "<input type='button' value='Select' onclick='window.open(\"index.php?option=farming&task=edit&id=0&ref=".$cdr['id']."\",\"_self\")' />";
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
            $sql = "select * from `batch` where `id` = '".$_REQUEST['ref']."'";
            $ref = $_REQUEST['ref'];
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $date = $cdr['date'];
            }
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from `farming` where `id` = '".$id."'";
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $date = $cdr['date'];
                $ref = $cdr['batch_id'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <form action="index.php" method="get">
                <div class='col-12'>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'><?php echo $head;?> farming Data</td>
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
                        <td>Reference from Batch </td>
                        <td>
                            <input type='hidden' name='ref' value='<?php echo $ref;?>' readonly />
							<?php echo $conn->get_doc_code("BATCH", $date, $ref);?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' class='text-center'>
                            <input type="submit" value="Save">
                            <input type="button" value="Back" onclick="window.open('index.php?option=farming&task=def','_self')">
                            <input type="hidden" name="option" value="farming">
                            <input type="hidden" name="task" value="save">
                        </td>
                    </tr>
                </table>
				</div>
			</div>
            <div class='row'>
			<?php
				$qq = array(1, 3);
				$aq = 0;
				$a = 1;
				while ($aq < count($qq))
				{
				?>
                <div class='col-6 text-center'>
				<?php
					if ($aq == 0)
					{
						echo "Material";
					}
					else
					{
						echo "Product";
					}
				?>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No</td>
                        <td class='text-center'>Name</td>
                        <td class='text-center'>Number</td>
                    </tr>
                    <?php
					if ($id == 0)
					{
                    $sql = "select *,`inventory`.`name` as `name`
                    , `inventory`.`id` as stid
                    , (
                    select `num` from `batch_detail`
                    where `batch_detail`.`status` > 0 
                    and `batch_detail`.`inventory_id` = `inventory`.`id`
                    and `batch_detail`.`batch_id` = '".$ref."'
                    and `batch_detail`.`typ` = '".$qq[$aq]."'
                    ) as `num`
                    from `inventory` 
                    where `inventory`.`status` > 0 having `num` <> ''";
					}
					else
					{
                    $sql = "select *,`inventory`.`name` as name
                    , `inventory`.`id` as stid
                    , (
                    select num from `farming_detail` 
                    where `farming_detail`.`status` > 0 
                    and `farming_detail`.`inventory_id` = `inventory`.`id`
                    and `farming_detail`.`farming_id` = '".$id."'
                    and `farming_detail`.`typ` = '".$qq[$aq]."'
                    ) as num
                    from inventory 
                    where `inventory`.`status` > 0 having `num` <> ''";
					}
					//echo $sql;
                    $res = $conn->query($sql);
					$b = 1;
                    while ($cdr = $res->fetch()) 
                    {
                        echo "<tr>";
                        echo "<td class='text-center'>";
                        echo $b;
                        echo "</td>";
                        echo "<td>";
                        echo $cdr['name'];
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
                        echo "<input type='hidden' name='typ-".$a."' value='".$qq[$aq]."' />";
                        echo "</td>";
                        echo "</tr>";
                        $a++;
                        $b++;
                    }
                    echo "<input type='hidden' name='limit' value='".$a."' />";
                    echo "<input type='hidden' name='id' value='".$id."' />";
                    ?>
                </table>
                </div>
			<?php
				$aq++;
				}
			?>
            </form>
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
            $sql = "update `farming` set `date` = '".$date."' where `id` = '".$id."'";
            $conn->query($sql);
            $sql = "update farming_detail set status = '0' where farming_id = '".$id."'";
            $conn->query($sql);
			$code = $conn->get_doc_code("farming", $date, $id);
			$conn->save_logs("Edit farming > ".$code, $_SESSION['uid'], $id);
        }
        else 
        {
            $sql = "insert into `farming` set `date` = '".$date."', `uid` = '".$_SESSION['uid']."', `batch_id` = '".$ref."'";
            $id = $conn->query_lastid($sql);
			$code = $conn->get_doc_code("farming", $date, $id);
			$conn->save_logs("Add farming > ".$code, $_SESSION['uid'], $id);
        }
        $limit = $_REQUEST['limit'];
        $a = 1;
        while ($a < $limit) 
        {
            if ($_REQUEST['num-'.$a] > 0) 
            {
                $sql = "insert into farming_detail set farming_id = '".$id."', inventory_id = '".$_REQUEST['id-'.$a]."', num = '".$_REQUEST['num-'.$a]."', `typ` = '".$_REQUEST['typ-'.$a]."'";
                $conn->query($sql);
            }
            $a++;
        }
        header("location:index.php?option=farming&task=def");
    }
 
    function del()
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        $stat = $_REQUEST['stat'];
        $date = $_REQUEST['date'];
		$code = $conn->get_doc_code("farming", $date, $id);
		if ($stat == 2)
		{
			$sql = "update farming set status = '".$stat."', `uaid` = '".$_SESSION['uid']."', `adate` = '".date('Y-m-d')."' where id = '".$id."' ";
            $conn->save_logs("Approve farming > ".$code, $_SESSION['uid'], $id);
		}
		else
		{
			$sql = "update farming set status = '".$stat."' where id = '".$id."' ";
            if ($stat == 1)
            {
                $conn->save_logs("Active farming > ".$code, $_SESSION['uid'], $id);
            }
            elseif ($stat == 0)
            {
                $conn->save_logs("In-Active farming > ".$code, $_SESSION['uid'], $id);
            }
		}
        $conn->query($sql);
        header("location:index.php?option=farming&task=def");
    }
 
    function det()
    {
        $conn = new connect();
		$id = $_REQUEST['id'];
		$sql = "select * from `farming` where `id` = '".$id."'";
		$res = $conn->query($sql);
		while ($cdr = $res->fetch())
		{
			$date = $cdr['date'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>farming</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'>farming Form</td>
                    </tr>
                    <tr>
                        <td>Doc ID</td>
                        <td>
                            <?php
								echo $conn->get_doc_code("farming", $date, $id);
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
									<input type="button" value="print" onclick="window.open('farmingint.php?cat=farming&typ=det&id=<?php echo $id;?>','_self')">
									<input type="button" value="Back" onclick="window.open('index.php?option=farming&task=def','_self')">
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
                    , `farming_detail`.`num` as `num` 
                    , `inventory`.`sale` as `farming` 
                    from `inventory`, `farming_detail`
				    where `inventory`.`status` > 0
                    and `farming_detail`.`status` > 0
                    and `farming_detail`.`inventory_id` = `inventory`.`id`
                    and `farming_detail`.`farming_id` = '".$id."'";
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
                        echo number_format($cdr['farming'],2);
                        echo "</td>";

                        echo "<td>";
                        echo $cdr['num'];
                        echo "<input type='hidden' name='id-".$a."' value='".$cdr['stid']."' />";
                        echo "</td>";
                        echo "<td class='text-end'>";
                        $farming = $cdr['farming'];
                        $num = $cdr['num'];
                        $total = ($farming * $num);
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

	function save_product()
	{
        $conn = new connect();
        $id = $_REQUEST['id'];
		$head = "Save";
		$sql = "select * from farming where id = '".$id."'";
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$date = $cdr['date'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Save farming</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'><?php echo $head;?> farming</td>
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
                            <?php echo $date;?>
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
                    , `farming_detail`.`num` as `num` 
                    , `inventory`.`sale` as `farming` 
                    from `inventory`, `farming_detail`
				    where `inventory`.`status` > 0
                    and `farming_detail`.`status` > 0
                    and `farming_detail`.`inventory_id` = `inventory`.`id`
                    and `farming_detail`.`farming_id` = '".$id."'";
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
                        echo number_format($cdr['farming'],2);
                        echo "</td>";
                        
                        echo "<td>";
                        echo $cdr['num'];
                        echo "<input type='hidden' name='id-".$a."' value='".$cdr['stid']."' />";
                        echo "</td>";
                        echo "<td class='text-end'>";
                        $farming = $cdr['farming'];
                        $num = $cdr['num'];
                        $total = ($farming * $num);
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
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td class='text-center'>No.</td>
                        <td class='text-center'>Name</td>
                        <td class='text-center'>Value</td>
                        <td class='text-center'>Number</td>
                        <td class='text-center'>Warehouse</td>
                    </tr>
                    <?php
                    $a = 1;
                    $net = 0;
                    $sql = "select `inventory`.`name` as `name`
                    , `inventory`.`id` as `stid`
                    , `inventory`.`sale` as `sale`
                    from `inventory` where `inventory`.`status` = '1' and `inventory`.`typ_id` = '3'";
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
                        echo number_format($cdr['sale'],2);
                        echo "</td>";
                        echo "<td>";
                        echo "<input type='hidden' name='unit_price-".$a."' value='".$cdr['sale']."' />";
                        echo "<input type='hidden' name='id-".$a."' value='".$cdr['stid']."' />";
                        echo "<input type='number' name='num-".$a."' value='0' />";
                        echo "</td>";
                        echo "<td class='text-center'>";
						echo "<select name='location_col_id-".$a."'>";
						$sql_1 = "select `id`, `name` from `location` where `status` = '1'";
						$res_1 = $conn->query($sql_1);
						while ($cdr_1 = $res_1->fetch()) 
						{
							echo "<option disabled>".$cdr_1['name']."</option>";
							$sql_2 = "select `id`, `name` from `location_road` where `status` = '1' and `location_id` =  '".$cdr_1['id']."'";
							$res_2 = $conn->query($sql_2);
							while ($cdr_2 = $res_2->fetch()) 
							{
								echo "<option disabled>>".$cdr_2['name']."</option>";
								$sql_3 = "select `id`, `name` from `location_ctn` where `status` = '1' and `road_id` =  '".$cdr_2['id']."'";
								$res_3 = $conn->query($sql_3);
								while ($cdr_3 = $res_3->fetch()) 
								{
									echo "<option disabled>>>".$cdr_3['name']."</option>";
									$sql_4 = "select `id`, `name` from `location_row` where `status` = '1' and `ctn_id` =  '".$cdr_3['id']."'";
									$res_4 = $conn->query($sql_4);
									while ($cdr_4 = $res_4->fetch()) 
									{
										echo "<option disabled>>>>".$cdr_4['name']."</option>";
										$sql_5 = "select `id`, `name` from `location_col` where `status` = '1' and `row_id` =  '".$cdr_4['id']."'";
										$res_5 = $conn->query($sql_5);
										while ($cdr_5 = $res_5->fetch()) 
										{
											echo "<option value='".$cdr_5['id']."'>>>>>".$cdr_5['name']."</option>";
										}
									}
								}
							}
						}
						echo "</select>";
                        echo "</td>";
                        echo "</tr>";
                        $net = $net + $total;
                        $a++;
                    }
                    echo "<input type='hidden' name='limit' value='".$a."' />";
                    echo "<input type='hidden' name='id' value='".$id."' />";
                    ?>
                    <tr>
                        <td colspan='5' class='text-center'>
                            <input type="submit" value="Save">
                            <input type="button" value="Back" onclick="window.open('index.php?option=farming&task=def','_self')">
                            <input type="hidden" name="option" value="farming">
                            <input type="hidden" name="task" value="save_prod">
                        </td>
                    </tr>
                </table>
                </form>
                </div>
            </div>
        </div>
        <?php
	}

	function save_prod()
	{
        $conn = new connect();
        $id = $_REQUEST['id'];
		$sql = "UPDATE farming SET status = '3' WHERE id = '".$id."' ";
		$conn->query($sql);
		$sql = "insert into recieve set supplier_id = '".$supplier."', date = '".$date."', `user_id` = '".$_SESSION['uid']."', `po_id` = '".$id."', `rec_typ` ='2'";
		$id = $conn->query_lastid($sql);
		$conn->save_logs("Add Receive #".$id,$_SESSION['uid']);
        $limit = $_REQUEST['limit'];
        $a = 1;
        while ($a < $limit) 
        {
            if ($_REQUEST['num-'.$a] > 0) 
            {
                $sql = "insert into recieve_detail set recieve_id = '".$id."', inventory_id = '".$_REQUEST['id-'.$a]."', num = '".$_REQUEST['num-'.$a]."', unit_price = '".$_REQUEST['unit_price-'.$a]."', `location_col_id` = '".$_REQUEST['location_col_id-'.$a]."'";
                $conn->query($sql);
            }
            $a++;
        }
        header("location:index.php?option=farming&task=def");
	}



}

?>