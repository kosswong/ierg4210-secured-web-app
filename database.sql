CREATE TABLE `categories` (
  `catid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `categories` (`catid`, `name`, `cname`) VALUES
(1, 'Beverages, Wine & Spirits', '飲品及酒類'),
(2, 'Groceries', '糧油雜貨'),
(3, 'Biscuits, Snacks & Confectionery', '餅乾、零食及糖果'),
(4, 'Household', '家居用品'),
(5, 'Baby Care', '嬰兒護理'),
(6, 'Health & Beauty Care', '保健及個人護理'),
(7, 'Frozen Food', '急凍食品'),
(8, 'Fresh Food', '新鮮食品'),
(9, 'Breakfast & Bakery', '早餐及麵包糕點'),
(10, 'Dairy, Chilled & Eggs', '乳製品、冷凍食品及雞蛋'),
(11, 'Pet Food & Care', '寵物食品及護理'),
(12, 'Home & Entertainment', '家庭及娛樂'),
(13, 'Clothing, Sports & Outdoors', '服裝, 運動及戶外用品'),
(14, 'Books, Gifts & Festive Products', '書籍、禮品及節日產品');

CREATE TABLE `products` (
  `pid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL DEFAULT 0,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `banding` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `origin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `capacity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `products` (`pid`, `catid`, `name`, `price`, `description`, `banding`, `origin`, `capacity`, `image`) VALUES
(1, 1, 'KOONUNGA HILL SHZ CAB', 80, 'Contains sulphites 14.5% ALC/VOL\r\nProduced with the aid of egg and milk products and traces may remain', 'PENFOLDS', 'Australia', '37.5CL', '1.jpg'),
(2, 1, 'ROSSI CALIFORNIA RED', 129, 'Keep in a cool and dry place and avoid direct sunlight.', 'CARLO ROSSI', 'United States', '3L', '2.jpg'),
(3, 2, 'Turkey Luncheon Meat', 26.9, 'Contain 3g fat per 100g, Store in a cool and dry place, avoid direct sunlight.', 'APIS', 'Spain', '220G', '3.jpg'),
(4, 2, 'Green Dot Dot Organic Laver', 23.9, 'Once opened, please keep refrigerated and keep away from high temperature and humid conditions. Please consume as soon as possible after opening.', 'GREEN DOT DOT ', 'China', '33G', '4.jpg'),
(5, 2, 'THAI HOM MALI RICE', 23.9, 'Eat rice is good', 'SILVERSPOON', 'Thailand', '1KG', '5.jpg'),
(6, 2, 'ORGANIC KETCHUP', 32.5, 'Approved by the United States Department of Agriculture (USDA), 100% manufactured by organic tomatoes. The manufacture process is strictly monitored. We ensure the tomatoes are of the highest standard starting from seeding, harvest till production.', 'HEINZ ', 'USA', '14OZ', '6.jpg');

ALTER TABLE `categories`
  ADD PRIMARY KEY (`catid`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`pid`);