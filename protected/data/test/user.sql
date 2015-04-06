--
--     Ingwie Phoenix, UID 1
--
INSERT INTO `tbl_users` (
    `id`, -- Ingwie
    `username`,
    `password`,
    `email`,
    `activkey`,
    `superuser`,
    `status`,
    `developer`,
    `create_at`
) VALUES (
    1,
    'IngwiePhoenix',
    '233595e872e9466463f46e129872e177',
    'ingwie2000@gmail.com',
    'This is a test.',
    3, -- Admin
    1, -- Active
    1, -- True
    1426215020 -- Friday, 13th March, 3.50 AM
);

INSERT INTO `tbl_user_profile` (
    `uID`,
    `skype`,
    `steam`,
    `psn`,
    `xboxlife`,
    `facebook`,
    `twitter`,
    `furaffinity`,
    `sofurry`,
    `about`
) VALUES (
    1, 'wlaningwie', 'IngwiePhoenix', 'TheIngwiePhoenix',
    'IngwiePhoenix', 'SexyXynu', 'IngwiePhoenix', 'ingwie2000',
    'Ingwie Phoenix', 'Meep, meep, *meep!!!*'
);
INSERT INTO `tbl_user_settings` (`id`) VALUES (1);
INSERT INTO `tbl_user_permissions` (`id`) VALUES (1);

--
--     Xynu, UID 2
--
INSERT INTO `tbl_users` (
    `id`, -- Xynu
    `username`,
    `password`,
    `email`,
    `activkey`,
    `superuser`,
    `status`,
    `developer`,
    `create_at`
) VALUES (
    2,
    'Xynu',
    '233595e872e9466463f46e129872e177',
    'xynu@gmail.com',
    'This is a test.',
    2, -- Admin
    1, -- Active
    1, -- True
    1426215020 -- Friday, 13th March, 3.50 AM
);
INSERT INTO `tbl_user_profile` ( `uID`, `about` )
VALUES ( 2, 'Yes yes, I **am** a whore. Why else do I wear <font color="red">red</font>? owo' );
INSERT INTO `tbl_user_settings` (`id`) VALUES (2);
INSERT INTO `tbl_user_permissions` (`id`) VALUES (2);
