<?php

class customer
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
                <h2>Customer</h2>
                <form action='index.php' method='get'>
                <input name='searcher' value='<?php echo $searcher;?>'>
                <input type='submit' value='Search'>			
				<?php
					if (($acl == '2') or ($acl > '5'))
					{
					?>
						<input type='button' value='Add' onclick='window.open("index.php?option=customer&task=edit&id=0","_self")'>
					<?php
					}
				?>
                <input type='hidden' name='option' value='customer'>
                <input type='hidden' name='task' value='def'>


                </form>
                <table id='datatable' class='table table-bordered table-striped'>
                    <thead>
                        <tr>
                            <th class='text-center'>Id</th>
                            <th class='text-center'>Name</th>
                            <th class='text-center'>Address</th>
							<th class='text-center'>Contact</th>
                            <th class='text-center'>Status</th>
                <?php
					if (($acl == '2') or ($acl > '5'))
					{
					?>
                            <th class='text-center'>Action</th>
					<?php
					}
				?>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = 'select `customer`.`id` as `id`, `customer`.`name` as `name`, `customer`.`address` as `address`, `customer`.`province` as `province`, `customer`.`zip` as `zip`, `customer`.`tel` as `tel`, `customer`.`mail` as `mail` , `customer`.`tax_id` as `tax_id`, `customer`.`status` as `status` 
						from `customer`';
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
                            echo $cdr['name'];
                            echo "</td>";
							echo "<td>";
                            echo $cdr['address'];
                            echo "&nbsp;";
							echo $cdr['province'];
                            echo "&nbsp;";
							echo $cdr['zip'];
                            echo "</td>";
                            echo "<td>";
                            echo "Tel. : ";
							echo $cdr['tel'];
							echo "<br />";
							echo "Mail : ";
							echo $cdr['mail'];
                            echo "<br />";
							echo "Tax ID : ";
							echo $cdr['tax_id'];
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
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=customer&task=edit&id=".$cdr['id']."\",\"_self\")' />";
						    echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=customer&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
						    echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=customer&task=det&id=".$cdr['id']."\",\"_self\")' />";
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
            $head = "Add";
            $name = "";
            $address = "";
            $province = "";
            $zip = "";
            $tel = "";
            $mail = "";
            $tax_id = "";
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from `customer` where `id` = '".$id."'";
            $conn = new connect();
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $name = $cdr['name'];
                $address = $cdr['address'];
                $province = $cdr['province'];
                $zip = $cdr['zip'];
                $tel = $cdr['tel'];
                $mail = $cdr['mail'];
                $tax_id = $cdr['tax_id'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Customer</h2>
                <form action='index.php' method='get'>
				<table class='table'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'><?php echo $head;?> Customer Data</th>
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
							<td>Address</td>
							<td>
								<input name='address' value='<?php echo $address;?>'>
							</td>
						</tr>
                        <tr>
							<td>Province</td>
							<td>
								<input name='province' value='<?php echo $province;?>'>
							</td>
						</tr>
                        <tr>
							<td>Zip</td>
							<td>
								<input name='zip' value='<?php echo $zip;?>'>
							</td>
						</tr>
                        <tr>
							<td>Tel</td>
							<td>
								<input name='tel' value='<?php echo $tel;?>'>
							</td>
						</tr>
                        <tr>
							<td>Mail</td>
							<td>
								<input name='mail' value='<?php echo $mail;?>'>
							</td>
						</tr>
                        <tr>
							<td>Tax ID</td>
							<td>
								<input name='tax_id' value='<?php echo $tax_id;?>'>
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='hidden' name="option" value='customer'>
								<input type='hidden' name="task" value='save'>
								<input type='hidden' name="id" value='<?php echo $id;?>'>
								<input type='submit' value='Save'>
								<input type='button' value='Back' onclick='window.open("index.php?option=customer&task=def","_self")'>
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
		$sql = "update `customer` set `status` = '".$_REQUEST['stat']."' where `id` = '".$id."'";
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=customer&task=def');
    }

    function save() 
    {
        $id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
        $address = $_REQUEST['address'];
        $province = $_REQUEST['province'];
        $zip = $_REQUEST['zip'];
        $tel = $_REQUEST['tel'];
        $mail = $_REQUEST['mail'];
        $tax_id = $_REQUEST['tax_id'];
		if ($id == 0) 
		{
			$sql = "insert into `customer` set `name` = '".$name."', `address` = '".$address."', `province` = '".$province."', `zip` = '".$zip."', `tel` = '".$tel."', `mail` = '".$mail."', `tax_id` = '".$tax_id."'";
		}
		else 
		{
			$sql = "update `customer` set `name` = '".$name."', `address` = '".$address."', `province` = '".$province."', `zip` = '".$zip."', `tel` = '".$tel."', `mail` = '".$mail."', `tax_id` = '".$tax_id."' where `id` = '".$id."'";
		}
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=customer&task=def');
    }

    function det() 
    {
        $id = $_REQUEST['id'];
		$sql = "select * from `customer` where `id` = '".$id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$name = $cdr['name'];
            $address = $cdr['address'];
            $province = $cdr['province'];
            $zip = $cdr['zip'];
            $tel = $cdr['tel'];
            $mail = $cdr['mail'];
            $tax_id = $cdr['tax_id'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2>Customer</h2>
                <table class='table'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'>Customer Data</th>
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
							<td>Address</td>
							<td>
								<?php echo $address;?>
							</td>
						</tr>
                        <tr>
							<td>Province</td>
							<td>
								<?php echo $province;?>
							</td>
						</tr>
                        <tr>
							<td>Zip</td>
							<td>
								<?php echo $zip;?>
							</td>
						</tr>
                        <tr>
							<td>Tel</td>
							<td>
								<?php echo $tel;?>
							</td>
						</tr>
                        <tr>
							<td>Mail</td>
							<td>
								<?php echo $mail;?>
							</td>
						</tr>
                        <tr>
							<td>Tax ID</td>
							<td>
								<?php echo $tax_id;?>
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='button' value='Back' onclick='window.open("index.php?option=customer&task=def","_self")'>
							</td>
						</tr>
					</tbody>
				</table>
                </div>
            </div>
        </div>
        <?php
    }
}

?>