DROP TABLE IF EXISTS `SpesenFormular`;
CREATE TABLE `SpesenFormular` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Personalnummer` varchar(255) NOT NULL,
  `Datum` datetime NOT NULL,
  `Reiseziel` varchar(255) NOT NULL,
  `Essenskosten` int NOT NULL,
  `Fahrtkosten` int NOT NULL,
  `KM_Anzahl` int NOT NULL,
  `Uebernachtung` int NOT NULL,
  `Verkehrsmittel` varchar(255) NOT NULL,
  `Unterschrift` tinyint NOT NULL,l
  PRIMARY KEY (`ID`)
)