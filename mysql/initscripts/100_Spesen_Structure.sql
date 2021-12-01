DROP TABLE IF EXISTS `SpesenFormular`;
CREATE TABLE `SpesenFormular` (
  `ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Personalnummer` varchar(255) NOT NULL,
  `UserID` int NOT NULL,
  `Datum` datetime NOT NULL,
  `Reiseziel` varchar(255) NOT NULL,
  `Essenskosten` int DEFAULT NULL,
  `Fahrtkosten` int DEFAULT NULL,
  `KM_Anzahl` int DEFAULT NULL,
  `Uebernachtung` int DEFAULT NULL,
  `Verkehrsmittel` varchar(255) NOT NULL,
  `Unterschrift` tinyint NOT NULL DEFAULT '0'
);