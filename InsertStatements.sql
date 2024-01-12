CREATE DATABASE twin_cities;

source /init-db-twin.sql

INSERT INTO `Cities` (`CurrencyID`, `Name`, `Population`, `CountryID`, `Mayor`, `YearFounded`, `Latitude`, `Longitude`) VALUES
('GBP', 'Liverpool', 917000, 'GBR', 'Steve Rotheram', '1207', 53.397300, -2.973200),
('EUR', 'Cologne', 1144000, 'DEU', 'Henriette Reker', '38 BC', 50.941300, 6.958300);

INSERT INTO `Currency` (`CurrencyID`, `ExchangeRate`, `CurrencyName`, `Symbol`) VALUES
('EUR', 1.15, 'Euro', '€'),
('GBP', 1.00, 'Pound Sterling', '£');

INSERT INTO `Weather` (`CityID`, `Temperature`, `Humidity`, `WindSpeed`, `WindDirection`, `Condition`) VALUES
(1, NULL, NULL, NULL, NULL, NULL),
(2, NULL, NULL, NULL, NULL, NULL);

INSERT INTO `Category` (`CategoryName`, `IconURL`) VALUES
('Botanical Garden', './icons/botanicalGarden.png'),
('Park', './icons/park.png'),
('Entertainment', './icons/entertainment.png'),
('Museum', './icons/museum.png'),
('Popular', './icons/popular.png'),
('Bridge', './icons/bridge.png'),
('Shopping', './icons/shopping.png'),
('Zoo', './icons/zoo.png');

INSERT INTO `Place_Category` (`PlaceID`, `CategoryID`) VALUES (1, 5), (2, 5), (3, 5), (3, 7), (4, 4), (5, 2), (6, 4), (6, 5), (7, 2), (8, 4), (9, 4), (10, 3), (11, 7), (12, 2), (13, 5), (14, 4), (15, 4), (16, 5), (16, 8), (17, 3), (18, 2),(19, 5), (19, 6), (20, 5), (20, 7), (21, 1), (22, 7), (23, 7), (24, 2);

INSERT INTO `Places_of_Interest` (`CityID`, `Name`, `Type`, `Capacity`, `Photos_Flickr_API`, `OpeningHours`, `Description`, `Latitude`, `Longitude`) VALUES
(1, 'Liverpool Cathedral', 'Popular', NULL, NULL, '0900-1800', 'A historical cathedral in Liverpool', 53.397300, -2.973200),
(1, 'Anfield Stadium', 'Popular', NULL, NULL, '0900-1800', 'Famous football stadium, home of Liverpool FC', 53.430800, -2.960800),
(1, 'Liverpool ONE Shopping Centre', 'Shopping', NULL, NULL, '0900-1800', 'Major retail and leisure complex', 53.403900, -2.987500),
(1, 'World Museum', 'Museum', NULL, NULL, '0900-1800', 'Museum with extensive collections covering world cultures', 53.410100, -2.979600),
(1, 'Sefton Park', 'Park', NULL, NULL, '0900-1800', 'A large, historic park in south Liverpool', 53.382800, -2.938200),
(1, 'Tate Liverpool', 'Museum', NULL, NULL, '0900-1800', 'Art museum and gallery', 53.400600, -2.994500),
(1, 'Calderstones Park', 'Park', NULL, NULL, '0900-1800', 'A public park with historic and botanical significance', 53.381700, -2.894000),
(1, 'Merseyside Maritime Museum', 'Museum', NULL, NULL, '0900-1800', 'Museum dedicated to the maritime history of Liverpool', 53.401400, -2.992900),
(1, 'Eureka! Science + Discovery', 'Museum', NULL, NULL, '0900-1800', 'Interactive science and discovery center for children', 53.409700, -3.016600),
(1, 'Hot Water Comedy Club', 'Entertainment', NULL, NULL, '0900-1800', 'Popular comedy club in Liverpool', 53.401700, -2.971000),
(1, 'Liverpool Shopping Park', 'Shopping', NULL, NULL, '0900-1800', 'Shopping park with various retail outlets', 53.410300, -2.924600),
(1, 'Newsham Park and Garden', 'Park', NULL, NULL, '0900-1800', 'Historic park with a lake and formal gardens', 53.418000, -2.934100),
(2, 'Cologne Cathedral', 'Popular', NULL, NULL, '1000-1700', 'A famous Roman Catholic cathedral in Cologne', 50.941300, 6.958300),
(2, 'Ludwig Museum', 'Museum', NULL, NULL, '1000-1700', 'Museum in Cologne with a collection of modern art', 50.940900, 6.960200),
(2, 'Cologne Chocolate Museum', 'Museum', NULL, NULL, '1000-1700', 'Museum dedicated to the history of chocolate', 50.932600, 6.964400),
(2, 'Cologne Zoo', 'Zoo', NULL, NULL, '1000-1700', 'Zoological garden with a large variety of species', 50.955400, 6.958300),
(2, 'Kölner Weihnachtscircus', 'Entertainment', NULL, NULL, '1000-1700', 'Popular Christmas circus in Cologne', 50.946900, 6.987700),
(2, 'Rheinpark', 'Park', NULL, NULL, '1000-1700', 'Large, well-maintained park along the Rhine River', 50.945200, 6.973900),
(2, 'Hohenzollern Bridge', 'Bridge', NULL, NULL, '1000-1700', 'Famous bridge in Cologne, known for its love locks', 50.941400, 6.965800),
(2, 'Neumarkt', 'Shopping', NULL, NULL, '1000-1700', 'Central square in Cologne, known for shopping', 50.935500, 6.948000),
(2, 'Subtropenschauhaus in der Flora', 'Botanical Garden', NULL, NULL, '1000-1700', 'Botanical garden with a variety of subtropical plants', 50.959800, 6.968400),
(2, 'Belgian Quarter', 'Shopping', NULL, NULL, '1000-1700', 'Trendy neighborhood in Cologne with boutiques and cafes', 50.941100, 6.934800),
(2, 'Schildergasse', 'Shopping', NULL, NULL, '1000-1700', 'One of the busiest shopping streets in Cologne', 50.937200, 6.934100),
(2, 'Volksgarten Köln', 'Park', NULL, NULL, '1000-1700', 'Public park in Cologne offering recreational activities', 50.920200, 6.947600);
