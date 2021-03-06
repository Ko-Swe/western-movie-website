<?php
	@session_start();
	if(!(isset($_SESSION['access']) && $_SESSION['access']==2))
	{
		header('location:index.php');
	}

	if(isset($_POST['film_name']) && isset($_POST['film_content']) && isset($_POST['film_category']) && isset($_POST['film_genere']) && isset($_POST['film_refrence_title']) && isset($_POST['film_refrence_url']) && isset($_POST['film_year']))
	{
		$film_name = ltrim(rtrim($_POST['film_name']));
		$film_content = ltrim(rtrim($_POST['film_content']));
		$film_category = ltrim(rtrim($_POST['film_category']));
		$film_genere = ltrim(rtrim($_POST['film_genere']));
		$film_year = ltrim(rtrim($_POST['film_year']));
		$film_refrence_title = ltrim(rtrim($_POST['film_refrence_title']));
		$film_refrence_url = ltrim(rtrim($_POST['film_refrence_url']));

		if(empty($film_name) || empty($film_content) || empty($film_category) || empty($film_genere) || empty($film_refrence_title) || empty($film_refrence_url) || empty($film_year))
		{
			$panel_error = 1;
		}
		elseif(strlen($film_name)>100 || strlen($film_content)>20000 || strlen($film_refrence_title)>100 || strlen($film_refrence_url)>255)
		{
			$panel_error = 2;
		}
		else
		{
			if(isset($_FILES["film_file"]))
			{
				if ($_FILES["film_file"]["size"] > 8388608)
				{
					$panel_error=3;
				}
				elseif ($_FILES["film_file"]["error"] > 0)
				{
					$panel_error=3;
				}
				else
				{
					$filename="film_" . time() . $_FILES["film_file"]["name"];
					move_uploaded_file($_FILES["film_file"]["tmp_name"],"../film/" . $filename);
					$film_file = $filename;
				}
			}

			if(isset($_FILES["image_file"]))
			{
				if ($_FILES["image_file"]["size"] > 8388608)
				{
					$panel_error=3;
				}
				elseif ($_FILES["image_file"]["error"] > 0)
				{
					$panel_error=3;
				}
				else
				{
					$filename="image_" . time() . $_FILES["image_file"]["name"];
					move_uploaded_file($_FILES["image_file"]["tmp_name"],"../image/" . $filename);
					$image_file = $filename;
				}
			}
			
			if(!isset($image_file))
			{
				$image_file = "Hydrangeas.jpg";
			}
			if(!isset($film_file))
			{
				$film_file = "Wildlife.wmv";
			}

			if(add_film($film_name, $film_content, $film_category, $film_genere, $film_refrence_title, $film_refrence_url, $image_file, $film_file, $film_year))
			{
				$panel_error = 5;
			}
			else
			{
				$panel_error = 4;
			}
		}
	}
	elseif(isset($_POST['film_name']) || isset($_POST['film_content']) || isset($_POST['film_category']) || isset($_POST['film_genere']) || isset($_POST['film_refrence_title']) || isset($_POST['film_refrence_url']) || isset($_POST['film_year']))
	{
		$panel_error = 1;
	}
?>
<h2>?????? ???????? ????</h2>
<div class="form_data">
	<form action="" method="post" class="form" enctype="multipart/form-data">
		<table>
			<tr>
				<td><label for="film_name">?????? ????????</label></td>
				<td><input type="text" name="film_name" id="film_name" maxlength="100"></td>
			</tr>
			<tr>
				<td><label for="film_content">??????????</label></td>
				<td><textarea name="film_content" id="film_content" maxlength="50000"></textarea></td>
			</tr>
			<tr>
				<td><label for="film_category">???????? ????????</label></td>
				<td>
					<select name="film_category" id="film_category">
						<?php
							$rc = read_category();
							if($rc!==false)
							{
								foreach ($rc as $foreach_rc) {
									echo '<option value="' . $foreach_rc['id'] . '">' . $foreach_rc['name'] . '</option>';
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="film_genere">????????</label></td>
				<td>
					<select name="film_genere" id="film_genere">
						<?php
							$rg = read_genere();
							if($rg!==false)
							{
								foreach ($rg as $foreach_rg) {
									echo '<option value="' . $foreach_rg['id'] . '">' . $foreach_rg['name'] . '</option>';
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="film_year">?????? ????????</label></td>
				<td>
					<select name="film_year" id="film_year">
						<?php
							for($i=1990;$i<=2017;$i++) {
								echo '<option value="' . $i . '">' . $i . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="film_refrence_title">?????????? ????????</label></td>
				<td><input type="text" name="film_refrence_title" id="film_refrence_title" maxlength="100"></td>
			</tr>
			<tr>
				<td><label for="film_refrence_url">???????? ????????</label></td>
				<td><input style="text-align:left !important;" type="text" name="film_refrence_url" id="film_refrence_url" maxlength="255"></td>
			</tr>
			<tr>
				<td><label for="image_file">?????????? ????????</label></td>
				<td><input type="file" name="image_file" id="image_file" /></td>
			</tr>
			<tr>
				<td><label for="film_file">????????</label></td>
				<td><input type="file" name="film_file" id="film_file" /></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="?????? ????????"></td>
			</tr>
		</table>
	</form>
	<?php
		if(isset($panel_error))
		{
			switch ($panel_error) {
				case '1':
					echo '<p style="color:#f00">???????? ???????? ???? ???????? ???? ????????.</p>';
				break;
				case '2':
					echo '<p style="color:#f00">???????? ???? ???????????? ?????????????????? ????????????.</p>';
				break;
				case '3':
					echo '<p style="color:#f00">???????? ?????? ?????? ???????? ?????????? ????????????.</p>';
				break;
				case '4':
					echo '<p style="color:#f00">???????? ???????? ?????? ??????.</p>';
				break;
				case '5':
					echo '<p style="color:#0c0">???????? ?????? ????.</p>';
				break;
			}
		}
	?>
	<hr />
	<b>???????? ????:</b>
	<table class="form_database" cellspacing="0" cellpadding="0">
		<tr>
			<td>??????</td>
			<td>??????????????</td>
		</tr>
		<?php
			$rf = read_film();
			if($rf===false)
			{
				echo '<td>???????? ???????? ???? ???????? ??????.</td><td></td>';
			}
			else
			{
				foreach ($rf as $foreach_rf) {
					echo '<tr><td style="font-size:11px;">' . $foreach_rf['title'] . '</td><td><a id="edit" href="panel.php?id=12&film_id=' . $foreach_rf['id'] . '" title="???????????? ????????">???????????? ????????</a><a id="delete" href="page/delete_film.php?film_id=' . $foreach_rf['id'] .'" title="?????? ????????">?????? ????????</a><a target="_blank" id="view" href="../film.php?id=' . $foreach_rf['id'] .'" title="???????????? ????????">???????????? ????????</a></td></tr>';
				}
			}
		?>
	</table>
	<?php
		if(isset($_SESSION['film']))
		{
			unset($_SESSION['film']);
			echo '<p style="color:#0c0">???????????? ?????????? ????.</p>';
		}
	?>
</div>