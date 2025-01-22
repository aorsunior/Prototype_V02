<?php

class batch
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
                <h2>Batch</h2>
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
                <input type='button' value='Add' onclick='window.open("index.php?option=batch&task=edit&id=0","_self")'>
                <?php
				}
				if ($acl > '3') {
				?>
                <input type='button' value='Print' onclick='window.open("print.php?cat=batch&typ=all","_self")'>
                <?php
				}
				?>
                <input type="hidden" name="option" value="batch">
                <input type="hidden" name="task" value="def">
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>No</th>
                            <th class='text-center'>Doc ID</th>
                            <th class='text-center'>Name</th>
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
                        $sql = "select `batch`.`name` as `name`, `batch`.`id` as `id`, `batch`.`status` as `status`, `batch`.`date` as `date`, `batch`.`uaid` as `uaid`, `batch`.`uid` as `uid`, `batch`.`adate` as `adate` 
						from `batch`
                        where '1' = '1'";
                        if ($searcher <> null) 
                        {
                            $sql = $sql." and action like '%".$searcher."%' ";
                        }
                        if ($status > -1) 
                        {
                            $sql = $sql." and `batch`.`status` = '".$status."' ";
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
							echo $conn->get_doc_code("BATCH", $cdr['date'], $cdr['id']);
                            echo "</td>";
                            echo "<td class='text-start'>";
                            echo $cdr['name'];
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
                            echo "</td>";
                            if (($acl == '2') or ($acl > '5')) {
                            echo "<td class='text-center'>";
                            echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=batch&task=det&id=".$cdr['id']."\",\"_self\")' />";
							if ($cdr['status'] <> 2)
                            {
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=batch&task=edit&id=".$cdr['id']."\",\"_self\")' />";
                            echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=batch&task=del&id=".$cdr['id']."&stat=".$dss."&date=".$cdr['date']."\")' />";
							}
							if ($cdr['status'] == 1)
                            {
                            echo "<input type='button' value='Approve' onclick='window.open(\"index.php?option=batch&task=del&id=".$cdr['id']."&stat=2&date=".$cdr['date']."\",\"_self\")' />";
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
			$name = "";
			$detail = "";
            $date = date("Y-m-d");
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from batch where id = '".$id."'";
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $name = $cdr['name'];
                $detail = $cdr['detail'];
                $date = $cdr['date'];
                $uid = $cdr['uid'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Batch</h2>
                <form action="index.php" method="get">
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'><?php echo $head;?> batch Data</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>
                            <input name='name' value='<?php echo $name;?>'>
                        </td>
                    </tr>
                    <tr>
                        <td>Detail</td>
                        <td>
                            <input  name='detail' value='<?php echo $detail;?>'>
                        </td>
                    </tr>
                    <tr>
                        <td>User</td>
                        <td>
                            <?php
								if ($id == 0)
								{
									echo $_SESSION['uname'];
								}
								else
								{
									echo $conn->get_user_name($uid, 2);
								}
							?>
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
                            <input type="button" value="Back" onclick="window.open('index.php?option=batch&task=def','_self')">
                            <input type="hidden" name="option" value="batch">
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
                    $sql = "select *, `inventory`.`name` as `name`
                    , `inventory`.`id` as `stid`
                    , (
                    select `num` from `batch_detail` 
                    where `batch_detail`.`status` = '1'
                    and `batch_detail`.`inventory_id` = `inventory`.`id`
                    and `batch_detail`.`batch_id` = '".$id."'
                    ) as `num`
                    from `inventory`
                    where `inventory`.`status` = '1'
					and `inventory`.`typ_id` = '".$qq[$aq]."'";
					// echo $sql;
                    $res = $conn->query($sql);
					$b = 1;
                    while ($cdr = $res->fetch()) 
                    {
                        echo "<tr>";
                        echo "<td class='text-center'>";
                        echo $b;
                        echo "</td>";
                        echo "<td class='text-start'>";
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
        $name = $_REQUEST['name'];
        $detail = $_REQUEST['detail'];
        $date = $_REQUEST['date'];
        if ($id > 0) 
        {
            $sql = "update `batch` set `name` = '".$name."', `detail` = '".$detail."', `date` = '".$date."' where `id` = '".$id."'";
            $conn->query($sql);
            $sql = "update `batch_detail` set `status` = '0' where `batch_id` = '".$id."'";
            $conn->query($sql);
			$code = $conn->get_doc_code("BATCH", $date, $id);
            $conn->save_logs("Edit Batch > ".$code, $_SESSION['uid'], $id);
        }
        else 
        {
            $sql = "insert into `batch` set `name` = '".$name."', `detail` = '".$detail."', `date` = '".$date."', `uid` = '".$_SESSION['uid']."'";
            $id = $conn->query_lastid($sql);
			$code = $conn->get_doc_code("BATCH", $date, $id);
            $conn->save_logs("Add Batch > ".$code, $_SESSION['uid'], $id);
        }
        $limit = $_REQUEST['limit'];
        $a = 1;
        while ($a < $limit) 
        {
            if ($_REQUEST['num-'.$a] > 0) 
            {
                $sql = "insert into `batch_detail` set `batch_id` = '".$id."', `inventory_id` = '".$_REQUEST['id-'.$a]."', `num` = '".$_REQUEST['num-'.$a]."', `typ` = '".$_REQUEST['typ-'.$a]."'";
                $conn->query($sql);
            }
            $a++;
        }
        header("location:index.php?option=batch&task=def");
    }
 
    function del()
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
        $stat = $_REQUEST['stat'];
        $date = $_REQUEST['date'];
		$code = $conn->get_doc_code("BATCH", $date, $id);
        if ($stat == 2)
        {
            $sql = "UPDATE `batch` SET `status` = '".$stat."', `uaid` = '".$_SESSION['uid']."', `adate` = '".date('Y-m-d')."' where `id` = '".$id."' ";
            $conn->save_logs("Approve Batch > ".$code, $_SESSION['uid'], $id);
        }
        else
        {
            $sql = "UPDATE `batch` SET `status` = '".$stat."' WHERE `id` = '".$id."' ";
            if ($stat == 1)
            {
                $conn->save_logs("Active Batch > ".$code, $_SESSION['uid'], $id);
            }
            elseif ($stat == 0)
            {
                $conn->save_logs("In-Active Batch > ".$code, $_SESSION['uid'], $id);
            }
        }
        $conn->query($sql);
        header("location:index.php?option=batch&task=def");
    }
 
    function det()
    {
        $conn = new connect();
        $id = $_REQUEST['id'];
		$sql = "select * from batch where id = '".$id."'";
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$name = $cdr['name'];
			$detail = $cdr['detail'];
			$date = $cdr['date'];
			$uid = $cdr['uid'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Batch Detail</h2>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <td colspan='2' class='text-center'>Batch Data</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>
							<?php echo $name;?>
                        </td>
                    </tr>
                    <tr>
                        <td>Detail</td>
                        <td>
							<?php echo $detail;?>
                        </td>
                    </tr>
                    <tr>
                        <td>User</td>
                        <td>
                            <?php
								echo $conn->get_user_name($uid, 2);
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
                        <td colspan='2' class='text-center'>
                            <input type="button" value="Back" onclick="window.open('index.php?option=batch&task=def','_self')">
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
					$sql = "select `inventory`.`name` as `name`, `batch_detail`.`num` as `num` 
                    from `inventory`, `batch_detail`
                    where `inventory`.`status` = '1'
					and `batch_detail`.`status` = '1'
                    and `batch_detail`.`inventory_id` = `inventory`.`id`
                    and `batch_detail`.`batch_id` = '".$id."'
					and `inventory`.`typ_id` = '".$qq[$aq]."'";
					//echo $sql;
                    $res = $conn->query($sql);
					$b = 1;
                    while ($cdr = $res->fetch()) 
                    {
                        echo "<tr>";
                        echo "<td class='text-center'>";
                        echo $b;
                        echo "</td>";
                        echo "<td class='text-start'>";
                        echo $cdr['name'];
                        echo "</td>";
                        echo "<td>";
                        echo $cdr['num'];
                        echo "</td>";
                        echo "</tr>";
                        $a++;
                        $b++;
                    }
                    ?>
                </table>
                </div>
			<?php
				$aq++;
				}
			?>
            </div>
        </div>
        <?php
    }

}

?>