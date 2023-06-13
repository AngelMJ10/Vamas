<?php

// Este archivo "invocará" desde el index (Dashboard) por lo tanto
// ya no es neceario session_start()

// Paso 1. ¿Cúal es nuestro nivel de acceso?
$permiso = $_SESSION['login']['nivelacceso'];

//2. Cada perfil tendrá disponible algunas opciones
$opciones = [];

switch ($permiso) {
  case "A":
    $opciones = [
      ["menu" => "Usuarios", "url" => "index.php?view=usuario.php"],
      ["menu" => "Proyectos", "url" => "index.php?view=proyecto.php"],
      ["menu" => "Fases", "url" => "index.php?view=fase.php"],
      ["menu" => "Tareas", "url" => "index.php?view=tareas.php"],
      ["menu" => "Reportes", "url" => "index.php?view=reportes.php"],
      ["menu" => "Compras", "url" => "index.php?view=compras.php"],
      ["menu" => "Empresas", "url" => "index.php?view=empresa.php"]
    ];
  break;
  
  case "S":
    $opciones = [
      ["menu" => "Fases", "url" => "index.php?view=fase.php"],
      ["menu" => "Tareas", "url" => "index.php?view=tareas.php"],
      ["menu" => "Reportes", "url" => "index.php?view=reportes.php"],
      ["menu" => "Compras", "url" => "index.php?view=compras.php"],
      ["menu" => "Empresas", "url" => "index.php?view=empresa.php"]
    ];
  break;

  case "C":
    $opciones = [
      ["menu" => "Tareas", "url" => "index.php?view=tareas.php"],
      ["menu" => "Reportes", "url" => "index.php?view=reportes.php"],
      ["menu" => "Empresas", "url" => "index.php?view=empresa.php"]
    ];
  break;
}

// Paso 3. Ahora redenrizamos a la vista(SIDEBAR) las opciones que 
// corresponde a cada perfil
foreach ($opciones as $item) {
  echo "
    <li class='nav-item'>
      <a href='{$item['url']}' class='nav-link'>
        <i class='fas fa-fw fa-chart-area'></i>
        <span>{$item['menu']}</span>
      </a>
    </li>";
}

?>