<?php

class warehouse
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
                <h2>Were House</h2>
                <form action='index.php' method='get'>
                <input name='searcher' value='<?php echo $searcher;?>'>
                <input type='submit' value='Search'>
                <?php
					if (($acl == '2') or ($acl > '5')) {
				?>
                <input type='button' value='Add' onclick='window.open("index.php?option=warehouse&task=edit&id=0","_self")'>
                <input type='button' value='Summary' onclick='window.open("index.php?option=warehouse&task=sum&id=0","_self")'>
                <?php
				}
				if ($acl > '3') {
				?>
                <input type='button' value='Print' onclick='window.open("print.php?cat=warehouse&typ=all","_self")'>
                <?php
				}
				?>
                <input type='hidden' name='option' value='warehouse'>
                <input type='hidden' name='task' value='def'>
                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>Id</th>
                            <th class='text-center'>Name</th>
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
                        $sql = 'select * from `wms`';
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
                            else
                            {
                                echo "In-Active";
                                $ds = "Active";
                                $dss = "1";
                            }
                            echo "</td>";
                            if (($acl == '2') or ($acl > '5')) {
                            echo "<td>";
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=warehouse&task=edit&id=".$cdr['id']."\",\"_self\")' />";
						    echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=warehouse&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
						    echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=warehouse&task=det&id=".$cdr['id']."\",\"_self\")' />";
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
        $id = $_REQUEST['id'];
        if ($id == 0) 
        {
            $name = "";
        }
        else 
        {
            $sql = "select * from `wms` where `id` = '".$id."'";
            $conn = new connect();
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $name = $cdr['name'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Were House</h2>
                <form action='index.php' method='get'>
				<table class='table'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'>Edit Data</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Name</td>
							<td>
								<input name='name' value='<?php echo $name;?>'>
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='hidden' name="option" value='warehouse'>
								<input type='hidden' name="task" value='save'>
								<input type='hidden' name="id" value='<?php echo $id;?>'>
								<input type='submit' value='Save'>
								<input type='button' value='Back' onclick='window.open("index.php?option=warehouse&task=def","_self")'>
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
		$sql = "update `wms` set `status` = '".$_REQUEST['stat']."' where `id` = '".$id."'";
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=warehouse&task=def');
    }

    function save() 
    {
        $id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		if ($id == 0) 
		{
			$sql = "insert into `wms` set `name` = '".$name."'";
		}
		else 
		{
			$sql = "update `wms` set `name` = '".$name."' where `id` = '".$id."'";
		}
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=warehouse&task=def');
    }

    function det() 
    {
        $id = $_REQUEST['id'];
		$sql = "select * from `wms` where `id` = '".$id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$name = $cdr['name'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Were House</h2>
                <table class='table'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'>Warehouse Data</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Name</td>
							<td>
								<?php echo $name;?>
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='button' value='Back' onclick='window.open("index.php?option=warehouse&task=def","_self")'>
							</td>
						</tr>
					</tbody>
				</table>
                </div>
            </div>
            <div class='row'>
                <div class='col-12'>
				<h2>Road</h2>
                <input type='button' value='Add' onclick='window.open("index.php?option=warehouse&task=edit_road&id=0","_self")'>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>Id</th>
                            <th class='text-center'>Name</th>
                            <th class='text-center'>Status</th>
                            <th class='text-center'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "select * from `wms_road` where `wms_road`.`status` = '1' and `wms_road`.`wms_id` = '".$id."'";
                        $conn = new connect();
                        $res = $conn->query($sql);
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
                            if ($cdr['status'] == 1) 
                            {
                                echo "Active";
                                $ds = "In-Active";
                                $dss = "0";
                            }
                            else
                            {
                                echo "In-Active";
                                $ds = "Active";
                                $dss = "1";
                            }
                            echo "</td>";
                            echo "<td>";
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=warehouse&task=edit&id=".$cdr['id']."\",\"_self\")' />";
						    echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=warehouse&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
						    echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=warehouse&task=det&id=".$cdr['id']."\",\"_self\")' />";
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
                    echo "warehouse Summary";
                ?>
                <form action='index.php' method='get'>
                <input name='searcher' value='<?php echo $searcher;?>'>
                <input type='submit' value='Search'>
                <input type='hidden' name='option' value='warehouse'>
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
                        ifnull((select sum(`buy_detail`.`num` * `stock`.`buy`) as tt from `buy`, `buy_detail`, stock where `buy`.`id` = `buy_detail`.`buy_id`
                        and `stock`.`id` = `buy_detail`.`stock_id` and `wms`.`id` = `buy`.`wms_id` group by `buy`.`id`),0) as sum from `wms`";
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
                <center><input type='button' value='Back' onclick='window.open("index.php?option=warehouse&task=def","_self")'></center>
                </div>
            </div>
        </div>
        <?php
    }
}

?>