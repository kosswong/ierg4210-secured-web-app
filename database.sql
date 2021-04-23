DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
                              `catid` int(11) NOT NULL,
                              `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                              `cname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `categories` (`catid`, `name`, `cname`) VALUES
(1, 'Beverages, Wine and Spirits', '飲品及酒類'),
(2, 'Groceries', '糧油雜貨'),
(3, 'Biscuits, Snacks and Confectionery', '餅乾、零食及糖果'),
(4, 'Household', '家居用品'),
(5, 'Baby Care', '嬰兒護理'),
(6, 'Health and Beauty Care', '保健及個人護理'),
(7, 'Frozen Food', '急凍食品'),
(8, 'Fresh Food', '新鮮食品'),
(9, 'Breakfast and Bakery', '早餐及麵包糕點'),
(10, 'Dairy, Chilled and Eggs', '乳製品、冷凍食品及雞蛋'),
(11, 'Pet Food and Care', '寵物食品及護理'),
(12, 'Home and Entertainment', '家庭及娛樂'),
(13, 'Clothing, Sports and Outdoors', '服裝, 運動及戶外用品'),
(14, 'Books, Gifts and Festive Products', '書籍、禮品及節日產品');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
                          `id` int(11) UNSIGNED NOT NULL,
                          `uid` int(11) NOT NULL,
                          `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                          `currency` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                          `salt` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                          `cart` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                          `total` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                          `completed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
                            `pid` int(11) NOT NULL,
                            `catid` int(11) NOT NULL,
                            `name` varchar(255) COLLATE utf8_unicode_ci NULL,
                            `price` double NOT NULL DEFAULT 0,
                            `description` text COLLATE utf8_unicode_ci NULL,
                            `banding` varchar(255) COLLATE utf8_unicode_ci NULL,
                            `origin` varchar(255) COLLATE utf8_unicode_ci NULL,
                            `capacity` varchar(255) COLLATE utf8_unicode_ci NULL,
                            `image` varchar(255) COLLATE utf8_unicode_ci NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `products` (`pid`, `catid`, `name`, `price`, `description`, `banding`, `origin`, `capacity`, `image`) VALUES
(1, 1, 'KOONUNGA HILL SHZ CAB', 80, 'Contains sulphites 14.5% ALC/VOL\r\nProduced with the aid of egg and milk products and traces may remain', 'PENFOLDS', 'Australia', '37.5CL', '1.jpg'),
(2, 1, 'ROSSI CALIFORNIA RED', 129, 'Keep in a cool and dry place and avoid direct sunlight.', 'CARLO ROSSI', 'United States', '3L', '2.jpg'),
(3, 2, 'Turkey Luncheon Meat', 26.9, 'Contain 3g fat per 100g, Store in a cool and dry place, avoid direct sunlight.', 'APIS', 'Spain', '220G', '3.jpg'),
(4, 2, 'Green Dot Dot Organic Laver', 23.9, 'Once opened, please keep refrigerated and keep away from high temperature and humid conditions. Please consume as soon as possible after opening.', 'GREEN DOT DOT ', 'China', '33G', '4.jpg'),
(5, 2, 'THAI HOM MALI RICE', 23.9, 'Eat rice is good', 'SILVERSPOON', 'Thailand', '1KG', '5.jpg'),
(6, 2, 'ORGANIC KETCHUP', 32.5, 'Approved by the United States Department of Agriculture (USDA), 100% manufactured by organic tomatoes. The manufacture process is strictly monitored. We ensure the tomatoes are of the highest standard starting from seeding, harvest till production.', 'HEINZ ', 'USA', '14OZ', '6.jpg');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
                         `userid` int(255) UNSIGNED NOT NULL,
                         `admin` int(11) NOT NULL,
                         `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                         `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                         `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                         `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                         `verified` int(11) NOT NULL DEFAULT 0,
                         `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                         `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                         `expried` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`userid`, `admin`, `email`, `name`, `password`, `salt`, `verified`, `ip`, `token`, `expried`) VALUES
(1, 1, 'test@test.com', '', 'e630e9a1dacee47939f6f6eafb481c5b613be815', '067f4b336ca73441', 0, '::1', '', '0000-00-00');

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
                              `id` int(11) NOT NULL,
                              `role` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user_roles` (`id`, `role`) VALUES
(1, 'admin'),
(2, 'editor'),
(3, 'user');


ALTER TABLE `categories`
    ADD PRIMARY KEY (`catid`);

ALTER TABLE `orders`
    ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

ALTER TABLE `products`
    ADD PRIMARY KEY (`pid`);

ALTER TABLE `users`
    ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `categories`
    MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE `orders`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

ALTER TABLE `products`
    MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `users`
    MODIFY `userid` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
