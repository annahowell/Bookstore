SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `bookstore`;
CREATE DATABASE `bookstore` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `bookstore`;


DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `categoryNo` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`categoryNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `category` (`categoryNo`, `name`) VALUES
(1, 'Books'),
(2, 'Stationary'),
(3, 'Gifts');



DROP TABLE IF EXISTS `order_item`;
CREATE TABLE `order_item` (
  `orderNo` int(11) NOT NULL,
  `productNo` int(11) NOT NULL,
  `quantityOrdered` int(11) NOT NULL,
  `pricePaid` double NOT NULL,
  PRIMARY KEY (`orderNo`,`productNo`),
  KEY `order_item_fk1` (`productNo`),
  CONSTRAINT `order_item_fk0` FOREIGN KEY (`orderNo`) REFERENCES `placed_order` (`orderNo`),
  CONSTRAINT `order_item_fk1` FOREIGN KEY (`productNo`) REFERENCES `product` (`productNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `order_item` (`orderNo`, `productNo`, `quantityOrdered`, `pricePaid`) VALUES
(1, 2,  2,  4.99),
(1, 8,  1,  4.5),
(1, 10, 1,  7.99),
(1, 11, 2,  1.99),
(2, 3,  1,  8.99),
(3, 7,  1,  2.99),
(3, 20, 3,  1.99),
(4, 25, 1,  179.98),
(5, 22, 1,  8.99),
(5, 27, 1,  4.99),
(6, 16, 2,  0.29),
(6, 21, 4,  3.99),
(7, 21, 2,  3.99),
(8, 10, 2,  7.99),
(8, 24, 1,  19.99),
(9, 6,  2,  4.99),
(10,  14, 3,  12.99),
(10,  17, 1,  5.99),
(10,  30, 1,  44.95),
(11,  1,  1,  14.99);



DROP TABLE IF EXISTS `placed_order`;
CREATE TABLE `placed_order` (
  `orderNo` int(11) NOT NULL AUTO_INCREMENT,
  `userNo` int(11) NOT NULL,
  `dateOrdered` datetime NOT NULL,
  `totalPaid` double NOT NULL,
  PRIMARY KEY (`orderNo`),
  KEY `order_fk0` (`userNo`),
  CONSTRAINT `order_fk0` FOREIGN KEY (`userNo`) REFERENCES `user` (`userNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `placed_order` (`orderNo`, `userNo`, `dateOrdered`, `totalPaid`) VALUES
(1, 2,  '2017-12-03 19:44:38',  26.45),
(2, 2,  '2017-12-08 20:46:58',  8.99),
(3, 3,  '2017-12-09 12:03:50',  8.96),
(4, 4,  '2017-12-09 12:12:48',  179.98),
(5, 5,  '2017-12-09 12:14:14',  13.98),
(6, 6,  '2017-12-09 12:16:55',  16.54),
(7, 6,  '2017-12-09 12:17:06',  7.98),
(8, 7,  '2017-12-09 12:19:19',  35.97),
(9, 6,  '2017-12-09 12:19:40',  9.98),
(10,  8,  '2017-12-09 12:23:51',  89.91),
(11,  2,  '2017-12-09 20:26:10',  14.99);



DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `productNo` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isbn` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(2550) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `stockLevel` int(11) NOT NULL,
  `removed` int(11) NOT NULL DEFAULT '0',
  `imageName` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'placeholder.png',
  `dateModified` datetime NOT NULL,
  `categoryNo` int(11) NOT NULL,
  PRIMARY KEY (`productNo`),
  KEY `categoryNo_fk0` (`categoryNo`),
  CONSTRAINT `categoryNo_fk0` FOREIGN KEY (`categoryNo`) REFERENCES `category` (`categoryNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product` (`productNo`, `name`, `author`, `isbn`, `description`, `price`, `stockLevel`, `removed`, `imageName`, `dateModified`, `categoryNo`) VALUES
(1, 'Lord of the Rings',  'JRR Tolkien',  '02611032532',  'A sumptuous one-volume edition of Tolkien\'s classic masterpiece that is fully illustrated throughout in watercolour by the acclaimed artist, Alan Lee, and housed in a special transparent slipcase.',  14.99,  49, 0,  '3238127687.jpg', '2017-12-09 00:24:34',  1),
(2, 'The Last Thing She Ever Did',  'Gregg Olsen',  '1542046424', 'In The Last Thing She Ever Did, Gregg Olsen gives us a glimpse of humanity\'s dark side and what people are willing to do to protect themselves. He also shows us that even our closest friends harbour disturbing secrets from their past secrets too painful and damaging to share.\r\n\r\nGregg Olsen has been writing bestselling true crime for decades. He has interviewed sociopaths, serial killers and predators and their families so he knows a lot about that dark side.\r\n\r\nWhen Liz makes a horrible mistake and follows it up with the absolute worst decision of her life, I couldn\'t help but wonder what would have happened if she had just come clean from the start. Or if her best friend had not answered the phone call that took her eyes off her son for a matter of seconds. And if they were truly best friends, would their friendship withstand a mistake so dire?\r\n\r\nAt the core of this riveting novel, Gregg Olsen poses that exact question: What if I told you something unforgivable? Would you give me a second chance?\'\r\n\r\nWould you?', 4.99, 45, 0,  '2482316405.jpg', '2017-12-10 12:04:40',  1),
(3, 'Closer To Home', 'Heleyne Hammersley', '1912175673', 'If you love psychological thrillers, discover a new novel today which will have you gripped form start to finish! \r\n\r\nFamily. Secrets. Murder.\r\nNewly promoted DI Kate Fletcher has reluctantly returned to her home town after a twenty-year absence and a recent divorce.  The discovery of a child\'s body near the estate where Kate grew up has her rushing back to Thorpe - a place of bad memories and closed mouths. \r\n\r\nAs her team investigate the murder, they keep hitting dead ends. The community is reluctant to reopen old wounds and retell old stories.  But Kate\'s history refuses to stay buried.\r\n\r\nThen another child disappears. \r\n\r\nCan Kate solve the case and right the wrongs from her past?', 8.99, 0,  0,  '5350917871.jpg', '2017-12-09 00:11:41',  1),
(4, 'Snow Light', 'Snow Light Kindle Edition',  '1912175770', 'Looking for a dark detective thriller which will have you on the edge of your seat?\r\n\r\nWhen Detective Inspector Nathaniel Thomas encounters a man attacking a young woman in a local park, the DI is unable to save her. Out of guilt, Thomas quits his job at Homicide Headquarters and relocates to the tiny village of Turtleville, where he regains control of himself and begins to enjoy life again.                           \r\n\r\nHowever, a year later, all the guilt and shame of the park murder re-emerges when a local hermit, Ethan Wright, is murdered with an unusual weapon and left on display in the centre of the village.',  7.99, 33, 0,  '7989601844.jpg', '2017-12-02 17:38:55',  1),
(5, 'Paladins of Shannara: The Black Irix', 'Terry Brooks', '32423156', 'Shea Ohmsford has had quite enough of quests. A year after surviving a harrowing odyssey, he is still plagued by troubling memories and dreams. A mysterious trafficker in spells and potions provides a restorative nostrum for the stricken Shea . . . along with a warning: Shea will break his vow to never again leave Shady Vale. And then the potion-maker\'s prophecy comes to pass.\r\n \r\nA thief, adventurer, and notoriously charismatic rogue, Panamon Creel unexpectedly appears in the Vale with a request for his longtime friend, Shea journey into the untamed northland, infiltrate the stronghold of a sinister dealer in stolen goods, and capture a precious artifact: the sacred Black Irix. Creel wishes to return this treasure to its rightful owners. Shea cannot refuse such a just cause. But what lies behind the black castle walls they must breach? And will this quest truly be their last?', 3.99, 0,  0,  '1623681145.jpg', '2017-12-10 12:05:58',  1),
(6, 'Anne Frank: Her Life and Legacy',  'Jemma J. Saunders',  '1522978054', 'Anne Frank is the most well-known victim of the Holocaust.\r\n\r\nIn 1945, at the age of fifteen she died at Bergen-Belsen concentration camp, becoming one of the six million Jews who were murdered in Europe under the Nazi regime.\r\n\r\nBut through her writing her memory lives on. ',  4.99, 52, 0,  '8883988410.jpg', '2017-12-02 17:44:49',  1),
(7, 'All the Rebel Women',  'Kira Cochrane',  'B00H7G1DMY', 'On a bright day at the Epsom Derby, 4 June 1913, Emily Wilding Davison was hit by the kings horse in one of the defining moments of the fight for women\'s suffrage - what became known as feminism\'s first wave.\r\n\r\nThe second wave arose in the late-1960s, activists campaigning tirelessly for women\'s liberation, organising around a wildly ambitious slate of issues - a struggle their daughters continued in the third wave that blossomed in the early-1990s.\r\n\r\nNow, a hundred years on from the campaign for the vote, fifty years since the very first murmurs of the second wave movement, a new tide of feminist voices is rising. Scattered across the world, campaigning online as well as marching in the streets, women are making themselves heard in irresistible fashion.',  2.99, 442,  0,  '4273218797.jpg', '2017-12-02 19:18:46',  1),
(8, 'The Great Charity Scandal',  'David Craig',  'B00PNPOQGG', 'There are more than 195,289 registered charities and charitable institutions in the UK that spend close to Â£80 billion of our money a year. Plus there are another 191,000 charities that don\'t need to register. \r\n\r\nAccording to a charity regulatory body, these charities make a huge thirteen billion asks\' for donations every year - that\'s around two hundred asks\' for every man, woman and child in the UK.\r\n\r\nBritain\'s registered charities claim that almost ninety pence in every pound we give them is spent on what they call charitable activities\'. But with many of our best-known charities, the real figure is likely to be less than fifty pence in every pound. With too many charities, at least half of our money goes on management, administration, strategy development, political campaigning and fundraising - not on what most of us would consider good causes\'.\r\n\r\nBut does Britain really need so many charities? And do our charities spend enough of our money on good causes?', 4.5,  19, 0,  '3864834523.jpg', '2017-12-10 12:05:31',  1),
(9, 'Good Me Bad Me', 'Ali Land', 'B01HOCLK14', 'When Annie hands her mother over to the police she hopes for a new start in life - but can we ever escape our past?\r\n\r\n\'NEW NAME. NEW FAMILY. SHINY. NEW. ME.\'\r\n\r\nAnnie\'s mother is a serial killer. The only way Annie can make it stop is to hand her in to the police.\r\n\r\nWith a new foster family and a new name - Milly - she hopes for a fresh start. Now, surely, she can be whoever she wants to be. But as her mother\'s trial looms, the secrets of Milly\'s past won\'t let her sleep . . .\r\n\r\nBecause Milly\'s mother is a serial killer. And blood is thicker than water...',  9.99, 94, 0,  '8038061219.jpg', '2017-12-02 19:20:12',  1),
(10,  'Pukka Pads A4 Metallic Jotta', '', '', 'Wirebound Notebook (Pack of 3)', 7.99, 54, 0,  '7144298575.jpg', '2017-12-02 20:16:18',  2),
(11,  'Duo Sparrow Bird House & Keyrings',  '', '', 'Each cute sparrow keyring will sit in the house perfectly.\r\n\r\nThey double as a whistle which could come in very handy when out and about, to call for help or attention.\r\n\r\nWhilst in their love nest the sparrows will be happy to whistle sweet nothings to eachother.\r\n\r\nEasy to perch on any wall using the foam tapes or screws provided. Simply pop your keys on the keyrings and you\'re away!\r\n\r\nPerfect housewarming gift.',  1.99, 516,  0,  '4910463754.jpg', '2017-12-02 20:25:26',  3),
(12,  'Guinness World Records 2018',  'Guinness World Records 2018',  '1910561711', 'The record-breaking records annual is back and packed with more incredible accomplishments, stunts, cutting-edge science and amazing sporting achievements than ever before. With more than 3,000 new and updated records and 1,000 eye-popping photos, it has thousands of new stats and facts and dazzling new features.\r\n\r\nThere is so much to explore inside. Go on a whirlwind tour of the planet\'s most amazing places, from the largest swamps to the deepest points on Earth. ',  7.99, 173,  0,  '1010052526.jpg', '2017-12-09 00:55:29',  1),
(13,  'PHP & MySQL for Dummies',  'Janet Valade', '0470527587', 'Learn to use the tools that bring Web sites to life it′s easy and fun!\r\n\r\n', 21.99,  323,  1,  '3386368875.jpeg',  '2017-12-09 01:11:43',  1),
(14,  'Tribal Pop: Memo Pad', '', '', 'Camille Walala is a textile designer, whose signature Tribal Pop style incorporates bold colours and graphic shapes. Camille\'s influences comprise the Memphis movement, the Ndebele tribe and optical art master, Vasarely, alongside the simple desire to put a smile on people\'s faces. Recent collaborations include Giorgio Armani, Topshop, Caterpillar, Harrods and Selfridges as well as Lisa Gorman and Third Drawer Down in Australia. She runs an eponymous studio in East London.\r\n\r\n',  12.99,  190,  0,  '6598873184.jpg', '2017-12-09 00:58:48',  2),
(15,  'Deluxe Leather Look Sticky ',  '', '', 'Deluxe Leather Look Sticky Memo Desktop Box Set With Arrow Flags Index Tabs',  4.49, 571,  0,  '5194193839.jpg', '2017-12-09 01:00:57',  2),
(16,  'Pencil Rubber Erasers',  '', '', 'Funky pencil erasers your kids will love!',  0.29, 26, 0,  '2492437121.jpg', '2017-12-09 01:10:50',  2),
(17,  'Soft Flexible Bendy Pencils ', '', '', 'These soft pencils are able to bend and twist in all directions.\r\n\r\nHelp your kids to give full play to make all kinds of craft as children toy.\r\n\r\nCan also be used for writing and there is also an eraser an the end of each pencil.',  5.99, 333,  0,  '1847108711.jpg', '2017-12-09 01:03:58',  2),
(18,  'Staedtler Medium Rainbow Ballpoint Pens',  '', '', 'Ergonomic triangular shape for relaxed and easy writing\r\n\r\nParticularly smooth writing performance\r\n\r\nPack contains 10 ballpoint pens in assorted rainbow colours (pen barrel matches the ink colour)\r\n\r\nAirplane-safe - automatic pressure equalization prevents pen from leaking on board aircraft', 3.35, 51, 0,  '6849567422.jpg', '2017-12-09 01:05:44',  2),
(19,  'Porpoise Mini Desktop Stapler',  '', '', 'Product Dimensions is about 6*2.5CM.\r\n\r\nUseful and portable mini stapler is suitable for school, home or office.\r\n\r\nLightweight and compact. This mini stapler is convenient to carry and easy to use.\r\n\r\nChild friendly and Environmental. The unique design add your fun in the working time. You will love it.',  0.99, 816,  0,  '7803699814.jpg', '2017-12-09 01:07:13',  2),
(20,  'Bostik Blu Tack Handy',  '', '', 'Bostik handy blu tack\r\n\r\nThe original re-usable adhesive.\r\nClean, safe and easy to use.\r\nProvides an ideal alternative to drawing pins and sticky tape.\r\nHolds up; posters, cards party decorations, maps, messages.\r\nHolds down : telephones and calculators to desks, ornaments etc.', 1.99, 7738, 0,  '6902103523.jpg', '2017-12-09 01:08:09',  2),
(21,  '25m Parcel Tape',  '', '', 'Just Stationery 25m Parcel Tape (Roll of 2)',  3.99, 325,  0,  '1566397544.jpg', '2017-12-09 01:09:07',  2),
(22,  'Phone Stand & Pencil Holder',  '', '', 'Elephant nose can holder mobile phone, the body can be used as a pen holder, can hold pens, pencils, rulers, highlighters and small stationery things\r\n\r\nMini size can be folded for easy storage Compatible for most phones and tablet.Convenient for carrying on with lightweight design\r\n\r\nElephant cute image and stand the phone & ipad stablely either horizontally or vertically',  8.99, 518,  0,  '6541882778.jpg', '2017-12-09 01:10:18',  2),
(23,  'Now That\'s What I Call Music! 98',  'Various',  'B076FC5NW6', 'Includes FREE MP3 version of this album.', 12.99,  38, 0,  '6162013846.jpg', '2017-12-09 11:47:51',  3),
(24,  'Exploding Kittens',  '', '', 'Exploding Kittens: A Card Game About Kittens and Explosions and Sometimes Goats\r\n\r\nExploding Kittens is a card game for people who are into kittens and explosions and laser beams and sometimes goats. In this highly-strategic, kitty-powered version of Russian Roulette, players draw cards until someone draws an Exploding Kitten, at which point they explode, they are dead, and they are out of the game -- unless that player has a defuse card, which can defuse the Kitten using things like laser pointers, belly rubs, and catnip sandwiches. All of the other cards in the deck are used to move, mitigate, or avoid the Exploding Kittens.', 19.99,  66, 0,  '9102125929.jpg', '2017-12-09 11:49:49',  3),
(25,  'Cozmo by Anki',  'Anki', '', 'A charming and intelligent robotic sidekick that explores, remembers and reacts to his environment - and to you!\r\nChallenge him to games, or turn on Explorer Mode to see things from Cozmo\'s perspective\r\n\r\nUnlock new games and upgrades the more you play\r\n\r\nRigorously tested for durability and security\r\n\r\nRequirements: A compatible iOS or Android device and the free Cozmo app',  179.98, 11, 0,  '6981970430.jpg', '2017-12-09 11:51:58',  3),
(26,  'Dunkirk ', '', '', 'Limited edition 2 disc DVD including behind the scenes content showcasing how the miracle of Dunkirk was recreated.\r\n\r\nFrom filmmaker Christopher Nolan (Interstellar, Inception, The Dark Knight Trilogy) comes the epic action thriller Dunkirk.\r\n\r\nDunkirk opens as hundreds of thousands of British and Allied troops are surrounded by enemy forces. Trapped on the beach with their backs to the sea, they face an impossible situation as the enemy closes in.\r\n\r\nThe story unfolds on land, sea and air. RAF Spitfires engage the enemy in the skies above the Channel, trying to protect the defenseless men below. Meanwhile, hundreds of small boats manned by both military and civilians are mounting a desperate rescue effort, risking their lives in a race against time to save even a fraction of their army.',  13.99,  38, 0,  '8687865432.jpg', '2017-12-10 12:07:28',  3),
(27,  'Nuby Octopus Floating Bath Toy', '', '', 'Nuby Octopus Floating Bath Toy is bright, colourful and fun. The 3 rings are designed to toss and hook onto the tentacles and encourage counting. Nuby have a wide range of Bath Time toys to make bath time fun.\r\n\r\nNuby comes to you from the USA and is one of the World\'s Leading Brands in infant feeding, teething and drinking products. Recently launched in the UK Nuby has won numerous prestigious awards including Mother&Baby, Practical Parenting and Prima Baby for Newborn Feeding, Drinking, Teething & Toys which are all independently tried and tested and voted for by parents themselves\r\n\r\n',  4.99, 48, 0,  '2853791183.jpg', '2017-12-10 12:07:37',  3),
(28,  'Blue Planet: The Collection',  'Sir David Attenborough', '', 'The Blue Planet is the first ever comprehensive series on the natural history of the world\'s oceans.\r\n\r\nNarrated by David Attenborough, the extraordinary images of this marine epic reveal the sea at its most fearsome and alluring, exposing some of its best kept secrets. Enter a world of breathtaking beauty and discover new species, visit unseen habitats and witness stories of survival never caught on camera before.\r\n\r\nAccompanied by a superb soundtrack from the award-winning composer George Fenton, this DVD is further enhanced by the technical brilliance of the film and sound quality.', 19.99,  46, 0,  '6279061101.jpg', '2017-12-09 11:55:52',  3),
(29,  'Wireless Bluetooth Speaker', '', '', 'ZoeeTree Bluetooth 4.2 Wireless Speaker for 10 hrs Music Streaming & Hands-Free Calling / 5W + 5W 40mm Dual Driver Speakerphone, Built-in Mic, 3.5mm Audio Port, Rechargeable Battery for Indoor & Outdoor Use. ', 24.99,  91, 0,  '1821077036.jpg', '2017-12-09 11:56:44',  3),
(30,  'Spitalfields Retro DAB', '', '', 'ADVANCED DAB/DAB+ \'FUTURE READY\' RADIO: The very latest DAB+ and FM Technology for crystal-clear reception. DAB+ ensures the Spitalfields receives more stations than other DAB Radios. It also has FM Radio for more choice. \"Future Ready\" ensures the radio has been designed to be compatible in the future.', 44.95,  22, 0,  '9446251126.jpg', '2017-12-09 11:57:30',  3),
(31,  'Liverpool FC 2017/18 Top Trumps',  '', '', 'When the Reds Go Marching in! You are not a Reds fan until you get your hands on the Liverpool FC 2017/18 Top Trumps pack. This special edition is jam-packed with interesting facts, eye-catching player stats and cool images of your favourite Liverpool FC stars. Discover how many goals Sadio Mané has scored or if Nathaniel Clyne\'s defence rating is higher than Dejan Lovren?', 7.99, 231,  0,  '9405000189.jpg', '2017-12-09 11:58:27',  3);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userNo` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `userlevel` int(11) NOT NULL DEFAULT '1',
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `add1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `county` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userNo`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- All passwords below are identical and are: Password123
INSERT INTO `user` (`userNo`, `username`, `password`, `userlevel`, `firstName`, `lastName`, `email`, `add1`, `city`, `county`, `postcode`) VALUES
(1,	'admin',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	0,	'admin',	'admin',	'-',	'-',	'-',	'-',	'-'),
(2,	'anna',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'Anna',	'Thomas',	's4927945@bournemouth.ac.uk',	'1 Bournemouth Road',	'Poole',	'Dorset',	'BH1 1AB'),
(3,	'mary79',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'Mary',	'Jones',	'mary79@gmail.com',	'67 Sherbourne Drive',	'Reading',	'Berkshire',	'RG45 1UH'),
(4,	'ryan1995',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'Ryan',	'Harris',	'r.harris@htomail.co.uk',	'37 Croner Road',	'Poole',	'Dorset',	'BH15 1HH'),
(5,	'steve1',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'Steve',	'Midland',	'steve1@outlook.com',	'99 London Road',	'London',	'Westminster',	'LN1 1ZJ'),
(6,	'brenda',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'brenda',	'bradly',	'bmi1956@yahoo.co.uk',	'21 Woolaton Road',	'Bournemouth',	'Dorset',	'BH21 18L'),
(7,	'elaine',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'elaine',	'needs',	'e.needs@aol.com',	'23 Bristol Street',	'Bristol',	'Avon',	'BR78 1UU'),
(8,	'g_man',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'Gernot',	'Liebchen',	'g.man@bournemouth.ac.uk',	'19 Awesome Lane',	'Bournemouth',	'Dorset',	'AW5 0ME'),
(9,	'andy',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'Andy',	'Logan',	'logan@cusardmail.com',	'78 Huddersfield Lane',	'Yorkshire',	'Yorkshire',	'YK8 8AU'),
(10,	'test',	'$6$rounds=150000$PerUserCryptoRan$ghbyZUVMskL.FszyCzp5PW96j6VIk.qccCmNg7YcBSOr5gRLZ.WSc0jTN5WRM/1QG3K7pqluIkBPaLFkR60mR.',	1,	'aa',	'aa',	'aa@co.uk',	'a',	'a',	'a',	'a');
-- All passwords above are identical and are: Password123
