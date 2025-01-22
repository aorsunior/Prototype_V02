<?php

class location_col
{

    function def() 
    {
		$conn = new connect();
		$acl = $conn->check_acl();
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2 class='text-center'>Location col</h2>
                <form action='index.php' method='get'>		
				<?php
					if (($acl == '2') or ($acl > '5'))
					{
					?>
						<input type='button' value='Add' onclick='window.open("index.php?option=location_col&task=edit&id=0","_self")'>
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
							<th class='text-center'>Detail</th>
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
                        $sql = 'select `location_col`.`id` as `id`, `location_col`.`name` as `name`, `location_col`.`detail` as `detail`, 
                        `location_col`.`status` as `status` from `location_col`';
                        $conn = new connect();
                        $res = $conn->query($sql);
                        while ($cdr = $res->fetch())
                        {
                            echo "<tr class=def>";
                            echo "<td>";
                            echo $cdr['id'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['name'];
                            echo "</td>";
                            echo "<td>";
                            echo $cdr['detail'];
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
                            echo "<input type='button' value='Edit' onclick='window.open(\"index.php?option=location_col&task=edit&id=".$cdr['id']."\",\"_self\")' />";
						    echo "<input type='button' value='".$ds."' onclick='confirm_del(\"index.php?option=location_col&task=del&id=".$cdr['id']."&stat=".$dss."\")' />";
						    echo "<input type='button' value='Detail' onclick='window.open(\"index.php?option=location_col&task=det&id=".$cdr['id']."\",\"_self\")' />";
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
		$conn = new connect();
        $id = $_REQUEST['id'];
        if ($id == 0) 
        {
            $head = "Add";
            $name = "";
            $detail = "";
			$row_id = 0;
        }
        else 
        {
            $head = "Edit";
            $sql = "select * from `location_col` where `id` = '".$id."'";
            $res = $conn->query($sql);
            while ($cdr = $res->fetch()) 
            {
                $name = $cdr['name'];
                $detail = $cdr['detail'];
                $row_id = $cdr['row_id'];
            }
        }
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2 class='text-center'>Location col</h2><br>
                <form action='index.php' method='get'>
				<table class='table'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'><?php echo $head;?> Location col Data</th>
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
							<td>Row</td>
							<td>
								<select name='location_id'>
								<?php
									$sql = "select * from `location` where `status` = '1'";
									$res = $conn->query($sql);
									while ($cdr = $res->fetch()) 
									{
										$name = $cdr['name'];
										$as = $cdr['id'];
										echo "<optgroup label='".$name."'>";
										$sqls = "select * from `location_road` where `status` = '1' and `location_id` = '".$as."'";
										$ress = $conn->query($sqls);
										while ($cdrs = $ress->fetch()) 
										{
											$name = $cdrs['name'];
											$as = $cdrs['id'];
											echo "<optgroup label='>".$name."'>";
											$sqlsd = "select * from `location_ctn` where `status` = '1' and `road_id` = '".$as."'";
											$ressd = $conn->query($sqlsd);
											while ($cdrsd = $ressd->fetch()) 
											{
												$name = $cdrsd['name'];
												$qid = $cdrsd['id'];
												echo "<optgroup label='>>".$name."'>";
												$sqlsdf = "select * from `location_row` where `status` = '1' and `ctn_id` = '".$as."'";
												$ressdf = $conn->query($sqlsdf);
												while ($cdrsdf = $ressdf->fetch()) 
												{
													$name = $cdrsdf['name'];
													$qid = $cdrsdf['id'];
													$q = "";
													if ($qid == $row_id)
													{
														$q = "selected";
													}
													echo "<option value='".$qid."' ".$q.">".$name."</option>";
												}
												echo "</optgroup>";
											}
											echo "</optgroup>";
										}
										echo "</optgroup>";
									}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Detail</td>
							<td>
								<input name='detail' value='<?php echo $detail;?>'>
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='hidden' name="option" value='location_col'>
								<input type='hidden' name="task" value='save'>
								<input type='hidden' name="id" value='<?php echo $id;?>'>
								<input type='submit' value='Save'>
								<input type='button' value='Back' onclick='window.open("index.php?option=location_col&task=def","_self")'>
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
		$sql = "update `location_col` set `status` = '".$_REQUEST['stat']."' where `id` = '".$id."'";
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=location_col&task=def');
    }

    function save() 
    {
        $id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		$detail = $_REQUEST['detail'];
		$location_id = $_REQUEST['location_id'];
		if ($id == 0) 
		{
			$sql = "insert into `location_col` set `name` = '".$name."', `detail` = '".$detail."', `row_id` = '".$location_id."'";
		}
		else 
		{
			$sql = "update `location_col` set `name` = '".$name."', `detail` = '".$detail."', `row_id` = '".$location_id."' where `id` = '".$id."'";
		}
		$conn = new connect();
		$conn->query($sql);
		header('location:index.php?option=location_col&task=def');
    }

    function det() 
    {
        $id = $_REQUEST['id'];
		$sql = "select * from `location_col` where `id` = '".$id."'";
		$conn = new connect();
		$acl = $conn->check_acl();
		$res = $conn->query($sql);
		while ($cdr = $res->fetch()) 
		{
			$name = $cdr['name'];
            $detail = $cdr['detail'];
            $location_id = $cdr['row_id'];
		}
		$_REQUEST['id'] = $location_id;
		require_once('class.location_row.php');
		$location = new location_row();
		$location->det();
        ?>
        <div class='container'>
            <div class='row'>
                <div class='col-12'>
                <h2 class='text-center'>Location_col</h2><br>
                <table class='table'>
					<thead>
						<tr>
							<th colspan='2' class='text-center'>Location_col Data</th>
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
							<td>Detail</td>
							<td>
								<?php echo $detail;?>
							</td>
						</tr>
						<tr>
							<td colspan='2' class='text-center'>
								<input type='button' value='Back' onclick='window.open("index.php?option=location_col&task=def","_self")'>
							</td>
						</tr>
					</tbody>
				</table>
                </div>
            </div>
        </div>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <?php
    }

}