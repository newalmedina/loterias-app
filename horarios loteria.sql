-- Lotería Nacional (Rep. Dominicana)
UPDATE loteries SET
    lunes_hora_fin = '14:30', martes_hora_fin = '14:30', miercoles_hora_fin = '14:30',
    jueves_hora_fin = '14:30', viernes_hora_fin = '14:30', sabado_hora_fin = '14:30', domingo_hora_fin = '18:00',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo',
    active = 1
WHERE slug = 'loteria-nacional';

-- Juega + Pega + y Gana Más (Rep. Dominicana)
UPDATE loteries SET
    lunes_hora_fin = '14:30', martes_hora_fin = '14:30', miercoles_hora_fin = '14:30',
    jueves_hora_fin = '14:30', viernes_hora_fin = '14:30', sabado_hora_fin = '14:30', domingo_hora_fin = '14:30',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo',
    active = 1
WHERE slug IN ('juega-pega', 'gana-mas');

-- Leidsa (Pega 3 Más, Loto Pool, Super Kino TV, Quiniela Leidsa)
UPDATE loteries SET
    lunes_hora_fin = '20:55', martes_hora_fin = '20:55', miercoles_hora_fin = '20:55',
    jueves_hora_fin = '20:55', viernes_hora_fin = '20:55', sabado_hora_fin = '20:55', domingo_hora_fin = '15:55',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo',
    active = 1
WHERE slug IN ('pega-3-mas', 'loto-pool', 'super-kino-tv', 'quiniela-leidsa');

-- Lotería Real (Quiniela Real)
UPDATE loteries SET
    lunes_hora_fin = '12:55', martes_hora_fin = '12:55', miercoles_hora_fin = '12:55',
    jueves_hora_fin = '12:55', viernes_hora_fin = '12:55', sabado_hora_fin = '12:55', domingo_hora_fin = '12:55',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo',
    active = 1
WHERE slug = 'quiniela-real';

-- Mega Chances y MegaLotto
UPDATE loteries SET
    lunes_hora_fin = '19:55', martes_hora_fin = '19:55', miercoles_hora_fin = '19:55',
    jueves_hora_fin = '19:55', viernes_hora_fin = '19:55', sabado_hora_fin = '19:55', domingo_hora_fin = '19:55',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo',
    active = 1
WHERE slug IN ('mega-chances', 'megalotto');

-- La Primera
UPDATE loteries SET
    lunes_hora_fin = '12:00', martes_hora_fin = '12:00', miercoles_hora_fin = '12:00',
    jueves_hora_fin = '12:00', viernes_hora_fin = '12:00', sabado_hora_fin = '12:00', domingo_hora_fin = '12:00',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo',
    active = 1
WHERE slug = 'la-primera-dia';

-- Anguila (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '10:00', martes_hora_fin = '13:00', miercoles_hora_fin = '18:00',
    jueves_hora_fin = '21:00', viernes_hora_fin = '10:00', sabado_hora_fin = '13:00', domingo_hora_fin = '18:00',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York',
    active = 1
WHERE slug LIKE 'anguila%';

-- King Lottery (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '12:30', martes_hora_fin = '19:30', miercoles_hora_fin = '12:30',
    jueves_hora_fin = '19:30', viernes_hora_fin = '12:30', sabado_hora_fin = '19:30', domingo_hora_fin = '12:30',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York',
    active = 1
WHERE slug LIKE 'king-lottery%';

-- New York (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '14:30', martes_hora_fin = '22:30', miercoles_hora_fin = '14:30',
    jueves_hora_fin = '22:30', viernes_hora_fin = '14:30', sabado_hora_fin = '22:30', domingo_hora_fin = '14:30',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York',
    active = 1
WHERE slug LIKE 'new-york%';

-- Florida (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '13:30', martes_hora_fin = '21:45', miercoles_hora_fin = '13:30',
    jueves_hora_fin = '21:45', viernes_hora_fin = '13:30', sabado_hora_fin = '21:45', domingo_hora_fin = '13:30',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York',
    active = 1
WHERE slug LIKE 'florida%';

-- Mega Millions (extranjera)
UPDATE loteries SET
    martes_hora_fin = '23:00', viernes_hora_fin = '23:00',
    martes_disponible = 1, viernes_disponible = 1,
    time_zone = 'America/New_York',
    active = 1
WHERE slug = 'mega-millions';

-- PowerBall (extranjera)
UPDATE loteries SET
    miercoles_hora_fin = '23:00', sabado_hora_fin = '23:00',
    miercoles_disponible = 1, sabado_disponible = 1,
    time_zone = 'America/New_York',
    active = 1
WHERE slug = 'powerball';

-- Cash 4 Life (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '21:00', martes_hora_fin = '21:00', miercoles_hora_fin = '21:00',
    jueves_hora_fin = '21:00', viernes_hora_fin = '21:00', sabado_hora_fin = '21:00', domingo_hora_fin = '21:00',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York',
    active = 1
WHERE slug = 'cash-4-life';


UPDATE `loteries` SET `id`=1, `short_name`='J. Peg.+ ', `nombre`='Juega + Pega +', `slug`='juega-pega', `code`='15678', `image`='loterias/juega-pega.png', `descripcion`=NULL, `active`=0, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='14:30:00', `martes_hora_fin`='14:30:00', `miercoles_hora_fin`='14:30:00', `jueves_hora_fin`='14:30:00', `viernes_hora_fin`='14:30:00', `sabado_hora_fin`='14:30:00', `domingo_hora_fin`='14:30:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:22', `updated_at`='2026-03-21 07:45:54' WHERE `id`=1;
UPDATE `loteries` SET `id`=2, `short_name`='G. Mas', `nombre`='Gana Más', `slug`='gana-mas', `code`='39066', `image`='loterias/gana-mas.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='14:30:00', `martes_hora_fin`='14:30:00', `miercoles_hora_fin`='14:30:00', `jueves_hora_fin`='14:30:00', `viernes_hora_fin`='14:30:00', `sabado_hora_fin`='14:30:00', `domingo_hora_fin`='14:30:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:23', `updated_at`='2026-03-19 08:42:23' WHERE `id`=2;
UPDATE `loteries` SET `id`=3, `short_name`='Nac.', `nombre`='Lotería Nacional', `slug`='loteria-nacional', `code`='88869', `image`='loterias/loteria-nacional.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='14:30:00', `martes_hora_fin`='14:30:00', `miercoles_hora_fin`='14:30:00', `jueves_hora_fin`='14:30:00', `viernes_hora_fin`='14:30:00', `sabado_hora_fin`='14:30:00', `domingo_hora_fin`='18:00:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:23', `updated_at`='2026-03-19 08:42:23' WHERE `id`=3;
UPDATE `loteries` SET `id`=4, `short_name`='P. 3 Mas', `nombre`='Pega 3 Más', `slug`='pega-3-mas', `code`='78512', `image`='loterias/pega-3-mas.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='20:55:00', `martes_hora_fin`='20:55:00', `miercoles_hora_fin`='20:55:00', `jueves_hora_fin`='20:55:00', `viernes_hora_fin`='20:55:00', `sabado_hora_fin`='20:55:00', `domingo_hora_fin`='15:55:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:23', `updated_at`='2026-03-19 08:42:23' WHERE `id`=4;
UPDATE `loteries` SET `id`=5, `short_name`='Q. Pale', `nombre`='Quiniela Leidsa', `slug`='quiniela-leidsa', `code`='60840', `image`='loterias/quiniela-leidsa.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='20:55:00', `martes_hora_fin`='20:55:00', `miercoles_hora_fin`='20:55:00', `jueves_hora_fin`='20:55:00', `viernes_hora_fin`='20:55:00', `sabado_hora_fin`='20:55:00', `domingo_hora_fin`='15:55:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:24', `updated_at`='2026-03-19 08:42:24' WHERE `id`=5;
UPDATE `loteries` SET `id`=6, `short_name`='L. Pool', `nombre`='Loto Pool', `slug`='loto-pool', `code`='87493', `image`='loterias/loto-pool.png', `descripcion`=NULL, `active`=0, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='20:55:00', `martes_hora_fin`='20:55:00', `miercoles_hora_fin`='20:55:00', `jueves_hora_fin`='20:55:00', `viernes_hora_fin`='20:55:00', `sabado_hora_fin`='20:55:00', `domingo_hora_fin`='15:55:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:24', `updated_at`='2026-03-21 07:45:54' WHERE `id`=6;
UPDATE `loteries` SET `id`=7, `short_name`='S. Kin. TV', `nombre`='Super Kino TV', `slug`='super-kino-tv', `code`='67990', `image`='loterias/super-kino-tv.png', `descripcion`=NULL, `active`=0, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='20:55:00', `martes_hora_fin`='20:55:00', `miercoles_hora_fin`='20:55:00', `jueves_hora_fin`='20:55:00', `viernes_hora_fin`='20:55:00', `sabado_hora_fin`='20:55:00', `domingo_hora_fin`='15:55:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:24', `updated_at`='2026-03-21 07:45:54' WHERE `id`=7;
UPDATE `loteries` SET `id`=8, `short_name`='S. Loto M.', `nombre`='Loto - Super Loto Más', `slug`='loto-super-loto-mas', `code`='86784', `image`='loterias/loto-super-loto-mas.png', `descripcion`=NULL, `active`=0, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:24', `updated_at`='2026-03-19 08:42:24' WHERE `id`=8;
UPDATE `loteries` SET `id`=9, `short_name`='Q. Real', `nombre`='Quiniela Real', `slug`='quiniela-real', `code`='85902', `image`='loterias/quiniela-real.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='12:55:00', `martes_hora_fin`='12:55:00', `miercoles_hora_fin`='12:55:00', `jueves_hora_fin`='12:55:00', `viernes_hora_fin`='12:55:00', `sabado_hora_fin`='12:55:00', `domingo_hora_fin`='12:55:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:25', `updated_at`='2026-03-19 08:42:25' WHERE `id`=9;
UPDATE `loteries` SET `id`=10, `short_name`='L. Real', `nombre`='Loto Real', `slug`='loto-real', `code`='42126', `image`='loterias/loto-real.png', `descripcion`=NULL, `active`=0, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:25', `updated_at`='2026-03-19 08:42:25' WHERE `id`=10;
UPDATE `loteries` SET `id`=11, `short_name`='Loteka', `nombre`='Quiniela Loteka', `slug`='quiniela-loteka', `code`='86054', `image`='loterias/quiniela-loteka.png', `descripcion`=NULL, `active`=1, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:25', `updated_at`='2026-03-21 07:45:54' WHERE `id`=11;
UPDATE `loteries` SET `id`=12, `short_name`='M. Chan', `nombre`='Mega Chances', `slug`='mega-chances', `code`='20755', `image`='loterias/mega-chances.png', `descripcion`=NULL, `active`=0, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='19:55:00', `martes_hora_fin`='19:55:00', `miercoles_hora_fin`='19:55:00', `jueves_hora_fin`='19:55:00', `viernes_hora_fin`='19:55:00', `sabado_hora_fin`='19:55:00', `domingo_hora_fin`='19:55:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:26', `updated_at`='2026-03-21 07:45:54' WHERE `id`=12;
UPDATE `loteries` SET `id`=13, `short_name`='M. Lotto', `nombre`='MegaLotto', `slug`='megalotto', `code`='56399', `image`='loterias/megalotto.png', `descripcion`=NULL, `active`=0, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='19:55:00', `martes_hora_fin`='19:55:00', `miercoles_hora_fin`='19:55:00', `jueves_hora_fin`='19:55:00', `viernes_hora_fin`='19:55:00', `sabado_hora_fin`='19:55:00', `domingo_hora_fin`='19:55:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:26', `updated_at`='2026-03-21 07:45:54' WHERE `id`=13;
UPDATE `loteries` SET `id`=14, `short_name`='Flo. Dia', `nombre`='Florida Día', `slug`='florida-dia', `code`='86268', `image`='loterias/florida-dia.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='13:30:00', `martes_hora_fin`='21:45:00', `miercoles_hora_fin`='13:30:00', `jueves_hora_fin`='21:45:00', `viernes_hora_fin`='13:30:00', `sabado_hora_fin`='21:45:00', `domingo_hora_fin`='13:30:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:26', `updated_at`='2026-03-19 08:42:26' WHERE `id`=14;
UPDATE `loteries` SET `id`=15, `short_name`='New. Y. Tar.', `nombre`='New York Tarde', `slug`='new-york-tarde', `code`='27324', `image`='loterias/new-york-tarde.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='14:30:00', `martes_hora_fin`='22:30:00', `miercoles_hora_fin`='14:30:00', `jueves_hora_fin`='22:30:00', `viernes_hora_fin`='14:30:00', `sabado_hora_fin`='22:30:00', `domingo_hora_fin`='14:30:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:27', `updated_at`='2026-03-19 08:42:27' WHERE `id`=15;
UPDATE `loteries` SET `id`=16, `short_name`='Flo. Noc.', `nombre`='Florida Noche', `slug`='florida-noche', `code`='81830', `image`='loterias/florida-noche.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='13:30:00', `martes_hora_fin`='21:45:00', `miercoles_hora_fin`='13:30:00', `jueves_hora_fin`='21:45:00', `viernes_hora_fin`='13:30:00', `sabado_hora_fin`='21:45:00', `domingo_hora_fin`='13:30:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:27', `updated_at`='2026-03-19 08:42:27' WHERE `id`=16;
UPDATE `loteries` SET `id`=17, `short_name`='New Y. Noc', `nombre`='New York Noche', `slug`='new-york-noche', `code`='31429', `image`='loterias/new-york-noche.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='14:30:00', `martes_hora_fin`='22:30:00', `miercoles_hora_fin`='14:30:00', `jueves_hora_fin`='22:30:00', `viernes_hora_fin`='14:30:00', `sabado_hora_fin`='22:30:00', `domingo_hora_fin`='14:30:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:28', `updated_at`='2026-03-19 08:42:28' WHERE `id`=17;
UPDATE `loteries` SET `id`=18, `short_name`='M. Mili', `nombre`='Mega Millions', `slug`='mega-millions', `code`='56879', `image`='loterias/mega-millions.png', `descripcion`=NULL, `active`=0, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`=NULL, `martes_hora_fin`='23:00:00', `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`='23:00:00', `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:28', `updated_at`='2026-03-21 07:45:54' WHERE `id`=18;
UPDATE `loteries` SET `id`=19, `short_name`='Pow.Ball', `nombre`='PowerBall', `slug`='powerball', `code`='45879', `image`='loterias/powerball.png', `descripcion`=NULL, `active`=0, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`='23:00:00', `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`='23:00:00', `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:28', `updated_at`='2026-03-21 07:45:54' WHERE `id`=19;
UPDATE `loteries` SET `id`=20, `short_name`='C. 4 life', `nombre`='Cash 4 Life', `slug`='cash-4-life', `code`='40610', `image`='loterias/cash-4-life.png', `descripcion`=NULL, `active`=0, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='21:00:00', `martes_hora_fin`='21:00:00', `miercoles_hora_fin`='21:00:00', `jueves_hora_fin`='21:00:00', `viernes_hora_fin`='21:00:00', `sabado_hora_fin`='21:00:00', `domingo_hora_fin`='21:00:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:29', `updated_at`='2026-03-21 07:45:54' WHERE `id`=20;
UPDATE `loteries` SET `id`=21, `short_name`='Prim. Dia', `nombre`='La Primera Día', `slug`='la-primera-dia', `code`='84947', `image`='loterias/la-primera-dia.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='12:00:00', `martes_hora_fin`='12:00:00', `miercoles_hora_fin`='12:00:00', `jueves_hora_fin`='12:00:00', `viernes_hora_fin`='12:00:00', `sabado_hora_fin`='12:00:00', `domingo_hora_fin`='12:00:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:29', `updated_at`='2026-03-19 08:42:29' WHERE `id`=21;
UPDATE `loteries` SET `id`=22, `short_name`='Prim. Noche', `nombre`='Primera Noche', `slug`='primera-noche', `code`='41002', `image`='loterias/primera-noche.png', `descripcion`=NULL, `active`=1, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:29', `updated_at`='2026-03-21 07:45:54' WHERE `id`=22;
UPDATE `loteries` SET `id`=23, `short_name`='Loto 5', `nombre`='Loto 5', `slug`='loto-5', `code`='99995', `image`='loterias/loto-5.png', `descripcion`=NULL, `active`=0, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:29', `updated_at`='2026-03-19 08:42:29' WHERE `id`=23;
UPDATE `loteries` SET `id`=24, `short_name`='L. Suer. 12:30', `nombre`='La Suerte 12:30', `slug`='la-suerte-1230', `code`='52046', `image`='loterias/la-suerte-1230.png', `descripcion`=NULL, `active`=1, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:30', `updated_at`='2026-03-21 07:45:54' WHERE `id`=24;
UPDATE `loteries` SET `id`=25, `short_name`='L. Suer. 18:00', `nombre`='La Suerte 18:00', `slug`='la-suerte-1800', `code`='71317', `image`='loterias/la-suerte-1800.png', `descripcion`=NULL, `active`=1, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:30', `updated_at`='2026-03-21 07:45:54' WHERE `id`=25;
UPDATE `loteries` SET `id`=26, `short_name`='Q. Lot.  Dom.', `nombre`='Quiniela LoteDom', `slug`='quiniela-lotedom', `code`='54969', `image`='loterias/quiniela-lotedom.png', `descripcion`=NULL, `active`=1, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:30', `updated_at`='2026-03-21 07:45:54' WHERE `id`=26;
UPDATE `loteries` SET `id`=27, `short_name`='Quem. Mayor', `nombre`='El Quemaito Mayor', `slug`='el-quemaito-mayor', `code`='19095', `image`='loterias/el-quemaito-mayor.png', `descripcion`=NULL, `active`=1, `time_zone`=NULL, `lunes_hora_fin`=NULL, `martes_hora_fin`=NULL, `miercoles_hora_fin`=NULL, `jueves_hora_fin`=NULL, `viernes_hora_fin`=NULL, `sabado_hora_fin`=NULL, `domingo_hora_fin`=NULL, `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:31', `updated_at`='2026-03-21 07:45:54' WHERE `id`=27;
UPDATE `loteries` SET `id`=28, `short_name`='Ang. 10am', `nombre`='Anguila 10AM', `slug`='anguila-manana', `code`='36542', `image`='loterias/anguila-manana.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='10:00:00', `martes_hora_fin`='10:00:00', `miercoles_hora_fin`='10:00:00', `jueves_hora_fin`='10:00:00', `viernes_hora_fin`='10:00:00', `sabado_hora_fin`='10:00:00', `domingo_hora_fin`='10:00:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:31', `updated_at`='2026-03-19 08:42:31' WHERE `id`=28;
UPDATE `loteries` SET `id`=29, `short_name`='Ang. 1pm', `nombre`='Anguila 1PM', `slug`='anguila-medio-dia', `code`='44704', `image`='loterias/anguila-medio-dia.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='13:00:00', `martes_hora_fin`='13:00:00', `miercoles_hora_fin`='13:00:00', `jueves_hora_fin`='13:00:00', `viernes_hora_fin`='13:00:00', `sabado_hora_fin`='13:00:00', `domingo_hora_fin`='13:00:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:31', `updated_at`='2026-03-19 08:42:31' WHERE `id`=29;
UPDATE `loteries` SET `id`=30, `short_name`='Ang. 6pm', `nombre`='Anguila 6PM', `slug`='anguila-tarde', `code`='85555', `image`='loterias/anguila-tarde.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='13:00:00', `martes_hora_fin`='13:00:00', `miercoles_hora_fin`='13:00:00', `jueves_hora_fin`='13:00:00', `viernes_hora_fin`='13:00:00', `sabado_hora_fin`='13:00:00', `domingo_hora_fin`='13:00:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:31', `updated_at`='2026-03-19 08:42:31' WHERE `id`=30;
UPDATE `loteries` SET `id`=31, `short_name`='Ang. 9pm', `nombre`='Anguila 9PM', `slug`='anguila-noche', `code`='53126', `image`='loterias/anguila-noche.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='21:00:00', `martes_hora_fin`='21:00:00', `miercoles_hora_fin`='21:00:00', `jueves_hora_fin`='21:00:00', `viernes_hora_fin`='21:00:00', `sabado_hora_fin`='21:00:00', `domingo_hora_fin`='21:00:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:32', `updated_at`='2026-03-19 08:42:32' WHERE `id`=31;
UPDATE `loteries` SET `id`=32, `short_name`='K. Lot. 12:30', `nombre`='King Lottery 12:30', `slug`='king-lottery-1230', `code`='19215', `image`='loterias/king-lottery-1230.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='12:30:00', `martes_hora_fin`='12:30:00', `miercoles_hora_fin`='12:30:00', `jueves_hora_fin`='12:30:00', `viernes_hora_fin`='12:30:00', `sabado_hora_fin`='12:30:00', `domingo_hora_fin`='12:30:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:32', `updated_at`='2026-03-19 08:42:32' WHERE `id`=32;
UPDATE `loteries` SET `id`=33, `short_name`='K. Lot. 7:30', `nombre`='King Lottery 7:30', `slug`='king-lottery-730', `code`='48498', `image`='loterias/king-lottery-730.png', `descripcion`=NULL, `active`=1, `time_zone`='America/Santo_Domingo', `lunes_hora_fin`='19:30:00', `martes_hora_fin`='19:30:00', `miercoles_hora_fin`='19:30:00', `jueves_hora_fin`='19:30:00', `viernes_hora_fin`='19:30:00', `sabado_hora_fin`='19:30:00', `domingo_hora_fin`='19:30:00', `lunes_disponible`=1, `martes_disponible`=1, `miercoles_disponible`=1, `jueves_disponible`=1, `viernes_disponible`=1, `sabado_disponible`=1, `domingo_disponible`=1, `created_at`='2026-03-19 08:42:33', `updated_at`='2026-03-19 08:42:33' WHERE `id`=33;
