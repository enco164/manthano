use manthanodb;
-- Activities 
call addActivityRaw('Root', 'Glavni', '4.12.2013', 'slika.com/coverRoot.png', 1, 1, 18);
call addActivityRaw('Informatika', 'ISmer', '4.12.2013', 'slika.com/coverI.png', 1, 2, 9);
call addActivityRaw('Matematika', 'MSmer', '4.12.2013', 'slika.com/coverM.png', 1, 10, 15);
call addActivityRaw('Astronomija', 'ASmer', '4.12.2013', 'slika.com/coverA.png', 1, 16, 17);
call addActivityRaw('UAR', 'Opis', '4.12.2013', 'slika.com/cover.png', 1, 3, 4);
call addActivityRaw('UOR', 'Opis', '4.12.2013', 'slika.com/cover.png', 1, 5, 6);
call addActivityRaw('UVIT', 'Opis', '4.12.2013', 'slika.com/cover.png', 1, 7, 8);
call addActivityRaw('Analiza 1', 'Opis', '4.12.2013', 'slika.com/cover.png', 1, 11, 12);
call addActivityRaw('Analiza 2', 'Opis', '4.12.2013', 'slika.com/cover.png', 1, 13, 14);
-- Events
call addEvent(8,'A1 Event 1', 'First Event', '708','4.12.2013','13:00');
call addEvent(8,'A1 Event 2', 'Second Event', '708','4.12.2013','13:00');
call addEvent(9,'A2 Event 1', 'First A2 Event', '708','4.12.2013','13:00');
call addEvent(9,'A2 Event 2', 'Secon A2 Event', '708','4.12.2013','13:00');
call addEvent(9,'A2 Event 3', 'Third A2 Event', '708','4.12.2013','13:00');
call addEvent(5,'Naziv predavanja', 'Prvo Event', '708','4.12.2013','13:00');

-- User import
INSERT INTO `user` (`user_id`, `username`, `password`, `acc_type`,`mail`, `Ext_login`, `Name`, `Surname`, `www`, `Proffession`, `School`, `ProfilePicture`, `salt`, `status`) VALUES
(1, 'stevos', 'f1ec24e771bb1191a796221cebca4a5a', 99, 'teva.biz@gmail.com', 0, 'Stefan', 'Isidorovic', NULL, 'Džabalebaroš/lelemud', 'OS Sveti Sava', NULL, '4eBgJ', NULL),
(2, 'peros', '3c0b3ff6f864f84f34ab10c8e87f4da1', 1, 'pera@blah.com', 0, 'Pera', 'Perić', 'www,nekisajt.com', 'Limar', 'OS Sveti Sava', NULL, '3nEne', NULL),
(3, 'darko91', '3570407b25a12f0bc101fa4d6426a5ea', 1, 'mi10034@matf.bg.ac.rs', 0, 'Darko', 'Vidakovic', 'www.redtube.com', 'Porno glumac', 'OS Popinski borci ', NULL, 'epZVv', NULL),
(4, 'techaz', 'ca4ebdf22334d5cc4b45093d71b383ac', 1, 'vladimir.djordjevic@outlook.com', 0, 'Vladimir', 'Djordjevic', '', 'student', 'visa elektrotehnicka', NULL, 'eZswP', NULL),
(5, 'Kamicak', 'be7c555af2fd022311dac52e13d36397', 1, 'mojimail@hotmail.com', 0, 'Tamara', 'Leposavic', 'www.mojsajt.com', 'studentkinja', 'Pavle Savic', NULL, 'FC03W', NULL),
(6, 'guza007', 'bafdbc6b3f1b3b6bc1f9eb607437bb8d', 1, 'perperoglu@gmail.com', 0, 'Nenad', 'Avramovic', 'www.24hxxx.com', 'student', 'tesla', NULL, 'bkUIg', NULL),
(7, 'xboxdajmi', '757d1efe403e10c4a718c1e29eda50e1', 1, 'bojants91@gmail.com', 0, 'Bojan', 'Nestorovic', 'www.fleksbuks.com', 'Cekam Stevu da mi da xbox', 'OS Sveti Sava! Kako si znao majmune!?', NULL, 'P5x2L', NULL),
(8, 'Blackbolt', '77efccb47961b952ed63c8d8d0847a7c', 1, 'blackbolt990@gmail.com', 0, 'Bojan', 'Milic', 'www.malaskolamatemat', 'Student', 'Osnovna', NULL, 'LB8h3', NULL),
(9, 'Komek', 'f49bc0b731cb33b561e0c67b1f08120a', 1, 'vlax2709@gmail.com', 0, 'Vladimir', 'Martić', 'www.daregej.com', 'student', 'Pancevacka Skola 2', NULL, '4c8Bd', NULL),
(10, 'Bambi', '4ce3945694a01ed3471e64306acd0416', 1, 'lanamandic@hotmail.com', 0, 'Lana', 'Mandic', '', 'hote', 'OS Pavle Savic', NULL, 'N1Rd2', NULL),
(11, 'zibrr', '6a33f5f15e026184b7a5d71dc89febca', 1, 'marko.stanacic@gmail.com', 0, 'Marko', 'Stanacic', '', 'Student', '', NULL, 'HU7lD', NULL),
(12, 'Bambi456', '3b39f840099e18bd0f14768d3532e7f8', 1, 'lana.mandic.11@hotmail.com', 0, 'Lana', 'Mandic', 'www.mojsajt.com', 'menadzer', 'OS Pavle Savic', NULL, 'Fwz8u', NULL),
(13, 'zdravko', 'bb95dc6ac895bab4b42a4489c0316d93', 1, 'fdsaf@fsd.com', 0, 'Zdravko', 'Rakic', '', 'Student', 'Peta gimnazija', NULL, 'ALwjw', NULL),
(14, 'VladaMT', 'eeddb415ea0c0cba0b67aa77a67ddd53', 1, 'vladaherbalife@gmail.com', 0, 'Vlada', 'Ivanovic', '', 'wellness savetnik', 'Sedma Beogradska Gimnazija', NULL, '5w144', NULL),
(15, 'Deljenje0NijeDefinis', 'c044955f99a413ac9f8b59fcd478197c', 1, 'missuchiha93@gmail.com', 0, 'Branislava', 'Zivkovic', '', 'Kucna pomocnica', 'OS Jovan Miodragovic', NULL, '361OU', NULL),
(16, 'lnluksa', 'bf9ae491b196f124d229ff94cd4dc75a', 1, 'pamtii@gmail.com', 0, 'Nikola', 'Lukovic', '', 'Student', 'OS Arilje 2', NULL, '85v3z', NULL),
(17, 'jo.stan8', '539db3d1bb53745732b66502989e056b', 1, 'jovanalp.stan@gmail.com', 0, 'Jovana', 'Stanimirović', 'www.nemasajta.com', 'dangubljenje', 'valjevska gimnazija', NULL, 'S269A', NULL),
(18, 'divic', 'a4eb5d39cafd17a1f7287288e7c4942a', 1, 'logotijedojaja@vrh.com', 0, 'Nikola', 'Divic', 'www.mojsajt.com', 'Metalokobasičar', 'MATF', NULL, 'uFzqb', NULL),
(19, 'prvul', 'b205799a73190fa2512f7fae09b1721a', 1, 'petar.prvulovic@gmail.com', 0, 'petar', 'prvulovic', 'prvulovic.net', 'Mladji prvulovic', 'OS Sveti Prvul', NULL, 'UioRH', NULL),
(20, 'poiuy', '6bfbcf7e783550b6d631d46c1b56554e', 1, 'example@example.com', 0, 'Nikola', 'Nikolic', '', 'jibubkib', 'bikb uiuojv', NULL, '2qi7M', NULL),
(21, 'savicmi', '89695bf727267a65b5b0d327739dac52', 1, 'savicmi@gmail.com', 0, 'Miloš', 'Savić', '', 'Informatičar', 'OS Miodrag Vuković', NULL, 'jhjWD', NULL),
(22, 'Stefi', 'd1d81fd2e219a6234d8d5fa3c75431a4', 1, 'stefanacerovina@gmail.com', 0, 'Stefana', 'Cerovina', '', 'Student', 'OS Varvarin 2', NULL, 'C1Ct5', NULL),
(23, 'wujic', '5441d8e76f748450c105b5a8442a76b6', 1, 'wujic88@gmail.com', 0, 'Predrag', 'Vujic', 'www.wujic.com', 'Student, web developer', 'MATF', NULL, 'CVJMt', NULL),
(24, 'nejedinstveno korisn', '50947e17635bdda9613f75f593262c77', 1, 'erdos@mejl.com', 0, 'Milica', 'Jovanović', 'www.sajt.com', 'Student', 'OS Nenad Milosavljevic', NULL, '6COP1', NULL),
(25, 'lollol11', 'a9376dd52e04306dd3eba81b90bc56e4', 1, 'mirjana_1991@hotmail.com', 0, 'Mirjana', 'Kostić', '...................................', '..................................', '...................................', NULL, 'uAdE2', NULL),
(26, 'jupike', '28b9a989a6d8251938a7840ef253756a', 99, 'jupikearilje@gmail.com', 0, 'Filip', 'Lukovic', '', 'Student', 'Matematicki fakultet', NULL, '95bK3', NULL),
(27, 'skostic92', 'a40dbec3386d9a7fd3f44a87989356f5', 1, 'skostic9242@gmail.com', 0, 'Stefan', 'Kostic', 'nestolazno', 'nestolazno', 'nestolazno', NULL, '44T4P', NULL),
(28, 'enco164', 'c726044d658f914d6a36fb2ffce1f237', 99, 'enco164@gmail.com', 0, 'Uros', 'Milenkovic', 'www.matf.bg.ac.rs/~mi10164', 'VBA programer lol', 'Koga briga Stevo za tvoju osnovnu skolu, kaka', NULL, 'R2EBP', NULL),
(29, 'Himenosa', 'f0b5fb9749c27c42eb36ada38b3631c5', 1, 'himenosa@gmail.com', 0, 'Ivana', 'Ribić', 'www.mojsajt.com', 'student', 'OŠ Jovan Jovanović Zmaj', NULL, 'psDMZ', NULL),
(30, 'djiraja', '6f96ff2b283c6cfa58886197f656ceac', 1, 'djiraja@live.com', 0, 'Marko', 'Makaric', 'www.teslabg.edu.rs', 'Senin', 'ETS Nikola Tesla', NULL, '7Uxb5', NULL);

-- Materials
call addMaterial(1,13,'Prvi Materijal č', 'nesto.com/blah.pdf', 'PDF Dokument', '4.12.2013');
-- Proposals
call addProposal(1, 'predlog', 'opis neki tamo');
