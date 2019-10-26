<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Integración de Culqui</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<!-- Incluyendo Culqi Checkout -->
	<script type="text/javascript" src="https://checkout.culqi.com/js/v3"></script>

	<!-- Google Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,600italic,700,700italic,800' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Poppins:400,300,500,600,700' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Kaushan+Script&amp;subset=latin-ext" rel="stylesheet">
	<?php
		$sid = $_SESSION['sid'] = session_id();
		$_SESSION['totCart']=952500000;
		$dni_comp = '72042683';
		$direccion = 'Francisco Lazo';
		$departamento = 'Lima';
		$provincia = 'Lima';
		$nombre_comp = 'Frank Moreno Alburqueque';
		$correo_comp = 'admin@frankmorenoalburqueque.com';
	?>
</head>
<body>


		<button type="submit" class="button btn-proceed-checkout" id="buyButton" name="prePago" title="Procesar con el Pago"><span>Procesar con el Pago</span></button>


  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <script>
    Culqi.publicKey = 'pk_test_25E7HHJpVTXS26cr';

    //descompocición del total:
    var largo, millones, millares, centenas, decimales, totalPago;
    largo = <?php echo strlen($_SESSION['totCart']); ?>;
    if (largo<7) {
      centenas = '<?php echo substr($_SESSION['totCart'],0, -3); ?>';
      decimales = '<?php echo substr($_SESSION['totCart'], -2) ?>';
      totalPago = centenas + decimales;
    }
    if (largo>=7 && largo<11) {
      millares = '<?php echo substr($_SESSION['totCart'],0, -6); ?>';
      centenas = '<?php echo substr($_SESSION['totCart'],-6, 3); ?>';
      decimales = '<?php echo substr($_SESSION['totCart'], -2) ?>';
      totalPago = millares + centenas + decimales;
    }
    if (largo>=10 && largo<14) {
      millones = '<?php echo substr($_SESSION['totCart'],0, -10); ?>';
      millares = '<?php echo substr($_SESSION['totCart'],0, -6); ?>';
      centenas = '<?php echo substr($_SESSION['totCart'],-6, 3); ?>';
      decimales = '<?php echo substr($_SESSION['totCart'], -2) ?>';
      totalPago = millones + millares + centenas + decimales;
    }

    Culqi.settings({
      title: 'Tienda en Linea - Frank Moreno',
      currency: 'PEN',
      description: 'Pago Productos varios - Frank Moreno',
      amount: totalPago,
      metadata:{
        order_id: "<?php echo $sid; ?>"
      }
    });

    $('#buyButton').on('click', function(e) {
        // Abre el formulario con la configuración en Culqi.settings
        Culqi.open();
        e.preventDefault();
    });

    function pdf() {
      window.location.assign("./?pagado=si");
    }

    function culqi() {
      if (Culqi.token) { // ¡Objeto Token creado exitosamente!
        var token = Culqi.token.id;
        var email = Culqi.token.email;

        var data = { 
          id:'<?php echo $sid; ?>', 
          producto:'Productos varios. Frank Moreno', 
          precio: totalPago, 
          token:token, 
          customer_id: "<?php echo $dni_comp.'_'.$sid; ?>",
          address: "<?php echo $direccion; ?>",
          address_city: "<?php echo $departamento.' - '.$provincia; ?>",
          first_name: "<?php echo $nombre_comp; ?>",
          email: '<?php echo $correo_comp; ?>' 
        };

        var url = "./plugins/proceso.php";

        $.post(url,data,function(res){
          alert(' Tu pago se Realizó con ' + res + '. Agradecemos tu preferencia.');
          if (res=="exito") {
            pdf();
          }else{
            alert("No se logró realizar el pago.");
          }
        });

        //En esta linea de codigo debemos enviar el "Culqi.token.id"
        //hacia tu servidor con Ajax
      } else { // ¡Hubo algún problema!
        // Mostramos JSON de objeto error en consola
        console.log(Culqi.error);
        alert(Culqi.error.user_message);
      }
    };
  </script>
</body>
</html>
