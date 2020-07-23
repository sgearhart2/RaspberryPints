ALTER TABLE  `pours` ADD  `pinId` INT( 11 ) NULL AFTER  `tapId`;
ALTER TABLE  `taps` ADD  `pinId` INT( 11 ) NULL AFTER  `tapNumber`;
INSERT INTO `raspberrypints`.`config` (`id`, `configName`, `configValue`, `displayName`, `showOnPanel`, `createdDate`, `modifiedDate`) VALUES (NULL, 'useFlowMeter', '0', 'Use Flow Monitoring', '1', NOW(), NOW());
ALTER TABLE `pours` CHANGE `amountPoured` `amountPoured` FLOAT( 6, 3 ) NOT NULL;
ALTER TABLE  `pours` ADD  `pulses` INT( 6 ) NOT NULL AFTER  `amountPoured`;


-- Adding untappdId
INSERT INTO `raspberrypints`.`config` (`id`, `configName`, `configValue`, `displayName`, `showOnPanel`, `createdDate`, `modifiedDate`) VALUES (NULL, 'untappdBreweryId', '', 'Untappd Brewery Id', '0', NOW(), NOW());

ALTER TABLE `beers` ADD `untappdId` INT(11) AFTER `beerStyleId`;
CREATE OR REPLACE VIEW vwGetActiveTaps
AS

SELECT
	t.id,
	b.name,
	b.untappdId,
	bs.name as 'style',
	b.notes,
	t.ogAct,
	t.fgAct,
	t.srmAct,
	t.ibuAct,
	t.startAmount,
	IFNULL(p.amountPoured, 0) as amountPoured,
	t.startAmount - IFNULL(p.amountPoured, 0) as remainAmount,
	t.tapNumber,
	s.rgb as srmRgb
FROM taps t
	LEFT JOIN beers b ON b.id = t.beerId
	LEFT JOIN beerStyles bs ON bs.id = b.beerStyleId
	LEFT JOIN srmRgb s ON s.srm = t.srmAct
	LEFT JOIN vwGetTapsAmountPoured as p ON p.tapId = t.Id
WHERE t.active = true
ORDER BY t.tapNumber;
