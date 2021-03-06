CREATE TABLE `events` (
  `evt_id` bigint(20) NOT NULL,
  `evt_trainer` text NOT NULL,
  `evt_lokaal` text NOT NULL,
  `evt_bezigheid` text NOT NULL,
  `evt_materiaal` text NOT NULL,
  `evt_andere` text NOT NULL,
  `evt_start` datetime NOT NULL,
  `evt_end` datetime NOT NULL,
  `evt_color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `events`
  ADD PRIMARY KEY (`evt_id`),
  ADD KEY `evt_start` (`evt_start`),
  ADD KEY `evt_end` (`evt_end`);

ALTER TABLE `events`
  MODIFY `evt_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;