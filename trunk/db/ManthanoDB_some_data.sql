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
-- Materials
call addMaterial('admin',13,'Prvi Materijal č', 'nesto.com/blah.pdf', 'PDF Dokument', '4.12.2013');
-- Proposals
call addProposal('admin', 'predlog', 'opis neki tamo');