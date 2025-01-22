<?php

class inventory
{

    function def() 
    {
		$conn = new connect();
		$acl = $conn->check_acl();
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2 class='text-center'>Inventory</h2>
                <form action='index.php' method='get'>		
				<?php
					if (($acl == '2') or ($acl > '5'))
					{
					?>
						<input type='button' value='Add' onclick='window.open("index.php?option=inventory&task=edit&id=0","_self")'>
					<?php
					}
				?>
                </form>
                <br>
                <table id='example1' class='table table-bordered table-striped'>
                    <thead class='def'>
                        <tr>
                            <th class='text-center'>Id</th>
                            <th class='text-center'>Name</th>
							<th class='text-center'>Type</th>
                            <th class='text-center'>Category</th>
                            <th class='text-center'>Buy</th>
                            <th class='text-center'>Sale</th>
                            <th class='text-center'>Critical Point</th>
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
                        $a = 1;
                        $sql = 'select `inventory`.`id` as `id`, `inventory`.`name` as `name`, `inventory_typ`.`name` as `typ`, 
                        `inventory_cat`.`name` as `cat`, `inventory`.`buy` as `buy`, `inventory`.`sale` as `sale`, `inventory`.`cp` as `cp`, 
                        `inventory`.`status` as `status` from `inventory`, `inventory_typ`, `inventory_cat` 
                        where `inventory`.`typ_id` = `inventory_typ`.`id` and `inventory`.`cate_id` = `inventory_cat`.`id`';
                        $conn = new connect();
                        $res = $conn->query($sql);
                        while ($cdr = $res->fetch())
                        {
                            echo "<tr class=def>";
                            echo "<td>";
                            echo $a;
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['name'];
                            echo "</td>";
							echo "<td>";
                            echo $cdr['typ'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['cat'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['buy'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['sale'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['cp'];
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
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=inventory&task=edit&id=".$cdr['id']."\",\"_self\")' />";
						    echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=inventory&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
						    echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=inventory&task=det&id=".$cdr['id']."\",\"_self\")' />";
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
        $id = $_REQUEST['id'];
        if ($id == 0) 
        {
            $head = "Add";
            $name = "";
            $typ = "";
            $category = "";
            $buy = "";
            $sale = "";
            $cp = "";
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from `inventory` where `id` = '".$id."'";
            $conn = new connect();
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $name = $cdr['name'];
                $typ = $cdr['typ_id'];
                $category = $cdr['cate_id'];
                $buy = $cdr['buy'];
                $sale = $cdr['sale'];
                $cp = $cdr['cp'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2 class='text-center'>Inventory</h2><br>
                <form action='index.php' method='get'>
				<table class='table'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'><?php echo $head;?> Inventory Data</th>
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
                            <td>Type</td>
                            <td>
                                <select name='typ'>
                                    <option value='1'>วัตถุดิบ</option>
                                    <option value='2'>สินค้าระหว่างผลิด</option>
                                    <option value='3'>สินค้าพร้อมขาย</option>
                                    <option value='4'>สินค้าสูญเสีย</option>
                                </select>
                            </td>
                    </tr>
                        <tr>
							<td>Category</td>
                            <td>
                                <select name='category'>
                                    <option value='1'>เมล็ดพันธุ์กาแฟ</option>
                                    <option value='2'>เมล็ดกาแฟสด</option>
                                    <option value='3'>เมล็ดกาแฟคั่ว	</option>
                                </select>
                            </td>
						</tr>
                        <tr>
							<td>Buy</td>
							<td>
								<input name='buy' value='<?php echo $buy;?>'>
							</td>
						</tr>
                        <tr>
							<td>Sale</td>
							<td>
								<input name='sale' value='<?php echo $sale;?>'>
							</td>
						</tr>
                        <tr>
							<td>Critical Point</td>
							<td>
								<input name='cp' value='<?php echo $cp;?>'>
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='hidden' name="option" value='inventory'>
								<input type='hidden' name="task" value='save'>
								<input type='hidden' name="id" value='<?php echo $id;?>'>
								<input type='submit' value='Save'>
								<input type='button' value='Back' onclick='window.open("index.php?option=inventory&task=def","_self")'>
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
		$sql = "update `inventory` set `status` = '".$_REQUEST['stat']."' where `id` = '".$id."'";
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=inventory&task=def');
    }

    function save() 
    {
        $id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		$typ = $_REQUEST['typ'];
		$category = $_REQUEST['category'];
        $buy = $_REQUEST['buy'];
        $sale = $_REQUEST['sale'];
        $cp = $_REQUEST['cp'];
		if ($id == 0) 
		{
			$sql = "insert into `inventory` set `name` = '".$name."', `typ_id` = '".$typ."', `cate_id` = '".$category."', `buy` = '".$buy."', `sale` = '".$sale."', `cp` = '".$cp."'";
		}
		else 
		{
			$sql = "update `inventory` set `name` = '".$name."', `typ_id` = '".$typ."', `cate_id` = '".$category."', `buy` = '".$buy."', `sale` = '".$sale."', `cp` = '".$cp."' where `id` = '".$id."'";
		}
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=inventory&task=def');
    }

    function det() 
    {
        $id = $_REQUEST['id'];
		$sql = "select * from `inventory` where `id` = '".$id."'";
		$conn = new connect();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$name = $cdr['name'];
            $typ = $cdr['typ'];
            $category = $cdr['category'];
            $buy = $cdr['buy'];
            $sale = $cdr['sale'];
            $cp = $cdr['cp'];
		}
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2 class='text-center'>Inventory</h2><br>
                <table class='table'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'>Inventory Data</th>
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
							<td>Type</td>
							<td>
								<?php echo $typ;?>
							</td>
						</tr>
                        <tr>
							<td>Category</td>
							<td>
								<?php echo $category;?>
							</td>
						</tr>
                        <tr>
							<td>Buy</td>
							<td>
								<?php echo $buy;?>
							</td>
						</tr>
                        <tr>
							<td>Sale</td>
							<td>
								<?php echo $sale;?>
							</td>
						</tr>
                        <tr>
							<td>Critical Point</td>
							<td>
								<?php echo $cp;?>
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='button' value='Back' onclick='window.open("index.php?option=inventory&task=def","_self")'>
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