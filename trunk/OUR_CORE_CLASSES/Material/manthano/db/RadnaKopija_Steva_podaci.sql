INSERT INTO `activity` (`idActivity`, `Name`, `Description`, `BeginDate`, `CoverPicture`, `lft`, `rgt`, `Active`) VALUES
(1, 'Root', 'Glavni', '2004-12-20', '/assets/img/math.jpg', 1, 38, 1),
(2, 'Informatika', 'Ä‡ÄÄ‡ÄÄ‡ÄÄ‡Ñ›Ñ‡Ñ›Ñ‡Ñ›Ñ‡Ð°ÑÐ´', '2004-12-20', '/assets/img/math.jpg', 4, 25, 1),
(3, 'Matematika', 'MSmer', '2004-12-20', '/assets/img/math.jpg', 26, 37, 1),
(5, 'UAR', 'Opis', '2004-12-20', '/assets/img/math.jpg', 19, 20, 1),
(6, 'UOR', 'Opis', '2004-12-20', '/assets/img/math.jpg', 21, 22, 1),
(7, 'UVIT', 'Opis', '2004-12-20', '/assets/img/math.jpg', 23, 24, 1),
(8, 'Analiza 1', 'Opis', '2004-12-20', '/assets/img/math.jpg', 33, 34, 1),
(9, 'Analiza 2', 'Opis', '2004-12-20', '/assets/img/math.jpg', 35, 36, 1),
(24, 'Projektovanje Baza Podataka', 'Kurs osposobljava studenta da samostalno modelira baze podataka koje odgovaraju zahtevnim informacionim sistemima.', '2013-09-23', '/assets/img/math.jpg', 7, 18, 1),
(28, 'Predavanja', 'Opis predavanja', '2013-09-23', '/assets/img/math.jpg', 10, 11, 1),
(29, 'Vezbe', 'Opis vezbi', '2013-01-23', '/assets/img/math.jpg', 8, 9, 1),
(39, 'NoviAct', 'nekiopis', '2001-01-20', '/assets/img/math.jpg', 5, 6, 1);

INSERT INTO `activitycontains` (`idEvent`, `idActivity`) VALUES
(15, 5),
(10, 8),
(11, 8),
(12, 9),
(13, 9),
(14, 9),
(30, 28),
(31, 28),
(32, 28),
(33, 28),
(34, 29),
(35, 29),
(36, 29),
(37, 29),
(38, 29);

INSERT INTO `activityholder` (`user_id`, `idActivity`) VALUES
(1, 2),
(32, 24),
(33, 24),
(32, 28),
(33, 28),
(32, 29),
(33, 29);

INSERT INTO `activityparticipant` (`user_id`, `idActivity`) VALUES
(1, 2);

INSERT INTO `entity` (`id`, `tipEntiteta`) VALUES
(1, 'Activity'),
(2, 'Activity'),
(3, 'Activity'),
(4, 'Activity'),
(5, 'Activity'),
(6, 'Activity'),
(7, 'Activity'),
(8, 'Activity'),
(9, 'Activity'),
(10, 'Event'),
(11, 'Event'),
(12, 'Event'),
(13, 'Event'),
(14, 'Event'),
(15, 'Event'),
(16, 'Material'),
(17, 'Proposal'),
(18, 'Proposal'),
(19, 'Proposal'),
(20, 'Proposal'),
(21, 'Proposal'),
(22, 'Proposal'),
(23, 'Proposal'),
(24, 'Activity'),
(25, 'Activity'),
(26, 'Activity'),
(27, 'Activity'),
(28, 'Activity'),
(29, 'Activity'),
(30, 'Event'),
(31, 'Event'),
(32, 'Event'),
(33, 'Event'),
(34, 'Event'),
(35, 'Event'),
(36, 'Event'),
(37, 'Event'),
(38, 'Event'),
(39, 'Activity'),
(40, 'Activity');


INSERT INTO `event` (`idEvent`, `Name`, `Description`, `Venue`, `Date`, `Time`) VALUES
(10, 'A1 Event 1', 'First Event', '708', '2004-12-20', '13:00:00'),
(11, 'A1 Event 2', 'Second Event', '708', '2004-12-20', '13:00:00'),
(12, 'A2 Event 1', 'First A2 Event', '708', '2004-12-20', '13:00:00'),
(13, 'A2 Event 2', 'Secon A2 Event', '708', '2004-12-20', '13:00:00'),
(14, 'A2 Event 3', 'Third A2 Event', '708', '2004-12-20', '13:00:00'),
(15, 'Naziv predavanja', 'Prvo Event', '708', '2004-12-20', '13:00:00'),
(30, 'Semantičko modeliranje I', 'http://www.matf.bg.ac.rs/~gordana/PRED4.pdf', '718', '2004-12-20', '18:00:00'),
(31, 'Semantičko modeliranje II', 'http://www.matf.bg.ac.rs/~gordana/PRED5.pdf', '718', '2011-12-20', '18:00:00'),
(32, 'Indeksne Strukture B Stablo', 'http://www.matf.bg.ac.rs/~gordana/B-stablo.pdf', '718', '2018-12-20', '18:00:00'),
(33, 'Indeksne Strukture B+ Stablo', 'http://www.matf.bg.ac.rs/~gordana/B+stablo.pdf', '718', '2025-12-20', '18:00:00'),
(34, 'ER Model, EER Model', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/ERcas1.pdf', 'JAG2', '2001-12-20', '17:00:00'),
(35, 'Prevođenje EER modela u relacioni model', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/LogickoProjektovanje.G.PavlovicLazetic.pdf', 'JAG2', '2008-12-20', '17:00:00'),
(36, 'Triggeri', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/trigeri.pdf', 'JAG2', '2015-12-20', '17:00:00'),
(37, 'Normalizacija.', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/NFalgoritmi.pdf', 'JAG2', '2022-12-20', '17:00:00'),
(38, 'Fizička organizacija', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/B-stabla.pdf', 'JAG2', '2029-12-20', '17:00:00');

INSERT INTO `eventcontains` (`idEvent`, `idMaterial`) VALUES
(13, 16);

INSERT INTO `eventholder` (`user_id`, `idEvent`) VALUES
(32, 30),
(33, 30),
(32, 31),
(33, 31),
(32, 32),
(33, 32),
(32, 33),
(33, 33),
(32, 34),
(33, 34),
(32, 35),
(33, 35),
(32, 36),
(33, 36),
(32, 37),
(33, 37),
(32, 38),
(33, 38);

INSERT INTO `material` (`idMaterial`, `Name`, `URI`, `Type`, `Date`, `OwnerID`) VALUES
(16, 'Prvi Materijal č', 'nesto.com/blah.pdf', 'PDF Dokument', '2014-01-08 21:19:50', 1);

INSERT INTO `proposal` (`idProposal`, `UserProposed`, `Name`, `Description`) VALUES
(17, 1, 'predlog', 'opis neki tamo'),
(18, 14, 'probaNayiv', 'dsgvgdvdvshds'),
(19, 14, 'probasdasdasdasdyiv', 'dsgvgdvdvshds'),
(20, 14, 'probaNayiv', 'dsgvgdvdvshds'),
(21, 14, 'probasdasdasdasdyiv', 'dsgvgdvdvshds'),
(22, 1, 'Ime nekog preldoga', 'aÄsdkasdÄgaslÄkgjbaslÄnbaÄslnbklafbadsfg'),
(23, 1, 'Enko predlog', 'bla truc truc');

INSERT INTO `proposalowner` (`UserPropose`, `idProposal`, `UserProposed`) VALUES
(1, 23, 1);

INSERT INTO `proposalsupport` (`user_id`, `idProposal`) VALUES
(1, 17),
(14, 18),
(14, 19),
(14, 20),
(14, 21),
(1, 22),
(1, 23),
(31, 23);

INSERT INTO `user` (`user_id`, `username`, `password`, `acc_type`, `mail`, `Ext_login`, `Name`, `Surname`, `www`, `Proffession`, `School`, `ProfilePicture`, `salt`, `status`) VALUES
(1, 'stevos', 'f1ec24e771bb1191a796221cebca4a5a', 99,'teva.biz@gmail.com', 0, 'Stefan', 'Isidorović', '', 'Džabalebaroš/lelemud', 'OS Sveti Sava', NULL, '4eBgJ', NULL),
(2, 'peros', '3c0b3ff6f864f84f34ab10c8e87f4da1', 1,'pera@blah.com', 0, 'Pera', 'Perić', 'www,nekisajt.com', 'Limar', 'OS Sveti Sava', NULL, '3nEne', NULL),
(3, 'darko91', '3570407b25a12f0bc101fa4d6426a5ea', 1,'mi10034@matf.bg.ac.rs', 0, 'Darko', 'Vidakovic', 'www.redtube.com', 'Porno glumac', 'OS Popinski borci ', NULL, 'epZVv', NULL),
(4, 'techaz', 'ca4ebdf22334d5cc4b45093d71b383ac', 1,'vladimir.djordjevic@outlook.com', 0, 'Vladimir', 'Djordjevic', '', 'student', 'visa elektrotehnicka', NULL, 'eZswP', NULL),
(5, 'Kamicak', 'be7c555af2fd022311dac52e13d36397', 1,'mojimail@hotmail.com', 0, 'Tamara', 'Leposavic', 'www.mojsajt.com', 'studentkinja', 'Pavle Savic', NULL, 'FC03W', NULL),
(6, 'guza007', 'bafdbc6b3f1b3b6bc1f9eb607437bb8d', 1,'perperoglu@gmail.com', 0, 'Nenad', 'Avramovic', 'www.24hxxx.com', 'student', 'tesla', NULL, 'bkUIg', NULL),
(7, 'xboxdajmi', '757d1efe403e10c4a718c1e29eda50e1', 1,'bojants91@gmail.com', 0, 'Bojan', 'Nestorovic', 'www.fleksbuks.com', 'Cekam Stevu da mi da xbox', 'OS Sveti Sava! Kako si znao majmune!?', NULL, 'P5x2L', NULL),
(8, 'Blackbolt', '77efccb47961b952ed63c8d8d0847a7c', 1,'blackbolt990@gmail.com', 0, 'Bojan', 'Milic', 'www.malaskolamatemat', 'Student', 'Osnovna', NULL, 'LB8h3', NULL),
(9, 'Komek', 'f49bc0b731cb33b561e0c67b1f08120a', 1,'vlax2709@gmail.com', 0, 'Vladimir', 'Martić', 'www.daregej.com', 'student', 'Pancevacka Skola 2', NULL, '4c8Bd', NULL),
(10, 'Bambi', '4ce3945694a01ed3471e64306acd0416', 1,'lanamandic@hotmail.com', 0, 'Lana', 'Mandic', '', 'hote', 'OS Pavle Savic', NULL, 'N1Rd2', NULL),
(11, 'zibrr', '6a33f5f15e026184b7a5d71dc89febca', 1,'marko.stanacic@gmail.com', 0, 'Marko', 'Stanacic', '', 'Student', '', NULL, 'HU7lD', NULL),
(12, 'Bambi456', '3b39f840099e18bd0f14768d3532e7f8', 1,'lana.mandic.11@hotmail.com', 0, 'Lana', 'Mandic', 'www.mojsajt.com', 'menadzer', 'OS Pavle Savic', NULL, 'Fwz8u', NULL),
(13, 'zdravko', 'bb95dc6ac895bab4b42a4489c0316d93', 1,'fdsaf@fsd.com', 0, 'Zdravko', 'Rakic', '', 'Student', 'Peta gimnazija', NULL, 'ALwjw', NULL),
(14, 'VladaMT', 'eeddb415ea0c0cba0b67aa77a67ddd53', 1,'vladaherbalife@gmail.com', 0, 'Vlada', 'Ivanovic', '', 'wellness savetnik', 'Sedma Beogradska Gimnazija', NULL, '5w144', NULL),
(15, 'Deljenje0NijeDefinis', 'c044955f99a413ac9f8b59fcd478197c', 1,'missuchiha93@gmail.com', 0, 'Branislava', 'Zivkovic', '', 'Kucna pomocnica', 'OS Jovan Miodragovic', NULL, '361OU', NULL),
(16, 'lnluksa', 'bf9ae491b196f124d229ff94cd4dc75a', 1,'pamtii@gmail.com', 0, 'Nikola', 'Lukovic', '', 'Student', 'OS Arilje 2', NULL, '85v3z', NULL),
(17, 'jo.stan8', '539db3d1bb53745732b66502989e056b', 1,'jovanalp.stan@gmail.com', 0, 'Jovana', 'Stanimirović', 'www.nemasajta.com', 'dangubljenje', 'valjevska gimnazija', NULL, 'S269A', NULL),
(18, 'divic', 'a4eb5d39cafd17a1f7287288e7c4942a', 1,'logotijedojaja@vrh.com', 0, 'Nikola', 'Divic', 'www.mojsajt.com', 'Metalokobasičar', 'MATF', NULL, 'uFzqb', NULL),
(19, 'prvul', 'b205799a73190fa2512f7fae09b1721a', 1,'petar.prvulovic@gmail.com', 0, 'petar', 'prvulovic', 'prvulovic.net', 'Mladji prvulovic', 'OS Sveti Prvul', NULL, 'UioRH', NULL),
(20, 'poiuy', '6bfbcf7e783550b6d631d46c1b56554e', 1,'example@example.com', 0, 'Nikola', 'Nikolic', '', 'jibubkib', 'bikb uiuojv', NULL, '2qi7M', NULL),
(21, 'savicmi', '89695bf727267a65b5b0d327739dac52', 1,'savicmi@gmail.com', 0, 'Miloš', 'Savić', '', 'Informatičar', 'OS Miodrag Vuković', NULL, 'jhjWD', NULL),
(22, 'Stefi', 'd1d81fd2e219a6234d8d5fa3c75431a4', 1,'stefanacerovina@gmail.com', 0, 'Stefana', 'Cerovina', '', 'Student', 'OS Varvarin 2', NULL, 'C1Ct5', NULL),
(23, 'wujic', '5441d8e76f748450c105b5a8442a76b6', 1,'wujic88@gmail.com', 0, 'Predrag', 'Vujic', 'www.wujic.com', 'Student, web developer', 'MATF', NULL, 'CVJMt', NULL),
(24, 'nejedinstveno korisn', '50947e17635bdda9613f75f593262c77', 1,'erdos@mejl.com', 0, 'Milica', 'Jovanović', 'www.sajt.com', 'Student', 'OS Nenad Milosavljevic', NULL, '6COP1', NULL),
(25, 'lollol11', 'a9376dd52e04306dd3eba81b90bc56e4', 1,'mirjana_1991@hotmail.com', 0, 'Mirjana', 'Kostić', '....................', '..................................', '...................................', NULL, 'uAdE2', NULL),
(26, 'jupike', '28b9a989a6d8251938a7840ef253756a', 99,'jupikearilje@gmail.com', 0, 'Filip', 'Lukovic', '', 'Student', 'Matematicki fakultet', NULL, '95bK3', NULL),
(27, 'skostic92', 'a40dbec3386d9a7fd3f44a87989356f5', 1,'skostic9242@gmail.com', 0, 'Stefan', 'Kostic', 'nestolazno', 'nestolazno', 'nestolazno', NULL, '44T4P', NULL),
(28, 'enco164', 'c726044d658f914d6a36fb2ffce1f237', 99,'enco164@gmail.com', 0, 'Uros', 'Milenkovic', 'www.matf.bg.ac.rs/~m', 'VBA programer lol', 'Koga briga Stevo za tvoju osnovnu skolu, kaka', NULL, 'R2EBP', NULL),
(29, 'Himenosa', 'f0b5fb9749c27c42eb36ada38b3631c5', 1,'himenosa@gmail.com', 0, 'Ivana', 'Ribić', 'www.mojsajt.com', 'student', 'OŠ Jovan Jovanović Zmaj', NULL, 'psDMZ', NULL),
(30, 'djiraja', '6f96ff2b283c6cfa58886197f656ceac', 1,'djiraja@live.com', 0, 'Marko', 'Makaric', 'www.teslabg.edu.rs', 'Senin', 'ETS Nikola Tesla', NULL, '7Uxb5', NULL),
(31, 'dummy', '84701b29c71bac3563d83f5eccebc2f1', 1,'dum@dum.com', 0, 'Dummy', 'User', 'www.truc.com', 'Glupan', 'Bla bla truć', NULL, 'Qj41x', NULL),
(32, 'ivana', 'e18a9ee54d08e2adcf407e580c2e661f', 99,'ivana@matf.bg.ac.rs', 0, 'Ivana', 'Tanasijević', 'http://poincare.matf.bg.ac.rs/~ivana', 'Asistent', 'Matematički Fakultet', NULL, 'l44md', NULL),
(33, 'jgraovac', '6a5752029867f709d67b938a2bab38d5', 99,'jgraovac@matf.bg.ac.rs', 0, 'Jelena', 'Graovac', 'http://poincare.matf.bg.ac.rs/~jgraovac', 'Asistent', 'Matematički Fakultet', NULL, 't6ug5', NULL),
(34, 'andjelkaz', '1bc1b0d3a4a8ae583b64de740cbb93cc', 99,'andjelkaz@matf.bg.ac.rs', 0, 'Andjelka', 'Zečević', 'www.matf.bg.ac.rs/~andjelkaz', 'Asistent', 'Matematički Fakultet', NULL, 'WBxjv', NULL);
