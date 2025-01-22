<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Project</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
			<?php
            require_once('class.connect.php');
			if (isset($_SESSION['uid']))
			{
				$sql = "select * from `app`, `acl`, `uig` where `app`.`status` ='1' and `acl`.`status` = '1' and `uig`.`status` = '1' and `acl`.`appid` = `app`.`id` and `acl`.`ugid` = `uig`.`ugid` and `uig`.`uid` = '".$_SESSION['uid']."' group by `app`.`id` order by `app`.`appgroup`, `app`.`name`";
				$conn = new connect();
				$res = $conn->query($sql);
				$a = -1;
				$b = $a;
				while ($cdr = $res->fetch())
				{
					if ($a <> $cdr['appgroup']) 
					{
						if ($b <> $a) 
						{
							echo "</ul></li>";
							$b = $a;
						}
						?>
						<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<?php
							echo $conn->get_app_group($cdr['appgroup']);
						?>
						</a>
						<ul class="dropdown-menu">
						<?php
						$a = $cdr['appgroup'];
					}
					echo '<li>';
					echo '<a class="dropdown-item" href="index.php?option='.$cdr['dir'].'&task=def">';
					echo $cdr['name'];
					echo '</a>';
					echo '</li>';
				}
			?>
				</ul></li>
				<li class="nav-item">
				<a class="nav-link" aria-current="page" href="index.php?option=logs&task=logout">Log out</a>
				</li>
			<?php
			}
			else 
			{
			?>
				<li class="nav-item">
				<a class="nav-link" aria-current="page" href="index.php?option=logs&task=login_form">Log in</a>
				</li>
			<?php
			}
		?>
        </ul>
        </div>
    </div>
</nav>