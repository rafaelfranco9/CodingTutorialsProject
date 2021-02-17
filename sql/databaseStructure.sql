-- Volcando estructura para tabla codingtutorials.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla codingtutorials.categoria: ~9 rows (aproximadamente)
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` (`id`, `nombre`) VALUES
	(1, 'web development'),
	(2, 'web design'),
	(3, 'ux design'),
	(4, 'ui design'),
	(5, 'artificial intelligence'),
	(6, 'web scraping'),
	(7, 'rest api'),
	(10, 'excel'),
	(12, 'Frameworks'),
	(14, 'otro');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;

-- Volcando estructura para tabla codingtutorials.tutorial
CREATE TABLE IF NOT EXISTS `tutorial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'Imagenes/default_tutorial.png',
  `categoria` int(11) NOT NULL DEFAULT 1,
  `etiquetas` varchar(255) DEFAULT NULL,
  `herramientas` longtext DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `visitas` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla codingtutorials.tutorial: ~14 rows (aproximadamente)
/*!40000 ALTER TABLE `tutorial` DISABLE KEYS */;
INSERT INTO `tutorial` (`id`, `titulo`, `descripcion`, `imagen`, `categoria`, `etiquetas`, `herramientas`, `estado`, `visitas`) VALUES
	(default, 'React Native principiantes', 'en este tutorial vamos a ver las cosas basicas de React Native', 'usuarios/USER_5/tutoriales/tutorial_105/react.png', 5, '["React","Javascript"]', '[{"indice":0,"html_tutorial":"<textarea elastic id=@texto@ spellcheck=@false@ oninput=@autosize(this)@  ng-model=@$ctrl.herramientas[0].valor@ class=@rounded m-1 textinput@></textarea>","html_view":"<textarea elastic id=@texto@ readonly spellcheck=@false@ ng-model=@$ctrl.herramientas[0].valor@ class=@rounded m-1 textinput@></textarea>","label":"text","valor":"Para empezar tenemos que saber que react native fue creado por facebook.<br><br>es un framework para desarrollar front end"},{"indice":1,"html_tutorial":"<input type=@text@  autocomplete=@off@ id=@texto@ spellcheck=@false@  ng-model=@$ctrl.herramientas[1].valor@ class=@rounded m-1 urlinput@><button class=@btn btn-sm mb-1 bg-light@ ng-click=@$ctrl.openLink($ctrl.herramientas[1].valor)@>Ir</button>","html_view":"<input type=@text@ id=@texto@ readonly spellcheck=@false@  ng-model=@$ctrl.herramientas[1].valor@ class=@rounded m-1 urlinput@><button class=@btn btn-sm mb-1 bg-light@ ng-click=@$ctrl.openLink($ctrl.herramientas[1].valor)@>Ir</button>","label":"url","valor":"https://reactnative.dev"},{"indice":2,"urlImagen":"blob:http://localhost:8080/3c4d4509-7aa7-4000-b11d-338d9a1f97d2","valor":"usuarios/USER_5/tutoriales/tutorial_105/react.png","html_tutorial":"<img id=@image_display_2@ class=@img-thumbnail@ ng-src=@{{$ctrl.herramientas[2].imagenGuardada ? $ctrl.herramientas[2].valor : $ctrl.herramientas[2].urlImagen}}@ style=@width:{{$ctrl.herramientas[2].ancho}}px; height:{{$ctrl.herramientas[2].alto}}px@ alt=@user_image@><button ng-click=@$ctrl.imgActiva(2)@ data-bs-toggle=@modal@ data-bs-target=@#exampleModal@ class=@btn btn-sm btn-dark@>Add/Change Image</button>","html_view":"<img id=@image_display_2@ class=@img-thumbnail@ ng-src=@{{$ctrl.herramientas[2].valor}}@ style=@width:{{$ctrl.herramientas[2].ancho}}px; height:{{$ctrl.herramientas[2].alto}}px@ alt=@user_image@>","label":"image","ancho":400,"alto":200,"imagenGuardada":true},{"indice":3,"html_tutorial":"<textarea elastic id=@codigo@ ng-keydown=@$ctrl.insertTab(3, $event)@ spellcheck=@false@ oninput=@autosize(this)@ ng-model=@$ctrl.herramientas[3].valor@  class=@rounded m-1 codeinput@ ></textarea>","html_view":"<textarea elastic id=@codigo@ readonly spellcheck=@false@ ng-model=@$ctrl.herramientas[3].valor@ class=@rounded m-1 codeinput@ ></textarea>","label":"code","valor":"var invariant = require(<CS>invariant<CS>);<br><br>invariant(<br>  2 + 2 === 4,<br>  <CS>You shall not pass!<CS><br>);"}]', 'publicado', 27);
/*!40000 ALTER TABLE `tutorial` ENABLE KEYS */;

-- Volcando estructura para tabla codingtutorials.tutorial_usuario
CREATE TABLE IF NOT EXISTS `tutorial_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_tutorial` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_tutorial` (`id_tutorial`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla codingtutorials.tutorial_usuario: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `tutorial_usuario` DISABLE KEYS */;
INSERT INTO `tutorial_usuario` (`id`, `id_usuario`, `id_tutorial`) VALUES
	(default, 1, 1);
/*!40000 ALTER TABLE `tutorial_usuario` ENABLE KEYS */;

-- Volcando estructura para tabla codingtutorials.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'Imagenes/default_profile.png',
  `descripcion` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT 'regular',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla codingtutorials.usuario: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `correo`, `contrasena`, `imagen`, `descripcion`, `tipo`) VALUES
	(default, 'admin', '1', 'admin@gmail.com', 'playa107', 'Imagenes/default_profile.png', 'Usuario para administrar la pagina web', 'admin');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
