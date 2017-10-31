SELECT
  `p`.`id`                                                   AS `proposal_id`,
  `pv`.`users_total`                                         AS `users_total`,
  `pv`.`negative`                                            AS `negative`,
  `pv`.`positive`                                            AS `positive`,
  (`pv`.`positive` / `pv`.`users_total`)                     AS `percents`,
  ((`pv`.`positive` + `pv`.`negative`) / `pv`.`users_total`) AS `voted`,
  `vt`.`percents_to_pass`                                    AS `percents_to_pass`,
  `vt`.`users_to_pass`                                       AS `users_to_pass`
FROM ((`proposal` `p` LEFT JOIN `vote_type` `vt` ON ((`p`.`vote_type_id` = `vt`.`id`))) LEFT JOIN `proposal_votes` `pv`
    ON ((`pv`.`id` = `p`.`id`)))