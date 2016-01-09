INSERT INTO `categories` (`id`, `parent_id`, `name`, `path`) VALUES
(1,	NULL,	'Cakes',	'cakes'),
(2,	NULL,	'Chocolate',	'chocolate'),
(3,	1,	'Wedding cakes',	'cakes/wedding-cakes'),
(4,	1,	'Birthday cakes',	'cakes/birthday-cakes'),
(5,	3,	'Small wedding cakes',	'cakes/wedding-cakes/small'),
(6,	3,	'Medium wedding cakes',	'cakes/wedding-cakes/medium'),
(7,	3,	'Big wedding cakes',	'cakes/wedding-cakes/big');

INSERT INTO `products` (`id`, `name`, `path`, `description`, `price`, `discount`) VALUES
(1,	'Muffin cake',  '',	'Tasty muffin.',	150,	0),
(2,	'Swiss chocolate',  '',	'From Swiss.',	98,	10);
