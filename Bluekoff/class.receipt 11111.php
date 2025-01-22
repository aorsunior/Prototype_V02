<?php 
class receipt{
    function def(){
        ?>
        
        <div class="container">
            <div class ="row">
                <div class = "col-12">
                <h1>Receipt</h1>
                <form action="index.php" method="get">
                        <input name="searcher">
                        <input type="submit" value="Search">
                        <input type='button' value='Add' onclick='window.open("index.php?option=receipt&task=edit&id=0&select=0","_self")' />
                        <input type="hidden" name="option" value="receipt">
                        <input type="hidden" name="task" value="def" >
                    </from>
                </div>
                    <table id="datatable" class="table table-bordered table-striped" >
                        <thead>
                            <tr>
                                <th class="text-center"> No </th>
                                <th class="text-center"> Customer </th>
                                <th class="text-center"> Date </th>
                                <th class="text-center"> Status</th>
                                <th class="text-center"> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                 $sql = "SELECT dvr.id AS id, dvr.date AS date, cm.name AS customer, dvr.status AS status FROM receipt AS dvr INNER JOIN customer_data AS cm ON dvr.customer_id = cm.id ORDER BY id DESC";
                                 $conn = new connect();
                                 $a = 1;
                                 $res = $conn->query($sql);
                                 while($cdr=$res->fetch()){
                                    echo "<tr>";
                                    echo "<td class='text text-center'>";
                                    echo $a ;
                                    echo "</td>";
                                    echo "<td class='text text-center'>";
                                    echo $cdr['customer'];
                                    echo "</td>";
                                    echo "<td class='text text-center'>";
                                    echo $cdr['date'];
                                    echo "</td>";
                                    if($cdr['status']==1){
                                        echo "<td class='text text-success text-center'>";
                                        echo "พร้อมใช้งาน";
                                        echo "</td>";
                                        $active = "active";
                                    }
                                    else{
                                        echo "<td class='text text-danger text-center'>";
                                        echo "ไม่พร้อมใช้งาน";
                                        echo "</td>";
                                        $active = "";
                                    }
                                     
                                    echo "<td>";
						            echo "<input class='btn btn-primary btn-sm' type='button' value='Detail' onclick='window.open(\"index.php?option=receipt&task=det&id=".$cdr['id']."\",\"_self\")' />";
                                    if($cdr['status'] == 1){
                                    echo "<input class='btn btn-warning btn-sm' type='button' value='Edit' onclick='window.open(\"index.php?option=receipt&task=edit&id=".$cdr['id']."\",\"_self\")' />";
                                    echo "<input class='btn btn-danger btn-sm $active' type='button' value='active' onclick='confirm_del(\"index.php?option=receipt&task=del&id=".$cdr['id']."&stat=".$cdr['status']."\",\"_self\")' />";
                                    }
                                    if($cdr['status'] < 1){
                                        echo "<input class='btn btn-warning btn-sm' type='button' value='Edit' onclick='window.open(\"index.php?option=receipt&task=edit&id=".$cdr['id']."\",\"_self\")' />";
                                        echo "<input class='btn btn-danger btn-sm $active' type='button' value='active' onclick='confirm_del(\"index.php?option=receipt&task=del&id=".$cdr['id']."&stat=".$cdr['status']."\",\"_self\")' />";
                                        echo "</td>";
                                        }
                                    echo "</tr>";
                                    $a++;
                                 }
                            ?>
                        </tbody>
        <?php 
    }
    function det(){
        $id=$_REQUEST["id"];
        $sql = "SELECT receipt.id AS id, receipt.uid AS uid, receipt.date AS date, receipt.status AS status, us.firstname AS fn, us.lastname AS ln, cm.name AS nc, receipt.so_id AS so
        FROM receipt
        INNER JOIN users AS us ON receipt.uid = us.id
        INNER JOIN customer_data AS cm ON receipt.customer_id = cm.id
        WHERE receipt.id = $id";
        $conn = new connect();
        $res = $conn->query($sql);
        while($cdr=$res->fetch()){
            $so_id = $cdr['so'];
            $customer = $cdr['nc'];
            $uid = $cdr["uid"];
            $date = $cdr['date'];
            $fsname = $cdr['fn'];
            $lsname = $cdr['ln'];
            $stat = $cdr['status'];
        }
        ?>
        <div class="container">
            <div class ="row">
                <div class = "col-12">
                <form action='index.php' method='get'>
                    <table class= "table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">Detail Data receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>เลขที่เอกสาร</td>
                                <td><?php echo $id ;?></td>
                            </tr>
                            <tr>
                                <td>เลขที่เอกสารแจ้งหนี้</td>
                                <td><?php echo $so_id ;?></td>
                            </tr>
                            <tr>
                                <td>ลูกค้า</td>
                                <td><?php echo $customer ;?></td>
                            </tr>
                            <tr>
                                <td>ผู้จัดทำ</td>
                                <td><?php echo $fsname, "&nbsp", $lsname  ;?></td>
                            </tr>
                            <tr>
                                <td>วันที่จัดทำ</td>
                                <td><?php echo $date ;?></td>
                            </tr>
                            <tr>
							<td colspan='2' class='text-center'>
								<input class='btn btn-secondary btn-sm' type='button' value='Back' onclick='window.open("index.php?option=receipt&task=def","_self")' />
							</td>
						</tr>
                        </tbody>
                    </table>
                    <table class= "table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="1" class="text-center">id</th>
                                <th colspan="1" class="text-center">name</th>
                                <th colspan="1" class="text-center">price</th>
                                <th colspan="1" class="text-center">quantity</th>
                                <th colspan="1" class="text-center">total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $a = 1;
                            $total = 0;
                            $sql = "select `product`.`name` as name
                    , `product`.`id` as `stid`
                    , `receipt_detail`.`quantity` as `num`
                    , `product`.`unit` as `unit`
                    , `receipt_detail`.`price` as `price`
                    from `product`, `receipt_detail`
				    where `receipt_detail`.`status` > 0
                    and `receipt_detail`.`product_id` = `product`.`id`
                    and `receipt_detail`.`receipt_id` = '".$id."'";
                            $conn = new connect();
                            $res = $conn->query($sql);
                            while($cdr=$res->fetch()){
                                echo "<tr>";
                                echo "<td class='text-center'>";
                                echo $a;
                                echo "</td>";
                                echo "<td class='text-center'>";
                                echo $cdr['name'];
                                echo "</td>";
                                echo "<td class='text-center'>";
                                echo $cdr['price'];
                                echo "</td>";
                                echo "<td class='text-end'>";
                                echo $cdr['num'];
                                echo "&nbsp";
                                echo $cdr['unit'];
                                echo "</td>";
                                echo "<td class='text-end'>";
                                echo number_format($cdr['num'] * $cdr['price'],2) ;
                                echo "</td>";
                                echo "</tr>";
                                $a++;
                                $total += $cdr['num'] * $cdr['price'] ;
                                }
                            ?>
                            <tr>
                                <td colspan="4" class="text-start"> Total Net</td>
                                <td colspan="4" class="text-end"><?php echo number_format($total,2); ?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </from>
                </div>
            </div>
        </div>
        
        <?php 
    }
    function edit(){
        $id=$_REQUEST["id"];
        if($id == 0){
            $select = $_REQUEST['select'];
        }
        if($id == 0 && $select == 0){
            $sql = "SELECT dvr.id AS id, dvr.date AS date FROM so AS dvr WHERE NOT EXISTS (SELECT 1 FROM receipt AS rcp WHERE rcp.so_id = dvr.id) AND status = 2 ORDER BY id DESC";
            $conn = new connect();
            $res = $conn->query($sql);
            echo "<div class='container'>";
            echo "<div class='row'>";
            echo "<div class='col-12'>";
            echo "<h1>Select So to receipt</h1>";
            echo "<table id='datatable' class='table table-bordered table-striped'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th class='text-center'> No </th>";
            echo "<th class='text-center'> Date </th>";
            echo "<th class='text-center'> Action</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($cdr = $res->fetch()){
                echo "<tr>";
                echo "<td>";
                echo $cdr['id'];
                echo "</td>";
                echo "<td>";
                echo $cdr['date'];
                echo "</td>";
                echo "<td>";
                echo '<input class="btn btn-success btn-sm" type="button" value="Select" onclick="window.open(\'index.php?option=receipt&task=edit&id=0&select=1&so_id=' . $cdr['id'] . '\', \'_self\')" />';
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";      
        }
        else if($id == 0 && $select == 1){
            $func = "Add receipt data";
            $so_id = $_REQUEST['so_id'];
            $date = date("Y-m-d");
            $sql = "SELECT firstname, lastname FROM users WHERE id = ".$_SESSION['uid']."";
            $conn = new connect();
            $res = $conn->query($sql);
            $cdr = $res->fetch();
            $firstname = $cdr['firstname'];
            $lastname = $cdr['lastname'];
            $sql = "SELECT customer_id FROM so WHERE id = '$so_id'";
            $res = $conn->query($sql);
            $cdr = $res->fetch();
            $cm_id = $cdr['customer_id'];
        }
        else{
            $func = "Edit Data receipt";
            $sql = "SELECT receipt.so_id AS so_id, receipt.uid AS uid, receipt.date AS date, us.firstname AS firstname, us.lastname AS lastname, cm.id AS cm_id FROM receipt INNER JOIN users AS us ON receipt.uid = us.id INNER JOIN customer_data AS cm ON receipt.customer_id = cm.id WHERE receipt.id = $id";
            $conn = new connect();
            $res = $conn->query($sql);
            $cdr = $res->fetch(); 
            $date = $cdr['date'];
            $cm_id = $cdr['cm_id'];
            $firstname = $cdr['firstname'];
            $lastname = $cdr['lastname'];
            $so_id = $cdr['so_id'];
        }
        if($id > 0 or $select == 1){
        ?>
            <div class="container">
            <div class ="row">
                <div class = "col-12">
                <form action='index.php' method='get'>
                    <table class= "table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center"><?php echo $func; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($id > 0){
                                echo "<tr>";
                                echo "<td>เลขที่เอกสาร</td>";
                                echo "<td>$id</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr>
                                <td>เลขที่เอกสารสั่งซื้อ</td>
                                <td><?php echo $so_id ;?></td>
                            </tr>
                            <tr>
                                <td>ลูกค้า</td>
                                <td>
                                <?php
                                    echo "<select class='form-control-sm' name='customer' required>";
                                    $sql = "SELECT * FROM customer_data WHERE status = 1 ORDER BY CASE WHEN id = $cm_id THEN 0 ELSE 1 END, id; ";
                                    $conn = new connect();
                                    $res = $conn->query($sql);
                                    while($cdrs=$res->fetch()){
                                        echo "<option value=".$cdrs['id']." >".$cdrs['name']."</option>";
                                    }
                                ?>

                                </td>
                            </tr>
                            <tr>
                                <td>ผู้จัดทำ</td>
                                <td><?php echo $firstname, "&nbsp", $lastname ;?></td>
                            </tr>
                            <tr>
                                <td>วันที่จัดทำ</td>
                                <td><input class="form-control-sm" type="date" name="date" value="<?php echo $date ;?>"></td>
                            </tr>
                            <tr>
							<td colspan='2' class='text-center'>
								<input type='hidden' name="option" value='receipt' />
								<input type='hidden' name="task" value='save' />
								<input type='hidden' name="id" value='<?php echo $id ;?>' />
								<input class='btn btn-primary btn-sm' type='submit' value='Save' />
								<input class='btn btn-secondary btn-sm' type='button' value='Back' onclick='window.open("index.php?option=receipt&task=def","_self")' />
							</td>
						</tr>
                        </tbody>
                    </table>
                    <table class= "table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="1" class="text-center">id</th>
                                <th colspan="1" class="text-center">name</th>
                                <th colspan="1" class="text-center">price</th>
                                <th colspan="1" class="text-center">quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if($id == 0){
                            $a = 1;
                            $sql = "SELECT dvr.product_id AS pd_id, dvr.price AS price, dvr.quantity AS num, pd.name  AS name, pd.unit AS unit FROM so_detail AS dvr INNER JOIN product AS pd ON dvr.product_id = pd.id  WHERE dvr.dvr_id  = $so_id AND dvr.status = 1";
                            $conn = new connect();
                            $res = $conn->query($sql);
                            while($cdr=$res->fetch()){
                                echo "<tr>";
                                echo "<td>";
                                echo $a;
                                echo "</td>";
                                echo "<td>";
                                echo $cdr['name'];
                                echo "</td>";
                                echo "<td>";
                                echo $cdr['price'];
                                echo "</td>";
                                echo "<td>";
                                echo "<input class='form-control-sm' type='number' name='num_".$a."' value='".$cdr['num']."' Min='0'>";
                                echo "&nbsp";
                                echo $cdr['unit'];
                                echo "</td>";
                                echo "<input type='hidden' name='idprd_".$a."' value='".$cdr['pd_id']."'>";
                                echo "<input type='hidden' name='price_".$a."' value='".$cdr['price']."'>";
                                $a++;
                            }
                            echo "<input type='hidden' name='break' value='".$a."'>";
                            echo "<input type='hidden' name='so_id' value='".$so_id."'>";
                        }
                        else if($id > 0){
                            $a = 1;
                            $sql = "SELECT rcpd.product_id AS pd_id, rcpd.price AS price, rcpd.quantity AS num, pd.name  AS name, pd.unit AS unit  FROM receipt_detail AS rcpd INNER JOIN product AS pd ON rcpd.product_id = pd.id  WHERE rcpd.receipt_id  = $id ";
                            $conn = new connect();
                            $res = $conn->query($sql);
                            while($cdr=$res->fetch()){
                                echo "<tr>";
                                echo "<td>";
                                echo $a;
                                echo "</td>";
                                echo "<td>";
                                echo $cdr['name'];
                                echo "</td>";
                                echo "<td>";
                                echo $cdr['price'];
                                echo "</td>";
                                echo "<td>";
                                echo "<input class='form-control-sm' type='number' name='num_".$a."' value='".$cdr['num']."' Min='0'>";
                                echo "&nbsp";
                                echo $cdr['unit'];
                                echo "</td>";
                                echo "<input type='hidden' name='idprd_".$a."' value='".$cdr['pd_id']."'>";
                                echo "<input type='hidden' name='price_".$a."' value='".$cdr['price']."'>";
                                $a++;
                            }
                            echo "<input type='hidden' name='break' value='".$a."'>";
                            echo "<input type='hidden' name='so' value='".$so_id."'>";
                        }
                        ?>
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>
        </div>
                    
        <?php
        }
        ?>
        
<?php   
    }
    function del(){
        $id = $_REQUEST['id'];
        $stat = $_REQUEST['stat'];
        if($stat == 1){
		    $sql = "update `receipt` set `status` = '0' where `id` = '".$id."'";
		    $conn = new connect();
		    $conn->query($sql);
            $conn->save_logs("In-Active Receipt_id ".$id."", $_SESSION['uid']);
		    header('location:index.php?option=receipt&task=def');
        }
        else if($stat == 0){
            $sql = "update `receipt` set `status` = '1' where `id` = '".$id."'";
		    $conn = new connect();
		    $conn->query($sql);
            $conn->save_logs("Active Receipt_id ".$id."", $_SESSION['uid']);
		    header('location:index.php?option=receipt&task=def');
        }
    }

    function save(){
        $id = $_REQUEST['id'];
        $date = $_REQUEST['date'];
        $break = $_REQUEST['break'];
        $limit = $_REQUEST['limit'];
        $customer = $_REQUEST['customer'];
        $so_id = $_REQUEST['so_id'];
        $chech_data_record = 1;
        $a = 1;
        $conn = new connect();
        
        while($a < $break){
            if($_REQUEST['num_'.$a] == 0){
                $chech_data_record++;
            }
            $a++;
        }
        if($id == 0 && $chech_data_record <> $break){
            $sql = "INSERT INTO receipt (so_id, customer_id, uid, date, status) VALUE ('".$_REQUEST['so_id']."','".$_REQUEST['customer']."', '".$_SESSION['uid']."', '".$_REQUEST['date']."', '1')";
            $id = $conn->query_lastid($sql);
            
            $a = 1;
            while($a < $break){
                if($_REQUEST['num_'.$a] > 0){
                    $sql = "INSERT INTO receipt_detail set receipt_id = '$id', product_id = '".$_REQUEST['idprd_'.$a]."', price = '".$_REQUEST['price_'.$a]."', quantity = '".$_REQUEST['num_'.$a]."', status = '1'";
                    $conn->query($sql);
                }
                $a++;
            }
            header("location:index.php?option=receipt&task=def");
            $conn->alert("บันทึกข้อมูลสำเร็จ", 1);
            $conn->save_logs("Add Receipt_id ".$id."", $_SESSION['uid']);
        }
        else if($id > 0 && $chech_data_record <> $break){
            $a = 1;
            $sql = "UPDATE receipt SET customer_id = '$customer' WHERE id = $id";
            $conn->query($sql);
            while($a < $break){
                if($_REQUEST['num_'.$a] > 0){
                    $price = $_REQUEST['price_'.$a];
                    $quantity = $_REQUEST['num_'.$a];
                    $idprd = $_REQUEST['idprd_'.$a];
                    $sql = "UPDATE receipt_detail SET price = '$price', quantity = '$quantity', status = '1' WHERE receipt_id = '$id' AND product_id = '$idprd'";
                    $conn->query($sql);
                }
                else{
                    $price = $_REQUEST['price_'.$a];
                    $quantity = $_REQUEST['num_'.$a];
                    $idprd = $_REQUEST['idprd_'.$a];
                    $sql = "UPDATE receipt_detail SET price = '$price', quantity = '$quantity', status = '0' WHERE receipt_id = '$id' AND product_id = '$idprd'";
                    $conn->query($sql);
                }
                $a++;
            }        
            header("location:index.php?option=receipt&task=def");
            $conn->alert("บันทึกข้อมูลสำเร็จ", 1);
            $conn->save_logs("Edit Receipt_id ".$id."", $_SESSION['uid']);
        }
        else if($chech_data_record == $break){
            header("location:index.php?option=receipt&task=edit&id=$id");
            $conn->alert("กรอกข้อมูลอย่างน้อย 1 Record", 2);
        }
    }
}

?>