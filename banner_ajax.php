<?php
session_start();
/* Llamar la Cadena de Conexion*/ 
include ("conn.php");
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	//Elimino producto
	if (isset($_REQUEST['id'])){
		$id_banner=intval($_REQUEST['id']);
		if ($delete=mysqli_query($con,"delete from banner where id='$id_banner'")){
			$message= "Datos eliminados satisfactoriamente";
		} else {
			$error= "No se pudo eliminar los datos";
		}
	}

	$tables="product";
	$sWhere=" ";
	$sWhere.=" ";
	
	
	$sWhere.=" order by productid";
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = 12; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	
	//Count the total number of row in your table*/
	$count_query   = mysqli_query($conn,"SELECT count(*) AS numrows FROM $tables  $sWhere ");
	if ($row= mysqli_fetch_array($count_query))
	{
	$numrows = $row['numrows'];
	}
	else {echo mysqli_error($conn);}
	$total_pages = ceil($numrows/$per_page);
	$reload = './productslist.php';
	//main query to fetch the data
	$query = mysqli_query($conn,"SELECT * FROM  $tables  $sWhere LIMIT $offset,$per_page");
	
	if (isset($message)){
		?>
		<div class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<strong>Aviso!</strong> <?php echo $message;?>
		</div>
		
		<?php
	}
	if (isset($error)){
		?>
		<div class="alert alert-danger alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<strong>Error!</strong> <?php echo $error;?>
		</div>
		
		<?php
	}
	//loop through fetched data
	if ($numrows>0)	{
		?>
		
		 <div class="row">
			<?php
				while($row = mysqli_fetch_array($query)){
					$photo=$row['photo'];
					$titulo=$row['product_name'];
					$id_slide=$row['productid'];
                    $price=$row['product_price'];
				
					?>
							
					<div class="d-inline-flex p-4">

           <div class="ih-item square colored effect4" style="height:200px; ">
                <a id="enviar">
                    <div class="img"><img src="POS/<?php if (empty($photo)){echo " upload/noimage.jpg ";}else{echo $photo;} ?>" alt="img" style="width:200px;"></div>
                    <div class="mask1"></div>
                    <div class="mask2"></div>
                    <div class="info">
                        <h3 style="text-align:left">
                            <?php echo $titulo; ?>
                        </h3>
                        <h4>₡
                            <?php echo $price; ?>
                        </h4>
                        <form action="details.php" method="post" name="Detalle"><input name="id_txt" type="hidden" value="<?php echo $id; ?>" /><input name="Detalles" type="submit" value="Detalles" class="btn btn-info" /></form>
                    </div>
                </a>
            </div>
            </div>
					
					<?php
				}
			?>
		  </div>
		
		<div class="table-pagination text-right">
			
			<?php echo paginate($reload, $page, $total_pages, $adjacents);?>
		</div>
		<?php
	}

}
?>