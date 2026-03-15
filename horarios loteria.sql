-- Lotería Nacional (Rep. Dominicana)
UPDATE loteries SET
    lunes_hora_fin = '14:30', martes_hora_fin = '14:30', miercoles_hora_fin = '14:30',
    jueves_hora_fin = '14:30', viernes_hora_fin = '14:30', sabado_hora_fin = '14:30', domingo_hora_fin = '18:00',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo'
WHERE slug = 'loteria-nacional';

-- Juega + Pega + y Gana Más (Rep. Dominicana)
UPDATE loteries SET
    lunes_hora_fin = '14:30', martes_hora_fin = '14:30', miercoles_hora_fin = '14:30',
    jueves_hora_fin = '14:30', viernes_hora_fin = '14:30', sabado_hora_fin = '14:30', domingo_hora_fin = '14:30',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo'
WHERE slug IN ('juega-pega', 'gana-mas');

-- Leidsa (Pega 3 Más, Loto Pool, Super Kino TV, Quiniela Leidsa)
UPDATE loteries SET
    lunes_hora_fin = '20:55', martes_hora_fin = '20:55', miercoles_hora_fin = '20:55',
    jueves_hora_fin = '20:55', viernes_hora_fin = '20:55', sabado_hora_fin = '20:55', domingo_hora_fin = '15:55',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo'
WHERE slug IN ('pega-3-mas', 'loto-pool', 'super-kino-tv', 'quiniela-leidsa');

-- Lotería Real (Quiniela Real)
UPDATE loteries SET
    lunes_hora_fin = '12:55', martes_hora_fin = '12:55', miercoles_hora_fin = '12:55',
    jueves_hora_fin = '12:55', viernes_hora_fin = '12:55', sabado_hora_fin = '12:55', domingo_hora_fin = '12:55',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo'
WHERE slug = 'quiniela-real';

-- Mega Chances y MegaLotto
UPDATE loteries SET
    lunes_hora_fin = '19:55', martes_hora_fin = '19:55', miercoles_hora_fin = '19:55',
    jueves_hora_fin = '19:55', viernes_hora_fin = '19:55', sabado_hora_fin = '19:55', domingo_hora_fin = '19:55',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo'
WHERE slug IN ('mega-chances', 'megalotto');

-- La Primera
UPDATE loteries SET
    lunes_hora_fin = '12:00', martes_hora_fin = '12:00', miercoles_hora_fin = '12:00',
    jueves_hora_fin = '12:00', viernes_hora_fin = '12:00', sabado_hora_fin = '12:00', domingo_hora_fin = '12:00',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/Santo_Domingo'
WHERE slug = 'la-primera-dia';

-- Anguila (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '10:00', martes_hora_fin = '13:00', miercoles_hora_fin = '18:00',
    jueves_hora_fin = '21:00', viernes_hora_fin = '10:00', sabado_hora_fin = '13:00', domingo_hora_fin = '18:00',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York'
WHERE slug LIKE 'anguila%';

-- King Lottery (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '12:30', martes_hora_fin = '19:30', miercoles_hora_fin = '12:30',
    jueves_hora_fin = '19:30', viernes_hora_fin = '12:30', sabado_hora_fin = '19:30', domingo_hora_fin = '12:30',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York'
WHERE slug LIKE 'king-lottery%';

-- New York (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '14:30', martes_hora_fin = '22:30', miercoles_hora_fin = '14:30',
    jueves_hora_fin = '22:30', viernes_hora_fin = '14:30', sabado_hora_fin = '22:30', domingo_hora_fin = '14:30',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York'
WHERE slug LIKE 'new-york%';

-- Florida (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '13:30', martes_hora_fin = '21:45', miercoles_hora_fin = '13:30',
    jueves_hora_fin = '21:45', viernes_hora_fin = '13:30', sabado_hora_fin = '21:45', domingo_hora_fin = '13:30',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York'
WHERE slug LIKE 'florida%';

-- Mega Millions (extranjera)
UPDATE loteries SET
    martes_hora_fin = '23:00', viernes_hora_fin = '23:00',
    martes_disponible = 1, viernes_disponible = 1,
    time_zone = 'America/New_York'
WHERE slug = 'mega-millions';

-- PowerBall (extranjera)
UPDATE loteries SET
    miercoles_hora_fin = '23:00', sabado_hora_fin = '23:00',
    miercoles_disponible = 1, sabado_disponible = 1,
    time_zone = 'America/New_York'
WHERE slug = 'powerball';

-- Cash 4 Life (extranjera)
UPDATE loteries SET
    lunes_hora_fin = '21:00', martes_hora_fin = '21:00', miercoles_hora_fin = '21:00',
    jueves_hora_fin = '21:00', viernes_hora_fin = '21:00', sabado_hora_fin = '21:00', domingo_hora_fin = '21:00',
    lunes_disponible = 1, martes_disponible = 1, miercoles_disponible = 1,
    jueves_disponible = 1, viernes_disponible = 1, sabado_disponible = 1, domingo_disponible = 1,
    time_zone = 'America/New_York'
WHERE slug = 'cash-4-life';
