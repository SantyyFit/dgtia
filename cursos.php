<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>dgtIA</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="shortcut icon" href="imagenes/logo.png" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mi Repositorio - NewSkill</title>
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <link rel="stylesheet" href="css/miRepositorio.css">
  <style>
    :root {
      --rojo-principal: #a7201f;
      --rojo-secundario: #9f2241;
      --vino: #691c32;
      --dorado-claro: #ddc9a3;
      --dorado-oscuro: #bc955c;
      --gris-claro: #98989a;
      --gris-oscuro: #6f7271;
      --blanco: #ffffff;

      body {
        background-color: var(--dorado-claro);
        color: var(--gris-oscuro);
        font-family: Arial, sans-serif;
      }

      h1,
      h2,
      h3 {
        color: var(--vino);
      }

      a {
        color: var(--dorado-oscuro);
      }

      button {
        background: var(--vino);
        color: var(--blanco);
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
      }

      button:hover {
        background: var(--rojo-secundario);
      }

    }
  </style>
</head>













<body style="background-color: #ddc9a3;">


  <link rel="stylesheet" href="css/headerr.css">

  <header class="header">
    <div class="listacontainer">
      <ul class="header_nav-list">
        <li class="header_nav-item">
          <a href="perfil.php?user=Gadiel&amp;i=47" class="">
            <img src="imagenes/usuarioIcono4.png">
          </a>
        </li>

        <li class="header_nav-item">
          <a href="busqueda.php?user=Gadiel&amp;i=47" class="">
            <img src="imagenes/busquedaIcono.png">
          </a>
        </li>

        <li class="header_nav-item">
          <a href="clases.php?user=Gadiel&amp;i=47" class="">
            <img src="imagenes/relacionesIcono.png">
          </a>
        </li>

        <li class="header_nav-item">
          <a href="inicio.php?user=Gadiel&amp;i=47" class="">
            <img src="imagenes/hogar.png">
          </a>
        </li>

        <li class="header_nav-item" style="position: relative;">
          <a href="ver_notificaciones.php?user=Gadiel&amp;i=47" class="">
            <img src="imagenes/campana.png">
          </a>
        </li>

        <li class="header_nav-item">
          <a href="cursos.php?user=Gadiel&amp;i=47" class="active">
            <img src="imagenes/clasesIcono.png" alt="Cursos">
          </a>
        </li>
      </ul>
    </div>
  </header>

  <div id="miRepositorio">

    <h1 style="color: var(--vino);">Mi Repositorio</h1>

    <button id="btnCrearClase" style="background-color: var(--vino);">
      <a href="editar_clase.php" style="text-decoration: none; color:var(--blanco)">Crear Clase</a>
    </button>

    <div style="max-width:600px; width: 100%; margin-bottom: 20px; color:var(--blanco)">
      <button class="toggle-btn active" onclick="mostrarLista('creadas', this)" style="">Clases creadas</button>
      <button class="toggle-btn" onclick="mostrarLista('recibidas', this)">Clases recibidas</button>
    </div>

    <div id="clasesCreadas" class="clases-lista activo" style="background-color: var(--dorado-oscuro);">
      <h2>📒 Clases que he creado</h2>
      <ul>
        <li>No has creado clases aún.</li>
      </ul>
    </div>

    <div id="clasesRecibidas" class="clases-lista">
      <h2>📥 Clases que me han compartido</h2>
      <ul>
        <li style="margin-bottom:10px; position: relative;">
          📥 <a href="ver_clase.php?id=24" style="color:var(--dorado-claro); font-weight:bold; text-decoration:none;">¡El poder de las consecuencias! </a>
          <span style="color:var(--gris-claro); font-size: 0.9em;"> de Cristian Baca</span>
          <span class="estrella-favorito" data-id="24" style="position:absolute; right:10px; top:12px; cursor:pointer; font-size:18px; color:var(--dorado-oscuro);">☆</span>
        </li>
      </ul>
    </div>

    <div id="modalCompartir" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:#000000cc; z-index:9999; align-items:center; justify-content:center;">
      <div style="background:var(--gris-oscuro); padding:25px; border-radius:10px; width:90%; max-width:400px; color:var(--blanco); position:relative;">
        <span onclick="cerrarModal()" style="position:absolute; top:10px; right:15px; cursor:pointer; color:var(--gris-claro);">✖</span>
        <h3 style="color:var(--dorado-claro); margin-bottom:15px;">Compartir clase: <span id="tituloClaseModal"></span></h3>
        <form action="compartir_clase.php" method="POST">
          <input type="hidden" name="id_clase" id="inputIdClase">
          <label>Nombre del usuario:</label>
          <input type="search" name="nombre_usuario" required="" style="width:100%; padding:8px; border-radius:5px; margin-bottom:10px; border:none; background:var(--gris-claro); color:#000000;" list="listadoUsuarios" placeholder="Buscar usuario...">
          <datalist id="listadoUsuarios">
            <option value="baca"></option>
            <option value="Carlos"></option>
            <option value="CrisBaca"></option>
            <option value="crisbaca"></option>
            <option value="Cristian Baca"></option>
            <option value="DUKI2"></option>
            <option value="DUKI3"></option>
            <option value="esponja"></option>
            <option value="g4"></option>
            <option value="Gadiel"></option>
            <option value="JAS"></option>
            <option value="JAS"></option>
            <option value="karin"></option>
            <option value="KAS"></option>
            <option value="king"></option>
            <option value="Mantis"></option>
            <option value="maqleo"></option>
            <option value="maqleoo"></option>
            <option value="ÑAÑU"></option>
            <option value="Patricio"></option>
            <option value="pedro"></option>
            <option value="Santy"></option>
            <option value="santyfit"></option>
            <option value="Santyy"></option>
            <option value="vicente"> </option>
          </datalist>
          <label>Permiso:</label>
          <select name="permiso" style="width:100%; padding:8px; border-radius:5px; margin-bottom:20px; background:var(--gris-claro); color:#000000; border:none;">
            <option value="lectura">Lectura</option>
            <option value="editable">Editable</option>
          </select>
          <button type="submit" style="background:var(--vino); color:var(--blanco); padding:10px; width:100%; border:none; border-radius:6px; font-weight:bold; cursor:pointer;">
            Compartir clase
          </button>
        </form>
      </div>
    </div>

  </div>

  <script>
    const btnCrear = document.getElementById('btnCrearClase');
    const formCrear = document.getElementById('formularioCrearClase');

    if (btnCrear && formCrear) {
      btnCrear.addEventListener('click', () => {
        if (formCrear.classList.contains('active')) {
          formCrear.classList.remove('active');
          btnCrear.textContent = 'Crear nueva clase';
        } else {
          formCrear.classList.add('active');
          btnCrear.textContent = 'Cerrar formulario';
          window.scrollTo({
            top: formCrear.offsetTop - 20,
            behavior: 'smooth'
          });
        }
      });
    }

    function mostrarLista(tipo, btn) {
      document.getElementById('clasesCreadas').classList.remove('activo');
      document.getElementById('clasesRecibidas').classList.remove('activo');
      document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
      if (tipo === 'creadas') {
        document.getElementById('clasesCreadas').classList.add('activo');
      } else {
        document.getElementById('clasesRecibidas').classList.add('activo');
      }
      btn.classList.add('active');
    }

    function abrirModalCompartir(idClase, titulo) {
      document.getElementById('inputIdClase').value = idClase;
      document.getElementById('tituloClaseModal').textContent = titulo;
      document.getElementById('modalCompartir').style.display = 'flex';
    }

    function cerrarModal() {
      document.getElementById('modalCompartir').style.display = 'none';
    }

    function eliminarClase(idClase) {
      if (confirm('¿Estás seguro de que quieres eliminar esta clase de tu repositorio?')) {
        fetch('eliminar_clase.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_clase=' + encodeURIComponent(idClase)
          })
          .then(response => {
            // Primero verificar si la respuesta es JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              throw new TypeError('La respuesta no es JSON');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              alert(data.message);
              location.reload();
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error completo:', error);
            alert('Error de conexión. Verifica la consola para más detalles.');
          });
      }
    }
  </script>

  <script>
    document.querySelectorAll('.estrella-favorito').forEach(estrella => {
      estrella.addEventListener('click', () => {
        const idClase = estrella.dataset.id;

        fetch('toggle_favorito.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id_clase=' + encodeURIComponent(idClase)
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              estrella.textContent = data.es_favorita ? '★' : '☆';
            }
          });
      });
    });
  </script>



</body>

</html>